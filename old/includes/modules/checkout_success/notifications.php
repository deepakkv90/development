<?php
/*
  $Id: notifcations.php,v 1.1.1.1 2006/07/04 23:41:17 datazen Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class notifications {
    var $code, $title, $description, $enabled, $sort_order, $output;

    function notifications() {
      $this->code = 'notifications';
      $this->title = MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_TITLE;
      $this->description = MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_DESCRIPTION;
      if (defined('MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_STATUS')) {
        $this->enabled = ((MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      if (defined('MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_SORT_ORDER')) {
        $this->sort_order = (int)MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_SORT_ORDER;
      } else {
        $this->sort_order = '';
      }

      $this->output = array();
    }

    function process() {
      global $customer_id;

      if (MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_STATUS != 'True') { return; }

      $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
      $global = tep_db_fetch_array($global_query);

      if ($global['global_product_notifications'] != '1') {
        $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
        $orders = tep_db_fetch_array($orders_query);

        $products_array = array();
        $products_query = tep_db_query("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
        while ($products = tep_db_fetch_array($products_query)) {
          $products_array[] = array('id' => $products['products_id'],
                                              'text' => $products['products_name']);
        }
      }

      if ($global['global_product_notifications'] != '1') {
        $notify_products = TEXT_NOTIFY_PRODUCTS . '<br><p class="productsNotifications">';
        $products_displayed = array();
        for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
          if (!in_array($products_array[$i]['id'], $products_displayed)) {
            $notify_products .= tep_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' ' . $products_array[$i]['text'] . '<br>';
            $products_displayed[] = $products_array[$i]['id'];
          }
        }
        $notify_products .= '</p>';
      } else {
        $notify_products = TEXT_SEE_ORDERS . '<br><br>' . TEXT_CONTACT_STORE_OWNER;
      }
      $output_text ='';
      if (MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_TABLE_BORDER == 'True') {
        $output_text .= '<tr><td valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td bgcolor="#99AECE"><table width="100%" border="0" cellspacing="0" cellpadding="1">';
        $output_text .= '<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="1"><tr><td bgcolor="#f8f8f9"><table width="100%" border="0" cellspacing="0" cellpadding="4"><tr><td>';
      }
      $output_text .= '<table border="0" width="100%" cellspacing="4" cellpadding="2"><tr><td valign="top">' . tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', $HEADING_TITLE) .'</td>';
      $output_text .= '<td valign="top" class="main">' . tep_draw_separator('pixel_trans.gif', '1', '10') . '<br>' . $notify_products . '<h3>' .  TEXT_THANKS_FOR_SHOPPING . '</h3></td></tr></table>';
      if (MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_TABLE_BORDER == 'True') {
        $output_text .= '</td></tr></table></td></tr></table></td></tr></table></td></tr></table></td></tr><tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr>';
      }
      $this->output[] = array('text' => $output_text);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_STATUS', 'MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_TABLE_BORDER', 'MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_SORT_ORDER');
    }

  function install() {
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Product Notifications', 'MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_STATUS', 'True', 'Do you want to enable Product Notifications?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Table Border', 'MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_TABLE_BORDER', 'False', 'Display Product Notifications with table border?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CHECKOUT_SUCCESS_NOTIFICATIONS_SORT_ORDER', '0', 'Sort order of display.', '6', '3', now())");
  }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>