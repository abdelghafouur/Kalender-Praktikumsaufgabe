-- phpMyAdmin SQL Dump
-- version 5.2.1deb1ubuntu0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 14, 2024 at 01:55 PM
-- Server version: 10.11.2-MariaDB-1
-- PHP Version: 8.1.12-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Appointment`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `is_full_day` tinyint(1) NOT NULL DEFAULT 0,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `title`, `description`, `date`, `end_date`, `start_time`, `end_time`, `is_full_day`, `category`, `created_at`, `category_id`) VALUES
(42, 'Einkaufsliste erstellen', 'Beschreibung für das Erstellen einer Einkaufsliste', '2024-03-16', '2024-03-17', '18:00:00', '20:00:00', 0, NULL, '2024-03-14 13:24:43', 6),
(43, 'Fußballtraining', 'Beschreibung für das Fußballtraining', '2024-03-23', '2024-03-24', '20:00:00', '21:30:00', 0, NULL, '2024-03-14 13:33:21', 4),
(44, 'Familienessen', 'Beschreibung für das gemeinsame Familienessen', '2024-03-15', NULL, '21:00:00', '22:30:00', 0, NULL, '2024-03-14 13:34:30', 5),
(45, 'Joggen im Park', 'Beschreibung für das Joggen im Park', '2024-03-16', NULL, '15:30:00', '17:00:00', 0, NULL, '2024-03-14 13:35:49', 4),
(46, 'Allgemeines Treffen', 'Beschreibung des allgemeinen Treffens', '2024-03-23', NULL, '16:00:00', '17:30:00', 0, NULL, '2024-03-14 13:37:50', 7),
(47, 'Familienausflug zum Zoo', 'Familienausflug zum Zoo', '2024-03-29', '2024-03-31', '10:00:00', '12:00:00', 0, NULL, '2024-03-14 13:39:28', 5),
(48, 'Meeting', 'Beschreibung des Meetings', '2024-03-05', '2024-03-06', NULL, NULL, 1, NULL, '2024-03-14 13:40:43', 7),
(49, 'Familienessen', 'Beschreibung für das gemeinsame Familienessen', '2024-03-08', NULL, '21:00:00', '22:30:00', 0, NULL, '2024-03-14 13:43:06', 5),
(50, 'Geburtstag', 'Beschreibung des Geburtstags', '2024-03-19', NULL, '22:30:00', '23:00:00', 0, NULL, '2024-03-14 13:45:15', 5),
(51, 'Projektbesprechung', 'Beschreibung der Projektbesprechung', '2024-02-19', '2024-02-23', NULL, NULL, 1, NULL, '2024-03-14 13:46:09', 7),
(52, 'Arzttermin', 'Beschreibung des Arzttermins', '2024-03-11', '2024-03-12', '08:00:00', '10:00:00', 0, NULL, '2024-03-14 13:47:18', 7),
(53, 'Besprechung', 'Beschreibung der Besprechung', '2024-03-27', NULL, NULL, NULL, 1, NULL, '2024-03-14 13:53:05', 7),
(54, 'Urlaubsplanung', 'Beschreibung der Urlaubsplanung', '2024-03-13', NULL, NULL, NULL, 1, NULL, '2024-03-14 13:59:28', 6);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`) VALUES
(4, 'Sport', 'khaki'),
(5, 'Familie', 'cyan'),
(6, 'Freizeit', 'gainsboro'),
(7, 'Andere', 'greenyellow');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
