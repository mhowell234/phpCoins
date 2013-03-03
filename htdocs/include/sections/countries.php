<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

$countries = getForeignCountries();

?>

<h2>Countries</h2>
     
<p>Click on a country below to see coins from that country.</p>

<?php

if (count($countries) > 0) {
  echo "<ul>";
  
  foreach ($countries as $country) {
    $fcid = get($country, 'id');
    $name = get($country, 'name');
    
    echo "<li><a href='/foreign-coins?fcid=$fcid'>$name</a></li>";
  }

  echo "</ul>"; 
}

//displayEbayResults("Eagle");
?>
