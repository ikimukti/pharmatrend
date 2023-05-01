-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2023 at 01:12 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi_arip`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `name` varchar(128) NOT NULL,
  `price` varchar(256) NOT NULL,
  `stock` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `code`, `name`, `price`, `stock`, `created_at`, `updated_at`, `id_user`) VALUES
(3, '001', 'Hufagrip Flu Syr 60 ml (Kuning)', '19800', '1000', '2023-05-01 07:34:22', '2023-05-01 07:34:22', 1),
(4, '002', 'Insto Eye Drop 7,5ml', '12200', '1000', '2023-05-01 07:35:30', '2023-05-01 07:35:30', 1),
(5, 'OBAT001', 'Obat A', '10000', '50', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(6, 'OBAT002', 'Obat B', '12000', '40', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(7, 'OBAT003', 'Obat C', '15000', '30', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(8, 'OBAT004', 'Obat D', '17000', '20', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(9, 'OBAT005', 'Obat E', '20000', '10', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(10, 'OBAT006', 'Obat F', '25000', '50', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(11, 'OBAT007', 'Obat G', '30000', '60', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(12, 'OBAT008', 'Obat H', '18000', '70', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(13, 'OBAT009', 'Obat I', '22000', '80', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(14, 'OBAT010', 'Obat J', '28000', '90', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(15, 'OBAT011', 'Obat K', '24000', '100', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(16, 'OBAT012', 'Obat L', '35000', '110', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(17, 'OBAT013', 'Obat M', '18000', '120', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(18, 'OBAT014', 'Obat N', '40000', '130', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(19, 'OBAT015', 'Obat O', '21000', '140', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(20, 'OBAT016', 'Obat P', '29000', '150', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(21, 'OBAT017', 'Obat Q', '32000', '160', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(22, 'OBAT018', 'Obat R', '27000', '170', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(23, 'OBAT019', 'Obat S', '23000', '180', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(24, 'OBAT020', 'Obat T', '26000', '190', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `sold` varchar(256) NOT NULL,
  `month` varchar(16) NOT NULL,
  `year` varchar(16) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `code`, `sold`, `month`, `year`, `created_at`, `updated_at`, `id_item`, `id_user`) VALUES
(1, 'SALE000001', '100', '1', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(2, 'SALE000002', '150', '2', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(3, 'SALE000003', '200', '3', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(4, 'SALE000004', '250', '4', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(5, 'SALE000005', '300', '5', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(6, 'SALE000006', '350', '6', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(7, 'SALE007', '400', '7', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(8, 'SALE008', '450', '8', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(9, 'SALE009', '500', '9', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(10, 'SALE010', '550', '10', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(11, 'SALE011', '600', '11', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(12, 'SALE012', '650', '12', '2021', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(13, 'SALE013', '700', '1', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(14, 'SALE014', '750', '2', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(15, 'SALE015', '800', '3', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(16, 'SALE016', '850', '4', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(17, 'SALE017', '900', '5', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(18, 'SALE018', '950', '6', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(19, 'SALE019', '1000', '7', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(20, 'SALE020', '1050', '8', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(21, 'SALE021', '1100', '9', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(22, 'SALE022', '1150', '10', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(23, 'SALE023', '1200', '11', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(24, 'SALE024', '1250', '12', '2022', '2023-05-01 13:20:43', '2023-05-01 13:20:43', 3, 1),
(27, 'ITM20230501130905', '2000', '1', '2023', '2023-05-01 13:09:05', '2023-05-01 13:09:05', 3, 1),
(28, 'ITM20230501133124', '5000', '2', '2023', '2023-05-01 13:35:53', '2023-05-01 13:35:53', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(128) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role` varchar(128) NOT NULL,
  `status` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Firmansyah Mukti Wijaya', 'iki.mukti@gmail.com', '$2y$10$kFdnH8qabK7IY7j6OATrBO9W5K.ev4CV/h/Jh7e9X1.FWvecG9ooq', 'user', 'active', '2023-05-01 05:21:56', '2023-05-01 05:21:56'),
(2, 'Purwanti', 'purwanti4official@gmail.com', '$2y$10$xlPzUYq38GRRcZaI1rqQVekgLMJTu9lTkroS/2YHgzMqkfbuTCJ62', 'user', 'active', '2023-05-01 05:24:41', '2023-05-01 05:24:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_item_relation` (`id_user`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_sale_relation` (`id_user`),
  ADD KEY `item_sale_relation` (`id_item`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `user_item_relation` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `item_sale_relation` FOREIGN KEY (`id_item`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_sale_relation` FOREIGN KEY (`id_user`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
