-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 05:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_asuransi`
--

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `merk` varchar(100) NOT NULL,
  `tipe` varchar(100) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nilai` decimal(15,2) NOT NULL,
  `jenis_asuransi` varchar(100) NOT NULL,
  `wilayah_penggunaan` varchar(100) NOT NULL,
  `perluasan_asuransi` text DEFAULT NULL,
  `no_polisi` varchar(50) NOT NULL,
  `no_rangka` varchar(100) NOT NULL,
  `no_mesin` varchar(100) NOT NULL,
  `warna` varchar(50) NOT NULL,
  `stnk_path` text DEFAULT NULL,
  `kendaraan_photo_path` text DEFAULT NULL,
  `sim_ktp_path` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `pengajuan_id` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status` enum('pending','sukses','gagal') DEFAULT 'pending',
  `tanggal_pembayaran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_asuransi`
--

CREATE TABLE `pengajuan_asuransi` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `kendaraan_id` int(11) NOT NULL,
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `alasan_penolakan` text DEFAULT NULL,
  `pembayaran_status` enum('belum_lunas','lunas') DEFAULT 'belum_lunas',
  `pembayaran_va` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Asri', 'psasri19@gmail.com', '$2y$10$1cEFLjS5mK.lB.B5V5aY0uOlNJpbZBot6SdLEvQ/RJalJqbdzAFCW', '2025-04-20 11:14:01', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengguna_id` (`pengguna_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_id` (`pengajuan_id`);

--
-- Indexes for table `pengajuan_asuransi`
--
ALTER TABLE `pengajuan_asuransi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengguna_id` (`pengguna_id`),
  ADD KEY `kendaraan_id` (`kendaraan_id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuan_asuransi`
--
ALTER TABLE `pengajuan_asuransi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD CONSTRAINT `kendaraan_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan_asuransi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengajuan_asuransi`
--
ALTER TABLE `pengajuan_asuransi`
  ADD CONSTRAINT `pengajuan_asuransi_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_asuransi_ibfk_2` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
