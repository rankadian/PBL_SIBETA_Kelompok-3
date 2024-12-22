-- Create Table TB_Mahasiswa
CREATE TABLE dbo.TB_Mahasiswa (
    NIM VARCHAR(20) PRIMARY KEY,  -- NIM sebagai primary key
    Nama VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    PasswordHash VARCHAR(255) NOT NULL
);

-- Create Table TB_Surat
CREATE TABLE dbo.TB_Surat (
    SuratID INT IDENTITY(1,1) PRIMARY KEY,
    NamaSurat VARCHAR(100) NOT NULL,
    FilePath VARCHAR(255) NOT NULL,
    TanggalUpload DATETIME NOT NULL
);

-- Create Table TB_Admin
CREATE TABLE dbo.TB_Admin (
    AdminID INT IDENTITY(1,1) PRIMARY KEY,
    Nama VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    PasswordHash VARCHAR(255) NOT NULL
);

-- Create Table TB_PengajuanSurat
CREATE TABLE dbo.TB_PengajuanSurat (
    PengajuanID INT IDENTITY(1,1) PRIMARY KEY,
    NIM VARCHAR(20) NOT NULL,  -- NIM sebagai foreign key
    SuratID INT NOT NULL,
    StatusPengajuan VARCHAR(20) NOT NULL,
    TanggalPengajuan DATETIME NOT NULL,
    FilePath VARCHAR(255),
    TanggalVerifikasi DATETIME,
    AdminID INT,
    CatatanVerifikasi VARCHAR(255)
);

-- Create Table TB_Verifikasi
CREATE TABLE dbo.TB_Verifikasi (
    VerifikasiID INT IDENTITY(1,1) PRIMARY KEY,
    PengajuanID INT NOT NULL,
    AdminID INT NOT NULL,
    StatusVerifikasi VARCHAR(20) NOT NULL,
    Catatan VARCHAR(255),
    TanggalVerifikasi DATETIME NOT NULL
);

-- Create Table TB_USER
CREATE TABLE dbo.TB_USER (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level VARCHAR(50) CHECK (level IN ('mahasiswa', 'admin')) NOT NULL,
    NIM VARCHAR(20) NULL,  -- Foreign key untuk mahasiswa
    AdminID INT NULL,      -- Foreign key untuk admin
    FOREIGN KEY (NIM) REFERENCES dbo.TB_Mahasiswa(NIM),  -- Relasi dengan TB_Mahasiswa
    FOREIGN KEY (AdminID) REFERENCES dbo.TB_Admin(AdminID)  -- Relasi dengan TB_Admin
);

-- Masukkan data ke tabel TB_USER
INSERT INTO dbo.TB_USER (username, password, level) VALUES
('admin01', '$2y$10$8I.NpkUK.jfchLfydbQLweBYvuObNj3Oie3IXIweld4jfFunNcVwq', 'admin'), --adminpass01
('mahasiswa001', '$2y$10$dFSmGNu6nanQ2fkQnIzhTemynRMCbE.SCBQuaWSzRfKz.O8b9SGCm', 'mahasiswa'), --mahasiswapass001
('mahasiswa002', '$2y$10$EIg.hARNDY1AkPcJC7TgLuay4QNTNDHATJjiFfc.3k3QYGOHlR1Eu', 'mahasiswa'), --mahasiswapass002
('mahasiswa003', '$2y$10$arb27gHYiqVXI7b9XaBgruFkdjA8G9skXDZpHvsVtARR/76ljFVNm', 'mahasiswa'), --mahasiswapass003
('mahasiswa004', '$2y$10$k0fZSWnAuYfYj74OulrI4.dUXOlRahCOcVb/2UBZuLbxmkd/NUUTq', 'mahasiswa'); --mahasiswapass004

-- Define Foreign Keys for TB_PengajuanSurat (perintah satu per satu)
ALTER TABLE dbo.TB_PengajuanSurat
    ADD CONSTRAINT FK_PengajuanSurat_Mahasiswa FOREIGN KEY (NIM) REFERENCES dbo.TB_Mahasiswa(NIM);

ALTER TABLE dbo.TB_PengajuanSurat
    ADD CONSTRAINT FK_PengajuanSurat_Surat FOREIGN KEY (SuratID) REFERENCES dbo.TB_Surat(SuratID);

ALTER TABLE dbo.TB_PengajuanSurat
    ADD CONSTRAINT FK_PengajuanSurat_Admin FOREIGN KEY (AdminID) REFERENCES dbo.TB_Admin(AdminID);

-- Foreign Keys for TB_Verifikasi (perintah satu per satu)
ALTER TABLE dbo.TB_Verifikasi
    ADD CONSTRAINT FK_Verifikasi_Pengajuan FOREIGN KEY (PengajuanID) REFERENCES dbo.TB_PengajuanSurat(PengajuanID);

ALTER TABLE dbo.TB_Verifikasi
    ADD CONSTRAINT FK_Verifikasi_Admin FOREIGN KEY (AdminID) REFERENCES dbo.TB_Admin(AdminID);
