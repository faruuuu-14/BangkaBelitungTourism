<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      v1_itinerary.php
 * @folder    api/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (RESTful API Endpoint: Smart Itinerary Generator)
 * 
 * DESKRIPSI:
 * Endpoint REST API mandiri berformat JSON untuk optimasi rute jadwal wisata harian.
 * Siap dikonsumsi oleh web front-end maupun aplikasi mobile Android Studio.
 * ==============================================================================
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../app/AlgoritmaItinerary.php';

try {
    // Ambil parameter dari GET atau JSON body
    $input = $_GET;
    $rawBody = file_get_contents('php://input');
    if (!empty($rawBody)) {
        $json = json_decode($rawBody, true);
        if (is_array($json)) {
            $input = array_merge($input, $json);
        }
    }

    $params = [
        'durasi_hari' => (int)($input['durasi_hari'] ?? 1),
        'jam_mulai' => trim($input['jam_mulai'] ?? '08:00'),
        'jam_selesai' => trim($input['jam_selesai'] ?? '18:00'),
        'budget_max' => (int)($input['budget_max'] ?? 500000),
        'titik_awal_key' => trim($input['titik_awal_key'] ?? 'bandara_pgk'),
        'kabupaten' => trim($input['kabupaten'] ?? ''),
        'preferensi' => isset($input['preferensi']) ? (is_array($input['preferensi']) ? $input['preferensi'] : explode(',', $input['preferensi'])) : ['Alam', 'Kebudayaan', 'Kuliner'],
        'ritme' => trim($input['ritme'] ?? 'standar')
    ];

    $engine = new AlgoritmaItinerary();
    $result = $engine->generateItinerary($params);

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'pesan' => 'Terjadi kesalahan server saat memproses algoritma itinerary: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
