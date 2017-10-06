
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
DROP TABLE IF EXISTS `wp_rg_lead_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_rg_lead_detail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `form_id` mediumint(8) unsigned NOT NULL,
  `field_number` float NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `lead_id` (`lead_id`),
  KEY `lead_field_number` (`lead_id`,`field_number`),
  KEY `lead_field_value` (`value`(191))
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_rg_lead_detail` WRITE;
/*!40000 ALTER TABLE `wp_rg_lead_detail` DISABLE KEYS */;
INSERT INTO `wp_rg_lead_detail` VALUES (11,3,1,3,'SMALL – 4 ‘ 0 ‘ (h) X 6 ‘ 0 ‘ (W) – 1 Panel - $10|10'),(14,3,1,6,'Grayscale ($5.00)|5'),(15,3,1,7,'Original ($1.00)|1'),(26,6,1,3,'CUSTOM SIZE - $40|40'),(29,6,1,6,'Grayscale ($5.00)|5'),(30,6,1,7,'Mirror ($2.00)|2'),(41,9,1,3,'SMALL – 4 ‘ 0 ‘ (h) X 6 ‘ 0 ‘ (W) – 1 Panel - $10|10'),(44,9,1,6,'Original ($1.00)|1'),(45,9,1,7,'Original ($1.00)|1'),(56,12,1,3,'SMALL – 4 ‘ 0 ‘ (h) X 6 ‘ 0 ‘ (W) – 1 Panel - $10|10'),(59,12,1,6,'Grayscale ($5.00)|5'),(60,12,1,7,'Mirror ($2.00)|2'),(71,15,1,3,'MEDIUM – 5 ‘ 4 ‘ (h) X 8 ‘ 0 ‘ (W) – 2 Panels - $20|20'),(74,15,1,6,'Original ($1.00)|1'),(75,15,1,7,'Mirror ($2.00)|2'),(96,20,1,3,'SMALL – 4 ‘ 0 ‘ (h) X 6 ‘ 0 ‘ (W) – 1 Panel - $10|10'),(99,20,1,6,'Grayscale ($5.00)|5'),(100,20,1,7,'Mirror ($2.00)|2'),(101,21,1,3,'MEDIUM – 5 ‘ 4 ‘ (h) X 8 ‘ 0 ‘ (W) – 2 Panels - $20|20'),(104,21,1,6,'Original ($1.00)|1'),(105,21,1,7,'Original ($1.00)|1'),(106,22,1,3,'SMALL – 4 ‘ 0 ‘ (h) X 6 ‘ 0 ‘ (W) – 1 Panel - $10|10'),(109,22,1,6,'Original ($1.00)|1'),(110,22,1,7,'Original ($1.00)|1'),(111,23,1,3,'LARGE – 7 ‘ 11 ‘ (h) X 11 ‘ 11 ‘ (w) – 3 Panels - $30|30'),(114,23,1,6,'Grayscale ($5.00)|5'),(115,23,1,7,'Mirror ($2.00)|2');
/*!40000 ALTER TABLE `wp_rg_lead_detail` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

