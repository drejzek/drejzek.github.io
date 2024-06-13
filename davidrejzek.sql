-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: db.dw128.webglobe.com
-- Vytvořeno: Sob 16. bře 2024, 14:46
-- Verze serveru: 10.5.24-MariaDB-1:10.5.24+maria~ubu2004-log
-- Verze PHP: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `davidrejzek`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `file_id`
--

CREATE TABLE `file_id` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `access_key` varchar(64) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ViewFile` tinyint(1) NOT NULL DEFAULT 1,
  `hideView` tinyint(1) NOT NULL DEFAULT 0,
  `passRequired` tinyint(1) NOT NULL DEFAULT 0,
  `pass` varchar(255) DEFAULT NULL,
  `privacy` int(11) NOT NULL DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `dislikes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Vypisuji data pro tabulku `file_id`
--

INSERT INTO `file_id` (`id`, `title`, `access_key`, `file_path`, `file_type`, `file_size`, `user_id`, `created_at`, `ViewFile`, `hideView`, `passRequired`, `pass`, `privacy`, `image_path`, `likes`, `dislikes`) VALUES
(1, '', 'd96dee13ebf010abb40b82e96be511f0', 'file/1.jpeg', 'image/jpeg', 5016942, 1, '2024-01-02 17:55:51', 0, 0, 0, 'd41d8cd98f00b204e9800998ecf8427e', 2, 'images/1_1704218151.jpeg', 0, 0),
(5, 'Mudkip (Pokémon)', '9491f1f067408f54a52433ff25d71029', 'images/4_1708870266.png', 'image/png', 137423, 4, '2024-02-25 14:11:19', 1, 0, 0, 'd41d8cd98f00b204e9800998ecf8427e', 0, 'images/4_1708870266.png', 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `file_votes`
--

CREATE TABLE `file_votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  `like_status` tinyint(4) DEFAULT 0,
  `dislike_status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
(22, 2, 60, 1, 0),
(23, 4, 63, NULL, 0),
(24, 1, 74, 0, 0),
(25, 1, 75, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `aid` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `folder_name` varchar(16) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_md5` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `aid`, `name`, `username`, `email`, `verified`, `folder_name`, `password`, `password_md5`) VALUES
(1, '', 'TEST', 'test', 'test@test.cz', 0, '', '$2y$10$whXTy320zMnXXmXOesY.fOSIIyO1wZ73AnJpcalbdUSg0/1VIgCYm', NULL),
(4, '172522ec1028ab781d9dfd17eaca4427', 'David Rejzek', 'david.rejzek', 'info@davidrejzek.cz', 1, 'david', '$2y$10$Fw4O9HnxGV.EhsG2MO4.c.sJc7crfW2Y..B3iVLhj/dKML2L1r1aO', 'c88b6cfeed975c13cd7179241ec3b45c'),
(10, '', 'kubabbbb', 'kubabbbb', 'surt@max-online.cz', 0, 'ahojda', NULL, '09405349f89cb6377ede41bc28c4ce29'),
(11, '79c2b46ce2594ecbcb5b73e928345492', 'Ahoj', 'ahoj', 'ahoj@ahoj', 0, 'ahoj', NULL, '79c2b46ce2594ecbcb5b73e928345492');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `file_id`
--
ALTER TABLE `file_id`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pro tabulku `file_votes`
--
ALTER TABLE `file_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `users`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pro tabulku `file_votes`
--
ALTER TABLE `file_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
