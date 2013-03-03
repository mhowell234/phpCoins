<?php

// Gets coin denominations
$COIN_VALUE_SQL = "SELECT CV.name AS denomination, CV.cvid FROM UsCoinDB.CoinValue CV";


// Gets coins for each denomination
$COIN_SQL = "SELECT C.name, CV.name AS denomination, C.cid FROM UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE C.cvid=CV.cvid";


// Gets coin info for each year a coin was made
$COIN_YEAR_SQL = "SELECT CY.year, C.name, CV.name AS denomination, CY.additionalInfo AS cyinfo, CY.cyid FROM UsCoinDB.Coin C, UsCoinDB.CoinValue CV, UsCoinDB.CoinYear CY WHERE C.cvid=CV.cvid AND C.cid=CY.cid";


// Gets Mint Info
$MINT_SQL = "SELECT M.name, M.symbol, MD.startYear, MD.endYear, M.mid FROM UsCoinDB.Mint M, UsCoinDB.MintDate MD WHERE M.mid=MD.mid";


// Gets all specific coin/mint/year combinations
$MINT_COIN_SQL = "SELECT M.symbol, CY.year, C.name, CV.name AS denomination, MC.additionalInfo AS mcinfo, CY.additionalInfo AS cyinfo, MC.mcid FROM UsCoinDB.Coin C, UsCoinDB.CoinValue CV, UsCoinDB.CoinYear CY, UsCoinDB.Mint M, UsCoinDB.MintCoin MC WHERE C.cvid=CV.cvid AND C.cid=CY.cid AND CY.cyid=MC.cyid AND M.mid=MC.mid";


// Gets all the rating agencies
$RATING_AGENCY_SQL = "SELECT RA.name, RA.fullName, RA.raid FROM CommonDB.RatingAgency RA";

// Gets all rating categories
$RATING_CATEGORY_SQL = "SELECT SRC.srcid, SRC.title, SRC.description, SRC.start, SRC.end, SRC.specialOrder FROM CommonDB.SheldonRatingCategory SRC";

// Gets all rating values
$RATING_VALUE_SQL = "SELECT SRS.srsid, SRS.title, SRS.value, SRS.description, SRC.title AS category FROM CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE SRS.srcid=SRC.srcid";


// Gets values for a specific coin/mint/year combination
$MINT_COIN_VALUE_SQL = "SELECT MCV.year, MCV.mcvid, MC.mcid, SRC.value, SRS.description AS name, SRS.title AS shortName, SRC.title FROM UsCoinDB.MintCoinValue MCV, UsCoinDB.MintCoin MC, CommonDB.SheldonRatingScale SRC, CommonDB.SheldonRatingCategory SRS WHERE MCV.mcid=MC.mcid AND MCV.srsid=SRC.srsid AND SRC.srcid=SRS.srcid";


// Gets all the grading rating categories
$COIN_GRADE_CATEGORY_SQL = "SELECT SRC.title AS category, SRC.srcid FROM CommonDB.SheldonRatingCategory SRC";


// Gets all the grading ratings
$COIN_GRADE_SQL = "SELECT SRS.title AS grade, SRS.srcid, SRS.value, SRS.srsid FROM CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE SRS.srcid=SRC.srcid";


// Gets Foreign Countries
$FOREIGN_COUNTRY_SQL = "SELECT FC.name, FC.abbreviation, FC.fcid FROM ForeignCoinDB.ForeignCountry FC";


// Gets Our Coins
$OUR_COIN_SQL = "SELECT M.symbol, CY.year, C.name, CV.name AS denomination, MC.additionalInfo AS mcinfo, CY.additionalInfo AS cyinfo, OC.ocid, OC.pricePaid, OC.originDate, MC.mcid FROM UsCoinDB.Coin C, UsCoinDB.CoinValue CV, UsCoinDB.CoinYear CY, UsCoinDB.Mint M, UsCoinDB.MintCoin MC, OurCoinDB.OurCoin OC WHERE C.cvid=CV.cvid AND C.cid=CY.cid AND CY.cyid=MC.cyid AND M.mid=MC.mid AND MC.mcid=OC.mcid";


// Gets Precious Metal values
$PRECIOUS_METAL_SQL = "SELECT PM.name, PM.pmid FROM CommonDB.PreciousMetal PM";


// Gets foreign coins
$FOREIGN_COIN_SQL = "SELECT C.name, CV.name AS denomination, C.cid FROM ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV WHERE C.cvid= CV.cvid";

?>