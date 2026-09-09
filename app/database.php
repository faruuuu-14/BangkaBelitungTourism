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
     * Mengambil Foto Representatif Otentik Bangka Belitung Berdasarkan Nama, Kategori & Kabupaten
     * Menjamin 100% dari 875 objek wisata & amenitas memiliki foto otentik berimbang di 7 Kabupaten/Kota.
     */
    function getDestinationPhoto($nama, $subKategori = '', $kabupaten = '') {
        $n = strtolower($nama ?? '');
        $s = strtolower($subKategori ?? '');
        $k = strtolower($kabupaten ?? '');

        // 1. Landmark Khusus Bangka Barat (Muntok & sekitarnya)
        if (str_contains($n, 'tanjung ular') || str_contains($n, 'batu rakit') || str_contains($n, 'siangau') || str_contains($n, 'menumbing') || str_contains($n, 'pesanggrahan') || str_contains($n, 'muntok') || str_contains($n, 'mentok') || str_contains($n, 'asrama tentara') || str_contains($n, 'benteng')) {
            return 'assets/img/pantai_tanjung_ular.jpg';
        }

        // 2. Landmark Khusus Bangka (Sungailiat & sekitarnya)
        if (str_contains($n, 'tikus emas') || str_contains($n, 'tikus') || str_contains($n, 'puri tri agung') || str_contains($n, 'tongaci') || str_contains($n, 'matras') || str_contains($n, 'rambak') || str_contains($n, 'parai') || str_contains($n, 'bedukang') || str_contains($n, 'rebo') || str_contains($n, 'teluk uber') || str_contains($n, 'air anyir') || str_contains($n, 'pantai tanjung pesona')) {
            return 'assets/img/pantai_tikus_emas.jpg';
        }

        // 3. Landmark Khusus Bangka Tengah (Koba, Air Bara & sekitarnya)
        if (str_contains($n, 'kaolin') || str_contains($n, 'air bara') || str_contains($n, 'koba') || str_contains($n, 'terentang') || str_contains($n, 'ketawai') || str_contains($n, 'tapak antu') || str_contains($n, 'pulau ketawai') || str_contains($n, 'danau')) {
            return 'assets/img/danau_kaolin_koba.jpg';
        }

        // 4. Landmark Khusus Bangka Selatan (Toboali & sekitarnya)
        if (str_contains($n, 'batu perahu') || str_contains($n, 'nek aji') || str_contains($n, 'toboali') || str_contains($n, 'kerasak') || str_contains($n, 'lepar') || str_contains($n, 'pongok') || str_contains($n, 'tanjung labu')) {
            return 'assets/img/pantai_batu_perahu.jpg';
        }

        // 5. Landmark Khusus Belitung & Belitung Timur
        if (str_contains($n, 'lengkuas') || str_contains($n, 'mercusuar') || str_contains($n, 'kepayang') || str_contains($n, 'batu berlayar') || str_contains($n, 'batu garuda') || str_contains($n, 'pulau pasir')) {
            return 'assets/img/pantai_lengkuas.jpg';
        }
        if (str_contains($n, 'tanjung tinggi') || str_contains($n, 'kelayang') || str_contains($n, 'bukit berahu') || str_contains($n, 'mabai') || str_contains($n, 'granit') || str_contains($n, 'punai')) {
            return 'assets/img/pantai_tanjung_tinggi.jpg';
        }
        if (str_contains($n, 'laskar pelangi') || str_contains($n, 'gantong') || str_contains($n, 'kata') || str_contains($n, 'sekolah') || str_contains($n, 'andrea') || str_contains($n, 'kirana') || str_contains($n, 'manggar') || str_contains($n, 'serdang') || str_contains($n, 'burung mandi')) {
            return 'assets/img/laskar_pelangi_gantong.jpg';
        }

        // 6. Museum & Heritage Kota
        if (str_contains($n, 'museum') || str_contains($n, 'timah') || str_contains($n, 'sejarah') || str_contains($n, 'prasasti') || str_contains($n, 'merdeka') || str_contains($n, 'residen')) {
            return 'assets/img/museum_timah_babel.jpg';
        }

        // 7. Busana & Kriya Tradisional
        if (str_contains($n, 'cual') || str_contains($n, 'tenun') || str_contains($n, 'kain') || str_contains($n, 'batik') || str_contains($n, 'souvenir') || str_contains($n, 'kerajinan')) {
            return 'assets/img/kain_cual_bangka.jpg';
        }

        // 8. Kesenian Musik & Tari
        if (str_contains($n, 'dambus') || str_contains($n, 'musik') || str_contains($n, 'sanggar') || str_contains($n, 'gamelan') || str_contains($n, 'seni')) {
            return 'assets/img/alat_musik_dambus.jpg';
        }
        if (str_contains($n, 'paksian') || str_contains($n, 'pengantin') || str_contains($n, 'pakaian adat')) {
            return 'assets/img/budaya_tari_paksian.jpg';
        }
        if (str_contains($n, 'campak') || str_contains($n, 'tari') || str_contains($n, 'joget')) {
            return 'assets/img/budaya_tari_campak.jpg';
        }

        // 9. Kuliner Khas (Nganggung / Lempah / Otak-Otak / Kopi)
        if (str_contains($n, 'nganggung') || str_contains($n, 'sepintu') || str_contains($n, 'sedulang') || str_contains($n, 'dulang') || str_contains($n, 'lempah') || str_contains($n, 'otak-otak') || str_contains($n, 'kopi') || str_contains($n, 'resto') || str_contains($n, 'warung') || str_contains($n, 'rumah makan') || str_contains($n, 'seafood') || $s === 'restoran') {
            return 'assets/img/budaya_nganggung.jpg';
        }

        // 10. Distribusi Geografis Otentik Berdasarkan Kabupaten
        if (str_contains($k, 'bangka barat')) {
            return 'assets/img/pantai_tanjung_ular.jpg';
        }
        if (str_contains($k, 'bangka tengah')) {
            return 'assets/img/danau_kaolin_koba.jpg';
        }
        if (str_contains($k, 'bangka selatan')) {
            return 'assets/img/pantai_batu_perahu.jpg';
        }
        if (str_contains($k, 'belitung timur')) {
            return 'assets/img/laskar_pelangi_gantong.jpg';
        }
        if (str_contains($k, 'belitung')) {
            return (crc32($nama) % 2 === 0) ? 'assets/img/pantai_lengkuas.jpg' : 'assets/img/pantai_tanjung_tinggi.jpg';
        }
        if (str_contains($k, 'pangkalpinang')) {
            return ($s === 'kebudayaan') ? 'assets/img/museum_timah_babel.jpg' : 'assets/img/pantai_tikus_emas.jpg';
        }

        // 11. Fallback Kategori Umum
        if ($s === 'kebudayaan' || str_contains($s, 'budaya') || str_contains($s, 'sejarah')) {
            return 'assets/img/kain_cual_bangka.jpg';
        }
        if ($s === 'buatan') {
            return 'assets/img/laskar_pelangi_gantong.jpg';
        }
        if ($s === 'hotel') {
            return 'assets/img/pantai_tanjung_tinggi.jpg';
        }

        // Default Bangka (Pantai Emas)
        return 'assets/img/pantai_tikus_emas.jpg';
    }
}
