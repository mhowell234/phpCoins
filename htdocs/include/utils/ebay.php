<?php

// Define global variables
$m_endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1?';  
$appid = 'MatthewH-db24-44f3-8a9c-c7103f6747d6';
$responseEncoding = 'XML';  


//
// Creates base Ebay search URL
//
function createEbaySearchBaseURL() {
  global $m_endpoint;
  global $appid;
  global $responseEncoding;
  
  $api  = "$m_endpoint";
  $api .= "SECURITY-APPNAME=$appid";
  $api .= "&OPERATION-NAME=findItemsAdvanced";
  $api .= "&SERVICE-VERSION=1.0.0";
  $api .= "&RESPONSE-DATA-FORMAT=$responseEncoding";
  $api .= "&REST-PAYLOAD";
  
  return $api;
}


//
// Create ebay search request for a string
//
function createEbaySearchURL($searchString) {

  $api = createEbaySearchBaseURL();
  $api .= "&keywords=$searchString";  
  $api .= "&paginationInput.entriesPerPage=15";
  $api .= "&sortOrder=BestMatch";
  $api .= "&categoryId=11116";
  
  return $api;
}


//
// Create tracker request based on tracker attributes
//
function createEbayTrackerSearchURL($tracker) {
  $MAX_CATEGORIES = 3;

  $coinMinWorth = getMinCoinWorth($tracker);
  $coinMaxWorth = getMaxCoinWorth($tracker);

  $tracker = convertTracker($tracker);
  
  $api = createEbaySearchBaseURL();
  $api .= "&sortOrder=BestMatch";

  $coinValue = $tracker['value'];
  $coin = $tracker['coin'];  

  $coinValueCategories = $tracker['coinValueCategories'];
  $coinCategories = $tracker['coinCategories'];
  
  $year = $tracker['year'];
  $startYear = $tracker['startYear'];
  $endYear = $tracker['endYear'];
  $mintSymbol = $tracker['mintSymbol'];
  $coinMintSymbol = $tracker['coinMintSymbol'];
  $minPrice = $tracker['minPrice'];
  $maxPrice = $tracker['maxPrice'];
  $discountPercentage = $tracker['discountPercentage'];
  $premiumPercentage = $tracker['premiumPercentage'];
  $auctionEndTime = $tracker['auctionEndTime'];
  $grade = $tracker['grade'];
  $gradeCategory = $tracker['gradeCategory'];
  $ratingAgency = $tracker['ratingAgency'];
  $isBuyItNow = $tracker['isBuyItNow'];
  $sellerRating = $tracker['sellerRating'];
  $phrasesToAdd = $tracker['phrasesToAdd'];
  $phrasesToRemove = $tracker['phrasesToRemove'];
  
  $discountValue = getDiscountedValue($coinMinWorth, $discountPercentage);
  $premiumValue = getDiscountedValue($coinMinWorth, "-$premiumPercentage");

  $keywords = "";
       
  $i=0;
  
  // END TIME
  if ($auctionEndTime != null && $auctionEndTime != '0') { 
    // Specify in GMT: YYYY-MM-DDTHH:MM:SS.SSSZ (e.g., 2004-08-04T19:09:02.768Z)
    $futureTime = time() + ($auctionEndTime * 60);
    $format = "Y-m-d\TH:i:s.000\Z";
    
    $gmTs = gmdate($format, $futureTime);
    
    $api .= "&itemFilter($i).name=EndTimeTo";
    $api .= "&itemFilter($i).value(0)=$gmTs";
    $i++;
  }  
  
  // SELLER RATING
  if (0) {
    // If $sellerRating specified
    $api .= "&itemFilter($i).name=FeedbackScoreMin";
    $api .= "&itemFilter($i).value(0)=$sellerRating";
    $i++;
  }
  
  // IS BUY IT NOW
  if ($isBuyItNow != null && $isBuyItNow == 1) {
    $api .= "&itemFilter($i).name=ListingType";
    $api .= "&itemFilter($i).value(0)=AuctionWithBIN";
    $i++;
  }
  else {
    $api .= "&itemFilter($i).name=ListingType";
    $api .= "&itemFilter($i).value(0)=Auction";
    $i++;
  }

  // If DISCOUNT PERCENTAGE/PREMIUM PERCENTAGE
  if ($discountPercentage != null && $discountValue != null) {
    	$api .= "&itemFilter($i).name=MaxPrice";
	    $api .= "&itemFilter($i).value(0)=$discountValue";
    	$api .= "&itemFilter($i).paramName=Currency";
	    $api .= "&itemFilter($i).paramValue=USD";
    	$i++;
  }
  else if ($premiumPercentage != null && $premiumValue != null) {
    	$api .= "&itemFilter($i).name=MaxPrice";
	    $api .= "&itemFilter($i).value(0)=$premiumValue";
    	$api .= "&itemFilter($i).paramName=Currency";
	    $api .= "&itemFilter($i).paramValue=USD";
    	$i++;
  }
  else {
	  // If MIN PRICE
	  if ($minPrice != null) {
    	$api .= "&itemFilter($i).name=MinPrice";
	    $api .= "&itemFilter($i).value(0)=$minPrice";
    	$api .= "&itemFilter($i).paramName=Currency";
	    $api .= "&itemFilter($i).paramValue=USD";
    	$i++;
	  }

	  // If MAX PRICE
	  if ($maxPrice != null) {
    	$api .= "&itemFilter($i).name=MaxPrice";
	    $api .= "&itemFilter($i).value(0)=$maxPrice";
    	$api .= "&itemFilter($i).paramName=Currency";
	    $api .= "&itemFilter($i).paramValue=USD";
    	$i++;
  	  }
  }
  
  // If no categories, default to US coins category
  if (isset($coinCategories) && sizeof($coinCategories) > 0) {
    $j = 0;
    foreach ($coinCategories as $category) {
      if ($j >= $MAX_CATEGORIES) {
        break;
      }
      
      $api .= "&categoryId=$category";
      $j++;
    }
  }
  else if (isset($coinValueCategories) && sizeof($coinValueCategories) > 0) {
    $j = 0;
    foreach ($coinValueCategories as $category) {
      if ($j >= $MAX_CATEGORIES) {
        break;
      }
      
      $api .= "&categoryId=$category";
      $j++;
    }
  }
  else {
    $api .= "&categoryId=253";
  }

  
  // Coin Year
  if ($year != null) {
    $startYear = (int)$year;
    $endYear = (int)$year;
    $keywords .= "$year ";
  }
  else if ($startYear != null || $endYear != null) {
  }

  // Mint Coin Symbol
  if ($coinMintSymbol != null) {
    $keywords .= "$coinMintSymbol ";
  }
  
  // Mint 
  if ($mintSymbol != null) {
    $keywords .= "$mintSymbol ";
  }
  
  // Grade Rating Categories
  $ratingScale = '';
  if ($gradeCategory != null) {
    $ratingScale = $gradeCategory;
  }
  // Grade Rating
  if ($grade != null) {
    $ratingScale = $grade;
  }
  if ($ratingScale != '') {
    $keywords .= "$ratingScale ";
  }

  // Rating Agency
  if ($ratingAgency != null) {
    $keywords .= "$ratingAgency ";
  }
  
  // Phrases to add 
  if (sizeof($phrasesToAdd) > 0) {
    foreach ($phrasesToAdd as $phrase) {
      $keywords .= "$phrase "; 
    }
  }
  
  // Phrases to remove
  if (sizeof($phrasesToRemove) > 0) {
    foreach ($phrasesToRemove as $phrase) {
      $keywords = str_replace($phrase, "", $keywords);
    }
  }

  
  if (strlen($keywords) > 0) {
    $keywords = preg_replace('!\s+!', '%20', trim($keywords));
    $api .= "&keywords=$keywords";  
  }  

  return $api;
}


//
// Return ebay results
//
function ebayResults($url, $tracker=null) {
  $resp = simplexml_load_file($url);  
  $results = array();
  
  if ($resp->ack == "Success") {
    $items = $resp->searchResult->item;  
    foreach ($items as $coin) {
      $info = array();
      
      $info['title'] = (string) $coin->title;
      $info['image'] = (string) $coin->galleryURL;
      $info['image2'] = (string) $coin->galleryPlusPictureURL;
      $info['url'] = (string) $coin->viewItemURL;
      $info['price'] = (string) $coin->sellingStatus->currentPrice;
      $info['shipping'] = (string) $coin->shippingInfo->shippingServiceCost;
      $info['bids'] = (string) $coin->sellingStatus->bidCount;     
      $info['end'] = (string) $coin->listingInfo->endTime;

//      getAdditionalEbayAttributes()
      
      array_push($results, $info);
    }  
    
  }

  return $results;

}


//
// Format ebay search results into a viewable format
//
function ebayDisplay($results, $header="eBay Auctions", $sectionClass="", $divClass="content", $showSubPhotos=true) {

  $data = "";

  if (count($results)) {
    $data .= "<section id='ebay-auctions' class='no-space $sectionClass'>";
    $data .= "<div class='section'>";
    $data .= "<h3 class='heading down-arrow'>$header</h3>";
    $data .= "<div class='$divClass'>";
    $data .= "<dl>";

    $first = true;
    
    foreach ($results as $coin) {
      $coinName = $coin['title'];
      $coinURL = $coin['url'];
      
      $coinPhoto = $coin['image'];
      
      $coinPrice = money($coin['price']);
      $coinShipping = money($coin['shipping']);

      $coinBids = $coin['bids'];
      
      $coinEndTime = strtotime($coin['end']);

      $timeLeft = formatTimeLeft($coinEndTime);

      $description = "<p><label>Current Price:</label> <strong>$coinPrice</strong></p>";

      if ($coinShipping !== '') {
        if ($coinShipping == '$0.00') { 
          $description .= "<p><label>Shipping:</label> <strong>FREE</strong></p>";
        }
        else {
          $description .= "<p><label>Shipping:</label> <strong>$coinShipping</strong></p>";
        }  
      }
    
      if ($coinBids !== '') {
        $description .= "<p><label>Bids:</label> $coinBids</p>";
      }

      $description .= "<p><label>Time left:</label> $timeLeft</p>";
      
      $photos = array();
      $photo = array();
      $photo['file'] = $coinPhoto;
      $photo['caption'] = $coinName;
      array_push($photos, $photo);
        
      if ($first) $data .= "<dt class='first'>";
      else $data .= "<dt>";
      
      $data .= "<p><a href='$coinURL' target='_blank'>$coinName</a></p></dt>";

      if ($first) $data .= "<dd class='first'>";
      else $data .= "<dd>";
      
      if (count($photos) > 0) {
        $data .= displayImages($photos, 100, $showSubPhotos);
      }   
  
      if ($description != "") {
        $data .= "<p>$description</p>";
      }
      $data .= "</dd>"; 

      $first = false;
    }
    $data .= "</dl></div><div class='visualClear'></div>";
    $data .= "</div></section>";
  }
  
  return $data;
}


//
// Search ebay for a given search ordered by best match
//
function getEbaySearchResults($searchString) {

  $api = createEbaySearchURL($searchString);
  
  return ebayResults($api);
}

//
// Get the best deals for coins ending in the next 60 minutes
//
function getBestEbayDeals($search) {

  $api = createEbaySearchBaseURL();  
  $api .= "&paginationInput.entriesPerPage=20000";
  $api .= "&sortOrder=BestMatch";
///  $api .= "&categoryId=253"; US Coins
  $api .= "&categoryId=256";  //World Coins

  return ebayResults($api);
}


//
// Ebay search to get tracker search results 
//
function getTrackerResults($tsid) {
  $tracker = getTrackerById($tsid);
  $api = createEbayTrackerSearchURL($tracker);
  return ebayResults($api);  
}


//
// Format ebay search results into a viewable format
//
function displayEbayResults($search) {

  $ebayResults = getEbaySearchResults($search);
  
  echo ebayDisplay($ebayResults);
}


//
// Format ebay search results into a viewable format
//
function displayTrackerResults($tsid) {

  $ebayResults = getTrackerResults($tsid);

  print ebayDisplay($ebayResults, "Current Results", "expand-on-load", "content");
}


//
// Ebay search to get tracker search results 
//
function batchTracker($tsid) {
  $tracker = getTrackerById($tsid);

  $api = createEbayTrackerSearchURL($tracker);

  $trackerConverted = convertTracker($tracker);
  
  $name = $trackerConverted['name'];
  
  // Get emails to send to
  $emails = $trackerConverted['emails'];
  
  if (sizeof($emails) <= 0) {
    return 3;
  }
  
  $ebayResults = ebayResults($api);  

  if (!isset($ebayResults) || sizeof($ebayResults) <= 0) {
    return 2;
  }

  $subject = "Ebay Tracker - $name";
  $to = implode(", ", $emails);

  $body = "<html><body>";
  $body .= "<style type='text/css'>";
  $body .= <<<EOF
  body {
  background: #AE070F;
  font: 1.1em Palatino,"Lucida Grande",Georgia,Verdana,Lucida,Helvetica,Arial,sans-serif;
}

h3, h4 {
  font-size: 1.2em;
}

p {
  line-height: 1.3em;
}

#main-wrapper {
  background-color: #fff;
  border: 2px solid #000;
  clear: both;
  margin: 2em auto;
  max-width: 960px;
  padding: 1em;
  border-radius: 1em;
  -moz-border-radius: 1em;
  -webkit-border-radius: 1em;
  position: relative;
}

h2 {
  color: #AE070F;
  border-bottom: 1px solid #AE070F;
  margin-bottom: 0.25em;
  padding-bottom: 0;
}

h3 {
  margin-top: 0;
  padding-top: 0;
}

h3 a {
  color: #999;
  font-size: .8em;
  text-decoration: none;
}

h3 a:hover {
  text-decoration: underline
}

#search-area-section, #coin-type-select {
  float: right;
}

section {
  margin: 1em 1em;
}

section#our-coins li {
  padding-top: .25em;
  padding-bottom: .25em;
}

.image-right {
  float: right;
  padding: 1em;
}

.image-right img {
  max-width: 200px;
}

.visualClear { clear: both; }

table {
  border-collapse:collapse;
  border-right:1px solid #e5eff8;
  border-top:1px solid #e5eff8;
  font-size: .9em;
  margin:0 .3em 1em;
  width:90%;
}

caption {
  color: #9ba9b4;
  font-size:.94em;
  letter-spacing:.1em;
  margin:1em 0 0 0;
  padding:0;
  caption-side:top;
  text-align:center;
}	

tr.odd td { background:#f7fbff }

tr.odd .column1	{ background:#f4f9fe; }	

.column1 { background:#f9fcfe; }

td {
  color:#678197;
  border-bottom:1px solid #e5eff8;
  border-left:1px solid #e5eff8;
  padding:.3em .5em;
  text-align:center;
}
	
th {
  font-weight:normal;
  color: #678197;
  text-align:left;
  border-bottom: 1px solid #e5eff8;
  border-left:1px solid #e5eff8;
  padding:.3em 1em;
}							

thead th {
  background:#f4f9fe;
  text-align:center;
  font:bold 1.1em;
  color:#66a3d3;
}	

tfoot th {
  text-align:center;
  background:#f4f9fe;
}	

tfoot th strong {
  font-size: 1.2em;
  font-weight: bold;
  margin:.5em .5em .5em 0;
  color:#66a3d3;
}		

tfoot th em {
  color:#f03b58;
  font-weight: bold;
  font-size: 1.1em;
  font-style: normal;
}

dl {
  float: left;
  width: 100%;
  margin: 1em 0;
  padding: 0;
  border-bottom: 1px solid #CCC;
}

dt {
  clear: left;
  float: left;
  width: 300px;
  padding: 10px 5px;
  border-top: 1px solid #CCC;
  font-weight: bold;
}

dd {
  float: left;
  width: 600px;
  margin: 0;
  padding: 10px 5px;
  border-top: 1px solid #CCC;
}	

fieldset#search, fieldset#add {
  border-radius: 1em;
  margin: auto;
}

fieldset#search input[type=text],
fieldset#add input[type=text] {
  font-size: 1em;
  border-radius: 1em;
  padding: 5px;
}

fieldset#search input[type=submit],
fieldset#add input[type=submit] {
  font-size: 1.25em;
  border-radius: 1em;
  padding: 10px;
}

label {
  font-size: .9em;
  font-weight: bold;
}

.no-wrap {
  white-space:nowrap;
}

.smaller {
  font-size: .9em;
}

.discreet {
  font-size: .8em;
}

.header-float {
  color: #000;
  float: right;
  padding-right: 1em;
}

th.header-column, td.header-column {
  font-weight: bold;
  text-align: center;
}

a.no-line {
  border-bottom: 0;
  padding-bottom: 0;
  text-decoration: none;
}

input[type=search] {
  -webkit-appearance: textfield;
  font-size: 12px;
  border: 2px inset #ccc;
  -moz-border-radius: 10px; 
  -webkit-border-radius: 10px;
  border-radius: 10px;
  width: 150px;
  background: white; 
  padding: 3px; 
  padding-left: 20px; 
}

h3.down-arrow, h3.up-arrow {
  min-height: 30px;
  line-height: 30px;
  padding: 1em 4em 1em 1em;
}

h3.down-arrow:hover, h3.up-arrow:hover {
  background-color: #eee;
  -moz-border-radius: 1em;
  -webkit-border-radius: 1em;
  border-radius: 1em;
}

#tracker-search	td {
  text-align: left;
}

.subdiv {
  color: #FFF;
  padding-left: .5em;
  padding-right: .25em;
}

span.showdiv {
  color: #AE070F;
}

EOF;

  $body .= "</style><div id='main-wrapper'>"; 
  $body .= ebayDisplay($ebayResults, "Tracker Coins! - $name", "", "content1 1content-show", false);
  $body .= "<p>I love you!</p></div></body></html>";

  $headers = 'Content-Type: text/html; charset=ISO-8859-1\r\n' .
    'MIME-Version: 1.0';

  if (mail($to, $subject, $body, $headers)) {
    return 0;
   //echo("<p>Message successfully sent!</p>");
  } else {
    return 1;
   //echo("<p>Message delivery failed...</p>");
  }
}


?>
