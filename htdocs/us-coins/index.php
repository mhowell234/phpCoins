<?php
  $navsection = 'us-coins';

  include_once('../include/init.php');
  include_once("$DOCUMENT_ROOT/include/utils.php");
  
  $valueId = request('valueId');
  $typeId = request('typeId');
  $mintCoinId = request('mintCoinId');

  headerTitleMenu("U.S. Coins");

  if ($valueId) { 
    section('coin-types', 'coinsByType', true);
  }
  else if ($typeId) { 
    section('coin-type-years', 'coinTypeByYear', true);
  }
  else if ($mintCoinId) {
    section('mint-coin-info', 'coinInfo', true);
  }
  else { 
    section('coin-types', 'coinsByDenominations', true);
  }

  endHeaderTitleMenu();

  include("$DOCUMENT_ROOT/include/html/footer.php");
?>
