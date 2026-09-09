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
<!-- PANDUAN STRATEGIS 4 KUADRAN PRIORITAS KEBIJAKAN                -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    
    <!-- Kuadran I: Unggulan -->
    <div class="bg-white rounded-3xl p-5 border border-emerald-200/80 shadow-xs space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
            Kuadran I: Unggulan (Bintang)
        </span>
        <h4 class="text-sm font-black text-slate-900">Kunjungan &amp; Amenitas Tinggi</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            <strong>Daerah:</strong> Pangkalpinang, Belitung, Bangka.<br>
            <strong>Arahan:</strong> Pertahankan mutu layanan, perkuat kelestarian lingkungan, dan kembangkan wisata MICE (Meeting, Incentive, Convention, Exhibition).
        </p>
    </div>

    <!-- Kuadran II: Peluang Emas -->
    <div class="bg-white rounded-3xl p-5 border border-amber-200/80 shadow-xs space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
            Kuadran II: Peluang Investasi
        </span>
        <h4 class="text-sm font-black text-slate-900">Kunjungan Tinggi, Amenitas Kurang</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            <strong>Daerah:</strong> Bangka Tengah (Koba).<br>
            <strong>Arahan:</strong> Prioritas utama pembangunan hotel bintang 3 dan sentra kuliner untuk menampung limpahan wisatawan Danau Kaolin dan pantai.
        </p>
    </div>

    <!-- Kuadran III: Potensial -->
    <div class="bg-white rounded-3xl p-5 border border-sky-200/80 shadow-xs space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-100 text-sky-800">
            Kuadran III: Potensial Berkembang
        </span>
        <h4 class="text-sm font-black text-slate-900">Kunjungan &amp; Amenitas Rendah</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            <strong>Daerah:</strong> Bangka Barat, Bangka Selatan, Belitung Timur.<br>
            <strong>Arahan:</strong> Bangun infrastruktur jalan dan akses transportasi publik, kembangkan desa wisata, dan adakan festival budaya tahunan.
        </p>
    </div>

    <!-- Kuadran IV: Optimalisasi -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-800">
            Kuadran IV: Optimalisasi Pasar
        </span>
        <h4 class="text-sm font-black text-slate-900">Amenitas Siap, Kunjungan Rendah</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            <strong>Status:</strong> Belum ada kabupaten di kuadran ini.<br>
            <strong>Arahan:</strong> Jika fasilitas melebihi kunjungan, gencarkan promosi digital pariwisata terarah dan paket wisata hemat terintegrasi.
        </p>
    </div>

</div>
