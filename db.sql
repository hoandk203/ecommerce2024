-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 03:10 AM
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
-- Database: `thaiviethoan`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `manufacturing_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `created_at`, `updated_at`, `quantity`, `image`, `manufacturing_date`, `expiry_date`) VALUES
(21, 'Bánh dừa', 'Nguyên liệu chính là dừa', 23.00, 1, '2024-10-28 07:39:48', '2024-12-10 21:42:52', 28, 'products/BdApBLYRRs2moCub8DiYic8m3uPZayObr3Ji1Tox.jpg', '2003-09-18', '2024-11-02'),
(22, 'Donut kem trứng', 'Loại bánh donut ngon', 44.00, 2, '2024-10-28 07:41:19', '2024-10-28 09:44:22', 25, 'products/hmOcr9HkKoh9NXhs37IsQAIMoP930pwDjoyzgdRI.jpg', '2003-09-18', '2025-11-11'),
(23, 'Dorayaki', 'Bánh rán doraemon', 55.00, 3, '2024-10-28 08:01:11', '2024-12-10 21:42:52', 33, 'products/hm8R2jkNJWDBxEs45SpeNa12nTYilWze0DkEMs4C.jpg', '2003-09-18', '2024-11-29'),
(24, 'Mousse', 'Bánh ngọt', 33.00, 2, '2024-10-28 09:37:22', '2024-10-28 09:37:22', 43, 'products/ESgC75yrqGtRoPV8KyoGoXZVpzDeoKqZlw80ZI0A.jpg', '2003-09-18', '2024-12-11'),
(25, 'Bánh phô mai sầu riêng', 'Sầu riêng với bánh kem', 66.00, 1, '2024-10-28 09:38:35', '2024-10-28 09:44:22', 51, 'products/lGgQFaCYxB39Eb66DoJlUlZPhL0MdW1OIdslBLnm.jpg', '2003-09-18', '2025-09-12'),
(26, 'Bánh su kem', 'Ngon', 33.00, 2, '2024-10-28 09:39:18', '2024-12-10 21:51:36', 44, 'products/3mYpL1a6nFuzOfs250yCG7WVbB043Lg5xC0tqbEg.jpg', '2003-09-18', '2026-04-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
