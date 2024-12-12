-- Database: dasar_web
CREATE DATABASE dasar_web;
GO
USE dasar_web;
GO

-- --------------------------------------------------------

-- Table structure for table m_buku
CREATE TABLE m_buku (
    buku_id INT IDENTITY(1,1) PRIMARY KEY,
    kategori_id INT NOT NULL,
    buku_kode VARCHAR(10) NOT NULL,
    buku_nama VARCHAR(255) NOT NULL,
    jumlah INT NOT NULL,
    deskripsi TEXT,
    gambar TEXT
);
GO

-- Inserting data into m_buku
INSERT INTO m_buku (kategori_id, buku_kode, buku_nama, jumlah, deskripsi, gambar)
VALUES
(3, 'KHBK', '111 Kode HTML untuk Belajar Kilat', 2, 'Sinopsis: Teknik menguasai HTML secepat kilat untuk pemula yang ingin menjadi master HTML! Untuk menguasai pemrograman web, HTML adalah syarat mutlak yang harus dikuasai sebelum mempelajari bahasa pemrograman web lainnya. ...', 'https://cdn.gramedia.com/uploads/items/9786020496863_111_Kode_HTML.jpg'),
(2, 'LB', 'Laut Bercerita', 3, 'Buku ini terdiri atas dua bagian. Bagian pertama mengambil sudut pandang seorang mahasiswa aktivis bernama Laut, menceritakan bagaimana Laut dan kawan-kawannya menyusun rencana, berpindah-pindah dalam pelarian, hingga tertangkap oleh pasukan rahasia. ...', 'https://cdn.gramedia.com/uploads/items/9786024246945_Laut-Bercerita.png'),
(0, 'sa', 'sa', 2, 'sa', 'sa');
GO

-- --------------------------------------------------------

-- Table structure for table m_kategori
CREATE TABLE m_kategori (
    kategori_id INT IDENTITY(1,1) PRIMARY KEY,
    kategori_kode VARCHAR(20) NOT NULL,
    kategori_nama VARCHAR(255) NOT NULL
);
GO

-- Inserting data into m_kategori
INSERT INTO m_kategori (kategori_kode, kategori_nama)
VALUES
('FKS', 'Fiksi'),
('NVL', 'Novel'),
('ILM', 'Ilmiah'),
('MTR', 'Misteri'),
('SSL', 'Sosial'),
('LKK', 'MELEDAK');
GO

-- --------------------------------------------------------

-- Table structure for table m_user
CREATE TABLE m_user (
    user_id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    nama VARCHAR(50) NOT NULL,
    level VARCHAR(10) CHECK (level IN ('admin', 'user')),
    password VARCHAR(255) NOT NULL
);
GO

-- Inserting data into m_user
INSERT INTO m_user (username, nama, level, password)
VALUES
('admin', 'Administrator', 'admin', '$2y$10$t9Gz10izTPl7Xku6MyPdlOnkmgawj18Tt848AOzjvAo0.srumQ03W'),
('aira', 'Pengguna', 'user', '$2y$10$t9Gz10izTPl7Xku6MyPdlOnkmgawj18Tt848AOzjvAo0.srumQ03W');
GO
