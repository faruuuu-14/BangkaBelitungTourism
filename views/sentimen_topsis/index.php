<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/sentimen_topsis/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (Sentiment-Driven TOPSIS View - Novelty Pilar 2)
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/AnalisisSentimenTopsis.php';
?>

<div class="space-y-6 pb-20 md:pb-10">

    <!-- 1. HEADER HERO SENTIMENT TOPSIS -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 border border-slate-800 p-5 sm:p-7 shadow-lg text-white">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-bold tracking-wide">
                    <i class="fa-solid fa-chart-line text-[10px]"></i>
                    <span>Novelty Skripsi &bull; Hybrid NLP Sentiment-Driven MCDM</span>
                </div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                    SPK Rekomendasi <span class="bg-gradient-to-r from-indigo-400 via-purple-300 to-pink-400 bg-clip-text text-transparent">TOPSIS Sentimen</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Sistem Pendukung Keputusan ilmiah yang memadukan rating bintang dengan <strong class="text-white">Analisis Sentimen Aspek Ulasan Nyata</strong> (kebersihan, fasilitas, dan aksesibilitas) untuk menentukan peringkat destinasi secara objektif.
                </p>
            </div>
            
            <div class="flex items-center gap-2 shrink-0">
                <button onclick="toggleBobotModal()" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs flex items-center gap-2 transition-all shadow-md active:scale-95">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Atur Bobot Kriteria</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. FILTER WILAYAH & PRESET CEPAT -->
    <div class="space-y-3">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-4 sm:p-5 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-slate-500 mr-1">Wilayah:</span>
                <select id="filter-kabupaten-topsis" onchange="loadTopsisRanking()" class="text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Wilayah (Bangka &amp; Belitung)</option>
                    <option value="Kabupaten Bangka">Kab. Bangka</option>
                    <option value="Kabupaten Belitung">Kab. Belitung</option>
                    <option value="Kabupaten Bangka Barat">Kab. Bangka Barat</option>
                    <option value="Kabupaten Bangka Tengah">Kab. Bangka Tengah</option>
                    <option value="Kabupaten Bangka Selatan">Kab. Bangka Selatan</option>
                    <option value="Kabupaten Belitung Timur">Kab. Belitung Timur</option>
                    <option value="Kota Pangkalpinang">Kota Pangkalpinang</option>
                </select>

                <select id="filter-kategori-topsis" onchange="loadTopsisRanking()" class="text-xs font-semibold px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    <option value="Alam">Wisata Alam</option>
                    <option value="Kebudayaan">Wisata Kebudayaan / Cagar Budaya</option>
                    <option value="Buatan">Wisata Buatan / Rekreasi</option>
                </select>
            </div>

            <!-- Preset Buttons -->
            <div class="flex flex-wrap items-center gap-1.5 self-start md:self-auto">
                <span class="text-[11px] font-bold text-slate-400 mr-1">Preset Bobot:</span>
                <button id="btn-preset-sentimen" onclick="applyPreset('sentimen')" class="px-3 py-1.5 text-[11px] font-black rounded-xl bg-indigo-600 text-white shadow-xs ring-2 ring-indigo-300 ring-offset-1 transition-all">
                    Kualitas Ulasan
                </button>
                <button id="btn-preset-populer" onclick="applyPreset('populer')" class="px-3 py-1.5 text-[11px] font-semibold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-all">
                    Terpopuler
                </button>
                <button id="btn-preset-seimbang" onclick="applyPreset('seimbang')" class="px-3 py-1.5 text-[11px] font-semibold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-all">
                    Seimbang
                </button>
                <button id="btn-preset-fasilitas" onclick="applyPreset('fasilitas')" class="px-3 py-1.5 text-[11px] font-semibold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-all">
                    Fasilitas &amp; Akses
                </button>
            </div>
        </div>

        <!-- Live Active Weights Badge -->
        <div class="flex flex-wrap items-center justify-between text-[11px] bg-indigo-50/70 border border-indigo-100/80 rounded-2xl px-4 py-2.5 text-indigo-950">
            <div class="flex flex-wrap items-center gap-2">
                <span class="font-black flex items-center gap-1.5 text-indigo-700">
                    <i class="fa-solid fa-scale-balanced text-xs"></i>
                    <span id="label-preset-aktif">Preset Aktif: Kualitas Ulasan &amp; Reputasi</span>
                </span>
                <span class="text-indigo-300 hidden sm:inline">&bull;</span>
                <span id="detail-bobot-aktif" class="font-mono text-slate-600 font-semibold text-[10px] sm:text-[11px]">
                    C1: 15% | C2 (Sentimen): 50% | C3: 10% | C4: 15% | C5: 10%
                </span>
            </div>
            <button onclick="toggleBobotModal()" class="text-indigo-600 font-bold hover:underline flex items-center gap-1 text-[11px] mt-1 sm:mt-0">
                <span>Atur Kustom</span>
                <i class="fa-solid fa-sliders text-[10px]"></i>
            </button>
        </div>
    </div>

    <!-- 3. PODIUM JUARA TOP 3 -->
    <div id="podium-section" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Diisi secara dinamis oleh JavaScript -->
    </div>

    <!-- 4. TABEL PERINGKAT LENGKAP -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900">Daftar Peringkat TOPSIS &amp; Skor Sentimen</h3>
                <p class="text-xs text-slate-500">Nilai preferensi kedekatan relatif ($V_i$) terhadap solusi ideal positif</p>
            </div>
            <button onclick="toggleTransparansiModal()" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1.5">
                <i class="fa-solid fa-square-root-variable"></i>
                <span class="hidden sm:inline">Transparansi Rumus</span>
            </button>
        </div>

        <!-- Loading -->
        <div id="loading-topsis" class="py-12 text-center space-y-2">
            <div class="inline-block w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs font-bold text-slate-600">Menghitung matriks normalisasi terbobot &amp; jarak Euclidean...</p>
        </div>

        <!-- Tabel Responsif -->
        <div id="table-container" class="overflow-x-auto hidden">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3.5">Peringkat &amp; Destinasi</th>
                        <th class="px-4 py-3.5">Wilayah</th>
                        <th class="px-4 py-3.5">Rating Bintang</th>
                        <th class="px-4 py-3.5">Sentimen Positif (NSS)</th>
                        <th class="px-4 py-3.5">Aksesibilitas</th>
                        <th class="px-4 py-3.5 text-right">Skor Preferensi ($V_i$)</th>
                    </tr>
                </thead>
                <tbody id="topsis-tbody" class="divide-y divide-slate-100 font-medium">
                    <!-- Diisi JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL PENGATURAN BOBOT KRITERIA -->
<div id="modal-bobot" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl space-y-5 animate-scale-up">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-indigo-600"></i>
                <span>Atur Bobot Kriteria TOPSIS</span>
            </h3>
            <button onclick="toggleBobotModal()" class="w-7 h-7 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <div class="space-y-4 text-xs">
            <div>
                <div class="flex justify-between font-bold text-slate-700 mb-1">
                    <span>C1: Rating Pengunjung (Bintang)</span>
                    <span id="label-w-c1" class="text-indigo-600 font-mono">25%</span>
                </div>
                <input type="range" id="slider-w-c1" min="5" max="60" value="25" oninput="updateBobotVal()" class="w-full accent-indigo-600">
            </div>

            <div>
                <div class="flex justify-between font-bold text-slate-700 mb-1">
                    <span>C2: Sentimen Positif Ulasan (NSS)</span>
                    <span id="label-w-c2" class="text-indigo-600 font-mono">30%</span>
                </div>
                <input type="range" id="slider-w-c2" min="5" max="60" value="30" oninput="updateBobotVal()" class="w-full accent-indigo-600">
                <p class="text-[10px] text-slate-400 mt-0.5">Novelty Skripsi: Menimbang teks keluhan &amp; kepuasan nyata</p>
            </div>

            <div>
                <div class="flex justify-between font-bold text-slate-700 mb-1">
                    <span>C3: Popularitas (Volume Ulasan)</span>
                    <span id="label-w-c3" class="text-indigo-600 font-mono">15%</span>
                </div>
                <input type="range" id="slider-w-c3" min="5" max="60" value="15" oninput="updateBobotVal()" class="w-full accent-indigo-600">
            </div>

            <div>
                <div class="flex justify-between font-bold text-slate-700 mb-1">
                    <span>C4: Kelengkapan Fasilitas</span>
                    <span id="label-w-c4" class="text-indigo-600 font-mono">15%</span>
                </div>
                <input type="range" id="slider-w-c4" min="5" max="60" value="15" oninput="updateBobotVal()" class="w-full accent-indigo-600">
            </div>

            <div>
                <div class="flex justify-between font-bold text-slate-700 mb-1">
                    <span>C5: Aksesibilitas Geospasial</span>
                    <span id="label-w-c5" class="text-indigo-600 font-mono">15%</span>
                </div>
                <input type="range" id="slider-w-c5" min="5" max="60" value="15" oninput="updateBobotVal()" class="w-full accent-indigo-600">
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex gap-2">
            <button onclick="resetBobotDefault()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">Reset Default</button>
            <button onclick="applyCustomBobot()" class="flex-1 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-bold text-white shadow-sm">Terapkan Bobot</button>
        </div>
    </div>
</div>

<!-- MODAL TRANSPARANSI RUMUS MATEMATIS TOPSIS -->
<div id="modal-transparansi" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5 hidden">
    <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-3xl p-5 sm:p-7 shadow-2xl space-y-5 overflow-y-auto animate-scale-up">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-square-root-variable text-indigo-600"></i>
                    <span>Transparansi Komputasi Matematis TOPSIS Sentimen</span>
                </h3>
                <p class="text-xs text-slate-500">Tahapan kalkulasi kuantitatif hybrid NLP Sentiment Lexicon + Vector Normalized TOPSIS</p>
            </div>
            <button onclick="toggleTransparansiModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- 6 Langkah Formula TOPSIS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-black">1</span>
                    <span>Matriks Keputusan (X)</span>
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed">Mengevaluasi 5 Kriteria Benefit: C1 (Rating), C2 (Sentimen NSS), C3 (Log10 Ulasan), C4 (Fasilitas), C5 (Aksesibilitas Geospasial).</p>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-black">2</span>
                    <span>Normalisasi Vektor (R)</span>
                </div>
                <div class="font-mono text-indigo-600 text-[11px] bg-white px-2 py-1 rounded-lg border border-slate-200">
                    r<sub>ij</sub> = x<sub>ij</sub> / &radic;( &Sigma; x<sub>kj</sub>&sup2; )
                </div>
                <p class="text-[10px] text-slate-400">Meniadakan perbedaan satuan skala.</p>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-black">3</span>
                    <span>Normalisasi Terbobot (Y)</span>
                </div>
                <div class="font-mono text-indigo-600 text-[11px] bg-white px-2 py-1 rounded-lg border border-slate-200">
                    y<sub>ij</sub> = w<sub>j</sub> &times; r<sub>ij</sub>
                </div>
                <p class="text-[10px] text-slate-400">&Sigma; w<sub>j</sub> = 1.0 (100% total bobot).</p>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-black">4</span>
                    <span>Solusi Ideal (A⁺ &amp; A⁻)</span>
                </div>
                <div class="font-mono text-indigo-600 text-[11px] bg-white px-2 py-1 rounded-lg border border-slate-200">
                    A⁺ = max(y<sub>ij</sub>), &nbsp; A⁻ = min(y<sub>ij</sub>)
                </div>
                <p class="text-[10px] text-slate-400">Seluruh kriteria bertipe benefit.</p>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-black">5</span>
                    <span>Jarak Euclidean (D⁺ &amp; D⁻)</span>
                </div>
                <div class="font-mono text-indigo-600 text-[11px] bg-white px-2 py-1 rounded-lg border border-slate-200">
                    D<sub>i</sub> = &radic;( &Sigma; (y<sub>ij</sub> - a<sub>j</sub>)&sup2; )
                </div>
                <p class="text-[10px] text-slate-400">Jarak geometris ke solusi ideal.</p>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-black">6</span>
                    <span>Nilai Preferensi (V<sub>i</sub>)</span>
                </div>
                <div class="font-mono text-indigo-600 text-[11px] bg-white px-2 py-1 rounded-lg border border-slate-200">
                    V<sub>i</sub> = D<sub>i</sub>⁻ / ( D<sub>i</sub>⁺ + D<sub>i</sub>⁻ )
                </div>
                <p class="text-[10px] text-slate-400">Mendekati 1.0 = Sangat Unggul.</p>
            </div>
        </div>

        <!-- Vektor Solusi Ideal Aktif -->
        <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-4 space-y-2 text-xs">
            <div class="font-bold text-indigo-900 flex items-center justify-between">
                <span>Vektor Solusi Ideal Hasil Perhitungan Aktif:</span>
                <span id="transparansi-kandidat-info" class="text-[11px] font-mono text-indigo-600 font-semibold"></span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 font-mono text-[11px]">
                <div class="p-2.5 bg-white rounded-xl border border-indigo-200 text-emerald-800">
                    <span class="font-bold">A⁺ (Solusi Ideal Positif):</span>
                    <div id="vector-ideal-positif" class="truncate mt-0.5 font-bold">[...]</div>
                </div>
                <div class="p-2.5 bg-white rounded-xl border border-indigo-200 text-rose-800">
                    <span class="font-bold">A⁻ (Solusi Ideal Negatif):</span>
                    <div id="vector-ideal-negatif" class="truncate mt-0.5 font-bold">[...]</div>
                </div>
            </div>
        </div>

        <!-- Tabel Pembuktian Komputasi 5 Alternatif Teratas -->
        <div class="space-y-2">
            <h4 class="text-xs font-bold text-slate-800">Matriks Kalkulasi 5 Destinasi Teratas:</h4>
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-[11px] text-slate-600">
                    <thead class="bg-slate-100 text-slate-700 font-bold">
                        <tr>
                            <th class="p-2.5">Alternatif (A<sub>i</sub>)</th>
                            <th class="p-2.5">Nilai Asli [C1..C5]</th>
                            <th class="p-2.5">Terbobot [Y1..Y5]</th>
                            <th class="p-2.5 text-right">D⁺</th>
                            <th class="p-2.5 text-right">D⁻</th>
                            <th class="p-2.5 text-right">V<sub>i</sub></th>
                            <th class="p-2.5 text-center">Rank</th>
                        </tr>
                    </thead>
                    <tbody id="transparansi-tbody" class="divide-y divide-slate-100 font-mono">
                        <!-- Diisi JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-2 border-t border-slate-100 flex justify-end">
            <button onclick="toggleTransparansiModal()" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>

<!-- SCRIPT LOGIKA TOPSIS SENTIMEN -->
<script>
let currentTopsisData = null;

const PRESET_WEIGHTS = {
    sentimen: {
        name: 'Kualitas Ulasan & Reputasi',
        desc: 'C1: 15% | C2 (Sentimen): 50% | C3: 10% | C4: 15% | C5: 10%',
        w: [15, 50, 10, 15, 10]
    },
    populer: {
        name: 'Terpopuler & Ramai Wisatawan',
        desc: 'C1: 20% | C2 (Sentimen): 10% | C3: 50% | C4: 10% | C5: 10%',
        w: [20, 10, 50, 10, 10]
    },
    seimbang: {
        name: 'Multikriteria Seimbang',
        desc: 'C1: 20% | C2 (Sentimen): 20% | C3: 20% | C4: 20% | C5: 20%',
        w: [20, 20, 20, 20, 20]
    },
    fasilitas: {
        name: 'Fasilitas & Aksesibilitas',
        desc: 'C1: 15% | C2 (Sentimen): 15% | C3: 10% | C4: 30% | C5: 30%',
        w: [15, 15, 10, 30, 30]
    }
};

let activePresetKey = 'sentimen';

document.addEventListener('DOMContentLoaded', () => {
    // Sinkronkan slider awal ke preset sentimen
    applyPreset('sentimen');
});

function toggleBobotModal() {
    document.getElementById('modal-bobot').classList.toggle('hidden');
}

function toggleTransparansiModal() {
    const modal = document.getElementById('modal-transparansi');
    modal.classList.toggle('hidden');
    if (!modal.classList.contains('hidden') && currentTopsisData) {
        populateTransparansiData();
    }
}

function updateBobotVal() {
    document.getElementById('label-w-c1').innerText = document.getElementById('slider-w-c1').value + '%';
    document.getElementById('label-w-c2').innerText = document.getElementById('slider-w-c2').value + '%';
    document.getElementById('label-w-c3').innerText = document.getElementById('slider-w-c3').value + '%';
    document.getElementById('label-w-c4').innerText = document.getElementById('slider-w-c4').value + '%';
    document.getElementById('label-w-c5').innerText = document.getElementById('slider-w-c5').value + '%';
}

function updatePresetUI(presetKey) {
    activePresetKey = presetKey;
    const presets = ['sentimen', 'populer', 'seimbang', 'fasilitas'];
    presets.forEach(key => {
        const btn = document.getElementById('btn-preset-' + key);
        if (!btn) return;
        if (key === presetKey) {
            btn.className = 'px-3 py-1.5 text-[11px] font-black rounded-xl bg-indigo-600 text-white shadow-xs ring-2 ring-indigo-300 ring-offset-1 transition-all';
        } else {
            btn.className = 'px-3 py-1.5 text-[11px] font-semibold rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition-all';
        }
    });

    const info = PRESET_WEIGHTS[presetKey];
    if (info) {
        document.getElementById('label-preset-aktif').innerText = 'Preset Aktif: ' + info.name;
        document.getElementById('detail-bobot-aktif').innerText = info.desc;
    } else {
        document.getElementById('label-preset-aktif').innerText = 'Preset Aktif: Kustom Pengguna';
        const wc1 = document.getElementById('slider-w-c1').value;
        const wc2 = document.getElementById('slider-w-c2').value;
        const wc3 = document.getElementById('slider-w-c3').value;
        const wc4 = document.getElementById('slider-w-c4').value;
        const wc5 = document.getElementById('slider-w-c5').value;
        document.getElementById('detail-bobot-aktif').innerText = `C1: ${wc1}% | C2: ${wc2}% | C3: ${wc3}% | C4: ${wc4}% | C5: ${wc5}%`;
    }
}

function applyPreset(preset) {
    const p = PRESET_WEIGHTS[preset];
    if (p) {
        document.getElementById('slider-w-c1').value = p.w[0];
        document.getElementById('slider-w-c2').value = p.w[1];
        document.getElementById('slider-w-c3').value = p.w[2];
        document.getElementById('slider-w-c4').value = p.w[3];
        document.getElementById('slider-w-c5').value = p.w[4];
        updateBobotVal();
        updatePresetUI(preset);
        loadTopsisRanking();
    }
}

function resetBobotDefault() {
    applyPreset('sentimen');
    toggleBobotModal();
}

function applyCustomBobot() {
    updateBobotVal();
    updatePresetUI('custom');
    toggleBobotModal();
    loadTopsisRanking();
}

async function loadTopsisRanking() {
    const kab = document.getElementById('filter-kabupaten-topsis').value;
    const kat = document.getElementById('filter-kategori-topsis').value;
    const wc1 = document.getElementById('slider-w-c1').value;
    const wc2 = document.getElementById('slider-w-c2').value;
    const wc3 = document.getElementById('slider-w-c3').value;
    const wc4 = document.getElementById('slider-w-c4').value;
    const wc5 = document.getElementById('slider-w-c5').value;

    const loading = document.getElementById('loading-topsis');
    const tableContainer = document.getElementById('table-container');

    loading.classList.remove('hidden');
    tableContainer.classList.add('hidden');

    try {
        const url = `api/v1_topsis_sentimen.php?kabupaten=${encodeURIComponent(kab)}&kategori=${encodeURIComponent(kat)}&w_c1=${wc1}&w_c2=${wc2}&w_c3=${wc3}&w_c4=${wc4}&w_c5=${wc5}&limit=20`;
        const res = await fetch(url);
        const json = await res.json();

        loading.classList.add('hidden');
        tableContainer.classList.remove('hidden');

        if (json.status !== 'success') {
            alert(json.pesan || 'Gagal memuat ranking TOPSIS');
            return;
        }

        currentTopsisData = json;
        renderPodium(json.peringkat_destinasi.slice(0, 3));
        renderTable(json.peringkat_destinasi);

        // Jika modal transparansi sedang terbuka, update datanya juga
        const modalTrans = document.getElementById('modal-transparansi');
        if (modalTrans && !modalTrans.classList.contains('hidden')) {
            populateTransparansiData();
        }
    } catch (err) {
        loading.classList.add('hidden');
        alert('Gagal mengambil data dari API: ' + err.message);
    }
}

function populateTransparansiData() {
    if (!currentTopsisData) return;
    const meta = currentTopsisData.metadata;
    const topList = currentTopsisData.peringkat_destinasi.slice(0, 5);

    document.getElementById('transparansi-kandidat-info').innerText = `${meta.total_kandidat} Alternatif Terhitung`;
    document.getElementById('vector-ideal-positif').innerText = `[${meta.solusi_ideal_positif.join(', ')}]`;
    document.getElementById('vector-ideal-negatif').innerText = `[${meta.solusi_ideal_negatif.join(', ')}]`;

    const tbody = document.getElementById('transparansi-tbody');
    let rows = '';
    topList.forEach(item => {
        const xStr = item.x.map(v => typeof v === 'number' ? (v > 10 ? Math.round(v) : v.toFixed(1)) : v).join(', ');
        const yStr = item.y.map(v => v.toFixed(4)).join(', ');
        rows += `
            <tr class="hover:bg-indigo-50/40 transition-colors">
                <td class="p-2.5 font-bold text-slate-800 text-[10px] truncate max-w-[130px]">${item.nama}</td>
                <td class="p-2.5 text-[10px] text-slate-500">[${xStr}]</td>
                <td class="p-2.5 text-[10px] text-indigo-700 font-semibold">[${yStr}]</td>
                <td class="p-2.5 text-right font-bold text-emerald-600 text-[10px]">${item.d_plus}</td>
                <td class="p-2.5 text-right font-bold text-rose-600 text-[10px]">${item.d_minus}</td>
                <td class="p-2.5 text-right font-black text-indigo-600 text-[11px]">${item.skor_preferensi}</td>
                <td class="p-2.5 text-center font-bold text-slate-700">#${item.peringkat}</td>
            </tr>
        `;
    });
    tbody.innerHTML = rows;
}

function renderPodium(top3) {
    const container = document.getElementById('podium-section');
    if (!top3 || top3.length === 0) {
        container.innerHTML = `
            <div class="col-span-3 py-10 text-center bg-slate-50 rounded-3xl border border-slate-200 text-xs text-slate-500">
                Tidak ada data destinasi untuk kombinasi filter ini.
            </div>
        `;
        return;
    }

    const podiumThemes = [
        { border: 'border-amber-400', badge: 'bg-amber-400 text-slate-950', ring: 'ring-amber-300' },
        { border: 'border-slate-300', badge: 'bg-slate-300 text-slate-800', ring: 'ring-slate-200' },
        { border: 'border-amber-600', badge: 'bg-amber-600 text-white', ring: 'ring-amber-500' }
    ];

    let html = '';
    top3.forEach((item, idx) => {
        const theme = podiumThemes[idx] || podiumThemes[0];
        html += `
            <div class="relative bg-white rounded-3xl border-2 ${theme.border} p-5 shadow-sm space-y-3.5 overflow-hidden transition-all duration-300 hover:shadow-md animate-scale-up">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-50 rounded-full blur-xl pointer-events-none"></div>

                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-full text-xs font-black ${theme.badge}">
                        Peringkat #${item.peringkat}
                    </span>
                    <span class="text-xs font-bold text-indigo-600 font-mono">
                        V = ${item.skor_preferensi} (${item.skor_persen}%)
                    </span>
                </div>

                <div class="relative h-32 rounded-2xl overflow-hidden shadow-xs">
                    <img src="${item.foto}" alt="${item.nama}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-3 right-3 text-white">
                        <div class="text-[10px] font-bold text-teal-300 uppercase">${item.sub_kategori}</div>
                        <h4 class="text-sm font-bold truncate">${item.nama}</h4>
                    </div>
                </div>

                <div class="space-y-1.5 text-xs text-slate-600">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Sentimen Positif (NSS):</span>
                        <strong class="text-emerald-700">${item.sentiment.skor_sentimen_nss}% Positif</strong>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: ${item.sentiment.skor_sentimen_nss}%"></div>
                    </div>
                    <div class="flex justify-between items-center text-[11px] pt-1">
                        <span><i class="fa-solid fa-star text-amber-400"></i> ${item.rating} (${item.ulasan} ulasan)</span>
                        <span class="text-slate-400">${item.kabupaten}</span>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function renderTable(rankedList) {
    const tbody = document.getElementById('topsis-tbody');
    let html = '';

    rankedList.forEach(item => {
        html += `
            <tr class="hover:bg-slate-50/80 transition-colors">
                <td class="px-4 py-3.5">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs shrink-0 ${item.peringkat === 1 ? 'bg-amber-400 text-slate-950' : (item.peringkat === 2 ? 'bg-slate-300 text-slate-800' : (item.peringkat === 3 ? 'bg-amber-200 text-amber-900' : 'bg-slate-100 text-slate-600'))}">
                            ${item.peringkat}
                        </span>
                        <div>
                            <div class="font-bold text-slate-900">${item.nama}</div>
                            <div class="text-[10px] text-slate-400">${item.sub_kategori}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3.5 text-slate-600">${item.kabupaten}</td>
                <td class="px-4 py-3.5">
                    <div class="flex items-center gap-1 font-bold text-slate-800">
                        <i class="fa-solid fa-star text-amber-400 text-[11px]"></i>
                        <span>${item.rating}</span>
                        <span class="text-[10px] text-slate-400 font-normal">(${item.ulasan})</span>
                    </div>
                </td>
                <td class="px-4 py-3.5">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-emerald-600 font-mono">${item.sentiment.skor_sentimen_nss}%</span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded-sm bg-emerald-50 text-emerald-700 font-semibold">${item.sentiment.label_reputasi}</span>
                    </div>
                </td>
                <td class="px-4 py-3.5">
                    <span class="font-mono text-slate-600">${item.x[4]} / 100</span>
                </td>
                <td class="px-4 py-3.5 text-right font-mono font-extrabold text-indigo-600 text-sm">
                    ${item.skor_preferensi}
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}
</script>
