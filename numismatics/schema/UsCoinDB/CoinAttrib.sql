USE `UsCoinDB`;

DROP TABLE IF EXISTS `CoinAttrib`;

CREATE TABLE `CoinAttrib` (
  `caid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `attribType` int(11) NOT NULL,
  `attribValue` text NOT NULL,
  PRIMARY KEY (`caid`)
); 

