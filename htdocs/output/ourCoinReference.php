<?php

include("referenceBase.php");

$result = mysql_query($OUR_COIN_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['year'];
  $data[] = $row['symbol'];
  $data[] = $row['name'];
  $data[] = $row['denomination'];
  $data[] = $row['cyinfo'];
  $data[] = $row['mcinfo'];
  $data[] = $row['pricePaid'];
  $data[] = $row['originDate'];
  $data[] = $row['mcid'];
  $data[] = $row['ocid'];
  
  writeRefLine($data);
}

?>
