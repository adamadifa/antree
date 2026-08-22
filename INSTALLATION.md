# ⚙️ System Requirements & Installation Guide - Antree

This document provides the system prerequisites and step-by-step installation instructions to set up the **Antree** Queue Management System in your local or production environment.

---

## 💻 Kebutuhan Sistem (System Requirements)

Before proceeding with the installation, ensure your server or local machine meets the following requirements:

### 1. Backend (PHP Environment)
* **PHP**: `>= 8.2`
* **PHP Extensions Required**:
  * `pdo` (MySQL or SQLite support)
  * `mbstring`
  * `openssl`
  * `xml`
  * `zip`
  * `curl`
  * `gd` (for logo image resizing/processing)
* **Composer**: `>= 2.x` (PHP Dependency Manager)

### 2. Frontend & Compilation
* **Node.js**: `>= 18.x`
* **NPM**: `>= 9.x`

### 3. Database Server
* **MySQL**: `>= 8.0` (Recommended) OR **MariaDB**
* **SQLite**: (Supported for testing/development environments)

### 4. Websocket Server (Real-time Sync)
* **Laravel Reverb**: (Runs automatically via PHP on port `8080` by default)

### 5. Client Web Browser
* Modern browser with HTML5 and Javascript support:
  * Google Chrome / Chromium
  * Apple Safari
  * Mozilla Firefox
  * Microsoft Edge

---

## 🛠️ Panduan Instalasi Langkah-Demi-Langkah (Step-by-Step Installation)

Follow these instructions to set up the project on your machine:

### Langkah 1: Pindah ke Direktori Projek
Buka Terminal atau Command Prompt, lalu masuk ke folder utama projek Antree:
```bash
cd "/Users/mac/Adam Adifa/Project/antree"
```

### Langkah 2: Install Dependensi PHP
Unduh semua paket backend Laravel yang dibutuhkan:
```bash
composer install
```

### Langkah 3: Install Dependensi Frontend
Pasang modul NodeJS untuk manajemen tampilan UI dan kompilasi CSS/JS:
```bash
npm install
```

### Langkah 4: Konfigurasi File Environment (`.env`)
Salin file template `.env.example` menjadi file `.env` baru:
```bash
cp .env.example .env
```

Buka file `.env` tersebut menggunakan editor teks (VS Code, Notepad, dll) dan sesuaikan konfigurasi database Anda. Contoh konfigurasi database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=antree
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan pengaturan koneksi Reverb (Websocket) di `.env` sudah terisi dengan benar untuk mendukung fitur sinkronisasi suara dan layar TV secara real-time:
```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=123456
REVERB_APP_KEY=antree-key-xyz
REVERB_APP_SECRET=antree-secret-xyz
REVERB_HOST="127.0.0.1"
REVERB_PORT=8080
REVERB_SCHEME=http
```

### Langkah 5: Buat Application Key
Generate kunci enkripsi aplikasi untuk menjaga keamanan session:
```bash
php artisan key:generate
```

### Langkah 6: Jalankan Migrasi Database dan Seeders
Buat tabel database dan masukkan data default awal (seperti akun administrator default, tipe layanan, dan pengaturan instansi):
```bash
php artisan migrate --seed
```
* **Akun Login Admin Bawaan**:
  * **Email**: `admin@antree.local`
  * **Password**: `password`

### Langkah 7: Buat Simbolik Link Storage
Hubungkan folder media agar logo perusahaan/instansi yang Anda upload di panel admin dapat diakses publik:
```bash
php artisan storage:link
```

### Langkah 8: Kompilasi Aset Frontend (Produksi)
Buat versi produksi dari file CSS dan Javascript agar performa aplikasi cepat:
```bash
npm run build
```

---

## 🚀 Cara Menjalankan Aplikasi secara Lokal

Untuk menjalankan aplikasi secara penuh dengan fitur panggilan suara real-time, Anda perlu membuka **3 tab/jendela terminal terpisah**:

### 1. Terminal 1: Server Utama Laravel (Web Server)
```bash
php artisan serve
```
*Aplikasi web Anda kini dapat diakses di browser melalui alamat:* **`http://127.0.0.1:8000`**

### 2. Terminal 2: Server Websocket Reverb (Real-time Voice & TV Call)
```bash
php artisan reverb:start
```
*Perintah ini memastikan panggilan loket terkirim secara instan ke layar utama TV tanpa jeda.*

### 3. Terminal 3: Compiler Development (Opsional - Untuk Kebutuhan Modifikasi Tampilan)
```bash
npm run dev
```
