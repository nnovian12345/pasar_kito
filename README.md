# PasarKita — Marketplace PHP Native

Aplikasi marketplace sederhana menggunakan PHP Native, MySQLi, Bootstrap 5, dan REST API.

## Fitur
- Registrasi dan login pengguna
- Daftar, pencarian, kategori, dan detail produk
- Keranjang belanja dan checkout
- Riwayat pesanan pelanggan
- Dashboard administrator
- CRUD produk dan kategori
- Pengelolaan status pesanan
- REST API produk, kategori, dan pembuatan pesanan

## Struktur API
| Endpoint | Method | Keterangan |
|---|---|---|
| `/api/produk.php` | GET | Seluruh produk aktif |
| `/api/produk.php?id=1` | GET | Detail produk |
| `/api/kategori.php` | GET | Seluruh kategori |
| `/api/pesanan.php` | POST | Membuat pesanan (HTTP 201 jika berhasil) |

## Instalasi
- Untuk hosting InfinityFree, ikuti **README_INFINITYFREE.txt**.
- Untuk XAMPP, buat database `pasarkita`, import `database_pasarkita.sql`, lalu isi kredensial lokal pada `config.php`.

## Catatan
Konfigurasi database belum diisi karena hostname, nama database, username, dan password berbeda untuk setiap akun InfinityFree. Isi nilai tersebut dari menu **MySQL Databases** pada Control Panel hosting.
