-- ═════════════════════════════════════════════════════════════
-- PAYMENT METHODS TABLE MIGRATION
-- This table stores custom payment methods that can be added by admins
-- ═════════════════════════════════════════════════════════════

-- Create the payment_methods table
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method_name` varchar(100) NOT NULL COMMENT 'Display name of the payment method (e.g., PayPal, Bank Transfer)',
  `method_value` varchar(50) NOT NULL UNIQUE COMMENT 'Identifier used in database (e.g., paypal, bank_transfer)',
  `icon_class` varchar(100) DEFAULT 'fa-solid fa-credit-card' COMMENT 'Font Awesome icon class for the method',
  `sort_order` int(11) DEFAULT 999 COMMENT 'Display order in dropdown',
  `is_system` tinyint(1) DEFAULT 0 COMMENT '1 if system payment method (cannot be deleted)',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1 if method is active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_method_value` (`method_value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ═════════════════════════════════════════════════════════════
-- INSERT SYSTEM PAYMENT METHODS
-- These are the default methods that cannot be deleted
-- ═════════════════════════════════════════════════════════════

INSERT IGNORE INTO `payment_methods` 
(`method_name`, `method_value`, `icon_class`, `sort_order`, `is_system`, `is_active`) 
VALUES 
('Cash on Delivery', 'cash', 'fa-solid fa-money-bill-wave', 1, 1, 1),
('GCash', 'gcash', 'fa-solid fa-mobile-alt', 2, 1, 1),
('Card', 'card', 'fa-regular fa-credit-card', 3, 1, 1);

-- ═════════════════════════════════════════════════════════════
-- UPDATE ORDERS TABLE (If needed for future updates)
-- The current orders table uses ENUM which is limited
-- In the future, you may want to change to VARCHAR and reference payment_methods
-- ═════════════════════════════════════════════════════════════

-- ALTER TABLE `orders` CHANGE `payment_method` `payment_method` VARCHAR(50) NOT NULL DEFAULT 'pending';
-- ALTER TABLE `payments` CHANGE `payment_method` `payment_method` VARCHAR(50) NOT NULL DEFAULT 'pending';
