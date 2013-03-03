USE `UsCoinDB`;

DROP TABLE IF EXISTS `CoinYear`;

CREATE TABLE `CoinYear` (
  `cyid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `additionalInfo` varchar(255) DEFAULT NULL,
  `isGold` tinyint(4) NOT NULL,
  `isSilver` tinyint(4) NOT NULL,
  PRIMARY KEY (`cyid`, `additionalInfo`)
);
