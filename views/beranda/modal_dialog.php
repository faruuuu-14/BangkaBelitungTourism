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
        <div class="p-5 sm:p-6 bg-slate-900/95 border-t border-white/10 text-white space-y-1.5">
            <h3 id="photoModalTitle" class="text-base sm:text-lg font-black text-white"></h3>
            <p id="photoModalDesc" class="text-xs sm:text-sm text-slate-300 leading-relaxed"></p>
        </div>

    </div>
</div>

<!-- Logika JavaScript Pengendali Buka-Tutup Lightbox Modal -->
<script>
/**
 * Membuka dialog penampil foto layar penuh
 * @param {string} src Jalur path file gambar
 * @param {string} title Judul objek wisata/budaya
 * @param {string} desc Narasi sejarah/keunikan objek
 */
function openPhotoModal(src, title, desc) {
    const modal = document.getElementById('photoModal');
    const img = document.getElementById('photoModalImg');
    const t = document.getElementById('photoModalTitle');
    const d = document.getElementById('photoModalDesc');

    if (!modal || !img) return;

    img.src = src;
    img.alt = title;
    t.innerHTML = title;
    d.innerHTML = desc;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Mencegah scrolling latar belakang
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
