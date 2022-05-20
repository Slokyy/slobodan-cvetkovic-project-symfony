-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 20, 2022 at 07:42 AM
-- Server version: 5.7.31
-- PHP Version: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `symfony_project_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_acc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `avatar_path`, `avatar_alt`, `email`, `payment_method`, `bank_acc`) VALUES
(1, 'Infostud', 'index-6282baa5f0102.png', 'Infostud', 'infostud@infostud.com', 'Visa', '9823498294829384'),
(2, 'ConcordSoft', 'concord-6282bab783c5e.jpg', 'ConcordSoft', 'concordsoft@mail.com', 'Paypal', '593045903495304'),
(4, 'Polovni automobili', 'polovniautomobili-6282bae413517.png', 'Polovni automobili', 'polovni@polovniautomobili.com', 'Cash', '09320492034902'),
(5, 'Najstudent', 'najstudent-6282b9a62cb0e.png', 'Najstudent', 'menadzer@najstudent.com', 'Cash', '0394029402394'),
(7, 'Coca Cola', 'coca-cola-6284aaf937c41.png', 'Coca Cola', 'cocacola@mail.com', 'VISA', '309420492304902');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220510090957', '2022-05-10 09:10:24', 855),
('DoctrineMigrations\\Version20220515122116', '2022-05-15 12:21:47', 1695),
('DoctrineMigrations\\Version20220518080903', '2022-05-18 08:09:21', 648),
('DoctrineMigrations\\Version20220518081126', '2022-05-18 08:11:31', 366),
('DoctrineMigrations\\Version20220518081414', '2022-05-18 08:14:20', 353);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_acc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `street`, `city`, `country`, `status`, `bank_acc`, `avatar_path`, `avatar_alt`) VALUES
(1, 'slobodancvetkovic@mail.com', '[\"ROLE_DEVELOPER\"]', '$2y$13$TYHWQKu4949PtjLtgVqJaunS0EOs0GZ90KaTffKiGMWYltRFKZYxe', 'Slobodan', 'Cvetkovic', 'Hrastova', 'Subotica', 'Srbija', 'ACTIVE', '4292953884392849238429348', 'profile-guy-four-6282ad6052c0b.png', 'Slobodan Cvetković'),
(3, 'ninanikolic@mail.com', '[\"ROLE_DEVELOPER\"]', '$2y$13$k74191bicqMAlyfWTuzUtucxRWsz1xRgt8RUDNck8/Z5cHXwf5IwS', 'Nina', 'Nikolic', 'Aleja Marsala Tita', 'Nis', 'Srbija', 'ACTIVE', '34234234234234', 'christie-campbell-6282ad2779c45.jpg', 'Nina Nikolic'),
(4, 'martinamarkovic@mail.com', '[\"ROLE_DEVELOPER\"]', '$2y$13$j5dFRLqukBQPKeBKX947GOAJmX.jviivtGPhMvEqdKvxSpwun2Ivi', 'Martina', 'Markovic', 'Jovan Mikic', 'Beograd', 'Srbija', 'ACTIVE', '34434224678', 'jake-nackos-6282ad982017a.jpg', 'Martina Markovic'),
(6, 'markopolo@mail.com', '[\"ROLE_DEVELOPER\"]', '$2y$13$syp1HMA0P/icQbMh2WOig.G3oNRHvRW2DB7wQACZj5M31Ci4EOrkG', 'Marko', 'Polo', 'Segedinski Put', 'Subotica', 'Srbija', 'INACTIVE', '420420420420', 'profile-guy-three-6282adb42e133.png', 'Marko Polo'),
(7, 'anastantic@mail.com', '[\"ROLE_DEVELOPER\"]', '$2y$13$g32sp7zhosj2TWRX64oPtO6Sr.BZAgtNTXTZq6vvrOVybxqNf02pS', 'Ana', 'Stantic', 'Knez Mihajlova', 'Beograd', 'Srbija', 'ACTIVE', '974894522184987', 'profile-woman-6282add0e0680.png', 'Ana Stantic'),
(8, 'lukalukovic@mail.com', '[\"ROLE_DEVELOPER\"]', '$2y$13$FKMDIecFTLoG.q/8umJAyOK5qqE1E6DfN6tU9ZjPFONUPcXKX9Rie', 'Luka', 'Lukovic', 'Lazarova', 'Kraljevo', 'Srbija', 'ACTIVE', '89754652187624897', 'profile-guy-six-6282adec36e1f.png', 'Luka Lukovic'),
(9, 'brankabrankovic@mail.com', '[\"ROLE_ADMIN\"]', '$2y$13$rPDP2L7T3HJwzvco8kc0sO3ktDnK2wDZfgXAgsR2u.tuy6MWJMUMu', 'Branka', 'Brankovic', 'Beogradski put', 'Beograd', 'Srbija', 'ACTIVE', '987654321654897', 'vicky-hladynets-6282ae083bfce.jpg', 'Branka Brankovic'),
(12, 'jovanajovanovic@mail.com', '[\"ROLE_DEVELOPER\"]', '$2y$13$cKYjqDDLzmLSyrB6kBtdXuUJLwCpdnS490XcgJ4EzfIodyR2CUh8a', 'Jovana', 'Jovanovic', 'Jovan Cvijic', 'Novi Sad', 'Srbija', 'INACTIVE', '42374827489234', 'stephanie-liverani-6282ae4949d72.jpg', 'Jovana Jovanovic'),
(13, 'admin@mail.com', '[\"ROLE_ADMIN\"]', '$2y$13$RQ5sdTQUQuFVKvyy.tSmJOXE.SSV1Co4.A5QAOObottiCu7Wnd8Fy', 'Admin', 'Admin', 'adminstreet 42', 'Adminsville', 'Srbija', 'ACTIVE', '3425442342336345234', 'stefan-stevic-6282ad7f9223a.jpg', 'Admin Admin'),
(14, 'nebojsamarkovic@mail.com', '[\"ROLE_DEVELOPER\"]', '$2y$13$XtVoQmQb2G.pSwFZe0rXdetxusZTZesc2YGWNwnpzgG6FUSOD7bOO', 'Nebojsa', 'Marković', 'Beogradski put', 'Subotica', 'Srbija', 'ACTIVE', '420420420420420420', 'stefan-stevic-62849282c2d91.jpg', 'Nebojsa Marković');

-- --------------------------------------------------------

--
-- Table structure for table `user_client`
--

DROP TABLE IF EXISTS `user_client`;
CREATE TABLE IF NOT EXISTS `user_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `month` datetime NOT NULL,
  `time` time NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A2161F68A76ED395` (`user_id`),
  KEY `IDX_A2161F6819EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_client`
--

INSERT INTO `user_client` (`id`, `user_id`, `client_id`, `month`, `time`, `description`) VALUES
(1, 1, 2, '2022-05-18 00:00:00', '04:00:00', 'Poslednji zadatak za Concord'),
(2, 1, 1, '2022-05-15 12:22:03', '08:00:00', 'Obuka'),
(4, 1, 1, '2022-05-16 15:13:09', '08:00:00', 'Predavanje'),
(5, 4, 1, '2022-06-08 12:16:14', '12:00:00', 'blalblalklaskdlakd'),
(7, 1, 1, '2022-05-18 13:48:02', '04:00:00', 'SOme random description'),
(9, 6, 1, '2022-05-05 00:00:00', '08:00:00', 'sdawdawdawd'),
(10, 6, 1, '2022-05-05 00:00:00', '19:50:00', 'Something random'),
(11, 6, 1, '2022-01-01 00:00:00', '02:48:00', 'Something random'),
(12, 6, 1, '2022-01-01 00:00:00', '00:59:00', 'sdawdawdawdaw'),
(13, 6, 1, '2022-01-01 00:00:00', '09:55:00', 'dawdawdawdawd');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_client`
--
ALTER TABLE `user_client`
  ADD CONSTRAINT `FK_A2161F6819EB6921` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A2161F68A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
