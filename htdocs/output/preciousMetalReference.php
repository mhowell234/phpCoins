<?php

include("referenceBase.php");

$result = mysql_query($PRECIOUS_METAL_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['name'];
  $data[] = $row['pmid'];
  
  writeRefLine($data);
}

?>


