<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      header.php
 * @folder    components/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Kepala Halaman Global, Pustaka CDN & Gaya Visual)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen kepala dokumen HTML (Head Header). Memuat deklarasi metadata dokumen,
 * atribusi kepemilikan karya akademis pengembang, pustaka eksternal (Tailwind CSS,
 * Google Font Plus Jakarta Sans, FontAwesome 6, Leaflet WebGIS, Leaflet Heatmap,
 * dan Chart.js), serta definisi tema warna kepariwisataan Kepulauan Bangka Belitung.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (di baris pertama perutean)
 * - Menyediakan fondasi visual bagi seluruh tampilan di views/
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menyediakan kerangka HTML5 standar dan menjamin seluruh pustaka visual UI/UX
 * terpasang secara konsisten di setiap fitur aplikasi.
 * ==============================================================================
 */
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title><?= $pageTitle ?? 'Sistem Intelijen Pariwisata Bangka Belitung' ?></title>
    
    <!-- Meta & Authorship Attribution -->
    <meta name="author" content="Fathurrahman Safari Lubis (NIM: 2411500025)">
    <meta name="developer" content="Fathurrahman Safari Lubis - Mahasiswa Teknik Informatika, Institut Sains dan Bisnis Atma Luhur">
    <meta name="institution" content="Institut Sains dan Bisnis Atma Luhur Pangkalpinang">
    <meta name="nim" content="2411500025">
    <meta name="program-studi" content="Teknik Informatika">
    <meta name="system-architect" content="Fathurrahman Safari Lubis (2411500025)">
    <meta name="copyright" content="Hak Cipta Terlindungi (c) 2026 Fathurrahman Safari Lubis. All Rights Reserved.">
    
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Heatmap Plugin -->
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Generator Kode QR Mandiri (Offline / Instant QR Code Engine) -->
    <script src="assets/qrcode.min.js"></script>

    <!-- Tailwind CSS CDN dengan Konfigurasi Kustom -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        ocean: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        emerald: {
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        },
                        coral: {
                            500: '#f97316',
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script>
    <script>
        // Inisialisasi Tema Gelap/Terang Instan (Mencegah Flashing Layar)
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Watermark Sistem & Hak Cipta Pengembang
        console.log("%c=======================================================", "color:#0284c7;font-weight:bold;");
        console.log("%c BABEL TOURISM SPATIAL INTELLIGENCE & DECISION SUPPORT SYSTEM %c", "background:#0284c7;color:#ffffff;font-weight:bold;padding:4px 8px;border-radius:4px;", "");
        console.log("%c Arsitek & Pengembang : Fathurrahman Safari Lubis %c", "background:#0f172a;color:#38bdf8;font-weight:bold;padding:3px 6px;border-radius:3px;", "");
        console.log("%c NIM                 : 2411500025 %c", "color:#0f172a;font-weight:bold;", "");
        console.log("%c Program Studi       : Teknik Informatika %c", "color:#475569;", "");
        console.log("%c Institusi           : Institut Sains dan Bisnis Atma Luhur Pangkalpinang %c", "color:#475569;", "");
        console.log("%c Status Sistem       : Enterprise Spatial Decision Support System (SDSS) v2.4 %c", "color:#10b981;font-weight:bold;", "");
        console.log("%c Hak Cipta Dilindungi Undang-Undang (c) 2026 Fathurrahman Safari Lubis %c", "color:#94a3b8;font-style:italic;", "");
        console.log("%c=======================================================", "color:#0284c7;font-weight:bold;");
    </script>
    <style>
        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }
        *, ::before, ::after {
            box-sizing: border-box;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            transition: background-color 0.25s ease, color 0.25s ease;
        }
        .dark body {
            background-color: #0b0f19;
            color: #f1f5f9;
        }
        .dark .bg-white {
            background-color: #111827 !important;
            color: #f3f4f6;
            border-color: #1f2937;
        }
        .dark .bg-slate-50 {
            background-color: #1a2234 !important;
            color: #f3f4f6;
            border-color: #26334d;
        }
        .dark .border-slate-200 {
            border-color: #1f2937 !important;
        }
        .dark .border-slate-100 {
            border-color: #1e293b !important;
        }
        .dark .text-slate-900 {
            color: #f8fafc !important;
        }
        .dark .text-slate-800 {
            color: #f1f5f9 !important;
        }
        .dark .text-slate-700 {
            color: #cbd5e1 !important;
        }
        .dark .text-slate-600 {
            color: #94a3b8 !important;
        }
        .dark .text-slate-500 {
            color: #94a3b8 !important;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth mobile scroll without scrollbars for horizontal pill bars */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .touch-scroll {
            -webkit-overflow-scrolling: touch;
        }

        /* ========================================================== */
        /* PRINT STYLING STANDAR RESMI LAPORAN KEMENPAREKRAF / SDSS   */
        /* ========================================================== */
        @media print {
            /* 1. Mencegah bug looping halaman tak terhingga di Chromium */
            html, body {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
                max-height: none !important;
                overflow: visible !important;
                overflow-x: visible !important;
                overflow-y: visible !important;
                display: block !important;
                position: static !important;
                background: white !important;
                color: #0f172a !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* 2. Sembunyikan elemen navigasi, tombol, footer, dan bottom nav */
            header, nav, footer, .tab-btn, button, .no-print, #mobileBottomNav, #guideModal {
                display: none !important;
            }

            /* 3. Pastikan elemen tersembunyi tidak bocor ke hasil cetakan */
            .hidden, [style*="display: none"] {
                display: none !important;
            }

            /* 4. Kontainer utama cetak */
            main {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                overflow: visible !important;
            }

            /* 5. Khusus cetak Lembar Rekomendasi Resmi (Policy Brief) */
            #policyBriefModal.hidden {
                display: none !important;
            }
            body.printing-brief main > *:not(#policyBriefModal) {
                display: none !important;
            }
            body.printing-brief #policyBriefModal {
                position: static !important;
                background: white !important;
                backdrop-filter: none !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                max-height: none !important;
                height: auto !important;
                overflow: visible !important;
            }
            body.printing-brief #policyBriefModal > div {
                max-width: 100% !important;
                max-height: none !important;
                height: auto !important;
                box-shadow: none !important;
                border: none !important;
                overflow: visible !important;
            }
            body.printing-brief #policyBriefModal .p-6.border-b,
            body.printing-brief #policyBriefModal .p-4.border-t {
                display: none !important;
            }
            body.printing-brief #printableBriefArea {
                overflow: visible !important;
                max-height: none !important;
                height: auto !important;
                padding: 0 !important;
            }

            /* 6. Atur pemisahan halaman agar rapi */
            tr, .avoid-break {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="text-slate-800 flex flex-col min-h-screen">
