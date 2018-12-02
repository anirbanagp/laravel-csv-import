-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 31, 2018 at 11:49 AM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arogyam`
--

-- --------------------------------------------------------

--
-- Table structure for table `billings`
--

CREATE TABLE `billings` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `collector_id` int(11) DEFAULT '4',
  `doctor_name` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,0) NOT NULL,
  `discount_or_commission` decimal(10,0) NOT NULL,
  `paid_amount` decimal(10,0) NOT NULL,
  `status` enum('due','paid') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `billing_details`
--

CREATE TABLE `billing_details` (
  `id` int(11) NOT NULL,
  `billing_id` int(11) NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `test_price` decimal(10,0) NOT NULL,
  `net_price` decimal(10,0) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `collectors`
--

CREATE TABLE `collectors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `collectors`
--

INSERT INTO `collectors` (`id`, `name`, `code`, `phone_number`, `address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Direct', 'AHC-C-1006', NULL, NULL, '2018-10-30 18:22:03', '2018-10-30 18:22:03', NULL),
(2, 'Assoc 1', 'AHC-C-1007', '8981567890', 'street name', '2018-10-30 19:08:44', '2018-10-30 19:08:44', NULL),
(3, 'Assoc 2', 'AHC-C-1008', '8981567891', NULL, '2018-10-31 15:48:52', '2018-10-31 15:48:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `collectors_path_tests`
--

CREATE TABLE `collectors_path_tests` (
  `id` int(11) NOT NULL,
  `collector_id` int(11) NOT NULL,
  `path_test_id` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug_name` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `is_group` enum('yes','no') NOT NULL,
  `is_menu` enum('yes','no') NOT NULL DEFAULT 'yes',
  `icon` varchar(255) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_by` int(11) DEFAULT '1',
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `title`, `slug_name`, `parent_id`, `is_group`, `is_menu`, `icon`, `rank`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(15, 'Content Management', 'content-management', 0, 'yes', 'yes', 'notebook.svg', 6, 'inactive', 1, 0, '2018-10-25 10:35:29', '2018-08-01 12:50:16'),
(16, 'Content Page List', 'cms-page-', 15, 'yes', 'yes', '', 0, 'active', 1, 0, '2018-05-24 03:13:21', '0000-00-00 00:00:00'),
(17, 'View Content Page List', 'view-content-page-', 15, 'yes', 'yes', '', 0, 'inactive', 1, 0, '2018-05-24 03:13:41', '0000-00-00 00:00:00'),
(18, 'Settings', 'settings', 0, 'yes', 'yes', 'gears.svg', 7, 'active', 1, 0, '2018-10-25 10:35:29', '2018-05-29 06:21:45'),
(20, 'Role Management', 'role-management', 18, 'yes', 'yes', '', 0, 'active', 1, 0, '2018-01-05 08:28:07', '0000-00-00 00:00:00'),
(21, 'Site Settings', 'site-settings-', 18, 'yes', 'yes', NULL, 8, 'active', 1, 0, '2018-08-06 07:21:58', '2018-08-06 07:21:58'),
(22, 'Profile Settings', 'profile-settings', 18, 'yes', 'yes', '', 0, 'inactive', 1, 0, '2018-03-30 01:06:54', '2018-03-30 01:06:54'),
(34, 'Mail Template', 'mail-template-', 18, 'yes', 'yes', NULL, 7, 'inactive', 0, 0, '2018-10-25 10:18:02', '2018-10-25 10:18:02'),
(38, 'Module Management', 'module-management-', 18, 'yes', 'yes', '', NULL, 'active', 1, 0, '2018-02-05 06:49:08', '2018-02-04 22:49:08'),
(50, 'User Group Management', 'user-group-', 18, 'yes', 'yes', 'group_add', 1, 'active', 1, 0, '2018-05-24 03:05:34', '2018-05-24 03:05:34'),
(53, 'Role Permission Management', 'role-permission-management', 18, 'yes', 'yes', '', NULL, 'active', NULL, 0, '2018-03-30 01:01:45', '2018-03-30 01:01:45'),
(55, 'Users', 'users-', 0, 'no', 'yes', 'football-player.svg', 10, 'active', NULL, 0, '2018-10-25 10:35:29', '2018-08-06 07:21:19'),
(93, 'User activity log', 'user-activity-log-', 0, 'no', 'yes', 'browser.svg', 8, 'active', NULL, 0, '2018-10-25 10:35:29', '2018-08-17 11:52:47'),
(94, 'Tests', 'tests-', 0, 'no', 'yes', 'blood-sample.svg', 11, 'active', NULL, 0, '2018-10-25 10:49:06', '2018-10-25 10:34:10'),
(95, 'Associates', 'collectors-', 0, 'no', 'yes', 'id-card.svg', 10, 'active', NULL, 0, '2018-10-30 12:38:55', '2018-10-30 12:38:55'),
(96, 'Collectors commission', 'collectors-commission-', 0, 'no', 'no', NULL, NULL, 'active', NULL, 0, '2018-10-26 08:13:59', '2018-10-26 08:13:59'),
(97, 'Billing', 'billing-', 0, 'no', 'yes', 'bill.svg', 9, 'active', NULL, 0, '2018-10-26 11:11:57', '2018-10-26 11:11:57'),
(98, 'Financial Report', 'financial-report-', 0, 'no', 'yes', 'money.svg', 8, 'active', NULL, 0, '2018-10-29 07:24:23', '2018-10-29 07:19:47'),
(99, 'Payments', 'payments-', 0, 'no', 'no', NULL, NULL, 'active', NULL, 0, '2018-10-31 06:38:37', '2018-10-31 06:38:37');

-- --------------------------------------------------------

--
-- Table structure for table `path_tests`
--

CREATE TABLE `path_tests` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sample` varchar(255) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `ph_number` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `billing_id` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `can_add` tinyint(1) NOT NULL DEFAULT '0',
  `can_view` tinyint(1) NOT NULL DEFAULT '0',
  `can_modify` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `role_id`, `module_id`, `can_add`, `can_view`, `can_modify`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(15, 1, 15, 1, 1, 1, 'active', 1, 1, '2017-12-28 00:45:09', '2017-12-27 19:15:09'),
(16, 1, 16, 1, 1, 1, 'active', 1, 0, '2017-12-26 00:30:18', '0000-00-00 00:00:00'),
(17, 1, 17, 1, 1, 1, 'active', 1, 0, '2017-12-26 00:30:53', '0000-00-00 00:00:00'),
(18, 1, 18, 1, 1, 1, 'active', 1, 0, '2017-12-27 01:07:13', '0000-00-00 00:00:00'),
(20, 1, 20, 1, 1, 1, 'active', 1, 1, '2017-12-28 00:49:43', '2017-12-27 19:18:44'),
(21, 1, 21, 1, 1, 1, 'active', 1, 0, '2017-12-26 00:32:06', '0000-00-00 00:00:00'),
(22, 1, 22, 1, 1, 1, 'active', 1, 0, '2017-12-26 00:32:06', '0000-00-00 00:00:00'),
(46, 2, 16, 1, 1, 1, 'active', 1, 1, '2018-05-24 02:27:14', '2018-05-24 02:27:14'),
(48, 2, 22, 0, 0, 0, 'active', 1, 1, '2017-12-28 00:58:36', '2017-12-27 19:28:36'),
(51, 4, 20, 0, 0, 0, 'active', 1, 1, '2017-12-28 01:06:48', '2017-12-27 19:36:48'),
(52, 4, 21, 0, 0, 0, 'active', 1, 1, '2017-12-28 01:07:03', '2017-12-27 19:37:03'),
(65, 1, 34, 1, 1, 1, 'active', 1, 1, '2018-01-09 07:11:38', '2018-01-09 01:41:38'),
(67, 2, 21, 1, 1, 1, 'active', 1, 1, '2018-05-24 02:27:24', '2018-05-24 02:27:24'),
(70, 2, 18, 1, 1, 1, 'active', 1, 1, '2018-05-24 02:27:16', '2018-05-24 02:27:16'),
(71, 3, 18, 0, 0, 0, 'active', 1, 1, '2018-05-24 02:27:29', '2018-05-24 02:27:29'),
(72, 1, 38, 1, 1, 1, 'active', 1, 1, '2018-02-05 06:48:37', '2018-02-04 22:48:37'),
(89, 1, 50, 1, 1, 1, 'active', 1, 1, '2018-04-25 03:23:27', '2018-04-25 00:53:27'),
(92, 2, 50, 0, 0, 0, 'active', 1, 1, '2018-05-24 03:00:54', '2018-05-24 03:00:54'),
(98, 1, 53, 1, 1, 1, 'active', 1, 1, '2018-03-30 01:07:48', '2018-03-30 01:07:48'),
(112, 2, 15, 1, 1, 1, 'active', 1, 1, '2018-05-24 02:27:11', '2018-05-24 02:27:11'),
(113, 2, 34, 1, 1, 1, 'active', 1, 1, '2018-05-24 02:27:19', '2018-05-24 02:27:19'),
(114, 1, 55, 1, 1, 1, 'active', 1, 1, '2018-05-24 03:03:11', '2018-05-24 03:03:11'),
(115, 2, 55, 0, 0, 0, 'active', 1, 1, '2018-10-25 10:53:43', '2018-10-25 10:53:43'),
(116, 3, 55, 0, 0, 0, 'active', 1, 1, '2018-10-29 10:22:16', '2018-10-29 10:22:16'),
(188, 2, 20, 0, 0, 0, 'active', 1, 1, '2018-10-26 05:04:19', '2018-10-26 05:04:19'),
(189, 2, 53, 0, 0, 0, 'active', 1, 1, '2018-10-26 05:04:15', '2018-10-26 05:04:15'),
(190, 2, 38, 0, 0, 0, 'active', 1, 1, '2018-08-01 12:50:38', '2018-08-01 12:50:38'),
(217, 1, 93, 0, 1, 0, 'active', 1, 1, '2018-08-22 08:36:29', '2018-08-22 08:36:29'),
(218, 2, 93, 0, 0, 0, 'active', 1, 1, '2018-10-26 05:04:21', '2018-10-26 05:04:21'),
(219, 1, 94, 1, 1, 1, 'active', 1, 1, '2018-10-25 10:54:06', '2018-10-25 10:54:06'),
(220, 2, 94, 1, 1, 1, 'active', 1, 1, '2018-10-25 10:48:41', '2018-10-25 10:48:41'),
(221, 1, 95, 1, 1, 1, 'active', 1, 1, '2018-10-26 06:17:06', '2018-10-26 06:17:06'),
(222, 2, 95, 1, 1, 1, 'active', 1, 1, '2018-10-26 06:17:13', '2018-10-26 06:17:13'),
(223, 1, 96, 1, 1, 1, 'active', 1, 1, '2018-10-26 08:14:09', '2018-10-26 08:14:09'),
(224, 2, 96, 1, 1, 1, 'active', 1, 1, '2018-10-26 08:14:14', '2018-10-26 08:14:14'),
(225, 1, 97, 1, 1, 1, 'active', 1, 1, '2018-10-26 11:12:09', '2018-10-26 11:12:09'),
(226, 2, 97, 1, 1, 1, 'active', 1, 1, '2018-10-26 11:12:16', '2018-10-26 11:12:16'),
(227, 1, 98, 0, 1, 0, 'active', 1, 0, '2018-10-29 07:23:53', '0000-00-00 00:00:00'),
(228, 2, 98, 0, 1, 0, 'active', 1, 0, '2018-10-29 07:23:59', '0000-00-00 00:00:00'),
(229, 3, 97, 1, 1, 1, 'active', 1, 1, '2018-10-29 10:22:20', '2018-10-29 10:22:20'),
(230, 1, 99, 1, 1, 1, 'active', 1, 1, '2018-10-31 06:38:48', '2018-10-31 06:38:48'),
(231, 2, 99, 1, 1, 1, 'active', 1, 1, '2018-10-31 06:38:53', '2018-10-31 06:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `is_admin` enum('yes','no') NOT NULL DEFAULT 'yes',
  `site_admin` enum('yes','no') NOT NULL DEFAULT 'no',
  `status` enum('active','inactive') NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'record user activity',
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `is_admin`, `site_admin`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super Admin', 'yes', 'yes', 'active', 1, 0, '2018-07-03 06:43:10', '2018-07-03 06:43:10', NULL),
(2, 'Admin', 'yes', 'yes', 'active', 1, 0, '2018-07-03 05:50:51', '2018-07-03 05:50:51', NULL),
(3, 'Receptionist', 'yes', 'no', 'active', 1, 0, '2018-10-29 10:22:02', '2018-10-29 10:22:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL,
  `can_add` tinyint(1) NOT NULL DEFAULT '0',
  `can_view` tinyint(1) NOT NULL DEFAULT '0',
  `can_modify` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `r_id`, `can_add`, `can_view`, `can_modify`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 'active', 1, 1, '2018-04-25 08:58:57', '2018-04-25 06:28:57'),
(2, 1, 2, 1, 1, 1, 'active', 1, 1, '2018-03-30 06:41:22', '2018-03-30 06:41:22'),
(3, 1, 3, 1, 1, 1, 'active', 1, 1, '2018-03-30 06:41:28', '2018-03-30 06:41:28'),
(8, 3, 3, 1, 1, 1, 'active', 1, 1, '2018-03-30 06:59:34', '2018-03-30 06:59:34'),
(9, 2, 2, 1, 1, 1, 'active', 1, 1, '2018-03-30 06:59:46', '2018-03-30 06:59:46'),
(10, 2, 3, 1, 1, 1, 'active', 1, 1, '2018-03-30 06:59:47', '2018-03-30 06:59:47'),
(11, 3, 2, 1, 1, 1, 'active', 1, 1, '2018-04-07 10:30:20', '2018-04-07 08:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_title` varchar(255) DEFAULT NULL,
  `admin_email` varchar(255) DEFAULT NULL,
  `image` varchar(225) DEFAULT NULL,
  `currency` enum('USD','INR') NOT NULL DEFAULT 'INR',
  `status` enum('active','inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_title`, `admin_email`, `image`, `currency`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Arogyam', 'anirban16.wgt@gmail.com', 'site_settings/CkZ2MuKQlBAyDWU2JkXjX5XD1ttgu3G3hg1czvzk.png', 'USD', 'active', '2018-10-31 10:31:52', '2018-10-31 10:31:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `unique_code` varchar(255) NOT NULL,
  `otp` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `parent_id`, `full_name`, `username`, `email`, `mobile`, `password`, `unique_code`, `otp`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Anirban Saha', 'anirban', 'anirban16.wgt@gmail.com', '8348865886', '965b21f9b0929eb034918f57a06065a8', 'jGHoTSVMhtAcm65cAwhD4rOy2C6NDN0O1qzsdxulN7Wx3vRQEn', 0, 'active', NULL, '2018-10-29 10:29:08', '2018-10-29 10:29:08'),
(2, 2, 1, 'Admin', 'arogyam-admin', 'admin@arogyam.com', '1234567890', 'e6e061838856bf47e1de730719fb2609', '', 0, 'active', NULL, '2018-10-29 10:28:27', '2018-10-29 10:27:14');

-- --------------------------------------------------------

--
-- Table structure for table `users_activity_logs`
--

CREATE TABLE `users_activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event` varchar(255) NOT NULL,
  `old_data` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_image` text,
  `country_id` int(11) NOT NULL,
  `address` text,
  `sex` varchar(10) NOT NULL,
  `language_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `profile_image`, `country_id`, `address`, `sex`, `language_id`, `currency_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'user_image/gp83oh5uC7pRRHnbH5I3gq5LZfmW7aPTCW3B5i8e.jpeg', 0, NULL, 'male', 0, 0, '2018-10-25 10:17:02', '2018-10-25 10:17:01'),
(2, 2, 'user_image/RMitbpvt4ENKvsBg6DiSl3kQP3ELXmsQBbtQ68ZQ.jpeg', 0, NULL, '', 0, 0, '2018-10-26 10:14:25', '2018-10-26 10:14:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billings`
--
ALTER TABLE `billings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `billing_details`
--
ALTER TABLE `billing_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_record_id` (`billing_id`);

--
-- Indexes for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collectors`
--
ALTER TABLE `collectors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `collectors_path_tests`
--
ALTER TABLE `collectors_path_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `collector_id` (`collector_id`),
  ADD KEY `path_test_id` (`path_test_id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_name` (`slug_name`),
  ADD KEY `title` (`title`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `path_tests`
--
ALTER TABLE `path_tests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`sample`),
  ADD KEY `deleted_at` (`deleted_at`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `ph_number` (`ph_number`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `billing_id` (`billing_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `module_id` (`module_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `create` (`can_add`),
  ADD KEY `view` (`can_view`),
  ADD KEY `modify` (`can_modify`) USING BTREE;

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_name` (`role_name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `module_id` (`r_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `create` (`can_add`),
  ADD KEY `view` (`can_view`),
  ADD KEY `modify` (`can_modify`) USING BTREE;

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `full_name` (`full_name`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`),
  ADD KEY `password` (`password`),
  ADD KEY `unique_code` (`unique_code`),
  ADD KEY `otp` (`otp`),
  ADD KEY `deleted_at` (`deleted_at`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `users_activity_logs`
--
ALTER TABLE `users_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `profile_image` (`profile_image`(767)),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `address` (`address`(767)),
  ADD KEY `sex` (`sex`),
  ADD KEY `base_language_id` (`language_id`),
  ADD KEY `base_currency_id` (`currency_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billings`
--
ALTER TABLE `billings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing_details`
--
ALTER TABLE `billing_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collectors`
--
ALTER TABLE `collectors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `collectors_path_tests`
--
ALTER TABLE `collectors_path_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `path_tests`
--
ALTER TABLE `path_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_activity_logs`
--
ALTER TABLE `users_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing_details`
--
ALTER TABLE `billing_details`
  ADD CONSTRAINT `details_billing` FOREIGN KEY (`billing_id`) REFERENCES `billings` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `collectors_path_tests`
--
ALTER TABLE `collectors_path_tests`
  ADD CONSTRAINT `collectors_test` FOREIGN KEY (`path_test_id`) REFERENCES `path_tests` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `path_tests_collector` FOREIGN KEY (`collector_id`) REFERENCES `collectors` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_billing` FOREIGN KEY (`billing_id`) REFERENCES `billings` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `apex_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
