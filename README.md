# 🏢 SIMBAK
![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=flat-square&logo=laravel)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-purple?style=flat-square&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Status](https://img.shields.io/badge/Status-Active-success?style=flat-square)

# 🏢 SIMBAK
**Sistem Inventaris & Manajemen Barang Kecamatan**  
Aplikasi berbasis web untuk mengelola inventaris barang di tingkat kecamatan, dibangun menggunakan **Laravel 10** dan **Bootstrap**.  

---

## 🚀 Fitur Utama
- 🔑 **Autentikasi**: Login, Register, dan Reset Password  
- 📊 **Dashboard**: Ringkasan data barang dan pemasukan  
- 📝 **Input Data Pemasukan**:
  - Kode Rekening  
  - Uraian dan Sub Uraian  
  - **Saldo Awal**: Jumlah, Satuan, Harga, Total  
  - **Pembelian**: Jumlah, Satuan, Harga, Total  
  - **Saldo Akhir**: Jumlah, Satuan, Harga, Total  
  - **Persediaan Rusak**: Jumlah, Satuan, Harga, Total  
  - **Beban Persediaan**: `Saldo Awal + Pembelian - Saldo Akhir - Persediaan Rusak`  
- 🗂️ **Manajemen Master Data**:
  - Kode Rekening  
  - Satuan Barang  
- 📑 **Laporan**:
  - Laporan bulanan  
  - Laporan tahunan  

---

## 🛠️ Instalasi

Ikuti langkah-langkah berikut untuk menjalankan SIMBAK di lokal:

### 1. Clone Repository
```bash
git clone https://github.com/fazarprgmr/simbak.git
cd SIMBAK

composer install
npm install
npm run dev

cp .env.example .env
Atur database

php artisan key:generate

php artisan storage:link

php artisan migrate --seed

php artisan serve
