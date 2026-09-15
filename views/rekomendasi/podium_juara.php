<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      podium_juara.php
 * @folder    views/rekomendasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Podium 3 Destinasi Juara Rekomendasi TOPSIS Vektor - Zero Raster Images)
 * ==============================================================================
 */

$juara1 = $top10[0] ?? null;
$juara2 = $top10[1] ?? null;
$juara3 = $top10[2] ?? null;
?>

<!-- ============================================================== -->
<!-- 3. PODIUM 3 DESTINASI TERBAIK (CLEAN VECTOR METALLIC PODIUM)   -->
<!-- ============================================================== -->
<div class="space-y-4">
    
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i>
                <span>Podium 3 Destinasi Paling Direkomendasikan (TOPSIS)</span>
            </h3>
            <p class="text-xs text-slate-500">Peringkat 1, 2, dan 3 dengan nilai kedekatan relatif tertinggi terhadap solusi ideal positif (A+)</p>
        </div>
    </div>

    <!-- Panduan Cara Membaca Skor TOPSIS (Vi) -->
    <div class="p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl bg-amber-50/80 border border-amber-200/90 text-[11px] sm:text-xs text-amber-950 flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 sm:gap-2.5">
        <div class="flex items-center gap-1.5 shrink-0">
            <i class="fa-solid fa-circle-question text-amber-600 text-xs"></i>
            <span class="font-bold">Panduan Skor Preferensi ($V_i$):</span>
        </div>
        <div class="text-[10px] sm:text-[11px] text-slate-700 leading-snug sm:leading-relaxed">
            Skor berkisar <strong>0% s/d 100%</strong>. Makin mendekati 100%, makin memenuhi rating tinggi, ulasan banyak, fasilitas lengkap, dan akses efisien.
        </div>
    </div>

    <!-- Grid 3 Kartu Podium (Urutan IMK Visual: Juara 2 di Kiri, Juara 1 di Tengah Lebih Tinggi, Juara 3 di Kanan) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 sm:gap-5 items-end pt-1 sm:pt-2">
        
        <!-- JUARA 2: MEDALI PERAK -->
        <?php if ($juara2): ?>
        <div class="order-2 md:order-1 bg-gradient-to-b from-slate-100/80 via-white to-white rounded-2xl sm:rounded-3xl overflow-hidden border-2 border-slate-300 shadow-md hover:shadow-xl transition-all space-y-2.5 sm:space-y-4 p-3.5 sm:p-5 flex flex-col justify-between min-h-0 md:min-h-[360px]">
            <div class="space-y-2 sm:space-y-3.5">
                <!-- Header Lencana Medali Perak -->
                <div class="flex items-center justify-between">
                    <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-black bg-slate-200 text-slate-800 shadow-2xs flex items-center gap-1 sm:gap-1.5">
                        <i class="fa-solid fa-medal text-slate-500"></i>
                        <span>Juara 2 (Perak)</span>
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                        <?= htmlspecialchars($juara2['sub_kategori']) ?>
                    </span>
                </div>

                <!-- Callout Nilai TOPSIS -->
                <div class="p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl bg-slate-100/70 border border-slate-200 space-y-0.5 sm:space-y-1">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-500 block truncate">Skor Preferensi (V)</span>
                    <div class="flex items-baseline gap-1.5 sm:gap-2">
                        <span class="text-xl sm:text-3xl font-black text-slate-800 font-mono"><?= number_format($juara2['score_v'], 4) ?></span>
                        <span class="text-[10px] sm:text-[11px] text-slate-500 font-semibold">(<?= number_format($juara2['score_v'] * 100, 1) ?>%)</span>
                    </div>
                </div>

                <!-- Identitas Destinasi -->
                <div>
                    <h4 class="font-black text-slate-900 text-sm sm:text-base line-clamp-1"><?= htmlspecialchars($juara2['nama']) ?></h4>
                    <p class="text-[11px] sm:text-xs text-slate-500 truncate"><?= htmlspecialchars($juara2['kabupaten']) ?> &bull; Kec. <?= htmlspecialchars($juara2['kecamatan']) ?></p>
                </div>

                <!-- 4 Kriteria Penilaian -->
                <div class="grid grid-cols-2 gap-1.5 sm:gap-2 text-[10px] sm:text-[11px] bg-slate-50 p-2 sm:p-2.5 rounded-xl text-slate-600">
                    <div>Rating: <strong class="text-slate-900">★ <?= $juara2['rating'] ?></strong></div>
                    <div>Ulasan: <strong class="text-slate-900"><?= number_format($juara2['ulasan']) ?></strong></div>
                    <div>Transport: <strong class="text-slate-900"><?= round($juara2['hub_dist'], 1) ?> km</strong></div>
                    <div>Amenitas: <strong class="text-slate-900"><?= $juara2['amenities'] ?> Unit</strong></div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2 pt-1.5 sm:pt-2 border-t border-slate-100">
                <button onclick="openQrModal('<?= htmlspecialchars(addslashes($juara2['nama'])) ?>', <?= (float)($juara2['latitude'] ?? -2.1215) ?>, <?= (float)($juara2['longitude'] ?? 106.1130) ?>, '<?= htmlspecialchars(addslashes($juara2['kabupaten'])) ?>', '<?= htmlspecialchars(addslashes($juara2['sub_kategori'])) ?>')" class="p-1.5 sm:p-2 px-2.5 sm:px-3 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 hover:text-ocean-700 text-xs font-bold transition-all flex items-center justify-center" title="Kode QR Navigasi">
                    <i class="fa-solid fa-qrcode text-xs sm:text-sm"></i>
                </button>
                <a href="index.php?page=peta&search=<?= urlencode($juara2['nama']) ?>" class="flex-1 py-1.5 sm:py-2 rounded-xl bg-slate-100 hover:bg-ocean-600 text-slate-700 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1">
                    <i class="fa-solid fa-location-crosshairs text-xs"></i>
                    <span>Buka di Peta</span>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- JUARA 1: MEDALI EMAS (KARTU LEBIH BESAR & TINGGI) -->
        <?php if ($juara1): ?>
        <div class="order-1 md:order-2 bg-gradient-to-b from-amber-500/10 via-amber-500/5 to-white rounded-2xl sm:rounded-3xl overflow-hidden border-2 border-amber-400 shadow-xl hover:shadow-2xl transition-all space-y-3 sm:space-y-4 p-4 sm:p-6 flex flex-col justify-between min-h-0 md:min-h-[410px] transform md:-translate-y-3">
            <div class="space-y-2.5 sm:space-y-3.5">
                <!-- Header Lencana Medali Emas -->
                <div class="flex items-center justify-between">
                    <span class="px-3 sm:px-3.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-black bg-amber-400 text-slate-950 shadow-md flex items-center gap-1 sm:gap-1.5">
                        <i class="fa-solid fa-crown text-amber-900"></i>
                        <span>Rekomendasi Utama (Juara 1 Emas)</span>
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-black px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                        <?= htmlspecialchars($juara1['sub_kategori']) ?>
                    </span>
                </div>

                <!-- Callout Nilai TOPSIS Juara 1 -->
                <div class="p-3 sm:p-4 rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-100/90 to-amber-50/60 border border-amber-300 shadow-2xs space-y-0.5 sm:space-y-1">
                    <span class="text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider text-amber-900 block truncate">Skor Preferensi Tertinggi (V)</span>
                    <div class="flex items-baseline gap-1.5 sm:gap-2">
                        <span class="text-2xl sm:text-4xl font-black text-amber-700 font-mono"><?= number_format($juara1['score_v'], 4) ?></span>
                        <span class="text-[10px] sm:text-xs text-amber-900 font-extrabold">(<?= number_format($juara1['score_v'] * 100, 1) ?>% Solusi Ideal)</span>
                    </div>
                </div>

                <!-- Identitas Destinasi -->
                <div>
                    <h4 class="font-black text-slate-900 text-base sm:text-xl line-clamp-1"><?= htmlspecialchars($juara1['nama']) ?></h4>
                    <p class="text-[11px] sm:text-xs text-slate-500 truncate"><?= htmlspecialchars($juara1['kabupaten']) ?> &bull; Kec. <?= htmlspecialchars($juara1['kecamatan']) ?></p>
                </div>

                <!-- 4 Kriteria Penilaian -->
                <div class="grid grid-cols-2 gap-1.5 sm:gap-2 text-[10px] sm:text-xs bg-amber-50/80 p-2.5 sm:p-3 rounded-xl sm:rounded-2xl border border-amber-200 text-slate-700 font-medium">
                    <div>Kualitas: <strong class="text-amber-900 font-black">★ <?= $juara1['rating'] ?></strong></div>
                    <div>Ulasan: <strong class="text-amber-900 font-black"><?= number_format($juara1['ulasan']) ?></strong></div>
                    <div>Transport: <strong class="text-amber-900 font-black"><?= round($juara1['hub_dist'], 1) ?> km</strong></div>
                    <div>Amenitas: <strong class="text-amber-900 font-black"><?= $juara1['amenities'] ?> Unit</strong></div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2 pt-2 border-t border-amber-200/60">
                <button onclick="openQrModal('<?= htmlspecialchars(addslashes($juara1['nama'])) ?>', <?= (float)($juara1['latitude'] ?? -2.1215) ?>, <?= (float)($juara1['longitude'] ?? 106.1130) ?>, '<?= htmlspecialchars(addslashes($juara1['kabupaten'])) ?>', '<?= htmlspecialchars(addslashes($juara1['sub_kategori'])) ?>')" class="p-2 sm:p-2.5 px-2.5 sm:px-3 rounded-xl bg-white hover:bg-ocean-50 text-slate-800 hover:text-ocean-700 text-xs font-bold border border-amber-300 transition-all flex items-center justify-center shadow-xs" title="Kode QR Navigasi">
                    <i class="fa-solid fa-qrcode text-sm sm:text-base text-ocean-600"></i>
                </button>
                <a href="index.php?page=peta&search=<?= urlencode($juara1['nama']) ?>" class="flex-1 py-2 sm:py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white text-xs font-extrabold shadow-md shadow-ocean-600/20 transition-all text-center flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-route"></i>
                    <span>Rute Destinasi Terbaik</span>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- JUARA 3: MEDALI PERUNGGU -->
        <?php if ($juara3): ?>
        <div class="order-3 md:order-3 bg-gradient-to-b from-amber-700/10 via-white to-white rounded-2xl sm:rounded-3xl overflow-hidden border-2 border-amber-700/30 shadow-md hover:shadow-xl transition-all space-y-2.5 sm:space-y-4 p-3.5 sm:p-5 flex flex-col justify-between min-h-0 md:min-h-[360px]">
            <div class="space-y-2 sm:space-y-3.5">
                <!-- Header Lencana Medali Perunggu -->
                <div class="flex items-center justify-between">
                    <span class="px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-black bg-amber-700/20 text-amber-900 border border-amber-700/30 shadow-2xs flex items-center gap-1 sm:gap-1.5">
                        <i class="fa-solid fa-medal text-amber-700"></i>
                        <span>Juara 3 (Perunggu)</span>
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                        <?= htmlspecialchars($juara3['sub_kategori']) ?>
                    </span>
                </div>

                <!-- Callout Nilai TOPSIS -->
                <div class="p-2.5 sm:p-3.5 rounded-xl sm:rounded-2xl bg-amber-50/50 border border-amber-200/80 space-y-0.5 sm:space-y-1">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-500 block truncate">Skor Preferensi (V)</span>
                    <div class="flex items-baseline gap-1.5 sm:gap-2">
                        <span class="text-xl sm:text-3xl font-black text-amber-800 font-mono"><?= number_format($juara3['score_v'], 4) ?></span>
                        <span class="text-[10px] sm:text-[11px] text-slate-500 font-semibold">(<?= number_format($juara3['score_v'] * 100, 1) ?>%)</span>
                    </div>
                </div>

                <!-- Identitas Destinasi -->
                <div>
                    <h4 class="font-black text-slate-900 text-sm sm:text-base line-clamp-1"><?= htmlspecialchars($juara3['nama']) ?></h4>
                    <p class="text-[11px] sm:text-xs text-slate-500 truncate"><?= htmlspecialchars($juara3['kabupaten']) ?> &bull; Kec. <?= htmlspecialchars($juara3['kecamatan']) ?></p>
                </div>

                <!-- 4 Kriteria Penilaian -->
                <div class="grid grid-cols-2 gap-1.5 sm:gap-2 text-[10px] sm:text-[11px] bg-slate-50 p-2 sm:p-2.5 rounded-xl text-slate-600">
                    <div>Rating: <strong class="text-slate-900">★ <?= $juara3['rating'] ?></strong></div>
                    <div>Ulasan: <strong class="text-slate-900"><?= number_format($juara3['ulasan']) ?></strong></div>
                    <div>Transport: <strong class="text-slate-900"><?= round($juara3['hub_dist'], 1) ?> km</strong></div>
                    <div>Amenitas: <strong class="text-slate-900"><?= $juara3['amenities'] ?> Unit</strong></div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-2 pt-1.5 sm:pt-2 border-t border-slate-100">
                <button onclick="openQrModal('<?= htmlspecialchars(addslashes($juara3['nama'])) ?>', <?= (float)($juara3['latitude'] ?? -2.1215) ?>, <?= (float)($juara3['longitude'] ?? 106.1130) ?>, '<?= htmlspecialchars(addslashes($juara3['kabupaten'])) ?>', '<?= htmlspecialchars(addslashes($juara3['sub_kategori'])) ?>')" class="p-1.5 sm:p-2 px-2.5 sm:px-3 rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 hover:text-ocean-700 text-xs font-bold transition-all flex items-center justify-center" title="Kode QR Navigasi">
                    <i class="fa-solid fa-qrcode text-xs sm:text-sm"></i>
                </button>
                <a href="index.php?page=peta&search=<?= urlencode($juara3['nama']) ?>" class="flex-1 py-1.5 sm:py-2 rounded-xl bg-slate-100 hover:bg-ocean-600 text-slate-700 hover:text-white text-xs font-bold transition-all text-center flex items-center justify-center gap-1">
                    <i class="fa-solid fa-location-crosshairs text-xs"></i>
                    <span>Buka di Peta</span>
                </a>
            </div>
        </div>
        <?php endif; ?>

    </div>

</div>
