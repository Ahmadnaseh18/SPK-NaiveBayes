-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 04:53 PM
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
-- Database: `spk_kelulusan`
--

-- --------------------------------------------------------

--
-- Table structure for table `testing`
--

CREATE TABLE `testing` (
  `id` int(11) NOT NULL,
  `ipk` double NOT NULL,
  `sks` int(11) NOT NULL,
  `kehadiran` int(11) NOT NULL,
  `nilai_mk` int(11) NOT NULL,
  `kerja` varchar(20) NOT NULL,
  `hasil_prediksi` varchar(50) DEFAULT NULL,
  `angka_lulus` double DEFAULT NULL,
  `angka_tidak` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testing`
--

INSERT INTO `testing` (`id`, `ipk`, `sks`, `kehadiran`, `nilai_mk`, `kerja`, `hasil_prediksi`, `angka_lulus`, `angka_tidak`) VALUES
(1, 3.5, 144, 90, 85, 'Tidak', 'Tepat Waktu', 99.999539328552, 0.00046067144774695),
(2, 2.75, 120, 70, 75, 'Ya', 'Terlambat', 0.00000000000058281192583783, 99.999999999999),
(3, 3.8, 144, 80, 80, 'Ya', 'Tepat Waktu', 99.978119618359, 0.021880381640644),
(4, 2.2, 144, 90, 85, 'Ya', 'Terlambat', 1.4806787801601, 98.51932121984),
(5, 3.76, 89, 100, 80, 'Ya', 'Terlambat', 2.1246768514764e-57, 100),
(6, 3, 144, 90, 85, 'Ya', 'Tepat Waktu', 99.869934383429, 0.13006561657056);

-- --------------------------------------------------------

--
-- Table structure for table `training`
--

CREATE TABLE `training` (
  `id` int(11) NOT NULL,
  `ipk` double NOT NULL,
  `sks` int(11) NOT NULL,
  `kehadiran` int(11) NOT NULL,
  `nilai_mk` int(11) NOT NULL,
  `kerja` varchar(20) NOT NULL,
  `kelulusan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training`
--

INSERT INTO `training` (`id`, `ipk`, `sks`, `kehadiran`, `nilai_mk`, `kerja`, `kelulusan`) VALUES
(1, 3.9, 144, 100, 90, 'Tidak', 'Tepat Waktu'),
(2, 3.75, 144, 95, 88, 'Tidak', 'Tepat Waktu'),
(3, 3.5, 140, 90, 85, 'Tidak', 'Tepat Waktu'),
(4, 3.25, 138, 85, 80, 'Iya', 'Tepat Waktu'),
(5, 2.15, 110, 60, 65, 'Iya', 'Terlambat'),
(6, 2.5, 120, 70, 70, 'Iya', 'Terlambat'),
(7, 2.8, 130, 75, 75, 'Tidak', 'Terlambat'),
(8, 3, 135, 80, 78, 'Iya', 'Terlambat'),
(9, 3.8, 144, 98, 92, 'Tidak', 'Tepat Waktu'),
(10, 2.2, 115, 50, 60, 'Iya', 'Terlambat'),
(11, 3.65, 142, 92, 86, 'Tidak', 'Tepat Waktu'),
(12, 2.95, 130, 78, 74, 'Iya', 'Terlambat'),
(13, 3.4, 140, 88, 82, 'Tidak', 'Tepat Waktu'),
(14, 2, 100, 40, 55, 'Iya', 'Terlambat'),
(15, 3.15, 136, 82, 79, 'Tidak', 'Tepat Waktu');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`) VALUES
(2, 'naseh', '$2y$10$MRBM7PfnyaV2UStuPRMhcuIyG5eGRW.zt2Cewr.z8xAvk.7MG.rV.', 'admin'),
(3, 'ismu', '$2y$10$r3.db8poALQbLzA4M/rufO/f5F/1/opAf/GdKWI6MyfLYAs7Xlj1G', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `testing`
--
ALTER TABLE `testing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `testing`
--
ALTER TABLE `testing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
