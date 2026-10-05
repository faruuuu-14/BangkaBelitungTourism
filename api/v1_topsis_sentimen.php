<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      v1_topsis_sentimen.php
 * @folder    api/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (RESTful API Endpoint: Sentiment-Driven TOPSIS Ranking)
 * 
 * DESKRIPSI:
 * Endpoint REST API JSON untuk perangkingan destinasi wisata berbasis SPK TOPSIS
 * dengan pembobotan sentimen ulasan teks dan transparansi komputasi matematis.
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

require_once __DIR__ . '/../app/AnalisisSentimenTopsis.php';

try {
    $input = $_GET;
    $rawBody = file_get_contents('php://input');
    if (!empty($rawBody)) {
        $json = json_decode($rawBody, true);
        if (is_array($json)) {
            $input = array_merge($input, $json);
        }
    }

    $customWeights = [];
    if (isset($input['w_c1'])) $customWeights['c1_rating'] = (float)$input['w_c1'];
    if (isset($input['w_c2'])) $customWeights['c2_sentimen'] = (float)$input['w_c2'];
    if (isset($input['w_c3'])) $customWeights['c3_ulasan'] = (float)$input['w_c3'];
    if (isset($input['w_c4'])) $customWeights['c4_fasilitas'] = (float)$input['w_c4'];
    if (isset($input['w_c5'])) $customWeights['c5_aksesibilitas'] = (float)$input['w_c5'];

    $kabupaten = trim($input['kabupaten'] ?? '');
    $kategori = trim($input['kategori'] ?? '');
    $limit = max(5, min(100, (int)($input['limit'] ?? 20)));

    $engine = new AnalisisSentimenTopsis();
    $result = $engine->computeRanking($customWeights, $kabupaten, $kategori, $limit);

    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'pesan' => 'Terjadi kesalahan server saat memproses TOPSIS: ' . $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
