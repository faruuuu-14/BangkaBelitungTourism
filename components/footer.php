<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      footer.php
 * @folder    components/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Kaki Halaman Global & Informasi Pengembang)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen kaki halaman (Footer) global. Menampilkan informasi ringkas platform
 * intelijen spasial pariwisata Bangka Belitung, kartu profil pengembang resmi
 * (Fathurrahman Safari Lubis, NIM: 2411500025 - ISB Atma Luhur), pernyataan
 * hak cipta, dan pemanggil modal panduan sistem (guide_modal.php).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: index.php (di baris paling akhir setiap halaman)
 * - Memuat file pembantu: components/guide_modal.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menutup struktur DOM HTML secara rapi (tag </body> dan </html>), memberikan
 * identitas kepemilikan karya akademis yang sah dan profesional.
 * ==============================================================================
 */
?>
<footer class="mt-auto bg-white border-t border-slate-200/80 py-6 sm:py-8 pb-24 lg:pb-8 text-sm text-slate-500 w-full max-w-full overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full min-w-0">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <!-- Identitas Platform -->
            <div class="space-y-1.5 text-center lg:text-left">
                <div class="flex items-center justify-center lg:justify-start gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-ocean-500 ring-4 ring-ocean-100"></span>
                    <span class="font-extrabold text-slate-800 text-sm">Pariwisata Bangka Belitung Hub</span>
                    <span class="text-xs px-2 py-0.5 rounded-md bg-ocean-50 text-ocean-700 font-bold border border-ocean-200">SDSS v2.4</span>
                </div>
                <p class="text-xs text-slate-500 max-w-xl">
                    Sistem Pendukung Keputusan Spasial &amp; Ekonometrika Pariwisata Berkelanjutan berbasis integrasi 875 Objek Wisata/Amenitas Spasial dan 112 Baris Data Kunjungan BPS Provinsi Kepulauan Bangka Belitung.
                </p>
            </div>

            <!-- Kartu Hak Cipta & Pengembang -->
            <div class="flex flex-col sm:flex-row items-center gap-3.5 p-3 rounded-2xl bg-gradient-to-r from-slate-50 via-white to-slate-50 border border-slate-200 shadow-xs text-center sm:text-left">
                <div class="relative flex-shrink-0">
                    <img src="assets/img/fathurrahman.jpg" alt="Fathurrahman Safari Lubis" class="w-12 h-12 rounded-xl object-cover object-center border-2 border-ocean-500 shadow-xs shadow-ocean-500/20" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-12 h-12 rounded-xl bg-gradient-to-br from-ocean-600 to-slate-900 text-white flex items-center justify-center font-black text-sm\'>FSL</div>';">
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-[8px] text-white" title="Pengembang Terverifikasi">
                        <i class="fa-solid fa-check"></i>
                    </span>
                </div>
                <div class="space-y-0.5">
                    <div class="flex items-center justify-center sm:justify-start gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pengembang &amp; Arsitek Sistem:</span>
                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded bg-emerald-50 text-emerald-700 text-[9px] font-extrabold border border-emerald-200">
                            <i class="fa-solid fa-circle-check text-[9px]"></i> Terverifikasi
                        </span>
                    </div>
                    <div class="font-extrabold text-slate-900 text-xs sm:text-sm">
                        Fathurrahman Safari Lubis <span class="text-ocean-600 font-bold text-xs">(NIM: 2411500025)</span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium">
                        Mahasiswa Teknik Informatika &bull; <strong class="text-slate-700">Institut Sains dan Bisnis Atma Luhur</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Baris Bawah / Hak Cipta -->
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400 text-center sm:text-left">
            <div>
                Hak Cipta Dilindungi Undang-Undang &copy; <?= date('Y') ?> <strong>Fathurrahman Safari Lubis</strong>. Seluruh hak cipta arsitektur sistem, formula komputasi, dan rancang bangun platform dilindungi.
            </div>
            <div class="flex items-center gap-3">
                <button onclick="openGuideModal('gtab-policy')" class="hover:text-ocean-600 font-semibold transition-colors">Profil Pengembang</button>
                <span>&bull;</span>
                <button onclick="openGuideModal('gtab-overview')" class="hover:text-ocean-600 font-semibold transition-colors">Panduan Sistem</button>
                <span>&bull;</span>
                <span class="font-mono text-[11px] text-slate-400">ISB-AL-TI-2411500025</span>
            </div>
        </div>
    </div>
</footer>

<?php include __DIR__ . '/guide_modal.php'; ?>
</body>
</html>
