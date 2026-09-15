<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      legenda_informasi.php
 * @folder    views/peta/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Modal Legenda Peta & Keterangan Radius Jangkauan Buffer)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen modal dialog informasi legenda peta spasial WebGIS. Menjelaskan kode
 * warna pin marker kategori (Hijau = Wisata Alam, Ungu = Kebudayaan, Kuning = Buatan,
 * Biru = Akomodasi Hotel, Merah = Restoran/Kuliner), keterangan radius penyangga
 * (Buffer Radius 5 km) amenitas terhadap daya tarik, serta tips navigasi peta.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/peta/index.php
 * - Dipicu oleh: tombol ikon layer pada panel_filter.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memenuhi standar kartografi digital dan kaidah IMK (Human-Computer Interaction)
 * agar pengguna tidak bingung mengartikan warna dan simbol pada peta.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 4. DIALOG MODAL LEGENDA PETA & KETERANGAN SPASIAL              -->
<!-- ============================================================== -->
<div id="modalLegendaPeta" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden space-y-4 p-5 sm:p-6">
        
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-ocean-600 text-sm"></i>
                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Legenda &amp; Simbol Peta WebGIS</h3>
            </div>
            <button onclick="closeModalLegenda()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Rincian Warna Kategori Marker -->
        <div class="space-y-2.5 text-xs text-slate-700">
            <div class="font-bold text-slate-900 text-[11px] uppercase tracking-wider text-slate-400">
                Warna Pin Marker Objek (Standar 3A Kemenparekraf)
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="font-semibold text-xs">Wisata Alam</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="w-3.5 h-3.5 rounded-full bg-rose-500 shrink-0"></span>
                    <span class="font-semibold text-xs">Kebudayaan</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="w-3.5 h-3.5 rounded-full bg-amber-500 shrink-0"></span>
                    <span class="font-semibold text-xs">Wisata Buatan</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="w-3.5 h-3.5 rounded-full bg-sky-500 shrink-0"></span>
                    <span class="font-semibold text-xs">Akomodasi Hotel</span>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-100 col-span-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-red-500 shrink-0"></span>
                    <span class="font-semibold text-xs">Restoran &amp; Rumah Makan</span>
                </div>
            </div>
        </div>

        <!-- Penjelasan Spektrum Panas Heatmap (Thermal Glow) -->
        <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200 text-xs text-slate-700 space-y-1.5">
            <div class="font-bold text-amber-950 flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <i class="fa-solid fa-fire text-amber-500"></i>
                    <span>Spektrum Panas Heatmap Kepadatan:</span>
                </div>
                <span class="text-[10px] text-amber-800 font-bold">Kontras Tinggi</span>
            </div>
            <!-- Bar gradasi warna cerah -->
            <div class="h-2.5 w-full rounded-full bg-gradient-to-r from-yellow-300 via-orange-500 via-red-500 via-rose-600 to-purple-800 shadow-xs"></div>
            <div class="flex justify-between text-[10px] text-slate-500 font-semibold px-0.5">
                <span>Rendah (Kuning)</span>
                <span>Sedang (Oranye)</span>
                <span>Tinggi (Merah)</span>
                <span>Pusat Inti (Ungu)</span>
            </div>
        </div>

        <!-- Penjelasan Buffer Radius 5 km -->
        <div class="p-3.5 rounded-2xl bg-ocean-50/70 border border-ocean-200 text-xs text-slate-600 space-y-1">
            <div class="font-bold text-ocean-900 flex items-center gap-1.5">
                <i class="fa-solid fa-bullseye text-ocean-600"></i>
                <span>Fungsi Radius Penyangga (Buffer 5 km):</span>
            </div>
            <p class="text-[11px] leading-relaxed">
                Lingkaran biru muda menandakan radius jangkauan layanan amenitas dasar (10-15 menit berkendara). Jika suatu objek wisata berada di luar lingkaran buffer, destinasi tersebut masuk kategori <em>amenity-isolated</em> yang mendesak untuk dilengkapi fasilitas hotel/restoran.
            </p>
        </div>

        <button onclick="closeModalLegenda()" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors">
            Tutup Legenda Peta
        </button>

    </div>
</div>

<script>
function openModalLegenda() {
    const m = document.getElementById('modalLegendaPeta');
    if (m) m.classList.remove('hidden');
}
function closeModalLegenda() {
    const m = document.getElementById('modalLegendaPeta');
    if (m) m.classList.add('hidden');
}
</script>
