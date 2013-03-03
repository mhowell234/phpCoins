<?php
  $navsection = 'tracker';

  include_once('../include/init.php');
  include_once("$DOCUMENT_ROOT/include/utils.php");
  
  $trackerId = request('trackerId');

  headerTitleMenu("Coin Trackers");
   
  if ($trackerId) { 
    echo '<section id="tracker"></section>';
  }
  else { 
    echo '<h2>Current Trackers</h2>';
    section('tracker-list', 'trackerList', true);
  } 
   
  endHeaderTitleMenu();
    
  include("$DOCUMENT_ROOT/include/html/footer.php");
?>
