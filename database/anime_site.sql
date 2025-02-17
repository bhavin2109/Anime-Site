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
  `anime_name` varchar(255) NOT NULL,
  `anime_image` varchar(255) NOT NULL,
  `anime_type` varchar(100) NOT NULL,
  `genre` varchar(100) NOT NULL,
  PRIMARY KEY (`anime_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anime`
--

LOCK TABLES `anime` WRITE;
/*!40000 ALTER TABLE `anime` DISABLE KEYS */;
INSERT INTO `anime` VALUES (1,'Dan Da Dan','Dan Da Dan - S01.jpg','TV','Action'),(2,'Your Name','your name.jpg','Movie','Fantasy'),(3,'Wind Breaker','Wind Breaker - S01.jpeg','TV','Action'),(4,'I want to Eat Your Pancreas','i want to eat your pancreas.jpg','Movie','Romance'),(5,'Black Clover','Black Clover.jpeg','TV','Fantasy'),(6,'Bleach','Bleach.jpeg','TV','Shonen'),(7,'Jujutsu Kaisen','Jujutsu Kaisen - S01.jpeg','TV','Action'),(8,'Chainsaw Man','Chainsaw Man - S01.jpeg','TV','Action'),(9,'Demon Slayer','Demon Slayer - Kimetsu no Yaiba.jpg','TV','Action'),(10,'Steins Gate','steins gate.jpg','TV','Isekai'),(12,'One Piece','OnePiece.jpg','TV','Adventure'),(13,'Kaiju No.8','kaiju no.8.jpg','TV','Action'),(14,'Tokyo Revengers','Tokyo Revengers - S01.jpeg','TV','Action'),(15,'Spy X Family','Spy x Family - S01.jpeg','TV','Comedy'),(16,'Blue Lock','blue lock.jpg','TV','Sports'),(17,'Sword Art Online','sword art online.jpg','TV','Isekai'),(18,'Uzumaki: Spiral into Horror','uzumaki.jpg','TV','Seinen'),(19,'Attack on Titan','attack on titan.jpg','TV','Action'),(20,'Weathering With You','weathering with you.jpg','Movie','Romance'),(21,'A Silent Voice','a silent voice.jpg','Movie','Drama'),(22,'Solo Leveling','solo leveling.png','TV','Action'),(23,'Solo Leveling Season 2: Arise from the Shadow','solo leveling s2.jpg','TV','Action'),(24,'Tokyo Ghoul','tokyo ghoul.jpg','TV','Seinen'),(25,'Tokyo Ghoul √A','tokyo ghoul a.jpg','TV','Seinen'),(26,'Tokyo Ghoul:re','tokyo ghoul re.jpg','TV','Seinen'),(27,'Tokyo Ghoul:re Part - 2','tokyo ghoul re part - 2.jpg','TV','Seinen'),(28,'The Garden of Words','the garden of words.jpg','Movie','Romance'),(29,'Pluto','pluto.jpg','ONA','Psychological'),(30,'Jujutsu Kaisen 2nd Season','jujutsu kaisen s2.png','TV','Action'),(31,'Jujutsu Kaisen 0 Movie','jujutsu kaisen 0 movie.jpg','Movie','Action'),(32,'Devilman: Crybaby','devilman crybaby.jpg','ONA','Action'),(33,'Suzume','suzume.jpg','Movie','Fantasy'),(34,'Into the Forest of Fireflies Light','Into the Forest of Fireflies Light.jpg','Movie','Romance'),(35,'Frieren Beyond Journeys End','frieren beyond journeys end.jpg','TV','Adventure'),(36,'Akira','akira.jpg','Movie','Action'),(37,'Whisper of the Heart','Whisper of the Heart.jpg','Movie','Romance'),(38,'Horimiya','horimiya.jpg','TV','Romance'),(39,'Horimiya: The Missing Pieces','Horimiya The Missing Pieces.png','TV','Romance'),(40,'Sakomoto Days','sakomoto days.jpg','TV','Action'),(41,'Naruto','naruto.jpg','TV','Shounen'),(42,'Naruto Shippuden','naruto shippuden.jpg','TV','Shounen'),(43,'Boruto: Naruto Next Generations','boruto.jpg','TV','Shounen'),(44,'The Last: Naruto the Movie','the last - naruto the movie.jpg','Movie','Shounen');
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
  `episode_url` text DEFAULT NULL,
  PRIMARY KEY (`episode_id`),
  KEY `anime_id` (`anime_id`),
  CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`anime_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `episodes`
--

LOCK TABLES `episodes` WRITE;
/*!40000 ALTER TABLE `episodes` DISABLE KEYS */;
INSERT INTO `episodes` VALUES (1,1,'1o-6-aR4eGyxbmF2PJ3XT5M9EV1J_STm0'),(2,2,'1yeirUYkEhVPXSWq-xhA7hlhCxqzWLbM3'),(3,1,'1yet2FV8rDV5HZkW7c85K0rwT0jWHt2Eu'),(4,1,'1bf4sF5X2xdxgefbRkxCuEoOpVdbE5mft'),(5,1,'1rU2kuj0NdPMtlXHoNit903Hw5Nbiv3J_'),(6,3,'1rTmSckwKJgPN6gzkTnxaONXjyewYImAT'),(7,3,'1LM9RJeEm4CIgsuM59GA3KpB8SCHJgsZK'),(8,1,'1nHeKg_qCj7cBiIfRRtuZJ74WWQKxJul0'),(9,1,'1VC9XiH6SpzhXOFkmLEfaWIROBUXUAmQh'),(10,1,'1dBKCbFpPpLCIP8FLjVtpFs8v8VfD5iIe'),(11,1,'15nbbvIs5a21SjEQwiFGC5w3NmdyINtB0'),(12,1,'17iQluKMENdhLPX_nvPnB4aRRKkOc1L7B'),(13,1,'1XWfgC6kwaLGO-wqbuL0PWZre0-M3KTy3'),(14,1,'1Z8OHWKK7lnfS8ZAZ70kCDfLzUKnu5pOz'),(15,1,'1qxp8kNM3O_buJEGy6w-25uFG9ZlLbuOZ'),(16,4,'16581f-XHkRy8Y4R4TFd4ZXtEbPPdJup4'),(17,5,'17-Lv_cY9MSrMK098AXgRNCLbP7KM2fJw'),(18,6,'12N8KdEiVdkI6vgJ_yeJ-utl1IhE5IsLf'),(19,12,'1nqcISCX9jjsd6ihKR_Ux6fJ2VQO-8ZqE'),(20,12,'1_EACuxrz8fl6JnbdNCAysfzCXdNJ44Ts'),(21,21,'1JVk2Ik4NAxBG6zsq2UIiAo94FkkFAXO0'),(22,36,'1zbdAvNsVQtcXF97KUBr0hF8AIIfk1UwU'),(23,34,'1EY4rfdxpqRTPzKeSGzOn4emRqdxPH68u'),(24,20,'121DyPKPudza_L7yqUWiV6NwuNtXlOj8p'),(25,37,'1EGKpVV_fWyGOk4k1nJtOyhIvN1Czm7bi');
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider`
--

LOCK TABLES `slider` WRITE;
/*!40000 ALTER TABLE `slider` DISABLE KEYS */;
INSERT INTO `slider` VALUES (5,8,'chainsaw man.jpeg',0),(6,13,'KaijuNo8-UW-LTR.jpeg',0),(7,12,'one piece.jpg',0),(8,9,'demon slayer.jpg',0),(9,16,'blue lock s2.jpeg',0),(10,17,'sword art online.png',0),(11,6,'bleach uw.jpg',0);
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

-- Dump completed on 2025-02-17 13:57:02
