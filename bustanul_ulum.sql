-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 07, 2024 at 09:02 PM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bustanul_ulum`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas` int(11) DEFAULT NULL,
  `nilai_raport` decimal(5,2) DEFAULT NULL,
  `extrakurikuler` decimal(5,2) DEFAULT NULL,
  `prestasi` decimal(5,2) DEFAULT NULL,
  `absensi` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `id_hasil` int(11) NOT NULL,
  `id_alternatif` int(11) DEFAULT NULL,
  `nilai_akhir` float DEFAULT NULL,
  `ranking` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perbandingan_alternatif_absensi`
--

CREATE TABLE `perbandingan_alternatif_absensi` (
  `id_perbandingan` int(11) NOT NULL,
  `alternatif1_id` int(11) DEFAULT NULL,
  `alternatif2_id` int(11) DEFAULT NULL,
  `nilai` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perbandingan_alternatif_ekstrakurikuler`
--

CREATE TABLE `perbandingan_alternatif_ekstrakurikuler` (
  `id_perbandingan` int(11) NOT NULL,
  `alternatif1_id` int(11) DEFAULT NULL,
  `alternatif2_id` int(11) DEFAULT NULL,
  `nilai` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perbandingan_alternatif_prestasi`
--

CREATE TABLE `perbandingan_alternatif_prestasi` (
  `id_perbandingan` int(11) NOT NULL,
  `alternatif1_id` int(11) DEFAULT NULL,
  `alternatif2_id` int(11) DEFAULT NULL,
  `nilai` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perbandingan_alternatif_raport`
--

CREATE TABLE `perbandingan_alternatif_raport` (
  `id_perbandingan` int(11) NOT NULL,
  `alternatif1_id` int(11) DEFAULT NULL,
  `alternatif2_id` int(11) DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perbandingan_kriteria`
--

CREATE TABLE `perbandingan_kriteria` (
  `id_perbandingan` int(11) NOT NULL,
  `kriteria1_id` int(11) NOT NULL,
  `kriteria2_id` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periode`
--

CREATE TABLE `periode` (
  `id_periode` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `status` enum('Aktif','Nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_alternatif` (`id_alternatif`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `perbandingan_alternatif_absensi`
--
ALTER TABLE `perbandingan_alternatif_absensi`
  ADD PRIMARY KEY (`id_perbandingan`),
  ADD KEY `alternatif1_id` (`alternatif1_id`),
  ADD KEY `alternatif2_id` (`alternatif2_id`);

--
-- Indexes for table `perbandingan_alternatif_ekstrakurikuler`
--
ALTER TABLE `perbandingan_alternatif_ekstrakurikuler`
  ADD PRIMARY KEY (`id_perbandingan`),
  ADD KEY `alternatif1_id` (`alternatif1_id`),
  ADD KEY `alternatif2_id` (`alternatif2_id`);

--
-- Indexes for table `perbandingan_alternatif_prestasi`
--
ALTER TABLE `perbandingan_alternatif_prestasi`
  ADD PRIMARY KEY (`id_perbandingan`),
  ADD KEY `alternatif1_id` (`alternatif1_id`),
  ADD KEY `alternatif2_id` (`alternatif2_id`);

--
-- Indexes for table `perbandingan_alternatif_raport`
--
ALTER TABLE `perbandingan_alternatif_raport`
  ADD PRIMARY KEY (`id_perbandingan`),
  ADD KEY `alternatif1_id` (`alternatif1_id`),
  ADD KEY `alternatif2_id` (`alternatif2_id`);

--
-- Indexes for table `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  ADD PRIMARY KEY (`id_perbandingan`);

--
-- Indexes for table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id_periode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perbandingan_alternatif_absensi`
--
ALTER TABLE `perbandingan_alternatif_absensi`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perbandingan_alternatif_ekstrakurikuler`
--
ALTER TABLE `perbandingan_alternatif_ekstrakurikuler`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perbandingan_alternatif_prestasi`
--
ALTER TABLE `perbandingan_alternatif_prestasi`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perbandingan_alternatif_raport`
--
ALTER TABLE `perbandingan_alternatif_raport`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `periode`
--
ALTER TABLE `periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `hasil_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE;

--
-- Constraints for table `perbandingan_alternatif_absensi`
--
ALTER TABLE `perbandingan_alternatif_absensi`
  ADD CONSTRAINT `perbandingan_alternatif_absensi_ibfk_1` FOREIGN KEY (`alternatif1_id`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE,
  ADD CONSTRAINT `perbandingan_alternatif_absensi_ibfk_2` FOREIGN KEY (`alternatif2_id`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE;

--
-- Constraints for table `perbandingan_alternatif_ekstrakurikuler`
--
ALTER TABLE `perbandingan_alternatif_ekstrakurikuler`
  ADD CONSTRAINT `perbandingan_alternatif_ekstrakurikuler_ibfk_1` FOREIGN KEY (`alternatif1_id`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE,
  ADD CONSTRAINT `perbandingan_alternatif_ekstrakurikuler_ibfk_2` FOREIGN KEY (`alternatif2_id`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE;

--
-- Constraints for table `perbandingan_alternatif_prestasi`
--
ALTER TABLE `perbandingan_alternatif_prestasi`
  ADD CONSTRAINT `perbandingan_alternatif_prestasi_ibfk_1` FOREIGN KEY (`alternatif1_id`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE,
  ADD CONSTRAINT `perbandingan_alternatif_prestasi_ibfk_2` FOREIGN KEY (`alternatif2_id`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE;

--
-- Constraints for table `perbandingan_alternatif_raport`
--
ALTER TABLE `perbandingan_alternatif_raport`
  ADD CONSTRAINT `perbandingan_alternatif_raport_ibfk_1` FOREIGN KEY (`alternatif1_id`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE,
  ADD CONSTRAINT `perbandingan_alternatif_raport_ibfk_2` FOREIGN KEY (`alternatif2_id`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
