<?php

$ourCoins = convertOurUSCoins();

if (count($ourCoins) > 0) { 

  echo '<h2>Our Coins</h2>';
  echo '<p>Click on one of our coins below to see more information about it.</p>';

  echo "<h3><a href='/our-coins/add'>&raquo; Add a Coin</a></h3>";

  statusDisplay();

  foreach($ourCoins as $coinDenom => $coinTypes) {

    echo "<div class='section'>";
    echo "<h3 class='heading down-arrow'>$coinDenom</h3>";
    echo "<div class='content'>";
    
    $first = true;
    foreach($coinTypes as $type => $coins) {
      echo "<dl class='normal-dl";
      
      if ($first) {
        echo " first";
        $first = false;
      }
      echo "'>";

      echo "<dt><h3>$type</h3></dt>";
      echo "<dd><ul>";
      foreach($coins as $coin) {
        $id = get($coin, 'id');
        $year = get($coin, 'year');
        $symbol = get($coin, 'symbol');
        
        echo "<li><a href='/our-coins?id=${id}'>${year}${symbol}</a></li>";
      }
      echo "</ul></dd>";
      echo clear();
      echo "</dl>";
    }
    echo "</div>";
    echo "</div>";
  }

}

?>

