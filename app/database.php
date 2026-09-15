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

if (!function_exists('getCurrentWilayah')) {
    /**
     * Mendapatkan cakupan wilayah yang sedang aktif dipilih pengguna
     * Mendukung mode: 'nasional' (default) atau 'babel' (Bangka Belitung)
     * 
     * @return string Kode wilayah aktif ('nasional' atau 'babel')
     */
    function getCurrentWilayah() {
        $w = isset($_GET['wilayah']) ? strtolower(trim((string)$_GET['wilayah'])) : 'babel';
        $daftar = ['babel', 'nasional', 'sumbar', 'sumut', 'riau', 'kepri', 'sumsel', 'lampung', 'dki', 'jabar', 'jateng', 'diy', 'jatim', 'bali'];
        return in_array($w, $daftar) ? $w : 'babel';
    }
}

if (!function_exists('getDaftarProvinsi')) {
    /**
     * Mengambil daftar master 14 provinsi wisata nusantara (Babel + 13 Provinsi Lainnya)
     * 
     * @return array Daftar informasi status dan metadata provinsi
     */
    function getDaftarProvinsi() {
        return [
            'nasional' => [
                'nama' => 'Skala Nasional (Seluruh Indonesia)',
                'singkat' => 'Skala Nasional',
                'status' => 'Aktif (Agregat Nasional)',
                'total_destinasi' => 875,
                'icon' => 'fa-solid fa-earth-asia',
                'badge' => 'Nusantara',
                'color' => 'from-ocean-600 to-indigo-700'
            ],
            'babel' => [
                'nama' => 'Kepulauan Bangka Belitung',
                'singkat' => 'Bangka Belitung',
                'status' => 'Wilayah Percontohan Utama (Data Terverifikasi)',
                'total_destinasi' => 875,
                'icon' => 'fa-solid fa-gem',
                'badge' => 'Pilot Project',
                'color' => 'from-emerald-600 to-teal-700'
            ],
            'sumbar' => [
                'nama' => 'Sumatera Barat',
                'singkat' => 'Sumatera Barat',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-landmark-dome',
                'badge' => 'Tahap 2',
                'color' => 'from-amber-600 to-orange-700'
            ],
            'sumut' => [
                'nama' => 'Sumatera Utara',
                'singkat' => 'Sumatera Utara',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-mountain',
                'badge' => 'Tahap 2',
                'color' => 'from-sky-600 to-cyan-700'
            ],
            'riau' => [
                'nama' => 'Riau',
                'singkat' => 'Riau',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-tree',
                'badge' => 'Tahap 2',
                'color' => 'from-teal-600 to-emerald-700'
            ],
            'kepri' => [
                'nama' => 'Kepulauan Riau',
                'singkat' => 'Kepulauan Riau',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-ship',
                'badge' => 'Tahap 2',
                'color' => 'from-blue-600 to-indigo-700'
            ],
            'sumsel' => [
                'nama' => 'Sumatera Selatan',
                'singkat' => 'Sumatera Selatan',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-bridge',
                'badge' => 'Tahap 2',
                'color' => 'from-purple-600 to-indigo-700'
            ],
            'lampung' => [
                'nama' => 'Lampung',
                'singkat' => 'Lampung',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-water',
                'badge' => 'Tahap 2',
                'color' => 'from-rose-600 to-pink-700'
            ],
            'dki' => [
                'nama' => 'DKI Jakarta',
                'singkat' => 'DKI Jakarta',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-city',
                'badge' => 'Tahap 2',
                'color' => 'from-slate-700 to-slate-900'
            ],
            'jabar' => [
                'nama' => 'Jawa Barat',
                'singkat' => 'Jawa Barat',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-mountain-sun',
                'badge' => 'Tahap 2',
                'color' => 'from-emerald-600 to-green-700'
            ],
            'jateng' => [
                'nama' => 'Jawa Tengah',
                'singkat' => 'Jawa Tengah',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-monument',
                'badge' => 'Tahap 2',
                'color' => 'from-amber-600 to-yellow-700'
            ],
            'diy' => [
                'nama' => 'DI Yogyakarta',
                'singkat' => 'DI Yogyakarta',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-crown',
                'badge' => 'Tahap 2',
                'color' => 'from-orange-600 to-red-700'
            ],
            'jatim' => [
                'nama' => 'Jawa Timur',
                'singkat' => 'Jawa Timur',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-volcano',
                'badge' => 'Tahap 2',
                'color' => 'from-red-600 to-rose-700'
            ],
            'bali' => [
                'nama' => 'Bali',
                'singkat' => 'Bali',
                'status' => 'Siap Integrasi Data',
                'total_destinasi' => 0,
                'icon' => 'fa-solid fa-umbrella-beach',
                'badge' => 'Tahap 2',
                'color' => 'from-fuchsia-600 to-purple-700'
            ],
        ];
    }
}

if (!function_exists('getDestinationPhoto')) {
    /**
     * Mengambil Foto Representatif Otentik Berdasarkan Nama, Kategori & Wilayah
     * Mendukung jalur folder modular: assets/img/bangka_belitung/ dan assets/img/nasional/
     * 
     * @param string $nama Nama tempat/destinasi
     * @param string $subKategori Sub kategori (Alam, Buatan, Kebudayaan, Hotel, Restoran)
     * @param string $kabupaten Nama Kabupaten/Kota
     * @param string $provinsi Nama atau kode provinsi (default: Babel)
     * @return string Jalur path file foto aset
     */
    function getDestinationPhoto($nama, $subKategori = '', $kabupaten = '', $provinsi = '') {
        $n = strtolower($nama ?? '');
        $s = strtolower($subKategori ?? '');
        $k = strtolower($kabupaten ?? '');
        $p = strtolower($provinsi ?? '');

        // JIKA WILAYAH NASIONAL / DILUAR BABEL
        if (str_contains($n, 'raja ampat') || str_contains($k, 'raja ampat') || str_contains($p, 'papua')) {
            return 'assets/img/nasional/raja_ampat.jpg';
        }
        if (str_contains($n, 'borobudur') || str_contains($k, 'magelang') || str_contains($p, 'jawa tengah')) {
            return 'assets/img/nasional/borobudur.jpg';
        }
        if (str_contains($n, 'bromo') || str_contains($k, 'probolinggo') || str_contains($p, 'jawa timur')) {
            return 'assets/img/nasional/bromo.jpg';
        }
        if (str_contains($n, 'labuan bajo') || str_contains($k, 'manggarai barat') || str_contains($p, 'nusa tenggara timur')) {
            return 'assets/img/nasional/labuan_bajo.jpg';
        }
        if (str_contains($n, 'danau toba') || str_contains($k, 'samosir') || str_contains($p, 'sumatera utara')) {
            return 'assets/img/nasional/danau_toba.jpg';
        }

        // 1. Landmark Khusus Bangka Barat (Muntok & sekitarnya)
        if (str_contains($n, 'tanjung ular') || str_contains($n, 'batu rakit') || str_contains($n, 'siangau') || str_contains($n, 'menumbing') || str_contains($n, 'pesanggrahan') || str_contains($n, 'muntok') || str_contains($n, 'mentok') || str_contains($n, 'asrama tentara') || str_contains($n, 'benteng')) {
            return 'assets/img/bangka_belitung/pantai_tanjung_ular.jpg';
        }

        // 2. Landmark Khusus Bangka (Sungailiat & sekitarnya)
        if (str_contains($n, 'tikus emas') || str_contains($n, 'tongaci') || str_contains($n, 'parai') || str_contains($n, 'matras') || str_contains($n, 'puri tri agung') || str_contains($n, 'tanjung pesona') || str_contains($n, 'rebo') || str_contains($n, 'sungailiat') || str_contains($n, 'pemali')) {
            return 'assets/img/bangka_belitung/pantai_tikus_emas.jpg';
        }

        // 3. Landmark Khusus Bangka Tengah (Koba & sekitarnya)
        if (str_contains($n, 'kaolin') || str_contains($n, 'air biru') || str_contains($n, 'koba') || str_contains($n, 'kebangket') || str_contains($n, 'gusung') || str_contains($n, 'pangkalan baru') || str_contains($n, 'kulur') || str_contains($n, 'namang')) {
            return 'assets/img/bangka_belitung/danau_kaolin_koba.jpg';
        }

        // 4. Landmark Khusus Bangka Selatan (Toboali & sekitarnya)
        if (str_contains($n, 'batu perahu') || str_contains($n, 'toboali') || str_contains($n, 'kelisut') || str_contains($n, 'nek aji') || str_contains($n, 'lepar') || str_contains($n, 'sadai') || str_contains($n, 'batu betumpang')) {
            return 'assets/img/bangka_belitung/pantai_batu_perahu.jpg';
        }

        // 5. Landmark Khusus Belitung & Belitung Timur
        if (str_contains($n, 'lengkuas') || str_contains($n, 'mercusuar') || str_contains($n, 'kepayang') || str_contains($n, 'batu berlayar') || str_contains($n, 'burung mandi')) {
            return 'assets/img/bangka_belitung/pantai_lengkuas.jpg';
        }
        if (str_contains($n, 'tanjung tinggi') || str_contains($n, 'laskar pelangi') || str_contains($n, 'geopark') || str_contains($n, 'kelayang') || str_contains($n, 'siantu')) {
            return 'assets/img/bangka_belitung/pantai_tanjung_tinggi.jpg';
        }
        if (str_contains($n, 'gantong') || str_contains($n, 'gantung') || str_contains($n, 'andrea hirata') || str_contains($n, 'sekolah laskar') || str_contains($n, 'manggar') || str_contains($n, 'kampit')) {
            return 'assets/img/bangka_belitung/laskar_pelangi_gantong.jpg';
        }

        // 6. Landmark Khusus Kota Pangkalpinang & Sejarah Timah
        if (str_contains($n, 'museum') || str_contains($n, 'timah') || str_contains($n, 'pasir padi') || str_contains($n, 'pangkalpinang') || str_contains($n, 'taman sari') || str_contains($n, 'merdeka')) {
            return 'assets/img/bangka_belitung/museum_timah_babel.jpg';
        }

        // 7. Landmark Kriya, Tekstil & Seni
        if (str_contains($n, 'cual') || str_contains($n, 'tenun') || str_contains($n, 'kain') || str_contains($n, 'batik') || str_contains($n, 'kriya') || str_contains($n, 'souvenir') || str_contains($n, 'kerajinan')) {
            return 'assets/img/bangka_belitung/kain_cual_bangka.jpg';
        }
        if (str_contains($n, 'dambus') || str_contains($n, 'musik') || str_contains($n, 'alat') || str_contains($n, 'sanggar') || str_contains($n, 'gita')) {
            return 'assets/img/bangka_belitung/alat_musik_dambus.jpg';
        }

        // 8. Pemetaan Fallback Berdasarkan Kabupaten
        if (str_contains($k, 'barat')) {
            return 'assets/img/bangka_belitung/pantai_tanjung_ular.jpg';
        }
        if (str_contains($k, 'selatan')) {
            return 'assets/img/bangka_belitung/pantai_batu_perahu.jpg';
        }
        if (str_contains($k, 'tengah')) {
            return 'assets/img/bangka_belitung/danau_kaolin_koba.jpg';
        }
        if (str_contains($k, 'timur')) {
            return 'assets/img/bangka_belitung/laskar_pelangi_gantong.jpg';
        }
        if (str_contains($k, 'belitung')) {
            return 'assets/img/bangka_belitung/pantai_tanjung_tinggi.jpg';
        }

        // 9. Pemetaan Fallback Berdasarkan Sub Kategori
        if ($s === 'kebudayaan') {
            return 'assets/img/bangka_belitung/budaya_tari_campak.jpg';
        }
        if ($s === 'restoran' || str_contains($n, 'restoran') || str_contains($n, 'rumah makan') || str_contains($n, 'warung') || str_contains($n, 'cafe') || str_contains($n, 'kuliner') || str_contains($n, 'kopi')) {
            return 'assets/img/bangka_belitung/budaya_nganggung.jpg';
        }
        if ($s === 'hotel' || str_contains($n, 'hotel') || str_contains($n, 'resort') || str_contains($n, 'penginapan') || str_contains($n, 'homestay')) {
            return 'assets/img/bangka_belitung/pantai_tanjung_tinggi.jpg';
        }

        // 10. Default Global Foto Unggulan
        return 'assets/img/bangka_belitung/pantai_tikus_emas.jpg';
    }
}
