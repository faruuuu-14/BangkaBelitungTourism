<?php
require_once __DIR__ . '/../app/database.php';
$pdo = getDbConnection();

// 1. Ambil Ringkasan Metrik
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

// 2. Data per Kabupaten untuk Chart
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

// 3. Top 8 Destinasi Terpopuler (Ulasan Terbanyak)
$topDestinasi = $pdo->query("
    SELECT nama, kabupaten, kecamatan, sub_kategori, rating, ulasan
    FROM tempat_wisata
    WHERE kelompok = 'Daya Tarik'
    ORDER BY ulasan DESC
    LIMIT 8
")->fetchAll();
?>

<main class="flex-grow max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 w-full max-w-full overflow-hidden">
    
    <!-- Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-ocean-900 to-ocean-800 text-white p-5 sm:p-8 lg:p-10 mb-6 sm:mb-8 shadow-xl">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-ocean-200 border border-white/10 mb-3 sm:mb-4 backdrop-blur-sm">
                <i class="fa-solid fa-layer-group text-[10px]"></i>
                Provinsi Kepulauan Bangka Belitung
            </span>
            <h1 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight mb-2 sm:mb-3 leading-tight">
                Monitoring &amp; Analisis Spasial Pariwisata Daerah
            </h1>
            <p class="text-slate-300 text-xs sm:text-sm lg:text-base leading-relaxed">
                Platform intelijen geospasial untuk mengevaluasi keseimbangan potensi daya tarik wisata dengan ketersediaan amenitas pendukung (hotel dan restoran).
            </p>
            <div class="mt-4 sm:mt-6 grid grid-cols-2 sm:flex sm:flex-wrap gap-2 sm:gap-2.5">
                <a href="index.php?page=peta" class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white font-bold text-xs shadow-md transition-all flex items-center justify-center sm:justify-start gap-1.5">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Eksplorasi WebGIS</span>
                </a>
                <a href="index.php?page=analisis" class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all flex items-center justify-center sm:justify-start gap-1.5">
                    <i class="fa-solid fa-award text-amber-400"></i>
                    <span>Indeks IPKN &amp; Klassen</span>
                </a>
                <button onclick="openGuideModal('dashboard')" class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center sm:justify-start gap-1.5">
                    <i class="fa-solid fa-book-bookmark text-xs"></i>
                    <span>Panduan &amp; Metode</span>
                </button>
                <a href="api/export.php?format=geojson" target="_blank" class="px-3 py-2 sm:py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-medium text-xs border border-white/10 transition-all flex items-center justify-center sm:justify-start gap-1.5">
                    <i class="fa-solid fa-cloud-arrow-down"></i>
                    <span>Satu Data (GeoJSON)</span>
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-ocean-500/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- Ringkasan Kartu Metrik (Responsive: 2 kolom di HP, 3 di tablet, 5 di laptop) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-4 mb-6 sm:mb-8 w-full max-w-full min-w-0">
        
        <!-- Total Objek -->
        <div class="bg-white p-3 sm:p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow min-w-0 overflow-hidden">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
                <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Total Objek</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 text-xs sm:text-sm flex-shrink-0">
                    <i class="fa-solid fa-map-pin"></i>
                </div>
            </div>
            <div class="text-lg xs:text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-none truncate"><?= number_format($stats['total_semua']) ?></div>
            <div class="text-[10px] sm:text-xs text-slate-500 mt-1 truncate">Data Resmi Terkurasi</div>
        </div>

        <!-- Wisata Alam -->
        <div class="bg-white p-3 sm:p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow min-w-0 overflow-hidden">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
                <span class="text-[10px] sm:text-xs font-bold text-emerald-600 uppercase tracking-wider">Daya Tarik Alam</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-xs sm:text-sm flex-shrink-0">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
            </div>
            <div class="text-lg xs:text-xl sm:text-2xl lg:text-3xl font-extrabold text-emerald-700 leading-none truncate"><?= number_format($stats['total_alam']) ?></div>
            <div class="text-[10px] sm:text-xs text-slate-500 mt-1 truncate">Pantai, Pulau, Bukit</div>
        </div>

        <!-- Wisata Buatan & Budaya -->
        <div class="bg-white p-3 sm:p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow min-w-0 overflow-hidden">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
                <span class="text-[10px] sm:text-xs font-bold text-amber-600 uppercase tracking-wider">Buatan &amp; Budaya</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-xs sm:text-sm flex-shrink-0">
                    <i class="fa-solid fa-monument"></i>
                </div>
            </div>
            <div class="text-lg xs:text-xl sm:text-2xl lg:text-3xl font-extrabold text-amber-700 leading-none truncate"><?= number_format($stats['total_buatan'] + $stats['total_budaya']) ?></div>
            <div class="text-[10px] sm:text-xs text-slate-500 mt-1 truncate"><?= $stats['total_buatan'] ?> Buatan, <?= $stats['total_budaya'] ?> Budaya</div>
        </div>

        <!-- Hotel / Homestay -->
        <div class="bg-white p-3 sm:p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow min-w-0 overflow-hidden">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
                <span class="text-[10px] sm:text-xs font-bold text-ocean-600 uppercase tracking-wider">Akomodasi</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-ocean-50 flex items-center justify-center text-ocean-600 text-xs sm:text-sm flex-shrink-0">
                    <i class="fa-solid fa-hotel"></i>
                </div>
            </div>
            <div class="text-lg xs:text-xl sm:text-2xl lg:text-3xl font-extrabold text-ocean-700 leading-none truncate"><?= number_format($stats['total_hotel']) ?></div>
            <div class="text-[10px] sm:text-xs text-slate-500 mt-1 truncate">Hotel &amp; Homestay</div>
        </div>

        <!-- Restoran & Kuliner -->
        <div class="col-span-2 sm:col-span-2 lg:col-span-1 bg-white p-3 sm:p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow min-w-0 overflow-hidden">
            <div class="flex items-center justify-between mb-2 sm:mb-3">
                <span class="text-[10px] sm:text-xs font-bold text-coral-600 uppercase tracking-wider">Restoran &amp; Cafe</span>
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl bg-coral-50 flex items-center justify-center text-coral-600 text-xs sm:text-sm flex-shrink-0">
                    <i class="fa-solid fa-utensils"></i>
                </div>
            </div>
            <div class="text-lg xs:text-xl sm:text-2xl lg:text-3xl font-extrabold text-coral-700 leading-none truncate"><?= number_format($stats['total_restoran']) ?></div>
            <div class="text-[10px] sm:text-xs text-slate-500 mt-1 truncate">Amenitas Kuliner</div>
        </div>

    </div>

    <!-- Grid Chart Analisis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-6 sm:mb-8 w-full max-w-full min-w-0">
        
        <!-- Bar Chart: Sebaran per Kabupaten -->
        <div class="lg:col-span-2 bg-white p-4 sm:p-6 rounded-3xl border border-slate-200 shadow-sm w-full max-w-full min-w-0 overflow-hidden">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Sebaran Fasilitas per Kabupaten/Kota</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Perbandingan Daya Tarik Wisata vs Amenitas (Hotel & Resto)</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 rounded-lg text-slate-600">7 Daerah</span>
            </div>
            <div class="relative w-full max-w-full min-w-0 h-60 sm:h-72 overflow-hidden">
                <canvas id="chartKabupaten" class="w-full"></canvas>
            </div>
        </div>

        <!-- Doughnut Chart: Komposisi 3A -->
        <div class="bg-white p-4 sm:p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between w-full max-w-full min-w-0 overflow-hidden">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900 mb-0.5">Proporsi Kategori</h3>
                <p class="text-[11px] sm:text-xs text-slate-500 mb-3">Komposisi Sektor Pariwisata Bangka Belitung</p>
                <div class="relative w-full max-w-full min-w-0 h-52 sm:h-56 overflow-hidden flex items-center justify-center">
                    <canvas id="chartProporsi" class="max-w-full max-h-full"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-[11px] sm:text-xs text-slate-600 mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-100">
                <div class="flex items-center gap-2 truncate">
                    <span class="w-3 h-3 rounded-md bg-emerald-500 flex-shrink-0"></span>
                    <span class="truncate">Alam (<?= round(($stats['total_alam']/$stats['total_semua'])*100) ?>%)</span>
                </div>
                <div class="flex items-center gap-2 truncate">
                    <span class="w-3 h-3 rounded-md bg-amber-500 flex-shrink-0"></span>
                    <span class="truncate">Buatan (<?= round(($stats['total_buatan']/$stats['total_semua'])*100) ?>%)</span>
                </div>
                <div class="flex items-center gap-2 truncate">
                    <span class="w-3 h-3 rounded-md bg-ocean-500 flex-shrink-0"></span>
                    <span class="truncate">Hotel (<?= round(($stats['total_hotel']/$stats['total_semua'])*100) ?>%)</span>
                </div>
                <div class="flex items-center gap-2 truncate">
                    <span class="w-3 h-3 rounded-md bg-coral-500 flex-shrink-0"></span>
                    <span class="truncate">Resto (<?= round(($stats['total_restoran']/$stats['total_semua'])*100) ?>%)</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Tabel Destinasi Terpopuler -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm w-full max-w-full min-w-0 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-900">Destinasi Wisata Paling Banyak Diulas</h3>
                <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Berdasarkan data aktivitas dan ulasan publik di Google Maps</p>
            </div>
            <a href="index.php?page=peta" class="text-xs font-semibold text-ocean-600 hover:text-ocean-700 flex items-center gap-1.5 flex-shrink-0">
                <span>Lihat di Peta</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="w-full max-w-full min-w-0 overflow-x-auto no-scrollbar touch-scroll">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/75 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-3 sm:px-6 py-2.5 sm:py-3.5">Nama Destinasi</th>
                        <th class="px-2 sm:px-6 py-2.5 sm:py-3.5">Kategori</th>
                        <th class="hidden sm:table-cell px-6 py-3.5">Wilayah</th>
                        <th class="px-2 sm:px-6 py-2.5 sm:py-3.5 text-center">Rating</th>
                        <th class="px-3 sm:px-6 py-2.5 sm:py-3.5 text-right">Ulasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($topDestinasi as $row): ?>
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-3 sm:px-6 py-3 sm:py-4 font-semibold text-slate-900">
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs flex-shrink-0">
                                    <i class="fa-solid <?= $row['sub_kategori'] == 'Alam' ? 'fa-water' : 'fa-camera' ?>"></i>
                                </div>
                                <div class="min-w-0 max-w-[130px] xs:max-w-[170px] sm:max-w-none">
                                    <div class="truncate text-xs sm:text-sm font-bold text-slate-900"><?= htmlspecialchars($row['nama']) ?></div>
                                    <div class="text-[10px] text-slate-400 sm:hidden truncate"><?= htmlspecialchars($row['kecamatan']) ?>, <?= htmlspecialchars(str_replace(['Kabupaten ', 'Kota '], '', $row['kabupaten'])) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold <?= $row['sub_kategori'] == 'Alam' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?>">
                                <?= htmlspecialchars($row['sub_kategori']) ?>
                            </span>
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4 text-xs whitespace-nowrap">
                            <div class="font-medium text-slate-800"><?= htmlspecialchars($row['kecamatan']) ?></div>
                            <div class="text-slate-400"><?= htmlspecialchars($row['kabupaten']) ?></div>
                        </td>
                        <td class="px-2 sm:px-6 py-3 sm:py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 font-bold text-amber-500 text-xs sm:text-sm">
                                <i class="fa-solid fa-star text-[10px] sm:text-xs"></i>
                                <span><?= $row['rating'] ?: '0.0' ?></span>
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-3 sm:py-4 text-right font-bold text-slate-800 text-xs sm:text-sm whitespace-nowrap">
                            <?= number_format($row['ulasan']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
                label: 'Wisata Alam',
                data: <?= json_encode(array_column($perKabupaten, 'alam')) ?>,
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
                    padding: 8,
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
        cutout: '70%'
    }
});
</script>
