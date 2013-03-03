<?php

include("referenceBase.php");

$result = mysql_query($RATING_CATEGORY_SQL);

while($row = mysql_fetch_array($result)) {

  $data = array();
  $data[] = $row['title'];
  $data[] = $row['start'];
  $data[] = $row['end'];
  $data[] = $row['srcid'];
  
  writeRefLine($data);
}

?>


