<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/smart_itinerary/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (Smart Itinerary Optimizer View - Novelty Pilar 1)
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/AlgoritmaItinerary.php';
?>

<div class="space-y-6 pb-20 md:pb-10">

    <!-- 1. HEADER HERO RINGKAS & BERKELAS -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-ocean-950 to-slate-900 border border-slate-800 p-5 sm:p-7 shadow-lg text-white">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-ocean-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ocean-500/20 border border-ocean-400/30 text-ocean-300 text-xs font-bold tracking-wide">
                    <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                    <span>Novelty Skripsi &bull; Multi-Objective Orienteering Optimization</span>
                </div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                    Smart Itinerary <span class="bg-gradient-to-r from-ocean-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">Optimizer</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Sistem cerdas penyusun jadwal perjalanan wisata otomatis. Memaksimalkan kepuasan kunjungan dalam batasan <strong class="text-white">waktu liburan</strong>, <strong class="text-white">anggaran (budget)</strong>, dan <strong class="text-white">jarak tempuh terpendek</strong>.
                </p>
            </div>
            
            <div class="flex items-center gap-2 shrink-0">
                <a href="#panel-hasil" class="px-4 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white font-bold text-xs flex items-center gap-2 transition-all shadow-md active:scale-95">
                    <i class="fa-solid fa-bolt"></i>
                    <span>Rekomendasi Cepat</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. FORMULIR PARAMETER PINTAR (WIZARD COMPACT CARD) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6">
        <div class="border-b border-slate-100 pb-4 mb-5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Kustomisasi Preferensi Liburan</h2>
                    <p class="text-xs text-slate-500">Sesuaikan durasi, titik mulai, dan batas biaya perjalananmu</p>
                </div>
            </div>
            <span class="text-[11px] font-semibold text-slate-400 hidden sm:inline-block">Multi-Constraint OPTW</span>
        </div>

        <form id="form-itinerary" onsubmit="generateRute(event)" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <!-- Parameter 1: Fokus Wilayah / Pulau Eksplorasi -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-map-location-dot text-ocean-600"></i>
                            <span>Wilayah Eksplorasi</span>
                        </span>
                        <span class="text-[10px] text-ocean-600 font-bold bg-ocean-50 px-2 py-0.5 rounded-full">Multi-Wilayah</span>
                    </label>
                    <select name="kabupaten" id="select-wilayah-itinerary" onchange="syncWilayahDanTitikAwal('wilayah')" class="w-full text-xs font-semibold px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-ocean-500 focus:outline-none transition-colors">
                        <optgroup label="✨ Rekomendasi Tur Multi-Wilayah (Pulau)">
                            <option value="semua_bangka" selected>🏝️ Seluruh Pulau Bangka (Multi-Kabupaten)</option>
                            <option value="semua_belitung">🏝️ Seluruh Pulau Belitung (Belitung &amp; Beltim)</option>
                        </optgroup>
                        <optgroup label="📍 Khusus Satu Kabupaten/Kota">
                            <option value="Kota Pangkalpinang">🏛️ Kota Pangkalpinang (Heritage &amp; Kuliner)</option>
                            <option value="Kabupaten Bangka">🏖️ Kab. Bangka (Sungailiat &amp; Wisata Bahari)</option>
                            <option value="Kabupaten Belitung">🪨 Kab. Belitung (Tanjung Pandan &amp; Granit)</option>
                            <option value="Kabupaten Belitung Timur">📖 Kab. Belitung Timur (Laskar Pelangi &amp; Manggar)</option>
                            <option value="Kabupaten Bangka Tengah">🌊 Kab. Bangka Tengah (Danau Kaolin &amp; Alam)</option>
                            <option value="Kabupaten Bangka Barat">🏰 Kab. Bangka Barat (Muntok &amp; Sejarah)</option>
                            <option value="Kabupaten Bangka Selatan">🌅 Kab. Bangka Selatan (Toboali &amp; Pantai)</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Parameter 2: Titik Awal / Gerbang Kedatangan -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-rose-500"></i>
                        <span>Titik Keberangkatan</span>
                    </label>
                    <select name="titik_awal_key" id="select-titik-awal-itinerary" onchange="syncWilayahDanTitikAwal('titik_awal')" class="w-full text-xs font-semibold px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-ocean-500 focus:outline-none transition-colors">
                        <optgroup label="Pulau Bangka">
                            <option value="bandara_pgk" selected>✈️ Bandara Depati Amir (Pangkalpinang)</option>
                            <option value="alun_alun_taman_merdeka">🏛️ Alun-Alun Pusat Pangkalpinang</option>
                            <option value="pelabuhan_pangkalbalam">🚢 Pelabuhan Pangkal Balam (PGK)</option>
                            <option value="pelabuhan_mentok">🚢 Pelabuhan Tanjung Kalian (Muntok)</option>
                        </optgroup>
                        <optgroup label="Pulau Belitung">
                            <option value="bandara_tjq">✈️ Bandara Hanandjoeddin (Belitung)</option>
                            <option value="pelabuhan_tanjungpandan">🚢 Pelabuhan Tanjung Pandan (Belitung)</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Parameter 3: Durasi Hari -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar-days text-ocean-600"></i>
                        <span>Durasi Wisata</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="durasi_hari" value="1" class="peer sr-only">
                            <div class="py-2.5 text-center text-xs font-bold rounded-xl border border-slate-200 text-slate-600 peer-checked:border-ocean-600 peer-checked:bg-ocean-50 peer-checked:text-ocean-700 transition-all">
                                1 Hari
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="durasi_hari" value="2" class="peer sr-only">
                            <div class="py-2.5 text-center text-xs font-bold rounded-xl border border-slate-200 text-slate-600 peer-checked:border-ocean-600 peer-checked:bg-ocean-50 peer-checked:text-ocean-700 transition-all">
                                2 Hari
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="durasi_hari" value="3" checked class="peer sr-only">
                            <div class="py-2.5 text-center text-xs font-bold rounded-xl border border-slate-200 text-slate-600 peer-checked:border-ocean-600 peer-checked:bg-ocean-50 peer-checked:text-ocean-700 transition-all">
                                3 Hari
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Parameter 4: Anggaran Maksimal (Budget) -->
                <div class="space-y-1.5 sm:col-span-2 lg:col-span-2">
                    <label class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-wallet text-emerald-600"></i>
                            <span>Batas Anggaran Liburan (Budget)</span>
                        </span>
                        <span id="display-budget" class="text-ocean-600 font-extrabold font-mono text-xs">Rp 1.000.000</span>
                    </label>
                    <input type="range" id="input-budget" name="budget_max" min="100000" max="3000000" step="50000" value="1000000" oninput="updateBudgetDisplay(this.value)" class="w-full accent-ocean-600 cursor-pointer">
                    <div class="flex justify-between text-[10px] text-slate-400 font-medium">
                        <span>Rp 100 rb</span>
                        <span>Rp 1.5 jt</span>
                        <span>Rp 3 jt</span>
                    </div>
                </div>

                <!-- Parameter 5: Ritme Perjalanan -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-gauge-high text-amber-500"></i>
                        <span>Ritme Wisata</span>
                    </label>
                    <select name="ritme" class="w-full text-xs font-semibold px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-ocean-500 focus:outline-none transition-colors">
                        <option value="standar" selected>⚡ Standar (4 - 5 destinasi/hari)</option>
                        <option value="santai">🏖️ Santai (2 - 3 destinasi/hari)</option>
                        <option value="padat">🚀 Padat/Eksplorasi (6 destinasi/hari)</option>
                    </select>
                </div>

            </div>

            <!-- Preferensi Kategori Wisata -->
            <div class="pt-2 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-slate-600 mr-1">Fokus Minat:</span>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="preferensi[]" value="Alam" checked class="peer sr-only">
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:border-emerald-300 transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-umbrella-beach text-[11px]"></i>
                            <span>Pantai &amp; Alam</span>
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="preferensi[]" value="Kebudayaan" checked class="peer sr-only">
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 peer-checked:bg-rose-50 peer-checked:text-rose-700 peer-checked:border-rose-300 transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-landmark text-[11px]"></i>
                            <span>Sejarah &amp; Budaya</span>
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="preferensi[]" value="Kuliner" checked class="peer sr-only">
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 peer-checked:bg-amber-50 peer-checked:text-amber-700 peer-checked:border-amber-300 transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-utensils text-[11px]"></i>
                            <span>Kuliner Khas</span>
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="preferensi[]" value="Buatan" class="peer sr-only">
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 peer-checked:bg-sky-50 peer-checked:text-sky-700 peer-checked:border-sky-300 transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-camera text-[11px]"></i>
                            <span>Spot Foto &amp; Rekreasi</span>
                        </span>
                    </label>
                </div>

                <button type="submit" id="btn-submit-itinerary" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Optimasi Jadwal &amp; Rute</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 3. PANEL HASIL GENERASI JADWAL (HASIL KOMPUTASI ILMIAH) -->
    <div id="panel-hasil" class="space-y-5">
        
        <!-- Loading Spinner -->
        <div id="loading-itinerary" class="hidden py-12 text-center space-y-3">
            <div class="inline-block w-8 h-8 border-4 border-ocean-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-xs font-bold text-slate-600">Menghitung optimasi rute matematis (Heuristic 2-Opt &amp; Time Windows)...</p>
        </div>

        <!-- Konten Hasil -->
        <div id="itinerary-content" class="space-y-6">
            <!-- Diisi secara dinamis oleh JavaScript -->
        </div>

    </div>

</div>

<!-- SCRIPT LOGIKA REAKTIF ITINERARY -->
<script>
function updateBudgetDisplay(val) {
    document.getElementById('display-budget').innerText = 'Rp ' + Number(val).toLocaleString('id-ID');
}

function syncWilayahDanTitikAwal(source) {
    const selWilayah = document.getElementById('select-wilayah-itinerary');
    const selTitik = document.getElementById('select-titik-awal-itinerary');

    if (source === 'wilayah') {
        const val = selWilayah.value;
        const isBelitung = val.includes('Belitung');
        if (isBelitung) {
            if (selTitik.value !== 'bandara_tjq' && selTitik.value !== 'pelabuhan_tanjungpandan') {
                selTitik.value = 'bandara_tjq';
            }
        } else {
            if (selTitik.value === 'bandara_tjq' || selTitik.value === 'pelabuhan_tanjungpandan') {
                selTitik.value = 'bandara_pgk';
            }
        }
    } else if (source === 'titik_awal') {
        const val = selTitik.value;
        const isBelitung = (val === 'bandara_tjq' || val === 'pelabuhan_tanjungpandan');
        if (isBelitung && !selWilayah.value.includes('Belitung')) {
            selWilayah.value = 'semua_belitung';
        } else if (!isBelitung && selWilayah.value.includes('Belitung')) {
            selWilayah.value = 'semua_bangka';
        }
    }
}

// Inisialisasi awal saat halaman dibuka
document.addEventListener('DOMContentLoaded', () => {
    generateRute();
});

async function generateRute(e) {
    if (e) e.preventDefault();

    const form = document.getElementById('form-itinerary');
    const formData = new FormData(form);
    const searchParams = new URLSearchParams(formData);

    const loading = document.getElementById('loading-itinerary');
    const content = document.getElementById('itinerary-content');
    const btn = document.getElementById('btn-submit-itinerary');

    loading.classList.remove('hidden');
    content.innerHTML = '';
    btn.disabled = true;
    btn.classList.add('opacity-75');

    try {
        const res = await fetch('api/v1_itinerary.php?' + searchParams.toString());
        const data = await res.json();

        loading.classList.add('hidden');
        btn.disabled = false;
        btn.classList.remove('opacity-75');

        if (data.status !== 'success') {
            content.innerHTML = `
                <div class="p-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                    <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                    ${data.pesan || 'Gagal memproses rekomendasi rute.'}
                </div>
            `;
            return;
        }

        renderItineraryResult(data);
    } catch (err) {
        loading.classList.add('hidden');
        btn.disabled = false;
        btn.classList.remove('opacity-75');
        content.innerHTML = `
            <div class="p-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                Terjadi kesalahan koneksi API: ${err.message}
            </div>
        `;
    }
}

function renderItineraryResult(data) {
    const content = document.getElementById('itinerary-content');
    const r = data.ringkasan_ilmiah;
    const p = data.parameter;

    let html = `
        <!-- Metrik Ringkasan Ilmiah -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
            <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                <div class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Total Destinasi</div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1">${r.total_destinasi} <span class="text-xs font-semibold text-slate-500">Spot</span></div>
                <div class="text-[11px] text-ocean-600 font-semibold mt-0.5">${r.total_hari} Hari Perjalanan</div>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                <div class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Estimasi Jarak</div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1">${r.grand_total_jarak_km} <span class="text-xs font-semibold text-slate-500">km</span></div>
                <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">Efisien 2-Opt TSP</div>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                <div class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Total Biaya</div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1 font-mono">Rp ${Number(r.grand_total_biaya).toLocaleString('id-ID')}</div>
                <div class="text-[11px] font-semibold mt-0.5 ${r.sisa_budget >= 0 ? 'text-emerald-600' : 'text-amber-600'}">
                    Sisa: Rp ${Number(r.sisa_budget).toLocaleString('id-ID')}
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                <div class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Efisiensi Rute</div>
                <div class="text-xl sm:text-2xl font-black text-ocean-600 mt-1">${r.efisiensi_rute_persen}%</div>
                <div class="text-[11px] text-slate-500 font-semibold mt-0.5">Optimasi Multi-Kendala</div>
            </div>
        </div>
    `;

    // Render Jadwal Per Hari
    data.jadwal_harian.forEach(day => {
        html += `
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-ocean-500/20 text-ocean-300 border border-ocean-400/30">
                            Hari Ke-${day.hari}
                        </span>
                        <h3 class="text-base sm:text-lg font-bold text-white mt-1">${day.judul_hari}</h3>
                    </div>
                    <div class="text-left sm:text-right text-xs text-slate-300">
                        <span>${day.total_destinasi} Destinasi &bull; Jarak: <strong>${day.total_jarak_km} km</strong> &bull; Biaya: <strong>Rp ${Number(day.total_biaya_hari).toLocaleString('id-ID')}</strong></span>
                    </div>
                </div>

                <div class="p-5 sm:p-6 space-y-4">
                    <!-- Timeline Steps -->
                    <div class="relative border-l-2 border-slate-200 ml-4 sm:ml-6 pl-5 sm:pl-7 space-y-6">
        `;

        day.rute_perjalanan.forEach((stop, idx) => {
            const isStart = stop.tipe === 'start_point';
            const iconBg = isStart ? 'bg-slate-900 text-white' : (stop.sub_kategori === 'Restoran' ? 'bg-amber-500 text-white' : 'bg-ocean-600 text-white');
            const iconFa = isStart ? 'fa-flag-checkered' : (stop.sub_kategori === 'Restoran' ? 'fa-utensils' : 'fa-location-dot');

            html += `
                <div class="relative group">
                    <!-- Marker Dot di Timeline -->
                    <div class="absolute -left-[31px] sm:-left-[39px] top-0 w-8 h-8 rounded-full ${iconBg} flex items-center justify-center text-xs font-bold shadow-xs">
                        <i class="fa-solid ${iconFa}"></i>
                    </div>

                    <div class="bg-slate-50 hover:bg-ocean-50/50 p-4 rounded-2xl border border-slate-200/70 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-bold font-mono px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700">
                                        ${stop.jam_mulai || stop.jam_tiba} - ${stop.jam_selesai}
                                    </span>
                                    <span class="text-xs text-slate-500 font-medium">(${stop.durasi_kunjungan_menit || 0} menit)</span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-slate-900 mt-1">${stop.nama}</h4>
                                <p class="text-xs text-slate-500">${stop.kecamatan ? stop.kecamatan + ', ' : ''}${stop.kabupaten} &bull; <span class="font-semibold text-ocean-700">${stop.sub_kategori || stop.kategori}</span></p>
                            </div>

                            ${!isStart ? `
                                <div class="text-left sm:text-right text-xs shrink-0">
                                    <div class="font-bold text-emerald-700">Tiket: Rp ${Number(stop.estimasi_biaya.tiket).toLocaleString('id-ID')}</div>
                                    ${stop.estimasi_biaya.kuliner > 0 ? `<div class="text-[11px] text-amber-700 font-semibold">Makan: Rp ${Number(stop.estimasi_biaya.kuliner).toLocaleString('id-ID')}</div>` : ''}
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=${stop.latitude},${stop.longitude}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-ocean-600 hover:text-ocean-700 mt-1">
                                        <span>Buka Google Maps</span>
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                    </a>
                                </div>
                            ` : `
                                <span class="text-[11px] font-bold text-slate-400">Titik Awal Rute</span>
                            `}
                        </div>

                        ${stop.jarak_dari_sebelumnya_km > 0 ? `
                            <div class="mt-2.5 pt-2 border-t border-slate-200/60 flex items-center gap-3 text-[11px] text-slate-500">
                                <span><i class="fa-solid fa-car text-ocean-600 mr-1"></i> ${stop.jarak_dari_sebelumnya_km} km perjalanan</span>
                                <span><i class="fa-regular fa-clock text-slate-400 mr-1"></i> ~${stop.waktu_tempuh_menit} menit di jalan</span>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
        });

        html += `
                    </div>
                </div>
            </div>
        `;
    });

    content.innerHTML = html;
}
</script>
