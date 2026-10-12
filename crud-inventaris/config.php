<?php
// config.php
// Koneksi database menggunakan PDO

$host = 'localhost';
$db   = 'db_inventaris';
$user = 'root';      // ganti sesuai konfigurasi MySQL kamu
$pass = '';           // ganti sesuai konfigurasi MySQL kamu
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // supaya error PDO ke-catch, bukan diam-diam gagal
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // pakai prepared statement asli dari MySQL
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Ketangguhan: tangani error koneksi PDO dengan pesan yang jelas
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()));
}
