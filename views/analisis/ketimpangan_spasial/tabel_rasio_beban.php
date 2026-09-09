<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      tabel_rasio_beban.php
 * @folder    views/analisis/ketimpangan_spasial/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Tabel Rasio Beban Amenitas & Evaluasi Ketimpangan Spasial)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen analisis ketimpangan spasial (Spatial Gap Analysis) pada level kecamatan
 * dan kabupaten/kota. Mengevaluasi rasio beban amenitas (Daya Tarik : Hotel : Restoran)
 * untuk mendeteksi wilayah mana yang mengalami Defisit Akut (jumlah atraksi tinggi
 * namun minim penginapan/restoran), wilayah Potensial, atau wilayah Seimbang (Ideal).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 3 (Ketimpangan Spasial & Beban)
 * - Menggunakan data: $gapData dari app/statistics.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan panduan nyata berbasis lokasi bagi Dinas Pariwisata dan penanam modal
 * mengenai titik lokasi yang paling mendesak untuk dibangun hotel atau rumah makan.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- TABEL EVALUASI KETIMPANGAN SPASIAL & STATUS DEFISIT AMENITAS   -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden space-y-4 p-5 sm:p-7">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-1">
                <i class="fa-solid fa-scale-unbalanced text-[11px]"></i>
                <span>Evaluasi Keseimbangan Wilayah</span>
            </div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                Tabel Rasio Kebutuhan Fasilitas per Kecamatan di Bangka Belitung
            </h3>
            <p class="text-xs text-slate-500">
                Deteksi otomatis wilayah surplus dan defisit amenitas berdasarkan perbandingan jumlah daya tarik terhadap fasilitas pendukung
            </p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                Merah: Butuh Tambahan Hotel/Resto Segera
            </span>
        </div>
    </div>

    <!-- Tabel Responsif Daftar Kecamatan & Rasio Beban -->
    <div class="overflow-x-auto max-h-96 overflow-y-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 sticky top-0 z-10 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3">Kecamatan &amp; Kabupaten</th>
                    <th class="px-3 py-3 text-center">Daya Tarik</th>
                    <th class="px-3 py-3 text-center">Hotel</th>
                    <th class="px-3 py-3 text-center">Restoran</th>
                    <th class="px-3 py-3 text-center">Rasio Amenitas</th>
                    <th class="px-4 py-3 text-right">Status Evaluasi Spasial</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                <?php 
                $counter = 0;
                foreach ($gapData as $row): 
                    $counter++;
                    $status = $row['status'] ?? 'Seimbang';
                    $isDefisit = str_contains($status, 'Defisit') || str_contains($status, 'Kritis');
                    $isWaspada = str_contains($status, 'Potensi') || str_contains($status, 'Longgar') || str_contains($status, 'Perlu');
                ?>
                <tr class="hover:bg-slate-50/80 transition-colors <?= $isDefisit ? 'bg-rose-50/20' : '' ?>">
                    <td class="px-4 py-3">
                        <div class="font-extrabold text-slate-900 text-xs sm:text-sm">
                            Kec. <?= htmlspecialchars($row['kecamatan']) ?>
                        </div>
                        <div class="text-[11px] text-slate-400">
                            <?= htmlspecialchars($row['kabupaten']) ?>
                        </div>
                    </td>
                    <td class="px-3 py-3 text-center font-bold text-slate-800">
                        <?= $row['total_atraksi'] ?? 0 ?>
                    </td>
                    <td class="px-3 py-3 text-center font-bold text-indigo-600">
                        <?= $row['total_hotel'] ?? 0 ?>
                    </td>
                    <td class="px-3 py-3 text-center font-bold text-amber-600">
                        <?= $row['total_resto'] ?? 0 ?>
                    </td>
                    <td class="px-3 py-3 text-center font-mono font-bold text-slate-700">
                        <?= $row['rasio'] ?? '1 : 0.2' ?>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-block <?= $isDefisit ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($isWaspada ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') ?>">
                            <?= htmlspecialchars($status) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
