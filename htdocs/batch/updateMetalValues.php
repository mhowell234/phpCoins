<?php

include("../include/init.php");

mysql_select_db("CommonDB");

$result = mysql_query("SELECT * FROM PreciousMetal PM");

$metals = array();

while($row = mysql_fetch_array($result)) {
  $name = strtolower($row['name']);
  $pmid = $row['pmid'];
  $conversionFactor = $row['conversionFactor'];
  
  $data = array();
  $data[] = $name;
  $data[] = $pmid;
  $data[] = $conversionFactor;
  
  echo implode("|", $data) . "<br />";
  
  $info = array();
  
  $info['id'] = $pmid;
  $info['conversion'] = $conversionFactor;
  
  $metals[$name] = $info;
}

$handle = fopen("/Users/mhowell/Dropbox/numismatics/DB/CommonDB/metalPrices.txt", "r");


if ($handle) {
 while (($buffer = fgets($handle, 4096)) !== false) {
 
   $pInfo = explode('|', $buffer);
   
   $pName = strtolower($pInfo[0]);
   $pPrice = $pInfo[1];
   $pUnitPrice = $pInfo[4];
   
   $pmid = $metals[$pName]['id'];
   
   $UPDATE_SQL = "UPDATE PreciousMetal SET pricePerUnit=$pPrice, pricePerGram=$pUnitPrice WHERE pmid=$pmid";
   
   mysql_query($UPDATE_SQL);
   echo $UPDATE_SQL;
 }
 if (!feof($handle)) {
   echo "Error: unexpected fgets() fail\n";
 }
 fclose($handle);
}

?>


