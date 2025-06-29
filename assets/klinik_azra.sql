-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Jan 2025 pada 08.23
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klinik_azra`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `account`
--

CREATE TABLE `account` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `account`
--

INSERT INTO `account` (`id`, `username`, `password`, `role`) VALUES
(5, 'jidan', '$2y$10$BfpMR1CHKTWQZh0.omWvyuaJ8wg63En18Qq0sef2sr8Jy9YPhmkZu', 'admin'),
(6, 'adit', '$2y$10$7Hf9QBNGuIKzWwWUoKgc5OfRfe6Bq9dN/OS/nerjwGYlV0nNLB5jC', 'user');

--
-- Trigger `account`
--
DELIMITER $$
CREATE TRIGGER `check_role` BEFORE INSERT ON `account` FOR EACH ROW BEGIN
  IF NEW.role NOT IN ('admin', 'user') THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Invalid role value.';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `home_content`
--

CREATE TABLE `home_content` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `home_content`
--

INSERT INTO `home_content` (`id`, `title`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Perawatan Wajah Terbaru!', 'Kami menghadirkan perawatan wajah terbaru dengan teknologi mutakhir untuk kulit sehat dan bercahaya.', 'bg_2.jpg', '2024-12-26 01:28:35', '2024-12-26 01:43:45'),
(2, 'Jadwal Operasi Klinik', 'Klinik kami buka setiap hari Senin-Sabtu, pukul 08.00-20.00 WIB. Minggu dan hari libur nasional tutup.', 'Screenshot 2024-04-26 085758.png', '2024-12-27 01:15:15', '2024-12-27 01:15:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `promo_id` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `image`, `created_at`, `promo_id`, `category`) VALUES
(10, 'Sun Screen', '70000.00', 65, 'sunscreen-azarine-50.jpg', '2025-01-06 13:53:40', NULL, 'skincare'),
(12, 'Bedak Bercula 1', '20000.00', 3, 'hair-treatment.jpg', '2025-01-11 12:26:20', NULL, 'Kosmetiq'),
(13, 'Lemon Penyegar', '8000.00', 25, 'bg-jeruk.jpg', '2025-01-11 14:05:38', NULL, 'Herbal'),
(14, 'Maybelline x ITZY', '200000.00', 10, 'download (9).jpeg', '2025-01-13 06:17:04', NULL, 'Kosmetik'),
(15, 'Maybelline x ITZYsssss', '300000.00', 55, 'download (9).jpeg', '2025-01-13 06:41:18', NULL, 'KosmetikNormal');

-- --------------------------------------------------------

--
-- Struktur dari tabel `promos`
--

CREATE TABLE `promos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `valid_until` date DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `promos`
--

INSERT INTO `promos` (`id`, `title`, `description`, `valid_until`, `discount`, `image`, `created_at`, `updated_at`, `category`) VALUES
(1, 'loreal', 'sdfdsd', '2025-01-22', 25, 'images (4).jpeg', '2025-01-13 12:29:10', '2025-01-13 12:49:10', 'Kosmetik'),
(6, 'tes 7', '                    88', '2025-01-08', 77, 'bg-lip.jpg', '2024-12-26 14:51:27', '2025-01-06 19:20:55', NULL),
(8, 'Lentern\'s Rite', 'Glow Up sebelum ketemu cece', '2025-02-28', 60, 'bg-jeruk.jpg', '2025-01-06 19:01:02', '2025-01-12 20:59:22', 'shave'),
(9, 'Special New Year', 'New Year New Me!', '2025-01-31', 55, 'newyear.jpg', '2025-01-06 20:01:55', '2025-01-11 21:20:33', 'skincare'),
(10, 'Spesial Lebaran', 'Dapatkan penawaran yang menarik!', '2025-01-31', 60, 'bg-jeruk.jpg', '2025-01-09 16:10:46', '2025-01-11 21:20:22', 'herbal'),
(11, 'Tes1', '                        Tes1', '2025-02-01', 20, 'bg-abstract.jpg', '2025-01-11 19:19:38', '2025-01-11 20:01:47', 'Kosmetiq'),
(12, 'dssd', 'sddsc', '2025-01-16', 20, 'download (9).jpeg', '2025-01-13 13:35:06', NULL, 'Kosmetik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `treatments`
--

CREATE TABLE `treatments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `treatments`
--

INSERT INTO `treatments` (`id`, `name`, `description`, `price`, `image`, `created_at`, `updated_at`, `category`) VALUES
(2, 'Hair Treatment', 'Merawat & mewarnai rambut', '2000000.00', 'bg-jeruk.jpg', '2024-12-24 22:22:00', '2024-12-24 22:32:36', NULL),
(3, 'Haircut', 'Rambut gadis itu seperti berlian hitam yang jatuh ke bahunya. Bersinar di bawah sinar matahari dengan cahaya hangat.', '150000.00', 'makeup.jpg', '2024-12-25 09:15:04', '2025-01-12 06:58:34', 'Shave'),
(4, 'Lasik', 'iii', '1234.00', 'pexels-pixabay-356040.jpg', '2024-12-26 00:52:31', '2025-01-11 07:44:41', NULL),
(5, 'Makeup Wedding', 'Pernikahan hanya sekali, tampil cantik maksimal dengan makeup dari kami!', '3500000.00', 'hair-treatment.jpg', '2025-01-06 06:18:24', '2025-01-12 06:24:55', 'kosmetiq'),
(7, 'xxx', 'xxx', '1111.00', 'bg-abstract.jpg', '2025-01-08 02:00:06', '2025-01-08 02:00:06', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) DEFAULT 'user' CHECK (`role` in ('admin','user')),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(3, 'adit', '$2y$10$MMqoToei5Q1U41ozkaafGesv9iSHJBS2mgZp.RZ7FAVgR/STm8u1q', 'admin', '2024-12-12 01:02:43', '2024-12-12 01:02:43'),
(5, 'jidan', '$2y$10$DajL.PtzX3bp6L09ptaGQOwo.u.yZ8XNproWntFZ62z4dGkzEWiEK', 'user', '2024-12-12 02:37:21', '2024-12-12 02:37:21');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `home_content`
--
ALTER TABLE `home_content`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `promos`
--
ALTER TABLE `promos`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `account`
--
ALTER TABLE `account`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `home_content`
--
ALTER TABLE `home_content`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `promos`
--
ALTER TABLE `promos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking_treatments`
--

CREATE TABLE `booking_treatments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `treatments_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','success','failed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_type` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `booking_treatments`
--
ALTER TABLE `booking_treatments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treatments_id` (`treatments_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `booking_treatments`
--
ALTER TABLE `booking_treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `booking_treatments`
--
ALTER TABLE `booking_treatments`
  ADD CONSTRAINT `booking_treatments_ibfk_1` FOREIGN KEY (`treatments_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
