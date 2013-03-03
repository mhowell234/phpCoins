<?php

include("referenceBase.php");

$result = mysql_query($COIN_GRADE_CATEGORY_SQL);

while($row = mysql_fetch_array($result)) {
  
  $data = array();
  $data[] = $row['category'];
  $data[] = $row['srcid'];
  
  writeRefLine($data);
}

?>