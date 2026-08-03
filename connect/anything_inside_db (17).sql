-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2026 at 10:29 AM
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
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-03 16:06:19'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-03 16:31:10'),
(0, 1, 'jaymichaelmontemarcastillo@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-07-03 20:58:45'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-03 20:59:03'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-05 21:14:13'),
(0, 6, 'admin12345@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-07-05 22:18:32'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-05 22:19:34'),
(0, 9, 'jmmc@gmail.com', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-05 22:20:03'),
(0, 8, 'admin123', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-05 22:20:41'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-05 22:21:11'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-08 10:28:34'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-08 10:40:29'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-10 20:43:17'),
(0, 1, 'jaymichaelmontemarcastillo@gmail.com', 'Failed login attempt', NULL, 'Logins', 'Failed', '2026-07-10 21:38:59'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-10 21:39:13'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-10 21:49:37'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-10 23:02:08'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-11 12:13:44'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-11 12:43:46'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-11 19:16:30'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-11 20:10:04'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-12 13:19:15'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-13 14:21:42'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-14 10:05:27'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-14 15:11:03'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-14 16:58:01'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-14 17:23:51'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-14 18:43:48'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-15 14:33:09'),
(0, 1, 'Jay Michael', 'Password reset via forgot password', NULL, 'Account', 'Success', '2026-07-15 14:38:43'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-15 14:38:54'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-16 16:54:21'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-18 08:37:36'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-19 20:27:20'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-20 14:03:18'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 09:15:18'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 09:54:56'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 09:57:49'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 10:02:00'),
(0, 1, 'Jay Michael', 'Password reset via forgot password', NULL, 'Account', 'Success', '2026-07-22 10:06:03'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 10:06:17'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 10:18:41'),
(0, 8, 'admin123', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 10:19:09'),
(0, 8, 'Jay Michael Castillo', 'Password reset via forgot password', NULL, 'Account', 'Success', '2026-07-22 10:23:53'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 10:40:48'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 10:44:20'),
(0, 1, 'Jay Michael', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 17:19:33'),
(0, 1, 'Jay Michael', 'Failed to update email settings: Current password is incorrect.', NULL, 'Email Settings', 'Failed', '2026-07-22 18:32:16'),
(0, 1, 'Jay Michael', 'Updated Gmail address from \'jaymichaelmontemarcastillo@gmail.com\' to \'jaymichaelmontemarcastillo@gmail.com\' and updated app password', NULL, 'Email Settings Update', 'Success', '2026-07-22 18:32:23'),
(0, 1, 'Jay Michael', 'Profile updated', NULL, 'Profile Management', 'Success', '2026-07-22 18:32:47'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 18:34:28'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 18:34:40'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 18:37:23'),
(0, 1, 'Jay Michael Castillo', 'Pending admin request processing failed: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'created_at\' in \'field list\'', '6', 'Pending Admin Management', 'Failed', '2026-07-22 18:38:00'),
(0, 1, 'Jay Michael Castillo', 'Pending admin request processing failed: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'created_at\' in \'field list\'', '6', 'Pending Admin Management', 'Failed', '2026-07-22 18:38:05'),
(0, 1, 'Jay Michael Castillo', 'Accepted pending admin request for \'Michael\' (Email: jaymichaelcastillotk4@gmail.com, Submitted: 2026-07-22 06:36:59 PM)', '6', 'Pending Admin Management', 'Success', '2026-07-22 18:39:04'),
(0, 1, 'Jay Michael Castillo', 'Changed status of quotation #AI-0726-0004 (ID: 65) for client \'LSPU LBC\' from \'converted\' to \'accepted\'', '65', 'Quotation Status', 'Success', '2026-07-22 18:43:06'),
(0, 1, 'Jay Michael Castillo', 'Changed status of quotation #AI-0726-0004 (ID: 65) for client \'LSPU LBC\' from \'accepted\' to \'converted\'', '65', 'Quotation Status', 'Success', '2026-07-22 18:43:16'),
(0, 1, 'Jay Michael Castillo', 'Updated quotation (ID: 65) for client \'LSPU LBC\' - Changes: email: \'jaymichaelmontemarcastillo@gmail.com\' → \'castillojaymichaeltk5@gmail.com\', tax: 0.00% → 0%, discount: ₱0.00 → ₱0.00 - Items: 1 item(s)', '65', 'Quotation Management', 'Success', '2026-07-22 18:43:31'),
(0, 1, 'Jay Michael Castillo', 'Updated email settings: App Password updated', NULL, 'Email Settings Update', 'Success', '2026-07-22 18:49:38'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 18:50:29'),
(0, 1, 'Jay Michael Castillo', 'Updated email settings: App Password updated', NULL, 'Email Settings Update', 'Success', '2026-07-22 18:50:47'),
(0, 10, 'Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 18:51:52'),
(0, 10, 'Michael Castillo', 'Password reset via forgot password', NULL, 'Account', 'Success', '2026-07-22 18:52:46'),
(0, 10, 'Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-22 18:53:03'),
(0, 1, 'Jay Michael Castillo', 'Auto-cleanup enabled: Deleted 161 activity log(s) older than 2 months', NULL, 'System Maintenance', 'Success', '2026-07-22 19:12:59'),
(0, 1, 'Jay Michael Castillo', 'Auto-cleanup enabled: Deleted 50 activity log(s) older than 1 month', NULL, 'System Maintenance', 'Success', '2026-07-22 19:13:36'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-23 15:32:16'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-24 20:24:58'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-28 19:59:40'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-07-31 10:24:09'),
(0, 1, 'Jay Michael Castillo', 'Admin logged in', NULL, 'Logins', 'Success', '2026-08-03 13:05:25');

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
(1, 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '$2y$10$JjVo9BSbiEo1FGhvKaKVZ.kOz2QZsxUZ/cUr0UxkwOj07BENJblYq', 'Admin', 'uploads/admins/1774715140_5010eb1b-7cfe-4f7b-8a46-174d4127c9ed.jfif', 'Active', '2026-03-27 09:06:30', '2026-08-03 05:05:25', '2026-08-03 05:05:25'),
(8, 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '$2y$10$eA1vVJDiXTw6dj2H9ZRDfunAPvDC1Jd9IHalKqC7qm2HQMCmHDcRy', 'Finance', 'uploads/admins/1784686801_737400.jpg', 'Active', '2026-07-05 14:19:43', '2026-07-22 02:23:53', '2026-07-22 02:19:09'),
(10, 'Michael Castillo', 'jaymichaelcastillo18@gmail.com', '$2y$10$UFmiX6WOABuSzvpmQteJaew4NyJiCQjbjS59ynjlJapV1eITg0G0.', 'Admin', NULL, 'Active', '2026-07-22 10:34:48', '2026-07-22 10:53:03', '2026-07-22 10:53:03'),
(11, 'Michael', 'jaymichaelcastillotk4@gmail.com', '$2y$10$Ox3FRKAhNH3CfK9CLH2VQOnFDaJ0piiQIalvv8zSk75mgJqAUgBPO', 'Admin', NULL, 'Active', '2026-07-22 10:39:04', '2026-07-23 08:43:55', NULL);

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
  `audit_name` varchar(255) DEFAULT NULL,
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
  `auto_compute` tinyint(1) DEFAULT 1,
  `electricity_shop` decimal(15,2) DEFAULT 0.00,
  `electricity_production` decimal(15,2) DEFAULT 0.00,
  `overhead_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`overhead_data`)),
  `working_hours` decimal(10,2) DEFAULT 250.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom_audit`
--

INSERT INTO `bom_audit` (`id`, `quote_id`, `audit_name`, `items`, `materials`, `rejects`, `quotation_items`, `signatures`, `status`, `created_at`, `updated_at`, `is_completed`, `total_material_cost`, `total_reject_cost`, `total_amount_due`, `profit`, `overhead_shop_rent`, `overhead_fixed_salaries`, `overhead_shop_utilities`, `overhead_subscriptions`, `overhead_machine_depreciation`, `overhead_maintenance_repair`, `overhead_marketing`, `overhead_electricity`, `total_overhead`, `overhead_per_hour`, `production_hours`, `total_cost_with_overhead`, `auto_compute`, `electricity_shop`, `electricity_production`, `overhead_data`, `working_hours`) VALUES
(6, NULL, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":0,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:00:27', '2026-05-27 10:00:27', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(7, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":49.99,\"total_amount\":1049.79}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:16:50', '2026-05-27 10:16:50', 1, 0.00, 0.00, 1049.79, 1049.79, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(8, NULL, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_price\":49.99,\"total_amount\":149.97}]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_cost\":11200,\"total_cost\":11200},{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":4,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:21:18', '2026-05-27 10:21:18', 1, 11200.00, 0.00, 149.97, -11050.03, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(9, NULL, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_price\":49.99,\"total_amount\":149.97}]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_cost\":11200,\"total_cost\":11200},{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":4,\"unit_cost\":0,\"total_cost\":0}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":3,\"unit_cost\":0,\"total_cost\":0}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:23:57', '2026-05-27 10:23:57', 1, 11200.00, 0.00, 149.97, -11050.03, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(15, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 10:34:34', '2026-05-27 10:34:34', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(32, NULL, NULL, '[{\"name\":\"Test\",\"quantity\":1,\"unit_price\":100,\"total_amount\":100}]', '[]', '[]', NULL, '{\"created_by\":\"Test\",\"audited_by\":\"Test\",\"acknowledged_by\":\"Test\"}', 'draft', '2026-05-27 11:12:23', '2026-05-27 11:12:23', 1, 0.00, 0.00, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(38, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":70,\"total_amount\":70}]', '[{\"id\":341,\"name\":\"Bamboo Notebook A5 Size\",\"quantity\":10,\"unit_cost\":51,\"total_cost\":510},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":9,\"unit_cost\":10,\"total_cost\":90}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 15:36:36', '2026-05-27 15:36:36', 1, 600.00, 20.00, 70.00, -550.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(39, NULL, NULL, '[{\"name\":\"Sport\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Sport\",\"quantity\":10,\"unit_price\":500,\"total_amount\":5000}]', '[{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":3,\"unit_cost\":59,\"total_cost\":177}]', '[{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":2,\"unit_cost\":59,\"total_cost\":118}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 16:09:36', '2026-05-27 16:09:36', 1, 177.00, 118.00, 5000.00, 4705.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(40, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":21,\"unit_cost\":2,\"total_cost\":42},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":2,\"unit_cost\":1,\"total_cost\":2}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 16:35:49', '2026-05-27 16:35:49', 1, 336.00, 98.00, 1050.00, 616.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(41, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":20,\"unit_cost\":2,\"total_cost\":40},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":3,\"unit_cost\":1,\"total_cost\":3}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-27 17:16:24', '2026-05-27 17:16:24', 1, 334.00, 99.00, 1050.00, 617.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(42, NULL, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950}]', '[{\"id\":385,\"name\":\"CUYI RC Glossy Photo Paper 4R 260 GSM\",\"quantity\":1,\"unit_cost\":1.75,\"total_cost\":1.75}]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 07:24:14', '2026-05-28 07:24:14', 1, 1.75, 0.00, 950.00, 948.25, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(43, NULL, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":321,\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_cost\":0,\"total_cost\":0}]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 07:44:40', '2026-05-28 07:44:40', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(44, NULL, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":3,\"unit_price\":950,\"total_amount\":2850},{\"name\":\"Baby Shower Favors\",\"quantity\":9,\"unit_price\":750,\"total_amount\":6750},{\"name\":\"Baby Welcome Kit\",\"quantity\":10,\"unit_price\":1950,\"total_amount\":19500},{\"name\":\"Birthday Surprise Box\",\"quantity\":10,\"unit_price\":1800,\"total_amount\":18000}]', '[{\"id\":385,\"name\":\"CUYI RC Glossy Photo Paper 4R 260 GSM\",\"quantity\":1,\"unit_cost\":1.75,\"total_cost\":1.75}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:01:57', '2026-05-28 08:01:57', 1, 1.75, 0.00, 47100.00, 47098.25, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(45, NULL, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:22:45', '2026-05-28 08:22:45', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(46, NULL, NULL, '[{\"name\":\"Acrylic Key ChainPrintable PVC SheetHookPrint\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Key Chain<blockquote style=\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:24:10', '2026-05-28 08:24:10', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(47, NULL, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:27:12', '2026-05-28 08:27:12', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(48, NULL, NULL, '[{\"name\":\"Bamboo Notebook A5\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence Salvador\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 08:29:01', '2026-05-28 08:29:01', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(49, NULL, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:31:06', '2026-05-28 08:31:06', 1, 0.00, 0.00, 950.00, 950.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(50, NULL, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:44:51', '2026-05-28 08:44:51', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(51, NULL, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[{\"id\":479,\"name\":\"A4 Black and White\",\"quantity\":2,\"unit_cost\":0,\"total_cost\":0},{\"id\":459,\"name\":\"Bamboo 500ml\",\"quantity\":2,\"unit_cost\":195,\"total_cost\":390},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 08:45:19', '2026-05-28 08:45:19', 1, 410.00, 0.00, 2400.00, 1990.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(52, NULL, NULL, '[{\"name\":\"Bamboo Notebook A5 SizeA4 Black and White\",\"quantity\":5,\"unit_price\":51,\"total_amount\":255},{\"name\":\"Bamboo 500mlA4 Black and White\",\"quantity\":6,\"unit_price\":195,\"total_amount\":1170},{\"name\":\"Bamboo 500ml\",\"quantity\":5,\"unit_price\":195,\"total_amount\":975}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-28 09:18:25', '2026-05-28 09:18:25', 1, 0.00, 0.00, 2400.00, 2400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(53, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":21,\"unit_cost\":2,\"total_cost\":42},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":586,\"name\":\"Printabe PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":2,\"unit_cost\":1,\"total_cost\":2}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 09:35:00', '2026-05-28 09:35:00', 1, 336.00, 98.00, 1050.00, 616.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(54, NULL, NULL, '[]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":4,\"unit_cost\":11200,\"total_cost\":44800}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-28 09:43:05', '2026-05-28 09:43:05', 1, 44800.00, 0.00, 0.00, -44800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(55, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":10,\"unit_price\":50,\"total_amount\":500}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_cost\":10,\"total_cost\":100},{\"id\":259,\"name\":\"Baseball BGC brand\",\"quantity\":5,\"unit_cost\":59,\"total_cost\":295}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":2,\"unit_cost\":10,\"total_cost\":20}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 09:30:16', '2026-05-29 09:30:16', 1, 395.00, 20.00, 500.00, 85.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(56, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":100,\"total_amount\":100}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":2,\"unit_cost\":45,\"total_cost\":90}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 10:49:14', '2026-05-29 10:49:14', 1, 90.00, 0.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(57, NULL, NULL, '[{\"name\":\"Audit for Quotation AI-0526-0003 - LSPU LBC\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_cost\":10,\"total_cost\":100}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:53:48', '2026-05-29 12:53:48', 1, 100.00, 0.00, 450.00, 350.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(58, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":5,\"unit_cost\":45,\"total_cost\":225}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":2,\"unit_cost\":45,\"total_cost\":90}]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:56:36', '2026-05-29 12:56:36', 1, 225.00, 90.00, 450.00, 135.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(59, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"A4 Colored\",\"quantity\":10,\"unit_price\":45,\"total_amount\":450}]', '[{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":9,\"unit_cost\":45,\"total_cost\":405}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 12:58:50', '2026-05-29 12:58:50', 1, 405.00, 0.00, 450.00, 45.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(60, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bond Paper  A4\",\"quantity\":100,\"unit_price\":0.44,\"total_amount\":44},{\"name\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_price\":10,\"total_amount\":50}]', '[{\"id\":381,\"name\":\"Bond Paper  A4\",\"quantity\":99,\"unit_cost\":0.44,\"total_cost\":43.56},{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":5,\"unit_cost\":10,\"total_cost\":50}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 13:04:08', '2026-05-29 13:04:08', 1, 93.56, 0.00, 94.00, 0.44, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(61, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo ballpoint\",\"quantity\":20,\"unit_price\":10,\"total_amount\":200},{\"name\":\"A4 Colored\",\"quantity\":5,\"unit_price\":45,\"total_amount\":225}]', '[{\"id\":412,\"name\":\"Bamboo ballpoint\",\"quantity\":20,\"unit_cost\":10,\"total_cost\":200},{\"id\":476,\"name\":\"A4 Colored\",\"quantity\":5,\"unit_cost\":45,\"total_cost\":225}]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 15:03:30', '2026-05-29 15:03:30', 1, 425.00, 0.00, 425.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(62, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":10,\"total_amount\":100},{\"name\":\"Baseball BGC brand\",\"quantity\":0,\"unit_price\":59,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 15:36:20', '2026-05-29 15:36:20', 1, 0.00, 0.00, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(63, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":5,\"unit_price\":11200,\"total_amount\":56000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 15:59:35', '2026-05-29 15:59:35', 1, 0.00, 0.00, 56000.00, 56000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(64, NULL, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":5,\"unit_price\":11200,\"total_amount\":56000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:00:46', '2026-05-29 16:00:46', 1, 0.00, 0.00, 56000.00, 56000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(65, NULL, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:08:20', '2026-05-29 16:08:20', 1, 0.00, 0.00, 950.00, 950.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(66, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950},{\"name\":\"Baby Shower Favors\",\"quantity\":1,\"unit_price\":750,\"total_amount\":750}]', '[]', '[]', NULL, '{\"created_by\":\"Juan Dela Cruz\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-29 16:11:42', '2026-05-29 16:11:42', 1, 0.00, 0.00, 1700.00, 1700.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(67, NULL, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:14:38', '2026-05-29 16:14:38', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(68, NULL, NULL, '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950},{\"name\":\"Baby Shower Favors\",\"quantity\":1,\"unit_price\":750,\"total_amount\":750},{\"name\":\"Baby Welcome Kit\",\"quantity\":1,\"unit_price\":1950,\"total_amount\":1950},{\"name\":\"Birthday Surprise Box\",\"quantity\":1,\"unit_price\":1800,\"total_amount\":1800},{\"name\":\"Christmas Hamper\",\"quantity\":1,\"unit_price\":3500,\"total_amount\":3500},{\"name\":\"Christmas Hamper\",\"quantity\":1,\"unit_price\":3000,\"total_amount\":3000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-29 16:19:42', '2026-05-29 16:19:42', 1, 0.00, 0.00, 11950.00, 11950.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(69, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_price\":11200,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:24:29', '2026-05-29 16:24:29', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(70, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo Notebook A5 Size\",\"quantity\":0,\"unit_price\":51,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"Art School\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:29:19', '2026-05-29 16:29:19', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(71, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":10,\"unit_price\":100,\"total_amount\":1000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:33:36', '2026-05-29 16:33:36', 1, 0.00, 0.00, 1000.00, 1000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(72, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950},{\"name\":\"Baby Shower Favors\",\"quantity\":1,\"unit_price\":750,\"total_amount\":750},{\"name\":\"Baby Welcome Kit\",\"quantity\":1,\"unit_price\":1950,\"total_amount\":1950}]', '[]', '[]', NULL, '{\"created_by\":\"Juan Dela Cruz\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"\"}', 'draft', '2026-05-29 16:34:24', '2026-05-29 16:34:24', 1, 0.00, 0.00, 3650.00, 3650.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(73, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Calling Card B2B no laminate\",\"quantity\":8,\"unit_price\":20,\"total_amount\":160}]', '[]', '[]', NULL, '{\"created_by\":\"Art School\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:36:49', '2026-05-29 16:36:49', 1, 0.00, 0.00, 160.00, 160.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(74, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_price\":11200,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:44:05', '2026-05-29 16:44:05', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(75, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"School Supplies Gift\",\"quantity\":21,\"unit_price\":50.01,\"total_amount\":1050.21}]', '[{\"id\":406,\"name\":\"Yasen Vinyl Glossy Photo Stickers  150 GSM\",\"quantity\":50,\"unit_cost\":6,\"total_cost\":300}]', '[]', NULL, '{\"created_by\":\"Juan Dela Cruz\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 16:57:19', '2026-05-29 16:57:19', 1, 300.00, 0.00, 1050.21, 750.21, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(76, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[{\"id\":590,\"name\":\"Printable PVC Sheet\",\"quantity\":6,\"unit_cost\":48,\"total_cost\":288},{\"id\":587,\"name\":\"Hook\",\"quantity\":21,\"unit_cost\":2,\"total_cost\":42},{\"id\":588,\"name\":\"Print\",\"quantity\":6,\"unit_cost\":1,\"total_cost\":6}]', '[{\"id\":590,\"name\":\"Printable PVC Sheet\",\"quantity\":2,\"unit_cost\":48,\"total_cost\":96},{\"id\":588,\"name\":\"Print\",\"quantity\":2,\"unit_cost\":1,\"total_cost\":2}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 17:08:15', '2026-05-29 17:08:15', 1, 336.00, 98.00, 1050.00, 616.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(77, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":10,\"unit_price\":11200,\"total_amount\":112000}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 17:21:04', '2026-05-29 17:21:04', 1, 0.00, 0.00, 112000.00, 112000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(78, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic keychain square\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 17:26:56', '2026-05-29 17:26:56', 1, 0.00, 0.00, 1050.00, 1050.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(79, NULL, NULL, '[{\"name\":\"School Supplies Gift\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Bamboo Notebook A5 Size\",\"quantity\":10,\"unit_price\":51,\"total_amount\":510}]', '[{\"id\":378,\"name\":\"Sublimation Transfer Paper  A4\",\"quantity\":99,\"unit_cost\":1.55,\"total_cost\":153.45},{\"id\":342,\"name\":\"Spring Notebook A5\",\"quantity\":5,\"unit_cost\":36,\"total_cost\":180}]', '[{\"id\":378,\"name\":\"Sublimation Transfer Paper  A4\",\"quantity\":9,\"unit_cost\":1.55,\"total_cost\":13.95}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-29 17:32:31', '2026-05-29 17:32:31', 1, 333.45, 13.95, 510.00, 162.60, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(80, NULL, NULL, '[{\"name\":\"Calling card B2B laminated\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Calling card B2B laminated\",\"quantity\":1,\"unit_price\":550,\"total_amount\":550}]', '[{\"id\":372,\"name\":\"Calling card Paper  220 GSM\",\"quantity\":10,\"unit_cost\":2.5,\"total_cost\":25},{\"id\":397,\"name\":\"Photo Top Glossy\",\"quantity\":20,\"unit_cost\":4.9,\"total_cost\":98}]', '[{\"id\":372,\"name\":\"Calling card Paper  220 GSM\",\"quantity\":2,\"unit_cost\":2.5,\"total_cost\":5},{\"id\":397,\"name\":\"Photo Top Glossy\",\"quantity\":2,\"unit_cost\":4.9,\"total_cost\":9.8}]', NULL, '{\"created_by\":\"Jeffer Dela Rueda\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-05-30 06:42:08', '2026-05-30 06:42:08', 1, 123.00, 14.80, 550.00, 412.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(81, NULL, NULL, '[]', '[{\"id\":323,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":0,\"unit_cost\":11200,\"total_cost\":0}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-06-03 13:59:00', '2026-06-03 13:59:00', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(84, NULL, NULL, '[{\"name\":\"Ackrylic keychain round\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050},{\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":10,\"total_amount\":100}]', '[{\"id\":527,\"name\":\"Ackrylic keychain round\",\"quantity\":99,\"unit_cost\":50,\"total_cost\":4950}]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-06-03 15:04:25', '2026-06-03 15:04:25', 1, 4950.00, 0.00, 1150.00, -3800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(85, NULL, NULL, '[{\"name\":\"Acrylic Keychain\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Ackrylic keychain round\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050},{\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":10,\"total_amount\":100}]', '[{\"id\":527,\"name\":\"Ackrylic keychain round\",\"quantity\":0,\"unit_cost\":50,\"total_cost\":0}]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-06-03 15:11:13', '2026-06-03 15:11:13', 1, 0.00, 0.00, 1150.00, -449.99, 200.00, 200.00, 200.00, 200.00, 200.00, 199.99, 200.00, 200.00, 1599.99, 0.00, 0.00, 1599.99, 1, 0.00, 0.00, NULL, 250.00),
(86, NULL, NULL, '[{\"name\":\"Photo Top Holographic Rainbow\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Photo Top Holographic Rainbow\",\"quantity\":20,\"unit_price\":7.1,\"total_amount\":142}]', '[{\"id\":398,\"name\":\"Photo Top Holographic Rainbow\",\"quantity\":20,\"unit_cost\":7.1,\"total_cost\":142}]', '[{\"id\":398,\"name\":\"Photo Top Holographic Rainbow\",\"quantity\":5,\"unit_cost\":7.1,\"total_cost\":35.5}]', NULL, '{\"created_by\":\"ABC Corp\",\"audited_by\":\"Rosel Eloria\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-06-05 11:53:00', '2026-06-05 11:53:00', 1, 142.00, 35.50, 142.00, -1635.50, 200.00, 200.00, 200.00, 200.00, 200.00, 200.00, 200.00, 200.00, 1600.00, 16.00, 100.00, 1777.50, 1, 0.00, 0.00, NULL, 250.00),
(87, NULL, NULL, '[{\"name\":\"Audit for AI-0526-0012\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0}]', '[]', '[]', NULL, '{\"created_by\":\"Quotation System\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-06-24 14:00:38', '2026-06-24 14:00:38', 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(88, NULL, NULL, '[{\"name\":\"Ackrylic keychain round\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Ackrylic keychain round\",\"quantity\":21,\"unit_price\":50,\"total_amount\":1050},{\"name\":\"Bamboo ballpoint\",\"quantity\":10,\"unit_price\":10,\"total_amount\":100}]', '[]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-03 08:13:08', '2026-07-03 08:13:08', 1, 0.00, 0.00, 1150.00, 1150.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(89, NULL, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":6,\"unit_price\":200,\"total_amount\":1200}]', '[]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-03 08:13:30', '2026-07-03 08:13:30', 1, 0.00, 0.00, 1200.00, 1200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(91, NULL, NULL, '[{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":1,\"unit_price\":0,\"total_amount\":0},{\"name\":\"Acrylic Keychain 5x9 cm cm\",\"quantity\":6,\"unit_price\":200,\"total_amount\":1200}]', '[]', '[]', NULL, '{\"created_by\":\"Organization Name\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-03 08:32:51', '2026-07-03 08:32:51', 1, 0.00, 0.00, 1200.00, 1200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 0.00, 0.00, NULL, 250.00),
(92, NULL, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":3,\"unit_cost\":11200,\"total_cost\":33600},{\"id\":1606,\"name\":\"Ackrylic keychain round\",\"quantity\":13,\"unit_cost\":50,\"total_cost\":650}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"michael\",\"acknowledged_by\":\"blessie\"}', 'draft', '2026-07-11 06:16:23', '2026-07-11 06:16:23', 1, 34250.00, 0.00, 22400.00, -11850.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 34250.00, 1, 0.00, 0.00, NULL, 250.00),
(93, NULL, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-11 12:20:22', '2026-07-11 12:20:22', 1, 22400.00, 0.00, 22400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 22400.00, 1, 0.00, 0.00, NULL, 250.00),
(94, NULL, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"LSPU LBC\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-11 12:54:27', '2026-07-11 12:54:27', 1, 22400.00, 0.00, 22400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 22400.00, 1, 0.00, 0.00, NULL, 250.00),
(95, NULL, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-11 13:21:06', '2026-07-11 13:21:06', 1, 22400.00, 0.00, 22400.00, -5640.00, 1000.00, 230.00, 3210.00, 430.00, 430.00, 340.00, 0.00, 0.00, 5640.00, 0.00, 0.00, 28040.00, 1, 0.00, 0.00, NULL, 250.00),
(96, NULL, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[]', '[]', NULL, '{\"created_by\":\"Jay Michael\",\"audited_by\":\"Jay Michael\",\"acknowledged_by\":\"Blessie\"}', 'draft', '2026-07-11 13:34:57', '2026-07-11 13:34:57', 1, 0.00, 0.00, 22400.00, 6200.00, 2210.00, 2230.00, 2340.00, 340.00, 630.00, 8450.00, 0.00, 0.00, 16200.00, 0.00, 0.00, 16200.00, 1, 0.00, 0.00, NULL, 250.00),
(97, NULL, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":1,\"unit_cost\":11200,\"total_cost\":11200}]', '[]', NULL, '{\"created_by\":\"Jay Michael Castillo\",\"audited_by\":\"Jay Michael Castillo\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-07-11 13:52:42', '2026-07-11 13:52:42', 1, 11200.00, 0.00, 22400.00, 3860.00, 340.00, 640.00, 450.00, 2140.00, 1430.00, 2340.00, 0.00, 0.00, 7340.00, 0.00, 0.00, 18540.00, 1, 0.00, 0.00, NULL, 250.00),
(101, NULL, NULL, '[{\"name\":\"Corrugated White T12Bond Paper  A4\",\"quantity\":5,\"unit_price\":128.6,\"total_amount\":643},{\"name\":\"Bond Paper  Long\",\"quantity\":100,\"unit_price\":100.44,\"total_amount\":10044}]', '[{\"id\":1635,\"name\":\"A4 Black and White\",\"quantity\":3,\"unit_cost\":0,\"total_cost\":0},{\"id\":1636,\"name\":\"A4 Colored\",\"quantity\":10,\"unit_cost\":45,\"total_cost\":450}]', '[]', NULL, '{\"created_by\":\"Jay Michael\",\"audited_by\":\"Jay Michael\",\"acknowledged_by\":\"Jay Michael\"}', 'draft', '2026-07-11 13:58:58', '2026-07-11 13:58:58', 1, 450.00, 0.00, 10687.00, 8877.00, 210.00, 230.00, 230.00, 230.00, 230.00, 230.00, 0.00, 0.00, 1360.00, 0.00, 0.00, 1810.00, 1, 0.00, 0.00, NULL, 250.00),
(102, NULL, NULL, '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Jay Michael M. Castillo\",\"acknowledged_by\":\"Jay Michael M. Castillo\"}', 'draft', '2026-07-11 14:07:14', '2026-07-11 14:07:14', 1, 22400.00, 0.00, 22400.00, -2840.01, 230.00, 230.00, 430.00, 540.00, 230.00, 540.00, 640.01, 0.00, 2840.01, 11.36, 0.00, 25240.01, 1, 120.00, 120.00, '{\"shop_rent\":230,\"fixed_salaries\":230,\"shop_utilities\":430,\"subscriptions\":540,\"machine_depreciation\":230,\"maintenance_repair\":540,\"marketing_promo\":640.01}', 250.00),
(103, 64, '', '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-11 14:16:24', '2026-07-11 14:16:24', 1, 22400.00, 0.00, 22400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 22400.00, 1, 0.00, 0.00, '{\"shop_rent\":0,\"fixed_salaries\":0,\"shop_utilities\":0,\"subscriptions\":0,\"machine_depreciation\":0,\"maintenance_repair\":0,\"marketing_promo\":0}', 250.00),
(104, 64, '', '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Jay Michael M. Castillo\",\"acknowledged_by\":\"Jay Michael M. Castillo\"}', 'draft', '2026-07-11 14:16:58', '2026-07-11 14:16:58', 1, 22400.00, 0.00, 22400.00, -2630.00, 230.00, 430.00, 540.00, 530.00, 340.00, 230.00, 330.00, 0.00, 2630.00, 10.52, 0.00, 25030.00, 1, 1230.00, 320.00, '{\"shop_rent\":230,\"fixed_salaries\":430,\"shop_utilities\":540,\"subscriptions\":530,\"machine_depreciation\":340,\"maintenance_repair\":230,\"marketing_promo\":330}', 250.00),
(105, 64, '', '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"Lawrence\",\"audited_by\":\"Jay Michael\",\"acknowledged_by\":\"Blessie\"}', 'draft', '2026-07-11 14:18:42', '2026-07-11 14:18:42', 1, 22400.00, 0.00, 22400.00, -840.00, 120.00, 120.00, 120.00, 120.00, 120.00, 120.00, 120.00, 0.00, 840.00, 3.36, 0.00, 23240.00, 1, 120.00, 120.00, '{\"shop_rent\":120,\"fixed_salaries\":120,\"shop_utilities\":120,\"subscriptions\":120,\"machine_depreciation\":120,\"maintenance_repair\":120,\"marketing_promo\":120}', 250.00),
(106, 64, '', '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"Lawrece\",\"audited_by\":\"Michael\",\"acknowledged_by\":\"Michael\"}', 'draft', '2026-07-11 14:24:54', '2026-07-11 14:24:54', 1, 22400.00, 0.00, 22400.00, -2240.00, 320.00, 320.00, 320.00, 320.00, 320.00, 320.00, 320.00, 0.00, 2240.00, 8.96, 0.00, 24640.00, 1, 320.00, 320.00, '{\"shop_rent\":320,\"fixed_salaries\":320,\"shop_utilities\":320,\"subscriptions\":320,\"machine_depreciation\":320,\"maintenance_repair\":320,\"marketing_promo\":320}', 250.00),
(107, 64, '', '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"\",\"audited_by\":\"\",\"acknowledged_by\":\"\"}', 'draft', '2026-07-11 14:26:20', '2026-07-11 14:26:20', 1, 22400.00, 0.00, 22400.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 22400.00, 1, 0.00, 0.00, '{\"shop_rent\":0,\"fixed_salaries\":0,\"shop_utilities\":0,\"subscriptions\":0,\"machine_depreciation\":0,\"maintenance_repair\":0,\"marketing_promo\":0}', 250.00),
(109, 64, '', '[{\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":11200,\"total_amount\":22400}]', '[{\"id\":1577,\"name\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_cost\":11200,\"total_cost\":22400}]', '[]', NULL, '{\"created_by\":\"Michael\",\"audited_by\":\"Michael\",\"acknowledged_by\":\"Michael\"}', 'draft', '2026-07-11 14:43:43', '2026-07-11 14:43:43', 1, 22400.00, 0.00, 22400.00, -4483.00, 2320.00, 320.00, 320.00, 430.00, 430.00, 430.00, 233.00, 0.00, 4483.00, 17.93, 0.00, 26883.00, 1, 120.00, 120.00, '{\"shop_rent\":2320,\"fixed_salaries\":320,\"shop_utilities\":320,\"subscriptions\":430,\"machine_depreciation\":430,\"maintenance_repair\":430,\"marketing_promo\":233}', 250.00),
(110, 18, '', '[{\"name\":\"Anniversary Photo Album\",\"quantity\":1,\"unit_price\":950,\"total_amount\":950},{\"name\":\"Baby Shower Favors\",\"quantity\":1,\"unit_price\":750,\"total_amount\":750},{\"name\":\"Baby Welcome Kit\",\"quantity\":1,\"unit_price\":1950,\"total_amount\":1950},{\"name\":\"Birthday Surprise Box\",\"quantity\":1,\"unit_price\":1800,\"total_amount\":1800}]', '[{\"id\":1646,\"name\":\"CUYI RC Glossy Photo Paper 4R 260 GSM\",\"quantity\":1,\"unit_cost\":1.75,\"total_cost\":1.75}]', '[]', NULL, '{\"created_by\":\"Michael\",\"audited_by\":\"Michael\",\"acknowledged_by\":\"Michael\"}', 'draft', '2026-07-11 14:58:13', '2026-07-11 14:58:13', 1, 1.75, 0.00, 5450.00, 4388.25, 210.00, 120.00, 230.00, 120.00, 120.00, 30.00, 230.00, 0.00, 1060.00, 4.24, 0.00, 1061.75, 1, 230.00, 230.00, '{\"shop_rent\":210,\"fixed_salaries\":120,\"shop_utilities\":230,\"subscriptions\":120,\"machine_depreciation\":120,\"maintenance_repair\":30,\"marketing_promo\":230}', 250.00),
(111, 65, '', '[{\"name\":\"Acrylic keychain rec\",\"quantity\":2,\"unit_price\":50,\"total_amount\":100}]', '[{\"id\":0,\"name\":\"Ackrylic keychain round\",\"quantity\":2,\"unit_cost\":50,\"total_cost\":100}]', '[]', NULL, '{\"created_by\":\"Jay Michael M. Castillo\",\"audited_by\":\"Lawrence\",\"acknowledged_by\":\"Blessie\"}', 'draft', '2026-07-11 14:58:32', '2026-07-22 10:43:57', 1, 100.00, 0.00, 100.00, -11450.00, 430.00, 4330.00, 340.00, 2340.00, 430.00, 340.00, 3240.00, 0.00, 11450.00, 45.80, 0.00, 11550.00, 1, 430.00, 430.00, '{\"shop_rent\":430,\"fixed_salaries\":4330,\"shop_utilities\":340,\"subscriptions\":2340,\"machine_depreciation\":430,\"maintenance_repair\":340,\"marketing_promo\":3240}', 250.00),
(112, NULL, '', '[{\"name\":\"Bamboo Notebook A5 Size\",\"quantity\":1,\"unit_price\":51,\"total_amount\":51}]', '[{\"id\":0,\"name\":\"Bamboo 500ml\",\"quantity\":1,\"unit_cost\":195,\"total_cost\":195}]', '[]', NULL, '{\"created_by\":\"Lawrence Salvador\",\"audited_by\":\"Jay Michael Castillo\",\"acknowledged_by\":\"Blessie Mabilangan\"}', 'draft', '2026-07-11 15:00:24', '2026-07-20 12:26:18', 1, 195.00, 0.00, 51.00, -3244.00, 440.00, 340.00, 430.00, 450.00, 540.00, 450.00, 450.00, 0.00, 3100.00, 12.40, 0.00, 3295.00, 1, 320.00, 1320.00, '{\"shop_rent\":440,\"fixed_salaries\":340,\"shop_utilities\":430,\"subscriptions\":450,\"machine_depreciation\":540,\"maintenance_repair\":450,\"marketing_promo\":450}', 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `bundles_merchandise`
--

CREATE TABLE `bundles_merchandise` (
  `id` int(11) NOT NULL,
  `type` enum('bundle','merchandise') NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bundles_merchandise`
--

INSERT INTO `bundles_merchandise` (`id`, `type`, `name`, `description`, `image_path`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'merchandise', 'Classic Logo T-Shirt', 'Premium cotton t-shirt with our signature logo. Available in multiple sizes.', 'uploads/merchandises/6a5adc523af52.jpg', 1, '2026-07-18 01:41:30', '2026-07-18 01:52:18'),
(10, 'bundle', 'Premium Collection Bundle', 'Our best-selling items bundled at a special price.', 'uploads/bundles/6a5adaf0f41ea.jpg', 2, '2026-07-18 01:41:54', '2026-07-18 01:46:25'),
(11, 'bundle', 'Holiday Special Bundle', 'Limited edition holiday bundle with exclusive items.', 'uploads/bundles/6a5adb0172eeb.jpg', 3, '2026-07-18 01:41:54', '2026-07-18 01:46:41'),
(12, 'bundle', 'DIY Craft Bundle', 'Everything you need for your next creative project.', 'uploads/bundles/6a5adb0a564e2.jpg', 4, '2026-07-18 01:41:54', '2026-07-18 01:46:50'),
(13, 'bundle', 'Business Essentials Bundle', 'Essential tools and materials for small businesses.', 'uploads/bundles/6a5adb2a70a98.jpg', 5, '2026-07-18 01:41:54', '2026-07-18 01:47:22');

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
(103, 'DR-2026-07-0002', 60, 'AI-0526-0016', 'Organization Name', 'Timugan, Los Banos, Laguan', '+639395529749', 'Jay Michael', '2026-07-03', '[{\"description\":\"Calling card B2B laminated\",\"quantity\":1,\"unit_price\":\"550.00\",\"total\":\"550.00\"}]', 550.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0002_20260703_101230.pdf', '2026-07-03 08:12:29', 1),
(104, 'DR-2026-07-0003', 63, 'AI-0726-0002', 'LSPU LBC', 'Anos, Los Banos, Laguna', '09395529749', 'Jay Michael', '2026-07-03', '[{\"description\":\"Corrugated White T12Bond Paper  A4\",\"quantity\":5,\"unit_price\":\"28.60\",\"total\":\"143.00\"},{\"description\":\"Bond Paper  Long\",\"quantity\":100,\"unit_price\":\"0.46\",\"total\":\"46.00\"}]', 189.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0003_20260703_160116.pdf', '2026-07-03 14:01:15', 1),
(105, 'DR-2026-07-0004', 64, 'AI-0726-0003', 'LSPU LBC', 'Anos, Los Banos, Laguna', '09121231234', 'Jay Michael', '2026-07-08', '[{\"description\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":\"11200.00\",\"total\":\"22400.00\"}]', 22400.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0004_20260708_115300.pdf', '2026-07-08 09:52:59', 1),
(106, 'DR-2026-07-0005', 64, 'AI-0726-0003', 'LSPU LBC', 'Anos, Los Banos, Laguna', '09121231234', 'Jay Michael', '2026-07-10', '[{\"description\":\"6-in-1 Combo Heat Press Press\",\"quantity\":2,\"unit_price\":\"11200.00\",\"total\":\"22400.00\"}]', 22400.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0005_20260710_144425.pdf', '2026-07-10 12:44:22', 1),
(107, 'DR-2026-07-0006', 60, 'AI-0526-0016', 'Organization Name', '', '+639395529749', 'Jay Michael', '2026-07-11', '[{\"description\":\"Calling card B2B laminated\",\"quantity\":1,\"unit_price\":\"550.00\",\"total\":\"550.00\"}]', 550.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0006_20260711_080621.pdf', '2026-07-11 06:06:16', 1),
(109, 'DR-2026-07-0008', 17, 'AI-202605-0008', 'Jay Michael M. Castillo', '', '09395529749', 'Jay Michael', '2026-07-12', '[{\"description\":\"Anniversary Photo Album\",\"quantity\":3,\"unit_price\":\"950.00\",\"total\":\"2850.00\"},{\"description\":\"Baby Shower Favors\",\"quantity\":9,\"unit_price\":\"750.00\",\"total\":\"6750.00\"},{\"description\":\"Baby Welcome Kit\",\"quantity\":10,\"unit_price\":\"1950.00\",\"total\":\"19500.00\"},{\"description\":\"Birthday Surprise Box\",\"quantity\":10,\"unit_price\":\"1800.00\",\"total\":\"18000.00\"}]', 47100.00, '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0008_20260712_072427.pdf', '2026-07-12 05:24:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

CREATE TABLE `email_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `GmailAddress` varchar(150) NOT NULL,
  `AppPasswordEncrypted` text NOT NULL,
  `UpdatedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_settings`
--

INSERT INTO `email_settings` (`id`, `GmailAddress`, `AppPasswordEncrypted`, `UpdatedBy`, `UpdatedAt`) VALUES
(1, 'jaymichaelmontemarcastillo@gmail.com', 'oijwV+cDSRB+ffhqadMdZxaWmiRzgWtyvj70HWktDcbV2D2/9tZJDo/nhhdoisaW', 1, '2026-07-22 10:50:47');

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
(75, 1577, 'order', 3, 96, 93, 1, 'Audit #92: Used 3 x 6-in-1 Combo Heat Press Press', '2026-07-11 06:16:23', 92),
(76, 1606, 'order', 13, 100, 87, 1, 'Audit #92: Used 13 x Ackrylic keychain round', '2026-07-11 06:16:23', 92),
(77, 1577, 'order', 2, 93, 91, 1, 'Audit #93: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 12:20:22', 93),
(78, 1577, 'order', 2, 91, 89, 1, 'Audit #94: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 12:54:27', 94),
(79, 1577, 'order', 2, 89, 87, 1, 'Audit #95: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 13:21:06', 95),
(80, 1577, 'order', 1, 87, 86, 1, 'Audit #97: Used 1 x 6-in-1 Combo Heat Press Press', '2026-07-11 13:52:42', 97),
(84, 1635, 'order', 3, 13, 10, 1, 'Audit #101: Used 3 x A4 Black and White', '2026-07-11 13:58:58', 101),
(85, 1636, 'order', 10, 10, 0, 1, 'Audit #101: Used 10 x A4 Colored', '2026-07-11 13:58:58', 101),
(86, 1577, 'order', 2, 86, 84, 1, 'Audit #102: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 14:07:14', 102),
(87, 1577, 'order', 2, 84, 82, 1, 'Audit #103: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 14:16:24', 103),
(88, 1577, 'order', 2, 82, 80, 1, 'Audit #104: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 14:16:58', 104),
(89, 1577, 'order', 2, 80, 78, 1, 'Audit #105: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 14:18:42', 105),
(90, 1577, 'order', 2, 78, 76, 1, 'Audit #106: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 14:24:54', 106),
(91, 1577, 'order', 2, 76, 74, 1, 'Audit #107: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 14:26:20', 107),
(93, 1577, 'order', 2, 74, 72, 1, 'Audit #109: Used 2 x 6-in-1 Combo Heat Press Press', '2026-07-11 14:43:43', 109),
(94, 1646, 'order', 1, 18, 17, 1, 'Audit #110: Used 1 x CUYI RC Glossy Photo Paper 4R 260 GSM', '2026-07-11 14:58:13', 110),
(95, 1606, 'order', 2, 87, 85, 1, 'Audit #111: Used 2 x Ackrylic keychain round', '2026-07-11 14:58:32', 111),
(96, 1804, 'order', 1, 98, 97, 1, 'Audit #112: Used 1 x Bamboo 500ml', '2026-07-11 15:00:24', 112);

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
(1821, 'ID Case Case', NULL, 'Accessories', NULL, NULL, 0, 40, 0, 40, 20.00, 1, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1822, 'Tote Plain Flat Portrait  8.5  by 10', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 38.00, 1, 38.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1823, 'Tote Plain Flat Portrait  12 by 14', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 58.00, 1, 58.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1824, 'Tote Plain Flat Portrait  13 by 16', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 66.00, 1, 66.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1825, 'Tote Plain with Base 12 by 14 by 3', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 68.00, 1, 68.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1826, 'Tote Plain with Base 13 by 15 by 3', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 78.00, 1, 78.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1827, 'Tote Plain Flat Portrait  10 by 12', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1828, 'Tote Plain Flat Colored 13 by 15', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 93.00, 1, 93.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1829, 'Tote Plainwith base and zipper Colored 12 by 14 by 3', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 120.00, 1, 120.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1830, 'Tote Plain Flat Colored 12 by 14', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 79.00, 1, 79.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1831, 'Tote Plain Flat with Zipper 12 by 14', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 79.00, 1, 79.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1832, 'Tote Plain Flat with Zipper 13 by 15', NULL, 'Bag', NULL, NULL, 0, 0, 0, 0, 87.00, 1, 87.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1833, 'Button pins 2.25 inches', NULL, 'Button Pins', NULL, NULL, 0, 86, 0, 86, 300.00, 100, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1834, 'Baseball BGC brand', NULL, 'Cap', NULL, NULL, 0, 0, 0, 0, 59.00, 1, 59.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1835, 'Mesh BGC brand', NULL, 'Cap', NULL, NULL, 0, 0, 0, 0, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1836, 'Bucket BGC brand', NULL, 'Cap', NULL, NULL, 0, 0, 0, 0, 65.00, 1, 65.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1837, 'CUYI Cleaning Solution 100ml', NULL, 'Cleaning Solution', NULL, NULL, 0, 1, 0, 1, 40.00, 1, 40.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1838, 'UV Moisturizer 500 ml', NULL, 'Cleaning Solution', NULL, NULL, 0, 0, 1, 1, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1839, 'Itech Glossy Cold Laminating Photo Top Glossy', NULL, 'Cold Laminate', NULL, NULL, 0, 0, 40, 40, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1840, 'Itech Matte Cold Laminating Photo Top Matte', NULL, 'Cold Laminate', NULL, NULL, 0, 0, 99, 99, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1841, 'Itech Transparent Vinyl Stickers A4  A4', NULL, 'Cold Laminate', NULL, NULL, 0, 0, 60, 60, 165.00, 20, 8.2500, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1842, 'Photo Top - Canvas matte matte', NULL, 'Cold Laminate', NULL, NULL, 0, 0, 20, 20, 100.00, 20, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1843, 'Paper Cutter (Metal Base)', NULL, 'Cutters', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1844, 'PVC Die Cutter (ATM Size) 54 by 86cm', NULL, 'Cutters', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1845, 'PET film A3+ Roll Roll', NULL, 'DTF', NULL, NULL, 0, 0, 0, 0, 1650.00, 1, 1650.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1846, 'Powder Honjet Pack 1 KG', NULL, 'DTF', NULL, NULL, 0, 0, 1, 1, 320.00, 1, 320.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1847, 'Black 35x50', NULL, 'Ecobag', NULL, NULL, 0, 0, 10, 10, 360.00, 20, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1848, 'White 25x30', NULL, 'Ecobag', NULL, NULL, 0, 0, 40, 40, 190.00, 50, 3.8000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1849, 'White 25x35', NULL, 'Ecobag', NULL, NULL, 0, 0, 48, 48, 400.00, 50, 8.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1850, 'White 35x50', NULL, 'Ecobag', NULL, NULL, 0, 0, 15, 15, 360.00, 20, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1851, 'White with base Small- 8 by 11', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 175.00, 50, 3.5000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1852, 'White with base M- 10 by 12', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 200.00, 50, 4.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1853, 'White with base Large 12 by 16', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 250.00, 50, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1854, 'White with base XL- 14 by 18', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 300.00, 50, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1855, 'Flat Bag Large 12 by 16', NULL, 'Ecobag', NULL, NULL, 0, 0, 0, 0, 250.00, 50, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1856, 'Ecobag Yellow 30x 38', NULL, 'Ecobag', NULL, NULL, 0, 0, 52, 52, 1300.00, 100, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1857, 'Long Brown Paper', NULL, 'Envelope', NULL, NULL, 0, 0, 0, 0, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1858, 'Short Brown Paper', NULL, 'Envelope', NULL, NULL, 0, 10, 0, 10, 110.00, 50, 2.2000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1859, 'Laminating Film A4 250 mic', NULL, 'Hot Laminate', NULL, NULL, 0, 15, 50, 65, 819.00, 100, 8.1900, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1860, 'Laminating Film Long 125 mic', NULL, 'Hot Laminate', NULL, NULL, 0, 0, 50, 50, 450.00, 100, 4.5000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1861, 'Cuyi Pigment Ink Black', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1862, 'Cuyi Pigment Ink Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1863, 'Cuyi Pigment Ink Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1864, 'Cuyi Pigment Ink Yellow', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 95.00, 1, 95.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1865, 'Cuyi Sublimation Ink Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1866, 'Cuyi Sublimation Ink Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1867, 'Cuyi Sublimation Ink Yellow', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1868, 'Cuyi Sublimation Ink Black', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 168.00, 1, 168.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1869, 'Cuyi UV Dye Black', NULL, 'Ink', NULL, NULL, 0, 1, 0, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1870, 'Cuyi UV Dye Cyan', NULL, 'Ink', NULL, NULL, 0, 1, 0, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1871, 'Cuyi UV Dye Light Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1872, 'Cuyi UV Dye Magenta', NULL, 'Ink', NULL, NULL, 0, 1, 0, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1873, 'Cuyi UV Dye Yellow', NULL, 'Ink', NULL, NULL, 0, 1, 0, 1, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1874, 'Hansol Pigment Black', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1875, 'Hansol Pigment Light Cyan', NULL, 'Ink', NULL, NULL, 0, 2, 0, 2, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1876, 'Hansol Pigment Light Magenta', NULL, 'Ink', NULL, NULL, 0, 2, 0, 2, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1877, 'Hansol Pigment Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1878, 'Hansol Pigment Yellow', NULL, 'Ink', NULL, NULL, 0, 1, 1, 2, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1879, 'Hansol Pigment Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1880, 'InkPro DTF Ink 100ml White', NULL, 'Ink', NULL, NULL, 0, 2, 0, 2, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1881, 'InkPro DTF Ink 100ml Black', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1882, 'InkPro DTF Ink 100ml Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1883, 'InkPro DTF Ink 100ml Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1884, 'InkPro DTF Ink 100ml Yellow', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1885, 'InkPro DTF Ink 500ml White', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 750.00, 1, 750.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1886, 'UV Ink Black', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1887, 'UV Ink Clear', NULL, 'Ink', NULL, NULL, 0, 0, 4, 4, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1888, 'UV Ink Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1889, 'UV Ink Light Cyan', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1890, 'UV Ink Light Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 0, 0, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1891, 'UV Ink Magenta', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1892, 'UV Ink White', NULL, 'Ink', NULL, NULL, 0, 0, 2, 2, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1893, 'UV Ink Yellow', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1250.00, 1, 1250.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1894, 'UV Varnish Glossy', NULL, 'Ink', NULL, NULL, 0, 0, 1, 1, 1825.00, 1, 1825.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1895, 'Insert-in Keychain', NULL, 'Keychain', NULL, NULL, 0, 36, 0, 36, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1896, 'Acrylic Keychain 5x9 cm cm', NULL, 'Keychain', NULL, NULL, 0, 21, 0, 21, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1897, 'Wooden keychain  square', NULL, 'Keychain', NULL, NULL, 0, 45, 0, 45, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1898, '6-in-1 Combo Heat Press Press', NULL, 'Machines', NULL, NULL, 0, 0, 1, 1, 11200.00, 1, 11200.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1899, 'Laminator A3', NULL, 'Machines', NULL, NULL, 0, 1, 0, 1, 2280.00, 1, 2280.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1900, 'Mug Press Machine Dual Dual', NULL, 'Machines', NULL, NULL, 0, 0, 1, 1, 26620.00, 1, 26620.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1901, 'Button Pin Maker Keychain Molds', NULL, 'Machines', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1902, 'Magnetic Sheet with Adhesive  A4 0.5mm', NULL, 'Magnet', NULL, NULL, 0, 0, 50, 50, 180.00, 10, 18.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1903, 'Magnetic Sheet with Adhesive  A4 1mm', NULL, 'Magnet', NULL, NULL, 0, 0, 10, 10, 298.00, 10, 29.8000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1904, 'Magnetic Sheet (Adhesive)', NULL, 'Magnet', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1905, 'Magnetic Sheet Non-Adhesive  A4 0.5mm', NULL, 'Magnet', NULL, NULL, 0, 1, 0, 1, 110.00, 10, 11.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1906, 'Ref Magnet ID size', NULL, 'Magnet', NULL, NULL, 0, 0, 14, 14, 300.00, 50, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1907, 'Wire Binder Binder', NULL, 'Metal', NULL, NULL, 0, 0, 38, 38, 380.00, 100, 3.8000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1908, 'Frosted / Clear Glass Mug Frosted', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 75.00, 1, 75.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1909, 'Inner Color Mug Assorted', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 1660.00, 24, 69.1667, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1910, 'Magic Mug (Matte/Glossy) Glossy', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 3040.00, 36, 84.4444, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1911, 'White Coated Mug  Subli White', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 1369.00, 36, 38.0278, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1912, 'Enamel 500ml', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 158.00, 1, 158.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1913, 'Ceramic Coffee V.1 cork base', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 134.00, 1, 134.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1914, 'Ceramic Coffee V.2 with wooden lid', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 139.00, 1, 139.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1915, 'Egg Tumbler 300ml', NULL, 'Mug', NULL, NULL, 0, 0, 0, 0, 119.00, 1, 119.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1916, 'Bamboo Notebook A5 Size', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 51.00, 1, 51.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1917, 'Spring Notebook A5', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 36.00, 1, 36.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1918, 'Leatherette Notebook Softbound A5', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 54.00, 1, 54.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1919, 'Leatherette Notebook Hardbound  A5', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 143.00, 1, 143.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1920, 'Moleskin-style Strap Diary A5', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 52.00, 1, 52.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1921, 'Moleskin-style Strap Diary A6', NULL, 'Notebook', NULL, NULL, 0, 0, 0, 0, 39.00, 1, 39.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1922, 'Mesh bag Big Big 8 by 12', NULL, 'Packaging', NULL, NULL, 0, 100, 2, 102, 349.00, 50, 6.9800, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1923, 'Mesh bag Small Small 6 by 9', NULL, 'Packaging', NULL, NULL, 0, 100, 2, 102, 230.00, 50, 4.6000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1924, 'Paper Bag  13.5x20.5x8.5', NULL, 'Packaging', NULL, NULL, 0, 0, 72, 72, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1925, 'Paper Bag  40x31.5x5', NULL, 'Packaging', NULL, NULL, 0, 0, 2, 2, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1926, 'Plastic Bag With Handle 12x15x6', NULL, 'Packaging', NULL, NULL, 0, 0, 1, 1, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1927, 'Plastic Bag With Handle 13x15x7', NULL, 'Packaging', NULL, NULL, 0, 0, 1, 1, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1928, 'Plastic Bag With Handle 13x20x7', NULL, 'Packaging', NULL, NULL, 0, 0, 2, 2, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1929, 'Plastic Bag With Handle 21x28x8', NULL, 'Packaging', NULL, NULL, 0, 0, 3, 3, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1930, 'Plastic Bag With Handle 24x32x10', NULL, 'Packaging', NULL, NULL, 0, 0, 2, 2, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1931, 'PP Plastic 10x13', NULL, 'Packaging', NULL, NULL, 0, 41, 100, 141, 59.00, 50, 1.1800, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1932, 'PP Plastic 11x9', NULL, 'Packaging', NULL, NULL, 0, 0, 0, 0, 58.00, 50, 1.1600, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1933, 'PP Plastic 3.5x5', NULL, 'Packaging', NULL, NULL, 0, 0, 0, 0, 33.00, 100, 0.3300, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1934, 'PP Plastic 3x5', NULL, 'Packaging', NULL, NULL, 0, 0, 0, 0, 31.00, 100, 0.3100, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1935, 'PP Plastic 4.5x6', NULL, 'Packaging', NULL, NULL, 0, 0, 200, 200, 68.00, 100, 0.6800, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1936, 'PP Plastic 4x6', NULL, 'Packaging', NULL, NULL, 0, 0, 0, 0, 47.00, 100, 0.4700, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1937, 'PP Plastic 6x8', NULL, 'Packaging', NULL, NULL, 0, 50, 200, 250, 95.00, 100, 0.9500, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1938, 'PP Plastic 9x12', NULL, 'Packaging', NULL, NULL, 0, 47, 143, 190, 59.00, 50, 1.1800, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1939, 'Brown Paper A3 A3', NULL, 'Packaging', NULL, NULL, 0, 0, 15, 15, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1940, 'Zip Lock 25x35 25x35', NULL, 'Packaging', NULL, NULL, 0, 0, 80, 80, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1941, 'Corrugated Brown T15', NULL, 'Pakaging', NULL, NULL, 0, 0, 4, 4, 173.00, 5, 34.6000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1942, 'Corrugated White T12', NULL, 'Pakaging', NULL, NULL, 0, 0, 10, 10, 143.00, 5, 28.6000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1943, 'Corrugated White T13', NULL, 'Pakaging', NULL, NULL, 0, 0, 5, 5, 114.00, 5, 22.8000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1944, 'Corrugated White Small', NULL, 'Pakaging', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1945, 'Coffee Contaimer/Plastic 14x10 White  White', NULL, 'Pakaging', NULL, NULL, 0, 0, 49, 49, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1946, 'Calling card Paper 300 GSM', NULL, 'Paper', NULL, NULL, 0, 3, 486, 489, 153.00, 50, 3.0600, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1947, 'Calling card Paper  220 GSM', NULL, 'Paper', NULL, NULL, 0, 46, 0, 46, 125.00, 50, 2.5000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1948, 'Non-waterproof Vinyl  Sticker A4 Matte', NULL, 'Paper', NULL, NULL, 0, 11, 18, 29, 150.00, 22, 6.8182, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1949, 'Matte Photo Sticker Paper  135 gsm', NULL, 'Paper', NULL, NULL, 0, 20, 40, 60, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1950, 'Matte Sticker Paper 165 gsm', NULL, 'Paper', NULL, NULL, 0, 0, 20, 20, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1951, 'No Back Print Photo Paper 230gsm', NULL, 'Paper', NULL, NULL, 0, 9, 0, 9, 42.00, 20, 2.1000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1952, 'No Back Print Photo Paper 250 gsm', NULL, 'Paper', NULL, NULL, 0, 0, 0, 0, 42.00, 20, 2.1000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1953, 'Sublimation Transfer Paper  A4', NULL, 'Paper', NULL, NULL, 0, 0, 300, 300, 155.00, 100, 1.5500, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1954, 'Transparent Non-waterproof Vinyl  Sticker A4', NULL, 'Paper', NULL, NULL, 0, 0, 35, 35, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1955, 'White Matte Vinyl  Sticker A4', NULL, 'Paper', NULL, NULL, 0, 0, 22, 22, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1956, 'Bond Paper  A4', NULL, 'Paper', NULL, NULL, 0, 400, 500, 900, 220.00, 500, 0.4400, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1957, 'Bond Paper  Letter', NULL, 'Paper', NULL, NULL, 0, 200, 0, 200, 210.00, 500, 0.4200, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1958, 'Bond Paper  Long', NULL, 'Paper', NULL, NULL, 0, 850, 0, 850, 232.00, 500, 0.4640, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1959, 'C2S Coated A3 230 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 31, 31, 229.00, 50, 4.5800, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1960, 'CUYI RC Glossy Photo Paper 4R 260 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 20, 20, 35.00, 20, 1.7500, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1961, 'CUYI RC Pearl Holographic Photo Paper 260 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 20, 20, 40.00, 20, 2.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1962, 'CUYI RC Satin Photo Paper  A4 200 GSM', NULL, 'Paper', NULL, NULL, 0, 2, 40, 42, 135.00, 20, 6.7500, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1963, 'CUYI RC Satin Photo Paper  A4 260 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 60, 60, 170.00, 20, 8.5000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1964, 'CUYI RC Woven Photo Paper 4R 260 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 20, 20, 47.00, 20, 2.3500, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1965, 'Foldcote Foldcote', NULL, 'Paper', NULL, NULL, 0, 0, 24, 24, 216.00, 25, 8.6400, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1966, 'Glossy Photo Sticker  A4 135 GSM', NULL, 'Paper', NULL, NULL, 0, 3, 70, 73, 68.00, 20, 3.4000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1967, 'Glossy Photo Sticker  A5 90 gsm', NULL, 'Paper', NULL, NULL, 0, 15, 0, 15, 68.00, 20, 3.4000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1968, 'L&C Double sided high glossy photo paper  A4 240 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 49, 49, 149.00, 50, 2.9800, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1969, 'L&C High glossy photo paper  A4 230 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 12, 12, 48.00, 20, 2.4000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1970, 'PET Card Sheets A4 PVC Sheets', NULL, 'Paper', NULL, NULL, 0, 0, 34, 34, 950.00, 50, 19.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1971, 'Photo Top Glitter', NULL, 'Paper', NULL, NULL, 0, 0, 0, 0, 128.00, 20, 6.4000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1972, 'Photo Top Glossy', NULL, 'Paper', NULL, NULL, 0, 1, 100, 101, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1973, 'Photo Top Holographic Rainbow', NULL, 'Paper', NULL, NULL, 0, 17, 20, 37, 142.00, 20, 7.1000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1974, 'Photo Top Matte', NULL, 'Paper', NULL, NULL, 0, 14, 180, 194, 98.00, 20, 4.9000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1975, 'Sublimation Paper (100gsm) Lanyard', NULL, 'Paper', NULL, NULL, 0, 0, 40, 40, 500.00, 100, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1976, 'Suki Vellum Board - Matte Letter 210gsm Matte Letter 210gsm', NULL, 'Paper', NULL, NULL, 0, 20, 0, 20, 0.00, 20, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1977, 'Vinyl Sticker Waterproof Matte', NULL, 'Paper', NULL, NULL, 0, 17, 0, 17, 200.00, 20, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1978, 'Vinyl Sticker Waterproof  Transparent', NULL, 'Paper', NULL, NULL, 0, 0, 0, 0, 240.00, 20, 12.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1979, 'Newsprint Letter Letter', NULL, 'Paper', NULL, NULL, 0, 200, 0, 200, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1980, 'Ultima matte Vinyl Sticker A4 A4', NULL, 'Paper', NULL, NULL, 0, 0, 11, 11, 150.00, 20, 7.5000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1981, 'Yasen Vinyl Glossy Photo Stickers  150 GSM', NULL, 'Paper', NULL, NULL, 0, 0, 340, 340, 120.00, 20, 6.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1982, 'Metal Pen - Black Black', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1983, 'Metal Pen - Blue Blue', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1984, 'Metal Pen - Green Green', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1985, 'Metal Pen - White White', NULL, 'Pens', NULL, NULL, 0, 0, 138, 138, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1986, 'Plastic White ballpoint', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 319.00, 50, 6.3800, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1987, 'Bamboo ballpoint', NULL, 'Pens', NULL, NULL, 0, 0, 0, 0, 10.00, 1, 10.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1988, 'Double Sided Glossy Photo Paper A4 120 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 3, 0, 3, 99.00, 50, 1.9800, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1989, 'Double Sided Glossy Photo Paper A4 200 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 28, 0, 28, 128.00, 50, 2.5600, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1990, 'Double Sided Glossy Photo Paper A4 250 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 140.00, 50, 2.8000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1991, 'Double Sided Glossy Photo Paper A4 160 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 120.00, 50, 2.4000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1992, 'Double Sided Glossy Photo Paper A4 220 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 135.00, 50, 2.7000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1993, 'Double Sided Glossy Photo Paper A4 300 GSM', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 155.00, 50, 3.1000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1994, 'Tree Type Glossy Paper (180/230gsm)', NULL, 'Photo Paper', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1995, 'ID Card Card', NULL, 'Plastic', NULL, NULL, 0, 84, 0, 84, 5.00, 1, 5.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1996, 'UV Sticker film A A', NULL, 'UV', NULL, NULL, 0, 0, 0, 0, 800.00, 20, 40.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1997, 'One-Hole Hang Tag Puncher', NULL, 'Puncher', NULL, NULL, 0, 1, 0, 1, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1998, 'Rubber loop Black', NULL, 'Rubber', NULL, NULL, 0, 28, 0, 28, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(1999, 'Rubber loop Transparent', NULL, 'Rubber', NULL, NULL, 0, 10, 100, 110, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2000, 'Rubber loop  Blue', NULL, 'Rubber', NULL, NULL, 0, 9, 0, 9, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2001, 'Rubber loop White', NULL, 'Rubber', NULL, NULL, 0, 4, 79, 83, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2002, 'Rubber loop  Yellow', NULL, 'Rubber', NULL, NULL, 0, 9, 0, 9, 150.00, 50, 3.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2003, 'Polo Shirt Blue Corner Colored XS- Large', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 241.00, 1, 241.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2004, 'Polo Shirt Blue Corner White XS- Large', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 192.00, 1, 192.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2005, 'Polo Shirt Blue Corner Colored XL-2XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 276.00, 1, 276.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2006, 'Polo Shirt Blue Corner Colored 3XL-4XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 310.00, 1, 310.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2007, 'Polo Shirt Blue Corner White XL-2XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 216.00, 1, 216.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2008, 'Polo Shirt Blue Corner White 3XL-4XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 255.00, 1, 255.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2009, 'Tshirt Gildan Colored XS- XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 199.00, 1, 199.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2010, 'Tshirt Gildan White XS- XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 171.00, 1, 171.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2011, 'Tshirt Gildan Colored 2XL-3XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 240.00, 1, 240.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2012, 'Tshirt Yalex White XS- Large', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 130.00, 1, 130.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2013, 'Tshirt Yalex White XL-2XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 145.00, 1, 145.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2014, 'Tshirt Yalex White 3XL-5XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 160.00, 1, 160.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2015, 'Tshirt Yalex Colored XS- Large', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 141.00, 1, 141.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2016, 'Tshirt Yalex Colored XL-2XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 165.00, 1, 165.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2017, 'Tshirt Yalex Colored 3XL-5XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 180.00, 1, 180.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2018, 'Tshirt Gildan White 2XL-3XL', NULL, 'Shirt', NULL, NULL, 0, 0, 0, 0, 201.00, 1, 201.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2019, 'Sintra Board  A2 3mm', NULL, 'Signage', NULL, NULL, 0, 0, 5, 5, 320.00, 5, 64.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2020, 'Sintra Board  A3 3mm', NULL, 'Signage', NULL, NULL, 0, 0, 9, 9, 300.00, 10, 30.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2021, 'Sintra Board  A4 1.5mm', NULL, 'Signage', NULL, NULL, 0, 0, 42, 42, 160.00, 10, 16.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2022, 'Sintra Board  A4 3mm', NULL, 'Signage', NULL, NULL, 0, 0, 18, 18, 200.00, 10, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2023, 'Canvas Board 10 x 10', NULL, 'Signage', NULL, NULL, 0, 2, 0, 2, 39.00, 2, 19.5000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2024, 'Canvas Board 20 x 30', NULL, 'Signage', NULL, NULL, 0, 0, 4, 4, 44.00, 1, 44.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2025, 'Canvas Board 25 x 30', NULL, 'Signage', NULL, NULL, 0, 0, 4, 4, 48.00, 1, 48.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2026, 'Canvas Board 30 x 40', NULL, 'Signage', NULL, NULL, 0, 0, 3, 3, 67.00, 1, 67.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2027, 'Sintra Board - A1 3mm 3mm', NULL, 'Signage', NULL, NULL, 0, 0, 5, 5, 640.00, 2, 320.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2028, 'Sintra Board - A4 5mm 5mm', NULL, 'Signage', NULL, NULL, 0, 0, 0, 0, 200.00, 10, 20.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2029, 'Printable PVC Cards (for Keytags)', NULL, 'Specialty', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2030, 'Printable PVC Pre-Cut Cards Cards', NULL, 'Specialty', NULL, NULL, 0, 0, 0, 0, 200.00, 50, 4.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2031, 'Vinyl  Sticker Roll Roll', NULL, 'Sticker', NULL, NULL, 0, 0, 0, 0, 0.00, 1, 0.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2032, 'Thermal Tape  (20mm x 33m)', NULL, 'Tape', NULL, NULL, 0, 0, 2, 2, 59.00, 1, 59.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2033, 'Thermal Tape  (6mm x 33m)', NULL, 'Tape', NULL, NULL, 0, 0, 2, 2, 34.00, 1, 34.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2034, 'Bamboo 500ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 195.00, 1, 195.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2035, 'Travel with Handle 500ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 149.00, 1, 149.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2036, 'Sports Tumbler 600ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 174.00, 1, 174.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2037, 'Travel Flat Lid 350ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 185.00, 1, 185.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2038, 'Flask  500ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 80.00, 1, 80.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2039, 'Insulated 450ml', NULL, 'Tumbler', NULL, NULL, 0, 0, 0, 0, 185.00, 1, 185.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2040, 'J-Type 23 inches', NULL, 'Umbrella', NULL, NULL, 0, 0, 0, 0, 100.00, 1, 100.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2041, 'Golf 30 inches', NULL, 'Umbrella', NULL, NULL, 0, 0, 0, 0, 159.00, 1, 159.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2042, 'Folding automatic', NULL, 'Umbrella', NULL, NULL, 0, 0, 0, 0, 90.00, 1, 90.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2043, 'UV Sticker Film B B', NULL, 'UV', NULL, NULL, 0, 0, 0, 0, 1200.00, 220, 5.4545, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2044, 'Wooden keychain rectangle', NULL, 'Wood', NULL, NULL, 0, 25, 0, 25, 130.00, 10, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2045, 'Wooden keychain square', NULL, 'Wood', NULL, NULL, 0, 0, 47, 47, 130.00, 10, 13.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2046, 'Satin Ribbon 25 yards 2cm', NULL, 'Ribbon', NULL, NULL, 0, 0, 0, 0, 37.00, 1, 37.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2047, 'Satin Ribbon 25 yards 2.5cm', NULL, 'Ribbon', NULL, NULL, 0, 0, 0, 0, 45.00, 1, 45.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2048, 'Satin Ribbon 25 yards 4cm', NULL, 'Ribbon', NULL, NULL, 0, 0, 0, 0, 25.00, 1, 25.0000, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2049, 'Shredded Paper (grams) Straight', NULL, 'Filler', NULL, NULL, 0, 0, 0, 0, 189.00, 500, 0.3780, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5),
(2050, 'Shredded Paper (grams)  Cinkled', NULL, 'Filler', NULL, NULL, 0, 0, 0, 0, 192.00, 500, 0.3840, '', NULL, 'uploads/materials/default.png', '2026-08-03 08:21:10', '2026-08-03 08:21:10', 5);

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
(22, 1494, 'total_stock', 'add', 6, 0, 6, 1, NULL, '', '2026-07-11 05:56:31'),
(23, 1496, 'total_stock', 'add', 10, 0, 10, 1, NULL, '', '2026-07-11 05:56:39');

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
(50, 'ORD-A79C1B1E', 'AI-0526-0016', 'Organization Name', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Jay', 550.00, 'gcash', NULL, NULL, 'paid', 'cancelled', '2026-05-30 06:47:30', '2026-07-03 13:01:33'),
(51, 'ORD-D1E81A83', 'AI-0626-0001', 'ABC Corp', '', '09121231234', 'Michael', 142.00, 'gcash', NULL, NULL, 'paid', 'cancelled', '2026-06-15 14:57:53', '2026-07-03 13:01:29'),
(52, 'ORD-09B38591', 'AI-0726-0001', 'Organization Name', 'admin123@gmail.com', '+639395529749', 'Jay', 1200.00, 'gcash', NULL, NULL, 'paid', 'cancelled', '2026-07-02 07:42:24', '2026-07-03 13:01:13'),
(54, 'ORD-C645C6E8', 'AI-0726-0004', 'LSPU LBC', 'jaymichaelmontemarcastillo@gmail.com', '+639395529749', 'Jay', 100.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:34:40', '2026-07-13 06:34:46'),
(55, 'ORD-1F472C6E', 'AI-0726-0003', 'LSPU LBC', 'jaymichaelmontemarcastillo@gmail.com', '09121231234', 'Michael', 22400.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:35:06', '2026-07-13 06:35:10'),
(56, 'ORD-337066BA', 'AI-0526-0015', 'Anything Inside', 'blessie@gmail.com', '09395529749', NULL, 25.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:35:40', '2026-07-13 06:35:40'),
(57, 'ORD-68842A28', 'AI-0526-0014', 'Organization Name', 'castillojaymichaeltk5@gmail.com', '+639395529749', NULL, 1150.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:35:42', '2026-07-13 06:35:42'),
(58, 'ORD-C9F01B39', 'AI-0526-0013', 'Organization Name', 'castillojaymichaeltk5@gmail.com', '+639395529749', NULL, 1150.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:35:45', '2026-07-13 06:35:45'),
(59, 'ORD-7E9D5777', 'AI-0526-0010', 'Jay Michael M. Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', NULL, 1050.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:35:50', '2026-07-13 06:35:50'),
(60, 'ORD-461A917B', 'AI-0526-0009', 'Jay Michael M. Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', NULL, 112000.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:35:52', '2026-07-13 06:35:52'),
(61, 'ORD-7EE392FC', 'AI-0526-0007', 'LSPU LBC', 'castillojaymichaeltk5@gmail.com', '', NULL, 0.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:35:55', '2026-07-13 06:35:55'),
(62, 'ORD-CCDC07C5', 'AI-0526-0006', 'Art School', 'jaymichaelmontemarcastillo@gmail.com', '+639395529749', NULL, 0.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:35:57', '2026-07-13 06:35:57'),
(63, 'ORD-E61E45A3', 'AI-0526-0005', 'Art School', 'castillojaymichaeltk5@gmail.com', '', NULL, 0.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-13 06:36:00', '2026-07-13 06:36:00'),
(64, 'ORD-DCE58F21', 'AI-0726-0002', 'LSPU LBC', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 189.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-15 06:34:16', '2026-07-15 06:34:16'),
(65, 'ORD-7183E448', 'AI-0526-0004', 'Jay Michael M. Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', NULL, 0.00, '', NULL, NULL, 'paid', 'delivered', '2026-07-15 06:34:20', '2026-07-15 06:34:20');

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
  `estimated_production_time` varchar(100) DEFAULT NULL,
  `extra_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `material_type`, `product_type_id`, `price`, `stock`, `production_cost`, `profit_margin`, `unit`, `width_cm`, `height_cm`, `depth_cm`, `weight_grams`, `color_options`, `size_options`, `material_specs`, `finishing_options`, `image`, `description`, `created_at`, `updated_at`, `featured`, `status`, `estimated_production_time`, `extra_data`) VALUES
(1244, 'A4 Colored', 'A4COLORE-557', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1245, 'Short Colored', 'SHORTCOL-484', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1246, 'Long Colored', 'LONGCOLO-508', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1247, 'A4 Colored', 'A4COLORE-126', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1248, 'Short Colored', 'SHORTCOL-268', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1249, 'A4 Black and White', 'A4BLACKA-103', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1250, 'Long Colored', 'LONGCOLO-979', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1251, 'Short Black and White', 'SHORTBLA-264', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1252, 'Long Black and White', 'LONGBLAC-199', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1253, 'A4 Black and White', 'A4BLACKA-613', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1254, 'A4 Half Colored', 'A4HALFCO-213', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1255, 'Short Black and White', 'SHORTBLA-554', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1256, 'Short Half Colored', 'SHORTHAL-592', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1257, 'Long Black and White', 'LONGBLAC-935', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1258, 'A4 Half Colored', 'A4HALFCO-549', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1259, 'Short Half Colored', 'SHORTHAL-932', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1260, 'Long Half Colored', 'LONGHALF-320', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1261, 'Ordinary Sticker Paper', 'ORDINARY-267', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1262, 'Ordinary SP with Laminate', 'ORDINARY-872', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1263, 'Long Half Colored', 'LONGHALF-903', 'assembled_product', 1, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1264, 'Ordinary Sticker Paper', 'ORDINARY-497', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1265, 'Vinyl Sticker Matte', 'VINYLSTI-454', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:28', '2026-08-03 08:19:28', 0, 'active', NULL, NULL),
(1266, 'Ordinary SP with Laminate', 'ORDINARY-332', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1267, 'Vinyl Sticker Matte with Laminate', 'VINYLSTI-331', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1268, 'Vinyl Sticker Matte', 'VINYLSTI-831', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1269, 'Vinyl Sticker Glossy', 'VINYLSTI-368', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1270, 'Vinyl Sticker Glossy with Laminate', 'VINYLSTI-455', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1271, 'Vinyl Sticker Matte with Laminate', 'VINYLSTI-110', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1272, 'Vinyl Sticker Glossy', 'VINYLSTI-873', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1273, 'Calling card one side no laminate', 'CALLINGC-626', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1274, 'Vinyl Sticker Glossy with Laminate', 'VINYLSTI-835', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1275, 'Calling Card B2B no laminate', 'CALLINGC-792', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1276, 'Calling card one side no laminate', 'CALLINGC-142', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1277, 'Calling Card B2B no laminate', 'CALLINGC-392', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1278, 'Calling card one side laminated', 'CALLINGC-898', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1279, 'Calling card B2B laminated', 'CALLINGC-362', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1280, 'TY Cards one side', 'TYCARDSO-293', 'assembled_product', 4, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1281, 'TY Cards B2B', 'TYCARDSB-451', 'assembled_product', 4, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1282, 'Decals', 'DECALS-288', 'assembled_product', 5, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1283, 'UV sticker logo', 'UVSTICKE-728', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1284, 'DTF Print half A4', 'DTFPRINT-117', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1285, 'DTF Print logo press', 'DTFPRINT-127', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1286, 'DTF Print A4 press', 'DTFPRINT-244', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1287, 'DTF Print A3 press', 'DTFPRINT-688', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1288, 'UV Sticker half A4', 'UVSTICKE-324', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1289, 'UV Sticker A4', 'UVSTICKE-816', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1290, 'UV sticker A3', 'UVSTICKE-518', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1291, 'Vinyl SP Matte Laminated Die Cut', 'VINYLSPM-581', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1292, 'Vinyl SP Glossy laminated Kiss Cut', 'VINYLSPG-192', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1293, 'Vinyl SP Matte NL Die Cut', 'VINYLSPM-204', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1294, 'Vinyl SP Glossy NL Kiss Cut', 'VINYLSPG-240', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1295, 'White Mug Subli', 'WHITEMUG-911', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1296, 'Calling card one side laminated', 'CALLINGC-661', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1297, 'Frosted Mug Subli', 'FROSTEDM-766', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1298, 'Calling card B2B laminated', 'CALLINGC-898', 'assembled_product', 3, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1299, 'Magic Mug Subli', 'MAGICMUG-113', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1300, 'TY Cards one side', 'TYCARDSO-179', 'assembled_product', 4, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1301, 'Enamel Mug 350ml Subli', 'ENAMELMU-676', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1302, 'TY Cards B2B', 'TYCARDSB-383', 'assembled_product', 4, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1303, 'Decals', 'DECALS-614', 'assembled_product', 5, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1304, 'Inner Colored Subli', 'INNERCOL-672', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1305, 'UV sticker logo', 'UVSTICKE-687', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1306, 'Baseball Cap logo print', 'BASEBALL-145', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1307, 'DTF Print half A4', 'DTFPRINT-545', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1308, 'Mesh Cap Logo Print', 'MESHCAPL-274', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1309, 'DTF Print logo press', 'DTFPRINT-377', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1310, 'Bucket hat Logo Print', 'BUCKETHA-855', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1311, 'DTF Print A4 press', 'DTFPRINT-268', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1312, 'Tumbler flask with logo', 'TUMBLERF-727', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1313, 'DTF Print A3 press', 'DTFPRINT-513', 'assembled_product', 6, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1314, 'Tumbler travel with logo', 'TUMBLERT-337', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1315, 'UV Sticker half A4', 'UVSTICKE-148', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1316, 'Tumbler travel with handle logo', 'TUMBLERT-515', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1317, 'UV Sticker A4', 'UVSTICKE-224', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1318, 'Tumbler Sports with logo', 'TUMBLERS-160', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:29', '2026-08-03 08:19:29', 0, 'active', NULL, NULL),
(1319, 'UV sticker A3', 'UVSTICKE-294', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1320, 'Keychain wooden rectangle', 'KEYCHAIN-620', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1321, 'Vinyl SP Matte Laminated Die Cut', 'VINYLSPM-351', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1322, 'Keychain wooden square', 'KEYCHAIN-579', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1323, 'Vinyl SP Glossy laminated Kiss Cut', 'VINYLSPG-628', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1324, 'Keychain wooden round', 'KEYCHAIN-935', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1325, 'Vinyl SP Matte NL Die Cut', 'VINYLSPM-283', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1326, 'Acrylic keychain rec', 'ACRYLICK-401', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1327, 'Vinyl SP Glossy NL Kiss Cut', 'VINYLSPG-149', 'assembled_product', 2, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1328, 'Acrylic keychain square', 'ACRYLICK-842', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1329, 'White Mug Subli', 'WHITEMUG-809', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1330, 'Ackrylic keychain round', 'ACKRYLIC-362', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1331, 'Frosted Mug Subli', 'FROSTEDM-340', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1332, 'Bag tag B2B laminated with loop', 'BAGTAGB2-635', 'assembled_product', 11, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1333, 'Magic Mug Subli', 'MAGICMUG-719', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1334, 'Bag tag one side laminated with loop', 'BAGTAGON-941', 'assembled_product', 11, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1335, 'Enamel Mug 350ml Subli', 'ENAMELMU-136', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1336, 'Lanyard with side release G hook', 'LANYARDW-878', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1337, 'Inner Colored Subli', 'INNERCOL-847', 'assembled_product', 7, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1338, 'Lanyard with Side release clamp', 'LANYARDW-488', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1339, 'Baseball Cap logo print', 'BASEBALL-493', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1340, 'Lanyard glossy with side release G hook', 'LANYARDG-206', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1341, 'Mesh Cap Logo Print', 'MESHCAPL-233', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1342, 'Lanyard glossy with Side release clamp', 'LANYARDG-652', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1343, 'Bucket hat Logo Print', 'BUCKETHA-772', 'assembled_product', 8, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1344, 'ID Card with laminate B2B pigment', 'IDCARDWI-768', 'assembled_product', 13, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1345, 'Tumbler flask with logo', 'TUMBLERF-674', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1346, 'ID Card with laminate B2B UV', 'IDCARDWI-860', 'assembled_product', 13, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1347, 'Tumbler travel with logo', 'TUMBLERT-519', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1348, 'Shirt with logo', 'SHIRTWIT-676', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1349, 'Tumbler travel with handle logo', 'TUMBLERT-326', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1350, 'Shirt with A5', 'SHIRTWIT-690', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1351, 'Tumbler Sports with logo', 'TUMBLERS-480', 'assembled_product', 9, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1352, 'Shirt with A4', 'SHIRTWIT-526', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1353, 'Keychain wooden rectangle', 'KEYCHAIN-851', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1354, 'Shirt with A3', 'SHIRTWIT-454', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1355, 'Keychain wooden square', 'KEYCHAIN-144', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1356, 'Shirt logo and A3', 'SHIRTLOG-594', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1357, 'Keychain wooden round', 'KEYCHAIN-356', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1358, 'Shirt B2B A4', 'SHIRTB2B-955', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1359, 'Acrylic keychain rec', 'ACRYLICK-289', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1360, 'Polo Shirt with logo', 'POLOSHIR-458', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1361, 'Acrylic keychain square', 'ACRYLICK-772', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1362, 'Ackrylic keychain round', 'ACKRYLIC-283', 'assembled_product', 10, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1363, 'Polo Shirt with A5', 'POLOSHIR-394', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1364, 'Polo Shirt with A4', 'POLOSHIR-965', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1365, 'Bag tag B2B laminated with loop', 'BAGTAGB2-168', 'assembled_product', 11, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1366, 'Polo Shirt with A3', 'POLOSHIR-347', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1367, 'Bag tag one side laminated with loop', 'BAGTAGON-790', 'assembled_product', 11, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1368, 'Polo Shirt logo and A3', 'POLOSHIR-893', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1369, 'Lanyard with side release G hook', 'LANYARDW-914', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1370, 'Polo Shirt B2B A4', 'POLOSHIR-565', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1371, 'Lanyard with Side release clamp', 'LANYARDW-867', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1372, 'Tote Plain Flat A4 print subli 8 by 10', 'TOTEPLAI-972', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1373, 'Lanyard glossy with side release G hook', 'LANYARDG-741', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1374, 'Lanyard glossy with Side release clamp', 'LANYARDG-549', 'assembled_product', 12, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1375, 'ID Card with laminate B2B pigment', 'IDCARDWI-387', 'assembled_product', 13, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1376, 'Tote Plain Flat A4 print subli 10 by 12', 'TOTEPLAI-505', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1377, 'ID Card with laminate B2B UV', 'IDCARDWI-340', 'assembled_product', 13, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1378, 'Tote Plain Flat A4 print subli 12  by 14', 'TOTEPLAI-241', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1379, 'Shirt with logo', 'SHIRTWIT-734', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1380, 'Tote Plain Flat A4 print subli 13 by 15', 'TOTEPLAI-535', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1381, 'Shirt with A5', 'SHIRTWIT-333', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1382, 'Tote Plain Flat A4 print DTF 8 by 10', 'TOTEPLAI-810', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1383, 'Shirt with A4', 'SHIRTWIT-251', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1384, 'Tote Plain Flat A4 print DTF 10 by 12', 'TOTEPLAI-552', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1385, 'Shirt with A3', 'SHIRTWIT-968', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1386, 'Tote Plain Flat A4 print DTF 12  by 14', 'TOTEPLAI-117', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1387, 'Shirt logo and A3', 'SHIRTLOG-728', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1388, 'Tote Plain Flat A4 print DTF 13 by 15', 'TOTEPLAI-164', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1389, 'Shirt B2B A4', 'SHIRTB2B-773', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1390, 'Tote Plain Flat logo DTF 8 by 10', 'TOTEPLAI-811', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1391, 'Polo Shirt with logo', 'POLOSHIR-989', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1392, 'Tote Plain Flat logoprint DTF 10 by 12', 'TOTEPLAI-809', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1393, 'Polo Shirt with A5', 'POLOSHIR-534', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1394, 'Polo Shirt with A4', 'POLOSHIR-594', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1395, 'Tote Plain Flat logo print DTF 12  by 14', 'TOTEPLAI-827', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1396, 'Polo Shirt with A3', 'POLOSHIR-632', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1397, 'Tote Plain Flat logo print DTF 13 by 15', 'TOTEPLAI-176', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1398, 'Tote Colored Flat A4 print DTF 8 by 10', 'TOTECOLO-926', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1399, 'Polo Shirt logo and A3', 'POLOSHIR-461', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1400, 'Tote Colored Flat A4 print DTF 10 by 12', 'TOTECOLO-736', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1401, 'Polo Shirt B2B A4', 'POLOSHIR-657', 'assembled_product', 14, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1402, 'Tote Colored Flat A4 print DTF 12  by 14', 'TOTECOLO-681', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1403, 'Tote Plain Flat A4 print subli 8 by 10', 'TOTEPLAI-750', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1404, 'Tote Colored Flat A4 print DTF 13 by 15', 'TOTECOLO-645', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1405, 'Tote Plain Flat A4 print subli 10 by 12', 'TOTEPLAI-697', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1406, 'Tote Colored Flat logo DTF 8 by 10', 'TOTECOLO-101', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1407, 'Tote Plain Flat A4 print subli 12  by 14', 'TOTEPLAI-499', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1408, 'Tote Colored Flat logoprint DTF 10 by 12', 'TOTECOLO-370', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1409, 'Tote Plain Flat A4 print subli 13 by 15', 'TOTEPLAI-546', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1410, 'Tote Colored Flat logo print DTF 12  by 14', 'TOTECOLO-331', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1411, 'Tote Plain Flat A4 print DTF 8 by 10', 'TOTEPLAI-982', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1412, 'Tote Colored Flat logo print DTF 13 by 15', 'TOTECOLO-972', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1413, 'Tote Plain Flat A4 print DTF 10 by 12', 'TOTEPLAI-534', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1414, 'Tote Colored Base A4 print DTF 8 by 10', 'TOTECOLO-697', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1415, 'Tote Plain Flat A4 print DTF 12  by 14', 'TOTEPLAI-889', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1416, 'Tote Colored Base A4 print DTF 10 by 12', 'TOTECOLO-267', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1417, 'Tote Plain Flat A4 print DTF 13 by 15', 'TOTEPLAI-140', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1418, 'Tote Colored Base A4 print DTF 12  by 14', 'TOTECOLO-755', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1419, 'Tote Plain Flat logo DTF 8 by 10', 'TOTEPLAI-455', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1420, 'Tote Colored Base A4 print DTF 13 by 15', 'TOTECOLO-489', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1421, 'Tote Plain Flat logoprint DTF 10 by 12', 'TOTEPLAI-422', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1422, 'Tote Colored Base logo DTF 8 by 10', 'TOTECOLO-654', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1423, 'Tote Plain Flat logo print DTF 12  by 14', 'TOTEPLAI-970', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1424, 'Tote Colored Base logoprint DTF 10 by 12', 'TOTECOLO-192', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1425, 'Tote Plain Flat logo print DTF 13 by 15', 'TOTEPLAI-336', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1426, 'Tote Colored Base logo print DTF 12  by 14', 'TOTECOLO-302', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1427, 'Tote Colored Flat A4 print DTF 8 by 10', 'TOTECOLO-655', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1428, 'Tote Colored Base logo print DTF 13 by 15', 'TOTECOLO-461', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1429, 'Tote Colored Flat A4 print DTF 10 by 12', 'TOTECOLO-120', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1430, 'Sintra A4 3mm UV', 'SINTRAA4-386', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1431, 'Tote Colored Flat A4 print DTF 12  by 14', 'TOTECOLO-776', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1432, 'Sintra A4 5mm UV', 'SINTRAA4-981', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1433, 'Tote Colored Flat A4 print DTF 13 by 15', 'TOTECOLO-678', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1434, 'Sintra A3 3mm UV', 'SINTRAA3-376', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1435, 'Tote Colored Flat logo DTF 8 by 10', 'TOTECOLO-497', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1436, 'Canvas 20 by 30', 'CANVAS20-807', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1437, 'Tote Colored Flat logoprint DTF 10 by 12', 'TOTECOLO-491', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1438, 'Tote Colored Flat logo print DTF 12  by 14', 'TOTECOLO-694', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1439, 'Canvas 25 by 30', 'CANVAS25-175', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL);
INSERT INTO `products` (`id`, `name`, `sku`, `material_type`, `product_type_id`, `price`, `stock`, `production_cost`, `profit_margin`, `unit`, `width_cm`, `height_cm`, `depth_cm`, `weight_grams`, `color_options`, `size_options`, `material_specs`, `finishing_options`, `image`, `description`, `created_at`, `updated_at`, `featured`, `status`, `estimated_production_time`, `extra_data`) VALUES
(1440, 'Tote Colored Flat logo print DTF 13 by 15', 'TOTECOLO-639', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1441, 'Canvas 20 by 20', 'CANVAS20-924', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1442, 'Tote Colored Base A4 print DTF 8 by 10', 'TOTECOLO-833', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1443, 'Sintra Sticker A4', 'SINTRAST-116', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1444, 'Sintra Sticker A3', 'SINTRAST-118', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1445, 'Tote Colored Base A4 print DTF 10 by 12', 'TOTECOLO-809', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1446, 'Tote Colored Base A4 print DTF 12  by 14', 'TOTECOLO-784', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1447, 'Sintra Sticker A2', 'SINTRAST-539', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1448, 'Sintra Sticker A1', 'SINTRAST-900', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1449, 'Tote Colored Base A4 print DTF 13 by 15', 'TOTECOLO-637', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1450, 'Tote Colored Base logo DTF 8 by 10', 'TOTECOLO-682', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1451, 'Tote Colored Base logoprint DTF 10 by 12', 'TOTECOLO-553', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1452, 'Tote Colored Base logo print DTF 12  by 14', 'TOTECOLO-458', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1453, 'Tote Colored Base logo print DTF 13 by 15', 'TOTECOLO-615', 'assembled_product', 15, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1454, 'Sintra A4 3mm UV', 'SINTRAA4-237', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1455, 'Sintra A4 5mm UV', 'SINTRAA4-110', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1456, 'Sintra A3 3mm UV', 'SINTRAA3-948', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1457, 'Canvas 20 by 30', 'CANVAS20-481', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1458, 'Canvas 25 by 30', 'CANVAS25-136', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1459, 'Canvas 20 by 20', 'CANVAS20-103', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1460, 'Sintra Sticker A4', 'SINTRAST-523', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1461, 'Sintra Sticker A3', 'SINTRAST-678', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1462, 'Sintra Sticker A2', 'SINTRAST-731', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL),
(1463, 'Sintra Sticker A1', 'SINTRAST-727', 'assembled_product', 16, 0.00, 0, 0.00, 0.00, 'piece', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default-product.png', '', '2026-08-03 08:19:30', '2026-08-03 08:19:30', 0, 'active', NULL, NULL);

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
(49, 'Filler', 'filler', NULL, NULL, NULL, 'active', '2026-06-26 14:22:18', '2026-06-26 14:22:18'),
(50, 'Jay Michael Castillo', 'jay-michael-castillo', NULL, NULL, NULL, 'active', '2026-07-11 05:50:42', '2026-07-11 05:50:42');

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
(10, 'AI-202605-0003', NULL, '4tmoudenuu4cnul5hu16dsr7uk', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'accepted', 950.00, 0.00, 0.00, 950.00, '', NULL, '2026-05-01 15:28:17', '2026-05-02', '2026-07-12 05:24:19', 0, NULL, NULL, NULL),
(11, 'AI-202605-0004', NULL, '4tmoudenuu4cnul5hu16dsr7uk', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 950.00, 0.00, 0.00, 950.00, 'Special Notes and Instructions\n\nFile Formats: Please submit your file in the following formats: PDF, TIFF, PNG or high-resolution JPEG for best results.\nColor Specifications: Ensure your files are set to CMYK color mode to avoid any discrepancies in color printing.\nProof Approval: A digital proof and test print will be sent for your approval before the final print run. Please review carefully to avoid any errors.\nPayment Terms: Full payment is due upon delivery of the product, with 50% required downpayment upon order confirmation.\nDelivery & Lead Time: The delivery time may vary depending on the quantity of your order. Standard turnaround time will be provided once we confirm the details of your order. Please let us know if you have any specific deadlines, and we’ll do our best to accommodate them.\nShipment and fees: Customers can pick up their orders at our designated pickup points in Laguna and Mandaluyong. Please confirm your preferred pickup location when placing the order. Shipping fees will be shouldered by the client and are based on the courier’s delivery rates.\nAdditional Requests: For custom sizes or any specific finishing (e.g., lamination, binding), please mention your requirements when placing the order.', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0004_20260526_132352.pdf', '2026-05-01 15:30:44', '2026-05-02', '2026-05-26 11:23:52', 0, NULL, NULL, NULL),
(12, 'AI-202605-0005', NULL, 'civ8n9talgmmmjh9a60scskea5', 'Juan Dela Cruz', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'accepted', 950.00, 0.00, 0.00, 950.00, '', NULL, '2026-05-02 11:38:44', '2026-05-03', '2026-05-29 09:40:36', 0, NULL, NULL, NULL),
(14, 'AI-202605-0006', NULL, 'brhmq4f023da8vt3t57u5v5iis', 'Juan Dela Cruz', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 54500.00, 0.00, 0.00, 54500.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0006_20260511_172253.pdf', '2026-05-08 09:00:08', '2026-05-09', '2026-05-11 15:22:53', 0, NULL, NULL, NULL),
(15, 'AI-202605-0007', NULL, '3dqne18loiism1rmf9ltub9990', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 47100.00, 0.00, 0.00, 47100.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0007_20260526_125208.pdf', '2026-05-08 11:58:42', '2026-05-09', '2026-05-26 10:52:08', 0, NULL, NULL, NULL),
(17, 'AI-202605-0008', NULL, '3dqne18loiism1rmf9ltub9990', 'Jay Michael M. Castillo', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', NULL, 'converted', 47100.00, 0.00, 0.00, 47100.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0008_20260712_072427.pdf', '2026-05-08 12:07:08', '2026-05-09', '2026-07-12 05:24:28', 0, NULL, NULL, NULL),
(18, 'AI-202605-0009', NULL, '3dqne18loiism1rmf9ltub9990', 'Jay Michael Castillo', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09395529749', NULL, 'converted', 5450.00, 0.00, 0.00, 5450.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-202605-0009_20260511_171758.pdf', '2026-05-08 12:07:39', '2026-05-07', '2026-07-11 14:58:13', 1, 110, NULL, NULL),
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
(57, 'AI-0526-0013', NULL, NULL, 'Organization Name', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 1150.00, 0.00, 0.00, 1150.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0526-0013_20260712_074449.pdf', '2026-05-30 06:09:14', NULL, '2026-07-12 05:44:49', 1, 88, '2026-07-03 08:13:08', 1),
(58, 'AI-0526-0014', NULL, NULL, 'Organization Name', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 1150.00, 0.00, 0.00, 1150.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-05-0098_20260530_081534.pdf', '2026-05-30 06:13:59', NULL, '2026-06-03 15:04:25', 1, 84, '2026-06-03 15:04:25', 1),
(59, 'AI-0526-0015', NULL, NULL, 'Anything Inside', 'Blessie  Mabilangan', 'blessie@gmail.com', '09395529749', '', 'converted', 25.00, 0.00, 0.00, 25.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0526-0015_20260603_140159.pdf', '2026-05-30 06:20:16', NULL, '2026-06-03 13:59:01', 1, 81, '2026-06-03 13:59:01', 1),
(60, 'AI-0526-0016', NULL, NULL, 'Organization Name', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', '', 'converted', 550.00, 0.00, 0.00, 550.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0006_20260711_080621.pdf', '2026-05-30 06:31:27', NULL, '2026-07-11 06:06:21', 1, 80, '2026-05-30 06:42:09', 1),
(61, 'AI-0626-0001', NULL, NULL, 'ABC Corp', 'Jay Michael M. Castillo', '', '09121231234', '', 'expired', 142.00, 0.00, 0.00, 142.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0626-0001_20260626_165124.pdf', '2026-06-05 11:50:41', NULL, '2026-07-03 13:01:29', 1, 86, '2026-06-05 11:53:00', 1),
(62, 'AI-0726-0001', NULL, NULL, 'Organization Name', 'Jay Michael Castillo', 'admin123@gmail.com', '+639395529749', '', 'converted', 1200.00, 0.00, 0.00, 1200.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0726-0001_20260703_101345.pdf', '2026-07-01 08:35:48', NULL, '2026-07-12 05:44:25', 1, 91, '2026-07-03 08:32:51', 1),
(63, 'AI-0726-0002', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '09395529749', 'Anos, Los Banos, Laguna', 'converted', 189.00, 0.00, 0.00, 189.00, '', '/Anything_Inside_Website/uploads/delivery_receipts/delivery_receipt_DR-2026-07-0003_20260703_160116.pdf', '2026-07-03 14:00:59', NULL, '2026-07-11 13:58:58', 1, 101, NULL, NULL),
(64, 'AI-0726-0003', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'jaymichaelmontemarcastillo@gmail.com', '09121231234', 'Anos, Los Banos, Laguna', 'converted', 22400.00, 0.00, 0.00, 22400.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0726-0003_20260711_154105.pdf', '2026-07-08 09:52:07', NULL, '2026-07-11 14:43:43', 1, 109, NULL, NULL),
(65, 'AI-0726-0004', NULL, NULL, 'LSPU LBC', 'Jay Michael Castillo', 'castillojaymichaeltk5@gmail.com', '+639395529749', 'Anos, Los Banos, Laguna', 'converted', 100.00, 0.00, 0.00, 100.00, '', '/Anything_Inside_Website/uploads/quotations/quotation_AI-0726-0004_20260720_143141.pdf', '2026-07-11 14:57:40', NULL, '2026-07-22 10:43:31', 1, 111, NULL, NULL);

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
(291, 61, NULL, 'Photo Top Holographic Rainbow', 20, 7.10, 142.00),
(294, 62, NULL, 'Acrylic Keychain 5x9 cm cm', 6, 200.00, 1200.00),
(295, 63, NULL, 'Corrugated White T12Bond Paper  A4', 5, 28.60, 143.00),
(296, 63, NULL, 'Bond Paper  Long', 100, 0.46, 46.00),
(297, 64, NULL, '6-in-1 Combo Heat Press Press', 2, 11200.00, 22400.00),
(298, 60, NULL, 'Calling card B2B laminated', 1, 550.00, 550.00),
(301, 57, NULL, 'Ackrylic keychain round', 21, 50.00, 1050.00),
(302, 57, NULL, 'Bamboo ballpoint', 10, 10.00, 100.00),
(303, 65, NULL, 'Acrylic keychain rec', 2, 50.00, 100.00);

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
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'retention_enabled', '1', '2026-07-22 11:13:36'),
(2, 'retention_period', '1_month', '2026-07-22 11:13:36');

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
-- Indexes for table `bundles_merchandise`
--
ALTER TABLE `bundles_merchandise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type` (`type`);

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
-- Indexes for table `email_settings`
--
ALTER TABLE `email_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_email_settings_admin` (`UpdatedBy`);

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
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`);

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
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `bom_audit`
--
ALTER TABLE `bom_audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `bundles_merchandise`
--
ALTER TABLE `bundles_merchandise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `delivery_receipts`
--
ALTER TABLE `delivery_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2051;

--
-- AUTO_INCREMENT for table `materials_logs`
--
ALTER TABLE `materials_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1464;

--
-- AUTO_INCREMENT for table `product_materials`
--
ALTER TABLE `product_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2331;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=304;

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
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `email_settings`
--
ALTER TABLE `email_settings`
  ADD CONSTRAINT `fk_email_settings_admin` FOREIGN KEY (`UpdatedBy`) REFERENCES `admins` (`AdminID`) ON DELETE SET NULL;

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
