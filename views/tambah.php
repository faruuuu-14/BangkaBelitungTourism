<?php
require_once __DIR__ . '/../app/database.php';
$pdo = getDbConnection();

$pesanSukses = '';
$pesanError = '';

// Data pemetaan Kabupaten & Kecamatan Bangka Belitung
$wilayahBabel = [
    "Kabupaten Bangka" => ["Sungailiat", "Belinyu", "Pemali", "Merawang", "Mendo Barat", "Riau Silip", "Bakam", "Puding Besar"],
    "Kabupaten Bangka Barat" => ["Mentok", "Simpang Teritip", "Jebus", "Kelapa", "Tempilang", "Parittiga"],
    "Kabupaten Bangka Tengah" => ["Koba", "Pangkalan Baru", "Sungai Selan", "Simpang Katis", "Namang", "Lubuk Besar"],
    "Kabupaten Bangka Selatan" => ["Toboali", "Air Gegas", "Payung", "Simpang Rimba", "Tukak Sadai", "Pulau Besar", "Lepar Pongok", "Kepulauan Pongok"],
    "Kabupaten Belitung" => ["Tanjung Pandan", "Sijuk", "Badau", "Membalong", "Selat Nasik"],
    "Kabupaten Belitung Timur" => ["Manggar", "Gantung", "Kelapa Kampit", "Damar", "Dendang", "Simpang Pesak", "Simpang Renggiang"],
    "Kota Pangkalpinang" => ["Bukit Intan", "Gabek", "Gerunggang", "Girimaya", "Pangkal Balam", "Rangkui", "Taman Sari"]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama        = trim($_POST['nama'] ?? '');
    $kabupaten   = trim($_POST['kabupaten'] ?? '');
    $kecamatan   = trim($_POST['kecamatan'] ?? '');
    $subKategori = trim($_POST['sub_kategori'] ?? '');
    $rating      = floatval($_POST['rating'] ?? 0);
    $ulasan      = intval($_POST['ulasan'] ?? 0);
    $lat         = floatval($_POST['latitude'] ?? 0);
    $lng         = floatval($_POST['longitude'] ?? 0);

    // Tentukan kelompok
    $kelompok = in_array($subKategori, ['Hotel', 'Restoran']) ? 'Amenitas' : 'Daya Tarik';

    if (empty($nama) || empty($kabupaten) || empty($kecamatan) || empty($subKategori)) {
        $pesanError = "Mohon lengkapi seluruh kolom wajib yang bertanda bintang (*).";
    } elseif ($lat == 0 || $lng == 0) {
        $pesanError = "Mohon tentukan titik lokasi koordinat dengan mengklik pada peta.";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO tempat_wisata 
                (nama, kabupaten, kecamatan, kelompok, sub_kategori, rating, ulasan, latitude, longitude, status_verifikasi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Usulan Baru')
            ");
            $stmt->execute([$nama, $kabupaten, $kecamatan, $kelompok, $subKategori, $rating, $ulasan, $lat, $lng]);
            $pesanSukses = "Objek wisata/amenitas baru '<strong>" . htmlspecialchars($nama) . "</strong>' berhasil didaftarkan dan masuk ke database!";
        } catch (Exception $e) {
            $pesanError = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
}
?>

<main class="flex-grow w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 pb-24 lg:pb-8 min-w-0 overflow-hidden">

    <!-- Header Section with Visual Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-ocean-950 to-slate-900 rounded-3xl p-5 sm:p-7 text-white shadow-xl border border-white/10 mb-8">
        <div class="absolute -right-16 -top-16 w-80 h-80 bg-ocean-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <div class="md:col-span-8 space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-ocean-300 text-xs font-semibold">
                    <i class="fa-solid fa-users"></i>
                    <span>Partisipasi Masyarakat, Komunitas &amp; Pelaku Usaha</span>
                </span>
                <h1 class="text-xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                    Pendaftaran Objek Wisata &amp; Amenitas Baru
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                    Daftarkan destinasi daya tarik alam, pusaka budaya, maupun amenitas penginapan/kuliner lokal di Bangka Belitung. Klik pada peta interaktif untuk menentukan titik koordinat GPS secara otomatis.
                </p>
            </div>
            <div class="md:col-span-4 flex justify-end">
                <div class="relative h-28 w-full max-w-[260px] rounded-2xl overflow-hidden shadow-lg border border-white/20 group">
                    <img src="assets/img/pantai_lengkuas.jpg" alt="Pulau Lengkuas" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-3 right-3 flex items-center justify-between text-[10px] font-bold text-white drop-shadow">
                        <span>Babel Tourism Registry</span>
                        <span class="bg-white/20 px-2 py-0.5 rounded backdrop-blur-xs">Resmi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi Alert -->
    <?php if ($pesanSukses): ?>
    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
        <div><?= $pesanSukses ?></div>
    </div>
    <?php endif; ?>

    <?php if ($pesanError): ?>
    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
        <div><?= $pesanError ?></div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Formulir Input -->
        <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-ocean-600"></i>
                <span>Data Informasi Tempat</span>
            </h2>

            <form method="POST" action="index.php?page=tambah" class="space-y-4">
                
                <!-- Nama Tempat -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Tempat / Objek <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama" required placeholder="Contoh: Pantai Tanjung Kelayang, Homestay Desa..." 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-ocean-500 text-sm">
                </div>

                <!-- Sub Kategori -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Kategori Tempat <span class="text-rose-500">*</span>
                    </label>
                    <select name="sub_kategori" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-ocean-500 text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        <optgroup label="Daya Tarik Wisata">
                            <option value="Alam">Daya Tarik Alam (Pantai, Pulau, Bukit)</option>
                            <option value="Buatan">Daya Tarik Buatan (Waterpark, Taman)</option>
                            <option value="Kebudayaan">Daya Tarik Kebudayaan / Sejarah</option>
                        </optgroup>
                        <optgroup label="Amenitas Fasilitas">
                            <option value="Hotel">Hotel / Homestay / Penginapan</option>
                            <option value="Restoran">Restoran / Rumah Makan / Cafe</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Wilayah: Kabupaten & Kecamatan Bertingkat -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kabupaten <span class="text-rose-500">*</span>
                        </label>
                        <select name="kabupaten" id="selectKabupaten" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-ocean-500 text-xs">
                            <option value="">Pilih Kabupaten</option>
                            <?php foreach (array_keys($wilayahBabel) as $kab): ?>
                            <option value="<?= htmlspecialchars($kab) ?>"><?= htmlspecialchars($kab) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kecamatan <span class="text-rose-500">*</span>
                        </label>
                        <select name="kecamatan" id="selectKecamatan" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-ocean-500 text-xs">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                </div>

                <!-- Koordinat (Terisi Otomatis dari Peta) -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Latitude</label>
                        <input type="text" name="latitude" id="inputLat" readonly placeholder="-2.xxxx" 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-100 font-mono text-xs text-slate-700 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Longitude</label>
                        <input type="text" name="longitude" id="inputLng" readonly placeholder="106.xxxx" 
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-100 font-mono text-xs text-slate-700 cursor-not-allowed">
                    </div>
                </div>
                <p class="text-[11px] text-ocean-600 font-medium -mt-2">
                    <i class="fa-solid fa-hand-pointer text-[10px]"></i> Klik lokasi pada peta di sebelah kanan untuk mengisi koordinat secara otomatis.
                </p>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full mt-4 py-3 rounded-xl bg-ocean-600 hover:bg-ocean-700 text-white font-bold text-sm shadow-md shadow-ocean-500/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Simpan ke Database</span>
                </button>

            </form>
        </div>

        <!-- Peta Interaktif Penentu Lokasi (Leaflet Click-to-Pin) -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Penentuan Koordinat Geospasial</h2>
                    <p class="text-xs text-slate-500">Klik pada peta di bawah ini untuk menandai posisi lokasi</p>
                </div>
                <span id="pinStatus" class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg">
                    Belum Ditandai
                </span>
            </div>

            <div id="pickerMap" class="flex-grow min-h-[380px] rounded-2xl border border-slate-200 z-0"></div>
        </div>

    </div>

</main>

<script>
const wilayahMap = <?= json_encode($wilayahBabel) ?>;

// Dynamic Dropdown Kecamatan berdasarkan Kabupaten
const selectKab = document.getElementById('selectKabupaten');
const selectKec = document.getElementById('selectKecamatan');

selectKab.addEventListener('change', function() {
    const kab = this.value;
    selectKec.innerHTML = '<option value="">Pilih Kecamatan</option>';
    if (wilayahMap[kab]) {
        wilayahMap[kab].forEach(kec => {
            const opt = document.createElement('option');
            opt.value = kec;
            opt.textContent = kec;
            selectKec.appendChild(opt);
        });
    }
});

// Peta Leaflet untuk Pin Lokasi
const pickerMap = L.map('pickerMap').setView([-2.5, 106.8], 9);

L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; CARTO'
}).addTo(pickerMap);

let userMarker = null;

pickerMap.on('click', function(e) {
    const lat = e.latlng.lat.toFixed(7);
    const lng = e.latlng.lng.toFixed(7);

    document.getElementById('inputLat').value = lat;
    document.getElementById('inputLng').value = lng;

    if (userMarker) {
        userMarker.setLatLng(e.latlng);
    } else {
        userMarker = L.marker(e.latlng, {
            draggable: true
        }).addTo(pickerMap);

        userMarker.on('dragend', function(ev) {
            const pos = ev.target.getLatLng();
            document.getElementById('inputLat').value = pos.lat.toFixed(7);
            document.getElementById('inputLng').value = pos.lng.toFixed(7);
        });
    }

    userMarker.bindPopup(`<b>Titik Terpilih</b><br>Lat: ${lat}<br>Lng: ${lng}`).openPopup();

    const statusBadge = document.getElementById('pinStatus');
    statusBadge.innerText = 'Titik Berhasil Ditandai';
    statusBadge.className = 'text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg';
});
</script>
