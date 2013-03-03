USE `OurForeignCoinDB`;

DROP TABLE IF EXISTS `OurCoinPhoto`;

CREATE TABLE `OurCoinPhoto` (
  `ocpid` int(11) NOT NULL AUTO_INCREMENT,
  `ocid` int(11) NOT NULL,
  `fileName` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ocpid`)
);
