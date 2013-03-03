<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

global $SEARCH_OUR_COIN_SQL;
global $SEARCH_ALL_SQL;
global $US_COIN_SEARCH_SQL;
global $FOREIGN_COIN_SEARCH_SQL;
global $OUR_COIN_SEARCH_SQL;


$isKMSearch = 0;

if ($ourCoinSearch != -1) {
  $SEARCH_SQL = sprintf($SEARCH_OUR_COIN_SQL, $searchText, $searchText);
}
else { 
  $SEARCH_SQL = sprintf($SEARCH_ALL_SQL, $searchText, $searchText);
  
  $lowerSearch = strtolower($searchText);
  if (startsWith($lowerSearch, "km") != 0) {
    $isKMSearch = 1;
  }
}

$searchResults = mysql_query($SEARCH_SQL);

echo "<dl>";

while ($searchRow = mysql_fetch_array($searchResults)) {

  $tableName = get($searchRow, 'tableName');
  $uid = get($searchRow, 'uid');

  $relevance = get($searchRow, 'score');
    
  if ($tableName == 'UsCoinDB.MintCoin') {
  
    $SQL = sprintf($US_COIN_SEARCH_SQL, $uid);    
    
    $results = mysql_query($SQL);
    
    $row = mysql_fetch_array($results);

    $typeId = get($row, 'cid');
    $valueId = get($row, 'cvid');
  
    $name = get($row, 'name');
    $denomination = get($row, 'denomination');
    $description = get($row, 'description');
  
    $startYear = get($row, 'startYear');
    $endYear = get($row, 'endYear');
    $dateRange = formatDateRange($startYear, $endYear);

    $photos = getImages($typeId);

    if ($description != "") {
      $description = formatDescription(getBriefDescription($description));
    }
  
    echo "<dt><h4><a href='/us-coins?typeId=$typeId'>$name</a></h4>";
  
    if ($dateRange != "") {
      echo "<p>($dateRange)</p>";
    }
      
    if ($denomination != "") {
      echo "<p>Type: <a class='no-line' href='/us-coins?valueId=$valueId'>$denomination</a></p>";
    }
    
    if ($relevance > 0) {
      echo "<!-- Relevance: $relevance -->";
    }
     
    echo "</dt>";
  
    echo "<dd>";
    if (count($photos) > 0) {
      echo displayImages($photos, 100);
    }   

    if ($description != "") {
      echo "<p>$description</p>";
    }
    echo "</dd>";
    
  } 

  else if ($tableName == 'ForeignCoinDB.MintCoin') {
  
    $SQL = sprintf($FOREIGN_COIN_SEARCH_SQL, $uid);    

    $results = mysql_query($SQL);
    
    $row = mysql_fetch_array($results);

    $typeId = get($row, 'cid');
    $valueId = get($row, 'cvid');
    $fcId = get($row, 'fcid');
    
    if ($isKMSearch) {
      echo "<script type='text/javascript'>";
      echo "document.location = 'http://localhost:8888/foreign-coins/?typeId=$typeId';";
      echo "</script>";
      break;
    }
 
    $name = get($row, 'name');
    $denomination = get($row, 'denomination');
    $description = get($row, 'description');
    $country = get($row, 'country');
    
    $startYear = get($row, 'startYear');
    $endYear = get($row, 'endYear');
    $dateRange = formatDateRange($startYear, $endYear);

    $photos = getForeignImages($typeId);

    if ($description != "") {
      $description = formatDescription(getBriefDescription($description));
    }
  
    echo "<dt><h4><a href='/foreign-coins?typeId=$typeId'>$name</a></h4>";
  
    if ($dateRange != "") {
      echo "<p>($dateRange)</p>";
    }
    
    if ($country != "" || $denomination != "") {
      echo "<p>";
      
      if ($country != "") {
        echo "Country: <a class='no-line' href='/foreign-coins?fcid=$fcId'>$country</a>";
      }
      
      if ($denomination != "") {
        echo "<br />Type: <a class='no-line' href='/foreign-coins?valueId=$valueId'>$denomination</a>";
      }
      echo "</p>";
    }  
    
    
    if ($relevance > 0) {
      echo "<!-- Relevance: $relevance -->";
    }
     
    echo "</dt>";
  
    echo "<dd>";
    if (count($photos) > 0) {
      echo displayImages($photos, 100);
    }   

    if ($description != "") {
      echo "<p>$description</p>";
    }
    echo "</dd>";
    
  } 
  
  
  else if ($tableName == 'OurCoinDB.OurCoin') {
  
    $SQL = sprintf($OUR_COIN_SEARCH_SQL, $uid);    
    $results = mysql_query($SQL);
    
    $row = mysql_fetch_array($results);

    $ocid = get($row, 'ocid');
    $valueId = get($row, 'cvid');
  
    $name = get($row, 'name');
    $denomination = get($row, 'denomination');
    $description = get($row, 'description');
    $origin = get($row, 'origin');
    $originDate = get($row, 'originDate');
    $year = get($row, 'year');
    $mint = get($row, 'symbol');
    $photos = getOurImages($ocid);

    $title = getCoinTitle($row);
    
    if ($description != "") {
      $description = formatDescription(getBriefDescription($description));
    }
  
    echo "<dt><h4><a href='/our-coins?id=$ocid'>$name - $title</a></h4>";
  
    if ($denomination != "") {
      echo "<p>Type: <a class='no-line' href='/us-coins?valueId=$valueId'>$denomination</a></p>";
    }
    
    if ($origin != "" || $originDate != "") {
      echo "<p>$origin on $originDate</p>";
    }
    
    if ($relevance > 0) {
      echo "<!-- Relevance: $relevance -->";
    }

    echo "</dt>";
  
    echo "<dd>";
    if (count($photos) > 0) {
      echo displayImages($photos, 100);
    }   

    echo "<p><strong>OUR COIN</strong></p>";
    if ($description != "") {
      echo "<p>$description</p>";
    }
    echo "</dd>";
    
  } 
  
}

echo "</dl>";

?>
