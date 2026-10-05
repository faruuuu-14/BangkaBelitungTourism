<?php
/**
 * ====================================================================================================
 * NAMA FILE : index.php
 * LOKASI    : C:\laragon\www\BangkaBelitungTourism\index.php
 * PENULIS   : Fathurrahman Safari Lubis (Faru) - NIM: 2411500025 (ISB Atma Luhur Pangkalpinang)
 * DESKRIPSI : Berkas Pengendali Utama (Master Router / Front Controller) Platform SPKS Intelijen
 *             Pariwisata Kepulauan Bangka Belitung. Mengelola perpindahan rute antar 3 pilar novelty
 *             (Smart Itinerary, Sentiment TOPSIS, Radar Bisnis SMCE, dan Peta WebGIS).
 * ====================================================================================================
 */

// 1. Tangkap parameter rute halaman dari URL (default ke 'beranda')
$page = isset($_GET['page']) ? trim((string)$_GET['page']) : 'beranda';

// 2. Daftar putih (whitelist) modul yang sah dan diizinkan dalam sistem
$allowedPages = [
    'beranda' => [
        'title' => 'Beranda & Pusat Intelijen | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/beranda/index.php'
    ],
    'dashboard' => [
        'title' => 'Beranda & Pusat Intelijen | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/beranda/index.php' // Alias
    ],
    'itinerary' => [
        'title' => 'Smart Itinerary Optimizer | Rute Wisata Cerdas Bangka Belitung',
        'file'  => __DIR__ . '/views/smart_itinerary/index.php'
    ],
    'rekomendasi' => [
        'title' => 'SPK TOPSIS Sentimen Ulasan | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/sentimen_topsis/index.php'
    ],
    'radar_bisnis' => [
        'title' => 'Radar Potensi Bisnis & UMKM | Evaluasi Kelayakan Spasial SMCE',
        'file'  => __DIR__ . '/views/radar_bisnis/index.php'
    ],
    'peta' => [
        'title' => 'Peta WebGIS Spasial Interaktif | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/peta_eksplorasi/index.php'
    ],
    // Kompatibilitas mundur (Legacy Aliases)
    'analisis' => [
        'title' => 'SPK TOPSIS Sentimen Ulasan | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/sentimen_topsis/index.php'
    ],
    'tambah' => [
        'title' => 'Pendaftaran Objek Wisata Baru | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/tambah/index.php'
    ],
    'verifikasi' => [
        'title' => 'Panel Moderasi Data Dinas | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/verifikasi/index.php'
    ]
];

// 3. Sanitasi rute: jika parameter tidak valid, kembalikan ke beranda
if (!array_key_exists($page, $allowedPages)) {
    $page = 'beranda';
}

// 4. Ambil konfigurasi judul halaman dan path berkas sasaran
$pageTitle  = $allowedPages[$page]['title'];
$targetView = $allowedPages[$page]['file'];

// 5. Muat Header Dokumen (HTML head, Tailwind CSS, Font, Leaflet, Chart.js)
include __DIR__ . '/components/header.php';

// 6. Muat Bilah Navigasi Atas (Top Header Navbar)
include __DIR__ . '/components/navbar.php';

// 7. Kontainer Utama Aplikasi (Mobile Responsive Padding)
echo "<main class='max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 pt-4 pb-24 md:pb-8'>";
if (file_exists($targetView)) {
    include $targetView;
} else {
    echo "
    <div class='py-20 text-center'>
        <div class='bg-rose-50 border border-rose-200 rounded-3xl p-10 max-w-lg mx-auto shadow-sm'>
            <div class='w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold'>!</div>
            <h1 class='text-xl font-bold text-gray-900 mb-2'>Modul Sedang Dikembangkan</h1>
            <p class='text-sm text-gray-600 mb-6'>Berkas pengendali untuk modul <code>" . htmlspecialchars($page) . "</code> sedang dalam pembaruan.</p>
            <a href='index.php?page=beranda' class='inline-flex items-center px-5 py-2.5 bg-ocean-600 hover:bg-ocean-700 text-white text-sm font-semibold rounded-xl shadow transition'>
                Kembali ke Beranda
            </a>
        </div>
    </div>";
}
echo "</main>";

// 8. Muat Navigasi Bawah Seluler (Fixed Bottom App Bar di Mobile)
include __DIR__ . '/components/bottom_nav.php';

// 9. Muat Footer Dokumen (Modal QR, Guide Modal, dan Skrip Penutup)
include __DIR__ . '/components/footer.php';
