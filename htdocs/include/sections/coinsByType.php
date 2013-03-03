<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

$valueId = request('valueId');

$coinType = getUSCoins($valueId);

$name1 = get($coinType, 'name');
$description = get($coinType, 'description');
$coins = get($coinType, 'list');

include("categories.php");
echo "<h2>$name1</h2>";


if ($description != "") {
  echo "<p class='expandable'>$description</p>";
}


if (count($coins)) {
  echo "<dl>";

  foreach ($coins as $coin) {
    $coinId = get($coin, 'id');
    $name = get($coin, 'name');
    $description = get($coin, 'description');
    $dateRange = get($coin, 'dateRange');
    
    $thumbs = getThumbnails($coinId);
    $photos = getImages($coinId);

    echo "<dt><p><a href='/us-coins?typeId=$coinId'>$name</a></p><p>($dateRange)</p></dt>";
  
    echo "<dd>";
    if (count($thumbs) > 0) {
      echo displayImages($thumbs, 100);
    }   
  
    if ($description != "") {
      echo "<p>$description</p>";
    }
    echo "</dd>"; 
  }

  echo "</dl>";
  echo clear();
}

// Add EBAY coins
$ebaySearch = getEbaySearchString($coinType);

displayEbayResults($ebaySearch);

?>

