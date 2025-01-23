-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: anime_site
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
-- Table structure for table `anime`
--

DROP TABLE IF EXISTS `anime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anime` (
  `anime_id` int(100) NOT NULL AUTO_INCREMENT,
  `anime_name` varchar(100) NOT NULL,
  `anime_image` varchar(255) NOT NULL,
  `anime_type` varchar(100) NOT NULL,
  `episodes` int(200) NOT NULL,
  `genre` varchar(100) NOT NULL,
  PRIMARY KEY (`anime_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anime`
--

LOCK TABLES `anime` WRITE;
/*!40000 ALTER TABLE `anime` DISABLE KEYS */;
INSERT INTO `anime` VALUES (1,'Dan Da Dan','Dan Da Dan - S01.jpg','TV',0,'Action'),(2,'Your Name','your name.jpg','Movie',0,'Fantasy'),(3,'Wind Breaker','Wind Breaker - S01.jpeg','TV',0,'Action'),(4,'I want to Eat Your Pancreas','i want to eat your pancreas.jpg','Movie',0,'Romance'),(5,'Black Clover','Black Clover.jpeg','TV',0,'Fantasy'),(6,'Bleach','Bleach.jpeg','TV',0,'Shonen'),(7,'Jujutsu Kaisen','Jujutsu Kaisen - S01.jpeg','TV',0,'Action'),(8,'Chainsaw Man','Chainsaw Man - S01.jpeg','TV',0,'Action'),(9,'Demon Slayer','Demon Slayer - Kimetsu no Yaiba.jpg','TV',0,'Action'),(10,'Steins Gate','steins gate.jpg','TV',0,'Isekai'),(12,'One Piece','OnePiece.jpg','TV',0,'Adventure'),(13,'Kaiju No.8','kaiju no.8.jpg','TV',0,'Action');
/*!40000 ALTER TABLE `anime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `episodes`
--

DROP TABLE IF EXISTS `episodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `episodes` (
  `episode_id` int(11) NOT NULL AUTO_INCREMENT,
  `anime_id` int(11) NOT NULL,
  `episode_title` varchar(255) DEFAULT NULL,
  `episode_url` text DEFAULT NULL,
  PRIMARY KEY (`episode_id`),
  KEY `anime_id` (`anime_id`),
  CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`anime_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `episodes`
--

LOCK TABLES `episodes` WRITE;
/*!40000 ALTER TABLE `episodes` DISABLE KEYS */;
INSERT INTO `episodes` VALUES (1,1,'That\'s How Love Starts, Ya Know!','1o-6-aR4eGyxbmF2PJ3XT5M9EV1J_STm0'),(2,2,'What is your Name ?','1yeirUYkEhVPXSWq-xhA7hlhCxqzWLbM3'),(3,1,'That\'s a Space Alien, Ain\'t It?!','1yet2FV8rDV5HZkW7c85K0rwT0jWHt2Eu'),(4,1,'It\'s a Granny vs Granny Clash!','1bf4sF5X2xdxgefbRkxCuEoOpVdbE5mft'),(5,1,'Kicking Turbo Granny\'s Ass','1rU2kuj0NdPMtlXHoNit903Hw5Nbiv3J_'),(6,3,'Sakura Arrives at Furin','1rTmSckwKJgPN6gzkTnxaONXjyewYImAT'),(7,3,'The Hero of My Dreams','1LM9RJeEm4CIgsuM59GA3KpB8SCHJgsZK'),(8,1,'Like, Where Are Your Balls?','1nHeKg_qCj7cBiIfRRtuZJ74WWQKxJul0'),(9,1,'A Dangerous Woman Arrives','1VC9XiH6SpzhXOFkmLEfaWIROBUXUAmQh'),(10,1,'To a Kinder World','1dBKCbFpPpLCIP8FLjVtpFs8v8VfD5iIe'),(11,1,'I\'ve Got This Funny Feeling','15nbbvIs5a21SjEQwiFGC5w3NmdyINtB0'),(12,1,' Merge! Serpo Dover Demon Nessie!','17iQluKMENdhLPX_nvPnB4aRRKkOc1L7B'),(13,1,'Have You Ever Seen a Cattle Mutilation?','1XWfgC6kwaLGO-wqbuL0PWZre0-M3KTy3'),(14,1,'First Love','1Z8OHWKK7lnfS8ZAZ70kCDfLzUKnu5pOz'),(15,1,'Let\'s Go to the Cursed House','1qxp8kNM3O_buJEGy6w-25uFG9ZlLbuOZ'),(16,4,'I want to Eat Your','16581f-XHkRy8Y4R4TFd4ZXtEbPPdJup4'),(17,5,'Asta and Yuno','17-Lv_cY9MSrMK098AXgRNCLbP7KM2fJw'),(18,6,'The Day I Became a Shinigami','12N8KdEiVdkI6vgJ_yeJ-utl1IhE5IsLf'),(19,12,'I\'m Luffy! The Man Who\'s Gonna Be King of the Pirates!','1nqcISCX9jjsd6ihKR_Ux6fJ2VQO-8ZqE'),(20,12,'Enter the Great Swordsman! Pirate Hunter Roronoa Zoro!','1_EACuxrz8fl6JnbdNCAysfzCXdNJ44Ts');
/*!40000 ALTER TABLE `episodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `highlight_videos`
--

DROP TABLE IF EXISTS `highlight_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `highlight_videos` (
  `video_id` int(100) NOT NULL,
  `video_name` varchar(100) NOT NULL,
  `video_file` varchar(255) NOT NULL,
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `highlight_videos`
--

LOCK TABLES `highlight_videos` WRITE;
/*!40000 ALTER TABLE `highlight_videos` DISABLE KEYS */;
INSERT INTO `highlight_videos` VALUES (1,'Highlight Video','Highlight Video.mp4');
/*!40000 ALTER TABLE `highlight_videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slider`
--

DROP TABLE IF EXISTS `slider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anime_id` int(11) NOT NULL,
  `slider_image` varchar(255) NOT NULL,
  `episodes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anime_id` (`anime_id`),
  CONSTRAINT `slider_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`anime_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider`
--

LOCK TABLES `slider` WRITE;
/*!40000 ALTER TABLE `slider` DISABLE KEYS */;
INSERT INTO `slider` VALUES (1,12,'OnePiece-UW-LTR.jpeg',0),(2,13,'KaijuNo8-UW-LTR.jpeg',0),(3,3,'wind breaker uw.jpg',0),(4,9,'demon slayer uw.jpg',0);
/*!40000 ALTER TABLE `slider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'bhavin','opbhavin21@gmail.com','bhavin2109'),(2,'heet','heetvelani@gmail.com','heet');
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

-- Dump completed on 2025-01-24  1:38:25
