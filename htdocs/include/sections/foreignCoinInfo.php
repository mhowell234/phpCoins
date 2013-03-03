<?php

include_once('../init.php');
include_once("$DOCUMENT_ROOT/include/utils.php");

$mintCoinId = request('mintCoinId');

$mintCoin = getForeignMintCoin($mintCoinId);

$coinTitle = get($mintCoin, 'title');
$coinId = get($mintCoin, 'id');
$coinValueId = get($mintCoin, 'cvid');
$coinName = get($mintCoin, 'name');
$coinValue = get($mintCoin, 'value');
$coinDescription = get($mintCoin, 'description');
$numberMinted = get($mintCoin, 'numberMinted');
$km = get($mintCoin, 'km');

echo "<h2>$coinTitle</h2>";
echo clear();

$photos = getForeignImages($coinId);
echo displayImages($photos);

echo "<h3><a href='/foreign-coins?valueId=$coinValueId'>$coinValue</a> &raquo; <a href='/foreign-coins?typeId=$coinId'>$coinName</a></h3>";

$coinValueWrapper = getForeignCoinValuesAndTypes($mintCoinId);
$coinValues = get($coinValueWrapper, 'values');
$coinValueTypes = get($coinValueWrapper, 'types');

if (isset($km)) {
  echo "<p><b>KM# $km</b></p>";
}
?>

<p><b>Number Minted:</b> <?php echo num($numberMinted) ?></p>

<?php 

  echo displayDescription($coinDescription);
  echo clear();

  echo displayNumismaticValues($coinValueTypes, $coinValues); 

  $metals = getForeignCoinComposition($mintCoinId);
  echo displayMeltValues($metals);
  
  // Add EBAY coins
  $ebaySearch = getEbaySearchString($mintCoin);

  displayEbayResults($ebaySearch);

?>
