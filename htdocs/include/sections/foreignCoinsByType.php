<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

$valueId = request('valueId');

$coinType = getForeignCoins($valueId);
$coinName = get($coinType, 'name');
$description = get($coinType, 'description');
$country = get($coinType, 'country');

$coins = get($coinType, 'list');

echo "<h2>$coinName</h2>";

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
    
    $photos = getForeignImages($coinId);

    echo "<dt><p><a href='/foreign-coins?typeId=$coinId'>$name</a></p><p>($dateRange)</p></dt>";
  
    echo "<dd>";
    if (count($photos) > 0) {
      echo displayImages($photos, 100);
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

