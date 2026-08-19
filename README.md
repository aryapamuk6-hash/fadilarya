# 🌱 Lumira
### Marketplace Sampah Daur Ulang Indonesia

Lumira adalah platform marketplace berbasis web yang menghubungkan pembeli dan penjual sampah daur ulang dalam satu ekosistem digital yang aman, mudah, dan terpercaya.

Website ini bertujuan untuk membantu masyarakat mengubah sampah menjadi sumber penghasilan sekaligus mendukung keberlanjutan lingkungan melalui sistem jual beli produk daur ulang secara online.

---

## 🚀 Grand Opening

📅 Periode:
20 Agustus 2026 – 23 Agustus 2026

🌐 Website:
https://lumira.wuaze.com/

🎉 Promo Grand Opening:
- Voucher Diskon
- Promo Seller Baru
- Cashback Saldo Lumira
- Bonus Event Khusus

---

# ✨ Fitur Utama

## 👤 Sistem Pengguna

- Registrasi akun
- Login & Logout
- Edit profil
- Upload foto profil
- Role User
- Role Seller
- Role Admin

---

## 🏪 Sistem Seller

- Pengajuan menjadi seller
- Persetujuan seller oleh admin
- Kelola toko
- Upload produk
- Edit produk
- Hapus produk
- Stok produk
- Status produk aktif / sold out
- Dashboard seller

---

## 🛒 Marketplace

- Daftar produk
- Detail produk
- Kategori produk
- Pencarian produk
- Wishlist
- Keranjang belanja
- Checkout
- Voucher diskon

---

## 💳 Sistem Pembayaran

- Saldo Lumira
- COD (Cash On Delivery)
- Top Up Saldo
- Riwayat Top Up
- Persetujuan Top Up oleh Admin

---

## 📦 Sistem Pesanan

- Buat pesanan
- Riwayat pesanan
- Status pesanan
- Pending
- Paid
- Shipped
- Completed

---

## ⭐ Sistem Review

- Rating toko
- Review produk
- Perhitungan rating seller

---

## 💬 Sistem Chat

- Chat Buyer ↔ Seller
- Riwayat percakapan
- Notifikasi pesan baru

---

## 🔔 Sistem Notifikasi

- Notifikasi pesanan
- Notifikasi seller
- Notifikasi top up
- Notifikasi admin

---

## 🎟 Voucher & Promo

- Voucher persentase
- Voucher nominal tetap
- Expired date
- Aktivasi/nonaktif voucher

---

## 📢 Announcement

- Pengumuman admin
- Event marketplace
- Promo marketplace

---

## 🎨 Banner Management

- Tambah banner
- Hapus banner
- Aktif/nonaktif banner
- Slider dashboard user

---

## 💰 Withdrawal Seller

- Request pencairan saldo
- Persetujuan admin
- Riwayat withdrawal

---

## 🛡 Admin Panel

### Dashboard Admin

- Total user
- Total seller
- Total produk
- Total transaksi
- Total pendapatan platform

### Manajemen Seller

- Approve seller
- Reject seller
- Suspend seller
- Unsuspend seller
- Warning seller

### Manajemen Produk

- Lihat semua produk
- Hide produk
- Show produk
- Hapus produk

### Manajemen Voucher

- Tambah voucher
- Kelola voucher

### Manajemen Banner

- Tambah banner
- Kelola banner

### Manajemen Top Up

- Approve top up
- Reject top up

---

# 🏗 Teknologi Yang Digunakan

## Backend

- Laravel 13
- PHP 8+
- Laravel Breeze

## Frontend

- Vue 3
- Inertia.js
- Tailwind CSS
- Vite

## UI & Animasi

- AOS (Animate On Scroll)
- Vue Animation
- Heroicons

## Database

- MySQL

## Storage

- Laravel Public Storage

## Authentication

- Laravel Authentication
- Middleware Role Based Access

---

# 🗄 Struktur Database Utama

- users
- stores
- products
- categories
- carts
- cart_items
- orders
- order_items
- reviews
- notifications
- conversations
- messages
- seller_applications
- top_ups
- withdrawals
- wishlists
- vouchers
- banners
- announcements

---

# ⚙ Instalasi Project

## Clone Repository

```bash
git clone https://github.com/username/lumira.git
```

## Masuk Folder

```bash
cd lumira
```

## Install Dependency

```bash
composer install
npm install
```

## Copy ENV

```bash
cp .env.example .env
```

## Generate Key

```bash
php artisan key:generate
```

## Setup Database

Buat database MySQL:

```sql
CREATE DATABASE lumira_db;
```

Atur file .env:

```env
DB_DATABASE=lumira_db
DB_USERNAME=root
DB_PASSWORD=
```

## Migrasi Database

```bash
php artisan migrate
```

## Jalankan Seeder

```bash
php artisan db:seed
```

## Storage Link

```bash
php artisan storage:link
```

## Jalankan Server

```bash
php artisan serve
npm run dev
```

---

# 👥 Target Pengguna

### Masyarakat Umum

Menjual sampah yang masih memiliki nilai ekonomi.

### UMKM

Menjual hasil daur ulang menjadi produk bernilai jual.

### Pengrajin

Membeli bahan baku daur ulang untuk produksi.

### Komunitas Lingkungan

Mengelola dan memperluas kegiatan ekonomi sirkular.

### Admin Marketplace

Mengelola seluruh aktivitas platform.

---

# 🎯 Tujuan Lumira

- Mengurangi limbah lingkungan
- Mendukung ekonomi sirkular
- Membantu masyarakat memperoleh penghasilan tambahan
- Mempermudah jual beli produk daur ulang
- Menjadi marketplace daur ulang terbesar di Indonesia

---

## 📜 License

Lumira © 2026

Developed with ❤️ using Laravel, Vue, Inertia, Tailwind CSS, AOS, dan MySQL.
