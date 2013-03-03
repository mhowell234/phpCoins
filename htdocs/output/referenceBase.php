<?php

include("../include/init.php");
include("$DOCUMENT_ROOT/include/sql/reference.php");

function writeRefLine($data) {
  echo implode("|", $data) . "<br />";
}

?>
