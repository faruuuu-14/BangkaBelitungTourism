<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      modal_qr.php
 * @folder    components/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Pembuat Kode QR Wisata Pintar & Navigasi Cepat Smartphone)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen modal global untuk membuat dan menampilkan Kode QR (Quick Response)
 * secara instan (0 milidetik, tanpa dependensi API eksternal) untuk setiap destinasi
 * pariwisata Bangka Belitung. Menggunakan pustaka lokal QRCode.js HTML5 Canvas
 * sehingga bekerja sempurna baik saat online maupun offline.
 * 
 * FITUR UTAMA:
 * 1. Pustaka QR Mandiri Lokal (Client-side HTML5 Canvas / Image) - Sangat Cepat & Andal
 * 2. Dropdown Pemilih Cepat 150 Destinasi Wisata Terpopuler se-Bangka Belitung
 * 3. Deep-linking Google Maps Turn-by-Turn GPS Driving Navigation
 * 4. Salin Tautan Instan ke Clipboard dengan Indikator Visual
 * 5. Cetak Papan Petunjuk Fisik (Print Signpost Poster) ber-Kop Resmi Dinas Pariwisata
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: components/footer.php (Tersedia di seluruh halaman aplikasi)
 * - Dipanggil oleh: 
 *   a. Tombol QR di Navbar Header (openGenericQrModal)
 *   b. Balon Popup Titik di Peta WebGIS (openQrModal)
 *   c. Dialog Lightbox Foto Beranda (triggerPhotoModalQr)
 *   d. Kartu Podium Juara & Tabel SPK TOPSIS (openQrModal)
 * ==============================================================================
 */

require_once __DIR__ . '/../app/database.php';

$qrDestList = [];
try {
    $qrPdo = getDbConnection();
    $stmtQr = $qrPdo->query("
        SELECT id, nama, kabupaten, sub_kategori, latitude, longitude 
        FROM tempat_wisata 
        WHERE latitude IS NOT NULL AND longitude IS NOT NULL 
        ORDER BY ulasan DESC 
        LIMIT 150
    ");
    $qrDestList = $stmtQr->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $qrDestList = [];
}
?>

<!-- ============================================================== -->
<!-- MODAL GLOBAL KODE QR WISATA PINTAR (SMART TOURISM QR CODE)     -->
<!-- ============================================================== -->
<div id="modalQrWisata" 
     class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm items-center justify-center p-3 sm:p-5" 
     style="display: none; z-index: 99999;"
     onclick="if(event.target === this) closeQrModal();">
    
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200"
         onclick="event.stopPropagation();">
        
        <!-- Header Modal -->
        <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-950 via-ocean-950 to-indigo-950 text-white flex items-center justify-between shrink-0 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-ocean-500 text-white flex items-center justify-center font-black text-lg shadow-md shadow-ocean-500/30">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <div>
                    <h3 class="font-black text-sm sm:text-base text-white">Kode QR Lokasi &amp; Wisata Babel</h3>
                    <p class="text-[10px] sm:text-xs text-ocean-300 font-medium">Pindai dengan Smartphone untuk Navigasi Google Maps Langsung</p>
                </div>
            </div>
            <button onclick="closeQrModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Selector Destinasi Cepat (Bisa Pilih Destinasi Mana Pun) -->
        <div class="p-3.5 sm:p-4 bg-slate-50 border-b border-slate-200 shrink-0">
            <label class="block text-[11px] font-extrabold text-ocean-900 uppercase tracking-wider mb-1.5">
                <i class="fa-solid fa-location-dot text-ocean-600 mr-1"></i> Pilih Lokasi Wilayah / Destinasi Wisata:
            </label>
            <select id="qrQuickSelector" onchange="onQrQuickSelectChange(this)" class="w-full text-xs font-semibold rounded-xl border border-slate-300 bg-white p-2.5 text-slate-800 focus:ring-2 focus:ring-ocean-500 shadow-2xs">
                <option value="babel_provinsi" 
                    data-nama="Provinsi Kepulauan Bangka Belitung"
                    data-lat="-2.1465"
                    data-lng="106.1472"
                    data-kab="Negeri Serumpun Sebalai (Pulau Bangka &amp; Belitung)"
                    data-sub="Pusat Wilayah Provinsi">
                    ⭐ Provinsi Kepulauan Bangka Belitung (Letak Geografis &amp; Titik Koordinat)
                </option>
                <?php foreach ($qrDestList as $idx => $qd): ?>
                <option value="<?= htmlspecialchars($qd['id']) ?>" 
                    data-nama="<?= htmlspecialchars($qd['nama']) ?>"
                    data-lat="<?= (float)$qd['latitude'] ?>"
                    data-lng="<?= (float)$qd['longitude'] ?>"
                    data-kab="<?= htmlspecialchars($qd['kabupaten']) ?>"
                    data-sub="<?= htmlspecialchars($qd['sub_kategori']) ?>">
                    <?= htmlspecialchars($qd['nama']) ?> &bull; <?= htmlspecialchars($qd['kabupaten']) ?> (<?= htmlspecialchars($qd['sub_kategori']) ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Body Modal: Papan Tanda Wisata yang Dapat Dicetak -->
        <div class="p-5 sm:p-6 overflow-y-auto space-y-5" id="printableQrCard">
            
            <!-- Kartu Destinasi -->
            <div class="text-center space-y-1">
                <span id="qrBadgeKategori" class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold bg-ocean-100 text-ocean-800 border border-ocean-200 uppercase tracking-wider mb-1">
                    Destinasi Wisata
                </span>
                <h4 id="qrNamaDestinasi" class="text-lg sm:text-xl font-black text-slate-900 leading-tight">
                    Nama Destinasi
                </h4>
                <p id="qrWilayahDestinasi" class="text-xs text-slate-500 font-medium">
                    Kabupaten Bangka Belitung
                </p>
            </div>

            <!-- Wadah Gambar QR Code dengan Frame Elegan -->
            <div class="flex flex-col items-center justify-center">
                <div class="p-4 bg-white rounded-2xl border-2 border-dashed border-ocean-300 shadow-sm flex flex-col items-center">
                    
                    <!-- Target Wadah Render QR Code HTML5 Canvas / Image -->
                    <div id="qrCodeTarget" class="flex items-center justify-center min-w-[220px] min-h-[220px] p-2 bg-white rounded-xl">
                        <!-- Pustaka QRCode.js merender canvas di sini -->
                    </div>

                    <div class="mt-3 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold">
                            <i class="fa-brands fa-google text-red-500"></i> Google Maps Navigation
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Koordinat Geospasial & Panduan -->
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                <div class="flex items-center justify-between text-slate-600">
                    <span class="font-medium flex items-center gap-1.5">
                        <i class="fa-solid fa-location-crosshairs text-ocean-600"></i> Titik Koordinat:
                    </span>
                    <span id="qrKoordinatText" class="font-mono font-bold text-slate-800 text-[11px]">-</span>
                </div>
                <div class="flex items-center justify-between text-slate-600">
                    <span class="font-medium flex items-center gap-1.5">
                        <i class="fa-solid fa-mobile-screen-button text-emerald-600"></i> Kompatibilitas:
                    </span>
                    <span class="font-semibold text-slate-800 text-[11px]">Kamera HP, Google Lens, WhatsApp</span>
                </div>
                <p class="text-[10px] text-slate-500 pt-1 border-t border-slate-200 text-center">
                    Arahkan kamera smartphone ke kode QR di atas untuk memulai navigasi rute perjalanan secara otomatis.
                </p>
            </div>

        </div>

        <!-- Footer Modal: Tombol Aksi -->
        <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between gap-2 shrink-0">
            <div class="flex items-center gap-2">
                <button onclick="copyQrNavigationLink()" id="btnCopyQrLink" class="px-3 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-copy text-slate-600"></i>
                    <span id="copyQrBtnText">Salin Tautan</span>
                </button>
                <button onclick="printQrPoster()" class="px-3 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-print text-slate-600"></i>
                    <span>Cetak Kartu</span>
                </button>
            </div>
            
            <a id="btnBukaGmapsDirect" href="#" target="_blank" class="px-4 py-2 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-ocean-600/30">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>Buka di Google Maps</span>
            </a>
        </div>

    </div>
</div>

<!-- Logika JavaScript Pengendali QR Code Mandiri -->
<script>
let currentGmapsNavUrl = '';

/**
 * Pemanggil Generator QR dari Menu Header Navbar
 * Secara default langsung menampilkan Kode QR Letak Provinsi Kepulauan Bangka Belitung
 */
function openGenericQrModal() {
    openBabelProvinsiQrModal();
}

/**
 * Membuka Kode QR khusus Letak Geografis Provinsi Kepulauan Bangka Belitung
 */
function openBabelProvinsiQrModal() {
    const sel = document.getElementById('qrQuickSelector');
    if (sel) {
        sel.selectedIndex = 0;
    }
    openQrModal(
        'Provinsi Kepulauan Bangka Belitung',
        -2.1465,
        106.1472,
        'Negeri Serumpun Sebalai (Pulau Bangka & Belitung)',
        'Letak Geografis Provinsi'
    );
}

/**
 * Menangani pergantian pilihan dari dropdown di dalam modal
 */
function onQrQuickSelectChange(selectEl) {
    if (!selectEl) return;
    const opt = selectEl.options[selectEl.selectedIndex];
    if (!opt) return;
    openQrModal(
        opt.getAttribute('data-nama'),
        opt.getAttribute('data-lat'),
        opt.getAttribute('data-lng'),
        opt.getAttribute('data-kab'),
        opt.getAttribute('data-sub')
    );
}

/**
 * Membuka Modal QR Code Wisata Pintar
 * @param {string} nama Nama destinasi wisata / provinsi
 * @param {number|string} lat Latitude koordinat
 * @param {number|string} lng Longitude koordinat
 * @param {string} kab Nama Kabupaten/Kota
 * @param {string} sub Kategori/Sub-kategori
 */
function openQrModal(nama, lat, lng, kab = '', sub = '') {
    const modal = document.getElementById('modalQrWisata');
    if (!modal) return;

    // Bersihkan format input
    const cleanNama = nama || 'Provinsi Kepulauan Bangka Belitung';
    const cleanLat = parseFloat(lat) || -2.1465;
    const cleanLng = parseFloat(lng) || 106.1472;
    const cleanKab = kab || 'Negeri Serumpun Sebalai (Pulau Bangka & Belitung)';
    const cleanSub = sub || 'Pusat Wilayah Provinsi';

    // Perbarui Teks UI
    const elNama = document.getElementById('qrNamaDestinasi');
    const elKab = document.getElementById('qrWilayahDestinasi');
    const elSub = document.getElementById('qrBadgeKategori');
    const elCoord = document.getElementById('qrKoordinatText');

    if (elNama) elNama.innerText = cleanNama;
    if (elKab) elKab.innerText = cleanKab;
    if (elSub) elSub.innerText = cleanSub;
    if (elCoord) elCoord.innerText = `${cleanLat.toFixed(6)}, ${cleanLng.toFixed(6)}`;

    // Sinkronisasi dropdown jika nama cocok
    const sel = document.getElementById('qrQuickSelector');
    if (sel) {
        for (let i = 0; i < sel.options.length; i++) {
            if (sel.options[i].getAttribute('data-nama') === cleanNama) {
                sel.selectedIndex = i;
                break;
            }
        }
    }

    // Konstruksi URL Navigasi Google Maps
    if (cleanNama.includes('Provinsi Kepulauan Bangka Belitung')) {
        currentGmapsNavUrl = `https://www.google.com/maps/search/?api=1&query=Provinsi+Kepulauan+Bangka+Belitung`;
    } else {
        currentGmapsNavUrl = `https://www.google.com/maps/dir/?api=1&destination=${cleanLat},${cleanLng}&travelmode=driving`;
    }
    
    // Tautan tombol langsung
    const btnDirect = document.getElementById('btnBukaGmapsDirect');
    if (btnDirect) {
        btnDirect.href = currentGmapsNavUrl;
    }

    // Render QR Code secara instan menggunakan QRCode.js lokal (0 ms, offline-ready)
    const target = document.getElementById('qrCodeTarget');
    if (target) {
        target.innerHTML = '';
        try {
            if (typeof QRCode !== 'undefined') {
                new QRCode(target, {
                    text: currentGmapsNavUrl,
                    width: 200,
                    height: 200,
                    colorDark: "#0c4a6e",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.M
                });
            } else {
                // Fallback online jika pustaka belum selesai dimuat
                target.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(currentGmapsNavUrl)}" class="w-48 h-48 object-contain" alt="QR Code">`;
            }
        } catch (e) {
            target.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(currentGmapsNavUrl)}" class="w-48 h-48 object-contain" alt="QR Code">`;
        }
    }

    // Reset tombol copy
    resetCopyQrBtn();

    // Tampilkan Modal secara absolut
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

/**
 * Menutup Modal QR Code
 */
function closeQrModal() {
    const modal = document.getElementById('modalQrWisata');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

/**
 * Menyalin tautan navigasi Google Maps ke clipboard
 */
function copyQrNavigationLink() {
    if (!currentGmapsNavUrl) return;

    navigator.clipboard.writeText(currentGmapsNavUrl).then(() => {
        const txt = document.getElementById('copyQrBtnText');
        const btn = document.getElementById('btnCopyQrLink');
        if (txt) txt.innerText = 'Tersalin!';
        if (btn) {
            btn.classList.remove('bg-slate-200', 'text-slate-800');
            btn.classList.add('bg-emerald-600', 'text-white');
        }

        setTimeout(() => {
            resetCopyQrBtn();
        }, 2500);
    }).catch(err => {
        alert('Gagal menyalin tautan: ' + err);
    });
}

function resetCopyQrBtn() {
    const txt = document.getElementById('copyQrBtnText');
    const btn = document.getElementById('btnCopyQrLink');
    if (txt) txt.innerText = 'Salin Tautan';
    if (btn) {
        btn.classList.remove('bg-emerald-600', 'text-white');
        btn.classList.add('bg-slate-200', 'text-slate-800');
    }
}

/**
 * Mencetak Kartu Papan Informasi Fisik (Print Signpost Poster)
 */
function printQrPoster() {
    const printContents = document.getElementById('printableQrCard').innerHTML;
    const printWindow = window.open('', '_blank', 'width=600,height=700');
    if (!printWindow) return;
    const namaWisata = document.getElementById('qrNamaDestinasi') ? document.getElementById('qrNamaDestinasi').innerText : 'Destinasi Babel';
    printWindow.document.write('<!DOCTYPE html><html><head><title>Cetak Papan Petunjuk Wisata - ' + namaWisata + '</title><style>body { font-family: sans-serif; padding: 40px; background: white; text-align: center; } .print-box { border: 2px solid #0284c7; border-radius: 20px; padding: 30px; max-width: 480px; margin: 0 auto; } canvas, img { margin: 20px auto !important; max-width: 200px; display: inline-block; }</style></head><body><div class="print-box"><div style="font-size: 11px; font-weight: bold; color: #0284c7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">DINAS PARIWISATA &bull; KEPULAUAN BANGKA BELITUNG</div>' + printContents + '<div style="font-size: 10px; color: #64748b; margin-top: 20px;">Diproduksi oleh Babel Tourism Hub &bull; Pengembang: Fathurrahman Safari Lubis (NIM: 2411500025)</div></div></body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        try {
            printWindow.print();
            printWindow.close();
        } catch (e) {}
    }, 500);
}

// Tutup modal jika tombol ESC ditekan
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeQrModal();
    }
});
</script>