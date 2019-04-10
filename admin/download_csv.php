<?php
if ($_GET['saveas']) {
    $savename= $_GET['saveas'] . ".csv";
    }
    else $savename='unknown.csv';
  $filename = 'temp.csv';
  if (file_exists($filename)){
  header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
  header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
  header("Content-Type: Application/octet-stream");
  header("Content-Disposition: attachment; filename=$savename");
  readfile($filename);
  }
  else echo 'file not found';
?>
