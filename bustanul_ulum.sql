-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Aug 07, 2024 at 10:54 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama`, `kelas`, `nilai_raport`, `extrakurikuler`, `prestasi`, `absensi`) VALUES
(30, 'ZAINULLAH', 3, '3.00', '3.00', '3.00', '3.00'),
(31, 'ANIS', 2, '8.00', '8.00', '8.00', '8.00'),
(32, 'ZAINULLAH', 2, '2.00', '2.00', '2.00', '2.00');

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

--
-- Dumping data for table `hasil`
--

INSERT INTO `hasil` (`id_hasil`, `id_alternatif`, `nilai_akhir`, `ranking`) VALUES
(38, 30, 1, 1),
(39, 30, 4, 1),
(40, 31, 4, 1),
(41, 30, 3, 3),
(42, 31, 2.33333, 4),
(43, 32, 4.33333, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `bobot`) VALUES
(5, 'zainullah', 0.2),
(6, 'zainiasdtsdsullah', 0.2);

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

--
-- Dumping data for table `perbandingan_alternatif_absensi`
--

INSERT INTO `perbandingan_alternatif_absensi` (`id_perbandingan`, `alternatif1_id`, `alternatif2_id`, `nilai`) VALUES
(21, 30, 31, 0.375),
(22, 30, 32, 1.5),
(23, 31, 30, 2.66667),
(24, 31, 32, 4),
(25, 32, 30, 0.666667),
(26, 32, 31, 0.25);

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

--
-- Dumping data for table `perbandingan_alternatif_raport`
--

INSERT INTO `perbandingan_alternatif_raport` (`id_perbandingan`, `alternatif1_id`, `alternatif2_id`, `nilai`) VALUES
(23, 30, 31, '0.38'),
(24, 30, 32, '1.50'),
(25, 31, 30, '2.67'),
(26, 31, 32, '4.00'),
(27, 32, 30, '0.67'),
(28, 32, 31, '0.25'),
(29, 30, 31, '0.38'),
(30, 30, 32, '1.50'),
(31, 31, 30, '2.67'),
(32, 31, 32, '4.00'),
(33, 32, 30, '0.67'),
(34, 32, 31, '0.25'),
(35, 30, 31, '0.38'),
(36, 30, 32, '1.50'),
(37, 31, 30, '2.67'),
(38, 31, 32, '4.00'),
(39, 32, 30, '0.67'),
(40, 32, 31, '0.25');

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

--
-- Dumping data for table `perbandingan_kriteria`
--

INSERT INTO `perbandingan_kriteria` (`id_perbandingan`, `kriteria1_id`, `kriteria2_id`, `nilai`) VALUES
(1, 2, 1, 45),
(2, 4, 1, 9),
(3, 6, 5, 9);

-- --------------------------------------------------------

--
-- Table structure for table `periode`
--

CREATE TABLE `periode` (
  `id_periode` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `id_alternatif` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `periode`
--

INSERT INTO `periode` (`id_periode`, `tahun`, `id_alternatif`) VALUES
(7, 2024, 30),
(8, 2024, 31),
(9, 2024, 32);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

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
  ADD PRIMARY KEY (`id_periode`),
  ADD KEY `id_alternatif` (`id_alternatif`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `perbandingan_alternatif_absensi`
--
ALTER TABLE `perbandingan_alternatif_absensi`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `perbandingan_alternatif_ekstrakurikuler`
--
ALTER TABLE `perbandingan_alternatif_ekstrakurikuler`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `perbandingan_alternatif_prestasi`
--
ALTER TABLE `perbandingan_alternatif_prestasi`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `perbandingan_alternatif_raport`
--
ALTER TABLE `perbandingan_alternatif_raport`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  MODIFY `id_perbandingan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `periode`
--
ALTER TABLE `periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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

--
-- Constraints for table `periode`
--
ALTER TABLE `periode`
  ADD CONSTRAINT `periode_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
