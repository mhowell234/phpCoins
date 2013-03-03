USE `UsCoinDB`;

DROP TABLE IF EXISTS `CoinValueAttrib`;

CREATE TABLE `CoinValueAttrib` (
  `cvaid` int(11) NOT NULL AUTO_INCREMENT,
  `cvid` int(11) NOT NULL,
  `attribType` int(11) NOT NULL,
  `attribValue` text NOT NULL,
  PRIMARY KEY (`cvaid`)
); 

