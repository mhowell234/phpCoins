USE `ForeignCoinDB`;

DROP TABLE IF EXISTS `MintCoin`;

CREATE TABLE `MintCoin` (
  `mcid` int(11) NOT NULL AUTO_INCREMENT,
  `cyid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `additionalInfo` varchar(255),
  `numberMinted` int(11),
  `proofMinted` int(11),
  PRIMARY KEY (`mcid`)
);
