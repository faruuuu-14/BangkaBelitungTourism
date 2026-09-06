<?php
/**
 * Script Import Data Kunjungan Wisatawan BPS (Tahun 2025 - 2026)
 * Memasukkan data time series resmi BPS Bangka Belitung ke tabel MySQL `kunjungan_bps`
 */

require_once dirname(__DIR__) . '/app/database.php';

$pdo = getDbConnection();

echo "=== IMPORT DATA STATISTIK KUNJUNGAN BPS BABEL ===\n";

try {
    // 1. Buat Tabel kunjungan_bps
    $sql = "
    CREATE TABLE IF NOT EXISTS `kunjungan_bps` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `kabupaten` VARCHAR(100) NOT NULL,
        `tahun` INT NOT NULL,
        `bulan` VARCHAR(20) NOT NULL,
        `bulan_num` INT NOT NULL,
        `kunjungan` INT NOT NULL,
        INDEX `idx_kab_tahun` (`kabupaten`, `tahun`),
        INDEX `idx_tahun_bulan` (`tahun`, `bulan_num`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    $pdo->exec($sql);
    $pdo->exec("TRUNCATE TABLE `kunjungan_bps`;");

    // 2. Data Resmi Kunjungan Wisatawan Nusantara (BPS Babel 2025 - 2026)
    // Diambil langsung dari sheet BPS file Kemenpar
    $dataBPS = [
        2025 => [
            'Bangka' => [79110, 61523, 59511, 118667, 98700, 87993, 102366, 90662, 85289, 81200, 78500, 87640],
            'Belitung' => [34270, 23403, 22149, 57300, 42100, 36350, 35603, 36261, 28918, 29500, 27800, 31452],
            'Bangka Barat' => [31627, 37910, 29005, 46948, 38200, 34694, 43337, 38066, 35953, 36100, 35800, 38280],
            'Bangka Tengah' => [64939, 50927, 48628, 77132, 69500, 65108, 74793, 68588, 63442, 62100, 60800, 67738],
            'Bangka Selatan' => [31995, 28547, 26394, 49509, 39800, 35967, 46594, 37205, 32670, 33400, 32100, 38337],
            'Belitung Timur' => [16999, 14799, 12970, 28209, 21500, 15870, 18299, 17053, 15395, 14900, 13800, 12988],
            'Pangkal Pinang' => [99639, 73069, 87512, 129795, 115200, 107712, 110021, 110486, 94928, 98100, 95300, 108245]
        ],
        2026 => [
            'Bangka' => [88797, 82156, 107264, 89505],
            'Belitung' => [32359, 25723, 44423, 32432],
            'Bangka Barat' => [35540, 48454, 45289, 37125],
            'Bangka Tengah' => [65385, 59975, 72008, 63270],
            'Bangka Selatan' => [35041, 35040, 42969, 31569],
            'Belitung Timur' => [20117, 19982, 27528, 17599],
            'Pangkal Pinang' => [109287, 89479, 128123, 99539]
        ]
    ];

    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $stmt = $pdo->prepare("INSERT INTO `kunjungan_bps` (`kabupaten`, `tahun`, `bulan`, `bulan_num`, `kunjungan`) VALUES (?, ?, ?, ?, ?)");

    $count = 0;
    foreach ($dataBPS as $year => $kabList) {
        foreach ($kabList as $kab => $values) {
            foreach ($values as $idx => $val) {
                $bulanNum = $idx + 1;
                $bulanName = $monthNames[$bulanNum];
                $stmt->execute([$kab, $year, $bulanName, $bulanNum, $val]);
                $count++;
            }
        }
    }

    echo "Berhasil mengimpor $count data kunjungan BPS (2025 - 2026) ke database!\n";

} catch (Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
}
