<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/radar_bisnis/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (Spatial Business Suitability Radar View - Novelty Pilar 3)
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/EvaluasiSpasialBisnis.php';
?>

<div class="space-y-6 pb-20 md:pb-10">

    <!-- 1. HEADER HERO RADAR BISNIS -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 border border-slate-800 p-5 sm:p-7 shadow-lg text-white">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-bold tracking-wide">
                    <i class="fa-solid fa-chart-pie text-[10px]"></i>
                    <span>Novelty Skripsi &bull; Spatial Multi-Criteria Evaluation (SMCE)</span>
                </div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                    Radar Potensi Bisnis <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-sky-400 bg-clip-text text-transparent">&amp; UMKM Wisata</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Sistem rekomendasi geospasial penentu titik lokasi strategis pendirian usaha pariwisata baru (Kafe, Homestay, dan Sentra Oleh-oleh) berbasis <strong class="text-white">Analisis Kesenjangan Pasar (*Market Gap*)</strong> dan radius keterjangkauan wisatawan.
                </p>
            </div>
            
            <div class="flex items-center gap-2 shrink-0">
                <a href="#peta-radar-bisnis" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-2 transition-all shadow-md active:scale-95">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Lihat Peta Peluang</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. PEMILIH MODEL BISNIS & WILAYAH -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900">Pilih Model Bisnis yang Ingin Dianalisis</h2>
                <p class="text-xs text-slate-500">Sistem akan menyesuaikan radius tarikan wisatawan dan jarak pantau kompetitor</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-400">Wilayah:</span>
                <select id="radar-kabupaten" onchange="runRadarEvaluation()" class="text-xs font-semibold px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:outline-none">
                    <option value="">Semua Wilayah Babel</option>
                    <option value="Kabupaten Bangka">Kab. Bangka</option>
                    <option value="Kabupaten Belitung">Kab. Belitung</option>
                    <option value="Kabupaten Bangka Barat">Kab. Bangka Barat</option>
                    <option value="Kabupaten Bangka Tengah">Kab. Bangka Tengah</option>
                    <option value="Kabupaten Bangka Selatan">Kab. Bangka Selatan</option>
                    <option value="Kabupaten Belitung Timur">Kab. Belitung Timur</option>
                    <option value="Kota Pangkalpinang">Kota Pangkalpinang</option>
                </select>
            </div>
        </div>

        <!-- 3 Kartu Model Bisnis -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <label class="cursor-pointer">
                <input type="radio" name="tipe_usaha" value="kafe_wisata" checked onchange="runRadarEvaluation()" class="peer sr-only">
                <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/40 transition-all space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-mug-saucer"></i>
                    </div>
                    <div class="font-bold text-slate-900 text-xs sm:text-sm">Kafe &amp; Resto Tepi Wisata</div>
                    <p class="text-[11px] text-slate-500 line-clamp-2">Radius tarikan 4 km &bull; Pantau resto 2 km</p>
                    <div class="text-[10px] font-bold text-emerald-700 font-mono pt-1">Modal: ~Rp 75 - 250 Jt</div>
                </div>
            </label>

            <label class="cursor-pointer">
                <input type="radio" name="tipe_usaha" value="homestay" onchange="runRadarEvaluation()" class="peer sr-only">
                <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/40 transition-all space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-house-chimney"></i>
                    </div>
                    <div class="font-bold text-slate-900 text-xs sm:text-sm">Homestay &amp; Penginapan</div>
                    <p class="text-[11px] text-slate-500 line-clamp-2">Radius tarikan 6 km &bull; Pantau hotel 3 km</p>
                    <div class="text-[10px] font-bold text-emerald-700 font-mono pt-1">Modal: ~Rp 150 - 500 Jt</div>
                </div>
            </label>

            <label class="cursor-pointer">
                <input type="radio" name="tipe_usaha" value="sentra_umkm" onchange="runRadarEvaluation()" class="peer sr-only">
                <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/40 transition-all space-y-1.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div class="font-bold text-slate-900 text-xs sm:text-sm">Sentra Oleh-oleh &amp; Kriya</div>
                    <p class="text-[11px] text-slate-500 line-clamp-2">Radius tarikan 7 km &bull; Pantau toko 4 km</p>
                    <div class="text-[10px] font-bold text-emerald-700 font-mono pt-1">Modal: ~Rp 50 - 150 Jt</div>
                </div>
            </label>
        </div>
    </div>

    <!-- 3. PETA SPASIAL HEATMAP KELAYAKAN LOKASI (LEAFLET) -->
    <div id="peta-radar-bisnis" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-satellite-dish text-emerald-600"></i>
                    <span>Peta Sebaran Peluang &amp; Indeks Kelayakan Spasial (SSI)</span>
                </h3>
                <p class="text-xs text-slate-500">Warna hijau/panas menunjukkan peluang tinggi (banyak wisatawan, minim kompetitor)</p>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Peluang Emas</span>
                <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-sky-500 inline-block"></span> Potensial</span>
                <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span> Kompetitif</span>
            </div>
        </div>

        <div id="radar-map" class="w-full h-80 sm:h-96 bg-slate-100 relative z-10"></div>
    </div>

    <!-- 4. TOP 10 REKOMENDASI TITIK LOKASI STRATEGIS -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
        <div class="border-b border-slate-100 pb-3">
            <h3 class="text-sm sm:text-base font-bold text-slate-900">Rekomendasi Titik Hotspot Investasi Teratas</h3>
            <p class="text-xs text-slate-500">Berdasarkan rasio kesenjangan pasar (Market Gap Ratio = Demand / (Competitor + 1))</p>
        </div>

        <div id="radar-loading" class="py-10 text-center space-y-2">
            <div class="inline-block w-8 h-8 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs font-bold text-slate-600">Mengkalkulasi buffer spasial dan kepadatan kompetitor...</p>
        </div>

        <div id="radar-cards" class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
            <!-- Diisi JavaScript -->
        </div>
    </div>

</div>

<!-- SCRIPT RADAR BISNIS & LEAFLET MAP -->
<script>
let radarMap = null;
let radarHeatLayer = null;
let radarMarkersGroup = null;

document.addEventListener('DOMContentLoaded', () => {
    initRadarMap();
    runRadarEvaluation();
});

function initRadarMap() {
    if (radarMap) return;

    // Koordinat pusat Bangka Belitung
    radarMap = L.map('radar-map', {
        zoomControl: true,
        scrollWheelZoom: false
    }).setView([-2.45, 106.8], 8);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
        maxZoom: 19
    }).addTo(radarMap);

    radarMarkersGroup = L.layerGroup().addTo(radarMap);
}

async function runRadarEvaluation() {
    const tipeUsaha = document.querySelector('input[name="tipe_usaha"]:checked').value;
    const kab = document.getElementById('radar-kabupaten').value;

    const loading = document.getElementById('radar-loading');
    const cards = document.getElementById('radar-cards');

    loading.classList.remove('hidden');
    cards.innerHTML = '';

    try {
        const url = `api/v1_kelayakan_bisnis.php?tipe_usaha=${encodeURIComponent(tipeUsaha)}&kabupaten=${encodeURIComponent(kab)}`;
        const res = await fetch(url);
        const data = await res.json();

        loading.classList.add('hidden');

        if (data.status !== 'success') {
            cards.innerHTML = `<div class="p-4 rounded-xl bg-rose-50 text-rose-700 text-xs">${data.pesan}</div>`;
            return;
        }

        renderRadarResults(data);
    } catch (err) {
        loading.classList.add('hidden');
        cards.innerHTML = `<div class="p-4 rounded-xl bg-rose-50 text-rose-700 text-xs">Error: ${err.message}</div>`;
    }
}

function renderRadarResults(data) {
    const cards = document.getElementById('radar-cards');
    const recs = data.top_rekomendasi;

    // Render Peta Heatmap & Markers
    if (radarMap) {
        if (radarHeatLayer) radarMap.removeLayer(radarHeatLayer);
        radarMarkersGroup.clearLayers();

        // Titik Heatmap
        if (typeof L.heatLayer === 'function' && data.titik_heatmap) {
            radarHeatLayer = L.heatLayer(data.titik_heatmap, {
                radius: 28,
                blur: 18,
                maxZoom: 14,
                gradient: { 0.2: '#0ea5e9', 0.5: '#10b981', 0.8: '#f59e0b', 1.0: '#ef4444' }
            }).addTo(radarMap);
        }

        // Tambah pin untuk top 10 rekomendasi
        recs.forEach(item => {
            const marker = L.circleMarker([item.latitude, item.longitude], {
                radius: 8,
                fillColor: item.warna_zona,
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.9
            });

            marker.bindPopup(`
                <div class="p-1 space-y-1 text-xs">
                    <strong class="text-slate-900 block">${item.nama_lokasi}</strong>
                    <div class="text-[10px] text-slate-500">${item.kecamatan}, ${item.kabupaten}</div>
                    <div class="text-emerald-700 font-bold">Skor Kelayakan (SSI): ${item.skor_ssi}%</div>
                    <p class="text-[10px] text-slate-600">${item.arahan_investasi}</p>
                </div>
            `);

            radarMarkersGroup.addLayer(marker);
        });

        if (recs.length > 0) {
            radarMap.setView([recs[0].latitude, recs[0].longitude], 10);
        }
    }

    // Render Kartu Top 10
    let html = '';
    recs.forEach(item => {
        html += `
            <div class="p-4 rounded-2xl bg-slate-50 hover:bg-emerald-50/40 border border-slate-200/80 transition-all space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center">
                                #${item.rank}
                            </span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-white" style="background-color: ${item.warna_zona}">
                                ${item.status_kelayakan}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 mt-1">${item.nama_lokasi}</h4>
                        <p class="text-xs text-slate-500">${item.kecamatan}, ${item.kabupaten}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-black text-emerald-600 font-mono">${item.skor_ssi}%</div>
                        <span class="text-[10px] text-slate-400 font-medium">Skor SSI</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-[11px] p-2.5 rounded-xl bg-white border border-slate-200/60">
                    <div>
                        <span class="text-slate-400 block">Tarikan Wisata Sekitar:</span>
                        <strong class="text-slate-800">${item.nearby_wisata} Objek</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Pesaing Sejenis Sekitar:</span>
                        <strong class="text-slate-800">${item.nearby_pesaing} Usaha</strong>
                    </div>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed">
                    <i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i>
                    ${item.arahan_investasi}
                </p>
            </div>
        `;
    });

    cards.innerHTML = html;
}
</script>
