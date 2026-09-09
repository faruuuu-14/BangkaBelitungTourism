<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      navbar.php
 * @folder    components/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Bilah Navigasi Global Berbahasa Indonesia)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen bilah navigasi utama (Header Navbar) dan navigasi bawah seluler
 * (Mobile Bottom Bar). Menyediakan navigasi instan antar fitur sistem informasi
 * (Beranda, Peta Wisata, Analisis BPS, Rekomendasi TOPSIS, Usulkan Destinasi,
 * dan Moderasi Data), status antrean usulan moderasi dinas, serta tombol modal
 * panduan metodologi komprehensif.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (Master Router)
 * - Membaca data: app/statistics.php (fungsi getPendingSubmissionsCount)
 * - Menautkan ke: index.php?page=beranda, peta, analisis, rekomendasi, tambah, verifikasi
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menjaga konsistensi navigasi (Nielsen Heuristic #4) di seluruh tampilan layar,
 * baik desktop monitor 2K, laptop, maupun perangkat smartphone pengguna.
 * ==============================================================================
 */

require_once __DIR__ . '/../app/statistics.php';

// Menentukan halaman aktif saat ini (Mendukung alias 'dashboard' dan 'beranda')
$currentPage = $_GET['page'] ?? 'beranda';
if ($currentPage === 'dashboard') {
    $currentPage = 'beranda';
}
$pendingSubmissions = getPendingSubmissionsCount();

if (!function_exists('navActive')) {
    /**
     * Helper penentu kelas CSS aktif pada menu navigasi
     */
    function navActive($page, $current) {
        $isActive = ($page === $current) || ($page === 'beranda' && $current === 'dashboard');
        return $isActive 
            ? 'bg-ocean-600 text-white shadow-sm font-bold' 
            : 'text-slate-600 hover:text-ocean-600 hover:bg-ocean-50 font-medium';
    }
}
?>

<!-- BANNER ATAS MODE KIOSK BANDARA (TOUCHSCREEN AIRPORT DISPLAY) -->
<div id="airportKioskTopBar" class="hidden bg-gradient-to-r from-purple-950 via-slate-900 to-indigo-950 text-white px-3 sm:px-6 py-2.5 z-50 sticky top-0 border-b border-purple-500/40 shadow-xl w-full">
    <div class="max-w-7xl mx-auto flex items-center justify-between text-xs">
        <div class="flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-plane-arrival text-purple-400 text-sm"></i>
                <span class="font-extrabold uppercase tracking-wider text-[10px] sm:text-xs">Anjungan Kiosk Wisata &bull; Bandara Depati Amir (PGK) &amp; Hanandjoeddin (TJQ)</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center gap-1.5 font-mono text-[11px] text-purple-200">
                <i class="fa-solid fa-cloud-sun text-amber-300"></i>
                <span>29°C Pesisir Babel</span>
            </div>
            <div class="font-mono text-amber-300 font-bold text-xs" id="kioskLiveClock">
                00:00:00 WIB
            </div>
            <button onclick="toggleAirportKioskMode()" class="px-2.5 py-1 rounded-lg bg-white/15 hover:bg-white/25 text-white font-bold text-[10px] border border-white/20 transition flex items-center gap-1">
                <i class="fa-solid fa-compress"></i>
                <span class="hidden sm:inline">Keluar</span>
            </button>
        </div>
    </div>
</div>

<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 w-full max-w-full overflow-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 w-full">
        <div class="flex items-center justify-between h-14 sm:h-16 gap-2 w-full min-w-0">
            
            <!-- Logo & Identitas Brand Sistem -->
            <a href="index.php?page=beranda" class="flex items-center gap-2 sm:gap-2.5 flex-shrink-0 group min-w-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-ocean-700 to-emerald-500 flex items-center justify-center text-white shadow-md shadow-ocean-500/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <i class="fa-solid fa-compass text-sm sm:text-lg"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-sm sm:text-lg font-extrabold bg-gradient-to-r from-ocean-800 to-slate-900 bg-clip-text text-transparent leading-none block truncate">Pariwisata Babel</span>
                    <span class="block text-[9px] sm:text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5 truncate">Portal Intelijen &amp; Spasial</span>
                </div>
            </a>

            <!-- Menu Navigasi Desktop (Responsif & Rapi Berbahasa Indonesia) -->
            <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1 bg-slate-100/90 p-1 rounded-2xl border border-slate-200/70 flex-shrink-0">
                <a href="index.php?page=beranda" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('beranda', $currentPage) ?>">
                    <i class="fa-solid fa-house text-xs"></i>
                    <span>Beranda</span>
                </a>
                <a href="index.php?page=peta" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('peta', $currentPage) ?>">
                    <i class="fa-solid fa-map-location-dot text-xs"></i>
                    <span>Peta Wisata</span>
                </a>
                <a href="index.php?page=analisis" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('analisis', $currentPage) ?>">
                    <i class="fa-solid fa-chart-line text-xs"></i>
                    <span>Analisis &amp; BPS</span>
                </a>
                <a href="index.php?page=rekomendasi" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('rekomendasi', $currentPage) ?>">
                    <i class="fa-solid fa-ranking-star text-xs"></i>
                    <span>Rekomendasi TOPSIS</span>
                </a>
                <a href="index.php?page=tambah" class="px-2.5 xl:px-3.5 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1.5 <?= navActive('tambah', $currentPage) ?>">
                    <i class="fa-solid fa-circle-plus text-xs"></i>
                    <span>Usulkan Tempat</span>
                </a>
                <a href="index.php?page=verifikasi" class="px-2 xl:px-3 py-1.5 xl:py-2 rounded-xl text-xs xl:text-sm transition-all flex items-center gap-1 <?= navActive('verifikasi', $currentPage) ?>">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Moderasi Dinas</span>
                    <?php if ($pendingSubmissions > 0): ?>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-purple-600 text-white animate-pulse">
                        <?= $pendingSubmissions ?>
                    </span>
                    <?php endif; ?>
                </a>
            </nav>

            <!-- Aksi Kanan & Profil Pengembang -->
                <!-- Tombol Pembuat Kode QR Wisata Pintar (Smart Tourism QR Generator) -->
                <button onclick="openGenericQrModal()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-ocean-50 hover:bg-ocean-100 text-ocean-700 flex items-center justify-center border border-ocean-200 transition-all shadow-xs cursor-pointer" title="Pembuat Kode QR Wisata Pintar (Navigasi HP Langsung)">
                    <i class="fa-solid fa-qrcode text-xs sm:text-sm"></i>
                </button>

                <!-- Tombol Mode Kiosk Bandara (Touchscreen Kiosk Display) -->
                <button onclick="toggleAirportKioskMode()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 flex items-center justify-center border border-purple-200 transition-all shadow-xs cursor-pointer" title="Mode Layar Sentuh Kiosk Bandara (Touchscreen Display)">
                    <i class="fa-solid fa-tv text-xs sm:text-sm"></i>
                </button>

                <!-- Sakelar Tema Gelap / Terang (Dark / Light Mode) -->
                <button id="themeToggleBtn" onclick="toggleAppTheme()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 flex items-center justify-center border border-slate-200 transition-all shadow-xs cursor-pointer" title="Ganti Mode Tampilan (Gelap / Terang)">
                    <i id="themeIconMoon" class="fa-solid fa-moon text-sm text-slate-600"></i>
                    <i id="themeIconSun" class="fa-solid fa-sun text-sm text-amber-400 hidden"></i>
                </button>

                <!-- Tombol Buku Panduan Pengguna -->
                <button onclick="openGuideModal()" class="flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 hover:text-ocean-700 text-xs font-bold border border-slate-200 transition-all shadow-xs" title="Buku Panduan, Sumber Data &amp; Metodologi">
                    <i class="fa-solid fa-circle-question text-ocean-600 text-sm"></i>
                    <span class="hidden sm:inline">Panduan</span>
                </button>

                <!-- Avatar Profil Pengembang (Faru) -->
                <button onclick="openGuideModal('gtab-policy')" class="flex items-center gap-2 p-1 pr-2 sm:pr-2.5 rounded-full bg-slate-100 hover:bg-ocean-50 border border-slate-200 transition-all text-xs" title="Profil Pengembang: Fathurrahman Safari Lubis (NIM: 2411500025)">
                    <img src="assets/img/fathurrahman.jpg" alt="Fathurrahman" class="w-7 h-7 rounded-full object-cover object-center border border-ocean-500 shadow-xs">
                    <span class="hidden 2xl:inline text-[11px] font-bold text-slate-700">Fathurrahman S.L.</span>
                </button>

                <!-- Status 875 Data Wisata Terverifikasi -->
                <div class="hidden 2xl:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-semibold">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>875 Data Terverifikasi</span>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- ============================================================== -->
<!-- NAVIGASI BAWAH SELULER (FIXED MOBILE APP NAVIGATION BAR)       -->
<!-- ============================================================== -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-slate-200 shadow-2xl py-1 px-2 flex items-center justify-around safe-area-bottom">
    <a href="index.php?page=beranda" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= in_array($currentPage, ['beranda', 'dashboard']) ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-house text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Beranda</span>
    </a>
    <a href="index.php?page=peta" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'peta' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-map-location-dot text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Peta</span>
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

<script>
// Fungsi Sinkronisasi Ikon & Status Tema Gelap/Terang
function updateThemeIcon() {
    const isDark = document.documentElement.classList.contains('dark');
    const moon = document.getElementById('themeIconMoon');
    const sun = document.getElementById('themeIconSun');
    if (moon && sun) {
        if (isDark) {
            moon.classList.add('hidden');
            sun.classList.remove('hidden');
        } else {
            moon.classList.remove('hidden');
            sun.classList.add('hidden');
        }
    }
}

function toggleAppTheme() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
    updateThemeIcon();
}

// Inisialisasi ikon saat DOM siap
document.addEventListener('DOMContentLoaded', updateThemeIcon);

// ==============================================================
// LOGIKA MODE KIOSK LAYAR SENTUH BANDARA (TOUCHSCREEN DISPLAY)
// ==============================================================
let kioskClockInterval = null;

function updateKioskClock() {
    const el = document.getElementById('kioskLiveClock');
    if (!el) return;
    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
    el.innerText = timeStr;
}

function toggleAirportKioskMode() {
    const topBar = document.getElementById('airportKioskTopBar');
    if (!topBar) return;
    const isCurrentlyKiosk = !topBar.classList.contains('hidden');

    if (!isCurrentlyKiosk) {
        topBar.classList.remove('hidden');
        document.body.classList.add('kiosk-active');
        if (!document.fullscreenElement && document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen().catch(() => {});
        }
        updateKioskClock();
        if (!kioskClockInterval) kioskClockInterval = setInterval(updateKioskClock, 1000);
    } else {
        topBar.classList.add('hidden');
        document.body.classList.remove('kiosk-active');
        if (document.fullscreenElement && document.exitFullscreen) {
            document.exitFullscreen().catch(() => {});
        }
        if (kioskClockInterval) {
            clearInterval(kioskClockInterval);
            kioskClockInterval = null;
        }
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.body.classList.contains('kiosk-active')) {
        toggleAirportKioskMode();
    }
});
</script>
