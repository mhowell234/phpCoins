<?php

//***************************************************************************************
//
// Common Attributes
//
//***************************************************************************************

//
// Exports all rating scales
//
function exportPreciousMetals($silent=false) {
  exportAll('getPreciousMetals', 'exportPreciousMetal', $silent);
}


//
// Exports all rating scales
//
function exportRatingScales($silent=false) {
  exportAll('getRatingScales', 'exportRatingScale', $silent);
}


//
// Exports all rating categories
//
function exportRatingCategories($silent=false) {
  exportAll('getRatingCategories', 'exportRatingCategory', $silent);
}


//
// Exports all coin origin data
//
function exportRatingAgencies($silent=false) {
  exportAll('getRatingAgencies', 'exportRatingAgency', $silent);
}


//
// Exports all coin origin data
//
function exportCoinOrigins($silent=false) {
  exportAll('getCoinOrigins', 'exportCoinOrigin', $silent);
}


//***************************************************************************************
//
// U.S. Coins
//
//***************************************************************************************

//
// Exports all mint data
//
function exportMints($silent=false) {
  exportAll('getMints', 'exportMint', $silent);
}


//
// Exports all mint dates data
//
function exportMintDates($silent=false) {
  exportAll('getMintDates', 'exportMintDate', $silent);
}


//
// Exports all coin data
//
function exportCoins($silent=false) {

  $coinsByValue = getAllCoinsByType();
  $ids = array();
  
  foreach ($coinsByValue as $coinValue) {
    foreach($coinValue['list'] as $coin) {
      $ids[] = $coin['id'];
    }
  }
  
  export($ids, "exportCoin", $silent);
}


//
// Exports all coin attrib data
//
function exportCoinAttribs($silent=false) {
  exportAll('getCoinAttributesAll', 'exportCoinAttrib', $silent);
}


//
// Exports all coin value data
//
function exportCoinValues($silent=false) {
  exportAll('getCoinDenominations', 'exportCoinValue', $silent);
}


//
// Exports all coin value attrib data
//
function exportCoinValueAttribs($silent=false) {
  exportAll('getCoinValueAttributesAll', 'exportCoinValueAttrib', $silent);
}


//
// Exports all coin year data
//
function exportCoinYears($silent=false) {
  exportAll('getCoinYears', 'exportCoinYear', $silent);
}


//
// Exports the precious metal breakdown of each coin + year + more info
//
function exportCoinMetalCompositions($silent=false) {
  global $US_COIN_DB_DIR;
  global $COIN_METAL_COMPOSITION_FILE;
  global $COIN_METAL_COMPOSITION_HEADER_LINE;

  $uniqueCoins = array();
  
  $coins = getCoinMetalCompositionAll();

  foreach($coins as $coin => $values) {
    foreach($values as $value => $years) {
      foreach($years as $year => $yearInfos) {
        foreach($yearInfos as $yearInfo => $coinInfos) {
          foreach($coinInfos as $coinInfo => $metals) {
            $key = "$coin|$value|$year|$yearInfo|$coinInfo";
            
            $metalData = array();
            foreach($metals as $metal) {
              
              $name = $metal['metal'];
              
              $info = array();
              
              $info['weight'] = $metal['weight'];
              $info['percentage'] = $metal['percentage'];
              
              $metalData[$name] = $info;
            }
            
            $uniqueCoins[$key] = $metalData;
          }
        }
      }
    }
  }

  $first = true;
    
  foreach($uniqueCoins as $coinKey => $metals) {
    $lineData = explode('|', $coinKey);
    
    $weightVal = '';
    foreach($metals as $metal) {
      if ($weightVal == '') {
        $weightVal = $metal['weight'];
        $lineData[] = $weightVal;
      }
      break;
    }

    $metalTypes = array();
    $metalTypes[] = 'Silver';    
    $metalTypes[] = 'Copper';    
    $metalTypes[] = 'Nickel';    
    $metalTypes[] = 'Manganese';    
    $metalTypes[] = 'Gold';    
    $metalTypes[] = 'Zinc';    
    
    foreach($metalTypes as $metalType) {
      if (array_key_exists($metalType, $metals)) {
        $lineData[] = $metals[$metalType]['percentage'];
      }
      else {
        $lineData[] = '-1';
      }
    }

    $overwrite = false;
    if ($first) {
      $overwrite = true;
      $first = false;
    }
    
    writeDBData($US_COIN_DB_DIR, $COIN_METAL_COMPOSITION_FILE, $COIN_METAL_COMPOSITION_HEADER_LINE, $lineData, $overwrite);
    
    echo implode('|', $lineData);
    echo "<br />";
  }
}


//
// Exports mint coins
//
function exportMintCoins($silent=false) {
  global $EXPORT_DB_DIR;
  global $US_COIN_DB_DIR;
  global $US_MINT_COIN_DB_DIR;
  
  $header_fields = array();
  $header_fields[] = "YEAR";
  $header_fields[] = "MINTAGE";
  // Need to add all grades in between
  $header_fields[] = "Paren";
  $header_fields[] = "ADDITIONAL_INFO";

  ensure_dir($EXPORT_DB_DIR);
  ensure_dir($US_COIN_DB_DIR);
  ensure_dir($US_MINT_COIN_DB_DIR);
  
  $coins = getMintCoinsAll();
  
  $hasSeen = array();
  
  foreach($coins as $coin) {
    $directory = $coin['value'];
    
    $path = "$US_MINT_COIN_DB_DIR/$directory";
    ensure_dir($path);
    
    $fileName = $coin['name'];
    $symbol = $coin['symbol'];
    $year = $coin['year'];
    $numberMinted = $coin['numberMinted'];
    if ($numberMinted == 0 || $numberMinted == '0') {
      $numberMinted = -1;
    }
    $proofMinted = $coin['proofMinted'];
    if ($proofMinted == 0 || $proofMinted == '0') {
      $proofMinted = -1;
    }
    $coinInfo = $coin['coinInfo'];
    $yearInfo = $coin['yearInfo'];
    $mcid = $coin['mcid'];
    
    $data = array();
    
    $title = "${year}${symbol}";
    if ($coinInfo != '') {
      $coinInfoEscaped = str_replace(" ", ":", $coinInfo);
      $title .= ",:" . $coinInfoEscaped;
    }
    $data[] = $title;
    
    $data[] = $numberMinted;
    

    // Add all grades in between      
    $cid = $coin['id'];
    $types = getUsCoinYearRatingScale($cid);
    $mintValues = getCoinValuesAndTypes($mcid)['values'];
    
    $i=0;
    foreach ($types as $type) {
      $currentTitle = $mintValues[$i]['title'];
      $currentValue = $mintValues[$i]['value'];
      
      if ($type != $currentTitle) {
        $data[] = '-1';
      }
      else { 
        $data[] = $currentValue;
        $i++;
      }
    }
    
    // Get RANKS AND YEARS
    $data[] = $proofMinted;
    $data[] = $yearInfo;

    $filePath = "$path/${fileName}.txt";
    if (array_key_exists($filePath, $hasSeen)) {
      $overwrite = false;
      $headerLine = '';
    }
    else {
      $overwrite = true;
      
      $header_fields = array();
      $header_fields[] = "YEAR";
      $header_fields[] = "MINTAGE";

      // Add all grades in between      
      foreach ($types as $type) {
        $header_fields[] = strtoupper($type);
      }
      
      $header_fields[] = "Paren";
      $header_fields[] = "ADDITIONAL_INFO";       
      
      $headerLine = implode('|', $header_fields);
      print "Writing: $cid -- $fileName<br />";
    }
        
    writeDBData($path, "${fileName}.txt", $headerLine, $data, $overwrite); 

    $hasSeen[$filePath] = true;
  }
}


//***************************************************************************************
//
// Our Coins
//
//***************************************************************************************

//
// Exports all our coins data
//
function exportOurCoins($silent=false) {
  exportAll('getOurUSCoins', 'exportOurCoin', $silent);
}


//***************************************************************************************
//
// Foreign Coins
//
//***************************************************************************************

//
// Exports all foreign countries data
//
function exportForeignCountries($silent=false) {
  exportAll('getForeignCountriesAll', 'exportForeignCountry', $silent);
}


//
// Exports foreign coin values
//
function exportForeignCoinValues($silent=false) {
  global $EXPORT_DB_DIR;
  global $FOREIGN_COIN_DB_DIR;
  global $FOREIGN_COINS_DB_DIR;
  global $COIN_VALUE_FILE;
  global $COIN_VALUE_HEADER_LINE;
  
  ensure_dir($EXPORT_DB_DIR);
  ensure_dir($FOREIGN_COIN_DB_DIR);
  ensure_dir($FOREIGN_COINS_DB_DIR);
  
  $countries = getForeignCountriesAll();
  
  foreach($countries as $country) {
    $fcid = $country['id'];
    $abbreviation = $country['abbreviation'];

    $path = "$FOREIGN_COINS_DB_DIR/$abbreviation";

    $coinTypes = getForeignCountryAllCoinTypes($fcid);
      
    if (sizeof($coinTypes) > 0) {
      ensure_dir($path);
      $overwriteFile = true;
      
      foreach($coinTypes as $value) {
        $data = array();
  
        $data[] = $value['type'];
        $data[] = $value['value'];
        
        $raw_description = str_replace("\n\n", "^^^^", $value['description']);
        $raw_description = str_replace('"', '\\"', $raw_description);
        $data[] = $raw_description;

        writeDBData($path, $COIN_VALUE_FILE, $COIN_VALUE_HEADER_LINE, $data, $overwriteFile);
        $overwriteFile = false;
        
        print_r($data);
        print "<br /><br />";
      }
    }
  }
  
}


//
// Exports foreign coins
//
function exportForeignCoins($silent=false) {
  global $EXPORT_DB_DIR;
  global $FOREIGN_COIN_DB_DIR;
  global $FOREIGN_COINS_DB_DIR;
  global $COIN_FILE;
  global $COIN_HEADER_LINE;
  
  ensure_dir($EXPORT_DB_DIR);
  ensure_dir($FOREIGN_COIN_DB_DIR);
  ensure_dir($FOREIGN_COINS_DB_DIR);
  
  $countries = getForeignCountriesAll();
  
  foreach($countries as $country) {
    $fcid = $country['id'];
    $abbreviation = $country['abbreviation'];

    $path = "$FOREIGN_COINS_DB_DIR/$abbreviation";

    $coinTypes = getForeignCountryAllCoinTypes($fcid);
      
    if (sizeof($coinTypes) > 0) {
      ensure_dir($path);
      
      foreach($coinTypes as $value) {
        $cvid = $value['cvid'];
        
        $coinWrapper = getForeignCoins($cvid);
        
        $overwriteFile = true;
        $coinValue = $coinWrapper['name'];
          
        foreach($coinWrapper['list'] as $coin) {
          $data = array();
  
          $data[] = $coin['name'];
          $data[] = $coinValue;
          $data[] = $coin['startYear'];
          $data[] = $coin['endYear'];
          
          $raw_description = str_replace("\n\n", "^^^^", $coin['description_raw']);
          $raw_description = str_replace('"', '\\"', $raw_description);
          $data[] = $raw_description;

          writeDBData($path, $COIN_FILE, $COIN_HEADER_LINE, $data, $overwriteFile);
          $overwriteFile = false;
                  
          print_r($data);
          print "<br /><br />";
        }
      }
    }
  }
  
}

//
// Exports all foreign coin year data
//
function exportForeignCoinYears($silent=false) {
  $hasSeen = [];
  $years = getForeignCoinYears();
  $ids = array();
  

  foreach($years as $year) {
    $overwrite = true;
    if (array_key_exists($year['abbreviation'], $hasSeen)) {
      $overwrite = false;
    }
    $output = exportForeignCoinYear($year['id'], $overwrite);
    $hasSeen[$year['abbreviation']] = 1;
    
    if (!$silent) {
      print_r($output);
      echo "<br /><br />";
    }
  }
}


//
// Exports all foreign mint data
//
function exportForeignMints($silent=false) {
  $hasSeen = [];
  
  $mints = getForeignMints();
  $ids = array();
  

  foreach($mints as $mint) {
    $overwrite = true;
    if (array_key_exists($mint['abbreviation'], $hasSeen)) {
      $overwrite = false;
    }
    $output = exportForeignMint($mint['id'], $overwrite);
    $hasSeen[$mint['abbreviation']] = 1;
    
    if (!$silent) {
      print_r($output);
      echo "<br /><br />";
    }
  }
}


//
// Exports dates for foreign mints
//
function exportForeignMintDates($silent=false) {
  $hasSeen = [];
  
  $dates = getForeignMintDates();
  $ids = array();
  
print_r($dates);
  foreach($dates as $date) {
    $overwrite = true;
    if (array_key_exists($date['abbreviation'], $hasSeen)) {
      $overwrite = false;
    }
    $output = exportForeignMintDate($date['id'], $overwrite);
    $hasSeen[$date['abbreviation']] = 1;
    
    if (!$silent) {
      print_r($output);
      echo "<br /><br />";
    }
  }
}


//
// Exports the precious metal breakdown of each foreign coin + year + more info
//
function exportForeignCoinMetalCompositions($silent=false) {
  global $EXPORT_DB_DIR;
  global $FOREIGN_COIN_DB_DIR;
  global $FOREIGN_COINS_DB_DIR;
  global $COIN_METAL_COMPOSITION_FILE;
  global $COIN_METAL_COMPOSITION_HEADER_LINE;

  ensure_dir($EXPORT_DB_DIR);
  ensure_dir($FOREIGN_COIN_DB_DIR);
  ensure_dir($FOREIGN_COINS_DB_DIR);

  $uniqueCoins = array();
  
  $coins = getForeignCoinMetalCompositionAll();
  
  foreach($coins as $coin => $values) {
    foreach($values as $value => $years) {
      foreach($years as $year => $yearInfos) {
        foreach($yearInfos as $yearInfo => $coinInfos) {
          foreach($coinInfos as $coinInfo => $metals) {
            $key = "$coin|$value|$year|$yearInfo|$coinInfo";
            
            $metalData = array();
            foreach($metals as $metal) {
              
              $name = $metal['metal'];
              
              $info = array();
              
              $info['weight'] = $metal['weight'];
              $info['percentage'] = $metal['percentage'];
              $info['abbreviation'] = $metal['abbreviation'];
              
              $metalData[$name] = $info;
            }
            
            $uniqueCoins[$key] = $metalData;
          }
        }
      }
    }
  }

  $hasSeen = array();
    
  foreach($uniqueCoins as $coinKey => $metals) {
    $lineData = explode('|', $coinKey);
    
    $weightVal = '';
    foreach($metals as $metal) {
      if ($weightVal == '') {
        $weightVal = $metal['weight'];
        $lineData[] = $weightVal;
      }
      break;
    }

    $metalTypes = array();
    $metalTypes[] = 'Silver';    
    $metalTypes[] = 'Copper';    
    $metalTypes[] = 'Nickel';    
    $metalTypes[] = 'Manganese';    
    $metalTypes[] = 'Gold';    
    $metalTypes[] = 'Zinc';    
    
    $country = '';
    foreach($metalTypes as $metalType) {
      if (array_key_exists($metalType, $metals)) {
        $lineData[] = $metals[$metalType]['percentage'];
        $country = $metals[$metalType]['abbreviation'];
      }
      else {
        $lineData[] = '-1';
      }
    }

    $overwrite = false;
    if (!array_key_exists($country, $hasSeen)) {
      $overwrite = true;
      $hasSeen[] = $country;
    }

    $path = "$FOREIGN_COINS_DB_DIR/$country";

    writeDBData($path, $COIN_METAL_COMPOSITION_FILE, $COIN_METAL_COMPOSITION_HEADER_LINE, $lineData, $overwrite);
    
    echo implode('|', $lineData);
    echo "<br />";
  }
}


//
// Exports foreign mint coins
//
function exportForeignMintCoins($silent=false) {
  global $EXPORT_DB_DIR;
  global $FOREIGN_COIN_DB_DIR;
  global $FOREIGN_COINS_DB_DIR;
  global $US_MINT_COIN_DB_DIR;
  
  $header_fields = array();
  $header_fields[] = "YEAR";
  $header_fields[] = "MINTAGE";
  // Need to add all grades in between
  $header_fields[] = "Paren";
  $header_fields[] = "ADDITIONAL_INFO";
  $header_fields[] = "KM";

  ensure_dir($EXPORT_DB_DIR);
  ensure_dir($FOREIGN_COIN_DB_DIR);
  ensure_dir($FOREIGN_COINS_DB_DIR);
  
  $coins = getForeignMintCoinsAll();
  
  $hasSeen = array();
  
  foreach($coins as $coin) {
    $directory = $coin['value'];
    
    $country = $coin['abbreviation'];
    
    $path = "$FOREIGN_COINS_DB_DIR/$country/mintCoin";
    ensure_dir($path);
    $path = "$path/$directory";
    ensure_dir($path);
    
    $fileName = $coin['name'];
    $symbol = $coin['symbol'];
    $year = $coin['year'];
    $numberMinted = $coin['numberMinted'];
    if ($numberMinted == 0 || $numberMinted == '0') {
      $numberMinted = -1;
    }
    $proofMinted = $coin['proofMinted'];
    if ($proofMinted == 0 || $proofMinted == '0') {
      $proofMinted = -1;
    }
    $coinInfo = $coin['coinInfo'];
    $yearInfo = $coin['yearInfo'];
    $mcid = $coin['mcid'];
    $km = $coin['km'];
    
    $data = array();
    
    $title = "${year}${symbol}";
    if ($coinInfo != '') {
      $coinInfoEscaped = str_replace(" ", ":", $coinInfo);
      $title .= ",:" . $coinInfoEscaped;
    }
    $data[] = $title;
    
    $data[] = $numberMinted;
    
    // Add all grades in between      
    $cid = $coin['id'];
    $types = getForeignCoinYearRatingScale($cid);
    $mintValues = getForeignCoinValuesAndTypes($mcid)['values'];
    
    $i=0;
    foreach ($types as $type) {
      $currentTitle = $mintValues[$i]['title'];
      $currentValue = $mintValues[$i]['value'];
      
      if ($type != $currentTitle) {
        $data[] = '-1';
      }
      else { 
        $data[] = $currentValue;
        $i++;
      }
    }
    
    // Get RANKS AND YEARS
    $data[] = $proofMinted;
    $data[] = $yearInfo;
    $data[] = $km;
    
    $filePath = "$path/${fileName}.txt";
    if (array_key_exists($filePath, $hasSeen)) {
      $overwrite = false;
      $headerLine = '';
    }
    else {
      $overwrite = true;
      
      $header_fields = array();
      $header_fields[] = "YEAR";
      $header_fields[] = "MINTAGE";

      foreach ($types as $type) {
        $header_fields[] = strtoupper($type);
      }
      
      $header_fields[] = "Paren";
      $header_fields[] = "ADDITIONAL_INFO";
      $header_fields[] = "KM";
      
      $headerLine = implode('|', $header_fields);
      print "Writing: $cid -- $fileName<br />";
    }
        
    writeDBData($path, "${fileName}.txt", $headerLine, $data, $overwrite); 

    $hasSeen[$filePath] = true;
  }
}


//***************************************************************************************
//
// Tracker Searches
//
//***************************************************************************************

//
// Exports all trackers to DB file
// Also clears current DB file
//
function exportTrackers($silent=false) {
  exportAll('getTrackers', 'exportTracker', $silent);
}


?>