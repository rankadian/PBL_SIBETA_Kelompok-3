-- Drop existing tables (if exists)
IF OBJECT_ID('dbo.absensi', 'U') IS NOT NULL DROP TABLE dbo.absensi;
IF OBJECT_ID('dbo.admin', 'U') IS NOT NULL DROP TABLE dbo.admin;
IF OBJECT_ID('dbo.kps', 'U') IS NOT NULL DROP TABLE dbo.kps;
IF OBJECT_ID('dbo.mahasiswa', 'U') IS NOT NULL DROP TABLE dbo.mahasiswa;
IF OBJECT_ID('dbo.pkl', 'U') IS NOT NULL DROP TABLE dbo.pkl;
IF OBJECT_ID('dbo.publikasi', 'U') IS NOT NULL DROP TABLE dbo.publikasi;
IF OBJECT_ID('dbo.skkm', 'U') IS NOT NULL DROP TABLE dbo.skkm;
IF OBJECT_ID('dbo.surat_bebas_tanggungan', 'U') IS NOT NULL DROP TABLE dbo.surat_bebas_tanggungan;
IF OBJECT_ID('dbo.tanggungan', 'U') IS NOT NULL DROP TABLE dbo.tanggungan;
IF OBJECT_ID('dbo.toeic', 'U') IS NOT NULL DROP TABLE dbo.toeic;
IF OBJECT_ID('dbo.ukt', 'U') IS NOT NULL DROP TABLE dbo.ukt;
IF OBJECT_ID('dbo.user', 'U') IS NOT NULL DROP TABLE dbo.[user];
IF OBJECT_ID('dbo.verifikasi_admin', 'U') IS NOT NULL DROP TABLE dbo.verifikasi_admin;
IF OBJECT_ID('dbo.verifikasi_kps', 'U') IS NOT NULL DROP TABLE dbo.verifikasi_kps;

-- Membuat database
CREATE DATABASE sibeta;
GO

USE sibeta;
GO


-- Membuat tabel user
CREATE TABLE [user] (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level VARCHAR(50) CHECK (level IN ('mahasiswa', 'admin', 'kps')) NOT NULL
);


-- Membuat tabel mahasiswa
CREATE TABLE dbo.mahasiswa (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nim VARCHAR(15) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES [user](id)
);


-- Membuat tabel admin
CREATE TABLE dbo.admin (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES [user](id)
);


-- Membuat tabel kps
CREATE TABLE dbo.kps (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES [user](id)
);


-- Membuat tabel absensi
CREATE TABLE dbo.absensi (
    id_absensi INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    jumlah_alpha INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel ukt
CREATE TABLE dbo.ukt (
    id_ukt INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel pkl
CREATE TABLE dbo.pkl (
    id_pkl INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    tempat_pkl VARCHAR(255) NOT NULL,
    laporan_pkl VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel toeic
CREATE TABLE dbo.toeic (
    id_toeic INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    hasil_toeic VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel skkm
CREATE TABLE dbo.skkm (
    id_skkm INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    point_skkm INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel publikasi
CREATE TABLE dbo.publikasi (
    id_publikasi INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    judul_skripsi VARCHAR(255) NOT NULL,
    file_publikasi VARBINARY(MAX),
    file_program VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel tanggungan
CREATE TABLE dbo.tanggungan (
    id_tanggungan INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    jenis_tanggungan VARCHAR(50) CHECK (jenis_tanggungan IN ('absensi', 'ukt', 'pkl', 'toeic', 'skkm', 'publikasi')) NOT NULL,
    absensi_id INT,
    ukt_id INT,
    pkl_id INT,
    toeic_id INT,
    skkm_id INT,
    publikasi_id INT,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    tanggal_ajukan DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (absensi_id) REFERENCES absensi(id_absensi) ON DELETE NO ACTION,
    FOREIGN KEY (ukt_id) REFERENCES ukt(id_ukt) ON DELETE NO ACTION,
    FOREIGN KEY (pkl_id) REFERENCES pkl(id_pkl) ON DELETE NO ACTION,
    FOREIGN KEY (toeic_id) REFERENCES toeic(id_toeic) ON DELETE NO ACTION,
    FOREIGN KEY (skkm_id) REFERENCES skkm(id_skkm) ON DELETE NO ACTION,
    FOREIGN KEY (publikasi_id) REFERENCES publikasi(id_publikasi) ON DELETE NO ACTION
);


-- Membuat tabel verifikasi admin
CREATE TABLE dbo.verifikasi_admin (
    id_verifikasi_admin INT IDENTITY(1,1) PRIMARY KEY,
    admin_id INT NOT NULL,
    mahasiswa_id INT NOT NULL,
    id_tanggungan INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'diajukan ke admin',
    tanggal_verifikasi DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE NO ACTION,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE NO ACTION,
    FOREIGN KEY (id_tanggungan) REFERENCES tanggungan(id_tanggungan) ON DELETE NO ACTION
);


-- Membuat tabel verifikasi kps
CREATE TABLE dbo.verifikasi_kps (
    id_verifikasi_kps INT IDENTITY(1,1) PRIMARY KEY,
    kps_id INT NOT NULL,
    mahasiswa_id INT NOT NULL,
    tanggungan_id INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'diajukan ke kps',
    tanggal_verifikasi DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (kps_id) REFERENCES kps(id) ON DELETE NO ACTION,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE NO ACTION,
    FOREIGN KEY (tanggungan_id) REFERENCES tanggungan(id_tanggungan) ON DELETE NO ACTION
);


-- Membuat tabel surat bebas tanggungan
CREATE TABLE dbo.surat_bebas_tanggungan (
    id_surat INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    tanggungan_id INT NOT NULL,
    tanggal_surat DATETIME DEFAULT GETDATE(),
    status_surat VARCHAR(50) DEFAULT 'belum diterbitkan',
    file_surat VARBINARY(MAX),
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE NO ACTION,
    FOREIGN KEY (tanggungan_id) REFERENCES tanggungan(id_tanggungan) ON DELETE NO ACTION
);

-- Masukkan data ke tabel user
INSERT INTO [user] (username, password, level) VALUES
('admin01', 'adminpass01', 'admin'),
('kps01', 'kpspass01', 'kps'),
('mahasiswa001', 'mahasiswapass001', 'mahasiswa'),
('mahasiswa002', 'mahasiswapass002', 'mahasiswa'),
('mahasiswa003', 'mahasiswapass003', 'mahasiswa'),
('mahasiswa004', 'mahasiswapass004', 'mahasiswa');


-- Masukkan data ke tabel mahasiswa
INSERT INTO dbo.mahasiswa (nim, nama, email, user_id) VALUES
('19061001', 'Ahmad Zaki', 'ahmad.zaki@gmail.com', 3),
('19061002', 'Diana Putri', 'diana.putri@gmail.com', 4),
('19061003', 'Budi Santoso', 'budi.santoso@gmail.com', 5),
('19061004', 'Bayu Wijaya', 'bayu.wijaya@gmail.com', 6);


-- Masukkan data ke tabel admin
INSERT INTO dbo.admin (username, email, user_id) VALUES
('admin01', 'adminjti@gmail.com', 1);


-- Masukkan data ke tabel kps
INSERT INTO dbo.kps (username, email, user_id) VALUES
('kps01', 'kpssib@gmail.com', 2);


-- Masukkan data ke tabel absensi
INSERT INTO dbo.absensi (mahasiswa_id, jumlah_alpha, semester, status_validasi) VALUES
(3, 14, '2024/2025', 'belum mengajukan'),
(4, 18, '2024/2025', 'belum mengajukan');


-- Masukkan data ke tabel ukt
INSERT INTO dbo.ukt (mahasiswa_id, status_validasi) VALUES
(3, 'belum mengajukan'),
(4, 'diajukan ke admin');


-- Masukkan data ke tabel pkl
INSERT INTO dbo.pkl (mahasiswa_id, tempat_pkl, laporan_pkl, status_validasi) VALUES
(3, 'TechInnova Solutions', NULL, 'belum mengajukan'),
(4, 'Indodax', NULL, 'belum mengajukan');


-- Masukkan data ke tabel toeic
INSERT INTO dbo.toeic (mahasiswa_id, hasil_toeic, status_validasi) VALUES
(1, NULL, 'belum mengajukan'),
(2, NULL, 'belum mengajukan');


-- Masukkan data ke tabel skkm
INSERT INTO dbo.skkm (mahasiswa_id, point_skkm, status_validasi) VALUES
(3, 10, 'belum mengajukan'),
(4, 15, 'diajukan ke admin');


-- Masukkan data ke tabel publikasi
INSERT INTO dbo.publikasi (mahasiswa_id, judul_skripsi, file_publikasi, file_program, status_validasi) VALUES
(1, 'Pengembangan Aplikasi Mobile dengan Teknologi Augmented Reality (AR) untuk Meningkatkan Pengalaman Pengguna di Sektor Pendidikan', NULL, NULL, 'belum mengajukan'),
(2, 'Implementasi Sistem Keamanan Berbasis Blockchain pada Aplikasi E-Commerce untuk Meningkatkan Keamanan Transaksi', NULL, NULL, 'belum mengajukan');


-- Masukkan data ke tabel tanggungan
INSERT INTO dbo.tanggungan (mahasiswa_id, jenis_tanggungan, absensi_id, ukt_id, pkl_id, toeic_id, skkm_id, publikasi_id, status_validasi, tanggal_ajukan) VALUES
(3, 'absensi', 1, 1, NULL, NULL, 1, NULL, 'belum mengajukan', '2024-12-03 13:31:21'),
(4, 'ukt', NULL, 2, NULL, NULL, 2, NULL, 'diajukan ke admin', '2024-12-03 13:31:21');



-- Masukkan data ke tabel verifikasi_admin
INSERT INTO dbo.verifikasi_admin (admin_id, mahasiswa_id,x status_validasi, tanggal_verifikasi) VALUES
(1, 3, 'belum diverifikasi', '2024-12-03 13:35:27'),
(1, 4, 'diajukan ke admin', '2024-12-03 13:35:27');



-- Masukkan data ke tabel verifikasi_kps
INSERT INTO dbo.verifikasi_kps (kps_id, mahasiswa_id, status_validasi, tanggal_verifikasi) VALUES
(1, 3, 'belum diverifikasi', '2024-12-03 13:35:27'),
(1, 4, 'diajukan ke kps', '2024-12-03 13:35:27');


-- Masukkan data ke tabel surat_bebas_tanggungan
INSERT INTO dbo.surat_bebas_tanggungan (mahasiswa_id, tanggungan_id, tanggal_surat, status_surat, file_surat) VALUES
(1, 3, '2024-12-03 13:35:27', 'belum diterbitkan', NULL),
(1, 4, '2024-12-03 13:35:27', 'belum diterbitkan', NULL);

