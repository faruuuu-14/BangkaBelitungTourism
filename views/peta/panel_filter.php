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
<div class="bg-white/95 backdrop-blur-md border-b border-slate-200 px-3 sm:px-6 py-2 z-20 shadow-xs flex flex-col gap-2 flex-shrink-0 w-full relative">
    
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

        <!-- Sisi Kanan: Tombol Kendali Peta Dikelompokkan Rapi (3 Kluster) -->
        <div class="flex items-center gap-2 flex-shrink-0">
            
            <!-- KLUSTER 1: Mode Visual Peta (Pin vs Heatmap) -->
            <div class="inline-flex rounded-xl bg-slate-100 p-0.5 border border-slate-200/80 shadow-2xs" title="Mode Tampilan Data">
                <button id="btnModePin" onclick="setMapMode('pin')" class="px-2.5 py-1.5 rounded-lg font-bold bg-white text-slate-900 shadow-xs flex items-center gap-1.5 text-[11px] transition-all" title="Mode Pin Marker Titik Wisata">
                    <i class="fa-solid fa-location-dot text-ocean-600 text-[10px]"></i>
                    <span>Pin</span>
                </button>
                <button id="btnModeHeat" onclick="setMapMode('heat')" class="px-2.5 py-1.5 rounded-lg font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 text-[11px] transition-all" title="Mode Heatmap Kepadatan Destinasi">
                    <i class="fa-solid fa-fire text-amber-500 text-[10px]"></i>
                    <span>Heatmap</span>
                </button>
            </div>

            <!-- Pemisah Antar-Kelompok -->
            <div class="hidden sm:block h-6 w-px bg-slate-200"></div>

            <!-- KLUSTER 2: Analisis Spasial & Layer GIS -->
            <div class="inline-flex items-center gap-1 p-0.5 rounded-xl bg-slate-100/70 border border-slate-200/70 shadow-2xs" title="Alat Analisis Spasial & Layer Peta">
                <!-- Tombol Toggle Buffer 5 km Amenitas -->
                <button id="btnBuffer" onclick="toggleBuffer()" class="px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-bold transition-all shadow-xs flex items-center gap-1.5" title="Buffer Radius 5 km Fasilitas Hotel & Restoran">
                    <i class="fa-solid fa-bullseye text-ocean-600 text-[10px]"></i>
                    <span class="hidden md:inline">Radius 5 km</span>
                </button>

                <!-- Tombol Toggle Buffer Cincin Kemenparekraf (15, 30, 50 km) -->
                <button id="btnCatchment" onclick="toggleCatchmentRings()" class="px-2.5 py-1.5 rounded-xl border border-purple-200 bg-purple-50 hover:bg-purple-100 text-purple-800 text-[11px] font-bold transition-all shadow-xs flex items-center gap-1.5" title="Buffer Aksesibilitas Bandara/Pelabuhan (15, 30, 50 km)">
                    <i class="fa-solid fa-plane-departure text-purple-600 text-[10px]"></i>
                    <span class="hidden md:inline">Buffer Akses</span>
                </button>

                <!-- Tombol Legenda Peta -->
                <button onclick="openModalLegenda()" class="p-1.5 px-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-bold transition-all shadow-xs flex items-center gap-1" title="Buka Legenda Simbol & Kategori Peta">
                    <i class="fa-solid fa-layer-group text-slate-600 text-[10px]"></i>
                    <span class="hidden lg:inline">Legenda</span>
                </button>
            </div>

            <!-- Pemisah Antar-Kelompok -->
            <div class="hidden sm:block h-6 w-px bg-slate-200"></div>

            <!-- KLUSTER 3: Aksi Rute & Ekspor Data Spasial -->
            <div class="inline-flex items-center gap-1.5">
                <!-- Tombol Perencana Rute Cerdas -->
                <button onclick="toggleItineraryDrawer()" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-ocean-600 text-white text-[11px] font-bold transition-all shadow-xs flex items-center gap-1.5" title="Buka Perencana Rute Wisata Cerdas">
                    <i class="fa-solid fa-route text-amber-400 text-xs"></i>
                    <span class="hidden sm:inline">Perencana Rute</span>
                    <span id="itineraryCountBadge" class="w-4 h-4 rounded-full bg-amber-400 text-slate-950 text-[9px] font-black flex items-center justify-center">0</span>
                </button>

                <!-- Tombol Unduh Data Spasial & Spreadsheet (Excel, GeoJSON, CSV) -->
                <div class="relative inline-block text-left z-50" id="exportDropdownContainer">
                    <button type="button" onclick="toggleExportMenu()" class="p-1.5 px-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs" title="Unduh Data Berdasarkan Filter Aktif">
                        <i class="fa-solid fa-cloud-arrow-down text-emerald-600"></i>
                        <span class="hidden xl:inline text-[11px]">Unduh Data</span>
                        <i class="fa-solid fa-chevron-down text-[8px] text-slate-400"></i>
                    </button>
                    
                    <!-- Menu Dropdown Floating Bebas Potong -->
                    <div id="exportMenu" class="hidden absolute right-0 top-full mt-2 w-72 sm:w-80 rounded-2xl bg-white shadow-2xl border border-slate-200/90 py-1.5 z-[100] text-xs font-semibold overflow-hidden">
                        
                        <!-- Header Kartu: Status Filter Terpilih Saat Ini -->
                        <div class="px-3.5 py-2.5 bg-slate-50 border-b border-slate-100">
                            <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                                <span>Filter Unduhan Aktif</span>
                                <span id="exportLiveCount" class="px-1.5 py-0.5 rounded-md bg-ocean-100 text-ocean-700 font-black">Memuat...</span>
                            </div>
                            <div class="mt-1 flex items-center gap-1.5 text-[11px] font-bold text-slate-800" id="exportLiveFilterDesc">
                                <i class="fa-solid fa-filter text-ocean-600 text-[10px] flex-shrink-0"></i>
                                <span class="truncate" id="exportFilterSummaryText">Semua Wilayah &bull; Semua Kategori</span>
                            </div>
                        </div>

                        <!-- Opsi 1: Microsoft Excel (.xls / .xlsx) -->
                        <button type="button" onclick="downloadFilteredData('excel')" class="w-full flex items-start gap-2.5 px-3.5 py-2.5 hover:bg-emerald-50/80 text-slate-700 hover:text-emerald-900 transition text-left group">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-file-excel text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 group-hover:text-emerald-700 text-xs flex items-center justify-between">
                                    <span>Microsoft Excel (.xls)</span>
                                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800">Utama</span>
                                </div>
                                <p class="text-[10px] text-slate-500 line-clamp-1">Tabel spreadsheet rapi, berformat &amp; siap olah</p>
                            </div>
                        </button>

                        <!-- Opsi 2: GeoJSON Spasial (RFC 7946) -->
                        <button type="button" onclick="downloadFilteredData('geojson')" class="w-full flex items-start gap-2.5 px-3.5 py-2.5 hover:bg-ocean-50/80 text-slate-700 hover:text-ocean-900 border-t border-slate-100 transition text-left group">
                            <div class="w-8 h-8 rounded-xl bg-ocean-100 text-ocean-700 flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-map-location-dot text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 group-hover:text-ocean-700 text-xs">GeoJSON (OGC RFC 7946)</div>
                                <p class="text-[10px] text-slate-500 line-clamp-1">Untuk aplikasi GIS (QGIS, ArcGIS, Leaflet)</p>
                            </div>
                        </button>

                        <!-- Opsi 3: CSV Tabular (UTF-8 BOM) -->
                        <button type="button" onclick="downloadFilteredData('csv')" class="w-full flex items-start gap-2.5 px-3.5 py-2.5 hover:bg-amber-50/80 text-slate-700 hover:text-amber-900 border-t border-slate-100 transition text-left group">
                            <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-file-csv text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 group-hover:text-amber-700 text-xs">CSV Tabular (UTF-8 BOM)</div>
                                <p class="text-[10px] text-slate-500 line-clamp-1">Kompatibel SPSS, R, Python &amp; Google Sheets</p>
                            </div>
                        </button>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
    function updateExportSummary() {
        const kabSelect = document.getElementById('kabFilter');
        const kabVal = kabSelect ? kabSelect.value : '';
        const catVal = (typeof currentCategory !== 'undefined') ? currentCategory : 'Semua';
        const searchInput = document.getElementById('searchInput');
        const searchVal = (searchInput ? searchInput.value : '').trim();
        
        const descElem = document.getElementById('exportFilterSummaryText');
        const countElem = document.getElementById('exportLiveCount');
        const counterBadge = document.getElementById('counterBadge');
        
        let parts = [];
        parts.push(kabVal ? kabVal : 'Semua Wilayah');
        parts.push(catVal !== 'Semua' ? catVal : 'Semua Kategori');
        if (searchVal) {
            parts.push(`"${searchVal}"`);
        }
        
        if (descElem) {
            descElem.innerText = parts.join(' • ');
        }
        if (countElem && counterBadge) {
            countElem.innerText = counterBadge.innerText;
        }
    }

    function toggleExportMenu() {
        const menu = document.getElementById('exportMenu');
        if (menu) {
            const isHidden = menu.classList.contains('hidden');
            if (isHidden) {
                updateExportSummary();
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        }
    }

    function downloadFilteredData(format) {
        const kab = document.getElementById('kabFilter')?.value || '';
        const cat = (typeof currentCategory !== 'undefined') ? currentCategory : 'Semua';
        const q = (document.getElementById('searchInput')?.value || '').trim();
        
        const params = new URLSearchParams();
        params.append('format', format);
        if (kab) params.append('kabupaten', kab);
        if (cat && cat !== 'Semua') params.append('kategori', cat);
        if (q) params.append('q', q);
        
        const menu = document.getElementById('exportMenu');
        if (menu) menu.classList.add('hidden');
        
        window.location.href = `api/export.php?${params.toString()}`;
    }

    document.addEventListener('click', function(e) {
        const container = document.getElementById('exportDropdownContainer');
        const menu = document.getElementById('exportMenu');
        if (container && menu && !container.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
    </script>

    <!-- Baris 2: Pencarian Cepat, Filter Wilayah (Highlighted), & Filter Kategori -->
    <div class="flex items-center gap-2 overflow-x-auto pb-0.5">
        
        <!-- Kolom Pencarian Cepat -->
        <div class="relative flex-1 min-w-[160px] sm:min-w-[220px]">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Cari pantai, hotel, museum..."
                class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-1 focus:ring-ocean-500 outline-hidden font-medium"
                oninput="applyFilter()">
        </div>

        <!-- Filter Kabupaten/Kota (DI-HIGHLIGHT: Menonjol, Elegan & Jelas untuk Diklik) -->
        <div class="relative flex-shrink-0 group">
            <div class="relative flex items-center">
                <i class="fa-solid fa-location-dot absolute left-2.5 text-ocean-600 text-xs pointer-events-none z-10 transition-transform group-hover:scale-110"></i>
                <select id="kabFilter" onchange="applyFilter()" 
                    class="appearance-none text-xs font-black rounded-xl border-2 border-ocean-500 bg-gradient-to-r from-ocean-50 via-teal-50/50 to-ocean-50 hover:from-ocean-100 hover:to-teal-100 hover:border-ocean-600 text-ocean-950 py-1.5 pl-7 pr-8 shadow-xs cursor-pointer focus:outline-hidden focus:ring-2 focus:ring-ocean-400 focus:border-ocean-600 transition-all">
                    <option value="" class="font-black text-ocean-950 bg-white">Semua Kabupaten/Kota (7)</option>
                    <?php foreach ($kabList as $kab): ?>
                    <option value="<?= htmlspecialchars($kab) ?>" class="text-slate-800 font-semibold bg-white"><?= htmlspecialchars($kab) ?></option>
                    <?php endforeach; ?>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-2.5 text-ocean-600 text-[10px] pointer-events-none z-10 transition-transform group-hover:translate-y-0.5"></i>
            </div>
        </div>

        <!-- Filter Simpul Gerbang Transportasi (Bandara & Pelabuhan) -->
        <div class="relative flex-shrink-0">
            <select id="hubFilter" onchange="onHubFilterChange()" class="appearance-none text-xs rounded-xl border border-purple-200 bg-purple-50/90 hover:bg-purple-100/90 py-1.5 pl-2.5 pr-7 font-bold text-purple-900 focus:ring-1 focus:ring-purple-500 cursor-pointer shadow-xs transition-all">
                <option value="all">✈️⚓ Semua Simpul</option>
                <option value="bandara">✈️ Bandara (PGK & TJQ)</option>
                <option value="pelabuhan">⚓ Pelabuhan Laut (5 Pintu)</option>
                <option value="none">Sembunyikan Simpul</option>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-purple-600 text-[9px] pointer-events-none"></i>
        </div>

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
