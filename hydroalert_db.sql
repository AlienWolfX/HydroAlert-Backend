-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2025 at 09:46 PM
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
-- Database: `hydroalert_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(64) DEFAULT '',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `phone`, `created_at`, `updated_at`) VALUES
(2, 'fwefewf', '2332423', '2025-11-23 06:12:40', '2025-11-23 06:12:40'),
(3, 'Allen Cruiz', '09124614430', '2025-11-23 06:13:01', '2025-11-23 06:13:01'),
(4, '234t', '33233', '2025-11-23 06:15:57', '2025-11-23 06:15:57'),
(5, 'Dalton Jones', '+1 (149) 917-2618', '2025-11-23 06:17:16', '2025-11-23 06:17:16'),
(8, 'Rashad Campbell', '+1 (497) 814-5641', '2025-11-23 06:32:20', '2025-11-23 06:32:20'),
(9, 'Vaughan Leach', '+1 (175) 314-2075', '2025-11-23 07:19:51', '2025-11-23 07:19:51'),
(10, 'rwerwerwe', '9124614430', '2025-11-23 07:19:57', '2025-11-23 07:19:57');

-- --------------------------------------------------------

--
-- Table structure for table `evacuation_centers`
--

CREATE TABLE `evacuation_centers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT '',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evacuation_centers`
--

INSERT INTO `evacuation_centers` (`id`, `name`, `address`, `status`, `created_at`, `updated_at`) VALUES
(3, 'CSU Gymnasium', 'Ampayon, Butuan City', 'active', '2025-11-23 04:27:40', '2025-11-23 07:19:50'),
(6, 'Cultural Center', 'Unknown St, Poblacion, Bislig City', 'inactive', '2025-11-23 04:48:52', '2025-11-23 07:19:50'),
(7, 'Alisa Hyde', 'Et minim voluptatem', 'inactive', '2025-11-23 04:50:05', '2025-11-23 07:19:50'),
(8, 'Wynne Rodgers', 'Iusto voluptate ipsu', 'active', '2025-11-23 04:50:10', '2025-11-23 07:19:50'),
(9, 'Malik Owen', 'Exercitationem dolor', 'inactive', '2025-11-23 04:50:16', '2025-11-23 07:19:50'),
(10, 'Kimberly Dickson', 'Tenetur quasi numqua', 'inactive', '2025-11-23 04:50:21', '2025-11-23 07:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `measures`
--

CREATE TABLE `measures` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT '',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `measures`
--

INSERT INTO `measures` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Voluptas optio ut c', 'Sequi excepturi pers', 'active', '2025-11-23 06:29:01', '2025-11-23 06:29:01'),
(2, 'Aliquid facere ipsum', 'Adipisci quo enim do', 'inactive', '2025-11-23 06:29:06', '2025-11-23 06:29:06'),
(3, 'Amet debitis autem ', 'Eos occaecat molest', 'inactive', '2025-11-23 06:29:14', '2025-11-23 06:29:14'),
(4, 'Mollitia voluptas ma', 'Et nulla deserunt al', 'inactive', '2025-11-23 06:29:21', '2025-11-23 06:29:21'),
(5, 'Est quia enim mollit', 'Consequuntur lorem s', 'active', '2025-11-23 06:29:27', '2025-11-23 06:29:27'),
(6, 'Sunt iure autem offi', 'Aut omnis officiis n', 'active', '2025-11-23 06:29:32', '2025-11-23 06:29:32'),
(7, 'Facilis sunt obcaeca', 'Sed ut consequuntur ', 'active', '2025-11-23 06:32:15', '2025-11-23 06:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `readings`
--

CREATE TABLE `readings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `imei` varchar(128) DEFAULT NULL,
  `distance` decimal(8,3) DEFAULT NULL,
  `water_level` int(11) DEFAULT NULL,
  `status` varchar(32) DEFAULT NULL,
  `device_timestamp` bigint(20) DEFAULT NULL,
  `max_depth` decimal(8,3) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$kbETAFXKcVf5osWElHl92ex.E1.UOHgKDDAOXc1Gb5SOagzpVDJoS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evacuation_centers`
--
ALTER TABLE `evacuation_centers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `measures`
--
ALTER TABLE `measures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `readings`
--
ALTER TABLE `readings`
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
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `evacuation_centers`
--
ALTER TABLE `evacuation_centers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `measures`
--
ALTER TABLE `measures`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `readings`
--
ALTER TABLE `readings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
