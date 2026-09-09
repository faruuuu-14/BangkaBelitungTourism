<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      panduan_pengguna.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Panduan Ringkas Fungsi Sistem Berbahasa Indonesia)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen kartu penjelasan ringkas (Contextual Helper Callout) pada beranda.
 * Menjelaskan secara lugas 3 fungsi utama sistem bagi pengguna baru:
 * 1. Eksplorasi Spasial GIS (Pemetaan sebaran & radius jangkauan hotel/resto).
 * 2. Analisis Beban & BPS (Korelasi kebutuhan amenitas terhadap kunjungan).
 * 3. Rekomendasi TOPSIS (Sistem pendukung keputusan multi-kriteria).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menjawab pertanyaan mendasar pengguna ("Untuk apa sistem ini dibuat?"),
 * mengurangi kebingungan navigasi, serta meningkatkan skor System Usability Scale (SUS).
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 2. KARTU PANDUAN PENGGUNA (UNTUK APA FITUR-FITUR SISTEM INI?)  -->
<!-- ============================================================== -->
<div class="bg-gradient-to-r from-ocean-50/90 via-sky-50/70 to-emerald-50/70 border border-ocean-200/80 rounded-3xl p-5 sm:p-6 shadow-xs">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-2xl bg-ocean-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-ocean-600/20 mt-0.5">
            <i class="fa-solid fa-lightbulb text-base"></i>
        </div>
        <div class="space-y-3 min-w-0 flex-1">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                <h2 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Panduan Ringkas: Cara Memanfaatkan Sistem Informasi Ini</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-ocean-100 text-ocean-800 border border-ocean-300/60">Pengenalan Fitur</span>
                </h2>
                <button onclick="openGuideModal('dashboard')" class="text-xs font-bold text-ocean-700 hover:text-ocean-800 flex items-center gap-1 self-start sm:self-auto transition-colors">
                    <span>Pelajari Metodologi Lengkap</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </div>
            
            <p class="text-xs text-slate-600 leading-relaxed">
                Platform ini mengintegrasikan data spasial destinasi pariwisata dengan indikator ekonomi statistik resmi BPS untuk mendukung perencanaan wilayah dan pengalaman wisatawan:
            </p>

            <!-- 3 Kolom Penjelasan Fitur Utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-1">
                <!-- Fitur 1: Peta Spasial -->
                <div class="bg-white/80 backdrop-blur-xs p-3.5 rounded-2xl border border-ocean-100 space-y-1 hover:border-ocean-300 transition-colors">
                    <div class="flex items-center gap-2 text-xs font-bold text-ocean-800">
                        <i class="fa-solid fa-map-pin text-ocean-600"></i>
                        <span>Peta Wisata WebGIS</span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Melihat sebaran 875 objek wisata, radius jangkauan hotel/restoran terdekat (buffer 5 km), serta perencana rute perjalanan otomatis.
                    </p>
                </div>

                <!-- Fitur 2: Analisis Beban BPS -->
                <div class="bg-white/80 backdrop-blur-xs p-3.5 rounded-2xl border border-amber-100 space-y-1 hover:border-amber-300 transition-colors">
                    <div class="flex items-center gap-2 text-xs font-bold text-amber-800">
                        <i class="fa-solid fa-chart-line text-amber-600"></i>
                        <span>Analisis Statistik BPS</span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Menganalisis korelasi Pearson antara rasio amenitas terhadap laju kunjungan wisatawan, ketimpangan spasial, dan matriks investasi.
                    </p>
                </div>

                <!-- Fitur 3: Rekomendasi TOPSIS -->
                <div class="bg-white/80 backdrop-blur-xs p-3.5 rounded-2xl border border-emerald-100 space-y-1 hover:border-emerald-300 transition-colors">
                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-800">
                        <i class="fa-solid fa-ranking-star text-emerald-600"></i>
                        <span>SPK Rekomendasi TOPSIS</span>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Menemukan destinasi terbaik berdasarkan preferensi pembobotan personal (rating, ulasan, jarak transportasi, dan amenitas sekitar).
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
