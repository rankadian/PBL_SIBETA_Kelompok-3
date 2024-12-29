-- Membuat Database
CREATE DATABASE SibetaWeb2;
GO

USE SibetaWeb2;
GO

-- Tabel User (harus dibuat pertama)
CREATE TABLE dbo.TB_USER (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level VARCHAR(50) CHECK (level IN ('mahasiswa', 'admin')) NOT NULL
);

-- Tabel Mahasiswa
CREATE TABLE dbo.TB_Mahasiswa (
    NIM CHAR(10) PRIMARY KEY,
    Nama NVARCHAR(100) NOT NULL,
    ProgramStudi NVARCHAR(100) NOT NULL,
    StatusTanggungan NVARCHAR(50) NOT NULL,
    IDUser INT
);

-- Tabel Surat
CREATE TABLE dbo.TB_Surat (
    IDSurat INT PRIMARY KEY IDENTITY(1,1),
    NamaSurat NVARCHAR(100) NOT NULL,
    JenisSurat NVARCHAR(50) NOT NULL,
    TanggalDibuat DATE NOT NULL
);

-- Tabel Admin
CREATE TABLE dbo.TB_Admin (
    IDAdmin INT PRIMARY KEY IDENTITY(1,1),
    NamaAdmin NVARCHAR(100) NOT NULL,
    Jabatan NVARCHAR(50) NOT NULL,
    IDUser INT
);

-- Tabel Pengajuan
CREATE TABLE dbo.TB_Pengajuan (
    IDPengajuan INT PRIMARY KEY IDENTITY(1,1),
    NIM CHAR(10) NOT NULL,
    IDSurat INT NOT NULL,
    TanggalPengajuan DATE NOT NULL,
    StatusPengajuan NVARCHAR(50) CHECK (StatusPengajuan IN ('Diajukan', 'Disetujui', 'Tidak Disetujui')) NOT NULL,
    CatatanAdmin NVARCHAR(MAX)
);

-- Tabel Log Verifikasi
CREATE TABLE dbo.TB_Verifikasi (
    IDVerifikasi INT PRIMARY KEY IDENTITY(1,1),
    IDPengajuan INT NOT NULL,
    IDAdmin INT NOT NULL,
    TanggalVerifikasi DATE NOT NULL,
    StatusVerifikasi NVARCHAR(50) CHECK (StatusVerifikasi IN ('Disetujui', 'Tidak Disetujui')) NOT NULL,
    Catatan NVARCHAR(MAX)
);

-- Masukkan data ke tabel TB_USER
INSERT INTO dbo.TB_USER (username, password, level) VALUES
('admin01', '$2y$10$8I.NpkUK.jfchLfydbQLweBYvuObNj3Oie3IXIweld4jfFunNcVwq', 'admin'), --adminpass01
('mahasiswa001', '$2y$10$dFSmGNu6nanQ2fkQnIzhTemynRMCbE.SCBQuaWSzRfKz.O8b9SGCm', 'mahasiswa'), --mahasiswapass001
('mahasiswa002', '$2y$10$EIg.hARNDY1AkPcJC7TgLuay4QNTNDHATJjiFfc.3k3QYGOHlR1Eu', 'mahasiswa'), --mahasiswapass002
('mahasiswa003', '$2y$10$arb27gHYiqVXI7b9XaBgruFkdjA8G9skXDZpHvsVtARR/76ljFVNm', 'mahasiswa'), --mahasiswapass003
('mahasiswa004', '$2y$10$k0fZSWnAuYfYj74OulrI4.dUXOlRahCOcVb/2UBZuLbxmkd/NUUTq', 'mahasiswa'); --mahasiswapass004

/*
-- Menambahkan FOREIGN KEY Constraints Secara Terpisah

-- FK pada TB_Mahasiswa
ALTER TABLE dbo.TB_Mahasiswa
ADD CONSTRAINT FK_TB_Mahasiswa_User FOREIGN KEY (IDUser) REFERENCES dbo.TB_USER(id);

-- FK pada TB_Admin
ALTER TABLE dbo.TB_Admin
ADD CONSTRAINT FK_TB_Admin_User FOREIGN KEY (IDUser) REFERENCES dbo.TB_USER(id);

-- FK pada TB_Pengajuan (NIM)
ALTER TABLE dbo.TB_Pengajuan
ADD CONSTRAINT FK_TB_Pengajuan_Mahasiswa FOREIGN KEY (NIM) REFERENCES dbo.TB_Mahasiswa(NIM);

-- FK pada TB_Pengajuan (IDSurat)
ALTER TABLE dbo.TB_Pengajuan
ADD CONSTRAINT FK_TB_Pengajuan_Surat FOREIGN KEY (IDSurat) REFERENCES dbo.TB_Surat(IDSurat);

-- FK pada TB_Verifikasi (IDPengajuan)
ALTER TABLE dbo.TB_Verifikasi
ADD CONSTRAINT FK_TB_Verifikasi_Pengajuan FOREIGN KEY (IDPengajuan) REFERENCES dbo.TB_Pengajuan(IDPengajuan);

-- FK pada TB_Verifikasi (IDAdmin)
ALTER TABLE dbo.TB_Verifikasi
ADD CONSTRAINT FK_TB_Verifikasi_Admin FOREIGN KEY (IDAdmin) REFERENCES dbo.TB_Admin(IDAdmin);
*/