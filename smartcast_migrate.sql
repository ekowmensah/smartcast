-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2025 at 12:07 AM
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
(196, NULL, 'login_failed', '{\"email\":\"ekowmee@gmail.com\",\"ip\":\"::1\"}', '::1', '2025-10-10 20:15:59'),
(197, NULL, 'user_registered', '{\"tenant_id\":\"20\",\"plan_id\":4,\"plan_name\":\"Enterprise\"}', '::1', '2025-10-10 20:17:31'),
(198, 3, 'tenant_rejected', '{\"tenant_id\":\"21\",\"tenant_name\":\"Pending Organization 202330\",\"tenant_email\":\"pending202330@example.com\",\"reason\":\"suspicious_activity: Suspect\"}', '::1', '2025-10-10 20:24:23'),
(199, 3, 'tenant_rejected', '{\"tenant_id\":\"20\",\"tenant_name\":\"Hope For All\",\"tenant_email\":\"ekowmee@gmail.com\",\"reason\":\"business_not_eligible: Paaa\"}', '::1', '2025-10-10 20:25:56'),
(200, 3, 'tenant_approved', '{\"tenant_id\":\"19\",\"tenant_name\":\"Pending Organization 201340\",\"tenant_email\":\"pending201340@example.com\"}', '::1', '2025-10-10 20:26:44'),
(201, 3, 'logout', '[]', '::1', '2025-10-10 20:27:40'),
(202, NULL, 'login_failed', '{\"email\":\"ekowme@gmail.comm\",\"ip\":\"::1\"}', '::1', '2025-10-10 20:27:43'),
(203, NULL, 'login', '{\"email\":\"ekowme@gmail.comm\",\"success\":true}', '::1', '2025-10-10 20:28:05'),
(204, 3, 'login', '{\"email\":\"ekowme@gmail.com\",\"success\":true}', '::1', '2025-10-10 20:31:21'),
(205, NULL, 'login_failed', '{\"email\":\"ekowmee@gmail.com\",\"ip\":\"::1\"}', '::1', '2025-10-10 20:31:30'),
(206, 17, 'user_registered', '{\"tenant_id\":\"22\",\"plan_id\":1,\"plan_name\":\"Free Starter\"}', '::1', '2025-10-10 20:31:58'),
(207, 3, 'tenant_approved', '{\"tenant_id\":\"22\",\"tenant_name\":\"Hope For All\",\"tenant_email\":\"ekowmee@gmail.com\"}', '::1', '2025-10-10 20:32:28'),
(208, 17, 'login', '{\"email\":\"ekowmee@gmail.com\",\"success\":true}', '::1', '2025-10-10 20:32:41'),
(209, NULL, 'vote_cast', '{\"transaction_id\":53,\"event_id\":38,\"contestant_id\":85,\"amount\":\"1.00\"}', '::1', '2025-10-10 20:51:10'),
(210, NULL, 'vote_cast', '{\"transaction_id\":54,\"event_id\":38,\"contestant_id\":85,\"amount\":\"1.00\"}', '::1', '2025-10-10 21:27:20'),
(211, NULL, 'vote_cast', '{\"transaction_id\":56,\"event_id\":38,\"contestant_id\":79,\"amount\":\"1.00\"}', '::1', '2025-10-10 21:38:07'),
(212, NULL, 'vote_cast', '{\"transaction_id\":57,\"event_id\":38,\"contestant_id\":79,\"amount\":\"1.00\"}', '::1', '2025-10-10 21:44:20'),
(213, NULL, 'vote_cast', '{\"transaction_id\":58,\"event_id\":38,\"contestant_id\":78,\"amount\":\"100.00\"}', '::1', '2025-10-10 21:50:48');

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
(65, 38, 22, 'Sample Cat 1', '', 17, 0, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(66, 38, 22, 'Sample Cat 2', '', 17, 0, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(67, 38, 22, 'Sample Cat 3', '', 17, 0, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(68, 38, 22, 'Sample Cat 4', '', 17, 0, '2025-10-10 20:43:05', '2025-10-10 20:43:05');

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
(76, 22, 38, 'Sample Nom 1', 'T2201', NULL, '', 0, 1, 17, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(77, 22, 38, 'Sample Nom 2', 'T2202', NULL, '', 0, 1, 17, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(78, 22, 38, 'Sample Nom 3', 'T2203', NULL, '', 0, 1, 17, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(79, 22, 38, 'Sample Nom 4', 'T2204', NULL, '', 0, 1, 17, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(80, 22, 38, 'Sample Nom 5', 'T2205', NULL, '', 0, 1, 17, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(81, 22, 38, 'Sample Nom 6', 'T2206', NULL, '', 0, 1, 17, '2025-10-10 20:43:06', '2025-10-10 20:43:06'),
(82, 22, 38, 'Sample Nom 7', 'T2207', NULL, '', 0, 1, 17, '2025-10-10 20:43:06', '2025-10-10 20:43:06'),
(83, 22, 38, 'Sample Nom 8', 'T2208', NULL, '', 0, 1, 17, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(84, 22, 38, 'Sample Nom 9', 'T2209', NULL, '', 0, 1, 17, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(85, 22, 38, 'Sample Nom 10', 'T2210', NULL, '', 0, 1, 17, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(86, 22, 38, 'Sample Nom 11', 'T2211', NULL, '', 0, 1, 17, '2025-10-10 20:43:07', '2025-10-10 20:43:07');

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
(86, 76, 65, 'T22SA001', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(87, 76, 67, 'T22SA002', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(88, 77, 66, 'T22SA003', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(89, 77, 67, 'T22SA004', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(90, 78, 66, 'T22SA005', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(91, 78, 67, 'T22SA006', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(92, 78, 68, 'T22SA007', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(93, 79, 65, 'T22SA008', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(94, 79, 67, 'T22SA009', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(95, 79, 68, 'T22SA010', 0, 1, '2025-10-10 20:43:05', '2025-10-10 20:43:05'),
(96, 80, 66, 'T22SA011', 0, 1, '2025-10-10 20:43:06', '2025-10-10 20:43:06'),
(97, 80, 67, 'T22SA012', 0, 1, '2025-10-10 20:43:06', '2025-10-10 20:43:06'),
(98, 80, 68, 'T22SA013', 0, 1, '2025-10-10 20:43:06', '2025-10-10 20:43:06'),
(99, 81, 66, 'T22SA014', 0, 1, '2025-10-10 20:43:06', '2025-10-10 20:43:06'),
(100, 81, 67, 'T22SA015', 0, 1, '2025-10-10 20:43:06', '2025-10-10 20:43:06'),
(101, 81, 68, 'T22SA016', 0, 1, '2025-10-10 20:43:06', '2025-10-10 20:43:06'),
(102, 82, 66, 'T22SA017', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(103, 82, 67, 'T22SA018', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(104, 82, 68, 'T22SA019', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(105, 83, 65, 'T22SA020', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(106, 83, 66, 'T22SA021', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(107, 83, 67, 'T22SA022', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(108, 84, 65, 'T22SA023', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(109, 84, 67, 'T22SA024', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(110, 84, 68, 'T22SA025', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(111, 85, 65, 'T22SA026', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(112, 85, 66, 'T22SA027', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(113, 85, 67, 'T22SA028', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(114, 86, 65, 'T22SA029', 0, 1, '2025-10-10 20:43:07', '2025-10-10 20:43:07'),
(115, 86, 66, 'T22SA030', 0, 1, '2025-10-10 20:43:08', '2025-10-10 20:43:08'),
(116, 86, 67, 'T22SA031', 0, 1, '2025-10-10 20:43:08', '2025-10-10 20:43:08');

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
(38, 22, 'Sample Voting System', 'SAMPLEVOTI', 'Sample Voting System Event', '/uploads/events/events_68e96da1390e3.jpeg', '2025-10-10 20:33:00', '2025-10-12 20:33:00', 1.00, 1, 'active', 'public', 'pending', NULL, 17, NULL, NULL, NULL, NULL, NULL, '2025-10-10 20:33:37', '2025-10-10 20:43:18', 1, NULL, NULL);

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
(9, NULL, NULL, 'percentage', 35.00, NULL, 1, '2025-10-10 21:26:43', '2025-10-10 21:54:11');

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
(62, 38, 85, 67, 2, '2025-10-10 21:27:20'),
(64, 38, 79, 68, 2, '2025-10-10 21:44:20'),
(66, 38, 78, 68, 100, '2025-10-10 21:50:47');

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
  `initiated_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `payout_id` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `processing_fee` decimal(10,2) DEFAULT 0.00,
  `net_amount` decimal(10,2) DEFAULT 0.00,
  `payout_method` enum('bank_transfer','mobile_money','paypal') DEFAULT 'bank_transfer',
  `payout_type` enum('manual','automatic','instant') DEFAULT 'manual',
  `payout_method_id` int(11) DEFAULT NULL,
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
-- Table structure for table `payout_methods`
--

CREATE TABLE `payout_methods` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `method_type` enum('bank_transfer','mobile_money','paypal','stripe') NOT NULL,
  `method_name` varchar(100) NOT NULL,
  `account_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`account_details`)),
  `is_default` tinyint(1) DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`verification_data`)),
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payout_schedules`
--

CREATE TABLE `payout_schedules` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `frequency` enum('manual','daily','weekly','monthly') DEFAULT 'monthly',
  `minimum_amount` decimal(10,2) DEFAULT 10.00,
  `auto_payout_enabled` tinyint(1) DEFAULT 0,
  `instant_payout_threshold` decimal(10,2) DEFAULT 1000.00,
  `next_payout_date` date DEFAULT NULL,
  `payout_day` int(2) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payout_schedules`
--

INSERT INTO `payout_schedules` (`id`, `tenant_id`, `frequency`, `minimum_amount`, `auto_payout_enabled`, `instant_payout_threshold`, `next_payout_date`, `payout_day`, `created_at`, `updated_at`) VALUES
(4, 4, 'monthly', 10.00, 0, 1000.00, NULL, 1, '2025-10-10 13:13:17', '2025-10-10 13:13:17'),
(5, 5, 'monthly', 10.00, 0, 1000.00, NULL, 1, '2025-10-10 13:13:17', '2025-10-10 13:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `plan_features`
--

CREATE TABLE `plan_features` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `feature_key` varchar(100) NOT NULL,
  `feature_name` varchar(255) NOT NULL,
  `feature_value` varchar(255) DEFAULT NULL,
  `is_boolean` tinyint(1) DEFAULT 0 COMMENT '1 if feature is yes/no, 0 if has value',
  `sort_order` int(3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plan_features`
--

INSERT INTO `plan_features` (`id`, `plan_id`, `feature_key`, `feature_name`, `feature_value`, `is_boolean`, `sort_order`) VALUES
(1, 1, 'events', 'Events', '1', 0, 1),
(2, 1, 'contestants', 'Contestants per Event', '10', 0, 2),
(3, 1, 'votes', 'Votes per Event', '1,000', 0, 3),
(4, 1, 'storage', 'Storage', '100 MB', 0, 4),
(5, 1, 'custom_branding', 'Custom Branding', NULL, 1, 5),
(6, 1, 'analytics', 'Analytics', 'Basic', 0, 6),
(7, 1, 'support', 'Support', 'Community', 0, 7),
(8, 2, 'events', 'Events', '5', 0, 1),
(9, 2, 'contestants', 'Contestants per Event', '50', 0, 2),
(10, 2, 'votes', 'Votes per Event', '10,000', 0, 3),
(11, 2, 'storage', 'Storage', '1 GB', 0, 4),
(12, 2, 'custom_branding', 'Custom Branding', '1', 1, 5),
(13, 2, 'analytics', 'Analytics', 'Standard', 0, 6),
(14, 2, 'support', 'Support', 'Email', 0, 7),
(15, 2, 'api_access', 'API Access', NULL, 1, 8),
(16, 3, 'events', 'Events', 'Unlimited', 0, 1),
(17, 3, 'contestants', 'Contestants per Event', 'Unlimited', 0, 2),
(18, 3, 'votes', 'Votes per Event', 'Unlimited', 0, 3),
(19, 3, 'storage', 'Storage', '5 GB', 0, 4),
(20, 3, 'custom_branding', 'Custom Branding', '1', 1, 5),
(21, 3, 'analytics', 'Analytics', 'Advanced', 0, 6),
(22, 3, 'support', 'Support', 'Priority', 0, 7),
(23, 3, 'api_access', 'API Access', '1', 1, 8),
(24, 3, 'webhooks', 'Webhooks', '1', 1, 9),
(25, 4, 'events', 'Events', 'Unlimited', 0, 1),
(26, 4, 'contestants', 'Contestants per Event', 'Unlimited', 0, 2),
(27, 4, 'votes', 'Votes per Event', 'Unlimited', 0, 3),
(28, 4, 'storage', 'Storage', '20 GB', 0, 4),
(29, 4, 'custom_branding', 'Custom Branding', '1', 1, 5),
(30, 4, 'analytics', 'Analytics', 'Premium', 0, 6),
(31, 4, 'support', 'Support', 'Dedicated', 0, 7),
(32, 4, 'api_access', 'API Access', '1', 1, 8),
(33, 4, 'webhooks', 'Webhooks', '1', 1, 9),
(34, 4, 'white_label', 'White Label', '1', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `platform_revenue`
--

CREATE TABLE `platform_revenue` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `revenue_type` enum('platform_fee','processing_fee','subscription_fee','other') DEFAULT 'platform_fee',
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `revenue_type` enum('platform_fee','tenant_share','referrer_commission','processing_fee') DEFAULT 'platform_fee',
  `percentage_applied` decimal(5,2) DEFAULT NULL,
  `original_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` varchar(255) DEFAULT NULL,
  `fee_rule_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue_shares`
--

INSERT INTO `revenue_shares` (`id`, `transaction_id`, `tenant_id`, `amount`, `revenue_type`, `percentage_applied`, `original_amount`, `description`, `fee_rule_id`, `created_at`) VALUES
(12, 56, 22, 0.35, 'platform_fee', NULL, 0.00, NULL, 9, '2025-10-10 21:38:07'),
(13, 57, 22, 0.35, 'platform_fee', NULL, 0.00, NULL, 9, '2025-10-10 21:44:20'),
(14, 58, 22, 35.00, 'platform_fee', NULL, 0.00, NULL, 9, '2025-10-10 21:50:48');

-- --------------------------------------------------------

--
-- Table structure for table `revenue_transactions`
--

CREATE TABLE `revenue_transactions` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `gross_amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL,
  `processing_fee` decimal(10,2) DEFAULT 0.00,
  `referrer_commission` decimal(10,2) DEFAULT 0.00,
  `net_tenant_amount` decimal(10,2) NOT NULL,
  `fee_rule_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`fee_rule_snapshot`)),
  `distribution_status` enum('pending','completed','failed') DEFAULT 'completed',
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
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billing_cycle` enum('monthly','yearly','lifetime','free') DEFAULT 'monthly',
  `max_events` int(11) DEFAULT NULL COMMENT 'NULL means unlimited',
  `max_contestants_per_event` int(11) DEFAULT NULL COMMENT 'NULL means unlimited',
  `max_votes_per_event` int(11) DEFAULT NULL COMMENT 'NULL means unlimited',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Additional features as JSON' CHECK (json_valid(`features`)),
  `fee_rule_id` int(11) DEFAULT NULL COMMENT 'Default fee rule for this plan',
  `is_popular` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(3) DEFAULT 0,
  `trial_days` int(3) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `description`, `price`, `billing_cycle`, `max_events`, `max_contestants_per_event`, `max_votes_per_event`, `features`, `fee_rule_id`, `is_popular`, `is_active`, `sort_order`, `trial_days`, `created_at`, `updated_at`) VALUES
(1, 'Free Starter', 'free', 'Perfect for trying out the platform', 0.00, 'free', 1, 10, 1000, '{\"custom_branding\": false, \"analytics\": \"basic\", \"support\": \"community\"}', NULL, 0, 1, 1, 14, '2025-10-10 13:36:30', '2025-10-10 13:36:30'),
(2, 'Basic Plan', 'basic', 'Great for small organizations and events', 29.99, 'monthly', 5, 50, 10000, '{\"custom_branding\": true, \"analytics\": \"standard\", \"support\": \"email\", \"api_access\": false}', NULL, 0, 1, 2, 7, '2025-10-10 13:36:30', '2025-10-10 13:36:30'),
(3, 'Professional', 'professional', 'Perfect for growing organizations', 99.99, 'monthly', NULL, NULL, NULL, '{\"custom_branding\": true, \"analytics\": \"advanced\", \"support\": \"priority\", \"api_access\": true, \"webhooks\": true}', NULL, 1, 1, 3, 7, '2025-10-10 13:36:30', '2025-10-10 13:36:30'),
(4, 'Enterprise', 'enterprise', 'For large organizations with unlimited needs', 299.99, 'monthly', NULL, NULL, NULL, '{\"custom_branding\": true, \"analytics\": \"premium\", \"support\": \"dedicated\", \"api_access\": true, \"webhooks\": true, \"white_label\": true}', NULL, 0, 1, 4, 14, '2025-10-10 13:36:30', '2025-10-10 13:36:30');

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
  `current_plan_id` int(11) DEFAULT NULL,
  `subscription_status` enum('active','trial','expired','cancelled','suspended') DEFAULT 'trial',
  `subscription_expires_at` timestamp NULL DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `email`, `phone`, `website`, `address`, `plan`, `current_plan_id`, `subscription_status`, `subscription_expires_at`, `trial_ends_at`, `active`, `verified`, `created_at`, `updated_at`) VALUES
(22, 'Hope For All', 'ekowmee@gmail.com', NULL, NULL, NULL, 'free', NULL, 'trial', NULL, NULL, 1, 1, '2025-10-10 20:31:58', '2025-10-10 20:32:28');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_balances`
--

CREATE TABLE `tenant_balances` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `available` decimal(10,2) DEFAULT 0.00,
  `pending` decimal(10,2) DEFAULT 0.00,
  `on_hold` decimal(10,2) DEFAULT 0.00,
  `total_earned` decimal(10,2) DEFAULT 0.00,
  `total_paid` decimal(10,2) DEFAULT 0.00,
  `last_payout_at` timestamp NULL DEFAULT NULL,
  `last_payout_amount` decimal(10,2) DEFAULT 0.00,
  `payout_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_balances`
--

INSERT INTO `tenant_balances` (`id`, `tenant_id`, `available`, `pending`, `on_hold`, `total_earned`, `total_paid`, `last_payout_at`, `last_payout_amount`, `payout_count`, `created_at`, `updated_at`) VALUES
(28, 22, 66.30, 0.00, 0.00, 66.30, 0.00, NULL, 0.00, 0, '2025-10-10 21:36:40', '2025-10-10 21:50:48');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_plan_history`
--

CREATE TABLE `tenant_plan_history` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `old_plan_id` int(11) DEFAULT NULL,
  `new_plan_id` int(11) NOT NULL,
  `changed_by` int(11) DEFAULT NULL COMMENT 'User ID who made the change',
  `change_reason` varchar(255) DEFAULT NULL,
  `effective_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(46, 22, 'otp_required', 'false', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(47, 22, 'leaderboard_lag_seconds', '30', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(48, 22, 'theme_json', '{\"primary_color\":\"#007bff\",\"secondary_color\":\"#6c757d\",\"success_color\":\"#28a745\",\"danger_color\":\"#dc3545\"}', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(49, 22, 'max_votes_per_msisdn', '10000', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(50, 22, 'fraud_detection_enabled', 'true', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(51, 22, 'webhook_enabled', 'false', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(52, 22, 'email_notifications_enabled', 'true', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(53, 22, 'sms_notifications_enabled', 'false', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(54, 22, 'auto_approve_events', 'false', '2025-10-10 20:31:58', '2025-10-10 20:31:58'),
(55, 22, 'minimum_payout_amount', '10', '2025-10-10 20:31:58', '2025-10-10 20:31:58');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_subscriptions`
--

CREATE TABLE `tenant_subscriptions` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `status` enum('active','expired','cancelled','suspended') DEFAULT 'active',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `auto_renew` tinyint(1) DEFAULT 1,
  `payment_method` varchar(50) DEFAULT NULL,
  `last_payment_at` timestamp NULL DEFAULT NULL,
  `next_payment_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_subscriptions`
--

INSERT INTO `tenant_subscriptions` (`id`, `tenant_id`, `plan_id`, `status`, `started_at`, `expires_at`, `cancelled_at`, `auto_renew`, `payment_method`, `last_payment_at`, `next_payment_at`, `created_at`, `updated_at`) VALUES
(14, 22, 1, 'active', '2025-10-10 20:31:58', NULL, NULL, 1, NULL, NULL, NULL, '2025-10-10 20:31:58', '2025-10-10 20:31:58');

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
(55, 22, 38, 79, 68, 22, 1.00, '0545644749', 'failed', 'momo', 'PAY_C93EEEE388', '', '', 'Payment failed. Please try again.', '2025-10-10 21:37:29', '2025-10-10 21:37:35'),
(56, 22, 38, 79, 68, 22, 1.00, '0545644749', 'success', 'momo', 'MPD2DC3725', '', '', NULL, '2025-10-10 21:38:01', '2025-10-10 21:38:07'),
(57, 22, 38, 79, 68, 22, 1.00, '0545644749', 'success', 'momo', 'MP465E4BD0', '', '', NULL, '2025-10-10 21:44:14', '2025-10-10 21:44:20'),
(58, 22, 38, 78, 68, 22, 100.00, '0545644749', 'success', 'momo', 'MPFB4C7980', '', '', NULL, '2025-10-10 21:50:41', '2025-10-10 21:50:47');

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
(3, NULL, 'ekowme@gmail.com', '$2y$10$IdVuCAw45Y7kkjsiqzBwIO99rvD6Fe0oSDQMAzL9DeJ7jzFUpEMFu', 'platform_admin', 1, '2025-10-10 20:31:21', '2025-10-03 11:36:30', '2025-10-10 20:31:21'),
(17, 22, 'ekowmee@gmail.com', '$2y$10$VVxsBptQb2P1E51R.kV6wuxE82yQnZgoMkaHiW0H.OGpCLLoXJlJa', 'owner', 1, '2025-10-10 20:32:41', '2025-10-10 20:31:58', '2025-10-10 20:32:41');

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
(40, 56, 22, 38, 79, 68, 1, '2025-10-10 21:38:07'),
(41, 57, 22, 38, 79, 68, 1, '2025-10-10 21:44:20'),
(42, 58, 22, 38, 78, 68, 100, '2025-10-10 21:50:47');

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
(22, 38, 'Single Vote', 1, 1.00, 1, '2025-10-10 20:50:22', '2025-10-10 20:50:22'),
(23, 38, 'Vote Pack (5)', 5, 4.50, 1, '2025-10-10 20:50:22', '2025-10-10 20:50:22'),
(24, 38, 'Vote Pack (10)', 10, 8.00, 1, '2025-10-10 20:50:22', '2025-10-10 20:50:22'),
(25, 38, 'Vote Pack (25)', 25, 17.50, 1, '2025-10-10 20:50:22', '2025-10-10 20:50:22');

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
(32, 40, 56, 22, 38, 79, NULL, 1, '06b245199f3574e784b1bda6fec5fb184ad056b42d247c381c3b6dfa53356dd2', '2025-10-10 21:38:07'),
(33, 41, 57, 22, 38, 79, NULL, 1, 'd6972f55bef73c5459ff87fc51484e966363c82486a43a36b4ea522d8333b8fe', '2025-10-10 21:44:20'),
(34, 42, 58, 22, 38, 78, NULL, 100, '9f3dbcb791d59c8ba372c1a43faece5d28e0cb3f17e8080632d086ed9990ce2d', '2025-10-10 21:50:48');

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
(32, 56, 'HRIXMBN1', '9f7061b64a5b7dd8c7e0a75a67d297f53254b4f996e402520825232c7bcc5652', '2025-10-10 21:38:07'),
(33, 57, '5SGT0PLX', 'b3664930698bc392fc6850ca809df80cbc02548b3da8dacf48f5ef8801bf638a', '2025-10-10 21:44:20'),
(34, 58, 'OA1GS8F2', '2b32e580fbb9775e17b9a7ca658924523636f6e6b8432a1f127a227129e9b140', '2025-10-10 21:50:48');

-- --------------------------------------------------------

--
-- Table structure for table `webhook_endpoints`
--

CREATE TABLE `webhook_endpoints` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `secret` varchar(255) DEFAULT NULL,
  `event_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`event_types`)),
  `retry_count` int(2) DEFAULT 3,
  `timeout_seconds` int(3) DEFAULT 10,
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
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `payout_method_id` (`payout_method_id`),
  ADD KEY `initiated_by` (`initiated_by`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `payout_methods`
--
ALTER TABLE `payout_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tenant_default` (`tenant_id`,`is_default`);

--
-- Indexes for table `payout_schedules`
--
ALTER TABLE `payout_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tenant_schedule` (`tenant_id`);

--
-- Indexes for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_plan_feature` (`plan_id`,`feature_key`),
  ADD KEY `idx_plan_sort` (`plan_id`,`sort_order`);

--
-- Indexes for table `platform_revenue`
--
ALTER TABLE `platform_revenue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `idx_type_date` (`revenue_type`,`created_at`),
  ADD KEY `idx_amount_date` (`amount`,`created_at`);

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
-- Indexes for table `revenue_transactions`
--
ALTER TABLE `revenue_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `idx_tenant_date` (`tenant_id`,`created_at`),
  ADD KEY `idx_event_date` (`event_id`,`created_at`),
  ADD KEY `idx_distribution_status` (`distribution_status`);

--
-- Indexes for table `risk_blocks`
--
ALTER TABLE `risk_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `idx_type_value_active` (`block_type`,`block_value`,`active`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fee_rule_id` (`fee_rule_id`),
  ADD KEY `idx_active_sort` (`is_active`,`sort_order`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_plan` (`plan`),
  ADD KEY `current_plan_id` (`current_plan_id`);

--
-- Indexes for table `tenant_balances`
--
ALTER TABLE `tenant_balances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `tenant_plan_history`
--
ALTER TABLE `tenant_plan_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `old_plan_id` (`old_plan_id`),
  ADD KEY `new_plan_id` (`new_plan_id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `idx_tenant_date` (`tenant_id`,`effective_date`);

--
-- Indexes for table `tenant_settings`
--
ALTER TABLE `tenant_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tenant_setting` (`tenant_id`,`setting_key`);

--
-- Indexes for table `tenant_subscriptions`
--
ALTER TABLE `tenant_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `idx_tenant_status` (`tenant_id`,`status`),
  ADD KEY `idx_expires_at` (`expires_at`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `contestants`
--
ALTER TABLE `contestants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `contestant_categories`
--
ALTER TABLE `contestant_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fraud_events`
--
ALTER TABLE `fraud_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaderboard_cache`
--
ALTER TABLE `leaderboard_cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payout_methods`
--
ALTER TABLE `payout_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payout_schedules`
--
ALTER TABLE `payout_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `plan_features`
--
ALTER TABLE `plan_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `platform_revenue`
--
ALTER TABLE `platform_revenue`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `revenue_transactions`
--
ALTER TABLE `revenue_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `risk_blocks`
--
ALTER TABLE `risk_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tenant_balances`
--
ALTER TABLE `tenant_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tenant_plan_history`
--
ALTER TABLE `tenant_plan_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tenant_settings`
--
ALTER TABLE `tenant_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tenant_subscriptions`
--
ALTER TABLE `tenant_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ussd_sessions`
--
ALTER TABLE `ussd_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `vote_bundles`
--
ALTER TABLE `vote_bundles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `vote_ledger`
--
ALTER TABLE `vote_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `vote_receipts`
--
ALTER TABLE `vote_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
  ADD CONSTRAINT `payouts_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payouts_ibfk_2` FOREIGN KEY (`payout_method_id`) REFERENCES `payout_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payouts_ibfk_3` FOREIGN KEY (`initiated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payouts_ibfk_4` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payout_methods`
--
ALTER TABLE `payout_methods`
  ADD CONSTRAINT `payout_methods_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payout_schedules`
--
ALTER TABLE `payout_schedules`
  ADD CONSTRAINT `payout_schedules_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD CONSTRAINT `plan_features_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `platform_revenue`
--
ALTER TABLE `platform_revenue`
  ADD CONSTRAINT `platform_revenue_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `revenue_shares`
--
ALTER TABLE `revenue_shares`
  ADD CONSTRAINT `revenue_shares_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revenue_shares_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revenue_shares_ibfk_3` FOREIGN KEY (`fee_rule_id`) REFERENCES `fee_rules` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `revenue_transactions`
--
ALTER TABLE `revenue_transactions`
  ADD CONSTRAINT `revenue_transactions_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revenue_transactions_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revenue_transactions_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `risk_blocks`
--
ALTER TABLE `risk_blocks`
  ADD CONSTRAINT `risk_blocks_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD CONSTRAINT `subscription_plans_ibfk_1` FOREIGN KEY (`fee_rule_id`) REFERENCES `fee_rules` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `tenants_ibfk_1` FOREIGN KEY (`current_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tenant_balances`
--
ALTER TABLE `tenant_balances`
  ADD CONSTRAINT `tenant_balances_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_plan_history`
--
ALTER TABLE `tenant_plan_history`
  ADD CONSTRAINT `tenant_plan_history_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tenant_plan_history_ibfk_2` FOREIGN KEY (`old_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tenant_plan_history_ibfk_3` FOREIGN KEY (`new_plan_id`) REFERENCES `subscription_plans` (`id`),
  ADD CONSTRAINT `tenant_plan_history_ibfk_4` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tenant_settings`
--
ALTER TABLE `tenant_settings`
  ADD CONSTRAINT `tenant_settings_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenant_subscriptions`
--
ALTER TABLE `tenant_subscriptions`
  ADD CONSTRAINT `tenant_subscriptions_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tenant_subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`);

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
