<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      kartu_kpi.php
 * @folder    views/analisis/ringkasan/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Ringkasan Eksekutif & 4 Indikator Strategis BPS Babel 2026)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen ringkasan eksekutif (Executive Summary KPI Cards) pada modul Analisis.
 * Menyajikan 4 temuan paling strategis dari hasil olah data resmi BPS 2026:
 * 1. Pertumbuhan Perjalanan Wisnus YoY (+9.95% Caturwulan I 2026: 1.58M Wisnus)
 * 2. Segmentasi Pasar: 82.9% Day-Tripper vs 17.1% Stayers (Pasar Riil Hotel)
 * 3. Okupansi Kamar Hotel (TPK BPS Rata-rata 25.51% - 26.70%: Kapasitas Sangat Longgar)
 * 4. Rata-Rata Lama Menginap (RLMT 1.55 Malam: Belitung 2.08 Malam vs Bangka 1.06 Malam)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 1 (Ringkasan BPS & Makro)
 * - Menggunakan data dari: app/statistics.php
 * ==============================================================================
 */

$tpkData = getBpsTpkMonthlyData();
$kabData = getBpsKabupatenDataApril2026();
?>

<!-- ============================================================== -->
<!-- 1. RINGKASAN EKSEKUTIF MAKRO PARIWISATA BABEL (BPS RESMI)     -->
<!-- ============================================================== -->
<div class="bg-gradient-to-br from-slate-950 via-ocean-950 to-slate-900 rounded-2xl sm:rounded-3xl p-3.5 sm:p-7 text-white shadow-xl border border-white/10 space-y-3 sm:space-y-4">
    
    <!-- Kepala Ringkasan Eksekutif -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 sm:gap-2 pb-2.5 sm:pb-3 border-b border-white/10">
        <div class="flex items-center gap-2 sm:gap-2.5">
            <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-xl sm:rounded-2xl bg-ocean-500/30 text-ocean-300 border border-ocean-400/30 flex items-center justify-center text-xs sm:text-sm shadow-xs">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <h2 class="text-xs sm:text-base font-extrabold text-white tracking-tight">Indikator Kunci Pariwisata Babel 2026</h2>
                <p class="text-[10px] sm:text-xs text-slate-400">Olah data resmi BPS Provinsi Kep. Bangka Belitung</p>
            </div>
        </div>
        <span class="text-[9px] sm:text-[10px] font-bold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 self-start sm:self-auto shadow-xs">
            <i class="fa-solid fa-check-circle mr-1"></i> BPS April 2026
        </span>
    </div>

    <!-- Panduan Cepat Membaca 4 Indikator Makro BPS (Collapsible / Compact) -->
    <div class="p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl bg-white/5 border border-white/10 text-[11px] sm:text-xs text-slate-300 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 sm:gap-2.5">
        <div class="flex items-center gap-1.5 shrink-0">
            <i class="fa-solid fa-circle-question text-ocean-400 text-xs"></i>
            <span class="font-bold text-white text-[11px] sm:text-xs">Panduan 4 Indikator BPS:</span>
        </div>
        <div class="text-[10px] sm:text-[11px] text-slate-300 leading-snug sm:leading-relaxed">
            <strong>(1) Kunjungan:</strong> Pertumbuhan wisnus; <strong>(2) Tipe:</strong> Day-tripper vs stayer; <strong>(3) TPK:</strong> Okupansi hotel; dan <strong>(4) RLMT:</strong> Rata-rata malam menginap.
        </div>
    </div>

    <!-- Grid 4 Kartu Temuan Strategis BPS (2x2 di Mobile, 4 Kolom di Layar Lebar) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3.5 text-xs">
        
        <!-- 1. Pertumbuhan Kunjungan Wisatawan (BPS) -->
        <div class="bg-white/5 border border-white/10 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 space-y-1 sm:space-y-1.5 hover:bg-white/10 transition-colors">
            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-ocean-300 block truncate">1. Kunjungan Wisnus</span>
            <div class="text-base sm:text-2xl font-black text-emerald-400 flex items-baseline gap-1">
                <span>+<?= $prov['growth'] ?? '9.95' ?>%</span>
                <span class="text-[9px] sm:text-xs font-normal text-slate-400">YoY</span>
            </div>
            <p class="text-slate-300 text-[10px] sm:text-[11px] leading-tight sm:leading-relaxed line-clamp-2 sm:line-clamp-none">
                <strong class="text-white"><?= number_format($prov['total_2026'] ?? 1585978) ?></strong> kunjungan masuk Babel di awal 2026.
            </p>
        </div>

        <!-- 2. Tipe Wisatawan: Harian vs Menginap -->
        <div class="bg-white/5 border border-white/10 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 space-y-1 sm:space-y-1.5 hover:bg-white/10 transition-colors">
            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-amber-300 block truncate">2. Tipe Wisatawan</span>
            <div class="text-base sm:text-2xl font-black text-amber-300 flex items-baseline gap-1">
                <span>82.9%</span>
                <span class="text-[9px] sm:text-xs font-normal text-slate-400">Harian</span>
            </div>
            <p class="text-slate-300 text-[10px] sm:text-[11px] leading-tight sm:leading-relaxed line-clamp-2 sm:line-clamp-none">
                Mayoritas (<strong class="text-amber-300">307 ribu</strong>) harian; hanya <strong class="text-white">17.1%</strong> yang tidur di hotel.
            </p>
        </div>

        <!-- 3. Keterisian Kamar Hotel (TPK) -->
        <div class="bg-white/5 border border-white/10 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 space-y-1 sm:space-y-1.5 hover:bg-white/10 transition-colors">
            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-sky-300 block truncate">3. Okupansi Hotel</span>
            <div class="text-base sm:text-2xl font-black text-sky-300 flex items-baseline gap-1">
                <span>26.70%</span>
                <span class="text-[9px] sm:text-xs font-normal text-slate-400">TPK</span>
            </div>
            <p class="text-slate-300 text-[10px] sm:text-[11px] leading-tight sm:leading-relaxed line-clamp-2 sm:line-clamp-none">
                Kapasitas kamar longgar (73% kosong), melonjak saat libur panjang.
            </p>
        </div>

        <!-- 4. Rata-Rata Lama Menginap (RLMT) -->
        <div class="bg-white/5 border border-white/10 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 space-y-1 sm:space-y-1.5 hover:bg-white/10 transition-colors">
            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-purple-300 block truncate">4. Lama Tamu</span>
            <div class="text-base sm:text-2xl font-black text-purple-300 flex items-baseline gap-1">
                <span>1.55</span>
                <span class="text-[9px] sm:text-xs font-normal text-slate-400">Malam</span>
            </div>
            <p class="text-slate-300 text-[10px] sm:text-[11px] leading-tight sm:leading-relaxed line-clamp-2 sm:line-clamp-none">
                <strong class="text-white">Belitung</strong> 2.08 malam vs Bangka rata-rata 1.06 malam.
            </p>
        </div>

    </div>

</div>
