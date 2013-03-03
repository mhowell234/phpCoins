USE `ForeignCoinDB`;

DROP TABLE IF EXISTS `MintDate`;

CREATE TABLE `MintDate` (
  `mdid` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `startYear` int(11) NOT NULL,
  `endYear` int(11) NOT NULL,
  PRIMARY KEY (`mdid`)
);
