-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 05:36 AM
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
-- Database: `food-api`
--

-- --------------------------------------------------------

--
-- Table structure for table `allergens`
--

CREATE TABLE `allergens` (
  `key_id` int(11) NOT NULL,
  `allergen_id` char(6) NOT NULL,
  `allergen_name` varchar(120) DEFAULT NULL,
  `allergen_reaction_type` varchar(120) DEFAULT NULL,
  `food_group` varchar(120) DEFAULT NULL,
  `food_type` varchar(120) DEFAULT NULL,
  `food_origin` varchar(120) DEFAULT NULL,
  `food_item` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allergens`
--

INSERT INTO `allergens` (`key_id`, `allergen_id`, `allergen_name`, `allergen_reaction_type`, `food_group`, `food_type`, `food_origin`, `food_item`) VALUES
(1, 'A01', 'Soy Allergy', 'Hives, anaphylaxis', 'Pulse', 'Cereal grain and pulse', 'Plant origin', 'Soybean'),
(2, 'A02', 'Peanut Allergy', 'Hives, Abdominal pain, Anaphylaxis', 'Pulse', 'Cereal grain and pulse', 'Plant origin', 'Peanut'),
(3, 'A03', 'Poultry Allergy', 'Hives, anaphylaxis', 'Egg', 'Poultry', 'Animal origin', 'Eggs'),
(4, 'A04', 'Milk Allergy', 'Hives, Gastrointestinal distress, anaphylaxis', 'Dairy', 'Dairy', 'Animal origin', 'Cow Milk'),
(5, 'A05', 'Gluten Allergy', 'Bloating, diarrhea, abdominal pain', 'Cereal grain', 'Cereal grain and pulse', 'Plant origin', 'Oats'),
(6, 'A06', 'Carrot Allergy', 'Hives, anaphylaxis', 'Pulse', 'Cereal grain and pulse', 'Plant origin', 'Soybean'),
(24, 'A07', 'Citrus Allergy', 'Hives, oral itching, swelling, GI discomfort', 'Citrus fruit', 'Fresh fruit', 'Plant origin', 'Lemon'),
(25, 'A08', 'Citrus Allergy', 'Hives, oral itching, swelling, GI discomfort', 'Citrus fruit', 'Fresh fruit', 'Plant origin', 'Lime'),
(26, 'A09', 'Berry Allergy', 'Hives, runny nose, anaphylaxis', 'Berry', 'Fresh fruit', 'Plant origin', 'Strawberry'),
(27, 'A10', 'Berry Allergy', 'Hives, runny nose, anaphylaxis', 'Berry', 'Fresh fruit', 'Plant origin', 'Raspberry'),
(28, 'A11', 'Berry Allergy', 'Hives, runny nose, anaphylaxis', 'Berry', 'Fresh fruit', 'Plant origin', 'Blueberry'),
(29, 'A12', 'Tropical Fruit Allergy', 'Anaphylaxis, itching, stomach pain', 'Tropical fruit', 'Fresh fruit', 'Plant origin', 'Mango'),
(30, 'A13', 'Tropical Fruit Allergy', 'Anaphylaxis, itching, stomach pain', 'Tropical fruit', 'Fresh fruit', 'Plant origin', 'Pineapple'),
(31, 'A14', 'Tropical Fruit Allergy', 'Anaphylaxis, itching, stomach pain', 'Tropical fruit', 'Fresh fruit', 'Plant origin', 'Papaya'),
(32, 'A15', 'Stone Fruit Allergy', 'Oral allergy syndrome, swelling', 'Stone fruit (drupe)', 'Fresh fruit', 'Plant origin', 'Peach'),
(33, 'A16', 'Stone Fruit Allergy', 'Oral allergy syndrome, swelling', 'Stone fruit (drupe)', 'Fresh fruit', 'Plant origin', 'Plum'),
(34, 'A17', 'Stone Fruit Allergy', 'Oral allergy syndrome, swelling', 'Stone fruit (drupe)', 'Fresh fruit', 'Plant origin', 'Cherry'),
(35, 'A18', 'Melon Allergy', 'Hives, oral irritation, anaphylaxis', 'Melon', 'Fresh fruit', 'Plant origin', 'Cantaloupe'),
(36, 'A19', 'Melon Allergy', 'Hives, oral irritation, anaphylaxis', 'Melon', 'Fresh fruit', 'Plant origin', 'Watermelon'),
(37, 'A20', 'Melon Allergy', 'Hives, oral irritation, anaphylaxis', 'Melon', 'Fresh fruit', 'Plant origin', 'Honeydew'),
(38, 'A21', 'Rosaceae Allergy', 'Oral allergy syndrome (especially with birch pollen)', 'Pome fruit', 'Fresh fruit', 'Plant origin', 'Apple'),
(39, 'A22', 'Rosaceae Allergy', 'Oral allergy syndrome (especially with birch pollen)', 'Pome fruit', 'Fresh fruit', 'Plant origin', 'Pear');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `key_id` int(11) NOT NULL,
  `brand_id` char(6) NOT NULL,
  `brand_name` varchar(120) NOT NULL,
  `brand_country` varchar(100) DEFAULT NULL,
  `brand_image` varchar(100) DEFAULT NULL,
  `brand_website` varchar(300) DEFAULT NULL,
  `isBrandSustainable` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`key_id`, `brand_id`, `brand_name`, `brand_country`, `brand_image`, `brand_website`, `isBrandSustainable`) VALUES
(1, 'B0001', 'Mateina', 'Canada', 'https://mateina.ca/cdn/shop/files/LOGO_MATEINA_V2.png', 'https://mateina.ca', 1),
(2, 'B0002', 'Oatly', 'Sweden', 'https://a.storyblok.com/f/107921/1920x1080/db39428076/oatlyxnespresso-shareimage.png', 'https://www.oatly.com', 1),
(3, 'B0003', 'Natrel', 'Canada', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTlnTuE4hulgSl95DOaV0rolagPTquqeeZccKPJW9V7wnWh', 'https://www.natrel.ca', 1),
(4, 'B0004', 'Hershey’s', 'USA', 'https://www.hersheyland.com', 'www.hersheyland.com', 0),
(5, 'B0005', 'Cadbury', 'United Kingdom', 'https://www.cadbury.co.uk', 'https://www.cadbury.co.uk', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `key_id` int(11) NOT NULL,
  `category_id` char(6) NOT NULL,
  `category_name` varchar(120) NOT NULL,
  `category_description` varchar(400) NOT NULL,
  `parent_category_id` char(6) DEFAULT NULL,
  `category_type` varchar(100) NOT NULL,
  `category_level` varchar(11) NOT NULL,
  `category_tags` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`key_id`, `category_id`, `category_name`, `category_description`, `parent_category_id`, `category_type`, `category_level`, `category_tags`) VALUES
(1, 'C-0001', 'Plant-Based Beverage', 'Made from ingredients derived from plants without any animal products', NULL, 'Beverage', 'parent', 'Vegan, drink, plant'),
(2, 'C-0002', 'Animal-Based Beverage', 'Made from animal-derived ingredients', NULL, 'Beverage', 'parent', 'Animal, drink'),
(3, 'C-0003', 'Confectionery', 'Sweet and sugary food', NULL, 'Food', 'parent', 'Sweet, dessert, snacks'),
(4, 'C-0004', 'Energy Drink', 'Beverages formulated to enhance physical energy and mental alertness.', 'C-0001', 'Beverage', 'child', 'Caffeine, drink, energy'),
(5, 'C-0005', 'Milk', 'Milk product', 'C-0002', 'Beverage', 'child', 'Milk, plant-based, vegan, dairy, animal-derived'),
(6, 'C-0006', 'Chocolate', 'Encompasses all chocolate-based products, including bars, candies, truffles, and baking chocolate', 'C-0003', 'Snack', 'child', 'Snack, chocolate, sweet');

-- --------------------------------------------------------

--
-- Table structure for table `dietary_attributes`
--

CREATE TABLE `dietary_attributes` (
  `key_id` int(11) NOT NULL,
  `diet_id` char(6) NOT NULL,
  `dairy_free` tinyint(1) DEFAULT NULL,
  `gluten_free` tinyint(1) DEFAULT NULL,
  `soy_free` tinyint(1) DEFAULT NULL,
  `nut_free` tinyint(1) DEFAULT NULL,
  `vegan` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dietary_attributes`
--

INSERT INTO `dietary_attributes` (`key_id`, `diet_id`, `dairy_free`, `gluten_free`, `soy_free`, `nut_free`, `vegan`) VALUES
(1, 'DA0001', 1, 1, 1, 1, 1),
(2, 'DA0002', 1, 0, 1, 1, 1),
(3, 'DA0003', 0, 1, 1, 1, 0),
(4, 'DA0004', 0, 1, 0, 0, 0),
(5, 'DA0005', 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `environmentals`
--

CREATE TABLE `environmentals` (
  `key_id` int(11) NOT NULL,
  `environmental_id` char(6) NOT NULL,
  `product_id` char(6) NOT NULL,
  `packaging_name` varchar(100) DEFAULT NULL,
  `eco_score` int(11) DEFAULT NULL,
  `transportation_score` int(11) DEFAULT NULL,
  `fromPalmOil` tinyint(1) DEFAULT NULL,
  `recycling_info` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `environmentals`
--

INSERT INTO `environmentals` (`key_id`, `environmental_id`, `product_id`, `packaging_name`, `eco_score`, `transportation_score`, `fromPalmOil`, `recycling_info`) VALUES
(1, 'E00001', 'P00001', 'Aluminum Can', NULL, NULL, 0, 'Recyclable'),
(2, 'E00002', 'P00002', 'Carton', 80, 75, 0, 'Recyclable'),
(3, 'E00003', 'P00003', 'Carton', 75, 70, 0, 'Recyclable'),
(4, 'E00004', 'P00004', 'Wrapper', NULL, NULL, 0, 'Not Specified'),
(5, 'E00005', 'P00005', 'Foil Wrapper', NULL, NULL, 0, 'Recyclable');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `key_id` int(11) NOT NULL,
  `ingredient_id` char(6) NOT NULL,
  `allergen_id` char(6) DEFAULT NULL,
  `category_id` char(6) DEFAULT NULL,
  `ingredient_name` varchar(120) NOT NULL,
  `processing_type` varchar(120) DEFAULT NULL,
  `ingredient_description` varchar(400) DEFAULT NULL,
  `isGMO` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`key_id`, `ingredient_id`, `allergen_id`, `category_id`, `ingredient_name`, `processing_type`, `ingredient_description`, `isGMO`) VALUES
(1, 'I01', NULL, 'C-0002', 'Organic yerba mate', 'Brewed', 'Infused organic yerba mate with organic peach juice and natural flavors', 0),
(2, 'I02', 'A05', 'C-0003', 'Oat base', 'UHT', 'A blend of water, oats, and a small amount of added vitamins', 0),
(3, 'I03', 'A04', 'C-0005', 'Partly skimmed milk', 'Fine-filtered', 'Partly skimmed milk, fortified with vitamin A palmitate and vitamin D3', 0),
(4, 'I04', 'A04', 'C-0006', 'Milk Chocolate, Peanuts, Sugar, Dextrose, Salt, TBHQ, Citric Acid', 'Mixed and Molded', 'Mixture of milk chocolate, peanuts, and other ingredients molded into cup shapes', 0),
(5, 'I05', 'A04', 'C-0006', 'Milk chocolate', 'Mixed and Molded', 'Fat derived from cocoa beans that gives chocolate its melt-in-your-mouth texture.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nutritions`
--

CREATE TABLE `nutritions` (
  `key_id` int(11) NOT NULL,
  `nutritional_id` char(6) NOT NULL,
  `energy` float DEFAULT NULL,
  `fat` float DEFAULT NULL,
  `saturated_fat` float DEFAULT NULL,
  `protein` float DEFAULT NULL,
  `carbs` float DEFAULT NULL,
  `sodium` float DEFAULT NULL,
  `sugar` float DEFAULT NULL,
  `nova_group` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nutritions`
--

INSERT INTO `nutritions` (`key_id`, `nutritional_id`, `energy`, `fat`, `saturated_fat`, `protein`, `carbs`, `sodium`, `sugar`, `nova_group`) VALUES
(1, 'N00001', 45, 0, 0, 0.3, 10, 10, 8, 4),
(2, 'N00002', 60, 3, 0.3, 1, 6.5, 10, 4, 4),
(3, 'N00003', 130, 5, 3, 9, 13, 105, 13, 4),
(4, 'N00004', 210, 12, 4.5, 4, 24, 135, 22, 4),
(5, 'N00005', 240, 14, 8, 3, 25, 0.06, 24, 4);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `key_id` int(11) NOT NULL,
  `product_id` char(6) NOT NULL,
  `product_name` varchar(120) NOT NULL,
  `product_barcode` varchar(50) DEFAULT NULL,
  `product_origin` varchar(100) DEFAULT NULL,
  `product_serving_size` float DEFAULT NULL,
  `product_image` varchar(200) DEFAULT NULL,
  `nutrition_id` char(6) DEFAULT NULL,
  `diet_id` char(6) DEFAULT NULL,
  `brand_id` char(6) DEFAULT NULL,
  `category_id` char(6) DEFAULT NULL,
  `environmental_id` char(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`key_id`, `product_id`, `product_name`, `product_barcode`, `product_origin`, `product_serving_size`, `product_image`, `nutrition_id`, `diet_id`, `brand_id`, `category_id`, `environmental_id`) VALUES
(1, 'P00001', 'Peach Passion Energy Infusion', '2147483647', 'Canada', 355, 'https://mateina.ca/cdn/shop/files/Mateina-Organic-Yerba-Mate-Drink-Canada-Peach-Passion-Boisson-Mate-Peche-Passion-Bio--Trimmed_720x.png', 'N00001', 'DA0001', 'B0001', 'C-0004', 'E00001'),
(2, 'P00002', 'Oat Drink Barista Edition', '2147483647', 'Sweden', 240, 'https://m.media-amazon.com/images/I/51eulMv-7HL.jpg', 'N00002', 'DA0002', 'B0002', 'C-0005', 'E00002'),
(3, 'P00003', 'Fine-Filtered 2% Milk', NULL, 'Canada', 250, 'https://www.natrel.ca/sites/default/files/styles/product_image_large/public/2025-01/Natrel-Fine-Filtered-Milk-2%25-2L_0.png', 'N00003', 'DA0003', 'B0003', 'C-0005', 'E00003'),
(4, 'P00004', 'Reese\'s Milk Chocolate Peanut Butter Cups', '2147483647', 'USA', 42, NULL, 'N00004', 'DA0004', 'B0004', 'C-0006', 'E00004'),
(5, 'P00005', 'Cadbury Dairy Milk Chocolate', '2147483647', 'United Kingdom', 45, NULL, 'N00005', 'DA0005', 'B0005', 'C-0006', 'E00005');

-- --------------------------------------------------------

--
-- Table structure for table `product_allergen`
--

CREATE TABLE `product_allergen` (
  `pa_id` char(6) NOT NULL,
  `product_id` char(6) NOT NULL,
  `allergen_id` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ingredients`
--

CREATE TABLE `product_ingredients` (
  `pi_id` char(6) NOT NULL,
  `product_id` char(6) NOT NULL,
  `ingredient_id` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ws_log`
--

CREATE TABLE `ws_log` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(150) NOT NULL,
  `user_action` varchar(255) NOT NULL,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ws_log`
--

INSERT INTO `ws_log` (`log_id`, `email`, `user_action`, `logged_at`, `user_id`) VALUES
(1, 'centro@yahoo.com', 'GET /product-api/products', '2025-05-15 17:26:44', 0),
(2, 'centro@yahoo.com', 'GET /product-api/products', '2025-05-15 17:28:19', 0),
(3, 'centro@yahoo.com', 'GET /product-api/products', '2025-05-15 17:30:43', 0),
(4, 'centro@yahoo.com', 'GET /product-api/products', '2025-05-15 17:32:50', 0),
(5, 'centro@yahoo.com', 'GET /product-api/products', '2025-05-15 17:32:56', 0),
(6, 'centro@yahoo.com', 'GET /product-api/products', '2025-05-15 17:39:32', 0),
(7, 'centro@yahoo.com', 'GET /product-api/recipes/product/P00005', '2025-05-15 17:39:37', 0),
(8, 'centro@yahoo.com', 'GET /product-api/recipes/product/P00005', '2025-05-15 17:50:51', 0),
(9, '', 'POST /product-api/fiber', '2025-05-15 17:54:43', 0),
(10, '', 'POST /product-api/login', '2025-05-16 18:43:20', 11),
(11, '', 'POST /product-api/login', '2025-05-16 18:44:10', 11),
(12, '', 'POST /product-api/login', '2025-05-16 18:44:52', 11),
(13, '', 'GET /product-api/products', '2025-05-16 18:45:13', 0),
(14, '', 'GET /product-api/recipes/product/P00007', '2025-05-16 18:46:07', 0),
(15, '', 'GET /product-api/products', '2025-05-16 18:47:28', 0),
(16, '', 'POST /product-api/login', '2025-05-16 18:49:46', 11),
(17, '', 'POST /product-api/admin/products', '2025-05-16 18:50:37', 0),
(18, '', 'DELETE /product-api/admin/products', '2025-05-16 19:21:06', 0),
(19, '', 'POST /product-api/admin/products', '2025-05-16 19:29:40', 0),
(20, '', 'POST /product-api/admin/products', '2025-05-16 19:41:39', 0),
(21, '', 'POST /product-api/admin/products', '2025-05-16 19:42:42', 0),
(22, '', 'DELETE /product-api/admin/products', '2025-05-16 19:44:54', 0),
(23, '', 'POST /product-api/login', '2025-05-16 19:52:36', 11),
(24, '', 'DELETE /product-api/admin/products', '2025-05-16 19:53:17', 0),
(25, '', 'DELETE /product-api/admin/products', '2025-05-16 19:53:29', 0),
(26, '', 'DELETE /product-api/admin/products', '2025-05-16 19:53:46', 0),
(27, '', 'DELETE /product-api/admin/products', '2025-05-16 19:58:33', 0),
(28, '', 'DELETE /product-api/admin/products', '2025-05-16 20:01:39', 0),
(29, '', 'DELETE /product-api/admin/products', '2025-05-16 20:10:31', 0),
(30, '', 'DELETE /product-api/admin/products', '2025-05-16 20:11:28', 0),
(31, '', 'DELETE /product-api/admin/products', '2025-05-16 20:12:14', 0),
(32, '', 'DELETE /product-api/admin/products', '2025-05-16 20:12:14', 0),
(33, '', 'DELETE /product-api/admin/products', '2025-05-16 20:12:48', 0),
(34, '', 'DELETE /product-api/admin/products', '2025-05-16 20:13:27', 0),
(35, '', 'DELETE /product-api/admin/products', '2025-05-16 20:13:43', 0),
(36, '', 'DELETE /product-api/admin/products', '2025-05-16 20:14:56', 0),
(37, '', 'DELETE /product-api/admin/products', '2025-05-16 20:15:08', 0),
(38, '', 'POST /product-api/login', '2025-05-20 23:20:48', 12),
(39, '', 'GET /product-api/cocktail_category', '2025-05-20 23:22:10', 0),
(40, '', 'GET /product-api/recipes/product/P00002', '2025-05-20 23:26:47', 0),
(41, '', 'GET /product-api/recipes/product/P00002', '2025-05-20 23:28:17', 0),
(42, '', 'GET /product-api/recipes/product/P00003', '2025-05-20 23:31:35', 0),
(43, '', 'GET /product-api/recipes/product/P00003', '2025-05-20 23:37:57', 0),
(44, '', 'GET /product-api/recipes/product/P00003', '2025-05-20 23:40:30', 0),
(45, '', 'GET /product-api/products/P00002/nutrition', '2025-05-20 23:44:45', 0),
(46, '', 'GET /product-api/fruit_information/papaya', '2025-05-20 23:49:37', 0),
(47, '', 'GET /product-api/fruit_information/papaya', '2025-05-20 23:50:29', 0),
(48, '', 'POST /product-api/bmi', '2025-05-20 23:51:51', 0),
(49, '', 'POST /product-api/bmi', '2025-05-20 23:52:32', 0),
(50, '', 'POST /product-api/bmi', '2025-05-20 23:52:38', 0),
(51, '', 'POST /product-api/bmi', '2025-05-20 23:52:51', 0),
(52, '', 'POST /product-api/calorie', '2025-05-20 23:53:17', 0),
(53, '', 'POST /product-api/calorie', '2025-05-20 23:53:29', 0),
(54, '', 'POST /product-api/calorie', '2025-05-20 23:53:41', 0),
(55, '', 'POST /product-api/fiber', '2025-05-20 23:54:05', 0),
(56, '', 'POST /product-api/fiber', '2025-05-20 23:54:18', 0),
(57, '', 'POST /product-api/fiber', '2025-05-20 23:54:25', 0),
(58, '', 'POST /product-api/fiber', '2025-05-20 23:54:32', 0),
(59, '', 'POST /product-api/login', '2025-05-21 04:15:08', 0),
(60, '', 'POST /product-api/fiber', '2025-05-21 04:15:46', 0),
(61, '', 'POST /product-api/register', '2025-05-22 01:22:10', 0),
(62, '', 'POST /product-api/register', '2025-05-22 01:22:45', 0),
(63, '', 'POST /product-api/login', '2025-05-22 01:24:58', 0),
(64, '', 'GET /product-api/products', '2025-05-22 01:25:43', 0),
(65, '', 'GET /product-api/products', '2025-05-22 01:26:11', 0),
(66, '', 'GET /product-api/products', '2025-05-22 01:28:43', 0),
(67, '', 'GET /product-api/products', '2025-05-22 01:28:49', 0),
(68, '', 'GET /product-api/products', '2025-05-22 01:28:55', 0),
(69, '', 'GET /product-api/products', '2025-05-22 01:29:00', 0),
(70, '', 'GET /product-api/products', '2025-05-22 01:29:00', 0),
(71, '', 'GET /product-api/products', '2025-05-22 01:29:00', 0),
(72, '', 'GET /product-api/products', '2025-05-22 01:29:16', 0),
(73, '', 'GET /product-api/products', '2025-05-22 01:29:51', 0),
(74, '', 'GET /product-api/products', '2025-05-22 01:29:59', 0),
(75, '', 'GET /product-api/products', '2025-05-22 01:29:59', 0),
(76, '', 'GET /product-api/products', '2025-05-22 01:30:00', 0),
(77, '', 'GET /product-api/products', '2025-05-22 01:30:00', 0),
(78, '', 'GET /product-api/products', '2025-05-22 01:30:00', 0),
(79, '', 'GET /product-api/products', '2025-05-22 01:30:00', 0),
(80, '', 'GET /product-api/products', '2025-05-22 01:30:00', 0),
(81, '', 'GET /product-api/products', '2025-05-22 01:30:00', 0),
(82, '', 'GET /product-api/products', '2025-05-22 01:30:00', 0),
(83, '', 'GET /product-api/products', '2025-05-22 01:30:08', 0),
(84, '', 'GET /product-api/products', '2025-05-22 01:30:08', 0),
(85, '', 'GET /product-api/products', '2025-05-22 01:30:08', 0),
(86, '', 'GET /product-api/products', '2025-05-22 01:30:09', 0),
(87, '', 'GET /product-api/products', '2025-05-22 01:36:44', 0),
(88, '', 'GET /product-api/products', '2025-05-22 01:42:01', 0),
(89, '', 'GET /product-api/products', '2025-05-22 01:43:38', 0),
(90, '', 'GET /product-api/products', '2025-05-22 01:43:45', 0),
(91, '', 'GET /product-api/products', '2025-05-22 01:43:50', 0),
(92, '', 'GET /product-api/products', '2025-05-22 01:43:50', 0),
(93, '', 'GET /product-api/products', '2025-05-22 01:43:51', 0),
(94, '', 'GET /product-api/products', '2025-05-22 01:43:51', 0),
(95, '', 'GET /product-api/products', '2025-05-22 01:44:15', 0),
(96, '', 'GET /product-api/products', '2025-05-22 01:44:16', 0),
(97, '', 'GET /product-api/products', '2025-05-22 01:44:16', 0),
(98, '', 'GET /product-api/products', '2025-05-22 01:44:16', 0),
(99, '', 'GET /product-api/products', '2025-05-22 01:48:41', 0),
(100, '', 'GET /product-api/products', '2025-05-22 01:48:48', 0),
(101, '', 'GET /product-api/products', '2025-05-22 01:48:50', 0),
(102, '', 'GET /product-api/products', '2025-05-22 01:48:51', 0),
(103, '', 'GET /product-api/products', '2025-05-22 01:48:51', 0),
(104, '', 'GET /product-api/products', '2025-05-22 01:48:55', 0),
(105, '', 'GET /product-api/products', '2025-05-22 01:48:56', 0),
(106, '', 'GET /product-api/products', '2025-05-22 01:48:56', 0),
(107, '', 'GET /product-api/products', '2025-05-22 01:48:56', 0),
(108, '', 'GET /product-api/products', '2025-05-22 01:49:01', 0),
(109, '', 'GET /product-api/products', '2025-05-22 01:49:02', 0),
(110, '', 'GET /product-api/products', '2025-05-22 01:49:09', 0),
(111, '', 'GET /product-api/products', '2025-05-22 01:49:09', 0),
(112, '', 'GET /product-api/products', '2025-05-22 01:49:09', 0),
(113, '', 'GET /product-api/products', '2025-05-22 01:49:17', 0),
(114, '', 'GET /product-api/products', '2025-05-22 01:50:45', 0),
(115, '', 'GET /product-api/products', '2025-05-22 01:50:52', 0),
(116, '', 'GET /product-api/products', '2025-05-22 01:51:02', 0),
(117, '', 'GET /product-api/products', '2025-05-22 02:00:52', 0),
(118, '', 'GET /product-api/products/P00001', '2025-05-22 02:16:25', 0),
(119, '', 'GET /product-api/products/P00001', '2025-05-22 02:16:38', 0),
(120, '', 'GET /product-api/products/P00002', '2025-05-22 02:16:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ws_users`
--

CREATE TABLE `ws_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ws_users`
--

INSERT INTO `ws_users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `isAdmin`, `created_at`) VALUES
(1, 'Jane', 'Doe', 'jane.doe@google.com', '$2y$12$6wosbIwygmwkyDpOXJY4YOGmZo0/GGZQJx/YrReZI74ivVcM3/Mjy', 0, '2025-05-09 15:04:19'),
(2, 'JannaNotAdmin', 'Lomibao', 'jannaNotAdmin@example.com', '$2y$12$sdeFF2.hyjTh0F84UE/Y7OpjNpa8wDk9eJJ2LSyoWG7CUgKHAIlri', 0, '2025-05-10 00:01:42'),
(3, 'Regular', 'User', 'reguser@google.com', '$2y$12$YBaFrsU5CTnc8ceQp3WmHeVlEt/uUIvyUlIeswK7CDUQOPayHpBDy', 0, '2025-05-10 23:48:39'),
(4, 'Admin', 'User', 'adminuser@google.com', '$2y$12$zGNZ9c43hjKTEOQGdZI1DOblPke9g.xV0b0AiXpaA.zF6KdJX/vsi', 1, '2025-05-10 23:49:19'),
(5, 'Bri', 'Cen', 'p', '$2y$12$R5zEY74cWPHDNNTJEsNN8e9PJHbQ1soShuLLa425T7sX4Ow8ArNre', 1, '2025-05-20 22:37:46'),
(6, 'Bridjette', 'Centro', 'bridjette@example.com', '$2y$12$IRUs9weHZRj7uv2Ejk1JTuD2l26y6bBAs.JGpZygbKGVy57SEIXfe', 1, '2025-05-13 11:07:05'),
(7, 'Quack First', 'Quack Last', 'test@google.com', '$2y$12$NL6aijqrTgmwwuu8ZQzfkeXOVUssAeXC/5QHEmbZ2FzAAWYJIYVOm', 1, '2025-05-15 16:28:03'),
(8, '1Quack First', '2Quack Last', 'quack@google.com', '$2y$12$ZNi4fmJALz0wTl7iVU4L.ulAYejmxP.BJuxAiNwJ3sJ2w.QcjOKGW', 1, '2025-05-15 16:28:51'),
(9, '1Quack First', '2Quack Last', 'quack2@google.com', '$2y$12$eypBawigl7WKCJeTvAR3UeU3Omudbo8QXorkdpghfmJzABJzAxc1i', 1, '2025-05-15 16:30:12'),
(10, 'Bridjette', 'Centro', 'bridjette_centro@yahoo.com', '$2y$12$Fi/3fGDiHEKMzdzpmmoYjuAbeYc5Efoq4x7/0l0KHaRFwDRlbZE/K', 1, '2025-05-15 17:15:32'),
(11, 'Bridjette', 'Centro', 'centro@yahoo.com', '$2y$12$qMjvpt0xm/Gb5lWEM.uixOS1pnQOSA/ShPzhmyezk/fCsXycyO.3O', 1, '2025-05-15 17:15:46'),
(12, 'Test', 'User', 'bc@google.com', '$2y$12$W453tuViBwapZT3QvQ0N8u0LMQvP9WrDdPtBdqgh.QJU7buljmIni', 1, '2025-05-20 22:35:51'),
(13, 'General', 'User', 'general-user@google.com', '$2y$12$LvpGRcGZ/0m1ORd03/wOdujcem6sFbqEgTRbRoTChqz75qizjvM2a', 0, '2025-05-22 01:22:10'),
(14, 'Admin', 'User', 'admin-user@google.com', '$2y$12$4ExX5LmEztGVLw8PEqYO0uyjPbjIJH1k/WeLKvC73ZSPhTqcsk5sW', 1, '2025-05-22 01:22:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allergens`
--
ALTER TABLE `allergens`
  ADD PRIMARY KEY (`key_id`),
  ADD UNIQUE KEY `allergen_id` (`allergen_id`) USING BTREE;

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`key_id`),
  ADD KEY `brand_id` (`brand_id`) USING BTREE;

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`key_id`),
  ADD KEY `parent_category_id` (`parent_category_id`),
  ADD KEY `category_id` (`category_id`) USING BTREE;

--
-- Indexes for table `dietary_attributes`
--
ALTER TABLE `dietary_attributes`
  ADD PRIMARY KEY (`key_id`),
  ADD KEY `diet_id` (`diet_id`) USING BTREE;

--
-- Indexes for table `environmentals`
--
ALTER TABLE `environmentals`
  ADD PRIMARY KEY (`key_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `environmental_id` (`environmental_id`) USING BTREE;

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`key_id`),
  ADD KEY `allergen_id` (`allergen_id`,`category_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `ingredient_id` (`ingredient_id`) USING BTREE;

--
-- Indexes for table `nutritions`
--
ALTER TABLE `nutritions`
  ADD PRIMARY KEY (`key_id`),
  ADD KEY `nutritional_id` (`nutritional_id`) USING BTREE;

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`key_id`),
  ADD UNIQUE KEY `product_id_2` (`product_id`),
  ADD KEY `nutrition_id` (`nutrition_id`),
  ADD KEY `diet_id` (`diet_id`,`brand_id`,`category_id`,`environmental_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `environmental_id` (`environmental_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`) USING BTREE;

--
-- Indexes for table `product_allergen`
--
ALTER TABLE `product_allergen`
  ADD PRIMARY KEY (`pa_id`),
  ADD KEY `product_id` (`product_id`,`allergen_id`),
  ADD KEY `allergen_id` (`allergen_id`);

--
-- Indexes for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD PRIMARY KEY (`pi_id`),
  ADD KEY `product_id` (`product_id`,`ingredient_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Indexes for table `ws_log`
--
ALTER TABLE `ws_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `ws_users`
--
ALTER TABLE `ws_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allergens`
--
ALTER TABLE `allergens`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dietary_attributes`
--
ALTER TABLE `dietary_attributes`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `environmentals`
--
ALTER TABLE `environmentals`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `nutritions`
--
ALTER TABLE `nutritions`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `ws_log`
--
ALTER TABLE `ws_log`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `ws_users`
--
ALTER TABLE `ws_users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`allergen_id`) REFERENCES `allergens` (`allergen_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `ingredients_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`diet_id`) REFERENCES `dietary_attributes` (`diet_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`nutrition_id`) REFERENCES `nutritions` (`nutritional_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `products_ibfk_4` FOREIGN KEY (`environmental_id`) REFERENCES `environmentals` (`environmental_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `products_ibfk_5` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `product_allergen`
--
ALTER TABLE `product_allergen`
  ADD CONSTRAINT `product_allergen_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_allergen_ibfk_2` FOREIGN KEY (`allergen_id`) REFERENCES `allergens` (`allergen_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD CONSTRAINT `product_ingredients_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
