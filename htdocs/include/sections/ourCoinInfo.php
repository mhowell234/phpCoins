<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

$ocid = request('id');

$ourCoin = getOurMintCoin($ocid);

$coinTitle = get($ourCoin, 'title');
$coinId = get($ourCoin, 'id');
$coinName = get($ourCoin, 'name');
$coinValue1 = get($ourCoin, 'value');
$coinValueId = get($ourCoin, 'cvid');
$mintCoinId = get($ourCoin, 'mcid');
$coinDescription = get($ourCoin, 'description');
$numberMinted = get($ourCoin, 'numberMinted');
$origin = get($ourCoin, 'origin');
$originDate = get($ourCoin, 'originDate');
$pricePaid = get($ourCoin, 'pricePaid');

echo "<h2>$coinTitle</h2>";

$photos = getOurImages($ocid);
$thumbs = getOurThumbnails($ocid);
echo displayImages($thumbs);

echo "<h3><a href='/us-coins?valueId=$coinValueId'>$coinValue1</a> &raquo; <a href='/us-coins?typeId=$coinId'>$coinName</a></h3>";

$coinValueWrapper = getOurCoinValuesAndTypes($ocid);
$coinValues = get($coinValueWrapper, 'values');
$coinValueTypes = get($coinValueWrapper, 'types');

$coinRatings = getOurCoinRatings($ocid);
?>

<p><b>Number Minted:</b> <?php echo $numberMinted ?></p>
<p><b>Bought From:</b> 
  <?php echo $origin ?> on <?php echo $originDate ?>
</p>
<p><b>Price Paid:</b> <?php echo $pricePaid ?></p>

<?php

  echo displayDescription($coinDescription);

  echo displayRatingValues($coinRatings);
 
  echo displayNumismaticValues($coinValueTypes, $coinValues); 

  $metals = getCoinComposition($mintCoinId);
  echo displayMeltValues($metals);
 
  // Add EBAY coins
  $ebaySearch = getEbaySearchString($ourCoin);

  displayEbayResults($ebaySearch);
 
?>

