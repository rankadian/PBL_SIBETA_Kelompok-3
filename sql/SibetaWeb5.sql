-- Membuat Database
CREATE DATABASE SibetaWeb5;
GO

USE SibetaWeb5;
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
    NIM INT PRIMARY KEY IDENTITY(1,1),
    Nama NVARCHAR(100) NOT NULL,
    ProgramStudi NVARCHAR(100) NOT NULL,
    IDUser INT, --fk
    FOREIGN KEY (IDUser) REFERENCES dbo.TB_USER(IDUser) --relasi
);

-- Tabel Admin
CREATE TABLE dbo.TB_Admin (
    IDAdmin INT PRIMARY KEY IDENTITY(1,1),
    NamaAdmin NVARCHAR(100) NOT NULL,
    Jabatan NVARCHAR(50) NOT NULL,
    IDUser INT, --fk
    FOREIGN KEY (IDUser) REFERENCES dbo.TB_USER(IDUser) --relasi
);

-- Tabel Pengajuan
CREATE TABLE dbo.TB_Pengajuan (
    IDPengajuan INT PRIMARY KEY IDENTITY(1,1),
    TanggalPengajuan DATE NOT NULL,
    StatusPengajuan NVARCHAR(50) CHECK (StatusPengajuan IN ('Diajukan', 'Disetujui', 'Tidak Disetujui')) NOT NULL,
    CatatanAdmin NVARCHAR(MAX),
	NIM INT, --fk
    FOREIGN KEY (NIM) REFERENCES dbo.TB_Mahasiswa(NIM) --relasi
);

-- Tabel Surat 
CREATE TABLE dbo.TB_Surat (
    IDSurat INT PRIMARY KEY IDENTITY(1,1),
    IDPengajuan INT NOT NULL, --fk
    Jenis_Surat NVARCHAR(50) CHECK (Jenis_Surat IN ('ukt', 'skkm','Toeic','Publikasi','Skla','kompensasi')) NOT NULL,
    TanggalDibuat DATE NOT NULL,
    FOREIGN KEY (IDPengajuan) REFERENCES dbo.TB_Pengajuan(IDPengajuan) --relasi
);

-- Tabel Verifikasi
CREATE TABLE dbo.TB_Verifikasi (
    IDVerifikasi INT PRIMARY KEY IDENTITY(1,1),
    IDPengajuan INT NOT NULL,
    IDAdmin INT NOT NULL,
    TanggalVerifikasi DATE NOT NULL,
    StatusVerifikasi NVARCHAR(50) CHECK (StatusVerifikasi IN ('Disetujui', 'Tidak Disetujui')) NOT NULL,
    Catatan NVARCHAR(MAX),
    FOREIGN KEY (IDPengajuan) REFERENCES dbo.TB_Pengajuan(IDPengajuan),
    FOREIGN KEY (IDAdmin) REFERENCES dbo.TB_Admin(IDAdmin)
);
