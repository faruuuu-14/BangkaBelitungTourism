"""
Modul Klasifikasi Pariwisata & Amenitas
Mengelompokkan data Google Maps ke dalam konsep 3A Pariwisata (Daya Tarik: Alam, Buatan, Budaya; Amenitas: Hotel, Restoran).
"""

# Daftar Kata Kunci Amenitas - Hotel / Penginapan
KEYWORDS_HOTEL = [
    "hotel", "resort", "villa", "guest house", "guesthouse", "homestay",
    "penginapan", "hostel", "losmen", "motel", "cottage", "pondok wisata",
    "glamping", "inn", "lodging"
]

# Daftar Kata Kunci Amenitas - Restoran / Kuliner
KEYWORDS_RESTORAN = [
    "restaurant", "restoran", "rumah makan", "warung", "bakso", "mie",
    "soto", "cafe", "coffee", "kedai", "seafood", "food court", "kuliner",
    "dapur", "bistro", "pempek", "otak-otak", "lempah", "kopi", "angkringan",
    "bar", "bakery", "roti"
]

# Daftar Kata Kunci Daya Tarik Wisata - Alam
KEYWORDS_ALAM = [
    "pantai", "beach", "pulau", "island", "bukit", "hill", "gunung",
    "mountain", "air terjun", "waterfall", "curug", "danau", "lake",
    "goa", "gua", "cave", "hutan", "forest", "mangrove", "teluk",
    "tanjung", "batu granit", "geopark", "aliran sungai", "riang"
]

# Daftar Kata Kunci Daya Tarik Wisata - Buatan
KEYWORDS_BUATAN = [
    "taman", "park", "waterpark", "waterboom", "theme park", "kolam renang",
    "agrowisata", "kebun binatang", "zoo", "wisata edukasi", "danau buatan",
    "kolong", "kampoeng", "kampung wisata", "spot foto", "alun-alun"
]

# Daftar Kata Kunci Daya Tarik Wisata - Kebudayaan & Sejarah
KEYWORDS_BUDAYA = [
    "museum", "candi", "vihara", "klenteng", "kelenteng", "masjid bersejarah",
    "gereja tua", "kampung adat", "keraton", "monumen", "situs", "makam bersejarah",
    "benteng", "mercusuar", "heritage", "rumah panggung", "pesanggrahan"
]


def classify_place(nama: str, kategori_google: str) -> tuple[str, str]:
    """
    Mengklasifikasikan tempat berdasarkan kategori resmi Google Maps dan nama tempat.
    Return: (kelompok, sub_kategori)
    Contoh: ("Daya Tarik", "Alam") atau ("Amenitas", "Hotel")
    """
    cat_lower = (kategori_google or "").lower()
    name_lower = (nama or "").lower()
    combined_text = f"{cat_lower} {name_lower}"

    # 1. Cek Kategori Hotel / Penginapan
    for kw in KEYWORDS_HOTEL:
        if kw in cat_lower or kw in name_lower:
            return "Amenitas", "Hotel"

    # 2. Cek Kategori Restoran / Kuliner
    for kw in KEYWORDS_RESTORAN:
        if kw in cat_lower or kw in name_lower:
            return "Amenitas", "Restoran"

    # 3. Cek Kategori Budaya & Sejarah
    for kw in KEYWORDS_BUDAYA:
        if kw in cat_lower or kw in name_lower:
            return "Daya Tarik", "Kebudayaan"

    # 4. Cek Kategori Alam
    for kw in KEYWORDS_ALAM:
        if kw in cat_lower or kw in name_lower:
            return "Daya Tarik", "Alam"

    # 5. Cek Kategori Buatan
    for kw in KEYWORDS_BUATAN:
        if kw in cat_lower or kw in name_lower:
            return "Daya Tarik", "Buatan"

    # Fallback Default jika belum terdeteksi spesifik
    return "Daya Tarik", "Lainnya"
