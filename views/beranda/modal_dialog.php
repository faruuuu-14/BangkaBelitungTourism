<?php
/**
 * ==============================================================================
 * SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
 * ==============================================================================
 * 
 * @file      modal_dialog.php
 * @folder    views/beranda/
 * @author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
 * @version   2.0.0 (Dialog Modal Lightbox Foto Layar Penuh & Panduan Sistem)
 * 
 * ------------------------------------------------------------------------------
 * DESKRIPSI & FUNGSI FILE:
 * Komponen dialog modal interaktif pada beranda. Menyediakan penampil foto
 * layar penuh (Fullscreen Photo Lightbox Modal) beresolusi tinggi dengan narasi
 * kebudayaan/sejarah lengkap, serta fungsi JavaScript pengendali buka-tutup modal.
 * 
 * KETERKAITAN ALUR (DATA & LOGIKA):
 * - Dimuat oleh: views/beranda/index.php
 * - Dipanggil oleh: tombol 'Lihat Foto Layar Penuh' di galeri_wilayah.php
 * 
 * TUJUAN DIBUATNYA FILE:
 * Meningkatkan kepuasan interaksi pengguna (IMK User Satisfaction) melalui
 * pengalaman visual imersif tanpa perlu memuat ulang (reload) halaman web.
 * ==============================================================================
 */
?>

<!-- ============================================================== -->
<!-- 7. DIALOG MODAL LIGHTBOX FOTO LAYAR PENUH                      -->
<!-- ============================================================== -->
<div id="photoModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-md flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="relative max-w-4xl w-full bg-slate-900 rounded-3xl overflow-hidden border border-white/15 shadow-2xl flex flex-col max-h-[90vh]">
        
        <!-- Tombol Tutup Modal -->
        <button onclick="closePhotoModal()" class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-black/60 hover:bg-black/80 text-white flex items-center justify-center border border-white/20 transition-all">
            <i class="fa-solid fa-xmark text-base"></i>
        </button>

        <!-- Wadah Gambar Foto Layar Penuh -->
        <div class="relative flex-1 bg-black flex items-center justify-center overflow-hidden min-h-[280px] sm:min-h-[400px]">
            <img id="photoModalImg" src="" alt="" class="max-w-full max-h-[65vh] object-contain">
        </div>

        <!-- Deskripsi & Narasi Objek Wisata -->
        <div class="p-5 sm:p-6 bg-slate-900/95 border-t border-white/10 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1 max-w-2xl">
                <h3 id="photoModalTitle" class="text-base sm:text-lg font-black text-white"></h3>
                <p id="photoModalDesc" class="text-xs sm:text-sm text-slate-300 leading-relaxed"></p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
                <button onclick="triggerPhotoModalQr()" class="px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-qrcode text-amber-300"></i>
                    <span>Kode QR</span>
                </button>
                <a id="photoModalMapBtn" href="index.php?page=peta" class="px-4 py-2.5 rounded-xl bg-ocean-600 hover:bg-ocean-500 text-white font-bold text-xs shadow-lg shadow-ocean-600/30 flex items-center gap-2 transition">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Jelajahi di Peta</span>
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Logika JavaScript Pengendali Buka-Tutup Lightbox Modal -->
<script>
let currentPhotoTitle = '';
let currentPhotoKeyword = '';

const iconSpotCoords = {
    'tikus emas': { lat: -1.8617, lng: 106.1264, kab: 'Kabupaten Bangka', sub: 'Wisata Bahari' },
    'tanjung ular': { lat: -1.9730, lng: 105.1585, kab: 'Kabupaten Bangka Barat', sub: 'Wisata Bahari & Cagar Sejarah' },
    'kaolin': { lat: -2.5186, lng: 106.4172, kab: 'Kabupaten Bangka Tengah', sub: 'Wisata Alam & Geowisata' },
    'batu perahu': { lat: -3.0034, lng: 106.4485, kab: 'Kabupaten Bangka Selatan', sub: 'Wisata Bahari' },
    'tanjung tinggi': { lat: -2.5519, lng: 107.7128, kab: 'Kabupaten Belitung', sub: 'UNESCO Global Geopark' },
    'laskar pelangi': { lat: -2.9644, lng: 108.1561, kab: 'Kabupaten Belitung Timur', sub: 'Wisata Edukasi & Budaya' },
    'timah': { lat: -2.1215, lng: 106.1130, kab: 'Kota Pangkalpinang', sub: 'Wisata Sejarah & Cagar Budaya' },
    'lengkuas': { lat: -2.5312, lng: 107.6213, kab: 'Kabupaten Belitung', sub: 'Wisata Bahari & Mercusuar' }
};

/**
 * Membuka dialog penampil foto layar penuh
 * @param {string} src Jalur path file gambar
 * @param {string} title Judul objek wisata/budaya
 * @param {string} desc Narasi sejarah/keunikan objek
 * @param {string} searchKeyword Kata kunci untuk pencarian di peta WebGIS
 */
function openPhotoModal(src, title, desc, searchKeyword = '') {
    const modal = document.getElementById('photoModal');
    const img = document.getElementById('photoModalImg');
    const t = document.getElementById('photoModalTitle');
    const d = document.getElementById('photoModalDesc');
    const mapBtn = document.getElementById('photoModalMapBtn');

    if (!modal || !img) return;

    currentPhotoTitle = title;
    currentPhotoKeyword = searchKeyword || title.split('&bull;')[0].trim();

    img.src = src;
    img.alt = title;
    t.innerHTML = title;
    d.innerHTML = desc;

    if (mapBtn) {
        mapBtn.href = `index.php?page=peta&search=${encodeURIComponent(currentPhotoKeyword)}`;
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Mencegah scrolling latar belakang
}

/**
 * Memicu modal QR Code dari dialog foto beranda
 */
function triggerPhotoModalQr() {
    const raw = (currentPhotoKeyword || currentPhotoTitle).toLowerCase();
    let found = null;
    for (const key in iconSpotCoords) {
        if (raw.includes(key)) {
            found = iconSpotCoords[key];
            break;
        }
    }

    const cleanTitle = currentPhotoTitle.replace(/<[^>]*>?/gm, '').split('•')[0].trim();
    if (found && typeof openQrModal === 'function') {
        openQrModal(cleanTitle, found.lat, found.lng, found.kab, found.sub);
    } else if (typeof openQrModal === 'function') {
        openQrModal(cleanTitle, -2.1215, 106.1130, 'Kepulauan Bangka Belitung', 'Pariwisata Babel');
    }
}

/**
 * Menutup dialog penampil foto layar penuh
 */
function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = ''; // Mengembalikan scrolling halaman
}

// Tutup modal jika pengguna menekan tombol ESC pada keyboard
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});
</script>
