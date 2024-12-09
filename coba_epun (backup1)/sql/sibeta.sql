-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 02, 2024 at 10:18 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sibeta`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `jumlah_alpha` int NOT NULL,
  `semester` varchar(20) NOT NULL,
  `status_validasi` enum('belum mengajukan','diajukan ke admin','diverifikasi admin','gagal di admin','diajukan ke kps','diverifikasi kps','gagal di kps','selesai') DEFAULT 'belum mengajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kps`
--

CREATE TABLE `kps` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int NOT NULL,
  `nim` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pkl`
--

CREATE TABLE `pkl` (
  `id_pkl` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `tempat_pkl` varchar(255) NOT NULL,
  `laporan_pkl` longblob,
  `status_validasi` enum('belum mengajukan','diajukan ke admin','diverifikasi admin','gagal di admin','diajukan ke kps','diverifikasi kps','gagal di kps','selesai') DEFAULT 'belum mengajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publikasi`
--

CREATE TABLE `publikasi` (
  `id_publikasi` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `judul_skripsi` varchar(255) NOT NULL,
  `file_publikasi` longblob,
  `file_program` longblob,
  `status_validasi` enum('belum mengajukan','diajukan ke admin','diverifikasi admin','gagal di admin','diajukan ke kps','diverifikasi kps','gagal di kps','selesai') DEFAULT 'belum mengajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skkm`
--

CREATE TABLE `skkm` (
  `id_skkm` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `point_skkm` int NOT NULL,
  `status_validasi` enum('belum mengajukan','diajukan ke admin','diverifikasi admin','gagal di admin','diajukan ke kps','diverifikasi kps','gagal di kps','selesai') DEFAULT 'belum mengajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_bebas_tanggungan`
--

CREATE TABLE `surat_bebas_tanggungan` (
  `id_surat` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `tanggungan_id` int NOT NULL,
  `tanggal_surat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status_surat` enum('belum diterbitkan','sudah diterbitkan') DEFAULT 'belum diterbitkan',
  `file_surat` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tanggal_wisuda`
--

CREATE TABLE `tanggal_wisuda` (
  `id_tanggal_wisuda` int NOT NULL,
  `admin_id` int NOT NULL,
  `tanggal_wisuda` date NOT NULL,
  `kuota` int DEFAULT '0',
  `status` enum('terbuka','tertutup') DEFAULT 'terbuka',
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tanggungan`
--

CREATE TABLE `tanggungan` (
  `id_tanggungan` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `jenis_tanggungan` enum('absensi','ukt','pkl','toeic','skkm','publikasi') NOT NULL,
  `absensi_id` int DEFAULT NULL,
  `ukt_id` int DEFAULT NULL,
  `pkl_id` int DEFAULT NULL,
  `toeic_id` int DEFAULT NULL,
  `skkm_id` int DEFAULT NULL,
  `publikasi_id` int DEFAULT NULL,
  `status_validasi` enum('belum mengajukan','diajukan ke admin','diverifikasi admin','gagal di admin','diajukan ke kps','diverifikasi kps','gagal di kps','selesai') DEFAULT 'belum mengajukan',
  `tanggal_ajukan` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `toeic`
--

CREATE TABLE `toeic` (
  `id_toeic` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `hasil_toeic` longblob,
  `status_validasi` enum('belum mengajukan','diajukan ke admin','diverifikasi admin','gagal di admin','diajukan ke kps','diverifikasi kps','gagal di kps','selesai') DEFAULT 'belum mengajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ukt`
--

CREATE TABLE `ukt` (
  `id_ukt` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `status_validasi` enum('belum mengajukan','diajukan ke admin','diverifikasi admin','gagal di admin','diajukan ke kps','diverifikasi kps','gagal di kps','selesai') DEFAULT 'belum mengajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('mahasiswa','admin','kps') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_admin`
--

CREATE TABLE `verifikasi_admin` (
  `id_verifikasi_admin` int NOT NULL,
  `admin_id` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `id_tanggungan` int NOT NULL,
  `status_validasi` enum('diajukan ke admin','diverifikasi admin','gagal di admin') DEFAULT 'diajukan ke admin',
  `tanggal_verifikasi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_kps`
--

CREATE TABLE `verifikasi_kps` (
  `id_verifikasi_kps` int NOT NULL,
  `kps_id` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `tanggungan_id` int NOT NULL,
  `status_validasi` enum('diajukan ke kps','diverifikasi kps','gagal di kps') DEFAULT 'diajukan ke kps',
  `tanggal_verifikasi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wisuda`
--

CREATE TABLE `wisuda` (
  `id_wisuda` int NOT NULL,
  `mahasiswa_id` int NOT NULL,
  `id_surat` int NOT NULL,
  `status_pendaftaran` enum('terdaftar','tidak terdaftar') DEFAULT 'terdaftar',
  `tanggal_wisuda_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kps`
--
ALTER TABLE `kps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pkl`
--
ALTER TABLE `pkl`
  ADD PRIMARY KEY (`id_pkl`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `publikasi`
--
ALTER TABLE `publikasi`
  ADD PRIMARY KEY (`id_publikasi`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `skkm`
--
ALTER TABLE `skkm`
  ADD PRIMARY KEY (`id_skkm`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `surat_bebas_tanggungan`
--
ALTER TABLE `surat_bebas_tanggungan`
  ADD PRIMARY KEY (`id_surat`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `tanggungan_id` (`tanggungan_id`);

--
-- Indexes for table `tanggal_wisuda`
--
ALTER TABLE `tanggal_wisuda`
  ADD PRIMARY KEY (`id_tanggal_wisuda`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `tanggungan`
--
ALTER TABLE `tanggungan`
  ADD PRIMARY KEY (`id_tanggungan`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `absensi_id` (`absensi_id`),
  ADD KEY `ukt_id` (`ukt_id`),
  ADD KEY `pkl_id` (`pkl_id`),
  ADD KEY `toeic_id` (`toeic_id`),
  ADD KEY `skkm_id` (`skkm_id`),
  ADD KEY `publikasi_id` (`publikasi_id`);

--
-- Indexes for table `toeic`
--
ALTER TABLE `toeic`
  ADD PRIMARY KEY (`id_toeic`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `ukt`
--
ALTER TABLE `ukt`
  ADD PRIMARY KEY (`id_ukt`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `verifikasi_admin`
--
ALTER TABLE `verifikasi_admin`
  ADD PRIMARY KEY (`id_verifikasi_admin`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `id_tanggungan` (`id_tanggungan`);

--
-- Indexes for table `verifikasi_kps`
--
ALTER TABLE `verifikasi_kps`
  ADD PRIMARY KEY (`id_verifikasi_kps`),
  ADD KEY `kps_id` (`kps_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `tanggungan_id` (`tanggungan_id`);

--
-- Indexes for table `wisuda`
--
ALTER TABLE `wisuda`
  ADD PRIMARY KEY (`id_wisuda`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `id_surat` (`id_surat`),
  ADD KEY `tanggal_wisuda_id` (`tanggal_wisuda_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kps`
--
ALTER TABLE `kps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pkl`
--
ALTER TABLE `pkl`
  MODIFY `id_pkl` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `publikasi`
--
ALTER TABLE `publikasi`
  MODIFY `id_publikasi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skkm`
--
ALTER TABLE `skkm`
  MODIFY `id_skkm` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_bebas_tanggungan`
--
ALTER TABLE `surat_bebas_tanggungan`
  MODIFY `id_surat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tanggal_wisuda`
--
ALTER TABLE `tanggal_wisuda`
  MODIFY `id_tanggal_wisuda` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tanggungan`
--
ALTER TABLE `tanggungan`
  MODIFY `id_tanggungan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `toeic`
--
ALTER TABLE `toeic`
  MODIFY `id_toeic` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ukt`
--
ALTER TABLE `ukt`
  MODIFY `id_ukt` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verifikasi_admin`
--
ALTER TABLE `verifikasi_admin`
  MODIFY `id_verifikasi_admin` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verifikasi_kps`
--
ALTER TABLE `verifikasi_kps`
  MODIFY `id_verifikasi_kps` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wisuda`
--
ALTER TABLE `wisuda`
  MODIFY `id_wisuda` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `kps`
--
ALTER TABLE `kps`
  ADD CONSTRAINT `kps_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `pkl`
--
ALTER TABLE `pkl`
  ADD CONSTRAINT `pkl_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `publikasi`
--
ALTER TABLE `publikasi`
  ADD CONSTRAINT `publikasi_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `skkm`
--
ALTER TABLE `skkm`
  ADD CONSTRAINT `skkm_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surat_bebas_tanggungan`
--
ALTER TABLE `surat_bebas_tanggungan`
  ADD CONSTRAINT `surat_bebas_tanggungan_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_bebas_tanggungan_ibfk_2` FOREIGN KEY (`tanggungan_id`) REFERENCES `tanggungan` (`id_tanggungan`) ON DELETE CASCADE;

--
-- Constraints for table `tanggal_wisuda`
--
ALTER TABLE `tanggal_wisuda`
  ADD CONSTRAINT `tanggal_wisuda_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tanggungan`
--
ALTER TABLE `tanggungan`
  ADD CONSTRAINT `tanggungan_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tanggungan_ibfk_2` FOREIGN KEY (`absensi_id`) REFERENCES `absensi` (`id_absensi`) ON DELETE CASCADE,
  ADD CONSTRAINT `tanggungan_ibfk_3` FOREIGN KEY (`ukt_id`) REFERENCES `ukt` (`id_ukt`) ON DELETE CASCADE,
  ADD CONSTRAINT `tanggungan_ibfk_4` FOREIGN KEY (`pkl_id`) REFERENCES `pkl` (`id_pkl`) ON DELETE CASCADE,
  ADD CONSTRAINT `tanggungan_ibfk_5` FOREIGN KEY (`toeic_id`) REFERENCES `toeic` (`id_toeic`) ON DELETE CASCADE,
  ADD CONSTRAINT `tanggungan_ibfk_6` FOREIGN KEY (`skkm_id`) REFERENCES `skkm` (`id_skkm`) ON DELETE CASCADE,
  ADD CONSTRAINT `tanggungan_ibfk_7` FOREIGN KEY (`publikasi_id`) REFERENCES `publikasi` (`id_publikasi`) ON DELETE CASCADE;

--
-- Constraints for table `toeic`
--
ALTER TABLE `toeic`
  ADD CONSTRAINT `toeic_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ukt`
--
ALTER TABLE `ukt`
  ADD CONSTRAINT `ukt_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `verifikasi_admin`
--
ALTER TABLE `verifikasi_admin`
  ADD CONSTRAINT `verifikasi_admin_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verifikasi_admin_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verifikasi_admin_ibfk_3` FOREIGN KEY (`id_tanggungan`) REFERENCES `tanggungan` (`id_tanggungan`) ON DELETE CASCADE;

--
-- Constraints for table `verifikasi_kps`
--
ALTER TABLE `verifikasi_kps`
  ADD CONSTRAINT `verifikasi_kps_ibfk_1` FOREIGN KEY (`kps_id`) REFERENCES `kps` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verifikasi_kps_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `verifikasi_kps_ibfk_3` FOREIGN KEY (`tanggungan_id`) REFERENCES `tanggungan` (`id_tanggungan`) ON DELETE CASCADE;

--
-- Constraints for table `wisuda`
--
ALTER TABLE `wisuda`
  ADD CONSTRAINT `wisuda_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wisuda_ibfk_2` FOREIGN KEY (`id_surat`) REFERENCES `surat_bebas_tanggungan` (`id_surat`) ON DELETE CASCADE,
  ADD CONSTRAINT `wisuda_ibfk_3` FOREIGN KEY (`tanggal_wisuda_id`) REFERENCES `tanggal_wisuda` (`id_tanggal_wisuda`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
