<?php

//
// Gets all countries data
//
function getForeignCountriesAll() {
  global $FOREIGN_COUNTRIES_SQL;
  
  $countries = array();
  $result = mysql_query($FOREIGN_COUNTRIES_SQL);

  while($row = mysql_fetch_array($result)) {
    $info = array();
    
    $info['id'] = $row['fcid'];
    $info['country'] = $row['country'];
    $info['possessiveName'] = $row['possessiveName'];
    $info['description'] = $row['description'];
    $info['abbreviation'] = $row['abbreviation'];

    array_push($countries, $info);
  }
  
  return $countries;
}

//
// Gets all countries data
//
function getForeignCountryById($id) {
  global $FOREIGN_COUNTRY_BY_ID_SQL;
  
  $SQL = sprintf($FOREIGN_COUNTRY_BY_ID_SQL, $id); 
  $result = mysql_query($SQL);

  $row = mysql_fetch_array($result);
  $info = array();
    
  $info['country'] = $row['country'];
  $info['possessiveName'] = $row['possessiveName'];
  $info['description'] = $row['description'];
  $info['abbreviation'] = $row['abbreviation'];
  
  return $info;
}


//
// Gets all the countries that we have coin information for
//
function getForeignCountries() {
  global $COUNTRIES_SQL;
  
  $countries = array();
  
  $result = mysql_query($COUNTRIES_SQL);
  while($row = mysql_fetch_array($result)) {
    $info = array();
    
    $info['id'] = $row['fcid'];
    $info['name'] = $row['name'];

    array_push($countries, $info);
  }
  
  return $countries;
}


//
// Get coin types by country (All Great Britain coin types)
//
function getForeignCountryAllCoinTypes($foreignCountryId) {
  global $FOREIGN_COUNTRY_COIN_TYPE_SQL;

  $SQL = sprintf($FOREIGN_COUNTRY_COIN_TYPE_SQL, $foreignCountryId);  
  $result = mysql_query($SQL);
  
  $coinTypes = array();
  
  while($row = mysql_fetch_array($result)) {
    $info = array();
    
    $info['country'] = $row['country'];
    $info['possessiveName'] = $row['possessiveName'];
    $info['cvid'] = $row['cvid'];
    $info['type'] = $row['type'];
    $info['value'] = $row['value'];
    $info['description'] = $row['description'];
    
    array_push($coinTypes, $info);
  }

  return $coinTypes;
}


//
// Get country name from id
//
function getCountryById($foreignCountryId) {
  global $FOREIGN_GET_COUNTRY_SQL;
  $country = "";
  
  
  $SQL = sprintf($FOREIGN_GET_COUNTRY_SQL, $foreignCountryId);
  $result = mysql_query($SQL);
  
  $row = mysql_fetch_array($result);
  $country = $row['country'];
  
  return $country;
}


//
// Get all coins and denominations by country
//
function getForeignAllCoins($foreignCountryId) {
  global $FOREIGN_ALL_COINS_BY_TYPE_SQL;

  $data = array();

  $country = getCountryById($foreignCountryId);
  $data['country'] = $country;
  
  $types = array();
  
  $coinTypes = getForeignCountryAllCoinTypes($foreignCountryId);

  foreach ($coinTypes as &$coinType) {
    $cvid = $coinType['cvid'];
    
    $SQL = sprintf($FOREIGN_ALL_COINS_BY_TYPE_SQL, $cvid);  
    $result = mysql_query($SQL);

    if (mysql_num_rows($result) == 0) {
      continue;
    }
  
    $coinInfo = $coinType;
    while($row = mysql_fetch_array($result)) {
      $info = array();
  
      $name = $row['name'];
      $startYear = $row['startYear'];
      $endYear = $row['endYear'];
      $dateRange = formatDateRange($startYear, $endYear);

      $coinId = $row['cid'];
      
      $description = $row['description'];    
      if ($description != "") {
        $description = formatDescription(getBriefDescription($description));
      }
      
      $info['name'] = $name;
      $info['dateRange'] = $dateRange;
      $info['id'] = $coinId;
      $info['description'] = $description;
      $info['possessiveName'] = $coinType['possessiveName'];
      
      $coinInfo['coins'][] = $info;
    }
    
    array_push($types, $coinInfo);
  }
  
  $data['types'] = $types;
  
  return $data;
}


//
// Get coin info for a general coin (British George VI Half Crown)
//
function getForeignCoin($coinId) {
  global $FOREIGN_COIN_BY_TYPE_SQL;
  
  $SQL = sprintf($FOREIGN_COIN_BY_TYPE_SQL, $coinId);  
  $result = mysql_query($SQL);
  
  $coinInfo = array();
  if (mysql_num_rows($result) == 0) {
    return $coinInfo;
  }
  
  $row = mysql_fetch_array($result);
  
  $id = $row['cid'];
  $name = $row['name'];
  $value = $row['value'];
  $startYear = $row['startYear'];
  $endYear = $row['endYear'];
  $description = $row['description'];
  $country = $row['country'];
  
  $dateRange = formatDateRange($startYear, $endYear);

  if ($description != "") {
    $description = formatDescription($description);
  }

  $coinInfo['id'] = $id;
  $coinInfo['name'] = $name;
  $coinInfo['value'] = $value;
  $coinInfo['dateRange'] = $dateRange;
  $coinInfo['description'] = $description;
  $coinInfo['country'] = $country;
  
  return $coinInfo;
}


//
// Get coins info for a denomination (Info for all British Half Crowns)
//
function getForeignCoins($valueId) {
  global $FOREIGN_COINS_INFO_FOR_TYPE_SQL;
  global $FOREIGN_COINS_BY_TYPE_SQL;

  $coins = array();

  $SQL = sprintf($FOREIGN_COINS_INFO_FOR_TYPE_SQL, $valueId);  
  $result = mysql_query($SQL);

  if (mysql_num_rows($result) == 0) {
    return $coins;
  }
  
  $row = mysql_fetch_array($result);
  $coinName = $row['typeName'];
  $coinCountry = $row['country'];

  $coinDescription = $row['coinDescription'];  
  if ($coinDescription != "") {
    $coinDescription = formatDescription($coinDescription);
  }
  
  $coinsByType = array();
    
  $SQL2 = sprintf($FOREIGN_COINS_BY_TYPE_SQL, $valueId);
  
  $result2 = mysql_query($SQL2);

  if (mysql_num_rows($result2) > 0) {
    while($row = mysql_fetch_array($result2)) {

      $coinId = $row['cid'];
      $name = $row['name'];
      $startYear = $row['startYear'];
      $endYear = $row['endYear'];
      $country = $row['country'];
      
      $dateRange = formatDateRange($startYear, $endYear);
  
      $description = $row['coinDescription'];
      if ($description != "") {
        $description = formatDescription(getBriefDescription($description));
      }

      $info = array();
      $info['name'] = $name;
      $info['id'] = $coinId;
      $info['description'] = $description;
      $info['dateRange'] = $dateRange;
      $info['country'] = $country;
      $info['startYear'] = $startYear;
      $info['endYear'] = $endYear;
      $info['description_raw'] = $row['coinDescription'];
            
      array_push($coinsByType, $info);

    }
  }      

  $coins['name'] = $coinName;
  $coins['description'] = $coinDescription;
  $coins['country'] = $coinCountry;
  
  $coins['list'] = $coinsByType;
    
  return $coins;
}


//
// Get info for a specific coin (1937 George VI Half Crown - British)
//
function getForeignMintCoin($mintCoinId) {
  global $FOREIGN_MINT_COIN_SQL;
  
  $SQL = sprintf($FOREIGN_MINT_COIN_SQL, $mintCoinId);
  
  $result = mysql_query($SQL);
  
  $row = mysql_fetch_array($result);
  
  $coinId = $row['cid'];
  $coinValueId = $row['cvid'];
  $coinTitle = getCoinTitle($row, 1);
  $coinName = $row['coinName'];
  $coinValue = $row['value'];
  $numberMinted = $row['numberMinted'];
  $coinDescription = $row['coinDescription'];
  if ($coinDescription != "") {
    $coinDescription = formatDescription($coinDescription);
  }
  $km = $row['km'];
  
  $mintCoinInfo = array();
  $mintCoinInfo['id'] = $coinId;
  $mintCoinInfo['cvid'] = $coinValueId;
  $mintCoinInfo['title'] = $coinTitle;
  $mintCoinInfo['name'] = $coinName;
  $mintCoinInfo['value'] = $coinValue;
  $mintCoinInfo['description'] = $coinDescription;
  $mintCoinInfo['numberMinted'] = $numberMinted;
  $mintCoinInfo['km'] = $km;
  $mintCoinInfo['year'] = $row['year'];
  $mintCoinInfo['symbol'] = $row['symbol'];
  
  return $mintCoinInfo;  
}


//
// Get the price values for grades for a specific coin
//
function getForeignCoinValuesAndTypes($mintCoinId) {
  global $FOREIGN_COIN_VALUE_SQL;
  
  $SQL = sprintf($FOREIGN_COIN_VALUE_SQL, $mintCoinId);

  $results = mysql_query($SQL);

  $coinValues = array();
  $coinValueTypes = array();

  while ($row = mysql_fetch_array($results)) {
    $info = array();
  
    $info['year'] = $row['year'];
    $info['title'] = $row['title'];
    $info['description'] = $row['description'];
    $info['value'] = $row['value'];

    array_push($coinValues, $info);
    array_push($coinValueTypes, $row['title']);
  }

  $coinValueWrapper = array();
  $coinValueWrapper['values'] = $coinValues;
  $coinValueWrapper['types'] = $coinValueTypes;
  
  return $coinValueWrapper;
}


//
// Gets into for each year a foreign coin was made.
//
function getForeignCoinYears() {
  global $FOREIGN_COIN_YEARS_SQL_ALL;
  
  $SQL = $FOREIGN_COIN_YEARS_SQL_ALL;
  $result = mysql_query($SQL);
  
  print $SQL;
  $years = array();
  if (mysql_num_rows($result) == 0) {
    return $years;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['cyid'];
    $coin = $row['coin'];
    $denomination = $row['denomination'];
    $year = $row['year'];
    $yearInfo = $row['yearInfo'];
    $isGold = $row['isGold'];
    $isSilver = $row['isSilver'];
    $km = $row['km'];
    $abbreviation = $row['abbreviation'];
    
    $info['id'] = $id;
    $info['coin'] = $coin;
    $info['denomination'] = $denomination;
    $info['year'] = $year;
    $info['yearInfo'] = $yearInfo;
    $info['isGold'] = $isGold;
    $info['isSilver'] = $isSilver;
    $info['km'] = $km;
    $info['abbreviation'] = $abbreviation;

    array_push($years, $info);
  }
  
  return $years;

}


//
// Get all foreign coin year data
//
function getForeignCoinYear($id) {
  global $FOREIGN_COIN_YEAR_BY_ID_SQL;
  
  $SQL = sprintf($FOREIGN_COIN_YEAR_BY_ID_SQL, $id);

  $result = mysql_query($SQL);

  $year = array();

  $row = mysql_fetch_array($result);

  $id = $row['cyid']; 
  $coin = $row['coin'];
  $denomination = $row['denomination'];
  $year = $row['year'];
  $yearInfo = $row['yearInfo'];
  $isGold = $row['isGold'];
  $isSilver = $row['isSilver'];
  $km = $row['km'];
  $abbreviation = $row['abbreviation'];
    
  $info['id'] = $id;
  $info['coin'] = $coin;
  $info['denomination'] = $denomination;
  $info['year'] = $year;
  $info['yearInfo'] = $yearInfo;
  $info['isGold'] = $isGold;
  $info['isSilver'] = $isSilver;
  $info['km'] = $km;
  $info['abbreviation'] = $abbreviation;
    
  return $info;
}


//
// Get data for all foreign mint
//
function getForeignMints() {
  global $FOREIGN_MINTS_SQL;
  
  $SQL = $FOREIGN_MINTS_SQL;
  $result = mysql_query($SQL);
  
  print $SQL;
  $mints = array();
  if (mysql_num_rows($result) == 0) {
    return $mints;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['mid']; 
    $name = $row['name'];
    $symbol = $row['symbol'];
    $alwaysPresent = $row['alwaysPresent'];
    $comments = $row['comments'];
    $abbreviation = $row['abbreviation'];
    
    $info['id'] = $id;
    $info['name'] = $name;
    $info['symbol'] = $symbol;
    $info['alwaysPresent'] = $alwaysPresent;  
    $info['comments'] = $comments;
    $info['abbreviation'] = $abbreviation;

    array_push($mints, $info);
  }
  
  return $mints;

}


//
// Get data for a foreign mint
//
function getForeignMint($id) {
  global $FOREIGN_MINT_BY_ID_SQL;
  
  $SQL = sprintf($FOREIGN_MINT_BY_ID_SQL, $id);

  $result = mysql_query($SQL);

  $year = array();

  $row = mysql_fetch_array($result);

  $id = $row['mid']; 
  $name = $row['name'];
  $symbol = $row['symbol'];
  $alwaysPresent = $row['alwaysPresent'];
  $comments = $row['comments'];
  $abbreviation = $row['abbreviation'];
    
  $info['id'] = $id;
  $info['name'] = $name;
  $info['symbol'] = $symbol;
  $info['alwaysPresent'] = $alwaysPresent;  
  $info['comments'] = $comments;
  $info['abbreviation'] = $abbreviation;
    
  return $info;
}


//
// Get dates for all foreign mints
//
function getForeignMintDates() {
  global $FOREIGN_MINT_DATES_SQL;
  
  $SQL = $FOREIGN_MINT_DATES_SQL;
  $result = mysql_query($SQL);
  
  print $SQL;
  $mints = array();
  if (mysql_num_rows($result) == 0) {
    return $mints;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['mdid']; 
    $mid = $row['mid'];
    $mint = $row['mint'];
    $startYear = $row['startYear'];
    $endYear = $row['endYear'];
    $abbreviation = $row['abbreviation'];
    
    $info['id'] = $id;
    $info['mid'] = $mid;
    $info['mint'] = $mint;
    $info['startYear'] = $startYear;  
    $info['endYear'] = $endYear;
    $info['abbreviation'] = $abbreviation;

    array_push($mints, $info);
  }
  
  return $mints;

}


//
// Get dates for a foreign mint
//
function getForeignMintDate($id) {
  global $FOREIGN_MINT_DATE_SQL;
  
  $SQL = sprintf($FOREIGN_MINT_DATE_SQL, $id);

  $result = mysql_query($SQL);

  $info = array();

  $row = mysql_fetch_array($result);

  $id = $row['mdid']; 
  $mid = $row['mid'];
  $mint = $row['mint'];
  $startYear = $row['startYear'];
  $endYear = $row['endYear'];
  $abbreviation = $row['abbreviation'];
    
  $info['id'] = $id;
  $info['mid'] = $mid;
  $info['mint'] = $mint;
  $info['startYear'] = $startYear;
  $info['endYear'] = $endYear;  
  $info['abbreviation'] = $abbreviation;
    
  return $info;
}


//
// Get coins years info for a mint coin type (Variety 1 - KM# 856 - George VI Half Crown - British)
//
function getForeignCoinYearsInfo($typeId) {
  global $FOREIGN_COIN_YEAR_SQL;
  
  $SQL = sprintf($FOREIGN_COIN_YEAR_SQL, $typeId);

  $result = mysql_query($SQL);

  $coins = array();

  while($row = mysql_fetch_array($result)) {

    $yearInfo = "default";
    if ($row['yearInfo'] !== "") {
      $yearInfo = $row['yearInfo'];
    }
    
    $coinData = array();

    $coinData['title'] = getCoinTitle($row);
    $coinData['id'] = $row['mcid'];
    $coinData['numberMinted'] = $row['numberMinted'];
    $coinData['proofMinted'] = $row['proofMinted'];
    $coinData['km'] = $row['km'];
    
    $coinValueWrapper = getForeignCoinValuesAndTypes($coinData['id']);
    $coinData['coinValues'] = $coinValueWrapper['values'];
  
    $coins[$yearInfo][] = $coinData;  
  }

  return $coins;
}


//
// Gets the grade categories for a general coin (All grades for George VI Half Crowns - British)
//
function getForeignCoinYearRatingScale($coinId) {
  global $FOREIGN_COIN_RATING_SCALE_SQL;
  
  $SQL = sprintf($FOREIGN_COIN_RATING_SCALE_SQL, $coinId);
  
  $result = mysql_query($SQL);
  
  $coinValueTypes = array();
    
  while ($row = mysql_fetch_array($result)) {
    $coinValueTypes[] = $row['title']; 
  }

  return $coinValueTypes;
}


//
// Get images for a coin type
//
function getForeignImages($coinId) {
  global $FOREIGN_COIN_PHOTO_SQL;
  
  $SQL = sprintf($FOREIGN_COIN_PHOTO_SQL, $coinId);

  $results = mysql_query($SQL);
  $photos = array();

  while ($row = mysql_fetch_array($results)) {

    $info = array();
    $info['file'] = $row['fileName'];
    $info['caption'] = $row['caption'];
    array_push($photos, $info);
  }

  return $photos;
}


//
// Get images for a coin type
//
function getForeignThumbnails($coinId) {
  global $FOREIGN_COIN_THUMBNAIL_SQL;
  
  $SQL = sprintf($FOREIGN_COIN_THUMBNAIL_SQL, $coinId);

  $results = mysql_query($SQL);
  $thumbs = array();

  while ($row = mysql_fetch_array($results)) {

    $info = array();
    $info['file'] = $row['fileName'];
    $info['caption'] = $row['caption'];
    array_push($thumbs, $info);
  }

  return $thumbs;
}


//
// Get metal composition and value for a specific coin
//
function getForeignCoinComposition($mintCoinId) {
  global $FOREIGN_METAL_COMPOSITION_SQL;
  
  $metals = array();
  
  $SQL = sprintf($FOREIGN_METAL_COMPOSITION_SQL, $mintCoinId);
  
  $results = mysql_query($SQL);
  
  while ($row = mysql_fetch_array($results)) {
    $info = array();
    
    $weight = $row['weight'];
    $metal = $row['name'];
    $unit = $row['unit'];
    $pricePerUnit = $row['pricePerUnit'];
    $pricePerGram = $row['pricePerGram'];
    $percentage = $row['percentage'];
    
    $info['metal'] = $metal;
    $info['totalWeight'] = $weight;
    $info['unit'] = "$metal/$unit";
    $info['price'] = $pricePerUnit;
    $info['percentage'] = $percentage;
    
    
    // Calculate value
    // percentage * weight
    $percentage = $percentage / 100;
    $weightPerMetal = $weight * $percentage;
    $info['weight'] = $weightPerMetal;
     
    $info['value'] = $weightPerMetal * $pricePerGram;
     
    array_push($metals, $info);
  }
  
  return $metals;
}


//
// Get all coin metal composition values
//
function getForeignCoinMetalCompositionAll() {
  global $FOREIGN_ALL_COINS_METAL_COMPOSITION_SQL;
  
  $coins = array();
  $SQL = $FOREIGN_ALL_COINS_METAL_COMPOSITION_SQL;
  
  $results = mysql_query($SQL);

  while ($row = mysql_fetch_array($results)) {
  
    $coin = $row['coin'];
    $value = $row['value'];
    $year = $row['year'];
    $yearInfo = $row['yearInfo'];
    $mint = $row['mint'];
    $symbol = $row['symbol'];
    $coinInfo = $row['coinInfo'];
    $weight = $row['weight'];
    $metal = $row['metal'];
    $percentage = $row['percentage'];
    $abbreviation = $row['abbreviation'];
    
    $info = array();
    $info['metal'] = $metal;
    $info['weight'] = $weight;
    $info['percentage'] = $percentage;
    $info['abbreviation'] = $abbreviation;
    
    $coins[$coin][$value][$year][$yearInfo][$coinInfo][] = $info;
  }
  
  return $coins;
}


//
//
//
// Get info for all specific foreign coins
//
function getForeignMintCoinsAll() {
  global $FOREIGN_MINT_COINS_ALL_SQL;

  $coins = array();
  
  $SQL = $FOREIGN_MINT_COINS_ALL_SQL;  
  $result = mysql_query($SQL);
  
  while ($row = mysql_fetch_array($result)) {
  
    $coinId = $row['cid'];
    $mintCoinId = $row['mcid'];
    $coinValueId = $row['cvid'];
    $coinName = $row['coinName'];
    $coinValue = $row['value'];
    $numberMinted = $row['numberMinted'];
    $proofMinted = $row['proofMinted'];
    $symbol = $row['symbol'];
    $year = $row['year'];
    $yearInfo = $row['yearInfo'];
    $coinInfo = $row['coinInfo'];
    $km = $row['km'];
    $abbreviation = $row['abbreviation'];
    
    $mintCoinInfo = array();
    $mintCoinInfo['id'] = $coinId;
    $mintCoinInfo['mcid'] = $mintCoinId;
    $mintCoinInfo['cvid'] = $coinValueId;
    $mintCoinInfo['yearInfo'] = $yearInfo;
    $mintCoinInfo['coinInfo'] = $coinInfo;
    $mintCoinInfo['name'] = $coinName;
    $mintCoinInfo['value'] = $coinValue;  
    $mintCoinInfo['year'] = $year;
    $mintCoinInfo['symbol'] = $symbol;  
    $mintCoinInfo['numberMinted'] = $numberMinted;
    $mintCoinInfo['proofMinted'] = $proofMinted;
    $mintCoinInfo['km'] = $km;
    $mintCoinInfo['abbreviation'] = $abbreviation;
    
    $coins[] = $mintCoinInfo;
  }
  
  return $coins;  
}
?>
