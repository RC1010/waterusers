-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 03, 2025 at 08:48 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `waterusers`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `number` varchar(15) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `first_name`, `last_name`, `address`, `number`, `created_at`, `updated_at`, `updated_by`, `created_by`) VALUES
(6, 'Kevin', 'Kaslana', '15 Apple Tree Bangboo St.', '639123123453', '2025-01-13 09:44:09', '2025-01-14 11:44:38', '0000-00-00 00:00:00', 12),
(8, 'Amanda', 'Dudu', '12 Flower Garden Celestio St. Near MAMAMU', '639123123453', '2025-01-14 10:12:29', '2025-01-14 11:44:46', '0000-00-00 00:00:00', 12),
(9, 'Riif', 'Filo', '56 Lala Land Banana St. Grass Ave', '+639812342223', '2025-01-20 13:11:09', '2025-01-20 13:11:09', '0000-00-00 00:00:00', 12),
(10, 'Lolu', 'Spleperk', '11 Canana Onana Banana St.', '+63636234213', '2025-01-20 13:13:55', '2025-01-20 13:13:55', '0000-00-00 00:00:00', 46),
(11, 'Mimima', 'Mamami', '90 Oak Tree St. Boudibi Ave. Lalanana', '+631230877940', '2025-01-21 06:13:59', '2025-01-21 06:13:59', '0000-00-00 00:00:00', 12);

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `id` int(11) NOT NULL,
  `quantity_ordered` int(50) NOT NULL,
  `User` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`id`, `quantity_ordered`, `User`, `created_at`, `updated_at`, `customer_id`, `created_by`, `amount`) VALUES
(1, 5, '', '2025-01-14 10:57:32', '2025-02-25 19:44:50', 6, 12, 160.00),
(2, 3, '', '2025-01-14 11:00:16', '2025-02-25 19:44:18', 8, 12, 120.00),
(3, 10, '', '2025-01-14 11:00:20', '2025-02-25 19:44:57', 6, 12, 400.00),
(4, 10, '', '2025-01-20 08:42:46', '2025-02-25 19:45:05', 6, 12, 400.00),
(5, 10, '', '2025-01-20 13:12:45', '2025-02-25 19:45:13', 9, 12, 400.00),
(6, 5, '', '2025-01-20 13:14:48', '2025-02-25 19:45:23', 10, 46, 200.00),
(7, 10, '', '2025-01-21 06:14:17', '2025-02-25 19:45:29', 11, 12, 400.00),
(8, 1, '', '2025-02-25 19:33:09', '2025-02-25 19:33:09', 6, 12, 40.00);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `roles` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `roles`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Has full access to all features.', 0, 0, 2147483647),
(2, 'Sales Analyst', 'Can view and analyze sales data.', 0, 0, 2147483647),
(3, 'Order Manager', 'Manages orders and fulfillment.', 0, 0, 2147483647),
(4, 'User Manager', 'Manages user accounts and permissions.', 0, 0, 2147483647),
(5, 'Scheduler', 'Manages and schedules tasks.', 0, 0, 2147483647),
(6, 'Report Viewer', 'Views and interacts with reports.', 0, 0, 0),
(16, 'Employee', 'Works in Delivering Orders', 12, 2025, 2025);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `password`, `email`, `created_at`, `updated_at`, `role_id`, `created_by`, `status`) VALUES
(12, 'Admin', '01', '$2y$10$tvdTYli7.DlzpeXRPL7Tde6fTholbZ4qxZwFgg9pas7ImQGUZ.8q6', 'admin@gmail.com', '2024-08-27 15:56:04', '2025-01-13 09:04:38', 1, 0, 'active'),
(40, 'Kevin', 'Kaslana', '$2y$10$o0eR3.8zKyWfnezlmsRRv.7T2QbJDBlV0WhYKfHyxIrTJUQgMx/42', 'kaslana@gmail.com', '2024-12-16 02:40:42', '2025-01-13 08:47:33', 6, 12, 'inactive'),
(43, 'Amt', 'Math', '$2y$10$M0LML7197SH.TKy5ie2ov.QumCYDT8eupb8ipMB1NnCzqz6mB2IKO', 'tm@gmail.com', '2024-12-16 10:53:37', '2025-01-13 09:16:37', 4, 12, 'inactive'),
(44, 'Kevin', 'Bottom', '$2y$10$dfWNonXU8ZJl.o7Uyc2kAueN9ALfMymZJ6uSbYHw3pUXBPqr.XRci', 'appleprddod@apple.com', '2024-12-16 11:15:41', '2024-12-16 11:37:51', 5, 12, 'inactive'),
(46, 'OLOl', 'Bottom', '$2y$10$W1dFIndZ73EpV6XVOQISg.vps2EpZ4CLZ1MXIUF4KHRyFDWiXPw0a', 'apple@gmail.com', '2024-12-16 12:55:05', '2025-01-20 13:19:20', 16, 12, 'inactive'),
(47, 'Asuna', 'Yuuki', '$2y$10$hIB7v1CvSe94X56r3ZSZnuU2HllGGV5uTlJPlaiTjRq/ys7pG.Idu', 'asunayuuki@gmail.com', '2025-01-21 06:15:18', '2025-01-21 06:15:43', 2, 12, 'inactive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_customer_created_by` (`created_by`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_customer_orders_customer` (`customer_id`),
  ADD KEY `fk_customer_orders_created_by` (`created_by`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles` (`roles`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`);

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `fk_customer_orders_created_by` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_customer_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
