<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      grafik_sebaran.php
 * @folder    views/analisis/korelasi_pearson/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Grafik Sebaran Scatter Plot BPS: Stayers & Day-Trippers vs RLMT)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen visualisasi sebaran korelasi empiris (Scatter Plot & Garis Regresi Linear).
 * Menggantikan formula naif lama dengan indikator resmi BPS April 2026:
 * 1. Tamu Menginap (X2) vs RLMT (Y): r = +0.5613 (Tren Naik Positif)
 * 2. Wisnus Day-Tripper (X3) vs RLMT (Y): r = -0.4991 (Tren Turun Negatif)
 * 3. Total Wisnus (X1) vs RLMT (Y): r = -0.2823 (Tren Negatif Lemah)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php
 * - Menggunakan data: $macroCorr dari app/statistics.php
 * - Pustaka: Chart.js
 * ==============================================================================
 */

$scatterX2 = $macroCorr['scatter_x2_y'] ?? [];
$scatterX3 = $macroCorr['scatter_x3_y'] ?? [];
$scatterX1 = $macroCorr['scatter_x1_y'] ?? [];
$matrix = $macroCorr['matrix_details'] ?? getBpsPearsonMatrix();
?>

<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
    
    <!-- Kepala Komponen & Pemilih Variabel Interaktif -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between pb-4 border-b border-slate-100 gap-3">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/80 mb-1.5">
                <i class="fa-solid fa-chart-line text-[11px]"></i>
                <span>Visualisasi Hubungan Empiris BPS (N = 7 Wilayah)</span>
            </div>
            <h4 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">
                Diagram Pencar (Scatter Plot) &amp; Garis Tren Regresi BPS
            </h4>
            <p class="text-xs text-slate-500 max-w-2xl">
                Uji linearitas antara variabel wisatawan terhadap Rata-Rata Lama Menginap (RLMT) di 7 Kabupaten/Kota Provinsi Kepulauan Bangka Belitung.
            </p>
        </div>

        <!-- Tombol Pengalih Skenario Korelasi -->
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-2xl self-start lg:self-auto overflow-x-auto max-w-full">
            <button onclick="setScatterView('x2')" id="btnScatterX2" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-indigo-700 shadow-xs whitespace-nowrap">
                <i class="fa-solid fa-bed mr-1"></i> X2: Stayers vs RLMT (+0.56)
            </button>
            <button onclick="setScatterView('x3')" id="btnScatterX3" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 whitespace-nowrap">
                <i class="fa-solid fa-person-walking-luggage mr-1"></i> X3: Day-Tripper (-0.50)
            </button>
            <button onclick="setScatterView('x1')" id="btnScatterX1" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 whitespace-nowrap">
                <i class="fa-solid fa-users mr-1"></i> X1: Total Wisnus (-0.28)
            </button>
        </div>
    </div>

    <!-- Panel Status Indikator Terpilih -->
    <!-- Panel Status Indikator Terpilih (Bahasa Mudah Dipahami) -->
    <div id="scatterInsightBox" class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-3">
            <div id="scatterIcon" class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-base shrink-0 shadow-xs">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
            <div>
                <div class="font-bold text-slate-900 text-sm" id="scatterTitle">
                    Tamu Hotel (X2) vs Lama Menginap / RLMT (Y)
                </div>
                <div class="text-slate-600 text-[11px] leading-relaxed" id="scatterDesc">
                    Di daerah tujuan liburan seperti Belitung, wisatawan datang untuk berlibur beberapa hari sehingga menyewa hotel lebih lama (hubungan searah/positif).
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
            <div class="text-right">
                <div class="text-[10px] uppercase font-bold text-slate-400">Koefisien r</div>
                <div class="text-base font-black text-indigo-700 font-mono" id="scatterRVal">+0.5613</div>
            </div>
            <div class="h-7 w-px bg-slate-200 mx-1"></div>
            <div class="text-right">
                <div class="text-[10px] uppercase font-bold text-slate-400">Kekuatan (R&sup2;)</div>
                <div class="text-base font-black text-slate-700 font-mono" id="scatterR2Val">31.5%</div>
            </div>
        </div>
    </div>

    <!-- Kanvas Scatter Plot -->
    <div class="h-72 sm:h-96 w-full relative">
        <canvas id="pearsonScatterChart"></canvas>
    </div>

    <!-- Catatan Penjelasan Mudah -->
    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-start gap-2.5 text-[11px] text-slate-600 leading-relaxed">
        <i class="fa-solid fa-circle-info text-indigo-600 mt-0.5 shrink-0 text-xs"></i>
        <div>
            <strong>Cara Membaca Grafik:</strong> Setiap titik mewakili 1 Kabupaten/Kota di Bangka Belitung. 
            Perhatikan titik paling atas yaitu <span class="font-bold text-emerald-700">Kabupaten Belitung</span> (lama menginap tertinggi: 2.08 malam) karena didominasi wisatawan yang bermalam. 
            Sebaliknya, daerah seperti Bangka Barat dan Bangka Selatan berada di bawah karena lebih dari 90% pengunjungnya adalah pelancong yang langsung pulang di hari yang sama.
        </div>
    </div>

</div>

<script>
let pearsonChartInstance = null;

const scatterDatasets = {
    x2: {
        title: 'Tamu Hotel (X2) vs Lama Menginap / RLMT (Y)',
        desc: 'Di daerah tujuan liburan seperti Belitung, wisatawan datang untuk berlibur beberapa hari sehingga menyewa hotel lebih lama (hubungan searah/positif).',
        r: '+0.5613',
        r2: '31.5%',
        icon: '<i class="fa-solid fa-arrow-trend-up"></i>',
        boxBg: 'bg-indigo-50/70',
        boxBorder: 'border-indigo-100',
        iconBg: 'bg-indigo-600',
        xTitle: 'Jumlah Tamu Menginap di Hotel (Jiwa)',
        data: <?= json_encode($scatterX2) ?>,
        color: '#4f46e5',
        trendColor: '#4338ca',
        slope: 0.000035,
        intercept: 1.18
    },
    x3: {
        title: 'Wisatawan Harian (X3) vs Lama Menginap / RLMT (Y)',
        desc: 'Makin banyak wisatawan yang datang hanya untuk pulang-pergi di hari yang sama, rata-rata durasi tinggal di daerah tersebut menjadi makin rendah (hubungan berlawanan).',
        r: '-0.4991',
        r2: '24.9%',
        icon: '<i class="fa-solid fa-arrow-trend-down"></i>',
        boxBg: 'bg-amber-50/70',
        boxBorder: 'border-amber-100',
        iconBg: 'bg-amber-600',
        xTitle: 'Jumlah Wisatawan Harian Pulang-Hari (Jiwa)',
        data: <?= json_encode($scatterX3) ?>,
        color: '#d97706',
        trendColor: '#b45309',
        slope: -0.000009,
        intercept: 1.82
    },
    x1: {
        title: 'Total Kunjungan (X1) vs Lama Menginap / RLMT (Y)',
        desc: 'Banyaknya jumlah wisatawan yang datang belum tentu membuat kamar hotel terisi, karena lebih dari 80% wisatawan di Babel adalah pelancong harian non-menginap.',
        r: '-0.2823',
        r2: '8.0%',
        icon: '<i class="fa-solid fa-minus"></i>',
        boxBg: 'bg-slate-100',
        boxBorder: 'border-slate-200',
        iconBg: 'bg-slate-700',
        xTitle: 'Total Perjalanan Wisatawan Nusantara (Jiwa)',
        data: <?= json_encode($scatterX1) ?>,
        color: '#64748b',
        trendColor: '#475569',
        slope: -0.0000045,
        intercept: 1.66
    }
};

function renderScatterChart(viewKey) {
    const ctx = document.getElementById('pearsonScatterChart');
    if (!ctx) return;

    const conf = scatterDatasets[viewKey];
    const points = conf.data;

    // Hitung titik regresi linear (min X dan max X)
    const xValues = points.map(p => p.x);
    const minX = Math.min(...xValues);
    const maxX = Math.max(...xValues);
    
    // Regresi linear formal: y = mx + c
    const lineStart = { x: minX, y: Math.max(0.8, conf.slope * minX + conf.intercept) };
    const lineEnd   = { x: maxX, y: Math.max(0.8, conf.slope * maxX + conf.intercept) };

    if (pearsonChartInstance) {
        pearsonChartInstance.destroy();
    }

    pearsonChartInstance = new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [
                {
                    type: 'scatter',
                    label: 'Realisasi BPS 7 Kabupaten/Kota',
                    data: points,
                    backgroundColor: conf.color,
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    pointRadius: 9,
                    pointHoverRadius: 13,
                    pointBackgroundColor: conf.color
                },
                {
                    type: 'line',
                    label: 'Garis Tren Regresi Linear (Trendline)',
                    data: [lineStart, lineEnd],
                    borderColor: conf.trendColor,
                    borderWidth: 2.5,
                    borderDash: [6, 4],
                    pointRadius: 0,
                    fill: false,
                    tension: 0
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
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 1) {
                                return `Garis Tren Regresi (r = ${conf.r})`;
                            }
                            const p = context.raw;
                            return `${p.label}: ${p.x.toLocaleString()} Jiwa, RLMT: ${p.y.toFixed(2)} Malam (${p.karakter})`;
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
                        text: conf.xTitle,
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                        color: '#334155'
                    },
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 10 },
                        color: '#64748b',
                        callback: val => val.toLocaleString()
                    }
                },
                y: {
                    min: 0.8,
                    max: 2.4,
                    title: {
                        display: true,
                        text: 'Rata-Rata Lama Menginap / RLMT (Malam)',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '700' },
                        color: '#334155'
                    },
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 10 },
                        color: '#64748b',
                        callback: val => val.toFixed(1) + ' hr'
                    }
                }
            }
        }
    });
}

function setScatterView(viewKey) {
    const conf = scatterDatasets[viewKey];
    if (!conf) return;

    // Perbarui Teks Status
    document.getElementById('scatterTitle').innerText = conf.title;
    document.getElementById('scatterDesc').innerText = conf.desc;
    document.getElementById('scatterRVal').innerText = conf.r;
    document.getElementById('scatterR2Val').innerText = conf.r2;

    const iconEl = document.getElementById('scatterIcon');
    iconEl.className = `w-10 h-10 rounded-xl ${conf.iconBg} text-white flex items-center justify-center text-base shrink-0 shadow-xs`;
    iconEl.innerHTML = conf.icon;

    // Perbarui Tombol Aktif
    ['x2', 'x3', 'x1'].forEach(k => {
        const btn = document.getElementById('btnScatter' + k.toUpperCase());
        if (btn) {
            if (k === viewKey) {
                btn.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-indigo-700 shadow-xs whitespace-nowrap';
            } else {
                btn.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900 whitespace-nowrap';
            }
        }
    });

    renderScatterChart(viewKey);
}

document.addEventListener('DOMContentLoaded', function() {
    renderScatterChart('x2');
});
</script>
