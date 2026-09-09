<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      widget_musim.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Widget Cerdas Musim Bahari & Keselamatan Wisata Pesisir)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen widget meteorologi dan oseanografi pariwisata kepulauan Bangka Belitung.
 * Menganalisis siklus angin muson regional (Musim Barat vs Musim Timur) secara
 * dinamis berdasarkan kalender tahunan, memberikan informasi tinggi gelombang,
 * jarak pandang bawah air, serta arahan keselamatan berwisata bahari.
 * 
 * KETERKAITAN ALUR:
 * - Dimuat oleh: views/beranda/index.php
 * - Menautkan ke: index.php?page=peta&search=pantai
 * ==============================================================================
 */

$bulanIni = (int)date('n'); // 1 s/d 12

// Logika Karakteristik Musim Kepulauan Bangka Belitung (BMKG & Pesisir)
if ($bulanIni >= 4 && $bulanIni <= 10) {
    // Musim Timur (Kondisi Laut Terbaik)
    $musimNama = 'Musim Angin Timur (Musim Kemarau Bahari)';
    $statusBahari = 'Sangat Aman & Kondusif untuk Wisata Bahari';
    $statusBadge = 'bg-emerald-500 text-white';
    $ombak = '0.2 – 0.8 meter (Tenang)';
    $visibilitas = '15 – 25 meter (Sangat Jernih)';
    $suhuLaut = '28 – 30 °C';
    $angin = 'Tenggara – Timur (8 – 14 knot)';
    $rekomendasi = 'Waktu puncak (golden period) terbaik untuk island hopping ke Pulau Lengkuas, snorkeling terumbu karang Tanjung Kelayang, dan jelajah pulau pasir.';
    $fokusKategori = 'Snorkeling, Hopping Island & Geosite Granit';
} elseif ($bulanIni >= 11 || $bulanIni <= 2) {
    // Musim Barat (Angin & Hujan)
    $musimNama = 'Musim Angin Barat (Musim Hujan Pesisir)';
    $statusBahari = 'Waspada Gelombang Sedang di Perairan Terbuka';
    $statusBadge = 'bg-amber-500 text-slate-950';
    $ombak = '1.2 – 2.2 meter (Sedang)';
    $visibilitas = '5 – 10 meter (Fluktuatif)';
    $suhuLaut = '27 – 29 °C';
    $angin = 'Barat Laut – Utara (14 – 22 knot)';
    $rekomendasi = 'Lebih disarankan menikmati pantai teluk terlindung (Pantai Parai / Tikus Emas), wisata sejarah cagar budaya Muntok, Museum Timah, dan Geopark Danau Kaolin.';
    $fokusKategori = 'Wisata Budaya, Museum, Cagar Sejarah & Geopark';
} else {
    // Maret (Pancaroba)
    $musimNama = 'Masa Transisi Pancaroba (Peralihan Musim)';
    $statusBahari = 'Kondusif dengan Fluktuasi Angin Harian';
    $statusBadge = 'bg-sky-500 text-white';
    $ombak = '0.5 – 1.2 meter (Rendah - Sedang)';
    $visibilitas = '10 – 18 meter (Baik)';
    $suhuLaut = '28 – 30 °C';
    $angin = 'Variabel / Berubah Arah (6 – 12 knot)';
    $rekomendasi = 'Kondisi perairan relatif baik di pagi hingga siang hari. Perhatikan panduan nakhoda kapal perahu tradisional saat menyeberang pulau.';
    $fokusKategori = 'Fotografi Bahari, Pantai Landai & Wisata Kuliner';
}
?>

<!-- ============================================================== -->
<!-- WIDGET INDIKATOR MUSIM BAHARI & KESELAMATAN PESISIR BABEL      -->
<!-- ============================================================== -->
<div class="rounded-3xl p-5 sm:p-6 bg-gradient-to-br from-slate-900 via-ocean-950 to-slate-900 text-white shadow-xl border border-ocean-800/40 relative overflow-hidden">
    
    <!-- Aksen Latar Belakang Geometris Khas Kelautan -->
    <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-ocean-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-0 right-1/4 w-40 h-40 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        
        <!-- Sisi Kiri: Status Musim & Rekomendasi Oseanografi -->
        <div class="space-y-2.5 max-w-2xl">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-ocean-500/20 text-ocean-300 border border-ocean-400/30 flex items-center gap-1.5">
                    <i class="fa-solid fa-compass-drafting text-xs"></i>
                    <span>Oseanografi &amp; Wisata Bahari Babel</span>
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold <?= $statusBadge ?> shadow-xs">
                    <?= $statusBahari ?>
                </span>
            </div>

            <h3 class="text-base sm:text-xl font-black text-white tracking-tight leading-snug">
                <?= $musimNama ?> &bull; <span class="text-amber-300"><?= date('F Y') ?></span>
            </h3>

            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                <?= $rekomendasi ?>
            </p>

            <div class="flex items-center gap-2 pt-1 text-[11px] text-ocean-300 font-semibold">
                <i class="fa-solid fa-bullseye text-xs text-amber-400"></i>
                <span>Aktivitas Utama: <strong><?= $fokusKategori ?></strong></span>
            </div>
        </div>

        <!-- Sisi Kanan: 4 Kartu Parameter Maritim -->
        <div class="grid grid-cols-2 gap-2.5 w-full lg:w-auto shrink-0 text-xs">
            
            <!-- Parameter 1: Ombak -->
            <div class="p-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 space-y-0.5 min-w-[130px]">
                <span class="text-[10px] uppercase font-bold text-slate-400 flex items-center gap-1">
                    <i class="fa-solid fa-water text-sky-400"></i> Tinggi Ombak
                </span>
                <div class="font-extrabold text-xs sm:text-sm text-white"><?= $ombak ?></div>
            </div>

            <!-- Parameter 2: Visibilitas Bawah Air -->
            <div class="p-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 space-y-0.5 min-w-[130px]">
                <span class="text-[10px] uppercase font-bold text-slate-400 flex items-center gap-1">
                    <i class="fa-solid fa-mask-snorkel text-emerald-400"></i> Kejernihan Air
                </span>
                <div class="font-extrabold text-xs sm:text-sm text-emerald-300"><?= $visibilitas ?></div>
            </div>

            <!-- Parameter 3: Kecepatan Angin -->
            <div class="p-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 space-y-0.5 min-w-[130px]">
                <span class="text-[10px] uppercase font-bold text-slate-400 flex items-center gap-1">
                    <i class="fa-solid fa-wind text-amber-400"></i> Arah Angin
                </span>
                <div class="font-extrabold text-[11px] text-white"><?= $angin ?></div>
            </div>

            <!-- Parameter 4: Suhu Air Laut -->
            <div class="p-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 space-y-0.5 min-w-[130px]">
                <span class="text-[10px] uppercase font-bold text-slate-400 flex items-center gap-1">
                    <i class="fa-solid fa-temperature-three-quarters text-rose-400"></i> Suhu Permukaan
                </span>
                <div class="font-extrabold text-xs sm:text-sm text-white"><?= $suhuLaut ?></div>
            </div>

        </div>

    </div>

</div>
