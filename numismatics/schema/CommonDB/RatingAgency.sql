USE `CommonDB`;


DROP TABLE IF EXISTS `RatingAgency`;

CREATE TABLE `RatingAgency` (
  `raid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `fullName` text NOT NULL,
  PRIMARY KEY (`raid`)
);
