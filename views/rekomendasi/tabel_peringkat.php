<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      tabel_peringkat.php
 * @folder    views/rekomendasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Tabel 10 Besar Hasil Rekomendasi TOPSIS dengan Foto Otentik)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen tabel hasil perankingan komparatif 10 destinasi terbaik. Menyajikan
 * peringkat, thumbnail avatar foto otentik objek, nilai preferensi V_i, jarak
 * ke solusi ideal positif (D+) dan solusi ideal negatif (D-), serta tombol rute spasial.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/rekomendasi/index.php
 * - Menggunakan array: $top10
 * - Helper foto: getDestinationPhoto($nama, $subKategori, $kabupaten)
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menyajikan hasil pemeringkatan secara transparan, lengkap, dan rapi dalam format tabel.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- 4. TABEL 10 BESAR HASIL REKOMENDASI TOPSIS                    -->
<!-- ============================================================== -->
<div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden space-y-3 sm:space-y-4 p-3.5 sm:p-7">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-2.5 sm:pb-3 border-b border-slate-100 gap-1.5 sm:gap-2">
        <div>
            <h3 class="text-xs sm:text-lg font-black text-slate-900 tracking-tight flex items-center gap-1.5 sm:gap-2">
                <span>Daftar 10 Besar Hasil Rekomendasi TOPSIS</span>
                <span class="text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Hasil TOPSIS</span>
            </h3>
            <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5">Nilai preferensi V dihitung dari rasio jarak relatif terhadap solusi ideal (A+ / A-)</p>
        </div>
        <button onclick="openModalRumusTopsis()" class="text-[11px] sm:text-xs font-bold text-ocean-600 hover:text-ocean-700 flex items-center gap-1.5 self-start sm:self-auto transition-colors">
            <i class="fa-solid fa-square-root-variable"></i>
            <span>Transparansi Rumus</span>
        </button>
    </div>

    <!-- Tabel Data 10 Besar -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-[10px] sm:text-[11px] uppercase font-bold text-slate-400 border-b border-slate-200">
                <tr>
                    <th class="px-2.5 sm:px-4 py-2 sm:py-3.5">Destinasi</th>
                    <th class="px-3 sm:px-4 py-2 sm:py-3.5 hidden md:table-cell">Wilayah</th>
                    <th class="px-2 sm:px-3 py-2 sm:py-3.5 text-center">Rating</th>
                    <th class="px-2 sm:px-3 py-2 sm:py-3.5 text-center hidden sm:table-cell">Ulasan</th>
                    <th class="px-3 py-3.5 text-center hidden lg:table-cell">Jarak Simpul</th>
                    <th class="px-3 py-3.5 text-center hidden lg:table-cell">Amenitas</th>
                    <th class="px-2 sm:px-4 py-2 sm:py-3.5 text-center">Skor (V)</th>
                    <th class="px-2.5 sm:px-4 py-2 sm:py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                <?php foreach ($top10 as $idx => $item): 
                    $foto = getDestinationPhoto($item['nama'], $item['sub_kategori'], $item['kabupaten']);
                    $rank = $idx + 1;
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors">
                    
                    <!-- Destinasi & Foto -->
                    <td class="px-2.5 sm:px-4 py-2 sm:py-3.5">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center font-black text-[10px] sm:text-xs shrink-0 <?= $rank === 1 ? 'bg-amber-400 text-slate-950 font-black' : ($rank === 2 ? 'bg-slate-300 text-slate-800' : ($rank === 3 ? 'bg-amber-700/30 text-amber-900' : 'bg-slate-100 text-slate-600')) ?>">
                                <?= $rank ?>
                            </span>
                            <img src="<?= $foto ?>" alt="<?= htmlspecialchars($item['nama']) ?>" class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl object-cover object-center border border-slate-200 shadow-2xs shrink-0">
                            <div class="min-w-0">
                                <div class="font-extrabold text-slate-900 text-xs sm:text-sm truncate">
                                    <?= htmlspecialchars($item['nama']) ?>
                                </div>
                                <div class="text-[9px] sm:text-[10px] text-slate-400 truncate">
                                    <?= htmlspecialchars($item['sub_kategori']) ?> &bull; Kec. <?= htmlspecialchars($item['kecamatan']) ?>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Wilayah -->
                    <td class="px-3 sm:px-4 py-2 sm:py-3.5 hidden md:table-cell font-semibold text-slate-700">
                        <?= htmlspecialchars($item['kabupaten']) ?>
                    </td>

                    <!-- Rating -->
                    <td class="px-2 sm:px-3 py-2 sm:py-3.5 text-center font-bold text-amber-600 text-[11px] sm:text-xs">
                        ★ <?= number_format($item['rating'], 1) ?>
                    </td>

                    <!-- Ulasan -->
                    <td class="px-2 sm:px-3 py-2 sm:py-3.5 text-center font-semibold text-slate-700 text-[11px] sm:text-xs hidden sm:table-cell">
                        <?= number_format($item['ulasan']) ?>
                    </td>

                    <!-- Jarak Transportasi -->
                    <td class="px-3 py-3.5 text-center font-semibold text-slate-700 hidden lg:table-cell">
                        <?= round($item['hub_dist'], 1) ?> km
                    </td>

                    <!-- Amenitas Sekitar -->
                    <td class="px-3 py-3.5 text-center font-semibold text-slate-700 hidden lg:table-cell">
                        <?= $item['amenities'] ?> Unit
                    </td>

                    <!-- Skor Preferensi V -->
                    <td class="px-2 sm:px-4 py-2 sm:py-3.5 text-center">
                        <span class="px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-black font-mono <?= $rank === 1 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-ocean-50 text-ocean-700 border border-ocean-200' ?>">
                            <?= number_format($item['score_v'], 4) ?>
                        </span>
                    </td>

                    <!-- Aksi Peta & QR -->
                    <td class="px-2.5 sm:px-4 py-2 sm:py-3.5 text-right">
                        <div class="inline-flex items-center gap-1 justify-end">
                            <button onclick="openQrModal('<?= htmlspecialchars(addslashes($item['nama'])) ?>', <?= (float)($item['latitude'] ?? -2.1215) ?>, <?= (float)($item['longitude'] ?? 106.1130) ?>, '<?= htmlspecialchars(addslashes($item['kabupaten'])) ?>', '<?= htmlspecialchars(addslashes($item['sub_kategori'])) ?>')" class="p-1.5 px-2 rounded-lg sm:rounded-xl bg-ocean-50 hover:bg-ocean-100 text-ocean-700 font-bold text-xs transition-all shadow-2xs" title="Kode QR Navigasi Pintar">
                                <i class="fa-solid fa-qrcode text-[11px] sm:text-[12px]"></i>
                            </button>
                            <a href="index.php?page=peta&search=<?= urlencode($item['nama']) ?>" class="inline-flex items-center gap-1 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl bg-slate-100 hover:bg-ocean-600 text-slate-700 hover:text-white font-bold text-[11px] sm:text-xs transition-all shadow-2xs">
                                <i class="fa-solid fa-location-dot text-[10px] sm:text-[11px]"></i>
                                <span class="hidden sm:inline">Peta</span>
                            </a>
                        </div>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
