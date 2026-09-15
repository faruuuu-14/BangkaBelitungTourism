<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      skrip_peta.php
 * @folder    views/peta/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.5.0 (Logika WebGIS Lengkap: Hubs, Radar Amenitas, Google Maps, WA)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen pengendali logika sisi klien (Client-Side GIS Controller) terlengkap.
 * Menyediakan:
 * 1. Peta interaktif Leaflet dengan Tile Layer OpenStreetMap.
 * 2. Visualisasi Simpul Transportasi (2 Bandara Utama + 4 Pelabuhan Pintu Masuk).
 * 3. Buffer Lingkaran Aksesibilitas Kemenparekraf (15 km, 30 km, 50 km).
 * 4. Radar Amenitas Terdekat (mencari hotel & restoran dalam radius 5 km via Haversine).
 * 5. Integrasi Perencana Rute Multi-Stop terhubung langsung ke Navigasi Google Maps.
 * 6. Ekspor Rencana Perjalanan Wisata langsung ke WhatsApp.
 * 7. Filter Spasial (Kategori 3A, Kabupaten, Mode Pin vs Heatmap, Buffer 5 km).
 * 8. Deep-linking otomatis via URL (?kabupaten=..., ?search=..., ?lat=...&lng=...).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/peta/index.php
 * - Menerima variabel PHP: $places (875 titik) dan $hubs (simpul transportasi)
 * ==============================================================================
 */
?>

<script>
// --------------------------------------------------------------------------
// 1. DATA MENTAH DARI BASIS DATA MYSQL
// --------------------------------------------------------------------------
const rawPlaces = <?= json_encode($places ?? []) ?>;
const transportHubs = <?= json_encode($hubs ?? []) ?>;

// --------------------------------------------------------------------------
// 2. INISIALISASI PETA LEAFLET
// Pusatkan koordinat awal pada titik tengah Kepulauan Bangka Belitung (-2.5, 106.8)
// --------------------------------------------------------------------------
const map = L.map('map', {
    zoomControl: false
}).setView([-2.5, 106.8], 9);

L.control.zoom({ position: 'bottomright' }).addTo(map);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | Pariwisata Babel',
    maxZoom: 19
}).addTo(map);

// Layer Groups
const markerLayer = L.layerGroup().addTo(map);
const hubMarkersLayer = L.layerGroup().addTo(map);
const catchmentRingsLayer = L.layerGroup().addTo(map);
const bufferLayer = L.layerGroup();
const radarPulseLayer = L.layerGroup().addTo(map);

let heatLayer = null;
let itineraryPolyline = null;

// Status Global
let currentMode = 'pin';
let currentCategory = 'Semua';
let isBufferActive = false;
let isCatchmentActive = true;
let itineraryStops = [];
let currentRadarData = { hotels: [], restos: [] };

// --------------------------------------------------------------------------
// 3. IKON PIN KUSTOM (DESTINASI & SIMPUL TRANSPORTASI)
// --------------------------------------------------------------------------
function getMarkerColor(sub) {
    switch (sub) {
        case 'Alam': return '#10b981';
        case 'Buatan': return '#f59e0b';
        case 'Kebudayaan': return '#ec4899';
        case 'Hotel': return '#0ea5e9';
        case 'Restoran': return '#ef4444';
        default: return '#64748b';
    }
}

function getSubCategoryIcon(sub) {
    if (sub === 'Alam') return 'fa-umbrella-beach';
    if (sub === 'Buatan') return 'fa-camera';
    if (sub === 'Kebudayaan') return 'fa-landmark';
    if (sub === 'Hotel') return 'fa-hotel';
    if (sub === 'Restoran') return 'fa-utensils';
    return 'fa-map-pin';
}

function getClientPhoto(nama, sub, kab) {
    return 'assets/img/fathurrahman.jpg';
}

function createCustomIcon(sub) {
    const color = getMarkerColor(sub);
    const iconClass = getSubCategoryIcon(sub);

    const html = `
        <div style="background-color: ${color}; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.35);">
            <i class="fa-solid ${iconClass}" style="font-size: 12px;"></i>
        </div>
    `;

    return L.divIcon({
        className: 'custom-pin',
        html: html,
        iconSize: [28, 28],
        iconAnchor: [14, 28],
        popupAnchor: [0, -28]
    });
}

function createHubIcon(tipe) {
    const isAirport = (tipe === 'Bandara');
    const bg = isAirport ? '#7c3aed' : '#0891b2'; // Ungu untuk bandara, Cyan untuk pelabuhan
    const iconClass = isAirport ? 'fa-plane-departure' : 'fa-ship';

    const html = `
        <div style="background-color: ${bg}; width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; border: 2.5px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.4); transform: rotate(-5deg);">
            <i class="fa-solid ${iconClass}" style="font-size: 14px;"></i>
        </div>
    `;

    return L.divIcon({
        className: 'custom-hub-pin',
        html: html,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
}

// --------------------------------------------------------------------------
// 5. ALGORITMA HAVERSINE & ANALISIS KETERJANGKAUAN SIMPUL
// --------------------------------------------------------------------------
function calculateHaversine(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius bumi km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function getNearestHubClient(lat, lng) {
    let minAirportDist = Infinity;
    let nearestAirport = null;
    let minPortDist = Infinity;
    let nearestPort = null;

    transportHubs.forEach(h => {
        const d = calculateHaversine(lat, lng, parseFloat(h.latitude), parseFloat(h.longitude));
        if (h.tipe === 'Bandara') {
            if (d < minAirportDist) {
                minAirportDist = d;
                nearestAirport = { ...h, dist: d, mins: Math.round((d / 45) * 60) };
            }
        } else if (h.tipe === 'Pelabuhan') {
            if (d < minPortDist) {
                minPortDist = d;
                nearestPort = { ...h, dist: d, mins: Math.round((d / 45) * 60) };
            }
        }
    });

    let zoneLabel = 'Ring 4: Blank Spot (>50 km)';
    let zoneColor = '#e11d48';

    if (minAirportDist <= 15) {
        zoneLabel = 'Ring 1: Inti Urban (≤15 km)';
        zoneColor = '#10b981';
    } else if (minAirportDist <= 30) {
        zoneLabel = 'Ring 2: Koridor (15-30 km)';
        zoneColor = '#0ea5e9';
    } else if (minAirportDist <= 50) {
        zoneLabel = 'Ring 3: Terjangkau (30-50 km)';
        zoneColor = '#f59e0b';
    }

    return {
        airport: nearestAirport,
        port: nearestPort,
        zoneLabel,
        zoneColor
    };
}

// --------------------------------------------------------------------------
// 6. RENDER SIMPUL TRANSPORTASI (BANDARA & PELABUHAN)
// --------------------------------------------------------------------------
function renderHubMarkers(filterType = 'all') {
    hubMarkersLayer.clearLayers();
    if (filterType === 'none') return;

    transportHubs.forEach(h => {
        if (filterType === 'bandara' && h.tipe !== 'Bandara') return;
        if (filterType === 'pelabuhan' && h.tipe !== 'Pelabuhan') return;

        const lat = parseFloat(h.latitude);
        const lng = parseFloat(h.longitude);
        if (isNaN(lat) || isNaN(lng)) return;

        const marker = L.marker([lat, lng], {
            icon: createHubIcon(h.tipe),
            zIndexOffset: 1000
        });

        const badgeBg = (h.tipe === 'Bandara') ? '#7c3aed' : '#0891b2';
        const popupContent = `
            <div class="p-2 max-w-[260px] text-slate-800" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-xs" style="background-color: ${badgeBg}">
                        ${h.tipe} &bull; ${h.kode}
                    </span>
                    <span class="text-[10px] text-slate-400 font-semibold">${h.kabupaten}</span>
                </div>
                <h4 class="font-black text-sm text-slate-900 leading-snug mb-1">${h.nama}</h4>
                <p class="text-xs text-slate-600 mb-2 leading-relaxed">${h.deskripsi}</p>
                <div class="pt-1.5 border-t border-slate-100 flex gap-1.5">
                    <button onclick="focusOnHub('${h.id}', ${lat}, ${lng})" 
                        class="w-full py-1.5 px-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] shadow-sm flex items-center justify-center gap-1.5 transition">
                        <i class="fa-solid fa-bullseye"></i> Fokus Buffer Hub Ini
                    </button>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent, { maxWidth: 280, className: 'clean-popup' });
        hubMarkersLayer.addLayer(marker);
    });
}

function renderCatchmentRings(targetHubId = 'all') {
    catchmentRingsLayer.clearLayers();
    if (!isCatchmentActive) return;

    // Default tampilkan buffer 2 bandara utama (PGK & TJQ) agar tidak bertumpuk berlebihan
    const hubsToRender = (targetHubId === 'all')
        ? transportHubs.filter(h => h.tipe === 'Bandara')
        : transportHubs.filter(h => h.id === targetHubId);

    hubsToRender.forEach(h => {
        const lat = parseFloat(h.latitude);
        const lng = parseFloat(h.longitude);

        // Ring 1: 15 km (Inti Urban)
        L.circle([lat, lng], {
            radius: 15000,
            color: '#10b981',
            weight: 1.5,
            dashArray: '4, 4',
            fillColor: '#10b981',
            fillOpacity: 0.05
        }).bindTooltip(`Ring 1 (≤15 km): ${h.nama}`, { sticky: true }).addTo(catchmentRingsLayer);

        // Ring 2: 30 km (Koridor Menengah)
        L.circle([lat, lng], {
            radius: 30000,
            color: '#0ea5e9',
            weight: 1.5,
            dashArray: '6, 6',
            fillColor: '#0ea5e9',
            fillOpacity: 0.03
        }).bindTooltip(`Ring 2 (30 km): ${h.nama}`, { sticky: true }).addTo(catchmentRingsLayer);

        // Ring 3: 50 km (Terjangkau Harian)
        L.circle([lat, lng], {
            radius: 50000,
            color: '#f59e0b',
            weight: 1.2,
            dashArray: '8, 8',
            fillColor: '#f59e0b',
            fillOpacity: 0.02
        }).bindTooltip(`Ring 3 (50 km): ${h.nama}`, { sticky: true }).addTo(catchmentRingsLayer);
    });
}

function toggleCatchmentRings() {
    isCatchmentActive = !isCatchmentActive;
    const btn = document.getElementById('btnCatchment');
    if (isCatchmentActive) {
        map.addLayer(catchmentRingsLayer);
        renderCatchmentRings('all');
        if (btn) btn.className = 'px-2.5 py-1.5 rounded-xl border border-purple-200 bg-purple-600 text-white text-[11px] font-bold transition-all shadow-xs flex items-center gap-1';
    } else {
        map.removeLayer(catchmentRingsLayer);
        if (btn) btn.className = 'px-2.5 py-1.5 rounded-xl border border-purple-200 bg-purple-50 hover:bg-purple-100 text-purple-800 text-[11px] font-bold transition-all shadow-xs flex items-center gap-1';
    }
}

function focusOnHub(hubId, lat, lng) {
    renderCatchmentRings(hubId);
    map.flyTo([lat, lng], 11, { duration: 1.2 });
}

function onHubFilterChange() {
    const val = document.getElementById('hubFilter')?.value || 'all';
    renderHubMarkers(val);
}

// --------------------------------------------------------------------------
// 7. RENDER POPUP DESTINASI DENGAN RADAR AMENITAS & SIMPUL
// --------------------------------------------------------------------------
function createPopupContent(place) {
    const color = getMarkerColor(place.sub_kategori);
    const icon = getSubCategoryIcon(place.sub_kategori);
    const access = getNearestHubClient(parseFloat(place.latitude), parseFloat(place.longitude));

    const safeNama = (place.nama || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    const safeSub = (place.sub_kategori || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    const safeKab = (place.kabupaten || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');

    // Tombol Radar Amenitas (Khusus kelompok Daya Tarik / Wisata)
    let radarButtonHtml = '';
    if (place.kelompok === 'Daya Tarik') {
        radarButtonHtml = `
            <button onclick="findNearbyAmenities(${place.id})" 
                class="w-full py-1.5 px-2 rounded-xl bg-gradient-to-r from-ocean-600 to-emerald-600 hover:from-ocean-700 hover:to-emerald-700 text-white font-bold text-[11px] transition shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-radar text-amber-300"></i>
                <span>Radar Amenitas Terdekat</span>
            </button>
        `;
    }

    return `
        <div class="overflow-hidden rounded-2xl max-w-[280px] text-slate-800" style="font-family: 'Plus Jakarta Sans', sans-serif;">
            <div class="p-3.5 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 text-white relative">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white shrink-0 shadow-sm" style="background-color: ${color};">
                            <i class="fa-solid ${icon} text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[9px] font-extrabold uppercase tracking-wider block" style="color: ${color};">${place.sub_kategori}</span>
                            <h4 class="font-black text-xs text-white leading-snug line-clamp-2">${place.nama}</h4>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-amber-400 text-xs font-black flex items-center gap-0.5 justify-end">
                            ★ ${parseFloat(place.rating).toFixed(1)}
                        </span>
                        <span class="px-1.5 py-0.5 rounded text-[8px] font-extrabold text-white mt-1 inline-block shadow-2xs" style="background-color: ${access.zoneColor};">
                            ${access.zoneLabel}
                        </span>
                    </div>
                </div>
                <div class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-[9px] text-slate-500"></i>
                    <span class="truncate">${place.kabupaten} &bull; Kec. ${place.kecamatan}</span>
                </div>
            </div>
            <div class="p-3 space-y-2 bg-white">

                <!-- Aksesibilitas Simpul Transportasi Kemenparekraf -->
                <div class="p-2 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1 text-[10px]">
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="font-bold flex items-center gap-1 text-purple-700">
                            <i class="fa-solid fa-plane-departure text-[9px]"></i> ${access.airport ? access.airport.kode : 'Bandara'}:
                        </span>
                        <span class="font-semibold text-slate-800">${access.airport ? access.airport.dist.toFixed(1) + ' km (~' + access.airport.mins + ' mnt)' : '-'}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-600">
                        <span class="font-bold flex items-center gap-1 text-cyan-700">
                            <i class="fa-solid fa-ship text-[9px]"></i> ${access.port ? access.port.kode : 'Pelabuhan'}:
                        </span>
                        <span class="font-semibold text-slate-800">${access.port ? access.port.dist.toFixed(1) + ' km (~' + access.port.mins + ' mnt)' : '-'}</span>
                    </div>
                </div>

                <div class="space-y-1.5 pt-1">
                    ${radarButtonHtml}
                    <div class="flex gap-1.5">
                        <button onclick="addToItinerary(${place.id}, '${safeNama}', ${place.latitude}, ${place.longitude}, '${safeSub}', '${safeKab}')" 
                            class="flex-1 py-1.5 px-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-[10px] transition-colors flex items-center justify-center gap-1">
                            <i class="fa-solid fa-plus text-amber-300"></i> Tambah Rute
                        </button>
                        <button onclick="openQrModal('${safeNama}', ${place.latitude}, ${place.longitude}, '${safeKab}', '${safeSub}')" 
                            class="p-1.5 px-2.5 rounded-xl bg-ocean-50 hover:bg-ocean-100 text-ocean-700 text-[10px] font-bold transition-colors flex items-center justify-center" title="Kode QR Navigasi Pintar">
                            <i class="fa-solid fa-qrcode text-xs"></i>
                        </button>
                        <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.nama + ' ' + place.kabupaten)}" target="_blank"
                            class="p-1.5 px-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold transition-colors flex items-center justify-center" title="Buka di Google Maps">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// --------------------------------------------------------------------------
// 8. RADAR AMENITAS TERDEKAT (RADIUS 5 KM HAVERSINE)
// --------------------------------------------------------------------------
function findNearbyAmenities(placeId) {
    const origin = rawPlaces.find(p => p.id == placeId);
    if (!origin) return;

    const originLat = parseFloat(origin.latitude);
    const originLng = parseFloat(origin.longitude);

    // Gambarkan Lingkaran Radar di Peta
    radarPulseLayer.clearLayers();
    const pulseCircle = L.circle([originLat, originLng], {
        radius: 5000,
        color: '#059669',
        weight: 2,
        fillColor: '#10b981',
        fillOpacity: 0.15
    }).addTo(radarPulseLayer);

    map.flyTo([originLat, originLng], 12, { duration: 1.0 });

    // Hitung seluruh amenitas (Hotel & Resto)
    const hotels = [];
    const restos = [];

    rawPlaces.forEach(p => {
        if (p.kelompok === 'Amenitas' && p.latitude && p.longitude) {
            const dist = calculateHaversine(originLat, originLng, parseFloat(p.latitude), parseFloat(p.longitude));
            if (dist <= 15.0) { // Cari hingga 15 km terdekat
                const item = { ...p, dist: dist, mins: Math.round((dist / 35) * 60) };
                if (p.sub_kategori === 'Hotel') hotels.push(item);
                else if (p.sub_kategori === 'Restoran') restos.push(item);
            }
        }
    });

    hotels.sort((a, b) => a.dist - b.dist);
    restos.sort((a, b) => a.dist - b.dist);

    currentRadarData = {
        origin: origin,
        hotels: hotels.slice(0, 8),
        restos: restos.slice(0, 8)
    };

    // Update Header Modal
    document.getElementById('radarOriginName').innerText = `${origin.nama} (${origin.kabupaten})`;
    document.getElementById('radarHotelCount').innerText = currentRadarData.hotels.length;
    document.getElementById('radarRestoCount').innerText = currentRadarData.restos.length;

    // Render Tab Hotel terlebih dahulu
    switchRadarTab('hotel');

    // Buka Modal Radar
    document.getElementById('amenityRadarModal').classList.remove('hidden');
}

function closeAmenityRadar() {
    document.getElementById('amenityRadarModal').classList.add('hidden');
    radarPulseLayer.clearLayers();
}

function switchRadarTab(type) {
    const tabHotel = document.getElementById('tabRadarHotel');
    const tabResto = document.getElementById('tabRadarResto');
    const container = document.getElementById('radarListContainer');

    if (type === 'hotel') {
        tabHotel.className = 'flex-1 py-1.5 rounded-xl font-bold text-xs bg-sky-600 text-white shadow-xs flex items-center justify-center gap-1.5 transition';
        tabResto.className = 'flex-1 py-1.5 rounded-xl font-bold text-xs bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 flex items-center justify-center gap-1.5 transition';
        renderRadarList(currentRadarData.hotels, 'Hotel');
    } else {
        tabResto.className = 'flex-1 py-1.5 rounded-xl font-bold text-xs bg-orange-600 text-white shadow-xs flex items-center justify-center gap-1.5 transition';
        tabHotel.className = 'flex-1 py-1.5 rounded-xl font-bold text-xs bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 flex items-center justify-center gap-1.5 transition';
        renderRadarList(currentRadarData.restos, 'Restoran');
    }
}

function renderRadarList(items, type) {
    const container = document.getElementById('radarListContainer');
    if (!items || items.length === 0) {
        container.innerHTML = `
            <div class="py-8 text-center text-slate-400 space-y-1">
                <i class="fa-solid fa-circle-question text-2xl text-slate-300"></i>
                <p class="text-xs">Tidak ditemukan fasilitas ${type} dalam radius 15 km dari titik ini.</p>
            </div>
        `;
        return;
    }

    let html = '';
    items.forEach(item => {
        const color = getMarkerColor(item.sub_kategori);
        const icon = getSubCategoryIcon(item.sub_kategori);
        const safeNama = item.nama.replace(/'/g, "\\'");
        const safeSub = (item.sub_kategori || '').replace(/'/g, "\\'");
        const safeKab = (item.kabupaten || '').replace(/'/g, "\\'");

        html += `
            <div class="p-2.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3 text-xs hover:bg-white transition-all shadow-2xs">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shrink-0 shadow-xs" style="background-color: ${color};">
                        <i class="fa-solid ${icon} text-xs"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-extrabold text-slate-900 truncate">${item.nama}</div>
                        <div class="text-[10px] text-slate-500 flex items-center gap-1.5 mt-0.5">
                            <span class="font-bold text-ocean-700 flex items-center gap-0.5">
                                <i class="fa-solid fa-location-arrow text-[9px]"></i> ${item.dist.toFixed(1)} km (~${item.mins} mnt)
                            </span>
                            <span>&bull;</span>
                            <span class="text-amber-500 font-bold">★ ${parseFloat(item.rating).toFixed(1)}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button onclick="addToItinerary(${item.id}, '${safeNama}', ${item.latitude}, ${item.longitude}, '${safeSub}', '${safeKab}')"
                        class="px-2.5 py-1.5 bg-slate-900 hover:bg-ocean-600 text-white rounded-xl text-[10px] font-bold transition flex items-center gap-1" title="Tambah ke Rute">
                        <i class="fa-solid fa-plus text-amber-300"></i> Rute
                    </button>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${item.latitude},${item.longitude}" target="_blank"
                        class="p-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-[10px] font-bold transition" title="Rute Google Maps Langsung">
                        <i class="fa-solid fa-diamond-turn-right"></i>
                    </a>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// --------------------------------------------------------------------------
// 9. RENDER MARKER & FILTER SPASIAL
// --------------------------------------------------------------------------
function renderMarkers(data) {
    markerLayer.clearLayers();
    bufferLayer.clearLayers();

    data.forEach(p => {
        const lat = parseFloat(p.latitude);
        const lng = parseFloat(p.longitude);
        if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) return;

        const marker = L.marker([lat, lng], {
            icon: createCustomIcon(p.sub_kategori)
        });

        marker.bindPopup(createPopupContent(p), { maxWidth: 290, className: 'clean-popup' });
        markerLayer.addLayer(marker);

        // Buffer Lingkaran 5 km untuk Hotel & Restoran
        if (p.kelompok === 'Amenitas' || p.sub_kategori === 'Hotel' || p.sub_kategori === 'Restoran') {
            const circle = L.circle([lat, lng], {
                radius: 5000,
                color: '#0ea5e9',
                weight: 1,
                fillColor: '#38bdf8',
                fillOpacity: 0.08
            });
            bufferLayer.addLayer(circle);
        }
    });

    const badge = document.getElementById('counterBadge');
    if (badge) badge.innerText = `${data.length} Titik`;
}

function applyFilter() {
    const searchVal = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const kabVal = document.getElementById('kabFilter')?.value || '';

    const filtered = rawPlaces.filter(p => {
        const matchesSearch = !searchVal || p.nama.toLowerCase().includes(searchVal) || (p.kecamatan || '').toLowerCase().includes(searchVal);
        const matchesKab = !kabVal || p.kabupaten === kabVal;
        const matchesCat = currentCategory === 'Semua' || p.sub_kategori === currentCategory;
        return matchesSearch && matchesKab && matchesCat;
    });

    if (currentMode === 'pin') {
        renderMarkers(filtered);
    } else {
        renderHeatmap(filtered);
    }
}

function filterCategory(cat) {
    currentCategory = cat;
    document.querySelectorAll('.cat-chip').forEach(btn => {
        const txt = btn.innerText.trim();
        if (txt === cat || (cat === 'Kebudayaan' && txt === 'Budaya')) {
            btn.classList.remove('bg-emerald-50', 'bg-rose-50', 'bg-amber-50', 'bg-sky-50', 'bg-orange-50', 'border');
            btn.classList.add('bg-slate-900', 'text-white', 'shadow-xs');
        } else {
            btn.classList.remove('bg-slate-900', 'text-white', 'shadow-xs');
        }
    });
    applyFilter();
}

function setMapMode(mode) {
    currentMode = mode;
    const btnPin = document.getElementById('btnModePin');
    const btnHeat = document.getElementById('btnModeHeat');

    if (mode === 'pin') {
        btnPin.classList.add('bg-white', 'text-slate-900', 'shadow-xs');
        btnPin.classList.remove('text-slate-600');
        btnHeat.classList.remove('bg-white', 'text-slate-900', 'shadow-xs');
        btnHeat.classList.add('text-slate-600');

        if (heatLayer) map.removeLayer(heatLayer);
        map.addLayer(markerLayer);
        applyFilter();
    } else {
        btnHeat.classList.add('bg-white', 'text-slate-900', 'shadow-xs');
        btnHeat.classList.remove('text-slate-600');
        btnPin.classList.remove('bg-white', 'text-slate-900', 'shadow-xs');
        btnPin.classList.add('text-slate-600');

        map.removeLayer(markerLayer);
        applyFilter();
    }
}

function renderHeatmap(data) {
    if (heatLayer) map.removeLayer(heatLayer);

    const heatPoints = data
        .filter(p => p.latitude && p.longitude)
        .map(p => {
            const ulasan = parseInt(p.ulasan) || 0;
            // Skala logaritmik agar titik dengan ulasan puluhan/ratusan menyala terang & berkontras tinggi
            // Bobot minimum 0.45 agar semua titik destinasi langsung terlihat cerah dan mencolok
            const weight = Math.min(1.0, 0.45 + (Math.log10(Math.max(1, ulasan)) / 3.5) * 0.55);
            return [parseFloat(p.latitude), parseFloat(p.longitude), weight];
        });

    if (typeof L.heatLayer === 'function') {
        heatLayer = L.heatLayer(heatPoints, {
            radius: 28,
            blur: 18,
            maxZoom: 13,
            minOpacity: 0.55, // Opasitas minimum 55% agar tidak ada titik pudar yang menyatu dengan latar laut/daratan
            gradient: {
                0.2:  '#facc15', // Kuning Emas Terang (Vivid Yellow) - Sangat kontras di atas laut & darat
                0.45: '#fb923c', // Oranye Terang (Bright Flame)
                0.7:  '#ef4444', // Merah Membara (Vivid Red)
                0.88: '#be123c', // Merah Ruby Pekat (Deep Crimson)
                1.0:  '#581c87'  // Ungu Elektrik Puncak Kepadatan (Electric Purple Core)
            }
        }).addTo(map);
    }
}

function toggleBuffer() {
    isBufferActive = !isBufferActive;
    const btn = document.getElementById('btnBuffer');

    if (isBufferActive) {
        map.addLayer(bufferLayer);
        btn.classList.add('bg-ocean-600', 'text-white', 'border-ocean-600');
        btn.classList.remove('bg-white', 'text-slate-700');
    } else {
        map.removeLayer(bufferLayer);
        btn.classList.remove('bg-ocean-600', 'text-white', 'border-ocean-600');
        btn.classList.add('bg-white', 'text-slate-700');
    }
}

// --------------------------------------------------------------------------
// 10. PERENCANA RUTE CERDAS TERINTEGRASI GOOGLE MAPS & WHATSAPP
// --------------------------------------------------------------------------
function toggleItineraryDrawer() {
    const drawer = document.getElementById('itineraryDrawer');
    if (drawer) drawer.classList.toggle('translate-x-full');
}

function addToItinerary(id, nama, lat, lng, sub, kab) {
    if (itineraryStops.some(s => s.id === id)) {
        alert('Destinasi ini sudah ada dalam daftar rencana rute kamu.');
        return;
    }

    itineraryStops.push({ id, nama, lat: parseFloat(lat), lng: parseFloat(lng), sub, kab });
    updateItineraryUI();

    const drawer = document.getElementById('itineraryDrawer');
    if (drawer && drawer.classList.contains('translate-x-full')) {
        drawer.classList.remove('translate-x-full');
    }
}

function removeFromItinerary(index) {
    itineraryStops.splice(index, 1);
    updateItineraryUI();
    if (itineraryPolyline) {
        map.removeLayer(itineraryPolyline);
        itineraryPolyline = null;
    }
}

function clearItinerary() {
    itineraryStops = [];
    updateItineraryUI();
    if (itineraryPolyline) {
        map.removeLayer(itineraryPolyline);
        itineraryPolyline = null;
    }
}

function updateItineraryUI() {
    const badge = document.getElementById('itineraryCountBadge');
    const list = document.getElementById('itineraryList');
    const empty = document.getElementById('itineraryEmpty');
    const totalDistEl = document.getElementById('itineraryTotalDist');
    const totalTimeEl = document.getElementById('itineraryTotalTime');
    const btnDraw = document.getElementById('btnDrawRoute');
    const btnGmaps = document.getElementById('btnGmapsNav');
    const btnWa = document.getElementById('btnShareWa');

    if (badge) badge.innerText = itineraryStops.length;

    if (itineraryStops.length === 0) {
        if (empty) empty.classList.remove('hidden');
        if (totalDistEl) totalDistEl.innerText = '0.0 km';
        if (totalTimeEl) totalTimeEl.innerText = '0 Menit';
        if (btnDraw) btnDraw.disabled = true;
        if (btnGmaps) {
            btnGmaps.href = '#';
            btnGmaps.classList.add('pointer-events-none', 'opacity-50');
        }
        if (btnWa) btnWa.disabled = true;
        list.innerHTML = '';
        return;
    }

    if (empty) empty.classList.add('hidden');
    if (btnDraw) btnDraw.disabled = (itineraryStops.length < 2);
    if (btnWa) btnWa.disabled = false;

    // Hitung total akumulasi jarak
    let totalDist = 0;
    for (let i = 0; i < itineraryStops.length - 1; i++) {
        totalDist += calculateHaversine(
            itineraryStops[i].lat, itineraryStops[i].lng,
            itineraryStops[i+1].lat, itineraryStops[i+1].lng
        );
    }

    const totalMinutes = Math.round((totalDist / 45) * 60);

    if (totalDistEl) totalDistEl.innerText = `${totalDist.toFixed(1)} km`;
    if (totalTimeEl) totalTimeEl.innerText = `~${totalMinutes} Menit`;

    // Hubungkan tautan multi-stop Google Maps
    if (btnGmaps) {
        const coords = itineraryStops.map(s => `${s.lat},${s.lng}`);
        const gmapsUrl = `https://www.google.com/maps/dir/${coords.join('/')}`;
        btnGmaps.href = gmapsUrl;
        btnGmaps.classList.remove('pointer-events-none', 'opacity-50');
    }

    // Render HTML kartu perhentian
    let html = '';
    itineraryStops.forEach((stop, i) => {
        const color = getMarkerColor(stop.sub);
        const icon = getSubCategoryIcon(stop.sub);
        html += `
            <div class="p-2.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="w-5 h-5 rounded-full bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                        ${i + 1}
                    </span>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shrink-0 shadow-2xs" style="background-color: ${color};">
                        <i class="fa-solid ${icon} text-xs"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="font-extrabold text-slate-900 truncate">${stop.nama}</div>
                        <div class="text-[10px] text-slate-400">${stop.kab || stop.kabupaten || ''} &bull; ${stop.sub}</div>
                    </div>
                </div>
                <button onclick="removeFromItinerary(${i})" class="w-6 h-6 rounded-full bg-slate-200 hover:bg-rose-100 hover:text-rose-600 text-slate-500 flex items-center justify-center transition-colors text-[10px] shrink-0 cursor-pointer">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
    });

    list.innerHTML = html;
}

function drawItineraryRoute() {
    if (itineraryStops.length < 2) return;

    if (itineraryPolyline) map.removeLayer(itineraryPolyline);

    const latlngs = itineraryStops.map(s => [s.lat, s.lng]);

    itineraryPolyline = L.polyline(latlngs, {
        color: '#0284c7',
        weight: 4,
        dashArray: '8, 8',
        opacity: 0.9
    }).addTo(map);

    map.fitBounds(itineraryPolyline.getBounds(), { padding: [50, 50] });
    toggleItineraryDrawer();
}

function shareItineraryWhatsApp() {
    if (itineraryStops.length === 0) return;

    let totalDist = 0;
    for (let i = 0; i < itineraryStops.length - 1; i++) {
        totalDist += calculateHaversine(
            itineraryStops[i].lat, itineraryStops[i].lng,
            itineraryStops[i+1].lat, itineraryStops[i+1].lng
        );
    }
    const totalMins = Math.round((totalDist / 45) * 60);

    const coords = itineraryStops.map(s => `${s.lat},${s.lng}`);
    const gmapsUrl = `https://www.google.com/maps/dir/${coords.join('/')}`;

    let msg = `🌴 *Rencana Perjalanan Wisata Bangka Belitung* 🌴\n`;
    msg += `Disusun melalui Portal Intelijen Pariwisata Babel SDSS\n\n`;
    msg += `📍 *Daftar Kunjungan Destinasi:*\n`;

    itineraryStops.forEach((s, idx) => {
        msg += `${idx + 1}. *${s.nama}* (${s.sub}, ${s.kab})\n`;
    });

    msg += `\n📏 *Estimasi Total Jarak:* ${totalDist.toFixed(1)} km\n`;
    msg += `⏱️ *Estimasi Waktu Tempuh:* ~${totalMins} Menit\n`;
    msg += `🗺️ *Navigasi Google Maps Multi-Stop:*\n${gmapsUrl}\n\n`;
    msg += `_Semoga perjalanan wisatamu di Bangka Belitung menyenangkan!_`;

    const waUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(msg)}`;
    window.open(waUrl, '_blank');
}

// --------------------------------------------------------------------------
// 11. INITIAL LOAD & URL QUERY PARAMETERS DEEP-LINKING
// --------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    // 1. Render data awal
    renderMarkers(rawPlaces);
    renderHubMarkers('all');
    renderCatchmentRings('all');

    // 2. Baca URL Query Parameters
    const urlParams = new URLSearchParams(window.location.search);
    const kabParam = urlParams.get('kabupaten');
    const searchParam = urlParams.get('search') || urlParams.get('q');
    const latParam = parseFloat(urlParams.get('lat'));
    const lngParam = parseFloat(urlParams.get('lng'));

    // A. Deep link filter Kabupaten
    if (kabParam) {
        const kabSelect = document.getElementById('kabFilter');
        if (kabSelect) {
            kabSelect.value = kabParam;
            applyFilter();
            const firstInKab = rawPlaces.find(p => p.kabupaten.toLowerCase() === kabParam.toLowerCase());
            if (firstInKab && firstInKab.latitude && firstInKab.longitude) {
                map.flyTo([parseFloat(firstInKab.latitude), parseFloat(firstInKab.longitude)], 11, { duration: 1.5 });
            }
        }
    }

    // B. Deep link pencarian kata kunci
    if (searchParam) {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.value = searchParam;
            applyFilter();
            const match = rawPlaces.find(p => p.nama.toLowerCase().includes(searchParam.toLowerCase()));
            if (match && match.latitude && match.longitude) {
                map.flyTo([parseFloat(match.latitude), parseFloat(match.longitude)], 13, { duration: 1.5 });
            }
        }
    }

    // C. Deep link koordinat spesifik
    if (!isNaN(latParam) && !isNaN(lngParam)) {
        map.flyTo([latParam, lngParam], 14, { duration: 1.5 });
    }
});
</script>
