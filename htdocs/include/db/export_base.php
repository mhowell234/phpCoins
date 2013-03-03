<?php

$EXPORT_DB_DIR = "/Users/mhowell/Dropbox/htdocs/db_export";

$COMMON_DB_DIR = "$EXPORT_DB_DIR/CommonDB";
$US_COIN_DB_DIR = "$EXPORT_DB_DIR/UsCoinDB";
$OUR_COIN_DB_DIR = "$EXPORT_DB_DIR/OurCoinDB";
$FOREIGN_COIN_DB_DIR = "$EXPORT_DB_DIR/ForeignCoinDB";
$FOREIGN_COINS_DB_DIR = "$FOREIGN_COIN_DB_DIR/countries";

$TRACKER_DB_DIR = "$EXPORT_DB_DIR/TrackerDB";

$US_MINT_COIN_DB_DIR = "$US_COIN_DB_DIR/mintCoin";

// CommonDB files
$PRECIOUS_METAL_FILE = "precious_Metal.txt";
$SHELDON_RATING_SCALE_FILE = "sheldon_Rating_Scale.txt";
$SHELDON_RATING_CATEGORY_FILE = "sheldon_Rating_Category.txt";
$RATING_AGENCY_FILE = "rating_Agency.txt";
$COIN_ORIGIN_FILE = "coin_Origin.txt";
// UsCoinDB files
$MINT_FILE = "mint.txt";
$MINT_DATE_FILE = "mint_Date.txt";
$COIN_FILE = "coin.txt";
$COIN_ATTRIB_FILE = "coin_Attrib.txt";
$COIN_VALUE_FILE = "coin_Value.txt";
$COIN_VALUE_ATTRIB_FILE = "coin_Value_Attrib.txt";
$COIN_YEAR_FILE = "coin_Year.txt";
$COIN_METAL_COMPOSITION_FILE = "coin_Metal_Composition.txt";
// OurCoinDB files
$OUR_COIN_FILE = "our_Coin.txt";
// ForeignCoinDB files
$FOREIGN_COUNTRY_FILE = "foreign_Country.txt";
// TrackerDB files
$TRACKER_FILE = "tracker_Search.txt";

// CommonDB headers
$PRECIOUS_METAL_HEADER_LINE = "NAME|SYMBOL|UNIT|CONVERSION_FACTOR";
$SHELDON_RATING_SCALE_HEADER_LINE = "TITLE|CATEGORY|VALUE|DESCRIPTION";
$SHELDON_RATING_CATEGORY_HEADER_LINE = "CATEGORY|NAME|START_VALUE|END_VALUE|ALT_TYPE";
$RATING_AGENCY_HEADER_LINE = "NAME|FULL_NAME";
$COIN_ORIGIN_HEADER_LINE = "NAME";
// UsCoinDB headers
$MINT_HEADER_LINE = "MINT|SYMBOL|ALWAYS_PRESENT|COMMENTS";
$MINT_DATE_HEADER_LINE = "MINT|START_YEAR|END_YEAR";
$COIN_HEADER_LINE = "NAME|TYPE|START_YEAR|END_YEAR|DESCRIPTION";
$COIN_ATTRIB_HEADER_LINE = "NAME|TYPE|ATTRIB_TYPE|VALUE";
$COIN_VALUE_HEADER_LINE = "NAME|VALUE|DESCRIPTION";
$COIN_VALUE_ATTRIB_HEADER_LINE = "NAME|ATTRIB_TYPE|VALUE";
$COIN_YEAR_HEADER_LINE = "COIN|DENOMINATION|YEAR|ADDITIONAL_INFO|IS_GOLD|IS_SILVER";

$COIN_METAL_COMPOSITION_HEADER_LINE = "NAME|DENOMINATION|YEAR|ADDITIONAL_INFO|COIN_TYPE_INFO|WEIGHT_IN_GRAMS|SILVER|COPPER|NICKEL|MANGANESE|GOLD|ZINC";
// OurCoinDB headers
$OUR_COIN_HEADER_LINE = "YEAR|NAME|RATING_CATEGORY|RATING_VALUE|RATING_AGENCY|IS_SILVER|IS_GOLD|IS_PROOF|DENOMINATION|NOTES|PRICE_PAID|IS_WRAPPED|ORIGIN|ORIGIN_DATE|COIN_TYPE|YEAR_TYPE";
// ForeignCoinDB headers
$FOREIGN_COUNTRY_HEADER_LINE = "NAME|DESCRIPTION|POSSESSIVE_NAME|ABBREVIATION";
$FOREIGN_COIN_YEAR_HEADER_LINE = "COIN|DENOMINATION|YEAR|ADDITIONAL_INFO|KM_NUMBER|IS_GOLD|IS_SILVER";
$FOREIGN_MINT_HEADER_LINE = "MINT|SYMBOL|ALWAYS_PRESENT|COMMENTS|COUNTRY";
// TrackerDB headers
$TRACKER_HEADER_LINE = "NAME|DESCRIPTION|VALUE|COIN|YEAR|YEAR_INFO|START_YEAR|END_YEAR|MINT|COIN_INFO|MIN_PRICE|MAX_PRICE|DISCOUNT_PERCENTAGE|PREMIUM_PERCENTAGE|AUCTION_END_TIME|SELLER_RATING|RATING_AGENCY|GRADE_CATEGORY|GRADE|IS_BUY_IT_NOW|PHRASE_TO_ADD|PHRASE_TO_REMOVE|EMAILS";


//
// Exports data for given ids by executing $functionName on each
//
function export($ids, $functionName, $silent=false) {
  $first = true;
  
  foreach ($ids as $id) {
    $overwrite = false;
    if ($first) $overwrite = true;
    
    $output = $functionName($id, $overwrite);
    
    if (!$silent) {
      print_r($output);
      echo "<br /><br />";
    }
    
    $first = false;
  }

}


//
// Writes DB $data to a file ($dir/$file)
// If $overwrite then create new file and add the $header line first
//
function writeDBData($dir, $file, $header, $data, $overwrite) {

  global $EXPORT_DB_DIR;

  ensure_dir($EXPORT_DB_DIR);
  ensure_dir($dir);
    
  $fileName = "$dir/$file";

  $fh = null;
  if ($overwrite) {
    $fh = fopen("$fileName", 'w');
    
    fwrite($fh, $header . "\n");
  }
  else {
    $fh = fopen("$fileName", 'a');
  }
  
  $stringData = "\n" . implode("|", $data) . "\n";
  fwrite($fh, $stringData);

  fclose($fh);
}


//
// Export all data..call $func_all first and then for each result
// call $func_each
//
function exportAll($func_all, $func_each, $silent=false) {
  $values = $func_all();
  
  $ids = array();
  
  foreach($values as $value) {
    $ids[] = $value['id'];
  }
  
  export($ids, $func_each, $silent);
}


?>