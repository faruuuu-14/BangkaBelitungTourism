<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      grafik_kuadran.php
 * @folder    views/analisis/matriks_kuadran/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Grafik Matriks 4 Kuadran Prioritas Investasi Pariwisata)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen matriks kuadran strategis (Boston Consulting Group / IPA Matrix Adaptation).
 * Memetakan 7 Kabupaten/Kota ke dalam 4 kuadran analitis:
 * - Kuadran I (Bintang / Wilayah Unggulan): Kunjungan Tinggi & Amenitas Tinggi
 * - Kuadran II (Peluang Emas Investasi): Kunjungan Tinggi namun Amenitas Rendah
 * - Kuadran III (Potensial Berkembang): Kunjungan Rendah & Amenitas Rendah
 * - Kuadran IV (Kelebihan Kapasitas Amenitas): Kunjungan Rendah namun Amenitas Tinggi
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 4 (Matriks Kuadran)
 * - Pustaka: Chart.js Scatter Plot
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menentukan arah alokasi anggaran dan sasaran investor secara tepat sasaran
 * (*evidence-based policy making*).
 * ==============================================================================
 */

// Titik kuadran 7 Kabupaten/Kota (Sumbu X: Indeks Amenitas, Sumbu Y: Indeks Kunjungan BPS)
$kuadranPoints = [
    ['x' => 85, 'y' => 88, 'label' => 'Kota Pangkalpinang (Kuadran I: Unggulan)'],
    ['x' => 80, 'y' => 82, 'label' => 'Kab. Belitung (Kuadran I: Unggulan)'],
    ['x' => 55, 'y' => 58, 'label' => 'Kab. Bangka (Kuadran I: Unggulan)'],
    ['x' => 35, 'y' => 65, 'label' => 'Kab. Bangka Tengah (Kuadran II: Peluang Investasi)'],
    ['x' => 30, 'y' => 42, 'label' => 'Kab. Belitung Timur (Kuadran III: Potensial)'],
    ['x' => 22, 'y' => 38, 'label' => 'Kab. Bangka Barat (Kuadran III: Potensial)'],
    ['x' => 15, 'y' => 25, 'label' => 'Kab. Bangka Selatan (Kuadran III: Potensial)']
];
?>

<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-4">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200 mb-1">
                <i class="fa-solid fa-table-cells-large text-[11px]"></i>
                <span>Matriks Penentuan Kebijakan</span>
            </div>
            <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                Matriks 4 Kuadran Prioritas Pengembangan Amenitas Pariwisata
            </h3>
            <p class="text-xs text-slate-500">
                Pemetaan posisi strategis 7 Kabupaten/Kota berdasarkan daya serap kunjungan terhadap ketersediaan fasilitas
            </p>
        </div>
        <span class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-full self-start sm:self-auto">
            Garis Ambang Batas Rata-rata (50, 50)
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
                label: 'Posisi Kabupaten/Kota',
                data: kPoints,
                backgroundColor: '#7c3aed',
                borderColor: '#6d28d9',
                pointRadius: 9,
                pointHoverRadius: 13,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
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
                            return context.raw.label || 'Daerah';
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
                    max: 100,
                    title: {
                        display: true,
                        text: 'Tingkat Ketersediaan Amenitas Hotel & Restoran (0 - 100)',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                        color: '#334155'
                    },
                    grid: {
                        color: context => context.tick.value === 50 ? '#94a3b8' : '#f1f5f9',
                        lineWidth: context => context.tick.value === 50 ? 2 : 1
                    }
                },
                y: {
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Tingkat Kunjungan Wisatawan BPS (0 - 100)',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                        color: '#334155'
                    },
                    grid: {
                        color: context => context.tick.value === 50 ? '#94a3b8' : '#f1f5f9',
                        lineWidth: context => context.tick.value === 50 ? 2 : 1
                    }
                }
            }
        }
    });
});
</script>
