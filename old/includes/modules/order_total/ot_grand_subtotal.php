<?php
/*
  $Id: ot_grand_subtotal.php,v 1.1.1.1 2004/03/04 23:41:16 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_grand_subtotal {
    var $title, $output,$credit_class;

    function ot_grand_subtotal() {
      $this->code = 'ot_grand_subtotal';	  
      $this->title = (defined('MODULE_ORDER_GRAND_SUBTOTAL_TITLE')) ? MODULE_ORDER_GRAND_SUBTOTAL_TITLE : '';
      $this->description = (defined('MODULE_ORDER_GRAND_SUBTOTAL_DESCRIPTION')) ? MODULE_ORDER_GRAND_SUBTOTAL_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_ORDER_GRAND_SUBTOTAL_STATUS') && MODULE_ORDER_GRAND_SUBTOTAL_STATUS == 'true') ? true : false;
      $this->sort_order = (defined('MODULE_ORDER_GRAND_SUBTOTAL_SORT_ORDER')) ? (int)MODULE_ORDER_GRAND_SUBTOTAL_SORT_ORDER : 120;
      $this->output = array();      
    }

    function process() {
      global $order, $gsubtot, $currencies, $cart;
	  //print_r($cart->show_shopping_cart_total());		  
	  
      $this->output[] = array('title' => $this->title . ':',
                              'text' => '<b>' . $currencies->format($cart->show_shopping_cart_total(), true, $order->info['currency'], $order->info['currency_value']) . '</b>',
                              'value' => $cart->show_shopping_cart_total());					  
	  
    }
		
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_GRAND_SUBTOTAL_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_GRAND_SUBTOTAL_STATUS', 'MODULE_ORDER_GRAND_SUBTOTAL_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Grand Subtotal', 'MODULE_ORDER_GRAND_SUBTOTAL_STATUS', 'true', 'Do you want to display the grand subtotal order value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_GRAND_SUBTOTAL_SORT_ORDER', '110', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>