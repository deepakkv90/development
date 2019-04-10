<?php
/*
  $Id: fdm.php,v 1.1.1.1 2006/08/18 23:41:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class fdm {
    var $title, $output;

    // class constructor
    function fdm() {
      $this->code = 'fdm';
      if (defined('MODULE_CHECKOUT_SUCCESS_FDM_TITLE')) {
        $this->title = MODULE_CHECKOUT_SUCCESS_FDM_TITLE;
      } else {
        $this->title = '';
      }
      if (defined('MODULE_CHECKOUT_SUCCESS_FDM_DESCRIPTION')) { 
        $this->description = MODULE_CHECKOUT_SUCCESS_FDM_DESCRIPTION;
      } else {
        $this->description = '';
      }   
      if (defined('MODULE_CHECKOUT_SUCCESS_FDM_STATUS')) {
        $this->enabled = ((MODULE_CHECKOUT_SUCCESS_FDM_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      if (defined('MODULE_CHECKOUT_SUCCESS_FDM_SORT_ORDER')) {
        $this->sort_order = (int)MODULE_CHECKOUT_SUCCESS_FDM_SORT_ORDER;
      } else {
        $this->sort_order = 0;
      }        
      $this->output = array();
    }

    function process() {
      global $languages_id, $language;

      if (!$this->enabled) { return; }

      require_once(DIR_WS_CLASSES . FILENAME_DOWNLOAD);
      require_once(DIR_WS_LANGUAGES . $language . '/modules/checkout_success/fdm.php');

      $products_array = array();
      $products_query = tep_db_query("SELECT products_id 
                                                         from " . TABLE_ORDERS_PRODUCTS . " 
                                                       WHERE orders_id = '" . (int)$_GET['order_id'] . "' 
                                                       ORDER BY products_id");

      while ($products = tep_db_fetch_array($products_query)) {
                   $products_array[] = array('id' => $products['products_id']);
      }

      $download = new download();
      $output = '';
      $is_files = false;
      for ($i=0;$i < sizeof($products_array);$i++) {
        $is_files_query = tep_db_query("SELECT lp.library_id 
                                                        from " . TABLE_LIBRARY_PRODUCTS . " lp 
                                                      WHERE lp.products_id = '" . (int)$products_array[$i]['id'] . "'");
        if (tep_db_num_rows($is_files_query) > 0) { $is_files = true; }
      }
      if ($is_files == true) {
        if (MODULE_CHECKOUT_SUCCESS_FDM_TABLE_BORDER == 'True') {
          $output .= '<tr><td valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="infoBox"><table width="100%" border="0" cellspacing="0" cellpadding="1">';
          $output .= '<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="1"><tr><td class="infoboxContents"><table width="100%" border="0" cellspacing=0 cellpadding="4"><tr><td>';
        }
        $output .= '<table width="100%" cellpadding="3" cellspacing="0" border="0"><tr class="filesListingBoxHeader"><td class="infoBoxHeading">' . TEXT_RELATED_FILES . '</td></tr><tr><td class="fileListing_left"><table width="100%"  border="0" cellspacing="0" cellpadding="3" align="center"><tr class="filesListingCol">';
        $output .= '<td class="fileListing_left_header" width="20%" align="center">' . TEXT_DOWNLOAD_FILE . '</td><td class="fileListing_left_header" width="40%">' .  TEXT_MORE_INFO . '</td><td class="fileListing_left_header" width="40%">' . TEXT_FILE_NAME . '</td></tr>';
        for ($i=0;$i < sizeof($products_array);$i++) {
          if (isset($products_array[$i]['id'])) {
             $sql_query =tep_db_query("SELECT lp.library_id, lf.files_name, lfde.files_descriptive_name, fi.icon_small 
                                                      from " . TABLE_LIBRARY_FILES . " lf, 
                                                             " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfde, 
                                                             " . TABLE_LIBRARY_PRODUCTS . " lp, 
                                                             " . TABLE_FILE_ICONS . " fi 
                                                    WHERE lfde.language_id = '" . $languages_id . "' 
                                                      and lp.library_id = lf.files_id 
                                                      and lf.files_id = lfde.files_id 
                                                      and lf.files_icon = fi.icon_id 
                                                      and lf.files_status = '1' 
                                                      and lp.products_id = '" . (int)$products_array[$i]['id'] . "'
                                                      and lp.download_show = '1'");
            $num_files_related = tep_db_num_rows($sql_query);   
            if($num_files_related > 0) {
              $row = 0;
              $col = 0;
              $info_box_contents = array();
              $info_box_contents[$row][$col] =array( 'params' => 'class="navBbrown" width="100%" valign="top"');
              while($listing_array=tep_db_fetch_array($sql_query)) {
                $download->process($listing_array['library_id'],$_SESSION['customer_id']); 
                $download_criteria = $download->file_content;        
                $output .= '<tr><td class="fileListing_left" width="20%" align="center">' . $download_criteria . '</td>';
                $output .= '<td class="fileListing" width="40%" ><a href="'.tep_href_link(FILENAME_FILE_DETAIL,'file_id='.$listing_array['library_id']).'">' .$listing_array['files_descriptive_name'].'</a></td>';
                $output .= '<td class="fileListing"><table width="40%" cellpadding="0" cellspacing="0" border="0"><tr>';
                $output .= '<td align="left" class="main"><a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $listing_array['library_id']) . '">' . tep_image('images/file_icons/' . $listing_array['icon_small']) . '</a></td>';
                $output .= '<td align="left" style="padding-left:2" class="main"><a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $listing_array['library_id']) . '">' . $listing_array['files_name'] . '</a></td></tr></table></td></tr>';
              }
            }                     
          }
        }
        $output .= '</table></td></tr></table>';
        if (defined('MODULE_CHECKOUT_SUCCESS_FDM_TABLE_BORDER') && MODULE_CHECKOUT_SUCCESS_FDM_TABLE_BORDER == 'True') {
          $output .= '</td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr>';
        }
        $this->output[] = array('text' => $output);
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_CHECKOUT_SUCCESS_FDM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable File Distribution System', 'MODULE_CHECKOUT_SUCCESS_FDM_STATUS', 'True', 'Do you want to enable the File Distribution System checkout success module?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Table Border', 'MODULE_CHECKOUT_SUCCESS_FDM_TABLE_BORDER', 'False', 'Display output within a table border on checkout success?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CHECKOUT_SUCCESS_FDM_SORT_ORDER', '0', 'Sort order of display.', '6', '3', now())");     
    }

    function keys() {
      return array('MODULE_CHECKOUT_SUCCESS_FDM_STATUS', 'MODULE_CHECKOUT_SUCCESS_FDM_TABLE_BORDER', 'MODULE_CHECKOUT_SUCCESS_FDM_SORT_ORDER');
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>