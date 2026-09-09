<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      kartu_kpi.php
 * @folder    views/analisis/ringkasan/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Ringkasan Eksekutif & Indikator Kunci Makro BPS)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen ringkasan eksekutif (Executive Summary KPI Cards) pada modul Analisis.
 * Menyajikan 4 temuan paling strategis dari hasil olah data resmi BPS dan Kemenparekraf:
 * 1. Pertumbuhan Kunjungan Wisatawan YoY (Year-over-Year 2026)
 * 2. Beban & Peluang Amenitas (Jumlah kecamatan dengan defisit fasilitas akomodasi)
 * 3. Pola Sebaran Spasial (Hasil uji statistik Nearest Neighbor Analysis - Mengelompok)
 * 4. Kesiapan Wilayah / Lokomotif Daerah (Indeks kesiapan pariwisata daerah)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 1 (Ringkasan BPS & Makro)
 * - Menggunakan variabel: $prov, $kritisCount, $idealCount dari app/statistics.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memfasilitasi pembacaan cepat bagi dosen penguji, kepala dinas, atau investor
 * untuk memahami kondisi makro pariwisata Babel dalam waktu kurang dari 1 menit.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 1. RINGKASAN EKSEKUTIF MAKRO PARIWISATA BABEL                  -->
<!-- ============================================================== -->
<div class="bg-gradient-to-br from-slate-950 via-ocean-950 to-slate-900 rounded-3xl p-5 sm:p-7 text-white shadow-xl border border-white/10 space-y-4">
    
    <!-- Kepala Ringkasan Eksekutif -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-white/10">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-2xl bg-ocean-500/30 text-ocean-300 border border-ocean-400/30 flex items-center justify-center text-sm shadow-xs">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <div>
                <h2 class="text-sm sm:text-base font-extrabold text-white tracking-tight">Ringkasan Eksekutif: Kondisi Pariwisata Babel Terkini</h2>
                <p class="text-xs text-slate-400">Rangkuman 4 temuan strategis dari analisis data resmi BPS &amp; Kemenparekraf</p>
            </div>
        </div>
        <span class="text-[10px] font-bold px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 self-start sm:self-auto shadow-xs">
            <i class="fa-solid fa-arrow-trend-up mr-1"></i> Status: Positif Bertumbuh
        </span>
    </div>

    <!-- Grid 4 Kartu Temuan Strategis -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 text-xs">
        
        <!-- 1. Laju Kunjungan Wisatawan (BPS) -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-1.5 hover:bg-white/10 transition-colors">
            <span class="text-[10px] font-bold uppercase tracking-wider text-ocean-300 block">1. Laju Kunjungan (BPS)</span>
            <div class="text-xl sm:text-2xl font-black text-emerald-400 flex items-baseline gap-1.5">
                <span>+<?= $prov['growth'] ?? '9.95' ?>%</span>
                <span class="text-xs font-normal text-slate-300">YoY 2026</span>
            </div>
            <p class="text-slate-400 text-[11px] leading-relaxed">
                Total <strong class="text-white"><?= number_format($prov['total_2026'] ?? 157294) ?></strong> wisatawan nusantara telah berkunjung ke Babel pada triwulan awal 2026.
            </p>
        </div>

        <!-- 2. Keseimbangan Beban Amenitas -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-1.5 hover:bg-white/10 transition-colors">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-300 block">2. Beban &amp; Peluang Hotel</span>
            <div class="text-xl sm:text-2xl font-black text-amber-400">
                <?= $kritisCount ?? 14 ?> <span class="text-xs font-normal text-slate-300">Kec. Defisit Amenitas</span>
            </div>
            <p class="text-slate-400 text-[11px] leading-relaxed">
                <strong class="text-white"><?= $idealCount ?? 18 ?></strong> kecamatan seimbang, sementara <strong class="text-amber-300"><?= $kritisCount ?? 14 ?></strong> kecamatan membutuhkan investasi hotel &amp; resto pendukung.
            </p>
        </div>

        <!-- 3. Pola Sebaran Spasial Objek -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-1.5 hover:bg-white/10 transition-colors">
            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-300 block">3. Pola Sebaran Spasial</span>
            <div class="text-xl sm:text-2xl font-black text-purple-300">
                Mengelompok <span class="text-xs font-normal text-slate-300">(Clustered)</span>
            </div>
            <p class="text-slate-400 text-[11px] leading-relaxed">
                Uji statistik NNA membuktikan objek wisata terkonsentrasi di kawasan pesisir pantai &amp; dekat simpul transportasi bandara/pelabuhan.
            </p>
        </div>

        <!-- 4. Lokomotif Kesiapan Wilayah -->
        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-1.5 hover:bg-white/10 transition-colors">
            <span class="text-[10px] font-bold uppercase tracking-wider text-sky-300 block">4. Lokomotif Daerah</span>
            <div class="text-base sm:text-lg font-black text-sky-300 truncate">
                Pangkalpinang &amp; Belitung
            </div>
            <p class="text-slate-400 text-[11px] leading-relaxed">
                Kesiapan fasilitas amenitas terbaik di Babel, berfungsi sebagai gerbang utama (*hub*) distribusi wisatawan nusantara.
            </p>
        </div>

    </div>

</div>
