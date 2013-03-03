<?php

include("../include/init.php");

mysql_select_db("SearchDB");

mysql_query("DROP TABLE IF EXISTS Searchable");

mysql_query("CREATE TABLE Searchable (sid int(11) NOT NULL AUTO_INCREMENT, searchText text, stkid int(11) NOT NULL, uid int(11) NOT NULL, PRIMARY KEY (sid))");
mysql_query("ALTER TABLE Searchable ENGINE = MYISAM");
mysql_query("ALTER TABLE Searchable ADD FULLTEXT(searchText)");

$result = mysql_query("SELECT * FROM SearchableTableKey STK");

$keyTable = array();

while($row = mysql_fetch_array($result)) {

  $stkid = $row['stkid'];
  $tableName = $row['tableName'];
  $keyField = $row['keyField'];
  
  $keyTable[$tableName] = $stkid;
}


//print_r($keyTable);


$mintStkId = $keyTable['UsCoinDB.MintCoin'];
$foreignMintCoinStkId = $keyTable['ForeignCoinDB.MintCoin'];
$ourCoinStkId = $keyTable['OurCoinDB.OurCoin'];



// Add Searchable Text for UsCoinDB
//  MintCoin -- additionalInfo == id = mcid
//  CoinYear -- additionalInfo == linked to MintCoin
//  Coin -- name description  == linked to CoinYear
//  CoinValue -- name description == linked to Coin


$result = mysql_query("SELECT C.cid, C.name AS name, C.description AS description, CV.name AS coinName, CV.description AS coinDescription FROM UsCoinDB.Coin C, UsCoinDB.CoinValue CV WHERE C.cvid=CV.cvid");

while ($row = mysql_fetch_array($result)) {

  $name = $row['name'];
  $description = $row['description'];
  $coinName = $row['coinName'];
  $coinDescription = $row['coinDescription'];
  
  $searchText = "$name $coinName $description $coinDescription";

  $cid = $row['cid'];

  $SQL = "SELECT CY.year, CY.additionalInfo AS coinYearInfo, MC.additionalInfo AS mintCoinInfo FROM UsCoinDB.Coin C, UsCoinDB.CoinYear CY, UsCoinDB.MintCoin MC WHERE C.cid=$cid AND C.cid=CY.cid AND CY.cyid=MC.cyid";
  
  //print $SQL;
  $additionalResult = mysql_query($SQL);
  
  while ($aRow = mysql_fetch_array($additionalResult)) {
    $year = $aRow['year'];
    $mintCoinInfo = $aRow['mintCoinInfo'];
    $coinYearInfo = $aRow['coinYearInfo']; 
    
    $searchText .= " " . $year . " " . $mintCoinInfo . " " . $coinYearInfo;
  }
  
  $searchText = mysql_real_escape_string(addslashes($searchText));
  $searchText = str_replace("!!", ".", $searchText);
  $searchText = str_replace('\n', ' ', $searchText);
  
  $searchQuery = "INSERT INTO Searchable VALUES(NULL, '$searchText', $mintStkId, $cid)";
  
  print $searchQuery;
  mysql_query($searchQuery);  

}


// Add Searchable Text for ForeignCoinDB

//  MintCoin -- additionalInfo == id = mcid
//  CoinYear -- additionalInfo == linked to MintCoin
//  Coin -- name description  == linked to CoinYear
//  CoinValue -- name description == linked to Coin

$result = mysql_query("SELECT C.cid, C.name AS name, C.description AS description, CV.name AS coinName, CV.description AS coinDescription, FC.name AS country, FC.possessiveName AS possessiveName FROM ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV, ForeignCoinDB.ForeignCountry FC WHERE C.cvid=CV.cvid AND CV.fcid=FC.fcid");

while ($row = mysql_fetch_array($result)) {

  $name = $row['name'];
  $country = $row['country'];
  $possessiveName = $row['possessiveName'];
  $description = $row['description'];
  $coinName = $row['coinName'];
  $coinDescription = $row['coinDescription'];
  
  $searchText = "$name $country $possessiveName $coinName $description $coinDescription";

  $cid = $row['cid'];

  $SQL = "SELECT CY.year, CY.additionalInfo AS coinYearInfo, CY.km, MC.additionalInfo AS mintCoinInfo FROM ForeignCoinDB.Coin C, ForeignCoinDB.CoinYear CY, ForeignCoinDB.MintCoin MC WHERE C.cid=$cid AND C.cid=CY.cid AND CY.cyid=MC.cyid";
  
  //print $SQL;
  $additionalResult = mysql_query($SQL);
  
  while ($aRow = mysql_fetch_array($additionalResult)) {
    $year = $aRow['year'];
    $mintCoinInfo = $aRow['mintCoinInfo'];
    $coinYearInfo = $aRow['coinYearInfo']; 
    $km = $aRow['km'];
    
    $searchText .= " " . $year . " " . $mintCoinInfo . " " . $coinYearInfo;
    
    if (isset($km)) {
      $searchText .= " KM" . $km; 
    }
  }
  
  $searchText = mysql_real_escape_string(addslashes($searchText));
  $searchText = str_replace("!!", ".", $searchText);
  $searchText = str_replace('\n', ' ', $searchText);
  
  $searchQuery = "INSERT INTO Searchable VALUES(NULL, '$searchText', $foreignMintCoinStkId, $cid)";
  
  print $searchQuery;
  mysql_query($searchQuery);  

}


// Add Searchable Text for OurCoinDB.OurCoin
$result = mysql_query("SELECT A.ocid, A.mcid, A.origin AS coid FROM OurCoinDB.OurCoin A");

while ($row = mysql_fetch_array($result)) {
  
  $ocid = $row['ocid'];
  $mcid = $row['mcid'];
  $coid = $row['coid'];
  
  $SQL = "SELECT A.additionalInfo AS mintCoinInfo, B.additionalInfo AS coinYearInfo, B.year, C.name, C.description, D.name AS coinName, D.description AS coinDescription FROM UsCoinDB.MintCoin A, UsCoinDB.CoinYear B, UsCoinDB.Coin C, UsCoinDB.CoinValue D WHERE A.mcid=$mcid AND A.cyid=B.cyid AND B.cid=C.cid AND C.cvid=D.cvid";
  
  $coinResult = mysql_query($SQL);
  $coinRow = mysql_fetch_array($coinResult);
    
  $mintCoinInfo = $coinRow['mintCoinInfo'];
  $coinYearInfo = $coinRow['coinYearInfo'];
  $year = $coinRow['year'];
  $name = $coinRow['name'];
  $description = $coinRow['description'];
  $coinName = $coinRow['coinName'];
  $coinDescription = $coinRow['coinDescription'];
  
  
  $OSQL = "SELECT A.name AS origin FROM CommonDB.CoinOrigin A WHERE A.coid=$coid";
  $originResult = mysql_query($OSQL);
  $originRow = mysql_fetch_array($originResult);
  
  $origin = $originRow['origin'];
  
  
  $searchText = "$name $coinName $year $origin $description $coinDescription $mintCoinInfo $coinYearInfo";
  $searchText = mysql_real_escape_string(addslashes($searchText));
  $searchText = str_replace("!!", ".", $searchText);
  $searchText = str_replace('\n', ' ', $searchText);

  $searchQuery = "INSERT INTO Searchable VALUES(NULL, '$searchText', $ourCoinStkId, $ocid)";
  print $searchQuery;
  mysql_query($searchQuery);  
}

?>

