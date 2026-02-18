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
-- Table structure for table `admin_actions`
--

DROP TABLE IF EXISTS `admin_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_actions` (
  `action_id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `target_id` int NOT NULL,
  `action_taken` enum('Banned','Suspended','Warning','N/A') NOT NULL,
  `reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_actions`
--

LOCK TABLES `admin_actions` WRITE;
/*!40000 ALTER TABLE `admin_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_actions` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interests`
--

LOCK TABLES `interests` WRITE;
/*!40000 ALTER TABLE `interests` DISABLE KEYS */;
INSERT INTO `interests` VALUES (1,'Backpacking'),(2,'Solo Travel'),(3,'Luxury Travel'),(4,'Road Trips'),(5,'City Breaks'),(6,'Beach Holidays'),(7,'Mountain Hiking'),(8,'Camping'),(9,'Van Life'),(10,'Island Hopping'),(11,'Cultural Tours'),(12,'Food Tourism'),(13,'Photography'),(14,'Adventure Travel'),(15,'Skiing'),(16,'Snowboarding'),(17,'Surfing'),(18,'Scuba Diving'),(19,'Snorkeling'),(20,'Safari Trips'),(21,'Historical Sites'),(22,'Museums'),(23,'Architecture'),(24,'Street Food'),(25,'Fine Dining'),(26,'Wine Tasting'),(27,'Coffee Culture'),(28,'Local Markets'),(29,'Festival Travel'),(30,'Cruise Holidays'),(31,'Nature Walks'),(32,'National Parks'),(33,'Waterfalls'),(34,'Sunsets'),(35,'Sunrises'),(36,'Travel Blogging'),(37,'Budget Travel'),(38,'Digital Nomad Life'),(39,'Language Learning'),(40,'Exchange Programs'),(41,'Train Travel'),(42,'Motorbike Trips'),(43,'Glamping'),(44,'Eco Tourism'),(45,'Wildlife Watching'),(46,'Stargazing'),(47,'Sailing'),(48,'Fishing Trips'),(49,'Kayaking'),(50,'Rock Climbing'),(51,'Yoga Retreats'),(52,'Spa Retreats'),(53,'Volunteering Abroad'),(54,'Study Abroad'),(55,'Travel Photography'),(56,'Drone Photography'),(57,'Travel Vlogging'),(58,'Theme Parks'),(59,'Road Cycling'),(60,'Running'),(61,'Fitness'),(62,'Gym'),(63,'Gaming'),(64,'PC Gaming'),(65,'Console Gaming'),(66,'Board Games'),(67,'Reading'),(68,'Fiction Books'),(69,'Non-fiction Books'),(70,'Writing'),(71,'Blogging'),(72,'Movies'),(73,'TV Series'),(74,'Anime'),(75,'Music Festivals'),(76,'Concerts'),(77,'Cooking'),(78,'Baking'),(79,'Tech'),(80,'Coding'),(81,'Startups'),(82,'Entrepreneurship'),(83,'Investing'),(84,'Cryptocurrency'),(85,'Art'),(86,'Painting'),(87,'Drawing'),(88,'Fashion'),(89,'Photography Editing'),(90,'Podcasts'),(91,'Meditation'),(92,'Mindfulness'),(93,'Self Development'),(94,'Basketball'),(95,'Football'),(96,'Tennis'),(97,'Swimming'),(98,'Martial Arts'),(99,'Cars'),(100,'Motorbikes');
/*!40000 ALTER TABLE `interests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_interests`
--

DROP TABLE IF EXISTS `user_interests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_interests` (
  `user_id` int NOT NULL,
  `interest_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`interest_id`),
  KEY `interest_id` (`interest_id`),
  CONSTRAINT `user_interests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_interests_ibfk_2` FOREIGN KEY (`interest_id`) REFERENCES `interests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_interests`
--

LOCK TABLES `user_interests` WRITE;
/*!40000 ALTER TABLE `user_interests` DISABLE KEYS */;
INSERT INTO `user_interests` VALUES (3,1),(2,2),(27,2),(16,3),(5,4),(2,5),(14,5),(20,8),(25,8),(15,9),(28,9),(6,12),(9,12),(13,12),(22,12),(1,15),(25,15),(2,17),(13,17),(24,17),(8,18),(21,18),(22,18),(5,19),(18,23),(4,24),(11,24),(15,24),(3,25),(4,26),(14,26),(2,27),(23,29),(14,30),(23,30),(12,33),(13,33),(21,33),(15,34),(7,35),(3,37),(16,38),(8,42),(10,42),(11,43),(30,43),(1,44),(10,44),(9,45),(12,46),(11,48),(1,49),(14,49),(25,49),(7,50),(15,50),(30,50),(7,51),(7,52),(20,52),(5,53),(15,53),(19,56),(28,56),(4,57),(13,57),(16,59),(4,60),(29,61),(2,63),(21,63),(4,64),(28,64),(18,65),(12,66),(24,66),(20,67),(25,67),(26,67),(8,69),(17,69),(19,69),(9,72),(24,74),(26,74),(24,75),(22,76),(24,76),(12,77),(29,78),(21,80),(13,81),(27,82),(9,84),(12,85),(25,86),(6,89),(17,89),(20,89),(5,91),(17,91),(11,94),(21,95),(22,95),(7,96),(11,98),(18,99);
/*!40000 ALTER TABLE `user_interests` ENABLE KEYS */;
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

-- Dump completed on 2026-02-18 22:36:07
