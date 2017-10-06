
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
DROP TABLE IF EXISTS `wp_rg_lead`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_rg_lead` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` mediumint(8) unsigned NOT NULL,
  `post_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `is_starred` tinyint(1) NOT NULL DEFAULT '0',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(39) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `source_url` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_agent` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `currency` varchar(5) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `payment_status` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_amount` decimal(19,2) DEFAULT NULL,
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `transaction_id` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `is_fulfilled` tinyint(1) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `transaction_type` tinyint(1) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_rg_lead` WRITE;
/*!40000 ALTER TABLE `wp_rg_lead` DISABLE KEYS */;
INSERT INTO `wp_rg_lead` VALUES (3,1,NULL,'2017-08-01 09:31:02',0,0,'192.168.0.86','http://php.twilightsoftwares.com:9998/murals/product/428220508-3/','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:54.0) Gecko/20100101 Firefox/54.0','USD',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'active'),(6,1,NULL,'2017-08-01 09:39:35',0,0,'192.168.0.86','http://php.twilightsoftwares.com:9998/murals/product/336422462/','API','USD',NULL,NULL,NULL,'',NULL,NULL,1,NULL,'active'),(9,1,NULL,'2017-08-01 09:56:04',0,0,'192.168.0.1','http://php.twilightsoftwares.com:9998/murals/product/336422462/','API','USD',NULL,NULL,NULL,'',NULL,NULL,1,NULL,'active'),(12,1,NULL,'2017-08-01 10:07:14',0,0,'192.168.0.86','http://php.twilightsoftwares.com:9998/murals/product/336422462/','API','USD',NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'active'),(15,1,NULL,'2017-08-01 10:26:50',0,0,'192.168.0.86','http://php.twilightsoftwares.com:9998/murals/product/282446912-4/','API','USD',NULL,NULL,NULL,'',NULL,NULL,1,NULL,'active'),(20,1,NULL,'2017-08-01 13:49:45',0,0,'192.168.0.86','http://php.twilightsoftwares.com:9998/murals/product/282446912-4/','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:54.0) Gecko/20100101 Firefox/54.0','USD',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'active'),(21,1,NULL,'2017-08-03 05:41:33',0,0,'192.168.0.86','http://php.twilightsoftwares.com:9998/murals/product/364151948-15/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36','USD',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'active'),(22,1,NULL,'2017-08-03 05:54:10',0,0,'192.168.0.86','http://php.twilightsoftwares.com:9998/murals/product/105118202/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36','USD',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'active'),(23,1,NULL,'2017-08-03 06:02:28',0,0,'192.168.0.86','http://php.twilightsoftwares.com:9998/murals/product/336422462-3/','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36','USD',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'active');
/*!40000 ALTER TABLE `wp_rg_lead` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

