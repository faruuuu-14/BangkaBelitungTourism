<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      EvaluasiSpasialBisnis.php
 * @folder    app/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (Spatial Multi-Criteria Business Suitability Radar Engine)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & NOVELTY ILMIAH SKRIPSI:
 * Engine Evaluasi Spasial Multi-Kriteria (Spatial Multi-Criteria Evaluation - SMCE)
 * untuk menganalisis dan merekomendasikan titik lokasi pendirian usaha pariwisata
 * baru (Kafe/Restoran Wisata, Homestay/Penginapan, Sentra Oleh-oleh/Kriya UMKM)
 * berdasarkan analisis kesenjangan pasar (Market Gap Analysis) & Buffer Spasial.
 * 
 * FORMULA SPASIAL:
 * 1. Tarikan Permintaan Wisata (Tourism Demand Attraction Buffer: 5 km)
 * 2. Kejenuhan Pesaing Sejenis (Competitor Supply Density Buffer: 3 km)
 * 3. Rasio Kesenjangan Pasar: Gap = Demand / (Competitor + 1)
 * 4. Indeks Kelayakan Spasial (Spatial Suitability Index - SSI): Skala 0 - 100
 * ==============================================================================
 */

require_once __DIR__ . '/database.php';

class EvaluasiSpasialBisnis {

    private PDO $pdo;

    // Tipe Usaha yang didukung
    public const BUSINESS_TYPES = [
        'homestay' => [
            'label' => 'Homestay & Penginapan Wisata',
            'deskripsi' => 'Akomodasi ramah kantong di sekitar klaster wisata alam & bahari',
            'target_kategori' => 'Hotel',
            'radius_wisata_km' => 6.0,
            'radius_pesaing_km' => 3.0,
            'investasi_estimasi' => 'Rp 150 - 500 Juta',
            'icon' => 'fa-house-chimney'
        ],
        'kafe_wisata' => [
            'label' => 'Kafe Estetik & Kuliner Tepi Wisata',
            'deskripsi' => 'Tempat nongkrong santai dekat pantai/daya tarik dengan panorama alam',
            'target_kategori' => 'Restoran',
            'radius_wisata_km' => 4.0,
            'radius_pesaing_km' => 2.0,
            'investasi_estimasi' => 'Rp 75 - 250 Juta',
            'icon' => 'fa-mug-saucer'
        ],
        'sentra_umkm' => [
            'label' => 'Sentra Oleh-oleh & Kriya Khas Babel',
            'deskripsi' => 'Pusat belanja suvenir (Kain Cual, Kemplang/Getas, Kerajinan Timah)',
            'target_kategori' => 'Restoran',
            'radius_wisata_km' => 7.0,
            'radius_pesaing_km' => 4.0,
            'investasi_estimasi' => 'Rp 50 - 150 Juta',
            'icon' => 'fa-bag-shopping'
        ]
    ];

    public function __construct(?PDO $pdo = null) {
        $this->pdo = $pdo ?? getDbConnection();
    }

    /**
     * Hitung Jarak Haversine (km)
     */
    public static function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float {
        $earthRadius = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return round($earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a))), 2);
    }

    /**
     * Algoritma Utama: Analisis Kelayakan Spasial Lokasi Usaha (SMCE)
     * 
     * @param string $tipeUsaha Key tipe bisnis ('homestay', 'kafe_wisata', 'sentra_umkm')
     * @param string $kabupatenFilter Filter kabupaten (opsional)
     * @return array Hasil evaluasi zona kelayakan & titik rekomendasi strategis
     */
    public function evaluateSuitability(string $tipeUsaha = 'kafe_wisata', string $kabupatenFilter = ''): array {
        $config = self::BUSINESS_TYPES[$tipeUsaha] ?? self::BUSINESS_TYPES['kafe_wisata'];
        $radiusWisata = $config['radius_wisata_km'];
        $radiusPesaing = $config['radius_pesaing_km'];
        $targetKategori = $config['target_kategori'];

        // 1. Ambil data semua tempat wisata (Daya Tarik) dan amenitas (Pesaing)
        $sql = "
            SELECT id, nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude
            FROM tempat_wisata
            WHERE latitude IS NOT NULL AND longitude IS NOT NULL
              AND status_verifikasi = 'Resmi'
        ";
        $binds = [];
        if (!empty($kabupatenFilter)) {
            $sql .= " AND kabupaten = ?";
            $binds[] = $kabupatenFilter;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($binds);
        $allData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($allData)) {
            return [
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan untuk analisis wilayah yang dipilih.'
            ];
        }

        $wisataList = array_values(array_filter($allData, fn($d) => $d['kelompok'] === 'Daya Tarik'));
        $pesaingList = array_values(array_filter($allData, fn($d) => $d['kelompok'] === 'Amenitas' && $d['sub_kategori'] === $targetKategori));

        // 2. Evaluasi titik-titik jangkar (Anchor Points): Objek wisata sebagai pusat tarikan
        $evaluations = [];
        $heatmapPoints = [];

        foreach ($wisataList as $w) {
            $wLat = (float)$w['latitude'];
            $wLon = (float)$w['longitude'];

            // A. Hitung Tarikan Permintaan Wisatawan Sekitar (Radius $radiusWisata km)
            $nearbyWisataCount = 0;
            $demandVolumeScore = 0;

            foreach ($wisataList as $other) {
                $dist = self::haversine($wLat, $wLon, (float)$other['latitude'], (float)$other['longitude']);
                if ($dist <= $radiusWisata) {
                    $nearbyWisataCount++;
                    // Bobot tarikan = Rating * log10(ulasan + 1)
                    $demandVolumeScore += ((float)$other['rating'] * log10(max(1, (int)$other['ulasan']) + 1));
                }
            }

            // B. Hitung Kejenuhan Pesaing Sejenis Sekitar (Radius $radiusPesaing km)
            $nearbyCompetitorsCount = 0;
            foreach ($pesaingList as $pesaing) {
                $dist = self::haversine($wLat, $wLon, (float)$pesaing['latitude'], (float)$pesaing['longitude']);
                if ($dist <= $radiusPesaing) {
                    $nearbyCompetitorsCount++;
                }
            }

            // C. Hitung Rasio Kesenjangan Pasar (Market Opportunity Gap)
            // Rumus: Demand / (Competitor + 1)
            $gapRatio = $demandVolumeScore / ($nearbyCompetitorsCount + 1.0);

            // D. Hitung Spatial Suitability Index (SSI) [Skala 0 - 100]
            // Komponen: 50% Demand, 30% Market Gap, 20% Base Rating
            $normDemand = min(100.0, ($demandVolumeScore / 60.0) * 100.0);
            $normGap = min(100.0, ($gapRatio / 25.0) * 100.0);
            $normRating = ((float)$w['rating'] / 5.0) * 100.0;

            $ssi = (0.45 * $normDemand) + (0.40 * $normGap) + (0.15 * $normRating);
            $ssi = round(min(98.5, max(15.0, $ssi)), 1);

            // Kategori Kelayakan
            if ($ssi >= 78.0) {
                $statusKelayakan = 'Peluang Emas (Prime Gap)';
                $color = '#10b981'; // Emerald
                $rekomendasi = 'Sangat potensial! Kunjungan wisatawan tinggi sementara fasilitas sejenis masih sangat minim.';
            } elseif ($ssi >= 60.0) {
                $statusKelayakan = 'Potensial Tinggi';
                $color = '#0ea5e9'; // Sky
                $rekomendasi = 'Peluang bagus untuk penetrasi pasar dengan konsep unik atau harga kompetitif.';
            } elseif ($ssi >= 45.0) {
                $statusKelayakan = 'Pasar Kompetitif';
                $color = '#f59e0b'; // Amber
                $rekomendasi = 'Tingkat persaingan mulai padat. Butuh diferensiasi produk yang kuat.';
            } else {
                $statusKelayakan = 'Tahap Perintisan';
                $color = '#64748b'; // Slate
                $rekomendasi = 'Pasar masih berkembang. Lebih cocok untuk investasi jangka panjang bertahap.';
            }

            $evaluations[] = [
                'id_jangkar' => $w['id'],
                'nama_lokasi' => $w['nama'],
                'kabupaten' => $w['kabupaten'],
                'kecamatan' => $w['kecamatan'],
                'latitude' => $wLat,
                'longitude' => $wLon,
                'foto' => getDestinationPhoto($w['nama'], $w['sub_kategori'], $w['kabupaten']),
                'nearby_wisata' => $nearbyWisataCount,
                'nearby_pesaing' => $nearbyCompetitorsCount,
                'skor_demand' => round($demandVolumeScore, 1),
                'skor_ssi' => $ssi,
                'status_kelayakan' => $statusKelayakan,
                'warna_zona' => $color,
                'arahan_investasi' => $rekomendasi
            ];

            // Tambahkan ke data heatmap spasial: [lat, lon, intensity (0.0 - 1.0)]
            $heatmapPoints[] = [$wLat, $wLon, round($ssi / 100.0, 2)];
        }

        // Urutkan dari Skor SSI Tertinggi
        usort($evaluations, fn($a, $b) => $b['skor_ssi'] <=> $a['skor_ssi']);

        // Ambil Top 10 Titik Rekomendasi Teratas
        $topRecommendations = array_slice($evaluations, 0, 10);
        foreach ($topRecommendations as $idx => &$rec) {
            $rec['rank'] = $idx + 1;
        }
        unset($rec);

        return [
            'status' => 'success',
            'konfigurasi_analisis' => [
                'tipe_usaha' => $tipeUsaha,
                'nama_usaha' => $config['label'],
                'estimasi_modal' => $config['investasi_estimasi'],
                'radius_tarikan_wisata' => "{$radiusWisata} km",
                'radius_pantau_pesaing' => "{$radiusPesaing} km",
                'total_titik_dievaluasi' => count($evaluations),
                'wilayah_filter' => $kabupatenFilter ?: 'Seluruh Bangka Belitung'
            ],
            'top_rekomendasi' => $topRecommendations,
            'titik_heatmap' => $heatmapPoints
        ];
    }
}
