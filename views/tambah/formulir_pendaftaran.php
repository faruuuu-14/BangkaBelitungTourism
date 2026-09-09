<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      formulir_pendaftaran.php
 * @folder    views/tambah/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Formulir Pendaftaran Objek & Pemilih Titik GPS Peta)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen formulir pendaftaran destinasi/amenitas baru. Menyediakan input nama
 * tempat, dropdown kabupaten dan kecamatan terhubung dinamis (Chained Select),
 * pilihan kategori 3A Kemenparekraf, estimasi rating dan ulasan, serta pemilih
 * titik koordinat GPS interaktif berbasis peta Leaflet (klik pada peta untuk
 * otomatis mengisi nilai Latitude dan Longitude presisi 7 desimal).
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/tambah/index.php
 * - Menggunakan array: $wilayahBabel
 * - Mengirim POST ke: index.php?page=tambah
 * 
 * TUJUAN DIBUATNYA FILE:
 * Memudahkan masyarakat, pengelola wisata, dan dinas mendaftarkan objek baru
 * dengan jaminan keakuratan titik koordinat spasial tanpa perlu mengetik manual.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- FORMULIR PENDAFTARAN & PEMILIH KOORDINAT PETA LEAFLET          -->
<!-- ============================================================== -->
<form method="POST" action="index.php?page=tambah" class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-xs space-y-6">
    
    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
        <div>
            <h3 class="text-base font-black text-slate-900">Formulir Usulan Destinasi / Amenitas Baru</h3>
            <p class="text-xs text-slate-500">Lengkapi informasi di bawah dan tentukan titik lokasi pada peta</p>
        </div>
        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
            * Kolom Wajib Diisi
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <!-- Kolom Kiri: Input Teks & Atribut -->
        <div class="space-y-4">
            
            <!-- Nama Tempat -->
            <div class="space-y-1">
                <label class="block text-xs font-extrabold text-slate-800">
                    Nama Objek Wisata / Fasilitas <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama" required placeholder="Contoh: Pantai Siangau, Hotel Santika, dll."
                    class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-1 focus:ring-ocean-500 focus:border-ocean-500 outline-hidden font-medium">
            </div>

            <!-- Kabupaten / Kota -->
            <div class="space-y-1">
                <label class="block text-xs font-extrabold text-slate-800">
                    Kabupaten / Kota <span class="text-rose-500">*</span>
                </label>
                <select name="kabupaten" id="selectKab" required onchange="updateKecamatanOptions()"
                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-1 focus:ring-ocean-500 outline-hidden font-medium text-slate-700">
                    <option value="">-- Pilih Kabupaten / Kota --</option>
                    <?php foreach (array_keys($wilayahBabel) as $kab): ?>
                    <option value="<?= htmlspecialchars($kab) ?>"><?= htmlspecialchars($kab) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Kecamatan (Terkoneksi Dinamis ke Kabupaten) -->
            <div class="space-y-1">
                <label class="block text-xs font-extrabold text-slate-800">
                    Kecamatan <span class="text-rose-500">*</span>
                </label>
                <select name="kecamatan" id="selectKec" required
                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-1 focus:ring-ocean-500 outline-hidden font-medium text-slate-700">
                    <option value="">-- Pilih Kabupaten Terlebih Dahulu --</option>
                </select>
            </div>

            <!-- Kategori Standar Kemenparekraf -->
            <div class="space-y-1">
                <label class="block text-xs font-extrabold text-slate-800">
                    Sub Kategori <span class="text-rose-500">*</span>
                </label>
                <select name="sub_kategori" required
                    class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-1 focus:ring-ocean-500 outline-hidden font-medium text-slate-700">
                    <option value="">-- Pilih Kategori --</option>
                    <optgroup label="Kelompok: Daya Tarik Wisata">
                        <option value="Alam">Wisata Alam (Pantai, Pulau, Danau Kaolin, Bukit)</option>
                        <option value="Kebudayaan">Kebudayaan &amp; Sejarah (Museum, Kelenteng, Tradisi)</option>
                        <option value="Buatan">Wisata Buatan (Taman Rekreasi, Agrowisata)</option>
                    </optgroup>
                    <optgroup label="Kelompok: Fasilitas Amenitas">
                        <option value="Hotel">Akomodasi (Hotel, Resort, Homestay)</option>
                        <option value="Restoran">Kuliner (Restoran, Rumah Makan, Kafe)</option>
                    </optgroup>
                </select>
            </div>

            <!-- Rating & Jumlah Ulasan -->
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-xs font-extrabold text-slate-800">Rating Awal (1.0 - 5.0)</label>
                    <input type="number" name="rating" min="1.0" max="5.0" step="0.1" value="4.5"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-1 focus:ring-ocean-500 outline-hidden font-medium">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-extrabold text-slate-800">Jumlah Ulasan Publik</label>
                    <input type="number" name="ulasan" min="0" value="25"
                        class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-1 focus:ring-ocean-500 outline-hidden font-medium">
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Pemilih Koordinat GPS Peta Interaktif -->
        <div class="space-y-2 flex flex-col">
            <div class="flex items-center justify-between">
                <label class="block text-xs font-extrabold text-slate-800">
                    Titik Lokasi GPS <span class="text-rose-500">*</span>
                </label>
                <span class="text-[10px] text-ocean-600 font-semibold">
                    <i class="fa-solid fa-hand-pointer mr-0.5"></i> Klik pada peta untuk memindahkan pin
                </span>
            </div>

            <!-- Kanvas Peta Mini -->
            <div id="pickerMap" class="w-full h-56 sm:h-64 rounded-2xl border border-slate-200 overflow-hidden shadow-inner z-10"></div>

            <!-- Koordinat Terpilih -->
            <div class="grid grid-cols-2 gap-3 pt-1">
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500">Latitude (Garis Lintang)</label>
                    <input type="text" name="latitude" id="inputLat" required readonly placeholder="-2.500000"
                        class="w-full px-3 py-1.5 text-xs font-mono rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-bold">
                </div>
                <div class="space-y-1">
                    <label class="text-[11px] font-bold text-slate-500">Longitude (Garis Bujur)</label>
                    <input type="text" name="longitude" id="inputLng" required readonly placeholder="106.800000"
                        class="w-full px-3 py-1.5 text-xs font-mono rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-bold">
                </div>
            </div>
        </div>

    </div>

    <!-- Tombol Simpan Usulan -->
    <div class="pt-4 border-t border-slate-100 flex justify-end">
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white font-extrabold text-xs shadow-md shadow-ocean-600/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-paper-plane"></i>
            <span>Kirimkan Usulan Data Baru</span>
        </button>
    </div>

</form>

<!-- Logika JavaScript Chained Select & Picker Peta Leaflet -->
<script>
const wilayahMap = <?= json_encode($wilayahBabel) ?>;

function updateKecamatanOptions() {
    const kab = document.getElementById('selectKab').value;
    const kecSelect = document.getElementById('selectKec');
    kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

    if (kab && wilayahMap[kab]) {
        wilayahMap[kab].forEach(kec => {
            const opt = document.createElement('option');
            opt.value = kec;
            opt.innerText = 'Kec. ' + kec;
            kecSelect.appendChild(opt);
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const pickerEl = document.getElementById('pickerMap');
    if (!pickerEl) return;

    // Inisialisasi peta mini
    const picker = L.map('pickerMap').setView([-2.5, 106.8], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(picker);

    let marker = null;

    picker.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);

        document.getElementById('inputLat').value = lat;
        document.getElementById('inputLng').value = lng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng, { draggable: true }).addTo(picker);
            marker.on('dragend', function(ev) {
                const pos = ev.target.getLatLng();
                document.getElementById('inputLat').value = pos.lat.toFixed(7);
                document.getElementById('inputLng').value = pos.lng.toFixed(7);
            });
        }
    });
});
</script>
