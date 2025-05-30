-- DATABASE NAME --
CREATE DATABASE peminjaman_barang

-- CREATE TABEL--
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  PASSWORD VARCHAR(255) NOT NULL,
  role ENUM('admin') DEFAULT 'admin'
);

CREATE TABLE barang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_barang VARCHAR(100) NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  kondisi VARCHAR(100)
);
ALTER TABLE barang ADD COLUMN stok_awal INT DEFAULT 0; -- Run terpisah karena variabel tambahan baru --


CREATE TABLE transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  barang_id INT,
  jenis ENUM('peminjaman','pengembalian'),
  tanggal DATE,
  keterangan TEXT,
  surat_path VARCHAR(255),
  FOREIGN KEY (barang_id) REFERENCES barang(id)
);
ALTER TABLE transaksi ADD COLUMN jumlah INT NOT NULL DEFAULT 0; -- Run terpisah karena variabel tambahan baru --

-- DATA DUMMY --
-- Admin User
INSERT INTO users (username, PASSWORD, role)
VALUES 
('admin', 'admin123', 'admin123');

-- Barang Dummy
INSERT INTO barang (nama_barang, stok, kondisi) VALUES
('Proyektor Epson', 3, 'Baik'),
('Laptop ASUS', 5, 'Baru'),
('Speaker Portable', 2, 'Baik'),
('Kamera DSLR', 1, 'Baik'),
('Meja Lipat', 10, 'Cukup');

-- Transaksi Dummy
INSERT INTO transaksi (barang_id, jenis, tanggal, keterangan, surat_path) VALUES
(1, 'peminjaman', '2025-05-20', 'Digunakan untuk presentasi kelas 12 IPA', NULL),
(2, 'peminjaman', '2025-05-21', 'Laptop untuk lomba coding', NULL),
(2, 'pengembalian', '2025-05-22', 'Sudah dikembalikan ke ruang TU', NULL),
(3, 'peminjaman', '2025-05-22', 'Digunakan untuk kegiatan OSIS', NULL);

