<?php
/**
 * Script Inisialisasi Database & Import Data Kurasi Pariwisata Bangka Belitung
 * Menjalankan pembuatan tabel dan memasukkan ~800 data resmi/terkurasi bebas noise.
 */

$host = "127.0.0.1";
$port = "3306";
$user = "root";
$pass = "";
$dbName = "db_babel_tourism";

echo "=== INISIALISASI DATABASE PARIWISATA BANGKA BELITUNG ===\n";

try {
    // 1. Buat Database jika belum ada
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "[1/4] Menyiapkan database '$dbName'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE `$dbName`;");

    // 2. Buat Tabel tempat_wisata
    echo "[2/4] Menyiapkan tabel 'tempat_wisata'...\n";
    $tableSql = "
    CREATE TABLE IF NOT EXISTS `tempat_wisata` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nama` VARCHAR(255) NOT NULL,
        `kabupaten` VARCHAR(100) NOT NULL,
        `kecamatan` VARCHAR(100) NOT NULL,
        `kelompok` ENUM('Daya Tarik', 'Amenitas') NOT NULL,
        `sub_kategori` VARCHAR(50) NOT NULL,
        `rating` DECIMAL(3,1) DEFAULT 0.0,
        `ulasan` INT DEFAULT 0,
        `latitude` DECIMAL(11,7) NULL,
        `longitude` DECIMAL(11,7) NULL,
        `status_verifikasi` ENUM('Resmi', 'Usulan Baru') DEFAULT 'Resmi',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX `idx_kabupaten` (`kabupaten`),
        INDEX `idx_kecamatan` (`kecamatan`),
        INDEX `idx_kelompok` (`kelompok`),
        INDEX `idx_sub_kategori` (`sub_kategori`),
        INDEX `idx_status` (`status_verifikasi`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($tableSql);

    // Kosongkan tabel agar bersih (fresh import)
    $pdo->exec("TRUNCATE TABLE `tempat_wisata`;");

    // 3. Baca dan Kurasi Data CSV
    $csvFile = dirname(__DIR__) . "/data/wisata_babel_master.csv";
    if (!file_exists($csvFile)) {
        die("[ERROR] File master CSV tidak ditemukan di: $csvFile\n");
    }

    echo "[3/4] Membaca dan mengurasi data dari CSV...\n";
    $handle = fopen($csvFile, "r");
    $headers = fgetcsv($handle); // baris header

    $stmt = $pdo->prepare("
        INSERT INTO `tempat_wisata` 
        (`nama`, `kabupaten`, `kecamatan`, `kelompok`, `sub_kategori`, `rating`, `ulasan`, `latitude`, `longitude`, `status_verifikasi`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Resmi')
    ");

    $totalMasuk = 0;
    $counts = [
        "Alam" => 0,
        "Buatan" => 0,
        "Kebudayaan" => 0,
        "Hotel" => 0,
        "Restoran" => 0
    ];

    $seen = [];

    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) < 10) continue;

        $kabupaten = trim($row[0]);
        $kecamatan = trim($row[1]);
        $nama      = trim($row[2]);
        $kelompok  = trim($row[4]);
        $subKat    = trim($row[5]);
        $rating    = floatval($row[6] ?? 0);
        $ulasan    = intval($row[7] ?? 0);
        $lat       = floatval($row[8] ?? 0);
        $lng       = floatval($row[9] ?? 0);

        // Filter 1: Buang 'Lainnya'
        if ($subKat === "Lainnya" || empty($subKat)) continue;

        // Filter 2: Koordinat Geografis Wilayah Kepulauan Bangka Belitung
        // Latitude antara -4.0 s.d -0.8, Longitude antara 105.0 s.d 109.5
        if ($lat < -4.0 || $lat > -0.8 || $lng < 105.0 || $lng > 109.5) {
            continue;
        }

        // Filter 3: Kredibilitas Amenitas (buang warung/kosan kecil acak)
        if ($subKat === "Hotel" && $ulasan < 10) continue;
        if ($subKat === "Restoran" && $ulasan < 25) continue;

        // Filter 4: Deduplikasi
        $dedupKey = strtolower($nama) . "_" . strtolower($kabupaten);
        if (isset($seen[$dedupKey])) continue;
        $seen[$dedupKey] = true;

        // Pastikan kelompok sesuai
        if (in_array($subKat, ["Hotel", "Restoran"])) {
            $kelompok = "Amenitas";
        } else {
            $kelompok = "Daya Tarik";
        }

        $stmt->execute([
            $nama,
            $kabupaten,
            $kecamatan,
            $kelompok,
            $subKat,
            $rating,
            $ulasan,
            $lat,
            $lng
        ]);

        $totalMasuk++;
        if (isset($counts[$subKat])) {
            $counts[$subKat]++;
        }
    }

    fclose($handle);

    echo "[4/4] Selesai!\n";
    echo "====================================================\n";
    echo "TOTAL DATA TERKURASI BERHASIL DI-IMPORT: $totalMasuk\n";
    echo "====================================================\n";
    foreach ($counts as $kat => $jml) {
        printf("  - %-15s : %d tempat\n", $kat, $jml);
    }
    echo "====================================================\n";
    echo "Database 'db_babel_tourism' sudah siap digunakan!\n";

} catch (Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
}
