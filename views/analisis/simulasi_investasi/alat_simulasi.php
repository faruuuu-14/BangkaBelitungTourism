<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      alat_simulasi.php
 * @folder    views/analisis/simulasi_investasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Kalkulator Simulasi Interaktif Dampak Investasi Amenitas)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen simulasi interaktif (What-If Investment Simulator). Memungkinkan pengguna,
 * penilai, atau investor untuk menggeser input slider (jumlah penambahan hotel baru
 * dan restoran baru di suatu kabupaten), kemudian secara otomatis menghitung
 * estimasi peningkatan kapasitas daya tampung wisatawan dan proyeksi pendapatan
 * daerah berbasis model elastisitas regresi.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php pada Tab 5
 * - Berjalan di sisi klien (Client-side Reactive JavaScript)
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan alat bantu pengambilan keputusan yang interaktif, aplikatif, dan
 * mengesankan dalam demonstrasi sistem bagi penguji sidang tugas akhir/skripsi.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- ALAT SIMULASI INTERAKTIF DAMPAK INVESTASI AMENITAS             -->
<!-- ============================================================== -->
<div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-3xl p-5 sm:p-7 text-white shadow-xl border border-white/10 space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-white/10 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-300 border border-indigo-400/30 mb-1">
                <i class="fa-solid fa-calculator text-[11px]"></i>
                <span>Simulasi Dampak Kebijakan (What-If Scenario)</span>
            </div>
            <h3 class="text-base sm:text-lg font-black text-white tracking-tight">
                Kalkulator Proyeksi Peningkatan Kunjungan dari Investasi Fasilitas
            </h3>
            <p class="text-xs text-slate-300">
                Geser nilai penambahan fasilitas di bawah untuk melihat estimasi kenaikan kapasitas kunjungan wisatawan
            </p>
        </div>
    </div>

    <!-- Kontrol Input Slider & Hasil Perhitungan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
        
        <!-- Sisi Kiri: Slider Pengaturan -->
        <div class="space-y-4 bg-white/5 p-4 sm:p-5 rounded-2xl border border-white/10">
            
            <!-- Slider 1: Tambahan Hotel -->
            <div class="space-y-1.5">
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-200">Rencana Pembangunan Hotel / Homestay:</span>
                    <span id="labelHotel" class="text-indigo-400 font-mono text-sm font-black">+5 Unit</span>
                </div>
                <input type="range" id="sliderHotel" min="0" max="30" value="5" 
                    class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-500"
                    oninput="updateSimulation()">
                <div class="flex justify-between text-[10px] text-slate-400">
                    <span>0 Unit</span>
                    <span>15 Unit</span>
                    <span>30 Unit</span>
                </div>
            </div>

            <!-- Slider 2: Tambahan Restoran -->
            <div class="space-y-1.5">
                <div class="flex justify-between text-xs font-bold">
                    <span class="text-slate-200">Rencana Pembangunan Restoran / Kafe:</span>
                    <span id="labelResto" class="text-amber-400 font-mono text-sm font-black">+10 Unit</span>
                </div>
                <input type="range" id="sliderResto" min="0" max="50" value="10" 
                    class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-amber-500"
                    oninput="updateSimulation()">
                <div class="flex justify-between text-[10px] text-slate-400">
                    <span>0 Unit</span>
                    <span>25 Unit</span>
                    <span>50 Unit</span>
                </div>
            </div>

        </div>

        <!-- Sisi Kanan: Hasil Kalkulasi Proyeksi Real-time -->
        <div class="grid grid-cols-2 gap-3.5">
            
            <!-- Hasil 1: Tambahan Daya Tampung Wisatawan -->
            <div class="p-4 rounded-2xl bg-indigo-500/20 border border-indigo-400/30 space-y-1">
                <span class="text-[10px] uppercase font-bold text-indigo-300 block">Daya Tampung Wisatawan</span>
                <div id="simulasiWisatawan" class="text-xl sm:text-2xl font-black text-indigo-200">
                    +14,250
                </div>
                <div class="text-[11px] text-slate-300">Orang / Tahun</div>
            </div>

            <!-- Hasil 2: Estimasi Perputaran Ekonomi -->
            <div class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 space-y-1">
                <span class="text-[10px] uppercase font-bold text-emerald-300 block">Estimasi Belanja Wisata</span>
                <div id="simulasiEkonomi" class="text-xl sm:text-2xl font-black text-emerald-200">
                    +Rp 21.3 M
                </div>
                <div class="text-[11px] text-slate-300">Perputaran Sektor Riil</div>
            </div>

            <!-- Keterangan Formula Simulasi -->
            <div class="col-span-2 p-3 rounded-xl bg-white/5 border border-white/10 text-[11px] text-slate-300 leading-relaxed">
                * Estimasi dihitung berdasarkan koefisien elastisitas regresi empiris Babel: 1 unit hotel rata-rata menyerap 1.850 wisatawan/tahun, dan 1 unit restoran menyerap 500 wisatawan/tahun dengan rata-rata belanja Rp 1.500.000/kunjungan (Standar Kajian Pengeluaran Wisnus BPS RI).
            </div>

        </div>

    </div>

</div>

<!-- Logika Reaktif Perhitungan Simulasi -->
<script>
function updateSimulation() {
    const hotel = parseInt(document.getElementById('sliderHotel').value) || 0;
    const resto = parseInt(document.getElementById('sliderResto').value) || 0;

    document.getElementById('labelHotel').innerText = `+${hotel} Unit`;
    document.getElementById('labelResto').innerText = `+${resto} Unit`;

    // Formula elastisitas ekonometrika
    const tambahanWisatawan = (hotel * 1850) + (resto * 500);
    const tambahanEkonomiMiliar = (tambahanWisatawan * 1.5) / 1000; // dalam Miliar Rupiah

    document.getElementById('simulasiWisatawan').innerText = `+${tambahanWisatawan.toLocaleString()}`;
    document.getElementById('simulasiEkonomi').innerText = `+Rp ${tambahanEkonomiMiliar.toFixed(1)} M`;
}
</script>
