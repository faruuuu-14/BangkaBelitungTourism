<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      podium_juara.php
 * @folder    views/rekomendasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Podium 3 Destinasi Juara Rekomendasi TOPSIS dengan Foto Otentik)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen podium visual 3 destinasi terbaik (Juara 1 Emas, Juara 2 Perak, dan
 * Juara 3 Perunggu) hasil perankingan nilai preferensi V_i algoritma TOPSIS.
 * Dilengkapi foto otentik representatif, nilai skor preferensi (0.0000 - 1.0000),
 * lencana wilayah, rincian kriteria, dan tombol buka lokasi pada Peta Spasial GIS.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/rekomendasi/index.php
 * - Menggunakan array: $top10 (elemen indeks 0, 1, dan 2)
 * - Menggunakan helper foto: getDestinationPhoto($nama, $subKategori, $kabupaten)
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan sorotan visual (*visual highlight*) yang jelas dan membanggakan bagi
 * 3 destinasi wisata paling unggul di Bangka Belitung berdasarkan preferensi pengguna.
 * ==============================================================================
 */

$juara1 = $top10[0] ?? null;
$juara2 = $top10[1] ?? null;
$juara3 = $top10[2] ?? null;
?>

<!-- ============================================================== -->
<!-- 3. PODIUM 3 DESTINASI TERBAIK HASIL PERHITUNGAN TOPSIS         -->
<!-- ============================================================== -->
<div class="space-y-4">
    
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i>
                <span>Podium 3 Destinasi Paling Direkomendasikan</span>
            </h3>
            <p class="text-xs text-slate-500">Peringkat 1, 2, dan 3 dengan nilai kedekatan relatif tertinggi terhadap solusi ideal positif (A+)</p>
        </div>
    </div>

    <!-- Grid 3 Kartu Podium (Urutan IMK Visual: Juara 2 di Kiri, Juara 1 di Tengah Lebih Tinggi, Juara 3 di Kanan) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end pt-2">
        
        <!-- JUARA 2: MEDALI PERAK -->
        <?php if ($juara2): 
            $foto2 = getDestinationPhoto($juara2['nama'], $juara2['sub_kategori'], $juara2['kabupaten']);
        ?>
        <div class="order-2 md:order-1 bg-white rounded-3xl overflow-hidden border-2 border-slate-300 shadow-md hover:shadow-xl transition-all space-y-3 p-4 flex flex-col justify-between min-h-[380px]">
            <div class="space-y-3">
                <!-- Banner Foto Otentik Juara 2 -->
                <div class="relative h-40 rounded-2xl overflow-hidden bg-slate-900">
                    <img src="<?= $foto2 ?>" alt="<?= htmlspecialchars($juara2['nama']) ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                    <span class="absolute top-2 left-2 px-2.5 py-1 rounded-full text-xs font-black bg-slate-200 text-slate-800 shadow flex items-center gap-1">
                        <i class="fa-solid fa-medal text-slate-500"></i> Juara 2 (Perak)
                    </span>
                    <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-[10px] font-bold text-white">
                        V: <?= number_format($juara2['score_v'], 4) ?>
                    </span>
                </div>
                <div>
                    <h4 class="font-black text-slate-900 text-sm sm:text-base line-clamp-1"><?= htmlspecialchars($juara2['nama']) ?></h4>
                    <p class="text-xs text-slate-500"><?= htmlspecialchars($juara2['kabupaten']) ?></p>
                </div>
                <div class="grid grid-cols-2 gap-2 text-[11px] bg-slate-50 p-2.5 rounded-xl text-slate-600">
                    <div>Rating: <strong class="text-slate-900">★ <?= $juara2['rating'] ?></strong></div>
                    <div>Ulasan: <strong class="text-slate-900"><?= number_format($juara2['ulasan']) ?></strong></div>
                    <div>Transport: <strong class="text-slate-900"><?= round($juara2['hub_dist'], 1) ?> km</strong></div>
                    <div>Amenitas: <strong class="text-slate-900"><?= $juara2['amenities'] ?> Unit</strong></div>
                </div>
            </div>
            <a href="index.php?page=peta&search=<?= urlencode($juara2['nama']) ?>" class="w-full py-2 rounded-xl bg-slate-100 hover:bg-ocean-600 text-slate-700 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1">
                <i class="fa-solid fa-location-crosshairs text-xs"></i>
                <span>Buka di Peta</span>
            </a>
        </div>
        <?php endif; ?>

        <!-- JUARA 1: MEDALI EMAS (KARTU LEBIH BESAR & TINGGI) -->
        <?php if ($juara1): 
            $foto1 = getDestinationPhoto($juara1['nama'], $juara1['sub_kategori'], $juara1['kabupaten']);
        ?>
        <div class="order-1 md:order-2 bg-gradient-to-b from-amber-50/50 to-white rounded-3xl overflow-hidden border-2 border-amber-400 shadow-xl hover:shadow-2xl transition-all space-y-3 p-5 flex flex-col justify-between min-h-[420px] transform md:-translate-y-2">
            <div class="space-y-3">
                <!-- Banner Foto Otentik Juara 1 -->
                <div class="relative h-48 rounded-2xl overflow-hidden bg-slate-900 shadow-md">
                    <img src="<?= $foto1 ?>" alt="<?= htmlspecialchars($juara1['nama']) ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                    <span class="absolute top-2 left-2 px-3 py-1 rounded-full text-xs font-black bg-amber-400 text-slate-950 shadow-md flex items-center gap-1.5">
                        <i class="fa-solid fa-crown text-amber-800"></i> Rekomendasi Utama (Juara 1 Emas)
                    </span>
                    <span class="absolute bottom-2 right-2 px-2.5 py-1 rounded-md bg-black/70 backdrop-blur-md text-xs font-black text-amber-300 border border-amber-400/40">
                        Skor V: <?= number_format($juara1['score_v'], 4) ?>
                    </span>
                </div>
                <div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-ocean-50 text-ocean-700 uppercase tracking-wider">Paling Direkomendasikan</span>
                    <h4 class="font-black text-slate-900 text-base sm:text-lg line-clamp-1 mt-1"><?= htmlspecialchars($juara1['nama']) ?></h4>
                    <p class="text-xs text-slate-500"><?= htmlspecialchars($juara1['kabupaten']) ?> &bull; Kec. <?= htmlspecialchars($juara1['kecamatan']) ?></p>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs bg-amber-50/80 p-3 rounded-2xl border border-amber-200 text-slate-700 font-medium">
                    <div>Kualitas: <strong class="text-amber-900 font-black">★ <?= $juara1['rating'] ?></strong></div>
                    <div>Ulasan: <strong class="text-amber-900 font-black"><?= number_format($juara1['ulasan']) ?></strong></div>
                    <div>Transportasi: <strong class="text-amber-900 font-black"><?= round($juara1['hub_dist'], 1) ?> km</strong></div>
                    <div>Amenitas: <strong class="text-amber-900 font-black"><?= $juara1['amenities'] ?> Unit</strong></div>
                </div>
            </div>
            <a href="index.php?page=peta&search=<?= urlencode($juara1['nama']) ?>" class="w-full py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white text-xs font-extrabold shadow-md shadow-ocean-600/20 transition-all text-center flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-route"></i>
                <span>Eksplorasi Rute Destinasi Terbaik</span>
            </a>
        </div>
        <?php endif; ?>

        <!-- JUARA 3: MEDALI PERUNGGU -->
        <?php if ($juara3): 
            $foto3 = getDestinationPhoto($juara3['nama'], $juara3['sub_kategori'], $juara3['kabupaten']);
        ?>
        <div class="order-3 md:order-3 bg-white rounded-3xl overflow-hidden border-2 border-amber-700/40 shadow-md hover:shadow-xl transition-all space-y-3 p-4 flex flex-col justify-between min-h-[380px]">
            <div class="space-y-3">
                <!-- Banner Foto Otentik Juara 3 -->
                <div class="relative h-40 rounded-2xl overflow-hidden bg-slate-900">
                    <img src="<?= $foto3 ?>" alt="<?= htmlspecialchars($juara3['nama']) ?>" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                    <span class="absolute top-2 left-2 px-2.5 py-1 rounded-full text-xs font-black bg-amber-700/20 text-amber-900 border border-amber-700/30 shadow flex items-center gap-1">
                        <i class="fa-solid fa-medal text-amber-700"></i> Juara 3 (Perunggu)
                    </span>
                    <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-[10px] font-bold text-white">
                        V: <?= number_format($juara3['score_v'], 4) ?>
                    </span>
                </div>
                <div>
                    <h4 class="font-black text-slate-900 text-sm sm:text-base line-clamp-1"><?= htmlspecialchars($juara3['nama']) ?></h4>
                    <p class="text-xs text-slate-500"><?= htmlspecialchars($juara3['kabupaten']) ?></p>
                </div>
                <div class="grid grid-cols-2 gap-2 text-[11px] bg-slate-50 p-2.5 rounded-xl text-slate-600">
                    <div>Rating: <strong class="text-slate-900">★ <?= $juara3['rating'] ?></strong></div>
                    <div>Ulasan: <strong class="text-slate-900"><?= number_format($juara3['ulasan']) ?></strong></div>
                    <div>Transport: <strong class="text-slate-900"><?= round($juara3['hub_dist'], 1) ?> km</strong></div>
                    <div>Amenitas: <strong class="text-slate-900"><?= $juara3['amenities'] ?> Unit</strong></div>
                </div>
            </div>
            <a href="index.php?page=peta&search=<?= urlencode($juara3['nama']) ?>" class="w-full py-2 rounded-xl bg-slate-100 hover:bg-ocean-600 text-slate-700 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1">
                <i class="fa-solid fa-location-crosshairs text-xs"></i>
                <span>Buka di Peta</span>
            </a>
        </div>
        <?php endif; ?>

    </div>

</div>
