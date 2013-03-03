<?php
  $navsection = 'foreign-coins';

  include_once('../include/init.php');
  include_once("$DOCUMENT_ROOT/include/utils.php");
  
  $fcid = request('fcid');
  $valueId = request('valueId');
  $typeId = request('typeId');
  $mintCoinId = request('mintCoinId');

  headerTitleMenu("Foreign Coins");

  if ($fcid) {  
    section('coin-types', 'foreignCoinsByCountry', true);
  } 
  else if ($valueId) { 
    section('coin-types', 'foreignCoinsByType', true);
  }
  else if ($typeId) { 
    section('coin-type-years', 'foreignCoinTypeByYear', true);
  }
  else if ($mintCoinId) {
    section('mint-coin-info', 'foreignCoinInfo', true);
  }
  else { 
    section('our-coins', 'countries', true);  
  } 
  
  endHeaderTitleMenu();
  
  include("$DOCUMENT_ROOT/include/html/footer.php");
?>
