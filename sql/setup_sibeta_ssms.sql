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
CREATE TABLE mahasiswa (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nim VARCHAR(15) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES [user](id)
);


-- Membuat tabel admin
CREATE TABLE admin (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES [user](id)
);


-- Membuat tabel kps
CREATE TABLE kps (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES [user](id)
);


-- Membuat tabel absensi
CREATE TABLE absensi (
    id_absensi INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    jumlah_alpha INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel ukt
CREATE TABLE ukt (
    id_ukt INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel pkl
CREATE TABLE pkl (
    id_pkl INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    tempat_pkl VARCHAR(255) NOT NULL,
    laporan_pkl VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel toeic
CREATE TABLE toeic (
    id_toeic INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    hasil_toeic VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel skkm
CREATE TABLE skkm (
    id_skkm INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    point_skkm INT NOT NULL,
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel publikasi
CREATE TABLE publikasi (
    id_publikasi INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    judul_skripsi VARCHAR(255) NOT NULL,
    file_publikasi VARBINARY(MAX),
    file_program VARBINARY(MAX),
    status_validasi VARCHAR(50) DEFAULT 'belum mengajukan',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);


-- Membuat tabel tanggungan
CREATE TABLE tanggungan (
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
CREATE TABLE verifikasi_admin (
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
CREATE TABLE verifikasi_kps (
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
CREATE TABLE surat_bebas_tanggungan (
    id_surat INT IDENTITY(1,1) PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    tanggungan_id INT NOT NULL,
    tanggal_surat DATETIME DEFAULT GETDATE(),
    status_surat VARCHAR(50) DEFAULT 'belum diterbitkan',
    file_surat VARBINARY(MAX),
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE NO ACTION,
    FOREIGN KEY (tanggungan_id) REFERENCES tanggungan(id_tanggungan) ON DELETE NO ACTION
);