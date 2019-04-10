<?php
/*
  $Id: fdm_download_file.php,v 1.1.1.1 2006/10/13 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DOWNLOAD_FILE);
  require(DIR_WS_CLASSES . FILENAME_DOWNLOAD);
  
  if (!isset($_GET['fileid'])) {
    $messageStack->add_session('header', ERROR_NO_FILE_ID, 'error');
    tep_redirect(tep_href_link(FILENAME_FOLDER_FILES));
  } else {
    $fileid = (int)$_GET['fileid'];
  }

  // Retrieve file information
  $downloads_query = ("SELECT lp.library_id, lp.login_required, lp.purchase_required, lfd.files_descriptive_name, lf.files_name, lf.files_id, lf.require_products_id 
                         from " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, " . TABLE_LIBRARY_FILES . " lf 
                       LEFT JOIN " . TABLE_LIBRARY_PRODUCTS . " lp 
                         on lf.files_id = lp.library_id 
                       WHERE lf.files_id = lfd.files_id 
                         and lfd.language_id = '" . (int)$languages_id . "' 
                         and lf.files_id = '" . $fileid . "'");

  $result = tep_db_query($downloads_query); 
  $downloads = tep_db_fetch_array($result);

  if (sizeof($downloads) == 0) {
    $messageStack->add_session('header', ERROR_FILE_INFORMATION_DOES_NOT_EXIST, 'error');
    tep_redirect(tep_href_link(FILENAME_FOLDER_FILES));
  }

  $filename = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $downloads['files_name']; 

  if (!file_exists($filename)) {  
    $messageStack->add_session('header', ERROR_FILE_DOES_NOT_EXIST, 'error');
    tep_redirect(tep_href_link(FILENAME_FOLDER_FILES));
  } 

   if (isset($_SESSION['customer_id'])) {
    $current_customer_id = (int)$_SESSION['customer_id'];
  } else {
    $current_customer_id = 0;
  }
  
  $fsize = filesize($filename);
  $download = new download();
  $download->process($fileid, $current_customer_id);
   
  if ($download->download_flag == false) {
    $messageStack->add_session('header', ERROR_FILE_NOT_DOWNLOADABLE, 'error');
    tep_redirect(tep_href_link(FILENAME_FOLDER_FILES));
  }
  
  $ip_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';  
  $page = explode('?', basename($_SERVER['HTTP_REFERER']));
  $sql_data_array = array('files_id' => $fileid,
                          'customers_id' => $current_customer_id,
                          'download_time' => 'now()',
                          'page' => $page[0],
                          'ip_addr' => $ip_address, 
                          'file_size' => $fsize);
  tep_db_perform(TABLE_LIBRARY_FILES_DOWNLOAD, $sql_data_array);
   
  // Now decrement counter
  tep_db_query("UPDATE " . TABLE_LIBRARY_FILES . " 
                SET files_download = files_download + 1 
                WHERE files_id = '" . $downloads['files_id'] . "'");
  
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