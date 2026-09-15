<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      tren_bulanan_bps.php
 * @folder    views/analisis/ringkasan/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Grafik Interaktif Tren Bulanan Wisnus & Kurva Musiman TPK BPS)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen visualisasi deret waktu bulanan resmi BPS (112 Baris di Database).
 * Menyajikan 2 mode visualisasi dinamis:
 * 1. Tren Kunjungan Wisnus (2025: 12 Bulan vs 2026: Jan-Apr) dengan pemilih
 *    Kabupaten/Kota real-time (Bangka, Belitung, Bangka Barat, Bangka Tengah,
 *    Bangka Selatan, Belitung Timur, Pangkal Pinang, dan Total Provinsi).
 * 2. Kurva Pola Musiman Tingkat Penghunian Kamar (TPK Hotel Bintang)
 *    Membuktikan fenomena Low Season (Maret/Ramadan) dan Peak Season (April & Des).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 1 (Ringkasan BPS & Makro)
 * - Pustaka data: app/statistics.php
 * ==============================================================================
 */

// Siapkan data bulanan untuk seluruh wilayah dan masing-masing 7 kabupaten
$kabupatenList = [
    'semua'          => 'Seluruh Provinsi Kep. Bangka Belitung',
    'Bangka'         => 'Kab. Bangka',
    'Belitung'       => 'Kab. Belitung',
    'Bangka Barat'   => 'Kab. Bangka Barat',
    'Bangka Tengah'  => 'Kab. Bangka Tengah',
    'Bangka Selatan' => 'Kab. Bangka Selatan',
    'Belitung Timur' => 'Kab. Belitung Timur',
    'Pangkal Pinang' => 'Kota Pangkal Pinang'
];

$allMonthlyData = [];
foreach ($kabupatenList as $key => $label) {
    $allMonthlyData[$key] = getBpsMonthlyTimeSeries($key === 'semua' ? null : $key);
}

$tpkMonthly = getBpsTpkMonthlyData();
?>

<div class="bg-white rounded-2xl sm:rounded-3xl p-3.5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-3.5 sm:space-y-5">
    
    <!-- Header Komponen & Pilihan Tampilan -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between pb-3 sm:pb-4 border-b border-slate-100 gap-2 sm:gap-3">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-0.5 rounded-full text-[10px] sm:text-xs font-bold bg-ocean-50 text-ocean-700 border border-ocean-200 mb-1 sm:mb-1.5">
                <i class="fa-solid fa-calendar-days text-[10px] sm:text-[11px]"></i>
                <span>Data Deret Waktu Bulanan BPS (112 Baris Riil)</span>
            </div>
            <h3 class="text-xs sm:text-lg font-black text-slate-900 tracking-tight">
                Tren Bulanan Perjalanan Wisnus &amp; Okupansi Hotel
            </h3>
            <p class="text-[11px] sm:text-xs text-slate-500">
                Pilih wilayah untuk membandingkan kunjungan 2025 vs 2026 atau cek kurva TPK hotel.
            </p>
        </div>

        <!-- Tombol Pengalih Mode & Dropdown Kabupaten -->
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 self-start lg:self-auto">
            <!-- Mode Switcher -->
            <div class="flex items-center bg-slate-100 p-0.5 sm:p-1 rounded-xl sm:rounded-2xl">
                <button onclick="switchMonthlyMode('wisnus')" id="btnModeWisnus" class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl text-[11px] sm:text-xs font-bold transition-all bg-white text-ocean-700 shadow-xs">
                    <i class="fa-solid fa-users mr-1"></i> Wisnus
                </button>
                <button onclick="switchMonthlyMode('tpk')" id="btnModeTpk" class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl text-[11px] sm:text-xs font-bold transition-all text-slate-600 hover:text-slate-900">
                    <i class="fa-solid fa-hotel mr-1"></i> TPK (%)
                </button>
            </div>

            <!-- Dropdown Filter Kabupaten (Hanya aktif di mode Wisnus) -->
            <div id="kabupatenFilterWrapper">
                <select id="selectKabupatenMonthly" onchange="onKabupatenMonthlyChange()" class="px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl border border-slate-200 text-[11px] sm:text-xs font-bold text-slate-700 bg-white shadow-xs focus:ring-2 focus:ring-ocean-500 focus:outline-none">
                    <?php foreach ($kabupatenList as $kKey => $kLabel): ?>
                        <option value="<?= htmlspecialchars($kKey) ?>"><?= htmlspecialchars($kLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Panel Status / Highlight Dinamis -->
    <div id="monthlyStatBanner" class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
        <div class="p-2.5 sm:p-3 bg-ocean-50/70 border border-ocean-100 rounded-xl sm:rounded-2xl">
            <span class="text-[9px] sm:text-[10px] uppercase font-bold text-ocean-700 block truncate">Total 2025</span>
            <div class="text-sm sm:text-lg font-black text-ocean-950 font-mono" id="statTotal2025">4,525,604</div>
            <span class="text-[9px] sm:text-[10px] text-slate-500">12 Bulan</span>
        </div>
        <div class="p-2.5 sm:p-3 bg-emerald-50/70 border border-emerald-100 rounded-xl sm:rounded-2xl">
            <span class="text-[9px] sm:text-[10px] uppercase font-bold text-emerald-700 block truncate">Jan-Apr 2026</span>
            <div class="text-sm sm:text-lg font-black text-emerald-900 font-mono" id="statTotal2026">1,585,978</div>
            <span class="text-[9px] sm:text-[10px] text-emerald-600 font-bold">YoY: +9.95%</span>
        </div>
        <div class="p-2.5 sm:p-3 bg-amber-50/70 border border-amber-100 rounded-xl sm:rounded-2xl">
            <span class="text-[9px] sm:text-[10px] uppercase font-bold text-amber-800 block truncate">Puncak (Peak)</span>
            <div class="text-sm sm:text-lg font-black text-amber-900 truncate" id="statPeakMonth">April &amp; Des</div>
            <span class="text-[9px] sm:text-[10px] text-slate-500">Libur Lebaran/Nataru</span>
        </div>
        <div class="p-2.5 sm:p-3 bg-rose-50/70 border border-rose-100 rounded-xl sm:rounded-2xl">
            <span class="text-[9px] sm:text-[10px] uppercase font-bold text-rose-700 block truncate">Rendah (Low)</span>
            <div class="text-sm sm:text-lg font-black text-rose-900 truncate" id="statLowMonth">Maret (Ramadan)</div>
            <span class="text-[9px] sm:text-[10px] text-slate-500">Okupansi 16-24%</span>
        </div>
    </div>

    <!-- Kanvas Grafik Chart.js -->
    <div class="h-52 sm:h-80 md:h-96 w-full relative">
        <canvas id="monthlyTrendChart"></canvas>
    </div>

    <!-- Callout Catatan Fenomena Seasonality -->
    <div id="monthlySeasonalityNote" class="p-3 sm:p-4 bg-slate-50 rounded-xl sm:rounded-2xl border border-slate-200/80 flex items-start gap-2.5 sm:gap-3 text-[11px] sm:text-xs text-slate-600 leading-snug sm:leading-relaxed">
        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 text-xs sm:text-sm mt-0.5 font-bold">
            <i class="fa-solid fa-lightbulb"></i>
        </div>
        <div>
            <strong class="text-slate-900">Temuan Kunci Musiman (BPS Babel):</strong>
            <span id="seasonalityText">
                TPK hotel di Babel rata-rata tahunan hanya <strong>25.5% s/d 26.7%</strong> (73% kamar kosong). Lonjakan musiman tajam hanya terjadi saat <em>April (Idulfitri - 30.39%)</em> dan <em>Desember (Nataru - 31.28%)</em>, sedangkan bulan <em>Maret (Ramadan)</em> turun ke <em>16.04%</em>.
            </span>
        </div>
    </div>

</div>

<script>
let monthlyChartInstance = null;
let currentMonthlyMode = 'wisnus';

const monthlyBpsData = <?= json_encode($allMonthlyData) ?>;
const monthlyTpkData = <?= json_encode($tpkMonthly) ?>;

function renderMonthlyChart() {
    const ctx = document.getElementById('monthlyTrendChart');
    if (!ctx) return;

    if (monthlyChartInstance) {
        monthlyChartInstance.destroy();
    }

    if (currentMonthlyMode === 'wisnus') {
        const selectedKab = document.getElementById('selectKabupatenMonthly').value;
        const data = monthlyBpsData[selectedKab] || monthlyBpsData['semua'];

        // Update banner stat
        document.getElementById('statTotal2025').innerText = Number(data.total_2025).toLocaleString();
        document.getElementById('statTotal2026').innerText = Number(data.total_2026_q1).toLocaleString();
        document.getElementById('statPeakMonth').innerText = 'April & Des';
        document.getElementById('statLowMonth').innerText = 'Maret (Ramadan)';

        monthlyChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Realisasi Wisnus 2025 (1 Tahun Penuh)',
                        data: data.series_2025,
                        backgroundColor: 'rgba(148, 163, 184, 0.4)',
                        borderColor: '#94a3b8',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Realisasi Wisnus 2026 (Jan - Apr BPS)',
                        data: data.series_2026,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.1)',
                        borderWidth: 3,
                        pointRadius: 6,
                        pointBackgroundColor: '#0284c7',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        tension: 0.25,
                        fill: true,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#475569' }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw ? Number(context.raw).toLocaleString() + ' Perjalanan' : '-'}`;
                            }
                        },
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
    } else {
        // Mode TPK Okupansi Hotel
        monthlyChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyTpkData.labels,
                datasets: [
                    {
                        label: 'TPK Hotel Bintang 2025 (%)',
                        data: monthlyTpkData.tpk_2025,
                        borderColor: '#94a3b8',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 5,
                        pointBackgroundColor: '#94a3b8',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'TPK Hotel Bintang 2026 (%)',
                        data: monthlyTpkData.tpk_2026,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 3,
                        pointRadius: 7,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        fill: true,
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
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#475569' }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw ? context.raw + '%' : '-'}`;
                            }
                        },
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
                        min: 10,
                        max: 40,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 10 },
                            color: '#64748b',
                            callback: val => val + '%'
                        }
                    }
                }
            }
        });
    }
}

function switchMonthlyMode(mode) {
    currentMonthlyMode = mode;

    const btnWisnus = document.getElementById('btnModeWisnus');
    const btnTpk = document.getElementById('btnModeTpk');
    const filterWrapper = document.getElementById('kabupatenFilterWrapper');

    if (mode === 'wisnus') {
        btnWisnus.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-ocean-700 shadow-xs';
        btnTpk.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900';
        filterWrapper.classList.remove('hidden');
    } else {
        btnTpk.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all bg-white text-amber-700 shadow-xs';
        btnWisnus.className = 'px-3 py-1.5 rounded-xl text-xs font-bold transition-all text-slate-600 hover:text-slate-900';
        filterWrapper.classList.add('hidden');
    }

    renderMonthlyChart();
}

function onKabupatenMonthlyChange() {
    renderMonthlyChart();
}

document.addEventListener('DOMContentLoaded', function() {
    renderMonthlyChart();
});
</script>
