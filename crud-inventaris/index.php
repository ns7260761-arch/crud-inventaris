<?php
require 'config.php';
require 'helpers.php';

$flash = get_flash();

// Pencarian sederhana (opsional, tetap pakai prepared statement)
$keyword = trim($_GET['q'] ?? '');

// READ + JOIN: ambil data barang beserta nama kategorinya
$sql = "SELECT b.id, b.nama_barang, k.nama_kategori, b.jumlah, b.harga, b.deskripsi
        FROM barang b
        JOIN kategori k ON b.kategori_id = k.id";

$params = [];
if ($keyword !== '') {
    $sql .= " WHERE b.nama_barang LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}
$sql .= " ORDER BY b.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>CRUD Inventaris</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>📦 CRUD Inventaris Barang</h1>

    <?php if ($flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <div class="toolbar">
        <div class="toolbar-left">
            <a class="btn btn-primary" href="create.php">+ Tambah Barang</a>
            <a class="btn btn-export" href="export_csv.php<?= $keyword !== '' ? '?q=' . urlencode($keyword) : '' ?>">⬇ Export CSV</a>
        </div>
        <form method="get" class="search-form">
            <input type="text" name="q" placeholder="Cari nama barang..." value="<?= htmlspecialchars($keyword) ?>">
            <button type="submit" class="btn">Cari</button>
        </form>
    </div>

    <div class="summary-cards">
        <div class="stat-card">
            <span class="stat-number"><?= count($data) ?></span>
            <span class="stat-label">Total Barang</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= array_sum(array_column($data, 'jumlah')) ?></span>
            <span class="stat-label">Total Stok</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">Rp <?= number_format(array_sum(array_map(fn($r) => $r['jumlah'] * $r['harga'], $data)), 0, ',', '.') ?></span>
            <span class="stat-label">Total Nilai Aset</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="7" class="empty">Belum ada data barang.</td></tr>
            <?php else: ?>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td data-label="#"><?= htmlspecialchars($row['id']) ?></td>
                    <td class="nama-barang" data-label="Nama Barang"><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td data-label="Kategori"><span class="badge"><?= htmlspecialchars($row['nama_kategori']) ?></span></td>
                    <td data-label="Jumlah"><?= htmlspecialchars($row['jumlah']) ?></td>
                    <td data-label="Harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td class="deskripsi" data-label="Deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td class="actions" data-label="Aksi">
                        <a class="btn btn-edit" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                        <a class="btn btn-delete" href="delete.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Yakin ingin menghapus barang ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
