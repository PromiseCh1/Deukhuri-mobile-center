-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: deukhuri_shop
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Mobiles'),(2,'Parts'),(4,'Topups'),(3,'Vapes');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_attribute_values`
--

DROP TABLE IF EXISTS `product_attribute_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_attribute_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `attribute_id` (`attribute_id`),
  CONSTRAINT `product_attribute_values_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_attribute_values_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `product_attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_attribute_values`
--

LOCK TABLES `product_attribute_values` WRITE;
/*!40000 ALTER TABLE `product_attribute_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_attribute_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `attribute_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_attributes`
--

LOCK TABLES `product_attributes` WRITE;
/*!40000 ALTER TABLE `product_attributes` DISABLE KEYS */;
INSERT INTO `product_attributes` VALUES (1,1,'Brand'),(2,1,'RAM'),(3,1,'Storage'),(4,1,'Condition'),(5,1,'Battery'),(6,2,'Use Case'),(7,2,'Compatibility'),(8,3,'Flavor'),(9,3,'Puffs'),(10,3,'Rechargeable'),(11,4,'Game'),(12,4,'Amount');
/*!40000 ALTER TABLE `product_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'assets/images/topup/ff_topup.png'),(2,2,'assets/images/topup/ff_topup.png'),(3,3,'assets/images/topup/ff_topup.png'),(4,4,'assets/images/topup/ff_topup.png'),(5,5,'assets/images/topup/ff_topup.png'),(6,6,'assets/images/topup/ff_topup.png'),(7,7,'assets/images/topup/ff_topup.png'),(8,8,'assets/images/topup/pubg_topup.png'),(9,9,'assets/images/topup/pubg_topup.png'),(10,10,'assets/images/topup/pubg_topup.png'),(11,11,'assets/images/topup/pubg_topup.png');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,4,'FreeFire 115 Diamonds','Instant FreeFire diamond topup',120.00,999,'2026-05-12 04:11:40'),(2,4,'FreeFire 240 Diamonds','Instant FreeFire diamond topup',230.00,999,'2026-05-12 04:11:40'),(3,4,'FreeFire 610 Diamonds','Instant FreeFire diamond topup',570.00,999,'2026-05-12 04:11:40'),(4,4,'FreeFire 1240 Diamonds','Instant FreeFire diamond topup',1100.00,999,'2026-05-12 04:11:40'),(5,4,'FreeFire 2530 Diamonds','Instant FreeFire diamond topup',2100.00,999,'2026-05-12 04:11:40'),(6,4,'FreeFire Weekly Membership','FreeFire weekly membership topup',250.00,999,'2026-05-12 04:11:40'),(7,4,'FreeFire Monthly Membership','FreeFire monthly membership topup',1100.00,999,'2026-05-12 04:11:40'),(8,4,'PUBG 60 UC','PUBG UC topup',160.00,999,'2026-05-12 04:11:40'),(9,4,'PUBG 325 UC','PUBG UC topup',850.00,999,'2026-05-12 04:11:40'),(10,4,'PUBG 660 UC','PUBG UC topup',1600.00,999,'2026-05-12 04:11:40'),(11,4,'PUBG 1800 UC','PUBG UC topup',3800.00,999,'2026-05-12 04:11:40');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-03 19:38:59
