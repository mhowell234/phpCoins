<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

$typeId = request('typeId');

$coinInfo = getForeignCoin($typeId);

$coinId = get($coinInfo, 'id');
$coinName = get($coinInfo, 'name');
$coinValue1 = get($coinInfo, 'value');
$dateRange = get($coinInfo, 'dateRange');
$description = get($coinInfo, 'description');
$country = get($coinInfo, 'country');

echo "<h2>$coinName - $coinValue1 <span class='header-float'>$dateRange</span></h2>";

$thumbs = getForeignThumbnails($coinId);
echo displayImages($thumbs);

if ($description != "") {
  echo "<p class='expandable'>$description</p>";
}

// Get coin years
$coins = getForeignCoinYearsInfo($typeId);

// Get coin ratings
$coinValueTypes = getForeignCoinYearRatingScale($typeId);

echo displayCoinYears($coins, $coinValueTypes);

echo clear();

// Add EBAY coins
$ebaySearch = getEbaySearchString($coinInfo);

displayEbayResults($ebaySearch);


?>
