<?php
/**
 * API & Open Data Exporter
 * Standar Satu Data Indonesia & OGC GeoJSON RFC 7946
 */
require_once __DIR__ . '/../app/database.php';

$pdo = getDbConnection();
$format = strtolower($_GET['format'] ?? 'geojson');
$kabupaten = $_GET['kabupaten'] ?? null;
$kelompok = $_GET['kelompok'] ?? null;

$query = "SELECT id, nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude, status_verifikasi 
          FROM tempat_wisata 
          WHERE status_verifikasi = 'Resmi' AND latitude IS NOT NULL AND longitude IS NOT NULL";
$params = [];

if ($kabupaten) {
    $query .= " AND kabupaten = ?";
    $params[] = $kabupaten;
}
if ($kelompok) {
    $query .= " AND kelompok = ?";
    $params[] = $kelompok;
}

$query .= " ORDER BY kabupaten ASC, ulasan DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$rows = $stmt->fetchAll();

if ($format === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="wisata_babel_' . date('Ymd_His') . '.csv"');
    
    $output = fopen('php://output', 'w');
    // UTF-8 BOM untuk kompatibilitas Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Header CSV
    fputcsv($output, ['ID', 'Nama Destinasi', 'Kabupaten/Kota', 'Kecamatan', 'Kelompok 3A', 'Sub Kategori', 'Rating', 'Jumlah Ulasan', 'Latitude', 'Longitude']);
    
    foreach ($rows as $r) {
        fputcsv($output, [
            $r['id'],
            $r['nama'],
            $r['kabupaten'],
            $r['kecamatan'],
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

// Default: GeoJSON (RFC 7946)
header('Content-Type: application/geo+json; charset=UTF-8');
header('Content-Disposition: attachment; filename="wisata_babel_' . date('Ymd_His') . '.geojson"');

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
            'kecamatan'    => $r['kecamatan'],
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
        'title'       => 'Dataset Spasial Pariwisata Kepulauan Bangka Belitung',
        'generator'   => 'Babel Tourism Intelligence Platform',
        'total_pois'  => count($features),
        'generated_at'=> date('c')
    ],
    'features' => $features
];

echo json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit;
