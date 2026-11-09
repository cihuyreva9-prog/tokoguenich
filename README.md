
# 🛒 TokoKu – Sistem Informasi Penjualan Toko

**TokoKu** adalah aplikasi berbasis web untuk membantu pengelolaan data dan transaksi penjualan toko. Proyek ini dibuat sebagai bagian dari pembelajaran **Database – Proyek Terintegrasi**.

## ✨ Fitur Utama

- 🔐 Login Admin
- 🚪 Logout Admin
- 📊 Dashboard Penjualan
- 📦 Manajemen Produk
- 🏷️ Manajemen Kategori
- 👥 Manajemen Pelanggan
- 🧾 Manajemen Transaksi
- 📈 Laporan Penjualan
- 🗄️ Pengelolaan Database MySQL

## 🛠️ Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| PHP | Backend |
| MySQL / MariaDB | Database |
| HTML5 | Struktur halaman |
| CSS3 | Desain tampilan |
| JavaScript | Interaksi website |
| PDO | Koneksi database |
| XAMPP | Web server lokal |
| GitHub | Penyimpanan proyek |

## 🗂️ Struktur Database

Nama database: `db_tokoku`

### Tabel Database

- `tbl_cabang`
- `tbl_kategori`
- `tbl_produk`
- `tbl_pelanggan`
- `tbl_transaksi`
- `tbl_detail_transaksi`
- `tbl_log`
- `log_pelanggan`
- `log_perubahan_pelanggan`

### View Database

- `view_laporan_transaksi`
- `view_total_belanja`

## ⚙️ Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/USERNAME/TokoKu.git
```

### 2. Pindahkan Folder

Pindahkan folder proyek ke:

```text
C:\xampp\htdocs\TokoKu
```

### 3. Jalankan XAMPP

Aktifkan:
- Apache
- MySQL

### 4. Buat Database

1. Buka `http://localhost/phpmyadmin`.
2. Buat database dengan nama `db_tokoku`.
3. Import file database SQL proyek.

### 5. Konfigurasi Database

Buka file `koneksi.php` dan sesuaikan:

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

### 6. Setup Admin

Buka browser:

```text
http://localhost/TokoKu/setup_admin.php
```

### 7. Jalankan Website

Buka:

```text
http://localhost/TokoKu/login.php
```

## 🔑 Akun Login Demo

| Keterangan | Akun |
|---|---|
| Username | `admin` |
| Password | `admin12345` |

**Catatan:** Akun tersebut merupakan akun demo. Ganti password setelah instalasi.

## 📸 Screenshot Website

Tambahkan screenshot aplikasi ke folder `screenshots`.

Contoh:

### Halaman Login

![Login TokoKu](screenshots/login.png)

### Dashboard

![Dashboard TokoKu](screenshots/dashboard.png)

## 🎯 Tujuan Proyek

Proyek ini dibuat untuk menerapkan pembelajaran:

- Database dan MySQL.
- Relasi antar tabel.
- SQL Query.
- Database View.
- Stored Procedure.
- Stored Function.
- Pengelolaan transaksi.
- Sistem informasi berbasis database.

## 👨‍💻 Identitas Pengembang

| Keterangan | Data |
|---|---|
| Nama | REVA ALVAY |
| Kelas | XII RPL 2 |
| Proyek | Sistem Informasi Penjualan Toko |
| Nama Aplikasi | TokoKu |

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pembelajaran dan pengembangan di bidang Rekayasa Perangkat Lunak (RPL).

---

⭐ **Terima kasih telah mengunjungi repository TokoKu!**
