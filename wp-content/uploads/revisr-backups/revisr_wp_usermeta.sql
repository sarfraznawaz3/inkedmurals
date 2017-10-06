
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
DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'first_name','test'),(3,1,'last_name','test'),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'comment_shortcuts','false'),(7,1,'admin_color','fresh'),(8,1,'use_ssl','0'),(9,1,'show_admin_bar_front','true'),(10,1,'locale',''),(11,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(12,1,'wp_user_level','10'),(13,1,'dismissed_wp_pointers',''),(14,1,'show_welcome_panel','1'),(15,1,'session_tokens','a:3:{s:64:\"9ea7ee259f19411469be0da2073e4a01c87d57d2400be51a860c0b568e9b40eb\";a:4:{s:10:\"expiration\";i:1507186383;s:2:\"ip\";s:15:\"103.213.192.118\";s:2:\"ua\";s:104:\"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36\";s:5:\"login\";i:1507013583;}s:64:\"fe9683b209079922957662f5b630e4d43a68ce20276f2e510d130e29b0faca78\";a:4:{s:10:\"expiration\";i:1507198081;s:2:\"ip\";s:15:\"103.213.192.118\";s:2:\"ua\";s:72:\"Mozilla/5.0 (Windows NT 6.2; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0\";s:5:\"login\";i:1507025281;}s:64:\"ecdd59939280753fa3b670d3f001fbaf484fb4cff259f4ef3b781b5b7fe5bb92\";a:4:{s:10:\"expiration\";i:1507294838;s:2:\"ip\";s:15:\"103.213.192.118\";s:2:\"ua\";s:72:\"Mozilla/5.0 (Windows NT 6.2; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0\";s:5:\"login\";i:1507122038;}}'),(16,1,'wp_dashboard_quick_press_last_post_id','3036'),(17,1,'community-events-location','a:1:{s:2:\"ip\";s:13:\"103.213.192.0\";}'),(18,1,'wp_user-settings','libraryContent=browse&editor=html'),(19,1,'wp_user-settings-time','1502517128'),(20,1,'closedpostboxes_product','a:1:{i:0;s:29:\"woocommerce-gravityforms-meta\";}'),(21,1,'metaboxhidden_product','a:3:{i:0;s:21:\"fusion_builder_layout\";i:1;s:10:\"postcustom\";i:2;s:7:\"slugdiv\";}'),(23,1,'managenav-menuscolumnshidden','a:5:{i:0;s:11:\"link-target\";i:1;s:11:\"css-classes\";i:2;s:3:\"xfn\";i:3;s:11:\"description\";i:4;s:15:\"title-attribute\";}'),(24,1,'metaboxhidden_nav-menus','a:14:{i:0;s:21:\"add-post-type-product\";i:1;s:29:\"add-post-type-avada_portfolio\";i:2;s:23:\"add-post-type-avada_faq\";i:3;s:33:\"add-post-type-themefusion_elastic\";i:4;s:19:\"add-post-type-slide\";i:5;s:12:\"add-post_tag\";i:6;s:15:\"add-post_format\";i:7;s:15:\"add-product_cat\";i:8;s:15:\"add-product_tag\";i:9;s:22:\"add-portfolio_category\";i:10;s:20:\"add-portfolio_skills\";i:11;s:18:\"add-portfolio_tags\";i:12;s:16:\"add-faq_category\";i:13;s:25:\"add-themefusion_es_groups\";}'),(25,1,'nav_menu_recently_edited','21'),(26,1,'gform_recent_forms','a:4:{i:0;s:1:\"3\";i:1;s:1:\"1\";i:2;s:1:\"2\";i:3;s:1:\"4\";}'),(27,1,'author_email',''),(28,1,'author_facebook',''),(29,1,'author_twitter',''),(30,1,'author_linkedin',''),(31,1,'author_dribble',''),(32,1,'author_gplus',''),(33,1,'author_custom',''),(34,1,'last_update','1501583209'),(35,1,'billing_first_name','test'),(36,1,'billing_last_name','test'),(37,1,'billing_address_1','test'),(38,1,'billing_address_2','test'),(39,1,'billing_city','pondy'),(40,1,'billing_state','PY'),(41,1,'billing_postcode','605001'),(42,1,'billing_country','IN'),(43,1,'billing_email','vengiece81@gmail.com'),(44,1,'billing_phone','123132'),(49,1,'shipping_method',''),(50,1,'_woocommerce_persistent_cart_1','a:1:{s:4:\"cart\";a:6:{s:32:\"1c33fab8b7bf26eaf727d2b4f8df5f1a\";a:10:{s:6:\"addons\";a:1:{i:0;a:3:{s:4:\"name\";s:9:\"Greyscale\";s:5:\"value\";s:9:\"Greyscale\";s:5:\"price\";s:0:\"\";}}s:10:\"product_id\";i:2629;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";d:1;s:10:\"line_total\";d:150;s:13:\"line_subtotal\";d:150;s:8:\"line_tax\";d:0;s:17:\"line_subtotal_tax\";d:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"bfbf0a23efce1ee7f33f473fac87ecb3\";a:10:{s:6:\"addons\";a:1:{i:0;a:3:{s:4:\"name\";s:5:\"Sepia\";s:5:\"value\";s:5:\"Sepia\";s:5:\"price\";s:0:\"\";}}s:10:\"product_id\";i:2864;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";d:1;s:10:\"line_total\";d:150;s:13:\"line_subtotal\";d:150;s:8:\"line_tax\";d:0;s:17:\"line_subtotal_tax\";d:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"706c1189b582e577e1a64316b968e9a4\";a:10:{s:6:\"addons\";a:3:{i:0;a:3:{s:4:\"name\";s:9:\"Greyscale\";s:5:\"value\";s:9:\"Greyscale\";s:5:\"price\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:17:\"Mirror Horizontal\";s:5:\"value\";s:17:\"Mirror Horizontal\";s:5:\"price\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:15:\"Mirror Vertical\";s:5:\"value\";s:15:\"Mirror Vertical\";s:5:\"price\";s:0:\"\";}}s:10:\"product_id\";i:2965;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";d:1;s:10:\"line_total\";d:150;s:13:\"line_subtotal\";d:150;s:8:\"line_tax\";d:0;s:17:\"line_subtotal_tax\";d:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"bf8df9c9963eec09bde4ae58c586859e\";a:10:{s:6:\"addons\";a:3:{i:0;a:4:{s:4:\"name\";s:23:\"Total Area - Total Area\";s:5:\"value\";d:10000;s:5:\"price\";d:10000;s:7:\"display\";s:14:\"&#36;10,000.00\";}i:1;a:3:{s:4:\"name\";s:9:\"Greyscale\";s:5:\"value\";s:9:\"Greyscale\";s:5:\"price\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:15:\"Mirror Vertical\";s:5:\"value\";s:15:\"Mirror Vertical\";s:5:\"price\";s:0:\"\";}}s:10:\"product_id\";i:2864;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";d:1;s:10:\"line_total\";d:10150;s:13:\"line_subtotal\";d:10150;s:8:\"line_tax\";d:0;s:17:\"line_subtotal_tax\";d:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"3977a8a125ca2c2ce2e8b632dba309c6\";a:10:{s:6:\"addons\";a:2:{i:0;a:4:{s:4:\"name\";s:23:\"Total Area - Total Area\";s:5:\"value\";d:10000;s:5:\"price\";d:10000;s:7:\"display\";s:14:\"&#36;10,000.00\";}i:1;a:3:{s:4:\"name\";s:5:\"Sepia\";s:5:\"value\";s:5:\"Sepia\";s:5:\"price\";s:0:\"\";}}s:10:\"product_id\";i:1070;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";d:2;s:10:\"line_total\";d:20300;s:13:\"line_subtotal\";d:20300;s:8:\"line_tax\";d:0;s:17:\"line_subtotal_tax\";d:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}s:32:\"58b5baf20107137df1a392b78afd0f36\";a:10:{s:6:\"addons\";a:4:{i:0;a:4:{s:4:\"name\";s:23:\"Total Area - Total Area\";s:5:\"value\";d:20000;s:5:\"price\";d:20000;s:7:\"display\";s:14:\"&#36;20,000.00\";}i:1;a:3:{s:4:\"name\";s:9:\"Greyscale\";s:5:\"value\";s:9:\"Greyscale\";s:5:\"price\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:17:\"Mirror Horizontal\";s:5:\"value\";s:17:\"Mirror Horizontal\";s:5:\"price\";s:0:\"\";}i:3;a:3:{s:4:\"name\";s:15:\"Mirror Vertical\";s:5:\"value\";s:15:\"Mirror Vertical\";s:5:\"price\";s:0:\"\";}}s:10:\"product_id\";i:1094;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";d:1;s:10:\"line_total\";d:20150;s:13:\"line_subtotal\";d:20150;s:8:\"line_tax\";d:0;s:17:\"line_subtotal_tax\";d:0;s:13:\"line_tax_data\";a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}}}}'),(51,1,'meta-box-order_product','a:3:{s:4:\"side\";s:84:\"submitdiv,product_catdiv,tagsdiv-product_tag,postimagediv,woocommerce-product-images\";s:6:\"normal\";s:119:\"fusion_builder_layout,woocommerce-product-data,postcustom,woocommerce-gravityforms-meta,slugdiv,postexcerpt,commentsdiv\";s:8:\"advanced\";s:24:\"pyre_woocommerce_options\";}'),(52,1,'screen_layout_product','2'),(53,1,'closedpostboxes_page','a:0:{}'),(54,1,'metaboxhidden_page','a:6:{i:0;s:24:\"fusion_settings_meta_box\";i:1;s:10:\"postcustom\";i:2;s:16:\"commentstatusdiv\";i:3;s:11:\"commentsdiv\";i:4;s:7:\"slugdiv\";i:5;s:9:\"authordiv\";}'),(55,2,'nickname','aaron@synergyframeworks.com'),(56,2,'first_name','Aaron'),(57,2,'last_name','Prince'),(58,2,'description',''),(59,2,'rich_editing','true'),(60,2,'comment_shortcuts','false'),(61,2,'admin_color','fresh'),(62,2,'use_ssl','0'),(63,2,'show_admin_bar_front','true'),(64,2,'locale',''),(65,2,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(66,2,'wp_user_level','10'),(67,2,'dismissed_wp_pointers',''),(68,3,'nickname','Webstep'),(69,3,'first_name','Saikat'),(70,3,'last_name','Mallick'),(71,3,'description',''),(72,3,'rich_editing','true'),(73,3,'comment_shortcuts','false'),(74,3,'admin_color','fresh'),(75,3,'use_ssl','0'),(76,3,'show_admin_bar_front','true'),(77,3,'locale',''),(78,3,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(79,3,'wp_user_level','10'),(80,3,'dismissed_wp_pointers','text_widget_custom_html'),(81,3,'session_tokens','a:7:{s:64:\"ac9f0710d9c5f18cd22e7d7ba1bba96b21cbd191c27c6ff9aa548b3b3e25dc03\";a:4:{s:10:\"expiration\";i:1504968245;s:2:\"ip\";s:14:\"103.242.189.22\";s:2:\"ua\";s:109:\"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36\";s:5:\"login\";i:1503758645;}s:64:\"2b2e7f580bd80fc29787d98d340d015f72637ae58ae4c65a00980d6e0f505666\";a:4:{s:10:\"expiration\";i:1503992772;s:2:\"ip\";s:15:\"106.203.141.178\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36\";s:5:\"login\";i:1503819972;}s:64:\"97ca279dbe8e17346bd48b1755aa167844dad77a519c3234043805f603ff90ef\";a:4:{s:10:\"expiration\";i:1504013223;s:2:\"ip\";s:15:\"106.203.142.202\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36\";s:5:\"login\";i:1503840423;}s:64:\"7c70a6ea39176a92eba46d6d644fe654c2b91b14f1cbf50b51d156ed7b0bfdbf\";a:4:{s:10:\"expiration\";i:1504016903;s:2:\"ip\";s:15:\"106.203.147.198\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36\";s:5:\"login\";i:1503844103;}s:64:\"71dcb2a68e3a66250a6362b9d4dbf71029a78d571fdf941690bcf6fed70d8593\";a:4:{s:10:\"expiration\";i:1504072320;s:2:\"ip\";s:13:\"114.69.235.57\";s:2:\"ua\";s:72:\"Mozilla/5.0 (Windows NT 6.2; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0\";s:5:\"login\";i:1503899520;}s:64:\"eb11a40e33be279ab3a9c6557787feabf4d7b1a4384c91ed47437fc2bf8a401d\";a:4:{s:10:\"expiration\";i:1504076178;s:2:\"ip\";s:14:\"116.193.138.95\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36\";s:5:\"login\";i:1503903378;}s:64:\"a2961af98aec992624e03b4d5a56bfc95a74a55deb818efb599c938baa2fe213\";a:4:{s:10:\"expiration\";i:1504161473;s:2:\"ip\";s:15:\"116.193.138.158\";s:2:\"ua\";s:114:\"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36\";s:5:\"login\";i:1503988673;}}'),(82,3,'gform_recent_forms','a:3:{i:0;s:1:\"3\";i:1;s:1:\"2\";i:2;s:1:\"1\";}'),(83,3,'wp_dashboard_quick_press_last_post_id','797'),(84,3,'community-events-location','a:1:{s:2:\"ip\";s:13:\"116.193.138.0\";}'),(85,3,'nav_menu_recently_edited','24'),(86,3,'managenav-menuscolumnshidden','a:4:{i:0;s:11:\"link-target\";i:1;s:15:\"title-attribute\";i:2;s:3:\"xfn\";i:3;s:11:\"description\";}'),(87,3,'metaboxhidden_nav-menus','a:14:{i:0;s:21:\"add-post-type-product\";i:1;s:29:\"add-post-type-avada_portfolio\";i:2;s:23:\"add-post-type-avada_faq\";i:3;s:33:\"add-post-type-themefusion_elastic\";i:4;s:19:\"add-post-type-slide\";i:5;s:12:\"add-post_tag\";i:6;s:15:\"add-post_format\";i:7;s:15:\"add-product_cat\";i:8;s:15:\"add-product_tag\";i:9;s:22:\"add-portfolio_category\";i:10;s:20:\"add-portfolio_skills\";i:11;s:18:\"add-portfolio_tags\";i:12;s:16:\"add-faq_category\";i:13;s:25:\"add-themefusion_es_groups\";}'),(88,3,'wp_user-settings','libraryContent=browse&editor=html&imgsize=full'),(89,3,'wp_user-settings-time','1503822819'),(90,3,'meta-box-order_page','a:3:{s:4:\"side\";s:149:\"fusion_settings_meta_box,submitdiv,pageparentdiv,postimagediv,featured-image-2_page,featured-image-3_page,featured-image-4_page,featured-image-5_page\";s:6:\"normal\";s:92:\"fusion_builder_layout,revisionsdiv,postcustom,commentstatusdiv,commentsdiv,slugdiv,authordiv\";s:8:\"advanced\";s:17:\"pyre_page_options\";}'),(91,3,'screen_layout_page','2'),(92,3,'closedpostboxes_page','a:0:{}'),(93,3,'metaboxhidden_page','a:7:{i:0;s:24:\"fusion_settings_meta_box\";i:1;s:12:\"revisionsdiv\";i:2;s:10:\"postcustom\";i:3;s:16:\"commentstatusdiv\";i:4;s:11:\"commentsdiv\";i:5;s:7:\"slugdiv\";i:6;s:9:\"authordiv\";}'),(94,1,'maxgalleria_mlp_review_notice','2017-09-30'),(95,2,'session_tokens','a:1:{s:64:\"5b5d32a12710f3702955dc1be8b37579fce760ee39077996d425ba00163876e0\";a:4:{s:10:\"expiration\";i:1507197546;s:2:\"ip\";s:13:\"76.101.198.55\";s:2:\"ua\";s:115:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:5:\"login\";i:1507024746;}}'),(96,2,'maxgalleria_mlp_review_notice','2017-09-23'),(97,2,'gform_recent_forms','a:3:{i:0;s:1:\"3\";i:1;s:1:\"2\";i:2;s:1:\"1\";}'),(98,2,'wp_dashboard_quick_press_last_post_id','3051'),(99,2,'community-events-location','a:1:{s:2:\"ip\";s:12:\"76.101.198.0\";}'),(100,4,'nickname','ema'),(101,4,'first_name',''),(102,4,'last_name',''),(103,4,'description',''),(104,4,'rich_editing','true'),(105,4,'comment_shortcuts','false'),(106,4,'admin_color','fresh'),(107,4,'use_ssl','0'),(108,4,'show_admin_bar_front','true'),(109,4,'locale',''),(110,4,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(111,4,'wp_user_level','10'),(112,4,'dismissed_wp_pointers',''),(113,4,'session_tokens','a:1:{s:64:\"ad89917d7d8bfedf383774dc115ecdf696fac1b4947abf7ff9bbfda3f29fcb08\";a:4:{s:10:\"expiration\";i:1506189288;s:2:\"ip\";s:14:\"65.123.231.210\";s:2:\"ua\";s:115:\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36\";s:5:\"login\";i:1506016488;}}'),(114,4,'maxgalleria_mlp_review_notice','2017-09-24'),(115,4,'gform_recent_forms','a:3:{i:0;s:1:\"3\";i:1;s:1:\"2\";i:2;s:1:\"1\";}'),(116,4,'wp_dashboard_quick_press_last_post_id','2827'),(117,4,'community-events-location','a:1:{s:2:\"ip\";s:12:\"65.123.231.0\";}');
/*!40000 ALTER TABLE `wp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
