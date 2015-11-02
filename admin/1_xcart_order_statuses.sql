-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: test_dev1
-- ------------------------------------------------------
-- Server version	5.5.31-1~dotdeb.0-log

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
-- Table structure for table `xcart_order_statuses`
--

DROP TABLE IF EXISTS `xcart_order_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xcart_order_statuses` (
  `code` char(1) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` char(2) NOT NULL DEFAULT '',
  `orderby` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xcart_order_statuses`
--

LOCK TABLES `xcart_order_statuses` WRITE;
/*!40000 ALTER TABLE `xcart_order_statuses` DISABLE KEYS */;
INSERT INTO `xcart_order_statuses` VALUES ('3','Pending partial refund','CB',81),('4','RMA','CA',80),('5','Watch list','CA',70),('6','Call required','CA',60),('7','Closed','CA',50),('8','Complete','CA',40),('9','Supervisor','CA',10),('A','Canceled','CB',60),('B','Backordered','DC',40),('C','Dispatched','DC',30),('D','Declined','CB',50),('E','Pending order entry','DC',20),('F','Failed','CB',70),('G','Shipped/Backordered','DC',50),('H','Partially refunded','CB',90),('I','Not finished','CB',10),('J','Indian office','CA',30),('K','Pending availability check','DC',11),('L','Received by distributor','DC',31),('M','BO/Pending addl payment','DC',12),('N','Unpaid','CB',40),('O','Unpaid: PO','CB',30),('P','Paid','CB',80),('Q','Queued','CB',20),('R','Fully refunded','CB',100),('S','Shipped','DC',60),('T','Not shipped','DC',10),('U','Russian office','CA',20),('V','Pending full refund','CB',82),('W','Unpaid','BD',10),('X','Invoiced','BD',20),('Y','Paid','BD',30),('Z','Refunded','BD',40);
/*!40000 ALTER TABLE `xcart_order_statuses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-05-28  5:35:44
