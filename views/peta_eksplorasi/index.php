<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/peta_eksplorasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (Fast & Clean WebGIS Exploration View)
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/database.php';
?>

<div class="relative w-full h-[calc(100vh-140px)] min-h-[500px] rounded-3xl overflow-hidden border border-slate-200/80 shadow-md">

    <!-- FLOATING TOP FILTER BAR (MODERN APP FLOATING PILL) -->
    <div class="absolute top-3 left-3 right-3 z-20 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 pointer-events-none">
        <!-- Pencarian & Filter Cepat -->
        <div class="bg-white/95 backdrop-blur-md p-1.5 rounded-2xl shadow-lg border border-slate-200/80 flex items-center gap-2 pointer-events-auto w-full sm:w-auto">
            <div class="relative flex-1 sm:w-56">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="map-search" placeholder="Cari 875 objek wisata..." oninput="filterMapPlaces()" class="w-full text-xs font-semibold pl-8 pr-3 py-1.5 rounded-xl bg-slate-50 focus:bg-white focus:outline-none border-none">
            </div>

            <select id="map-filter-kab" onchange="filterMapPlaces()" class="text-xs font-semibold px-2.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 border-none focus:outline-none">
                <option value="">Semua Wilayah</option>
                <option value="Kabupaten Bangka">Bangka</option>
                <option value="Kabupaten Belitung">Belitung</option>
                <option value="Kabupaten Bangka Barat">Bangka Barat</option>
                <option value="Kabupaten Bangka Tengah">Bangka Tengah</option>
                <option value="Kabupaten Bangka Selatan">Bangka Selatan</option>
                <option value="Kabupaten Belitung Timur">Belitung Timur</option>
                <option value="Kota Pangkalpinang">Pangkalpinang</option>
            </select>
        </div>

        <!-- Mode Layer Tampilan Peta -->
        <div class="bg-white/95 backdrop-blur-md p-1.5 rounded-2xl shadow-lg border border-slate-200/80 flex items-center gap-1 pointer-events-auto self-end sm:self-auto">
            <button onclick="setMapMode('markers')" id="btn-mode-markers" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-ocean-600 text-white shadow-2xs transition-all">
                <i class="fa-solid fa-location-dot mr-1"></i> Pin
            </button>
            <button onclick="setMapMode('heatmap')" id="btn-mode-heatmap" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all">
                <i class="fa-solid fa-fire text-amber-500 mr-1"></i> Heatmap
            </button>
        </div>
    </div>

    <!-- PETA LEAFLET UTAMA -->
    <div id="main-webgis" class="w-full h-full bg-slate-100 relative z-10"></div>

    <!-- FLOATING INFO MODAL / BOTTOM SHEET DI MOBILE -->
    <div id="place-bottom-sheet" class="absolute bottom-3 left-3 right-3 sm:left-auto sm:right-4 sm:bottom-4 z-20 bg-white/95 backdrop-blur-md p-4 rounded-3xl shadow-2xl border border-slate-200/90 max-w-sm hidden transition-all">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <img id="sheet-foto" src="assets/img/bangka_belitung/pantai_tikus_emas.jpg" alt="" class="w-12 h-12 rounded-xl object-cover shrink-0 shadow-2xs">
                <div>
                    <span id="sheet-kategori" class="text-[10px] font-bold text-ocean-600 uppercase">Pantai &amp; Alam</span>
                    <h4 id="sheet-nama" class="text-sm font-bold text-slate-900 line-clamp-1">Pantai Tanjung Tinggi</h4>
                    <p id="sheet-lokasi" class="text-xs text-slate-500">Sijuk, Belitung</p>
                </div>
            </div>
            <button onclick="closeBottomSheet()" class="w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-1 font-bold text-slate-800">
                <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                <span id="sheet-rating">4.8</span>
                <span id="sheet-ulasan" class="text-[10px] text-slate-400 font-normal">(1.240 ulasan)</span>
            </div>
            <a id="sheet-gmaps" href="#" target="_blank" class="px-3 py-1.5 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-bold text-xs flex items-center gap-1.5">
                <span>Rute Google Maps</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

</div>

<!-- SCRIPT PETA EKSPLORASI -->
<script>
let gisMap = null;
let allPlaces = [];
let markerLayer = null;
let heatLayer = null;
let currentMode = 'markers';

document.addEventListener('DOMContentLoaded', () => {
    initGisMap();
    fetchPlaces();
});

function initGisMap() {
    gisMap = L.map('main-webgis', {
        zoomControl: false,
        attributionControl: false
    }).setView([-2.45, 106.8], 8);

    L.control.zoom({ position: 'bottomright' }).addTo(gisMap);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        maxZoom: 19
    }).addTo(gisMap);

    markerLayer = L.layerGroup().addTo(gisMap);
}

async function fetchPlaces() {
    try {
        const res = await fetch('api/v1_destinasi.php?limit=500');
        const json = await res.json();
        if (json.status === 'success') {
            allPlaces = json.data;
            renderGisMap();
        }
    } catch (e) {
        console.error('Gagal mengambil data destinasi:', e);
    }
}

function filterMapPlaces() {
    renderGisMap();
}

function renderGisMap() {
    if (!gisMap) return;

    markerLayer.clearLayers();
    if (heatLayer) gisMap.removeLayer(heatLayer);

    const q = (document.getElementById('map-search').value || '').toLowerCase();
    const kab = document.getElementById('map-filter-kab').value;

    const filtered = allPlaces.filter(p => {
        const matchQ = !q || p.nama.toLowerCase().includes(q) || (p.kecamatan && p.kecamatan.toLowerCase().includes(q));
        const matchKab = !kab || p.kabupaten === kab;
        return matchQ && matchKab;
    });

    if (currentMode === 'markers') {
        filtered.forEach(p => {
            const isDayaTarik = p.kelompok === 'Daya Tarik';
            const color = isDayaTarik ? '#0284c7' : '#f59e0b';

            const marker = L.circleMarker([p.latitude, p.longitude], {
                radius: 6,
                fillColor: color,
                color: '#ffffff',
                weight: 1.5,
                fillOpacity: 0.85
            });

            marker.on('click', () => {
                showBottomSheet(p);
            });

            markerLayer.addLayer(marker);
        });
    } else {
        const heatPoints = filtered.map(p => [p.latitude, p.longitude, (p.rating / 5.0)]);
        if (typeof L.heatLayer === 'function') {
            heatLayer = L.heatLayer(heatPoints, {
                radius: 25,
                blur: 16,
                maxZoom: 13,
                gradient: { 0.2: '#06b6d4', 0.5: '#84cc16', 0.7: '#eab308', 1.0: '#ec4899' }
            }).addTo(gisMap);
        }
    }
}

function setMapMode(mode) {
    currentMode = mode;
    const btnMarkers = document.getElementById('btn-mode-markers');
    const btnHeat = document.getElementById('btn-mode-heatmap');

    if (mode === 'markers') {
        btnMarkers.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-ocean-600 text-white shadow-2xs transition-all';
        btnHeat.className = 'px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all';
    } else {
        btnHeat.className = 'px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-500 text-white shadow-2xs transition-all';
        btnMarkers.className = 'px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all';
    }

    renderGisMap();
}

function showBottomSheet(p) {
    const sheet = document.getElementById('place-bottom-sheet');
    document.getElementById('sheet-foto').src = p.foto;
    document.getElementById('sheet-kategori').innerText = p.sub_kategori + ' (' + p.kelompok + ')';
    document.getElementById('sheet-nama').innerText = p.nama;
    document.getElementById('sheet-lokasi').innerText = (p.kecamatan ? p.kecamatan + ', ' : '') + p.kabupaten;
    document.getElementById('sheet-rating').innerText = p.rating;
    document.getElementById('sheet-ulasan').innerText = '(' + p.ulasan + ' ulasan)';
    document.getElementById('sheet-gmaps').href = `https://www.google.com/maps/dir/?api=1&destination=${p.latitude},${p.longitude}`;
    
    sheet.classList.remove('hidden');
}

function closeBottomSheet() {
    document.getElementById('place-bottom-sheet').classList.add('hidden');
}
</script>
