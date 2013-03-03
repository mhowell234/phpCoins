USE `UsCoinDB`;

DROP TABLE IF EXISTS `MintCoinValue`;

CREATE TABLE `MintCoinValue` (
  `mcvid` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(11) NOT NULL,
  `mcid` int(11) NOT NULL,
  `srsid` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  PRIMARY KEY (`mcvid`)
);
