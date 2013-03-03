<?php
  $navsection = 'our-coins';

  include_once('../include/init.php');
  include_once("$DOCUMENT_ROOT/include/utils.php");
  
  $ocid = request('id');

  headerTitleMenu("Our Coins");

  if (!$ocid) { 
    section('our-coins', 'ourCoins', true);
  } 
  else {
    section('coin-info', 'ourCoinInfo', true);
  } 
   
  endHeaderTitleMenu();
  
  include("$DOCUMENT_ROOT/include/html/footer.php");
?>
