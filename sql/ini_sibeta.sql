-- Drop existing tables (if exists)
IF OBJECT_ID('dbo.TB_ABSENSI', 'U') IS NOT NULL DROP TABLE dbo.TB_ABSENSI;
IF OBJECT_ID('dbo.TB_ADMIN', 'U') IS NOT NULL DROP TABLE dbo.TB_ADMIN;
IF OBJECT_ID('dbo.TB_KPS', 'U') IS NOT NULL DROP TABLE dbo.TB_KPS;
IF OBJECT_ID('dbo.TB_MAHASISWA', 'U') IS NOT NULL DROP TABLE dbo.TB_MAHASISWA;
IF OBJECT_ID('dbo.TB_PKL', 'U') IS NOT NULL DROP TABLE dbo.TB_PKL;
IF OBJECT_ID('dbo.TB_PUBLIKASI', 'U') IS NOT NULL DROP TABLE dbo.TB_PUBLIKASI;
IF OBJECT_ID('dbo.TB_SKKM', 'U') IS NOT NULL DROP TABLE dbo.TB_SKKM;
IF OBJECT_ID('dbo.TB_SURAT_BEBAS_TANGGUNGAN', 'U') IS NOT NULL DROP TABLE dbo.TB_SURAT_BEBAS_TANGGUNGAN;
IF OBJECT_ID('dbo.TB_TANGGUNGAN', 'U') IS NOT NULL DROP TABLE dbo.TB_TANGGUNGAN;
IF OBJECT_ID('dbo.TB_TOEIC', 'U') IS NOT NULL DROP TABLE dbo.TB_TOEIC;
IF OBJECT_ID('dbo.TB_UKT', 'U') IS NOT NULL DROP TABLE dbo.TB_UKT;
IF OBJECT_ID('dbo.TB_USER', 'U') IS NOT NULL DROP TABLE dbo.TB_USER;
IF OBJECT_ID('dbo.TB_VERIFIKASI_ADMIN', 'U') IS NOT NULL DROP TABLE dbo.TB_VERIFIKASI_ADMIN;
IF OBJECT_ID('dbo.TB_VERIFIKASI_KPS', 'U') IS NOT NULL DROP TABLE dbo.TB_VERIFIKASI_KPS;
IF OBJECT_ID('dbo.TB_NOTIFIKASI', 'U') IS NOT NULL DROP TABLE dbo.TB_NOTIFIKASI;


-- Membuat database
CREATE DATABASE SIBETA_NEW;
GO

USE SIBETA_NEW;
GO


-- Membuat tabel TB_USER
CREATE TABLE dbo.TB_USER (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level VARCHAR(50) CHECK (level IN ('mahasiswa', 'admin', 'kps')) NOT NULL
);


-- Membuat tabel TB_MAHASISWA
CREATE TABLE dbo.TB_MAHASISWA (
    nim VARCHAR(15) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    alamat VARCHAR(255),
    nomor_telepon VARCHAR(15),
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES dbo.TB_USER(id)
);


-- Membuat tabel TB_ADMIN
CREATE TABLE dbo.TB_ADMIN (
    email VARCHAR(100) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES dbo.TB_USER(id)
);


-- Membuat tabel TB_KPS
CREATE TABLE dbo.TB_KPS (
    email VARCHAR(100) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES dbo.TB_USER(id)
);


-- Membuat tabel TB_ABSENSI
CREATE TABLE dbo.TB_ABSENSI (
    id_absensi INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    jumlah_alpha INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE CASCADE
);


-- Membuat tabel TB_UKT
CREATE TABLE dbo.TB_UKT (
    id_ukt INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE CASCADE
);


-- Membuat tabel TB_PKL
CREATE TABLE dbo.TB_PKL (
    id_pkl INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    tempat_pkl VARCHAR(255) NOT NULL,
    laporan_pkl VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE CASCADE
);


-- Membuat tabel TB_TOEIC
CREATE TABLE dbo.TB_TOEIC (
    id_toeic INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    hasil_toeic VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE CASCADE
);


-- Membuat tabel TB_SKKM
CREATE TABLE dbo.TB_SKKM (
    id_skkm INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    point_skkm INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE CASCADE
);


-- Membuat tabel TB_PUBLIKASI
CREATE TABLE dbo.TB_PUBLIKASI (
    id_publikasi INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    judul_skripsi VARCHAR(255) NOT NULL,
    file_publikasi VARBINARY(MAX),
    file_program VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE CASCADE
);


-- Membuat tabel TB_TANGGUNGAN
CREATE TABLE dbo.TB_TANGGUNGAN (
    id_tanggungan INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    jenis_tanggungan VARCHAR(50) CHECK (jenis_tanggungan IN ('absensi', 'ukt', 'pkl', 'toeic', 'skkm', 'publikasi')) NOT NULL,
    absensi_id INT,
    ukt_id INT,
    pkl_id INT,
    toeic_id INT,
    skkm_id INT,
    publikasi_id INT,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    tanggal_ajukan DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE CASCADE,
    FOREIGN KEY (absensi_id) REFERENCES dbo.TB_ABSENSI(id_absensi) ON DELETE NO ACTION,
    FOREIGN KEY (ukt_id) REFERENCES dbo.TB_UKT(id_ukt) ON DELETE NO ACTION,
    FOREIGN KEY (pkl_id) REFERENCES dbo.TB_PKL(id_pkl) ON DELETE NO ACTION,
    FOREIGN KEY (toeic_id) REFERENCES dbo.TB_TOEIC(id_toeic) ON DELETE NO ACTION,
    FOREIGN KEY (skkm_id) REFERENCES dbo.TB_SKKM(id_skkm) ON DELETE NO ACTION,
    FOREIGN KEY (publikasi_id) REFERENCES dbo.TB_PUBLIKASI(id_publikasi) ON DELETE NO ACTION
);


-- Membuat tabel TB_VERIFIKASI_ADMIN
CREATE TABLE dbo.TB_VERIFIKASI_ADMIN (
    id_verifikasi_admin INT IDENTITY(1,1) PRIMARY KEY,
    admin_email VARCHAR(100) NOT NULL,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    id_tanggungan INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'diajukan ke admin',
    tanggal_verifikasi DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (admin_email) REFERENCES dbo.TB_ADMIN(email) ON DELETE NO ACTION,
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE NO ACTION,
    FOREIGN KEY (id_tanggungan) REFERENCES dbo.TB_TANGGUNGAN(id_tanggungan) ON DELETE NO ACTION
);


-- Membuat tabel TB_VERIFIKASI_KPS
CREATE TABLE dbo.TB_VERIFIKASI_KPS (
    id_verifikasi_kps INT IDENTITY(1,1) PRIMARY KEY,
    kps_email VARCHAR(100) NOT NULL,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    id_tanggungan INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'diajukan ke kps',
    tanggal_verifikasi DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (kps_email) REFERENCES dbo.TB_KPS(email) ON DELETE NO ACTION,
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE NO ACTION,
    FOREIGN KEY (id_tanggungan) REFERENCES dbo.TB_TANGGUNGAN(id_tanggungan) ON DELETE NO ACTION
);


-- Membuat tabel TB_SURAT_BEBAS_TANGGUNGAN
CREATE TABLE dbo.TB_SURAT_BEBAS_TANGGUNGAN (
    id_surat INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_nim VARCHAR(15) NOT NULL,
    tanggungan_id INT NOT NULL,
    tanggal_surat DATETIME DEFAULT GETDATE(),
    status_surat VARCHAR(50) DEFAULT 'belum diterbitkan',
    file_surat VARBINARY(MAX),
    FOREIGN KEY (mahasiswa_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE NO ACTION,
    FOREIGN KEY (tanggungan_id) REFERENCES dbo.TB_TANGGUNGAN(id_tanggungan) ON DELETE NO ACTION
);


-- Membuat tabel TB_NOTIFIKASI
CREATE TABLE dbo.TB_NOTIFIKASI (
    id_notifikasi INT IDENTITY(1,1) PRIMARY KEY,
    pengirim_email VARCHAR(100) NOT NULL,
    penerima_nim VARCHAR(15) NOT NULL,
    id_tanggungan INT NOT NULL,
    pesan VARCHAR(255) NOT NULL,
    status_notifikasi VARCHAR(50) CHECK (status_notifikasi IN ('belum dibaca', 'sudah dibaca')) DEFAULT 'belum dibaca',
    tanggal_notifikasi DATETIME DEFAULT GETDATE(),
    jenis_pengirim VARCHAR(10) CHECK (jenis_pengirim IN ('admin', 'kps')) NOT NULL,
    status_tanggungan VARCHAR(50) DEFAULT 'belum diverifikasi',
    FOREIGN KEY (penerima_nim) REFERENCES dbo.TB_MAHASISWA(nim) ON DELETE NO ACTION,  -- Ubah di sini
    FOREIGN KEY (id_tanggungan) REFERENCES dbo.TB_TANGGUNGAN(id_tanggungan) ON DELETE NO ACTION  -- Ubah di sini
);


-- Masukkan data ke tabel TB_USER
INSERT INTO dbo.TB_USER (username, password, level) VALUES
('admin01', 'adminpass01', 'admin'),
('kps01', 'kpspass01', 'kps'),
('mahasiswa001', 'mahasiswapass001', 'mahasiswa'),
('mahasiswa002', 'mahasiswapass002', 'mahasiswa'),
('mahasiswa003', 'mahasiswapass003', 'mahasiswa'),
('mahasiswa004', 'mahasiswapass004', 'mahasiswa');


-- Masukkan data ke tabel TB_MAHASISWA
INSERT INTO dbo.TB_MAHASISWA (nim, nama, email, alamat, nomor_telepon, user_id) VALUES
('19061001', 'Ahmad Zaki', 'ahmad.zaki@gmail.com', 'Jl. Merdeka No. 1, Jakarta', '08123456789', 3),
('19061002', 'Budi Setiawan', 'budi.setiawan@gmail.com', 'Jl. Raya No. 2, Bandung', '08234567890', 4),
('19061003', 'Charlie Ivan', 'charlie.ivan@gmail.com', 'Jl. Sudirman No. 3, Surabaya', '08345678901', 5),
('19061004', 'Diana Pratiwi', 'diana.pratiwi@gmail.com', 'Jl. Thamrin No. 4, Yogyakarta', '08456789012', 6);


-- Masukkan data ke tabel TB_ADMIN
INSERT INTO dbo.TB_ADMIN (email, username, user_id) VALUES
('adminjti@gmail.com', 'admin01', 1);


-- Masukkan data ke tabel TB_KPS
INSERT INTO dbo.TB_KPS (email, username, user_id) VALUES
('kpssib@gmail.com', 'kps01', 2);


-- Masukkan data ke tabel TB_ABSENSI
INSERT INTO dbo.TB_ABSENSI (mahasiswa_nim, jumlah_alpha, semester, status_validasi) VALUES
('19061003', 14, '2024/2025', 'belum mengajukan'),
('19061004', 18, '2024/2025', 'belum mengajukan');


-- Masukkan data ke tabel TB_UKT
INSERT INTO dbo.TB_UKT (mahasiswa_nim, status_validasi) VALUES
('19061003', 'belum mengajukan'),
('19061004', 'diajukan ke admin');


-- Masukkan data ke tabel TB_PKL
INSERT INTO dbo.TB_PKL (mahasiswa_nim, tempat_pkl, laporan_pkl, status_validasi) VALUES
('19061003', 'TechInnova Solutions', NULL, 'belum mengajukan'),
('19061004', 'Indodax', NULL, 'belum mengajukan');


-- Masukkan data ke tabel TB_TOEIC
INSERT INTO dbo.TB_TOEIC (mahasiswa_nim, hasil_toeic, status_validasi) VALUES
('19061001', NULL, 'belum mengajukan'),
('19061002', NULL, 'belum mengajukan');


-- Masukkan data ke tabel TB_SKKM
INSERT INTO dbo.TB_SKKM (mahasiswa_nim, point_skkm, status_validasi) VALUES
('19061003', 10, 'belum mengajukan'),
('19061004', 15, 'diajukan ke admin');


-- Masukkan data ke tabel TB_PUBLIKASI
INSERT INTO dbo.TB_PUBLIKASI (mahasiswa_nim, judul_skripsi, file_publikasi, file_program, status_validasi) VALUES
('19061001', 'Pengembangan Aplikasi Mobile dengan Teknologi Augmented Reality (AR)', NULL, NULL, 'belum mengajukan'),
('19061002', 'Implementasi Sistem Keamanan Berbasis Blockchain', NULL, NULL, 'belum mengajukan');


-- Masukkan data ke tabel TB_TANGGUNGAN
INSERT INTO dbo.TB_TANGGUNGAN (mahasiswa_nim, jenis_tanggungan, absensi_id, ukt_id, pkl_id, toeic_id, skkm_id, publikasi_id, status_validasi, tanggal_ajukan) VALUES
('19061003', 'absensi', 1, 1, NULL, NULL, 1, NULL, 'belum mengajukan', '2024-12-03 13:31:21'),
('19061004', 'ukt', NULL, 2, NULL, NULL, 2, NULL, 'diajukan ke admin', '2024-12-03 13:31:21');


-- Masukkan data ke tabel TB_VERIFIKASI_ADMIN
INSERT INTO dbo.TB_VERIFIKASI_ADMIN (admin_email, mahasiswa_nim, id_tanggungan, status_validasi, tanggal_verifikasi) VALUES
('adminjti@gmail.com', '19061003', 1, 'belum diverifikasi', '2024-12-03 13:35:27'),
('adminjti@gmail.com', '19061004', 2, 'diajukan ke admin', '2024-12-03 13:35:27');


-- Masukkan data ke tabel TB_VERIFIKASI_KPS
INSERT INTO dbo.TB_VERIFIKASI_KPS (kps_email, mahasiswa_nim, id_tanggungan, status_validasi, tanggal_verifikasi) VALUES
('kpssib@gmail.com', '19061003', 1, 'belum diverifikasi', '2024-12-03 13:35:27'),
('kpssib@gmail.com', '19061004', 2, 'diajukan ke kps', '2024-12-03 13:35:27');


-- Masukkan data ke tabel TB_SURAT_BEBAS_TANGGUNGAN
INSERT INTO dbo.TB_SURAT_BEBAS_TANGGUNGAN (mahasiswa_nim, tanggungan_id, tanggal_surat, status_surat, file_surat) VALUES
('19061003', 1, '2024-12-03 13:35:27', 'belum diterbitkan', NULL),
('19061004', 2, '2024-12-03 13:35:27', 'belum diterbitkan', NULL);


-- Menambahkan beberapa contoh notifikasi
-- Notifikasi dari admin (misalnya ada ketidaksesuaian file)
INSERT INTO dbo.TB_NOTIFIKASI (pengirim_email, penerima_nim, id_tanggungan, pesan, jenis_pengirim, status_tanggungan) 
VALUES 
('adminjti@gmail.com', '19061003', 1, 'Tanggungan absensi Anda belum lengkap. Silakan periksa kembali dokumen yang diunggah.', 'admin', 'permasalahan'),
('adminjti@gmail.com', '19061004', 2, 'Tanggungan UKT Anda mengalami ketidaksesuaian data. Silakan hubungi admin.', 'admin', 'permasalahan'),
('adminjti@gmail.com', '19061001', 1, 'Tanggungan PKL Anda berhasil diunggah dan tidak ada masalah.', 'admin', 'diterima');


-- Notifikasi dari KPS setelah verifikasi admin selesai
INSERT INTO dbo.TB_NOTIFIKASI (pengirim_email, penerima_nim, id_tanggungan, pesan, jenis_pengirim, status_tanggungan) VALUES
('kpssib@gmail.com', '19061003', 1, 'Tanggungan absensi Anda berhasil diverifikasi oleh admin dan diteruskan ke KPS.', 'kps', 'diterima'),
('kpssib@gmail.com', '19061004', 2, 'Tanggungan UKT Anda telah berhasil diverifikasi dan disetujui oleh KPS.', 'kps', 'diterima');


-- Notifikasi sukses setelah KPS memverifikasi
INSERT INTO dbo.TB_NOTIFIKASI (pengirim_email, penerima_nim, id_tanggungan, pesan, jenis_pengirim, status_tanggungan) VALUES
('kpssib@gmail.com', '19061003', 1, 'Tanggungan absensi Anda berhasil diverifikasi oleh KPS.', 'kps', 'diterima'),
('kpssib@gmail.com', '19061004', 2, 'Tanggungan UKT Anda berhasil diverifikasi dan diterima oleh KPS.', 'kps', 'diterima');