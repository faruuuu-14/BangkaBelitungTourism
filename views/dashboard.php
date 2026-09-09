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
?>

<main class="flex-grow max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 w-full max-w-full overflow-hidden space-y-6 sm:space-y-8">
    
    <!-- ============================================================== -->
    <!-- 1. HERO BANNER: SELAMAT DATANG & IDENTITAS SISTEM              -->
    <!-- ============================================================== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-ocean-950 to-slate-900 text-white p-5 sm:p-8 lg:p-10 shadow-xl border border-white/10">
        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-ocean-300 border border-white/10 mb-3 sm:mb-4 backdrop-blur-sm">
                <i class="fa-solid fa-compass text-ocean-400"></i>
                <span>Sistem Informasi Geospasial &amp; Intelijen Pariwisata Babel</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight mb-2 sm:mb-3 leading-tight text-white">
                Dashboard Pariwisata Kepulauan Bangka Belitung
            </h1>
            <p class="text-slate-300 text-xs sm:text-sm lg:text-base leading-relaxed">
                Platform satu pintu untuk memantau sebaran 875 objek pariwisata, menganalisis keseimbangan daya tarik dengan hotel/restoran, dan meranking destinasi unggulan berbasis standar ilmiah Kemenparekraf &amp; BPS RI.
            </p>

            <!-- Aksi Cepat Menuju 3 Fitur Utama -->
            <div class="mt-5 sm:mt-7 flex flex-wrap gap-2 sm:gap-3">
                <a href="index.php?page=peta" class="px-4 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white font-bold text-xs shadow-lg shadow-ocean-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Eksplorasi Peta GIS</span>
                </a>
                <a href="index.php?page=analisis" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-amber-400"></i>
                    <span>Analisis Beban &amp; Gap</span>
                </a>
                <a href="index.php?page=rekomendasi" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-ranking-star text-emerald-400"></i>
                    <span>SPK Rekomendasi TOPSIS</span>
                </a>
                <button onclick="openGuideModal('dashboard')" class="px-3.5 py-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-semibold text-xs border border-white/10 transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-question text-xs"></i>
                    <span>Panduan Ringkas</span>
                </button>
            </div>
        </div>
        <div class="absolute -right-12 -bottom-12 w-80 h-80 bg-ocean-500/20 rounded-full blur-3xl pointer-events-none"></div>
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
    <!-- 5. TOP 5 DESTINASI TERPOPULER                                   -->
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
            <?php foreach ($topDestinasi as $index => $row): ?>
            <div class="p-4 sm:px-6 flex items-center justify-between hover:bg-slate-50/70 transition-colors gap-3">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-8 h-8 rounded-xl <?= $index === 0 ? 'bg-amber-100 text-amber-700 border border-amber-300' : 'bg-slate-100 text-slate-600' ?> flex items-center justify-center font-extrabold text-xs shrink-0">
                        #<?= $index + 1 ?>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                            <?= htmlspecialchars($row['nama']) ?>
                        </div>
                        <div class="text-[11px] text-slate-500 flex items-center gap-2 mt-0.5">
                            <span><?= htmlspecialchars($row['kecamatan']) ?>, <?= htmlspecialchars(str_replace(['Kabupaten ', 'Kota '], '', $row['kabupaten'])) ?></span>
                            <span>&bull;</span>
                            <span class="font-medium <?= $row['sub_kategori'] === 'Alam' ? 'text-emerald-600' : 'text-amber-600' ?>">
                                <?= htmlspecialchars($row['sub_kategori']) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-right">
                        <div class="flex items-center justify-end gap-1 text-xs font-bold text-amber-500">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <span><?= $row['rating'] ?: '0.0' ?></span>
                        </div>
                        <div class="text-[11px] text-slate-400">
                            <?= number_format($row['ulasan']) ?> ulasan
                        </div>
                    </div>
                    <a href="index.php?page=peta&q=<?= urlencode($row['nama']) ?>" class="hidden sm:inline-flex items-center justify-center w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 hover:bg-ocean-100 transition-colors" title="Buka di Peta">
                        <i class="fa-solid fa-location-crosshairs text-xs"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
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
</script>
