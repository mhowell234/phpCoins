<?php

//
// Header/title area display
//
function headerTitleMenu($title) {
  global $DOCUMENT_ROOT; 

  include("$DOCUMENT_ROOT/include/html/header.php");
  echo '<div id="main-wrapper">';
  include("$DOCUMENT_ROOT/include/html/searchSection.php"); 

  echo "<h1>$title</h1>";
  include("$DOCUMENT_ROOT/include/html/menu.php"); 
}


//
// End Header/Section display
//
function endHeaderTitleMenu() {
  echo '<div class="visualClear"></div>';
  echo '</div>';
}


//
// A visual clear
//
function clear() {
  return "<div class='visualClear'></div>";
}


//
// Outputs a section with a PHP include
//
function section($id, $file, $showDisplay=false) {
  global $DOCUMENT_ROOT; 
  global $error;
  global $errorRow;
  
  if(!$showDisplay) {
    $include_path = "$DOCUMENT_ROOT/include/${file}.php";
  }
  else {
    $include_path = "$DOCUMENT_ROOT/include/sections/${file}.php";
  }
  
  echo "<section id='$id'>\n";
  include($include_path);
  echo "</section>";
}


//
// Displays the description section
//
function displayDescription($description) {
  $data = '';
  
  if (isset($description) && $description !== "") {
    $data .= "<h3>Description</h3>";
    $data .= "<p class='expandable'>$description</p>";
  }
  
  return $data;
}


//
// Display a table of grade/values for coins
//
function displayNumismaticValues($coinValueTypes, $coinValues) {
  $data = "";

  if (isset($coinValueTypes) && count($coinValueTypes) > 0) { 
    $data .= "<div class='section'>";
    $data .= "<h3 class='heading down-arrow'>Numismatic Values</h3>";

    $data .= "<div class='content'>";
    $data .= "<table border='1' summary='Coin Values Based on Condition'>";
    $data .= "<caption></caption>";

    $data .= "<thead><tr>";
  
    foreach ($coinValueTypes as $coinValueType) {
      $data .= "<th scope='col'>" . $coinValueType . "</th>";
    }

    $data .= "</tr></thead>";
    $data .= "<tbody><tr>";

    foreach ($coinValues as $coinValue) {
      $data .= "<td>" . money($coinValue['value']) . "</td>";
    }

    $data .= "</tr></tbody>";
    $data .= "</table>";
    $data .= "</div>";
    $data .= "</div>";
  }
  
  return $data;
}


//
// Display a table of melt values for coins
//
function displayMeltValues($metals) {
  $data = "";
  
  if (count($metals) > 0) {

    $data .= clear();
    $data .= "<div class='section'>";
    $data .= "<h3 class='heading down-arrow'>Melt Values</h3>";
    $data .= "<div class='content'>";
    $data .= '<table border="1" summary="Coin Metals and Melt Values">';
    $data .= "<caption></caption>";
    $data .= "<thead><tr><th scope='col'>Metal</th><th scope='col'>Price</th><th scope='col'>Percentage</th><th>Value</th>";
    $data .= "</tr></thead>";
    $data .= "<tbody>";
    
    $totalValue = 0.00;
   
    foreach ($metals as $metal) {
      $data .= "<tr>";
     
      $name = $metal['metal'];
     
      $price = money($metal['price']);
      $unit = $metal['unit'];
      $percentage = per($metal['percentage']);
      $value = money($metal['value']);
      $totalValue += $metal['value'];
     
      $data .= "<td>$name</td>";
      $data .= "<td>$price $unit</td>";
      $data .= "<td>$percentage</td>";
      $data .= "<td>$value</td>";
      
      $data .= "</tr>";
    } 
   
    $totalValue = money($totalValue);
   
    $data .= "<tr><th class='header-column' scope='row'>Total Value</th><td> </td><td> </td><td class='header-column'>$totalValue</td></tr>";
    $data .= "</tbody>";
    $data .= "</table>";
    $data .= "</div>";
    $data .= "</div>";
  }
  
  return $data;
}


//
// Display a table of coin ratings
//
function displayRatingValues($ratings) {
  $data = "";
  
  if (isset($ratings) && count($ratings) > 0) { 
    $data .= "<div class='section'>";
    $data .= "<h3 class='heading down-arrow'>Ratings</h3>";
    $data .= "<div class='content'>";
    $data .= "<table border='1' summary='Coin Ratings'>";
    $data .= "<caption></caption>";
    $data .= "<thead><tr><th>Year</th><th>Agency</th><th>Rating</th></tr></thead>";
    $data .= "<tbody>";
    
    foreach ($ratings as $coinRating) {
      $data .= "<tr>";
      $data .= "<td>" . date("m/d/Y", strtotime($coinRating['date'])) . "</td>";
      $data .= "<td>" . $coinRating['agency'] . "</td>";
      $data .= "<td>" . $coinRating['rating'] . "</td>";    
      $data .= "</tr>";
    }

    $data .= "</tbody>";
    $data .= "</table>";
    $data .= "</div>";
    $data .= "</div>";

  }

  return $data;
}


//
// Displays years for a coin and their value
//
function displayCoinYears($coins, $coinValueTypes) {
  $data = '';
  
  $coinValueTypeLength = count($coinValueTypes);

  foreach ($coins as $key => $value) {
     $km = $value[0]['km'];

     $data .= clear();
     $data .= "<div class='section'>";
     if ($key !== "default") {
       $data .= "<h3 class='heading down-arrow'>$key";
       if (isset($km)) {
         $data .= " - KM# $km";
       }
       $data .= "</h3>";
     }
     else {
       if (isset($km)) {
         $data .= "<h3 class='heading down-arrow'>KM# $km</h3>";
       }
       else {
         $data .= "<h3 class='heading down-arrow'>Years</h3>";
       }
     }
   
     $data .= "<div class='content'>";
     $data .= "<table>";
     $data .= "<caption></caption>";
     $data .= "<thead><tr><th>Year</th><th class='no-wrap'>Minted</th>";

     foreach ($coinValueTypes as $coinValueTitle) {
       $data .= "<th class='no-wrap'>$coinValueTitle</th>";
     }
   
     $data .= "</tr></thead>";
     $data .= "<tbody>";
   
     foreach ($value as $coinValue) {
       $data .= "<tr>";
     
       $data .= "<td style='min-width:120px'><a href='?mintCoinId=" . $coinValue['id'] . "'>" . $coinValue['title'] . "</a></</td>";
       $data .= "<td class='smaller'>" . num($coinValue['numberMinted']); 
     
       if ($coinValue['proofMinted'] != 0) {
         $data .= " <span class='discreet'>(" . num($coinValue['proofMinted']) . ")</span>";
       }
       $data .= "</td>";

       $counter = 0;
       foreach ($coinValue['coinValues'] as $coinValues) {
         while ($coinValues['title'] != $coinValueTypes[$counter]) {
           $data .= "<td>-</td>";
           $counter++;
         }
       
         $specificCoinValue = $coinValues['value'];
       
         if (intval($specificCoinValue) != $specificCoinValue) { 
           $specificCoinValue = money($specificCoinValue);
         }
         else {
           $specificCoinValue = money($specificCoinValue, '%.0n');
         }
       
         $data .= "<td class='no-wrap'>" . $specificCoinValue . "</td>";
         $counter++;
       }

       while ($counter < $coinValueTypeLength) {
         $data .= "<td>-</td>";
         $counter++;
       }
     
       $data .= "</tr>"; 
     }
   $data .= "</tbody></table></div></div>";
  }

  return $data;
}

?>
