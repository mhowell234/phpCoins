USE `UsCoinDB`;

DROP TABLE IF EXISTS `Mint`;

CREATE TABLE `Mint` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(4) NOT NULL,
  `alwaysPresent` tinyint(4) NOT NULL,
  `comments` varchar(255) NOT NULL,
  PRIMARY KEY (`mid`)
);
