"""
==============================================================================
SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
==============================================================================

@file      konfigurasi.py
@folder    perayap_data/
@author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
@version   2.0.0 (Modul Perayap Data Otomatis Google Maps)

------------------------------------------------------------------------------
DESKRIPSI & FUNGSI FILE:
Pusat konfigurasi utama untuk program perayap data (crawler) Google Maps.
Mengatur target direktori penyimpanan hasil (CSV & JSON), daftar kata kunci
pencarian (search queries) untuk objek wisata dan fasilitas pendukung (amenitas),
pemetaan lengkap 7 Kabupaten/Kota beserta kecamatannya di Provinsi Kepulauan
Bangka Belitung, serta parameter delay browser Playwright.

KETERKAITAN ALUR (DATA & LOGIKA):
- Diimpor oleh: jalankan_crawler.py dan mesin_perayap.py
- Menghasilkan output ke: perayap_data/output/data_wisata_babel.csv & .json
- Dibaca oleh: impor_ke_database.php untuk dimasukkan ke MySQL db_pariwisata_babel

TUJUAN DIBUATNYA FILE:
Memisahkan data konfigurasi dari logika pemrograman (Separation of Concerns).
Jika Faru ingin menambah destinasi, menambah kata kunci, atau mengubah delay,
Faru CUKUP mengedit file ini tanpa perlu membongkar logika mesin perayap.
==============================================================================
"""

import os

# --------------------------------------------------------------------------
# 1. PENGATURAN DIREKTORI PENYIMPANAN OUTPUT
# Menentukan lokasi folder dan nama file output tempat data hasil crawl disimpan.
# --------------------------------------------------------------------------
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
OUTPUT_DIR = os.path.join(BASE_DIR, "output")
os.makedirs(OUTPUT_DIR, exist_ok=True)  # Buat folder output otomatis jika belum ada

# Path file output dalam dua format standar (CSV untuk Excel, JSON untuk Web)
OUTPUT_CSV = os.path.join(OUTPUT_DIR, "data_wisata_babel.csv")
OUTPUT_JSON = os.path.join(OUTPUT_DIR, "data_wisata_babel.json")

# --------------------------------------------------------------------------
# 2. KATA KUNCI PENCARIAN GOOGLE MAPS (SEARCH QUERIES)
# Kumpulan kata kunci yang diketikkan otomatis oleh robot Playwright di kolom search.
# TIP BELAJAR FARU: Tambahkan kata kunci baru di sini jika ingin mencari jenis tempat lain!
# --------------------------------------------------------------------------
SEARCH_QUERIES = [
    "tempat wisata",      # Objek wisata alam / rekreasi umum
    "objek wisata",       # Variasi istilah daya tarik wisata
    "pantai",             # Daya tarik pesisir khas Bangka Belitung
    "hotel",              # Fasilitas akomodasi amenitas bintang & melati
    "penginapan",         # Fasilitas akomodasi alternatif
    "homestay",           # Penginapan berbasis komunitas / desa wisata
    "restoran",           # Fasilitas kuliner & amenitas makan
    "rumah makan",        # Kuliner tradisional lokal khas Babel
    "cafe"                # Tempat santai & amenitas kekinian
]

# --------------------------------------------------------------------------
# 3. PEMETAAN WILAYAH GEOGRAFIS (7 KABUPATEN/KOTA & KECAMATAN)
# Struktur dictionary berjenjang: Kunci = Nama Kabupaten, Nilai = List Kecamatan.
# Robot akan mencari setiap query di atas dikombinasikan dengan tiap kecamatan di sini.
# --------------------------------------------------------------------------
LOCATIONS = {
    "Kabupaten Bangka": [
        "Sungailiat",
        "Belinyu",
        "Pemali",
        "Merawang",
        "Mendo Barat",
        "Riau Silip",
        "Bakam",
        "Puding Besar"
    ],
    "Kabupaten Bangka Barat": [
        "Mentok",
        "Simpang Teritip",
        "Jebus",
        "Kelapa",
        "Tempilang",
        "Parittiga"
    ],
    "Kabupaten Bangka Tengah": [
        "Koba",
        "Pangkalan Baru",
        "Sungai Selan",
        "Simpang Katis",
        "Namang",
        "Lubuk Besar"
    ],
    "Kabupaten Bangka Selatan": [
        "Toboali",
        "Air Gegas",
        "Payung",
        "Simpang Rimba",
        "Tukak Sadai",
        "Pulau Besar",
        "Lepar Pongok",
        "Kepulauan Pongok"
    ],
    "Kabupaten Belitung": [
        "Tanjung Pandan",
        "Sijuk",
        "Badau",
        "Membalong",
        "Selat Nasik"
    ],
    "Kabupaten Belitung Timur": [
        "Manggar",
        "Gantung",
        "Kelapa Kampit",
        "Damar",
        "Dendang",
        "Simpang Pesak",
        "Simpang Renggiang"
    ],
    "Kota Pangkalpinang": [
        "Bukit Intan",
        "Gabek",
        "Gerunggang",
        "Girimaya",
        "Pangkal Balam",
        "Rangkui",
        "Taman Sari"
    ]
}

# --------------------------------------------------------------------------
# 4. PENGATURAN BROWSER & PARAMETER DELAY (ANTI-BLOKIR GOOGLE)
# Menghindari blokir sistem anti-bot Google dengan jeda scrolling yang wajar.
# --------------------------------------------------------------------------
PENGATURAN_BROWSER = {
    "headless": False,          # Tampilkan jendela browser agar transparan dan tidak dicurigai bot
    "timeout": 15,              # Batas waktu maksimal tunggu elemen (detik)
    "scroll_pause": 1.2,        # Jeda waktu antar guliran mouse (detik)
    "max_scroll": 35,           # Batas maksimal guliran daftar hasil per kata kunci
    "autosave_interval": 1      # Simpan file CSV/JSON otomatis setiap selesai 1 kecamatan
}
