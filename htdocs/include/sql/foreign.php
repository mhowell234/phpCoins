<?php

/***********************
   FOREIGN COIN SQL 
***********************/

// Get coin info for a Foreign coin with CoinId
$FOREIGN_COIN_BY_TYPE_SQL = "SELECT C.name AS name, CV.name AS value, C.startYear, C.endYear, C.description, C.cid, FC.name AS country FROM ForeignCoinDB.CoinValue CV, ForeignCoinDB.Coin C, ForeignCoinDB.ForeignCountry FC WHERE CV.cvid=C.cvid AND C.cid=%d AND CV.fcid=FC.fcid ORDER BY value DESC";

// Get all foreign coins for a value with CoinValueId
$FOREIGN_COINS_BY_TYPE_SQL = "SELECT C.startYear, C.endYear, C.cid, C.name AS name, C.description AS coinDescription, FC.name AS country FROM ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV, ForeignCoinDB.ForeignCountry FC WHERE C.cvid=%d AND C.cvid=CV.cvid AND CV.fcid=FC.fcid ORDER BY C.startYear";

// Get all foreign coins by a denomination
$FOREIGN_ALL_COINS_BY_TYPE_SQL = "SELECT * FROM ForeignCoinDB.Coin WHERE cvid=%d ORDER BY startYear";

// Get country name from id
$FOREIGN_GET_COUNTRY_SQL = "SELECT name AS country FROM ForeignCoinDB.ForeignCountry WHERE fcid=%d";

// Get foreign coin value info
$FOREIGN_COINS_INFO_FOR_TYPE_SQL = "SELECT CV.name AS typeName, CV.description AS coinDescription, FC.name AS country FROM ForeignCoinDB.CoinValue CV, ForeignCoinDB.ForeignCountry FC WHERE CV.cvid=%d AND CV.fcid=FC.fcid";

// Gets all photos for a Foreign coin
$FOREIGN_COIN_PHOTO_SQL = "SELECT * FROM ForeignCoinDB.CoinPhoto CP, ForeignCoinDB.Coin C WHERE CP.cid=C.cid AND CP.cid=%d ORDER BY CP.fileName";

// Gets all thumbnail photos for a Foreign coin
$FOREIGN_COIN_THUMBNAIL_SQL = "SELECT * FROM ForeignCoinDB.CoinThumbnail CT, ForeignCoinDB.Coin C WHERE CT.cid=C.cid AND CT.cid=%d ORDER BY CT.fileName";

// Get all foreign countries that have coins  
$COUNTRIES_SQL = "SELECT DISTINCT FC.fcid, FC.name FROM ForeignCoinDB.ForeignCountry FC, ForeignCoinDB.CoinValue CV, ForeignCoinDB.Coin C WHERE FC.fcid=CV.fcid AND CV.cvid=C.cvid ORDER BY FC.name";

// Get Foreign coin info for each year minted for each mint
$FOREIGN_COIN_YEAR_SQL = "SELECT MC.cyid, MC.mcid, CY.year, CY.km, CY.additionalInfo AS yearInfo, M.symbol, MC.additionalInfo AS coinInfo, MC.numberMinted, MC.proofMinted FROM ForeignCoinDB.CoinYear CY, ForeignCoinDB.MintCoin MC, ForeignCoinDB.Mint M WHERE CY.cid=%d AND MC.cyid=CY.cyid AND MC.mid=M.mid ORDER BY CY.year ASC, M.symbol";

// Get Foreign coin info for each year that a coin was minted
$FOREIGN_COIN_YEARS_SQL_ALL = "SELECT CY.cyid, CY.year, CY.additionalInfo AS yearInfo, CY.isGold, CY.isSilver, CY.km, C.name AS coin, CV.name AS denomination, FC.name AS country, FC.abbreviation FROM ForeignCoinDB.CoinYear CY, ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV, ForeignCoinDB.ForeignCountry FC WHERE CY.cid=C.cid AND C.cvid=CV.cvid AND FC.fcid=CV.fcid";

$FOREIGN_COIN_YEAR_BY_ID_SQL = "SELECT CY.cyid, CY.year, CY.additionalInfo AS yearInfo, CY.isGold, CY.isSilver, CY.km, C.name AS coin, CV.name AS denomination, FC.name AS country, FC.abbreviation FROM ForeignCoinDB.CoinYear CY, ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV, ForeignCoinDB.ForeignCountry FC WHERE CY.cid=C.cid AND C.cvid=CV.cvid AND FC.fcid=CV.fcid AND CY.cyid=%d";

$FOREIGN_MINTS_SQL = "SELECT M.mid, M.name, M.symbol, M.alwaysPresent, M.comments, FC.abbreviation FROM ForeignCoinDB.Mint M, ForeignCoinDB.ForeignCountry FC WHERE M.fcid=FC.fcid";

$FOREIGN_MINT_BY_ID_SQL = "SELECT M.mid, M.name, M.symbol, M.alwaysPresent, M.comments, FC.abbreviation FROM ForeignCoinDB.Mint M, ForeignCoinDB.ForeignCountry FC WHERE M.fcid=FC.fcid AND M.mid=%d";

$FOREIGN_MINT_DATES_SQL = "SELECT MD.mdid, MD.mid, M.name AS mint, MD.startYear, MD.endYear, FC.abbreviation FROM ForeignCoinDB.MintDate MD, ForeignCoinDB.Mint M, ForeignCoinDB.ForeignCountry FC WHERE MD.mid=M.mid AND M.fcid=FC.fcid";

$FOREIGN_MINT_DATE_SQL = "SELECT MD.mdid, MD.mid, M.name AS mint, MD.startYear, MD.endYear, FC.abbreviation FROM ForeignCoinDB.MintDate MD, ForeignCoinDB.Mint M, ForeignCoinDB.ForeignCountry FC WHERE MD.mid=M.mid AND M.fcid=FC.fcid AND MD.mdid=%d";

// Gets coin rating scale info for a foreign coin
$FOREIGN_COIN_RATING_SCALE_SQL = "SELECT DISTINCT SRS.title FROM ForeignCoinDB.MintCoinValue MCV, CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC, ForeignCoinDB.MintCoin MC, ForeignCoinDB.Coin C, ForeignCoinDB.CoinYear CY where MCV.mcid=MC.mcid AND CY.cid=C.cid AND C.cid=%d AND MC.cyid=CY.cyid AND MCV.srsid=SRS.srsid AND SRS.srcid=SRC.srcid ORDER BY SRC.specialOrder ASC, SRS.value";
  
// Gets coin $ value by scale for different foreign mint coins
$FOREIGN_COIN_VALUE_SQL = "SELECT MCV.year, MCV.value, SRS.title, SRS.description FROM ForeignCoinDB.MintCoin MC, ForeignCoinDB.MintCoinValue MCV, CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE MC.mcid=%d AND MC.mcid=MCV.mcid AND MCV.srsid=SRS.srsid AND SRS.srcid=SRC.srcid ORDER BY MCV.year DESC, SRC.specialOrder, SRS.value";
  
// Get foreign mint coin info
$FOREIGN_MINT_COIN_SQL = "SELECT C.name AS coinName, CV.name AS value, CV.cvid, M.symbol, CY.year, CY.km, CY.additionalInfo AS yearInfo, MC.numberMinted, C.description AS coinDescription, C.cid AS cid, MC.additionalInfo AS coinInfo FROM ForeignCoinDB.MintCoin MC, ForeignCoinDB.Mint M, ForeignCoinDB.CoinYear CY, ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV WHERE MC.mcid=%d AND MC.mid=M.mid AND MC.cyid=CY.cyid AND CY.cid=C.cid AND C.cvid=CV.cvid ORDER BY C.name, CV.name, CY.year, M.symbol";

// Gets the metals and their percentage of the coin weight for foreign coins
$FOREIGN_METAL_COMPOSITION_SQL = "SELECT * FROM ForeignCoinDB.MintCoinAttribute MCA, CommonDB.PreciousMetal PM, ForeignCoinDB.CoinMetalComposition CMC WHERE MCA.mcid=%d AND MCA.mcaid=CMC.mcaid AND PM.pmid=CMC.pmid";

// Gets coin denominations for a specific country (All Great Britain coins types)
$FOREIGN_COUNTRY_COIN_TYPE_SQL = "SELECT FC.name AS country, FC.possessiveName AS possessiveName, CV.cvid, CV.name as type, CV.description, CV.value FROM ForeignCoinDB.ForeignCountry FC, ForeignCoinDB.CoinValue CV WHERE FC.fcid=%d AND FC.fcid=CV.fcid ORDER BY value DESC";

$FOREIGN_COUNTRIES_SQL = "SELECT FC.fcid, FC.name AS country, FC.possessiveName, FC.description, FC.abbreviation FROM ForeignCoinDB.ForeignCountry FC";

$FOREIGN_COUNTRY_BY_ID_SQL = "SELECT FC.name AS country, FC.possessiveName, FC.description, FC.abbreviation FROM ForeignCoinDB.ForeignCountry FC WHERE FC.fcid=%d";

$FOREIGN_ALL_COINS_METAL_COMPOSITION_SQL = "SELECT C.name AS coin, CV.name AS value, CY.year AS year, CY.additionalInfo AS yearInfo, MC.additionalInfo AS coinInfo, MCA.weight, PM.name AS metal, CMC.percentage, FC.abbreviation FROM ForeignCoinDB.CoinValue CV, ForeignCoinDB.Coin C, ForeignCoinDB.CoinYear CY, ForeignCoinDB.MintCoin MC, ForeignCoinDB.MintCoinAttribute MCA, CommonDB.PreciousMetal PM, ForeignCoinDB.CoinMetalComposition CMC, ForeignCoinDB.ForeignCountry FC WHERE CV.cvid=C.cvid AND C.cid=CY.cid AND CY.cyid=MC.cyid AND MC.mcid=MCA.mcid AND MCA.mcaid=CMC.mcaid AND PM.pmid=CMC.pmid AND CV.fcid=FC.fcid";

// Get all foreign mint coins
$FOREIGN_MINT_COINS_ALL_SQL = "SELECT C.name AS coinName, CV.name AS value, CV.cvid, M.symbol, M.name AS mint, CY.year, CY.additionalInfo AS yearInfo, CY.km, MC.additionalInfo AS coinInfo, MC.numberMinted, MC.proofMinted, C.cid AS cid, MC.mcid AS mcid, FC.abbreviation FROM ForeignCoinDB.MintCoin MC, ForeignCoinDB.Mint M, ForeignCoinDB.CoinYear CY, ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV, ForeignCoinDB.ForeignCountry FC WHERE MC.mid=M.mid AND MC.cyid=CY.cyid AND CY.cid=C.cid AND C.cvid=CV.cvid AND CV.fcid=FC.fcid ORDER BY C.name, CV.name, CY.year, M.symbol";

?>