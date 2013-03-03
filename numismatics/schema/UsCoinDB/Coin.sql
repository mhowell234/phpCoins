USE `UsCoinDB`;

DROP TABLE IF EXISTS `Coin`;

CREATE TABLE `Coin` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `cvid` int(11) NOT NULL,
  `startYear` int(11) NOT NULL,
  `endYear` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`cid`)
); 

