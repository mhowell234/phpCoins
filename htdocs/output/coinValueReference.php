<?php

include("referenceBase.php");

$result = mysql_query($COIN_VALUE_SQL);

while($row = mysql_fetch_array($result)) {
  
  $data = array();
  $data[] = $row['denomination'];
  $data[] = $row['cvid'];
  
  writeRefLine($data);
}

?>
