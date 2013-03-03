<?php

//***************************************************************************************
//
// Common Attributes
//
//***************************************************************************************

//
// Exports a precious metal out to a DB file
// If $overwriteFile, will generate a new file
//
function exportPreciousMetal($metalId, $overwriteFile=false) {

  global $COMMON_DB_DIR;
  global $PRECIOUS_METAL_FILE;
  global $PRECIOUS_METAL_HEADER_LINE;

  $metal = getPreciousMetalById($metalId);
  
  $data = array();
  
  $data[] = $metal['name'];
  $data[] = $metal['symbol'];
  $data[] = $metal['unit'];
  $data[] = $metal['conversionFactor'];

  writeDBData($COMMON_DB_DIR, $PRECIOUS_METAL_FILE, $PRECIOUS_METAL_HEADER_LINE, $data, $overwriteFile);

  return $metal;
}


//
// Exports a rating scale out to a DB file
// If $overwriteFile, will generate a new file
//
function exportRatingScale($scaleId, $overwriteFile=false) {

  global $COMMON_DB_DIR;
  global $SHELDON_RATING_SCALE_FILE;
  global $SHELDON_RATING_SCALE_HEADER_LINE;

  $scale = getRatingById($scaleId);
  
  $data = array();
  
  $data[] = $scale['title'];
  $data[] = $scale['category'];
  $data[] = $scale['value'];
  $data[] = $scale['description'];

  writeDBData($COMMON_DB_DIR, $SHELDON_RATING_SCALE_FILE, $SHELDON_RATING_SCALE_HEADER_LINE, $data, $overwriteFile);

  return $scale;
}


//
// Exports a rating category out to a DB file
// If $overwriteFile, will generate a new file
//
function exportRatingCategory($categoryId, $overwriteFile=false) {

  global $COMMON_DB_DIR;
  global $SHELDON_RATING_CATEGORY_FILE;
  global $SHELDON_RATING_CATEGORY_HEADER_LINE;

  $category = getRatingCategoryById($categoryId);
  
  $data = array();
  
  $data[] = $category['title'];
  $data[] = $category['description'];
  $data[] = $category['start'];
  $data[] = $category['end'];
  $data[] = $category['specialOrder'];

  writeDBData($COMMON_DB_DIR, $SHELDON_RATING_CATEGORY_FILE, $SHELDON_RATING_CATEGORY_HEADER_LINE, $data, $overwriteFile);

  return $category;
}


//
// Exports a coin origin out to a DB file
// If $overwriteFile, will generate a new file
//
function exportRatingAgency($agencyId, $overwriteFile=false) {

  global $COMMON_DB_DIR;
  global $RATING_AGENCY_FILE;
  global $RATING_AGENCY_HEADER_LINE;

  $agency = getRatingAgencyById($agencyId);
  
  $data = array();
  
  $data[] = $agency['name'];
  $data[] = $agency['fullName'];

  writeDBData($COMMON_DB_DIR, $RATING_AGENCY_FILE, $RATING_AGENCY_HEADER_LINE, $data, $overwriteFile);

  return $agency;
}


//
// Exports a coin origin out to a DB file
// If $overwriteFile, will generate a new file
//
function exportCoinOrigin($originId, $overwriteFile=false) {

  global $COMMON_DB_DIR;
  global $COIN_ORIGIN_FILE;
  global $COIN_ORIGIN_HEADER_LINE;

  $origin = getCoinOriginById($originId);
  
  $data = array();
  
  $data[] = $origin['name'];

  writeDBData($COMMON_DB_DIR, $COIN_ORIGIN_FILE, $COIN_ORIGIN_HEADER_LINE, $data, $overwriteFile);

  return $origin;
}


//***************************************************************************************
//
// U.S. Coins
//
//***************************************************************************************


//
// Exports a mint out to a DB file
// If $overwriteFile, will generate a new file
//
function exportMint($mintId, $overwriteFile=false) {

  global $US_COIN_DB_DIR;
  global $MINT_FILE;
  global $MINT_HEADER_LINE;

  $mint = getUsMintInfo($mintId);
  
  $data = array();
  
  $data[] = $mint['name'];
  $data[] = $mint['symbol'];
  $data[] = $mint['alwaysPresent'];
  $data[] = $mint['comments'];

  writeDBData($US_COIN_DB_DIR, $MINT_FILE, $MINT_HEADER_LINE, $data, $overwriteFile);

  return $mint;
}


//
// Exports a mint date out to a DB file
// If $overwriteFile, will generate a new file
//
function exportMintDate($mintDateId, $overwriteFile=false) {

  global $US_COIN_DB_DIR;
  global $MINT_DATE_FILE;
  global $MINT_DATE_HEADER_LINE;

  $mintDate = getMintDate($mintDateId);
  
  $data = array();
  
  $data[] = $mintDate['name'];
  $data[] = $mintDate['startYear'];
  $data[] = $mintDate['endYear'];

  writeDBData($US_COIN_DB_DIR, $MINT_DATE_FILE, $MINT_DATE_HEADER_LINE, $data, $overwriteFile);

  return $mintDate;
}


//
// Exports a coin out to a DB file
// If $overwriteFile, will generate a new file
//
function exportCoin($coinId, $overwriteFile=false) {

  global $US_COIN_DB_DIR;
  global $COIN_FILE;
  global $COIN_HEADER_LINE;

  $coin = getUSCoin($coinId);
  
  $data = array();
  
  $data[] = $coin['name'];
  $data[] = $coin['value'];
  $data[] = $coin['startYear'];
  $data[] = $coin['endYear'];
  
  $raw_description = str_replace("\n\n", "^^^^", $coin['description_raw']);
  $raw_description = str_replace('"', '\\"', $raw_description);
  $data[] = $raw_description;

  writeDBData($US_COIN_DB_DIR, $COIN_FILE, $COIN_HEADER_LINE, $data, $overwriteFile);

  return $coin;
}


//
// Exports a coinAttrib out to a DB file
// If $overwriteFile, will generate a new file
//
function exportCoinAttrib($coinAttribId, $overwriteFile=false) {

  global $US_COIN_DB_DIR;
  global $COIN_ATTRIB_FILE;
  global $COIN_ATTRIB_HEADER_LINE;

  $attrib = getCoinAttributeById($coinAttribId);
  
  $data = array();
  
  $data[] = $attrib['name'];
  $data[] = $attrib['coinType'];
  $data[] = $attrib['type'];
  $data[] = $attrib['value'];

  writeDBData($US_COIN_DB_DIR, $COIN_ATTRIB_FILE, $COIN_ATTRIB_HEADER_LINE, $data, $overwriteFile);

  return $attrib;
}


//
// Exports a coinValue out to a DB file
// If $overwriteFile, will generate a new file
//
function exportCoinValue($coinValueId, $overwriteFile=false) {

  global $US_COIN_DB_DIR;
  global $COIN_VALUE_FILE;
  global $COIN_VALUE_HEADER_LINE;

  $value = getCoinDenomination($coinValueId);
  
  $data = array();
  
  $data[] = $value['name'];
  $data[] = $value['value'];
  
  $raw_description = str_replace("\n\n", "^^^^", $value['description_raw']);
  $raw_description = str_replace('"', '\\"', $raw_description);
  $data[] = $raw_description;

  writeDBData($US_COIN_DB_DIR, $COIN_VALUE_FILE, $COIN_VALUE_HEADER_LINE, $data, $overwriteFile);

  return $value;
}


//
// Exports a coinValueAttrib out to a DB file
// If $overwriteFile, will generate a new file
//
function exportCoinValueAttrib($coinValueAttribId, $overwriteFile=false) {

  global $US_COIN_DB_DIR;
  global $COIN_VALUE_ATTRIB_FILE;
  global $COIN_VALUE_ATTRIB_HEADER_LINE;

  $attrib = getCoinValueAttributeById($coinValueAttribId);
  
  $data = array();
  
  $data[] = $attrib['name'];
  $data[] = $attrib['type'];
  $data[] = $attrib['value'];

  writeDBData($US_COIN_DB_DIR, $COIN_VALUE_ATTRIB_FILE, $COIN_VALUE_ATTRIB_HEADER_LINE, $data, $overwriteFile);

  return $attrib;
}


//
// Exports a coinYear out to a DB file
// If $overwriteFile, will generate a new file
//
function exportCoinYear($coinYearId, $overwriteFile=false) {

  global $US_COIN_DB_DIR;
  global $COIN_YEAR_FILE;
  global $COIN_YEAR_HEADER_LINE;

  $year = getCoinYear($coinYearId);
  
  $data = array();
  
  $data[] = $year['coin'];
  $data[] = $year['denomination'];
  $data[] = $year['year'];
  $data[] = $year['yearInfo'];
  $data[] = $year['isGold'];
  $data[] = $year['isSilver'];

  writeDBData($US_COIN_DB_DIR, $COIN_YEAR_FILE, $COIN_YEAR_HEADER_LINE, $data, $overwriteFile);

  return $year;
}

/*
//
// Export precious metal composition for a coin/year/info to a DB file
// If $overwriteFile, will generate a new file
//
function exportCoinMetalComposition($coinMetalCompositionId, $overwriteFile=false) {
  
  
  $composition = getCoinMetalCompositionAll($coinMetalCompositionId);
  
  $data = array();
  
  //exportAll('getCoinMetalCompositions', 'exportCoinMetalComposition', $silent);
}
*/

//***************************************************************************************
//
// Our Coins
//
//***************************************************************************************


//
// Exports one of our coins out to a DB file
// If $overwriteFile, will generate a new file
//
function exportOurCoin($ourCoinId, $overwriteFile=false) {

  global $OUR_COIN_DB_DIR;
  global $OUR_COIN_FILE;
  global $OUR_COIN_HEADER_LINE;

  $coin = getOurMintCoin($ourCoinId);
  
  $data = array();

  $yearSymbol = $coin['year'];  
  if ($coin['symbol'] != '') {
    $yearSymbol .= $coin['symbol'];
  }
  
  $data[] = $yearSymbol;
  $data[] = $coin['name'];

  $ratings = getOurCoinRatings($ourCoinId);
  if (sizeof($ratings) > 0) {
    $data[] = $ratings[0]['category'];    
    $data[] = $ratings[0]['rating'];
    $data[] = $ratings[0]['agency'];
  }
  else {
    $data[] = "";
    $data[] = "";
    $data[] = "";
  }

  if ($coin['isSilver'] == 1) {
    $silver = 'Y';
  }
  else {
    $silver = 'N';  
  }
  $data[] = $silver;
  
  if ($coin['isGold'] == 1) {
    $gold = 'Y';
  }
  else {
    $gold = 'N';  
  }
  $data[] = $gold;
  
  $data[] = $coin['isProof'];
  $data[] = strtoupper($coin['value']);
  $data[] = "";
  $data[] = str_replace("$", "", $coin['pricePaid']);
  $data[] = "";
  $data[] = $coin['origin'];
  $data[] = date("Y-m-d", strtotime($coin['originDate']));  
  $data[] = $coin['yearInfo'];
  $data[] = $coin['coinInfo'];
  
  writeDBData($OUR_COIN_DB_DIR, $OUR_COIN_FILE, $OUR_COIN_HEADER_LINE, $data, $overwriteFile);

  return $coin;
}


//***************************************************************************************
//
// Foreign Coins
//
//***************************************************************************************


//
// Exports a country out to a DB file
// If $overwriteFile, will generate a new file
//
function exportForeignCountry($countryId, $overwriteFile=false) {

  global $FOREIGN_COIN_DB_DIR;
  global $FOREIGN_COUNTRY_FILE;
  global $FOREIGN_COUNTRY_HEADER_LINE;

  $country = getForeignCountryById($countryId);
  
  $data = array();
  
  $data[] = $country['country'];
  $data[] = $country['description'];
  $data[] = $country['possessiveName'];
  $data[] = $country['abbreviation'];

  writeDBData($FOREIGN_COIN_DB_DIR, $FOREIGN_COUNTRY_FILE, $FOREIGN_COUNTRY_HEADER_LINE, $data, $overwriteFile);

  return $country;
}


//
// Exports a foreign coinYear out to a DB file
// If $overwriteFile, will generate a new file
//
function exportForeignCoinYear($coinYearId, $overwriteFile=false) {

  global $FOREIGN_COIN_DB_DIR;
  global $FOREIGN_COINS_DB_DIR;
  global $COIN_YEAR_FILE;
  global $FOREIGN_COIN_YEAR_HEADER_LINE;

  ensure_dir($FOREIGN_COIN_DB_DIR);
  ensure_dir($FOREIGN_COINS_DB_DIR);
  
  $year = getForeignCoinYear($coinYearId);
  
  $abbreviation = $year['abbreviation'];
  $path = "$FOREIGN_COINS_DB_DIR/$abbreviation";
  ensure_dir($path);
  
  $data = array();
  
  $data[] = $year['coin'];
  $data[] = $year['denomination'];
  $data[] = $year['year'];
  $data[] = $year['yearInfo'];
  $data[] = $year['km'];
  $data[] = $year['isGold'];
  $data[] = $year['isSilver'];

  writeDBData($path, $COIN_YEAR_FILE, $FOREIGN_COIN_YEAR_HEADER_LINE, $data, $overwriteFile);

  return $year;
}


//
// Exports a foreign mint out to a DB file
// If $overwriteFile, will generate a new file
//
function exportForeignMint($coinMintId, $overwriteFile=false) {

  global $FOREIGN_COIN_DB_DIR;
  global $FOREIGN_COINS_DB_DIR;
  global $MINT_FILE;
  global $FOREIGN_MINT_HEADER_LINE;

  ensure_dir($FOREIGN_COIN_DB_DIR);
  ensure_dir($FOREIGN_COINS_DB_DIR);
  
  $mint = getForeignMint($coinMintId);
    
  $abbreviation = $mint['abbreviation'];
  $path = "$FOREIGN_COINS_DB_DIR/$abbreviation";
  ensure_dir($path);
  
  $data = array();
  
  $data[] = $mint['name'];
  $data[] = $mint['symbol'];
  $data[] = $mint['alwaysPresent'];
  $data[] = $mint['comments'];
  $data[] = $abbreviation;

  writeDBData($path, $MINT_FILE, $FOREIGN_MINT_HEADER_LINE, $data, $overwriteFile);

  return $mint;
}


//
// Exports a date for a foreign mint out to a DB file
// If $overwriteFile, will generate a new file
//
function exportForeignMintDate($coinMintDateId, $overwriteFile=false) {
print "callign for $coinMintDateId…<br />";
  global $FOREIGN_COIN_DB_DIR;
  global $FOREIGN_COINS_DB_DIR;
  global $MINT_DATE_FILE;
  global $MINT_DATE_HEADER_LINE;

  ensure_dir($FOREIGN_COIN_DB_DIR);
  ensure_dir($FOREIGN_COINS_DB_DIR);
  
  $mint = getForeignMintDate($coinMintDateId);
  print 'ingetsingle<Br /><br />';
  print_r($mint);  
  $abbreviation = $mint['abbreviation'];
  $path = "$FOREIGN_COINS_DB_DIR/$abbreviation";
  ensure_dir($path);
  
  $data = array();
  
  $data[] = $mint['mint'];
  $data[] = $mint['startYear'];
  $data[] = $mint['endYear'];

  writeDBData($path, $MINT_DATE_FILE, $MINT_DATE_HEADER_LINE, $data, $overwriteFile);

  return $mint;
}


//***************************************************************************************
//
// Tracker Searches
//
//***************************************************************************************


//
// Exports a tracker out to a DB file
// If $overwriteFile, will generate a new file
//
function exportTracker($trackerId, $overwriteFile=false) {

  global $TRACKER_DB_DIR;
  global $TRACKER_FILE;
  global $TRACKER_HEADER_LINE;

  $tracker = getTrackerById($trackerId);
  $tracker = convertTracker($tracker);
  
  $data = array();
  
  $data[] = $tracker['name'];
  $data[] = $tracker['description'];
  $data[] = $tracker['value'];
  $data[] = $tracker['coin'];
  $data[] = $tracker['year'];
  
  $data[] = $tracker['yearInfo'];
  
  $data[] = ''; //START YEAR
  $data[] = ''; //END YEAR
  
  $coinMint = $tracker['coinMint'];
  $mint = $tracker['mint'];
  if ($coinMint != null && $coinMint != '') {
    $data[] = $coinMint;
  }
  else if ($mint != null && $mint != '') {
    $data[] = $mint;
  }
  else {
    $data[] = '';
  }

  $data[] = $tracker['coinInfo'];
  
  $data[] = $tracker['minPrice'];
  $data[] = $tracker['maxPrice'];
  $data[] = $tracker['discountPercentage'];
  $data[] = $tracker['premiumPercentage'];
  $data[] = $tracker['auctionEndTime'];
  $data[] = $tracker['sellerRating'];
  $data[] = $tracker['ratingAgency'];
  $data[] = $tracker['gradeCategory'];
  $data[] = $tracker['grade'];
  $data[] = $tracker['isBuyItNow'];
    
  $phrasesToAdd = $tracker['phrasesToAdd'];
  if ($phrasesToAdd == null || sizeof($phrasesToAdd) == 0) {
    $data[] = "";
  }
  else {
    $data[] = implode("##", $phrasesToAdd);
  }

  $phrasesToRemove = $tracker['phrasesToRemove'];
  if ($phrasesToRemove == null || sizeof($phrasesToRemove) == 0) {
    $data[] = "";
  }
  else {
    $data[] = implode("##", $phrasesToRemove);
  }

  $emails = $tracker['emails'];
  if ($emails == null || sizeof($emails) == 0) {
    $data[] = "";
  }
  else {
    $data[] = implode("##", $emails);
  }
  
  writeDBData($TRACKER_DB_DIR, $TRACKER_FILE, $TRACKER_HEADER_LINE, $data, $overwriteFile);

  return $tracker;
}


?>
