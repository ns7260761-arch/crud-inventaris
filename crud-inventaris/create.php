<?php
require 'config.php';
require 'helpers.php';

$errors = [];

// Ambil daftar kategori untuk dropdown
$kategori_list = $pdo->query("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $kategori_id = $_POST['kategori_id'] ?? '';
    $jumlah      = $_POST['jumlah'] ?? '';
    $harga       = $_POST['harga'] ?? '';
    $deskripsi   = trim($_POST['deskripsi'] ?? '');

    // Validasi & sanitasi input (Tanggung Jawab: jaga data integrity)
    if ($nama_barang === '') $errors[] = 'Nama barang wajib diisi.';
    if (!ctype_digit((string)$kategori_id)) $errors[] = 'Kategori tidak valid.';
    if (!is_numeric($jumlah) || $jumlah < 0) $errors[] = 'Jumlah harus angka >= 0.';
    if (!is_numeric($harga) || $harga < 0) $errors[] = 'Harga harus angka >= 0.';

    if (empty($errors)) {
        // CREATE menggunakan prepared statement (mencegah SQL Injection)
        $stmt = $pdo->prepare(
            "INSERT INTO barang (nama_barang, kategori_id, jumlah, harga, deskripsi)
             VALUES (:nama_barang, :kategori_id, :jumlah, :harga, :deskripsi)"
        );
        $stmt->execute([
            ':nama_barang' => $nama_barang,
            ':kategori_id' => $kategori_id,
            ':jumlah'      => $jumlah,
            ':harga'       => $harga,
            ':deskripsi'   => $deskripsi,
        ]);

        set_flash('success', 'Barang berhasil ditambahkan.');
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Barang</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>➕ Tambah Barang</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" class="form">
        <label>Nama Barang</label>
        <input type="text" name="nama_barang" value="<?= htmlspecialchars($_POST['nama_barang'] ?? '') ?>" required>

        <label>Kategori</label>
        <select name="kategori_id" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategori_list as $k): ?>
                <option value="<?= $k['id'] ?>"
                    <?= (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $k['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['nama_kategori']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Jumlah</label>
        <input type="number" name="jumlah" min="0" value="<?= htmlspecialchars($_POST['jumlah'] ?? '0') ?>" required>

        <label>Harga</label>
        <input type="number" name="harga" min="0" step="0.01" value="<?= htmlspecialchars($_POST['harga'] ?? '0') ?>" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
