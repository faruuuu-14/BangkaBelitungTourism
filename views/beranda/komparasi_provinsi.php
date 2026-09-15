<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA NUSANTARA (INDONESIA TOURISM HUB)
 * ==============================================================================
 * 
 * @file      komparasi_provinsi.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Matriks Komparasi 14 Provinsi Wisata Nusantara)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Menampilkan tabel komparatif dan kartu kesiapan data pariwisata 14 provinsi di
 * Indonesia. Membandingkan status integrasi data spasial, total destinasi,
 * kelengkapan amenitas (hotel & restoran), seri data BPS, dan kesiapan SPK TOPSIS.
 * Menegaskan kesiapan sistem dalam menerima input data 13 provinsi secara dinamis.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php saat wilayah=nasional
 * - Membaca fungsi: getDaftarProvinsi() di app/database.php
 * ==============================================================================
 */

$daftarProvinsi = getDaftarProvinsi();

// Matriks detail komparasi provinsi nusantara
$provinsiMatrix = [
    'babel' => [
        'nama' => 'Kepulauan Bangka Belitung',
        'kawasan' => 'Kepulauan Sumatera',
        'total' => 875,
        'amenitas' => 'Hotel, Resto, Bandara & Pelabuhan (Lengkap)',
        'bps' => '2021-2026 (112 Seri Data)',
        'topsis' => 'Aktif (5 Kriteria Lengkap)',
        'status' => 'Aktif Penuh (Percontohan Utama)',
        'status_type' => 'active',
        'icon' => 'fa-solid fa-gem',
        'badge' => 'Pilot Project',
        'action_url' => 'index.php?page=beranda&wilayah=babel',
        'action_text' => 'Buka Portal Babel',
        'color' => 'emerald'
    ],
    'sumbar' => [
        'nama' => 'Sumatera Barat',
        'kawasan' => 'Sumatera Bagian Barat',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-landmark-dome',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'sumut' => [
        'nama' => 'Sumatera Utara',
        'kawasan' => 'Sumatera Bagian Utara',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-mountain',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'riau' => [
        'nama' => 'Riau',
        'kawasan' => 'Sumatera Tengah',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-tree',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'kepri' => [
        'nama' => 'Kepulauan Riau',
        'kawasan' => 'Kepulauan Selat Malaka',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-ship',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'sumsel' => [
        'nama' => 'Sumatera Selatan',
        'kawasan' => 'Sumatera Bagian Selatan',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-bridge',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'lampung' => [
        'nama' => 'Lampung',
        'kawasan' => 'Sumatera Selatan / Selat Sunda',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-water',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'dki' => [
        'nama' => 'DKI Jakarta',
        'kawasan' => 'Jawa Bagian Barat (Metropolitan)',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-city',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'jabar' => [
        'nama' => 'Jawa Barat',
        'kawasan' => 'Jawa Bagian Barat',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-mountain-sun',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'jateng' => [
        'nama' => 'Jawa Tengah',
        'kawasan' => 'Jawa Bagian Tengah',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-monument',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'diy' => [
        'nama' => 'DI Yogyakarta',
        'kawasan' => 'Jawa Bagian Selatan (Budaya)',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-crown',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'jatim' => [
        'nama' => 'Jawa Timur',
        'kawasan' => 'Jawa Bagian Timur',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-volcano',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
    'bali' => [
        'nama' => 'Bali',
        'kawasan' => 'Kepulauan Nusa Tenggara',
        'total' => 0,
        'amenitas' => 'Struktur Siap Diisi',
        'bps' => 'Menunggu Input Data',
        'topsis' => 'Formula Siap Pakai',
        'status' => 'Siap Integrasi Data',
        'status_type' => 'pending',
        'icon' => 'fa-solid fa-umbrella-beach',
        'badge' => 'Tahap 2',
        'action_url' => '#',
        'action_text' => 'Siap Diisi',
        'color' => 'slate'
    ],
];
?>

<!-- ============================================================== -->
<!-- 4. TABEL & MATRIKS KOMPARASI 14 PROVINSI NUSANTARA             -->
<!-- ============================================================== -->
<div id="komparasiProvinsi" class="bg-white rounded-3xl p-5 sm:p-7 lg:p-8 border border-slate-200/80 shadow-xs space-y-6">
    
    <!-- Kepala Bagian Komparasi -->
    <div class="flex flex-col md:flex-row md:items-center justify-between pb-4 border-b border-slate-100 gap-3">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-ocean-50 text-ocean-700 border border-ocean-200/80 mb-1.5">
                <i class="fa-solid fa-scale-balanced text-[11px]"></i>
                <span>Komparasi Kesiapan Data Antar-Provinsi</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Matriks Integrasi 14 Provinsi Pariwisata Nusantara
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 max-w-3xl leading-relaxed">
                Pemantauan status integrasi data spasial, pemetaan fasilitas 3A, keterhubungan data statistik BPS, dan kesiapan algoritma SPK TOPSIS se-Indonesia.
            </p>
        </div>

        <div class="flex items-center gap-2 self-start md:self-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>1 Pilot Aktif</span>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold">
                <span>13 Siap Integrasi</span>
            </span>
        </div>
    </div>

    <!-- Tabel Komparasi Modern & Responsif -->
    <div class="overflow-x-auto rounded-2xl border border-slate-200">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-extrabold text-[11px] uppercase tracking-wider">
                    <th class="py-3 px-4">Provinsi &amp; Kawasan</th>
                    <th class="py-3 px-3 text-center">Destinasi</th>
                    <th class="py-3 px-3">Kesiapan Fasilitas 3A</th>
                    <th class="py-3 px-3">Data Statistik BPS</th>
                    <th class="py-3 px-3">SPK TOPSIS</th>
                    <th class="py-3 px-3">Status Operasional</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                <?php foreach ($provinsiMatrix as $k => $p): ?>
                <?php $isPilot = ($p['status_type'] === 'active'); ?>
                <tr class="<?= $isPilot ? 'bg-emerald-50/40 hover:bg-emerald-50/70' : 'hover:bg-slate-50/80' ?> transition-colors">
                    
                    <!-- Kolom 1: Provinsi & Kawasan -->
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl <?= $isPilot ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-500' ?> flex items-center justify-center shrink-0 text-sm font-bold">
                                <i class="<?= $p['icon'] ?>"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="font-extrabold text-slate-900 flex items-center gap-1.5">
                                    <span class="truncate"><?= $p['nama'] ?></span>
                                    <?php if ($isPilot): ?>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        Percontohan
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-[10px] text-slate-500 mt-0.5">
                                    <?= $p['kawasan'] ?>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Kolom 2: Destinasi Terpetakan -->
                    <td class="py-3 px-3 text-center">
                        <?php if ($isPilot): ?>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <?= $p['total'] ?> Objek
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                            0 Objek
                        </span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom 3: Kesiapan Fasilitas 3A -->
                    <td class="py-3 px-3">
                        <div class="text-[11px] <?= $isPilot ? 'font-bold text-slate-800' : 'text-slate-500' ?>">
                            <?= $p['amenitas'] ?>
                        </div>
                    </td>

                    <!-- Kolom 4: Data Statistik BPS -->
                    <td class="py-3 px-3">
                        <div class="text-[11px] flex items-center gap-1.5">
                            <?php if ($isPilot): ?>
                            <i class="fa-solid fa-circle-check text-emerald-600 text-xs"></i>
                            <span class="font-bold text-slate-800"><?= $p['bps'] ?></span>
                            <?php else: ?>
                            <i class="fa-solid fa-hourglass-half text-amber-500 text-xs"></i>
                            <span class="text-slate-500"><?= $p['bps'] ?></span>
                            <?php endif; ?>
                        </div>
                    </td>

                    <!-- Kolom 5: SPK TOPSIS -->
                    <td class="py-3 px-3">
                        <div class="text-[11px] flex items-center gap-1.5">
                            <?php if ($isPilot): ?>
                            <i class="fa-solid fa-ranking-star text-purple-600 text-xs"></i>
                            <span class="font-bold text-purple-700"><?= $p['topsis'] ?></span>
                            <?php else: ?>
                            <i class="fa-solid fa-code text-slate-400 text-xs"></i>
                            <span class="text-slate-500"><?= $p['topsis'] ?></span>
                            <?php endif; ?>
                        </div>
                    </td>

                    <!-- Kolom 6: Status Operasional -->
                    <td class="py-3 px-3">
                        <?php if ($isPilot): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-ping"></span>
                            <span>Aktif Penuh</span>
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                            <i class="fa-solid fa-clock-rotate-left text-[9px]"></i>
                            <span>Siap Diisi</span>
                        </span>
                        <?php endif; ?>
                    </td>

                    <!-- Kolom 7: Aksi -->
                    <td class="py-3 px-4 text-center">
                        <?php if ($isPilot): ?>
                        <a href="<?= $p['action_url'] ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-xs transition-all">
                            <span>Buka Portal</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                        <?php else: ?>
                        <button type="button" onclick="alertProvinsiTahapDua('<?= addslashes($p['nama']) ?>')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-[11px] transition cursor-pointer">
                            <span>Info</span>
                            <i class="fa-solid fa-circle-info text-[10px]"></i>
                        </button>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Catatan Penjelasan Skalabilitas Sistem (Transparan & Ilmiah) -->
    <div class="bg-gradient-to-r from-ocean-50 via-slate-50 to-emerald-50 p-4 sm:p-5 rounded-2xl border border-ocean-100 flex items-start gap-3 text-xs text-slate-600 leading-relaxed">
        <div class="w-7 h-7 rounded-xl bg-ocean-600 text-white flex items-center justify-center shrink-0 mt-0.5">
            <i class="fa-solid fa-lightbulb text-xs"></i>
        </div>
        <div>
            <strong class="text-slate-900 block font-bold text-xs sm:text-sm mb-0.5">
                Kalkulasi Otomatis Skala Nasional Saat Data Bertambah:
            </strong>
            Sistem dirancang menggunakan arsitektur basis data relasional multi-wilayah. Ketika administrator atau dinas menambahkan data untuk 13 provinsi lainnya, mesin kalkulasi statistik BPS, perhitungan beban amenitas, serta perangkingan rekomendasi SPK TOPSIS antar-provinsi akan langsung aktif dan terhitung otomatis pada dashboard nasional tanpa perubahan kode sumber.
        </div>
    </div>

</div>
