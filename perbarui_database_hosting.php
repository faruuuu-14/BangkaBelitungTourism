<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      perbarui_database_hosting.php
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Alat Bantu Sinkronisasi Basis Data Otomatis)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Berkas skrip utilitas web mandiri untuk memeriksa status dan mengeksekusi
 * pembaruan (migrasi) skema tabel serta pemuatan 875 data objek wisata dan
 * 112 data kunjungan BPS secara otomatis langsung di server hosting InfinityFree
 * maupun localhost tanpa perlu masuk ke phpMyAdmin secara manual.
 * ==============================================================================
 */

require_once __DIR__ . '/app/database.php';

$pdo = getDbConnection();
$message = '';
$status = 'info';

// Cek status tabel saat ini
function checkDbStatus($pdo) {
    $res = [
        'tempat_wisata' => null,
        'kunjungan_bps' => null
    ];
    try {
        $res['tempat_wisata'] = (int)$pdo->query("SELECT COUNT(*) FROM tempat_wisata")->fetchColumn();
    } catch (Exception $e) {
        $res['tempat_wisata'] = -1;
    }
    try {
        $res['kunjungan_bps'] = (int)$pdo->query("SELECT COUNT(*) FROM kunjungan_bps")->fetchColumn();
    } catch (Exception $e) {
        $res['kunjungan_bps'] = -1;
    }
    return $res;
}

// Proses jika tombol sinkronisasi diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sync') {
    $sqlFile = __DIR__ . '/data/import_infinityfree.sql';
    if (!file_exists($sqlFile)) {
        $message = "Berkas SQL <code>data/import_infinityfree.sql</code> tidak ditemukan di server!";
        $status = 'error';
    } else {
        try {
            $sql = file_get_contents($sqlFile);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
            $pdo->exec($sql);
            
            $message = "<strong>Berhasil!</strong> Skema tabel baru dan seluruh 875 data tempat wisata serta 112 data kunjungan BPS resmi telah sukses dipasang ke database hosting!";
            $status = 'success';
        } catch (Exception $e) {
            $message = "Terjadi kesalahan saat mengeksekusi SQL: " . htmlspecialchars($e->getMessage());
            $status = 'error';
        }
    }
}

$dbStatus = checkDbStatus($pdo);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Basis Data Otomatis &bull; Babel Tourism Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; color: #f8fafc; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="max-w-xl w-full bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
        
        <!-- Header Profil & Logo -->
        <div class="flex items-center gap-3.5 pb-6 border-b border-slate-800">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-sky-600 to-emerald-500 flex items-center justify-center text-white text-xl shadow-lg shadow-sky-500/20">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <h1 class="text-lg sm:text-xl font-black text-white">Sinkronisasi Basis Data Hosting</h1>
                <p class="text-xs text-slate-400">Pengembang: <strong class="text-sky-400">Fathurrahman Safari Lubis</strong> (NIM: 2411500025)</p>
            </div>
        </div>

        <!-- Notifikasi Pesan -->
        <?php if (!empty($message)): ?>
            <div class="mt-6 p-4 rounded-2xl text-xs sm:text-sm <?= $status === 'success' ? 'bg-emerald-500/20 border border-emerald-500/40 text-emerald-300' : 'bg-rose-500/20 border border-rose-500/40 text-rose-300' ?>">
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid <?= $status === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-triangle-exclamation text-rose-400' ?> text-base mt-0.5"></i>
                    <div><?= $message ?></div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Info Status Basis Data -->
        <div class="mt-6 space-y-3">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Lingkungan &amp; Koneksi Aktif:</div>
            
            <div class="grid grid-cols-2 gap-2.5 text-xs">
                <div class="bg-slate-800/60 p-3 rounded-xl border border-slate-700/50">
                    <div class="text-slate-400">Lingkungan:</div>
                    <div class="font-extrabold text-white mt-0.5 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full <?= $isLocalhost ? 'bg-amber-400' : 'bg-emerald-400 animate-pulse' ?>"></span>
                        <?= $isLocalhost ? 'Localhost (Laragon)' : 'Server Online (InfinityFree)' ?>
                    </div>
                </div>
                <div class="bg-slate-800/60 p-3 rounded-xl border border-slate-700/50">
                    <div class="text-slate-400">Nama Database:</div>
                    <div class="font-mono font-bold text-sky-300 mt-0.5 truncate"><?= htmlspecialchars($DB_NAME) ?></div>
                </div>
            </div>

            <!-- Kartu Status 2 Tabel Utama -->
            <div class="bg-slate-800/40 p-4 rounded-2xl border border-slate-800 space-y-3">
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-sky-400"></i>
                        <span class="font-bold">Tabel <code>tempat_wisata</code></span>
                    </div>
                    <div>
                        <?php if ($dbStatus['tempat_wisata'] >= 0): ?>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                Terpasang (<?= $dbStatus['tempat_wisata'] ?> data)
                            </span>
                        <?php else: ?>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                Belum Terpasang
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs sm:text-sm pt-2 border-t border-slate-700/40">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-chart-simple text-amber-400"></i>
                        <span class="font-bold">Tabel <code>kunjungan_bps</code></span>
                    </div>
                    <div>
                        <?php if ($dbStatus['kunjungan_bps'] >= 0): ?>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                Terpasang (<?= $dbStatus['kunjungan_bps'] ?> data)
                            </span>
                        <?php else: ?>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                Belum Terpasang
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-8 space-y-3">
            <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyinkronkan dan memperbarui seluruh tabel database hosting sekarang?');">
                <input type="hidden" name="action" value="sync">
                <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-sky-600 to-emerald-600 hover:from-sky-500 hover:to-emerald-500 text-white font-extrabold text-sm shadow-xl shadow-sky-600/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Pasang / Perbarui Database Otomatis Sekarang</span>
                </button>
            </form>

            <div class="flex items-center justify-between gap-3 pt-2">
                <a href="index.php?page=beranda" class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold text-center border border-slate-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-house text-slate-400"></i>
                    <span>Buka Beranda Sistem</span>
                </a>
            </div>
        </div>

        <!-- Panduan Manual phpMyAdmin InfinityFree -->
        <div class="mt-6 p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 text-[11px] text-slate-400 space-y-1.5">
            <div class="font-bold text-slate-300 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-info text-sky-400"></i>
                <span>Cara Manual via phpMyAdmin InfinityFree (Opsi Alternatif):</span>
            </div>
            <ol class="list-decimal list-inside space-y-1 text-slate-400">
                <li>Buka cPanel InfinityFree &rarr; klik <strong>phpMyAdmin</strong> pada database <code>if0_42843499_tourism</code>.</li>
                <li>Klik tab <strong>Import</strong> di bagian atas.</li>
                <li>Pilih berkas khusus bebas error: <code>data/import_infinityfree.sql</code>.</li>
                <li>Klik tombol <strong>Go / Kirim</strong> di bagian bawah. Selesai!</li>
            </ol>
        </div>

    </div>
</body>
</html>
