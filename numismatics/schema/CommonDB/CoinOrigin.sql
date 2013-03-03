USE `CommonDB`;

DROP TABLE IF EXISTS `CoinOrigin`;

CREATE TABLE `CoinOrigin` (
  `coid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`coid`)
);
