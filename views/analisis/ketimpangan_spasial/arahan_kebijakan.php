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
<!-- 3 KARTU ARAHAN KEBIJAKAN STRATEGIS DAERAH                      -->
<!-- ============================================================== -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    
    <!-- Kebijakan 1: Insentif Investasi Wilayah Defisit -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2.5">
        <div class="w-9 h-9 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold shadow-xs">
            <i class="fa-solid fa-hand-holding-dollar"></i>
        </div>
        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
            Insentif Investasi Akomodasi Wilayah Defisit
        </h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Prioritaskan kemudahan izin dan insentif fiskal penanaman modal hotel/homestay di Bangka Barat (Muntok) dan Bangka Selatan (Toboali) untuk menekan angka defisit fasilitas penunjang pantai.
        </p>
    </div>

    <!-- Kebijakan 2: Daya Dukung Geopark Belitung -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2.5">
        <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold shadow-xs">
            <i class="fa-solid fa-seedling"></i>
        </div>
        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
            Pengelolaan Daya Dukung UNESCO Geopark
        </h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Kabupaten Belitung telah memiliki rasio fasilitas yang sangat baik. Fokus kebijakan beralih ke pengelolaan daya dukung lingkungan (*Tourism Carrying Capacity*) agar kelestarian terumbu karang dan granit tetap terjaga.
        </p>
    </div>

    <!-- Kebijakan 3: Wisata Budaya & Kriya Cual -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-2.5">
        <div class="w-9 h-9 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold shadow-xs">
            <i class="fa-solid fa-route"></i>
        </div>
        <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
            Penguatan Rute Koridor Budaya &amp; Kuliner
        </h4>
        <p class="text-xs text-slate-500 leading-relaxed">
            Membangun koridor terpadu antara Pangkalpinang (Museum Timah), Bangka (Sentra Cual &amp; Nganggung), dan Belitung Timur (Literasi Laskar Pelangi) untuk meningkatkan lama tinggal wisatawan (*length of stay*).
        </p>
    </div>

</div>
