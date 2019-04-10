<?php
/*
<<<<<<< ot_lev_discount.php
  $Id: ot_lev_discount.php,v 1.0 2002/04/08 01:13:43 hpdl Exp $
=======
  $Id: ot_lev_discount.php,v 1.3 2002/09/04 22:49:11 wilt Exp $
>>>>>>> 1.3

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class ot_lev_discount {
    var $title, $output,$credit_class;

    function ot_lev_discount() {
      $this->code = 'ot_lev_discount';
      $this->title = (defined('MODULE_LEV_DISCOUNT_TITLE')) ? MODULE_LEV_DISCOUNT_TITLE : '';
      $this->description = (defined('MODULE_LEV_DISCOUNT_DESCRIPTION')) ? MODULE_LEV_DISCOUNT_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_LEV_DISCOUNT_STATUS') && MODULE_LEV_DISCOUNT_STATUS == 'true') ? true : false;
      $this->sort_order = (defined('MODULE_LEV_DISCOUNT_SORT_ORDER')) ? (int)MODULE_LEV_DISCOUNT_SORT_ORDER : 70;
      $this->include_shipping = (defined('MODULE_LEV_DISCOUNT_INC_SHIPPING')) ? MODULE_LEV_DISCOUNT_INC_SHIPPING : false;
      $this->include_tax = (defined('MODULE_LEV_DISCOUNT_INC_TAX')) ? MODULE_LEV_DISCOUNT_INC_TAX : false;
      $this->calculate_tax = (defined('MODULE_LEV_DISCOUNT_CALC_TAX')) ? MODULE_LEV_DISCOUNT_CALC_TAX : false; 
      $this->table = (defined('MODULE_LEV_DISCOUNT_TABLE')) ? MODULE_LEV_DISCOUNT_TABLE : '';
      $this->output = array();
    }

    function process() {
      global $order, $ot_subtotal, $currencies;
      //$od_amount = $this->calculate_credit($this->get_order_total());
     //Modified Aug 30, 2010
	 $od_amount = $this->price_break_calculation($this->get_order_total());	 	
     $shown_price  = $order->info['subtotal'] - $od_amount;	
	 $order->info['gst_tax'] =  $shown_price / $order->info['tax_class_id'];  
	 $order->info['total'] = $order->info['subtotal'] + $order->info['gst_tax']+ $order->info['shipping_cost'];

      if ($od_amount>0) {
      $this->deduction = $od_amount;
      $this->output[] = array('title' => $this->title . ':',
                              'text' => '<b>-' . $currencies->format($od_amount) . '</b>',
                              'value' => $od_amount);
    	$order->info['total'] = $order->info['total'] - $od_amount;
	    if ($this->sort_order < $ot_subtotal->sort_order) {
	      $order->info['subtotal'] = $order->info['subtotal'] - $od_amount;
	    }
       }
    }

  function calculate_credit($amount) {
    global $order;
    $od_amount=0;
    $table_cost = split("[:,]" , MODULE_LEV_DISCOUNT_TABLE);
    for ($i = 0; $i < count($table_cost); $i+=2) {
          if ($amount >= $table_cost[$i]) {
            $od_pc = $table_cost[$i+1];
          }
        }
// Calculate tax reduction if necessary
    if($this->calculate_tax == true) {
// Calculate main tax reduction
      $tod_amount = tep_round($order->info['tax']*10,2)/10*$od_pc/100;
      $order->info['tax'] = $order->info['tax'] - $tod_amount;
// Calculate tax group deductions
      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups'])) {
        $god_amount = tep_round($value*10,2)/10*$od_pc/100;
        $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
      }
    }
    if (!isset($tod_amount)) {
      $tod_amount = 0;
    }
    $od_amount = tep_round($amount*10,2)/10*$od_pc/100;
    $od_amount = $od_amount + $tod_amount;
    return $od_amount;
  }


 function price_break_calculation($amount) {
	global $order;
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
		$shown_price =($order->products[$i]['product_original_final_price'] - $order->products[$i]['final_price']) * $order->products[$i]['qty'];
		$this->info['price_break_subtotal'] += $shown_price;
		$index++;
	}
	$od_amount = $this->info['price_break_subtotal'];
    	return $od_amount;
  }

  function get_order_total() {
    global  $order, $cart;
    $order_total = $order->info['total'];
// Check if gift voucher is in cart and adjust total
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $t_prid = tep_get_prid($products[$i]['id']);
      $gv_query = tep_db_query("select products_price, products_tax_class_id, products_model from " . TABLE_PRODUCTS . " where products_id = '" . $t_prid . "'");
      $gv_result = tep_db_fetch_array($gv_query);
      if (ereg('^GIFT', addslashes($gv_result['products_model']))) {echo "if FN calls ";
        $qty = $cart->get_quantity($t_prid);
        $products_tax = tep_get_tax_rate($gv_result['products_tax_class_id']);
        if ($this->include_tax == false) {
           $gv_amount = $gv_result['products_price'] * $qty;
        } else {
          $gv_amount = ($gv_result['products_price'] + tep_calculate_tax($gv_result['products_price'],$products_tax)) * $qty;
        }
        $order_total=$order_total - $gv_amount;
      }
    }
    if ($this->include_tax == false) $order_total=$order_total-$order->info['tax'];
    if ($this->include_shipping == false) $order_total=$order_total-$order->info['shipping_cost'];
    return $order_total;
  }



    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_LEV_DISCOUNT_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_LEV_DISCOUNT_STATUS', 'MODULE_LEV_DISCOUNT_SORT_ORDER','MODULE_LEV_DISCOUNT_TABLE', 'MODULE_LEV_DISCOUNT_INC_SHIPPING', 'MODULE_LEV_DISCOUNT_INC_TAX','MODULE_LEV_DISCOUNT_CALC_TAX');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Total', 'MODULE_LEV_DISCOUNT_STATUS', 'true', 'Do you want to enable the Order Discount?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_LEV_DISCOUNT_SORT_ORDER', '70', 'Sort order of display.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Shipping', 'MODULE_LEV_DISCOUNT_INC_SHIPPING', 'true', 'Include Shipping in calculation', '6', '3', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_LEV_DISCOUNT_INC_TAX', 'true', 'Include Tax in calculation.', '6', '4','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Calculate Tax', 'MODULE_LEV_DISCOUNT_CALC_TAX', 'false', 'Re-calculate Tax on discounted amount.', '6', '5','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Discount Percentage', 'MODULE_LEV_DISCOUNT_TABLE', '100:7.5,250:10,500:12.5,1000:15', 'Set the price breaks and discount percentages', '6', '6', now())");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }
  }
?>