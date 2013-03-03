USE `OurCoinDB`;

DROP TABLE IF EXISTS `OurCoinThumbnail`;

CREATE TABLE `OurCoinThumbnail` (
  `octid` int(11) NOT NULL AUTO_INCREMENT,
  `ocid` int(11) NOT NULL,
  `fileName` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`octid`)
);
