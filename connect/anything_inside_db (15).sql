-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2026 at 10:18 AM
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
-- Database: `anything_inside_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `LogID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `ActionDetails` text NOT NULL,
  `ReferenceID` varchar(50) DEFAULT NULL,
  `ActionType` varchar(50) DEFAULT NULL,
  `Status` enum('Success','Failed') NOT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`LogID`, `UserID`, `UserName`, `ActionDetails`, `ReferenceID`, `ActionType`, `Status`, `CreatedAt`) VALUES
(1, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-04 10:35:09'),
(2, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-04 11:10:13'),
(3, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-04 11:20:43'),
(4, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-05 20:58:02'),
(5, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-05 21:00:30'),
(6, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-05 21:07:36'),
(7, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-08 13:23:05'),
(8, NULL, 'Unknown', 'Updated product \"LOREMIPSUM LOGO\" (ID: 32): Stock: 100 ? 1000', '32', 'Update Product', 'Success', '2026-04-08 14:59:57'),
(9, 1, 'Jay Michael', 'Updated product \"LOREMIPSUM LOGO\" (ID: 32): Stock: 1000 ? 100', '32', 'Update Product', 'Success', '2026-04-08 15:12:22'),
(10, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 32): Product name must be at least 3 characters', '32', 'Update Product', 'Failed', '2026-04-08 15:12:34'),
(11, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 32): Product name must be at least 3 characters', '32', 'Update Product', 'Failed', '2026-04-08 15:12:39'),
(12, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 32): Product name must be at least 3 characters', '32', 'Update Product', 'Failed', '2026-04-08 15:12:46'),
(13, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 32): Product name must be at least 3 characters', '32', 'Update Product', 'Failed', '2026-04-08 15:12:55'),
(14, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 32): Product name must be at least 3 characters', '32', 'Update Product', 'Failed', '2026-04-08 15:13:01'),
(15, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 32): Product name must be at least 3 characters', '32', 'Update Product', 'Failed', '2026-04-08 15:13:06'),
(16, 1, 'Jay Michael', 'Added new product: \"Stary Cat\" | Category: Corporate | Price: ?1,500.00 | Stock: 1', '33', 'Add Product', 'Success', '2026-04-08 15:14:22'),
(17, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 33): Product name must be at least 3 characters', '33', 'Update Product', 'Failed', '2026-04-08 15:14:54'),
(18, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 33): Category must be at least 2 characters', '33', 'Update Product', 'Failed', '2026-04-08 15:15:13'),
(19, 1, 'Jay Michael', 'Updated product \"\" (ID: 33): Name: \"Stary Cat\" ? \"\", Category: \"Corporate\" ? \"\", Price: ?1,500.00 ? ?0.00, Stock: 1 ? ', '33', 'Update Product', 'Success', '2026-04-08 15:15:23'),
(20, 1, 'Jay Michael', 'Updated product \"Stary Cat\" (ID: 33): Name: \"\" ? \"Stary Cat\", Category: \"\" ? \"Thank You\", Price: ?0.00 ? ?1,500.00, Stock: 0 ? 10', '33', 'Update Product', 'Success', '2026-04-08 15:16:07'),
(21, 1, 'Jay Michael', 'Deleted product \"Stary Cat\" (ID: 33)', '33', 'Delete Product', 'Success', '2026-04-08 15:21:57'),
(22, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 33): Product name must be at least 3 characters', '33', 'Update Product', 'Failed', '2026-04-08 15:22:01'),
(23, 1, 'Jay Michael', 'Failed to update product \"Unknown\" (ID: 33): Product name must be at least 3 characters', '33', 'Update Product', 'Failed', '2026-04-08 15:22:07'),
(24, 1, 'Jay Michael', 'Deleted product \"A.I Logo\" (ID: 19)', '19', 'Delete Product', 'Success', '2026-04-08 15:22:53'),
(25, 1, 'Jay Michael', 'Updated product \"LOREMIPSUM LOGO\" (ID: 32): Stock: 100 ? 1000', '32', 'Update Product', 'Success', '2026-04-08 15:23:39'),
(26, 1, 'Jay Michael', 'Updated product \"LOREMIPSUM LOGO\" (ID: 32): Stock: 1000 ? 10', '32', 'Update Product', 'Success', '2026-04-08 15:24:57'),
(27, 1, 'Jay Michael', 'Deleted product \"LOREMIPSUM LOGO\" (ID: 32)', '32', 'Delete Product', 'Success', '2026-04-08 15:24:59'),
(28, 6, 'admin@giftshop.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-04-08 15:25:52'),
(29, 6, 'admin@giftshop.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-04-08 15:26:07'),
(30, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-08 15:26:17'),
(31, 1, 'Jay Michael', 'Added new product: \"Art Works\" | Category: Thank You | Price: ?1,500.00 | Stock: 10', '34', 'Add Product', 'Success', '2026-04-08 15:27:08'),
(32, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-10 22:04:02'),
(33, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-11 00:07:51'),
(34, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-11 15:11:34'),
(0, 1, 'Michael Jay', 'Updated product \"A.I Logo\" (ID: 17) — no changes', '17', 'Update Product', 'Success', '2026-04-11 16:57:42'),
(0, 1, 'Michael Jay', 'Updated product \"Baby Welcome Kit\" (ID: 1): Stock: 140 → 1400', '1', 'Update Product', 'Success', '2026-04-11 19:41:00'),
(0, 1, 'Michael Jay', 'Updated product \"Baby Welcome Kit\" (ID: 1): Stock: 1400 → 140', '1', 'Update Product', 'Success', '2026-04-11 19:41:05'),
(0, 1, 'Michael Jay', 'Updated product \"A.I Logo\" (ID: 17): Image updated', '17', 'Update Product', 'Success', '2026-04-11 19:41:50'),
(0, 1, 'Michael Jay', 'Updated product \"A.I Logo\" (ID: 17) — no changes', '17', 'Update Product', 'Success', '2026-04-11 19:47:10'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-12 16:20:15'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-12 17:11:23'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-12 20:14:03'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-14 11:06:56'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-14 11:08:50'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-14 15:52:45'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-14 20:39:52'),
(0, 1, 'Michael Jay', 'Deleted product \"A.I Logo\" (ID: 17)', '17', 'Delete Product', 'Success', '2026-04-14 22:06:40'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-14 22:47:43'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-15 13:07:48'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-15 18:24:16'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-15 19:06:41'),
(0, NULL, 'admin@giftshop.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-04-15 19:59:55'),
(0, NULL, 'admin@giftshop.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-04-15 20:00:07'),
(0, NULL, 'admin123@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-04-15 20:00:14'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-15 22:07:53'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-15 22:44:54'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-16 11:13:14'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-16 11:21:04'),
(0, 1, 'Michael Jay', 'Updated product \"Couple Wine Set\" (ID: 5): Stock: 0 → 100', '5', 'Update Product', 'Success', '2026-04-16 11:23:20'),
(0, 1, 'Michael Jay', 'Updated product \"Success Gift Box\" (ID: 6): Stock: 0 → 10', '6', 'Update Product', 'Success', '2026-04-16 11:23:26'),
(0, 1, 'Michael Jay', 'Updated product \"Romantic Rose Box\" (ID: 7): Stock: 5 → 115', '7', 'Update Product', 'Success', '2026-04-16 11:23:29'),
(0, 1, 'Michael Jay', 'Updated product \"Executive Gift Set\" (ID: 8): Stock: 0 → 110', '8', 'Update Product', 'Success', '2026-04-16 11:23:32'),
(0, 1, 'Michael Jay', 'Updated product \"Anniversary Photo Album\" (ID: 10): Stock: 0 → 10', '10', 'Update Product', 'Success', '2026-04-16 11:23:38'),
(0, 1, 'Michael Jay', 'Updated product \"Baby Shower Favors\" (ID: 13): Stock: 2 → 21', '13', 'Update Product', 'Success', '2026-04-16 11:23:41'),
(0, 1, 'Michael Jay', 'Updated product \"Wedding Guest Book\" (ID: 14): Stock: 10 → 100', '14', 'Update Product', 'Success', '2026-04-16 11:23:45'),
(0, 1, 'Michael Jay', 'Updated product \"Valentine Chocolate Set\" (ID: 15): Stock: 0 → 100', '15', 'Update Product', 'Success', '2026-04-16 11:23:48'),
(0, 1, 'Michael Jay', 'Updated product \"Christmas Hamper\" (ID: 11): Stock: 8 → 58', '11', 'Update Product', 'Success', '2026-04-16 11:23:54'),
(0, 1, 'Michael Jay', 'Updated product \"Birthday Surprise Box\" (ID: 9): Stock: 0 → 80', '9', 'Update Product', 'Success', '2026-04-16 11:23:59'),
(0, 1, 'Michael Jay', 'Updated product \"Anniversary Photo Album\" (ID: 10): Stock: 10 → 110', '10', 'Update Product', 'Success', '2026-04-16 11:24:03'),
(0, 1, 'Michael Jay', 'Updated product \"Success Gift Box\" (ID: 6): Stock: 10 → 120', '6', 'Update Product', 'Success', '2026-04-16 11:24:10'),
(0, 1, 'Michael Jay', 'Updated product \"Spa Relaxation Set\" (ID: 4): Stock: 7 → 75', '4', 'Update Product', 'Success', '2026-04-16 11:24:14'),
(0, 1, 'Michael Jay', 'Updated product \"Wedding Memory Frame\" (ID: 3): Stock: 2 → 25', '3', 'Update Product', 'Success', '2026-04-16 11:24:17'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-16 11:30:13'),
(0, NULL, 'michael@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-04-16 12:07:41'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-16 12:08:04'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-16 13:43:35'),
(0, 1, 'Michael Jay', 'Updated product \"Baby Welcome Kit\" (ID: 1) — no changes', '1', 'Update Product', 'Success', '2026-04-16 15:13:48'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-16 19:12:50'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-17 15:39:22'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-18 22:39:34'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-24 21:04:43'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-24 21:11:41'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-25 11:02:37'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-30 18:13:08'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-30 18:33:17'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-04-30 22:03:01'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-01 08:48:36'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-01 09:50:54'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-01 10:02:01'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-01 10:03:24'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-01 10:05:12'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-01 19:22:15'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-01 21:25:09'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 18:45:00'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 20:18:44'),
(0, 1, 'michael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-02 22:13:23'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:13:37'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:28:31'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:28:44'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:31:24'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:33:18'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:36:31'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:38:03'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:41:16'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:42:27'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:45:25'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 22:59:45'),
(0, NULL, 'AdminMichael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-02 23:22:08'),
(0, NULL, 'AdminMichael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-02 23:22:18'),
(0, NULL, 'AdminMichael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-02 23:22:35'),
(0, NULL, 'AdminMichael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-02 23:22:43'),
(0, NULL, 'admin@giftshop.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-02 23:24:49'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 23:32:58'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-02 23:43:57'),
(0, NULL, 'michael@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-03 01:06:23'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-03 01:06:36'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-03 07:56:44'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-03 08:00:14'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-03 14:21:50'),
(0, NULL, 'System/Unknown', 'Updated product \"Baby Welcome Kit\" (ID: 1): Stock: 125 → 1', '1', 'Update Product', 'Success', '2026-05-03 14:22:29'),
(0, NULL, 'System/Unknown', 'Updated product \"Gourmet Chocolate Box\" (ID: 2): Stock: 190 → 1', '2', 'Update Product', 'Success', '2026-05-03 14:22:31'),
(0, NULL, 'System/Unknown', 'Updated product \"Wedding Memory Frame\" (ID: 3): Stock: 25 → 2', '3', 'Update Product', 'Success', '2026-05-03 14:22:35'),
(0, NULL, 'System/Unknown', 'Updated product \"Spa Relaxation Set\" (ID: 4): Stock: 75 → 7', '4', 'Update Product', 'Success', '2026-05-03 14:22:37'),
(0, NULL, 'System/Unknown', 'Updated product \"Couple Wine Set\" (ID: 5): Stock: 100 → 10', '5', 'Update Product', 'Success', '2026-05-03 14:22:39'),
(0, NULL, 'System/Unknown', 'Updated product \"Romantic Rose Box\" (ID: 7): Stock: 115 → 11', '7', 'Update Product', 'Success', '2026-05-03 14:22:43'),
(0, NULL, 'System/Unknown', 'Updated product \"Anniversary Photo Album\" (ID: 10): Stock: 110 → 11', '10', 'Update Product', 'Success', '2026-05-03 14:22:45'),
(0, NULL, 'System/Unknown', 'Updated product \"Birthday Surprise Box\" (ID: 9): Stock: 80 → 8', '9', 'Update Product', 'Success', '2026-05-03 14:22:48'),
(0, NULL, 'AdminMichael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-03 20:14:36'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-03 20:14:46'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-03 20:18:51'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-03 22:24:22'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-03 22:41:47'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-04 08:53:32'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-04 10:22:59'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-06 10:20:37'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-06 17:01:22'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-07 20:27:05'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-08 15:55:26'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-08 16:49:19'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-08 16:49:37'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-08 17:00:51'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-08 20:14:04'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-10 20:42:29'),
(0, NULL, 'jaymichaelmontemarcastillo@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-10 20:43:26'),
(0, NULL, 'michael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-10 20:43:35'),
(0, NULL, 'jaymichaelmontemarcastillo@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-10 20:43:48'),
(0, NULL, 'michael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-10 20:44:00'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-10 20:46:20'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-10 20:53:39'),
(0, 1, 'Michael Jay', 'Password reset via forgot password', NULL, 'Account', 'Success', '2026-05-10 20:55:42'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-10 20:55:53'),
(0, 1, 'Michael Jay', 'Password reset via forgot password', NULL, 'Account', 'Success', '2026-05-10 20:56:58'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-10 20:57:17'),
(0, 1, 'Michael Jay', 'Password reset via forgot password', NULL, 'Account', 'Success', '2026-05-10 21:22:41'),
(0, NULL, 'jaymichaelcastillo18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-11 19:41:26'),
(0, NULL, 'jaymichaelcastillo18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-11 19:41:46'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-11 19:41:54'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-11 21:43:02'),
(0, NULL, 'jaymichaelcastillo18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-14 10:07:22'),
(0, NULL, 'jaymichaelcastillo18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-14 10:07:42'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-14 10:07:53'),
(0, NULL, 'michael18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-22 22:05:11'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-22 22:05:20'),
(0, 1, 'Michael Jay', 'Password reset via forgot password', NULL, 'Account', 'Success', '2026-05-22 22:07:10'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-22 22:16:56'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-23 20:55:01'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-23 23:34:30'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-24 20:09:30'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-24 20:54:27'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-24 21:03:08'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-24 21:19:30'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-24 21:19:59'),
(0, 1, 'jaymichaelmontemarcastillo@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-25 20:40:53'),
(0, 1, 'Michael Jay', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-25 20:41:04'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-25 21:05:49'),
(0, NULL, 'jaymichaelcastillo18@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-26 18:42:54'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-26 18:43:06'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-27 02:08:26'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-27 09:58:54'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-27 14:34:51'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-27 15:46:11'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-27 22:25:54'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-28 09:50:41'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-28 12:43:13'),
(0, NULL, 'jaymichaelcastillomontemar@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-28 15:38:25'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-28 15:38:32'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-28 15:42:22'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-28 17:01:15'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-28 17:03:41'),
(0, 1, 'jaymichaelmontemarcastillo@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-05-28 21:36:10'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-28 21:36:16'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-29 15:30:02'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-30 08:46:04'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-30 09:15:03'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-30 10:02:52'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-30 22:07:22'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-30 23:00:45'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-03 19:59:36'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-03 22:00:20'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-04 14:14:04'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-04 14:38:19'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-05 19:45:10'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-05 19:49:15'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-08 21:07:47'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-15 14:41:04'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-15 21:45:57'),
(0, NULL, 'anythingInside@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-06-22 10:56:04'),
(0, NULL, 'anythingInside@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-06-22 10:56:21'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-22 10:56:38'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-22 11:40:48'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-22 12:44:29'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-23 21:22:30'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-24 21:41:29'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-24 22:36:59'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-24 22:37:33'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-26 21:31:39'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-06-26 23:02:35'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-01 16:33:38'),
(0, NULL, 'jaymichaelcastillomontemar@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-07-01 17:05:06'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-01 17:05:16'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-02 13:37:29'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-03 16:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminID` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('Admin','Finance','Staff') NOT NULL DEFAULT 'Admin',
  `ProfilePicture` varchar(255) DEFAULT NULL,
  `AccountStatus` enum('Active','Disabled') DEFAULT 'Active',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `LastLogin` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminID`, `FullName`, `Email`, `Password`, `Role`, `ProfilePicture`, `AccountStatus`, `CreatedAt`, `UpdatedAt`, `LastLogin`) VALUES
(1, 'Jay Michael', 'jaymichaelmontemarcastillo@gmail.com', '$2y$10$BIuRy0PwOpabNghRQAoHXehVJjjpjvlCDhsCSY6pGJS3EokDpM2iq', 'Admin', 'uploads/admins/1774715140_5010eb1b-7cfe-4f7b-8a46-174d4127c9ed.jfif', 'Active', '2026-03-27 09:06:30', '2026-07-03 08:06:19', '2026-07-03 08:06:19'),
(6, 'admin12345', 'admin12345@gmail.com', '$2y$10$rm2OBvPnwxbEBtT9yqzwcOHH16K8.uVR6EbdOcNJiBLzQ7MkFA08W', 'Staff', NULL, 'Active', '2026-06-22 03:49:57', '2026-06-24 14:03:12', NULL),
(7, 'michael18', 'AdminMichael18@gmail.com', '$2y$10$ndy4YsJg3J1wc5vqm5kkOeFlyfw4k24/qPI7UwoZy0/dla4B2uP8m', 'Finance', NULL, 'Active', '2026-06-22 03:49:58', '2026-06-24 14:03:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `audit_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `audit_id`, `action`, `admin_id`, `details`, `created_at`) VALUES
(1, 15, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"logged_count\":0,\"total_amount\":1050}', '2026-05-27 10:34:34'),
(2, 32, 'create', 1, '{\"items_count\":1,\"materials_count\":0,\"logged_count\":0,\"total_amount\":100}', '2026-05-27 11:12:23'),
(3, 38, 'create', 1, '{\"items_count\":2,\"materials_count\":2,\"rejects_count\":1,\"logged_count\":2,\"total_amount\":70}', '2026-05-27 15:36:36'),
(4, 39, 'create', 1, '{\"items_count\":2,\"materials_count\":1,\"rejects_count\":1,\"logged_count\":1,\"total_amount\":5000}', '2026-05-27 16:09:36'),
(5, 40, 'create', 1, '{\"items_count\":2,\"materials_count\":3,\"rejects_count\":2,\"logged_count\":3,\"total_amount\":1050}', '2026-05-27 16:35:49'),
(6, 41, 'create', 1, '{\"items_count\":2,\"materials_count\":3,\"rejects_count\":2,\"logged_count\":3,\"total_amount\":1050}', '2026-05-27 17:16:24'),
(7, 42, 'create', 1, '{\"items_count\":2,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":1,\"total_amount\":950}', '2026-05-28 07:24:14'),
(8, 43, 'create', 1, '{\"items_count\":1,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":1,\"total_amount\":1050}', '2026-05-28 07:44:40'),
(9, 44, 'create', 1, '{\"items_count\":4,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":1,\"total_amount\":47100}', '2026-05-28 08:01:57'),
(10, 45, 'create', 1, '{\"items_count\":3,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":2400}', '2026-05-28 08:22:45'),
(11, 46, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":1050}', '2026-05-28 08:24:10'),
(12, 47, 'create', 1, '{\"items_count\":3,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":2400}', '2026-05-28 08:27:12'),
(13, 48, 'create', 1, '{\"items_count\":4,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":2400}', '2026-05-28 08:29:01'),
(14, 49, 'create', 1, '{\"items_count\":1,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":950}', '2026-05-28 08:31:06'),
(15, 50, 'create', 1, '{\"items_count\":3,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":2400,\"total_material_cost\":0,\"profit\":2400}', '2026-05-28 08:44:51'),
(16, 51, 'create', 1, '{\"items_count\":3,\"materials_count\":3,\"rejects_count\":0,\"logged_count\":3,\"total_amount\":2400,\"total_material_cost\":410,\"profit\":1990}', '2026-05-28 08:45:20'),
(17, 52, 'create', 1, '{\"items_count\":3,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":2400,\"total_material_cost\":0,\"profit\":2400}', '2026-05-28 09:18:25'),
(18, 53, 'create', 1, '{\"items_count\":2,\"materials_count\":3,\"rejects_count\":2,\"logged_count\":3,\"total_amount\":1050,\"total_material_cost\":336,\"profit\":616}', '2026-05-28 09:35:00'),
(19, 54, 'create', 1, '{\"items_count\":0,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":1,\"total_amount\":0,\"total_material_cost\":44800,\"profit\":-44800}', '2026-05-28 09:43:05'),
(20, 55, 'create', 1, '{\"items_count\":2,\"materials_count\":2,\"rejects_count\":1,\"logged_count\":2,\"total_amount\":500,\"total_material_cost\":395,\"profit\":85}', '2026-05-29 09:30:16'),
(21, 56, 'create', 1, '{\"items_count\":2,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":1,\"total_amount\":100,\"total_material_cost\":90,\"profit\":10}', '2026-05-29 10:49:14'),
(22, 57, 'create', 1, '{\"items_count\":2,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":1,\"total_amount\":450,\"total_material_cost\":100,\"profit\":350}', '2026-05-29 12:53:48'),
(23, 58, 'create', 1, '{\"items_count\":2,\"materials_count\":1,\"rejects_count\":1,\"logged_count\":1,\"total_amount\":450,\"total_material_cost\":225,\"profit\":135}', '2026-05-29 12:56:36'),
(24, 59, 'create', 1, '{\"items_count\":2,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":1,\"total_amount\":450,\"total_material_cost\":405,\"profit\":45}', '2026-05-29 12:58:50'),
(25, 60, 'create', 1, '{\"items_count\":3,\"materials_count\":2,\"rejects_count\":0,\"logged_count\":2,\"total_amount\":94,\"total_material_cost\":93.56,\"profit\":0.4399999999999977}', '2026-05-29 13:04:08'),
(26, 61, 'create', 1, '{\"items_count\":3,\"materials_count\":2,\"rejects_count\":0,\"logged_count\":2,\"total_amount\":425,\"total_material_cost\":425,\"profit\":0}', '2026-05-29 15:03:30'),
(27, 62, 'create', 1, '{\"items_count\":3,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":100,\"total_material_cost\":0,\"profit\":100}', '2026-05-29 15:36:20'),
(28, 63, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":56000,\"total_material_cost\":0,\"profit\":56000}', '2026-05-29 15:59:35'),
(29, 64, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":56000,\"total_material_cost\":0,\"profit\":56000}', '2026-05-29 16:00:46'),
(30, 65, 'create', 1, '{\"items_count\":1,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":950,\"total_material_cost\":0,\"profit\":950}', '2026-05-29 16:08:20'),
(31, 66, 'create', 1, '{\"items_count\":3,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":1700,\"total_material_cost\":0,\"profit\":1700}', '2026-05-29 16:11:42'),
(32, 67, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":1050,\"total_material_cost\":0,\"profit\":1050}', '2026-05-29 16:14:38'),
(33, 68, 'create', 1, '{\"items_count\":7,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":11950,\"total_material_cost\":0,\"profit\":11950}', '2026-05-29 16:19:42'),
(34, 69, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":0,\"total_material_cost\":0,\"profit\":0}', '2026-05-29 16:24:29'),
(35, 70, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":0,\"total_material_cost\":0,\"profit\":0}', '2026-05-29 16:29:19'),
(36, 71, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":1000,\"total_material_cost\":0,\"profit\":1000}', '2026-05-29 16:33:36'),
(37, 72, 'create', 1, '{\"items_count\":4,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":3650,\"total_material_cost\":0,\"profit\":3650}', '2026-05-29 16:34:24'),
(38, 73, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":160,\"total_material_cost\":0,\"profit\":160}', '2026-05-29 16:36:49'),
(39, 74, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":0,\"total_material_cost\":0,\"profit\":0}', '2026-05-29 16:44:05'),
(40, 75, 'create', 1, '{\"items_count\":2,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":1,\"total_amount\":1050.21,\"total_material_cost\":300,\"profit\":750.21}', '2026-05-29 16:57:19'),
(41, 76, 'create', 1, '{\"items_count\":2,\"materials_count\":3,\"rejects_count\":2,\"logged_count\":3,\"total_amount\":1050,\"total_material_cost\":336,\"profit\":616}', '2026-05-29 17:08:15'),
(42, 77, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":112000,\"total_material_cost\":0,\"profit\":112000}', '2026-05-29 17:21:04'),
(43, 78, 'create', 1, '{\"items_count\":2,\"materials_count\":0,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":1050,\"total_material_cost\":0,\"profit\":1050}', '2026-05-29 17:26:56'),
(44, 79, 'create', 1, '{\"items_count\":2,\"materials_count\":2,\"rejects_count\":1,\"logged_count\":2,\"total_amount\":510,\"total_material_cost\":333.45,\"profit\":162.60000000000002}', '2026-05-29 17:32:31'),
(45, 80, 'create', 1, '{\"items_count\":2,\"materials_count\":2,\"rejects_count\":2,\"logged_count\":2,\"total_amount\":550,\"total_material_cost\":123,\"profit\":412.2}', '2026-05-30 06:42:08'),
(46, 81, 'create', 1, '{\"items_count\":0,\"materials_count\":1,\"rejects_count\":0,\"logged_count\":0,\"total_amount\":0,\"total_material_cost\":0,\"total_overhead\":0,\"profit\":0}', '2026-06-03 13:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `bom_audit`
--

CREATE TABLE `bom_audit` (
  `id` int(11) NOT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Store items (name only)' CHECK (json_valid(`items`)),
  `materials` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Store material costs' CHECK (json_valid(`materials`)),
  `rejects` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Store reject costs' CHECK (json_valid(`rejects`)),
  `quotation_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Store quotation items with prices' CHECK (json_valid(`quotation_items`)),
  `signatures` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Store created_by, audited_by, acknowledged_by, audit_date' CHECK (json_valid(`signatures`)),
  `status` enum('draft','completed') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_completed` tinyint(1) DEFAULT 0,
  `total_material_cost` decimal(12,2) DEFAULT 0.00,
  `total_reject_cost` decimal(12,2) DEFAULT 0.00,
  `total_amount_due` decimal(12,2) DEFAULT 0.00,
  `profit` decimal(12,2) DEFAULT 0.00,
  `overhead_shop_rent` decimal(12,2) DEFAULT 0.00,
  `overhead_fixed_salaries` decimal(12,2) DEFAULT 0.00,
  `overhead_shop_utilities` decimal(12,2) DEFAULT 0.00,
  `overhead_subscriptions` decimal(12,2) DEFAULT 0.00,
  `overhead_machine_depreciation` decimal(12,2) DEFAULT 0.00,
  `overhead_maintenance_repair` decimal(12,2) DEFAULT 0.00,
  `overhead_marketing` decimal(12,2) DEFAULT 0.00,
  `overhead_electricity` decimal(12,2) DEFAULT 0.00,
  `total_overhead` decimal(12,2) DEFAULT 0.00,
  `overhead_per_hour` decimal(12,2) DEFAULT 0.00,
  `production_hours` decimal(10,2) DEFAULT 0.00,
  `total_cost_with_overhead` decimal(12,2) DEFAULT 0.00,
  `auto_compute` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom_audit`
--

INSERT INTO `bom_audit` (`id`, `quote_id`, `items`, `materials`, `rejects`, `quotation_items`, `signatures`, `status`, `created_at`, `updated_at`, `is_completed`, `total_material_cost`, `total_reject_cost`, `total_amount_due`, `profit`, `overhead_shop_rent`, `overhead_fixed_salaries`, `overhead_shop_utilities`, `overhead_subscriptions`, `overhead_machine_depreciation`, `overhead_maintenance_repair`, `overhead_marketing`, `overhead_electricity`, `total_overhead`, `overhead_per_hour`, `production_hours`, `total_cost_with_overhead`, `auto_compute`) VALUES
(6, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":0,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:00:27', '2026-05-27 10:00:27', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(7, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":49.99,\"total_amount\":1049.79}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:16:50', '2026-05-27 10:16:50', 1, 0.00, 0.00, 1049.79, 1049.79, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(8, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_price\":49.99,\"total_amount\":149.97}]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_cost\":11200,\"total_cost\":11200},{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":4,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:21:18', '2026-05-27 10:21:18', 1, 11200.00, 0.00, 149.97, -11050.03, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(9, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_price\":49.99,\"total_amount\":149.97}]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_cost\":11200,\"total_cost\":11200},{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":4,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:23:57', '2026-05-27 10:23:57', 1, 11200.00, 0.00, 149.97, -11050.03, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(15, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:34:34', '2026-05-27 10:34:34', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(32, NULL, '[{\"name\":\"Test\",\"quantity\":1,\"unit_price\":100,\"total_amount\":100}]', '[]', '[]', NULL, '{\"created_by\":\"Test\",\"audited_by\":\"Test\",\"acknowledged_by\":\"Test\"}', 'draft', '2026-05-27 11:12:23', '2026-05-27 11:12:23', 1, 0.00, 0.00, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(38, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":70,\"total_amount\":70}]', '[{\"id\":341,\"name\":\"Bamboo Notebook A5 Size\",\"quantity\":10,\"unit_cost\":51,\"total_cost\":510},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":9,\"unit_cost\":10,\"total_cost\":90}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 15:36:36', '2026-05-27 15:36:36', 1, 600.00, 20.00, 70.00, -550.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(39, NULL, '[{\"name\":\"Sport\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Sport\",\"quantity\":10,\"unit_price\":500,\"total_amount\":5000}]', '[{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":3,\"unit_cost\":59,\"total_cost\":177}]', '[{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":2,\"unit_cost\":59,\"total_cost\":118}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 16:09:36', '2026-05-27 16:09:36', 1, 177.00, 118.00, 5000.00, 4705.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(40, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":21,\"unit_cost\":2,\"total_cost\":42},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":2,\"unit_cost\":1,\"total_cost\":2}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 16:35:49', '2026-05-27 16:35:49', 1, 336.00, 98.00, 1050.00, 616.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(41, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":20,\"unit_cost\":2,\"total_cost\":40},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":3,\"unit_cost\":1,\"total_cost\":3}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 17:16:24', '2026-05-27 17:16:24', 1, 334.00, 99.00, 1050.00, 617.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(42, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950}]', '[{\"id\":385,\"name\":\"CUYI RC Glossy Photo Paper 4R 260 GSM\",\"quantity\":1,\"unit_cost\":1.75,\"total_cost\":1.75}]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 07:24:14', '2026-05-28 07:24:14', 1, 1.75, 0.00, 950.00, 948.25, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(43, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_cost\":0,\"total_cost\":0}]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 07:44:40', '2026-05-28 07:44:40', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(44, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":3,\"unit_price\":950,\"total_amount\":2850},{\"name\":\"Baby Shower Favors\",\"quantity\":9,\"unit_price\":750,\"total_amount\":6750},{\"name\":\"Baby Welcome Kit\",\"quantity\":10,\"unit_price\":1950,\"total_amount\":19500},{\"name\":\"Birthday Surprise Box\",\"quantity\":10,\"unit_price\":1800,\"total_amount\":18000}]', '[{\"id\":385,\"name\":\"CUYI RC Glossy Photo Paper 4R 260 GSM\",\"quantity\":1,\"unit_cost\":1.75,\"total_cost\":1.75}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:01:57', '2026-05-28 08:01:57', 1, 1.75, 0.00, 47100.00, 47098.25, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(45, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:22:45', '2026-05-28 08:22:45', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(46, NULL, '[{\"name\":\"Acrylic Key ChainPrintable PVC SheetHookPrint\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Key Chain<blockquote style=\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:24:10', '2026-05-28 08:24:10', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(47, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:27:12', '2026-05-28 08:27:12', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(48, NULL, '[{\"name\":\"Bamboo Notebook A5\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:29:01', '2026-05-28 08:29:01', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(49, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:31:06', '2026-05-28 08:31:06', 1, 0.00, 0.00, 950.00, 950.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(50, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:44:51', '2026-05-28 08:44:51', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(51, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[{\"id\":479,\"name\":\"A4 Black and White\",\"quantity\":2,\"unit_cost\":0,\"total_cost\":0},{\"id\":459,\"name\":\"Bamboo 500ml\",\"quantity\":2,\"unit_cost\":195,\"total_cost\":390},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:45:19', '2026-05-28 08:45:19', 1, 410.00, 0.00, 2400.00, 1990.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(52, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 09:18:25', '2026-05-28 09:18:25', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(53, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":21,\"unit_cost\":2,\"total_cost\":42},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":2,\"unit_cost\":1,\"total_cost\":2}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 09:35:00', '2026-05-28 09:35:00', 1, 336.00, 98.00, 1050.00, 616.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(54, NULL, '[]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":4,\"unit_cost\":11200,\"total_cost\":44800}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 09:43:05', '2026-05-28 09:43:05', 1, 44800.00, 0.00, 0.00, -44800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(55, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":10,\"unit_price\":50,\"total_amount\":500}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_cost\":10,\"total_cost\":100},{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":5,\"unit_cost\":59,\"total_cost\":295}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 09:30:16', '2026-05-29 09:30:16', 1, 395.00, 20.00, 500.00, 85.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(56, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":100,\"total_amount\":100}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":2,\"unit_cost\":45,\"total_cost\":90}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 10:49:14', '2026-05-29 10:49:14', 1, 90.00, 0.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(57, NULL, '[{\"name\":\"Audit for Quotation AI-0526-0003 - LSPU LBC\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_cost\":10,\"total_cost\":100}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:53:48', '2026-05-29 12:53:48', 1, 100.00, 0.00, 450.00, 350.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(58, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":5,\"unit_cost\":45,\"total_cost\":225}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":2,\"unit_cost\":45,\"total_cost\":90}]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:56:36', '2026-05-29 12:56:36', 1, 225.00, 90.00, 450.00, 135.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(59, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":9,\"unit_cost\":45,\"total_cost\":405}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:58:50', '2026-05-29 12:58:50', 1, 405.00, 0.00, 450.00, 45.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(60, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":0.44,\"total_amount\":44},{\"name\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":10,\"total_amount\":50}]', '[{\"id\":381,\"name\":\"Bond Paper  A4\",\"quantity\":99,\"unit_cost\":0.44,\"total_cost\":43.56},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_cost\":10,\"total_cost\":50}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 13:04:08', '2026-05-29 13:04:08', 1, 93.56, 0.00, 94.00, 0.44, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(61, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo ballpoint\",\"quantity\":20,\"unit_price\":10,\"total_amount\":200},{\"name\":\"A4 Colored\",\"quantity\":5,\"unit_price\":45,\"total_amount\":225}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":20,\"unit_cost\":10,\"total_cost\":200},{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":5,\"unit_cost\":45,\"total_cost\":225}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 15:03:30', '2026-05-29 15:03:30', 1, 425.00, 0.00, 425.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(62, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":10,\"total_amount\":100},{\"name\":\"Baseball BGC brand\",\"quantity\":0,\"unit_price\":59,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 15:36:20', '2026-05-29 15:36:20', 1, 0.00, 0.00, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(63, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":5,\"unit_price\":11200,\"total_amount\":56000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 15:59:35', '2026-05-29 15:59:35', 1, 0.00, 0.00, 56000.00, 56000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(64, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":5,\"unit_price\":11200,\"total_amount\":56000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:00:46', '2026-05-29 16:00:46', 1, 0.00, 0.00, 56000.00, 56000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(65, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:08:20', '2026-05-29 16:08:20', 1, 0.00, 0.00, 950.00, 950.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(66, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950},{\"name\":\"Baby Shower Favors\",\"quantity\":1,\"unit_price\":750,\"total_amount\":750}]', '[]', '[]', NULL, '{\"created_by\":\"Juan Dela Cruz\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-29 16:11:42', '2026-05-29 16:11:42', 1, 0.00, 0.00, 1700.00, 1700.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(67, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:14:38', '2026-05-29 16:14:38', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(68, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950},{\"name\":\"Baby Shower Favors\",\"quantity\":1,\"unit_price\":750,\"total_amount\":750},{\"name\":\"Baby Welcome Kit\",\"quantity\":1,\"unit_price\":1950,\"total_amount\":1950},{\"name\":\"Birthday Surprise Box\",\"quantity\":1,\"unit_price\":1800,\"total_amount\":1800},{\"name\":\"Christmas Hamper\",\"quantity\":1,\"unit_price\":3500,\"total_amount\":3500},{\"name\":\"Christmas Hamper\",\"quantity\":1,\"unit_price\":3000,\"total_amount\":3000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-29 16:19:42', '2026-05-29 16:19:42', 1, 0.00, 0.00, 11950.00, 11950.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(69, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_price\":11200,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:24:29', '2026-05-29 16:24:29', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(70, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo Notebook A5 Size\",\"quantity\":0,\"unit_price\":51,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"Art School\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:29:19', '2026-05-29 16:29:19', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(71, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":10,\"unit_price\":100,\"total_amount\":1000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:33:36', '2026-05-29 16:33:36', 1, 0.00, 0.00, 1000.00, 1000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(72, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950},{\"name\":\"Baby Shower Favors\",\"quantity\":1,\"unit_price\":750,\"total_amount\":750},{\"name\":\"Baby Welcome Kit\",\"quantity\":1,\"unit_price\":1950,\"total_amount\":1950}]', '[]', '[]', NULL, '{\"created_by\":\"Juan Dela Cruz\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-29 16:34:24', '2026-05-29 16:34:24', 1, 0.00, 0.00, 3650.00, 3650.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(73, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Calling Card B2B no laminate\",\"quantity\":8,\"unit_price\":20,\"total_amount\":160}]', '[]', '[]', NULL, '{\"created_by\":\"Art School\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:36:49', '2026-05-29 16:36:49', 1, 0.00, 0.00, 160.00, 160.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(74, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_price\":11200,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:44:05', '2026-05-29 16:44:05', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(75, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":21,\"unit_price\":50.01,\"total_amount\":1050.21}]', '[{\"id\":406,\"name\":\"Yasen Vinyl Glossy Photo Stickers  150 GSM\",\"quantity\":50,\"unit_cost\":6,\"total_cost\":300}]', '[]', NULL, '{\"created_by\":\"Juan Dela Cruz\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:57:19', '2026-05-29 16:57:19', 1, 300.00, 0.00, 1050.21, 750.21, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(76, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":590,\"name\":\"Printable PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":21,\"unit_cost\":2,\"total_cost\":42},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":590,\"name\":\"Printable PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":2,\"unit_cost\":1,\"total_cost\":2}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 17:08:15', '2026-05-29 17:08:15', 1, 336.00, 98.00, 1050.00, 616.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(77, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":10,\"unit_price\":11200,\"total_amount\":112000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 17:21:04', '2026-05-29 17:21:04', 1, 0.00, 0.00, 112000.00, 112000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(78, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic keychain square\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 17:26:56', '2026-05-29 17:26:56', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(79, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo Notebook A5 Size\",\"quantity\":10,\"unit_price\":51,\"total_amount\":510}]', '[{\"id\":378,\"name\":\"Sublimation Transfer Paper  A4\",\"quantity\":99,\"unit_cost\":1.55,\"total_cost\":153.45},{\"id\":342,\"name\":\"Spring Notebook A5\",\"quantity\":5,\"unit_cost\":36,\"total_cost\":180}]', '[{\"id\":378,\"name\":\"Sublimation Transfer Paper  A4\",\"quantity\":9,\"unit_cost\":1.55,\"total_cost\":13.95}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 17:32:31', '2026-05-29 17:32:31', 1, 333.45, 13.95, 510.00, 162.60, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(80, NULL, '[{\"name\":\"Calling card B2B laminated\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Calling card B2B laminated\",\"quantity\":1,\"unit_price\":550,\"total_amount\":550}]', '[{\"id\":372,\"name\":\"Calling card Paper  220 GSM\",\"quantity\":10,\"unit_cost\":2.5,\"total_cost\":25},{\"id\":397,\"name\":\"Photo Top Glossy\",\"quantity\":20,\"unit_cost\":4.9,\"total_cost\":98}]', '[{\"id\":372,\"name\":\"Calling card Paper  220 GSM\",\"quantity\":2,\"unit_cost\":2.5,\"total_cost\":5},{\"id\":397,\"name\":\"Photo Top Glossy\",\"quantity\":2,\"unit_cost\":4.9,\"total_cost\":9.8}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-30 06:42:08', '2026-05-30 06:42:08', 1, 123.00, 14.80, 550.00, 412.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(81, NULL, '[]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_cost\":11200,\"total_cost\":0}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-06-03 13:59:00', '2026-06-03 13:59:00', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(84, NULL, '[{\"name\":\"Ackrylic keychain round\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050},{\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":10,\"total_amount\":100}]', '[{\"id\":527,\"name\":\"Ackrylic keychain round\",\"quantity\":99,\"unit_cost\":50,\"total_cost\":4950}]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-06-03 15:04:25', '2026-06-03 15:04:25', 1, 4950.00, 0.00, 1150.00, -3800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(85, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Ackrylic keychain round\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050},{\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":10,\"total_amount\":100}]', '[{\"id\":527,\"name\":\"Ackrylic keychain round\",\"quantity\":0,\"unit_cost\":50,\"total_cost\":0}]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-06-03 15:11:13', '2026-06-03 15:11:13', 1, 0.00, 0.00, 1150.00, -449.99, 200.00, 200.00, 200.00, 200.00, 200.00, 199.99, 200.00, 200.00, 1599.99, 0.00, 0.00, 1599.99, 1),
(86, NULL, '[{\"name\":\"Photo Top Holographic Rainbow\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Photo Top Holographic Rainbow\",\"quantity\":20,\"unit_price\":7.1,\"total_amount\":142}]', '[{\"id\":398,\"name\":\"Photo Top Holographic Rainbow\",\"quantity\":20,\"unit_cost\":7.1,\"total_cost\":142}]', '[{\"id\":398,\"name\":\"Photo Top Holographic Rainbow\",\"quantity\":5,\"unit_cost\":7.1,\"total_cost\":35.5}]', NULL, '{\"created_by\":\"ABC Corp\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-06-05 11:53:00', '2026-06-05 11:53:00', 1, 142.00, 35.50, 142.00, -1635.50, 200.00, 200.00, 200.00, 200.00, 200.00, 200.00, 200.00, 200.00, 1600.00, 16.00, 100.00, 1777.50, 1),
(87, NULL, '[{\"name\":\"Audit for AI-0526-0012\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"Quotation System\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-06-24 14:00:38', '2026-06-24 14:00:38', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(88, NULL, '[{\"name\":\"Ackrylic keychain round\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Ackrylic keychain round\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050},{\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":10,\"total_amount\":100}]', '[]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-03 08:13:08', '2026-07-03 08:13:08', 1, 0.00, 0.00, 1150.00, 1150.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1),
(89, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":6,\"unit_price\":200,\"total_amount\":1200}]', '[]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-03 08:13:30', '2026-07-03 08:13:30', 1, 0.00, 0.00, 1200.00, 1200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Baby Shower', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
(2, 'Holiday', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
(3, 'Wedding', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
(5, 'Anniversary', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
(6, 'Valentine', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
(7, 'Corporate', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
(8, 'Graduation', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
(9, 'Thank You', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
(10, 'BirthDay', 'Testing Description Text', 'irth-ay', 'active', '2026-03-31 04:47:38', '2026-03-31 04:47:38'),
(12, 'Anniversary', 'Anniversary Description', 'nniversary', 'active', '2026-03-31 04:48:48', '2026-03-31 04:48:48');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_receipts`
--

CREATE TABLE `delivery_receipts` (
  `id` int(11) NOT NULL,
  `dr_number` varchar(50) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `order_no` varchar(50) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_address` text DEFAULT NULL,
  `client_phone` varchar(50) DEFAULT NULL,
  `delivered_by` varchar(255) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `items` longtext DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `pdf_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_receipts`
--

INSERT INTO `delivery_receipts` (`id`, `dr_number`, `quotation_id`, `order_no`, `client_name`, `client_address`, `client_phone`, `delivered_by`, `delivery_date`, `items`, `total_amount`, `pdf_url`, `created_at`, `created_by`) VALUES
(78, 'DR-2026-05-0078', 46, 'AI-0526-0002', 'LSPU LBC', '', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":\"0.44\",\"total\":\"44.00\"},{\"description\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":\"10.00\",\"total\":\"50.00\"}]', 94.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0078_20260529_114149.pdf', '2026-05-29 09:41:48', 1),
(79, 'DR-2026-05-0079', 46, 'AI-0526-0002', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":\"0.44\",\"total\":\"44.00\"},{\"description\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":\"10.00\",\"total\":\"50.00\"}]', 94.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0079_20260529_114233.pdf', '2026-05-29 09:42:32', 1),
(80, 'DR-2026-05-0080', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0080_20260529_124117.pdf', '2026-05-29 10:41:16', 1),
(81, 'DR-2026-05-0081', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0081_20260529_124931.pdf', '2026-05-29 10:49:30', 1),
(82, 'DR-2026-05-0082', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0082_20260529_144840.pdf', '2026-05-29 12:48:39', 1),
(83, 'DR-2026-05-0083', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0083_20260529_145256.pdf', '2026-05-29 12:52:54', 1),
(84, 'DR-2026-05-0084', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0084_20260529_145518.pdf', '2026-05-29 12:55:17', 1),
(85, 'DR-2026-05-0085', 47, 'AI-0526-0003', 'LSPU LBC', '', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0085_20260529_145824.pdf', '2026-05-29 12:58:22', 1),
(86, 'DR-2026-05-0086', 46, 'AI-0526-0002', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":\"0.44\",\"total\":\"44.00\"},{\"description\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":\"10.00\",\"total\":\"50.00\"}]', 94.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0086_20260529_150035.pdf', '2026-05-29 13:00:34', 1),
(88, 'DR-2026-05-0087', 48, 'AI-0526-0004', 'Jay Michael M. Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_price\":\"11200.00\",\"total\":\"0.00\"}]', 0.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0087_20260529_182417.pdf', '2026-05-29 16:24:16', 1),
(89, 'DR-2026-05-0088', 49, 'AI-0526-0005', 'Art School', '', '', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bamboo Notebook A5 Size\",\"quantity\":0,\"unit_price\":\"51.00\",\"total\":\"0.00\"}]', 0.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0088_20260529_182904.pdf', '2026-05-29 16:29:03', 1),
(90, 'DR-2026-05-0089', 19, 'AI-202605-0010', 'Juan Dela Cruz', '', '09395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":\"950.00\",\"total\":\"950.00\"},{\"description\":\"Baby Shower Favors\",\"quantity\":1,\"unit_price\":\"750.00\",\"total\":\"750.00\"},{\"description\":\"Baby Welcome Kit\",\"quantity\":1,\"unit_price\":\"1950.00\",\"total\":\"1950.00\"}]', 3650.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0089_20260529_183405.pdf', '2026-05-29 16:34:04', 1),
(91, 'DR-2026-05-0090', 50, 'AI-0526-0006', 'Art School', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Calling Card B2B no laminate\",\"quantity\":0,\"unit_price\":\"20.00\",\"total\":\"0.00\"}]', 0.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0090_20260529_183626.pdf', '2026-05-29 16:36:25', 1),
(92, 'DR-2026-05-0091', 50, 'AI-0526-0006', 'Art School', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Calling Card B2B no laminate\",\"quantity\":0,\"unit_price\":\"20.00\",\"total\":\"0.00\"}]', 0.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0091_20260529_183629.pdf', '2026-05-29 16:36:26', 1),
(93, 'DR-2026-05-0092', 51, 'AI-0526-0007', 'LSPU LBC', '', '', 'Jay Michael', '2026-05-29', '[{\"description\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_price\":\"11200.00\",\"total\":\"0.00\"}]', 0.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0092_20260529_184346.pdf', '2026-05-29 16:43:45', 1),
(95, 'DR-2026-05-0094', 53, 'AI-0526-0009', 'Jay Michael M. Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"6-in-1 Combo Heat Press Press\",\"quantity\":10,\"unit_price\":\"11200.00\",\"total\":\"112000.00\"}]', 112000.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0094_20260529_192031.pdf', '2026-05-29 17:20:30', 1),
(96, 'DR-2026-05-0095', 54, 'AI-0526-0010', 'Jay Michael M. Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Acrylic keychain square\",\"quantity\":21,\"unit_price\":\"50.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0095_20260529_192642.pdf', '2026-05-29 17:26:41', 1),
(97, 'DR-2026-05-0096', 54, 'AI-0526-0010', 'Jay Michael M. Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Acrylic keychain square\",\"quantity\":21,\"unit_price\":\"50.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0096_20260529_192859.pdf', '2026-05-29 17:28:58', 1),
(98, 'DR-2026-05-0097', 55, 'AI-0526-0011', 'LSPU LBC', '', '', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bamboo Notebook A5 Size\",\"quantity\":0,\"unit_price\":\"51.00\",\"total\":\"0.00\"}]', 0.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0097_20260529_193013.pdf', '2026-05-29 17:30:12', 1),
(99, 'DR-2026-05-0098', 58, 'AI-0526-0014', 'Organization Name', '', '+639395529749', 'Jay Michael', '2026-05-30', '[{\"description\":\"Ackrylic keychain round\",\"quantity\":21,\"unit_price\":\"50.00\",\"total\":\"1050.00\"},{\"description\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":\"10.00\",\"total\":\"100.00\"}]', 1150.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0098_20260530_081534.pdf', '2026-05-30 06:15:33', 1),
(100, 'DR-2026-05-0099', 60, 'AI-0526-0016', 'Organization Name', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-30', '[{\"description\":\"Calling card B2B laminated\",\"quantity\":1,\"unit_price\":\"550.00\",\"total\":\"550.00\"}]', 550.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0099_20260530_083144.pdf', '2026-05-30 06:31:43', 1),
(101, 'DR-2026-06-0001', 61, 'AI-0626-0001', 'ABC Corp', '', '09121231234', 'Jay Michael', '2026-06-05', '[{\"description\":\"Photo Top Holographic Rainbow\",\"quantity\":20,\"unit_price\":\"7.10\",\"total\":\"142.00\"}]', 142.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-06-0001_20260605_135125.pdf', '2026-06-05 11:51:24', 1),
(102, 'DR-2026-07-0001', 62, 'AI-0726-0001', 'Organization Name', 'Anos, Los Banos, Laguna', '+639395529749', 'Jay Michael', '2026-07-01', '[{\"description\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":6,\"unit_price\":\"200.00\",\"total\":\"1200.00\"}]', 1200.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0001_20260701_103606.pdf', '2026-07-01 08:36:03', 1),
(103, 'DR-2026-07-0002', 60, 'AI-0526-0016', 'Organization Name', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-07-03', '[{\"description\":\"Calling card B2B laminated\",\"quantity\":1,\"unit_price\":\"550.00\",\"total\":\"550.00\"}]', 550.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0002_20260703_101230.pdf', '2026-07-03 08:12:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `change_type` enum('add','subtract','order','return','adjust') NOT NULL,
  `quantity` int(11) NOT NULL,
  `previous_stock` int(11) DEFAULT 0,
  `new_stock` int(11) DEFAULT 0,
  `admin_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `audit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `material_type` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `is_product` tinyint(1) DEFAULT 0,
  `shop_stock` int(11) DEFAULT 0,
  `ph_stock` int(11) DEFAULT 0,
  `total_stock` int(11) DEFAULT 0,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `pieces_per_pack` int(11) DEFAULT 1,
  `unit_cost` decimal(10,4) DEFAULT 0.0000,
  `remarks` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(500) DEFAULT 'uploads/materials/default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `low_stock_threshold` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `material_name`, `sku`, `type`, `material_type`, `category`, `is_product`, `shop_stock`, `ph_stock`, `total_stock`, `total_cost`, `pieces_per_pack`, `unit_cost`, `remarks`, `description`, `image`, `created_at`, `updated_at`, `low_stock_threshold`) VALUES
(1113, 'ID Case Case', NULL, 'Accessories', NULL, NULL, 0, 40, 0, 40, 20.00, 1, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1114, 'Tote Plain Flat Portrait  8.5  by 10', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 38.00, 1, 38.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1115, 'Tote Plain Flat Portrait  12 by 14', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 58.00, 1, 58.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1116, 'Tote Plain Flat Portrait  13 by 16', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 66.00, 1, 66.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1117, 'Tote Plain with Base 12 by 14 by 3', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 68.00, 1, 68.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1118, 'Tote Plain with Base 13 by 15 by 3', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 78.00, 1, 78.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1119, 'Tote Plain Flat Portrait  10 by 12', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1120, 'Tote Plain Flat Colored 13 by 15', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 93.00, 1, 93.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1121, 'Tote Plainwith base and zipper Colored 12 by 14 by 3', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 120.00, 1, 120.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-26 14:39:33', 5),
(1122, 'Tote Plain Flat Colored 12 by 14', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 79.00, 1, 79.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1123, 'Tote Plain Flat with Zipper 12 by 14', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 79.00, 1, 79.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1124, 'Tote Plain Flat with Zipper 13 by 15', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 87.00, 1, 87.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1125, 'Button pins 2.25 inches', NULL, 'Button Pins', NULL, NULL, 0, 86, 0, 86, 300.00, 100, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1126, 'Baseball BGC brand', NULL, 'Cap', NULL, NULL, 0, 0, 0, 0, 59.00, 1, 59.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1127, 'Mesh BGC brand', NULL, 'Cap', NULL, NULL, 0, 0, 0, 0, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1128, 'Bucket BGC brand', NULL, 'Cap', NULL, NULL, 0, 0, 0, 0, 65.00, 1, 65.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1129, 'CUYI Cleaning Solution 100ml', NULL, 'Cleaning Solution', NULL, NULL, 0, 1, 0, 1, 40.00, 1, 40.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1130, 'UV Moisturizer 500 ml', NULL, 'Cleaning Solution', NULL, NULL, 0, 0, 1, 1, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1131, 'Itech Glossy Cold Laminating Photo Top Glossy', NULL, 'Cold Laminate', NULL, NULL, 0, 0, 40, 40, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1132, 'Itech Matte Cold Laminating Photo Top Matte', NULL, 'Cold Laminate', NULL, NULL, 0, 0, 99, 99, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1133, 'Itech Transparent Vinyl Stickers A4  A4', NULL, 'Cold Laminate', NULL, NULL, 0, 0, 60, 60, 165.00, 20, 8.2500, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1134, 'Photo Top - Canvas matte matte', NULL, 'Cold Laminate', NULL, NULL, 0, 0, 20, 20, 100.00, 20, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1135, 'Paper Cutter (Metal Base)', NULL, 'Cutters', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1136, 'PVC Die Cutter (ATM Size) 54 by 86cm', NULL, 'Cutters', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1137, 'PET film A3+ Roll Roll', NULL, 'DTF', NULL, NULL, 0, 0, 0, 0, 1650.00, 1, 1650.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1138, 'Powder Honjet Pack 1 KG', NULL, 'DTF', NULL, NULL, 0, 0, 1, 1, 320.00, 1, 320.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1139, 'Black 35x50', NULL, 'Ecobag', NULL, NULL, 0, 0, 10, 10, 360.00, 20, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1140, 'White 25x30', NULL, 'Ecobag', NULL, NULL, 0, 0, 40, 40, 190.00, 50, 3.8000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1141, 'White 25x35', NULL, 'Ecobag', NULL, NULL, 0, 0, 48, 48, 400.00, 50, 8.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1142, 'White 35x50', NULL, 'Ecobag', NULL, NULL, 0, 0, 15, 15, 360.00, 20, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1143, 'White with base Small- 8 by 11', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 175.00, 50, 3.5000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1144, 'White with base M- 10 by 12', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 200.00, 50, 4.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1145, 'White with base Large 12 by 16', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 250.00, 50, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1146, 'White with base XL- 14 by 18', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 300.00, 50, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1147, 'Flat Bag Large 12 by 16', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 250.00, 50, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1148, 'Ecobag Yellow 30x 38', NULL, 'Ecobag', NULL, NULL, 0, 0, 52, 52, 1300.00, 100, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1149, 'Long Brown Paper', NULL, 'Envelope', NULL, NULL, 0, 0, 0, 0, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1150, 'Short Brown Paper', NULL, 'Envelope', NULL, NULL, 0, 10, 0, 10, 110.00, 50, 2.2000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1151, 'Laminating Film A4 250 mic', NULL, 'Hot Laminate', NULL, NULL, 0, 15, 50, 65, 819.00, 100, 8.1900, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1152, 'Laminating Film Long 125 mic', NULL, 'Hot Laminate', NULL, NULL, 0, 0, 50, 50, 450.00, 100, 4.5000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1153, 'Cuyi Pigment Ink Black', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1154, 'Cuyi Pigment Ink Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1155, 'Cuyi Pigment Ink Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1156, 'Cuyi Pigment Ink Yellow', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1157, 'Cuyi Sublimation Ink Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1158, 'Cuyi Sublimation Ink Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1159, 'Cuyi Sublimation Ink Yellow', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1160, 'Cuyi Sublimation Ink Black', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1161, 'Cuyi UV Dye Black', NULL, 'Ink', NULL, NULL, 0, 1, 0, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1162, 'Cuyi UV Dye Cyan', NULL, 'Ink', NULL, NULL, 0, 1, 0, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1163, 'Cuyi UV Dye Light Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1164, 'Cuyi UV Dye Magenta', NULL, 'Ink', NULL, NULL, 0, 1, 0, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1165, 'Cuyi UV Dye Yellow', NULL, 'Ink', NULL, NULL, 0, 1, 0, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1166, 'Hansol Pigment Black', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1167, 'Hansol Pigment Light Cyan', NULL, 'Ink', NULL, NULL, 0, 2, 0, 2, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1168, 'Hansol Pigment Light Magenta', NULL, 'Ink', NULL, NULL, 0, 2, 0, 2, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1169, 'Hansol Pigment Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1170, 'Hansol Pigment Yellow', NULL, 'Ink', NULL, NULL, 0, 1, 1, 2, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1171, 'Hansol Pigment Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1172, 'InkPro DTF Ink 100ml White', NULL, 'Ink', NULL, NULL, 0, 2, 0, 2, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1173, 'InkPro DTF Ink 100ml Black', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1174, 'InkPro DTF Ink 100ml Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1175, 'InkPro DTF Ink 100ml Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1176, 'InkPro DTF Ink 100ml Yellow', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1177, 'InkPro DTF Ink 500ml White', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 750.00, 1, 750.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1178, 'UV Ink Black', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1179, 'UV Ink Clear', NULL, 'Ink', NULL, NULL, 0, 0, 4, 4, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1180, 'UV Ink Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1181, 'UV Ink Light Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1182, 'UV Ink Light Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1183, 'UV Ink Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1184, 'UV Ink White', NULL, 'Ink', NULL, NULL, 0, 0, 2, 2, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1185, 'UV Ink Yellow', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1186, 'UV Varnish Glossy', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1825.00, 1, 1825.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1187, 'Insert-in Keychain', NULL, 'Keychain', NULL, NULL, 0, 36, 0, 36, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1188, 'Acrylic Keychain 5x9 cm cm', NULL, 'Keychain', NULL, NULL, 0, 21, 0, 21, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1189, 'Wooden keychain  square', NULL, 'Keychain', NULL, NULL, 0, 45, 0, 45, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1190, '6-in-1 Combo Heat Press Press', NULL, 'Machines', NULL, NULL, 0, 0, 1, 1, 11200.00, 1, 11200.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1191, 'Laminator A3', NULL, 'Machines', NULL, NULL, 0, 1, 0, 1, 2280.00, 1, 2280.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1192, 'Mug Press Machine Dual Dual', NULL, 'Machines', NULL, NULL, 0, 0, 1, 1, 26620.00, 1, 26620.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1193, 'Button Pin Maker Keychain Molds', NULL, 'Machines', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1194, 'Magnetic Sheet with Adhesive  A4 0.5mm', NULL, 'Magnet', NULL, NULL, 0, 0, 50, 50, 180.00, 10, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1195, 'Magnetic Sheet with Adhesive  A4 1mm', NULL, 'Magnet', NULL, NULL, 0, 0, 10, 10, 298.00, 10, 29.8000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1196, 'Magnetic Sheet (Adhesive)', NULL, 'Magnet', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1197, 'Magnetic Sheet Non-Adhesive  A4 0.5mm', NULL, 'Magnet', NULL, NULL, 0, 1, 0, 1, 110.00, 10, 11.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1198, 'Ref Magnet ID size', NULL, 'Magnet', NULL, NULL, 0, 0, 14, 14, 300.00, 50, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1199, 'Wire Binder Binder', NULL, 'Metal', NULL, NULL, 0, 0, 38, 38, 380.00, 100, 3.8000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1200, 'Frosted / Clear Glass Mug Frosted', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 75.00, 1, 75.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1201, 'Inner Color Mug Assorted', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 1660.00, 24, 69.1667, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1202, 'Magic Mug (Matte/Glossy) Glossy', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 3040.00, 36, 84.4444, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1203, 'White Coated Mug  Subli White', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 1369.00, 36, 38.0278, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1204, 'Enamel 500ml', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 158.00, 1, 158.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1205, 'Ceramic Coffee V.1 cork base', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 134.00, 1, 134.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1206, 'Ceramic Coffee V.2 with wooden lid', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 139.00, 1, 139.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1207, 'Egg Tumbler 300ml', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 119.00, 1, 119.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1208, 'Bamboo Notebook A5 Size', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 51.00, 1, 51.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1209, 'Spring Notebook A5', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1210, 'Leatherette Notebook Softbound A5', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 54.00, 1, 54.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1211, 'Leatherette Notebook Hardbound  A5', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 143.00, 1, 143.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1212, 'Moleskin-style Strap Diary A5', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 52.00, 1, 52.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1213, 'Moleskin-style Strap Diary A6', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 39.00, 1, 39.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1214, 'Mesh bag Big Big 8 by 12', NULL, 'Packaging', NULL, NULL, 0, 100, 2, 102, 349.00, 50, 6.9800, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1215, 'Mesh bag Small Small 6 by 9', NULL, 'Packaging', NULL, NULL, 0, 100, 2, 102, 230.00, 50, 4.6000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1216, 'Paper Bag  13.5x20.5x8.5', NULL, 'Packaging', NULL, NULL, 0, 0, 72, 72, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1217, 'Paper Bag  40x31.5x5', NULL, 'Packaging', NULL, NULL, 0, 0, 2, 2, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1218, 'Plastic Bag With Handle 12x15x6', NULL, 'Packaging', NULL, NULL, 0, 0, 1, 1, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1219, 'Plastic Bag With Handle 13x15x7', NULL, 'Packaging', NULL, NULL, 0, 0, 1, 1, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1220, 'Plastic Bag With Handle 13x20x7', NULL, 'Packaging', NULL, NULL, 0, 0, 2, 2, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1221, 'Plastic Bag With Handle 21x28x8', NULL, 'Packaging', NULL, NULL, 0, 0, 3, 3, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1222, 'Plastic Bag With Handle 24x32x10', NULL, 'Packaging', NULL, NULL, 0, 0, 2, 2, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1223, 'PP Plastic 10x13', NULL, 'Packaging', NULL, NULL, 0, 41, 100, 141, 59.00, 50, 1.1800, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1224, 'PP Plastic 11x9', NULL, 'Packaging', NULL, NULL, 0, 0, 0, 0, 58.00, 50, 1.1600, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1225, 'PP Plastic 3.5x5', NULL, 'Packaging', NULL, NULL, 0, 0, 0, 0, 33.00, 100, 0.3300, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1226, 'PP Plastic 3x5', NULL, 'Packaging', NULL, NULL, 0, 0, 0, 0, 31.00, 100, 0.3100, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1227, 'PP Plastic 4.5x6', NULL, 'Packaging', NULL, NULL, 0, 0, 200, 200, 68.00, 100, 0.6800, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1228, 'PP Plastic 4x6', NULL, 'Packaging', NULL, NULL, 0, 0, 0, 0, 47.00, 100, 0.4700, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1229, 'PP Plastic 6x8', NULL, 'Packaging', NULL, NULL, 0, 50, 200, 250, 95.00, 100, 0.9500, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1230, 'PP Plastic 9x12', NULL, 'Packaging', NULL, NULL, 0, 47, 143, 190, 59.00, 50, 1.1800, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1231, 'Brown Paper A3 A3', NULL, 'Packaging', NULL, NULL, 0, 0, 15, 15, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1232, 'Zip Lock 25x35 25x35', NULL, 'Packaging', NULL, NULL, 0, 0, 80, 80, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1233, 'Corrugated Brown T15', NULL, 'Pakaging', NULL, NULL, 0, 0, 4, 4, 173.00, 5, 34.6000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1234, 'Corrugated White T12', NULL, 'Pakaging', NULL, NULL, 0, 0, 10, 10, 143.00, 5, 28.6000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1235, 'Corrugated White T13', NULL, 'Pakaging', NULL, NULL, 0, 0, 5, 5, 114.00, 5, 22.8000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1236, 'Corrugated White Small', NULL, 'Pakaging', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1237, 'Coffee Contaimer/Plastic 14x10 White  White', NULL, 'Pakaging', NULL, NULL, 0, 0, 49, 49, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1238, 'Calling card Paper 300 GSM', NULL, 'Paper', NULL, NULL, 0, 3, 486, 489, 153.00, 50, 3.0600, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1239, 'Calling card Paper  220 GSM', NULL, 'Paper', NULL, NULL, 0, 46, 0, 46, 125.00, 50, 2.5000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1240, 'Non-waterproof Vinyl  Sticker A4 Matte', NULL, 'Paper', NULL, NULL, 0, 11, 18, 29, 150.00, 22, 6.8182, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1241, 'Matte Photo Sticker Paper  135 gsm', NULL, 'Paper', NULL, NULL, 0, 20, 40, 60, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1242, 'Matte Sticker Paper 165 gsm', NULL, 'Paper', NULL, NULL, 0, 0, 20, 20, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1243, 'No Back Print Photo Paper 230gsm', NULL, 'Paper', NULL, NULL, 0, 9, 0, 9, 42.00, 20, 2.1000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1244, 'No Back Print Photo Paper 250 gsm', NULL, 'Paper', NULL, NULL, 0, 0, 0, 0, 42.00, 20, 2.1000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1245, 'Sublimation Transfer Paper  A4', NULL, 'Paper', NULL, NULL, 0, 0, 300, 300, 155.00, 100, 1.5500, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1246, 'Transparent Non-waterproof Vinyl  Sticker A4', NULL, 'Paper', NULL, NULL, 0, 0, 35, 35, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1247, 'White Matte Vinyl  Sticker A4', NULL, 'Paper', NULL, NULL, 0, 0, 22, 22, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1248, 'Bond Paper  A4', NULL, 'Paper', NULL, NULL, 0, 400, 500, 900, 220.00, 500, 0.4400, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1249, 'Bond Paper  Letter', NULL, 'Paper', NULL, NULL, 0, 200, 0, 200, 210.00, 500, 0.4200, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1250, 'Bond Paper  Long', NULL, 'Paper', NULL, NULL, 0, 850, 0, 850, 232.00, 500, 0.4640, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1251, 'C2S Coated A3 230 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 31, 31, 229.00, 50, 4.5800, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1252, 'CUYI RC Glossy Photo Paper 4R 260 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 20, 20, 35.00, 20, 1.7500, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1253, 'CUYI RC Pearl Holographic Photo Paper 260 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 20, 20, 40.00, 20, 2.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1254, 'CUYI RC Satin Photo Paper  A4 200 GSM', NULL, 'Paper', NULL, NULL, 0, 2, 40, 42, 135.00, 20, 6.7500, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1255, 'CUYI RC Satin Photo Paper  A4 260 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 60, 60, 170.00, 20, 8.5000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1256, 'CUYI RC Woven Photo Paper 4R 260 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 20, 20, 47.00, 20, 2.3500, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1257, 'Foldcote Foldcote', NULL, 'Paper', NULL, NULL, 0, 0, 24, 24, 216.00, 25, 8.6400, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1258, 'Glossy Photo Sticker  A4 135 GSM', NULL, 'Paper', NULL, NULL, 0, 3, 70, 73, 68.00, 20, 3.4000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1259, 'Glossy Photo Sticker  A5 90 gsm', NULL, 'Paper', NULL, NULL, 0, 15, 0, 15, 68.00, 20, 3.4000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1260, 'L&C Double sided high glossy photo paper  A4 240 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 49, 49, 149.00, 50, 2.9800, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1261, 'L&C High glossy photo paper  A4 230 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 12, 12, 48.00, 20, 2.4000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1262, 'PET Card Sheets A4 PVC Sheets', NULL, 'Paper', NULL, NULL, 0, 0, 34, 34, 950.00, 50, 19.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1263, 'Photo Top Glitter', NULL, 'Paper', NULL, NULL, 0, 0, 0, 0, 128.00, 20, 6.4000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1264, 'Photo Top Glossy', NULL, 'Paper', NULL, NULL, 0, 1, 100, 101, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1265, 'Photo Top Holographic Rainbow', NULL, 'Paper', NULL, NULL, 0, 17, 20, 37, 142.00, 20, 7.1000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1266, 'Photo Top Matte', NULL, 'Paper', NULL, NULL, 0, 14, 180, 194, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1267, 'Sublimation Paper (100gsm) Lanyard', NULL, 'Paper', NULL, NULL, 0, 0, 40, 40, 500.00, 100, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1268, 'Suki Vellum Board - Matte Letter 210gsm Matte Letter 210gsm', NULL, 'Paper', NULL, NULL, 0, 20, 0, 20, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1269, 'Vinyl Sticker Waterproof Matte', NULL, 'Paper', NULL, NULL, 0, 17, 0, 17, 200.00, 20, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1270, 'Vinyl Sticker Waterproof  Transparent', NULL, 'Paper', NULL, NULL, 0, 0, 0, 0, 240.00, 20, 12.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1271, 'Newsprint Letter Letter', NULL, 'Paper', NULL, NULL, 0, 200, 0, 200, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1272, 'Ultima matte Vinyl Sticker A4 A4', NULL, 'Paper', NULL, NULL, 0, 0, 11, 11, 150.00, 20, 7.5000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1273, 'Yasen Vinyl Glossy Photo Stickers  150 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 340, 340, 120.00, 20, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1274, 'Metal Pen - Black Black', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1275, 'Metal Pen - Blue Blue', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1276, 'Metal Pen - Green Green', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1277, 'Metal Pen - White White', NULL, 'Pens', NULL, NULL, 0, 0, 138, 138, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1278, 'Plastic White ballpoint', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 319.00, 50, 6.3800, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1279, 'Bamboo ballpoint', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1280, 'Double Sided Glossy Photo Paper A4 120 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 3, 0, 3, 99.00, 50, 1.9800, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1281, 'Double Sided Glossy Photo Paper A4 200 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 28, 0, 28, 128.00, 50, 2.5600, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1282, 'Double Sided Glossy Photo Paper A4 250 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 140.00, 50, 2.8000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-07-01 08:59:50', 5),
(1283, 'Double Sided Glossy Photo Paper A4 160 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 120.00, 50, 2.4000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1284, 'Double Sided Glossy Photo Paper A4 220 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 135.00, 50, 2.7000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1285, 'Double Sided Glossy Photo Paper A4 300 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 155.00, 50, 3.1000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1286, 'Tree Type Glossy Paper (180/230gsm)', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1287, 'ID Card Card', NULL, 'Plastic', NULL, NULL, 0, 84, 0, 84, 5.00, 1, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1288, 'UV Sticker film A A', NULL, 'UV', NULL, NULL, 0, 0, 0, 0, 800.00, 20, 40.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-07-01 08:59:50', 5),
(1289, 'One-Hole Hang Tag Puncher', NULL, 'Puncher', NULL, NULL, 0, 1, 0, 1, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1290, 'Rubber loop Black', NULL, 'Rubber', NULL, NULL, 0, 28, 0, 28, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1291, 'Rubber loop Transparent', NULL, 'Rubber', NULL, NULL, 0, 10, 100, 110, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1292, 'Rubber loop  Blue', NULL, 'Rubber', NULL, NULL, 0, 9, 0, 9, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1293, 'Rubber loop White', NULL, 'Rubber', NULL, NULL, 0, 4, 79, 83, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1294, 'Rubber loop  Yellow', NULL, 'Rubber', NULL, NULL, 0, 9, 0, 9, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1295, 'Polo Shirt Blue Corner Colored XS- Large', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 241.00, 1, 241.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1296, 'Polo Shirt Blue Corner White XS- Large', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 192.00, 1, 192.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1297, 'Polo Shirt Blue Corner Colored XL-2XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 276.00, 1, 276.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1298, 'Polo Shirt Blue Corner Colored 3XL-4XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 310.00, 1, 310.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1299, 'Polo Shirt Blue Corner White XL-2XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 216.00, 1, 216.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1300, 'Polo Shirt Blue Corner White 3XL-4XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 255.00, 1, 255.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1301, 'Tshirt Gildan Colored XS- XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 199.00, 1, 199.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1302, 'Tshirt Gildan White XS- XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 171.00, 1, 171.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1303, 'Tshirt Gildan Colored 2XL-3XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 240.00, 1, 240.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1304, 'Tshirt Yalex White XS- Large', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 130.00, 1, 130.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1305, 'Tshirt Yalex White XL-2XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 145.00, 1, 145.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1306, 'Tshirt Yalex White 3XL-5XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1307, 'Tshirt Yalex Colored XS- Large', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 141.00, 1, 141.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1308, 'Tshirt Yalex Colored XL-2XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 165.00, 1, 165.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1309, 'Tshirt Yalex Colored 3XL-5XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 180.00, 1, 180.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1310, 'Tshirt Gildan White 2XL-3XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 201.00, 1, 201.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1311, 'Sintra Board  A2 3mm', NULL, 'Signage', NULL, NULL, 0, 0, 5, 5, 320.00, 5, 64.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1312, 'Sintra Board  A3 3mm', NULL, 'Signage', NULL, NULL, 0, 0, 9, 9, 300.00, 10, 30.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1313, 'Sintra Board  A4 1.5mm', NULL, 'Signage', NULL, NULL, 0, 0, 42, 42, 160.00, 10, 16.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1314, 'Sintra Board  A4 3mm', NULL, 'Signage', NULL, NULL, 0, 0, 18, 18, 200.00, 10, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1315, 'Canvas Board 10 x 10', NULL, 'Signage', NULL, NULL, 0, 2, 0, 2, 39.00, 2, 19.5000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1316, 'Canvas Board 20 x 30', NULL, 'Signage', NULL, NULL, 0, 0, 4, 4, 44.00, 1, 44.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1317, 'Canvas Board 25 x 30', NULL, 'Signage', NULL, NULL, 0, 0, 4, 4, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1318, 'Canvas Board 30 x 40', NULL, 'Signage', NULL, NULL, 0, 0, 3, 3, 67.00, 1, 67.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1319, 'Sintra Board - A1 3mm 3mm', NULL, 'Signage', NULL, NULL, 0, 0, 5, 5, 640.00, 2, 320.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1320, 'Sintra Board - A4 5mm 5mm', NULL, 'Signage', NULL, NULL, 0, 0, 0, 0, 200.00, 10, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1321, 'Printable PVC Cards (for Keytags)', NULL, 'Specialty', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1322, 'Printable PVC Pre-Cut Cards Cards', NULL, 'Specialty', NULL, NULL, 0, 0, 0, 0, 200.00, 50, 4.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1323, 'Vinyl  Sticker Roll Roll', NULL, 'Sticker', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1324, 'Thermal Tape  (20mm x 33m)', NULL, 'Tape', NULL, NULL, 0, 0, 2, 2, 59.00, 1, 59.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1325, 'Thermal Tape  (6mm x 33m)', NULL, 'Tape', NULL, NULL, 0, 0, 2, 2, 34.00, 1, 34.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1326, 'Bamboo 500ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 195.00, 1, 195.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-26 14:39:33', 5),
(1327, 'Travel with Handle 500ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 149.00, 1, 149.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1328, 'Sports Tumbler 600ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 174.00, 1, 174.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1329, 'Travel Flat Lid 350ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 185.00, 1, 185.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1330, 'Flask  500ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 80.00, 1, 80.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1331, 'Insulated 450ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 185.00, 1, 185.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1332, 'J-Type 23 inches', NULL, 'Umbrella', NULL, NULL, 0, 0, 0, 0, 100.00, 1, 100.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1333, 'Golf 30 inches', NULL, 'Umbrella', NULL, NULL, 0, 0, 0, 0, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1334, 'Folding automatic', NULL, 'Umbrella', NULL, NULL, 0, 0, 0, 0, 90.00, 1, 90.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1335, 'UV Sticker Film B B', NULL, 'UV', NULL, NULL, 0, 0, 0, 0, 1200.00, 220, 5.4545, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1336, 'Wooden keychain rectangle', NULL, 'Wood', NULL, NULL, 0, 25, 0, 25, 130.00, 10, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1337, 'Wooden keychain square', NULL, 'Wood', NULL, NULL, 0, 0, 47, 47, 130.00, 10, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1338, 'Satin Ribbon 25 yards 2cm', NULL, 'Ribbon', NULL, NULL, 0, 0, 0, 0, 37.00, 1, 37.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1339, 'Satin Ribbon 25 yards 2.5cm', NULL, 'Ribbon', NULL, NULL, 0, 0, 0, 0, 45.00, 1, 45.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1340, 'Satin Ribbon 25 yards 4cm', NULL, 'Ribbon', NULL, NULL, 0, 0, 0, 0, 25.00, 1, 25.0000, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1341, 'Shredded Paper (grams) Straight', NULL, 'Filler', NULL, NULL, 0, 0, 0, 0, 189.00, 500, 0.3780, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1342, 'Shredded Paper (grams)  Cinkled', NULL, 'Filler', NULL, NULL, 0, 0, 0, 0, 192.00, 500, 0.3840, '', NULL, 'uploads/materials/default.png', '2026-06-24 14:47:41', '2026-06-24 14:47:41', 5),
(1343, 'Tree Type Glossy Paper', NULL, 'imported', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, NULL, NULL, 'uploads/materials/default.png', '2026-06-26 14:33:34', '2026-06-26 14:33:34', 5),
(1344, 'Thermal Tape', NULL, 'imported', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, NULL, NULL, 'uploads/materials/default.png', '2026-06-26 14:33:34', '2026-06-26 14:33:34', 5),
(1345, 'Tree Type Glossy Paper', NULL, 'imported', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, NULL, NULL, 'uploads/materials/default.png', '2026-06-26 14:33:36', '2026-06-26 14:33:36', 5),
(1346, 'Thermal Tape', NULL, 'imported', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, NULL, NULL, 'uploads/materials/default.png', '2026-06-26 14:33:36', '2026-06-26 14:33:36', 5);

-- --------------------------------------------------------

--
-- Table structure for table `materials_logs`
--

CREATE TABLE `materials_logs` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `location` varchar(50) DEFAULT 'total_stock',
  `change_type` enum('add','subtract','order','return','adjust') NOT NULL,
  `quantity` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `audit_id` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `quote_number` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','gcash','card') NOT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `payment_proof` text DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `order_status` enum('pending','paid','processing','packed','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `quote_number`, `customer_name`, `customer_email`, `customer_phone`, `receiver_name`, `total_amount`, `payment_method`, `payment_reference`, `payment_proof`, `payment_status`, `order_status`, `created_at`, `updated_at`) VALUES
(48, 'ORD-BE7E0EB6', 'AI-202605-0008', 'Jay Michael M. Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 47100.00, 'cash', NULL, NULL, 'paid', 'packed', '2026-05-30 03:00:50', '2026-05-30 03:05:33'),
(49, 'ORD-77D34ACA', 'AI-0526-0012', 'Jay Michael M. Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', NULL, 500.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-05-30 03:07:59', '2026-05-30 03:08:18'),
(50, 'ORD-A79C1B1E', 'AI-0526-0016', 'Organization Name', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Jay', 550.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-05-30 06:47:30', '2026-07-02 13:46:13'),
(51, 'ORD-D1E81A83', 'AI-0626-0001', 'ABC Corp', '', '09121231234', 'Michael', 142.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-06-15 14:57:53', '2026-07-02 13:42:11'),
(52, 'ORD-09B38591', 'AI-0726-0001', 'Organization Name', 'admin123@gmail.com', '+639395529749', 'Jay', 1200.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-07-02 07:42:24', '2026-07-02 13:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('cash','gcash','card') NOT NULL,
  `payment_status` enum('pending','verified','failed','unpaid','paid') DEFAULT 'pending',
  `reference_number` varchar(100) DEFAULT NULL,
  `proof_image` text DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_admins`
--

CREATE TABLE `pending_admins` (
  `request_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `material_type` enum('raw_material','assembled_product','print_service','custom') DEFAULT 'assembled_product',
  `product_type_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `production_cost` decimal(10,2) DEFAULT 0.00,
  `profit_margin` decimal(5,2) DEFAULT 0.00,
  `unit` varchar(20) DEFAULT 'piece',
  `width_cm` decimal(8,2) DEFAULT NULL,
  `height_cm` decimal(8,2) DEFAULT NULL,
  `depth_cm` decimal(8,2) DEFAULT NULL,
  `weight_grams` decimal(10,2) DEFAULT NULL,
  `color_options` text DEFAULT NULL,
  `size_options` text DEFAULT NULL,
  `material_specs` text DEFAULT NULL,
  `finishing_options` text DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `estimated_production_time` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `material_type`, `product_type_id`, `price`, `stock`, `production_cost`, `profit_margin`, `unit`, `width_cm`, `height_cm`, `depth_cm`, `weight_grams`, `color_options`, `size_options`, `material_specs`, `finishing_options`, `image`, `description`, `created_at`, `updated_at`, `featured`, `status`, `estimated_production_time`) VALUES
(19, 'Short Colored', 'SHORTCOLOR-729', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(20, 'Long Colored', 'LONGCOLORE-953', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(21, 'A4 Black and White', 'A4BLACKAND-471', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(22, 'Short Black and White', 'SHORTBLACK-499', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(23, 'Long Black and White', 'LONGBLACKA-886', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(24, 'A4 Half Colored', 'A4HALFCOLO-556', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(25, 'Short Half Colored', 'SHORTHALFC-431', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(26, 'Long Half Colored', 'LONGHALFCO-231', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(27, 'Ordinary Sticker Paper', 'ORDINARYST-804', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(28, 'Ordinary SP with Laminate', 'ORDINARYSP-749', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(29, 'Vinyl Sticker Matte', 'VINYLSTICK-751', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(30, 'Vinyl Sticker Matte with Laminate', 'VINYLSTICK-475', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(31, 'Vinyl Sticker Glossy', 'VINYLSTICK-837', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(32, 'Vinyl Sticker Glossy with Laminate', 'VINYLSTICK-541', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(33, 'Calling card one side no laminate', 'CALLINGCAR-450', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(34, 'Calling Card B2B no laminate', 'CALLINGCAR-242', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(35, 'Calling card one side laminated', 'CALLINGCAR-756', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(36, 'Calling card B2B laminated', 'CALLINGCAR-895', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(37, 'TY Cards one side', 'TYCARDSONE-460', 'assembled_product', 4, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(38, 'TY Cards B2B', 'TYCARDSB2B-212', 'assembled_product', 4, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(39, 'Decals', 'DECALS-113', 'assembled_product', 5, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(40, 'UV sticker logo', 'UVSTICKERL-747', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(41, 'DTF Print half A4', 'DTFPRINTHA-363', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(42, 'DTF Print logo press', 'DTFPRINTLO-740', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(43, 'DTF Print A4 press', 'DTFPRINTA4-126', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(44, 'DTF Print A3 press', 'DTFPRINTA3-880', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(45, 'UV Sticker half A4', 'UVSTICKERH-417', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(46, 'UV Sticker A4', 'UVSTICKERA-976', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(47, 'UV sticker A3', 'UVSTICKERA-688', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(48, 'Vinyl SP Matte Laminated Die Cut', 'VINYLSPMAT-771', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(49, 'Vinyl SP Glossy laminated Kiss Cut', 'VINYLSPGLO-567', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(50, 'Vinyl SP Matte NL Die Cut', 'VINYLSPMAT-110', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(51, 'Vinyl SP Glossy NL Kiss Cut', 'VINYLSPGLO-253', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(52, 'White Mug Subli', 'WHITEMUGSU-241', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(53, 'Frosted Mug Subli', 'FROSTEDMUG-399', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(54, 'Magic Mug Subli', 'MAGICMUGSU-417', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(55, 'Enamel Mug 350ml Subli', 'ENAMELMUG3-508', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(56, 'Inner Colored Subli', 'INNERCOLOR-632', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(57, 'Baseball Cap logo print', 'BASEBALLCA-355', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(58, 'Mesh Cap Logo Print', 'MESHCAPLOG-516', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(59, 'Bucket hat Logo Print', 'BUCKETHATL-609', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(60, 'Tumbler flask with logo', 'TUMBLERFLA-448', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(61, 'Tumbler travel with logo', 'TUMBLERTRA-880', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(62, 'Tumbler travel with handle logo', 'TUMBLERTRA-190', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(63, 'Tumbler Sports with logo', 'TUMBLERSPO-514', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(64, 'Keychain wooden rectangle', 'KEYCHAINWO-445', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(65, 'Keychain wooden square', 'KEYCHAINWO-327', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(66, 'Keychain wooden round', 'KEYCHAINWO-138', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(67, 'Acrylic keychain rec', 'ACRYLICKEY-591', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(68, 'Acrylic keychain square', 'ACRYLICKEY-814', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(69, 'Ackrylic keychain round', 'ACKRYLICKE-828', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(70, 'Bag tag B2B laminated with loop', 'BAGTAGB2BL-957', 'assembled_product', 11, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(71, 'Bag tag one side laminated with loop', 'BAGTAGONES-475', 'assembled_product', 11, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(72, 'Lanyard with side release G hook', 'LANYARDWIT-785', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(73, 'Lanyard with Side release clamp', 'LANYARDWIT-780', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(74, 'Lanyard glossy with side release G hook', 'LANYARDGLO-901', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(75, 'Lanyard glossy with Side release clamp', 'LANYARDGLO-198', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(76, 'ID Card with laminate B2B pigment', 'IDCARDWITH-711', 'assembled_product', 13, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(77, 'ID Card with laminate B2B UV', 'IDCARDWITH-184', 'assembled_product', 13, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(78, 'Shirt with logo', 'SHIRTWITHL-210', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(79, 'Shirt with A5', 'SHIRTWITHA-303', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(80, 'Shirt with A4', 'SHIRTWITHA-232', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(81, 'Shirt with A3', 'SHIRTWITHA-514', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(82, 'Shirt logo and A3', 'SHIRTLOGOA-741', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(83, 'Shirt B2B A4', 'SHIRTB2BA4-692', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(84, 'Polo Shirt with logo', 'POLOSHIRTW-682', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(85, 'Polo Shirt with A5', 'POLOSHIRTW-576', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(86, 'Polo Shirt with A4', 'POLOSHIRTW-535', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(87, 'Polo Shirt with A3', 'POLOSHIRTW-458', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(88, 'Polo Shirt logo and A3', 'POLOSHIRTL-976', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(89, 'Polo Shirt B2B A4', 'POLOSHIRTB-194', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(90, 'Tote Plain Flat A4 print subli 8 by 10', 'TOTEPLAINF-190', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(91, 'Tote Plain Flat A4 print subli 10 by 12', 'TOTEPLAINF-520', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(92, 'Tote Plain Flat A4 print subli 12  by 14', 'TOTEPLAINF-458', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(93, 'Tote Plain Flat A4 print subli 13 by 15', 'TOTEPLAINF-653', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(94, 'Tote Plain Flat A4 print DTF 8 by 10', 'TOTEPLAINF-194', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(95, 'Tote Plain Flat A4 print DTF 10 by 12', 'TOTEPLAINF-369', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(96, 'Tote Plain Flat A4 print DTF 12  by 14', 'TOTEPLAINF-537', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(97, 'Tote Plain Flat A4 print DTF 13 by 15', 'TOTEPLAINF-609', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(98, 'Tote Plain Flat logo DTF 8 by 10', 'TOTEPLAINF-645', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(99, 'Tote Plain Flat logoprint DTF 10 by 12', 'TOTEPLAINF-325', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(100, 'Tote Plain Flat logo print DTF 12  by 14', 'TOTEPLAINF-448', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(101, 'Tote Plain Flat logo print DTF 13 by 15', 'TOTEPLAINF-490', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(102, 'Tote Colored Flat A4 print DTF 8 by 10', 'TOTECOLORE-358', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(103, 'Tote Colored Flat A4 print DTF 10 by 12', 'TOTECOLORE-962', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(104, 'Tote Colored Flat A4 print DTF 12  by 14', 'TOTECOLORE-531', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(105, 'Tote Colored Flat A4 print DTF 13 by 15', 'TOTECOLORE-783', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(106, 'Tote Colored Flat logo DTF 8 by 10', 'TOTECOLORE-411', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(107, 'Tote Colored Flat logoprint DTF 10 by 12', 'TOTECOLORE-546', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(108, 'Tote Colored Flat logo print DTF 12  by 14', 'TOTECOLORE-722', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(109, 'Tote Colored Flat logo print DTF 13 by 15', 'TOTECOLORE-398', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(110, 'Tote Colored Base A4 print DTF 8 by 10', 'TOTECOLORE-846', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(111, 'Tote Colored Base A4 print DTF 10 by 12', 'TOTECOLORE-755', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(112, 'Tote Colored Base A4 print DTF 12  by 14', 'TOTECOLORE-939', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(113, 'Tote Colored Base A4 print DTF 13 by 15', 'TOTECOLORE-525', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(114, 'Tote Colored Base logo DTF 8 by 10', 'TOTECOLORE-192', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(115, 'Tote Colored Base logoprint DTF 10 by 12', 'TOTECOLORE-578', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(116, 'Tote Colored Base logo print DTF 12  by 14', 'TOTECOLORE-110', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(117, 'Tote Colored Base logo print DTF 13 by 15', 'TOTECOLORE-718', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(118, 'Sintra A4 3mm UV', 'SINTRAA43M-436', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(119, 'Sintra A4 5mm UV', 'SINTRAA45M-942', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(120, 'Sintra A3 3mm UV', 'SINTRAA33M-621', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(121, 'Canvas 20 by 30', 'CANVAS20BY-628', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(122, 'Canvas 25 by 30', 'CANVAS25BY-377', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(123, 'Canvas 20 by 20', 'CANVAS20BY-532', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(124, 'Sintra Sticker A4', 'SINTRASTIC-359', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(125, 'Sintra Sticker A3', 'SINTRASTIC-210', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(126, 'Sintra Sticker A2', 'SINTRASTIC-380', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(127, 'Sintra Sticker A1', 'SINTRASTIC-602', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-23 14:06:02', '2026-06-26 14:20:27', 0, 'active', NULL),
(133, 'A.I Logo', 'A4COLORED-254', 'assembled_product', 8, 100.00, 97, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2026-06-24 14:16:53', '2026-06-24 14:48:44', 0, 'active', NULL),
(134, 'A4 Colored', 'A4COLORED-506', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:20:25', '2026-06-26 14:20:27', 0, 'active', NULL),
(135, 'ID Case Case', 'IDCASECA-915', 'assembled_product', 20, 20.00, 40, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:27', 0, 'active', NULL),
(136, 'Tote Plain Flat Portrait  8.5  by 10', 'TOTEPLAI-119', 'assembled_product', 21, 38.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(137, 'Tote Plain Flat Portrait  12 by 14', 'TOTEPLAI-253', 'assembled_product', 21, 58.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(138, 'Tote Plain Flat Portrait  13 by 16', 'TOTEPLAI-538', 'assembled_product', 21, 66.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(139, 'Tote Plain with Base 12 by 14 by 3', 'TOTEPLAI-727', 'assembled_product', 21, 68.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(140, 'Tote Plain with Base 13 by 15 by 3', 'TOTEPLAI-936', 'assembled_product', 21, 78.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(141, 'Tote Plain Flat Portrait  10 by 12', 'TOTEPLAI-421', 'assembled_product', 21, 48.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(142, 'Tote Plain Flat Colored 13 by 15', 'TOTEPLAI-502', 'assembled_product', 21, 93.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(143, 'Tote Plainwith base and zipper Colored 12 by 14 by 3', 'TOTEPLAI-418', 'assembled_product', 21, 120.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(144, 'Tote Plain Flat Colored 12 by 14', 'TOTEPLAI-227', 'assembled_product', 21, 79.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(145, 'Tote Plain Flat with Zipper 12 by 14', 'TOTEPLAI-913', 'assembled_product', 21, 79.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(146, 'Tote Plain Flat with Zipper 13 by 15', 'TOTEPLAI-909', 'assembled_product', 21, 87.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(147, 'Button pins 2.25 inches', 'BUTTONPI-546', 'assembled_product', 22, 300.00, 86, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(148, 'Baseball BGC brand', 'BASEBALL-705', 'assembled_product', 8, 59.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(149, 'Mesh BGC brand', 'MESHBGCB-573', 'assembled_product', 8, 48.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(150, 'Bucket BGC brand', 'BUCKETBG-329', 'assembled_product', 8, 65.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(151, 'CUYI Cleaning Solution 100ml', 'CUYICLEA-584', 'assembled_product', 23, 40.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(152, 'UV Moisturizer 500 ml', 'UVMOISTU-101', 'assembled_product', 23, 0.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(153, 'Itech Glossy Cold Laminating Photo Top Glossy', 'ITECHGLO-583', 'assembled_product', 24, 98.00, 40, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(154, 'Itech Matte Cold Laminating Photo Top Matte', 'ITECHMAT-713', 'assembled_product', 24, 98.00, 99, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(155, 'Itech Transparent Vinyl Stickers A4  A4', 'ITECHTRA-407', 'assembled_product', 24, 165.00, 60, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(156, 'Photo Top - Canvas matte matte', 'PHOTOTOP-692', 'assembled_product', 24, 100.00, 20, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(157, 'Paper Cutter (Metal Base)', 'PAPERCUT-118', 'assembled_product', 25, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(158, 'PVC Die Cutter (ATM Size) 54 by 86cm', 'PVCDIECU-266', 'assembled_product', 25, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(159, 'PET film A3+ Roll Roll', 'PETFILMA-847', 'assembled_product', 6, 1650.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(160, 'Powder Honjet Pack 1 KG', 'POWDERHO-851', 'assembled_product', 6, 320.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(161, 'Black 35x50', 'BLACK35X-428', 'assembled_product', 26, 360.00, 10, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(162, 'White 25x30', 'WHITE25X-856', 'assembled_product', 26, 190.00, 40, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(163, 'White 25x35', 'WHITE25X-908', 'assembled_product', 26, 400.00, 48, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(164, 'White 35x50', 'WHITE35X-270', 'assembled_product', 26, 360.00, 15, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(165, 'White with base Small- 8 by 11', 'WHITEWIT-710', 'assembled_product', 26, 175.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(166, 'White with base M- 10 by 12', 'WHITEWIT-353', 'assembled_product', 26, 200.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(167, 'White with base Large 12 by 16', 'WHITEWIT-559', 'assembled_product', 26, 250.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(168, 'White with base XL- 14 by 18', 'WHITEWIT-110', 'assembled_product', 26, 300.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(169, 'Flat Bag Large 12 by 16', 'FLATBAGL-990', 'assembled_product', 26, 250.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(170, 'Ecobag Yellow 30x 38', 'ECOBAGYE-484', 'assembled_product', 26, 1300.00, 52, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(171, 'Long Brown Paper', 'LONGBROW-341', 'assembled_product', 27, 150.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(172, 'Short Brown Paper', 'SHORTBRO-545', 'assembled_product', 27, 110.00, 10, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(173, 'Laminating Film A4 250 mic', 'LAMINATI-485', 'assembled_product', 28, 819.00, 65, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(174, 'Laminating Film Long 125 mic', 'LAMINATI-728', 'assembled_product', 28, 450.00, 50, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(175, 'Cuyi Pigment Ink Black', 'CUYIPIGM-289', 'assembled_product', 29, 95.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(176, 'Cuyi Pigment Ink Cyan', 'CUYIPIGM-911', 'assembled_product', 29, 95.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(177, 'Cuyi Pigment Ink Magenta', 'CUYIPIGM-727', 'assembled_product', 29, 95.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(178, 'Cuyi Pigment Ink Yellow', 'CUYIPIGM-646', 'assembled_product', 29, 95.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(179, 'Cuyi Sublimation Ink Cyan', 'CUYISUBL-690', 'assembled_product', 29, 168.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(180, 'Cuyi Sublimation Ink Magenta', 'CUYISUBL-228', 'assembled_product', 29, 168.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(181, 'Cuyi Sublimation Ink Yellow', 'CUYISUBL-878', 'assembled_product', 29, 168.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(182, 'Cuyi Sublimation Ink Black', 'CUYISUBL-137', 'assembled_product', 29, 168.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(183, 'Cuyi UV Dye Black', 'CUYIUVDY-110', 'assembled_product', 29, 36.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(184, 'Cuyi UV Dye Cyan', 'CUYIUVDY-881', 'assembled_product', 29, 36.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(185, 'Cuyi UV Dye Light Cyan', 'CUYIUVDY-240', 'assembled_product', 29, 36.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(186, 'Cuyi UV Dye Magenta', 'CUYIUVDY-834', 'assembled_product', 29, 36.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(187, 'Cuyi UV Dye Yellow', 'CUYIUVDY-849', 'assembled_product', 29, 36.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(188, 'Hansol Pigment Black', 'HANSOLPI-526', 'assembled_product', 29, 159.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(189, 'Hansol Pigment Light Cyan', 'HANSOLPI-983', 'assembled_product', 29, 159.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(190, 'Hansol Pigment Light Magenta', 'HANSOLPI-910', 'assembled_product', 29, 159.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:37', 0, 'active', NULL),
(191, 'Hansol Pigment Magenta', 'HANSOLPI-499', 'assembled_product', 29, 159.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(192, 'Hansol Pigment Yellow', 'HANSOLPI-855', 'assembled_product', 29, 159.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(193, 'Hansol Pigment Cyan', 'HANSOLPI-738', 'assembled_product', 29, 159.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(194, 'InkPro DTF Ink 100ml White', 'INKPRODT-384', 'assembled_product', 29, 160.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(195, 'InkPro DTF Ink 100ml Black', 'INKPRODT-161', 'assembled_product', 29, 160.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(196, 'InkPro DTF Ink 100ml Cyan', 'INKPRODT-850', 'assembled_product', 29, 160.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(197, 'InkPro DTF Ink 100ml Magenta', 'INKPRODT-488', 'assembled_product', 29, 160.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(198, 'InkPro DTF Ink 100ml Yellow', 'INKPRODT-682', 'assembled_product', 29, 160.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(199, 'InkPro DTF Ink 500ml White', 'INKPRODT-703', 'assembled_product', 29, 750.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(200, 'UV Ink Black', 'UVINKBLA-373', 'assembled_product', 29, 1250.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(201, 'UV Ink Clear', 'UVINKCLE-627', 'assembled_product', 29, 1250.00, 4, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(202, 'UV Ink Cyan', 'UVINKCYA-288', 'assembled_product', 29, 1250.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(203, 'UV Ink Light Cyan', 'UVINKLIG-865', 'assembled_product', 29, 1250.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(204, 'UV Ink Light Magenta', 'UVINKLIG-662', 'assembled_product', 29, 1250.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(205, 'UV Ink Magenta', 'UVINKMAG-369', 'assembled_product', 29, 1250.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(206, 'UV Ink White', 'UVINKWHI-888', 'assembled_product', 29, 1250.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(207, 'UV Ink Yellow', 'UVINKYEL-333', 'assembled_product', 29, 1250.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(208, 'UV Varnish Glossy', 'UVVARNIS-482', 'assembled_product', 29, 1825.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(209, 'Insert-in Keychain', 'INSERTIN-214', 'assembled_product', 10, 0.00, 36, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(210, 'Acrylic Keychain 5x9 cm cm', 'ACRYLICK-195', 'assembled_product', 10, 0.00, 21, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(211, 'Wooden keychain  square', 'WOODENKE-113', 'assembled_product', 10, 0.00, 45, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(212, '6-in-1 Combo Heat Press Press', '6IN1COMB-391', 'assembled_product', 30, 11200.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(213, 'Laminator A3', 'LAMINATO-171', 'assembled_product', 30, 2280.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(214, 'Mug Press Machine Dual Dual', 'MUGPRESS-188', 'assembled_product', 30, 26620.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(215, 'Button Pin Maker Keychain Molds', 'BUTTONPI-772', 'assembled_product', 30, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(216, 'Magnetic Sheet with Adhesive  A4 0.5mm', 'MAGNETIC-817', 'assembled_product', 31, 180.00, 50, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(217, 'Magnetic Sheet with Adhesive  A4 1mm', 'MAGNETIC-630', 'assembled_product', 31, 298.00, 10, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(218, 'Magnetic Sheet (Adhesive)', 'MAGNETIC-685', 'assembled_product', 31, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(219, 'Magnetic Sheet Non-Adhesive  A4 0.5mm', 'MAGNETIC-606', 'assembled_product', 31, 110.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(220, 'Ref Magnet ID size', 'REFMAGNE-637', 'assembled_product', 31, 300.00, 14, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(221, 'Wire Binder Binder', 'WIREBIND-724', 'assembled_product', 32, 380.00, 38, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(222, 'Frosted / Clear Glass Mug Frosted', 'FROSTEDC-544', 'assembled_product', 33, 75.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(223, 'Inner Color Mug Assorted', 'INNERCOL-724', 'assembled_product', 33, 1660.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(224, 'Magic Mug (Matte/Glossy) Glossy', 'MAGICMUG-794', 'assembled_product', 33, 3040.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(225, 'White Coated Mug  Subli White', 'WHITECOA-265', 'assembled_product', 33, 1369.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(226, 'Enamel 500ml', 'ENAMEL50-350', 'assembled_product', 33, 158.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(227, 'Ceramic Coffee V.1 cork base', 'CERAMICC-399', 'assembled_product', 33, 134.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(228, 'Ceramic Coffee V.2 with wooden lid', 'CERAMICC-749', 'assembled_product', 33, 139.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(229, 'Egg Tumbler 300ml', 'EGGTUMBL-410', 'assembled_product', 33, 119.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(230, 'Bamboo Notebook A5 Size', 'BAMBOONO-917', 'assembled_product', 34, 51.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(231, 'Spring Notebook A5', 'SPRINGNO-776', 'assembled_product', 34, 36.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(232, 'Leatherette Notebook Softbound A5', 'LEATHERE-537', 'assembled_product', 34, 54.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL);
INSERT INTO `products` (`id`, `name`, `sku`, `material_type`, `product_type_id`, `price`, `stock`, `production_cost`, `profit_margin`, `unit`, `width_cm`, `height_cm`, `depth_cm`, `weight_grams`, `color_options`, `size_options`, `material_specs`, `finishing_options`, `image`, `description`, `created_at`, `updated_at`, `featured`, `status`, `estimated_production_time`) VALUES
(233, 'Leatherette Notebook Hardbound  A5', 'LEATHERE-241', 'assembled_product', 34, 143.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(234, 'Moleskin-style Strap Diary A5', 'MOLESKIN-503', 'assembled_product', 34, 52.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(235, 'Moleskin-style Strap Diary A6', 'MOLESKIN-689', 'assembled_product', 34, 39.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(236, 'Mesh bag Big Big 8 by 12', 'MESHBAGB-477', 'assembled_product', 35, 349.00, 102, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(237, 'Mesh bag Small Small 6 by 9', 'MESHBAGS-927', 'assembled_product', 35, 230.00, 102, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(238, 'Paper Bag  13.5x20.5x8.5', 'PAPERBAG-655', 'assembled_product', 35, 0.00, 72, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(239, 'Paper Bag  40x31.5x5', 'PAPERBAG-984', 'assembled_product', 35, 0.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(240, 'Plastic Bag With Handle 12x15x6', 'PLASTICB-857', 'assembled_product', 35, 0.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(241, 'Plastic Bag With Handle 13x15x7', 'PLASTICB-803', 'assembled_product', 35, 0.00, 1, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(242, 'Plastic Bag With Handle 13x20x7', 'PLASTICB-539', 'assembled_product', 35, 0.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(243, 'Plastic Bag With Handle 21x28x8', 'PLASTICB-261', 'assembled_product', 35, 0.00, 3, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(244, 'Plastic Bag With Handle 24x32x10', 'PLASTICB-455', 'assembled_product', 35, 0.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(245, 'PP Plastic 10x13', 'PPPLASTI-503', 'assembled_product', 35, 59.00, 141, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(246, 'PP Plastic 11x9', 'PPPLASTI-326', 'assembled_product', 35, 58.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(247, 'PP Plastic 3.5x5', 'PPPLASTI-999', 'assembled_product', 35, 33.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(248, 'PP Plastic 3x5', 'PPPLASTI-686', 'assembled_product', 35, 31.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(249, 'PP Plastic 4.5x6', 'PPPLASTI-308', 'assembled_product', 35, 68.00, 200, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(250, 'PP Plastic 4x6', 'PPPLASTI-869', 'assembled_product', 35, 47.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(251, 'PP Plastic 6x8', 'PPPLASTI-919', 'assembled_product', 35, 95.00, 250, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(252, 'PP Plastic 9x12', 'PPPLASTI-282', 'assembled_product', 35, 59.00, 190, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(253, 'Brown Paper A3 A3', 'BROWNPAP-293', 'assembled_product', 35, 0.00, 15, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(254, 'Zip Lock 25x35 25x35', 'ZIPLOCK2-821', 'assembled_product', 35, 0.00, 80, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(255, 'Corrugated Brown T15', 'CORRUGAT-709', 'assembled_product', 36, 173.00, 4, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(256, 'Corrugated White T12', 'CORRUGAT-197', 'assembled_product', 36, 143.00, 10, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(257, 'Corrugated White T13', 'CORRUGAT-519', 'assembled_product', 36, 114.00, 5, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(258, 'Corrugated White Small', 'CORRUGAT-229', 'assembled_product', 36, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(259, 'Coffee Contaimer/Plastic 14x10 White  White', 'COFFEECO-557', 'assembled_product', 36, 0.00, 49, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(260, 'Calling card Paper 300 GSM', 'CALLINGC-594', 'assembled_product', 37, 153.00, 489, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(261, 'Calling card Paper  220 GSM', 'CALLINGC-118', 'assembled_product', 37, 125.00, 46, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(262, 'Non-waterproof Vinyl  Sticker A4 Matte', 'NONWATER-305', 'assembled_product', 37, 150.00, 29, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(263, 'Matte Photo Sticker Paper  135 gsm', 'MATTEPHO-420', 'assembled_product', 37, 0.00, 60, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(264, 'Matte Sticker Paper 165 gsm', 'MATTESTI-117', 'assembled_product', 37, 0.00, 20, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(265, 'No Back Print Photo Paper 230gsm', 'NOBACKPR-233', 'assembled_product', 37, 42.00, 9, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(266, 'No Back Print Photo Paper 250 gsm', 'NOBACKPR-363', 'assembled_product', 37, 42.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(267, 'Sublimation Transfer Paper  A4', 'SUBLIMAT-956', 'assembled_product', 37, 155.00, 300, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(268, 'Transparent Non-waterproof Vinyl  Sticker A4', 'TRANSPAR-125', 'assembled_product', 37, 0.00, 35, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(269, 'White Matte Vinyl  Sticker A4', 'WHITEMAT-251', 'assembled_product', 37, 0.00, 22, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(270, 'Bond Paper  A4', 'BONDPAPE-689', 'assembled_product', 37, 220.00, 900, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(271, 'Bond Paper  Letter', 'BONDPAPE-365', 'assembled_product', 37, 210.00, 200, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(272, 'Bond Paper  Long', 'BONDPAPE-527', 'assembled_product', 37, 232.00, 850, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(273, 'C2S Coated A3 230 GSM', 'C2SCOATE-604', 'assembled_product', 37, 229.00, 31, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(274, 'CUYI RC Glossy Photo Paper 4R 260 GSM', 'CUYIRCGL-972', 'assembled_product', 37, 35.00, 20, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(275, 'CUYI RC Pearl Holographic Photo Paper 260 GSM', 'CUYIRCPE-574', 'assembled_product', 37, 40.00, 20, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(276, 'CUYI RC Satin Photo Paper  A4 200 GSM', 'CUYIRCSA-853', 'assembled_product', 37, 135.00, 42, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(277, 'CUYI RC Satin Photo Paper  A4 260 GSM', 'CUYIRCSA-566', 'assembled_product', 37, 170.00, 60, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(278, 'CUYI RC Woven Photo Paper 4R 260 GSM', 'CUYIRCWO-694', 'assembled_product', 37, 47.00, 20, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(279, 'Foldcote Foldcote', 'FOLDCOTE-596', 'assembled_product', 37, 216.00, 24, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(280, 'Glossy Photo Sticker  A4 135 GSM', 'GLOSSYPH-555', 'assembled_product', 37, 68.00, 73, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(281, 'Glossy Photo Sticker  A5 90 gsm', 'GLOSSYPH-239', 'assembled_product', 37, 68.00, 15, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(282, 'L&C Double sided high glossy photo paper  A4 240 GSM', 'LCDOUBLE-289', 'assembled_product', 37, 149.00, 49, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(283, 'L&C High glossy photo paper  A4 230 GSM', 'LCHIGHGL-959', 'assembled_product', 37, 48.00, 12, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(284, 'PET Card Sheets A4 PVC Sheets', 'PETCARDS-510', 'assembled_product', 37, 950.00, 34, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(285, 'Photo Top Glitter', 'PHOTOTOP-648', 'assembled_product', 37, 128.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(286, 'Photo Top Glossy', 'PHOTOTOP-569', 'assembled_product', 37, 98.00, 101, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(287, 'Photo Top Holographic Rainbow', 'PHOTOTOP-547', 'assembled_product', 37, 142.00, 37, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(288, 'Photo Top Matte', 'PHOTOTOP-849', 'assembled_product', 37, 98.00, 194, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(289, 'Sublimation Paper (100gsm) Lanyard', 'SUBLIMAT-602', 'assembled_product', 37, 500.00, 40, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(290, 'Suki Vellum Board - Matte Letter 210gsm Matte Letter 210gsm', 'SUKIVELL-826', 'assembled_product', 37, 0.00, 20, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(291, 'Vinyl Sticker Waterproof Matte', 'VINYLSTI-616', 'assembled_product', 37, 200.00, 17, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(292, 'Vinyl Sticker Waterproof  Transparent', 'VINYLSTI-923', 'assembled_product', 37, 240.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(293, 'Newsprint Letter Letter', 'NEWSPRIN-682', 'assembled_product', 37, 0.00, 200, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(294, 'Ultima matte Vinyl Sticker A4 A4', 'ULTIMAMA-318', 'assembled_product', 37, 150.00, 11, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(295, 'Yasen Vinyl Glossy Photo Stickers  150 GSM', 'YASENVIN-220', 'assembled_product', 37, 120.00, 340, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(296, 'Metal Pen - Black Black', 'METALPEN-483', 'assembled_product', 38, 10.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(297, 'Metal Pen - Blue Blue', 'METALPEN-674', 'assembled_product', 38, 10.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(298, 'Metal Pen - Green Green', 'METALPEN-693', 'assembled_product', 38, 10.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(299, 'Metal Pen - White White', 'METALPEN-970', 'assembled_product', 38, 10.00, 138, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(300, 'Plastic White ballpoint', 'PLASTICW-612', 'assembled_product', 38, 319.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(301, 'Bamboo ballpoint', 'BAMBOOBA-209', 'assembled_product', 38, 10.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(302, 'Double Sided Glossy Photo Paper A4 120 GSM', 'DOUBLESI-472', 'assembled_product', 39, 99.00, 3, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(303, 'Double Sided Glossy Photo Paper A4 200 GSM', 'DOUBLESI-661', 'assembled_product', 39, 128.00, 28, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(304, 'Double Sided Glossy Photo Paper A4 250 GSM', 'DOUBLESI-491', 'assembled_product', 39, 140.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(305, 'Double Sided Glossy Photo Paper A4 160 GSM', 'DOUBLESI-210', 'assembled_product', 39, 120.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(306, 'Double Sided Glossy Photo Paper A4 220 GSM', 'DOUBLESI-462', 'assembled_product', 39, 135.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(307, 'Double Sided Glossy Photo Paper A4 300 GSM', 'DOUBLESI-214', 'assembled_product', 39, 155.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(308, 'Tree Type Glossy Paper (180/230gsm)', 'TREETYPE-289', 'assembled_product', 39, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(309, 'ID Card Card', 'IDCARDCA-517', 'assembled_product', 40, 5.00, 84, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(310, 'UV Sticker film A A', 'UVSTICKE-538', 'assembled_product', 46, 800.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(311, 'One-Hole Hang Tag Puncher', 'ONEHOLEH-960', 'assembled_product', 41, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(312, 'Rubber loop Black', 'RUBBERLO-451', 'assembled_product', 42, 150.00, 28, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(313, 'Rubber loop Transparent', 'RUBBERLO-460', 'assembled_product', 42, 150.00, 110, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(314, 'Rubber loop  Blue', 'RUBBERLO-849', 'assembled_product', 42, 150.00, 9, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(315, 'Rubber loop White', 'RUBBERLO-395', 'assembled_product', 42, 150.00, 83, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(316, 'Rubber loop  Yellow', 'RUBBERLO-988', 'assembled_product', 42, 150.00, 9, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(317, 'Polo Shirt Blue Corner Colored XS- Large', 'POLOSHIR-876', 'assembled_product', 14, 241.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(318, 'Polo Shirt Blue Corner White XS- Large', 'POLOSHIR-321', 'assembled_product', 14, 192.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(319, 'Polo Shirt Blue Corner Colored XL-2XL', 'POLOSHIR-262', 'assembled_product', 14, 276.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(320, 'Polo Shirt Blue Corner Colored 3XL-4XL', 'POLOSHIR-248', 'assembled_product', 14, 310.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(321, 'Polo Shirt Blue Corner White XL-2XL', 'POLOSHIR-349', 'assembled_product', 14, 216.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(322, 'Polo Shirt Blue Corner White 3XL-4XL', 'POLOSHIR-284', 'assembled_product', 14, 255.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(323, 'Tshirt Gildan Colored XS- XL', 'TSHIRTGI-335', 'assembled_product', 14, 199.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(324, 'Tshirt Gildan White XS- XL', 'TSHIRTGI-975', 'assembled_product', 14, 171.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(325, 'Tshirt Gildan Colored 2XL-3XL', 'TSHIRTGI-148', 'assembled_product', 14, 240.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(326, 'Tshirt Yalex White XS- Large', 'TSHIRTYA-489', 'assembled_product', 14, 130.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(327, 'Tshirt Yalex White XL-2XL', 'TSHIRTYA-510', 'assembled_product', 14, 145.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(328, 'Tshirt Yalex White 3XL-5XL', 'TSHIRTYA-137', 'assembled_product', 14, 160.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(329, 'Tshirt Yalex Colored XS- Large', 'TSHIRTYA-556', 'assembled_product', 14, 141.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(330, 'Tshirt Yalex Colored XL-2XL', 'TSHIRTYA-742', 'assembled_product', 14, 165.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(331, 'Tshirt Yalex Colored 3XL-5XL', 'TSHIRTYA-447', 'assembled_product', 14, 180.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(332, 'Tshirt Gildan White 2XL-3XL', 'TSHIRTGI-601', 'assembled_product', 14, 201.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(333, 'Sintra Board  A2 3mm', 'SINTRABO-227', 'assembled_product', 16, 320.00, 5, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(334, 'Sintra Board  A3 3mm', 'SINTRABO-705', 'assembled_product', 16, 300.00, 9, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(335, 'Sintra Board  A4 1.5mm', 'SINTRABO-876', 'assembled_product', 16, 160.00, 42, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(336, 'Sintra Board  A4 3mm', 'SINTRABO-299', 'assembled_product', 16, 200.00, 18, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(337, 'Canvas Board 10 x 10', 'CANVASBO-211', 'assembled_product', 16, 39.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(338, 'Canvas Board 20 x 30', 'CANVASBO-566', 'assembled_product', 16, 44.00, 4, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(339, 'Canvas Board 25 x 30', 'CANVASBO-597', 'assembled_product', 16, 48.00, 4, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(340, 'Canvas Board 30 x 40', 'CANVASBO-518', 'assembled_product', 16, 67.00, 3, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(341, 'Sintra Board - A1 3mm 3mm', 'SINTRABO-377', 'assembled_product', 16, 640.00, 5, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(342, 'Sintra Board - A4 5mm 5mm', 'SINTRABO-632', 'assembled_product', 16, 200.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(343, 'Printable PVC Cards (for Keytags)', 'PRINTABL-693', 'assembled_product', 43, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(344, 'Printable PVC Pre-Cut Cards Cards', 'PRINTABL-646', 'assembled_product', 43, 200.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(345, 'Vinyl  Sticker Roll Roll', 'VINYLSTI-262', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(346, 'Thermal Tape  (20mm x 33m)', 'THERMALT-343', 'assembled_product', 44, 59.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(347, 'Thermal Tape  (6mm x 33m)', 'THERMALT-718', 'assembled_product', 44, 34.00, 2, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(348, 'Bamboo 500ml', 'BAMBOO50-393', 'assembled_product', 9, 195.00, 99, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:36:10', 0, 'active', NULL),
(349, 'Travel with Handle 500ml', 'TRAVELWI-748', 'assembled_product', 9, 149.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(350, 'Sports Tumbler 600ml', 'SPORTSTU-527', 'assembled_product', 9, 174.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(351, 'Travel Flat Lid 350ml', 'TRAVELFL-864', 'assembled_product', 9, 185.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(352, 'Flask  500ml', 'FLASK500-903', 'assembled_product', 9, 80.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(353, 'Insulated 450ml', 'INSULATE-252', 'assembled_product', 9, 185.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(354, 'J-Type 23 inches', 'JTYPE23I-238', 'assembled_product', 45, 100.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(355, 'Golf 30 inches', 'GOLF30IN-963', 'assembled_product', 45, 159.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(356, 'Folding automatic', 'FOLDINGA-146', 'assembled_product', 45, 90.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(357, 'UV Sticker Film B B', 'UVSTICKE-556', 'assembled_product', 46, 1200.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(358, 'Wooden keychain rectangle', 'WOODENKE-909', 'assembled_product', 47, 130.00, 25, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(359, 'Wooden keychain square', 'WOODENKE-846', 'assembled_product', 47, 130.00, 47, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(360, 'Satin Ribbon 25 yards 2cm', 'SATINRIB-573', 'assembled_product', 48, 37.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(361, 'Satin Ribbon 25 yards 2.5cm', 'SATINRIB-368', 'assembled_product', 48, 45.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(362, 'Satin Ribbon 25 yards 4cm', 'SATINRIB-624', 'assembled_product', 48, 25.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(363, 'Shredded Paper (grams) Straight', 'SHREDDED-659', 'assembled_product', 49, 189.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL),
(364, 'Shredded Paper (grams)  Cinkled', 'SHREDDED-814', 'assembled_product', 49, 192.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-06-26 14:22:18', '2026-07-01 09:00:38', 0, 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_materials`
--

CREATE TABLE `product_materials` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_materials`
--

INSERT INTO `product_materials` (`id`, `product_id`, `material_id`, `quantity`, `created_at`) VALUES
(7, 133, 1130, 1.00, '2026-06-24 14:48:44'),
(8, 133, 1125, 1.00, '2026-06-24 14:48:44'),
(1633, 135, 1113, 1.00, '2026-07-01 09:00:37'),
(1634, 136, 1114, 1.00, '2026-07-01 09:00:37'),
(1635, 137, 1115, 1.00, '2026-07-01 09:00:37'),
(1636, 138, 1116, 1.00, '2026-07-01 09:00:37'),
(1637, 139, 1117, 1.00, '2026-07-01 09:00:37'),
(1638, 140, 1118, 1.00, '2026-07-01 09:00:37'),
(1639, 141, 1119, 1.00, '2026-07-01 09:00:37'),
(1640, 142, 1120, 1.00, '2026-07-01 09:00:37'),
(1641, 143, 1121, 1.00, '2026-07-01 09:00:37'),
(1642, 144, 1122, 1.00, '2026-07-01 09:00:37'),
(1643, 145, 1123, 1.00, '2026-07-01 09:00:37'),
(1644, 146, 1124, 1.00, '2026-07-01 09:00:37'),
(1645, 147, 1125, 1.00, '2026-07-01 09:00:37'),
(1646, 148, 1126, 1.00, '2026-07-01 09:00:37'),
(1647, 149, 1127, 1.00, '2026-07-01 09:00:37'),
(1648, 150, 1128, 1.00, '2026-07-01 09:00:37'),
(1649, 151, 1129, 1.00, '2026-07-01 09:00:37'),
(1650, 152, 1130, 1.00, '2026-07-01 09:00:37'),
(1651, 153, 1131, 1.00, '2026-07-01 09:00:37'),
(1652, 154, 1132, 1.00, '2026-07-01 09:00:37'),
(1653, 155, 1133, 1.00, '2026-07-01 09:00:37'),
(1654, 156, 1134, 1.00, '2026-07-01 09:00:37'),
(1655, 157, 1135, 1.00, '2026-07-01 09:00:37'),
(1656, 158, 1136, 1.00, '2026-07-01 09:00:37'),
(1657, 159, 1137, 1.00, '2026-07-01 09:00:37'),
(1658, 160, 1138, 1.00, '2026-07-01 09:00:37'),
(1659, 161, 1139, 1.00, '2026-07-01 09:00:37'),
(1660, 162, 1140, 1.00, '2026-07-01 09:00:37'),
(1661, 163, 1141, 1.00, '2026-07-01 09:00:37'),
(1662, 164, 1142, 1.00, '2026-07-01 09:00:37'),
(1663, 165, 1143, 1.00, '2026-07-01 09:00:37'),
(1664, 166, 1144, 1.00, '2026-07-01 09:00:37'),
(1665, 167, 1145, 1.00, '2026-07-01 09:00:37'),
(1666, 168, 1146, 1.00, '2026-07-01 09:00:37'),
(1667, 169, 1147, 1.00, '2026-07-01 09:00:37'),
(1668, 170, 1148, 1.00, '2026-07-01 09:00:37'),
(1669, 171, 1149, 1.00, '2026-07-01 09:00:37'),
(1670, 172, 1150, 1.00, '2026-07-01 09:00:37'),
(1671, 173, 1151, 1.00, '2026-07-01 09:00:37'),
(1672, 174, 1152, 1.00, '2026-07-01 09:00:37'),
(1673, 175, 1153, 1.00, '2026-07-01 09:00:37'),
(1674, 176, 1154, 1.00, '2026-07-01 09:00:37'),
(1675, 177, 1155, 1.00, '2026-07-01 09:00:37'),
(1676, 178, 1156, 1.00, '2026-07-01 09:00:37'),
(1677, 179, 1157, 1.00, '2026-07-01 09:00:37'),
(1678, 180, 1158, 1.00, '2026-07-01 09:00:37'),
(1679, 181, 1159, 1.00, '2026-07-01 09:00:37'),
(1680, 182, 1160, 1.00, '2026-07-01 09:00:37'),
(1681, 183, 1161, 1.00, '2026-07-01 09:00:37'),
(1682, 184, 1162, 1.00, '2026-07-01 09:00:37'),
(1683, 185, 1163, 1.00, '2026-07-01 09:00:37'),
(1684, 186, 1164, 1.00, '2026-07-01 09:00:37'),
(1685, 187, 1165, 1.00, '2026-07-01 09:00:37'),
(1686, 188, 1166, 1.00, '2026-07-01 09:00:37'),
(1687, 189, 1167, 1.00, '2026-07-01 09:00:37'),
(1688, 190, 1168, 1.00, '2026-07-01 09:00:37'),
(1689, 191, 1169, 1.00, '2026-07-01 09:00:38'),
(1690, 192, 1170, 1.00, '2026-07-01 09:00:38'),
(1691, 193, 1171, 1.00, '2026-07-01 09:00:38'),
(1692, 194, 1172, 1.00, '2026-07-01 09:00:38'),
(1693, 195, 1173, 1.00, '2026-07-01 09:00:38'),
(1694, 196, 1174, 1.00, '2026-07-01 09:00:38'),
(1695, 197, 1175, 1.00, '2026-07-01 09:00:38'),
(1696, 198, 1176, 1.00, '2026-07-01 09:00:38'),
(1697, 199, 1177, 1.00, '2026-07-01 09:00:38'),
(1698, 200, 1178, 1.00, '2026-07-01 09:00:38'),
(1699, 201, 1179, 1.00, '2026-07-01 09:00:38'),
(1700, 202, 1180, 1.00, '2026-07-01 09:00:38'),
(1701, 203, 1181, 1.00, '2026-07-01 09:00:38'),
(1702, 204, 1182, 1.00, '2026-07-01 09:00:38'),
(1703, 205, 1183, 1.00, '2026-07-01 09:00:38'),
(1704, 206, 1184, 1.00, '2026-07-01 09:00:38'),
(1705, 207, 1185, 1.00, '2026-07-01 09:00:38'),
(1706, 208, 1186, 1.00, '2026-07-01 09:00:38'),
(1707, 209, 1187, 1.00, '2026-07-01 09:00:38'),
(1708, 210, 1188, 1.00, '2026-07-01 09:00:38'),
(1709, 211, 1189, 1.00, '2026-07-01 09:00:38'),
(1710, 212, 1190, 1.00, '2026-07-01 09:00:38'),
(1711, 213, 1191, 1.00, '2026-07-01 09:00:38'),
(1712, 214, 1192, 1.00, '2026-07-01 09:00:38'),
(1713, 215, 1193, 1.00, '2026-07-01 09:00:38'),
(1714, 216, 1194, 1.00, '2026-07-01 09:00:38'),
(1715, 217, 1195, 1.00, '2026-07-01 09:00:38'),
(1716, 218, 1196, 1.00, '2026-07-01 09:00:38'),
(1717, 219, 1197, 1.00, '2026-07-01 09:00:38'),
(1718, 220, 1198, 1.00, '2026-07-01 09:00:38'),
(1719, 221, 1199, 1.00, '2026-07-01 09:00:38'),
(1720, 222, 1200, 1.00, '2026-07-01 09:00:38'),
(1721, 223, 1201, 1.00, '2026-07-01 09:00:38'),
(1722, 224, 1202, 1.00, '2026-07-01 09:00:38'),
(1723, 225, 1203, 1.00, '2026-07-01 09:00:38'),
(1724, 226, 1204, 1.00, '2026-07-01 09:00:38'),
(1725, 227, 1205, 1.00, '2026-07-01 09:00:38'),
(1726, 228, 1206, 1.00, '2026-07-01 09:00:38'),
(1727, 229, 1207, 1.00, '2026-07-01 09:00:38'),
(1728, 230, 1208, 1.00, '2026-07-01 09:00:38'),
(1729, 231, 1209, 1.00, '2026-07-01 09:00:38'),
(1730, 232, 1210, 1.00, '2026-07-01 09:00:38'),
(1731, 233, 1211, 1.00, '2026-07-01 09:00:38'),
(1732, 234, 1212, 1.00, '2026-07-01 09:00:38'),
(1733, 235, 1213, 1.00, '2026-07-01 09:00:38'),
(1734, 236, 1214, 1.00, '2026-07-01 09:00:38'),
(1735, 237, 1215, 1.00, '2026-07-01 09:00:38'),
(1736, 238, 1216, 1.00, '2026-07-01 09:00:38'),
(1737, 239, 1217, 1.00, '2026-07-01 09:00:38'),
(1738, 240, 1218, 1.00, '2026-07-01 09:00:38'),
(1739, 241, 1219, 1.00, '2026-07-01 09:00:38'),
(1740, 242, 1220, 1.00, '2026-07-01 09:00:38'),
(1741, 243, 1221, 1.00, '2026-07-01 09:00:38'),
(1742, 244, 1222, 1.00, '2026-07-01 09:00:38'),
(1743, 245, 1223, 1.00, '2026-07-01 09:00:38'),
(1744, 246, 1224, 1.00, '2026-07-01 09:00:38'),
(1745, 247, 1225, 1.00, '2026-07-01 09:00:38'),
(1746, 248, 1226, 1.00, '2026-07-01 09:00:38'),
(1747, 249, 1227, 1.00, '2026-07-01 09:00:38'),
(1748, 250, 1228, 1.00, '2026-07-01 09:00:38'),
(1749, 251, 1229, 1.00, '2026-07-01 09:00:38'),
(1750, 252, 1230, 1.00, '2026-07-01 09:00:38'),
(1751, 253, 1231, 1.00, '2026-07-01 09:00:38'),
(1752, 254, 1232, 1.00, '2026-07-01 09:00:38'),
(1753, 255, 1233, 1.00, '2026-07-01 09:00:38'),
(1754, 256, 1234, 1.00, '2026-07-01 09:00:38'),
(1755, 257, 1235, 1.00, '2026-07-01 09:00:38'),
(1756, 258, 1236, 1.00, '2026-07-01 09:00:38'),
(1757, 259, 1237, 1.00, '2026-07-01 09:00:38'),
(1758, 260, 1238, 1.00, '2026-07-01 09:00:38'),
(1759, 261, 1239, 1.00, '2026-07-01 09:00:38'),
(1760, 262, 1240, 1.00, '2026-07-01 09:00:38'),
(1761, 263, 1241, 1.00, '2026-07-01 09:00:38'),
(1762, 264, 1242, 1.00, '2026-07-01 09:00:38'),
(1763, 265, 1243, 1.00, '2026-07-01 09:00:38'),
(1764, 266, 1244, 1.00, '2026-07-01 09:00:38'),
(1765, 267, 1245, 1.00, '2026-07-01 09:00:38'),
(1766, 268, 1246, 1.00, '2026-07-01 09:00:38'),
(1767, 269, 1247, 1.00, '2026-07-01 09:00:38'),
(1768, 270, 1248, 1.00, '2026-07-01 09:00:38'),
(1769, 271, 1249, 1.00, '2026-07-01 09:00:38'),
(1770, 272, 1250, 1.00, '2026-07-01 09:00:38'),
(1771, 273, 1251, 1.00, '2026-07-01 09:00:38'),
(1772, 274, 1252, 1.00, '2026-07-01 09:00:38'),
(1773, 275, 1253, 1.00, '2026-07-01 09:00:38'),
(1774, 276, 1254, 1.00, '2026-07-01 09:00:38'),
(1775, 277, 1255, 1.00, '2026-07-01 09:00:38'),
(1776, 278, 1256, 1.00, '2026-07-01 09:00:38'),
(1777, 279, 1257, 1.00, '2026-07-01 09:00:38'),
(1778, 280, 1258, 1.00, '2026-07-01 09:00:38'),
(1779, 281, 1259, 1.00, '2026-07-01 09:00:38'),
(1780, 282, 1260, 1.00, '2026-07-01 09:00:38'),
(1781, 283, 1261, 1.00, '2026-07-01 09:00:38'),
(1782, 284, 1262, 1.00, '2026-07-01 09:00:38'),
(1783, 285, 1263, 1.00, '2026-07-01 09:00:38'),
(1784, 286, 1264, 1.00, '2026-07-01 09:00:38'),
(1785, 287, 1265, 1.00, '2026-07-01 09:00:38'),
(1786, 288, 1266, 1.00, '2026-07-01 09:00:38'),
(1787, 289, 1267, 1.00, '2026-07-01 09:00:38'),
(1788, 290, 1268, 1.00, '2026-07-01 09:00:38'),
(1789, 291, 1269, 1.00, '2026-07-01 09:00:38'),
(1790, 292, 1270, 1.00, '2026-07-01 09:00:38'),
(1791, 293, 1271, 1.00, '2026-07-01 09:00:38'),
(1792, 294, 1272, 1.00, '2026-07-01 09:00:38'),
(1793, 295, 1273, 1.00, '2026-07-01 09:00:38'),
(1794, 296, 1274, 1.00, '2026-07-01 09:00:38'),
(1795, 297, 1275, 1.00, '2026-07-01 09:00:38'),
(1796, 298, 1276, 1.00, '2026-07-01 09:00:38'),
(1797, 299, 1277, 1.00, '2026-07-01 09:00:38'),
(1798, 300, 1278, 1.00, '2026-07-01 09:00:38'),
(1799, 301, 1279, 1.00, '2026-07-01 09:00:38'),
(1800, 302, 1280, 1.00, '2026-07-01 09:00:38'),
(1801, 303, 1281, 1.00, '2026-07-01 09:00:38'),
(1803, 305, 1283, 1.00, '2026-07-01 09:00:38'),
(1804, 306, 1284, 1.00, '2026-07-01 09:00:38'),
(1805, 304, 1282, 1.00, '2026-07-01 09:00:38'),
(1806, 307, 1285, 1.00, '2026-07-01 09:00:38'),
(1807, 308, 1343, 180.00, '2026-07-01 09:00:38'),
(1808, 309, 1287, 1.00, '2026-07-01 09:00:38'),
(1810, 311, 1289, 1.00, '2026-07-01 09:00:38'),
(1811, 312, 1290, 1.00, '2026-07-01 09:00:38'),
(1812, 313, 1291, 1.00, '2026-07-01 09:00:38'),
(1813, 314, 1292, 1.00, '2026-07-01 09:00:38'),
(1814, 315, 1293, 1.00, '2026-07-01 09:00:38'),
(1815, 316, 1294, 1.00, '2026-07-01 09:00:38'),
(1816, 317, 1295, 1.00, '2026-07-01 09:00:38'),
(1817, 318, 1296, 1.00, '2026-07-01 09:00:38'),
(1818, 319, 1297, 1.00, '2026-07-01 09:00:38'),
(1819, 320, 1298, 1.00, '2026-07-01 09:00:38'),
(1820, 321, 1299, 1.00, '2026-07-01 09:00:38'),
(1821, 322, 1300, 1.00, '2026-07-01 09:00:38'),
(1822, 323, 1301, 1.00, '2026-07-01 09:00:38'),
(1823, 324, 1302, 1.00, '2026-07-01 09:00:38'),
(1824, 325, 1303, 1.00, '2026-07-01 09:00:38'),
(1825, 326, 1304, 1.00, '2026-07-01 09:00:38'),
(1826, 327, 1305, 1.00, '2026-07-01 09:00:38'),
(1827, 328, 1306, 1.00, '2026-07-01 09:00:38'),
(1828, 329, 1307, 1.00, '2026-07-01 09:00:38'),
(1829, 330, 1308, 1.00, '2026-07-01 09:00:38'),
(1830, 331, 1309, 1.00, '2026-07-01 09:00:38'),
(1831, 332, 1310, 1.00, '2026-07-01 09:00:38'),
(1832, 333, 1311, 1.00, '2026-07-01 09:00:38'),
(1833, 334, 1312, 1.00, '2026-07-01 09:00:38'),
(1834, 335, 1313, 1.00, '2026-07-01 09:00:38'),
(1835, 336, 1314, 1.00, '2026-07-01 09:00:38'),
(1836, 337, 1315, 1.00, '2026-07-01 09:00:38'),
(1837, 338, 1316, 1.00, '2026-07-01 09:00:38'),
(1838, 339, 1317, 1.00, '2026-07-01 09:00:38'),
(1839, 340, 1318, 1.00, '2026-07-01 09:00:38'),
(1840, 341, 1319, 1.00, '2026-07-01 09:00:38'),
(1841, 342, 1320, 1.00, '2026-07-01 09:00:38'),
(1842, 343, 1321, 1.00, '2026-07-01 09:00:38'),
(1843, 344, 1322, 1.00, '2026-07-01 09:00:38'),
(1844, 345, 1323, 1.00, '2026-07-01 09:00:38'),
(1845, 346, 1344, 20.00, '2026-07-01 09:00:38'),
(1846, 347, 1344, 6.00, '2026-07-01 09:00:38'),
(1848, 349, 1327, 1.00, '2026-07-01 09:00:38'),
(1849, 350, 1328, 1.00, '2026-07-01 09:00:38'),
(1850, 351, 1329, 1.00, '2026-07-01 09:00:38'),
(1851, 352, 1330, 1.00, '2026-07-01 09:00:38'),
(1852, 353, 1331, 1.00, '2026-07-01 09:00:38'),
(1853, 354, 1332, 1.00, '2026-07-01 09:00:38'),
(1854, 355, 1333, 1.00, '2026-07-01 09:00:38'),
(1855, 356, 1334, 1.00, '2026-07-01 09:00:38'),
(1856, 310, 1288, 1.00, '2026-07-01 09:00:38'),
(1857, 357, 1335, 1.00, '2026-07-01 09:00:38'),
(1858, 358, 1336, 1.00, '2026-07-01 09:00:38'),
(1859, 359, 1337, 1.00, '2026-07-01 09:00:38'),
(1860, 360, 1338, 1.00, '2026-07-01 09:00:38'),
(1861, 361, 1339, 1.00, '2026-07-01 09:00:38'),
(1862, 362, 1340, 1.00, '2026-07-01 09:00:38'),
(1863, 363, 1341, 1.00, '2026-07-01 09:00:38'),
(1864, 364, 1342, 1.00, '2026-07-01 09:00:38'),
(1866, 348, 1326, 1.00, '2026-07-01 09:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `default_materials` text DEFAULT NULL COMMENT 'JSON of default materials for this product type',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `name`, `slug`, `description`, `category_id`, `default_materials`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Paper Printing', 'paper-printing', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(2, 'Sticker', 'sticker', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(3, 'Calling Card', 'calling-card', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(4, 'Cards', 'cards', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(5, 'Decals', 'decals', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(6, 'DTF', 'dtf', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(7, 'Mugs', 'mugs', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(8, 'Cap', 'cap', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(9, 'Tumbler', 'tumbler', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(10, 'Keychain', 'keychain', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(11, 'Bag Tag', 'bag-tag', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(12, 'Lanyard', 'lanyard', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(13, 'ID Card', 'id-card', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(14, 'Shirt', 'shirt', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(15, 'Tote', 'tote', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(16, 'Signage', 'signage', NULL, NULL, NULL, 'active', '2026-06-23 14:06:02', '2026-06-23 14:06:02'),
(19, 'Uncategorized', 'uncategorized', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(20, 'Accessories', 'accessories', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(21, 'Bag', 'bag', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(22, 'Button Pins', 'button-pins', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(23, 'Cleaning Solution', 'cleaning-solution', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(24, 'Cold Laminate', 'cold-laminate', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(25, 'Cutters', 'cutters', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(26, 'Ecobag', 'ecobag', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(27, 'Envelope', 'envelope', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(28, 'Hot Laminate', 'hot-laminate', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(29, 'Ink', 'ink', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(30, 'Machines', 'machines', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(31, 'Magnet', 'magnet', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(32, 'Metal', 'metal', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(33, 'Mug', 'mug', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(34, 'Notebook', 'notebook', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(35, 'Packaging', 'packaging', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(36, 'Pakaging', 'pakaging', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(37, 'Paper', 'paper', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(38, 'Pens', 'pens', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(39, 'Photo Paper', 'photo-paper', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(40, 'Plastic', 'plastic', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(41, 'Puncher', 'puncher', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(42, 'Rubber', 'rubber', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(43, 'Specialty', 'specialty', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(44, 'Tape', 'tape', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(45, 'Umbrella', 'umbrella', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(46, 'UV', 'uv', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(47, 'Wood', 'wood', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(48, 'Ribbon', 'ribbon', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(49, 'Filler', 'filler', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `quote_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('draft','sent','accepted','expired','converted') DEFAULT 'draft',
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `pdf_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` date DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `audited` tinyint(1) DEFAULT 0 COMMENT '0 = Not audited, 1 = Audited',
  `audit_id` int(11) DEFAULT NULL COMMENT 'Reference to bom_audit table',
  `audited_at` timestamp NULL DEFAULT NULL,
  `audited_by` int(11) DEFAULT NULL COMMENT 'Admin ID who performed audit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `quote_number`, `user_id`, `session_id`, `client_name`, `contact_person`, `email`, `phone`, `address`, `status`, `subtotal`, `tax`, `discount`, `total`, `notes`, `pdf_url`, `created_at`, `expires_at`, `updated_at`, `audited`, `audit_id`, `audited_at`, `audited_by`) VALUES
(2, 'AI-202604-0001', NULL, '9v4147bejpkr7emv4h1hfo7p6n', 'lawrence', 'totoy', 'anghulingelbimbi@gmail.com', '09761621862', NULL, 'converted', 3250.00, 0.00, 0.00, 3250.00, 'we need the folllowing items within a month of may', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202604-0001_20260526_210800.pdf', '2026-04-10 13:32:12', '2026-05-30', '2026-05-26 19:08:00', 0, NULL, NULL, NULL),
(3, 'AI-202604-0002', NULL, 'if1hqo71d5pii5jt498mrsvsnr', 'lawrence', 'totoy', 'anghulingelbimbi@gmail.com', '09761621862', NULL, 'converted', 2900.00, 0.00, 0.00, 2900.00, '', NULL, '2026-04-10 15:06:01', '2026-05-30', '2026-05-01 14:25:10', 0, NULL, NULL, NULL),
(4, 'AI-202604-0003', NULL, 'k9t9bjtni94p6vu9lej10522mj', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 144000.00, 0.00, 0.00, 144000.00, 'Special Notes and Instructions\n\nFile Formats: Please submit your file in the following formats: PDF, TIFF, PNG or high-resolution JPEG for best results.\nColor Specifications: Ensure your files are set to CMYK color mode to avoid any discrepancies in color printing.\nProof Approval: A digital proof and test print will be sent for your approval before the final print run. Please review carefully to avoid any errors.\nPayment Terms: Full payment is due upon delivery of the product, with 50% required downpayment upon order confirmation.\nDelivery & Lead Time: The delivery time may vary depending on the quantity of your order. Standard turnaround time will be provided once we confirm the details of your order. Please let us know if you have any specific deadlines, and we’ll do our best to accommodate them.\nShipment and fees: Customers can pick up their orders at our designated pickup points in Laguna and Mandaluyong. Please confirm your preferred pickup location when placing the order. Shipping fees will be shouldered by the client and are based on the courier’s delivery rates.\nAdditional Requests: For custom sizes or any specific finishing (e.g., lamination, binding), please mention your requirements when placing the order.', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202604-0003_20260526_125257.pdf', '2026-04-11 07:24:13', '2026-04-10', '2026-05-26 11:22:17', 0, NULL, NULL, NULL),
(5, 'AI-202604-0004', NULL, 'vs24kqmo4ct7atvstvcr3c9kf8', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 1100.00, 0.00, 0.00, 1100.00, 'Special Notes and Instructions\n\nFile Formats: Please submit your file in the following formats: PDF, TIFF, PNG or high-resolution JPEG for best results.\nColor Specifications: Ensure your files are set to CMYK color mode to avoid any discrepancies in color printing.\nProof Approval: A digital proof and test print will be sent for your approval before the final print run. Please review carefully to avoid any errors.\nPayment Terms: Full payment is due upon delivery of the product, with 50% required downpayment upon order confirmation.\nDelivery & Lead Time: The delivery time may vary depending on the quantity of your order. Standard turnaround time will be provided once we confirm the details of your order. Please let us know if you have any specific deadlines, and we’ll do our best to accommodate them.\nShipment and fees: Customers can pick up their orders at our designated pickup points in Laguna and Mandaluyong. Please confirm your preferred pickup location when placing the order. Shipping fees will be shouldered by the client and are based on the courier’s delivery rates.\nAdditional Requests: For custom sizes or any specific finishing (e.g., lamination, binding), please mention your requirements when placing the order.', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202604-0004_20260523_145722.pdf', '2026-04-11 12:04:05', '2026-04-29', '2026-05-23 12:57:22', 0, NULL, NULL, NULL),
(8, 'AI-202605-0001', NULL, '4tmoudenuu4cnul5hu16dsr7uk', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'draft', 3650.00, 0.00, 0.00, 3650.00, '', NULL, '2026-05-01 15:27:51', '2026-05-02', '2026-05-01 15:27:51', 0, NULL, NULL, NULL),
(9, 'AI-202605-0002', NULL, '4tmoudenuu4cnul5hu16dsr7uk', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'draft', 750.00, 0.00, 0.00, 750.00, '', NULL, '2026-05-01 15:28:07', '2026-05-02', '2026-05-01 15:28:07', 0, NULL, NULL, NULL),
(10, 'AI-202605-0003', NULL, '4tmoudenuu4cnul5hu16dsr7uk', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'draft', 950.00, 0.00, 0.00, 950.00, '', NULL, '2026-05-01 15:28:17', '2026-05-02', '2026-05-06 11:41:14', 0, NULL, NULL, NULL),
(11, 'AI-202605-0004', NULL, '4tmoudenuu4cnul5hu16dsr7uk', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 950.00, 0.00, 0.00, 950.00, 'Special Notes and Instructions\n\nFile Formats: Please submit your file in the following formats: PDF, TIFF, PNG or high-resolution JPEG for best results.\nColor Specifications: Ensure your files are set to CMYK color mode to avoid any discrepancies in color printing.\nProof Approval: A digital proof and test print will be sent for your approval before the final print run. Please review carefully to avoid any errors.\nPayment Terms: Full payment is due upon delivery of the product, with 50% required downpayment upon order confirmation.\nDelivery & Lead Time: The delivery time may vary depending on the quantity of your order. Standard turnaround time will be provided once we confirm the details of your order. Please let us know if you have any specific deadlines, and we’ll do our best to accommodate them.\nShipment and fees: Customers can pick up their orders at our designated pickup points in Laguna and Mandaluyong. Please confirm your preferred pickup location when placing the order. Shipping fees will be shouldered by the client and are based on the courier’s delivery rates.\nAdditional Requests: For custom sizes or any specific finishing (e.g., lamination, binding), please mention your requirements when placing the order.', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0004_20260526_132352.pdf', '2026-05-01 15:30:44', '2026-05-02', '2026-05-26 11:23:52', 0, NULL, NULL, NULL),
(12, 'AI-202605-0005', NULL, 'civ8n9talgmmmjh9a60scskea5', 'Juan Dela Cruz', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'accepted', 950.00, 0.00, 0.00, 950.00, '', NULL, '2026-05-02 11:38:44', '2026-05-03', '2026-05-29 09:40:36', 0, NULL, NULL, NULL),
(14, 'AI-202605-0006', NULL, 'brhmq4f023da8vt3t57u5v5iis', 'Juan Dela Cruz', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 54500.00, 0.00, 0.00, 54500.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0006_20260511_172253.pdf', '2026-05-08 09:00:08', '2026-05-09', '2026-05-11 15:22:53', 0, NULL, NULL, NULL),
(15, 'AI-202605-0007', NULL, '3dqne18loiism1rmf9ltub9990', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 47100.00, 0.00, 0.00, 47100.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0007_20260526_125208.pdf', '2026-05-08 11:58:42', '2026-05-09', '2026-05-26 10:52:08', 0, NULL, NULL, NULL),
(17, 'AI-202605-0008', NULL, '3dqne18loiism1rmf9ltub9990', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'accepted', 47100.00, 0.00, 0.00, 47100.00, '', NULL, '2026-05-08 12:07:08', '2026-05-09', '2026-05-30 03:00:55', 0, NULL, NULL, NULL),
(18, 'AI-202605-0009', NULL, '3dqne18loiism1rmf9ltub9990', 'Jay Michael Castillo', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 5450.00, 0.00, 0.00, 5450.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0009_20260511_171758.pdf', '2026-05-08 12:07:39', '2026-05-07', '2026-05-11 15:17:58', 0, NULL, NULL, NULL),
(19, 'AI-202605-0010', NULL, '20qs9ccst03kdoomn6t508rmak', 'Juan Dela Cruz', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 3650.00, 0.00, 0.00, 3650.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0089_20260529_183405.pdf', '2026-05-08 12:19:30', '2026-05-10', '2026-05-29 16:34:24', 1, 72, '2026-05-29 16:34:24', 1),
(21, 'AI-202605-0011', NULL, 'hdghbdo4v0907thmdjmo9tf1ru', 'Jay Michael Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 11950.00, 0.00, 0.00, 11950.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0011_20260511_174814.pdf', '2026-05-08 15:23:59', '2026-05-07', '2026-05-29 16:19:43', 1, 68, '2026-05-29 16:19:43', 1),
(22, 'AI-202605-0012', NULL, 'j3e0n5kfnl0aaa1eoq1meub2g2', 'Juan Dela Cruz', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 1700.00, 0.00, 0.00, 1700.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0012_20260511_174945.pdf', '2026-05-08 15:32:03', '2026-05-08', '2026-05-29 16:11:42', 1, 66, '2026-05-29 16:11:42', 1),
(23, 'AI-202605-0013', NULL, 'hdghbdo4v0907thmdjmo9tf1ru', 'Jay Michael Castillo', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 950.00, 0.00, 0.00, 950.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0013_20260511_175117.pdf', '2026-05-08 15:58:32', '2026-05-09', '2026-05-29 16:08:20', 1, 65, '2026-05-29 16:08:20', 1),
(45, 'AI-0526-0001', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 425.00, 0.00, 0.00, 425.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0526-0001_20260529_113610.pdf', '2026-05-29 09:34:22', NULL, '2026-05-29 15:03:30', 1, 61, '2026-05-29 15:03:30', 1),
(46, 'AI-0526-0002', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 94.00, 0.00, 0.00, 94.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0086_20260529_150035.pdf', '2026-05-29 09:38:55', NULL, '2026-05-29 13:04:08', 1, 60, '2026-05-29 13:04:08', 1),
(47, 'AI-0526-0003', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 450.00, 0.00, 0.00, 450.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0085_20260529_145824.pdf', '2026-05-29 10:13:20', NULL, '2026-05-29 12:58:50', 1, 59, '2026-05-29 12:58:50', 1),
(48, 'AI-0526-0004', NULL, NULL, 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 0.00, 0.00, 0.00, 0.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0087_20260529_182417.pdf', '2026-05-29 16:24:04', NULL, '2026-05-29 16:24:29', 1, 69, '2026-05-29 16:24:29', 1),
(49, 'AI-0526-0005', NULL, NULL, 'Art School', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '', '', 'converted', 0.00, 0.00, 0.00, 0.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0088_20260529_182904.pdf', '2026-05-29 16:28:53', NULL, '2026-05-29 16:29:19', 1, 70, '2026-05-29 16:29:19', 1),
(50, 'AI-0526-0006', NULL, NULL, 'Art School', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 0.00, 0.00, 0.00, 0.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0091_20260529_183629.pdf', '2026-05-29 16:36:13', NULL, '2026-05-29 16:36:49', 1, 73, '2026-05-29 16:36:49', 1),
(51, 'AI-0526-0007', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '', '', 'converted', 0.00, 0.00, 0.00, 0.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0092_20260529_184346.pdf', '2026-05-29 16:43:37', NULL, '2026-05-29 16:44:05', 1, 74, '2026-05-29 16:44:05', 1),
(53, 'AI-0526-0009', NULL, NULL, 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 112000.00, 0.00, 0.00, 112000.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0094_20260529_192031.pdf', '2026-05-29 17:20:14', NULL, '2026-05-29 17:29:36', 1, 77, '2026-05-29 17:21:04', 1),
(54, 'AI-0526-0010', NULL, NULL, 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 1050.00, 0.00, 0.00, 1050.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0096_20260529_192859.pdf', '2026-05-29 17:26:27', NULL, '2026-05-29 17:29:20', 1, 78, '2026-05-29 17:26:56', 1),
(55, 'AI-0526-0011', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '', '', 'converted', 510.00, 0.00, 0.00, 510.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0097_20260529_193013.pdf', '2026-05-29 17:30:05', NULL, '2026-05-29 17:33:03', 1, 79, '2026-05-29 17:32:31', 1),
(56, 'AI-0526-0012', NULL, NULL, 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 500.00, 0.00, 0.00, 500.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0526-0012_20260530_050306.pdf', '2026-05-30 03:03:05', NULL, '2026-06-24 14:00:38', 1, 87, '2026-06-24 14:00:38', 1),
(57, 'AI-0526-0013', NULL, NULL, 'Organization Name', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 1150.00, 0.00, 0.00, 1150.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0526-0013_20260604_082031.pdf', '2026-05-30 06:09:14', NULL, '2026-07-03 08:13:08', 1, 88, '2026-07-03 08:13:08', 1),
(58, 'AI-0526-0014', NULL, NULL, 'Organization Name', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 1150.00, 0.00, 0.00, 1150.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0098_20260530_081534.pdf', '2026-05-30 06:13:59', NULL, '2026-06-03 15:04:25', 1, 84, '2026-06-03 15:04:25', 1),
(59, 'AI-0526-0015', NULL, NULL, 'Anything Inside', 'Blessie  Mabilangan', 'blessie@gmail.com', '09395529749', '', 'converted', 25.00, 0.00, 0.00, 25.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0526-0015_20260603_140159.pdf', '2026-05-30 06:20:16', NULL, '2026-06-03 13:59:01', 1, 81, '2026-06-03 13:59:01', 1),
(60, 'AI-0526-0016', NULL, NULL, 'Organization Name', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 550.00, 0.00, 0.00, 550.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0002_20260703_101230.pdf', '2026-05-30 06:31:27', NULL, '2026-07-03 08:12:30', 1, 80, '2026-05-30 06:42:09', 1),
(61, 'AI-0626-0001', NULL, NULL, 'ABC Corp', 'Jay Michael M. Castillo', '', '09121231234', '', 'converted', 142.00, 0.00, 0.00, 142.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0626-0001_20260626_165124.pdf', '2026-06-05 11:50:41', NULL, '2026-06-26 14:51:24', 1, 86, '2026-06-05 11:53:00', 1),
(62, 'AI-0726-0001', NULL, NULL, 'Organization Name', 'Jay Michael Castillo', 'admin123@gmail.com', '+639395529749', '', 'converted', 1200.00, 0.00, 0.00, 1200.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0726-0001_20260703_101345.pdf', '2026-07-01 08:35:48', NULL, '2026-07-03 08:13:45', 1, 89, '2026-07-03 08:13:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_items`
--

INSERT INTO `quotation_items` (`id`, `quotation_id`, `product_id`, `description`, `quantity`, `unit_price`, `total`) VALUES
(1, 2, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(2, 2, NULL, 'Couple Wine Set', 1, 2300.00, 2300.00),
(88, 3, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(89, 3, NULL, 'Baby Welcome Kit', 1, 1950.00, 1950.00),
(90, 8, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(91, 8, NULL, 'Baby Shower Favors', 1, 750.00, 750.00),
(92, 8, NULL, 'Baby Welcome Kit', 1, 1950.00, 1950.00),
(93, 9, NULL, 'Baby Shower Favors', 1, 750.00, 750.00),
(112, 10, NULL, 'Anniversary Photo Album<div><ul><li>Notebook</li><li>Pen</li><li>Eraser</li><li>Tape</li></ul></div>', 1, 950.00, 950.00),
(121, 15, NULL, 'Anniversary Photo Album', 3, 950.00, 2850.00),
(122, 15, NULL, 'Baby Shower Favors', 9, 750.00, 6750.00),
(123, 15, NULL, 'Baby Welcome Kit', 10, 1950.00, 19500.00),
(124, 15, NULL, 'Birthday Surprise Box', 10, 1800.00, 18000.00),
(129, 17, NULL, 'Anniversary Photo Album', 3, 950.00, 2850.00),
(130, 17, NULL, 'Baby Shower Favors', 9, 750.00, 6750.00),
(131, 17, NULL, 'Baby Welcome Kit', 10, 1950.00, 19500.00),
(132, 17, NULL, 'Birthday Surprise Box', 10, 1800.00, 18000.00),
(133, 18, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(134, 18, NULL, 'Baby Shower Favors', 1, 750.00, 750.00),
(135, 18, NULL, 'Baby Welcome Kit', 1, 1950.00, 1950.00),
(136, 18, NULL, 'Birthday Surprise Box', 1, 1800.00, 1800.00),
(137, 19, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(138, 19, NULL, 'Baby Shower Favors', 1, 750.00, 750.00),
(139, 19, NULL, 'Baby Welcome Kit', 1, 1950.00, 1950.00),
(174, 22, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(175, 22, NULL, 'Baby Shower Favors', 1, 750.00, 750.00),
(176, 23, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(188, 14, NULL, 'Anniversary Photo Album', 10, 950.00, 9500.00),
(189, 14, NULL, 'Baby Shower Favors', 10, 750.00, 7500.00),
(190, 14, NULL, 'Baby Welcome Kit', 10, 1950.00, 19500.00),
(191, 14, NULL, 'Birthday Surprise Box', 10, 1800.00, 18000.00),
(192, 5, NULL, 'Anniversary Photo Album<div><ul><li>&nbsp;Wire Spring</li><ul><li>sub content</li><li>sub content</li><li>sub content</li></ul><li>&nbsp;No of pages: 50, 70 gsm lined</li><li>Colored cover</li></ul></div>', 1, 950.00, 950.00),
(193, 5, NULL, 'A.I Logo', 1, 150.00, 150.00),
(194, 21, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(195, 21, NULL, 'Baby Shower Favors', 1, 750.00, 750.00),
(196, 21, NULL, 'Baby Welcome Kit', 1, 1950.00, 1950.00),
(197, 21, NULL, 'Birthday Surprise Box', 1, 1800.00, 1800.00),
(198, 21, NULL, 'Christmas Hamper', 1, 3500.00, 3500.00),
(199, 21, NULL, 'Christmas Hamper', 1, 3000.00, 3000.00),
(207, 4, NULL, 'Event', 100, 10.00, 1000.00),
(208, 4, NULL, 'Notepads Bind:<div><ul><li>&nbsp;Wire Spring</li><li>&nbsp;No of pages: 50, 70 gsm lined</li><li>&nbsp;Colored cover&nbsp;</li></ul></div>', 10, 950.00, 9500.00),
(209, 4, NULL, 'Baby Welcome Kit', 50, 1950.00, 97500.00),
(210, 4, NULL, 'Birthday Surprise Box', 20, 1800.00, 36000.00),
(211, 11, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(237, 12, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(255, 45, NULL, 'Bamboo ballpoint', 20, 10.00, 200.00),
(256, 45, NULL, 'A4 Colored', 5, 45.00, 225.00),
(263, 46, NULL, 'Bond Paper  A4', 100, 0.44, 44.00),
(264, 46, NULL, 'Bamboo ballpoint', 5, 10.00, 50.00),
(266, 47, NULL, 'A4 Colored', 10, 45.00, 450.00),
(267, 48, NULL, '6-in-1 Combo Heat Press Press', 0, 11200.00, 0.00),
(268, 49, NULL, 'Bamboo Notebook A5 Size', 0, 51.00, 0.00),
(269, 50, NULL, 'Calling Card B2B no laminate', 0, 20.00, 0.00),
(270, 51, NULL, '6-in-1 Combo Heat Press Press', 0, 11200.00, 0.00),
(272, 53, NULL, '6-in-1 Combo Heat Press Press', 10, 11200.00, 112000.00),
(273, 54, NULL, 'Acrylic keychain square', 21, 50.00, 1050.00),
(275, 55, NULL, 'Bamboo Notebook A5 Size', 10, 51.00, 510.00),
(276, 56, NULL, 'Acrylic Keychain 5x9 cm cm', 10, 50.00, 500.00),
(282, 58, NULL, 'Ackrylic keychain round', 21, 50.00, 1050.00),
(283, 58, NULL, 'Bamboo ballpoint', 10, 10.00, 100.00),
(286, 59, NULL, 'Calling card Paper  220 GSM', 10, 2.50, 25.00),
(287, 60, NULL, 'Calling card B2B laminated', 1, 550.00, 550.00),
(288, 57, NULL, 'Ackrylic keychain round', 21, 50.00, 1050.00),
(289, 57, NULL, 'Bamboo ballpoint', 10, 10.00, 100.00),
(291, 61, NULL, 'Photo Top Holographic Rainbow', 20, 7.10, 142.00),
(294, 62, NULL, 'Acrylic Keychain 5x9 cm cm', 6, 200.00, 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `request_number` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','processed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `request_number`, `user_id`, `session_id`, `client_name`, `contact_person`, `email`, `phone`, `notes`, `status`, `created_at`) VALUES
(1, 'REQ-1775913130', NULL, 'vs24kqmo4ct7atvstvcr3c9kf8', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', 'Test Notes', 'pending', '2026-04-11 13:12:10'),
(2, 'REQ-1775913142', NULL, 'vs24kqmo4ct7atvstvcr3c9kf8', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', 'Test Notes', 'pending', '2026-04-11 13:12:22');

-- --------------------------------------------------------

--
-- Table structure for table `request_items`
--

CREATE TABLE `request_items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_items`
--

INSERT INTO `request_items` (`id`, `request_id`, `description`, `quantity`) VALUES
(1, 1, 'Event', 100),
(2, 1, 'Event', 100),
(3, 1, 'Event', 100),
(4, 2, 'Event', 100);

-- --------------------------------------------------------

--
-- Table structure for table `saved_carts`
--

CREATE TABLE `saved_carts` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', '2026-03-29 14:57:04', '2026-03-29 14:57:04');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_id` (`audit_id`),
  ADD KEY `idx_admin_id` (`admin_id`);

--
-- Indexes for table `bom_audit`
--
ALTER TABLE `bom_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_quote_id` (`quote_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_receipts`
--
ALTER TABLE `delivery_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dr_number` (`dr_number`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `idx_audit_id` (`audit_id`),
  ADD KEY `inventory_logs_ibfk_1` (`material_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_ip` (`ip_address`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials_logs`
--
ALTER TABLE `materials_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_material_id` (`material_id`),
  ADD KEY `idx_audit_id` (`audit_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_email` (`customer_email`),
  ADD KEY `idx_status` (`order_status`),
  ADD KEY `idx_quote_number` (`quote_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `pending_admins`
--
ALTER TABLE `pending_admins`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_price` (`price`),
  ADD KEY `fk_product_type` (`product_type_id`);

--
-- Indexes for table `product_materials`
--
ALTER TABLE `product_materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_material` (`product_id`,`material_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quote_number` (`quote_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_quotations_session_id` (`session_id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_number` (`request_number`);

--
-- Indexes for table `request_items`
--
ALTER TABLE `request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `saved_carts`
--
ALTER TABLE `saved_carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_session_product` (`session_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_session_product_wish` (`session_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_session` (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `bom_audit`
--
ALTER TABLE `bom_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `delivery_receipts`
--
ALTER TABLE `delivery_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1347;

--
-- AUTO_INCREMENT for table `materials_logs`
--
ALTER TABLE `materials_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pending_admins`
--
ALTER TABLE `pending_admins`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=365;

--
-- AUTO_INCREMENT for table `product_materials`
--
ALTER TABLE `product_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1867;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `request_items`
--
ALTER TABLE `request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `saved_carts`
--
ALTER TABLE `saved_carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`audit_id`) REFERENCES `bom_audit` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `audit_logs_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`AdminID`) ON DELETE SET NULL;

--
-- Constraints for table `bom_audit`
--
ALTER TABLE `bom_audit`
  ADD CONSTRAINT `bom_audit_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `delivery_receipts`
--
ALTER TABLE `delivery_receipts`
  ADD CONSTRAINT `delivery_receipts_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `fk_inventory_logs_audit` FOREIGN KEY (`audit_id`) REFERENCES `bom_audit` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_logs_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`AdminID`) ON DELETE SET NULL;

--
-- Constraints for table `materials_logs`
--
ALTER TABLE `materials_logs`
  ADD CONSTRAINT `materials_logs_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_quote_number` FOREIGN KEY (`quote_number`) REFERENCES `quotations` (`quote_number`) ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_type` FOREIGN KEY (`product_type_id`) REFERENCES `product_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_materials`
--
ALTER TABLE `product_materials`
  ADD CONSTRAINT `product_materials_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_materials_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_types`
--
ALTER TABLE `product_types`
  ADD CONSTRAINT `product_types_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_items`
--
ALTER TABLE `request_items`
  ADD CONSTRAINT `request_items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_carts`
--
ALTER TABLE `saved_carts`
  ADD CONSTRAINT `saved_carts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
