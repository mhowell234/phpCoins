<?php

include_once('../include/init.php');
include_once("$DOCUMENT_ROOT/include/utils.php");

$coin_root = "/Users/mhowell/Desktop/coinOutput";
$BASE = "/Users/mhowell/Dropbox/htdocs";
$FIELD_DIV = "|-#-|";
$BINARY_DIV = "{{{{{{{{{{";
$BINARY_END_DIV = "}}}}}}}}}}";

$SECTION_START = "{#1}";
$SECTION_END = "{/#1}";
$SECTION2_START = "{#2}";
$SECTION2_END = "{/#2}";
$SECTION3_START = "{#3}";
$SECTION3_END = "{/#3}";
$SECTION4_START = "{#4}";
$SECTION4_END = "{/#4}";
$SECTION5_START = "{#5}";
$SECTION5_END = "{/#5}";
$SECTION_SEP = "\n";
  
$types = getAllCoinsByType();  

foreach ($types as $type) {
  
  $typeName = $type['name'];
  
  foreach ($type['list'] as $coin) {
    $coinId = $coin['id'];
    $coinName = $coin['name'];
    $coinDescription = $coin['fullDescription'];
    $startYear = $coin['startYear'];
    $endYear = $coin['endYear'];
    if ($endYear == 0) { $endYear = -1; }
    
    $stringData = "${SECTION_START}INFO${SECTION_SEP}${coinName}${FIELD_DIV}${typeName}${FIELD_DIV}${startYear}${FIELD_DIV}${endYear}${FIELD_DIV}${coinDescription}${FIELD_DIV}${SECTION_SEP}${SECTION_END}${SECTION_SEP}";
    
    $yearsInfo = getUSCoinYearsInfo($coinId);
    
    if (count($yearsInfo) > 0) {
      $stringData .= "${SECTION_SEP}${SECTION_START}YEARS${SECTION_SEP}";

      foreach ($yearsInfo as $coinSection => $cdata) {

        foreach ($cdata as $data) {
        
          $minted = $data['numberMinted'];
          $proofMinted = $data['proofMinted'];
          $title = $data['title'];
          $cid = $data['id'];
          
          $stringData .= "${SECTION_SEP}${SECTION2_START}${SECTION_SEP}";
          $stringData .= "${title}${FIELD_DIV}${minted}${FIELD_DIV}${proofMinted}${FIELD_DIV}";
          
          foreach ($data['coinValues'] as $yearData) {
            $grade = $yearData['title'];
            $value = $yearData['value'];
            
            $stringData .= "${SECTION_SEP}${SECTION3_START}${SECTION_SEP}${grade}:${value}${SECTION_SEP}${SECTION3_END}${SECTION_SEP}";
          }
          $stringData .= "${SECTION_SEP}${SECTION2_END}";
        }
      }
      $stringData .= "${SECTION_SEP}${SECTION_END}${SECTION_SEP}";

    }
    
    
//    $photos = getThumbnails($coinId);
    $photos = getImages($coinId);
    
    if (count($photos) > 0) {
      $stringData .= "${SECTION_SEP}${SECTION_START}PHOTOS${SECTION_SEP}";
            
      foreach ($photos as $photo) {
      
          $fileName = $photo['file'];
          $caption = $photo['caption'];
        
          $compFileName = "$BASE/$fileName"; 
          $fnh = fopen($compFileName, "rb");
          $photoContents = fread($fnh, filesize($compFileName));
          fclose($fnh);
          
          $stringData .= "${SECTION_SEP}${SECTION2_START}${SECTION_SEP}";
          $stringData .= "${caption}${BINARY_DIV}${photoContents}${BINARY_END_DIV}";
          $stringData .= "${SECTION_SEP}${SECTION2_END}";
        
      }
      $stringData .= "${SECTION_SEP}${SECTION_END}${SECTION_SEP}";
    }
    
    $fileName = strtolower("us-$typeName-$coinName.coin");
    
    echo "<br />$fileName";
    
    $fh = fopen("$coin_root/$fileName", 'w');
    
    fwrite($fh, $stringData);
    fclose($fh);
  }
}

?>
