PASARKITA - MARKETPLACE TRADISIONAL
=================================

KEBUTUHAN
- XAMPP (Apache dan MySQL)
- PHP 8.0+ direkomendasikan
- Browser dan koneksi internet untuk memuat Bootstrap CDN

INSTALASI DI XAMPP
1. Ekstrak folder PasarKita ke C:\xampp\htdocs\
2. Jalankan Apache dan MySQL dari XAMPP Control Panel.
3. Buka http://localhost/phpmyadmin
4. Buat/import database dengan memilih tab Import dan file database_pasarkita.sql.
5. Pastikan konfigurasi di config.php sesuai:
   host=localhost, user=root, password kosong, database=pasarkita
6. Buka http://localhost/PasarKita/

AKUN ADMIN
1. Daftar akun biasa melalui menu Daftar.
2. Buka phpMyAdmin > database pasarkita > tabel users.
3. Edit kolom role untuk akun yang baru dibuat dari customer menjadi admin.
4. Login kembali, menu Admin akan muncul.
   (Jangan biarkan akun admin menggunakan password yang mudah.)

FITUR
- Daftar/login pelanggan dengan password_hash/password_verify
- Daftar dan pencarian produk serta filter kategori
- Detail produk
- Keranjang belanja berbasis session
- Checkout dan penyimpanan transaksi ke orders/order_items
- Pengurangan stok saat transaksi
- Riwayat pesanan pelanggan
- Dashboard admin, CRUD produk, kategori, dan pembaruan status pesanan

CATATAN
- Foto produk disimpan di folder uploads. Pastikan folder dapat ditulisi oleh PHP.
- Ini adalah starter project untuk tugas/praktikum. Sebelum produksi, tambahkan CSRF protection,
  validasi upload yang lebih ketat, pengaturan HTTPS, dan kebijakan pembayaran/pengiriman.
- Jangan mengunggah password database atau kredensial hosting ke GitHub.
