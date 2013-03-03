<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

$denominations = getCoinDenominations();
?>

<div id="coin-type-select"> 
  <select id='coinType' name='coinType'>
    <option value=''>-- Coin Type --</option>

<?php
foreach ($denominations as $key => $row) {
  echo "<option value='$row[id]'>$row[name]</option>";
}
?>
  </select>
</div>
