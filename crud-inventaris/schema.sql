-- schema.sql
-- Database: db_inventaris
-- Tugas Rutin 8 - CRUD Inventaris (PHP Native + PDO)

CREATE DATABASE IF NOT EXISTS db_inventaris;
USE db_inventaris;

-- Tabel kategori (induk)
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Tabel barang (anak), kategori_id adalah FOREIGN KEY ke kategori.id
-- Struktur ini sudah 3NF: tidak ada data kategori yang diulang-ulang di tabel barang,
-- hanya id-nya saja yang direferensikan.
CREATE TABLE IF NOT EXISTS barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(150) NOT NULL,
    kategori_id INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 0,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    deskripsi TEXT,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_barang_kategori
        FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Data contoh kategori
INSERT INTO kategori (nama_kategori) VALUES
('Alat Tulis'),
('Elektronik'),
('Perlengkapan Kantor');

-- Data contoh barang
INSERT INTO barang (nama_barang, kategori_id, jumlah, harga, deskripsi) VALUES
('Pulpen Pilot', 1, 100, 3500.00, 'Pulpen tinta hitam standar'),
('Laptop Asus', 2, 5, 7500000.00, 'Laptop untuk operasional kantor'),
('Kertas A4', 3, 50, 45000.00, 'Kertas 1 rim isi 500 lembar');
