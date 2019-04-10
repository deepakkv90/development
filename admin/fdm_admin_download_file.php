<?php
/*
  $Id: fdm_admin_download_file.php,v 1.1.1.1 2007/01/09 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');

  if (!isset($_GET['fileid'])) {
    $messageStack->add_session('header', ERROR_NO_FILE_ID, 'error');
    tep_redirect(tep_href_link(FILENAME_LIBRARY_FILES));
  } else {
    $fileid = (int)$_GET['fileid'];
  }

  // Retrieve file information
  $downloads_query = ("SELECT lf.files_name
                         from " . TABLE_LIBRARY_FILES . " lf 
                       WHERE lf.files_id = '" . $fileid . "'");

  $result= tep_db_query($downloads_query); 
  $downloads = tep_db_fetch_array($result);

  $filename = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $downloads['files_name'];

  if (!file_exists($filename)) {  
    $messageStack->add_session('header', ERROR_FILE_DOES_NOT_EXIST, 'error');
    tep_redirect(tep_href_link(FILENAME_LIBRARY_FILES));
  } 
  
  if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
    header('Content-Type: application/octetstream');
    header("Pragma: public");
    header("Cache-control: private");
  } else {
    header('Content-Type: application/octet-stream');
    header("Pragma: no-cache");
  }
  header('Cache-Control: no-store, no-cache, must-revalidate' );
  header('Cache-Control: post-check=0, pre-check=0', false );
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
  header('Content-Transfer-Encoding: Binary');
  header("Content-length: " . filesize($filename));
  header("Content-disposition: attachment; filename=" . $downloads['files_name']);
  readfile($filename);
  die();
?>