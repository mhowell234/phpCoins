<?php


include_once("$DOCUMENT_ROOT/include/utils.php");

$mintCoinId = request('mintCoinId');

$mintCoin = getMintCoin($mintCoinId);

$coinTitle = get($mintCoin, 'title');
$coinId = get($mintCoin, 'id');
$coinName = get($mintCoin, 'name');
$coinValue1 = get($mintCoin, 'value');
$coinValueId = get($mintCoin, 'cvid');

$coinDescription = get($mintCoin, 'description');
$numberMinted = get($mintCoin, 'numberMinted');

echo "<h2>$coinTitle</h2>";
echo clear();

$photos = getImages($coinId);
$thumbs = getThumbnails($coinId);

echo displayImages($thumbs);

echo "<h3><a href='/us-coins?valueId=$coinValueId'>$coinValue1</a> &raquo; <a href='/us-coins?typeId=$coinId'>$coinName</a></h3>";

$coinValueWrapper = getCoinValuesAndTypes($mintCoinId);
$coinValues = get($coinValueWrapper, 'values');
$coinValueTypes = get($coinValueWrapper, 'types');

?>

<p><b>Number Minted:</b> <?php echo num($numberMinted) ?></p>

<?php 

  echo displayDescription($coinDescription);
  echo clear();

  echo displayNumismaticValues($coinValueTypes, $coinValues); 

  $metals = getCoinComposition($mintCoinId);
  echo displayMeltValues($metals);

  // Add EBAY coins
  $ebaySearch = getEbaySearchString($mintCoin);

  displayEbayResults($ebaySearch);

?>
