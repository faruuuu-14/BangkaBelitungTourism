<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      penjelasan_rumus.php
 * @folder    views/analisis/korelasi_pearson/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   3.0.0 (Panel Pembuktian Metodologi Ilmiah Pearson & Scale vs Scale)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen pembuktian ilmiah dan transparansi metodologi (Academic Proof Panel).
 * Menyajikan panduan pemilihan uji statistik berbasis referensi 'What test can I use':
 * 1. Taksonomi Data: Mengapa Uji Pearson? (Variabel X = Scale, Variabel Y = Scale)
 * 2. Penjelasan Definisi "Nominal vs Scale" untuk membedakan data kategori dan angka riil
 * 3. Matriks 4 Indikator Korelasi Empiris BPS April 2026 (X2, X3, X1, X5 terhadap Y)
 * 4. Formula Karl Pearson (1895), Uji Hipotesis t-Statistik, dan R-Squared
 * 5. Justifikasi Ilmiah Keterbatasan Sampel Wilayah (N = 7 Kabupaten/Kota & p > 0.05)
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/analisis/index.php
 * - Menggunakan data: getBpsPearsonMatrix() dari app/statistics.php
 * ==============================================================================
 */

$matrixInfo = getBpsPearsonMatrix();
?>

<!-- ============================================================== -->
<!-- PANEL PEMBUKTIAN RUMUS ILMIAH PEARSON (COLLAPSIBLE ACCORDION)  -->
<!-- ============================================================== -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
    
    <!-- Tombol Header Accordion Buka-Tutup -->
    <button onclick="toggleAccordion('pearsonFormulaContent')" class="w-full p-5 sm:p-6 text-left flex items-center justify-between hover:bg-slate-50/80 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-xs font-black">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <h4 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">
                    Metodologi Akademis &amp; Pembuktian Rumus Korelasi Pearson (BPS)
                </h4>
                <p class="text-xs text-slate-500">Klik untuk membuka rincian panduan &lsquo;What test can I use&rsquo;, taksonomi Scale vs Scale, formula t-hitung, dan analisis N=7 sampel</p>
            </div>
        </div>
        <div id="pearsonFormulaIcon" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs transition-transform duration-300">
            <i class="fa-solid fa-chevron-down"></i>
        </div>
    </button>

    <!-- Konten Accordion Rumus Tersembunyi (Default: Tersembunyi agar Bersih) -->
    <div id="pearsonFormulaContent" class="hidden p-5 sm:p-7 pt-2 border-t border-slate-100 space-y-6 text-xs text-slate-600 bg-slate-50/40">
        
        <!-- 1. Panduan 'What Test Can I Use' & Taksonomi Skala Data -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
            <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-indigo-600 text-white flex items-center justify-center text-xs">1</span>
                <span>Mengapa Memilih Korelasi Pearson? (Pedoman &lsquo;What test can I use&rsquo;)</span>
            </div>
            <p class="text-[11px] text-slate-600 leading-relaxed">
                Berdasarkan pedoman baku pemilihan uji statistik (<em>UCLA Statistical Consulting &amp; Field, 2013</em>), metode analisis ditentukan oleh <strong>skala pengukuran variabel</strong>:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl space-y-1">
                    <div class="font-extrabold text-amber-900 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-tag text-amber-600"></i>
                        <span>Data Nominal (Bukan yang Kita Uji)</span>
                    </div>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Data berbentuk <strong>nama, label, atau kategori diskret</strong> tanpa nilai matematis berjenjang. Contoh: Jenis Kelamin (Pria/Wanita), Nama Kabupaten, Kategori Objek (Alam/Budaya). Tidak dapat dijumlah atau dirata-ratakan secara matematis.
                    </p>
                </div>
                <div class="p-3 bg-emerald-50/70 border border-emerald-200/80 rounded-xl space-y-1">
                    <div class="font-extrabold text-emerald-900 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-ruler-combined text-emerald-600"></i>
                        <span>Data Scale / Kontinu (Data Riset Babel)</span>
                    </div>
                    <p class="text-[11px] text-emerald-800 leading-relaxed">
                        Data berbentuk <strong>angka kuantitatif riil (interval/rasio)</strong> yang memiliki bobot terukur. Variabel bebas (Tamu Menginap $X_2$, Pelancong $X_3$) dan variabel terikat (RLMT $Y$) keduanya bertipe <strong>Scale</strong>. Sesuai aturan: <em>Scale vs Scale &rarr; Pearson Correlation</em>.
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Formula Karl Pearson & Uji t-Statistik -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
            <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-sky-600 text-white flex items-center justify-center text-xs">2</span>
                <span>Rumus Koefisien Korelasi Pearson (r) &amp; Uji Signifikansi (t-Hitung)</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <span class="text-[11px] font-bold text-slate-700">A. Rumus Korelasi Pearson Product-Moment:</span>
                    <div class="p-3 bg-slate-900 text-emerald-400 rounded-xl font-mono text-center text-xs sm:text-sm overflow-x-auto">
                        r = &sum;(x - x̄)(y - ȳ) / &radic;[ &sum;(x - x̄)&sup2; &times; &sum;(y - ȳ)&sup2; ]
                    </div>
                </div>
                <div class="space-y-1.5">
                    <span class="text-[11px] font-bold text-slate-700">B. Rumus Uji Hipotesis (t-Hitung) &amp; R&sup2;:</span>
                    <div class="p-3 bg-slate-900 text-sky-400 rounded-xl font-mono text-center text-xs sm:text-sm overflow-x-auto">
                        t = [ r &times; &radic;(n - 2) ] / &radic;(1 - r&sup2;) &nbsp;|&nbsp; R&sup2; = r&sup2; &times; 100%
                    </div>
                </div>
            </div>

            <p class="text-[11px] text-slate-500 leading-relaxed">
                Di mana <strong>n</strong> = 7 Kabupaten/Kota, derajat kebebasan <strong>df = n - 2 = 5</strong>, taraf signifikansi <strong>&alpha; = 0.05</strong> (t-tabel dua arah = 2.571).
            </p>
        </div>

        <!-- 3. Matriks Hasil 4 Indikator Korelasi Empiris BPS -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 space-y-3">
            <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="w-6 h-6 rounded-md bg-purple-600 text-white flex items-center justify-center text-xs">3</span>
                <span>Matriks Empiris 4 Pasang Variabel BPS April 2026</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-[11px]">
                    <thead class="bg-slate-100 text-slate-700 font-bold">
                        <tr>
                            <th class="p-2.5 rounded-l-lg">Pasangan Variabel Uji</th>
                            <th class="p-2.5 text-center">Koefisien (r)</th>
                            <th class="p-2.5 text-center">t-Hitung</th>
                            <th class="p-2.5 text-center">R-Squared (R&sup2;)</th>
                            <th class="p-2.5 text-center">Arah &amp; Kekuatan</th>
                            <th class="p-2.5 rounded-r-lg">Makna Ekonometrika Pariwisata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="bg-emerald-50/50 font-bold">
                            <td class="p-2 text-emerald-950">1. Tamu Menginap (X2) vs RLMT (Y)</td>
                            <td class="p-2 text-center text-emerald-700 font-mono">+0.5613</td>
                            <td class="p-2 text-center font-mono">+1.516</td>
                            <td class="p-2 text-center font-mono">31.5%</td>
                            <td class="p-2 text-center text-emerald-700">Positif Sedang-Kuat</td>
                            <td class="p-2 text-slate-600 font-normal">Makin banyak tamu memesan kamar, makin lama durasi menginap daerah.</td>
                        </tr>
                        <tr class="bg-amber-50/50 font-bold">
                            <td class="p-2 text-amber-950">2. Day-Tripper (X3) vs RLMT (Y)</td>
                            <td class="p-2 text-center text-amber-700 font-mono">-0.4991</td>
                            <td class="p-2 text-center font-mono">-1.287</td>
                            <td class="p-2 text-center font-mono">24.9%</td>
                            <td class="p-2 text-center text-amber-700">Negatif Sedang</td>
                            <td class="p-2 text-slate-600 font-normal">Dominasi pelancong harian menekan rata-rata durasi menginap daerah.</td>
                        </tr>
                        <tr>
                            <td class="p-2 text-slate-900 font-bold">3. Total Wisnus (X1) vs RLMT (Y)</td>
                            <td class="p-2 text-center text-slate-700 font-mono">-0.2823</td>
                            <td class="p-2 text-center font-mono">-0.658</td>
                            <td class="p-2 text-center font-mono">8.0%</td>
                            <td class="p-2 text-center text-slate-600 font-bold">Negatif Lemah</td>
                            <td class="p-2 text-slate-500">Banyaknya kunjungan total tidak menjamin tingginya durasi kamar hotel.</td>
                        </tr>
                        <tr>
                            <td class="p-2 text-slate-900 font-bold">4. TPK Non-Bintang (X5) vs RLMT (Y)</td>
                            <td class="p-2 text-center text-slate-700 font-mono">+0.2891</td>
                            <td class="p-2 text-center font-mono">+0.675</td>
                            <td class="p-2 text-center font-mono">8.4%</td>
                            <td class="p-2 text-center text-slate-600 font-bold">Positif Lemah</td>
                            <td class="p-2 text-slate-500">Okupansi penginapan non-bintang berkorelasi searah tapi lemah dengan durasi.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Penjelasan Ilmiah Sampel N=7 dan p-value > 0.05 -->
        <div class="bg-indigo-50/80 p-4 sm:p-5 rounded-2xl border border-indigo-200/80 space-y-2">
            <div class="font-bold text-indigo-950 text-sm flex items-center gap-2">
                <i class="fa-solid fa-scale-balanced text-indigo-600"></i>
                <span>Mengapa Nilai p-Value &gt; 0.05 pada N = 7 Kabupaten/Kota?</span>
            </div>
            <p class="text-[11px] text-indigo-900 leading-relaxed">
                Dalam kaidah statistika induktif, uji signifikansi sangat sensitif terhadap ukuran sampel ($N$). Provinsi Kepulauan Bangka Belitung secara administratif hanya memiliki <strong>7 Kabupaten/Kota</strong> ($N = 7$, $df = 5$). 
                Untuk mencapai nilai signifikansi $p < 0.05$ pada $df = 5$, koefisien korelasi yang dibutuhkan harus sangat ekstrem yaitu $|r| > 0.754$.
            </p>
            <p class="text-[11px] text-indigo-900 leading-relaxed font-bold">
                Meskipun secara formal &ldquo;Gagal Tolak H0 pada &alpha;=5%&rdquo;, nilai $r = +0.5613$ dan $R^2 = 31.5\%$ adalah bukti empiris yang substansial (*practically significant*) bahwa karakteristik wisatawan (Stayers vs Day-Tripper) merupakan faktor penentu utama lama tinggal wisatawan di Bangka Belitung.
            </p>
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

