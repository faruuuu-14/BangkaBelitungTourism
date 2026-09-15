<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      pengaturan_bobot.php
 * @folder    views/rekomendasi/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Pengaturan Bobot Kriteria & Preset Persona Wisatawan)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen formulir pengaturan bobot kriteria preferensi pengguna (User Preferences).
 * Menyediakan tombol preset 1-klik untuk 3 persona wisatawan:
 * 1. Backpacker / Mandiri : Mengutamakan kemudahan akses transportasi (Cost rendah).
 * 2. Liburan Keluarga     : Mengutamakan kelengkapan amenitas hotel & restoran sekitar.
 * 3. Pecinta Tempat Viral : Mengutamakan rating tertinggi & ulasan terbanyak.
 * Serta 4 slider fleksibel untuk kustomisasi bobot individual secara bebas.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/rekomendasi/index.php
 * - Mengirim parameter GET: w_rating, w_hub, w_amenities, w_ulasan, kabupaten, sub_kategori
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memberikan fleksibilitas personalisasi preferensi keputusan bagi pengguna
 * dengan antarmuka yang modern, responsif, dan interaktif.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 2. FORMULIR PENGATURAN BOBOT KRITERIA & PRESET PERSONA         -->
<!-- ============================================================== -->
<form method="GET" action="index.php" class="bg-white rounded-2xl sm:rounded-3xl p-3.5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-3.5 sm:space-y-6">
    <input type="hidden" name="page" value="rekomendasi">

    <!-- Kepala Formulir Pengaturan -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 sm:pb-4 border-b border-slate-100 gap-2">
        <div>
            <h3 class="text-xs sm:text-lg font-black text-slate-900 tracking-tight flex items-center gap-1.5 sm:gap-2">
                <span>Atur Bobot Keputusan &amp; Preferensi</span>
                <span class="text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">Total: 100%</span>
            </h3>
            <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Pilih preset cepat atau atur kriteria dengan slider</p>
        </div>
        
        <!-- Tombol Preset Cepat (1-Click Persona Presets) -->
        <div class="flex items-center gap-1.5 flex-wrap self-start sm:self-auto">
            <button type="button" onclick="setPersonaPreset('backpacker')" class="px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-lg sm:rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 hover:text-ocean-700 text-[11px] sm:text-xs font-bold transition-all border border-slate-200">
                🎒 Mandiri
            </button>
            <button type="button" onclick="setPersonaPreset('keluarga')" class="px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-lg sm:rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 hover:text-ocean-700 text-[11px] sm:text-xs font-bold transition-all border border-slate-200">
                👨‍👩‍👧‍👦 Keluarga
            </button>
            <button type="button" onclick="setPersonaPreset('populer')" class="px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-lg sm:rounded-xl bg-slate-100 hover:bg-ocean-50 text-slate-700 hover:text-ocean-700 text-[11px] sm:text-xs font-bold transition-all border border-slate-200">
                🌟 Populer
            </button>
        </div>
    </div>

    <!-- 4 Slider Bobot Kriteria (2x2 di Mobile, 4 Kolom di Layar Lebar) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-5">
        
        <!-- Kriteria 1: Kualitas Rating (Benefit) -->
        <div class="space-y-1 sm:space-y-2 bg-slate-50/70 p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/70">
            <div class="flex justify-between items-center text-[11px] sm:text-xs font-bold">
                <span class="text-slate-800 flex items-center gap-1">
                    <i class="fa-solid fa-star text-amber-500 text-[10px] sm:text-[11px]"></i>
                    <span class="truncate">Rating</span>
                </span>
                <span id="label_w_rating" class="text-ocean-600 font-mono font-black text-xs sm:text-sm"><?= $wRating ?>%</span>
            </div>
            <input type="range" name="w_rating" id="w_rating" min="5" max="70" step="5" value="<?= $wRating ?>"
                class="w-full h-1.5 sm:h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-ocean-600"
                oninput="document.getElementById('label_w_rating').innerText = this.value + '%'">
            <div class="hidden sm:block text-[10px] text-slate-500 font-medium">Prioritas: Bintang kepuasan tinggi</div>
        </div>

        <!-- Kriteria 2: Akses Transportasi (Cost) -->
        <div class="space-y-1 sm:space-y-2 bg-slate-50/70 p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/70">
            <div class="flex justify-between items-center text-[11px] sm:text-xs font-bold">
                <span class="text-slate-800 flex items-center gap-1">
                    <i class="fa-solid fa-road text-sky-500 text-[10px] sm:text-[11px]"></i>
                    <span class="truncate">Transport</span>
                </span>
                <span id="label_w_hub" class="text-sky-600 font-mono font-black text-xs sm:text-sm"><?= $wHub ?>%</span>
            </div>
            <input type="range" name="w_hub" id="w_hub" min="5" max="70" step="5" value="<?= $wHub ?>"
                class="w-full h-1.5 sm:h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-sky-600"
                oninput="document.getElementById('label_w_hub').innerText = this.value + '%'">
            <div class="hidden sm:block text-[10px] text-slate-500 font-medium">Prioritas: Dekat bandara/pelabuhan</div>
        </div>

        <!-- Kriteria 3: Ketersediaan Amenitas (Benefit) -->
        <div class="space-y-1 sm:space-y-2 bg-slate-50/70 p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/70">
            <div class="flex justify-between items-center text-[11px] sm:text-xs font-bold">
                <span class="text-slate-800 flex items-center gap-1">
                    <i class="fa-solid fa-hotel text-indigo-500 text-[10px] sm:text-[11px]"></i>
                    <span class="truncate">Amenitas</span>
                </span>
                <span id="label_w_amenities" class="text-indigo-600 font-mono font-black text-xs sm:text-sm"><?= $wAmenities ?>%</span>
            </div>
            <input type="range" name="w_amenities" id="w_amenities" min="5" max="70" step="5" value="<?= $wAmenities ?>"
                class="w-full h-1.5 sm:h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                oninput="document.getElementById('label_w_amenities').innerText = this.value + '%'">
            <div class="hidden sm:block text-[10px] text-slate-500 font-medium">Prioritas: Banyak hotel &amp; resto</div>
        </div>

        <!-- Kriteria 4: Popularitas Ulasan (Benefit) -->
        <div class="space-y-1 sm:space-y-2 bg-slate-50/70 p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/70">
            <div class="flex justify-between items-center text-[11px] sm:text-xs font-bold">
                <span class="text-slate-800 flex items-center gap-1">
                    <i class="fa-solid fa-comments text-emerald-500 text-[10px] sm:text-[11px]"></i>
                    <span class="truncate">Ulasan</span>
                </span>
                <span id="label_w_ulasan" class="text-emerald-600 font-mono font-black text-xs sm:text-sm"><?= $wUlasan ?>%</span>
            </div>
            <input type="range" name="w_ulasan" id="w_ulasan" min="5" max="70" step="5" value="<?= $wUlasan ?>"
                class="w-full h-1.5 sm:h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-600"
                oninput="document.getElementById('label_w_ulasan').innerText = this.value + '%'">
            <div class="hidden sm:block text-[10px] text-slate-500 font-medium">Prioritas: Jumlah ulasan publik</div>
        </div>

    </div>

    <!-- Filter Geografis & Tombol Eksekusi -->
    <div class="pt-3 sm:pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3">
        <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
            <!-- Filter Kabupaten -->
            <select name="kabupaten" class="text-[11px] sm:text-xs rounded-xl border-slate-200 bg-slate-50 py-1.5 sm:py-2 px-2.5 sm:px-3 font-medium text-slate-700 focus:ring-ocean-500 flex-1 sm:flex-none">
                <option value="">Semua Daerah (7 Kab/Kota)</option>
                <?php foreach ($kabList as $kab): ?>
                <option value="<?= htmlspecialchars($kab) ?>" <?= $filterKab === $kab ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kab) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <!-- Filter Kategori -->
            <select name="sub_kategori" class="text-[11px] sm:text-xs rounded-xl border-slate-200 bg-slate-50 py-1.5 sm:py-2 px-2.5 sm:px-3 font-medium text-slate-700 focus:ring-ocean-500 flex-1 sm:flex-none">
                <option value="">Semua Kategori</option>
                <option value="Alam" <?= $filterSub === 'Alam' ? 'selected' : '' ?>>Wisata Alam</option>
                <option value="Kebudayaan" <?= $filterSub === 'Kebudayaan' ? 'selected' : '' ?>>Budaya &amp; Sejarah</option>
                <option value="Buatan" <?= $filterSub === 'Buatan' ? 'selected' : '' ?>>Buatan / Rekreasi</option>
            </select>
        </div>

        <button type="submit" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white font-extrabold text-xs shadow-md shadow-ocean-600/20 transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-calculator"></i>
            <span>Hitung Ulang TOPSIS</span>
        </button>
    </div>

</form>

<!-- Logika JavaScript Pemilihan Preset Persona -->
<script>
function setPersonaPreset(tipe) {
    const r = document.getElementById('w_rating');
    const h = document.getElementById('w_hub');
    const a = document.getElementById('w_amenities');
    const u = document.getElementById('w_ulasan');

    if (tipe === 'backpacker') {
        // Backpacker: Akses transportasi diutamakan
        r.value = 20; h.value = 45; a.value = 15; u.value = 20;
    } else if (tipe === 'keluarga') {
        // Keluarga: Amenitas & kenyamanan diutamakan
        r.value = 25; h.value = 20; a.value = 40; u.value = 15;
    } else if (tipe === 'populer') {
        // Populer: Rating dan ulasan diutamakan
        r.value = 40; h.value = 15; a.value = 15; u.value = 30;
    }

    // Perbarui label tampilan
    document.getElementById('label_w_rating').innerText = r.value + '%';
    document.getElementById('label_w_hub').innerText = h.value + '%';
    document.getElementById('label_w_amenities').innerText = a.value + '%';
    document.getElementById('label_w_ulasan').innerText = u.value + '%';
}
</script>
