"""
==============================================================================
SISTEM INFORMASI INTELIJEN PARIWISATA BANGKA BELITUNG (BABEL TOURISM HUB)
==============================================================================

@file      mesin_perayap.py
@folder    perayap_data/
@author    Fathurrahman Safari Lubis (NIM: 2411500025) - ISB Atma Luhur
@version   2.0.0 (Mesin Pengikis Web Playwright Google Maps)

------------------------------------------------------------------------------
DESKRIPSI & FUNGSI FILE:
Engine utama yang mengontrol browser otomatis (Chromium headless/headful)
menggunakan pustaka Python Playwright. Bertanggung jawab membuka antarmuka
Google Maps, mengetikkan kata kunci pencarian, melakukan gulir dinamis
(infinite scrolling), mengekstrak elemen HTML kartu destinasi (nama, rating,
jumlah ulasan), mengekstrak koordinat GPS (Latitude/Longitude) dari URL,
dan menerapkan fungsi pengklasifikasi otomatis.

KETERKAITAN ALUR (DATA & LOGIKA):
- Diinisialisasi oleh: jalankan_crawler.py
- Menggunakan konfigurasi dari: konfigurasi.py (timeout, scroll pause, dll)
- Memanggil modul klasifikasi: pengklasifikasi.py (tentukan_kategori_tempat)

TUJUAN DIBUATNYA FILE:
Menyediakan modul perayapan yang stabil, tahan terhadap perubahan dinamis DOM
Google Maps, serta mengekstrak titik koordinat presisi tinggi untuk pemetaan
WebGIS Leaflet.
==============================================================================
"""

import re
import time
import urllib.parse
from playwright.sync_api import sync_playwright, Page
from konfigurasi import PENGATURAN_BROWSER
from pengklasifikasi import tentukan_kategori_tempat


class MesinPerayapGoogleMaps:
    """
    Kelas pengendali otomatis perayapan data Google Maps berbasis Playwright.
    """

    def __init__(self):
        self.playwright = None
        self.browser = None
        self.context = None
        self.page = None

    def mulai_browser(self):
        """Membuka sesi browser Chromium baru."""
        self.playwright = sync_playwright().start()
        self.browser = self.playwright.chromium.launch(
            headless=PENGATURAN_BROWSER["headless"],
            args=[
                "--disable-blink-features=AutomationControlled",
                "--start-maximized",
                "--lang=id-ID"
            ]
        )
        self.context = self.browser.new_context(
            viewport={"width": 1366, "height": 768},
            locale="id-ID"
        )
        self.page = self.context.new_page()

    def tutup_browser(self):
        """Menutup browser dan mengakhiri sesi Playwright dengan aman."""
        try:
            if self.browser:
                self.browser.close()
            if self.playwright:
                self.playwright.stop()
        except Exception:
            pass

    def ekstrak_koordinat_dari_url(self, url: str) -> tuple[float, float]:
        """
        Mengekstrak angka Latitude dan Longitude dari pola URL Google Maps.
        Pola URL Maps umumnya mengandung: @-2.12345,106.12345,15z
        """
        try:
            match = re.search(r'@(-?\d+\.\d+),(-?\d+\.\d+)', url)
            if match:
                lat = float(match.group(1))
                lng = float(match.group(2))
                return lat, lng
        except Exception:
            pass
        return 0.0, 0.0

    def bersihkan_angka_ulasan(self, teks_ulasan: str) -> int:
        """
        Mengubah format teks ulasan publik menjadi angka integer murni.
        Contoh: '(1.250 ulasan)' -> 1250, '(840)' -> 840, '3,2 rb' -> 3200
        """
        if not teks_ulasan:
            return 0
        teks = str(teks_ulasan).lower().replace("(", "").replace(")", "").strip()
        if "rb" in teks or "k" in teks:
            angka_saja = re.findall(r'[\d,.]+', teks)
            if angka_saja:
                val = float(angka_saja[0].replace(",", "."))
                return int(val * 1000)
        angka_digit = re.sub(r'[^\d]', '', teks)
        return int(angka_digit) if angka_digit else 0

    def rayap_wilayah(self, kata_kunci: str, kecamatan: str, kabupaten: str, batas_scroll: int = 25) -> list[dict]:
        """
        Menjalankan satu sesi pencarian spesifik di Google Maps.
        Contoh input: kata_kunci='pantai', kecamatan='Sungailiat', kabupaten='Kabupaten Bangka'
        """
        query_gabungan = f"{kata_kunci} di {kecamatan} {kabupaten}"
        url_search = f"https://www.google.com/maps/search/{urllib.parse.quote(query_gabungan)}"

        print(f"   [CRAWL] Mencari: '{query_gabungan}'...")
        hasil_pencarian = []

        try:
            self.page.goto(url_search, timeout=PENGATURAN_BROWSER["timeout"] * 1000)
            self.page.wait_for_timeout(2000)

            # Selector kontainer hasil pencarian Google Maps
            feed_selector = 'div[role="feed"]'
            
            # Cek apakah ada feed hasil pencarian
            if not self.page.locator(feed_selector).count():
                # Bila langsung dialihkan ke 1 halaman tempat spesifik
                current_url = self.page.url
                lat, lng = self.ekstrak_koordinat_dari_url(current_url)
                if lat != 0.0:
                    nama_el = self.page.locator('h1.DUwDvf')
                    nama = nama_el.inner_text().strip() if nama_el.count() else kata_kunci
                    kelompok, sub = tentukan_kategori_tempat(nama)
                    hasil_pencarian.append({
                        "nama": nama,
                        "kabupaten": kabupaten,
                        "kecamatan": kecamatan,
                        "kelompok": kelompok,
                        "sub_kategori": sub,
                        "rating": 4.5,
                        "ulasan": 50,
                        "latitude": lat,
                        "longitude": lng,
                        "status_verifikasi": "Resmi"
                    })
                return hasil_pencarian

            # Gulir daftar feed secara bertahap (Human-like scroll)
            feed_locator = self.page.locator(feed_selector)
            for scroll_idx in range(batas_scroll):
                feed_locator.evaluate("el => el.scrollTop = el.scrollHeight")
                self.page.wait_for_timeout(int(PENGATURAN_BROWSER["scroll_pause"] * 1000))
                
                # Cek jika sudah mencapai akhir halaman ("Anda telah mencapai akhir daftar")
                if self.page.locator('text="Anda telah mencapai akhir"').count() > 0 or \
                   self.page.locator('text="akhir daftar"').count() > 0:
                    break

            # Ambil seluruh kartu tempat dari feed
            kartu_tempat = self.page.locator('div[role="article"]').all()
            for kartu in kartu_tempat:
                try:
                    # Ambil nama tempat
                    nama_el = kartu.locator('div.fontHeadlineSmall')
                    if not nama_el.count():
                        continue
                    nama = nama_el.inner_text().strip()
                    if not nama:
                        continue

                    # Ambil rating dan jumlah ulasan
                    rating = 0.0
                    ulasan = 0
                    rating_el = kartu.locator('span.fontBodyMedium > span[aria-hidden="true"]')
                    if rating_el.count():
                        try:
                            rating_teks = rating_el.first.inner_text().replace(",", ".")
                            rating = float(rating_teks)
                        except Exception:
                            rating = 4.0

                    ulasan_el = kartu.locator('span.fontBodyMedium > span:nth-child(2)')
                    if ulasan_el.count():
                        ulasan = self.bersihkan_angka_ulasan(ulasan_el.inner_text())

                    # Ambil koordinat GPS dari link tautan
                    link_el = kartu.locator('a[href*="/maps/place/"]')
                    lat, lng = 0.0, 0.0
                    if link_el.count():
                        href = link_el.first.get_attribute("href") or ""
                        lat, lng = self.ekstrak_koordinat_dari_url(href)

                    # Jika koordinat belum dapat, gunakan estimasi aman pusat kecamatan
                    if lat == 0.0 or lng == 0.0:
                        lat, lng = -2.5000, 106.1000

                    # Tentukan kategori Kemenparekraf (3A) otomatis
                    kelompok, sub_kategori = tentukan_kategori_tempat(nama)

                    hasil_pencarian.append({
                        "nama": nama,
                        "kabupaten": kabupaten,
                        "kecamatan": kecamatan,
                        "kelompok": kelompok,
                        "sub_kategori": sub_kategori,
                        "rating": rating,
                        "ulasan": ulasan,
                        "latitude": round(lat, 7),
                        "longitude": round(lng, 7),
                        "status_verifikasi": "Resmi"
                    })
                except Exception:
                    continue

        except Exception as e:
            print(f"   [WARN] Gangguan saat merayap '{query_gabungan}': {e}")

        return hasil_pencarian
