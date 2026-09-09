# 📚 PANDUAN PENGGUNAAN & KUSTOMISASI PERAYAP DATA (CRAWLER GOOGLE MAPS)
**Sistem Informasi Intelijen Pariwisata Provinsi Kepulauan Bangka Belitung**
*Penyusun: Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur Pangkalpinang*

---

## 🎯 1. Tentang Folder Ini
Folder `perayap_data/` ini merupakan arsip mandiri dan alat otomatis (*automated web scraping engine*) untuk mengekstraksi data objek wisata, amenitas (hotel/penginapan), dan restoran/kuliner secara terstruktur dari **Google Maps** menggunakan **Python Playwright**.

Data yang diekstraksi meliputi:
- **Nama Tempat / Destinasi**
- **Kelompok & Sub Kategori** (Alam, Buatan, Kebudayaan, Hotel, Restoran) sesuai standar Kemenparekraf (3A)
- **Koordinat Spasial Akurat** (Latitude & Longitude) langsung dari URL pin Google Maps
- **Rating Rata-rata** (Bintang 1.0 - 5.0)
- **Jumlah Ulasan Publik** (Volume interaksi wisatawan)
- **Kecamatan & Kabupaten/Kota** di Bangka Belitung

---

## 📂 2. Struktur File & Fungsinya

| Nama File | Fungsi & Deskripsi | Kapan Harus Diubah? |
| :--- | :--- | :--- |
| **`konfigurasi.py`** | Pusat pengaturan wilayah (kabupaten & kecamatan), kata kunci pencarian, jeda waktu (*delay*), dan folder penyimpanan output. | **UBAH DI SINI** jika ingin merayap wilayah baru atau kategori wisata baru. |
| **`jalankan_crawler.py`** | Titik masuk utama program (*entry point*). Menampilkan menu interaktif di terminal dan memandu proses perayapan langkah demi langkah. | Cukup dijalankan: `python jalankan_crawler.py`. |
| **`mesin_perayap.py`** | Mesin utama pengikis web (*Playwright Engine*). Membuka browser, mengetik pencarian di Google Maps, menggulir daftar (*auto-scroll*), dan mengekstrak data atribut. | Tidak perlu diubah kecuali ada pembaruan selector HTML Google Maps. |
| **`pengklasifikasi.py`** | Logika kecerdasan buatan berbasis aturan (*Rule-Based NLP*) untuk mengelompokkan nama tempat ke dalam kelompok **Daya Tarik** atau **Amenitas**. | Ubah di sini jika ada kata kunci jenis tempat baru. |
| **`impor_ke_database.php`** | Skrip otomatis untuk membaca file CSV/JSON hasil perayapan dan memasukkannya langsung ke tabel `tempat_wisata` di database MySQL `db_pariwisata_babel`. | Dijalankan setelah proses crawling selesai. |

---

## 🚀 3. Langkah Praktis: Cara Merayap Data Wisata Baru

Jika di masa depan (setelah magang atau untuk tugas akhir) Faru ingin merayap data baru, ikuti 4 langkah mudah berikut:

### Langkah 1: Pasang Kebutuhan Pustaka (Prerequisites)
Pastikan Python dan Playwright sudah terpasang di komputermu:
```bash
pip install playwright pandas
playwright install chromium
```

### Langkah 2: Atur Target Pencarian di `konfigurasi.py`
Buka file `perayap_data/konfigurasi.py`:
- Jika ingin menambah kata kunci pencarian baru (misal: "sentra oleh-oleh", "desa wisata"), tambahkan pada array `SEARCH_QUERIES`:
  ```python
  SEARCH_QUERIES = [
      "tempat wisata",
      "objek wisata",
      "hotel",
      "desa wisata",      # <--- Contoh penambahan baru
      "sentra kriya"       # <--- Contoh penambahan baru
  ]
  ```
- Jika ingin menambah wilayah kecamatan baru, sesuaikan pada dictionary `LOCATIONS`.

### Langkah 3: Jalankan Perayap Data
Buka terminal (PowerShell / Command Prompt) di folder proyek:
```bash
cd C:\laragon\www\BangkaBelitungTourism\perayap_data
python jalankan_crawler.py
```
Pilih opsi menu:
- Ketik `0` untuk merayap seluruh 7 Kabupaten/Kota secara lengkap.
- Atau ketik `1` sampai `7` untuk memilih kabupaten tertentu saja (misal: `1` untuk Kabupaten Bangka).

*Hasil perayapan otomatis tersimpan di folder `output/` dalam bentuk file `data_wisata_babel.csv` dan `data_wisata_babel.json`.*

### Langkah 4: Masukkan Data ke Database MySQL
Setelah proses perayapan selesai, masukkan datanya ke database web pariwisata:
```bash
php impor_ke_database.php
```
Data akan otomatis masuk ke tabel `tempat_wisata` pada database **`db_pariwisata_babel`** dengan status terverifikasi `Resmi`.

---

## 🛡️ 4. Fitur Keamanan & Anti-Blokir yang Terpasang
1. **Human-like Scrolling**: Menggunakan jeda waktu acak (*natural pause*) saat menggulir feed Google Maps agar menyerupai aktivitas manusia asli.
2. **Auto-Deduplication**: Sistem otomatis mengingat nama dan koordinat tempat yang sudah diambil sebelumnya. Jika proses perayapan terhenti di tengah jalan, kamu bisa melanjutkannya kembali (*resume*) tanpa khawatir data ganda.
3. **Autosave Interval**: Data otomatis disimpan setiap kali selesai merayap satu kecamatan, sehingga data tidak akan hilang jika koneksi internet terputus.

---
*Semoga file arsip dan panduan ini bermanfaat untuk bahan belajar mandiri, referensi kodingan skripsi, serta portofolio profesional Faru!*
