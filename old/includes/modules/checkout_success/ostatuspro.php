<?php
/*
  $Id: ostatuspro.php,v 1.1.1.1 2007/03/19 23:41:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ostatuspro {
    var $title, $output;

    function ostatuspro() {
      $this->code = 'ostatuspro';
      if (defined('MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_TITLE')) {
        $this->title = MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_TITLE;
      } else {
        $this->title = '';
      }      
      if (defined('MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DESCRIPTION')) {
        $this->description = MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DESCRIPTION;
      } else {
        $this->description = '';
      }       
      if (defined('MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS')) {
        $this->enabled = ((MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      if (defined('MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_SORT_ORDER')) {
        $this->sort_order = (int)MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_SORT_ORDER;
      } else {
        $this->sort_order = '';
      }      
      $this->output = array();
    }

    function process() {
      global $languages_id, $sppc_customer_group_id, $customer_id, $order_id, $output_text;

      if (!$this->enabled) { return; }

      // get the order so we know what we are working with
      require_once(DIR_WS_CLASSES . 'order.php');
      $oID = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
      $order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;  
      if ($oID == 0) { return; }
      $order = new order($oID);
      //groups logic
      include_once(DIR_WS_INCLUDES . 'version.php');
      if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
        $group_query = tep_db_query("select customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" . $sppc_customer_group_id . "'");
        $group = tep_db_fetch_array($group_query);
        $ok_to_process = (eregi($group['customers_group_name'], MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_GROUPS)) ? true : false;
      } else {
        $ok_to_process = true;
      }
      // process triggers which are cumulative
      if ($ok_to_process) {
        $error_text = '';
        $trigger = false;
        // order amount trigger
        if (round(MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_AMOUNT, 2) > 0.00) {
          if (round($order->info['total_value'], 2) >= round(MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_AMOUNT, 2)) {   
            $trigger = true;
            $error_text .= ERROR_TEXT_OSTATUSPRO_AMOUNT . '=' . round($order->info['total_value'], 2) . "\n";
          }
        }
        // total weight trigger
        if (round(MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_WEIGHT_AMOUNT, 2) > 0.00) {
          $total_weight = 0.00;
          for ($i=0; $i < sizeof($order->products); $i++) {
            $product_info = tep_db_fetch_array(tep_db_query("SELECT products_weight from " . TABLE_PRODUCTS . " WHERE products_id = '" . (int)$order->products[$i]['id'] . "'"));
            $total_weight = (float)$total_weight + ((float)$product_info['products_weight'] * (int)$order->products[$i]['qty']);
          }
          if (round($total_weight, 2) >= round(MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_WEIGHT_AMOUNT, 2)) {
            $error_text .= ERROR_TEXT_OSTATUSPRO_WEIGHT . '=' . $total_weight . "\n";
            $trigger = true;
          }
        }
        // total quantity filter
        if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_QTY > 0) {
          $total_qty = 0;
          for ($i=0; $i < sizeof($order->products); $i++) {
            $total_qty = $total_qty + $order->products[$i]['qty'];
          }
          if ($total_qty >= MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_QTY) {
            $error_text .= ERROR_TEXT_OSTATUSPRO_QTY . '=' . $total_qty . "\n";
            $trigger = true;
          }
        }
        // order time filter
        if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_TIME > 0) {
          // last order datetime
          $last_order_datetime_query = tep_db_fetch_array(tep_db_query("SELECT DISTINCT date_purchased from " . TABLE_ORDERS . " WHERE customers_id = '" . (int)$customer_id . "' AND orders_id <> '" . (int)$order_id . "' ORDER BY orders_id DESC"));
          $last_order_datetime = strtotime($last_order_datetime_query['date_purchased']);
          // this order datetime
          $this_order_datetime = strtotime($order->info['date_purchased']);
          $diff = $this_order_datetime - $last_order_datetime;
          if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_TIME > $diff) {
            $error_text .= ERROR_TEXT_OSTATUSPRO_TIME . '=' . $diff;
            $trigger = true;
          }        
        }      
        // address mismatch trigger
        if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ADDRESS_MISMATCH == 'True') {
          if ( (is_array($order->billing)) && (is_array($order->delivery)) ) {
            $result = array_diff($order->billing, $order->delivery);
            if (sizeof($result) > 0) {
              $error_text .= ERROR_TEXT_OSTATUSPRO_ADDR_MISMATCH;
              $trigger = true;
            }
          } else if (!is_array($order->delivery)) {  // no delivery address
            $error_text .= ERROR_TEXT_OSTATUSPRO_ADDR_MISMATCH;
            $trigger = true;
          }
        }
        // check hold list and trigger if customer exists in hold list
        if ((MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_HOLD_LIST == 'Use Hold List') || (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_HOLD_LIST == 'Use Hold List with Auto Add')) {
          // check if customer already exists in the hold list
          $customer_hold_query = tep_db_query("SELECT * from `orders_hold_list` WHERE holdlist_email = '" . $order->customer['email_address'] . "'");
          $customer_hold = tep_db_fetch_array($customer_hold_query);
          if ($customer_hold['holdlist_id'] != NULL) {
            $error_text .= ERROR_TEXT_OSTATUSPRO_HOLD_LIST;
            $trigger = true;
          }
          // add the customer if not already in the hold list and trigger = true and Auto add selected
          if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_HOLD_LIST == 'Use Hold List with Auto Add') {
            if (($trigger) && ($customer_hold['holdlist_id'] == NULL)) {
              $error_text .= ERROR_TEXT_OSTATUSPRO_HOLD_LIST_ADD;
              tep_db_query("INSERT INTO `orders_hold_list` VALUES ('','" . $order->customer['email_address'] . "', now(), now())");
            }
          }
        }
        // show message page
        if ($trigger == true) {
          if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_PAGE != 0) {
            $output_text ='';
            $pID = MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_PAGE;
            $pages_page_query = tep_db_query("select pages_title, pages_body from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$pID . "' and language_id = '" . (int)$languages_id . "'");
            if ($pages_page = tep_db_fetch_array($pages_page_query)) {
              //display message page
              if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_TABLE_BORDER == 'True') {
                $output_text .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td bgcolor="#99AECE"><table width="100%" border="0" cellspacing="0" cellpadding="1">';
                $output_text .= '<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="1"><tr><td bgcolor="#f8f8f9"><table width="100%" border="0" cellspacing="0" cellpadding="4"><tr><td>';
              }
              $output_text .= '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>';
              if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_DISPLAY_TITLE == 'True') {
                $output_text .= '<tr><td class="pageHeading">' . $pages_page['pages_title'] . '</td></tr>';
              }
              $output_text .= '<tr><td class="main">' . $pages_page['pages_body'] . '</td></tr>';
              $output_text .= '</td></tr></table>';
              if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_TABLE_BORDER == 'True') {
                $output_text .= '</td></tr></table></td></tr></table></td></tr></table></td></tr></table>';
              }
            }
            if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEBUG == 'True') {
              $output_text .= '<br><table width="100%" border="1" cellspacing="0" cellpadding="4"><tr><td>' . $error_text . '</td></tr></table>';
            }
            $this->output[] = array('text' => $output_text); 
          }

          //set Order Filter Status
          $order_status = MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_FILTER_STATUS;
          // send admin email notification
          if (MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEFAULT_EMAIL != 'noreply@creforge.com') {
            $email_link = HTTP_SERVER . DIR_WS_HTTP_CATALOG . 'admin/orders.php?oID=' . $order_id . '&action=edit';
            $email_subject = str_replace('##order_id##', $order_id, MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_EMAIL_SUBJECT);
            $email_message = str_replace('##order_id##', $order_id, MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_EMAIL_MESSAGE);
            $email_message .= "\n\n" . $email_link;
            tep_mail(STORE_OWNER, MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEFAULT_EMAIL, $email_subject, $email_message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 
          }
          // update orders status history
          $order_status_name =  tep_db_fetch_array(tep_db_query("select orders_status_name as name from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . (int)$order_status . "' and language_id = '" . (int)$languages_id . "'"));
          $sql_data_array = array('orders_status' => $order_status);
          tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id=' . $order_id);
          $customer_notification = '0';
          $order_comments = TEXT_OSTATUSPRO_EMAIL_1 . $order_status_name['name'] . "\n" . TEXT_OSTATUSPRO_EMAIL_2 . '  ' . $error_text;
          $sql_data_array = array('orders_id' => $order_id,
                                  'orders_status_id' => $order_status,
                                  'date_added' => 'now()',
                                  'customer_notified' => $customer_notification,
                                  'comments' => $order_comments);
          tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        }
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      include_once(DIR_WS_INCLUDES . 'version.php');
      if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
        return array('MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_AMOUNT', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_WEIGHT_AMOUNT',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_TIME',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_QTY',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ADDRESS_MISMATCH',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_HOLD_LIST',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_FILTER_STATUS', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_PAGE', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_DISPLAY_TITLE', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_TABLE_BORDER',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_GROUPS', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEFAULT_EMAIL',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_EMAIL_SUBJECT',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_EMAIL_MESSAGE',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEBUG',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_SORT_ORDER'
                    );
    } else {
        return array('MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_AMOUNT', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_WEIGHT_AMOUNT',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_TIME',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_QTY',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ADDRESS_MISMATCH',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_HOLD_LIST',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_FILTER_STATUS', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_PAGE', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_DISPLAY_TITLE', 
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_TABLE_BORDER',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEFAULT_EMAIL',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_EMAIL_SUBJECT',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_EMAIL_MESSAGE',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEBUG',
                     'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_SORT_ORDER'
                    );
      }
    }

    function install() {
      include_once(DIR_WS_INCLUDES . 'version.php');
      if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) { 
        $groups_str = "";
        $groups_query_raw = "select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id";
        $groups_query = tep_db_query($groups_query_raw);
        while ($groups = tep_db_fetch_array($groups_query)) {
          $groups_str .= "''" . $groups['customers_group_name'] . "'',";
        }
        $groups_str = substr($groups_str,0,-1);
      }
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable CRE Order Status Monitor Pro CS Module', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_STATUS', 'True', 'Do you want to enable CRE Order Status Monitor Pro Module?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order Total Filter', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_AMOUNT', '0.00', 'If the Order Total is greater than or equal tothis amount, set to Order Filter Status below.<br>An amount of 0.00 disables this filter.', '6', '0', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order Weight Filter', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_WEIGHT_AMOUNT', '0.00', 'If the total Order Weight is greater than or equal to this amount, set to Order Filter Status below.<br>An amount of 0.00 disables this filter.', '6', '0', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order Time Filter', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_TIME', '0', 'If the Order Time (in seconds) between orders is less than this amount, set to Order Filter Status below.<br>(1 hour = 3600 seconds)<br>An amount of 0 disables this filter.', '6', '0', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Order Quantity Filter', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_QTY', '0', 'If the total number of items in the order is greater than or equal to this amount, set to Order Filter Status below.<br>An amount of 0 disables this filter.', '6', '0', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Address Mismatch Filter', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ADDRESS_MISMATCH', 'False', 'If Billing Address does not match Shipping Address, set Order Filter Status below.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Orders Hold List', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_HOLD_LIST', 'Off', 'Use Hold List = If customer exists in Hold List database, set to Order Filter Status below.<br><br>Auto Add = If selected, customer is also automatically added to the Hold List if any filter is triggered.<br><br>Off = Disables this filter.', '6', '0', 'tep_cfg_select_option(array(\'Off\', \'Use Hold List\', \'Use Hold List with Auto Add\'), ', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Order Filter Status', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_ORDER_FILTER_STATUS', '0', 'Set the status of the order to this value if any filter is triggered.', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Message Page', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_PAGE', '0', 'Select the Page that you want to display when Order Filter Status is triggered.<br><br>NOTE: The page used as a message should be marked in-active so that it is not also found when browsing the catalog.<br>', '6', '0', 'tep_cfg_pull_down_ospro_pages(', 'tep_get_ospro_pages_name', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Page Title?', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_MESSAGE_DISPLAY_TITLE', 'False', 'Also display the page title on the message page?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Table Border?', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_TABLE_BORDER', 'False', 'Display output within a table border?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
        tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Groups Affected', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_GROUPS', 'Retail', 'Select the Customer Groups affected by this module.<br><br>NOTE: Customer Groups not selected will be bypassed by this module.', '6', '0',  'tep_cfg_select_multioption(array($groups_str),', now())");
      }
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store Admin Email Notification', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEFAULT_EMAIL', 'noreply@creforge.com', 'Enter the Store Admin email for Order Status notification.', '6', '0', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store Admin Email Subject', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_EMAIL_SUBJECT', ' Order Requires Verification', 'Enter the Store Admin Email Subject', '6', '0', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Store Admin Email Message', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_EMAIL_MESSAGE', 'Order ##order_id## requires your manual approval. Please log in to the admin to view this order and approve it.', 'Enter the Email Message', '6', '0', 'tep_cfg_textarea(', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Debug Mode', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_DEBUG', 'False', 'Display debug text on checkout success page?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CHECKOUT_SUCCESS_OSTATUSPRO_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");

     //create the Hold List table
     tep_db_query("CREATE TABLE IF NOT EXISTS `orders_hold_list` (
                                              `holdlist_id` int(11) NOT NULL auto_increment,
                                              `holdlist_email` varchar(96) NOT NULL default '',
                                              `date_added` datetime default NULL,
                                              `last_modified` datetime default NULL,
                   KEY `holdlist_id` (`holdlist_id`)) TYPE=MyISAM AUTO_INCREMENT=1;");

    tep_db_query('DELETE FROM `configuration` WHERE `configuration_group_id` = "482" AND `configuration_key` = "TABLE_ORDERS_HOLD_LIST"');
    tep_db_query('INSERT IGNORE INTO `configuration` VALUES ("", "", "TABLE_ORDERS_HOLD_LIST", "orders_hold_list", "", 482, 1, now(), now(), NULL, NULL)');
    tep_db_query('INSERT IGNORE INTO `configuration` VALUES ("", "", "FILENAME_ORDERS_HOLD_LIST", "orders_hold_list.php", "", 482, 1, now(), now(), NULL, NULL)');
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
      tep_db_query('DELETE FROM `configuration` WHERE `configuration_group_id` = "482" AND `configuration_key` = "TABLE_ORDERS_HOLD_LIST"');
    }
  }

  function tep_cfg_pull_down_ospro_pages($pages_id, $key = '') {
      global $languages_id;

      $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

      $pages_array = array(array('id' => '0', 'text' => TEXT_OSTATUSPRO_DEFAULT));
      $pages_query = tep_db_query("select pages_id, pages_title from " . TABLE_PAGES_DESCRIPTION . " where language_id = '" . (int)$languages_id . "' order by pages_title");
      while ($pages = tep_db_fetch_array($pages_query)) {
        $pages_array[] = array('id' => $pages['pages_id'],
                                        'text' => $pages['pages_title']);
      }
      return tep_draw_pull_down_menu($name, $pages_array, $pages_id);
    }

    function tep_get_ospro_pages_name($pages_id, $language_id = '') {
      global $languages_id;

      if ($pages_id < 1) return TEXT_OSTATUSPRO_DEFAULT;

      if (!is_numeric($language_id)) $language_id = $languages_id;

      $page_query = tep_db_query("select pages_title from " . TABLE_PAGES_DESCRIPTION . " where pages_id = '" . (int)$pages_id . "' and language_id = '" . (int)$language_id . "'");
      $page = tep_db_fetch_array($page_query);

      return $page['pages_title'];
    } 
?>