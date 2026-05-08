-- MySQL dump 10.13  Distrib 8.0.45, for Linux (aarch64)
--
-- Host: mysql    Database: laravel
-- ------------------------------------------------------
-- Server version	8.4.8

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('venalia-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0','i:2;',1778230717),('venalia-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer','i:1778230717;',1778230717);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Electrónica',NULL,'2026-03-10 11:58:28','2026-03-10 11:58:28'),(2,1,'Móvil',NULL,'2026-03-10 11:58:54','2026-03-10 11:59:01'),(3,1,'Tablet',NULL,'2026-03-10 11:59:15','2026-03-10 11:59:15'),(4,2,'Apple',NULL,'2026-03-10 11:59:28','2026-03-10 11:59:28'),(5,3,'Apple',NULL,'2026-03-10 12:05:42','2026-03-10 12:05:42'),(6,NULL,'Motor',NULL,'2026-03-10 12:05:53','2026-03-10 12:05:53'),(7,6,'Coche',NULL,'2026-03-10 12:06:04','2026-03-10 12:06:04'),(8,6,'Moto',NULL,'2026-03-10 12:06:11','2026-03-10 12:06:11'),(9,6,'Camión',NULL,'2026-03-10 12:06:17','2026-03-10 12:06:17'),(10,NULL,'Ropa',NULL,'2026-03-10 12:06:30','2026-03-10 12:06:30'),(11,10,'Hombre',NULL,'2026-03-10 12:06:37','2026-03-10 12:06:37'),(12,10,'Mujer',NULL,'2026-03-10 12:06:43','2026-03-10 12:06:43'),(13,11,'Camiseta Corta',NULL,'2026-03-10 12:07:00','2026-03-10 12:07:00'),(14,12,'Camiseta Corta',NULL,'2026-03-10 12:07:08','2026-03-10 12:07:08'),(15,12,'Falda',NULL,'2026-03-10 12:07:16','2026-03-10 12:07:16'),(16,12,'Vestido',NULL,'2026-03-10 12:07:34','2026-03-10 12:07:34'),(17,NULL,'Hogar',NULL,'2026-03-10 12:07:41','2026-03-10 12:07:41'),(18,19,'Sofá',NULL,'2026-03-10 12:08:04','2026-03-10 12:09:46'),(19,17,'Salón',NULL,'2026-03-10 12:09:39','2026-03-10 12:09:39'),(20,17,'Dormitorio',NULL,'2026-03-10 12:10:16','2026-03-10 12:10:16'),(21,20,'Cama',NULL,'2026-03-10 12:10:23','2026-03-10 12:10:23'),(22,20,'Armario',NULL,'2026-03-10 12:10:40','2026-03-10 12:10:40'),(23,NULL,'Libro','categorias/v08lQlsJdf9iFb4G0dz2E3fgfcGg4s7DLSGVDIiY.png','2026-03-10 12:11:53','2026-04-30 12:37:56'),(24,1,'Portátil',NULL,'2026-03-10 12:37:34','2026-03-10 12:37:34');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_messages`
--

DROP TABLE IF EXISTS `daily_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_messages_date_unique` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_messages`
--

LOCK TABLES `daily_messages` WRITE;
/*!40000 ALTER TABLE `daily_messages` DISABLE KEYS */;
INSERT INTO `daily_messages` VALUES (1,'Colabora con otros vendedores 🤝','2026-05-04','2026-05-04 10:50:43','2026-05-04 10:50:43'),(2,'La educación continua es clave 🎓','2026-05-05','2026-05-05 10:51:27','2026-05-05 10:51:27'),(3,'La consistencia vence a la velocidad 🏃','2026-05-06','2026-05-06 12:37:46','2026-05-06 12:37:46'),(4,'Tu energía positiva atrae clientes 🌟','2026-05-07','2026-05-07 09:18:02','2026-05-07 09:18:02'),(5,'Mantente actualizado con tendencias de mercado 📚','2026-05-08','2026-05-08 11:03:40','2026-05-08 11:03:40');
/*!40000 ALTER TABLE `daily_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `thread_user_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `messages_thread_user_id_foreign` (`thread_user_id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_product_id_thread_user_id_index` (`product_id`,`thread_user_id`),
  CONSTRAINT `messages_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_thread_user_id_foreign` FOREIGN KEY (`thread_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,6,3,3,'HOLAA, sigue disponible','2026-04-28 10:52:52','2026-04-28 08:38:54'),(2,7,3,3,'como tiene las camaras','2026-04-28 15:36:09','2026-04-28 08:39:23'),(3,7,3,3,'estan bien o no?','2026-04-28 15:36:09','2026-04-28 08:39:30'),(4,7,3,3,'??','2026-04-28 15:36:09','2026-04-28 08:43:44'),(5,7,3,3,'hola','2026-04-28 15:36:09','2026-04-28 10:47:08'),(6,6,3,3,'entonces lo tiense?','2026-04-28 10:52:52','2026-04-28 10:47:48'),(7,1,3,3,'Que tal!!','2026-04-28 15:35:51','2026-04-28 15:11:46'),(8,1,3,3,'Sigue en venta el bicho','2026-04-28 15:35:51','2026-04-28 15:11:53'),(9,2,3,3,'ENSeriO','2026-04-28 15:31:23','2026-04-28 15:16:35'),(10,4,3,3,'lo tenes dispo?','2026-04-28 15:31:10','2026-04-28 15:17:09'),(11,4,3,3,'hola?','2026-04-28 15:31:10','2026-04-28 15:19:08'),(12,4,3,2,'hola si','2026-04-29 09:13:44','2026-04-29 09:04:51'),(13,4,3,3,'donde estas ubicado','2026-04-29 09:21:22','2026-04-29 09:14:02'),(14,1,3,2,'que tal','2026-04-29 09:22:01','2026-04-29 09:21:34'),(15,5,2,2,'holaaa','2026-04-29 09:48:50','2026-04-29 09:23:54'),(16,12,2,2,'que tal','2026-04-29 09:49:20','2026-04-29 09:26:55'),(17,12,2,2,'hola','2026-04-29 09:49:20','2026-04-29 09:30:24'),(18,5,2,2,'hola','2026-04-29 09:48:50','2026-04-29 09:33:23'),(19,6,3,2,'e','2026-04-29 09:48:47','2026-04-29 09:48:38'),(20,6,3,3,'pues no se','2026-04-30 12:00:41','2026-04-29 09:49:35'),(21,11,3,3,'Lo tenes aun dispo?','2026-04-30 12:17:43','2026-04-30 12:17:31'),(22,11,3,2,'Nop, se lo acaban de llevar','2026-05-04 11:50:21','2026-04-30 12:17:52'),(23,12,2,2,'hola','2026-05-04 11:30:18','2026-05-04 11:28:34'),(24,12,2,3,'hola, dime','2026-05-04 11:30:42','2026-05-04 11:30:25'),(25,12,2,2,'sigue dispo','2026-05-04 11:32:00','2026-05-04 11:30:53'),(26,12,2,3,'si, aqiu lo tengo','2026-05-04 11:35:32','2026-05-04 11:34:27'),(27,12,2,2,'a cuanto me lo dejas','2026-05-04 11:42:27','2026-05-04 11:42:09'),(28,12,2,3,'15','2026-05-04 11:43:24','2026-05-04 11:42:38'),(29,12,2,2,'15?, si lo tienes en 12','2026-05-04 11:44:51','2026-05-04 11:44:49'),(30,12,2,3,'pues no preguntes','2026-05-04 11:45:04','2026-05-04 11:45:01'),(31,12,2,2,'eso no es forma de hablar a un posible comprador','2026-05-04 11:47:03','2026-05-04 11:47:02'),(32,12,2,3,'lo quieres o me estas mareando','2026-05-04 11:47:25','2026-05-04 11:47:24'),(33,12,2,2,'fuck you','2026-05-04 11:48:18','2026-05-04 11:48:18'),(34,12,2,3,'pesado','2026-05-04 11:48:25','2026-05-04 11:48:22'),(35,12,2,2,'mar1con','2026-05-04 11:48:30','2026-05-04 11:48:29'),(36,12,2,3,'*bloqueado*','2026-05-04 11:48:40','2026-05-04 11:48:37'),(37,12,2,2,'??','2026-05-04 11:48:42','2026-05-04 11:48:41'),(38,11,3,3,'hola¿','2026-05-04 11:52:43','2026-05-04 11:50:31'),(39,11,3,3,'se que has leído el mensaje, no me dejes en visto','2026-05-04 11:54:01','2026-05-04 11:54:00'),(40,11,3,2,'que dime','2026-05-04 11:54:18','2026-05-04 11:54:16'),(41,11,3,3,'lo tienes en venta','2026-05-04 11:54:25','2026-05-04 11:54:25'),(42,11,3,2,'pero si te dije el otro dia que no','2026-05-04 11:54:36','2026-05-04 11:54:34'),(43,11,3,3,'pues porque no lo pones como vendido','2026-05-04 11:54:46','2026-05-04 11:54:44'),(44,11,3,3,'ey¿','2026-05-04 11:55:28','2026-05-04 11:55:26'),(45,11,3,2,'que','2026-05-04 11:58:06','2026-05-04 11:58:05'),(46,11,3,3,'entonces','2026-05-04 11:58:14','2026-05-04 11:58:11'),(47,11,3,3,'hola?','2026-05-04 11:58:22','2026-05-04 11:58:20'),(48,11,3,2,'?','2026-05-04 12:01:41','2026-05-04 12:01:38'),(49,11,3,3,'?','2026-05-04 12:01:47','2026-05-04 12:01:46'),(50,11,3,2,'pesado','2026-05-04 12:01:53','2026-05-04 12:01:52'),(51,11,3,3,'no tienes otro por ahi','2026-05-04 12:03:44','2026-05-04 12:03:44'),(52,11,3,2,'que voy a tener','2026-05-04 12:03:50','2026-05-04 12:03:49'),(53,11,3,2,'lo que tengo es ganar de bloquearto','2026-05-04 12:03:56','2026-05-04 12:03:56'),(54,11,3,2,'o reportarte','2026-05-04 12:03:59','2026-05-04 12:03:59'),(55,11,3,3,'por?','2026-05-04 12:04:05','2026-05-04 12:04:02'),(56,11,3,3,'hola','2026-05-04 12:05:29','2026-05-04 12:05:26'),(57,11,3,2,'queeeeee','2026-05-04 12:05:33','2026-05-04 12:05:31'),(58,11,3,2,'que no tengo','2026-05-04 12:05:36','2026-05-04 12:05:35'),(59,11,3,3,'porfi','2026-05-04 12:10:47','2026-05-04 12:10:44'),(60,11,3,2,'que no tio','2026-05-04 12:10:55','2026-05-04 12:10:54'),(61,11,3,3,'pls','2026-05-04 12:10:59','2026-05-04 12:10:58'),(62,5,2,2,'estas vivo?','2026-05-04 13:22:03','2026-05-04 13:12:44'),(63,5,2,2,'esta ahi','2026-05-04 13:22:03','2026-05-04 13:21:37'),(64,5,2,3,'hola','2026-05-04 13:22:08','2026-05-04 13:22:07'),(65,5,2,2,'hola','2026-05-04 13:22:24','2026-05-04 13:22:23'),(66,5,2,2,'holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','2026-05-04 13:32:20','2026-05-04 13:32:20'),(67,2,3,3,'hola','2026-05-05 09:20:58','2026-05-05 09:20:41'),(68,2,3,2,'quetal','2026-05-05 09:21:07','2026-05-05 09:21:04'),(69,2,3,2,'ey','2026-05-05 09:22:14','2026-05-05 09:21:19');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_03_05_154439_create_products_table',1),(5,'2026_03_05_155439_create_product_images_table',1),(6,'2026_03_09_092244_create_daily_messages_table',1),(7,'2026_03_09_140857_add_indexes_to_products_table',1),(8,'2026_03_09_153051_create_categories_table',1),(9,'2026_03_09_160647_add_image_to_categories_table',1),(10,'2026_03_09_163505_refactor_products_categoria_to_category_id',1),(11,'2026_03_09_add_parent_id_to_categories',1),(12,'2026_03_10_120321_remove_unique_name_from_categories',2),(13,'2026_03_10_122851_increase_precio_precision_in_products',3),(14,'2026_04_27_171115_add_is_admin_to_users_table',4),(15,'2026_04_28_084943_create_product_user_table',5),(16,'2026_04_28_000001_create_product_likes_table',6),(17,'2026_04_28_000002_create_messages_table',7),(18,'2026_04_28_000003_add_user_id_to_products_table',8),(20,'2026_05_04_000001_add_last_seen_at_to_users_table',9),(21,'2026_05_05_101920_drop_product_user_table',10),(22,'2026_05_07_000001_add_avatar_to_users_table',11),(23,'2026_05_08_113006_create_ratings_table',12);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'productos/w7IazujBukLdqUeRnU0unzjbXMIShOaez2rt7Lej.jpg','2026-03-10 12:20:06','2026-03-10 12:20:06'),(2,1,'productos/PXFCFfropBg6vBd0WKnOwPuUEUb5se2S9PEK0N3a.jpg','2026-03-10 12:20:06','2026-03-10 12:20:06'),(3,2,'productos/2e49Lg0hwmtV2hhAdaKQMBZM8dXnBPTR8yAcgR5e.jpg','2026-03-10 12:30:23','2026-03-10 12:30:23'),(4,3,'productos/hBT8FlA6JGYUJyr0jCq1reQp3fN4Tmhl4MTNklXy.jpg','2026-03-10 12:31:09','2026-03-10 12:31:09'),(5,4,'productos/wOXxvDEmhtg2DIP1jNtr4ybwrtWdTgUZWfw8jabb.jpg','2026-03-10 12:32:32','2026-03-10 12:32:32'),(6,5,'productos/t7i1z7F3RM72Hmxgmzcv4a3mDdtdagcC8Dp3w5SC.jpg','2026-03-10 12:33:30','2026-03-10 12:33:30'),(7,6,'productos/CTkUkF01yTAU2zB3ejsTPbjEAeSn71UyFBSXZ7dF.jpg','2026-03-10 12:35:51','2026-03-10 12:35:51'),(8,7,'productos/HmCu1gBRYn69jEani6xyohhTf8LJGTzjg1V4IUEy.jpg','2026-03-10 12:36:53','2026-03-10 12:36:53'),(9,7,'productos/DfZYqYAmiKnnkJNLOrMyHXT1zPkA7KlGQKJNkQiO.png','2026-03-10 12:36:53','2026-03-10 12:36:53'),(10,8,'productos/iNJPjSLMomvApn5uWsKhXRGK07fpTdh8Wb1wdPRm.jpg','2026-03-10 12:38:18','2026-03-10 12:38:18'),(11,9,'productos/mJeIYf6J9FrTSHNEyI15MuF2QhQ2tvsgsDomqgus.jpg','2026-03-10 12:39:15','2026-03-10 12:39:15'),(12,10,'productos/0aKCM3Z3PggnAf55GCXr2loz4dI76Cj5bs3qrXAX.jpg','2026-03-10 12:39:50','2026-03-10 12:39:50'),(13,11,'productos/6YCg0kNHAZrqkUeiUFKYl507YHJhQiAM1mvybwiJ.jpg','2026-04-28 11:29:52','2026-04-28 11:29:52'),(14,11,'productos/D8W2pRHzcA7ZzrFB84XdZm5wBM4S3PUi7F57f8re.jpg','2026-04-28 11:29:52','2026-04-28 11:29:52'),(15,12,'productos/K77T6tSOsN7E8DVv4Vg02IxQRfXSuTQ1Nr4gt3Ct.png','2026-04-29 09:26:16','2026-04-29 09:26:16');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_likes`
--

DROP TABLE IF EXISTS `product_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_likes_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `product_likes_product_id_foreign` (`product_id`),
  CONSTRAINT `product_likes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_likes`
--

LOCK TABLES `product_likes` WRITE;
/*!40000 ALTER TABLE `product_likes` DISABLE KEYS */;
INSERT INTO `product_likes` VALUES (1,2,10,'2026-04-28 10:24:26'),(2,2,9,'2026-04-28 10:26:00'),(3,2,8,'2026-04-28 10:26:00'),(5,2,7,'2026-04-28 10:26:16'),(6,2,6,'2026-04-28 10:26:29'),(7,2,12,'2026-04-29 09:26:28'),(9,3,2,'2026-04-29 09:47:29');
/*!40000 ALTER TABLE `product_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(15,2) NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_estado_index` (`estado`),
  KEY `products_created_at_index` (`created_at`),
  KEY `products_category_id_index` (`category_id`),
  KEY `products_estado_category_index` (`estado`,`category_id`),
  KEY `products_user_id_foreign` (`user_id`),
  FULLTEXT KEY `products_fulltext_search` (`nombre`,`descripcion`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,2,8,'Honda CBF 500','Moto poco usada, solo para los fines de semana.\r\n\r\nNegra\r\n2006\r\n30.000km\r\nsiempre garaje, nunca circuito\r\nno negociable',3333.00,'reservado','2026-03-10 12:20:06','2026-05-08 11:09:27'),(2,2,7,'Audi Future','El prototipo de coche que Audi supone que estará para el año 2030',123000000.00,'vendido','2026-03-10 12:30:23','2026-05-08 11:33:59'),(3,2,14,'Camiseta Blanca para Mujeres','Blanca',15.00,'activo','2026-03-10 12:31:09','2026-03-10 12:31:09'),(4,2,7,'Vehículo Histórico','Vehículo para bodas, histórico\r\n\r\nLlámanos o escríbenos al 654654654',300.00,'vendido','2026-03-10 12:32:32','2026-05-08 09:57:26'),(5,3,23,'Don Quijte de La Mancha','El libro original, solo existe esté.\r\n\r\nMuy bien conservado\r\nSiempre tocado con guantes',1000000.00,'activo','2026-03-10 12:33:30','2026-03-10 12:33:30'),(6,NULL,6,'1.9 TDI Código motor ARL','Motor de un típico 1.9TDI  \r\nCódigo motor ARL',599.00,'activo','2026-03-10 12:35:51','2026-03-10 12:35:51'),(7,3,4,'iPhone 12 Pro','iPhone 12 Pro\r\n256GB\r\n86%\r\nNo roces, no goles\r\nNo envio.',333.00,'activo','2026-03-10 12:36:53','2026-03-10 12:36:53'),(8,NULL,24,'MacBook Air M1 2020','MacBook Air M1 2020\r\n256GB\r\n16GB\r\n90%',1000.00,'activo','2026-03-10 12:38:18','2026-04-30 12:53:34'),(9,NULL,19,'Silla Premium','Silla blanca con acabado premium',100.00,'activo','2026-03-10 12:39:15','2026-03-10 12:39:15'),(10,NULL,18,'Sofá L','Sofá con forma de L\r\ncolor gris claro',300.00,'activo','2026-03-10 12:39:50','2026-03-10 12:39:50'),(11,2,5,'iPad WiFi 256GB','Nuevo',344.00,'inactivo','2026-04-28 11:29:52','2026-04-30 12:52:24'),(12,3,23,'test','a',12.00,'reservado','2026-04-29 09:26:16','2026-04-30 12:36:36'),(13,2,17,'Cojin','Es un cojin',5.00,'activo','2026-05-07 13:28:14','2026-05-07 13:28:14'),(14,2,1,'TV','Es una tv',399.00,'activo','2026-05-07 13:28:36','2026-05-07 13:28:36'),(15,2,3,'Tablet','Si, es una tablet',123.00,'vendido','2026-05-07 13:31:42','2026-05-08 10:15:45');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `rater_id` bigint unsigned NOT NULL,
  `rated_user_id` bigint unsigned NOT NULL,
  `stars` tinyint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ratings_product_id_rater_id_unique` (`product_id`,`rater_id`),
  KEY `ratings_rater_id_foreign` (`rater_id`),
  KEY `ratings_rated_user_id_foreign` (`rated_user_id`),
  CONSTRAINT `ratings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_rated_user_id_foreign` FOREIGN KEY (`rated_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_rater_id_foreign` FOREIGN KEY (`rater_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
INSERT INTO `ratings` VALUES (1,2,2,3,5,'2026-05-08 11:34:10','2026-05-08 11:34:10');
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('Mh5hJVGaqeZYVe4KzHZ3ZeyM7b4KtjgmnKDdnxak',2,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2tGS3NzeUpXdWRBQTRLMHF2OGNWUXAwRXd0S1BzMXNTSWtrZ0hNWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3QvYWRtaW4vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1778234835);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com',NULL,0,NULL,'2026-03-10 11:28:50','$2y$12$8V7x1gCtCw.Eex0KMOBEI.nrHT.uf9hFuFo2wEOmEz5/j/Up/5CDa','x2OV6EnAFg','2026-03-10 11:28:51','2026-03-10 11:28:51'),(2,'Francisco','fran@gmail.com','avatars/cMwC3m5TgMO82BdWlGF5A8Het6o5QdU2JTOiakZv.jpg',1,'2026-05-08 11:34:13','2026-05-08 10:57:42','$2y$12$g5/XToR7TrpFO/D0H0Xu1.cZGvNkf3IIpk73nSzTlu7SzsVggH0eq','gwXztHbh20XY3T6ZaVYPIqAnI35n04iIEdP3DqwpeIXHnHbBSdQWpPwKIM8h','2026-03-10 11:31:44','2026-05-08 11:34:13'),(3,'Prueba','prueba@gmail.com',NULL,0,'2026-05-05 09:24:46',NULL,'$2y$12$mgLpOtOSGux9rNaFu5C8o.H15RFTVgkCKYZO4YrQfG1wZ13QdnbuS',NULL,'2026-04-27 17:09:10','2026-05-05 09:24:46'),(4,'wasd','wasd@gmail.com',NULL,1,NULL,NULL,'$2y$12$eFjvS28cTONRdd46aETkHea/nCxij/D5Q9XVoaA3v3EYs.6jeL0tm',NULL,'2026-05-07 12:44:13','2026-05-07 12:48:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'laravel'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-08 10:09:53
