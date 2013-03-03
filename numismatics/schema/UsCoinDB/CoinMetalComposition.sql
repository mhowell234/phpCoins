USE `UsCoinDB`;

DROP TABLE IF EXISTS `CoinMetalComposition`;

CREATE TABLE `CoinMetalComposition` (
  `cmcid` int(11) NOT NULL AUTO_INCREMENT,
  `mcaid` int(11) NOT NULL,
  `pmid` int(11) NOT NULL,
  `percentage` decimal(10,5),
  PRIMARY KEY (`cmcid`)
);