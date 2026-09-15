<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/analisis/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Pengendali Utama Modul Analisis Statistik & Beban BPS)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Pengontrol utama (Master View Controller) untuk seluruh modul Pusat Analisis
 * Statistik dan Ekonometrika Pariwisata Bangka Belitung. Mengorganisasikan
 * modul-modul sub-fitur ke dalam navigasi tab modern (Segmented Pills Navigation)
 * untuk mengeliminasi beban informasi berlebih (Zero Information Overload):
 * 1. Tab 1: Ringkasan Eksekutif & KPI Makro BPS
 * 2. Tab 2: Uji Korelasi Pearson & Pembuktian Rumus
 * 3. Tab 3: Evaluasi Ketimpangan Spasial & Rasio Kebutuhan
 * 4. Tab 4: Matriks 4 Kuadran Prioritas Investasi Daerah
 * 5. Tab 5: Proyeksi Tren Deret Waktu & Simulasi Interaktif
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (Master Router) saat ?page=analisis
 * - Membaca pustaka data: app/statistics.php (seluruh fungsi kalkulasi statistik)
 * - Memuat sub-komponen:
 *   - ringkasan/kartu_kpi.php, ringkasan/pilar_visual.php
 *   - korelasi_pearson/ringkasan_korelasi.php, grafik_sebaran.php, penjelasan_rumus.php
 *   - ketimpangan_spasial/tabel_rasio_beban.php, arahan_kebijakan.php
 *   - matriks_kuadran/grafik_kuadran.php, panduan_kuadran.php
 *   - proyeksi_tren/grafik_proyeksi.php
 *   - simulasi_investasi/alat_simulasi.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menggantikan halaman panjang 137 KB menjadi arsitektur modular terorganisir,
 * bersih, mudah dirawat (*maintainable*), dan ramah terhadap pengguna serta penguji.
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/statistics.php';

// --------------------------------------------------------------------------
// 1. DATA PERTUMBUHAN YEAR-OVER-YEAR (YoY) BPS
// --------------------------------------------------------------------------
$yoyData = getBpsYoYGrowth();
$prov = $yoyData['provinsi'] ?? ['growth' => '9.95', 'total_2026' => 157294];
$daerahYoY = $yoyData['daerah'] ?? [];

// --------------------------------------------------------------------------
// 2. DATA KORELASI PEARSON & DERET WAKTU
// --------------------------------------------------------------------------
$macroCorr = getMacroCorrelation();
$corr = getCorrelationMatrix();

// --------------------------------------------------------------------------
// 3. DATA EVALUASI KETIMPANGAN AMENITAS PER KECAMATAN
// --------------------------------------------------------------------------
$gapData = getGapAnalysis();
$kritisCount = count(array_filter($gapData, fn($i) => str_contains($i['status'], 'Defisit')));
$waspadaCount = count(array_filter($gapData, fn($i) => str_contains($i['status'], 'Potensi') || str_contains($i['status'], 'Longgar') || str_contains($i['status'], 'Perlu')));
$idealCount = count(array_filter($gapData, fn($i) => str_contains($i['status'], 'Seimbang')));
?>

<!-- ============================================================== -->
<!-- STRUKTUR TATA LETAK UTAMA MODUL ANALISIS (STANDAR KONSISTENSI) -->
<!-- ============================================================== -->
<main class="flex-grow w-full max-w-[1720px] mx-auto px-3 sm:px-6 lg:px-10 py-3 sm:py-8 pb-20 lg:pb-8 min-w-0 overflow-hidden space-y-3.5 sm:space-y-6">

    <!-- KOP SURAT RESMI PEMERINTAH PROVINSI (HANYA MUNCUL SAAT CETAK / PDF) -->
    <div class="hidden print:block border-b-2 border-slate-900 pb-3 mb-6">
        <div class="flex items-center justify-between gap-4">
            <div class="w-14 h-14 shrink-0 flex items-center justify-center">
                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xs border border-slate-700">
                    BABEL
                </div>
            </div>
            <div class="text-center flex-1 space-y-0.5">
                <h2 class="text-sm font-black uppercase tracking-wider text-slate-900 leading-tight">Pemerintah Provinsi Kepulauan Bangka Belitung</h2>
                <h3 class="text-xs font-extrabold uppercase text-slate-800 leading-tight">Dinas Pariwisata, Kebudayaan, dan Kepemudaan Olahraga</h3>
                <p class="text-[9px] text-slate-600">Komplek Perkantoran Terpadu Pemerintah Provinsi Kepulauan Bangka Belitung, Pangkalpinang</p>
                <p class="text-[9px] font-mono text-slate-500 font-bold">Ringkasan Eksekutif Spatial Decision Support System (SDSS) Pariwisata Babel - <?= date('d F Y') ?></p>
            </div>
            <div class="w-14 h-14 shrink-0 text-right text-[8px] text-slate-500 font-mono flex flex-col justify-center">
                <span class="font-bold border border-slate-400 px-1 py-0.5 rounded">DOKUMEN<br>EKSEKUTIF</span>
            </div>
        </div>
    </div>

    <!-- Header Halaman Analisis -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2.5 sm:gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-bold bg-ocean-50 text-ocean-700 border border-ocean-200/80 mb-1 sm:mb-2">
                <i class="fa-solid fa-landmark-flag text-[10px] sm:text-[11px]"></i>
                <span>Standar Analisis Kemenparekraf, Bappenas &amp; BPS RI</span>
            </div>
            <h1 class="text-lg sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                Pusat Intelijen &amp; Analisis Statistik Pariwisata
            </h1>
            <p class="text-slate-500 text-[11px] sm:text-sm mt-0.5 sm:mt-1 max-w-3xl leading-relaxed line-clamp-2 sm:line-clamp-none">
                Integrasi indikator pertumbuhan Year-over-Year (YoY), matriks korelasi Pearson, evaluasi ketimpangan amenitas spasial, dan peramalan tren kunjungan resmi BPS.
            </p>
        </div>

        <!-- Tombol Aksi Header (Hanya di Desktop/Tablet) -->
        <div class="hidden sm:flex items-center gap-2 self-start md:self-auto">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-xs transition-colors flex items-center gap-2" title="Cetak Ringkasan Eksekutif Resmi (A4 / PDF)">
                <i class="fa-solid fa-print text-ocean-400"></i>
                <span>Cetak Ringkasan Eksekutif</span>
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- BILAH NAVIGASI TAB SEGMENTASI BERBAHASA INDONESIA              -->
    <!-- Horizontal Scrollable di Mobile, Grid Proporsional di Desktop -->
    <!-- ============================================================== -->
    <div class="bg-white p-1 sm:p-1.5 rounded-2xl border border-slate-200/80 shadow-2xs flex overflow-x-auto no-scrollbar sm:grid sm:grid-cols-3 lg:grid-cols-5 gap-1 sm:gap-1.5 w-full">
        
        <button onclick="switchTab('tab-ringkasan')" id="btn-tab-ringkasan" class="tab-btn flex-shrink-0 sm:flex-shrink flex items-center justify-center gap-1.5 sm:gap-2 py-2 sm:py-2.5 px-3 rounded-xl text-xs sm:text-[13px] font-bold transition-all text-center bg-ocean-600 text-white shadow-xs whitespace-nowrap">
            <i class="fa-solid fa-chart-pie shrink-0 text-xs sm:text-sm"></i>
            <span>Ringkasan BPS &amp; Makro</span>
        </button>

        <button onclick="switchTab('tab-korelasi')" id="btn-tab-korelasi" class="tab-btn flex-shrink-0 sm:flex-shrink flex items-center justify-center gap-1.5 sm:gap-2 py-2 sm:py-2.5 px-3 rounded-xl text-xs sm:text-[13px] font-bold transition-all text-center text-slate-600 hover:text-ocean-600 hover:bg-ocean-50 whitespace-nowrap">
            <i class="fa-solid fa-link shrink-0 text-xs sm:text-sm"></i>
            <span>Korelasi Pearson (r)</span>
        </button>

        <button onclick="switchTab('tab-ketimpangan')" id="btn-tab-ketimpangan" class="tab-btn flex-shrink-0 sm:flex-shrink flex items-center justify-center gap-1.5 sm:gap-2 py-2 sm:py-2.5 px-3 rounded-xl text-xs sm:text-[13px] font-bold transition-all text-center text-slate-600 hover:text-ocean-600 hover:bg-ocean-50 whitespace-nowrap">
            <i class="fa-solid fa-scale-unbalanced shrink-0 text-xs sm:text-sm"></i>
            <span>Ketimpangan Spasial</span>
        </button>

        <button onclick="switchTab('tab-kuadran')" id="btn-tab-kuadran" class="tab-btn flex-shrink-0 sm:flex-shrink flex items-center justify-center gap-1.5 sm:gap-2 py-2 sm:py-2.5 px-3 rounded-xl text-xs sm:text-[13px] font-bold transition-all text-center text-slate-600 hover:text-ocean-600 hover:bg-ocean-50 whitespace-nowrap">
            <i class="fa-solid fa-table-cells-large shrink-0 text-xs sm:text-sm"></i>
            <span>Matriks Kuadran</span>
        </button>

        <button onclick="switchTab('tab-proyeksi')" id="btn-tab-proyeksi" class="tab-btn flex-shrink-0 sm:flex-shrink flex items-center justify-center gap-1.5 sm:gap-2 py-2 sm:py-2.5 px-3 rounded-xl text-xs sm:text-[13px] font-bold transition-all text-center text-slate-600 hover:text-ocean-600 hover:bg-ocean-50 whitespace-nowrap">
            <i class="fa-solid fa-wand-magic-sparkles shrink-0 text-xs sm:text-sm"></i>
            <span>Proyeksi &amp; Simulasi</span>
        </button>

    </div>

    <!-- ============================================================== -->
    <!-- KONTEN TAB 1: RINGKASAN BPS & MAKRO                            -->
    <!-- ============================================================== -->
    <div id="tab-ringkasan" class="tab-content space-y-6">
        <?php include __DIR__ . '/ringkasan/kartu_kpi.php'; ?>
        <?php include __DIR__ . '/ringkasan/tren_bulanan_bps.php'; ?>
    </div>

    <!-- ============================================================== -->
    <!-- KONTEN TAB 2: KORELASI PEARSON (r)                             -->
    <!-- ============================================================== -->
    <div id="tab-korelasi" class="tab-content hidden space-y-6">
        <?php include __DIR__ . '/korelasi_pearson/ringkasan_korelasi.php'; ?>
        <?php include __DIR__ . '/korelasi_pearson/grafik_sebaran.php'; ?>
    </div>

    <!-- ============================================================== -->
    <!-- KONTEN TAB 3: KETIMPANGAN SPASIAL & RASIO BEBAN                -->
    <!-- ============================================================== -->
    <div id="tab-ketimpangan" class="tab-content hidden space-y-6">
        <?php include __DIR__ . '/ketimpangan_spasial/tabel_rasio_beban.php'; ?>
        <?php include __DIR__ . '/ketimpangan_spasial/arahan_kebijakan.php'; ?>
    </div>

    <!-- ============================================================== -->
    <!-- KONTEN TAB 4: MATRIKS KUADRAN PRIORITAS INVESTASI              -->
    <!-- ============================================================== -->
    <div id="tab-kuadran" class="tab-content hidden space-y-6">
        <?php include __DIR__ . '/matriks_kuadran/grafik_kuadran.php'; ?>
        <?php include __DIR__ . '/matriks_kuadran/panduan_kuadran.php'; ?>
    </div>

    <!-- ============================================================== -->
    <!-- KONTEN TAB 5: PROYEKSI TREN DERET WAKTU & SIMULASI INTERAKTIF  -->
    <!-- ============================================================== -->
    <div id="tab-proyeksi" class="tab-content hidden space-y-6">
        <?php include __DIR__ . '/proyeksi_tren/grafik_proyeksi.php'; ?>
        <?php include __DIR__ . '/simulasi_investasi/alat_simulasi.php'; ?>
    </div>

</main>

<!-- Skrip Pengendali Perpindahan Tab Navigasi -->
<script>
function switchTab(targetTabId) {
    // 1. Sembunyikan seluruh konten tab
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.add('hidden');
    });

    // 2. Tampilkan konten tab yang dipilih
    const targetContent = document.getElementById(targetTabId);
    if (targetContent) {
        targetContent.classList.remove('hidden');
    }

    // 3. Perbarui gaya tombol tab aktif vs tidak aktif
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-ocean-600', 'text-white', 'shadow-xs');
        btn.classList.add('text-slate-600', 'hover:text-ocean-600', 'hover:bg-ocean-50');
    });

    const activeBtn = document.getElementById('btn-' + targetTabId);
    if (activeBtn) {
        activeBtn.classList.remove('text-slate-600', 'hover:text-ocean-600', 'hover:bg-ocean-50');
        activeBtn.classList.add('bg-ocean-600', 'text-white', 'shadow-xs');
    }

    // Trigger resize agar grafik Chart.js menyesuaikan ukuran kontainer
    window.dispatchEvent(new Event('resize'));
}
</script>
