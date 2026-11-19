PANDUAN DEPLOY PASARKITA KE INFINITYFREE
========================================

A. BUAT DATABASE
1. Login ke InfinityFree Client Area dan buka Control Panel akun hosting.
2. Masuk menu MySQL Databases, lalu buat database baru.
3. Catat MySQL Hostname, Database Name, Username, dan Password yang ditampilkan.
   PENTING: Host database InfinityFree biasanya BUKAN localhost. Gunakan hostname
   persis seperti yang ditampilkan pada panel akun Anda.
4. Buka phpMyAdmin dari panel hosting, pilih database yang baru dibuat.
5. Pilih Import, unggah database_pasarkita.sql, lalu jalankan.
   File SQL ini tidak menjalankan CREATE DATABASE/USE karena database harus dibuat
   lebih dahulu melalui panel InfinityFree.

B. ATUR KONEKSI DATABASE
1. Buka file config.php.
2. Ganti empat nilai berikut dengan data dari menu MySQL Databases:
   $host = 'ISI_HOST_MYSQL_INFINITYFREE';
   $user = 'ISI_USERNAME_DATABASE';
   $pass = 'ISI_PASSWORD_DATABASE';
   $db   = 'ISI_NAMA_DATABASE';
3. Simpan file. Jangan membagikan config.php karena berisi password database.

C. UPLOAD FILE WEBSITE
1. Buka File Manager InfinityFree atau sambungkan FTP.
2. Masuk ke folder htdocs pada domain/subdomain yang akan digunakan.
3. Upload ZIP proyek ini, lalu Extract di dalam htdocs.
4. Pastikan index.php berada langsung di htdocs/index.php, bukan di dalam folder
   tambahan seperti htdocs/PasarKita/index.php.
5. Pastikan folder uploads dapat ditulisi oleh PHP. Jika upload foto gagal, periksa
   permission folder uploads melalui File Manager.
6. Buka domain Anda menggunakan HTTPS.

D. BUAT AKUN ADMIN
1. Daftar akun melalui halaman Daftar pada website.
2. Buka phpMyAdmin > database > tabel users.
3. Jalankan query berikut dengan email akun Anda:
   UPDATE users SET role='admin' WHERE email='email-anda@example.com';
4. Keluar lalu login kembali. Menu Admin akan tampil.

E. UJI API
- GET  https://DOMAIN-ANDA/api/produk.php
- GET  https://DOMAIN-ANDA/api/produk.php?id=1
- GET  https://DOMAIN-ANDA/api/kategori.php
- POST https://DOMAIN-ANDA/api/pesanan.php
  Header: Content-Type: application/json
  Body contoh:
  {
    "user_id": 1,
    "address": "Jl. Melati No. 10",
    "phone": "081234567890",
    "notes": "Mohon pilih produk segar",
    "items": [{"product_id": 1, "quantity": 2}]
  }

Jika sukses, API pesanan mengembalikan HTTP 201 Created dan JSON success=true.
Pastikan user_id dan product_id memang tersedia pada database, produk aktif,
dan stok mencukupi. Periksa tabel orders dan order_items untuk memastikan transaksi
tersimpan.

CATATAN PENTING
- Jangan mengunggah database_pasarkita.sql ke folder publik untuk dibuka browser.
  File tersebut diblokir oleh .htaccess; sebaiknya hapus setelah proses import.
- Jangan mengubah nama file/folder api, assets, partials, admin, atau uploads.
- Setelah mengubah config.php, jika muncul pesan koneksi database, periksa kembali
  empat nilai database dan pastikan database sudah di-import.
- API POST pesanan pada versi ini menerima user_id dari body untuk kebutuhan tugas.
  Untuk penggunaan publik/produksi, endpoint harus dilindungi autentikasi/token agar
  pengguna tidak dapat membuat pesanan atas nama user lain.
