-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 05:05 PM
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
-- Database: `billing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_no`, `item_code`, `description`, `quantity`, `price`, `discount_percent`, `total`) VALUES
(1, 'INV-20260419-6777', 'ITEM001', 'Handwoven Rattan Basket', 5.00, 850.00, 5.00, 4037.50),
(2, 'INV-20260419-6777', 'ITEM002', 'Coconut Shell Bowl', 3.00, 450.00, 0.00, 1350.00),
(3, 'INV-20260419-2522', 'ITEM004', 'Batik Wall Hanging', 4.00, 1200.00, 0.00, 4800.00),
(4, 'INV-20260419-2522', 'ITEM008', 'Ceramic Painted Mug', 5.00, 380.00, 0.00, 1900.00),
(5, 'INV-20260419-9654', 'ITEM007', 'Handloom Cotton Scarf', 1.00, 950.00, 0.00, 950.00),
(6, 'INV-20260419-9654', 'ITEM006', 'Ceylon Tea Gift Pack', 1.00, 750.00, 0.00, 750.00),
(7, 'INV-20260419-9654', 'ITEM009', 'Sandalwood Carved Box', 1.00, 950.00, 0.00, 950.00),
(8, 'INV-20260419-9010', 'ITEM004', 'Batik Wall Hanging', 1.00, 1200.00, 0.00, 1200.00),
(9, 'INV-20260419-9010', 'ITEM007', 'Handloom Cotton Scarf', 6.00, 950.00, 0.00, 5700.00),
(10, 'INV-20260419-3500', 'ITEM007', 'Handloom Cotton Scarf', 1.00, 950.00, 0.00, 950.00),
(11, 'INV-20260419-3500', 'ITEM006', 'Ceylon Tea Gift Pack', 1.00, 750.00, 0.00, 750.00),
(12, 'INV-20260419-3500', 'ITEM009', 'Sandalwood Carved Box', 1.00, 950.00, 0.00, 950.00),
(13, 'INV-20260419-6298', 'ITEM004', 'Batik Wall Hanging', 7.00, 1200.00, 0.00, 8400.00),
(14, 'INV-20260419-1911', 'ITEM007', 'Handloom Cotton Scarf', 1.00, 950.00, 0.00, 950.00),
(15, 'INV-20260419-1911', 'ITEM006', 'Ceylon Tea Gift Pack', 1.00, 750.00, 0.00, 750.00),
(16, 'INV-20260419-1911', 'ITEM009', 'Sandalwood Carved Box', 1.00, 950.00, 0.00, 950.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_no` (`invoice_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_no`) REFERENCES `invoices` (`invoice_no`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
