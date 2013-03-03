<?php

/***********************
   TRACKER SQL 
***********************/

$US_TRACKER_SQL = "SELECT * FROM TrackerDB.TrackerSearch TS ORDER BY TS.tsid ASC";

$US_TRACKER_BY_ID_SQL = "SELECT * FROM TrackerDB.TrackerSearch TS WHERE TS.tsid=%d";

$US_TRACKER_EMAIL_SQL = "SELECT EA.address FROM TrackerDB.TrackerSearch TS, TrackerDB.EmailAddress EA WHERE TS.tsid=EA.tsid AND TS.tsid=%d";

$US_TRACKER_DELETE_SQL = "DELETE FROM TrackerDB.TrackerSearch WHERE tsid=%d";

$US_TRACKER_ADD_SQL = "INSERT INTO TrackerDB.TrackerSearch VALUES(NULL, '%s', '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '%s', %s, %s, '%s', '%s')";

?>
