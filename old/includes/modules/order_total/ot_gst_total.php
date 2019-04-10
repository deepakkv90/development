<?php
/*
  $Id: ot_gst_total.php,v 1.1.1.1 2004/03/04 23:41:16 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_gst_total {
    var $title, $output,$credit_class;

    function ot_gst_total() {
      $this->code = 'ot_gst_total';
      $this->title = (defined('MODULE_ORDER_GST_TOTAL_TITLE')) ? MODULE_ORDER_GST_TOTAL_TITLE : '';
      $this->description = (defined('MODULE_ORDER_GST_TOTAL_DESCRIPTION')) ? MODULE_ORDER_GST_TOTAL_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_ORDER_GST_TOTAL_STATUS') && MODULE_ORDER_GST_TOTAL_STATUS == 'true') ? true : false;
      $this->sort_order = (defined('MODULE_ORDER_GST_TOTAL_SORT_ORDER')) ? (int)MODULE_ORDER_GST_TOTAL_SORT_ORDER : 100;
      $this->output = array();
    }

    function process() {
      global $order, $currencies;

      reset($order->info['tax_groups']);
	  $gst_total = $order->info['gst_tax'];
      
	  while (list($key, $value) = each($order->info['tax_groups'])) {
	    if ($value > 0) {
		  $gst_tax_value = (($_SESSION['shipping']['cost'] / 100 ) * $order->info['tax_class_id']);
		  $gst_total 	 += $gst_tax_value; 
	    }
      }
        
      $order->info['gst_total'] = tep_round(($gst_total - $order->info["discount_tax_amount"]),2); //June 2012
       
	   //print_r($order);
	   
      $this->output[] = array('title' => $this->title . ':',
                              'text' => $currencies->format($order->info['gst_total'], true, $order->info['currency'], $order->info['currency_value']),
                              'value' => $order->info['gst_total']);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_GST_TOTAL_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_GST_TOTAL_STATUS', 'MODULE_ORDER_GST_TOTAL_SORT_ORDER');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display GST Total', 'MODULE_ORDER_GST_TOTAL_STATUS', 'true', 'Do you want to display the gst total value?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_GST_TOTAL_SORT_ORDER', '100', 'Sort order of display.', '6', '2', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>