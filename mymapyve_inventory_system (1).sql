-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 27, 2025 at 11:14 PM
-- Server version: 11.4.9-MariaDB-cll-lve-log
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mymapyve_inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Table Gaming'),
(2, 'Slot Gaming'),
(3, 'IT'),
(4, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` enum('IN','OUT') NOT NULL,
  `quantity` int(11) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `item_id`, `user_id`, `action`, `quantity`, `remark`, `created_at`) VALUES
(1, 2, 3, 'OUT', 10, '', '2025-12-23 06:46:32'),
(2, 1, 1, 'IN', 4, '', '2025-12-23 07:47:33'),
(3, 1, 1, 'OUT', 10, 'table games', '2025-12-23 07:47:50'),
(4, 5, 1, 'OUT', 10, 'jh', '2025-12-23 09:26:37'),
(5, 5, 1, 'OUT', 10, 'fgf', '2025-12-23 10:11:28'),
(6, 1, 1, 'OUT', 3, 'dddd', '2025-12-23 10:11:41'),
(7, 1, 1, 'IN', 100, '', '2025-12-23 15:04:44'),
(8, 1, 1, 'OUT', 1, 'replace', '2025-12-23 15:04:58'),
(9, 1, 1, 'IN', 2, '', '2025-12-23 15:06:52'),
(10, 1, 1, 'OUT', 2, 'd', '2025-12-23 15:06:59'),
(11, 1, 8, 'IN', 22, '', '2025-12-23 15:07:45'),
(12, 1, 8, 'OUT', 2, 'dfsdf', '2025-12-23 15:07:53'),
(13, 1, 9, 'IN', 100, '', '2025-12-23 15:08:35'),
(14, 1, 9, 'OUT', 20, 'sfsdf', '2025-12-23 15:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_code` varchar(20) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `min_quantity` int(11) DEFAULT 10,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_code`, `item_name`, `description`, `quantity`, `min_quantity`, `category_id`, `created_at`) VALUES
(1, 'ITM-0001', 'Laptop', 'Dell Latitude Laptop', 200, 5, 2, '2025-12-23 06:06:48'),
(2, 'ITM-0002', 'Notebook', 'A4 Notebook', 40, 20, 2, '2025-12-23 06:06:48'),
(3, 'ITM-0003', 'Chocolate', 'Milk Chocolate', 100, 50, 3, '2025-12-23 06:06:48'),
(4, 'ITM-0004', 'Laptop', 'dfdfssssssssssssssssssss', 10, 10, 1, '2025-12-23 06:48:24'),
(5, 'ITM-0005', 'Display', 'monitor', 80, 10, 1, '2025-12-23 09:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_time` datetime NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `status` enum('success','failed') NOT NULL,
  `reason` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `login_time`, `ip_address`, `user_agent`, `status`, `reason`) VALUES
(1, 8, '2025-12-24 15:41:55', '175.157.139.135', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL),
(2, 1, '2025-12-24 15:44:24', '175.157.139.135', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL),
(3, 9, '2025-12-24 16:52:11', '175.157.139.135', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL),
(4, 1, '2025-12-24 17:05:31', '175.157.139.135', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'success', NULL),
(5, 8, '2025-12-25 11:05:28', '175.157.139.135', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL),
(6, 1, '2025-12-25 17:35:49', '175.157.139.135', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'success', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `person_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('manager','senior_tech','tech') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `login_status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `person_name`, `username`, `password`, `role`, `created_at`, `login_status`) VALUES
(1, 'Manager', 'superadmin', 'f35364bc808b079853de5a1e343e7159', 'manager', '2025-12-23 06:45:39', 'enabled'),
(9, 'Pasan Madushanka', 'pasan', 'e097a7877717f707d2aaaae67242af65', 'tech', '2025-12-23 14:56:43', 'enabled'),
(8, 'Ruwan Chamara', 'ruwan', 'aa96474ea05df393533bff57b59e3eb7', 'senior_tech', '2025-12-23 14:56:27', 'enabled');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_code` (`item_code`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
