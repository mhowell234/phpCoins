<?php

/***********************
   OUR COIN SQL 
***********************/

// Get our US coins
$OUR_COINS_SQL = "SELECT OC.ocid, C.name AS coinName, CV.name AS value, M.symbol, CY.year, CY.additionalInfo AS yearInfo, MC.additionalInfo AS coinInfo, OC.isSilver, OC.isGold, OC.isProof, OC.pricePaid, OC.originDate, CO.name AS origin FROM OurCoinDB.OurCoin OC, UsCoinDB.MintCoin MC, CommonDB.CoinOrigin CO, UsCoinDB.Mint M, UsCoinDB.CoinYear CY, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE OC.mcid=MC.mcid AND OC.origin=CO.coid AND MC.mid=M.mid AND MC.cyid=CY.cyid AND CY.cid=C.cid AND C.cvid=CV.cvid ORDER BY CV.value DESC, CV.name, CY.year, C.name, CV.name, M.symbol";

// Gets coin $ value by scale for our US coins
$OUR_COIN_VALUE_SQL = "SELECT MCV.year, MCV.value, SRS.title, SRS.description FROM OurCoinDB.OurCoin OC, UsCoinDB.MintCoin MC, UsCoinDB.MintCoinValue MCV, CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE OC.ocid=%d AND OC.mcid=MC.mcid AND MC.mcid=MCV.mcid AND MCV.srsid=SRS.srsid AND SRS.srcid=SRC.srcid ORDER BY MCV.year DESC, SRC.specialOrder, SRS.value";

// Get info for one of our US coins
$OUR_COIN_INFO_SQL = "SELECT OC.ocid, OC.pricePaid, DATE_FORMAT(OC.originDate, '%%m/%%e/%%y') AS originDate, C.name AS coinName, C.cid, CV.cvid, CV.name AS value, M.symbol, CY.year, CO.name AS origin, MC.numberMinted, C.description AS coinDescription, MC.mcid, OC.isSilver, OC.isGold, OC.isProof, CY.additionalInfo AS yearInfo, MC.additionalInfo AS coinInfo FROM OurCoinDB.OurCoin OC, UsCoinDB.MintCoin MC, CommonDB.CoinOrigin CO, UsCoinDB.Mint M, UsCoinDB.CoinYear CY, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE OC.mcid=MC.mcid AND OC.origin=CO.coid AND MC.mid=M.mid AND MC.cyid=CY.cyid AND CY.cid=C.cid AND C.cvid=CV.cvid AND OC.ocid=%d ORDER BY C.name, CV.name, CY.year, M.symbol";

// Gets the rating grades for one of our US coins
$OUR_COIN_RATING_SQL = "SELECT R.ratingDate, SRS.title AS rating, RA.name AS agency, SRC.title AS ratingCategory FROM OurCoinDB.OurCoin OC, OurCoinDB.Rating R, CommonDB.RatingAgency RA, CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE OC.ocid=%d AND OC.ocid=R.ocid AND R.raid=RA.raid AND R.srsid=SRS.srsid AND SRS.srcid=SRC.srcid";

// Gets all photos for one of our US coins
$OUR_US_COIN_PHOTO_SQL = "SELECT * FROM OurCoinDB.OurCoinPhoto OP, OurCoinDB.OurCoin OC WHERE OP.ocid=OC.ocid AND OP.ocid=%d ORDER BY OP.fileName";

// Gets all thumbnail photos for one of our US coins
$OUR_US_COIN_THUMBNAIL_SQL = "SELECT * FROM OurCoinDB.OurCoinThumbnail OCT, OurCoinDB.OurCoin OC WHERE OCT.ocid=OC.ocid AND OCT.ocid=%d ORDER BY OCT.fileName";

$OUR_US_COIN_ADD_SQL = "INSERT INTO OurCoinDB.OurCoin VALUES(NULL, %s, %s, '%s', %s, %s, %s, %s)";

$OUR_US_COIN_RATING_ADD_SQL = "INSERT INTO OurCoinDB.Rating VALUES(NULL, %s, %s, %s, %s)";

?>