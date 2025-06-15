-- =========================
-- 1. CREATE DATABASE
-- =========================
CREATE DATABASE peminjaman_barang;
USE peminjaman_barang;

-- =========================
-- 2. TABEL USERS
-- =========================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  PASSWORD VARCHAR(255) NOT NULL,
  role ENUM('admin') DEFAULT 'admin'
);

-- =========================
-- 3. TABEL BARANG
-- =========================
CREATE TABLE barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_barang VARCHAR(100) NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  kondisi VARCHAR(100),
  stok_awal INT DEFAULT 0,
  gambar VARCHAR(255)
);

-- =========================
-- 4. TABEL TRANSAKSI
-- =========================
CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  barang_id INT,
  jenis ENUM('peminjaman','pengembalian'),
  tanggal DATE,
  keterangan TEXT,
  surat_path VARCHAR(255),
  jumlah INT NOT NULL DEFAULT 0,
  FOREIGN KEY (barang_id) REFERENCES barang(id)
);

-- =========================
-- 5. INSERT USER ADMIN
-- =========================
INSERT INTO users (username, PASSWORD, role)
VALUES ('admin', 'admin123', 'admin');

-- =========================
-- 6. INSERT DATA BARANG
-- =========================
INSERT INTO barang (id, nama_barang, stok, kondisi, stok_awal, gambar) VALUES
(1, 'Proyektor Epson', 10, 'Baik', 10, '1749912481_proyektor.jpg'),
(2, 'Laptop ASUS', 5, 'Baru', 5, '1749913370_laptop.jpeg'),
(3, 'Speaker Portable', 3, 'Baik', 3, '1749915095_speakter.jpg'),
(4, 'Kamera DSLR', 5, 'Baik', 5, '1749915108_kamera.jpg'),
(6, 'Kursi', 8, 'Baik', 8, '684d965b3450a_kursi.webp'),
(7, 'Spidol', 10, 'Baru', 10, '684d96fc9b3de_spidol.jpg'),
(8, 'Penghapus', 8, 'Baru', 8, '684d971ebd719_penghapus.jpg');
