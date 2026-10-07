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
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_token` char(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_range` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `classification` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affiliation` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_registered` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('absent','confirmed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'absent',
  `confirmed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_attendees_uuid` (`uuid`),
  UNIQUE KEY `uq_attendees_username` (`username`),
  UNIQUE KEY `uq_attendees_qr_token` (`qr_token`),
  KEY `idx_attendees_email` (`email`),
  KEY `idx_attendees_registered` (`is_registered`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendees`
--

LOCK TABLES `attendees` WRITE;
/*!40000 ALTER TABLE `attendees` DISABLE KEYS */;
INSERT INTO `attendees` VALUES (1,'90ee3ed9-7fe3-4549-b864-fc8425deb7ee','f513b2fc9b2a1fc5284c23fdeca76da8ea9b18426955850cd3b2acc4d5d5283c','user123','$2y$10$gm8gJ2WFkIF00hgh/X9SgOUV1wC.9JwuIe8EKzP5ZIeqI.guTjOHu','John Leo','','Jariel','female','23','government','ummercool559@gmail.com','exhibitor','SLSU','6','signatures/90ee3ed9-7fe3-4549-b864-fc8425deb7ee.png',1,'absent',NULL,'2026-10-05 13:28:38'),(2,'80d38a94-b055-4a9c-8e53-dfeca2255b9b','8ebd73b871fbf571346967ba60b4f42b579848730ad23f1f7f70a50795f783f8','emansicat','$2y$10$qml1Cs5XwjAhbtiSswo4seAYF6FdwwBr.74CSc6oNj5jxjwdju/8u','Jose Emmanuel','Almeda','Sicat','male','25','government','jasicat@slsu.edu.ph','participant','Slsu','5','signatures/80d38a94-b055-4a9c-8e53-dfeca2255b9b.png',1,'absent',NULL,'2026-10-05 15:59:32'),(3,'935d51d2-fe9a-4135-850a-cdb5ceb8b8df','2258f2dab6fa714d475db1bca67653064e00b296d7eab97b26009ce1f9d846b1','user2','$2y$10$F31ZUjIpb1qI4EYm47ey.eIuVfA0Gio/JSV/XlyNrejT3BRdozEFa','Well','Well','Well','male','26','homemaker','johnleojariel9@gmail.com','exhibitor','SLSU','6','signatures/935d51d2-fe9a-4135-850a-cdb5ceb8b8df.png',1,'absent',NULL,'2026-10-05 15:59:51'),(4,'f51f0fcd-6ae9-4b94-8bea-c7dc2ad1245b','ba90179dc3aceb8368f8619d53d1127df4d9ee109f6ea8f8d319ac6c4aa045d8','xepgalcor','$2y$10$dgAHJo4hNeoSnq86szlF2e9sZ6k1r8Ltni5uFVNuaQrx0i453hHwy','Xepred','Gallardo','Coros','male','20','student','xgcoros@slsu.edu.ph','participant','SLSU','6','signatures/f51f0fcd-6ae9-4b94-8bea-c7dc2ad1245b.png',1,'confirmed','2026-10-05 16:28:05','2026-10-05 16:19:13'),(5,'a504b266-e7b5-4444-81e8-ba8af6bb3808',NULL,'zewa','$2y$10$WMvp0R87aErrw/7aDdk9jeLSmoxqbJLDXiC6eDQGLh0ZNQAV06z3.','Carl Jasper','Salentes','Abina',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'absent',NULL,'2026-10-05 16:26:37');
/*!40000 ALTER TABLE `attendees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attendee_id` int NOT NULL,
  `q1_overall` tinyint NOT NULL,
  `q2_content` tinyint NOT NULL,
  `q3_speakers` tinyint NOT NULL,
  `q4_venue` tinyint NOT NULL,
  `q5_recommend` tinyint NOT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_eval_attendee` (`attendee_id`),
  CONSTRAINT `fk_eval_attendee` FOREIGN KEY (`attendee_id`) REFERENCES `attendees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluations`
--

LOCK TABLES `evaluations` WRITE;
/*!40000 ALTER TABLE `evaluations` DISABLE KEYS */;
INSERT INTO `evaluations` VALUES (1,2,5,5,5,5,5,NULL,'2026-10-05 16:00:53');
/*!40000 ALTER TABLE `evaluations` ENABLE KEYS */;
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

-- Dump completed on 2026-10-07 14:08:11
