# 🌴 WebGIS & Spatial Decision Support System (SDSS) Pariwisata Kepulauan Bangka Belitung

Aplikasi **Sistem Informasi Geografis (SIG)** dan **Sistem Pendukung Keputusan Spasial (SPKS)** berbasis web untuk pemetaan, analisis aksesibilitas, dan optimasi rute destinasi pariwisata terintegrasi di 7 Kabupaten/Kota se-Provinsi Kepulauan Bangka Belitung.

🌐 **Live Demo (Hosting)**: [https://babeltourism.infinityfree.io](https://babeltourism.infinityfree.io)

---

## 👨‍💻 Pengembang / Author
- **Nama**: Fathurrahman Safari Lubis
- **NIM**: 2411500025
- **Program Studi / Institusi**: Institut Sains & Bisnis (ISB) Atma Luhur Pangkalpinang

---

## 🚀 Fitur Utama Sistem

### 1. Peta Interaktif & Amenitas Spasial (WebGIS)
- Pemetaan **875 titik destinasi riil** (Wisata Alam, Buatan, Budaya, Perhotelan/Homestay, dan Restoran/Kuliner).
- Pilihan mode visualisasi: **Marker Cluster**, **Peta Sebaran Pin**, dan **Peta Panas Kepadatan (*Heatmap Density*)**.
- Filter multi-level berdasarkan wilayah (7 Kabupaten/Kota) dan 5 kategori amenitas.
- Pencarian cerdas *live auto-complete* (bisa diketik langsung).

### 2. Smart Itinerary Planner (Optimasi Rute Wisata)
- Algoritma Heuristik **Traveling Salesperson Problem (TSP) via Nearest Neighbor** untuk menghitung urutan rute multi-stop paling hemat jarak.
- Integrasi langsung ke **Google Maps Multi-Stop Turn-by-Turn Navigation**.
- Estimasi jarak dan waktu tempuh antar-titik secara presisi dengan formula trigonometri **Haversine Distance**.

### 3. Analisis Aksesibilitas & Simpul Transportasi (Catchment Buffer)
- Analisis jangkauan aksesibilitas **Isochrone Ring (15 km, 30 km, 50 km)** dari 6 pintu gerbang transportasi utama (2 Bandara & 4 Pelabuhan se-Babel).
- Deteksi otomatis destinasi terisolir (*blind spots / ring 4*) untuk prioritas intervensi rute *feeder* atau *shuttle*.

### 4. SPK Rekomendasi Destinasi (Multi-Criteria Decision Making - TOPSIS)
- Metode **TOPSIS** (*Technique for Order Preference by Similarity to Ideal Solution*) dengan berbagai kriteria (Rating, Ulasan, Aksesibilitas Jarak, Fasilitas, dan Bobot Persona).
- Preset persona wisatawan: *Backpacker/Hemat*, *Keluarga/Santai*, *Mewah/Luxury*, dan *Solo Traveler*.
- Transparansi perhitungan matriks ternormalisasi terbobot dan solusi ideal positif/negatif.

### 5. Analisis Strategis & Kebijakan Pariwisata
- **Tipologi Klassen**: Klasifikasi pertumbuhan pariwisata 7 kabupaten/kota.
- **Tourism Carrying Capacity (TCC)**: Perhitungan daya tampung fisik dan ekologis destinasi harian standar UNWTO / Kemenparekraf.
- **Pola Spasial NNA (*Nearest Neighbor Analysis*)**: Analisis pola distribusi (*clustered / dispersed / random*).
- **Ekspor Dokumen Resmi Policy Brief Kemenparekraf** siap cetak A4.

---

## 🛠️ Arsitektur & Teknologi

- **Backend**: PHP 8 (Native OOP & Procedural Modular)
- **Database**: MySQL / MariaDB (PDO Secure Prepared Statements)
- **Frontend / Styling**: Tailwind CSS, FontAwesome 6 Icons
- **Peta Spasial**: Leaflet.js, CartoDB Voyager Tiles, Leaflet.heat, Leaflet.markercluster
- **Data Ingestion / Web Crawler**: Python 3 (Automated Google Maps Scraper & Classifier)

---

## 💻 Panduan Instalasi Lokal (Localhost)

1. **Clone Repository**:
   ```bash
   git clone https://github.com/aatthhuurr/BangkaBelitungTourism.git
   ```
2. **Pindahkan ke Direktori Web Server**:
   - Jika menggunakan Laragon: letakkan di `C:/laragon/www/BangkaBelitungTourism/`
   - Jika menggunakan XAMPP: letakkan di `C:/xampp/htdocs/BangkaBelitungTourism/`
3. **Konfigurasi Database**:
   - Buka phpMyAdmin / HeidiSQL, buat database baru: `db_babel_tourism`
   - Jalankan script import data:
     ```bash
     php scripts/setup_database.php
     ```
4. **Buka di Browser**:
   - Akses melalui: `http://localhost/BangkaBelitungTourism/` atau `http://bangkabelitungtourism.test/`

---

## 📄 Lisensi
Dikembangkan untuk keperluan Tugas Akhir / Skripsi & Publikasi Jurnal Ilmiah Pariwisata. Hak cipta dilindungi undang-undang.
