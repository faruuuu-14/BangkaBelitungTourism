"""
==============================================================================
SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
==============================================================================

@file      jalankan_crawler.py
@folder    perayap_data/
@author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
@version   2.0.0 (Titik Masuk Utama Program Perayap Data)

------------------------------------------------------------------------------
DESKRIPSI & FUNGSI FILE:
Skrip utama yang dijalankan oleh Faru melalui terminal untuk memulai proses
crawling data dari Google Maps. Menyediakan menu pilihan interaktif (pilih
seluruh provinsi atau per kabupaten), memuat data lama agar tidak terjadi
duplikasi (deduplikasi data cerdas), memanggil mesin perayap, dan menyimpan
hasil akhir ke format CSV dan JSON.

KETERKAITAN ALUR (DATA & LOGIKA):
- Membaca konfigurasi dari: konfigurasi.py (wilayah, query, delay)
- Menggunakan mesin: mesin_perayap.py (MesinPerayapGoogleMaps)
- Menghasilkan file: perayap_data/output/data_wisata_babel.csv & JSON
- Selanjutnya diimpor ke MySQL oleh: impor_ke_database.php

TUJUAN DIBUATNYA FILE:
Memberikan antarmuka CLI (Command Line Interface) yang ramah pengguna, aman,
terstruktur, dan mudah dipahami alur eksekusinya dari awal hingga akhir.
==============================================================================
"""

import os
import sys
import time
import json
import pandas as pd

# Konfigurasi encoding UTF-8 untuk konsol Windows agar tidak crash pada karakter khusus
if sys.platform == "win32":
    try:
        sys.stdout.reconfigure(encoding="utf-8")
        sys.stderr.reconfigure(encoding="utf-8")
    except Exception:
        pass

from konfigurasi import LOCATIONS, SEARCH_QUERIES, PENGATURAN_BROWSER, OUTPUT_CSV, OUTPUT_JSON, OUTPUT_DIR
from mesin_perayap import MesinPerayapGoogleMaps


def muat_data_tersimpan() -> tuple[list[dict], set]:
    """
    LOGIKA 1: Memuat data yang sudah pernah di-crawl sebelumnya.
    Tujuannya adalah fitur 'Resume': jika proses terhenti di tengah jalan,
    crawler tidak akan mengambil data yang sama dua kali.
    """
    data_lama = []
    kunci_unik = set()

    if os.path.exists(OUTPUT_CSV):
        try:
            df = pd.read_csv(OUTPUT_CSV)
            data_lama = df.to_dict(orient="records")
            for item in data_lama:
                # Kunci unik gabungan nama tempat dan koordinat GPS (dibulatkan 3 desimal)
                nama_norm = str(item.get("nama", "")).strip().lower()
                lat_norm = round(float(item.get("latitude", 0) or 0), 3)
                lng_norm = round(float(item.get("longitude", 0) or 0), 3)
                kunci_unik.add((nama_norm, lat_norm, lng_norm))
            print(f"[INFO] Ditemukan data tersimpan sebelumnya: {len(data_lama)} entri unik.")
        except Exception as e:
            print(f"[PERINGATAN] Gagal membaca data lama: {e}")

    return data_lama, kunci_unik


def simpan_ke_file(data: list[dict]):
    """
    LOGIKA 2: Menyimpan data yang terkumpul ke file CSV (Excel) dan JSON (Web).
    """
    if not data:
        return

    os.makedirs(OUTPUT_DIR, exist_ok=True)
    df = pd.DataFrame(data)

    # 1. Simpan CSV dengan encoding UTF-8-SIG (agar karakter aksen terbaca rapi di Microsoft Excel)
    df.to_csv(OUTPUT_CSV, index=False, encoding="utf-8-sig")

    # 2. Simpan JSON untuk pertukaran data API Web
    with open(OUTPUT_JSON, "w", encoding="utf-8") as f:
        json.dump(data, f, ensure_ascii=False, indent=2)


def tampilkan_rekapitulasi(data: list[dict]):
    """
    LOGIKA 3: Mencetak ringkasan statistik data pariwisata yang berhasil dikumpulkan.
    """
    if not data:
        print("[INFO] Belum ada data terkumpul.")
        return

    df = pd.DataFrame(data)
    print("\n" + "=" * 65)
    print("📊 REKAPITULASI HASIL PERAYAPAN DATA PARIWISATA BABEL")
    print("=" * 65)
    print(f"Total Destinasi & Amenitas Terkumpul : {len(df)} tempat")
    print("\nBerdasarkan Kelompok Kemenparekraf (3A):")
    if "kelompok" in df.columns:
        print(df["kelompok"].value_counts().to_string())
    print("\nBerdasarkan Sub Kategori:")
    if "sub_kategori" in df.columns:
        print(df["sub_kategori"].value_counts().to_string())
    print("=" * 65)
    print(f"📁 Lokasi File CSV  : {OUTPUT_CSV}")
    print(f"📁 Lokasi File JSON : {OUTPUT_JSON}")
    print("=" * 65 + "\n")


def main():
    """
    Fungsi Utama Program Perayap Data.
    """
    print("\n" + "=" * 65)
    print("🌴 MESIN PERAYAP DATA PARIWISATA BANGKA BELITUNG (GOOGLE MAPS)")
    print("   Pengembang: Fathurrahman Safari Lubis (NIM: 2411500025)")
    print("=" * 65)

    # Muat data yang sudah ada sebelumnya
    semua_data, kunci_terdaftar = muat_data_tersimpan()

    # Tampilkan menu pilihan wilayah
    daftar_kabupaten = list(LOCATIONS.keys())
    print("\nPilih Cakupan Wilayah yang Ingin Dirayap:")
    print("0. Seluruh Provinsi Bangka Belitung (Lengkap 7 Kabupaten/Kota)")
    for i, kab in enumerate(daftar_kabupaten, start=1):
        print(f"{i}. {kab} ({len(LOCATIONS[kab])} Kecamatan)")
    print("q. Keluar Program")

    pilihan = input("\nKetik nomor pilihan [0-7 / q]: ").strip()
    if pilihan.lower() == "q":
        print("[INFO] Program selesai.")
        return

    # Tentukan kabupaten mana yang akan diproses
    kabupaten_dipilih = []
    if pilihan == "0":
        kabupaten_dipilih = daftar_kabupaten
    elif pilihan.isdigit() and 1 <= int(pilihan) <= len(daftar_kabupaten):
        kabupaten_dipilih = [daftar_kabupaten[int(pilihan) - 1]]
    else:
        print("[PERINGATAN] Pilihan tidak valid, membatalkan proses.")
        return

    # Inisialisasi dan jalankan mesin perayap browser
    mesin = MesinPerayapGoogleMaps()
    print("\n[INFO] Membuka browser Chromium...")
    mesin.mulai_browser()

    entri_baru = 0
    try:
        for kab in kabupaten_dipilih:
            kecamatan_list = LOCATIONS[kab]
            print(f"\n=======================================================")
            print(f"📍 MEMPROSES: {kab.upper()} ({len(kecamatan_list)} KECAMATAN)")
            print(f"=======================================================")

            for kec in kecamatan_list:
                print(f"\n>> Menjelajah Kecamatan: {kec}...")

                for query in SEARCH_QUERIES:
                    # Ambil data dari Google Maps untuk kombinasi query + kecamatan ini
                    hasil = mesin.rayap_wilayah(
                        kata_kunci=query,
                        kecamatan=kec,
                        kabupaten=kab,
                        batas_scroll=PENGATURAN_BROWSER["max_scroll"]
                    )

                    # Deduplikasi: Simpan hanya jika belum pernah terdaftar sebelumnya
                    for item in hasil:
                        kunci = (
                            str(item.get("nama", "")).strip().lower(),
                            round(float(item.get("latitude", 0) or 0), 3),
                            round(float(item.get("longitude", 0) or 0), 3)
                        )
                        if kunci not in kunci_terdaftar:
                            kunci_terdaftar.add(kunci)
                            semua_data.append(item)
                            entri_baru += 1

                # Simpan otomatis (autosave) setiap kali selesai 1 kecamatan
                simpan_ke_file(semua_data)
                print(f"   [AUTOSAVE] Data tersimpan sementara ({len(semua_data)} total).")

    except KeyboardInterrupt:
        print("\n[PERINGATAN] Proses dihentikan manual oleh pengguna (Ctrl+C).")
    finally:
        print("\n[INFO] Menutup browser...")
        mesin.tutup_browser()
        simpan_ke_file(semua_data)

    print(f"\n[SUKSES] Perayapan selesai! Menambahkan {entri_baru} tempat baru.")
    tampilkan_rekapitulasi(semua_data)
    print("TIP: Jalankan 'php impor_ke_database.php' untuk memasukkan hasil ini ke MySQL db_pariwisata_babel.\n")


if __name__ == "__main__":
    main()
