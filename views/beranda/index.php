<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN & SPKS PARIWISATA BANGKA BELITUNG TERPADU
 * ==============================================================================
 * 
 * @file      index.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   4.0.0 (Clean Modern Hub & Novelty Gateway)
 * ==============================================================================
 */

require_once __DIR__ . '/../../app/database.php';

$pdo = getDbConnection();

// Hitung statistik cepat
$totalWisata = 875;
$totalAlam = 0;
$totalBudaya = 0;
$totalAmenitas = 0;

try {
    $totalWisata = (int)$pdo->query("SELECT COUNT(*) FROM tempat_wisata WHERE status_verifikasi = 'Resmi'")->fetchColumn();
    $totalAlam = (int)$pdo->query("SELECT COUNT(*) FROM tempat_wisata WHERE sub_kategori = 'Alam'")->fetchColumn();
    $totalBudaya = (int)$pdo->query("SELECT COUNT(*) FROM tempat_wisata WHERE sub_kategori = 'Kebudayaan'")->fetchColumn();
    $totalAmenitas = (int)$pdo->query("SELECT COUNT(*) FROM tempat_wisata WHERE kelompok = 'Amenitas'")->fetchColumn();
} catch (Exception $e) {
    // fallback
}
?>

<div class="space-y-6 pb-20 md:pb-10">

    <!-- 1. HERO BANNER MODERN BERKELAS (APPLE / LINEAR STYLE) -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-ocean-950 border border-slate-800/80 p-6 sm:p-9 shadow-xl text-white">
        <!-- Ambient Glow -->
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-ocean-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl space-y-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-bold text-ocean-300 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Sistem Pendukung Keputusan Spasial Cerdas Terpadu</span>
            </div>

            <h1 class="text-2xl sm:text-4xl md:text-5xl font-black tracking-tight leading-tight text-white">
                Intelijen Pariwisata <br>
                <span class="bg-gradient-to-r from-ocean-400 via-teal-300 to-emerald-400 bg-clip-text text-transparent">
                    Bangka Belitung
                </span>
            </h1>

            <p class="text-xs sm:text-sm md:text-base text-slate-300 font-normal leading-relaxed max-w-2xl">
                Bukan sekadar peta visualisasi data biasa. Platform komputasi cerdas yang menggabungkan <strong class="text-white">Optimasi Rute Wisata Multi-Objektif</strong>, <strong class="text-white">SPK TOPSIS Sentimen Ulasan</strong>, dan <strong class="text-white">Radar Kelayakan Lokasi Bisnis UMKM</strong>.
            </p>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="index.php?page=itinerary" class="px-5 py-3 rounded-2xl bg-ocean-600 hover:bg-ocean-500 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-lg shadow-ocean-600/30 active:scale-95 transition-all">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Rencanakan Rute Cerdas</span>
                </a>
                <a href="index.php?page=peta" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-bold text-xs sm:text-sm flex items-center gap-2 active:scale-95 transition-all">
                    <i class="fa-solid fa-map-location-dot text-teal-300"></i>
                    <span>Eksplorasi Peta WebGIS</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. TIGA PILAR NOVELTY ILMIAH SKRIPSI (GATEWAY UTAMA) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <div>
                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">3 Pilar Utama Sistem Cerdas</h2>
                <p class="text-xs text-slate-500">Pilih modul fungsional yang ingin kamu uji dan manfaatkan</p>
            </div>
            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                Novelty Teruji
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Pilar 1: Smart Itinerary Optimizer -->
            <a href="index.php?page=itinerary" class="group relative bg-white hover:bg-ocean-50/30 rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-ocean-50 text-ocean-600 border border-ocean-100 flex items-center justify-center text-xl font-bold shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-route"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-ocean-600 uppercase tracking-wider">Pilar 1 &bull; Wisatawan</div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-ocean-600 transition-colors">
                            Smart Itinerary Optimizer
                        </h3>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Penyusun rencana perjalanan otomatis menit-ke-menit berbasis Algoritma 2-Opt yang menyeimbangkan durasi, anggaran (budget), dan efisiensi jarak tempuh.
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-ocean-600">
                    <span>Mulai Optimasi Rute</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- Pilar 2: Sentiment-Driven TOPSIS -->
            <a href="index.php?page=rekomendasi" class="group relative bg-white hover:bg-indigo-50/30 rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-xl font-bold shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-ranking-star"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Pilar 2 &bull; Publik &amp; Dinas</div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                            SPK TOPSIS Sentimen Ulasan
                        </h3>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Perangkingan destinasi objektif yang memadukan rating bintang dengan analisis sentimen teks nyata terkait kebersihan, fasilitas, dan kemudahan akses.
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-indigo-600">
                    <span>Lihat Peringkat Ilmiah</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

            <!-- Pilar 3: Spatial Business Suitability Radar -->
            <a href="index.php?page=radar_bisnis" class="group relative bg-white hover:bg-emerald-50/30 rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-xl font-bold shadow-2xs group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Pilar 3 &bull; UMKM &amp; Investor</div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">
                            Radar Potensi Usaha &amp; UMKM
                        </h3>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Evaluasi spasial multi-kriteria untuk merekomendasikan titik lokasi terbaik mendirikan kafe, homestay, atau sentra suvenir berbasis analisis kesenjangan pasar.
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600">
                    <span>Eksplorasi Radar Potensi</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </div>
            </a>

        </div>
    </div>

    <!-- 3. METRIK CEPAT & INDIKATOR DATA RESMI -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Terpetakan</div>
                <div class="text-xl sm:text-3xl font-black text-slate-900 mt-1"><?= $totalWisata ?></div>
                <div class="text-[11px] text-ocean-600 font-semibold mt-0.5">875 Titik Koordinat GPS</div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Wisata Bahari &amp; Alam</div>
                <div class="text-xl sm:text-3xl font-black text-slate-900 mt-1"><?= $totalAlam ?></div>
                <div class="text-[11px] text-emerald-600 font-semibold mt-0.5">Pantai, Danau, &amp; Pulau</div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Budaya &amp; Sejarah</div>
                <div class="text-xl sm:text-3xl font-black text-slate-900 mt-1"><?= $totalBudaya ?></div>
                <div class="text-[11px] text-rose-600 font-semibold mt-0.5">Museum, Kelenteng, Tradisi</div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Amenitas Pendukung</div>
                <div class="text-xl sm:text-3xl font-black text-slate-900 mt-1"><?= $totalAmenitas ?></div>
                <div class="text-[11px] text-amber-600 font-semibold mt-0.5">Hotel &amp; Restoran Kuliner</div>
            </div>
        </div>
    </div>

    <!-- 4. BANNER KESIAPAN APLIKASI ANDROID STUDIO -->
    <div class="p-5 sm:p-6 rounded-3xl bg-slate-900 text-white border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                <i class="fa-brands fa-android"></i>
                <span>Android Studio Ready</span>
            </div>
            <h4 class="text-sm sm:text-base font-bold text-white">Arsitektur Terbuka &bull; RESTful JSON API Backend</h4>
            <p class="text-xs text-slate-400 max-w-xl">
                Seluruh engine di atas sudah terhubung dengan endpoint REST API mandiri di folder <code>api/v1_*.php</code> yang siap langsung dihubungkan ke aplikasi Android Faru kelak menggunakan Retrofit.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="api/v1_destinasi.php?limit=5" target="_blank" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-mono text-xs border border-slate-700 flex items-center gap-1.5">
                <span>Cek API JSON</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
            </a>
        </div>
    </div>

</div>
