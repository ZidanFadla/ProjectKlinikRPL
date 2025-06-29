-- Update Database untuk Klinik Azra
-- Menambahkan tabel-tabel yang hilang

-- Tabel cart untuk keranjang belanja
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel booking_treatments untuk booking treatment
CREATE TABLE IF NOT EXISTS `booking_treatments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `treatments_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `treatments_id` (`treatments_id`),
  CONSTRAINT `booking_treatments_ibfk_1` FOREIGN KEY (`treatments_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel transactions untuk payment
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','success','failed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_type` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Menambahkan kolom phone dan address ke tabel users untuk data customer yang lebih lengkap
ALTER TABLE `users` 
ADD COLUMN `phone` varchar(20) DEFAULT NULL AFTER `username`,
ADD COLUMN `address` text DEFAULT NULL AFTER `phone`,
ADD COLUMN `full_name` varchar(255) DEFAULT NULL AFTER `username`;

-- Menambahkan kolom duration ke tabel treatments untuk durasi treatment
ALTER TABLE `treatments` 
ADD COLUMN `duration` int(11) DEFAULT 60 COMMENT 'Duration in minutes' AFTER `price`;

-- Menambahkan kolom untuk tracking stock produk
ALTER TABLE `products` 
ADD COLUMN `min_stock` int(11) DEFAULT 5 COMMENT 'Minimum stock alert' AFTER `stock`;

-- Insert sample data untuk testing (optional)
-- INSERT INTO `cart` (`user_id`, `product_id`, `quantity`) VALUES 
-- (5, 10, 2),
-- (5, 12, 1);

-- INSERT INTO `booking_treatments` (`name`, `email`, `treatments_id`, `date`, `time`, `status`) VALUES 
-- ('John Doe', 'john@example.com', 2, '2025-01-15', '10:00:00', 'confirmed'),
-- ('Jane Smith', 'jane@example.com', 3, '2025-01-16', '14:00:00', 'pending');
