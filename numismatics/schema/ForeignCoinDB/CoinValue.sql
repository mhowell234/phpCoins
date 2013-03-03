USE `ForeignCoinDB`;

DROP TABLE IF EXISTS `CoinValue`;

CREATE TABLE `CoinValue` (
  `cvid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` decimal(11,4) NOT NULL,
  `description` text NOT NULL,
  `fcid` int(11) NOT NULL,
  PRIMARY KEY (`cvid`)
);
