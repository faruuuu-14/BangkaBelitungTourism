<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      banner_utama.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Hero Banner Latar Belakang Penuh Foto Otentik Babel)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen hero banner utama pada halaman beranda. Menggunakan teknik latar
 * belakang visual penuh (full-bleed background image) foto otentik Pulau Lengkuas
 * Belitung (assets/img/pantai_lengkuas.jpg) yang dilapisi gradasi gelap elegan
 * (cinematic dark overlay). Menyajikan judul sambutan megah, label kebanggaan
 * UNESCO Global Geopark Belitong, serta 4 tombol pintasan aksi cepat ke fitur utama.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php
 * - Menggunakan aset: assets/img/pantai_lengkuas.jpg
 * - Menautkan ke: index.php?page=peta, index.php?page=analisis, index.php?page=rekomendasi
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menciptakan daya tarik visual awal (first impression) yang imersif, profesional,
 * dan berkelas dunia, sekaligus memenuhi prinsip kontras tinggi (WCAG 2.1 AAA)
 * sehingga seluruh teks terbaca sangat tajam dan nyaman di mata pengguna.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 1. HERO BANNER UTAMA (LATAR BELAKANG FOTO LENGKUAS PENUH)      -->
<!-- ============================================================== -->
<div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-800/80 w-full min-h-[360px] sm:min-h-[420px] lg:min-h-[460px] flex items-center">
    
    <!-- Latar Belakang Foto Panorama Otentik Babel -->
    <img src="assets/img/pantai_lengkuas.jpg" alt="Panorama Bahari Pulau Lengkuas Bangka Belitung" 
        class="absolute inset-0 w-full h-full object-cover object-center filter brightness-90 transform scale-100 transition-transform duration-1000 hover:scale-105">
    
    <!-- Lapisan Gradasi Sinematik Gelap (High-Contrast Dark Gradient Overlay) -->
    <!-- Memastikan keterbacaan teks 100% tajam dan elegan tanpa mengaburkan keindahan foto -->
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 to-slate-900/40"></div>
    
    <!-- Konten Teks & Tombol Aksi di Atas Foto -->
    <div class="relative z-10 w-full p-6 sm:p-10 lg:p-14 max-w-4xl space-y-5">
        
        <!-- Label Badge Kebanggaan Provinsi Kepulauan Bangka Belitung -->
        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 backdrop-blur-md shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Portal Resmi Satu Data Spasial &amp; Intelijen Pariwisata</span>
            </div>
            <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-300 border border-amber-400/30 backdrop-blur-md">
                <i class="fa-solid fa-gem text-amber-400 text-[11px]"></i>
                <span>UNESCO Global Geopark Belitong</span>
            </div>
        </div>

        <!-- Judul Utama Megah (Headline) -->
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight sm:leading-tight">
            Eksplorasi Keindahan Alam &amp; Warisan Budaya <span class="bg-gradient-to-r from-sky-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">Bangka Belitung</span>
        </h1>

        <!-- Deskripsi Ringkas & Konteks Nilai Guna Platform -->
        <p class="text-slate-200 text-xs sm:text-sm lg:text-base leading-relaxed max-w-2xl font-normal drop-shadow-xs">
            Sistem informasi intelijen terintegrasi untuk memetakan <strong class="text-white font-bold">875 objek pariwisata</strong>, menganalisis rasio beban amenitas hotel &amp; restoran berbasis data resmi BPS, serta meranking destinasi unggulan menggunakan algoritma ilmiah SPK TOPSIS.
        </p>

        <!-- Tombol Aksi Pintas (Modern Glassmorphic Quick Action Buttons) -->
        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3.5 pt-2">
            <!-- Aksi 1: Peta Spasial GIS -->
            <a href="index.php?page=peta" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-ocean-600 hover:bg-ocean-500 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-ocean-600/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Eksplorasi Peta Wisata</span>
            </a>

            <!-- Aksi 2: Analisis BPS & Beban Amenitas -->
            <a href="index.php?page=analisis" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-amber-400"></i>
                <span>Analisis Statistik BPS</span>
            </a>

            <!-- Aksi 3: Rekomendasi Destinasi TOPSIS -->
            <a href="index.php?page=rekomendasi" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fa-solid fa-ranking-star text-emerald-400"></i>
                <span>Rekomendasi TOPSIS</span>
            </a>

            <!-- Aksi 4: Buku Panduan Pengguna -->
            <button onclick="openGuideModal('dashboard')" class="px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-2xl bg-slate-900/80 hover:bg-slate-800 text-slate-300 hover:text-white font-semibold text-xs sm:text-sm border border-white/10 backdrop-blur-md transition-all flex items-center gap-1.5">
                <i class="fa-solid fa-circle-question text-xs"></i>
                <span>Panduan</span>
            </button>
        </div>

    </div>

    <!-- Badge Sudut Kanan Bawah: Info Lokasi Foto Latar Belakang -->
    <div class="hidden md:flex absolute bottom-4 right-4 z-10 items-center gap-2 px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-md border border-white/20 text-white text-[11px]">
        <i class="fa-solid fa-camera text-amber-400 text-xs"></i>
        <span>Mercusuar 1882 Pulau Lengkuas, Sijuk - Kab. Belitung</span>
    </div>

</div>
