-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2026 at 12:18 PM
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
(1, 'Color', '2026-03-10 23:23:10', '2026-03-10 23:23:10'),
(2, 'Size', '2026-03-10 23:26:51', '2026-03-10 23:26:51'),
(3, 'Memory', '2026-03-11 02:28:07', '2026-03-11 02:28:07');

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
(1, 1, 'Red', '2026-03-10 23:26:26', '2026-03-10 23:26:26'),
(2, 1, 'Blue', '2026-03-10 23:26:35', '2026-03-10 23:26:35'),
(3, 1, 'Green', '2026-03-10 23:26:42', '2026-03-10 23:26:42'),
(4, 2, 'L', '2026-03-10 23:26:57', '2026-03-10 23:26:57'),
(6, 2, 'M', '2026-03-10 23:27:11', '2026-03-10 23:27:11'),
(7, 2, 'XL', '2026-03-10 23:27:21', '2026-03-10 23:27:21'),
(8, 2, 'XXL', '2026-03-10 23:27:30', '2026-03-10 23:27:30'),
(9, 1, 'Black', '2026-03-10 23:36:03', '2026-03-10 23:36:03'),
(10, 3, '128 GB + 6GB', '2026-03-11 02:28:33', '2026-03-11 02:28:33'),
(11, 3, '128 GB + 8 GB', '2026-03-11 02:28:54', '2026-03-11 02:28:54'),
(12, 3, '256 GB + 8GB', '2026-03-11 02:29:26', '2026-03-11 02:29:26'),
(13, 3, '256 GB + 12GB', '2026-03-11 02:29:39', '2026-03-11 02:29:39'),
(15, 3, '512 GB + 12GB', '2026-03-11 02:30:09', '2026-03-11 02:30:09'),
(16, 1, 'Golden', '2026-03-20 03:46:44', '2026-03-20 03:46:44'),
(17, 2, 'Black, M', '2026-03-20 22:36:10', '2026-03-20 22:36:10'),
(18, 2, 'Blue, XL', '2026-03-20 22:36:10', '2026-03-20 22:36:10'),
(19, 2, 'L, XXL', '2026-03-20 22:36:10', '2026-03-20 22:36:10'),
(20, 3, '1TB + 12GB', '2026-03-23 09:35:53', '2026-03-23 09:35:53');

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
(7, 9),
(9, 4),
(13, 11),
(8, 2),
(6, 4),
(10, 2),
(2, 16),
(2, 16),
(2, 16),
(2, 16),
(2, 16),
(2, 16),
(1, 7),
(4, 11),
(5, 12),
(12, 10),
(14, 11),
(15, 12),
(16, 12),
(17, 4),
(18, 6),
(19, 7),
(20, 13),
(21, 15),
(22, 20),
(3, 10),
(23, 6);

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

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `customer_id`, `product_id`, `product_variant_id`, `quantity`, `created_at`, `updated_at`) VALUES
(14, 9, 1, 1, 1, '2026-03-23 10:17:46', '2026-03-23 10:17:46');

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
(1, 'Men\'s', 'mens', 2, NULL, 1, '2026-03-06 22:25:42', '2026-04-08 09:24:40'),
(3, 'Women\'s', 'womens', 4, NULL, 1, '2026-03-06 22:56:06', '2026-04-08 09:24:40'),
(4, 'Electronic', 'electronic', 5, NULL, 1, '2026-03-07 01:36:54', '2026-04-08 09:24:40'),
(5, 'Home', 'home', 3, NULL, 1, '2026-03-10 03:42:27', '2026-04-08 09:24:40'),
(6, 'Mobiles', 'moblies', 1, NULL, 1, '2026-03-10 03:49:16', '2026-04-08 09:24:40');

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
(1, 'LuckyCUS', '3XH0LXTH', 200.00, 'fixed', 2, '2026-03-24 20:38:20', NULL, 1, 1, 1, '2026-03-22 20:34:27', '2026-03-22 20:45:15'),
(5, 'Welcome Gift — New', 'WELCOME0E524B', 10.00, 'percent', 30, '2026-04-21 22:43:20', 9, NULL, 0, 1, '2026-03-22 22:43:20', '2026-03-22 22:43:20'),
(6, 'New', 'RAY01UGW', 200.00, 'fixed', 5, '2026-03-30 03:30:23', NULL, 1, 1, 1, '2026-03-25 03:30:23', '2026-03-25 03:38:28'),
(7, 'Test Coupon', 'K6VIEHQH', 200.00, 'fixed', 5, '2026-03-30 03:43:51', NULL, 1, 1, 1, '2026-03-25 03:43:51', '2026-03-25 03:44:16'),
(8, 'fsdfsdfdds', 'OHHG3QBZ', 100.00, 'fixed', 5, '2026-03-30 03:47:50', NULL, 1, 1, 1, '2026-03-25 03:47:50', '2026-03-25 03:47:58'),
(9, 'dsfgdsgdf', 'NQLTER2F', 200.00, 'fixed', 5, '2026-03-30 03:53:06', NULL, 1, 1, 1, '2026-03-25 03:53:06', '2026-03-25 03:54:29'),
(10, 'gfdgdfgdfg', 'G6O6O8KP', 10.00, 'percent', 2, '2026-03-27 03:56:58', NULL, 1, 1, 1, '2026-03-25 03:56:58', '2026-03-25 04:04:19'),
(11, 'Welcome Gift — New Customer', 'WELCOME9ABE75', 10.00, 'percent', 30, '2026-04-25 08:28:25', 10, 10, 1, 1, '2026-03-26 08:28:25', '2026-03-26 08:38:53'),
(12, 'fgdfgfgfghf', 'X0U9601J', 200.00, 'fixed', 4, '2026-03-30 08:34:57', NULL, 10, 1, 1, '2026-03-26 08:34:57', '2026-03-26 08:37:52'),
(13, 'sfsdfdsfsd', 'I0A8OC3H', 50.00, 'fixed', 2, '2026-03-28 08:49:55', NULL, 10, 1, 1, '2026-03-26 08:49:55', '2026-03-26 08:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
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

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `password`, `area`, `city`, `state`, `country`, `postal_code`, `remember_token`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Akshay Sain', 'akashaysain247340@gmail.com', '9027116545', '$2y$12$Y8yJhV2i6kVWlsRkduQ52..Tqvjg6PBu36rbzQquVbvkjSsaOzafy', 'Nakur To ambehta Road , Chhuchhakpur', 'Saharanpur', 'UP', 'India', '247340', NULL, '2026-03-07 03:32:21', '2026-03-17 19:55:38', 1),
(4, 'Test User', 'test@gmail.com', '9562654564', '$2y$12$LCZjCMfAK/PkXHOtb8vdMez5YiLrjraotVgpEJoj9rU0IvASE06cG', 'Sre', 'Sre', 'UP', 'India', '568545', NULL, '2026-03-17 03:39:38', '2026-04-03 02:06:21', 1),
(9, 'New', 'new@gmail.com', '9854754125', '$2y$12$d2eVD4h4nkM0EIVSBn7VxetEiriGpIuRL5zz7LKanRhP4NXZVZlSK', 'Mohali', 'Chandigrah', 'Punjab', 'India', '758456', NULL, '2026-03-22 22:43:20', '2026-03-22 22:43:20', 1),
(10, 'New Customer', 'newcus@gmail.com', '9565524125', '$2y$12$kCxW04asLLMmJ2Pd4WL31eXrtLo0wMaCR9X41CuM8nJIzZFAFzOHq', 'ssfdsd', 'dsfdsd', 'sdfsdfsd', 'sfsdds', '524125', NULL, '2026-03-26 08:28:25', '2026-03-26 08:28:25', 1);

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
(1, 1, 'Retail', 15.00, '2026-03-27 02:58:37', '2026-03-27 02:58:37'),
(3, 2, 'NEW', 5.00, '2026-03-27 05:47:18', '2026-03-27 05:47:18');

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
(50, '2026_04_08_120727_add_position_to_categories', 33);

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
(8, 4, 'Welcome on MyShop.', 'Your account has been activated now for using this store.', 0, '2026-03-17 03:39:38', '2026-03-17 03:40:54'),
(22, 4, 'Login Your account.', 'Your account has been logged in new device.', 0, '2026-03-18 22:49:09', '2026-03-18 22:49:09'),
(23, 4, 'New Product Added in Cart', 'New product Added in Cart for proccessed in checkout.', 0, '2026-03-18 23:44:04', '2026-03-18 23:44:04'),
(24, 4, 'New Product Added in Wishlist', 'Your new product Added in wishlist please check your wishlist and place order.', 0, '2026-03-18 23:45:29', '2026-03-18 23:45:29'),
(25, 4, 'New Product Added in Wishlist', 'Your new product Added in wishlist please check your wishlist and place order.', 0, '2026-03-18 23:46:32', '2026-03-18 23:46:32'),
(49, 9, 'Welcome on MyShop.', 'Your account has been activated now and your First Order Discount in 20% Coupon is WELCOME0E524B', 0, '2026-03-22 22:43:20', '2026-03-22 22:43:20'),
(50, 9, 'New Product Added in Cart', 'New product Added in Cart for proccessed in checkout.', 0, '2026-03-22 22:51:12', '2026-03-22 22:51:12'),
(60, 9, 'Login Your account.', 'Your account has been logged in new device.', 0, '2026-03-23 10:16:09', '2026-03-23 10:16:09'),
(61, 9, 'New Product Added in Cart', 'New product Added in Cart for proccessed in checkout.', 0, '2026-03-23 10:17:46', '2026-03-23 10:17:46'),
(85, 10, 'Welcome on MyShop.', 'Your account has been activated now and yor First Order Discount in 20% Coupon is WELCOME9ABE75', 0, '2026-03-26 08:28:25', '2026-03-26 08:28:25'),
(86, 10, 'New Product Added in Cart', 'New product Added in Cart for proccessed in checkout.', 0, '2026-03-26 08:29:51', '2026-03-26 08:29:51'),
(87, 10, 'New Order Placed', 'Your order #ORD-69C4EEA36DB40 has been successfully placed.', 0, '2026-03-26 08:30:51', '2026-03-26 08:30:51'),
(88, 10, 'New Product Added in Cart', 'New product Added in Cart for proccessed in checkout.', 0, '2026-03-26 08:35:14', '2026-03-26 08:35:14'),
(89, 10, 'New Order Placed', 'Your order #ORD-69C4F060AC30B has been successfully placed.', 0, '2026-03-26 08:37:52', '2026-03-26 08:37:52'),
(90, 10, 'New Product Added in Cart', 'New product Added in Cart for proccessed in checkout.', 0, '2026-03-26 08:38:45', '2026-03-26 08:38:45'),
(91, 10, 'New Order Placed', 'Your order #ORD-69C4F09D81D74 has been successfully placed.', 0, '2026-03-26 08:38:53', '2026-03-26 08:38:53'),
(92, 10, 'New Product Added in Cart', 'New product Added in Cart for proccessed in checkout.', 0, '2026-03-26 08:49:15', '2026-03-26 08:49:15'),
(93, 10, 'New Order Placed', 'Your order #ORD-69C4F33DE02E2 has been successfully placed.', 0, '2026-03-26 08:50:40', '2026-03-26 08:50:40');

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
(1, 'ORD-69C0A0EFF1575', 1, 1098.00, NULL, NULL, 0.00, 'delivered', '2026-03-26', 'COD', NULL, NULL, NULL, '2026-03-22 20:39:52', '2026-03-26 10:12:27', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(2, 'ORD-69C0A233E7802', 1, 354.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-22 20:45:15', '2026-03-22 21:24:00', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(3, 'ORD-69C0EFFAF3F72', 1, 29999.00, 29099.00, NULL, 0.00, 'cancelled', NULL, 'STRIPE', 'pi_3TE3CiCSipI1MBTM0odSJBRc', '4242', NULL, '2026-03-23 07:47:34', '2026-03-25 06:54:44', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(4, 'ORD-69C0F0CE21EB0', 1, 29999.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-23 07:50:38', '2026-03-23 07:50:38', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(5, 'ORD-69C357AE1D2AF', 1, 139999.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-25 03:34:06', '2026-03-25 03:34:06', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(6, 'ORD-69C35884146E8', 1, 399.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-25 03:37:40', '2026-03-25 03:37:40', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(7, 'ORD-69C358B42BF88', 1, 449.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-25 03:38:28', '2026-03-25 03:38:28', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(8, 'ORD-69C35A1029554', 1, 399.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-25 03:44:16', '2026-03-25 03:44:16', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(9, 'ORD-69C35AEE36333', 1, 354.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-25 03:47:58', '2026-03-25 06:14:38', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(10, 'ORD-69C35C752777B', 1, 449.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-25 03:54:29', '2026-03-25 06:16:58', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(11, 'ORD-69C35EC39F70D', 1, 354.00, NULL, 'G6O6O8KP', 35.40, 'delivered', NULL, 'COD', NULL, NULL, NULL, '2026-03-25 04:04:19', '2026-03-25 05:15:47', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(12, 'ORD-69C4BDC153FC6', 1, 139999.00, NULL, NULL, 0.00, 'paid', NULL, 'STRIPE', 'pi_3TF63KCSipI1MBTM18OCQ1Ij', '4242', NULL, '2026-03-26 05:02:07', '2026-03-26 05:02:07', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(13, 'ORD-69C4D4A8EB62A', 1, 370.00, NULL, NULL, 0.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-26 06:39:36', '2026-03-26 06:39:36', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340'),
(14, 'ORD-69C4EEA36DB40', 10, 139999.10, NULL, NULL, 0.00, 'paid', NULL, 'STRIPE', 'pi_3TF9JACSipI1MBTM01OBlhXa', '4242', NULL, '2026-03-26 08:30:51', '2026-03-26 08:30:51', 'ssfdsd, dsfdsd, sdfsdfsd, sfsdds - 524125'),
(15, 'ORD-69C4F060AC30B', 10, 5499.00, NULL, 'X0U9601J', 200.00, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-26 08:37:52', '2026-03-26 08:37:52', 'ssfdsd, dsfdsd, sdfsdfsd, sfsdds - 524125'),
(16, 'ORD-69C4F09D81D74', 10, 14999.00, NULL, 'WELCOME9ABE75', 1499.90, 'pending', NULL, 'COD', NULL, NULL, NULL, '2026-03-26 08:38:53', '2026-03-26 08:38:53', 'ssfdsd, dsfdsd, sdfsdfsd, sfsdds - 524125'),
(17, 'ORD-69C4F33DE02E2', 10, 354.00, NULL, 'I0A8OC3H', 50.00, 'delivered', '2026-03-26', 'STRIPE', 'pi_3TF9cACSipI1MBTM02oG0MVz', '4242', NULL, '2026-03-26 08:50:40', '2026-03-26 09:23:05', 'ssfdsd, dsfdsd, sdfsdfsd, sfsdds - 524125'),
(18, 'ORD-69C5FD353AEC3', 1, 313.95, NULL, NULL, 0.00, 'shipped', NULL, 'COD', NULL, NULL, NULL, '2026-03-27 03:44:53', '2026-04-08 10:03:29', 'Nakur To ambehta Road , Chhuchhakpur, Saharanpur, UP, India - 247340');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_variant_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 6, 1, 499.00, '2026-03-22 20:39:52', '2026-03-22 20:39:52'),
(2, 1, 10, 7, 1, 599.00, '2026-03-22 20:39:52', '2026-03-22 20:39:52'),
(3, 2, 1, 1, 1, 354.00, '2026-03-22 20:45:15', '2026-03-22 20:45:15'),
(4, 3, 9, 4, 1, 29999.00, '2026-03-23 07:47:34', '2026-03-23 07:47:34'),
(5, 4, 9, 4, 1, 29999.00, '2026-03-23 07:50:38', '2026-03-23 07:50:38'),
(6, 5, 4, 20, 1, 139999.00, '2026-03-25 03:34:06', '2026-03-25 03:34:06'),
(7, 6, 11, 10, 1, 399.00, '2026-03-25 03:37:40', '2026-03-25 03:37:40'),
(9, 8, 11, 10, 1, 399.00, '2026-03-25 03:44:16', '2026-03-25 03:44:16'),
(10, 9, 1, 1, 1, 354.00, '2026-03-25 03:47:58', '2026-03-25 03:47:58'),
(12, 11, 1, 1, 1, 354.00, '2026-03-25 04:04:19', '2026-03-25 04:04:19'),
(13, 12, 9, 20, 1, 139999.00, '2026-03-26 05:02:07', '2026-03-26 05:02:07'),
(14, 13, 2, 19, 1, 370.00, '2026-03-26 06:39:36', '2026-03-26 06:39:36'),
(15, 14, 9, 20, 1, 139999.00, '2026-03-26 08:30:51', '2026-03-26 08:30:51'),
(16, 15, 3, 2, 1, 5499.00, '2026-03-26 08:37:52', '2026-03-26 08:37:52'),
(17, 16, 9, 12, 1, 14999.00, '2026-03-26 08:38:53', '2026-03-26 08:38:53'),
(18, 17, 11, 1, 1, 354.00, '2026-03-26 08:50:40', '2026-03-26 08:50:40'),
(19, 18, 1, 8, 1, 299.00, '2026-03-27 03:44:53', '2026-03-27 03:44:53');

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
(1, 1, 2, 'Men\'s t-shirt', 'Men\'s t-shirt', 'products/lwMBPNjhIEElIQx9mRl8Dk9zqw91Ud3LOhcbld5P.jpg', '[]', 1, '2026-03-20 23:48:00', '2026-03-27 09:12:09', 1),
(2, 3, 2, 'Kurti', NULL, 'products/c9phxTK9BUKprfeCPzKvnW9FHl98rWFMelpdq0AW.webp', '[\"products\\/jkCmJLY60pIeBlZ33PRicvX4VqfiBCCAjZz11bdL.webp\",\"products\\/iF2pkcbglwwgTEayUBqSB20at0T8RhJyCvDJXiID.webp\",\"products\\/fwmEsEkwrAHJN7ytRRhR4KHYfwXmTafR2Myq0GHL.webp\"]', 1, '2026-03-20 23:48:00', '2026-03-23 08:17:34', 1),
(3, 4, 2, 'Buds Air 7 Pro', 'Buds Air 7 Pro with 53dB ANC,360 Spatial Audio, 48hrs Playback, IP55 ,6 Mic Bluetooth Headset (Glory Beige, True Wireless)', 'products/OXRPTyAuHj42Spfk6Y6F8NYNYgpTwN2MMHYwyr65.webp', '[\"products\\/AiorWO8djv7EoHAmueydora4IKsOlEeqnx3C3Dn5.webp\",\"products\\/szXPRDJbgp97dD9A2Tto4LYoreu0imVGSdLTs6su.webp\",\"products\\/Gdo34JvNaWtOWXTH5xcU4Y5wcO2Ut0SHCIEUKnkH.webp\",\"products\\/pZEoAteS0RaUI3h8S4omxaIi4hUOIX84uPzEq0z1.webp\",\"products\\/Dqk4utGmVQIbI9UOilVo8izhzeE2FPjnFsnnHRsU.webp\",\"products\\/VLskNJ36zRazPTVjZM8KmWiArHUWErhe2SkiJqOB.webp\",\"products\\/lDEN2XbAFyQXTCldiLaP1Z9yQBJDmkEz2MRFVUFc.webp\",\"products\\/Uy0BaNm6IeymMp31k6yL89KZwTwcwSc2yqDeMT0O.webp\",\"products\\/OP2RI9r5mNk6zCeqPw4Ck5ix88XHkJOnszibEQz2.webp\"]', 1, '2026-03-20 23:48:00', '2026-03-20 23:48:00', 2),
(4, 6, 2, 'Galaxy S26 Ultra 5G (Cobalt Violet, 256 GB)', 'Galaxy S26 Ultra 5G (Cobalt Violet, 256 GB) (12 GB RAM)', 'products/5y2m87XkQAZChvYNlybmXw801NpW3FkUGd4U8V1M.jpg', '[\"products\\/Rq7sx1CP0MBArmkuLWnLJjRWPqLYekUL4358s1B4.jpg\",\"products\\/3bT0R6BbNGM92mN4bFlK96bm6sUyiL0MqHR3X1EL.jpg\",\"products\\/NuOLb0vfZ02VmlRKtvkBB3YWrpYvtLE0Y9sCzjCx.jpg\",\"products\\/62oXJCdmgGZ01fUd37IVZcYQdFqdujqMrjlo9iIA.jpg\",\"products\\/cjDm8ra5Q5YF4dbfVbDBgYAQpRMnZ72Zam9up6cI.jpg\",\"products\\/TBvfmgbKEDRNfEwzfwF9cMD9vG7Hh4ZPokOg0PgX.jpg\",\"products\\/4DJNmmoHA6iCJqnovfgKmsdHOpGDLKCa9hpUML9y.jpg\",\"products\\/ZMS4kZpm5Krn8A5cIvYD8gppw2zn5scS9LUq9Xco.jpg\",\"products\\/OsqwWTcDKLdxZm5zM0purUWFjHfG0t8MJ9UtWpgZ.jpg\"]', 1, '2026-03-20 23:48:00', '2026-03-23 08:20:28', 2),
(7, 6, 2, 'Galaxy F70e 5G (Limelight Green, 128 GB)', 'Galaxy F70e 5G (Limelight Green, 128 GB) (4GB RAM)', 'products/Vn7d1f1NG4x9jfSUzW2KUghyFSG9fKCdjaVn2xOF.webp', '[\"products\\/XWkrlEzySYIDHWHBP8ocU9XDZJvuF3b21gmw6Kim.webp\",\"products\\/4URvaPHKT8306XhYMUyUO1we9edCqj9YUjPF3EnT.webp\",\"products\\/17AVufOprmtU7hhnl95zD0vsBeoP7AoirxNhVy1O.webp\",\"products\\/lxaYQbnW2oBlZm7bHoxyMpaO3ZvVsQKmuf87rkWr.webp\",\"products\\/1NhoLxcl3rN0ipTq1HMpEEOg28H72y0qQ2wkUAae.webp\",\"products\\/0foW40Pf5DihVeNtiUgXgo0pATEa0uDQ5yWhvj7C.webp\",\"products\\/H3cunc2EWjZU9Bq5PYOvj9Vmw0UhReuBaPU84xKd.webp\",\"products\\/M5VC7cSWFImQLl58BQHYPnrSmpx0U58nBBnJA3Xr.webp\",\"products\\/f5kwFz3ShKYm4dWUhRvhhjVkFZIrZuSi7NUIodMf.webp\",\"products\\/67pKeWVEHHd4U3EjEvY0Iine8pyAxCZcMEtTfblO.webp\",\"products\\/gm2XcDh5wN37cEa2UxXmqGMmH4YWBo8KPeNAMjVh.webp\",\"products\\/RmTpPGq7hjsliJtPRwqGYCA0JHGf8hFo5RsLZA9Q.webp\"]', 1, '2026-03-20 23:48:00', '2026-03-20 23:48:00', 2),
(8, 6, 2, 'Realme 15 5G (Flowing Silver, 256 GB)', 'Realme 15 5G (Flowing Silver, 256 GB) (8GB RAM)', 'products/Ggi7ffOvtPd1erWE8l3KAaoa6btTbO7wFmfnkXiE.webp', '[\"products\\/QElMLxZuMzobhqkGBsUevpUx4mzI87eg4M9DkGO9.webp\",\"products\\/VOBTWANaZXBeGVismdmW7sDAGpGwuJzn84jiWXT8.webp\",\"products\\/OuieCd0FZ6B9ChIaEoYVBQpXMUB6ggQvZf5zt59S.webp\",\"products\\/a8BfHrxDHnfYTxzerJaEmG8qBoTkfjCA4r32QR6x.webp\",\"products\\/ZMQufTx6MJ7bjutZiZ2OzDBW80bqAkPPqB65Nb3X.webp\",\"products\\/E7WkAdHpe9a06ujRbgiMhJCXOiCTRPpk7fPBFwe4.webp\",\"products\\/gbjSxIOvMr20yMnFqqTdtpGtnPc8SJsCY2XNGlg5.webp\",\"products\\/e81iwt0HsUYtAVlA5nOtXMh1wQmTGgVUjW5rrcSF.webp\",\"products\\/cIEOWwMPxTaQ3Lr9lANIuL2WWwZIzkNleaPcjjwZ.webp\"]', 1, '2026-03-20 23:48:00', '2026-03-20 23:48:00', 2),
(9, 6, 2, 'Pova Curve 2 5G (Mystic Purple, 128 GB)', 'Pova Curve 2 5G (Mystic Purple, 128 GB) (8 GB RAM)', 'products/nOXAxiJe4YvWhPPKwUZts7W831zmvxIaRQ5vMPsn.webp', '[\"products\\/DtNVg8IMF1Cjt7Dqrqtr7tiekiZ0TNmqfTHsn04l.webp\",\"products\\/3sSsgXRR7Y8cUiiqD957zM3PnwdYt1x6rwrrYGIR.webp\",\"products\\/PqjWUUSxasxDd1ix7djHJnYjR0u9Qd5CXUhSB8tQ.webp\",\"products\\/18qUdyaBsU87nVLkQq5OTirbF0qsYdLW3x9cIrRr.webp\",\"products\\/f3oJo7carbyBh2yHgbpi6Wopmlc4nv4fEr9O58Y9.webp\",\"products\\/hr5PnCeuB53gxrhLTGa6agNoioqq0ds6jXQa0DLX.webp\",\"products\\/moL1z9IC2HdCvosKPvDTqXfiHV3RKCN63a2sRNRa.webp\",\"products\\/r26i6d0MStE2sAXpt1iUZimOS4m7ZrBL2nCUm49L.webp\",\"products\\/zUfbzJepGITUIN17pPBgROK51WzedXIdWzqOTQ9P.webp\",\"products\\/HH3qb5jj5IW2Fljou1J5k6JCwT0Isd7YtIxidlTW.webp\",\"products\\/QraL3CNe2Js5gOtiA9HFEO9vrmy1TPvP94YVGuLH.webp\",\"products\\/DAFof49FrR9f7Jd5YtmmFYfhpRaIKNhe0ZjgmriY.webp\",\"products\\/Tq2dGv6VVqX8WUYNWlF2TJ8m7AE92MbqFZ2YpOOX.webp\"]', 1, '2026-03-20 23:48:00', '2026-03-20 23:48:00', 2),
(10, 1, 2, 'Athleo Comfortable Lightweight Sports Slip-On Running Shoes For Men', 'Athleo ATG-424 Comfortable Lightweight Breathable Durable Sports Slip-On Running Shoes For Men (Blue , 6)', 'products/o9C76T8xbpYFGCIrb2Iw7W9AcndOfCZiW0H3J26P.webp', '[\"products\\/e0fny8vsKvPoxuqIyGs0K4s7QeRYkownNbSFnybp.webp\",\"products\\/50T7LxSuUDPrK5vRjvPT8ccgaCnSSeyvqfGYGVmV.webp\",\"products\\/1G5V8mzoK1oqOEteuKIDKBFZeGLT9moJIO9WDQ9H.webp\",\"products\\/JIkWbK2rt9N46sBxVqhTDx4KxopbpBveYnTcJgam.webp\",\"products\\/pkUwUiR02yScMNaFf9lQs9qvvBc4FAIs6XN16Aaf.webp\",\"products\\/8Olep9lS0iyuR3pDDT0vNKNtFisrxDccTOGVdJop.webp\"]', 1, '2026-03-20 23:48:01', '2026-03-27 04:07:56', 2),
(11, 1, 2, 'Men Regular Fit Checkered Spread Collar Casual Shirt', 'Men Regular Fit Checkered Spread Collar Casual Shirt', 'products/OQIymKRBZTUCw5YRHdaZl8vkis9D0lUb9CQ2VJSn.webp', '[\"products\\/aWIxSOlHe0SBLiW9hjzy7HYJMAoOoEu8GGpc2FkO.webp\",\"products\\/Ln5l59XbDeiw7gIywhRb5ohiJlOw1WdoXSY740ui.webp\"]', 1, '2026-03-20 23:48:01', '2026-03-27 09:30:34', 2),
(12, 1, 2, 'gfdgdfg', 'dfgdfgdfgdf', 'products/dwimwVOf8l75c2g9PUicscZdQA4PtMsnpmSbY1CB.jpg', '[\"products\\/5OJRxusVXYuRH4Gu5f3NuTrB2d2JcGlkleo8lkr8.png\",\"products\\/LmlMkqtEAz38anDJ6jO1IzoEqmwH5bF27zodE49H.png\",\"products\\/oyyug0SCNpwIjw98db305lM9MJeoJweJ3dogWklX.jpg\"]', 1, '2026-04-08 07:06:51', '2026-04-08 07:12:56', 2);

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
(1, 'Simple Product', 'simple', '2026-03-18 22:10:00', '2026-03-18 22:10:00'),
(2, 'Variable Products', 'variable', '2026-03-18 22:27:06', '2026-03-18 22:27:06'),
(3, 'Default', 'default', '2026-03-20 03:33:25', '2026-03-20 03:33:25');

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
(1, 1, 'MENS-T-SHIRT-BLUE-LMSK', 'XL', 354.00, 407.10, 6, '2026-03-20 23:48:00', '2026-03-27 09:12:09', 2),
(2, 3, 'BUDS-AIR-7-PRO-GOLDEN-HFZF', 'Golden', 5499.00, 5773.95, 14, '2026-03-20 23:48:00', '2026-03-27 05:47:18', 1),
(3, 9, 'POVA-CURVE-2-5G-MYSTIC-PURPLE-128-GB-128-GB-8-GB-XELU', '128 GB + 6GB', 27999.00, 29398.95, 11, '2026-03-20 23:48:00', '2026-03-27 09:05:32', 3),
(4, 9, 'POVA-CURVE-2-5G-MYSTIC-PURPLE-128-GB-256-GB-8GB-YDAD', '128 GB + 8 GB', 29999.00, 31498.95, 7, '2026-03-20 23:48:00', '2026-03-27 05:47:18', 3),
(5, 9, 'POVA-CURVE-2-5G-MYSTIC-PURPLE-128-GB-256-GB-12GB-PXRK', '256 GB + 8GB', 32999.00, 34648.95, 0, '2026-03-20 23:48:01', '2026-03-27 05:47:18', 3),
(6, 10, 'ATHLEO-COMFORTABLE-LIGHTWEIGHT-SPORTS-SLIP-ON-RUNNING-SHOES-FOR-MEN-BLACK-5C8G', 'L', 499.00, 523.95, 1, '2026-03-20 23:48:01', '2026-03-27 05:47:18', 2),
(7, 10, 'ATHLEO-COMFORTABLE-LIGHTWEIGHT-SPORTS-SLIP-ON-RUNNING-SHOES-FOR-MEN-BLUE-1ZMK', 'Black', 599.00, 628.95, 0, '2026-03-20 23:48:01', '2026-03-27 05:47:18', 1),
(8, 11, 'MEN-REGULAR-FIT-CHECKERED-SPREAD-COLLAR-CASUAL-SHIRT-L-ESGD', 'Blue', 299.00, 313.95, 9, '2026-03-20 23:48:01', '2026-03-27 09:30:34', 1),
(9, 11, 'MEN-REGULAR-FIT-CHECKERED-SPREAD-COLLAR-CASUAL-SHIRT-M-CBR1', 'L', 349.00, 366.45, 10, '2026-03-20 23:48:01', '2026-03-27 09:30:34', 2),
(10, 11, 'MEN-REGULAR-FIT-CHECKERED-SPREAD-COLLAR-CASUAL-SHIRT-XL-0IUI', 'Blue', 399.00, 418.95, 5, '2026-03-20 23:48:01', '2026-03-27 09:30:34', 1),
(12, 7, 'GALAXY-F70E-5G-LIMELIGHT-GREEN-128-GB-128-GB-6GB-TBTW', '128 GB + 6GB', 14999.00, 15748.95, 49, '2026-03-23 08:10:56', '2026-03-27 05:47:18', 3),
(13, 7, 'GALAXY-F70E-5G-LIMELIGHT-GREEN-128-GB-128-GB-8-GB-SNOI', '128 GB + 8 GB', 16999.00, 17848.95, 20, '2026-03-23 08:10:56', '2026-03-27 05:47:18', 3),
(14, 8, 'REALME-15-5G-FLOWING-SILVER-256-GB-128-GB-8-GB-YWNI', '128 GB + 8 GB', 29999.00, 31498.95, 10, '2026-03-23 08:14:04', '2026-03-27 05:47:18', 3),
(15, 8, 'REALME-15-5G-FLOWING-SILVER-256-GB-256-GB-8GB-2VUZ', '256 GB + 8GB', 31999.00, 33598.95, 10, '2026-03-23 08:14:04', '2026-03-27 05:47:18', 3),
(16, 8, 'REALME-15-5G-FLOWING-SILVER-256-GB-256-GB-8GB-JX9R', '256 GB + 8GB', 34999.00, 36748.95, 10, '2026-03-23 08:14:04', '2026-03-27 05:47:18', 3),
(17, 2, 'KURTI-L-L9PY', 'L', 323.00, 371.45, 10, '2026-03-23 08:17:34', '2026-03-27 02:58:37', 2),
(18, 2, 'KURTI-M-4ND8', 'M', 330.00, 379.50, 10, '2026-03-23 08:17:34', '2026-03-27 02:58:37', 2),
(19, 2, 'KURTI-XL-ARU3', 'XL', 370.00, 425.50, 0, '2026-03-23 08:17:34', '2026-03-27 02:58:37', 2),
(20, 4, 'GALAXY-S26-ULTRA-5G-COBALT-VIOLET-256-GB-256-GB-12GB-BBBL', '256 GB + 12GB', 139999.00, 146998.95, 7, '2026-03-23 08:19:40', '2026-03-27 05:47:18', 3),
(21, 4, 'GALAXY-S26-ULTRA-5G-COBALT-VIOLET-256-GB-512-GB-12GB-VG4B', '512 GB + 12GB', 159999.00, 167998.95, 16, '2026-03-23 08:19:40', '2026-03-27 05:47:18', 3),
(22, 4, 'GALAXY-S26-ULTRA-5G-COBALT-VIOLET-256-GB-1TB-12GB-KIK7', '1TB + 12GB', 189999.00, 199498.95, 13, '2026-03-23 09:36:17', '2026-03-27 05:47:18', 3),
(23, 12, 'GFDGDFG-M-WVCG', 'M', 2121.00, 2227.05, 0, '2026-04-08 07:06:51', '2026-04-08 07:06:51', 2);

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

INSERT INTO `ratings` (`id`, `customer_id`, `product_id`, `rating`, `review`, `title`, `helpful_yes`, `helpful_no`, `is_verified_purchase`, `created_at`, `updated_at`) VALUES
(4, 4, 25, 5, 'Best colors and sizes are good in this price.', 'This product quality is good', 0, 0, 0, '2026-03-19 00:48:20', '2026-03-19 00:48:20'),
(5, 1, 11, 5, 'sfsdfsadfsd', 'safsdfsd', 0, 0, 0, '2026-03-19 03:00:27', '2026-03-19 03:00:27'),
(6, 1, 9, 5, 'Look is very Smart.', 'This is good product.', 0, 0, 0, '2026-03-23 06:34:53', '2026-03-23 06:34:53');

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
(10, 9, 9, '2026-03-22 22:49:03', '2026-03-22 22:49:03'),
(42, 9, 1, '2026-03-23 10:17:44', '2026-03-23 10:17:44'),
(43, 1, 4, '2026-03-23 10:24:00', '2026-03-23 10:24:00'),
(81, 10, 3, '2026-03-26 08:35:12', '2026-03-26 08:35:12'),
(82, 10, 9, '2026-03-26 08:38:42', '2026-03-26 08:38:42'),
(83, 10, 11, '2026-03-26 08:49:14', '2026-03-26 08:49:14'),
(94, 1, 3, '2026-03-27 03:54:45', '2026-03-27 03:54:45'),
(97, 1, 2, '2026-03-27 04:42:03', '2026-03-27 04:42:03'),
(98, 1, 1, '2026-04-02 06:48:57', '2026-04-02 06:48:57'),
(99, 1, 11, '2026-04-02 06:50:39', '2026-04-02 06:50:39'),
(100, 1, 9, '2026-04-02 06:50:45', '2026-04-02 06:50:45');

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

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `name`, `discount`, `type`, `scope`, `scope_id`, `starts_at`, `ends_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Summer Sale', 10.00, 'percent', 'product_type', 2, '2026-03-23 05:46:00', '2026-03-24 10:30:00', 1, '2026-03-23 00:03:11', '2026-03-24 09:20:22'),
(3, 'Tags Sale', 10.00, 'percent', 'tag', 1, '2026-03-24 05:28:00', '2026-03-25 05:28:00', 1, '2026-03-24 05:28:22', '2026-03-25 05:57:55'),
(4, 'New sale', 5.00, 'percent', 'tag', 1, '2026-03-27 04:25:00', '2026-04-10 04:25:00', 0, '2026-03-27 04:25:42', '2026-03-30 02:42:14');

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
('AMrEB02iKlJoJRstVXMwq8q5TW7XURBioj2xOLNX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWjVZM1RHWEljNHBBcTVLemxMM1JBQzh5OVdtdDg4b0V5NkY1MGZLdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zYWxlcy1yZXBvcnQiO3M6NToicm91dGUiO3M6MTI6InNhbGVzLnJlcG9ydCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6ODoiYWRtaW5faWQiO2k6MjtzOjEwOiJhZG1pbl9uYW1lIjtzOjU6IkFkbWluIjtzOjEyOiJwcm9kdWN0c191cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9kdWN0Ijt9', 1775643458);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2026-03-06 20:16:47', '$2y$12$ta52oRzDEOey07MSm9MDTOuYr4f6aYaLmVTyhzlj/3hqOMYSHaW8.', '9LaJjiuaLq', '2026-03-06 20:16:48', '2026-03-06 20:16:48'),
(2, 'Admin', 'admin@gmail.com', NULL, '$2y$12$2SPaPF8S.E/i/uUCkxrnLuU6t2erUsrr/hcNVsQ6rGffgcCuAL4yO', NULL, '2026-03-06 20:18:31', '2026-03-06 20:18:31');

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
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `customer_id`, `product_id`, `created_at`, `updated_at`) VALUES
(10, 4, 21, '2026-03-18 23:46:32', '2026-03-18 23:46:32');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guest_carts`
--
ALTER TABLE `guest_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `import_batches`
--
ALTER TABLE `import_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `margins`
--
ALTER TABLE `margins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_tag`
--
ALTER TABLE `product_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `recent_views`
--
ALTER TABLE `recent_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `return_orders`
--
ALTER TABLE `return_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- Constraints for table `product_tag`
--
ALTER TABLE `product_tag`
  ADD CONSTRAINT `product_tag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recent_views`
--
ALTER TABLE `recent_views`
  ADD CONSTRAINT `recent_views_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recent_views_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return_orders`
--
ALTER TABLE `return_orders`
  ADD CONSTRAINT `return_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `return_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
