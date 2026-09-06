<footer class="mt-auto bg-white border-t border-slate-200/80 py-6 sm:py-8 pb-24 lg:pb-8 text-sm text-slate-500 w-full max-w-full overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full min-w-0">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <!-- Identitas Platform -->
            <div class="space-y-1.5 text-center lg:text-left">
                <div class="flex items-center justify-center lg:justify-start gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-ocean-500 ring-4 ring-ocean-100"></span>
                    <span class="font-extrabold text-slate-800 text-sm">Bangka Belitung Tourism Intelligence</span>
                    <span class="text-xs px-2 py-0.5 rounded-md bg-ocean-50 text-ocean-700 font-bold border border-ocean-200">SDSS v2.4</span>
                </div>
                <p class="text-xs text-slate-500 max-w-xl">
                    Spatial Decision Support System &amp; Ekonometrika Pariwisata Berkelanjutan berbasis integrasi 875 POI Spasial dan 112 Baris Data Makro BPS Provinsi Kepulauan Bangka Belitung.
                </p>
            </div>

            <!-- Kartu Hak Cipta & Pengembang -->
            <div class="flex flex-col sm:flex-row items-center gap-3.5 p-3 rounded-2xl bg-gradient-to-r from-slate-50 via-white to-slate-50 border border-slate-200 shadow-sm text-center sm:text-left">
                <div class="relative flex-shrink-0">
                    <img src="assets/img/fathurrahman.jpg" alt="Fathurrahman Safari Lubis" class="w-12 h-12 rounded-xl object-cover object-center border-2 border-ocean-500 shadow-md shadow-ocean-500/20" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-12 h-12 rounded-xl bg-gradient-to-br from-ocean-600 to-slate-900 text-white flex items-center justify-center font-black text-sm\'>FSL</div>';">
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-[8px] text-white" title="Verified Developer">
                        <i class="fa-solid fa-check"></i>
                    </span>
                </div>
                <div class="space-y-0.5">
                    <div class="flex items-center justify-center sm:justify-start gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Arsitek Sistem &amp; Pengembang:</span>
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

        <!-- Baris Bawah / Disclaimer & Copyright -->
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
