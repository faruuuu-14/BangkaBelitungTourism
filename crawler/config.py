"""
Konfigurasi Crawler Pariwisata Bangka Belitung
Mengatur daftar wilayah (Kabupaten & Kecamatan), kata kunci pencarian, dan pengaturan crawler.
"""

import os

# Direktori Output
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
OUTPUT_DIR = os.path.join(BASE_DIR, "output")
os.makedirs(OUTPUT_DIR, exist_ok=True)

# File Penyimpanan
OUTPUT_CSV = os.path.join(OUTPUT_DIR, "data_wisata_babel.csv")
OUTPUT_JSON = os.path.join(OUTPUT_DIR, "data_wisata_babel.json")

# Kategori Pencarian di Google Maps
SEARCH_QUERIES = [
    "tempat wisata",
    "objek wisata",
    "hotel",
    "penginapan",
    "homestay",
    "restoran",
    "rumah makan",
    "cafe"
]

# Pemetaan Wilayah Lengkap: 7 Kabupaten/Kota & Kecamatan di Prov. Kepulauan Bangka Belitung
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

# Pengaturan Browser & Delay
BROWSER_SETTINGS = {
    "headless": False,          # Tampilkan browser agar tidak mudah diblokir Google
    "timeout": 15,              # Detik waktu tunggu elemen
    "scroll_pause": 1.2,        # Jeda waktu antar scroll (detik)
    "max_scroll": 35,           # Batas maksimal scroll per pencarian
    "autosave_interval": 1      # Simpan data setiap selesai 1 kecamatan
}
