USE `OurCoinDB`;

DROP TABLE IF EXISTS `OurCoin`;

CREATE TABLE `OurCoin` (
  `ocid` int(11) NOT NULL AUTO_INCREMENT,
  `mcid` int(11) NOT NULL,
  `pricePaid` double,
  `origin` varchar(255) DEFAULT NULL,
  `originDate` date DEFAULT NULL,
  `isSilver` int(11) DEFAULT 0,
  `isGold` int(11) DEFAULT 0,
  `isProof` int(11) DEFAULT 0,
  PRIMARY KEY (`ocid`)
);
