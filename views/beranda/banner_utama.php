<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      banner_utama.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Hero Banner Latar Belakang Penuh Foto Otentik Babel)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen hero banner utama pada halaman beranda. Menggunakan teknik latar
 * belakang visual penuh (full-bleed background image) foto otentik Pulau Lengkuas
 * Belitung (assets/img/pantai_lengkuas.jpg) yang dilapisi gradasi gelap elegan
 * (cinematic dark overlay). Menyajikan judul sambutan megah, label kebanggaan
 * UNESCO Global Geopark Belitong, serta 4 tombol pintasan aksi cepat ke fitur utama.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php
 * - Menggunakan aset: assets/img/pantai_lengkuas.jpg
 * - Menautkan ke: index.php?page=peta, index.php?page=analisis, index.php?page=rekomendasi
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menciptakan daya tarik visual awal (first impression) yang imersif, profesional,
 * dan berkelas dunia, sekaligus memenuhi prinsip kontras tinggi (WCAG 2.1 AAA)
 * sehingga seluruh teks terbaca sangat tajam dan nyaman di mata pengguna.
 * ==============================================================================
 */
require_once __DIR__ . '/../../app/database.php';

$currentWilayah = getCurrentWilayah();
$isNasional = ($currentWilayah === 'nasional');

if ($isNasional) {
    $heroBgImage = 'assets/img/nasional/hero_indonesia.jpg';
    $heroFallbackBg = 'assets/img/bangka_belitung/pantai_lengkuas.jpg';
    $heroBadge1 = '<span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span><span>Sistem Informasi Intelijen &amp; Spasial Pariwisata Nusantara</span>';
    $heroBadge2 = '<i class="fa-solid fa-flag text-amber-400 text-[11px]"></i><span>Cakupan 14 Provinsi &bull; Pilot Bangka Belitung</span>';
    $heroTitle = 'Intelijen &amp; Spasial Pariwisata <span class="bg-gradient-to-r from-sky-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">Nusantara Indonesia</span>';
    $heroDesc = 'Platform geospasial analitik dan sistem pendukung keputusan pariwisata nasional. Menghubungkan ekosistem 14 provinsi wisata dengan <strong class="text-white font-bold">Provinsi Kepulauan Bangka Belitung</strong> sebagai wilayah percontohan utama (875 objek wisata terpetakan).';
    $heroSearchPlaceholder = 'Cari destinasi nusantara, pantai, cagar budaya, hotel, kuliner...';
    $heroCredit = 'Kepulauan Maritim &amp; Wisata Bahari Nusantara';
} else {
    $heroBgImage = 'assets/img/bangka_belitung/pantai_lengkuas.jpg';
    $heroFallbackBg = 'assets/img/bangka_belitung/pantai_lengkuas.jpg';
    $heroBadge1 = '<span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span><span>Portal Resmi Satu Data Spasial &amp; Intelijen Pariwisata</span>';
    $heroBadge2 = '<i class="fa-solid fa-gem text-amber-400 text-[11px]"></i><span>UNESCO Global Geopark Belitong</span>';
    $heroTitle = 'Eksplorasi Keindahan Alam &amp; Warisan Budaya <span class="bg-gradient-to-r from-sky-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">Bangka Belitung</span>';
    $heroDesc = 'Sistem informasi intelijen terintegrasi untuk memetakan <strong class="text-white font-bold">875 objek pariwisata</strong>, menganalisis rasio beban amenitas hotel &amp; restoran berbasis data resmi BPS, serta meranking destinasi unggulan menggunakan algoritma ilmiah SPK TOPSIS.';
    $heroSearchPlaceholder = 'Cari pantai, hotel, museum, atau kuliner khas Babel...';
    $heroCredit = 'Mercusuar 1882 Pulau Lengkuas, Sijuk - Kab. Belitung';
}
?>

<!-- ============================================================== -->
<!-- 1. HERO BANNER UTAMA (LATAR BELAKANG CLEAN MESH CSS TANPA FOTO)-->
<!-- ============================================================== -->
<div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xl sm:shadow-2xl border border-slate-800/80 w-full min-h-0 sm:min-h-[400px] flex items-center bg-gradient-to-br from-slate-950 via-slate-900 to-ocean-950">
    
    <!-- Subtle Radial Dot Matrix (Pure CSS, 0 KB image overhead) -->
    <div class="absolute inset-0 opacity-25 pointer-events-none" style="background-image: radial-gradient(rgba(56, 189, 248, 0.35) 1px, transparent 1px); background-size: 24px 24px;"></div>
    
    <!-- Soft Glow Accents -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-ocean-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    
    <!-- Konten Teks & Tombol Aksi -->
    <div class="relative z-10 w-full p-4 sm:p-8 lg:p-12 max-w-5xl space-y-3 sm:space-y-4">
        
        <!-- Label Badges Kebanggaan Wilayah -->
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
            <div class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3.5 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 backdrop-blur-md shadow-xs">
                <?= $heroBadge1 ?>
            </div>
            <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-300 border border-amber-400/30 backdrop-blur-md">
                <?= $heroBadge2 ?>
            </div>
        </div>

        <!-- Judul Utama Megah (Headline Adaptif) -->
        <h1 class="text-xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-snug sm:leading-tight">
            <?= $heroTitle ?>
        </h1>

        <!-- Deskripsi Ringkas & Konteks Nilai Guna Platform -->
        <p class="text-slate-200 text-xs sm:text-sm lg:text-base leading-relaxed max-w-2xl font-normal drop-shadow-xs line-clamp-2 sm:line-clamp-none">
            <?= $heroDesc ?>
        </p>

        <!-- Kotak Pencarian Instan (Instant Quick Search Bar) -->
        <div class="relative w-full max-w-xl z-20 pt-0.5">
            <div class="relative flex items-center">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs sm:text-sm"></i>
                <input type="text" id="heroQuickSearch" placeholder="<?= $heroSearchPlaceholder ?>"
                    autocomplete="off"
                    class="w-full pl-9 sm:pl-11 pr-20 sm:pr-24 py-2 sm:py-3 text-xs sm:text-sm rounded-xl sm:rounded-2xl bg-white/95 text-slate-900 placeholder-slate-400 font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-ocean-500 shadow-lg sm:shadow-xl border border-white/30 backdrop-blur-md"
                    oninput="handleHeroSearch(this.value)"
                    onkeydown="if(event.key==='Enter') submitHeroSearch()">
                <button onclick="submitHeroSearch()" class="absolute right-1.5 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-bold text-xs shadow-sm transition">
                    Cari
                </button>
            </div>
            <!-- Dropdown Hasil Pencarian Cepat -->
            <div id="heroSearchResults" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden hidden max-h-72 overflow-y-auto text-slate-800 z-50">
                <!-- Hasil dimasukkan secara dinamis via JS -->
            </div>
        </div>

        <!-- Tombol Aksi Pintas (Desktop/Tablet Saja - Mobile sudah terwakili di Bilah Bawah) -->
        <div class="hidden sm:flex flex-wrap items-center gap-2.5 sm:gap-3.5 pt-1">
            <?php if ($isNasional): ?>
                <!-- Aksi Nasional 1: Peta Spasial GIS -->
                <a href="index.php?page=peta&wilayah=nasional" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-ocean-600 hover:bg-ocean-500 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-ocean-600/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Eksplorasi Peta Wisata</span>
                </a>

                <!-- Aksi Nasional 2: Matriks Komparasi 14 Provinsi -->
                <a href="#komparasiProvinsi" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-scale-balanced text-amber-400"></i>
                    <span>Komparasi 14 Provinsi</span>
                </a>

                <!-- Aksi Nasional 3: Buka Wilayah Percontohan Babel -->
                <a href="index.php?page=beranda&wilayah=babel" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-emerald-600/80 hover:bg-emerald-600 text-white font-bold text-xs sm:text-sm border border-emerald-400/40 backdrop-blur-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-gem text-amber-300"></i>
                    <span>Buka Pilot Babel (875 Objek)</span>
                </a>
            <?php else: ?>
                <!-- Aksi Babel 1: Peta Spasial GIS -->
                <a href="index.php?page=peta&wilayah=babel" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-ocean-600 hover:bg-ocean-500 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-ocean-600/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Eksplorasi Peta Babel</span>
                </a>

                <!-- Aksi Babel 2: Analisis BPS & Beban Amenitas -->
                <a href="index.php?page=analisis&wilayah=babel" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-amber-400"></i>
                    <span>Analisis Statistik BPS</span>
                </a>

                <!-- Aksi Babel 3: Rekomendasi Destinasi TOPSIS -->
                <a href="index.php?page=rekomendasi&wilayah=babel" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-ranking-star text-emerald-400"></i>
                    <span>Rekomendasi Destinasi</span>
                </a>
            <?php endif; ?>
        </div>

    </div>

</div>

<script>
// Data tempat untuk pencarian instan
const heroSearchData = <?= json_encode($searchPlaces ?? []) ?>;

function handleHeroSearch(q) {
    const val = (q || '').trim().toLowerCase();
    const resultsContainer = document.getElementById('heroSearchResults');
    if (!val || val.length < 2) {
        resultsContainer.classList.add('hidden');
        resultsContainer.innerHTML = '';
        return;
    }

    const matches = heroSearchData.filter(p => 
        p.nama.toLowerCase().includes(val) || 
        p.kabupaten.toLowerCase().includes(val) || 
        (p.kecamatan && p.kecamatan.toLowerCase().includes(val))
    ).slice(0, 5);

    if (matches.length === 0) {
        resultsContainer.innerHTML = `
            <div class="p-4 text-center text-xs text-slate-500">
                Tidak ada destinasi cocok dengan "<strong>${val}</strong>".
            </div>
        `;
        resultsContainer.classList.remove('hidden');
        return;
    }

    let html = '';
    matches.forEach(p => {
        const photo = getHeroItemPhoto(p.nama, p.sub_kategori, p.kabupaten);
        html += `
            <a href="index.php?page=peta&search=${encodeURIComponent(p.nama)}" 
                class="p-2.5 flex items-center justify-between gap-3 hover:bg-slate-50 border-b border-slate-100 last:border-0 transition-colors">
                <div class="flex items-center gap-2.5 min-w-0">
                    <img src="${photo}" alt="${p.nama}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shrink-0">
                    <div class="min-w-0">
                        <div class="font-bold text-xs text-slate-900 truncate">${p.nama}</div>
                        <div class="text-[10px] text-slate-500 flex items-center gap-1.5 mt-0.5">
                            <span class="font-semibold text-ocean-700">${p.sub_kategori}</span>
                            <span>&bull;</span>
                            <span>${p.kabupaten}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1 shrink-0 text-amber-500 font-bold text-xs">
                    <i class="fa-solid fa-star text-[10px]"></i>
                    <span>${parseFloat(p.rating).toFixed(1)}</span>
                </div>
            </a>
        `;
    });

    resultsContainer.innerHTML = html;
    resultsContainer.classList.remove('hidden');
}

function submitHeroSearch() {
    const q = document.getElementById('heroQuickSearch').value.trim();
    if (q) {
        window.location.href = `index.php?page=peta&search=${encodeURIComponent(q)}`;
    }
}

function getHeroItemPhoto(nama, sub, kab) {
    const n = (nama || '').toLowerCase();
    const k = (kab || '').toLowerCase();

    // Deteksi foto destinasi super prioritas nasional
    if (n.includes('raja ampat') || k.includes('raja ampat')) return 'assets/img/nasional/raja_ampat.jpg';
    if (n.includes('borobudur') || k.includes('magelang')) return 'assets/img/nasional/borobudur.jpg';
    if (n.includes('bromo') || k.includes('probolinggo')) return 'assets/img/nasional/bromo.jpg';
    if (n.includes('labuan bajo') || k.includes('manggarai')) return 'assets/img/nasional/labuan_bajo.jpg';
    if (n.includes('danau toba') || k.includes('samosir')) return 'assets/img/nasional/danau_toba.jpg';

    // Deteksi foto otentik Bangka Belitung dari folder khusus bangka_belitung/
    if (n.includes('tikus emas') || n.includes('parai')) return 'assets/img/bangka_belitung/pantai_tikus_emas.jpg';
    if (n.includes('tanjung ular') || n.includes('menumbing') || k.includes('barat')) return 'assets/img/bangka_belitung/pantai_tanjung_ular.jpg';
    if (n.includes('kaolin') || k.includes('tengah')) return 'assets/img/bangka_belitung/danau_kaolin_koba.jpg';
    if (n.includes('batu perahu') || k.includes('selatan')) return 'assets/img/bangka_belitung/pantai_batu_perahu.jpg';
    if (n.includes('lengkuas')) return 'assets/img/bangka_belitung/pantai_lengkuas.jpg';
    if (n.includes('tanjung tinggi') || k.includes('belitung')) return 'assets/img/bangka_belitung/pantai_tanjung_tinggi.jpg';
    if (n.includes('gantong') || k.includes('timur')) return 'assets/img/bangka_belitung/laskar_pelangi_gantong.jpg';
    if (n.includes('museum') || n.includes('timah') || k.includes('pangkalpinang')) return 'assets/img/bangka_belitung/museum_timah_babel.jpg';
    if (sub === 'Kebudayaan') return 'assets/img/bangka_belitung/kain_cual_bangka.jpg';
    if (sub === 'Restoran') return 'assets/img/bangka_belitung/budaya_nganggung.jpg';
    return 'assets/img/bangka_belitung/pantai_tikus_emas.jpg';
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(e) {
    const searchBox = document.getElementById('heroQuickSearch');
    const results = document.getElementById('heroSearchResults');
    if (searchBox && results && !searchBox.contains(e.target) && !results.contains(e.target)) {
        results.classList.add('hidden');
    }
});
</script>
