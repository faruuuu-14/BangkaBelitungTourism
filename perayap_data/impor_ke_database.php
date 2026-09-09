<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      impor_ke_database.php
 * @folder    perayap_data/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Skrip Impor Data Hasil Crawling ke MySQL)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Skrip jembatan otomatis (ETL Pipeline / Loader) yang membaca file hasil crawling
 * (data_wisata_babel.json atau CSV) dari folder output perayap, kemudian melakukan
 * sanitasi tipe data dan memasukkannya (INSERT / ON DUPLICATE KEY UPDATE) ke tabel
 * `tempat_wisata` pada database MySQL `db_pariwisata_babel`.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Membaca file: perayap_data/output/data_wisata_babel.json (atau data/data_wisata_babel.json)
 * - Menghubungkan ke: app/database.php (fungsi getDbConnection)
 * - Menulis ke tabel: tempat_wisata di database MySQL db_pariwisata_babel
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memastikan data yang dirayap dari Google Maps langsung dapat diakses oleh
 * seluruh fitur web (Peta WebGIS, Analisis Korelasi Pearson, dan SPK TOPSIS)
 * tanpa perlu input manual satu per satu ke phpMyAdmin.
 * ==============================================================================
 */

require_once __DIR__ . '/../app/database.php';

echo "\n=======================================================\n";
echo "📥 SKRIP IMPOR DATA PERAYAPAN KE DATABASE MYSQL\n";
echo "   Target Database: db_pariwisata_babel\n";
echo "=======================================================\n";

$pdo = getDbConnection();

// Cari file sumber JSON (prioritas di output perayap, lalu di data/)
$jsonPath = __DIR__ . '/output/data_wisata_babel.json';
if (!file_exists($jsonPath)) {
    $jsonPath = __DIR__ . '/../data/data_wisata_babel.json';
}

if (!file_exists($jsonPath)) {
    die("[GAGAL] File hasil perayapan data tidak ditemukan. Jalankan 'python jalankan_crawler.py' terlebih dahulu!\n");
}

echo "[INFO] Membaca file sumber: {$jsonPath}...\n";
$jsonContent = file_get_contents($jsonPath);
$data = json_decode($jsonContent, true);

if (!is_array($data) || empty($data)) {
    die("[GAGAL] Format file JSON tidak valid atau data kosong.\n");
}

echo "[INFO] Ditemukan " . count($data) . " entri data wisata & amenitas.\n";

// Query SQL Prepared Statement untuk performa tinggi & keamanan dari SQL Injection
$sql = "
    INSERT INTO tempat_wisata 
        (nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude, status_verifikasi)
    VALUES 
        (:nama, :kabupaten, :kecamatan, :kelompok, :sub_kategori, :rating, :ulasan, :latitude, :longitude, :status_verifikasi)
    ON DUPLICATE KEY UPDATE
        kelompok = VALUES(kelompok),
        sub_kategori = VALUES(sub_kategori),
        rating = VALUES(rating),
        ulasan = VALUES(ulasan),
        latitude = VALUES(latitude),
        longitude = VALUES(longitude)
";

$stmt = $pdo->prepare($sql);

$berhasil = 0;
$diabaikan = 0;

$pdo->beginTransaction();

try {
    foreach ($data as $item) {
        $nama = trim($item['nama'] ?? '');
        if (empty($nama)) {
            $diabaikan++;
            continue;
        }

        $stmt->execute([
            ':nama'              => $nama,
            ':kabupaten'         => $item['kabupaten'] ?? 'Kabupaten Bangka',
            ':kecamatan'         => $item['kecamatan'] ?? 'Sungailiat',
            ':kelompok'          => $item['kelompok'] ?? 'Daya Tarik',
            ':sub_kategori'      => $item['sub_kategori'] ?? 'Alam',
            ':rating'            => floatval($item['rating'] ?? 0),
            ':ulasan'            => intval($item['ulasan'] ?? 0),
            ':latitude'          => floatval($item['latitude'] ?? 0),
            ':longitude'         => floatval($item['longitude'] ?? 0),
            ':status_verifikasi' => $item['status_verifikasi'] ?? 'Resmi'
        ]);
        $berhasil++;
    }
    $pdo->commit();
    echo "[SUKSES] Berhasil memasukkan/memperbarui {$berhasil} entri ke database MySQL!\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "[GAGAL] Terjadi kesalahan database: " . $e->getMessage() . "\n";
}

// Rekapitulasi akhir dari database
$totalDb = $pdo->query("SELECT COUNT(*) FROM tempat_wisata")->fetchColumn();
echo "\n=======================================================\n";
echo "📊 STATUS BASIS DATA SAAT INI:\n";
echo "Total Destinasi & Amenitas di Database: {$totalDb} tempat.\n";
echo "=======================================================\n\n";
