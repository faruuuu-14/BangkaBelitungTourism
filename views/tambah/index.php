<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/tambah/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Pengendali Utama Formulir Pendaftaran Objek Wisata Baru)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Pengontrol utama (Master View Controller) untuk pendaftaran usulan objek wisata
 * atau amenitas baru. Memproses pengiriman formulir (POST Request), melakukan validasi
 * input dan sanitasi koordinat, menyimpan data ke tabel tempat_wisata berstatus
 * 'Usulan Baru', serta merender komponen-komponen antarmuka pendaftaran.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (Master Router) saat ?page=tambah
 * - Menulis ke basis data: MySQL db_pariwisata_babel (tabel tempat_wisata)
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memfasilitasi crowdsourcing data pariwisata partisipatif dari masyarakat
 * dan komunitas lokal di 7 Kabupaten/Kota Provinsi Bangka Belitung.
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/database.php';
$pdo = getDbConnection();

$pesanSukses = '';
$pesanError = '';

// Data pemetaan 7 Kabupaten & Kecamatan di Bangka Belitung
$wilayahBabel = [
    "Kabupaten Bangka" => ["Sungailiat", "Belinyu", "Pemali", "Merawang", "Mendo Barat", "Riau Silip", "Bakam", "Puding Besar"],
    "Kabupaten Bangka Barat" => ["Mentok", "Simpang Teritip", "Jebus", "Kelapa", "Tempilang", "Parittiga"],
    "Kabupaten Bangka Tengah" => ["Koba", "Pangkalan Baru", "Sungai Selan", "Simpang Katis", "Namang", "Lubuk Besar"],
    "Kabupaten Bangka Selatan" => ["Toboali", "Air Gegas", "Payung", "Simpang Rimba", "Tukak Sadai", "Pulau Besar", "Lepar Pongok", "Kepulauan Pongok"],
    "Kabupaten Belitung" => ["Tanjung Pandan", "Sijuk", "Badau", "Membalong", "Selat Nasik"],
    "Kabupaten Belitung Timur" => ["Manggar", "Gantung", "Kelapa Kampit", "Damar", "Dendang", "Simpang Pesak", "Simpang Renggiang"],
    "Kota Pangkalpinang" => ["Bukit Intan", "Gabek", "Gerunggang", "Girimaya", "Pangkal Balam", "Rangkui", "Taman Sari"]
];

// Pemrosesan Pengiriman Formulir POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama        = trim($_POST['nama'] ?? '');
    $kabupaten   = trim($_POST['kabupaten'] ?? '');
    $kecamatan   = trim($_POST['kecamatan'] ?? '');
    $subKategori = trim($_POST['sub_kategori'] ?? '');
    $rating      = floatval($_POST['rating'] ?? 0);
    $ulasan      = intval($_POST['ulasan'] ?? 0);
    $lat         = floatval($_POST['latitude'] ?? 0);
    $lng         = floatval($_POST['longitude'] ?? 0);

    // Penentuan kelompok otomatis (3A Kemenparekraf)
    $kelompok = in_array($subKategori, ['Hotel', 'Restoran']) ? 'Amenitas' : 'Daya Tarik';

    if (empty($nama) || empty($kabupaten) || empty($kecamatan) || empty($subKategori)) {
        $pesanError = "Mohon lengkapi seluruh kolom wajib yang bertanda bintang (*).";
    } elseif ($lat == 0 || $lng == 0) {
        $pesanError = "Mohon tentukan titik lokasi koordinat dengan mengklik pada peta.";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO tempat_wisata 
                (nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude, status_verifikasi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Usulan Baru')
            ");
            $stmt->execute([$nama, $kabupaten, $kecamatan, $kelompok, $subKategori, $rating, $ulasan, $lat, $lng]);
            $pesanSukses = "Objek wisata/amenitas baru '<strong>" . htmlspecialchars($nama) . "</strong>' berhasil didaftarkan dan masuk ke antrean moderasi!";
        } catch (Exception $e) {
            $pesanError = "Gagal menyimpan data ke database: " . $e->getMessage();
        }
    }
}
?>

<!-- ============================================================== -->
<!-- STRUKTUR TATA LETAK UTAMA FITUR PENDAFTARAN OBJEK BARU         -->
<!-- ============================================================== -->
<main class="flex-grow w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 min-w-0 overflow-hidden space-y-6 sm:space-y-8">

    <!-- Header Section Pendaftaran -->
    <div class="relative overflow-hidden bg-gradient-to-r from-slate-950 via-ocean-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800">
        <div class="relative z-10 space-y-2 max-w-3xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-ocean-300 text-xs font-bold">
                <i class="fa-solid fa-users text-ocean-400"></i>
                <span>Partisipasi Masyarakat, Pengelola Wisata &amp; Pelaku Usaha</span>
            </span>
            <h1 class="text-xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                Pendaftaran Objek Wisata &amp; Fasilitas Baru
            </h1>
            <p class="text-slate-200 text-xs sm:text-sm leading-relaxed">
                Daftarkan destinasi daya tarik alam, pusaka budaya, maupun amenitas hotel dan kuliner lokal di Bangka Belitung. Klik pada peta interaktif untuk menentukan titik koordinat GPS secara otomatis.
            </p>
        </div>
    </div>

    <!-- Alert Notifikasi Berhasil / Gagal -->
    <?php if (!empty($pesanSukses)): ?>
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
        <div><?= $pesanSukses ?></div>
    </div>
    <?php endif; ?>

    <?php if (!empty($pesanError)): ?>
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-3">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
        <div><?= $pesanError ?></div>
    </div>
    <?php endif; ?>

    <!-- 1. Formulir Pendaftaran Objek & Pemilih Peta Leaflet -->
    <?php include __DIR__ . '/formulir_pendaftaran.php'; ?>

    <!-- 2. Panduan Kriteria Validasi Data -->
    <?php include __DIR__ . '/panduan_kriteria.php'; ?>

</main>
