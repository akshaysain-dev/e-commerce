-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 11:59 AM
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
-- Database: `e_commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Color', '2026-04-13 09:12:24', '2026-04-13 09:12:24'),
(2, 'Size', '2026-04-13 09:12:28', '2026-04-13 09:12:28'),
(3, 'Storage', '2026-04-13 09:12:32', '2026-04-13 09:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'Red', '2026-04-13 09:12:38', '2026-04-13 09:12:38'),
(2, 2, 'L', '2026-04-13 09:12:44', '2026-04-13 09:12:44'),
(3, 2, 'M', '2026-04-13 09:12:49', '2026-04-13 09:12:49'),
(4, 2, 'XL', '2026-04-13 09:12:58', '2026-04-13 09:12:58'),
(5, 1, 'XXL', '2026-04-13 09:13:04', '2026-04-13 09:13:04'),
(6, 3, '4GB + 64GB', '2026-04-13 09:13:26', '2026-04-13 09:13:26'),
(7, 3, '8GB + 128GB', '2026-04-13 09:13:40', '2026-04-13 09:13:40'),
(8, 3, '8GB + 256GB', '2026-04-13 09:14:11', '2026-04-13 09:14:11'),
(9, 3, '12GB + 256GB', '2026-04-13 09:14:32', '2026-04-13 09:14:32'),
(10, 1, 'Gray', '2026-04-13 09:22:31', '2026-04-13 09:22:31'),
(11, 1, 'Gold', '2026-04-13 09:22:37', '2026-04-13 09:22:37'),
(12, 1, 'Purple', '2026-04-13 09:22:43', '2026-04-13 09:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_value_product_variant`
--

CREATE TABLE `attribute_value_product_variant` (
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_value_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_value_product_variant`
--

INSERT INTO `attribute_value_product_variant` (`variant_id`, `attribute_value_id`) VALUES
(4, 2),
(5, 3),
(6, 4),
(7, 6),
(8, 7),
(9, 10),
(10, 11);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `type` enum('main','side') NOT NULL DEFAULT 'main',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `type`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Let\'s Shoping Now', 'Welcome !', 'banners/YAvX58D40YqOoVEqs1aajdCDvUzkgS3nfvK1NnJ1.jpg', NULL, 'main', 1, 1, '2026-03-16 01:00:15', '2026-03-16 02:32:36'),
(2, 'For you New offers', 'Shop Now', 'banners/pB1RuakhhiIYwd44OPVUZU7BUizRGc8ljn4skm0P.jpg', NULL, 'side', 1, 1, '2026-03-16 01:33:49', '2026-03-16 02:33:16'),
(3, 'Super Sale', 'Collect Now', 'banners/vcDfAoFx9xOalXoHQtvV7w3qjxWQzCjrocwTcBgS.jpg', 'sales', 'side', 2, 1, '2026-03-16 01:34:02', '2026-03-26 06:26:10'),
(4, 'Unlock All New Deals', 'Shop Now !', 'banners/A9p7LLrP1bFTyrrqu8la1xgChltFFVWTddW0G6Db.jpg', 'category/3/womens', 'main', 2, 1, '2026-03-16 02:38:19', '2026-03-16 02:39:48');

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
('myshop-cache-boost.roster.scan', 'a:2:{s:6:\"roster\";O:21:\"Laravel\\Roster\\Roster\":3:{s:13:\"\0*\0approaches\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"\0*\0packages\";O:32:\"Laravel\\Roster\\PackageCollection\":2:{s:8:\"\0*\0items\";a:10:{i:0;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^12.0\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:LARAVEL\";s:14:\"\0*\0packageName\";s:17:\"laravel/framework\";s:10:\"\0*\0version\";s:7:\"12.53.0\";s:6:\"\0*\0dev\";b:0;}i:1;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:7:\"v0.3.13\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PROMPTS\";s:14:\"\0*\0packageName\";s:15:\"laravel/prompts\";s:10:\"\0*\0version\";s:6:\"0.3.13\";s:6:\"\0*\0dev\";b:0;}i:2;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^11.1\";s:10:\"\0*\0package\";E:35:\"Laravel\\Roster\\Enums\\Packages:SCOUT\";s:14:\"\0*\0packageName\";s:13:\"laravel/scout\";s:10:\"\0*\0version\";s:6:\"11.1.0\";s:6:\"\0*\0dev\";b:0;}i:3;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^5.26\";s:10:\"\0*\0package\";E:39:\"Laravel\\Roster\\Enums\\Packages:SOCIALITE\";s:14:\"\0*\0packageName\";s:17:\"laravel/socialite\";s:10:\"\0*\0version\";s:6:\"5.26.1\";s:6:\"\0*\0dev\";b:0;}i:4;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v0.5.9\";s:10:\"\0*\0package\";E:33:\"Laravel\\Roster\\Enums\\Packages:MCP\";s:14:\"\0*\0packageName\";s:11:\"laravel/mcp\";s:10:\"\0*\0version\";s:5:\"0.5.9\";s:6:\"\0*\0dev\";b:1;}i:5;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.24\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PINT\";s:14:\"\0*\0packageName\";s:12:\"laravel/pint\";s:10:\"\0*\0version\";s:6:\"1.27.1\";s:6:\"\0*\0dev\";b:1;}i:6;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.41\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:SAIL\";s:14:\"\0*\0packageName\";s:12:\"laravel/sail\";s:10:\"\0*\0version\";s:6:\"1.53.0\";s:6:\"\0*\0dev\";b:1;}i:7;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^3.8\";s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PEST\";s:14:\"\0*\0packageName\";s:12:\"pestphp/pest\";s:10:\"\0*\0version\";s:5:\"3.8.5\";s:6:\"\0*\0dev\";b:1;}i:8;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:7:\"11.5.50\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PHPUNIT\";s:14:\"\0*\0packageName\";s:15:\"phpunit/phpunit\";s:10:\"\0*\0version\";s:7:\"11.5.50\";s:6:\"\0*\0dev\";b:1;}i:9;O:22:\"Laravel\\Roster\\Package\":6:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:0:\"\";s:10:\"\0*\0package\";E:41:\"Laravel\\Roster\\Enums\\Packages:TAILWINDCSS\";s:14:\"\0*\0packageName\";s:11:\"tailwindcss\";s:10:\"\0*\0version\";s:5:\"4.2.2\";s:6:\"\0*\0dev\";b:1;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"\0*\0nodePackageManager\";E:43:\"Laravel\\Roster\\Enums\\NodePackageManager:NPM\";}s:9:\"timestamp\";i:1776309487;}', 1776395887);

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
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `position`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Men\'s', 'mens', 5, NULL, 1, '2026-03-06 22:25:42', '2026-04-15 08:37:00'),
(3, 'Women\'s', 'womens', 2, NULL, 1, '2026-03-06 22:56:06', '2026-04-15 08:37:00'),
(4, 'Electronic', 'electronic', 3, NULL, 1, '2026-03-07 01:36:54', '2026-04-15 08:37:00'),
(5, 'Home', 'home', 1, NULL, 1, '2026-03-10 03:42:27', '2026-04-15 08:37:00'),
(6, 'Mobiles', 'moblies', 4, NULL, 1, '2026-03-10 03:49:16', '2026-04-15 08:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `type` enum('fixed','percent') NOT NULL DEFAULT 'fixed',
  `expires_in_days` int(11) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `generated_for` bigint(20) UNSIGNED DEFAULT NULL,
  `used_by` bigint(20) UNSIGNED DEFAULT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `name`, `code`, `discount`, `type`, `expires_in_days`, `expires_at`, `generated_for`, `used_by`, `is_used`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'Welcome Gift — Akshay Sain', 'WELCOME23459F', 10.00, 'percent', 30, '2026-05-15 07:24:34', 22, NULL, 0, 1, '2026-04-15 07:24:34', '2026-04-15 07:24:34'),
(5, 'Welcome Gift — Akshay Sain', 'WELCOME370899', 10.00, 'percent', 30, '2026-05-15 09:42:59', 23, NULL, 0, 1, '2026-04-15 09:42:59', '2026-04-15 09:42:59'),
(6, 'Welcome Gift — Abhimanyu Kumar', 'WELCOME5DA636', 10.00, 'percent', 30, '2026-05-15 09:51:33', 24, NULL, 0, 1, '2026-04-15 09:51:33', '2026-04-15 09:51:33'),
(7, 'Welcome Gift — Abhimanyu Kumar', 'WELCOME7596DC', 10.00, 'percent', 30, '2026-05-15 09:58:31', 25, NULL, 0, 1, '2026-04-15 09:58:31', '2026-04-15 09:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zoho_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `area` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `zoho_id`, `name`, `email`, `google_id`, `avatar`, `phone`, `password`, `area`, `city`, `state`, `country`, `postal_code`, `remember_token`, `created_at`, `updated_at`, `status`) VALUES
(1, NULL, 'Akshay Sain', 'akashaysain247340@gmail.com', NULL, NULL, '9027116545', '$2y$12$Y8yJhV2i6kVWlsRkduQ52..Tqvjg6PBu36rbzQquVbvkjSsaOzafy', 'Nakur To ambehta Road , Chhuchhakpur', 'Saharanpur', 'UP', 'India', '247340', NULL, '2026-03-07 03:32:21', '2026-04-15 06:59:08', 1),
(9, NULL, 'New', 'new@gmail.com', NULL, NULL, '9854754125', '$2y$12$d2eVD4h4nkM0EIVSBn7VxetEiriGpIuRL5zz7LKanRhP4NXZVZlSK', 'Mohali', 'Chandigrah', 'Punjab', 'India', '758456', NULL, '2026-03-22 22:43:20', '2026-04-15 06:51:22', 1),
(10, NULL, 'New Customer', 'newcus@gmail.com', NULL, NULL, '9565524125', '$2y$12$kCxW04asLLMmJ2Pd4WL31eXrtLo0wMaCR9X41CuM8nJIzZFAFzOHq', 'ssfdsd', 'dsfdsd', 'sdfsdfsd', 'sfsdds', '524125', NULL, '2026-03-26 08:28:25', '2026-03-26 08:28:25', 1),
(13, '1276511000000606006', 'Akshay', 'akshaysain760@gmail.com', '104056625080160840119', 'https://lh3.googleusercontent.com/a/ACg8ocL4t1UbnBg3PfKEkbzvaaWJI_FdOK8N_T_3Ei_Dd7Qpqdppfg=s96-c', '9412315083', '$2y$12$hQFCsaq3fQ9XhN647TFwlOHv7bJQIiKt.VYHJquZg.gAmFjidEBE.', 'Nakur', 'Saharanpur', 'Up', 'India', '247340', 'vGpIiDN1w6QY45FdS7lPbHhOxlCWPNajY8r5Fx19UVNzLWteKyk2dnMrHsI2', '2026-04-10 04:15:09', '2026-04-16 07:44:29', 1),
(14, NULL, 'Deepak Kumar', 'deepakkr8156@gmail.com', '115098243204406431375', 'https://lh3.googleusercontent.com/a/ACg8ocJXMGBdVta7_rhYebQpoL2fHvGc7VPYzeuT4yCUz68Ev-jQ=s96-c', NULL, '$2y$12$OMzM80AKfhx4NL40f90z6u4jjiYKiqgO/f1DkljKDPIE8kZZZkvYS', NULL, NULL, NULL, NULL, NULL, 'cPz04LXKPSSKpo9L0OS4r22dNitm62OpTR8vF6T94PObCfO3mXTCIwoZOoT5', '2026-04-10 04:19:38', '2026-04-10 09:38:51', 1),
(16, NULL, 'Abhimanyu Katoch', 'abhimanyyyukatoch8561@gmail.com', NULL, NULL, '6154360708', '$2y$12$SwJTAmZj7yzKCNaSVOs52.38DTKIUnqWwTIYG3XBplBa6BwxAb5YW', '2232', 'MOHALI', 'Punjab', 'India', '143001', NULL, '2026-04-10 09:18:46', '2026-04-10 09:18:46', 1),
(18, NULL, 'Akshay Sain', 'akshaysain940@gmail.com', NULL, NULL, '9412315083', '$2y$12$iJiWZ5tqb4JJkNbJU56Y2.NFJ.KXo1LFq3cLD4EocP/HXozN3DG2e', 'Nakur', 'Saharanpur', 'Up', 'India', '247340', NULL, '2026-04-13 08:51:13', '2026-04-13 08:51:13', 1),
(25, '1276511000000594013', 'Abhimanyu Kumar', 'abhimanyukatoch8561@gmail.com', NULL, NULL, '9412315083', '$2y$12$qfXzwlb.Zg.apVBlFAFcueaQRyX3Pb4K42WeeFRc98xygRI4how0O', 'Nakur', 'Saharanpur', 'Uttar Pradesh', 'India', '247340', NULL, '2026-04-15 09:58:26', '2026-04-15 10:00:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_verifications`
--

CREATE TABLE `customer_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_otps`
--

CREATE TABLE `email_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `otp_for` varchar(255) DEFAULT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `guest_carts`
--

CREATE TABLE `guest_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_batches`
--

CREATE TABLE `import_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `total_rows` int(11) NOT NULL,
  `total_batches` int(11) NOT NULL,
  `processed_batches` int(11) NOT NULL DEFAULT 0,
  `imported_rows` int(11) NOT NULL DEFAULT 0,
  `failed_rows` int(11) NOT NULL DEFAULT 0,
  `status` enum('pending','processing','completed','partial','failed') NOT NULL DEFAULT 'pending',
  `errors` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `import_batches`
--

INSERT INTO `import_batches` (`id`, `user_id`, `filename`, `total_rows`, `total_batches`, `processed_batches`, `imported_rows`, `failed_rows`, `status`, `errors`, `created_at`, `updated_at`) VALUES
(1, 2, 'products_2026-03-20_09-56-33.csv', 17, 1, 1, 17, 0, 'completed', NULL, '2026-03-20 23:48:00', '2026-03-20 23:48:01');

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
-- Table structure for table `margins`
--

CREATE TABLE `margins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `margins`
--

INSERT INTO `margins` (`id`, `type_id`, `name`, `percentage`, `created_at`, `updated_at`) VALUES
(1, 1, 'Retail', 10.00, '2026-04-13 09:04:30', '2026-04-13 09:04:30');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_07_013601_create_customers_table', 2),
(5, '2026_03_07_013720_create_products_table', 2),
(6, '2026_03_07_030928_create_categories_table', 3),
(7, '2026_03_07_031324_add_category_id_to_products_table', 3),
(8, '2026_03_07_065612_add_images_to_products_table', 4),
(9, '2026_03_11_021021_add_status_column_to_customers', 5),
(10, '2026_03_11_031308_create_product_variants_table', 6),
(12, '2026_03_11_042746_create_attirbute_tables', 7),
(13, '2026_03_11_055913_add_attribute_id_to_product_variants_table', 8),
(14, '2026_03_11_090728_create_carts_table', 8),
(17, '2026_03_12_030828_create_orders_table', 9),
(18, '2026_03_12_030840_create_order_items_table', 9),
(19, '2026_03_12_051923_add_address_to_orders_table', 10),
(20, '2026_03_13_040332_update_orders_table_add_payment_fields', 11),
(21, '2026_03_16_055621_create_banners_table', 12),
(22, '2026_03_16_093011_create_recent_views_table', 13),
(23, '2026_03_17_035729_create_wishlists_table', 14),
(24, '2026_03_17_064545_create_notifications_table', 15),
(26, '2026_03_18_092000_create_ratings_table', 16),
(27, '2026_03_19_015554_remove_price_and_stock_from_products_table', 17),
(28, '2026_03_19_031533_create_product_types_table', 17),
(29, '2026_03_19_031740_add_product_type_id_to_products_table', 18),
(30, '2026_03_19_061348_add_missing_columns_to_ratings_table', 19),
(31, '2026_03_21_034815_create_import_batches_table', 19),
(32, '2026_03_21_061335_create_coupons_table', 20),
(33, '2026_03_21_062448_add_coupon_fields_to_orders_table', 21),
(34, '2026_03_23_033642_add_customer_fields_to_coupons_table', 22),
(35, '2026_03_23_045642_create_sales_table', 23),
(36, '2026_03_24_095659_create_tags_table', 24),
(37, '2026_03_25_123006_add_refunded_amount_to_orders_table', 25),
(42, '2026_03_25_141543_create_return_orders_table', 26),
(43, '2026_03_26_144535_add_delivery_date_to_orders_table', 27),
(44, '2026_03_27_073244_add_margin_price_to_product_variants_table', 28),
(45, '2026_03_27_073727_add_margin_price_to_product_variants_table', 29),
(47, '2026_03_27_074515_create_margins_table', 30),
(48, '2026_03_30_082914_create_guest_carts_table', 31),
(49, '2026_04_06_132102_create_tax_or_shipping_charge', 32),
(50, '2026_04_08_120727_add_position_to_categories', 33),
(51, '2026_04_10_085034_add_google_fields_to_customers_table', 34),
(52, '2026_04_10_102036_create_password_reset_tokens_table', 35),
(53, '2026_04_10_141121_create_customer_verifications_table', 35),
(55, '2026_04_13_113353_create_email_otps_table', 36),
(56, '2026_04_15_095349_add_zoho_id_to_customers_table', 37),
(57, '2026_04_16_084507_add_google_2fa_to_users_table', 38),
(58, '2026_04_16_110436_add_images_to_ratings_table', 39),
(59, '2026_04_16_122127_create_review_replies_table', 40),
(60, '2026_04_16_144920_add_main_price_to_order_items_table', 41),
(61, '2026_04_16_150509_add_status_to_ratings_table', 42);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `customer_id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 13, 'New Product Added in Cart', 'New product Added in Cart for processing in checkout.', 0, '2026-04-13 09:27:25', '2026-04-13 09:27:25'),
(2, 13, 'New Product Added in Cart', 'New product Added in Cart for processing in checkout.', 0, '2026-04-13 09:28:37', '2026-04-13 09:28:37'),
(3, 13, 'New Product Added in Cart', 'New product Added in Cart for processing in checkout.', 0, '2026-04-13 09:29:05', '2026-04-13 09:29:05'),
(4, 13, 'New Product Added in Cart', 'New product Added in Cart for processing in checkout.', 0, '2026-04-13 09:29:19', '2026-04-13 09:29:19'),
(10, 1, 'Login Your account.', 'Your account has been logged in on a new device.', 0, '2026-04-15 06:49:27', '2026-04-15 06:49:27'),
(11, 1, 'Login Your account.', 'Your account has been logged in on a new device.', 0, '2026-04-15 06:51:13', '2026-04-15 06:51:13'),
(12, 1, 'Login Your account.', 'Your account has been logged in on a new device.', 0, '2026-04-15 06:53:04', '2026-04-15 06:53:04'),
(13, 1, 'Login Your account.', 'Your account has been logged in on a new device.', 0, '2026-04-15 06:59:32', '2026-04-15 06:59:32'),
(17, 25, 'Welcome on MyShop.', 'Your coupon is WELCOME7596DC', 0, '2026-04-15 09:58:31', '2026-04-15 09:58:31'),
(18, 13, 'Login Your account.', 'Your account has been logged in via Google.', 0, '2026-04-16 05:02:13', '2026-04-16 05:02:13'),
(19, 13, 'New Order Placed', 'Your order #ORD-69E06DCE1FB07 has been successfully placed.', 0, '2026-04-16 05:04:41', '2026-04-16 05:04:41'),
(20, 1, 'Login Your account.', 'Your account has been logged in on a new device.', 0, '2026-04-16 07:44:08', '2026-04-16 07:44:08'),
(21, 1, 'New Product Added in Cart', 'New product Added in Cart for processing in checkout.', 0, '2026-04-16 07:55:43', '2026-04-16 07:55:43'),
(22, 1, 'New Product Added in Cart', 'New product Added in Cart for processing in checkout.', 0, '2026-04-16 07:55:48', '2026-04-16 07:55:48'),
(23, 1, 'New Order Placed', 'Your order #ORD-69E09611AA414 has been successfully placed.', 0, '2026-04-16 07:56:07', '2026-04-16 07:56:07'),
(24, 1, 'New Product Added in Cart', 'New product Added in Cart for processing in checkout.', 0, '2026-04-16 08:24:04', '2026-04-16 08:24:04'),
(25, 1, 'New Order Placed', 'Your order #ORD-69E09CA93C36D has been successfully placed.', 0, '2026-04-16 08:24:13', '2026-04-16 08:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unique_order_id` varchar(255) DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `refunded_amount` decimal(10,2) DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','paid','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `delivery_date` date DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'COD',
  `transaction_id` varchar(255) DEFAULT NULL,
  `card_last_four` varchar(4) DEFAULT NULL,
  `card_holder_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `unique_order_id`, `customer_id`, `total_amount`, `refunded_amount`, `coupon_code`, `discount_amount`, `status`, `delivery_date`, `payment_method`, `transaction_id`, `card_last_four`, `card_holder_name`, `created_at`, `updated_at`, `address`) VALUES
(1, 'ORD-69E06DCE1FB07', 13, 2406.69, NULL, NULL, 0.00, 'delivered', '2026-04-16', 'STRIPE', 'pi_3TMi64CSipI1MBTM0wbUYYI8', '4242', NULL, '2026-04-16 05:04:37', '2026-04-16 06:05:36', 'Nakur, Saharanpur, Up, India - 247340'),
(2, 'ORD-69E09611AA414', 1, 13871.81, NULL, NULL, 0.00, 'delivered', '2026-04-16', 'COD', NULL, NULL, NULL, '2026-04-16 07:56:01', '2026-04-16 07:57:06', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(3, 'ORD-69E09CA93C36D', 1, 514.26, NULL, NULL, 0.00, 'delivered', '2026-04-16', 'COD', NULL, NULL, NULL, '2026-04-16 08:24:09', '2026-04-16 08:24:19', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `main_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_variant_id`, `quantity`, `price`, `main_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 5, 471.90, 429.00, '2026-04-16 05:04:37', '2026-04-16 05:04:37'),
(2, 2, 3, 9, 1, 3958.90, 3599.00, '2026-04-16 07:56:01', '2026-04-16 07:56:01'),
(3, 2, 2, 7, 1, 9843.90, 8949.00, '2026-04-16 07:56:01', '2026-04-16 07:56:01'),
(4, 3, 1, 4, 1, 471.90, 429.00, '2026-04-16 08:24:09', '2026-04-16 08:24:09');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Store multiple image paths as JSON' CHECK (json_valid(`images`)),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_type_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `user_id`, `name`, `description`, `image`, `images`, `status`, `created_at`, `updated_at`, `product_type_id`) VALUES
(1, 1, 2, 'Lymio Polo T Shirt for Men || T Shirt for Man || Collar T Shirt Style Men (Packs Also Available) (Polo-40-4', 'Lymio Polo T Shirt for Men || T Shirt for Man || Collar T Shirt Style Men (Packs Also Available) (Polo-40-4', 'products/ogLyJ4mdqCAQW27TLfdzVPyFyCOmLpb5lOh7hku2.jpg', '[\"products\\/pYk4F8FlkHvzT5rX5njeQAoNUTMDC9U6tlFIIVL6.jpg\",\"products\\/f31xi86zdbgNy9vQUazhKK1K4x5mQb87F2AcuVnr.jpg\",\"products\\/nOYsP1Gcl5k4dIXemJKLjG9LFpdNp9SfD8htRzge.jpg\",\"products\\/4ZtntgE9A0EgeM8ROAqqoF9XmffRLt4N4SPXe00B.jpg\"]', 1, '2026-04-13 09:07:36', '2026-04-13 09:07:36', 1),
(2, 6, 2, 'POCO C71, Desert Gold (4GB, 64GB)', 'POCO C71, Desert Gold (4GB, 64GB)', 'products/9KekxFYAimcA3cIkZqyhEHsouVCiJjp65N7lrNIC.jpg', '[\"products\\/UlM4g5MXvHuoQwW4D1wTHneYVnK4pIpfcRxYMlrA.jpg\",\"products\\/voursiDqOHhfbw8DVxOloze9uSnYU20u0xx5rrvD.jpg\",\"products\\/kD6DD62bC1obp8Qn6dsmNJEmpmGJcO11xqSrOjXB.jpg\",\"products\\/efHZQCUAACtlGWXtZ8JNAbDCaZtS2gdQcYu4itYZ.jpg\",\"products\\/cRT2SQm1bPqCzhMGqjyh3ewRYIUSC77MieYKnH2i.jpg\"]', 1, '2026-04-13 09:20:15', '2026-04-13 09:20:15', 1),
(3, 4, 2, 'realme Buds Air 8,11mm+6mm Dual Dynamic Bass Drivers,58Hrs Playtime', 'realme Buds Air 8,11mm+6mm Dual Dynamic Bass Drivers,58Hrs Playtime, 55dB ANC,6 Mic ENC, 45ms Low Latency, 360° Spatial Audio, Hi-Res LHDC, IP55 Dust & Water Resistant, BT v5.4 (Master Grey)', 'products/vZMFtn35jZR3HAsmEhdNDNLEPntsZg8h7tikrOhG.jpg', '[\"products\\/G0cEcKyPTBf8SNUy1SqwCxvV0xFcsvfQn8Fd2DMB.jpg\",\"products\\/LxWibBO2M4aVO3TDQtOTWtI9r2zg5wl6QI9jTIsf.jpg\",\"products\\/2Q8pFJIRkFD4C81Gce7Fr9pkEgmfeRicbuZwGzmC.jpg\",\"products\\/xaTDHSjdBg63wbIXJxhWytClH4AQfi23OGUCZHs6.jpg\",\"products\\/5uCZeP2iLcayifP0qsgW36pmfmPeUA9c9jHaF9wL.jpg\",\"products\\/H89tRnIczDc2m4UPPCIOGH5BjRXbrlH0oiImNqs8.jpg\",\"products\\/sRvaS9uybeo5B7fuHYHXBwGYLA4htYsIgV0BjFma.jpg\",\"products\\/5q6rCX4T0zsipMF0rw1ZWYkzUtDERbqYDo3CXx8H.jpg\"]', 1, '2026-04-13 09:26:25', '2026-04-16 07:56:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_tag`
--

CREATE TABLE `product_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_tag`
--

INSERT INTO `product_tag` (`id`, `product_id`, `tag_id`) VALUES
(1, 1, 1),
(22, 3, 1),
(20, 10, 1),
(21, 11, 1),
(23, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Simple', 'simple', '2026-04-13 09:02:35', '2026-04-13 09:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `margin_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `name`, `price`, `margin_price`, `stock`, `created_at`, `updated_at`, `attribute_id`) VALUES
(4, 1, 'LYMIO-POLO-T-SHIRT-FOR-MEN-T-SHIRT-FOR-MAN-COLLAR-T-SHIRT-STYLE-MEN-PACKS-ALSO-AVAILABLE-POLO-40-4-L-0KRZ', 'L', 429.00, 471.90, 4, '2026-04-13 09:15:31', '2026-04-16 08:24:09', 2),
(5, 1, 'LYMIO-POLO-T-SHIRT-FOR-MEN-T-SHIRT-FOR-MAN-COLLAR-T-SHIRT-STYLE-MEN-PACKS-ALSO-AVAILABLE-POLO-40-4-M-BVLQ', 'M', 449.00, 493.90, 10, '2026-04-13 09:15:31', '2026-04-13 09:15:31', 2),
(6, 1, 'LYMIO-POLO-T-SHIRT-FOR-MEN-T-SHIRT-FOR-MAN-COLLAR-T-SHIRT-STYLE-MEN-PACKS-ALSO-AVAILABLE-POLO-40-4-XL-HRJW', 'XL', 479.00, 526.90, 10, '2026-04-13 09:15:31', '2026-04-13 09:15:31', 2),
(7, 2, 'POCO-C71-DESERT-GOLD-4GB-64GB-4GB-64GB-5T0Q', '4GB + 64GB', 8949.00, 9843.90, 9, '2026-04-13 09:20:17', '2026-04-16 07:56:01', 3),
(8, 2, 'POCO-C71-DESERT-GOLD-4GB-64GB-8GB-128GB-9YRC', '8GB + 128GB', 12999.00, 14298.90, 10, '2026-04-13 09:20:17', '2026-04-13 09:20:17', 3),
(9, 3, 'REALME-BUDS-AIR-811MM6MM-DUAL-DYNAMIC-BASS-DRIVERS58HRS-PLAYTIME-55DB-ANC6-MIC-ENC-45MS-LOW-LATENCY-360-SPATIAL-AUDIO-HI-RES-LHDC-IP55-DUST-WATER-RESISTANT-BT-V54-MASTER-GREY-GRAY-ZFNF', 'Gray', 3599.00, 3958.90, 9, '2026-04-13 09:26:26', '2026-04-16 07:56:48', 1),
(10, 3, 'REALME-BUDS-AIR-811MM6MM-DUAL-DYNAMIC-BASS-DRIVERS58HRS-PLAYTIME-55DB-ANC6-MIC-ENC-45MS-LOW-LATENCY-360-SPATIAL-AUDIO-HI-RES-LHDC-IP55-DUST-WATER-RESISTANT-BT-V54-MASTER-GREY-GOLD-QAYO', 'Gold', 3699.00, 4068.90, 10, '2026-04-13 09:26:26', '2026-04-16 07:56:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `review` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `title` varchar(100) DEFAULT NULL,
  `helpful_yes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `helpful_no` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `customer_id`, `product_id`, `rating`, `review`, `status`, `images`, `title`, `helpful_yes`, `helpful_no`, `is_verified_purchase`, `created_at`, `updated_at`) VALUES
(3, 13, 1, 5, 'This product is good quality.', 'approved', '[\"reviews\\/4GkGpEwQeRr3XUnRvRvXJBgWiJj5Uc5hTiB6c3Zq.png\",\"reviews\\/80UFZCoBJO5IncbF8i8nJ7UYAAIqbqjV3OiH8gRn.png\",\"reviews\\/ieK5rDKmIoWbJfDAbqw5gs2oMYfpc8ktvK4MWmUj.png\",\"reviews\\/ALguWMbXNQCA48i3MgYWawZul4pyARYhXfsMQiyK.png\",\"reviews\\/tOvwHnX6nhWh19M83s9slZtKlFdK1dgbOi6dgvNY.png\",\"reviews\\/2lpflMjoJwU5mHEKTgG5AFBMHHFQCndLHSIj8sLY.png\",\"reviews\\/xQGq7DcK6aJGZphCkpVrSfqHSGL6mymG2519H1KW.png\",\"reviews\\/WhaFWQm6RoJbTnsm5g9h7T0q20luZrooBEYwik4E.png\"]', 'Quality', 0, 0, 1, '2026-04-16 06:38:21', '2026-04-16 09:49:50'),
(4, 1, 1, 2, 'This is very low quality product', 'approved', '[\"reviews\\/bYKAYl09eVdtZd8FHEjsCHKYrhUJZBh2RrZfwSrA.png\",\"reviews\\/BqDqKqC5DWB6rngI7loF7gwxWkVBhKp9miLrKgGL.png\",\"reviews\\/skABCZCEG93HXl02iLlr1pGMH4aVGWsgSLdRz19O.png\",\"reviews\\/5BRFk67DIxXVfEaRMJuVnjL1eXSV2sWOL7fDTlnz.png\",\"reviews\\/wR4NDw46f5uWZtJiu79ypfBCYCaLFkbdjgVWWrCS.png\",\"reviews\\/xHr8KadccKYXmVynckY8fX03EKCLiTSDyzNkgk5z.png\",\"reviews\\/5ZHvd3e2xMkZzTegbOCL3P5Mmn2XM2qjjXP5FdwM.png\",\"reviews\\/6srD21FESzjFdMGk2gtRy6nEFlmL9S6gLxPskSXP.png\",\"reviews\\/Oya9D4GG2F7g3K7RtEpoXuFjThi3XyHV68Adlv0z.png\",\"reviews\\/0coSzAKGGq7EJoh00PuPcwJCYJmKgPsXfwL2RAoD.png\",\"reviews\\/uINGSxEHpWNXNrCafb6lVg8OVNLIMOxSTTUgm6hy.png\"]', 'Quality issue', 0, 0, 1, '2026-04-16 08:25:11', '2026-04-16 09:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `recent_views`
--

CREATE TABLE `recent_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recent_views`
--

INSERT INTO `recent_views` (`id`, `customer_id`, `product_id`, `created_at`, `updated_at`) VALUES
(42, 1, 3, '2026-04-16 07:55:42', '2026-04-16 07:55:42'),
(43, 1, 2, '2026-04-16 07:55:47', '2026-04-16 07:55:47'),
(52, 13, 2, '2026-04-16 08:08:29', '2026-04-16 08:08:29'),
(77, 13, 3, '2026-04-16 08:46:11', '2026-04-16 08:46:11'),
(103, 13, 1, '2026-04-16 09:49:13', '2026-04-16 09:49:13'),
(104, 1, 1, '2026-04-16 09:49:15', '2026-04-16 09:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `return_orders`
--

CREATE TABLE `return_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `bank_name` varchar(255) DEFAULT NULL,
  `account_holder_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `refund_amount` decimal(8,2) DEFAULT NULL,
  `stripe_refund_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `return_orders`
--

INSERT INTO `return_orders` (`id`, `order_id`, `customer_id`, `reason`, `status`, `bank_name`, `account_holder_name`, `account_number`, `ifsc_code`, `refund_amount`, `stripe_refund_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Quality is not good', 'pending', 'SBI', 'Akshay', '000123456789', 'SBIN0014141', 871.06, NULL, '2026-03-25 10:15:03', '2026-03-25 10:15:03');

-- --------------------------------------------------------

--
-- Table structure for table `review_replies`
--

CREATE TABLE `review_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rating_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `body` text NOT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `is_seller` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `review_replies`
--

INSERT INTO `review_replies` (`id`, `rating_id`, `customer_id`, `admin_id`, `body`, `author_name`, `is_seller`, `created_at`, `updated_at`) VALUES
(1, 3, 13, NULL, 'hfgvbghfghgfh', 'Akshay', 0, '2026-04-16 07:02:43', '2026-04-16 07:02:43'),
(2, 3, 1, NULL, 'You are buy this product please tell me this quality about.', 'Akshay Sain', 0, '2026-04-16 07:46:03', '2026-04-16 07:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `discount` decimal(5,2) NOT NULL,
  `type` enum('percent','fixed') NOT NULL,
  `scope` enum('category','product_type','tag') NOT NULL,
  `scope_id` bigint(20) UNSIGNED NOT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
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
('E7isdPr3y0XAN8zZjAqAUPSyZ9Dbn694cRhp9NbT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWlpGcDEzMmpCMnZoTHhDc29BcmRYdmlqZnBqazRBMnowNGh4cmhYZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0LzEiO3M6NToicm91dGUiO3M6MTI6InZpZXdfcHJvZHVjdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTE6ImN1c3RvbWVyX2lkIjtpOjE7czoxMzoiY3VzdG9tZXJfbmFtZSI7czoxMToiQWtzaGF5IFNhaW4iO30=', 1776332955),
('Nn7wcfHuTeGPv7UHB1LRDOl2XdvKRry9flaGKduV', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToxMTp7czo2OiJfdG9rZW4iO3M6NDA6ImpVbDN1dkNIYmt2dTBpeVVkZldQTVFLQ0h3dGJhaVZsQzM1cjZyWHUiO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjUxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcmV2aWV3cz9zdGF0dXM9YXBwcm92ZWQiO3M6NToicm91dGUiO3M6MTM6ImFkbWluLnJldmlld3MiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMztzOjU6InN0YXRlIjtzOjQwOiJ6MDl6cFZpcThRdXo4QnZrU0w5U01kWjFBZWQ1bnVnTUp0Rng0YXY5IjtzOjExOiJjdXN0b21lcl9pZCI7aToxMztzOjEzOiJjdXN0b21lcl9uYW1lIjtzOjY6IkFrc2hheSI7czoxMjoicHJvZHVjdHNfdXJsIjtzOjM1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcHJvZHVjdCI7czo4OiJhZG1pbl9pZCI7aToyO3M6MTA6ImFkbWluX25hbWUiO3M6NToiQWRtaW4iO3M6MTI6IjJmYV92ZXJpZmllZCI7YjoxO30=', 1776333002),
('Q2dfQyfH2b5fynFnZ9LJCmzY5FgGm8wEmZTOhUvB', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiNVF4YmZoWEZnbUIwWDV5Unp6cXU3R1pVNjJKVlhJZzhQWmJxYXVlYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czo1OiJhZG1pbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEzO3M6NToic3RhdGUiO3M6NDA6InowOXpwVmlxOFF1ejhCdmtTTDlTTWRaMUFlZDVudWdNSnRGeDRhdjkiO3M6MTE6ImN1c3RvbWVyX2lkIjtpOjEzO3M6MTM6ImN1c3RvbWVyX25hbWUiO3M6NjoiQWtzaGF5IjtzOjEyOiIyZmFfdmVyaWZpZWQiO2I6MTtzOjEyOiJwcm9kdWN0c191cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9kdWN0Ijt9', 1776328071);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#6366f1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `color`, `created_at`, `updated_at`) VALUES
(1, 'Trending', 'trending', '#070727', '2026-03-24 04:35:27', '2026-03-24 05:15:43'),
(2, 'Sale', 'sale', '#e70d0d', '2026-03-24 05:06:19', '2026-03-24 05:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `tax_or_shipping_charge`
--

CREATE TABLE `tax_or_shipping_charge` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tax` varchar(255) NOT NULL,
  `shipping_charge` varchar(255) NOT NULL,
  `max_charge_for_shipping` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_or_shipping_charge`
--

INSERT INTO `tax_or_shipping_charge` (`id`, `tax`, `shipping_charge`, `max_charge_for_shipping`, `created_at`, `updated_at`) VALUES
(1, '0.5', '40', '500', '2026-04-13 02:03:07', '2026-04-16 05:06:41');

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
  `google2fa_secret` text DEFAULT NULL,
  `google2fa_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google2fa_secret`, `google2fa_enabled`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2026-03-06 20:16:47', '$2y$12$ta52oRzDEOey07MSm9MDTOuYr4f6aYaLmVTyhzlj/3hqOMYSHaW8.', NULL, 0, '9LaJjiuaLq', '2026-03-06 20:16:48', '2026-03-06 20:16:48'),
(2, 'Admin', 'admin@gmail.com', NULL, '$2y$12$2SPaPF8S.E/i/uUCkxrnLuU6t2erUsrr/hcNVsQ6rGffgcCuAL4yO', 'eyJpdiI6IjRYR05EdmlpZENDakNFQjVxNWFnZGc9PSIsInZhbHVlIjoid0FTZ2lPNnFiaFZXNEMzamd0WTlMWktGY3gvYU5lZ1dieWFJYkkrNmxSR0tsclV1d3A3bTlsR1ROUm41ZGYyaiIsIm1hYyI6IjIxNjk3ODcxNWUxZjRiOGFjOTBhZDhkYWJhOTFmMDA0YzVmN2Y2Njk3NDc3OTQxZDVkNzMyZDY4ZjFlMjk4ZWMiLCJ0YWciOiIifQ==', 1, NULL, '2026-03-06 20:18:31', '2026-04-16 04:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_values_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `attribute_value_product_variant`
--
ALTER TABLE `attribute_value_product_variant`
  ADD KEY `attribute_value_product_variant_variant_id_foreign` (`variant_id`),
  ADD KEY `attribute_value_product_variant_attribute_value_id_foreign` (`attribute_value_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_customer_id_foreign` (`customer_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`),
  ADD KEY `carts_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `customer_verifications`
--
ALTER TABLE `customer_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_otps`
--
ALTER TABLE `email_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_otps_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `guest_carts`
--
ALTER TABLE `guest_carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_carts_ip_address_index` (`ip_address`);

--
-- Indexes for table `import_batches`
--
ALTER TABLE `import_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `margins`
--
ALTER TABLE `margins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `margins_type_id_foreign` (`type_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_unique_order_id_unique` (`unique_order_id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_user_id_foreign` (`user_id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_tag_product_id_tag_id_unique` (`product_id`,`tag_id`),
  ADD KEY `product_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_types_slug_unique` (`slug`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ratings_customer_id_foreign` (`customer_id`),
  ADD KEY `ratings_product_id_foreign` (`product_id`);

--
-- Indexes for table `recent_views`
--
ALTER TABLE `recent_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recent_views_customer_id_foreign` (`customer_id`),
  ADD KEY `recent_views_product_id_foreign` (`product_id`);

--
-- Indexes for table `return_orders`
--
ALTER TABLE `return_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_orders_order_id_foreign` (`order_id`),
  ADD KEY `return_orders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `review_replies`
--
ALTER TABLE `review_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_replies_rating_id_foreign` (`rating_id`),
  ADD KEY `review_replies_customer_id_foreign` (`customer_id`),
  ADD KEY `review_replies_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_slug_unique` (`slug`);

--
-- Indexes for table `tax_or_shipping_charge`
--
ALTER TABLE `tax_or_shipping_charge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_customer_id_foreign` (`customer_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attribute_values`
--
ALTER TABLE `attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `customer_verifications`
--
ALTER TABLE `customer_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `email_otps`
--
ALTER TABLE `email_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guest_carts`
--
ALTER TABLE `guest_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `import_batches`
--
ALTER TABLE `import_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `margins`
--
ALTER TABLE `margins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_tag`
--
ALTER TABLE `product_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `recent_views`
--
ALTER TABLE `recent_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `return_orders`
--
ALTER TABLE `return_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review_replies`
--
ALTER TABLE `review_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tax_or_shipping_charge`
--
ALTER TABLE `tax_or_shipping_charge`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attribute_value_product_variant`
--
ALTER TABLE `attribute_value_product_variant`
  ADD CONSTRAINT `attribute_value_product_variant_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attribute_value_product_variant_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `email_otps`
--
ALTER TABLE `email_otps`
  ADD CONSTRAINT `email_otps_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `margins`
--
ALTER TABLE `margins`
  ADD CONSTRAINT `margins_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `product_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_replies`
--
ALTER TABLE `review_replies`
  ADD CONSTRAINT `review_replies_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_replies_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_replies_rating_id_foreign` FOREIGN KEY (`rating_id`) REFERENCES `ratings` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
