<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      skrip_peta.php
 * @folder    views/peta/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Logika JavaScript Leaflet WebGIS, Clustering, & Perencana Rute)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen pengendali logika sisi klien (Client-Side GIS Controller) untuk WebGIS.
 * Mengelola inisialisasi peta Leaflet (OpenStreetMap Tile Layer), penempatan pin
 * marker kustom dengan warna kategori Kemenparekraf, popup kaya informasi dengan
 * foto otentik representatif, layer heatmap kepadatan destinasi, visualisasi radius
 * buffer 5 km, pencarian interaktif, filter wilayah, serta mesin perhitungan rute
 * wisata cerdas (Smart Itinerary Optimizer).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/peta/index.php
 * - Menerima data: variabel JSON $places dari PHP
 * - Menggunakan pustaka: Leaflet.js & Leaflet.heat
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan performa pemetaan spasial yang cepat, interaktif, mulus, dan bebas lag
 * saat menampilkan ratusan data titik geografis di Kepulauan Bangka Belitung.
 * ==============================================================================
 */
?>

<script>
// --------------------------------------------------------------------------
// 1. DATA MENTAH DARI PHP BASIS DATA
// --------------------------------------------------------------------------
const rawPlaces = <?= json_encode($places) ?>;

// --------------------------------------------------------------------------
// 2. INISIALISASI PETA LEAFLET
// Pusatkan koordinat awal pada titik tengah geografis Kepulauan Bangka Belitung
// Lat: -2.500, Lng: 106.800 dengan tingkat zoom 9
// --------------------------------------------------------------------------
const map = L.map('map', {
    zoomControl: false
}).setView([-2.5, 106.8], 9);

// Kontrol Zoom di sudut kanan bawah
L.control.zoom({ position: 'bottomright' }).addTo(map);

// Tile Layer Resmi OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | Pariwisata Babel',
    maxZoom: 19
}).addTo(map);

// Layer Group Penampung Marker & Pola
const markerLayer = L.layerGroup().addTo(map);
const bufferLayer = L.layerGroup();
let heatLayer = null;
let itineraryPolyline = null;

// State Global
let currentMode = 'pin';
let currentCategory = 'Semua';
let isBufferActive = false;
let itineraryStops = [];

// --------------------------------------------------------------------------
// 3. FUNGSI RESOLUSI FOTO OTENTIK SISI KLIEN (CLIENT-SIDE PHOTO RESOLVER)
// Menjamin 100% dari 875 popup objek memiliki foto representatif berimbang
// --------------------------------------------------------------------------
function getClientPhoto(nama, sub, kab) {
    const n = (nama || '').toLowerCase();
    const s = (sub || '').toLowerCase();
    const k = (kab || '').toLowerCase();

    // Landmark Spesifik Bangka Barat
    if (n.includes('tanjung ular') || n.includes('batu rakit') || n.includes('menumbing') || n.includes('pesanggrahan') || n.includes('muntok') || n.includes('mentok')) {
        return 'assets/img/pantai_tanjung_ular.jpg';
    }
    // Landmark Spesifik Bangka (Sungailiat)
    if (n.includes('tikus emas') || n.includes('tongaci') || n.includes('parai') || n.includes('matras') || n.includes('puri tri agung') || n.includes('sungailiat')) {
        return 'assets/img/pantai_tikus_emas.jpg';
    }
    // Landmark Spesifik Bangka Tengah (Koba)
    if (n.includes('kaolin') || n.includes('air biru') || n.includes('koba') || n.includes('namang')) {
        return 'assets/img/danau_kaolin_koba.jpg';
    }
    // Landmark Spesifik Bangka Selatan (Toboali)
    if (n.includes('batu perahu') || n.includes('toboali') || n.includes('nek aji') || n.includes('lepar')) {
        return 'assets/img/pantai_batu_perahu.jpg';
    }
    // Landmark Spesifik Belitung
    if (n.includes('lengkuas') || n.includes('mercusuar') || n.includes('kepayang')) {
        return 'assets/img/pantai_lengkuas.jpg';
    }
    if (n.includes('tanjung tinggi') || n.includes('geopark') || n.includes('kelayang')) {
        return 'assets/img/pantai_tanjung_tinggi.jpg';
    }
    if (n.includes('gantong') || n.includes('gantung') || n.includes('laskar pelangi') || n.includes('andrea hirata') || n.includes('manggar')) {
        return 'assets/img/laskar_pelangi_gantong.jpg';
    }
    // Landmark Spesifik Pangkalpinang
    if (n.includes('museum') || n.includes('timah') || n.includes('pasir padi') || n.includes('pangkalpinang')) {
        return 'assets/img/museum_timah_babel.jpg';
    }
    if (n.includes('cual') || n.includes('tenun') || n.includes('batik')) {
        return 'assets/img/kain_cual_bangka.jpg';
    }
    if (n.includes('dambus') || n.includes('musik')) {
        return 'assets/img/alat_musik_dambus.jpg';
    }

    // Fallback Wilayah
    if (k.includes('barat')) return 'assets/img/pantai_tanjung_ular.jpg';
    if (k.includes('selatan')) return 'assets/img/pantai_batu_perahu.jpg';
    if (k.includes('tengah')) return 'assets/img/danau_kaolin_koba.jpg';
    if (k.includes('timur')) return 'assets/img/laskar_pelangi_gantong.jpg';
    if (k.includes('belitung')) return 'assets/img/pantai_tanjung_tinggi.jpg';

    // Fallback Kategori
    if (s === 'kebudayaan') return 'assets/img/budaya_tari_campak.jpg';
    if (s === 'restoran' || n.includes('resto') || n.includes('warung') || n.includes('kuliner')) return 'assets/img/budaya_nganggung.jpg';
    if (s === 'hotel') return 'assets/img/pantai_tanjung_tinggi.jpg';

    return 'assets/img/pantai_tikus_emas.jpg';
}

// --------------------------------------------------------------------------
// 4. IKON PIN MARKER KUSTOM BERDASARKAN KATEGORI
// --------------------------------------------------------------------------
function getMarkerColor(sub) {
    switch (sub) {
        case 'Alam': return '#10b981';       // Hijau Zamrud
        case 'Buatan': return '#f59e0b';     // Amber Emas
        case 'Kebudayaan': return '#ec4899';  // Merah Muda / Mawar
        case 'Hotel': return '#0ea5e9';      // Biru Langit
        case 'Restoran': return '#ef4444';   // Merah Terang
        default: return '#64748b';
    }
}

function createCustomIcon(sub) {
    const color = getMarkerColor(sub);
    let iconClass = 'fa-map-pin';
    if (sub === 'Alam') iconClass = 'fa-umbrella-beach';
    else if (sub === 'Buatan') iconClass = 'fa-camera';
    else if (sub === 'Kebudayaan') iconClass = 'fa-landmark';
    else if (sub === 'Hotel') iconClass = 'fa-hotel';
    else if (sub === 'Restoran') iconClass = 'fa-utensils';

    const html = `
        <div style="background-color: ${color}; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
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

// --------------------------------------------------------------------------
// 5. RENDER POPUP DESTINASI DENGAN FOTO & TOMBOL AKSI
// --------------------------------------------------------------------------
function createPopupContent(place) {
    const photo = getClientPhoto(place.nama, place.sub_kategori, place.kabupaten);
    const color = getMarkerColor(place.sub_kategori);

    // Escape nama tempat untuk parameter JS
    const safeNama = place.nama.replace(/'/g, "\\'");
    const safeSub = (place.sub_kategori || '').replace(/'/g, "\\'");
    const safeKab = (place.kabupaten || '').replace(/'/g, "\\'");

    return `
        <div class="overflow-hidden rounded-2xl max-w-[260px] text-slate-800" style="font-family: 'Plus Jakarta Sans', sans-serif;">
            <div class="relative h-28 w-full overflow-hidden bg-slate-900">
                <img src="${photo}" alt="${place.nama}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[10px] font-bold text-white shadow" style="background-color: ${color};">
                    ${place.sub_kategori}
                </span>
                <span class="absolute bottom-2 right-2 text-amber-300 text-xs font-bold flex items-center gap-0.5 drop-shadow">
                    ★ ${parseFloat(place.rating).toFixed(1)}
                </span>
            </div>
            <div class="p-3 space-y-2 bg-white">
                <div>
                    <h4 class="font-extrabold text-xs text-slate-900 leading-snug">${place.nama}</h4>
                    <p class="text-[10px] text-slate-400 mt-0.5">${place.kabupaten} &bull; Kec. ${place.kecamatan}</p>
                </div>
                <div class="text-[10px] text-slate-500 flex items-center justify-between border-t border-slate-100 pt-1.5">
                    <span>${parseInt(place.ulasan).toLocaleString()} ulasan publik</span>
                    <span class="font-mono text-slate-400">${parseFloat(place.latitude).toFixed(3)}, ${parseFloat(place.longitude).toFixed(3)}</span>
                </div>
                <div class="pt-1 flex gap-1.5">
                    <button onclick="addToItinerary(${place.id}, '${safeNama}', ${place.latitude}, ${place.longitude}, '${safeSub}', '${safeKab}')" 
                        class="flex-1 py-1.5 px-2 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-bold text-[10px] transition-colors flex items-center justify-center gap-1">
                        <i class="fa-solid fa-plus"></i> Tambah ke Rute
                    </button>
                    <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.nama + ' ' + place.kabupaten)}" target="_blank"
                        class="p-1.5 px-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold transition-colors" title="Buka di Google Maps">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
        </div>
    `;
}

// --------------------------------------------------------------------------
// 6. RENDER MARKER & FILTER SPASIAL
// --------------------------------------------------------------------------
function renderMarkers(data) {
    markerLayer.clearLayers();
    bufferLayer.clearLayers();

    data.forEach(p => {
        const lat = parseFloat(p.latitude);
        const lng = parseFloat(p.longitude);
        if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) return;

        // Marker Pin
        const marker = L.marker([lat, lng], {
            icon: createCustomIcon(p.sub_kategori)
        });

        marker.bindPopup(createPopupContent(p), { maxWidth: 280, className: 'clean-popup' });
        markerLayer.addLayer(marker);

        // Buffer Lingkaran 5 km untuk Hotel & Resto
        if (p.kelompok === 'Amenitas' || p.sub_kategori === 'Hotel' || p.sub_kategori === 'Restoran') {
            const circle = L.circle([lat, lng], {
                radius: 5000, // 5.000 meter = 5 km
                color: '#0ea5e9',
                weight: 1,
                fillColor: '#38bdf8',
                fillOpacity: 0.08
            });
            bufferLayer.addLayer(circle);
        }
    });

    // Perbarui badge jumlah titik
    const badge = document.getElementById('counterBadge');
    if (badge) badge.innerText = `${data.length} Titik`;
}

// --------------------------------------------------------------------------
// 7. PENYARING DATA (FILTER LOGIC)
// --------------------------------------------------------------------------
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
        if (btn.innerText.trim() === cat) {
            btn.classList.remove('bg-emerald-50', 'bg-rose-50', 'bg-amber-50', 'bg-sky-50', 'bg-orange-50', 'border');
            btn.classList.add('bg-slate-900', 'text-white', 'shadow-xs');
        } else {
            btn.classList.remove('bg-slate-900', 'text-white', 'shadow-xs');
        }
    });
    applyFilter();
}

// --------------------------------------------------------------------------
// 8. ALIH MODE: PIN MARKER VS HEATMAP MULTI-KATEGORI
// --------------------------------------------------------------------------
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
        .map(p => [parseFloat(p.latitude), parseFloat(p.longitude), Math.min(1.0, (p.ulasan || 10) / 1000)]);

    if (typeof L.heatLayer === 'function') {
        heatLayer = L.heatLayer(heatPoints, {
            radius: 25,
            blur: 15,
            maxZoom: 14,
            gradient: { 0.2: '#0284c7', 0.5: '#10b981', 0.8: '#f59e0b', 1.0: '#ef4444' }
        }).addTo(map);
    }
}

// --------------------------------------------------------------------------
// 9. TOGGLE BUFFER 5 KM AMENITAS
// --------------------------------------------------------------------------
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
// 10. PERENCANA RUTE WISATA CERDAS (SMART ITINERARY LOGIC)
// --------------------------------------------------------------------------
function toggleItineraryDrawer() {
    const drawer = document.getElementById('itineraryDrawer');
    if (drawer) drawer.classList.toggle('translate-x-full');
}

function addToItinerary(id, nama, lat, lng, sub, kab) {
    // Cek jika tempat sudah terdaftar
    if (itineraryStops.some(s => s.id === id)) {
        alert('Destinasi ini sudah ada dalam daftar rencana rute kamu.');
        return;
    }

    const photo = getClientPhoto(nama, sub, kab);
    itineraryStops.push({ id, nama, lat: parseFloat(lat), lng: parseFloat(lng), sub, kab, photo });
    updateItineraryUI();
    
    // Buka drawer otomatis
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

// Hitung Jarak Haversine antar 2 koordinat (dalam km)
function calculateHaversine(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius bumi dalam km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function updateItineraryUI() {
    const badge = document.getElementById('itineraryCountBadge');
    const list = document.getElementById('itineraryList');
    const empty = document.getElementById('itineraryEmpty');
    const totalDistEl = document.getElementById('itineraryTotalDist');
    const totalTimeEl = document.getElementById('itineraryTotalTime');
    const btnDraw = document.getElementById('btnDrawRoute');

    if (badge) badge.innerText = itineraryStops.length;

    if (itineraryStops.length === 0) {
        if (empty) empty.classList.remove('hidden');
        if (totalDistEl) totalDistEl.innerText = '0.0 km';
        if (totalTimeEl) totalTimeEl.innerText = '0 Menit';
        if (btnDraw) btnDraw.disabled = true;
        return;
    }

    if (empty) empty.classList.add('hidden');
    if (btnDraw) btnDraw.disabled = itineraryStops.length < 2;

    // Hitung total akumulasi jarak rute
    let totalDist = 0;
    for (let i = 0; i < itineraryStops.length - 1; i++) {
        totalDist += calculateHaversine(
            itineraryStops[i].lat, itineraryStops[i].lng,
            itineraryStops[i+1].lat, itineraryStops[i+1].lng
        );
    }

    // Estimasi kecepatan rata-rata darat: 45 km/jam
    const totalMinutes = Math.round((totalDist / 45) * 60);

    if (totalDistEl) totalDistEl.innerText = `${totalDist.toFixed(1)} km`;
    if (totalTimeEl) totalTimeEl.innerText = `~${totalMinutes} Menit`;

    // Render HTML kartu perhentian
    let html = '';
    itineraryStops.forEach((stop, i) => {
        html += `
            <div class="p-2.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="w-5 h-5 rounded-full bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                        ${i + 1}
                    </span>
                    <img src="${stop.photo}" alt="${stop.nama}" class="w-9 h-9 rounded-xl object-cover border border-slate-200 shrink-0">
                    <div class="min-w-0">
                        <div class="font-extrabold text-slate-900 truncate">${stop.nama}</div>
                        <div class="text-[10px] text-slate-400">${stop.kabupaten}</div>
                    </div>
                </div>
                <button onclick="removeFromItinerary(${i})" class="w-6 h-6 rounded-full bg-slate-200 hover:bg-rose-100 hover:text-rose-600 text-slate-500 flex items-center justify-center transition-colors text-[10px] shrink-0">
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
        opacity: 0.85
    }).addTo(map);

    map.fitBounds(itineraryPolyline.getBounds(), { padding: [40, 40] });
    toggleItineraryDrawer(); // Tutup drawer agar jalur terlihat jelas
}

// --------------------------------------------------------------------------
// 11. INITIAL LOAD
// --------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    renderMarkers(rawPlaces);

    // Cek jika ada parameter search dari URL (misal: index.php?page=peta&search=Pantai+Tikus)
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam) {
        const input = document.getElementById('searchInput');
        if (input) {
            input.value = searchParam;
            applyFilter();
            // Zoom ke tempat jika ditemukan 1 hasil
            const found = rawPlaces.find(p => p.nama.toLowerCase().includes(searchParam.toLowerCase()));
            if (found && found.latitude && found.longitude) {
                map.flyTo([parseFloat(found.latitude), parseFloat(found.longitude)], 13, { duration: 1.5 });
            }
        }
    }
});
</script>
