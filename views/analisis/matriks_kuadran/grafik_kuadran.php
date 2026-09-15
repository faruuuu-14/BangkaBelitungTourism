<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      grafik_kuadran.php
 * @folder    views/analisis/matriks_kuadran/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Matriks 4 Kuadran Tipologi: Rasio Stayers vs Lama Menginap RLMT)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen matriks tipologi pariwisata daerah adaptasi BCG / IPA Matrix.
 * Memetakan 7 Kabupaten/Kota secara data-driven berdasarkan data BPS April 2026:
 * - Sumbu X: Rasio Tamu Menginap / Stayers (%) (Rentang 0% - 70%)
 * - Sumbu Y: Rata-Rata Lama Menginap / RLMT (Malam) (Rentang 0.8 - 2.4 Malam)
 * 
 * 4 Kuadran Tipologi BPS:
 * - Kuadran I (Kanan Atas): Destination Stayers (Belitung)
 * - Kuadran II (Kiri Atas): Corridor Stayers (Bangka Tengah)
 * - Kuadran III (Kiri Bawah): High-Volume Day-Trippers & Buffers (Bangka, Basel, Babar, Beltim)
 * - Kuadran IV (Kanan Bawah): Urban Hub & Transit Center (Kota Pangkal Pinang)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 4 (Matriks Kuadran)
 * - Pustaka: Chart.js Scatter Plot
 * ==============================================================================
 */

$kabData = getBpsKabupatenDataApril2026();
$kuadranPoints = [];

foreach ($kabData as $k => $item) {
    $stayersPercent = round(($item['tamu_menginap'] / $item['wisnus_tujuan']) * 100, 2);
    $kuadranPoints[] = [
        'x' => $stayersPercent,
        'y' => (float)$item['rlmt'],
        'label' => $item['kabupaten'],
        'karakter' => $item['karakter'],
        'badge_color' => $item['badge_color']
    ];
}
?>

<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200 mb-1">
                <i class="fa-solid fa-table-cells-large text-[11px]"></i>
                <span>Matriks Tipologi Kebijakan BPS 2026</span>
            </div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                Matriks 4 Kuadran Tipologi: Rasio Stayers vs Lama Menginap (RLMT)
            </h3>
            <p class="text-xs text-slate-500">
                Memetakan posisi empiris 7 Kabupaten/Kota berdasarkan proporsi tamu menginap terhadap durasi tinggal
            </p>
        </div>
        <span class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-full self-start sm:self-auto">
            Ambang Batas: Stayers 18% | RLMT 1.40 Malam
        </span>
    </div>

    <!-- Kanvas Grafik Matriks Kuadran -->
    <div class="h-72 sm:h-96 w-full relative">
        <canvas id="kuadranScatterChart"></canvas>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxK = document.getElementById('kuadranScatterChart');
    if (!ctxK) return;

    const kPoints = <?= json_encode($kuadranPoints) ?>;

    new Chart(ctxK, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Posisi Tipologi Daerah',
                data: kPoints,
                backgroundColor: '#7c3aed',
                borderColor: '#ffffff',
                borderWidth: 2,
                pointRadius: 10,
                pointHoverRadius: 14,
                pointBackgroundColor: '#8b5cf6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const p = context.raw;
                            return `${p.label}: Stayers ${p.x}%, RLMT ${p.y} Malam (${p.karakter})`;
                        }
                    },
                    padding: 10,
                    cornerRadius: 8,
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 }
                }
            },
            scales: {
                x: {
                    min: 0,
                    max: 70,
                    title: {
                        display: true,
                        text: 'Proporsi Tamu Menginap / Stayers (%)',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                        color: '#334155'
                    },
                    grid: {
                        color: context => context.tick.value === 18 ? '#cbd5e1' : '#f1f5f9',
                        lineWidth: context => context.tick.value === 18 ? 2 : 1
                    },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 10 },
                        color: '#64748b',
                        callback: val => val + '%'
                    }
                },
                y: {
                    min: 0.8,
                    max: 2.3,
                    title: {
                        display: true,
                        text: 'Rata-Rata Lama Menginap / RLMT (Malam)',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                        color: '#334155'
                    },
                    grid: {
                        color: context => context.tick.value === 1.4 ? '#cbd5e1' : '#f1f5f9',
                        lineWidth: context => context.tick.value === 1.4 ? 2 : 1
                    },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 10 },
                        color: '#64748b',
                        callback: val => val.toFixed(1) + ' hr'
                    }
                }
            }
        }
    });
});
</script>
