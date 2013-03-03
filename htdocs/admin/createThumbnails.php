<?php

include_once('../include/init.php');
include_once("$DOCUMENT_ROOT/include/utils.php");
require_once("$DOCUMENT_ROOT/include/phpthumb/phpthumb.class.php");

$photo_root = "$DOCUMENT_ROOT/photos";
$thumbnail_root = "$DOCUMENT_ROOT/thumbnails";
$thumbnail_width = 300;
$limit = 4000;


set_time_limit($limit);


$di = new RecursiveDirectoryIterator($photo_root);

foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
  
  if (strstr($filename, '.DS_Store')) {
    continue;
  }
  else if (strstr($filename, 'thumb_')) {
    continue;
  }
    
  $phpThumb = new phpThumb();
  
  echo $filename . ' - ' . $file->getSize() . ' bytes <br/>';
  
  $phpThumb->setSourceFilename($filename);
  
  $output_filename = str_replace($photo_root, $thumbnail_root, $filename);

  $path_parts = pathinfo($output_filename);

  $dir = $path_parts['dirname'];

  mkdir($dir, 0777, true);
  
  $phpThumb->setParameter('w', $thumbnail_width);


  $phpThumb->config_allow_src_above_docroot = true;

if ($phpThumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
	if ($phpThumb->RenderToFile($output_filename)) {
		// do something on success
		echo 'Successfully rendered to "'.$output_filename.'"';
	} else {
		// do something with debug/error messages
		echo 'Failed:<pre>'.implode("\n\n", $phpThumb->debugmessages).'</pre>';
	}
	$phpThumb->purgeTempFiles();
} else {
	// do something with debug/error messages
	echo 'Failed:<pre>'.$phpThumb->fatalerror."\n\n".implode("\n\n", $phpThumb->debugmessages).'</pre>';
}

}
  
?>