-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 01:36 AM
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
CREATE DATABASE IF NOT EXISTS `food-api` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `food-api`;

-- --------------------------------------------------------

--
-- Table structure for table `allergens`
--

CREATE TABLE `allergens` (
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

INSERT INTO `allergens` (`allergen_id`, `allergen_name`, `allergen_reaction_type`, `food_group`, `food_type`, `food_origin`, `food_item`) VALUES
('A01', 'Soy Allergy', 'Hives, anaphylaxis', 'Pulse', 'Cereal grain and pulse', 'Plant origin', 'Soybean'),
('A02', 'Peanut Allergy', 'Hives, Abdominal pain, Anaphylaxis', 'Pulse', 'Cereal grain and pulse', 'Plant origin', 'Peanut'),
('A03', 'Poultry Allergy', 'Hives, anaphylaxis', 'Egg', 'Poultry', 'Animal origin', 'Eggs'),
('A04', 'Milk Allergy', 'Hives, Gastrointestinal distress, anaphylaxis', 'Dairy', 'Dairy', 'Animal origin', 'Cow Milk'),
('A05', 'Gluten Allergy', 'Bloating, diarrhea, abdominal pain', 'Cereal grain', 'Cereal grain and pulse', 'Plant origin', 'Oats');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
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

INSERT INTO `brands` (`brand_id`, `brand_name`, `brand_country`, `brand_image`, `brand_website`, `isBrandSustainable`) VALUES
('B0001', 'Mateina', 'Canada', 'https://mateina.ca/cdn/shop/files/LOGO_MATEINA_V2.png', 'https://mateina.ca', 1),
('B0002', 'Oatly', 'Sweden', 'https://a.storyblok.com/f/107921/1920x1080/db39428076/oatlyxnespresso-shareimage.png', 'https://www.oatly.com', 1),
('B0003', 'Natrel', 'Canada', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTlnTuE4hulgSl95DOaV0rolagPTquqeeZccKPJW9V7wnWh', 'https://www.natrel.ca', 1),
('B0004', 'Hershey’s', 'USA', 'https://www.hersheyland.com', 'www.hersheyland.com', 0),
('B0005', 'Cadbury', 'United Kingdom', 'https://www.cadbury.co.uk', 'https://www.cadbury.co.uk', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` char(6) NOT NULL,
  `category_name` varchar(120) NOT NULL,
  `category_description` varchar(400) NOT NULL,
  `parent_category_id` char(6) DEFAULT NULL,
  `category_type` varchar(100) NOT NULL,
  `category_level` varchar(11) NOT NULL,
  `category_tags` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_description`, `parent_category_id`, `category_type`, `category_level`, `category_tags`) VALUES
('C-0001', 'Plant-Based Beverage', 'Made from ingredients derived from plants without any animal products', NULL, 'Beverage', 'parent', 'Vegan, drink, plant'),
('C-0002', 'Animal-Based Beverage', 'Made from animal-derived ingredients', NULL, 'Beverage', 'parent', 'Animal, drink'),
('C-0003', 'Confectionery', 'Sweet and sugary food', NULL, 'Food', 'parent', 'Sweet, dessert, snacks'),
('C-0004', 'Energy Drink', 'Beverages formulated to enhance physical energy and mental alertness.', 'C-0001', 'Beverage', 'child', 'Caffeine, drink, energy'),
('C-0005', 'Milk', 'Milk product', 'C-0002', 'Beverage', 'child', 'Milk, plant-based, vegan, dairy, animal-derived'),
('C-0006', 'Chocolate', 'Encompasses all chocolate-based products, including bars, candies, truffles, and baking chocolate', 'C-0003', 'Snack', 'child', 'Snack, chocolate, sweet');

-- --------------------------------------------------------

--
-- Table structure for table `dietary_attributes`
--

CREATE TABLE `dietary_attributes` (
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

INSERT INTO `dietary_attributes` (`diet_id`, `dairy_free`, `gluten_free`, `soy_free`, `nut_free`, `vegan`) VALUES
('DA0001', 1, 1, 1, 1, 1),
('DA0002', 1, 0, 1, 1, 1),
('DA0003', 0, 1, 1, 1, 0),
('DA0004', 0, 1, 0, 0, 0),
('DA0005', 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `environmental`
--

CREATE TABLE `environmental` (
  `environmental_id` char(6) NOT NULL,
  `product_id` char(6) NOT NULL,
  `packaging_name` varchar(100) DEFAULT NULL,
  `eco_score` int(11) DEFAULT NULL,
  `transportation_score` int(11) DEFAULT NULL,
  `fromPalmOil` tinyint(1) DEFAULT NULL,
  `recycling_info` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `environmental`
--

INSERT INTO `environmental` (`environmental_id`, `product_id`, `packaging_name`, `eco_score`, `transportation_score`, `fromPalmOil`, `recycling_info`) VALUES
('E00001', 'P00001', 'Aluminum Can', NULL, NULL, 0, 'Recyclable'),
('E00002', 'P00002', 'Carton', 80, 75, 0, 'Recyclable'),
('E00003', 'P00003', 'Carton', 75, 70, 0, 'Recyclable'),
('E00004', 'P00004', 'Wrapper', NULL, NULL, 0, 'Not Specified'),
('E00005', 'P00005', 'Foil Wrapper', NULL, NULL, 0, 'Recyclable');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
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

INSERT INTO `ingredients` (`ingredient_id`, `allergen_id`, `category_id`, `ingredient_name`, `processing_type`, `ingredient_description`, `isGMO`) VALUES
('I01', NULL, 'C-0002', 'Organic yerba mate', 'Brewed', 'Infused organic yerba mate with organic peach juice and natural flavors', 0),
('I02', 'A05', 'C-0003', 'Oat base', 'UHT', 'A blend of water, oats, and a small amount of added vitamins', 0),
('I03', 'A04', 'C-0005', 'Partly skimmed milk', 'Fine-filtered', 'Partly skimmed milk, fortified with vitamin A palmitate and vitamin D3', 0),
('I04', 'A04', 'C-0006', 'Milk Chocolate, Peanuts, Sugar, Dextrose, Salt, TBHQ, Citric Acid', 'Mixed and Molded', 'Mixture of milk chocolate, peanuts, and other ingredients molded into cup shapes', 0),
('I05', 'A04', 'C-0006', 'Milk chocolate', 'Mixed and Molded', 'Fat derived from cocoa beans that gives chocolate its melt-in-your-mouth texture.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nutrition`
--

CREATE TABLE `nutrition` (
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
-- Dumping data for table `nutrition`
--

INSERT INTO `nutrition` (`nutritional_id`, `energy`, `fat`, `saturated_fat`, `protein`, `carbs`, `sodium`, `sugar`, `nova_group`) VALUES
('N00001', 45, 0, 0, 0.3, 10, 10, 8, 4),
('N00002', 60, 3, 0.3, 1, 6.5, 10, 4, 4),
('N00003', 130, 5, 3, 9, 13, 105, 13, 4),
('N00004', 210, 12, 4.5, 4, 24, 135, 22, 4),
('N00005', 240, 14, 8, 3, 25, 0.06, 24, 4);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
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
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_barcode`, `product_origin`, `product_serving_size`, `product_image`, `nutrition_id`, `diet_id`, `brand_id`, `category_id`, `environmental_id`) VALUES
('P00001', 'Peach Passion Energy Infusion', '2147483647', 'Canada', 355, 'https://mateina.ca/cdn/shop/files/Mateina-Organic-Yerba-Mate-Drink-Canada-Peach-Passion-Boisson-Mate-Peche-Passion-Bio--Trimmed_720x.png', 'N00001', 'DA0001', 'B0001', 'C-0004', 'E00001'),
('P00002', 'Oat Drink Barista Edition', '2147483647', 'Sweden', 240, 'https://m.media-amazon.com/images/I/51eulMv-7HL.jpg', 'N00002', 'DA0002', 'B0002', 'C-0005', 'E00002'),
('P00003', 'Fine-Filtered 2% Milk', NULL, 'Canada', 250, 'https://www.natrel.ca/sites/default/files/styles/product_image_large/public/2025-01/Natrel-Fine-Filtered-Milk-2%25-2L_0.png', 'N00003', 'DA0003', 'B0003', 'C-0005', 'E00003'),
('P00004', 'Reese\'s Milk Chocolate Peanut Butter Cups', '2147483647', 'USA', 42, NULL, 'N00004', 'DA0004', 'B0004', 'C-0006', 'E00004'),
('P00005', 'Cadbury Dairy Milk Chocolate', '2147483647', 'United Kingdom', 45, NULL, 'N00005', 'DA0005', 'B0005', 'C-0006', 'E00005');

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allergens`
--
ALTER TABLE `allergens`
  ADD PRIMARY KEY (`allergen_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `parent_category_id` (`parent_category_id`);

--
-- Indexes for table `dietary_attributes`
--
ALTER TABLE `dietary_attributes`
  ADD PRIMARY KEY (`diet_id`);

--
-- Indexes for table `environmental`
--
ALTER TABLE `environmental`
  ADD PRIMARY KEY (`environmental_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD KEY `allergen_id` (`allergen_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `nutrition`
--
ALTER TABLE `nutrition`
  ADD PRIMARY KEY (`nutritional_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `nutrition_id` (`nutrition_id`),
  ADD KEY `diet_id` (`diet_id`,`brand_id`,`category_id`,`environmental_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `environmental_id` (`environmental_id`),
  ADD KEY `category_id` (`category_id`);

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
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `category` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`allergen_id`) REFERENCES `allergens` (`allergen_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `ingredients_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`diet_id`) REFERENCES `dietary_attributes` (`diet_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`nutrition_id`) REFERENCES `nutrition` (`nutritional_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_ibfk_4` FOREIGN KEY (`environmental_id`) REFERENCES `environmental` (`environmental_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_ibfk_5` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Constraints for table `product_allergen`
--
ALTER TABLE `product_allergen`
  ADD CONSTRAINT `product_allergen_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_allergen_ibfk_2` FOREIGN KEY (`allergen_id`) REFERENCES `allergens` (`allergen_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `product_ingredients`
--
ALTER TABLE `product_ingredients`
  ADD CONSTRAINT `product_ingredients_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
