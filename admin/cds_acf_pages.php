<?php
/*
  $Id: cds_acf_pages.php,v 1.1.1.1 2007/05/21 23:41:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  global $languages_id;

  $data = '';
  // erase the previous cds_acf_pages.txt file
  $sf = @fopen(DIR_FS_CATALOG . '/includes/languages/cds_acf_pages.txt', "w");
  @fwrite($sf, $data);
  @fclose($sf);
  // get all ACF filenames from database - sorted by alpha description asc.
  $acf_array = array();
  $acf_query = tep_db_query("SELECT * 
                                             from " . TABLE_PAGES_DESCRIPTION . " 
                                           WHERE pages_file IS NOT NULL  
                                             and language_id = '" . $languages_id . "' 
                                           ORDER BY pages_file");
  while ($acf = tep_db_fetch_array($acf_query)) {  
    if (tep_not_null($acf['pages_file'])) {
     $data .= $acf['pages_file'] . "\n"; 
    }
    // write to cds_acf_pages.txt
    $sf = @fopen(DIR_FS_CATALOG . '/includes/languages/cds_acf_pages.txt', "w+");
    @fwrite($sf, $data);
    @fclose($sf);
  }
?>