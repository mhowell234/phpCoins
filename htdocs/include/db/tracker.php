<?php

//
// Get Coin Trackers stored for ebay coins
//
function getTrackers() {
  global $US_TRACKER_SQL;
  global $US_COINS_INFO_FOR_TYPE_SQL;
  global $US_COIN_BY_TYPE_SQL;
  
  $trackers = array();
  
  $results = mysql_query($US_TRACKER_SQL);
  
  while ($row = mysql_fetch_array($results)) {
    $info = array();
    
    $info['id'] = $row['tsid'];
    $info['cvid'] = $row['cvid'];
    $info['cid'] = $row['cid'];
    $info['name'] = $row['name'];
    $info['description'] = $row['description'];
    $info['maxPrice'] = $row['maxPrice'];
    if ($info['maxPrice'] != null && $info['maxPrice'] != '') {
      $info['maxPrice'] = money($info['maxPrice']);
    }
    
    $timeLeft = 0;
    if (isset($row['auctionEndTime'])) {
      $timeLeft = time() + ($row['auctionEndTime'] * 60);
      
      $info['timeLeft'] = formatTimeLeft($timeLeft);      
    }
    
    // Get coin denomination info    
    $SQL = sprintf($US_COINS_INFO_FOR_TYPE_SQL, $info['cvid']);  
    $result_cvid = mysql_query($SQL);

    if (mysql_num_rows($result_cvid) != 0) {
      $valueRow = mysql_fetch_array($result_cvid);
      $coinName = $valueRow['typeName'];
      $info['value'] = $coinName;
    }
  
    if (isset($info['cid']) && $info['cid'] != '') {    
      $SQL2 = sprintf($US_COIN_BY_TYPE_SQL, $info['cid']);
      $result_cid = mysql_query($SQL2);

      if (mysql_num_rows($result_cid) != 0) {
        $coinRow = mysql_fetch_array($result_cid);
        $coin = $coinRow['name'];
        $info['coin'] = $coin;
      }
    }

    array_push($trackers, $info);
  }
  
  return $trackers;
}


//
// Get specific Coin Tracker
//
function getTrackerById($tsid) {
  global $US_TRACKER_BY_ID_SQL;
  global $US_TRACKER_EMAIL_SQL;
  
  $tracker = array();
  
  $SQL = sprintf($US_TRACKER_BY_ID_SQL, $tsid);

  $results = mysql_query($SQL);
  if (mysql_num_rows($results) == 0) {
    return $tracker;
  }
  
  $row = mysql_fetch_array($results);

  $tracker['id'] = $row['tsid'];
  $tracker['name'] = $row['name'];
  $tracker['description'] = $row['description'];
  $tracker['cvid'] = $row['cvid'];
  $tracker['cid'] = $row['cid'];
  $tracker['cyid'] = $row['cyid'];
  $tracker['cyidStart'] = $row['cyidStart'];
  $tracker['cyidEnd'] = $row['cyidEnd'];
  $tracker['mcid'] = $row['mcid'];
  $tracker['mid'] = $row['mid'];
  $tracker['minPrice'] = $row['minPrice'];
  $tracker['maxPrice'] = $row['maxPrice'];
  $tracker['discountPercentage'] = $row['discountPercentage'];
  $tracker['premiumPercentage'] = $row['premiumPercentage'];
  $tracker['auctionEndTime'] = $row['auctionEndTime'];
  $tracker['gradeCategory'] = $row['gradeCategory'];
  $tracker['grade'] = $row['grade'];
  $tracker['ratingAgency'] = $row['ratingAgency'];
  $tracker['sellerRating'] = $row['sellerRating'];
  $tracker['isBuyItNow'] = $row['isBuyItNow'];
  $tracker['phraseToAdd'] = $row['phraseToAdd'];
  $tracker['phraseToRemove'] = $row['phraseToRemove'];
  
  $SQL2 = sprintf($US_TRACKER_EMAIL_SQL, $tsid);
  
  $emailResults = mysql_query($SQL2);
  if (mysql_num_rows($emailResults) > 0) {
    $addresses = array();
    while ($row = mysql_fetch_array($emailResults)) {
      $addresses[] = $row['address'];
    }
    $tracker['emails'] = $addresses;
  }
  
  return $tracker;
}


//
// Delete a Coin Tracker 
//
function deleteTracker($tracker) {
  global $US_TRACKER_DELETE_SQL;

  $SQL = sprintf($US_TRACKER_DELETE_SQL, $tracker);
  
  mysql_query($SQL);
  
  // Recreate DB file with current trackers…
  // Have to export all over again since there isn't a way to 
  // determine which tracker was deleted from the DB file
  exportAllTrackers($silent=true);
}


//
// Add a Coin Tracker 
//
function addTracker($tracker) {
  global $US_TRACKER_ADD_SQL;
    
  $name = $tracker['name'];
  $description = $tracker['description'];
  $cvid = $tracker['coinValue'];
  $cid = $tracker['coinType'];
  if ($cid == '') $cid = 'NULL';

  $cyidStart = $tracker['coinYearStart'];
  if ($cyidStart == '') { $cyidStart = 'NULL'; }
  
  $cyidEnd = $tracker['coinYearEnd'];
  if ($cyidEnd == '') { $cyidEnd = 'NULL'; }
  
  $cyid = $tracker['coinYear'];
  if ($cyid == '') { $cyid = 'NULL'; }
  
  $mcid = $tracker['coinMintYear'];
  if ($mcid == '') { $mcid = 'NULL'; }
  
  $mid = $tracker['coinMint'];
  if ($mid == '') { $mid = 'NULL'; }
  
  $minPrice = $tracker['minPrice'];
  if ($minPrice == '') { $minPrice = 'NULL'; }

  $maxPrice = $tracker['maxPrice'];
  if ($maxPrice == '') { $maxPrice = 'NULL'; }

  $discountPercentage = $tracker['discountPercentage'];
  if ($discountPercentage == '') { $discountPercentage = 'NULL'; }

  $premiumPercentage = $tracker['premiumPercentage'];
  if ($premiumPercentage == '') { $premiumPercentage = 'NULL'; }
  
  $auctionEndTime = 0;
  $auctionEndDay = $tracker['auctionEndDay'];
  $auctionEndHour = $tracker['auctionEndHour'];
  $auctionEndMinute = $tracker['auctionEndMinute'];

  if ($auctionEndDay < 0 || $auctionEndDay == '') {
    $auctionEndDay = 0;
  }
  if ($auctionEndHour < 0 || $auctionEndHour == '') {
    $auctionEndHour = 0;
  }
  if ($auctionEndMinute < 0 || $auctionEndMinute == '') {
    $auctionEndMinute = 0;
  }  
  $auctionEndTime = ($auctionEndDay * 24 * 60) + ($auctionEndHour * 60) + ($auctionEndMinute);
  
  $gradeCategory = $tracker['gradeCategory'];
  if ($gradeCategory == '') { $gradeCategory = 'NULL'; }
  
  $grade = $tracker['grade'];
  if ($grade == '') { $grade = 'NULL'; }
  
  $sellerRating = $tracker['sellerMinRating'];
  if ($sellerRating == '') $sellerRating = 'NULL';

  $ratingAgency = $tracker['ratingAgency'];
  
  $isBuyItNow = $tracker['isBuyItNow'];
  if ($isBuyItNow == '' || $isBuyItNow == 'off') { $isBuyItNow = 'NULL'; }
  else { $isBuyItNow = 1; }

  $phraseToAdd = $tracker['phrasesToAdd'];
  $phraseToRemove = $tracker['phrasesToRemove'];
  $emails = $tracker['emails'];
  
  $SQL = sprintf($US_TRACKER_ADD_SQL, $name, $description, $cvid, $cid, $cyid, $cyidStart, 
            $cyidEnd, $mcid, $mid, $minPrice, $maxPrice, $discountPercentage, $premiumPercentage,
            $auctionEndTime, $gradeCategory, $grade, $ratingAgency, $sellerRating, 
            $isBuyItNow, $phraseToAdd, $phraseToRemove);
  			

  mysql_query($SQL);
  
  // Export tracker to DB file for record keeping
  exportTracker(mysql_insert_id());
}


//
// Converts a tracker's DB values to readable form
//
function convertTracker($tracker) {
  $new_tracker = array();

  $id = $tracker['id'];						// Tracker id
  $name = $tracker['name'];					// Name
  $description = $tracker['description'];	// Description
  $cvid = $tracker['cvid'];					// Coin denomination
  $cid = $tracker['cid'];					// Coin
  $cyid = $tracker['cyid'];					// Coin year
  $mcid = $tracker['mcid'];	 				// Mint coin  
  $cyidStart = $tracker['cyidStart'];		// Coin year range start
  $cyidEnd = $tracker['cyidEnd'];			// Coin year range start
  $mid = $tracker['mid'];					// Mint
  $minPrice = $tracker['minPrice'];			// Min price
  $maxPrice = $tracker['maxPrice'];  		// Max price
  $discountPercentage = $tracker['discountPercentage'];	// Amount discounted from grade
  $premiumPercentage = $tracker['premiumPercentage'];	// Amount added to grade
  $auctionEndTime = $tracker['auctionEndTime'];	// Time left in auction
  $gradeCategory = $tracker['gradeCategory'];	// Coin grade category
  $grade = $tracker['grade'];				// Coin specific grade
  $ratingAgency = $tracker['ratingAgency'];	// Rating agency
  $sellerRating = $tracker['sellerRating'];	// Seller's rating
  $isBuyItNow = $tracker['isBuyItNow'];		// Is buy it now?
  $phrasesToAdd = explode("\n", $tracker['phraseToAdd']);  // Keyword phrases to add
  $phrasesToRemove = explode("\n", $tracker['phraseToRemove']); // Keyword phrases to remove
  $emails = $tracker['emails'];
  
  $pA = array();
  foreach ($phrasesToAdd as $phrase) {
    $phrase = trim($phrase);
    $pA[] = $phrase;
  }
  $phrasesToAdd = $pA;

  $pR = array();
  foreach ($phrasesToRemove as $phrase) {
    $phrase = trim($phrase);
    $pR[] = $phrase;
  }
  $phrasesToRemove = $pR;
  
  $new_tracker['id'] = $id;
  $new_tracker['cvid'] = $cvid;
  $new_tracker['cid'] = $cid;
  $new_tracker['cyid'] = $cyid;
  $new_tracker['mid'] = $mid;
  $new_tracker['mcid'] = $mcid;
  $new_tracker['name'] = $name;
  $new_tracker['description'] = $description;
  $new_tracker['minPrice'] = $minPrice;
  $new_tracker['maxPrice'] = $maxPrice;
  $new_tracker['discountPercentage'] = $discountPercentage;
  $new_tracker['premiumPercentage'] = $premiumPercentage;
  $new_tracker['auctionEndTime'] = $auctionEndTime;
  $new_tracker['ratingAgency'] = $ratingAgency;
  $new_tracker['sellerRating'] = $sellerRating;
  $new_tracker['isBuyItNow'] = $isBuyItNow;
  $new_tracker['phrasesToAdd'] = $phrasesToAdd;
  $new_tracker['phrasesToRemove'] = $phrasesToRemove;
  $new_tracker['emails'] = $emails;
  
  // COIN DENOMINATION
  if ($cvid != null && $cvid != '') {
    $categories = array();
  
    $attribs = getCoinValueAttributes($cvid);

    foreach($attribs as $attrib) {
      $type = $attrib['type'];
      if ($type == '1') {
        $categories[] = $attrib['value'];
      }
    } 
    
    $new_tracker['coinValueCategories'] = $categories; 

    $result = getUSCoins($cvid);
    if (sizeof($result) > 0) {
    	$new_tracker['value'] = $result['name'];
    }
  }

  // COIN 
  if ($cid != null && $cid != '') {
    $categories = array();

    $attribs = getCoinAttributes($cid);
    
    foreach($attribs as $attrib) {
      $type = $attrib['type'];
      if ($type == '1') {
        $categories[] = $attrib['value'];
        $hasCategories = true;  
      }
    }  

    $new_tracker['coinCategories'] = $categories; 
    
    $result = getUSCoin($cid);
    if (sizeof($result) > 0) {
	    $new_tracker['coin'] = $result['name'];
    	$new_tracker['coinStartYearDB'] = $result['startYear'];
	    $new_tracker['coinEndYearDB'] = $result['endYear'];
	}
  }

  // Coin Year INFO
  if ($cyid != null) {
    $yearData = getUSCoinYear($cyid);

    $year = $yearData['year'];
    $new_tracker['year'] = (int)$year;
    $new_tracker['yearInfo'] = $yearData['info'];
  }
  
  if ($cyidStart != null) {
      $yearData = getUSCoinYear($cyidStart);
      $year = $yearData['year'];
      
      $new_tracker['startYear'] = (int)$year;
      $new_tracker['yearInfo'] = $yearData['info'];
  }
  if ($cyidEnd != null) {
      $yearData = getUSCoinYear($cyidEnd);
      $year = $yearData['year'];

      $new_tracker['endYear'] = (int)$year;
      $new_tracker['yearInfo'] = $yearData['info'];
  }

  // Mint Coin INFO (symbol)
  if ($mcid != null) {
    $mintCoinData = getMintCoin($mcid);

    $symbol = $mintCoinData['symbol'];
    $mint = $mintCoinData['mint'];
    $coinInfo = $mintCoinData['coinInfo'];
    
    $new_tracker['coinMintSymbol'] = $symbol;
    $new_tracker['coinMint'] = $mint;
    $new_tracker['coinInfo'] = $coinInfo;
  }
  
  // Mint INFO
  if ($mid != null) {
    $mintData = getUsMintInfo($mid);
    
    $symbol = $mintData['symbol'];
    $mint = $mintData['name'];
    
    $new_tracker['mintSymbol'] = $symbol;
    $new_tracker['mint'] = $mint;
  }
  
  // Grade Rating Categories
  if ($gradeCategory != null) {
    $categoryData = getRatingCategoryById($gradeCategory);
    
    $ratingScale = $categoryData['title'];
    
    $new_tracker['gradeCategory'] = $ratingScale;
  }
  
  // Grade Rating
  if ($grade != null) {
    $ratingData = getRatingById($grade);

    $ratingScale = $ratingData['title'];

    $new_tracker['grade'] = $ratingScale;
  }
  
  // 
  // If a $mcid is defined...
  // If grade…get the coin value for that grade
  // else if category. get the value for the lowest grade in that category
  // else use the lowest grade possible
  // 
  
  return $new_tracker;
}



?>
