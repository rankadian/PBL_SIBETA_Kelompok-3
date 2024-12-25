-- Membuat Database
CREATE DATABASE SibetaWeb6;
GO

USE SibetaWeb6;
GO

-- Tabel User
CREATE TABLE dbo.TB_USER (
    ID INT PRIMARY KEY IDENTITY(1,1),
    username NVARCHAR(50) NOT NULL UNIQUE,
    password NVARCHAR(255) NOT NULL,
    level NVARCHAR(50) CHECK (level IN ('Mahasiswa', 'Admin')) NOT NULL
);

-- Tabel Mahasiswa
CREATE TABLE dbo.TB_Mahasiswa (
    NIM INT PRIMARY KEY IDENTITY(1,1),
    Nama NVARCHAR(100) NOT NULL,
    ProgramStudi NVARCHAR(100) NOT NULL,
    ID INT
);

-- Tabel Admin
CREATE TABLE dbo.TB_Admin (
    IDAdmin INT PRIMARY KEY IDENTITY(1,1),
    NamaAdmin NVARCHAR(100) NOT NULL,
    Jabatan NVARCHAR(50) NOT NULL,
    ID INT
);

-- Tabel Upload
CREATE TABLE dbo.TB_Upload (
    IDUpload INT PRIMARY KEY IDENTITY(1,1),
	Nama_file NVARCHAR(50),
    Jenis_Surat NVARCHAR(50) CHECK (Jenis_Surat IN ('ukt', 'skkm','Toeic','Publikasi','Skla','kompensasi')) NOT NULL,
    TanggalDibuat DATE NOT NULL,
	NIM INT NOT NULL
);

-- Tabel Verifikasi
CREATE TABLE dbo.TB_Verifikasi (
    IDVerifikasi INT PRIMARY KEY IDENTITY(1,1),
    IDUpload INT NOT NULL,
    IDAdmin INT NOT NULL,
    TanggalVerifikasi DATE NOT NULL,
    StatusVerifikasi BIT NOT NULL, -- Status bertipe BIT
    Catatan NVARCHAR(MAX)
);

-- Relasi Mahasiswa dengan User
ALTER TABLE dbo.TB_Mahasiswa
ADD CONSTRAINT FK_Mahasiswa_User FOREIGN KEY (ID) REFERENCES dbo.TB_USER(ID);

-- Relasi Admin dengan User
ALTER TABLE dbo.TB_Admin
ADD CONSTRAINT FK_Admin_User FOREIGN KEY (ID) REFERENCES dbo.TB_USER(ID);

-- Relasi Upload dengan Mahasiswa
ALTER TABLE dbo.TB_Upload
ADD CONSTRAINT FK_Upload_Mahasiswa FOREIGN KEY (NIM) REFERENCES dbo.TB_Mahasiswa(NIM);

-- Relasi Verifikasi dengan Upload
ALTER TABLE dbo.TB_Verifikasi
ADD CONSTRAINT FK_Verifikasi_Upload FOREIGN KEY (IDUpload) REFERENCES dbo.TB_Upload(IDUpload);

-- Relasi Verifikasi dengan Admin
ALTER TABLE dbo.TB_Verifikasi
ADD CONSTRAINT FK_Verifikasi_Admin FOREIGN KEY (IDAdmin) REFERENCES dbo.TB_Admin(IDAdmin);
