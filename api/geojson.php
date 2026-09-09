<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      geojson.php
 * @folder    api/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (API Layanan Data Spasial Format GeoJSON Standar RFC 7946)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Menyediakan endpoint data spasial terbuka (Open Geospatial Data Service) dalam
 * format GeoJSON FeatureCollection berstandar OGC RFC 7946. File ini dapat langsung
 * diimpor ke dalam aplikasi SIG profesional seperti QGIS, ArcGIS Desktop/Pro,
 * Mapbox, Leaflet, maupun Google Earth.
 * 
 * KETERKAITAN ALUR:
 * - Mengakses basis data: app/database.php (tabel tempat_wisata)
 * - Dipanggil oleh tombol: "Unduh GeoJSON (QGIS)" di views/peta/panel_filter.php
 * ==============================================================================
 */

$_GET['format'] = 'geojson';
require_once __DIR__ . '/export.php';
