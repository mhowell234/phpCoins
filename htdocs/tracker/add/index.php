<?php
  $navsection = 'tracker';

include_once('../../include/init.php');
include_once("$DOCUMENT_ROOT/include/db/db.php");
include_once("$DOCUMENT_ROOT/include/utils.php");

$name = '';
$description = '';
$coinValue = '';
$coinType = '';
$coinYearStart = '';
$coinYearEnd = '';
$coinYear = '';
$coinMintYear = '';
$coinMint = '';
$minPrice = '';
$maxPrice = '';
$discountPercentage = '';
$premiumPercentage = '';
$auctionEndDay = '';
$auctionEndHour = '';
$auctionEndMinute = '';
$gradeCategory = '';
$grade = '';
$sellerMinRating = '';
$ratingAgency = '';
$isBuyItNow = '';
$phrasesToAdd = '';
$phrasesToRemove = '';
$emails = '';

$error = '';
$errorRow = '';

$formSubmitted = request_post('formSubmitted');

if ($formSubmitted) {
 
  $name = request_post('name');
  $description = request_post('description');
  $coinValue = request_post('coinValue');
  $coinType = request_post('coinType');

  $coinYearStart = request_post('coinYearStart');
  $coinYearEnd = request_post('coinYearEnd');
  $coinYear = request_post('coinYear');
  $coinMintYear = request_post('coinMintYear');
  $coinMint = request_post('coinMint');
  
  $minPrice = request_post('minPrice');
  $maxPrice = request_post('maxPrice');
  $discountPercentage = request_post('discountPercentage');
  $premiumPercentage = request_post('premiumPercentage');
  $auctionEndDay = request_post('auctionEndDay');
  $auctionEndHour = request_post('auctionEndHour');
  $auctionEndMinute = request_post('auctionEndMinute');
  $gradeCategory = request_post('gradeCategory');
  $grade = request_post('grade');
  $sellerMinRating = request_post('sellerMinRating');
  
  $ratingAgency = request_post('ratingAgency');
  $isBuyItNow = request_post('isBuyItNow');
  
  $phrasesToAdd = request_post('phrasesToAdd');
  $phrasesToRemove = request_post('phrasesToRemove');
  $emails = request_post('emails');
  
  if ($name == '') {
    $error = "You must enter a name for this tracker.";
    $errorRow = 'name';
  }
  else if ($coinValue == '') {
    $error = "You must select a denomination (Quarter, Half Dollar, etc)";
    $errorRow = 'coinValue';
  }  
}

if ($formSubmitted && $error == '') {
  
   // Create dict of values and call addTracker() util method
   $tracker = array();
   
   $tracker['name'] = $name;
   $tracker['description'] = $description;
   $tracker['coinValue'] = $coinValue;
   $tracker['coinType'] = $coinType;
   $tracker['coinYearStart'] = $coinYearStart;
   $tracker['coinYearEnd'] = $coinYearEnd;
   $tracker['coinYear'] = $coinYear;
   $tracker['coinMintYear'] = $coinMintYear;
   $tracker['coinMint'] = $coinMint;
   $tracker['minPrice'] = $minPrice;
   $tracker['maxPrice'] = $maxPrice;
   $tracker['discountPercentage'] = $discountPercentage;
   $tracker['premiumPercentage'] = $premiumPercentage;
   $tracker['auctionEndDay'] = $auctionEndDay;
   $tracker['auctionEndHour'] = $auctionEndHour;
   $tracker['auctionEndMinute'] = $auctionEndMinute;
   $tracker['gradeCategory'] = $gradeCategory;
   $tracker['grade'] = $grade;
   $tracker['sellerMinRating'] = $sellerMinRating;
   $tracker['ratingAgency'] = $ratingAgency;
   $tracker['isBuyItNow'] = $isBuyItNow;
   $tracker['phrasesToAdd'] = $phrasesToAdd;
   $tracker['phrasesToRemove'] = $phrasesToRemove;
   $tracker['emails'] = $emails;
   
   addTracker($tracker);
   
   //print "redirecting to /tracker?msg=Added $name Tracker successfully!";
   return header("Location: /tracker?success=Added $name Tracker successfully!");
   
   //echo "<p class='success'>Added $name Tracker successfully!</p>";
}   

  $trackerId = request('trackerId');

  include("$DOCUMENT_ROOT/include/html/header.php");

?>

  <div id="main-wrapper">
   <?php include("$DOCUMENT_ROOT/include/html/searchSection.php"); ?>

   <h1>Coin Trackers</h1>
   <?php include("$DOCUMENT_ROOT/include/html/menu.php"); ?>
   

   
<?php if ($trackerId) { ?>
   <section id="tracker">
   </section>
   
<?php } else { ?>

   <h2>Add a Tracker</h2>
   
<?php
   section('tracker-add', 'trackerAdd', true);
  }
?>
   
    <div class="visualClear"></div>
  </div>
  
<?php include("$DOCUMENT_ROOT/include/html/footer.php"); ?>

<script type="text/javascript" src="/js/tracker.js"></script>
