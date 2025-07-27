# Aplikasi Koperasi Pegawai

Aplikasi web sederhana untuk manajemen data pengadaan barang dan perlengkapan rumah tangga koperasi pegawai.
Dibangun sebagai bagian dari pelatihan LSP.

## Fitur Utama

* Login & Otentikasi Pengguna
* Manajemen Data Master (CRUD):
    * Petugas
    * Customer
    * Item (Barang)
* Manajemen Transaksi (CRUD):
    * Sales (Penjualan/Faktur)
    * Transaction Detail (Detail Item per Penjualan)
* Dashboard dengan Ringkasan Statistik

## Teknologi yang Digunakan

* **Backend:** PHP (Native)
* **Database:** MySQL/MariaDB
* **Frontend:** HTML, CSS, JavaScript
* **Framework CSS/JS:** SB Admin 2 (Bootstrap)

## Instalasi

1.  **Clone Repositori:**
    ```bash
    git clone [https://github.com/yogiastriana/koperasi-app.git](https://github.com/yogiastriana/koperasi-app.git)
    ```
    (Ganti `USERNAME_ANDA` dengan username GitHub Anda)
2.  **Siapkan Lingkungan Server:** Pastikan Anda memiliki XAMPP (Apache, MySQL, PHP) terinstal.
3.  **Pindahkan Proyek:** Pindahkan folder `koperasi-app` ke direktori `htdocs` XAMPP Anda (`E:\xampp\htdocs\`).
4.  **Konfigurasi Database:**
    * Buat database baru di phpMyAdmin (misal: `koperasi_db`).
    * Import skema database Anda (dari file SQL yang Anda miliki, atau buat ulang tabel secara manual).
    * Edit `koperasi-app/app/config/Database.php` untuk menyesuaikan kredensial database Anda.
5.  **Akses Aplikasi:** Buka browser dan navigasi ke `http://localhost/koperasi-app/`.
6.  **Login:** Gunakan kredensial default (jika ada) atau buat petugas baru melalui database.

## Kontributor / Kredit

Proyek ini dikembangkan oleh:

**[Yogi Astriana]**
* [Link Profil GitHub Anda](https://github.com/yogiastriana)
* [Link LinkedIn (https://www.linkedin.com/in/yogi-astriana-a44b70250/)]
* [Email (yogiastriyana123@gmail.com)]

## Lisensi

Proyek ini dilisensikan.

---
*Catatan: Ini adalah proyek pelatihan dan mungkin memerlukan penyesuaian lebih lanjut untuk penggunaan produksi.*