<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      panduan_kuadran.php
 * @folder    views/analisis/matriks_kuadran/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Panduan Strategis 4 Kuadran Prioritas Investasi Pariwisata)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen penjelas arti setiap kuadran (Legend & Strategic Action Guide).
 * Merinci karakteristik dan arahan kebijakan untuk masing-masing dari 4 kuadran:
 * 1. Kuadran I   : Wilayah Bintang (Pertahankan Mutu & Diversifikasi Event)
 * 2. Kuadran II  : Peluang Emas (Tingkatkan Fasilitas Segera)
 * 3. Kuadran III : Potensial Berkembang (Promosi & Aksesibilitas Dasar)
 * 4. Kuadran IV  : Optimalisasi Pemasaran (Dorong Promosi agar Fasilitas Terisi)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memudahkan interpretasi grafik kuadran bagi pembaca tanpa perlu menghafal teori.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- PANDUAN STRATEGIS 4 KUADRAN PRIORITAS KEBIJAKAN BPS            -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    
    <!-- Kuadran I: Destination Stayers -->
    <div class="bg-white rounded-3xl p-5 border border-emerald-200/80 shadow-xs space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
            Kuadran I: Destination Stayers
        </span>
        <h4 class="text-sm font-black text-slate-900">Stayers &amp; RLMT Tinggi</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            <strong>Wilayah:</strong> Kab. Belitung (Stayers 62.3%, RLMT 2.08 Malam).<br>
            <strong>Arahan:</strong> Pertahankan mutu amenitas bintang, kelola kapasitas daya dukung pesisir (*carrying capacity*), dan perluas rute ke Belitung Timur.
        </p>
    </div>

    <!-- Kuadran II: Corridor Stayers -->
    <div class="bg-white rounded-3xl p-5 border border-indigo-200/80 shadow-xs space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-800">
            Kuadran II: Corridor Stayers
        </span>
        <h4 class="text-sm font-black text-slate-900">RLMT Tinggi, Stayers Sedang</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            <strong>Wilayah:</strong> Kab. Bangka Tengah (RLMT 1.54 Malam).<br>
            <strong>Arahan:</strong> Optimalkan fasilitas koridor bandara Depati Amir dan akomodasi pantai resort untuk menyerap limpahan perjalanan bisnis.
        </p>
    </div>

    <!-- Kuadran III: Day-Trippers & Buffers -->
    <div class="bg-white rounded-3xl p-5 border border-amber-200/80 shadow-xs space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
            Kuadran III: Day-Trippers &amp; Buffers
        </span>
        <h4 class="text-sm font-black text-slate-900">Day-Tripper Masif (&gt;88%)</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            <strong>Wilayah:</strong> Bangka, Babar, Basel, Beltim (RLMT 1.06 - 1.27 Malam).<br>
            <strong>Arahan:</strong> Standarisasi desa wisata dan homestay fleksibel untuk menampung lonjakan libur musiman tanpa membebani biaya modal hotel.
        </p>
    </div>

    <!-- Kuadran IV: Urban Hub & Transit Center -->
    <div class="bg-white rounded-3xl p-5 border border-purple-200/80 shadow-xs space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800">
            Kuadran IV: Urban Hub &amp; MICE
        </span>
        <h4 class="text-sm font-black text-slate-900">Volume Stayers Tertinggi</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            <strong>Wilayah:</strong> Kota Pangkal Pinang (23.235 Stayers, RLMT 1.32 Malam).<br>
            <strong>Arahan:</strong> Dorong event MICE, atraksi kuliner malam, dan museum heritage agar wisatawan transit memperpanjang masa tinggalnya.
        </p>
    </div>

</div>
