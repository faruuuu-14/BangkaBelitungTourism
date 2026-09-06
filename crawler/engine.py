"""
Mesin Crawler Google Maps Menggunakan Selenium
Menavigasi, mencari, menggulir (scroll) feed kartu, dan mengekstrak data tempat serta koordinat geografis.
"""

import re
import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

from classifier import classify_place


class GoogleMapsCrawlerEngine:
    MAPS_URL = "https://www.google.com/maps?hl=id"

    def __init__(self, headless=False, timeout=15):
        self.timeout = timeout
        self.headless = headless
        self.driver = self._init_driver()
        self.wait = WebDriverWait(self.driver, self.timeout)

    def _init_driver(self):
        options = Options()
        if self.headless:
            options.add_argument("--headless=new")
        options.add_argument("--start-maximized")
        options.add_argument("--disable-notifications")
        options.add_argument("--disable-gpu")
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        options.add_argument("--lang=id")
        
        # Mencegah deteksi otomatisasi dasar
        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option("useAutomationExtension", False)

        service = Service(ChromeDriverManager().install())
        driver = webdriver.Chrome(service=service, options=options)
        driver.implicitly_wait(5)
        return driver

    def open(self):
        """Membuka Google Maps versi Bahasa Indonesia."""
        self.driver.get(self.MAPS_URL)
        time.sleep(2)
        # Tunggu kotak pencarian muncul
        self.wait.until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "input#searchboxinput, input[role='combobox']"))
        )
        print("[INFO] Google Maps berhasil dimuat.")

    def search(self, query: str):
        """Melakukan pencarian kata kunci di kotak pencarian Google Maps."""
        try:
            search_box = self.wait.until(
                EC.element_to_be_clickable((By.CSS_SELECTOR, "input#searchboxinput, input[role='combobox']"))
            )
            search_box.click()
            search_box.send_keys(Keys.CONTROL, "a")
            search_box.send_keys(Keys.DELETE)
            time.sleep(0.3)
            search_box.send_keys(query)
            time.sleep(0.3)
            search_box.send_keys(Keys.ENTER)
            time.sleep(2.5)
        except Exception as e:
            print(f"[WARN] Gagal memasukkan kata kunci '{query}': {e}")

    def scroll_results(self, max_scroll=30, pause_time=1.2):
        """Menggulir daftar hasil (feed) di sidebar kiri hingga bawah atau batas maksimal."""
        try:
            # Mencari container feed hasil pencarian
            feed_elem = None
            selectors = [
                "div[role='feed']",
                "div.m6QErb.DxyBCb.kA9KIf.dS8AEf",
                "div.m6QErb[aria-label]"
            ]
            for sel in selectors:
                elems = self.driver.find_elements(By.CSS_SELECTOR, sel)
                if elems:
                    feed_elem = elems[0]
                    break

            if not feed_elem:
                return

            last_card_count = 0
            unchanged_count = 0

            for i in range(max_scroll):
                # Gulir ke bawah
                self.driver.execute_script("arguments[0].scrollTop = arguments[0].scrollHeight", feed_elem)
                time.sleep(pause_time)

                # Hitung kartu saat ini
                current_cards = self.driver.find_elements(By.CSS_SELECTOR, "div.Nv2PK")
                count = len(current_cards)

                if count == last_card_count:
                    unchanged_count += 1
                    if unchanged_count >= 3:
                        # Sudah mencapai akhir hasil pencarian
                        break
                else:
                    unchanged_count = 0
                    last_card_count = count

                # Cek tanda akhir feed dari Google Maps
                end_text = self.driver.find_elements(By.CSS_SELECTOR, "span.HlvSq, div.HlvSq")
                if end_text and any("akhir" in e.text.lower() or "end" in e.text.lower() for e in end_text):
                    break

        except Exception as e:
            print(f"[DEBUG] Scroll feed terhenti: {e}")

    def extract_places(self, kabupaten: str, kecamatan: str, search_query: str) -> list[dict]:
        """Mengekstrak seluruh kartu tempat wisata/amenitas yang tampil."""
        places = []
        cards = self.driver.find_elements(By.CSS_SELECTOR, "div.Nv2PK")

        if not cards:
            # Jika Google Maps langsung membuka 1 tempat tunggal (detail view)
            single = self._extract_single_detail(kabupaten, kecamatan, search_query)
            if single:
                places.append(single)
            return places

        for card in cards:
            try:
                # 1. Nama Tempat
                nama_elem = card.find_elements(By.CSS_SELECTOR, "div.qBF1Pd")
                if not nama_elem:
                    continue
                nama = nama_elem[0].text.strip()
                if not nama:
                    continue

                # 2. Rating Bintang
                rating = None
                rating_elems = card.find_elements(By.CSS_SELECTOR, "span.MW4etd")
                if rating_elems:
                    try:
                        rating = float(rating_elems[0].text.strip().replace(",", "."))
                    except:
                        rating = None

                # 3. Jumlah Ulasan
                ulasan = 0
                ulasan_elems = card.find_elements(By.CSS_SELECTOR, "span.UY7F9")
                if ulasan_elems:
                    txt = ulasan_elems[0].text.replace(".", "").replace(",", "")
                    match = re.search(r'\d+', txt)
                    if match:
                        ulasan = int(match.group(0))

                # 4. Kategori Resmi Google Maps
                kategori_google = ""
                detail_elems = card.find_elements(By.CSS_SELECTOR, "div.W4Efsd")
                if detail_elems:
                    # Ambil teks kategori dari baris pertama detail kartu
                    spans = detail_elems[0].find_elements(By.CSS_SELECTOR, "span")
                    for s in spans:
                        t = s.text.strip()
                        # Jangan ambil teks rating atau harga/jam buka
                        if t and not re.search(r'^\d+(\.\d+)?$', t) and not "buka" in t.lower() and not "tutup" in t.lower():
                            kategori_google = t
                            break

                # 5. Koordinat Geografis (Latitude & Longitude)
                lat, lng = None, None
                link_elems = card.find_elements(By.CSS_SELECTOR, "a")
                for link in link_elems:
                    href = link.get_attribute("href") or ""
                    # 1. Cek pola !3d(-?lat)!4d(-?lng)
                    match_3d = re.search(r'!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)', href)
                    if match_3d:
                        lat = float(match_3d.group(1))
                        lng = float(match_3d.group(2))
                        break
                    # 2. Cek pola @(-?lat),(-?lng)
                    match_at = re.search(r'@(-?\d+\.\d+),(-?\d+\.\d+)', href)
                    if match_at:
                        lat = float(match_at.group(1))
                        lng = float(match_at.group(2))
                        break

                # 6. Klasifikasi Konsep 3A
                kelompok, sub_kategori = classify_place(nama, kategori_google)

                places.append({
                    "kabupaten": kabupaten,
                    "kecamatan": kecamatan,
                    "nama": nama,
                    "kategori_google": kategori_google or "Umum",
                    "kelompok": kelompok,
                    "sub_kategori": sub_kategori,
                    "rating": rating,
                    "ulasan": ulasan,
                    "latitude": lat,
                    "longitude": lng,
                    "query_pencarian": search_query
                })
            except Exception as e:
                continue

        return places

    def _extract_single_detail(self, kabupaten: str, kecamatan: str, search_query: str) -> dict | None:
        """Ekstraksi jika Google Maps langsung menampilkan halaman detail 1 tempat."""
        try:
            nama_elems = self.driver.find_elements(By.CSS_SELECTOR, "h1.DUwifb, h1.fontTitleLarge")
            if not nama_elems:
                return None
            nama = nama_elems[0].text.strip()
            if not nama:
                return None

            rating = None
            rating_elems = self.driver.find_elements(By.CSS_SELECTOR, "span.ce3oMc, span.MW4etd")
            if rating_elems:
                try:
                    rating = float(rating_elems[0].text.strip().replace(",", "."))
                except:
                    rating = None

            ulasan = 0
            ulasan_elems = self.driver.find_elements(By.CSS_SELECTOR, "button.HH2P6d, span.F72Y3d")
            if ulasan_elems:
                txt = ulasan_elems[0].text.replace(".", "").replace(",", "")
                m = re.search(r'\d+', txt)
                if m:
                    ulasan = int(m.group(0))

            kategori_google = ""
            cat_elems = self.driver.find_elements(By.CSS_SELECTOR, "button.DkEaL")
            if cat_elems:
                kategori_google = cat_elems[0].text.strip()

            lat, lng = None, None
            url = self.driver.current_url
            m_at = re.search(r'@(-?\d+\.\d+),(-?\d+\.\d+)', url)
            if m_at:
                lat = float(m_at.group(1))
                lng = float(m_at.group(2))

            kelompok, sub_kategori = classify_place(nama, kategori_google)

            return {
                "kabupaten": kabupaten,
                "kecamatan": kecamatan,
                "nama": nama,
                "kategori_google": kategori_google or "Umum",
                "kelompok": kelompok,
                "sub_kategori": sub_kategori,
                "rating": rating,
                "ulasan": ulasan,
                "latitude": lat,
                "longitude": lng,
                "query_pencarian": search_query
            }
        except:
            return None

    def close(self):
        """Menutup browser."""
        try:
            self.driver.quit()
        except:
            pass
