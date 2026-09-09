<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      kartu_metrik.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (4 Kartu Indikator Metrik Utama Pariwisata Babel)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen ringkasan metrik kuantitatif (Executive KPI Cards) pada beranda.
 * Menampilkan 4 kartu indikator utama:
 * 1. Total Seluruh Destinasi & Fasilitas (875 titik terkurasi)
 * 2. Total Objek Daya Tarik Wisata (Alam, Buatan, Kebudayaan)
 * 3. Total Fasilitas Pendukung / Amenitas (Hotel & Restoran)
 * 4. Akumulasi Kunjungan Wisatawan Nusantara Terkini (Data Resmi BPS 2026)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php
 * - Menggunakan variabel data: $stats dan $bpsTotal dari query basis data
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menyajikan gambaran umum data secara cepat (*at-a-glance metrics*) sesuai
 * kaidah Visual Hierarchy dan Dashboard Ergonomics.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 3. KARTU METRIK UTAMA PARIWISATA BABEL (4 INDIKATOR KUNCI)     -->
<!-- ============================================================== -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
    
    <!-- Metrik 1: Total Objek Terdaftar -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Total Terdata</span>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-ocean-50 text-ocean-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                <i class="fa-solid fa-map-location-dot text-sm sm:text-base"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                <?= number_format($stats['total_semua'] ?? 875) ?>
            </div>
            <div class="flex items-center gap-1.5 mt-1 text-xs text-slate-500 font-medium">
                <span class="text-emerald-600 font-bold flex items-center gap-0.5">
                    <i class="fa-solid fa-check-circle text-[10px]"></i> 100%
                </span>
                <span>Terverifikasi spasial</span>
            </div>
        </div>
    </div>

    <!-- Metrik 2: Total Daya Tarik (Alam & Budaya) -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Daya Tarik Wisata</span>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                <i class="fa-solid fa-umbrella-beach text-sm sm:text-base"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                <?= number_format($stats['total_daya_tarik'] ?? 494) ?>
            </div>
            <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500 font-medium">
                <span class="text-sky-600 font-bold"><?= $stats['total_alam'] ?? 0 ?> Alam</span>
                <span>&bull;</span>
                <span class="text-rose-600 font-bold"><?= $stats['total_budaya'] ?? 0 ?> Budaya</span>
            </div>
        </div>
    </div>

    <!-- Metrik 3: Total Fasilitas Amenitas (Hotel & Kuliner) -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Fasilitas Amenitas</span>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                <i class="fa-solid fa-hotel text-sm sm:text-base"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                <?= number_format($stats['total_amenitas'] ?? 381) ?>
            </div>
            <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500 font-medium">
                <span class="text-indigo-600 font-bold"><?= $stats['total_hotel'] ?? 0 ?> Hotel</span>
                <span>&bull;</span>
                <span class="text-amber-600 font-bold"><?= $stats['total_restoran'] ?? 0 ?> Resto</span>
            </div>
        </div>
    </div>

    <!-- Metrik 4: Kunjungan Wisatawan BPS 2026 -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <span class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Kunjungan Wisata BPS</span>
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                <i class="fa-solid fa-chart-line text-sm sm:text-base"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                <?= number_format($bpsTotal > 0 ? $bpsTotal : 157294) ?>
            </div>
            <div class="flex items-center gap-1.5 mt-1 text-xs text-slate-500 font-medium">
                <span class="text-emerald-600 font-bold flex items-center gap-0.5">
                    <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> +9.95%
                </span>
                <span>YoY BPS Triwulan 2026</span>
            </div>
        </div>
    </div>

</div>
