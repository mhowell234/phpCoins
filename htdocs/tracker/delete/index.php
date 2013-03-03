<?php
  $navsection = 'tracker';

  include_once('../../include/db/db.php');
  include_once("$DOCUMENT_ROOT/include/utils.php");

  $trackerId = request_get('trackerId');

  if ($trackerId) {
    deleteTracker($trackerId);
   
    return header("Location: /tracker?info=Tracker deleted successfully!"); 
  }
  else {
    return header("Location: /tracker?error=No tracker id passed.");
  }

?>
