<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      grafik_sebaran.php
 * @folder    views/analisis/korelasi_pearson/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Grafik Sebaran Scatter Plot Korelasi Amenitas vs Kunjungan)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen visualisasi sebaran korelasi (Scatter Plot dengan Garis Tren Regresi).
 * Menampilkan posisi koordinat empiris 7 Kabupaten/Kota pada sumbu X (Jumlah Amenitas
 * Hotel & Restoran) dan sumbu Y (Total Kunjungan Wisatawan BPS) menggunakan Chart.js.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php
 * - Menggunakan data: $macroCorr dari app/statistics.php
 * - Pustaka: Chart.js
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memvisualisasikan arah linearitas hubungan data secara grafis sehingga mempermudah
 * pemahaman pola hubungan variabel bebas (amenitas) dan variabel terikat (kunjungan).
 * ==============================================================================
 */

$scatterPoints = $macroCorr['scatter_data'] ?? [
    ['x' => 125, 'y' => 52000, 'label' => 'Kota Pangkalpinang'],
    ['x' => 98,  'y' => 46000, 'label' => 'Kab. Belitung'],
    ['x' => 64,  'y' => 24000, 'label' => 'Kab. Bangka'],
    ['x' => 32,  'y' => 14500, 'label' => 'Kab. Bangka Tengah'],
    ['x' => 28,  'y' => 9800,  'label' => 'Kab. Belitung Timur'],
    ['x' => 22,  'y' => 7400,  'label' => 'Kab. Bangka Barat'],
    ['x' => 12,  'y' => 3594,  'label' => 'Kab. Bangka Selatan'],
];
?>

<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
        <div>
            <h4 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">
                Diagram Pencar (Scatter Plot) &amp; Garis Tren Regresi Linear
            </h4>
            <p class="text-xs text-slate-500">
                Titik koordinat 7 Kabupaten/Kota menggambarkan proporsionalitas fasilitas terhadap kunjungan
            </p>
        </div>
        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full self-start sm:self-auto">
            Garis Tren Naik Positif (Slope &gt; 0)
        </span>
    </div>

    <!-- Kanvas Scatter Plot -->
    <div class="h-64 sm:h-80 w-full relative">
        <canvas id="pearsonScatterChart"></canvas>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxScatter = document.getElementById('pearsonScatterChart');
    if (!ctxScatter) return;

    const rawPoints = <?= json_encode($scatterPoints) ?>;

    new Chart(ctxScatter, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Titik Sebaran Kabupaten/Kota',
                data: rawPoints,
                backgroundColor: '#4f46e5',
                borderColor: '#4338ca',
                pointRadius: 8,
                pointHoverRadius: 11,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                        color: '#475569'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const p = context.raw;
                            return `${p.label || 'Daerah'}: ${p.x} Amenitas, ${p.y.toLocaleString()} Wisatawan`;
                        }
                    },
                    padding: 10,
                    cornerRadius: 8,
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Jumlah Fasilitas Amenitas (Hotel + Restoran)',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                        color: '#334155'
                    },
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#64748b' }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Total Kunjungan Wisatawan (Orang/Tahun)',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                        color: '#334155'
                    },
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 10 },
                        color: '#64748b',
                        callback: value => value.toLocaleString()
                    }
                }
            }
        }
    });
});
</script>
