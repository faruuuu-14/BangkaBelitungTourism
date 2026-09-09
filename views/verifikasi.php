<?php
require_once __DIR__ . '/../app/database.php';
$pdo = getDbConnection();

$notifMsg = '';
$notifType = '';

// Proses Aksi Verifikasi / Tolak
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        if ($action === 'approve') {
            $stmt = $pdo->prepare("UPDATE tempat_wisata SET status_verifikasi = 'Resmi' WHERE id = ?");
            $stmt->execute([$id]);
            $notifMsg = "Tempat berhasil diverifikasi dan sekarang resmi tampil di Peta serta Analisis!";
            $notifType = 'success';
        } elseif ($action === 'reject') {
            $stmt = $pdo->prepare("DELETE FROM tempat_wisata WHERE id = ?");
            $stmt->execute([$id]);
            $notifMsg = "Usulan tempat telah ditolak dan dihapus dari sistem.";
            $notifType = 'info';
        }
    }
}

// Ambil data yang masih 'Usulan Baru'
$pendingPlaces = $pdo->query("
    SELECT * FROM tempat_wisata 
    WHERE status_verifikasi = 'Usulan Baru'
    ORDER BY created_at DESC
")->fetchAll();

$totalPending = count($pendingPlaces);
?>

<main class="flex-grow w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 min-w-0 overflow-hidden">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/80 mb-2">
                <i class="fa-solid fa-shield-halved text-[11px]"></i>
                <span>Tata Kelola Data & Moderasi Dinas Pariwisata</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Panel Verifikasi Usulan Tempat Baru
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                Tinjau dan kurasi usulan objek wisata atau amenitas baru yang didaftarkan oleh masyarakat/pelaku usaha sebelum dipublikasikan ke peta utama.
            </p>
        </div>

        <span class="px-4 py-2 rounded-2xl bg-purple-100 text-purple-800 text-xs font-bold self-start sm:self-auto flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-purple-600 animate-pulse"></span>
            <span><?= $totalPending ?> Usulan Menunggu</span>
        </span>
    </div>

    <!-- Alert Notifikasi -->
    <?php if ($notifMsg): ?>
    <div class="mb-6 p-4 rounded-2xl <?= $notifType === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-slate-100 border-slate-200 text-slate-800' ?> border text-sm flex items-center gap-3">
        <i class="fa-solid <?= $notifType === 'success' ? 'fa-circle-check text-emerald-600' : 'fa-circle-info text-slate-600' ?> text-lg"></i>
        <span><?= htmlspecialchars($notifMsg) ?></span>
    </div>
    <?php endif; ?>

    <!-- Konten Utama: Daftar Usulan -->
    <?php if ($totalPending === 0): ?>
    
    <!-- Empty State -->
    <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-lg mx-auto my-12">
        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner">
            <i class="fa-solid fa-check-double"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-1">Semua Data Telah Terverifikasi!</h3>
        <p class="text-xs text-slate-500 mb-6">
            Tidak ada usulan baru yang tertunda. Semua objek wisata dan amenitas yang ada di database telah berstatus resmi.
        </p>
        <a href="index.php?page=tambah" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white text-xs font-bold transition-all shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Uji Coba Input Usulan Baru</span>
        </a>
    </div>

    <?php else: ?>

    <!-- Tabel Moderasi -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-400 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Nama Tempat & Kategori</th>
                        <th class="px-6 py-4">Wilayah</th>
                        <th class="px-6 py-4 text-center">Koordinat GPS</th>
                        <th class="px-6 py-4 text-center">Waktu Pengajuan</th>
                        <th class="px-6 py-4 text-right">Tindakan Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php foreach ($pendingPlaces as $row): ?>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="<?= htmlspecialchars(getDestinationPhoto($row['nama'], $row['sub_kategori'])) ?>" 
                                     alt="<?= htmlspecialchars($row['nama']) ?>"
                                     class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-2xs shrink-0">
                                <div>
                                    <div class="font-bold text-slate-900 text-sm leading-snug"><?= htmlspecialchars($row['nama']) ?></div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            <?= htmlspecialchars($row['sub_kategori']) ?> (<?= htmlspecialchars($row['kelompok']) ?>)
                                        </span>
                                        <span class="text-xs text-slate-400">★ <?= $row['rating'] ?: '0.0' ?> (<?= $row['ulasan'] ?> ulasan)</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <div class="font-bold text-slate-800"><?= htmlspecialchars($row['kecamatan']) ?></div>
                            <div class="text-slate-400"><?= htmlspecialchars($row['kabupaten']) ?></div>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-xs text-slate-500">
                            <?= $row['latitude'] ?>, <?= $row['longitude'] ?>
                            <a href="https://www.google.com/maps?q=<?= $row['latitude'] ?>,<?= $row['longitude'] ?>" target="_blank" class="block text-[10px] text-ocean-600 hover:underline mt-0.5">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i> Cek di Google Maps
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-slate-400">
                            <?= date('d M Y, H:i', strtotime($row['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                
                                <!-- Form Approve -->
                                <form method="POST" action="index.php?page=verifikasi" onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi dan menerbitkan tempat ini ke peta resmi?');">
                                    <input type="hidden" name="action" value="approve">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-colors flex items-center gap-1.5">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Setujui</span>
                                    </button>
                                </form>

                                <!-- Form Reject -->
                                <form method="POST" action="index.php?page=verifikasi" onsubmit="return confirm('Hapus usulan data ini?');">
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition-colors flex items-center gap-1.5">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span>Tolak</span>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php endif; ?>

</main>
