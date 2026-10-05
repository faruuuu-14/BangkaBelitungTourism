<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      navbar.php
 * @folder    components/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (Clean Modern App Header & Desktop Nav)
 * ==============================================================================
 */

require_once __DIR__ . '/../app/database.php';

$currentPage = $_GET['page'] ?? 'beranda';
if ($currentPage === 'dashboard') $currentPage = 'beranda';

if (!function_exists('navLinkClass')) {
    function navLinkClass($page, $current) {
        $isActive = ($page === $current);
        return $isActive 
            ? 'bg-slate-900 text-white font-extrabold shadow-xs' 
            : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-semibold';
    }
}
?>

<!-- HEADER NAVBAR GLOBAL -->
<header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 sm:h-16">

            <!-- LOGO & BRAND IDENTIFIER -->
            <a href="index.php?page=beranda" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-2xl bg-gradient-to-br from-ocean-500 to-ocean-700 text-white flex items-center justify-center font-black text-base shadow-sm shadow-ocean-500/30 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-compass"></i>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm sm:text-base font-black tracking-tight text-slate-900 group-hover:text-ocean-600 transition-colors">
                            Babel<span class="text-ocean-600">Tourism</span>
                        </span>
                        <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider hidden sm:inline-block">
                            SPKS Cerdas
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium hidden sm:block">Sistem Pendukung Keputusan Spasial</span>
                </div>
            </a>

            <!-- DESKTOP NAVIGATION TABS (5 PILAR MENU) -->
            <nav class="hidden md:flex items-center gap-1 p-1 bg-slate-50 border border-slate-200/80 rounded-2xl text-xs">
                <a href="index.php?page=beranda" class="px-3 py-1.5 rounded-xl transition-all <?= navLinkClass('beranda', $currentPage) ?>">
                    <span>Beranda</span>
                </a>
                <a href="index.php?page=itinerary" class="px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 <?= navLinkClass('itinerary', $currentPage) ?>">
                    <i class="fa-solid fa-wand-magic-sparkles text-[10px] text-ocean-500"></i>
                    <span>Smart Itinerary</span>
                </a>
                <a href="index.php?page=rekomendasi" class="px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 <?= navLinkClass('rekomendasi', $currentPage) ?>">
                    <i class="fa-solid fa-ranking-star text-[10px] text-indigo-500"></i>
                    <span>SPK TOPSIS</span>
                </a>
                <a href="index.php?page=radar_bisnis" class="px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 <?= navLinkClass('radar_bisnis', $currentPage) ?>">
                    <i class="fa-solid fa-chart-pie text-[10px] text-emerald-500"></i>
                    <span>Radar UMKM</span>
                </a>
                <a href="index.php?page=peta" class="px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5 <?= navLinkClass('peta', $currentPage) ?>">
                    <i class="fa-solid fa-map-location-dot text-[10px] text-slate-500"></i>
                    <span>Peta WebGIS</span>
                </a>
            </nav>

            <!-- RIGHT ACTION CONTROLS -->
            <div class="flex items-center gap-2">
                <!-- Tombol QR Akses Mobile -->
                <button onclick="openModalQR()" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-600 hover:text-ocean-600 flex items-center justify-center text-xs transition-colors" title="Buka di HP (Scan QR)">
                    <i class="fa-solid fa-qrcode"></i>
                </button>

                <!-- Tombol Info Skripsi & Metodologi -->
                <button onclick="openGuideModal('gtab-policy')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-600 hover:text-ocean-600 flex items-center justify-center text-xs transition-colors" title="Informasi Metodologi Skripsi">
                    <i class="fa-solid fa-circle-info"></i>
                </button>

                <!-- Avatar Pembuat Sistem (Fathurrahman Safari Lubis) -->
                <div class="flex items-center gap-2 pl-1 border-l border-slate-200">
                    <img src="assets/img/profil/fathurrahman.jpg" alt="Fathurrahman Safari Lubis" class="w-8 h-8 rounded-xl object-cover object-top border border-slate-200 shadow-2xs">
                    <div class="hidden lg:flex flex-col text-left">
                        <span class="text-xs font-bold text-slate-900 leading-none">Faru</span>
                        <span class="text-[10px] text-slate-400 font-mono">2411500025</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>
