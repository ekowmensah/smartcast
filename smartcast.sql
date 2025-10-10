-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 03:09 PM
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
-- Database: `smartcast`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-03 12:00:03'),
(2, 1, 'logout', '[]', '::1', '2025-10-03 12:02:40'),
(3, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-03 12:02:42'),
(4, 1, 'logout', '[]', '::1', '2025-10-03 13:02:27'),
(5, 3, 'login', '{\"email\":\"platform@votesaas.com\",\"success\":true}', '::1', '2025-10-03 13:03:01'),
(6, 3, 'logout', '[]', '::1', '2025-10-03 13:07:41'),
(7, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-03 13:07:43'),
(8, 1, 'logout', '[]', '::1', '2025-10-03 13:07:59'),
(9, 3, 'login', '{\"email\":\"platform@votesaas.com\",\"success\":true}', '::1', '2025-10-03 13:08:14'),
(10, 3, 'logout', '[]', '::1', '2025-10-03 13:13:57'),
(11, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-03 13:14:08'),
(12, 1, 'logout', '[]', '::1', '2025-10-03 13:14:38'),
(13, 2, 'login', '{\"email\":\"manager@demo.com\",\"success\":true}', '::1', '2025-10-03 13:14:49'),
(14, 2, 'logout', '[]', '::1', '2025-10-03 13:15:15'),
(15, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-03 13:15:22'),
(16, 1, 'logout', '[]', '::1', '2025-10-03 13:21:05'),
(17, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-03 13:43:13'),
(18, 1, 'logout', '[]', '::1', '2025-10-03 14:28:29'),
(19, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-03 14:31:18'),
(20, 4, 'logout', '[]', '::1', '2025-10-03 14:37:14'),
(21, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-03 14:46:16'),
(22, 4, 'logout', '[]', '::1', '2025-10-03 14:50:23'),
(23, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-03 14:50:31'),
(24, 4, 'logout', '[]', '::1', '2025-10-03 15:34:41'),
(25, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-03 15:34:48'),
(26, 1, 'event_created', '{\"event_id\":\"3\",\"name\":\"Ghana Music Awards\"}', '::1', '2025-10-03 15:51:10'),
(27, NULL, 'login_failed', '{\"email\":\"ekowme@gmail.com\",\"ip\":\"::1\"}', '::1', '2025-10-05 03:20:54'),
(28, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-05 03:21:00'),
(29, NULL, 'login_failed', '{\"email\":\"ekowme@gmail.com\",\"ip\":\"::1\"}', '::1', '2025-10-05 04:12:33'),
(30, NULL, 'login_failed', '{\"email\":\"ekowme@gmail.com\",\"ip\":\"::1\"}', '::1', '2025-10-05 04:12:38'),
(31, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-05 04:12:41'),
(32, 4, 'contestant_created', '{\"contestant_id\":\"5\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-05 04:20:10'),
(33, 4, 'contestant_deleted', '{\"contestant_id\":\"4\",\"name\":\"Sarah Wilson\"}', '::1', '2025-10-05 04:20:33'),
(34, 4, 'contestant_created', '{\"contestant_id\":\"6\",\"name\":\"Sean Paull\"}', '::1', '2025-10-05 04:20:48'),
(35, 1, 'contestant_deleted', '{\"contestant_id\":\"3\",\"name\":\"Mike Johnson\"}', '::1', '2025-10-05 04:21:38'),
(36, 1, 'contestant_deleted', '{\"contestant_id\":\"3\",\"name\":\"Mike Johnson\"}', '::1', '2025-10-05 04:23:26'),
(37, 1, 'contestant_created', '{\"contestant_id\":\"7\",\"name\":\"Ekow Mensah\"}', '::1', '2025-10-05 04:23:38'),
(38, 1, 'contestant_updated', '{\"contestant_id\":\"7\",\"name\":\"Ekow Mensah\"}', '::1', '2025-10-05 04:23:46'),
(39, 1, 'contestant_updated', '{\"contestant_id\":\"3\",\"name\":\"Mike Johnson\"}', '::1', '2025-10-05 04:23:52'),
(40, 1, 'contestant_created', '{\"contestant_id\":\"8\",\"name\":\"Event Two Nominee\"}', '::1', '2025-10-05 04:26:16'),
(41, 1, 'contestant_updated', '{\"contestant_id\":\"2\",\"name\":\"Jane Smith\"}', '::1', '2025-10-05 04:26:31'),
(42, 1, 'contestant_updated', '{\"contestant_id\":\"2\",\"name\":\"Jane Smith\"}', '::1', '2025-10-05 04:26:40'),
(43, 1, 'contestant_updated', '{\"contestant_id\":\"1\",\"name\":\"John Doe\"}', '::1', '2025-10-05 04:26:53'),
(44, 1, 'contestant_updated', '{\"contestant_id\":\"3\",\"name\":\"Mike Johnson\"}', '::1', '2025-10-05 04:27:01'),
(45, 1, 'contestant_deactivated', '{\"contestant_id\":\"2\",\"name\":\"Jane Smith\"}', '::1', '2025-10-05 04:28:04'),
(46, 1, 'event_updated', '{\"event_id\":\"1\",\"name\":\"Music Voting Event 2024\"}', '::1', '2025-10-05 04:52:03'),
(47, 1, 'event_updated', '{\"event_id\":\"3\",\"name\":\"Music Awards 2025\"}', '::1', '2025-10-05 04:54:19'),
(48, 1, 'event_deleted', '{\"event_id\":\"1\",\"name\":\"Music Voting Event 2024\"}', '::1', '2025-10-05 04:54:26'),
(49, 1, 'event_deleted', '{\"event_id\":\"3\",\"name\":\"Music Awards 2025\"}', '::1', '2025-10-05 04:54:30'),
(50, 1, 'event_deleted', '{\"event_id\":\"1\",\"name\":\"Music Voting Event 2024\"}', '::1', '2025-10-05 04:55:18'),
(51, 1, 'event_updated', '{\"event_id\":\"3\",\"name\":\"Music Awards 2025\"}', '::1', '2025-10-05 04:55:26'),
(52, 1, 'event_updated', '{\"event_id\":\"3\",\"name\":\"Music Awards 2024\"}', '::1', '2025-10-05 04:55:36'),
(53, 1, 'contestant_created', '{\"contestant_id\":\"10\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-05 05:01:39'),
(54, 1, 'contestant_created', '{\"contestant_id\":\"11\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-05 05:01:59'),
(55, 1, 'event_created', '{\"event_id\":\"4\",\"name\":\"Ghana Music Awards 2025\"}', '::1', '2025-10-05 05:07:33'),
(56, 1, 'contestant_created', '{\"contestant_id\":\"12\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-05 05:11:36'),
(57, 4, 'contestant_created', '{\"contestant_id\":\"13\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-05 05:12:05'),
(58, 1, 'event_updated', '{\"event_id\":\"4\",\"name\":\"Ghana Music Awards 2025\"}', '::1', '2025-10-05 05:13:28'),
(59, 4, 'event_created', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-05 05:14:44'),
(60, 1, 'event_updated', '{\"event_id\":\"4\",\"name\":\"Ghana Music Awards 2025\"}', '::1', '2025-10-05 05:20:18'),
(61, 4, 'contestant_reactivated', '{\"contestant_id\":\"13\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-05 05:22:54'),
(62, 1, 'contestant_reactivated', '{\"contestant_id\":\"12\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-05 05:23:02'),
(63, 4, 'event_created', '{\"event_id\":\"6\",\"name\":\"Breman Excellence Awards\"}', '::1', '2025-10-05 05:40:43'),
(64, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-05 05:46:38'),
(65, 4, 'event_updated', '{\"event_id\":\"6\",\"name\":\"Breman Excellence Awards\"}', '::1', '2025-10-05 05:46:46'),
(66, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-05 05:47:56'),
(67, 1, 'event_updated', '{\"event_id\":\"4\",\"name\":\"Ghana Music Awards 2025\"}', '::1', '2025-10-05 06:56:43'),
(68, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-05 07:13:22'),
(69, 4, 'event_updated', '{\"event_id\":\"6\",\"name\":\"Breman Excellence Awards\"}', '::1', '2025-10-05 07:15:43'),
(70, 1, 'event_updated', '{\"event_id\":\"4\",\"name\":\"Ghana Music Awards 2025\"}', '::1', '2025-10-05 09:23:20'),
(71, NULL, 'login_failed', '{\"email\":\"ekowme@gmail.com\",\"ip\":\"::1\"}', '::1', '2025-10-06 12:08:10'),
(72, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-06 12:08:14'),
(73, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-06 12:15:26'),
(74, 4, 'event_status_changed', '{\"event_id\":\"6\",\"active\":false}', '::1', '2025-10-06 12:17:07'),
(75, 4, 'event_status_changed', '{\"event_id\":\"6\",\"active\":true}', '::1', '2025-10-06 12:17:15'),
(76, 4, 'event_status_changed', '{\"event_id\":\"6\",\"active\":false}', '::1', '2025-10-06 12:17:20'),
(77, 4, 'event_status_changed', '{\"event_id\":\"6\",\"active\":true}', '::1', '2025-10-06 12:17:38'),
(78, 4, 'event_results_visibility_changed', '{\"event_id\":\"5\",\"results_visible\":true}', '::1', '2025-10-06 12:28:25'),
(79, 4, 'event_results_visibility_changed', '{\"event_id\":\"6\",\"results_visible\":false}', '::1', '2025-10-06 12:28:44'),
(80, 4, 'event_results_visibility_changed', '{\"event_id\":\"5\",\"results_visible\":false}', '::1', '2025-10-06 12:28:56'),
(81, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-06 12:38:26'),
(82, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-06 12:44:45'),
(83, 4, 'event_activated', '{\"event_id\":\"5\",\"event_name\":\"Ghana Music Awards 25\",\"previous_status\":\"draft\"}', '::1', '2025-10-06 12:45:10'),
(84, 4, 'event_status_changed', '{\"event_id\":\"5\",\"active\":false}', '::1', '2025-10-06 12:45:15'),
(85, 4, 'event_created', '{\"event_id\":\"7\",\"name\":\"Ghana&#039;s Most Beautiful\"}', '::1', '2025-10-06 12:51:55'),
(86, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&amp;#039;s Most Beautiful\"}', '::1', '2025-10-06 12:52:07'),
(87, 3, 'logout', '[]', '::1', '2025-10-06 12:57:43'),
(88, NULL, 'login_failed', '{\"email\":\"ekowme@gmail.com\",\"ip\":\"::1\"}', '::1', '2025-10-06 12:57:45'),
(89, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-06 12:57:50'),
(90, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-06 13:03:52'),
(91, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&amp;amp;#039;s Most Beautiful\"}', '::1', '2025-10-06 13:06:45'),
(92, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&#039;s Most Beautiful\"}', '::1', '2025-10-06 13:16:26'),
(93, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&#039;s Most Beautiful\"}', '::1', '2025-10-06 13:16:51'),
(94, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&amp;#039;s Most Beautiful\"}', '::1', '2025-10-06 13:17:32'),
(95, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&amp;amp;#039;s Most Beautiful\"}', '::1', '2025-10-06 13:19:58'),
(96, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&amp;amp;amp;#039;s Most Beautiful\"}', '::1', '2025-10-06 13:21:12'),
(97, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-06 13:21:22'),
(98, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-06 13:21:45'),
(99, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&amp;amp;amp;amp;#039;s Most Beautiful\"}', '::1', '2025-10-06 13:22:17'),
(100, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&amp;amp;amp;amp;amp;#039;s Most Beautiful\"}', '::1', '2025-10-06 13:25:06'),
(101, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&#039;s Most Beautiful\"}', '::1', '2025-10-06 13:27:17'),
(102, 4, 'bundle_created', '{\"bundle_id\":\"7\",\"name\":\"Premium\"}', '::1', '2025-10-06 13:30:34'),
(103, 4, 'bundle_updated', '{\"bundle_id\":\"7\",\"name\":\"Premium\"}', '::1', '2025-10-06 13:30:43'),
(104, 4, 'bundle_updated', '{\"bundle_id\":\"7\",\"name\":\"Premium\"}', '::1', '2025-10-06 13:31:30'),
(105, 4, 'event_updated', '{\"event_id\":\"7\",\"name\":\"Ghana&amp;#039;s Most Beautiful\"}', '::1', '2025-10-06 13:32:28'),
(106, 4, 'event_updated', '{\"event_id\":\"6\",\"name\":\"Breman Excellence Awards\"}', '::1', '2025-10-06 13:33:48'),
(107, 4, 'event_updated', '{\"event_id\":\"5\",\"name\":\"Ghana Music Awards 25\"}', '::1', '2025-10-06 13:36:36'),
(108, 4, 'event_results_visibility_changed', '{\"event_id\":\"5\",\"results_visible\":true}', '::1', '2025-10-06 13:40:23'),
(109, 4, 'event_results_visibility_changed', '{\"event_id\":\"6\",\"results_visible\":true}', '::1', '2025-10-06 13:49:38'),
(110, 4, 'contestant_updated', '{\"contestant_id\":\"13\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-06 14:00:30'),
(111, 4, 'contestant_quick_created', '{\"contestant_id\":\"17\",\"name\":\"Susubiribi\",\"via_wizard\":true}', '::1', '2025-10-08 08:22:36'),
(112, 4, 'contestant_updated', '{\"contestant_id\":\"13\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-08 08:23:17'),
(113, 4, 'contestant_updated', '{\"contestant_id\":\"13\",\"name\":\"Kofi Weather\"}', '::1', '2025-10-08 09:58:35'),
(114, NULL, 'login_failed', '{\"email\":\"admin@demo.com\",\"ip\":\"::1\"}', '::1', '2025-10-08 10:39:09'),
(115, 1, 'login', '{\"email\":\"admin@demo.com\",\"success\":true}', '::1', '2025-10-08 10:39:41'),
(116, NULL, 'login_failed', '{\"email\":\"ekowme@gmail.com\",\"ip\":\"::1\"}', '::1', '2025-10-08 17:25:53'),
(117, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-08 17:25:58'),
(118, 4, 'logout', '[]', '::1', '2025-10-08 19:13:27'),
(119, 5, 'user_registered', '{\"tenant_id\":\"3\"}', '::1', '2025-10-08 19:16:34'),
(120, 5, 'login', '{\"email\":\"ekowmeee@gmail.com\",\"success\":true}', '::1', '2025-10-08 19:20:15'),
(121, 5, 'login', '{\"email\":\"ekowmeee@gmail.com\",\"success\":true}', '::1', '2025-10-08 19:20:26'),
(122, 5, 'logout', '[]', '::1', '2025-10-08 19:20:34'),
(123, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-08 19:20:43'),
(124, 4, 'logout', '[]', '::1', '2025-10-08 19:20:53'),
(125, 5, 'login', '{\"email\":\"ekowmeee@gmail.com\",\"success\":true}', '::1', '2025-10-08 19:20:59'),
(126, 5, 'logout', '[]', '::1', '2025-10-08 19:21:08'),
(127, 3, 'login', '{\"email\":\"ekowme@gmail.comm\",\"success\":true}', '::1', '2025-10-08 19:21:32'),
(128, 3, 'logout', '[]', '::1', '2025-10-08 19:23:41'),
(129, 5, 'login', '{\"email\":\"ekowmeee@gmail.com\",\"success\":true}', '::1', '2025-10-08 19:23:44'),
(130, 5, 'logout', '[]', '::1', '2025-10-08 19:24:10'),
(131, 2, 'login', '{\"email\":\"manager@demo.com\",\"success\":true}', '::1', '2025-10-08 19:24:48'),
(132, 2, 'logout', '[]', '::1', '2025-10-08 19:27:38'),
(133, 3, 'login', '{\"email\":\"ekowme@gmail.comm\",\"success\":true}', '::1', '2025-10-08 19:27:52'),
(134, 3, 'logout', '[]', '::1', '2025-10-08 19:38:46'),
(135, 3, 'login', '{\"email\":\"ekowme@gmail.comm\",\"success\":true}', '::1', '2025-10-08 19:39:22'),
(136, 3, 'logout', '[]', '::1', '2025-10-08 19:41:56'),
(137, 5, 'login', '{\"email\":\"ekowmeee@gmail.com\",\"success\":true}', '::1', '2025-10-08 19:41:59'),
(138, 3, 'login', '{\"email\":\"ekowme@gmail.comm\",\"success\":true}', '::1', '2025-10-08 20:10:45'),
(139, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-08 20:19:42'),
(140, 5, 'logout', '[]', '::1', '2025-10-09 01:06:16'),
(141, 5, 'login', '{\"email\":\"ekowmeee@gmail.com\",\"success\":true}', '::1', '2025-10-09 05:28:59'),
(142, NULL, 'vote_cast', '{\"transaction_id\":13,\"event_id\":23,\"contestant_id\":41,\"amount\":\"80.00\"}', '::1', '2025-10-09 07:13:48'),
(143, NULL, 'vote_cast', '{\"transaction_id\":14,\"event_id\":23,\"contestant_id\":40,\"amount\":\"300.00\"}', '::1', '2025-10-09 07:16:05'),
(144, NULL, 'vote_cast', '{\"transaction_id\":15,\"event_id\":23,\"contestant_id\":40,\"amount\":\"600.00\"}', '::1', '2025-10-09 07:16:58'),
(145, NULL, 'vote_cast', '{\"transaction_id\":16,\"event_id\":23,\"contestant_id\":39,\"amount\":\"1800.00\"}', '::1', '2025-10-09 07:24:55'),
(146, NULL, 'vote_cast', '{\"transaction_id\":17,\"event_id\":23,\"contestant_id\":39,\"amount\":\"1800.00\"}', '::1', '2025-10-09 07:27:16'),
(147, NULL, 'vote_cast', '{\"transaction_id\":18,\"event_id\":23,\"contestant_id\":41,\"amount\":\"300.00\"}', '::1', '2025-10-09 07:28:20'),
(148, NULL, 'vote_cast', '{\"transaction_id\":19,\"event_id\":23,\"contestant_id\":41,\"amount\":\"1200.00\"}', '::1', '2025-10-09 07:39:07'),
(149, NULL, 'vote_cast', '{\"transaction_id\":20,\"event_id\":23,\"contestant_id\":41,\"amount\":\"3600.00\"}', '::1', '2025-10-09 07:42:58'),
(150, NULL, 'vote_cast', '{\"transaction_id\":22,\"event_id\":23,\"contestant_id\":41,\"amount\":\"600.00\"}', '::1', '2025-10-09 07:44:27'),
(151, NULL, 'vote_cast', '{\"transaction_id\":23,\"event_id\":23,\"contestant_id\":39,\"amount\":\"1800.00\"}', '::1', '2025-10-09 07:47:29'),
(152, NULL, 'vote_cast', '{\"transaction_id\":24,\"event_id\":22,\"contestant_id\":36,\"amount\":\"90.00\"}', '::1', '2025-10-09 08:23:10'),
(153, NULL, 'vote_cast', '{\"transaction_id\":25,\"event_id\":24,\"contestant_id\":42,\"amount\":\"14.00\"}', '::1', '2025-10-09 08:23:59'),
(154, NULL, 'vote_cast', '{\"transaction_id\":28,\"event_id\":24,\"contestant_id\":42,\"amount\":\"2.40\"}', '::1', '2025-10-09 08:52:57'),
(155, NULL, 'vote_cast', '{\"transaction_id\":29,\"event_id\":24,\"contestant_id\":42,\"amount\":\"16.00\"}', '::1', '2025-10-09 09:06:11'),
(156, NULL, 'vote_cast', '{\"transaction_id\":30,\"event_id\":24,\"contestant_id\":42,\"amount\":\"80.00\"}', '::1', '2025-10-09 09:09:31'),
(157, NULL, 'vote_cast', '{\"transaction_id\":32,\"event_id\":24,\"contestant_id\":46,\"amount\":\"6.40\"}', '::1', '2025-10-09 09:12:53'),
(158, NULL, 'vote_cast', '{\"transaction_id\":33,\"event_id\":24,\"contestant_id\":46,\"amount\":\"6.40\"}', '::1', '2025-10-09 09:13:40'),
(159, NULL, 'vote_cast', '{\"transaction_id\":34,\"event_id\":24,\"contestant_id\":43,\"amount\":\"90.00\"}', '::1', '2025-10-09 09:22:17'),
(160, NULL, 'vote_cast', '{\"transaction_id\":36,\"event_id\":24,\"contestant_id\":44,\"amount\":\"7200.00\"}', '::1', '2025-10-09 09:50:32'),
(161, 3, 'login', '{\"email\":\"ekowme@gmail.comm\",\"success\":true}', '::1', '2025-10-09 09:54:03'),
(162, NULL, 'vote_cast', '{\"transaction_id\":37,\"event_id\":22,\"contestant_id\":37,\"amount\":\"1.00\"}', '::1', '2025-10-09 12:06:08'),
(163, NULL, 'vote_cast', '{\"transaction_id\":38,\"event_id\":23,\"contestant_id\":40,\"amount\":\"35.00\"}', '::1', '2025-10-09 12:09:44'),
(164, NULL, 'vote_cast', '{\"transaction_id\":39,\"event_id\":23,\"contestant_id\":41,\"amount\":\"10.00\"}', '::1', '2025-10-09 12:11:26'),
(165, 5, 'logout', '[]', '::1', '2025-10-10 11:46:39'),
(166, NULL, 'login_failed', '{\"email\":\"ekowme@gmail.comm\",\"ip\":\"::1\"}', '::1', '2025-10-10 11:46:47'),
(167, 3, 'login', '{\"email\":\"ekowme@gmail.comm\",\"success\":true}', '::1', '2025-10-10 11:46:51'),
(168, 4, 'logout', '[]', '::1', '2025-10-10 12:58:16'),
(169, 4, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-10 12:58:23');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `event_id`, `tenant_id`, `name`, `description`, `created_by`, `display_order`, `created_at`, `updated_at`) VALUES
(40, 22, 3, 'BEST RAPPER', '', 5, 0, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(41, 22, 3, 'BEST SINGER', '', 5, 0, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(42, 23, 2, 'Contestants', '', 4, 0, '2025-10-09 06:14:31', '2025-10-09 06:14:31'),
(43, 24, 2, 'English Teacher', '', 4, 0, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(44, 24, 2, 'Maths Teacher', '', 4, 0, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(45, 24, 2, 'Science Teacher', '', 4, 0, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(46, 24, 2, 'Class Teacher', '', 4, 0, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(47, 24, 2, 'Computer Teacher', '', 4, 0, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(48, 25, 2, 'General', '', 4, 0, '2025-10-09 10:19:58', '2025-10-09 10:19:58'),
(49, 26, 2, 'FACE MODEL / BEAUTY QUEEN', '', 4, 0, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(50, 26, 2, 'BEST STUDENT PHOTO MODEL (MALE)', '', 4, 0, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(51, 26, 2, 'OUTSTANDING ENTERTAINMENT PREFECT (FEMALE)', '', 4, 0, '2025-10-09 12:34:10', '2025-10-09 12:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `contestants`
--

CREATE TABLE `contestants` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL DEFAULT 1,
  `event_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contestant_code` varchar(20) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contestants`
--

INSERT INTO `contestants` (`id`, `tenant_id`, `event_id`, `name`, `contestant_code`, `image_url`, `bio`, `display_order`, `active`, `created_by`, `created_at`, `updated_at`) VALUES
(36, 3, 22, 'SARKODIE', 'T301', '/uploads/nominees/nominees_68e7081f7ba9e.jpg', '', 0, 1, 5, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(37, 3, 22, 'OBRAFOUR', 'T302', '/uploads/nominees/nominees_68e7081f8137c.png', '', 0, 1, 5, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(38, 3, 22, 'DJ FLASH', 'T303', '/uploads/nominees/nominees_68e7081f84b3e.jpg', '', 0, 1, 5, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(39, 2, 23, 'Nana Adjoa', 'T201', '/uploads/nominees/nominees_68e752c76911e.jpg', '', 0, 1, 4, '2025-10-09 06:14:31', '2025-10-09 06:14:31'),
(40, 2, 23, 'Ewurabena', 'T202', '/uploads/nominees/nominees_68e752c772807.png', '', 0, 1, 4, '2025-10-09 06:14:31', '2025-10-09 06:14:31'),
(41, 2, 23, 'Kwame Sakyi', 'T203', '/uploads/nominees/nominees_68e752c774c31.jpg', '', 0, 1, 4, '2025-10-09 06:14:31', '2025-10-09 06:14:31'),
(42, 2, 24, 'John Bongo', 'T204', '/uploads/nominees/nominees_68e76fd57e168.png', '', 0, 1, 4, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(43, 2, 24, 'Agenda', 'T205', '/uploads/nominees/nominees_68e76fd58b4c9.jpg', '', 0, 1, 4, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(44, 2, 24, 'Bigboss', 'T206', '/uploads/nominees/nominees_68e76fd593eb1.jpg', '', 0, 1, 4, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(45, 2, 24, 'Vigour', 'T207', '/uploads/nominees/nominees_68e76fd599b6b.jpeg', '', 0, 1, 4, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(46, 2, 24, 'Emma Boakye Danquah', 'T208', '/uploads/nominees/nominees_68e76fd5aaa69.jpeg', '', 0, 1, 4, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(47, 2, 25, 'Yaw Mensah', 'T209', '/uploads/nominees/nominees_68e78c4ea8ee3.jpeg', '', 0, 1, 4, '2025-10-09 10:19:58', '2025-10-09 10:19:58'),
(48, 2, 25, 'Kwame Amponsah', 'T210', '/uploads/nominees/nominees_68e78c4ec34fb.jpeg', '', 0, 1, 4, '2025-10-09 10:19:58', '2025-10-09 10:19:58'),
(49, 2, 26, 'KUKKY ARTHUR', 'T211', NULL, '', 0, 1, 4, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(50, 2, 26, 'JOAN MENSAH', 'T212', NULL, '', 0, 1, 4, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(51, 2, 26, 'GEORGE BOATENG', 'T213', NULL, '', 0, 1, 4, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(52, 2, 26, 'JAMES ANNAN', 'T214', NULL, '', 0, 1, 4, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(53, 2, 26, 'ERICKA AKWEI', 'T215', NULL, '', 0, 1, 4, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(54, 2, 26, 'EKUA MANU', 'T216', NULL, '', 0, 1, 4, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(55, 2, 26, 'NORBERT MENSAH', 'T217', NULL, '', 0, 1, 4, '2025-10-09 12:34:10', '2025-10-09 12:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `contestant_categories`
--

CREATE TABLE `contestant_categories` (
  `id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `short_code` varchar(10) NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contestant_categories`
--

INSERT INTO `contestant_categories` (`id`, `contestant_id`, `category_id`, `short_code`, `display_order`, `active`, `created_at`, `updated_at`) VALUES
(39, 36, 40, 'T3SA001', 0, 1, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(40, 36, 41, 'T3SA002', 0, 1, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(41, 37, 40, 'T3OB001', 0, 1, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(42, 37, 41, 'T3OB002', 0, 1, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(43, 38, 40, 'T3DJ001', 0, 1, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(44, 38, 41, 'T3DJ002', 0, 1, '2025-10-09 00:55:59', '2025-10-09 00:55:59'),
(45, 39, 42, 'T2NA001', 0, 1, '2025-10-09 06:14:31', '2025-10-09 06:14:31'),
(46, 40, 42, 'T2EW001', 0, 1, '2025-10-09 06:14:31', '2025-10-09 06:14:31'),
(47, 41, 42, 'T2KW001', 0, 1, '2025-10-09 06:14:31', '2025-10-09 06:14:31'),
(48, 42, 44, 'T2JO001', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(49, 42, 46, 'T2JO002', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(50, 42, 47, 'T2JO003', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(51, 43, 44, 'T2AG001', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(52, 43, 45, 'T2AG002', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(53, 43, 47, 'T2AG003', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(54, 44, 43, 'T2BI001', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(55, 44, 44, 'T2BI002', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(56, 44, 45, 'T2BI003', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(57, 45, 45, 'T2VI001', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(58, 45, 46, 'T2VI002', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(59, 45, 47, 'T2VI003', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(60, 46, 43, 'T2EM001', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(61, 46, 46, 'T2EM002', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(62, 46, 47, 'T2EM003', 0, 1, '2025-10-09 08:18:29', '2025-10-09 08:18:29'),
(63, 47, 48, 'T2YA001', 0, 1, '2025-10-09 10:19:58', '2025-10-09 10:19:58'),
(64, 48, 48, 'T2KW002', 0, 1, '2025-10-09 10:19:58', '2025-10-09 10:19:58'),
(65, 49, 49, 'T2KU001', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(66, 49, 50, 'T2KU002', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(67, 50, 50, 'T2JO004', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(68, 50, 51, 'T2JO005', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(69, 51, 49, 'T2GE001', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(70, 51, 51, 'T2GE002', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(71, 52, 50, 'T2JA001', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(72, 52, 51, 'T2JA002', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(73, 53, 49, 'T2ER001', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(74, 53, 51, 'T2ER002', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(75, 54, 49, 'T2EK001', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(76, 54, 50, 'T2EK002', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(77, 55, 49, 'T2NO001', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10'),
(78, 55, 51, 'T2NO002', 0, 1, '2025-10-09 12:34:10', '2025-10-09 12:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `vote_price` decimal(10,2) DEFAULT 0.50 COMMENT 'Price per vote in USD',
  `active` tinyint(1) DEFAULT 1,
  `status` enum('draft','active','suspended','closed','archived') DEFAULT 'draft',
  `visibility` enum('private','public','unlisted') DEFAULT 'private',
  `admin_status` enum('approved','pending','rejected','under_review') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `suspended_reason` text DEFAULT NULL,
  `suspended_by` int(11) DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `results_visible` tinyint(1) DEFAULT 1 COMMENT 'Controls whether results/leaderboard is visible to public',
  `deactivated_at` timestamp NULL DEFAULT NULL,
  `deactivated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `tenant_id`, `name`, `code`, `description`, `featured_image`, `start_date`, `end_date`, `vote_price`, `active`, `status`, `visibility`, `admin_status`, `admin_notes`, `created_by`, `suspended_reason`, `suspended_by`, `suspended_at`, `closed_at`, `archived_at`, `created_at`, `updated_at`, `results_visible`, `deactivated_at`, `deactivated_by`) VALUES
(22, 3, 'Ghana Music Awards 25', 'GHANAMUSIC', 'GhaMA2025', '/uploads/events/events_68e7081f62707.jpg', '2025-10-09 00:55:00', '2025-10-30 00:54:00', 1.00, 1, 'active', 'public', 'pending', NULL, 5, NULL, NULL, NULL, NULL, NULL, '2025-10-09 00:55:59', '2025-10-09 09:23:23', 0, NULL, NULL),
(23, 2, 'Breman Excellence Awardss', 'BREMANEXCE', 'nmbmnmmnbm,b n  bnm bn, n', '/uploads/events/events_68e752c725078.jpeg', '2025-10-09 06:09:00', '2025-10-26 06:09:00', 2.00, 1, 'active', 'public', 'pending', NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:14:31', '2025-10-09 10:17:22', 1, NULL, NULL),
(24, 2, 'Teachers Awards', 'TEACHERSAW', 'Teachers Awards 2025', '/uploads/events/events_68e76fd571ae4.jpg', '2025-10-09 08:14:00', '2025-10-22 08:14:00', 0.80, 1, 'active', 'public', 'pending', NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-10-09 08:18:29', '2025-10-09 09:35:19', 0, NULL, NULL),
(25, 2, 'Another Sample', 'ASE25', 'aNOTHER SAMPLE EVENT FOR TESTING', '/uploads/events/events_68e78c4e7dce1.jpeg', '2025-10-17 10:18:00', '2025-10-24 10:19:00', 1.00, 1, 'active', 'public', 'pending', NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-10-09 10:19:58', '2025-10-09 10:21:01', 1, NULL, NULL),
(26, 2, 'NATIONAL HIGH SCHOOL ENTERTAINMENT AWARDS 2025', 'NHSEA25', 'NATIONAL HIGH SCHOOL AWARDS', '/uploads/events/events_68e7abc1e1fb9.jpeg', '2025-10-09 12:31:00', '2025-10-31 12:31:00', 1.00, 1, 'active', 'public', 'pending', NULL, 4, NULL, NULL, NULL, NULL, NULL, '2025-10-09 12:34:10', '2025-10-09 12:34:25', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_drafts`
--

CREATE TABLE `event_drafts` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `draft_name` varchar(255) NOT NULL,
  `draft_data` text NOT NULL,
  `step` int(11) DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_status_history`
--

CREATE TABLE `event_status_history` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `old_admin_status` varchar(50) DEFAULT NULL,
  `new_admin_status` varchar(50) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `change_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_rules`
--

CREATE TABLE `fee_rules` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `rule_type` enum('percentage','fixed','blend') DEFAULT 'percentage',
  `percentage_rate` decimal(5,2) DEFAULT NULL,
  `fixed_amount` decimal(10,2) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fee_rules`
--

INSERT INTO `fee_rules` (`id`, `tenant_id`, `event_id`, `rule_type`, `percentage_rate`, `fixed_amount`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'percentage', 10.00, NULL, 1, '2025-10-03 11:36:30', '2025-10-03 11:36:30'),
(2, 2, NULL, 'percentage', 15.00, NULL, 1, '2025-10-03 11:36:30', '2025-10-03 11:36:30'),
(3, NULL, NULL, 'percentage', 12.00, NULL, 1, '2025-10-03 11:36:30', '2025-10-03 11:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `fraud_events`
--

CREATE TABLE `fraud_events` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `event_type` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard_cache`
--

CREATE TABLE `leaderboard_cache` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `total_votes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaderboard_cache`
--

INSERT INTO `leaderboard_cache` (`id`, `event_id`, `contestant_id`, `category_id`, `total_votes`, `updated_at`) VALUES
(52, 22, 37, 40, 1, '2025-10-09 12:06:08'),
(53, 23, 40, 42, 25, '2025-10-09 12:09:44'),
(54, 23, 41, 42, 5, '2025-10-09 12:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int(11) NOT NULL,
  `msisdn` varchar(20) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `consumed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `payout_id` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payout_method` enum('bank_transfer','mobile_money','paypal') DEFAULT 'bank_transfer',
  `recipient_details` text NOT NULL,
  `status` enum('queued','processing','success','failed','cancelled') DEFAULT 'queued',
  `provider_reference` varchar(100) DEFAULT NULL,
  `failure_reason` text DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rate_limits`
--

CREATE TABLE `rate_limits` (
  `id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rate_limits`
--

INSERT INTO `rate_limits` (`id`, `key`, `created_at`) VALUES
(26, 'login_attempt_::1', '2025-10-08 17:25:58');

-- --------------------------------------------------------

--
-- Table structure for table `revenue_shares`
--

CREATE TABLE `revenue_shares` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `fee_rule_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `risk_blocks`
--

CREATE TABLE `risk_blocks` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `block_type` enum('ip','msisdn','device') NOT NULL,
  `block_value` varchar(255) NOT NULL,
  `reason` text DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `plan` enum('free','basic','premium','enterprise') DEFAULT 'basic',
  `active` tinyint(1) DEFAULT 1,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `email`, `phone`, `website`, `address`, `plan`, `active`, `verified`, `created_at`, `updated_at`) VALUES
(1, 'Organizer One', 'demo@votesaas.com', '+233241234567', NULL, NULL, 'basic', 1, 1, '2025-10-03 11:36:30', '2025-10-05 05:04:28'),
(2, 'Organizer Two', 'test@votesaas.com', '+233501234567', NULL, NULL, 'basic', 1, 1, '2025-10-03 11:36:30', '2025-10-05 05:04:36'),
(3, 'Everything Nice Family', 'ekowmeee@gmail.com', NULL, NULL, NULL, 'basic', 1, 0, '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(4, 'Test Owner', 'owner@test.com', NULL, NULL, NULL, 'basic', 1, 1, '2025-10-08 19:29:28', '2025-10-08 19:29:28'),
(5, 'Test Platform_admin', 'admin@test.com', NULL, NULL, NULL, 'basic', 1, 1, '2025-10-08 19:29:28', '2025-10-08 19:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_balances`
--

CREATE TABLE `tenant_balances` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `available` decimal(10,2) DEFAULT 0.00,
  `pending` decimal(10,2) DEFAULT 0.00,
  `total_earned` decimal(10,2) DEFAULT 0.00,
  `total_paid` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_balances`
--

INSERT INTO `tenant_balances` (`id`, `tenant_id`, `available`, `pending`, `total_earned`, `total_paid`, `created_at`, `updated_at`) VALUES
(3, 1, 0.00, 0.00, 0.00, 0.00, '2025-10-05 08:16:59', '2025-10-05 06:16:59'),
(4, 2, 0.00, 0.00, 0.00, 0.00, '2025-10-05 08:53:02', '2025-10-05 06:53:02'),
(5, 3, 0.00, 0.00, 0.00, 0.00, '2025-10-08 19:43:51', '2025-10-08 19:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_settings`
--

CREATE TABLE `tenant_settings` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_settings`
--

INSERT INTO `tenant_settings` (`id`, `tenant_id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'otp_required', 'false', '2025-10-03 11:36:30', '2025-10-03 11:36:30'),
(2, 1, 'leaderboard_lag_seconds', '30', '2025-10-03 11:36:30', '2025-10-03 11:36:30'),
(3, 1, 'theme_json', '{\"primary_color\": \"#007bff\", \"secondary_color\": \"#6c757d\"}', '2025-10-03 11:36:30', '2025-10-03 11:36:30'),
(4, 1, 'max_votes_per_msisdn', '100', '2025-10-03 11:36:30', '2025-10-03 11:36:30'),
(5, 1, 'fraud_detection_enabled', 'true', '2025-10-03 11:36:30', '2025-10-03 11:36:30'),
(6, 3, 'otp_required', 'false', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(7, 3, 'leaderboard_lag_seconds', '30', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(8, 3, 'theme_json', '{\"primary_color\":\"#007bff\",\"secondary_color\":\"#6c757d\",\"success_color\":\"#28a745\",\"danger_color\":\"#dc3545\"}', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(9, 3, 'max_votes_per_msisdn', '100', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(10, 3, 'fraud_detection_enabled', 'true', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(11, 3, 'webhook_enabled', 'false', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(12, 3, 'email_notifications_enabled', 'true', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(13, 3, 'sms_notifications_enabled', 'false', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(14, 3, 'auto_approve_events', 'false', '2025-10-08 19:16:34', '2025-10-08 19:16:34'),
(15, 3, 'minimum_payout_amount', '10', '2025-10-08 19:16:34', '2025-10-08 19:16:34');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `bundle_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `msisdn` varchar(20) DEFAULT NULL,
  `status` enum('pending','success','failed') DEFAULT 'pending',
  `provider` varchar(50) DEFAULT NULL,
  `provider_reference` varchar(100) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `referral_code` varchar(50) DEFAULT NULL,
  `failure_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `tenant_id`, `event_id`, `contestant_id`, `category_id`, `bundle_id`, `amount`, `msisdn`, `status`, `provider`, `provider_reference`, `coupon_code`, `referral_code`, `failure_reason`, `created_at`, `updated_at`) VALUES
(37, 3, 22, 37, 40, 16, 1.00, '+233545644749', 'success', 'momo', 'MPAD069BE3', '', '', NULL, '2025-10-09 12:06:01', '2025-10-09 12:06:07'),
(38, 2, 23, 40, 42, 11, 35.00, '233545644749', 'success', 'momo', 'MP97F2A883', '', '', NULL, '2025-10-09 12:09:37', '2025-10-09 12:09:44'),
(39, 2, 23, 41, 42, 8, 10.00, '0545644749', 'success', 'momo', 'MP4CBE0D72', '', '', NULL, '2025-10-09 12:11:19', '2025-10-09 12:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('platform_admin','owner','manager','staff') DEFAULT 'staff',
  `active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `tenant_id`, `email`, `password_hash`, `role`, `active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@demo.com', '$2y$10$IdVuCAw45Y7kkjsiqzBwIO99rvD6Fe0oSDQMAzL9DeJ7jzFUpEMFu', 'owner', 1, '2025-10-08 10:39:41', '2025-10-03 11:36:30', '2025-10-08 10:39:41'),
(2, 1, 'manager@demo.com', '$2y$10$IdVuCAw45Y7kkjsiqzBwIO99rvD6Fe0oSDQMAzL9DeJ7jzFUpEMFu', 'manager', 1, '2025-10-08 19:24:48', '2025-10-03 11:36:30', '2025-10-08 19:24:48'),
(3, NULL, 'ekowme@gmail.comm', '$2y$10$IdVuCAw45Y7kkjsiqzBwIO99rvD6Fe0oSDQMAzL9DeJ7jzFUpEMFu', 'platform_admin', 1, '2025-10-10 11:46:51', '2025-10-03 11:36:30', '2025-10-10 11:46:51'),
(4, 2, 'ekowme@gmail.com', '$2y$10$IdVuCAw45Y7kkjsiqzBwIO99rvD6Fe0oSDQMAzL9DeJ7jzFUpEMFu', 'owner', 1, '2025-10-10 12:58:23', '2025-10-03 14:24:41', '2025-10-10 12:58:23'),
(5, 3, 'ekowmeee@gmail.com', '$2y$10$rGl6GaNNGv.o6Bz18lBES.Wc5yiwUi79dDzE5EIZu34QUzZjE/inC', 'owner', 1, '2025-10-09 05:28:59', '2025-10-08 19:16:34', '2025-10-09 05:28:59'),
(6, 4, 'owner@test.com', '$2y$10$xK5ypV6EIp7taPnz.Y4Hn.8wYCvlzQtt2xG19fcMH93.9c/Rc4HmG', 'owner', 1, NULL, '2025-10-08 19:29:28', '2025-10-08 19:29:28'),
(7, 5, 'admin@test.com', '$2y$10$X5x1hRHNS9.fF8a3G89kleFxmt9eKjEF3Lnrzha6X4sE115290HnO', 'platform_admin', 1, NULL, '2025-10-08 19:29:28', '2025-10-08 19:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `ussd_sessions`
--

CREATE TABLE `ussd_sessions` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `msisdn` varchar(20) NOT NULL,
  `state` varchar(50) NOT NULL,
  `data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `transaction_id`, `tenant_id`, `event_id`, `contestant_id`, `category_id`, `quantity`, `created_at`) VALUES
(28, 37, 3, 22, 37, 40, 1, '2025-10-09 12:06:08'),
(29, 38, 2, 23, 40, 42, 25, '2025-10-09 12:09:44'),
(30, 39, 2, 23, 41, 42, 5, '2025-10-09 12:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `vote_bundles`
--

CREATE TABLE `vote_bundles` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `votes` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vote_bundles`
--

INSERT INTO `vote_bundles` (`id`, `event_id`, `name`, `votes`, `price`, `active`, `created_at`, `updated_at`) VALUES
(8, 23, 'Single Vote', 1, 2.00, 1, '2025-10-09 07:38:32', '2025-10-09 07:38:32'),
(9, 23, 'Vote Pack (5)', 5, 9.00, 1, '2025-10-09 07:38:32', '2025-10-09 07:38:32'),
(10, 23, 'Vote Pack (10)', 10, 16.00, 1, '2025-10-09 07:38:32', '2025-10-09 07:38:32'),
(11, 23, 'Vote Pack (25)', 25, 35.00, 1, '2025-10-09 07:38:32', '2025-10-09 07:38:32'),
(12, 24, 'Single Vote', 1, 0.80, 1, '2025-10-09 08:19:44', '2025-10-09 08:19:44'),
(13, 24, 'Vote Pack (5)', 5, 3.60, 1, '2025-10-09 08:19:44', '2025-10-09 08:19:44'),
(14, 24, 'Vote Pack (10)', 10, 6.40, 1, '2025-10-09 08:19:44', '2025-10-09 08:19:44'),
(15, 24, 'Vote Pack (25)', 25, 14.00, 1, '2025-10-09 08:19:44', '2025-10-09 08:19:44'),
(16, 22, 'Vote Package', 100, 90.00, 1, '2025-10-09 08:22:28', '2025-10-09 08:22:28'),
(17, 24, 'For You Bundle', 100, 90.00, 1, '2025-10-09 09:20:40', '2025-10-09 09:20:40'),
(18, 26, 'Single Vote', 1, 1.00, 1, '2025-10-09 12:40:20', '2025-10-09 12:40:20'),
(19, 26, 'Vote Pack (5)', 5, 4.50, 1, '2025-10-09 12:40:20', '2025-10-09 12:40:20'),
(20, 26, 'Vote Pack (10)', 10, 8.00, 1, '2025-10-09 12:40:20', '2025-10-09 12:40:20'),
(21, 26, 'Vote Pack (25)', 25, 17.50, 1, '2025-10-09 12:40:20', '2025-10-09 12:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `vote_ledger`
--

CREATE TABLE `vote_ledger` (
  `id` int(11) NOT NULL,
  `vote_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vote_ledger`
--

INSERT INTO `vote_ledger` (`id`, `vote_id`, `transaction_id`, `tenant_id`, `event_id`, `contestant_id`, `category_id`, `quantity`, `hash`, `created_at`) VALUES
(20, 28, 37, 3, 22, 37, NULL, 1, 'fde0f58059f910f2fa55ea4d058ad82ceb4508ee84797ebaf7ef3760270a8b6c', '2025-10-09 12:06:08'),
(21, 29, 38, 2, 23, 40, NULL, 25, '4ae4c0a67921a24ea845dde1e2bb230e6da32ca36e064ad4dddb14672dabe3a5', '2025-10-09 12:09:44'),
(22, 30, 39, 2, 23, 41, NULL, 5, 'f4568d33c6f9567aa88aa561a5d0709a387514541f9617f5ffdd65322ff70acb', '2025-10-09 12:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `vote_receipts`
--

CREATE TABLE `vote_receipts` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `short_code` varchar(20) NOT NULL,
  `public_hash` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vote_receipts`
--

INSERT INTO `vote_receipts` (`id`, `transaction_id`, `short_code`, `public_hash`, `created_at`) VALUES
(20, 37, 'YBK8T409', 'f983b7a7bfea5713639ce70be94893f3ecc2ad9c832ce930aced64958030b53a', '2025-10-09 12:06:08'),
(21, 38, 'B6I0RM9S', 'f49f35bfbcfd95b3967853a597ed342125eb020de1703e43aafe3e17652e7178', '2025-10-09 12:09:44'),
(22, 39, 'VEYAKF8I', '51aa1b9e704f92095b5af10c926fcfa2752c71f94c413cb96ea9e1cd1d6f4bfc', '2025-10-09 12:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `webhook_endpoints`
--

CREATE TABLE `webhook_endpoints` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `secret` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webhook_events`
--

CREATE TABLE `webhook_events` (
  `id` int(11) NOT NULL,
  `endpoint_id` int(11) NOT NULL,
  `event` varchar(100) NOT NULL,
  `payload` text NOT NULL,
  `status` enum('queued','sent','failed') DEFAULT 'queued',
  `attempts` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_action` (`user_id`,`action`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `contestants`
--
ALTER TABLE `contestants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contestant_code` (`contestant_code`),
  ADD KEY `idx_contestant_code` (`contestant_code`),
  ADD KEY `idx_contestants_tenant_active` (`tenant_id`,`active`);

--
-- Indexes for table `contestant_categories`
--
ALTER TABLE `contestant_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_contestant_category` (`contestant_id`,`category_id`),
  ADD UNIQUE KEY `unique_category_code` (`category_id`,`short_code`),
  ADD KEY `idx_contestant` (`contestant_id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_short_code` (`short_code`),
  ADD KEY `idx_contestant_categories_active` (`category_id`,`active`,`display_order`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_tenant_active` (`tenant_id`,`active`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `fk_events_suspended_by` (`suspended_by`),
  ADD KEY `idx_events_results_visible` (`results_visible`),
  ADD KEY `idx_events_deactivated_at` (`deactivated_at`);

--
-- Indexes for table `event_drafts`
--
ALTER TABLE `event_drafts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tenant_id` (`tenant_id`),
  ADD KEY `idx_created_by` (`created_by`);

--
-- Indexes for table `event_status_history`
--
ALTER TABLE `event_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_status_history_event_id` (`event_id`),
  ADD KEY `idx_event_status_history_changed_by` (`changed_by`),
  ADD KEY `idx_event_status_history_created_at` (`created_at`);

--
-- Indexes for table `fee_rules`
--
ALTER TABLE `fee_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `fraud_events`
--
ALTER TABLE `fraud_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `leaderboard_cache`
--
ALTER TABLE `leaderboard_cache`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_event_contestant_category` (`event_id`,`contestant_id`,`category_id`),
  ADD KEY `contestant_id` (`contestant_id`),
  ADD KEY `idx_category` (`category_id`);

--
-- Indexes for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_msisdn_expires` (`msisdn`,`expires_at`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payout_id` (`payout_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_tenant_status` (`tenant_id`,`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `rate_limits`
--
ALTER TABLE `rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_key_time` (`key`,`created_at`);

--
-- Indexes for table `revenue_shares`
--
ALTER TABLE `revenue_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `fee_rule_id` (`fee_rule_id`);

--
-- Indexes for table `risk_blocks`
--
ALTER TABLE `risk_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `idx_type_value_active` (`block_type`,`block_value`,`active`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_plan` (`plan`);

--
-- Indexes for table `tenant_balances`
--
ALTER TABLE `tenant_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `tenant_settings`
--
ALTER TABLE `tenant_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tenant_setting` (`tenant_id`,`setting_key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `contestant_id` (`contestant_id`),
  ADD KEY `bundle_id` (`bundle_id`),
  ADD KEY `idx_provider_ref` (`provider`,`provider_reference`),
  ADD KEY `idx_tenant_status` (`tenant_id`,`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_transactions_category` (`category_id`),
  ADD KEY `idx_transactions_event_category` (`event_id`,`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `ussd_sessions`
--
ALTER TABLE `ussd_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`),
  ADD KEY `idx_session_id` (`session_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `idx_contestant_votes` (`contestant_id`,`created_at`),
  ADD KEY `idx_votes_category` (`category_id`),
  ADD KEY `idx_votes_event_category_contestant` (`event_id`,`category_id`,`contestant_id`);

--
-- Indexes for table `vote_bundles`
--
ALTER TABLE `vote_bundles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `vote_ledger`
--
ALTER TABLE `vote_ledger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vote_id` (`vote_id`),
  ADD KEY `idx_tenant_hash` (`tenant_id`,`hash`),
  ADD KEY `idx_vote_ledger_category` (`category_id`);

--
-- Indexes for table `vote_receipts`
--
ALTER TABLE `vote_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_code` (`short_code`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `idx_short_code` (`short_code`);

--
-- Indexes for table `webhook_endpoints`
--
ALTER TABLE `webhook_endpoints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `webhook_events`
--
ALTER TABLE `webhook_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `endpoint_id` (`endpoint_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `contestants`
--
ALTER TABLE `contestants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `contestant_categories`
--
ALTER TABLE `contestant_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `event_drafts`
--
ALTER TABLE `event_drafts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_status_history`
--
ALTER TABLE `event_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_rules`
--
ALTER TABLE `fee_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fraud_events`
--
ALTER TABLE `fraud_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaderboard_cache`
--
ALTER TABLE `leaderboard_cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rate_limits`
--
ALTER TABLE `rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `revenue_shares`
--
ALTER TABLE `revenue_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `risk_blocks`
--
ALTER TABLE `risk_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tenant_balances`
--
ALTER TABLE `tenant_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tenant_settings`
--
ALTER TABLE `tenant_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ussd_sessions`
--
ALTER TABLE `ussd_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `vote_bundles`
--
ALTER TABLE `vote_bundles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `vote_ledger`
--
ALTER TABLE `vote_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vote_receipts`
--
ALTER TABLE `vote_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `webhook_endpoints`
--
ALTER TABLE `webhook_endpoints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webhook_events`
--
ALTER TABLE `webhook_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contestant_categories`
--
ALTER TABLE `contestant_categories`
  ADD CONSTRAINT `contestant_categories_ibfk_1` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contestant_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_events_suspended_by` FOREIGN KEY (`suspended_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `event_drafts`
--
ALTER TABLE `event_drafts`
  ADD CONSTRAINT `event_drafts_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_drafts_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_status_history`
--
ALTER TABLE `event_status_history`
  ADD CONSTRAINT `event_status_history_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_rules`
--
ALTER TABLE `fee_rules`
  ADD CONSTRAINT `fee_rules_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_rules_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fraud_events`
--
ALTER TABLE `fraud_events`
  ADD CONSTRAINT `fraud_events_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leaderboard_cache`
--
ALTER TABLE `leaderboard_cache`
  ADD CONSTRAINT `leaderboard_cache_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leaderboard_cache_ibfk_2` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `payouts_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `revenue_shares`
--
ALTER TABLE `revenue_shares`
  ADD CONSTRAINT `revenue_shares_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revenue_shares_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revenue_shares_ibfk_3` FOREIGN KEY (`fee_rule_id`) REFERENCES `fee_rules` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `risk_blocks`
--
ALTER TABLE `risk_blocks`
  ADD CONSTRAINT `risk_blocks_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_balances`
--
ALTER TABLE `tenant_balances`
  ADD CONSTRAINT `tenant_balances_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_settings`
--
ALTER TABLE `tenant_settings`
  ADD CONSTRAINT `tenant_settings_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`bundle_id`) REFERENCES `vote_bundles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_votes_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_4` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vote_bundles`
--
ALTER TABLE `vote_bundles`
  ADD CONSTRAINT `vote_bundles_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vote_ledger`
--
ALTER TABLE `vote_ledger`
  ADD CONSTRAINT `fk_vote_ledger_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `vote_ledger_ibfk_1` FOREIGN KEY (`vote_id`) REFERENCES `votes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vote_receipts`
--
ALTER TABLE `vote_receipts`
  ADD CONSTRAINT `vote_receipts_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `webhook_endpoints`
--
ALTER TABLE `webhook_endpoints`
  ADD CONSTRAINT `webhook_endpoints_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `webhook_events`
--
ALTER TABLE `webhook_events`
  ADD CONSTRAINT `webhook_events_ibfk_1` FOREIGN KEY (`endpoint_id`) REFERENCES `webhook_endpoints` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
