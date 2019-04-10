<?php
/*
  $Id: affiliate_functions.php,v 1.1.1.1 2004/03/04 23:40:47 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  function affiliate_check_url($url) {
    return eregi("^https?://[a-z0-9]([-_.]?[a-z0-9])+[.][a-z0-9][a-z0-9/=?.&\~_-]+$",$url);
  }

  function affiliate_insert ($sql_data_array, $affiliate_parent = 0) {
    // LOCK TABLES
    tep_db_query("LOCK TABLES " . TABLE_AFFILIATE . " WRITE");
    if ($affiliate_parent > 0) {
      $affiliate_root_query = tep_db_query("select affiliate_root, affiliate_rgt, affiliate_lft from  " . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_parent . "' ");
      // Check if we have a parent affiliate
      if ($affiliate_root_array = tep_db_fetch_array($affiliate_root_query)) {
        tep_db_query("update " . TABLE_AFFILIATE . " SET affiliate_lft = affiliate_lft + 2 WHERE affiliate_root  =  '" . $affiliate_root_array['affiliate_root'] . "' and  affiliate_lft > "  . $affiliate_root_array['affiliate_rgt'] . "  AND affiliate_rgt >= " . $affiliate_root_array['affiliate_rgt'] . " ");
        tep_db_query("update " . TABLE_AFFILIATE . " SET affiliate_rgt = affiliate_rgt + 2 WHERE affiliate_root  =  '" . $affiliate_root_array['affiliate_root'] . "' and  affiliate_rgt >= "  . $affiliate_root_array['affiliate_rgt'] . "  ");


        $sql_data_array['affiliate_root'] = $affiliate_root_array['affiliate_root'];
        $sql_data_array['affiliate_lft'] = $affiliate_root_array['affiliate_rgt'];
        $sql_data_array['affiliate_rgt'] = ($affiliate_root_array['affiliate_rgt'] + 1);
        tep_db_perform(TABLE_AFFILIATE, $sql_data_array);
        $affiliate_id = tep_db_insert_id();
      }
    // no parent -> new root
    } else {
      $sql_data_array['affiliate_lft'] = '1';
      $sql_data_array['affiliate_rgt'] = '2';
      tep_db_perform(TABLE_AFFILIATE, $sql_data_array);
      $affiliate_id = tep_db_insert_id();
      tep_db_query ("update " . TABLE_AFFILIATE . " set affiliate_root = '" . $affiliate_id . "' where affiliate_id = '" . $affiliate_id . "' ");
    }
    // UNLOCK TABLES
    tep_db_query("UNLOCK TABLES");
    return $affiliate_id;

  }

  
  function affiliate_id_valid($affiliate_id) {
    // this function checks to see if the affilaite id is in the table
    $affiliate_query = tep_db_query("select affiliate_id from  " . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_id . "' ");
    if (tep_db_num_rows($affiliate_query) > 0 ) {
      return true;
    } else {
      return false;
    }
  }

?>
