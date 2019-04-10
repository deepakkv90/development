<?php
/*
  $Id: recovercarts.php,v 1.0.0 2008/05/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class recovercarts {
    var $title, $debug_info;

    function recovercarts() {
      $this->code = 'recovercarts';
      $this->title = (defined('MODULE_ADDONS_RECOVERCARTS_TITLE')) ? MODULE_ADDONS_RECOVERCARTS_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_RECOVERCARTS_DESCRIPTION')) ? MODULE_ADDONS_RECOVERCARTS_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_RECOVERCARTS_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_RECOVERCARTS_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_RECOVERCARTS_SORT_ORDER')) ? (int)MODULE_ADDONS_RECOVERCARTS_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                       from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_RECOVERCARTS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_RECOVERCARTS_STATUS', 
                   'RECOVER_CARTS_BASE_DAYS',
                   'RECOVER_CARTS_SKIP_DAYS',
                   'RECOVER_CARTS_REPORT_DAYS',
                   'RECOVER_CARTS_INCLUDE_TAX_IN_PRICES',
                   'RECOVER_CARTS_USE_FIXED_TAX_IN_PRICES',
                   'RECOVER_CARTS_FIXED_TAX_RATE',
                   'RECOVER_CARTS_EMAIL_TTL',
                   'RECOVER_CARTS_EMAIL_FRIENDLY',
                   'RECOVER_CARTS_EMAIL_COPIES_TO',
                   'RECOVER_CARTS_SHOW_ATTRIBUTES',
                   'RECOVER_CARTS_CHECK_SESSIONS',
                   'RECOVER_CARTS_CURCUST_COLOR',
                   'RECOVER_CARTS_UNCONTACTED_COLOR',
                   'RECOVER_CARTS_CONTACTED_COLOR',
                   'RECOVER_CARTS_MATCHED_ORDER_COLOR',
                   'RECOVER_CARTS_SKIP_MATCHED_CARTS',
                   'RECOVER_CARTS_AUTO_CHECK',
                   'RECOVER_CARTS_MATCH_ALL_DATES',
                   'RECOVER_CARTS_PENDING_SALE_STATUS',
                   'RECOVER_CARTS_REPORT_EVEN_STYLE',
                   'RECOVER_CARTS_REPORT_ODD_STYLE'
                   );
    }

    function install() {
      global $languages_id;
                  
      // insert module config values
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Enable Recover Cart Sales Module', 'MODULE_ADDONS_RECOVERCARTS_STATUS', 'True', 'Select True to enable Recover Cart Sales.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Lookback Days', 'RECOVER_CARTS_BASE_DAYS', '30', 'Default number of days to look back from today for abandoned carts. Today Equals 0 (zero)', '6', '2', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Skip Days', 'RECOVER_CARTS_SKIP_DAYS', '5', 'Number of days to skip when looking for abandoned carts.', '6', '3', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Sales Results Report Days', 'RECOVER_CARTS_REPORT_DAYS', '90', 'Number of days the sales results report takes into account. The more days the longer the SQL queries!', '6', '4', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Use Calculated Taxes', 'RECOVER_CARTS_INCLUDE_TAX_IN_PRICES', 'False', 'Try to calculate the taxes when determining prices. This may not be 100% correct as determing location being shopped from, etc. may be incorrect.', '6', '5', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Use Fixed Tax Rate', 'RECOVER_CARTS_USE_FIXED_TAX_IN_PRICES', 'False', 'Use a fixed tax rate when determining prices (rate set below). Overridden if Use Calculated Taxes is True.', '6', '6', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Fixed Tax Rate', 'RECOVER_CARTS_FIXED_TAX_RATE', '7.00', 'The fixed tax rate for use when Use Fixed Tax Rate is True and Use Calculated Taxes is False.', '6', '7', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('E-mail Time to Live', 'RECOVER_CARTS_EMAIL_TTL', '90', 'Number of days to give for emails before they no longer show as being sent. Default = 90', '6', '8', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Friendly E-Mails', 'RECOVER_CARTS_EMAIL_FRIENDLY', 'True', 'If <b>True</b> then the customer\'s name will be used in the greeting. If <b>False</b> then a generic greeting will be used.', '6', '9', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('E-mail Copies To', 'RECOVER_CARTS_EMAIL_COPIES_TO', '', 'If you want copies of emails that are sent to customers, enter the email address here. If empty no copies are sent.', '6', '10', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Show Attributes', 'RECOVER_CARTS_SHOW_ATTRIBUTES', 'False', 'Controls display of item attributes.<br><br>Set this to <b>True</b> if your site uses attributes and you want to show them, otherwise set to <b>False</b>.', '6', '11', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Ignore Customers with Sessions', 'RECOVER_CARTS_CHECK_SESSIONS', 'True', 'If you want the tool to ignore customers with an active session (ie, probably still shopping) set this to <b>True</b>.<br><br>Setting this to <b>False</b> will operate in the default manner of ignoring session data &amp; using less resources.', '6', '12', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Current Customer Color', 'RECOVER_CARTS_CURCUST_COLOR', '#0000FF', 'Color for the word/phrase used to notate a current customer<br><br>A current customer is someone who has purchased items from your store in the past.', '6', '12', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Contacted Highlight Color', 'RECOVER_CARTS_CONTACTED_COLOR', '#FF9F9F', 'Row highlight color for contacted customers.<br><br>A contacted customer is one that you <i>have</i> used this tool to send an email to before.', '6', '13', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Uncontacted Highlight Color', 'RECOVER_CARTS_UNCONTACTED_COLOR', '#9FFF9F', 'Row highlight color for uncontacted customers.<br><br>An uncontacted customer is one that you have <i>not</i> used this tool to send an email to before.', '6', '14', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Matching Order Highlight Color', 'RECOVER_CARTS_MATCHED_ORDER_COLOR', '#9FFFFF', 'Row highlight color for entries that may have a matching order.', '6', '15', now(), now(), NULL, NULL)");
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Skip Carts w/Matched Orders', 'RECOVER_CARTS_SKIP_MATCHED_CARTS', 'False', 'To ignore carts with a matching order set this to <b>False</b>.<br><br>Setting this to <b>True</b> will cause entries with a matching order to show, along with the matching order\'s status.', '6', '15', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Autocheck Safe Carts to Email', 'RECOVER_CARTS_AUTO_CHECK', 'True', 'To check entries which are most likely safe to email (ie, not existing customers, not previously emailed, etc.) set this to <b>True</b>.<br><br>Setting this to <b>False</b> will leave all entries unchecked (must manually check entries to send an email to).', '6', '16', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Match Orders From Any Date', 'RECOVER_CARTS_MATCH_ALL_DATES', 'True', 'If <b>True</b> then any order found with a matching item will be considered a matched order.<br><br>If <b>False</b> only orders placed after the abandoned cart are considered.', '6', '17', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");       
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Lowest Pending Sales Status', 'RECOVER_CARTS_PENDING_SALE_STATUS', '1', 'The highest value that an order can have and still be considered pending. Any value higher than this will be considered by RAC as sale which completed.', '6', '18', now(), now(), 'tep_get_order_status_name', 'tep_cfg_pull_down_order_statuses(')");     
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Report Even Row Style', 'RECOVER_CARTS_REPORT_EVEN_STYLE', 'dataTableRow', 'Style for even rows in results report. Typical options are <i>dataTableRow</i> and <i>attributes-even</i>.', '6', '19', now(), now(), NULL, NULL)"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('Report Odd Row Style', 'RECOVER_CARTS_REPORT_ODD_STYLE', '', 'Style for odd rows in results report. Typical options are NULL (ie, no entry) and <i>attributes-odd</i>.', '6', '20', now(), now(), NULL, NULL)");             
   
      // insert database table constants
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('TABLE_RECOVER_CARTS', 'recover_carts')");
      // insert filename constants
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('FILENAME_RECOVER_ABANDONED_CARTS', 'recover_abandoned_carts.php')"); 
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('FILENAME_STATS_RECOVER_ABANDONED_CARTS', 'stats_recover_abandoned_carts.php')");      
      // insert language for admin box heading
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('BOX_RECOVER_ABANDONED_CARTS', 'Abandoned Sales')"); 
      // insert version
      tep_db_query("INSERT IGNORE INTO `configuration` (`configuration_key`, `configuration_value`) VALUES ('MODULE_ADDONS_RECOVERCARTS_VERSION', '1.0')");
      // create database
      tep_db_query("CREATE TABLE IF NOT EXISTS `recover_carts` (
                      `scartid` int(11) NOT NULL auto_increment,
                      `customers_id` int(11) NOT NULL default 0,
                      `dateadded` varchar(8) NOT NULL default '',
                      `datemodified` varchar(8) NOT NULL default '',
                      PRIMARY KEY  (`scartid`)
                  ) ENGINE=MyISAM;");
    }

    function remove() {
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");  
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'TABLE_RECOVER_CARTS'"); 
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'FILENAME_RECOVER_ABANDONED_CARTS'");
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'FILENAME_STATS_RECOVER_ABANDONED_CARTS'");  
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'BOX_RECOVER_ABANDONED_CARTS'");
      tep_db_query("DELETE FROM `configuration` WHERE configuration_key = 'MODULE_ADDONS_RECOVERCARTS_VERSION'"); 
      tep_db_query("DROP TABLE IF EXISTS `recover_carts`");
    }
  }  
?>