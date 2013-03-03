<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

include("categories.php"); 
?>

<h2>Coins By Type</h2>    

<?php

$denominations = getCoinDenominations();

echo "<dl>";

foreach ($denominations as $key => $row) {

  $id = get($row, 'id');
  $name = get($row, 'name');
  $dateRange = get($row, 'dateRange');
  $description = get($row, 'description');
  
  echo "<dt><a href='/us-coins?valueId=${id}'>$name</a><br /><br />";
  
  if ($dateRange != null) {
    echo "($dateRange)";
  }
  
  echo "</dt>";

  echo "<dd>";
  if ($description != "") {
      echo "<p>$description</p>";
  }
  echo "</dd>";

}

echo "</dl>";
echo clear();

?>

