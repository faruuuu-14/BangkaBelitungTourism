<?php
require_once __DIR__ . '/../app/database.php';
require_once __DIR__ . '/../app/statistics.php';
$pdo = getDbConnection();

// Ambil semua data titik resmi untuk peta
$sql = "
    SELECT id, nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude
    FROM tempat_wisata
    WHERE latitude IS NOT NULL AND longitude IS NOT NULL AND status_verifikasi = 'Resmi'
    ORDER BY ulasan DESC
";
$places = $pdo->query($sql)->fetchAll();

// Daftar Kabupaten unik
$kabList = array_unique(array_column($places, 'kabupaten'));
sort($kabList);

// Data Simpul Transportasi & Analisis Catchment Area
$hubs = getTransportHubs();
$catchmentStats = getAccessibilityCatchmentStats();
?>

<main class="flex-grow flex flex-col h-[calc(100dvh-3.5rem)] lg:h-[calc(100dvh-4rem)] pb-14 lg:pb-0 relative overflow-hidden w-full max-w-full">
    
    <!-- Filter Bar Header (Responsive, Never Overlaps on Mobile or 100% Laptop Zoom) -->
    <div class="bg-white/95 backdrop-blur-md border-b border-slate-200 px-2.5 sm:px-6 py-2 z-20 shadow-sm flex flex-col gap-1.5 sm:gap-2 flex-shrink-0 w-full max-w-full overflow-hidden">
        
        <!-- Row 1 Container: Flex-wrap on mobile (clean 2 sub-rows), Flex-nowrap on tablet/laptop/desktop -->
        <div class="flex flex-wrap sm:flex-nowrap items-center justify-between gap-1.5 sm:gap-2 w-full max-w-full min-w-0">
            
            <!-- 1. Left: Brand Title & Counter Badge -->
            <div class="order-1 flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-map-location-dot text-xs sm:text-sm"></i>
                </div>
                <div>
                    <h1 class="text-xs sm:text-sm font-bold text-slate-900 leading-tight">Peta Spasial Babel</h1>
                    <div class="hidden xl:flex items-center gap-1 text-[10px] text-slate-500">
                        <span>Radar</span> &bull; <span>Heatmap</span> &bull; <span>Rute</span>
                    </div>
                </div>
                <span id="counterBadge" class="px-1.5 sm:px-2 py-0.5 rounded-lg bg-slate-900 text-white text-[10px] font-bold">
                    <?= count($places) ?> <span class="hidden sm:inline">Titik</span>
                </span>
            </div>

            <!-- 2. Action Buttons & Tools: Order-2 on mobile (sits right next to Title), Order-3 on desktop (sits at end) -->
            <div class="order-2 sm:order-3 flex items-center gap-1 sm:gap-1.5 flex-shrink-0">
                
                <!-- Mode Switcher: Pin vs Heatmap -->
                <div class="inline-flex rounded-xl bg-slate-100 p-0.5 border border-slate-200">
                    <button id="btnModePin" onclick="setMapMode('pin')" class="px-2 sm:px-2.5 py-1 rounded-lg font-bold bg-white text-slate-900 shadow-sm flex items-center gap-1 text-[11px] transition-all" title="Mode Pin Marker">
                        <i class="fa-solid fa-location-dot text-ocean-600 text-[10px]"></i>
                        <span class="hidden md:inline">Pin</span>
                    </button>
                    <button id="btnModeHeat" onclick="setMapMode('heat')" class="px-2 sm:px-2.5 py-1 rounded-lg font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1 text-[11px] transition-all" title="Mode Heatmap Multi-Kategori">
                        <i class="fa-solid fa-fire text-amber-500 text-[10px]"></i>
                        <span class="hidden md:inline">Heatmap</span>
                    </button>
                </div>

                <!-- Tombol Smart Itinerary Planner (Rute) -->
                <button onclick="toggleItineraryDrawer()" class="px-2 sm:px-2.5 py-1 rounded-xl bg-gradient-to-r from-ocean-600 to-emerald-600 hover:from-ocean-700 hover:to-emerald-700 text-white font-bold text-xs shadow-sm flex items-center gap-1 transition-all" title="Smart Itinerary Planner (TSP)">
                    <i class="fa-solid fa-route text-[11px]"></i>
                    <span class="hidden md:inline">Rute</span>
                </button>

                <!-- Tombol Aksesibilitas Hub & Catchment Area -->
                <button id="btnCatchmentToggle" onclick="toggleCatchmentDrawer()" class="px-2 sm:px-2.5 py-1 rounded-xl bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 font-bold text-xs shadow-sm flex items-center gap-1 transition-all" title="Analisis Aksesibilitas Simpul Transportasi">
                    <i class="fa-solid fa-plane-departure text-purple-600 text-[11px]"></i>
                    <span class="hidden md:inline">Hub</span>
                </button>

                <!-- Tombol Panduan Peta -->
                <button onclick="openGuideModal('peta')" class="p-1 sm:px-2 sm:py-1 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1 transition-all" title="Panduan Penggunaan Peta WebGIS">
                    <i class="fa-solid fa-circle-question text-ocean-600 text-xs"></i>
                </button>

                <!-- Ekspor Data Terbuka (Open Data Satu Data) -->
                <div class="relative group">
                    <button class="p-1 sm:px-2 sm:py-1 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-bold text-xs flex items-center gap-1 transition-all" title="Unduh Data Terbuka">
                        <i class="fa-solid fa-cloud-arrow-down text-ocean-600 text-[11px]"></i>
                        <span class="hidden lg:inline">Ekspor</span>
                    </button>
                    <div class="hidden group-hover:block absolute right-0 top-full mt-1 w-44 bg-white rounded-2xl shadow-xl border border-slate-200 p-1.5 z-50">
                        <a href="api/export.php?format=geojson" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-ocean-50 hover:text-ocean-700 transition-all">
                            <i class="fa-solid fa-code text-ocean-600"></i>
                            <div>
                                <div>Format GeoJSON</div>
                                <div class="text-[10px] text-slate-400 font-normal">Untuk QGIS / ArcGIS</div>
                            </div>
                        </a>
                        <a href="api/export.php?format=csv" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-all">
                            <i class="fa-solid fa-file-csv text-emerald-600"></i>
                            <div>
                                <div>Format CSV / Excel</div>
                                <div class="text-[10px] text-slate-400 font-normal">Tabel Data Spasial</div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

            <!-- 3. Search Bar & Filter Kabupaten: Order-3 w-full on mobile, Order-2 flex-grow on desktop -->
            <div class="order-3 sm:order-2 flex items-center gap-1.5 w-full sm:w-auto sm:flex-grow min-w-0 sm:justify-end">
                
                <!-- Pencarian Cepat -->
                <div class="relative flex-1 min-w-0 sm:w-36 md:w-44 lg:w-48">
                    <i class="fa-solid fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[11px]"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama tempat..." 
                        class="w-full pl-7 pr-2.5 py-1 bg-slate-100 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-ocean-500 focus:bg-white text-xs">
                </div>

                <!-- Filter Kabupaten -->
                <select id="filterKabupaten" class="px-2 py-1 bg-slate-100 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-ocean-500 text-xs font-medium text-slate-700 w-28 sm:w-32 md:w-36 lg:w-40 flex-shrink-0 truncate">
                    <option value="">Semua Wilayah</option>
                    <?php foreach ($kabList as $k): ?>
                    <option value="<?= htmlspecialchars($k) ?>"><?= htmlspecialchars(str_replace(['Kabupaten ', 'Kota '], '', $k)) ?></option>
                    <?php endforeach; ?>
                </select>

            </div>

        </div>

        <!-- Row 2: Floating Category Filter Pills (In Flow - Safely Wrapped) -->
        <div class="w-full max-w-full min-w-0 overflow-x-auto no-scrollbar touch-scroll py-0.5">
            <div class="flex items-center gap-1 sm:gap-1.5 flex-nowrap w-max">
                <button onclick="setCategory('all')" id="pill-all" class="cat-pill px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-900 text-white shadow-sm transition-all whitespace-nowrap flex-shrink-0">
                    🌟 Semua
                </button>
                <button onclick="setCategory('Alam')" id="pill-Alam" class="cat-pill px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:text-slate-900 transition-all whitespace-nowrap flex-shrink-0">
                    🏖️ Wisata Alam
                </button>
                <button onclick="setCategory('Buatan')" id="pill-Buatan" class="cat-pill px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:text-slate-900 transition-all whitespace-nowrap flex-shrink-0">
                    🎡 Wisata Buatan
                </button>
                <button onclick="setCategory('Kebudayaan')" id="pill-Kebudayaan" class="cat-pill px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:text-slate-900 transition-all whitespace-nowrap flex-shrink-0">
                    🏛️ Budaya &amp; Sejarah
                </button>
                <button onclick="setCategory('Hotel')" id="pill-Hotel" class="cat-pill px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:text-slate-900 transition-all whitespace-nowrap flex-shrink-0">
                    🏨 Hotel &amp; Homestay
                </button>
                <button onclick="setCategory('Restoran')" id="pill-Restoran" class="cat-pill px-2.5 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:text-slate-900 transition-all whitespace-nowrap flex-shrink-0">
                    🍽️ Restoran &amp; Kuliner
                </button>
            </div>
        </div>

    </div>

    <!-- Container Peta Penuh -->
    <div id="map" class="flex-grow w-full z-0 bg-slate-100"></div>

    <!-- Floating Legend Heatmap Kategori (Hanya tampil saat mode Heatmap aktif) -->
    <div id="heatmapLegend" class="hidden absolute bottom-6 left-3 sm:left-6 z-30 bg-white/95 backdrop-blur-md px-3.5 py-2.5 rounded-2xl border border-slate-200/80 shadow-xl flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 text-xs pointer-events-auto">
        <span class="font-black text-slate-800 text-[11px] flex items-center gap-1.5">
            <i class="fa-solid fa-fire text-amber-500"></i>
            <span>Heatmap Kategori:</span>
        </span>
        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-[10px] font-bold">
            <span class="flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 border border-white shadow-sm inline-block"></span> Alam (Hijau)</span>
            <span class="flex items-center gap-1 text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 border border-white shadow-sm inline-block"></span> Buatan (Kuning)</span>
            <span class="flex items-center gap-1 text-purple-700 bg-purple-50 px-2 py-0.5 rounded-lg border border-purple-200"><span class="w-2.5 h-2.5 rounded-full bg-purple-500 border border-white shadow-sm inline-block"></span> Budaya (Ungu)</span>
            <span class="flex items-center gap-1 text-sky-700 bg-sky-50 px-2 py-0.5 rounded-lg border border-sky-200"><span class="w-2.5 h-2.5 rounded-full bg-sky-500 border border-white shadow-sm inline-block"></span> Hotel (Biru)</span>
            <span class="flex items-center gap-1 text-rose-700 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-200"><span class="w-2.5 h-2.5 rounded-full bg-rose-500 border border-white shadow-sm inline-block"></span> Resto (Merah)</span>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- SLIDE-OVER DRAWER: SMART ITINERARY PLANNER (OPTIMASI RUTE)     -->
    <!-- ============================================================== -->
    <div id="itineraryDrawer" class="hidden absolute inset-x-0 bottom-0 sm:bottom-0 sm:top-0 sm:left-auto sm:right-0 z-[60] w-full sm:w-[420px] max-w-full sm:max-w-[420px] max-h-[85vh] sm:max-h-full rounded-t-3xl sm:rounded-none bg-white/98 sm:bg-white/95 backdrop-blur-md border-t sm:border-t-0 sm:border-l border-slate-200 shadow-2xl flex flex-col transition-all min-w-0 overflow-hidden">
        
        <!-- Header Drawer -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-900 to-ocean-950 text-white shrink-0">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-ocean-500/20 text-ocean-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-route text-sm"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="font-black text-sm truncate">Smart Itinerary Planner</h3>
                    <p class="text-[11px] text-slate-400 truncate">Optimasi Rute Efisien (Nearest Neighbor)</p>
                </div>
            </div>
            <button onclick="toggleItineraryDrawer()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white text-xs shrink-0 ml-2">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body Drawer -->
        <div class="p-4 sm:p-5 flex-grow overflow-y-auto overflow-x-hidden space-y-4 text-xs w-full max-w-full min-w-0">
            
            <!-- Form Tambah Destinasi (Pencarian Cepat Bisa Diketik + Opsi Manual) -->
            <div class="bg-slate-50 p-3.5 sm:p-4 rounded-2xl border border-slate-200 w-full max-w-full min-w-0 space-y-2.5">
                <div class="flex items-center justify-between">
                    <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px] truncate">
                        Tambahkan Tempat ke Rute:
                    </label>
                    <span class="text-[10px] font-bold text-ocean-600 bg-ocean-50 border border-ocean-200 px-2 py-0.5 rounded-full flex items-center gap-1">
                        <i class="fa-solid fa-magnifying-glass text-[9px]"></i> Cari Cepat
                    </span>
                </div>

                <!-- Input Pencarian Auto-complete (Bisa Diketik) -->
                <div class="relative w-full min-w-0" id="searchDestinasiContainer">
                    <div class="relative flex items-center">
                        <span class="absolute left-3 text-slate-400 pointer-events-none text-xs">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input 
                            type="text" 
                            id="inputCariDestinasi" 
                            oninput="onSearchDestinasiInput(this.value)"
                            onfocus="onSearchDestinasiFocus()"
                            placeholder="Ketik nama tempat, pantai, hotel, resto..." 
                            autocomplete="off"
                            class="w-full pl-8 pr-8 py-2.5 bg-white border border-slate-200 focus:border-ocean-500 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-ocean-500/20 shadow-sm transition-all"
                        >
                        <button 
                            type="button" 
                            id="btnClearDestinasiSearch" 
                            onclick="clearDestinasiSearchInput()" 
                            class="hidden absolute right-2 text-slate-400 hover:text-slate-600 p-1 text-xs"
                            title="Hapus pencarian"
                        >
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </div>

                    <!-- Dropdown Hasil Pencarian Auto-suggest -->
                    <div 
                        id="dropdownCariDestinasi" 
                        class="hidden absolute left-0 right-0 top-full mt-1.5 z-50 bg-white border border-slate-200 rounded-2xl shadow-2xl max-h-64 overflow-y-auto divide-y divide-slate-100 text-xs w-full min-w-0"
                    >
                        <!-- Hasil pencarian diisi JS -->
                    </div>
                </div>

                <!-- Pilihan Alternatif: Dropdown Manual per Kategori (Collapsible) -->
                <div>
                    <button 
                        type="button" 
                        onclick="toggleManualDropdown()" 
                        class="text-[11px] font-semibold text-slate-500 hover:text-ocean-600 flex items-center gap-1 transition-colors"
                    >
                        <i id="manualToggleIcon" class="fa-solid fa-chevron-right text-[9px]"></i>
                        <span>Atau pilih manual dari daftar kategori</span>
                    </button>

                    <div id="manualDropdownWrapper" class="hidden mt-2 flex items-center gap-2 w-full max-w-full min-w-0">
                        <select id="selectDestinasi" class="flex-1 min-w-0 w-0 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-ocean-500 truncate text-slate-700 font-medium">
                            <option value="">-- Pilih Tempat Wisata / Hotel --</option>
                            <?php 
                            $groupedPlaces = [];
                            foreach ($places as $p) {
                                $groupedPlaces[$p['sub_kategori']][] = $p;
                            }
                            foreach ($groupedPlaces as $kat => $items): 
                            ?>
                            <optgroup label="📍 <?= htmlspecialchars($kat) ?> (<?= count($items) ?> Tempat)">
                                <?php foreach ($items as $p): 
                                    $shortName = mb_strimwidth($p['nama'], 0, 36, '...');
                                ?>
                                <option value="<?= $p['id'] ?>" data-nama="<?= htmlspecialchars($p['nama']) ?>" data-lat="<?= $p['latitude'] ?>" data-lng="<?= $p['longitude'] ?>" data-sub="<?= $p['sub_kategori'] ?>" data-kec="<?= htmlspecialchars($p['kecamatan']) ?>" title="<?= htmlspecialchars($p['nama']) ?>">
                                    <?= htmlspecialchars($shortName) ?> &bull; <?= htmlspecialchars($p['kecamatan']) ?>
                                </option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" onclick="addSelectedToTrip()" class="px-3 py-2 bg-ocean-600 hover:bg-ocean-700 text-white font-bold rounded-xl shadow-sm shrink-0 flex items-center justify-center gap-1 transition-all" title="Tambahkan ke daftar kunjungan">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span class="text-xs hidden sm:inline">Tambah</span>
                        </button>
                    </div>
                </div>

                <p class="text-[10px] text-slate-400 pt-0.5 flex items-center gap-1 truncate">
                    <i class="fa-solid fa-circle-info text-ocean-500 shrink-0"></i>
                    <span class="truncate">Bisa juga klik <strong>"+ Tambah ke Rute"</strong> pada pin di peta.</span>
                </p>
            </div>

            <!-- Daftar Tempat Terpilih (Trip Bucket) -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-slate-700 uppercase tracking-wider text-[11px]">Daftar Tempat Kunjungan:</span>
                    <span id="tripCountBadge" class="text-[11px] font-bold text-ocean-600">0 Tempat</span>
                </div>
                <div id="tripList" class="space-y-2 min-h-[80px]">
                    <div class="p-4 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl">
                        Belum ada tempat dipilih. Pilih dari menu di atas atau klik tombol "Rencanakan" di peta.
                    </div>
                </div>
            </div>

            <!-- Tombol Hitung Optimasi Rute -->
            <button id="btnOptimizeRoute" onclick="solveOptimalRoute()" class="w-full py-3 rounded-2xl bg-gradient-to-r from-ocean-600 to-emerald-600 hover:from-ocean-700 hover:to-emerald-700 text-white font-extrabold text-xs shadow-md shadow-ocean-500/20 flex items-center justify-center gap-2 transition-all">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Hitung Urutan Rute Paling Hemat Jarak</span>
            </button>

            <!-- Hasil Rute Optimal -->
            <div id="routeResultCard" class="hidden bg-slate-900 text-white p-4 rounded-2xl space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-white/10">
                    <span class="font-bold text-emerald-400 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Rute Berhasil Dioptimasi!
                    </span>
                    <span id="totalDistanceLabel" class="font-black text-xs text-white">0.0 km</span>
                </div>

                <div id="itinerarySteps" class="space-y-2 text-[11px]">
                    <!-- Step-by-step diisi JS -->
                </div>

                <!-- Buka Google Maps Navigation -->
                <a id="gmapsRouteBtn" href="#" target="_blank" class="block w-full py-2.5 text-center rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all">
                    <i class="fa-solid fa-diamond-turn-right text-ocean-400 mr-1.5"></i>
                    <span>Buka Rute Lengkap di Google Maps</span>
                </a>
            </div>

        </div>

        <!-- Footer Drawer -->
        <div class="p-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
            <button onclick="clearTrip()" class="text-rose-600 hover:underline font-bold text-[11px]">
                <i class="fa-solid fa-trash-can mr-1"></i> Kosongkan
            </button>
            <span class="text-[10px] text-slate-400 italic">Algoritma: TSP Nearest-Neighbor</span>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- SLIDE-OVER DRAWER: ANALISIS AKSESIBILITAS & CATCHMENT BUFFER   -->
    <!-- ============================================================== -->
    <div id="catchmentDrawer" class="hidden absolute inset-x-0 bottom-0 sm:bottom-0 sm:top-0 sm:right-auto sm:left-0 z-[60] w-full sm:w-[420px] max-w-full sm:max-w-[420px] max-h-[85vh] sm:max-h-full rounded-t-3xl sm:rounded-none bg-white/98 sm:bg-white/95 backdrop-blur-md border-t sm:border-t-0 sm:border-r border-slate-200 shadow-2xl flex flex-col transition-all min-w-0 overflow-hidden">
        
        <!-- Header Drawer -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-purple-900 to-indigo-950 text-white shrink-0">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-purple-500/20 text-purple-300 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-compass-drafting text-sm"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="font-black text-sm truncate">Catchment &amp; Aksesibilitas</h3>
                    <p class="text-[11px] text-purple-200 truncate">Simpul Transportasi (Bandara &amp; Pelabuhan)</p>
                </div>
            </div>
            <button onclick="toggleCatchmentDrawer()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white text-xs shrink-0 ml-2">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Body Drawer -->
        <div class="p-4 sm:p-5 flex-grow overflow-y-auto overflow-x-hidden space-y-4 text-xs w-full max-w-full min-w-0">
            
            <!-- Kontrol Simpul Transportasi -->
            <div class="bg-slate-50 p-3.5 sm:p-4 rounded-2xl border border-slate-200 space-y-3 w-full max-w-full min-w-0 overflow-hidden">
                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px] truncate">
                    Pilih Simpul Transportasi:
                </label>
                <select id="selectHubFilter" onchange="onHubFilterChange()" class="w-full max-w-full min-w-0 truncate px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="all">🌐 Semua Simpul (2 Bandara + 4 Pelabuhan)</option>
                    <optgroup label="✈️ Bandara (Pintu Gerbang Udara)">
                        <?php foreach ($hubs as $h): if ($h['tipe'] === 'Bandara'): ?>
                        <option value="<?= $h['id'] ?>">Bandara <?= htmlspecialchars($h['nama']) ?> (<?= $h['kode'] ?>)</option>
                        <?php endif; endforeach; ?>
                    </optgroup>
                    <optgroup label="⚓ Pelabuhan (Pintu Gerbang Laut & Feri)">
                        <?php foreach ($hubs as $h): if ($h['tipe'] === 'Pelabuhan'): ?>
                        <option value="<?= $h['id'] ?>">Pelabuhan <?= htmlspecialchars($h['nama']) ?> (<?= $h['kode'] ?>)</option>
                        <?php endif; endforeach; ?>
                    </optgroup>
                </select>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none text-slate-700 font-semibold text-xs">
                        <input type="checkbox" id="toggleCatchmentCircles" checked onchange="toggleCatchmentRings(this.checked)" class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 w-4 h-4">
                        <span>Tampilkan Buffer Cincin (15, 30, 50 km)</span>
                    </label>
                </div>
            </div>

            <!-- Ringkasan Statistik 4 Zona Kemenparekraf -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <span class="font-black text-slate-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                        <i class="fa-solid fa-chart-pie text-purple-600"></i> Distribusi Keterjangkauan (875 Titik)
                    </span>
                    <span class="text-[10px] text-slate-400 font-semibold">Standar Kemenparekraf</span>
                </div>

                <!-- Ring 1 -->
                <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80">
                    <div class="flex items-center justify-between font-bold text-emerald-900 text-xs mb-1">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                            Ring 1: Inti Urban (≤15 km)
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-200/80 text-emerald-800 text-[10px]">
                            <?= count($catchmentStats['zones']['ring_1']['pois']) ?> Titik (<?= round(count($catchmentStats['zones']['ring_1']['pois']) / $catchmentStats['total_pois'] * 100, 1) ?>%)
                        </span>
                    </div>
                    <div class="text-[11px] text-emerald-700 flex items-center justify-between">
                        <span>Aksesibilitas Sangat Tinggi (≤20 mnt)</span>
                        <span class="text-[10px] text-emerald-600 font-medium">
                            <?= $catchmentStats['zones']['ring_1']['daya_tarik'] ?> Wisata &bull; <?= $catchmentStats['zones']['ring_1']['hotel'] ?> Hotel
                        </span>
                    </div>
                </div>

                <!-- Ring 2 -->
                <div class="p-2.5 rounded-xl bg-blue-50/70 border border-blue-200/80">
                    <div class="flex items-center justify-between font-bold text-blue-900 text-xs mb-1">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
                            Ring 2: Koridor Menengah (15.1 - 30 km)
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-blue-200/80 text-blue-800 text-[10px]">
                            <?= count($catchmentStats['zones']['ring_2']['pois']) ?> Titik (<?= round(count($catchmentStats['zones']['ring_2']['pois']) / $catchmentStats['total_pois'] * 100, 1) ?>%)
                        </span>
                    </div>
                    <div class="text-[11px] text-blue-700 flex items-center justify-between">
                        <span>Aksesibilitas Baik (20 - 45 mnt)</span>
                        <span class="text-[10px] text-blue-600 font-medium">
                            <?= $catchmentStats['zones']['ring_2']['daya_tarik'] ?> Wisata &bull; <?= $catchmentStats['zones']['ring_2']['hotel'] ?> Hotel
                        </span>
                    </div>
                </div>

                <!-- Ring 3 -->
                <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-200/80">
                    <div class="flex items-center justify-between font-bold text-amber-900 text-xs mb-1">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                            Ring 3: Terjangkau Harian (30.1 - 50 km)
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-200/80 text-amber-800 text-[10px]">
                            <?= count($catchmentStats['zones']['ring_3']['pois']) ?> Titik (<?= round(count($catchmentStats['zones']['ring_3']['pois']) / $catchmentStats['total_pois'] * 100, 1) ?>%)
                        </span>
                    </div>
                    <div class="text-[11px] text-amber-700 flex items-center justify-between">
                        <span>Aksesibilitas Sedang (45 - 75 mnt)</span>
                        <span class="text-[10px] text-amber-600 font-medium">
                            <?= $catchmentStats['zones']['ring_3']['daya_tarik'] ?> Wisata &bull; <?= $catchmentStats['zones']['ring_3']['hotel'] ?> Hotel
                        </span>
                    </div>
                </div>

                <!-- Ring 4 -->
                <div class="p-2.5 rounded-xl bg-rose-50/70 border border-rose-200/80">
                    <div class="flex items-center justify-between font-bold text-rose-900 text-xs mb-1">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                            Ring 4: Blank Spot / Terpencil (>50 km)
                        </span>
                        <span class="px-2 py-0.5 rounded-full bg-rose-200/80 text-rose-800 text-[10px]">
                            <?= count($catchmentStats['zones']['ring_4']['pois']) ?> Titik (<?= round(count($catchmentStats['zones']['ring_4']['pois']) / $catchmentStats['total_pois'] * 100, 1) ?>%)
                        </span>
                    </div>
                    <div class="text-[11px] text-rose-700 flex items-center justify-between">
                        <span>Kawasan Terisolir (>75 mnt dari Hub)</span>
                        <span class="text-[10px] text-rose-600 font-medium">
                            <?= $catchmentStats['zones']['ring_4']['daya_tarik'] ?> Wisata &bull; <?= $catchmentStats['zones']['ring_4']['hotel'] ?> Hotel
                        </span>
                    </div>
                </div>

                <!-- Visual Stacked Bar -->
                <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden flex shadow-inner">
                    <div class="bg-emerald-500 h-full" style="width: <?= round(count($catchmentStats['zones']['ring_1']['pois']) / $catchmentStats['total_pois'] * 100, 1) ?>%" title="Ring 1"></div>
                    <div class="bg-blue-500 h-full" style="width: <?= round(count($catchmentStats['zones']['ring_2']['pois']) / $catchmentStats['total_pois'] * 100, 1) ?>%" title="Ring 2"></div>
                    <div class="bg-amber-500 h-full" style="width: <?= round(count($catchmentStats['zones']['ring_3']['pois']) / $catchmentStats['total_pois'] * 100, 1) ?>%" title="Ring 3"></div>
                    <div class="bg-rose-500 h-full" style="width: <?= round(count($catchmentStats['zones']['ring_4']['pois']) / $catchmentStats['total_pois'] * 100, 1) ?>%" title="Ring 4"></div>
                </div>
            </div>

            <!-- Rekomendasi Destinasi Blank Spot Unggulan -->
            <div class="bg-slate-900 text-white p-4 rounded-2xl space-y-3">
                <div class="border-b border-white/10 pb-2">
                    <span class="font-bold text-rose-400 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation"></i> Destinasi Unggulan Terisolir (Ring 4)
                    </span>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        Destinasi rating tinggi (≥4.0) yang jauh dari bandara (>50 km), prioritas rute shuttle/feeder:
                    </p>
                </div>

                <div class="space-y-2">
                    <?php foreach ($catchmentStats['blind_spots'] as $bs): ?>
                    <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 hover:border-purple-400/50 transition-all flex items-center justify-between">
                        <div class="space-y-0.5 pr-2">
                            <div class="font-bold text-white text-xs leading-snug"><?= htmlspecialchars($bs['nama']) ?></div>
                            <div class="text-[10px] text-slate-400 flex items-center gap-2">
                                <span><?= htmlspecialchars($bs['kabupaten']) ?></span>
                                <span>&bull;</span>
                                <span class="text-amber-400 font-semibold"><i class="fa-solid fa-star text-[9px]"></i> <?= $bs['rating'] ?></span>
                            </div>
                            <div class="text-[10px] text-rose-300">
                                ✈️ <?= round($bs['dist_to_airport'], 1) ?> km ke <?= htmlspecialchars($bs['airport_name']) ?> (~<?= $bs['est_minutes'] ?> mnt)
                            </div>
                        </div>
                        <button onclick="focusOnPlace(<?= $bs['latitude'] ?>, <?= $bs['longitude'] ?>, <?= $bs['id'] ?>)" 
                            class="px-2.5 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-[10px] font-bold whitespace-nowrap shadow-sm transition-all flex items-center gap-1">
                            <i class="fa-solid fa-location-crosshairs"></i> Fokus
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- Footer Drawer -->
        <div class="p-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
            <span class="text-[10px] text-slate-400 italic">Metode: Isochrone Buffer (~45 km/jam)</span>
            <button onclick="map.flyTo([-2.5, 106.8], 9, { duration: 1.0 })" class="text-purple-600 hover:underline font-bold text-[11px] flex items-center gap-1">
                <i class="fa-solid fa-expand"></i> Reset Tampilan
            </button>
        </div>

    </div>

    <!-- Floating Haversine Nearby Panel (Modal Samping) -->
    <div id="haversinePanel" class="hidden absolute inset-x-3 sm:inset-x-auto top-14 sm:top-16 sm:right-6 z-[60] w-auto sm:w-96 bg-white/95 backdrop-blur-md p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-2xl transition-all">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
            <div class="flex items-center gap-2 text-ocean-700 font-bold text-xs">
                <i class="fa-solid fa-radar text-sm animate-spin" style="animation-duration: 4s;"></i>
                <span>Radar Amenitas Terdekat (Haversine)</span>
            </div>
            <button onclick="closeHaversinePanel()" class="w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <div id="haversineContent" class="space-y-3 text-xs max-h-[65vh] overflow-y-auto pr-1">
            <!-- Konten dinamis diisi oleh JS -->
        </div>
    </div>

</main>

<script>
const rawPlaces = <?= json_encode($places) ?>;

// Inisialisasi Peta Leaflet
const map = L.map('map', {
    zoomControl: false
}).setView([-2.5, 106.8], 9);

L.control.zoom({ position: 'bottomright' }).addTo(map);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | Data Pariwisata Babel',
    maxZoom: 19
}).addTo(map);

function getMarkerColor(sub) {
    switch (sub) {
        case 'Alam': return '#10b981';
        case 'Buatan': return '#f59e0b';
        case 'Kebudayaan': return '#8b5cf6';
        case 'Hotel': return '#0ea5e9';
        case 'Restoran': return '#ef4444';
        default: return '#64748b';
    }
}

function createCustomIcon(sub) {
    const color = getMarkerColor(sub);
    let iconClass = 'fa-map-pin';
    if (sub === 'Alam') iconClass = 'fa-umbrella-beach';
    else if (sub === 'Buatan') iconClass = 'fa-camera';
    else if (sub === 'Kebudayaan') iconClass = 'fa-monument';
    else if (sub === 'Hotel') iconClass = 'fa-hotel';
    else if (sub === 'Restoran') iconClass = 'fa-utensils';

    return L.divIcon({
        className: 'custom-pin',
        html: `
            <div style="background-color: ${color}; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.25); border: 2px solid white;">
                <i class="fa-solid ${iconClass}" style="font-size: 12px;"></i>
            </div>
        `,
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        popupAnchor: [0, -14]
    });
}

const markersLayer = L.layerGroup().addTo(map);
const hubMarkersLayer = L.layerGroup().addTo(map);
const catchmentRingsLayer = L.layerGroup().addTo(map);
const transportHubs = <?= json_encode($hubs) ?>;
let catchmentRingsVisible = true;
let heatLayers = [];

function clearHeatLayers() {
    if (heatLayers && heatLayers.length > 0) {
        heatLayers.forEach(l => {
            if (map.hasLayer(l)) {
                map.removeLayer(l);
            }
        });
        heatLayers = [];
    }
}
let currentMode = 'pin';
let currentCategory = 'all';
let radarCircle = null;
let routePolyline = null;
let routeMarkers = [];

// ==============================================================
// ALGORITMA HAVERSINE CLIENT-SIDE
// ==============================================================
function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius bumi km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
        Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return (R * c);
}

// ==============================================================
// MODE SWITCHER & KATEGORI FILTER
// ==============================================================
function setCategory(cat) {
    currentCategory = cat;
    
    // Update visual pills
    document.querySelectorAll('.cat-pill').forEach(el => {
        el.className = 'cat-pill px-3 py-1 rounded-xl text-xs font-semibold bg-slate-100 text-slate-600 hover:text-slate-900 transition-all whitespace-nowrap';
    });
    const activePill = document.getElementById('pill-' + cat);
    if (activePill) {
        activePill.className = 'cat-pill px-3 py-1 rounded-xl text-xs font-bold bg-slate-900 text-white shadow-sm transition-all whitespace-nowrap';
    }

    if (currentMode === 'pin') {
        renderMarkers();
    } else {
        renderHeatmap();
    }
}

function setMapMode(mode) {
    currentMode = mode;
    const btnPin = document.getElementById('btnModePin');
    const btnHeat = document.getElementById('btnModeHeat');
    const legend = document.getElementById('heatmapLegend');

    if (mode === 'pin') {
        btnPin.className = 'px-2.5 py-1 rounded-lg font-bold bg-white text-slate-900 shadow-sm flex items-center gap-1.5 transition-all';
        btnHeat.className = 'px-2.5 py-1 rounded-lg font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition-all';
        clearHeatLayers();
        map.addLayer(markersLayer);
        if (legend) legend.classList.add('hidden');
        renderMarkers();
    } else {
        btnHeat.className = 'px-2.5 py-1 rounded-lg font-bold bg-slate-900 text-white shadow-sm flex items-center gap-1.5 transition-all';
        btnPin.className = 'px-2.5 py-1 rounded-lg font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5 transition-all';
        map.removeLayer(markersLayer);
        if (legend) legend.classList.remove('hidden');
        renderHeatmap();
    }
}

function renderHeatmap() {
    clearHeatLayers();

    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const kabFilter = document.getElementById('filterKabupaten').value;

    // Palet warna dasar gonjreng, tegas & kontras untuk tiap kategori
    // Membentuk heat cloud L.heatLayer yang estetik, tidak meluber/blepotan ke laut
    const categoryConfig = {
        'Alam': {
            // Hijau Zamrud Tropis (Daya Tarik Alam)
            gradient: { 0.2: '#86efac', 0.5: '#22c55e', 0.8: '#16a34a', 1.0: '#14532d' }
        },
        'Buatan': {
            // Kuning Emas / Amber (Daya Tarik Buatan)
            gradient: { 0.2: '#fef08a', 0.5: '#eab308', 0.8: '#ca8a04', 1.0: '#713f12' }
        },
        'Kebudayaan': {
            // Ungu Violet Bersejarah (Daya Tarik Kebudayaan)
            gradient: { 0.2: '#e9d5ff', 0.5: '#a855f7', 0.8: '#7e22ce', 1.0: '#3b0764' }
        },
        'Hotel': {
            // Biru Samudra Cerah (Amenitas Hotel & Penginapan)
            gradient: { 0.2: '#7dd3fc', 0.5: '#0ea5e9', 0.8: '#0284c7', 1.0: '#0c4a6e' }
        },
        'Restoran': {
            // Merah Crimson Menyala (Amenitas Restoran & Kuliner)
            gradient: { 0.2: '#fda4af', 0.5: '#f43f5e', 0.8: '#e11d48', 1.0: '#881337' }
        }
    };

    const categoryPoints = {
        'Alam': [],
        'Buatan': [],
        'Kebudayaan': [],
        'Hotel': [],
        'Restoran': []
    };

    let matched = 0;

    rawPlaces.forEach(p => {
        if (p.latitude && p.longitude) {
            const matchCat = (currentCategory === 'all') || (p.sub_kategori === currentCategory);
            const matchKab = (!kabFilter) || (p.kabupaten === kabFilter);
            const matchQuery = (!query) || (p.nama.toLowerCase().includes(query)) || (p.kecamatan.toLowerCase().includes(query));

            if (matchCat && matchKab && matchQuery) {
                matched++;
                // Intensitas logaritmik ulasan (0.45 - 1.0)
                const intensity = Math.min(1.0, Math.max(0.45, 0.45 + Math.log10((p.ulasan || 0) + 1) * 0.16));
                const targetCat = categoryPoints[p.sub_kategori] ? p.sub_kategori : 'Alam';
                categoryPoints[targetCat].push([p.latitude, p.longitude, intensity]);
            }
        }
    });

    // Render masing-masing heat layer per kategori
    Object.keys(categoryPoints).forEach(cat => {
        const points = categoryPoints[cat];
        if (points.length > 0 && categoryConfig[cat]) {
            const layer = L.heatLayer(points, {
                radius: 17,
                blur: 11,
                maxZoom: 13,
                max: 1.0,
                minOpacity: 0.35,
                gradient: categoryConfig[cat].gradient
            }).addTo(map);
            heatLayers.push(layer);
        }
    });

    const badge = document.getElementById('counterBadge');
    if (badge) badge.innerText = `${matched} Titik (Heatmap)`;
}

// ==============================================================
// RADAR HAVERSINE BUFFER 5 KM
// ==============================================================
function findNearbyAmenities(originId) {
    const origin = rawPlaces.find(p => p.id === originId);
    if (!origin) return;

    if (radarCircle) map.removeLayer(radarCircle);

    radarCircle = L.circle([origin.latitude, origin.longitude], {
        radius: 5000,
        color: '#0284c7',
        fillColor: '#38bdf8',
        fillOpacity: 0.15,
        weight: 2,
        dashArray: '5, 8'
    }).addTo(map);

    map.flyTo([origin.latitude, origin.longitude], 12, { duration: 1.0 });

    const hotels = [];
    const restos = [];

    rawPlaces.forEach(p => {
        if (p.kelompok === 'Amenitas' && p.latitude && p.longitude) {
            const dist = haversineDistance(origin.latitude, origin.longitude, p.latitude, p.longitude);
            if (p.sub_kategori === 'Hotel') hotels.push({ ...p, dist });
            else if (p.sub_kategori === 'Restoran') restos.push({ ...p, dist });
        }
    });

    hotels.sort((a, b) => a.dist - b.dist);
    restos.sort((a, b) => a.dist - b.dist);

    const topHotels = hotels.slice(0, 3);
    const topRestos = restos.slice(0, 3);

    let html = `
        <div class="bg-ocean-50 p-3 rounded-2xl border border-ocean-200 mb-3">
            <span class="text-[10px] font-bold text-ocean-700 uppercase tracking-wider block">Titik Asal Radius 5 KM</span>
            <h4 class="font-black text-slate-900 text-sm">${origin.nama}</h4>
            <p class="text-[11px] text-slate-500">${origin.kecamatan}, ${origin.kabupaten}</p>
        </div>

        <div>
            <span class="font-bold text-ocean-800 flex items-center gap-1 mb-2">
                <i class="fa-solid fa-hotel text-xs"></i> 3 Hotel / Penginapan Terdekat:
            </span>
            <div class="space-y-1.5">
    `;

    if (topHotels.length === 0) {
        html += `<p class="text-slate-400 italic">Tidak ada hotel dalam radius dekat.</p>`;
    } else {
        topHotels.forEach(h => {
            html += `
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-800">${h.nama}</div>
                        <div class="text-[10px] text-slate-400">${h.kecamatan} &bull; ★ ${h.rating || '-'}</div>
                    </div>
                    <span class="px-2 py-1 rounded-lg bg-ocean-100 text-ocean-800 font-extrabold text-[11px] whitespace-nowrap">
                        ${h.dist.toFixed(2)} km
                    </span>
                </div>
            `;
        });
    }

    html += `
            </div>
        </div>

        <div class="pt-2">
            <span class="font-bold text-coral-800 flex items-center gap-1 mb-2">
                <i class="fa-solid fa-utensils text-xs"></i> 3 Restoran / Kuliner Terdekat:
            </span>
            <div class="space-y-1.5">
    `;

    if (topRestos.length === 0) {
        html += `<p class="text-slate-400 italic">Tidak ada restoran dalam radius dekat.</p>`;
    } else {
        topRestos.forEach(r => {
            html += `
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-800">${r.nama}</div>
                        <div class="text-[10px] text-slate-400">${r.kecamatan} &bull; ★ ${r.rating || '-'}</div>
                    </div>
                    <span class="px-2 py-1 rounded-lg bg-coral-100 text-coral-800 font-extrabold text-[11px] whitespace-nowrap">
                        ${r.dist.toFixed(2)} km
                    </span>
                </div>
            `;
        });
    }

    html += `
            </div>
        </div>
        <p class="text-[10px] text-slate-400 pt-2 border-t border-slate-100 italic">
            * Jarak dihitung menggunakan formula trigonometri Haversine.
        </p>
    `;

    document.getElementById('haversineContent').innerHTML = html;
    document.getElementById('haversinePanel').classList.remove('hidden');
}

function closeHaversinePanel() {
    document.getElementById('haversinePanel').classList.add('hidden');
    if (radarCircle) map.removeLayer(radarCircle);
}

// ==============================================================
// SMART ITINERARY PLANNER (OPTIMASI RUTE NEAREST NEIGHBOR)
// ==============================================================
let tripPlaces = [];

function toggleItineraryDrawer() {
    const drawer = document.getElementById('itineraryDrawer');
    drawer.classList.toggle('hidden');
    if (!drawer.classList.contains('hidden')) {
        const cDrawer = document.getElementById('catchmentDrawer');
        if (cDrawer) cDrawer.classList.add('hidden');
    }
}

function getSubkategoriBadge(sub) {
    switch (sub) {
        case 'Alam': return { icon: 'fa-umbrella-beach', bg: 'bg-emerald-500' };
        case 'Buatan': return { icon: 'fa-camera', bg: 'bg-amber-500' };
        case 'Kebudayaan': return { icon: 'fa-monument', bg: 'bg-purple-500' };
        case 'Hotel': return { icon: 'fa-hotel', bg: 'bg-ocean-500' };
        case 'Restoran': return { icon: 'fa-utensils', bg: 'bg-orange-500' };
        default: return { icon: 'fa-map-pin', bg: 'bg-slate-500' };
    }
}

function toggleManualDropdown() {
    const wrap = document.getElementById('manualDropdownWrapper');
    const icon = document.getElementById('manualToggleIcon');
    if (!wrap) return;
    wrap.classList.toggle('hidden');
    if (wrap.classList.contains('hidden')) {
        if (icon) icon.className = 'fa-solid fa-chevron-right text-[9px]';
    } else {
        if (icon) icon.className = 'fa-solid fa-chevron-down text-[9px]';
    }
}

function clearDestinasiSearchInput() {
    const inp = document.getElementById('inputCariDestinasi');
    const btn = document.getElementById('btnClearDestinasiSearch');
    const dropdown = document.getElementById('dropdownCariDestinasi');
    if (inp) inp.value = '';
    if (btn) btn.classList.add('hidden');
    if (dropdown) dropdown.classList.add('hidden');
}

function onSearchDestinasiFocus() {
    const inp = document.getElementById('inputCariDestinasi');
    if (!inp) return;
    onSearchDestinasiInput(inp.value);
}

function onSearchDestinasiInput(keyword) {
    const dropdown = document.getElementById('dropdownCariDestinasi');
    const btnClear = document.getElementById('btnClearDestinasiSearch');
    if (!dropdown) return;

    const q = (keyword || '').trim().toLowerCase();

    if (btnClear) {
        if (q.length > 0) btnClear.classList.remove('hidden');
        else btnClear.classList.add('hidden');
    }

    let filtered = [];
    if (q.length === 0) {
        filtered = rawPlaces.filter(p => parseFloat(p.rating || 0) >= 4.5).slice(0, 15);
        if (filtered.length === 0) filtered = rawPlaces.slice(0, 15);
    } else {
        filtered = rawPlaces.filter(p => {
            const nama = (p.nama || '').toLowerCase();
            const sub = (p.sub_kategori || '').toLowerCase();
            const kec = (p.kecamatan || '').toLowerCase();
            const kab = (p.kabupaten || '').toLowerCase();
            return nama.includes(q) || sub.includes(q) || kec.includes(q) || kab.includes(q);
        }).slice(0, 25);
    }

    if (filtered.length === 0) {
        dropdown.innerHTML = `
            <div class="p-4 text-center text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-slate-300 text-lg mb-1 block"></i>
                <div class="font-bold text-slate-600">Tidak ada hasil cocok</div>
                <div class="text-[10px] text-slate-400 mt-0.5">Coba cari dengan nama pantai, hotel, resto, atau kecamatan lain.</div>
            </div>
        `;
        dropdown.classList.remove('hidden');
        return;
    }

    let html = '';
    if (q.length === 0) {
        html += `
            <div class="p-2 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider flex items-center justify-between border-b border-slate-100">
                <span>⭐ Rekomendasi Terpopuler:</span>
                <span class="text-ocean-600 lowercase font-normal">total 875 tempat</span>
            </div>
        `;
    } else {
        html += `
            <div class="p-2 bg-slate-50 text-slate-600 text-[10px] font-bold flex items-center justify-between border-b border-slate-100">
                <span>Ditemukan ${filtered.length} tempat cocok:</span>
                <span class="text-slate-400 font-normal">Klik untuk langsung tambah</span>
            </div>
        `;
    }

    filtered.forEach(p => {
        const meta = getSubkategoriBadge(p.sub_kategori);
        const isAdded = tripPlaces.some(t => t.id === p.id);

        html += `
            <div onclick="addSearchedPlaceToTrip(${p.id})" class="p-2.5 hover:bg-ocean-50/80 active:bg-ocean-100 flex items-center justify-between gap-2.5 cursor-pointer transition-colors group w-full min-w-0">
                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                    <span class="w-6 h-6 rounded-lg ${meta.bg} text-white flex items-center justify-center flex-shrink-0 text-[10px] shadow-sm">
                        <i class="fa-solid ${meta.icon}"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-slate-800 text-xs truncate group-hover:text-ocean-700" title="${p.nama}">
                            ${p.nama}
                        </div>
                        <div class="text-[10px] text-slate-400 truncate">
                            ${p.sub_kategori} &bull; ${p.kecamatan || '-'} &bull; <span class="text-amber-500 font-semibold"><i class="fa-solid fa-star text-[9px]"></i> ${p.rating || '-'}</span>
                        </div>
                    </div>
                </div>
                ${isAdded ? 
                    `<span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 font-bold text-[10px] flex-shrink-0 flex items-center gap-1">
                        <i class="fa-solid fa-check"></i> Sudah Ada
                    </span>` : 
                    `<button type="button" class="px-2.5 py-1 bg-ocean-600 group-hover:bg-ocean-700 text-white rounded-lg text-[10px] font-bold flex-shrink-0 transition-all flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-plus text-[9px]"></i> Tambah
                    </button>`
                }
            </div>
        `;
    });

    dropdown.innerHTML = html;
    dropdown.classList.remove('hidden');
}

function addSearchedPlaceToTrip(id) {
    addToTripDirect(id);
    clearDestinasiSearchInput();
}

// Event listener klik di luar dropdown untuk menutup autocomplete
document.addEventListener('click', function(e) {
    const container = document.getElementById('searchDestinasiContainer');
    const dropdown = document.getElementById('dropdownCariDestinasi');
    if (container && !container.contains(e.target) && dropdown) {
        dropdown.classList.add('hidden');
    }
});

// Event listener tombol Enter pada input pencarian untuk memilih item pertama
document.addEventListener('DOMContentLoaded', function() {
    const inp = document.getElementById('inputCariDestinasi');
    if (inp) {
        inp.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const dropdown = document.getElementById('dropdownCariDestinasi');
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    const firstItem = dropdown.querySelector('[onclick^="addSearchedPlaceToTrip"]');
                    if (firstItem) firstItem.click();
                }
            }
        });
    }
});

function addSelectedToTrip() {
    const sel = document.getElementById('selectDestinasi');
    if (!sel.value) return;

    const opt = sel.options[sel.selectedIndex];
    const placeId = parseInt(opt.value);

    // Cek apakah sudah ada
    if (tripPlaces.some(p => p.id === placeId)) {
        alert('Tempat ini sudah ada dalam rencana kunjungan Anda.');
        return;
    }

    if (tripPlaces.length >= 8) {
        alert('Maksimal 8 tempat dalam satu paket rencana rute.');
        return;
    }

    tripPlaces.push({
        id: placeId,
        nama: opt.getAttribute('data-nama'),
        lat: parseFloat(opt.getAttribute('data-lat')),
        lng: parseFloat(opt.getAttribute('data-lng')),
        sub: opt.getAttribute('data-sub'),
        kec: opt.getAttribute('data-kec')
    });

    renderTripList();
}

function addToTripDirect(id) {
    const p = rawPlaces.find(x => x.id === id);
    if (!p) return;

    if (tripPlaces.some(t => t.id === id)) {
        alert('Tempat ini sudah ada dalam rencana kunjungan.');
        return;
    }
    if (tripPlaces.length >= 8) {
        alert('Maksimal 8 tempat per rencana rute.');
        return;
    }

    tripPlaces.push({
        id: p.id,
        nama: p.nama,
        lat: parseFloat(p.latitude),
        lng: parseFloat(p.longitude),
        sub: p.sub_kategori,
        kec: p.kecamatan
    });
    renderTripList();
    document.getElementById('itineraryDrawer').classList.remove('hidden');
}

function removeFromTrip(idx) {
    tripPlaces.splice(idx, 1);
    renderTripList();
    clearRouteVisuals();
}

function clearTrip() {
    tripPlaces = [];
    renderTripList();
    clearRouteVisuals();
}

function renderTripList() {
    const container = document.getElementById('tripList');
    document.getElementById('tripCountBadge').innerText = `${tripPlaces.length} Tempat`;

    if (tripPlaces.length === 0) {
        container.innerHTML = `
            <div class="p-4 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl">
                Belum ada tempat dipilih. Pilih dari menu di atas atau klik tombol "+ Tambah ke Rute" pada pin peta.
            </div>
        `;
        document.getElementById('routeResultCard').classList.add('hidden');
        return;
    }

    let html = '';
    tripPlaces.forEach((p, idx) => {
        html += `
            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-2 w-full min-w-0 overflow-hidden">
                <div class="flex items-center gap-2.5 min-w-0 flex-1 overflow-hidden">
                    <span class="w-5 h-5 flex-shrink-0 rounded-full bg-slate-800 text-white font-bold text-[10px] flex items-center justify-center">
                        ${idx + 1}
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-slate-800 text-xs truncate" title="${p.nama}">${p.nama}</div>
                        <div class="text-[10px] text-slate-400 truncate">${p.sub} &bull; ${p.kec}</div>
                    </div>
                </div>
                <button onclick="removeFromTrip(${idx})" class="flex-shrink-0 text-slate-400 hover:text-rose-600 p-1" title="Hapus">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        `;
    });
    container.innerHTML = html;
}

// Algoritma Heuristik: Nearest Neighbor TSP
function solveOptimalRoute() {
    if (tripPlaces.length < 2) {
        alert('Pilih minimal 2 tempat untuk menghitung rute perjalanan.');
        return;
    }

    const unvisited = [...tripPlaces];
    const orderedRoute = [];

    // Mulai dari titik pertama
    let current = unvisited.shift();
    orderedRoute.push(current);

    while (unvisited.length > 0) {
        let nearestIdx = 0;
        let minDist = Infinity;

        for (let i = 0; i < unvisited.length; i++) {
            const d = haversineDistance(current.lat, current.lng, unvisited[i].lat, unvisited[i].lng);
            if (d < minDist) {
                minDist = d;
                nearestIdx = i;
            }
        }

        current = unvisited.splice(nearestIdx, 1)[0];
        orderedRoute.push(current);
    }

    // Visualisasikan Rute di Peta
    drawRouteOnMap(orderedRoute);
}

function clearRouteVisuals() {
    if (routePolyline) map.removeLayer(routePolyline);
    routeMarkers.forEach(m => map.removeLayer(m));
    routeMarkers = [];
    document.getElementById('routeResultCard').classList.add('hidden');
}

function drawRouteOnMap(route) {
    clearRouteVisuals();

    const latlngs = route.map(p => [p.lat, p.lng]);

    // Gambar Polyline Garis Rute (Biru Laut Elegan)
    routePolyline = L.polyline(latlngs, {
        color: '#0284c7',
        weight: 5,
        opacity: 0.85,
        dashArray: '8, 10'
    }).addTo(map);

    // Zoom peta agar memuat seluruh rute
    map.fitBounds(routePolyline.getBounds(), { padding: [50, 50] });

    // Pasang Pin Berurutan (1, 2, 3...)
    let totalDist = 0;
    let stepsHtml = '';
    const gmapsCoords = [];

    route.forEach((p, idx) => {
        gmapsCoords.push(`${p.lat},${p.lng}`);

        const numIcon = L.divIcon({
            className: 'route-num-pin',
            html: `
                <div style="background-color: #0f172a; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 13px; border: 3px solid #38bdf8; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                    ${idx + 1}
                </div>
            `,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        const m = L.marker([p.lat, p.lng], { icon: numIcon }).addTo(map);
        m.bindPopup(`<b>Stop ${idx + 1}</b>: ${p.nama}`);
        routeMarkers.push(m);

        stepsHtml += `
            <div class="flex items-center gap-2 w-full min-w-0">
                <span class="w-5 h-5 flex-shrink-0 rounded-full bg-emerald-500/20 text-emerald-400 font-bold flex items-center justify-center text-[10px]">
                    ${idx + 1}
                </span>
                <span class="font-bold text-white text-xs truncate flex-1 min-w-0" title="${p.nama}">${p.nama}</span>
            </div>
        `;

        if (idx < route.length - 1) {
            const legDist = haversineDistance(p.lat, p.lng, route[idx+1].lat, route[idx+1].lng);
            totalDist += legDist;
            stepsHtml += `
                <div class="pl-7 text-[10px] text-slate-400 py-0.5 flex items-center gap-1.5 truncate">
                    <i class="fa-solid fa-arrow-down text-[9px] text-ocean-400 flex-shrink-0"></i>
                    <span class="truncate">${legDist.toFixed(1)} km (~${Math.round(legDist * 1.8)} mnt berkendara)</span>
                </div>
            `;
        }
    });

    document.getElementById('totalDistanceLabel').innerText = `Total Jarak: ${totalDist.toFixed(1)} km`;
    document.getElementById('itinerarySteps').innerHTML = stepsHtml;

    // Link Navigasi Google Maps Multi-Stop
    const gmapsUrl = `https://www.google.com/maps/dir/${gmapsCoords.join('/')}`;
    document.getElementById('gmapsRouteBtn').href = gmapsUrl;

    document.getElementById('routeResultCard').classList.remove('hidden');
}

// ==============================================================
// SIMPUL TRANSPORTASI & ANALISIS CATCHMENT BUFFER
// ==============================================================
function createHubIcon(tipe) {
    const isAirport = (tipe === 'Bandara');
    const bg = isAirport ? '#7c3aed' : '#0891b2'; // purple vs cyan
    const iconClass = isAirport ? 'fa-plane-departure' : 'fa-ship';

    return L.divIcon({
        className: 'hub-pin',
        html: `
            <div style="background-color: ${bg}; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 14px rgba(0,0,0,0.35); border: 2.5px solid white;">
                <i class="fa-solid ${iconClass}" style="font-size: 14px;"></i>
            </div>
        `,
        iconSize: [34, 34],
        iconAnchor: [17, 17],
        popupAnchor: [0, -17]
    });
}

function renderHubMarkers() {
    hubMarkersLayer.clearLayers();
    transportHubs.forEach(h => {
        const marker = L.marker([h.latitude, h.longitude], {
            icon: createHubIcon(h.tipe),
            zIndexOffset: 1000
        });

        const badgeColor = (h.tipe === 'Bandara') ? 'bg-purple-100 text-purple-800 border-purple-200' : 'bg-cyan-100 text-cyan-800 border-cyan-200';
        const popupContent = `
            <div class="p-1 min-w-[220px]">
                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border ${badgeColor} mb-1.5">
                    ${h.tipe} &bull; ${h.kode}
                </span>
                <h4 class="font-extrabold text-slate-900 text-sm mb-0.5 leading-snug">${h.nama}</h4>
                <p class="text-[11px] text-slate-500 mb-2">${h.kabupaten} (${h.wilayah})</p>
                <div class="text-[11px] text-slate-600 bg-slate-50 p-2 rounded-xl border border-slate-200 mb-2.5 leading-relaxed">
                    ${h.deskripsi}
                </div>
                <button onclick="focusOnHub('${h.id}')" 
                    class="w-full py-1.5 px-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] shadow-sm flex items-center justify-center gap-1.5 transition-all">
                    <i class="fa-solid fa-bullseye text-xs"></i>
                    <span>Fokus Buffer Cincin Hub Ini</span>
                </button>
            </div>
        `;

        marker.bindPopup(popupContent);
        hubMarkersLayer.addLayer(marker);
    });
}

function renderCatchmentRings(targetHubId = 'all') {
    catchmentRingsLayer.clearLayers();
    if (!catchmentRingsVisible) return;

    // Jika 'all', tampilkan buffer 2 bandara utama (PGK & TJQ) agar peta tetap rapi
    const hubsToRender = (targetHubId === 'all')
        ? transportHubs.filter(h => h.tipe === 'Bandara')
        : transportHubs.filter(h => h.id === targetHubId);

    hubsToRender.forEach(h => {
        // Ring 1: 15 km (≤20 menit) - Emerald
        L.circle([h.latitude, h.longitude], {
            radius: 15000,
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.08,
            weight: 2,
            dashArray: '4, 6'
        }).bindTooltip(`<b>Ring 1 (≤15 km)</b><br>${h.nama}<br><span class='text-[10px] text-emerald-600'>Akses Sangat Cepat (≤20 mnt)</span>`, { sticky: true }).addTo(catchmentRingsLayer);

        // Ring 2: 30 km (20-45 menit) - Blue
        L.circle([h.latitude, h.longitude], {
            radius: 30000,
            color: '#3b82f6',
            fillColor: '#3b82f6',
            fillOpacity: 0.05,
            weight: 2,
            dashArray: '5, 8'
        }).bindTooltip(`<b>Ring 2 (15-30 km)</b><br>${h.nama}<br><span class='text-[10px] text-blue-600'>Akses Koridor Menengah (20-45 mnt)</span>`, { sticky: true }).addTo(catchmentRingsLayer);

        // Ring 3: 50 km (45-75 menit) - Amber
        L.circle([h.latitude, h.longitude], {
            radius: 50000,
            color: '#f59e0b',
            fillColor: '#f59e0b',
            fillOpacity: 0.03,
            weight: 2,
            dashArray: '6, 10'
        }).bindTooltip(`<b>Ring 3 (30-50 km)</b><br>${h.nama}<br><span class='text-[10px] text-amber-600'>Batas Perjalanan Siang (45-75 mnt)</span>`, { sticky: true }).addTo(catchmentRingsLayer);
    });
}

function toggleCatchmentDrawer() {
    const drawer = document.getElementById('catchmentDrawer');
    drawer.classList.toggle('hidden');
    if (!drawer.classList.contains('hidden')) {
        const iDrawer = document.getElementById('itineraryDrawer');
        if (iDrawer) iDrawer.classList.add('hidden');
    }
}

function onHubFilterChange() {
    const hubId = document.getElementById('selectHubFilter').value;
    renderCatchmentRings(hubId);
    if (hubId !== 'all') {
        const hub = transportHubs.find(h => h.id === hubId);
        if (hub) {
            map.flyTo([hub.latitude, hub.longitude], 10, { duration: 1.2 });
        }
    } else {
        map.flyTo([-2.5, 106.8], 9, { duration: 1.2 });
    }
}

function toggleCatchmentRings(visible) {
    catchmentRingsVisible = visible;
    const hubId = document.getElementById('selectHubFilter').value;
    renderCatchmentRings(hubId);
}

function focusOnHub(hubId) {
    document.getElementById('selectHubFilter').value = hubId;
    onHubFilterChange();
}

function focusOnPlace(lat, lng, id) {
    map.flyTo([lat, lng], 13, { duration: 1.2 });
    setTimeout(() => {
        markersLayer.eachLayer(m => {
            const pos = m.getLatLng();
            if (Math.abs(pos.lat - lat) < 0.0001 && Math.abs(pos.lng - lng) < 0.0001) {
                m.openPopup();
            }
        });
    }, 1300);
}

// Menghitung simpul terdekat dan zona aksesibilitas untuk suatu titik POI
function getNearestHubClient(lat, lng) {
    let minAirportDist = Infinity;
    let nearestAirport = null;
    let minPortDist = Infinity;
    let nearestPort = null;

    transportHubs.forEach(h => {
        const d = haversineDistance(lat, lng, h.latitude, h.longitude);
        if (h.tipe === 'Bandara') {
            if (d < minAirportDist) {
                minAirportDist = d;
                nearestAirport = { ...h, dist: d, mins: Math.round((d / 45) * 60) };
            }
        } else if (h.tipe === 'Pelabuhan') {
            if (d < minPortDist) {
                minPortDist = d;
                nearestPort = { ...h, dist: d, mins: Math.round((d / 45) * 60) };
            }
        }
    });

    let zoneLabel = 'Ring 4: Blank Spot (>50 km)';
    let zoneBadge = 'bg-rose-100 text-rose-800 border-rose-200';

    if (minAirportDist <= 15) {
        zoneLabel = 'Ring 1: Inti Urban (≤15 km)';
        zoneBadge = 'bg-emerald-100 text-emerald-800 border-emerald-200';
    } else if (minAirportDist <= 30) {
        zoneLabel = 'Ring 2: Koridor (15-30 km)';
        zoneBadge = 'bg-blue-100 text-blue-800 border-blue-200';
    } else if (minAirportDist <= 50) {
        zoneLabel = 'Ring 3: Terjangkau (30-50 km)';
        zoneBadge = 'bg-amber-100 text-amber-800 border-amber-200';
    }

    return {
        airport: nearestAirport,
        port: nearestPort,
        zoneLabel,
        zoneBadge
    };
}

// ==============================================================
// RENDER MARKER STANDAR
// ==============================================================
function renderMarkers() {
    markersLayer.clearLayers();
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const kabFilter = document.getElementById('filterKabupaten').value;

    let matched = 0;

    rawPlaces.forEach(p => {
        const matchCat = (currentCategory === 'all') || (p.sub_kategori === currentCategory);
        const matchKab = (!kabFilter) || (p.kabupaten === kabFilter);
        const matchQuery = (!query) || (p.nama.toLowerCase().includes(query)) || (p.kecamatan.toLowerCase().includes(query));

        if (matchCat && matchKab && matchQuery) {
            matched++;
            const marker = L.marker([p.latitude, p.longitude], {
                icon: createCustomIcon(p.sub_kategori)
            });

            const access = getNearestHubClient(p.latitude, p.longitude);

            let radarBtn = '';
            if (p.kelompok === 'Daya Tarik') {
                radarBtn = `
                    <button onclick="findNearbyAmenities(${p.id})" 
                        class="w-full mt-2 py-1.5 px-3 rounded-xl bg-gradient-to-r from-ocean-600 to-emerald-600 hover:from-ocean-700 hover:to-emerald-700 text-white font-bold text-[11px] shadow-sm flex items-center justify-center gap-1.5 transition-all">
                        <i class="fa-solid fa-radar text-xs"></i>
                        <span>Radar Amenitas Terdekat</span>
                    </button>
                `;
            }

            const addTripBtn = `
                <button onclick="addToTripDirect(${p.id})" 
                    class="w-full mt-1.5 py-1 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[10px] flex items-center justify-center gap-1 transition-all">
                    <i class="fa-solid fa-plus text-[9px]"></i>
                    <span>Tambahkan ke Rute Itinerary</span>
                </button>
            `;

            const popupContent = `
                <div class="p-1 min-w-[220px]">
                    <div class="flex items-center justify-between gap-1 mb-1.5">
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold text-white" style="background-color: ${getMarkerColor(p.sub_kategori)}">
                            ${p.sub_kategori} (${p.kelompok})
                        </span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold border ${access.zoneBadge}">
                            ${access.zoneLabel}
                        </span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-1 leading-snug">${p.nama}</h4>
                    <p class="text-xs text-slate-500 mb-2">${p.kecamatan}, ${p.kabupaten}</p>
                    <div class="flex items-center justify-between text-xs pt-1.5 border-t border-slate-100">
                        <span class="font-bold text-amber-500 flex items-center gap-1">
                            <i class="fa-solid fa-star text-[10px]"></i> ${p.rating > 0 ? p.rating : 'N/A'}
                        </span>
                        <span class="text-slate-400 font-medium">${Number(p.ulasan).toLocaleString()} ulasan</span>
                    </div>

                    <!-- Aksesibilitas Simpul Transportasi Kemenparekraf -->
                    <div class="mt-2 p-2 bg-slate-50 rounded-xl border border-slate-200/80 space-y-1 text-[10px]">
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="font-bold flex items-center gap-1"><i class="fa-solid fa-plane-departure text-purple-600 text-[9px]"></i> ${access.airport ? access.airport.kode : 'Bandara'}:</span>
                            <span class="font-semibold text-slate-800">${access.airport ? access.airport.dist.toFixed(1) + ' km (~' + access.airport.mins + ' mnt)' : '-'}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span class="font-bold flex items-center gap-1"><i class="fa-solid fa-ship text-cyan-600 text-[9px]"></i> ${access.port ? access.port.kode : 'Pelabuhan'}:</span>
                            <span class="font-semibold text-slate-800">${access.port ? access.port.dist.toFixed(1) + ' km (~' + access.port.mins + ' mnt)' : '-'}</span>
                        </div>
                    </div>

                    ${radarBtn}
                    ${addTripBtn}
                </div>
            `;

            marker.bindPopup(popupContent);
            markersLayer.addLayer(marker);
        }
    });

    document.getElementById('counterBadge').innerText = `${matched} Titik Tampil`;
}

// Event Listeners
document.getElementById('searchInput').addEventListener('input', () => {
    if (currentMode === 'pin') renderMarkers();
    else renderHeatmap();
});

document.getElementById('filterKabupaten').addEventListener('change', () => {
    if (currentMode === 'pin') renderMarkers();
    else renderHeatmap();

    const val = document.getElementById('filterKabupaten').value;
    if (val) {
        const first = rawPlaces.find(p => p.kabupaten === val);
        if (first) {
            map.flyTo([first.latitude, first.longitude], 11, { duration: 1.2 });
        }
    } else {
        map.flyTo([-2.5, 106.8], 9, { duration: 1.2 });
    }
});

// Render awal
renderMarkers();
renderHubMarkers();
renderCatchmentRings('all');

// URL Query Param Deep Linking (dari TOPSIS SPK / Rekomendasi)
const urlParams = new URLSearchParams(window.location.search);
const focusQ = urlParams.get('q');
const focusLat = parseFloat(urlParams.get('lat'));
const focusLng = parseFloat(urlParams.get('lng'));

if (focusQ) {
    document.getElementById('searchInput').value = focusQ;
    renderMarkers();
}
if (!isNaN(focusLat) && !isNaN(focusLng)) {
    map.flyTo([focusLat, focusLng], 14, { duration: 1.5 });
}
</script>

