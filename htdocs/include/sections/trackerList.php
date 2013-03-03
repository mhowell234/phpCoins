<?php

include_once("$DOCUMENT_ROOT/include/utils.php");

echo "<h3><a href='/tracker/add'>&raquo; Add a Tracker</a></h3>";

statusDisplay();

$trackers = getTrackers();

if (sizeof($trackers) > 0) { 
$esid = '';
?>


  <table border="1" summary="Ebay Coin Trackers">
  <caption></caption>

  <thead><tr>
  <th scope='col'>#</th>
  <th scope='col'>Name</th>
  <th scope='col'>Coin</th>
  <th scope='col'>Denomination</th>
  <th scope='col'>Max Price</th>
  <th scope='col'>Auction End</th>
  <th scope='col'>?</th>
  </tr></thead>

  <tbody>

<?php 

$i = 0;

foreach ($trackers as $key => $row) {

    $name = get($row, 'name');
    $id = get($row, 'id');
    //  <th scope='col'>Description</th>

    //$description = get($row, 'description');
    $value = get($row, 'value');
    $cvid = get($row, 'cvid');
    $coin = '';
    if (isset($row['coin'])) {
      $coin = get($row, 'coin');
      $cid = get($row, 'cid');
    }
    $maxPrice = get($row, 'maxPrice');
    $timeLeft = get($row, 'timeLeft');
    
    ++$i;
    echo "<tr>";  

    echo "<td><a href='/tracker/view?trackerId=$id'>$i</a></td>";
    echo "<td>$name</td>";
    
    //echo "<td>$i</td>";
    //echo "<td><a href='/tracker/view?trackerId=$id'>$name</a></td>";
    //echo "<td>$description</td>";  
    echo "<td><a href='/us-coins/?typeId=$cid'>$coin</a></td>";  
    echo "<td><a href='/us-coins/?valueId=$cvid'>$value</a></td>";  
    echo "<td>$maxPrice</td>";  
    echo "<td>$timeLeft</td>";  
    echo "<td><a href='/tracker/delete?trackerId=$id'>Delete</a></td>";
    echo "</tr>";
    
  }
?>

  </tbody>
  </table>

<?php } else { ?>

<strong>No trackers found bitches.</strong>

<?php } ?>
