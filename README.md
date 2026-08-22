# 🌿 Antree - Sistem Manajemen Antrean Profesional & Modern

Antree adalah aplikasi manajemen antrean (*Queue Management System*) komersial berbasis web yang dirancang secara modern, responsif, dan berkinerja tinggi. Menggunakan **Laravel 11**, **Tailwind CSS**, **Vite**, serta **Laravel Reverb** (Websocket) untuk menghadirkan sinkronisasi panggilan antrean secara *real-time* tanpa perlu memuat ulang halaman (*zero-refresh latency*).

Aplikasi ini telah diperbarui secara menyeluruh menggunakan prinsip estetika premium, tipografi natural (*Sentence Case*), dan skema warna solid dengan kontras tinggi untuk kenyamanan operasional tingkat tinggi.

---

## ✨ Fitur Unggulan

1. **Dashboard Admin Modern**: Tampilan dashboard premium yang dirancang ulang dengan visual statistik solid (Teal, Blue, Emerald, Orange) bebas dari warna gradasi mencolok dan emotikon berlebih.
2. **Manajemen Pengaturan Umum (General Settings)**: Atur nama aplikasi, nama perusahaan, logo instansi, alamat, dan deskripsi footer langsung dari satu pintu secara dinamis.
3. **Pengaturan Tampilan (Display Settings)**: Unggah logo kustom instansi Anda dengan *path renderer* otomatis yang mendukung pratinjau langsung, serta pilih suara panggilan loket kustom.
4. **Laporan & Analytics Kelas Bisnis**:
   * Filter antrean horisontal super ringkas tanpa label kaku.
   * Kalender premium menggunakan **Flatpickr** yang intuitif dan elegan.
   * Ringkasan data dalam bentuk kartu metrik berwarna penuh.
   * Ekspor data laporan antrean ke format `.csv` secara instan.
5. **Layar Kios Antrean (Kiosk Mode)**: Halaman pengambilan nomor tiket antrean mandiri untuk pengunjung yang responsif dan ramah sentuhan.
6. **TV Display Utama (Public TV Screen)**: Layar utama ruang tunggu publik *real-time* yang dilengkapi dengan suara panggilan suara manusia otomatis (*voice synthesizer*) dan pemutar video informasi.
7. **Portal Operator Loket**: Antarmuka panggil antrean untuk operator loket yang terintegrasi (Tombol Panggil, Panggil Ulang, Mulai Melayani, Selesai, dan Lewati).

---

## 💻 Kebutuhan Sistem (Prerequisites)

Sebelum menginstal, pastikan server atau komputer lokal Anda telah memenuhi persyaratan berikut:
* **PHP** >= 8.2 (dengan ekstensi `pdo`, `mbstring`, `openssl`, `xml`, `zip`)
* **Composer** (Dependency manager PHP)
* **Node.js** >= 18.x & **NPM** >= 9.x
* **Database Server**: MySQL (>= 8.0) atau SQLite
* **Web Browser Modern**: Google Chrome, Microsoft Edge, Safari, atau Firefox

---

## 🛠️ Panduan Instalasi Langkah-Demi-Langkah

Ikuti langkah-langkah di bawah ini untuk memasang dan menjalankan aplikasi Antree di lingkungan lokal Anda:

### 1. Gandakan Repositori & Masuk ke Folder Projek
Jika Anda mengunduh source code atau menggunakan git, buka terminal dan masuk ke direktori utama projek:
```bash
cd "/Users/mac/Adam Adifa/Project/antree"
```

### 2. Pasang Dependensi PHP (Composer)
Unduh seluruh pustaka backend yang diperlukan oleh Laravel:
```bash
composer install
```

### 3. Pasang Dependensi Frontend & Aset (NPM)
Unduh seluruh modul Javascript dan Tailwind CSS untuk kompilasi aset:
```bash
npm install
```

### 4. Konfigurasi File Lingkungan (`.env`)
Salin file template konfigurasi bawaan menjadi file `.env` aktif:
```bash
cp .env.example .env
```
Buka file `.env` yang baru dibuat dan sesuaikan konfigurasi database Anda. Contoh untuk MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=antree
DB_USERNAME=root
DB_PASSWORD=
```
Pastikan pengaturan **Laravel Reverb (Websocket)** sudah sesuai untuk fitur real-time:
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST="127.0.0.1"
REVERB_PORT=8080
REVERB_SCHEME=http
```

### 5. Buat Kunci Aplikasi (Application Key)
Generate kunci enkripsi unik untuk keamanan sesi aplikasi Anda:
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Seeder Database
Buat seluruh struktur tabel database beserta data awal (admin default, divisi layanan bawaan, dan konfigurasi instansi awal):
```bash
php artisan migrate --seed
```

### 7. Buat Simbolik Link Folder Penyimpanan
Hubungkan folder penyimpanan file lokal agar logo yang Anda unggah di halaman *General/Display Settings* dapat diakses langsung oleh publik:
```bash
php artisan storage:link
```

### 8. Kompilasi Aset Frontend (Tailwind & Vite)
Kompilasi seluruh file aset Javascript, Tailwind CSS, dan Flatpickr ke versi produksi:
```bash
npm run build
```

---

## 🚀 Cara Menjalankan Aplikasi

Aplikasi Antree membutuhkan server web dan websocket agar dapat berfungsi penuh secara *real-time*. Jalankan 3 terminal terpisah berikut ini:

### 📺 Terminal 1: Server Web Utama (Laravel Development Server)
Menjalankan mesin PHP utama untuk memproses request halaman web:
```bash
php artisan serve
```
*Aplikasi sekarang dapat diakses secara lokal di:* `http://127.0.0.1:8000`

### ⚡ Terminal 2: Server Websocket Reverb (Real-time Sync)
Menjalankan server Reverb untuk mendengarkan dan mengirimkan event panggilan secara instan ke layar TV display tanpa membebani database:
```bash
php artisan reverb:start
```

### ⚙️ Terminal 3: Compiler Aset Real-time (Optional - Dev Mode)
Jika Anda sedang melakukan modifikasi kode atau penyesuaian gaya tampilan, jalankan dev server Vite:
```bash
npm run dev
```

---

## 🧭 Panduan Cara Penggunaan Aplikasi

### 👨‍💼 1. Masuk Sebagai Administrator (Admin Portal)
* **URL Akses**: `http://127.0.0.1:8000/login`
* **Akun Bawaan (Default)**:
  * *Email*: `admin@antree.local` (atau email yang disesuaikan saat seeding)
  * *Password*: `password`
* **Fungsi Utama**:
  * **Dashboard**: Pantau jumlah antrean berjalan hari ini, performa layanan, status loket operasional aktif, serta grafik kunjungan harian.
  * **Service Types**: Kelola divisi layanan (contoh: *Pendaftaran*, *Customer Service*, *Pembayaran*), batas kuota antrean harian, dan kode prefix tiket (A, B, C, dll.).
  * **Counters**: Kelola nomor loket fisik yang tersedia dan tugaskan operator ke masing-masing loket tersebut.
  * **Users**: Buat dan sunting akun operator loket beserta peran hak aksesnya.
  * **Display Settings**: Ganti logo utama display, atur visual tata letak, dan pilih rekaman suara pemanggil.
  * **General Settings**: Sesuaikan nama perusahaan, alamat instansi untuk dicetak di tiket fisik, serta penulisan footer.
  * **Laporan**: Filter riwayat transaksi antrean per periode secara instan menggunakan kalender Flatpickr yang menawan, saring berdasarkan jenis layanan atau status tiket, dan unduh sebagai file `.csv` untuk laporan berkala.

### 🎫 2. Layar Ambil Tiket Mandiri (Kiosk Mode)
* **URL Akses**: `http://127.0.0.1:8000/kiosk` (atau klik menu Kiosk di admin panel)
* **Cara Kerja**:
  * Pengunjung datang dan memilih jenis layanan yang ingin dituju pada layar sentuh.
  * Aplikasi akan mengirimkan perintah cetak tiket (atau menampilkan nomor tiket) beserta instruksi jumlah sisa antrean di depan mereka.

### 📺 3. Layar Antrean Utama Ruang Tunggu (TV Display Screen)
* **URL Akses**: `http://127.0.0.1:8000/display`
* **Cara Kerja**:
  * Tampilkan halaman ini pada TV LCD/LED besar di ruang tunggu utama menggunakan mode *full screen* (tekan tombol `F11` pada browser).
  * Layar akan memuat video promosi, nama instansi beserta logo yang diatur di *Settings*, serta panel besar berisi informasi antrean yang sedang aktif dilayani di setiap loket.
  * Ketika operator melakukan panggilan dari komputer loket, suara pengumuman otomatis (*"Nomor Antrean A-005, Silakan Menuju ke Loket 1"*) akan berbunyi di layar TV ini tanpa ada jeda pemuatan ulang halaman.

### 🎧 4. Panel Kerja Operator Loket (Operator Portal)
* **URL Akses**: `http://127.0.0.1:8000/counter`
* **Cara Kerja**:
  * Operator masuk dengan akun loket masing-masing dan memilih nomor loket kerja aktif mereka.
  * Klik tombol **"Panggil Antrean"** (*Call*) untuk memanggil nomor antrean berikutnya di antrean tunggu.
  * Klik **"Mulai Melayani"** (*Serve*) saat pengunjung sudah tiba di loket.
  * Klik **"Selesai"** (*Complete*) setelah transaksi selesai untuk menyimpan durasi pelayanan.
  * Klik **"Panggil Ulang"** (*Recall*) jika pengunjung tidak merespon, atau **"Lewati"** (*Skip*) untuk melanjutkan ke antrean berikutnya.

---

## 🛠️ Penanganan Masalah (Troubleshooting)

1. **Logo yang Baru Diunggah Tidak Muncul**:
   * Pastikan Anda telah menjalankan perintah `php artisan storage:link`.
   * Periksa hak akses tulis (*write permissions*) direktori `storage/app/public` di sistem operasi Anda.
2. **Suara Panggilan Tidak Terdengar di TV Display**:
   * Sebagian besar browser modern memblokir suara otomatis (*autoplay*). Klik di area mana saja pada layar TV display sekali untuk mengaktifkan izin suara (*user gesture activation*).
3. **Panggilan di Operator Tidak Mengubah Layar TV Utama**:
   * Pastikan server websocket Reverb telah berjalan (`php artisan reverb:start`).
   * Periksa di konsol browser (tekan `F12` > tab *Console*) apakah ada kesalahan koneksi Pusher/Reverb. Pastikan port `8080` tidak diblokir oleh firewall server Anda.

---
🌿 **Antree** - *Sederhana, Cepat, dan Profesional.* Dibuat dengan dedikasi penuh untuk performa pelayanan publik terbaik.
