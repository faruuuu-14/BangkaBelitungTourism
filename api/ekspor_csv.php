<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      ekspor_csv.php
 * @folder    api/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (API Layanan Ekspor Data Tabular Format CSV Excel)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Menyediakan endpoint unduh dataset pariwisata terbuka dalam format CSV
 * (Comma-Separated Values) ber-BOM UTF-8 agar langsung terbuka rapi di Microsoft
 * Excel tanpa masalah encoding. Berisi 875 objek wisata resmi dengan koordinat GPS.
 * 
 * KETERKAITAN ALUR:
 * - Mengakses basis data: app/database.php (tabel tempat_wisata)
 * - Dipanggil oleh tombol: "Unduh CSV" di views/peta/panel_filter.php
 * ==============================================================================
 */

$_GET['format'] = 'csv';
require_once __DIR__ . '/export.php';
