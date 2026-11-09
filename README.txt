TOKOKU - SISTEM INFORMASI PENJUALAN TOKO
=======================================

TEKNOLOGI
- PHP Native
- MySQL / MariaDB
- HTML, CSS, JavaScript
- PDO untuk koneksi database

CARA MENJALANKAN
1. Ekstrak folder TokoKu ke:
   C:\xampp\htdocs\
   atau C:\lv_reva\xampp\htdocs\

2. Pastikan Apache dan MySQL di XAMPP sudah Start.

3. Pastikan database db_tokoku sudah tersedia di phpMyAdmin.

4. Buka koneksi.php dan sesuaikan host, username, password jika berbeda.
   Default: host 127.0.0.1, user root, password kosong.

5. Buka browser:
   http://localhost/TokoKu/

FITUR TAMPILAN
- Dashboard statistik produk, pelanggan, transaksi, dan penjualan
- Grafik penjualan berdasarkan transaksi enam bulan terakhir
- Ringkasan stok dan peringatan stok rendah
- Daftar produk dan kategori
- Data pelanggan
- Riwayat transaksi
- Laporan penjualan dan tombol cetak
- Pencarian tabel
- Layout responsif untuk desktop dan ponsel

CATATAN DATABASE
Website membaca tabel yang sudah ada di db_tokoku.
Website ini tidak membuat ulang, menghapus, atau mengubah data database.
Struktur yang digunakan mengikuti kolom yang telah terlihat:
tbl_produk: id_produk, id_kategori, nama_produk, harga, stok
tbl_kategori: id_kategori, nama_kategori
tbl_pelanggan: id_pelanggan, nama_pelanggan, kota
tbl_transaksi: id_transaksi, tanggal, id_pelanggan, total_belanja
tbl_detail_transaksi: id_detail, id_transaksi, nama_produk, jumlah, harga

Jika nama kolom database berbeda, sesuaikan query pada index.php.
