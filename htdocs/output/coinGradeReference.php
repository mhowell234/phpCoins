<?php

include("referenceBase.php");

$result = mysql_query($COIN_GRADE_SQL);

while($row = mysql_fetch_array($result)) {
  
  $data = array();
  $data[] = $row['grade'];
  $data[] = $row['value'];
  $data[] = $row['srcid'];
  $data[] = $row['srsid'];
  
  writeRefLine($data);
}

?>