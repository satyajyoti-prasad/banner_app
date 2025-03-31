-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2025 at 08:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `banner_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `au_id` int(11) NOT NULL,
  `au_username` varchar(50) DEFAULT NULL,
  `au_password` varchar(255) DEFAULT NULL,
  `au_reset_token` varchar(64) DEFAULT NULL,
  `au_reset_expires` datetime DEFAULT NULL,
  `au_created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`au_id`, `au_username`, `au_password`, `au_reset_token`, `au_reset_expires`, `au_created_on`) VALUES
(1, 'user@admin.com', '$2y$12$Ggi5smTanGia3hz5b/ls4ejezXvRbv2lKCr12ACx9CEcA5xNlt85y', NULL, NULL, '2025-03-30 15:05:24');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `banner_id` int(11) NOT NULL,
  `banner_customer_id` int(11) DEFAULT NULL,
  `banner_image_url` varchar(255) DEFAULT NULL,
  `banner_link_url` varchar(255) DEFAULT NULL,
  `banner_alt_text` varchar(100) DEFAULT NULL,
  `banner_is_active` tinyint(1) NOT NULL DEFAULT 1,
  `banner_created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`banner_id`, `banner_customer_id`, `banner_image_url`, `banner_link_url`, `banner_alt_text`, `banner_is_active`, `banner_created_on`) VALUES
(1, 1, 'assets/uploads/banners/1743402523_5d6769bd87458e475b41.png', 'https://google.com', 'Banner Text - Inspiring India', 1, '2025-03-31 11:58:43');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_logo` varchar(255) DEFAULT NULL,
  `customer_pseudo_id` varchar(32) NOT NULL COMMENT 'This to be shared along with script for dynamic banner',
  `customer_created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_logo`, `customer_pseudo_id`, `customer_created_at`) VALUES
(1, 'MicroCorp', 'assets/uploads/logos/1743402423_e29476ac8bb33b7377f0.jpg', '71a1fbe174ed024fc1ed8aa1e3e1712e', '2025-03-31 11:57:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`au_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `au_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
