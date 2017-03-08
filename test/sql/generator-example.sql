CREATE DATABASE IF NOT EXISTS `generator-example`;
USE `generator-example`;


CREATE TABLE IF NOT EXISTS `element_list` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`aid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `element` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `list` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `double_test` double NOT NULL,
  `decimal_test` decimal(10,0) NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `FK_element_lists` (`list`),
  CONSTRAINT `FK_element_lists` FOREIGN KEY (`list`) REFERENCES `element_list` (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;