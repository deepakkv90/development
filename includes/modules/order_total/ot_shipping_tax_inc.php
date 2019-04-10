<?php
/*
  $Id: ot_tax.php,v 1.1.1.1 2004/03/04 23:41:16 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_shipping_tax_inc {
    var $title, $output,$credit_class;

    function ot_shipping_tax_inc() {
      $this->code = 'ot_shipping_tax_inc';
      $this->title = (defined('MODULE_ORDER_SHIPPING_TAX_TITLE')) ? MODULE_ORDER_SHIPPING_TAX_TITLE : '';
      $this->description = (defined('MODULE_ORDER_SHIPPING_TAX_DESCRIPTION')) ? MODULE_ORDER_SHIPPING_TAX_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_ORDER_SHIPPING_TAX_STATUS') && MODULE_ORDER_SHIPPING_TAX_STATUS == 'true') ? true : false;
      $this->sort_order = (defined('MODULE_ORDER_SHIPPING_TAX_SORT_ORDER')) ? (int)MODULE_ORDER_SHIPPING_TAX_SORT_ORDER : 100;
      $this->output = array();
      if (isset($_SESSION['sppc_customer_group_tax_exempt']) && $_SESSION['sppc_customer_group_tax_exempt'] == '1') {
        $this->enabled = false;
      }
    }

    function process() {
    global $order, $currencies;
      if (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') {
        switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
          case 'national':
            if (isset($order->delivery['country_id']) && $order->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;
          case 'international':
            if (isset($order->delivery['country_id']) && $order->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;
          case 'both':
            $pass = true; break;
          default:
            $pass = false; break;
        }
        if ( (isset($pass) && $pass == true) && ( ($order->info['total'] - $order->info['shipping_cost']) >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
          $order->info['shipping_method'] = $this->title;
          $order->info['total'] -= $order->info['shipping_cost'];
          $order->info['shipping_cost'] = 0;
        }
      }
      if (isset($_SESSION['shipping']['id'])) {
        $module = substr($_SESSION['shipping']['id'], 0, strpos($_SESSION['shipping']['id'], '_'));
      } else {
        $module = '';
      }
      if (tep_not_null($order->info['shipping_method'])) {
        if (isset($GLOBALS[$module]->tax_class) && $GLOBALS[$module]->tax_class > 0) {
          $shipping_tax = tep_get_tax_rate($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          $shipping_tax_description = tep_get_tax_description($GLOBALS[$module]->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
          /*$order->info['tax'] += tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);
          $order->info['tax_groups']["$shipping_tax_description"] += tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);
          $order->info['total'] += tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);*/
          $order->info['shipping_cost'] += tep_calculate_tax($order->info['shipping_cost'], $shipping_tax);
        }
        $this->output[] = array('title' => $this->title . ':',
                                'text' => $currencies->format($order->info['shipping_cost'], true, $order->info['currency'], $order->info['currency_value']),
                                'value' => $order->info['shipping_cost']);
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_SHIPPING_TAX_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
          return array('MODULE_ORDER_SHIPPING_TAX_STATUS', 'MODULE_ORDER_SHIPPING_TAX_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Shipping Tax', 'MODULE_ORDER_SHIPPING_TAX_STATUS', 'true', 'Do you want to display the shipping tax value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_SHIPPING_TAX_SORT_ORDER', '100', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>