<?php
/**
 * views/rekomendasi.php
 * Sistem Pendukung Keputusan (SPK) Rekomendasi Destinasi Wisata
 * Menggunakan Algoritma TOPSIS (Technique for Order Preference by Similarity to Ideal Solution)
 * Standar Komputasi Akademik & Kemenparekraf RI
 */

require_once __DIR__ . '/../app/database.php';
require_once __DIR__ . '/../app/statistics.php';

$pdo = getDbConnection();

// Ambil parameter bobot dari URL atau gunakan default standar
$wRating    = isset($_GET['w_rating']) ? (float)$_GET['w_rating'] : 30.0;
$wHub       = isset($_GET['w_hub']) ? (float)$_GET['w_hub'] : 25.0;
$wAmenities = isset($_GET['w_amenities']) ? (float)$_GET['w_amenities'] : 25.0;
$wUlasan    = isset($_GET['w_ulasan']) ? (float)$_GET['w_ulasan'] : 20.0;

$filterKab = !empty($_GET['kabupaten']) ? trim($_GET['kabupaten']) : null;
$filterSub = !empty($_GET['sub_kategori']) ? trim($_GET['sub_kategori']) : null;
$limitPOI  = isset($_GET['limit']) ? (int)$_GET['limit'] : 30;

// Eksekusi TOPSIS
$topsisResult = calculateTopsisRecommendation([
    'rating'    => $wRating,
    'hub_dist'  => $wHub,
    'amenities' => $wAmenities,
    'ulasan'    => $wUlasan
], $filterKab, $filterSub, $limitPOI);

$weights     = $topsisResult['weights'];
$rankedList  = $topsisResult['candidates'];
$top10       = $topsisResult['top_10'];
$aPlus       = $topsisResult['a_plus'];
$aMinus      = $topsisResult['a_minus'];
$divisors    = $topsisResult['divisors'];
$totalPOI    = $topsisResult['total_counted'];

// Daftar Kabupaten unik untuk dropdown filter
$kabList = [
    'Kota Pangkalpinang',
    'Kabupaten Bangka',
    'Kabupaten Belitung',
    'Kabupaten Bangka Barat',
    'Kabupaten Bangka Tengah',
    'Kabupaten Bangka Selatan',
    'Kabupaten Belitung Timur'
];

// Highlight Stats
$top1 = !empty($top10) ? $top10[0] : null;
$avgV = !empty($rankedList) ? round(array_sum(array_column($rankedList, 'score_v')) / count($rankedList), 4) : 0;
$closestHubPOI = !empty($rankedList) ? array_reduce($rankedList, fn($carry, $item) => ($carry === null || $item['hub_dist'] < $carry['hub_dist']) ? $item : $carry, null) : null;
$maxAmenityPOI = !empty($rankedList) ? array_reduce($rankedList, fn($carry, $item) => ($carry === null || $item['amenities'] > $carry['amenities']) ? $item : $carry, null) : null;
?>

<main class="w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 space-y-6 sm:space-y-8 min-w-0 overflow-hidden">

    <!-- ============================================================== -->
    <!-- HEADER HERO SECTION                                            -->
    <!-- ============================================================== -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-ocean-950 to-indigo-950 rounded-3xl p-4 sm:p-8 lg:p-10 text-white shadow-xl border border-white/10 w-full max-w-full min-w-0">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-ocean-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-32 -bottom-20 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 sm:space-y-3 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-ocean-300 text-xs font-semibold">
                    <i class="fa-solid fa-ranking-star"></i>
                    <span>Multi-Criteria Decision Making (MCDM) &bull; Spatial DSS</span>
                </div>
                <h1 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                    Sistem Pendukung Keputusan (SPK) TOPSIS
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    Perankingan destinasi pariwisata terunggul berdasarkan jarak geometris ke <strong class="text-white">Solusi Ideal Positif ($A^+$)</strong> dan keterjauhan dari <strong class="text-white">Solusi Ideal Negatif ($A^-$)</strong> sesuai metodologi Hwang &amp; Yoon (1981) yang disesuaikan dengan kriteria spasial Kemenparekraf RI.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 md:flex md:flex-col gap-2 w-full md:w-auto shrink-0">
                <button onclick="openGuideModal('rekomendasi')" class="inline-flex items-center justify-center gap-1.5 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold border border-white/20 transition-all shadow-sm w-full">
                    <i class="fa-solid fa-circle-question text-ocean-400"></i>
                    <span>Panduan TOPSIS</span>
                </button>
                <a href="index.php?page=analisis" class="inline-flex items-center justify-center gap-1.5 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all shadow-sm w-full">
                    <i class="fa-solid fa-chart-line text-ocean-400"></i>
                    <span>Analisis Spasial</span>
                </a>
                <button onclick="exportTopsisCSV()" class="inline-flex items-center justify-center gap-1.5 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-sm w-full">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Ekspor CSV</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- PANEL KONTROL BOBOT & FILTER INTERAKTIF                         -->
    <!-- ============================================================== -->
    <div class="bg-white rounded-3xl p-4 sm:p-6 lg:p-8 border border-slate-200/80 shadow-sm space-y-6 w-full max-w-full min-w-0 overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-sliders"></i>
                    </span>
                    <span>Konfigurasi Bobot Kriteria &amp; Preferensi Wisatawan</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Geser slider atau pilih preset persona untuk menyesuaikan bobot matematis ($W_j$). Sistem otomatis menormalisasi total bobot menjadi 100% ($1.0$).
                </p>
            </div>

            <!-- Total Bobot Live Indicator -->
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 rounded-2xl bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700 flex items-center gap-2">
                    <span>Total Input:</span>
                    <span id="badgeTotalWeight" class="text-ocean-600 font-extrabold">100%</span>
                </div>
            </div>
        </div>

        <!-- Quick Persona Presets -->
        <div class="space-y-2">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pilihan Cepat Profil Preferensi (Persona Presets):</span>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 text-xs">
                <button type="button" onclick="applyPreset(30, 25, 25, 20)" class="p-2.5 text-left rounded-xl bg-slate-50 hover:bg-ocean-50 hover:border-ocean-300 border border-slate-200 transition-all min-w-0">
                    <span class="font-bold text-slate-800 block text-xs truncate">Standar Riset</span>
                    <span class="text-[10px] text-slate-500 block truncate">30:25:25:20</span>
                </button>
                <button type="button" onclick="applyPreset(35, 15, 40, 10)" class="p-2.5 text-left rounded-xl bg-slate-50 hover:bg-ocean-50 hover:border-ocean-300 border border-slate-200 transition-all min-w-0">
                    <span class="font-bold text-slate-800 block text-xs truncate">Keluarga &amp; Santai</span>
                    <span class="text-[10px] text-slate-500 block truncate">Amenitas (40%)</span>
                </button>
                <button type="button" onclick="applyPreset(20, 45, 15, 20)" class="p-2.5 text-left rounded-xl bg-slate-50 hover:bg-ocean-50 hover:border-ocean-300 border border-slate-200 transition-all min-w-0">
                    <span class="font-bold text-slate-800 block text-xs truncate">Backpacker</span>
                    <span class="text-[10px] text-slate-500 block truncate">Hub Dekat (45%)</span>
                </button>
                <button type="button" onclick="applyPreset(25, 10, 15, 50)" class="p-2.5 text-left rounded-xl bg-slate-50 hover:bg-ocean-50 hover:border-ocean-300 border border-slate-200 transition-all min-w-0">
                    <span class="font-bold text-slate-800 block text-xs truncate">Destinasi Viral</span>
                    <span class="text-[10px] text-slate-500 block truncate">Ulasan (50%)</span>
                </button>
                <button type="button" onclick="applyPreset(55, 10, 25, 10)" class="p-2.5 text-left rounded-xl bg-slate-50 hover:bg-ocean-50 hover:border-ocean-300 border border-slate-200 transition-all min-w-0 col-span-2 sm:col-span-1">
                    <span class="font-bold text-slate-800 block text-xs truncate">Kualitas Mutu</span>
                    <span class="text-[10px] text-slate-500 block truncate">Rating (55%)</span>
                </button>
            </div>
        </div>

        <!-- Form Sliders & Filter -->
        <form method="GET" action="index.php" id="topsisForm" class="space-y-6">
            <input type="hidden" name="page" value="rekomendasi">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- Kriteria C1: Rating -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold">C1</span>
                            <span class="font-bold text-slate-800 text-xs">Rating Google</span>
                        </div>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Benefit</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 text-[11px]">Bobot Kriteria ($W_1$):</span>
                        <span id="label_wRating" class="font-extrabold text-ocean-600"><?= (int)$wRating ?>%</span>
                    </div>
                    <input type="range" name="w_rating" id="slider_wRating" min="5" max="60" step="5" value="<?= (int)$wRating ?>" 
                        oninput="updateWeightLabels()" class="w-full accent-ocean-600 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Nilai bintang ulasan kepuasan wisatawan (1.0 - 5.0).</p>
                </div>

                <!-- Kriteria C2: Jarak Simpul (Cost) -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-bold">C2</span>
                            <span class="font-bold text-slate-800 text-xs">Aksesibilitas Hub</span>
                        </div>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800">Cost (Biaya)</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 text-[11px]">Bobot Kriteria ($W_2$):</span>
                        <span id="label_wHub" class="font-extrabold text-ocean-600"><?= (int)$wHub ?>%</span>
                    </div>
                    <input type="range" name="w_hub" id="slider_wHub" min="5" max="60" step="5" value="<?= (int)$wHub ?>" 
                        oninput="updateWeightLabels()" class="w-full accent-ocean-600 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Jarak Euclidean ke bandara/pelabuhan (makin dekat makin unggul).</p>
                </div>

                <!-- Kriteria C3: Amenitas 5km -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center text-xs font-bold">C3</span>
                            <span class="font-bold text-slate-800 text-xs">Amenitas 5 km</span>
                        </div>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Benefit</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 text-[11px]">Bobot Kriteria ($W_3$):</span>
                        <span id="label_wAmenities" class="font-extrabold text-ocean-600"><?= (int)$wAmenities ?>%</span>
                    </div>
                    <input type="range" name="w_amenities" id="slider_wAmenities" min="5" max="60" step="5" value="<?= (int)$wAmenities ?>" 
                        oninput="updateWeightLabels()" class="w-full accent-ocean-600 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Jumlah hotel &amp; resto pendukung dalam radius 5 km.</p>
                </div>

                <!-- Kriteria C4: Popularitas Ulasan -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">C4</span>
                            <span class="font-bold text-slate-800 text-xs">Volume Ulasan</span>
                        </div>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Benefit</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 text-[11px]">Bobot Kriteria ($W_4$):</span>
                        <span id="label_wUlasan" class="font-extrabold text-ocean-600"><?= (int)$wUlasan ?>%</span>
                    </div>
                    <input type="range" name="w_ulasan" id="slider_wUlasan" min="5" max="60" step="5" value="<?= (int)$wUlasan ?>" 
                        oninput="updateWeightLabels()" class="w-full accent-ocean-600 cursor-pointer">
                    <p class="text-[10px] text-slate-400">Tingkat popularitas publik ($\ln(\text{ulasan}+1)$).</p>
                </div>

            </div>

            <!-- Filter Wilayah & Kategori Tambahan -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-4 border-t border-slate-100 w-full max-w-full min-w-0">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs w-full lg:w-auto">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 min-w-0">
                        <label class="font-bold text-slate-600 shrink-0">Wilayah:</label>
                        <select name="kabupaten" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl focus:ring-2 focus:ring-ocean-500 font-medium text-slate-700 text-xs truncate">
                            <option value="">Semua Wilayah (Babel)</option>
                            <?php foreach ($kabList as $k): ?>
                            <option value="<?= htmlspecialchars($k) ?>" <?= $filterKab === $k ? 'selected' : '' ?>><?= htmlspecialchars($k) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 min-w-0">
                        <label class="font-bold text-slate-600 shrink-0">Kategori:</label>
                        <select name="sub_kategori" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl focus:ring-2 focus:ring-ocean-500 font-medium text-slate-700 text-xs truncate">
                            <option value="">Semua Daya Tarik</option>
                            <option value="Alam" <?= $filterSub === 'Alam' ? 'selected' : '' ?>>Wisata Alam</option>
                            <option value="Buatan" <?= $filterSub === 'Buatan' ? 'selected' : '' ?>>Wisata Buatan</option>
                            <option value="Kebudayaan" <?= $filterSub === 'Kebudayaan' ? 'selected' : '' ?>>Budaya / Sejarah</option>
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 min-w-0">
                        <label class="font-bold text-slate-600 shrink-0">Jumlah:</label>
                        <select name="limit" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl focus:ring-2 focus:ring-ocean-500 font-medium text-slate-700 text-xs">
                            <option value="20" <?= $limitPOI == 20 ? 'selected' : '' ?>>Top 20</option>
                            <option value="30" <?= $limitPOI == 30 ? 'selected' : '' ?>>Top 30 (Standar)</option>
                            <option value="50" <?= $limitPOI == 50 ? 'selected' : '' ?>>Top 50</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 w-full lg:w-auto">
                    <a href="index.php?page=rekomendasi" class="flex-1 sm:flex-initial text-center px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all whitespace-nowrap">
                        <i class="fa-solid fa-rotate-left mr-1"></i> Reset
                    </a>
                    <button type="submit" class="flex-auto sm:flex-initial px-5 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white text-xs font-bold shadow-md shadow-ocean-500/20 transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Hitung Ulang TOPSIS</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- KPI SUMMARY CARDS                                              -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- #1 Rekomendasi Teratas -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-3xl p-5 text-white shadow-lg shadow-amber-500/15 relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between text-xs mb-2 text-amber-100">
                <span class="font-bold uppercase tracking-wider text-[10px]">Peringkat #1 Teratas</span>
                <i class="fa-solid fa-crown text-base text-yellow-200"></i>
            </div>
            <div class="text-lg font-black truncate" title="<?= htmlspecialchars($top1['nama'] ?? '-') ?>">
                <?= htmlspecialchars($top1['nama'] ?? 'Belum Ada') ?>
            </div>
            <div class="text-xs text-amber-100 flex items-center gap-1 mt-0.5">
                <span>Skor Preferensi ($V_1$):</span>
                <span class="font-extrabold text-white text-sm"><?= $top1 ? number_format($top1['score_v'], 4) : '-' ?></span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-white/20 flex items-center justify-between text-[11px] text-amber-100">
                <span><?= htmlspecialchars($top1['kabupaten'] ?? '') ?></span>
                <span class="font-bold">★ <?= $top1['rating'] ?? 0 ?> (<?= number_format($top1['ulasan'] ?? 0) ?> ulasan)</span>
            </div>
        </div>

        <!-- Rata-rata Skor V -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between text-xs mb-2 text-slate-400">
                <span class="font-bold uppercase tracking-wider text-[10px]">Rata-rata Skor Preferensi</span>
                <span class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-chart-pie"></i>
                </span>
            </div>
            <div class="text-2xl font-black text-slate-900">
                <?= number_format($avgV, 4) ?>
            </div>
            <div class="text-xs text-slate-500 mt-1">
                Dari <strong class="text-slate-800"><?= $totalPOI ?></strong> destinasi wisata terpilih
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Skor Tertinggi: <strong class="text-slate-800"><?= $top1 ? number_format($top1['score_v'], 4) : 0 ?></strong></span>
                <span>Terendah: <strong class="text-slate-800"><?= !empty($rankedList) ? number_format(end($rankedList)['score_v'], 4) : 0 ?></strong></span>
            </div>
        </div>

        <!-- Simpul Akses Terdekat -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between text-xs mb-2 text-slate-400">
                <span class="font-bold uppercase tracking-wider text-[10px]">Akses Simpul Paling Cepat</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-plane-departure"></i>
                </span>
            </div>
            <div class="text-lg font-black text-slate-900 truncate" title="<?= htmlspecialchars($closestHubPOI['nama'] ?? '-') ?>">
                <?= htmlspecialchars($closestHubPOI['nama'] ?? '-') ?>
            </div>
            <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                <span>Jarak ke Hub:</span>
                <span class="font-extrabold text-purple-700"><?= $closestHubPOI ? $closestHubPOI['hub_dist'] . ' km' : '-' ?></span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span class="truncate"><?= htmlspecialchars($closestHubPOI['closest_hub'] ?? 'Simpul') ?></span>
                <span class="font-bold text-slate-700"><?= htmlspecialchars($closestHubPOI['kabupaten'] ?? '') ?></span>
            </div>
        </div>

        <!-- Amenitas Terlengkap 5km -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between text-xs mb-2 text-slate-400">
                <span class="font-bold uppercase tracking-wider text-[10px]">Amenitas Paling Padat</span>
                <span class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hotel"></i>
                </span>
            </div>
            <div class="text-lg font-black text-slate-900 truncate" title="<?= htmlspecialchars($maxAmenityPOI['nama'] ?? '-') ?>">
                <?= htmlspecialchars($maxAmenityPOI['nama'] ?? '-') ?>
            </div>
            <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                <span>Kepadatan 5 km:</span>
                <span class="font-extrabold text-sky-700"><?= $maxAmenityPOI ? $maxAmenityPOI['amenities'] . ' Hotel &amp; Resto' : '-' ?></span>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span class="truncate"><?= htmlspecialchars($maxAmenityPOI['kecamatan'] ?? '') ?></span>
                <span class="font-bold text-slate-700">Skor V: <?= $maxAmenityPOI ? number_format($maxAmenityPOI['score_v'], 3) : '-' ?></span>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- TOP 10 REKOMENDASI TERUNGGUL (CARDS DISPLAY)                   -->
    <!-- ============================================================== -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2.5">
                    <i class="fa-solid fa-trophy text-amber-500"></i>
                    <span>Top 10 Destinasi Rekomendasi Terbaik</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Diurutkan dari nilai kedekatan relatif preferensi ($V_i$) tertinggi mendekati solusi ideal positif ($A^+$).
                </p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                Menampilkan 10 dari <?= $totalPOI ?> Alternatif
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($top10 as $rank => $item): 
                $rankNum = $rank + 1;
                $pctScore = round($item['score_v'] * 100, 1);

                // Styling medali
                if ($rankNum === 1) {
                    $cardBorder = 'border-amber-300 ring-2 ring-amber-400/20 bg-gradient-to-br from-amber-50/40 via-white to-white';
                    $rankBadge  = 'bg-gradient-to-tr from-amber-500 to-yellow-400 text-white shadow-md shadow-amber-500/20';
                    $rankIcon   = '<i class="fa-solid fa-crown text-xs"></i>';
                } elseif ($rankNum === 2) {
                    $cardBorder = 'border-slate-300 ring-1 ring-slate-200 bg-gradient-to-br from-slate-50/40 via-white to-white';
                    $rankBadge  = 'bg-gradient-to-tr from-slate-400 to-slate-300 text-slate-900 shadow-sm';
                    $rankIcon   = '<i class="fa-solid fa-medal text-xs"></i>';
                } elseif ($rankNum === 3) {
                    $cardBorder = 'border-amber-700/20 bg-gradient-to-br from-orange-50/40 via-white to-white';
                    $rankBadge  = 'bg-gradient-to-tr from-amber-700 to-amber-600 text-white shadow-sm';
                    $rankIcon   = '<i class="fa-solid fa-award text-xs"></i>';
                } else {
                    $cardBorder = 'border-slate-200 bg-white hover:border-ocean-300';
                    $rankBadge  = 'bg-slate-100 text-slate-700 border border-slate-200';
                    $rankIcon   = '#' . $rankNum;
                }

                // Sub kategori badge
                if ($item['sub_kategori'] === 'Alam') {
                    $subColor = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $subIcon = 'fa-umbrella-beach';
                } elseif ($item['sub_kategori'] === 'Buatan') {
                    $subColor = 'bg-amber-50 text-amber-700 border-amber-200';
                    $subIcon = 'fa-camera';
                } else {
                    $subColor = 'bg-purple-50 text-purple-700 border-purple-200';
                    $subIcon = 'fa-monument';
                }
            ?>
            <div class="rounded-3xl p-5 border <?= $cardBorder ?> shadow-sm transition-all hover:shadow-md space-y-4 relative flex flex-col justify-between">
                
                <div>
                    <!-- Header Kartu -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-2xl flex items-center justify-center font-extrabold text-sm shrink-0 <?= $rankBadge ?>">
                                <?= $rankIcon ?>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base leading-tight">
                                    <?= htmlspecialchars($item['nama']) ?>
                                </h3>
                                <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md border text-[10px] font-bold <?= $subColor ?>">
                                        <i class="fa-solid <?= $subIcon ?> text-[9px]"></i>
                                        <?= htmlspecialchars($item['sub_kategori']) ?>
                                    </span>
                                    <span>&bull;</span>
                                    <span><?= htmlspecialchars($item['kabupaten']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Badge Skor V -->
                        <div class="text-right shrink-0">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Skor $V_i$</div>
                            <div class="text-lg font-black text-ocean-700">
                                <?= number_format($item['score_v'], 4) ?>
                            </div>
                            <div class="text-[10px] font-bold text-emerald-600"><?= $pctScore ?>% Ideal</div>
                        </div>
                    </div>

                    <!-- Progress Bar Kedekatan Ideal -->
                    <div class="mt-3">
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-ocean-600 to-emerald-500 h-2 rounded-full transition-all duration-500" style="width: <?= $pctScore ?>%"></div>
                        </div>
                    </div>

                    <!-- Atribut Kriteria Spesifik -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-4 text-[11px]">
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 block text-[10px]">C1: Rating</span>
                            <span class="font-extrabold text-amber-600 flex items-center gap-1">
                                <i class="fa-solid fa-star text-[9px]"></i> <?= $item['rating'] ?>
                            </span>
                            <span class="text-[9px] text-slate-400"><?= number_format($item['ulasan']) ?> ulasan</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 block text-[10px]">C2: Hub Akses</span>
                            <span class="font-extrabold text-purple-700 flex items-center gap-1">
                                <i class="fa-solid fa-plane text-[9px]"></i> <?= $item['hub_dist'] ?> km
                            </span>
                            <span class="text-[9px] text-slate-400 truncate block" title="<?= htmlspecialchars($item['closest_hub']) ?>"><?= htmlspecialchars($item['closest_hub']) ?></span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 block text-[10px]">C3: Amenitas 5km</span>
                            <span class="font-extrabold text-sky-700 flex items-center gap-1">
                                <i class="fa-solid fa-hotel text-[9px]"></i> <?= $item['amenities'] ?> Unit
                            </span>
                            <span class="text-[9px] text-slate-400">Hotel &amp; Resto</span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400 block text-[10px]">Jarak Separasi</span>
                            <span class="font-extrabold text-slate-700 block">
                                $D^+$: <?= $item['d_plus'] ?>
                            </span>
                            <span class="text-[9px] text-slate-500 font-semibold block">
                                $D^-$: <?= $item['d_minus'] ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Footer Aksi -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] text-slate-400">
                        <i class="fa-solid fa-location-dot mr-1"></i> Kec. <?= htmlspecialchars($item['kecamatan']) ?>
                    </span>
                    <div class="flex items-center gap-2">
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($item['nama'] . ' Bangka Belitung') ?>" target="_blank" 
                           class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-[11px] font-semibold transition-all">
                            <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> G-Maps
                        </a>
                        <a href="index.php?page=peta&q=<?= urlencode($item['nama']) ?>&lat=<?= $item['lat'] ?>&lng=<?= $item['lng'] ?>" 
                           class="px-3 py-1.5 rounded-lg bg-ocean-600 hover:bg-ocean-700 text-white text-[11px] font-bold shadow-sm transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-map-pin text-[10px]"></i>
                            <span>Lihat di Peta</span>
                        </a>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- TRANSPARANSI & AUDIT KOMPUTASI MATEMATIS TOPSIS                -->
    <!-- ============================================================== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden w-full max-w-full min-w-0">
        
        <!-- Accordion Header -->
        <button type="button" onclick="toggleDefenseAccordion()" class="w-full p-4 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50/80 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base lg:text-lg font-extrabold text-slate-900 flex flex-wrap items-center gap-2">
                        <span>Transparansi &amp; Verifikasi Komputasi Algoritma TOPSIS (Audit Matematis MCDM)</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">5 Langkah Standar</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Klik untuk melihat matriks keputusan ($X$), matriks normalisasi ($R$), matriks terbobot ($Y$), vektor solusi ideal ($A^+/A^-$), serta tabel 30 alternatif.
                    </p>
                </div>
            </div>
            <div id="accordionIcon" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-sm transition-transform duration-300 shrink-0 ml-2">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </button>

        <!-- Accordion Body -->
        <div id="defenseBody" class="hidden p-4 sm:p-6 lg:p-8 border-t border-slate-200/80 space-y-8 bg-slate-50/50 w-full max-w-full min-w-0 overflow-hidden">
            
            <!-- Tahapan Matematis & Rumus -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-2">
                    <div class="font-extrabold text-slate-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-ocean-600 text-white flex items-center justify-center text-[10px]">1</span>
                        <span>Normalisasi Matriks ($r_{ij}$)</span>
                    </div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Membagi nilai elemen matriks keputusan awal ($x_{ij}$) dengan akar jumlah kuadrat seluruh alternatif:
                    </p>
                    <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] text-center">
                        r_ij = x_ij / &radic;(&sum; x_kj&sup2;)
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-2">
                    <div class="font-extrabold text-slate-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-ocean-600 text-white flex items-center justify-center text-[10px]">2</span>
                        <span>Matriks Terbobot ($y_{ij}$)</span>
                    </div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Mengalikan nilai ternormalisasi dengan bobot kriteria preferensi ($w_j$):
                    </p>
                    <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] text-center">
                        y_ij = w_j &times; r_ij
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-2">
                    <div class="font-extrabold text-slate-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-ocean-600 text-white flex items-center justify-center text-[10px]">3</span>
                        <span>Preferensi Relatif ($V_i$)</span>
                    </div>
                    <p class="text-slate-600 text-[11px] leading-relaxed">
                        Rasio jarak keterjauhan dari solusi negatif terhadap total jarak Euclidean:
                    </p>
                    <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] text-center">
                        V_i = D_i&oline; / (D_i&oline; + D_i&plus;)
                    </div>
                </div>
            </div>

            <!-- Vektor Solusi Ideal Positif & Negatif -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 space-y-3">
                <h4 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-arrows-split-up-and-left text-ocean-600"></i>
                    <span>Vektor Solusi Ideal Positif ($A^+$) dan Solusi Ideal Negatif ($A^-$)</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 space-y-1">
                        <span class="font-bold block text-[11px] text-emerald-700">A+ (Solusi Ideal Positif - Nilai Target Maksimal):</span>
                        <div class="text-[11px]">C1 (Rating) &nbsp; : <?= $aPlus['rating'] ?? 0 ?> (Max Benefit)</div>
                        <div class="text-[11px]">C2 (Hub Dist): <?= $aPlus['hub_dist'] ?? 0 ?> (Min Cost)</div>
                        <div class="text-[11px]">C3 (Amenitas): <?= $aPlus['amenities'] ?? 0 ?> (Max Benefit)</div>
                        <div class="text-[11px]">C4 (Ulasan) &nbsp;: <?= $aPlus['ulasan'] ?? 0 ?> (Max Benefit)</div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 space-y-1">
                        <span class="font-bold block text-[11px] text-rose-700">A- (Solusi Ideal Negatif - Nilai Target Terburuk):</span>
                        <div class="text-[11px]">C1 (Rating) &nbsp; : <?= $aMinus['rating'] ?? 0 ?> (Min Benefit)</div>
                        <div class="text-[11px]">C2 (Hub Dist): <?= $aMinus['hub_dist'] ?? 0 ?> (Max Cost)</div>
                        <div class="text-[11px]">C3 (Amenitas): <?= $aMinus['amenities'] ?? 0 ?> (Min Benefit)</div>
                        <div class="text-[11px]">C4 (Ulasan) &nbsp;: <?= $aMinus['ulasan'] ?? 0 ?> (Min Benefit)</div>
                    </div>
                </div>
            </div>

            <!-- Tabel Transparansi Lengkap Seluruh Alternatif -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden space-y-3 p-4 sm:p-5 w-full max-w-full min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-sm">
                            Tabel Lengkap Perhitungan Matriks Keputusan &amp; Ranking TOPSIS
                        </h4>
                        <p class="text-[11px] text-slate-500">
                            Menampilkan seluruh <?= count($rankedList) ?> alternatif daya tarik wisata beserta parameter kalkulasi separasi Euclidean.
                        </p>
                    </div>
                    <span class="text-xs font-mono font-bold text-ocean-700 bg-ocean-50 px-3 py-1 rounded-lg self-start sm:self-auto shrink-0">
                        N = <?= count($rankedList) ?> Destinasi
                    </span>
                </div>

                <div class="w-full max-w-full min-w-0 overflow-x-auto touch-scroll">
                    <table id="topsisTable" class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700 font-extrabold border-b border-slate-200 text-[11px]">
                                <th class="p-2.5 text-center">Rank</th>
                                <th class="p-2.5">Destinasi Wisata</th>
                                <th class="p-2.5">Wilayah</th>
                                <th class="p-2.5 text-right">Rating (C1)</th>
                                <th class="p-2.5 text-right">Jarak Hub (C2)</th>
                                <th class="p-2.5 text-right">Amenitas 5km (C3)</th>
                                <th class="p-2.5 text-right">Ulasan (C4)</th>
                                <th class="p-2.5 text-right font-mono">$D^+$</th>
                                <th class="p-2.5 text-right font-mono">$D^-$</th>
                                <th class="p-2.5 text-right font-mono font-black text-ocean-700">Skor $V_i$</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-[11px]">
                            <?php foreach ($rankedList as $idx => $row): 
                                $r = $idx + 1;
                                $rowHighlight = $r <= 3 ? 'bg-amber-50/30 font-semibold' : ($r % 2 === 0 ? 'bg-slate-50/50' : 'bg-white');
                            ?>
                            <tr class="hover:bg-ocean-50/40 transition-colors <?= $rowHighlight ?>">
                                <td class="p-2.5 text-center font-extrabold text-slate-600">
                                    <?= $r === 1 ? '🥇 1' : ($r === 2 ? '🥈 2' : ($r === 3 ? '🥉 3' : $r)) ?>
                                </td>
                                <td class="p-2.5 font-bold text-slate-900">
                                    <a href="index.php?page=peta&q=<?= urlencode($row['nama']) ?>&lat=<?= $row['lat'] ?>&lng=<?= $row['lng'] ?>" class="hover:text-ocean-600 transition-colors">
                                        <?= htmlspecialchars($row['nama']) ?>
                                    </a>
                                </td>
                                <td class="p-2.5 text-slate-500"><?= htmlspecialchars($row['kabupaten']) ?></td>
                                <td class="p-2.5 text-right font-mono text-amber-700 font-bold"><?= $row['rating'] ?></td>
                                <td class="p-2.5 text-right font-mono text-purple-700"><?= $row['hub_dist'] ?> km</td>
                                <td class="p-2.5 text-right font-mono text-sky-700"><?= $row['amenities'] ?></td>
                                <td class="p-2.5 text-right font-mono text-slate-600"><?= number_format($row['ulasan']) ?></td>
                                <td class="p-2.5 text-right font-mono text-slate-500"><?= number_format($row['d_plus'], 4) ?></td>
                                <td class="p-2.5 text-right font-mono text-slate-500"><?= number_format($row['d_minus'], 4) ?></td>
                                <td class="p-2.5 text-right font-mono font-black text-ocean-700 bg-ocean-50/50"><?= number_format($row['score_v'], 4) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

</main>

<script>
// Sinkronisasi Slider Bobot dan Live Label
function updateWeightLabels() {
    const r = parseInt(document.getElementById('slider_wRating').value) || 0;
    const h = parseInt(document.getElementById('slider_wHub').value) || 0;
    const a = parseInt(document.getElementById('slider_wAmenities').value) || 0;
    const u = parseInt(document.getElementById('slider_wUlasan').value) || 0;

    document.getElementById('label_wRating').innerText = r + '%';
    document.getElementById('label_wHub').innerText = h + '%';
    document.getElementById('label_wAmenities').innerText = a + '%';
    document.getElementById('label_wUlasan').innerText = u + '%';

    const tot = r + h + a + u;
    const badge = document.getElementById('badgeTotalWeight');
    badge.innerText = tot + '%';

    if (tot === 100) {
        badge.className = 'text-emerald-600 font-extrabold';
    } else {
        badge.className = 'text-amber-600 font-extrabold';
    }
}

// Quick Persona Preset Setter
function applyPreset(r, h, a, u) {
    document.getElementById('slider_wRating').value = r;
    document.getElementById('slider_wHub').value = h;
    document.getElementById('slider_wAmenities').value = a;
    document.getElementById('slider_wUlasan').value = u;
    updateWeightLabels();
    document.getElementById('topsisForm').submit();
}

// Toggle Transparansi Akordeon
function toggleDefenseAccordion() {
    const body = document.getElementById('defenseBody');
    const icon = document.getElementById('accordionIcon');
    if (body.classList.contains('hidden')) {
        body.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        body.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Ekspor Tabel TOPSIS ke CSV
function exportTopsisCSV() {
    const table = document.getElementById('topsisTable');
    let csv = [];
    const rows = table.querySelectorAll('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');
        for (let j = 0; j < cols.length; j++) {
            let cell = cols[j].innerText.replace(/"/g, '""').trim();
            row.push('"' + cell + '"');
        }
        csv.push(row.join(','));
    }

    const csvContent = 'data:text/csv;charset=utf-8,\uFEFF' + encodeURIComponent(csv.join('\n'));
    const link = document.createElement('a');
    link.setAttribute('href', csvContent);
    link.setAttribute('download', 'Hasil_Rekomendasi_TOPSIS_Babel.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Inisialisasi awal
updateWeightLabels();
</script>
