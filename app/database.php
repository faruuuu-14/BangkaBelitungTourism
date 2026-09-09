<?php
/**
 * Konfigurasi & Koneksi Database MySQL (PDO)
 * Menggunakan kredensial standar Laragon (Host: 127.0.0.1, User: root, Pass: kosong)
 */

$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1', 'localhost:80']);

if ($isLocalhost) {
    // 1. Kredensial Localhost (Laragon)
    $DB_HOST = "127.0.0.1";
    $DB_PORT = "3306";
    $DB_NAME = "db_babel_tourism";
    $DB_USER = "root";
    $DB_PASS = "";
} else {
    // 2. Kredensial Hosting Online (InfinityFree)
    $DB_HOST = "sql103.infinityfree.com"; 
    $DB_PORT = "3306";
    $DB_NAME = "if0_42843499_tourism";     
    $DB_USER = "if0_42843499";              
    $DB_PASS = "8tyNWDPPJkAkNum";
}

function getDbConnection() {
    global $DB_HOST, $DB_PORT, $DB_NAME, $DB_USER, $DB_PASS;
    
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
        } catch (PDOException $e) {
            die("Gagal terhubung ke database: " . $e->getMessage());
        }
    }
    return $pdo;
}

if (!function_exists('getDestinationPhoto')) {
    /**
     * Mengambil Foto Representatif Otentik Bangka Belitung Berdasarkan Nama & Kategori Objek
     */
    function getDestinationPhoto($nama, $subKategori = '') {
        $n = strtolower($nama ?? '');
        $s = strtolower($subKategori ?? '');

        // 1. Landmark Khusus & Ikonik Babel
        if (str_contains($n, 'lengkuas') || str_contains($n, 'mercusuar')) {
            return 'assets/img/pantai_lengkuas.jpg';
        }
        if (str_contains($n, 'tanjung tinggi') || str_contains($n, 'kelayang') || str_contains($n, 'parai') || str_contains($n, 'granit') || str_contains($n, 'pasir padi') || str_contains($n, 'tanjung pesona') || str_contains($n, 'pantai') || str_contains($n, 'pulau')) {
            return 'assets/img/pantai_tanjung_tinggi.jpg';
        }
        if (str_contains($n, 'laskar pelangi') || str_contains($n, 'gantong') || str_contains($n, 'kata') || str_contains($n, 'sekolah') || str_contains($n, 'andrea')) {
            return 'assets/img/laskar_pelangi_gantong.jpg';
        }
        if (str_contains($n, 'museum') || str_contains($n, 'timah') || str_contains($n, 'sejarah') || str_contains($n, 'benteng') || str_contains($n, 'menumbing') || str_contains($n, 'prasasti') || str_contains($n, 'kota tua')) {
            return 'assets/img/museum_timah_babel.jpg';
        }
        if (str_contains($n, 'cual') || str_contains($n, 'tenun') || str_contains($n, 'kain') || str_contains($n, 'batik') || str_contains($n, 'souvenir') || str_contains($n, 'kerajinan')) {
            return 'assets/img/kain_cual_bangka.jpg';
        }
        if (str_contains($n, 'dambus') || str_contains($n, 'musik') || str_contains($n, 'sanggar') || str_contains($n, 'seni')) {
            return 'assets/img/alat_musik_dambus.jpg';
        }
        if (str_contains($n, 'paksian') || str_contains($n, 'pengantin') || str_contains($n, 'pakaian adat')) {
            return 'assets/img/budaya_tari_paksian.jpg';
        }
        if (str_contains($n, 'campak') || str_contains($n, 'tari') || str_contains($n, 'joget')) {
            return 'assets/img/budaya_tari_campak.jpg';
        }
        if (str_contains($n, 'nganggung') || str_contains($n, 'sepintu') || str_contains($n, 'sedulang') || str_contains($n, 'dulang') || str_contains($n, 'lempah') || str_contains($n, 'otak-otak') || str_contains($n, 'kopi') || str_contains($n, 'makan') || str_contains($n, 'resto')) {
            return 'assets/img/budaya_nganggung.jpg';
        }

        // 2. Kategori Fallback
        if ($s === 'kebudayaan' || str_contains($s, 'budaya') || str_contains($s, 'sejarah')) {
            return 'assets/img/kain_cual_bangka.jpg';
        }
        if ($s === 'restoran' || str_contains($s, 'kuliner')) {
            return 'assets/img/budaya_nganggung.jpg';
        }
        if ($s === 'buatan') {
            return 'assets/img/laskar_pelangi_gantong.jpg';
        }
        if ($s === 'hotel') {
            return 'assets/img/pantai_lengkuas.jpg';
        }

        return 'assets/img/pantai_tanjung_tinggi.jpg';
    }
}
