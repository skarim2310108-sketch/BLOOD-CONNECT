-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 07:20 AM
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
-- Database: `bloodconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `units` int(11) NOT NULL,
  `hospital` varchar(100) NOT NULL,
  `district` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('pending','fulfilled','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `recipient_id`, `patient_name`, `age`, `blood_group`, `units`, `hospital`, `district`, `address`, `status`, `created_at`) VALUES
(1, 1, 'John Doe', 45, 'O', 2, 'Square Hospital', 'Dhaka', 'Dhanmondi', 'pending', '2026-06-15 13:29:39'),
(2, 2, 'tarek', 26, 'A+', 1, 'Dhaka medical college', 'Dhaka', 'Dhaka Bangladesh', 'fulfilled', '2026-06-15 13:56:13'),
(3, 1, 'Nabil Patient', 30, 'B', 2, 'City Hospital', 'Dhaka', 'Mirpur', 'pending', '2026-06-15 14:32:51'),
(4, 2, 'tarek', 26, 'A+', 1, 'Dhaka medical college', 'Dhaka', 'Dhaka Bangladesh', 'fulfilled', '2026-06-15 14:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `donation_date` date NOT NULL,
  `location` varchar(100) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `units` int(11) NOT NULL DEFAULT 450,
  `status` enum('completed','cancelled') DEFAULT 'completed',
  `certificate_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_id`, `request_id`, `donation_date`, `location`, `blood_group`, `units`, `status`, `certificate_id`, `created_at`) VALUES
(1, 6, 2, '2026-06-15', 'Dhaka medical college', 'A+', 1, 'completed', NULL, '2026-06-15 14:35:04'),
(2, 7, 4, '2026-06-15', 'Dhaka medical college', 'A+', 1, 'completed', NULL, '2026-06-15 14:48:39');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `district` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `email`, `phone`, `blood_group`, `district`, `address`, `status`, `password`, `created_at`) VALUES
(1, 'Rahim Uddin', 'rahim@example.com', '01711111111', 'B+', 'Dhaka', 'Mirpur, Dhaka', 'available', '$2y$10$m6F.Gqg9D0wsuNSnWcButeC9afUhMSbntOlEw2cqsroZPtrsrN75y', '2026-06-15 13:27:50'),
(2, 'Fatema Begum', 'fatema@example.com', '01822222222', 'A-', 'Dhaka', 'Dhanmondi, Dhaka', 'available', '$2y$10$6WfkMRKazu1HhOGEqYqSZOKfkTwYAX2MLg2I0XndI.dfDMxM4fGlG', '2026-06-15 13:27:50'),
(3, 'Kamal Hossain', 'kamal@example.com', '01933333333', 'O+', 'Dhaka', 'Uttara, Dhaka', 'available', '$2y$10$BK46YUSEZXqHplvOVEb6g.ptGUTfWgfoaTUcS4XhpuOuiaJzEGwR2', '2026-06-15 13:27:50'),
(4, 'Nusrat Jahan', 'nusrat@example.com', '01644444444', 'AB+', 'Chittagong', 'Agrabad, Chittagong', 'available', '$2y$10$e5hwSmIDndIhvCp9d2Y3ue.QbPG2dF0UYQpfjpvHejwyMSbmVztLy', '2026-06-15 13:27:50'),
(5, 'Sabbir Rahman', 'sabbir@example.com', '01555555555', 'A+', 'Rajshahi', 'Shaheb Bazar, Rajshahi', 'available', '$2y$10$sV3q.sRnouAHhCS71MCz8.GIjQxLk1uNWoR5cK2Lq/tGztk5esyKi', '2026-06-15 13:27:50'),
(6, 'Test Donor', 'testdonor@example.com', '01711111111', 'B', 'Dhaka', 'Mirpur', 'available', '$2y$10$QNb9FjF7F6/dGYtJOFN5Ru0OpnSFUL.ndKm1NdKcUQ7QdSwQYLyHC', '2026-06-15 14:31:17'),
(7, 'nahid', 'nahid@gmail.com', '01679217777', 'A+', 'Dhaka', 'Dhaka Bangladesh', 'available', '$2y$10$m45.b0YGf5PvFPKGVKPpQ.4TITHndF9s39BUEa9hENO1OZGpshYX2', '2026-06-15 14:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `recipients`
--

CREATE TABLE `recipients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `district` varchar(50) NOT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipients`
--

INSERT INTO `recipients` (`id`, `name`, `email`, `phone`, `blood_group`, `district`, `address`, `password`, `created_at`) VALUES
(1, 'Test Recipient', 'testrecipient@example.com', '01712345678', 'O', 'Dhaka', 'Mirpur', '$2y$10$96rH3p.vAT5xpOK3uXWLg.fkf3ltNOHwtj9Po5FMmg/r3IjA2P5Ry', '2026-06-15 13:28:07'),
(2, 'nabil', 'nabil@gmail.com', '01679217477', 'A+', 'Dhaka', 'Dhaka Bangladesh', '$2y$10$yzzHw.7mJ30YQ9rakwN3QuX9ehrL.NLY4Ds/HAZiWxA4RHCOHXzGe', '2026-06-15 13:41:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `recipients`
--
ALTER TABLE `recipients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `recipients`
--
ALTER TABLE `recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD CONSTRAINT `blood_requests_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `recipients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `blood_requests` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admins` (`name`, `email`, `password`) VALUES (
  'Super Admin',
  'admin@bloodconnect.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC3uzpv7NEFpCirGaJmy'
);