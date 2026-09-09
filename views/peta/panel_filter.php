<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      panel_filter.php
 * @folder    views/peta/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Bilah Filter Spasial & Kontrol Layer Peta WebGIS)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen bilah kendali atas peta WebGIS. Menyediakan input pencarian cepat destinasi,
 * filter pemilihan kabupaten/kota, filter kategori 3A (Alam, Budaya, Buatan, Hotel, Resto),
 * tombol alih mode (Pin Marker vs Heatmap), tombol visualisasi radius buffer 5 km,
 * serta tombol pembuka Perencana Rute Wisata Cerdas.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/peta/index.php
 * - Mengakses variabel: $kabList dan $places
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan kendali penyaringan data spasial yang responsif dan fleksibel
 * tanpa menutupi area pandang peta secara berlebihan.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 1. BILAH FILTER & KONTROL ATAS PETA SPASIAL                   -->
<!-- ============================================================== -->
<div class="bg-white/95 backdrop-blur-md border-b border-slate-200 px-3 sm:px-6 py-2 z-20 shadow-xs flex flex-col gap-2 flex-shrink-0 w-full overflow-hidden">
    
    <!-- Baris 1: Judul, Jumlah Titik, Mode Pin/Heatmap, Buffer, & Tombol Rute -->
    <div class="flex flex-wrap sm:flex-nowrap items-center justify-between gap-2 w-full min-w-0">
        
        <!-- Sisi Kiri: Judul & Penghitung Titik -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-map-location-dot text-sm"></i>
            </div>
            <div>
                <h1 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-tight">Peta Spasial Babel</h1>
                <div class="hidden sm:flex items-center gap-1 text-[10px] text-slate-500">
                    <span>Radius Buffer 5 km</span> &bull; <span>Heatmap</span> &bull; <span>Rute</span>
                </div>
            </div>
            <span id="counterBadge" class="px-2 py-0.5 rounded-lg bg-slate-900 text-white text-[10px] font-bold">
                <?= count($places) ?> Titik
            </span>
        </div>

        <!-- Sisi Kanan: Tombol Mode Peta & Drawer -->
        <div class="flex items-center gap-1.5 flex-shrink-0">
            
            <!-- Switcher: Pin vs Heatmap -->
            <div class="inline-flex rounded-xl bg-slate-100 p-0.5 border border-slate-200">
                <button id="btnModePin" onclick="setMapMode('pin')" class="px-2.5 py-1 rounded-lg font-bold bg-white text-slate-900 shadow-xs flex items-center gap-1 text-[11px] transition-all" title="Mode Pin Marker">
                    <i class="fa-solid fa-location-dot text-ocean-600 text-[10px]"></i>
                    <span>Pin</span>
                </button>
                <button id="btnModeHeat" onclick="setMapMode('heat')" class="px-2.5 py-1 rounded-lg font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1 text-[11px] transition-all" title="Mode Heatmap Kepadatan">
                    <i class="fa-solid fa-fire text-amber-500 text-[10px]"></i>
                    <span>Heatmap</span>
                </button>
            </div>

            <!-- Tombol Toggle Buffer 5 km Amenitas -->
            <button id="btnBuffer" onclick="toggleBuffer()" class="px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-bold transition-all shadow-xs flex items-center gap-1" title="Buffer Radius 5 km Fasilitas Hotel & Restoran">
                <i class="fa-solid fa-bullseye text-ocean-600 text-[10px]"></i>
                <span class="hidden md:inline">Radius 5 km</span>
            </button>

            <!-- Tombol Toggle Buffer Cincin Kemenparekraf (15, 30, 50 km) -->
            <button id="btnCatchment" onclick="toggleCatchmentRings()" class="px-2.5 py-1.5 rounded-xl border border-purple-200 bg-purple-50 hover:bg-purple-100 text-purple-800 text-[11px] font-bold transition-all shadow-xs flex items-center gap-1" title="Buffer Aksesibilitas Bandara/Pelabuhan (15, 30, 50 km)">
                <i class="fa-solid fa-plane-departure text-purple-600 text-[10px]"></i>
                <span class="hidden md:inline">Buffer Akses</span>
            </button>

            <!-- Tombol Perencana Rute Cerdas -->
            <button onclick="toggleItineraryDrawer()" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-ocean-600 text-white text-[11px] font-bold transition-all shadow-xs flex items-center gap-1.5">
                <i class="fa-solid fa-route text-amber-400"></i>
                <span class="hidden sm:inline">Perencana Rute</span>
                <span id="itineraryCountBadge" class="w-4 h-4 rounded-full bg-amber-400 text-slate-950 text-[9px] font-black flex items-center justify-center">0</span>
            </button>

            <!-- Tombol Legenda Peta -->
            <button onclick="openModalLegenda()" class="p-1.5 px-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all" title="Buka Legenda Peta">
                <i class="fa-solid fa-layer-group text-slate-600"></i>
            </button>

        </div>

    </div>

    <!-- Baris 2: Pencarian Cepat & Filter Kategori -->
    <div class="flex items-center gap-2 overflow-x-auto pb-0.5">
        
        <!-- Kolom Pencarian Cepat -->
        <div class="relative flex-1 min-w-[180px] sm:min-w-[240px]">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari pantai, hotel, museum..."
                class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-ocean-500 outline-hidden font-medium"
                oninput="applyFilter()">
        </div>

        <!-- Filter Kabupaten -->
        <select id="kabFilter" onchange="applyFilter()" class="text-xs rounded-xl border-slate-200 bg-slate-50 py-1.5 px-2.5 font-medium text-slate-700 focus:ring-1 focus:ring-ocean-500">
            <option value="">Semua Kabupaten/Kota</option>
            <?php foreach ($kabList as $kab): ?>
            <option value="<?= htmlspecialchars($kab) ?>"><?= htmlspecialchars($kab) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Filter Simpul Gerbang Transportasi (Bandara & Pelabuhan) -->
        <select id="hubFilter" onchange="onHubFilterChange()" class="text-xs rounded-xl border border-purple-200 bg-purple-50/90 py-1.5 px-2.5 font-bold text-purple-900 focus:ring-1 focus:ring-purple-500">
            <option value="all">✈️⚓ Semua Simpul (Bandara & Pelabuhan)</option>
            <option value="bandara">✈️ Bandara Udara (PGK & TJQ)</option>
            <option value="pelabuhan">⚓ Pelabuhan Laut (5 Pintu Masuk)</option>
            <option value="none">Sembunyikan Simpul</option>
        </select>

        <!-- Filter Kategori Chip Buttons -->
        <div class="flex items-center gap-1">
            <button onclick="filterCategory('Semua')" class="cat-chip px-2.5 py-1 rounded-xl text-[11px] font-bold bg-slate-900 text-white shadow-xs whitespace-nowrap">Semua</button>
            <button onclick="filterCategory('Alam')" class="cat-chip px-2.5 py-1 rounded-xl text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 whitespace-nowrap">Alam</button>
            <button onclick="filterCategory('Kebudayaan')" class="cat-chip px-2.5 py-1 rounded-xl text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 whitespace-nowrap">Budaya</button>
            <button onclick="filterCategory('Buatan')" class="cat-chip px-2.5 py-1 rounded-xl text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 whitespace-nowrap">Buatan</button>
            <button onclick="filterCategory('Hotel')" class="cat-chip px-2.5 py-1 rounded-xl text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-200 hover:bg-sky-100 whitespace-nowrap">Hotel</button>
            <button onclick="filterCategory('Restoran')" class="cat-chip px-2.5 py-1 rounded-xl text-[11px] font-semibold bg-orange-50 text-orange-700 border border-orange-200 hover:bg-orange-100 whitespace-nowrap">Restoran</button>
        </div>

    </div>

</div>
