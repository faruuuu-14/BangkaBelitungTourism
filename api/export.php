<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      export.php
 * @folder    api/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (API Layanan Ekspor Multi-Format: Excel, CSV, & GeoJSON Spasial)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Menyediakan endpoint pengunduhan dataset pariwisata terbuka (Open Data) yang
 * disaring secara dinamis berdasarkan filter aktif pengguna:
 * - Filter Wilayah (Kabupaten/Kota)
 * - Filter Sub Kategori 3A (Alam, Kebudayaan, Buatan, Hotel, Restoran)
 * - Filter Pencarian Kata Kunci
 * 
 * MENDUKUNG 3 FORMAT UTAMA:
 * 1. Microsoft Excel (.xls) - Spreadsheet kaya format, warna header, dan grid
 * 2. CSV Tabular (.csv)    - Standar RFC 4180 dengan UTF-8 BOM untuk SPSS/Excel
 * 3. GeoJSON (.geojson)    - Standar OGC RFC 7946 untuk QGIS, ArcGIS, Mapbox, Leaflet
 * ==============================================================================
 */

require_once __DIR__ . '/../app/database.php';

$pdo = getDbConnection();

// 1. Parameter Filter dari Permintaan Pengguna
$format    = strtolower(trim($_GET['format'] ?? 'excel'));
$kabupaten = trim($_GET['kabupaten'] ?? '');
$kategori  = trim($_GET['kategori'] ?? $_GET['sub_kategori'] ?? '');
$kelompok  = trim($_GET['kelompok'] ?? '');
$q         = trim($_GET['q'] ?? $_GET['search'] ?? '');

// 2. Susun Kueri SQL Berdasarkan Filter Aktif
$query = "
    SELECT id, nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude, status_verifikasi 
    FROM tempat_wisata 
    WHERE status_verifikasi = 'Resmi' AND latitude IS NOT NULL AND longitude IS NOT NULL
";
$params = [];

if (!empty($kabupaten) && $kabupaten !== 'Semua') {
    $query .= " AND kabupaten = ?";
    $params[] = $kabupaten;
}

if (!empty($kategori) && $kategori !== 'Semua') {
    $query .= " AND sub_kategori = ?";
    $params[] = $kategori;
}

if (!empty($kelompok) && $kelompok !== 'Semua') {
    $query .= " AND kelompok = ?";
    $params[] = $kelompok;
}

if (!empty($q)) {
    $query .= " AND (nama LIKE ? OR kecamatan LIKE ? OR sub_kategori LIKE ?)";
    $searchTerm = "%{$q}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$query .= " ORDER BY kabupaten ASC, ulasan DESC, rating DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// 3. Nama Berkas Dinamis Berdasarkan Filter Aktif
$nameSegments = ['wisata_babel'];

if (!empty($kabupaten) && $kabupaten !== 'Semua') {
    $cleanKab = preg_replace('/[^a-zA-Z0-9]/', '', str_replace(['Kabupaten', 'Kota', ' '], '', $kabupaten));
    if (!empty($cleanKab)) $nameSegments[] = $cleanKab;
}

if (!empty($kategori) && $kategori !== 'Semua') {
    $cleanCat = preg_replace('/[^a-zA-Z0-9]/', '', str_replace(' ', '', $kategori));
    if (!empty($cleanCat)) $nameSegments[] = $cleanCat;
}

$nameSegments[] = date('Ymd_His');
$baseFilename = implode('_', $nameSegments);

$filterWilayahText  = (!empty($kabupaten) && $kabupaten !== 'Semua') ? $kabupaten : 'Semua Kabupaten/Kota (7 Daerah)';
$filterKategoriText = (!empty($kategori) && $kategori !== 'Semua') ? $kategori : 'Semua Kategori (Alam, Budaya, Buatan, Hotel, Resto)';

// ==============================================================================
// 4. FORMAT 1: MICROSOFT EXCEL (.XLS)
// ==============================================================================
if ($format === 'excel' || $format === 'xls') {
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $baseFilename . '.xls"');
    header('Pragma: no-cache');
    header('Expires: 0');
    ?>
    <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!--[if gte mso 9]>
        <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Pariwisata Babel</x:Name>
                    <x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
        </xml>
        <![endif]-->
        <style>
            body { font-family: 'Segoe UI', Calibri, Arial, sans-serif; font-size: 10pt; color: #1e293b; }
            .main-title { font-size: 14pt; font-weight: bold; color: #0369a1; }
            .sub-title { font-size: 9.5pt; color: #64748b; margin-bottom: 8px; }
            th { background-color: #0284c7; color: #ffffff; font-weight: bold; font-size: 9.5pt; border: 1px solid #0369a1; padding: 7px 10px; text-align: left; }
            td { border: 1px solid #cbd5e1; padding: 5px 8px; font-size: 9.5pt; vertical-align: middle; }
            .zebra { background-color: #f8fafc; }
            .center { text-align: center; }
            .num { text-align: right; }
            .coord { font-family: 'Consolas', monospace; font-size: 9pt; text-align: center; color: #0369a1; }
        </style>
    </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="11" class="main-title" style="border:none; padding: 6px 0;">PORTAL SATU DATA PARIWISATA KEPULAUAN BANGKA BELITUNG</td>
            </tr>
            <tr>
                <td colspan="11" class="sub-title" style="border:none; padding: 2px 0;">
                    <b>Filter Wilayah:</b> <?= htmlspecialchars($filterWilayahText) ?> &nbsp;|&nbsp; 
                    <b>Filter Kategori:</b> <?= htmlspecialchars($filterKategoriText) ?> &nbsp;|&nbsp; 
                    <b>Total Data Terpilih:</b> <?= count($rows) ?> Objek
                </td>
            </tr>
            <tr>
                <td colspan="11" class="sub-title" style="border:none; padding: 2px 0 10px 0;">
                    <b>Waktu Pengunduhan:</b> <?= date('d F Y - H:i:s') ?> WIB &nbsp;|&nbsp; 
                    <b>Sumber Data:</b> Dinas Kebudayaan &amp; Pariwisata Babel terintegrasi BPS
                </td>
            </tr>
            <tr><td colspan="11" style="border:none; height: 8px;"></td></tr>
            <thead>
                <tr>
                    <th class="center" style="width: 45px;">No</th>
                    <th class="center" style="width: 55px;">ID</th>
                    <th style="width: 260px;">Nama Destinasi / Amenitas</th>
                    <th style="width: 170px;">Kabupaten / Kota</th>
                    <th style="width: 150px;">Kecamatan</th>
                    <th style="width: 120px;">Kelompok 3A</th>
                    <th style="width: 130px;">Sub Kategori</th>
                    <th class="center" style="width: 75px;">Rating</th>
                    <th class="num" style="width: 95px;">Ulasan</th>
                    <th class="center" style="width: 110px;">Latitude</th>
                    <th class="center" style="width: 110px;">Longitude</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="11" class="center" style="padding: 20px; color: #64748b;">
                        Tidak ada data destinasi wisata yang cocok dengan filter yang dipilih.
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($rows as $r): ?>
                <tr class="<?= ($no % 2 === 0) ? 'zebra' : '' ?>">
                    <td class="center"><?= $no++ ?></td>
                    <td class="center"><?= (int)$r['id'] ?></td>
                    <td><b><?= htmlspecialchars($r['nama']) ?></b></td>
                    <td><?= htmlspecialchars($r['kabupaten']) ?></td>
                    <td><?= htmlspecialchars($r['kecamatan'] ?: '-') ?></td>
                    <td><?= htmlspecialchars($r['kelompok']) ?></td>
                    <td><?= htmlspecialchars($r['sub_kategori']) ?></td>
                    <td class="center"><?= number_format((float)$r['rating'], 1) ?></td>
                    <td class="num"><?= number_format((int)$r['ulasan'], 0, ',', '.') ?></td>
                    <td class="coord"><?= number_format((float)$r['latitude'], 6, '.', '') ?></td>
                    <td class="coord"><?= number_format((float)$r['longitude'], 6, '.', '') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
    exit;
}

// ==============================================================================
// 5. FORMAT 2: CSV TABULAR (.CSV DENGAN UTF-8 BOM)
// ==============================================================================
if ($format === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $baseFilename . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    // UTF-8 BOM untuk kompatibilitas Microsoft Excel / SPSS
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Header Kolom CSV
    fputcsv($output, [
        'No',
        'ID',
        'Nama Destinasi',
        'Kabupaten/Kota',
        'Kecamatan',
        'Kelompok 3A',
        'Sub Kategori',
        'Rating',
        'Jumlah Ulasan',
        'Latitude',
        'Longitude'
    ]);
    
    $no = 1;
    foreach ($rows as $r) {
        fputcsv($output, [
            $no++,
            $r['id'],
            $r['nama'],
            $r['kabupaten'],
            $r['kecamatan'] ?: '-',
            $r['kelompok'],
            $r['sub_kategori'],
            $r['rating'],
            $r['ulasan'],
            $r['latitude'],
            $r['longitude']
        ]);
    }
    fclose($output);
    exit;
}

// ==============================================================================
// 6. FORMAT 3: GEOJSON SPASIAL OGC STANDAR RFC 7946 (.GEOJSON)
// ==============================================================================
header('Content-Type: application/geo+json; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $baseFilename . '.geojson"');
header('Pragma: no-cache');
header('Expires: 0');

$features = [];
foreach ($rows as $r) {
    $features[] = [
        'type' => 'Feature',
        'geometry' => [
            'type' => 'Point',
            'coordinates' => [(float)$r['longitude'], (float)$r['latitude']]
        ],
        'properties' => [
            'id'           => (int)$r['id'],
            'nama'         => $r['nama'],
            'kabupaten'    => $r['kabupaten'],
            'kecamatan'    => $r['kecamatan'] ?: '',
            'kelompok'     => $r['kelompok'],
            'sub_kategori' => $r['sub_kategori'],
            'rating'       => (float)$r['rating'],
            'ulasan'       => (int)$r['ulasan']
        ]
    ];
}

$geojson = [
    'type' => 'FeatureCollection',
    'crs' => [
        'type' => 'name',
        'properties' => [
            'name' => 'urn:ogc:def:crs:OGC:1.3:CRS84'
        ]
    ],
    'metadata' => [
        'title'           => 'Dataset Spasial Pariwisata Kepulauan Bangka Belitung',
        'filter_wilayah'  => $filterWilayahText,
        'filter_kategori' => $filterKategoriText,
        'filter_search'   => $q ?: 'Semua',
        'generator'       => 'Babel Tourism Intelligence WebGIS Platform',
        'total_pois'      => count($features),
        'generated_at'    => date('c')
    ],
    'features' => $features
];

echo json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit;
