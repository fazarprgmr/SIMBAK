<img width="1920" height="1080" alt="Screenshot 2025-09-07 202448" src="https://github.com/user-attachments/assets/cc26cf13-655c-4471-8c0b-c3d424d8e337" /><img width="1920" height="1080" alt="Screenshot 2025-09-07 202455" src="https://github.com/user-attachments/assets/8a0ab5ed-430d-46e9-ac22-aeecccba21c2" /><img width="1920" height="1080" alt="Screenshot 2025-09-07 202411" src="https://github.com/user-attachments/assets/d68c5ac1-73d2-49d7-abe2-c25484576ec2" /><img width="1920" height="1080" alt="Screenshot 2025-09-07 202440" src="https://github.com/user-attachments/assets/1d0c2483-9d40-479e-97e6-84c1456f083a" />

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
git clone https://github.com/fazarprgmr/SIMBAK.git
cd SIMBAK
```
```bash
composer install
npm install
npm run dev
```
```bash
cp .env.example .env
Atur database
```
```bash
php artisan key:generate
```
```bash
php artisan storage:link
```
```bash
php artisan migrate --seed
```
```bash
php artisan serve
```




### 2. Login Menggunakan Akun ini
```bash
Email: admin@example.com
Password: admin123
```

<img width="1920" height="1080" alt="Screenshot 2025-09-07 202411" src="https://github.com/user-attachments/assets/42760b85-f95a-4bc9-bbf8-c18d91d0c54a" />
<img width="1920" height="1080" alt="Screenshot 2025-09-07 202426" src="https://github.com/user-attachments/assets/819aa34b-eaec-4c45-b992-0b2287ed41bb" />
<img width="1920" height="1080" alt="Screenshot 2025-09-07 202433" src="https://github.com/user-attachments/assets/9a08cea5-4186-431f-a2d9-9fbcff7e3861" />
<img width="1920" height="1080" alt="Screenshot 2025-09-07 202440" src="https://github.com/user-attachments/assets/8f439c8f-e3dc-48d4-ae95-3978e634c2dd" />
<img width="1920" height="1080" alt="Screenshot 2025-09-07 202448" src="https://github.com/user-attachments/assets/48fc1313-7e6b-40a1-b9b8-1a0293cfc13b" />
<img width="1920" height="1080" alt="Screenshot 2025-09-07 202455" src="https://github.com/user-attachments/assets/2de9921a-59ef-4de6-8e00-15227f6cd271" />
<img width="1920" height="1080" alt="Screenshot 2025-09-07 202831" src="https://github.com/user-attachments/assets/4a2c6480-9f6d-4e7d-ad6d-95dabe3f9063" />


### 🏢 SIMBAK
![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=flat-square&logo=laravel)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-purple?style=flat-square&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Status](https://img.shields.io/badge/Status-Active-success?style=flat-square)
