CREATE TABLE `SheldonRatingScale` (
  `srsid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `srcid` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`srsid`)
);