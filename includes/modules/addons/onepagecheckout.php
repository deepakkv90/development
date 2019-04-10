<?php
/*
  $Id: onepagecheckout.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class onepagecheckout {
    var $title, $debug_info;

    function onepagecheckout() {
      $this->code = 'onepagecheckout';
      if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_TITLE')) {
        $this->title = MODULE_ADDONS_ONEPAGECHECKOUT_TITLE;
      } else {
        $this->title = '';
      }      
      if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_DESCRIPTION')) {
        $this->description = MODULE_ADDONS_ONEPAGECHECKOUT_DESCRIPTION;
      } else {
        $this->description = '';
      }      
      if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_ONEPAGECHECKOUT_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_SORT_ORDER')) {
        $this->sort_order = (int)MODULE_ADDONS_ONEPAGECHECKOUT_SORT_ORDER;
      } else {
        $this->sort_order = '';
      }    
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_ONEPAGECHECKOUT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_ONEPAGECHECKOUT_STATUS');
    }

    function install() {
      global $languages_id;
                  
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Enable CRE One Page Checkout Module', 'MODULE_ADDONS_ONEPAGECHECKOUT_STATUS', 'True', 'Select True to enable CRE One Page Checkout.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      // insert filename constants
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('FILENAME_CHECKOUT_PROCESSING', 'checkout_processing.php')"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('CONTENT_CHECKOUT_PROCESSING', 'checkout_processing')"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('FILENAME_ORDER_CHECKOUT', 'order_checkout.php')");      
      // insert language for admin box heading
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('CONTENT_ORDER_CHECKOUT', 'order_checkout')"); 
    }

    function remove() {
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");  
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'FILENAME_CHECKOUT_PROCESSING'");
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'FILENAME_STATS_RECOVER_CART_SALES'");  
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'CONTENT_ORDER_CHECKOUT'");  
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'CONTENT_CHECKOUT_PROCESSING'");
    }
  }  
?>