<?php
/*
  $Id: googleanalytics.php,v 1.0.0 2008/05/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

class googleanalytics {
  var $title;

  function googleanalytics() {
    $this->code = 'googleanalytics';
    if (defined('MODULE_ADDONS_GOOGLEANALYTICS_TITLE')) {
      $this->title = MODULE_ADDONS_GOOGLEANALYTICS_TITLE;
    } else {
      $this->title = '';
    }      
    if (defined('MODULE_ADDONS_GOOGLEANALYTICS_DESCRIPTION')) {
      $this->description = MODULE_ADDONS_GOOGLEANALYTICS_DESCRIPTION;
    } else {
      $this->description = '';
    }      
    if (defined('MODULE_ADDONS_GOOGLEANALYTICS_STATUS')) {
      $this->enabled = ((MODULE_ADDONS_GOOGLEANALYTICS_STATUS == 'True') ? true : false);
    } else {
      $this->enabled = false;
    }
    if (defined('MODULE_ADDONS_GOOGLEANALYTICS_SORT_ORDER')) {
      $this->sort_order = (int)MODULE_ADDONS_GOOGLEANALYTICS_SORT_ORDER;
    } else {
      $this->sort_order = '';
    }    
  }

  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                   WHERE configuration_key = 'MODULE_ADDONS_GOOGLEANALYTICS_STATUS'");
      $this->_check = tep_db_num_rows($check_query);
    }
    return $this->_check;
  }

  function keys() {
    return array('MODULE_ADDONS_GOOGLEANALYTICS_STATUS', 
                 'MODULE_ADDONS_GOOGLEANALYTICS_SITEMAP_STATUS',
                 'GOOGLEANALYTICS_UA_NUMBER',
                 'GOOGLEANALYTICS_SITEMAP_TIMESTAMP',
                 'GOOGLEANALYTICS_SITEMAP_CHANGE_FREQ',
                 'GOOGLEANALYTICS_SITEMAP_CHANGE_PRIORITY',
                 );
  }

  function install() {
    global $languages_id;
                
    // insert module config values
    tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Enable Google Analytics', 'MODULE_ADDONS_GOOGLEANALYTICS_STATUS', 'True', 'Select True to enable the Google Analytics/Sitemap module.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Google Analytics Account Number', 'GOOGLEANALYTICS_UA_NUMBER', 'UA-', 'Enter your Google Analytics account number.  This number should start with UA-', '6', '2', now(), now(), NULL, NULL)");
    tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Enable Google XML Sitemap', 'MODULE_ADDONS_GOOGLEANALYTICS_SITEMAP_STATUS', 'True', 'Select True to enable the dynamic Google XML Sitemap.', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Sitemap Last Updated Date Reference', 'GOOGLEANALYTICS_SITEMAP_TIMESTAMP', 'Server Date', 'This value tells Google when the file was last updated, so that if Google has already indexed the page, it does not need to do it again.  Choices are Server Date, Product Added Date, and Product Modified Date.', '6', '4', 'tep_cfg_select_option(array(\'Server Date\', \'Date Added\', \'Date Modified\'), ', now())");  
    tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Sitemap Crawl Frequency', 'GOOGLEANALYTICS_SITEMAP_CHANGE_FREQ', 'weekly', 'This value is used to indicate to Google how often the file is updated.', '6', '5', 'tep_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\',\'yearly\',\'never\'), ', now())");  
    tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Sitemap Priority', 'GOOGLEANALYTICS_SITEMAP_CHANGE_PRIORITY', '0.5', 'Enter the default priority for Google Sitemap XML. Valid values range from 0.0 to 1.0. The default priority of a page is 0.5.<br><br>Note: Priority has no effect on your rankings in the Google Search Engine.', '6', '6', now(), now(), NULL, NULL)");
    // insert version
    tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('MODULE_ADDONS_GOOGLEANALYTICS_VERSION', '1.0')");
   }
    
  function remove() {
    tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");  
    tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'MODULE_ADDONS_GOOGLEANALYTICS_VERSION'"); 
  }
}  
?>