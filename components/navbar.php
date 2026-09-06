<?php
require_once __DIR__ . '/../app/statistics.php';

$currentPage = $_GET['page'] ?? 'dashboard';
$pendingSubmissions = getPendingSubmissionsCount();

if (!function_exists('navActive')) {
    function navActive($page, $current) {
        return $page === $current 
            ? 'bg-ocean-600 text-white shadow-sm font-semibold' 
            : 'text-slate-600 hover:text-ocean-600 hover:bg-ocean-50 font-medium';
    }
}
?>

<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 w-full max-w-full overflow-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 w-full">
        <div class="flex items-center justify-between h-14 sm:h-16 gap-2 w-full min-w-0">
            
            <!-- Brand Logo -->
            <a href="index.php?page=dashboard" class="flex items-center gap-2 sm:gap-2.5 flex-shrink-0 group min-w-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-ocean-700 to-emerald-500 flex items-center justify-center text-white shadow-md shadow-ocean-500/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <i class="fa-solid fa-compass text-sm sm:text-lg"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-sm sm:text-lg font-bold bg-gradient-to-r from-ocean-800 to-slate-900 bg-clip-text text-transparent leading-none block truncate">BabelTourism</span>
                    <span class="block text-[9px] sm:text-[11px] font-medium text-slate-500 uppercase tracking-wider mt-0.5 truncate">Spatial &amp; Analytics Hub</span>
                </div>
            </a>

            <!-- Desktop Navigation Links (Responsive: Compact on Laptop 100% zoom, Full on 2K+) -->
            <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1 bg-slate-100/90 p-1 rounded-2xl border border-slate-200/70 flex-shrink-0">
                <a href="index.php?page=dashboard" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('dashboard', $currentPage) ?>">
                    <i class="fa-solid fa-chart-pie text-xs"></i>
                    <span>Dashboard</span>
                </a>
                <a href="index.php?page=peta" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('peta', $currentPage) ?>">
                    <i class="fa-solid fa-map-location-dot text-xs"></i>
                    <span>Peta GIS</span>
                </a>
                <a href="index.php?page=analisis" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('analisis', $currentPage) ?>">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                    <span>Analisis</span>
                </a>
                <a href="index.php?page=rekomendasi" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('rekomendasi', $currentPage) ?>">
                    <i class="fa-solid fa-ranking-star text-xs"></i>
                    <span>SPK TOPSIS</span>
                </a>
                <a href="index.php?page=tambah" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('tambah', $currentPage) ?>">
                    <i class="fa-solid fa-circle-plus text-xs"></i>
                    <span>Input</span>
                </a>
                <a href="index.php?page=verifikasi" class="px-2 xl:px-3 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1 <?= navActive('verifikasi', $currentPage) ?>">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Moderasi</span>
                    <?php if ($pendingSubmissions > 0): ?>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-purple-600 text-white animate-pulse">
                        <?= $pendingSubmissions ?>
                    </span>
                    <?php endif; ?>
                </a>
            </nav>

            <!-- Right Action & Profile Section -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <!-- Panduan Modal Button -->
                <button onclick="openGuideModal()" class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 hover:text-ocean-700 text-xs font-bold border border-slate-200 transition-all shadow-sm" title="Buku Panduan, Sumber Data &amp; Metodologi">
                    <i class="fa-solid fa-circle-question text-ocean-600 text-sm"></i>
                    <span class="hidden sm:inline">Panduan</span>
                </button>

                <!-- Author Quick Profile Badge -->
                <button onclick="openGuideModal('gtab-policy')" class="flex items-center gap-2 p-1 pr-2 sm:pr-2.5 rounded-full bg-slate-100 hover:bg-ocean-50 border border-slate-200 transition-all text-xs" title="Profil Pengembang: Fathurrahman Safari Lubis (NIM: 2411500025)">
                    <img src="assets/img/fathurrahman.jpg" alt="Fathurrahman" class="w-7 h-7 rounded-full object-cover object-center border border-ocean-500 shadow-sm">
                    <span class="hidden 2xl:inline text-[11px] font-bold text-slate-700">Fathurrahman S.L.</span>
                </button>

                <!-- 875 Data Terkurasi Pill (Only on wide screens) -->
                <div class="hidden 2xl:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-semibold">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>875 Data</span>
                </div>
                
                <!-- Quick Input Action -->
                <a href="index.php?page=tambah" class="hidden xl:flex items-center gap-1.5 bg-slate-900 hover:bg-ocean-600 text-white text-xs font-semibold px-3 py-1.5 rounded-xl shadow-sm transition-colors">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Usulan</span>
                </a>
            </div>

        </div>
    </div>
</header>

<!-- ============================================================== -->
<!-- MODERN MOBILE BOTTOM NAVIGATION BAR (FIXED APP-STYLE)          -->
<!-- ============================================================== -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-slate-200 shadow-2xl py-1 px-2 flex items-center justify-around safe-area-bottom">
    <a href="index.php?page=dashboard" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'dashboard' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-chart-pie text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Dashboard</span>
    </a>
    <a href="index.php?page=peta" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'peta' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-map-location-dot text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Peta GIS</span>
    </a>
    <a href="index.php?page=analisis" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'analisis' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-chart-line text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Analisis</span>
    </a>
    <a href="index.php?page=rekomendasi" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'rekomendasi' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-ranking-star text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">SPK</span>
    </a>
    <a href="index.php?page=verifikasi" class="relative flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'verifikasi' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-shield-halved text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Moderasi</span>
        <?php if ($pendingSubmissions > 0): ?>
        <span class="absolute top-0 right-2 w-4 h-4 rounded-full bg-purple-600 text-white text-[9px] font-black flex items-center justify-center animate-pulse">
            <?= $pendingSubmissions ?>
        </span>
        <?php endif; ?>
    </a>
</nav>
