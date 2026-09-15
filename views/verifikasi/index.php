<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/verifikasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Pengendali Utama Panel Moderasi & Verifikasi Data Dinas)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Pengontrol utama (Master View Controller) untuk panel kurasi dan verifikasi data
 * Dinas Pariwisata. Memproses persetujuan usulan (UPDATE status_verifikasi = 'Resmi')
 * atau penolakan usulan (DELETE dari tabel), mengambil daftar tempat yang masih
 * berstatus 'Usulan Baru', serta merender antarmuka antrean moderasi.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (Master Router) saat ?page=verifikasi
 * - Menghubungkan ke: app/database.php (MySQL db_pariwisata_babel)
 * - Memuat: tabel_antrean.php dan petunjuk_moderasi.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menyediakan dashboard kontrol kualitas data (Data Quality Control) bagi dinas
 * pengampu kebijakan kepariwisataan daerah.
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/database.php';
$pdo = getDbConnection();

$notifMsg = '';
$notifType = '';

// Proses Aksi Verifikasi / Tolak (POST Request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        if ($action === 'approve') {
            $stmt = $pdo->prepare("UPDATE tempat_wisata SET status_verifikasi = 'Resmi' WHERE id = ?");
            $stmt->execute([$id]);
            $notifMsg = "Destinasi berhasil diverifikasi dan sekarang resmi tampil di Peta Spasial dan Analisis!";
            $notifType = 'success';
        } elseif ($action === 'reject') {
            $stmt = $pdo->prepare("DELETE FROM tempat_wisata WHERE id = ?");
            $stmt->execute([$id]);
            $notifMsg = "Usulan tempat telah ditolak dan dihapus dari sistem.";
            $notifType = 'info';
        }
    }
}

// Ambil data yang masih berstatus 'Usulan Baru'
$pendingPlaces = $pdo->query("
    SELECT * FROM tempat_wisata 
    WHERE status_verifikasi = 'Usulan Baru'
    ORDER BY created_at DESC
")->fetchAll();

$totalPending = count($pendingPlaces);
?>

<!-- ============================================================== -->
<!-- STRUKTUR TATA LETAK UTAMA MODUL PANEL MODERASI DINAS          -->
<!-- ============================================================== -->
<main class="flex-grow w-full max-w-[1720px] mx-auto px-4 sm:px-6 lg:px-10 py-4 sm:py-8 pb-24 lg:pb-8 min-w-0 overflow-hidden space-y-6 sm:space-y-8">

    <!-- Header Section Panel Moderasi -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80 mb-2">
                <i class="fa-solid fa-shield-halved text-[11px]"></i>
                <span>Tata Kelola Data &amp; Moderasi Dinas Pariwisata</span>
            </div>
            <h1 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                Panel Verifikasi &amp; Moderasi Data Usulan
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                Tinjau dan kurasi usulan objek wisata atau amenitas baru yang didaftarkan oleh masyarakat/pelaku usaha sebelum dipublikasikan ke peta utama.
            </p>
        </div>

        <div class="px-4 py-2 rounded-2xl bg-purple-100 text-purple-800 text-xs font-black self-start sm:self-auto flex items-center gap-2 shadow-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-purple-600 animate-pulse"></span>
            <span><?= $totalPending ?> Usulan Menunggu</span>
        </div>
    </div>

    <!-- Alert Notifikasi Hasil Aksi -->
    <?php if (!empty($notifMsg)): ?>
    <div class="p-4 rounded-2xl text-xs font-semibold flex items-center gap-3 <?= $notifType === 'success' ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-slate-100 border border-slate-300 text-slate-800' ?>">
        <i class="fa-solid <?= $notifType === 'success' ? 'fa-circle-check text-emerald-600' : 'fa-circle-info text-slate-600' ?> text-base"></i>
        <div><?= htmlspecialchars($notifMsg) ?></div>
    </div>
    <?php endif; ?>

    <!-- 1. Tabel Antrean Usulan Moderasi -->
    <?php include __DIR__ . '/tabel_antrean.php'; ?>

    <!-- 2. Petunjuk SOP Moderasi Dinas -->
    <?php include __DIR__ . '/petunjuk_moderasi.php'; ?>

</main>
