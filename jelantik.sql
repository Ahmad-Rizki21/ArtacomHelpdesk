-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2025 at 04:04 PM
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
-- Database: `jelantik`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'updated', 'App\\Models\\User', 'updated', 4, 'App\\Models\\User', 4, '{\"attributes\":{\"name\":\"NOC-Dani\",\"email\":\"dani@noc.com\"},\"old\":{\"name\":\"NOC-Dani\",\"email\":\"dani@noc.com\"}}', NULL, '2025-02-09 15:11:42', '2025-02-09 15:11:42'),
(2, 'default', 'updated', 'App\\Models\\User', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"name\":\"admin\",\"email\":\"admin@helpdesk.net\"},\"old\":{\"name\":\"admin\",\"email\":\"admin@helpdesk.net\"}}', NULL, '2025-02-10 04:00:42', '2025-02-10 04:00:42'),
(3, 'default', 'updated', 'App\\Models\\User', 'updated', 4, 'App\\Models\\User', 4, '{\"attributes\":{\"name\":\"NOC-Dani\",\"email\":\"dani@noc.com\"},\"old\":{\"name\":\"NOC-Dani\",\"email\":\"dani@noc.com\"}}', NULL, '2025-02-10 04:27:06', '2025-02-10 04:27:06'),
(4, 'default', 'updated', 'App\\Models\\User', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"name\":\"admin\",\"email\":\"admin@helpdesk.net\"},\"old\":{\"name\":\"admin\",\"email\":\"admin@helpdesk.net\"}}', NULL, '2025-02-10 04:54:09', '2025-02-10 04:54:09'),
(5, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{\"attributes\":{\"name\":\"Customer\",\"email\":\"customer@example.com\"}}', NULL, '2025-02-10 06:32:01', '2025-02-10 06:32:01'),
(6, 'default', 'updated', 'App\\Models\\User', 'updated', 4, 'App\\Models\\User', 4, '{\"attributes\":{\"name\":\"NOC-Dani\",\"email\":\"dani@noc.com\"},\"old\":{\"name\":\"NOC-Dani\",\"email\":\"dani@noc.com\"}}', NULL, '2025-02-10 06:32:36', '2025-02-10 06:32:36'),
(7, 'default', 'updated', 'App\\Models\\User', 'updated', 4, 'App\\Models\\User', 4, '{\"attributes\":{\"name\":\"NOC-Dani\",\"email\":\"dani@noc.com\"},\"old\":{\"name\":\"NOC-Dani\",\"email\":\"dani@noc.com\"}}', NULL, '2025-02-10 08:56:15', '2025-02-10 08:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `backbone_cids`
--

CREATE TABLE `backbone_cids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cid` varchar(255) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `jenis_isp` enum('INDIBIZ','ASTINET','ICON PLUS','FIBERNET') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `backbone_cids`
--

INSERT INTO `backbone_cids` (`id`, `cid`, `lokasi`, `jenis_isp`, `created_at`, `updated_at`) VALUES
(2, '111401004200 (Pulogebang Tower)', 'Rusunawa Pulogebang Tower', 'ICON PLUS', '2025-02-03 08:24:34', '2025-02-09 14:11:55'),
(3, '111401004201 (Pinus Elok Blok A)', 'Rusunawa Pinus Elok Blok A', 'ICON PLUS', '2025-02-03 08:25:36', '2025-02-09 14:12:19'),
(4, '111401004202 (Tipar Cakung)', 'Rusunawa Tipar Cakung', 'ICON PLUS', '2025-02-03 08:26:20', '2025-02-09 14:12:44'),
(5, '1965743220 (Nagrak Tower 5)', 'Rusunawa Nagrak Tower 5 / Biru', 'ASTINET', '2025-02-03 08:27:28', '2025-02-09 14:13:05'),
(6, 'IS18A114 (Nagrak Tower 5)', 'Rusunawa Nagrak Tower 5 / Biru', 'FIBERNET', '2025-02-03 08:28:08', '2025-02-09 14:13:22'),
(7, '122118291627 (Tipar Cakung)', 'Rusunawa Tipar Cakung', 'INDIBIZ', '2025-02-03 08:29:11', '2025-02-09 14:13:39'),
(8, '122121219832 (Pinus Elok Blok A)', 'Rusunawa Pinus Elok Blok A', 'INDIBIZ', '2025-02-03 08:30:36', '2025-02-09 14:14:02'),
(9, '111401007214 (Kompas Tambun)', 'Perumahan Kompas Tambun', 'ICON PLUS', '2025-02-03 08:32:13', '2025-02-09 14:14:17'),
(10, '111401008005 (Parama Serang)', 'Perumahan Parama Serang - Banten', 'ICON PLUS', '2025-02-03 08:33:48', '2025-02-09 14:14:35'),
(11, '111401008541 (Waringin Kurung)', 'Perumahan Waringin Kurung Serang - Banten', 'ICON PLUS', '2025-02-03 08:35:32', '2025-02-09 14:14:57');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1739259461),
('356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1739259461;', 1739259461),
('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:2;', 1739258961),
('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1739258961;', 1739258961),
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}', 1739345312);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `composite_data` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `customer_id`, `ip_address`, `service`, `composite_data`, `created_at`, `updated_at`, `no`) VALUES
(1, 'Ika Kurniawati', '62897292837335', '101.101.100.80', 'ISP-JAKINET', 'Ika Kurniawati - 62897292837335 - 101.101.100.80', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 2),
(2, 'Howiyah Elinda', '628191158585992', '101.101.100.82', 'ISP-JAKINET', 'Howiyah Elinda - 628191158585992 - 101.101.100.82', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 3),
(3, 'Joko Haryono', '628211122266213', '101.101.100.83', 'ISP-JAKINET', 'Joko Haryono - 628211122266213 - 101.101.100.83', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 4),
(4, 'Irna Niscahyati', '628577793699091', '101.101.100.84', 'ISP-JAKINET', 'Irna Niscahyati - 628577793699091 - 101.101.100.84', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 5),
(5, 'Yusuf Efendi', '628588354597392', '101.101.100.87', 'ISP-JAKINET', 'Yusuf Efendi - 628588354597392 - 101.101.100.87', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 6),
(6, 'Erik', '628131912918754', '101.101.100.88', 'ISP-JAKINET', 'Erik - 628131912918754 - 101.101.100.88', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 7),
(7, 'Yudha Rizky Alvingky', '628821478709185', '101.101.100.98', 'ISP-JAKINET', 'Yudha Rizky Alvingky - 628821478709185 - 101.101.100.98', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 8),
(8, 'Charles Daniel Christian', 'CKG-KM2-A-413-20-0623-Christian', '101.101.100.102', 'ISP-JAKINET', 'Charles Daniel Christian - CKG-KM2-A-413-20-0623-Christian - 101.101.100.102', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 9),
(9, 'Julianus', 'CKG-KM2-A-114-20-0623-Julianus', '101.101.100.107', 'ISP-JAKINET', 'Julianus - CKG-KM2-A-114-20-0623-Julianus - 101.101.100.107', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 10),
(10, 'Ahmad Hazami', '628577730222339', '101.101.100.6', 'ISP-JAKINET', 'Ahmad Hazami - 628577730222339 - 101.101.100.6', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 11),
(11, 'Faradiba Marsaoly', '628127925022665', '101.101.100.112', 'ISP-JAKINET', 'Faradiba Marsaoly - 628127925022665 - 101.101.100.112', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 12),
(12, 'Ruslan', 'CKM-KM2-B-101-30-1223-RUSLAN', '101.101.100.149', 'ISP-JAKINET', 'Ruslan - CKM-KM2-B-101-30-1223-RUSLAN - 101.101.100.149', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 13),
(13, 'Ahmad Maulana', 'CKM-KM2-B-515-30-0124-ahmadmaulana', '101.101.100.158', 'ISP-JAKINET', 'Ahmad Maulana - CKM-KM2-B-515-30-0124-ahmadmaulana - 101.101.100.158', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 14),
(14, 'Rifa Bayu Zulfanida', 'CKM-KM2-A-419-30-0124-Rifa', '101,101,100,160', 'ISP-JAKINET', 'Rifa Bayu Zulfanida - CKM-KM2-A-419-30-0124-Rifa - 101101100160', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 15),
(15, 'Sakinah', 'CKM-KM2-A-124-30-0224-Sakinah', '101.101.100.168', 'ISP-JAKINET', 'Sakinah - CKM-KM2-A-124-30-0224-Sakinah - 101.101.100.168', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 16),
(16, 'Dhanu Irwansyah', 'CKG-KM2-B-114-20-0324-Dhanu', '101.101.100.173', 'ISP-JAKINET', 'Dhanu Irwansyah - CKG-KM2-B-114-20-0324-Dhanu - 101.101.100.173', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 17),
(17, 'Arnando Amos', 'CKG-KM2-10-0324-Arnando', '101.101.100.187', 'ISP-JAKINET', 'Arnando Amos - CKG-KM2-10-0324-Arnando - 101.101.100.187', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 18),
(18, 'Aida', 'CKG-KM2-A-408-10-0524-Aida', '101.101.100.197', 'ISP-JAKINET', 'Aida - CKG-KM2-A-408-10-0524-Aida - 101.101.100.197', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 19),
(19, 'Vian', 'CKG-KM2-B-411-20-0042-Vian', '101.101.100.214', 'ISP-JAKINET', 'Vian - CKG-KM2-B-411-20-0042-Vian - 101.101.100.214', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 20),
(20, 'Reika Nilam Macesa', 'CKG-KM2-B-203-Reika', '101.101.100.227', 'ISP-JAKINET', 'Reika Nilam Macesa - CKG-KM2-B-203-Reika - 101.101.100.227', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 21),
(21, 'Diya Linawati', 'CKG-KM2-B-213-Diya', '101.101.100.226', 'ISP-JAKINET', 'Diya Linawati - CKG-KM2-B-213-Diya - 101.101.100.226', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 22),
(22, 'Nahrawi', 'CKG-KM2-B-305-Nahrawi', '101.101.100.225', 'ISP-JAKINET', 'Nahrawi - CKG-KM2-B-305-Nahrawi - 101.101.100.225', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 23),
(23, 'Rhaditya Maulana C', 'CKG-KM2-B-503-Rhaditya', '101.101.100.230', 'ISP-JAKINET', 'Rhaditya Maulana C - CKG-KM2-B-503-Rhaditya - 101.101.100.230', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 24),
(24, 'Natalia', 'CKG-KM2-B-20-25-Natalia', '101.101.100.231', 'ISP-JAKINET', 'Natalia - CKG-KM2-B-20-25-Natalia - 101.101.100.231', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 25),
(25, 'Puput Setiyawati', 'CKG-KM2-A-423-Puput', '101.101.100.19', 'ISP-JAKINET', 'Puput Setiyawati - CKG-KM2-A-423-Puput - 101.101.100.19', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 26),
(26, 'Syahroni', '628128832795653', '101.101.100.12', 'ISP-JAKINET', 'Syahroni - 628128832795653 - 101.101.100.12', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 27),
(27, 'Dahori Bin Jahir', '628131071599795', '101.101.100.11', 'ISP-JAKINET', 'Dahori Bin Jahir - 628131071599795 - 101.101.100.11', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 28),
(28, 'Nina Kurniasih', '6285817610776', '101.101.100.16', 'ISP-JAKINET', 'Nina Kurniasih - 6285817610776 - 101.101.100.16', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 29),
(29, 'Fatimus Zahro', '62878832300519', '101.101.100.15', 'ISP-JAKINET', 'Fatimus Zahro - 62878832300519 - 101.101.100.15', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 30),
(30, 'Ningsih', '628128042273447', '101.101.100.17', 'ISP-JAKINET', 'Ningsih - 628128042273447 - 101.101.100.17', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 31),
(31, 'Sukartini', '628569481637123', '101.101.100.13', 'ISP-JAKINET', 'Sukartini - 628569481637123 - 101.101.100.13', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 32),
(32, 'Mariya', 'PIN-A-A2-212-20-0623-Mariya', '101.101.100.33', 'ISP-JAKINET', 'Mariya - PIN-A-A2-212-20-0623-Mariya - 101.101.100.33', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 33),
(33, 'Rakino', '628560244516258', '101.101.100.30', 'ISP-JAKINET', 'Rakino - 628560244516258 - 101.101.100.30', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 34),
(34, 'Suroto', '628577524352042', '101.101.100.28', 'ISP-JAKINET', 'Suroto - 628577524352042 - 101.101.100.28', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 35),
(35, 'Arini', '628829883344210', '101.101.100.27', 'ISP-JAKINET', 'Arini - 628829883344210 - 101.101.100.27', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 36),
(36, 'Syaiful Anwar', '62838726279997', '101.101.100.29', 'ISP-JAKINET', 'Syaiful Anwar - 62838726279997 - 101.101.100.29', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 37),
(37, 'Rosita', '62838913881565', '101.101.100.34', 'ISP-JAKINET', 'Rosita - 62838913881565 - 101.101.100.34', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 38),
(38, 'Syarifudin', '628180839389833', '101.101.100.25', 'ISP-JAKINET', 'Syarifudin - 628180839389833 - 101.101.100.25', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 39),
(39, 'Dina Saputri', 'PIN-A-A3-317-20-0623-Saputri', '101.101.100.35', 'ISP-JAKINET', 'Dina Saputri - PIN-A-A3-317-20-0623-Saputri - 101.101.100.35', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 40),
(40, 'Suparno', '628385204816476', '101.101.100.36', 'ISP-JAKINET', 'Suparno - 628385204816476 - 101.101.100.36', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 41),
(41, 'Ismail', '628389914442226', '101.101.100.31', 'ISP-JAKINET', 'Ismail - 628389914442226 - 101.101.100.31', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 42),
(42, 'Maryadi', '628569268762622', '101.101.100.38', 'ISP-JAKINET', 'Maryadi - 628569268762622 - 101.101.100.38', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 43),
(43, 'Dwi Trisyani', '62888966951394', '101.101.100.45', 'ISP-JAKINET', 'Dwi Trisyani - 62888966951394 - 101.101.100.45', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 44),
(44, 'Diana', '628121316736823', '101.101.100.41', 'ISP-JAKINET', 'Diana - 628121316736823 - 101.101.100.41', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 45),
(45, 'Ahmad Zaeni', '628778732951049', '101.101.100.39', 'ISP-JAKINET', 'Ahmad Zaeni - 628778732951049 - 101.101.100.39', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 46),
(46, 'Sri Suharyanti', 'PIN-A-A3-318-20-0823-Suharyanti', '101.101.100.37', 'ISP-JAKINET', 'Sri Suharyanti - PIN-A-A3-318-20-0823-Suharyanti - 101.101.100.37', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 47),
(47, 'Katini', '628138548758869', '101.101.100.40', 'ISP-JAKINET', 'Katini - 628138548758869 - 101.101.100.40', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 48),
(48, 'Enung Kominah', '628788599224945', '101.101.100.43', 'ISP-JAKINET', 'Enung Kominah - 628788599224945 - 101.101.100.43', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 49),
(49, 'Ardy', 'PIN-A-A2-107-20-0623-Ardi', '101.101.100.47', 'ISP-JAKINET', 'Ardy - PIN-A-A2-107-20-0623-Ardi - 101.101.100.47', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 50),
(50, 'Rahayu', '628770917108796', '101.101.100.49', 'ISP-JAKINET', 'Rahayu - 628770917108796 - 101.101.100.49', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 51),
(51, 'Agus sulistiono', '628216025814878', '101.101.100.48', 'ISP-JAKINET', 'Agus sulistiono - 628216025814878 - 101.101.100.48', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 52),
(52, 'Cornelis Palandi', 'PIN-A-A1-316-20-0623-Palandi', '101.101.100.50', 'ISP-JAKINET', 'Cornelis Palandi - PIN-A-A1-316-20-0623-Palandi - 101.101.100.50', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 53),
(53, 'Dewi Marina', '628787218247134', '101.101.100.52', 'ISP-JAKINET', 'Dewi Marina - 628787218247134 - 101.101.100.52', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 54),
(54, 'Yunizar Abdi', '628211388300236', '101.101.100.56', 'ISP-JAKINET', 'Yunizar Abdi - 628211388300236 - 101.101.100.56', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 55),
(55, 'Fitri Utari', 'PIN-A-A2-210-20-0623-Utari', '101.101.100.59', 'ISP-JAKINET', 'Fitri Utari - PIN-A-A2-210-20-0623-Utari - 101.101.100.59', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 56),
(56, 'Cecep', '628950706170768', '101.101.100.61', 'ISP-JAKINET', 'Cecep - 628950706170768 - 101.101.100.61', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 57),
(57, 'Indra', 'PIN-A-A3-119-30-0923-Indra', '101,101,100,134', 'ISP-JAKINET', 'Indra - PIN-A-A3-119-30-0923-Indra - 101101100134', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 58),
(58, 'Dinda Komalasari', 'PIN-A-A6-514-20-0623-Dinda', '101.101.100.68', 'ISP-JAKINET', 'Dinda Komalasari - PIN-A-A6-514-20-0623-Dinda - 101.101.100.68', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 59),
(59, 'Yudi Heryadi', '628151366514779', '101.101.100.64', 'ISP-JAKINET', 'Yudi Heryadi - 628151366514779 - 101.101.100.64', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 60),
(60, 'Rame Dosmaria Samosir', '628521756288843', '101.101.100.70', 'ISP-JAKINET', 'Rame Dosmaria Samosir - 628521756288843 - 101.101.100.70', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 61),
(61, 'Lili Putri', 'PIN-A-A1-211-20-0623-Putri', '101.101.100.71', 'ISP-JAKINET', 'Lili Putri - PIN-A-A1-211-20-0623-Putri - 101.101.100.71', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 62),
(62, 'Mutri ningsih', '628577545393345', '101.101.100.74', 'ISP-JAKINET', 'Mutri ningsih - 628577545393345 - 101.101.100.74', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 63),
(63, 'Dewi Yurnita', 'PIN-A-A4-204-20-0623-Yurnita', '101.101.100.75', 'ISP-JAKINET', 'Dewi Yurnita - PIN-A-A4-204-20-0623-Yurnita - 101.101.100.75', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 64),
(64, 'Fajar Aprilyansyah', '628829357682671', '101.101.100.76', 'ISP-JAKINET', 'Fajar Aprilyansyah - 628829357682671 - 101.101.100.76', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 65),
(65, 'Riyanti', 'PIN-A-A2-113-10-1223-Riyanti', '101,101,100,154', 'ISP-JAKINET', 'Riyanti - PIN-A-A2-113-10-1223-Riyanti - 101101100154', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 66),
(66, 'Dody Setiawan', '628121138319435', '101.101.100.91', 'ISP-JAKINET', 'Dody Setiawan - 628121138319435 - 101.101.100.91', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 67),
(67, 'Ahmad Abdul Azid', '628180675810136', '101.101.100.93', 'ISP-JAKINET', 'Ahmad Abdul Azid - 628180675810136 - 101.101.100.93', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 68),
(68, 'Rusli', 'PIN-A-A1-219-10-0624-Rusli', '101,101,100,218', 'ISP-JAKINET', 'Rusli - PIN-A-A1-219-10-0624-Rusli - 101101100218', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 69),
(69, 'Debora Fransiska', 'PIN-A-A4-109-20-0623-Debora', '101,101,100,100', 'ISP-JAKINET', 'Debora Fransiska - PIN-A-A4-109-20-0623-Debora - 101101100100', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 70),
(70, 'Salamah', '62889211244863', '101,101,100,101', 'ISP-JAKINET', 'Salamah - 62889211244863 - 101101100101', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 71),
(71, 'Afiliani Agustin', '628131618608728', '101,101,100,103', 'ISP-JAKINET', 'Afiliani Agustin - 628131618608728 - 101101100103', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 72),
(72, 'Renol Biller Simatupang', 'PIN-A-A2-505-20-0623-Simatupang', '101,101,100,104', 'ISP-JAKINET', 'Renol Biller Simatupang - PIN-A-A2-505-20-0623-Simatupang - 101101100104', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 73),
(73, 'Ade Noviana', 'PIN-A-A3-315-30-0923-Noviana', '101,101,100,105', 'ISP-JAKINET', 'Ade Noviana - PIN-A-A3-315-30-0923-Noviana - 101101100105', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 74),
(74, 'Yuylita', 'PIN-A-A2-416-20-0623-Purnama', '101,101,100,106', 'ISP-JAKINET', 'Yuylita - PIN-A-A2-416-20-0623-Purnama - 101101100106', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 75),
(75, 'Farida', 'PIN-A-A1-215-20-0623-Farida', '101,101,100,108', 'ISP-JAKINET', 'Farida - PIN-A-A1-215-20-0623-Farida - 101101100108', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 76),
(76, 'Dwi Mulyoko', '628121926213927', '101.101.100.8', 'ISP-JAKINET', 'Dwi Mulyoko - 628121926213927 - 101.101.100.8', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 77),
(77, 'Ade Kurniawan', 'PIN-A-A1-409-20-0623-Kurniawan', '101.101.100.4', 'ISP-JAKINET', 'Ade Kurniawan - PIN-A-A1-409-20-0623-Kurniawan - 101.101.100.4', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 78),
(78, 'Firman Nulloh', '62898197776652', '101.101.100.32', 'ISP-JAKINET', 'Firman Nulloh - 62898197776652 - 101.101.100.32', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 79),
(79, 'Juhendi', '628131866119743', '101,101,100,110', 'ISP-JAKINET', 'Juhendi - 628131866119743 - 101101100110', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 80),
(80, 'Nur Alifah', '628953269035627', '101,101,100,111', 'ISP-JAKINET', 'Nur Alifah - 628953269035627 - 101101100111', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 81),
(81, 'Sulyawati', '628191515122416', '101,101,100,113', 'ISP-JAKINET', 'Sulyawati - 628191515122416 - 101101100113', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 82),
(82, 'Edy Irawan', '628389256321996', '101,101,100,114', 'ISP-JAKINET', 'Edy Irawan - 628389256321996 - 101101100114', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 83),
(83, 'Heri Virdaus', '628788846899040', '101,101,100,115', 'ISP-JAKINET', 'Heri Virdaus - 628788846899040 - 101101100115', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 84),
(84, 'Siti sholikhah', '628387418782941', '101,101,100,117', 'ISP-JAKINET', 'Siti sholikhah - 628387418782941 - 101101100117', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 85),
(85, 'Sri Rusbiyati', '6287880468748', '101,101,100,116', 'ISP-JAKINET', 'Sri Rusbiyati - 6287880468748 - 101101100116', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 86),
(86, 'Santi krismayanti', '6289636316536', '101,101,100,118', 'ISP-JAKINET', 'Santi krismayanti - 6289636316536 - 101101100118', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 87),
(87, 'Tutik', 'PIN-A-A2-309-20-0623-Tutik', '101,101,100,119', 'ISP-JAKINET', 'Tutik - PIN-A-A2-309-20-0623-Tutik - 101101100119', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 88),
(88, 'Ham Gultom', 'PIN-A-A2-117-20-0623-Gultom', '101,101,100,120', 'ISP-JAKINET', 'Ham Gultom - PIN-A-A2-117-20-0623-Gultom - 101101100120', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 89),
(89, 'Bambang Suseno', 'PIN-A-A2-106-30-0723-Suseno', '101,101,100,123', 'ISP-JAKINET', 'Bambang Suseno - PIN-A-A2-106-30-0723-Suseno - 101101100123', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 90),
(90, 'Elik safitri', 'PIN-A-A3-410-20-0723-Elik', '101,101,100,124', 'ISP-JAKINET', 'Elik safitri - PIN-A-A3-410-20-0723-Elik - 101101100124', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 91),
(91, 'Meielisabeth Barasa', 'PIN-A-A1-108-20-0723-Barasa', '101,101,100,125', 'ISP-JAKINET', 'Meielisabeth Barasa - PIN-A-A1-108-20-0723-Barasa - 101101100125', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 92),
(92, 'Wulandari', 'PIN-A-A1-101-30-0823-Wulandari', '101,101,100,129', 'ISP-JAKINET', 'Wulandari - PIN-A-A1-101-30-0823-Wulandari - 101101100129', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 93),
(93, 'Tiarmin Samosir', 'PIN-A-A4-120-30-0823-Tiarmin', '101,101,100,131', 'ISP-JAKINET', 'Tiarmin Samosir - PIN-A-A4-120-30-0823-Tiarmin - 101101100131', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 94),
(94, 'Fahry Fahrezi', 'PIN-A-A4-314-30-0923-Fahrezi', '101,101,100,133', 'ISP-JAKINET', 'Fahry Fahrezi - PIN-A-A4-314-30-0923-Fahrezi - 101101100133', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 95),
(95, 'ilma febriyana hayati', 'PIN-A-A2-202-20-0923-Febriyana', '101,101,100,132', 'ISP-JAKINET', 'ilma febriyana hayati - PIN-A-A2-202-20-0923-Febriyana - 101101100132', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 96),
(96, 'Taufik', 'PIN-A-A3-206-50-0923-Taufik', '101,101,100,135', 'ISP-JAKINET', 'Taufik - PIN-A-A3-206-50-0923-Taufik - 101101100135', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 97),
(97, 'Parsiah', 'PIN-A-A3-109-20-0923-Parsiah', '101,101,100,136', 'ISP-JAKINET', 'Parsiah - PIN-A-A3-109-20-0923-Parsiah - 101101100136', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 98),
(98, 'Zakaria', 'PIN-A-A4-515-20-0923-Zakaria', '101,101,100,137', 'ISP-JAKINET', 'Zakaria - PIN-A-A4-515-20-0923-Zakaria - 101101100137', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 99),
(99, 'Winarti', 'PIN-A-A1-417-10-0923-Winarti', '101,101,100,138', 'ISP-JAKINET', 'Winarti - PIN-A-A1-417-10-0923-Winarti - 101101100138', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 100),
(100, 'Ndaru Aji Sakeyti', 'PIN-A-A6-118-30-0923-NDARU', '101,101,100,140', 'ISP-JAKINET', 'Ndaru Aji Sakeyti - PIN-A-A6-118-30-0923-NDARU - 101101100140', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 101),
(101, 'Siti Ida Mahmudah', 'PIN-A-A4-305-30-1023-Mahmudah', '101,101,100,141', 'ISP-JAKINET', 'Siti Ida Mahmudah - PIN-A-A4-305-30-1023-Mahmudah - 101101100141', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 102),
(102, 'Farikhatin khasanah', 'PIN-A-A4-517-30-1023-Khasanah', '101,101,100,142', 'ISP-JAKINET', 'Farikhatin khasanah - PIN-A-A4--517-30-1023-Khasanah - 101101100142', '2025-01-21 21:59:33', '2025-01-21 23:09:42', 103),
(103, 'Anggun Ramadhania', 'PIN-A-A5-111-50-1023-Anggun', '101,101,100,143', 'ISP-JAKINET', 'Anggun Ramadhania - PIN-A-A5-111-50-1023-Anggun - 101101100143', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 104),
(104, 'Dewi Mawar Sari', 'PIN-A-A1-317-30-1123-Dewi', '101,101,100,145', 'ISP-JAKINET', 'Dewi Mawar Sari - PIN-A-A1-317-30-1123-Dewi - 101101100145', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 105),
(105, 'Hopsah', 'PIN-A6-307-Hopsah', '101.101.100.5', 'ISP-JAKINET', 'Hopsah - PIN-A6-307-Hopsah - 101.101.100.5', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 106),
(106, 'Ramdani Umri', 'PIN-A-A2-319-10-1123-Ramdani', '101.101.100.147', 'ISP-JAKINET', 'Ramdani Umri - PIN-A-A2-319-10-1123-Ramdani - 101.101.100.147', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 107),
(107, 'Anita Permatasari', 'PIN-A-A2-108-10-1123-Anita', '101.101.100.148', 'ISP-JAKINET', 'Anita Permatasari - PIN-A-A2-108-10-1123-Anita - 101.101.100.148', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 108),
(108, 'Wiwin Aritonang', 'PIN-A-A1-201-20-1223-Wiwin', '101,101,100,151', 'ISP-JAKINET', 'Wiwin Aritonang - PIN-A-A1-201-20-1223-Wiwin - 101101100151', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 109),
(109, 'Casto', 'PIN-A-A5-109-20-1223-Casto', '101,101,100,152', 'ISP-JAKINET', 'Casto - PIN-A-A5-109-20-1223-Casto - 101101100152', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 110),
(110, 'Yuliana', 'PIN-A-A2-511-20-1223-Yuliana', '101,101,100,144', 'ISP-JAKINET', 'Yuliana - PIN-A-A2-511-20-1223-Yuliana - 101101100144', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 111),
(111, 'Arka', 'PIN-A-A5-120-30-0124-Arka', '101,101,100,155', 'ISP-JAKINET', 'Arka - PIN-A-A5-120-30-0124-Arka - 101101100155', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 112),
(112, 'Alfian Nur Hasbi', 'PIN-A-A1-205-30-0124-Alfian', '101,101,100,156', 'ISP-JAKINET', 'Alfian Nur Hasbi - PIN-A-A1-205-30-0124-Alfian - 101101100156', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 113),
(113, 'Ayu Savina', 'PINA-A3-20-0124-Savina', '101,101,100,159', 'ISP-JAKINET', 'Ayu Savina - PINA-A3-20-0124-Savina - 101101100159', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 114),
(114, 'Titin Kartini', 'PIN-A-A6-101-10-0124-Titin', '101,101,100,161', 'ISP-JAKINET', 'Titin Kartini - PIN-A-A6-101-10-0124-Titin - 101101100161', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 115),
(115, 'Syamsul kholis', 'PINA-A1-116-10-0224-Syamsul', '101,101,100,162', 'ISP-JAKINET', 'Syamsul kholis - PINA-A1-116-10-0224-Syamsul - 101101100162', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 116),
(116, 'Sepriyani', 'PIN-A-A2-315-20-0224-Sepriyani', '101,101,100,163', 'ISP-JAKINET', 'Sepriyani - PIN-A-A2-315-20-0224-Sepriyani - 101101100163', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 117),
(117, 'Anita Trisnaningsih', 'PINA-A6-301-20-0224-Anita', '101,101,100,164', 'ISP-JAKINET', 'Anita Trisnaningsih - PINA-A6-301-20-0224-Anita - 101101100164', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 118),
(118, 'Karina Anggraini', 'PIN-A-A6-304-20-0224-Karina', '101.101.100.165', 'ISP-JAKINET', 'Karina Anggraini - PIN-A-A6-304-20-0224-Karina - 101.101.100.165', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 119),
(119, 'Sukmana', 'PIN-A-A6-305-10-0224-Sukmana', '101.101.100.166', 'ISP-JAKINET', 'Sukmana - PIN-A-A6-305-10-0224-Sukmana - 101.101.100.166', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 120),
(120, 'Elsa Tesalonika', 'PIN-A-A6-310-20-0224-Elsa', '101,101,100,167', 'ISP-JAKINET', 'Elsa Tesalonika - PIN-A-A6-310-20-0224-Elsa - 101101100167', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 121),
(121, 'Prihatini', 'PIN-A-A2-501-20-0224-Prihatini', '101,101,100,169', 'ISP-JAKINET', 'Prihatini - PIN-A-A2-501-20-0224-Prihatini - 101101100169', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 122),
(122, 'Yohani', 'PINA-A1-419-10-0324-Yohani', '101,101,100,171', 'ISP-JAKINET', 'Yohani - PINA-A1-419-10-0324-Yohani - 101101100171', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 123),
(123, 'Kastura', 'PINA-A1-415-10-0324-Kastura', '101,101,100,174', 'ISP-JAKINET', 'Kastura - PINA-A1-415-10-0324-Kastura - 101101100174', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 124),
(124, 'Ajie Persada', 'PIN-A-A6-416-10-0324-Ajie', '101,101,100,175', 'ISP-JAKINET', 'Ajie Persada - PIN-A-A6-416-10-0324-Ajie - 101101100175', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 125),
(125, 'Yani Sumiyati', 'PIN-A-A2-420-10-0324-Yani', '101,101,100,177', 'ISP-JAKINET', 'Yani Sumiyati - PIN-A-A2-420-10-0324-Yani - 101101100177', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 126),
(126, 'Gilang', 'PIN-A-A5-405-20-0324-Gilang', '101,101,100,178', 'ISP-JAKINET', 'Gilang - PIN-A-A5-405-20-0324-Gilang - 101101100178', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 127),
(127, 'Sakir', 'PIN-A-A1-309-20-0324-Sakir', '101,101,100,176', 'ISP-JAKINET', 'Sakir - PIN-A-A1-309-20-0324-Sakir - 101101100176', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 128),
(128, 'Nur Hasia', 'PIN-A-A5-512-20-0324-Hasia', '101.101.100.97', 'ISP-JAKINET', 'Nur Hasia - PIN-A-A5-512-20-0324-Hasia - 101.101.100.97', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 129),
(129, 'Bahriya', 'PIN-A-A2-507-20-0324-Bahriya', '101,101,100,180', 'ISP-JAKINET', 'Bahriya - PIN-A-A2-507-20-0324-Bahriya - 101101100180', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 130),
(130, 'Juli Setiawati', 'PIN-A-A4-209-10-0324-Juli', '101,101,100,182', 'ISP-JAKINET', 'Juli Setiawati - PIN-A-A4-209-10-0324-Juli - 101101100182', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 131),
(131, 'Andris Pramuji', 'PIN-A-A2-502-20-0324-Pramuji', '101,101,100,181', 'ISP-JAKINET', 'Andris Pramuji - PIN-A-A2-502-20-0324-Pramuji - 101101100181', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 132),
(132, 'Sutrisno Rais', 'PIN-A-A5-406-50-0324-Sutrisno', '101,101,100,183', 'ISP-JAKINET', 'Sutrisno Rais - PIN-A-A5-406-50-0324-Sutrisno - 101101100183', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 133),
(133, 'Yoantoro', 'PIN-A-A1-411-10-0324-Yoantoro', '101,101,100,184', 'ISP-JAKINET', 'Yoantoro - PIN-A-A1-411-10-0324-Yoantoro - 101101100184', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 134),
(134, 'Neneng kartini', 'PIN-A-A2-115-10-0324-Neneng', '101,101,100,185', 'ISP-JAKINET', 'Neneng kartini - PIN-A-A2-115-10-0324-Neneng - 101101100185', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 135),
(135, 'Nining', 'PIN-A-A1-412-10-0324-nining', '101,101,100,186', 'ISP-JAKINET', 'Nining - PIN-A-A1-412-10-0324-nining - 101101100186', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 136),
(136, 'Saipul Rohim', 'PIN-A-A6-302-30-0424-Saipul', '101,101,100,188', 'ISP-JAKINET', 'Saipul Rohim - PIN-A-A6-302-30-0424-Saipul - 101101100188', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 137),
(137, 'Ahmad Surudin', 'PIN-A2-517-10-0424-ahmad9243', '101,101,100,189', 'ISP-JAKINET', 'Ahmad Surudin - PIN-A2-517-10-0424-ahmad9243 - 101101100189', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 138),
(138, 'Rudy siallagan', 'PIN-A-A3-401-30-04-0424-Sialagan', '101,101,100,190', 'ISP-JAKINET', 'Rudy siallagan - PIN-A-A3-401-30-04-0424-Sialagan - 101101100190', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 139),
(139, 'Rini Tri Reswati', 'PIN-A-A6-211-20-0424-Reswati', '101,101,100,191', 'ISP-JAKINET', 'Rini Tri Reswati - PIN-A-A6-211-20-0424-Reswati - 101101100191', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 140),
(140, 'Muhammad Abdul Rosit', 'PIN-A-A3-405-10-0424-Abdul', '101,101,100,192', 'ISP-JAKINET', 'Muhammad Abdul Rosit - PIN-A-A3-405-10-0424-Abdul - 101101100192', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 141),
(141, 'Ramsidah', 'PIN-A-A3-107-10-0424-Ramsidah', '101,101,100,193', 'ISP-JAKINET', 'Ramsidah - PIN-A-A3-107-10-0424-Ramsidah - 101101100193', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 142),
(142, 'Rika Dwi Setiawati', 'PIN-A-A5-307-30-0524-Rika', '101,101,100,194', 'ISP-JAKINET', 'Rika Dwi Setiawati - PIN-A-A5-307-30-0524-Rika - 101101100194', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 143),
(143, 'Audina', 'PIN-A-A2-301-10-0524-Audina', '101,101,101,198', 'ISP-JAKINET', 'Audina - PIN-A-A2-301-10-0524-Audina - 101101101198', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 144),
(144, 'Saryati', 'PIN-A-A3-214-10-0524-Saryati', '101,101,100,199', 'ISP-JAKINET', 'Saryati - PIN-A-A3-214-10-0524-Saryati - 101101100199', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 145),
(145, 'Nurzainina', 'PIN-A-A1-511-10-0524-Nurzainina', '101,101,100,201', 'ISP-JAKINET', 'Nurzainina - PIN-A-A1-511-10-0524-Nurzainina - 101101100201', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 146),
(146, 'Sri Haryati', 'PIN-A-A6-312-20-0524-haryati', '101,101,100,202', 'ISP-JAKINET', 'Sri Haryati - PIN-A-A6-312-20-0524-haryati - 101101100202', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 147),
(147, 'Rena Afrilyanti', 'PIN-A-A5-315-10-0646-Rena', '101.101.100.203', 'ISP-JAKINET', 'Rena Afrilyanti - PIN-A-A5-315-10-0646-Rena - 101.101.100.203', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 148),
(148, 'Tiya', 'PIN-A-A2-304-10-0524-Tiya', '101.101.100.204', 'ISP-JAKINET', 'Tiya - PIN-A-A2-304-10-0524-Tiya - 101.101.100.204', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 149),
(149, 'Rusmiati', 'PINA-A2-203-10-3036-rusmiati', '101.101.100.205', 'ISP-JAKINET', 'Rusmiati - PINA-A2-203-10-3036-rusmiati - 101.101.100.205', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 150),
(150, 'Carli Siahaan', 'PIN-A-A6-203-50-0524-Carli', '101.101.100.206', 'ISP-JAKINET', 'Carli Siahaan - PIN-A-A6-203-50-0524-Carli - 101.101.100.206', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 151),
(151, 'Dewi Ratna Sari', 'PIN-A-A6-314-10-0524-Ratna', '101,101,100,207', 'ISP-JAKINET', 'Dewi Ratna Sari - PIN-A-A6-314-10-0524-Ratna - 101101100207', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 152),
(152, 'Sista Giri', 'PIN-A-A4-404-20-0524-Sista', '101,101,100,210', 'ISP-JAKINET', 'Sista Giri - PIN-A-A4-404-20-0524-Sista - 101101100210', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 153),
(153, 'Untung Wijaya', 'PIN-A-A6-212-10-0524-Untung', '101,101,100,211', 'ISP-JAKINET', 'Untung Wijaya - PIN-A-A6-212-10-0524-Untung - 101101100211', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 154),
(154, 'Juwati', 'PIN-A-A4-416-10-0624-Juwati', '101,101,100,212', 'ISP-JAKINET', 'Juwati - PIN-A-A4-416-10-0624-Juwati - 101101100212', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 155),
(155, 'Awaludin', 'PIN-A-A4-412-10-0624-Awaludin', '101,101,100,211', 'ISP-JAKINET', 'Awaludin - PIN-A-A4-412-10-0624-Awaludin - 101101100211', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 156),
(156, 'Najla Fathilah', 'PIN-A-A6-412-10-0624-Najla', '101,101,100,215', 'ISP-JAKINET', 'Najla Fathilah - PIN-A-A6-412-10-0624-Najla - 101101100215', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 157),
(157, 'Suroso', 'PIN-A-A1-319-10-0624-Suroso', '101,101,100,216', 'ISP-JAKINET', 'Suroso - PIN-A-A1-319-10-0624-Suroso - 101101100216', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 158),
(158, 'Dwi Maryana', 'PIN-A-A5-318-10-0624-Dwi', '101,101,100,217', 'ISP-JAKINET', 'Dwi Maryana - PIN-A-A5-318-10-0624-Dwi - 101101100217', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 159),
(159, 'Marce Susanti', 'PIN-A-A4-303-20-0524-Marce', '101,101,100,208', 'ISP-JAKINET', 'Marce Susanti - PIN-A-A4-303-20-0524-Marce - 101101100208', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 160),
(160, 'Yoheti', 'PIN-A-A3-103-20-0624-Yoheti', '101,101,100,219', 'ISP-JAKINET', 'Yoheti - PIN-A-A3-103-20-0624-Yoheti - 101101100219', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 161),
(161, 'Novi', 'PIN-A-A5-313-10-0724-Novi', '101,101,100,220', 'ISP-JAKINET', 'Novi - PIN-A-A5-313-10-0724-Novi - 101101100220', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 162),
(162, 'Evi Lisa', 'PIN-A-A3-115-10-0724-evi', '101,101,100,221', 'ISP-JAKINET', 'Evi Lisa - PIN-A-A3-115-10-0724-evi - 101101100221', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 163),
(163, 'Muh Fathurrahman', 'PIN-A5-508-Muh', '101,101,100,223', 'ISP-JAKINET', 'Muh Fathurrahman - PIN-A5-508-Muh - 101101100223', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 164),
(164, 'Trimah', 'PIN-A4-208-trimah', '101,101,100,224', 'ISP-JAKINET', 'Trimah - PIN-A4-208-trimah - 101101100224', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 165),
(165, 'Indah Kurniawati', 'PIN-A4-419-indah', '101,101,100,222', 'ISP-JAKINET', 'Indah Kurniawati - PIN-A4-419-indah - 101101100222', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 166),
(166, 'Sri Nur Hayani', 'PIN-A4-116-Sri', '101,101,100,228', 'ISP-JAKINET', 'Sri Nur Hayani - PIN-A4-116-Sri - 101101100228', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 167),
(167, 'Titin', 'PIN-A-A3-501-Titin', '101,101,100,229', 'ISP-JAKINET', 'Titin - PIN-A-A3-501-Titin - 101101100229', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 168),
(168, 'Yopi Oktavia', 'PIN-A4-107-Yopi', '101,101,100,232', 'ISP-JAKINET', 'Yopi Oktavia - PIN-A4-107-Yopi - 101101100232', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 169),
(169, 'Ristyanto', 'PIN-A2-510-Ristyanto', '101,101,100,233', 'ISP-JAKINET', 'Ristyanto - PIN-A2-510-Ristyanto - 101101100233', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 170),
(170, 'pujiyati', 'PIN-A4-214-Pujiyati', '101,101,100,247', 'ISP-JAKINET', 'pujiyati - PIN-A4-214-Pujiyati - 101101100247', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 171),
(171, 'Muh Sandi Prinoto', 'PIN-A5-404-Muh Sandi', '101,101,100,248', 'ISP-JAKINET', 'Muh Sandi Prinoto - PIN-A5-404-Muh Sandi - 101101100248', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 172),
(172, 'Suhendro', 'PIN-A2-411-Suhendro', '101,101,100,251', 'ISP-JAKINET', 'Suhendro - PIN-A2-411-Suhendro - 101101100251', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 173),
(173, 'Rukaeni', 'PIN-A5-520-Rukaeni', '101,101,100,252', 'ISP-JAKINET', 'Rukaeni - PIN-A5-520-Rukaeni - 101101100252', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 174),
(174, 'Susanti', 'PIN-A2-412-Susanti', '101,101,100,253', 'ISP-JAKINET', 'Susanti - PIN-A2-412-Susanti - 101101100253', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 175),
(175, 'Nurul kurniatun', 'PIN-A3-403-Nurul', '101,101,100,254', 'ISP-JAKINET', 'Nurul kurniatun - PIN-A3-403-Nurul - 101101100254', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 176),
(176, 'Suryadi Kurniawan', 'PIN-A2-418-Suryadi', '101,101,100,234', 'ISP-JAKINET', 'Suryadi Kurniawan - PIN-A2-418-Suryadi - 101101100234', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 177),
(177, 'Anwari Susanto', 'PIN-A2-503-Anwari', '101,101,100,235', 'ISP-JAKINET', 'Anwari Susanto - PIN-A2-503-Anwari - 101101100235', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 178),
(178, 'Landrina Bawimbang', 'PIN-A1-517-Landrina', '101,101,100,246', 'ISP-JAKINET', 'Landrina Bawimbang - PIN-A1-517-Landrina - 101101100246', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 179),
(179, 'Ziad Al Hujaili', 'PIN-A2- 320-Ziad', '101.101.100.9', 'ISP-JAKINET', 'Ziad Al Hujaili - PIN-A2- 320-Ziad - 101.101.100.9', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 180),
(180, 'Widiyanto', 'PIN-A5-416-Widiyanto', '101,101,100,237', 'ISP-JAKINET', 'Widiyanto - PIN-A5-416-Widiyanto - 101101100237', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 181),
(181, 'Vina', 'PIN-A4-420-Vina', '101,101,101,246', 'ISP-JAKINET', 'Vina - PIN-A4-420-Vina - 101101101246', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 182),
(182, 'Sari', 'PIN-A2-515-Sari', '101.101.100.7', 'ISP-JAKINET', 'Sari - PIN-A2-515-Sari - 101.101.100.7', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 183),
(183, 'Falinda', 'PIN-BLOK-A-A6-406-Falinda', '101.101.100.10', 'ISP-JAKINET', 'Falinda - PIN-BLOK-A-A6-406-Falinda - 101.101.100.10', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 184),
(184, 'Dera Wati', 'PIN-A-A2-2-211-Dera', '101.101.100.14', 'ISP-JAKINET', 'Dera Wati - PIN-A-A2-2-211-Dera - 101.101.100.14', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 185),
(185, 'Handayani', 'PIN-A2-209-Handayani', '101.101.100.18', 'ISP-JAKINET', 'Handayani - PIN-A2-209-Handayani - 101.101.100.18', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 186),
(186, 'Poniran', 'PIN-A4-408-poniran', '101.101.100.20', 'ISP-JAKINET', 'Poniran - PIN-A4-408-poniran - 101.101.100.20', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 187),
(187, 'Yanti', 'PIN-A4-104-Yanti', '101,101,100,109', 'ISP-JAKINET', 'Yanti - PIN-A4-104-Yanti - 101101100109', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 188),
(188, 'Kastanya', 'PIN-A1-1-11-Kastanya', '101.101.100.21', 'ISP-JAKINET', 'Kastanya - PIN-A1-1-11-Kastanya - 101.101.100.21', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 189),
(189, 'Seti Wahyuni', 'PIN-A3-110-Seti', '101.101.100.22', 'ISP-JAKINET', 'Seti Wahyuni - PIN-A3-110-Seti - 101.101.100.22', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 190),
(190, 'Hermawati', 'PIN-A2-520-Hermawati', '101.101.100.24', 'ISP-JAKINET', 'Hermawati - PIN-A2-520-Hermawati - 101.101.100.24', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 191),
(191, 'Davina', 'PIN-A-A3-312-Davina', '101.101.100.23', 'ISP-JAKINET', 'Davina - PIN-A-A3-312-Davina - 101.101.100.23', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 192),
(192, 'Amy', 'PIN-A-A5-311-Amy', '101.101.100.26', 'ISP-JAKINET', 'Amy - PIN-A-A5-311-Amy - 101.101.100.26', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 193),
(193, 'Khodijah', 'PIN-A6-08-Khodijah', '101,101,100,195', 'ISP-JAKINET', 'Khodijah - PIN-A6-08-Khodijah - 101101100195', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 194),
(194, 'Fitri Handayani', 'PIN-A5-310-Fitri', '101.101.100.62', 'ISP-JAKINET', 'Fitri Handayani - PIN-A5-310-Fitri - 101.101.100.62', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 195),
(195, 'Berti', 'PIN-A-A2-410-Berti', '101.101.100.44', 'ISP-JAKINET', 'Berti - PIN-A-A2-410-Berti - 101.101.100.44', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 196),
(196, 'Gina Kusmartini', 'PIN-A6-403-Gina', '101.101.100.46', 'ISP-JAKINET', 'Gina Kusmartini - PIN-A6-403-Gina - 101.101.100.46', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 197),
(197, 'Fadilah', 'PIN-A4-401-Fadilah', '101.101.100.53', 'ISP-JAKINET', 'Fadilah - PIN-A4-401-Fadilah - 101.101.100.53', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 198),
(198, 'Ary Kuswanto', 'PIN-A2-105-Ary', '101.101.100.54', 'ISP-JAKINET', 'Ary Kuswanto - PIN-A2-105-Ary - 101.101.100.54', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 199),
(199, 'Agus Santoso', 'PIN-A6-419-Agus', '101,101,100,157', 'ISP-JAKINET', 'Agus Santoso - PIN-A6-419-Agus - 101101100157', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 200),
(200, 'Dony Sanjaya', '8121299681845', '103.103.100.17', 'ISP-JAKINET', 'Dony Sanjaya - 8121299681845 - 103.103.100.17', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 201),
(201, 'Ivone Martin', '628787943629990', '103.103.100.8', 'ISP-JAKINET', 'Ivone Martin - 628787943629990 - 103.103.100.8', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 202),
(202, 'A Fatoni', '628515746619198', '103.103.100.7', 'ISP-JAKINET', 'A Fatoni - 628515746619198 - 103.103.100.7', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 203),
(203, 'Ina Tumiwa', '6281185708898', '103.103.100.9', 'ISP-JAKINET', 'Ina Tumiwa - 6281185708898 - 103.103.100.9', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 204),
(204, 'Indra Andiana', '628596279558670', '103.103.100.12', 'ISP-JAKINET', 'Indra Andiana - 628596279558670 - 103.103.100.12', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 205),
(205, 'Desman', '628782068702627', '103.103.100.29', 'ISP-JAKINET', 'Desman - 628782068702627 - 103.103.100.29', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 206),
(206, 'Gabriella', '62877422522858', '103.103.100.13', 'ISP-JAKINET', 'Gabriella - 62877422522858 - 103.103.100.13', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 207),
(207, 'Analya', '6281112995816', '103.103.100.14', 'ISP-JAKINET', 'Analya - 6281112995816 - 103.103.100.14', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 208),
(208, 'Herlina lase', 'PGB-TWR-0602-20-0623', '103.103.100.15', 'ISP-JAKINET', 'Herlina lase - PGB-TWR-0602-20-0623 - 103.103.100.15', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 209),
(209, 'Darawati', '628950922982050', '103.103.100.32', 'ISP-JAKINET', 'Darawati - 628950922982050 - 103.103.100.32', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 210),
(210, 'Rumondang', '628226065004934', '103.103.100.28', 'ISP-JAKINET', 'Rumondang - 628226065004934 - 103.103.100.28', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 211),
(211, 'Imanuel Lembong', '628777611392330', '103.103.100.19', 'ISP-JAKINET', 'Imanuel Lembong - 628777611392330 - 103.103.100.19', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 212),
(212, 'Mieke Indriati', '628138491213881', '103.103.100.31', 'ISP-JAKINET', 'Mieke Indriati - 628138491213881 - 103.103.100.31', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 213),
(213, 'Sugiarto', '6281113637703', '103.103.100.21', 'ISP-JAKINET', 'Sugiarto - 6281113637703 - 103.103.100.21', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 214),
(214, 'Olla Tulong', '62813872744571', '103.103.100.24', 'ISP-JAKINET', 'Olla Tulong - 62813872744571 - 103.103.100.24', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 215),
(215, 'Tika Kartika', '628577371627150', '103.103.100.25', 'ISP-JAKINET', 'Tika Kartika - 628577371627150 - 103.103.100.25', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 216),
(216, 'Billie Hutabarat', '628180786149144', '103.103.100.27', 'ISP-JAKINET', 'Billie Hutabarat - 628180786149144 - 103.103.100.27', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 217),
(217, 'Titin Setiatin', '628780010734458', '103.103.100.35', 'ISP-JAKINET', 'Titin Setiatin - 628780010734458 - 103.103.100.35', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 218),
(218, 'Heince', '628157487973639', '103.103.100.34', 'ISP-JAKINET', 'Heince - 628157487973639 - 103.103.100.34', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 219),
(219, 'Waskito gunawan', '628211668133898', '103.103.100.55', 'ISP-JAKINET', 'Waskito gunawan - 628211668133898 - 103.103.100.55', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 220),
(220, 'Komalasari', '628577030881251', '103.103.100.33', 'ISP-JAKINET', 'Komalasari - 628577030881251 - 103.103.100.33', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 221),
(221, 'Rieka', '628131485117528', '103.103.100.38', 'ISP-JAKINET', 'Rieka - 628131485117528 - 103.103.100.38', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 222),
(222, 'Ety Sanjaya', '628963079562663', '103.103.100.39', 'ISP-JAKINET', 'Ety Sanjaya - 628963079562663 - 103.103.100.39', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 223),
(223, 'Ranni Panjaitan', '628128491603426', '103.103.100.52', 'ISP-JAKINET', 'Ranni Panjaitan - 628128491603426 - 103.103.100.52', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 224),
(224, 'Herna', '628578034054022', '103.103.100.51', 'ISP-JAKINET', 'Herna - 628578034054022 - 103.103.100.51', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 225),
(225, 'Deni kalanit', '628129348933142', '103.103.100.60', 'ISP-JAKINET', 'Deni kalanit - 628129348933142 - 103.103.100.60', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 226),
(226, 'Tuti Herayani', '628138161872562', '103.103.100.37', 'ISP-JAKINET', 'Tuti Herayani - 628138161872562 - 103.103.100.37', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 227),
(227, 'Yamonaha Waruwu', '628123111002126', '103.103.100.53', 'ISP-JAKINET', 'Yamonaha Waruwu - 628123111002126 - 103.103.100.53', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 228),
(228, 'Tjen Sugianto', '628138034118854', '103.103.100.43', 'ISP-JAKINET', 'Tjen Sugianto - 628138034118854 - 103.103.100.43', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 229),
(229, 'Mulyati', '628138482612792', '103.103.100.61', 'ISP-JAKINET', 'Mulyati - 628138482612792 - 103.103.100.61', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 230),
(230, 'Mauren', '628963801900766', '103.103.100.41', 'ISP-JAKINET', 'Mauren - 628963801900766 - 103.103.100.41', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 231),
(231, 'Merry Magdalena', '628121308411738', '103.103.100.58', 'ISP-JAKINET', 'Merry Magdalena - 628121308411738 - 103.103.100.58', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 232),
(232, 'Darwin', 'PGB-TWR-1007-20-1023-Darwin', '103.103.100.80', 'ISP-JAKINET', 'Darwin - PGB-TWR-1007-20-1023-Darwin - 103.103.100.80', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 233),
(233, 'Iwan', 'PGB-BLOK-F-316-20-0623-Iwan', '103.103.100.42', 'ISP-JAKINET', 'Iwan - PGB-BLOK-F-316-20-0623-Iwan - 103.103.100.42', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 234),
(234, 'Yeksen Sijabat', '628131418179654', '103.103.100.65', 'ISP-JAKINET', 'Yeksen Sijabat - 628131418179654 - 103.103.100.65', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 235),
(235, 'Yunior', '628128463350029', '103.103.100.64', 'ISP-JAKINET', 'Yunior - 628128463350029 - 103.103.100.64', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 236),
(236, 'Ronal Hasudungan', '62838625577716', '103.103.100.57', 'ISP-JAKINET', 'Ronal Hasudungan - 62838625577716 - 103.103.100.57', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 237),
(237, 'Suling', '628578179471116', '103.103.100.50', 'ISP-JAKINET', 'Suling - 628578179471116 - 103.103.100.50', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 238),
(238, 'Arvi', '628128239938021', '103.103.100.36', 'ISP-JAKINET', 'Arvi - 628128239938021 - 103.103.100.36', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 239),
(239, 'Yere mias', '628129583330093', '103.103.100.49', 'ISP-JAKINET', 'Yere mias - 628129583330093 - 103.103.100.49', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 240),
(240, 'Dwi Antoro', '628128090308311', '103.103.100.48', 'ISP-JAKINET', 'Dwi Antoro - 628128090308311 - 103.103.100.48', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 241),
(241, 'Nilam Tasya', 'PGB-TWR-1204-NILAM', '103.103.100.59', 'ISP-JAKINET', 'Nilam Tasya - PGB-TWR-1204-NILAM - 103.103.100.59', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 242),
(242, 'Erwin', '628131780524094', '103.103.100.66', 'ISP-JAKINET', 'Erwin - 628131780524094 - 103.103.100.66', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 243),
(243, 'Tari', '62812939663248', '103.103.100.63', 'ISP-JAKINET', 'Tari - 62812939663248 - 103.103.100.63', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 244),
(244, 'Tasya', '628131485705064', '103.103.100.67', 'ISP-JAKINET', 'Tasya - 628131485705064 - 103.103.100.67', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 245),
(245, 'Mike Mery Dijayanti', '628581036027215', '103.103.100.54', 'ISP-JAKINET', 'Mike Mery Dijayanti - 628581036027215 - 103.103.100.54', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 246),
(246, 'Suryani', '62899926461520', '103.103.100.76', 'ISP-JAKINET', 'Suryani - 62899926461520 - 103.103.100.76', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 247),
(247, 'Nurharisna', '628138237710035', '103.103.100.71', 'ISP-JAKINET', 'Nurharisna - 628138237710035 - 103.103.100.71', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 248),
(248, 'Julpiani', '628122132840478', '103.103.100.70', 'ISP-JAKINET', 'Julpiani - 628122132840478 - 103.103.100.70', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 249),
(249, 'Silvi', '628788554554492', '103.103.100.73', 'ISP-JAKINET', 'Silvi - 628788554554492 - 103.103.100.73', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 250),
(250, 'Sasa sajida', '62887117523446', '103.103.100.69', 'ISP-JAKINET', 'Sasa sajida - 62887117523446 - 103.103.100.69', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 251),
(251, 'Sofia Susana', 'PGB-TWR-916-20-0623-Anna', '103.103.100.72', 'ISP-JAKINET', 'Sofia Susana - PGB-TWR-916-20-0623-Anna - 103.103.100.72', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 252),
(252, 'Nerita Hardy', '628595164709118', '103.103.100.74', 'ISP-JAKINET', 'Nerita Hardy - 628595164709118 - 103.103.100.74', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 253),
(253, 'Fransiskawati', '628138206541893', '103.103.100.75', 'ISP-JAKINET', 'Fransiskawati - 628138206541893 - 103.103.100.75', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 254),
(254, 'Titin Nurjanah', '628528086713947', '103.103.100.62', 'ISP-JAKINET', 'Titin Nurjanah - 628528086713947 - 103.103.100.62', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 255),
(255, 'Suaina', '628387428994629', '103.103.100.77', 'ISP-JAKINET', 'Suaina - 628387428994629 - 103.103.100.77', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 256),
(256, 'Anna Pramesa', '62813885855858', '103.103.100.78', 'ISP-JAKINET', 'Anna Pramesa - 62813885855858 - 103.103.100.78', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 257),
(257, 'Nyoman Firdaus', '628131169807080', '103.103.100.79', 'ISP-JAKINET', 'Nyoman Firdaus - 628131169807080 - 103.103.100.79', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 258),
(258, 'Novita anggri', '628211240939391', '103.103.100.83', 'ISP-JAKINET', 'Novita anggri - 628211240939391 - 103.103.100.83', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 259),
(259, 'Alfi', '628960127296266', '103.103.100.82', 'ISP-JAKINET', 'Alfi - 628960127296266 - 103.103.100.82', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 260),
(260, 'Henny Retno', 'PGB-BLOK-H-116-20-0623-Heni', '103.103.100.84', 'ISP-JAKINET', 'Henny Retno - PGB-BLOK-H-116-20-0623-Heni - 103.103.100.84', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 261),
(261, 'Yosep', '628528935720889', '103.103.100.86', 'ISP-JAKINET', 'Yosep - 628528935720889 - 103.103.100.86', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 262),
(262, 'Marice', '628128785062686', '103.103.100.87', 'ISP-JAKINET', 'Marice - 628128785062686 - 103.103.100.87', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 263),
(263, 'Defi Sulandari', '628128184600984', '103.103.100.88', 'ISP-JAKINET', 'Defi Sulandari - 628128184600984 - 103.103.100.88', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 264),
(264, 'Ermy Gustiawati', '628121403450073', '103.103.100.89', 'ISP-JAKINET', 'Ermy Gustiawati - 628121403450073 - 103.103.100.89', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 265),
(265, 'Fitri Yanti', '628138035249427', '103.103.100.91', 'ISP-JAKINET', 'Fitri Yanti - 628138035249427 - 103.103.100.91', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 266),
(266, 'Dimas Wahyu', '628141350212069', '103.103.100.6', 'ISP-JAKINET', 'Dimas Wahyu - 628141350212069 - 103.103.100.6', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 267),
(267, 'Evi Rianty', '628389478878581', '103.103.100.16', 'ISP-JAKINET', 'Evi Rianty - 628389478878581 - 103.103.100.16', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 268),
(268, 'Elisabeth Angul', '628128035803076', '103.103.100.44', 'ISP-JAKINET', 'Elisabeth Angul - 628128035803076 - 103.103.100.44', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 269),
(269, 'Susi susilawati', '628131066936389', '103.103.100.45', 'ISP-JAKINET', 'Susi susilawati - 628131066936389 - 103.103.100.45', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 270),
(270, 'Andrawin Gunung', '62817003017827', '103.103.100.92', 'ISP-JAKINET', 'Andrawin Gunung - 62817003017827 - 103.103.100.92', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 271),
(271, 'Resmiyati', '628229923334315', '103.103.100.94', 'ISP-JAKINET', 'Resmiyati - 628229923334315 - 103.103.100.94', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 272),
(272, 'Nofri', '628128502981453', '103.103.100.95', 'ISP-JAKINET', 'Nofri - 628128502981453 - 103.103.100.95', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 273),
(273, 'Norma L Sondakh', '62852194428597', '103.103.100.96', 'ISP-JAKINET', 'Norma L Sondakh - 62852194428597 - 103.103.100.96', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 274),
(274, 'Tigrisna Olivia', '628571496560682', '103.103.100.97', 'ISP-JAKINET', 'Tigrisna Olivia - 628571496560682 - 103.103.100.97', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 275);
INSERT INTO `customers` (`id`, `name`, `customer_id`, `ip_address`, `service`, `composite_data`, `created_at`, `updated_at`, `no`) VALUES
(275, 'Desi Susilawati', '62896122805001', '103.103.100.98', 'ISP-JAKINET', 'Desi Susilawati - 62896122805001 - 103.103.100.98', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 276),
(276, 'Rotua Elisabeth Siahaan', 'PGB-TWR-314-50-0723-Siahaan', '103.103.100.99', 'ISP-JAKINET', 'Rotua Elisabeth Siahaan - PGB-TWR-314-50-0723-Siahaan - 103.103.100.99', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 277),
(277, 'Lisa', 'PGB-TWR-515-LISA', '103,103,100,100', 'ISP-JAKINET', 'Lisa - PGB-TWR-515-LISA - 103103100100', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 278),
(278, 'Titin Supartini', 'PGB-TWR-701-50-0823-Titin', '103,103,100,101', 'ISP-JAKINET', 'Titin Supartini - PGB-TWR-701-50-0823-Titin - 103103100101', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 279),
(279, 'Erniawati Galib', 'PGB-TWR-503-20-0923-Galib', '103,103,100,102', 'ISP-JAKINET', 'Erniawati Galib - PGB-TWR-503-20-0923-Galib - 103103100102', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 280),
(280, 'Ayu Indra Junita', 'PGB-TWR-1002-30-0923-Ayu', '103,103,100,103', 'ISP-JAKINET', 'Ayu Indra Junita - PGB-TWR-1002-30-0923-Ayu - 103103100103', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 281),
(281, 'MM Yuli Isnaeni', 'PGB-TWR-1013-20-0923-Yuli', '103,103,100,104', 'ISP-JAKINET', 'MM Yuli Isnaeni - PGB-TWR-1013-20-0923-Yuli - 103103100104', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 282),
(282, 'Esti mubarokah', 'PGB-TWR-1216-20-1023-Esti', '103,103,100,105', 'ISP-JAKINET', 'Esti mubarokah - PGB-TWR-1216-20-1023-Esti - 103103100105', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 283),
(283, 'Ushuluddin ilmi', 'PGB-TWR-508-30-1123-Ilmi', '103,103,100,106', 'ISP-JAKINET', 'Ushuluddin ilmi - PGB-TWR-508-30-1123-Ilmi - 103103100106', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 284),
(284, 'Lusiana Esther', 'PGB-TWR-1004-30-1123-Lusiana', '103,103,100,107', 'ISP-JAKINET', 'Lusiana Esther - PGB-TWR-1004-30-1123-Lusiana - 103103100107', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 285),
(285, 'Marsyah', 'PGB-TWR-1311-50-0224-marsyah', '103,103,100,111', 'ISP-JAKINET', 'Marsyah - PGB-TWR-1311-50-0224-marsyah - 103103100111', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 286),
(286, 'Kristy Jane', 'PGB-TWR-1517-10-0224-Kristy', '103,103,100,112', 'ISP-JAKINET', 'Kristy Jane - PGB-TWR-1517-10-0224-Kristy - 103103100112', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 287),
(287, 'Ika Pujiastuti', 'PGB-TWR-308-20-0324-Ika', '103,103,100,113', 'ISP-JAKINET', 'Ika Pujiastuti - PGB-TWR-308-20-0324-Ika - 103103100113', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 288),
(288, 'Bintang', 'PGB-TWR-1212-20-0324-Bintang', '103,103,100,114', 'ISP-JAKINET', 'Bintang - PGB-TWR-1212-20-0324-Bintang - 103103100114', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 289),
(289, 'Maylina', 'PGB-TWR-1109-20-0324-Maylina', '103,103,100,115', 'ISP-JAKINET', 'Maylina - PGB-TWR-1109-20-0324-Maylina - 103103100115', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 290),
(290, 'Virgo Delima', 'PGB-TWR-810-20-0324-Virgo', '103,103,100,116', 'ISP-JAKINET', 'Virgo Delima - PGB-TWR-810-20-0324-Virgo - 103103100116', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 291),
(291, 'Toni Predi', 'PGB-BLOK-H-410-30-0324-Topred', '103,103,100,117', 'ISP-JAKINET', 'Toni Predi - PGB-BLOK-H-410-30-0324-Topred - 103103100117', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 292),
(292, 'Rosmalia', 'PGB-TWR-407-10-0424-Rosmalia', '103,103,100,118', 'ISP-JAKINET', 'Rosmalia - PGB-TWR-407-10-0424-Rosmalia - 103103100118', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 293),
(293, 'Fitri Simanjuntak', 'PGB-TWR-414-10-0424-Fitri', '103,103,100,119', 'ISP-JAKINET', 'Fitri Simanjuntak - PGB-TWR-414-10-0424-Fitri - 103103100119', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 294),
(294, 'Kurniawan', 'PGB-TWR-45-10-0424-Kurniawan', '103,103,100,120', 'ISP-JAKINET', 'Kurniawan - PGB-TWR-45-10-0424-Kurniawan - 103103100120', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 295),
(295, 'Retno Susanti', 'PGB-TWR-717-20-0424-retno', '103,103,100,121', 'ISP-JAKINET', 'Retno Susanti - PGB-TWR-717-20-0424-retno - 103103100121', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 296),
(296, 'Muhammad Rizaldi Hindhami', 'PGB-TWR-1316-20-0524-Rizaldi', '103,103,100,122', 'ISP-JAKINET', 'Muhammad Rizaldi Hindhami - PGB-TWR-1316-20-0524-Rizaldi - 103103100122', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 297),
(297, 'Anisa Nurhakim', 'PGB-BLOK-A-401-30-0524-Anisa', '103,103,100,124', 'ISP-JAKINET', 'Anisa Nurhakim - PGB-BLOK-A-401-30-0524-Anisa - 103103100124', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 298),
(298, 'Fery Altaji Pratama', 'PGB-BLOK-A-20-0524-Fery', '103,103,100,125', 'ISP-JAKINET', 'Fery Altaji Pratama - PGB-BLOK-A-20-0524-Fery - 103103100125', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 299),
(299, 'Dewi Purwanti', 'PGB-TWR-1110-20-0524-Dewi', '103,103,100,126', 'ISP-JAKINET', 'Dewi Purwanti - PGB-TWR-1110-20-0524-Dewi - 103103100126', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 300),
(300, 'phillips', 'PGB-BLOK-G-514-50-0524-Philips', '103,103,100,129', 'ISP-JAKINET', 'phillips - PGB-BLOK-G-514-50-0524-Philips - 103103100129', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 301),
(301, 'Elly', 'PGB- TWR -1415-20-0624-Elly', '103,103,100,130', 'ISP-JAKINET', 'Elly - PGB- TWR -1415-20-0624-Elly - 103103100130', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 302),
(302, 'Vernando Gultom', 'PGB-TWR-605-10-0624-Vernando', '103,103,100,133', 'ISP-JAKINET', 'Vernando Gultom - PGB-TWR-605-10-0624-Vernando - 103103100133', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 303),
(303, 'Natalia Pangaribuan', 'PGB-BLOK-D-517-10-0624-Natalia', '103,103,100,134', 'ISP-JAKINET', 'Natalia Pangaribuan - PGB-BLOK-D-517-10-0624-Natalia - 103103100134', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 304),
(304, 'Wiharti', 'PGB-TWR-1405-10-0624-Wiharti', '103,103,100,132', 'ISP-JAKINET', 'Wiharti - PGB-TWR-1405-10-0624-Wiharti - 103103100132', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 305),
(305, 'Muhamad Rizki', 'PGB-TWR-0116-10-0724-rizki', '103,103,100,135', 'ISP-JAKINET', 'Muhamad Rizki - PGB-TWR-0116-10-0724-rizki - 103103100135', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 306),
(306, 'Kentbra Eg', 'PGB-F-503-Kentbra', '103,103,100,136', 'ISP-JAKINET', 'Kentbra Eg - PGB-F-503-Kentbra - 103103100136', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 307),
(307, 'Femmy', 'PGB-A-112-Femmy', '103,103,100,137', 'ISP-JAKINET', 'Femmy - PGB-A-112-Femmy - 103103100137', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 308),
(308, 'James Roy', 'PGB-H-503-James', '103,103,100,138', 'ISP-JAKINET', 'James Roy - PGB-H-503-James - 103103100138', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 309),
(309, 'Apritha', 'PGB-TWR-502-Apritha', '103,103,100,139', 'ISP-JAKINET', 'Apritha - PGB-TWR-502-Apritha - 103103100139', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 310),
(310, 'Sri Muntiah', 'PGB-B-215-Sri', '103.103.100.3', 'ISP-JAKINET', 'Sri Muntiah - PGB-B-215-Sri - 103.103.100.3', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 311),
(311, 'Andin', 'PGB-A-118-Andin', '103,103,100,140', 'ISP-JAKINET', 'Andin - PGB-A-118-Andin - 103103100140', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 312),
(312, 'Endah', 'PGB-E-209-Endah', '103,103,100,142', 'ISP-JAKINET', 'Endah - PGB-E-209-Endah - 103103100142', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 313),
(313, 'Retno', 'PBG-B5-01-Retno', '103,103,100,143', 'ISP-JAKINET', 'Retno - PBG-B5-01-Retno - 103103100143', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 314),
(314, 'Ita Lestari', 'PGB-B-202-Lestari', '103.103.100.46', 'ISP-JAKINET', 'Ita Lestari - PGB-B-202-Lestari - 103.103.100.46', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 315),
(315, 'Bunda Umi', 'PGB-E-207-Umi', '103,103,100,141', 'ISP-JAKINET', 'Bunda Umi - PGB-E-207-Umi - 103103100141', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 316),
(316, 'Indri', 'PGB-F-104-Indri', '103,103,100,145', 'ISP-JAKINET', 'Indri - PGB-F-104-Indri - 103103100145', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 317),
(317, 'Yenni', 'PGB-B-108-Yenni', '103,103,100,144', 'ISP-JAKINET', 'Yenni - PGB-B-108-Yenni - 103103100144', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 318),
(318, 'Rahma', 'PGB-B-217-Rahma', '103,103,100,146', 'ISP-JAKINET', 'Rahma - PGB-B-217-Rahma - 103103100146', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 319),
(319, 'Denny Susanto', 'PGB-B-1412-Deny', '103,103,100,147', 'ISP-JAKINET', 'Denny Susanto - PGB-B-1412-Deny - 103103100147', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 320),
(320, 'Chintia Damayanti', 'PGB-H-311-Chintia', '103,103,100,155', 'ISP-JAKINET', 'Chintia Damayanti - PGB-H-311-Chintia - 103103100155', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 321),
(321, 'Fitri', 'PGB-G-215-Fitri', '103,103,100,148', 'ISP-JAKINET', 'Fitri - PGB-G-215-Fitri - 103103100148', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 322),
(322, 'Nonie', 'PGB-B-213-Nonie', '103,103,100,149', 'ISP-JAKINET', 'Nonie - PGB-B-213-Nonie - 103103100149', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 323),
(323, 'Rejeki Malau', 'PGB-A-405-Rejeki', '103,103,100,150', 'ISP-JAKINET', 'Rejeki Malau - PGB-A-405-Rejeki - 103103100150', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 324),
(324, 'Tri Rejeki', 'PGB-B-315-Tri', '103,103,100,152', 'ISP-JAKINET', 'Tri Rejeki - PGB-B-315-Tri - 103103100152', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 325),
(325, 'Ratna Komala Dewi Ningrum', 'PGB-B-118-Ratna', '103,103,100,151', 'ISP-JAKINET', 'Ratna Komala Dewi Ningrum - PGB-B-118-Ratna - 103103100151', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 326),
(326, 'Matthew Oktavianus Gabriel', 'PGB-G-512-Matthew', '103,103,100,153', 'ISP-JAKINET', 'Matthew Oktavianus Gabriel - PGB-G-512-Matthew - 103103100153', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 327),
(327, 'Tara Novi S.l', 'PGB-A-202-Tara', '103,103,100,154', 'ISP-JAKINET', 'Tara Novi S.l - PGB-A-202-Tara - 103103100154', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 328),
(328, 'Wawan Riyanto', 'PGB-BLOK-C-112-10-6573-Wawan', '103,103,100,156', 'ISP-JAKINET', 'Wawan Riyanto - PGB-BLOK-C-112-10-6573-Wawan - 103103100156', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 329),
(329, 'Bayu Angelo Sondakh', 'PGB-BLOK-B-518-50-0624-Bayu', '103,103,100,131', 'ISP-JAKINET', 'Bayu Angelo Sondakh - PGB-BLOK-B-518-50-0624-Bayu - 103103100131', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 330),
(330, 'Krissamon P Nababan', 'PGB-TWR-304-30-Krissamon', '103,103,100,157', 'ISP-JAKINET', 'Krissamon P Nababan - PGB-TWR-304-30-Krissamon - 103103100157', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 331),
(331, 'Yuliani', 'PGB-BLOK G-2-202-yuliani', '103,103,100,158', 'ISP-JAKINET', 'Yuliani - PGB-BLOK G-2-202-yuliani - 103103100158', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 332),
(332, 'Nur Widyastuti', 'PGB-BLOK-B-210-Nur', '103,103,100,160', 'ISP-JAKINET', 'Nur Widyastuti - PGB-BLOK-B-210-Nur - 103103100160', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 333),
(333, 'Adrian Ramadhan', 'PGB-BLOK-B-214-Adrian', '103,103,100,161', 'ISP-JAKINET', 'Adrian Ramadhan - PGB-BLOK-B-214-Adrian - 103103100161', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 334),
(334, 'Setiadi', 'PGB-BLOK-F-301-Setiadi', '103,103,100,162', 'ISP-JAKINET', 'Setiadi - PGB-BLOK-F-301-Setiadi - 103103100162', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 335),
(335, 'Fariz', 'PGB-BLOK-F-309-Fariz', '103,103,100,163', 'ISP-JAKINET', 'Fariz - PGB-BLOK-F-309-Fariz - 103103100163', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 336),
(336, 'Imam Sudiro', 'PGB-BLOK-H-411-Imam', '103,103,100,164', 'ISP-JAKINET', 'Imam Sudiro - PGB-BLOK-H-411-Imam - 103103100164', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 337),
(337, 'Viddy Supit', 'PGB-BLOK-D-08-Viddy', '103,103,100,165', 'ISP-JAKINET', 'Viddy Supit - PGB-BLOK-D-08-Viddy - 103103100165', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 338),
(338, 'Pratiwi', 'PGB-BLOK-A-215-pratiwi', '103,103,100,166', 'ISP-JAKINET', 'Pratiwi - PGB-BLOK-A-215-pratiwi - 103103100166', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 339),
(339, 'Bunda Aina', 'PGB-BLOK-G-102-Aina', '103,103,100,167', 'ISP-JAKINET', 'Bunda Aina - PGB-BLOK-G-102-Aina - 103103100167', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 340),
(340, 'Eddyson manulang', 'PGB-TWR-1102-Eddyson', '103,103,100,168', 'ISP-JAKINET', 'Eddyson manulang - PGB-TWR-1102-Eddyson - 103103100168', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 341),
(341, 'Attalah', 'PGB-BLOK-D-401-Attalah', '103,103,100,169', 'ISP-JAKINET', 'Attalah - PGB-BLOK-D-401-Attalah - 103103100169', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 342),
(342, 'Abidan simanjuntak', 'PGB-BLOK-E-15-Abidan', '103,103,100,170', 'ISP-JAKINET', 'Abidan simanjuntak - PGB-BLOK-E-15-Abidan - 103103100170', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 343),
(343, 'Antis Mawilda', 'PGB-BLOK-F-206-Antis', '103,103,100,170', 'ISP-JAKINET', 'Antis Mawilda - PGB-BLOK-F-206-Antis - 103103100170', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 344),
(344, 'H. Herlan Matrusdi', 'PGB-TWR-1608-Herlan', '103,103,100,172', 'ISP-JAKINET', 'H. Herlan Matrusdi - PGB-TWR-1608-Herlan - 103103100172', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 345),
(345, 'Yulianti', 'PGB-B-218-Yulianti', '103,103,100,173', 'ISP-JAKINET', 'Yulianti - PGB-B-218-Yulianti - 103103100173', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 346),
(346, 'Diah Ayu Nurkhasanah', 'PGB-BLOK-A-510-Diah', '103,103,100,174', 'ISP-JAKINET', 'Diah Ayu Nurkhasanah - PGB-BLOK-A-510-Diah - 103103100174', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 347),
(347, 'Tri Widyawati', 'PGB-BLOK-A-509-Tri', '103,103,100,175', 'ISP-JAKINET', 'Tri Widyawati - PGB-BLOK-A-509-Tri - 103103100175', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 348),
(348, 'Erna', 'PGB-BLOK-B-105-Erna', '103,103,100,176', 'ISP-JAKINET', 'Erna - PGB-BLOK-B-105-Erna - 103103100176', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 349),
(349, 'Ida Jedah', 'PGB-BLOK-B-104-Ida', '103,103,100,177', 'ISP-JAKINET', 'Ida Jedah - PGB-BLOK-B-104-Ida - 103103100177', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 350),
(350, 'Maista', 'PGB-BLOK-B-106-Maista', '103,103,100,178', 'ISP-JAKINET', 'Maista - PGB-BLOK-B-106-Maista - 103103100178', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 351),
(351, 'Tri Riyantini', 'PGB-BLOK-D-420-Tri', '103,103,100,179', 'ISP-JAKINET', 'Tri Riyantini - PGB-BLOK-D-420-Tri - 103103100179', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 352),
(352, 'Angka Praditya', 'PGB-BLOK-D-112-Praditya', '103,103,100,180', 'ISP-JAKINET', 'Angka Praditya - PGB-BLOK-D-112-Praditya - 103103100180', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 353),
(353, 'Fatima Alhaura', 'PGB-BLOK-H-112-Fatima', '103,103,100,182', 'ISP-JAKINET', 'Fatima Alhaura - PGB-BLOK-H-112-Fatima - 103103100182', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 354),
(354, 'Rian Hidayat', 'PGB-BLOK-F-302-Hidayat', '103,103,100,181', 'ISP-JAKINET', 'Rian Hidayat - PGB-BLOK-F-302-Hidayat - 103103100181', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 355),
(355, 'Risna', 'PGB-TWR-307-Risna', '103,103,100,183', 'ISP-JAKINET', 'Risna - PGB-TWR-307-Risna - 103103100183', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 356),
(356, 'Kencana', 'PGB-BLOK-H-211-Kencana', '103,103,100,184', 'ISP-JAKINET', 'Kencana - PGB-BLOK-H-211-Kencana - 103103100184', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 357),
(357, 'Dewi Maryani', 'PGB-Blok-B-113-Dewi', '103,103,100,185', 'ISP-JAKINET', 'Dewi Maryani - PGB-Blok-B-113-Dewi - 103103100185', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 358),
(358, 'Linda', 'PGB-Blok-B-107-Linda', '103,103,100,186', 'ISP-JAKINET', 'Linda - PGB-Blok-B-107-Linda - 103103100186', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 359),
(359, 'Darwis', 'PGB-Blok-H-314-Darwis', '103,103,100,187', 'ISP-JAKINET', 'Darwis - PGB-Blok-H-314-Darwis - 103103100187', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 360),
(360, 'Keyla', 'PGB-TWR-1217-Keyla', '103,103,100,188', 'ISP-JAKINET', 'Keyla - PGB-TWR-1217-Keyla - 103103100188', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 361),
(361, 'Suheri', 'PGB-Blok-A-302-Suheri', '103,103,100,189', 'ISP-JAKINET', 'Suheri - PGB-Blok-A-302-Suheri - 103103100189', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 362),
(362, 'Haerunisa', 'PGB-Blok-F-306-Haerunis', '103,103,100,190', 'ISP-JAKINET', 'Haerunisa - PGB-Blok-F-306-Haerunis - 103103100190', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 363),
(363, 'Wida Widianti', 'PGB-BLOK-D-1-113-Wida', '103,103,100,191', 'ISP-JAKINET', 'Wida Widianti - PGB-BLOK-D-1-113-Wida - 103103100191', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 364),
(364, 'Heri Ertuti', 'PGB-BLOK-B-1-101-Heri', '103,103,100,192', 'ISP-JAKINET', 'Heri Ertuti - PGB-BLOK-B-1-101-Heri - 103103100192', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 365),
(365, 'Marina', 'PGB-BLOK-H-403-Marina', '103,103,100,193', 'ISP-JAKINET', 'Marina - PGB-BLOK-H-403-Marina - 103103100193', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 366),
(366, 'Annauli BR Lingga', 'PGB-TWR-412-Annauli', '103,103,100,194', 'ISP-JAKINET', 'Annauli BR Lingga - PGB-TWR-412-Annauli - 103103100194', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 367),
(367, 'Hotlan Simanjuntak', 'PGB-BLOK-D-207-Hotlan', '103,103,100,195', 'ISP-JAKINET', 'Hotlan Simanjuntak - PGB-BLOK-D-207-Hotlan - 103103100195', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 368),
(368, 'Johny Mantiri', 'PGB-BLOK-D-310-Johny', '103,103,100,196', 'ISP-JAKINET', 'Johny Mantiri - PGB-BLOK-D-310-Johny - 103103100196', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 369),
(369, 'Liberti Murni', 'PGB-BLOK-A-320-Liberti', '103.103.100.64', 'ISP-JAKINET', 'Liberti Murni - PGB-BLOK-A-320-Liberti - 103.103.100.64', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 370),
(370, 'Reni Juniati', 'PGB-BLOK-E-406-Reni', '103,103,100,197', 'ISP-JAKINET', 'Reni Juniati - PGB-BLOK-E-406-Reni - 103103100197', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 371),
(371, 'Asminah', 'PGB-TWR-612-Asminah', '103,103,100,198', 'ISP-JAKINET', 'Asminah - PGB-TWR-612-Asminah - 103103100198', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 372),
(372, 'Angel Tifanie', 'CKG-BRT-105-20-0623-Tifani627', '102.102.100.13', 'ISP-JAKINET', 'Angel Tifanie - CKG-BRT-105-20-0623-Tifani627 - 102.102.100.13', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 373),
(373, 'Richard Herrison Tuegeh', 'CKG-BRT-B-105-30-0623-Richard6386', '102.102.100.21', 'ISP-JAKINET', 'Richard Herrison Tuegeh - CKG-BRT-B-105-30-0623-Richard6386 - 102.102.100.21', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 374),
(374, 'Veriana Permatasari', '628770877949118', '102.102.100.22', 'ISP-JAKINET', 'Veriana Permatasari - 628770877949118 - 102.102.100.22', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 375),
(375, 'David Putra', '6289532736548053', '102.102.100.23', 'ISP-JAKINET', 'David Putra - 6289532736548053 - 102.102.100.23', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 376),
(376, 'Marsel Kusoy', '628777554489859', '102.102.100.24', 'ISP-JAKINET', 'Marsel Kusoy - 628777554489859 - 102.102.100.24', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 377),
(377, 'Cynthia Ratna Octaviana', '62811193200418', '102.102.100.29', 'ISP-JAKINET', 'Cynthia Ratna Octaviana - 62811193200418 - 102.102.100.29', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 378),
(378, 'Jerrico Christian Karisoh', '628515527507087', '102.102.100.30', 'ISP-JAKINET', 'Jerrico Christian Karisoh - 628515527507087 - 102.102.100.30', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 379),
(379, 'Delima Roshendra', '628227322356217', '102.102.100.31', 'ISP-JAKINET', 'Delima Roshendra - 628227322356217 - 102.102.100.31', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 380),
(380, 'Semuel Arnolus', '628222100092072', '102.102.100.33', 'ISP-JAKINET', 'Semuel Arnolus - 628222100092072 - 102.102.100.33', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 381),
(381, 'Alfin', 'CKG-BRT-F-105-20-0723-Alfin', '102.102.100.38', 'ISP-JAKINET', 'Alfin - CKG-BRT-F-105-20-0723-Alfin - 102.102.100.38', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 382),
(382, 'Putri Adyta', 'CKG-BRT-B-201-10-1023-PUTRI', '102.102.100.40', 'ISP-JAKINET', 'Putri Adyta - CKG-BRT-B-201-10-1023-PUTRI - 102.102.100.40', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 383),
(383, 'Fitriana', 'CKG-BRT-B-101-10-1023-FITRI', '102.102.100.41', 'ISP-JAKINET', 'Fitriana - CKG-BRT-B-101-10-1023-FITRI - 102.102.100.41', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 384),
(384, 'Gustina', 'CKG-BRT-B-108-10-1023-Gustina', '102.102.100.42', 'ISP-JAKINET', 'Gustina - CKG-BRT-B-108-10-1023-Gustina - 102.102.100.42', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 385),
(385, 'Firmansyah', 'CKG-BRT-B-107-10-1023-Firmansyah', '102.102.100.43', 'ISP-JAKINET', 'Firmansyah - CKG-BRT-B-107-10-1023-Firmansyah - 102.102.100.43', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 386),
(386, 'Ali Subandi', 'CKG-BRT-B-211-20-1123-Subandi', '102.102.100.45', 'ISP-JAKINET', 'Ali Subandi - CKG-BRT-B-211-20-1123-Subandi - 102.102.100.45', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 387),
(387, 'Winda', 'CKG-BRT-A-512-10-1123-Winda', '102.102.100.46', 'ISP-JAKINET', 'Winda - CKG-BRT-A-512-10-1123-Winda - 102.102.100.46', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 388),
(388, 'Marsikul Fitri aningrum', 'CKG-BRT-B-404-1123-MARSIKUL', '102.102.100.48', 'ISP-JAKINET', 'Marsikul Fitri aningrum - CKG-BRT--B-404-1123-MARSIKUL - 102.102.100.48', '2025-01-21 21:59:33', '2025-01-21 23:10:00', 389),
(389, 'Allesandro Giovany', 'CKG-BRT-B-102-0224-Allesandro', '102.102.100.51', 'ISP-JAKINET', 'Allesandro Giovany - CKG-BRT--B-102-0224-Allesandro - 102.102.100.51', '2025-01-21 21:59:33', '2025-01-21 23:09:17', 390),
(390, 'Rendra Setyawan', 'CKG-BRT-B-308-10-0424-Rendra', '102.102.100.53', 'ISP-JAKINET', 'Rendra Setyawan - CKG-BRT--B-308-10-0424-Rendra - 102.102.100.53', '2025-01-21 21:59:33', '2025-01-21 23:10:23', 391),
(391, 'Fathir', 'CKG-BRT-B-110-50-0524-Fathir', '102.102.100.54', 'ISP-JAKINET', 'Fathir - CKG-BRT-B-110-50-0524-Fathir - 102.102.100.54', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 392),
(392, 'Oma Joey', 'CKG-BRT-B-207-10-0524-Oma', '102.102.100.55', 'ISP-JAKINET', 'Oma Joey - CKG-BRT-B-207-10-0524-Oma - 102.102.100.55', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 393),
(393, 'Selly Ayu Irawaty', 'CKB-G-205-Selly', '102.102.100.56', 'ISP-JAKINET', 'Selly Ayu Irawaty - CKB-G-205-Selly - 102.102.100.56', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 394),
(394, 'Silvia Putranti', 'CKB-G-210-Silvia', '102.102.100.59', 'ISP-JAKINET', 'Silvia Putranti - CKB-G-210-Silvia - 102.102.100.59', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 395),
(395, 'Heryanti', 'CKB-G-0307-Heryanti', '102.102.100.60', 'ISP-JAKINET', 'Heryanti - CKB-G-0307-Heryanti - 102.102.100.60', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 396),
(396, 'Micka Afianto', 'CKB-B-402- Micka', '102.102.100.61', 'ISP-JAKINET', 'Micka Afianto - CKB-B-402- Micka - 102.102.100.61', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 397),
(397, 'Nadya', 'CKB-A-506-Nadya', '102.102.100.63', 'ISP-JAKINET', 'Nadya - CKB-A-506-Nadya - 102.102.100.63', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 398),
(398, 'Eva', '628231017107431', '102.102.100.11', 'ISP-JAKINET', 'Eva - 628231017107431 - 102.102.100.11', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 399),
(399, 'Axl', '628226000505020', '102.102.100.9', 'ISP-JAKINET', 'Axl - 628226000505020 - 102.102.100.9', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 400),
(400, 'Fransiska tjatur s', '628965253238887', '102.102.100.10', 'ISP-JAKINET', 'Fransiska tjatur s - 628965253238887 - 102.102.100.10', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 401),
(401, 'Sari mawar', '62811979258859', '102.102.100.14', 'ISP-JAKINET', 'Sari mawar - 62811979258859 - 102.102.100.14', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 402),
(402, 'Daniel Kristianto', '6281181696918', '102.102.100.18', 'ISP-JAKINET', 'Daniel Kristianto - 6281181696918 - 102.102.100.18', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 403),
(403, 'Mardelina tambunan', '628131581615691', '102.102.100.16', 'ISP-JAKINET', 'Mardelina tambunan - 628131581615691 - 102.102.100.16', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 404),
(404, 'Alexander Buyung', '62816134517892', '102.102.100.19', 'ISP-JAKINET', 'Alexander Buyung - 62816134517892 - 102.102.100.19', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 405),
(405, 'Aziz Safani', '628121329729981', '102.102.100.27', 'ISP-JAKINET', 'Aziz Safani - 628121329729981 - 102.102.100.27', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 406),
(406, 'Asael Reinhart', '628125955031871', '102.102.100.32', 'ISP-JAKINET', 'Asael Reinhart - 628125955031871 - 102.102.100.32', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 407),
(407, 'Robi Hambali', '628129466143893', '102.102.100.7', 'ISP-JAKINET', 'Robi Hambali - 628129466143893 - 102.102.100.7', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 408),
(408, 'Febry Papilaya', 'CKG-TPR-RSML-310-20-1123-Febry', '102.102.100.47', 'ISP-JAKINET', 'Febry Papilaya - CKG-TPR-RSML-310-20-1123-Febry - 102.102.100.47', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 409),
(409, 'Junus R. S.', 'CKR-TPR-AKS-30-107-0224-Junus', '102.102.100.49', 'ISP-JAKINET', 'Junus R. S. - CKR-TPR-AKS-30-107-0224-Junus - 102.102.100.49', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 410),
(410, 'Brally Henoch', 'CKR-TPR-ANG-303-20-0324-Brally', '102.102.100.52', 'ISP-JAKINET', 'Brally Henoch - CKR-TPR-ANG-303-20-0324-Brally - 102.102.100.52', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 411),
(411, 'Binsar M Silaen', 'CKG-TPR-PSP-118-Binsar', '102.102.100.62', 'ISP-JAKINET', 'Binsar M Silaen - CKG-TPR-PSP-118-Binsar - 102.102.100.62', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 412),
(412, 'Nadia Ulfa', 'CKG-TPR-MER-319-Nadia', '102.102.100.64', 'ISP-JAKINET', 'Nadia Ulfa - CKG-TPR-MER-319-Nadia - 102.102.100.64', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 413),
(413, 'Edi', 'NGR-TWR2-0203-akila', '192.168.50.15', 'ISP-JELANTIK', 'Edi - NGR-TWR2-0203-akila - 192.168.50.15', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 414),
(414, 'Niko Falentino', 'NGR-TWR3-0811-niko', '192.168.50.14', 'ISP-JELANTIK', 'Niko Falentino - NGR-TWR3-0811-niko - 192.168.50.14', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 415),
(415, 'Syech Syarif Hidayatollah', 'NGR-TWR2-1107-Syarif', '192.168.50.5', 'ISP-JELANTIK', 'Syech Syarif Hidayatollah - NGR-TWR2-1107-Syarif - 192.168.50.5', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 416),
(416, 'Siti Khodijah', 'NGR-TWR2-Khadijah', '192.168.50.2', 'ISP-JELANTIK', 'Siti Khodijah - NGR-TWR2-Khadijah - 192.168.50.2', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 417),
(417, 'Ainun', 'NGR-TWR3-0904-Rosliati', '192.168.50.4', 'ISP-JELANTIK', 'Ainun - NGR-TWR3-0904-Rosliati - 192.168.50.4', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 418),
(418, 'Shirley Aplonia', 'NGR-TWR3-1312-Shirley', '192.168.50.3', 'ISP-JELANTIK', 'Shirley Aplonia - NGR-TWR3-1312-Shirley - 192.168.50.3', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 419),
(419, 'Netti Sigalingging', 'NGR-TWR3-1202-Netti', '192.168.50.6', 'ISP-JELANTIK', 'Netti Sigalingging - NGR-TWR3-1202-Netti - 192.168.50.6', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 420),
(420, 'Jeliana Siagian', 'NGR-TWR3-1209-Jeliana', '192.168.50.7', 'ISP-JELANTIK', 'Jeliana Siagian - NGR-TWR3-1209-Jeliana - 192.168.50.7', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 421),
(421, 'Toga Hutasoit', 'NGR-TWR1-1008-Toga', '192.168.50.9', 'ISP-JELANTIK', 'Toga Hutasoit - NGR-TWR1-1008-Toga - 192.168.50.9', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 422),
(422, 'Ferdi Rifani', 'NGR-TWR2-1013-Rifani', '192.168.50.10', 'ISP-JELANTIK', 'Ferdi Rifani - NGR-TWR2-1013-Rifani - 192.168.50.10', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 423),
(423, 'Astutik', 'NGR-TWR3-1210-Tutik', '192.168.50.8', 'ISP-JELANTIK', 'Astutik - NGR-TWR3-1210-Tutik - 192.168.50.8', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 424),
(424, 'Rasman', 'NGR-TWR1-1514-Rasman', '192.168.50.12', 'ISP-JELANTIK', 'Rasman - NGR-TWR1-1514-Rasman - 192.168.50.12', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 425),
(425, 'Virgie Wulan Lengkong', 'NGR-TWR3-121-Virgie', '192.168.50.13', 'ISP-JELANTIK', 'Virgie Wulan Lengkong - NGR-TWR3-121-Virgie - 192.168.50.13', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 426),
(426, 'Rani Surya', 'NGR-TWR1-1511-Rani', '192.168.50.16', 'ISP-JELANTIK', 'Rani Surya - NGR-TWR1-1511-Rani - 192.168.50.16', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 427),
(427, 'Effy', 'NGR-TWR3-99-EFFY', '192.168.50.19', 'ISP-JELANTIK', 'Effy - NGR-TWR3-99-EFFY - 192.168.50.19', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 428),
(428, 'Dedi Setiawan', 'NGR-TWR3-709-DEDI', '192.168.50.18', 'ISP-JELANTIK', 'Dedi Setiawan - NGR-TWR3-709-DEDI - 192.168.50.18', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 429),
(429, 'Jenita', 'NGR-TWR1-210-Jenita', '192.168.50.17', 'ISP-JELANTIK', 'Jenita - NGR-TWR1-210-Jenita - 192.168.50.17', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 430),
(430, 'Santi Alif', 'NGR-TWR3-93-Alif', '192.168.50.20', 'ISP-JELANTIK', 'Santi Alif - NGR-TWR3-93-Alif - 192.168.50.20', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 431),
(431, 'Erna Zea', 'NGR-TWR3-3912-Erna', '192.168.50.21', 'ISP-JELANTIK', 'Erna Zea - NGR-TWR3-3912-Erna - 192.168.50.21', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 432),
(432, 'Muhyidin', 'NGR-TWR3-0911-muhyidin', '192.168.50.22', 'ISP-JELANTIK', 'Muhyidin - NGR-TWR3-0911-muhyidin - 192.168.50.22', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 433),
(433, 'Abdul Nana', 'NGR-TWR3-0410-Abdul', '192.168.50.23', 'ISP-JELANTIK', 'Abdul Nana - NGR-TWR3-0410-Abdul - 192.168.50.23', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 434),
(434, 'Deka Swi Indriana', 'NGR-TWR1-1108-Deka', '192.168.50.24', 'ISP-JELANTIK', 'Deka Swi Indriana - NGR-TWR1-1108-Deka - 192.168.50.24', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 435),
(435, 'Sutiyanto', 'NGR-TWR3-813-Sutiyanto', '192.168.50.25', 'ISP-JELANTIK', 'Sutiyanto - NGR-TWR3-813-Sutiyanto - 192.168.50.25', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 436),
(436, 'Saeful Bahri', 'NGR-TWR3-78-Saeful', '192.168.50.26', 'ISP-JELANTIK', 'Saeful Bahri - NGR-TWR3-78-Saeful - 192.168.50.26', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 437),
(437, 'Mulyani', 'NGR-TWR1-602-Mulyanil', '192.168.50.27', 'ISP-JELANTIK', 'Mulyani - NGR-TWR1-602-Mulyanil - 192.168.50.27', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 438),
(438, 'Riyani', 'NGR-TWR1-606-Riyani', '192.168.50.29', 'ISP-JELANTIK', 'Riyani - NGR-TWR1-606-Riyani - 192.168.50.29', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 439),
(439, 'Winda', 'NGR-TWR3-1206-winda', '192.168.50.30', 'ISP-JELANTIK', 'Winda - NGR-TWR3-1206-winda - 192.168.50.30', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 440),
(440, 'Indarto', 'NGR-TWR3-812-Indarto', '192.168.50.31', 'ISP-JELANTIK', 'Indarto - NGR-TWR3-812-Indarto - 192.168.50.31', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 441),
(441, 'Alimansyah', 'NGR-TWR1-1501-Alimansyah', '192.168.50.32', 'ISP-JELANTIK', 'Alimansyah - NGR-TWR1-1501-Alimansyah - 192.168.50.32', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 442),
(442, 'Achmad Zaini', 'NGR-TWR3-1010-Achmad', '192.168.50.33', 'ISP-JELANTIK', 'Achmad Zaini - NGR-TWR3-1010-Achmad - 192.168.50.33', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 443),
(443, 'Heppi Tri Sulistyo', 'NGR-TWR3-801-Heppi', '192.168.50.34', 'ISP-JELANTIK', 'Heppi Tri Sulistyo - NGR-TWR3-801-Heppi - 192.168.50.34', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 444),
(444, 'Jubaidah', 'NGR-TWR2-910-Jubaidah', '192.168.50.36', 'ISP-JELANTIK', 'Jubaidah - NGR-TWR2-910-Jubaidah - 192.168.50.36', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 445),
(445, 'Rizki', 'NGR-TWR3-1004-Rizki', '192.168.50.37', 'ISP-JELANTIK', 'Rizki - NGR-TWR3-1004-Rizki - 192.168.50.37', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 446),
(446, 'Andri', 'NGR-TWR1-614-Andri', '192.168.50.35', 'ISP-JELANTIK', 'Andri - NGR-TWR1-614-Andri - 192.168.50.35', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 447),
(447, 'Dede Ratminingsih', 'NGR-TWR1-1102-Dede', '192.168.50.38', 'ISP-JELANTIK', 'Dede Ratminingsih - NGR-TWR1-1102-Dede - 192.168.50.38', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 448),
(448, 'Abdul Manan', 'NGR-TWR3-807-Abdul', '192.168.50.39', 'ISP-JELANTIK', 'Abdul Manan - NGR-TWR3-807-Abdul - 192.168.50.39', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 449),
(449, 'Wulan', 'NGR-TWR2-1512-Wulan', '192.168.50.40', 'ISP-JELANTIK', 'Wulan - NGR-TWR2-1512-Wulan - 192.168.50.40', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 450),
(450, 'Rita Novita Sari', 'NGR-TWR1-1014-Rita', '192.168.50.41', 'ISP-JELANTIK', 'Rita Novita Sari - NGR-TWR1-1014-Rita - 192.168.50.41', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 451),
(451, 'Ayu Oktaviani', 'NGR-TWR2-1211-Ayu', '192.168.50.42', 'ISP-JELANTIK', 'Ayu Oktaviani - NGR-TWR2-1211-Ayu - 192.168.50.42', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 452),
(452, 'Alwani', 'NGR-TWR1-149-Alwani', '192.168.50.43', 'ISP-JELANTIK', 'Alwani - NGR-TWR1-149-Alwani - 192.168.50.43', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 453),
(453, 'Diana', 'NGR-TWR3-307- diana', '192.168.50.44', 'ISP-JELANTIK', 'Diana - NGR-TWR3-307- diana - 192.168.50.44', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 454),
(454, 'Rasmini', 'NGR-TWR3-1301-Rasmini', '192.168.50.45', 'ISP-JELANTIK', 'Rasmini - NGR-TWR3-1301-Rasmini - 192.168.50.45', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 455),
(455, 'Suherpi', 'NGR-TWR3-610-Suherpi', '192.168.50.46', 'ISP-JELANTIK', 'Suherpi - NGR-TWR3-610-Suherpi - 192.168.50.46', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 456),
(456, 'Opik', 'NGR-TWR3-810-Opik', '192.168.50.47', 'ISP-JELANTIK', 'Opik - NGR-TWR3-810-Opik - 192.168.50.47', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 457),
(457, 'muhammad nur', 'NGR-TWR2-1112-Nur', '192.168.50.48', 'ISP-JELANTIK', 'muhammad nur - NGR-TWR2-1112-Nur - 192.168.50.48', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 458),
(458, 'Rochaeni', 'NGR-TWR2-1505-Rochaeni', '192.168.50.49', 'ISP-JELANTIK', 'Rochaeni - NGR-TWR2-1505-Rochaeni - 192.168.50.49', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 459),
(459, 'Akbar', 'NGR-TWR2-1210-Akbar', '192.168.50.50', 'ISP-JELANTIK', 'Akbar - NGR-TWR2-1210-Akbar - 192.168.50.50', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 460),
(460, 'Kaka Maulana', 'NGR-TWR-7706- Maulana', '192.168.50.52', 'ISP-JELANTIK', 'Kaka Maulana - NGR-TWR-7706- Maulana - 192.168.50.52', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 461),
(461, 'Rahmatudin', 'NGR-TWR2-705-Rahmatudin', '192.168.50.53', 'ISP-JELANTIK', 'Rahmatudin - NGR-TWR2-705-Rahmatudin - 192.168.50.53', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 462),
(462, 'Sefianty', 'NGR-TWR2-1204-Sefianty', '192.168.50.54', 'ISP-JELANTIK', 'Sefianty - NGR-TWR2-1204-Sefianty - 192.168.50.54', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 463),
(463, 'Juju juwariah', 'NGR-TWR3-1006-juju', '192.168.50.55', 'ISP-JELANTIK', 'Juju juwariah - NGR-TWR3-1006-juju - 192.168.50.55', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 464),
(464, 'Desta Rizki Amalia', 'NGR-TWR3-612-desta', '192.168.50.56', 'ISP-JELANTIK', 'Desta Rizki Amalia - NGR-TWR3-612-desta - 192.168.50.56', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 465),
(465, 'Maiti', 'NGR-TWR3-1110-maiti', '192.168.50.57', 'ISP-JELANTIK', 'Maiti - NGR-TWR3-1110-maiti - 192.168.50.57', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 466),
(466, 'Resmia Damayanthi', 'NGR-TWR1-1112-resmia', '192.168.50.58', 'ISP-JELANTIK', 'Resmia Damayanthi - NGR-TWR1-1112-resmia - 192.168.50.58', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 467),
(467, 'Kholifah', 'PRM-A1-40-kholifah', '192.168.60.4', 'ISP-JELANTIK', 'Kholifah - PRM-A1-40-kholifah - 192.168.60.4', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 468),
(468, 'Yati', 'PRM-A4-03-Yati', '192.168.60.8', 'ISP-JELANTIK', 'Yati - PRM-A4-03-Yati - 192.168.60.8', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 469),
(469, 'Mustika', 'PRM-A5-8-Mustika', '192.168.60.3', 'ISP-JELANTIK', 'Mustika - PRM-A5-8-Mustika - 192.168.60.3', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 470),
(470, 'Dheny Akbar Saputra', 'PRM-A1-46-Dheny', '192.168.60.5', 'ISP-JELANTIK', 'Dheny Akbar Saputra - PRM-A1-46-Dheny - 192.168.60.5', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 471),
(471, 'Eka Nurullatim', 'PRM-A4-01-Eka', '192.168.60.7', 'ISP-JELANTIK', 'Eka Nurullatim - PRM-A4-01-Eka - 192.168.60.7', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 472),
(472, 'Humaeni', 'PRM-A4-10-Humaeni', '192.168.60.9', 'ISP-JELANTIK', 'Humaeni - PRM-A4-10-Humaeni - 192.168.60.9', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 473),
(473, 'Dewi Rosita', 'PRM-A4-11-dewi', '192.168.60.10', 'ISP-JELANTIK', 'Dewi Rosita - PRM-A4-11-dewi - 192.168.60.10', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 474),
(474, 'Popy Apriana', 'PRM-A1-34-Popy', '192.168.60.6', 'ISP-JELANTIK', 'Popy Apriana - PRM-A1-34-Popy - 192.168.60.6', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 475),
(475, 'Firman', 'PRM-B3-28-Firman', '192.168.60.14', 'ISP-JELANTIK', 'Firman - PRM-B3-28-Firman - 192.168.60.14', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 476),
(476, 'Anggi Rafsanjani', 'PRM-B3-21-Anggi', '192.168.60.11', 'ISP-JELANTIK', 'Anggi Rafsanjani - PRM-B3-21-Anggi - 192.168.60.11', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 477),
(477, 'Jannatan Sang Adji', 'PRM-B1-12-Jannatan', '192.168.60.12', 'ISP-JELANTIK', 'Jannatan Sang Adji - PRM-B1-12-Jannatan - 192.168.60.12', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 478),
(478, 'Jati Satria', 'PRM-B1-14-Jati', '192.168.60.13', 'ISP-JELANTIK', 'Jati Satria - PRM-B1-14-Jati - 192.168.60.13', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 479),
(479, 'Pipin Nurwulan Dari', 'PRM-A4-23-Pipin', '192.168.60.15', 'ISP-JELANTIK', 'Pipin Nurwulan Dari - PRM-A4-23-Pipin - 192.168.60.15', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 480),
(480, 'Muzahidin', 'PRM-A4-28-Muzahidin', '192.168.60.16', 'ISP-JELANTIK', 'Muzahidin - PRM-A4-28-Muzahidin - 192.168.60.16', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 481),
(481, 'Ivo astriani', 'PRM-A4-22-Ivo', '192.168.60.17', 'ISP-JELANTIK', 'Ivo astriani - PRM-A4-22-Ivo - 192.168.60.17', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 482),
(482, 'Annisa rizqiani', 'PRM-A4-13-Annisa', '192.168.60.18', 'ISP-JELANTIK', 'Annisa rizqiani - PRM-A4-13-Annisa - 192.168.60.18', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 483),
(483, 'Amirul Khair', 'PRM-B4-07-Amirul', '192.168.60.19', 'ISP-JELANTIK', 'Amirul Khair - PRM-B4-07-Amirul - 192.168.60.19', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 484),
(484, 'Pita Azizah', 'PRM-A1-41-Azizah', '192.168.60.20', 'ISP-JELANTIK', 'Pita Azizah - PRM-A1-41-Azizah - 192.168.60.20', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 485),
(485, 'Rachmat Irfanto', 'PRM-B1-03-Racmat', '192.168.60.21', 'ISP-JELANTIK', 'Rachmat Irfanto - PRM-B1-03-Racmat - 192.168.60.21', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 486),
(486, 'Nasiroh', 'PRM-A1-37-Nasiroh', '192.168.60.22', 'ISP-JELANTIK', 'Nasiroh - PRM-A1-37-Nasiroh - 192.168.60.22', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 487),
(487, 'Ita', 'PRM-A4-07-Ita', '192.168.60.23', 'ISP-JELANTIK', 'Ita - PRM-A4-07-Ita - 192.168.60.23', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 488),
(488, 'Wilda Suharyati', 'PRM-B3-23-wilda', '192.168.60.24', 'ISP-JELANTIK', 'Wilda Suharyati - PRM-B3-23-wilda - 192.168.60.24', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 489),
(489, 'Rizki Ahmad Ghifari', 'PRM-B7-12-Rizki', '192.168.60.25', 'ISP-JELANTIK', 'Rizki Ahmad Ghifari - PRM-B7-12-Rizki - 192.168.60.25', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 490),
(490, 'Syahrul Sabri', 'PRM-B5-04-Syahrul', '192.168.60.27', 'ISP-JELANTIK', 'Syahrul Sabri - PRM-B5-04-Syahrul - 192.168.60.27', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 491),
(491, 'Chandra Aprilian Simanjuntak', 'PRM-A4-24-Chandra', '192.168.60.28', 'ISP-JELANTIK', 'Chandra Aprilian Simanjuntak - PRM-A4-24-Chandra - 192.168.60.28', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 492),
(492, 'Nurhasanah', 'PRM-Blok-B2-1-Nurhasanah', '192.168.60.29', 'ISP-JELANTIK', 'Nurhasanah - PRM-Blok-B2-1-Nurhasanah - 192.168.60.29', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 493),
(493, 'Hanifah', 'PRM-B4-9-Hanifah', '192.168.60.30', 'ISP-JELANTIK', 'Hanifah - PRM-B4-9-Hanifah - 192.168.60.30', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 494),
(494, 'Tika', 'PRM-B1-9-Tika', '192.168.60.32', 'ISP-JELANTIK', 'Tika - PRM-B1-9-Tika - 192.168.60.32', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 495),
(495, 'Arsyil Fajri', 'PRM-B10-29-Arsyil', '192.168.60.33', 'ISP-JELANTIK', 'Arsyil Fajri - PRM-B10-29-Arsyil - 192.168.60.33', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 496),
(496, 'Tati Asnawati', 'PRM-B4-20-Tati', '192.168.60.34', 'ISP-JELANTIK', 'Tati Asnawati - PRM-B4-20-Tati - 192.168.60.34', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 497),
(497, 'Ridotul Juniah', 'PRM-B4-02-Juniah', '192.168.60.35', 'ISP-JELANTIK', 'Ridotul Juniah - PRM-B4-02-Juniah - 192.168.60.35', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 498),
(498, 'Ade Syahril', 'PRM-A1-38-Syahril', '192.168.60.36', 'ISP-JELANTIK', 'Ade Syahril - PRM-A1-38-Syahril - 192.168.60.36', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 499),
(499, 'Eni Gustini', 'PRM-B5-17-Eni', '192.168.60.37', 'ISP-JELANTIK', 'Eni Gustini - PRM-B5-17-Eni - 192.168.60.37', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 500),
(500, 'Aliya  ', 'PRM-B4-22-Aliya', '192.168.60.38', 'ISP-JELANTIK', 'Aliya   - PRM-B4-22-Aliya - 192.168.60.38', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 501),
(501, 'Alfamart Parama', 'PRM-Ruko-Alfamart', '192.168.60.39', 'ISP-JELANTIK', 'Alfamart Parama - PRM-Ruko-Alfamart - 192.168.60.39', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 502),
(502, 'Khairo', 'KMD-0705-Reno-2024', '101,101,100,246', 'ISP-JELANTIK', 'Khairo - KMD-0705-Reno-2024 - 101101100246', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 503),
(503, 'Ayu Putriany', 'KMD-1205-Ayu-2024', '101,101,100,249', 'ISP-JELANTIK', 'Ayu Putriany - KMD-1205-Ayu-2024 - 101101100249', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 504),
(504, 'Abu Bakar', 'KMD-1205-Abu-2024', '101,101,100,250', 'ISP-JELANTIK', 'Abu Bakar - KMD-1205-Abu-2024 - 101101100250', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 505),
(505, 'Ahmad Hamdani', 'KMD-1205-Dani-2024', '101.101.100.236', 'ISP-JELANTIK', 'Ahmad Hamdani - KMD-1205-Dani-2024 - 101.101.100.236', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 506),
(506, 'Sutata', 'KMD-7533-Sutata-2024', '101.101.100.51', 'ISP-JELANTIK', 'Sutata - KMD-7533-Sutata-2024 - 101.101.100.51', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 507),
(507, 'Woro Lestari', 'KMD-12547-Woro-2024', '101.101.100.42', 'ISP-JELANTIK', 'Woro Lestari - KMD-12547-Woro-2024 - 101.101.100.42', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 508),
(508, 'M Yusuf', 'TMB-MGG-109-Yusuf', '192.168.30.6', 'ISP-JELANTIK', 'M Yusuf - TMB-MGG-109-Yusuf - 192.168.30.6', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 509),
(509, 'Bu Fajar', 'TMB-KBJ2-C20A-Fajar ', '192.168.30.5', 'ISP-JELANTIK', 'Bu Fajar - TMB-KBJ2-C20A-Fajar  - 192.168.30.5', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 510),
(510, 'Delif Jaket', 'TMB-KHM-42-Delif', '192.168.30.7', 'ISP-JELANTIK', 'Delif Jaket - TMB-KHM-42-Delif - 192.168.30.7', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 511),
(511, 'Maman', 'WRG-B6-2-Maman', '192.168.40.3', 'ISP-JELANTIK', 'Maman - WRG-B6-2-Maman - 192.168.40.3', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 512),
(512, 'Slamet', 'WRG-B5-16-Slamet', '192.168.40.4', 'ISP-JELANTIK', 'Slamet - WRG-B5-16-Slamet - 192.168.40.4', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 513),
(513, 'Rita', 'WRG-B5-22-Rita', '192.168.40.5', 'ISP-JELANTIK', 'Rita - WRG-B5-22-Rita - 192.168.40.5', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 514),
(514, 'Inggit Rosphiana', 'WRG-A3-5-Inggit', '192.168.40.6', 'ISP-JELANTIK', 'Inggit Rosphiana - WRG-A3-5-Inggit - 192.168.40.6', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 515),
(515, 'Rian', 'WRG-A3-1-Rian', '192.168.40.7', 'ISP-JELANTIK', 'Rian - WRG-A3-1-Rian - 192.168.40.7', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 516),
(516, 'Neneng Sri Widayanti', 'WRG-A3-10-Widayanti', '192.168.40.8', 'ISP-JELANTIK', 'Neneng Sri Widayanti - WRG-A3-10-Widayanti - 192.168.40.8', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 517),
(517, 'Dewi Hartati', 'WRG-A3-9-Dewi', '192.168.40.9', 'ISP-JELANTIK', 'Dewi Hartati - WRG-A3-9-Dewi - 192.168.40.9', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 518),
(518, 'Adien', 'WRG-A1-7-Adien', '192.168.40.10', 'ISP-JELANTIK', 'Adien - WRG-A1-7-Adien - 192.168.40.10', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 519),
(519, 'Yopan', 'WRG-A2-9-Yopan', '192.168.40.11', 'ISP-JELANTIK', 'Yopan - WRG-A2-9-Yopan - 192.168.40.11', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 520),
(520, 'Anton Saputra', 'WRG-A4-2-Anton', '192.168.40.12', 'ISP-JELANTIK', 'Anton Saputra - WRG-A4-2-Anton - 192.168.40.12', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 521),
(521, 'Yoga', 'WRG-B3-8-Yoga', '192.168.40.13', 'ISP-JELANTIK', 'Yoga - WRG-B3-8-Yoga - 192.168.40.13', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 522),
(522, 'Sudira', 'WRG-A5-12-Sudira', '192.168.40.14', 'ISP-JELANTIK', 'Sudira - WRG-A5-12-Sudira - 192.168.40.14', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 523),
(523, 'Suproji', 'WRG-B5-20-Suproji', '192.168.40.15', 'ISP-JELANTIK', 'Suproji - WRG-B5-20-Suproji - 192.168.40.15', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 524),
(524, 'Eli Sukasih', 'WRG-B4-3-ELI', '192.168.40.16', 'ISP-JELANTIK', 'Eli Sukasih - WRG-B4-3-ELI - 192.168.40.16', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 525),
(525, 'Lima Dara ', 'WRG-B1-1-Lima', '192.168.40.17', 'ISP-JELANTIK', 'Lima Dara  - WRG-B1-1-Lima - 192.168.40.17', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 526),
(526, 'Vivian desilva', 'WRG-A6-2-Vivian', '192.168.40.18', 'ISP-JELANTIK', 'Vivian desilva - WRG-A6-2-Vivian - 192.168.40.18', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 527),
(527, 'Fitri Lindawati', 'WRG-A4-11-Fitri', '192.168.40.19', 'ISP-JELANTIK', 'Fitri Lindawati - WRG-A4-11-Fitri - 192.168.40.19', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 528),
(528, 'Cipto', 'WRG-A4-15-Cipto', '192.168.40.20', 'ISP-JELANTIK', 'Cipto - WRG-A4-15-Cipto - 192.168.40.20', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 529),
(529, 'Shelvi Dianeth', 'WRG-B1-11-Shelvi', '192.168.40.21', 'ISP-JELANTIK', 'Shelvi Dianeth - WRG-B1-11-Shelvi - 192.168.40.21', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 530),
(530, 'Restu Ady', 'WRG-A6-11-Restu', '192.168.40.22', 'ISP-JELANTIK', 'Restu Ady - WRG-A6-11-Restu - 192.168.40.22', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 531),
(531, 'Aldy', 'WRG-B3-9-Aldy', '192.168.40.23', 'ISP-JELANTIK', 'Aldy - WRG-B3-9-Aldy - 192.168.40.23', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 532),
(532, 'Raswa Jaya', 'WRG-A3-15-Raswa', '192.168.40.24', 'ISP-JELANTIK', 'Raswa Jaya - WRG-A3-15-Raswa - 192.168.40.24', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 533),
(533, 'Isnawati', 'WRG-B5-5-Isnawati', '192.168.40.25', 'ISP-JELANTIK', 'Isnawati - WRG-B5-5-Isnawati - 192.168.40.25', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 534),
(534, 'Reza Eka Putra', 'WRG-B4-7-Reza', '192.168.40.26', 'ISP-JELANTIK', 'Reza Eka Putra - WRG-B4-7-Reza - 192.168.40.26', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 535),
(535, 'Lis Budianto', 'WRG-B3-06-Budianto', '192.168.40.27', 'ISP-JELANTIK', 'Lis Budianto - WRG-B3-06-Budianto - 192.168.40.27', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 536),
(536, 'Najmudin', 'WRG-A2-19-Najmudin', '192.168.40.28', 'ISP-JELANTIK', 'Najmudin - WRG-A2-19-Najmudin - 192.168.40.28', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 537),
(537, 'Nadia Rahmahdani', 'WRG-A7-6-Nadia', '192.168.40.29', 'ISP-JELANTIK', 'Nadia Rahmahdani - WRG-A7-6-Nadia - 192.168.40.29', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 538),
(538, 'Dila Upitasari', 'WRG-A7-11-Dila', '192.168.40.30', 'ISP-JELANTIK', 'Dila Upitasari - WRG-A7-11-Dila - 192.168.40.30', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 539),
(539, 'Dinda Rizki Aprilia', 'WRG-B6-26-Dinda', '192.168.40.31', 'ISP-JELANTIK', 'Dinda Rizki Aprilia - WRG-B6-26-Dinda - 192.168.40.31', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 540),
(540, 'Tubagus Yahya ramadhan Suhendar', 'WRG-A3-18-Yahya', '192.168.40.32', 'ISP-JELANTIK', 'Tubagus Yahya ramadhan Suhendar - WRG-A3-18-Yahya - 192.168.40.32', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 541),
(541, 'Yudha Bagus Asmoro', 'WRG-A2-7-Yudha', '192.168.40.33', 'ISP-JELANTIK', 'Yudha Bagus Asmoro - WRG-A2-7-Yudha - 192.168.40.33', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 542),
(542, 'Tuty rukmana', 'WRG-A2-5-Tuty', '192.168.40.34', 'ISP-JELANTIK', 'Tuty rukmana - WRG-A2-5-Tuty - 192.168.40.34', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 543),
(544, 'Riyanto', 'CKG-KM2-A-406-20-0623-Riyanto', '101.101.100.79', 'ISP-JAKINET', 'Riyanto - CKG-KM2-A-406-20-0623-Riyanto - 101.101.100.79', '2025-01-21 21:59:33', '2025-01-21 21:59:33', 1),
(546, 'Ummi Hasanah', 'PIN-A5-317-Ummi', '101.101.100.63', 'ISP-JAKINET', 'Ummi Hasanah - PIN-A5-317-Ummi - 101.101.100.63', '2025-01-21 16:44:38', '2025-01-21 16:44:38', 544),
(547, 'Abdul Muin', 'CKG-TPR-PSP-108-Abdul', '102.102.100.65', 'ISP-JAKINET', 'Abdul Muin - CKG-TPR-PSP-108-Abdul - 102.102.100.65', '2025-01-21 16:48:46', '2025-01-21 16:48:46', 545),
(548, 'Stevi', 'PGB-BLOK-A-319-Stevi', '103.103.100.203', 'ISP-JAKINET', 'Stevi - PGB-BLOK-A-319-Stevi - 103.103.100.203', '2025-01-21 16:51:02', '2025-01-21 16:51:02', 546),
(550, 'Hayati', 'NGR-TWR3-1106-Hayati', '192.168.50.62', 'ISP-JELANTIK', 'Hayati - NGR-TWR3-1106-Hayati - 192.168.50.62', '2025-01-21 16:52:53', '2025-01-21 16:52:53', 547),
(551, 'Ade Kurniawan', 'PIN-RUKO-A1-408-Ade', '101.101.100.60', 'ISP-JAKINET', 'Ade Kurniawan - PIN-RUKO-A1-408-Ade - 101.101.100.60', '2025-01-21 16:55:55', '2025-01-21 16:55:55', 548),
(552, 'Sunariyah', 'PIN-A-A3-308-Sunariyah', '101.101.100.58', 'ISP-JAKINET', 'Sunariyah - PIN-A-A3-308-Sunariyah - 101.101.100.58', '2025-01-21 16:57:55', '2025-01-21 16:57:55', 549);
INSERT INTO `customers` (`id`, `name`, `customer_id`, `ip_address`, `service`, `composite_data`, `created_at`, `updated_at`, `no`) VALUES
(553, 'Winda Yuliani ', 'PGB-BLOK-B-208-Winda', '103.103.100.10', 'ISP-JAKINET', 'Winda Yuliani  - PGB-BLOK-B-208-Winda - 103.103.100.10', '2025-01-21 17:00:27', '2025-01-21 17:00:27', 550),
(554, 'Rahman Edy', 'NGR-TWR3-411-Rahman', '192.168.50.60', 'ISP-JELANTIK', 'Rahman Edy - NGR-TWR3-411-Rahman - 192.168.50.60', '2025-01-21 17:01:55', '2025-01-21 17:01:55', 551),
(555, 'Rommy Michael Rattu', 'PGB-BLOK-C1-14-Rommy', '103.103.100.202', 'ISP-JAKINET', 'Rommy Michael Rattu - PGB-BLOK-C1-14-Rommy - 103.103.100.202', '2025-01-21 17:03:39', '2025-01-21 17:03:39', 552),
(556, 'Asep Firmansyah', 'PIN-A6-317-Asep', '101.101.100.57', 'ISP-JAKINET', 'Asep Firmansyah - PIN-A6-317-Asep - 101.101.100.57', '2025-01-21 17:06:19', '2025-01-21 17:06:19', 553),
(557, 'Payaman tobing', 'PGB-TWR-141-Payaman', '103.103.100.201', 'ISP-JAKINET', 'Payaman tobing - PGB-TWR-141-Payaman - 103.103.100.201', '2025-01-21 17:07:26', '2025-01-21 17:07:26', 554),
(558, 'Felix Beneditus Larosa', 'PGB-TWR-817-Felix', '103.103.100.200', 'ISP-JAKINET', 'Felix Beneditus Larosa - PGB-TWR-817-Felix - 103.103.100.200', '2025-01-21 17:08:56', '2025-01-21 17:08:56', 555),
(559, 'Tina Suryani', 'PGB-F-210-Tina', '103.103.100.199', 'ISP-JAKINET', 'Tina Suryani - PGB-F-210-Tina - 103.103.100.199', '2025-01-21 17:10:04', '2025-01-21 17:10:04', 556),
(560, 'Sri Mulyani', 'NGR-TWR2-905-Mulyani', '192.168.50.59', 'ISP-JELANTIK', 'Sri Mulyani - NGR-TWR2-905-Mulyani - 192.168.50.59', '2025-01-21 17:11:05', '2025-01-21 17:11:05', 557),
(561, 'Zuyyina Anta Zakka', 'PRM-A5-6-Zuyyina', '192.168.60.40', 'ISP-JELANTIK', 'Zuyyina Anta Zakka - PRM-A5-6-Zuyyina - 192.168.60.40', '2025-01-21 17:13:09', '2025-01-21 17:13:09', 558),
(562, 'Jaenudin', 'TMB-KBJ-C148-Jaenudin', '192.168.30.8', 'ISP-JELANTIK', 'Jaenudin - TMB-KBJ-C148-Jaenudin - 192.168.30.8', '2025-02-11 07:39:29', '2025-02-11 07:39:29', 559),
(563, 'Ginanjar Dwi saputra', 'PIN-A3-302-Ginanjar', '101.101.100.73', 'ISP-JAKINET', 'Ginanjar Dwi saputra - PIN-A3-302-Ginanjar - 101.101.100.73', '2025-02-11 07:40:34', '2025-02-11 07:40:34', 560),
(564, 'Supriyatiningsih', 'CKG-KM2-A-418-Supriyatiningsih', '101.101.100.69', 'ISP-JAKINET', 'Supriyatiningsih - CKG-KM2-A-418-Supriyatiningsih - 101.101.100.69', '2025-02-11 07:41:08', '2025-02-11 07:41:08', 561),
(565, 'Regina', 'PGB-BLOK-F-402-Regina', '103.103.100.208', 'ISP-JAKINET', 'Regina - PGB-BLOK-F-402-Regina - 103.103.100.208', '2025-02-11 07:41:35', '2025-02-11 07:41:35', 562),
(566, 'Febriyanto Yulian', 'PIN-A5-108-Febriyanto', '101.101.100.67', 'ISP-JAKINET', 'Febriyanto Yulian - PIN-A5-108-Febriyanto - 101.101.100.67', '2025-02-11 07:42:05', '2025-02-11 07:42:05', 563),
(567, 'Ian Arief', 'PGB-BLOK-C-206-Arief', '103.103.100.207', 'ISP-JAKINET', 'Ian Arief - PGB-BLOK-C-206-Arief - 103.103.100.207', '2025-02-11 07:43:03', '2025-02-11 07:43:03', 564),
(568, 'Bambang', 'PGB-TWR-1105-Bambang', '103.103.100.206', 'ISP-JAKINET', 'Bambang - PGB-TWR-1105-Bambang - 103.103.100.206', '2025-02-11 07:43:30', '2025-02-11 07:43:30', 565),
(569, 'Risna Sari', 'PGB-BLOK-A-415-Risna', '103.103.100.204', 'ISP-JAKINET', 'Risna Sari - PGB-BLOK-A-415-Risna - 103.103.100.204', '2025-02-11 07:44:03', '2025-02-11 07:44:03', 566);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(10, '2025_01_21_063438_create_s_l_a_s_table', 6),
(13, '2025_01_21_110613_create_permissions_table', 9),
(14, '2025_01_21_110614_create_roles_table', 9),
(15, '2025_01_21_110620_create_user_logs_table', 10),
(16, '2025_01_21_112846_add_role_to_users_table', 11),
(17, '2025_01_21_115044_add_role_to_users_table', 12),
(24, '2025_02_02_062503_evidance', 15),
(28, '0001_01_01_000000_create_users_table', 16),
(29, '0001_01_01_000001_create_cache_table', 16),
(30, '0001_01_01_000002_create_jobs_table', 16),
(31, '2025_01_20_122914_create_customers_table', 16),
(32, '2025_01_20_123613_add_no_to_customers_table', 16),
(33, '2025_01_20_140132_create_tickets_table', 16),
(34, '2025_01_20_151555_add_problem_summary_and_extra_description_to_tickets_table', 16),
(35, '2025_01_21_063438_create_slas_table', 16),
(36, '2025_01_21_072620_add_sla_id_to_tickets_table', 16),
(37, '2025_01_21_130104_create_logs_table', 16),
(38, '2025_01_22_074047_add_role_to_users_table', 16),
(39, '2025_02_02_140501_add_evidance_column_to_tickets_table', 16),
(40, '2025_02_03_144418_create_backbone_c_i_d_s_table', 16),
(41, '2025_02_03_155703_create_ticket_backbones_table', 17),
(42, '2025_02_09_055019_add_jenis_isp_to_ticket_backbones', 18),
(43, '2025_02_09_055631_add_created_by_to_ticket_backbones_table', 19),
(44, '2025_02_09_102032_create_activity_log_table', 20),
(45, '2025_02_09_102033_add_event_column_to_activity_log_table', 20),
(46, '2025_02_09_102034_add_batch_uuid_column_to_activity_log_table', 20),
(47, '2025_02_09_103703_create_log_activities_table', 21),
(48, '2025_02_09_215530_create_activity_log_table', 22),
(49, '2025_02_09_221022_create_activity_log_table', 23),
(50, '2025_02_09_221023_add_event_column_to_activity_log_table', 23),
(51, '2025_02_09_221024_add_batch_uuid_column_to_activity_log_table', 23),
(52, '2025_02_10_134657_create_user_pelanggan_table', 24),
(53, '2025_02_10_143748_create_pelanggan_table', 25),
(54, '2025_02_10_151628_create_user_pelanggan_table', 26),
(55, '2025_02_10_151725_create_ticket_updates_table', 26),
(56, '2025_02_10_160547_create_user_pelanggan', 27),
(57, '0001_01_01_000000_create_users_pelanggan_table', 28),
(58, '2025_02_10_173332_create_pelanggan_table', 29);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('vSSgQ2TXqrWLzqeTa43xG9XUWBQO4JemKSgtIxeg', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 Edg/132.0.0.0', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiVlNvVG9nT1lIejJHdWRGRURVbXZjb3hoelpqY1FuRnpTcURSOFB1YiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vbG9nLWFjdGl2aXRpZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkYS9UaVBoU29uQmJ5UG5CYmxUZUV5LjZFVGJOdlg0YnNqWjd0bS92aXgwYzFFdjdrcC5BZWkiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fXM6MTY6ImZpbHRlcmVkX3RpY2tldHMiO086Mzk6IklsbHVtaW5hdGVcRGF0YWJhc2VcRWxvcXVlbnRcQ29sbGVjdGlvbiI6Mjp7czo4OiIAKgBpdGVtcyI7YTowOnt9czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO319', 1739264625);

-- --------------------------------------------------------

--
-- Table structure for table `s_l_a_s`
--

CREATE TABLE `s_l_a_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `response_time` int(11) NOT NULL,
  `resolution_time` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `s_l_a_s`
--

INSERT INTO `s_l_a_s` (`id`, `name`, `response_time`, `resolution_time`, `created_at`, `updated_at`) VALUES
(1, 'MEDIUM', 1, 2, '2025-01-20 17:07:58', '2025-01-20 20:49:56'),
(2, 'HIGH', 1, 4, '2025-01-20 17:09:08', '2025-01-20 20:50:09'),
(3, 'LOW', 1, 1, '2025-01-20 17:14:12', '2025-01-20 20:50:22');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no` int(11) NOT NULL,
  `service` varchar(255) NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'OPEN',
  `pending_clock` timestamp NULL DEFAULT NULL,
  `closed_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `problem_summary` varchar(255) DEFAULT NULL,
  `extra_description` text DEFAULT NULL,
  `sla_id` bigint(20) UNSIGNED DEFAULT NULL,
  `evidance_path` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_backbones`
--

CREATE TABLE `ticket_backbones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `no_ticket` varchar(255) NOT NULL,
  `cid` bigint(20) UNSIGNED NOT NULL,
  `jenis_isp` varchar(255) DEFAULT NULL,
  `lokasi_id` bigint(20) UNSIGNED NOT NULL,
  `extra_description` text DEFAULT NULL,
  `status` enum('OPEN','PENDING','CLOSED') NOT NULL DEFAULT 'OPEN',
  `open_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `pending_date` timestamp NULL DEFAULT NULL,
  `closed_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'Helpdesk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'admin', 'admin@helpdesk.net', NULL, '$2y$12$a/TiPhSonBbyPnBblTeEy.6ETbNvX4bsjZ7tm/vix0c1Ev7kp.Aei', '4Np40jA9AGwWEvns67RxwknqqTxpmv2IMN0JeIKfW69sN75fdGWexWYGHMMr', '2025-01-19 22:18:11', '2025-01-21 18:08:50', 'Admin'),
(2, 'Ahmad', 'ahmad@helpdesk.net', NULL, '$2y$12$zOgF/2SoxvAR7dncNZfi4O/M/taYRdwfd4Otr7wG5MiNSdv2T7TIC', NULL, '2025-01-21 18:13:04', '2025-01-21 18:13:04', 'Helpdesk'),
(3, 'NOC-Baihaqi', 'baihaqi@noc.com', NULL, '$2y$12$abHG74wKX6v3dyML2Tm2VONliE45XU7Y2yJHk2G6h.SuZsFPWn5JG', NULL, '2025-01-21 18:45:14', '2025-01-21 18:45:14', 'Helpdesk'),
(4, 'NOC-Dani', 'dani@noc.com', NULL, '$2y$12$yVTrCAkycGsb63hz5IOdju8MaodLrDJBWIu6CJSZEjMiW7VTmXP7e', 'MgNpZg0JO7lU87gYdo3TUxeTfdwrDF9WwuuWi3JeWUiDa44JtHokapiJcIPH', '2025-01-21 18:45:48', '2025-01-21 18:45:48', 'Helpdesk'),
(5, 'NOC-Irsyad', 'irsyad@noc.com', NULL, '$2y$12$UuGPIAi5naGbeDonWGvQq.yMCGw2XuBNhj7ew6Ha4gW0LVdKHvoIC', NULL, '2025-01-21 18:46:22', '2025-01-21 18:46:22', 'Helpdesk'),
(6, 'NOC-Rizki', 'rizki@noc.com', NULL, '$2y$12$x8JfTz3HPDgwiuwp/HhOv.Wg23ArMzu6P3nIitZxmVNvbP.sx4UVq', NULL, '2025-01-21 18:46:48', '2025-01-21 18:46:48', 'Helpdesk'),
(7, 'NOC-Koko', 'koko@noc.com', NULL, '$2y$12$4LsdeFQX/2lqS4zGNrwRhuB8ZAsHxxM2XhfVkx0k.VH.wCFsSW1V6', 'TIdx9ePgNQnI454sQBNt90vCjDpjDA3Kg7qotK7VUUQD43Xyj9vdMRi06e0X', '2025-01-21 22:19:42', '2025-01-21 22:19:42', 'Helpdesk');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `backbone_cids`
--
ALTER TABLE `backbone_cids`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `backbone_cids_cid_unique` (`cid`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_customer_id_unique` (`customer_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `s_l_a_s`
--
ALTER TABLE `s_l_a_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_no_unique` (`no`),
  ADD UNIQUE KEY `tickets_ticket_number_unique` (`ticket_number`),
  ADD KEY `tickets_customer_id_foreign` (`customer_id`),
  ADD KEY `tickets_sla_id_foreign` (`sla_id`);

--
-- Indexes for table `ticket_backbones`
--
ALTER TABLE `ticket_backbones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_backbones_no_ticket_unique` (`no_ticket`),
  ADD KEY `ticket_backbones_cid_foreign` (`cid`),
  ADD KEY `ticket_backbones_lokasi_id_foreign` (`lokasi_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `backbone_cids`
--
ALTER TABLE `backbone_cids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=573;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `s_l_a_s`
--
ALTER TABLE `s_l_a_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ticket_backbones`
--
ALTER TABLE `ticket_backbones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_sla_id_foreign` FOREIGN KEY (`sla_id`) REFERENCES `s_l_a_s` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_backbones`
--
ALTER TABLE `ticket_backbones`
  ADD CONSTRAINT `ticket_backbones_cid_foreign` FOREIGN KEY (`cid`) REFERENCES `backbone_cids` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_backbones_lokasi_id_foreign` FOREIGN KEY (`lokasi_id`) REFERENCES `backbone_cids` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
