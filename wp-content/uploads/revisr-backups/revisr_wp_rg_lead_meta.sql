
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
DROP TABLE IF EXISTS `wp_rg_lead_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_rg_lead_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lead_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`id`),
  KEY `meta_key` (`meta_key`(191)),
  KEY `lead_id` (`lead_id`),
  KEY `form_id_meta_key` (`form_id`,`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_rg_lead_meta` WRITE;
/*!40000 ALTER TABLE `wp_rg_lead_meta` DISABLE KEYS */;
INSERT INTO `wp_rg_lead_meta` VALUES (1,1,3,'gform_product_info_1_','a:2:{s:8:\"products\";a:5:{i:3;a:4:{s:4:\"name\";s:61:\"SMALL – 4 ‘ 0 ‘ (h) X 6 ‘ 0 ‘ (W) – 1 Panel - $10\";s:5:\"price\";s:2:\"10\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:4;a:4:{s:4:\"name\";s:19:\"Materials 2 ($4.00)\";s:5:\"price\";s:1:\"4\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:5;a:4:{s:4:\"name\";s:16:\"Finish 1 ($2.00)\";s:5:\"price\";s:1:\"2\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:6;a:4:{s:4:\"name\";s:17:\"Grayscale ($5.00)\";s:5:\"price\";s:1:\"5\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:7;a:4:{s:4:\"name\";s:16:\"Original ($1.00)\";s:5:\"price\";s:1:\"1\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}}s:8:\"shipping\";a:3:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"price\";b:0;}}'),(2,1,6,'woocommerce_order_number','219'),(3,1,6,'woocommerce_order_item_number','1'),(4,1,9,'woocommerce_order_number','220'),(5,1,9,'woocommerce_order_item_number','2'),(6,1,12,'woocommerce_order_number','223'),(7,1,12,'woocommerce_order_item_number','3'),(8,1,15,'woocommerce_order_number','224'),(9,1,15,'woocommerce_order_item_number','4'),(10,1,20,'gform_product_info_1_','a:2:{s:8:\"products\";a:5:{i:3;a:4:{s:4:\"name\";s:61:\"SMALL – 4 ‘ 0 ‘ (h) X 6 ‘ 0 ‘ (W) – 1 Panel - $10\";s:5:\"price\";s:2:\"10\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:4;a:4:{s:4:\"name\";s:19:\"Materials 1 ($3.00)\";s:5:\"price\";s:1:\"3\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:5;a:4:{s:4:\"name\";s:16:\"Finish 1 ($2.00)\";s:5:\"price\";s:1:\"2\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:6;a:4:{s:4:\"name\";s:17:\"Grayscale ($5.00)\";s:5:\"price\";s:1:\"5\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:7;a:4:{s:4:\"name\";s:14:\"Mirror ($2.00)\";s:5:\"price\";s:1:\"2\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}}s:8:\"shipping\";a:3:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"price\";b:0;}}'),(11,1,21,'gform_product_info_1_','a:2:{s:8:\"products\";a:5:{i:3;a:4:{s:4:\"name\";s:63:\"MEDIUM – 5 ‘ 4 ‘ (h) X 8 ‘ 0 ‘ (W) – 2 Panels - $20\";s:5:\"price\";s:2:\"20\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:4;a:4:{s:4:\"name\";s:19:\"Materials 1 ($3.00)\";s:5:\"price\";s:1:\"3\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:5;a:4:{s:4:\"name\";s:16:\"Finish 1 ($2.00)\";s:5:\"price\";s:1:\"2\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:6;a:4:{s:4:\"name\";s:16:\"Original ($1.00)\";s:5:\"price\";s:1:\"1\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:7;a:4:{s:4:\"name\";s:16:\"Original ($1.00)\";s:5:\"price\";s:1:\"1\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}}s:8:\"shipping\";a:3:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"price\";b:0;}}'),(12,1,22,'gform_product_info_1_','a:2:{s:8:\"products\";a:5:{i:3;a:4:{s:4:\"name\";s:61:\"SMALL – 4 ‘ 0 ‘ (h) X 6 ‘ 0 ‘ (W) – 1 Panel - $10\";s:5:\"price\";s:2:\"10\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:4;a:4:{s:4:\"name\";s:10:\"- Select -\";s:5:\"price\";s:1:\"0\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:5;a:4:{s:4:\"name\";s:10:\"- Select -\";s:5:\"price\";s:1:\"0\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:6;a:4:{s:4:\"name\";s:16:\"Original ($1.00)\";s:5:\"price\";s:1:\"1\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:7;a:4:{s:4:\"name\";s:16:\"Original ($1.00)\";s:5:\"price\";s:1:\"1\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}}s:8:\"shipping\";a:3:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"price\";b:0;}}'),(13,1,23,'gform_product_info__','a:2:{s:8:\"products\";a:5:{i:3;a:4:{s:4:\"name\";s:65:\"LARGE – 7 ‘ 11 ‘ (h) X 11 ‘ 11 ‘ (w) – 3 Panels - $30\";s:5:\"price\";s:2:\"30\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:4;a:4:{s:4:\"name\";s:10:\"- Select -\";s:5:\"price\";s:1:\"0\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:5;a:4:{s:4:\"name\";s:10:\"- Select -\";s:5:\"price\";s:1:\"0\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:6;a:4:{s:4:\"name\";s:17:\"Grayscale ($5.00)\";s:5:\"price\";s:1:\"5\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:7;a:4:{s:4:\"name\";s:14:\"Mirror ($2.00)\";s:5:\"price\";s:1:\"2\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}}s:8:\"shipping\";a:3:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"price\";b:0;}}'),(14,1,23,'gform_product_info_1_','a:2:{s:8:\"products\";a:5:{i:3;a:4:{s:4:\"name\";s:65:\"LARGE – 7 ‘ 11 ‘ (h) X 11 ‘ 11 ‘ (w) – 3 Panels - $30\";s:5:\"price\";s:2:\"30\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:4;a:4:{s:4:\"name\";s:10:\"- Select -\";s:5:\"price\";s:1:\"0\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:5;a:4:{s:4:\"name\";s:10:\"- Select -\";s:5:\"price\";s:1:\"0\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:6;a:4:{s:4:\"name\";s:17:\"Grayscale ($5.00)\";s:5:\"price\";s:1:\"5\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}i:7;a:4:{s:4:\"name\";s:14:\"Mirror ($2.00)\";s:5:\"price\";s:1:\"2\";s:8:\"quantity\";i:1;s:7:\"options\";a:0:{}}}s:8:\"shipping\";a:3:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"price\";b:0;}}');
/*!40000 ALTER TABLE `wp_rg_lead_meta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

