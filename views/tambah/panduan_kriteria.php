<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      panduan_kriteria.php
 * @folder    views/tambah/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Panduan Standar Validasi Data Usulan Objek Wisata)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen panduan kriteria pendaftaran data objek baru (Submission Guidelines).
 * Menjelaskan standar kepatuhan data pariwisata sesuai pedoman Dinas Pariwisata
 * dan Kemenparekraf RI:
 * 1. Keberadaan Fisik & Aksesibilitas (Objek nyata dapat dikunjungi)
 * 2. Ketepatan Titik Koordinat GPS (Berada dalam batas wilayah Provinsi Babel)
 * 3. Alur Moderasi Verifikasi Dinas (Data berstatus 'Usulan Baru' sebelum disetujui)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/tambah/index.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menjaga kebersihan dan integritas basis data (Data Hygiene) dari input sampah
 * atau spam yang tidak sesuai realitas geografis.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- PANDUAN STANDAR VALIDASI DATA USULAN BARU                      -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    
    <!-- Panduan 1: Keberadaan Fisik Nyata -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2">
        <div class="w-8 h-8 rounded-xl bg-ocean-50 text-ocean-600 flex items-center justify-center text-xs font-bold shadow-xs">
            <i class="fa-solid fa-map-pin"></i>
        </div>
        <h4 class="text-xs sm:text-sm font-black text-slate-900">1. Keberadaan Fisik Nyata</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Objek wisata atau fasilitas amenitas yang didaftarkan harus memiliki lokasi fisik yang dapat dikunjungi langsung oleh publik di Provinsi Kepulauan Bangka Belitung.
        </p>
    </div>

    <!-- Panduan 2: Presisi Koordinat GPS -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2">
        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold shadow-xs">
            <i class="fa-solid fa-crosshairs"></i>
        </div>
        <h4 class="text-xs sm:text-sm font-black text-slate-900">2. Akurasi Koordinat GPS</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Pastikan pin lokasi berada tepat pada koordinat gerbang masuk objek. Koordinat yang akurat menjamin kalkulasi jarak transportasi pada SPK TOPSIS tetap presisi.
        </p>
    </div>

    <!-- Panduan 3: Alur Moderasi Dinas -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2">
        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold shadow-xs">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h4 class="text-xs sm:text-sm font-black text-slate-900">3. Moderasi Petugas Dinas</h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Setiap usulan baru akan masuk ke antrean moderasi berstatus <em>'Usulan Baru'</em> dan akan ditinjau oleh tim verifikator sebelum dipublikasikan ke peta utama.
        </p>
    </div>

</div>
