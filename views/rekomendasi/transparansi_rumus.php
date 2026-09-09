<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      transparansi_rumus.php
 * @folder    views/rekomendasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Modal Transparansi Algoritma Matematis TOPSIS Hwang & Yoon)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen modal dialog transparansi metodologi ilmiah (Transparency Modal).
 * Merinci seluruh 6 tahapan komputasi algoritma TOPSIS:
 * 1. Matriks Keputusan Awal (X)
 * 2. Matriks Normalisasi (R) menggunakan rumus pembagi Euclidean
 * 3. Matriks Normalisasi Terbobot (Y = R x W)
 * 4. Penentuan Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-)
 * 5. Perhitungan Jarak Euclidean Solusi Ideal (D+ dan D-)
 * 6. Perhitungan Nilai Preferensi Akhir (V_i = D- / (D+ + D-))
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/rekomendasi/index.php
 * - Dipicu oleh: tombol 'Transparansi Rumus Matriks' di tabel_peringkat.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Menyediakan bukti kalkulasi matematika yang transparan bagi dosen penguji
 * tanpa mengotori tampilan antarmuka utama pengguna umum.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 5. MODAL TRANSPARANSI RUMUS MATEMATIS TOPSIS (HWANG & YOON)    -->
<!-- ============================================================== -->
<div id="modalRumusTopsis" class="fixed inset-0 z-50 hidden bg-slate-900/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[85vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden">
        
        <!-- Kepala Modal -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-xs">
                    <i class="fa-solid fa-square-root-variable"></i>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-black text-slate-900">Transparansi Tahapan Komputasi TOPSIS</h3>
                    <p class="text-xs text-slate-500">Metodologi Multi-Criteria Decision Making (Hwang &amp; Yoon, 1981)</p>
                </div>
            </div>
            <button onclick="closeModalRumusTopsis()" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-slate-300 text-slate-700 flex items-center justify-center text-xs transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Isi Modal Rincian 6 Tahapan -->
        <div class="p-5 sm:p-6 overflow-y-auto space-y-5 text-xs text-slate-600">
            
            <!-- Tahap 1: Normalisasi Matriks R -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <div class="font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-ocean-600 text-white text-[10px] flex items-center justify-center">1</span>
                    <span>Normalisasi Matriks Keputusan (r_ij)</span>
                </div>
                <div class="p-2.5 bg-slate-900 text-emerald-400 rounded-xl font-mono text-center text-xs">
                    r_ij = x_ij / &radic;( &sum; x_ij&sup2; )
                </div>
                <p class="text-[11px] text-slate-500">
                    Menghilangkan satuan dimensi nilai mentah (rating, km, jumlah) menjadi skala seragam [0, 1].
                </p>
            </div>

            <!-- Tahap 2: Normalisasi Terbobot Y -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <div class="font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-indigo-600 text-white text-[10px] flex items-center justify-center">2</span>
                    <span>Matriks Normalisasi Terbobot (y_ij)</span>
                </div>
                <div class="p-2.5 bg-slate-900 text-indigo-400 rounded-xl font-mono text-center text-xs">
                    y_ij = w_j &times; r_ij
                </div>
                <p class="text-[11px] text-slate-500">
                    Di mana <strong>w_j</strong> adalah bobot ternormalisasi preferensi pengguna (&sum;w_j = 1).
                </p>
            </div>

            <!-- Tahap 3: Solusi Ideal Positif (A+) & Negatif (A-) -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <div class="font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-emerald-600 text-white text-[10px] flex items-center justify-center">3</span>
                    <span>Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-)</span>
                </div>
                <div class="p-2.5 bg-slate-900 text-amber-400 rounded-xl font-mono text-center text-xs">
                    A+ = { max(y_ij) untuk benefit, min(y_ij) untuk cost }<br>
                    A- = { min(y_ij) untuk benefit, max(y_ij) untuk cost }
                </div>
                <p class="text-[11px] text-slate-500">
                    Kriteria Jarak Transportasi berstatus <strong>Cost</strong> (makin kecil makin baik), sedangkan Rating, Amenitas, dan Ulasan berstatus <strong>Benefit</strong> (makin besar makin baik).
                </p>
            </div>

            <!-- Tahap 4: Jarak Euclidean D+ & D- -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <div class="font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-amber-600 text-white text-[10px] flex items-center justify-center">4</span>
                    <span>Jarak Solusi Ideal Euclidean (D+ dan D-)</span>
                </div>
                <div class="p-2.5 bg-slate-900 text-sky-400 rounded-xl font-mono text-center text-xs">
                    D_i+ = &radic;[ &sum; (y_ij - y_j+)&sup2; ] &nbsp;&bull;&nbsp; D_i- = &radic;[ &sum; (y_ij - y_j-)&sup2; ]
                </div>
            </div>

            <!-- Tahap 5: Nilai Preferensi Akhir V -->
            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-2">
                <div class="font-bold text-emerald-900 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-md bg-emerald-600 text-white text-[10px] flex items-center justify-center">5</span>
                    <span>Kedekatan Relatif terhadap Solusi Ideal (Skor V_i)</span>
                </div>
                <div class="p-2.5 bg-slate-900 text-emerald-400 rounded-xl font-mono text-center text-xs sm:text-sm">
                    V_i = D_i- / ( D_i+ + D_i- )
                </div>
                <p class="text-[11px] text-emerald-800">
                    Nilai <strong>V_i</strong> berada pada rentang 0 hingga 1. Nilai V_i yang semakin mendekati 1 menunjukkan alternatif destinasi yang semakin ideal dan diposisikan pada peringkat teratas rekomendasi.
                </p>
            </div>

        </div>

        <!-- Tombol Tutup Kaki Modal -->
        <div class="p-4 border-t border-slate-100 flex justify-end bg-slate-50">
            <button onclick="closeModalRumusTopsis()" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors">
                Tutup Jendela Pembuktian
            </button>
        </div>

    </div>
</div>

<script>
function openModalRumusTopsis() {
    const m = document.getElementById('modalRumusTopsis');
    if (m) m.classList.remove('hidden');
}
function closeModalRumusTopsis() {
    const m = document.getElementById('modalRumusTopsis');
    if (m) m.classList.add('hidden');
}
</script>
