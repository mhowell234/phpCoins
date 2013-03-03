<?php

include("referenceBase.php");

$result = mysql_query($MINT_COIN_VALUE_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['year'];
  $data[] = $row['mcid'];
  $data[] = $row['value'];
  $data[] = $row['name'];
  $data[] = $row['shortName'];
  $data[] = $row['title'];
  $data[] = $row['mcvid'];
  
  writeRefLine($data);
}

?>
