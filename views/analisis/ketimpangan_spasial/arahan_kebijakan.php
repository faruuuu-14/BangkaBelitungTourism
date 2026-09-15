<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      arahan_kebijakan.php
 * @folder    views/analisis/ketimpangan_spasial/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Arahan Kebijakan Strategis Pembangunan & Investasi Daerah)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen rekomendasi kebijakan strategis (Policy Brief Cards) hasil sintesis
 * dari evaluasi ketimpangan spasial. Merumuskan 3 pilar aksi nyata bagi
 * Pemerintah Daerah dan Dinas Kebudayaan & Pariwisata Provinsi Kepulauan Bangka Belitung:
 * 1. Insentif Investasi Akomodasi di Wilayah Defisit (Bangka Barat & Bangka Selatan)
 * 2. Optimalisasi Klaster Bahari & Daya Dukung Lingkungan (Belitung & Belitung Timur)
 * 3. Integrasi Rute Wisata Budaya & Suvenir Kriya (Pangkalpinang, Bangka & Bangka Tengah)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menjembatani luaran data statistik ke dalam rekomendasi kebijakan nyata (Actionable Insights)
 * yang dapat diajukan dalam sidang pengujian skripsi maupun forum musrenbang daerah.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 3 KARTU ARAHAN KEBIJAKAN STRATEGIS DAERAH (BPS EVIDENCE-BASED) -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    
    <!-- Kebijakan 1: Homestay & Wisata Transit Penyangga -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2.5">
        <div class="w-9 h-9 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold shadow-xs">
            <i class="fa-solid fa-house-chimney-window"></i>
        </div>
        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
            Homestay &amp; Buffer Penyangga (Babar &amp; Basel)
        </h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Data BPS menunjukkan &gt;94% wisatawan di Bangka Barat dan Bangka Selatan adalah pelancong harian (*day-tripper*). Alih-alih membangun hotel bintang yang berisiko sepi saat *low season*, fokuskan insentif pada standardisasi <strong>desa wisata dan homestay berbasis masyarakat</strong> guna menampung limpahan wisatawan saat puncak liburan Lebaran dan Nataru.
        </p>
    </div>

    <!-- Kebijakan 2: Destination Stayers & Geopark Belitung -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2.5">
        <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold shadow-xs">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
            Destination Stayers &amp; Carrying Capacity (Belitung)
        </h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Kabupaten Belitung merupakan wilayah dengan durasi tinggal tertinggi di Babel (<strong>RLMT 2.08 malam</strong>, 62.3% *stayers*). Fokus kebijakan adalah menjaga daya dukung lingkungan (*Tourism Carrying Capacity*) UNESCO Geopark serta mendistribusikan rute perjalanan ke Belitung Timur agar durasi tinggal meningkat hingga 2.50 malam.
        </p>
    </div>

    <!-- Kebijakan 3: MICE & Night Attraction Pangkalpinang-Bangka -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2.5">
        <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shadow-xs">
            <i class="fa-solid fa-champagne-glasses"></i>
        </div>
        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
            MICE &amp; Konversi Day-Trippers (Pangkalpinang &amp; Bangka)
        </h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Kabupaten Bangka mencatat volume kunjungan masif (89.505 jiwa) namun 91.9% pulang hari sehingga RLMT hanya 1.06 malam. Pemerintah perlu memperbanyak <strong>atraksi malam (*night market*), festival budaya, dan wisata belanja kriya Cual</strong> yang terintegrasi dengan MICE di Pangkalpinang agar pelancong harian terdorong memesan kamar hotel.
        </p>
    </div>

</div>
