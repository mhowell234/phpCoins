<?php

include("referenceBase.php");

$result = mysql_query($RATING_VALUE_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['title'];
  $data[] = $row['value'];
  $data[] = $row['srsid'];
  
  writeRefLine($data);
}

?>
