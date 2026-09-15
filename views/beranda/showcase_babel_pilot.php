<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA NUSANTARA (INDONESIA TOURISM HUB)
 * ==============================================================================
 * 
 * @file      showcase_babel_pilot.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Showcase Kartu Wilayah Percontohan Utama Bangka Belitung)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Menampilkan kartu informasi penegas bahwa Provinsi Kepulauan Bangka Belitung
 * adalah daerah percontohan (Pilot Project) utama dengan basis data terlengkap
 * (875 objek, 7 kab/kota, data BPS 2021-2026, dan SPK TOPSIS 5 kriteria).
 * Menyediakan tombol 1-klik untuk beralih langsung ke tampilan mikro Babel.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php saat wilayah=nasional
 * - Menautkan ke: index.php?page=beranda&wilayah=babel
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 3. SHOWCASE WILAYAH PERCONTOHAN UTAMA: BANGKA BELITUNG         -->
<!-- ============================================================== -->
<div class="relative bg-gradient-to-br from-slate-900 via-ocean-950 to-slate-950 rounded-3xl p-6 sm:p-8 lg:p-10 text-white overflow-hidden shadow-2xl border border-ocean-800/40">
    
    <!-- Dekorasi Latar Belakang Cahaya Maritim -->
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-ocean-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-center">
        
        <!-- Sisi Kiri: Narasi Ilmiah & Status Percontohan -->
        <div class="lg:col-span-7 space-y-4">
            
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Wilayah Percontohan Utama (Pilot Project)</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-ocean-500/20 text-ocean-300 border border-ocean-400/30">
                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                    <span>Laboratorium Riset ISB Atma Luhur</span>
                </span>
            </div>

            <h2 class="text-xl sm:text-3xl font-black text-white tracking-tight leading-snug">
                Provinsi Kepulauan <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-sky-400 bg-clip-text text-transparent">Bangka Belitung</span>
            </h2>

            <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                Sebagai inisiator sistem pariwisata berbasis bukti, Bangka Belitung telah memetakan <strong class="text-white font-bold">875 destinasi</strong> di 7 Kabupaten/Kota, mengintegrasikan data runtun waktu BPS 2021&ndash;2026, dan menerapkan algoritma SPK TOPSIS ilmiah. Data ini menjadi model baku untuk 13 provinsi mitra di seluruh Indonesia.
            </p>

            <!-- 3 Pilar Keunggulan Wilayah Percontohan -->
            <div class="grid grid-cols-3 gap-2.5 sm:gap-3 pt-2">
                <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/10 text-center">
                    <div class="text-lg sm:text-2xl font-black text-emerald-300">875</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 font-semibold mt-0.5">Objek 7 Wilayah</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/10 text-center">
                    <div class="text-lg sm:text-2xl font-black text-sky-300">112</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 font-semibold mt-0.5">Seri BPS 2021-2026</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/10 text-center">
                    <div class="text-lg sm:text-2xl font-black text-amber-300">5 Kriteria</div>
                    <div class="text-[10px] sm:text-xs text-slate-300 font-semibold mt-0.5">SPK TOPSIS</div>
                </div>
            </div>

            <!-- Tombol Aksi Buka Dashboard Babel -->
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="index.php?page=beranda&wilayah=babel" class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-emerald-600/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-gem text-amber-300"></i>
                    <span>Masuk ke Dashboard Bangka Belitung</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
                <a href="index.php?page=peta&wilayah=babel" class="px-4 py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-sky-400"></i>
                    <span>Peta 875 Titik Babel</span>
                </a>
            </div>

        </div>

        <!-- Sisi Kanan: 4 Kartu Metrik Keunggulan Babel (Clean Vector Bento) -->
        <div class="lg:col-span-5 grid grid-cols-2 gap-2.5 sm:gap-3">
            <div class="bg-white/10 hover:bg-white/15 border border-white/20 rounded-2xl p-4 transition-all flex flex-col justify-between space-y-2">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-map-pin"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-black text-white">875</div>
                    <div class="text-[11px] text-slate-300 font-medium">Objek Wisata Presisi</div>
                </div>
            </div>

            <div class="bg-white/10 hover:bg-white/15 border border-white/20 rounded-2xl p-4 transition-all flex flex-col justify-between space-y-2">
                <div class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-300 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-gem"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-black text-white">2.08</div>
                    <div class="text-[11px] text-slate-300 font-medium">RLMT Tertinggi (Belitung)</div>
                </div>
            </div>

            <div class="bg-white/10 hover:bg-white/15 border border-white/20 rounded-2xl p-4 transition-all flex flex-col justify-between space-y-2">
                <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-black text-white">112</div>
                    <div class="text-[11px] text-slate-300 font-medium">Deret Waktu BPS Riil</div>
                </div>
            </div>

            <div class="bg-white/10 hover:bg-white/15 border border-white/20 rounded-2xl p-4 transition-all flex flex-col justify-between space-y-2">
                <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-calculator"></i>
                </div>
                <div>
                    <div class="text-xl sm:text-2xl font-black text-white">SPK</div>
                    <div class="text-[11px] text-slate-300 font-medium">Algoritma TOPSIS 3A</div>
                </div>
            </div>
        </div>

    </div>

</div>
