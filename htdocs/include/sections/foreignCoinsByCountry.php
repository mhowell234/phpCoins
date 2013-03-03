<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

$fcid = request('fcid');

$data = getForeignAllCoins($fcid);
$country = get($data, 'country');

$types = $data['types'];

echo "<h2>" . get($data['types'][0], 'possessiveName') . " Coins</h2><br />";

foreach($types as $coin) {
  echo "<h3>" . get($coin, 'type') . "</h3>";
  
  $coins = get($coin, 'coins');

  echo "<dl>";
  
  foreach($coins as $coin) {
  
    $dateRange = get($coin, 'dateRange');
    $coinId = get($coin, 'id');
  
    echo "<dt><p><a href='/foreign-coins?typeId=" . $coinId . "'>" . $coin['name'] . "</a></p><p>($dateRange)</p></dt>";

    $photos = getForeignImages($coinId);
    $thumbs = getForeignThumbnails($coinId);
  
    echo "<dd>";
    if (count($thumbs) > 0) {
      echo displayImages($thumbs, 100);
    }   

    $description = get($coin, 'description');
    if ($description != "") {
      $description = formatDescription(getBriefDescription($description));
      echo "<p>$description</p>";
    }

    echo "</dd>";
  
  }
  
  echo "</dl>";
  echo clear();
}



// Add EBAY coins
displayEbayResults($ebaySearch);

?>



