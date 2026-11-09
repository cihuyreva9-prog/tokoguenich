# 🛒 TokoKu – Sistem Informasi Penjualan Toko

**TokoKu** adalah aplikasi berbasis web yang dikembangkan untuk membantu pengelolaan data dan transaksi penjualan toko. Proyek ini dibuat sebagai bagian dari pembelajaran **Database – Proyek Terintegrasi**.

Aplikasi ini menggunakan PHP dan MySQL sebagai teknologi utama dengan tampilan antarmuka yang sederhana, modern, dan mudah digunakan.

## ✨ Fitur Utama

- 🔐 **Login Admin** – Autentikasi administrator menggunakan username dan password.
- 🚪 **Logout** – Mengakhiri sesi administrator dengan aman.
- 📊 **Dashboard** – Menampilkan ringkasan informasi penjualan.
- 📦 **Manajemen Produk** – Mengelola data produk toko.
- 🏷️ **Manajemen Kategori** – Mengelola kategori produk.
- 👥 **Manajemen Pelanggan** – Mengelola informasi pelanggan.
- 🧾 **Manajemen Transaksi** – Menampilkan dan mengelola transaksi penjualan.
- 📈 **Laporan Penjualan** – Menampilkan ringkasan data penjualan.
- 🗄️ **Database MySQL** – Menyimpan data aplikasi secara terstruktur.

## 🛠️ Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP | Backend aplikasi |
| MySQL / MariaDB | Database |
| HTML5 | Struktur halaman |
| CSS3 | Tampilan website |
| JavaScript | Interaksi halaman |
| PDO | Koneksi dan query database |
| XAMPP | Web server lokal |
| Git & GitHub | Version control dan penyimpanan proyek |

## 🗂️ Struktur Database

Database yang digunakan bernama `db_tokoku`.

Tabel utama:

- `tbl_cabang`
- `tbl_kategori`
- `tbl_produk`
- `tbl_pelanggan`
- `tbl_transaksi`
- `tbl_detail_transaksi`
- `tbl_log`

Tabel tambahan:

- `log_pelanggan`
- `log_perubahan_pelanggan`

View database:

- `view_laporan_transaksi`
- `view_total_belanja`

## ⚙️ Cara Instalasi

### 1. Persiapan

Pastikan sudah menginstal:
- XAMPP
- PHP
- MySQL atau MariaDB
- Browser

### 2. Clone Repository

Buka terminal atau CMD, kemudian jalankan:

```bash
git clone https://github.com/USERNAME/TokoKu.git
```

Ganti `USERNAME` dengan username GitHub milikmu.

### 3. Pindahkan Folder

Letakkan folder proyek ke dalam:

```text
C:\xampp\htdocs\TokoKu
```

### 4. Jalankan XAMPP

Aktifkan:
- Apache
- MySQL

### 5. Import Database

1. Buka `http://localhost/phpmyadmin`.
2. Buat database bernama `db_tokoku`.
3. Pilih database tersebut.
4. Import file SQL database proyek jika tersedia.

### 6. Konfigurasi Database

Sesuaikan file `koneksi.php` dengan konfigurasi MySQL lokal:

```php
<?php
$host = "127.0.0.1";
$db   = "db_tokoku";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Koneksi database gagal.");
}
```

### 7. Setup Akun Admin

Jalankan file berikut melalui browser:

```text
http://localhost/TokoKu/setup_admin.php
```

Jika proses setup berhasil, akun administrator dapat digunakan untuk login.

### 8. Buka Aplikasi

Akses melalui browser:

```text
http://localhost/TokoKu/login.php
```

## 🔑 Akun Login Demo

| Keterangan | Data |
|---|---|
| Username | `admin` |
| Password | `admin12345` |

**Catatan:** Ganti password demo setelah instalasi. Jangan mengunggah password asli atau informasi rahasia ke repository publik.

## 📸 Screenshot Aplikasi

Tambahkan screenshot aplikasi pada folder `screenshots/`, kemudian tampilkan di bagian ini.

Contoh:

```markdown
### Halaman Login
![Login TokoKu](screenshots/login.png)

### Dashboard
![Dashboard TokoKu](screenshots/dashboard.png)
```

## 🎯 Tujuan Proyek

Proyek ini bertujuan untuk menerapkan materi pembelajaran database, meliputi:

- Pembuatan dan pengelolaan database.
- Relasi antar tabel.
- Penggunaan SQL.
- Penggunaan View.
- Stored Procedure dan Function.
- Pengelolaan transaksi penjualan.
- Implementasi aplikasi berbasis database.

## 👨‍💻 Pengembang

**Nama:** REVA ALVAY  
**Kelas:** XII RPL 2  
**Proyek:** Pengembangan Database Sistem Informasi Penjualan Toko

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran dan pengembangan di bidang Rekayasa Perangkat Lunak (RPL).

---

⭐ **Terima kasih telah mengunjungi repository TokoKu!**
