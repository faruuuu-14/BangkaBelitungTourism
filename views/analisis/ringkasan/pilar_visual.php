<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      pilar_visual.php
 * @folder    views/analisis/ringkasan/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Strip Visual 4 Pilar Warisan Wisata & Budaya Babel)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen strip visual pada ringkasan analisis. Menampilkan 4 kartu visual
 * representatif yang menghubungkan data statistik ekonometrika dengan identitas
 * nyata pariwisata Bangka Belitung:
 * 1. Wisata Bahari & Geopark (Pulau Lengkuas Belitung)
 * 2. Seni Kriya Tradisional (Kain Tenun Cual Bangka)
 * 3. Wisata Literasi & Sastra (SD Laskar Pelangi Gantong)
 * 4. Warisan Sejarah & Edukasi (Museum Timah Indonesia Pangkalpinang)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php
 * - Menggunakan aset: assets/img/*.jpg
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan penyegaran visual estetis agar analisis statistik tidak terasa
 * kaku atau monoton, serta memperkuat branding pariwisata daerah.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 2. STRIP VISUAL 4 PILAR IDENTITAS PARIWISATA BABEL            -->
<!-- ============================================================== -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
    
    <!-- Pilar 1: Wisata Bahari & Geopark -->
    <div class="group relative rounded-2xl overflow-hidden shadow-xs border border-slate-200 bg-slate-900 h-28 sm:h-36 flex flex-col justify-end p-3 sm:p-4 text-white">
        <img src="assets/img/pantai_lengkuas.jpg" alt="Wisata Bahari Lengkuas" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 opacity-75">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
        <div class="relative z-10">
            <span class="text-[9px] sm:text-[10px] font-bold text-teal-300 block uppercase tracking-wider">Bahari &amp; Geopark</span>
            <div class="font-black text-xs sm:text-sm line-clamp-1">Pulau Lengkuas &amp; Granit</div>
            <div class="text-[10px] text-slate-300 hidden sm:block">Daya Tarik Utama Kunjungan Wisman</div>
        </div>
    </div>

    <!-- Pilar 2: Seni Kriya Kain Cual -->
    <div class="group relative rounded-2xl overflow-hidden shadow-xs border border-slate-200 bg-slate-900 h-28 sm:h-36 flex flex-col justify-end p-3 sm:p-4 text-white">
        <img src="assets/img/kain_cual_bangka.jpg" alt="Kain Cual Bangka" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 opacity-75">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
        <div class="relative z-10">
            <span class="text-[9px] sm:text-[10px] font-bold text-rose-300 block uppercase tracking-wider">Ekonomi Kreatif</span>
            <div class="font-black text-xs sm:text-sm line-clamp-1">Tenun Sutra Kain Cual</div>
            <div class="text-[10px] text-slate-300 hidden sm:block">Penggerak Belanja Suvenir Wisatawan</div>
        </div>
    </div>

    <!-- Pilar 3: Sastra Laskar Pelangi -->
    <div class="group relative rounded-2xl overflow-hidden shadow-xs border border-slate-200 bg-slate-900 h-28 sm:h-36 flex flex-col justify-end p-3 sm:p-4 text-white">
        <img src="assets/img/laskar_pelangi_gantong.jpg" alt="SD Laskar Pelangi" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 opacity-75">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
        <div class="relative z-10">
            <span class="text-[9px] sm:text-[10px] font-bold text-emerald-300 block uppercase tracking-wider">Wisata Sastra</span>
            <div class="font-black text-xs sm:text-sm line-clamp-1">SD Laskar Pelangi Gantong</div>
            <div class="text-[10px] text-slate-300 hidden sm:block">Pendorong Pariwisata Belitung Timur</div>
        </div>
    </div>

    <!-- Pilar 4: Sejarah Museum Timah -->
    <div class="group relative rounded-2xl overflow-hidden shadow-xs border border-slate-200 bg-slate-900 h-28 sm:h-36 flex flex-col justify-end p-3 sm:p-4 text-white">
        <img src="assets/img/museum_timah_babel.jpg" alt="Museum Timah Indonesia" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 opacity-75">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
        <div class="relative z-10">
            <span class="text-[9px] sm:text-[10px] font-bold text-amber-300 block uppercase tracking-wider">Cagar Budaya</span>
            <div class="font-black text-xs sm:text-sm line-clamp-1">Museum Timah Pangkalpinang</div>
            <div class="text-[10px] text-slate-300 hidden sm:block">Edukasi Pertimahan Asia Tenggara</div>
        </div>
    </div>

</div>
