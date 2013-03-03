USE `TrackerDB`;

DROP TABLE IF EXISTS `TrackerSearch`;

CREATE TABLE `TrackerSearch` (
  `tsid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `cvid` int(11),
  `cid` int(11),
  `cyid` int(11),
  `cyidStart` int(11),
  `cyidEnd` int(11),
  `mcid` int(11),
  `mid` int(11),
  `minPrice` decimal(10,2),
  `maxPrice` decimal(10,2),
  `discountPercentage` int(11),
  `premiumPercentage` int(11),
  `auctionEndTime` int(11),
  `gradeCategory` int(11),
  `grade` int(11),
  `ratingAgency` varchar(32),
  `sellerRating` int(11),
  `isBuyItNow` boolean,
  `phraseToAdd` text,
  `phraseToRemove` text,  
  PRIMARY KEY (`tsid`)
); 
