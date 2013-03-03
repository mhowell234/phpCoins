<?php

//
// Ensures a path exists
// If not, creates it
//
function ensure_dir($path) {
  if (!is_dir($path)) {
    mkdir($path);
  }    
}

//
// Gets a value from a hash
// Return NULL if not in the hash
//
function get($hash, $key) {
  if (!array_key_exists($key, $hash)) {
    return NULL;
  }
  
  return $hash[$key];
}


//
// Gets a request variable
// -1 if the var isn't set
//
function request($name, $defaultValue='') {
  $nameVar = NULL;
  if (isset($_REQUEST[$name])) {

    if ($defaultValue != '') {
      $nameVar = $defaultValue;
    }
    else {
      $nameVar = $_REQUEST[$name];
    }
  }
  return $nameVar;
}


//
// Gets a GET request variable
// -1 if the var isn't set
//
function request_get($name, $defaultValue='') {
  $nameVar = NULL;
  if (isset($_GET[$name])) {
    if ($defaultValue != '') {
      $nameVar = $defaultValue;
    }
    else {
      $nameVar = $_GET[$name];
    }
  }

  return $nameVar;
}


//
// Gets a POST request variable
// -1 if the var isn't set
//
function request_post($name, $defaultValue='') {
  $nameVar = NULL;
  if (isset($_POST[$name])) {
    if ($defaultValue != '') {
      $nameVar = $defaultValue;
    }
    else {
      $nameVar = $_POST[$name];
    }
  }

  return $nameVar;
}


//
// Reduces a list of hashes to only include the keys provided
//
function map($list, $keys) {
  $newList = array();

  foreach($list as $item) { 
    $info = array();
    
    foreach($keys as $key) {
      $info[$key] = $item[$key];
    }
    
    $newList[] = $info;
  }

  return $newList;
}


//
// Returns an XML tag with given data
//
function XmlTag($tagName, $input) {
  $data = XmlStartTag($tagName) . $input . XmlEndTag($tagName);
  
  return $data;
}


//
// Returns a start XML tag with given data
//
function XmlStartTag($tagName) {
  $data = "<${tagName}>";
  
  return $data;
}


//
// Returns an end XML tag with given data
//
function XmlEndTag($tagName) {
  $data = "</${tagName}>";
  
  return $data;
}


//
// If a str (haystack) starts with a value (needle)
//
function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}


//
// If a str (haystack) ends with a value (needle)
//
function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}


//
// Is a given value (key) in an array (arr)
//
function isInArray($arr, $key) {
  $exists = false;
  
  if (array_key_exists($key, $arr) && ! is_null($arr[$key]) && $arr[$key] !== "") {
    $exists = true;
  }
  
  return $exists;
}


//
// Convert input to a money value
//
function money($input, $format="%.2n") { 
  return money_format($format, floatval($input));
}


//
// Convert input to a number with a given number of decimals places
//
function num($input, $decimals=0) {
  return number_format(floatval($input), $decimals);
}


//
// Convert input to a percentage with a given number of decimal places
//
function per($input, $decimals=2) {
  return number_format(floatval($input), $decimals) . " %";
}


//
// Convert an array of coin data into a title
//
function getCoinTitle($arr, $showYearInfo = 0) {
 
  $coinName = $arr['year'];
  if (isInArray($arr, 'symbol')) {
    $coinName .= '-' . $arr['symbol'];
  }
  
  if (isInArray($arr, 'coinInfo')) {
    $coinName .= ', ' . $arr['coinInfo'];
  } 
  
  if ($showYearInfo > 0 && isInArray($arr, 'yearInfo')) {
    $coinName .= ' - ' . $arr['yearInfo'];
  } 
  
  return $coinName;
}


//
// Get ebay search string from input array 
//
function getEbaySearchString($arr) {
  $searchName = '';
  
  if (isInArray($arr, 'country')) {
    if (startsWith($arr['country'], 'Great Britain')) {
      $searchName .= ' Great Britain';
    }
    else {
      $searchName .= ' ' . $arr['country'];
    }
  }

  if (isInArray($arr, 'year')) {
    $searchName .= $arr['year'];
  }
  
  if (isInArray($arr, 'symbol')) {
    $searchName .= '-' . $arr['symbol'];
  }

  if (isInArray($arr, 'name')) {
    $searchName .= ' "' . $arr['name'] . '"';
  }

  if (isInArray($arr, 'value')) {
    $value = $arr['value'];
    $searchName .= ' "' . $value . '"';
    
    if ($value == 'Eagle') {
      $searchName .= ' -Double -Quarter -Half -Flying -2.50 10';
    }
  }

  $replacements = array();
  $replacements[] = "-P";
  $replacements[] = "Silver and Related";
  $replacements[] = " or Liberty Head";

  foreach ($replacements as $replacement) {
    $searchName = str_replace($replacement, "", $searchName);
  }

  $searchName = str_replace("Silver - Trime", "Trime", $searchName);
  
  return $searchName;
}


function formatDescription($description) {
  $des = str_replace("\n", "<br />", $description);
  $des = str_replace("!!", ".", $des);
  return $des;
}


//
// Returns first 2 sentences from a string
//
function getBriefDescription($content) {

  $dot = ".";
  $numSentences = 2;
  
  return splitIntoSentences($content, $dot, $numSentences);

}


//
// Returns $numSentences sentences from a string
//
function splitIntoSentences($content, $separator, $numSentences) {

  $i = 0;
  $offset = 0;
  
  while($i < $numSentences) {
    $position = stripos ($content, $separator, $offset); 
    
    if (!$position) { 
      $ofset = 0;
      break;
    }

	$offset = $position + 1;
	$i++;
  }

  if ($offset != 0) {
    $content = substr($content, 0, $offset);
  }
  
  return $content;
}


//
// Creates a start-end year date string
//
function formatDateRange($startDate, $endDate) {

  if ($startDate == 0) {
    return null;
  }
  
  if ($endDate == 0) {
    $endDate = date('Y');
  }
  
  if ($startDate == $endDate) {
    return "" . $startDate . "";
  }
  else {
    return "${startDate}-${endDate}";
  }
}


//
// Creates HTML image code for using lightview images
//
function displayImages($photos, $maxWidth=200, $showSubPhotos=true) {
  $data = "";
  
  $photoLength = count($photos);

  if ($photoLength > 0) {
    $data .= "<div class='image-right'>";
  
    $photoOne = $photos[0];
  
    $fileURL = $photoOne['file'];
    if (! startsWith($fileURL, 'http')) {
      
      $fileURL = '/' . $fileURL;
    }
    $data .= "<a href=\"" . $fileURL . "\" class='lightview'  data-lightview-group='coins' data-lightview-type='image' data-lightview-group-options='controls: \"thumbnails\"'>";
  
    $data .= "<img style='max-width:${maxWidth}px;' src=\"" . $fileURL . "\" alt='" . $photoOne['caption'] . "' />";
    $data .= "</a>";
  
    if ($showSubPhotos && $photoLength > 1) {
      $data .= "<span style='display:none'>";
    
      $isFirst = 1;
    
      foreach ($photos as $photo) {
        if ($isFirst == 1) {
          $isFirst = 0;
          continue;
        }
      
        $fileURL = $photo['file'];
        if (! startsWith($fileURL, 'http')) {
          $fileURL = '/' . $fileURL;
        }
      
        $data .= "<a class='lightview' data-lightview-group='coins' href=\"" . $fileURL . "\">" . $photo['caption'] ."</a>";    
      }
    
      $data .= "</span>";
    }
  
    $data .= "</div>";

  }

  return $data;
}


//
// Creates breadcrumb for current page - TODO: NEED to move SQL to utils_sql file 
//
function breadcrumbs($separator = ' &rarr; ', $home = 'Home') {
  global $navsection;
  
  $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
  $base = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

  $breadcrumbs = Array("<a href=\"$base\">$home</a>");
  
  //$last = end(array_keys($path));
  $last = "";
  
  $trackerCrumb = false;
  foreach ($path AS $x => $crumb) {
    $title = ucwords(str_replace(Array('.php', '-'), Array('', ' '), $crumb));

    if ($trackerCrumb) {
      $breadcrumbs[] = "<a href=\"$base$last/$crumb\">$title</a>";
      $trackerCrumb = false;
    }
    else {
      if ($crumb == 'tracker') {
        $trackerCrumb = true;
      }
      $breadcrumbs[] = "<a href=\"$base$crumb\">$title</a>";
    }
    $last = $crumb;
  }


  
  if (strcmp($navsection, 'us-coins') == 0) {
    // Possible query params
    
    // Coin denomination (silver dollar, etc.) cvid
    $valueId = -1;
    if (isset($_REQUEST['valueId'])) {
      $valueId = $_REQUEST['valueId'];
      
      $SQL = sprintf("SELECT * FROM UsCoinDB.CoinValue WHERE cvid=%d", $valueId);
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
      // Get coin denomination name, cid
      $cvName = $row['name'];
      
      // Add to breadcrumb
      $breadcrumbs[] = "<a href=\"$base$last?valueId=$valueId\">$cvName</a>";
    }

    // Coin type i.e. Morgan silver dollar
    $typeId = -1;
    if (isset($_REQUEST['typeId'])) {
      $typeId = $_REQUEST['typeId'];
      
      $SQL = "SELECT CV.name AS cvName, CV.cvid, C.name AS cName FROM UsCoinDB.CoinValue CV, UsCoinDB.Coin C WHERE C.cvid=CV.cvid AND C.cid=$typeId";
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
        
      $cvId = $row['cvid'];
      $cvName = $row['cvName'];

      $cName = $row['cName'];

      $breadcrumbs[] = "<a href=\"$base$last?valueId=$cvId\">$cvName</a>";
      $breadcrumbs[] = "<a href=\"$base$last?typeId=$typeId\">$cName</a>";
        
    }

    // Specific coin and mint (1878CC Morgan silver dollar) mcid
    $mintCoinId = -1;
    if (isset($_REQUEST['mintCoinId'])) {
      $mintCoinId = $_REQUEST['mintCoinId'];
      
      $SQL = sprintf("SELECT CV.cvid, CV.name AS denomination, C.cid, C.name, CY.year, M.symbol, MC.additionalInfo AS coinInfo FROM UsCoinDB.MintCoin MC, UsCoinDB.CoinYear CY, UsCoinDB.Coin C, UsCoinDB.CoinValue CV, UsCoinDB.Mint M WHERE MC.mcid=%d AND MC.cyid=CY.cyid AND C.cid=CY.cid AND CV.cvid=C.cvid AND MC.mid=M.mid", $mintCoinId);
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
      $valueId = $row['cvid'];
      $cvName = $row['denomination'];

      $typeId = $row['cid'];
      $cName = $row['name'];

      $mcName = getCoinTitle($row);

      $breadcrumbs[] = "<a href=\"$base$last?valueId=$valueId\">$cvName</a>";
      $breadcrumbs[] = "<a href=\"$base$last?typeId=$typeId\">$cName</a>";
      $breadcrumbs[] = "<a href=\"$base$last?mintCoinId=$mintCoinId\">$mcName</a>";        
    }
  }
  else if (strcmp($navsection, 'foreign-coins') == 0) {

    $fcid = -1;
    if (isset($_REQUEST['fcid'])) {
      $fcid = $_REQUEST['fcid'];
  
      $SQL = sprintf("SELECT FC.name AS country FROM ForeignCoinDB.ForeignCountry FC WHERE FC.fcid=%d", $fcid);
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
      $cName = $row['country'];

      $breadcrumbs[] = "<a href=\"$base$last?fcid=$fcid\">$cName</a>";
      
    }

    // Coin denomination (silver dollar, etc.) cvid
    $valueId = -1;
    if (isset($_REQUEST['valueId'])) {
      $valueId = $_REQUEST['valueId'];
      
      $SQL = sprintf("SELECT CV.cvid, CV.name AS denomination, FC.name AS country, FC.fcid FROM ForeignCoinDB.ForeignCountry FC, ForeignCoinDB.CoinValue CV WHERE CV.cvid=%d AND CV.fcid=FC.fcid", $valueId);
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
      $fcid = $row['fcid'];
      $country = $row['country'];
      
      $cvid = $row['cvid'];
      $cvName = $row['denomination'];
      
      $breadcrumbs[] = "<a href=\"$base$last?fcid=$fcid\">$country</a>";
      $breadcrumbs[] = "<a href=\"$base$last?valueId=$valueId\">$cvName</a>";
    }

    // Need to add country, denomination and type
    $typeId = -1;
    if (isset($_REQUEST['typeId'])) {
      $typeId = $_REQUEST['typeId'];
      
      // Add country
      $SQL = sprintf("SELECT FC.name AS country, FC.fcid, CV.cvid, CV.name AS denomination, C.cid, C.name FROM ForeignCoinDB.ForeignCountry FC, ForeignCoinDB.CoinValue CV, ForeignCoinDB.Coin C WHERE CV.cvid=C.cvid AND C.cid=%d AND FC.fcid=CV.fcid ORDER BY value DESC", $typeId);
      
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);

      $cName = $row['country'];
      $fcid = $row['fcid'];
      
      $cvName = $row['denomination'];
      $cvid = $row['cvid'];

      $name = $row['name'];
      $cid = $row['cid'];
      
      $breadcrumbs[] = "<a href=\"$base$last?fcid=$fcid\">$cName</a>";
      $breadcrumbs[] = "<a href=\"$base$last?valueId=$cvid\">$cvName</a>";
      $breadcrumbs[] = "<a href=\"$base$last?typeId=$cid\">$name</a>";
      
    }

    // Specific coin and mint - mcid
    $mintCoinId = -1;
    if (isset($_REQUEST['mintCoinId'])) {
      $mintCoinId = $_REQUEST['mintCoinId'];
      
      $SQL = sprintf("SELECT FC.name AS country, FC.fcid, CV.cvid, CV.name AS denomination, C.cid, C.name, CY.year, M.symbol, MC.additionalInfo AS coinInfo FROM ForeignCoinDB.ForeignCountry FC, ForeignCoinDB.MintCoin MC, ForeignCoinDB.CoinYear CY, ForeignCoinDB.Coin C, ForeignCoinDB.CoinValue CV, ForeignCoinDB.Mint M WHERE MC.mcid=%d AND MC.cyid=CY.cyid AND C.cid=CY.cid AND CV.cvid=C.cvid AND MC.mid=M.mid AND FC.fcid=CV.fcid", $mintCoinId);
      $result = mysql_query($SQL);
      $row = mysql_fetch_array($result);
      
      $fcid = $row['fcid'];
      $country = $row['country'];
      
      $valueId = $row['cvid'];
      $cvName = $row['denomination'];

      $typeId = $row['cid'];
      $cName = $row['name'];

      $mcName = getCoinTitle($row);

      $breadcrumbs[] = "<a href=\"$base$last?fcid=$fcid\">$country</a>";
      $breadcrumbs[] = "<a href=\"$base$last?valueId=$valueId\">$cvName</a>";
      $breadcrumbs[] = "<a href=\"$base$last?typeId=$typeId\">$cName</a>";
      $breadcrumbs[] = "<a href=\"$base$last?mintCoinId=$mintCoinId\">$mcName</a>";        
    }

  }
  
  
  return implode($separator, $breadcrumbs);
}


function breadcrumbs_OLD($separator = ' &raquo; ', $home = 'Home') {
    $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

    $base = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

    $breadcrumbs = Array("<a href=\"$base\">$home</a>");

    $last = end(array_keys($path));

    foreach ($path AS $x => $crumb) {
        $title = ucwords(str_replace(Array('.php', '-'), Array('', ' '), $crumb));

        $breadcrumbs[] = "<a href=\"$base$crumb\">$title</a>";
    }

    return implode($separator, $breadcrumbs);
}


//
// Define a MySQL charset func
//
if (function_exists('mysql_set_charset') === false) {
     /**
      * Sets the client character set.
      *
      * Note: This function requires MySQL 5.0.7 or later.
      *
      * @see http://www.php.net/mysql-set-charset
      * @param string $charset A valid character set name
      * @param resource $link_identifier The MySQL connection
      * @return TRUE on success or FALSE on failure
      */
     function mysql_set_charset($charset, $link_identifier = null)
     {
         if ($link_identifier == null) {
             return mysql_query('SET NAMES "'.$charset.'"');
         } else {
             return mysql_query('SET NAMES "'.$charset.'"', $link_identifier);
         }
     }
}

//
// Format auction time left
//
function formatTimeLeft($timeStr) {

  $coinEndTime = $timeStr; //strtotime($timeStr);

  $coinDiff = $coinEndTime - time();      
  
  $seconds = $coinDiff;
  $days = floor($seconds / 86400);
  $seconds %= 86400;
  $hours = floor($seconds / 3600);
  $seconds %= 3600;
  $minutes = floor($seconds / 60);
  $seconds %= 60;

  $timeArray = array();
  $timeLeft = '';
  if ($days > 0) {
    $timeArray[] = "$days day";
    $timeLeft .= "$days days, ";
  }
  if ($hours > 0) {
    $timeArray[] = "$hours hour";
    $timeLeft .= "$hours hours, ";
  }
  if ($minutes > 0) {
    $timeArray[] = "$minutes minute";
    $timeLeft .= "$minutes minutes ";
  }
  if ($seconds > 0) {
    if ($seconds > 1) {
      $timeArray[] = "$seconds seconds";
    }  
    else {
      $timeArray[] = "$seconds second";
    }
    $timeLeft .= "and $seconds seconds";
  }
      
  return implode("s, ", $timeArray);    
  //$timeLeft = "$days days, $hours hours, $minutes minutes and $seconds seconds";
  return $timeLeft;
}
 
 
//
// Prints a 2 column table row with label and value
//
function addTableRow($label, $value) {

  if ($value != '') {
    echo "<tr><td><label>$label</label></td><td>$value</td></tr>";
  }

}
 

//
// Build an SQL select statement with input arrays
//
function buildSQLSelect($select, $from, $where, $order) {
  $SQL = "SELECT ";
  $SQL .= implode(", ", $select);
  $SQL .= " FROM ";
  $SQL .= implode(", ", $from);
  if (isset($where) && sizeof($where) > 0) {
    $SQL .= " WHERE ";
    $SQL .= implode(" AND ", $where);
  }
  if (isset($order) && sizeof($order) > 0) {
    $SQL .= " ORDER BY ";
    $SQL .= implode(", ", $order);
  }
  
  return $SQL;
}


/**
 * Input an object, returns a json-ized string of said object, pretty-printed
 *
 * @param mixed $obj The array or object to encode
 * @return string JSON formatted output
 */
function json_encode_pretty($obj, $indentation = 0) {
  switch (gettype($obj)) {
    case 'object':
      $obj = get_object_vars($obj);
    case 'array':
      if (!isset($obj[0])) {
        $arr_out = array();
        foreach ($obj as $key => $val) {
          $arr_out[] = '"' . addslashes($key) . '": ' . json_encode_pretty($val, $indentation + 1);
        }
        if (count($arr_out) < 2) {
          return '{' . implode(',', $arr_out) . '}';
        }
        return "{\n" . str_repeat("  ", $indentation + 1) . implode(",\n".str_repeat("  ", $indentation + 1), $arr_out) . "\n" . str_repeat("  ", $indentation) . "}";
      } else {
        $arr_out = array();
        $ct = count($obj);
        for ($j = 0; $j < $ct; $j++) {
          $arr_out[] = json_encode_pretty($obj[$j], $indentation + 1);
        }
        if (count($arr_out) < 2) {
          return '[' . implode(',', $arr_out) . ']';
        }
        return "[\n" . str_repeat("  ", $indentation + 1) . implode(",\n".str_repeat("  ", $indentation + 1), $arr_out) . "\n" . str_repeat("  ", $indentation) . "]";
      }
      break;
    case 'NULL':
      return 'null';
      break;
    case 'boolean':
      return $obj ? 'true' : 'false';
      break;
    case 'integer':
    case 'double':
      return $obj;
      break;
    case 'string':
    default:
      $obj = str_replace(array('\\','"',), array('\\\\','\"'), $obj);
      return '"' . $obj . '"';
      break;
  }
}


//
// Prints an admin DB section
//
function printDBSection($func, $header) {
  echo "<h2>$header</h2>";
  echo "<div style='margin:1em 3em'>";
  $func();
  echo "</div>";
}
 

//
// Display a status message
// 
function statusDisplay() {
  $success = request_get('success');
  $info = request_get('info');
  $warning = request_get('warning');
  $error = request_get('error');

  if ($error) {
    echo "<br /><p class='error'>$error</p><br />";
  }
  else if ($warning) {
    echo "<br /><p class='warning'>$warning</p><br />";
  }
  else if ($info) {
    echo "<br /><p class='info'>$info</p><br />";
  }
  else if ($success) {
    echo "<br /><p class='success'>$success</p><br />";
  }
}

?>
