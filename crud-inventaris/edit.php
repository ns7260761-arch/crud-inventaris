<?php
require 'config.php';
require 'helpers.php';

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id || !ctype_digit((string)$id)) {
    set_flash('error', 'ID barang tidak valid.');
    header('Location: index.php');
    exit;
}

$errors = [];
$kategori_list = $pdo->query("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori")->fetchAll();

// Ambil data barang yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM barang WHERE id = :id");
$stmt->execute([':id' => $id]);
$barang = $stmt->fetch();

if (!$barang) {
    set_flash('error', 'Barang tidak ditemukan.');
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $kategori_id = $_POST['kategori_id'] ?? '';
    $jumlah      = $_POST['jumlah'] ?? '';
    $harga       = $_POST['harga'] ?? '';
    $deskripsi   = trim($_POST['deskripsi'] ?? '');

    if ($nama_barang === '') $errors[] = 'Nama barang wajib diisi.';
    if (!ctype_digit((string)$kategori_id)) $errors[] = 'Kategori tidak valid.';
    if (!is_numeric($jumlah) || $jumlah < 0) $errors[] = 'Jumlah harus angka >= 0.';
    if (!is_numeric($harga) || $harga < 0) $errors[] = 'Harga harus angka >= 0.';

    if (empty($errors)) {
        // UPDATE menggunakan prepared statement
        $stmt = $pdo->prepare(
            "UPDATE barang
             SET nama_barang = :nama_barang, kategori_id = :kategori_id,
                 jumlah = :jumlah, harga = :harga, deskripsi = :deskripsi
             WHERE id = :id"
        );
        $stmt->execute([
            ':nama_barang' => $nama_barang,
            ':kategori_id' => $kategori_id,
            ':jumlah'      => $jumlah,
            ':harga'       => $harga,
            ':deskripsi'   => $deskripsi,
            ':id'          => $id,
        ]);

        set_flash('success', 'Barang berhasil diperbarui.');
        header('Location: index.php');
        exit;
    }
    // supaya form tetap menampilkan input terbaru saat validasi gagal
    $barang = array_merge($barang, $_POST);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Barang</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>✏️ Edit Barang</h1>

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
        <input type="hidden" name="id" value="<?= htmlspecialchars($barang['id']) ?>">

        <label>Nama Barang</label>
        <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>

        <label>Kategori</label>
        <select name="kategori_id" required>
            <?php foreach ($kategori_list as $k): ?>
                <option value="<?= $k['id'] ?>" <?= ($barang['kategori_id'] == $k['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['nama_kategori']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Jumlah</label>
        <input type="number" name="jumlah" min="0" value="<?= htmlspecialchars($barang['jumlah']) ?>" required>

        <label>Harga</label>
        <input type="number" name="harga" min="0" step="0.01" value="<?= htmlspecialchars($barang['harga']) ?>" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi"><?= htmlspecialchars($barang['deskripsi']) ?></textarea>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="index.php" class="btn">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
