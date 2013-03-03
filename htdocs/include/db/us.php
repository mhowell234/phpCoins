<?php


//
// Get coin info for a general coin (Washington Quarter, Roosevelt Dime, etc.)
//
function getUSCoin($coinId) {
  global $US_COIN_BY_TYPE_SQL;
  
  $SQL = sprintf($US_COIN_BY_TYPE_SQL, $coinId);  
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

  $dateRange = formatDateRange($startYear, $endYear);

  if ($description != "") {
    $description_formatted = formatDescription($description);
  }

  $coinInfo['id'] = $id;
  $coinInfo['name'] = $name;
  $coinInfo['value'] = $value;
  $coinInfo['dateRange'] = $dateRange;
  $coinInfo['description'] = $description_formatted;
  $coinInfo['description_raw'] = $description;
  $coinInfo['startYear'] = $startYear;
  $coinInfo['endYear'] = $endYear;
  
  return $coinInfo;
}


//
// Get coins info for a denomination (Info for all types of Quarters)
//
function getUSCoins($valueId) {
  global $US_COINS_INFO_FOR_TYPE_SQL;
  global $US_COINS_BY_TYPE_SQL;

  $coins = array();

  $SQL = sprintf($US_COINS_INFO_FOR_TYPE_SQL, $valueId);  
  $result = mysql_query($SQL);

  if (mysql_num_rows($result) == 0) {
    return $coins;
  }
  
  $row = mysql_fetch_array($result);
  $coinName = $row['typeName'];
  $coinDescription = $row['coinDescription'];
  if ($coinDescription != "") {
    $coinDescription = formatDescription($coinDescription);
  }
  
  $coinsByType = array();
    
  $SQL2 = sprintf($US_COINS_BY_TYPE_SQL, $valueId);
  $result2 = mysql_query($SQL2);

  if (mysql_num_rows($result2) > 0) {
    while($row = mysql_fetch_array($result2)) {

      $coinId = $row['cid'];
      $name = $row['name'];
      $startYear = $row['startYear'];
      $endYear = $row['endYear'];

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
      
      array_push($coinsByType, $info);

    }
  }      

  $coins['name'] = $coinName;
  $coins['description'] = $coinDescription;
  $coins['list'] = $coinsByType;
    
  return $coins;
}


//
// Get all US coin denominations (Penny, Nickel, Dime…)
//
function getCoinDenominations() {
  global $US_COIN_DENOMINATIONS_SQL;
  global $US_COIN_YEAR_RANGE_SQL;
  global $US_COIN_MIN_END_YEAR_SQL;
  
  $result = mysql_query($US_COIN_DENOMINATIONS_SQL);

  $coins = array();
  
  while($row = mysql_fetch_array($result)) {
    $cvid = $row['cvid'];
    $name = $row['name'];
    $value = $row['value'];
    
    $SQL = sprintf($US_COIN_YEAR_RANGE_SQL, $cvid);
     
    $yearResult = mysql_query($SQL);

    $yearRow = mysql_fetch_array($yearResult);
    $startYear = $yearRow['start'];
    $endYear = $yearRow['end'];
    
    $SQL2 = sprintf($US_COIN_MIN_END_YEAR_SQL, $cvid);
    
    $minYearResult = mysql_query($SQL2);
    $minYearRow = mysql_fetch_array($minYearResult);
    $minEndYear = $minYearRow['end'];
  
    if ($minEndYear == 0) {
      $endYear = 0;
    }  
  
    $dateRange = formatDateRange($startYear, $endYear);
	
	$description = $row['description'];
	if ($description != "") {
	  $description_formatted = formatDescription(getBriefDescription($description));
	}
  
    $info = array();
    $info['id'] = $cvid;
    $info['name'] = $name;
    $info['value'] = $value;
    $info['dateRange'] = $dateRange;
    $info['description'] = $description_formatted;
    $info['description_raw'] = $description;
    
    array_push($coins, $info);
  }

  return $coins;
}



//
// Get US coin denomination by id (Penny, Nickel, Dime…)
//
function getCoinDenomination($cvid) {
  global $US_COIN_DENOMINATION_BY_ID_SQL;
  global $US_COIN_YEAR_RANGE_SQL;
  global $US_COIN_MIN_END_YEAR_SQL;
  
  $SQL = sprintf($US_COIN_DENOMINATION_BY_ID_SQL, $cvid);
  $result = mysql_query($SQL);

  $row = mysql_fetch_array($result);
  $cvid = $row['cvid'];
  $name = $row['name'];
  $value = $row['value'];
    
  $SQL2 = sprintf($US_COIN_YEAR_RANGE_SQL, $cvid);
  $yearResult = mysql_query($SQL2);

  $yearRow = mysql_fetch_array($yearResult);
  $startYear = $yearRow['start'];
  $endYear = $yearRow['end'];
    
  $SQL3 = sprintf($US_COIN_MIN_END_YEAR_SQL, $cvid);  
  $minYearResult = mysql_query($SQL3);
  
  $minYearRow = mysql_fetch_array($minYearResult);
  $minEndYear = $minYearRow['end'];
  
  if ($minEndYear == 0) {
    $endYear = 0;
  }  
  
  $dateRange = formatDateRange($startYear, $endYear);
	
  $description = $row['description'];
  if ($description != "") {
    $description_formatted = formatDescription(getBriefDescription($description));
  }
  
  $info = array();
  $info['id'] = $cvid;
  $info['name'] = $name;
  $info['value'] = $value;
  $info['dateRange'] = $dateRange;
  $info['startYear'] = $startYear;
  $info['endYear'] = $endYear;
  $info['description'] = $description_formatted;
  $info['description_raw'] = $description;
    
  return $info;
}


//
// Get all coins by denomination type (All Quarters)
//
function getAllCoinsByType() {
  global $US_COIN_DENOMINATIONS_SQL;
  global $US_ALL_COINS_BY_TYPE_SQL;

  $result = mysql_query($US_COIN_DENOMINATIONS_SQL);

  $coins = array();
  
  while($row = mysql_fetch_array($result)) {
    $cvid = $row['cvid'];
    $name = $row['name'];
    
    $SQL = sprintf($US_ALL_COINS_BY_TYPE_SQL, $cvid);
    
    $coinResult = mysql_query($SQL);

    if (mysql_num_rows($coinResult) == 0) {
      continue;
    }

    $coinsByType = array();
    
    while($coinRow = mysql_fetch_array($coinResult)) {
      $coinName = $coinRow['name'];
      $startYear = $coinRow['startYear'];
      $endYear = $coinRow['endYear'];
      $coinId = $coinRow['cid'];
      $description = $coinRow['description'];
      
      $dateRange = formatDateRange($startYear, $endYear);

      $fullDescription = $description;
      if ($description != "") {
        $description = formatDescription(getBriefDescription($description));
      }

      $info = array();
      $info['name'] = $coinName;
      $info['id'] = $coinId;
      $info['description'] = $description;
      $info['dateRange'] = $dateRange;
      $info['startYear'] = $startYear;
      $info['endYear'] = $endYear;
      $info['fullDescription'] = $fullDescription;
      
      array_push($coinsByType, $info);
    }
 
    $coinList = array();
    $coinList['id'] = $cvid;
    $coinList['name'] = $name;
    $coinList['list'] = $coinsByType;
    
    array_push($coins, $coinList);
  }

  return $coins;
}


//
// Get coins years info for a coin type (Silver Clad - Washington Quarter)
//
function getUSCoinYearsInfo($typeId) {
  global $US_COIN_YEAR_SQL;
  
  $SQL = sprintf($US_COIN_YEAR_SQL, $typeId);

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
    $coinData['year'] = $row['year'];
    $coinData['yearInfo'] = $row['yearInfo'];
    
    $coinValueWrapper = getCoinValuesAndTypes($coinData['id']);
    $coinData['coinValues'] = $coinValueWrapper['values'];
  
    $coins[$yearInfo][] = $coinData;  
  }

  return $coins;
}


//
// Get coin year info
//
function getUSCoinYear($yearId) {
  global $US_YEAR_SQL;
  
  $SQL = sprintf($US_YEAR_SQL, $yearId);
  $result = mysql_query($SQL);

  $coin = array();

  $row = mysql_fetch_array($result);

  $yearInfo = "";
  if ($row['yearInfo'] !== "") {
    $yearInfo = $row['yearInfo'];
  }
    
  $coin['id'] = $row['cyid'];
  $coin['year'] = $row['year'];
  $coin['info'] = $yearInfo;
    
  return $coin;
}


//
// Get just coin's years info for a coin type (Silver Clad - Washington Quarter)
//
function getUSCoinYears($typeId) {
  global $US_YEARS_SQL;
  
  $SQL = sprintf($US_YEARS_SQL, $typeId);

  $result = mysql_query($SQL);

  $coins = array();

  while($row = mysql_fetch_array($result)) {

    $yearInfo = "";
    if ($row['yearInfo'] !== "") {
      $yearInfo = $row['yearInfo'];
    }
    
    $coinData = array();

    $coinData['id'] = $row['cyid'];
    $coinData['year'] = $row['year'];
    $coinData['info'] = $yearInfo;
    
    array_push($coins, $coinData);
  }

  return $coins;
}


//
// Get mints available for a coin made in a year
//
function getUSCoinMintsForYear($yearId) {
  global $US_MINT_YEAR_SQL;
  
  $SQL = sprintf($US_MINT_YEAR_SQL, $yearId);

  $result = mysql_query($SQL);

  $mints = array();

  while($row = mysql_fetch_array($result)) {

    $coinInfo = "";
    if ($row['coinInfo'] !== "") {
      $coinInfo = $row['coinInfo'];
    }
    
    $mintData = array();

    $mintData['id'] = $row['mcid'];
    $mintData['name'] = $row['name'];
    $mintData['symbol'] = $row['symbol'];
    $mintData['info'] = $coinInfo;
    
    array_push($mints, $mintData);
  }

  return $mints;
}


//
// Get all mints for a coin
//
function getUsMintInfo($mid) {
  global $US_MINT_INFO_SQL;
  
  $SQL = sprintf($US_MINT_INFO_SQL, $mid);

  $result = mysql_query($SQL);

  $mint = array();

  $row = mysql_fetch_array($result);

  $mint['id'] = $row['mid'];
  $mint['name'] = $row['name'];
  $mint['symbol'] = $row['symbol'];
  $mint['alwaysPresent'] = $row['alwaysPresent'];
  $mint['comments'] = $row['comments'];
  
  return $mint;
}


//
// Get all mints for a coin
//
function getUsMintsForCoin($coinId) {
  global $US_MINT_SQL;
  
  $SQL = sprintf($US_MINT_SQL, $coinId);

  $result = mysql_query($SQL);

  $mints = array();

  while($row = mysql_fetch_array($result)) {

    $mintData = array();

    $mintData['id'] = $row['mid'];
    $mintData['name'] = $row['name'];
    $mintData['symbol'] = $row['symbol'];
    
    array_push($mints, $mintData);
  }

  return $mints;  
}


//
// Gets the grade categories for a general coin (All grades for Washington Quarters)
//
function getUsCoinYearRatingScale($coinId) {
  global $US_COIN_RATING_SCALE_SQL;
  
  $SQL = sprintf($US_COIN_RATING_SCALE_SQL, $coinId);
  
  $result = mysql_query($SQL);
  
  $coinValueTypes = array();
    
  while ($row = mysql_fetch_array($result)) {
    $coinValueTypes[] = $row['title']; 
  }

  return $coinValueTypes;
}


//
// Get info for a specific coin (1964P Washington Quarter)
//
function getMintCoin($mintCoinId) {
  global $US_MINT_COIN_SQL;
  
  $SQL = sprintf($US_MINT_COIN_SQL, $mintCoinId);
  
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
  
  $mintCoinInfo = array();
  $mintCoinInfo['id'] = $coinId;
  $mintCoinInfo['cvid'] = $coinValueId;
  $mintCoinInfo['title'] = $coinTitle;
  $mintCoinInfo['coinInfo'] = $row['coinInfo'];
  $mintCoinInfo['name'] = $coinName;
  $mintCoinInfo['value'] = $coinValue;
  $mintCoinInfo['nameUrl'] = $nameUrl;
  $mintCoinInfo['valueUrl'] = $valueUrl;
  $mintCoinInfo['year'] = $row['year'];
  $mintCoinInfo['symbol'] = $row['symbol'];
  $mintCoinInfo['mint'] = $row['mint'];
  
  $mintCoinInfo['description'] = $coinDescription;
  $mintCoinInfo['numberMinted'] = $numberMinted;
  
  return $mintCoinInfo;  
}


//
//
//
// Get info for all specific coins
//
function getMintCoinsAll() {
  global $US_MINT_COINS_ALL_SQL;

  $coins = array();
  
  $SQL = $US_MINT_COINS_ALL_SQL;  
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
    
    $coins[] = $mintCoinInfo;
  }
  
  return $coins;  
}


//
// Get the price values for grades for a specific coin
//
function getCoinValuesAndTypes($mintCoinId) {
  global $US_COIN_VALUE_SQL;
  
  $SQL = sprintf($US_COIN_VALUE_SQL, $mintCoinId);

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
// Get images for a coin type
//
function getImages($coinId) {
  global $US_COIN_PHOTO_SQL;
  
  $SQL = sprintf($US_COIN_PHOTO_SQL, $coinId);
  
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
// Get thumbnail images for a coin type
//
function getThumbnails($coinId) {
  global $US_COIN_THUMBNAIL_SQL;
  
  $SQL = sprintf($US_COIN_THUMBNAIL_SQL, $coinId);
  
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
function getCoinComposition($mintCoinId) {
  global $US_METAL_COMPOSITION_SQL;
  
  $metals = array();
  
  $SQL = sprintf($US_METAL_COMPOSITION_SQL, $mintCoinId);
  
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
function getCoinMetalCompositionAll() {
  global $US_ALL_COINS_METAL_COMPOSITION_SQL;
  
  $coins = array();
  $SQL = $US_ALL_COINS_METAL_COMPOSITION_SQL;
  
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
    
    $info = array();
    $info['metal'] = $metal;
    $info['weight'] = $weight;
    $info['percentage'] = $percentage;
    
    $coins[$coin][$value][$year][$yearInfo][$coinInfo][] = $info;
  }
  
  return $coins;
}


//
// Gets coin value attributes
//
function getCoinValueAttributesAll() {
  global $US_COIN_VALUE_ATTRIBS_ALL_SQL;

  $attribs = array();

  $SQL = $US_COIN_VALUE_ATTRIBS_ALL_SQL;
    
  $results = mysql_query($SQL);
  
  while ($row = mysql_fetch_array($results)) {
  
    $info = array();
    
    $info['type'] = $row['attribType'];
    $info['value'] = $row['attribValue'];
    $info['name'] = $row['name'];
    $info['id'] = $row['cvaid'];
    
    array_push($attribs, $info);
  }
  
  return $attribs;
}


//
// Gets coin value attributes
//
function getCoinValueAttributeById($id) {
  global $US_COIN_VALUE_ATTRIB_BY_ID_SQL;

  $attribs = array();

  $SQL = sprintf($US_COIN_VALUE_ATTRIB_BY_ID_SQL, $id);
    
  $results = mysql_query($SQL);
  
  $row = mysql_fetch_array($results);
  
  $info = array();
    
  $info['type'] = $row['attribType'];
  $info['value'] = $row['attribValue'];
  $info['name'] = $row['name'];
  $info['id'] = $row['cvaid'];
      
  return $info;
}


//
// Gets coin value attributes
//
function getCoinValueAttributes($id) {
  global $US_COIN_VALUE_ATTRIBS_SQL;

  $attribs = array();

  $SQL = sprintf($US_COIN_VALUE_ATTRIBS_SQL, $id);
    
  $results = mysql_query($SQL);
  
  while ($row = mysql_fetch_array($results)) {
  
    $info = array();
    
    $info['type'] = $row['attribType'];
    $info['value'] = $row['attribValue'];
    $info['name'] = $row['name'];
    $info['id'] = $row['cvaid'];
    
    array_push($attribs, $info);
  }
  
  return $attribs;
}


//
// Gets coin attributes
//
function getCoinAttributesAll() {
  global $US_COIN_ATTRIBS_ALL_SQL;

  $attribs = array();

  $SQL = $US_COIN_ATTRIBS_ALL_SQL;
    
  $results = mysql_query($SQL);
  
  while ($row = mysql_fetch_array($results)) {
  
    $info = array();
    
    $info['type'] = $row['attribType'];
    $info['value'] = $row['attribValue'];
    $info['name'] = $row['name'];
    $info['coinType']  = $row['coinType'];
    $info['id'] = $row['caid'];
    
    array_push($attribs, $info);
  }
  
  return $attribs;
}


//
// Gets coin attributes by id
//
function getCoinAttributeById($id) {
  global $US_COIN_ATTRIB_BY_ID_SQL;

  $attribs = array();

  $SQL = sprintf($US_COIN_ATTRIB_BY_ID_SQL, $id);
    
  $results = mysql_query($SQL);
  
  $row = mysql_fetch_array($results);
  
  $info = array();
    
  $info['type'] = $row['attribType'];
  $info['value'] = $row['attribValue'];
  $info['name'] = $row['name'];
  $info['coinType']  = $row['coinType'];
  $info['id'] = $row['caid'];
      
  return $info;
}


//
// Gets coin attributes
//
function getCoinAttributes($id) {
  global $US_COIN_ATTRIBS_SQL;

  $attribs = array();

  $SQL = sprintf($US_COIN_ATTRIBS_SQL, $id);
    
  $results = mysql_query($SQL);
  
  while ($row = mysql_fetch_array($results)) {
  
    $info = array();
    
    $info['type'] = $row['attribType'];
    $info['value'] = $row['attribValue'];
    
    array_push($attribs, $info);
  }
  
  return $attribs;
}


//
// Get the worth values of a coin/coin type for
// various inputs.
// Defaults to all coins from lowest to highest value
// INPUTS are:
//    cvid, cid, cyid, mcid
//    mid, srsid, srcid
//    sortOrder
//
function getCoinWorth($attribs) {

  $attribs = convertCoinWorthInput($attribs);

  $select = array();
  $from = array();
  $where = array();
  $order = array();

  $select[] = "SRS.value AS rating";
  $select[] = "MCV.srsid";
  $select[] = "MCV.value";
  $select[] = "MCV.mcvid";
  $select[] = "MC.mcid";
  $select[] = "M.symbol";
  $select[] = "M.mid";

  $from[] = "CommonDB.SheldonRatingScale SRS";
  $from[] = "UsCoinDB.MintCoinValue MCV";
  $from[] = "UsCoinDB.MintCoin MC";
  $from[] = "UsCoinDB.Mint M";

  $where[] = "SRS.srsid=MCV.srsid";
  $where[] = "MCV.mcid=MC.mcid";
  $where[] = "MC.mid=M.mid";

  $sortOrder = 'ASC';
  if (isset($attribs['sortOrder']) && $attribs['sortOrder'] != '') {
    $sortOrder = $attribs['sortOrder'];
  }
  $order[] = sprintf("MCV.value %s", $sortOrder);

  if (isset($attribs['mcid']) && $attribs['mcid'] != '') {
    $mcid = $attribs['mcid'];
    
    $where[] = sprintf("MC.mcid=%d", $mcid);
  }
  else if (isset($attribs['cyid']) && $attribs['cyid'] != '') {
    $cyid = $attribs['cyid'];
    
    $select[] = "CY.cyid";
    $select[] = "CY.year";
    
    $from[] = "UsCoinDB.CoinYear CY";
    
    $where[] = "MC.cyid=CY.cyid";
    $where[] = sprintf("CY.cyid=%d", $cyid);
  }
  else if (isset($attribs['cid']) && $attribs['cid'] != '') {
    $cid = $attribs['cid'];

    $select[] = "CY.cyid";
    $select[] = "CY.year";
    $select[] = "C.cid";
    
    $from[] = "UsCoinDB.CoinYear CY";
    $from[] = "UsCoinDB.Coin C";
    
    $where[] = "MC.cyid=CY.cyid";
    $where[] = "CY.cid=C.cid";
    $where[] = sprintf("C.cid=%d", $cid);
  }
  else if (isset($attribs['cvid']) && $attribs['cvid'] != '') {
    $cvid = $attribs['cvid'];

    $select[] = "CY.cyid";
    $select[] = "CY.year";
    $select[] = "C.cid";
    $select[] = "CV.cvid";
    
    $from[] = "UsCoinDB.CoinYear CY";
    $from[] = "UsCoinDB.Coin C";
    $from[] = "UsCoinDB.CoinValue CV";
    
    $where[] = "MC.cyid=CY.cyid";
    $where[] = "CY.cid=C.cid";
    $where[] = "C.cvid=CV.cvid";
    $where[] = sprintf("CV.cvid=%d", $cvid);    
  }
  
  if (isset($attribs['mid']) && $attribs['mid'] != '') {
    $mid = $attribs['mid'];
    
    $where[] = sprintf("M.mid=%d", $mid);
  }
    
  if (isset($attribs['srsid']) && $attribs['srsid'] != '') {
    $srsid = $attribs['srsid'];
    
    $where[] = sprintf("SRS.srsid=%d", $srsid);
  }
  else if (isset($attribs['srcid']) && $attribs['srcid'] != '') {  
    $srcid = $attribs['srcid'];

    $from[] = "CommonDB.SheldonRatingCategory SRC";
    $where[] = "SRS.srcid=SRC.srcid";
    $where[] = sprintf("SRC.srcid=%d", $srcid);
  }
  
  $SQL = buildSQLSelect($select, $from, $where, $order);
  
  $values = array();
  
  $results = mysql_query($SQL);
  
  while ($row = mysql_fetch_array($results)) {
    $values[] = $row['value'];
  }
  
  return $values;

}


//
// Convert coin worth input values to valid input
//
function convertCoinWorthInput($attribs) {
  
  $new_attribs = array();
  
  if (isset($attribs['cvid']) && $attribs['cvid'] != '') {
    $new_attribs['cvid'] = $attribs['cvid'];
  }
  else if (isset($attribs['coinValue']) && $attribs['coinValue'] != '') {
    // Convert to cvid
    $coinValue = $attribs['coinValue'];
  }

  if (isset($attribs['cid']) && $attribs['cid'] != '') {
    $new_attribs['cid'] = $attribs['cid'];
  }
  else if (isset($attribs['coin']) && $attribs['coin'] != '') {
    // Convert to cid
    $coin = $attribs['coin'];
  }

  if (isset($attribs['cyid']) && $attribs['cyid'] != '') {
    $new_attribs['cyid'] = $attribs['cyid'];
  }
  else if (isset($attribs['coinYear']) && $attribs['coinYear'] != '') {
    // Convert to cyid
    $coinYear = $attribs['coinYear'];
  }

  if (isset($attribs['mcid']) && $attribs['mcid'] != '') {
    $new_attribs['mcid'] = $attribs['mcid'];
  }

  if (isset($attribs['mid']) && $attribs['mid'] != '') {
    $new_attribs['mid'] = $attribs['mid'];
  }
  else if (isset($attribs['mint']) && $attribs['mint'] != '') {
    // Convert to mid
    $mint = $attribs['mint'];
  }

  if (isset($attribs['sortOrder']) && $attribs['sortOrder'] != '') {
    $new_attribs['sortOrder'] = $attribs['sortOrder'];
  }


  if (isset($attribs['srcid']) && $attribs['srcid'] != '') {
    $new_attribs['srcid'] = $attribs['srcid'];
  }
  else if (isset($attribs['gradeCategory']) && $attribs['gradeCategory'] != '') {
    // Convert to srcid
    $gradeCategory = $attribs['gradeCategory'];
    $new_attribs['srcid'] = $attribs['gradeCategory'];
  }
  
  if (isset($attribs['srsid']) && $attribs['srsid'] != '') {
    $new_attribs['srsid'] = $attribs['srsid'];
  }
  else if (isset($attribs['grade']) && $attribs['grade'] != '') {
    // Convert to srsid
    $grade = $attribs['grade'];
    $new_attribs['srsid'] = $attribs['grade'];
  }
  
  return $new_attribs;
}


//
// Gets the min worth for a coin based on search params
//
function getMinCoinWorth($attribs) {

  $worth = getCoinWorth($attribs);
  
  if (sizeof($worth) > 0) {
    return $worth[0];
  }
  else {
    return null;
  }
}


//
// Gets the max worth for a coin based on search params
//
function getMaxCoinWorth($attribs) {

  $attribs['sortOrder'] = 'DESC';
  
  $worth = getCoinWorth($attribs);
  
  if (sizeof($worth) > 0) {
    return $worth[0];
  }
  else {
    return null;
  }
}


//
// Gets the discount value for a percentage off a price
//
function getDiscountedValue($price, $percentage) {

  $priceFloat = floatval($price);
  $percentageFloat = floatval($percentage) / 100.0;
  
  return $priceFloat - ($priceFloat * $percentageFloat);
}


//
// Get all US mints
//
function getMints() {
  global $US_MINTS_SQL;
  
  $SQL = $US_MINTS_SQL;
  $result = mysql_query($SQL);
  
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

    $info['id'] = $id;
    $info['name'] = $name;
    $info['symbol'] = $symbol;
    $info['alwaysPresent'] = $alwaysPresent;
    $info['comments'] = $comments;
    
    array_push($mints, $info);
  }
  
  return $mints;
}

//
// Get all US mints
//
function getMintDates() {
  global $US_MINT_DATES_SQL;
  
  $SQL = $US_MINT_DATES_SQL;
  $result = mysql_query($SQL);
  
  $mintDates = array();
  if (mysql_num_rows($result) == 0) {
    return $mintDates;
  }

  while($row = mysql_fetch_array($result)) {
  
    $info = array();
    
    $id = $row['mdid'];
    $mid = $row['mid'];
    $startYear = $row['startYear'];
    $endYear = $row['endYear'];

    $info['id'] = $id;
    $info['mid'] = $mid;
    $info['startYear'] = $startYear;
    $info['endYear'] = $endYear;
    
    array_push($mintDates, $info);
  }
  
  return $mintDates;
}


//
// Get all data for a mint date
//
function getMintDate($id) {
  global $US_MINT_DATE_BY_ID_SQL;
  
  $SQL = sprintf($US_MINT_DATE_BY_ID_SQL, $id);

  $result = mysql_query($SQL);

  $mintDate = array();

  $row = mysql_fetch_array($result);

  $mintDate['id'] = $row['mdid'];
  $mintDate['name'] = $row['name'];
  $mintDate['startYear'] = $row['startYear'];
  $mintDate['endYear'] = $row['endYear'];
  
  return $mintDate;
}


//
// Get all coin years
//
function getCoinYears() {
  global $US_COIN_YEARS_SQL;
  
  $SQL = $US_COIN_YEARS_SQL;
  $result = mysql_query($SQL);
  
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

    $info['id'] = $id;
    $info['coin'] = $coin;
    $info['denomination'] = $denomination;
    $info['year'] = $year;
    $info['yearInfo'] = $yearInfo;
    $info['isGold'] = $isGold;
    $info['isSilver'] = $isSilver;
    
    array_push($years, $info);
  }
  
  return $years;
}


//
// Get all data for a mint date
//
function getCoinYear($id) {
  global $US_COIN_YEAR_BY_ID_SQL;
  
  $SQL = sprintf($US_COIN_YEAR_BY_ID_SQL, $id);

  $result = mysql_query($SQL);

  $year = array();

  $row = mysql_fetch_array($result);

  $id = $row['cyid'];
  $coin = $row['coin'];
  $denomination = $row['denomination'];
  $yearVal = $row['year'];
  $yearInfo = $row['yearInfo'];
  $isGold = $row['isGold'];
  $isSilver = $row['isSilver'];

  $year['id'] = $id;
  $year['coin'] = $coin;
  $year['denomination'] = $denomination;
  $year['year'] = $yearVal;
  $year['yearInfo'] = $yearInfo;
  $year['isGold'] = $isGold;
  $year['isSilver'] = $isSilver;
  
  return $year;
}

?>