<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      penjelasan_rumus.php
 * @folder    views/analisis/korelasi_pearson/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Panel Pembuktian Rumus Ilmiah Korelasi Pearson & Uji t)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen pembuktian ilmiah dan transparansi metodologi (Academic Proof Panel).
 * Disajikan dalam bentuk panel lipat interaktif (Collapsible Accordion). Memuat
 * turunan rumus matematis Pearson Product-Moment (Karl Pearson, 1895), rumus
 * uji hipotesis t-statistik, rumus koefisien determinasi R^2, serta tabel pedoman
 * interpretasi kekuatan korelasi menurut Sugiyono (2018).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menerapkan prinsip Progressive Disclosure: menjaga antarmuka tetap bersih dan
 * tidak membingungkan pengguna awam, namun menyediakan bukti kalkulasi akademis
 * lengkap saat diuji oleh dosen pembimbing atau penguji skripsi.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- PANEL PEMBUKTIAN RUMUS ILMIAH PEARSON (COLLAPSIBLE ACCORDION)  -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
    
    <!-- Tombol Header Accordion Buka-Tutup -->
    <button onclick="toggleAccordion('pearsonFormulaContent')" class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50/80 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-xs font-black">
                <i class="fa-solid fa-square-root-variable"></i>
            </div>
            <div>
                <h4 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">
                    Pembuktian Rumus Matematis &amp; Metodologi Korelasi Pearson
                </h4>
                <p class="text-xs text-slate-500">Klik untuk membuka rincian formula Karl Pearson (1895), uji t-statistik, dan derajat signifikansi</p>
            </div>
        </div>
        <div id="pearsonFormulaIcon" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs transition-transform duration-300">
            <i class="fa-solid fa-chevron-down"></i>
        </div>
    </button>

    <!-- Konten Accordion Rumus Tersembunyi (Default: Tersembunyi agar Bersih) -->
    <div id="pearsonFormulaContent" class="hidden p-5 sm:p-7 pt-2 border-t border-slate-100 space-y-6 text-xs text-slate-600 bg-slate-50/40">
        
        <!-- 1. Formula Pearson Product-Moment -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
            <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-indigo-600 text-white flex items-center justify-center text-xs">1</span>
                <span>Rumus Koefisien Korelasi Pearson (r)</span>
            </div>
            <div class="p-3.5 bg-slate-900 text-emerald-400 rounded-xl font-mono text-center text-xs sm:text-sm overflow-x-auto">
                r = [ n &sum;(XY) - (&sum;X)(&sum;Y) ] / &radic;[ {n &sum;X&sup2; - (&sum;X)&sup2;} {n &sum;Y&sup2; - (&sum;Y)&sup2;} ]
            </div>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                Di mana <strong>n</strong> = jumlah sampel daerah (7 Kabupaten/Kota), <strong>X</strong> = jumlah fasilitas amenitas (hotel &amp; restoran), dan <strong>Y</strong> = jumlah kunjungan wisatawan nusantara resmi BPS.
            </p>
        </div>

        <!-- 2. Formula Uji Signifikansi t-Hitung -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
            <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-sky-600 text-white flex items-center justify-center text-xs">2</span>
                <span>Rumus Uji Hipotesis Signifikansi (t-Hitung)</span>
            </div>
            <div class="p-3.5 bg-slate-900 text-sky-400 rounded-xl font-mono text-center text-xs sm:text-sm overflow-x-auto">
                t = [ r &times; &radic;(n - 2) ] / &radic;(1 - r&sup2;)
            </div>
            <p class="text-[11px] text-slate-500 leading-relaxed">
                Derajat kebebasan <strong>df = n - 2 = 5</strong>. Hipotesis nol (H0) ditolak jika <strong>t-hitung &gt; t-tabel (&alpha; = 0.05, df = 5, t-tabel = 2.571)</strong>, membuktikan adanya korelasi nyata yang tidak terjadi karena faktor kebetulan acak.
            </p>
        </div>

        <!-- 3. Tabel Pedoman Interpretasi Koefisien Sugiyono -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
            <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-amber-600 text-white flex items-center justify-center text-xs">3</span>
                <span>Pedoman Interpretasi Tingkat Keeratan Korelasi (Sugiyono, 2018)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[11px]">
                    <thead class="bg-slate-100 text-slate-700 font-bold">
                        <tr>
                            <th class="p-2.5 rounded-l-lg">Interval Nilai (r)</th>
                            <th class="p-2.5">Kategori Keeratan Hubungan</th>
                            <th class="p-2.5 rounded-r-lg">Kondisi Pariwisata Babel Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="p-2 font-mono">0.00 - 0.199</td>
                            <td class="p-2">Sangat Rendah</td>
                            <td class="p-2 text-slate-400">-</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-mono">0.20 - 0.399</td>
                            <td class="p-2">Rendah</td>
                            <td class="p-2 text-slate-400">-</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-mono">0.40 - 0.599</td>
                            <td class="p-2">Sedang</td>
                            <td class="p-2 text-slate-400">-</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-mono">0.60 - 0.799</td>
                            <td class="p-2">Kuat</td>
                            <td class="p-2 text-slate-400">-</td>
                        </tr>
                        <tr class="bg-emerald-50/80 font-bold text-emerald-900">
                            <td class="p-2 font-mono">0.80 - 1.000</td>
                            <td class="p-2">Sangat Kuat</td>
                            <td class="p-2 text-emerald-700">&bull; r = +0.842 (Amenitas vs Kunjungan BPS)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- Skrip Pengendali Accordion Buka-Tutup -->
<script>
function toggleAccordion(contentId) {
    const content = document.getElementById(contentId);
    const icon = document.getElementById(contentId.replace('Content', 'Icon'));
    if (!content) return;

    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
}
</script>
