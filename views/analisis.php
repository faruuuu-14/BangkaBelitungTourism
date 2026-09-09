<?php
require_once __DIR__ . '/../app/statistics.php';

// 1. Data Pertumbuhan YoY BPS
$yoyData = getBpsYoYGrowth();
$prov = $yoyData['provinsi'];
$daerahYoY = $yoyData['daerah'];

// 2. Data Tren Bulanan untuk Line Chart
$monthlyTrends = getBpsMonthlyTrends();

// 3. Data Korelasi Makro & Mikro
$macroCorr = getMacroCorrelation();
$corr = getCorrelationMatrix();

// 4. Data Gap Amenitas & Keseimbangan Triangulasi per Kecamatan
$gapData = getGapAnalysis();
$kritisCount = count(array_filter($gapData, fn($i) => str_contains($i['status'], 'Defisit')));
$waspadaCount = count(array_filter($gapData, fn($i) => str_contains($i['status'], 'Potensi') || str_contains($i['status'], 'Longgar') || str_contains($i['status'], 'Perlu')));
$idealCount = count(array_filter($gapData, fn($i) => str_contains($i['status'], 'Seimbang')));

// 5. Data IPKN-Daerah (BTDI Score) & Tipologi Klassen
$btdiScores = getBtdiDistrictScores();
$klassenData = getKlassenTourismTypology();
$policyBrief = getExecutivePolicyBriefData();

// 6. Data Pola Spasial (NNA & Moran's I)
$nnaData = getNearestNeighborAnalysis();
$moranData = getGlobalMoransI();

// 7. Data Time Series Forecasting BPS
$forecastData = getTourismForecasting();

// 8. Data Tourism Carrying Capacity (TCC)
$tccData = getTourismCarryingCapacity();

function getCorrelationBadge($r) {
    if ($r >= 0.7) return ['Korelasi Sangat Kuat', 'bg-emerald-50 text-emerald-700 border-emerald-200'];
    if ($r >= 0.4) return ['Korelasi Sedang', 'bg-ocean-50 text-ocean-700 border-ocean-200'];
    if ($r >= 0.2) return ['Korelasi Positif Lemah', 'bg-amber-50 text-amber-700 border-amber-200'];
    if ($r > -0.2) return ['Tidak Berkorelasi Linear', 'bg-slate-100 text-slate-700 border-slate-200'];
    return ['Korelasi Negatif', 'bg-rose-50 text-rose-700 border-rose-200'];
}
?>

<main class="flex-grow w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 min-w-0 overflow-hidden">

    <!-- Header Section -->
    <div class="mb-6 sm:mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-ocean-50 text-ocean-700 border border-ocean-200/80 mb-2">
                <i class="fa-solid fa-landmark-flag text-[11px]"></i>
                <span>Standar Analisis Kemenparekraf, Bappenas &amp; BPS RI</span>
            </div>
            <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Pusat Intelijen &amp; Analisis Statistik Pariwisata
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1 max-w-3xl leading-relaxed">
                Integrasi indikator pertumbuhan Year-over-Year (YoY), matriks korelasi Pearson, evaluasi gap amenitas, indeks IPKN-Daerah (BTDI), dan tipologi Klassen pariwisata.
            </p>
        </div>

        <!-- Tombol Aksi Eksekutif -->
        <div class="flex items-center gap-2 self-stretch sm:self-auto flex-wrap sm:flex-nowrap w-full sm:w-auto">
            <button onclick="openGuideModal('analisis')" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md shadow-slate-900/20 transition-all whitespace-nowrap">
                <i class="fa-solid fa-circle-question text-ocean-400 text-xs"></i>
                <span>Panduan &amp; Rumus</span>
            </button>
            <button onclick="openPolicyBriefModal()" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-gradient-to-r from-ocean-600 to-emerald-600 hover:from-ocean-700 hover:to-emerald-700 text-white text-xs font-bold shadow-md shadow-ocean-500/20 transition-all whitespace-nowrap">
                <i class="fa-solid fa-file-shield text-xs"></i>
                <span>Policy Brief</span>
            </button>
            <button onclick="window.print()" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 sm:py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-sm transition-colors whitespace-nowrap" title="Cetak Ringkasan Eksekutif Halaman Ini">
                <i class="fa-solid fa-print text-xs text-ocean-600"></i>
                <span class="text-xs font-bold">Cetak</span>
            </button>
        </div>
    </div>

    
    <!-- ============================================================== -->
    <!-- RINGKASAN EKSEKUTIF UTAMA (1 MENIT UNTUK PENGAMBIL KEBIJAKAN)   -->
    <!-- ============================================================== -->
    <div class="bg-gradient-to-br from-slate-950 via-ocean-950 to-slate-900 rounded-3xl p-5 sm:p-7 text-white shadow-xl border border-white/10 mb-6 sm:mb-8 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-white/10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-ocean-500/30 text-ocean-300 border border-ocean-400/30 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-white">Ringkasan Eksekutif: Kondisi Pariwisata Babel Terkini</h2>
                    <p class="text-xs text-slate-400">Rangkuman 4 temuan paling strategis dari 8 modul analisis data resmi BPS &amp; Kemenparekraf</p>
                </div>
            </div>
            <span class="text-[10px] font-bold px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 self-start sm:self-auto">
                Status: Positif Bertumbuh
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <!-- 1. Pertumbuhan Wisatawan -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-ocean-300 block">1. Laju Kunjungan (BPS)</span>
                <div class="text-xl sm:text-2xl font-black text-emerald-400 flex items-baseline gap-1.5">
                    <span>+<?= $prov['growth'] ?>%</span>
                    <span class="text-xs font-normal text-slate-300">YoY 2026</span>
                </div>
                <p class="text-slate-400 text-[11px] leading-relaxed">
                    Total <?= number_format($prov['total_2026'] ?? 0) ?> wisatawan nusantara telah berkunjung ke Babel pada triwulan awal 2026.
                </p>
            </div>

            <!-- 2. Keseimbangan Amenitas -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300 block">2. Beban &amp; Peluang Hotel</span>
                <div class="text-xl sm:text-2xl font-black text-amber-400">
                    <?= $kritisCount ?> <span class="text-xs font-normal text-slate-300">Kec. Defisit Akut</span>
                </div>
                <p class="text-slate-400 text-[11px] leading-relaxed">
                    <?= $idealCount ?> kecamatan seimbang, sementara <?= $kritisCount ?> kecamatan membutuhkan penambahan hotel &amp; resto pendukung.
                </p>
            </div>

            <!-- 3. Pola Spasial -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-300 block">3. Pola Sebaran Objek</span>
                <div class="text-xl sm:text-2xl font-black text-purple-300">
                    Mengelompok <span class="text-xs font-normal text-slate-300">(Clustered)</span>
                </div>
                <p class="text-slate-400 text-[11px] leading-relaxed">
                    Pola NNA membuktikan objek wisata terkonsentrasi di kawasan pesisir pantai &amp; dekat simpul transportasi bandara/pelabuhan.
                </p>
            </div>

            <!-- 4. Kesiapan Wilayah -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5 space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-sky-300 block">4. Lokomotif Daerah</span>
                <div class="text-base sm:text-lg font-black text-sky-300 truncate">
                    Pangkalpinang &amp; Belitung
                </div>
                <p class="text-slate-400 text-[11px] leading-relaxed">
                    Memimpin indeks kesiapan pariwisata (IPKN) &amp; menempati Kuadran I (Daerah Maju &amp; Cepat Tumbuh) dalam tipologi Klassen.
                </p>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- SPOTLIGHT VISUAL: 4 PILAR IDENTITAS PARIWISATA BANGKA BELITUNG  -->
    <!-- ============================================================== -->
    <div class="mb-6 sm:mb-8 grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="relative h-28 sm:h-36 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 group">
            <img src="assets/img/pantai_lengkuas.jpg" alt="Pulau Lengkuas" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
            <div class="absolute bottom-2.5 left-2.5 right-2.5">
                <span class="text-[9px] font-bold text-amber-300 uppercase tracking-wider block">Bahari &amp; Geopark</span>
                <span class="text-xs font-bold text-white block truncate">Pulau Lengkuas 1882</span>
            </div>
        </div>

        <div class="relative h-28 sm:h-36 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 group">
            <img src="assets/img/kain_cual_bangka.jpg" alt="Kain Cual Bangka" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
            <div class="absolute bottom-2.5 left-2.5 right-2.5">
                <span class="text-[9px] font-bold text-purple-300 uppercase tracking-wider block">Warisan Kriya</span>
                <span class="text-xs font-bold text-white block truncate">Tenun Cual Sutra Emas</span>
            </div>
        </div>

        <div class="relative h-28 sm:h-36 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 group">
            <img src="assets/img/laskar_pelangi_gantong.jpg" alt="SD Laskar Pelangi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
            <div class="absolute bottom-2.5 left-2.5 right-2.5">
                <span class="text-[9px] font-bold text-sky-300 uppercase tracking-wider block">Wisata Sastra</span>
                <span class="text-xs font-bold text-white block truncate">Laskar Pelangi Gantong</span>
            </div>
        </div>

        <div class="relative h-28 sm:h-36 rounded-2xl overflow-hidden shadow-xs border border-slate-200/80 group">
            <img src="assets/img/museum_timah_babel.jpg" alt="Museum Timah Babel" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
            <div class="absolute bottom-2.5 left-2.5 right-2.5">
                <span class="text-[9px] font-bold text-emerald-300 uppercase tracking-wider block">Sejarah &amp; Edukasi</span>
                <span class="text-xs font-bold text-white block truncate">Museum Timah Pangkalpinang</span>
            </div>
        </div>
    </div>

    <!-- Tab Navigasi Analisis (8 Pilar Standar Kemenpar & Akademik) -->
    <div class="w-full max-w-full min-w-0 border-b border-slate-200 mb-6 sm:mb-8 pb-1">
        <div class="w-full max-w-full min-w-0 overflow-x-auto no-scrollbar touch-scroll py-1">
            <div class="flex items-center gap-1.5 sm:gap-2 flex-nowrap w-max px-0.5">
                <button onclick="switchTab('tab-yoy')" id="btn-tab-yoy" 
                    class="tab-btn px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-ocean-600 text-white shadow-sm whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                    <span>1. Pertumbuhan YoY &amp; BPS</span>
                </button>
                <button onclick="switchTab('tab-korelasi')" id="btn-tab-korelasi" 
                    class="tab-btn px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-square-root-variable"></i>
                    <span>2. Korelasi Pearson</span>
                </button>
                <button onclick="switchTab('tab-gap')" id="btn-tab-gap" 
                    class="tab-btn px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-scale-unbalanced"></i>
                    <span>3. Gap Amenitas &amp; Simulasi</span>
                </button>
                <button onclick="switchTab('tab-ipkn')" id="btn-tab-ipkn" 
                    class="tab-btn px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-award text-amber-500"></i>
                    <span>4. Indeks IPKN-Daerah</span>
                </button>
                <button onclick="switchTab('tab-klassen')" id="btn-tab-klassen" 
                    class="tab-btn px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-table-cells-large text-emerald-500"></i>
                    <span>5. Tipologi Klassen</span>
                </button>
                <button onclick="switchTab('tab-nna')" id="btn-tab-nna" 
                    class="tab-btn px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-circle-nodes text-purple-500"></i>
                    <span>6. Pola Spasial (NNA &amp; Moran)</span>
                </button>
                <button onclick="switchTab('tab-forecast')" id="btn-tab-forecast" 
                    class="tab-btn px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-chart-line text-blue-500"></i>
                    <span>7. Prediksi Tren BPS</span>
                </button>
                <button onclick="switchTab('tab-tcc')" id="btn-tab-tcc" 
                    class="tab-btn px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 whitespace-nowrap flex-shrink-0">
                    <i class="fa-solid fa-leaf text-emerald-500"></i>
                    <span>8. Daya Dukung Lingkungan (TCC)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- TAB 1: PERTUMBUHAN YoY & TREN TIME SERIES BPS                 -->
    <!-- ============================================================== -->
    <div id="tab-yoy" class="tab-content space-y-8">
        <!-- CONTEXT BOX: FUNGSI TAB 1 -->
        <div class="bg-gradient-to-r from-ocean-50 via-sky-50 to-emerald-50 border border-ocean-200/80 rounded-2xl p-4 flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-xl bg-ocean-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                <i class="fa-solid fa-lightbulb text-xs"></i>
            </div>
            <div class="space-y-1 text-xs">
                <h3 class="font-bold text-slate-900">💡 Apa Fungsi Analisis Ini &amp; Bagaimana Cara Membacanya?</h3>
                <p class="text-slate-600 leading-relaxed text-[11px]">
                    <strong>Tujuan:</strong> Memantau pergerakan wisatawan nusantara dari tahun ke tahun berdasarkan data resmi Badan Pusat Statistik (BPS) RI.<br>
                    <strong>Cara Baca:</strong> Persentase hijau positif menandakan kunjungan meningkat dibanding periode yang sama tahun lalu. Angka ini menjadi dasar bagi pemerintah daerah dalam menetapkan target pendapatan dan retribusi wisata.
                </p>
            </div>
        </div>
        
        <!-- Kartu Metrik YoY Provinsi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Pertumbuhan YoY Babel -->
            <div class="bg-gradient-to-br from-ocean-800 to-slate-900 text-white p-6 rounded-3xl shadow-md flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-ocean-300 uppercase tracking-wider block mb-1">Pertumbuhan YoY Provinsi</span>
                    <div class="text-3xl sm:text-4xl font-black text-emerald-400 mt-2 flex items-baseline gap-2">
                        <span>+<?= $prov['growth'] ?>%</span>
                        <i class="fa-solid fa-arrow-up-right-dots text-lg"></i>
                    </div>
                </div>
                <div class="text-xs text-slate-300 mt-4 pt-3 border-t border-white/10">
                    Jan-Apr 2026 vs Jan-Apr 2025
                </div>
            </div>

            <!-- Total Kunjungan 2026 (Jan-Apr) -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Kunjungan Wisnus (2026)</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
                        <?= number_format($prov['total_2026']) ?>
                    </div>
                </div>
                <div class="text-xs text-emerald-600 font-semibold mt-4 pt-3 border-t border-slate-100 flex items-center gap-1">
                    <i class="fa-solid fa-circle-plus text-[10px]"></i>
                    <span>+<?= number_format($prov['selisih']) ?> wisatawan baru</span>
                </div>
            </div>

            <!-- Daerah Pertumbuhan Tertinggi -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Pertumbuhan Tertinggi</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
                        Belitung Timur
                    </div>
                </div>
                <div class="text-xs text-emerald-600 font-semibold mt-4 pt-3 border-t border-slate-100">
                    Laju YoY: <strong>+16.78%</strong> (Performa Terbaik)
                </div>
            </div>

            <!-- Destinasi Terpadat -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Volume Kunjungan Terbesar</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">
                        Pangkal Pinang
                    </div>
                </div>
                <div class="text-xs text-slate-500 font-medium mt-4 pt-3 border-t border-slate-100">
                    426.428 kunjungan (Jan-Apr 2026)
                </div>
            </div>

        </div>

        <!-- Grafik Time Series Tren Bulanan BPS -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Grafik Fluktuasi Kunjungan Wisatawan Nusantara (BPS)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbandingan Volume Bulanan Tahun 2025 vs 2026 Se-Provinsi</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                        <span class="text-slate-600">Tahun 2025</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-ocean-600"></span>
                        <span class="text-slate-900 font-bold">Tahun 2026 (Aktif)</span>
                    </div>
                </div>
            </div>
            <div class="h-80">
                <canvas id="chartBpsMonthly"></canvas>
            </div>
        </div>

        <!-- Tabel Performa YoY per Kabupaten/Kota -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Tabel Kinerja Pertumbuhan Wisatawan Nusantara (YoY)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Komparasi periode berjalan Jan-Apr 2025 terhadap Jan-Apr 2026</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">Sumber: BPS Babel</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Kabupaten / Kota</th>
                            <th class="px-6 py-4 text-right">Kunjungan (2025)</th>
                            <th class="px-6 py-4 text-right">Kunjungan (2026)</th>
                            <th class="px-6 py-4 text-right">Selisih (+/-)</th>
                            <th class="px-6 py-4 text-center">Laju YoY (%)</th>
                            <th class="px-6 py-4 text-center">Status Pertumbuhan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <?php foreach ($daerahYoY as $d): ?>
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full <?= $d['yoy_growth'] > 0 ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
                                <span><?= htmlspecialchars($d['kabupaten']) ?></span>
                            </td>
                            <td class="px-6 py-4 text-right"><?= number_format($d['kunjungan_2025']) ?></td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900"><?= number_format($d['kunjungan_2026']) ?></td>
                            <td class="px-6 py-4 text-right text-xs <?= $d['selisih'] >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                                <?= $d['selisih'] >= 0 ? '+' : '' ?><?= number_format($d['selisih']) ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-extrabold <?= $d['yoy_growth'] >= 10 ? 'text-emerald-600' : ($d['yoy_growth'] > 0 ? 'text-ocean-600' : 'text-rose-600') ?>">
                                    <?= $d['yoy_growth'] >= 0 ? '+' : '' ?><?= $d['yoy_growth'] ?>%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold <?= $d['yoy_growth'] >= 10 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($d['yoy_growth'] > 0 ? 'bg-ocean-50 text-ocean-700 border border-ocean-200' : 'bg-rose-50 text-rose-700 border border-rose-200') ?>">
                                    <?= $d['trend_label'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- TAB 2: KORELASI PEARSON (MAKRO BPS & MIKRO SPASIAL)           -->
    <!-- ============================================================== -->
    <div id="tab-korelasi" class="tab-content hidden space-y-8">
        
        <!-- Korelasi Makro: Fasilitas vs Kunjungan BPS -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="border-b border-slate-100 pb-4 mb-6">
                <span class="text-xs font-bold text-ocean-600 uppercase tracking-wider block mb-1">Validasi Silang (Cross-Validation)</span>
                <h3 class="text-lg font-bold text-slate-900">Korelasi Makro: Sebaran Fasilitas vs Realisasi Kunjungan Wisatawan BPS</h3>
                <p class="text-xs text-slate-500 mt-0.5">Membuktikan secara kuantitatif apakah fasilitas pariwisata berbanding lurus dengan angka kedatangan wisatawan nusantara</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Daya Tarik Alam vs Kunjungan -->
                <?php [$mLabel1, $mBadge1] = getCorrelationBadge($macroCorr['alam_vs_kunjungan']); ?>
                <div class="p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200/60">
                    <div class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2">Wisata Alam vs Kunjungan BPS</div>
                    <div class="text-3xl font-black text-emerald-700 mb-1"><?= $macroCorr['alam_vs_kunjungan'] ?></div>
                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold border <?= $mBadge1 ?> mb-2"><?= $mLabel1 ?></span>
                    <p class="text-xs text-slate-600">Menilai seberapa besar potensi objek alam mendorong kedatangan wisatawan di setiap kabupaten.</p>
                </div>

                <!-- Hotel vs Kunjungan -->
                <?php [$mLabel2, $mBadge2] = getCorrelationBadge($macroCorr['hotel_vs_kunjungan']); ?>
                <div class="p-5 rounded-2xl bg-ocean-50/50 border border-ocean-200/60">
                    <div class="text-xs font-bold text-ocean-800 uppercase tracking-wider mb-2">Hotel vs Kunjungan BPS</div>
                    <div class="text-3xl font-black text-ocean-700 mb-1"><?= $macroCorr['hotel_vs_kunjungan'] ?></div>
                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold border <?= $mBadge2 ?> mb-2"><?= $mLabel2 ?></span>
                    <p class="text-xs text-slate-600">Mengukur ketersediaan akomodasi hotel terhadap kemampuan menyerap volume wisatawan daerah.</p>
                </div>

                <!-- Restoran vs Kunjungan -->
                <?php [$mLabel3, $mBadge3] = getCorrelationBadge($macroCorr['resto_vs_kunjungan']); ?>
                <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200/60">
                    <div class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">Restoran vs Kunjungan BPS</div>
                    <div class="text-3xl font-black text-amber-700 mb-1"><?= $macroCorr['resto_vs_kunjungan'] ?></div>
                    <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold border <?= $mBadge3 ?> mb-2"><?= $mLabel3 ?></span>
                    <p class="text-xs text-slate-600">Hubungan ketersediaan fasilitas kuliner terhadap mobilisasi wisatawan di wilayah terkait.</p>
                </div>

            </div>
        </div>

        <!-- Korelasi Mikro Spasial Antarkecamatan -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="border-b border-slate-100 pb-4 mb-6">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Analisis Geospasial Mikro</span>
                <h3 class="text-lg font-bold text-slate-900">Korelasi Antarfaktor (Konsep 3A) di Tingkat Kecamatan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Dihitung dari titik sebaran per kecamatan se-Provinsi Kepulauan Bangka Belitung</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="text-xs font-bold text-slate-400 uppercase mb-2">Alam vs Hotel</div>
                    <div class="text-3xl font-black text-slate-900 mb-1"><?= $corr['alam_vs_hotel'] ?></div>
                    <p class="text-xs text-slate-600 mt-2">Korelasi lemah menunjukkan banyak destinasi alam di pelosok yang belum memiliki penginapan.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="text-xs font-bold text-slate-400 uppercase mb-2">Alam vs Restoran</div>
                    <div class="text-3xl font-black text-slate-900 mb-1"><?= $corr['alam_vs_restoran'] ?></div>
                    <p class="text-xs text-slate-600 mt-2">Restoran kuliner khas lebih adaptif hadir di dekat kawasan pantai dan wisata alam.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="text-xs font-bold text-slate-400 uppercase mb-2">Buatan vs Hotel</div>
                    <div class="text-3xl font-black text-slate-900 mb-1"><?= $corr['buatan_vs_hotel'] ?></div>
                    <p class="text-xs text-slate-600 mt-2">Wisata buatan (taman/waterpark) dan hotel tumbuh bersamaan di pusat perkotaan.</p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    <div class="text-xs font-bold text-slate-400 uppercase mb-2">Hotel vs Restoran</div>
                    <div class="text-3xl font-black text-slate-900 mb-1"><?= $corr['hotel_vs_restoran'] ?></div>
                    <p class="text-xs text-slate-600 mt-2">Sinergi amenitas akomodasi dan kuliner membentuk klaster ekonomi pariwisata daerah.</p>
                </div>

            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- TAB 3: ANALISIS KESENJANGAN (AMENITY GAP) PER KECAMATAN        -->
    <!-- ============================================================== -->
    <div id="tab-gap" class="tab-content hidden space-y-8">
        
        <!-- ============================================================== -->
        <!-- SIMULATOR KEBIJAKAN INTERAKTIF (WHAT-IF POLICY SIMULATOR)      -->
        <!-- ============================================================== -->
        <div class="bg-gradient-to-br from-slate-900 via-ocean-950 to-slate-900 text-white rounded-3xl p-4 sm:p-6 lg:p-8 shadow-xl border border-ocean-800/40 w-full max-w-full min-w-0 overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-white/10 mb-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-ocean-500/20 text-ocean-300 border border-ocean-400/30 mb-2">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Decision Support System (DSS) Simulator &bull; Supply-Demand Equilibrium</span>
                    </div>
                    <h3 class="text-xl font-black text-white">Simulator Kebijakan: "What-If Scenario"</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Uji dampak penambahan akomodasi &amp; kuliner terhadap daya tampung pasar riil dan kelayakan investasi anti-rugi pengelola secara real-time</p>
                </div>
                <div class="text-left md:text-right">
                    <span class="text-xs text-slate-400 block">Metode Evaluasi</span>
                    <span class="text-base font-black text-emerald-400">Triangulasi Pasar (BPS)</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Kontrol Slider -->
                <div class="lg:col-span-6 space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            1. Pilih Kecamatan Target Intervensi:
                        </label>
                        <select id="simKecamatan" class="w-full px-4 py-3 rounded-2xl bg-white/10 border border-white/20 text-white text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-ocean-400">
                            <?php foreach ($gapData as $k): ?>
                            <option value="<?= htmlspecialchars($k['kecamatan']) ?>" class="bg-slate-900 text-white" 
                                data-dt="<?= $k['daya_tarik'] ?>" 
                                data-am="<?= $k['amenitas'] ?>" 
                                data-vis="<?= $k['kunjungan_tahunan'] ?>" 
                                data-beban="<?= $k['beban_per_amenitas'] ?>" 
                                data-status="<?= $k['status'] ?>" 
                                data-kelayakan="<?= $k['kelayakan'] ?>"
                                data-kab="<?= htmlspecialchars($k['kabupaten']) ?>">
                                <?= htmlspecialchars($k['kecamatan']) ?> (<?= htmlspecialchars($k['kabupaten']) ?>) &bull; <?= number_format($k['kunjungan_tahunan'], 0, ',', '.') ?> Wisatawan &bull; <?= $k['status'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Slider Tambah Hotel -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5">
                            <span class="text-ocean-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-hotel"></i> Rencana Tambah Homestay / Hotel:
                            </span>
                            <span id="labelAddHotel" class="text-white bg-ocean-600 px-2.5 py-0.5 rounded-lg font-mono">+0 Unit</span>
                        </div>
                        <input type="range" id="sliderHotel" min="0" max="15" value="0" class="w-full accent-ocean-400 cursor-pointer">
                    </div>

                    <!-- Slider Tambah Restoran -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5">
                            <span class="text-coral-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-utensils"></i> Rencana Tambah Sentra Kuliner / Resto:
                            </span>
                            <span id="labelAddResto" class="text-white bg-coral-600 px-2.5 py-0.5 rounded-lg font-mono">+0 Unit</span>
                        </div>
                        <input type="range" id="sliderResto" min="0" max="15" value="0" class="w-full accent-coral-400 cursor-pointer">
                    </div>
                </div>

                <!-- Panel Hasil Proyeksi Real-time -->
                <div class="lg:col-span-6 bg-white/5 border border-white/10 p-6 rounded-3xl backdrop-blur-sm space-y-4">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Hasil Proyeksi Ekuilibrium Real-time:</span>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white/5 p-3.5 rounded-2xl border border-white/5">
                            <span class="text-[10px] text-slate-400 block mb-0.5">Beban Wisatawan / Unit (Eksisting)</span>
                            <div id="simCurrentLoad" class="text-xl font-black text-slate-300">0</div>
                            <span class="text-[10px] text-slate-500">orang / fasilitas / thn</span>
                        </div>
                        <div class="bg-ocean-500/10 p-3.5 rounded-2xl border border-ocean-500/30">
                            <span class="text-[10px] text-ocean-300 block mb-0.5">Beban Proyeksi (Simulasi)</span>
                            <div id="simNewLoad" class="text-xl font-black text-emerald-400">0</div>
                            <span class="text-[10px] text-ocean-400">orang / fasilitas / thn</span>
                        </div>
                    </div>

                    <div class="bg-white/5 p-3.5 rounded-2xl border border-white/5 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Status Kelayakan Investasi:</span>
                            <div id="simFeasibilityLabel" class="text-sm font-black text-white mt-0.5">Memuat...</div>
                        </div>
                        <span id="simNewStatus" class="inline-block px-3 py-1 rounded-xl text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Ideal</span>
                    </div>

                    <!-- Pesan Kesimpulan Rekomendasi Anti-Rugi -->
                    <div id="simInsightText" class="text-xs text-slate-300 leading-relaxed bg-black/30 p-3.5 rounded-2xl border border-white/5">
                        Memuat kalkulasi proyeksi intervensi...
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Model Keseimbangan Triangulasi Pariwisata per Kecamatan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Keseimbangan 3 Faktor: Daya Tarik (Atraksi) &bull; Amenitas (Kapasitas) &bull; Volume Kunjungan BPS (Demand Pasar Riil)</p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                        <?= $kritisCount ?> Defisit Beban Tinggi
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <?= $waspadaCount ?> Potensi / Perlu Promosi
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <?= $idealCount ?> Seimbang / Ideal
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Kecamatan &amp; Wilayah</th>
                            <th class="px-6 py-4 text-center">Daya Tarik</th>
                            <th class="px-6 py-4 text-center">Amenitas (Hotel/Resto)</th>
                            <th class="px-6 py-4 text-center">Kunjungan BPS / Thn</th>
                            <th class="px-6 py-4 text-center">Beban / Amenitas</th>
                            <th class="px-6 py-4 text-center">Kelayakan Investasi</th>
                            <th class="px-6 py-4 text-right">Status Ekuilibrium</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <?php foreach ($gapData as $row): ?>
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900"><?= htmlspecialchars($row['kecamatan']) ?></div>
                                <div class="text-xs text-slate-400"><?= htmlspecialchars($row['kabupaten']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-slate-800"><?= $row['daya_tarik'] ?></span>
                                <span class="text-xs text-slate-400 block">(<?= $row['alam'] ?> Alam / <?= $row['buatan'] ?> B/B)</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-slate-800"><?= $row['amenitas'] ?></span>
                                <span class="text-xs text-slate-400 block">(<?= $row['hotel'] ?> Hotel / <?= $row['restoran'] ?> Resto)</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-slate-900 font-mono">
                                    <?= number_format($row['kunjungan_tahunan'], 0, ',', '.') ?>
                                </span>
                                <span class="text-[10px] text-slate-400 block">wisatawan/thn</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold font-mono <?= $row['beban_per_amenitas'] >= 8000 ? 'text-rose-600' : ($row['beban_per_amenitas'] < 2000 ? 'text-sky-600' : 'text-emerald-600') ?>">
                                    <?= number_format($row['beban_per_amenitas'], 0, ',', '.') ?>
                                </span>
                                <span class="text-[10px] text-slate-400 block">wis/unit</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                $kelClass = match($row['kelayakan']) {
                                    'Sangat Layak' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'Waspada Over-Supply' => 'bg-sky-100 text-sky-800 border-sky-200',
                                    'Waspada Rugi' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200'
                                };
                                ?>
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold border <?= $kelClass ?>">
                                    <?= $row['kelayakan'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php 
                                $badgeClass = match($row['badge_color']) {
                                    'red' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'sky' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    default => 'bg-slate-100 text-slate-600 border-slate-200'
                                };
                                ?>
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold border <?= $badgeClass ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- TAB 4: INDEKS PEMBANGUNAN KEPARIWISATAAN DAERAH (IPKN-DAERAH) -->
    <!-- ============================================================== -->
    <div id="tab-ipkn" class="tab-content hidden space-y-8">
        
        <!-- Header Info & Konsep Akademis -->
        <div class="bg-gradient-to-r from-slate-900 via-ocean-950 to-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-lg relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-3">
                    <i class="fa-solid fa-award"></i>
                    <span>Adaptasi Travel &amp; Tourism Development Index (TTDI) &amp; IPKN Kemenparekraf</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black">Babel Tourism Development Index (BTDI / IPKN-Daerah)</h2>
                <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-3xl leading-relaxed">
                    Indeks komposit multi-dimensi untuk mengukur daya saing kepariwisataan 7 Kabupaten/Kota di Kepulauan Bangka Belitung. Mengintegrasikan normalisasi Min-Max pada 3 pilar strategis: <strong>Pilar Atraksi (35%)</strong>, <strong>Pilar Amenitas (45%)</strong>, dan <strong>Pilar Pasar Wisatawan (20%)</strong>.
                </p>
            </div>
        </div>

        <!-- Highlight Top Performers -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-black">
                    🥇
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Peringkat 1 IPKN Babel</span>
                    <h4 class="font-extrabold text-slate-900 text-base"><?= $btdiScores[0]['kabupaten'] ?></h4>
                    <span class="text-xs font-bold text-amber-600"><?= $btdiScores[0]['total_ipkn'] ?> Poin (<?= $btdiScores[0]['grade'] ?>)</span>
                </div>
            </div>

            <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-black">
                    🏖️
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Atraksi Alam Terbanyak</span>
                    <?php 
                    $topAlam = $btdiScores;
                    usort($topAlam, fn($a, $b) => $b['jml_alam'] <=> $a['jml_alam']);
                    ?>
                    <h4 class="font-extrabold text-slate-900 text-base"><?= $topAlam[0]['kabupaten'] ?></h4>
                    <span class="text-xs font-bold text-emerald-600"><?= $topAlam[0]['jml_alam'] ?> Titik Wisata Alam</span>
                </div>
            </div>

            <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-ocean-50 text-ocean-600 flex items-center justify-center text-xl font-black">
                    🏨
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Amenitas Terlengkap</span>
                    <?php 
                    $topHotel = $btdiScores;
                    usort($topHotel, fn($a, $b) => $b['jml_hotel'] <=> $a['jml_hotel']);
                    ?>
                    <h4 class="font-extrabold text-slate-900 text-base"><?= $topHotel[0]['kabupaten'] ?></h4>
                    <span class="text-xs font-bold text-ocean-600"><?= $topHotel[0]['jml_hotel'] ?> Hotel Terverifikasi</span>
                </div>
            </div>

            <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-black">
                    📈
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Akselerasi Wisatawan</span>
                    <?php 
                    $topGrowth = $btdiScores;
                    usort($topGrowth, fn($a, $b) => $b['yoy_growth'] <=> $a['yoy_growth']);
                    ?>
                    <h4 class="font-extrabold text-slate-900 text-base"><?= $topGrowth[0]['kabupaten'] ?></h4>
                    <span class="text-xs font-bold text-purple-600">+<?= $topGrowth[0]['yoy_growth'] ?>% YoY Wisnus</span>
                </div>
            </div>
        </div>

        <!-- Radar Chart & Tabel Skor -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Radar Chart Spider Web (5 Kolom) -->
            <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Spider Radar Daya Saing</h3>
                        <p class="text-[11px] text-slate-500">Profil multi-dimensi per wilayah</p>
                    </div>
                    <select id="radarKabSelect" onchange="updateRadarChart()" class="text-xs bg-slate-100 border border-slate-200 rounded-xl px-2.5 py-1.5 font-bold text-slate-700 focus:ring-2 focus:ring-ocean-500">
                        <?php foreach ($btdiScores as $k): ?>
                        <option value="<?= htmlspecialchars($k['kabupaten']) ?>"><?= htmlspecialchars($k['kabupaten']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="h-72 sm:h-80 w-full relative">
                    <canvas id="chartIpknRadar"></canvas>
                </div>

                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-[11px] text-slate-600 leading-relaxed">
                    <strong class="text-slate-900">Catatan Metodologi:</strong> Garis biru menggambarkan profil kabupaten terpilih, sedangkan garis abu-abu putus-putus menunjukkan rata-rata provinsi. Semakin simetris dan lebar jaring radar, semakin mandiri ekosistem kepariwisataannya.
                </div>
            </div>

            <!-- Tabel Rangking Scorecard Kemenpar (7 Kolom) -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Scorecard Peringkat IPKN-Daerah</h3>
                        <p class="text-[11px] text-slate-500">Evaluasi komposit 7 Kabupaten/Kota Se-Bangka Belitung</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 text-[10px] font-bold">Skor Maks: 100</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100 uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="px-4 py-3 text-center">Rank</th>
                                <th class="px-4 py-3">Kabupaten / Kota</th>
                                <th class="px-3 py-3 text-center">Atraksi (35%)</th>
                                <th class="px-3 py-3 text-center">Amenitas (45%)</th>
                                <th class="px-3 py-3 text-center">Pasar (20%)</th>
                                <th class="px-3 py-3 text-right">Total IPKN</th>
                                <th class="px-4 py-3 text-center">Kesiapan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($btdiScores as $row): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3 text-center">
                                    <?php if ($row['rank'] == 1): ?>
                                    <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 font-black inline-flex items-center justify-center text-xs">1</span>
                                    <?php elseif ($row['rank'] == 2): ?>
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-black inline-flex items-center justify-center text-xs">2</span>
                                    <?php elseif ($row['rank'] == 3): ?>
                                    <span class="w-6 h-6 rounded-full bg-amber-50 text-amber-800 font-black inline-flex items-center justify-center text-xs">3</span>
                                    <?php else: ?>
                                    <span class="text-slate-400 font-bold"><?= $row['rank'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    <?= htmlspecialchars($row['kabupaten']) ?>
                                </td>
                                <td class="px-3 py-3 text-center font-semibold text-emerald-600">
                                    <?= $row['skor_atraksi'] ?>
                                </td>
                                <td class="px-3 py-3 text-center font-semibold text-ocean-600">
                                    <?= $row['skor_amenitas'] ?>
                                </td>
                                <td class="px-3 py-3 text-center font-semibold text-purple-600">
                                    <?= $row['skor_pasar'] ?>
                                </td>
                                <td class="px-3 py-3 text-right">
                                    <span class="text-sm font-black text-slate-900"><?= $row['total_ipkn'] ?></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php
                                    $bColor = match($row['grade_color']) {
                                        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'ocean' => 'bg-ocean-50 text-ocean-700 border-ocean-200',
                                        'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default => 'bg-rose-50 text-rose-700 border-rose-200'
                                    };
                                    ?>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border <?= $bColor ?>">
                                        <?= $row['grade'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- TAB 5: TIPOLOGI KLASSEN SEKTOR PARIWISATA                      -->
    <!-- ============================================================== -->
    <div id="tab-klassen" class="tab-content hidden space-y-8">
        
        <!-- Header Info & Konsep Akademis -->
        <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 text-white p-4 sm:p-6 lg:p-8 rounded-3xl shadow-lg relative overflow-hidden w-full max-w-full min-w-0">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-3">
                    <i class="fa-solid fa-table-cells-large"></i>
                    <span>Analisis Ekonomi Regional &amp; Perencanaan Pariwisata Daerah</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black">Matriks Tipologi Klassen Pariwisata Bangka Belitung</h2>
                <p class="text-slate-300 text-xs sm:text-sm mt-2 max-w-3xl leading-relaxed">
                    Memetakan posisi 7 Kabupaten/Kota ke dalam <strong>4 Kuadran Kebijakan Kemenparekraf</strong> berdasarkan perbandingan antara laju pertumbuhan wisatawan daerah terhadap rata-rata provinsi (<strong>g<sub>prov</sub> = +<?= $klassenData['g_provinsi'] ?>%</strong>) dan volume kunjungan terhadap rata-rata provinsi (<strong>y<sub>prov</sub> = <?= number_format($klassenData['avg_kunjungan_prov']) ?></strong> wisatawan).
                </p>
            </div>
        </div>

        <!-- 4 Kuadran Summary Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Kuadran I: Maju & Tumbuh Cepat -->
            <div class="bg-white p-6 rounded-3xl border-2 border-emerald-500/30 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Kuadran I: Maju &amp; Tumbuh Cepat
                    </span>
                    <span class="text-xs font-bold text-slate-400">g &ge; g<sub>prov</sub> &amp; y &ge; y<sub>prov</sub></span>
                </div>
                <p class="text-xs text-slate-600 mb-3">
                    <strong>Pusat Gravitasi Wisata (Prime Engine).</strong> Wilayah dengan pangsa pasar kunjungan di atas rata-rata provinsi dan terus berekspansi pesat.
                </p>
                <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-200/80 mb-3">
                    <span class="text-[11px] font-bold text-emerald-900 block mb-1">Kabupaten/Kota:</span>
                    <div class="flex flex-wrap gap-2">
                        <?php if (empty($klassenData['quadrants']['kuadran_1'])): ?>
                        <span class="text-xs text-slate-400 italic">Tidak ada wilayah di kuadran ini.</span>
                        <?php else: foreach ($klassenData['quadrants']['kuadran_1'] as $q1): ?>
                        <span class="px-2.5 py-1 rounded-xl bg-white border border-emerald-300 text-emerald-800 font-extrabold text-xs shadow-sm">
                            <?= $q1['kabupaten'] ?> (+<?= $q1['yoy_growth'] ?>% &bull; <?= number_format($q1['kunjungan_2026']) ?> wisnus)
                        </span>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <div class="text-[11px] text-slate-500 flex items-start gap-2">
                    <i class="fa-solid fa-lightbulb text-amber-500 mt-0.5"></i>
                    <span><strong>Arah Kebijakan:</strong> Kendalikan daya dukung lingkungan (*carrying capacity*), perluas MICE &amp; quality tourism, dan perpanjang lama tinggal wisatawan.</span>
                </div>
            </div>

            <!-- Kuadran II: Berkembang Cepat (Potensial) -->
            <div class="bg-white p-6 rounded-3xl border-2 border-ocean-500/30 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-ocean-100 text-ocean-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-rocket"></i> Kuadran II: Berkembang Cepat (Potensial)
                    </span>
                    <span class="text-xs font-bold text-slate-400">g &ge; g<sub>prov</sub> &amp; y &lt; y<sub>prov</sub></span>
                </div>
                <p class="text-xs text-slate-600 mb-3">
                    <strong>Destinasi Naik Daun (Rising Star).</strong> Laju pertumbuhan sangat tinggi namun pangsa volume dan fasilitas amenitas penunjang masih terbatas.
                </p>
                <div class="p-3 bg-ocean-50 rounded-2xl border border-ocean-200/80 mb-3">
                    <span class="text-[11px] font-bold text-ocean-900 block mb-1">Kabupaten/Kota:</span>
                    <div class="flex flex-wrap gap-2">
                        <?php if (empty($klassenData['quadrants']['kuadran_2'])): ?>
                        <span class="text-xs text-slate-400 italic">Tidak ada wilayah di kuadran ini.</span>
                        <?php else: foreach ($klassenData['quadrants']['kuadran_2'] as $q2): ?>
                        <span class="px-2.5 py-1 rounded-xl bg-white border border-ocean-300 text-ocean-800 font-extrabold text-xs shadow-sm">
                            <?= $q2['kabupaten'] ?> (+<?= $q2['yoy_growth'] ?>% &bull; <?= number_format($q2['kunjungan_2026']) ?> wisnus)
                        </span>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <div class="text-[11px] text-slate-500 flex items-start gap-2">
                    <i class="fa-solid fa-lightbulb text-amber-500 mt-0.5"></i>
                    <span><strong>Arah Kebijakan:</strong> Prioritaskan pembangunan amenitas hotel dan restoran, suntikan DAK Fisik Pariwisata, dan promosi destinasi baru.</span>
                </div>
            </div>

            <!-- Kuadran III: Maju Tertekan (Mapan/Jenuh) -->
            <div class="bg-white p-6 rounded-3xl border-2 border-amber-500/30 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-hourglass-half"></i> Kuadran III: Maju Tertekan (Mapan)
                    </span>
                    <span class="text-xs font-bold text-slate-400">g &lt; g<sub>prov</sub> &amp; y &ge; y<sub>prov</sub></span>
                </div>
                <p class="text-xs text-slate-600 mb-3">
                    <strong>Destinasi Mapan (Mature).</strong> Basis wisatawan dan fasilitas sudah mapan dan besar, namun laju pertumbuhannya melambat di bawah rata-rata provinsi.
                </p>
                <div class="p-3 bg-amber-50 rounded-2xl border border-amber-200/80 mb-3">
                    <span class="text-[11px] font-bold text-amber-900 block mb-1">Kabupaten/Kota:</span>
                    <div class="flex flex-wrap gap-2">
                        <?php if (empty($klassenData['quadrants']['kuadran_3'])): ?>
                        <span class="text-xs text-slate-400 italic">Tidak ada wilayah di kuadran ini.</span>
                        <?php else: foreach ($klassenData['quadrants']['kuadran_3'] as $q3): ?>
                        <span class="px-2.5 py-1 rounded-xl bg-white border border-amber-300 text-amber-800 font-extrabold text-xs shadow-sm">
                            <?= $q3['kabupaten'] ?> (+<?= $q3['yoy_growth'] ?>% &bull; <?= number_format($q3['kunjungan_2026']) ?> wisnus)
                        </span>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <div class="text-[11px] text-slate-500 flex items-start gap-2">
                    <i class="fa-solid fa-lightbulb text-amber-500 mt-0.5"></i>
                    <span><strong>Arah Kebijakan:</strong> Revitalisasi produk wisata lama, suntikan atraksi Karisma Event Nusantara (KEN), dan inovasi paket wisata tematik.</span>
                </div>
            </div>

            <!-- Kuadran IV: Relatif Tertinggal (Rintisan) -->
            <div class="bg-white p-6 rounded-3xl border-2 border-rose-500/30 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-seedling"></i> Kuadran IV: Relatif Tertinggal (Rintisan)
                    </span>
                    <span class="text-xs font-bold text-slate-400">g &lt; g<sub>prov</sub> &amp; y &lt; y<sub>prov</sub></span>
                </div>
                <p class="text-xs text-slate-600 mb-3">
                    <strong>Destinasi Rintisan (Inception).</strong> Laju pertumbuhan maupun volume pasar wisatawan masih tertinggal dibanding rata-rata provinsi.
                </p>
                <div class="p-3 bg-rose-50 rounded-2xl border border-rose-200/80 mb-3">
                    <span class="text-[11px] font-bold text-rose-900 block mb-1">Kabupaten/Kota:</span>
                    <div class="flex flex-wrap gap-2">
                        <?php if (empty($klassenData['quadrants']['kuadran_4'])): ?>
                        <span class="text-xs text-slate-400 italic">Tidak ada wilayah di kuadran ini.</span>
                        <?php else: foreach ($klassenData['quadrants']['kuadran_4'] as $q4): ?>
                        <span class="px-2.5 py-1 rounded-xl bg-white border border-rose-300 text-rose-800 font-extrabold text-xs shadow-sm">
                            <?= $q4['kabupaten'] ?> (<?= $q4['yoy_growth'] ?>% &bull; <?= number_format($q4['kunjungan_2026']) ?> wisnus)
                        </span>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <div class="text-[11px] text-slate-500 flex items-start gap-2">
                    <i class="fa-solid fa-lightbulb text-amber-500 mt-0.5"></i>
                    <span><strong>Arah Kebijakan:</strong> Penguatan aksesibilitas dasar transportasi, pendampingan Pokdarwis desa wisata, dan digitalisasi promosi.</span>
                </div>
            </div>

        </div>

        <!-- Scatter Plot Kuadran Klassen -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Diagram Sebaran Kuadran Tipologi Klassen</h3>
                    <p class="text-xs text-slate-500">Sumbu X: Pertumbuhan YoY (%) &bull; Sumbu Y: Volume Kunjungan Wisatawan (Jan-Apr 2026)</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 flex-wrap">
                    <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Kuadran I</span>
                    <span class="inline-flex items-center gap-1 ml-2"><span class="w-3 h-3 rounded-full bg-ocean-500 inline-block"></span> Kuadran II</span>
                    <span class="inline-flex items-center gap-1 ml-2"><span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span> Kuadran III</span>
                    <span class="inline-flex items-center gap-1 ml-2"><span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span> Kuadran IV</span>
                </div>
            </div>

            <div class="h-80 sm:h-96 w-full relative">
                <canvas id="chartKlassenScatter"></canvas>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- TAB 6: POLA SPASIAL (NNA & GLOBAL MORAN'S I)                   -->
    <!-- ============================================================== -->
    <div id="tab-nna" class="tab-content hidden space-y-8">
        
        <!-- Header Tab 6 -->
        <div class="bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 text-white p-4 sm:p-6 lg:p-8 rounded-3xl shadow-lg border border-purple-800/30 flex flex-col md:flex-row md:items-center justify-between gap-4 w-full max-w-full min-w-0 overflow-hidden">
            <div>
                <span class="px-3 py-1 rounded-full text-[11px] font-black bg-purple-500/20 text-purple-300 border border-purple-500/30 uppercase tracking-wider inline-flex items-center gap-1.5 mb-2">
                    <i class="fa-solid fa-circle-nodes"></i> Geocomputation &amp; Spatial Data Mining
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-white">Analisis Pola Spasial &amp; Autokorelasi Keruangan</h2>
                <p class="text-xs sm:text-sm text-purple-200/80 mt-1 max-w-3xl">
                    Pembuktian matematis pola sebaran 875 objek wisata (Nearest Neighbor Analysis / Clark &amp; Evans 1954) dan derajat pengelompokan popularitas destinasi (Global Moran's I).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-white/10 text-purple-200 text-xs font-bold border border-white/10">
                    N = <?= $nnaData['total_pois'] ?> Titik &bull; A = <?= number_format($nnaData['total_area']) ?> km²
                </span>
            </div>
        </div>

        <!-- Grid 2 Kolom: NNA Gauge & Pengujian Hipotesis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Kartu 1: Nearest Neighbor Ratio (R) & Gauge Meter -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Indeks Clark &amp; Evans (1954)</span>
                        <h3 class="font-extrabold text-slate-900 text-lg">Nearest Neighbor Ratio (R)</h3>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-purple-100 text-purple-800 border border-purple-200">
                        R = <?= $nnaData['ratio_r'] ?>
                    </span>
                </div>

                <!-- Visual Spatial Scale Bar -->
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-bold text-slate-600">
                        <span class="text-purple-700">Mengelompok (Clustered)</span>
                        <span class="text-slate-400">Acak (Random)</span>
                        <span class="text-emerald-700">Tersebar (Dispersed)</span>
                    </div>
                    <div class="relative w-full h-4 rounded-full bg-gradient-to-r from-purple-600 via-amber-400 to-emerald-500 shadow-inner">
                        <!-- Pin Posisi R = 0.407 -->
                        <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-7 h-7 rounded-full bg-white border-4 border-slate-900 shadow-lg flex items-center justify-center text-[9px] font-black text-slate-900" style="left: <?= min(100, max(0, ($nnaData['ratio_r'] / 2.15) * 100)) ?>%;">
                            R
                        </div>
                    </div>
                    <div class="flex justify-between text-[10px] text-slate-400 font-semibold pt-1">
                        <span>R = 0.0</span>
                        <span>R = 1.0 (CSR)</span>
                        <span>R = 2.15 (Hexagonal)</span>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200/80 text-xs text-purple-900 space-y-1.5 leading-relaxed">
                    <div class="font-bold flex items-center gap-1.5 text-purple-950">
                        <i class="fa-solid fa-circle-check text-purple-600"></i> Kesimpulan Pola Spasial:
                    </div>
                    <p>
                        Nilai <strong>R = <?= $nnaData['ratio_r'] ?> (&lt; 1.0)</strong> membuktikan secara empiris bahwa sebaran titik pariwisata di Kepulauan Bangka Belitung <strong>membentuk pola aglomerasi mengelompok yang sangat kuat (*heavily clustered*)</strong>. Titik-titik daya tarik dan amenitas saling berkumpul membentuk nodus konsentrasi di koridor pesisir dan perkotaan.
                    </p>
                </div>
            </div>

            <!-- Kartu 2: Uji Hipotesis Statistik & Formula Geodesik -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pengujian Hipotesis Statistik</span>
                        <h3 class="font-extrabold text-slate-900 text-lg">Z-Score &amp; Derajat Keyakinan</h3>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-200">
                        Z = <?= $nnaData['z_score'] ?>
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 font-bold block mb-0.5">Jarak Teramati Rata-Rata (D̄O):</span>
                        <span class="text-base font-black text-slate-900"><?= $nnaData['mean_observed_m'] ?> m</span>
                        <span class="text-[10px] text-slate-500 block">(<?= $nnaData['mean_observed'] ?> km antar-tetangga)</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 font-bold block mb-0.5">Jarak Harapan Acak (D̄E):</span>
                        <span class="text-base font-black text-slate-900"><?= $nnaData['mean_expected_m'] ?> m</span>
                        <span class="text-[10px] text-slate-500 block">(<?= $nnaData['mean_expected'] ?> km jika acak Poisson)</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 font-bold block mb-0.5">Standard Error (SE):</span>
                        <span class="text-sm font-black text-slate-800"><?= $nnaData['standard_error'] ?></span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                        <span class="text-[10px] text-slate-400 font-bold block mb-0.5">Signifikansi (p-value):</span>
                        <span class="text-sm font-black text-emerald-700"><?= $nnaData['p_value'] ?></span>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-900 text-white text-xs space-y-1.5 leading-relaxed">
                    <div class="font-bold flex items-center gap-1.5 text-amber-400">
                        <i class="fa-solid fa-scale-balanced"></i> Keputusan Uji Hipotesis (α = 0.001):
                    </div>
                    <p class="text-slate-300">
                        Karena nilai <strong>|Z| = <?= abs($nnaData['z_score']) ?> &gt; 2.58</strong> dengan <strong>p &lt; 0.0001</strong>, maka <strong>H0 ditolak</strong>. Probabilitas bahwa pola sebaran ini terjadi secara acak (*Complete Spatial Randomness*) adalah <strong>kurang dari 0.01%</strong>. Pola klaster ini signifikan pada tingkat kepercayaan 99.9%.
                    </p>
                </div>
            </div>

        </div>

        <!-- Tabel Perbandingan NNA per Kabupaten/Kota -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Perbandingan Pola Spasial per Kabupaten / Kota</h3>
                    <p class="text-xs text-slate-500">Menganalisis disparitas konsentrasi spasial di masing-masing wilayah administratif Babel</p>
                </div>
                <span class="text-xs text-slate-400 font-semibold">Data Luas Wilayah Resmi BPS</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 bg-slate-50/75">
                            <th class="py-3 px-4 font-bold">Kabupaten / Kota</th>
                            <th class="py-3 px-3 font-bold text-center">Jumlah Titik (N)</th>
                            <th class="py-3 px-3 font-bold text-right">Luas (km²)</th>
                            <th class="py-3 px-3 font-bold text-right">D̄O (km)</th>
                            <th class="py-3 px-3 font-bold text-right">D̄E (km)</th>
                            <th class="py-3 px-3 font-bold text-center">Rasio (R)</th>
                            <th class="py-3 px-3 font-bold text-center">Z-Score</th>
                            <th class="py-3 px-4 font-bold text-center">Pola Spasial</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <?php foreach ($nnaData['daerah'] as $dn): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-900"><?= $dn['kabupaten'] ?></td>
                            <td class="py-3 px-3 text-center"><?= $dn['n'] ?></td>
                            <td class="py-3 px-3 text-right text-slate-500"><?= number_format($dn['area_km2'], 1) ?></td>
                            <td class="py-3 px-3 text-right font-semibold"><?= $dn['d_o'] ?></td>
                            <td class="py-3 px-3 text-right text-slate-400"><?= $dn['d_e'] ?></td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[11px] font-black <?= $dn['r'] < 1.0 ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                    <?= $dn['r'] ?>
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center font-bold <?= $dn['z'] < 0 ? 'text-purple-700' : 'text-blue-700' ?>">
                                <?= $dn['z'] ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold <?= $dn['r'] < 1.0 ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-700 border border-slate-200' ?>">
                                    <?= $dn['pola'] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bagian Global Moran's I & Moran Scatterplot -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <span class="text-xs font-bold text-purple-600 uppercase tracking-wider block">Autokorelasi Spasial Atribut</span>
                    <h3 class="font-extrabold text-slate-900 text-lg">Global Moran's I &amp; Kuadran Sebaran Nilai</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Menguji apakah destinasi yang populer cenderung bertetangga dengan destinasi populer lainnya (Hotspot Clustering)</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-2xl bg-purple-50 border border-purple-200 text-center">
                        <span class="text-[10px] text-purple-600 font-bold block">Indeks Moran's I</span>
                        <span class="text-lg font-black text-purple-900">+<?= $moranData['moran_i'] ?></span>
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                        <span class="text-[10px] text-slate-500 font-bold block">Ekspektasi E[I]</span>
                        <span class="text-xs font-bold text-slate-700"><?= $moranData['expected_i'] ?></span>
                    </div>
                </div>
            </div>

            <!-- Ringkasan 4 Kuadran Moran -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200">
                    <span class="text-[10px] font-bold text-emerald-700 block mb-0.5">High-High (Hotspot):</span>
                    <div class="text-xl font-black text-emerald-900"><?= $moranData['quadrants']['high_high'] ?> Destinasi</div>
                    <p class="text-[10px] text-emerald-600 mt-1">Populer dikelilingi destinasi populer.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-blue-50 border border-blue-200">
                    <span class="text-[10px] font-bold text-blue-700 block mb-0.5">Low-Low (Coldspot):</span>
                    <div class="text-xl font-black text-blue-900"><?= $moranData['quadrants']['low_low'] ?> Destinasi</div>
                    <p class="text-[10px] text-blue-600 mt-1">Ulasan rendah di kawasan tenang.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200">
                    <span class="text-[10px] font-bold text-amber-700 block mb-0.5">High-Low (Outlier):</span>
                    <div class="text-xl font-black text-amber-900"><?= $moranData['quadrants']['high_low'] ?> Destinasi</div>
                    <p class="text-[10px] text-amber-600 mt-1">Unggulan mandiri di area sepi.</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200">
                    <span class="text-[10px] font-bold text-rose-700 block mb-0.5">Low-High (Outlier):</span>
                    <div class="text-xl font-black text-rose-900"><?= $moranData['quadrants']['low_high'] ?> Destinasi</div>
                    <p class="text-[10px] text-rose-600 mt-1">Ulasan rendah di pusat keramaian.</p>
                </div>
            </div>

            <!-- Moran Scatterplot Chart -->
            <div class="pt-2">
                <div class="h-80 sm:h-96 w-full relative">
                    <canvas id="chartMoranScatter"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- TAB 7: PREDIKSI TREN BPS (TIME SERIES FORECASTING)             -->
    <!-- ============================================================== -->
    <div id="tab-forecast" class="tab-content hidden space-y-8">
        
        <!-- Header Tab 7 -->
        <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white p-4 sm:p-6 lg:p-8 rounded-3xl shadow-lg border border-blue-800/30 flex flex-col md:flex-row md:items-center justify-between gap-4 w-full max-w-full min-w-0 overflow-hidden">
            <div>
                <span class="px-3 py-1 rounded-full text-[11px] font-black bg-blue-500/20 text-blue-300 border border-blue-500/30 uppercase tracking-wider inline-flex items-center gap-1.5 mb-2">
                    <i class="fa-solid fa-chart-line"></i> Predictive Data Analytics
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-white">Proyeksi Deret Waktu Kunjungan Wisatawan BPS</h2>
                <p class="text-xs sm:text-sm text-blue-200/80 mt-1 max-w-3xl">
                    Pemodelan tren masa depan menggunakan algoritma Double Exponential Smoothing (Holt's Linear Trend) dengan rentang interval kepercayaan 95%.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-white/10 text-blue-200 text-xs font-bold border border-white/10">
                    Akurasi Model: <?= $forecastData['metrics']['accuracy'] ?>% (MAPE: <?= $forecastData['metrics']['mape'] ?>%)
                </span>
            </div>
        </div>

        <!-- Kartu Metrik Proyeksi Makro -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Realisasi 2025 (Setahun)</span>
                    <div class="text-2xl font-black text-slate-900 mt-1">
                        <?= number_format($forecastData['summary']['total_2025_actual']) ?>
                    </div>
                </div>
                <span class="text-[11px] text-slate-400 mt-2">Kunjungan Wisnus BPS Resmi</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Realisasi Caturwulan I 2026</span>
                    <div class="text-2xl font-black text-ocean-700 mt-1">
                        <?= number_format($forecastData['summary']['actual_2026_caturwulan1']) ?>
                    </div>
                </div>
                <span class="text-[11px] text-emerald-600 font-bold mt-2">+9.95% vs Periode Sama 2025</span>
            </div>

            <div class="bg-gradient-to-br from-blue-700 to-indigo-900 text-white p-6 rounded-3xl shadow-md flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-blue-200 uppercase tracking-wider block mb-1">Proyeksi Total 2026 (Model)</span>
                    <div class="text-2xl sm:text-3xl font-black text-white mt-1">
                        <?= number_format($forecastData['summary']['projected_2026_total']) ?>
                    </div>
                </div>
                <span class="text-[11px] text-emerald-300 font-bold mt-2">
                    Proyeksi YoY: +<?= $forecastData['summary']['projected_growth_yoy'] ?>%
                </span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Root Mean Square Error</span>
                    <div class="text-2xl font-black text-slate-800 mt-1">
                        <?= number_format($forecastData['metrics']['rmse']) ?>
                    </div>
                </div>
                <span class="text-[11px] text-slate-400 mt-2">Standar Deviasi Error Residual</span>
            </div>
        </div>

        <!-- Grafik Time Series & Proyeksi Chart.js -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Kurva Tren Kunjungan Aktual &amp; Proyeksi Masa Depan</h3>
                    <p class="text-xs text-slate-500">Januari 2025 – Desember 2026 (Area berbayang menandakan pita interval kepercayaan 95%)</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-bold text-slate-600 flex-wrap">
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-slate-700 inline-block"></span> Data Aktual</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span> Nilai Model</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-indigo-500 inline-block"></span> Proyeksi 2026</span>
                </div>
            </div>

            <div class="h-80 sm:h-96 w-full relative">
                <canvas id="chartForecasting"></canvas>
            </div>
        </div>

        <!-- Tabel Rincian Proyeksi Bulanan Mei - Desember 2026 -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="pb-2 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-base">Tabel Estimasi Kunjungan Bulanan (Mei – Desember 2026)</h3>
                <p class="text-xs text-slate-500">Rujukan angka proyeksi untuk target perencanaan kapasitas hotel, restoran, dan penerbangan</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 bg-slate-50/75">
                            <th class="py-3 px-4 font-bold">Periode Proyeksi</th>
                            <th class="py-3 px-4 font-bold text-right">Batas Bawah 95% (Orang)</th>
                            <th class="py-3 px-4 font-bold text-center text-blue-700">Estimasi Titik (Orang)</th>
                            <th class="py-3 px-4 font-bold text-right">Batas Atas 95% (Orang)</th>
                            <th class="py-3 px-4 font-bold text-center">Status Periode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <?php foreach ($forecastData['forecast']['labels'] as $idx => $flab): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-bold text-slate-900"><?= $flab ?></td>
                            <td class="py-3 px-4 text-right text-slate-400"><?= number_format($forecastData['forecast']['ci_lower'][$idx]) ?></td>
                            <td class="py-3 px-4 text-center font-black text-blue-700 text-sm"><?= number_format($forecastData['forecast']['values'][$idx]) ?></td>
                            <td class="py-3 px-4 text-right text-slate-400"><?= number_format($forecastData['forecast']['ci_upper'][$idx]) ?></td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    Proyeksi Holt
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- TAB 8: DAYA DUKUNG LINGKUNGAN (TOURISM CARRYING CAPACITY - TCC) -->
    <!-- ============================================================== -->
    <div id="tab-tcc" class="tab-content hidden space-y-8">
        
        <!-- Header Tab 8 -->
        <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 text-white p-4 sm:p-6 lg:p-8 rounded-3xl shadow-lg border border-emerald-800/30 flex flex-col md:flex-row md:items-center justify-between gap-4 w-full max-w-full min-w-0 overflow-hidden">
            <div>
                <span class="px-3 py-1 rounded-full text-[11px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider inline-flex items-center gap-1.5 mb-2">
                    <i class="fa-solid fa-leaf"></i> Sustainable Ecotourism &amp; UNESCO Geopark
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-white">Daya Dukung Lingkungan (Tourism Carrying Capacity)</h2>
                <p class="text-xs sm:text-sm text-emerald-200/80 mt-1 max-w-3xl">
                    Evaluasi batas beban ekologis pantai granit dan geosite Babel berdasarkan metodologi standar Cifuentes (1992) dan UNWTO untuk mencegah kerusakan habitat pesisir (*Over-Tourism*).
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-white/10 text-emerald-200 text-xs font-bold border border-white/10">
                    6 Lokasi Pantai &amp; Geosite Ikonik
                </span>
            </div>
        </div>

        <!-- Formula Standar UNWTO / Cifuentes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tahap 1: Fisik Ruang</span>
                <div class="font-extrabold text-slate-900 text-sm">Physical Carrying Capacity (PCC)</div>
                <div class="p-2.5 bg-slate-50 rounded-xl font-mono text-[11px] text-slate-700">
                    PCC = Area &times; (1 / Standar Ruang) &times; Rf
                </div>
                <p class="text-[11px] text-slate-500">Kapasitas teoretis maksimum berdasarkan luas ruang efektif dan jam buka harian.</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tahap 2: Koreksi Alam</span>
                <div class="font-extrabold text-slate-900 text-sm">Real Carrying Capacity (RCC)</div>
                <div class="p-2.5 bg-slate-50 rounded-xl font-mono text-[11px] text-slate-700">
                    RCC = PCC &times; &prod; (1 - Koreksi Faktor)
                </div>
                <p class="text-[11px] text-slate-500">Dikoreksi oleh terik panas matahari, abrasi pasang laut, dan batuan granit rentan.</p>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tahap 3: Kapasitas Kelola</span>
                <div class="font-extrabold text-slate-900 text-sm">Effective Carrying Capacity (ECC)</div>
                <div class="p-2.5 bg-slate-50 rounded-xl font-mono text-[11px] text-slate-700">
                    ECC = RCC &times; Management Capacity (MC)
                </div>
                <p class="text-[11px] text-slate-500">Batas aman kunjungan riil dengan mempertimbangkan kesiapan petugas &amp; sanitasi.</p>
            </div>
        </div>

        <!-- Kartu 6 Lokasi Pantai & Geosite -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($tccData as $tc): ?>
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                            <?= $tc['kabupaten'] ?>
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black <?= $tc['badge_color'] === 'emerald' ? 'bg-emerald-100 text-emerald-800' : ($tc['badge_color'] === 'amber' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') ?>">
                            <?= $tc['status'] ?>
                        </span>
                    </div>

                    <h4 class="font-extrabold text-slate-900 text-base leading-snug"><?= $tc['nama'] ?></h4>
                    <p class="text-xs text-slate-500"><?= $tc['wilayah'] ?></p>

                    <!-- Indikator Angka TCC -->
                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center text-xs">
                        <div class="p-2 rounded-xl bg-slate-50">
                            <span class="text-[9px] text-slate-400 font-bold block">PCC</span>
                            <span class="font-extrabold text-slate-700"><?= number_format($tc['pcc']) ?></span>
                        </div>
                        <div class="p-2 rounded-xl bg-slate-50">
                            <span class="text-[9px] text-slate-400 font-bold block">RCC</span>
                            <span class="font-extrabold text-slate-700"><?= number_format($tc['rcc']) ?></span>
                        </div>
                        <div class="p-2 rounded-xl bg-emerald-50 text-emerald-900 font-bold border border-emerald-200">
                            <span class="text-[9px] text-emerald-600 block">Batas ECC</span>
                            <span class="font-black text-sm"><?= number_format($tc['ecc']) ?></span>
                        </div>
                    </div>

                    <!-- Progress Bar Utilisasi Beban -->
                    <div class="pt-2 space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-600">Beban Puncak Harian:</span>
                            <span class="font-black text-slate-900"><?= number_format($tc['peak_daily']) ?> orang (<?= $tc['util_pct'] ?>%)</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full <?= $tc['util_pct'] > 85 ? 'bg-rose-500' : ($tc['util_pct'] >= 65 ? 'bg-amber-500' : 'bg-emerald-500') ?>" style="width: <?= min(100, $tc['util_pct']) ?>%;"></div>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-600 leading-relaxed mt-2">
                    <strong class="text-slate-900 block mb-0.5"><i class="fa-solid fa-shield-heart text-emerald-600 mr-1"></i> Rekomendasi Konservasi:</strong>
                    <?= $tc['mitigasi'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- MODAL: LEMBAR REKOMENDASI KEBIJAKAN RESMI (POLICY BRIEF)       -->
    <!-- ============================================================== -->
    <div id="policyBriefModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden">
            
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-900 to-ocean-950 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-amber-400 text-lg">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-ocean-300 uppercase tracking-wider block">Kemenparekraf &amp; Disparbud Babel</span>
                        <h3 class="font-extrabold text-base sm:text-lg text-white">Executive Policy Brief Pariwisata</h3>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="printPolicyBrief()" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak / PDF</span>
                    </button>
                    <button onclick="closePolicyBriefModal()" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Printable Body -->
            <div class="p-6 sm:p-8 overflow-y-auto space-y-6 text-slate-800 text-xs sm:text-sm leading-relaxed" id="printableBriefArea">
                
                <!-- Kop Surat / Dokumen Info -->
                <div class="border-b-2 border-slate-900 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-2 text-xs">
                    <div>
                        <h4 class="font-black text-slate-900 text-base uppercase tracking-tight">Pemerintah Provinsi Kepulauan Bangka Belitung</h4>
                        <p class="text-slate-500 text-xs">Dinas Pariwisata, Kebudayaan, Kepemudaan dan Olahraga &bull; Kerjasama BPS Provinsi</p>
                    </div>
                    <div class="text-right text-[11px] text-slate-500">
                        <div><strong>No:</strong> <?= $policyBrief['nomor_dokumen'] ?></div>
                        <div><strong>Tanggal:</strong> <?= $policyBrief['tanggal_cetak'] ?></div>
                        <span class="inline-block px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold text-[10px] mt-1">Status: Siap Ditindaklanjuti</span>
                    </div>
                </div>

                <!-- Judul Policy Brief -->
                <div class="bg-ocean-50 p-4 rounded-2xl border border-ocean-100">
                    <span class="text-[10px] font-bold text-ocean-700 uppercase tracking-wider block mb-1">Perihal Rekomendasi:</span>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base leading-snug">
                        Strategi Pemerataan Ekosistem Pariwisata Bangka Belitung: Pengentasan Kesenjangan Amenitas &amp; Intervensi Tipologi Wilayah Berbasis Bukti
                    </h3>
                </div>

                <!-- Ringkasan Eksekutif -->
                <div>
                    <h4 class="font-black text-slate-900 uppercase text-xs tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-pie text-ocean-600"></i> 1. Ringkasan Kinerja Makro
                    </h4>
                    <p class="text-slate-600 leading-relaxed text-xs">
                        Kunjungan wisatawan nusantara ke Kepulauan Bangka Belitung pada caturwulan I 2026 mencapai <strong><?= number_format($policyBrief['total_kunjungan_2026']) ?> orang</strong>, bertumbuh <strong>+<?= $policyBrief['yoy_provinsi'] ?>% YoY</strong>. Namun demikian, hasil regresi dan korelasi spasial Pearson mengonfirmasi bahwa pertumbuhan ini sangat dipengaruhi oleh ketersediaan fasilitas akomodasi (<strong>r = <?= $policyBrief['macro_correlation']['hotel_vs_kunjungan'] ?></strong>) dan restoran (<strong>r = <?= $policyBrief['macro_correlation']['resto_vs_kunjungan'] ?></strong>), menyebabkan terjadinya disparitas spasial antardaerah.
                    </p>
                </div>

                <!-- Temuan Utama Indeks & Tipologi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <h5 class="font-bold text-slate-900 text-xs mb-1.5">Peringkat 1 IPKN Daerah (BTDI):</h5>
                        <div class="text-base font-black text-ocean-700"><?= $policyBrief['top_district_ipkn']['kabupaten'] ?> (Skor: <?= $policyBrief['top_district_ipkn']['total_ipkn'] ?>)</div>
                        <p class="text-[11px] text-slate-500 mt-1">Memiliki kesiapan ekosistem terlengkap dengan 60 hotel dan 42 restoran resmi, menjadi pusat gravitasi utama pariwisata provinsi.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <h5 class="font-bold text-slate-900 text-xs mb-1.5">Kawasan Defisit Amenitas Paling Kritis:</h5>
                        <div class="space-y-1 mt-1 text-[11px]">
                            <?php foreach ($policyBrief['critical_deficits'] as $cd): ?>
                            <div class="flex items-center justify-between text-rose-700 font-bold">
                                <span><?= $cd['kecamatan'] ?> (<?= $cd['kabupaten'] ?>)</span>
                                <span>Defisit: <?= $cd['gap_defisit'] ?> unit</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Prioritas Aksi Kebijakan Kemenparekraf -->
                <div>
                    <h4 class="font-black text-slate-900 uppercase text-xs tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-list-check text-emerald-600"></i> 2. Paket Rekomendasi Aksi Prioritas (Kemenparekraf &amp; Dispar)
                    </h4>
                    <div class="space-y-2 text-xs">
                        <?php foreach ($policyBrief['prioritas_kebijakan'] as $idx => $pk): ?>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <?= $idx + 1 ?>
                            </span>
                            <div class="flex-grow">
                                <div class="flex items-center justify-between">
                                    <strong class="text-slate-900"><?= $pk['pilar'] ?></strong>
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold <?= $pk['urgensi'] === 'Sangat Tinggi' ? 'bg-rose-100 text-rose-800' : ($pk['urgensi'] === 'Tinggi' ? 'bg-amber-100 text-amber-800' : 'bg-ocean-100 text-ocean-800') ?>">
                                        Urgensi: <?= $pk['urgensi'] ?>
                                    </span>
                                </div>
                                <p class="text-slate-600 text-[11px] mt-1"><?= $pk['aksi'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Lembar Otorisasi & Tanda Tangan Analis Eksekutif -->
                <div class="pt-6 border-t-2 border-slate-900 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div class="space-y-1.5 p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Disusun &amp; Dianalisis Oleh:</span>
                        <div class="flex items-center gap-3 pt-0.5">
                            <img src="assets/img/fathurrahman.jpg" alt="Fathurrahman Safari Lubis" class="w-12 h-12 rounded-xl object-cover object-center border-2 border-ocean-600 shadow-sm flex-shrink-0">
                            <div>
                                <div class="font-black text-slate-900 text-sm"><?= $policyBrief['analyst_name'] ?></div>
                                <div class="text-[11px] text-slate-600 font-semibold">
                                    NIM: <span class="font-mono text-ocean-700"><?= $policyBrief['analyst_nim'] ?></span> &bull; <?= $policyBrief['analyst_role'] ?>
                                </div>
                                <div class="text-[10px] text-slate-500">
                                    Mahasiswa <?= $policyBrief['analyst_prodi'] ?> &bull; <strong><?= $policyBrief['analyst_institution'] ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="pt-1.5 border-t border-slate-200 flex items-center gap-1 text-[9px] text-emerald-700 font-bold font-mono">
                            <i class="fa-solid fa-stamp text-emerald-600"></i> <?= $policyBrief['auth_token'] ?> (VERIFIED)
                        </div>
                    </div>

                    <div class="space-y-1.5 p-3 rounded-xl bg-ocean-50/50 border border-ocean-200 text-right sm:text-right">
                        <span class="text-[10px] font-bold text-ocean-600 uppercase tracking-wider block">Otorisasi Platform &amp; Integritas Data:</span>
                        <div class="font-black text-slate-900 text-sm">Bangka Belitung Tourism Intelligence</div>
                        <div class="text-[11px] text-ocean-800 font-semibold">Spatial Decision Support System (SDSS v2.4)</div>
                        <div class="text-[11px] text-slate-500">
                            Berdasarkan Standar Satu Data Indonesia &amp; IPKN Kemenparekraf RI
                        </div>
                        <div class="pt-1.5 border-t border-ocean-200 text-[9px] text-slate-400 font-medium">
                            Dokumen digital sah dicetak untuk rujukan kebijakan resmi daerah
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2 text-xs">
                <button onclick="closePolicyBriefModal()" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold transition-all">
                    Tutup
                </button>
                <button onclick="printPolicyBrief()" class="px-4 py-2 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-bold transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Lembar Resmi</span>
                </button>
            </div>

        </div>
    </div>

</main>

<script>
// Fungsi Tab Switching
function switchTab(targetId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.className = 'tab-btn px-4 py-2.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100';
    });

    document.getElementById(targetId).classList.remove('hidden');
    const activeBtn = document.getElementById('btn-' + targetId);
    activeBtn.className = 'tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-ocean-600 text-white shadow-sm';
}

// Chart BPS Time Series Bulanan
const monthlyCtx = document.getElementById('chartBpsMonthly').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($monthlyTrends['labels']) ?>,
        datasets: [
            {
                label: 'Tahun 2025',
                data: <?= json_encode($monthlyTrends['series_2025']) ?>,
                borderColor: '#94a3b8',
                backgroundColor: 'rgba(148, 163, 184, 0.1)',
                borderWidth: 2,
                borderDash: [5, 5],
                tension: 0.3,
                fill: true
            },
            {
                label: 'Tahun 2026',
                data: <?= json_encode($monthlyTrends['series_2026']) ?>,
                borderColor: '#0284c7',
                backgroundColor: 'rgba(2, 132, 199, 0.15)',
                borderWidth: 3,
                pointBackgroundColor: '#0284c7',
                pointRadius: 5,
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: '#f1f5f9' }, beginAtZero: false }
        }
    }
});

// ==============================================================
// SIMULATOR WHAT-IF INTERAKTIF
// ==============================================================
const selKec = document.getElementById('simKecamatan');
const sHotel = document.getElementById('sliderHotel');
const sResto = document.getElementById('sliderResto');

function updateSimulation() {
    if (!selKec) return;
    const opt = selKec.options[selKec.selectedIndex];
    const dt = parseInt(opt.getAttribute('data-dt')) || 0;
    const am = parseInt(opt.getAttribute('data-am')) || 0;
    const vis = parseInt(opt.getAttribute('data-vis')) || 0;
    const addH = parseInt(sHotel.value) || 0;
    const addR = parseInt(sResto.value) || 0;

    document.getElementById('labelAddHotel').innerText = `+${addH} Unit`;
    document.getElementById('labelAddResto').innerText = `+${addR} Unit`;

    // Beban pengunjung per amenitas (orang / unit / thn)
    const currentLoad = am > 0 ? Math.round(vis / am) : vis;
    const newAm = am + addH + addR;
    const newLoad = newAm > 0 ? Math.round(vis / newAm) : vis;

    const elCurrentLoad = document.getElementById('simCurrentLoad');
    const elNewLoad = document.getElementById('simNewLoad');
    if (elCurrentLoad) elCurrentLoad.innerText = currentLoad.toLocaleString('id-ID');
    if (elNewLoad) elNewLoad.innerText = newLoad.toLocaleString('id-ID');

    // Evaluasi Kelayakan Investasi & Status Keseimbangan
    let feasibilityText = 'Seimbang / Stabil';
    let feasibilityClass = 'text-emerald-400';
    let newStatus = 'Seimbang / Ideal';
    let newStatusBadge = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
    let insight = '';

    const kecName = opt.value;
    const totalAdded = addH + addR;

    if (vis < 4000 && totalAdded >= 3) {
        // PERINGATAN RISIKO KERUGIAN PENGELOLA (OVER-SUPPLY PADA PASAR SEPI)
        feasibilityText = '⚠️ Risiko Tinggi Merugikan Pengelola';
        feasibilityClass = 'text-rose-400 font-black';
        newStatus = 'Over-Supply (Pasar Sepi)';
        newStatusBadge = 'bg-rose-500/20 text-rose-300 border-rose-500/30';
        insight = `⚠️ <strong>Peringatan Risiko Pengelola:</strong> Total kunjungan di <strong>${kecName}</strong> saat ini masih relatif rendah (~<strong>${vis.toLocaleString('id-ID')} wisatawan/tahun</strong>). Menambah <strong>+${addH} hotel</strong> dan <strong>+${addR} resto</strong> berisiko membuat beban pelayanan anjlok ke ~<strong>${newLoad.toLocaleString('id-ID')} orang/unit</strong>, sehingga tingkat okupansi rendah dan <strong>pengelola berisiko merugi!</strong> Prioritaskan promosi event &amp; aktivasi atraksi terlebih dahulu sebelum menambah unit fisik.`;
    } else if (currentLoad >= 8000 && totalAdded > 0) {
        // SANGAT LAYAK & MENGUNTUNGKAN (PASAR MEMBLUDAK)
        feasibilityText = '🎉 Sangat Layak Investasi (Pasar Siap)';
        feasibilityClass = 'text-emerald-400 font-black';
        newStatus = 'Defisit Teratasi';
        newStatusBadge = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
        insight = `🎉 <strong>Investasi Sangat Layak &amp; Menguntungkan!</strong> Dengan volume kunjungan mencapai ~<strong>${vis.toLocaleString('id-ID')} wisatawan/tahun</strong>, penambahan <strong>${addH} hotel</strong> dan <strong>${addR} resto</strong> akan langsung terserap pasar tanpa risiko sepi. Beban pelayanan menjadi lebih sehat dari <strong>${currentLoad.toLocaleString('id-ID')}</strong> menjadi <strong>${newLoad.toLocaleString('id-ID')} orang/unit</strong>.`;
    } else if (totalAdded > 0) {
        // MODERAT / SEIMBANG
        feasibilityText = '📈 Layak Bertahap (Pasar Terjaga)';
        feasibilityClass = 'text-sky-400 font-bold';
        newStatus = 'Ekuilibrium Terjaga';
        newStatusBadge = 'bg-sky-500/20 text-sky-300 border-sky-500/30';
        insight = `📈 <strong>Progres Ekuilibrium Positif:</strong> Beban pelayanan bergeser dari <strong>${currentLoad.toLocaleString('id-ID')}</strong> menjadi <strong>${newLoad.toLocaleString('id-ID')} orang/unit</strong>. Penambahan fasilitas proporsional dengan proyeksi pertumbuhan pasar wisatawan.`;
    } else {
        feasibilityText = currentLoad >= 8000 ? '🔴 Butuh Tambahan Amenitas' : (vis < 4000 ? '🟡 Butuh Promosi Wisatawan' : '🟢 Kondisi Saat Ini Stabil');
        feasibilityClass = currentLoad >= 8000 ? 'text-rose-400' : 'text-slate-300';
        insight = `Geser slider <strong>+Hotel</strong> dan <strong>+Resto</strong> untuk menguji daya serap pasar wisatawan riil di <strong>${kecName}</strong> (~${vis.toLocaleString('id-ID')} kunjungan/tahun).`;
    }

    const elFeas = document.getElementById('simFeasibilityLabel');
    if (elFeas) {
        elFeas.innerHTML = feasibilityText;
        elFeas.className = `text-sm font-black mt-0.5 ${feasibilityClass}`;
    }

    const newStatusEl = document.getElementById('simNewStatus');
    if (newStatusEl) {
        newStatusEl.innerText = newStatus;
        newStatusEl.className = `inline-block px-3 py-1 rounded-xl text-xs font-bold border ${newStatusBadge}`;
    }

    const elInsight = document.getElementById('simInsightText');
    if (elInsight) elInsight.innerHTML = insight;
}

if (selKec && sHotel && sResto) {
    selKec.addEventListener('change', () => {
        sHotel.value = 0;
        sResto.value = 0;
        updateSimulation();
    });

    sHotel.addEventListener('input', updateSimulation);
    sResto.addEventListener('input', updateSimulation);

    updateSimulation();
}

// ==============================================================
// MODAL POLICY BRIEF CONTROLLER
// ==============================================================
function openPolicyBriefModal() {
    document.getElementById('policyBriefModal').classList.remove('hidden');
}
function closePolicyBriefModal() {
    document.getElementById('policyBriefModal').classList.add('hidden');
    document.body.classList.remove('printing-brief');
}
function printPolicyBrief() {
    document.body.classList.add('printing-brief');
    window.print();
    setTimeout(() => {
        document.body.classList.remove('printing-brief');
    }, 1200);
}

// ==============================================================
// RADAR SPIDER CHART: IPKN-DAERAH (BTDI)
// ==============================================================
const btdiScoresData = <?= json_encode($btdiScores) ?>;

// Hitung rata-rata norm provinsi untuk benchmark radar
const radarAvgProv = [0, 0, 0, 0, 0, 0];
btdiScoresData.forEach(d => {
    radarAvgProv[0] += d.norm.jml_alam;
    radarAvgProv[1] += d.norm.jml_buatan_budaya;
    radarAvgProv[2] += d.norm.jml_hotel;
    radarAvgProv[3] += d.norm.jml_restoran;
    radarAvgProv[4] += d.norm.kunjungan_2026;
    radarAvgProv[5] += d.norm.yoy_growth;
});
const countKab = btdiScoresData.length;
for (let i = 0; i < 6; i++) {
    radarAvgProv[i] = Math.round(radarAvgProv[i] / countKab);
}

const radarCtx = document.getElementById('chartIpknRadar').getContext('2d');
const radarLabels = ['Wisata Alam', 'Budaya & Buatan', 'Hotel', 'Restoran', 'Volume Pasar', 'Laju YoY'];

let ipknRadarChart = new Chart(radarCtx, {
    type: 'radar',
    data: {
        labels: radarLabels,
        datasets: [
            {
                label: btdiScoresData[0].kabupaten,
                data: [
                    btdiScoresData[0].norm.jml_alam,
                    btdiScoresData[0].norm.jml_buatan_budaya,
                    btdiScoresData[0].norm.jml_hotel,
                    btdiScoresData[0].norm.jml_restoran,
                    btdiScoresData[0].norm.kunjungan_2026,
                    btdiScoresData[0].norm.yoy_growth
                ],
                backgroundColor: 'rgba(2, 132, 199, 0.25)',
                borderColor: '#0284c7',
                pointBackgroundColor: '#0284c7',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#0284c7',
                borderWidth: 2.5
            },
            {
                label: 'Rata-Rata Provinsi',
                data: radarAvgProv,
                backgroundColor: 'rgba(148, 163, 184, 0.1)',
                borderColor: '#94a3b8',
                borderDash: [4, 4],
                pointBackgroundColor: '#94a3b8',
                borderWidth: 1.5
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        elements: {
            line: { tension: 0.2 }
        },
        scales: {
            r: {
                angleLines: { color: '#e2e8f0' },
                grid: { color: '#f1f5f9' },
                suggestedMin: 0,
                suggestedMax: 100,
                ticks: {
                    stepSize: 25,
                    backdropColor: 'transparent',
                    font: { size: 9 }
                },
                pointLabels: {
                    font: { size: 10, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                    color: '#334155'
                }
            }
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" } }
            }
        }
    }
});

function updateRadarChart() {
    const sel = document.getElementById('radarKabSelect');
    if (!sel) return;
    const targetName = sel.value;
    const item = btdiScoresData.find(d => d.kabupaten === targetName);
    if (!item) return;

    ipknRadarChart.data.datasets[0].label = item.kabupaten;
    ipknRadarChart.data.datasets[0].data = [
        item.norm.jml_alam,
        item.norm.jml_buatan_budaya,
        item.norm.jml_hotel,
        item.norm.jml_restoran,
        item.norm.kunjungan_2026,
        item.norm.yoy_growth
    ];
    ipknRadarChart.update();
}

// ==============================================================
// SCATTER PLOT: TIPOLOGI KLASSEN
// ==============================================================
const klassenRaw = <?= json_encode($klassenData) ?>;
const scatterCtx = document.getElementById('chartKlassenScatter').getContext('2d');

const scatterDatasets = [
    {
        label: 'Kuadran I (Maju & Cepat)',
        data: klassenRaw.items.filter(d => d.kuadran === 1).map(d => ({ x: d.yoy_growth, y: d.kunjungan_2026, kab: d.kabupaten, kuadran: d.kuadran_title })),
        backgroundColor: '#10b981',
        borderColor: '#059669',
        pointRadius: 8,
        pointHoverRadius: 11
    },
    {
        label: 'Kuadran II (Potensial)',
        data: klassenRaw.items.filter(d => d.kuadran === 2).map(d => ({ x: d.yoy_growth, y: d.kunjungan_2026, kab: d.kabupaten, kuadran: d.kuadran_title })),
        backgroundColor: '#0ea5e9',
        borderColor: '#0284c7',
        pointRadius: 8,
        pointHoverRadius: 11
    },
    {
        label: 'Kuadran III (Mapan)',
        data: klassenRaw.items.filter(d => d.kuadran === 3).map(d => ({ x: d.yoy_growth, y: d.kunjungan_2026, kab: d.kabupaten, kuadran: d.kuadran_title })),
        backgroundColor: '#f59e0b',
        borderColor: '#d97706',
        pointRadius: 8,
        pointHoverRadius: 11
    },
    {
        label: 'Kuadran IV (Rintisan)',
        data: klassenRaw.items.filter(d => d.kuadran === 4).map(d => ({ x: d.yoy_growth, y: d.kunjungan_2026, kab: d.kabupaten, kuadran: d.kuadran_title })),
        backgroundColor: '#f43f5e',
        borderColor: '#e11d48',
        pointRadius: 8,
        pointHoverRadius: 11
    }
];

new Chart(scatterCtx, {
    type: 'scatter',
    data: {
        datasets: scatterDatasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, font: { size: 11 } }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const raw = context.raw;
                        return [
                            `🏛️ ${raw.kab}`,
                            `🏷️ ${raw.kuadran}`,
                            `📈 Pertumbuhan: +${raw.x}% YoY`,
                            `👥 Kunjungan: ${Number(raw.y).toLocaleString()} Wisnus`
                        ];
                    }
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: `Laju Pertumbuhan YoY (%) — Rata-Rata Provinsi: +${klassenRaw.g_provinsi}%`,
                    font: { size: 11, weight: 'bold' }
                },
                grid: { color: '#f1f5f9' }
            },
            y: {
                title: {
                    display: true,
                    text: `Volume Kunjungan (Orang) — Rata-Rata Provinsi: ${Number(klassenRaw.avg_kunjungan_prov).toLocaleString()}`,
                    font: { size: 11, weight: 'bold' }
                },
                grid: { color: '#f1f5f9' },
                beginAtZero: false
            }
        }
    }
});



// ==============================================================
// SCATTER PLOT: GLOBAL MORAN'S I
// ==============================================================
const moranDataRaw = <?= json_encode($moranData) ?>;
const moranCtx = document.getElementById('chartMoranScatter').getContext('2d');

const moranDatasets = [
    {
        label: 'High-High (Hotspot)',
        data: moranDataRaw.scatter_sample.filter(d => d.kuadran.startsWith('High-High')).map(d => ({ x: d.x, y: d.y, nama: d.nama, kab: d.kabupaten, ulasan: d.ulasan, q: d.kuadran })),
        backgroundColor: '#10b981',
        borderColor: '#059669',
        pointRadius: 6,
        pointHoverRadius: 9
    },
    {
        label: 'Low-Low (Coldspot)',
        data: moranDataRaw.scatter_sample.filter(d => d.kuadran.startsWith('Low-Low')).map(d => ({ x: d.x, y: d.y, nama: d.nama, kab: d.kabupaten, ulasan: d.ulasan, q: d.kuadran })),
        backgroundColor: '#3b82f6',
        borderColor: '#2563eb',
        pointRadius: 6,
        pointHoverRadius: 9
    },
    {
        label: 'High-Low (Outlier)',
        data: moranDataRaw.scatter_sample.filter(d => d.kuadran.startsWith('High-Low')).map(d => ({ x: d.x, y: d.y, nama: d.nama, kab: d.kabupaten, ulasan: d.ulasan, q: d.kuadran })),
        backgroundColor: '#f59e0b',
        borderColor: '#d97706',
        pointRadius: 6,
        pointHoverRadius: 9
    },
    {
        label: 'Low-High (Outlier)',
        data: moranDataRaw.scatter_sample.filter(d => d.kuadran.startsWith('Low-High')).map(d => ({ x: d.x, y: d.y, nama: d.nama, kab: d.kabupaten, ulasan: d.ulasan, q: d.kuadran })),
        backgroundColor: '#f43f5e',
        borderColor: '#e11d48',
        pointRadius: 6,
        pointHoverRadius: 9
    }
];

new Chart(moranCtx, {
    type: 'scatter',
    data: { datasets: moranDatasets },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const raw = context.raw;
                        return [
                            `📍 ${raw.nama} (${raw.kab})`,
                            `🏷️ ${raw.q}`,
                            `📊 Z-Score Nilai (X): ${raw.x}`,
                            `🌐 Spatial Lag (Y): ${raw.y}`,
                            `💬 ${Number(raw.ulasan).toLocaleString()} Ulasan`
                        ];
                    }
                }
            }
        },
        scales: {
            x: {
                title: { display: true, text: 'Z-Score Nilai Atribut Popularitas Ulasan (Zi)', font: { size: 11, weight: 'bold' } },
                grid: { color: '#f1f5f9' }
            },
            y: {
                title: { display: true, text: 'Spatial Lag Rata-Rata Tetangga (W·Zi)', font: { size: 11, weight: 'bold' } },
                grid: { color: '#f1f5f9' }
            }
        }
    }
});

// ==============================================================
// CHART: TIME SERIES FORECASTING BPS
// ==============================================================
const forecastRaw = <?= json_encode($forecastData) ?>;
const forecastCtx = document.getElementById('chartForecasting').getContext('2d');

const allLabels = [...forecastRaw.historical.labels, ...forecastRaw.forecast.labels];

// Dataset Aktual (hanya sampai April 2026)
const actualData = [...forecastRaw.historical.values];
for (let i = 0; i < forecastRaw.forecast.labels.length; i++) {
    actualData.push(null);
}

// Dataset Model Fitting (Jan 2025 - Apr 2026)
const fittedData = [...forecastRaw.fitted];
for (let i = 0; i < forecastRaw.forecast.labels.length; i++) {
    fittedData.push(null);
}

// Dataset Forecast (mulai dari April 2026 sambung ke Mei-Des 2026)
const forecastPoints = Array(forecastRaw.historical.labels.length - 1).fill(null);
forecastPoints.push(forecastRaw.historical.values[forecastRaw.historical.values.length - 1]);
forecastRaw.forecast.values.forEach(v => forecastPoints.push(v));

// Dataset CI Upper & Lower
const ciUpperPoints = Array(forecastRaw.historical.labels.length - 1).fill(null);
ciUpperPoints.push(forecastRaw.historical.values[forecastRaw.historical.values.length - 1]);
forecastRaw.forecast.ci_upper.forEach(v => ciUpperPoints.push(v));

const ciLowerPoints = Array(forecastRaw.historical.labels.length - 1).fill(null);
ciLowerPoints.push(forecastRaw.historical.values[forecastRaw.historical.values.length - 1]);
forecastRaw.forecast.ci_lower.forEach(v => ciLowerPoints.push(v));

new Chart(forecastCtx, {
    type: 'line',
    data: {
        labels: allLabels,
        datasets: [
            {
                label: 'Data Historis Aktual BPS',
                data: actualData,
                borderColor: '#1e293b',
                backgroundColor: '#1e293b',
                borderWidth: 3,
                pointRadius: 4,
                tension: 0.2
            },
            {
                label: 'Nilai Fitting Model',
                data: fittedData,
                borderColor: '#0284c7',
                backgroundColor: 'rgba(2, 132, 199, 0.1)',
                borderWidth: 2,
                borderDash: [4, 4],
                pointRadius: 2,
                tension: 0.2
            },
            {
                label: 'Proyeksi Masa Depan (Holt)',
                data: forecastPoints,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderWidth: 3,
                borderDash: [6, 4],
                pointRadius: 4,
                pointBackgroundColor: '#6366f1',
                tension: 0.2
            },
            {
                label: 'Batas Atas CI 95%',
                data: ciUpperPoints,
                borderColor: 'rgba(99, 102, 241, 0.3)',
                borderWidth: 1,
                borderDash: [3, 3],
                pointRadius: 0,
                fill: '+1',
                backgroundColor: 'rgba(99, 102, 241, 0.08)'
            },
            {
                label: 'Batas Bawah CI 95%',
                data: ciLowerPoints,
                borderColor: 'rgba(99, 102, 241, 0.3)',
                borderWidth: 1,
                borderDash: [3, 3],
                pointRadius: 0,
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 12, font: { size: 11 } }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.parsed.y ? Number(context.parsed.y).toLocaleString() + ' Wisnus' : '-'}`;
                    }
                }
            }
        },
        scales: {
            x: { grid: { color: '#f8fafc' }, ticks: { font: { size: 10 } } },
            y: {
                title: { display: true, text: 'Jumlah Kunjungan Wisatawan (Orang/Bulan)', font: { size: 11, weight: 'bold' } },
                grid: { color: '#f1f5f9' },
                beginAtZero: false
            }
        }
    }
});
</script>
