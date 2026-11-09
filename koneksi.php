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
    http_response_code(500);
    die("Koneksi database gagal. Pastikan MySQL XAMPP aktif dan database db_tokoku tersedia. Detail: "
        . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
