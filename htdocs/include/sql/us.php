<?php


/***********************
   US COIN SQL 
***********************/

// Get coin info for a US coin with CoinId
$US_COIN_BY_TYPE_SQL = "SELECT C.name AS name, CV.name AS value, C.startYear, C.endYear, C.description, C.cid FROM UsCoinDB.CoinValue CV, UsCoinDB.Coin C WHERE CV.cvid=C.cvid AND C.cid=%d ORDER BY value DESC";

// Get all coins for a value with CoinValueId
$US_COINS_BY_TYPE_SQL = "SELECT C.startYear, C.endYear, C.cid, C.name AS name, C.description AS coinDescription FROM UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE C.cvid=%d AND C.cvid=CV.cvid ORDER BY C.startYear";

// Gets coin denominations for US coins
$US_COIN_DENOMINATIONS_SQL = "SELECT * FROM UsCoinDB.CoinValue ORDER BY value DESC";

// Gets coin denomination by id
$US_COIN_DENOMINATION_BY_ID_SQL = "SELECT * FROM UsCoinDB.CoinValue WHERE cvid=%d";

// Get all US coins by a denomination
$US_ALL_COINS_BY_TYPE_SQL = "SELECT * FROM UsCoinDB.Coin WHERE cvid=%d ORDER BY startYear";

// Get coin value info
$US_COINS_INFO_FOR_TYPE_SQL = "SELECT CV.name AS typeName, CV.description AS coinDescription, CV.cvid FROM UsCoinDB.CoinValue CV WHERE CV.cvid=%d";

// Gets coin $ value by scale for different US mint coins
$US_COIN_VALUE_SQL = "SELECT MCV.year, MCV.value, SRS.title, SRS.description FROM UsCoinDB.MintCoin MC, UsCoinDB.MintCoinValue MCV, CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE MC.mcid=%d AND MC.mcid=MCV.mcid AND MCV.srsid=SRS.srsid AND SRS.srcid=SRC.srcid ORDER BY MCV.year DESC, SRC.specialOrder, SRS.value";

// Get US coin info for each year minted
$US_COIN_YEAR_SQL = "SELECT MC.cyid, MC.mcid, CY.year, CY.additionalInfo AS yearInfo, M.symbol, MC.additionalInfo AS coinInfo, MC.numberMinted, MC.proofMinted FROM UsCoinDB.CoinYear CY, UsCoinDB.MintCoin MC, UsCoinDB.Mint M WHERE CY.cid=%d AND MC.cyid=CY.cyid AND MC.mid=M.mid ORDER BY CY.year ASC, M.symbol";

// Get US coin info for each year minted
$US_YEARS_SQL = "SELECT CY.cyid, CY.year, CY.additionalInfo AS yearInfo FROM UsCoinDB.CoinYear CY WHERE CY.cid=%d ORDER BY CY.year ASC, CY.additionalInfo";

// Get US coin info for a specific year
$US_YEAR_SQL = "SELECT CY.cyid, CY.year, CY.additionalInfo AS yearInfo FROM UsCoinDB.CoinYear CY WHERE CY.cyid=%d";

// Get US coin info for each year minted
$US_MINT_YEAR_SQL = "SELECT MC.mcid, M.name, M.symbol, MC.additionalInfo AS coinInfo FROM UsCoinDB.CoinYear CY, UsCoinDB.MintCoin MC, UsCoinDB.Mint M WHERE CY.cyid=%d AND CY.cyid=MC.cyid AND MC.mid=M.mid ORDER BY M.symbol";

// Get mint info
$US_MINT_INFO_SQL = "SELECT M.mid, M.name, M.symbol, M.alwaysPresent, M.comments FROM UsCoinDB.Mint M WHERE M.mid=%d";

// Get all mints for a US coin
$US_MINT_SQL = "SELECT distinct M.mid, M.name, M.symbol FROM UsCoinDB.Coin C, UsCoinDB.CoinYear CY, UsCoinDB.MintCoin MC, UsCoinDB.Mint M WHERE C.cid=%d AND C.cid=CY.cid AND CY.cyid=MC.cyid AND MC.mid=M.mid ORDER BY M.symbol";

// Get US mint coin info
$US_MINT_COIN_SQL = "SELECT C.name AS coinName, CV.name AS value, CV.cvid, M.symbol, M.name AS mint, CY.year, CY.additionalInfo AS yearInfo, MC.additionalInfo AS coinInfo, MC.numberMinted, C.description AS coinDescription, C.cid AS cid FROM UsCoinDB.MintCoin MC, UsCoinDB.Mint M, UsCoinDB.CoinYear CY, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE MC.mcid=%d AND MC.mid=M.mid AND MC.cyid=CY.cyid AND CY.cid=C.cid AND C.cvid=CV.cvid ORDER BY C.name, CV.name, CY.year, M.symbol";

// Get all US mint coins
$US_MINT_COINS_ALL_SQL = "SELECT C.name AS coinName, CV.name AS value, CV.cvid, M.symbol, M.name AS mint, CY.year, CY.additionalInfo AS yearInfo, MC.additionalInfo AS coinInfo, MC.numberMinted, MC.proofMinted, C.cid AS cid, MC.mcid AS mcid FROM UsCoinDB.MintCoin MC, UsCoinDB.Mint M, UsCoinDB.CoinYear CY, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE MC.mid=M.mid AND MC.cyid=CY.cyid AND CY.cid=C.cid AND C.cvid=CV.cvid ORDER BY C.name, CV.name, CY.year, M.symbol";

// Gets coin rating scale info for a US coin
$US_COIN_RATING_SCALE_SQL = "SELECT DISTINCT SRS.title FROM UsCoinDB.MintCoinValue MCV, CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC, UsCoinDB.MintCoin MC, UsCoinDB.Coin C, UsCoinDB.CoinYear CY where MCV.mcid=MC.mcid AND CY.cid=C.cid AND C.cid=%d AND MC.cyid=CY.cyid AND MCV.srsid=SRS.srsid AND SRS.srcid=SRC.srcid ORDER BY SRC.specialOrder ASC, SRS.value";

// Gets the year range for a US coin
$US_COIN_YEAR_RANGE_SQL = "SELECT min(startYear) AS start, max(endYear) AS end FROM UsCoinDB.CoinValue CV, UsCoinDB.Coin C WHERE CV.cvid=%d AND CV.cvid=C.cvid";

// Gets the minimum end year to see if this coin is still minted
$US_COIN_MIN_END_YEAR_SQL = "SELECT min(endYear) AS end FROM UsCoinDB.CoinValue CV, UsCoinDB.Coin C WHERE CV.cvid=%d AND CV.cvid=C.cvid";

// Gets all photos for a US coin
$US_COIN_PHOTO_SQL = "SELECT * FROM UsCoinDB.CoinPhoto CP, UsCoinDB.Coin C WHERE CP.cid=C.cid AND CP.cid=%d ORDER BY CP.fileName";

// Gets all thumbnail photos for a US coin
$US_COIN_THUMBNAIL_SQL = "SELECT * FROM UsCoinDB.CoinThumbnail CT, UsCoinDB.Coin C WHERE CT.cid=C.cid AND CT.cid=%d ORDER BY CT.fileName";

// Gets the metals and their percentage of the coin weight
$US_METAL_COMPOSITION_SQL = "SELECT * FROM UsCoinDB.MintCoinAttribute MCA, CommonDB.PreciousMetal PM, UsCoinDB.CoinMetalComposition CMC WHERE MCA.mcid=%d AND MCA.mcaid=CMC.mcaid AND PM.pmid=CMC.pmid";

// Gets coin value attributes
$US_COIN_VALUE_ATTRIBS_ALL_SQL = "SELECT CVA.cvaid, CVA.attribType, CVA.attribValue, CV.name FROM UsCoinDB.CoinValueAttrib CVA, UsCoinDB.CoinValue CV WHERE CVA.cvid=CV.cvid";

// Gets coin value attributes by coin value attrib id
$US_COIN_VALUE_ATTRIB_BY_ID_SQL = "SELECT CVA.cvaid, CVA.attribType, CVA.attribValue, CV.name FROM UsCoinDB.CoinValueAttrib CVA, UsCoinDB.CoinValue CV WHERE CVA.cvaid=%d AND CVA.cvid=CV.cvid";

// Gets coin value attributes by coin value id
$US_COIN_VALUE_ATTRIBS_SQL = "SELECT CVA.cvaid, CVA.attribType, CVA.attribValue, CV.name FROM UsCoinDB.CoinValueAttrib CVA, UsCoinDB.CoinValue CV WHERE CVA.cvid=%d AND CVA.cvid=CV.cvid";

// Gets coin attributes
$US_COIN_ATTRIBS_ALL_SQL = "SELECT CA.caid, CA.attribType, CA.attribValue, C.name, CV.name AS coinType FROM UsCoinDB.CoinAttrib CA, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE CA.cid=C.cid AND C.cvid=CV.cvid";

// Gets coin attributes by coin attrib id
$US_COIN_ATTRIB_BY_ID_SQL = "SELECT CA.caid, CA.attribType, CA.attribValue, C.name, CV.name AS coinType FROM UsCoinDB.CoinAttrib CA, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE CA.caid=%d AND CA.cid=C.cid AND C.cvid=CV.cvid";

// Gets coin attributes by coin id
$US_COIN_ATTRIBS_SQL = "SELECT * FROM UsCoinDB.CoinAttrib CA WHERE CA.cid=%d";

$US_MINTS_SQL = "SELECT * FROM UsCoinDB.Mint M";

$US_MINT_DATES_SQL = "SELECT * FROM UsCoinDB.MintDate MD";

$US_MINT_DATE_BY_ID_SQL = "SELECT MD.mdid, M.name, MD.startYear, MD.endYear FROM UsCoinDB.MintDate MD, UsCoinDB.Mint M WHERE MD.mid=M.mid AND MD.mdid=%d";

$US_COIN_YEARS_SQL = "SELECT CY.cyid, CY.year, CY.additionalInfo AS yearInfo, CY.isGold, CY.isSilver, C.name AS coin, CV.name AS denomination FROM UsCoinDB.CoinYear CY, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE CY.cid=C.cid AND C.cvid=CV.cvid";


$US_COIN_YEAR_BY_ID_SQL = "SELECT CY.cyid, CY.year, CY.additionalInfo AS yearInfo, CY.isGold, CY.isSilver, C.name AS coin, CV.name AS denomination FROM UsCoinDB.CoinYear CY, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE CY.cyid=%d AND CY.cid=C.cid AND C.cvid=CV.cvid";

//$US_ALL_COINS_METAL_COMPOSITION_SQL = "SELECT C.name AS coin, CV.name AS value, CY.year AS year, CY.additionalInfo AS yearInfo, M.name AS mint, M.symbol, MC.additionalInfo AS coinInfo, MCA.weight, PM.name AS metal, CMC.percentage FROM UsCoinDB.CoinValue CV, UsCoinDB.Coin C, UsCoinDB.CoinYear CY, UsCoinDB.Mint M, UsCoinDB.MintCoin MC, UsCoinDB.MintCoinAttribute MCA, CommonDB.PreciousMetal PM, UsCoinDB.CoinMetalComposition CMC WHERE CV.cvid=C.cvid AND C.cid=CY.cid AND CY.cyid=MC.cyid AND M.mid=MC.mid AND MC.mcid=MCA.mcid AND MCA.mcaid=CMC.mcaid AND PM.pmid=CMC.pmid";


$US_ALL_COINS_METAL_COMPOSITION_SQL = "SELECT C.name AS coin, CV.name AS value, CY.year AS year, CY.additionalInfo AS yearInfo, MC.additionalInfo AS coinInfo, MCA.weight, PM.name AS metal, CMC.percentage FROM UsCoinDB.CoinValue CV, UsCoinDB.Coin C, UsCoinDB.CoinYear CY, UsCoinDB.MintCoin MC, UsCoinDB.MintCoinAttribute MCA, CommonDB.PreciousMetal PM, UsCoinDB.CoinMetalComposition CMC WHERE CV.cvid=C.cvid AND C.cid=CY.cid AND CY.cyid=MC.cyid AND MC.mcid=MCA.mcid AND MCA.mcaid=CMC.mcaid AND PM.pmid=CMC.pmid";

?>