-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 10:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_wash`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `check_in_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `check_in_time`) VALUES
(1, 3, '2024-07-23 23:04:05'),
(2, 2, '2024-07-23 23:35:32'),
(3, 2, '2024-07-23 23:35:39'),
(4, 2, '2024-07-23 23:39:05'),
(5, 2, '2024-07-23 23:39:05'),
(6, 2, '2024-07-23 23:39:05'),
(7, 2, '2024-07-23 23:39:05'),
(8, 2, '2024-07-23 23:39:05'),
(9, 2, '2024-07-23 23:39:05'),
(10, 2, '2024-07-23 23:39:06'),
(11, 2, '2024-07-23 23:39:06'),
(12, 2, '2024-07-23 23:39:06'),
(13, 2, '2024-07-23 23:39:06'),
(14, 2, '2024-07-23 23:39:06'),
(15, 2, '2024-07-23 23:39:08'),
(16, 2, '2024-07-23 23:39:08'),
(17, 2, '2024-07-23 23:39:08'),
(18, 2, '2024-07-23 23:39:08'),
(19, 2, '2024-07-23 23:39:08'),
(20, 2, '2024-07-23 23:39:09'),
(21, 2, '2024-07-23 23:39:09'),
(22, 2, '2024-07-23 23:39:09'),
(23, 2, '2024-07-23 23:39:09'),
(24, 2, '2024-07-23 23:39:09'),
(25, 2, '2024-07-23 23:39:09'),
(26, 2, '2024-07-23 23:39:14'),
(27, 2, '2024-07-23 23:39:17'),
(28, 2, '2024-07-23 23:42:02'),
(29, 2, '2024-07-23 23:42:02'),
(30, 2, '2024-07-23 23:42:02'),
(31, 2, '2024-07-23 23:42:05'),
(32, 2, '2024-07-23 23:45:16'),
(33, 2, '2024-07-23 23:45:16'),
(34, 2, '2024-07-23 23:45:16'),
(35, 2, '2024-07-23 23:45:17'),
(36, 2, '2024-07-23 23:45:17'),
(37, 2, '2024-07-23 23:45:17'),
(38, 2, '2024-07-23 23:45:17'),
(39, 2, '2024-07-23 23:45:17'),
(40, 2, '2024-07-23 23:45:17'),
(41, 2, '2024-07-23 23:45:17'),
(42, 2, '2024-07-23 23:45:17'),
(43, 2, '2024-07-23 23:45:22'),
(44, 2, '2024-07-23 23:45:22'),
(45, 2, '2024-07-23 23:45:22'),
(46, 2, '2024-07-23 23:45:23'),
(47, 2, '2024-07-23 23:45:23'),
(48, 2, '2024-07-23 23:45:23'),
(49, 2, '2024-07-23 23:45:23'),
(50, 2, '2024-07-23 23:45:23'),
(51, 2, '2024-07-23 23:45:23'),
(52, 2, '2024-07-23 23:45:23'),
(53, 2, '2024-07-23 23:45:23'),
(54, 2, '2024-07-23 23:45:24'),
(55, 2, '2024-07-23 23:45:30'),
(56, 2, '2024-07-23 23:53:48'),
(57, 2, '2024-07-23 23:53:51'),
(58, 2, '2024-07-23 23:59:30'),
(59, 2, '2024-07-23 23:59:51'),
(60, 2, '2024-07-23 23:59:51'),
(61, 2, '2024-07-23 23:59:51'),
(62, 2, '2024-07-23 23:59:54'),
(63, 3, '2024-07-24 00:01:57'),
(64, 3, '2024-07-24 00:01:58'),
(65, 3, '2024-07-24 00:01:58'),
(66, 3, '2024-07-24 00:01:58'),
(67, 3, '2024-07-24 00:01:58'),
(68, 3, '2024-07-24 00:01:58'),
(69, 3, '2024-07-24 00:01:59'),
(70, 3, '2024-07-24 00:01:59'),
(71, 3, '2024-07-24 00:01:59'),
(72, 3, '2024-07-24 00:01:59'),
(73, 3, '2024-07-24 00:01:59'),
(74, 3, '2024-07-24 00:01:59'),
(75, 3, '2024-07-24 00:02:00'),
(76, 3, '2024-07-24 00:02:00'),
(77, 3, '2024-07-24 00:02:00'),
(78, 3, '2024-07-24 00:02:00'),
(79, 3, '2024-07-24 00:02:00'),
(80, 3, '2024-07-24 00:02:00'),
(81, 3, '2024-07-24 00:02:01'),
(82, 3, '2024-07-24 00:02:01'),
(83, 3, '2024-07-24 00:02:01'),
(84, 3, '2024-07-24 00:02:23'),
(85, 3, '2024-07-24 00:02:23'),
(86, 3, '2024-07-24 00:02:24'),
(87, 3, '2024-07-24 00:02:24'),
(88, 3, '2024-07-24 00:02:27'),
(89, 3, '2024-07-24 00:06:30'),
(90, 3, '2024-07-24 00:06:34'),
(91, 3, '2024-09-27 18:21:46'),
(92, 3, '2024-09-27 18:21:46'),
(93, 3, '2024-09-27 18:21:46'),
(94, 3, '2024-09-27 18:21:46'),
(95, 3, '2024-09-27 18:21:49'),
(96, 3, '2024-09-27 18:22:57'),
(97, 3, '2024-09-27 18:22:59'),
(98, 3, '2024-09-27 18:24:07'),
(99, 3, '2024-09-27 18:24:09'),
(100, 3, '2024-09-27 18:24:17'),
(101, 3, '2024-09-27 18:25:22'),
(102, 3, '2024-09-27 18:25:24'),
(103, 3, '2024-09-27 18:46:14'),
(104, 3, '2024-09-27 18:46:43'),
(105, 3, '2024-09-27 18:47:29'),
(106, 3, '2024-09-27 20:59:32'),
(107, 3, '2024-09-27 20:59:39'),
(108, 3, '2024-09-27 21:08:14'),
(109, 3, '2024-09-27 21:13:10'),
(110, 3, '2024-09-27 21:13:22'),
(111, 3, '2024-09-27 21:13:22'),
(112, 3, '2024-09-27 21:13:22'),
(113, 3, '2024-09-27 21:14:13'),
(114, 3, '2024-09-27 21:33:17'),
(115, 3, '2024-09-27 21:33:31'),
(116, 3, '2024-09-27 22:30:07'),
(117, 3, '2024-09-27 22:36:58'),
(118, 3, '2024-09-27 22:41:28'),
(119, 13, '2024-09-29 12:14:44'),
(120, 13, '2024-09-29 12:16:09'),
(121, 13, '2024-09-29 12:16:09'),
(122, 15, '2024-09-29 12:16:19'),
(123, 15, '2024-09-29 16:19:11'),
(124, 14, '2024-09-29 16:19:19'),
(125, 13, '2024-09-29 16:39:57'),
(126, 13, '2024-09-29 16:43:14'),
(127, 13, '2024-09-29 16:50:07'),
(128, 14, '2024-09-29 16:50:12'),
(129, 14, '2024-09-29 16:50:13'),
(130, 14, '2024-09-29 16:53:32'),
(131, 13, '2024-09-29 16:56:26'),
(132, 13, '2024-09-29 16:57:50'),
(133, 13, '2024-09-29 16:57:57'),
(134, 15, '2024-09-29 16:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `booked_slots`
--

CREATE TABLE `booked_slots` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `booking_datetime` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_washes`
--

CREATE TABLE `car_washes` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `client_name` varchar(100) NOT NULL,
  `car_number` varchar(20) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `arrival_time` datetime NOT NULL,
  `delivery_time` datetime DEFAULT NULL,
  `service_type` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `car_type` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car_washes`
--

INSERT INTO `car_washes` (`id`, `employee_id`, `client_name`, `car_number`, `phone_number`, `arrival_time`, `delivery_time`, `service_type`, `total_amount`, `car_type`, `notes`) VALUES
(3, 2, 'NASSER ALBATRANI', '66995S', '71122755', '2024-06-22 05:40:16', '2024-06-22 10:40:00', 'المركبة بالكامل', 900.00, NULL, NULL),
(4, 2, 'NASSER ALBATRANI', '66995S', '71122755', '2024-06-22 05:43:13', '2024-06-22 19:43:00', 'المركبة بالكامل', 900.00, NULL, NULL),
(5, 2, 'nasser', '66995', '71122755', '2024-06-22 05:52:09', '2024-06-28 09:35:00', 'النانو سيراميك, المركبة بالكامل', 1360.00, NULL, NULL),
(6, 2, 'nasser', '66995', '71122755', '2024-06-22 05:53:35', '2024-06-28 09:35:00', '0', 0.00, NULL, 'hugdf'),
(7, 2, 'NASSER ALBATRANI', '66995S', '71122755', '2024-06-22 05:43:13', '2024-06-22 19:43:00', '0', 900.00, NULL, 'Please I want Today');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `car_type` enum('saloon','4wheel') NOT NULL,
  `service_level` enum('simple','special') NOT NULL DEFAULT 'simple',
  `access` enum('manager','employee') NOT NULL DEFAULT 'manager'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `car_type`, `service_level`, `access`) VALUES
(31, 'CAR WASH SERVICE/خدمات الغسيل', '2024-07-31 17:26:55', 'saloon', 'simple', 'employee'),
(32, 'Polishing Services/خدمات التلميع', '2024-08-01 13:56:11', 'saloon', 'simple', 'employee'),
(33, 'PPF/جلاد الحماية ', '2024-08-01 14:29:34', 'saloon', 'special', 'employee'),
(34, 'Nano ceramic/نانوسيراميك', '2024-08-01 14:41:07', 'saloon', 'simple', 'employee');

-- --------------------------------------------------------

--
-- Table structure for table `client_cars`
--

CREATE TABLE `client_cars` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `car_number` varchar(20) NOT NULL,
  `car_name` varchar(100) NOT NULL,
  `car_type` enum('saloon','4wheel') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `client_name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_cars`
--

INSERT INTO `client_cars` (`id`, `employee_id`, `car_number`, `car_name`, `car_type`, `created_at`, `updated_at`, `client_name`, `phone`) VALUES
(1, 19, 'dgffffg', 'dffgffd', 'saloon', '2024-12-08 20:06:22', '2024-12-08 20:06:22', 'fgeeerrr', '33445453'),
(2, 19, 'AW99432', 'Ahmed car', 'saloon', '2024-12-08 20:43:01', '2024-12-08 20:43:01', 'user', '88458854'),
(3, 19, 'wd33', 'sadasdf', '4wheel', '2024-12-08 20:44:06', '2024-12-08 20:44:06', 'user', '88458854'),
(4, 20, '102', 'camry', 'saloon', '2024-12-09 18:19:57', '2024-12-09 18:19:57', 'ahmed', '99999999');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `coupon_code` varchar(255) NOT NULL,
  `discount_amount` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_code`, `discount_amount`) VALUES
(3, '1', 1.000),
(4, '2', 2.000),
(5, '3', 3.000),
(6, '4', 4.000),
(7, '5', 5.000);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_name` varchar(100) DEFAULT NULL,
  `expense_amount` decimal(10,2) DEFAULT NULL,
  `expense_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_name`, `expense_amount`, `expense_date`) VALUES
(1, 'Electric ', 50.00, '2024-06-28'),
(2, 'Patrol', 20.00, '2024-06-28');

-- --------------------------------------------------------

--
-- Table structure for table `external_settings`
--

CREATE TABLE `external_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `external_settings`
--

INSERT INTO `external_settings` (`id`, `name`, `image`) VALUES
(2, 'العمال ', 'uploads/usertoplevel.png'),
(3, 'الفئات', 'uploads/categorytoplevel.png'),
(4, 'تفاصيل الطلبات', 'uploads/ordertoplevel.jpg'),
(5, 'الكوبون', 'uploads/cop.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `installments`
--

CREATE TABLE `installments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,3) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internal_settings`
--

CREATE TABLE `internal_settings` (
  `id` int(11) NOT NULL,
  `external_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internal_settings`
--

INSERT INTO `internal_settings` (`id`, `external_id`, `name`, `link`, `image`) VALUES
(7, 2, 'Add New ', 'register.php', 'uploads/360_F_604547101_Pbl3NGslWXEseBTvqruF5UneRypQV2M5.jpg'),
(8, 2, 'Edit&Delete', 'users_list.php', 'uploads/33.jpeg'),
(9, 3, 'Add new category', 'categories.php', 'uploads/add-category.png'),
(10, 3, 'Edit&Delete', 'manage_categories_services.php', 'uploads/edit-246.png'),
(11, 4, 'الجرد', 'view_orders.php', 'uploads/IMG_4427.png'),
(12, 5, 'Add&Edit Coupoun', 'https://toplevelom.com/manage_coupons.php', 'uploads/cop.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `message`, `created_at`) VALUES
(1, 3, 'NASSER', '2024-06-28 13:05:55'),
(2, 3, 'Yes', '2024-06-28 13:06:08'),
(3, 3, 'How are you ?', '2024-06-28 13:06:25'),
(4, 2, 'yes', '2024-06-28 13:07:11'),
(5, 3, 'Hello my name nasser', '2024-06-28 13:09:30'),
(6, 2, 'اهلا و سهلا ', '2024-06-28 13:13:58'),
(7, 3, 'yes ', '2024-06-29 10:38:40');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_car_id` int(11) DEFAULT NULL,
  `order_details` text DEFAULT NULL,
  `qrcode_path` varchar(255) DEFAULT NULL,
  `car_type` varchar(50) DEFAULT NULL,
  `employee_name` varchar(100) DEFAULT NULL,
  `client_name` varchar(100) DEFAULT NULL,
  `service_category` varchar(50) DEFAULT NULL,
  `services` text DEFAULT NULL,
  `car_number` varchar(50) DEFAULT NULL,
  `car_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `arrival_time` datetime DEFAULT NULL,
  `delivery_time` datetime DEFAULT NULL,
  `total_amount` decimal(10,3) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `order_code` varchar(255) DEFAULT NULL,
  `deposit_amount` decimal(10,3) NOT NULL DEFAULT 0.000,
  `remaining_amount` decimal(10,3) NOT NULL DEFAULT 0.000,
  `installments` int(11) NOT NULL DEFAULT 0,
  `coupon_code` varchar(255) DEFAULT NULL,
  `coupon_discount` decimal(10,3) DEFAULT 0.000,
  `total_after_discount` decimal(10,3) DEFAULT 0.000,
  `status` enum('pending','completed','canceled') DEFAULT 'pending',
  `car_delivered` tinyint(1) NOT NULL DEFAULT 0,
  `car_delivery_time` datetime DEFAULT NULL,
  `extra_service_name` text DEFAULT NULL,
  `extra_service_price` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_car_id`, `order_details`, `qrcode_path`, `car_type`, `employee_name`, `client_name`, `service_category`, `services`, `car_number`, `car_name`, `phone_number`, `arrival_time`, `delivery_time`, `total_amount`, `notes`, `order_code`, `deposit_amount`, `remaining_amount`, `installments`, `coupon_code`, `coupon_discount`, `total_after_discount`, `status`, `car_delivered`, `car_delivery_time`, `extra_service_name`, `extra_service_price`) VALUES
(284, NULL, NULL, NULL, 'saloon', 'NASSER ALBATRANI', 'NASSER ALBATRANI', '31', 'Car wash insida and outside shampo ceramic / غسيل السيارة من الداخل و الخارج بشامبو سيراميك', '66995 s', 'PORSCHE', '71122755', '2024-09-26 13:17:00', '0000-00-00 00:00:00', 2.000, '', 'order_66f526ef63eb0', 0.000, 2.000, 0, '', 0.000, 2.000, 'pending', 0, NULL, '', '0'),
(285, 2, NULL, NULL, 'saloon', 'user', 'user', NULL, '[\"inside and outside polishing\\/\\u062a\\u0644\\u0645\\u064a\\u0639 \\u062f\\u0627\\u062e\\u0644\\u064a \\u0648 \\u062e\\u0627\\u0631\\u062c\\u064a\"]', 'AW99432', 'Ahmed car', '88458854', '2024-12-09 01:53:58', NULL, 35.000, NULL, 'ORD-2024-285', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(286, 2, NULL, NULL, 'saloon', 'user', 'user', NULL, '[\"inside and outside polishing\\/\\u062a\\u0644\\u0645\\u064a\\u0639 \\u062f\\u0627\\u062e\\u0644\\u064a \\u0648 \\u062e\\u0627\\u0631\\u062c\\u064a\",\"Car wash inside and outside \\/ \\u063a\\u0633\\u064a\\u0644 \\u0627\\u0644\\u0633\\u064a\\u0627\\u0631\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062f\\u0627\\u062e\\u0644 \\u0648 \\u0627\\u0644\\u062e\\u0627\\u0631\\u062c \"]', 'AW99432', 'Ahmed car', '88458854', '2024-12-09 02:00:32', NULL, 36.500, NULL, 'ORD-2024-286', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(287, 1, NULL, NULL, 'saloon', 'user', 'fgeeerrr', NULL, '[\"inside and outside polishing\\/\\u062a\\u0644\\u0645\\u064a\\u0639 \\u062f\\u0627\\u062e\\u0644\\u064a \\u0648 \\u062e\\u0627\\u0631\\u062c\\u064a\"]', 'dgffffg', 'dffgffd', '88458854', '2024-12-09 02:33:11', NULL, 45.000, NULL, 'ORD-2024-287', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(288, 1, NULL, NULL, 'saloon', 'user', 'fgeeerrr', NULL, '[\"inside and outside polishing\\/\\u062a\\u0644\\u0645\\u064a\\u0639 \\u062f\\u0627\\u062e\\u0644\\u064a \\u0648 \\u062e\\u0627\\u0631\\u062c\\u064a\",\"Basic parts of the vehicle\\/\\u0627\\u0644\\u0623\\u062c\\u0632\\u0627\\u0621 \\u0627\\u0644\\u0623\\u0633\\u0627\\u0633\\u064a\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u0645\\u0631\\u0643\\u0628\\u0629\",\"Basic parts of the vehicle\\/\\u0627\\u0644\\u0623\\u062c\\u0632\\u0627\\u0621 \\u0627\\u0644\\u0623\\u0633\\u0627\\u0633\\u064a\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u0645\\u0631\\u0643\\u0628\\u0629\"]', 'dgffffg', 'dffgffd', '88458854', '2024-12-09 02:34:23', NULL, 95.000, NULL, 'ORD-2024-288', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(289, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[\"Car wash insida and outside shampo ceramic \\/ \\u063a\\u0633\\u064a\\u0644 \\u0627\\u0644\\u0633\\u064a\\u0627\\u0631\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062f\\u0627\\u062e\\u0644 \\u0648 \\u0627\\u0644\\u062e\\u0627\\u0631\\u062c \\u0628\\u0634\\u0627\\u0645\\u0628\\u0648 \\u0633\\u064a\\u0631\\u0627\\u0645\\u064a\\u0643\",\"Car wash insida and outside shampo ceramic \\/ \\u063a\\u0633\\u064a\\u0644 \\u0627\\u0644\\u0633\\u064a\\u0627\\u0631\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062f\\u0627\\u062e\\u0644 \\u0648 \\u0627\\u0644\\u062e\\u0627\\u0631\\u062c \\u0628\\u0634\\u0627\\u0645\\u0628\\u0648 \\u0633\\u064a\\u0631\\u0627\\u0645\\u064a\\u0643\"]', '102', 'camry', '99999999', '2024-12-09 22:26:54', NULL, 4.500, NULL, 'ORD-2024-289', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'completed', 1, '2024-12-09 22:47:33', NULL, NULL),
(290, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[\"Car wash insida and outside shampo ceramic \\/ \\u063a\\u0633\\u064a\\u0644 \\u0627\\u0644\\u0633\\u064a\\u0627\\u0631\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062f\\u0627\\u062e\\u0644 \\u0648 \\u0627\\u0644\\u062e\\u0627\\u0631\\u062c \\u0628\\u0634\\u0627\\u0645\\u0628\\u0648 \\u0633\\u064a\\u0631\\u0627\\u0645\\u064a\\u0643\",\"Car wash insida and outside shampo ceramic \\/ \\u063a\\u0633\\u064a\\u0644 \\u0627\\u0644\\u0633\\u064a\\u0627\\u0631\\u0629 \\u0645\\u0646 \\u0627\\u0644\\u062f\\u0627\\u062e\\u0644 \\u0648 \\u0627\\u0644\\u062e\\u0627\\u0631\\u062c \\u0628\\u0634\\u0627\\u0645\\u0628\\u0648 \\u0633\\u064a\\u0631\\u0627\\u0645\\u064a\\u0643\"]', '102', 'camry', '99999999', '2024-12-11 00:45:09', NULL, 4.500, NULL, 'ORD-2024-290', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(291, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[]', '102', 'camry', '99999999', '2024-12-11 00:45:47', NULL, 0.000, NULL, 'ORD-2024-291', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(292, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[]', '102', 'camry', '99999999', '2024-12-11 00:54:47', NULL, 0.000, NULL, 'ORD-2024-292', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(293, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[]', '102', 'camry', '99999999', '2024-12-11 00:55:21', NULL, 0.000, NULL, 'ORD-2024-293', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(294, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[]', '102', 'camry', '99999999', '2024-12-11 01:00:52', NULL, 0.000, NULL, 'ORD-2024-294', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(295, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[]', '102', 'camry', '99999999', '2024-12-11 01:01:27', NULL, 0.000, NULL, 'ORD-2024-295', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(296, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[]', '102', 'camry', '99999999', '2024-12-11 01:03:20', NULL, 0.000, NULL, 'ORD-2024-296', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL),
(297, 4, NULL, NULL, 'saloon', 'o', 'ahmed', NULL, '[]', '102', 'camry', '99999999', '2024-12-11 01:05:23', NULL, 0.000, NULL, 'ORD-2024-297', 0.000, 0.000, 0, NULL, 0.000, 0.000, 'pending', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `car_type` varchar(255) NOT NULL,
  `service_level` enum('simple','special') NOT NULL DEFAULT 'simple'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category_id`, `name`, `price`, `created_at`, `car_type`, `service_level`) VALUES
(51, 32, 'inside and outside polishing/تلميع داخلي و خارجي', 35.000, '2024-08-01 14:11:17', 'saloon', 'simple'),
(52, 32, 'inside and outside polishing/تلميع داخلي و خارجي', 45.000, '2024-08-01 14:14:42', '4', 'simple'),
(53, 32, 'inside polishing/تلميع داخلي', 20.000, '2024-08-01 14:15:58', 'saloon', 'simple'),
(54, 32, 'inside polishing/تلميع داخلي ', 30.000, '2024-08-01 14:20:49', '4wheel', 'simple'),
(55, 32, 'Outside Polishing/تلميع خارجي', 25.000, '2024-08-01 14:28:36', 'saloon', 'simple'),
(56, 32, 'Outside Polishing/تلميع خارجي', 35.000, '2024-08-01 14:29:02', '4wheel', 'simple'),
(57, 33, 'front / الواجهة الأمامية', 350.000, '2024-08-01 14:30:37', 'saloon', 'simple'),
(58, 33, 'front/الواجهة الأمامية', 450.000, '2024-08-01 14:31:10', '4wheel', 'simple'),
(59, 33, 'Basic parts of the vehicle/الأجزاء الأساسية من المركبة', 25.000, '2024-08-01 14:32:21', 'saloon', 'simple'),
(60, 33, 'Basic parts of the vehicle/الأجزاء الأساسية من المركبة', 35.000, '2024-08-01 14:32:44', '4wheel', 'simple'),
(61, 33, 'The whole car/المركبة بالكامل', 900.000, '2024-08-01 14:33:43', 'saloon', 'simple'),
(62, 33, 'The whole car/المركبة بالكامل', 1200.000, '2024-08-01 14:33:55', '4wheel', 'simple'),
(63, 34, 'Nano ceramic/نانوسيراميك', 140.000, '2024-08-01 14:41:57', 'saloon', 'simple'),
(64, 34, 'Nano ceramic/نانوسيراميك', 160.000, '2024-08-01 14:42:12', '4wheel', 'simple'),
(69, 31, 'Car wash insida and outside shampo ceramic / غسيل السيارة من الداخل و الخارج بشامبو سيراميك', 2.000, '2024-09-25 14:39:29', 'saloon', 'simple'),
(70, 31, 'Car wash insida and outside shampo ceramic / غسيل السيارة من الداخل و الخارج بشامبو سيراميك', 2.500, '2024-09-25 14:40:52', '4wheel', 'simple'),
(71, 31, 'Light - Engine and under car / غسيل الأضواء و المكينة و أسفل السيارة', 15.000, '2024-09-25 14:43:06', 'saloon', 'simple'),
(72, 31, 'Light - Engine and under car / غسيل الأضواء و المكينة و أسفل السيارة', 15.000, '2024-09-25 14:43:18', '4wheel', 'simple'),
(74, 31, 'Car wash special/ الخدمة الخاصة لغسيل السيارة', 3.000, '2024-09-25 14:44:44', 'saloon', 'simple'),
(75, 31, 'Car wash special/ الخدمة الخاصة لغسيل السيارة', 4.000, '2024-09-25 14:44:59', '4wheel', 'simple'),
(76, 31, 'Car wash inside and outside / غسيل السيارة من الداخل و الخارج ', 1.500, '2024-09-25 14:46:40', 'saloon', 'simple'),
(77, 31, 'Car wash inside and outside / غسيل السيارة من الداخل و الخارج ', 2.000, '2024-09-25 14:46:49', '4wheel', 'simple');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `link`, `image`, `icon`) VALUES
(1, 'Categor', 'categories.php', 'uploads/black-panther-painting-paul-meijering.jpg', ''),
(2, 'Admin', 'register.php', 'uploads/add-employee-icon.jpg', 'uploads/add-employee-icon.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('manager','employee') NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `employee_name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qr_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `phone`, `email`, `employee_name`, `image`, `salary`, `qr_code`) VALUES
(2, 'Moh', '$2y$10$47gPvYslZCyuktsvv.PcU.Uxa06x0NdbDpVRudIAyyqYf01xdHYAu', 'employee', NULL, NULL, 'Moh', 'uploads/black-panther-painting-paul-meijering.jpg', 100.00, NULL),
(3, 'n', '$2y$10$uTUXXUVbsPSHBXDF44qCnuQuwE47Zar3JGB9O9bv84jaEktHsmV46', 'manager', NULL, NULL, 'NASSER ALBATRANI', 'uploads/IMG_9360.jpeg', 0.00, NULL),
(11, 'Mohammed', '$2y$10$rUwP6Voflqq793HJ4pZ6KOTFNU826HcGjbkr9VnG7Ws0kIV4uCj0y', 'manager', NULL, NULL, 'Mohammed Aloufi', 'uploads/IMG_7354.png', 0.00, NULL),
(12, 'Ahmed', '$2y$10$0Mh/du7VWorXNRE6m1I.bO5GPuRaAHircAr3x0lSrM8/cbqUckVp2', 'manager', NULL, NULL, 'Ahmed Alofi', 'uploads/bat.jpg', 0.00, NULL),
(13, 'Mosharof', '$2y$10$pG6fJPcIjPe6nc3zbdItE.3N2pwn0Xw4MBxo/sEY/mAQ7ZpQUHN.2', 'employee', NULL, NULL, 'Mosharof', 'uploads/E914486E-F3BF-4968-851C-543DDB1191EC.jpeg', 0.00, NULL),
(14, 'Robi', '$2y$10$n4yoPgKKygOeKs20dfXLCeOjftJQQ6IhUxlmPZtHh9NWuX2btgw/2', 'employee', NULL, NULL, 'Robi', 'uploads/4F7936AB-0E75-402A-A2BA-8E19CE47E17B.jpeg', 0.00, NULL),
(15, 'Semon', '$2y$10$NDRluzmoVf/aebHpwTlQ1OAtg7y4HaG8cFk4crRGhQLcg7nvJys9a', 'employee', NULL, NULL, 'Semon', 'uploads/FAEEF1EC-101C-4E7A-BAB7-5B111353CA16.jpeg', 0.00, NULL),
(16, 'Robi', '$2y$10$1D2OOlYmfiMTDvTJA5rv/uTeuZe3jlTYsjU3KBUjTvIk4GVjh8VLG', 'employee', NULL, NULL, 'Robi', 'uploads/4F7936AB-0E75-402A-A2BA-8E19CE47E17B.jpeg', 0.00, NULL),
(18, 'aha', '$2y$10$UxmRdQyonXxdECDYRG.tpeCJ3KWGYf8kIeGzRs0Xlgv0t/2hiTmuC', 'employee', NULL, NULL, 'ahmed2', 'uploads/Blue Flat Illustrative Human Artificial Intelligence Technology Logo.png', 0.00, NULL),
(19, 'user', '$2y$10$Y3S8bP/TCT/OhMeW/FhOR.zAVLzT2V4nIYLRrQds5FHalCQMnw9oW', 'employee', '88458854', 'gsdfgsd@gma.com', 'user', NULL, 0.00, NULL),
(20, 'user1', '$2y$10$dazDQ07S6WOxwDADWQZK0OOMOYlB/6qH3CXYfrA8zL8jCwwkLfPgG', 'employee', '99999999', 'ss@sss.com', 'o', NULL, 0.00, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `booked_slots`
--
ALTER TABLE `booked_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking_datetime` (`booking_datetime`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `car_washes`
--
ALTER TABLE `car_washes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_cars`
--
ALTER TABLE `client_cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `car_number` (`car_number`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `external_settings`
--
ALTER TABLE `external_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installments`
--
ALTER TABLE `installments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `internal_settings`
--
ALTER TABLE `internal_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `external_id` (`external_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_car_id` (`client_car_id`),
  ADD KEY `idx_car_number` (`car_number`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `booked_slots`
--
ALTER TABLE `booked_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_washes`
--
ALTER TABLE `car_washes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `client_cars`
--
ALTER TABLE `client_cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `external_settings`
--
ALTER TABLE `external_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `installments`
--
ALTER TABLE `installments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `internal_settings`
--
ALTER TABLE `internal_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booked_slots`
--
ALTER TABLE `booked_slots`
  ADD CONSTRAINT `booked_slots_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_washes`
--
ALTER TABLE `car_washes`
  ADD CONSTRAINT `car_washes_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_cars`
--
ALTER TABLE `client_cars`
  ADD CONSTRAINT `client_cars_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `installments`
--
ALTER TABLE `installments`
  ADD CONSTRAINT `installments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `internal_settings`
--
ALTER TABLE `internal_settings`
  ADD CONSTRAINT `internal_settings_ibfk_1` FOREIGN KEY (`external_id`) REFERENCES `external_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_car_id`) REFERENCES `client_cars` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
