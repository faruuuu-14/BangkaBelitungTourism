<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA NUSANTARA (INDONESIA TOURISM HUB)
 * ==============================================================================
 * 
 * @file      destinasi_unggulan_nasional.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Katalog 5 Destinasi Super Prioritas Vektor Bento - Zero Raster Images)
 * ==============================================================================
 */

$dspList = [
    [
        'nama' => 'Kepulauan Raja Ampat',
        'provinsi' => 'Papua Barat Daya',
        'icon' => 'fa-water',
        'color' => 'sky',
        'badge' => 'bg-sky-50 text-sky-700 border-sky-200',
        'accent' => 'text-sky-600',
        'deskripsi' => 'Gugusan karst zamrud dengan 75% keanekaragaman spesies karang dunia di segitiga terumbu karang global.'
    ],
    [
        'nama' => 'Candi Borobudur',
        'provinsi' => 'Jawa Tengah',
        'icon' => 'fa-monument',
        'color' => 'amber',
        'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
        'accent' => 'text-amber-600',
        'deskripsi' => 'Monumen cagar budaya Buddha terbesar di dunia abad ke-8 warisan luhur peradaban UNESCO.'
    ],
    [
        'nama' => 'Taman Nasional Bromo',
        'provinsi' => 'Jawa Timur',
        'icon' => 'fa-mountain-sun',
        'color' => 'rose',
        'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
        'accent' => 'text-rose-600',
        'deskripsi' => 'Lautan pasir berbisik dan kawah vulkanik aktif spektakuler di kaldera Tengger Jawa Timur.'
    ],
    [
        'nama' => 'Labuan Bajo & Komodo',
        'provinsi' => 'Nusa Tenggara Timur',
        'icon' => 'fa-dragon',
        'color' => 'emerald',
        'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'accent' => 'text-emerald-600',
        'deskripsi' => 'Habitat kadal purba terbesar di dunia, perairan manta ray, dan lanskap bukit Pulau Padar.'
    ],
    [
        'nama' => 'Kaldera Danau Toba',
        'provinsi' => 'Sumatera Utara',
        'icon' => 'fa-volcano',
        'color' => 'indigo',
        'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'accent' => 'text-indigo-600',
        'deskripsi' => 'Danau vulkanik purba terbesar di Asia Tenggara yang dinobatkan sebagai UNESCO Global Geopark.'
    ]
];
?>

<!-- ============================================================== -->
<!-- 5. KATALOG DESTINASI SUPER PRIORITAS (CLEAN VECTOR BENTO)      -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-6">
    
    <!-- Kepala Bagian Galeri Nasional -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-ocean-50 text-ocean-700 border border-ocean-200/80 mb-1.5">
                <i class="fa-solid fa-crown text-[11px] text-amber-500"></i>
                <span>Destinasi Unggulan &amp; Super Prioritas Indonesia</span>
            </div>
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                Pesona Mahakarya Alam &amp; Warisan Budaya Dunia Nusantara
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Target ekspansi sistem informasi geospasial pariwisata terpadu lintas kepulauan Indonesia.
            </p>
        </div>
        <a href="index.php?page=peta&wilayah=nasional" class="self-start sm:self-auto text-xs font-bold text-ocean-600 hover:text-ocean-700 flex items-center gap-1.5 transition-colors">
            <span>Peta Nusantara</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <!-- Grid 5 Kartu Destinasi Nasional (Layout Dinamis & Modern) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        <?php foreach ($dspList as $dsp): ?>
        <div class="group relative rounded-2xl p-4 border border-slate-200/80 bg-slate-50/60 hover:bg-white hover:shadow-lg transition-all duration-300 flex flex-col justify-between space-y-3">
            <div class="space-y-2.5">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-xs border border-slate-200 flex items-center justify-center <?= $dsp['accent'] ?> text-base group-hover:scale-110 transition-transform">
                        <i class="fa-solid <?= $dsp['icon'] ?>"></i>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold <?= $dsp['badge'] ?> border">
                        DSP Nasional
                    </span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">
                        <?= $dsp['provinsi'] ?>
                    </span>
                    <h3 class="text-sm font-black text-slate-900 group-hover:text-ocean-600 transition-colors line-clamp-1">
                        <?= $dsp['nama'] ?>
                    </h3>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed line-clamp-3">
                    <?= $dsp['deskripsi'] ?>
                </p>
            </div>

            <div class="pt-2 border-t border-slate-200/60">
                <a href="index.php?page=peta&wilayah=nasional" class="inline-flex items-center gap-1.5 text-xs font-bold <?= $dsp['accent'] ?> hover:underline">
                    <i class="fa-solid fa-map-pin text-[11px]"></i>
                    <span>Tinjau di Peta</span>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>
