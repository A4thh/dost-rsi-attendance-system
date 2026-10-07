-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: rsi_forum
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attendees`
--

DROP TABLE IF EXISTS `attendees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `qr_token` char(64) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `middle_name` varchar(80) DEFAULT '',
  `last_name` varchar(80) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `age_range` varchar(10) NOT NULL,
  `classification` varchar(60) NOT NULL,
  `email` varchar(160) NOT NULL,
  `visitor_type` varchar(60) NOT NULL,
  `affiliation` varchar(160) NOT NULL,
  `region` varchar(80) NOT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `status` enum('absent','confirmed') DEFAULT 'absent',
  `confirmed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  UNIQUE KEY `qr_token` (`qr_token`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendees`
--

LOCK TABLES `attendees` WRITE;
/*!40000 ALTER TABLE `attendees` DISABLE KEYS */;
INSERT INTO `attendees` VALUES (4,'764e82c1-f0ae-4ef7-8646-dccafc4a67f2','bf4bb1b1c99c4d984c7736e69139526c86e8a43db8f2886884e9de47065abe50','test','','pogi','male','31-59','homemaker','bumbembeng@gmail.com','organizer','ewavrw','12','signatures/764e82c1-f0ae-4ef7-8646-dccafc4a67f2.png','confirmed','2026-09-19 02:41:49','2026-09-19 02:40:36'),(5,'a12a741a-8f04-4c94-b55f-0a6fc7176159','e165ed26f59594e8b3bf8b5d2afab5c7bc7dd9c915ea8da6a2635c15debe74d3','Johnny','','Storm','female','31-59','homemaker','ummercool559@gmail.com','participant','SLSU','6','signatures/a12a741a-8f04-4c94-b55f-0a6fc7176159.png','absent',NULL,'2026-09-19 17:45:54'),(6,'f77843c6-0703-45c3-908d-119355329612','078b52020315204bf57c4b213ab11d98e27b3206798ebfafe1f48b7addf57f08','Germae','','Ilagan','female','15-30','homemaker','gpilagan@slsu.edu.ph','organizer','SLSU','9','signatures/f77843c6-0703-45c3-908d-119355329612.png','confirmed','2026-09-21 15:02:58','2026-09-21 15:00:40'),(7,'2cbf402a-64ac-47e5-8119-f7bb544512b4','cc15b0a1aecfbb703bdf6785437331a833933e58907fb4a7ea2b6ba1d53445e3','John Leo','Barcelon','Jariel','male','15-30','student','johnleojariel9@gmail.com','participant','SLSU','6','signatures/2cbf402a-64ac-47e5-8119-f7bb544512b4.png','confirmed','2026-09-21 18:47:19','2026-09-21 18:43:21');
/*!40000 ALTER TABLE `attendees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'rsi_forum'
--

--
-- Dumping routines for database 'rsi_forum'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-29 20:26:53
