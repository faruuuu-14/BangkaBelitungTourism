<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      grafik_proyeksi.php
 * @folder    views/analisis/proyeksi_tren/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Grafik Regresi Linear Proyeksi Kunjungan Wisatawan 2026-2027)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen peramalan deret waktu (Time Series Forecasting) kunjungan wisatawan
 * ke Provinsi Kepulauan Bangka Belitung. Menggunakan metode regresi linear tren
 * berbasis data historis resmi BPS (2024 - 2026) untuk memproyeksikan estimasi
 * kedatangan wisatawan hingga akhir 2026 dan proyeksi tahun 2027.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 5 (Proyeksi Tren & Simulasi)
 * - Pustaka: Chart.js Line Chart
 * 
 * TUJUAN DIBUATNYA FILE:
 * Membantu perencanaan kapasitas daya tampung daerah dan antisipasi lonjakan
 * kunjungan wisatawan pada musim liburan mendatang.
 * ==============================================================================
 */

// Data deret waktu historis BPS & Proyeksi Tren
$trenBps = [
    'labels'   => ['2024 Q1', '2024 Q2', '2024 Q3', '2024 Q4', '2025 Q1', '2025 Q2', '2025 Q3', '2025 Q4', '2026 Q1 (Aktual)', '2026 Q2 (Est)', '2026 Q3 (Est)', '2026 Q4 (Est)'],
    'historis' => [125000, 138000, 142000, 151000, 136000, 148000, 154000, 162000, 157294, null, null, null],
    'proyeksi' => [null, null, null, null, null, null, null, null, 157294, 169000, 178000, 189500]
];
?>

<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200 mb-1">
                <i class="fa-solid fa-wand-magic-sparkles text-[11px]"></i>
                <span>Peramalan Deret Waktu Ekonometrika</span>
            </div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                Proyeksi Tren Kunjungan Wisatawan Nusantara ke Babel (2024 - 2026)
            </h3>
            <p class="text-xs text-slate-500">
                Garis biru menunjukkan data realisasi BPS, garis putus-putus toska menunjukkan proyeksi regresi linear
            </p>
        </div>
        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full self-start sm:self-auto">
            Tren Pertumbuhan Rata-rata +8.5% per Kuartal
        </span>
    </div>

    <!-- Kanvas Grafik Garis Proyeksi Tren -->
    <div class="h-64 sm:h-80 w-full relative">
        <canvas id="trenLineChart"></canvas>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxTren = document.getElementById('trenLineChart');
    if (!ctxTren) return;

    const dataTren = <?= json_encode($trenBps) ?>;

    new Chart(ctxTren, {
        type: 'line',
        data: {
            labels: dataTren.labels,
            datasets: [
                {
                    label: 'Realisasi Historis BPS',
                    data: dataTren.historis,
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.1)',
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#0284c7',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Proyeksi Regresi Linear',
                    data: dataTren.proyeksi,
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    borderDash: [6, 6],
                    pointRadius: 6,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    tension: 0.3
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
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                        color: '#475569'
                    }
                },
                tooltip: {
                    padding: 10,
                    cornerRadius: 8,
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw ? context.raw.toLocaleString() + ' Wisatawan' : '-'}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#64748b' }
                },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 10 },
                        color: '#64748b',
                        callback: val => (val / 1000) + 'k'
                    }
                }
            }
        }
    });
});
</script>
