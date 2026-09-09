<?php
/**
 * ====================================================================================================
 * NAMA FILE : index.php
 * LOKASI    : C:\laragon\www\BangkaBelitungTourism\index.php
 * PENULIS   : Fathurrahman Safari Lubis (Faru) - NIM: 2411500025 (ISB Atma Luhur Pangkalpinang)
 * DESKRIPSI : Berkas Pengendali Utama (Master Router / Front Controller) Aplikasi Web Analisis & SPK
 *             Pariwisata Kepulauan Bangka Belitung. Mengelola perpindahan rute antar modul secara dinamis.
 * ALUR/RELASI:
 *   1. Menerima query parameter HTTP GET `?page=...` dari peramban pengguna.
 *   2. Memverifikasi kunci halaman pada daftar putih (whitelist) untuk mencegah Local File Inclusion (LFI).
 *   3. Memuat komponen tata letak pembuka: `components/header.php` dan `components/navbar.php`.
 *   4. Mengarahkan muatan konten ke berkas modul controller `views/{nama_modul}/index.php`.
 *   5. Menutup dokumen dengan menyertakan footer: `components/footer.php`.
 * TUJUAN    : Memisahkan tata letak global dengan logika per modul agar kode rapi, modular, aman, dan
 *             sangat mudah dirawat saat pengujian maupun presentasi sidang skripsi.
 * ====================================================================================================
 */

// 1. Tangkap parameter rute halaman dari URL (default ke 'beranda')
$page = isset($_GET['page']) ? trim((string)$_GET['page']) : 'beranda';

// 2. Daftar putih (whitelist) modul yang sah dan diizinkan dalam sistem
$allowedPages = [
    'beranda' => [
        'title' => 'Beranda Utama & Statistik | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/beranda/index.php'
    ],
    'dashboard' => [
        'title' => 'Beranda Utama & Statistik | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/beranda/index.php' // Alias ramah pengguna
    ],
    'peta' => [
        'title' => 'Peta WebGIS Spasial Interaktif | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/peta/index.php'
    ],
    'analisis' => [
        'title' => 'Pusat Analisis Statistik & Data BPS | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/analisis/index.php'
    ],
    'rekomendasi' => [
        'title' => 'Sistem Pendukung Keputusan TOPSIS | Pariwisata Bangka Belitung',
        'file'  => __DIR__ . '/views/rekomendasi/index.php'
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

// 6. Muat Bilah Navigasi Atas (Navbar Global)
include __DIR__ . '/components/navbar.php';

// 7. Muat Konten Halaman Sesuai Rute Terpilih
if (file_exists($targetView)) {
    include $targetView;
} else {
    echo "
    <main class='max-w-7xl mx-auto px-4 py-20 text-center'>
        <div class='bg-rose-50 border border-rose-200 rounded-3xl p-10 max-w-lg mx-auto shadow-sm'>
            <div class='w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold'>!</div>
            <h1 class='text-xl font-bold text-gray-900 mb-2'>Modul Belum Tersedia</h1>
            <p class='text-sm text-gray-600 mb-6'>Berkas pengendali untuk modul <code>" . htmlspecialchars($page) . "</code> tidak ditemukan di peladen.</p>
            <a href='index.php?page=beranda' class='inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow transition'>
                Kembali ke Beranda
            </a>
        </div>
    </main>";
}

// 8. Muat Footer Dokumen (Hak cipta, tautan universitas, skrip penutup)
include __DIR__ . '/components/footer.php';

