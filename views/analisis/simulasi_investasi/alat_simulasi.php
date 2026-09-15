<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      alat_simulasi.php
 * @folder    views/analisis/simulasi_investasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Simulator Kebijakan: Optimalisasi RLMT & Konversi Day-Tripper)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen simulasi kebijakan interaktif (What-If Policy Simulation).
 * Menggantikan kalkulator lama yang naif dengan model ekonometrika pariwisata BPS:
 * 1. Menyesuaikan Target Rata-Rata Lama Menginap (RLMT) dari 1.55 s/d 3.00 malam.
 * 2. Menghitung efek Konversi Day-Tripper menjadi Stayers (0% s/d 15%).
 * 3. Menghitung proyeksi kenaikan Tingkat Penghunian Kamar (TPK) dari dasar 26.70%.
 * 4. Memproyeksikan perputaran ekonomi sektor riil berbasis Kajian Pengeluaran BPS.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 5
 * - Berjalan di sisi klien secara reaktif (Reactive Vanilla JS)
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- ALAT SIMULASI KEBIJAKAN & DAMPAK EKONOMI RLMT (BPS BASED)      -->
<!-- ============================================================== -->
<div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-5 sm:p-7 text-white shadow-xl border border-white/10 space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-white/10 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-300 border border-indigo-400/30 mb-1.5">
                <i class="fa-solid fa-sliders text-[11px]"></i>
                <span>Simulasi Ekonometrika Pariwisata BPS (What-If Policy)</span>
            </div>
            <h3 class="text-base sm:text-lg font-black text-white tracking-tight">
                Simulator Peningkatan Rata-Rata Lama Menginap (RLMT) &amp; Konversi Stayers
            </h3>
            <p class="text-xs text-slate-300">
                Uji dampak kebijakan: Berapa tambahan perputaran ekonomi dan okupansi kamar jika kita menaikkan durasi tinggal wisatawan di Babel?
            </p>
        </div>
        <span class="text-xs font-bold text-amber-300 bg-amber-500/20 border border-amber-400/30 px-3 py-1 rounded-full self-start sm:self-auto">
            Base RLMT: 1.55 Malam | Base TPK: 26.70%
        </span>
    </div>

    <!-- Kontrol Input Slider & Hasil Perhitungan Dinamis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
        
        <!-- Sisi Kiri: Slider Pengaturan Parameter Kebijakan -->
        <div class="space-y-5 bg-white/5 p-4 sm:p-5 rounded-2xl border border-white/10">
            
            <!-- Slider 1: Target RLMT -->
            <div class="space-y-2">
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-200">Target Rata-Rata Lama Menginap (RLMT):</span>
                    <span id="labelTargetRlmt" class="text-indigo-400 font-mono text-sm font-black">2.20 Malam (+0.65)</span>
                </div>
                <input type="range" id="sliderRlmt" min="1.55" max="3.00" step="0.05" value="2.20" 
                    class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-500"
                    oninput="updatePolicySimulation()">
                <div class="flex justify-between text-[10px] text-slate-400">
                    <span>1.55 (Eksisting)</span>
                    <span>2.08 (Level Belitung)</span>
                    <span>3.00 (Target Bali/DIY)</span>
                </div>
                <p class="text-[10px] text-slate-400">
                    * Dicapai melalui integrasi rute paket wisata antar-kabupaten, atraksi malam hari (night market), dan event budaya tahunan.
                </p>
            </div>

            <!-- Slider 2: Konversi Day-Tripper Menjadi Stayers -->
            <div class="space-y-2 pt-2 border-t border-white/10">
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-200">Konversi Day-Tripper Jadi Menginap:</span>
                    <span id="labelKonversiDaytripper" class="text-emerald-400 font-mono text-sm font-black">5.0% (185.000 Jiwa)</span>
                </div>
                <input type="range" id="sliderKonversi" min="0" max="15" step="1" value="5" 
                    class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-emerald-500"
                    oninput="updatePolicySimulation()">
                <div class="flex justify-between text-[10px] text-slate-400">
                    <span>0% (Status Quo)</span>
                    <span>7.5% (Moderat)</span>
                    <span>15% (Agresif)</span>
                </div>
                <p class="text-[10px] text-slate-400">
                    * Mengubah pelancong pulang-hari (Bangka/Pangkalpinang) menjadi tamu menginap di homestay/hotel lokal.
                </p>
            </div>

        </div>

        <!-- Sisi Kanan: 4 Kartu Output Hasil Kalkulasi Proyeksi Real-time -->
        <div class="grid grid-cols-2 gap-3.5">
            
            <!-- Hasil 1: Tambahan Malam Kamar (Room Nights) -->
            <div class="p-4 rounded-2xl bg-indigo-500/20 border border-indigo-400/30 space-y-1">
                <span class="text-[10px] uppercase font-bold text-indigo-300 block">Tambahan Room-Nights</span>
                <div id="simulasiRoomNights" class="text-xl sm:text-2xl font-black text-indigo-200 font-mono">
                    +678,800
                </div>
                <div class="text-[11px] text-slate-300">Malam Kamar / Tahun</div>
            </div>

            <!-- Hasil 2: Proyeksi TPK Hotel Baru -->
            <div class="p-4 rounded-2xl bg-sky-500/20 border border-sky-400/30 space-y-1">
                <span class="text-[10px] uppercase font-bold text-sky-300 block">Proyeksi Okupansi TPK</span>
                <div id="simulasiTpk" class="text-xl sm:text-2xl font-black text-sky-200 font-mono">
                    38.45%
                </div>
                <div class="text-[11px] text-emerald-400 font-bold" id="simulasiTpkGain">+11.75% dari 26.70%</div>
            </div>

            <!-- Hasil 3: Tambahan Perputaran Ekonomi -->
            <div class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 space-y-1">
                <span class="text-[10px] uppercase font-bold text-emerald-300 block">Injeksi Ekonomi Riil</span>
                <div id="simulasiEkonomi" class="text-xl sm:text-2xl font-black text-emerald-200 font-mono">
                    +Rp 848.5 M
                </div>
                <div class="text-[11px] text-slate-300">Belanja Wisatawan / Thn</div>
            </div>

            <!-- Hasil 4: Peluang Homestay Penyangga -->
            <div class="p-4 rounded-2xl bg-amber-500/20 border border-amber-400/30 space-y-1">
                <span class="text-[10px] uppercase font-bold text-amber-300 block">Spillover Penyangga</span>
                <div id="simulasiSpillover" class="text-xl sm:text-2xl font-black text-amber-200 font-mono">
                    55,500
                </div>
                <div class="text-[11px] text-slate-300">Tamu Basel &amp; Beltim</div>
            </div>

            <!-- Keterangan Landasan Ekonometrika BPS -->
            <div class="col-span-2 p-3.5 rounded-xl bg-white/5 border border-white/10 text-[11px] text-slate-300 leading-relaxed">
                <strong>Rasionalisasi Ilmiah Mentor &amp; BPS:</strong> Tingkat hunian hotel tahunan Babel baru berkisar di 25.5%-26.7%. 
                Membangun hotel baru secara sembarangan justru akan memicu perang tarif dan inefisiensi investasi. 
                Strategi paling rasional adalah <strong>menaikkan durasi tinggal (RLMT)</strong> dan <strong>mengonversi day-trippers menjadi stayers</strong> dengan rata-rata pengeluaran Rp 1.250.000 / hari (Survei Pola Konsumsi Wisnus BPS RI).
            </div>

        </div>

    </div>

</div>

<!-- Logika Reaktif Perhitungan Simulasi BPS -->
<script>
function updatePolicySimulation() {
    const rlmt = parseFloat(document.getElementById('sliderRlmt').value) || 1.55;
    const konversiPersen = parseFloat(document.getElementById('sliderKonversi').value) || 0;

    const deltaRlmt = Math.max(0, rlmt - 1.55);
    document.getElementById('labelTargetRlmt').innerText = `${rlmt.toFixed(2)} Malam (+${deltaRlmt.toFixed(2)})`;

    // Baseline tahunan BPS:
    // Total Wisnus Setahun ~4.52 Juta.
    // Stayers Tahunan ~ 17.1% x 4.52 Juta = 772,900 jiwa.
    // Day-Trippers Tahunan ~ 82.9% x 4.52 Juta = 3,747,100 jiwa.
    const baselineStayers = 772900;
    const baselineDaytrippers = 3747100;

    // Jiwa day-tripper yang berhasil dikonversi menjadi stayers
    const convertedStayers = Math.round(baselineDaytrippers * (konversiPersen / 100));
    document.getElementById('labelKonversiDaytripper').innerText = `${konversiPersen}% (${convertedStayers.toLocaleString()} Jiwa)`;

    const totalStayersBaru = baselineStayers + convertedStayers;

    // Room-nights baru:
    // Baseline room nights = baselineStayers x 1.55 = 1,198,000
    // Target room nights = totalStayersBaru x rlmt
    const baselineRoomNights = baselineStayers * 1.55;
    const targetRoomNights = totalStayersBaru * rlmt;
    const deltaRoomNights = Math.round(targetRoomNights - baselineRoomNights);

    // Kenaikan TPK:
    // Jika baseline room nights menghasilkan TPK 26.70%, maka TPK baru sebanding dengan rasio pertambahan room nights
    const baselineTpk = 26.70;
    const newTpk = Math.min(85.0, baselineTpk * (targetRoomNights / baselineRoomNights));
    const deltaTpk = newTpk - baselineTpk;

    // Injeksi Ekonomi Riil:
    // Rata-rata pengeluaran wisnus menginap per hari = Rp 1.250.000
    const injeksiEkonomiMiliar = (deltaRoomNights * 1.25) / 1000; // dalam Miliar Rupiah

    // Spillover ke homestay penyangga (Bangka Selatan & Belitung Timur): ~30% dari converted stayers
    const spilloverBuffer = Math.round(convertedStayers * 0.30);

    // Render ke UI
    document.getElementById('simulasiRoomNights').innerText = `+${deltaRoomNights.toLocaleString()}`;
    document.getElementById('simulasiTpk').innerText = `${newTpk.toFixed(2)}%`;
    document.getElementById('simulasiTpkGain').innerText = `+${deltaTpk.toFixed(2)}% dari ${baselineTpk.toFixed(2)}%`;
    document.getElementById('simulasiEkonomi').innerText = `+Rp ${injeksiEkonomiMiliar.toFixed(1)} M`;
    document.getElementById('simulasiSpillover').innerText = spilloverBuffer.toLocaleString();
}

document.addEventListener('DOMContentLoaded', function() {
    updatePolicySimulation();
});
</script>

