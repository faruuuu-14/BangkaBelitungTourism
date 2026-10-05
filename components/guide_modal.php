<?php
/**
 * components/guide_modal.php
 * Modal Pusat Panduan Pengguna, Sumber Data, dan Metodologi Komputasi Matematis
 * Standar Dokumentasi Sistem Informasi Intelijen Geospasial
 */
?>

<div id="guideModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/75 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-5xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- ============================================================== -->
        <!-- MODAL HEADER                                                   -->
        <!-- ============================================================== -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-ocean-950 to-indigo-950 text-white">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-ocean-500/20 text-ocean-400 flex items-center justify-center text-xl border border-white/10 shrink-0">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-white/10 text-ocean-300 text-[10px] font-bold tracking-wider uppercase mb-1">
                        <span>Pusat Informasi &amp; Dokumentasi Teknis</span>
                    </div>
                    <h3 class="font-extrabold text-lg sm:text-xl text-white leading-tight">
                        Panduan Pengguna, Sumber Data &amp; Metodologi Komputasi
                    </h3>
                </div>
            </div>

            <!-- Header Action Controls -->
            <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                <div class="relative hidden md:block">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="guideSearchInput" placeholder="Cari rumus, data, grafik..." oninput="searchGuideContent()"
                           class="pl-8 pr-3 py-1.5 bg-white/10 hover:bg-white/15 focus:bg-white focus:text-slate-900 text-white text-xs rounded-xl border border-white/20 focus:outline-none focus:ring-2 focus:ring-ocean-400 w-44 transition-all">
                </div>
                <button onclick="window.print()" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-print"></i>
                    <span class="hidden sm:inline">Cetak</span>
                </button>
                <button onclick="closeGuideModal()" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-rose-600/80 text-white flex items-center justify-center text-xs ml-1 transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TAB NAVIGATION (5 PILAR INFORMASI)                             -->
        <!-- ============================================================== -->
        <div class="flex items-center gap-1 p-2 bg-slate-100 border-b border-slate-200 overflow-x-auto text-xs shrink-0">
            <button onclick="switchGuideTab('gtab-overview')" id="btn-gtab-overview" 
                    class="guide-tab-btn px-4 py-2 rounded-xl font-bold bg-white text-ocean-700 shadow-sm flex items-center gap-2 whitespace-nowrap transition-all">
                <i class="fa-solid fa-compass text-xs"></i>
                <span>1. Panduan Penggunaan</span>
            </button>
            <button onclick="switchGuideTab('gtab-sources')" id="btn-gtab-sources" 
                    class="guide-tab-btn px-4 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 flex items-center gap-2 whitespace-nowrap transition-all">
                <i class="fa-solid fa-database text-xs"></i>
                <span>2. Sumber &amp; Validasi Data</span>
            </button>
            <button onclick="switchGuideTab('gtab-math')" id="btn-gtab-math" 
                    class="guide-tab-btn px-4 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 flex items-center gap-2 whitespace-nowrap transition-all">
                <i class="fa-solid fa-square-root-variable text-xs"></i>
                <span>3. Rumus &amp; Kalkulasi</span>
            </button>
            <button onclick="switchGuideTab('gtab-charts')" id="btn-gtab-charts" 
                    class="guide-tab-btn px-4 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 flex items-center gap-2 whitespace-nowrap transition-all">
                <i class="fa-solid fa-chart-pie text-xs"></i>
                <span>4. Membaca Diagram</span>
            </button>
            <button onclick="switchGuideTab('gtab-policy')" id="btn-gtab-policy" 
                    class="guide-tab-btn px-4 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 flex items-center gap-2 whitespace-nowrap transition-all">
                <i class="fa-solid fa-bullseye text-xs"></i>
                <span>5. Tujuan &amp; Nilai Kebijakan</span>
            </button>
        </div>

        <!-- ============================================================== -->
        <!-- MODAL BODY CONTENT                                             -->
        <!-- ============================================================== -->
        <div class="p-6 sm:p-8 overflow-y-auto space-y-6 text-slate-800 text-xs sm:text-sm leading-relaxed" id="guideModalBody">
            
            <!-- ========================================================== -->
            <!-- TAB 1: PANDUAN PENGGUNAAN FITUR & 3 PILAR NOVELTY          -->
            <!-- ========================================================== -->
            <div id="gtab-overview" class="guide-tab-pane space-y-6">
                <div class="bg-ocean-50/60 p-4 rounded-2xl border border-ocean-100 space-y-1">
                    <h4 class="font-extrabold text-ocean-950 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-ocean-600"></i>
                        <span>Platform Intelijen &amp; SPKS Cerdas Pariwisata Bangka Belitung (Skripsi Edition)</span>
                    </h4>
                    <p class="text-slate-600 text-xs">
                        Bukan sekadar visualisasi data pasif, platform ini merupakan <em>Integrated Spatial Decision Support System (SDSS)</em> berbasis komputasi cerdas dengan 3 pilar kebaruan ilmiah (novelty) untuk wisatawan, dinas, dan investor, serta siap diintegrasikan langsung ke aplikasi mobile Android Studio via RESTful JSON API.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <!-- Pilar 1: Smart Itinerary -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                        <div class="flex items-center gap-2 font-extrabold text-slate-900 text-sm">
                            <span class="w-7 h-7 rounded-lg bg-ocean-100 text-ocean-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-route"></i>
                            </span>
                            <span>Pilar 1: Smart Itinerary</span>
                        </div>
                        <p class="text-slate-600">
                            Penyusun jadwal rute harian otomatis berbasis <strong>Algoritma Heuristic 2-Opt Multi-Constraint OPTW</strong> yang menyeimbangkan durasi jam liburan, batas anggaran (budget), minat kategori, dan efisiensi jarak tempuh.
                        </p>
                        <div class="p-2.5 bg-slate-50 rounded-xl text-[11px] text-slate-500 font-medium">
                            <strong>Cara Pakai:</strong> Pilih durasi (1-3 hari), titik keberangkatan (bandara/pelabuhan), geser batas budget, lalu klik <em>[Optimasi Jadwal &amp; Rute]</em> untuk melihat timeline menit-ke-menit.
                        </div>
                    </div>

                    <!-- Pilar 2: Sentiment TOPSIS -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                        <div class="flex items-center gap-2 font-extrabold text-slate-900 text-sm">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-ranking-star"></i>
                            </span>
                            <span>Pilar 2: TOPSIS Sentimen</span>
                        </div>
                        <p class="text-slate-600">
                            Sistem Pendukung Keputusan pemeringkatan destinasi yang menggabungkan rating bintang dengan <strong>Analisis Sentimen Aspek NLP</strong> (kebersihan, fasilitas, dan aksesibilitas) pada ulasan asli Google Maps.
                        </p>
                        <div class="p-2.5 bg-slate-50 rounded-xl text-[11px] text-slate-500 font-medium">
                            <strong>Cara Pakai:</strong> Buka menu SPK TOPSIS, amati podium juara Top 3, gunakan preset persona atau ubah bobot kriteria untuk melihat perubahan nilai preferensi ($V_i$).
                        </div>
                    </div>

                    <!-- Pilar 3: Radar Bisnis UMKM -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-2">
                        <div class="flex items-center gap-2 font-extrabold text-slate-900 text-sm">
                            <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-chart-pie"></i>
                            </span>
                            <span>Pilar 3: Radar Bisnis UMKM</span>
                        </div>
                        <p class="text-slate-600">
                            Evaluasi Spasial Multi-Kriteria (<strong>SMCE</strong>) penentu lokasi strategis pendirian usaha kafe, homestay, atau sentra oleh-oleh berdasarkan <em>Market Gap Analysis</em> (tarikan wisata vs kepadatan pesaing).
                        </p>
                        <div class="p-2.5 bg-slate-50 rounded-xl text-[11px] text-slate-500 font-medium">
                            <strong>Cara Pakai:</strong> Pilih model usaha (Kafe, Homestay, atau Suvenir UMKM), amati peta panas zona peluang emas (hijau), dan pelajari rekomendasi koordinat investasi terbaik.
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- TAB 2: SUMBER DATA & ASAL MUASAL                           -->
            <!-- ========================================================== -->
            <div id="gtab-sources" class="guide-tab-pane hidden space-y-6">
                <div class="space-y-4 text-xs">
                    <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-database text-ocean-600"></i>
                        <span>Asal Muasal &amp; Metodologi Kurasi Dataset (Satu Data Pariwisata)</span>
                    </h4>

                    <!-- 3 Komponen Sumber Data -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                            <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                <i class="fa-brands fa-google text-rose-500"></i>
                                <span>875 Data Spasial POI</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                <strong>Sumber:</strong> Google Places API &amp; Geocoding Crawler yang telah diverifikasi dan dikurasi oleh tim data dinas.
                            </p>
                            <p class="text-slate-500 text-[11px]">
                                <strong>Struktur Data:</strong> Koordinat Lintang/Bujur (Latitude/Longitude), nama resmi, kabupaten, kecamatan, kelompok (Daya Tarik vs Amenitas), sub-kategori (Alam, Buatan, Budaya, Hotel, Resto), rating bintang (1.0–5.0), dan jumlah ulasan publik.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                            <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-landmark text-ocean-600"></i>
                                <span>112 Baris Data Makro BPS</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                <strong>Sumber:</strong> Badan Pusat Statistik (BPS) Provinsi Kepulauan Bangka Belitung (Publikasi Resmi Statistik Kunjungan Wisatawan).
                            </p>
                            <p class="text-slate-500 text-[11px]">
                                <strong>Cakupan:</strong> 16 Titik Observasi Bulanan (Januari 2025 s.d. April 2026) mencakup 7 Kabupaten/Kota untuk wisatawan nusantara (Wisnus), menjadi dasar model deret waktu (*time-series forecasting*).
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                            <div class="font-bold text-slate-900 text-xs flex items-center gap-1.5">
                                <i class="fa-solid fa-plane-arrival text-purple-600"></i>
                                <span>6 Simpul Transportasi Utama</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                <strong>Sumber:</strong> Data simpul gerbang masuk Kementerian Perhubungan RI, PT Angkasa Pura II, dan PT Pelindo Regional II.
                            </p>
                            <p class="text-slate-500 text-[11px]">
                                <strong>Objek:</strong> Bandara Depati Amir (PGK), Bandara H.A.S. Hanandjoeddin (TJQ), Pelabuhan Tanjung Kalian (TJK), Pangkal Balam (PKB), Tanjung Pandan (TJP), dan Sadai (SDI).
                            </p>
                        </div>
                    </div>

                    <!-- Alur Verifikasi & Integritas Data -->
                    <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200 space-y-2">
                        <h5 class="font-bold text-emerald-900 text-xs flex items-center gap-2">
                            <i class="fa-solid fa-shield-check text-emerald-600"></i>
                            <span>Protokol Integritas &amp; Verifikasi Data</span>
                        </h5>
                        <p class="text-slate-600 text-[11px]">
                            Setiap titik objek wisata melewati tahapan verifikasi koordinat, penyaringan duplikasi (*deduplication*), serta validasi batas administrasi kecamatan/kabupaten menggunakan batas peta resmi Badan Informasi Geospasial (BIG). Titik yang diajukan masyarakat melalui menu <em>Input Tempat</em> masuk ke status <strong>Pending</strong> dan wajib disetujui di panel <em>Moderasi</em> sebelum tampil di peta publik.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- TAB 3: RUMUS & KALKULASI MATEMATIS                         -->
            <!-- ========================================================== -->
            <div id="gtab-math" class="guide-tab-pane hidden space-y-6">
                <div class="space-y-4 text-xs">
                    <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-square-root-variable text-ocean-600"></i>
                        <span>Bedah Rumus Komputasi &amp; Algoritma Sistem</span>
                    </h4>

                    <div class="space-y-3">
                        
                        <!-- 1. Jarak Haversine -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-xs">1. Formula Jarak Geodesik Lingkaran Besar (Haversine Distance)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-ocean-100 text-ocean-800">Spasial</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Menghitung jarak melengkung di atas permukaan bumi antara dua koordinat bola $(\phi_1, \lambda_1)$ dan $(\phi_2, \lambda_2)$ dengan jari-jari bumi $R = 6,371\text{ km}$:
                            </p>
                            <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] overflow-x-auto">
                                a = sin²(Δφ/2) + cos(φ₁) · cos(φ₂) · sin²(Δλ/2)
                                c = 2 · atan2(√a, √(1-a))
                                d = R · c  (km)
                            </div>
                            <p class="text-slate-500 text-[10px]">
                                Digunakan pada: Cincin Catchment Buffer, Radar Amenitas 5 km, NNA, dan Jarak ke Simpul Transportasi.
                            </p>
                        </div>

                        <!-- 2. Korelasi Pearson -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-xs">2. Koefisien Korelasi Product Moment Pearson (r)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-800">Statistik</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Mengukur derajat kekuatan dan arah hubungan linier antara variabel bebas $X$ (amenitas hotel/resto) terhadap variabel terikat $Y$ (kunjungan wisatawan):
                            </p>
                            <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] overflow-x-auto">
                                r = Σ((X - X̄)(Y - Ȳ)) / √[ Σ(X - X̄)² · Σ(Y - Ȳ)² ]
                            </div>
                            <p class="text-slate-500 text-[10px]">
                                Interpretasi: Nilai $r \ge 0.70$ menunjukkan korelasi sangat kuat; korelasi hotel terhadap kunjungan tercatat $r = +0.817$.
                            </p>
                        </div>

                        <!-- 3. Clark & Evans NNA -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-xs">3. Nearest Neighbor Analysis (Clark &amp; Evans 1954)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Spasial Pattern</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Menguji apakah titik sebaran berkumpul, acak, atau tersebar teratur dengan membandingkan jarak teramati ($\bar{D}_O$) dan jarak teoretis Poisson ($\bar{D}_E$):
                            </p>
                            <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] overflow-x-auto">
                                D̄_O = (Σ d_min) / N
                                D̄_E = 0.5 / √(N / Luas_Area)
                                R = D̄_O / D̄_E
                                Z = (D̄_O - D̄_E) / [ 0.26136 / √(N² / Luas_Area) ]
                            </div>
                            <p class="text-slate-500 text-[10px]">
                                Interpretasi: $R = 0.4066$ ($R < 1.0$) dan $Z = -33.58$ ($p < 0.0001$). Terbukti pola sebaran mengelompok signifikan (*Clustered*).
                            </p>
                        </div>

                        <!-- 4. Global Moran's I -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-xs">4. Autokorelasi Spasial Global Moran's I (Spatial Lag)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">Ekonometrika</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Mengukur dependensi spasial atribut popularitas ($\ln(\text{ulasan}+1)$) menggunakan matriks bobot invers jarak $w_{ij} = 1/d_{ij}$ ($d \le 25\text{ km}$):
                            </p>
                            <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] overflow-x-auto">
                                I = (N / W) · [ Σ Σ w_ij(x_i - x̄)(x_j - x̄) ] / [ Σ (x_i - x̄)² ]
                                E[I] = -1 / (N - 1)
                            </div>
                            <p class="text-slate-500 text-[10px]">
                                Interpretasi: Nilai $I = +0.1121 > E[I] = -0.0011$, membuktikan aglomerasi spasial positif (*Hotspot Clustering*).
                            </p>
                        </div>

                        <!-- 5. Holt's Linear Trend -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-xs">5. Peramalan Deret Waktu Holt's Linear Trend (Double Exponential Smoothing)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-sky-100 text-sky-800">Time-Series</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Memuluskan komponen level ($L_t$) dan komponen tren linier ($T_t$) dengan koefisien $\alpha = 0.35$ dan $\beta = 0.15$:
                            </p>
                            <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] overflow-x-auto">
                                L_t = α · Y_t + (1 - α)(L_{t-1} + T_{t-1})
                                T_t = β · (L_t - L_{t-1}) + (1 - β) · T_{t-1}
                                Ŷ_{t+m} = L_t + m · T_t
                                CI 95% = Ŷ_{t+m} ± 1.96 · SE
                            </div>
                            <p class="text-slate-500 text-[10px]">
                                Akurasi model: $\text{RMSE} = 120,588$, $\text{MAPE} = 21.32\%$ (Akurasi $78.68\%$).
                            </p>
                        </div>

                        <!-- 6. TCC Cifuentes -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-xs">6. Daya Dukung Pariwisata (Tourism Carrying Capacity - TCC Cifuentes 1992)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-teal-100 text-teal-800">Lingkungan</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Menghitung batas kunjungan harian aman agar ekosistem pesisir/geosite tidak rusak:
                            </p>
                            <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] overflow-x-auto">
                                PCC = (Luas Area / Standar Ruang per Orang) × (Jam Buka / Durasi Kunjungan)
                                RCC = PCC × (1 - CF_cuaca) × (1 - CF_pasang) × (1 - CF_erosi)
                                ECC = RCC × Koefisien_Manajemen (MC)
                                Utilisasi = (Kunjungan Puncak Harian / ECC) × 100%
                            </div>
                        </div>

                        <!-- 7. TOPSIS MCDM -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-xs">7. Multi-Criteria Decision Making Metode TOPSIS (Hwang &amp; Yoon 1981)</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">SPK</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Merangking alternatif berdasarkan jarak terpendek ke solusi ideal positif ($A^+$) dan terjauh dari ideal negatif ($A^-$):
                            </p>
                            <div class="p-2.5 rounded-xl bg-slate-900 text-emerald-400 font-mono text-[11px] overflow-x-auto">
                                r_ij = x_ij / √(Σ x_kj²)
                                y_ij = w_j · r_ij
                                D_i⁺ = √[ Σ (y_ij - A_j⁺)² ]   (Jarak ke Ideal Positif)
                                D_i⁻ = √[ Σ (y_ij - A_j⁻)² ]   (Jarak ke Ideal Negatif)
                                V_i  = D_i⁻ / (D_i⁺ + D_i⁻)    (Skor Preferensi Relatif 0.0 – 1.0)
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- TAB 4: MEMBACA DIAGRAM & GRAFIK                            -->
            <!-- ========================================================== -->
            <div id="gtab-charts" class="guide-tab-pane hidden space-y-6">
                <div class="space-y-4 text-xs">
                    <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-ocean-600"></i>
                        <span>Panduan Membaca &amp; Menginterpretasikan Grafik</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Moran Scatterplot -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <span class="font-bold text-slate-900 text-xs block">1. Moran Scatterplot (4 Kuadran)</span>
                            <p class="text-slate-600 text-[11px]">
                                Sumbu $X$ mewakili nilai standar popularitas destinasi ($z_i$), sedangkan sumbu $Y$ mewakili rata-rata popularitas tetangga sekitarnya (*Spatial Lag* $W z_i$).
                            </p>
                            <ul class="space-y-1 text-[11px] text-slate-500">
                                <li>🟢 <strong class="text-emerald-700">Kanan Atas (High-High):</strong> Klaster Hotspot (Destinasi populer dikelilingi destinasi populer).</li>
                                <li>🔵 <strong class="text-blue-700">Kiri Bawah (Low-Low):</strong> Klaster Coldspot (Destinasi sepi dikelilingi destinasi sepi).</li>
                                <li>🟡 <strong class="text-amber-700">Kanan Bawah (High-Low):</strong> Outlier (Destinasi populer tapi tetangganya sepi).</li>
                                <li>🟣 <strong class="text-purple-700">Kiri Atas (Low-High):</strong> Potensial (Destinasi sepi tapi berada di kawasan populer).</li>
                            </ul>
                        </div>

                        <!-- Tipologi Klassen -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <span class="font-bold text-slate-900 text-xs block">2. Matriks Tipologi Klassen Pariwisata</span>
                            <p class="text-slate-600 text-[11px]">
                                Menilai posisi ekonomi pariwisata daerah terhadap patokan rata-rata provinsi ($g_{prov} = +9.95\%$, $y_{prov} = 226,568$ orang).
                            </p>
                            <ul class="space-y-1 text-[11px] text-slate-500">
                                <li>🟢 <strong>Kuadran I (Maju &amp; Tumbuh Cepat):</strong> Kab. Bangka (Pusat gravitasi utama pariwisata).</li>
                                <li>🔵 <strong>Kuadran II (Potensial Berkembang):</strong> Belitung Timur &amp; Bangka Barat (Pertumbuhan pesat butuh DAK Fisik).</li>
                                <li>🟡 <strong>Kuadran III (Mapan):</strong> Pangkalpinang &amp; Bangka Tengah (Kunjungan besar, butuh event festival KEN).</li>
                                <li>🔴 <strong>Kuadran IV (Rintisan):</strong> Belitung &amp; Bangka Selatan (Perlu penguatan konektivitas bandara &amp; Pokdarwis).</li>
                            </ul>
                        </div>

                        <!-- Spider Radar IPKN -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <span class="font-bold text-slate-900 text-xs block">3. Spider Radar Chart IPKN-Daerah</span>
                            <p class="text-slate-600 text-[11px]">
                                Membandingkan profil 6 pilar kesiapan pariwisata daerah terhadap rata-rata provinsi. Semakin luas area poligon terisi, semakin mandiri dan siap daerah tersebut menyambut wisatawan mancanegara dan nusantara.
                            </p>
                        </div>

                        <!-- Cincin Buffer Catchment -->
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 space-y-2">
                            <span class="font-bold text-slate-900 text-xs block">4. Cincin Konsentris Keterjangkauan (Catchment Rings)</span>
                            <p class="text-slate-600 text-[11px]">
                                Menampilkan lingkaran radius keterjangkauan dari simpul bandara/pelabuhan. Hijau ($\le 15$ km / $\le 20$ mnt), Biru ($15–30$ km), Kuning ($30–50$ km), dan Merah ($>50$ km / Blank Spot isolasi).
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- TAB 5: TUJUAN & NILAI KEBIJAKAN                            -->
            <!-- ========================================================== -->
            <div id="gtab-policy" class="guide-tab-pane hidden space-y-6">
                <div class="space-y-4 text-xs">
                    <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-bullseye text-ocean-600"></i>
                        <span>Manfaat Praktis &amp; Pengambilan Keputusan (*Use Cases*)</span>
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <div class="font-bold text-slate-900 text-xs flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-ocean-100 text-ocean-700 flex items-center justify-center text-[10px]">1</span>
                                <span>Bagi Dinas Pariwisata &amp; Bappenas</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Menjadi rujukan ilmiah (*Evidence-Based Policy*) dalam menyusun Rencana Induk Pembangunan Kepariwisataan Daerah (RIPPARDA), penentuan alokasi DAK Fisik Pariwisata di 14 kecamatan defisit, serta bahan negosiasi pembukaan trayek bus perintis Kemenhub ke destinasi Ring 4.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <div class="font-bold text-slate-900 text-xs flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px]">2</span>
                                <span>Bagi Investor Hotel &amp; Restoran</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Mengidentifikasi peluang emas investasi akomodasi pada kecamatan dengan potensi daya tarik alam tinggi namun defisit hotel (seperti Membalong dan Belinyu) untuk menangkap luapan wisatawan nusantara.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <div class="font-bold text-slate-900 text-xs flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-[10px]">3</span>
                                <span>Bagi Pengelola Geosite &amp; Pokdarwis</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Memantau batas daya dukung lingkungan (TCC) agar tidak terjadi *over-tourism* yang merusak ekosistem batuan granit Tor dan habitat endemik Tarsius di UNESCO Global Geopark Belitong.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <div class="font-bold text-slate-900 text-xs flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-[10px]">4</span>
                                <span>Bagi Wisatawan Nusantara &amp; Mancanegara</span>
                            </div>
                            <p class="text-slate-600 text-[11px]">
                                Memperoleh rekomendasi objektif multi-kriteria sesuai preferensi pribadi melalui SPK TOPSIS dan merencanakan rute kunjungan paling efisien bebas macet (*Smart Itinerary Planner*).
                            </p>
                        </div>
                    </div>

                    <!-- Profil Pembuat Website -->
                    <div class="p-5 rounded-2xl bg-slate-900 text-white space-y-4 border border-slate-800 shadow-md">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-3 border-b border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="relative flex-shrink-0">
                                    <img src="assets/img/profil/fathurrahman.jpg" alt="Fathurrahman Safari Lubis" class="w-14 h-14 rounded-2xl object-cover object-top border border-slate-700 shadow-md">
                                </div>
                                <div>
                                    <span class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase">Pembuat Website:</span>
                                    <h5 class="text-base font-bold text-white tracking-tight">Fathurrahman Safari Lubis</h5>
                                    <p class="text-xs text-slate-300 font-medium">NIM: <strong class="text-ocean-300 font-mono">2411500025</strong> &bull; Mahasiswa Teknik Informatika</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right text-xs text-slate-300">
                                <div class="font-semibold text-white">Institut Sains dan Bisnis Atma Luhur</div>
                                <div class="text-[11px] text-slate-400">Pangkalpinang, Kep. Bangka Belitung</div>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 text-xs text-slate-300 leading-relaxed space-y-1.5">
                            <div class="font-semibold text-ocean-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-code"></i>
                                <span>Tentang Proyek Website</span>
                            </div>
                            <p>
                                Website ini dibuat dan dikembangkan oleh <strong>Fathurrahman Safari Lubis</strong> sebagai proyek pengolahan data dan sistem informasi geospasial (WebGIS) pariwisata Provinsi Kepulauan Bangka Belitung, menggabungkan pemetaan 875 objek wisata dan fasilitas dengan statistik kunjungan BPS.
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-slate-400">
                            <div>
                                &copy; <?= date('Y') ?> <strong>Fathurrahman Safari Lubis</strong> &bull; ISB Atma Luhur Pangkalpinang.
                            </div>
                            <div class="text-slate-400 text-xs">
                                Teknik Informatika 2024
                            </div>
                        </div>
                    </div>

                    <!-- Kontak & Layanan Data -->
                    <div class="p-4 rounded-2xl bg-slate-100 text-slate-600 text-[11px] flex flex-col sm:flex-row items-center justify-between gap-2">
                        <span>Pusat Data &amp; Statistik Dinas Kebudayaan, Pariwisata, Kepemudaan dan Olahraga Provinsi Kepulauan Bangka Belitung</span>
                        <span class="font-bold text-slate-800">Versi Sistem: 2.4-SDSS (Produksi Terverifikasi)</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ============================================================== -->
        <!-- MODAL FOOTER                                                   -->
        <!-- ============================================================== -->
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
            <span class="text-slate-400 italic">Dokumentasi Standar Sistem Intelijen Geospasial &amp; Ekonometrika Pariwisata</span>
            <button onclick="closeGuideModal()" class="px-5 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold transition-all">
                Tutup Panduan
            </button>
        </div>

    </div>
</div>

<script>
// ==============================================================
// CONTROLLER MODAL PANDUAN PENGGUNA & METODOLOGI
// ==============================================================
function openGuideModal(initialTab) {
    document.getElementById('guideModal').classList.remove('hidden');
    if (initialTab) {
        if (initialTab === 'dashboard') switchGuideTab('gtab-overview');
        else if (initialTab === 'peta') switchGuideTab('gtab-charts');
        else if (initialTab === 'analisis') switchGuideTab('gtab-math');
        else if (initialTab === 'rekomendasi') switchGuideTab('gtab-math');
        else if (initialTab === 'sumber_data') switchGuideTab('gtab-sources');
        else switchGuideTab(initialTab);
    }
}

function closeGuideModal() {
    document.getElementById('guideModal').classList.add('hidden');
}

function switchGuideTab(tabId) {
    document.querySelectorAll('.guide-tab-pane').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.guide-tab-btn').forEach(btn => {
        btn.className = 'guide-tab-btn px-4 py-2 rounded-xl font-semibold text-slate-600 hover:text-slate-900 hover:bg-white/60 flex items-center gap-2 whitespace-nowrap transition-all';
    });

    const targetPane = document.getElementById(tabId);
    if (targetPane) targetPane.classList.remove('hidden');

    const activeBtn = document.getElementById('btn-' + tabId);
    if (activeBtn) {
        activeBtn.className = 'guide-tab-btn px-4 py-2 rounded-xl font-bold bg-white text-ocean-700 shadow-sm flex items-center gap-2 whitespace-nowrap transition-all';
    }
}

function searchGuideContent() {
    const q = document.getElementById('guideSearchInput').value.toLowerCase().trim();
    const panes = document.querySelectorAll('.guide-tab-pane');
    
    if (!q) {
        // Tampilkan semua card jika search kosong
        document.querySelectorAll('#guideModalBody [class*="rounded-2xl"]').forEach(el => {
            el.style.display = '';
        });
        return;
    }

    // Cari di semua tab, buka tab yang cocok
    let foundTab = null;
    panes.forEach(pane => {
        const text = pane.innerText.toLowerCase();
        if (text.includes(q)) {
            if (!foundTab) foundTab = pane.id;
        }
    });

    if (foundTab) {
        switchGuideTab(foundTab);
    }
}

// Tutup modal jika tombol ESC ditekan
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeGuideModal();
    }
});
</script>
