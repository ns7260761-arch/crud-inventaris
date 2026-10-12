# CRUD Inventaris

Aplikasi CRUD (Create, Read, Update, Delete) sederhana untuk mengelola data inventaris barang, dibuat menggunakan PHP Native dan PDO untuk koneksi ke database MySQL.

## Fitur

- **Create**: tambah data barang baru beserta kategorinya
- **Read**: menampilkan daftar barang dengan `JOIN` ke tabel kategori, dilengkapi pencarian
- **Update**: edit data barang yang sudah ada
- **Delete**: hapus data barang (dengan konfirmasi)
- Menggunakan **prepared statement** di semua query untuk mencegah SQL Injection
- Struktur database **3NF** dengan **foreign key** (`barang.kategori_id` → `kategori.id`)
- Output di-sanitasi dengan `htmlspecialchars()` untuk mencegah XSS
- **Export data ke CSV** (ikut mengikuti kata kunci pencarian yang sedang aktif)
- Tampilan ringkasan (total barang, total stok, total nilai aset) dan tabel responsif untuk layar kecil

## Struktur Database

- `kategori` (id, nama_kategori)
- `barang` (id, nama_barang, kategori_id [FK], jumlah, harga, deskripsi, dibuat_pada)

## Langkah Import Database

1. Buka **phpMyAdmin** (atau MySQL client lain).
2. Buat database baru bernama `db_inventaris` (atau langsung import, karena `schema.sql` sudah membuat database-nya otomatis).
3. Pilih tab **Import**, lalu pilih file `schema.sql` dari folder proyek ini.
4. Klik **Go/Import** dan tunggu sampai selesai — tabel `kategori` dan `barang` beserta data contoh akan otomatis terbuat.
5. Buka `config.php`, sesuaikan `$user` dan `$pass` dengan konfigurasi MySQL lokal kamu (default XAMPP: user `root`, password kosong).
6. Jalankan aplikasi melalui server lokal, contoh dengan XAMPP:
   - Letakkan folder proyek ini di dalam `htdocs`
   - Akses melalui browser: `http://localhost/crud-inventaris/index.php`

## Screenshot Aplikasi

> Tambahkan screenshot aplikasi kamu di sini setelah dijalankan, misalnya:

**Halaman Daftar Barang**
`(sisipkan screenshot index.php di sini)`

**Halaman Tambah Barang**
`(sisipkan screenshot create.php di sini)`

**Halaman Edit Barang**
`(sisipkan screenshot edit.php di sini)`

## Struktur File

```
crud-inventaris/
├── config.php      # koneksi database (PDO)
├── helpers.php      # fungsi bantu (flash message)
├── index.php        # READ (list + JOIN + pencarian)
├── create.php        # CREATE
├── edit.php          # UPDATE
├── delete.php        # DELETE
├── export_csv.php     # export data ke file CSV
├── style.css          # styling
├── schema.sql         # skema database + data contoh
└── README.md
```

## Nilai Karakter yang Diterapkan

- **Ketangguhan**: penanganan error PDO (`try-catch`) saat koneksi maupun saat delete gagal karena constraint foreign key
- **Tanggung Jawab**: validasi input di sisi server untuk menjaga integritas data
- **Kejujuran**: seluruh kode ditulis sendiri sesuai pemahaman materi
- **Ketekunan**: query disusun dengan teliti (prepared statement, JOIN, dan penamaan parameter yang konsisten)
