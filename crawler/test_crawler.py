"""
Script Uji Coba Cepat Crawler (Quick Test)
Menjalankan 1 pencarian cepat di Kota Pangkalpinang (Kecamatan Taman Sari)
untuk memverifikasi bahwa browser, scrolling, dan ekstraksi data bekerja sempurna.
"""

import sys
if sys.platform == "win32":
    try:
        sys.stdout.reconfigure(encoding="utf-8")
        sys.stderr.reconfigure(encoding="utf-8")
    except Exception:
        pass

import pandas as pd
from engine import GoogleMapsCrawlerEngine

def quick_test():
    print("\n[START] Menjalankan Uji Coba Cepat Crawler Google Maps...")
    crawler = GoogleMapsCrawlerEngine(headless=False, timeout=15)

    try:
        crawler.open()
        
        test_query = "tempat wisata Taman Sari Kota Pangkalpinang"
        print(f"[SEARCH] Mencari: '{test_query}'...")
        crawler.search(test_query)

        print("[SCROLL] Menggulir hasil pencarian (3 kali)...")
        crawler.scroll_results(max_scroll=3, pause_time=1.0)

        print("[EXTRACT] Mengekstrak data tempat...")
        places = crawler.extract_places("Kota Pangkalpinang", "Taman Sari", test_query)

        print(f"\n[SUCCESS] Berhasil mendapatkan {len(places)} tempat!\n")
        df = pd.DataFrame(places)
        if not df.empty:
            cols_to_show = ["nama", "kelompok", "sub_kategori", "rating", "ulasan", "latitude", "longitude"]
            print(df[[c for c in cols_to_show if c in df.columns]].to_string(index=False))

    except Exception as e:
        print(f"[ERROR] Terjadi kesalahan: {e}")
    finally:
        print("\n[CLOSE] Menutup browser...")
        crawler.close()

if __name__ == "__main__":
    quick_test()
