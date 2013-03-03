<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

$trackerId = request('trackerId');

// Get tracker info
$trackerInit = getTrackerById($trackerId);

$tracker = convertTracker($trackerInit);

$coinMinWorth = getMinCoinWorth($trackerInit);
$coinMaxWorth = getMaxCoinWorth($trackerInit);

$tracker['minWorth'] = $coinMinWorth;
$tracker['maxWorth'] = $coinMaxWorth;

$name = get($tracker, 'name');
$description = get($tracker, 'description');
$cvid = get($tracker, 'cvid');
$cid = get($tracker, 'cid');
$value = get($tracker, 'value');
$coin = get($tracker, 'coin');
$year = get($tracker, 'year');
$startYear = get($tracker, 'startYear');
$endYear = get($tracker, 'endYear');
$coinMintSymbol = get($tracker, 'coinMintSymbol');
$coinMint = get($tracker, 'coinMint');
$mintSymbol = get($tracker, 'mintSymbol');
$mint = get($tracker, 'mint');
$gradeCategory = get($tracker, 'gradeCategory');
$grade = get($tracker, 'grade');
$minPrice = get($tracker, 'minPrice');
$maxPrice = get($tracker, 'maxPrice');
$discountPercentage = get($tracker, 'discountPercentage');
$premiumPercentage = get($tracker, 'premiumPercentage');
$auctionEndTime = get($tracker, 'auctionEndTime');
$ratingAgency = get($tracker, 'ratingAgency');
$sellerRating = get($tracker, 'sellerRating');
$isBuyItNow = get($tracker, 'isBuyItNow');
$phrasesToAdd = get($tracker, 'phrasesToAdd');
$phrasesToRemove = get($tracker, 'phrasesToRemove');
$emails = get($tracker, 'emails');

if (isset($discountPercentage) && isset($coinMinWorth)) {
  $discountValue = getDiscountedValue($coinMinWorth, $discountPercentage);
}
if (isset($premiumPercentage) && isset($coinMinWorth)) {
  $premiumValue = getDiscountedValue($coinMinWorth, "-${premiumPercentage}");
}

echo "<h2>$name</h2>";

if ($description != '') {
  echo "<p>$description</p>";
}
echo "<br />";

echo "<h3><a href='/tracker/view?trackerId=$trackerId&sendEmail=1'>&raquo; Send Tracker Email</a></h3>";

$api = createEbayTrackerSearchURL($trackerInit);

echo "<h3><a href='$api' target='_blank'>&raquo; View Tracker URL</a></h3>";


echo "<div class='section'>";
echo "<h3 class='heading down-arrow'>Attributes</h3>";
echo "<div class='content'>";
echo "<table id='tracker-attribs'>";
echo "<caption></caption>";
echo "<thead><tr><th scope='col'>Attribute</th><th scope='col'>Value</th></tr></thead><tbody>";


addTableRow("Denomination", "<a href='/us-coins/?valueId=$cvid'>$value</a>");
if ($coin != null) {
  addTableRow("Coin", "<a href='/us-coins/?typeId=$cid'>$coin</a>");
}
addTableRow("Year", $year);
addTableRow("Start Year", $startYear);
addTableRow("End Year", $endYear);

if ($coinMint != null) {
  addTableRow("Mint", "$coinMint ($coinMintSymbol)");
}
if ($mint != null) {
  addTableRow("Mint", "$mint ($mintSymbol)");
}

addTableRow("Grade Category", $gradeCategory);
addTableRow("Grade", $grade);

if ($minPrice != null) {
  $minPrice = money($minPrice);
  addTableRow("Minimum Price", $minPrice);
}
if ($maxPrice != null) {
  $maxPrice = money($maxPrice);
  addTableRow("Maximum Price", $maxPrice);
}
if ($discountPercentage != null) {
  $discountValue = money($discountValue);
  $discountPercentage = "${discountPercentage}% (${discountValue})";
  addTableRow("Discount Percentage", $discountPercentage);
}
if ($premiumPercentage != null) {
  $premiumValue = money($premiumValue);
  $premiumPercentage = "${premiumPercentage}% (${premiumValue})";
  addTableRow("Premium Percentage", $premiumPercentage);
}
if ($coinMinWorth != null) {
  $coinMinWorth = money($coinMinWorth);
  addTableRow("Our Min Worth", $coinMinWorth);
}
if ($coinMaxWorth != null) {
  $coinMaxWorth = money($coinMaxWorth);
  addTableRow("Our Max Worth", $coinMaxWorth);
}

if ($auctionEndTime != null) {
  $timeLeft = time() + ($auctionEndTime * 60);
  $timeLeft = formatTimeLeft($timeLeft);
  addTableRow("Auction End", $timeLeft);
}

addTableRow("Rating Agency", $ratingAgency);
addTableRow("Seller Rating", $sellerRating);
addTableRow("Is Buy It Now?", $isBuyItNow);

if (sizeof($phrasesToAdd) > 0) {
  $phrasesToAdd = implode("<br />", $phrasesToAdd);
  addTableRow("Phrases To Add", $phrasesToAdd);
}
if (sizeof($phrasesToRemove) > 0) {
  $phrasesToRemove = implode("<br />", $phrasesToRemove);
  addTableRow("Phrases To Remove", $phrasesToRemove);
}
if (sizeof($emails) > 0) {
  $emails = implode("<br />", $emails);
  addTableRow("Email Addresses", $emails);
}

echo "</tbody></table>";
echo "</div>";
echo "</div>";

$id = get($tracker, 'id');

displayTrackerResults($tracker);

?>

