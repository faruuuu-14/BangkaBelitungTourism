<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      bottom_nav.php
 * @folder    components/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (Native Mobile App-Like Bottom Navigation Bar)
 * ==============================================================================
 */

$currentPage = $_GET['page'] ?? 'beranda';
if ($currentPage === 'dashboard') $currentPage = 'beranda';
?>

<!-- NAVIGASI BAWAH SELULER (FIXED MOBILE APP BOTTOM BAR) -->
<nav class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200/90 shadow-2xl md:hidden px-2 py-1.5 pb-[max(0.5rem,env(safe-area-inset-bottom))]">
    <div class="grid grid-cols-5 items-center justify-around text-center">

        <!-- 1. Beranda -->
        <a href="index.php?page=beranda" class="flex flex-col items-center justify-center py-1 transition-transform active:scale-90 <?= ($currentPage === 'beranda') ? 'text-ocean-600 font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium' ?>">
            <div class="relative w-6 h-6 flex items-center justify-center text-base">
                <i class="fa-solid fa-compass"></i>
                <?php if ($currentPage === 'beranda'): ?>
                    <span class="absolute -bottom-1 w-1 h-1 rounded-full bg-ocean-600"></span>
                <?php endif; ?>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5">Beranda</span>
        </a>

        <!-- 2. Smart Itinerary -->
        <a href="index.php?page=itinerary" class="flex flex-col items-center justify-center py-1 transition-transform active:scale-90 <?= ($currentPage === 'itinerary') ? 'text-ocean-600 font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium' ?>">
            <div class="relative w-6 h-6 flex items-center justify-center text-base">
                <i class="fa-solid fa-route"></i>
                <?php if ($currentPage === 'itinerary'): ?>
                    <span class="absolute -bottom-1 w-1 h-1 rounded-full bg-ocean-600"></span>
                <?php endif; ?>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5">Rute Cerdas</span>
        </a>

        <!-- 3. SPK TOPSIS Sentimen -->
        <a href="index.php?page=rekomendasi" class="flex flex-col items-center justify-center py-1 transition-transform active:scale-90 <?= ($currentPage === 'rekomendasi') ? 'text-indigo-600 font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium' ?>">
            <div class="relative w-6 h-6 flex items-center justify-center text-base">
                <i class="fa-solid fa-ranking-star"></i>
                <?php if ($currentPage === 'rekomendasi'): ?>
                    <span class="absolute -bottom-1 w-1 h-1 rounded-full bg-indigo-600"></span>
                <?php endif; ?>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5">TOPSIS</span>
        </a>

        <!-- 4. Radar Bisnis -->
        <a href="index.php?page=radar_bisnis" class="flex flex-col items-center justify-center py-1 transition-transform active:scale-90 <?= ($currentPage === 'radar_bisnis') ? 'text-emerald-600 font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium' ?>">
            <div class="relative w-6 h-6 flex items-center justify-center text-base">
                <i class="fa-solid fa-chart-pie"></i>
                <?php if ($currentPage === 'radar_bisnis'): ?>
                    <span class="absolute -bottom-1 w-1 h-1 rounded-full bg-emerald-600"></span>
                <?php endif; ?>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5">Radar UMKM</span>
        </a>

        <!-- 5. Peta Spasial -->
        <a href="index.php?page=peta" class="flex flex-col items-center justify-center py-1 transition-transform active:scale-90 <?= ($currentPage === 'peta') ? 'text-ocean-600 font-extrabold' : 'text-slate-400 hover:text-slate-600 font-medium' ?>">
            <div class="relative w-6 h-6 flex items-center justify-center text-base">
                <i class="fa-solid fa-map-location-dot"></i>
                <?php if ($currentPage === 'peta'): ?>
                    <span class="absolute -bottom-1 w-1 h-1 rounded-full bg-ocean-600"></span>
                <?php endif; ?>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5">Peta WebGIS</span>
        </a>

    </div>
</nav>
