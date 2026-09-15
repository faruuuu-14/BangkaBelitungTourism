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
<footer class="mt-auto bg-white border-t border-slate-200/80 py-4 sm:py-8 pb-20 lg:pb-8 text-sm text-slate-500 w-full max-w-full overflow-hidden">
    <div class="w-full max-w-[1720px] mx-auto px-3 sm:px-6 lg:px-10 min-w-0">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-4 sm:gap-6 pb-4 sm:pb-6 border-b border-slate-100">
            <!-- Identitas Platform -->
            <div class="space-y-1 text-center lg:text-left">
                <div class="flex items-center justify-center lg:justify-start gap-1.5 sm:gap-2">
                    <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-ocean-500 ring-4 ring-ocean-100"></span>
                    <span class="font-extrabold text-slate-800 text-xs sm:text-sm">Pariwisata Bangka Belitung</span>
                    <span class="text-[10px] sm:text-xs px-1.5 sm:px-2 py-0.5 rounded-md bg-ocean-50 text-ocean-700 font-medium border border-ocean-200">WebGIS &amp; BPS</span>
                </div>
                <p class="text-[11px] sm:text-xs text-slate-500 max-w-xl">
                    Website informasi dan pemetaan pariwisata Provinsi Kepulauan Bangka Belitung, mengintegrasikan peta 875 objek wisata dan fasilitas dengan data statistik kunjungan BPS.
                </p>
            </div>

            <!-- Kartu Identitas Pembuat Web -->
            <div class="flex flex-row items-center gap-3 p-2.5 sm:p-3 rounded-xl sm:rounded-2xl bg-white border border-slate-200/80 shadow-2xs text-left">
                <div class="relative flex-shrink-0">
                    <img src="assets/img/profil/fathurrahman.jpg" alt="Fathurrahman Safari Lubis" class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl object-cover object-top border border-slate-200 shadow-2xs">
                </div>
                <div class="space-y-0.5 min-w-0">
                    <div class="text-[10px] sm:text-[11px] font-semibold text-slate-400">
                        Dibuat oleh:
                    </div>
                    <div class="font-bold text-slate-800 text-xs sm:text-sm truncate">
                        Fathurrahman Safari Lubis
                    </div>
                    <p class="text-[10px] sm:text-xs text-slate-500 truncate">
                        NIM: 2411500025 &bull; <span class="text-slate-600">ISB Atma Luhur</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Baris Bawah / Hak Cipta & Tautan -->
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400 text-center sm:text-left">
            <div>
                &copy; <?= date('Y') ?> <strong>Fathurrahman Safari Lubis</strong> (NIM: 2411500025) &bull; Teknik Informatika, ISB Atma Luhur.
            </div>
            <div class="flex items-center gap-3 pr-0 sm:pr-12 lg:pr-16">
                <button onclick="openGuideModal('gtab-policy')" class="hover:text-ocean-600 font-medium transition-colors">Tentang Pembuat</button>
                <span>&bull;</span>
                <button onclick="openGuideModal('gtab-overview')" class="hover:text-ocean-600 font-medium transition-colors">Panduan Website</button>
            </div>
        </div>
    </div>
</footer>

<!-- Tombol Melayang Kembali ke Atas (Back to Top FAB) -->
<button id="backToTopBtn" onclick="scrollToTop()" class="fixed bottom-20 lg:bottom-8 right-4 lg:right-8 z-40 w-11 h-11 rounded-2xl bg-ocean-600 hover:bg-ocean-700 text-white shadow-lg shadow-ocean-600/30 flex items-center justify-center transition-all duration-300 opacity-0 pointer-events-none translate-y-3 cursor-pointer group" title="Kembali ke Atas Halaman">
    <i class="fa-solid fa-arrow-up text-sm group-hover:-translate-y-0.5 transition-transform"></i>
</button>

<script>
window.addEventListener('scroll', function() {
    const btn = document.getElementById('backToTopBtn');
    if (btn) {
        if (window.scrollY > 300) {
            btn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-3');
            btn.classList.add('opacity-100', 'pointer-events-auto', 'translate-y-0');
        } else {
            btn.classList.remove('opacity-100', 'pointer-events-auto', 'translate-y-0');
            btn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-3');
        }
    }
});

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>

<?php include __DIR__ . '/guide_modal.php'; ?>
<?php include __DIR__ . '/modal_qr.php'; ?>
</body>
</html>
