USE `ForeignCoinDB`;

DROP TABLE IF EXISTS `CoinPhoto`;

CREATE TABLE `CoinPhoto` (
  `cpid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `fileName` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cpid`)
);
