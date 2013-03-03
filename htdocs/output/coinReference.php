<?php

include("referenceBase.php");

$result = mysql_query($COIN_SQL);

while($row = mysql_fetch_array($result)) {
  
  $data = array();
  $data[] = $row['name'];
  $data[] = $row['denomination'];
  $data[] = $row['cid'];
  
  writeRefLine($data);
}

?>