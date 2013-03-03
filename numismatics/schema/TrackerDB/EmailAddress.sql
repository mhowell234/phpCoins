USE `TrackerDB`;

DROP TABLE IF EXISTS `EmailAddress`;

CREATE TABLE `EmailAddress` (
  `eaid` int(11) NOT NULL AUTO_INCREMENT,
  `tsid` int(11) NOT NULL,
  `address` varchar(255),
  PRIMARY KEY (`eaid`)
); 
