USE `OurForeignCoinDB`;

DROP TABLE IF EXISTS `OurCoin`;

CREATE TABLE `OurCoin` (
  `ocid` int(11) NOT NULL AUTO_INCREMENT,
  `mcid` int(11) NOT NULL,
  `pricePaid` double,
  `origin` varchar(255) DEFAULT NULL,
  `originDate` date DEFAULT NULL,
  PRIMARY KEY (`ocid`)
);
