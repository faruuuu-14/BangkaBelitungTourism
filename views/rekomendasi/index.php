<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/rekomendasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Pengendali Utama SPK Rekomendasi Destinasi TOPSIS)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Pengontrol utama (Master View Controller) untuk fitur Sistem Pendukung Keputusan
 * (SPK) Rekomendasi Destinasi Wisata Terbaik. Menerima input preferensi bobot
 * kriteria pengguna (Rating, Jarak Transportasi, Amenitas Sekitar, dan Ulasan),
 * memanggil mesin komputasi matematis calculateTopsisRecommendation() dari
 * app/statistics.php, lalu merender komponen modular berurutan:
 * 1. banner_rekomendasi.php (Header Visual & Panduan)
 * 2. pengaturan_bobot.php   (Form Bobot & Preset Persona Wisatawan)
 * 3. podium_juara.php       (Podium 3 Destinasi Juara dengan Foto Otentik)
 * 4. tabel_peringkat.php    (Tabel 10 Besar Hasil Rekomendasi)
 * 5. transparansi_rumus.php (Modal Transparansi Rumus Hwang & Yoon)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (Master Router) saat ?page=rekomendasi
 * - Membaca data: app/database.php dan app/statistics.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan alur perekomendasian yang terstruktur, transparan, objektif,
 * dan sesuai standar akademik tugas akhir/skripsi.
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/database.php';
require_once __DIR__ . '/../../app/statistics.php';

$pdo = getDbConnection();

// --------------------------------------------------------------------------
// 1. PENGAMBILAN BOBOT DARI PARAMETER GET (ATAU NILAI DEFAULT TERSTANDAR)
// --------------------------------------------------------------------------
$wRating    = isset($_GET['w_rating']) ? (float)$_GET['w_rating'] : 30.0;
$wHub       = isset($_GET['w_hub']) ? (float)$_GET['w_hub'] : 25.0;
$wAmenities = isset($_GET['w_amenities']) ? (float)$_GET['w_amenities'] : 25.0;
$wUlasan    = isset($_GET['w_ulasan']) ? (float)$_GET['w_ulasan'] : 20.0;

$filterKab = !empty($_GET['kabupaten']) ? trim($_GET['kabupaten']) : null;
$filterSub = !empty($_GET['sub_kategori']) ? trim($_GET['sub_kategori']) : null;
$limitPOI  = isset($_GET['limit']) ? (int)$_GET['limit'] : 30;

// --------------------------------------------------------------------------
// 2. EKSEKUSI ALGORITMA TOPSIS
// --------------------------------------------------------------------------
$topsisResult = calculateTopsisRecommendation([
    'rating'    => $wRating,
    'hub_dist'  => $wHub,
    'amenities' => $wAmenities,
    'ulasan'    => $wUlasan
], $filterKab, $filterSub, $limitPOI);

$weights     = $topsisResult['weights'];
$rankedList  = $topsisResult['candidates'];
$top10       = $topsisResult['top_10'];
$aPlus       = $topsisResult['a_plus'];
$aMinus      = $topsisResult['a_minus'];
$divisors    = $topsisResult['divisors'];
$totalPOI    = $topsisResult['total_counted'];

// Daftar 7 Kabupaten/Kota untuk dropdown filter
$kabList = [
    'Kota Pangkalpinang',
    'Kabupaten Bangka',
    'Kabupaten Belitung',
    'Kabupaten Bangka Barat',
    'Kabupaten Bangka Tengah',
    'Kabupaten Bangka Selatan',
    'Kabupaten Belitung Timur'
];
?>

<!-- ============================================================== -->
<!-- STRUKTUR TATA LETAK UTAMA FITUR REKOMENDASI TOPSIS            -->
<!-- ============================================================== -->
<main class="flex-grow w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 min-w-0 overflow-hidden space-y-6 sm:space-y-8">

    <!-- 1. Banner Visual Header SPK -->
    <?php include __DIR__ . '/banner_rekomendasi.php'; ?>

    <!-- 2. Formulir Bobot Kriteria & Preset Persona Wisatawan -->
    <?php include __DIR__ . '/pengaturan_bobot.php'; ?>

    <!-- 3. Podium 3 Destinasi Juara (Emas, Perak, Perunggu) -->
    <?php include __DIR__ . '/podium_juara.php'; ?>

    <!-- 4. Tabel 10 Besar Hasil Rekomendasi TOPSIS -->
    <?php include __DIR__ . '/tabel_peringkat.php'; ?>

    <!-- 5. Modal Transparansi Rumus Matematis TOPSIS -->
    <?php include __DIR__ . '/transparansi_rumus.php'; ?>

    <!-- 6. Modal Komparasi Berdampingan 2 Destinasi Wisata -->
    <?php include __DIR__ . '/komparasi_destinasi.php'; ?>

</main>

<!-- Skrip Ekspor CSV Hasil TOPSIS -->
<script>
function exportTopsisCSV() {
    const data = <?= json_encode($top10) ?>;
    if (!data || data.length === 0) {
        alert('Tidak ada data rekomendasi untuk diekspor.');
        return;
    }

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Peringkat,Nama Destinasi,Kabupaten,Kecamatan,Kategori,Rating,Ulasan,Jarak Hub Transportasi (km),Amenitas Sekitar (Unit),Nilai Preferensi V\n";

    data.forEach((row, index) => {
        const line = [
            index + 1,
            `"${row.nama.replace(/"/g, '""')}"`,
            `"${row.kabupaten}"`,
            `"${row.kecamatan}"`,
            `"${row.sub_kategori}"`,
            row.rating,
            row.ulasan,
            row.hub_dist,
            row.amenities,
            row.score_v
        ].join(",");
        csvContent += line + "\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `Rekomendasi_TOPSIS_Babel_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
