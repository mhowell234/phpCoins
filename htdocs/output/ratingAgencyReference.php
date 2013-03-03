<?php

include("referenceBase.php");

$result = mysql_query($RATING_AGENCY_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['name'];
  $data[] = $row['fullName'];
  $data[] = $row['raid'];
  
  writeRefLine($data);
}

?>


