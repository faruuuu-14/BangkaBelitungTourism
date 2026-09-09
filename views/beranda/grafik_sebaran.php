<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      grafik_sebaran.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Grafik Komparasi Sebaran Destinasi & Kebutuhan Amenitas)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen visualisasi statistik pada beranda. Menampilkan grafik batang
 * komparasi sebaran daya tarik wisata (Alam, Buatan, Budaya) terhadap fasilitas
 * pendukung (Hotel & Restoran) di 7 Kabupaten/Kota menggunakan pustaka Chart.js,
 * dilengkapi tabel rincian data per kabupaten.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php
 * - Menggunakan variabel: $perKabupaten dari query basis data
 * - Menggunakan pustaka klien: Chart.js
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan wawasan analitis mengenai ketimpangan sebaran fasilitas amenitas
 * antar wilayah untuk mendukung pengambilan keputusan investasi pariwisata.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 5. KOMPARASI SEBARAN DESTINASI & FASILITAS PER KABUPATEN/KOTA -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    
    <!-- Kolom Kiri: Visualisasi Grafik Batang Komparasi (2 Kolom) -->
    <div class="lg:col-span-2 bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
            <div>
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Sebaran Daya Tarik vs Amenitas per Kabupaten/Kota</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">7 Daerah</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Komparasi ketersediaan objek wisata terhadap fasilitas akomodasi dan restoran</p>
            </div>
            <a href="index.php?page=analisis" class="text-xs font-bold text-ocean-600 hover:text-ocean-700 flex items-center gap-1 self-start sm:self-auto transition-colors">
                <span>Detail Ekonometrika</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        
        <!-- Kontainer Kanvas Chart.js -->
        <div class="h-64 sm:h-72 w-full relative">
            <canvas id="berandaBarChart"></canvas>
        </div>
    </div>

    <!-- Kolom Kanan: Rangkuman Ringkas Sebaran Wilayah (1 Kolom) -->
    <div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-4 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">Rincian per Wilayah</h3>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Total Objek</span>
            </div>
            
            <div class="divide-y divide-slate-100 mt-2 space-y-0.5 max-h-64 overflow-y-auto pr-1">
                <?php foreach ($perKabupaten as $kab): ?>
                <div class="py-2.5 flex items-center justify-between text-xs hover:bg-slate-50/80 px-2 rounded-xl transition-colors">
                    <div class="min-w-0 pr-2">
                        <div class="font-bold text-slate-800 truncate"><?= htmlspecialchars($kab['kabupaten']) ?></div>
                        <div class="text-[11px] text-slate-400 mt-0.5">
                            <span class="text-sky-600 font-semibold"><?= $kab['alam'] ?> Alam</span> &bull; 
                            <span class="text-indigo-600 font-semibold"><?= $kab['hotel'] ?> Hotel</span> &bull; 
                            <span class="text-amber-600 font-semibold"><?= $kab['restoran'] ?> Resto</span>
                        </div>
                    </div>
                    <span class="font-black text-slate-900 px-2.5 py-1 rounded-lg bg-slate-100 text-xs shrink-0">
                        <?= $kab['total'] ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100">
            <a href="index.php?page=peta" class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-ocean-600 text-white font-bold text-xs flex items-center justify-center gap-2 transition-colors shadow-xs">
                <i class="fa-solid fa-map"></i>
                <span>Tinjau Peta Persebaran Geospasial</span>
            </a>
        </div>
    </div>

</div>

<!-- Inisialisasi Script Grafik Batang Beranda -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('berandaBarChart');
    if (!ctx) return;

    const dataKab = <?= json_encode($perKabupaten) ?>;
    const labels = dataKab.map(d => d.kabupaten.replace('Kabupaten ', 'Kab. '));
    const dtAlam = dataKab.map(d => parseInt(d.alam || 0));
    const dtHotel = dataKab.map(d => parseInt(d.hotel || 0));
    const dtResto = dataKab.map(d => parseInt(d.restoran || 0));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Wisata Alam',
                    data: dtAlam,
                    backgroundColor: '#0284c7',
                    borderRadius: 6
                },
                {
                    label: 'Akomodasi Hotel',
                    data: dtHotel,
                    backgroundColor: '#6366f1',
                    borderRadius: 6
                },
                {
                    label: 'Restoran/Kuliner',
                    data: dtResto,
                    backgroundColor: '#f59e0b',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                        color: '#475569'
                    }
                },
                tooltip: {
                    padding: 10,
                    cornerRadius: 8,
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#64748b' }
                }
            }
        }
    });
});
</script>
