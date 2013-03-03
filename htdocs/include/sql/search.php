<?php

/***********************
   SEARCH SQL 
***********************/

$SEARCH_OUR_COIN_SQL = "SELECT S.uid, MATCH(S.searchText) AGAINST('%s') AS score, STK.tableName, STK.keyField FROM SearchDB.Searchable S JOIN SearchDB.SearchableTableKey STK ON S.stkid = STK.stkid AND STK.tableName='OurCoinDB.OurCoin' WHERE MATCH(S.searchText) AGAINST('%s' IN BOOLEAN MODE) ORDER BY score DESC";

$SEARCH_ALL_SQL = "SELECT S.uid, MATCH(S.searchText) AGAINST('%s') AS score, STK.tableName, STK.keyField FROM SearchDB.Searchable S JOIN SearchDB.SearchableTableKey STK ON S.stkid = STK.stkid WHERE MATCH(S.searchText) AGAINST('%s' IN BOOLEAN MODE) ORDER BY score DESC";

$US_COIN_SEARCH_SQL = "SELECT C.cid, CV.cvid, C.name, C.startYear, C.endYear, C.description, CV.name AS denomination FROM UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE C.cvid=CV.cvid AND C.cid=%d";

$FOREIGN_COIN_SEARCH_SQL = "SELECT C.cid, CV.cvid, C.name, C.startYear, C.endYear, C.description, CV.name AS denomination, FC.fcid, FC.name AS country FROM ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV, ForeignCoinDB.ForeignCountry FC WHERE C.cvid=CV.cvid AND CV.fcid=FC.fcid AND C.cid=%d";

$OUR_COIN_SEARCH_SQL = "SELECT OC.ocid, OC.pricePaid, DATE_FORMAT(OC.originDate, '%%m/%%e/%%y') AS originDate, C.name AS name, CV.cvid, CV.name AS denomination, M.symbol, CY.year, MC.additionalInfo as yearInfo, CO.name AS origin, MC.numberMinted, C.description AS description, MC.mcid FROM OurCoinDB.OurCoin OC, UsCoinDB.MintCoin MC, CommonDB.CoinOrigin CO, UsCoinDB.Mint M, UsCoinDB.CoinYear CY, UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE OC.mcid=MC.mcid AND OC.origin=CO.coid AND MC.mid=M.mid AND MC.cyid=CY.cyid AND CY.cid=C.cid AND C.cvid=CV.cvid AND OC.ocid=%d";


?>