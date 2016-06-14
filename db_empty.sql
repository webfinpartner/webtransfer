-- MySQL dump 10.13  Distrib 5.6.30, for Linux (x86_64)
--
-- Host: localhost    Database: wtest2_empty
-- ------------------------------------------------------
-- Server version	5.6.30-log

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

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `index` text CHARACTER SET utf8,
  `town` text CHARACTER SET utf8,
  `street` text CHARACTER SET utf8,
  `house` text CHARACTER SET utf8,
  `kc` text CHARACTER SET utf8,
  `flat` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) CHARACTER SET utf8 NOT NULL,
  `password` varchar(100) CHARACTER SET utf8 NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `family` varchar(100) CHARACTER SET utf8 NOT NULL,
  `doljnost` varchar(100) CHARACTER SET utf8 NOT NULL,
  `otdel` varchar(100) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `telephone` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sms_code` varchar(50) NOT NULL DEFAULT '32165498765461298756512354',
  `sec_auth` varchar(10) NOT NULL DEFAULT 'sms',
  `hash` varchar(100) NOT NULL DEFAULT '',
  `manager` int(11) NOT NULL,
  `permission` varchar(30) NOT NULL DEFAULT 'moderator',
  `users_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=46794 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (46793,'admin','admin123123','Admin','Admin','ревизор','Security','admin@domain.com','','12345','sms','NEZFRURFOTEyN0VGN0QwQTVEQUU4OERDQTUzQkMyOUM4NTc2OEYyRQ==',0,'root',NULL);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_changes`
--

DROP TABLE IF EXISTS `admin_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_changes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `admin_ip` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `change_dttm` datetime DEFAULT NULL,
  `changed_object` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `changed_record_id` int(11) DEFAULT NULL,
  `field` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `old_data` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `new_data` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_changes`
--

LOCK TABLES `admin_changes` WRITE;
/*!40000 ALTER TABLE `admin_changes` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_changes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_user_notes`
--

DROP TABLE IF EXISTS `admin_user_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_user_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `note` text COLLATE utf8_bin NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_admin` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user_notes`
--

LOCK TABLES `admin_user_notes` WRITE;
/*!40000 ALTER TABLE `admin_user_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_user_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arbitration`
--

DROP TABLE IF EXISTS `arbitration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arbitration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  `payed` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arbitration`
--

LOCK TABLES `arbitration` WRITE;
/*!40000 ALTER TABLE `arbitration` DISABLE KEYS */;
/*!40000 ALTER TABLE `arbitration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archive_credits`
--

DROP TABLE IF EXISTS `archive_credits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `master` tinyint(5) unsigned DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `debit` int(11) NOT NULL,
  `previous_debit` int(11) NOT NULL,
  `debit_id_user` int(11) NOT NULL,
  `exchange` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `summ_exchange` decimal(8,2) NOT NULL,
  `kontract` int(20) DEFAULT NULL,
  `date_kontract` date DEFAULT NULL,
  `summa` decimal(8,2) NOT NULL,
  `time` int(11) NOT NULL,
  `percent` double NOT NULL,
  `income` decimal(8,2) NOT NULL,
  `out_summ` decimal(8,2) NOT NULL,
  `out_time` date DEFAULT NULL,
  `payment` text CHARACTER SET utf8 NOT NULL,
  `date_active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `garant_percent` double NOT NULL,
  `garant` int(1) NOT NULL DEFAULT '0',
  `overdraft` int(1) NOT NULL,
  `arbitration` int(11) NOT NULL DEFAULT '0',
  `direct` int(11) NOT NULL,
  `certificate` int(1) NOT NULL DEFAULT '1',
  `certificate_pay_cause` int(1) NOT NULL DEFAULT '0',
  `confirm_payment` int(11) NOT NULL DEFAULT '2',
  `confirm_return` int(1) NOT NULL DEFAULT '2',
  `active` int(1) NOT NULL DEFAULT '1',
  `state` int(11) NOT NULL DEFAULT '1',
  `type` int(1) NOT NULL DEFAULT '1',
  `blocked_money` int(11) NOT NULL,
  `bonus` int(1) NOT NULL,
  `sum_arbitration` decimal(15,2) NOT NULL,
  `sum_own` decimal(15,2) NOT NULL,
  `sum_loan` int(11) NOT NULL DEFAULT '0',
  `arbitration_invest_pay` date DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `account_type` varchar(20) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `debit` (`debit`),
  KEY `id_user` (`id_user`),
  KEY `master` (`master`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archive_credits`
--

LOCK TABLES `archive_credits` WRITE;
/*!40000 ALTER TABLE `archive_credits` DISABLE KEYS */;
/*!40000 ALTER TABLE `archive_credits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archive_transactions`
--

DROP TABLE IF EXISTS `archive_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archive_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '2',
  `summa` double NOT NULL,
  `metod` varchar(50) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `value` int(11) NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  `note_admin` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bonus` int(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `date` (`date`),
  KEY `bonus` (`bonus`),
  KEY `user_ip` (`user_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archive_transactions`
--

LOCK TABLES `archive_transactions` WRITE;
/*!40000 ALTER TABLE `archive_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `archive_transactions` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trig_ins_arch_trans_add_need_recalculate_scors` AFTER INSERT ON `archive_transactions`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(NEW.`id_user`,'archive_transactions') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trig_arch_trans_upd_add_need_recalculate_scors` AFTER UPDATE ON `archive_transactions`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(OLD.`id_user`,'archive_transactions') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trig_arch_trans_del_add_need_recalculate_scors` AFTER DELETE ON `archive_transactions`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(OLD.`id_user`,'archive_transactions') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `automatic`
--

DROP TABLE IF EXISTS `automatic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automatic` (
  `id_user` int(10) unsigned NOT NULL,
  `zajm` tinyint(3) unsigned NOT NULL,
  `zajm_sum` tinyint(3) unsigned NOT NULL,
  `zajm_time` tinyint(3) unsigned NOT NULL,
  `zajm_psnt` tinyint(3) unsigned NOT NULL,
  `credit` tinyint(3) unsigned NOT NULL,
  `credit_max_start_psnt_auto` tinyint(3) unsigned NOT NULL,
  `summ` int(10) unsigned NOT NULL,
  `time` tinyint(3) unsigned NOT NULL,
  `percent` float unsigned NOT NULL,
  `credit_max_start_psnt` float unsigned NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `automatic`
--

LOCK TABLES `automatic` WRITE;
/*!40000 ALTER TABLE `automatic` DISABLE KEYS */;
/*!40000 ALTER TABLE `automatic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banner`
--

DROP TABLE IF EXISTS `banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `url` varchar(150) CHARACTER SET utf8 NOT NULL,
  `button` varchar(30) CHARACTER SET utf8 NOT NULL,
  `foto` text CHARACTER SET utf8 NOT NULL,
  `section` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner`
--

LOCK TABLES `banner` WRITE;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_center`
--

DROP TABLE IF EXISTS `business_center`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_center` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=395 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_center`
--

LOCK TABLES `business_center` WRITE;
/*!40000 ALTER TABLE `business_center` DISABLE KEYS */;
INSERT INTO `business_center` VALUES (1,'Victory Plaza ','A'),(2,'1-й Троицкий переулок 12 ','A'),(3,'Arcus II ','A'),(4,'Central City Tower ','A'),(5,'Daev Plaza ','A'),(6,'Elmia II ','A'),(7,'Four Winds Plaza ','A'),(8,'Green Wood ','A'),(9,'Imperia Tower ','A'),(10,'Lotte Plaza ','A'),(11,'Midland Plaza ','A'),(12,'Nordstar Tower ','A'),(13,'Pallau-NK ','A'),(14,'PREO 8 ','A'),(15,'Radisson Slavyanskaya ','A'),(16,'Riverside Towers ','A'),(17,'Silver House ','A'),(18,'Sky Light ','A'),(19,'Time Center ','A'),(20,'Yan-Ron ','A'),(21,'Авиа-Плаза ','A'),(22,'Авилон Плаза ','A'),(23,'Аврора ','A'),(24,'Аквамарин III ','A'),(25,'Алеексеевская башня ','A'),(26,'Алтуфьевский ','A'),(27,'Амерон ','A'),(28,'Арбат ','A'),(29,'Аркадия ','A'),(30,'Астория Плаза ','A'),(31,'Атмосфера ','A'),(32,'Балчуг Плаза ','A'),(33,'Басманный ','A'),(34,'Басманов ','A'),(35,'Башня 2000 ','A'),(36,'Башня на Набережной ','A'),(37,'Башня Федерации ','A'),(38,'Беговая, 6 ','A'),(39,'Белая Площадь ','A'),(40,'Берлинский Дом ','A'),(41,'Большая Грузинская ','A'),(42,'Большая Дмитровка, 23 ','A'),(43,'Большой Палашевский  ','A'),(44,'Боровский ','A'),(45,'Бородино Плаза ','A'),(46,'Ботанический 5 ','A'),(47,'Бригантина-Холл ','A'),(48,'Бутиковский 7 ','A'),(49,'Вавилон Тауэр ','A'),(50,'Вивальди Плаза ','A'),(51,'Вика ','A'),(52,'Военторг ','A'),(53,'Волконский ','A'),(54,'Газойл Плаза ','A'),(55,'Галс ','A'),(56,'Галс Тауэр ','A'),(57,'Георг Плаза ','A'),(58,'Гнездиковский ','A'),(59,'Голутвинский двор ','A'),(60,'Гончарная, 21 ','A'),(61,'Город Столиц ','A'),(62,'Город Яхт ','A'),(63,'Горький Парк Тауэр ','A'),(64,'Гостиница Москвы ','A'),(65,'Гостиница Реенессанс ','A'),(66,'Гостинный двор ','A'),(67,'Гранд Сетунь Плаза ','A'),(68,'Даймонд Холл ','A'),(69,'Двинцев ','A'),(70,'Депо ','A'),(71,'Довженко, 4 ','A'),(72,'Дом на Саввинской ','A'),(73,'Дом Чаплыгина ','A'),(74,'Домников ','A'),(75,'Домус ','A'),(76,'Дукат Плейс II ','A'),(77,'Дукат Плейс III ','A'),(78,'Европа Билдинг ','A'),(79,'Женевскмй Дом ','A'),(80,'Западные ворота ','A'),(81,'Зенит Плаза ','A'),(82,'Знаменка ','A'),(83,'Имперский Дом ','A'),(84,'Ина Хаус ','A'),(85,'Инженер-А ','A'),(86,'Интерпонт ','A'),(87,'Каланчевская Плаза ','A'),(88,'Кантри Парк ','A'),(89,'Капитал Тауэр ','A'),(90,'Капиталл Плаза ','A'),(91,'Капитолий ','A'),(92,'Кеско Хаус ','A'),(93,'Кожевники Плаза ','A'),(94,'Кондор-Трейд ','A'),(95,'Коперник ','A'),(96,'Красная Пресня, 22 ','A'),(97,'Красная Роза ','A'),(98,'Краснопресненский ','A'),(99,'Кристалл Плаза ','A'),(100,'Кругозор ','A'),(101,'Крылатские холмы ','A'),(102,'Кубик ','A'),(103,'Кулон Билдинг ','A'),(104,'Куткзовъ Холл ','A'),(105,'Кутузофф Тауэр ','A'),(106,'Лайтхаус ','A'),(107,'Легион  III ','A'),(108,'Легион I  ','A'),(109,'Леегенда Цветного ','A'),(110,'Линкор ','A'),(111,'Мал Афанасьевский ','A'),(112,'Маномах ','A'),(113,'Марин Хаус ','A'),(114,'Маяковская Плаза ','A'),(115,'Маяковский ','A'),(116,'Мейерхольд ','A'),(117,'Метрополис ','A'),(118,'Миллениум-Хаус ','A'),(119,'Миньора Плейс ','A'),(120,'Модуль ','A'),(121,'Монарх ','A'),(122,'Мосэнка Парк Тауэр ','A'),(123,'Мосэнка Плаза 2 ','A'),(124,'Мосэнка Плаза 3 ','A'),(125,'Мосэнка Плаза 5 ','A'),(126,'Мосэнка плаза 6 ','A'),(127,'Моховая 1 ','A'),(128,'Мясницкая Plaza ','A'),(129,'На Большом Дровяном, 6 ','A'),(130,'На Газетном ','A'),(131,'На Ордынке ','A'),(132,'На Симоновском валу ','A'),(133,'На Страстном ','A'),(134,'Навигатор ','A'),(135,'Нагатинская 16 ','A'),(136,'Нахимов ','A'),(137,'Неглинная Плаза ','A'),(138,'Никольская плаза ','A'),(139,'Никольская плаза ','A'),(140,'Новая Площадь 8 ','A'),(141,'Новинский Пассаж ','A'),(142,'Ноев Ковчег ','A'),(143,'Обыденский  ','A'),(144,'Океан Плаза ','A'),(145,'Олимпик Плаза ','A'),(146,'Олимпия Парк ','A'),(147,'Орликов плаза ','A'),(148,'Остоженка 10 ','A'),(149,'Павелецкая Плаза ','A'),(150,'Павловский II ','A'),(151,'Палаццо на Цветном ','A'),(152,'Паллау ОСТ ','A'),(153,'Панарома центр ','A'),(154,'Парус ','A'),(155,'Передний Двор ','A'),(156,'Плеханов Плаза ','A'),(157,'Поварская Плаза ','A'),(158,'Пожарский 13 ','A'),(159,'Покровский двор ','A'),(160,'Премьер Плаза ','A'),(161,'Проспект Мира, 3 ','A'),(162,'Пульман ','A'),(163,'Путейский ','A'),(164,'Ринко Плаза ','A'),(165,'Романов Двор 2 ','A'),(166,'Саввинский ','A'),(167,'Садовая Галерея ','A'),(168,'Садовая Плаза ','A'),(169,'Садовая Черногрязская 13/3 ','A'),(170,'Садовническая, 3 ','A'),(171,'Саммит ','A'),(172,'Святогор 2 ','A'),(173,'Святогор IV ','A'),(174,'Северная Башня ','A'),(175,'Северное Сияние ','A'),(176,'Серебряный Город ','A'),(177,'Ситидел ','A'),(178,'Скатерный переулок ','A'),(179,'Смоленский Пассаж ','A'),(180,'Сретенка Комплекс ','A'),(181,'Сухаревский ','A'),(182,'Таганский Пассаж ','A'),(183,'ТДК Тульский ','A'),(184,'Триумф Палас ','A'),(185,'Тропикано ','A'),(186,'Удальцова Плаза ','A'),(187,'Усадьба-Центр ','A'),(188,'Фабрика Станиславского ','A'),(189,'Филлиповский ','A'),(190,'Чайка плаза I ','A'),(191,'Чайка Плаза II ','A'),(192,'Чайка Плаза IIX ','A'),(193,'Чайка Плаза VI ','A'),(194,'Чайка Плаза VII ','A'),(195,'Чайка плаза X ','A'),(196,'Школа журналистов ','A'),(197,'Энерджи Хаус ','A'),(198,'Яковоапостольский ','A'),(199,'Ямская центр ','A'),(200,'Ямское Плаза ','A'),(201,'Японский Дом ','A'),(202,'Скол-Центр ','B'),(203,'Авиатор ','B'),(204,'Аэропорт ','B'),(205,'На Тверской-Ямской ','B'),(206,'GS Тушино ','B'),(207,'Старопетровский Атриум ','B'),(208,'Смольный ','B'),(209,'Ермолаевский ','B'),(210,'Пушкинский ','B'),(211,'Инженер-В+ ','B'),(212,'Чистопрудный, 5 ','B'),(213,'Новый Арбат ','B'),(214,'Полянка, 44 ','B'),(215,'На Звенигородке ','B'),(216,'Краснопресненский-В+ ','B'),(217,'World Trade Center ','B'),(218,'Новинский ','B'),(219,'Малая Пироговская, 18 ','B'),(220,'Крымский вал ','B'),(221,'ВНИИНЕФТЕМАШ ','B'),(222,'Брест Сити ','B'),(223,'Квартал-Сити ','B'),(224,'AFI на Павелецкой ','B'),(225,'На Дубининской ','B'),(226,'Сибирский Альянс ','B'),(227,'На Дербеневской ','B'),(228,'Полларис ','B'),(229,'Ноаоспасский двор ','B'),(230,'Оазис ','B'),(231,'Дербеневская Плаза ','B'),(232,'Omega Plaza ','B'),(233,'Новоостаповский ','B'),(234,'РТС ','B'),(235,'Tupolev Plaza ','B'),(236,'Tupolev Plaza 2 ','B'),(237,'На Спартаковской ','B'),(238,'Новосущевский ','B'),(239,'Минаевский ','B'),(240,'Baker Plaza ','B'),(241,'Фадеева, 5 ','B'),(242,'Золотой век ','B'),(243,'ДУКС ','B'),(244,'Трио ','B'),(245,'Z-Plaza ','B'),(246,'Диагональ-Хаус ','B'),(247,'Mirland ','B'),(248,'Премьер ','B'),(249,'Яблочкова 21 (Я 21) ','B'),(250,'На Гостиничной ','B'),(251,'Бета-Центр ','B'),(252,'Красивый дом ','B'),(253,'Бастион Капитал ','B'),(254,'Октябрьская, 33 ','B'),(255,'Малахит ','B'),(256,'Леоново ','B'),(257,'Отрадный ','B'),(258,'Вольт-Центр ','B'),(259,'Пришвина, 8 ','B'),(260,'Аннино Плаза ','B'),(261,'Solutios ','B'),(262,'Варшавское, 129 ','B'),(263,'Меридио ','B'),(264,'SKY Варшавка ','B'),(265,'Милан ','B'),(266,'Теплый Стан ','B'),(267,'Газфилд ','B'),(268,'9 акров ','B'),(269,'Тоуэр ','B'),(270,'Cherry Tower ','B'),(271,'Вавилово ','B'),(272,'United R&D Centr ','B'),(273,'Москва ','B'),(274,'Варшавская Плаза ','B'),(275,'River Plaza ','B'),(276,'Даниловский Форт ','B'),(277,'Даниловская мануфактура ','B'),(278,'На Загородном ','B'),(279,'Nagatino i-land ','B'),(280,'Технопарк Синтез ','B'),(281,'Магистраль Плаза ','B'),(282,'На Соколе ','B'),(283,'ЦКБ-Связь ','B'),(284,'Верейская Плаза 1 ','B'),(285,'Верейская Плаза 2 ','B'),(286,'Best Plaza ','B'),(287,'Art Plaza ','B'),(288,'Спектр ','B'),(289,'Первый километр ','B'),(290,'Можайский ','B'),(291,'Буревестник ','B'),(292,'Короленко, 3 ','B'),(293,'Вилла Рива ','B'),(294,'Сорус Холл ','B'),(295,' Первомайский  ','B'),(296,'ЛеФОРТ ','B'),(297,'Переведеновский 13/18 ','B'),(298,'Post&Plaza ','B'),(299,'Семеновская, 32 ','B'),(300,'РОСТЭК ','B'),(301,'Интеграл ','B'),(302,'Энтузиастов, 11 ','B'),(303,'Капитал ','B'),(304,'Юникон ','B'),(305,'Восток ','B'),(306,'Helios City ','B'),(307,'Рос 21 ','B'),(308,'Рязанский ','B'),(309,'Альтеза ','B'),(310,'На Пятницкой ','B'),(311,'На Раушской ','B'),(312,'Pallau-SW ','B'),(313,'Жулебино ','B'),(314,'Соколиная гора ','B'),(315,'Arion ','B'),(316,'Виктория-Плаза ','B'),(317,'Синица плаза ','B'),(318,'Solutions (CAO) ','B'),(319,'Вятская, 27 ','B'),(320,'Вятское ','B'),(321,'Дружба ','B'),(322,'Лейпциг Fashion House ','B'),(323,'W-Plaza ','B'),(324,'Шухова Плаза ','B'),(325,'Аэростар ','B'),(326,'Серебряный квартет ','B'),(327,'River Place ','B'),(328,'Riga Land ','B'),(329,'SKY-Point ','B'),(330,'Ферро-Плаза ','B'),(331,'Riverside Station ','B'),(332,'The Yard ','B'),(333,'River City ','B'),(334,'ИРБИС ','B'),(335,'Золотое кольцо ','B'),(336,'Румянцево ','B'),(337,'Большая Дмитровкка, 32 ','B'),(338,'Стендаль ','B'),(339,'РГР ','B'),(340,'W-Plaza 2 ','B'),(341,'Арма завод ','B'),(342,'МВКС ','B'),(343,'1-й Павловский д.3 ','B'),(344,'Крылатский ','B'),(345,'Яуза Плаза 2 ','B'),(346,'Солид Кама ','B'),(347,'Денисовский, 26 ','B'),(348,'Новоданиловский дом ','B'),(349,'Барклай Плаза 2 ','B'),(350,'Сафа ','B'),(351,'Аэробус ','B'),(352,'Велка Плаза ','B'),(353,'Петровский ','B'),(354,'Орликов Плаза ','B'),(355,'Святогор 1 ','B'),(356,'Куб ','B'),(357,'Центр 1 ','B'),(358,'Нижегородский ','B'),(359,'Аурум  ','B'),(360,'Савва ','B'),(361,'Лихоборский ','B'),(362,'Тверской ','B'),(363,'Проспект Мира 19 ','B'),(364,'Калошин переулок, 4 ','B'),(365,'Тимура Фрунзе 20 ','B'),(366,'Вест Парк ','B'),(367,'Аэростар ','B'),(368,'Пьетро Хаус ','B'),(369,'1-й Волконский 13 ','B'),(370,'Земляной Вал 75 ','B'),(371,'Суворовская 19 ','B'),(372,'Стефф ','B'),(373,'Шаболовка 31 ','B'),(374,'Бережковская набережная, 28 ','B'),(375,'Люсиновский ','B'),(376,'Бородинский ','B'),(377,'Брестская 22 ','B'),(378,'3-я Тверская Ямская 39 ','B'),(379,'Елоховский пассаж ','B'),(380,'Шумкина 20 ','B'),(381,'Аркада ','B'),(382,'Шитова 4 ','B'),(383,'Шехтель ','B'),(384,'Майский ','B'),(385,'Саввинская 23 ','B'),(386,'Вавилова 47 ','B'),(387,'Бородинская панорама ','B'),(388,'Большой Николопесковский пер. 13 ','B'),(389,'Риверсайд Стейшн ','B'),(390,'Никитский 5 ','B'),(391,'Кузнецкий мост, 21/5 ','B'),(392,'Варшавское шоссе 17 ','B'),(393,'Фридриха Энгельса, 20 ','B'),(394,'Гостиница Бурятия','В');
/*!40000 ALTER TABLE `business_center` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_exchange_list`
--

DROP TABLE IF EXISTS `card_exchange_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_exchange_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `wtcard_user_id` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `payment_code` int(11) DEFAULT NULL,
  `payment_wallet` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `amount` float(11,0) DEFAULT NULL,
  `dttm` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `api_details` text COLLATE utf8_bin,
  `hash` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_exchange_list`
--

LOCK TABLES `card_exchange_list` WRITE;
/*!40000 ALTER TABLE `card_exchange_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `card_exchange_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_transactions`
--

DROP TABLE IF EXISTS `card_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_card_id` int(11) DEFAULT NULL,
  `to_card_id` int(11) DEFAULT NULL,
  `operation_dttm` datetime DEFAULT NULL,
  `note` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `api_response` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `method` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_ip` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `summa` float(9,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_transactions`
--

LOCK TABLES `card_transactions` WRITE;
/*!40000 ALTER TABLE `card_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `card_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `card_user_id` int(20) DEFAULT NULL,
  `card_proxy` bigint(20) DEFAULT NULL,
  `card_type` int(11) DEFAULT NULL COMMENT '0 - plastic / 1 - virtual',
  `name_on_card` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `creation_date` date DEFAULT NULL,
  `txnId` int(11) DEFAULT NULL,
  `pan` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `last_update` datetime NOT NULL,
  `last_balance` float(9,2) NOT NULL DEFAULT '0.00',
  `kyc` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards_requests`
--

DROP TABLE IF EXISTS `cards_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cards_requests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `name` tinytext COLLATE utf8_bin,
  `birthday` date DEFAULT NULL,
  `phone_mobile` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `country` char(4) COLLATE utf8_bin DEFAULT NULL,
  `city` text COLLATE utf8_bin,
  `zip_code` tinytext COLLATE utf8_bin,
  `prop_adress` text COLLATE utf8_bin,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('created','verified','approved','declined','processed','pay_declined') COLLATE utf8_bin DEFAULT NULL,
  `verified` timestamp NULL DEFAULT NULL,
  `approved` timestamp NULL DEFAULT NULL,
  `declined` timestamp NULL DEFAULT NULL,
  `processed` timestamp NULL DEFAULT NULL,
  `email` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `delivery_address` text COLLATE utf8_bin,
  `delivery_city` tinytext COLLATE utf8_bin,
  `delivery_zip_code` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `delivery_country` char(4) COLLATE utf8_bin DEFAULT NULL,
  `holder_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `decline_error` text COLLATE utf8_bin,
  `card_pan` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `phone_home` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `card_user_id` int(11) DEFAULT NULL,
  `card_proxy` int(11) DEFAULT NULL,
  `surname` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  `card_type` int(11) DEFAULT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `delivery_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards_requests`
--

LOCK TABLES `cards_requests` WRITE;
/*!40000 ALTER TABLE `cards_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `cards_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chanche_users`
--

DROP TABLE IF EXISTS `chanche_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chanche_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `id_volunteer` int(11) NOT NULL,
  `parent_change_counter` int(11) NOT NULL DEFAULT '0',
  `money` double NOT NULL,
  `user_login` text NOT NULL,
  `user_pass` varchar(128) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `online_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(128) NOT NULL,
  `sername` text NOT NULL,
  `patronymic` text NOT NULL,
  `email` varchar(128) NOT NULL,
  `identity` text NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `ip_reg` varchar(100) NOT NULL,
  `phone` text,
  `phone_new` text,
  `skype` varchar(50) NOT NULL,
  `sex` text NOT NULL,
  `born` text NOT NULL,
  `place` text NOT NULL,
  `family_state` text NOT NULL,
  `inn` text NOT NULL,
  `pasport_seria` text NOT NULL,
  `pasport_number` text NOT NULL,
  `pasport_date` text NOT NULL,
  `pasport_kpd` text NOT NULL,
  `pasport_kvn` text NOT NULL,
  `pasport_born` text NOT NULL,
  `bank_name` text NOT NULL,
  `bank_schet` text NOT NULL,
  `bank_bik` text NOT NULL,
  `bank_kor` text NOT NULL,
  `bank_yandex` text NOT NULL,
  `bank_paypal` text NOT NULL,
  `bank_cc` text NOT NULL,
  `bank_cc_date_off` text NOT NULL,
  `bank_w1` text NOT NULL,
  `bank_w1_rub` text NOT NULL,
  `bank_perfectmoney` text NOT NULL,
  `bank_okpay` text NOT NULL,
  `bank_wpay` text NOT NULL,
  `bank_egopay` text NOT NULL,
  `bank_liqpay` text NOT NULL,
  `bank_qiwi` text NOT NULL,
  `bank_tinkoff` text NOT NULL,
  `bank_webmoney` text NOT NULL,
  `bank_rbk` text NOT NULL,
  `bank_mail` text NOT NULL,
  `bank_lava` text NOT NULL,
  `webmoney` text NOT NULL,
  `legal_form` text NOT NULL,
  `kpp` text NOT NULL,
  `ogrn` text NOT NULL,
  `payment_system` varchar(100) NOT NULL,
  `face` text NOT NULL,
  `work_name` text,
  `work_phone` text,
  `work_place` text,
  `work_who` text,
  `work_time` text,
  `work_money` text,
  `bot` int(11) NOT NULL DEFAULT '2',
  `account_verification` text,
  `partner` int(1) NOT NULL DEFAULT '2',
  `partner_plan` int(1) NOT NULL DEFAULT '1',
  `client` int(1) NOT NULL DEFAULT '2',
  `vip` int(1) NOT NULL DEFAULT '1',
  `state` int(11) NOT NULL DEFAULT '2',
  `status_cause` text NOT NULL,
  `payment_default` text NOT NULL,
  `hash_code` text NOT NULL,
  `phone_verification` text NOT NULL,
  `blocked_money` int(11) NOT NULL,
  `doc_request` int(11) NOT NULL,
  `payout_limit` int(11) NOT NULL DEFAULT '0',
  `rating` float NOT NULL,
  `bonuses` float DEFAULT NULL,
  `payment_account` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `fsrc` float NOT NULL,
  `max_loan_available` float NOT NULL,
  `volunteer` int(1) NOT NULL DEFAULT '2',
  `rindex` text NOT NULL,
  `rtown` text NOT NULL,
  `rstreet` text NOT NULL,
  `rhouse` text NOT NULL,
  `rkc` text NOT NULL,
  `rflat` text NOT NULL,
  `findex` text NOT NULL,
  `ftown` text NOT NULL,
  `fstreet` text NOT NULL,
  `fhouse` text NOT NULL,
  `fkc` text NOT NULL,
  `fflat` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chanche_users`
--

LOCK TABLES `chanche_users` WRITE;
/*!40000 ALTER TABLE `chanche_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `chanche_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_auth_tokens`
--

DROP TABLE IF EXISTS `chat_auth_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_auth_tokens` (
  `user_id` int(11) NOT NULL,
  `ins_dttm` datetime NOT NULL,
  `token` varchar(256) COLLATE utf8_bin NOT NULL,
  `upd_dttm` datetime DEFAULT NULL,
  PRIMARY KEY (`token`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_auth_tokens`
--

LOCK TABLES `chat_auth_tokens` WRITE;
/*!40000 ALTER TABLE `chat_auth_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_auth_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat`
--

DROP TABLE IF EXISTS `cometchat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL DEFAULT '0',
  `read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `direction` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `to` (`to`),
  KEY `from` (`from`),
  KEY `direction` (`direction`),
  KEY `read` (`read`),
  KEY `sent` (`sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat`
--

LOCK TABLES `cometchat` WRITE;
/*!40000 ALTER TABLE `cometchat` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_announcements`
--

DROP TABLE IF EXISTS `cometchat_announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `announcement` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `to` int(10) NOT NULL,
  `recd` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `to` (`to`),
  KEY `time` (`time`),
  KEY `to_id` (`to`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_announcements`
--

LOCK TABLES `cometchat_announcements` WRITE;
/*!40000 ALTER TABLE `cometchat_announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_block`
--

DROP TABLE IF EXISTS `cometchat_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_block` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fromid` int(10) unsigned NOT NULL,
  `toid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fromid` (`fromid`),
  KEY `toid` (`toid`),
  KEY `fromid_toid` (`fromid`,`toid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_block`
--

LOCK TABLES `cometchat_block` WRITE;
/*!40000 ALTER TABLE `cometchat_block` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_chatroommessages`
--

DROP TABLE IF EXISTS `cometchat_chatroommessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_chatroommessages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `chatroomid` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `chatroomid` (`chatroomid`),
  KEY `sent` (`sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_chatroommessages`
--

LOCK TABLES `cometchat_chatroommessages` WRITE;
/*!40000 ALTER TABLE `cometchat_chatroommessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_chatroommessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_chatrooms`
--

DROP TABLE IF EXISTS `cometchat_chatrooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_chatrooms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `vidsession` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lastactivity` (`lastactivity`),
  KEY `createdby` (`createdby`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_chatrooms`
--

LOCK TABLES `cometchat_chatrooms` WRITE;
/*!40000 ALTER TABLE `cometchat_chatrooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_chatrooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_chatrooms_users`
--

DROP TABLE IF EXISTS `cometchat_chatrooms_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_chatrooms_users` (
  `userid` int(10) unsigned NOT NULL,
  `chatroomid` int(10) unsigned NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  `isbanned` int(1) DEFAULT '0',
  PRIMARY KEY (`userid`,`chatroomid`) USING BTREE,
  KEY `chatroomid` (`chatroomid`),
  KEY `lastactivity` (`lastactivity`),
  KEY `userid` (`userid`),
  KEY `userid_chatroomid` (`chatroomid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_chatrooms_users`
--

LOCK TABLES `cometchat_chatrooms_users` WRITE;
/*!40000 ALTER TABLE `cometchat_chatrooms_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_chatrooms_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_comethistory`
--

DROP TABLE IF EXISTS `cometchat_comethistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_comethistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel` (`channel`),
  KEY `sent` (`sent`),
  KEY `channel_sent` (`channel`,`sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_comethistory`
--

LOCK TABLES `cometchat_comethistory` WRITE;
/*!40000 ALTER TABLE `cometchat_comethistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_comethistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_guests`
--

DROP TABLE IF EXISTS `cometchat_guests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_guests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lastactivity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lastactivity` (`lastactivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_guests`
--

LOCK TABLES `cometchat_guests` WRITE;
/*!40000 ALTER TABLE `cometchat_guests` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_guests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_status`
--

DROP TABLE IF EXISTS `cometchat_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_status` (
  `userid` int(10) unsigned NOT NULL,
  `message` text,
  `status` enum('available','away','busy','invisible','offline') DEFAULT NULL,
  `typingto` int(10) unsigned DEFAULT NULL,
  `typingtime` int(10) unsigned DEFAULT NULL,
  `isdevice` int(1) unsigned NOT NULL DEFAULT '0',
  `lastactivity` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  KEY `typingto` (`typingto`),
  KEY `typingtime` (`typingtime`),
  KEY `cometchat_status_lastactivity` (`lastactivity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_status`
--

LOCK TABLES `cometchat_status` WRITE;
/*!40000 ALTER TABLE `cometchat_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cometchat_videochatsessions`
--

DROP TABLE IF EXISTS `cometchat_videochatsessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cometchat_videochatsessions` (
  `username` varchar(255) NOT NULL,
  `identity` varchar(255) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`username`),
  KEY `username` (`username`),
  KEY `identity` (`identity`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cometchat_videochatsessions`
--

LOCK TABLES `cometchat_videochatsessions` WRITE;
/*!40000 ALTER TABLE `cometchat_videochatsessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `cometchat_videochatsessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contribution`
--

DROP TABLE IF EXISTS `contribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contribution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 NOT NULL,
  `percent` decimal(11,2) NOT NULL,
  `month` int(11) NOT NULL,
  `bonus` int(11) NOT NULL,
  `charge` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contribution`
--

LOCK TABLES `contribution` WRITE;
/*!40000 ALTER TABLE `contribution` DISABLE KEYS */;
/*!40000 ALTER TABLE `contribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contributions`
--

DROP TABLE IF EXISTS `contributions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contributions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `amount` double NOT NULL,
  `partner` double NOT NULL,
  `webfin` double NOT NULL,
  `debit` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contributions`
--

LOCK TABLES `contributions` WRITE;
/*!40000 ALTER TABLE `contributions` DISABLE KEYS */;
/*!40000 ALTER TABLE `contributions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` int(10) NOT NULL,
  `iso2` char(2) DEFAULT NULL,
  `iso3` char(3) DEFAULT NULL,
  `num_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Афганистан',93,'AF','AFG',NULL),(2,'Албания',355,'AL','ALB',NULL),(3,'Алжир',213,'DZ','DZA',NULL),(4,'Аргентина',54,'AR','ARG',NULL),(5,'Армения',374,'AM','ARM',NULL),(6,'Австралия',61,'AU','AUS',NULL),(7,'Австрия',43,'AT','AUT',NULL),(8,'Азербайджан',994,'AZ','AZE',NULL),(9,'Бахрейн',973,'BH','BHR',NULL),(10,'Бангладеш',880,'BD','BGD',NULL),(11,'Беларусь',375,'BY','BLR',NULL),(12,'Бенин',229,'BJ','BEN',NULL),(13,'Босния и Герцеговина',387,'BA','BIH',NULL),(14,'Бразилия',55,'BR','BRA',NULL),(15,'Болгария',359,'BG','BGR',NULL),(16,'Венесуэла',58,'VE','VEN',NULL),(17,'Вьетнам',84,'VN','VNM',NULL),(18,'Венгрия',36,'HU','HUN',NULL),(19,'Великобритания',44,NULL,NULL,NULL),(20,'Голландия',31,NULL,NULL,NULL),(21,'Гамбия',220,'GM','GMB',NULL),(22,'Грузия',995,'GE','GEO',NULL),(23,'Германия',49,'DE','DEU',NULL),(24,'Гана',233,'GH','GHA',NULL),(25,'Греция',30,'GR','GRC',NULL),(26,'Гуам',1,'GU','GUM',NULL),(27,'Гондурас',504,'HN','HND',NULL),(28,'Гонконг',852,'HK','HKG',NULL),(29,'Дания',45,'DK','DNK',NULL),(30,'Египет',20,'EG','EGY',NULL),(31,'Исландия',354,'IS','IS',NULL),(32,'Индия',91,'IN','IND',NULL),(33,'Индонезия',62,'ID','IDN',NULL),(34,'Ирландия',353,'IE','IRL',NULL),(35,'Израиль',972,'IL','ISR',NULL),(36,'Италия',39,'IT','ITA',NULL),(37,'Ирак',964,'IQ','IRQ',NULL),(38,'Иран',98,'IR','IRN',NULL),(39,'Иордания',962,'JO','JOR',NULL),(40,'Испания',34,'ES','ESP',NULL),(41,'Йемен',967,'YE','YEM',NULL),(42,'Камбоджа',855,'KH','KHM',NULL),(43,'Канада',1,'CA','CAN',NULL),(44,'Колумбия',57,'CO','COL',NULL),(45,'КонгоДем.респ.',243,NULL,NULL,NULL),(46,'Куба',53,'CU','CUB',NULL),(47,'Кипр',357,'CY','CYP',NULL),(48,'Китай',86,'CN','CHN',NULL),(49,'Казахстан',7,'KZ','KAZ',NULL),(50,'Кыргызстан',996,'KG','KGZ',NULL),(51,'Катар',974,'QA','QAT',NULL),(52,'Латвия',371,'LV','LVA',NULL),(53,'Либерия',231,'LR','LBR',NULL),(54,'Лихтенштейн',423,'LI','LIE',NULL),(55,'Литва',370,'LT','LTU',NULL),(56,'Люксембург',352,'LU','LUX',NULL),(57,'Македония',389,'MK','MKD',NULL),(58,'Мадагаскар',261,'MG','MDG',NULL),(59,'Малави',265,'MW','MWI',NULL),(60,'Малайзия',60,'MY','MYS',NULL),(61,'Мальдивы',960,NULL,NULL,NULL),(62,'Мали',223,'ML','MLI',NULL),(63,'Мальта',356,'MT','MLT',NULL),(64,'Мексика',52,'MX','MEX',NULL),(65,'Молдова',373,'MD','MDA',NULL),(66,'Монголия',976,'MN','MNG',NULL),(67,'Марокко',212,'MA','MAR',NULL),(68,'Мозамбик',258,'MZ','MOZ',NULL),(69,'Намибия',264,'NA','NAM',NULL),(70,'Непал',977,'NP','NPL',NULL),(71,'Никарагуа',505,'NI','NIC',NULL),(72,'Нигерия',234,'NG','NGA',NULL),(73,'Норвегия',47,'NO','NOR',NULL),(74,'Оман',968,'OM','OMN',NULL),(75,'ОАЭ',971,'AE','ARE',NULL),(76,'Пакистан',92,'PK','PAK',NULL),(77,'Палестина',972,NULL,NULL,NULL),(78,'Панама',507,'PA','PAN',NULL),(79,'Парагвай',595,'PY','PRY',NULL),(80,'Польша',48,'PL','POL',NULL),(81,'Португалия',351,'PT','PRT',NULL),(82,'Румыния',40,'RO','ROU',NULL),(83,'Россия',7,'RU','RUS',NULL),(84,'Сенегал',221,'SN','SEN',NULL),(85,'Сербия',381,'RS','SRB',NULL),(86,'Сьерра-Леоне',232,'SL','SLE',NULL),(87,'Саудовская Аравия',966,'SA','SAU',NULL),(88,'Сингапур',65,'SG','SGP',NULL),(89,'Словения',386,'SI','SVN',NULL),(90,'Сирия',963,'SY','SYR',NULL),(91,'США',1,'US','USA',NULL),(92,'Таджикистан',992,'TJ','TJK',NULL),(93,'Таиланд',66,'TH','THA',NULL),(94,'Тринидад и Тобаго',1,'TT','TTO',NULL),(95,'Тунис',216,'TN','TUN',NULL),(96,'Турция',90,'TR','TUR',NULL),(97,'Туркменистан',993,'TM','TKM',NULL),(98,'Украина',380,'UA','UKR',NULL),(99,'Уругвай',598,'UY','URY',NULL),(100,'Финляндия',358,'FI','FIN',NULL),(101,'Филиппины',63,'PH','PHL',NULL),(102,'Черногория',382,'ME','MNE',NULL),(103,'Чехия',420,NULL,NULL,NULL),(104,'Шри-Ланка',94,'LK','LKA',NULL),(105,'Швеция',46,'SE','SWE',NULL),(106,'Швейцария',41,'CH','CHE',NULL),(107,'Эквадор',593,'EC','ECU',NULL),(108,'Эстония',372,'EE','EST',NULL),(109,'Эфиопия',251,'ET','ETH',NULL),(110,'Южная Африка',27,'ZA','ZAF',NULL),(111,'Ямайка',187,'JM','JAM',NULL),(112,'Япония',81,'JP','JPN',NULL),(113,'Бельгия',32,'BE','BEL',NULL),(114,'ОАЭ',882,'AE','ARE',NULL);
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries_list`
--

DROP TABLE IF EXISTS `countries_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries_list` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` int(10) NOT NULL,
  `iso2` char(3) DEFAULT NULL,
  `iso3` char(3) DEFAULT NULL,
  `eng_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `iso2` (`iso2`)
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=51;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries_list`
--

LOCK TABLES `countries_list` WRITE;
/*!40000 ALTER TABLE `countries_list` DISABLE KEYS */;
INSERT INTO `countries_list` VALUES (1,'Россия',7,'RU','RUS','Russia'),(2,'Австралия',61,'AU','AUS','Australia'),(3,'Австрия',43,'AT','AUT','Austria'),(4,'Азербайджан',994,'AZ','AZE','Azerbaijan'),(5,'Албания',355,'AL','ALB','Albania'),(6,'Алжир',213,'DZ','DZA','Algeria'),(7,'Виргинские Острова (США)',1,'VI','VIR','US Virgin Islands'),(8,'Американское Самоа',1,'AS','ASM','American Samoa'),(9,'Ангилья',1,'AI','AIA','Anguilla'),(10,'Ангола',244,'AO','AGO','Angola'),(11,'Андорра',376,'AD','AND','andorra'),(12,'Антарктида',672,'ATA','ATA','Antarctica'),(13,'Антигуа и Барбуда',1,'AG','ATG','Antigua and Barbuda'),(14,'Аргентина',54,'AR','ARG','Argentina'),(15,'Армения',374,'AM','ARM','Armenia'),(16,'Аруба',297,'AW','ABW','Aruba'),(17,'Афганистан',93,'AF','AFG','Afghanistan'),(18,'Багамские Острова',1242,'BS','BHS','Bahamas'),(19,'Бангладеш',880,'BD','BGD','Bangladesh'),(20,'Барбадос',1246,'BB','BRB','Barbados'),(21,'Бахрейн',973,'BH','BHR','Bahrain'),(22,'Белоруссия',375,'BY','BLR','Belarus'),(23,'Белиз',501,'BZ','BLZ','Belize'),(24,'Бельгия',32,'BE','BEL','Belgium'),(25,'Бенин',229,'BJ','BEN','Benin'),(26,'Бермудские Острова',1441,'BM','BMU','Bermuda'),(27,'Мьянма',95,'MM','MMR','Burma (Myanmar)'),(28,'Болгария',359,'BG','BGR','Bulgaria'),(29,'Боливия',591,'BO','BOL','Bolivia'),(30,'Босния и Герцеговина',387,'BA','BIH','Bosnia and Herzegovina'),(31,'Ботсвана',267,'BW','BWA','Botswana'),(32,'Бразилия',55,'BR','BRA','Brazil'),(33,'Виргинские Острова (Великобритания)',1,'VG','VGB','British Virgin Islands'),(34,'Бруней',673,'BN','BRN','Brunei'),(35,'Буркина-Фасо',226,'BF','BFA','Burkina Faso'),(36,'Бурунди',257,'BI','BDI','Burundi'),(37,'Бутан',975,'BT','BTN','Butane'),(38,'Вануату',678,'VU','VUT','Vanuatu'),(39,'Венгрия',36,'HU','HUN','Hungary'),(40,'Венесуэла',58,'VE','VEN','Venezuela'),(41,'Вьетнам',84,'VN','VNM','Vietnam'),(42,'Габон',241,'GA','GAB','Gabon'),(43,'Гайана',592,'GY','GUY','Guyana'),(44,'Гаити',509,'HT','HTI','Haiti'),(45,'Гамбия',220,'GM','GMB','Gambia'),(46,'Гана',233,'GH','GHA','Ghana'),(47,'Гватемала',502,'GT','GTM','Guatemala'),(48,'Гвинея',224,'GN','GIN','Guinea'),(49,'Гвинея-Бисау',245,'GW','GNB','Guinea-Bissau'),(50,'Германия',49,'DE','DEU','Germany'),(51,'Гибралтар',350,'GI','GIB','Gibraltar'),(52,'Гондурас',504,'HN','HND','Honduras'),(53,'Гонконг',852,'HK','HKG','Hong Kong'),(54,'Гренада',1,'GD','GRD','Grenada'),(55,'Гренландия',299,'GL','GRL','Greenland'),(56,'Греция',30,'GR','GRC','Greece'),(57,'Грузия',995,'GE','GEO','Georgia'),(58,'Гуам',1,'GU','GUM','Guam'),(59,'Дания',45,'DK','DNK','Denmark'),(60,'Демократическая Республика Конго',243,'CD','COD','Democratic Republic of the Congo'),(61,'Джибути',253,'DJ','DJI','Djibouti'),(62,'Доминика',1,'DM','DMA','Dominica'),(63,'Доминиканская Республика',1,'DO','DOM','Dominican Republic'),(64,'Египет',20,'EG','EGY','Egypt'),(65,'Замбия',260,'ZM','ZMB','Zambia'),(66,'Зимбабве',263,'ZW','ZWE','Zimbabwe'),(67,'Йемен',967,'YE','YEM','Yemen'),(68,'Израиль',972,'IL','ISR','Israel'),(69,'Индия',91,'IN','IND','India'),(70,'Индонезия',62,'ID','IDN','Indonesia'),(71,'Иордания',962,'JO','JOR','Jordan'),(72,'Ирак',964,'IQ','IRQ','Iraq'),(73,'Иран',98,'IR','IRN','Iran'),(74,'Ирландия',353,'IE','IRL','Ireland'),(75,'Исландия',354,'IS','IS','Iceland'),(76,'Испания',34,'ES','ESP','Spain'),(77,'Италия',39,'IT','ITA','Italy'),(78,'Кабо-Верде',238,'CV','CPV','Cape Verde'),(79,'Казахстан',7,'KZ','KAZ','Kazakhstan'),(80,'Каймановы острова',1,'KY','CYM','Cayman Islands'),(81,'Камбоджа',855,'KH','KHM','Cambodia'),(82,'Камерун',237,'CM','CMR','Cameroon'),(83,'Канада',1,'CA','CAN','Canada'),(84,'Катар',974,'QA','QAT','Qatar'),(85,'Кения',254,'KE','KEN','Kenya'),(86,'Кипр',357,'CY','CYP','Cyprus'),(87,'Кирибати',686,'KI','KIR','Kiribati'),(88,'Китайская Республика',86,'CN','CHN','China'),(89,'Кокосовые острова',61,'CC','CCK','Cocos (Keeling) Islands'),(90,'Колумбия',57,'CO','COL','Colombia'),(91,'Коморские о-ва',269,'KM','COM','Comoros'),(92,'Коста-Рика',506,'CR','CRC','Costa Rica'),(93,'Кот-д’Ивуар',225,'CI','CIV','Cote d Ivoire'),(94,'Куба',53,'CU','CUB','Cuba'),(95,'Кувейт',965,'KW','KWT','Kuwait'),(96,'Киргизия',996,'KG','KGZ','Kyrgyzstan'),(97,'Лаос',856,'LA','LAO','Laos'),(98,'Латвия',371,'LV','LVA','Latvia'),(99,'Лесото',266,'LS','LSO','Lesotho'),(100,'Либерия',231,'LR','LBR','Liberia'),(101,'Ливан',961,'LB','LBN','Lebanon'),(102,'Ливия',218,'LY','LBY','Libya'),(103,'Литва',370,'LT','LTU','Lithuania'),(104,'Лихтенштейн',423,'LI','LIE','Liechtenstein'),(105,'Люксембург',352,'LU','LUX','Luxembourg'),(106,'Маврикий',230,'MU','MUS','Mauritius'),(107,'Мавритания',222,'MR','MRT','Mauritania'),(108,'Мадагаскар',261,'MG','MDG','Madagascar'),(109,'Майотта',262,'YT','MYT','Mayotte'),(110,'Макао',853,'MO','MAC','Macau'),(111,'Македония',389,'MK','MKD','Macedonia'),(112,'Малави',265,'MW','MWI','Malawi'),(113,'Малайзия',60,'MY','MYS','Malaysia'),(114,'Мали',223,'ML','MLI','Mali'),(115,'Мальдивы',960,'MV','MDV','Maldives'),(116,'Мальта',356,'MT','MLT','Malta'),(117,'Марокко',212,'MA','MAR','Morocco'),(118,'Маршалловы Острова',692,'MH','MHL','Marshall Islands'),(119,'Мексика',52,'MX','MEX','Mexico'),(120,'Микронезия',691,'FM','FSM','Micronesia'),(121,'Мозамбик',258,'MZ','MOZ','Mozambique'),(122,'Молдавия',373,'MD','MDA','Moldova'),(123,'Монако',377,'MC','MCO','Monaco'),(124,'Монголия',976,'MN','MNG','Mongolia'),(125,'Монтсеррат',1,'MS','MSR','Montserrat'),(126,'Намибия',264,'NA','NAM','Namibia'),(127,'Науру',674,'NR','NRU','Nauru'),(128,'Непал',977,'NP','NPL','Nepal'),(129,'Нигер',227,'NE','NER','Niger'),(130,'Нигерия',234,'NG','NGA','Nigeria'),(131,'Нидерландские Антильские о-ва',599,'AN','ANT','Netherlands Antilles'),(132,'Нидерланды',31,'NL','NLD','Netherlands'),(133,'Никарагуа',505,'NI','NIC','Nicaragua'),(134,'Ниуэ',683,'NU','NIU','Niue'),(135,'Новая Зеландия',64,'NZ','NZL','New Zealand'),(136,'Новая Каледония',687,'NC','NCL','New Caledonia'),(137,'Норвегия',47,'NO','NOR','Norway'),(138,'Великобритания',44,'GB','GBR','United Kingdom'),(139,'Объединённые Арабские Эмираты',971,'AE','ARE','UAE'),(140,'Оман',968,'OM','OMN','Oman'),(141,'Мэн',44,'IMN','IMN','Isle Of Man'),(142,'Норфолк остров',672,'NFK','NFK','Norfolk Islands'),(143,'Остров Рождества',61,'CX','CXR','Christmas Island'),(144,'Остров Святой Елены',290,'SH','SHN','Saint Helena Island'),(145,'Острова Кука',682,'CK','COK','Cook Islands'),(146,'Острова Питкэрн',870,'PN','PCN','Pitcairn Islands'),(147,'Тёркс и Кайкос',1,'TC','TCA','Turks and Caicos Islands'),(148,'Пакистан',92,'PK','PAK','Pakistan'),(149,'Палау',680,'PW','PLW','Palau'),(150,'Панама',507,'PA','PAN','Panama'),(151,'Папуа',675,'PG','PNG','Papua New Guinea'),(152,'Парагвай',595,'PY','PRY','Paraguay'),(153,'Перу',51,'PE','PER','Peru'),(154,'Польша',48,'PL','POL','Poland'),(155,'Португалия',351,'PT','PRT','Portugal'),(156,'Пуэрто-Рико',1,'PR','PRI','Puerto-Rico'),(157,'Республика Конго',242,'CG','COG','Republic of the Congo'),(158,'Руанда',250,'RW','RWA','Rwanda'),(159,'Румыния',40,'RO','ROU','Romania'),(160,'Сальвадор',503,'SV','SLV','Salvador'),(161,'Самоа',685,'WS','WSM','Samoa'),(162,'Сан - Марино',378,'SM','SMR','San - Marino'),(163,'Санкт-Бартелеми',590,'BLM','BLM','St. Barthelemy'),(164,'Сан-Томе и Принсипи',239,'ST','STP','Sao Tome and Principe'),(165,'Саудовская Аравия',966,'SA','SAU','Saudi Arabia'),(166,'Свазиленд',268,'SZ','SWZ','Swaziland'),(167,'Ватикан',39,'VA','VAT','Vatican City'),(168,'Северная Корея',850,'KP','PRK','North Korea'),(169,'Северные Марианские о-ва',1,'MP','MNP','Northern Mariana Islands'),(170,'Сейшельские о-ва',248,'SC','SYC','Seychelles'),(171,'Сектор Газа',970,'SCG','SCG','Gaza Sector'),(172,'Сенегал',221,'SN','SEN','Senegal'),(173,'Сен-Мартен',1,'MAF','MAF','Saint Marten'),(174,'Сен-Пьер и Микелон',508,'PM','SPM','Saint Pierre and Miquelon'),(175,'Сент-Винсент и Гренадины',1,'VC','VCT','Saint Vincent '),(176,'Сент-Китс и Невис',1,'KN','KNA','Saint Kitts and Nevis'),(177,'Сент-Люсия',1,'LC','LCA','Saint Lucia'),(178,'Сербия',381,'RS','SRB','Serbia'),(179,'Сингапур',65,'SG','SGP','Singapore'),(180,'Сирия',963,'SY','SYR','Syria'),(181,'Словакия',421,'SK','SVK','Slovakia'),(182,'Словения',386,'SI','SVN','Slovenia'),(183,'Соломоновы Острова',677,'SB','SLB','Solomon islands'),(184,'Сомали',252,'SO','SOM','Somalia'),(185,'Судан',249,'SD','SDN','Sudan'),(186,'Суринам',597,'SR','SUR','Surinam'),(187,'Соединённые Штаты Америки',1,'US','USA','USA'),(188,'Сьерра-Леоне',232,'SL','SLE','Sierra Leone'),(189,'Таджикистан',992,'TJ','TJK','Tajikistan'),(190,'Тайвань',886,'TW','TWN','Taiwan'),(191,'Таиланд',66,'TH','THA','Thailand'),(192,'Танзания',255,'TZ','TZA','Tanzania'),(193,'Тимор-Лешти',670,'TL','TLS','Timor-Leste'),(194,'Того',228,'TG','TGO','Togo'),(195,'Токелау',690,'TK','TKL','Tokelau'),(196,'Тонга',676,'TO','TON','Tonga'),(197,'Тринидад и Тобаго',1,'TT','TTO','Trinidad and Tobago'),(198,'Тувалу',688,'TV','TUV','Tuvalu'),(199,'Тунис',216,'TN','TUN','Tunisia'),(200,'Туркмения',993,'TM','TKM','Turkmenistan'),(201,'Турция',90,'TR','TUR','Turkey'),(202,'Уганда',256,'UG','UGA','Uganda'),(203,'Узбекистан',998,'UZ','UZB','Uzbekistan'),(204,'Украина',38,'UA','UKR','Ukraine'),(205,'Уоллис и Футуна',681,'WF','WLF','Wallis and Futuna'),(206,'Уругвай',598,'UY','URY','Uruguay'),(207,'Фарерские острова',298,'FO','FRO','Faroe Islands'),(208,'Фиджи',679,'FJ','FJI','Fiji'),(209,'Филиппины',63,'PH','PHL','Philippines'),(210,'Финляндия',358,'FI','FIN','Finland'),(211,'Фолклендские о-ва',500,'FK','FLK','Falkland Islands'),(212,'Франция',33,'FR','FRA','France'),(213,'Французская Полинезия',689,'PF','PYF','French polynesia'),(214,'Хорватия',385,'HR','HRV','Croatia'),(215,'Центрально-Африканская Республика',236,'CF','CAF','Central African Republic'),(216,'Чад',235,'TD','TCD','Chad'),(217,'Черногория',382,'ME','MNE','Montenegro'),(218,'Чехия',420,'CZ','CZE','Czech Republic'),(219,'Чили',56,'CL','CHL','Chile'),(220,'Швейцария',41,'CH','CHE','Switzerland'),(221,'Швеция',46,'SE','SWE','Sweden'),(222,'Шри-Ланка',94,'LK','LKA','Sri Lanka'),(223,'Эквадор',593,'EC','ECU','Ecuador'),(224,'Экваториальная Гвинея',240,'GQ','GNQ','Equatorial Guinea'),(225,'Эритрея',291,'ER','ERI','Eritrea'),(226,'Эстония',372,'EE','EST','Estonia'),(227,'Эфиопия',251,'ET','ETH','Ethiopia'),(228,'Южная Африка',27,'ZA','ZAF','South Africa'),(229,'Южная Корея',82,'KR','KOR','South Korea'),(230,'Ямайка',1,'JM','JAM','Jamaica'),(231,'Япония',81,'JP','JPN','Japan'),(232,'Джерси',7,'JE','JEY','Jersey'),(233,'Западная Сахара',7,'EH','ESH','West Sahara'),(234,'Бонэйр',7,'BQ','BES','Bonair'),(235,'Аландские острова',7,'AX','ALA','Aland islands'),(236,'Гвиана',7,'GF','GUF','French Guiana'),(237,'Нагорно-Карабахская Республика',385,'NX','NKR','Nagorni - Karabakh'),(238,'Гваделупа',93,'GP','GLP','Gvadelupa'),(239,'Синт-Эстатиус',7,'BQ1','BES','St Estatius');
/*!40000 ALTER TABLE `countries_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_state`
--

DROP TABLE IF EXISTS `credit_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_credit` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `ip` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `performer` text CHARACTER SET utf8,
  `note` text CHARACTER SET utf8,
  `cause` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `id_credit` (`id_credit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_state`
--

LOCK TABLES `credit_state` WRITE;
/*!40000 ALTER TABLE `credit_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `credit_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credits`
--

DROP TABLE IF EXISTS `credits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `master` tinyint(5) unsigned DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `debit` int(11) NOT NULL,
  `previous_debit` int(11) NOT NULL,
  `debit_id_user` int(11) NOT NULL,
  `exchange` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `summ_exchange` decimal(8,2) NOT NULL,
  `kontract` int(20) DEFAULT NULL,
  `date_kontract` date DEFAULT NULL,
  `summa` decimal(8,2) NOT NULL,
  `time` int(11) NOT NULL,
  `percent` double NOT NULL,
  `income` decimal(8,2) NOT NULL,
  `out_summ` decimal(8,2) NOT NULL,
  `out_time` date DEFAULT NULL,
  `payment` text CHARACTER SET utf8 NOT NULL,
  `date_active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `garant_percent` double NOT NULL,
  `garant` int(1) NOT NULL DEFAULT '0',
  `overdraft` int(1) NOT NULL,
  `arbitration` int(11) NOT NULL DEFAULT '0',
  `direct` int(11) NOT NULL,
  `certificate` int(1) NOT NULL DEFAULT '1',
  `certificate_pay_cause` int(1) NOT NULL DEFAULT '0',
  `confirm_payment` int(11) NOT NULL DEFAULT '2',
  `confirm_return` int(1) NOT NULL DEFAULT '2',
  `active` int(1) NOT NULL DEFAULT '1',
  `state` int(11) NOT NULL DEFAULT '1',
  `type` int(1) NOT NULL DEFAULT '1',
  `blocked_money` int(11) NOT NULL,
  `bonus` int(1) NOT NULL,
  `sum_arbitration` decimal(15,2) NOT NULL,
  `sum_own` decimal(15,2) NOT NULL,
  `sum_loan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `arbitration_invest_pay` date DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `account_type` varchar(20) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `debit` (`debit`),
  KEY `id_user` (`id_user`),
  KEY `summa` (`summa`),
  KEY `percent` (`percent`),
  KEY `avtomat_garant` (`state`,`type`,`bonus`,`percent`,`arbitration`),
  KEY `out_time` (`out_time`),
  KEY `state_type_garant_direct` (`state`,`type`,`garant`,`direct`),
  KEY `debit_id_user` (`debit_id_user`),
  KEY `exchange_state_type` (`exchange`,`type`,`state`),
  KEY `birja` (`percent`,`state`,`type`),
  KEY `auto_pay` (`type`,`garant`,`state`,`arbitration`,`direct`,`out_time`),
  KEY `master` (`master`),
  KEY `birja_new` (`state`,`garant`,`type`,`bonus`,`time`,`percent`,`summa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credits`
--

LOCK TABLES `credits` WRITE;
/*!40000 ALTER TABLE `credits` DISABLE KEYS */;
/*!40000 ALTER TABLE `credits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credits_backup`
--

DROP TABLE IF EXISTS `credits_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credits_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `debit` int(11) NOT NULL,
  `previous_debit` int(11) NOT NULL,
  `debit_id_user` int(11) NOT NULL,
  `exchange` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `summ_exchange` decimal(8,2) NOT NULL,
  `kontract` int(20) DEFAULT NULL,
  `date_kontract` date DEFAULT NULL,
  `summa` decimal(8,2) NOT NULL,
  `time` int(11) NOT NULL,
  `percent` double NOT NULL,
  `income` decimal(8,2) NOT NULL,
  `out_summ` decimal(8,2) NOT NULL,
  `out_time` date DEFAULT NULL,
  `payment` text CHARACTER SET utf8 NOT NULL,
  `date_active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `garant_percent` double NOT NULL,
  `garant` int(1) NOT NULL DEFAULT '0',
  `overdraft` int(1) NOT NULL,
  `arbitration` int(11) NOT NULL DEFAULT '0',
  `direct` int(11) NOT NULL,
  `certificate` int(1) NOT NULL DEFAULT '1',
  `confirm_payment` int(11) NOT NULL DEFAULT '2',
  `confirm_return` int(1) NOT NULL DEFAULT '2',
  `active` int(1) NOT NULL DEFAULT '1',
  `state` int(11) NOT NULL DEFAULT '1',
  `type` int(1) NOT NULL DEFAULT '1',
  `blocked_money` int(11) NOT NULL,
  `bonus` int(1) NOT NULL,
  `sum_arbitration` decimal(15,2) NOT NULL,
  `sum_own` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `debit` (`debit`),
  KEY `id_user` (`id_user`),
  KEY `summa` (`summa`),
  KEY `percent` (`percent`),
  KEY `avtomat_garant` (`state`,`type`,`bonus`,`percent`,`arbitration`),
  KEY `state` (`state`),
  KEY `direct` (`direct`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credits_backup`
--

LOCK TABLES `credits_backup` WRITE;
/*!40000 ALTER TABLE `credits_backup` DISABLE KEYS */;
/*!40000 ALTER TABLE `credits_backup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `code` int(11) NOT NULL,
  `short_name` varchar(11) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `symbol` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency`
--

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_all_currency`
--

DROP TABLE IF EXISTS `currency_exchange_all_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_all_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8_bin NOT NULL,
  `num` varchar(5) COLLATE utf8_bin NOT NULL,
  `currency` varchar(100) COLLATE utf8_bin NOT NULL,
  `country_id` int(11) NOT NULL,
  `location` varchar(100) COLLATE utf8_bin NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_all_currency`
--

LOCK TABLES `currency_exchange_all_currency` WRITE;
/*!40000 ALTER TABLE `currency_exchange_all_currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_all_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_block_user`
--

DROP TABLE IF EXISTS `currency_exchange_block_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_block_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `note` text COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_block_user`
--

LOCK TABLES `currency_exchange_block_user` WRITE;
/*!40000 ALTER TABLE `currency_exchange_block_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_block_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_last_deals`
--

DROP TABLE IF EXISTS `currency_exchange_last_deals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_last_deals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(64) COLLATE utf8_bin NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  `datetime` bigint(20) NOT NULL,
  `deal_data` text COLLATE utf8_bin,
  `order_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_last_deals`
--

LOCK TABLES `currency_exchange_last_deals` WRITE;
/*!40000 ALTER TABLE `currency_exchange_last_deals` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_last_deals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_new_paymant_systems`
--

DROP TABLE IF EXISTS `currency_exchange_new_paymant_systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_new_paymant_systems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
  `count` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_new_paymant_systems`
--

LOCK TABLES `currency_exchange_new_paymant_systems` WRITE;
/*!40000 ALTER TABLE `currency_exchange_new_paymant_systems` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_new_paymant_systems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_operator_notes`
--

DROP TABLE IF EXISTS `currency_exchange_operator_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_operator_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `is_arhiv` int(11) NOT NULL,
  `server_order_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8_bin,
  `operator_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `date_modified` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_operator_notes`
--

LOCK TABLES `currency_exchange_operator_notes` WRITE;
/*!40000 ALTER TABLE `currency_exchange_operator_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_operator_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_operator_reject_notes`
--

DROP TABLE IF EXISTS `currency_exchange_operator_reject_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_operator_reject_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `is_arhiv` int(11) NOT NULL,
  `server_order_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8_bin,
  `operator_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `date_modified` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_operator_reject_notes`
--

LOCK TABLES `currency_exchange_operator_reject_notes` WRITE;
/*!40000 ALTER TABLE `currency_exchange_operator_reject_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_operator_reject_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_order_details`
--

DROP TABLE IF EXISTS `currency_exchange_order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_status` int(11) DEFAULT NULL,
  `orig_order_data` text CHARACTER SET utf8,
  `payment_system` int(11) NOT NULL,
  `summa` varchar(200) DEFAULT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_order_details`
--

LOCK TABLES `currency_exchange_order_details` WRITE;
/*!40000 ALTER TABLE `currency_exchange_order_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_orders`
--

DROP TABLE IF EXISTS `currency_exchange_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` varchar(256) NOT NULL,
  `seller_user_id` int(11) NOT NULL,
  `seller_ip` varchar(256) NOT NULL,
  `seller_amount` varchar(256) NOT NULL,
  `seller_fee` varchar(256) NOT NULL,
  `seller_set_up_date` varchar(256) NOT NULL,
  `seller_confirmation_date` varchar(256) DEFAULT NULL,
  `seller_payout_limit` float NOT NULL,
  `seller_transaction_id` int(11) NOT NULL,
  `seller_confirmed` int(11) NOT NULL,
  `seller_payment_system_set` varchar(256) NOT NULL,
  `buyer_user_id` int(11) NOT NULL,
  `buyer_account_data` varchar(256) DEFAULT NULL,
  `buyer_amount_down` varchar(256) NOT NULL,
  `buyer_amount_up` varchar(256) NOT NULL,
  `buyer_confirmed` int(11) NOT NULL,
  `buyer_payment_system_set` varchar(256) NOT NULL,
  `buyer_confirmation_date` varchar(256) DEFAULT NULL,
  `buyer_document_image_path` varchar(256) NOT NULL,
  `buyer_payout_limit` float NOT NULL,
  `buyer_transaction_id` int(11) NOT NULL,
  `buy_payment_data` text NOT NULL,
  `sell_payment_data` text NOT NULL,
  `sms` int(11) DEFAULT '0',
  `wt_set` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_orders`
--

LOCK TABLES `currency_exchange_orders` WRITE;
/*!40000 ALTER TABLE `currency_exchange_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_orders_arhive`
--

DROP TABLE IF EXISTS `currency_exchange_orders_arhive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_orders_arhive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_order_id` int(11) NOT NULL,
  `server_id` varchar(256) NOT NULL,
  `seller_user_id` int(11) NOT NULL,
  `initiator` int(11) NOT NULL,
  `buyer_order_id` int(11) NOT NULL,
  `seller_ip` varchar(256) NOT NULL,
  `seller_amount` varchar(256) NOT NULL,
  `seller_fee` varchar(256) NOT NULL,
  `seller_set_up_date` varchar(256) NOT NULL,
  `seller_confirmation_date` varchar(256) DEFAULT NULL,
  `seller_payout_limit` float NOT NULL,
  `seller_transaction_id` int(11) NOT NULL,
  `seller_confirmed` int(11) NOT NULL,
  `seller_payment_system_set` varchar(256) NOT NULL,
  `buyer_user_id` int(11) NOT NULL,
  `buyer_account_data` varchar(256) DEFAULT NULL,
  `buyer_amount_down` varchar(256) NOT NULL,
  `buyer_amount_up` varchar(256) NOT NULL,
  `buyer_confirmed` int(11) NOT NULL,
  `buyer_payment_system_set` varchar(256) NOT NULL,
  `buyer_confirmation_date` varchar(256) DEFAULT NULL,
  `buyer_document_image_path` varchar(256) NOT NULL,
  `seller_document_image_path` varchar(256) NOT NULL,
  `buyer_send_money_date` datetime NOT NULL,
  `seller_send_money_date` datetime NOT NULL,
  `buyer_get_money_date` datetime NOT NULL,
  `seller_get_money_date` datetime NOT NULL,
  `buyer_payout_limit` float NOT NULL,
  `buyer_transaction_id` int(11) NOT NULL,
  `buy_payment_data` text NOT NULL,
  `sell_payment_data` text NOT NULL,
  `confirm_type` int(11) NOT NULL,
  `payed_system` int(11) NOT NULL,
  `sell_system` int(11) NOT NULL,
  `wt_set` int(11) NOT NULL,
  `order_details_arhiv` text,
  `sms` int(11) DEFAULT '0',
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_orders_arhive`
--

LOCK TABLES `currency_exchange_orders_arhive` WRITE;
/*!40000 ALTER TABLE `currency_exchange_orders_arhive` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_orders_arhive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_payment_systems`
--

DROP TABLE IF EXISTS `currency_exchange_payment_systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_payment_systems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `machine_name` varchar(255) NOT NULL,
  `is_bank` int(11) NOT NULL,
  `present_out` int(11) NOT NULL,
  `present_in` int(11) NOT NULL,
  `fee_percentage` float NOT NULL,
  `fee_percentage_add` float NOT NULL,
  `fee_min` float NOT NULL,
  `fee_max` float NOT NULL,
  `method_calc_fee` varchar(100) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `public_path_to_icon` varchar(255) NOT NULL,
  `active` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `branch_parent_id` int(11) DEFAULT NULL,
  `root_currency` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_payment_systems`
--

LOCK TABLES `currency_exchange_payment_systems` WRITE;
/*!40000 ALTER TABLE `currency_exchange_payment_systems` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_payment_systems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_payment_systems_groups`
--

DROP TABLE IF EXISTS `currency_exchange_payment_systems_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_payment_systems_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `machin_name` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
  `human_name` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
  `public_path_to_icon` varchar(256) NOT NULL,
  `language_id` int(11) NOT NULL,
  `show_add_new` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_payment_systems_groups`
--

LOCK TABLES `currency_exchange_payment_systems_groups` WRITE;
/*!40000 ALTER TABLE `currency_exchange_payment_systems_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_payment_systems_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_payment_systems_rates`
--

DROP TABLE IF EXISTS `currency_exchange_payment_systems_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_payment_systems_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_systems_id` int(11) NOT NULL,
  `rate` float NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_payment_systems_rates`
--

LOCK TABLES `currency_exchange_payment_systems_rates` WRITE;
/*!40000 ALTER TABLE `currency_exchange_payment_systems_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_payment_systems_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_problem_chat`
--

DROP TABLE IF EXISTS `currency_exchange_problem_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_problem_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_subject` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `to` int(11) NOT NULL DEFAULT '0',
  `text` text CHARACTER SET utf8mb4 NOT NULL,
  `other` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
  `date` datetime NOT NULL,
  `suport_team_operator_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `user_to_operator` int(11) NOT NULL,
  `document` varchar(256) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `operator` int(11) DEFAULT NULL,
  `show` int(11) DEFAULT '0',
  `show_operator` int(11) NOT NULL DEFAULT '0',
  `orig_order_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_problem_chat`
--

LOCK TABLES `currency_exchange_problem_chat` WRITE;
/*!40000 ALTER TABLE `currency_exchange_problem_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_problem_chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_problem_suject`
--

DROP TABLE IF EXISTS `currency_exchange_problem_suject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_problem_suject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `machin_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `human_name` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
  `language_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_problem_suject`
--

LOCK TABLES `currency_exchange_problem_suject` WRITE;
/*!40000 ALTER TABLE `currency_exchange_problem_suject` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_problem_suject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_user_paymant_data`
--

DROP TABLE IF EXISTS `currency_exchange_user_paymant_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency_exchange_user_paymant_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `payment_system_id` int(11) NOT NULL,
  `payment_data` varchar(655) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_user_paymant_data`
--

LOCK TABLES `currency_exchange_user_paymant_data` WRITE;
/*!40000 ALTER TABLE `currency_exchange_user_paymant_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_user_paymant_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `img` varchar(100) DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  `num` int(11) NOT NULL,
  `img2` varchar(100) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dynamic_vars`
--

DROP TABLE IF EXISTS `dynamic_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dynamic_vars` (
  `name` varchar(20) COLLATE utf8_bin NOT NULL,
  `value` varchar(128) COLLATE utf8_bin NOT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dynamic_vars`
--

LOCK TABLES `dynamic_vars` WRITE;
/*!40000 ALTER TABLE `dynamic_vars` DISABLE KEYS */;
/*!40000 ALTER TABLE `dynamic_vars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exchange_api_log`
--

DROP TABLE IF EXISTS `exchange_api_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exchange_api_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `method` varchar(50) COLLATE utf8_bin NOT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `data` text COLLATE utf8_bin,
  `status` int(11) NOT NULL DEFAULT '0',
  `note` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exchange_api_log`
--

LOCK TABLES `exchange_api_log` WRITE;
/*!40000 ALTER TABLE `exchange_api_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `exchange_api_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exchange_users_sets`
--

DROP TABLE IF EXISTS `exchange_users_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exchange_users_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sets` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exchange_users_sets`
--

LOCK TABLES `exchange_users_sets` WRITE;
/*!40000 ALTER TABLE `exchange_users_sets` DISABLE KEYS */;
/*!40000 ALTER TABLE `exchange_users_sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exchanges_list`
--

DROP TABLE IF EXISTS `exchanges_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exchanges_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `source_url` varchar(200) NOT NULL,
  `foto` varchar(200) NOT NULL,
  `info` text NOT NULL,
  `description` text NOT NULL,
  `lang` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exchanges_list`
--

LOCK TABLES `exchanges_list` WRITE;
/*!40000 ALTER TABLE `exchanges_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `exchanges_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8 NOT NULL,
  `answer` text CHARACTER SET utf8 NOT NULL,
  `position` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` varchar(30) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `sys_id` varchar(12) NOT NULL,
  `where` int(1) DEFAULT NULL,
  `text` text,
  `state` int(1) NOT NULL DEFAULT '1',
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_state` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback_answer`
--

DROP TABLE IF EXISTS `feedback_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback_answer` (
  `id_back` int(11) NOT NULL,
  `id_feed` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  PRIMARY KEY (`id_feed`),
  KEY `id_back` (`id_back`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback_answer`
--

LOCK TABLES `feedback_answer` WRITE;
/*!40000 ALTER TABLE `feedback_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gift_cards`
--

DROP TABLE IF EXISTS `gift_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gift_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL,
  `date_buy` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 - новый\r\n1 - активирован\r\n2 - продан',
  `date_activate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gift_cards`
--

LOCK TABLES `gift_cards` WRITE;
/*!40000 ALTER TABLE `gift_cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `gift_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gift_cards_history`
--

DROP TABLE IF EXISTS `gift_cards_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gift_cards_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gift_card_id` int(11) DEFAULT NULL,
  `ins_date` datetime DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gift_cards_history`
--

LOCK TABLES `gift_cards_history` WRITE;
/*!40000 ALTER TABLE `gift_cards_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `gift_cards_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `giftguarant_cards`
--

DROP TABLE IF EXISTS `giftguarant_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `giftguarant_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issuer_user_id` int(11) DEFAULT NULL,
  `current_user_id` int(11) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL,
  `percent` float(11,2) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `date_buy` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 - новый\r\n1 - передана\r\n2 - на бирже\r\n3 - использована',
  `date_activate` datetime DEFAULT NULL,
  `pin` int(11) DEFAULT NULL,
  `market_price` float(9,2) DEFAULT NULL,
  `market_receive_to` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 - standart\r\n1 - investgift',
  `invest` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `giftguarant_cards`
--

LOCK TABLES `giftguarant_cards` WRITE;
/*!40000 ALTER TABLE `giftguarant_cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `giftguarant_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `giftguarant_cards_history`
--

DROP TABLE IF EXISTS `giftguarant_cards_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `giftguarant_cards_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giftguarant_card_id` int(11) DEFAULT NULL,
  `ins_date` datetime DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `mode` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `from_wallet` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `to_wallet` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `transfer_summ` float(9,2) DEFAULT NULL,
  `ip_address` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `giftguarant_cards_history`
--

LOCK TABLES `giftguarant_cards_history` WRITE;
/*!40000 ALTER TABLE `giftguarant_cards_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `giftguarant_cards_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inbox`
--

DROP TABLE IF EXISTS `inbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_to` int(11) NOT NULL,
  `recipient_view` int(1) NOT NULL DEFAULT '1',
  `user_from` int(11) NOT NULL,
  `sender_view` int(1) NOT NULL DEFAULT '1',
  `admin` int(11) NOT NULL,
  `debit` int(11) NOT NULL,
  `debit_account_type` varchar(25) DEFAULT NULL,
  `debit_account_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `cause` varchar(500) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_to` (`user_to`),
  KEY `user_from` (`user_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inbox`
--

LOCK TABLES `inbox` WRITE;
/*!40000 ALTER TABLE `inbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `inbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invest`
--

DROP TABLE IF EXISTS `invest`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `kontract` int(11) DEFAULT NULL,
  `summa` double NOT NULL,
  `income` double NOT NULL,
  `time` int(11) NOT NULL,
  `bonus` int(11) NOT NULL,
  `type_invest` varchar(100) CHARACTER SET utf8 NOT NULL,
  `payment` varchar(50) CHARACTER SET utf8 NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `out_summ` double NOT NULL,
  `date_kontract` date DEFAULT NULL,
  `out_time` date DEFAULT NULL,
  `percent` double NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  `charge` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invest`
--

LOCK TABLES `invest` WRITE;
/*!40000 ALTER TABLE `invest` DISABLE KEYS */;
/*!40000 ALTER TABLE `invest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invest_state`
--

DROP TABLE IF EXISTS `invest_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invest_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_invest` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `ip` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `performer` text CHARACTER SET utf8,
  `note` text CHARACTER SET utf8,
  `cause` text CHARACTER SET utf8,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invest_state`
--

LOCK TABLES `invest_state` WRITE;
/*!40000 ALTER TABLE `invest_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `invest_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lang`
--

DROP TABLE IF EXISTS `lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `locale` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lang`
--

LOCK TABLES `lang` WRITE;
/*!40000 ALTER TABLE `lang` DISABLE KEYS */;
INSERT INTO `lang` VALUES (1,'en','English','en_US'),(2,'ru','Русский','ru_RU'),(3,'cn','简体中文','zh_CN');
/*!40000 ALTER TABLE `lang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `limited_usa_users`
--

DROP TABLE IF EXISTS `limited_usa_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `limited_usa_users` (
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `limited_usa_users`
--

LOCK TABLES `limited_usa_users` WRITE;
/*!40000 ALTER TABLE `limited_usa_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `limited_usa_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logincontrol`
--

DROP TABLE IF EXISTS `logincontrol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logincontrol` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL DEFAULT '',
  `logins` int(11) NOT NULL DEFAULT '0',
  `state` varchar(20) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logincontrol`
--

LOCK TABLES `logincontrol` WRITE;
/*!40000 ALTER TABLE `logincontrol` DISABLE KEYS */;
/*!40000 ALTER TABLE `logincontrol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_servers`
--

DROP TABLE IF EXISTS `mail_servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `host` varchar(100) COLLATE utf8_bin NOT NULL,
  `port` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `protocol` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT 'SMTP',
  `enabled` int(11) NOT NULL DEFAULT '1',
  `from` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `master` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_servers`
--

LOCK TABLES `mail_servers` WRITE;
/*!40000 ALTER TABLE `mail_servers` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mailing_list`
--

DROP TABLE IF EXISTS `mailing_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mailing_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(90) COLLATE utf8_bin NOT NULL,
  `ins_date` datetime DEFAULT NULL,
  `ip` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mailing_list`
--

LOCK TABLES `mailing_list` WRITE;
/*!40000 ALTER TABLE `mailing_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `mailing_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_send_1day_statistic`
--

DROP TABLE IF EXISTS `message_send_1day_statistic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_send_1day_statistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messenger_type_id` int(11) NOT NULL,
  `send_service_name` varchar(25) DEFAULT '0',
  `status_success_cnt` int(11) NOT NULL DEFAULT '0',
  `status_success_check_cnt` int(11) NOT NULL DEFAULT '0',
  `status_pending_cnt` int(11) NOT NULL DEFAULT '0',
  `status_fail_cnt` int(11) NOT NULL DEFAULT '0',
  `status_fail_check_cnt` int(11) NOT NULL DEFAULT '0',
  `status_another_cnt` int(11) NOT NULL DEFAULT '0',
  `status_another_check_cnt` int(11) NOT NULL DEFAULT '0',
  `create_dttm` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `avg_send_time_in_sec` float NOT NULL DEFAULT '0',
  `balance` float NOT NULL DEFAULT '0',
  `cost` float(9,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_send_1day_statistic`
--

LOCK TABLES `message_send_1day_statistic` WRITE;
/*!40000 ALTER TABLE `message_send_1day_statistic` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_send_1day_statistic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_send_1hour_statistic`
--

DROP TABLE IF EXISTS `message_send_1hour_statistic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_send_1hour_statistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messenger_type_id` int(20) NOT NULL,
  `send_service_name` varchar(25) DEFAULT NULL,
  `country_short_name` varchar(20) DEFAULT NULL,
  `operator_name` varchar(25) DEFAULT NULL,
  `status_success_cnt` int(11) NOT NULL DEFAULT '0',
  `status_success_check_cnt` int(11) NOT NULL DEFAULT '0',
  `status_pending_cnt` int(11) NOT NULL DEFAULT '0',
  `status_fail_cnt` int(11) NOT NULL DEFAULT '0',
  `status_fail_check_cnt` int(11) NOT NULL DEFAULT '0',
  `status_another_cnt` int(11) NOT NULL DEFAULT '0',
  `status_another_check_cnt` int(11) NOT NULL DEFAULT '0',
  `create_dttm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `avg_send_time_in_sec` float NOT NULL DEFAULT '0',
  `balance` float NOT NULL DEFAULT '0',
  `cost` float(9,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_send_1hour_statistic`
--

LOCK TABLES `message_send_1hour_statistic` WRITE;
/*!40000 ALTER TABLE `message_send_1hour_statistic` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_send_1hour_statistic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message_send_5min_statistic`
--

DROP TABLE IF EXISTS `message_send_5min_statistic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_send_5min_statistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `messenger_type_id` int(11) NOT NULL,
  `send_service_name` varchar(25) DEFAULT '0',
  `status_success_cnt` int(11) NOT NULL DEFAULT '0',
  `status_success_check_cnt` int(11) NOT NULL DEFAULT '0',
  `status_pending_cnt` int(11) NOT NULL DEFAULT '0',
  `status_fail_cnt` int(11) NOT NULL DEFAULT '0',
  `status_fail_check_cnt` int(11) NOT NULL DEFAULT '0',
  `status_another_cnt` int(11) NOT NULL DEFAULT '0',
  `status_another_check_cnt` int(11) NOT NULL DEFAULT '0',
  `create_dttm` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `avg_send_time_in_sec` float NOT NULL DEFAULT '0',
  `balance` float NOT NULL DEFAULT '0',
  `cost` float(9,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message_send_5min_statistic`
--

LOCK TABLES `message_send_5min_statistic` WRITE;
/*!40000 ALTER TABLE `message_send_5min_statistic` DISABLE KEYS */;
/*!40000 ALTER TABLE `message_send_5min_statistic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messengers`
--

DROP TABLE IF EXISTS `messengers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messengers` (
  `id` int(11) NOT NULL,
  `machine_name` varchar(45) NOT NULL,
  `human_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messengers`
--

LOCK TABLES `messengers` WRITE;
/*!40000 ALTER TABLE `messengers` DISABLE KEYS */;
/*!40000 ALTER TABLE `messengers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messengers_services`
--

DROP TABLE IF EXISTS `messengers_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messengers_services` (
  `id` int(11) NOT NULL,
  `machine_name` varchar(45) NOT NULL,
  `human_name` varchar(45) NOT NULL,
  `messenger_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messengers_services`
--

LOCK TABLES `messengers_services` WRITE;
/*!40000 ALTER TABLE `messengers_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `messengers_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_p2p_pairs_statistic`
--

DROP TABLE IF EXISTS `module_p2p_pairs_statistic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_p2p_pairs_statistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_system_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `update_interval` int(11) NOT NULL,
  `update_window` int(11) NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `last` int(11) NOT NULL,
  `average_data` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_p2p_pairs_statistic`
--

LOCK TABLES `module_p2p_pairs_statistic` WRITE;
/*!40000 ALTER TABLE `module_p2p_pairs_statistic` DISABLE KEYS */;
/*!40000 ALTER TABLE `module_p2p_pairs_statistic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multisite_hashes`
--

DROP TABLE IF EXISTS `multisite_hashes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multisite_hashes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hash` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `create_dttm` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `access_count` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(80) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multisite_hashes`
--

LOCK TABLES `multisite_hashes` WRITE;
/*!40000 ALTER TABLE `multisite_hashes` DISABLE KEYS */;
/*!40000 ALTER TABLE `multisite_hashes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_table`
--

DROP TABLE IF EXISTS `new_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `val` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_table`
--

LOCK TABLES `new_table` WRITE;
/*!40000 ALTER TABLE `new_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `new_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `new_views`
--

DROP TABLE IF EXISTS `new_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `new_views` (
  `id` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `new_views`
--

LOCK TABLES `new_views` WRITE;
/*!40000 ALTER TABLE `new_views` DISABLE KEYS */;
/*!40000 ALTER TABLE `new_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id_new` int(11) NOT NULL AUTO_INCREMENT,
  `master` tinyint(5) NOT NULL DEFAULT '1',
  `lang` enum('ru','en','de','fr','nl') NOT NULL DEFAULT 'ru',
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `url` varchar(104) NOT NULL,
  `data` date NOT NULL,
  `foto` text,
  `to_all` bit(1) NOT NULL DEFAULT b'0',
  `ids_viewed` mediumtext NOT NULL,
  `ids_deleted` mediumtext NOT NULL,
  PRIMARY KEY (`id_new`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_view`
--

DROP TABLE IF EXISTS `news_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_post` (`id_post`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_view`
--

LOCK TABLES `news_view` WRITE;
/*!40000 ALTER TABLE `news_view` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `one_pass`
--

DROP TABLE IF EXISTS `one_pass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `one_pass` (
  `one_pass_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `issue_counter` int(11) NOT NULL DEFAULT '1',
  `pass_data` varchar(65000) NOT NULL,
  `saved` int(11) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`one_pass_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `one_pass`
--

LOCK TABLES `one_pass` WRITE;
/*!40000 ALTER TABLE `one_pass` DISABLE KEYS */;
/*!40000 ALTER TABLE `one_pass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `one_pass_num`
--

DROP TABLE IF EXISTS `one_pass_num`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `one_pass_num` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `one_pass_num` varchar(255) NOT NULL,
  `datetime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `one_pass_num`
--

LOCK TABLES `one_pass_num` WRITE;
/*!40000 ALTER TABLE `one_pass_num` DISABLE KEYS */;
/*!40000 ALTER TABLE `one_pass_num` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id_page` int(11) NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) DEFAULT NULL,
  `shablon` varchar(20) COLLATE utf8_hungarian_ci NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `url` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `lang` enum('ru','en','de','fr','nl') COLLATE utf8_hungarian_ci NOT NULL DEFAULT 'ru',
  `active` int(1) NOT NULL DEFAULT '1',
  `show_menu` int(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `meta_words` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `meta_descript` text CHARACTER SET utf8,
  `master` int(11) DEFAULT '1',
  `banner_menu` varchar(70) COLLATE utf8_hungarian_ci DEFAULT NULL,
  `secondary_menu` varchar(20) COLLATE utf8_hungarian_ci DEFAULT NULL,
  PRIMARY KEY (`id_page`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parents`
--

DROP TABLE IF EXISTS `parents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `estimation_date` date DEFAULT NULL,
  `changing_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parents`
--

LOCK TABLES `parents` WRITE;
/*!40000 ALTER TABLE `parents` DISABLE KEYS */;
/*!40000 ALTER TABLE `parents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_banner`
--

DROP TABLE IF EXISTS `partner_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `foto` varchar(200) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `lang` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_banner`
--

LOCK TABLES `partner_banner` WRITE;
/*!40000 ALTER TABLE `partner_banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `partner_banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_change`
--

DROP TABLE IF EXISTS `partner_change`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `old_partner_id` int(11) DEFAULT NULL,
  `new_partner_id` int(11) DEFAULT NULL,
  `dttm` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_change`
--

LOCK TABLES `partner_change` WRITE;
/*!40000 ALTER TABLE `partner_change` DISABLE KEYS */;
/*!40000 ALTER TABLE `partner_change` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_change_request`
--

DROP TABLE IF EXISTS `partner_change_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_change_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'кто отослал запрос',
  `parent_user_id` int(11) NOT NULL COMMENT 'кто должен стать партнером',
  `create_dttm` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 - новый\r\n1 - обработан\r\n2 - откланен',
  `change_status_dttm` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_change_request`
--

LOCK TABLES `partner_change_request` WRITE;
/*!40000 ALTER TABLE `partner_change_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `partner_change_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_transaction`
--

DROP TABLE IF EXISTS `partner_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_debit` int(11) NOT NULL,
  `id_invited` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('1','2','3') NOT NULL,
  `price` double NOT NULL,
  `progress` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_transaction`
--

LOCK TABLES `partner_transaction` WRITE;
/*!40000 ALTER TABLE `partner_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `partner_transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners_url`
--

DROP TABLE IF EXISTS `partners_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `url` text CHARACTER SET utf8,
  `hits` int(11) NOT NULL DEFAULT '0',
  `registration` int(11) NOT NULL DEFAULT '0',
  `template` int(11) NOT NULL,
  `showSkype` int(11) NOT NULL DEFAULT '0',
  `showPhone` int(11) NOT NULL DEFAULT '0',
  `skype` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `user_text` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners_url`
--

LOCK TABLES `partners_url` WRITE;
/*!40000 ALTER TABLE `partners_url` DISABLE KEYS */;
/*!40000 ALTER TABLE `partners_url` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_data_names`
--

DROP TABLE IF EXISTS `payment_data_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_data_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `machine_name` varchar(255) NOT NULL,
  `human_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_data_names`
--

LOCK TABLES `payment_data_names` WRITE;
/*!40000 ALTER TABLE `payment_data_names` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_data_names` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_data_values`
--

DROP TABLE IF EXISTS `payment_data_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_data_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_data_values`
--

LOCK TABLES `payment_data_values` WRITE;
/*!40000 ALTER TABLE `payment_data_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_data_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phones`
--

DROP TABLE IF EXISTS `phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `short_name` varchar(5) NOT NULL,
  `phone_code_len` int(11) NOT NULL DEFAULT '0',
  `phone_number` varchar(255) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `verifing_date` datetime NOT NULL,
  `sms_code` varchar(10) NOT NULL,
  `sms_count` int(11) NOT NULL,
  `last_date_sms` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `phone_number` (`phone_number`),
  KEY `sms_code` (`sms_code`),
  KEY `short_name` (`short_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phones`
--

LOCK TABLES `phones` WRITE;
/*!40000 ALTER TABLE `phones` DISABLE KEYS */;
/*!40000 ALTER TABLE `phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phones_codes`
--

DROP TABLE IF EXISTS `phones_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phones_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `purpose` varchar(255) CHARACTER SET utf8 NOT NULL,
  `code` varchar(255) CHARACTER SET utf8 NOT NULL,
  `sms_attempts` int(11) NOT NULL,
  `input_attempts` int(11) NOT NULL,
  `last_sms_date` datetime NOT NULL,
  `expiration_datetime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phones_codes`
--

LOCK TABLES `phones_codes` WRITE;
/*!40000 ALTER TABLE `phones_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `phones_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rebonus_temp`
--

DROP TABLE IF EXISTS `rebonus_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rebonus_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `bonus_2_before` int(11) DEFAULT NULL,
  `bonus_5_before` int(11) DEFAULT NULL,
  `bonus_2_after` int(11) DEFAULT NULL,
  `bonus_5_after` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rebonus_temp`
--

LOCK TABLES `rebonus_temp` WRITE;
/*!40000 ALTER TABLE `rebonus_temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `rebonus_temp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recovery_pass`
--

DROP TABLE IF EXISTS `recovery_pass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recovery_pass` (
  `id_user` int(11) NOT NULL,
  `access_hash` text NOT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recovery_pass`
--

LOCK TABLES `recovery_pass` WRITE;
/*!40000 ALTER TABLE `recovery_pass` DISABLE KEYS */;
/*!40000 ALTER TABLE `recovery_pass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `security_attributes_names`
--

DROP TABLE IF EXISTS `security_attributes_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `security_attributes_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `human_name` mediumtext NOT NULL,
  `machine_name` varchar(255) NOT NULL,
  `is_active` enum('0','1') NOT NULL,
  `hidden` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `security_attributes_names`
--

LOCK TABLES `security_attributes_names` WRITE;
/*!40000 ALTER TABLE `security_attributes_names` DISABLE KEYS */;
INSERT INTO `security_attributes_names` VALUES (1,0,'Подтверждение операций СМС-паролем','withdrawal_standart_credit','1','0'),(2,0,'Save security settings by SMS confirm','save_security_settings','1','1'),(3,0,'секрет','code_secret','1','1');
/*!40000 ALTER TABLE `security_attributes_names` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `security_attributes_values`
--

DROP TABLE IF EXISTS `security_attributes_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `security_attributes_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `value` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `security_attributes_values`
--

LOCK TABLES `security_attributes_values` WRITE;
/*!40000 ALTER TABLE `security_attributes_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `security_attributes_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `security_changes`
--

DROP TABLE IF EXISTS `security_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `security_changes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `old_value` mediumtext COLLATE utf8_bin,
  `new_value` mediumtext COLLATE utf8_bin,
  `attribute_id` int(11) DEFAULT NULL,
  `dttm` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `security_changes`
--

LOCK TABLES `security_changes` WRITE;
/*!40000 ALTER TABLE `security_changes` DISABLE KEYS */;
/*!40000 ALTER TABLE `security_changes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `send_messages_history`
--

DROP TABLE IF EXISTS `send_messages_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `send_messages_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(25) CHARACTER SET utf8 NOT NULL,
  `added_datetime` datetime NOT NULL,
  `confirm_datetime` datetime NOT NULL,
  `type_id` int(11) NOT NULL,
  `service_name` varchar(40) NOT NULL,
  `recipient` varchar(100) CHARACTER SET utf8 NOT NULL,
  `message` text NOT NULL,
  `page_link_hash` varchar(200) CHARACTER SET utf8 NOT NULL,
  `page_hash` varchar(7) CHARACTER SET utf8 NOT NULL,
  `message_id_in_service` varchar(40) CHARACTER SET utf8 NOT NULL,
  `service_balance` double NOT NULL,
  `cost` double NOT NULL,
  `delivery_status` int(11) NOT NULL,
  `short_country_name` varchar(25) NOT NULL,
  `target_operator` varchar(60) DEFAULT NULL,
  `status_error_service_result` int(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `send_messages_history`
--

LOCK TABLES `send_messages_history` WRITE;
/*!40000 ALTER TABLE `send_messages_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `send_messages_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `send_money_protection`
--

DROP TABLE IF EXISTS `send_money_protection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `send_money_protection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) unsigned NOT NULL DEFAULT '0',
  `code` decimal(5,0) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `send_money_protection`
--

LOCK TABLES `send_money_protection` WRITE;
/*!40000 ALTER TABLE `send_money_protection` DISABLE KEYS */;
/*!40000 ALTER TABLE `send_money_protection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sender`
--

DROP TABLE IF EXISTS `sender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) CHARACTER SET utf8 NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL,
  `mail` varchar(300) CHARACTER SET utf8 NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `state` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `master` int(11) NOT NULL DEFAULT '1',
  `lang` varchar(11) NOT NULL DEFAULT 'ru',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sender`
--

LOCK TABLES `sender` WRITE;
/*!40000 ALTER TABLE `sender` DISABLE KEYS */;
/*!40000 ALTER TABLE `sender` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `email` varchar(60) CHARACTER SET utf8 NOT NULL,
  `telephon` varchar(15) CHARACTER SET utf8 NOT NULL,
  `sms` text CHARACTER SET utf8 NOT NULL,
  `p_new_credior` double NOT NULL,
  `p_old_credior` double NOT NULL,
  `vip_credior` double NOT NULL,
  `shtraf` double NOT NULL,
  `foto` varchar(100) CHARACTER SET utf8 NOT NULL,
  `flc` int(11) NOT NULL,
  `flp` int(11) NOT NULL,
  `slp` int(11) NOT NULL,
  `regpartner` double NOT NULL,
  `bonus_off` int(11) DEFAULT NULL,
  `bonus_rate` float NOT NULL DEFAULT '2',
  `real_rate` float NOT NULL DEFAULT '2',
  `kontract_count` int(20) NOT NULL,
  `ip_firewall` text NOT NULL,
  `ip_white` text NOT NULL,
  `vdna_emails` varchar(120) DEFAULT NULL,
  `wirebank_emails` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shablon`
--

DROP TABLE IF EXISTS `shablon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shablon` (
  `id_shablon` int(11) NOT NULL AUTO_INCREMENT,
  `sh_title` varchar(20) CHARACTER SET utf8 NOT NULL,
  `sh_content` text CHARACTER SET utf8 NOT NULL,
  `master` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_shablon`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shablon`
--

LOCK TABLES `shablon` WRITE;
/*!40000 ALTER TABLE `shablon` DISABLE KEYS */;
/*!40000 ALTER TABLE `shablon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_transactions`
--

DROP TABLE IF EXISTS `shop_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `shop_id` varchar(24) COLLATE utf8_bin DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `description` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `other` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `user_id` (`user_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_transactions`
--

LOCK TABLES `shop_transactions` WRITE;
/*!40000 ALTER TABLE `shop_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `shop_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shops` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Цифирный идентификатор магазина',
  `shop_id` varchar(24) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Буквенно-циферный идентификатор магазина',
  `user_id` int(11) DEFAULT NULL,
  `section` int(11) DEFAULT '0' COMMENT 'Циферный раздел магазина',
  `status` enum('create','enable','ban','del') CHARACTER SET latin1 NOT NULL DEFAULT 'create' COMMENT 'Статус активации (''0'' => "Не активирован", ''1'' => "Активирован", ''2'' => "Заблокирован", ''3'' => "Удален")',
  `title` varchar(1024) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Название магазина',
  `url` varchar(1024) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Адрес на магазин',
  `url_result` varchar(1024) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Адрес страницы отправки информации об оплаченом платеже',
  `url_success` varchar(1024) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Адрес страницы успешного платежа',
  `url_fail` varchar(1024) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Адрес страницы ошибки платежа',
  `string` varchar(24) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Ключ активации сайта и по совместности высылаемый ключ при успешной оплате ',
  `image` varchar(1024) DEFAULT NULL,
  `email` varchar(1024) DEFAULT NULL,
  `tel` varchar(1024) DEFAULT NULL,
  `commission` tinyint(4) DEFAULT '0',
  `commission_psnt` float DEFAULT '0.5',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier_unic` (`shop_id`),
  KEY `user_id` (`user_id`),
  KEY `identifier` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_network`
--

DROP TABLE IF EXISTS `social_network`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_network` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `url` text NOT NULL,
  `foto` text NOT NULL,
  `photo_100` mediumtext,
  `name` text NOT NULL,
  `id_social` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_network`
--

LOCK TABLES `social_network` WRITE;
/*!40000 ALTER TABLE `social_network` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_network` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statistics`
--

DROP TABLE IF EXISTS `statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `avg_rate` decimal(15,2) NOT NULL,
  `avg_summa` decimal(15,2) NOT NULL,
  `avg_period` decimal(15,2) NOT NULL,
  `total_deals` decimal(15,2) NOT NULL,
  `total_volume` decimal(15,2) NOT NULL,
  `public_deals` decimal(15,2) NOT NULL,
  `public_volume` decimal(15,2) NOT NULL,
  `certificats` decimal(15,2) NOT NULL,
  `certificats_summ` decimal(15,2) NOT NULL,
  `new_users` int(11) unsigned NOT NULL,
  `users` int(11) unsigned NOT NULL,
  `birja_deals` int(11) unsigned NOT NULL,
  `birja_value` decimal(15,2) NOT NULL,
  `creds_balance` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics`
--

LOCK TABLES `statistics` WRITE;
/*!40000 ALTER TABLE `statistics` DISABLE KEYS */;
/*!40000 ALTER TABLE `statistics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statistics_percent`
--

DROP TABLE IF EXISTS `statistics_percent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statistics_percent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `percent` double NOT NULL COMMENT 'Процент :)',
  `total_deals` int(11) NOT NULL COMMENT 'Количество',
  `avg_sum` decimal(15,2) NOT NULL COMMENT 'Средняя сумма',
  `avg_time` int(11) NOT NULL COMMENT 'Средний период',
  `total_sum` decimal(15,2) NOT NULL COMMENT 'Общая сумма',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statistics_percent`
--

LOCK TABLES `statistics_percent` WRITE;
/*!40000 ALTER TABLE `statistics_percent` DISABLE KEYS */;
/*!40000 ALTER TABLE `statistics_percent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_events`
--

DROP TABLE IF EXISTS `system_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `machine_name` varchar(40) COLLATE utf8_bin NOT NULL,
  `human_name` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `params` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  UNIQUE KEY `machine_name` (`machine_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_events`
--

LOCK TABLES `system_events` WRITE;
/*!40000 ALTER TABLE `system_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_events_messages`
--

DROP TABLE IF EXISTS `system_events_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_events_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_datetime` datetime DEFAULT NULL,
  `event_subscribe_id` int(11) DEFAULT NULL,
  `event_type` varchar(20) COLLATE utf8_bin NOT NULL,
  `status` int(11) DEFAULT NULL,
  `text` varchar(500) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_events_messages`
--

LOCK TABLES `system_events_messages` WRITE;
/*!40000 ALTER TABLE `system_events_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_events_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_events_subscribe`
--

DROP TABLE IF EXISTS `system_events_subscribe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_events_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `params` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_events_subscribe`
--

LOCK TABLES `system_events_subscribe` WRITE;
/*!40000 ALTER TABLE `system_events_subscribe` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_events_subscribe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_messages_list`
--

DROP TABLE IF EXISTS `system_messages_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_messages_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `text` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lang` varchar(10) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `exp_datetime` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_messages_list`
--

LOCK TABLES `system_messages_list` WRITE;
/*!40000 ALTER TABLE `system_messages_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_messages_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_messages_read`
--

DROP TABLE IF EXISTS `system_messages_read`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_messages_read` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_messages_read`
--

LOCK TABLES `system_messages_read` WRITE;
/*!40000 ALTER TABLE `system_messages_read` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_messages_read` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_messages_type`
--

DROP TABLE IF EXISTS `system_messages_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_messages_type` (
  `id` int(11) NOT NULL,
  `human_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_messages_type`
--

LOCK TABLES `system_messages_type` WRITE;
/*!40000 ALTER TABLE `system_messages_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_messages_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_news`
--

DROP TABLE IF EXISTS `system_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_news` (
  `id_new` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `url` varchar(104) NOT NULL,
  `data` date NOT NULL,
  `foto` text,
  PRIMARY KEY (`id_new`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_news`
--

LOCK TABLES `system_news` WRITE;
/*!40000 ALTER TABLE `system_news` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_vars`
--

DROP TABLE IF EXISTS `system_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `machine_name` varchar(55) NOT NULL,
  `default_value` varchar(55) NOT NULL,
  `value` varchar(55) NOT NULL,
  `description` text,
  `human_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `system_vars_idx1` (`machine_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_vars`
--

LOCK TABLES `system_vars` WRITE;
/*!40000 ALTER TABLE `system_vars` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_vars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `val` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testuser_groups`
--

DROP TABLE IF EXISTS `testuser_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testuser_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `group_description` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testuser_groups`
--

LOCK TABLES `testuser_groups` WRITE;
/*!40000 ALTER TABLE `testuser_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `testuser_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testuser_user_list`
--

DROP TABLE IF EXISTS `testuser_user_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testuser_user_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testuser_user_list`
--

LOCK TABLES `testuser_user_list` WRITE;
/*!40000 ALTER TABLE `testuser_user_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `testuser_user_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmp_own_funds_to_bonus_5`
--

DROP TABLE IF EXISTS `tmp_own_funds_to_bonus_5`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_own_funds_to_bonus_5` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `dttm` datetime DEFAULT NULL,
  `wallet` float(11,0) DEFAULT NULL,
  `balans` float(11,0) DEFAULT NULL,
  `money_sum_add_funds_by_bonus` float(11,0) DEFAULT NULL,
  `money_sum_transfer_from_users_by_bonus` float(11,0) DEFAULT NULL,
  `money_sum_withdrawal_by_bonus` float(11,0) DEFAULT NULL,
  `money_sum_transfer_to_users_by_bonus` float(11,0) DEFAULT NULL,
  `p2p_money_sum_transfer_to_users_by_bonus` float(11,0) DEFAULT NULL,
  `money_sum_transfer_to_merchant_by_bonus` float(11,0) DEFAULT NULL,
  `transfered_amount` float(9,3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmp_own_funds_to_bonus_5`
--

LOCK TABLES `tmp_own_funds_to_bonus_5` WRITE;
/*!40000 ALTER TABLE `tmp_own_funds_to_bonus_5` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp_own_funds_to_bonus_5` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmp_own_funds_to_bonus_6`
--

DROP TABLE IF EXISTS `tmp_own_funds_to_bonus_6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp_own_funds_to_bonus_6` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `ins_dttm` datetime DEFAULT NULL,
  `net_liq` float(9,3) DEFAULT NULL,
  `net_inn` float(9,3) DEFAULT NULL,
  `balance_inn` float(9,3) DEFAULT NULL,
  `liq_funds` float(9,3) DEFAULT NULL,
  `nonliq_funds` float(9,3) DEFAULT NULL,
  `total_profit` float(9,3) DEFAULT NULL,
  `total_inflow` float(9,3) DEFAULT NULL,
  `total_out` float(9,3) DEFAULT NULL,
  `k` float(9,3) DEFAULT NULL,
  `S_P` float(9,3) DEFAULT NULL,
  `total_liq` float(9,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmp_own_funds_to_bonus_6`
--

LOCK TABLES `tmp_own_funds_to_bonus_6` WRITE;
/*!40000 ALTER TABLE `tmp_own_funds_to_bonus_6` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp_own_funds_to_bonus_6` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `performer` varchar(100) CHARACTER SET utf8 NOT NULL,
  `date` date NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_debit` int(11) NOT NULL,
  `summa` double NOT NULL,
  `payment` varchar(100) CHARACTER SET utf8 NOT NULL,
  `note` text CHARACTER SET utf8 NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction`
--

LOCK TABLES `transaction` WRITE;
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '2',
  `summa` double NOT NULL,
  `metod` varchar(50) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `value` int(11) NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  `note_admin` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bonus` int(1) NOT NULL DEFAULT '2',
  `master` int(11) DEFAULT NULL,
  `transactionscol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `date` (`date`),
  KEY `bonus` (`bonus`),
  KEY `user_ip` (`user_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trig_ins_transactions_add_need_recalculate_scors` AFTER INSERT ON `transactions`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(NEW.`id_user`,'transactions') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trig_transactions_upd_add_need_recalculate_scors` AFTER UPDATE ON `transactions`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(OLD.`id_user`,'transactions') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trig_del_transactions_add_need_recalculate_scors` AFTER DELETE ON `transactions`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(OLD.`id_user`,'transactions') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `trasactions_errors`
--

DROP TABLE IF EXISTS `trasactions_errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trasactions_errors` (
  `id` int(11) NOT NULL,
  `error_name` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trasactions_errors`
--

LOCK TABLES `trasactions_errors` WRITE;
/*!40000 ALTER TABLE `trasactions_errors` DISABLE KEYS */;
/*!40000 ALTER TABLE `trasactions_errors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_arbitration_scores`
--

DROP TABLE IF EXISTS `user_arbitration_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_arbitration_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `scores` float(9,3) NOT NULL DEFAULT '0.000',
  `status` int(11) NOT NULL COMMENT '1 - add\r\n3 - remove',
  `invest_id` int(11) NOT NULL DEFAULT '0',
  `dttm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_arbitration_scores`
--

LOCK TABLES `user_arbitration_scores` WRITE;
/*!40000 ALTER TABLE `user_arbitration_scores` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_arbitration_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_balances`
--

DROP TABLE IF EXISTS `user_balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_balances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  `balance` double NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user` (`id_user`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_balances`
--

LOCK TABLES `user_balances` WRITE;
/*!40000 ALTER TABLE `user_balances` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_balances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_balans`
--

DROP TABLE IF EXISTS `user_balans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_balans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `FSR` int(11) NOT NULL,
  `last_update` datetime NOT NULL,
  `expiration_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_balans`
--

LOCK TABLES `user_balans` WRITE;
/*!40000 ALTER TABLE `user_balans` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_balans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_current_score_data`
--

DROP TABLE IF EXISTS `user_current_score_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_current_score_data` (
  `user_id` int(11) NOT NULL,
  `fsr` double NOT NULL,
  `fsrc` double NOT NULL,
  `max_loan_available` double NOT NULL,
  `balance` double NOT NULL,
  `payment_account` double NOT NULL,
  `bonuses` double NOT NULL,
  `all_advanced_invests_bonuses_summ` double NOT NULL,
  `investments_garant` double NOT NULL,
  `my_investments_garant_percent` double NOT NULL,
  `investments_garant_bonuses` double NOT NULL,
  `investments_standart` double NOT NULL,
  `all_liabilities` double NOT NULL,
  `loans` double NOT NULL,
  `loans_percentage` double NOT NULL,
  `loans_percentage_garant` double NOT NULL,
  `future_income` double NOT NULL,
  `total_future_income` double NOT NULL,
  `future_interest_payout` double NOT NULL,
  `all_advanced_loans_out_summ` double NOT NULL,
  `all_advanced_invests_summ` double NOT NULL,
  `all_advanced_standart_invests_summ` double NOT NULL,
  `total_processing_payout` double NOT NULL,
  `total_garant_loans` double NOT NULL,
  `payout_limit` double NOT NULL,
  `total_assets` double NOT NULL,
  `max_garant_loan_available` double NOT NULL,
  `total_arbitrage` double NOT NULL,
  `arbitrage_collateral` double NOT NULL,
  `overdraft` double NOT NULL,
  `loans_standart_out_summ` double NOT NULL,
  `all_active_garant_loans_out_summ` double NOT NULL,
  `soon` double NOT NULL,
  `direct_collateral` double NOT NULL,
  `overdue_standart_count` double NOT NULL,
  `overdue_standart` text NOT NULL,
  `overdue_garant_count` double NOT NULL,
  `overdue_garant` text NOT NULL,
  `money_sum_add_funds` double NOT NULL,
  `money_sum_withdrawal` double NOT NULL,
  `money_sum_process_withdrawal` double NOT NULL,
  `money_sum_transfer_to_users` double NOT NULL,
  `money_sum_transfer_from_users` double NOT NULL,
  `sum_partner_reword` double NOT NULL,
  `partner_unic_id_count` double NOT NULL,
  `diversification_coeff` double NOT NULL,
  `partner_week_contribution` double NOT NULL,
  `partner_contribution_count` double NOT NULL,
  `average_partner_contribution` double NOT NULL,
  `diversification_degree` double NOT NULL,
  `partner_network_valuation_coeff` double NOT NULL,
  `partner_network_value` double NOT NULL,
  `max_arbitrage_amount` double NOT NULL,
  `overdue_garant_interest` double NOT NULL,
  `net_funds` double NOT NULL,
  `fee_or_fine` double NOT NULL,
  `pay_off_arbitration` double NOT NULL,
  `max_arbitrage_calc` double NOT NULL,
  `date_modified` datetime NOT NULL,
  `social_integration_value` double NOT NULL,
  `money_sum_process_withdrawal_bank` double NOT NULL,
  `month_partner_unic_id_count` double NOT NULL,
  `top_up_sum` double NOT NULL,
  `temp_15` double NOT NULL,
  `temp_16` double NOT NULL,
  `temp_17` double NOT NULL,
  `temp_18` double NOT NULL,
  `temp_19` double NOT NULL,
  `temp_110` double NOT NULL,
  `temp_111` double NOT NULL,
  `temp_112` double NOT NULL,
  `temp_113` double NOT NULL,
  `temp_114` double NOT NULL,
  `temp_115` double NOT NULL,
  `temp_116` double NOT NULL,
  `temp_117` double NOT NULL,
  `temp_118` double NOT NULL,
  `temp_119` double NOT NULL,
  `temp_120` double NOT NULL,
  `temp_121` double NOT NULL,
  `temp_122` double NOT NULL,
  `temp_123` double NOT NULL,
  `temp_124` double NOT NULL,
  `temp_125` double NOT NULL,
  `temp_126` double NOT NULL,
  `temp_127` double NOT NULL,
  `temp_128` double NOT NULL,
  `temp_129` double NOT NULL,
  `temp_130` double NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_current_score_data`
--

LOCK TABLES `user_current_score_data` WRITE;
/*!40000 ALTER TABLE `user_current_score_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_current_score_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_del_bonuses`
--

DROP TABLE IF EXISTS `user_del_bonuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_del_bonuses` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL,
  `id` int(11) NOT NULL DEFAULT '0',
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_del_bonuses`
--

LOCK TABLES `user_del_bonuses` WRITE;
/*!40000 ALTER TABLE `user_del_bonuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_del_bonuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_need_recalculate_scors`
--

DROP TABLE IF EXISTS `user_need_recalculate_scors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_need_recalculate_scors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `table_name` varchar(30) NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `checked` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_need_recalculate_scors`
--

LOCK TABLES `user_need_recalculate_scors` WRITE;
/*!40000 ALTER TABLE `user_need_recalculate_scors` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_need_recalculate_scors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_page_requests`
--

DROP TABLE IF EXISTS `user_page_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_page_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `data` varchar(5000) CHARACTER SET utf8 NOT NULL,
  `avrg_time` float NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_page_requests`
--

LOCK TABLES `user_page_requests` WRITE;
/*!40000 ALTER TABLE `user_page_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_page_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_preferable_sms_service`
--

DROP TABLE IF EXISTS `user_preferable_sms_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_preferable_sms_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `success_counter` int(11) NOT NULL DEFAULT '0',
  `pending_counter` int(11) NOT NULL DEFAULT '0',
  `fail_counter` int(11) NOT NULL DEFAULT '0',
  `another_status_counter` int(11) NOT NULL DEFAULT '0',
  `another_status_details` varchar(20000) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_preferable_sms_service`
--

LOCK TABLES `user_preferable_sms_service` WRITE;
/*!40000 ALTER TABLE `user_preferable_sms_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_preferable_sms_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_soc_answer_log`
--

DROP TABLE IF EXISTS `user_soc_answer_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_soc_answer_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dttm` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `answer` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_soc_answer_log`
--

LOCK TABLES `user_soc_answer_log` WRITE;
/*!40000 ALTER TABLE `user_soc_answer_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_soc_answer_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_test_table`
--

DROP TABLE IF EXISTS `user_test_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_test_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_test_table`
--

LOCK TABLES `user_test_table` WRITE;
/*!40000 ALTER TABLE `user_test_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_test_table` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_insert_add_flag_to_user_need_recalculate_scors` AFTER INSERT ON `user_test_table`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(NEW.`user_id`,'user_test_table') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_update_add_flag_to_user_need_recalculate_scors` AFTER UPDATE ON `user_test_table`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(NEW.`user_id`,'user_test_table') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_delete__add_flag_to_user_need_recalculate_scors` AFTER DELETE ON `user_test_table`
 FOR EACH ROW CALL add_flag_to_user_need_recalculate_scors(OLD.`user_id`,'user_test_table') */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL,
  `id_volunteer` int(11) NOT NULL,
  `master` tinyint(5) unsigned DEFAULT NULL,
  `parent_change_counter` int(11) NOT NULL DEFAULT '0',
  `money` double NOT NULL,
  `user_login` text NOT NULL,
  `user_pass` varchar(128) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `online_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(128) NOT NULL,
  `sername` text NOT NULL,
  `patronymic` text NOT NULL,
  `email` varchar(128) NOT NULL,
  `identity` text NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `ip_reg` varchar(100) NOT NULL,
  `phone` text,
  `phone_new` text,
  `skype` varchar(50) NOT NULL,
  `sex` text NOT NULL,
  `born` text NOT NULL,
  `place` text NOT NULL,
  `family_state` text NOT NULL,
  `inn` text NOT NULL,
  `pasport_seria` text NOT NULL,
  `pasport_number` text NOT NULL,
  `pasport_date` text NOT NULL,
  `pasport_kpd` text NOT NULL,
  `pasport_kvn` text NOT NULL,
  `pasport_born` text NOT NULL,
  `bank_name` text NOT NULL,
  `bank_schet` text NOT NULL,
  `bank_bik` text NOT NULL,
  `bank_kor` text NOT NULL,
  `bank_yandex` text NOT NULL,
  `bank_paypal` text NOT NULL,
  `bank_cc` text NOT NULL,
  `bank_cc_date_off` text NOT NULL,
  `bank_w1` text NOT NULL,
  `bank_w1_rub` text NOT NULL,
  `bank_perfectmoney` text NOT NULL,
  `bank_okpay` text NOT NULL,
  `bank_wpay` text NOT NULL,
  `bank_egopay` text NOT NULL,
  `bank_liqpay` text NOT NULL,
  `bank_qiwi` text NOT NULL,
  `bank_tinkoff` text NOT NULL,
  `bank_webmoney` text NOT NULL,
  `bank_rbk` text NOT NULL,
  `bank_mail` text NOT NULL,
  `bank_lava` text NOT NULL,
  `webmoney` text NOT NULL,
  `legal_form` text NOT NULL,
  `kpp` text NOT NULL,
  `ogrn` text NOT NULL,
  `payment_system` varchar(100) NOT NULL,
  `face` text NOT NULL,
  `work_name` text,
  `work_phone` text,
  `work_place` text,
  `work_who` text,
  `work_time` text,
  `work_money` text,
  `bot` int(11) NOT NULL DEFAULT '2',
  `account_verification` text,
  `partner` int(1) NOT NULL DEFAULT '2',
  `partner_plan` int(1) NOT NULL DEFAULT '1',
  `partner_plan_update` date NOT NULL,
  `client` int(1) NOT NULL DEFAULT '2',
  `vip` int(1) NOT NULL DEFAULT '1',
  `state` int(11) NOT NULL DEFAULT '2',
  `status_cause` text NOT NULL,
  `payment_default` text NOT NULL,
  `hash_code` text NOT NULL,
  `phone_verification` text NOT NULL,
  `blocked_money` int(11) NOT NULL,
  `doc_request` int(11) NOT NULL,
  `payout_limit` int(11) NOT NULL DEFAULT '0',
  `rating` float NOT NULL,
  `bonuses` float DEFAULT NULL,
  `payment_account` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `fsrc` float NOT NULL,
  `max_loan_available` float NOT NULL,
  `volunteer` int(1) NOT NULL DEFAULT '2',
  `invest_arbitrage` tinyint(4) NOT NULL DEFAULT '2',
  `auth_token` text NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `parent` (`parent`),
  KEY `reg_date` (`reg_date`),
  KEY `phone` (`phone`(30)),
  KEY `state` (`state`),
  KEY `client` (`client`),
  KEY `partner` (`partner`),
  KEY `place` (`place`(100)),
  KEY `email` (`email`),
  KEY `user_pass` (`user_pass`),
  KEY `bot` (`bot`),
  KEY `ip_reg` (`ip_reg`),
  KEY `id_volunteer` (`id_volunteer`),
  KEY `master` (`master`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users2`
--

DROP TABLE IF EXISTS `users2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users2` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `phone` text,
  `hash_code` text NOT NULL,
  `phone_verification` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users2`
--

LOCK TABLES `users2` WRITE;
/*!40000 ALTER TABLE `users2` DISABLE KEYS */;
/*!40000 ALTER TABLE `users2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_filds`
--

DROP TABLE IF EXISTS `users_filds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_filds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nickname` varchar(256) NOT NULL,
  `is_show` int(11) NOT NULL DEFAULT '0',
  `partner_level` tinyint(4) NOT NULL DEFAULT '1',
  `id_network` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_user` (`id_user`),
  KEY `nickname` (`nickname`(255)),
  KEY `id_user_2` (`id_user`),
  KEY `id_user_3` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_filds`
--

LOCK TABLES `users_filds` WRITE;
/*!40000 ALTER TABLE `users_filds` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_filds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_for_pay`
--

DROP TABLE IF EXISTS `users_for_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_for_pay` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL,
  `money` double NOT NULL,
  `user_login` text NOT NULL,
  `user_pass` text NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `online_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` text NOT NULL,
  `sername` text NOT NULL,
  `patronymic` text NOT NULL,
  `email` text NOT NULL,
  `identity` text NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `phone` text,
  `phone_new` text,
  `skype` varchar(50) NOT NULL,
  `sex` text NOT NULL,
  `born` text NOT NULL,
  `place` text NOT NULL,
  `family_state` text NOT NULL,
  `inn` text NOT NULL,
  `pasport_seria` text NOT NULL,
  `pasport_number` text NOT NULL,
  `pasport_date` text NOT NULL,
  `pasport_kpd` text NOT NULL,
  `pasport_kvn` text NOT NULL,
  `pasport_born` text NOT NULL,
  `bank_name` text NOT NULL,
  `bank_schet` text NOT NULL,
  `bank_bik` text NOT NULL,
  `bank_kor` text NOT NULL,
  `bank_yandex` text NOT NULL,
  `bank_paypal` text NOT NULL,
  `bank_cc` text NOT NULL,
  `bank_liqpay` text NOT NULL,
  `bank_qiwi` text NOT NULL,
  `bank_tinkoff` text NOT NULL,
  `bank_webmoney` text NOT NULL,
  `webmoney` text NOT NULL,
  `legal_form` text NOT NULL,
  `kpp` text NOT NULL,
  `ogrn` text NOT NULL,
  `payment_system` varchar(100) NOT NULL,
  `face` text NOT NULL,
  `work_name` text,
  `work_phone` text,
  `work_place` text,
  `work_who` text,
  `work_time` text,
  `work_money` text,
  `bot` int(11) NOT NULL DEFAULT '2',
  `account_verification` text,
  `partner` int(1) NOT NULL DEFAULT '2',
  `partner_plan` int(1) NOT NULL DEFAULT '1',
  `client` int(1) NOT NULL DEFAULT '2',
  `vip` int(1) NOT NULL DEFAULT '1',
  `state` int(11) NOT NULL DEFAULT '2',
  `payment_default` text NOT NULL,
  `hash_code` text NOT NULL,
  `phone_verification` text NOT NULL,
  `blocked_money` int(11) NOT NULL,
  `rating` float NOT NULL,
  `bonuses` float DEFAULT NULL,
  `payment_account` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `fsrc` float NOT NULL,
  `max_loan_available` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_for_pay`
--

LOCK TABLES `users_for_pay` WRITE;
/*!40000 ALTER TABLE `users_for_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_for_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_limited`
--

DROP TABLE IF EXISTS `users_limited`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_limited` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_limited`
--

LOCK TABLES `users_limited` WRITE;
/*!40000 ALTER TABLE `users_limited` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_limited` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_other_accounts`
--

DROP TABLE IF EXISTS `users_other_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_other_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `summa` float(9,2) NOT NULL DEFAULT '0.00',
  `add_dttm` datetime DEFAULT NULL,
  `account_type` char(20) COLLATE utf8_bin NOT NULL,
  `account_extra_data` text COLLATE utf8_bin,
  `account_personal_num` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `bank_card_num` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `own_wallet` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `bank_card_expire_date` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_other_accounts`
--

LOCK TABLES `users_other_accounts` WRITE;
/*!40000 ALTER TABLE `users_other_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_other_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_other_accounts_transactions`
--

DROP TABLE IF EXISTS `users_other_accounts_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_other_accounts_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_other_account_id` int(11) DEFAULT NULL,
  `old_summa` float(9,2) DEFAULT NULL,
  `new_summa` float(9,3) DEFAULT NULL,
  `debit` int(11) DEFAULT NULL,
  `dttm` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_other_accounts_transactions`
--

LOCK TABLES `users_other_accounts_transactions` WRITE;
/*!40000 ALTER TABLE `users_other_accounts_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_other_accounts_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_parents_change`
--

DROP TABLE IF EXISTS `users_parents_change`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_parents_change` (
  `id_user` int(11) NOT NULL,
  `parent_before` int(11) NOT NULL,
  `parent_after` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_parents_change`
--

LOCK TABLES `users_parents_change` WRITE;
/*!40000 ALTER TABLE `users_parents_change` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_parents_change` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_pay`
--

DROP TABLE IF EXISTS `users_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_pay` (
  `parent` int(11) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_pay`
--

LOCK TABLES `users_pay` WRITE;
/*!40000 ALTER TABLE `users_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_pay2`
--

DROP TABLE IF EXISTS `users_pay2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_pay2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_pay2`
--

LOCK TABLES `users_pay2` WRITE;
/*!40000 ALTER TABLE `users_pay2` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_pay2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_pay_off_arbitration`
--

DROP TABLE IF EXISTS `users_pay_off_arbitration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_pay_off_arbitration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pay_off_arbitration` double NOT NULL,
  `payed_off` double NOT NULL,
  `arbitration_id` int(11) NOT NULL,
  `pay_off_date` date NOT NULL,
  `modifing_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `output` text NOT NULL,
  `purpose` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_pay_off_arbitration`
--

LOCK TABLES `users_pay_off_arbitration` WRITE;
/*!40000 ALTER TABLE `users_pay_off_arbitration` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_pay_off_arbitration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_pay_off_arbitration_old`
--

DROP TABLE IF EXISTS `users_pay_off_arbitration_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_pay_off_arbitration_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_data` varchar(6530) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pay_off_arbitration` double NOT NULL,
  `payed_off` double NOT NULL,
  `arbitration_id` int(11) NOT NULL,
  `modifing_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `output` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_pay_off_arbitration_old`
--

LOCK TABLES `users_pay_off_arbitration_old` WRITE;
/*!40000 ALTER TABLE `users_pay_off_arbitration_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_pay_off_arbitration_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_photo`
--

DROP TABLE IF EXISTS `users_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(256) NOT NULL,
  `url` varchar(500) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_photo`
--

LOCK TABLES `users_photo` WRITE;
/*!40000 ALTER TABLE `users_photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_social_friends`
--

DROP TABLE IF EXISTS `users_social_friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_social_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `friend_id` varchar(256) CHARACTER SET utf8 NOT NULL,
  `f_name` varchar(256) CHARACTER SET utf8 NOT NULL,
  `l_name` varchar(256) CHARACTER SET utf8 NOT NULL,
  `s_name` varchar(256) CHARACTER SET utf8 NOT NULL,
  `social_uri` varchar(256) CHARACTER SET utf8 NOT NULL,
  `social_name` varchar(256) CHARACTER SET utf8 NOT NULL,
  `foto` varchar(256) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`social_name`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_social_friends`
--

LOCK TABLES `users_social_friends` WRITE;
/*!40000 ALTER TABLE `users_social_friends` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_social_friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_status`
--

DROP TABLE IF EXISTS `users_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_status` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_user` int(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(2) NOT NULL,
  `note` text COLLATE utf8_bin NOT NULL,
  `id_admin` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='сообщения о причинах смены статуса пользователей';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_status`
--

LOCK TABLES `users_status` WRITE;
/*!40000 ALTER TABLE `users_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vdna_reports`
--

DROP TABLE IF EXISTS `vdna_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vdna_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_date` datetime DEFAULT '0000-00-00 00:00:00',
  `records_count` int(11) NOT NULL DEFAULT '0',
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `report_app_path` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `report_bhvr_path` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `stat` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vdna_reports`
--

LOCK TABLES `vdna_reports` WRITE;
/*!40000 ALTER TABLE `vdna_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `vdna_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang` enum('ru','en','de','fr','nl') COLLATE utf8_bin NOT NULL DEFAULT 'ru',
  `title` varchar(150) COLLATE utf8_bin NOT NULL,
  `category` tinyint(1) unsigned NOT NULL,
  `id_video` varchar(50) COLLATE utf8_bin NOT NULL,
  `info` text COLLATE utf8_bin NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 - on 0 - off',
  `featured` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `vote` int(10) unsigned NOT NULL DEFAULT '0',
  `id_user` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_video` (`id_video`),
  KEY `status` (`status`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visualdna`
--

DROP TABLE IF EXISTS `visualdna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visualdna` (
  `puid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `complete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`puid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visualdna`
--

LOCK TABLES `visualdna` WRITE;
/*!40000 ALTER TABLE `visualdna` DISABLE KEYS */;
/*!40000 ALTER TABLE `visualdna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visualdna_extra_data`
--

DROP TABLE IF EXISTS `visualdna_extra_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visualdna_extra_data` (
  `puid` int(11) NOT NULL,
  `birthday` date NOT NULL,
  `sex` int(11) NOT NULL,
  PRIMARY KEY (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visualdna_extra_data`
--

LOCK TABLES `visualdna_extra_data` WRITE;
/*!40000 ALTER TABLE `visualdna_extra_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `visualdna_extra_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer_message`
--

DROP TABLE IF EXISTS `volunteer_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer_message` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) NOT NULL,
  `id_topic` int(10) NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `for_admin` int(1) NOT NULL DEFAULT '2' COMMENT '1 - yes, 2 - no',
  `is_admin` int(1) NOT NULL DEFAULT '2' COMMENT '1 - yes, 2 - no',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `topic` (`id_topic`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer_message`
--

LOCK TABLES `volunteer_message` WRITE;
/*!40000 ALTER TABLE `volunteer_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteer_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer_topic`
--

DROP TABLE IF EXISTS `volunteer_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer_topic` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) NOT NULL,
  `name` tinytext COLLATE utf8_bin NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer_topic`
--

LOCK TABLES `volunteer_topic` WRITE;
/*!40000 ALTER TABLE `volunteer_topic` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteer_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteers`
--

DROP TABLE IF EXISTS `volunteers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteers` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `date` (`date`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteers`
--

LOCK TABLES `volunteers` WRITE;
/*!40000 ALTER TABLE `volunteers` DISABLE KEYS */;
/*!40000 ALTER TABLE `volunteers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_vote` int(10) unsigned NOT NULL,
  `variant` tinyint(3) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_addr` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `vote_name` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'unknown',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user_id_vote` (`id_user`,`id_vote`),
  KEY `variant` (`variant`),
  KEY `id_user&id_vote` (`id_user`,`id_vote`),
  KEY `id_user` (`id_user`),
  KEY `id_vote` (`id_vote`),
  KEY `id_vote_variant` (`id_vote`,`variant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_3`
--

DROP TABLE IF EXISTS `wallet_3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wallet_3` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `added` decimal(15,2) NOT NULL,
  `in_s` decimal(15,2) NOT NULL,
  `out_s` decimal(15,2) NOT NULL,
  `withdrawal` decimal(15,2) NOT NULL,
  `exchange` decimal(15,2) NOT NULL,
  `net_fund` decimal(15,2) NOT NULL,
  `netvalue` decimal(15,2) NOT NULL,
  `withdraw` decimal(15,2) NOT NULL,
  `payment_account` decimal(15,2) NOT NULL,
  `payment_account_25` decimal(15,2) NOT NULL,
  `merchant` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_3`
--

LOCK TABLES `wallet_3` WRITE;
/*!40000 ALTER TABLE `wallet_3` DISABLE KEYS */;
/*!40000 ALTER TABLE `wallet_3` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-14 19:42:53
