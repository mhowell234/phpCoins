<?php
  $navsection = 'home';

  include("include/init.php");
  include_once("$DOCUMENT_ROOT/include/utils.php");  

  headerTitleMenu("Our Coin Awesomeness");
  
  section('recent-coins', 'coinsByDenominations', true);   

  endHeaderTitleMenu();
   
  include_once("$DOCUMENT_ROOT/include/html/footer.php");
  
?>
