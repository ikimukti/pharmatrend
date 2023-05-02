-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2023 at 08:53 PM
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
(7, 'OBAT003', 'Paracetamol Tab PIM', '15000', '30', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(8, 'ITM20230502191518', 'Dexteem plus', '17000', '0', '2023-05-01 12:57:11', '2023-05-02 19:15:27', 1),
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
(24, 'OBAT020', 'Obat T', '26000', '190', '2023-05-01 12:57:11', '2023-05-01 12:57:11', 1),
(25, '01248', 'Hufagrip BP Syr (Hijau)', '16500', '10000', '2023-05-02 17:40:10', '2023-05-02 17:40:10', 1),
(26, 'ITM20230502181623', 'Pimtrakol Syr Cherry', '13100', '0', '2023-05-02 18:16:35', '2023-05-02 18:16:35', 1),
(27, 'ITM20230502181859', 'Pimtrakol Syr Lemon', '13100', '0', '2023-05-02 18:19:13', '2023-05-02 18:19:13', 1),
(28, 'ITM20230502182018', 'Voltadex Tab 50', '26000', '0', '2023-05-02 18:20:29', '2023-05-02 18:20:29', 1),
(29, 'ITM20230502182956', 'OBH Combi Flu Dewasa 100ml', '12700', '0', '2023-05-02 18:30:08', '2023-05-02 18:30:08', 1),
(30, 'ITM20230502183051', 'OBH Combi Flu Dewasa 60ml', '12100', '0', '2023-05-02 18:30:59', '2023-05-02 18:30:59', 1),
(31, 'ITM20230502183640', 'Demacolin Tab', '40000', '0', '2023-05-02 18:36:52', '2023-05-02 18:36:52', 1),
(32, 'ITM20230502183838', 'OBH Combi Flu Anak Straw', '12100', '0', '2023-05-02 18:38:46', '2023-05-02 18:38:46', 1),
(33, 'ITM20230502183937', 'OBH ITRASAL 100 ml', '7100', '0', '2023-05-02 18:39:46', '2023-05-02 18:39:46', 1),
(34, 'ITM20230502184129', 'Demacolin Syr', '12000', '0', '2023-05-02 18:41:51', '2023-05-02 18:41:51', 1),
(35, 'ITM20230502184235', 'Yusimox Syr', '5200', '0', '2023-05-02 18:42:46', '2023-05-02 18:42:46', 1),
(36, 'ITM20230502184300', 'OBH Combi Flu Anak Jeruk', '12100', '0', '2023-05-02 18:43:21', '2023-05-02 18:43:21', 1),
(37, 'ITM20230502184344', 'Paracetamol Syr NOVA', '3500', '0', '2023-05-02 18:43:53', '2023-05-02 18:43:53', 1),
(38, 'ITM20230502184412', 'Sanmol Syr', '14100', '0', '2023-05-02 18:44:24', '2023-05-02 18:44:24', 1),
(39, 'ITM20230502184454', 'OBH Combi Gepeng 100 ml', '12500', '0', '2023-05-02 18:45:02', '2023-05-02 18:45:02', 1),
(40, 'ITM20230502184524', 'Hufagrip Pilek SYR 60 ml (Biru)', '16500', '0', '2023-05-02 18:45:33', '2023-05-02 18:45:33', 1),
(41, 'ITM20230502185047', 'Rohto TM 7ML', '12500', '0', '2023-05-02 18:51:01', '2023-05-02 18:51:01', 1),
(42, 'ITM20230502185140', 'Rohto Cool TM 7ml', '12500', '0', '2023-05-02 18:51:49', '2023-05-02 18:51:49', 1),
(43, 'ITM20230502190259', 'Hufagrip TMP SYR 60 ml (Merah)', '15000', '0', '2023-05-02 19:03:20', '2023-05-02 19:03:20', 1),
(44, 'ITM20230502190419', 'Halmezyn Syr', '15000', '0', '2023-05-02 19:04:28', '2023-05-02 19:04:28', 1),
(45, 'ITM20230502190830', 'Halfilyn Syr', '15000', '0', '2023-05-02 19:08:42', '2023-05-02 19:08:42', 1),
(46, 'ITM20230502191128', 'Bronchitin Syr', '17000', '0', '2023-05-02 19:11:34', '2023-05-02 19:11:34', 1),
(47, 'ITM20230502191155', 'Peditox', '17000', '0', '2023-05-02 19:11:57', '2023-05-02 19:11:57', 1);

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
(34, 'SALE20230502202040', '1000', '1', '2021', '2023-05-02 20:22:50', '2023-05-02 20:22:50', 47, 1),
(35, 'SALE20230502202937', '1000', '1', '2023', '2023-05-02 20:29:50', '2023-05-02 20:29:50', 3, 1);

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
  ADD KEY `item_sale_relation` (`id_item`),
  ADD KEY `user_sale_relation` (`id_user`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
  ADD CONSTRAINT `user_sale_relation` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
