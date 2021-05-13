-- MySQL dump 10.19  Distrib 10.3.28-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: liuqcup
-- ------------------------------------------------------
-- Server version	10.3.28-MariaDB

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
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水序號',
  `adminid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '管理人員的帳號',
  `adminname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '管理人員的真實姓名',
  `password` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '管理人員的密碼',
  `salt` char(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '加密用的 Hash Key',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '管理人員的 Key',
  `level` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '管理人員等級碼',
  `phoneno` char(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '管理人員連絡電話',
  `email` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '管理人員連絡用Email',
  `lock` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y' COMMENT '凍結帳號與否',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accounts_adminid_unique` (`adminid`),
  UNIQUE KEY `accounts_salt_unique` (`salt`),
  UNIQUE KEY `accounts_token_unique` (`token`),
  UNIQUE KEY `accounts_phoneno_unique` (`phoneno`),
  UNIQUE KEY `accounts_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,'peter','LuLupeter','ABCD123456','q$g65f.VWP','$2y$10$J0x8MjLz975DYF5MwMFgi.5cCUN4kAoA4GSBZ22QylGRLBDLQNq2G','2','0912345678','hello@test.com','N',NULL,'2021-04-30 00:40:25'),(3,'admin','admin','AB123456','o8N+4iuz!b','$2y$10$Xy.ZUrjO/NpGZlcgm1SpLuTK6Otao0TQ9Z1ybNsol9KNwF4Nm26z.','0','0123456789','test@hello.com','N','2021-04-29 17:15:41','2021-04-29 17:15:41');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水序號',
  `classid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '類別編號',
  `classname` char(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '類別名稱',
  PRIMARY KEY (`id`),
  UNIQUE KEY `classes_classid_unique` (`classid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'1','專賣'),(2,'2','民宿'),(3,'3','商店');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `functions`
--

DROP TABLE IF EXISTS `functions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `functions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水序號',
  `funcid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '功能編號',
  `funcname` char(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '功能名稱',
  PRIMARY KEY (`id`),
  UNIQUE KEY `functions_funcid_unique` (`funcid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `functions`
--

LOCK TABLES `functions` WRITE;
/*!40000 ALTER TABLE `functions` DISABLE KEYS */;
INSERT INTO `functions` VALUES (1,'1','還杯'),(2,'2','借杯'),(3,'3','使用琉行杯消費');
/*!40000 ALTER TABLE `functions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2021_04_13_012724_create_manager_accounts_table',1),(2,'2021_04_19_071505_create_stores_table',1),(3,'2021_04_19_072911_create_storesclass_table',1),(4,'2021_04_19_073023_create_storesfunctions_table',1),(5,'2021_04_19_073339_create_storescupsrecords_table',1),(6,'2021_04_20_024951_create_class_table',1),(7,'2021_04_20_025001_create_functions_table',1),(8,'2021_04_20_035303_add__f_k_to_storesclass_table',1),(9,'2021_04_20_035327_add__f_k_to_storesfunctions_table',1),(10,'2021_04_20_035352_add__f_k_to_storescupsrecords_table',1),(11,'2021_04_25_142502_create_storesagentids_table',1),(12,'2021_04_25_145801_add__f_k_to_storesagentids_table',1),(19,'2021_05_01_121846_modify_stores_table',2),(20,'2021_05_01_125734_modify_stores_table_2',2),(23,'2021_05_01_131345_modify_stores_table_3',3),(27,'2021_05_01_141545_modify_storesclass_table',4),(28,'2021_05_01_142126_modify_storesclass_table_2',4),(29,'2021_05_01_143428_add__f_k_storesclass_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水序號',
  `storeid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家編號',
  `storename` char(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家名稱',
  `qrcodeid` char(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家 QRcode 編碼',
  `phoneno` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '店家連絡電話(DC2Type:json)',
  `email` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '店家連絡用Email(DC2Type:json)',
  `lock` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y' COMMENT '凍結帳號與否',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '店家地址',
  `businessid` char(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家統一編號',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stores_storeid_unique` (`storeid`),
  UNIQUE KEY `stores_qrcodeid_unique` (`qrcodeid`),
  UNIQUE KEY `stores_businessid_unique` (`businessid`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
INSERT INTO `stores` VALUES (1,'10011021','èŠ±ç¾¨æ²åµ','125D1CBA5B0A98C18D',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯84è™Ÿ','125D1CBA5B0A98C18D'),(2,'10031223','é¾œå’–','125D1CBA5B0A991077',NULL,NULL,'Y',NULL,NULL,'ç‰çƒé„‰æ°‘ç”Ÿè·¯63è™Ÿ','125D1CBA5B0A991077'),(3,'10041324','å‰ç¥¥ç´”æžœæ±','125D1CBA5B0A9937EC',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯144è™Ÿ','125D1CBA5B0A9937EC'),(4,'10051425','å‡æª¸èŒ¶','125D1CBA5B0A995F61',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯','125D1CBA5B0A995F61'),(5,'10071627','å³å®¶ç´…èŒ¶å†°','125D1CBA5B0A99AE4B',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯49è™Ÿ','125D1CBA5B0A99AE4B'),(6,'10081728','å°ç‰çƒæµ·æ´‹ç”œå¿ƒ','125D1CBA5B0A99D5C0',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯99è™Ÿ','125D1CBA5B0A99D5C0'),(7,'10091829','å°ç‰çƒ KhÃ³o æžœå­è“å­','125D1CBA5B0A99FD35',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯78è™Ÿ','125D1CBA5B0A99FD35'),(8,'10101930','æ¸…å¿ƒç¦å…¨','125D1CBA5B0A9A24AA',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­æ­£è·¯172-7è™Ÿ','125D1CBA5B0A9A24AA'),(9,'10112031','Hibulaå°ç‰çƒåº—','125D1CBA5B0A9A4C1F',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯88-2è™Ÿ','125D1CBA5B0A9A4C1F'),(10,'10122132','Wave Bar å†°éƒŽ','125D1CBA5B0A9A7394',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸‰æ°‘è·¯308è™Ÿ','125D1CBA5B0A9A7394'),(11,'10132233','ç°çª¯äººæ–‡å’–å•¡ Coral Cafe','125D1CBA5B0A9A9B09',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘æ—è·¯10è™Ÿ','125D1CBA5B0A9A9B09'),(12,'10142334','æ…¢æ¿å’–å•¡','125D1CBA5B0A9AC27E',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬æ¼è·¯112-2è™Ÿ','125D1CBA5B0A9AC27E'),(13,'10152435','OKLATEE','125D1CBA5B0A9AE9F3',NULL,NULL,'Y',NULL,NULL,'ç‰çƒé„‰ä¸­å±±è·¯8-27è™Ÿ','125D1CBA5B0A9AE9F3'),(14,'10162536','æ‚…é£²å°èˆ–','125D1CBA5B0A9B1168',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ç›¸åŸ”è·¯13 ä¹‹ 23 è™Ÿ','125D1CBA5B0A9B1168'),(15,'10172637','å°ç‰çƒç‘ªéº—å®‰','125D1CBA5B0A9B38DD',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯58è™Ÿ','125D1CBA5B0A9B38DD'),(16,'10182738','è¶…é›£å–åˆ°çš„ç´…èŒ¶','125D1CBA5B0A9B6052',NULL,NULL,'Y',NULL,NULL,'ç‰çƒé„‰ä¸­å±±è·¯198ä¹‹1è™Ÿæ—','125D1CBA5B0A9B6052'),(17,'10192839','æ¾æœ¬é®®å¥¶èŒ¶','125D1CBA5B0A9B87C7',NULL,NULL,'Y',NULL,NULL,'ç‰çƒé„‰ä¸­æ­£è·¯172-11è™Ÿ','125D1CBA5B0A9B87C7'),(18,'10213041','7-ELEVEN å°ç‰çƒé–€å¸‚','125D1CBA5B0A9BD6B1',NULL,NULL,'Y',NULL,NULL,'ç‰çƒé„‰æ°‘ç”Ÿè·¯61è™Ÿ','125D1CBA5B0A9BD6B1'),(19,'10223142','7-ELEVEN èŠ±ç“¶å²©é–€å¸‚','125D1CBA5B0A9BFE26',NULL,NULL,'Y',NULL,NULL,'ç‰çƒé„‰ä¸­å±±è·¯51-5è™Ÿ','125D1CBA5B0A9BFE26'),(20,'10233243','7-ELEVEN ç™½ç‡ˆå¡”é–€å¸‚','125D1CBA5B0A9C259B',NULL,NULL,'Y',NULL,NULL,'ç‰çƒé„‰ä¸­æ­£è·¯303ä¹‹2è™Ÿ','125D1CBA5B0A9C259B'),(21,'10324152','è€æ—©ä¼¯é£²æ–™åº—','125D1CC638F79D88B8',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰å¤§ç¦æ‘å’Œå¹³è·¯20-2è™Ÿ','125D1CC638F79D88B8'),(22,'10334253','æ¶¼æ°´å†°åº—','125D1CC638F79DB02D',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯37è™Ÿ','125D1CC638F79DB02D'),(23,'10344354','è€ç”•æ‰‹å·¥ç²‰åœ“','125D1CC638F79DD7A2',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯11-1è™Ÿ','125D1CC638F79DD7A2'),(24,'10354455','è‡ªå·±ä¾†ç´…èŒ¶ç‰›å¥¶åº—','125D1CC638F79DFF17',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬ç¦æ‘æ°‘ç”Ÿè·¯16è™Ÿ','125D1CC638F79DFF17'),(25,'10364556','ã‡ é †é£²æ–™åº—','125D1CC638F79E268C',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰å¤§ç¦æ‘ä¸­æ­£è·¯309è™Ÿ','125D1CC638F79E268C'),(26,'10374657','å†°å¿ƒèŒ¶çŽ‹','125D1CC638F79E4E01',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰å¤§ç¦æ‘å’Œå¹³è·¯3å··31-5è™Ÿ','125D1CC638F79E4E01'),(27,'10384758','å ¤é¦™èŒ¶åŠ','125D1CC638F79E7576',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯230è™Ÿ','125D1CC638F79E7576'),(28,'10394859','æ°´å··èŒ¶å¼„','125D1CC638F79E9CEB',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯26ä¹‹1è™Ÿ','125D1CC638F79E9CEB'),(29,'10404960','ä¸ƒé‡Œé¦™','125D1CC638F79EC460',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬ç¦æ‘ä¸­å±±è·¯24ä¹‹7è™Ÿ','125D1CC638F79EC460'),(30,'10415061','è·èŠ±è»’','125D1CC638F79EEBD5',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­æ­£è·¯182-1è™Ÿ','125D1CC638F79EEBD5'),(31,'10445364','è¥¿æ™’å’–å•¡','125F46CF6CE59F6234',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰','125F46CF6CE59F6234'),(32,'10455465','æ½›æ°´å’–å•¡','125F46CF6D999F89A9',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰ç‰é„‰ä¸‰æ°‘è·¯202è™Ÿ','125F46CF6D999F89A9'),(33,'10465566','å…¨å®¶ä¾¿åˆ©å•†åº—','125F46CF6E839FB11E',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰ç‰é„‰æ°‘ç”Ÿè·¯2-6è™Ÿ','125F46CF6E839FB11E'),(34,'11243344','ç‰å¤èŠé¤æ—…','125D1CBA5B0AAB8F50',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯43-2è™Ÿ','125D1CBA5B0AAB8F50'),(35,'11253445','å°ç‰çƒæ¾ŽåŠå…ç¨…å•†åº—','125D1CBA5B0AABB6C5',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸‰æ°‘è·¯360è™Ÿ','125D1CBA5B0AABB6C5'),(36,'11263546','æµ·é¾œã„‰æ•…äº‹','125D1CBA5B0AABDE3A',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯84è™Ÿ','125D1CBA5B0AABDE3A'),(37,'11273647','èœœä»”ç‰éƒ¨','125D1CBA5B0AAC05AF',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ç™½æ²™è§€å…‰æ¸¯10è™Ÿå•†åº—','125D1CBA5B0AAC05AF'),(38,'11283748','ç‰è¡Œæ¯æ¸…æ´—ç«™','125D1CBA5B0AAC2D24',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘æ—è·¯20è™Ÿ(éŠå®¢ä¸­å¿ƒè³£åº—)','125D1CBA5B0AAC2D24'),(39,'12293849','å°å³¶åœç‰-æµ·æ´‹ç¨ç«‹æ›¸åº—','125D1CBA5B0ABB96D9',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­æ­£è·¯255-1è™Ÿ','125D1CBA5B0ABB96D9'),(40,'12303950','å°ç‰çƒéŠå®¢ä¸­å¿ƒ','125D1CBA5B0ABBBE4E',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘æ—è·¯20è™Ÿ','125D1CBA5B0ABBBE4E'),(41,'12324152','é„­è¨˜é¦™è…¸','125F52F8D800BC0D38',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯204è™Ÿ','125F52F8D800BC0D38'),(42,'12425162','ä¸‰é‡‘é¦¬å•†åº—','125D1CC638F7BD97CA',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯','125D1CC638F7BD97CA'),(43,'12435263','æŽ¢ç´¢æ‹‰ç¾Ž','125D22CDF95EBDBF3F',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰','125D22CDF95EBDBF3F'),(44,'13011021','ç™½ç‡ˆæ¨“æ—…å®¿','125D1CC638F7C6884D',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­æ­£è·¯303-2è™Ÿ','125D1CC638F7C6884D'),(45,'13021122','åŠ ä¾å®¶æ°‘å®¿','125D1CC638F7C6AFC2',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­æ­£è·¯184ã€184-1è™Ÿ','125D1CC638F7C6AFC2'),(46,'13031223','è˜‡å®…æ—…åº—','125D1CC638F7C6D737',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯199è™Ÿ','125D1CC638F7C6D737'),(47,'13041324','æµ·æ´‹é¢¨æƒ…åº¦å‡æ—…é¤¨','125D1CC638F7C6FEAC',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯73ä¹‹1è™Ÿ','125D1CC638F7C6FEAC'),(48,'13051425','é¦¬éžæ°‘å®¿','125D1CC638F7C72621',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­æ­£è·¯170ä¹‹6è™Ÿ','125D1CC638F7C72621'),(49,'13061526','ç‰çƒè°·æ°‘å®¿','125D1CC638F7C74D96',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸‰æ°‘è·¯250è™Ÿ','125D1CC638F7C74D96'),(50,'13071627','å—åœ‹æµ·å²¸æ¸¡å‡ç‰¹è‰²æ°‘å®¿','125D1CC638F7C7750B',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ‰ç¦æ‘è‚šä»”åªè·¯8è™Ÿ','125D1CC638F7C7750B'),(51,'13081728','è²æ®¼æ²™æ°‘å®¿','125D1CC638F7C79C80',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­ç¦æ‘ä¸‰æ°‘è·¯221-2è™Ÿ','125D1CC638F7C79C80'),(52,'13091829','å¤§å³°æ°‘å®¿','125D1CC638F7C7C3F5',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸‰æ°‘è·¯288-3è™Ÿ','125D1CC638F7C7C3F5'),(53,'13101930','å°ç«é›žæ°‘å®¿','125D1CC638F7C7EB6A',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ‰ç¦æ‘å¾©èˆˆè·¯91-9è™Ÿ','125D1CC638F7C7EB6A'),(54,'13112031','é„‰æ‘æ°‘å®¿','125D1CC638F7C812DF',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯177ä¹‹1è™Ÿ','125D1CC638F7C812DF'),(55,'13122132','æ˜Ÿæœˆæ—…åº—','125D1CC638F7C83A54',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯116è™Ÿ','125D1CC638F7C83A54'),(56,'13132233','ç‘šå²©ç¾Žè¡“é¤¨','125D1CC638F7C861C9',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬ç¦æ‘ä¸­å±±è·¯23è™Ÿ','125D1CC638F7C861C9'),(57,'13142334','å¹¸ç¦æ¨‚æ°‘å®¿','125D1CC638F7C8893E',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ‰æ¿è·¯67è™Ÿ','125D1CC638F7C8893E'),(58,'13152435','ç™½æµ·æ°‘å®¿','125D1CC638F7C8B0B3',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ‰æ¿è·¯65è™Ÿ','125D1CC638F7C8B0B3'),(59,'13162536','å¥½å–ã„Ÿç‰¹è‰²æ°‘å®¿','125D1CC638F7C8D828',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯176è™Ÿ','125D1CC638F7C8D828'),(60,'13172637','æ¨‚æ´»å³¶å¶¼','125D1CC638F7C8FF9D',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ‰æ¿è·¯91è™Ÿ','125D1CC638F7C8FF9D'),(61,'13182738','æœˆç‰™ç£','125D1CC638F7C92712',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ‰æ¿è·¯71è™Ÿ','125D1CC638F7C92712'),(62,'13192839','å¤§æµ·çš„å¤©ç©º','125D1CC638F7C94E87',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬æ¼è·¯102è™Ÿ','125D1CC638F7C94E87'),(63,'13202940','1å¹¸ç¦æ°‘å®¿','125D1CC638F7C975FC',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬æ¼è·¯106ã€108è™Ÿ','125D1CC638F7C975FC'),(64,'13213041','7å¹¸ç¦æ°‘å®¿','125D1CC638F7C99D71',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬æ¼è·¯110ã€112è™Ÿ','125D1CC638F7C99D71'),(65,'13223142','ç‰å¤èŠæ—…åº—','125D1CC638F7C9C4E6',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯43-2è™Ÿ','125D1CC638F7C9C4E6'),(66,'13233243','å¥½æ¨£çš„æ°‘å®¿','125D1CC638F7C9EC5B',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸‰æ°‘è·¯129è™Ÿ','125D1CC638F7C9EC5B'),(67,'13243344','æµ·è±šç£æµ·æ™¯æ°‘å®¿','125D1CC638F7CA13D0',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸‰æ°‘è·¯276è™Ÿ','125D1CC638F7CA13D0'),(68,'13253445','æ¼åŸ•æ°‘å®¿','125D1CC638F7CA3B45',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸‰æ°‘è·¯19è™Ÿ','125D1CC638F7CA3B45'),(69,'13263546','å½©ç¹ªæ°‘å®¿','125D1CC638F7CA62BA',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬ç¦æ‘ä¸­å±±è·¯193-1è™Ÿ','125D1CC638F7CA62BA'),(70,'13273647','å¤å ¤æ°‘å®¿','125D1CC638F7CA8A2F',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ‰æ¿è·¯51å··2è™Ÿ','125D1CC638F7CA8A2F'),(71,'13283748','é›²æµ·å±…è§€æ™¯æ°‘å®¿','125D1CC638F7CAB1A4',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ‰ç¦æ‘è‚šä»”åªè·¯6è™Ÿ','125D1CC638F7CAB1A4'),(72,'13293849','æ‰æ¿ç£åè™Ÿç‰¹è‰²æ°‘å®¿','125D1CC638F7CAD919',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰è‚šä»”åªè·¯2-10è™Ÿ','125D1CC638F7CAD919'),(73,'13303950','å°ç‰çƒå¤æ‹‰å…‹æ°‘å®¿','125D1CC638F7CB008E',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯61-20è™Ÿ','125D1CC638F7CB008E'),(74,'13314051','å°ç‰çƒèˆ¹å±‹æ°‘å®¿','125D1CC638F7CB2803',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯224è™Ÿ','125D1CC638F7CB2803'),(75,'13324152','æ³•æ‹‰åœ’','125D22347214CB4F78',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ç›¸åŸ”è·¯92-5è™Ÿ','125D22347214CB4F78'),(76,'13334253','ä¸Šç¦æ°‘å®¿','125D228E4F4BCB76ED',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰','125D228E4F4BCB76ED'),(77,'13344354','å…«æ‘æ°‘å®¿','125D228E4F8BCB9E62',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰','125D228E4F8BCB9E62'),(78,'13354455','æ‚ éŠæ°‘å®¿','125F3A7927B8CBC5D7',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸‰æ°‘è·¯146è™Ÿ','125F3A7927B8CBC5D7'),(79,'13354456','ç·£èšé–£æ°‘å®¿','125F3A79286FCBC5D8',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘ç”Ÿè·¯6è™Ÿ','125F3A79286FCBC5D8'),(80,'13354457','æµ·æ˜Žç æ°‘å®¿','125F3A7929A9CBC5D9',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸Šæ‰è·¯101-5è™Ÿ','125F3A7929A9CBC5D9'),(81,'13354458','èœ‚æ½›æ°´æ°‘å®¿','125F3A792A54CBC5DA',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­èˆˆè·¯33-39è™Ÿ','125F3A792A54CBC5DA'),(82,'13354459','é›ä¸€é›æ°‘å®¿','125F3A792ACECBC5DB',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰å’Œå¹³è·¯ä¸‰å··6-16è™Ÿ','125F3A792ACECBC5DB'),(83,'13354460','èŠ­èŠ­é›…','125F52F8D7A7CBC5DC',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬ç¦æ‘æ°‘ç”Ÿè·¯1è™Ÿ','125F52F8D7A7CBC5DC'),(84,'13354461','ä¸Šæ‰çœ‹æµ·æ°‘å®¿','125F46CF6E839FB11F',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸Šç¦æ‘å¾©èˆˆè·¯6-32è™Ÿ','125F46CF6E839FB11F'),(85,'13354462','å‰ç¥¥æ°‘å®¿','125F46CF6E839FB120',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æ°‘æ¬Šè·¯44ä¹‹5è™Ÿ','125F46CF6E839FB120'),(86,'13354463','åœ‹çŽ‹æ—…åº—','125F46CF6E839FB121',NULL,NULL,'Y',NULL,NULL,'å±æ±ç·šç‰çƒé„‰ä¸­å±±è·¯43ä¹‹16è™Ÿ','125F46CF6E839FB121'),(87,'13354464','å¥½èŠå±‹æ°‘å®¿','125F46CF6E839FB122',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸Šç¦æ‘æ‰æ¿è·¯81å··10è™Ÿ','125F46CF6E839FB122'),(88,'13354465','å¥½å®¿å¤šæ°‘å®¿','125F46CF6E839FB123',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ç›¸åŸ”è·¯66è™Ÿ','125F46CF6E839FB123'),(89,'13354466','èœ‚æ½›æ°´','125F46CF6E839FB124',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­èˆˆè·¯33-39è™Ÿ','125F46CF6E839FB124'),(90,'13354467','å½©ç¹ªå‡æœŸ','125F46CF6E839FB125',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯193-1è™Ÿ','125F46CF6E839FB125'),(91,'13354468','æ˜Ÿå®¿æµ·æ°‘å®¿','125F46CF6E839FB126',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰å—ç¦æ‘ä¸­æ­£è·¯2-2è™Ÿ','125F46CF6E839FB126'),(92,'13354469','æ˜Ÿæœˆæ°‘å®¿','125F46CF6E839FB127',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰ä¸­å±±è·¯116è™Ÿ','125F46CF6E839FB127'),(93,'13354470','æ™¯å¥½ç¡æ°‘å®¿','125F46CF6E839FB128',NULL,NULL,'Y',NULL,NULL,'å±æ±ç¸£ç‰çƒé„‰æœ¬ç¦æ‘æ°‘ç”Ÿè·¯11è™Ÿ','125F46CF6E839FB128'),(94,'13354471','暮旅民宿    ','125F46CF6E839FB129','0988212039',NULL,'Y',NULL,'2021-05-09 06:43:01','屏東縣琉球鄉中興路5-3號','125F46CF6E839FB129'),(95,'13354472','海墘民宿','125F46CF6E839FB130','0988537535',NULL,'Y',NULL,'2021-05-08 21:28:36','屏東縣琉球鄉三民路28號','125F46CF6E839FB130'),(96,'13354473','琉球夯生態旅遊民宿','125F46CF6E839FB131','0980061585',NULL,'Y',NULL,'2021-05-08 21:27:57','屏東縣琉球鄉中山路6號','125F46CF6E839FB131'),(97,'13354474','睡一宿民宿','125F46CF6E839FB132','0919172795',NULL,'Y',NULL,'2021-05-08 21:26:21','屏東縣琉球鄉民生路20-1號','125F46CF6E839FB132'),(98,'13354475','輪廓莊園','125F46CF6E839FB133',NULL,NULL,'N',NULL,NULL,'屏東縣琉球鄉杉福村復興路163號-5','125F46CF6E839FB133'),(100,'13354476','好棒棒','13354476','0912345678',NULL,'N','2021-05-09 07:51:10',NULL,'屏東縣琉球鄉中正路四號','13354476'),(101,'13354477','好棒棒','13354477','0912345678',NULL,'N','2021-05-09 08:05:22',NULL,'屏東縣琉球鄉中正路四號','13354477');
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storesagentids`
--

DROP TABLE IF EXISTS `storesagentids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storesagentids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agentid` char(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家管理人員帳號',
  `agentname` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '店家管理人員姓名',
  `agentphone` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storeid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家編號',
  `salt` char(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '加密用的 Hash Key',
  `token` char(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家管理人員的 Key',
  `password` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家管理人員密碼',
  `lock` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT '凍結帳號與否',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `storesagentids_agentid_unique` (`agentid`),
  UNIQUE KEY `storesagentids_salt_unique` (`salt`),
  UNIQUE KEY `storesagentids_token_unique` (`token`),
  KEY `storesagentids_storeid_foreign` (`storeid`),
  CONSTRAINT `storesagentids_storeid_foreign` FOREIGN KEY (`storeid`) REFERENCES `stores` (`storeid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storesagentids`
--

LOCK TABLES `storesagentids` WRITE;
/*!40000 ALTER TABLE `storesagentids` DISABLE KEYS */;
INSERT INTO `storesagentids` VALUES (3,'peter','Peter Wang','0912345679','13354477','l$,sSceI2o','$2y$10$ih4YeLpz2b61qj804SK9p.CMn7YRWI1UxTCS45svVCj0fMF/HPJVu','CDEF1234','N','2021-05-13 06:03:53','2021-05-13 06:59:13');
/*!40000 ALTER TABLE `storesagentids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storesclass`
--

DROP TABLE IF EXISTS `storesclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storesclass` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水序號',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `storeid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `classid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `storesclass_storeid_index` (`storeid`),
  KEY `storesclass_classid_index` (`classid`),
  CONSTRAINT `storesclass_classid_classes` FOREIGN KEY (`classid`) REFERENCES `classes` (`classid`),
  CONSTRAINT `storesclass_storeid_stores` FOREIGN KEY (`storeid`) REFERENCES `stores` (`storeid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storesclass`
--

LOCK TABLES `storesclass` WRITE;
/*!40000 ALTER TABLE `storesclass` DISABLE KEYS */;
INSERT INTO `storesclass` VALUES (2,'2021-05-13 02:16:47','2021-05-13 02:16:47','13354477','1'),(7,'2021-05-13 02:23:05','2021-05-13 02:23:05','13354475','2');
/*!40000 ALTER TABLE `storesclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storescupsrecords`
--

DROP TABLE IF EXISTS `storescupsrecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storescupsrecords` (
  `id` bigint(20) unsigned NOT NULL COMMENT '流水序號',
  `storeid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家編號',
  `pullcup` int(11) NOT NULL DEFAULT 0 COMMENT '取杯數量',
  `pushcup` int(11) NOT NULL DEFAULT 0 COMMENT '送杯數量',
  `date` datetime NOT NULL DEFAULT current_timestamp() COMMENT '收送時間戳記',
  `adminid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '管理人員的帳號',
  `check` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT '確認章簽',
  `comment` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '備註',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`date`,`id`),
  KEY `storescupsrecords_storeid_adminid_index` (`storeid`,`adminid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
 PARTITION BY RANGE (year(`date`))
SUBPARTITION BY HASH (month(`date`))
(PARTITION `year2019` VALUES LESS THAN (2020)
 (SUBPARTITION `dec2019` ENGINE = InnoDB,
  SUBPARTITION `jan2019` ENGINE = InnoDB,
  SUBPARTITION `feb2019` ENGINE = InnoDB,
  SUBPARTITION `mar2019` ENGINE = InnoDB,
  SUBPARTITION `apr2019` ENGINE = InnoDB,
  SUBPARTITION `may2019` ENGINE = InnoDB,
  SUBPARTITION `jun2019` ENGINE = InnoDB,
  SUBPARTITION `jul2019` ENGINE = InnoDB,
  SUBPARTITION `aug2019` ENGINE = InnoDB,
  SUBPARTITION `sep2019` ENGINE = InnoDB,
  SUBPARTITION `oct2019` ENGINE = InnoDB,
  SUBPARTITION `nov2019` ENGINE = InnoDB),
 PARTITION `year2020` VALUES LESS THAN (2021)
 (SUBPARTITION `dec2020` ENGINE = InnoDB,
  SUBPARTITION `jan2020` ENGINE = InnoDB,
  SUBPARTITION `feb2020` ENGINE = InnoDB,
  SUBPARTITION `mar2020` ENGINE = InnoDB,
  SUBPARTITION `apr2020` ENGINE = InnoDB,
  SUBPARTITION `may2020` ENGINE = InnoDB,
  SUBPARTITION `jun2020` ENGINE = InnoDB,
  SUBPARTITION `jul2020` ENGINE = InnoDB,
  SUBPARTITION `aug2020` ENGINE = InnoDB,
  SUBPARTITION `sep2020` ENGINE = InnoDB,
  SUBPARTITION `oct2020` ENGINE = InnoDB,
  SUBPARTITION `nov2020` ENGINE = InnoDB),
 PARTITION `year2021` VALUES LESS THAN (2022)
 (SUBPARTITION `dec2021` ENGINE = InnoDB,
  SUBPARTITION `jan2021` ENGINE = InnoDB,
  SUBPARTITION `feb2021` ENGINE = InnoDB,
  SUBPARTITION `mar2021` ENGINE = InnoDB,
  SUBPARTITION `apr2021` ENGINE = InnoDB,
  SUBPARTITION `may2021` ENGINE = InnoDB,
  SUBPARTITION `jun2021` ENGINE = InnoDB,
  SUBPARTITION `jul2021` ENGINE = InnoDB,
  SUBPARTITION `aug2021` ENGINE = InnoDB,
  SUBPARTITION `sep2021` ENGINE = InnoDB,
  SUBPARTITION `oct2021` ENGINE = InnoDB,
  SUBPARTITION `nov2021` ENGINE = InnoDB),
 PARTITION `year2022` VALUES LESS THAN (2023)
 (SUBPARTITION `dec2022` ENGINE = InnoDB,
  SUBPARTITION `jan2022` ENGINE = InnoDB,
  SUBPARTITION `feb2022` ENGINE = InnoDB,
  SUBPARTITION `mar2022` ENGINE = InnoDB,
  SUBPARTITION `apr2022` ENGINE = InnoDB,
  SUBPARTITION `may2022` ENGINE = InnoDB,
  SUBPARTITION `jun2022` ENGINE = InnoDB,
  SUBPARTITION `jul2022` ENGINE = InnoDB,
  SUBPARTITION `aug2022` ENGINE = InnoDB,
  SUBPARTITION `sep2022` ENGINE = InnoDB,
  SUBPARTITION `oct2022` ENGINE = InnoDB,
  SUBPARTITION `nov2022` ENGINE = InnoDB),
 PARTITION `year2023` VALUES LESS THAN (2024)
 (SUBPARTITION `dec2023` ENGINE = InnoDB,
  SUBPARTITION `jan2023` ENGINE = InnoDB,
  SUBPARTITION `feb2023` ENGINE = InnoDB,
  SUBPARTITION `mar2023` ENGINE = InnoDB,
  SUBPARTITION `apr2023` ENGINE = InnoDB,
  SUBPARTITION `may2023` ENGINE = InnoDB,
  SUBPARTITION `jun2023` ENGINE = InnoDB,
  SUBPARTITION `jul2023` ENGINE = InnoDB,
  SUBPARTITION `aug2023` ENGINE = InnoDB,
  SUBPARTITION `sep2023` ENGINE = InnoDB,
  SUBPARTITION `oct2023` ENGINE = InnoDB,
  SUBPARTITION `nov2023` ENGINE = InnoDB),
 PARTITION `year2024` VALUES LESS THAN (2025)
 (SUBPARTITION `dec2024` ENGINE = InnoDB,
  SUBPARTITION `jan2024` ENGINE = InnoDB,
  SUBPARTITION `feb2024` ENGINE = InnoDB,
  SUBPARTITION `mar2024` ENGINE = InnoDB,
  SUBPARTITION `apr2024` ENGINE = InnoDB,
  SUBPARTITION `may2024` ENGINE = InnoDB,
  SUBPARTITION `jun2024` ENGINE = InnoDB,
  SUBPARTITION `jul2024` ENGINE = InnoDB,
  SUBPARTITION `aug2024` ENGINE = InnoDB,
  SUBPARTITION `sep2024` ENGINE = InnoDB,
  SUBPARTITION `oct2024` ENGINE = InnoDB,
  SUBPARTITION `nov2024` ENGINE = InnoDB),
 PARTITION `year2025` VALUES LESS THAN (2026)
 (SUBPARTITION `dec2025` ENGINE = InnoDB,
  SUBPARTITION `jan2025` ENGINE = InnoDB,
  SUBPARTITION `feb2025` ENGINE = InnoDB,
  SUBPARTITION `mar2025` ENGINE = InnoDB,
  SUBPARTITION `apr2025` ENGINE = InnoDB,
  SUBPARTITION `may2025` ENGINE = InnoDB,
  SUBPARTITION `jun2025` ENGINE = InnoDB,
  SUBPARTITION `jul2025` ENGINE = InnoDB,
  SUBPARTITION `aug2025` ENGINE = InnoDB,
  SUBPARTITION `sep2025` ENGINE = InnoDB,
  SUBPARTITION `oct2025` ENGINE = InnoDB,
  SUBPARTITION `nov2025` ENGINE = InnoDB),
 PARTITION `year2026` VALUES LESS THAN (2027)
 (SUBPARTITION `dec2026` ENGINE = InnoDB,
  SUBPARTITION `jan2026` ENGINE = InnoDB,
  SUBPARTITION `feb2026` ENGINE = InnoDB,
  SUBPARTITION `mar2026` ENGINE = InnoDB,
  SUBPARTITION `apr2026` ENGINE = InnoDB,
  SUBPARTITION `may2026` ENGINE = InnoDB,
  SUBPARTITION `jun2026` ENGINE = InnoDB,
  SUBPARTITION `jul2026` ENGINE = InnoDB,
  SUBPARTITION `aug2026` ENGINE = InnoDB,
  SUBPARTITION `sep2026` ENGINE = InnoDB,
  SUBPARTITION `oct2026` ENGINE = InnoDB,
  SUBPARTITION `nov2026` ENGINE = InnoDB),
 PARTITION `year2027` VALUES LESS THAN (2028)
 (SUBPARTITION `dec2027` ENGINE = InnoDB,
  SUBPARTITION `jan2027` ENGINE = InnoDB,
  SUBPARTITION `feb2027` ENGINE = InnoDB,
  SUBPARTITION `mar2027` ENGINE = InnoDB,
  SUBPARTITION `apr2027` ENGINE = InnoDB,
  SUBPARTITION `may2027` ENGINE = InnoDB,
  SUBPARTITION `jun2027` ENGINE = InnoDB,
  SUBPARTITION `jul2027` ENGINE = InnoDB,
  SUBPARTITION `aug2027` ENGINE = InnoDB,
  SUBPARTITION `sep2027` ENGINE = InnoDB,
  SUBPARTITION `oct2027` ENGINE = InnoDB,
  SUBPARTITION `nov2027` ENGINE = InnoDB),
 PARTITION `year2028` VALUES LESS THAN (2029)
 (SUBPARTITION `dec2028` ENGINE = InnoDB,
  SUBPARTITION `jan2028` ENGINE = InnoDB,
  SUBPARTITION `feb2028` ENGINE = InnoDB,
  SUBPARTITION `mar2028` ENGINE = InnoDB,
  SUBPARTITION `apr2028` ENGINE = InnoDB,
  SUBPARTITION `may2028` ENGINE = InnoDB,
  SUBPARTITION `jun2028` ENGINE = InnoDB,
  SUBPARTITION `jul2028` ENGINE = InnoDB,
  SUBPARTITION `aug2028` ENGINE = InnoDB,
  SUBPARTITION `sep2028` ENGINE = InnoDB,
  SUBPARTITION `oct2028` ENGINE = InnoDB,
  SUBPARTITION `nov2028` ENGINE = InnoDB),
 PARTITION `year2029` VALUES LESS THAN (2030)
 (SUBPARTITION `dec2029` ENGINE = InnoDB,
  SUBPARTITION `jan2029` ENGINE = InnoDB,
  SUBPARTITION `feb2029` ENGINE = InnoDB,
  SUBPARTITION `mar2029` ENGINE = InnoDB,
  SUBPARTITION `apr2029` ENGINE = InnoDB,
  SUBPARTITION `may2029` ENGINE = InnoDB,
  SUBPARTITION `jun2029` ENGINE = InnoDB,
  SUBPARTITION `jul2029` ENGINE = InnoDB,
  SUBPARTITION `aug2029` ENGINE = InnoDB,
  SUBPARTITION `sep2029` ENGINE = InnoDB,
  SUBPARTITION `oct2029` ENGINE = InnoDB,
  SUBPARTITION `nov2029` ENGINE = InnoDB),
 PARTITION `year2030` VALUES LESS THAN (2031)
 (SUBPARTITION `dec2030` ENGINE = InnoDB,
  SUBPARTITION `jan2030` ENGINE = InnoDB,
  SUBPARTITION `feb2030` ENGINE = InnoDB,
  SUBPARTITION `mar2030` ENGINE = InnoDB,
  SUBPARTITION `apr2030` ENGINE = InnoDB,
  SUBPARTITION `may2030` ENGINE = InnoDB,
  SUBPARTITION `jun2030` ENGINE = InnoDB,
  SUBPARTITION `jul2030` ENGINE = InnoDB,
  SUBPARTITION `aug2030` ENGINE = InnoDB,
  SUBPARTITION `sep2030` ENGINE = InnoDB,
  SUBPARTITION `oct2030` ENGINE = InnoDB,
  SUBPARTITION `nov2030` ENGINE = InnoDB),
 PARTITION `future` VALUES LESS THAN MAXVALUE
 (SUBPARTITION `dec` ENGINE = InnoDB,
  SUBPARTITION `jan` ENGINE = InnoDB,
  SUBPARTITION `feb` ENGINE = InnoDB,
  SUBPARTITION `mar` ENGINE = InnoDB,
  SUBPARTITION `apr` ENGINE = InnoDB,
  SUBPARTITION `may` ENGINE = InnoDB,
  SUBPARTITION `jun` ENGINE = InnoDB,
  SUBPARTITION `jul` ENGINE = InnoDB,
  SUBPARTITION `aug` ENGINE = InnoDB,
  SUBPARTITION `sep` ENGINE = InnoDB,
  SUBPARTITION `oct` ENGINE = InnoDB,
  SUBPARTITION `nov` ENGINE = InnoDB));
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storescupsrecords`
--

LOCK TABLES `storescupsrecords` WRITE;
/*!40000 ALTER TABLE `storescupsrecords` DISABLE KEYS */;
/*!40000 ALTER TABLE `storescupsrecords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storesfunctions`
--

DROP TABLE IF EXISTS `storesfunctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storesfunctions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水序號',
  `storeid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店家編號',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `funcid` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `storesfunctions_funcid_functions` (`funcid`),
  KEY `storesfunctions_storeid_stores` (`storeid`),
  CONSTRAINT `storesfunctions_funcid_functions` FOREIGN KEY (`funcid`) REFERENCES `functions` (`funcid`),
  CONSTRAINT `storesfunctions_storeid_stores` FOREIGN KEY (`storeid`) REFERENCES `stores` (`storeid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storesfunctions`
--

LOCK TABLES `storesfunctions` WRITE;
/*!40000 ALTER TABLE `storesfunctions` DISABLE KEYS */;
INSERT INTO `storesfunctions` VALUES (4,'13354475','2021-05-13 01:43:08','2021-05-13 01:43:08','1'),(5,'13354475','2021-05-13 01:43:08','2021-05-13 01:43:08','2'),(6,'13354474','2021-05-13 01:43:44','2021-05-13 01:43:44','1'),(7,'13354474','2021-05-13 01:43:44','2021-05-13 01:43:44','2'),(8,'13354476','2021-05-13 01:43:48','2021-05-13 01:43:48','1'),(9,'13354476','2021-05-13 01:43:48','2021-05-13 01:43:48','2');
/*!40000 ALTER TABLE `storesfunctions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-13 23:02:54
