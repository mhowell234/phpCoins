<?php
  $navsection = 'tracker';


  include_once('../../include/init.php');
  include_once("$DOCUMENT_ROOT/include/utils.php");
  
  $trackerId = request('trackerId');
  $sendEmail = request('sendEmail', 1);

  if ($sendEmail) {
 
    $trackerStatus = batchTracker($trackerId);
    if ($trackerStatus == 0) {
      $success = "Tracker Email Sent!";
    }
    else if ($trackerStatus == 1) {
      $error = "Issue sending email...";
    }
    else if ($trackerStatus == 2) {
      $info = "No results for tracker. No email sent.";
    }
    else if ($trackerStatus == 3) {
      $info = "No emails specified for this tracker. So no email sent.";
    }
  }
  
  headerTitleMenu("Coin Trackers");

if ($error != '') {
  echo "<br /><p class='error'>$error</p><br />";
}
else if ($warning != '') {
  echo "<br /><p class='warning'>$warning</p><br />";
}
else if ($info != '') {
  echo "<br /><p class='info'>$info</p><br />";
}
else if ($success != '') {
  echo "<br /><p class='success'>$success</p><br />";
}

  section('tracker-view', 'trackerView', true);


  endHeaderTitleMenu();

  include("$DOCUMENT_ROOT/include/html/footer.php"); 
?>
