<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      banner_rekomendasi.php
 * @folder    views/rekomendasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Header Visual & Panduan SPK Rekomendasi TOPSIS)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen header utama pada modul SPK Rekomendasi Destinasi. Menampilkan
 * judul pengantar sistem pendukung keputusan multi-kriteria (MCDM), penjelasan
 * algoritma TOPSIS (Hwang & Yoon, 1981), serta tombol aksi panduan metodologi,
 * ekspor file CSV, dan eksplorasi spasial di peta.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/rekomendasi/index.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan orientasi awal yang jelas dan kredibel bagi pengguna atau penguji
 * skripsi mengenai dasar ilmiah dari perankingan destinasi pariwisata Babel.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 1. HEADER BANNER REKOMENDASI TOPSIS                           -->
<!-- ============================================================== -->
<div class="relative overflow-hidden rounded-3xl p-6 sm:p-9 text-white shadow-xl border border-slate-800 bg-gradient-to-r from-slate-950 via-ocean-950 to-indigo-950">
    
    <div class="relative z-10 space-y-4 max-w-4xl">
        
        <!-- Label Badge Standar MCDM -->
        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-ocean-300 text-xs font-bold">
                <i class="fa-solid fa-ranking-star text-ocean-400"></i>
                <span>Sistem Pendukung Keputusan (SPK) &bull; Algoritma TOPSIS</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold">
                <i class="fa-solid fa-check-double text-[11px]"></i>
                <span>4 Kriteria Standar Kemenparekraf RI</span>
            </div>
        </div>

        <!-- Judul Utama -->
        <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight text-white">
            Rekomendasi Destinasi Wisata Terbaik (TOPSIS)
        </h1>

        <!-- Penjelasan Ilmiah Singkat -->
        <p class="text-slate-200 text-xs sm:text-sm leading-relaxed max-w-3xl">
            Sistem merangking destinasi wisata secara objektif dan matematis menggunakan metode <em>Technique for Order Preference by Similarity to Ideal Solution</em> (Hwang &amp; Yoon, 1981) berdasarkan 4 pilar kriteria: <strong>Kualitas Rating</strong> (Benefit), <strong>Jarak ke Pusat Transportasi</strong> (Cost), <strong>Ketersediaan Amenitas Sekitar</strong> (Benefit), dan <strong>Popularitas Ulasan</strong> (Benefit).
        </p>

        <!-- Tombol Aksi Cepat -->
        <div class="flex flex-wrap items-center gap-2.5 pt-1">
            <button onclick="openModalKomparasi()" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-black transition-all flex items-center gap-1.5 shadow-md hover:shadow-amber-500/20">
                <i class="fa-solid fa-scale-balanced"></i>
                <span>Bandingkan 2 Destinasi</span>
            </button>
            <button onclick="openGuideModal('rekomendasi')" class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 backdrop-blur-md transition-all flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-circle-question text-ocean-400"></i>
                <span>Panduan &amp; Rumus TOPSIS</span>
            </button>
            <button onclick="exportTopsisCSV()" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-file-csv"></i>
                <span>Unduh Hasil CSV</span>
            </button>
            <a href="index.php?page=peta" class="px-4 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Lihat di Peta Wisata</span>
            </a>
        </div>

    </div>

</div>
