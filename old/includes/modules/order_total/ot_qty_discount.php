<?php
/*
  $Id: ot_qty_discount.php,v 1.41 2004-09-14 dreamscape Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 Josh Dechant
  Protions Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class ot_qty_discount {
    var $title, $output,$credit_class;

    function ot_qty_discount() {
      $this->code = 'ot_qty_discount';
      $this->title = (defined('MODULE_QTY_DISCOUNT_TITLE')) ? MODULE_QTY_DISCOUNT_TITLE : '';
      $this->description = (defined('MODULE_QTY_DISCOUNT_DESCRIPTION')) ? MODULE_QTY_DISCOUNT_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_QTY_DISCOUNT_STATUS') && MODULE_QTY_DISCOUNT_STATUS == 'true') ? true : false;
      $this->sort_order = (defined('MODULE_QTY_DISCOUNT_SORT_ORDER')) ? (int)MODULE_QTY_DISCOUNT_SORT_ORDER : 50;
      $this->include_shipping = (defined('MODULE_QTY_DISCOUNT_INC_SHIPPING')) ? MODULE_QTY_DISCOUNT_INC_SHIPPING : false;
      $this->include_tax = (defined('MODULE_QTY_DISCOUNT_INC_TAX')) ? MODULE_QTY_DISCOUNT_INC_TAX : false; 
      $this->calculate_tax = (defined('MODULE_QTY_DISCOUNT_CALC_TAX')) ? MODULE_QTY_DISCOUNT_CALC_TAX : false;
      $this->output = array();
    }

    function process() {
      global $order, $currencies, $ot_subtotal, $cart;
      $od_amount = $this->calculate_discount($this->get_order_total());
      if ($this->calculate_tax == true) $tod_amount = $this->calculate_tax_effect($od_amount);
      if ($od_amount > 0) {
        if (MODULE_QTY_DISCOUNT_RATE_TYPE == 'percentage' && is_object($cart)) $title_ext = sprintf(MODULE_QTY_DISCOUNT_PERCENTAGE_TEXT_EXTENSION ,$this->calculate_rate($cart->count_contents()));
        $this->deduction = $od_amount+$tod_amount;
        $this->output[] = array('title' => sprintf(MODULE_QTY_DISCOUNT_FORMATED_TITLE, $title_ext),
                                'text' => sprintf(MODULE_QTY_DISCOUNT_FORMATED_TEXT, $currencies->format($od_amount)),
                                'value' => $od_amount);
        $order->info['total'] -= $this->deduction;
        $order->info['tax'] -= $tod_amount;
        if ($this->sort_order < $ot_subtotal->sort_order) $order->info['subtotal'] -= $this->deduction;
      }
    }

    function calculate_discount($amount) {
      global $cart;
      $od_amount = 0;
      if ((MODULE_QTY_DISCOUNT_DISABLE_WITH_COUPON == 'true') && (isset($_SESSION['cc_id']) && tep_not_null($_SESSION['cc_id']) )) return $od_amount;
      if (is_object($cart)) {
        $qty_discount = $this->calculate_rate($cart->count_contents());
      } else {
        $qty_discount = 0;
      }
      if ($qty_discount > 0) {
        if (MODULE_QTY_DISCOUNT_RATE_TYPE == 'percentage') {
          $od_amount = tep_round((($amount*10)/10)*($qty_discount/100), 2);
        } else {
          $od_amount = tep_round((($qty_discount*10)/10), 2);
        }
      }
      return $od_amount;
    }

    function calculate_rate($order_qty) {
      $discount_rate = split("[:,]" , MODULE_QTY_DISCOUNT_RATES);
      $size = sizeof($discount_rate);
      for ($i=0, $n=$size; $i<$n; $i+=2) {
        if ($order_qty >= $discount_rate[$i]) {
          $qty_discount = $discount_rate[$i+1];
        }
      }
      return $qty_discount;
    }

    function calculate_tax_effect($od_amount) {
      global $order;
      if (MODULE_QTY_DISCOUNT_RATE_TYPE == 'percentage') {
        $tod_amount = 0;
        reset($order->info['tax_groups']);
        while (list($key, $value) = each($order->info['tax_groups'])) {
          $god_amount = 0;
          $tax_rate = tep_get_tax_rate_from_desc($key);
          $net = ($tax_rate * $order->info['tax_groups'][$key]);
          if ($net > 0) {
            $god_amount = $this->calculate_discount($order->info['tax_groups'][$key]);
            $tod_amount += $god_amount;
            $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
          }
        }
      } else {
        $tod_amount = 0;
        reset($order->info['tax_groups']);
        while (list($key, $value) = each($order->info['tax_groups'])) {
          $god_amount = 0;
          $tax_rate = tep_get_tax_rate_from_desc($key);
          $net = ($tax_rate * $order->info['tax_groups'][$key]);
          if ($net>0) {
            $god_amount = ($tax_rate/100)*$od_amount;
            $tod_amount += $god_amount;
            $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
          }
        }
      }
      return $tod_amount;
    }

    function get_order_total() {
      global $order;
      $order_total = $order->info['total'];
      if ($this->include_tax == false) $order_total = ($order_total - $order->info['tax']);
      if ($this->include_shipping == false) $order_total = ($order_total - $order->info['shipping_cost']);
      return $order_total;
    }

    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_QTY_DISCOUNT_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }
      return $this->check;
    }

    function keys() {
      return array('MODULE_QTY_DISCOUNT_STATUS', 'MODULE_QTY_DISCOUNT_SORT_ORDER', 'MODULE_QTY_DISCOUNT_DISABLE_WITH_COUPON', 'MODULE_QTY_DISCOUNT_RATE_TYPE', 'MODULE_QTY_DISCOUNT_RATES', 'MODULE_QTY_DISCOUNT_INC_SHIPPING', 'MODULE_QTY_DISCOUNT_INC_TAX', 'MODULE_QTY_DISCOUNT_CALC_TAX');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Quantity Discount', 'MODULE_QTY_DISCOUNT_STATUS', 'true', 'Do you want to enable the quantity discount module?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_QTY_DISCOUNT_SORT_ORDER', '50', 'Sort order of display.', '50', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Disable If Coupon Used', 'MODULE_QTY_DISCOUNT_DISABLE_WITH_COUPON', 'true', 'Do you want to disable the quantity discount module if a discount coupon is being used by the user?', '6', '3','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Discount Rate Type', 'MODULE_QTY_DISCOUNT_RATE_TYPE', 'percentage', 'Choose the type of discount rate - percentage or flat rate', '6', '4','tep_cfg_select_option(array(\'percentage\', \'flat rate\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Discount Rates', 'MODULE_QTY_DISCOUNT_RATES', '10:5,20:10', 'The discount is based on the total number of items.  Example: 10:5,20:10.. 10 or more items get a 5% or $5 discount; 20 or more items receive a 10% or $10 discount; depending on the rate type.', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Shipping', 'MODULE_QTY_DISCOUNT_INC_SHIPPING', 'false', 'Include Shipping in calculation', '6', '6', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_QTY_DISCOUNT_INC_TAX', 'false', 'Include Tax in calculation.', '6', '7','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Calculate Tax', 'MODULE_QTY_DISCOUNT_CALC_TAX', 'true', 'Re-calculate Tax on discounted amount.', '6', '8','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
  // Get tax rate from tax description
  if (!function_exists('tep_get_tax_rate_from_desc')) {
    function tep_get_tax_rate_from_desc($tax_desc) {
      $tax_query = tep_db_query("select tax_rate from " . TABLE_TAX_RATES . " where tax_description = '" . $tax_desc . "'");
      $tax = mysql_fetch_assoc($tax_query);
      return $tax['tax_rate'];
    }
  }
?>