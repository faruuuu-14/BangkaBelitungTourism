<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      perencana_rute.php
 * @folder    views/peta/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Panel Geser Perencana Rute Wisata Cerdas / Itinerary Planner)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen panel geser samping (Slide-over Drawer) untuk menyusun jadwal dan rute
 * perjalanan wisata cerdas di Bangka Belitung. Memungkinkan wisatawan menambahkan
 * beberapa destinasi dari popup peta ke dalam daftar kunjungan, menghitung total
 * jarak perjalanan antar titik secara otomatis (rumus Haversine), estimasi waktu
 * tempuh kendaraan darat, serta menampilkan thumbnail foto otentik setiap destinasi.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/peta/index.php
 * - Dikendalikan oleh fungsi JS di: skrip_peta.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan nilai guna nyata bagi wisatawan yang ingin merencanakan liburan
 * efisien dan bebas repot keliling pulau Bangka atau pulau Belitung.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 3. PANEL PERENCANA RUTE WISATA CERDAS (SLIDE-OVER DRAWER)      -->
<!-- ============================================================== -->
<div id="itineraryDrawer" class="fixed inset-y-0 right-0 z-50 w-full sm:w-96 bg-white shadow-2xl border-l border-slate-200 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    
    <!-- Kepala Panel Rute -->
    <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-900 text-white">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center font-bold text-xs shadow-xs">
                <i class="fa-solid fa-route"></i>
            </div>
            <div>
                <h3 class="font-black text-sm text-white">Perencana Rute Wisata</h3>
                <p class="text-[10px] text-slate-400">Rute kunjungan destinasi optimal</p>
            </div>
        </div>
        <button onclick="toggleItineraryDrawer()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-xs transition-colors">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Ringkasan Jarak & Waktu Tempuh -->
    <div class="p-4 bg-slate-50 border-b border-slate-100 grid grid-cols-2 gap-3 text-xs">
        <div class="p-3 bg-white rounded-2xl border border-slate-200 space-y-0.5">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Jarak</span>
            <div id="itineraryTotalDist" class="text-base font-black text-ocean-700">0.0 km</div>
        </div>
        <div class="p-3 bg-white rounded-2xl border border-slate-200 space-y-0.5">
            <span class="text-[10px] uppercase font-bold text-slate-400 block">Estimasi Waktu</span>
            <div id="itineraryTotalTime" class="text-base font-black text-emerald-700">0 Menit</div>
        </div>
    </div>

    <!-- Daftar Perhentian Destinasi (Itinerary Stops) -->
    <div class="flex-1 p-4 overflow-y-auto space-y-2.5" id="itineraryList">
        <!-- Keadaan Kosong (Empty State) -->
        <div id="itineraryEmpty" class="py-12 text-center text-slate-400 space-y-2">
            <i class="fa-solid fa-map-location text-3xl text-slate-300"></i>
            <p class="text-xs">Belum ada destinasi yang ditambahkan.<br>Klik pin di peta lalu pilih <strong>"Tambah ke Rute"</strong>.</p>
        </div>
    </div>

    <!-- Tombol Aksi Kaki Panel -->
    <div class="p-4 border-t border-slate-100 bg-white space-y-2">
        <button onclick="drawItineraryRoute()" id="btnDrawRoute" class="w-full py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white font-extrabold text-xs shadow-md shadow-ocean-600/20 transition-all flex items-center justify-center gap-1.5 disabled:opacity-50" disabled>
            <i class="fa-solid fa-draw-polygon"></i>
            <span>Gambarkan Jalur di Peta</span>
        </button>
        <button onclick="clearItinerary()" class="w-full py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
            Kosongkan Daftar
        </button>
    </div>

</div>
