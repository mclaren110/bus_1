-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2024 at 05:38 AM
-- Server version: 10.1.40-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bus_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `bus_information`
--

CREATE TABLE `bus_information` (
  `id` int(11) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `part_number` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `spare_part_info` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bus_parts`
--

CREATE TABLE `bus_parts` (
  `id` int(11) NOT NULL,
  `part_number` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `min_reorder_qty` int(11) NOT NULL,
  `last_reordered_date` date DEFAULT NULL,
  `supplier_info` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bus_parts`
--

INSERT INTO `bus_parts` (`id`, `part_number`, `description`, `quantity`, `min_reorder_qty`, `last_reordered_date`, `supplier_info`, `price`, `created_at`) VALUES
(5, '78293827', 'Compressor', 148, 10000, '2024-10-27', 'China Volvo', '120000.00', '2024-10-27 02:57:44'),
(6, '32134233', 'Transmission', 10, 10000, '2024-10-27', 'china nissan', '1230000.00', '2024-10-27 02:58:37'),
(7, '1231234324', 'Condenser fan', 10, 10000, '2024-10-27', 'china nissan', '12333333.00', '2024-10-27 02:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `reliability_rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_info`, `reliability_rating`, `created_at`) VALUES
(1, 'china of suppliers', '09616378187', 5, '2024-10-27 02:43:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_approved` tinyint(1) DEFAULT '0',
  `role` enum('admin','user') NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `is_active`, `is_approved`, `role`, `created_at`) VALUES
(3, 'stevenjoshsarmienti10@gmail.com', '$2y$10$nBAs6.yJq7NdjqFy93OUrOVUREgJx9o.vkAAvesGBwqyTAZ2c0G7S', 'mclaren', 'sarmiento', 1, 1, 'admin', '2024-10-26 15:56:14'),
(8, 'gians', '$2y$10$K2N9yTPmtspTnXvwsFLlWOIEhg.cmzeCjAAGTz7NmAgvgHQdE0y76', 'gians', 'jaquins', 1, 1, 'user', '2024-10-26 17:25:03'),
(9, 'admin@admin', '$2y$10$B4MFoLxNJ/ma/XAf3cgsD.LNSObcKNPI8iYa3tb3oQbbCzFZI8uGu', 'josh', 'mclaren', 1, 1, 'admin', '2024-10-27 03:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bus_information`
--
ALTER TABLE `bus_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_parts`
--
ALTER TABLE `bus_parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bus_information`
--
ALTER TABLE `bus_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `bus_parts`
--
ALTER TABLE `bus_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
