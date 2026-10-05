<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      v1_destinasi.php
 * @folder    api/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (RESTful API Endpoint: Places Search & Spatial GeoJSON/List)
 * 
 * DESKRIPSI:
 * Endpoint REST API JSON untuk pencarian destinasi, filter kategori/wilayah,
 * dan penyedia data titik spasial untuk Leaflet atau Android Google Maps SDK.
 * ==============================================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../app/database.php';
require_once __DIR__ . '/../app/AnalisisSentimenTopsis.php';

try {
    $pdo = getDbConnection();

    $q = trim($_GET['q'] ?? '');
    $kabupaten = trim($_GET['kabupaten'] ?? '');
    $kategori = trim($_GET['kategori'] ?? '');
    $kelompok = trim($_GET['kelompok'] ?? '');
    $limit = max(1, min(1000, (int)($_GET['limit'] ?? 100)));
    $format = strtolower(trim($_GET['format'] ?? 'json'));

    $sql = "
        SELECT id, nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude
        FROM tempat_wisata
        WHERE latitude IS NOT NULL AND longitude IS NOT NULL
          AND status_verifikasi = 'Resmi'
    ";
    $binds = [];

    if (!empty($q)) {
        $sql .= " AND (nama LIKE ? OR kecamatan LIKE ?)";
        $binds[] = "%$q%";
        $binds[] = "%$q%";
    }
    if (!empty($kabupaten)) {
        $sql .= " AND kabupaten = ?";
        $binds[] = $kabupaten;
    }
    if (!empty($kategori)) {
        $sql .= " AND sub_kategori = ?";
        $binds[] = $kategori;
    }
    if (!empty($kelompok)) {
        $sql .= " AND kelompok = ?";
        $binds[] = $kelompok;
    }

    $sql .= " ORDER BY rating DESC, ulasan DESC LIMIT " . (int)$limit;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($binds);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format GeoJSON jika diminta (misal untuk QGIS atau Mapbox)
    if ($format === 'geojson') {
        $features = [];
        foreach ($data as $item) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$item['longitude'], (float)$item['latitude']]
                ],
                'properties' => [
                    'id' => (int)$item['id'],
                    'nama' => $item['nama'],
                    'kabupaten' => $item['kabupaten'],
                    'kecamatan' => $item['kecamatan'],
                    'kelompok' => $item['kelompok'],
                    'sub_kategori' => $item['sub_kategori'],
                    'rating' => (float)$item['rating'],
                    'ulasan' => (int)$item['ulasan'],
                    'foto' => getDestinationPhoto($item['nama'], $item['sub_kategori'], $item['kabupaten'])
                ]
            ];
        }

        echo json_encode([
            'type' => 'FeatureCollection',
            'features' => $features
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // Default JSON Array
    $results = [];
    foreach ($data as $item) {
        $item['foto'] = getDestinationPhoto($item['nama'], $item['sub_kategori'], $item['kabupaten']);
        $item['rating'] = (float)$item['rating'];
        $item['ulasan'] = (int)$item['ulasan'];
        $item['latitude'] = (float)$item['latitude'];
        $item['longitude'] = (float)$item['longitude'];
        $results[] = $item;
    }

    echo json_encode([
        'status' => 'success',
        'total' => count($results),
        'data' => $results
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'pesan' => 'Terjadi kesalahan server saat mengambil data: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
