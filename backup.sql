-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: online_nursery
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
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Indoor','2025-05-02 10:44:27'),(2,'Outdoor','2025-05-02 10:44:27'),(3,'Flowering','2025-05-02 10:44:27');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inquiries`
--

DROP TABLE IF EXISTS `inquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `response` text DEFAULT NULL,
  `responded_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inquiries`
--

LOCK TABLES `inquiries` WRITE;
/*!40000 ALTER TABLE `inquiries` DISABLE KEYS */;
INSERT INTO `inquiries` VALUES (1,'Nabin Neupane','nabinneupane@gmail.com','how can i buy plants','i liked it very much','2025-05-03 19:13:04','wertyuiytrewrty','2025-05-09 07:03:33'),(6,'asna','asna@gmail.com','how can i buy plants','hi are you there?','2025-05-07 08:55:48','yes','2025-05-08 15:19:41'),(7,'Arjun','arjun123@gmail.com','plant','nice to meet you','2025-05-11 08:36:28',NULL,NULL),(8,'Arjun','arjun@gmail.com','k xa ho halli challi','la hai reply dim','2025-05-17 22:03:00',NULL,NULL),(9,'Arjun','arjun@gmail.com','k xa ho halli challi','la hai reply dim','2025-05-17 22:04:14',NULL,NULL),(10,'Nabin Neupane','nabinneupane@gmail.com','plant','k k plant haru xan','2025-05-17 22:06:18',NULL,NULL),(11,'Chitra','chitra@gmail.com','malai kei biruwa chaiyeko xa','biruwa haru ko list haru','2025-05-17 22:07:37',NULL,NULL),(12,'Asmit','asmit@gmail.com','i loved your concept','sdfghjklokok','2025-05-17 22:08:58',NULL,NULL),(13,'Chitra','chitra@gmail.com','how can i buy plants','la hai la hai la hai la hai','2025-05-17 22:14:19',NULL,NULL),(14,'Hemant Rawal','hemant@gmail.com','what are the plants available ?','kk xa vanum tw xitto','2025-05-17 22:23:26',NULL,NULL),(15,'Nabin Neupane','nabinneupane@gmail.com','plant','sdfufebidhowjpksa[lc,;','2025-05-17 22:26:26',NULL,NULL),(16,'jiten','jiten12@gmail.com','how can i buy plants','cghwei0ovbiyrwuvkn','2025-05-17 22:30:21',NULL,NULL);
/*!40000 ALTER TABLE `inquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_html` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,75,2,'INV-000075','\r\n    <div class=\"invoice\" style=\"background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;\">\r\n        <h2 style=\"color: #28a745; text-align: center;\">Invoice #INV-000075</h2>\r\n        <p style=\"text-align: center; color: #666;\">Online Nursery System</p>\r\n        <p style=\"text-align: center; color: #666;\">Date: 2025-05-17</p>\r\n        <div style=\"margin: 1em 0;\">\r\n            <h3 style=\"color: #28a745;\">Customer Details</h3>\r\n            <p><strong>Name:</strong> Nabin Neupane</p>\r\n            <p><strong>Email:</strong> nabinneupane@gmail.com</p>\r\n            <p><strong>Delivery Address:</strong> hiiiii</p>\r\n        </div>\r\n        <h3 style=\"color: #28a745;\">Order Details</h3>\r\n        <table style=\"width: 100%; border-collapse: collapse; margin-bottom: 1em;\">\r\n            <thead>\r\n                <tr style=\"background: #f2f2f2;\">\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Product</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Quantity</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Price (Rs.)</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Subtotal (Rs.)</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Sunflower</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">1</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n        <div style=\"text-align: right; margin-bottom: 1em;\">\r\n            <p><strong>Subtotal:</strong> Rs. 100.00</p>\r\n            <p><strong>Shipping:</strong> Rs. 50.00</p>\r\n            <p><strong>Total:</strong> Rs. 150.00</p>\r\n        </div>\r\n    </div>','2025-05-17 14:58:16'),(2,76,50,'INV-000076','\r\n    <div class=\"invoice\" style=\"background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;\">\r\n        <h2 style=\"color: #28a745; text-align: center;\">Invoice #INV-000076</h2>\r\n        <p style=\"text-align: center; color: #666;\">Online Nursery System</p>\r\n        <p style=\"text-align: center; color: #666;\">Date: 2025-05-17</p>\r\n        <div style=\"margin: 1em 0;\">\r\n            <h3 style=\"color: #28a745;\">Customer Details</h3>\r\n            <p><strong>Name:</strong> kshitiz</p>\r\n            <p><strong>Email:</strong> kshitiz123@gmail.com</p>\r\n            <p><strong>Delivery Address:</strong> chowk tira</p>\r\n        </div>\r\n        <h3 style=\"color: #28a745;\">Order Details</h3>\r\n        <table style=\"width: 100%; border-collapse: collapse; margin-bottom: 1em;\">\r\n            <thead>\r\n                <tr style=\"background: #f2f2f2;\">\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Product</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Quantity</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Price (Rs.)</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Subtotal (Rs.)</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Rose Plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">1</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">150.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">150.00</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n        <div style=\"text-align: right; margin-bottom: 1em;\">\r\n            <p><strong>Subtotal:</strong> Rs. 150.00</p>\r\n            <p><strong>Shipping:</strong> Rs. 50.00</p>\r\n            <p><strong>Total:</strong> Rs. 200.00</p>\r\n        </div>\r\n    </div>','2025-05-17 15:05:11'),(3,77,50,'INV-000077','\r\n    <div class=\"invoice\" style=\"background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;\">\r\n        <h2 style=\"color: #28a745; text-align: center;\">Invoice #INV-000077</h2>\r\n        <p style=\"text-align: center; color: #666;\">Online Nursery System</p>\r\n        <p style=\"text-align: center; color: #666;\">Date: 2025-05-17</p>\r\n        <div style=\"margin: 1em 0;\">\r\n            <h3 style=\"color: #28a745;\">Customer Details</h3>\r\n            <p><strong>Name:</strong> kshitiz</p>\r\n            <p><strong>Email:</strong> kshitiz123@gmail.com</p>\r\n            <p><strong>Delivery Address:</strong> kata kata</p>\r\n            <p><strong>Order Status:</strong> </p>\r\n        </div>\r\n        <h3 style=\"color: #28a745;\">Order Details</h3>\r\n        <table style=\"width: 100%; border-collapse: collapse; margin-bottom: 1em;\">\r\n            <thead>\r\n                <tr style=\"background: #f2f2f2;\">\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Product</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Quantity</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Price (Rs.)</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Subtotal (Rs.)</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Spider Plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">4</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">450.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">1,800.00</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Sunflower</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">5</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">500.00</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">rose plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">3</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">300.00</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n        <div style=\"text-align: right; margin-bottom: 1em;\">\r\n            <p><strong>Subtotal:</strong> Rs. 2,600.00</p>\r\n            <p><strong>Shipping:</strong> Rs. 50.00</p>\r\n            <p><strong>Total:</strong> Rs. 2,650.00</p>\r\n        </div>\r\n    </div>','2025-05-17 16:49:52'),(4,77,50,'INV-000077','\r\n    <div class=\"invoice\" style=\"background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;\">\r\n        <h2 style=\"color: #28a745; text-align: center;\">Invoice #INV-000077</h2>\r\n        <p style=\"text-align: center; color: #666;\">Online Nursery System</p>\r\n        <p style=\"text-align: center; color: #666;\">Date: 2025-05-17</p>\r\n        <div style=\"margin: 1em 0;\">\r\n            <h3 style=\"color: #28a745;\">Customer Details</h3>\r\n            <p><strong>Name:</strong> kshitiz</p>\r\n            <p><strong>Email:</strong> kshitiz123@gmail.com</p>\r\n            <p><strong>Delivery Address:</strong> kata kata</p>\r\n            <p><strong>Order Status:</strong> </p>\r\n        </div>\r\n        <h3 style=\"color: #28a745;\">Order Details</h3>\r\n        <table style=\"width: 100%; border-collapse: collapse; margin-bottom: 1em;\">\r\n            <thead>\r\n                <tr style=\"background: #f2f2f2;\">\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Product</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Quantity</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Price (Rs.)</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Subtotal (Rs.)</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Spider Plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">4</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">450.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">1,800.00</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Sunflower</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">5</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">500.00</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">rose plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">3</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">300.00</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n        <div style=\"text-align: right; margin-bottom: 1em;\">\r\n            <p><strong>Subtotal:</strong> Rs. 2,600.00</p>\r\n            <p><strong>Shipping:</strong> Rs. 50.00</p>\r\n            <p><strong>Total:</strong> Rs. 2,650.00</p>\r\n        </div>\r\n    </div>','2025-05-17 16:50:53'),(5,77,50,'INV-000077','\r\n    <div class=\"invoice\" style=\"background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;\">\r\n        <h2 style=\"color: #28a745; text-align: center;\">Invoice #INV-000077</h2>\r\n        <p style=\"text-align: center; color: #666;\">Online Nursery System</p>\r\n        <p style=\"text-align: center; color: #666;\">Date: 2025-05-17</p>\r\n        <div style=\"margin: 1em 0;\">\r\n            <h3 style=\"color: #28a745;\">Customer Details</h3>\r\n            <p><strong>Name:</strong> kshitiz</p>\r\n            <p><strong>Email:</strong> kshitiz123@gmail.com</p>\r\n            <p><strong>Delivery Address:</strong> kata kata</p>\r\n            <p><strong>Order Status:</strong> </p>\r\n        </div>\r\n        <h3 style=\"color: #28a745;\">Order Details</h3>\r\n        <table style=\"width: 100%; border-collapse: collapse; margin-bottom: 1em;\">\r\n            <thead>\r\n                <tr style=\"background: #f2f2f2;\">\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Product</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Quantity</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Price (Rs.)</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Subtotal (Rs.)</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Spider Plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">4</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">450.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">1,800.00</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Sunflower</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">5</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">500.00</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">rose plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">3</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">300.00</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n        <div style=\"text-align: right; margin-bottom: 1em;\">\r\n            <p><strong>Subtotal:</strong> Rs. 2,600.00</p>\r\n            <p><strong>Shipping:</strong> Rs. 50.00</p>\r\n            <p><strong>Total:</strong> Rs. 2,650.00</p>\r\n        </div>\r\n    </div>','2025-05-17 16:51:13'),(6,77,50,'INV-000077','\r\n    <div class=\"invoice\" style=\"background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;\">\r\n        <h2 style=\"color: #28a745; text-align: center;\">Invoice #INV-000077</h2>\r\n        <p style=\"text-align: center; color: #666;\">Online Nursery System</p>\r\n        <p style=\"text-align: center; color: #666;\">Date: 2025-05-17</p>\r\n        <div style=\"margin: 1em 0;\">\r\n            <h3 style=\"color: #28a745;\">Customer Details</h3>\r\n            <p><strong>Name:</strong> kshitiz</p>\r\n            <p><strong>Email:</strong> kshitiz123@gmail.com</p>\r\n            <p><strong>Delivery Address:</strong> kata kata</p>\r\n            <p><strong>Order Status:</strong> </p>\r\n        </div>\r\n        <h3 style=\"color: #28a745;\">Order Details</h3>\r\n        <table style=\"width: 100%; border-collapse: collapse; margin-bottom: 1em;\">\r\n            <thead>\r\n                <tr style=\"background: #f2f2f2;\">\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Product</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Quantity</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Price (Rs.)</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Subtotal (Rs.)</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Spider Plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">4</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">450.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">1,800.00</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Sunflower</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">5</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">500.00</td>\r\n                </tr>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">rose plant</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">3</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">300.00</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n        <div style=\"text-align: right; margin-bottom: 1em;\">\r\n            <p><strong>Subtotal:</strong> Rs. 2,600.00</p>\r\n            <p><strong>Shipping:</strong> Rs. 50.00</p>\r\n            <p><strong>Total:</strong> Rs. 2,650.00</p>\r\n        </div>\r\n    </div>','2025-05-17 16:51:15'),(7,79,50,'INV-000079','\r\n    <div class=\"invoice\" style=\"background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;\">\r\n        <h2 style=\"color: #28a745; text-align: center;\">Invoice #INV-000079</h2>\r\n        <p style=\"text-align: center; color: #666;\">Online Nursery System</p>\r\n        <p style=\"text-align: center; color: #666;\">Date: 2025-05-18</p>\r\n        <div style=\"margin: 1em 0;\">\r\n            <h3 style=\"color: #28a745;\">Customer Details</h3>\r\n            <p><strong>Name:</strong> kshitiz</p>\r\n            <p><strong>Email:</strong> kshitiz123@gmail.com</p>\r\n            <p><strong>Delivery Address:</strong> retry5ut6iyuoiytrwearstdfyjgkuhijl;</p>\r\n            <p><strong>Order Status:</strong> </p>\r\n        </div>\r\n        <h3 style=\"color: #28a745;\">Order Details</h3>\r\n        <table style=\"width: 100%; border-collapse: collapse; margin-bottom: 1em;\">\r\n            <thead>\r\n                <tr style=\"background: #f2f2f2;\">\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Product</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Quantity</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Price (Rs.)</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Subtotal (Rs.)</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Sunflower</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">4</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">400.00</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n        <div style=\"text-align: right; margin-bottom: 1em;\">\r\n            <p><strong>Subtotal:</strong> Rs. 400.00</p>\r\n            <p><strong>Shipping:</strong> Rs. 50.00</p>\r\n            <p><strong>Total:</strong> Rs. 450.00</p>\r\n        </div>\r\n    </div>','2025-05-18 01:25:50'),(8,80,62,'INV-000080','\r\n    <div class=\"invoice\" style=\"background: #fff; padding: 20px; border: 1px solid #ddd; margin: 1em auto; max-width: 600px;\">\r\n        <h2 style=\"color: #28a745; text-align: center;\">Invoice #INV-000080</h2>\r\n        <p style=\"text-align: center; color: #666;\">Online Nursery System</p>\r\n        <p style=\"text-align: center; color: #666;\">Date: 2025-05-18</p>\r\n        <div style=\"margin: 1em 0;\">\r\n            <h3 style=\"color: #28a745;\">Customer Details</h3>\r\n            <p><strong>Name:</strong> Mahesh</p>\r\n            <p><strong>Email:</strong> mahesh123@gmail.com</p>\r\n            <p><strong>Delivery Address:</strong> koteshwor</p>\r\n            <p><strong>Order Status:</strong> </p>\r\n        </div>\r\n        <h3 style=\"color: #28a745;\">Order Details</h3>\r\n        <table style=\"width: 100%; border-collapse: collapse; margin-bottom: 1em;\">\r\n            <thead>\r\n                <tr style=\"background: #f2f2f2;\">\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Product</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Quantity</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Price (Rs.)</th>\r\n                    <th style=\"border: 1px solid #ddd; padding: 8px;\">Subtotal (Rs.)</th>\r\n                </tr>\r\n            </thead>\r\n            <tbody>\r\n                <tr>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px;\">Sunflower</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: center;\">5</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">100.00</td>\r\n                    <td style=\"border: 1px solid #ddd; padding: 8px; text-align: right;\">500.00</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n        <div style=\"text-align: right; margin-bottom: 1em;\">\r\n            <p><strong>Subtotal:</strong> Rs. 500.00</p>\r\n            <p><strong>Shipping:</strong> Rs. 50.00</p>\r\n            <p><strong>Total:</strong> Rs. 550.00</p>\r\n        </div>\r\n    </div>','2025-05-18 04:45:44');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (4,16,4,1),(5,34,4,2),(6,34,5,1),(7,35,5,1),(8,36,4,1),(9,37,4,1),(10,37,5,1),(11,38,6,1),(12,38,7,1),(13,39,7,1),(14,40,6,1),(15,41,4,2),(16,41,9,3),(17,42,6,1),(18,43,5,1),(19,44,7,1),(20,45,5,1),(21,46,5,1),(22,47,5,1),(23,48,5,1),(24,48,6,1),(25,49,5,1),(26,49,6,1),(27,50,4,10),(28,51,10,1),(29,52,5,6),(30,53,6,1),(31,54,4,1),(32,55,7,1),(33,56,4,1),(34,57,6,1),(35,58,4,1),(36,59,4,1),(37,60,4,1),(38,61,4,1),(39,62,4,1),(40,63,4,1),(41,64,4,1),(42,65,4,1),(43,66,4,1),(44,68,7,1),(45,69,4,4),(46,70,4,25),(47,71,5,1),(48,72,4,3),(49,72,5,2),(50,73,4,3),(51,73,6,3),(52,74,4,1),(53,74,8,3),(54,75,4,1),(55,76,6,1),(56,77,5,4),(57,77,4,5),(58,77,8,3),(59,78,4,1),(60,78,5,1),(61,78,6,2),(62,79,4,4),(63,80,4,5);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_address` text NOT NULL,
  `status` enum('Pending','Shipped','Delivered') DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_orders_date_status` (`order_date`,`status`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,0.00,'kathmandu','Delivered','2025-05-02 12:32:21'),(2,1,2345.00,'mulpani','Delivered','2025-05-02 12:57:28'),(3,2,4690.00,'jhgjit','Delivered','2025-05-02 13:09:41'),(4,1,100.00,'','Delivered','2025-05-02 14:15:59'),(5,2,0.00,'sdfgh','Delivered','2025-05-03 02:17:00'),(6,2,0.00,'baneshwor','Delivered','2025-05-03 06:12:03'),(7,2,0.00,'wertyre','Delivered','2025-05-03 06:12:17'),(8,1,0.00,'SDFGHJKL','Delivered','2025-05-03 12:43:54'),(9,2,0.00,'ghjkjhg','Delivered','2025-05-03 14:44:49'),(11,2,0.00,'baneshwor','Delivered','2025-05-05 10:28:51'),(12,1,0.00,'asdfghjk','Delivered','2025-05-05 10:49:17'),(13,2,0.00,'baneshwor','Delivered','2025-05-05 12:41:47'),(14,1,0.00,'\r\nMulpani ma ayera khanal ko ghar sodh','Shipped','2025-05-06 02:53:23'),(15,48,0.00,'minbhawan','Delivered','2025-05-07 03:09:52'),(16,2,258.00,'asdfghnmjhg','Pending','2025-05-07 19:08:52'),(17,2,0.00,'sdfghjkjhytr','Pending','2025-05-09 01:20:22'),(18,1,50.00,'asdfghgfd','Shipped','2025-05-10 13:09:46'),(19,1,50.00,'qwergtrewrgt','Shipped','2025-05-10 13:10:18'),(20,1,50.00,'asdfgyuio','Shipped','2025-05-10 14:13:49'),(21,2,50.00,'zcvbxzxcv','Shipped','2025-05-10 16:30:02'),(22,50,50.00,'koteshwor','Pending','2025-05-11 02:55:41'),(23,1,50.00,'AQWER','Pending','2025-05-12 10:55:43'),(24,50,50.00,'sdfds','Pending','2025-05-13 13:58:02'),(25,2,50.00,'asdfdsas','Pending','2025-05-13 13:58:31'),(26,1,50.00,'sdertyhj','Pending','2025-05-13 15:56:01'),(27,1,50.00,'palighar','Pending','2025-05-13 18:11:18'),(28,1,50.00,'qwsdsqas','Pending','2025-05-13 18:11:26'),(29,2,50.00,'zxdfgh','Pending','2025-05-14 00:48:53'),(30,2,50.00,'asdfsdf','Pending','2025-05-15 08:51:34'),(31,1,50.00,'qwertrw','Pending','2025-05-15 08:57:44'),(32,1,50.00,'asdf','Pending','2025-05-15 08:58:33'),(33,1,700.00,'AWERTYH','Pending','2025-05-15 09:11:25'),(34,1,700.00,'asdfghj','Pending','2025-05-15 09:14:59'),(35,1,500.00,'sdfghgfd','Pending','2025-05-15 09:15:37'),(36,1,150.00,'wertyhujhgf','Pending','2025-05-15 11:21:13'),(37,1,600.00,'mulpani','Pending','2025-05-15 11:22:08'),(38,1,434.00,'asgdfhgjhk','Pending','2025-05-15 11:28:54'),(39,1,284.00,'awsedrty','Pending','2025-05-15 11:36:50'),(40,1,200.00,'sedrtyuj','Pending','2025-05-15 12:47:49'),(41,1,1600.00,'mulpani','Pending','2025-05-15 13:56:19'),(42,2,200.00,'asdf','Pending','2025-05-15 13:57:37'),(43,1,500.00,'mulpani','Pending','2025-05-15 14:10:05'),(44,1,284.00,'aesd','Pending','2025-05-15 14:27:39'),(45,1,500.00,'asdfgh','Pending','2025-05-15 14:28:46'),(46,1,500.00,'asdfg','Pending','2025-05-15 14:29:35'),(47,1,500.00,'asdfg','Delivered','2025-05-15 14:30:07'),(48,1,650.00,'koteshwor','Pending','2025-05-16 01:31:20'),(49,1,650.00,'koteshwor','Pending','2025-05-16 01:34:43'),(50,59,1050.00,'Lokanthali','Pending','2025-05-16 01:49:08'),(51,1,371.00,'koteshwor\r\n','Pending','2025-05-16 03:51:14'),(52,60,2750.00,'gttrtyh','Pending','2025-05-16 03:58:40'),(53,2,200.00,'sssssss','Pending','2025-05-17 09:05:50'),(54,50,150.00,'asdsa','Shipped','2025-05-17 09:24:25'),(55,1,284.00,'asdfghj','Pending','2025-05-17 12:29:35'),(56,1,150.00,'sdfghjk','Pending','2025-05-17 12:30:43'),(57,1,200.00,'dfghjkl;','Pending','2025-05-17 12:32:10'),(58,1,150.00,'dfghjkl','Pending','2025-05-17 12:33:21'),(59,1,150.00,'asdfg','Pending','2025-05-17 12:58:22'),(60,1,150.00,'sdfgbfd','Pending','2025-05-17 12:58:58'),(61,1,150.00,'dfghjhgf','Pending','2025-05-17 12:59:42'),(62,1,150.00,'sdfghjk','Pending','2025-05-17 13:12:04'),(63,1,150.00,'sdfghj','Pending','2025-05-17 13:36:04'),(64,1,150.00,'sdfghjkl;\'\r\n','Pending','2025-05-17 13:36:48'),(65,1,150.00,'sdfghjk','Pending','2025-05-17 13:38:24'),(66,1,150.00,'sdfsd','Pending','2025-05-17 13:43:54'),(68,1,284.00,'fdsdfds','Pending','2025-05-17 13:55:35'),(69,1,450.00,'mulpani\r\n','Pending','2025-05-17 13:56:50'),(70,2,2550.00,'baneshwor','Shipped','2025-05-17 14:05:30'),(71,2,500.00,'tinkune','Shipped','2025-05-17 14:11:31'),(72,50,1250.00,'minbhawan','Delivered','2025-05-17 14:17:09'),(73,50,800.00,'ram ram','Shipped','2025-05-17 14:23:47'),(74,2,450.00,'hello','Shipped','2025-05-17 14:49:46'),(75,2,150.00,'hiiiii','Delivered','2025-05-17 14:57:50'),(76,50,200.00,'chowk tira','Delivered','2025-05-17 15:04:54'),(77,50,2650.00,'kata kata','','2025-05-17 16:49:31'),(78,50,900.00,'estrdyutrewasedrgfthyjhfsdadzfxgchjnkhgfsdfgh','Pending','2025-05-18 01:23:38'),(79,50,450.00,'retry5ut6iyuoiytrwearstdfyjgkuhijl;','Delivered','2025-05-18 01:25:15'),(80,62,550.00,'koteshwor','Delivered','2025-05-18 04:45:06');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_category` (`category_id`),
  CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (4,'Sunflower','The common sunflower is a species of large annual forb of the daisy family Asteraceae.',100.00,'sunflower.jpg',0,'2025-05-03 12:42:47',3,30),(5,'Spider Plant','Spider plant is very easy to grow indoors in medium to bright light throughout the year.',450.00,'spiderplant.jpg',1,'2025-05-03 12:44:45',1,34),(6,'Rose Plant','A rose is either a woody perennial flowering plant of the genus Rosa.',150.00,'rose.jpg',1,'2025-05-03 12:45:09',3,50),(7,'apple tree','asdfgh',234.00,'rose.jpg',1,'2025-05-05 11:51:35',2,76),(8,'rose plant','flowers',100.00,'rose.jpg',0,'2025-05-07 03:07:16',3,34),(9,'mango tree','lsakjdgfhijoreh',450.00,'fern.jpg',1,'2025-05-07 10:26:00',2,56),(10,'apple tree','qwertyufds',321.00,'bird_paradise.jpg',1,'2025-05-07 10:27:18',1,23),(13,'jiten','2ewe',123.00,'spiderplant.jpg',0,'2025-05-15 16:08:10',2,32),(14,'Bamboo','Tall, fast-growing grass used in construction and crafts.',250.00,'bamboo.jpg',0,'2025-05-18 10:30:25',2,50),(15,'Neem','Medicinal tree known for its antifungal and antibacterial properties.',150.00,'neem.jpg',0,'2025-05-18 10:30:25',2,30),(16,'Bonsai','Miniature tree symbolizing patience and artistic expression.',500.00,'bonsai.jpg',1,'2025-05-18 10:30:25',1,20),(17,'Peepal Tree','Sacred fig tree worshipped in many Asian cultures.',300.00,'peepal.jpg',0,'2025-05-18 10:30:25',2,25),(18,'Tulsi','Holy basil revered for its medicinal and religious significance.',100.00,'tulsi.jpg',1,'2025-05-18 10:30:25',3,40),(19,'Jade Plant','Popular indoor succulent for its thick, shiny leaves.',350.00,'jade.jpg',1,'2025-05-18 10:30:25',1,30),(20,'Orchid','Exotic flowering plant with beautiful, intricate blooms.',450.00,'orchid.jpg',1,'2025-05-18 10:30:25',3,20),(21,'Coconut Tree','Tall palm tree producing versatile coconuts for multiple uses.',600.00,'coconut.jpg',0,'2025-05-18 10:30:25',2,15),(22,'Rice Plant','Staple crop widely cultivated across Asian countries.',200.00,'rice.jpg',0,'2025-05-18 10:30:25',2,50),(23,'Lotus','Sacred flower growing in still water and mud.',400.00,'lotus.jpg',1,'2025-05-18 10:30:25',3,20),(24,'Banana Tree','Fast-growing tree producing sweet, nutritious bananas.',300.00,'banana.jpg',0,'2025-05-18 10:30:25',2,30),(25,'Teak Tree','Valuable hardwood tree used in high-quality furniture.',700.00,'teak.jpg',0,'2025-05-18 10:30:25',2,10),(26,'Rhododendron','National flower of Nepal, blooms in vibrant red clusters.',500.00,'rhododendron.jpg',1,'2025-05-18 10:30:25',3,25),(27,'Aloe Vera','Medicinal plant used in skin care and health products.',250.00,'aloe.jpg',1,'2025-05-18 10:30:25',1,40),(28,'Ginger Plant','Root vegetable widely used in Asian cooking and medicine.',200.00,'ginger.jpg',0,'2025-05-18 10:30:25',2,50),(29,'Palm Tree','Iconic tropical tree with feathery fronds.',450.00,'palm.jpg',0,'2025-05-18 10:30:25',2,15),(30,'Cactus','Drought-resistant plant with spiny stems and vibrant flowers.',350.00,'cactus.jpg',1,'2025-05-18 10:30:25',1,20),(31,'Mango Tree','Fruit-bearing tree producing sweet, juicy mangoes.',500.00,'mango.jpg',0,'2025-05-18 10:30:25',2,20),(32,'Peach Tree','Deciduous tree yielding fragrant, juicy peaches.',600.00,'peach.jpg',0,'2025-05-18 10:30:25',2,15),(33,'Pine Tree','Evergreen tree valued for its wood and aromatic resin.',700.00,'pine.jpg',0,'2025-05-18 10:30:25',2,10);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `pidx` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,75,'RG5nEQTGK7fqVKPAKt3ZEX',150.00,'Completed','2025-05-17 14:58:15'),(2,75,'RG5nEQTGK7fqVKPAKt3ZEX',150.00,'Completed','2025-05-17 15:04:21'),(3,76,'HHUivWfDNxYtddbLEvwubL',200.00,'Completed','2025-05-17 15:05:11'),(4,76,'HHUivWfDNxYtddbLEvwubL',200.00,'Completed','2025-05-17 15:09:07'),(5,77,'hYXK5Go4WQAd3r74JqaSHk',2650.00,'Completed','2025-05-17 16:49:52'),(6,77,'hYXK5Go4WQAd3r74JqaSHk',2650.00,'Completed','2025-05-17 16:50:53'),(7,77,'hYXK5Go4WQAd3r74JqaSHk',2650.00,'Completed','2025-05-17 16:51:13'),(8,77,'hYXK5Go4WQAd3r74JqaSHk',2650.00,'Completed','2025-05-17 16:51:15'),(9,79,'Efxu3ZGc6viRe3FeohePMN',450.00,'Completed','2025-05-18 01:25:50'),(10,80,'jNV3aBMGESpJ2g2DzyMFeK',550.00,'Completed','2025-05-18 04:45:44');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirm_password` varchar(100) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`),
  CONSTRAINT `chk_email_format` CHECK (`email` regexp '^[a-zA-Z0-9][a-zA-Z0-9._%+-]{0,62}[a-zA-Z0-9]@[a-zA-Z]+\\.[a-zA-Z]{2,63}$')
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Mridul Khanal','khanalmridul74@gmail.com','$2y$10$3lkxBth4Si8x5Ys3Upm8zewKERgGuV3nPcKiaOH7yWW/DWr27GbU2',NULL,'admin','2025-05-02 12:31:43'),(2,'Nabin Neupane','nabinneupane@gmail.com','$2y$10$9hPEn4VXo.XzlbkAGarrHeWqshG3mE5X2.Kvob1QhmO/yJOEZH2Du',NULL,'customer','2025-05-02 13:00:28'),(15,'Chitra','chitra@gmail.com','$2y$10$6OeVBfKkglTnTTqrciAV6eGjsek4/ZVhWdMxUG41yMNxG49AogH5K',NULL,'customer','2025-05-05 15:59:04'),(36,'Jitendra','jiten12@gmail.com','$2y$10$BPu9jNqTK2HXcWuzcaau0uTUCUKBSCy461RutjfCEEy5.pV1Ux55m',NULL,'customer','2025-05-07 02:24:52'),(48,'asna','asna@gmail.com','$2y$10$foN139uDpbYQWkUaH194TuQP9MFTCdA0ubxRMB046OgYusfRoMXfG','$2y$10$7l2WMjXC8xjbaRU1cVuC0eniGO22I/a17cWhjoAXRCTn0YzcwDJES','customer','2025-05-07 03:08:51'),(49,'Ishab Das','ishabdas@gmail.com','$2y$10$PLDKVPg2AN82ZnWKasHaCOTCEZT1sgb1SFOTD9so1gyV0D.yRvxgK','$2y$10$/xllX1Jsmg/QS7a64hWGo..6GKh5s3z1v8JQGGslaoq4XHYrMg6Wm','customer','2025-05-09 01:30:33'),(50,'kshitiz','kshitiz123@gmail.com','$2y$10$wh0d5igP7s6LlGtHQMW1o.7Rm4a4X75e5h38zihy4KCK2DBIW1nDa','$2y$10$vBkA/M4T65pvqlsV4mrS4.hcvR/JolZ6lGyS9bH8Gel.LrYhgChv6','customer','2025-05-11 02:53:13'),(59,'Aashish Regmi','regmiaashish660@gmail.com','$2y$10$xEOEsZiVNexxZsHQCBqphuOYXyM8jD0I4jh2REaz2Rk4PEwuX9UIC','$2y$10$iyQMGoTG793TVlEn0p6b/er9XnwMp9QdwKiI2URtdRQyzOjj5CSJu','customer','2025-05-16 01:36:56'),(60,'hemant','hemant123@gmail.com','$2y$10$7OPeo8UwqbbRJFbTEUYsPeowKhjnZiCB0tiFQR9EVw01V26HHys8y','$2y$10$l3kcw2t2E0w.4iGvQ2V6YOTbS.4ve8rBuqqPJlKaYr9jY9xwgqYV6','customer','2025-05-16 03:53:58'),(61,'Abhishek','abhi@gmail.com','$2y$10$FTbr4eo/eSB3sS3CRKTn6eiM8ad/L3QepCCwD7naDg0Nw4Z2VPT0a','$2y$10$OOlr/qNVKCVkyYJZCZUk/u.C8CAEGJbYtX8uQmwprcXeMTISFnTV.','customer','2025-05-16 04:00:37'),(62,'Mahesh','mahesh123@gmail.com','$2y$10$OYfe2hp0e2unACW9hP9hduimFOMQ7/b.vk6MeCHF9ispx2MTYq9/u','$2y$10$NLazBZshqUvv9o783DWlTeuQuT8wwoiCbrDXz3w0cPTgZrQNejFyK','customer','2025-05-18 04:44:18');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-20 23:04:58
