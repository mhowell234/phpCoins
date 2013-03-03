<?php

include("referenceBase.php");

$result = mysql_query($MINT_COIN_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['year'];
  $data[] = $row['symbol'];
  $data[] = $row['name'];
  $data[] = $row['denomination'];
  $data[] = $row['cyinfo'];
  $data[] = $row['mcinfo'];
  $data[] = $row['mcid'];
  
  writeRefLine($data);
}

?>
