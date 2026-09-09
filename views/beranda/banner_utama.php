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
?>

<!-- ============================================================== -->
<!-- 1. HERO BANNER UTAMA (LATAR BELAKANG FOTO LENGKUAS PENUH)      -->
<!-- ============================================================== -->
<div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-800/80 w-full min-h-[360px] sm:min-h-[420px] lg:min-h-[460px] flex items-center">
    
    <!-- Latar Belakang Foto Panorama Otentik Babel -->
    <img src="assets/img/pantai_lengkuas.jpg" alt="Panorama Bahari Pulau Lengkuas Bangka Belitung" 
        class="absolute inset-0 w-full h-full object-cover object-center filter brightness-90 transform scale-100 transition-transform duration-1000 hover:scale-105">
    
    <!-- Lapisan Gradasi Sinematik Gelap (High-Contrast Dark Gradient Overlay) -->
    <!-- Memastikan keterbacaan teks 100% tajam dan elegan tanpa mengaburkan keindahan foto -->
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/80 to-slate-900/40"></div>
    
    <!-- Konten Teks & Tombol Aksi di Atas Foto -->
    <div class="relative z-10 w-full p-6 sm:p-10 lg:p-14 max-w-4xl space-y-5">
        
        <!-- Label Badge Kebanggaan Provinsi Kepulauan Bangka Belitung -->
        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 backdrop-blur-md shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Portal Resmi Satu Data Spasial &amp; Intelijen Pariwisata</span>
            </div>
            <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-300 border border-amber-400/30 backdrop-blur-md">
                <i class="fa-solid fa-gem text-amber-400 text-[11px]"></i>
                <span>UNESCO Global Geopark Belitong</span>
            </div>
        </div>

        <!-- Judul Utama Megah (Headline) -->
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight sm:leading-tight">
            Eksplorasi Keindahan Alam &amp; Warisan Budaya <span class="bg-gradient-to-r from-sky-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">Bangka Belitung</span>
        </h1>

        <!-- Deskripsi Ringkas & Konteks Nilai Guna Platform -->
        <p class="text-slate-200 text-xs sm:text-sm lg:text-base leading-relaxed max-w-2xl font-normal drop-shadow-xs">
            Sistem informasi intelijen terintegrasi untuk memetakan <strong class="text-white font-bold">875 objek pariwisata</strong>, menganalisis rasio beban amenitas hotel &amp; restoran berbasis data resmi BPS, serta meranking destinasi unggulan menggunakan algoritma ilmiah SPK TOPSIS.
        </p>

        <!-- Kotak Pencarian Instan (Instant Quick Search Bar) -->
        <div class="relative w-full max-w-xl z-20 pt-1">
            <div class="relative flex items-center">
                <i class="fa-solid fa-magnifying-glass absolute left-4 text-slate-400 text-sm"></i>
                <input type="text" id="heroQuickSearch" placeholder="Cari pantai, hotel, museum, atau kuliner khas Babel..."
                    autocomplete="off"
                    class="w-full pl-11 pr-24 py-3 sm:py-3.5 text-xs sm:text-sm rounded-2xl bg-white/95 text-slate-900 placeholder-slate-400 font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-ocean-500 shadow-xl border border-white/30 backdrop-blur-md"
                    oninput="handleHeroSearch(this.value)"
                    onkeydown="if(event.key==='Enter') submitHeroSearch()">
                <button onclick="submitHeroSearch()" class="absolute right-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-bold text-xs shadow-sm transition">
                    Cari
                </button>
            </div>
            <!-- Dropdown Hasil Pencarian Cepat -->
            <div id="heroSearchResults" class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden hidden max-h-72 overflow-y-auto text-slate-800 z-50">
                <!-- Hasil dimasukkan secara dinamis via JS -->
            </div>
        </div>

        <!-- Tombol Aksi Pintas (Modern Glassmorphic Quick Action Buttons) -->
        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3.5 pt-1">
            <!-- Aksi 1: Peta Spasial GIS -->
            <a href="index.php?page=peta" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-ocean-600 hover:bg-ocean-500 text-white font-extrabold text-xs sm:text-sm shadow-lg shadow-ocean-600/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Eksplorasi Peta Wisata</span>
            </a>

            <!-- Aksi 2: Analisis BPS & Beban Amenitas -->
            <a href="index.php?page=analisis" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-amber-400"></i>
                <span>Analisis Statistik BPS</span>
            </a>

            <!-- Aksi 3: Rekomendasi Destinasi TOPSIS -->
            <a href="index.php?page=rekomendasi" class="px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fa-solid fa-ranking-star text-emerald-400"></i>
                <span>Rekomendasi TOPSIS</span>
            </a>

            <!-- Aksi 4: Buku Panduan Pengguna -->
            <button onclick="openGuideModal('dashboard')" class="px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-2xl bg-slate-900/80 hover:bg-slate-800 text-slate-300 hover:text-white font-semibold text-xs sm:text-sm border border-white/10 backdrop-blur-md transition-all flex items-center gap-1.5">
                <i class="fa-solid fa-circle-question text-xs"></i>
                <span>Panduan</span>
            </button>
        </div>

    </div>

    <!-- Badge Sudut Kanan Bawah: Info Lokasi Foto Latar Belakang -->
    <div class="hidden md:flex absolute bottom-4 right-4 z-10 items-center gap-2 px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-md border border-white/20 text-white text-[11px]">
        <i class="fa-solid fa-camera text-amber-400 text-xs"></i>
        <span>Mercusuar 1882 Pulau Lengkuas, Sijuk - Kab. Belitung</span>
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
    if (n.includes('tikus emas') || n.includes('parai')) return 'assets/img/pantai_tikus_emas.jpg';
    if (n.includes('tanjung ular') || n.includes('menumbing') || k.includes('barat')) return 'assets/img/pantai_tanjung_ular.jpg';
    if (n.includes('kaolin') || k.includes('tengah')) return 'assets/img/danau_kaolin_koba.jpg';
    if (n.includes('batu perahu') || k.includes('selatan')) return 'assets/img/pantai_batu_perahu.jpg';
    if (n.includes('lengkuas')) return 'assets/img/pantai_lengkuas.jpg';
    if (n.includes('tanjung tinggi') || k.includes('belitung')) return 'assets/img/pantai_tanjung_tinggi.jpg';
    if (n.includes('gantong') || k.includes('timur')) return 'assets/img/laskar_pelangi_gantong.jpg';
    if (n.includes('museum') || n.includes('timah') || k.includes('pangkalpinang')) return 'assets/img/museum_timah_babel.jpg';
    if (sub === 'Kebudayaan') return 'assets/img/kain_cual_bangka.jpg';
    if (sub === 'Restoran') return 'assets/img/budaya_nganggung.jpg';
    return 'assets/img/pantai_tikus_emas.jpg';
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
