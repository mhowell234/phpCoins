<?php

include("referenceBase.php");

$result = mysql_query($MINT_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['name'];
  $data[] = $row['symbol'];
  $data[] = $row['startYear'];
  $data[] = $row['endYear'];
  $data[] = $row['mid'];
  
  writeRefLine($data);
}

?>
