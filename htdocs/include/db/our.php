<?php

//
// Get coins info for a denomination (Info for all types of Quarters)
//
function getOurUSCoins() {
  global $OUR_COINS_SQL;
  
  $ourCoins = array();
  
  $result = mysql_query($OUR_COINS_SQL);
  
  while ($row = mysql_fetch_array($result)) {
    $info = array();  
    
    $info['id'] = $row['ocid'];
    $info['name'] = $row['coinName'];
    $info['value'] = $row['value'];
    $info['year'] = $row['year'];
    $info['yearInfo'] = $row['yearInfo'];
    $info['coinInfo'] = $row['coinInfo'];
    $info['symbol'] = $row['symbol'];
    $info['originDate'] = $row['originDate'];
    $info['origin'] = $row['origin'];
    $info['isSilver'] = $row['isSilver'];
    $info['isGold'] = $row['isGold'];
    $info['isProof'] = $row['isProof'];
    $info['pricePaid'] = $row['pricePaid'];

    array_push($ourCoins, $info);
  }
  
  return $ourCoins;
}


//
// Get info for a specific coin (1964P Washington Quarter)
//
function getOurMintCoin($ourCoinId) {
  global $OUR_COIN_INFO_SQL;
  
  $SQL = sprintf($OUR_COIN_INFO_SQL, $ourCoinId);
  
  $result = mysql_query($SQL);
  
  $row = mysql_fetch_array($result);
  
  $coinId = $row['cid'];
  $coinValueId = $row['cvid'];
  $coinTitle = getCoinTitle($row, 1);
  $coinName = $row['coinName'];
  $coinValue = $row['value'];
  $mintCoinId = $row['mcid'];
  
  $numberMinted = num($row['numberMinted']);
  $origin = $row['origin'];
  $originDate = $row['originDate'];
  $pricePaid = money($row['pricePaid']);
  
  $coinDescription = $row['coinDescription'];
  if ($coinDescription != "") {
    $coinDescription = formatDescription($coinDescription);
  }
  
  $ourCoinInfo = array();
  $ourCoinInfo['id'] = $coinId;
  $ourCoinInfo['cvid'] = $coinValueId;
  $ourCoinInfo['mcid'] = $mintCoinId;
  $ourCoinInfo['title'] = $coinTitle;
  $ourCoinInfo['name'] = $coinName;
  $ourCoinInfo['value'] = $coinValue;
  $ourCoinInfo['description'] = $coinDescription;
  $ourCoinInfo['numberMinted'] = $numberMinted;
  $ourCoinInfo['origin'] = $origin;
  $ourCoinInfo['originDate'] = $originDate;
  $ourCoinInfo['pricePaid'] = $pricePaid;
  $ourCoinInfo['year'] = $row['year'];
  $ourCoinInfo['symbol'] = $row['symbol'];
  $ourCoinInfo['isSilver'] = $row['isSilver'];
  $ourCoinInfo['isGold'] = $row['isGold'];
  $ourCoinInfo['isProof'] = $row['isProof'];
  $ourCoinInfo['yearInfo'] = $row['yearInfo'];
  $ourCoinInfo['coinInfo'] = $row['coinInfo'];
  
  return $ourCoinInfo;  
}


//
// Get the price values for grades for a specific coin
//
function getOurCoinValuesAndTypes($ourCoinId) {
  global $OUR_COIN_VALUE_SQL;
  
  $SQL = sprintf($OUR_COIN_VALUE_SQL, $ourCoinId);

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
// Get grade ratings from rating agencies from one of our coins
function getOurCoinRatings($coinId) {
  global $OUR_COIN_RATING_SQL;

  $SQL = sprintf($OUR_COIN_RATING_SQL, $coinId);
  
  $results = mysql_query($SQL);
  $coinRatings = array();

  while ($row = mysql_fetch_array($results)) {
    $info = array();
    $info['date'] = $row['ratingDate'];
    $info['rating'] = $row['rating'];
    $info['agency'] = $row['agency'];
    $info['category'] = $row['ratingCategory'];
    
    array_push($coinRatings, $info);
  }
  
  return $coinRatings;
}


//
// Get images for a coin type
//
function getOurImages($coinId) {
  global $OUR_US_COIN_PHOTO_SQL;

  $SQL = sprintf($OUR_US_COIN_PHOTO_SQL, $coinId);
  
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
function getOurThumbnails($coinId) {
  global $OUR_US_COIN_THUMBNAIL_SQL;
  
  $SQL = sprintf($OUR_US_COIN_THUMBNAIL_SQL, $coinId);

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
// Convert our US Coins into sub arrays
//
function convertOurUSCoins() {

  $data = array();
  $coins = getOurUSCoins();
  
  foreach($coins as $coin) {

    $id = $coin['id'];
    $name = $coin['name'];
    $year = $coin['year'];
    $symbol = $coin['symbol'];
    $value = $coin['value'];

    $info = array();
    $info['id'] = $id;
    $info['name'] = $name;
    $info['year'] = $year;
    $info['symbol'] = $symbol;
    $info['value'] = $value;
    
    $data[$value][$name][] = $info;    
  }
  
  return $data;
}


//
// Add one of Our Coins
//
function addOurCoin($coin) {
  global $OUR_US_COIN_ADD_SQL;
    
  $mcid = $coin['coinMintYear'];
  $pricePaid = $coin['pricePaid'];

  $ratingAgency = $coin['ratingAgency'];
  $gradeCategory = $coin['gradeCategory'];
  if ($gradeCategory == '') { $gradeCategory = 'NULL'; }  
  $grade = $coin['grade'];
  if ($grade == '') { $grade = 'NULL'; }
  
  $origin = $coin['origin'];
  $originDate = $coin['originDate'];
  $isWrapped = $coin['isWrapped'];
  if ($isWrapped == '' || $isWrapped == 'off') { $isWrapped = 'NULL'; }
  else { $isWrapped = 1; }

  $isProof = $coin['isProof'];
  if ($isProof == '' || $isProof == 'off') { $isProof = 'NULL'; }
  else { $isProof = 1; }

  $notes = $coin['notes'];
  
  $SQL = sprintf($OUR_US_COIN_ADD_SQL, $mcid, $pricePaid, $origin, $originDate, 'NULL','NULL', $isProof);
  			
  mysql_query($SQL);
  
  // Export tracker to DB file for record keeping
  //exportTracker(mysql_insert_id());
}

?>
