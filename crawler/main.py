"""
Program Utama Crawler Pariwisata Bangka Belitung
Menjalankan pencarian terstruktur untuk seluruh atau sebagian wilayah Bangka Belitung,
melakukan deduplikasi data, dan menyimpan hasil secara otomatis.
"""

import os
import sys
import time
import json
import pandas as pd

if sys.platform == "win32":
    try:
        sys.stdout.reconfigure(encoding="utf-8")
        sys.stderr.reconfigure(encoding="utf-8")
    except Exception:
        pass

from config import LOCATIONS, SEARCH_QUERIES, BROWSER_SETTINGS, OUTPUT_CSV, OUTPUT_JSON, OUTPUT_DIR
from engine import GoogleMapsCrawlerEngine


def load_existing_data() -> tuple[list[dict], set]:
    """Membaca data yang sudah tersimpan sebelumnya agar bisa melanjutkan (resume) tanpa duplikasi."""
    data = []
    seen_keys = set()

    if os.path.exists(OUTPUT_CSV):
        try:
            df = pd.read_csv(OUTPUT_CSV)
            data = df.to_dict(orient="records")
            for item in data:
                key = (
                    str(item.get("nama", "")).strip().lower(),
                    round(float(item.get("latitude", 0) or 0), 3),
                    round(float(item.get("longitude", 0) or 0), 3)
                )
                seen_keys.add(key)
            print(f"[INFO] Ditemukan data tersimpan sebelumnya: {len(data)} entri unik.")
        except Exception as e:
            print(f"[WARN] Tidak dapat membaca file lama: {e}")

    return data, seen_keys


def save_data(data: list[dict]):
    """Menyimpan data ke format CSV dan JSON secara aman."""
    if not data:
        return

    os.makedirs(OUTPUT_DIR, exist_ok=True)
    df = pd.DataFrame(data)

    # 1. Simpan CSV (UTF-8 with BOM agar aman dibuka di Excel)
    df.to_csv(OUTPUT_CSV, index=False, encoding="utf-8-sig")

    # 2. Simpan JSON untuk kemudahan pembacaan oleh aplikasi web
    with open(OUTPUT_JSON, "w", encoding="utf-8") as f:
        json.dump(data, f, ensure_ascii=False, indent=2)


def print_summary(data: list[dict]):
    """Mencetak rekapitulasi data berdasarkan kategori 3A."""
    if not data:
        print("[INFO] Belum ada data terkumpul.")
        return

    df = pd.DataFrame(data)
    print("\n" + "=" * 60)
    print("REKAPITULASI HASIL DATA CRAWLER PARIWISATA")
    print("=" * 60)
    print(f"Total Entri Terkumpul : {len(df)}")
    print("\nBerdasarkan Kelompok:")
    if "kelompok" in df.columns:
        print(df["kelompok"].value_counts().to_string())
    print("\nBerdasarkan Sub Kategori:")
    if "sub_kategori" in df.columns:
        print(df["sub_kategori"].value_counts().to_string())
    print("=" * 60)
    print(f"File CSV  : {OUTPUT_CSV}")
    print(f"File JSON : {OUTPUT_JSON}")
    print("=" * 60 + "\n")


def main():
    print("\n" + "=" * 60)
    print("CRAWLER DATA PARIWISATA PROV. BANGKA BELITUNG")
    print("=" * 60)

    # Pilihan Menu Wilayah
    kabupaten_list = list(LOCATIONS.keys())
    print("Pilih Cakupan Wilayah yang Ingin Di-crawl:")
    print("0. Seluruh Provinsi Bangka Belitung (7 Kabupaten/Kota)")
    for idx, kab in enumerate(kabupaten_list, start=1):
        print(f"{idx}. {kab} ({len(LOCATIONS[kab])} Kecamatan)")
    print("q. Keluar")

    pilihan = input("\nMasukkan nomor pilihan [0-7 / q]: ").strip()
    if pilihan.lower() == "q":
        print("Dibatalkan.")
        return

    target_locations = {}
    if pilihan == "0":
        target_locations = LOCATIONS
    elif pilihan.isdigit() and 1 <= int(pilihan) <= len(kabupaten_list):
        kab_terpilih = kabupaten_list[int(pilihan) - 1]
        target_locations = {kab_terpilih: LOCATIONS[kab_terpilih]}
    else:
        print("Pilihan tidak valid.")
        return

    # Inisialisasi Data & Engine
    all_data, seen_keys = load_existing_data()

    print("\n[INFO] Memulai Browser Chrome...")
    crawler = GoogleMapsCrawlerEngine(
        headless=BROWSER_SETTINGS.get("headless", False),
        timeout=BROWSER_SETTINGS.get("timeout", 15)
    )

    try:
        crawler.open()

        for kab_name, kec_list in target_locations.items():
            print(f"\n============================================================")
            print(f"KABUPATEN/KOTA: {kab_name.upper()}")
            print(f"============================================================")

            for kec_name in kec_list:
                print(f"\n[KECAMATAN] {kec_name}")

                for query_prefix in SEARCH_QUERIES:
                    full_query = f"{query_prefix} {kec_name} {kab_name}"
                    print(f"   [SEARCH] '{full_query}'...")

                    crawler.search(full_query)
                    crawler.scroll_results(
                        max_scroll=BROWSER_SETTINGS.get("max_scroll", 30),
                        pause_time=BROWSER_SETTINGS.get("scroll_pause", 1.2)
                    )

                    new_places = crawler.extract_places(kab_name, kec_name, full_query)
                    added_count = 0

                    for p in new_places:
                        key = (
                            str(p["nama"]).strip().lower(),
                            round(float(p["latitude"] or 0), 3),
                            round(float(p["longitude"] or 0), 3)
                        )
                        if key not in seen_keys:
                            seen_keys.add(key)
                            all_data.append(p)
                            added_count += 1

                    print(f"      -> Ditemukan {len(new_places)} tempat ({added_count} baru, total: {len(all_data)})")

                # Autosave setiap selesai 1 kecamatan
                save_data(all_data)
                print(f"[AUTOSAVE] Progres tersimpan ({len(all_data)} tempat).")

    except KeyboardInterrupt:
        print("\n[WARN] Proses dihentikan oleh pengguna. Menyimpan data terakhir...")
    except Exception as e:
        print(f"\n[ERROR] Terjadi kesalahan: {e}")
    finally:
        print("\n[INFO] Menutup browser dan memfinalisasi data...")
        crawler.close()
        save_data(all_data)
        print_summary(all_data)


if __name__ == "__main__":
    main()
