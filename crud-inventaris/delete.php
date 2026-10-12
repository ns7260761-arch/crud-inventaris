<?php
require 'config.php';
require 'helpers.php';

$id = $_GET['id'] ?? null;

if (!$id || !ctype_digit((string)$id)) {
    set_flash('error', 'ID barang tidak valid.');
    header('Location: index.php');
    exit;
}

try {
    // DELETE menggunakan prepared statement
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        set_flash('success', 'Barang berhasil dihapus.');
    } else {
        set_flash('error', 'Barang tidak ditemukan.');
    }
} catch (PDOException $e) {
    // Ketangguhan: tangani error PDO (misal pelanggaran foreign key) dengan baik
    set_flash('error', 'Gagal menghapus barang: ' . $e->getMessage());
}

header('Location: index.php');
exit;
