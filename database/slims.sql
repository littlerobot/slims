-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: slims
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.6

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
-- Table structure for table `Activity`
--

DROP TABLE IF EXISTS `Activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Activity` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Dewar` varchar(50) NOT NULL,
  `Stack` int(11) NOT NULL,
  `Box` int(11) NOT NULL,
  `Position` int(11) NOT NULL,
  `Sample` int(11) DEFAULT NULL,
  `ActivityDate` datetime DEFAULT NULL,
  `Operation` int(11) DEFAULT NULL,
  `Staff` varchar(50) DEFAULT NULL,
  `ResearchGroup` int(11) DEFAULT NULL,
  `RecordAddedDate` datetime DEFAULT NULL,
  `User` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Activity`
--

LOCK TABLES `Activity` WRITE;
/*!40000 ALTER TABLE `Activity` DISABLE KEYS */;
INSERT INTO `Activity` VALUES (1,'',0,0,0,1,'0000-00-00 00:00:00',0,NULL,1,'2011-07-02 03:14:42',''),(2,'',0,0,0,2,'0000-00-00 00:00:00',0,NULL,1,'2011-07-02 03:14:42',''),(3,'',0,0,0,3,'0000-00-00 00:00:00',0,NULL,1,'2011-07-02 03:14:42',''),(4,'',0,0,0,4,'0000-00-00 00:00:00',0,NULL,1,'2011-07-02 03:14:42',''),(5,'',0,0,0,5,'0000-00-00 00:00:00',0,NULL,1,'2011-07-02 03:14:42',''),(6,'',0,0,0,6,'0000-00-00 00:00:00',0,NULL,1,'2011-07-02 03:14:42',''),(7,'',0,0,0,7,'0000-00-00 00:00:00',0,'Foo Bar',1,'2011-07-02 03:20:44',''),(8,'',0,0,0,8,'2011-10-05 00:00:00',0,'Foo Bar',1,'2011-07-02 03:35:53',''),(9,'',0,0,0,9,'2011-10-05 00:00:00',0,'Foo Bar',1,'2011-07-02 03:37:28',''),(10,'',0,0,0,10,'2011-10-05 00:00:00',1,'fb1',1,'2011-07-02 03:39:05',''),(11,'',0,0,0,11,'2011-10-23 00:00:00',1,'FooBar',1,'2011-07-02 07:27:20','fb1'),(12,'',0,0,0,11,'2011-10-23 00:00:00',0,'Array',1,'2011-07-02 10:52:51','1'),(13,'',0,0,0,10,'2011-10-23 00:00:00',0,'Foo Bar',1,'2011-07-02 10:54:51','fb1'),(14,'Hewey',1,1,91,13,'2011-10-23 00:00:00',1,'',1,'2011-07-02 12:05:59','fb1'),(15,'Hewey',1,1,92,14,'2011-10-23 00:00:00',1,'Foo Bar',1,'2011-07-02 12:06:49','fb1'),(16,'Hewey',1,1,91,13,'2011-10-23 00:00:00',0,'Array',1,'2011-07-02 12:07:51','fb1'),(17,'Hewey',1,1,92,14,'2011-10-23 00:00:00',2,'Foo Bar',1,'2011-07-02 12:13:47','fb1'),(18,'Hewey',1,1,93,15,'2011-10-23 00:00:00',1,'Foo Bar',1,'2011-07-02 12:20:43','fb1'),(19,'Hewey',1,1,93,15,'2011-10-23 00:00:00',2,'Array',1,'2011-07-02 12:24:12','fb1'),(20,'Hewey',1,1,31,16,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(21,'Hewey',1,1,32,17,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(22,'Hewey',1,1,33,18,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(23,'Hewey',1,1,41,19,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(24,'Hewey',1,1,42,20,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(25,'Hewey',1,1,43,21,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(26,'Hewey',1,1,51,22,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(27,'Hewey',1,1,52,23,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(28,'Hewey',1,1,53,24,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:20','fb1'),(29,'Hewey',1,1,61,25,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(30,'Hewey',1,1,62,26,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(31,'Hewey',1,1,63,27,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(32,'Hewey',1,1,71,28,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(33,'Hewey',1,1,72,29,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(34,'Hewey',1,1,73,30,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(35,'Hewey',1,1,81,31,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(36,'Hewey',1,1,82,32,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(37,'Hewey',1,1,83,33,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(38,'Hewey',1,1,91,34,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(39,'Hewey',1,1,92,35,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(40,'Hewey',1,1,93,36,'2011-10-27 00:00:00',1,'FooBar',1,'2011-07-02 17:34:38','fb1'),(41,'Hewey',1,1,15,37,'2011-11-03 00:00:00',1,'',3,'2011-11-03 20:51:05','Bar Baz'),(42,'Hewey',1,1,16,38,'2011-11-03 00:00:00',1,'',3,'2011-11-03 20:51:05','Bar Baz'),(43,'Hewey',1,1,25,39,'2011-11-03 00:00:00',1,'',3,'2011-11-03 20:51:05','Bar Baz'),(44,'Hewey',1,1,26,40,'2011-11-03 00:00:00',1,'',3,'2011-11-03 20:51:05','Bar Baz'),(45,'Hewey',1,1,54,41,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(46,'Hewey',1,1,55,42,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(47,'Hewey',1,1,56,43,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(48,'Hewey',1,1,64,44,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(49,'Hewey',1,1,65,45,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(50,'Hewey',1,1,66,46,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(51,'Hewey',1,1,74,47,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(52,'Hewey',1,1,75,48,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(53,'Hewey',1,1,76,49,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(54,'Hewey',1,1,84,50,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(55,'Hewey',1,1,85,51,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(56,'Hewey',1,1,86,52,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:15:00','Bar Baz'),(57,'Hewey',1,1,58,53,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:21:23','Bar Baz'),(58,'Hewey',1,1,59,54,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:21:23','Bar Baz'),(59,'Hewey',1,1,68,55,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:21:23','Bar Baz'),(60,'Hewey',1,1,69,56,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:21:23','Bar Baz'),(61,'Hewey',1,1,78,57,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:21:23','Bar Baz'),(62,'Hewey',1,1,79,58,'2011-11-03 00:00:00',1,'',3,'2011-11-03 21:21:23','Bar Baz'),(63,'Hewey',1,1,19,59,'2011-11-03 00:00:00',1,'',1,'2011-11-03 21:25:25','fb1'),(64,'Hewey',1,1,20,60,'2011-11-03 00:00:00',1,'',1,'2011-11-03 21:25:25','fb1'),(65,'Hewey',1,1,29,61,'2011-11-03 00:00:00',1,'',1,'2011-11-03 21:25:25','fb1'),(66,'Hewey',1,1,30,62,'2011-11-03 00:00:00',1,'',1,'2011-11-03 21:25:25','fb1'),(67,'Hewey',1,1,39,63,'2011-11-03 00:00:00',1,'',1,'2011-11-03 21:25:25','fb1'),(68,'Hewey',1,1,40,64,'2011-11-03 00:00:00',1,'',1,'2011-11-03 21:25:25','fb1'),(69,'Hewey',1,1,58,53,'2011-11-05 00:00:00',0,'',3,'2011-11-05 18:37:14','Bar Baz'),(70,'Hewey',1,1,59,54,'2011-11-05 00:00:00',0,'',3,'2011-11-05 18:37:14','Bar Baz'),(71,'Hewey',1,1,68,55,'2011-11-05 00:00:00',0,'',3,'2011-11-05 18:37:14','Bar Baz'),(72,'Hewey',1,1,69,56,'2011-11-05 00:00:00',0,'',3,'2011-11-05 18:37:14','Bar Baz'),(73,'Hewey',1,1,78,57,'2011-11-05 00:00:00',0,'',3,'2011-11-05 18:37:14','Bar Baz'),(74,'Hewey',1,1,79,58,'2011-11-05 00:00:00',0,'',3,'2011-11-05 18:37:14','Bar Baz'),(75,'Foo Bar',1,1,1,65,'2012-06-03 00:00:00',1,'',1,'2012-04-24 13:51:00','fb1'),(76,'Foo Bar',1,1,2,66,'2012-06-03 00:00:00',1,'',1,'2012-04-24 13:51:00','fb1'),(77,'Foo Bar',1,1,3,67,'2012-06-03 00:00:00',1,'',1,'2012-04-24 13:51:00','fb1'),(78,'Foo Bar',1,1,11,68,'2012-06-03 00:00:00',1,'',1,'2012-04-24 13:51:00','fb1'),(79,'Foo Bar',1,1,12,69,'2012-06-03 00:00:00',1,'',1,'2012-04-24 13:51:00','fb1'),(80,'Foo Bar',1,1,13,70,'2012-06-03 00:00:00',1,'',1,'2012-04-24 13:51:00','fb1');
/*!40000 ALTER TABLE `Activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BasalMedia`
--

DROP TABLE IF EXISTS `BasalMedia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BasalMedia` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `BasalMedia` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BasalMedia`
--

LOCK TABLES `BasalMedia` WRITE;
/*!40000 ALTER TABLE `BasalMedia` DISABLE KEYS */;
INSERT INTO `BasalMedia` VALUES (1,'the cornic');
/*!40000 ALTER TABLE `BasalMedia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Box`
--

DROP TABLE IF EXISTS `Box`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Box` (
  `Dewar` varchar(50) NOT NULL DEFAULT '',
  `Box` int(11) NOT NULL,
  `Stack` int(11) NOT NULL,
  `Width` int(11) NOT NULL,
  `Height` int(11) NOT NULL,
  `Comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Dewar`,`Box`,`Stack`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Box`
--

LOCK TABLES `Box` WRITE;
/*!40000 ALTER TABLE `Box` DISABLE KEYS */;
INSERT INTO `Box` VALUES ('Foo Bar',1,1,10,10,'graaah');
/*!40000 ALTER TABLE `Box` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CellLine`
--

DROP TABLE IF EXISTS `CellLine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CellLine` (
  `CellLineName` varchar(50) NOT NULL DEFAULT '',
  `PassageTechnique` varchar(255) DEFAULT NULL,
  `Tissue` int(11) DEFAULT NULL,
  `Species` int(11) DEFAULT NULL,
  `GrowthMode` varchar(13) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Morphology` int(11) DEFAULT NULL,
  `Kayrotype` varchar(50) DEFAULT NULL,
  `NegativeMycoplasmaTestDate` datetime DEFAULT NULL,
  PRIMARY KEY (`CellLineName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CellLine`
--

LOCK TABLES `CellLine` WRITE;
/*!40000 ALTER TABLE `CellLine` DISABLE KEYS */;
INSERT INTO `CellLine` VALUES ('Cell line 1','Cell line 2',1,2,'Cell line 3','Cell line 4',1,'Cell line 5','2011-10-01 00:00:00');
/*!40000 ALTER TABLE `CellLine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CellSample`
--

DROP TABLE IF EXISTS `CellSample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CellSample` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CellLine` varchar(50) DEFAULT NULL,
  `PassageNumber` varchar(20) DEFAULT NULL,
  `BasalMedia` int(11) DEFAULT NULL,
  `Comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CellSample`
--

LOCK TABLES `CellSample` WRITE;
/*!40000 ALTER TABLE `CellSample` DISABLE KEYS */;
INSERT INTO `CellSample` VALUES (65,'Cell line 1',NULL,NULL,''),(66,'Cell line 1',NULL,NULL,''),(67,'Cell line 1',NULL,NULL,''),(68,'Cell line 1',NULL,NULL,''),(69,'Cell line 1',NULL,NULL,''),(70,'Cell line 1',NULL,NULL,'');
/*!40000 ALTER TABLE `CellSample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Department`
--

DROP TABLE IF EXISTS `Department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Department` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Department` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Department`
--

LOCK TABLES `Department` WRITE;
/*!40000 ALTER TABLE `Department` DISABLE KEYS */;
INSERT INTO `Department` VALUES (1,'Dept 1'),(2,'Dept 2');
/*!40000 ALTER TABLE `Department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DewarDefinition`
--

DROP TABLE IF EXISTS `DewarDefinition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DewarDefinition` (
  `DewarName` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`DewarName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DewarDefinition`
--

LOCK TABLES `DewarDefinition` WRITE;
/*!40000 ALTER TABLE `DewarDefinition` DISABLE KEYS */;
INSERT INTO `DewarDefinition` VALUES ('A'),('B'),('C'),('Foo Bar'),('D'),('E'),('F'),('G');
/*!40000 ALTER TABLE `DewarDefinition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Documents`
--

DROP TABLE IF EXISTS `Documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Documents` (
  `CellLineName` varchar(50) NOT NULL,
  `Document` varchar(255) NOT NULL,
  PRIMARY KEY (`CellLineName`,`Document`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Documents`
--

LOCK TABLES `Documents` WRITE;
/*!40000 ALTER TABLE `Documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `Documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Kayrotype`
--

DROP TABLE IF EXISTS `Kayrotype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Kayrotype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kayrotype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Kayrotype`
--

LOCK TABLES `Kayrotype` WRITE;
/*!40000 ALTER TABLE `Kayrotype` DISABLE KEYS */;
INSERT INTO `Kayrotype` VALUES (1,'cabbage');
/*!40000 ALTER TABLE `Kayrotype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Location`
--

DROP TABLE IF EXISTS `Location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Location` (
  `Dewar` varchar(50) NOT NULL,
  `Stack` int(11) NOT NULL,
  `Box` int(11) NOT NULL,
  `Position` int(11) NOT NULL,
  `Sample` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`Dewar`,`Stack`,`Box`,`Position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Location`
--

LOCK TABLES `Location` WRITE;
/*!40000 ALTER TABLE `Location` DISABLE KEYS */;
INSERT INTO `Location` VALUES ('Foo Bar',1,1,1,65),('Foo Bar',1,1,2,66),('Foo Bar',1,1,3,67),('Foo Bar',1,1,4,-1),('Foo Bar',1,1,5,-1),('Foo Bar',1,1,6,-1),('Foo Bar',1,1,7,-1),('Foo Bar',1,1,8,-1),('Foo Bar',1,1,9,-1),('Foo Bar',1,1,10,-1),('Foo Bar',1,1,11,68),('Foo Bar',1,1,12,69),('Foo Bar',1,1,13,70),('Foo Bar',1,1,14,-1),('Foo Bar',1,1,15,-1),('Foo Bar',1,1,16,-1),('Foo Bar',1,1,17,-1),('Foo Bar',1,1,18,-1),('Foo Bar',1,1,19,-1),('Foo Bar',1,1,20,-1),('Foo Bar',1,1,21,-1),('Foo Bar',1,1,22,-1),('Foo Bar',1,1,23,-1),('Foo Bar',1,1,24,-1),('Foo Bar',1,1,25,-1),('Foo Bar',1,1,26,-1),('Foo Bar',1,1,27,-1),('Foo Bar',1,1,28,-1),('Foo Bar',1,1,29,-1),('Foo Bar',1,1,30,-1),('Foo Bar',1,1,31,-1),('Foo Bar',1,1,32,-1),('Foo Bar',1,1,33,-1),('Foo Bar',1,1,34,-1),('Foo Bar',1,1,35,-1),('Foo Bar',1,1,36,-1),('Foo Bar',1,1,37,-1),('Foo Bar',1,1,38,-1),('Foo Bar',1,1,39,-1),('Foo Bar',1,1,40,-1),('Foo Bar',1,1,41,-1),('Foo Bar',1,1,42,-1),('Foo Bar',1,1,43,-1),('Foo Bar',1,1,44,-1),('Foo Bar',1,1,45,-1),('Foo Bar',1,1,46,-1),('Foo Bar',1,1,47,-1),('Foo Bar',1,1,48,-1),('Foo Bar',1,1,49,-1),('Foo Bar',1,1,50,-1),('Foo Bar',1,1,51,-1),('Foo Bar',1,1,52,-1),('Foo Bar',1,1,53,-1),('Foo Bar',1,1,54,-1),('Foo Bar',1,1,55,-1),('Foo Bar',1,1,56,-1),('Foo Bar',1,1,57,-1),('Foo Bar',1,1,58,-1),('Foo Bar',1,1,59,-1),('Foo Bar',1,1,60,-1),('Foo Bar',1,1,61,-1),('Foo Bar',1,1,62,-1),('Foo Bar',1,1,63,-1),('Foo Bar',1,1,64,-1),('Foo Bar',1,1,65,-1),('Foo Bar',1,1,66,-1),('Foo Bar',1,1,67,-1),('Foo Bar',1,1,68,-1),('Foo Bar',1,1,69,-1),('Foo Bar',1,1,70,-1),('Foo Bar',1,1,71,-1),('Foo Bar',1,1,72,-1),('Foo Bar',1,1,73,-1),('Foo Bar',1,1,74,-1),('Foo Bar',1,1,75,-1),('Foo Bar',1,1,76,-1),('Foo Bar',1,1,77,-1),('Foo Bar',1,1,78,-1),('Foo Bar',1,1,79,-1),('Foo Bar',1,1,80,-1),('Foo Bar',1,1,81,-1),('Foo Bar',1,1,82,-1),('Foo Bar',1,1,83,-1),('Foo Bar',1,1,84,-1),('Foo Bar',1,1,85,-1),('Foo Bar',1,1,86,-1),('Foo Bar',1,1,87,-1),('Foo Bar',1,1,88,-1),('Foo Bar',1,1,89,-1),('Foo Bar',1,1,90,-1),('Foo Bar',1,1,91,-1),('Foo Bar',1,1,92,-1),('Foo Bar',1,1,93,-1),('Foo Bar',1,1,94,-1),('Foo Bar',1,1,95,-1),('Foo Bar',1,1,96,-1),('Foo Bar',1,1,97,-1),('Foo Bar',1,1,98,-1),('Foo Bar',1,1,99,-1),('Foo Bar',1,1,100,-1);
/*!40000 ALTER TABLE `Location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Morphology`
--

DROP TABLE IF EXISTS `Morphology`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Morphology` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Morphology` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Morphology`
--

LOCK TABLES `Morphology` WRITE;
/*!40000 ALTER TABLE `Morphology` DISABLE KEYS */;
INSERT INTO `Morphology` VALUES (1,'Morphy morph morph');
/*!40000 ALTER TABLE `Morphology` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Operation`
--

DROP TABLE IF EXISTS `Operation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Operation` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Operation` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Operation`
--

LOCK TABLES `Operation` WRITE;
/*!40000 ALTER TABLE `Operation` DISABLE KEYS */;
INSERT INTO `Operation` VALUES (1,'Freeze'),(2,'Thaw');
/*!40000 ALTER TABLE `Operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Position`
--

DROP TABLE IF EXISTS `Position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Position` (
  `ID` int(3) NOT NULL DEFAULT '0',
  `Position` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Position`
--

LOCK TABLES `Position` WRITE;
/*!40000 ALTER TABLE `Position` DISABLE KEYS */;
INSERT INTO `Position` VALUES (1,'A1'),(2,'A2'),(3,'A3'),(4,'A4'),(5,'A5'),(6,'A6'),(7,'A7'),(8,'A8'),(9,'A9'),(10,'A10'),(11,'B1'),(12,'B2'),(13,'B3'),(14,'B4'),(15,'B5'),(16,'B6'),(17,'B7'),(18,'B8'),(19,'B9'),(20,'B10'),(21,'C1'),(22,'C2'),(23,'C3'),(24,'C4'),(25,'C5'),(26,'C6'),(27,'C7'),(28,'C8'),(29,'C9'),(30,'C10'),(31,'D1'),(32,'D2'),(33,'D3'),(34,'D4'),(35,'D5'),(36,'D6'),(37,'D7'),(38,'D8'),(39,'D9'),(40,'D10'),(41,'E1'),(42,'E2'),(43,'E3'),(44,'E4'),(45,'E5'),(46,'E6'),(47,'E7'),(48,'E8'),(49,'E9'),(50,'E10'),(51,'F1'),(52,'F2'),(53,'F3'),(54,'F4'),(55,'F5'),(56,'F6'),(57,'F7'),(58,'F8'),(59,'F9'),(60,'F10'),(61,'G1'),(62,'G2'),(63,'G3'),(64,'G4'),(65,'G5'),(66,'G6'),(67,'G7'),(68,'G8'),(69,'G9'),(70,'G10'),(71,'H1'),(72,'H2'),(73,'H3'),(74,'H4'),(75,'H5'),(76,'H6'),(77,'H7'),(78,'H8'),(79,'H9'),(80,'H10'),(81,'I1'),(82,'I2'),(83,'I3'),(84,'I4'),(85,'I5'),(86,'I6'),(87,'I7'),(88,'I8'),(89,'I9'),(90,'I10'),(91,'J1'),(92,'J2'),(93,'J3'),(94,'J4'),(95,'J5'),(96,'J6'),(97,'J7'),(98,'J8'),(99,'J9'),(100,'J10');
/*!40000 ALTER TABLE `Position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ResearchGroup`
--

DROP TABLE IF EXISTS `ResearchGroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ResearchGroup` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ResearchGroupName` varchar(50) DEFAULT NULL,
  `Department` int(11) DEFAULT NULL,
  `IsAdmin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ResearchGroup`
--

LOCK TABLES `ResearchGroup` WRITE;
/*!40000 ALTER TABLE `ResearchGroup` DISABLE KEYS */;
INSERT INTO `ResearchGroup` VALUES (1,'Tissue Culture',1,1),(2,'Foo',1,0),(3,'Bar',2,0);
/*!40000 ALTER TABLE `ResearchGroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Species`
--

DROP TABLE IF EXISTS `Species`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Species` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Species` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Species`
--

LOCK TABLES `Species` WRITE;
/*!40000 ALTER TABLE `Species` DISABLE KEYS */;
INSERT INTO `Species` VALUES (1,'mouse'),(2,'human');
/*!40000 ALTER TABLE `Species` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Stack`
--

DROP TABLE IF EXISTS `Stack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Stack` (
  `Dewar` varchar(50) DEFAULT NULL,
  `Stack` int(11) DEFAULT NULL,
  `ResearchGroup` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Stack`
--

LOCK TABLES `Stack` WRITE;
/*!40000 ALTER TABLE `Stack` DISABLE KEYS */;
INSERT INTO `Stack` VALUES ('A',1,NULL),('B',1,3),('C',1,NULL),('D',1,NULL),('E',1,NULL),('F',1,NULL);
/*!40000 ALTER TABLE `Stack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Staff`
--

DROP TABLE IF EXISTS `Staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Staff` (
  `ID` varchar(50) NOT NULL,
  `StaffName` varchar(50) NOT NULL,
  `ResearchGroup` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Staff`
--

LOCK TABLES `Staff` WRITE;
/*!40000 ALTER TABLE `Staff` DISABLE KEYS */;
INSERT INTO `Staff` VALUES ('ab1','A B',3),('cd2','C D',1);
/*!40000 ALTER TABLE `Staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tissue`
--

DROP TABLE IF EXISTS `Tissue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tissue` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tissue` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tissue`
--

LOCK TABLES `Tissue` WRITE;
/*!40000 ALTER TABLE `Tissue` DISABLE KEYS */;
INSERT INTO `Tissue` VALUES (1,'Tissue 1'),(2,'Tissue 2');
/*!40000 ALTER TABLE `Tissue` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-04-24 14:59:39
