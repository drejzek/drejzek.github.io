-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Sob 09. pro 2023, 17:09
-- Verze serveru: 10.1.36-MariaDB
-- Verze PHP: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `max-play`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `file_id`
--

CREATE TABLE `file_id` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_path` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT '0',
  `dislikes` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `file_id`
--

INSERT INTO `file_id` (`id`, `title`, `file_path`, `file_type`, `file_size`, `user_id`, `created_at`, `image_path`, `likes`, `dislikes`) VALUES
(43, 'Roblox history', 'file/2.mp4', 'video/mp4', 3036442, 2, '2023-11-29 01:25:43', 'default-image/video.jpg', 1, 0),
(47, 'VC installer', 'file/1.exe', 'application/octet-stream', 13837672, 1, '2023-11-29 07:57:27', 'default-image/ostatni.jpg', 0, 1),
(50, 'Avicii - Wake Me Up', 'file/5.mp4', 'video/mp4', 27546598, 2, '2023-11-30 00:19:11', 'default-image/video.jpg', 1, 0),
(51, 'Olga', 'file/6.mp4', 'video/mp4', 29157764, 2, '2023-11-30 00:53:05', 'default-image/video.jpg', 1, 0),
(59, 'Fallout 76', 'file/1.mp4', 'video/mp4', 4708525, 5, '2023-12-03 00:18:19', 'default-image/video.jpg', 1, 0),
(60, 'Alvaro Soler', 'file/3.mp4', 'video/mp4', 91045470, 5, '2023-12-03 02:17:20', 'default-image/video.jpg', 2, 0),
(61, 'NCS', 'file/4.mp4', 'video/mp4', 94189184, 2, '2023-12-04 01:31:39', 'default-image/video.jpg', 1, 0),
(63, 'Tedss', 'file/7.mp4', 'video/mp4', 4708525, 2, '2023-12-08 06:37:35', 'default-image/video.jpg', 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `file_votes`
--

CREATE TABLE `file_votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  `like_status` tinyint(4) DEFAULT '0',
  `dislike_status` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `file_votes`
--

INSERT INTO `file_votes` (`id`, `user_id`, `file_id`, `like_status`, `dislike_status`) VALUES
(8, 2, 50, 1, 0),
(9, 1, 47, 0, 1),
(10, 5, 59, 1, 0),
(11, 2, 51, 1, 0),
(12, 5, 60, 1, 0),
(13, 2, 43, 1, 0),
(19, 2, 61, 1, 0),
(20, 2, 63, 1, 0),
(21, 1, 61, NULL, 0),
(22, 2, 60, 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'test', '$2y$10$whXTy320zMnXXmXOesY.fOSIIyO1wZ73AnJpcalbdUSg0/1VIgCYm'),
(2, 'mafie', '$2y$10$hjRIoN50/ieA7aqG9aTTfO4ljjJcn02J6/1ziNV.LvVwy/afhPD1K'),
(3, 'Filip', '$2y$10$8gfxEpYait7Fq9tfi8vCOuP.rzdGxdWrM/PhO7qCXnZiQ2.JBUY2G'),
(4, 'SliestBull58637', '$2y$10$Fw4O9HnxGV.EhsG2MO4.c.sJc7crfW2Y..B3iVLhj/dKML2L1r1aO'),
(5, 'Tedy', '$2y$10$TQ9qG4ZsvMZSpwkjdCE8hOrCmBWPMvWzrmoNY055z9UwHR0NYFPQC'),
(6, 'Rebou', '$2y$10$V7ypSsG./dmnH2Ac9kA4KOJE20.IAHrYBYP.Ht8/mHx24ZdZbihA6');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `file_id`
--
ALTER TABLE `file_id`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Klíče pro tabulku `file_votes`
--
ALTER TABLE `file_votes`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `file_id`
--
ALTER TABLE `file_id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT pro tabulku `file_votes`
--
ALTER TABLE `file_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `file_id`
--
ALTER TABLE `file_id`
  ADD CONSTRAINT `file_id_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
