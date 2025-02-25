from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from webdriver_manager.chrome import ChromeDriverManager
import time

# Setup WebDriver
options = webdriver.ChromeOptions()
options.add_argument("--user-data-dir=./user_data")  # Simpan sesi login
options.add_argument("--headless")  # (Opsional) Jalankan tanpa GUI

service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=options)

# Buka WhatsApp Web
driver.get("https://web.whatsapp.com")
input("Scan QR Code, lalu tekan Enter...")  # Tunggu user scan QR Code

# Tunggu agar halaman sepenuhnya dimuat
time.sleep(5)

# Cari semua chat yang ada
chat_list = driver.find_elements(By.CLASS_NAME, "_21S-L")

# Loop semua chat & ambil nama + pesan terakhir
for chat in chat_list:
    try:
        name = chat.find_element(By.CLASS_NAME, "_21S-L").text  # Nama kontak
        last_message = chat.find_element(By.CLASS_NAME, "_1Gy50").text  # Pesan terakhir
        print(f"{name}: {last_message}")
    except:
        continue

# Tutup browser (opsional)
# driver.quit()
