<?php

include("referenceBase.php");

$result = mysql_query($FOREIGN_COUNTRY_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['name'];
  $data[] = $row['abbreviation'];
  $data[] = $row['fcid'];
  
  writeRefLine($data);
}

?>


