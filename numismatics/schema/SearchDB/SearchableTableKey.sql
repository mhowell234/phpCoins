USE `SearchDB`;

DROP TABLE IF EXISTS `SearchableTableKey`;

CREATE TABLE `SearchableTableKey` (
  `stkid` int(11) NOT NULL AUTO_INCREMENT,
  `tableName` varchar(255),
  `keyField` varchar(255),
  PRIMARY KEY (`stkid`)
); 

INSERT INTO SearchableTableKey VALUES(1, "UsCoinDB.MintCoin", "mcid");
INSERT INTO SearchableTableKey VALUES(2, "ForeignCoinDB.MintCoin", "mcid");
INSERT INTO SearchableTableKey VALUES(3, "OurCoinDB.OurCoin", "ocid");
