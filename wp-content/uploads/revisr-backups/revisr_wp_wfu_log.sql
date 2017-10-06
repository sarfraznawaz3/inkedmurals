
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `wp_wfu_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wfu_log` (
  `idlog` mediumint(9) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `uploaduserid` int(11) NOT NULL,
  `uploadtime` bigint(20) DEFAULT NULL,
  `sessionid` varchar(40) DEFAULT NULL,
  `filepath` text NOT NULL,
  `filehash` varchar(100) NOT NULL,
  `filesize` bigint(20) NOT NULL,
  `uploadid` varchar(20) NOT NULL,
  `pageid` mediumint(9) DEFAULT NULL,
  `blogid` mediumint(9) DEFAULT NULL,
  `sid` varchar(10) DEFAULT NULL,
  `date_from` datetime DEFAULT NULL,
  `date_to` datetime DEFAULT NULL,
  `action` varchar(20) NOT NULL,
  `linkedto` mediumint(9) DEFAULT NULL,
  `filedata` text,
  PRIMARY KEY (`idlog`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_wfu_log` WRITE;
/*!40000 ALTER TABLE `wp_wfu_log` DISABLE KEYS */;
INSERT INTO `wp_wfu_log` VALUES (1,1,1,1502185637,'o0njjte4g7b0rp525amn546c57','/wp-content/uploads/aspdotnetstorefront-development-660x540.jpg','',44448,'DSZv3dBW1N',282,1,'1','2017-08-08 09:47:17','0000-00-00 00:00:00','upload',NULL,NULL),(2,1,1,1502185875,'o0njjte4g7b0rp525amn546c57','/wp-content/uploads/unnamed.png','',33352,'rdvDwXVcbq',282,1,'1','2017-08-08 09:51:15','2017-08-08 10:40:30','upload',NULL,NULL),(3,1,1,1502188271,'o0njjte4g7b0rp525amn546c57','/wp-content/uploads/29.jpg','',33662,'TTICTW3RAo',282,1,'1','2017-08-08 10:31:11','2017-08-08 10:54:40','upload',NULL,NULL),(4,1,1,1502188830,'o0njjte4g7b0rp525amn546c57','/wp-content/uploads/unnamed.png','',33352,'MmpvdwHKrF',282,1,'1','2017-08-08 10:40:30','0000-00-00 00:00:00','upload',NULL,NULL),(5,1,1,1502188919,'o0njjte4g7b0rp525amn546c57','/wp-content/uploads/29.jpg','',33662,'ySF5giaUN2',282,1,'1','2017-08-08 10:41:59','2017-08-08 10:54:40','upload',NULL,NULL),(6,1,1,1502189680,'o0njjte4g7b0rp525amn546c57','/wp-content/uploads/29.jpg','',33662,'dKWyMMTBzw',282,1,'1','2017-08-08 10:54:40','0000-00-00 00:00:00','upload',NULL,NULL),(7,0,0,1502191298,'6grgb21abcc906ouhureut9hk6','/wp-content/uploads/BlogCategoriesKothariIndustrialCorporationLimitedWordPress.png','',88223,'b8JySBr9T8',282,1,'1','2017-08-08 11:21:38','0000-00-00 00:00:00','upload',NULL,NULL);
/*!40000 ALTER TABLE `wp_wfu_log` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

