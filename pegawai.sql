-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2024 at 05:49 AM
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
(5, 'Tidak Ada Bonus', 0),
(6, 'Bonus Jabatan Kasir', 75000),
(7, 'Bonus Jabatan Pramuniaga', 75000);

-- --------------------------------------------------------

--
-- Table structure for table `gaji`
--

CREATE TABLE `gaji` (
  `id_gaji` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `jumlah_hadir` varchar(25) NOT NULL,
  `tgl_gaji` date NOT NULL DEFAULT current_timestamp(),
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
(25, 3, '24', '2024-08-10', 3300000, 250000, 350000, 314615, 3585385),
(36, 8, '26', '2024-08-12', 2400000, 100000, 75000, 585083, 1989917),
(37, 10, '26', '2024-08-20', 2400000, 150000, 175000, 410724, 2314276),
(38, 3, '36', '2024-08-20', 3300000, 150000, 175000, 390212, 3234788),
(39, 8, '23', '2024-08-31', 2400000, 100000, 75000, 368400, 2206600),
(41, 4, '23', '2024-08-21', 2800000, 200000, 300000, 335897, 2964103),
(42, 4, '24', '2024-06-21', 2800000, 250000, 300000, 259829, 3090171),
(43, 3, '21', '2024-08-21', 3300000, 150000, 250000, 556410, 3143590),
(44, 3, '21', '2024-08-21', 3300000, 150000, 350000, 1070679, 2729321),
(45, 4, '23', '2020-05-31', 2800000, 200000, 300000, 323932, 2976068),
(46, 4, '21', '2022-04-30', 2800000, 200000, 300000, 559829, 2740171),
(47, 9, '24', '2021-01-31', 2600000, 150000, 175000, 274444, 2650556),
(48, 9, '3', '2024-01-31', 2600000, 200000, 175000, 2385556, 589444),
(49, 9, '3', '2024-01-31', 2600000, 200000, 175000, 2385556, 589444),
(50, 9, '3', '2024-01-31', 2600000, 200000, 175000, 2385556, 589444);

-- --------------------------------------------------------

--
-- Table structure for table `gaji_bonus`
--

CREATE TABLE `gaji_bonus` (
  `id_gaji` int(11) DEFAULT NULL,
  `id_bonus` int(11) DEFAULT NULL,
  `nilai_bonus` int(11) DEFAULT NULL,
  `tgl_gaji` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gaji_bonus`
--

INSERT INTO `gaji_bonus` (`id_gaji`, `id_bonus`, `nilai_bonus`, `tgl_gaji`) VALUES
(36, 5, 0, '2024-08-12 11:23:44'),
(36, 4, 0, '2024-08-12 11:23:44'),
(37, 2, 0, '2024-08-20 08:37:21'),
(37, 4, 0, '2024-08-20 08:37:21'),
(38, 2, 0, '2024-08-20 08:56:20'),
(38, 4, 0, '2024-08-20 08:56:20'),
(39, 5, 0, '2024-08-31 18:49:01'),
(39, 4, 0, '2024-08-31 18:49:01'),
(41, 2, 0, '2024-08-21 09:53:04'),
(41, 4, 0, '2024-08-21 09:53:04'),
(42, 2, 0, '2024-08-21 09:57:22'),
(42, 4, 0, '2024-08-21 09:57:22'),
(43, 3, 0, '2024-08-21 10:04:13'),
(46, 2, 0, '2022-04-30 10:31:14'),
(46, 4, 0, '2022-04-30 10:31:14'),
(47, 2, 0, '2021-01-31 10:32:21'),
(47, 4, 0, '2021-01-31 10:32:21'),
(50, 2, 100000, '2024-01-31 10:47:35'),
(50, 6, 75000, '2024-01-31 10:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `gaji_potongan`
--

CREATE TABLE `gaji_potongan` (
  `id_gaji` int(11) DEFAULT NULL,
  `id_potongan` int(11) DEFAULT NULL,
  `nilai_potongan` int(11) DEFAULT NULL,
  `tgl_gaji` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gaji_potongan`
--

INSERT INTO `gaji_potongan` (`id_gaji`, `id_potongan`, `nilai_potongan`, `tgl_gaji`) VALUES
(25, 2, NULL, '2024-08-10 10:17:25'),
(25, 6, NULL, '2024-08-10 10:17:25'),
(25, 7, NULL, '2024-08-10 10:17:25'),
(36, 2, NULL, '2024-08-12 11:23:44'),
(36, 6, NULL, '2024-08-12 11:23:44'),
(37, 2, NULL, '2024-08-20 08:37:21'),
(37, 6, NULL, '2024-08-20 08:37:21'),
(38, 2, NULL, '2024-08-20 08:56:20'),
(38, 6, NULL, '2024-08-20 08:56:20'),
(39, 2, NULL, '2024-08-31 18:49:01'),
(39, 6, NULL, '2024-08-31 18:49:01'),
(39, 7, NULL, '2024-08-31 18:49:01'),
(41, 6, NULL, '2024-08-21 09:53:04'),
(41, 7, NULL, '2024-08-21 09:53:04'),
(42, 6, NULL, '2024-08-21 09:57:22'),
(42, 7, NULL, '2024-08-21 09:57:22'),
(43, 6, NULL, '2024-08-21 10:04:13'),
(43, 7, NULL, '2024-08-21 10:04:13'),
(46, 6, NULL, '2022-04-30 10:31:14'),
(46, 7, NULL, '2022-04-30 10:31:14'),
(47, 2, NULL, '2021-01-31 10:32:21'),
(47, 6, NULL, '2021-01-31 10:32:21'),
(47, 7, NULL, '2021-01-31 10:32:21'),
(50, 2, 30000, '2024-01-31 10:47:35'),
(50, 6, 5, '2024-01-31 10:47:35'),
(50, 7, 23, '2024-01-31 10:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(50) NOT NULL,
  `gaji_pokok` int(11) NOT NULL,
  `gaji_bonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `nama_jabatan`, `gaji_pokok`, `gaji_bonus`) VALUES
(4, 'Kepala toko', 3300000, 250000),
(5, 'Bendahara', 2800000, 200000),
(6, 'Kasir', 2600000, 75000),
(7, 'Pramuniaga', 2400000, 75000);

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
(3, 4, 'Hendra', 'sendang senori tuban', 'Tuban', '2024-08-08', '2024-08-08', 'Amirul', 'amirul123', '2147483647'),
(4, 5, 'sairoh', 'jakarta', 'jakarta', '2024-08-01', '2024-08-11', 'sairoh', 'sairoh123', '2147483647'),
(8, 7, 'Taufik', 'seturan', 'wonosobo', '2003-01-14', '2024-08-20', 'taufik', 'taufik123', '081332552410'),
(9, 6, 'Amirul', 'senori', 'Tuban', '2004-01-31', '2024-08-01', 'Mukminin', 'mukminin12', '083857054402'),
(10, 7, 'Mukminin', 'bantul', 'tuban', '2003-02-19', '2024-08-01', 'mirul', 'mirul123', '081332551322');

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
(2, 'Kerugian barang', 15, 'dalam persen'),
(3, 'Keterlambatan kepala toko', 14102, 'per jam'),
(4, 'Keterlambatan bendahara', 11965, 'per jam'),
(5, 'Keterlambatan kasir', 11111, 'per jam'),
(6, 'Keterlambatan pramuniaga', 10256, 'per jam'),
(7, 'Tidak hadir', 100000, 'per hari');

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
  MODIFY `id_bonus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gaji`
--
ALTER TABLE `gaji`
  MODIFY `id_gaji` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
