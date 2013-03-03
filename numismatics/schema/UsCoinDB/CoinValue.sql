USE `UsCoinDB`;

DROP TABLE IF EXISTS `CoinValue`;

CREATE TABLE `CoinValue` (
  `cvid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`cvid`)
);
