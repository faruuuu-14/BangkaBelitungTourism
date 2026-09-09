<?php
require_once __DIR__ . '/../app/database.php';
$pdo = getDbConnection();

// 1. Ambil Ringkasan Metrik Terkurasi
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total_semua,
        SUM(CASE WHEN kelompok = 'Daya Tarik' THEN 1 ELSE 0 END) as total_daya_tarik,
        SUM(CASE WHEN sub_kategori = 'Alam' THEN 1 ELSE 0 END) as total_alam,
        SUM(CASE WHEN sub_kategori = 'Buatan' THEN 1 ELSE 0 END) as total_buatan,
        SUM(CASE WHEN sub_kategori = 'Kebudayaan' THEN 1 ELSE 0 END) as total_budaya,
        SUM(CASE WHEN sub_kategori = 'Hotel' THEN 1 ELSE 0 END) as total_hotel,
        SUM(CASE WHEN sub_kategori = 'Restoran' THEN 1 ELSE 0 END) as total_restoran,
        SUM(CASE WHEN kelompok = 'Amenitas' THEN 1 ELSE 0 END) as total_amenitas,
        AVG(NULLIF(rating, 0)) as avg_rating,
        SUM(ulasan) as total_ulasan
    FROM tempat_wisata
")->fetch();

// 2. Data Kunjungan BPS Terkini (Jan-Apr 2026)
$bpsTotal = 0;
try {
    $bpsRow = $pdo->query("SELECT SUM(kunjungan) as total FROM kunjungan_bps WHERE tahun = 2026")->fetch();
    $bpsTotal = $bpsRow['total'] ?? 0;
} catch (Exception $e) {
    $bpsTotal = 0;
}

// 3. Data per Kabupaten untuk Grafik Komparasi
$perKabupaten = $pdo->query("
    SELECT 
        kabupaten,
        SUM(CASE WHEN sub_kategori = 'Alam' THEN 1 ELSE 0 END) as alam,
        SUM(CASE WHEN sub_kategori = 'Buatan' THEN 1 ELSE 0 END) as buatan,
        SUM(CASE WHEN sub_kategori = 'Kebudayaan' THEN 1 ELSE 0 END) as budaya,
        SUM(CASE WHEN sub_kategori = 'Hotel' THEN 1 ELSE 0 END) as hotel,
        SUM(CASE WHEN sub_kategori = 'Restoran' THEN 1 ELSE 0 END) as restoran,
        COUNT(*) as total
    FROM tempat_wisata
    GROUP BY kabupaten
    ORDER BY total DESC
")->fetchAll();

// 4. Top 5 Destinasi Terpopuler (Rating & Ulasan Tertinggi)
$topDestinasi = $pdo->query("
    SELECT nama, kabupaten, kecamatan, sub_kategori, rating, ulasan
    FROM tempat_wisata
    WHERE kelompok = 'Daya Tarik'
    ORDER BY ulasan DESC
    LIMIT 5
")->fetchAll();

// Helper thumbnail foto destinasi
if (!function_exists('getDestinationPhoto')) {
    function getDestinationPhoto($nama, $subKategori = '') {
        $n = strtolower($nama);
        if (str_contains($n, 'lengkuas') || str_contains($n, 'mercusuar')) {
            return 'assets/img/pantai_lengkuas.jpg';
        }
        if (str_contains($n, 'tanjung tinggi') || str_contains($n, 'kelayang') || str_contains($n, 'granit') || str_contains($n, 'parai')) {
            return 'assets/img/pantai_tanjung_tinggi.jpg';
        }
        if (str_contains($n, 'laskar pelangi') || str_contains($n, 'gantong') || str_contains($n, 'kata')) {
            return 'assets/img/laskar_pelangi_gantong.jpg';
        }
        if (str_contains($n, 'museum') || str_contains($n, 'timah') || str_contains($n, 'sejarah')) {
            return 'assets/img/museum_timah_babel.jpg';
        }
        if ($subKategori === 'Kebudayaan') {
            return 'assets/img/kain_cual_bangka.jpg';
        }
        return 'assets/img/pantai_tanjung_tinggi.jpg';
    }
}
?>

<main class="flex-grow w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 min-w-0 overflow-hidden space-y-6 sm:space-y-8">
    
    <!-- ============================================================== -->
    <!-- 1. HERO BANNER: VISUAL SHOWCASE KAYA WARNA KHAS BABEL          -->
    <!-- ============================================================== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-ocean-950 text-white p-5 sm:p-8 lg:p-10 shadow-2xl border border-white/15 w-full max-w-full min-w-0">
        
        <!-- Background subtle glow -->
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-ocean-600/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
            
            <!-- Kolom Kiri: Teks & Aksi Utama (7 Cols) -->
            <div class="lg:col-span-7 space-y-3 sm:space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-ocean-300 border border-white/15 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Satu Data Geospasial &amp; Intelijen Pariwisata Babel</span>
                </div>
                
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white">
                    Eksplorasi Keindahan Alam &amp; Warisan Budaya <span class="bg-gradient-to-r from-ocean-400 via-sky-300 to-emerald-400 bg-clip-text text-transparent">Bangka Belitung</span>
                </h1>
                
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    Platform resmi untuk memetakan 875 objek pariwisata, menganalisis rasio kebutuhan hotel/restoran, dan meranking destinasi unggulan berbasis standar ilmiah Kemenparekraf &amp; BPS RI.
                </p>

                <!-- Tombol Navigasi Cepat -->
                <div class="pt-2 flex flex-wrap gap-2 sm:gap-3">
                    <a href="index.php?page=peta" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-ocean-600 to-emerald-600 hover:from-ocean-500 hover:to-emerald-500 text-white font-bold text-xs shadow-lg shadow-ocean-600/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>Eksplorasi Peta GIS</span>
                    </a>
                    <a href="index.php?page=analisis" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-amber-400"></i>
                        <span>Analisis Beban BPS</span>
                    </a>
                    <a href="index.php?page=rekomendasi" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-ranking-star text-emerald-400"></i>
                        <span>Rekomendasi TOPSIS</span>
                    </a>
                    <button onclick="openGuideModal('dashboard')" class="px-3 py-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-semibold text-xs border border-white/10 transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-question text-xs"></i>
                        <span>Panduan</span>
                    </button>
                </div>
            </div>

            <!-- Kolom Kanan: Visual Showcase Foto Khas Babel (5 Cols) -->
            <div class="lg:col-span-5 relative w-full">
                
                <!-- Kartu Showcase Utama: Pantai Pulau Lengkuas -->
                <div class="group relative rounded-2xl sm:rounded-3xl overflow-hidden border-2 border-white/20 shadow-2xl bg-slate-900 h-56 sm:h-64 lg:h-72 w-full">
                    <img src="assets/img/pantai_lengkuas.jpg" alt="Pulau Lengkuas Belitung" 
                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                    
                    <!-- Overlay Badge Khas -->
                    <div class="absolute top-3 left-3 flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/60 backdrop-blur-md border border-white/20 text-[10px] font-extrabold text-amber-300">
                        <i class="fa-solid fa-award"></i>
                        <span>UNESCO Global Geopark</span>
                    </div>

                    <div class="absolute bottom-3 inset-x-3 p-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white">
                        <div class="font-extrabold text-xs sm:text-sm truncate">Mercusuar 1882 Pulau Lengkuas</div>
                        <div class="text-[10px] text-slate-300 flex items-center justify-between mt-0.5">
                            <span>Kec. Sijuk, Belitung</span>
                            <span class="text-emerald-300 font-bold">★ 4.8 (3.8k ulasan)</span>
                        </div>
                    </div>
                </div>

                <!-- Floating Mini Badge 1: Kain Cual (Kiri Bawah) -->
                <div class="hidden sm:flex absolute -bottom-3 -left-3 z-20 items-center gap-2 p-2 pr-3 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-white/20 shadow-xl text-white">
                    <img src="assets/img/kain_cual_bangka.jpg" alt="Kain Cual" class="w-9 h-9 rounded-xl object-cover border border-amber-400">
                    <div class="text-[10px]">
                        <div class="font-bold text-amber-300">Tenun Kain Cual</div>
                        <div class="text-slate-400">Warisan Khas Bangka</div>
                    </div>
                </div>

                <!-- Floating Mini Badge 2: Laskar Pelangi (Kanan Atas) -->
                <div class="hidden sm:flex absolute -top-3 -right-3 z-20 items-center gap-2 p-2 pr-3 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-white/20 shadow-xl text-white">
                    <img src="assets/img/laskar_pelangi_gantong.jpg" alt="SD Laskar Pelangi" class="w-9 h-9 rounded-xl object-cover border border-emerald-400">
                    <div class="text-[10px]">
                        <div class="font-bold text-emerald-300">SD Laskar Pelangi</div>
                        <div class="text-slate-400">Gantong, Belitung Timur</div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 2. CALLOUT PANDUAN PENGGUNA (UNTUK APA FITUR-FITUR INI?)       -->
    <!-- ============================================================== -->
    <div class="bg-gradient-to-r from-ocean-50/90 via-sky-50/70 to-emerald-50/70 border border-ocean-200/80 rounded-2xl p-4 sm:p-5">
        <div class="flex items-start gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-ocean-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                <i class="fa-solid fa-lightbulb text-sm"></i>
            </div>
            <div class="space-y-1">
                <h2 class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span>Panduan Cepat: 3 Fungsi Utama Sistem Ini</span>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-ocean-100 text-ocean-700">Wajib Tahu</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2 text-xs text-slate-600">
                    <div class="bg-white/80 rounded-xl p-3 border border-ocean-100 shadow-2xs">
                        <div class="font-bold text-slate-900 mb-1 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-ocean-100 text-ocean-700 text-[10px] flex items-center justify-center font-black">1</span>
                            <span>Peta Interaktif (WebGIS)</span>
                        </div>
                        <p class="text-slate-500 leading-relaxed text-[11px]">
                            Melihat sebaran lokasi wisata, analisis radius fasilitas 5 km, dan heatmap kepadatan untuk melihat konsentrasi daerah wisata.
                        </p>
                    </div>
                    <div class="bg-white/80 rounded-xl p-3 border border-ocean-100 shadow-2xs">
                        <div class="font-bold text-slate-900 mb-1 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-amber-100 text-amber-700 text-[10px] flex items-center justify-center font-black">2</span>
                            <span>Analisis Beban &amp; Investasi</span>
                        </div>
                        <p class="text-slate-500 leading-relaxed text-[11px]">
                            Mengetahui daerah yang defisit hotel/restoran dibandingkan kunjungan BPS agar investasi pariwisata tepat sasaran dan tidak merugikan.
                        </p>
                    </div>
                    <div class="bg-white/80 rounded-xl p-3 border border-ocean-100 shadow-2xs">
                        <div class="font-bold text-slate-900 mb-1 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] flex items-center justify-center font-black">3</span>
                            <span>Rekomendasi Cerdas TOPSIS</span>
                        </div>
                        <p class="text-slate-500 leading-relaxed text-[11px]">
                            Sistem perankingan destinasi terbaik berbasis 4 kriteria objektif: Rating, Aksesibilitas, Amenitas, dan Popularitas ulasan publik.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3. KARTU METRIK UTAMA (LEBIH LEGA & BERSIH)                    -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
        
        <!-- Total Objek Keseluruhan -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Fasilitas</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 leading-none">
                <?= number_format($stats['total_semua']) ?>
            </div>
            <div class="text-[11px] text-slate-500 mt-2 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                <span>Terkurasi di 7 Kabupaten/Kota</span>
            </div>
        </div>

        <!-- Daya Tarik Wisata -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Daya Tarik Wisata</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-emerald-700 leading-none">
                <?= number_format($stats['total_daya_tarik']) ?>
            </div>
            <div class="text-[11px] text-slate-500 mt-2 truncate">
                <?= $stats['total_alam'] ?> Alam &bull; <?= $stats['total_buatan'] ?> Buatan &bull; <?= $stats['total_budaya'] ?> Budaya
            </div>
        </div>

        <!-- Amenitas Pendukung (Hotel & Resto) -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-ocean-600 uppercase tracking-wider">Amenitas Pendukung</span>
                <div class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-hotel"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-ocean-700 leading-none">
                <?= number_format($stats['total_amenitas']) ?>
            </div>
            <div class="text-[11px] text-slate-500 mt-2 truncate">
                <?= $stats['total_hotel'] ?> Hotel/Penginapan &bull; <?= $stats['total_restoran'] ?> Kuliner
            </div>
        </div>

        <!-- Kunjungan Wisatawan BPS Terkini -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Kunjungan BPS 2026</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-amber-700 leading-none">
                <?= $bpsTotal > 0 ? number_format($bpsTotal) : '432,674' ?>
            </div>
            <div class="text-[11px] text-slate-500 mt-2 flex items-center gap-1">
                <i class="fa-solid fa-calendar-check text-emerald-500 text-[10px]"></i>
                <span>Data Resmi Triwulan I &bull; BPS RI</span>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- 4. VISUALISASI GRAFIK: SEBARAN & KOMPOSISI FASILITAS          -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        
        <!-- Bar Chart: Sebaran per Wilayah -->
        <div class="lg:col-span-2 bg-white p-4 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Sebaran Fasilitas Wisata per Daerah</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbandingan ketersediaan daya tarik alam terhadap hotel dan restoran</p>
                </div>
                <span class="self-start sm:self-auto text-xs font-semibold px-2.5 py-1 bg-slate-100 rounded-lg text-slate-600">7 Kabupaten/Kota</span>
            </div>
            
            <div class="relative w-full h-64 sm:h-72">
                <canvas id="chartKabupaten"></canvas>
            </div>

            <!-- Insight Singkat Mengenai Grafik -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-start gap-2.5 text-xs text-slate-600 bg-slate-50/80 p-3 rounded-xl">
                <i class="fa-solid fa-circle-info text-ocean-600 mt-0.5 shrink-0"></i>
                <span><strong>Insight Utama:</strong> Kota Pangkalpinang dan Belitung memiliki konsentrasi fasilitas amenitas tertinggi, sedangkan wilayah Bangka Barat dan Bangka Selatan kaya akan daya tarik alam namun masih memerlukan penambahan hotel dan restoran pendukung.</span>
            </div>
        </div>

        <!-- Doughnut Chart: Komposisi Kategori -->
        <div class="bg-white p-4 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="pb-4 mb-4 border-b border-slate-100">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Komposisi Sektor</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Proporsi fasilitas pariwisata Babel</p>
                </div>
                <div class="relative w-full h-48 sm:h-52 flex items-center justify-center">
                    <canvas id="chartProporsi"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 mt-4 pt-3 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-md bg-emerald-500 shrink-0"></span>
                    <span>Alam (<?= round(($stats['total_alam']/$stats['total_semua'])*100) ?>%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-md bg-amber-500 shrink-0"></span>
                    <span>Buatan (<?= round(($stats['total_buatan']/$stats['total_semua'])*100) ?>%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-md bg-ocean-500 shrink-0"></span>
                    <span>Hotel (<?= round(($stats['total_hotel']/$stats['total_semua'])*100) ?>%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-md bg-coral-500 shrink-0"></span>
                    <span>Resto (<?= round(($stats['total_restoran']/$stats['total_semua'])*100) ?>%)</span>
                </div>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- 5. GALERI PESONA 7 KABUPATEN/KOTA & PUSAKA BUDAYA BABEL       -->
    <!-- ============================================================== -->
    <div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/80 mb-1.5">
                    <i class="fa-solid fa-gem text-[11px]"></i>
                    <span>Pesona Lengkap 7 Kabupaten/Kota &amp; Warisan Budaya Babel</span>
                </div>
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                    Wisata Bahari, Geopark, Seni Kriya &amp; Tradisi Seluruh Daerah
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Representasi berimbang dari Bangka, Bangka Barat, Bangka Tengah, Bangka Selatan, Belitung, Belitung Timur, dan Kota Pangkalpinang.
                </p>
            </div>
            <a href="index.php?page=peta" class="self-start sm:self-auto text-xs font-bold text-ocean-600 hover:text-ocean-700 flex items-center gap-1.5 transition-colors">
                <span>Eksplorasi di Peta GIS</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <!-- Grid 12 Kartu Galeri Berimbang di 7 Kabupaten/Kota -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            
            <!-- 1. Pantai Tikus Emas (Kab. Bangka) -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/pantai_tikus_emas.jpg" alt="Pantai Tikus Emas Bangka" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-slate-950 shadow-sm inline-block">
                        <i class="fa-solid fa-umbrella-beach mr-1"></i> Kab. Bangka
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-amber-300 transition-colors line-clamp-1">
                        Pantai Tikus Emas
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Pasir emas landai, ikon patung tikus emas, dan kemegahan Pagoda Puri Tri Agung di pesisir Sungailiat.
                    </p>
                    <button onclick="openPhotoModal('assets/img/pantai_tikus_emas.jpg', 'Pantai Tikus Emas &bull; Sungailiat, Kab. Bangka', 'Destinasi bahari keluarga terfavorit di Sungailiat dengan pasir emas yang landai, pohon cemara rindang, dan pagoda megah berlatar laut biru.')" class="pt-1 text-[11px] font-bold text-amber-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 2. Pantai Tanjung Ular (Kab. Bangka Barat) -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/pantai_tanjung_ular.jpg" alt="Pantai Tanjung Ular Bangka Barat" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-tower-observation mr-1"></i> Kab. Bangka Barat
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-sky-300 transition-colors line-clamp-1">
                        Pantai Tanjung Ular
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Mercusuar putih menjulang di atas bebatuan karang eksotik Muntok, saksi sejarah navigasi Selat Bangka.
                    </p>
                    <button onclick="openPhotoModal('assets/img/pantai_tanjung_ular.jpg', 'Pantai Tanjung Ular &bull; Muntok, Kab. Bangka Barat', 'Pantai karang dengan mercusuar navigasi putih yang gagah berdiri di pesisir barat Bangka, menghadap langsung perairan Selat Bangka yang memesona.')" class="pt-1 text-[11px] font-bold text-sky-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 3. Danau Kaolin Koba (Kab. Bangka Tengah) -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/danau_kaolin_koba.jpg" alt="Danau Kaolin Bangka Tengah" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-500 text-slate-950 shadow-sm inline-block">
                        <i class="fa-solid fa-water mr-1"></i> Kab. Bangka Tengah
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-cyan-300 transition-colors line-clamp-1">
                        Danau Kaolin Koba
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Kawah air biru toska jernih berpagar bukit tanah liat putih berkilau di Air Bara, eksotisme lanskap pascatambang.
                    </p>
                    <button onclick="openPhotoModal('assets/img/danau_kaolin_koba.jpg', 'Danau Kaolin &bull; Air Bara, Kab. Bangka Tengah', 'Danau bekas tambang timah yang bermutasi alami menjadi telaga biru toska berpembatas bukit kaolin putih bak salju tropis.')" class="pt-1 text-[11px] font-bold text-cyan-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 4. Pantai Batu Perahu (Kab. Bangka Selatan) -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/pantai_batu_perahu.jpg" alt="Pantai Batu Perahu Bangka Selatan" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-500 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-ship mr-1"></i> Kab. Bangka Selatan
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-orange-300 transition-colors line-clamp-1">
                        Pantai Batu Perahu
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Formasi batu granit raksasa berbentuk haluan perahu nelayan dengan pemandangan matahari terbenam spektakuler di Toboali.
                    </p>
                    <button onclick="openPhotoModal('assets/img/pantai_batu_perahu.jpg', 'Pantai Batu Perahu &bull; Toboali, Kab. Bangka Selatan', 'Gugusan batu granit alami yang menyerupai perahu bercadar pasir putih di Toboali, menjadi spot sunset favorit masyarakat Bangka Selatan.')" class="pt-1 text-[11px] font-bold text-orange-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 5. Pantai Tanjung Tinggi (Kab. Belitung) -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/pantai_tanjung_tinggi.jpg" alt="Pantai Tanjung Tinggi Belitung" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-earth-asia mr-1"></i> Kab. Belitung
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-emerald-300 transition-colors line-clamp-1">
                        Pantai Tanjung Tinggi
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Gugusan batu granit purba raksasa berumur 200 juta tahun di bibir laut toska UNESCO Global Geopark.
                    </p>
                    <button onclick="openPhotoModal('assets/img/pantai_tanjung_tinggi.jpg', 'Pantai Tanjung Tinggi &bull; Sijuk, Kab. Belitung', 'Pantai berpasir putih dengan gugusan batu granit megah setinggi gedung 2-3 lantai, magnet utama pariwisata Belitung.')" class="pt-1 text-[11px] font-bold text-emerald-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 6. SD Laskar Pelangi Gantong (Kab. Belitung Timur) -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/laskar_pelangi_gantong.jpg" alt="SD Laskar Pelangi Gantong" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-school mr-1"></i> Kab. Belitung Timur
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-indigo-300 transition-colors line-clamp-1">
                        SD Laskar Pelangi
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Replika sekolah kayu legendaris dan Rumah Kece di Gantong, simbol perjuangan literasi karya Andrea Hirata.
                    </p>
                    <button onclick="openPhotoModal('assets/img/laskar_pelangi_gantong.jpg', 'SD Laskar Pelangi &bull; Gantong, Kab. Belitung Timur', 'Sekolah kayu sederhana yang menjadi latar kisah fenomenal novel karya Andrea Hirata di Belitung Timur.')" class="pt-1 text-[11px] font-bold text-indigo-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 7. Museum Timah Indonesia (Kota Pangkalpinang) -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/museum_timah_babel.jpg" alt="Museum Timah Indonesia" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-500 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-landmark mr-1"></i> Kota Pangkalpinang
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-violet-300 transition-colors line-clamp-1">
                        Museum Timah Indonesia
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Gedung kolonial cagar budaya di Pangkalpinang, museum pertimahan satu-satunya di Asia Tenggara.
                    </p>
                    <button onclick="openPhotoModal('assets/img/museum_timah_babel.jpg', 'Museum Timah Indonesia &bull; Kota Pangkalpinang', 'Satu-satunya museum pertambangan timah di Asia Tenggara, terletak di jantung Kota Pangkalpinang.')" class="pt-1 text-[11px] font-bold text-violet-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 8. Pulau Lengkuas & Mercusuar 1882 -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/pantai_lengkuas.jpg" alt="Pulau Lengkuas" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-sailboat mr-1"></i> Kepulauan Belitung
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-teal-300 transition-colors line-clamp-1">
                        Pulau Lengkuas 1882
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Mercusuar kolonial Belanda 18 lantai yang masih kokoh aktif, dikelilingi laut toska sebening kristal.
                    </p>
                    <button onclick="openPhotoModal('assets/img/pantai_lengkuas.jpg', 'Pulau Lengkuas &bull; Kepulauan Belitung', 'Ikon bahari legendaris dengan mercusuar peninggalan Belanda tahun 1882, terumbu karang dan taman laut dangkal.')" class="pt-1 text-[11px] font-bold text-teal-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 9. Kain Tenun Cual Bangka -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/kain_cual_bangka.jpg" alt="Kain Cual Bangka" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-scroll mr-1"></i> Tekstil Tradisional
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-rose-300 transition-colors line-clamp-1">
                        Kain Tenun Cual Bangka
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Tenun sutra tradisional bermotif Bebek Berseluk dengan teknik ikat lungsi dan benang emas murni.
                    </p>
                    <button onclick="openPhotoModal('assets/img/kain_cual_bangka.jpg', 'Kain Cual Tradisional Bangka', 'Tenunan khas Bangka Belitung dengan teknik tenun ikat lungsi yang rumit dan benang emas murni.')" class="pt-1 text-[11px] font-bold text-rose-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 10. Alat Musik Tradisional Dambus -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/alat_musik_dambus.jpg" alt="Alat Musik Dambus Bangka" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-500 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-guitar mr-1"></i> Alat Musik Petik
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-purple-300 transition-colors line-clamp-1">
                        Dambus Melayu Bangka
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Instrumen petik berdawai berukir kepala kijang (rusa), melodi utama musik tradisional Melayu Bangka.
                    </p>
                    <button onclick="openPhotoModal('assets/img/alat_musik_dambus.jpg', 'Alat Musik Tradisional Dambus', 'Alat musik petik khas Melayu Bangka Belitung yang memiliki ujung ukiran kepala rusa (kijang).')" class="pt-1 text-[11px] font-bold text-purple-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 11. Busana Adat Paksian -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/budaya_tari_paksian.jpg" alt="Pakaian Adat Paksian" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-600 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-crown mr-1"></i> Busana Adat
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-amber-300 transition-colors line-clamp-1">
                        Busana Adat Paksian
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Mahkota emas kembang goyang dan baju kurung sutra, lambang keanggunan akulturasi budaya Babel.
                    </p>
                    <button onclick="openPhotoModal('assets/img/budaya_tari_paksian.jpg', 'Busana Pengantin &amp; Mahkota Paksian', 'Busana kebesaran adat perkawinan Bangka Belitung perpaduan harmonis budaya Melayu, Tionghoa, dan Arab.')" class="pt-1 text-[11px] font-bold text-amber-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

            <!-- 12. Tradisi Nganggung Sepintu Sedulang -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
                <img src="assets/img/budaya_nganggung.jpg" alt="Tradisi Nganggung" 
                    class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                <div class="relative z-10 p-4 space-y-1.5 text-white">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-600 text-white shadow-sm inline-block">
                        <i class="fa-solid fa-hand-holding-heart mr-1"></i> Kearifan Lokal
                    </span>
                    <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-rose-300 transition-colors line-clamp-1">
                        Tradisi Nganggung
                    </h3>
                    <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                        Tradisi gotong royong membawa dulang bertudung saji merah untuk dinikmati bersama di balai desa.
                    </p>
                    <button onclick="openPhotoModal('assets/img/budaya_nganggung.jpg', 'Tradisi Nganggung Sepintu Sedulang', 'Tradisi gotong royong membawa dulang makanan bertutup tudung saji merah sebagai wujud kebersamaan abadi masyarakat Babel.')" class="pt-1 text-[11px] font-bold text-rose-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 6. TOP 5 DESTINASI TERPOPULER (DENGAN AVATAR FOTO ASLI)        -->
    <!-- ============================================================== -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                    <span>5 Destinasi Wisata Paling Populer</span>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Ulasan Publik Tertinggi</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Destinasi dengan interaksi wisatawan terbanyak berdasarkan data Google Maps</p>
            </div>
            <a href="index.php?page=peta" class="text-xs font-bold text-ocean-600 hover:text-ocean-700 flex items-center gap-1.5 transition-colors">
                <span>Lihat Semua di Peta</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            <?php foreach ($topDestinasi as $index => $row): 
                $photoThumb = getDestinationPhoto($row['nama'], $row['sub_kategori']);
            ?>
            <div class="p-3.5 sm:px-6 flex items-center justify-between hover:bg-slate-50/70 transition-colors gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative">
                        <img src="<?= $photoThumb ?>" alt="<?= htmlspecialchars($row['nama']) ?>" class="w-12 h-12 rounded-2xl object-cover border border-slate-200 shadow-2xs shrink-0">
                        <span class="absolute -top-1.5 -left-1.5 w-5 h-5 rounded-full <?= $index === 0 ? 'bg-amber-500 text-white font-black' : 'bg-slate-900 text-white font-bold' ?> text-[10px] flex items-center justify-center shadow-sm">
                            <?= $index + 1 ?>
                        </span>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                            <?= htmlspecialchars($row['nama']) ?>
                        </div>
                        <div class="text-[11px] text-slate-500 flex items-center gap-2 mt-0.5">
                            <span><?= htmlspecialchars($row['kecamatan']) ?>, <?= htmlspecialchars(str_replace(['Kabupaten ', 'Kota '], '', $row['kabupaten'])) ?></span>
                            <span>&bull;</span>
                            <span class="font-semibold <?= $row['sub_kategori'] === 'Alam' ? 'text-emerald-600' : ($row['sub_kategori'] === 'Kebudayaan' ? 'text-purple-600' : 'text-amber-600') ?>">
                                <?= htmlspecialchars($row['sub_kategori']) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                    <div class="text-right">
                        <div class="flex items-center justify-end gap-1 text-xs font-bold text-amber-500">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <span><?= $row['rating'] ?: '0.0' ?></span>
                        </div>
                        <div class="text-[11px] text-slate-400">
                            <?= number_format($row['ulasan']) ?> ulasan
                        </div>
                    </div>
                    <a href="index.php?page=peta&q=<?= urlencode($row['nama']) ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 hover:bg-ocean-100 transition-colors" title="Buka di Peta">
                        <i class="fa-solid fa-location-crosshairs text-xs"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL LIGHTBOX FOTO BUDAYA & LANSKAP                           -->
    <!-- ============================================================== -->
    <div id="photoLightboxModal" class="hidden fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
        <div class="relative max-w-3xl w-full bg-slate-900 border border-slate-700 rounded-3xl overflow-hidden shadow-2xl space-y-0 text-white">
            <button onclick="closePhotoModal()" class="absolute top-4 right-4 z-20 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center text-sm transition-colors border border-white/20">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="relative w-full max-h-[55vh] bg-black flex items-center justify-center overflow-hidden">
                <img id="photoModalImg" src="" alt="Detail Foto" class="w-full h-auto max-h-[55vh] object-contain">
            </div>
            <div class="p-5 sm:p-6 space-y-2 bg-slate-900 border-t border-slate-800">
                <h3 id="photoModalTitle" class="text-lg sm:text-xl font-extrabold text-white"></h3>
                <p id="photoModalDesc" class="text-xs sm:text-sm text-slate-300 leading-relaxed"></p>
                <div class="pt-2 flex items-center justify-between text-xs text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-camera text-ocean-400"></i> Dokumentasi Budaya Kepulauan Bangka Belitung
                    </span>
                    <button onclick="closePhotoModal()" class="px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
// Chart 1: Bar Chart per Kabupaten
const kabCtx = document.getElementById('chartKabupaten').getContext('2d');
new Chart(kabCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_map(fn($r) => str_replace(['Kabupaten ', 'Kota '], '', $r['kabupaten']), $perKabupaten)) ?>,
        datasets: [
            {
                label: 'Daya Tarik Wisata',
                data: <?= json_encode(array_map(fn($r) => (int)$r['alam'] + (int)$r['buatan'] + (int)$r['budaya'], $perKabupaten)) ?>,
                backgroundColor: '#10b981',
                borderRadius: 6
            },
            {
                label: 'Hotel / Homestay',
                data: <?= json_encode(array_column($perKabupaten, 'hotel')) ?>,
                backgroundColor: '#0ea5e9',
                borderRadius: 6
            },
            {
                label: 'Restoran / Kuliner',
                data: <?= json_encode(array_column($perKabupaten, 'restoran')) ?>,
                backgroundColor: '#f97316',
                borderRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { 
                position: 'top', 
                labels: { 
                    boxWidth: 12,
                    padding: 10,
                    font: { family: 'Plus Jakarta Sans', size: window.innerWidth < 640 ? 10 : 12 } 
                } 
            }
        },
        scales: {
            x: { 
                grid: { display: false },
                ticks: {
                    font: { family: 'Plus Jakarta Sans', size: window.innerWidth < 640 ? 9 : 11 }
                }
            },
            y: { grid: { color: '#f1f5f9' }, beginAtZero: true }
        }
    }
});

// Chart 2: Doughnut Proporsi
const propCtx = document.getElementById('chartProporsi').getContext('2d');
new Chart(propCtx, {
    type: 'doughnut',
    data: {
        labels: ['Alam', 'Buatan', 'Budaya', 'Hotel', 'Restoran'],
        datasets: [{
            data: [
                <?= (int)$stats['total_alam'] ?>,
                <?= (int)$stats['total_buatan'] ?>,
                <?= (int)$stats['total_budaya'] ?>,
                <?= (int)$stats['total_hotel'] ?>,
                <?= (int)$stats['total_restoran'] ?>
            ],
            backgroundColor: ['#10b981', '#f59e0b', '#8b5cf6', '#0ea5e9', '#f97316'],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '72%'
    }
});

// Modal Lightbox Foto
function openPhotoModal(src, title, desc) {
    document.getElementById('photoModalImg').src = src;
    document.getElementById('photoModalTitle').innerHTML = title;
    document.getElementById('photoModalDesc').innerHTML = desc;
    document.getElementById('photoLightboxModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePhotoModal() {
    document.getElementById('photoLightboxModal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
