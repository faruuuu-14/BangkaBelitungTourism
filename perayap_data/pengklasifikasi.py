"""
==============================================================================
SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
==============================================================================

@file      pengklasifikasi.py
@folder    perayap_data/
@author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
@version   2.0.0 (Modul Pengklasifikasi Otomatis Standar Kemenparekraf 3A)

------------------------------------------------------------------------------
DESKRIPSI & FUNGSI FILE:
Modul kecerdasan buatan berbasis aturan teks (Rule-Based NLP Text Classification).
Berfungsi menganalisis nama tempat dan kategori mentah yang didapat dari Google Maps,
kemudian secara otomatis mengklasifikasikannya ke dalam:
1. Kelompok Utama: 'Daya Tarik' (Atraksi) atau 'Amenitas' (Fasilitas Pendukung).
2. Sub Kategori: 'Alam', 'Buatan', 'Kebudayaan', 'Hotel', atau 'Restoran'.

KETERKAITAN ALUR (DATA & LOGIKA):
- Dipanggil oleh: mesin_perayap.py pada fungsi ekstraksi data setiap pin tempat
- Menggunakan standar: Konsep 3A Pariwisata Kemenparekraf (Atraksi, Aksesibilitas, Amenitas)

TUJUAN DIBUATNYA FILE:
Menghilangkan proses pelabelan data secara manual (manual labeling) yang memakan
waktu ratusan jam untuk 875 objek, sekaligus memastikan konsistensi taksonomi
data pariwisata yang siap dihitung dalam analisis korelasi Pearson dan SPK TOPSIS.
==============================================================================
"""

import re

# --------------------------------------------------------------------------
# 1. KATA KUNCI DAYA TARIK ALAM (WISATA BAHARI, GEOPARK, BUKIT, DLL)
# Karakteristik alam kepulauan Bangka Belitung didominasi pantai pasir putih,
# batuan granit purba, pulau-pulau kecil, danau bekas tambang, serta perbukitan.
# --------------------------------------------------------------------------
KATA_KUNCI_ALAM = [
    "pantai", "beach", "pulau", "island", "tanjung", "teluk", "danau", "lake",
    "kaolin", "air terjun", "waterfall", "goa", "gua", "bukit", "gunung", "hill",
    "hutan", "forest", "mangrove", "batu", "karang", "sungai", "river", "laut",
    "taman nasional", "geopark", "konservasi", "penyu", "aliran", "air panas"
]

# --------------------------------------------------------------------------
# 2. KATA KUNCI DAYA TARIK KEBUDAYAAN & SEJARAH (WARISAN, KELENTENG, MASJID, MUSEUM)
# Merepresentasikan keharmonisan budaya Thionghoa dan Melayu (Tongin Fan Ngin Jit Jong),
# peninggalan kolonial pertimahan, sanggar seni cual, dan rumah adat.
# --------------------------------------------------------------------------
KATA_KUNCI_BUDAYA = [
    "museum", "cagar budaya", "makam", "keraton", "istana", "benteng", "fort",
    "kelenteng", "klenteng", "vihara", "vihear", "pagoda", "puri tri agung",
    "masjid jamik", "masjid raya", "rumah adat", "kampoeng", "kampung adat",
    "situs", "monumen", "tugu", "mercusuar", "laskar pelangi", "andrea hirata",
    "dambus", "cual", "tenun", "sanggar seni", "balai adat", "pesanggrahan"
]

# --------------------------------------------------------------------------
# 3. KATA KUNCI DAYA TARIK BUATAN (REKREASI & HIBURAN KELUARGA)
# --------------------------------------------------------------------------
KATA_KUNCI_BUATAN = [
    "waterboom", "waterpark", "kolam renang", "taman hiburan", "theme park",
    "agrowisata", "kebun binatang", "zoo", "taman kota", "alun-alun", "plaza",
    "arena bermain", "outbound", "wisata edukasi", "kampung kreatif"
]

# --------------------------------------------------------------------------
# 4. KATA KUNCI AMENITAS: AKOMODASI (HOTEL, RESORT, HOMESTAY)
# --------------------------------------------------------------------------
KATA_KUNCI_HOTEL = [
    "hotel", "resort", "inn", "guest house", "guesthouse", "homestay", "villa",
    "motel", "lodge", "penginapan", "cottage", "hostel", "stay", "kost"
]

# --------------------------------------------------------------------------
# 5. KATA KUNCI AMENITAS: KULINER (RESTORAN, RUMAH MAKAN, KAFE)
# --------------------------------------------------------------------------
KATA_KUNCI_RESTORAN = [
    "restoran", "restaurant", "rumah makan", "rm ", "warung", "resto", "cafe",
    "kafe", "kopi", "coffee", "seafood", "kuliner", "otak-otak", "mie koba",
    "mie belitung", "lempah", "bakso", "sate", "kedai", "dapur", "bistro", "eatery"
]


def tentukan_kategori_tempat(nama_tempat: str, kategori_gmaps: str = "") -> tuple[str, str]:
    """
    Fungsi cerdas untuk menentukan Kelompok Utama dan Sub Kategori tempat.
    
    Parameter:
    - nama_tempat (str): Nama tempat hasil crawl (misal: 'Pantai Tikus Emas Sungailiat')
    - kategori_gmaps (str): Label kategori bawaan Google Maps jika ada (misal: 'Taman Wisata Alam')
    
    Return:
    - tuple: (kelompok, sub_kategori)
      Contoh: ('Daya Tarik', 'Alam') atau ('Amenitas', 'Hotel')
    """
    teks_gabungan = f"{nama_tempat} {kategori_gmaps}".lower()

    # PRIORITAS 1: Klasifikasi Amenitas Hotel
    for kata in KATA_KUNCI_HOTEL:
        if re.search(r'\b' + re.escape(kata) + r'\b', teks_gabungan):
            return "Amenitas", "Hotel"

    # PRIORITAS 2: Klasifikasi Amenitas Restoran & Kuliner
    for kata in KATA_KUNCI_RESTORAN:
        if re.search(r'\b' + re.escape(kata) + r'\b', teks_gabungan):
            return "Amenitas", "Restoran"

    # PRIORITAS 3: Klasifikasi Daya Tarik Kebudayaan & Sejarah
    for kata in KATA_KUNCI_BUDAYA:
        if re.search(r'\b' + re.escape(kata) + r'\b', teks_gabungan):
            return "Daya Tarik", "Kebudayaan"

    # PRIORITAS 4: Klasifikasi Daya Tarik Alam
    for kata in KATA_KUNCI_ALAM:
        if re.search(r'\b' + re.escape(kata) + r'\b', teks_gabungan):
            return "Daya Tarik", "Alam"

    # PRIORITAS 5: Klasifikasi Daya Tarik Buatan
    for kata in KATA_KUNCI_BUATAN:
        if re.search(r'\b' + re.escape(kata) + r'\b', teks_gabungan):
            return "Daya Tarik", "Buatan"

    # Default Fallback cerdas: Bila tidak ada kata kunci yang cocok,
    # dianggap Daya Tarik Buatan (rekreasi lokal)
    return "Daya Tarik", "Buatan"
