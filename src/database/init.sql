-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: roamance
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `interests`
--

DROP TABLE IF EXISTS `interests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `interests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interests`
--

LOCK TABLES `interests` WRITE;
/*!40000 ALTER TABLE `interests` DISABLE KEYS */;
/*!40000 ALTER TABLE `interests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `bio` text,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Kai1234','kai.howley@email.com','Password!23','Kai','Howley','2004-10-04','I love gaming, coding, and late-night coffee.',NULL,'2026-02-17 15:51:16'),(2,'J4neDoe','jane.doe@email.com','SecurePass#45','Jane','Doe','2003-05-21','Avid reader and music enthusiast.',NULL,'2026-02-17 15:51:16'),(3,'Leo89','leo.martin@email.com','LeoRocks89!','Leo','Martin','1999-08-12','Passionate about photography and travel.',NULL,'2026-02-17 15:51:16'),(4,'SophieL','sophie.lane@email.com','Sophie123$','Sophie','Lane','2002-11-03','Baker, dog lover, and tech fan.',NULL,'2026-02-17 15:51:16'),(5,'MaxPower','max.power@email.com','Max!Power2023','Max','Power','2001-07-19','Fitness junkie and movie buff.',NULL,'2026-02-17 15:51:16'),(6,'EmmaG23','emma.green@email.com','EmmaG@321','Emma','Green','2004-03-15','Love painting and exploring new cafes.',NULL,'2026-02-17 15:51:16'),(7,'ChrisT99','chris.taylor@email.com','ChrisT99#Pass','Chris','Taylor','2000-12-01','Gamer and sports fanatic.',NULL,'2026-02-17 15:51:16'),(8,'LilyRose','lily.rose@email.com','LilyR!2024','Lily','Rose','2003-09-09','Yoga enthusiast and blogger.',NULL,'2026-02-17 15:51:16'),(9,'OliverB','oliver.bennett@email.com','OllyB@789','Oliver','Bennett','2002-06-23','Tech geek and aspiring chef.',NULL,'2026-02-17 15:51:16'),(10,'MiaK','mia.keller@email.com','MiaK!321','Mia','Keller','2004-01-30','Love hiking, photography, and music.',NULL,'2026-02-17 15:51:16'),(11,'NoahS','noah.smith@email.com','NoahS2023!','Noah','Smith','2001-10-14','Soccer player and coffee lover.',NULL,'2026-02-17 15:51:16'),(12,'AvaL','ava.larson@email.com','AvaL@456','Ava','Larson','2003-04-07','Artist and bookworm.',NULL,'2026-02-17 15:51:16'),(13,'EthanH','ethan.hughes@email.com','EthanH!12','Ethan','Hughes','2000-08-20','Loves coding and sci-fi movies.',NULL,'2026-02-17 15:51:16'),(14,'IslaM','isla.morris@email.com','IslaM#99','Isla','Morris','2002-02-11','Foodie and travel addict.',NULL,'2026-02-17 15:51:16'),(15,'LucasF','lucas.foster@email.com','LucasF2024$','Lucas','Foster','2001-05-27','Guitarist and podcast fan.',NULL,'2026-02-17 15:51:16'),(16,'ZoeW','zoe.walsh@email.com','ZoeW!1234','Zoe','Walsh','2003-07-18','Animal lover and blogger.',NULL,'2026-02-17 15:51:16'),(17,'JackC','jack.cole@email.com','JackC#2023','Jack','Cole','2000-09-05','Cyclist and coffee enthusiast.',NULL,'2026-02-17 15:51:16'),(18,'ChloeB','chloe.barnes@email.com','ChloeB!45','Chloe','Barnes','2004-12-22','Dancer and music fan.',NULL,'2026-02-17 15:51:16'),(19,'RyanD','ryan.davis@email.com','RyanD@321','Ryan','Davis','2002-03-30','Tech lover and gamer.',NULL,'2026-02-17 15:51:16'),(20,'SophiaJ','sophia.jenkins@email.com','SophiaJ!78','Sophia','Jenkins','2003-11-13','Enjoys painting and reading.',NULL,'2026-02-17 15:51:16'),(21,'MasonT','mason.thomas@email.com','MasonT@99','Mason','Thomas','2001-01-19','Basketball player and movie fan.',NULL,'2026-02-17 15:51:16'),(22,'LilaP','lila.parker@email.com','LilaP!2024','Lila','Parker','2004-06-06','Coffee addict and blogger.',NULL,'2026-02-17 15:51:16'),(23,'LeoK','leo.kane@email.com','LeoK#123','Leo','Kane','2002-09-29','Love tech gadgets and gaming.',NULL,'2026-02-17 15:51:16'),(24,'EllaS','ella.sanders@email.com','EllaS!456','Ella','Sanders','2003-05-03','Writer and music lover.',NULL,'2026-02-17 15:51:16'),(25,'HenryB','henry.bradley@email.com','HenryB@2023','Henry','Bradley','2001-12-10','Runner and coffee enthusiast.',NULL,'2026-02-17 15:51:16'),(26,'MayaH','maya.henderson@email.com','MayaH#789','Maya','Henderson','2004-08-15','Foodie and traveler.',NULL,'2026-02-17 15:51:16'),(27,'OwenR','owen.richards@email.com','OwenR!2024','Owen','Richards','2000-04-04','Loves coding and basketball.',NULL,'2026-02-17 15:51:16'),(28,'ZaraL','zara.lawson@email.com','ZaraL@123','Zara','Lawson','2002-11-25','Photographer and writer.',NULL,'2026-02-17 15:51:16'),(29,'EliM','eli.morris@email.com','EliM!321','Eli','Morris','2003-02-17','Gamer and podcast fan.',NULL,'2026-02-17 15:51:16'),(30,'NinaF','nina.foster@email.com','NinaF!456','Nina','Foster','2001-07-07','Loves reading and traveling.',NULL,'2026-02-17 15:51:16');
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

-- Dump completed on 2026-02-17 15:51:44
