-- MySQL dump 10.13  Distrib 5.5.25, for osx10.6 (i386)
--
-- Host: localhost    Database: websitesapp
-- ------------------------------------------------------
-- Server version	5.5.25

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
-- Table structure for table `bannerclicks`
--

DROP TABLE IF EXISTS `bannerclicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bannerclicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) DEFAULT NULL,
  `banners_id` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bannerclicks`
--

LOCK TABLES `bannerclicks` WRITE;
/*!40000 ALTER TABLE `bannerclicks` DISABLE KEYS */;
/*!40000 ALTER TABLE `bannerclicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `file` varchar(256) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `content` text,
  `clicks` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `sbbs` float DEFAULT NULL,
  `filtre` int(11) DEFAULT NULL,
  `imageback` varchar(256) DEFAULT NULL,
  `backgroundcolor` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(256) DEFAULT 'fr',
  `title` varchar(256) DEFAULT NULL,
  `content` text,
  `titleseo` varchar(256) DEFAULT NULL,
  `position` varchar(256) DEFAULT NULL,
  `lck` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocks`
--

LOCK TABLES `blocks` WRITE;
/*!40000 ALTER TABLE `blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contents`
--

DROP TABLE IF EXISTS `contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `access` varchar(512) DEFAULT '-1',
  `cached` int(11) DEFAULT '1',
  `porder` int(11) DEFAULT NULL,
  `contents_id` int(11) DEFAULT NULL,
  `layout` varchar(256) DEFAULT NULL,
  `titleshort` varchar(256) DEFAULT NULL,
  `hidden` int(11) DEFAULT '0',
  `path` varchar(256) DEFAULT NULL,
  `fullpath` varchar(512) DEFAULT NULL,
  `contents_ko_id` int(11) DEFAULT '-1',
  `contents_ja_id` int(11) DEFAULT '-1',
  `contents_zh_id` int(11) DEFAULT '-1',
  `contents_nl_id` int(11) DEFAULT '-1',
  `contents_es_id` int(11) DEFAULT '-1',
  `contents_en_id` int(11) DEFAULT '-1',
  `contents_fr_id` int(11) DEFAULT '-1',
  `seodescription` text,
  `seokeywords` text,
  `params` varchar(256) DEFAULT NULL,
  `placeholder_1` varchar(256) DEFAULT NULL,
  `placeholder_1_value` varchar(256) DEFAULT NULL,
  `placeholder_2` varchar(256) DEFAULT NULL,
  `placeholder_2_value` varchar(256) DEFAULT NULL,
  `placeholder_3` varchar(256) DEFAULT NULL,
  `placeholder_3_value` varchar(256) DEFAULT NULL,
  `placeholder_4` varchar(256) DEFAULT NULL,
  `placeholder_4_value` varchar(256) DEFAULT NULL,
  `placeholder_5` varchar(256) DEFAULT NULL,
  `placeholder_5_value` varchar(256) DEFAULT NULL,
  `placeholder_6` varchar(256) DEFAULT NULL,
  `placeholder_6_value` varchar(256) DEFAULT NULL,
  `placeholder_7` varchar(256) DEFAULT NULL,
  `placeholder_7_value` varchar(256) DEFAULT NULL,
  `placeholder_8` varchar(256) DEFAULT NULL,
  `placeholder_8_value` varchar(256) DEFAULT NULL,
  `placeholder_9` varchar(256) DEFAULT NULL,
  `placeholder_9_value` varchar(256) DEFAULT NULL,
  `comment` text,
  `hits` int(11) DEFAULT '0',
  `creator_id` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `modifier_id` int(11) DEFAULT NULL,
  `placeholder_1_param` varchar(256) DEFAULT NULL,
  `placeholder_2_param` varchar(256) DEFAULT NULL,
  `placeholder_3_param` varchar(256) DEFAULT NULL,
  `placeholder_4_param` varchar(256) DEFAULT NULL,
  `placeholder_5_param` varchar(256) DEFAULT NULL,
  `placeholder_6_param` varchar(256) DEFAULT NULL,
  `placeholder_7_param` varchar(256) DEFAULT NULL,
  `placeholder_8_param` varchar(256) DEFAULT NULL,
  `placeholder_9_param` varchar(256) DEFAULT NULL,
  `menus` varchar(32) DEFAULT '1',
  `sitemap` int(4) DEFAULT '1',
  `content_1` longtext,
  `content_2` longtext,
  `content_3` longtext,
  `content_4` longtext,
  `content_5` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contents`
--

LOCK TABLES `contents` WRITE;
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',
  `description` text,
  `file` varchar(256) DEFAULT NULL,
  `groups_id` int(11) DEFAULT NULL,
  `ddate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',
  `contents` longtext,
  `blurb` longtext,
  `porder` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,'fr','Bac roulant','bac-roulant','<p>\n	Que vous pouvez mettre dans votre bac roulant destin&eacute; &agrave; la collecte des mati&egrave;res recyclables les contenants et bouteilles de plastique num&eacute;rot&eacute;s de 1 &agrave; 7.</p>\n<p>\n	Que vous pouvez mettre dans votre bac roulant destin&eacute; &agrave; la collecte des mati&egrave;res recyclables les contenants et bouteilles de plastique num&eacute;rot&eacute;s de 1 &agrave; 7.</p>\n<p>\n	Que vous pouvez mettre dans votre bac roulant destin&eacute; &agrave; la collecte des mati&egrave;res recyclables les contenants et bouteilles de plastique num&eacute;rot&eacute;s de 1 &agrave; 7.</p>\n<p>\n	Que vous pouvez mettre dans votre bac roulant destin&eacute; &agrave; la collecte des mati&egrave;res recyclables les contenants et bouteilles de plastique num&eacute;rot&eacute;s de 1 &agrave; 7.</p>\n<p>\n	Que vous pouvez mettre dans votre bac roulant destin&eacute; &agrave; la collecte des mati&egrave;res recyclables les contenants et bouteilles de plastique num&eacute;rot&eacute;s de 1 &agrave; 7.</p>','<p>\n	Que vous pouvez mettre dans votre bac roulant destin&eacute; &agrave; la collecte des mati&egrave;res recyclables les contenants et bouteilles de plastique num&eacute;rot&eacute;s de 1 &agrave; 7.</p>','01'),(2,'fr','Autre FAQ','autre-faq','<p>\n	Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.Autre FAQ qui devrait appara&icirc;tre de temps en temps.</p>','<p>\n	Autre FAQ qui devrait appara&icirc;tre de temps en temps.</p>','02');
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filters`
--

DROP TABLE IF EXISTS `filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT NULL,
  `tabledefinitions_id` int(11) DEFAULT NULL,
  `condition` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filters`
--

LOCK TABLES `filters` WRITE;
/*!40000 ALTER TABLE `filters` DISABLE KEYS */;
INSERT INTO `filters` VALUES (1,'fr','IDEVA',49,'active=1 and email like &quot;%@ideva.ca&quot;');
/*!40000 ALTER TABLE `filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formsdefinitions`
--

DROP TABLE IF EXISTS `formsdefinitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formsdefinitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `max_questions` int(11) DEFAULT '100',
  `title` varchar(256) DEFAULT NULL,
  `introduction` text,
  `thanks` text,
  `errors` text,
  `mailadmin` text,
  `mailuser` text,
  `questions` text,
  `emailsender` int(11) DEFAULT NULL,
  `emailadmin` int(11) DEFAULT NULL,
  `emailextra` text,
  `instructions` text,
  `locked` int(11) DEFAULT NULL,
  `tabledefinitions_id` int(11) DEFAULT NULL,
  `userquestions` int(11) DEFAULT '0',
  `contesterror` text,
  `usecaptcha` int(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formsdefinitions`
--

LOCK TABLES `formsdefinitions` WRITE;
/*!40000 ALTER TABLE `formsdefinitions` DISABLE KEYS */;
INSERT INTO `formsdefinitions` VALUES (3,'en','normal',100,'Contactez-nous...','<p>\n	bfbb</p>','<p>\n	bdfbdfb</p>','<p>\n	bdfbdfbdf</p>','<p>\n	bbrb cv &nbsp;brbreb</p>\n<p>\n	e</p>\n<p>\n	berberberb<img alt=\"\" src=\"/public/images/841.gif\" style=\"width: 279px; height: 200px;\" /></p>','','a:2:{s:5:\"svsvs\";O:8:\"stdClass\":8:{s:5:\"order\";s:1:\"1\";s:4:\"name\";s:5:\"svsvs\";s:4:\"type\";s:4:\"text\";s:5:\"label\";s:5:\"svsvs\";s:7:\"comment\";s:0:\"\";s:5:\"error\";s:0:\"\";s:9:\"mandatory\";b:0;s:6:\"values\";s:0:\"\";}s:13:\"bfdbffdbdfbdf\";O:8:\"stdClass\":8:{s:5:\"order\";s:1:\"2\";s:4:\"name\";s:13:\"bfdbffdbdfbdf\";s:4:\"type\";s:4:\"text\";s:5:\"label\";s:13:\"bfdbffdbdfbdf\";s:7:\"comment\";s:0:\"\";s:5:\"error\";s:0:\"\";s:9:\"mandatory\";b:0;s:6:\"values\";s:0:\"\";}}',0,0,'','',0,0,0,'',0),(4,'en','normal',100,'Sondages...','','','','','','a:0:{}',0,0,'','',0,0,0,'',0);
/*!40000 ALTER TABLE `formsdefinitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formsresponses`
--

DROP TABLE IF EXISTS `formsresponses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formsresponses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `formsdefinitions_id` int(11) DEFAULT NULL,
  `responsedate` datetime DEFAULT NULL,
  `values` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formsresponses`
--

LOCK TABLES `formsresponses` WRITE;
/*!40000 ALTER TABLE `formsresponses` DISABLE KEYS */;
/*!40000 ALTER TABLE `formsresponses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forumcomments`
--

DROP TABLE IF EXISTS `forumcomments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forumcomments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(256) DEFAULT 'fr',
  `title` varchar(256) DEFAULT NULL,
  `titleseo` varchar(256) DEFAULT NULL,
  `forumthreads_id` int(11) DEFAULT NULL,
  `forumcomments_id` int(11) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `level` int(4) DEFAULT '1',
  `lineage` varchar(256) DEFAULT '000001',
  `postdate` varchar(128) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forumcomments`
--

LOCK TABLES `forumcomments` WRITE;
/*!40000 ALTER TABLE `forumcomments` DISABLE KEYS */;
INSERT INTO `forumcomments` VALUES (1,'fr','L&#039;Entreprise','l-entreprise',1,0,1,1,'000001','2013-03-11 14:55:35','Coucou ! Quelle belle nouvelle !'),(2,'fr','L\'Entreprise','l-entreprise-1',1,1,1,2,'000001000001','2013-03-11 14:55:52','Bah non, c\'est nul !'),(3,'fr','L\'Entreprise','l-entreprise-2',1,2,1,3,'000001000001000001','2013-03-11 14:56:03','vsvsdvsd'),(4,'fr','L\'Entreprise','l-entreprise-3',1,1,1,2,'000001000002','2013-03-11 14:56:06','vdvsvds'),(5,'fr','L&#039;Entreprise','l-entreprise-4',1,0,1,1,'000002','2013-03-11 14:58:03','vsvsdvs<br />\nvsdvsd<br />\nvsd<br />\nvsdv<br />\nsd<br />\nvds<br />\nvsdvsdvsdvsdv<br />\nsdvsdvsdvsdv');
/*!40000 ALTER TABLE `forumcomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forumthreads`
--

DROP TABLE IF EXISTS `forumthreads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forumthreads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(256) DEFAULT 'fr',
  `title` varchar(256) DEFAULT NULL,
  `titleseo` varchar(256) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `contents_id` int(11) DEFAULT '0',
  `createdate` varchar(128) DEFAULT '',
  `page_title` varchar(256) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forumthreads`
--

LOCK TABLES `forumthreads` WRITE;
/*!40000 ALTER TABLE `forumthreads` DISABLE KEYS */;
INSERT INTO `forumthreads` VALUES (1,'fr','L&#039;Entreprise','l-entreprise',1,15,'2013-03-11 14:55:35','L&#039;Entreprise','/fr/profil/entreprise/');
/*!40000 ALTER TABLE `forumthreads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `rights` text,
  `datarights` text,
  `language` varchar(256) DEFAULT 'fr',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'God','a:31:{i:10;s:2:\"10\";i:11;s:2:\"11\";i:12;s:2:\"12\";i:13;s:2:\"13\";i:14;s:2:\"14\";i:15;s:2:\"15\";i:16;s:2:\"16\";i:18;s:2:\"18\";i:17;s:2:\"17\";i:19;s:2:\"19\";i:20;s:2:\"20\";i:30;s:2:\"30\";i:31;s:2:\"31\";i:32;s:2:\"32\";i:33;s:2:\"33\";i:34;s:2:\"34\";i:35;s:2:\"35\";i:36;s:2:\"36\";i:37;s:2:\"37\";i:38;s:2:\"38\";i:40;s:2:\"40\";i:41;s:2:\"41\";i:42;s:2:\"42\";i:43;s:2:\"43\";i:44;s:2:\"44\";i:50;s:2:\"50\";i:51;s:2:\"51\";i:80;s:2:\"80\";i:90;s:2:\"90\";i:91;s:2:\"91\";i:99;s:2:\"99\";}','a:18:{s:6:\"blocks\";s:6:\"blocks\";s:13:\"blocks_modify\";s:13:\"blocks_modify\";s:6:\"nusers\";s:6:\"nusers\";s:13:\"nusers_modify\";s:13:\"nusers_modify\";s:7:\"nblocks\";s:7:\"nblocks\";s:14:\"nblocks_modify\";s:14:\"nblocks_modify\";s:8:\"settings\";s:8:\"settings\";s:15:\"settings_modify\";s:15:\"settings_modify\";s:7:\"filters\";s:7:\"filters\";s:14:\"filters_modify\";s:14:\"filters_modify\";s:6:\"groups\";s:6:\"groups\";s:13:\"groups_modify\";s:13:\"groups_modify\";s:16:\"tabledefinitions\";s:16:\"tabledefinitions\";s:23:\"tabledefinitions_modify\";s:23:\"tabledefinitions_modify\";s:11:\"tablefields\";s:11:\"tablefields\";s:18:\"tablefields_modify\";s:18:\"tablefields_modify\";s:5:\"users\";s:5:\"users\";s:12:\"users_modify\";s:12:\"users_modify\";}','fr'),(3,'Gestionnaires du site','a:16:{i:10;s:2:\"10\";i:11;s:2:\"11\";i:12;s:2:\"12\";i:13;s:2:\"13\";i:14;s:2:\"14\";i:15;s:2:\"15\";i:16;s:2:\"16\";i:18;s:2:\"18\";i:17;s:2:\"17\";i:20;s:2:\"20\";i:30;s:2:\"30\";i:31;s:2:\"31\";i:33;s:2:\"33\";i:34;s:2:\"34\";i:36;s:2:\"36\";i:80;s:2:\"80\";}','a:12:{s:6:\"blocks\";s:6:\"blocks\";s:13:\"blocks_modify\";s:13:\"blocks_modify\";s:4:\"news\";s:4:\"news\";s:11:\"news_modify\";s:11:\"news_modify\";s:8:\"settings\";s:8:\"settings\";s:15:\"settings_modify\";s:15:\"settings_modify\";s:3:\"faq\";s:3:\"faq\";s:10:\"faq_modify\";s:10:\"faq_modify\";s:6:\"slider\";s:6:\"slider\";s:13:\"slider_modify\";s:13:\"slider_modify\";s:11:\"testimonies\";s:11:\"testimonies\";s:18:\"testimonies_modify\";s:18:\"testimonies_modify\";}','fr'),(-1,'Tous','a:0:{}','a:0:{}','fr'),(-2,'Compte site inactif','a:0:{}','a:0:{}','fr'),(13,'','a:0:{}','a:0:{}','');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nblocks`
--

DROP TABLE IF EXISTS `nblocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nblocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `contents` text,
  `ddate` date DEFAULT NULL,
  `datesent` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nblocks`
--

LOCK TABLES `nblocks` WRITE;
/*!40000 ALTER TABLE `nblocks` DISABLE KEYS */;
INSERT INTO `nblocks` VALUES (1,'fr','test 1','','2011-02-23',''),(2,'fr','test 2','','2011-02-23','');
/*!40000 ALTER TABLE `nblocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(256) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',
  `ddate` date DEFAULT NULL,
  `ddateshow` date DEFAULT NULL,
  `ddatehide` date DEFAULT NULL,
  `contents` text,
  `vedette` int(11) DEFAULT '0',
  `calendar` int(11) DEFAULT '0',
  `image` varchar(256) DEFAULT NULL,
  `blurb` longtext,
  `maintain` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
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
-- Table structure for table `newsletter`
--

DROP TABLE IF EXISTS `newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `ddate` date DEFAULT NULL,
  `blocks_id` int(11) DEFAULT NULL,
  `filters_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `completion` int(11) DEFAULT NULL,
  `layout` varchar(256) DEFAULT 'default',
  `nblocks_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter`
--

LOCK TABLES `newsletter` WRITE;
/*!40000 ALTER TABLE `newsletter` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletterusers`
--

DROP TABLE IF EXISTS `newsletterusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newsletterusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `sourcetable` varchar(256) DEFAULT NULL,
  `sourcekey` varchar(256) DEFAULT NULL,
  `sent` int(11) DEFAULT '0',
  `newsletter_id` int(11) DEFAULT NULL,
  `checkcode` varchar(40) DEFAULT '0',
  `readdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletterusers`
--

LOCK TABLES `newsletterusers` WRITE;
/*!40000 ALTER TABLE `newsletterusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletterusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `content` varchar(256) DEFAULT NULL,
  `path` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
INSERT INTO `notes` VALUES (1,'fr','Contenu','','__CONTENU__'),(2,'fr','Accueil','http://websitesapp.com/doc/doku.php?id=accueil','__ACCUEIL__'),(3,'fr','Contenu - D&eacute;tails','','/fr/content/'),(4,'fr','Fichiers','','/fr/files'),(5,'fr','Tables - CMS - Documentation','','/tables/notes'),(6,'fr','Tables - CMS - Utilisateurs','','/tables/users');
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nusers`
--

DROP TABLE IF EXISTS `nusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `surname` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `active` int(11) DEFAULT NULL,
  `provenance` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nusers`
--

LOCK TABLES `nusers` WRITE;
/*!40000 ALTER TABLE `nusers` DISABLE KEYS */;
INSERT INTO `nusers` VALUES (1,'fr','IDEVA Gestion','','gestion@ideva.ca',1,'');
/*!40000 ALTER TABLE `nusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photoalbums`
--

DROP TABLE IF EXISTS `photoalbums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photoalbums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(256) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',
  `ddate` date DEFAULT NULL,
  `thumbnail` varchar(256) DEFAULT NULL,
  `featured` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photoalbums`
--

LOCK TABLES `photoalbums` WRITE;
/*!40000 ALTER TABLE `photoalbums` DISABLE KEYS */;
INSERT INTO `photoalbums` VALUES (1,'fr','Photos la derni&egrave;re descente en folie de d&eacute;cembre','photos-la-derniere-descente-en-folie-de-decembre','2011-12-12','/public/images/albums/2011-12-12/j0399404.jpg',0),(2,'fr','Haut sur la montagne','haut-sur-la-montagne','2011-12-23','/public/images/albums/2011-12-12/jlm-ski-19.jpg',1);
/*!40000 ALTER TABLE `photoalbums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',
  `value` varchar(256) DEFAULT NULL,
  `lck` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'fr','Titre vide.','titre-vide','',1);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slider`
--

DROP TABLE IF EXISTS `slider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT 'Titre vide.',
  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',
  `porder` varchar(10) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `link` varchar(256) DEFAULT NULL,
  `link_title` varchar(256) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider`
--

LOCK TABLES `slider` WRITE;
/*!40000 ALTER TABLE `slider` DISABLE KEYS */;
/*!40000 ALTER TABLE `slider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tabledefinitions`
--

DROP TABLE IF EXISTS `tabledefinitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tabledefinitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(256) DEFAULT 'fr',
  `title` varchar(256) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `description` text,
  `sortparams` varchar(256) DEFAULT 'title asc',
  `filtrable` int(11) DEFAULT NULL,
  `childtable` varchar(256) DEFAULT NULL,
  `system` int(11) DEFAULT '0',
  `rss` int(11) DEFAULT '0',
  `rss_sublink` varchar(256) DEFAULT NULL,
  `porder` smallint(4) DEFAULT NULL,
  `inlineadd` int(11) DEFAULT '0',
  `monolanguage` int(11) DEFAULT '0',
  `default_language` varchar(256) DEFAULT 'fr',
  `menu_fr` varchar(256) DEFAULT 'Donn&eacute;es',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=298 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tabledefinitions`
--

LOCK TABLES `tabledefinitions` WRITE;
/*!40000 ALTER TABLE `tabledefinitions` DISABLE KEYS */;
INSERT INTO `tabledefinitions` VALUES (2,'fr','Utilisateurs','users','Utilisateurs.','username asc',0,'',1,0,'',0,0,1,'fr','Donn&eacute;es'),(1,'fr','Tables','tabledefinitions','Cette table repr&eacute;sente les tables accessibles par l&#039;utilisateur au travers du CMS.\nElle permet de cr&eacute;er des tables suppl&eacute;mentaires.','system asc, title asc',0,'tablefields',1,0,'',0,0,1,'fr','Donn&eacute;es'),(3,'fr','Tables - Champs','tablefields','Ceci est une table syst&egrave;me !\nElle repr&eacute;sente les champs utilis&eacute;s dans les tables du CMS.','tabledefinitions_id asc, porder asc, title asc',0,'',1,0,'',0,0,1,'fr','Donn&eacute;es'),(4,'fr','Bannieres','banners','Bannieres publicitaires.','title asc',0,'',1,0,'',0,0,0,'fr','Donn&eacute;es'),(25,'fr','Groupes','groups','Groupes d&#039;utilisateurs.','title asc',0,'',1,0,'',0,0,1,'fr','Donn&eacute;es'),(41,'fr','Blocs','blocks','Cette liste reprend tous les textes fixes du site.','language asc, title asc',0,'',0,0,'',0,0,0,'fr','Contenu'),(46,'fr','Documentation','notes','Cette table reprend la documentation du CMS / Site.','title asc',0,'',1,0,'',0,0,1,'fr','Donn&eacute;es'),(49,'fr','Destinataires','nusers','Liste des personnes inscrites &agrave; la liste de diffusion.','title asc',1,'',0,0,'',0,0,0,'fr','Newsletters'),(50,'fr','Filtres','filters','Ceci est une liste des filtres disponibles pour les envois de newsletter.','title asc',0,'',1,0,'',0,0,1,'fr','Donn&eacute;es'),(126,'fr','Textes','nblocks','Textes utilis&eacute;s par les newsletter.','title asc',0,'',0,0,'',0,0,0,'fr','Newsletters'),(250,'fr','T&eacute;l&eacute;chargements','documents','Les t&eacute;l&eacute;chargements mis &agrave; disposition','title asc',0,'',0,0,'',0,0,0,'fr','Donn&eacute;es'),(263,'fr','Nouvelles','news','Les nouvelles du site.','title asc',0,'',0,1,'/evenements/fiche/+',0,0,0,'fr','Donn&eacute;es'),(287,'fr','Slider','slider','Slider en page d&#039;accueil.','title asc',0,'',0,0,'',0,0,0,'fr','Donn&eacute;es'),(297,'fr','T&eacute;moignages','testimonies','Liste des t&eacute;moignages du site.','title asc',0,'',0,0,'',0,0,0,'fr','Donn&eacute;es'),(289,'fr','Albums Photo','photoalbums','Albums de photos sur le site.','title asc',0,'',0,0,'',0,0,0,'fr','Donn&eacute;es'),(290,'fr','R&egrave;glages','settings','R&egrave;glages du site','title asc',0,'',0,0,'',0,0,0,'fr','Donn&eacute;es'),(296,'fr','Savez-vous que ?','faq','','title asc',0,'',0,0,'',0,0,0,'fr','Donn&eacute;es');
/*!40000 ALTER TABLE `tabledefinitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablefields`
--

DROP TABLE IF EXISTS `tablefields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tablefields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `tabledefinitions_id` int(11) DEFAULT NULL,
  `porder` int(11) DEFAULT NULL,
  `showlist` int(11) DEFAULT NULL,
  `listeditable` int(11) DEFAULT '0',
  `showedit` int(11) DEFAULT '1',
  `description` text,
  `type` int(11) DEFAULT '20',
  `format_en` varchar(256) DEFAULT NULL,
  `format_fr` varchar(256) DEFAULT NULL,
  `format_es` varchar(256) DEFAULT NULL,
  `filtre` int(11) DEFAULT NULL,
  `default` varchar(256) DEFAULT NULL,
  `width` varchar(256) DEFAULT '100%',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1925 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablefields`
--

LOCK TABLES `tablefields` WRITE;
/*!40000 ALTER TABLE `tablefields` DISABLE KEYS */;
INSERT INTO `tablefields` VALUES (1,'fr','id','id',2,1,0,0,0,'ID unique de la table.',10,'','','',0,'','100%'),(2,'fr','username','Courriel',2,4,1,0,1,'Le courriel sera votre point de contact et votre login sur le site.',20,'','','',0,'','30%'),(3,'fr','password','Mot de passe',2,5,0,0,1,'Mot de passe (Laisser vide pour ne pas le changer).',21,'','','',0,'','100%'),(4,'fr','groups_id','Groupe',2,9,1,1,1,'Groupe auquel l&#039;utilisateur appartient.',19,'','','',0,'','20%'),(10,'fr','sortparams','Tri',1,6,0,0,1,'Parametres de tri.',20,'','','',0,'','100%'),(8,'fr','name','Nom',1,4,1,0,1,'Nom r&eacute;el de la table.',20,'','','',0,'','100%'),(7,'fr','title','Libell&eacute;',1,3,1,0,1,'Libell&eacute; de la table (utilis&eacute; pour l\'affichage dans le CMS)',20,'','','',0,'','100%'),(6,'fr','language','Langue',1,2,0,0,0,'Langue de la table.',29,'','','',0,'fr','100%'),(9,'fr','description','Description',1,5,0,0,1,'Description de la table, explications quand &agrave; son contenu, ...',30,'','','',0,'','100%'),(5,'fr','id','id',1,1,0,0,0,'ID unique de la table.',10,'','','',0,'','100%'),(25,'fr','format_es','Formattage ES',3,16,0,0,1,'Formattage sp&eacute;cifique du champ en ES',20,'','','',0,'','100%'),(23,'fr','format_en','Formattage EN',3,14,0,0,1,'Formattage sp&eacute;cifique du champ en EN',20,'','','',0,'','100%'),(24,'fr','format_fr','Formattage FR',3,15,0,0,1,'Formattage sp&eacute;cifique du champ en FR',20,'','','',0,'','100%'),(21,'fr','type','Type',3,6,1,0,1,'Type du champ.',18,'','','',0,'20','100%'),(20,'fr','description','Description',3,12,0,0,1,'Description du champ. Essayer de donner des explications claires et concises, c&#039;est tr&egrave;s important !',30,'','','',0,'','100%'),(19,'fr','showedit','Aff. ds Editeur',3,11,1,1,1,'Affiche ou non le champ dans l&#039;&eacute;diteur.',11,'','','',0,'1','100%'),(18,'fr','showlist','Aff. ds Liste',3,9,1,1,1,'Affiche ou non le champ dans la liste.',11,'','','',0,'','100%'),(17,'fr','porder','Ordre',3,7,1,1,1,'Numero d&#039;ordre du champ dans la table.',17,'','','',0,'','100%'),(15,'fr','name','Libell&eacute;',3,3,1,0,1,'Libell&eacute; du champ.',20,'','','',0,'','60%'),(16,'fr','tabledefinitions_id','Table',3,5,1,0,1,'Table a laquelle le champ appartient.',19,'','','',0,'','30%'),(12,'fr','id','id',3,1,0,0,0,'ID unique du champ.',10,'','','',0,'','100%'),(13,'fr','language','Langue',3,2,0,0,0,'Langue du champ.',29,'','','',0,'','100%'),(14,'fr','title','Nom',3,4,0,0,1,'Nom r&eacute;el du champ.',20,'','','',0,'','100%'),(26,'fr','company_id','Entreprise',4,8,1,0,1,'Entreprise liees a la banniere.',19,'','','',0,'','100%'),(27,'fr','clicks','Clics',4,7,1,0,1,'Total de clics effectues sur la banniere.',10,'','','',0,'','100%'),(28,'fr','content','Contenu',4,6,0,0,1,'Information concernant la banniere.',30,'','','',0,'','100%'),(29,'fr','url','Site',4,5,0,0,1,'URL du site lie a la banniere.',20,'','','',0,'','100%'),(30,'fr','file','Image - Top',4,4,1,0,1,'Image de la banniere.',23,'','','',0,'','100%'),(31,'fr','title','Nom',4,3,1,0,1,'Nom de la banniere.',20,'','','',0,'','100%'),(32,'fr','language','Langue',4,2,0,0,1,'Langue de la banniere.',29,'','','',0,'','100%'),(33,'fr','id','id',4,1,0,0,0,'ID unique de la table.',10,'','','',0,'','100%'),(34,'fr','listeditable','Editable ds. liste',3,10,0,0,1,'Specifie si le champ est editable dans la liste ou pas.',11,'','','',0,'','100%'),(84,'fr','id','id',25,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(88,'fr','title','Nom et pr&eacute;nom',2,2,1,0,1,'',20,'','','',0,'','30%'),(90,'fr','title','Nom',25,1,1,0,1,'Nom du groupe.',20,'','','',0,'','100%'),(91,'fr','rights','Modules',25,4,0,0,1,'Liste des modules auxquels l&#039;utilisateur a acc&egrave;s.',34,'','','',0,'','100%'),(92,'fr','datarights','Donn&eacute;es',25,6,0,0,1,'Liste des donn&eacute;es auxquelles le groupe a droit.',33,'','','',0,'','100%'),(93,'fr','id','ID',41,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(94,'fr','language','Langue',41,3,0,0,1,'Langue.',29,'','','',0,'fr','100%'),(95,'fr','title','Nom',41,2,1,0,1,'Nom du bloc. Veuillez &ecirc;tre explicite, ce nom sera pr&eacute;sent&eacute; dans le gestionnaire de contenu afin de vous permettre de choisir un bloc particulier.',20,'','','',0,'','100%'),(96,'fr','content','Contenu',41,5,0,0,1,'',31,'','','',0,'','100%'),(117,'fr','id','ID',46,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(118,'fr','language','Langue',46,2,0,0,1,'Langue.',29,'','','',0,'','100%'),(119,'fr','title','Titre',46,3,1,0,1,'Titre.',20,'','','',0,'','100%'),(120,'fr','content','Lien Websitesapp.com',46,5,0,0,1,'Chemin vers la page de documentation sur websitesapp.com',20,'','','',0,'','100%'),(133,'fr','id','ID',49,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(134,'fr','language','Langue de communication',49,2,0,0,1,'Langue.',29,'','','',0,'','100%'),(135,'fr','title','Nom',49,3,1,0,1,'Titre.',20,'','','',0,'','100%'),(136,'fr','surname','Pr&eacute;nom',49,4,0,0,0,'',20,'','','',0,'','100%'),(137,'fr','email','Courriel',49,5,0,0,1,'',20,'','','',1,'','100%'),(153,'fr','filtrable','Filtrable',1,10,1,1,1,'Indique si cette table peut &ecirc;tre filtr&eacute;e ou non.',11,'','','',0,'','100%'),(154,'fr','filtre','Filtre',4,15,0,0,1,'Indique si ce champ peut &ecirc;tre utilis&eacute; comme crit&egrave;re de filtre ou non.',11,'','','',0,'','100%'),(155,'fr','filtre','Filtre',3,17,0,0,1,'Indique si ce champ peut &ecirc;tre un crit&egrave;re de filtre.',11,'','','',0,'','100%'),(156,'fr','id','ID',50,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(157,'fr','language','Langue',50,2,0,0,1,'Langue.',29,'','','',0,'','100%'),(158,'fr','title','Titre',50,3,1,0,1,'Titre.',20,'','','',0,'','25%'),(169,'fr','tabledefinitions_id','Table',50,6,0,0,1,'Table utilis&eacute;e dans le filtre.',19,'','','',0,'','100%'),(170,'fr','childtable','Table fille',1,15,0,0,1,'Nom de la table fille. Si indiqu&eacute;, le syst&eacute;me affichera les enregistrements de cette table fille en-dessous lors de la modification d&#039;un enregistrement de la table actuelle.',20,'','','',0,'','100%'),(216,'fr','active','Actif',49,8,1,1,1,'Utilisateur actif.',11,'','','',0,'','100%'),(218,'fr','path','Chemin',46,4,0,0,1,'Chemin de la page &agrave; documenter ( peut &ecirc;tre partiel, comme par exemple &quot;/admin/fr/tables&quot; ou encore &quot;files&quot;, etc... )',20,'','','',0,'','100%'),(219,'fr','default','Valeur par d&eacute;faut',3,8,0,0,1,'Valeur par d&eacute;faut du champ. Cette valeur sera automatiquement ins&eacute;r&eacute;e lors de la cr&eacute;ation d&#039;un enregistrement vide.',20,'','','',0,'','100%'),(237,'fr','width','Largeur liste',3,13,0,0,1,'Largeur dans la liste (exemples: 25%, 140px, ...)',20,'','','',0,'100%','100%'),(238,'fr','system','Syst&egrave;me',1,10,1,0,1,'Indique si la table appartient au CMS ou pas.',11,'','','',0,'0','100%'),(281,'fr','language','Langue',25,4,0,0,0,'',29,'','','',0,'fr','100%'),(282,'fr','language','Langue',2,1,0,0,0,'Langue de l&#039;utilisateur.',29,'','','',0,'fr','100%'),(290,'fr','provenance','Provenance',49,6,0,0,0,'R&eacute;gion, province ou pays de provenance.',20,'','','',0,'','100%'),(540,'fr','rss','RSS',1,29,1,0,1,'Indique si la table peut g&eacute;n&eacute;rer un flux RSS. ATTENTION !!! Une table ne peut g&eacute;n&eacute;rer un flux RSS QUE si il y a au moins un champ titre, date et description !!!',11,'','','',0,'0','100%'),(541,'fr','rss_sublink','RSS Sublink',1,44,0,0,1,'Lien ( sans ID ) permettant de voir chaque &eacute;l&eacute;ment de la table dans une page. Attention, ne pas mettre http, ni la langue. La page doit &ecirc;tre la m&ecirc;me dans toutes les langues. Exemple: /news-detail/+ deviendra http://site.com/fr/new',20,'','','',0,'','100%'),(554,'fr','imageback','Image - Background',4,34,0,0,1,'Image background &agrave; utiliser.',22,'','','',0,'','100%'),(555,'fr','backgroundcolor','Couleur du fond ( # web )',4,28,0,0,1,'Couleur du fond du site ( mettre le code hexa sur 6 caract&egrave;res )',20,'','','',0,'','100%'),(1476,'','mediadescription','Description',0,13,0,0,1,'Description du media (Infobulle)',32,'','','',0,'','100%'),(1621,'fr','bio','Bio',2,99,0,0,1,'',30,'','','',0,'','100%'),(1622,'fr','twitter','Compte Twitter',2,99,0,0,0,'',20,'','','',0,'','100%'),(1623,'fr','website','Site internet',2,99,0,0,1,'L&#039;url de votre site internet.',20,'','','',0,'','100%'),(1624,'fr','titleseo','Title SEO',41,4,0,0,0,'Identifiant pour le bloc.',20,'','','',0,'','100%'),(1625,'fr','position','Positionnement',41,30,1,1,1,'Indique que le bloc sera plac&eacute; &agrave; cette position sur toutes les pages utilisant le template sp&eacute;cifi&eacute;.',26,'','','',0,'','100%'),(800,'fr','id','ID',126,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(801,'fr','language','Langue',126,2,0,0,1,'Langue.',29,'','','',0,'fr','100%'),(802,'fr','title','Titre',126,3,1,0,1,'Titre.',20,'','','',0,'Titre vide.','100%'),(803,'fr','contents','Contenu',126,10,0,0,1,'',31,'','','',0,'','100%'),(828,'fr','inlineadd','Inline',1,10,0,0,1,'Indique si la table peut &ecirc;tre automatiquement inline dans une autre.',11,'','','',0,'0','100%'),(846,'fr','ddate','Date cr&eacute;ation',126,30,0,0,1,'',50,'','','',0,'','100%'),(847,'fr','datesent','Date envoi',126,85,0,0,1,'',20,'','','',0,'','100%'),(848,'fr','condition','Condition',50,10,1,0,1,'Condition.',20,'','','',0,'','100%'),(1511,'fr','titleseo','Titre SEO',250,4,0,0,0,'Titre SEO (Automatique).',20,'','','',0,'Titre SEO.','100%'),(1512,'fr','description','Description',250,10,0,0,1,'Description du document',30,'','','',0,'','100%'),(1513,'fr','file','Fichier',250,11,0,0,1,'Fichier',23,'','','',0,'','100%'),(1514,'fr','groups_id','Media',250,12,1,1,1,'Groupe ayant acc&egrave;s',19,'','','',0,'','100%'),(1515,'fr','ddate','Date',250,13,0,0,1,'Date de mise &agrave; jour du document',50,'','','',0,'','100%'),(1508,'fr','id','ID',250,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(1509,'fr','language','Langue',250,2,0,0,1,'Langue.',29,'','','',0,'fr','100%'),(1510,'fr','title','Titre',250,3,1,0,1,'Titre.',20,'','','',0,'Titre vide.','100%'),(1597,'fr','monolanguage','Monolangue',1,39,1,1,1,'Indique si la table est dans une seule langue uniquement. Si oui, utilise defaultlanguage.',11,'','','',0,'0','100%'),(1599,'fr','default_language','Langue par d&eacute;faut',1,41,0,0,1,'Langue par d&eacute;faut de la table.\nUtilis&eacute;e que si monolanguage=1',28,'','','',0,'fr','100%'),(1628,'fr','title','Titre',263,3,1,0,1,'Titre.',20,'','','',0,'Titre vide.','75%'),(1629,'fr','titleseo','Titre SEO',263,98,0,0,0,'Titre SEO (Automatique).',20,'','','',0,'Titre SEO.','100%'),(1630,'fr','ddate','Date affich&eacute;e',263,10,1,0,1,'Date de l&#039;&eacute;v&eacute;nement qui sera affich&eacute;e.',50,'','','',0,'','15%'),(1631,'fr','ddateshow','D&eacute;but',263,11,1,0,1,'Date de d&eacute;but d&#039;affichage.',50,'','','',0,'','15%'),(1632,'fr','ddatehide','Fin',263,12,1,0,1,'Date de fin d&#039;affichage.',50,'','','',0,'','100%'),(1633,'fr','contents','Contenu',263,25,0,0,1,'Texte de la nouvelle',31,'','','',0,'','100%'),(1627,'fr','language','Langue',263,4,0,0,1,'Langue.',29,'','','',0,'fr','100%'),(1626,'fr','id','ID',263,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(1680,'fr','adresse','Adresse',2,20,0,0,1,'',20,'','','',0,'','100%'),(1681,'fr','ville','Ville',2,21,0,0,1,'',20,'','','',0,'','100%'),(1682,'fr','codepostal','Code postal',2,22,0,0,1,'',20,'','','',0,'','100%'),(1683,'fr','telephone','T&eacute;l&eacute;phone',2,23,0,0,1,'',20,'','','',0,'','100%'),(1758,'fr','fax','T&eacute;l&eacute;copieur',2,24,0,0,1,'',20,'','','',0,'','100%'),(1675,'fr','hash','Hash',2,99,0,0,0,'Hash pour l&#039;activation du compte.',20,'','','',0,'','100%'),(1818,'fr','vedette','Vedette',263,30,1,1,1,'Indique si la nouvelle doit se retrouver en page d&#039;accueil ou non.',11,'','','',0,'0','100%'),(1819,'fr','calendar','Calendrier',263,31,0,1,0,'Indique si la nouvelle ou l&#039;&eacute;v&eacute;nement se retrouvera dans le calendrier du site.',11,'','','',0,'0','100%'),(1820,'fr','id','ID',287,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(1821,'fr','language','Langue',287,2,0,0,1,'Langue.',29,'','','',0,'','100%'),(1822,'fr','title','Titre',287,3,1,0,1,'Titre.',20,'','','',0,'Titre vide.','100%'),(1823,'fr','titleseo','Titre SEO',287,4,0,0,0,'Titre SEO (Automatique).',20,'','','',0,'Titre SEO.','100%'),(1824,'fr','porder','Position',287,5,1,1,1,'Position de la fiche.',17,'','','',0,'','100%'),(1825,'fr','image','Image',287,30,0,0,1,'Image (930x324 environ)',22,'','','',0,'','100%'),(1826,'fr','link','Lien',287,7,0,0,1,'Lien correspondant.',20,'','','',0,'','100%'),(1827,'fr','image','Image mini',263,16,0,0,1,'Image pour les activites en vedette (76x80), optionnelle.',22,'','','',0,'','100%'),(1828,'fr','blurb','Texte mini',263,24,0,0,1,'Mini phrase ou paragraphe de description de l&#039;&eacute;v&egrave;nement.',30,'','','',0,'','100%'),(1923,'fr','active','Actif',297,10,1,1,1,'',11,'','','',0,'1','100%'),(1922,'fr','contents','Contenu',297,10,0,0,1,'',31,'','','',0,'','100%'),(1918,'fr','id','ID',297,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(1919,'fr','language','Langue',297,2,0,0,1,'Langue.',29,'','','',0,'fr','100%'),(1920,'fr','title','Titre',297,3,1,0,1,'Titre.',20,'','','',0,'Titre vide.','100%'),(1921,'fr','titleseo','Titre SEO',297,4,0,0,0,'Titre SEO (Automatique).',20,'','','',0,'Titre SEO.','100%'),(1917,'fr','description','Description',287,70,0,0,1,'Petit texte de description.',31,'','','',0,'','100%'),(1916,'fr','link_title','Titre lien',287,6,0,0,1,'Titre du lien de droite.',20,'','','',0,'','100%'),(1841,'fr','id','ID',289,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(1842,'fr','language','Langue',289,2,0,0,0,'Langue.',29,'','','',0,'fr','100%'),(1843,'fr','title','Titre',289,3,1,0,1,'Titre/nom de l&#039;album.',20,'','','',0,'Titre vide.','100%'),(1844,'fr','titleseo','Titre SEO',289,4,0,0,0,'Titre SEO (Automatique).',20,'','','',0,'Titre SEO.','100%'),(1845,'fr','ddate','Date',289,9,1,0,1,'Date d&#039;ajout de l&#039;album.',50,'','','',0,'','100%'),(1846,'fr','thumbnail','Une image',289,26,0,0,1,'Choisissez une image de votre album. Le syst&egrave;me fera 2 choses:\n- il fera utilisera automatiquement cette image comme icone dans la liste d&#039;albums sur le site\n- il prendra automatiquement toutes les images qui sont dans ce r&eacute;pertoire pour en faire un album',22,'','','',0,'','100%'),(1847,'fr','featured','Vedette',289,16,1,1,1,'Indique si l&#039;album photo sera en vedette sur la premi&egrave;re page.',11,'','','',0,'','100%'),(1848,'fr','menu_fr','Menu FR',1,40,0,0,1,'Menu du CMS ou se placera la table.',20,'','','',0,'Donn&eacute;es','100%'),(1849,'fr','maintain','Maintenir',263,32,0,1,0,'Indique si une nouvelle est maintenue sur le site ou non.',11,'','','',0,'0','100%'),(1850,'fr','id','ID',290,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(1851,'fr','language','Langue',290,2,0,0,1,'Langue.',29,'','','',0,'fr','100%'),(1852,'fr','title','Intitul&eacute;',290,3,1,0,1,'R&eacute;glage ( attention de ne pas changer l&#039;intul&eacute; d&#039;un r&eacute;glage existante ).',20,'','','',0,'Titre vide.','100%'),(1853,'fr','titleseo','Titre SEO',290,4,0,0,0,'Titre SEO (Automatique).',20,'','','',0,'Titre SEO.','100%'),(1854,'fr','value','Valeur',290,9,1,0,1,'Valeur du r&eacute;glage.',20,'','','',0,'','100%'),(1899,'fr','id','ID',296,1,0,0,0,'Cl&eacute; de la table.',10,'','','',0,'','100%'),(1900,'fr','language','Langue',296,2,0,0,1,'Langue.',29,'','','',0,'fr','100%'),(1901,'fr','title','Titre',296,3,1,0,1,'Titre.',20,'','','',0,'Titre vide.','100%'),(1902,'fr','titleseo','Titre SEO',296,4,0,0,0,'Titre SEO (Automatique).',20,'','','',0,'Titre SEO.','100%'),(1903,'fr','contents','Contenu',296,64,0,0,1,'',31,'','','',0,'','100%'),(1904,'fr','blurb','R&eacute;sum&eacute;',296,20,0,0,1,'',31,'','','',0,'','100%'),(1905,'fr','porder','Ordre',296,8,1,1,1,'Ordre de la FAQ dans la page FAQ.',17,'','','',0,'','100%'),(1910,'fr','lck','Lock',290,5,0,0,0,'Indique si le record est bloqu&eacute; ou non.',11,'','','',0,'0','100%'),(1911,'fr','lck','Lock',41,5,0,0,0,'Indique si le record est bloqu&eacute; ou non.',11,'','','',0,'0','100%'),(1915,'fr','membersince','Membre depuis',2,50,1,0,0,'Date de la permi&egrave;re inscription de cet utilisateur.',20,'','','',0,'','100%');
/*!40000 ALTER TABLE `tablefields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonies`
--

DROP TABLE IF EXISTS `testimonies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',
  `contents` longtext,
  `active` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonies`
--

LOCK TABLES `testimonies` WRITE;
/*!40000 ALTER TABLE `testimonies` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `groups_id` int(11) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `titleseo` varchar(256) DEFAULT NULL,
  `language` varchar(256) DEFAULT 'fr',
  `bio` text,
  `twitter` varchar(256) DEFAULT NULL,
  `website` varchar(256) DEFAULT NULL,
  `hash` varchar(256) DEFAULT NULL,
  `adresse` varchar(256) DEFAULT NULL,
  `ville` varchar(256) DEFAULT NULL,
  `codepostal` varchar(256) DEFAULT NULL,
  `telephone` varchar(256) DEFAULT NULL,
  `fax` varchar(256) DEFAULT NULL,
  `membersince` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password` (`password`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'dgrevink@starplace.org','098f6bcd4621d373cade4e832627b4f6',1,'starplace.org','starplace-org','fr','','','http://starplace.org','','','','','','','2013-02-14 14:50:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weather`
--

DROP TABLE IF EXISTS `weather`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weather` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT 'fr',
  `title` varchar(256) DEFAULT 'Titre vide.',
  `titleseo` varchar(256) DEFAULT 'Titre SEO vide.',
  `override` int(11) DEFAULT NULL,
  `temp` varchar(256) DEFAULT NULL,
  `total_tracks` varchar(256) DEFAULT NULL,
  `snow_height` varchar(256) DEFAULT NULL,
  `commentaire` varchar(256) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `icon` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weather`
--

LOCK TABLES `weather` WRITE;
/*!40000 ALTER TABLE `weather` DISABLE KEYS */;
INSERT INTO `weather` VALUES (2,'fr','METEO ACTUELLE','meteo-actuelle',0,'10','0','0','Ensoleill&eacute;','2012-09-12 07:09:47','/public/images/meteo/00.gif');
/*!40000 ALTER TABLE `weather` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wsthistory`
--

DROP TABLE IF EXISTS `wsthistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wsthistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(4) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `table_name` varchar(256) DEFAULT NULL,
  `table_record_id` int(11) DEFAULT NULL,
  `ddate` datetime DEFAULT NULL,
  `data` longblob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wsthistory`
--

LOCK TABLES `wsthistory` WRITE;
/*!40000 ALTER TABLE `wsthistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `wsthistory` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-15 11:42:54
