-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 29, 2025 at 06:09 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pd`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `kode` varchar(20) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb3_swedish_ci NOT NULL,
  `alamat` text COLLATE utf8mb3_swedish_ci NOT NULL,
  `telepon` varchar(20) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `kode`, `nama`, `alamat`, `telepon`, `created_at`) VALUES
(1, 'CST001', 'Toko Tani Jaya', 'Padang', '082165443677', '2025-06-28 09:01:32');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int NOT NULL,
  `pesanan_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `pesanan_id`, `produk_id`, `jumlah`, `harga`, `subtotal`) VALUES
(5, 3, 1, 2, '2500000.00', '5000000.00');

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id` int NOT NULL,
  `pesanan_id` int NOT NULL,
  `tanggal_kirim` date NOT NULL,
  `driver` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `status` enum('siap','dikirim','sampai') COLLATE utf8mb3_swedish_ci DEFAULT 'siap',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `pengiriman`
--

INSERT INTO `pengiriman` (`id`, `pesanan_id`, `tanggal_kirim`, `driver`, `status`, `created_by`, `created_at`) VALUES
(1, 3, '2025-06-28', 'Budi', 'dikirim', 1, '2025-06-28 09:21:55');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int NOT NULL,
  `kode` varchar(20) COLLATE utf8mb3_swedish_ci NOT NULL,
  `customer_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `total` decimal(12,2) DEFAULT '0.00',
  `status` enum('pending','approved','selesai') COLLATE utf8mb3_swedish_ci DEFAULT 'pending',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `kode`, `customer_id`, `tanggal`, `total`, `status`, `created_by`, `created_at`) VALUES
(3, 'PO2025001', 1, '2025-06-28', '5000000.00', 'approved', 1, '2025-06-28 09:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `kode` varchar(20) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb3_swedish_ci NOT NULL,
  `formula` varchar(20) COLLATE utf8mb3_swedish_ci NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kode`, `nama`, `formula`, `harga`, `stok`, `created_at`) VALUES
(1, 'PRD001', 'NPK 01', '15-15-15', '2500000.00', 103, '2025-06-28 08:45:25');

-- --------------------------------------------------------

--
-- Table structure for table `produksi`
--

CREATE TABLE `produksi` (
  `id` int NOT NULL,
  `produk_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` int NOT NULL,
  `shift` enum('pagi','siang','malam') COLLATE utf8mb3_swedish_ci NOT NULL,
  `status` enum('draft','approved','completed') COLLATE utf8mb3_swedish_ci DEFAULT 'draft',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `produksi`
--

INSERT INTO `produksi` (`id`, `produk_id`, `tanggal`, `jumlah`, `shift`, `status`, `created_by`, `created_at`) VALUES
(1, 1, '2025-06-28', 5, 'pagi', 'completed', 1, '2025-06-28 08:51:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_swedish_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb3_swedish_ci NOT NULL,
  `role` enum('admin','pimpinan') COLLATE utf8mb3_swedish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`, `created_at`) VALUES
(1, 'admin', 'admin', 'Administrator', 'admin', '2025-06-28 08:07:52'),
(2, 'pimpinan', 'pimpinan', 'Pimpinan', 'pimpinan', '2025-06-28 10:02:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `produksi`
--
ALTER TABLE `produksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produk_id` (`produk_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produksi`
--
ALTER TABLE `produksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`);

--
-- Constraints for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD CONSTRAINT `pengiriman_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`),
  ADD CONSTRAINT `pengiriman_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `produksi`
--
ALTER TABLE `produksi`
  ADD CONSTRAINT `produksi_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`),
  ADD CONSTRAINT `produksi_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
