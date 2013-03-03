USE `SearchDB`;

DROP TABLE IF EXISTS `Searchable`;

CREATE TABLE `Searchable` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `searchText` text,
  `stkid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`sid`)
); 

ALTER TABLE Searchable ENGINE = MYISAM;
ALTER TABLE Searchable ADD FULLTEXT(searchText);

