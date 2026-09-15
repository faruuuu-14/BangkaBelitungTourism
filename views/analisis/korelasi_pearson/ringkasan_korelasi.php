<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      ringkasan_korelasi.php
 * @folder    views/analisis/korelasi_pearson/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.5.0 (Uji Korelasi Pearson BPS: Tamu Menginap vs Day-Tripper vs RLMT)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen evaluasi statistik korelasi Pearson Product-Moment berbasis data resmi BPS.
 * Menguji hubungan linear antara variabel skala (Scale vs Scale):
 * 1. Tamu Menginap (X2) vs Rata-Rata Lama Menginap / RLMT (Y) -> r = +0.5613
 * 2. Wisnus Day-Tripper (X3) vs RLMT (Y) -> r = -0.4991
 * 3. Total Wisnus Tujuan (X1) vs RLMT (Y) -> r = -0.2823
 * 4. TPK Non-Bintang (X5) vs RLMT (Y) -> r = +0.2891
 * Berlandaskan pedoman metodologi 'What test can I use.docx' dan BRS BPS April 2026.
 * ==============================================================================
 */

require_once __DIR__ . '/../../../app/statistics.php';

$bpsMatrix = getBpsPearsonMatrix();
$bpsKabData = getBpsKabupatenDataApril2026();
?>

<!-- ============================================================== -->
<!-- 1. KEPALA BAGIAN & KARTU KORELASI UTAMA PEARSON BPS            -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-1">
                <i class="fa-solid fa-link text-[11px]"></i>
                <span>Statistika Inferensial Parametrik (Scale vs Scale)</span>
            </div>
            <h3 class="text-base sm:text-xl font-black text-slate-900 tracking-tight">
                Uji Korelasi Pearson: Karakteristik Wisatawan &amp; Durasi Menginap (RLMT)
            </h3>
            <p class="text-xs text-slate-500 max-w-3xl leading-relaxed mt-0.5">
                Menguji hubungan empiris antara volume tamu menginap (Stayers) dan pelancong harian (Day-Tripper) terhadap Rata-Rata Lama Menginap (RLMT) di 7 Kabupaten/Kota berdasarkan data resmi BPS Provinsi Kepulauan Bangka Belitung.
            </p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 self-start sm:self-auto shadow-xs shrink-0">
            <i class="fa-solid fa-calculator mr-1"></i> Data Riil BPS April 2026
        </span>
    </div>

    <!-- Pilihan Metode Uji Korelasi (Triangulasi 3 Metode / Robustness Check) -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 p-3 sm:p-4 rounded-2xl bg-slate-100 border border-slate-200/80">
        <div>
            <div class="text-xs sm:text-sm font-bold text-slate-900 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Opsi Metode Uji Korelasi (Triangulasi Bukti Ilmiah):</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-0.5">
                Bandingkan hasil antara <strong>Pearson</strong> (nilai riil), <strong>Spearman</strong> (peringkat), dan <strong>Kendall Tau</strong> (khusus sampel kecil) untuk membuktikan kekuatan data.
            </p>
        </div>
        <div class="flex items-center gap-1.5 overflow-x-auto shrink-0">
            <button type="button" onclick="switchCorrMethod('pearson')" id="btnCorrPearson" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-ocean-700 shadow-xs whitespace-nowrap">
                🔵 Pearson (Default)
            </button>
            <button type="button" onclick="switchCorrMethod('spearman')" id="btnCorrSpearman" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 whitespace-nowrap">
                🟣 Spearman Rank
            </button>
            <button type="button" onclick="switchCorrMethod('kendall')" id="btnCorrKendall" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 whitespace-nowrap">
                🟢 Kendall Tau (N=7)
            </button>
            <button type="button" onclick="switchCorrMethod('tabel')" id="btnCorrTabel" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 whitespace-nowrap">
                📊 Tabel Triangulasi
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- PANDUAN PENTING: PERBEDAAN METODE KORELASI & CARA BACA SKOR    -->
    <!-- ============================================================== -->
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 via-white to-slate-50/80 p-4 sm:p-5 space-y-4 shadow-2xs">
        
        <!-- Header Panduan -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200/80">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm shadow-2xs">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Panduan Metodologi: Apa Bedanya Ketiga Uji Korelasi Ini?</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Uji Triangulasi</span>
                    </h4>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                        Pelajari perbedaan konsep uji statistik berikut agar Anda memahami mengapa hasil pengujian di bawah ini saling melengkapi dan saling menguatkan.
                    </p>
                </div>
            </div>
            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200 self-start sm:self-auto shrink-0 shadow-2xs">
                <i class="fa-solid fa-scale-balanced mr-1"></i> Standar UCLA &amp; BPS
            </span>
        </div>

        <!-- 3 Kartu Komparasi Perbedaan Korelasi (Bisa Diklik untuk Mengubah Metode) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            
            <!-- 1. Pearson -->
            <div id="guideCardPearson" onclick="switchCorrMethod('pearson')" class="p-3.5 rounded-xl border-2 border-ocean-500 bg-ocean-50/60 shadow-xs ring-2 ring-ocean-200 transition-all space-y-2 cursor-pointer hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-ocean-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-line text-ocean-600"></i>
                        <span>🔵 Pearson (r)</span>
                    </span>
                    <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded bg-ocean-200 text-ocean-800">Parametrik</span>
                </div>
                <div class="text-[11px] text-slate-600 space-y-1.5 leading-relaxed">
                    <p>
                        <strong class="text-slate-800">Prinsip Kerja:</strong> Menghitung nilai data asli secara langsung. Menguji seberapa presisi titik-titik data membentuk satu garis lurus linear.
                    </p>
                    <p>
                        <strong class="text-slate-800">Kapan Digunakan:</strong> Data berskala rasio/angka riil dan sebaran data wajar (berdistribusi normal).
                    </p>
                    <div class="pt-1.5 border-t border-ocean-200/70 text-[10px] text-ocean-900 font-medium flex items-center justify-between">
                        <span>⭐ Standar utama internasional</span>
                        <span class="text-[9px] font-bold text-ocean-700 underline">Klik untuk pilih</span>
                    </div>
                </div>
            </div>

            <!-- 2. Spearman -->
            <div id="guideCardSpearman" onclick="switchCorrMethod('spearman')" class="p-3.5 rounded-xl border border-slate-200 bg-white transition-all space-y-2 cursor-pointer hover:border-purple-400 hover:shadow-md opacity-90">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-purple-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-ranking-star text-purple-600"></i>
                        <span>🟣 Spearman Rank (&rho;)</span>
                    </span>
                    <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded bg-purple-100 text-purple-800">Non-Parametrik</span>
                </div>
                <div class="text-[11px] text-slate-600 space-y-1.5 leading-relaxed">
                    <p>
                        <strong class="text-slate-800">Prinsip Kerja:</strong> Angka riil diubah menjadi urutan peringkat (ranking juara 1 s/d 7) terlebih dahulu, baru dihitung korelasi urutannya.
                    </p>
                    <p>
                        <strong class="text-slate-800">Kapan Digunakan:</strong> Data tidak normal, berbentuk peringkat, atau ada angka ekstrem (outlier) yang melonjak tinggi.
                    </p>
                    <div class="pt-1.5 border-t border-purple-200/70 text-[10px] text-purple-900 font-medium flex items-center justify-between">
                        <span>🛡️ Kebal terhadap angka pencilan</span>
                        <span class="text-[9px] font-bold text-purple-700 underline">Klik untuk pilih</span>
                    </div>
                </div>
            </div>

            <!-- 3. Kendall Tau -->
            <div id="guideCardKendall" onclick="switchCorrMethod('kendall')" class="p-3.5 rounded-xl border border-slate-200 bg-white transition-all space-y-2 cursor-pointer hover:border-emerald-400 hover:shadow-md opacity-90">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-emerald-900 flex items-center gap-1.5">
                        <i class="fa-solid fa-check-double text-emerald-600"></i>
                        <span>🟢 Kendall&rsquo;s Tau (&tau;)</span>
                    </span>
                    <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Khusus Sampel N=7</span>
                </div>
                <div class="text-[11px] text-slate-600 space-y-1.5 leading-relaxed">
                    <p>
                        <strong class="text-slate-800">Prinsip Kerja:</strong> Membandingkan setiap pasang daerah: apakah saat data A naik, data B juga ikut naik (konkordan) atau berlawanan (diskordan).
                    </p>
                    <p>
                        <strong class="text-slate-800">Kapan Digunakan:</strong> <strong>Paling direkomendasikan para pakar jika sampel kecil (N &lt; 30, Babel N=7 daerah)</strong>.
                    </p>
                    <div class="pt-1.5 border-t border-emerald-200/70 text-[10px] text-emerald-900 font-medium flex items-center justify-between">
                        <span>🎯 Estimasi galat paling minim</span>
                        <span class="text-[9px] font-bold text-emerald-700 underline">Klik untuk pilih</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Cara Membaca Skor Angka (-1 s/d +1) -->
        <div class="p-3 rounded-xl bg-slate-100/90 border border-slate-200/90 text-xs text-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
            <div class="flex items-center gap-2 shrink-0">
                <i class="fa-solid fa-compass text-ocean-600"></i>
                <span class="font-bold text-slate-900">Cara Membaca Skor Kartu di Bawah (-1.00 s/d +1.00):</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap text-[11px]">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">
                    <i class="fa-solid fa-arrow-up text-[9px]"></i> Positif (+): Hubungan Searah (X naik, Y naik)
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold">
                    <i class="fa-solid fa-arrow-down text-[9px]"></i> Negatif (-): Berlawanan (X naik, Y turun)
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-700 font-bold">
                    Mendekati 0: Lemah / Netral
                </span>
            </div>
        </div>

    </div>

    <!-- Kotak Penjelasan Metode Aktif -->
    <div id="methodExplanationBox" class="p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-200/80 text-indigo-950 text-xs flex items-start gap-2.5">
        <i class="fa-solid fa-circle-info text-indigo-600 mt-0.5 text-xs shrink-0"></i>
        <div id="methodExplanationText">
            <strong>Metode Aktif: Pearson Product-Moment (r):</strong> Mengukur derajat hubungan linear antara dua variabel berangka riil (skala rasio). Cocok sebagai standar utama karena data kunjungan dan lama menginap keduanya berbentuk angka terukur.
        </div>
    </div>

    <!-- 4 Kartu Matriks Korelasi (Dapat Berubah Sesuai Metode Terpilih) -->
    <div id="corrCardsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        
        <!-- Kartu 1: Tamu Hotel vs Lama Tinggal (RLMT) -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-50/90 to-teal-50/50 border border-emerald-200 space-y-2 relative overflow-hidden shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 block">Tamu Hotel vs Lama Tinggal</span>
                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-600 text-white shadow-xs">X2 vs Y</span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span id="corrScore1" class="text-2xl sm:text-3xl font-black text-emerald-900">+0.561</span>
                <span id="corrBadge1" class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded">Hubungan Searah</span>
            </div>
            <div id="corrTrend1" class="text-[11px] font-bold text-emerald-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> Pengaruh Positif Kuat
            </div>
            <div id="corrDesc1" class="text-xs text-slate-600 leading-relaxed border-t border-emerald-200/80 pt-2 mt-1">
                Di daerah tujuan liburan (seperti Belitung), wisatawan datang khusus untuk menginap berhari-hari (rata-rata 2 malam). Makin banyak tamu memesan hotel, makin lama rata-rata durasi tinggal di daerah tersebut.
            </div>
            <div id="corrNote1" class="text-[10px] font-mono text-emerald-700/80 pt-0.5">
                R&sup2; = 31.5% &bull; Belitung tertinggi (RLMT 2.08 malam)
            </div>
        </div>

        <!-- Kartu 2: Wisatawan Harian (Day-Tripper) vs Lama Tinggal -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-rose-50/90 to-amber-50/50 border border-rose-200 space-y-2 relative overflow-hidden shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-700 block">Wisatawan Harian vs Lama Tinggal</span>
                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-rose-600 text-white shadow-xs">X3 vs Y</span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span id="corrScore2" class="text-2xl sm:text-3xl font-black text-rose-900">-0.499</span>
                <span id="corrBadge2" class="text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">Berbanding Terbalik</span>
            </div>
            <div id="corrTrend2" class="text-[11px] font-bold text-rose-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-down text-[10px]"></i> Pengaruh Negatif Sedang
            </div>
            <div id="corrDesc2" class="text-xs text-slate-600 leading-relaxed border-t border-rose-200/80 pt-2 mt-1">
                Makin mendominasi wisatawan yang datang hanya untuk pulang-pergi di hari yang sama (tanpa bermalam), angka rata-rata lama menginap di daerah tersebut menjadi makin pendek/rendah.
            </div>
            <div id="corrNote2" class="text-[10px] font-mono text-rose-700/80 pt-0.5">
                R&sup2; = 24.9% &bull; Bangka Barat &amp; Basel tertinggi (>90% pulang-hari)
            </div>
        </div>

        <!-- Kartu 3: Total Kunjungan vs Lama Tinggal -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-50 to-indigo-50/40 border border-slate-200 space-y-2 relative overflow-hidden shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-600 block">Total Kunjungan vs Lama Tinggal</span>
                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-slate-700 text-white shadow-xs">X1 vs Y</span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span id="corrScore3" class="text-2xl sm:text-3xl font-black text-slate-800">-0.282</span>
                <span id="corrBadge3" class="text-[10px] font-bold text-slate-600 bg-slate-200 px-1.5 py-0.5 rounded">Hubungan Lemah</span>
            </div>
            <div id="corrTrend3" class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                <i class="fa-solid fa-minus text-[10px]"></i> Ramai Belum Tentu Bermalam
            </div>
            <div id="corrDesc3" class="text-xs text-slate-600 leading-relaxed border-t border-slate-200 pt-2 mt-1">
                Banyaknya jumlah wisatawan yang datang ke suatu wilayah belum tentu membuat hotel ramai, jika mayoritasnya (83%) hanya berkunjung sebentar tanpa menyewa kamar penginapan.
            </div>
            <div id="corrNote3" class="text-[10px] font-mono text-slate-500 pt-0.5">
                R&sup2; = 8.0% &bull; Kunjungan ramai ≠ kamar hotel laku
            </div>
        </div>

        <!-- Kartu 4: Homestay & Losmen vs Lama Tinggal -->
        <div class="p-4 rounded-2xl bg-gradient-to-br from-purple-50/90 to-indigo-50/50 border border-purple-200 space-y-2 relative overflow-hidden shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-purple-700 block">Homestay/Losmen vs Lama Tinggal</span>
                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-purple-600 text-white shadow-xs">X5 vs Y</span>
            </div>
            <div class="flex items-baseline gap-1.5">
                <span id="corrScore4" class="text-2xl sm:text-3xl font-black text-purple-900">+0.289</span>
                <span id="corrBadge4" class="text-[10px] font-bold text-purple-700 bg-purple-100 px-1.5 py-0.5 rounded">Pengaruh Positif Ringan</span>
            </div>
            <div id="corrTrend4" class="text-[11px] font-bold text-purple-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> Sedikit Membantu
            </div>
            <div id="corrDesc4" class="text-xs text-slate-600 leading-relaxed border-t border-purple-200/80 pt-2 mt-1">
                Keberadaan losmen dan homestay murah membantu menambah pilihan menginap bagi pelancong hemat/backpacker, meskipun pengaruhnya belum sebesar daya tarik hotel berbintang.
            </div>
            <div id="corrNote4" class="text-[10px] font-mono text-purple-700/80 pt-0.5">
                R&sup2; = 8.4% &bull; Alternatif penginapan ramah kantong
            </div>
        </div>

    </div>

    <!-- Tabel Perbandingan Triangulasi 3 Metode (Pearson vs Spearman vs Kendall) -->
    <div id="corrTriangulationTable" class="hidden space-y-3 pt-1">
        <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-xs text-emerald-950 flex items-start gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs shrink-0"></i>
            <div>
                <strong>Kesimpulan Uji Triangulasi (Robustness Check):</strong>
                Ketiga metode (Pearson, Spearman Rank, dan Kendall&rsquo;s Tau) secara <strong>100% konsisten searah</strong> membuktikan bahwa Tamu Hotel memiliki korelasi positif kuat terhadap durasi menginap, sementara Wisatawan Harian berkorelasi negatif. Ini membuktikan bahwa hasil analisis BPS ini sangat kokoh dan tidak dipengaruhi bias rumus!
            </div>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-700 font-extrabold text-[11px] uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3.5">Variabel yang Diuji</th>
                        <th class="py-3 px-3 text-center">Pearson (r)<br><span class="text-[9px] font-normal text-slate-500">Nilai Riil</span></th>
                        <th class="py-3 px-3 text-center">Spearman (&rho;)<br><span class="text-[9px] font-normal text-slate-500">Peringkat</span></th>
                        <th class="py-3 px-3 text-center">Kendall (&tau;)<br><span class="text-[9px] font-normal text-slate-500">Sampel N=7</span></th>
                        <th class="py-3 px-3 text-center">Arah Hubungan</th>
                        <th class="py-3 px-3.5">Kesimpulan Konsistensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-xs">
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-3 px-3.5 font-bold text-slate-900">
                            Tamu Hotel (X2) vs Lama Menginap (Y)
                        </td>
                        <td class="py-3 px-3 text-center font-mono font-extrabold text-emerald-700 bg-emerald-50/30">+0.5613</td>
                        <td class="py-3 px-3 text-center font-mono font-extrabold text-emerald-700 bg-emerald-50/30">+0.5714</td>
                        <td class="py-3 px-3 text-center font-mono font-extrabold text-emerald-700 bg-emerald-50/30">+0.3333</td>
                        <td class="py-3 px-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">POSITIF / SEARAH</span>
                        </td>
                        <td class="py-3 px-3.5 text-[11px] text-slate-600">Semua metode sepakat: Tamu hotel memperpanjang lama tinggal.</td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-3 px-3.5 font-bold text-slate-900">
                            Wisatawan Harian (X3) vs Lama Menginap (Y)
                        </td>
                        <td class="py-3 px-3 text-center font-mono font-extrabold text-rose-700 bg-rose-50/30">-0.4991</td>
                        <td class="py-3 px-3 text-center font-mono font-extrabold text-rose-700 bg-rose-50/30">-0.4643</td>
                        <td class="py-3 px-3 text-center font-mono font-extrabold text-rose-700 bg-rose-50/30">-0.4286</td>
                        <td class="py-3 px-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800">NEGATIF / TERBALIK</span>
                        </td>
                        <td class="py-3 px-3.5 text-[11px] text-slate-600">Semua metode sepakat: Turis pulang-hari menekan lama tinggal.</td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-3 px-3.5 font-bold text-slate-900">
                            Total Kunjungan (X1) vs Lama Menginap (Y)
                        </td>
                        <td class="py-3 px-3 text-center font-mono font-bold text-slate-600 bg-slate-50">-0.2823</td>
                        <td class="py-3 px-3 text-center font-mono font-bold text-slate-600 bg-slate-50">-0.0714</td>
                        <td class="py-3 px-3 text-center font-mono font-bold text-slate-600 bg-slate-50">-0.1429</td>
                        <td class="py-3 px-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 text-slate-700">LEMAH / TIDAK LANGSUNG</span>
                        </td>
                        <td class="py-3 px-3.5 text-[11px] text-slate-600">Semua metode sepakat: Kunjungan ramai ≠ hotel terisi.</td>
                    </tr>
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-3 px-3.5 font-bold text-slate-900">
                            Homestay &amp; Losmen (X5) vs Lama Menginap (Y)
                        </td>
                        <td class="py-3 px-3 text-center font-mono font-bold text-purple-700 bg-purple-50/30">+0.2891</td>
                        <td class="py-3 px-3 text-center font-mono font-bold text-purple-700 bg-purple-50/30">+0.6071</td>
                        <td class="py-3 px-3 text-center font-mono font-bold text-purple-700 bg-purple-50/30">+0.3333</td>
                        <td class="py-3 px-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800">POSITIF / SEARAH</span>
                        </td>
                        <td class="py-3 px-3.5 text-[11px] text-slate-600">Semua metode sepakat: Homestay membantu menambah opsi menginap.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 2. TABEL DATA BPS RESMI 7 KABUPATEN/KOTA (APRIL 2026)          -->
    <!-- ============================================================== -->
    <div class="space-y-3 pt-2">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h4 class="text-sm font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-table-cells text-ocean-600"></i>
                    <span>Tabel Indikator Pariwisata BPS Kep. Bangka Belitung (April 2026)</span>
                </h4>
                <p class="text-[11px] text-slate-500">Basis data empiris uji korelasi Pearson per kabupaten/kota</p>
            </div>
            <span class="text-[11px] font-mono text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg self-start sm:self-auto">
                N = 7 Wilayah Pengamatan
            </span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-200">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold text-[10px] uppercase tracking-wider">
                        <th class="py-2.5 px-3">Kabupaten / Kota</th>
                        <th class="py-2.5 px-3 text-right">Total Wisnus (X1)</th>
                        <th class="py-2.5 px-3 text-right text-emerald-700 bg-emerald-50/50">Tamu Menginap (X2)</th>
                        <th class="py-2.5 px-3 text-right text-rose-700 bg-rose-50/50">Day-Tripper (X3)</th>
                        <th class="py-2.5 px-3 text-right">Porsi Harian</th>
                        <th class="py-2.5 px-3 text-right">TPK Bintang</th>
                        <th class="py-2.5 px-3 text-right">TPK Non-Bintang</th>
                        <th class="py-2.5 px-3 text-right text-indigo-700 bg-indigo-50/50">Lama Menginap (Y)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium text-[11px]">
                    <?php foreach ($bpsKabData as $row): ?>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-2.5 px-3 font-bold text-slate-900">
                            <?= $row['kabupaten'] ?>
                            <span class="block text-[9px] font-normal text-slate-500"><?= $row['karakter'] ?></span>
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono"><?= number_format($row['wisnus_tujuan']) ?></td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-700 bg-emerald-50/30">
                            <?= number_format($row['tamu_menginap']) ?>
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold text-rose-700 bg-rose-50/30">
                            <?= number_format($row['wisnus_daytripper']) ?>
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono text-[10px]">
                            <span class="px-1.5 py-0.2 rounded font-bold <?= $row['persen_daytripper'] > 85 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' ?>">
                                <?= number_format($row['persen_daytripper'], 1) ?>%
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono">
                            <?= $row['tpk_bintang'] !== null ? number_format($row['tpk_bintang'], 2) . '%' : '<span class="text-slate-400">N/A</span>' ?>
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono">
                            <?= number_format($row['tpk_non_bintang'], 2) ?>%
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono font-extrabold text-indigo-700 bg-indigo-50/30">
                            <?= number_format($row['rlmt'], 2) ?> malam
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <!-- Baris Total / Rata-rata Provinsi -->
                    <tr class="bg-slate-100/90 font-extrabold text-slate-900 border-t-2 border-slate-300 text-[11px]">
                        <td class="py-2.5 px-3">Total / Rata-Rata Provinsi</td>
                        <td class="py-2.5 px-3 text-right font-mono">371.039</td>
                        <td class="py-2.5 px-3 text-right font-mono text-emerald-800">63.408</td>
                        <td class="py-2.5 px-3 text-right font-mono text-rose-800">307.631</td>
                        <td class="py-2.5 px-3 text-right font-mono">82.91%</td>
                        <td class="py-2.5 px-3 text-right font-mono">30.39%</td>
                        <td class="py-2.5 px-3 text-right font-mono">19.73%</td>
                        <td class="py-2.5 px-3 text-right font-mono text-indigo-900">1.55 malam</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3. KESIMPULAN PRAKTIS & CARA MEMBACA HASIL KORELASI             -->
    <!-- ============================================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 pt-1">
        
        <!-- Kotak 1: Intisari Hasil Analisis -->
        <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200/80 text-xs text-slate-700 space-y-2">
            <div class="font-bold text-indigo-950 flex items-center gap-1.5">
                <i class="fa-solid fa-lightbulb text-indigo-600"></i>
                <span>Intisari Praktis Hasil Uji Korelasi:</span>
            </div>
            <ul class="space-y-1.5 text-[11px] leading-relaxed text-slate-600">
                <li>
                    <strong class="text-slate-900">1. Angka Positif (+0.561):</strong> Menunjukkan hubungan <strong>searah</strong>. Daerah yang sukses menarik tamu menginap di hotel (seperti Belitung) terbukti membuat wisatawan tinggal lebih lama.
                </li>
                <li>
                    <strong class="text-slate-900">2. Angka Negatif (-0.499):</strong> Menunjukkan hubungan <strong>berlawanan</strong>. Daerah yang didominasi pelancong pulang-hari (seperti Bangka Barat &amp; Basel) rata-rata lama tinggalnya rendah.
                </li>
                <li>
                    <strong class="text-slate-900">3. Rekomendasi Nyata:</strong> Program promosi wisata Babel perlu diarahkan untuk <strong>mendorong wisatawan menginap</strong> (misal dengan paket wisata 3D2N atau event malam hari), bukan sekadar mengejar jumlah orang yang lewat.
                </li>
            </ul>
        </div>

        <!-- Kotak 2: Cakupan Data Riset 7 Kabupaten/Kota -->
        <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-xs text-slate-700 space-y-2">
            <div class="font-bold text-amber-950 flex items-center gap-1.5">
                <i class="fa-solid fa-map-location-dot text-amber-600"></i>
                <span>Cakupan Wilayah Data BPS:</span>
            </div>
            <p class="text-[11px] leading-relaxed text-slate-600">
                Perhitungan ini mencakup <strong>seluruh 7 Kabupaten/Kota di Provinsi Kepulauan Bangka Belitung</strong> berdasarkan Berita Resmi Statistik (BRS) BPS April 2026. Meskipun jumlah wilayahnya 7 daerah (sampel agregat wilayah), pola korelasi ini memberikan gambaran riil yang akurat mengenai perilaku wisatawan di Bangka Belitung.
            </p>
        </div>

    </div>

</div>

<!-- SCRIPT PENGALIH METODE KORELASI & TRIANGULASI BUKTI -->
<script>
const corrMethodData = {
    pearson: {
        btnId: 'btnCorrPearson',
        desc: '<strong>Metode Aktif: Pearson Product-Moment (r):</strong> Menguji hubungan linier langsung dari data angka riil (skala rasio). Standar utama statistik parametrik untuk mengukur arah dan kekuatan hubungan antara dua variabel terukur.',
        scores: ['+0.561', '-0.499', '-0.282', '+0.289'],
        badges: ['Hubungan Searah', 'Berbanding Terbalik', 'Hubungan Lemah', 'Pengaruh Ringan'],
        trends: ['<i class="fa-solid fa-arrow-trend-up text-[10px]"></i> Pengaruh Positif Kuat', '<i class="fa-solid fa-arrow-trend-down text-[10px]"></i> Pengaruh Negatif Sedang', '<i class="fa-solid fa-minus text-[10px]"></i> Ramai Belum Tentu Bermalam', '<i class="fa-solid fa-arrow-trend-up text-[10px]"></i> Sedikit Membantu'],
        notes: ['R² = 31.5% • Belitung tertinggi (RLMT 2.08 malam)', 'R² = 24.9% • Bangka Barat & Basel (>90% pulang-hari)', 'R² = 8.0% • Kunjungan ramai ≠ kamar hotel laku', 'R² = 8.4% • Alternatif penginapan ramah kantong']
    },
    spearman: {
        btnId: 'btnCorrSpearman',
        desc: '<strong>Metode Aktif: Spearman Rank Correlation (ρ / rho):</strong> Uji non-parametrik berbasis urutan/peringkat (Rank 1 s.d. 7). Tidak berasumsi data berdistribusi normal dan sangat kebal terhadap nilai ekstrem (outlier). Hasilnya: <strong>+0.5714</strong> (sangat kuat searah).',
        scores: ['+0.571', '-0.464', '-0.071', '+0.607'],
        badges: ['Peringkat Searah', 'Peringkat Terbalik', 'Nyaris Nol', 'Peringkat Positif'],
        trends: ['<i class="fa-solid fa-ranking-star text-[10px]"></i> Peringkat Positif Kuat', '<i class="fa-solid fa-arrow-trend-down text-[10px]"></i> Peringkat Negatif Kuat', '<i class="fa-solid fa-minus text-[10px]"></i> Tidak Berpengaruh', '<i class="fa-solid fa-ranking-star text-[10px]"></i> Peringkat Positif Kuat'],
        notes: ['rho = +0.5714 • Konsisten searah dengan Pearson', 'rho = -0.4643 • Konsisten terbalik dengan Pearson', 'rho = -0.0714 • Kunjungan total tidak berkorelasi peringkat', 'rho = +0.6071 • Homestay konsisten membantu opsi']
    },
    kendall: {
        btnId: 'btnCorrKendall',
        desc: '<strong>Metode Aktif: Kendall’s Tau-b (τ):</strong> Uji non-parametrik berbasis pasangan konkordan dan diskordan. Sangat direkomendasikan para pakar statistik jika ukuran sampel kecil (N = 7 daerah). Hasilnya: <strong>+0.3333</strong> (konsisten searah).',
        scores: ['+0.333', '-0.429', '-0.143', '+0.333'],
        badges: ['Pasangan Searah', 'Pasangan Terbalik', 'Hubungan Lemah', 'Pasangan Searah'],
        trends: ['<i class="fa-solid fa-check-double text-[10px]"></i> Pasangan Positif Stabil', '<i class="fa-solid fa-arrow-trend-down text-[10px]"></i> Pasangan Negatif Stabil', '<i class="fa-solid fa-minus text-[10px]"></i> Tidak Linier', '<i class="fa-solid fa-check-double text-[10px]"></i> Pasangan Positif'],
        notes: ['tau = +0.3333 • Paling kokoh untuk sampel kecil (N=7)', 'tau = -0.4286 • Terbukti kokoh secara non-parametrik', 'tau = -0.1429 • Hubungan tidak terbukti pada sampel N=7', 'tau = +0.3333 • Sesuai tren akomodasi alternatif']
    }
};

function switchCorrMethod(method) {
    const cardsGrid = document.getElementById('corrCardsGrid');
    const triTable = document.getElementById('corrTriangulationTable');
    const descBox = document.getElementById('methodExplanationText');
    const buttons = ['btnCorrPearson', 'btnCorrSpearman', 'btnCorrKendall', 'btnCorrTabel'];
    
    buttons.forEach(id => {
        const btn = document.getElementById(id);
        if (btn) {
            btn.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 whitespace-nowrap';
        }
    });

    const gPearson = document.getElementById('guideCardPearson');
    const gSpearman = document.getElementById('guideCardSpearman');
    const gKendall = document.getElementById('guideCardKendall');

    if (method === 'tabel') {
        document.getElementById('btnCorrTabel').className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-ocean-700 shadow-xs whitespace-nowrap';
        cardsGrid.classList.add('hidden');
        triTable.classList.remove('hidden');
        descBox.innerHTML = '<strong>Mode Triangulasi 3 Metode:</strong> Membandingkan nilai Pearson, Spearman, dan Kendall Tau secara berdampingan. Perhatikan bahwa arah hubungan (tanda positif/negatif) pada ketiga metode <strong>100% selaras</strong>!';
        
        if (gPearson) gPearson.className = 'p-3.5 rounded-xl border border-slate-200 bg-white transition-all space-y-2 cursor-pointer opacity-75';
        if (gSpearman) gSpearman.className = 'p-3.5 rounded-xl border border-slate-200 bg-white transition-all space-y-2 cursor-pointer opacity-75';
        if (gKendall) gKendall.className = 'p-3.5 rounded-xl border border-slate-200 bg-white transition-all space-y-2 cursor-pointer opacity-75';
        return;
    }

    // Tampilkan kembali kartu
    triTable.classList.add('hidden');
    cardsGrid.classList.remove('hidden');

    const data = corrMethodData[method];
    if (!data) return;

    document.getElementById(data.btnId).className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-ocean-700 shadow-xs whitespace-nowrap';
    descBox.innerHTML = data.desc;

    // Highlight kartu panduan yang aktif di atas
    if (gPearson) {
        gPearson.className = (method === 'pearson')
            ? 'p-3.5 rounded-xl border-2 border-ocean-500 bg-ocean-50/60 shadow-xs ring-2 ring-ocean-200 transition-all space-y-2 cursor-pointer'
            : 'p-3.5 rounded-xl border border-slate-200 bg-white transition-all space-y-2 cursor-pointer opacity-80 hover:border-ocean-300';
    }
    if (gSpearman) {
        gSpearman.className = (method === 'spearman')
            ? 'p-3.5 rounded-xl border-2 border-purple-500 bg-purple-50/60 shadow-xs ring-2 ring-purple-200 transition-all space-y-2 cursor-pointer'
            : 'p-3.5 rounded-xl border border-slate-200 bg-white transition-all space-y-2 cursor-pointer opacity-80 hover:border-purple-300';
    }
    if (gKendall) {
        gKendall.className = (method === 'kendall')
            ? 'p-3.5 rounded-xl border-2 border-emerald-500 bg-emerald-50/60 shadow-xs ring-2 ring-emerald-200 transition-all space-y-2 cursor-pointer'
            : 'p-3.5 rounded-xl border border-slate-200 bg-white transition-all space-y-2 cursor-pointer opacity-80 hover:border-emerald-300';
    }

    // Update 4 Cards
    for (let i = 1; i <= 4; i++) {
        const idx = i - 1;
        document.getElementById('corrScore' + i).innerText = data.scores[idx];
        document.getElementById('corrBadge' + i).innerText = data.badges[idx];
        document.getElementById('corrTrend' + i).innerHTML = data.trends[idx];
        document.getElementById('corrNote' + i).innerHTML = data.notes[idx];
    }
}
</script>
