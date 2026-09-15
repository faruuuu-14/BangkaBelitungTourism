<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA NUSANTARA (INDONESIA TOURISM HUB)
 * ==============================================================================
 * 
 * @file      kartu_metrik_nasional.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Kartu Indikator Kunci Agregat Pariwisata Skala Nasional)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Menyajikan 4 indikator utama pariwisata makro skala nasional:
 * 1. Total Destinasi Terpetakan (875 Objek terverifikasi di wilayah percontohan).
 * 2. Ekosistem Wilayah (14 Provinsi: 1 Pilot Project penuh + 13 Siap Integrasi).
 * 3. Kesiapan Fasilitas Standar 3A Kemenparekraf (Atraksi, Aksesibilitas, Amenitas).
 * 4. Rata-rata Skor Kepuasan Wisatawan Nasional (4.4 / 5.0).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php saat wilayah=nasional
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 2. KARTU METRIK UTAMA AGREGAT PARIWISATA NASIONAL              -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 w-full">

    <!-- Kartu 1: Total Destinasi Terpetakan -->
    <div class="relative bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col justify-between">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-ocean-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="flex items-center justify-between relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Destinasi Terpetakan</span>
            <div class="w-10 h-10 rounded-2xl bg-ocean-100 text-ocean-700 flex items-center justify-center text-base shadow-xs group-hover:bg-ocean-600 group-hover:text-white transition-colors">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
        </div>
        <div class="mt-3 sm:mt-4 relative z-10">
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">875</span>
                <span class="text-xs font-bold text-ocean-600 bg-ocean-50 px-2 py-0.5 rounded-full border border-ocean-200/60">Objek Aktif</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1 font-medium leading-relaxed">
                Basis data geospasial terverifikasi di wilayah percontohan Bangka Belitung, siap menampung 13 provinsi mitra.
            </p>
        </div>
    </div>

    <!-- Kartu 2: Ekosistem Wilayah / Cakupan Provinsi -->
    <div class="relative bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col justify-between">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="flex items-center justify-between relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cakupan Wilayah</span>
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-base shadow-xs group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i class="fa-solid fa-earth-asia"></i>
            </div>
        </div>
        <div class="mt-3 sm:mt-4 relative z-10">
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">14</span>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">Provinsi</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1 font-medium leading-relaxed">
                1 Wilayah Percontohan Penuh (Bangka Belitung) &bull; 13 Provinsi Tahap 2 siap integrasi data pariwisata.
            </p>
        </div>
    </div>

    <!-- Kartu 3: Kesiapan Standar Kemenparekraf 3A -->
    <div class="relative bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col justify-between">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="flex items-center justify-between relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Standar Fasilitas</span>
            <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center text-base shadow-xs group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <i class="fa-solid fa-hotel"></i>
            </div>
        </div>
        <div class="mt-3 sm:mt-4 relative z-10">
            <div class="flex items-baseline gap-2">
                <span class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">3A Terpadu</span>
                <span class="text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-200/60">Kemenparekraf</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1 font-medium leading-relaxed">
                Klasifikasi atraksi alam, buatan, cagar budaya, serta amenitas hotel &amp; kuliner berstandar nasional.
            </p>
        </div>
    </div>

    <!-- Kartu 4: Kepuasan Pengunjung Nasional -->
    <div class="relative bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col justify-between">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full opacity-60 group-hover:scale-125 transition-transform duration-500"></div>
        <div class="flex items-center justify-between relative z-10">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Indeks Kepuasan</span>
            <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-base shadow-xs group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <i class="fa-solid fa-star"></i>
            </div>
        </div>
        <div class="mt-3 sm:mt-4 relative z-10">
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">4.4</span>
                <span class="text-xs font-bold text-slate-400">/ 5.0</span>
                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60 ml-1">Sangat Baik</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1 font-medium leading-relaxed">
                Agregat ulasan publik terverifikasi yang merefleksikan daya tarik dan kepuasan pengunjung nusantara.
            </p>
        </div>
    </div>

</div>
