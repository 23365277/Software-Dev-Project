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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` enum('LOGIN','LOGOUT','LIKE','MESSAGE','PROFILE_UPDATE','OTHER') DEFAULT NULL,
  `reference_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

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
  `action_taken` enum('BANNED','SUSPENDED','WARNING') DEFAULT NULL,
  `reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`action_id`),
  KEY `fk_admin` (`admin_id`),
  KEY `fk_target` (`target_id`),
  CONSTRAINT `fk_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_target` FOREIGN KEY (`target_id`) REFERENCES `users` (`id`)
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
-- Table structure for table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blocks` (
  `blocker_id` int NOT NULL,
  `blocked_id` int NOT NULL,
  `blocked_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`blocker_id`,`blocked_id`),
  KEY `blocked_id` (`blocked_id`),
  CONSTRAINT `blocks_ibfk_1` FOREIGN KEY (`blocker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blocks_ibfk_2` FOREIGN KEY (`blocked_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocks`
--

LOCK TABLES `blocks` WRITE;
/*!40000 ALTER TABLE `blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks` ENABLE KEYS */;
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
  PRIMARY KEY (`sender_id`,`receiver_id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `unique_like` (`sender_id`,`receiver_id`),
  KEY `fk_likes_receiver` (`receiver_id`),
  CONSTRAINT `fk_likes_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_likes_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
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
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matches` (
  `match_id` int NOT NULL AUTO_INCREMENT,
  `user1_id` int NOT NULL,
  `user2_id` int NOT NULL,
  `matched_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`match_id`),
  UNIQUE KEY `user1_id` (`user1_id`,`user2_id`),
  KEY `user2_id` (`user2_id`),
  CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`),
  CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matches`
--

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;
/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
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
  `match_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `fk_messages_match` (`match_id`),
  CONSTRAINT `fk_messages_match` FOREIGN KEY (`match_id`) REFERENCES `matches` (`match_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_messages_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
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
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` enum('LIKE','MATCH','MESSAGE','ADMIN_ACTION','OTHER') NOT NULL,
  `reference_id` int DEFAULT NULL,
  `content` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `photos` (
  `photo_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`photo_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos`
--

LOCK TABLES `photos` WRITE;
/*!40000 ALTER TABLE `photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles` (
  `user_id` int NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('MALE','FEMALE','OTHER') DEFAULT NULL,
  `bio` text,
  `height_cm` int DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `looking_for` enum('FRIENDSHIP','CASUAL','RELATIONSHIP') DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (83,'Elizabeth','Martin','1973-12-20','OTHER','Enjoys reading, hiking, and good music.',162,'San Jose','Netherlands','RELATIONSHIP','https://i.pravatar.cc/150?img=24','2025-10-06 15:16:22'),(84,'Jennifer','Martin','1972-09-04','FEMALE','Always curious and learning new things.',173,'Dallas','Spain','RELATIONSHIP','https://i.pravatar.cc/150?img=14','2025-08-08 15:16:22'),(85,'Michael','Anderson','1999-07-13','MALE','Love adventures and meeting new people.',171,'Los Angeles','Sweden','CASUAL','https://i.pravatar.cc/150?img=32','2026-02-13 15:16:23'),(86,'Thomas','White','1996-03-01','MALE','Love adventures and meeting new people.',198,'Los Angeles','Spain','RELATIONSHIP','https://i.pravatar.cc/150?img=19','2025-11-04 15:16:23'),(87,'Richard','Thomas','2007-12-25','MALE','Looking for meaningful connections.',154,'Dallas','UK','RELATIONSHIP','https://i.pravatar.cc/150?img=32','2026-01-31 15:16:23'),(88,'James','White','1991-04-21','MALE','Tech enthusiast and coffee lover.',192,'Dallas','France','RELATIONSHIP','https://i.pravatar.cc/150?img=43','2025-08-19 15:16:23'),(89,'Charles','Harris','1996-04-26','OTHER','Love adventures and meeting new people.',172,'San Antonio','Italy','CASUAL','https://i.pravatar.cc/150?img=23','2026-01-24 15:16:23'),(90,'Barbara','Thomas','1990-04-08','OTHER','Enjoys reading, hiking, and good music.',189,'Phoenix','Italy','RELATIONSHIP','https://i.pravatar.cc/150?img=23','2025-03-29 15:16:23'),(91,'Sarah','Harris','1970-05-26','OTHER','Always curious and learning new things.',187,'Phoenix','Germany','FRIENDSHIP','https://i.pravatar.cc/150?img=5','2025-08-08 15:16:23'),(92,'Elizabeth','Jackson','2008-06-16','OTHER','Looking for meaningful connections.',175,'Dallas','Germany','CASUAL','https://i.pravatar.cc/150?img=6','2025-03-29 15:16:23'),(93,'Robert','Harris','1968-08-02','MALE','Love adventures and meeting new people.',156,'San Diego','Italy','FRIENDSHIP','https://i.pravatar.cc/150?img=70','2025-03-17 15:16:23'),(94,'David','Anderson','1985-10-04','OTHER','Always curious and learning new things.',157,'Houston','Australia','CASUAL','https://i.pravatar.cc/150?img=50','2026-02-03 15:16:23'),(95,'Karen','White','2003-10-13','OTHER','Always curious and learning new things.',188,'Philadelphia','Germany','CASUAL','https://i.pravatar.cc/150?img=31','2025-07-23 15:16:23'),(96,'Joseph','Martin','1969-01-09','MALE','Enjoys reading, hiking, and good music.',176,'Philadelphia','Spain','RELATIONSHIP','https://i.pravatar.cc/150?img=32','2025-07-26 15:16:23'),(97,'Mary','Johnson','1994-10-05','OTHER','Enjoys reading, hiking, and good music.',151,'Philadelphia','USA','CASUAL','https://i.pravatar.cc/150?img=65','2025-03-14 15:16:23'),(98,'Thomas','Jackson','1991-07-09','MALE','Love adventures and meeting new people.',194,'Houston','Australia','CASUAL','https://i.pravatar.cc/150?img=17','2025-12-15 15:16:23'),(99,'Richard','Thomas','1976-07-21','MALE','Enjoys reading, hiking, and good music.',155,'San Jose','Canada','RELATIONSHIP','https://i.pravatar.cc/150?img=7','2025-10-18 15:16:23'),(100,'James','Johnson','1993-04-14','MALE','Enjoys reading, hiking, and good music.',194,'New York','Canada','FRIENDSHIP','https://i.pravatar.cc/150?img=39','2025-04-05 15:16:23'),(101,'Jessica','Johnson','1973-03-23','FEMALE','Love adventures and meeting new people.',188,'Los Angeles','USA','CASUAL','https://i.pravatar.cc/150?img=11','2025-11-01 15:16:23'),(102,'James','Anderson','1984-01-19','MALE','Fun, outgoing, and spontaneous.',190,'Los Angeles','UK','CASUAL','https://i.pravatar.cc/150?img=5','2025-09-15 15:16:23'),(103,'Patricia','Thomas','1968-09-13','OTHER','Enjoys reading, hiking, and good music.',170,'San Jose','Sweden','FRIENDSHIP','https://i.pravatar.cc/150?img=9','2025-07-30 15:16:23'),(104,'Sarah','Taylor','1983-03-17','OTHER','Enjoys reading, hiking, and good music.',176,'Dallas','France','FRIENDSHIP','https://i.pravatar.cc/150?img=32','2025-04-07 15:16:23'),(105,'Susan','Harris','1972-06-15','OTHER','Fun, outgoing, and spontaneous.',174,'Los Angeles','Italy','RELATIONSHIP','https://i.pravatar.cc/150?img=68','2025-05-10 15:16:24'),(106,'Mary','Anderson','1982-04-18','FEMALE','Enjoys reading, hiking, and good music.',183,'Philadelphia','UK','RELATIONSHIP','https://i.pravatar.cc/150?img=44','2025-04-02 15:16:24'),(107,'Elizabeth','Martin','1981-09-01','OTHER','Love adventures and meeting new people.',187,'Los Angeles','UK','FRIENDSHIP','https://i.pravatar.cc/150?img=22','2026-02-08 15:16:24'),(108,'Thomas','White','2005-02-24','OTHER','Always curious and learning new things.',200,'Chicago','Australia','CASUAL','https://i.pravatar.cc/150?img=43','2025-12-13 15:16:24'),(109,'Elizabeth','Smith','1987-11-24','FEMALE','Fun, outgoing, and spontaneous.',151,'Chicago','Netherlands','FRIENDSHIP','https://i.pravatar.cc/150?img=11','2025-02-25 15:16:24'),(110,'John','Johnson','1976-11-07','OTHER','Looking for meaningful connections.',186,'Phoenix','Germany','FRIENDSHIP','https://i.pravatar.cc/150?img=55','2025-08-11 15:16:24'),(111,'James','Johnson','1998-03-14','OTHER','Love adventures and meeting new people.',174,'San Antonio','Netherlands','RELATIONSHIP','https://i.pravatar.cc/150?img=11','2025-03-24 15:16:24'),(112,'Sarah','Harris','1968-01-04','FEMALE','Fun, outgoing, and spontaneous.',198,'San Jose','Sweden','CASUAL','https://i.pravatar.cc/150?img=26','2025-06-26 15:16:24'),(113,'Barbara','Johnson','1982-08-04','FEMALE','Love adventures and meeting new people.',188,'Los Angeles','Netherlands','FRIENDSHIP','https://i.pravatar.cc/150?img=65','2025-04-27 15:16:24'),(114,'David','Thomas','2001-11-06','MALE','Enjoys reading, hiking, and good music.',150,'Houston','Canada','CASUAL','https://i.pravatar.cc/150?img=57','2025-12-25 15:16:24'),(115,'Jessica','Thomas','1978-04-25','FEMALE','Looking for meaningful connections.',165,'San Jose','Italy','RELATIONSHIP','https://i.pravatar.cc/150?img=35','2025-10-22 15:16:24'),(116,'Jennifer','Brown','1996-05-15','FEMALE','Fun, outgoing, and spontaneous.',174,'San Jose','Sweden','CASUAL','https://i.pravatar.cc/150?img=69','2025-06-26 15:16:24'),(117,'David','Martin','2000-07-25','MALE','Tech enthusiast and coffee lover.',173,'San Jose','Sweden','CASUAL','https://i.pravatar.cc/150?img=66','2025-06-08 15:16:24'),(118,'Sarah','Taylor','1998-11-14','OTHER','Looking for meaningful connections.',161,'Houston','Spain','CASUAL','https://i.pravatar.cc/150?img=46','2025-12-08 15:16:24'),(119,'Jessica','White','1999-12-14','OTHER','Fun, outgoing, and spontaneous.',186,'Chicago','Australia','RELATIONSHIP','https://i.pravatar.cc/150?img=4','2025-12-09 15:16:24'),(120,'Linda','Taylor','2007-06-05','FEMALE','Always curious and learning new things.',175,'Los Angeles','Sweden','FRIENDSHIP','https://i.pravatar.cc/150?img=1','2025-10-02 15:16:24'),(121,'Charles','Anderson','1980-07-01','OTHER','Enjoys reading, hiking, and good music.',162,'Philadelphia','Italy','FRIENDSHIP','https://i.pravatar.cc/150?img=39','2025-07-04 15:16:24'),(122,'David','Thomas','1983-10-01','OTHER','Fun, outgoing, and spontaneous.',187,'Houston','UK','CASUAL','https://i.pravatar.cc/150?img=17','2025-10-07 15:16:24'),(123,'John','Johnson','1977-08-18','OTHER','Love adventures and meeting new people.',158,'Los Angeles','Canada','RELATIONSHIP','https://i.pravatar.cc/150?img=5','2025-07-23 15:16:24'),(124,'Elizabeth','Brown','1993-04-23','FEMALE','Enjoys reading, hiking, and good music.',174,'Los Angeles','Italy','CASUAL','https://i.pravatar.cc/150?img=20','2025-06-29 15:16:24'),(125,'Jennifer','Johnson','1986-04-18','OTHER','Looking for meaningful connections.',188,'Dallas','Italy','FRIENDSHIP','https://i.pravatar.cc/150?img=70','2026-01-21 15:16:24'),(126,'David','White','2003-08-24','OTHER','Looking for meaningful connections.',157,'Los Angeles','France','FRIENDSHIP','https://i.pravatar.cc/150?img=32','2025-10-04 15:16:25'),(127,'Karen','Taylor','1976-07-09','OTHER','Love adventures and meeting new people.',165,'Los Angeles','UK','FRIENDSHIP','https://i.pravatar.cc/150?img=61','2025-10-08 15:16:25'),(128,'Jessica','Brown','1998-11-08','FEMALE','Love adventures and meeting new people.',196,'Philadelphia','Italy','RELATIONSHIP','https://i.pravatar.cc/150?img=44','2025-06-28 15:16:25'),(129,'William','Jackson','1983-05-25','MALE','Tech enthusiast and coffee lover.',171,'Houston','Spain','FRIENDSHIP','https://i.pravatar.cc/150?img=63','2025-10-15 15:16:25'),(130,'Jennifer','Smith','1992-09-07','OTHER','Tech enthusiast and coffee lover.',195,'San Antonio','Canada','FRIENDSHIP','https://i.pravatar.cc/150?img=58','2025-09-02 15:16:25'),(131,'Michael','Jackson','1973-12-14','MALE','Always curious and learning new things.',181,'San Antonio','France','CASUAL','https://i.pravatar.cc/150?img=31','2025-12-13 15:16:25'),(132,'James','Thomas','1989-03-05','MALE','Love adventures and meeting new people.',156,'San Diego','Spain','FRIENDSHIP','https://i.pravatar.cc/150?img=52','2026-01-10 15:16:25');
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `report_id` int NOT NULL AUTO_INCREMENT,
  `reporter_id` int NOT NULL,
  `reported_id` int NOT NULL,
  `reason` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`),
  KEY `reporter_id` (`reporter_id`),
  KEY `reported_id` (`reported_id`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`reported_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
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
  CONSTRAINT `fk_ui_interest` FOREIGN KEY (`interest_id`) REFERENCES `interests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ui_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_interests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_interests_ibfk_2` FOREIGN KEY (`interest_id`) REFERENCES `interests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_interests`
--

LOCK TABLES `user_interests` WRITE;
/*!40000 ALTER TABLE `user_interests` DISABLE KEYS */;
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
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `account_status` enum('ACTIVE','BANNED','SUSPENDED') DEFAULT 'ACTIVE',
  `last_login` timestamp NULL DEFAULT NULL,
  `role` enum('USER','ADMIN') DEFAULT 'USER',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (83,'Elizabeth.M699','elizabeth.m699@gmail.com','$2y$10$kV1mFwH/ZN7EH6JdhSBHqe0JysdQcJhC1PLvsP31Ebw7/zYIQhWza','2025-10-06 15:16:22','ACTIVE',NULL,'USER'),(84,'Jennifer.M324','jennifer.m324@gmail.com','$2y$10$142Q5qN7AAbQZdDyTccV7ugNKHNfJ9KM5GeFvhXI2hn9FdhjM0rUy','2025-08-08 15:16:22','ACTIVE',NULL,'USER'),(85,'Michael.A877','michael.a877@gmail.com','$2y$10$6hWxpLQdXldGCoO070JoaO8t8KhRNDgqq4TTd8v1V9aqg6QvJ5yNC','2026-02-13 15:16:23','ACTIVE',NULL,'USER'),(86,'Thomas.W371','thomas.w371@yahoo.com','$2y$10$YopQ452tWN6tgwiVKFw0lOIKJtjp53PlTRqVVe9QOXfI3wP5wd..O','2025-11-04 15:16:23','ACTIVE',NULL,'USER'),(87,'Richard.T213','richard.t213@hotmail.com','$2y$10$r1XZohAtjolYAb/7nBrl5eev9CO1Dyr.Uneng8CYAV4jK/tWXdY/O','2026-01-31 15:16:23','ACTIVE',NULL,'USER'),(88,'James.W635','james.w635@gmail.com','$2y$10$.qN3t4uBfqngHiS1w/DVm.uEpS.ig.Y3Y38Ql64TiTR9FC/0sjbd.','2025-08-19 15:16:23','ACTIVE',NULL,'USER'),(89,'Charles.H294','charles.h294@gmail.com','$2y$10$9tj/fDvaq4Yh/iR/wKsi6ePE8LsC49uY6fRCGnfV6jE6nXHJGtMMK','2026-01-24 15:16:23','ACTIVE',NULL,'USER'),(90,'Barbara.T167','barbara.t167@yahoo.com','$2y$10$2xqoSBhSmshldxtIeQzU5uUKznCDFWU3Thwi9.R9pDsPIcqcG6P.W','2025-03-29 15:16:23','ACTIVE',NULL,'USER'),(91,'Sarah.H524','sarah.h524@outlook.com','$2y$10$mWEmmy2qpba9cm59LWH3/uRuMGYeCzKG2hqYIMmvFOUpUEldCHfaS','2025-08-08 15:16:23','ACTIVE',NULL,'USER'),(92,'Elizabeth.J136','elizabeth.j136@yahoo.com','$2y$10$pT78S.D5UlIBU74npQPK3uMhTvnK1oMxhi/DeYdGVQ8ed8juUptGu','2025-03-29 15:16:23','ACTIVE',NULL,'USER'),(93,'Robert.H138','robert.h138@yahoo.com','$2y$10$UWa7UH8d5v7BSt/yrdyBf.hb3skJ5xyXA0U77Kd/wAUoUz104bYAe','2025-03-17 15:16:23','ACTIVE',NULL,'USER'),(94,'David.A545','david.a545@yahoo.com','$2y$10$ACSqXmFcYQirY60ySKxnwe9FWfoqvaMVKzx8Bgm3tli7pu.VcEaT.','2026-02-03 15:16:23','ACTIVE',NULL,'USER'),(95,'Karen.W286','karen.w286@outlook.com','$2y$10$m9h/C9/pYEU0ExRSWuaut.8M.XaBKtOl2lZrCY/uDDGpksSSBTLyO','2025-07-23 15:16:23','ACTIVE',NULL,'USER'),(96,'Joseph.M964','joseph.m964@hotmail.com','$2y$10$A8sE6gzIL65g52wA3vFwPuEOtn8oZCdiS6SN5jvN8a4pOxQK02E5m','2025-07-26 15:16:23','ACTIVE',NULL,'USER'),(97,'Mary.J608','mary.j608@yahoo.com','$2y$10$DXWsqtynzurZ5tjNfYU4xuAobax/UIcTxUH2LD4TI5zCizQJsdgqq','2025-03-14 15:16:23','ACTIVE',NULL,'USER'),(98,'Thomas.J508','thomas.j508@gmail.com','$2y$10$30Vp3/TdAyfdlhakw7riJuHC.PcUHmuhni5SXE/URjT5rFTjKue3S','2025-12-15 15:16:23','ACTIVE',NULL,'USER'),(99,'Richard.T797','richard.t797@yahoo.com','$2y$10$CalgS5/XNZANb3EB2RlNy.AroeQQ/9MPuelLEVipBQXE/xDlV97Le','2025-10-18 15:16:23','ACTIVE',NULL,'USER'),(100,'James.J805','james.j805@gmail.com','$2y$10$6zPMiD5WFAs1lCVhuJ807.doNCNEOgf.ezzOHdlVVF4Gw5b2ErJ0a','2025-04-05 15:16:23','ACTIVE',NULL,'USER'),(101,'Jessica.J866','jessica.j866@hotmail.com','$2y$10$14x9jbr63yy6hlOhDR5HWOEFTzgTOXHzrqYMH5AAG02GZX9LWaBaq','2025-11-01 15:16:23','ACTIVE',NULL,'USER'),(102,'James.A511','james.a511@outlook.com','$2y$10$AHEpMxVBx.o251jtYKZUUuaRvSreQnz6IvE.5/o8pf4.rJ0QZQTcy','2025-09-15 15:16:23','ACTIVE',NULL,'USER'),(103,'Patricia.T760','patricia.t760@outlook.com','$2y$10$dAkbWqDodDloxIooyDVOuegls5XthL2.eiNIOUaVzFlddBeYCCDpW','2025-07-30 15:16:23','ACTIVE',NULL,'USER'),(104,'Sarah.T169','sarah.t169@yahoo.com','$2y$10$j8BaHo/30AGq4YZRJ0ea1.z3Rw7cM1P6XN4LYFHFb2XAfeebzRY5.','2025-04-07 15:16:23','ACTIVE',NULL,'USER'),(105,'Susan.H61','susan.h61@hotmail.com','$2y$10$5BJNYPYO9eAVXUImJhgQp.k6JVv7/dmTDlzjZyWOx2Cae3t5gm/Ja','2025-05-10 15:16:24','ACTIVE',NULL,'USER'),(106,'Mary.A59','mary.a59@yahoo.com','$2y$10$KfueYczAjIFho.nmoKwVBuuJQ/pBpi4iD098f9NWD6DfU6A1K.Auu','2025-04-02 15:16:24','ACTIVE',NULL,'USER'),(107,'Elizabeth.M301','elizabeth.m301@gmail.com','$2y$10$Zil4o5wFSQMY9yHAXOVcu.BPe.8DwvSMDpx8Ii2lxoDRiPKi6I7l6','2026-02-08 15:16:24','ACTIVE',NULL,'USER'),(108,'Thomas.W996','thomas.w996@outlook.com','$2y$10$l3m9RdJqZW8XOu67H1IQveuWpvZLCNSzLpy5sHOl.W0IDjuPbToEC','2025-12-13 15:16:24','ACTIVE',NULL,'USER'),(109,'Elizabeth.S40','elizabeth.s40@outlook.com','$2y$10$TdcaW554CO1rDbPC6ko9QuIJ/x9QEplh7S/EFgEiho/CSeotK1Evq','2025-02-25 15:16:24','ACTIVE',NULL,'USER'),(110,'John.J935','john.j935@hotmail.com','$2y$10$r8Jok1seNZLjyFPPBs0vDeJ/PwpzUH1FrzWH5MSVNYwLZJG/EStc2','2025-08-11 15:16:24','ACTIVE',NULL,'USER'),(111,'James.J336','james.j336@gmail.com','$2y$10$sbC8VjjRHV0.IluyhSkMWuo.LbATOek3.a7o10sRX5HkfV2vCRG.i','2025-03-24 15:16:24','ACTIVE',NULL,'USER'),(112,'Sarah.H885','sarah.h885@yahoo.com','$2y$10$ozFCiAB6mwUy1qoDfQyBfOfWwDngNqkUvmCDuGtNSWRQL6TgigolC','2025-06-26 15:16:24','ACTIVE',NULL,'USER'),(113,'Barbara.J37','barbara.j37@gmail.com','$2y$10$uYafVePupl8SR6xRM7HsdO3tu8GcnAtWXi38vPN/FKDxAaOOJ79KK','2025-04-27 15:16:24','ACTIVE',NULL,'USER'),(114,'David.T42','david.t42@yahoo.com','$2y$10$XzxC/roplqshnA.1XnU1SeZZ/BE2AVZ5HGIeE6VcWJHRZhIBUhYSi','2025-12-25 15:16:24','ACTIVE',NULL,'USER'),(115,'Jessica.T200','jessica.t200@yahoo.com','$2y$10$hs0J.yM2dnLwzYlAAchHbedwVkxrpPQQg3DidDxHp1q/xFgByK8SS','2025-10-22 15:16:24','ACTIVE',NULL,'USER'),(116,'Jennifer.B48','jennifer.b48@hotmail.com','$2y$10$DLWQVHXcOjQvrdJx0KRUbOxafMzIP/NfPrwJaFAuwkMo/NO/4o6HW','2025-06-26 15:16:24','ACTIVE',NULL,'USER'),(117,'David.M510','david.m510@outlook.com','$2y$10$YA4spBZSkA3NLvCAE7ckVO87tgdiE299sAxKyI1LW3WkEU3ohnNI.','2025-06-08 15:16:24','ACTIVE',NULL,'USER'),(118,'Sarah.T676','sarah.t676@hotmail.com','$2y$10$xgIr3oU7wFpSlIcCuY9yZuOE0U7sqWHuCqS/CDvBM0y8yIWR5jcqq','2025-12-08 15:16:24','ACTIVE',NULL,'USER'),(119,'Jessica.W370','jessica.w370@hotmail.com','$2y$10$81EzoY6F6nmuAjgkxdSFP.RzItzkW2HQQLYT1vrqJlyd1yHl5ou2C','2025-12-09 15:16:24','ACTIVE',NULL,'USER'),(120,'Linda.T969','linda.t969@outlook.com','$2y$10$.PIn3t0yrOwwf7HQo7lmq.swSoT0.kQDsCLGs1BFzsB.9cogLtPl.','2025-10-02 15:16:24','ACTIVE',NULL,'USER'),(121,'Charles.A888','charles.a888@outlook.com','$2y$10$HfbRx16BQGKuHNbdZP7.eOOB07U6l58e9oC9yqAknzU8EqdAG1BmK','2025-07-04 15:16:24','ACTIVE',NULL,'USER'),(122,'David.T705','david.t705@hotmail.com','$2y$10$sw5oTGAKNFqyZNgxyEjDxOkm.ioKJ7Cg1vupNEqudl1O2b6Jb5okq','2025-10-07 15:16:24','ACTIVE',NULL,'USER'),(123,'John.J819','john.j819@gmail.com','$2y$10$HD.OMNav2yyC/9rzhB.oiezmJe1CbHgVvrrT130HaVIZrScQCZ.l.','2025-07-23 15:16:24','ACTIVE',NULL,'USER'),(124,'Elizabeth.B721','elizabeth.b721@outlook.com','$2y$10$aWJVyjd34TXTePrvtDEk5OmHi7/BcECycrjTJo1jOiupFUCx0m3y6','2025-06-29 15:16:24','ACTIVE',NULL,'USER'),(125,'Jennifer.J606','jennifer.j606@yahoo.com','$2y$10$1v4HTxbOiy2OsBr8oO5SZOrjemrPUj5ofpKjsITtWMuW62QxZIIzK','2026-01-21 15:16:24','ACTIVE',NULL,'USER'),(126,'David.W156','david.w156@hotmail.com','$2y$10$bKR85kTsJXi4HxCLCFpuUeR0Z7BsRSpDdk9EoByKkhjejJTwX.7F6','2025-10-04 15:16:25','ACTIVE',NULL,'USER'),(127,'Karen.T387','karen.t387@gmail.com','$2y$10$4gw5JKCzsBsQZTfF86.qqOYzd5lMGN7dNyeIGklNQ3TDoo7XLaF1C','2025-10-08 15:16:25','ACTIVE',NULL,'USER'),(128,'Jessica.B590','jessica.b590@hotmail.com','$2y$10$3dvR9FKa2mZ9m9G9afzUK.fAMdTymSVtU9Z1S7/Ghv8arjYADaQ1G','2025-06-28 15:16:25','ACTIVE',NULL,'USER'),(129,'William.J912','william.j912@outlook.com','$2y$10$yzZLpjMUTW.u7KYF.nTT0ujVFbiTNyIeRYnef2ei5/q4Pq2wtxUI6','2025-10-15 15:16:25','ACTIVE',NULL,'USER'),(130,'Jennifer.S348','jennifer.s348@gmail.com','$2y$10$U3tEx3qy.RPsQbwC7kkOa.90w5.91Sgxq7yxC.7EI7ZlCorght/5i','2025-09-02 15:16:25','ACTIVE',NULL,'USER'),(131,'Michael.J713','michael.j713@gmail.com','$2y$10$sUUx9PEIFunUev9UAce85.MMtMTNOCeevn008L.qPHuOTbcRRD2rO','2025-12-13 15:16:25','ACTIVE',NULL,'USER'),(132,'James.T595','james.t595@gmail.com','$2y$10$0ZJMUoRZzevRSnZ939QLCezLFaehhjrdV.FdNJ3Kdi.hkZYSfsLj.','2026-01-10 15:16:25','ACTIVE',NULL,'USER');
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

-- Dump completed on 2026-02-22 15:18:18
