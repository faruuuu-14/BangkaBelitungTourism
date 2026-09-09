<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      galeri_wilayah.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Galeri Berimbang 12 Objek Wisata 7 Kabupaten/Kota & Budaya)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen galeri visual interaktif pada beranda yang menyajikan 12 kartu foto
 * otentik berimbang dari seluruh 7 Kabupaten/Kota di Provinsi Kepulauan Bangka
 * Belitung (Kab. Bangka, Bangka Barat, Bangka Tengah, Bangka Selatan, Belitung,
 * Belitung Timur, dan Kota Pangkalpinang) serta 4 pilar pusaka budaya (Kain Cual,
 * Alat Musik Dambus, Busana Paksian, dan Tradisi Nganggung).
 * Dilengkapi pemicu interaktif penampil foto layar penuh (Lightbox Modal).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php
 * - Mengakses aset: assets/img/*.jpg
 * - Memanggil fungsi JS: openPhotoModal() di modal_dialog.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menjamin keadilan representasi wilayah geografis dan kekayaan budaya Babel,
 * memberikan daya tarik estetika modern, serta memenuhi prinsip ergonomi IMK.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 4. GALERI PESONA 7 KABUPATEN/KOTA & PUSAKA BUDAYA BABEL       -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-6">
    
    <!-- Kepala Bagian Galeri -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/80 mb-1.5">
                <i class="fa-solid fa-gem text-[11px]"></i>
                <span>Pesona Lengkap 7 Kabupaten/Kota &amp; Warisan Budaya Babel</span>
            </div>
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                Wisata Bahari, Geopark, Seni Kriya &amp; Tradisi Seluruh Daerah
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Representasi berimbang dari Bangka, Bangka Barat, Bangka Tengah, Bangka Selatan, Belitung, Belitung Timur, dan Kota Pangkalpinang.
            </p>
        </div>
        <a href="index.php?page=peta" class="self-start sm:self-auto text-xs font-bold text-ocean-600 hover:text-ocean-700 flex items-center gap-1.5 transition-colors">
            <span>Eksplorasi di Peta Spasial</span>
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <!-- Grid 12 Kartu Galeri Berimbang (4 Kolom pada Desktop) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- 1. Pantai Tikus Emas (Kab. Bangka) -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/pantai_tikus_emas.jpg" alt="Pantai Tikus Emas Bangka" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-slate-950 shadow-xs inline-block">
                    <i class="fa-solid fa-umbrella-beach mr-1"></i> Kab. Bangka
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-amber-300 transition-colors line-clamp-1">
                    Pantai Tikus Emas
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Pasir emas landai, ikon patung tikus emas, dan kemegahan Pagoda Puri Tri Agung di pesisir Sungailiat.
                </p>
                <button onclick="openPhotoModal('assets/img/pantai_tikus_emas.jpg', 'Pantai Tikus Emas &bull; Sungailiat, Kab. Bangka', 'Destinasi bahari keluarga terfavorit di Sungailiat dengan pasir emas yang landai, pohon cemara rindang, dan pagoda megah berlatar laut biru.')" class="pt-1 text-[11px] font-bold text-amber-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- 2. Pantai Tanjung Ular (Kab. Bangka Barat) -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/pantai_tanjung_ular.jpg" alt="Pantai Tanjung Ular Bangka Barat" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-tower-observation mr-1"></i> Kab. Bangka Barat
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-sky-300 transition-colors line-clamp-1">
                    Pantai Tanjung Ular
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Mercusuar putih menjulang di atas bebatuan karang eksotik Muntok, saksi sejarah navigasi Selat Bangka.
                </p>
                <button onclick="openPhotoModal('assets/img/pantai_tanjung_ular.jpg', 'Pantai Tanjung Ular &bull; Muntok, Kab. Bangka Barat', 'Pantai karang dengan mercusuar navigasi putih yang gagah berdiri di pesisir barat Bangka, menghadap langsung perairan Selat Bangka yang memesona.')" class="pt-1 text-[11px] font-bold text-sky-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- 3. Danau Kaolin / Air Biru (Kab. Bangka Tengah) -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/danau_kaolin_koba.jpg" alt="Danau Kaolin Air Biru Koba Bangka Tengah" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-water mr-1"></i> Kab. Bangka Tengah
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-cyan-300 transition-colors line-clamp-1">
                    Danau Kaolin (Air Biru)
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Kawah danau toska muda bertebing putih kapur di Koba, lanskap geopark pascatambang yang fotogenik.
                </p>
                <button onclick="openPhotoModal('assets/img/danau_kaolin_koba.jpg', 'Danau Kaolin (Air Biru) &bull; Koba, Kab. Bangka Tengah', 'Danau toska menakjubkan yang terbentuk dari ceruk pascatambang kaolin dengan perbukitan kapur putih bersih.')" class="pt-1 text-[11px] font-bold text-cyan-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- 4. Pantai Batu Perahu (Kab. Bangka Selatan) -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/pantai_batu_perahu.jpg" alt="Pantai Batu Perahu Toboali Bangka Selatan" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-ship mr-1"></i> Kab. Bangka Selatan
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-orange-300 transition-colors line-clamp-1">
                    Pantai Batu Perahu
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Formasi batuan granit menyerupai haluan perahu nelayan raksasa dan siluet matahari terbenam Toboali.
                </p>
                <button onclick="openPhotoModal('assets/img/pantai_batu_perahu.jpg', 'Pantai Batu Perahu &bull; Toboali, Kab. Bangka Selatan', 'Batu granit unik menyerupai perahu di pesisir Toboali, titik terbaik menikmati matahari terbenam di selatan Bangka.')" class="pt-1 text-[11px] font-bold text-orange-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- 5. Pantai Tanjung Tinggi (Kab. Belitung) -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/pantai_tanjung_tinggi.jpg" alt="Pantai Tanjung Tinggi Belitung" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-mountain mr-1"></i> Kab. Belitung
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-emerald-300 transition-colors line-clamp-1">
                    Pantai Tanjung Tinggi
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Ikon batuan granit purba raksasa berumur ratusan juta tahun di kawasan UNESCO Global Geopark Belitong.
                </p>
                <div class="flex items-center justify-between pt-1">
                    <button onclick="openPhotoModal('assets/img/pantai_tanjung_tinggi.jpg', 'Pantai Tanjung Tinggi &bull; Sijuk, Kab. Belitung', 'Geosite utama UNESCO Global Geopark Belitong dengan formasi batu granit raksasa di atas pasir putih lembut.', 'Pantai Tanjung Tinggi')" class="text-[11px] font-bold text-emerald-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                    <a href="index.php?page=peta&kabupaten=Kabupaten+Belitung" class="text-[10px] px-2 py-0.5 rounded-lg bg-white/15 hover:bg-white/30 text-white font-bold transition flex items-center gap-1" title="Buka Peta Kabupaten Belitung">
                        <i class="fa-solid fa-map"></i> Peta
                    </a>
                </div>
            </div>
        </div>

        <!-- 6. SD Laskar Pelangi (Kab. Belitung Timur) -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/laskar_pelangi_gantong.jpg" alt="SD Laskar Pelangi Gantong" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-book-open mr-1"></i> Kab. Belitung Timur
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-indigo-300 transition-colors line-clamp-1">
                    SD Laskar Pelangi
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Replika sekolah kayu legendaris dan Rumah Kece di Gantong, simbol perjuangan literasi karya Andrea Hirata.
                </p>
                <div class="flex items-center justify-between pt-1">
                    <button onclick="openPhotoModal('assets/img/laskar_pelangi_gantong.jpg', 'SD Laskar Pelangi &bull; Gantong, Kab. Belitung Timur', 'Replika sekolah kayu bersejarah karya novelis Andrea Hirata yang menginspirasi jutaan insan tentang kekuatan cita-cita.', 'Laskar Pelangi')" class="text-[11px] font-bold text-indigo-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                    <a href="index.php?page=peta&kabupaten=Kabupaten+Belitung+Timur" class="text-[10px] px-2 py-0.5 rounded-lg bg-white/15 hover:bg-white/30 text-white font-bold transition flex items-center gap-1" title="Buka Peta Kabupaten Belitung Timur">
                        <i class="fa-solid fa-map"></i> Peta
                    </a>
                </div>
            </div>
        </div>

        <!-- 7. Museum Timah Indonesia (Kota Pangkalpinang) -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/museum_timah_babel.jpg" alt="Museum Timah Indonesia" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-landmark mr-1"></i> Kota Pangkalpinang
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-violet-300 transition-colors line-clamp-1">
                    Museum Timah Indonesia
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Gedung kolonial cagar budaya di Pangkalpinang, museum pertimahan satu-satunya di Asia Tenggara.
                </p>
                <div class="flex items-center justify-between pt-1">
                    <button onclick="openPhotoModal('assets/img/museum_timah_babel.jpg', 'Museum Timah Indonesia &bull; Kota Pangkalpinang', 'Gedung cagar budaya pertambangan timah dunia satu-satunya di Asia Tenggara yang berdiri megah di jantung ibukota Pangkalpinang.', 'Museum Timah')" class="text-[11px] font-bold text-violet-300 hover:text-white flex items-center gap-1">
                        <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                    </button>
                    <a href="index.php?page=peta&kabupaten=Kota+Pangkalpinang" class="text-[10px] px-2 py-0.5 rounded-lg bg-white/15 hover:bg-white/30 text-white font-bold transition flex items-center gap-1" title="Buka Peta Kota Pangkalpinang">
                        <i class="fa-solid fa-map"></i> Peta
                    </a>
                </div>
            </div>
        </div>

        <!-- 8. Pulau Lengkuas & Mercusuar 1882 -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/pantai_lengkuas.jpg" alt="Pulau Lengkuas" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-sailboat mr-1"></i> Kepulauan Belitung
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-teal-300 transition-colors line-clamp-1">
                    Pulau Lengkuas 1882
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Mercusuar kolonial Belanda 18 lantai yang masih kokoh aktif, dikelilingi laut toska sebening kristal.
                </p>
                <button onclick="openPhotoModal('assets/img/pantai_lengkuas.jpg', 'Pulau Lengkuas &bull; Kepulauan Belitung', 'Ikon bahari legendaris dengan mercusuar peninggalan Belanda tahun 1882, terumbu karang dan taman laut dangkal.')" class="pt-1 text-[11px] font-bold text-teal-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- 9. Kain Tenun Cual Bangka -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/kain_cual_bangka.jpg" alt="Kain Cual Bangka" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-scroll mr-1"></i> Tekstil Tradisional
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-rose-300 transition-colors line-clamp-1">
                    Kain Tenun Cual Bangka
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Tenun sutra tradisional bermotif Bebek Berseluk dengan teknik ikat lungsi dan benang emas murni.
                </p>
                <button onclick="openPhotoModal('assets/img/kain_cual_bangka.jpg', 'Kain Cual Tradisional Bangka', 'Tenunan khas Bangka Belitung dengan teknik tenun ikat lungsi yang rumit dan benang emas murni.')" class="pt-1 text-[11px] font-bold text-rose-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- 10. Alat Musik Tradisional Dambus -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/alat_musik_dambus.jpg" alt="Alat Musik Dambus Bangka" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-500 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-guitar mr-1"></i> Alat Musik Petik
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-purple-300 transition-colors line-clamp-1">
                    Dambus Melayu Bangka
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Instrumen petik berdawai berukir kepala kijang (rusa), melodi utama musik tradisional Melayu Bangka.
                </p>
                <button onclick="openPhotoModal('assets/img/alat_musik_dambus.jpg', 'Alat Musik Tradisional Dambus', 'Alat musik petik khas Melayu Bangka Belitung yang memiliki ujung ukiran kepala rusa (kijang).')" class="pt-1 text-[11px] font-bold text-purple-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- 11. Busana Adat Paksian -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/budaya_tari_paksian.jpg" alt="Pakaian Adat Paksian" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-600 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-crown mr-1"></i> Busana Adat
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-amber-300 transition-colors line-clamp-1">
                    Busana Adat Paksian
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Mahkota emas kembang goyang dan baju kurung sutra, lambang keanggunan akulturasi budaya Babel.
                </p>
                <button onclick="openPhotoModal('assets/img/budaya_tari_paksian.jpg', 'Busana Pengantin &amp; Mahkota Paksian', 'Busana kebesaran adat perkawinan Bangka Belitung perpaduan harmonis budaya Melayu, Tionghoa, dan Arab.')" class="pt-1 text-[11px] font-bold text-amber-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

        <!-- 12. Tradisi Nganggung Sepintu Sedulang -->
        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 bg-slate-900 border border-slate-200 flex flex-col justify-end min-h-[260px]">
            <img src="assets/img/budaya_nganggung.jpg" alt="Tradisi Nganggung" 
                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 opacity-90">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
            <div class="relative z-10 p-4 space-y-1.5 text-white">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-600 text-white shadow-xs inline-block">
                    <i class="fa-solid fa-hand-holding-heart mr-1"></i> Kearifan Lokal
                </span>
                <h3 class="text-sm font-extrabold leading-snug text-white group-hover:text-rose-300 transition-colors line-clamp-1">
                    Tradisi Nganggung
                </h3>
                <p class="text-slate-300 text-[11px] line-clamp-2 leading-relaxed">
                    Tradisi gotong royong membawa dulang bertudung saji merah untuk dinikmati bersama di balai desa.
                </p>
                <button onclick="openPhotoModal('assets/img/budaya_nganggung.jpg', 'Tradisi Nganggung Sepintu Sedulang', 'Tradisi gotong royong membawa dulang makanan bertutup tudung saji merah sebagai wujud kebersamaan abadi masyarakat Babel.')" class="pt-1 text-[11px] font-bold text-rose-300 hover:text-white flex items-center gap-1">
                    <span>Lihat Foto Layar Penuh</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </button>
            </div>
        </div>

    </div>
</div>
