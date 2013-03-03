USE `ForeignCoinDB`;

DROP TABLE IF EXISTS `MintCoinAttribute`;

CREATE TABLE `MintCoinAttribute` (
  `mcaid` int(11) NOT NULL AUTO_INCREMENT,
  `mcid` int(11) NOT NULL,
  `weight` decimal(10,5),
  PRIMARY KEY (`mcaid`)
);

