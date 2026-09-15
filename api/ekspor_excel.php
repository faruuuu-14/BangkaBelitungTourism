<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      ekspor_excel.php
 * @folder    api/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (API Layanan Ekspor Dataset Format Spreadsheet Microsoft Excel)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Menyediakan endpoint unduh dataset pariwisata terbuka format Microsoft Excel
 * (.xls) dengan format tabel terstruktur, judul metadata, dan warna header.
 * ==============================================================================
 */

$_GET['format'] = 'excel';
require_once __DIR__ . '/export.php';
