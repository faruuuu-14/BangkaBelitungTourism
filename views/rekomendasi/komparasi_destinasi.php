<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      komparasi_destinasi.php
 * @folder    views/rekomendasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Modal Komparasi Berdampingan 2 Destinasi Wisata TOPSIS)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen modal komparasi *head-to-head* berdampingan antara dua alternatif
 * destinasi wisata. Menampilkan visualisasi perbandingan multi-kriteria:
 * 1. Skor Preferensi Relatif TOPSIS (V)
 * 2. Kualitas Rating & Popularitas Ulasan Publik
 * 3. Aksesibilitas Geodesik ke Simpul Transportasi (Bandara / Pelabuhan)
 * 4. Ketersediaan Fasilitas Amenitas (Hotel & Restoran) dalam Radius 5 km
 * 
 * KETERKAITAN ALUR:
 * - Dimuat oleh: views/rekomendasi/index.php
 * - Menggunakan data: variabel JSON $rankedList dari PHP
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- MODAL KOMPARASI BERDAMPINGAN 2 DESTINASI WISATA (HEAD-TO-HEAD) -->
<!-- ============================================================== -->
<div id="modalKomparasi" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
        
        <!-- Header Modal Komparasi -->
        <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-900 via-ocean-950 to-slate-900 text-white flex items-center justify-between shrink-0 border-b border-white/10">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center font-bold text-base shadow-xs">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <div>
                    <h3 class="font-black text-sm sm:text-base text-white">Komparasi 2 Destinasi Wisata (Head-to-Head)</h3>
                    <p class="text-[10px] sm:text-xs text-slate-300">Perbandingan objektif multi-kriteria berbasis metode ilmiah TOPSIS</p>
                </div>
            </div>
            <button onclick="closeModalKomparasi()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Pemilihan 2 Destinasi (Dropdown Selector) -->
        <div class="p-4 bg-slate-50 border-b border-slate-200 grid grid-cols-1 sm:grid-cols-2 gap-3 shrink-0">
            <!-- Pilihan Destinasi A -->
            <div class="space-y-1">
                <label class="text-[11px] font-extrabold text-ocean-800 uppercase tracking-wider block">
                    <i class="fa-solid fa-circle-dot text-ocean-600 mr-1"></i> Destinasi Alternatif A:
                </label>
                <select id="selectCompA" onchange="updateComparisonView()" class="w-full text-xs font-semibold rounded-xl border border-slate-300 bg-white p-2.5 text-slate-800 focus:ring-2 focus:ring-ocean-500">
                    <!-- Dimasukkan via JavaScript -->
                </select>
            </div>

            <!-- Pilihan Destinasi B -->
            <div class="space-y-1">
                <label class="text-[11px] font-extrabold text-purple-800 uppercase tracking-wider block">
                    <i class="fa-solid fa-circle-dot text-purple-600 mr-1"></i> Destinasi Alternatif B:
                </label>
                <select id="selectCompB" onchange="updateComparisonView()" class="w-full text-xs font-semibold rounded-xl border border-slate-300 bg-white p-2.5 text-slate-800 focus:ring-2 focus:ring-purple-500">
                    <!-- Dimasukkan via JavaScript -->
                </select>
            </div>
        </div>

        <!-- Area Komparasi Berdampingan (Body) -->
        <div class="p-4 sm:p-6 overflow-y-auto flex-1 space-y-6" id="comparisonContentArea">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                
                <!-- Kartu Hasil Destinasi A -->
                <div id="cardCompA" class="rounded-2xl border-2 border-ocean-200 p-4 bg-gradient-to-b from-ocean-50/50 to-white space-y-4">
                    <!-- Diisi dinamis -->
                </div>

                <!-- Kartu Hasil Destinasi B -->
                <div id="cardCompB" class="rounded-2xl border-2 border-purple-200 p-4 bg-gradient-to-b from-purple-50/50 to-white space-y-4">
                    <!-- Diisi dinamis -->
                </div>

            </div>

            <!-- Kartu Kesimpulan Rekomendasi Sistem -->
            <div id="compVerdictCard" class="p-4 rounded-2xl bg-slate-900 text-white flex flex-col sm:flex-row items-center justify-between gap-3">
                <!-- Diisi dinamis -->
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="p-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500 shrink-0">
            <span class="text-[11px]">
                <i class="fa-solid fa-calculator text-ocean-600 mr-1"></i> Formula TOPSIS: $V_i = \frac{D_i^-}{D_i^+ + D_i^-}$
            </span>
            <button onclick="closeModalKomparasi()" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold transition">
                Tutup Komparasi
            </button>
        </div>

    </div>
</div>

<script>
// Data kandidat dari controller TOPSIS
const compCandidates = <?= json_encode($rankedList ?? []) ?>;

function getCompPhoto(nama, sub, kab) {
    const n = (nama || '').toLowerCase();
    const k = (kab || '').toLowerCase();
    if (n.includes('tikus emas') || n.includes('parai')) return 'assets/img/bangka_belitung/pantai_tikus_emas.jpg';
    if (n.includes('tanjung ular') || n.includes('menumbing') || k.includes('barat')) return 'assets/img/bangka_belitung/pantai_tanjung_ular.jpg';
    if (n.includes('kaolin') || k.includes('tengah')) return 'assets/img/bangka_belitung/danau_kaolin_koba.jpg';
    if (n.includes('batu perahu') || k.includes('selatan')) return 'assets/img/bangka_belitung/pantai_batu_perahu.jpg';
    if (n.includes('lengkuas')) return 'assets/img/bangka_belitung/pantai_lengkuas.jpg';
    if (n.includes('tanjung tinggi') || k.includes('belitung')) return 'assets/img/bangka_belitung/pantai_tanjung_tinggi.jpg';
    if (n.includes('gantong') || k.includes('timur')) return 'assets/img/bangka_belitung/laskar_pelangi_gantong.jpg';
    if (n.includes('museum') || n.includes('timah') || k.includes('pangkalpinang')) return 'assets/img/bangka_belitung/museum_timah_babel.jpg';
    return 'assets/img/bangka_belitung/pantai_tikus_emas.jpg';
}

function openModalKomparasi() {
    if (!compCandidates || compCandidates.length < 2) {
        alert('Data kandidat tidak mencukupi untuk komparasi.');
        return;
    }

    const selA = document.getElementById('selectCompA');
    const selB = document.getElementById('selectCompB');

    selA.innerHTML = '';
    selB.innerHTML = '';

    compCandidates.forEach((c, idx) => {
        const optA = new Option(`#${idx + 1} ${c.nama} (${c.kabupaten})`, c.id);
        const optB = new Option(`#${idx + 1} ${c.nama} (${c.kabupaten})`, c.id);
        selA.add(optA);
        selB.add(optB);
    });

    // Default: Alternatif 1 vs Alternatif 2
    selA.selectedIndex = 0;
    selB.selectedIndex = 1;

    updateComparisonView();
    document.getElementById('modalKomparasi').classList.remove('hidden');
}

function closeModalKomparasi() {
    document.getElementById('modalKomparasi').classList.add('hidden');
}

function renderCompCard(item, badgeColor, borderColor) {
    const photo = getCompPhoto(item.nama, item.sub_kategori, item.kabupaten);
    return `
        <div class="relative h-36 rounded-xl overflow-hidden shadow-xs">
            <img src="${photo}" alt="${item.nama}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
            <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold ${badgeColor} shadow-xs">
                ${item.sub_kategori} &bull; ${item.kabupaten}
            </span>
            <span class="absolute bottom-2 right-2 text-amber-300 font-bold text-xs drop-shadow">
                ★ ${parseFloat(item.rating).toFixed(1)} (${parseInt(item.ulasan).toLocaleString()} ulasan)
            </span>
        </div>

        <div>
            <h4 class="font-extrabold text-sm text-slate-900 leading-snug">${item.nama}</h4>
            <p class="text-[11px] text-slate-500">${item.kecamatan}, ${item.kabupaten}</p>
        </div>

        <!-- 4 Indikator Nilai -->
        <div class="space-y-2 pt-2 border-t border-slate-200/80 text-xs">
            <div class="flex items-center justify-between">
                <span class="text-slate-600 font-medium">Skor Preferensi TOPSIS (V):</span>
                <span class="font-mono font-black text-sm text-ocean-700">${parseFloat(item.score_v).toFixed(4)}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-600 font-medium">Jarak Simpul Transportasi:</span>
                <span class="font-bold text-slate-800">${item.hub_dist} km (${item.closest_hub})</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-600 font-medium">Amenitas Sekitar (≤5 km):</span>
                <span class="font-bold text-emerald-700">${item.amenities} Fasilitas</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate-600 font-medium">Rating Kepuasan Publik:</span>
                <span class="font-bold text-amber-600">★ ${parseFloat(item.rating).toFixed(1)}</span>
            </div>
        </div>

        <a href="index.php?page=peta&search=${encodeURIComponent(item.nama)}" 
            class="w-full mt-3 py-2 px-3 rounded-xl bg-slate-900 hover:bg-ocean-600 text-white font-bold text-xs text-center transition flex items-center justify-center gap-1.5 shadow-xs">
            <i class="fa-solid fa-map-location-dot"></i>
            <span>Buka Lokasi di Peta</span>
        </a>
    `;
}

function updateComparisonView() {
    const idA = document.getElementById('selectCompA').value;
    const idB = document.getElementById('selectCompB').value;

    const itemA = compCandidates.find(c => c.id == idA) || compCandidates[0];
    const itemB = compCandidates.find(c => c.id == idB) || compCandidates[1];

    document.getElementById('cardCompA').innerHTML = renderCompCard(itemA, 'bg-ocean-600 text-white', 'border-ocean-300');
    document.getElementById('cardCompB').innerHTML = renderCompCard(itemB, 'bg-purple-600 text-white', 'border-purple-300');

    // Analisis Kesimpulan
    const winner = (itemA.score_v >= itemB.score_v) ? itemA : itemB;
    const diff = Math.abs(itemA.score_v - itemB.score_v).toFixed(4);

    document.getElementById('compVerdictCard').innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center text-lg font-black shrink-0">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <div class="text-[10px] text-amber-300 uppercase font-extrabold tracking-wider">Kesimpulan Rekomendasi SPK TOPSIS:</div>
                <div class="font-extrabold text-sm text-white">
                    <strong>${winner.nama}</strong> lebih direkomendasikan dengan selisih skor preferensi <strong>+${diff}</strong>.
                </div>
            </div>
        </div>
        <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold shrink-0">
            Alternatif Pilihan Utama
        </span>
    `;
}
</script>
