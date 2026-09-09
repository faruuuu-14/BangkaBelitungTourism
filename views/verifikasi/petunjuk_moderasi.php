<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      petunjuk_moderasi.php
 * @folder    views/verifikasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Petunjuk Standar Operasional Prosedur Moderasi Data Dinas)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen kartu SOP Moderasi (Moderation Guidelines Card). Merinci panduan
 * bagi petugas verifikator dinas dalam mengevaluasi kelayakan data usulan:
 * 1. Pengecekan Duplikasi Data (Mencegah pencatatan ganda objek yang sama)
 * 2. Validasi Batas Wilayah Administrasi (Koordinat harus di Kepulauan Bangka Belitung)
 * 3. Kelayakan Nama & Kategori (Bebas dari konten promosi ilegal atau ofensif)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/verifikasi/index.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menjaga integritas data sistem informasi sesuai standar Satu Data Indonesia (SDI).
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- PETUNJUK STANDAR OPERASIONAL MODERASI DATA DINAS               -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2">
        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold shadow-xs">
            <i class="fa-solid fa-copy"></i>
        </div>
        <h4 class="text-xs sm:text-sm font-black text-slate-900">1. Cek Duplikasi Data</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Periksa apakah nama objek wisata atau hotel/restoran sudah terdaftar sebelumnya di kecamatan terkait guna mencegah data ganda.
        </p>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2">
        <div class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center text-xs font-bold shadow-xs">
            <i class="fa-solid fa-map"></i>
        </div>
        <h4 class="text-xs sm:text-sm font-black text-slate-900">2. Validasi Batas Spasial</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Pastikan koordinat GPS berada dalam batas geografis Provinsi Bangka Belitung (Latitude -1.0 s.d -3.5, Longitude 105.0 s.d 108.5).
        </p>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2">
        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold shadow-xs">
            <i class="fa-solid fa-tag"></i>
        </div>
        <h4 class="text-xs sm:text-sm font-black text-slate-900">3. Ketepatan Kategori 3A</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Pastikan pengelompokan (Alam, Kebudayaan, Buatan, Hotel, Restoran) telah sesuai dengan peruntukan fungsi utama tempat di lapangan.
        </p>
    </div>

</div>
