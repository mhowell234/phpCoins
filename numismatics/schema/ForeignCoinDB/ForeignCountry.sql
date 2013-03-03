USE `ForeignCoinDB`;

DROP TABLE IF EXISTS `ForeignCountry`;

CREATE TABLE `ForeignCountry` (
  `fcid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `possessiveName` varchar(255) NOT NULL,
  `abbreviation` varchar(10),
  PRIMARY KEY (`fcid`)
); 

