<?php
require 'config.php';

// EXPORT ke CSV — tetap pakai prepared statement seperti query lain
$keyword = trim($_GET['q'] ?? '');

$sql = "SELECT b.id, b.nama_barang, k.nama_kategori, b.jumlah, b.harga, b.deskripsi
        FROM barang b
        JOIN kategori k ON b.kategori_id = k.id";

$params = [];
if ($keyword !== '') {
    $sql .= " WHERE b.nama_barang LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}
$sql .= " ORDER BY b.id ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

// Header supaya browser men-download-nya sebagai file .csv
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=inventaris_' . date('Y-m-d_His') . '.csv');

$output = fopen('php://output', 'w');

// BOM supaya karakter dibaca benar saat dibuka di Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header kolom
fputcsv($output, ['ID', 'Nama Barang', 'Kategori', 'Jumlah', 'Harga', 'Deskripsi']);

foreach ($data as $row) {
    fputcsv($output, [
        $row['id'],
        $row['nama_barang'],
        $row['nama_kategori'],
        $row['jumlah'],
        $row['harga'],
        $row['deskripsi'],
    ]);
}

fclose($output);
exit;
