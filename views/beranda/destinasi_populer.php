<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      destinasi_populer.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (5 Destinasi Wisata Terpopuler dengan Avatar Foto Otentik)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen daftar destinasi wisata unggulan dengan tingkat interaksi wisatawan
 * tertinggi berdasarkan volume ulasan publik dan rating Google Maps.
 * Menampilkan avatar foto otentik, lencana kategori, bintang rating, dan tombol
 * langsung menuju lokasi pada Peta Spasial GIS.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php
 * - Menggunakan variabel data: $topDestinasi dari query basis data
 * - Menggunakan helper foto: getDestinationPhoto($nama, $subKategori, $kabupaten)
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan rekomendasi cepat berbasis bukti data publik (Social Proof)
 * bagi wisatawan yang mencari destinasi paling favorit di Bangka Belitung.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 6. TOP 5 DESTINASI WISATA TERPOPULER (DENGAN FOTO OTENTIK)     -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
    
    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h3 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                <span>5 Destinasi Wisata Paling Populer di Bangka Belitung</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Ulasan Tertinggi</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Destinasi dengan volume interaksi wisatawan tertinggi berdasarkan data terkurasi Google Maps</p>
        </div>
        <a href="index.php?page=rekomendasi" class="text-xs font-bold text-ocean-600 hover:text-ocean-700 flex items-center gap-1.5 transition-colors self-start sm:self-auto">
            <span>Lihat Pemeringkatan TOPSIS</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <!-- Tabel Daftar 5 Destinasi Terpopuler -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50/80 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="px-4 sm:px-6 py-3.5">Peringkat &amp; Destinasi</th>
                    <th class="px-4 py-3.5 hidden sm:table-cell">Wilayah Administratif</th>
                    <th class="px-4 py-3.5">Kategori</th>
                    <th class="px-4 py-3.5">Rating &amp; Ulasan</th>
                    <th class="px-4 sm:px-6 py-3.5 text-right">Aksi Spasial</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                <?php foreach ($topDestinasi as $idx => $item): 
                    $sub = $item['sub_kategori'];
                    $iconClass = 'fa-umbrella-beach';
                    $iconColor = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                    if ($sub === 'Kebudayaan') {
                        $iconClass = 'fa-landmark';
                        $iconColor = 'bg-rose-50 text-rose-600 border-rose-200';
                    } elseif ($sub === 'Buatan') {
                        $iconClass = 'fa-camera';
                        $iconColor = 'bg-amber-50 text-amber-600 border-amber-200';
                    } elseif ($sub === 'Hotel') {
                        $iconClass = 'fa-hotel';
                        $iconColor = 'bg-sky-50 text-sky-600 border-sky-200';
                    } elseif ($sub === 'Restoran') {
                        $iconClass = 'fa-utensils';
                        $iconColor = 'bg-teal-50 text-teal-600 border-teal-200';
                    }
                ?>
                <tr class="hover:bg-slate-50/70 transition-colors">
                    
                    <!-- Kolom 1: Peringkat, Ikon Vektor, & Nama Tempat -->
                    <td class="px-4 sm:px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center font-black text-xs shrink-0 <?= $idx === 0 ? 'bg-amber-400 text-slate-950 font-black shadow-xs' : ($idx === 1 ? 'bg-slate-300 text-slate-800' : ($idx === 2 ? 'bg-amber-200 text-amber-900' : 'bg-slate-100 text-slate-500')) ?>">
                                <?= $idx + 1 ?>
                            </span>
                            <div class="w-9 h-9 rounded-xl <?= $iconColor ?> border flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                <i class="fa-solid <?= $iconClass ?>"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="font-extrabold text-slate-900 text-xs sm:text-sm truncate">
                                    <?= htmlspecialchars($item['nama']) ?>
                                </div>
                                <div class="sm:hidden text-[10px] text-slate-400 mt-0.5">
                                    <?= htmlspecialchars($item['kabupaten']) ?>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Kolom 2: Kabupaten & Kecamatan -->
                    <td class="px-4 py-3.5 hidden sm:table-cell">
                        <div class="font-semibold text-slate-800"><?= htmlspecialchars($item['kabupaten']) ?></div>
                        <div class="text-[11px] text-slate-400">Kec. <?= htmlspecialchars($item['kecamatan']) ?></div>
                    </td>

                    <!-- Kolom 3: Sub Kategori -->
                    <td class="px-4 py-3.5">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $item['sub_kategori'] === 'Alam' ? 'bg-sky-50 text-sky-700 border border-sky-200' : ($item['sub_kategori'] === 'Kebudayaan' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200') ?>">
                            <?= htmlspecialchars($item['sub_kategori']) ?>
                        </span>
                    </td>

                    <!-- Kolom 4: Rating Bintang & Jumlah Ulasan -->
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1 text-amber-500 font-bold">
                            <i class="fa-solid fa-star text-xs"></i>
                            <span class="text-slate-900"><?= number_format($item['rating'], 1) ?></span>
                        </div>
                        <div class="text-[10px] text-slate-400 font-normal">
                            <?= number_format($item['ulasan']) ?> ulasan publik
                        </div>
                    </td>

                    <!-- Kolom 5: Tombol Aksi Spasial -->
                    <td class="px-4 sm:px-6 py-3.5 text-right">
                        <a href="index.php?page=peta&search=<?= urlencode($item['nama']) ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-ocean-50 hover:bg-ocean-600 text-ocean-700 hover:text-white font-bold text-xs border border-ocean-200 hover:border-ocean-600 transition-all shadow-xs">
                            <i class="fa-solid fa-location-crosshairs text-[11px]"></i>
                            <span>Buka di Peta</span>
                        </a>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
