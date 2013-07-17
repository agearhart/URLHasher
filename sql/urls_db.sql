-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.5.25 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             8.0.0.4396
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for urls
CREATE DATABASE IF NOT EXISTS `urls` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `urls`;


-- Dumping structure for table urls.hashes
CREATE TABLE IF NOT EXISTS `hashes` (
  `url` varchar(2048) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `cache_savings` bigint(20) NOT NULL DEFAULT '0',
  `used_count` bigint(20) NOT NULL DEFAULT '0',
  `date_inserted` datetime NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `hash` (`hash`),
  KEY `unq_url` (`url`(128))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
