-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2026 at 04:55 PM
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
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-05-29 15:30:02');

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
(1, 'Jay Michael', 'jaymichaelmontemarcastillo@gmail.com', '$2y$10$BIuRy0PwOpabNghRQAoHXehVJjjpjvlCDhsCSY6pGJS3EokDpM2iq', 'Admin', 'uploads/admins/1774715140_5010eb1b-7cfe-4f7b-8a46-174d4127c9ed.jfif', 'Active', '2026-03-27 09:06:30', '2026-05-29 07:30:02', '2026-05-29 07:30:02');

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
(25, 60, 'create', 1, '{\"items_count\":3,\"materials_count\":2,\"rejects_count\":0,\"logged_count\":2,\"total_amount\":94,\"total_material_cost\":93.56,\"profit\":0.4399999999999977}', '2026-05-29 13:04:08');

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
  `auto_compute` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom_audit`
--

INSERT INTO `bom_audit` (`id`, `quote_id`, `items`, `materials`, `rejects`, `quotation_items`, `signatures`, `status`, `created_at`, `updated_at`, `is_completed`, `total_material_cost`, `total_reject_cost`, `total_amount_due`, `profit`, `auto_compute`) VALUES
(6, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":0,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:00:27', '2026-05-27 10:00:27', 1, 0.00, 0.00, 1050.00, 1050.00, 1),
(7, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":49.99,\"total_amount\":1049.79}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:16:50', '2026-05-27 10:16:50', 1, 0.00, 0.00, 1049.79, 1049.79, 1),
(8, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_price\":49.99,\"total_amount\":149.97}]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_cost\":11200,\"total_cost\":11200},{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":4,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:21:18', '2026-05-27 10:21:18', 1, 11200.00, 0.00, 149.97, -11050.03, 1),
(9, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_price\":49.99,\"total_amount\":149.97}]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_cost\":11200,\"total_cost\":11200},{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":4,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:23:57', '2026-05-27 10:23:57', 1, 11200.00, 0.00, 149.97, -11050.03, 1),
(15, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:34:34', '2026-05-27 10:34:34', 1, 0.00, 0.00, 1050.00, 1050.00, 1),
(32, NULL, '[{\"name\":\"Test\",\"quantity\":1,\"unit_price\":100,\"total_amount\":100}]', '[]', '[]', NULL, '{\"created_by\":\"Test\",\"audited_by\":\"Test\",\"acknowledged_by\":\"Test\"}', 'draft', '2026-05-27 11:12:23', '2026-05-27 11:12:23', 1, 0.00, 0.00, 100.00, 100.00, 1),
(38, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":70,\"total_amount\":70}]', '[{\"id\":341,\"name\":\"Bamboo Notebook A5 Size\",\"quantity\":10,\"unit_cost\":51,\"total_cost\":510},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":9,\"unit_cost\":10,\"total_cost\":90}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 15:36:36', '2026-05-27 15:36:36', 1, 600.00, 20.00, 70.00, -550.00, 1),
(39, NULL, '[{\"name\":\"Sport\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Sport\",\"quantity\":10,\"unit_price\":500,\"total_amount\":5000}]', '[{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":3,\"unit_cost\":59,\"total_cost\":177}]', '[{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":2,\"unit_cost\":59,\"total_cost\":118}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 16:09:36', '2026-05-27 16:09:36', 1, 177.00, 118.00, 5000.00, 4705.00, 1),
(40, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":21,\"unit_cost\":2,\"total_cost\":42},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":2,\"unit_cost\":1,\"total_cost\":2}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 16:35:49', '2026-05-27 16:35:49', 1, 336.00, 98.00, 1050.00, 616.00, 1),
(41, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":20,\"unit_cost\":2,\"total_cost\":40},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":3,\"unit_cost\":1,\"total_cost\":3}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 17:16:24', '2026-05-27 17:16:24', 1, 334.00, 99.00, 1050.00, 617.00, 1),
(42, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950}]', '[{\"id\":385,\"name\":\"CUYI RC Glossy Photo Paper 4R 260 GSM\",\"quantity\":1,\"unit_cost\":1.75,\"total_cost\":1.75}]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 07:24:14', '2026-05-28 07:24:14', 1, 1.75, 0.00, 950.00, 948.25, 1),
(43, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_cost\":0,\"total_cost\":0}]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 07:44:40', '2026-05-28 07:44:40', 1, 0.00, 0.00, 1050.00, 1050.00, 1),
(44, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":3,\"unit_price\":950,\"total_amount\":2850},{\"name\":\"Baby Shower Favors\",\"quantity\":9,\"unit_price\":750,\"total_amount\":6750},{\"name\":\"Baby Welcome Kit\",\"quantity\":10,\"unit_price\":1950,\"total_amount\":19500},{\"name\":\"Birthday Surprise Box\",\"quantity\":10,\"unit_price\":1800,\"total_amount\":18000}]', '[{\"id\":385,\"name\":\"CUYI RC Glossy Photo Paper 4R 260 GSM\",\"quantity\":1,\"unit_cost\":1.75,\"total_cost\":1.75}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:01:57', '2026-05-28 08:01:57', 1, 1.75, 0.00, 47100.00, 47098.25, 1),
(45, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:22:45', '2026-05-28 08:22:45', 1, 0.00, 0.00, 2400.00, 2400.00, 1),
(46, NULL, '[{\"name\":\"Acrylic Key ChainPrintable PVC SheetHookPrint\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Key Chain<blockquote style=\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:24:10', '2026-05-28 08:24:10', 1, 0.00, 0.00, 1050.00, 1050.00, 1),
(47, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:27:12', '2026-05-28 08:27:12', 1, 0.00, 0.00, 2400.00, 2400.00, 1),
(48, NULL, '[{\"name\":\"Bamboo Notebook A5\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:29:01', '2026-05-28 08:29:01', 1, 0.00, 0.00, 2400.00, 2400.00, 1),
(49, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:31:06', '2026-05-28 08:31:06', 1, 0.00, 0.00, 950.00, 950.00, 1),
(50, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:44:51', '2026-05-28 08:44:51', 1, 0.00, 0.00, 2400.00, 2400.00, 1),
(51, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[{\"id\":479,\"name\":\"A4 Black and White\",\"quantity\":2,\"unit_cost\":0,\"total_cost\":0},{\"id\":459,\"name\":\"Bamboo 500ml\",\"quantity\":2,\"unit_cost\":195,\"total_cost\":390},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:45:19', '2026-05-28 08:45:19', 1, 410.00, 0.00, 2400.00, 1990.00, 1),
(52, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 09:18:25', '2026-05-28 09:18:25', 1, 0.00, 0.00, 2400.00, 2400.00, 1),
(53, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":21,\"unit_cost\":2,\"total_cost\":42},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":2,\"unit_cost\":1,\"total_cost\":2}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 09:35:00', '2026-05-28 09:35:00', 1, 336.00, 98.00, 1050.00, 616.00, 1),
(54, NULL, '[]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":4,\"unit_cost\":11200,\"total_cost\":44800}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 09:43:05', '2026-05-28 09:43:05', 1, 44800.00, 0.00, 0.00, -44800.00, 1),
(55, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":10,\"unit_price\":50,\"total_amount\":500}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_cost\":10,\"total_cost\":100},{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":5,\"unit_cost\":59,\"total_cost\":295}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 09:30:16', '2026-05-29 09:30:16', 1, 395.00, 20.00, 500.00, 85.00, 1),
(56, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":100,\"total_amount\":100}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":2,\"unit_cost\":45,\"total_cost\":90}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 10:49:14', '2026-05-29 10:49:14', 1, 90.00, 0.00, 100.00, 10.00, 1),
(57, NULL, '[{\"name\":\"Audit for Quotation AI-0526-0003 - LSPU LBC\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_cost\":10,\"total_cost\":100}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:53:48', '2026-05-29 12:53:48', 1, 100.00, 0.00, 450.00, 350.00, 1),
(58, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":5,\"unit_cost\":45,\"total_cost\":225}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":2,\"unit_cost\":45,\"total_cost\":90}]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:56:36', '2026-05-29 12:56:36', 1, 225.00, 90.00, 450.00, 135.00, 1),
(59, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":9,\"unit_cost\":45,\"total_cost\":405}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:58:50', '2026-05-29 12:58:50', 1, 405.00, 0.00, 450.00, 45.00, 1),
(60, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":0.44,\"total_amount\":44},{\"name\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":10,\"total_amount\":50}]', '[{\"id\":381,\"name\":\"Bond Paper  A4\",\"quantity\":99,\"unit_cost\":0.44,\"total_cost\":43.56},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_cost\":10,\"total_cost\":50}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 13:04:08', '2026-05-29 13:04:08', 1, 93.56, 0.00, 94.00, 0.44, 1);

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
(4, 'Birthday', NULL, NULL, 'active', '2026-03-31 04:18:25', '2026-03-31 04:18:25'),
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
(1, 'DR-2026-05-0001', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0001_20260527_154240.pdf', '2026-05-27 13:42:40', 1),
(2, 'DR-2026-05-0002', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0002_20260527_154659.pdf', '2026-05-27 13:46:59', 1),
(3, 'DR-2026-05-0003', 27, 'Q-202605-0002', 'Jay Michael Castillo', '', '09395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Acrylic Paint<\\/li><li>Hook<\\/li><li>PVC Sheet<\\/li><\\/ul><\\/div>\",\"quantity\":21,\"unit_price\":\"49.99\",\"total\":\"1049.79\"}]', 1049.79, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0003_20260527_155226.pdf', '2026-05-27 13:52:24', 1),
(4, 'DR-2026-05-0004', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0004_20260527_155738.pdf', '2026-05-27 13:57:36', 1),
(5, 'DR-2026-05-0005', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0005_20260527_155832.pdf', '2026-05-27 13:58:30', 1),
(6, 'DR-2026-05-0006', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0006_20260527_155925.pdf', '2026-05-27 13:59:24', 1),
(7, 'DR-2026-05-0007', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0007_20260527_155950.pdf', '2026-05-27 13:59:49', 1),
(8, 'DR-2026-05-0008', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0008_20260527_160009.pdf', '2026-05-27 14:00:08', 1),
(9, 'DR-2026-05-0009', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0009_20260527_160029.pdf', '2026-05-27 14:00:27', 1),
(10, 'DR-2026-05-0010', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0010_20260527_160056.pdf', '2026-05-27 14:00:55', 1),
(11, 'DR-2026-05-0011', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0011_20260527_160131.pdf', '2026-05-27 14:01:29', 1),
(12, 'DR-2026-05-0012', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0012_20260527_160249.pdf', '2026-05-27 14:02:47', 1),
(13, 'DR-2026-05-0013', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0013_20260527_160329.pdf', '2026-05-27 14:03:28', 1),
(14, 'DR-2026-05-0014', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0014_20260527_160355.pdf', '2026-05-27 14:03:54', 1),
(15, 'DR-2026-05-0015', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0015_20260527_160425.pdf', '2026-05-27 14:04:24', 1),
(16, 'DR-2026-05-0016', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0016_20260527_160506.pdf', '2026-05-27 14:05:04', 1),
(17, 'DR-2026-05-0017', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0017_20260527_160526.pdf', '2026-05-27 14:05:25', 1),
(18, 'DR-2026-05-0018', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0018_20260527_160806.pdf', '2026-05-27 14:08:04', 1),
(19, 'DR-2026-05-0019', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0019_20260527_160834.pdf', '2026-05-27 14:08:32', 1),
(20, 'DR-2026-05-0020', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0020_20260527_160854.pdf', '2026-05-27 14:08:52', 1),
(21, 'DR-2026-05-0021', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0021_20260527_160915.pdf', '2026-05-27 14:09:14', 1),
(22, 'DR-2026-05-0022', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0022_20260527_161010.pdf', '2026-05-27 14:10:09', 1),
(23, 'DR-2026-05-0023', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0023_20260527_161035.pdf', '2026-05-27 14:10:34', 1),
(24, 'DR-2026-05-0024', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0024_20260527_161929.pdf', '2026-05-27 14:19:25', 1),
(25, 'DR-2026-05-0025', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0025_20260527_162153.pdf', '2026-05-27 14:21:51', 1),
(26, 'DR-2026-05-0026', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0026_20260527_162229.pdf', '2026-05-27 14:22:27', 1),
(27, 'DR-2026-05-0027', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0027_20260527_162328.pdf', '2026-05-27 14:23:26', 1),
(28, 'DR-2026-05-0028', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0028_20260527_162427.pdf', '2026-05-27 14:24:26', 1),
(29, 'DR-2026-05-0029', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0029_20260527_162456.pdf', '2026-05-27 14:24:55', 1),
(30, 'DR-2026-05-0030', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0030_20260527_162605.pdf', '2026-05-27 14:26:02', 1),
(31, 'DR-2026-05-0031', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0031_20260527_163056.pdf', '2026-05-27 14:30:55', 1),
(32, 'DR-2026-05-0032', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0032_20260527_163456.pdf', '2026-05-27 14:34:55', 1),
(33, 'DR-2026-05-0033', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0033_20260527_163619.pdf', '2026-05-27 14:36:19', 1),
(34, 'DR-2026-05-0034', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0034_20260527_163717.pdf', '2026-05-27 14:37:16', 1),
(35, 'DR-2026-05-0035', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0035_20260527_163754.pdf', '2026-05-27 14:37:53', 1),
(36, 'DR-2026-05-0036', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0036_20260527_163825.pdf', '2026-05-27 14:38:24', 1),
(37, 'DR-2026-05-0037', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0037_20260527_163919.pdf', '2026-05-27 14:39:17', 1),
(38, 'DR-2026-05-0038', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0038_20260527_163936.pdf', '2026-05-27 14:39:35', 1),
(39, 'DR-2026-05-0039', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0039_20260527_163956.pdf', '2026-05-27 14:39:55', 1),
(40, 'DR-2026-05-0040', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0040_20260527_164028.pdf', '2026-05-27 14:40:27', 1),
(41, 'DR-2026-05-0041', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0041_20260527_164302.pdf', '2026-05-27 14:43:01', 1),
(42, 'DR-2026-05-0042', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0042_20260527_164316.pdf', '2026-05-27 14:43:15', 1),
(43, 'DR-2026-05-0043', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0043_20260527_164334.pdf', '2026-05-27 14:43:33', 1),
(44, 'DR-2026-05-0044', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0044_20260527_164346.pdf', '2026-05-27 14:43:45', 1),
(45, 'DR-2026-05-0045', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0045_20260527_164359.pdf', '2026-05-27 14:43:58', 1),
(46, 'DR-2026-05-0046', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0046_20260527_164449.pdf', '2026-05-27 14:44:48', 1),
(47, 'DR-2026-05-0047', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0047_20260527_164559.pdf', '2026-05-27 14:45:58', 1),
(48, 'DR-2026-05-0048', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0048_20260527_164641.pdf', '2026-05-27 14:46:40', 1),
(49, 'DR-2026-05-0049', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0049_20260527_164659.pdf', '2026-05-27 14:46:58', 1),
(50, 'DR-2026-05-0050', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0050_20260527_164701.pdf', '2026-05-27 14:46:59', 1),
(51, 'DR-2026-05-0051', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0051_20260527_164716.pdf', '2026-05-27 14:47:15', 1),
(52, 'DR-2026-05-0052', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0052_20260527_164833.pdf', '2026-05-27 14:48:31', 1),
(53, 'DR-2026-05-0053', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0053_20260527_164847.pdf', '2026-05-27 14:48:46', 1),
(54, 'DR-2026-05-0054', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0054_20260527_164901.pdf', '2026-05-27 14:49:00', 1),
(55, 'DR-2026-05-0055', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0055_20260527_164931.pdf', '2026-05-27 14:49:31', 1),
(56, 'DR-2026-05-0056', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0056_20260527_164957.pdf', '2026-05-27 14:49:57', 1),
(57, 'DR-2026-05-0057', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0057_20260527_165100.pdf', '2026-05-27 14:51:00', 1),
(58, 'DR-2026-05-0058', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0058_20260527_165141.pdf', '2026-05-27 14:51:39', 1),
(59, 'DR-2026-05-0059', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0059_20260527_165227.pdf', '2026-05-27 14:52:26', 1),
(60, 'DR-2026-05-0060', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0060_20260527_165256.pdf', '2026-05-27 14:52:55', 1),
(61, 'DR-2026-05-0061', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0061_20260527_165311.pdf', '2026-05-27 14:53:10', 1),
(62, 'DR-2026-05-0062', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0062_20260527_165412.pdf', '2026-05-27 14:54:11', 1),
(63, 'DR-2026-05-0063', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0063_20260527_165503.pdf', '2026-05-27 14:55:02', 1),
(64, 'DR-2026-05-0064', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0064_20260527_165540.pdf', '2026-05-27 14:55:39', 1),
(65, 'DR-2026-05-0065', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-27', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0065_20260527_183909.pdf', '2026-05-27 16:39:05', 1),
(66, 'DR-2026-05-0066', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0066_20260528_053243.pdf', '2026-05-28 03:32:42', 1),
(67, 'DR-2026-05-0067', 37, 'Q-2026-05-0018', 'Organization Name', '', '09395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"Personalize Mug\",\"quantity\":1,\"unit_price\":\"20.00\",\"total\":\"20.00\"}]', 20.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0067_20260528_064852.pdf', '2026-05-28 04:48:51', 1),
(68, 'DR-2026-05-0068', 37, 'Q-2026-05-0018', 'Organization Name', '', '09395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"Personalize Mug\",\"quantity\":1,\"unit_price\":\"20.00\",\"total\":\"20.00\"}]', 20.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0068_20260528_064854.pdf', '2026-05-28 04:48:52', 1),
(69, 'DR-2026-05-0069', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"Acrylic Keychain<div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0069_20260528_065139.pdf', '2026-05-28 04:51:38', 1),
(70, 'DR-2026-05-0070', 38, 'QT-20260527-0167', 'Jay Michael M. Castillo', '', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"<b>Acrylic Keychain<\\/b><div><ul><li>Portable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":50,\"unit_price\":\"21.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0070_20260528_075025.pdf', '2026-05-28 05:50:23', 1),
(71, 'DR-2026-05-0071', 39, 'QT-20260528-4245', 'Jay Michael M. Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"Acrylic Keychain<div><ul><li>Printable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div>\",\"quantity\":21,\"unit_price\":\"50.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0071_20260528_075543.pdf', '2026-05-28 05:55:42', 1),
(72, 'DR-2026-05-0072', 40, 'QT-20260528-2245', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":\"50.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0072_20260528_081944.pdf', '2026-05-28 06:19:43', 1),
(73, 'DR-2026-05-0073', 41, 'QT-20260528-8756', 'Jay Michael Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"Acrylic Key Chain<blockquote style=\\\"margin: 0 0 0 40px; border: none; padding: 0px;\\\"><div><ul><li>Printable PVC Sheet<\\/li><li>Hook<\\/li><li>Print<\\/li><\\/ul><\\/div><\\/blockquote>\",\"quantity\":21,\"unit_price\":\"50.00\",\"total\":\"1050.00\"}]', 1050.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0073_20260528_094748.pdf', '2026-05-28 07:47:47', 1),
(74, 'DR-2026-05-0074', 42, 'QT-20260528-2162', 'Jay Michael M. Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":\"51.00\",\"total\":\"255.00\"},{\"description\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":\"195.00\",\"total\":\"1170.00\"},{\"description\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":\"195.00\",\"total\":\"975.00\"}]', 2400.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0074_20260528_102000.pdf', '2026-05-28 08:20:00', 1),
(75, 'DR-2026-05-0075', 43, 'QT-20260528-4099', 'Jay Michael M. Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_price\":\"11200.00\",\"total\":\"0.00\"}]', 0.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0075_20260528_114201.pdf', '2026-05-28 09:41:59', 1),
(76, 'DR-2026-05-0076', 43, 'QT-20260528-4099', 'Jay Michael M. Castillo', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-28', '[{\"description\":\"6-in-1 Combo Heat Press Press\",\"quantity\":5,\"unit_price\":\"11200.00\",\"total\":\"56000.00\"}]', 56000.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0076_20260528_114220.pdf', '2026-05-28 09:42:20', 1),
(77, 'DR-2026-05-0077', 44, 'AI-2605-0001', 'Art School', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":\"10.00\",\"total\":\"100.00\"},{\"description\":\"Baseball BGC brand\",\"quantity\":0,\"unit_price\":\"59.00\",\"total\":\"0.00\"}]', 100.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0077_20260529_112818.pdf', '2026-05-29 09:28:17', 1),
(78, 'DR-2026-05-0078', 46, 'AI-0526-0002', 'LSPU LBC', '', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":\"0.44\",\"total\":\"44.00\"},{\"description\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":\"10.00\",\"total\":\"50.00\"}]', 94.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0078_20260529_114149.pdf', '2026-05-29 09:41:48', 1),
(79, 'DR-2026-05-0079', 46, 'AI-0526-0002', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":\"0.44\",\"total\":\"44.00\"},{\"description\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":\"10.00\",\"total\":\"50.00\"}]', 94.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0079_20260529_114233.pdf', '2026-05-29 09:42:32', 1),
(80, 'DR-2026-05-0080', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0080_20260529_124117.pdf', '2026-05-29 10:41:16', 1),
(81, 'DR-2026-05-0081', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0081_20260529_124931.pdf', '2026-05-29 10:49:30', 1),
(82, 'DR-2026-05-0082', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0082_20260529_144840.pdf', '2026-05-29 12:48:39', 1),
(83, 'DR-2026-05-0083', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0083_20260529_145256.pdf', '2026-05-29 12:52:54', 1),
(84, 'DR-2026-05-0084', 47, 'AI-0526-0003', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0084_20260529_145518.pdf', '2026-05-29 12:55:17', 1),
(85, 'DR-2026-05-0085', 47, 'AI-0526-0003', 'LSPU LBC', '', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"A4 Colored\",\"quantity\":10,\"unit_price\":\"45.00\",\"total\":\"450.00\"}]', 450.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0085_20260529_145824.pdf', '2026-05-29 12:58:22', 1),
(86, 'DR-2026-05-0086', 46, 'AI-0526-0002', 'LSPU LBC', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-05-29', '[{\"description\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":\"0.44\",\"total\":\"44.00\"},{\"description\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":\"10.00\",\"total\":\"50.00\"}]', 94.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0086_20260529_150035.pdf', '2026-05-29 13:00:34', 1);

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

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `material_id`, `change_type`, `quantity`, `previous_stock`, `new_stock`, `admin_id`, `note`, `created_at`, `audit_id`) VALUES
(36, 341, 'order', 10, 100, 90, 1, 'Audit #38: Used 10 x Bamboo Notebook A5 Size', '2026-05-27 15:36:36', 38),
(37, 412, 'order', 9, 100, 91, 1, 'Audit #38: Used 9 x Bamboo ballpoint', '2026-05-27 15:36:36', 38),
(38, 259, 'order', 3, 100, 97, 1, 'Audit #39: Used 3 x Baseball BGC brand', '2026-05-27 16:09:36', 39),
(39, 586, 'order', 6, 100, 94, 1, 'Audit #40: Used 6 x Printabe PVC Sheet', '2026-05-27 16:35:49', 40),
(40, 587, 'order', 21, 100, 79, 1, 'Audit #40: Used 21 x Hook', '2026-05-27 16:35:49', 40),
(41, 588, 'order', 6, 500, 494, 1, 'Audit #40: Used 6 x Print', '2026-05-27 16:35:49', 40),
(42, 586, 'order', 6, 94, 88, 1, 'Audit #41: Used 6 x Printabe PVC Sheet', '2026-05-27 17:16:24', 41),
(43, 587, 'order', 20, 79, 59, 1, 'Audit #41: Used 20 x Hook', '2026-05-27 17:16:24', 41),
(44, 588, 'order', 6, 494, 488, 1, 'Audit #41: Used 6 x Print', '2026-05-27 17:16:24', 41),
(45, 385, 'order', 1, 20, 19, 1, 'Audit #42: Used 1 x CUYI RC Glossy Photo Paper 4R 260 GSM', '2026-05-28 07:24:14', 42),
(46, 321, 'order', 21, 21, 0, 1, 'Audit #43: Used 21 x Acrylic Keychain 5x9 cm cm', '2026-05-28 07:44:40', 43),
(47, 385, 'order', 1, 19, 18, 1, 'Audit #44: Used 1 x CUYI RC Glossy Photo Paper 4R 260 GSM', '2026-05-28 08:01:57', 44),
(48, 479, 'order', 2, 5, 3, 1, 'Audit #51: Used 2 x A4 Black and White', '2026-05-28 08:45:20', 51),
(49, 459, 'order', 2, 100, 98, 1, 'Audit #51: Used 2 x Bamboo 500ml', '2026-05-28 08:45:20', 51),
(50, 412, 'order', 2, 91, 89, 1, 'Audit #51: Used 2 x Bamboo ballpoint', '2026-05-28 08:45:20', 51),
(51, 586, 'order', 6, 88, 82, 1, 'Audit #53: Used 6 x Printabe PVC Sheet', '2026-05-28 09:35:00', 53),
(52, 587, 'order', 21, 59, 38, 1, 'Audit #53: Used 21 x Hook', '2026-05-28 09:35:00', 53),
(53, 588, 'order', 6, 488, 482, 1, 'Audit #53: Used 6 x Print', '2026-05-28 09:35:00', 53),
(54, 323, 'order', 4, 100, 96, 1, 'Audit #54: Used 4 x 6-in-1 Combo Heat Press Press', '2026-05-28 09:43:05', 54),
(55, 412, 'order', 10, 89, 79, 1, 'Audit #55: Used 10 x Bamboo ballpoint', '2026-05-29 09:30:16', 55),
(56, 259, 'order', 5, 97, 92, 1, 'Audit #55: Used 5 x Baseball BGC brand', '2026-05-29 09:30:16', 55),
(57, 476, 'order', 2, 21, 19, 1, 'Audit #56: Used 2 x A4 Colored', '2026-05-29 10:49:14', 56),
(58, 412, 'order', 10, 79, 69, 1, 'Audit #57: Used 10 x Bamboo ballpoint', '2026-05-29 12:53:48', 57),
(59, 476, 'order', 5, 19, 14, 1, 'Audit #58: Used 5 x A4 Colored', '2026-05-29 12:56:36', 58),
(60, 476, 'order', 9, 14, 5, 1, 'Audit #59: Used 9 x A4 Colored', '2026-05-29 12:58:50', 59),
(61, 381, 'order', 99, 900, 801, 1, 'Audit #60: Used 99 x Bond Paper  A4', '2026-05-29 13:04:08', 60),
(62, 412, 'order', 5, 69, 64, 1, 'Audit #60: Used 5 x Bamboo ballpoint', '2026-05-29 13:04:08', 60);

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

INSERT INTO `materials` (`id`, `material_name`, `sku`, `type`, `is_product`, `shop_stock`, `ph_stock`, `total_stock`, `total_cost`, `pieces_per_pack`, `unit_cost`, `remarks`, `description`, `image`, `created_at`, `updated_at`, `low_stock_threshold`) VALUES
(246, 'ID Case Case', NULL, 'Accessories', 0, 40, 0, 40, 20.00, 1, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(247, 'Tote Plain Flat Portrait  8.5  by 10', NULL, 'Bag', 0, 0, 0, 100, 38.00, 1, 38.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(248, 'Tote Plain Flat Portrait  12 by 14', NULL, 'Bag', 0, 0, 0, 100, 58.00, 1, 58.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(249, 'Tote Plain Flat Portrait  13 by 16', NULL, 'Bag', 0, 0, 0, 100, 66.00, 1, 66.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(250, 'Tote Plain with Base 12 by 14 by 3', NULL, 'Bag', 0, 0, 0, 100, 68.00, 1, 68.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(251, 'Tote Plain with Base 13 by 15 by 3', NULL, 'Bag', 0, 0, 0, 100, 78.00, 1, 78.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(252, 'Tote Plain Flat Portrait  10 by 12', NULL, 'Bag', 0, 0, 0, 100, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(253, 'Tote Plain Flat Colored 13 by 15', NULL, 'Bag', 0, 0, 0, 100, 93.00, 1, 93.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(254, 'Tote Plainwith base and zipper Colored 12 by 14 by 3', NULL, 'Bag', 0, 0, 0, 100, 120.00, 1, 120.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(255, 'Tote Plain Flat Colored 12 by 14', NULL, 'Bag', 0, 0, 0, 100, 79.00, 1, 79.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(256, 'Tote Plain Flat with Zipper 12 by 14', NULL, 'Bag', 0, 0, 0, 100, 79.00, 1, 79.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(257, 'Tote Plain Flat with Zipper 13 by 15', NULL, 'Bag', 0, 0, 0, 100, 87.00, 1, 87.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(258, 'Button pins 2.25 inches', NULL, 'Button Pins', 0, 86, 0, 86, 300.00, 100, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(259, 'Baseball BGC brand', NULL, 'Cap', 0, 0, 0, 92, 59.00, 1, 59.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-29 09:30:16', 5),
(260, 'Mesh BGC brand', NULL, 'Cap', 0, 0, 0, 100, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(261, 'Bucket BGC brand', NULL, 'Cap', 0, 0, 0, 100, 65.00, 1, 65.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(262, 'CUYI Cleaning Solution 100ml', NULL, 'Cleaning Solution', 0, 1, 0, 50, 40.00, 1, 40.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(263, 'UV Moisturizer 500 ml', NULL, 'Cleaning Solution', 0, 0, 1, 50, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(264, 'Itech Glossy Cold Laminating Photo Top Glossy', NULL, 'Cold Laminate', 0, 0, 40, 40, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(265, 'Itech Matte Cold Laminating Photo Top Matte', NULL, 'Cold Laminate', 0, 0, 99, 99, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(266, 'Itech Transparent Vinyl Stickers A4  A4', NULL, 'Cold Laminate', 0, 0, 60, 60, 165.00, 20, 8.2500, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(267, 'Photo Top - Canvas matte matte', NULL, 'Cold Laminate', 0, 0, 20, 20, 100.00, 20, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(268, 'Paper Cutter (Metal Base)', NULL, 'Cutters', 0, 0, 0, 100, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(269, 'PVC Die Cutter (ATM Size) 54 by 86cm', NULL, 'Cutters', 0, 0, 0, 100, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(270, 'PET film A3+ Roll Roll', NULL, 'DTF', 0, 0, 0, 100, 1650.00, 1, 1650.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(271, 'Powder Honjet Pack 1 KG', NULL, 'DTF', 0, 0, 1, 50, 320.00, 1, 320.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(272, 'Black 35x50', NULL, 'Ecobag', 0, 0, 10, 10, 360.00, 20, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(273, 'White 25x30', NULL, 'Ecobag', 0, 0, 40, 40, 190.00, 50, 3.8000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(274, 'White 25x35', NULL, 'Ecobag', 0, 0, 48, 48, 400.00, 50, 8.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(275, 'White 35x50', NULL, 'Ecobag', 0, 0, 15, 15, 360.00, 20, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(276, 'White with base Small- 8 by 11', NULL, 'Ecobag', 0, 0, 0, 100, 175.00, 50, 3.5000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(277, 'White with base M- 10 by 12', NULL, 'Ecobag', 0, 0, 0, 100, 200.00, 50, 4.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(278, 'White with base Large 12 by 16', NULL, 'Ecobag', 0, 0, 0, 100, 250.00, 50, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(279, 'White with base XL- 14 by 18', NULL, 'Ecobag', 0, 0, 0, 100, 300.00, 50, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(280, 'Flat Bag Large 12 by 16', NULL, 'Ecobag', 0, 0, 0, 100, 250.00, 50, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(281, 'Ecobag Yellow 30x 38', NULL, 'Ecobag', 0, 0, 52, 52, 1300.00, 100, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(282, 'Long Brown Paper', NULL, 'Envelope', 0, 0, 0, 100, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(283, 'Short Brown Paper', NULL, 'Envelope', 0, 10, 0, 10, 110.00, 50, 2.2000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(284, 'Laminating Film A4 250 mic', NULL, 'Hot Laminate', 0, 15, 50, 65, 819.00, 100, 8.1900, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(285, 'Laminating Film Long 125 mic', NULL, 'Hot Laminate', 0, 0, 50, 50, 450.00, 100, 4.5000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(286, 'Cuyi Pigment Ink Black', NULL, 'Ink', 0, 0, 1, 50, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(287, 'Cuyi Pigment Ink Cyan', NULL, 'Ink', 0, 0, 1, 50, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(288, 'Cuyi Pigment Ink Magenta', NULL, 'Ink', 0, 0, 1, 50, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(289, 'Cuyi Pigment Ink Yellow', NULL, 'Ink', 0, 0, 1, 50, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(290, 'Cuyi Sublimation Ink Cyan', NULL, 'Ink', 0, 0, 0, 100, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(291, 'Cuyi Sublimation Ink Magenta', NULL, 'Ink', 0, 0, 1, 50, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(292, 'Cuyi Sublimation Ink Yellow', NULL, 'Ink', 0, 0, 1, 50, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(293, 'Cuyi Sublimation Ink Black', NULL, 'Ink', 0, 0, 1, 50, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(294, 'Cuyi UV Dye Black', NULL, 'Ink', 0, 1, 0, 50, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(295, 'Cuyi UV Dye Cyan', NULL, 'Ink', 0, 1, 0, 50, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(296, 'Cuyi UV Dye Light Cyan', NULL, 'Ink', 0, 0, 1, 50, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(297, 'Cuyi UV Dye Magenta', NULL, 'Ink', 0, 1, 0, 50, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(298, 'Cuyi UV Dye Yellow', NULL, 'Ink', 0, 1, 0, 50, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(299, 'Hansol Pigment Black', NULL, 'Ink', 0, 0, 1, 50, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(300, 'Hansol Pigment Light Cyan', NULL, 'Ink', 0, 2, 0, 50, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(301, 'Hansol Pigment Light Magenta', NULL, 'Ink', 0, 2, 0, 50, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(302, 'Hansol Pigment Magenta', NULL, 'Ink', 0, 0, 1, 50, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(303, 'Hansol Pigment Yellow', NULL, 'Ink', 0, 1, 1, 50, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(304, 'Hansol Pigment Cyan', NULL, 'Ink', 0, 0, 1, 50, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(305, 'InkPro DTF Ink 100ml White', NULL, 'Ink', 0, 2, 0, 50, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(306, 'InkPro DTF Ink 100ml Black', NULL, 'Ink', 0, 0, 0, 100, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(307, 'InkPro DTF Ink 100ml Cyan', NULL, 'Ink', 0, 0, 0, 100, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(308, 'InkPro DTF Ink 100ml Magenta', NULL, 'Ink', 0, 0, 0, 100, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(309, 'InkPro DTF Ink 100ml Yellow', NULL, 'Ink', 0, 0, 0, 100, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(310, 'InkPro DTF Ink 500ml White', NULL, 'Ink', 0, 0, 0, 100, 750.00, 1, 750.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(311, 'UV Ink Black', NULL, 'Ink', 0, 0, 1, 50, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(312, 'UV Ink Clear', NULL, 'Ink', 0, 0, 4, 50, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(313, 'UV Ink Cyan', NULL, 'Ink', 0, 0, 1, 50, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(314, 'UV Ink Light Cyan', NULL, 'Ink', 0, 0, 1, 50, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(315, 'UV Ink Light Magenta', NULL, 'Ink', 0, 0, 0, 100, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(316, 'UV Ink Magenta', NULL, 'Ink', 0, 0, 1, 50, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(317, 'UV Ink White', NULL, 'Ink', 0, 0, 2, 50, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(318, 'UV Ink Yellow', NULL, 'Ink', 0, 0, 1, 50, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(319, 'UV Varnish Glossy', NULL, 'Ink', 0, 0, 1, 50, 1825.00, 1, 1825.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(320, 'Insert-in Keychain', NULL, 'Keychain', 0, 36, 0, 36, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(321, 'Acrylic Keychain 5x9 cm cm', NULL, 'Keychain', 0, 0, 0, 100, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-28 12:10:11', 5),
(322, 'Wooden keychain  square', NULL, 'Keychain', 0, 45, 0, 45, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(323, '6-in-1 Combo Heat Press Press', NULL, 'Machines', 0, 0, 1, 96, 11200.00, 1, 11200.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-28 09:43:05', 5),
(324, 'Laminator A3', NULL, 'Machines', 0, 1, 0, 50, 2280.00, 1, 2280.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(325, 'Mug Press Machine Dual Dual', NULL, 'Machines', 0, 0, 1, 50, 26620.00, 1, 26620.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(326, 'Button Pin Maker Keychain Molds', NULL, 'Machines', 0, 0, 0, 100, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(327, 'Magnetic Sheet with Adhesive  A4 0.5mm', NULL, 'Magnet', 0, 0, 50, 50, 180.00, 10, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(328, 'Magnetic Sheet with Adhesive  A4 1mm', NULL, 'Magnet', 0, 0, 10, 10, 298.00, 10, 29.8000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(329, 'Magnetic Sheet (Adhesive)', NULL, 'Magnet', 0, 0, 0, 100, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(330, 'Magnetic Sheet Non-Adhesive  A4 0.5mm', NULL, 'Magnet', 0, 1, 0, 50, 110.00, 10, 11.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(331, 'Ref Magnet ID size', NULL, 'Magnet', 0, 0, 14, 14, 300.00, 50, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(332, 'Wire Binder Binder', NULL, 'Metal', 0, 0, 38, 38, 380.00, 100, 3.8000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(333, 'Frosted / Clear Glass Mug Frosted', NULL, 'Mug', 0, 0, 0, 100, 75.00, 1, 75.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(334, 'Inner Color Mug Assorted', NULL, 'Mug', 0, 0, 0, 100, 1660.00, 24, 69.1667, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(335, 'Magic Mug (Matte/Glossy) Glossy', NULL, 'Mug', 0, 0, 0, 100, 3040.00, 36, 84.4444, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(336, 'White Coated Mug  Subli White', NULL, 'Mug', 0, 0, 0, 100, 1369.00, 36, 38.0278, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(337, 'Enamel 500ml', NULL, 'Mug', 0, 0, 0, 100, 158.00, 1, 158.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(338, 'Ceramic Coffee V.1 cork base', NULL, 'Mug', 0, 0, 0, 100, 134.00, 1, 134.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(339, 'Ceramic Coffee V.2 with wooden lid', NULL, 'Mug', 0, 0, 0, 100, 139.00, 1, 139.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(340, 'Egg Tumbler 300ml', NULL, 'Mug', 0, 0, 0, 100, 119.00, 1, 119.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(341, 'Bamboo Notebook A5 Size', NULL, 'Notebook', 0, 0, 0, 90, 51.00, 1, 51.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 15:36:36', 5),
(342, 'Spring Notebook A5', NULL, 'Notebook', 0, 0, 0, 100, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(343, 'Leatherette Notebook Softbound A5', NULL, 'Notebook', 0, 0, 0, 100, 54.00, 1, 54.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(344, 'Leatherette Notebook Hardbound  A5', NULL, 'Notebook', 0, 0, 0, 100, 143.00, 1, 143.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(345, 'Moleskin-style Strap Diary A5', NULL, 'Notebook', 0, 0, 0, 100, 52.00, 1, 52.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(346, 'Moleskin-style Strap Diary A6', NULL, 'Notebook', 0, 0, 0, 100, 39.00, 1, 39.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(347, 'Mesh bag Big Big 8 by 12', NULL, 'Packaging', 0, 100, 2, 102, 349.00, 50, 6.9800, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(348, 'Mesh bag Small Small 6 by 9', NULL, 'Packaging', 0, 100, 2, 102, 230.00, 50, 4.6000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(349, 'Paper Bag  13.5x20.5x8.5', NULL, 'Packaging', 0, 0, 72, 72, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(350, 'Paper Bag  40x31.5x5', NULL, 'Packaging', 0, 0, 2, 50, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(351, 'Plastic Bag With Handle 12x15x6', NULL, 'Packaging', 0, 0, 1, 50, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(352, 'Plastic Bag With Handle 13x15x7', NULL, 'Packaging', 0, 0, 1, 50, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(353, 'Plastic Bag With Handle 13x20x7', NULL, 'Packaging', 0, 0, 2, 50, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(354, 'Plastic Bag With Handle 21x28x8', NULL, 'Packaging', 0, 0, 3, 50, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(355, 'Plastic Bag With Handle 24x32x10', NULL, 'Packaging', 0, 0, 2, 50, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(356, 'PP Plastic 10x13', NULL, 'Packaging', 0, 41, 100, 141, 59.00, 50, 1.1800, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(357, 'PP Plastic 11x9', NULL, 'Packaging', 0, 0, 0, 100, 58.00, 50, 1.1600, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(358, 'PP Plastic 3.5x5', NULL, 'Packaging', 0, 0, 0, 100, 33.00, 100, 0.3300, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(359, 'PP Plastic 3x5', NULL, 'Packaging', 0, 0, 0, 100, 31.00, 100, 0.3100, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(360, 'PP Plastic 4.5x6', NULL, 'Packaging', 0, 0, 200, 200, 68.00, 100, 0.6800, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(361, 'PP Plastic 4x6', NULL, 'Packaging', 0, 0, 0, 100, 47.00, 100, 0.4700, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(362, 'PP Plastic 6x8', NULL, 'Packaging', 0, 50, 200, 250, 95.00, 100, 0.9500, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(363, 'PP Plastic 9x12', NULL, 'Packaging', 0, 47, 143, 190, 59.00, 50, 1.1800, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(364, 'Brown Paper A3 A3', NULL, 'Packaging', 0, 0, 15, 15, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(365, 'Zip Lock 25x35 25x35', NULL, 'Packaging', 0, 0, 80, 80, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(366, 'Corrugated Brown T15', NULL, 'Pakaging', 0, 0, 4, 50, 173.00, 5, 34.6000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(367, 'Corrugated White T12', NULL, 'Pakaging', 0, 0, 10, 10, 143.00, 5, 28.6000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(368, 'Corrugated White T13', NULL, 'Pakaging', 0, 0, 5, 50, 114.00, 5, 22.8000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(369, 'Corrugated White Small', NULL, 'Pakaging', 0, 0, 0, 100, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(370, 'Coffee Contaimer/Plastic 14x10 White  White', NULL, 'Pakaging', 0, 0, 49, 49, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(371, 'Calling card Paper 300 GSM', NULL, 'Paper', 0, 3, 486, 489, 153.00, 50, 3.0600, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(372, 'Calling card Paper  220 GSM', NULL, 'Paper', 0, 46, 0, 46, 125.00, 50, 2.5000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(373, 'Non-waterproof Vinyl  Sticker A4 Matte', NULL, 'Paper', 0, 11, 18, 29, 150.00, 22, 6.8182, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(374, 'Matte Photo Sticker Paper  135 gsm', NULL, 'Paper', 0, 20, 40, 60, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(375, 'Matte Sticker Paper 165 gsm', NULL, 'Paper', 0, 0, 20, 20, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(376, 'No Back Print Photo Paper 230gsm', NULL, 'Paper', 0, 9, 0, 50, 42.00, 20, 2.1000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(377, 'No Back Print Photo Paper 250 gsm', NULL, 'Paper', 0, 0, 0, 100, 42.00, 20, 2.1000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(378, 'Sublimation Transfer Paper  A4', NULL, 'Paper', 0, 0, 300, 300, 155.00, 100, 1.5500, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(379, 'Transparent Non-waterproof Vinyl  Sticker A4', NULL, 'Paper', 0, 0, 35, 35, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(380, 'White Matte Vinyl  Sticker A4', NULL, 'Paper', 0, 0, 22, 22, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(381, 'Bond Paper  A4', NULL, 'Paper', 0, 356, 445, 801, 220.00, 500, 0.4400, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-29 13:04:08', 5),
(382, 'Bond Paper  Letter', NULL, 'Paper', 0, 200, 0, 200, 210.00, 500, 0.4200, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(383, 'Bond Paper  Long', NULL, 'Paper', 0, 850, 0, 850, 232.00, 500, 0.4640, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(384, 'C2S Coated A3 230 GSM', NULL, 'Paper', 0, 0, 31, 31, 229.00, 50, 4.5800, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(385, 'CUYI RC Glossy Photo Paper 4R 260 GSM', NULL, 'Paper', 0, 0, 18, 18, 35.00, 20, 1.7500, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-28 08:01:57', 5),
(386, 'CUYI RC Pearl Holographic Photo Paper 260 GSM', NULL, 'Paper', 0, 0, 20, 20, 40.00, 20, 2.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(387, 'CUYI RC Satin Photo Paper  A4 200 GSM', NULL, 'Paper', 0, 2, 40, 42, 135.00, 20, 6.7500, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(388, 'CUYI RC Satin Photo Paper  A4 260 GSM', NULL, 'Paper', 0, 0, 60, 60, 170.00, 20, 8.5000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(389, 'CUYI RC Woven Photo Paper 4R 260 GSM', NULL, 'Paper', 0, 0, 20, 20, 47.00, 20, 2.3500, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(390, 'Foldcote Foldcote', NULL, 'Paper', 0, 0, 24, 24, 216.00, 25, 8.6400, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(391, 'Glossy Photo Sticker  A4 135 GSM', NULL, 'Paper', 0, 3, 70, 73, 68.00, 20, 3.4000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(392, 'Glossy Photo Sticker  A5 90 gsm', NULL, 'Paper', 0, 15, 0, 15, 68.00, 20, 3.4000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(393, 'L&C Double sided high glossy photo paper  A4 240 GSM', NULL, 'Paper', 0, 0, 49, 49, 149.00, 50, 2.9800, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(394, 'L&C High glossy photo paper  A4 230 GSM', NULL, 'Paper', 0, 0, 12, 12, 48.00, 20, 2.4000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(395, 'PET Card Sheets A4 PVC Sheets', NULL, 'Paper', 0, 0, 34, 34, 950.00, 50, 19.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(396, 'Photo Top Glitter', NULL, 'Paper', 0, 0, 0, 100, 128.00, 20, 6.4000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(397, 'Photo Top Glossy', NULL, 'Paper', 0, 1, 100, 101, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(398, 'Photo Top Holographic Rainbow', NULL, 'Paper', 0, 17, 20, 37, 142.00, 20, 7.1000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(399, 'Photo Top Matte', NULL, 'Paper', 0, 14, 180, 194, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(400, 'Sublimation Paper (100gsm) Lanyard', NULL, 'Paper', 0, 0, 40, 40, 500.00, 100, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(401, 'Suki Vellum Board - Matte Letter 210gsm Matte Letter 210gsm', NULL, 'Paper', 0, 20, 0, 20, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(402, 'Vinyl Sticker Waterproof Matte', NULL, 'Paper', 0, 17, 0, 17, 200.00, 20, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(403, 'Vinyl Sticker Waterproof  Transparent', NULL, 'Paper', 0, 0, 0, 100, 240.00, 20, 12.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(404, 'Newsprint Letter Letter', NULL, 'Paper', 0, 200, 0, 200, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(405, 'Ultima matte Vinyl Sticker A4 A4', NULL, 'Paper', 0, 0, 11, 11, 150.00, 20, 7.5000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(406, 'Yasen Vinyl Glossy Photo Stickers  150 GSM', NULL, 'Paper', 0, 0, 340, 340, 120.00, 20, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(407, 'Metal Pen - Black Black', NULL, 'Pens', 0, 0, 0, 100, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(408, 'Metal Pen - Blue Blue', NULL, 'Pens', 0, 0, 0, 100, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(409, 'Metal Pen - Green Green', NULL, 'Pens', 0, 0, 0, 100, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(410, 'Metal Pen - White White', NULL, 'Pens', 0, 0, 138, 138, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(411, 'Plastic White ballpoint', NULL, 'Pens', 0, 0, 0, 100, 319.00, 50, 6.3800, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(412, 'Bamboo ballpoint', NULL, 'Pens', 0, 0, 0, 64, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-29 13:04:08', 5),
(413, 'Double Sided Glossy Photo Paper A4 120 GSM', NULL, 'Photo Paper', 0, 3, 0, 50, 99.00, 50, 1.9800, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(414, 'Double Sided Glossy Photo Paper A4 200 GSM', NULL, 'Photo Paper', 0, 28, 0, 28, 128.00, 50, 2.5600, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(415, 'Double Sided Glossy Photo Paper A4 250 GSM', NULL, 'Photo Paper', 0, 0, 0, 100, 140.00, 50, 2.8000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(416, 'Double Sided Glossy Photo Paper A4 160 GSM', NULL, 'Photo Paper', 0, 0, 0, 100, 120.00, 50, 2.4000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(417, 'Double Sided Glossy Photo Paper A4 220 GSM', NULL, 'Photo Paper', 0, 0, 0, 100, 135.00, 50, 2.7000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(418, 'Double Sided Glossy Photo Paper A4 300 GSM', NULL, 'Photo Paper', 0, 0, 0, 100, 155.00, 50, 3.1000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(419, 'Tree Type Glossy Paper (180/230gsm)', NULL, 'Photo Paper', 0, 0, 0, 100, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(420, 'ID Card Card', NULL, 'Plastic', 0, 84, 0, 84, 5.00, 1, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(421, 'UV Sticker film A A', NULL, 'UV', 0, 0, 0, 100, 800.00, 20, 40.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(422, 'One-Hole Hang Tag Puncher', NULL, 'Puncher', 0, 1, 0, 100, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(423, 'Rubber loop Black', NULL, 'Rubber', 0, 28, 0, 28, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(424, 'Rubber loop Transparent', NULL, 'Rubber', 0, 10, 100, 110, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(425, 'Rubber loop  Blue', NULL, 'Rubber', 0, 9, 0, 50, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(426, 'Rubber loop White', NULL, 'Rubber', 0, 4, 79, 83, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(427, 'Rubber loop  Yellow', NULL, 'Rubber', 0, 9, 0, 50, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(428, 'Polo Shirt Blue Corner Colored XS- Large', NULL, 'Shirt', 0, 0, 0, 100, 241.00, 1, 241.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(429, 'Polo Shirt Blue Corner White XS- Large', NULL, 'Shirt', 0, 0, 0, 100, 192.00, 1, 192.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(430, 'Polo Shirt Blue Corner Colored XL-2XL', NULL, 'Shirt', 0, 0, 0, 100, 276.00, 1, 276.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(431, 'Polo Shirt Blue Corner Colored 3XL-4XL', NULL, 'Shirt', 0, 0, 0, 100, 310.00, 1, 310.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(432, 'Polo Shirt Blue Corner White XL-2XL', NULL, 'Shirt', 0, 0, 0, 100, 216.00, 1, 216.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(433, 'Polo Shirt Blue Corner White 3XL-4XL', NULL, 'Shirt', 0, 0, 0, 100, 255.00, 1, 255.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(434, 'Tshirt Gildan Colored XS- XL', NULL, 'Shirt', 0, 0, 0, 100, 199.00, 1, 199.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(435, 'Tshirt Gildan White XS- XL', NULL, 'Shirt', 0, 0, 0, 100, 171.00, 1, 171.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(436, 'Tshirt Gildan Colored 2XL-3XL', NULL, 'Shirt', 0, 0, 0, 100, 240.00, 1, 240.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(437, 'Tshirt Yalex White XS- Large', NULL, 'Shirt', 0, 0, 0, 100, 130.00, 1, 130.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(438, 'Tshirt Yalex White XL-2XL', NULL, 'Shirt', 0, 0, 0, 100, 145.00, 1, 145.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(439, 'Tshirt Yalex White 3XL-5XL', NULL, 'Shirt', 0, 0, 0, 100, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(440, 'Tshirt Yalex Colored XS- Large', NULL, 'Shirt', 0, 0, 0, 100, 141.00, 1, 141.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(441, 'Tshirt Yalex Colored XL-2XL', NULL, 'Shirt', 0, 0, 0, 100, 165.00, 1, 165.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(442, 'Tshirt Yalex Colored 3XL-5XL', NULL, 'Shirt', 0, 0, 0, 100, 180.00, 1, 180.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(443, 'Tshirt Gildan White 2XL-3XL', NULL, 'Shirt', 0, 0, 0, 100, 201.00, 1, 201.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(444, 'Sintra Board  A2 3mm', NULL, 'Signage', 0, 0, 5, 50, 320.00, 5, 64.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(445, 'Sintra Board  A3 3mm', NULL, 'Signage', 0, 0, 9, 50, 300.00, 10, 30.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(446, 'Sintra Board  A4 1.5mm', NULL, 'Signage', 0, 0, 42, 42, 160.00, 10, 16.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(447, 'Sintra Board  A4 3mm', NULL, 'Signage', 0, 0, 18, 18, 200.00, 10, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(448, 'Canvas Board 10 x 10', NULL, 'Signage', 0, 2, 0, 50, 39.00, 2, 19.5000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(449, 'Canvas Board 20 x 30', NULL, 'Signage', 0, 0, 4, 50, 44.00, 1, 44.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(450, 'Canvas Board 25 x 30', NULL, 'Signage', 0, 0, 4, 50, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(451, 'Canvas Board 30 x 40', NULL, 'Signage', 0, 0, 3, 50, 67.00, 1, 67.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(452, 'Sintra Board - A1 3mm 3mm', NULL, 'Signage', 0, 0, 5, 50, 640.00, 2, 320.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(453, 'Sintra Board - A4 5mm 5mm', NULL, 'Signage', 0, 0, 0, 100, 200.00, 10, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(454, 'Printable PVC Cards (for Keytags)', NULL, 'Specialty', 0, 0, 0, 100, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(455, 'Printable PVC Pre-Cut Cards Cards', NULL, 'Specialty', 0, 0, 0, 100, 200.00, 50, 4.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(456, 'Vinyl  Sticker Roll Roll', NULL, 'Sticker', 0, 0, 0, 100, 0.00, 0, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(457, 'Thermal Tape  (20mm x 33m)', NULL, 'Tape', 0, 0, 2, 50, 59.00, 1, 59.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(458, 'Thermal Tape  (6mm x 33m)', NULL, 'Tape', 0, 0, 2, 50, 34.00, 1, 34.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:27:32', 5),
(459, 'Bamboo 500ml', NULL, 'Tumbler', 0, 0, 0, 98, 195.00, 1, 195.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-28 08:45:20', 5),
(460, 'Travel with Handle 500ml', NULL, 'Tumbler', 0, 0, 0, 100, 149.00, 1, 149.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(461, 'Sports Tumbler 600ml', NULL, 'Tumbler', 0, 0, 0, 100, 174.00, 1, 174.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(462, 'Travel Flat Lid 350ml', NULL, 'Tumbler', 0, 0, 0, 100, 185.00, 1, 185.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(463, 'Flask  500ml', NULL, 'Tumbler', 0, 0, 0, 100, 80.00, 1, 80.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(464, 'Insulated 450ml', NULL, 'Tumbler', 0, 0, 0, 100, 185.00, 1, 185.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(465, 'J-Type 23 inches', NULL, 'Umbrella', 0, 0, 0, 100, 100.00, 1, 100.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(466, 'Golf 30 inches', NULL, 'Umbrella', 0, 0, 0, 100, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(467, 'Folding automatic', NULL, 'Umbrella', 0, 0, 0, 100, 90.00, 1, 90.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(468, 'UV Sticker Film B B', NULL, 'UV', 0, 0, 0, 100, 1200.00, 220, 5.4545, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(469, 'Wooden keychain rectangle', NULL, 'Wood', 0, 25, 0, 25, 130.00, 10, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(470, 'Wooden keychain square', NULL, 'Wood', 0, 0, 47, 47, 130.00, 10, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 09:48:04', 5),
(471, 'Satin Ribbon 25 yards 2cm', NULL, 'Ribbon', 0, 0, 0, 100, 37.00, 1, 37.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(472, 'Satin Ribbon 25 yards 2.5cm', NULL, 'Ribbon', 0, 0, 0, 100, 45.00, 1, 45.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(473, 'Satin Ribbon 25 yards 4cm', NULL, 'Ribbon', 0, 0, 0, 100, 25.00, 1, 25.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(474, 'Shredded Paper (grams) Straight', NULL, 'Filler', 0, 0, 0, 100, 189.00, 500, 0.3780, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(475, 'Shredded Paper (grams)  Cinkled', NULL, 'Filler', 0, 0, 0, 100, 192.00, 500, 0.3840, '', NULL, 'uploads/materials/default.png', '2026-05-27 09:48:04', '2026-05-27 10:26:45', 5),
(476, 'A4 Colored', NULL, 'Paper', 0, 0, 0, 5, 0.00, 1, 45.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-29 12:58:50', 5),
(477, 'Short Colored', NULL, 'Paper Printing', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(478, 'Long Colored', NULL, 'Paper Printing', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(479, 'A4 Black and White', NULL, 'Paper', 0, 0, 3, 3, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 08:45:20', 5),
(480, 'Short Black and White', NULL, 'Paper Printing', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(481, 'Long Black and White', NULL, 'Paper Printing', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(482, 'A4 Half Colored', NULL, 'Paper', 0, 0, 0, 21, 0.00, 1, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 12:08:59', 5),
(483, 'Short Half Colored', NULL, 'Paper Printing', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(484, 'Long Half Colored', NULL, 'Paper Printing', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(485, 'Ordinary Sticker Paper', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(486, 'Ordinary SP with Laminate', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(487, 'Vinyl Sticker Matte', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(488, 'Vinyl Sticker Matte with Laminate', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(489, 'Vinyl Sticker Glossy', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(490, 'Vinyl Sticker Glossy with Laminate', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(491, 'Calling card one side no laminate', NULL, 'Calling Card', 0, 0, 0, 10, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 04:54:26', 5),
(492, 'Calling Card B2B no laminate', NULL, 'Calling Card', 0, 0, 0, 12, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 04:54:34', 5),
(493, 'Calling card one side laminated', NULL, 'Calling Card', 0, 0, 0, 12, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 04:54:32', 5),
(494, 'Calling card B2B laminated', NULL, 'Calling Card', 0, 0, 0, 43, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 04:54:37', 5),
(495, 'TY Cards one side', NULL, 'Cards', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(496, 'TY Cards B2B', NULL, 'Cards', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(497, 'Decals', NULL, 'Decals', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(498, 'UV sticker logo', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(499, 'DTF Print half A4', NULL, 'DTF', 0, 0, 0, 10, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 04:47:37', 5),
(500, 'DTF Print logo press', NULL, 'DTF', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(501, 'DTF Print A4 press', NULL, 'DTF', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(502, 'DTF Print A3 press', NULL, 'DTF', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(503, 'UV Sticker half A4', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(504, 'UV Sticker A4', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(505, 'UV sticker A3', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(506, 'Vinyl SP Matte Laminated Die Cut', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(507, 'Vinyl SP Glossy laminated Kiss Cut', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(508, 'Vinyl SP Matte NL Die Cut', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(509, 'Vinyl SP Glossy NL Kiss Cut', NULL, 'Sticker', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(510, 'White Mug Subli', NULL, 'Mugs', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(511, 'Frosted Mug Subli', NULL, 'Mugs', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(512, 'Magic Mug Subli', NULL, 'Mugs', 0, 0, 0, 10, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-29 14:53:00', 5),
(513, 'Enamel Mug 350ml Subli', NULL, 'Mugs', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(514, 'Inner Colored Subli', NULL, 'Mugs', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(515, 'Baseball Cap logo print', NULL, 'Cap', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 17:12:34', 5),
(516, 'Mesh Cap Logo Print', NULL, 'Cap', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(517, 'Bucket hat Logo Print', NULL, 'Cap', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(518, 'Tumbler flask with logo', NULL, 'Tumbler', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(519, 'Tumbler travel with logo', NULL, 'Tumbler', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(520, 'Tumbler travel with handle logo', NULL, 'Tumbler', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(521, 'Tumbler Sports with logo', NULL, 'Tumbler', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(522, 'Keychain wooden rectangle', NULL, 'Keychain', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(523, 'Keychain wooden square', NULL, 'Keychain', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(524, 'Keychain wooden round', NULL, 'Keychain', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(525, 'Acrylic keychain rec', NULL, 'Keychain', 0, 0, 0, 100, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 12:10:18', 5),
(526, 'Acrylic keychain square', NULL, 'Keychain', 0, 0, 0, 100, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-28 12:10:15', 5),
(527, 'Ackrylic keychain round', NULL, 'Other', 0, 0, 100, 100, 5000.00, 1, 50.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-29 13:57:45', 5),
(528, 'Bag tag B2B laminated with loop', NULL, 'Bag Tag', 0, 0, 0, 100, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-29 14:05:23', 5),
(529, 'Bag tag one side laminated with loop', NULL, 'Bag Tag', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5);
INSERT INTO `materials` (`id`, `material_name`, `sku`, `type`, `is_product`, `shop_stock`, `ph_stock`, `total_stock`, `total_cost`, `pieces_per_pack`, `unit_cost`, `remarks`, `description`, `image`, `created_at`, `updated_at`, `low_stock_threshold`) VALUES
(530, 'Lanyard with side release G hook', NULL, 'Lanyard', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(531, 'Lanyard with Side release clamp', NULL, 'Lanyard', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(532, 'Lanyard glossy with side release G hook', NULL, 'Lanyard', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(533, 'Lanyard glossy with Side release clamp', NULL, 'Lanyard', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(534, 'ID Card with laminate B2B pigment', NULL, 'ID Card', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(535, 'ID Card with laminate B2B UV', NULL, 'ID Card', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(536, 'Shirt with logo', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(537, 'Shirt with A5', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(538, 'Shirt with A4', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(539, 'Shirt with A3', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(540, 'Shirt logo and A3', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(541, 'Shirt B2B A4', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(542, 'Polo Shirt with logo', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(543, 'Polo Shirt with A5', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(544, 'Polo Shirt with A4', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(545, 'Polo Shirt with A3', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(546, 'Polo Shirt logo and A3', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(547, 'Polo Shirt B2B A4', NULL, 'Shirt', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(548, 'Tote Plain Flat A4 print subli 8 by 10', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(549, 'Tote Plain Flat A4 print subli 10 by 12', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(550, 'Tote Plain Flat A4 print subli 12  by 14', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(551, 'Tote Plain Flat A4 print subli 13 by 15', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(552, 'Tote Plain Flat A4 print DTF 8 by 10', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(553, 'Tote Plain Flat A4 print DTF 10 by 12', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(554, 'Tote Plain Flat A4 print DTF 12  by 14', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(555, 'Tote Plain Flat A4 print DTF 13 by 15', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(556, 'Tote Plain Flat logo DTF 8 by 10', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(557, 'Tote Plain Flat logoprint DTF 10 by 12', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(558, 'Tote Plain Flat logo print DTF 12  by 14', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(559, 'Tote Plain Flat logo print DTF 13 by 15', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(560, 'Tote Colored Flat A4 print DTF 8 by 10', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(561, 'Tote Colored Flat A4 print DTF 10 by 12', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(562, 'Tote Colored Flat A4 print DTF 12  by 14', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(563, 'Tote Colored Flat A4 print DTF 13 by 15', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(564, 'Tote Colored Flat logo DTF 8 by 10', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(565, 'Tote Colored Flat logoprint DTF 10 by 12', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(566, 'Tote Colored Flat logo print DTF 12  by 14', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(567, 'Tote Colored Flat logo print DTF 13 by 15', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(568, 'Tote Colored Base A4 print DTF 8 by 10', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(569, 'Tote Colored Base A4 print DTF 10 by 12', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(570, 'Tote Colored Base A4 print DTF 12  by 14', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(571, 'Tote Colored Base A4 print DTF 13 by 15', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(572, 'Tote Colored Base logo DTF 8 by 10', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(573, 'Tote Colored Base logoprint DTF 10 by 12', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(574, 'Tote Colored Base logo print DTF 12  by 14', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(575, 'Tote Colored Base logo print DTF 13 by 15', NULL, 'Tote', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(576, 'Sintra A4 3mm UV', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(577, 'Sintra A4 5mm UV', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(578, 'Sintra A3 3mm UV', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(579, 'Canvas 20 by 30', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(580, 'Canvas 25 by 30', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(581, 'Canvas 20 by 20', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(582, 'Sintra Sticker A4', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(583, 'Sintra Sticker A3', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(584, 'Sintra Sticker A2', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(585, 'Sintra Sticker A1', NULL, 'Signage', 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 13:32:52', '2026-05-27 13:32:52', 5),
(586, 'Printabe PVC Sheet', NULL, 'Paper', 0, 82, 0, 82, 4800.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 16:32:16', '2026-05-28 09:35:00', 5),
(587, 'Hook', NULL, 'Other', 0, 38, 0, 38, 200.00, 1, 2.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 16:32:40', '2026-05-28 09:35:00', 5),
(588, 'Print', NULL, 'Paper', 0, 482, 0, 482, 500.00, 1, 1.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 16:33:02', '2026-05-28 09:35:00', 5),
(589, 'Printabe PVC Sheet (Large)Test', NULL, 'Paper', 0, 100, 0, 100, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-05-27 17:13:00', '2026-05-27 17:13:00', 5),
(590, 'Printable PVC Sheet', NULL, 'Paper', 0, 100, 0, 100, 4800.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-05-28 12:09:51', '2026-05-28 12:09:51', 5);

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

--
-- Dumping data for table `materials_logs`
--

INSERT INTO `materials_logs` (`id`, `material_id`, `location`, `change_type`, `quantity`, `previous_stock`, `new_stock`, `admin_id`, `audit_id`, `note`, `created_at`) VALUES
(1, 479, 'ph_stock', 'add', 5, 0, 5, 1, NULL, '', '2026-05-27 15:40:49'),
(2, 515, 'total_stock', 'add', 8, 0, 8, 1, NULL, '', '2026-05-27 16:53:49'),
(3, 476, 'total_stock', 'add', 20, 0, 20, 1, NULL, '', '2026-05-27 16:57:04'),
(4, 499, 'total_stock', 'add', 10, 0, 10, 1, NULL, '', '2026-05-28 04:47:37'),
(5, 491, 'total_stock', 'add', 10, 0, 10, 1, NULL, '', '2026-05-28 04:54:26'),
(6, 493, 'total_stock', 'add', 12, 0, 12, 1, NULL, '', '2026-05-28 04:54:32'),
(7, 492, 'total_stock', 'add', 12, 0, 12, 1, NULL, '', '2026-05-28 04:54:34'),
(8, 494, 'total_stock', 'add', 43, 0, 43, 1, NULL, '', '2026-05-28 04:54:37'),
(9, 476, 'total_stock', 'add', 21, 0, 21, 1, NULL, '', '2026-05-28 12:08:50'),
(10, 482, 'total_stock', 'add', 21, 0, 21, 1, NULL, '', '2026-05-28 12:08:59'),
(11, 321, 'total_stock', 'add', 100, 0, 100, 1, NULL, '', '2026-05-28 12:10:11'),
(12, 526, 'total_stock', 'add', 100, 0, 100, 1, NULL, '', '2026-05-28 12:10:15'),
(13, 525, 'total_stock', 'add', 100, 0, 100, 1, NULL, '', '2026-05-28 12:10:18'),
(14, 528, 'total_stock', 'add', 100, 0, 100, 1, NULL, 'Stock add on total_stock by 100 units', '2026-05-29 14:05:23'),
(15, 512, 'total_stock', 'add', 10, 0, 10, 1, NULL, 'Stock add on total_stock by 10 units', '2026-05-29 14:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','gcash','card') NOT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `payment_proof` text DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `order_status` enum('pending','paid','processing','packed','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `total_amount`, `payment_method`, `payment_reference`, `payment_proof`, `payment_status`, `order_status`, `created_at`) VALUES
(1, 'ORD-20260316-3850', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 2300.00, 'gcash', NULL, NULL, 'failed', 'pending', '2026-03-16 17:42:09'),
(2, 'ORD-20260316-6741', 'dasda', 'lawrenzesalvador29@gmail.com', '09926611791', 750.00, 'cash', NULL, NULL, 'pending', 'processing', '2026-03-16 17:48:12'),
(3, 'ORD-20260316-2554', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 2300.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-03-16 17:52:53'),
(4, 'ORD-20260316-2093', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 3250.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-03-16 17:54:08'),
(5, 'ORD-20260316-7892', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 950.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-03-16 17:55:45'),
(6, 'ORD-20260316-9714', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 2300.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-03-16 17:56:22'),
(7, 'ORD-20260316-7075', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 3250.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-03-16 18:21:21'),
(8, 'ORD-20260317-1091', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 4500.00, 'gcash', NULL, NULL, 'failed', 'pending', '2026-03-17 03:32:41'),
(9, 'ORD-20260317-9771', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 2300.00, 'gcash', NULL, NULL, 'failed', 'paid', '2026-03-17 03:36:08'),
(10, 'ORD-20260410-9970', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 3100.00, 'gcash', NULL, NULL, 'failed', 'processing', '2026-04-10 12:48:45'),
(11, 'ORD-20260411-9696', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 1850.00, 'cash', NULL, NULL, 'pending', 'delivered', '2026-04-11 07:23:13'),
(12, 'ORD-20260411-7504', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 1850.00, 'cash', NULL, NULL, 'paid', 'shipped', '2026-04-11 07:23:27'),
(13, 'ORD-20260411-4285', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 1850.00, 'cash', NULL, NULL, 'pending', 'delivered', '2026-04-11 07:23:36'),
(14, 'ORD-20260411-2950', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 24750.00, 'cash', NULL, NULL, 'pending', 'delivered', '2026-04-11 07:26:39'),
(15, 'ORD-20260414-1077', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 6650.00, 'cash', NULL, NULL, 'pending', 'shipped', '2026-04-14 03:09:00'),
(16, 'ORD-20260414-7770', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 2300.00, 'cash', NULL, NULL, 'paid', 'processing', '2026-04-14 03:09:40'),
(17, 'ORD-20260414-8809', 'Jessica Lorano', 'jessica@gmail.com', '09121231234', 42500.00, 'cash', NULL, NULL, 'paid', 'shipped', '2026-04-14 03:11:04'),
(18, 'ORD-20260414-9465', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 16200.00, 'cash', NULL, NULL, 'paid', 'delivered', '2026-04-14 13:55:05'),
(19, 'ORD-20260414-4910', 'Juan Dela Cruz', 'Juan@gmail.com', '09121231234', 39550.00, 'gcash', NULL, NULL, 'failed', 'processing', '2026-04-14 14:04:47'),
(20, 'ORD-20260414-5179', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 39550.00, 'cash', NULL, NULL, 'pending', 'delivered', '2026-04-14 14:05:35'),
(21, 'ORD-20260414-4339', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 750.00, 'gcash', NULL, NULL, 'failed', 'delivered', '2026-04-14 14:17:38'),
(22, 'ORD-20260414-9711', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 4500.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-04-14 15:08:47'),
(23, 'ORD-20260414-2754', 'Jessie Jey', 'jessie@gmail.com', '09121231234', 3750.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-04-14 15:16:21'),
(24, 'ORD-20260414-7347', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 19500.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-04-14 15:19:28'),
(25, 'ORD-20260416-7712', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 7500.00, 'gcash', NULL, NULL, 'paid', 'packed', '2026-04-16 04:21:37'),
(26, 'ORD-20260430-1154', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 1950.00, 'cash', NULL, NULL, 'pending', 'processing', '2026-04-30 10:13:57'),
(27, 'ORD-20260502-1259', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 950.00, 'cash', NULL, NULL, 'pending', 'processing', '2026-05-02 11:41:54'),
(28, 'ORD-20260502-6014', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 950.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-02 12:21:07'),
(29, 'ORD-20260502-6615', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 19500.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-05-02 12:22:38'),
(30, 'ORD-20260502-8112', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 2300.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-05-02 12:24:04'),
(31, 'ORD-20260503-3371', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 12000.00, 'cash', NULL, NULL, 'paid', 'delivered', '2026-05-03 01:44:38'),
(32, 'ORD-20260503-6663', 'Trexie Dela Cruz', 'trexie@gmail.com', '09395529749', 12600.00, 'cash', NULL, NULL, 'paid', 'delivered', '2026-05-03 01:46:05'),
(33, 'ORD-20260503-6211', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 2550.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-05-03 13:04:08'),
(34, 'ORD-20260504-1854', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 14400.00, 'gcash', NULL, NULL, 'paid', 'delivered', '2026-05-04 03:24:30'),
(35, 'ORD-20260504-1294', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 23000.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-04 03:25:17'),
(36, 'ORD-20260506-8501', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 5800.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-06 03:24:28'),
(37, 'ORD-20260507-2642', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 3050.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-07 12:38:51'),
(38, 'ORD-20260507-8908', 'Jay Michael Castillo', 'jm@gmail.com', '09395529749', 3600.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-07 13:45:45'),
(39, 'ORD-20260508-1601', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 5600.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-08 07:38:44'),
(40, 'ORD-20260508-8679', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 10600.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-08 07:54:14'),
(41, 'ORD-20260508-3648', 'Jay Michael Castillo', 'jessica@gmail.com', '09395529749', 950.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-08 08:08:45'),
(42, 'ORD-20260508-7443', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 750.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-08 08:59:31'),
(43, 'ORD-20260508-1450', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 12350.00, 'cash', NULL, NULL, 'paid', 'delivered', '2026-05-08 09:10:08'),
(44, 'ORD-20260508-1751', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 3350.00, 'cash', NULL, NULL, 'pending', 'pending', '2026-05-08 12:10:49'),
(45, 'ORD-20260508-1112', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 15650.00, 'gcash', NULL, NULL, 'pending', 'delivered', '2026-05-08 12:13:22'),
(46, 'ORD-20260508-3456', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 1400.00, 'cash', NULL, NULL, 'paid', 'delivered', '2026-05-08 14:52:59'),
(47, 'ORD-20260511-9101', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', 4350.00, 'cash', NULL, NULL, 'paid', 'processing', '2026-05-11 11:40:52');

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

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 5, 'Couple Wine Set', 1, 2300.00),
(2, 2, 13, 'Baby Shower Favors', 1, 750.00),
(3, 3, 5, 'Couple Wine Set', 1, 2300.00),
(4, 4, 5, 'Couple Wine Set', 1, 2300.00),
(5, 4, 10, 'Anniversary Photo Album', 1, 950.00),
(6, 5, 10, 'Anniversary Photo Album', 1, 950.00),
(7, 6, 5, 'Couple Wine Set', 1, 2300.00),
(8, 7, 10, 'Anniversary Photo Album', 1, 950.00),
(9, 7, 5, 'Couple Wine Set', 1, 2300.00),
(10, 8, 13, 'Baby Shower Favors', 1, 750.00),
(11, 8, 1, 'Baby Welcome Kit', 1, 1950.00),
(12, 8, 9, 'Birthday Surprise Box', 1, 1800.00),
(13, 9, 5, 'Couple Wine Set', 1, 2300.00),
(14, 10, 8, 'Executive Gift Set', 1, 3100.00),
(15, 11, 3, 'Wedding Memory Frame', 1, 1100.00),
(16, 11, 13, 'Baby Shower Favors', 1, 750.00),
(17, 12, 3, 'Wedding Memory Frame', 1, 1100.00),
(18, 12, 13, 'Baby Shower Favors', 1, 750.00),
(19, 13, 3, 'Wedding Memory Frame', 1, 1100.00),
(20, 13, 13, 'Baby Shower Favors', 1, 750.00),
(21, 14, 15, 'Valentine Chocolate Set', 15, 1650.00),
(22, 15, 10, 'Anniversary Photo Album', 7, 950.00),
(23, 16, 5, 'Couple Wine Set', 1, 2300.00),
(24, 17, 8, 'Executive Gift Set', 5, 3100.00),
(25, 17, 4, 'Spa Relaxation Set', 5, 1650.00),
(26, 17, 9, 'Birthday Surprise Box', 5, 1800.00),
(27, 17, 1, 'Baby Welcome Kit', 5, 1950.00),
(28, 18, 9, 'Birthday Surprise Box', 9, 1800.00),
(29, 19, 1, 'Baby Welcome Kit', 5, 1950.00),
(30, 19, 6, 'Success Gift Box', 5, 1250.00),
(31, 19, 2, 'Gourmet Chocolate Box', 5, 1400.00),
(32, 19, 13, 'Baby Shower Favors', 3, 750.00),
(33, 19, 8, 'Executive Gift Set', 3, 3100.00),
(34, 19, 12, 'Thank You Gift Set', 2, 850.00),
(35, 19, 3, 'Wedding Memory Frame', 3, 1100.00),
(36, 20, 1, 'Baby Welcome Kit', 5, 1950.00),
(37, 20, 6, 'Success Gift Box', 5, 1250.00),
(38, 20, 2, 'Gourmet Chocolate Box', 5, 1400.00),
(39, 20, 13, 'Baby Shower Favors', 3, 750.00),
(40, 20, 8, 'Executive Gift Set', 3, 3100.00),
(41, 20, 12, 'Thank You Gift Set', 2, 850.00),
(42, 20, 3, 'Wedding Memory Frame', 3, 1100.00),
(43, 21, 13, 'Baby Shower Favors', 1, 750.00),
(44, 22, 13, 'Baby Shower Favors', 6, 750.00),
(45, 23, 13, 'Baby Shower Favors', 5, 750.00),
(46, 24, 1, 'Baby Welcome Kit', 10, 1950.00),
(47, 25, 13, 'Baby Shower Favors', 10, 750.00),
(48, 26, 1, 'Baby Welcome Kit', 1, 1950.00),
(49, 27, 10, 'Anniversary Photo Album', 1, 950.00),
(50, 28, 10, 'Anniversary Photo Album', 1, 950.00),
(51, 29, 1, 'Baby Welcome Kit', 10, 1950.00),
(52, 30, 5, 'Couple Wine Set', 1, 2300.00),
(53, 31, 13, 'Baby Shower Favors', 2, 750.00),
(54, 31, 9, 'Birthday Surprise Box', 2, 1800.00),
(55, 31, 5, 'Couple Wine Set', 3, 2300.00),
(56, 32, 9, 'Birthday Surprise Box', 7, 1800.00),
(57, 33, 13, 'Baby Shower Favors', 1, 750.00),
(58, 33, 9, 'Birthday Surprise Box', 1, 1800.00),
(59, 34, 9, 'Birthday Surprise Box', 8, 1800.00),
(60, 35, 5, 'Couple Wine Set', 10, 2300.00),
(61, 36, 13, 'Baby Shower Favors', 1, 750.00),
(62, 36, 10, 'Anniversary Photo Album', 1, 950.00),
(63, 36, 9, 'Birthday Surprise Box', 1, 1800.00),
(64, 36, 5, 'Couple Wine Set', 1, 2300.00),
(65, 37, 5, 'Couple Wine Set', 1, 2300.00),
(66, 37, 13, 'Baby Shower Favors', 1, 750.00),
(67, 38, 9, 'Birthday Surprise Box', 2, 1800.00),
(68, 39, 13, 'Baby Shower Favors', 2, 750.00),
(69, 39, 9, 'Birthday Surprise Box', 1, 1800.00),
(70, 39, 5, 'Couple Wine Set', 1, 2300.00),
(71, 40, 7, 'Romantic Rose Box', 1, 2800.00),
(72, 40, 6, 'Success Gift Box', 1, 1250.00),
(73, 40, 10, 'Anniversary Photo Album', 1, 950.00),
(74, 40, 13, 'Baby Shower Favors', 2, 750.00),
(75, 40, 9, 'Birthday Surprise Box', 1, 1800.00),
(76, 40, 5, 'Couple Wine Set', 1, 2300.00),
(77, 41, 10, 'Anniversary Photo Album', 1, 950.00),
(78, 42, 13, 'Baby Shower Favors', 1, 750.00),
(79, 43, 1, 'Baby Welcome Kit', 2, 1950.00),
(80, 43, 2, 'Gourmet Chocolate Box', 2, 1400.00),
(81, 43, 13, 'Baby Shower Favors', 1, 750.00),
(82, 43, 5, 'Couple Wine Set', 1, 2300.00),
(83, 43, 4, 'Spa Relaxation Set', 1, 1650.00),
(84, 43, 10, 'Anniversary Photo Album', 1, 950.00),
(85, 44, 1, 'Baby Welcome Kit', 1, 1950.00),
(86, 44, 2, 'Gourmet Chocolate Box', 1, 1400.00),
(87, 45, 13, 'Baby Shower Favors', 1, 750.00),
(88, 45, 5, 'Couple Wine Set', 1, 2300.00),
(89, 45, 2, 'Gourmet Chocolate Box', 9, 1400.00),
(90, 46, 2, 'Gourmet Chocolate Box', 1, 1400.00),
(91, 47, 9, 'Birthday Surprise Box', 2, 1800.00),
(92, 47, 13, 'Baby Shower Favors', 1, 750.00);

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

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `payment_status`, `reference_number`, `proof_image`, `verified_by`, `verified_at`, `created_at`) VALUES
(1, 22, 'gcash', 'verified', 'REF-GC-20250926-7391', 'uploads/gcash/gcash_1776179327_5535.png', NULL, '2026-04-14 23:46:11', '2026-04-14 15:08:48'),
(2, 23, 'gcash', 'verified', 'REF-GC-20250926-7391', 'uploads/gcash/gcash_1776179781_6398.jpg', NULL, '2026-04-14 23:46:01', '2026-04-14 15:16:21'),
(3, 24, 'gcash', 'verified', 'REF-GC-20250926-7391', 'uploads/gcash/gcash_ORD-20260414-7347_1776179968.jpg', NULL, '2026-04-14 23:45:33', '2026-04-14 15:19:28'),
(4, 25, 'gcash', 'verified', 'REF-GC-20250926-7391', 'uploads/gcash/gcash_ORD-20260416-7712_1776313297.png', NULL, '2026-04-16 20:22:46', '2026-04-16 04:21:37'),
(5, 26, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-04-30 10:13:57'),
(6, 27, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-02 11:41:54'),
(7, 28, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-02 12:21:07'),
(8, 29, 'gcash', 'verified', '123456789', 'uploads/gcash/gcash_ORD-20260502-6615_1777724558.jpg', NULL, '2026-05-02 20:22:52', '2026-05-02 12:22:38'),
(9, 30, 'gcash', 'pending', '123456789', 'uploads/gcash/gcash_ORD-20260502-8112_1777724644.jpg', NULL, NULL, '2026-05-02 12:24:04'),
(10, 31, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-03 01:44:38'),
(11, 32, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-03 01:46:05'),
(12, 33, 'gcash', 'verified', 'REF-GC-20250926-7391', 'uploads/gcash/gcash_ORD-20260503-6211_1777813448.png', NULL, '2026-05-03 21:08:14', '2026-05-03 13:04:08'),
(13, 34, 'gcash', 'verified', 'REF-GC-20250926-7391', 'uploads/gcash/gcash_ORD-20260504-1854_1777865070.png', NULL, '2026-05-06 17:03:43', '2026-05-04 03:24:30'),
(14, 35, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-04 03:25:17'),
(15, 36, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-06 03:24:28'),
(16, 37, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-07 12:38:51'),
(17, 38, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-07 13:45:45'),
(18, 39, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-08 07:38:44'),
(19, 40, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-08 07:54:14'),
(20, 41, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-08 08:08:45'),
(21, 42, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-08 08:59:31'),
(22, 43, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-08 09:10:08'),
(23, 44, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-08 12:10:49'),
(24, 45, 'gcash', 'verified', '123456789', 'uploads/gcash/gcash_ORD-20260508-1112_1778242402.png', NULL, '2026-05-08 23:47:08', '2026-05-08 12:13:23'),
(25, 46, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-08 14:53:00'),
(26, 47, 'cash', 'pending', NULL, NULL, NULL, NULL, '2026-05-11 11:40:52');

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

--
-- Dumping data for table `pending_admins`
--

INSERT INTO `pending_admins` (`request_id`, `username`, `email`, `password`, `submitted_at`) VALUES
(0, 'admin12345', 'admin12345@gmail.com', '$2y$10$rm2OBvPnwxbEBtT9yqzwcOHH16K8.uVR6EbdOcNJiBLzQ7MkFA08W', '2026-04-30 10:33:04'),
(0, 'michael18', 'AdminMichael18@gmail.com', '$2y$10$ndy4YsJg3J1wc5vqm5kkOeFlyfw4k24/qPI7UwoZy0/dla4B2uP8m', '2026-05-02 14:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `material_type` enum('raw_material','assembled_product','print_service','custom') DEFAULT 'assembled_product',
  `category_id` int(11) DEFAULT NULL,
  `product_type_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
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

INSERT INTO `products` (`id`, `name`, `sku`, `material_type`, `category_id`, `product_type_id`, `price`, `production_cost`, `profit_margin`, `unit`, `width_cm`, `height_cm`, `depth_cm`, `weight_grams`, `color_options`, `size_options`, `material_specs`, `finishing_options`, `image`, `description`, `created_at`, `updated_at`, `featured`, `status`, `estimated_production_time`) VALUES
(1, 'Baby Welcome Kit', NULL, 'assembled_product', NULL, NULL, 1950.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/baby_welcome_kit.jpg', 'Adorable baby essentials including a onesie, soft blanket, rattle, and plush toy.', '2026-03-16 16:10:07', '2026-05-04 03:20:45', 0, 'active', NULL),
(2, 'Gourmet Chocolate Box', NULL, 'assembled_product', NULL, NULL, 1400.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/gourmet_chocolate_box.avif', '24-piece assorted Belgian chocolates in an elegant premium box.', '2026-03-16 16:10:07', '2026-05-06 02:22:19', 0, 'active', NULL),
(3, 'Wedding Memory Frame', NULL, 'assembled_product', NULL, NULL, 1100.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/wedding_memory_frame.jpg', 'Elegant silver-plated frame engraved with \"Mr & Mrs\" design.', '2026-03-16 16:10:07', '2026-05-06 02:22:10', 0, 'active', NULL),
(4, 'Spa Relaxation Set', NULL, 'assembled_product', NULL, NULL, 1650.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/spa_relaxation_set.jpg', 'Pamper yourself with bath bombs, body lotion, and face mask.', '2026-03-16 16:10:07', '2026-05-03 06:22:37', 0, 'active', NULL),
(5, 'Couple Wine Set', NULL, 'assembled_product', NULL, NULL, 2300.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/couple_wine_set.avif', 'Premium red wine with two engraved crystal wine glasses.', '2026-03-16 16:10:07', '2026-05-07 12:38:51', 0, 'active', NULL),
(6, 'Success Gift Box', NULL, 'assembled_product', NULL, NULL, 1250.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/success_gift_box.jpg', 'Inspirational notebook, premium pen, and celebration treats.', '2026-03-16 16:10:07', '2026-05-08 12:15:59', 0, 'active', NULL),
(7, 'Romantic Rose Box', NULL, 'assembled_product', NULL, NULL, 2800.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/romantic_rose_box.jpg', 'Preserved roses arranged in a luxury heart-shaped box.', '2026-03-16 16:10:07', '2026-05-03 06:22:43', 0, 'active', NULL),
(8, 'Executive Gift Set', NULL, 'assembled_product', NULL, NULL, 3100.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/executive_gift_set.jpg', 'Luxury leather planner with pen and premium coffee selection.', '2026-03-16 16:10:07', '2026-04-16 03:23:32', 0, 'active', NULL),
(9, 'Birthday Surprise Box', NULL, 'assembled_product', NULL, NULL, 1800.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/birthday_surprise_box.jpg', 'Birthday cake flavored treats, candles, and party poppers.', '2026-03-16 16:10:07', '2026-05-03 06:22:48', 0, 'active', NULL),
(10, 'Anniversary Photo Album', NULL, 'assembled_product', NULL, NULL, 950.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/anniversary_photo_album.avif', 'Leather-bound photo album with \"Our Love Story\" embossing.', '2026-03-16 16:10:07', '2026-05-03 06:22:45', 0, 'active', NULL),
(11, 'Christmas Hamper', NULL, 'assembled_product', NULL, NULL, 3500.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/christmas_hamper.jpg', 'Festive hamper with wine, cheese, chocolates, and Christmas treats.', '2026-03-16 16:10:07', '2026-04-16 03:23:54', 0, 'active', NULL),
(12, 'Thank You Gift Set', NULL, 'assembled_product', NULL, NULL, 850.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/thank_you_gift_set.jpg', 'Premium thank you cards with gourmet cookies and tea.', '2026-03-16 16:10:07', '2026-05-06 11:39:39', 0, 'active', NULL),
(13, 'Baby Shower Favors', NULL, 'assembled_product', NULL, NULL, 750.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/baby_shower_favors.jpg', 'Set of 5 baby shower favors with mini onesies and candies.', '2026-03-16 16:10:07', '2026-05-07 12:38:51', 0, 'active', NULL),
(14, 'Wedding Guest Book', NULL, 'assembled_product', NULL, NULL, 1200.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/wedding_guest_book.jpg', 'Elegant wedding guest book with pen holder.', '2026-03-16 16:10:07', '2026-04-16 03:23:45', 0, 'active', NULL),
(15, 'Valentine Chocolate Set', NULL, 'assembled_product', NULL, NULL, 1650.00, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'assets/images/products/valentine_chocolate_set.jpg', 'Heart-shaped chocolate box with assorted pralines.', '2026-03-16 16:10:07', '2026-04-16 03:23:48', 0, 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `default_materials` text DEFAULT NULL COMMENT 'JSON of default materials for this product type',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `quote_number` varchar(50) NOT NULL,
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
(17, 'AI-202605-0008', NULL, '3dqne18loiism1rmf9ltub9990', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'accepted', 47100.00, 0.00, 0.00, 47100.00, '', NULL, '2026-05-08 12:07:08', '2026-05-09', '2026-05-28 06:18:02', 0, NULL, NULL, NULL),
(18, 'AI-202605-0009', NULL, '3dqne18loiism1rmf9ltub9990', 'Jay Michael Castillo', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 5450.00, 0.00, 0.00, 5450.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0009_20260511_171758.pdf', '2026-05-08 12:07:39', '2026-05-07', '2026-05-11 15:17:58', 0, NULL, NULL, NULL),
(19, 'AI-202605-0010', NULL, '20qs9ccst03kdoomn6t508rmak', 'Juan Dela Cruz', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'accepted', 3650.00, 0.00, 0.00, 3650.00, '', NULL, '2026-05-08 12:19:30', '2026-05-10', '2026-05-08 12:20:08', 0, NULL, NULL, NULL),
(21, 'AI-202605-0011', NULL, 'hdghbdo4v0907thmdjmo9tf1ru', 'Jay Michael Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 11950.00, 0.00, 0.00, 11950.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0011_20260511_174814.pdf', '2026-05-08 15:23:59', '2026-05-07', '2026-05-25 12:44:35', 0, NULL, NULL, NULL),
(22, 'AI-202605-0012', NULL, 'j3e0n5kfnl0aaa1eoq1meub2g2', 'Juan Dela Cruz', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 1700.00, 0.00, 0.00, 1700.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0012_20260511_174945.pdf', '2026-05-08 15:32:03', '2026-05-08', '2026-05-11 15:49:45', 0, NULL, NULL, NULL),
(23, 'AI-202605-0013', NULL, 'hdghbdo4v0907thmdjmo9tf1ru', 'Jay Michael Castillo', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 950.00, 0.00, 0.00, 950.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0013_20260511_175117.pdf', '2026-05-08 15:58:32', '2026-05-09', '2026-05-11 15:51:17', 0, NULL, NULL, NULL),
(25, 'AI-202605-0014', NULL, 'mhar49atc0c6okhrqi3ae7j2af', 'Jay Michael Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'accepted', 3650.00, 0.00, 0.00, 3650.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0014_20260526_204824.pdf', '2026-05-11 15:22:12', '2026-05-13', '2026-05-27 08:36:50', 0, NULL, NULL, NULL),
(26, 'Q-202605-0001', NULL, 'g333hs55uoraa8brseo53gca8p', 'Jay Michael Castillo', 'Jay Michael Castillo', 'Michael@gmail.com', '09395529749', NULL, 'accepted', 1050.00, 0.00, 0.00, 1050.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_Q-202605-0001_20260527_043951.pdf', '2026-05-26 18:57:28', '2026-06-25', '2026-05-27 08:34:56', 0, NULL, NULL, NULL),
(27, 'Q-202605-0002', NULL, 'lehemsa8uf154paeco65803sjq', 'Jay Michael Castillo', 'Jay Michael Castillo', 'Michael@gmail.com', '09395529749', NULL, 'converted', 1049.79, 0.00, 0.00, 1049.79, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0003_20260527_155226.pdf', '2026-05-27 02:42:03', '2026-06-26', '2026-05-27 13:52:27', 0, NULL, NULL, NULL),
(36, 'Q-2026-05-0017', NULL, 'lehemsa8uf154paeco65803sjq', 'Organization Name', 'Jay Michael Castillo', 'jaymichael@gmail.com', '09395529749', NULL, 'accepted', 1050.00, 0.00, 0.00, 1050.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_Q-2026-05-0017_20260527_061313.pdf', '2026-05-27 04:05:51', NULL, '2026-05-27 13:12:17', 0, NULL, NULL, NULL),
(37, 'Q-2026-05-0018', NULL, 'lehemsa8uf154paeco65803sjq', 'Organization Name', 'Jay Michael Castillo', 'jaymichael@gmail.com', '09395529749', NULL, 'converted', 20.00, 0.00, 0.00, 20.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_Q-2026-05-0018_20260528_081844.pdf', '2026-05-27 04:29:02', NULL, '2026-05-28 06:18:44', 0, NULL, NULL, NULL),
(38, 'QT-20260527-0167', NULL, NULL, 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', NULL, 'accepted', 1050.00, 0.00, 0.00, 1050.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0070_20260528_075025.pdf', '2026-05-27 08:10:28', NULL, '2026-05-28 06:18:38', 0, NULL, NULL, NULL),
(39, 'QT-20260528-4245', NULL, NULL, 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'expired', 1050.00, 0.00, 0.00, 1050.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0071_20260528_075543.pdf', '2026-05-28 05:55:02', NULL, '2026-05-28 14:12:37', 0, NULL, NULL, NULL),
(40, 'QT-20260528-2245', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 1050.00, 0.00, 0.00, 1050.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0072_20260528_081944.pdf', '2026-05-28 06:17:49', NULL, '2026-05-28 06:19:44', 0, NULL, NULL, NULL),
(41, 'QT-20260528-8756', NULL, NULL, 'Jay Michael Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 1050.00, 0.00, 0.00, 1050.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0073_20260528_094748.pdf', '2026-05-28 07:47:05', NULL, '2026-05-29 07:35:05', 0, NULL, NULL, NULL),
(42, 'QT-20260528-2162', NULL, NULL, 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 2400.00, 0.00, 0.00, 2400.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0074_20260528_102000.pdf', '2026-05-28 08:19:27', NULL, '2026-05-28 08:20:19', 0, NULL, NULL, NULL),
(43, 'QT-20260528-4099', NULL, NULL, 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 56000.00, 0.00, 0.00, 56000.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0076_20260528_114220.pdf', '2026-05-28 09:41:41', NULL, '2026-05-28 09:42:21', 0, NULL, NULL, NULL),
(44, 'AI-2605-0001', NULL, NULL, 'Art School', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'accepted', 100.00, 0.00, 0.00, 100.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0077_20260529_112818.pdf', '2026-05-29 09:26:58', NULL, '2026-05-29 09:53:13', 0, NULL, NULL, NULL),
(45, 'AI-0526-0001', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 425.00, 0.00, 0.00, 425.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0526-0001_20260529_113610.pdf', '2026-05-29 09:34:22', NULL, '2026-05-29 09:36:10', 0, NULL, NULL, NULL),
(46, 'AI-0526-0002', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Timugan, Los Banos, Laguan', 'converted', 94.00, 0.00, 0.00, 94.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0086_20260529_150035.pdf', '2026-05-29 09:38:55', NULL, '2026-05-29 13:04:08', 1, 60, '2026-05-29 13:04:08', 1),
(47, 'AI-0526-0003', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 450.00, 0.00, 0.00, 450.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0085_20260529_145824.pdf', '2026-05-29 10:13:20', NULL, '2026-05-29 12:58:50', 1, 59, '2026-05-29 12:58:50', 1);

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
(215, 25, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(216, 25, NULL, 'Baby Shower Favors', 1, 750.00, 750.00),
(217, 25, NULL, 'Baby Welcome Kit', 1, 1950.00, 1950.00),
(218, 26, NULL, 'Acrylic Paint', 21, 50.00, 1050.00),
(222, 36, NULL, 'Acrylic Keychain', 21, 50.00, 1050.00),
(229, 27, NULL, 'Acrylic Keychain<div><ul><li>Acrylic Paint</li><li>Hook</li><li>PVC Sheet</li></ul></div>', 21, 49.99, 1049.79),
(233, 38, NULL, '<b>Acrylic Keychain</b><div><ul><li>Portable PVC Sheet</li><li>Hook</li><li>Print</li></ul></div>', 50, 21.00, 1050.00),
(235, 39, NULL, 'Acrylic Keychain<div><ul><li>Printable PVC Sheet</li><li>Hook</li><li>Print</li></ul></div>', 21, 50.00, 1050.00),
(236, 40, NULL, 'Acrylic Keychain 5x9 cm cm', 21, 50.00, 1050.00),
(237, 12, NULL, 'Anniversary Photo Album', 1, 950.00, 950.00),
(238, 37, NULL, 'Personalize Mug', 1, 20.00, 20.00),
(243, 42, NULL, 'Bamboo Notebook A5 SizeA4 Black and White', 5, 51.00, 255.00),
(244, 42, NULL, 'Bamboo 500mlA4 Black and White', 6, 195.00, 1170.00),
(245, 42, NULL, 'Bamboo 500ml', 5, 195.00, 975.00),
(247, 43, NULL, '6-in-1 Combo Heat Press Press', 5, 11200.00, 56000.00),
(248, 41, NULL, 'Acrylic Key Chain<blockquote style=\"margin: 0 0 0 40px; border: none; padding: 0px;\"><div><ul><li>Printable PVC Sheet</li><li>Hook</li><li>Print</li></ul></div></blockquote>', 21, 50.00, 1050.00),
(249, 44, NULL, 'Bamboo ballpoint', 10, 10.00, 100.00),
(250, 44, NULL, 'Baseball BGC brand', 0, 59.00, 0.00),
(255, 45, NULL, 'Bamboo ballpoint', 20, 10.00, 200.00),
(256, 45, NULL, 'A4 Colored', 5, 45.00, 225.00),
(263, 46, NULL, 'Bond Paper  A4', 100, 0.44, 44.00),
(264, 46, NULL, 'Bamboo ballpoint', 5, 10.00, 50.00),
(266, 47, NULL, 'A4 Colored', 10, 45.00, 450.00);

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
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `session_id`, `product_id`, `added_at`, `created_at`) VALUES
(6, 'q44sr1eood0iq92duj3ettr3fl', 5, '2026-03-16 17:39:51', '2026-03-16 17:39:51'),
(7, 'q44sr1eood0iq92duj3ettr3fl', 10, '2026-03-16 18:23:56', '2026-03-16 18:23:56'),
(8, 'q44sr1eood0iq92duj3ettr3fl', 13, '2026-03-16 18:23:58', '2026-03-16 18:23:58'),
(13, 'g5e29fij4bgt7fvqnrg4gm3m4e', 10, '2026-03-17 03:38:52', '2026-03-17 03:38:52'),
(14, 'g5e29fij4bgt7fvqnrg4gm3m4e', 5, '2026-03-17 03:38:53', '2026-03-17 03:38:53'),
(15, 'g5e29fij4bgt7fvqnrg4gm3m4e', 13, '2026-03-17 03:38:54', '2026-03-17 03:38:54'),
(16, 'g5e29fij4bgt7fvqnrg4gm3m4e', 1, '2026-03-17 03:38:55', '2026-03-17 03:38:55'),
(17, 'g5e29fij4bgt7fvqnrg4gm3m4e', 9, '2026-03-17 03:38:56', '2026-03-17 03:38:56'),
(18, 'g5e29fij4bgt7fvqnrg4gm3m4e', 2, '2026-03-17 03:38:58', '2026-03-17 03:38:58'),
(19, 'g5e29fij4bgt7fvqnrg4gm3m4e', 11, '2026-03-17 03:38:59', '2026-03-17 03:38:59'),
(20, 'g5e29fij4bgt7fvqnrg4gm3m4e', 6, '2026-03-17 03:39:00', '2026-03-17 03:39:00'),
(21, 'g5e29fij4bgt7fvqnrg4gm3m4e', 8, '2026-03-17 03:39:01', '2026-03-17 03:39:01'),
(22, 'g5e29fij4bgt7fvqnrg4gm3m4e', 4, '2026-03-17 03:39:02', '2026-03-17 03:39:02'),
(23, 'g5e29fij4bgt7fvqnrg4gm3m4e', 12, '2026-03-17 03:39:03', '2026-03-17 03:39:03'),
(24, 'g5e29fij4bgt7fvqnrg4gm3m4e', 7, '2026-03-17 03:39:04', '2026-03-17 03:39:04'),
(25, 'g5e29fij4bgt7fvqnrg4gm3m4e', 15, '2026-03-17 03:39:05', '2026-03-17 03:39:05'),
(26, 'g5e29fij4bgt7fvqnrg4gm3m4e', 14, '2026-03-17 03:39:05', '2026-03-17 03:39:05'),
(27, 'g5e29fij4bgt7fvqnrg4gm3m4e', 3, '2026-03-17 03:39:07', '2026-03-17 03:39:07'),
(28, 'l34euegf55bstoo6kfa0ajn43j', 5, '2026-03-24 15:03:00', '2026-03-24 15:03:00'),
(29, '48437bvi79pgukmfe79tigtv38', 10, '2026-05-02 12:20:42', '2026-05-02 12:20:42'),
(30, 'l2c6ad3ailcqo7tro6qtgnfk17', 1, '2026-05-06 03:25:24', '2026-05-06 03:25:24'),
(33, 'jf4s581ikbakhhu0ekrk9pos9v', 10, '2026-05-08 08:27:39', '2026-05-08 08:27:39'),
(34, 'jf4s581ikbakhhu0ekrk9pos9v', 13, '2026-05-08 08:27:43', '2026-05-08 08:27:43'),
(35, 'jf4s581ikbakhhu0ekrk9pos9v', 9, '2026-05-08 08:28:36', '2026-05-08 08:28:36'),
(36, 'jf4s581ikbakhhu0ekrk9pos9v', 5, '2026-05-08 08:28:37', '2026-05-08 08:28:37'),
(37, 'jf4s581ikbakhhu0ekrk9pos9v', 2, '2026-05-08 08:39:13', '2026-05-08 08:39:13'),
(38, 'jf4s581ikbakhhu0ekrk9pos9v', 3, '2026-05-08 08:41:39', '2026-05-08 08:41:39'),
(55, 'brhmq4f023da8vt3t57u5v5iis', 8, '2026-05-08 09:09:16', '2026-05-08 09:09:16'),
(62, 'lm9jupcodouok4hi10oivp7t5p', 5, '2026-05-08 09:28:50', '2026-05-08 09:28:50'),
(63, 'lm9jupcodouok4hi10oivp7t5p', 2, '2026-05-08 09:33:28', '2026-05-08 09:33:28'),
(64, 'lm9jupcodouok4hi10oivp7t5p', 3, '2026-05-08 09:33:30', '2026-05-08 09:33:30'),
(65, 'lm9jupcodouok4hi10oivp7t5p', 4, '2026-05-08 09:33:31', '2026-05-08 09:33:31'),
(66, 'lm9jupcodouok4hi10oivp7t5p', 13, '2026-05-08 09:33:36', '2026-05-08 09:33:36'),
(67, 'lm9jupcodouok4hi10oivp7t5p', 1, '2026-05-08 09:41:48', '2026-05-08 09:41:48'),
(68, 'lm9jupcodouok4hi10oivp7t5p', 7, '2026-05-08 09:41:51', '2026-05-08 09:41:51'),
(69, 'lm9jupcodouok4hi10oivp7t5p', 6, '2026-05-08 09:44:34', '2026-05-08 09:44:34'),
(71, 'brhmq4f023da8vt3t57u5v5iis', 10, '2026-05-08 09:45:41', '2026-05-08 09:45:41'),
(72, '20qs9ccst03kdoomn6t508rmak', 1, '2026-05-08 11:49:46', '2026-05-08 11:49:46'),
(73, '20qs9ccst03kdoomn6t508rmak', 2, '2026-05-08 12:10:28', '2026-05-08 12:10:28'),
(74, '20qs9ccst03kdoomn6t508rmak', 10, '2026-05-08 12:10:32', '2026-05-08 12:10:32'),
(75, '20qs9ccst03kdoomn6t508rmak', 13, '2026-05-08 12:10:32', '2026-05-08 12:10:32'),
(76, '20qs9ccst03kdoomn6t508rmak', 9, '2026-05-08 12:10:33', '2026-05-08 12:10:33'),
(77, '20qs9ccst03kdoomn6t508rmak', 5, '2026-05-08 12:10:33', '2026-05-08 12:10:33'),
(78, 'hdghbdo4v0907thmdjmo9tf1ru', 2, '2026-05-08 14:29:11', '2026-05-08 14:29:11'),
(79, 'hdghbdo4v0907thmdjmo9tf1ru', 13, '2026-05-08 14:54:06', '2026-05-08 14:54:06'),
(80, 'ffombdqlo8240vrfumk3v646p2', 10, '2026-05-08 15:14:22', '2026-05-08 15:14:22'),
(81, 'ffombdqlo8240vrfumk3v646p2', 13, '2026-05-08 15:14:22', '2026-05-08 15:14:22'),
(82, 'qnb07fp65a5octvaueuqe4pllu', 13, '2026-05-11 11:40:35', '2026-05-11 11:40:35'),
(83, 'qnb07fp65a5octvaueuqe4pllu', 9, '2026-05-11 11:40:37', '2026-05-11 11:40:37'),
(84, 'qfcfplk87g6aj3c9kd46l85poo', 10, '2026-05-11 11:53:29', '2026-05-11 11:53:29'),
(85, 'qfcfplk87g6aj3c9kd46l85poo', 13, '2026-05-11 11:53:31', '2026-05-11 11:53:31'),
(86, 'qfcfplk87g6aj3c9kd46l85poo', 4, '2026-05-11 11:53:38', '2026-05-11 11:53:38'),
(87, 'qfcfplk87g6aj3c9kd46l85poo', 8, '2026-05-11 11:53:40', '2026-05-11 11:53:40'),
(88, 'qfcfplk87g6aj3c9kd46l85poo', 2, '2026-05-11 12:39:50', '2026-05-11 12:39:50');

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
  ADD KEY `idx_status` (`order_status`);

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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_price` (`price`),
  ADD KEY `fk_product_type` (`product_type_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

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
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bom_audit`
--
ALTER TABLE `bom_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `delivery_receipts`
--
ALTER TABLE `delivery_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=591;

--
-- AUTO_INCREMENT for table `materials_logs`
--
ALTER TABLE `materials_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

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
