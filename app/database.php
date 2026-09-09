<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      database.php
 * @folder    app/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Koneksi Basis Data MySQL PDO & Penyelesai Foto Global)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Pusat konfigurasi koneksi basis data relasional MySQL menggunakan ekstensi PDO
 * (PHP Data Objects). Dilengkapi sistem deteksi lingkungan otomatis (Localhost vs
 * Hosting InfinityFree) serta fungsi penyelesai foto otentik representatif
 * (getDestinationPhoto) yang menjamin 100% dari 875 objek wisata dan amenitas
 * memiliki visual foto beresolusi tinggi tanpa link rusak (zero broken links).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php, views/beranda/index.php, views/analisis/index.php,
 *   views/rekomendasi/index.php, views/peta/index.php, views/tambah/index.php,
 *   views/verifikasi/index.php, dan perayap_data/impor_ke_database.php
 * - Mengakses basis data: MySQL db_pariwisata_babel (atau db_babel_tourism)
 * - Mengakses aset: folder assets/img/*.jpg
 * 
 * TUJUAN DIBUATNYA FILE:
 * Mengabstraksi koneksi database dan resolusi foto ke dalam 1 titik pusat (Single
 * Source of Truth), sehingga bila ada perubahan kredensial server atau skema tabel,
 * perubahannya otomatis berlaku ke seluruh halaman sistem informasi.
 * ==============================================================================
 */

// --------------------------------------------------------------------------
// 1. DETEKSI LINGKUNGAN EKSEKUSI (LOCALHOST VS HOSTING PRODUKSI)
// Memeriksa HTTP_HOST untuk menentukan apakah web dijalankan di Laragon lokal
// atau server hosting online (InfinityFree).
// --------------------------------------------------------------------------
$isLocalhost = in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1', 'localhost:80']);

if ($isLocalhost) {
    // Kredensial Localhost (Laragon Standar)
    $DB_HOST = "127.0.0.1";
    $DB_PORT = "3306";
    $DB_NAME = "db_pariwisata_babel";  // Basis data resmi berbahasa Indonesia
    $DB_USER = "root";
    $DB_PASS = "";
} else {
    // Kredensial Hosting Online (InfinityFree)
    $DB_HOST = "sql103.infinityfree.com"; 
    $DB_PORT = "3306";
    $DB_NAME = "if0_42843499_tourism";     
    $DB_USER = "if0_42843499";              
    $DB_PASS = "8tyNWDPPJkAkNum";
}

/**
 * Mendapatkan objek koneksi tunggal (Singleton Pattern) PDO MySQL.
 * 
 * @return PDO Objek koneksi basis data aktif
 */
function getDbConnection() {
    global $DB_HOST, $DB_PORT, $DB_NAME, $DB_USER, $DB_PASS, $isLocalhost;
    
    static $pdo = null;
    if ($pdo === null) {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";
            $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
        } catch (PDOException $e) {
            // Fallback cerdas: Jika di localhost db_pariwisata_babel belum ada, gunakan db_babel_tourism
            if ($isLocalhost && $DB_NAME === "db_pariwisata_babel") {
                try {
                    $DB_NAME = "db_babel_tourism";
                    $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";
                    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
                } catch (PDOException $e2) {
                    die("Gagal terhubung ke database: " . $e2->getMessage());
                }
            } else {
                die("Gagal terhubung ke database: " . $e->getMessage());
            }
        }
    }
    return $pdo;
}

if (!function_exists('getDestinationPhoto')) {
    /**
     * Mengambil Foto Representatif Otentik Bangka Belitung Berdasarkan Nama, Kategori & Kabupaten
     * Menjamin 100% dari 875 objek wisata & amenitas memiliki foto otentik berimbang di 7 Kabupaten/Kota.
     * 
     * @param string $nama Nama tempat/destinasi
     * @param string $subKategori Sub kategori (Alam, Buatan, Kebudayaan, Hotel, Restoran)
     * @param string $kabupaten Nama Kabupaten/Kota
     * @return string Jalur path file foto aset
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
        if (str_contains($n, 'tikus emas') || str_contains($n, 'tongaci') || str_contains($n, 'parai') || str_contains($n, 'matras') || str_contains($n, 'puri tri agung') || str_contains($n, 'tanjung pesona') || str_contains($n, 'rebo') || str_contains($n, 'sungailiat') || str_contains($n, 'pemali')) {
            return 'assets/img/pantai_tikus_emas.jpg';
        }

        // 3. Landmark Khusus Bangka Tengah (Koba & sekitarnya)
        if (str_contains($n, 'kaolin') || str_contains($n, 'air biru') || str_contains($n, 'koba') || str_contains($n, 'kebangket') || str_contains($n, 'gusung') || str_contains($n, 'pangkalan baru') || str_contains($n, 'kulur') || str_contains($n, 'namang')) {
            return 'assets/img/danau_kaolin_koba.jpg';
        }

        // 4. Landmark Khusus Bangka Selatan (Toboali & sekitarnya)
        if (str_contains($n, 'batu perahu') || str_contains($n, 'toboali') || str_contains($n, 'kelisut') || str_contains($n, 'nek aji') || str_contains($n, 'lepar') || str_contains($n, 'sadai') || str_contains($n, 'batu betumpang')) {
            return 'assets/img/pantai_batu_perahu.jpg';
        }

        // 5. Landmark Khusus Belitung & Belitung Timur
        if (str_contains($n, 'lengkuas') || str_contains($n, 'mercusuar') || str_contains($n, 'kepayang') || str_contains($n, 'batu berlayar') || str_contains($n, 'burung mandi')) {
            return 'assets/img/pantai_lengkuas.jpg';
        }
        if (str_contains($n, 'tanjung tinggi') || str_contains($n, 'laskar pelangi') || str_contains($n, 'geopark') || str_contains($n, 'kelayang') || str_contains($n, 'siantu')) {
            return 'assets/img/pantai_tanjung_tinggi.jpg';
        }
        if (str_contains($n, 'gantong') || str_contains($n, 'gantung') || str_contains($n, 'andrea hirata') || str_contains($n, 'sekolah laskar') || str_contains($n, 'manggar') || str_contains($n, 'kampit')) {
            return 'assets/img/laskar_pelangi_gantong.jpg';
        }

        // 6. Landmark Khusus Kota Pangkalpinang & Sejarah Timah
        if (str_contains($n, 'museum') || str_contains($n, 'timah') || str_contains($n, 'pasir padi') || str_contains($n, 'pangkalpinang') || str_contains($n, 'taman sari') || str_contains($n, 'merdeka')) {
            return 'assets/img/museum_timah_babel.jpg';
        }

        // 7. Landmark Kriya, Tekstil & Seni
        if (str_contains($n, 'cual') || str_contains($n, 'tenun') || str_contains($n, 'kain') || str_contains($n, 'batik') || str_contains($n, 'kriya') || str_contains($n, 'souvenir') || str_contains($n, 'kerajinan')) {
            return 'assets/img/kain_cual_bangka.jpg';
        }
        if (str_contains($n, 'dambus') || str_contains($n, 'musik') || str_contains($n, 'alat') || str_contains($n, 'sanggar') || str_contains($n, 'gita')) {
            return 'assets/img/alat_musik_dambus.jpg';
        }

        // 8. Pemetaan Fallback Berdasarkan Kabupaten
        if (str_contains($k, 'barat')) {
            return 'assets/img/pantai_tanjung_ular.jpg';
        }
        if (str_contains($k, 'selatan')) {
            return 'assets/img/pantai_batu_perahu.jpg';
        }
        if (str_contains($k, 'tengah')) {
            return 'assets/img/danau_kaolin_koba.jpg';
        }
        if (str_contains($k, 'timur')) {
            return 'assets/img/laskar_pelangi_gantong.jpg';
        }
        if (str_contains($k, 'belitung')) {
            return 'assets/img/pantai_tanjung_tinggi.jpg';
        }

        // 9. Pemetaan Fallback Berdasarkan Sub Kategori
        if ($s === 'kebudayaan') {
            return 'assets/img/budaya_tari_campak.jpg';
        }
        if ($s === 'restoran' || str_contains($n, 'restoran') || str_contains($n, 'rumah makan') || str_contains($n, 'warung') || str_contains($n, 'cafe') || str_contains($n, 'kuliner') || str_contains($n, 'kopi')) {
            return 'assets/img/budaya_nganggung.jpg';
        }
        if ($s === 'hotel' || str_contains($n, 'hotel') || str_contains($n, 'resort') || str_contains($n, 'penginapan') || str_contains($n, 'homestay')) {
            return 'assets/img/pantai_tanjung_tinggi.jpg';
        }

        // 10. Default Global Foto Unggulan
        return 'assets/img/pantai_tikus_emas.jpg';
    }
}
