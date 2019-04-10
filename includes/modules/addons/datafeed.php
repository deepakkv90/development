<?php
/*
  $Id: datafeed.php,v 1.0.0 2009/06/14 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class datafeed {
    var $title, $debug_info;

    function datafeed() {
      $this->code = 'datafeed';
      $this->title = (defined('MODULE_ADDONS_DATAFEED_TITLE')) ? MODULE_ADDONS_DATAFEED_TITLE : ''; 
      $this->description = (defined('MODULE_ADDONS_DATAFEED_DESCRIPTION')) ? MODULE_ADDONS_DATAFEED_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_ADDONS_DATAFEED_STATUS') && MODULE_ADDONS_DATAFEED_STATUS == 'True') ? true : false;
      $this->sort_order = (defined('MODULE_ADDONS_DATAFEED_SORT_ORDER')) ? (int)MODULE_ADDONS_DATAFEED_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                       from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_DATAFEED_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_DATAFEED_STATUS');
    }
    
    function hidden_keys() {
      return array('TABLE_DATA_FEEDS',
                   'FILENAME_DATA_MANAGER',
                   'CONTENT_DATA_MANAGER',
                   'BOX_DATA_MANAGER');
    }    

    function install() {
      global $languages_id;
                  
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Enable CRE Data Feed Module', 'MODULE_ADDONS_DATAFEED_STATUS', 'True', 'Select True to enable CRE Data Feed Management Module.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      // insert table constants
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('TABLE_DATA_FEEDS', 'data_feeds')"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('FILENAME_DATA_MANAGER', 'datafeeds.php')"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('CONTENT_DATA_MANAGER', 'datafeeds')"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('BOX_DATA_MANAGER', 'Data Feed Manager')"); 

      // create the database table
      tep_db_query("CREATE TABLE IF NOT EXISTS `data_feeds` (`feed_id` int(11) NOT NULL auto_increment,
                                                             `feed_name` varchar(32) NOT NULL default '',
                                                             `feed_type` varchar(32) NOT NULL default 'Basic',
                                                             `feed_desc` varchar(255) NOT NULL default '',
                                                             `feed_service` varchar(16) NOT NULL default '',
                                                             `feed_file_name` varchar(64) NOT NULL default '',
                                                             `feed_file_type` varchar(16) NOT NULL default '',
                                                             `feed_ftp_user` varchar(64) default NULL,
                                                             `feed_ftp_pass` varchar(64) default NULL,
                                                             `feed_language` varchar(2) NOT NULL default 'en',
                                                             `feed_currency` char(3) default 'USD',
                                                             `feed_tax_class` varchar(32) default 0,
                                                             `feed_price_group_id` int(11) default 0,  
                                                             `feed_auto_send` int(1) NOT NULL default 0,
                                                             `date_created` datetime NOT NULL default '0000-00-00 00:00:00',
                                                             `last_modified` datetime NOT NULL default '0000-00-00 00:00:00',
                                                              PRIMARY KEY  (`feed_id`)
                                                             ) ENGINE=MyISAM;");
    }

    function remove() {
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");  
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->hidden_keys()) . "')");  
    }
  }  
?>