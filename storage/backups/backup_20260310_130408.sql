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
INSERT INTO `categories` VALUES (1,NULL,'Electrónica',NULL,'2026-03-10 11:58:28','2026-03-10 11:58:28'),(2,1,'Móvil',NULL,'2026-03-10 11:58:54','2026-03-10 11:59:01'),(3,1,'Tablet',NULL,'2026-03-10 11:59:15','2026-03-10 11:59:15'),(4,2,'Apple',NULL,'2026-03-10 11:59:28','2026-03-10 11:59:28'),(5,3,'Apple',NULL,'2026-03-10 12:05:42','2026-03-10 12:05:42'),(6,NULL,'Motor',NULL,'2026-03-10 12:05:53','2026-03-10 12:05:53'),(7,6,'Coche',NULL,'2026-03-10 12:06:04','2026-03-10 12:06:04'),(8,6,'Moto',NULL,'2026-03-10 12:06:11','2026-03-10 12:06:11'),(9,6,'Camión',NULL,'2026-03-10 12:06:17','2026-03-10 12:06:17'),(10,NULL,'Ropa',NULL,'2026-03-10 12:06:30','2026-03-10 12:06:30'),(11,10,'Hombre',NULL,'2026-03-10 12:06:37','2026-03-10 12:06:37'),(12,10,'Mujer',NULL,'2026-03-10 12:06:43','2026-03-10 12:06:43'),(13,11,'Camiseta Corta',NULL,'2026-03-10 12:07:00','2026-03-10 12:07:00'),(14,12,'Camiseta Corta',NULL,'2026-03-10 12:07:08','2026-03-10 12:07:08'),(15,12,'Falda',NULL,'2026-03-10 12:07:16','2026-03-10 12:07:16'),(16,12,'Vestido',NULL,'2026-03-10 12:07:34','2026-03-10 12:07:34'),(17,NULL,'Hogar',NULL,'2026-03-10 12:07:41','2026-03-10 12:07:41'),(18,19,'Sofá',NULL,'2026-03-10 12:08:04','2026-03-10 12:09:46'),(19,17,'Salón',NULL,'2026-03-10 12:09:39','2026-03-10 12:09:39'),(20,17,'Dormitorio',NULL,'2026-03-10 12:10:16','2026-03-10 12:10:16'),(21,20,'Cama',NULL,'2026-03-10 12:10:23','2026-03-10 12:10:23'),(22,20,'Armario',NULL,'2026-03-10 12:10:40','2026-03-10 12:10:40'),(23,NULL,'Libro',NULL,'2026-03-10 12:11:53','2026-03-10 12:11:53'),(24,1,'Portátil',NULL,'2026-03-10 12:37:34','2026-03-10 12:37:34');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_messages`
--

LOCK TABLES `daily_messages` WRITE;
/*!40000 ALTER TABLE `daily_messages` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_03_05_154439_create_products_table',1),(5,'2026_03_05_155439_create_product_images_table',1),(6,'2026_03_09_092244_create_daily_messages_table',1),(7,'2026_03_09_140857_add_indexes_to_products_table',1),(8,'2026_03_09_153051_create_categories_table',1),(9,'2026_03_09_160647_add_image_to_categories_table',1),(10,'2026_03_09_163505_refactor_products_categoria_to_category_id',1),(11,'2026_03_09_add_parent_id_to_categories',1),(12,'2026_03_10_120321_remove_unique_name_from_categories',2),(13,'2026_03_10_122851_increase_precio_precision_in_products',3);
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'productos/w7IazujBukLdqUeRnU0unzjbXMIShOaez2rt7Lej.jpg','2026-03-10 12:20:06','2026-03-10 12:20:06'),(2,1,'productos/PXFCFfropBg6vBd0WKnOwPuUEUb5se2S9PEK0N3a.jpg','2026-03-10 12:20:06','2026-03-10 12:20:06'),(3,2,'productos/2e49Lg0hwmtV2hhAdaKQMBZM8dXnBPTR8yAcgR5e.jpg','2026-03-10 12:30:23','2026-03-10 12:30:23'),(4,3,'productos/hBT8FlA6JGYUJyr0jCq1reQp3fN4Tmhl4MTNklXy.jpg','2026-03-10 12:31:09','2026-03-10 12:31:09'),(5,4,'productos/wOXxvDEmhtg2DIP1jNtr4ybwrtWdTgUZWfw8jabb.jpg','2026-03-10 12:32:32','2026-03-10 12:32:32'),(6,5,'productos/t7i1z7F3RM72Hmxgmzcv4a3mDdtdagcC8Dp3w5SC.jpg','2026-03-10 12:33:30','2026-03-10 12:33:30'),(7,6,'productos/CTkUkF01yTAU2zB3ejsTPbjEAeSn71UyFBSXZ7dF.jpg','2026-03-10 12:35:51','2026-03-10 12:35:51'),(8,7,'productos/HmCu1gBRYn69jEani6xyohhTf8LJGTzjg1V4IUEy.jpg','2026-03-10 12:36:53','2026-03-10 12:36:53'),(9,7,'productos/DfZYqYAmiKnnkJNLOrMyHXT1zPkA7KlGQKJNkQiO.png','2026-03-10 12:36:53','2026-03-10 12:36:53'),(10,8,'productos/iNJPjSLMomvApn5uWsKhXRGK07fpTdh8Wb1wdPRm.jpg','2026-03-10 12:38:18','2026-03-10 12:38:18'),(11,9,'productos/mJeIYf6J9FrTSHNEyI15MuF2QhQ2tvsgsDomqgus.jpg','2026-03-10 12:39:15','2026-03-10 12:39:15'),(12,10,'productos/0aKCM3Z3PggnAf55GCXr2loz4dI76Cj5bs3qrXAX.jpg','2026-03-10 12:39:50','2026-03-10 12:39:50');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
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
  FULLTEXT KEY `products_fulltext_search` (`nombre`,`descripcion`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,8,'Honda CBF 500','Moto poco usada, solo para los fines de semana.\r\n\r\nNegra\r\n2006\r\n30.000km\r\nsiempre garaje, nunca circuito\r\nno negociable',3333.00,'activo','2026-03-10 12:20:06','2026-03-10 12:20:06'),(2,7,'Audi Future','El prototipo de coche que Audi supone que estará para el año 2030',123000000.00,'activo','2026-03-10 12:30:23','2026-03-10 12:30:23'),(3,14,'Camiseta Blanca para Mujeres','Blanca',15.00,'activo','2026-03-10 12:31:09','2026-03-10 12:31:09'),(4,7,'Vehículo Histórico','Vehículo para bodas, histórico\r\n\r\nLlámanos o escríbenos al 654654654',300.00,'activo','2026-03-10 12:32:32','2026-03-10 12:32:32'),(5,23,'Don Quijte de La Mancha','El libro original, solo existe esté.\r\n\r\nMuy bien conservado\r\nSiempre tocado con guantes',1000000.00,'activo','2026-03-10 12:33:30','2026-03-10 12:33:30'),(6,6,'1.9 TDI Código motor ARL','Motor de un típico 1.9TDI  \r\nCódigo motor ARL',599.00,'activo','2026-03-10 12:35:51','2026-03-10 12:35:51'),(7,4,'iPhone 12 Pro','iPhone 12 Pro\r\n256GB\r\n86%\r\nNo roces, no goles\r\nNo envio.',333.00,'activo','2026-03-10 12:36:53','2026-03-10 12:36:53'),(8,24,'MacBook Air M1 2020','MacBook Air M1 2020\r\n256GB\r\n16GB\r\n90%',1000.00,'activo','2026-03-10 12:38:18','2026-03-10 12:38:18'),(9,19,'Silla Premium','Silla blanca con acabado premium',100.00,'activo','2026-03-10 12:39:15','2026-03-10 12:39:15'),(10,18,'Sofá L','Sofá con forma de L\r\ncolor gris claro',300.00,'activo','2026-03-10 12:39:50','2026-03-10 12:39:50');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('c9puVxX3P6wZsJ0djda9lDg6gKuVNMtYmXyiNH4n',2,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUjVJOGo5a0RaR0VYRWI4bE94SU1UekxNMWZiVDV3bWhzeFo3Y0lzaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxNjoiaHR0cDovL2xvY2FsaG9zdCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI1OiJodHRwOi8vbG9jYWxob3N0L3Byb2R1Y3RzIjtzOjU6InJvdXRlIjtzOjE0OiJwcm9kdWN0cy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1773142877),('lhAQeP18CaLseWg2eAIso9vY1XaBHXogmCgVJmcX',2,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUFRpWTdvdkJDa29IY1ZqR1R1M1d2eVZTU1I5ZEprMzdUREZ3NmxNdCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vbG9jYWxob3N0L2NhdGVnb3JpZXMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjE3OiJjYXRlZ29yaWVzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1773140912);
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
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com','2026-03-10 11:28:50','$2y$12$8V7x1gCtCw.Eex0KMOBEI.nrHT.uf9hFuFo2wEOmEz5/j/Up/5CDa','x2OV6EnAFg','2026-03-10 11:28:51','2026-03-10 11:28:51'),(2,'Francisco','fran@gmail.com',NULL,'$2y$12$g5/XToR7TrpFO/D0H0Xu1.cZGvNkf3IIpk73nSzTlu7SzsVggH0eq',NULL,'2026-03-10 11:31:44','2026-03-10 11:31:44');
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

-- Dump completed on 2026-03-10 12:04:08
