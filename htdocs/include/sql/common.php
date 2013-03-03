<?php

/***********************
   COMMON SQL 
***********************/

$RATING_CATEGORIES_SQL = "SELECT SRC.srcid, SRC.title, SRC.description FROM CommonDB.SheldonRatingCategory AS SRC ORDER BY SRC.specialOrder ASC, SRC.start DESC";

$RATING_VALUES_BY_CATEGORY_SQL = "SELECT SRS.srsid, SRS.title FROM CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE SRS.srcid=SRC.srcid AND SRC.srcid=%d ORDER BY SRS.value ASC";

$RATING_CATEGORY_BY_ID_SQL = "SELECT SRC.srcid, SRC.title, SRC.description, SRC.start, SRC.end, SRC.specialOrder FROM CommonDB.SheldonRatingCategory AS SRC WHERE SRC.srcid=%d";

$RATING_SCALES_SQL = "SELECT SRS.srsid, SRS.title, SRS.value, SRS.description, SRC.title AS category FROM CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE SRS.srcid=SRC.srcid";
 
$RATING_BY_ID_SQL = "SELECT SRS.srsid, SRS.title, SRS.value, SRS.description, SRC.title AS category FROM CommonDB.SheldonRatingScale SRS, CommonDB.SheldonRatingCategory SRC WHERE SRS.srsid=%d AND SRS.srcid=SRC.srcid";

$RATING_AGENCIES_SQL = "SELECT * FROM CommonDB.RatingAgency RA";

$RATING_AGENCY_BY_ID_SQL = "SELECT * FROM CommonDB.RatingAgency RA WHERE RA.raid=%d";

$COIN_ORIGINS_SQL = "SELECT CO.coid, CO.name FROM CommonDB.CoinOrigin CO";

$COIN_ORIGIN_BY_ID_SQL = "SELECT * FROM CommonDB.CoinOrigin CO WHERE CO.coid=%d";

$PRECIOUS_METALS_SQL = "SELECT * FROM CommonDB.PreciousMetal PM";

$PRECIOUS_METAL_BY_ID_SQL = "SELECT PM.pmid, PM.name, PM.symbol, PM.unit, PM.conversionFactor FROM CommonDB.PreciousMetal PM WHERE PM.pmid=%d";

?>