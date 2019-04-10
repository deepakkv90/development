<?php
/*
  $Id: banners.php,v 1.1.1.1 2006/10/05 23:41:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class banners {
    var $title, $output;

// class constructor
    function banners() {
      $this->code = 'banners';
      $this->title = MODULE_CHECKOUT_SUCCESS_BANNERS_TITLE;
      $this->description = MODULE_CHECKOUT_SUCCESS_BANNERS_DESCRIPTION;
      if (defined('MODULE_CHECKOUT_SUCCESS_BANNERS_STATUS')) {
        $this->enabled = ((MODULE_CHECKOUT_SUCCESS_BANNERS_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order = MODULE_CHECKOUT_SUCCESS_BANNERS_SORT_ORDER;
      $this->output = array();
    }

    function process() {
      global $languages_id;

      if (!$this->enabled) { return; }

      $output_text ='';
      $bID = (int)MODULE_CHECKOUT_SUCCESS_BANNERS_ID;
      if($bID != 0){ 
        if (MODULE_CHECKOUT_SUCCESS_BANNERS_TABLE_BORDER == 'True') {
          $output_text .= '<tr><td valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td bgcolor="#99AECE"><table width="100%" border="0" cellspacing="0" cellpadding="1">';
          $output_text .= '<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="1"><tr><td bgcolor="#f8f8f9"><table width="100%" border="0" cellspacing="0" cellpadding="4"><tr><td>';
        }
        $output_text .= '<tr><td><table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
        $output_text .= '<td align="center" style="padding-bottom:8px">' . tep_display_banner('static', (int)$bID) . '</td>';
        $output_text .= '</tr></table></td></tr>';
        if (MODULE_CHECKOUT_SUCCESS_BANNERS_TABLE_BORDER == 'True') {
          $output_text .= '</td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr>';
        }
      }
      $this->output[] = array('text' => $output_text);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_CHECKOUT_SUCCESS_BANNERS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function install() {
      global $languages_id;
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Banners Module?', 'MODULE_CHECKOUT_SUCCESS_BANNERS_STATUS', 'True', 'Do you want to enable the Banners checkout success module?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Table Border?', 'MODULE_CHECKOUT_SUCCESS_BANNERS_TABLE_BORDER', 'False', 'Display output within a table border on checkout success?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Banner to Display', 'MODULE_CHECKOUT_SUCCESS_BANNERS_ID', '0', 'Select the Banner that you want to display on checkout success.<br>', '6', '2', 'tep_cfg_pull_down_banners(', 'tep_get_banners_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CHECKOUT_SUCCESS_BANNERS_SORT_ORDER', '0', 'Sort order of display.', '6', '3', now())");     
    }

    function keys() {
      return array('MODULE_CHECKOUT_SUCCESS_BANNERS_STATUS', 
                       'MODULE_CHECKOUT_SUCCESS_BANNERS_TABLE_BORDER', 
                       'MODULE_CHECKOUT_SUCCESS_BANNERS_ID', 
                       'MODULE_CHECKOUT_SUCCESS_BANNERS_SORT_ORDER');

    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }

  function tep_cfg_pull_down_banners($banners_id, $key = '') {

      $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

      $banners_array = array(array('id' => '0', 'text' => TEXT_BANNERS_DEFAULT));
      $banners_query = tep_db_query("select banners_id, banners_title from " . TABLE_BANNERS . " where status = '1' order by banners_title");
      while ($banners = tep_db_fetch_array($banners_query)) {
        $banners_array[] = array('id' => $banners['banners_id'],
                                        'text' => $banners['banners_title']);
      }
      return tep_draw_pull_down_menu($name, $banners_array, $banners_id);
    }

    function tep_get_banners_name($banners_id, $language_id = '') {

      if ($banners_id < 1) return TEXT_BANNERS_DEFAULT;

      $banner_query = tep_db_query("select banners_title from " . TABLE_BANNERS . " where banners_id = '" . (int)$banners_id . "'");
      $banner = tep_db_fetch_array($banner_query);

      return $banner['banners_title'];
    } 
?>