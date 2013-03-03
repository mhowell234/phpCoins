<?php

include_once('../include/init.php');
include_once("$DOCUMENT_ROOT/include/utils.php");

$coin_root = "/Users/mhowell/Desktop/coinOutput";
$BASE = "/Users/mhowell/Dropbox/htdocs";

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
    
    $stringData = XmlStartTag('Coin') . "\n";
    $stringData .= "  " . XmlTag('Name', $coinName) . "\n";
    $stringData .= "  " . XmlTag('Type', $typeName) . "\n";
    $stringData .= "  " . XmlTag('Description', $coinDescription) . "\n";
    $stringData .= "  " . XmlTag('StartYear', $startYear) . "\n";
    $stringData .= "  " . XmlTag('EndYear', $endYear) . "\n";
    
    $yearsInfo = getUSCoinYearsInfo($coinId);
    
    if (count($yearsInfo) > 0) {
      $stringData .= "  " . XmlStartTag('Years') . "\n";

      foreach ($yearsInfo as $coinSection => $cdata) {

        foreach ($cdata as $data) {
          $stringData .= "    " . XmlStartTag('Year') . "\n";
          
          $year = $data['year'];
          $yearInfo = $data['yearInfo'];
          $minted = $data['numberMinted'];
          $proofMinted = $data['proofMinted'];
          $title = $data['title'];
          $cid = $data['id'];
          
          $stringData .= "      " . XmlTag('Year', $year) . "\n";
          $stringData .= "      " . XmlTag('YearInfo', $yearInfo) . "\n";
          $stringData .= "      " . XmlTag('Minted', $minted) . "\n";
          $stringData .= "      " . XmlTag('ProofMinted', $proofMinted) . "\n";
          
          $stringData .= "      " . XmlStartTag('Grades') . "\n";
          foreach ($data['coinValues'] as $yearData) {
            $stringData .= "        " . XmlStartTag('Grade') . "\n";
            $grade = $yearData['title'];
            $value = $yearData['value'];
            
            $stringData .= "          " . XmlTag('Title', $grade) . "\n";
            $stringData .= "          " . XmlTag('Value', $value) . "\n";
            $stringData .= "        " . XmlEndTag('Grade') . "\n";
          }
          $stringData .= "      " . XmlEndTag('Grades') . "\n";
          $stringData .= "    " . XmlEndTag('Year') . "\n";

        }
      }
      $stringData .= "  " . XmlEndTag('Years') . "\n";
    }
    
    
//    $photos = getThumbnails($coinId);
    $photos = getImages($coinId);
    
    if (count($photos) > 0) {
      $stringData .= "  " . XmlStartTag('Photos') . "\n";
            
      foreach ($photos as $photo) {
        $stringData .= "    " . XmlStartTag('Photo') . "\n";
          
        $fileName = $photo['file'];
        $caption = $photo['caption'];

        $stringData .= "      " . XmlTag('FileName', $fileName) . "\n";
        $stringData .= "      " . XmlTag('Caption', $caption) . "\n";
        
        $compFileName = "$BASE/$fileName"; 
        $fnh = fopen($compFileName, "rb");
        $photoContents = fread($fnh, filesize($compFileName));
        fclose($fnh);
        
        $stringData .= "      " . XmlTag('Data', "<![CDATA[$photoContents]]>") . "\n";
        $stringData .= "    " . XmlEndTag('Photo') . "\n";
      }
      $stringData .= "  " . XmlEndTag('Photos') . "\n";
    }
    
    $stringData .= XmlEndTag('Coin');

    $fileName = strtolower("us-$typeName-$coinName.coin");
    
    echo "<br />$fileName";
    
    $fh = fopen("$coin_root/$fileName", 'w');
    
    fwrite($fh, $stringData);
    fclose($fh);
  }
}

?>
