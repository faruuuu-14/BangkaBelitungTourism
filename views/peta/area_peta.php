<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      area_peta.php
 * @folder    views/peta/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Kontainer Kanvas Peta Spasial Leaflet)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen kontainer utama kanvas peta interaktif Leaflet. Mengisi sisa ruang
 * layar vertikal secara responsif (Viewport Fill), memuat elemen #map tempat
 * layer OpenStreetMap dirender, serta dilengkapi kartu status mengambang di pojok
 * untuk informasi koordinat kursor dan status zona aksesibilitas.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/peta/index.php
 * - Dirender oleh: skrip_peta.php menggunakan pustaka Leaflet.js
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menyediakan area interaksi spasial yang lega, tajam, dan bebas hambatan visual.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 2. KONTAINER KANVAS PETA SPASIAL LEAFLET                       -->
<!-- ============================================================== -->
<div class="relative flex-1 w-full h-full min-h-[400px] overflow-hidden bg-slate-100">
    
    <!-- Kanvas Utama Leaflet Map -->
    <div id="map" class="w-full h-full z-10"></div>

    <!-- Kotak Indikator Mengambang: Keterangan Cepat Bawah Kiri Peta -->
    <div class="absolute bottom-4 left-4 z-20 hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/90 backdrop-blur-md border border-slate-200/80 shadow-md text-xs text-slate-700">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <span class="font-bold">WebGIS Aktif</span>
        <span class="text-slate-400">&bull;</span>
        <span class="text-[11px] text-slate-500">Klik marker untuk melihat foto &amp; jarak fasilitas</span>
    </div>

</div>
