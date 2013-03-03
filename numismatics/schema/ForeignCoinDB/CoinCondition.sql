USE `ForeignCoinDB`;

DROP TABLE IF EXISTS `CoinCondition`;

CREATE TABLE `CoinCondition` (
  `ccid` int(11) NOT NULL AUTO_INCREMENT,
  `value` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `shortName` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `specialOrder` int(11) NOT NULL,
  PRIMARY KEY (`ccid`)
);
