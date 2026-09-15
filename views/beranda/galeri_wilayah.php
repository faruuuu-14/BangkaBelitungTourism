<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      galeri_wilayah.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Direktori 7 Kabupaten/Kota & Budaya Bento Grid Vector - Zero Raster Images)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen direktori wilayah terpadu (Regional Directory Bento Grid).
 * Menggantikan kartu foto raster statis dengan kartu bento vektor modern:
 * 1. 100% berbasis Tailwind CSS & ikonografi FontAwesome (Zero KB Image Overhead).
 * 2. Menyajikan profil geostrategis 7 Kabupaten/Kota dan 1 pilar Warisan Budaya.
 * 3. Menghubungkan langsung pengguna ke Peta Spasial GIS dengan filter instan per wilayah.
 * ==============================================================================
 */

// Data konfigurasi direktori 7 Kabupaten/Kota & Budaya
$wilayahCards = [
    [
        'id' => 'pangkalpinang',
        'nama' => 'Kota Pangkalpinang',
        'julukan' => 'Pusat Pemerintahan & Urban Hub MICE',
        'icon' => 'fa-city',
        'color' => 'indigo',
        'bg_light' => 'bg-indigo-50/80',
        'text_accent' => 'text-indigo-700',
        'border' => 'border-indigo-200/80',
        'badge' => 'bg-indigo-600 text-white',
        'karakter' => 'Volume stayers tertinggi (23.235 orang), sentra kuliner, dan museum warisan timah nusantara.',
        'filter_kw' => 'Pangkalpinang'
    ],
    [
        'id' => 'bangka',
        'nama' => 'Kabupaten Bangka',
        'julukan' => 'Pusat Wisata Bahari & Pesisir',
        'icon' => 'fa-umbrella-beach',
        'color' => 'sky',
        'bg_light' => 'bg-sky-50/80',
        'text_accent' => 'text-sky-700',
        'border' => 'border-sky-200/80',
        'badge' => 'bg-sky-600 text-white',
        'karakter' => 'Garis pantai terpanjang dengan destinasi unggulan Pantai Tikus Emas, Matras, dan Puri Tri Agung.',
        'filter_kw' => 'Kabupaten Bangka'
    ],
    [
        'id' => 'belitung',
        'nama' => 'Kabupaten Belitung',
        'julukan' => 'UNESCO Global Geopark Belitong',
        'icon' => 'fa-gem',
        'color' => 'emerald',
        'bg_light' => 'bg-emerald-50/80',
        'text_accent' => 'text-emerald-700',
        'border' => 'border-emerald-200/80',
        'badge' => 'bg-emerald-600 text-white',
        'karakter' => 'Lama tinggal tertinggi (RLMT 2.08 malam), batu granit purba Tanjung Tinggi, dan Pulau Lengkuas.',
        'filter_kw' => 'Kabupaten Belitung'
    ],
    [
        'id' => 'bangka_tengah',
        'nama' => 'Kabupaten Bangka Tengah',
        'julukan' => 'Koridor Bisnis Bandara & Geowisata',
        'icon' => 'fa-route',
        'color' => 'amber',
        'bg_light' => 'bg-amber-50/80',
        'text_accent' => 'text-amber-700',
        'border' => 'border-amber-200/80',
        'badge' => 'bg-amber-600 text-white',
        'karakter' => 'Penyangga Bandara Depati Amir, Danau Kaolin Koba, dan ekowisata hutan mangrove pesisir.',
        'filter_kw' => 'Kabupaten Bangka Tengah'
    ],
    [
        'id' => 'bangka_barat',
        'nama' => 'Kabupaten Bangka Barat',
        'julukan' => 'Pusaka Sejarah & Kota Mentok',
        'icon' => 'fa-landmark',
        'color' => 'rose',
        'bg_light' => 'bg-rose-50/80',
        'text_accent' => 'text-rose-700',
        'border' => 'border-rose-200/80',
        'badge' => 'bg-rose-600 text-white',
        'karakter' => 'Cagar budaya nasional Pesanggrahan Menumbing, Pantai Tanjung Ular, dan gerbang feri Tanjung Kalian.',
        'filter_kw' => 'Kabupaten Bangka Barat'
    ],
    [
        'id' => 'bangka_selatan',
        'nama' => 'Kabupaten Bangka Selatan',
        'julukan' => 'Pesisir Bahari & Sentra Maritim',
        'icon' => 'fa-anchor',
        'color' => 'teal',
        'bg_light' => 'bg-teal-50/80',
        'text_accent' => 'text-teal-700',
        'border' => 'border-teal-200/80',
        'badge' => 'bg-teal-600 text-white',
        'karakter' => 'Batu Perahu Toboali, benteng cagar sejarah Toboali, dan sentra penghasil terasi udang khas Babel.',
        'filter_kw' => 'Kabupaten Bangka Selatan'
    ],
    [
        'id' => 'belitung_timur',
        'nama' => 'Kabupaten Belitung Timur',
        'julukan' => 'Wisata Sastra & Kota 1001 Kopi',
        'icon' => 'fa-book-open',
        'color' => 'purple',
        'bg_light' => 'bg-purple-50/80',
        'text_accent' => 'text-purple-700',
        'border' => 'border-purple-200/80',
        'badge' => 'bg-purple-600 text-white',
        'karakter' => 'Ikon literasi SD Laskar Pelangi Gantong, warung kopi Manggar, dan geopark Pulau Buku Limau.',
        'filter_kw' => 'Kabupaten Belitung Timur'
    ],
    [
        'id' => 'budaya_babel',
        'nama' => 'Warisan Budaya Takbenda',
        'julukan' => 'Kearifan Lokal Melayu-Tionghoa',
        'icon' => 'fa-hands-holding',
        'color' => 'slate',
        'bg_light' => 'bg-slate-100/80',
        'text_accent' => 'text-slate-800',
        'border' => 'border-slate-300/80',
        'badge' => 'bg-slate-900 text-white',
        'karakter' => 'Tenun Cual sutra emas, musik tradisi Dambus, adat Nganggung sepintu sedulang, dan Tari Campak.',
        'filter_kw' => 'Kebudayaan'
    ]
];
?>

<!-- ============================================================== -->
<!-- DIREKTORI 7 KABUPATEN/KOTA & BUDAYA (CLEAN BENTO GRID VEKTOR)  -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-6">
    
    <!-- Kepala Bagian Direktori -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-ocean-50 text-ocean-700 border border-ocean-200/80 mb-1.5">
                <i class="fa-solid fa-map-location-dot text-[11px]"></i>
                <span>Cakupan Lengkap 7 Wilayah Administratif &amp; Budaya</span>
            </div>
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                Direktori Geospasial &amp; Profil Kepariwisataan Daerah
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Pilih wilayah untuk memfilter peta interaktif dan mengakses karakteristik kunjungan berdasarkan data BPS.
            </p>
        </div>
        <a href="index.php?page=peta" class="self-start sm:self-auto text-xs font-bold text-ocean-600 hover:text-ocean-700 flex items-center gap-1.5 transition-colors">
            <span>Buka Seluruh Peta Babel</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <!-- Grid 8 Kartu Bento Wilayah (4 Kolom pada Desktop) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($wilayahCards as $c): ?>
        <div class="group relative rounded-2xl p-4 sm:p-5 border <?= $c['border'] ?> <?= $c['bg_light'] ?> hover:bg-white hover:shadow-lg transition-all duration-300 flex flex-col justify-between space-y-4">
            
            <div class="space-y-3">
                <!-- Header Kartu: Ikon & Lencana -->
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-xs border border-slate-200/80 flex items-center justify-center <?= $c['text_accent'] ?> text-base group-hover:scale-110 transition-transform">
                        <i class="fa-solid <?= $c['icon'] ?>"></i>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black <?= $c['badge'] ?> shadow-xs">
                        Terpetakan
                    </span>
                </div>

                <!-- Judul & Julukan Wilayah -->
                <div>
                    <h3 class="text-sm font-black text-slate-900 group-hover:text-ocean-600 transition-colors">
                        <?= $c['nama'] ?>
                    </h3>
                    <span class="text-[11px] font-semibold <?= $c['text_accent'] ?> block">
                        <?= $c['julukan'] ?>
                    </span>
                </div>

                <!-- Deskripsi Karakter BPS -->
                <p class="text-[11px] text-slate-600 leading-relaxed">
                    <?= $c['karakter'] ?>
                </p>
            </div>

            <!-- Tombol Aksi Langsung ke Peta -->
            <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                <a href="index.php?page=peta&search=<?= urlencode($c['filter_kw']) ?>" class="inline-flex items-center gap-1.5 text-xs font-bold <?= $c['text_accent'] ?> hover:underline">
                    <i class="fa-solid fa-location-dot text-[11px]"></i>
                    <span>Lihat di Peta</span>
                </a>
                <a href="index.php?page=analisis" class="text-[11px] font-semibold text-slate-400 hover:text-slate-700">
                    Analisis BPS &rarr;
                </a>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

</div>
