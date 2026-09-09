<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/peta/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Pengendali Utama Modul Peta Spasial WebGIS)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Pengontrol utama (Master View Controller) untuk fitur Peta Spasial WebGIS.
 * Mengambil seluruh data titik pariwisata terkurasi (875 POI resmi dengan koordinat
 * GPS valid), menyusun daftar kabupaten unik untuk filter, lalu memuat seluruh
 * komponen modular antarmuka peta:
 * 1. panel_filter.php      (Bilah Kendali Filter Kategori, Wilayah & Mode)
 * 2. area_peta.php         (Kanvas Peta Leaflet & Status Peta)
 * 3. perencana_rute.php    (Panel Geser Perencana Rute Wisata Cerdas)
 * 4. legenda_informasi.php (Dialog Modal Legenda & Buffer 5 km)
 * 5. skrip_peta.php        (Logika JavaScript Peta, Clustering, & Haversine)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (Master Router) saat ?page=peta
 * - Membaca basis data: app/database.php (tabel tempat_wisata)
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menggabungkan kekuatan WebGIS modern dengan arsitektur modular yang rapi,
 * bersih, dan mudah dikembangkan di masa depan.
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/database.php';
require_once __DIR__ . '/../../app/statistics.php';

$pdo = getDbConnection();

// Ambil seluruh data titik resmi untuk peta
$sql = "
    SELECT id, nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude
    FROM tempat_wisata
    WHERE latitude IS NOT NULL AND longitude IS NOT NULL AND status_verifikasi = 'Resmi'
    ORDER BY ulasan DESC
";
$places = $pdo->query($sql)->fetchAll();

// Daftar Kabupaten unik untuk dropdown filter
$kabList = array_unique(array_column($places, 'kabupaten'));
sort($kabList);

// Simpul Transportasi (Bandara & Pelabuhan) & Statistik Aksesibilitas
$hubs = getTransportHubs();
$catchmentStats = getAccessibilityCatchmentStats();
?>

<!-- ============================================================== -->
<!-- STRUKTUR TATA LETAK UTAMA MODUL PETA SPASIAL WEBGIS            -->
<!-- ============================================================== -->
<main class="flex-grow flex flex-col h-[calc(100dvh-3.5rem)] lg:h-[calc(100dvh-4rem)] pb-14 lg:pb-0 relative overflow-hidden w-full max-w-full">

    <!-- 1. Bilah Filter & Kendali Atas Peta -->
    <?php include __DIR__ . '/panel_filter.php'; ?>

    <!-- 2. Kontainer Kanvas Peta Leaflet -->
    <?php include __DIR__ . '/area_peta.php'; ?>

    <!-- 3. Panel Geser Perencana Rute Wisata Cerdas (Drawer) -->
    <?php include __DIR__ . '/perencana_rute.php'; ?>

    <!-- 4. Modal Legenda Peta & Radius Jangkauan 5 km -->
    <?php include __DIR__ . '/legenda_informasi.php'; ?>

</main>

<!-- 5. Logika JavaScript Leaflet WebGIS & Perhitungan Spasial -->
<?php include __DIR__ . '/skrip_peta.php'; ?>
