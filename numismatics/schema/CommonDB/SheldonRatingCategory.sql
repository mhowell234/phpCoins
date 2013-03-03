CREATE TABLE `SheldonRatingCategory` (
  `srcid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `specialOrder` int(11) NOT NULL,
  PRIMARY KEY (`srcid`)
);