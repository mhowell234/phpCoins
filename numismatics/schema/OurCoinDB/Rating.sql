USE `OurCoinDB`;

DROP TABLE IF EXISTS `Rating`;

CREATE TABLE `Rating` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `ocid` int(11) NOT NULL,
  `raid` int(11) NOT NULL,
  `srsid` int(11) DEFAULT NULL,
  `ratingDate` date DEFAULT NULL,
  PRIMARY KEY (`rid`)
);
