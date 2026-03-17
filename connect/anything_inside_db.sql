-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2026 at 07:54 PM
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
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `order_status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `total_amount`, `payment_method`, `payment_status`, `order_status`, `created_at`) VALUES
(1, 'ORD-20260316-3850', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 2300.00, 'gcash', 'pending', 'pending', '2026-03-16 17:42:09'),
(2, 'ORD-20260316-6741', 'dasda', 'lawrenzesalvador29@gmail.com', '09926611791', 750.00, 'cash', 'pending', 'pending', '2026-03-16 17:48:12'),
(3, 'ORD-20260316-2554', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 2300.00, 'cash', 'pending', 'pending', '2026-03-16 17:52:53'),
(4, 'ORD-20260316-2093', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 3250.00, 'cash', 'pending', 'pending', '2026-03-16 17:54:08'),
(5, 'ORD-20260316-7892', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 950.00, 'cash', 'pending', 'pending', '2026-03-16 17:55:45'),
(6, 'ORD-20260316-9714', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 2300.00, 'cash', 'pending', 'pending', '2026-03-16 17:56:22'),
(7, 'ORD-20260316-7075', 'Lawrence', 'lawrenzesalvador29@gmail.com', '09926611791', 3250.00, 'cash', 'pending', 'pending', '2026-03-16 18:21:21');

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
(9, 7, 5, 'Couple Wine Set', 1, 2300.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`, `description`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'Baby Welcome Kit', 'Baby Shower', 1950.00, 'assets/images/products/baby_welcome_kit.jpg', 'Adorable baby essentials including a onesie, soft blanket, rattle, and plush toy.', 15, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(2, 'Gourmet Chocolate Box', 'Holiday', 1400.00, 'assets/images/products/gourmet_chocolate_box.avif', '24-piece assorted Belgian chocolates in an elegant premium box.', 20, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(3, 'Wedding Memory Frame', 'Wedding', 1100.00, 'assets/images/products/wedding_memory_frame.jpg', 'Elegant silver-plated frame engraved with \"Mr & Mrs\" design.', 8, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(4, 'Spa Relaxation Set', 'Birthday', 1650.00, 'assets/images/products/spa_relaxation_set.jpg', 'Pamper yourself with bath bombs, body lotion, and face mask.', 12, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(5, 'Couple Wine Set', 'Anniversary', 2300.00, 'assets/images/products/couple_wine_set.avif', 'Premium red wine with two engraved crystal wine glasses.', 2, '2026-03-16 16:10:07', '2026-03-16 18:21:21'),
(6, 'Success Gift Box', 'Graduation', 1250.00, 'assets/images/products/success_gift_box.jpg', 'Inspirational notebook, premium pen, and celebration treats.', 10, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(7, 'Romantic Rose Box', 'Valentine', 2800.00, 'assets/images/products/romantic_rose_box.jpg', 'Preserved roses arranged in a luxury heart-shaped box.', 5, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(8, 'Executive Gift Set', 'Corporate', 3100.00, 'assets/images/products/executive_gift_set.jpg', 'Luxury leather planner with pen and premium coffee selection.', 6, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(9, 'Birthday Surprise Box', 'Birthday', 1800.00, 'assets/images/products/birthday_surprise_box.jpg', 'Birthday cake flavored treats, candles, and party poppers.', 15, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(10, 'Anniversary Photo Album', 'Anniversary', 950.00, 'assets/images/products/anniversary_photo_album.avif', 'Leather-bound photo album with \"Our Love Story\" embossing.', 7, '2026-03-16 16:10:07', '2026-03-16 18:21:21'),
(11, 'Christmas Hamper', 'Holiday', 3500.00, 'assets/images/products/christmas_hamper.jpg', 'Festive hamper with wine, cheese, chocolates, and Christmas treats.', 8, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(12, 'Thank You Gift Set', 'Thank You', 850.00, 'assets/images/products/thank_you_gift_set.jpg', 'Premium thank you cards with gourmet cookies and tea.', 20, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(13, 'Baby Shower Favors', 'Baby Shower', 750.00, 'assets/images/products/baby_shower_favors.jpg', 'Set of 5 baby shower favors with mini onesies and candies.', 24, '2026-03-16 16:10:07', '2026-03-16 17:48:12'),
(14, 'Wedding Guest Book', 'Wedding', 1200.00, 'assets/images/products/wedding_guest_book.jpg', 'Elegant wedding guest book with pen holder.', 10, '2026-03-16 16:10:07', '2026-03-16 17:34:02'),
(15, 'Valentine Chocolate Set', 'Valentine', 1650.00, 'assets/images/products/valentine_chocolate_set.jpg', 'Heart-shaped chocolate box with assorted pralines.', 15, '2026-03-16 16:10:07', '2026-03-16 17:34:02');

-- --------------------------------------------------------

--
-- Stand-in structure for view `product_categories`
-- (See below for the actual view)
--
CREATE TABLE `product_categories` (
`category` varchar(100)
,`product_count` bigint(21)
);

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
(8, 'q44sr1eood0iq92duj3ettr3fl', 13, '2026-03-16 18:23:58', '2026-03-16 18:23:58');

-- --------------------------------------------------------

--
-- Structure for view `product_categories`
--
DROP TABLE IF EXISTS `product_categories`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_categories`  AS SELECT DISTINCT `products`.`category` AS `category`, count(0) AS `product_count` FROM `products` GROUP BY `products`.`category` ORDER BY `products`.`category` ASC ;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_price` (`price`);

--
-- Indexes for table `saved_carts`
--
ALTER TABLE `saved_carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_session_product` (`session_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_session` (`session_id`);

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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `saved_carts`
--
ALTER TABLE `saved_carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

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
