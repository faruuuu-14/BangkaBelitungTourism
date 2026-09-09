<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      tabel_antrean.php
 * @folder    views/verifikasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Tabel Antrean Usulan Moderasi Tempat Masuk)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen tabel antrean moderasi data. Menampilkan seluruh data objek wisata
 * atau amenitas yang berstatus 'Usulan Baru'. Menyediakan tombol aksi langsung:
 * - 'Setujui' (Mengubah status menjadi 'Resmi' sehingga tampil di Peta & Analisis)
 * - 'Tolak' (Menghapus usulan dari basis data)
 * Dilengkapi tampilan keadaan kosong (Empty State) jika antrean usulan bersih.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/verifikasi/index.php
 * - Menggunakan array: $pendingPlaces
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan alur kerja moderasi data yang efisien dan aman bagi petugas verifikator.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- TABEL ANTREAN USULAN MODERASI DATA                             -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
    
    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-black text-slate-900">Daftar Antrean Usulan Masuk</h3>
            <p class="text-xs text-slate-500 mt-0.5">Tinjau kelayakan nama, wilayah, dan koordinat sebelum menyetujui</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
            <?= count($pendingPlaces) ?> Usulan Menunggu
        </span>
    </div>

    <?php if (empty($pendingPlaces)): ?>
    <!-- Tampilan Keadaan Kosong (Zero Pending) -->
    <div class="py-16 text-center space-y-3">
        <div class="w-14 h-14 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto text-xl shadow-xs">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <h4 class="font-extrabold text-slate-900 text-sm">Semua Usulan Selesai Dimoderasi!</h4>
        <p class="text-xs text-slate-400 max-w-sm mx-auto">
            Tidak ada usulan tempat baru yang menunggu verifikasi saat ini. Seluruh data di peta berstatus terverifikasi resmi.
        </p>
    </div>
    <?php else: ?>
    <!-- Tabel Data Usulan Menunggu -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3.5">Nama Usulan &amp; Kategori</th>
                    <th class="px-4 py-3.5">Wilayah Administratif</th>
                    <th class="px-4 py-3.5">Rating / Ulasan</th>
                    <th class="px-4 py-3.5">Koordinat GPS</th>
                    <th class="px-5 py-3.5 text-right">Keputusan Moderasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                <?php foreach ($pendingPlaces as $row): ?>
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-5 py-3.5">
                        <div class="font-extrabold text-slate-900 text-xs sm:text-sm">
                            <?= htmlspecialchars($row['nama']) ?>
                        </div>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-bold px-2 py-0.2 rounded-md bg-ocean-50 text-ocean-700 border border-ocean-200">
                                <?= htmlspecialchars($row['sub_kategori']) ?>
                            </span>
                            <span class="text-[10px] text-slate-400">&bull; <?= htmlspecialchars($row['kelompok']) ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="font-semibold text-slate-800"><?= htmlspecialchars($row['kabupaten']) ?></div>
                        <div class="text-[11px] text-slate-400">Kec. <?= htmlspecialchars($row['kecamatan']) ?></div>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="font-bold text-amber-600">★ <?= number_format($row['rating'], 1) ?></div>
                        <div class="text-[10px] text-slate-400"><?= number_format($row['ulasan']) ?> ulasan</div>
                    </td>
                    <td class="px-4 py-3.5 font-mono text-[11px] text-slate-600">
                        <?= round($row['latitude'], 4) ?>, <?= round($row['longitude'], 4) ?>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <form method="POST" action="index.php?page=verifikasi" class="inline-flex items-center gap-1.5" onsubmit="return confirm('Apakah Anda yakin ingin memproses usulan ini?');">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="approve" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> Setujui
                            </button>
                            <button type="submit" name="action" value="reject" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</div>
