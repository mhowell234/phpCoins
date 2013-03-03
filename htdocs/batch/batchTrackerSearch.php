<?php

include("../include/init.php");
include("$DOCUMENT_ROOT/include/utils.php");


$trackers = getTrackers();

foreach ($trackers as $tracker) {
  $trackerId = $tracker['id'];
  print "Tracking $trackerId - ";

  $trackerConverted = convertTracker($tracker);
  print $trackerConverted['name'];
  print ' - ';
  
  $trackerStatus = batchTracker($trackerId);
  if ($trackerStatus == 0) {
    print "Tracker Email Sent!";
  }
  else if ($trackerStatus == 1) {
    print "Issue sending email...";
  }
  else if ($trackerStatus == 2) {
    print "No results for tracker. No email sent.";
  }
  else if ($trackerStatus == 3) {
    print "No emails specified for this tracker. So no email sent.";
  }  
  print "<br />";
}

?>

