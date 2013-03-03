<?php

include("referenceBase.php");

$result = mysql_query($COIN_YEAR_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['year'];
  $data[] = $row['name'];
  $data[] = $row['denomination'];
  $data[] = $row['cyinfo'];
  $data[] = $row['cyid'];
  
  writeRefLine($data);
}

?>
