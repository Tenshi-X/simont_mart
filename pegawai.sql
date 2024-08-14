-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2024 at 05:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pegawai`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(3, 'admin', '12345'),
(4, 'pemilik', 'pemilik');

-- --------------------------------------------------------

--
-- Table structure for table `bonus`
--

CREATE TABLE `bonus` (
  `id_bonus` int(11) NOT NULL,
  `nama_bonus` varchar(50) NOT NULL,
  `jumlah_bonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bonus`
--

INSERT INTO `bonus` (`id_bonus`, `nama_bonus`, `jumlah_bonus`) VALUES
(2, 'Bonus Kinerja', 100000),
(3, 'Bonus Jabatan Kepala Toko', 250000),
(4, 'Bonus Jabatan Bendahara', 200000),
(5, 'Tidak Ada Bonus', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gaji`
--

CREATE TABLE `gaji` (
  `id_gaji` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `jumlah_hadir` varchar(25) NOT NULL,
  `tgl_gaji` datetime NOT NULL DEFAULT current_timestamp(),
  `gaji_pokok` int(11) NOT NULL,
  `gaji_lembur` int(11) NOT NULL,
  `tot_bonus` int(11) NOT NULL,
  `tot_potongan` int(11) NOT NULL,
  `tot_gaji` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gaji`
--

INSERT INTO `gaji` (`id_gaji`, `id_pegawai`, `jumlah_hadir`, `tgl_gaji`, `gaji_pokok`, `gaji_lembur`, `tot_bonus`, `tot_potongan`, `tot_gaji`) VALUES
(25, 3, '24', '2024-08-10 10:17:25', 3300000, 250000, 350000, 314615, 3585385),
(26, 3, '24', '2024-08-10 10:19:29', 3300000, 250000, 350000, 314615, 3585385),
(27, 4, '23', '2024-08-07 10:22:03', 2800000, 200000, 300000, 371795, 2928205),
(28, 3, '23', '2024-08-09 10:27:07', 3300000, 200000, 350000, 384615, 3465385),
(29, 3, '23', '2024-08-09 10:28:34', 3300000, 200000, 350000, 384615, 3465385),
(30, 3, '23', '2024-08-16 10:30:25', 3300000, 200000, 350000, 370513, 3479487),
(31, 4, '23', '2024-08-08 10:33:06', 2800000, 200000, 350000, 359829, 2990171),
(32, 3, '21', '2024-08-08 10:35:01', 3300000, 200000, 350000, 667949, 3182051),
(33, 3, '23', '2024-08-08 10:36:46', 3300000, 250000, 350000, 384615, 3515385),
(34, 3, '21', '2024-08-08 10:38:23', 3300000, 250000, 350000, 584615, 3315385);

-- --------------------------------------------------------

--
-- Table structure for table `gaji_bonus`
--

CREATE TABLE `gaji_bonus` (
  `id_gaji` int(11) DEFAULT NULL,
  `id_bonus` int(11) DEFAULT NULL,
  `tgl_gaji` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gaji_bonus`
--

INSERT INTO `gaji_bonus` (`id_gaji`, `id_bonus`, `tgl_gaji`) VALUES
(29, 3, '2024-08-09 10:28:34'),
(30, 3, '2024-08-16 10:30:25'),
(31, 3, '2024-08-08 10:33:06'),
(32, 3, '2024-08-08 10:35:01'),
(33, 3, '2024-08-08 10:36:46'),
(34, 2, '2024-08-08 10:38:23'),
(34, 3, '2024-08-08 10:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `gaji_potongan`
--

CREATE TABLE `gaji_potongan` (
  `id_gaji` int(11) DEFAULT NULL,
  `id_potongan` int(11) DEFAULT NULL,
  `tgl_gaji` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gaji_potongan`
--

INSERT INTO `gaji_potongan` (`id_gaji`, `id_potongan`, `tgl_gaji`) VALUES
(25, 2, '2024-08-10 10:17:25'),
(25, 6, '2024-08-10 10:17:25'),
(25, 7, '2024-08-10 10:17:25'),
(26, 2, '2024-08-10 10:19:29'),
(26, 6, '2024-08-10 10:19:29'),
(26, 7, '2024-08-10 10:19:29'),
(28, 6, '2024-08-09 10:27:07'),
(28, 7, '2024-08-09 10:27:07'),
(29, 6, '2024-08-09 10:28:34'),
(29, 7, '2024-08-09 10:28:34'),
(30, 6, '2024-08-16 10:30:25'),
(30, 7, '2024-08-16 10:30:25'),
(31, 6, '2024-08-08 10:33:06'),
(31, 7, '2024-08-08 10:33:06'),
(32, 2, '2024-08-08 10:35:01'),
(32, 6, '2024-08-08 10:35:01'),
(32, 7, '2024-08-08 10:35:01'),
(33, 6, '2024-08-08 10:36:46'),
(33, 7, '2024-08-08 10:36:46'),
(34, 6, '2024-08-08 10:38:23'),
(34, 7, '2024-08-08 10:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(50) NOT NULL,
  `gaji_pokok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `gaji_pokok`) VALUES
(4, 'kepala toko', 3300000),
(5, 'bendahara', 2800000),
(6, 'kasir', 2600000),
(7, 'pramuniaga', 2400000);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `nama_pegawai` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL DEFAULT current_timestamp(),
  `tgl_masuk_kerja` date NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `no_hp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `id_jabatan`, `nama_pegawai`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `tgl_masuk_kerja`, `username`, `password`, `no_hp`) VALUES
(3, 4, 'jokowi', 'jakarta', 'solo', '2024-08-08', '2024-08-08', 'awikwok', 'jokowi123', '2147483647'),
(4, 5, 'prabowo', 'jakarta', 'jakarta', '2024-08-01', '2024-08-11', 'gedagedi', 'gibran123', '2147483647');

-- --------------------------------------------------------

--
-- Table structure for table `potongan`
--

CREATE TABLE `potongan` (
  `id_potongan` int(11) NOT NULL,
  `nama_potongan` varchar(50) NOT NULL,
  `nilai_potongan` int(11) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `potongan`
--

INSERT INTO `potongan` (`id_potongan`, `nama_potongan`, `nilai_potongan`, `keterangan`) VALUES
(2, 'kerugian barang', 15, 'dalam persen'),
(3, 'keterlambatan kepala toko', 14102, 'per jam'),
(4, 'keterlambatan bendahara', 11965, 'per jam'),
(5, 'keterlambatan kasir', 11111, 'per jam'),
(6, 'keterlambatan pramuniaga', 10256, 'per jam'),
(7, 'tidak hadir', 100000, 'per hari');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `bonus`
--
ALTER TABLE `bonus`
  ADD PRIMARY KEY (`id_bonus`);

--
-- Indexes for table `gaji`
--
ALTER TABLE `gaji`
  ADD PRIMARY KEY (`id_gaji`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `gaji_bonus`
--
ALTER TABLE `gaji_bonus`
  ADD KEY `gaji_bonus_ibfk_1` (`id_gaji`),
  ADD KEY `gaji_bonus_ibfk_2` (`id_bonus`);

--
-- Indexes for table `gaji_potongan`
--
ALTER TABLE `gaji_potongan`
  ADD KEY `gaji_potongan_ibfk_1` (`id_gaji`),
  ADD KEY `gaji_potongan_ibfk_2` (`id_potongan`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD KEY `fk_jabatan_pegawai` (`id_jabatan`);

--
-- Indexes for table `potongan`
--
ALTER TABLE `potongan`
  ADD PRIMARY KEY (`id_potongan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bonus`
--
ALTER TABLE `bonus`
  MODIFY `id_bonus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id_gaji` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `potongan`
--
ALTER TABLE `potongan`
  MODIFY `id_potongan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gaji`
--
ALTER TABLE `gaji`
  ADD CONSTRAINT `fk_pegawai_gaji` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gaji_bonus`
--
ALTER TABLE `gaji_bonus`
  ADD CONSTRAINT `gaji_bonus_ibfk_1` FOREIGN KEY (`id_gaji`) REFERENCES `gaji` (`id_gaji`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gaji_bonus_ibfk_2` FOREIGN KEY (`id_bonus`) REFERENCES `bonus` (`id_bonus`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gaji_potongan`
--
ALTER TABLE `gaji_potongan`
  ADD CONSTRAINT `gaji_potongan_ibfk_1` FOREIGN KEY (`id_gaji`) REFERENCES `gaji` (`id_gaji`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gaji_potongan_ibfk_2` FOREIGN KEY (`id_potongan`) REFERENCES `potongan` (`id_potongan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `fk_jabatan_pegawai` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
