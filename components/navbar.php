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
require_once __DIR__ . '/../app/database.php';

// Menentukan cakupan wilayah aktif saat ini (Default: nasional)
$currentWilayah = getCurrentWilayah();
$daftarProvinsi = getDaftarProvinsi();
$activeWilayah = $daftarProvinsi[$currentWilayah] ?? $daftarProvinsi['nasional'];
$isNasional = ($currentWilayah === 'nasional');
$wilayahParam = ($currentWilayah !== 'nasional') ? '&wilayah=' . urlencode($currentWilayah) : '';

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

<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 w-full">
    <div class="w-full max-w-[1720px] mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex items-center justify-between h-14 sm:h-16 gap-2 w-full min-w-0">
            
            <!-- Logo & Identitas Brand Sistem (Resmi Bangka Belitung) -->
            <a href="index.php?page=beranda" class="flex items-center gap-2 sm:gap-2.5 flex-shrink-0 group min-w-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-ocean-700 to-emerald-500 flex items-center justify-center text-white shadow-md shadow-ocean-500/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <i class="fa-solid fa-compass text-sm sm:text-lg"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-sm sm:text-base font-extrabold bg-gradient-to-r from-ocean-800 to-slate-900 bg-clip-text text-transparent leading-none block truncate">
                        Pariwisata Babel
                    </span>
                    <span class="hidden xl:block text-[9px] sm:text-[10px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5 truncate">
                        Portal Intelijen &amp; Spasial
                    </span>
                </div>
            </a>

            <!-- Menu Navigasi Desktop (Proporsional, Merata & Seimbang di Seluruh Lebar Layar) -->
            <nav class="hidden lg:grid grid-cols-6 gap-1 bg-slate-100/90 p-1.5 rounded-2xl border border-slate-200/80 shadow-2xs flex-1 max-w-3xl xl:max-w-4xl mx-3 xl:mx-8">
                <a href="index.php?page=beranda<?= $wilayahParam ?>" class="py-2 px-1.5 xl:px-2 rounded-xl text-xs xl:text-[13px] font-bold transition-all flex items-center justify-center gap-1.5 text-center truncate <?= navActive('beranda', $currentPage) ?>">
                    <i class="fa-solid fa-house text-xs shrink-0"></i>
                    <span class="truncate">Beranda</span>
                </a>
                <a href="index.php?page=peta<?= $wilayahParam ?>" class="py-2 px-1.5 xl:px-2 rounded-xl text-xs xl:text-[13px] font-bold transition-all flex items-center justify-center gap-1.5 text-center truncate <?= navActive('peta', $currentPage) ?>">
                    <i class="fa-solid fa-map-location-dot text-xs shrink-0"></i>
                    <span class="truncate">Peta Wisata</span>
                </a>
                <a href="index.php?page=analisis<?= $wilayahParam ?>" class="py-2 px-1.5 xl:px-2 rounded-xl text-xs xl:text-[13px] font-bold transition-all flex items-center justify-center gap-1.5 text-center truncate <?= navActive('analisis', $currentPage) ?>">
                    <i class="fa-solid fa-chart-line text-xs shrink-0"></i>
                    <span class="truncate">Analisis BPS</span>
                </a>
                <a href="index.php?page=rekomendasi<?= $wilayahParam ?>" class="py-2 px-1.5 xl:px-2 rounded-xl text-xs xl:text-[13px] font-bold transition-all flex items-center justify-center gap-1.5 text-center truncate <?= navActive('rekomendasi', $currentPage) ?>">
                    <i class="fa-solid fa-ranking-star text-xs shrink-0"></i>
                    <span class="truncate">SPK TOPSIS</span>
                </a>
                <a href="index.php?page=tambah<?= $wilayahParam ?>" class="py-2 px-1.5 xl:px-2 rounded-xl text-xs xl:text-[13px] font-bold transition-all flex items-center justify-center gap-1.5 text-center truncate <?= navActive('tambah', $currentPage) ?>">
                    <i class="fa-solid fa-circle-plus text-xs shrink-0"></i>
                    <span class="truncate">Usulkan</span>
                </a>
                <a href="index.php?page=verifikasi<?= $wilayahParam ?>" class="py-2 px-1.5 xl:px-2 rounded-xl text-xs xl:text-[13px] font-bold transition-all flex items-center justify-center gap-1 text-center truncate <?= navActive('verifikasi', $currentPage) ?>">
                    <i class="fa-solid fa-shield-halved text-xs shrink-0"></i>
                    <span class="truncate">Moderasi</span>
                    <?php if ($pendingSubmissions > 0): ?>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-purple-600 text-white animate-pulse shrink-0">
                        <?= $pendingSubmissions ?>
                    </span>
                    <?php endif; ?>
                </a>
            </nav>

            <!-- Aksi Kanan & Profil Pengembang (Ukuran Seragam, Rapi & Elegan) -->
            <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                
                <!-- Lencana Wilayah Resmi (Kepulauan Bangka Belitung) -->
                <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-900 text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-gem text-emerald-600 text-xs"></i>
                    <span>Kep. Bangka Belitung</span>
                </div>

                <!-- Tombol Kode QR Letak Geografis Provinsi Kep. Bangka Belitung -->
                <button onclick="openGenericQrModal()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-ocean-50 hover:bg-ocean-100 text-ocean-700 flex items-center justify-center border border-ocean-200 transition-all shadow-xs cursor-pointer" title="Kode QR Letak Geografis Provinsi Kepulauan Bangka Belitung (Buka Google Maps)">
                    <i class="fa-solid fa-qrcode text-xs sm:text-sm"></i>
                </button>

                <!-- Sakelar Tema Gelap / Terang (Dark / Light Mode) -->
                <button id="themeToggleBtn" onclick="toggleAppTheme()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 flex items-center justify-center border border-slate-200 transition-all shadow-xs cursor-pointer" title="Ganti Mode Tampilan (Gelap / Terang)">
                    <i id="themeIconMoon" class="fa-solid fa-moon text-sm text-slate-600"></i>
                    <i id="themeIconSun" class="fa-solid fa-sun text-sm text-amber-400 hidden"></i>
                </button>

                <!-- Tombol Buku Panduan Pengguna -->
                <button onclick="openGuideModal()" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 hover:text-ocean-700 flex items-center justify-center border border-slate-200 transition-all shadow-xs cursor-pointer" title="Buku Panduan, Sumber Data &amp; Metodologi">
                    <i class="fa-solid fa-circle-question text-ocean-600 text-xs sm:text-sm"></i>
                </button>

                <!-- Avatar Pembuat Web (Fathurrahman Safari Lubis) -->
                <button onclick="openGuideModal('gtab-policy')" class="flex items-center justify-center p-0.5 rounded-full bg-slate-100 hover:bg-ocean-50 border border-slate-200 hover:border-ocean-300 transition-all cursor-pointer group" title="Dibuat oleh: Fathurrahman Safari Lubis (NIM: 2411500025)">
                    <img src="assets/img/fathurrahman.jpg" alt="Fathurrahman Safari Lubis" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover object-top border border-slate-200 group-hover:border-ocean-500 shadow-xs group-hover:scale-105 transition-transform">
                </button>
            </div>

        </div>
    </div>
</header>

<!-- ============================================================== -->
<!-- NAVIGASI BAWAH SELULER (FIXED MOBILE APP NAVIGATION BAR)       -->
<!-- ============================================================== -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-slate-200 shadow-2xl py-1 px-2 flex items-center justify-around safe-area-bottom">
    <a href="index.php?page=beranda<?= $wilayahParam ?>" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= in_array($currentPage, ['beranda', 'dashboard']) ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-house text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Beranda</span>
    </a>
    <a href="index.php?page=peta<?= $wilayahParam ?>" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'peta' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-map-location-dot text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Peta</span>
    </a>
    <a href="index.php?page=analisis<?= $wilayahParam ?>" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'analisis' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-chart-line text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">Analisis</span>
    </a>
    <a href="index.php?page=rekomendasi<?= $wilayahParam ?>" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'rekomendasi' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
        <i class="fa-solid fa-ranking-star text-base mb-0.5"></i>
        <span class="text-[10px] tracking-tight leading-tight">SPK</span>
    </a>
    <a href="index.php?page=verifikasi<?= $wilayahParam ?>" class="relative flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all <?= $currentPage == 'verifikasi' ? 'text-ocean-600 font-extrabold' : 'text-slate-500 hover:text-slate-800' ?>">
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
// ==============================================================
// LOGIKA PEMILIH CAKUPAN WILAYAH (REGIONAL SCOPE SWITCHER)
// ==============================================================
function toggleWilayahScopeMenu(e) {
    if (e) e.stopPropagation();
    const menu = document.getElementById('wilayahScopeMenu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

function alertProvinsiTahapDua(namaProv) {
    const menu = document.getElementById('wilayahScopeMenu');
    if (menu) menu.classList.add('hidden');
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Provinsi ' + namaProv,
            html: '<p class="text-xs sm:text-sm text-slate-600">Modul provinsi ini berstatus <strong>Siap Integrasi Data (Tahap 2)</strong>. Seiring pengisian basis data oleh administrator, analitik spasial, data BPS, dan algoritma SPK TOPSIS akan terhitung secara otomatis di portal nasional.</p>',
            icon: 'info',
            confirmButtonText: 'Kembali ke Skala Nasional',
            showCancelButton: true,
            cancelButtonText: 'Buka Percontohan Babel',
            confirmButtonColor: '#0284c7',
            cancelButtonColor: '#059669',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-slate-200'
            }
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = 'index.php?page=<?= htmlspecialchars($currentPage) ?>&wilayah=nasional';
            } else if (res.dismiss === Swal.DismissReason.cancel) {
                window.location.href = 'index.php?page=<?= htmlspecialchars($currentPage) ?>&wilayah=babel';
            }
        });
    } else {
        alert('Provinsi ' + namaProv + ' berstatus Siap Integrasi Data (Tahap 2). Saat ini Anda dapat menjelajahi wilayah percontohan Bangka Belitung (875 objek) atau tinjauan makro Skala Nasional.');
    }
}

document.addEventListener('click', function(e) {
    const container = document.getElementById('wilayahScopeContainer');
    const menu = document.getElementById('wilayahScopeMenu');
    if (container && menu && !container.contains(e.target)) {
        menu.classList.add('hidden');
    }
});

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
