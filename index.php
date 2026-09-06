<?php
/**
 * Master Router Aplikasi Web Intelijen Pariwisata Bangka Belitung
 */

$page = $_GET['page'] ?? 'dashboard';

// Whitelist halaman yang diizinkan
$allowedPages = [
    'dashboard' => [
        'title' => 'Dashboard Eksekutif | Babel Tourism Hub',
        'file'  => __DIR__ . '/views/dashboard.php'
    ],
    'peta' => [
        'title' => 'Peta WebGIS Interaktif | Babel Tourism Hub',
        'file'  => __DIR__ . '/views/peta.php'
    ],
    'analisis' => [
        'title' => 'Analisis Korelasi Pearson & Gap | Babel Tourism Hub',
        'file'  => __DIR__ . '/views/analisis.php'
    ],
    'rekomendasi' => [
        'title' => 'SPK Rekomendasi TOPSIS | Babel Tourism Hub',
        'file'  => __DIR__ . '/views/rekomendasi.php'
    ],
    'tambah' => [
        'title' => 'Pendaftaran Objek Wisata Baru | Babel Tourism Hub',
        'file'  => __DIR__ . '/views/tambah.php'
    ],
    'verifikasi' => [
        'title' => 'Panel Moderasi Data Dinas | Babel Tourism Hub',
        'file'  => __DIR__ . '/views/verifikasi.php'
    ]
];

if (!array_key_exists($page, $allowedPages)) {
    $page = 'dashboard';
}

$pageTitle = $allowedPages[$page]['title'];
$targetView = $allowedPages[$page]['file'];

// Render Komponen
include __DIR__ . '/components/header.php';
include __DIR__ . '/components/navbar.php';

if (file_exists($targetView)) {
    include $targetView;
} else {
    echo "<div class='p-12 text-center text-rose-500'>Halaman tidak ditemukan.</div>";
}

include __DIR__ . '/components/footer.php';
