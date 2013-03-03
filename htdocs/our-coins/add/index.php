<?php
  $navsection = 'our-coins';

include_once('../../include/init.php');
include_once("$DOCUMENT_ROOT/include/utils/core.php");
include_once("$DOCUMENT_ROOT/include/utils/sql.php");
include_once("$DOCUMENT_ROOT/include/utils/db.php");

   

$coinValue = '';
$coinType = '';
$coinYear = '';
$coinMintYear = '';
$pricePaid = '';
$ratingAgency = '';
$gradeCategory = '';
$grade = '';
$origin = '';
$originDate = '';
$isWrapped = '';
$isProof = '';
$notes = '';

$error = '';
$errorRow = '';

$formSubmitted = request_post('formSubmitted');

if ($formSubmitted) {
 
  $coinValue = request_post('coinValue');
  $coinType = request_post('coinType');
  $coinYear = request_post('coinYear');
  $coinMintYear = request_post('coinMintYear');
  $pricePaid = request_post('pricePaid');
  $ratingAgency = request_post('ratingAgency');
  $gradeCategory = request_post('gradeCategory');
  $grade = request_post('grade');
  $origin = request_post('origin');
  $originDate = request_post('originDate');
  $isWrapped = request_post('isWrapped');
  $isProof = request_post('isProof');
  $notes = request_post('notes');
  
  if ($coinValue == '') {
    $error = "You must select a denomination (Quarter, Half Dollar, etc)";
    $errorRow = 'coinValue';
  }  
}

if ($formSubmitted && $error == '') {
  
   // Create dict of values and call addOurCoin() util method
   $coin = array();
   
   $coin['coinValue'] = $coinValue;
   $coin['coinType'] = $coinType;
   $coin['coinYear'] = $coinYear;
   $coin['coinMintYear'] = $coinMintYear;
   $coin['pricePaid'] = $pricePaid;
   $coin['ratingAgency'] = $ratingAgency;
   $coin['gradeCategory'] = $gradeCategory;
   $coin['grade'] = $grade;
   $coin['origin'] = $origin;
   $coin['originDate'] = $originDate;
   $coin['isWrapped'] = $isWrapped;
   $coin['isProof'] = $isProof;
   $coin['notes'] = $notes;
   
   addOurCoin($coin);

   return header("Location: /our-coins?success=Added Our Coin successfully!");
   
}   

  $coinId = request('ourCoinId');

  include("$DOCUMENT_ROOT/include/html/header.php");

?>

  <div id="main-wrapper">
   <?php include("$DOCUMENT_ROOT/include/html/searchSection.php"); ?>

   <h1>Our Coins</h1>
   <?php include("$DOCUMENT_ROOT/include/html/menu.php"); ?>
   

   
<?php if ($ourCoinId) { ?>
   <section id="ourCoin">
   </section>
   
<?php } else { ?>

   <h2>Add Coin For Us</h2>
   
<?php
   section('ourcoin-add', 'ourCoinAdd', true);
  }
?>
   
    <div class="visualClear"></div>
  </div>
  
<?php include("$DOCUMENT_ROOT/include/html/footer.php"); ?>

<script type="text/javascript" src="/js/ourCoinAdd.js"></script>
