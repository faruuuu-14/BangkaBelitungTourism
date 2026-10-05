<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      v1_kelayakan_bisnis.php
 * @folder    api/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (RESTful API Endpoint: Spatial Business Suitability Radar)
 * 
 * DESKRIPSI:
 * Endpoint REST API JSON untuk analisis kelayakan spasial lokasi usaha UMKM/Kafe/
 * Homestay baru menggunakan Spatial Multi-Criteria Evaluation (SMCE).
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

require_once __DIR__ . '/../app/EvaluasiSpasialBisnis.php';

try {
    $input = $_GET;
    $rawBody = file_get_contents('php://input');
    if (!empty($rawBody)) {
        $json = json_decode($rawBody, true);
        if (is_array($json)) {
            $input = array_merge($input, $json);
        }
    }

    $tipeUsaha = trim($input['tipe_usaha'] ?? 'kafe_wisata');
    $kabupaten = trim($input['kabupaten'] ?? '');

    $engine = new EvaluasiSpasialBisnis();
    $result = $engine->evaluateSuitability($tipeUsaha, $kabupaten);

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'pesan' => 'Terjadi kesalahan server saat memproses kelayakan bisnis: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
