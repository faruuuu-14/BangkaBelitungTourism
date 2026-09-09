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
    <!-- 1. HERO HEADER SECTION                                         -->
    <!-- ============================================================== -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-ocean-950 to-indigo-950 rounded-3xl p-5 sm:p-8 lg:p-10 text-white shadow-xl border border-white/10 w-full max-w-full min-w-0">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-ocean-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-32 -bottom-20 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            
            <!-- Kolom Kiri: Teks & Aksi Utama (7 Cols) -->
            <div class="lg:col-span-7 space-y-3 sm:space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-ocean-300 text-xs font-semibold">
                    <i class="fa-solid fa-ranking-star text-ocean-400"></i>
                    <span>Sistem Pendukung Keputusan (SPK) &bull; Multi-Criteria Decision Making (MCDM)</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white">
                    Rekomendasi Destinasi Terbaik (TOPSIS)
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    Menentukan peringkat destinasi wisata secara objektif menggunakan metode ilmiah TOPSIS (Hwang &amp; Yoon, 1981) berdasarkan 4 pilar standar Kemenparekraf RI: Kualitas Rating, Jarak Akses Transportasi, Ketersediaan Hotel/Restoran, dan Popularitas Wisatawan.
                </p>

                <div class="flex flex-wrap items-center gap-2.5 pt-1">
                    <button onclick="openGuideModal('rekomendasi')" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/15 transition-all shadow-sm">
                        <i class="fa-solid fa-circle-question text-ocean-400 text-xs"></i>
                        <span>Panduan &amp; Rumus TOPSIS</span>
                    </button>
                    <button onclick="exportTopsisCSV()" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-sm">
                        <i class="fa-solid fa-file-csv text-xs"></i>
                        <span>Ekspor Hasil CSV</span>
                    </button>
                    <a href="index.php?page=peta" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-ocean-600/80 hover:bg-ocean-600 text-white text-xs font-bold transition-all shadow-sm">
                        <i class="fa-solid fa-map-location-dot text-xs"></i>
                        <span>Buka Peta Spasial</span>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Visual Card Showcase Geopark & Budaya (5 Cols) -->
            <div class="lg:col-span-5 relative mt-4 lg:mt-0">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/20 group">
                    <img src="assets/img/pantai_tanjung_tinggi.jpg" 
                         alt="Pantai Tanjung Tinggi Belitung" 
                         class="w-full h-52 sm:h-64 object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent"></div>

                    <!-- Badge Kemenpar & Geopark -->
                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-full bg-slate-900/85 backdrop-blur-md text-[10px] font-bold text-amber-300 border border-amber-400/30 flex items-center gap-1.5 shadow">
                            <i class="fa-solid fa-earth-asia text-amber-400"></i>
                            <span>UNESCO Global Geopark</span>
                        </span>
                        <span class="px-2 py-1 rounded-full bg-ocean-600/80 backdrop-blur-md text-[10px] font-semibold text-white shadow">
                            4 Kriteria Kemenpar
                        </span>
                    </div>

                    <!-- Overlay Info & Thumbnail Mini -->
                    <div class="absolute bottom-3 left-3 right-3 space-y-2">
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-white drop-shadow">
                                Destinasi Unggulan Kep. Bangka Belitung
                            </h3>
                            <p class="text-[11px] text-slate-300 drop-shadow line-clamp-1">
                                Geopark Belitong, Bentang Alam Granit &amp; Pusaka Budaya Bahari
                            </p>
                        </div>

                        <!-- Mini Thumbnail Slider Preview -->
                        <div class="flex items-center gap-2 pt-1 border-t border-white/15">
                            <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-2 py-1 rounded-xl border border-white/10">
                                <img src="assets/img/kain_cual_bangka.jpg" alt="Kain Cual" class="w-5 h-5 rounded-md object-cover">
                                <span class="text-[10px] font-medium text-slate-200">Kain Cual</span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-2 py-1 rounded-xl border border-white/10">
                                <img src="assets/img/laskar_pelangi_gantong.jpg" alt="Laskar Pelangi" class="w-5 h-5 rounded-md object-cover">
                                <span class="text-[10px] font-medium text-slate-200">Laskar Pelangi</span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-2 py-1 rounded-xl border border-white/10">
                                <img src="assets/img/alat_musik_dambus.jpg" alt="Dambus" class="w-5 h-5 rounded-md object-cover">
                                <span class="text-[10px] font-medium text-slate-200">Dambus</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 2. CALLOUT KONTEKS: "FITUR INI UNTUK APA & BAGAIMANA CARA BACANYA?" -->
    <!-- ============================================================== -->
    <div class="bg-gradient-to-r from-ocean-50/90 via-sky-50/70 to-indigo-50/70 border border-ocean-200/80 rounded-2xl p-4 sm:p-5">
        <div class="flex items-start gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-ocean-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                <i class="fa-solid fa-lightbulb text-sm"></i>
            </div>
            <div class="space-y-1.5 w-full">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                    <h2 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>Untuk Apa Fitur Ini &amp; Bagaimana Cara Kerjanya?</span>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-ocean-100 text-ocean-700">Mudah &amp; Transparan</span>
                    </h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-1 text-xs text-slate-600">
                    <div class="bg-white/90 rounded-xl p-3 border border-ocean-100/80 shadow-2xs">
                        <span class="font-bold text-slate-900 block mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-bullseye text-ocean-600 text-[11px]"></i>
                            <span>1. Menghilangkan Bias Pribadi</span>
                        </span>
                        <p class="text-slate-500 text-[11px] leading-relaxed">
                            Rekomendasi tidak dibuat berdasarkan sponsor, tetapi dihitung murni dari data jarak riil, jumlah fasilitas sekitar, serta ribuan ulasan publik.
                        </p>
                    </div>
                    <div class="bg-white/90 rounded-xl p-3 border border-ocean-100/80 shadow-2xs">
                        <span class="font-bold text-slate-900 block mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-sliders text-indigo-600 text-[11px]"></i>
                            <span>2. Bisa Disesuaikan Kebutuhan</span>
                        </span>
                        <p class="text-slate-500 text-[11px] leading-relaxed">
                            Pilih tombol persona cepat di bawah: Apakah Anda tipe wisatawan hemat yang ingin dekat bandara, atau keluarga yang mencari hotel terlengkap.
                        </p>
                    </div>
                    <div class="bg-white/90 rounded-xl p-3 border border-ocean-100/80 shadow-2xs">
                        <span class="font-bold text-slate-900 block mb-1 flex items-center gap-1.5">
                            <i class="fa-solid fa-chart-line text-emerald-600 text-[11px]"></i>
                            <span>3. Skor Kedekatan Ideal ($V_i$)</span>
                        </span>
                        <p class="text-slate-500 text-[11px] leading-relaxed">
                            Skor berkisar antara 0.00 hingga 1.00 (atau 0% - 100%). Semakin tinggi skornya, semakin mendekati kondisi destinasi sempurna.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3. PRESET PERSONA & FILTER CEPAT                               -->
    <!-- ============================================================== -->
    <div class="bg-white rounded-3xl p-4 sm:p-6 border border-slate-200/80 shadow-xs space-y-5">
        
        <!-- Header Persona -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-tag text-ocean-600"></i>
                    <span>Pilih Profil Gaya Wisata Anda (1-Klik Persona)</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Sistem akan langsung mengatur komposisi bobot penilaian kriteria yang paling cocok</p>
            </div>
            <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full self-start sm:self-auto">
                Total Bobot Otomatis 100%
            </span>
        </div>

        <!-- Tombol Persona -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            <button type="button" onclick="applyPreset(30, 25, 25, 20)" 
                class="p-3 text-left rounded-2xl bg-slate-50 hover:bg-ocean-50/80 border border-slate-200 hover:border-ocean-300 transition-all group">
                <div class="w-7 h-7 rounded-lg bg-ocean-100 text-ocean-700 flex items-center justify-center text-xs mb-2 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <div class="font-bold text-slate-900 text-xs">Standar Riset</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Rating 30%, Akses 25%, Amenitas 25%, Ulasan 20%</div>
            </button>

            <button type="button" onclick="applyPreset(30, 10, 45, 15)" 
                class="p-3 text-left rounded-2xl bg-slate-50 hover:bg-sky-50/80 border border-slate-200 hover:border-sky-300 transition-all group">
                <div class="w-7 h-7 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center text-xs mb-2 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-people-roof"></i>
                </div>
                <div class="font-bold text-slate-900 text-xs">Wisata Keluarga</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Fokus Amenitas Hotel &amp; Resto Lengkap (45%)</div>
            </button>

            <button type="button" onclick="applyPreset(20, 50, 15, 15)" 
                class="p-3 text-left rounded-2xl bg-slate-50 hover:bg-purple-50/80 border border-slate-200 hover:border-purple-300 transition-all group">
                <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-xs mb-2 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-person-hiking"></i>
                </div>
                <div class="font-bold text-slate-900 text-xs">Backpacker / Mandiri</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Fokus Akses Dekat Bandara / Pelabuhan (50%)</div>
            </button>

            <button type="button" onclick="applyPreset(35, 10, 15, 40)" 
                class="p-3 text-left rounded-2xl bg-slate-50 hover:bg-amber-50/80 border border-slate-200 hover:border-amber-300 transition-all group">
                <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-xs mb-2 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-fire"></i>
                </div>
                <div class="font-bold text-slate-900 text-xs">Paling Populer &amp; Viral</div>
                <div class="text-[11px] text-slate-500 mt-0.5">Fokus Banyak Ulasan (40%) &amp; Bintang Tinggi</div>
            </button>
        </div>

        <!-- Form Pengaturan Lanjutan (Slider & Filter) -->
        <form method="GET" action="index.php" id="topsisForm" class="pt-3 border-t border-slate-100 space-y-4">
            <input type="hidden" name="page" value="rekomendasi">

            <!-- Detail Slider Kriteria -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                
                <!-- C1: Rating -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center text-[10px] font-black">C1</span>
                            <span>Rating Google</span>
                        </span>
                        <span id="label_wRating" class="font-black text-ocean-600"><?= (int)$wRating ?>%</span>
                    </div>
                    <input type="range" name="w_rating" id="slider_wRating" min="5" max="60" step="5" value="<?= (int)$wRating ?>" 
                        oninput="updateWeightLabels()" class="w-full accent-ocean-600 cursor-pointer">
                    <div class="text-[10px] text-slate-400">Tingkat kepuasan bintang (Benefit)</div>
                </div>

                <!-- C2: Hub Transportasi -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-purple-100 text-purple-700 flex items-center justify-center text-[10px] font-black">C2</span>
                            <span>Akses Simpul</span>
                        </span>
                        <span id="label_wHub" class="font-black text-ocean-600"><?= (int)$wHub ?>%</span>
                    </div>
                    <input type="range" name="w_hub" id="slider_wHub" min="5" max="60" step="5" value="<?= (int)$wHub ?>" 
                        oninput="updateWeightLabels()" class="w-full accent-ocean-600 cursor-pointer">
                    <div class="text-[10px] text-slate-400">Jarak ke bandara/pelabuhan (Cost)</div>
                </div>

                <!-- C3: Amenitas 5km -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-sky-100 text-sky-700 flex items-center justify-center text-[10px] font-black">C3</span>
                            <span>Amenitas 5 km</span>
                        </span>
                        <span id="label_wAmenities" class="font-black text-ocean-600"><?= (int)$wAmenities ?>%</span>
                    </div>
                    <input type="range" name="w_amenities" id="slider_wAmenities" min="5" max="60" step="5" value="<?= (int)$wAmenities ?>" 
                        oninput="updateWeightLabels()" class="w-full accent-ocean-600 cursor-pointer">
                    <div class="text-[10px] text-slate-400">Hotel &amp; resto sekitar (Benefit)</div>
                </div>

                <!-- C4: Volume Ulasan -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-black">C4</span>
                            <span>Popularitas</span>
                        </span>
                        <span id="label_wUlasan" class="font-black text-ocean-600"><?= (int)$wUlasan ?>%</span>
                    </div>
                    <input type="range" name="w_ulasan" id="slider_wUlasan" min="5" max="60" step="5" value="<?= (int)$wUlasan ?>" 
                        oninput="updateWeightLabels()" class="w-full accent-ocean-600 cursor-pointer">
                    <div class="text-[10px] text-slate-400">Total volume ulasan publik (Benefit)</div>
                </div>

            </div>

            <!-- Filter Wilayah & Tombol Hitung -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pt-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs w-full lg:w-auto">
                    <select name="kabupaten" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700 text-xs">
                        <option value="">Semua Wilayah (Bangka Belitung)</option>
                        <?php foreach ($kabList as $k): ?>
                        <option value="<?= htmlspecialchars($k) ?>" <?= $filterKab === $k ? 'selected' : '' ?>><?= htmlspecialchars($k) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="sub_kategori" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700 text-xs">
                        <option value="">Semua Kategori Wisata</option>
                        <option value="Alam" <?= $filterSub === 'Alam' ? 'selected' : '' ?>>Wisata Alam</option>
                        <option value="Buatan" <?= $filterSub === 'Buatan' ? 'selected' : '' ?>>Wisata Buatan</option>
                        <option value="Kebudayaan" <?= $filterSub === 'Kebudayaan' ? 'selected' : '' ?>>Budaya &amp; Sejarah</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <a href="index.php?page=rekomendasi" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-all">
                        Reset
                    </a>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white text-xs font-bold shadow-md shadow-ocean-500/20 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-rotate text-xs"></i>
                        <span>Hitung Ulang Rekomendasi</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ============================================================== -->
    <!-- 4. PODIUM 3 DESTINASI TERBAIK (TOP 3 HIGHLIGHT)                 -->
    <!-- ============================================================== -->
    <?php if (!empty($top10)): ?>
    <div>
        <div class="mb-4">
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i>
                <span>Podium 3 Destinasi Rekomendasi Tertinggi</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Destinasi dengan perpaduan paling sempurna antara rating, kemudahan akses, dan kelengkapan fasilitas</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php 
            $medals = [
                0 => ['bg' => 'from-amber-500/10 via-amber-50 to-white', 'border' => 'border-amber-300', 'badge' => 'bg-amber-500 text-white', 'title' => 'Juara 1 (Emas)', 'icon' => 'fa-crown'],
                1 => ['bg' => 'from-slate-400/10 via-slate-50 to-white', 'border' => 'border-slate-300', 'badge' => 'bg-slate-500 text-white', 'title' => 'Juara 2 (Perak)', 'icon' => 'fa-medal'],
                2 => ['bg' => 'from-amber-700/10 via-orange-50 to-white', 'border' => 'border-orange-300', 'badge' => 'bg-amber-700 text-white', 'title' => 'Juara 3 (Perunggu)', 'icon' => 'fa-award']
            ];
            for ($i = 0; $i < min(3, count($top10)); $i++): 
                $item = $top10[$i];
                $m = $medals[$i];
            ?>
            <div class="rounded-3xl p-4 sm:p-5 border <?= $m['border'] ?> bg-gradient-to-b <?= $m['bg'] ?> shadow-xs hover:shadow-lg transition-all flex flex-col justify-between space-y-3 sm:space-y-4 group">
                <div>
                    <!-- Foto Destinasi Unggulan Podium -->
                    <div class="relative h-44 w-full rounded-2xl overflow-hidden mb-3.5 shadow-sm border border-black/5">
                        <img src="<?= htmlspecialchars(getDestinationPhoto($item['nama'], $item['sub_kategori'])) ?>" 
                             alt="<?= htmlspecialchars($item['nama']) ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
                        <div class="absolute top-2.5 left-2.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold shadow-md <?= $m['badge'] ?>">
                                <i class="fa-solid <?= $m['icon'] ?>"></i>
                                <span><?= $m['title'] ?></span>
                            </span>
                        </div>
                        <div class="absolute top-2.5 right-2.5">
                            <span class="text-xs font-black text-slate-900 bg-white/95 backdrop-blur-md px-2 py-0.5 rounded-lg shadow-md border border-white/50">
                                Skor: <?= number_format($item['score_v'], 4) ?>
                            </span>
                        </div>
                        <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-[11px] text-white font-semibold drop-shadow">
                            <span class="flex items-center gap-1 truncate max-w-[140px]">
                                <i class="fa-solid fa-location-dot text-amber-300 text-[10px]"></i>
                                <?= htmlspecialchars($item['kabupaten']) ?>
                            </span>
                            <span class="bg-white/25 backdrop-blur-xs px-2 py-0.5 rounded-full text-[10px] text-white">
                                <?= htmlspecialchars($item['sub_kategori']) ?>
                            </span>
                        </div>
                    </div>

                    <h3 class="text-base font-extrabold text-slate-900 line-clamp-1" title="<?= htmlspecialchars($item['nama']) ?>">
                        <?= htmlspecialchars($item['nama']) ?>
                    </h3>
                    <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                        <span class="font-medium text-slate-700">Kec. <?= htmlspecialchars($item['kecamatan']) ?></span>
                        <span>&bull;</span>
                        <span class="text-emerald-600 font-semibold"><?= round($item['score_v'] * 100, 1) ?>% Ideal TOPSIS</span>
                    </div>

                    <!-- Indikator 4 Kriteria -->
                    <div class="grid grid-cols-2 gap-2 mt-3.5 text-[11px]">
                        <div class="bg-white/90 p-2 rounded-xl border border-slate-100 shadow-2xs">
                            <span class="text-slate-400 block text-[10px] font-medium">Rating &amp; Ulasan:</span>
                            <span class="font-bold text-amber-600">★ <?= $item['rating'] ?> (<?= number_format($item['ulasan']) ?>)</span>
                        </div>
                        <div class="bg-white/90 p-2 rounded-xl border border-slate-100 shadow-2xs">
                            <span class="text-slate-400 block text-[10px] font-medium">Akses Hub:</span>
                            <span class="font-bold text-purple-700"><?= $item['hub_dist'] ?> km</span>
                        </div>
                        <div class="bg-white/90 p-2 rounded-xl border border-slate-100 shadow-2xs col-span-2">
                            <span class="text-slate-400 block text-[10px] font-medium">Amenitas 5 km Sekitar:</span>
                            <span class="font-bold text-sky-700"><?= $item['amenities'] ?> Unit Hotel &amp; Resto</span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Navigasi -->
                <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between gap-2">
                    <a href="index.php?page=peta&q=<?= urlencode($item['nama']) ?>" class="flex-1 text-center py-2 px-3 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-bold text-xs shadow-xs transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-map-pin text-[11px]"></i>
                        <span>Lihat di Peta</span>
                    </a>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($item['nama'] . ' Bangka Belitung') ?>" target="_blank" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs transition-colors" title="Buka Google Maps">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ============================================================== -->
    <!-- 5. TABEL TOP 10 DESTINASI (BERSIH & RAPI)                      -->
    <!-- ============================================================== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-ocean-600"></i>
                    <span>Peringkat 10 Besar Hasil Rekomendasi</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Dihitung otomatis dari perbandingan kedekatan relatif seluruh alternatif destinasi</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                Top 10 Terunggul
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3 text-center w-12">Rank</th>
                        <th class="px-4 py-3">Nama Destinasi</th>
                        <th class="px-4 py-3">Wilayah</th>
                        <th class="px-3 py-3 text-center">Rating</th>
                        <th class="px-3 py-3 text-center">Jarak ke Hub</th>
                        <th class="px-3 py-3 text-center">Amenitas (5km)</th>
                        <th class="px-4 py-3 text-right">Skor TOPSIS ($V_i$)</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php foreach ($top10 as $rank => $item): 
                        $rankNum = $rank + 1;
                    ?>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-3.5 text-center">
                            <span class="w-6 h-6 rounded-full inline-flex items-center justify-center font-black text-xs <?= $rankNum <= 3 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' ?>">
                                <?= $rankNum ?>
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <img src="<?= htmlspecialchars(getDestinationPhoto($item['nama'], $item['sub_kategori'])) ?>" 
                                     alt="<?= htmlspecialchars($item['nama']) ?>"
                                     class="w-11 h-11 rounded-xl object-cover border border-slate-200 shadow-2xs shrink-0">
                                <div>
                                    <div class="font-bold text-slate-900 leading-snug"><?= htmlspecialchars($item['nama']) ?></div>
                                    <div class="text-[11px] text-slate-400 mt-0.5"><?= htmlspecialchars($item['sub_kategori']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <div class="text-slate-800"><?= htmlspecialchars($item['kabupaten']) ?></div>
                            <div class="text-[10px] text-slate-400">Kec. <?= htmlspecialchars($item['kecamatan']) ?></div>
                        </td>
                        <td class="px-3 py-3.5 text-center whitespace-nowrap">
                            <span class="font-bold text-amber-500">★ <?= $item['rating'] ?></span>
                            <span class="text-[10px] text-slate-400 block"><?= number_format($item['ulasan']) ?> ulasan</span>
                        </td>
                        <td class="px-3 py-3.5 text-center whitespace-nowrap">
                            <span class="font-bold text-slate-800"><?= $item['hub_dist'] ?> km</span>
                            <span class="text-[10px] text-slate-400 block truncate max-w-[100px] mx-auto"><?= htmlspecialchars($item['closest_hub']) ?></span>
                        </td>
                        <td class="px-3 py-3.5 text-center whitespace-nowrap">
                            <span class="font-bold text-sky-700"><?= $item['amenities'] ?> Unit</span>
                            <span class="text-[10px] text-slate-400 block">Hotel &amp; Resto</span>
                        </td>
                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            <div class="font-extrabold text-sm text-ocean-700"><?= number_format($item['score_v'], 4) ?></div>
                            <div class="text-[10px] font-semibold text-emerald-600"><?= round($item['score_v'] * 100, 1) ?>% Ideal</div>
                        </td>
                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                            <a href="index.php?page=peta&q=<?= urlencode($item['nama']) ?>" class="px-2.5 py-1.5 rounded-lg bg-ocean-50 hover:bg-ocean-100 text-ocean-700 font-bold text-[11px] transition-colors inline-flex items-center gap-1">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>Peta</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 6. ACCORDION RINCIAN MATEMATIS TOPSIS (KHUSUS PENGUJI/DOSEN)   -->
    <!-- ============================================================== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <button type="button" onclick="toggleDefenseAccordion()" class="w-full p-4 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50/80 transition-colors">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-square-root-variable"></i>
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <span>📐 Lihat Pembuktian Rumus &amp; Matriks TOPSIS Lengkap</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">Khusus Penguji / Peneliti</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Klik untuk membuka matriks normalisasi ($R$), matriks terbobot ($Y$), vektor solusi ideal ($A^+/A^-$), dan jarak Euclidean ($D^+/D^-$)</p>
                </div>
            </div>
            <div id="accordionIcon" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs transition-transform duration-300 shrink-0">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </button>

        <!-- Accordion Body -->
        <div id="defenseBody" class="hidden p-4 sm:p-6 lg:p-8 border-t border-slate-100 space-y-6 bg-slate-50/50">
            
            <!-- Ringkasan 5 Tahapan Algoritma -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-1.5">
                    <div class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-ocean-600 text-white flex items-center justify-center text-[10px]">1</span>
                        <span>Normalisasi Vektor ($r_{ij}$)</span>
                    </div>
                    <p class="text-slate-500 text-[11px] leading-relaxed">
                        Membagi nilai elemen matriks keputusan awal dengan akar jumlah kuadrat seluruh alternatif:
                    </p>
                    <div class="p-2 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] text-center">
                        r_ij = x_ij / &radic;(&sum; x_kj&sup2;)
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-1.5">
                    <div class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-ocean-600 text-white flex items-center justify-center text-[10px]">2</span>
                        <span>Matriks Terbobot ($y_{ij}$)</span>
                    </div>
                    <p class="text-slate-500 text-[11px] leading-relaxed">
                        Mengalikan nilai normalisasi dengan bobot kriteria preferensi ($w_j$):
                    </p>
                    <div class="p-2 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] text-center">
                        y_ij = w_j &times; r_ij
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-1.5">
                    <div class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-ocean-600 text-white flex items-center justify-center text-[10px]">3</span>
                        <span>Preferensi Relatif ($V_i$)</span>
                    </div>
                    <p class="text-slate-500 text-[11px] leading-relaxed">
                        Rasio jarak keterjauhan dari solusi negatif terhadap total jarak Euclidean:
                    </p>
                    <div class="p-2 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] text-center">
                        V_i = D_i&oline; / (D_i&oline; + D_i&plus;)
                    </div>
                </div>
            </div>

            <!-- Vektor Solusi Ideal -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-2">
                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">
                    Vektor Solusi Ideal Positif ($A^+$) dan Negatif ($A^-$)
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-mono">
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-[11px] space-y-1">
                        <span class="font-bold block text-emerald-700">A+ (Solusi Ideal Positif - Kondisi Sempurna):</span>
                        <div>C1 (Rating Max) : <?= $aPlus['rating'] ?? 0 ?></div>
                        <div>C2 (Hub Min) &nbsp; : <?= $aPlus['hub_dist'] ?? 0 ?> km</div>
                        <div>C3 (Amenitas Max): <?= $aPlus['amenities'] ?? 0 ?> unit</div>
                        <div>C4 (Ulasan Max) &nbsp;: <?= $aPlus['ulasan'] ?? 0 ?></div>
                    </div>
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-[11px] space-y-1">
                        <span class="font-bold block text-rose-700">A- (Solusi Ideal Negatif - Kondisi Terburuk):</span>
                        <div>C1 (Rating Min) : <?= $aMinus['rating'] ?? 0 ?></div>
                        <div>C2 (Hub Max) &nbsp; : <?= $aMinus['hub_dist'] ?? 0 ?> km</div>
                        <div>C3 (Amenitas Min): <?= $aMinus['amenities'] ?? 0 ?> unit</div>
                        <div>C4 (Ulasan Min) &nbsp;: <?= $aMinus['ulasan'] ?? 0 ?></div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data 30 Alternatif Lengkap -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="p-3.5 bg-slate-100 border-b border-slate-200 flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-800">Tabel Seluruh Alternatif (Jarak Euclidean &amp; Ranking Lengkap)</span>
                    <span class="text-slate-500 font-semibold"><?= count($rankedList) ?> Alternatif Terhitung</span>
                </div>
                <div class="overflow-x-auto max-h-96 no-scrollbar">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-400 font-semibold sticky top-0 border-b border-slate-200">
                            <tr>
                                <th class="px-3 py-2 text-center">Rank</th>
                                <th class="px-3 py-2">Nama Alternatif</th>
                                <th class="px-3 py-2">Wilayah</th>
                                <th class="px-2 py-2 text-center">D+ (Jarak Ideal Positif)</th>
                                <th class="px-2 py-2 text-center">D- (Jarak Ideal Negatif)</th>
                                <th class="px-3 py-2 text-right">Skor Akhir ($V_i$)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                            <?php foreach ($rankedList as $idx => $r): ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-3 py-2 text-center font-bold font-sans text-slate-800">#<?= $idx + 1 ?></td>
                                <td class="px-3 py-2 font-sans font-medium text-slate-900"><?= htmlspecialchars($r['nama']) ?></td>
                                <td class="px-3 py-2 font-sans text-slate-500"><?= htmlspecialchars($r['kabupaten']) ?></td>
                                <td class="px-2 py-2 text-center text-slate-600"><?= $r['d_plus'] ?></td>
                                <td class="px-2 py-2 text-center text-slate-600"><?= $r['d_minus'] ?></td>
                                <td class="px-3 py-2 text-right font-bold text-ocean-700"><?= number_format($r['score_v'], 4) ?></td>
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
function applyPreset(r, h, a, u) {
    document.getElementById('slider_wRating').value = r;
    document.getElementById('slider_wHub').value = h;
    document.getElementById('slider_wAmenities').value = a;
    document.getElementById('slider_wUlasan').value = u;
    updateWeightLabels();
    document.getElementById('topsisForm').submit();
}

function updateWeightLabels() {
    const r = parseInt(document.getElementById('slider_wRating').value);
    const h = parseInt(document.getElementById('slider_wHub').value);
    const a = parseInt(document.getElementById('slider_wAmenities').value);
    const u = parseInt(document.getElementById('slider_wUlasan').value);

    document.getElementById('label_wRating').innerText = r + '%';
    document.getElementById('label_wHub').innerText = h + '%';
    document.getElementById('label_wAmenities').innerText = a + '%';
    document.getElementById('label_wUlasan').innerText = u + '%';

    const total = r + h + a + u;
    const badge = document.getElementById('badgeTotalWeight');
    if (badge) {
        badge.innerText = total + '%';
        if (total === 100) {
            badge.className = 'text-emerald-600 font-extrabold';
        } else {
            badge.className = 'text-amber-600 font-extrabold';
        }
    }
}

function toggleDefenseAccordion() {
    const body = document.getElementById('defenseBody');
    const icon = document.getElementById('accordionIcon');
    if (body.classList.contains('hidden')) {
        body.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        body.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

function exportTopsisCSV() {
    const tableData = <?= json_encode(array_map(function($item, $idx) {
        return [
            'Rank' => $idx + 1,
            'Nama' => $item['nama'],
            'Kabupaten' => $item['kabupaten'],
            'Kecamatan' => $item['kecamatan'],
            'Kategori' => $item['sub_kategori'],
            'Rating' => $item['rating'],
            'Ulasan' => $item['ulasan'],
            'Jarak_Hub_KM' => $item['hub_dist'],
            'Amenitas_5KM' => $item['amenities'],
            'Skor_TOPSIS' => $item['score_v']
        ];
    }, $rankedList, array_keys($rankedList))) ?>;

    if (!tableData || tableData.length === 0) {
        alert('Tidak ada data rekomendasi untuk diekspor.');
        return;
    }

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += Object.keys(tableData[0]).join(",") + "\n";

    tableData.forEach(row => {
        const values = Object.values(row).map(val => `"${String(val).replace(/"/g, '""')}"`);
        csvContent += values.join(",") + "\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "Rekomendasi_TOPSIS_Babel_Pariwisata.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
