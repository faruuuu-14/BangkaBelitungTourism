<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Pengendali Tampilan Utama Beranda / Master View Controller)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Pengontrol utama (Main Controller & View Loader) untuk fitur Beranda Utama.
 * Mengeksekusi query agregasi data dari basis data MySQL (db_pariwisata_babel),
 * seperti total metrik 3A (Daya Tarik & Amenitas), data kunjungan resmi BPS 2026,
 * sebaran per kabupaten/kota, serta 5 destinasi terpopuler.
 * Kemudian merender komponen-komponen modular berurutan:
 * 1. banner_utama.php       (Hero Banner Latar Belakang Foto Lengkuas Penuh)
 * 2. panduan_pengguna.php   (Panduan Ringkas Pengguna Sistem)
 * 3. kartu_metrik.php       (4 Indikator Kunci Pariwisata Babel)
 * 4. galeri_wilayah.php     (Galeri 12 Objek Berimbang 7 Kabupaten/Kota & Budaya)
 * 5. grafik_sebaran.php     (Grafik Batang Komparasi Sebaran Wilayah)
 * 6. destinasi_populer.php  (5 Destinasi Terpopuler dengan Foto)
 * 7. modal_dialog.php       (Modal Lightbox Foto Layar Penuh)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (Master Router) saat ?page=beranda atau ?page=dashboard
 * - Memanggil basis data: app/database.php (fungsi getDbConnection & getDestinationPhoto)
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menerapkan arsitektur kode modular berbasis komponen (Component-Based Architecture),
 * memisahkan data fetching dari template presentasi, dan menjamin konsistensi
 * tata letak IMK (max-w-7xl, tanpa max-w-full).
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/database.php';
$pdo = getDbConnection();

// --------------------------------------------------------------------------
// 1. QUERY METRIK TERKURASI SISTEM
// Menghitung total objek, daya tarik alam/buatan/budaya, hotel, restoran, dan ulasan
// --------------------------------------------------------------------------
$stats = $pdo->query("
    SELECT 
        COUNT(*) as total_semua,
        SUM(CASE WHEN kelompok = 'Daya Tarik' THEN 1 ELSE 0 END) as total_daya_tarik,
        SUM(CASE WHEN sub_kategori = 'Alam' THEN 1 ELSE 0 END) as total_alam,
        SUM(CASE WHEN sub_kategori = 'Buatan' THEN 1 ELSE 0 END) as total_buatan,
        SUM(CASE WHEN sub_kategori = 'Kebudayaan' THEN 1 ELSE 0 END) as total_budaya,
        SUM(CASE WHEN sub_kategori = 'Hotel' THEN 1 ELSE 0 END) as total_hotel,
        SUM(CASE WHEN sub_kategori = 'Restoran' THEN 1 ELSE 0 END) as total_restoran,
        SUM(CASE WHEN kelompok = 'Amenitas' THEN 1 ELSE 0 END) as total_amenitas,
        AVG(NULLIF(rating, 0)) as avg_rating,
        SUM(ulasan) as total_ulasan
    FROM tempat_wisata
")->fetch();

// --------------------------------------------------------------------------
// 2. QUERY DATA KUNJUNGAN BPS RESMI TAHUN TERKINI (2026)
// --------------------------------------------------------------------------
$bpsTotal = 0;
try {
    $bpsRow = $pdo->query("SELECT SUM(kunjungan) as total FROM kunjungan_bps WHERE tahun = 2026")->fetch();
    $bpsTotal = $bpsRow['total'] ?? 0;
} catch (Exception $e) {
    $bpsTotal = 0;
}

// --------------------------------------------------------------------------
// 3. QUERY KOMPARASI SEBARAN FASILITAS PER 7 KABUPATEN/KOTA
// --------------------------------------------------------------------------
$perKabupaten = $pdo->query("
    SELECT 
        kabupaten,
        SUM(CASE WHEN sub_kategori = 'Alam' THEN 1 ELSE 0 END) as alam,
        SUM(CASE WHEN sub_kategori = 'Buatan' THEN 1 ELSE 0 END) as buatan,
        SUM(CASE WHEN sub_kategori = 'Kebudayaan' THEN 1 ELSE 0 END) as budaya,
        SUM(CASE WHEN sub_kategori = 'Hotel' THEN 1 ELSE 0 END) as hotel,
        SUM(CASE WHEN sub_kategori = 'Restoran' THEN 1 ELSE 0 END) as restoran,
        COUNT(*) as total
    FROM tempat_wisata
    GROUP BY kabupaten
    ORDER BY total DESC
")->fetchAll();

// --------------------------------------------------------------------------
// 4. QUERY 5 DESTINASI WISATA TERPOPULER (ULASAN & RATING TERTINGGI)
// --------------------------------------------------------------------------
$topDestinasi = $pdo->query("
    SELECT nama, kabupaten, kecamatan, sub_kategori, rating, ulasan
    FROM tempat_wisata
    WHERE kelompok = 'Daya Tarik'
    ORDER BY ulasan DESC
    LIMIT 5
")->fetchAll();

// --------------------------------------------------------------------------
// 5. QUERY DATA OBJEK WISATA UNTUK PENCARIAN CEPAT INSTAN DI HERO BANNER
// --------------------------------------------------------------------------
$searchPlaces = $pdo->query("
    SELECT id, nama, kabupaten, kecamatan, sub_kategori, rating, ulasan
    FROM tempat_wisata
    WHERE status_verifikasi = 'Resmi'
    ORDER BY ulasan DESC
    LIMIT 150
")->fetchAll();
$currentWilayah = getCurrentWilayah();
$isNasional = ($currentWilayah === 'nasional');
?>

<!-- ============================================================== -->
<!-- STRUKTUR TATA LETAK UTAMA BERANDA (STANDAR KONSISTENSI IMK)    -->
<!-- ============================================================== -->
<main class="flex-grow w-full max-w-[1720px] mx-auto px-3 sm:px-6 lg:px-10 py-3 sm:py-8 pb-20 lg:pb-8 min-w-0 overflow-hidden space-y-3.5 sm:space-y-8">

    <!-- 1. Hero Banner Utama (Pencarian Cepat 875 Objek Babel) -->
    <?php include __DIR__ . '/banner_utama.php'; ?>

    <!-- 2. Kartu Metrik Kunci Pariwisata Babel (875 Objek & BPS 2026) -->
    <?php include __DIR__ . '/kartu_metrik.php'; ?>

    <!-- 3. Grafik Komparasi Sebaran Fasilitas 7 Kabupaten/Kota -->
    <?php include __DIR__ . '/grafik_sebaran.php'; ?>

    <!-- Modal Dialog Lightbox -->
    <?php include __DIR__ . '/modal_dialog.php'; ?>

</main>

