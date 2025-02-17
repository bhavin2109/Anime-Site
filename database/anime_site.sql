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
) ENGINE=InnoDB AUTO_INCREMENT=370 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `episodes`
--

LOCK TABLES `episodes` WRITE;
/*!40000 ALTER TABLE `episodes` DISABLE KEYS */;
INSERT INTO `episodes` VALUES (1,1,'1o-6-aR4eGyxbmF2PJ3XT5M9EV1J_STm0'),(2,2,'1yeirUYkEhVPXSWq-xhA7hlhCxqzWLbM3'),(3,1,'1yet2FV8rDV5HZkW7c85K0rwT0jWHt2Eu'),(4,1,'1bf4sF5X2xdxgefbRkxCuEoOpVdbE5mft'),(5,1,'1rU2kuj0NdPMtlXHoNit903Hw5Nbiv3J_'),(6,3,'1rTmSckwKJgPN6gzkTnxaONXjyewYImAT'),(7,3,'1LM9RJeEm4CIgsuM59GA3KpB8SCHJgsZK'),(8,1,'1nHeKg_qCj7cBiIfRRtuZJ74WWQKxJul0'),(9,1,'1VC9XiH6SpzhXOFkmLEfaWIROBUXUAmQh'),(10,1,'1dBKCbFpPpLCIP8FLjVtpFs8v8VfD5iIe'),(11,1,'15nbbvIs5a21SjEQwiFGC5w3NmdyINtB0'),(12,1,'17iQluKMENdhLPX_nvPnB4aRRKkOc1L7B'),(13,1,'1XWfgC6kwaLGO-wqbuL0PWZre0-M3KTy3'),(14,1,'1Z8OHWKK7lnfS8ZAZ70kCDfLzUKnu5pOz'),(15,1,'1qxp8kNM3O_buJEGy6w-25uFG9ZlLbuOZ'),(16,4,'16581f-XHkRy8Y4R4TFd4ZXtEbPPdJup4'),(17,5,'17-Lv_cY9MSrMK098AXgRNCLbP7KM2fJw'),(18,6,'12N8KdEiVdkI6vgJ_yeJ-utl1IhE5IsLf'),(19,12,'1nqcISCX9jjsd6ihKR_Ux6fJ2VQO-8ZqE'),(20,12,'1_EACuxrz8fl6JnbdNCAysfzCXdNJ44Ts'),(21,21,'1JVk2Ik4NAxBG6zsq2UIiAo94FkkFAXO0'),(22,36,'1zbdAvNsVQtcXF97KUBr0hF8AIIfk1UwU'),(23,34,'1EY4rfdxpqRTPzKeSGzOn4emRqdxPH68u'),(24,20,'121DyPKPudza_L7yqUWiV6NwuNtXlOj8p'),(25,37,'1EGKpVV_fWyGOk4k1nJtOyhIvN1Czm7bi'),(26,19,'1qmTP-goVjiHMMicHpGUunI5_7A90_E6q'),(27,19,'1_xwVkh6wjYQXFPE-z-BRRZXAQoHtcT1Y'),(28,19,'13KBHqyF6PJp0zaazkq_zD4qEreeOwW_a'),(29,19,'1Blu5jUlG-Unghwa5piUZbKo7s8gzxipa'),(30,19,'1AJarCYvfLTrBPh731lp4uEDk3ppd11Fj'),(31,19,'1tST5mHCMi8xrA_viiDZFAILXz7FSDVy0'),(32,19,'1zMpXNd_Mqvc4PNUIlSJlQJ8_ExCvcGwY'),(33,19,'1aI8lZ7tSFQ4KKib9nNNdSShP9AWG6rox'),(34,19,'1Rxz1_JNY9xrG6_vdnfPpPKm0n4OKkpJQ'),(35,19,'1ERuYGrsrXpeOPHLFhBdrw_fvcpvoA_XC'),(36,19,'1loWyaO7_pOi6t-GEtNvdYJgkiAxDSy00'),(37,19,'1ErhqHL7s5vc1IdZbJGR0qOwdlDMt2Dex'),(38,19,'1_0WlQ7EbB4BE3FbNSuKv7Ix4pKjezG_n'),(39,19,'1sskEHBfZze15C1WcVt9Vv09DscriYxJF'),(40,19,'1-_q2Snr7KG08tlN4MfoKw1eMEWEJA7At'),(41,19,'1xTZYSgvEOysg3mDZQCQeCBLilvFNXO_U'),(42,19,'1675G6I37Av-AC-36dgArsiB4B3JuqQWh'),(43,19,'1ZO1JwbkQyjZm8GzD12go9nFzp6qHmiXS'),(44,19,'1YA1QuHknW-t5BQw3jUpvYRVVDZflAH4e'),(45,19,'1_e5e1FQjBp4DpU0Katx0yDcJgi-LR_HC'),(46,19,'1mg9CtkbaAglv9TP5B9xlgXjhGqcmrQex'),(47,19,'1cut3BdxSgOEXRPRnhVSZ8tDG3Lltrm_E'),(48,19,'11Eb-adSargNjm3S6nFztBujg8Whijdqt'),(49,19,'10hxssO4i16omnINXiJJHVuBzed08tzpF'),(50,19,'1mpxkXuYsWBRFItclGfzSckmROlGyxnjB'),(51,5,'1ls-K84qsPJynLQZsx0MUgRuF9Q66gXvf'),(52,5,'1iddJ4pET5FubrLE7tvBHg3UFvQK3eu6G'),(53,5,'1TwZTTbaAHRGN_Ad2bCqe1XctSN4GvjgN'),(54,5,'1TXMgfDke795bKSgt2DmDRDggBK8gDrNP'),(55,5,'1cTQh5NLXk-7ipp-RYXRVXKZmw-_8Hgzo'),(56,5,'1mcTbJF6IFEFBzAUOZM4mwVfM1V7Rtr4E'),(57,5,'1rING5J00WqOtDBFP4F8gdPgEZB_wHfek'),(58,5,'1i5DDO4DAchk7QHRrkl6OU1oiFBbTSOqT'),(59,5,'1mPa_QKBsyBayQOZ72xpIWpXSgoH2c-q1'),(60,5,'1ynt90JnFPrLnkErabF4m8hZEndj2dTzH'),(61,5,'1dQRk-ebOytKMQ4R0gDPhkeaiV4-y0fx0'),(62,5,'1MV6htIw_838Hn6f556AYCw0pIEAR-2cF'),(63,5,'1kP7L7naU92iSYVTt7FliDOC4Guewjns1'),(64,5,'11d_T34YJe4ladnWk82KWMDUWvfydlc-7'),(65,5,'1CgePmerLYFwzZpwyPY1Yg1FbO7HW58ee'),(66,5,'15RENOxnoMVuwHAWujR8q2G67efVq4aWJ'),(67,5,'1X8GGQ9Dc7UcAYjZ7pPfT9zVpMElxT3Cv'),(68,5,'1jyJGYVoSGGvfLTvw5-jhsqQT7CCqlTwC'),(69,5,'1kG7b-IAuJa-4QxxTh5sP9rGEkCMIAh7q'),(70,5,'14icdxPQSmyBP8XNoUBVq8dzUDUM_QqYG'),(71,5,'1Dekd9iHGdaESOMUYXlnRwnLHj8Q-hE1D'),(72,5,'12u_GrY8VDiwrMwlU0j1TwtqSkJoLCUzB'),(73,5,'1o47l00ooTHOFF-L14Z_oMSekCrOZqG0p'),(74,5,'1wvV2VApTxBbe5n0yHwX0D0ytlgzkKF4s'),(75,5,'1M4zyIfmpBiF0kVvb-9JTN08xzkCuJvGa'),(76,5,'19eLfbUWLGxIp0MSEKX_ZjeMBwztl-YSB'),(77,5,'1VlxfC3Nh3xIPuQ5Ju4t5fmuv17qKPz4d'),(78,5,'1mTnUwpwNT9tL-gE4EwlxsV8Z6dgbDxIu'),(79,5,'1dCtgqvALD5P4xuaNYExK7LW-R-M5WY33'),(80,5,'1sOpCAK091aGVV71ak2U5gFN6NoSJXfxL'),(81,5,'1ajHnzrPJaebzf_9b-gZnnO9wF_zkWUTu'),(82,5,'1AT2geKfvOamCm-XG8gEDr8DpA65pKOD_'),(83,5,'1FmogsWjPKMlNbGamM1TukVBX7mpnMfpB'),(84,5,'1SIeNJI9_MTLuDPWd0uspFukQ9vAyBaC9'),(85,5,'1AwPeQdXgduGn6GiQIUcsJPSHw7xiWalL'),(86,5,'1q4MCuGNIt8MrZMFTvtlZOOOQ9gS3PkbN'),(87,5,'1pEc9OPm5kOe3h33CokZ2KrfIax8d11L8'),(88,5,'13zuTL40wX2tPl9YdOom0N7WH5Lv6uqNF'),(89,5,'10E0WrfJvSz6lHLBkAlUl1WOfNMrJsC_i'),(90,5,'1uHCi354SwUz7QYRAIKf0_OWIHHIFJ9j8'),(91,5,'1LZZgIXMFf9xT-g4HJ5aVT35HUssi829E'),(92,5,'1j-ndE38yd5tzMqWesqkql-TLojoI8XjM'),(93,5,'1ZHm1lAYdrGAz6Pz0oQ-12vKl2BdK-in3'),(94,5,'15FTkBobe60rDWPX-5pYDOgzaD4-E7W8N'),(95,5,'1sMCkUVqmZq5AyBtqR8lC4hbBqs8uTNjS'),(96,5,'1urDKsbbsBHIXGquFDWWXtwa-iRHwiY55'),(97,5,'1tj2hop0LAOQ88eLDI0SjmBtLYLsofB_i'),(98,5,'1RZ0z-dsDOclyIqI1pV6lLo0Erhdm_Q5N'),(99,5,'1tQu3fL3uZiv3MytNEEuMe2CmzoF5rICC'),(100,5,'1symClQ3DJz4J9Cf7QafV20_Yrftsgcp0'),(101,5,'10e06y8F5z14ayTJt7pEAm9RpJAC7Gagf'),(102,5,'1-n5FeOhKnOjQdyG2JLYHFZHpx3fQWsHC'),(103,5,'1FgbB7DbqRZ6etn0nFWGn_6Ipl_HKDnqn'),(104,5,'1lbhXTXicBkyfjq1IArgIz7_VhspJvYbQ'),(105,5,'15rU4S50kqaObaJH-ozs8_IvNZdsXf4Qk'),(106,5,'15fTlMyo1aLZigYJA1qGAPuhQ9NoAB2QW'),(107,5,'1sn78vpWROFGBk1pOjNq1eg6uRFO5YVSi'),(108,5,'1p8jVwdAc_eS4lFa-bDrW8caFHzR-w7sO'),(109,5,'1vhAIE3tQsR04QZMPzAvInT3n8xfOoDXW'),(110,5,'1hEt0bGnNMvB6DSKUuvxw985305XDIorT'),(111,5,'17k0lIt-6NWvO5r-84XFt0TvrJytuJI2N'),(112,5,'1yf2Bl1XVVpSn-obWosEpkC-YCxbaj8y6'),(113,5,'188Ih31il1hpa7f1VSqgx2nz6T0gmxWw0'),(114,5,'1yuj4UT3LPcKUgDk9-UfSaC5Ta49Jw3jt'),(115,5,'1I9Km5v1gtIpVjLVWWaxReUlru9nihWaD'),(116,5,'1HqS0lAkj1NZzrgzHBp6CmSxR5QJBn0yk'),(117,5,'13WhNqQKw3CuEIlXRV-j7cdOitxgAzdCZ'),(118,5,'1XBQTiMZMaYalyKlUR-IjsbGYZcX2q1Qg'),(119,5,'16ZIlo1suIp7sD9ylgm9ujSy_33Ji8ziv'),(120,5,'1vC-VfHncSAZraliJeSS5wTGZKVVJ2vWI'),(121,5,'1NqDIKdBz4TUHqFjlyMCuok7-cVQ8xTkT'),(122,5,'1t_OMTQrdV0bA-wKycadri6q61ytJ4VFg'),(123,5,'1WGslxu7uAs1KXHQs9eWlT-5WIE_Z2Q_h'),(124,5,'1uQGrRD3wlqyuBXHJU7jCf3ESks6WC3jT'),(125,5,'1bEtGMDgTm7-k0FaNlD4ALIKdAy-w9sUA'),(126,5,'19I1eRvJmAzHZgFOBFKhu2p3ySZOY-1ZE'),(127,5,'15edB1wwnSLbaFrK-nEhVRw1fRwPYAtxq'),(128,5,'1ONGlAGIckU4-LB2TGMLztYlRbX4DEb25'),(129,5,'1ddjyHkYBuotrvkzmECFz6UAASZ84J78n'),(130,5,'1RA6URPpVaYwxkyeQcoa3RHNbTThOv4e6'),(131,5,'1z6mt_899MIWhl1S0XKLhSqrpIuGUHVL3'),(132,5,'1KmCs7-F6fDB8Byrh-rv42hQkNNwHW8Tg'),(133,5,'12_8BypF5wC2Uh6DD96d7KeKs9_fJKxn_'),(134,5,'19R7J0REzKpeR7PkHj8DhJjEq32Dm0jDl'),(135,5,'1yzVw8q-hXCN98IM_TAKJQO3OfeI_ssKF'),(136,5,'1YMe0wXjs_4-Ci7T4X9Z76N-PttN-HnbS'),(137,5,'1OuS7uPw1cuY8Us5OtQNoOWhSZStRqVN7'),(138,5,'1jskGznJbNT7d5bIf3HIpfIxZiAwKVg5i'),(139,5,'1YdlLIKQ8IoVbDsJFETbstVLGYoL6Uk8E'),(140,5,'1wkIHs7GjxXIYx2grQvA9wioCxrLVi7--'),(141,5,'1nZN-eSQ68GkHfDLn_bfrNHv1ZnvwM50l'),(142,5,'1H8SKwEVfnuspfdvk00RYJEBOtXu_qPpS'),(143,5,'1T3gTf5Anr1tYXRWwEXnD9Ky2o6KHGh-j'),(144,5,'1GbDEpaKt6sm8JtMbvjI0B0dq65IPz13s'),(145,5,'1txHC34ZXCbE3vCNS37vGINoUspMKraSi'),(146,5,'1WJ31gX8bSteefiTDkkOyRUep7wkCzyxl'),(147,5,'1oJbNz-aEGVhOTChgTW0eM7MW8VRDl5G_'),(148,5,'1eMXng5mQxgzmcR80NmYEKqWgunDQ7a3j'),(149,5,'13nc_6T7GWjzLVM13Vu13R5GltgSpzxQL'),(150,5,'1zMfi8HzMumraHzRA0oEO6-Mvy4tTD9E6'),(151,5,'1JyikUE3G8-6wJST0GbtX1pgIqIQwUhud'),(152,5,'1THaNb0QSUKSt_D7x3H99q_rfFqXuLaWF'),(153,5,'1w1njxyeX0dAFRxijLblQAMe3D87lQPEM'),(154,5,'1fdMH2p7OJG7cAHoqZHcjArCOEmfkqPm_'),(155,5,'1EJZV0pDCg_rRDw2PL1xngWd3_ctD_O9-'),(156,5,'1MDwz9SLq2l-sY9iMvUVvkcwb2pVsByDK'),(157,5,'1u7h4x2dU9h_XOge-mi0mwSrI3DQ4e8dX'),(158,5,'1znpfN89t2MBl7ZYta1412mRAu-9FpeUH'),(159,5,'1vUjfVm8nU5LgRTG86CvM57HvXf7SPwFe'),(160,5,'1Pd1E73WEf7_RIDeg7nmRfmmmU64BMvvo'),(161,5,'19RMpZ-lkmTDlCUJrNXFqA3xRE1haiX7-'),(162,5,'1RMeiEeXL5nBp-ioc-rwyeiTzXyKoMhIf'),(163,5,'1nwhLtJ1CsTJ42XC-OymgSQCLzIOXLBoH'),(164,5,'1Q5XMdHKwX78WciK-AtJco6XSqlttAEiw'),(165,5,'1oTrtUu74Gi7l3bE1o07PKuul-2kabyfy'),(166,5,'1kK86exva900G9mJtAS2t4wEjMO-zG0i_'),(167,5,'1RVEUVja5m3Z2uv9mOxbxtyaqN2ZqTi7p'),(168,5,'12KSrYrWPdWODIa5TNx42Ug-PmI-Ej7-3'),(169,5,'1ONVQGlG66bT4mme60LilcSq_-KjVpTwx'),(170,5,'1EF9JuGOnKZoS6HBqL8h0-MvyJ2tSKcz7'),(171,5,'1p63PlVCf0E4hLlAzU1re7CL7UUdWSBYW'),(172,5,'1RwdROxYNuEvAdo2NylxGBxagRA1D6X2-'),(173,5,'1aYkCPGhZeZX4CsPnTpAoBTL18IqBQAAH'),(174,5,'1IIELkjZvEXmkSq2ciXDq56ueDdnT8TxU'),(175,5,'1ebijay19MiiY3t_jtYy8VPkJhxQ7M5XT'),(176,5,'1-vFjdD9Wv14P2QmqGzcbA_b1Nb87x-mn'),(177,5,'1NUN7t5AOQV2l0nvOZFlnahd15Dg5vL4v'),(178,5,'1ct3uVYi6MBfMY8mqsXiO1CVOle7k2Bca'),(179,5,'1TDu1tsVYfWW8Kf_SHL8hjyN2m48wamuP'),(180,5,'1n4VsYNGo-66Bx133J3JGrqtIJgU1sk-c'),(181,5,'1JAt5TJ8XsxocxAO7m18EqNTGh-NvHIt1'),(182,5,'1thMaHAkwCoOjdBouu-VZevo_E847frMY'),(183,5,'1lz1g3U5ioRluZfJ5nkE853Tu61-ccmQf'),(184,5,'1IZGrBDsjWrdu3BcYdPml9rec6c_x9NxC'),(185,5,'1LLYJnZaIO5XsEVeZ7mqukIRqrnWGopth'),(186,5,'1BwUInVIXEgwmd5PGLn18PEJ3Y_7HW6gX'),(187,5,'1izPmEjxSUSZnK2SsuYTzEsgTDsl_X-VO'),(188,5,'1M6lDoASm04nCD2es0XhB3KIfYzM1w2hr'),(189,5,'14Q6cFtPUwfQtSVDn65PZ3Fbbpo19YTcX'),(190,5,'12lvP7xebkSXiUVL5Y_bqG4OfKb__NEIj'),(191,5,'1e1kf5nMMK22mv-fK2SxwqFsUyC8UOohI'),(192,5,'1Xd8iOlKBI6yhu3ktmIoy28NaBTi2EDzB'),(193,5,'1nLokhctBTWEf0MCdIcebDGHAYqYVyDqG'),(194,5,'1jLBAKbPo26sbTJmi0DGQ0vQ96uP-xooF'),(195,5,'1yyXqAjUVOKAU-b9fc6GrEu1ri0G-rXRD'),(196,5,'1djPPjpP7BA79R3sdkuuXVeR6X3VAiKZm'),(197,5,'1KUZ8hn5k9CxsxETAfxCN0-h-BWQg3gH1'),(198,5,'15PlP1YZvCH4ymUUgwA7RP9ZLERgtUHMh'),(199,5,'1qoUiytRZRgKjYamDATZ1vVwI9KnDzvZN'),(200,5,'1uPyS9sYUyDitvDPASHFryAzkICelIwNp'),(201,5,'1J_PFCqE6rz-QKTXDBz-F3JJQ11g1HyQr'),(202,5,'1nplyg_6NUlXQMjhx5T3UZPPPSVeMvzu_'),(203,5,'1TiZeePiF3vOyW96MaBCrFuFRRVbV4Be_'),(204,5,'108kk0LfIQ4aWevq21QueOCZZSSLFitiD'),(205,5,'1hmSqapvwdzHsd81oQ7QczRM_ociOvhQ_'),(206,5,'1gjNwJP2Ro8lXyxrl4VS7UWhjMKEBFv1u'),(207,5,'1rR_Q1rGGmNkuoDCF8V4ZVo3vTAzhYR6d'),(208,5,'19aKnnTTlwV0tUmQ2Zu0Z2RnKZWEQelcd'),(209,5,'1_qXkcZJV2bfAuD1RdiAF5GtmkNfIOuCA'),(210,5,'1mFswc99A90Errfykc_rYBkF_JUID_VMq'),(211,5,'1usO530oT4e8D2KZ_PMX6ikdQB9yWcQTU'),(212,5,'1pH7LbRVrI3MXm3x_6UbAyeF9p8RfdRGl'),(213,5,'1XYLqAaYnYU7z0nl2bpf-z48sGe_7hcLQ'),(214,5,'1_7XdvUMzq3_ZA38a_FhgNCfxCfNvhYTn'),(215,5,'1HrbTUKAMKrcxKz-VUnFWZmcqYzXEgvo0'),(216,5,'1DQVktk0cZdqBO0fUnpvZNQ1mZ25sYTJq'),(217,5,'19Z3tz95DLD1CsrPuEhCByImAkND9Kj4q'),(218,5,'1_Dii0wu-IIlG87zV78-MrGdBixo8Uj-4'),(219,5,'1ULeafKwbWq9-BsATsT5XRaeDPfUB0wEJ'),(220,41,'1-VMYxyk5KHh144z676vDGzEZmegAuSFk'),(221,41,'1WYna1WWItWL4Rq175tuSD-n2v8yN1eoV'),(222,41,'19oQSOSQNGg3HYTavFn4daoKJl3jX2OW8'),(223,41,'1r6C4Xp2M_h9o7rrQ7idJjDuFfHq9V6cq'),(224,41,'1i4R-OzeZvCYXPda7aT3889QfpYZImg-H'),(225,41,'1-9ZCMPgZFyebYtSS9UEThwpSQPWdNQvL'),(226,41,'1bExmFF95eMr9IqA_urBEfQJhDNDcQRX9'),(227,41,'1SwGU0L103TCb_gi40Glh_bTZNIydY_Ln'),(228,41,'1DC2rV_BiX_SoC8l8CGlNFSJS9ZcWmI3O'),(229,41,'1xNxRMZb6mvssX77_fUMlxM3BAdURyqjD'),(230,41,'1BusA8M10st1BcyInSfBG_bWyPnfWcxIp'),(231,41,'1YcajPvZSdp0DYHrWhpUhal-1kTmFkpat'),(232,41,'1PFwSbC3X_HdG8RCZ8WIlmuiIip-JXP2O'),(233,41,'1qhUePsPqrTrgOHhmhlghRvl9pw3TMY8P'),(234,41,'12OqgV3WYrCTMpByCGkCJ1Dr7MHMnQEYN'),(235,41,'1cEopYeJ1fjM444_HDcajSd9nVWZhsfXG'),(236,41,'1mI067vNg61Hx4GEK_pmqs0VXqIEo3PxA'),(237,41,'1JpOG9-ERhvvoMUORZD5AeOTPRoyAHWBn'),(238,41,'13q5zNVKOT_A6TItrg9t3m8oQaTpiR3LM'),(239,41,'1SnoHLl62Wg9jbQJvn0OmQHe5pnkP4sqU'),(240,41,'1I4Y3OrtDSOb2I4eaUNw7jrxzwAJa8YpQ'),(241,41,'1_wZ5Odgke90Wp2mVNiEWCHzt2taeft6Y'),(242,41,'10IKCngH8agl3d5R8ngraQtDNBsksgfiE'),(243,41,'1fI7Qs1rmk09aKHwktVOpGqpGkvpot2RQ'),(244,41,'1eRaalA-Gd8gK2MiCqXYcyRcoAvLpikez'),(245,41,'1FDy_o91VOcxq3jOKIy8rOWyPi6brNz3n'),(246,41,'1axYNFx_PwJ_iIaxa7fRwugm02C_hXFu3'),(247,41,'1U_SqIstRQU2zb1OzOCLKfTrdAxCYYcSE'),(248,41,'1yX2nUQ9GBRp9CZznkJCv42e2QfTIHn-s'),(249,41,'12It1C6Qa95tZGa3WOEWg2rqqtPKdcamj'),(250,41,'1scl_2evaVHIXmv0VaYDyaBPlIoK_HaRI'),(251,41,'1rA1OJqe87nhurkM1RJEfHk41ENbOHU__'),(252,41,'1FekxiXCVWBjcz41m7sCkoCOz77CIo68P'),(253,41,'1E0SPK2mAI3Ct1dqQTHmcSHSZ5EoP4Ii8'),(254,41,'1BZ3YkQcOwhCY7rQ27ihZ6g_5rGrSjWYm'),(255,41,'17XygEBHhT8I66mwEbOa606ra_lQ_VTVB'),(256,41,'1mI_dc4EwJzflX7pFoHTXvrdRGeh4KTm1'),(257,41,'1n0ZAhPJxF5Z_7Opwkipkl5R-oA0yOoJZ'),(258,41,'1TVTYpBHiPY35_zqapfSLNZl0s4AX6AoO'),(259,41,'1JSEacff4gBklPxRucGBf89zsd_U7c-eZ'),(260,41,'12O2SCH2g8frcM-vFXkrZVkbQG15veq6S'),(261,41,'1s4jKtQvu7Xic8FmPLmLVPVrcg9XPp3KC'),(262,41,'1y9BTblviQ21eLCQ9_7D_0ZiNikw_srXG'),(263,41,'1fvl50oa4RJsS-C39XxzIOJz4aWo2yK-1'),(264,41,'1u31I60xoM3zrAgO8EVVfewYjShevDN4U'),(265,41,'11Q1xlpLEHAKrapBSgJQ-5kQc9AuWrWFk'),(266,41,'1j4-t0Myl6NweLFB6RL7me14PtfgbfCEL'),(267,41,'1IYy6lxmss3r4LgEAPlaQwjshlIUvoAHP'),(268,41,'1UBIZK9RwVljIdvw4zOhimab7MQvStLmz'),(269,41,'1HdNNjmjXlWaMTmto-CM_VMPpsUsLHg_o'),(270,41,'1Rx64PJwVZRG42YFKIMOn-Ve9ROHvNmil'),(271,41,'1pw74CY20mDZW4AphKQEv5f2j8KuB3-ZL'),(272,41,'1yXxpevL_m6p30QNrKR6kyxlZhbLnZbCG'),(273,41,'1ylkmrM-atO6FFpQLyRJJNIwleoy-JPJn'),(274,41,'16z3tkkD7HElAg-HhQnxxItqdsq4bHlUI'),(275,41,'1YuAKCbUWPLly454Fhg4ncWN6gDaY07m0'),(276,41,'1GAX-_3IcQCzVxtjnOfknrkGNLCs74GSA'),(277,41,'1T9W2MNR29pn7jkyfM5PyrBOkjPynaSIo'),(278,41,'1bswkHAuHR6CU9dU0l1B3B0LBtHTi3Rk4'),(279,41,'1vTruUgVB8ThimIiGCRqbuucXMZkA6_78'),(280,41,'1a4vkbtqfRKqGA8NHmZPRxQNXbF5M6oqw'),(281,41,'1_Wml34J2YwjeZLkvxs6b9d8PzQf5sxvB'),(282,41,'1TZfStYB2j2nDZcUwRd1ZjBZREZQdz9SF'),(283,41,'19L8GyTPfAYv0XrCXOivBfXBA13zv-5yy'),(284,41,'12CTe-U7eh9CT0MUL9ANID4giPd_FT_Q7'),(285,41,'1Tr6WqKaB4rOFF4ieTVa2RE2DdS1-ZoRC'),(286,41,'1HPAILfrnG38VhwSiXUeIa5OLno_RP_LK'),(287,41,'1V4rLvioAgnHyClCVMB0rHEqAgyH6Pw04'),(288,41,'1aJPKDyPOXNzdHmdo_EkQZW3gAUT59uP4'),(289,41,'12lAzVSmMIY7tu9Q0yznV0ARDBgzGyv4v'),(290,41,'19Nk6V07dCQ2IScpLtah_sY_YR-pA6NOU'),(291,41,'1PffLmnU98WRvaxK0xAEotRMuJP4GGe0L'),(292,41,'1blvUXhhsy-AisKd0ovTCkpXVokE50QQL'),(293,41,'1LHsaOI6YOafk3QUHNgjClZ8gPA3MVXcv'),(294,41,'1U0XOoQoMOwEkbrAQ_VbDs6cbNjPVTE2g'),(295,41,'1HvfgZM7sbfI_qM00mkZ_PJVBu5tNSmMp'),(296,41,'1lAmc5_043bOw898Yed4f8QyoM6hYEqtq'),(297,41,'1wjFp1Y81qD1QBMaFgvpHUnvOsRrF429Y'),(298,41,'1Cu5nABlurklXt1vEfvT7fYvtIdJB3fiO'),(299,41,'1bfE5HPneRgUeRR9eg34Ef89BeeL6kyID'),(300,41,'1iPX14jSxZD379UAfkGEW-9FYcJHzXMdt'),(301,41,'13tLAHtFbDNu6ayKQ7ncXusw6i1Hx-Tva'),(302,41,'117unzutsLeUjZt1Izc9nw1ktKuBq-8pR'),(303,41,'107vJyjrw5jya7qOpcGloaIouRyTh3soL'),(304,41,'1UXGHj0DlY3lvBrU-IAZ_mu3sux7hOkiN'),(305,41,'18ywqYWGxtJU8VgxaQm4l-ma8tRbyFRmU'),(306,41,'1_Vlr1BQ_wfgkzuirO-YX6dG1Uroxbit0'),(307,41,'1La9Wpjwic3AXmPeqJ91xmTm5Fk1wLQHK'),(308,41,'1t2h7OZAefUScUn2lOi8EGkjtAiSEtilV'),(309,41,'1E-dw0oWSljsXCVypZK_i5YtBhqWDNTST'),(310,41,'1QMhxLxZC84U-1iz3e-s_JdXmV6AVV9gD'),(311,41,'1R1oRWPRNtDiPHwFtnGpzOXm8vW6-w00_'),(312,41,'1KeTR09qdEhveCyhV7Fyh80u4ZrMBtrnj'),(313,41,'1at98gpGqcgPXyegWlBLjORJ6tDJHlMou'),(314,41,'15Rfdvv10E2BZOyBJgN1u9Rqk2mduZiam'),(315,41,'1tU5L2KqRkIvVE50mGokW3f4FSilY-D6z'),(316,41,'1ZD33MGiohNxYjQnz8jGMpyVNpvRajXk1'),(317,41,'192ByE_PN3vp7w9j6rZavA7jx3DXTftad'),(318,41,'1ReFlsPHwaw4AnhcbK43k-t1dEjhy9sKG'),(319,41,'1ntECXeC9cMde8mEy3XD7Qzs96FK4s4DY'),(320,41,'1BtBOyLh6Fttn_dwa2vXR7MAU40ji9Iy-'),(321,41,'1TfVxPVUOUUMkqJx9aOr7kclj45k4xLNt'),(322,41,'1Q2AREVf5DV5SKj5CSro7JY3dwvam7U9E'),(323,41,'1IMC3dnm7SSuZzPmOs96tySl34rTkcC-I'),(324,41,'1dT7Ou0XdETMGY0Wu1TDZo6n_z9kvYeH3'),(325,41,'1rFyotRHY0_VbggpTH4wo12BeA46b5dTK'),(326,41,'1qQd3rhPDMEnOmvViZh1rq7YiXIs6oqwd'),(327,41,'1K8yGub-DJMmdo1qSopP5MvJz_SXoai45'),(328,41,'1iBkd1zkfVBYlo8kLT7wxfn9bbVM_atOV'),(329,41,'1VhrpoQ01_Xi5PeZB3FNgLx_23Jca_80F'),(330,41,'1PIP6HpvACE1dV85FH133OsjGn98-HxEa'),(331,41,'1rDc-tGCFoDECaaABuEZVKaZysx1X1Tbq'),(332,41,'1L_YjnaTcBpeVouE_O9796oYIxAmKCzYC'),(333,41,'16LJOQvP-2sILS1tZxTnCQUhFa6-P3QPT'),(334,41,'1OriDiMZpUZkcYi-pQRxDVT1xlGz5iS04'),(335,41,'1j-L67b-bmgUyCr3rGFQYDicdyyDOjDtX'),(336,41,'1RqHQ7G6eaTimgMsELnaH-4T5WUMdyWZR'),(337,41,'1HLuDH0HYOywTnvPbUEWUUxKiChulQXsw'),(338,41,'10YSB9vI8k1xDIQlcAQI46wL87ivXn21-'),(339,41,'1j5K-kgCO1Pvr8iqXol7arSmpCqzFxY_b'),(340,41,'1NrfRczNpuKxdaOpvw3bgBn4v_IrdGemf'),(341,41,'15bg4r0SvpTVuSqr0ljMGKoitpjXuo8iK'),(342,41,'18dLPNq9ddKExWarTjDtdRwkOX-MzqWKm'),(343,41,'1VRWxChEpPbcwjGAwUtxiN43o3CfX-fqn'),(344,41,'1xY_3_i0xBU7tWjYfx42Ac9HZtZbGOWsh'),(345,41,'1Yk7GzjLpE12RXmFpA0Hc2FlDDYqbOsqs'),(346,41,'1ndGS1HKzmbTkw9RaWtuFwsTz68_yKB4G'),(347,41,'1Iz199WTQtx475bjbv3o2ZWY5wfavlNQM'),(348,41,'1weUDawwaOVhNmIrNoAaG-ERnFAC9wtNc'),(349,41,'1k493YuiZ5ffVI9axQWKXMFw8bhKTbEIc'),(350,41,'1viiG9L8VvunVxMV11HRy8i3kBI5J6QP2'),(351,41,'1TEFHl7Vzs4Tu10nFnSy7y236J9sY0rNF'),(352,41,'1iXEckmCT_H4NEm9N4M8BoIWNDfDIYwjf'),(353,41,'1pJic1hfe2poGSiSmSzw-vFbzakoKBJR8'),(354,41,'1Jhi61UwA6gPl-n0Shs0uh5wfVHNUnCWn'),(355,41,'1VyZ7JkjLfHTyLjUveyb0ZaGedhV4ejTJ'),(356,41,'1-ztXxKszj68XtIEm9yLnVdCcI95hHsGt'),(357,41,'1XFL_6b0Ikd5luiS1Qvc0e2ReiG82qXRc'),(358,41,'1pkcsKsKJm3xYSKqujDhxJjisJF9XpyWh'),(359,41,'1UqTw7H9UGtClmxAjrIdouY70c-h4sTm3'),(360,41,'12eGyL6ipmO-cjBMZWOTax5VEUECoA0gO'),(361,41,'1F3yRB780wUejl8urrbmIV-AzHWbgxqw_'),(362,41,'1S0csv1WQAyNZdmMMgoNEbvBrKUgueHLb'),(363,41,'1KNLsN09T_oSvbki5QPpiNo3XeRZTNf-6'),(364,41,'1ILIE2p9LJlef8YGbeVyhGPk1uYeO90eJ'),(365,41,'1r3wVr0nrnaNi5VbUovA8YqvnZmhVSkYO'),(366,41,'1ckhmmzZRnLR79PujEIUjXCD8ZGOax5JL'),(367,41,'1FQgai8puiB5iWyOz07QLN5Ar7HKY8XNp'),(368,41,'1R86707OVy8plMerErmwNLY3xOG0RA1O_'),(369,41,'11ighfPYVSubxDxeyIbv9SFVn3ivNNDY0');
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

-- Dump completed on 2025-02-17 14:22:19
