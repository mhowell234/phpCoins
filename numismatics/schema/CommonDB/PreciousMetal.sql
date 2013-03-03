USE `CommonDB`;

DROP TABLE IF EXISTS `PreciousMetal`;

CREATE TABLE `PreciousMetal` (
  `pmid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(5),
  `unit` varchar(10),
  `conversionFactor` decimal(18,10),
  `pricePerUnit` decimal(18,10),
  `pricePerGram` decimal(18,10),
  PRIMARY KEY (`pmid`)
);
