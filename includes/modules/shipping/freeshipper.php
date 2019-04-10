<?php
/*
  WebMakers.com Added: Free Payments and Shipping
  Written by Linda McGrath osCOMMERCE@WebMakers.com
  http://www.thewebmakerscorner.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class freeshipper {
    var $code, $title, $description, $icon, $enabled, $tax_class, $zone_id, $cost;

// BOF: WebMakers.com Added: Free Payments and Shipping
// class constructor
    function freeshipper() {
      global $order, $cart;
      $this->code = 'freeshipper';
      $this->title = MODULE_SHIPPING_FREESHIPPER_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_FREESHIPPER_TEXT_DESCRIPTION;
      if (defined('MODULE_SHIPPING_FREESHIPPER_SORT_ORDER')) {
        $this->sort_order = (int)MODULE_SHIPPING_FREESHIPPER_SORT_ORDER;
      } else {
        $this->sort_order = '';
      }

      $this->icon = DIR_WS_ICONS . 'shipping_free_shipper.jpg';
      if (defined('MODULE_SHIPPING_FREESHIPPER_TAX_CLASS')) {
        $this->tax_class = $this->__get_value(MODULE_SHIPPING_FREESHIPPER_TAX_CLASS);
      } else {
        $this->tax_class = 0;
      }
      
      if (defined('MODULE_SHIPPING_FREESHIPPER_ZONE')) {
        $this->zone_id = $this->__get_value(MODULE_SHIPPING_FREESHIPPER_ZONE);
      } else {
        $this->zone_id = 0;
      }
      
      if (defined('MODULE_SHIPPING_FREESHIPPER_COST')) {
        $this->cost = $this->__get_value(MODULE_SHIPPING_FREESHIPPER_COST);
      } else {
        $this->cost = 0;
      }
      
      if (defined('MODULE_SHIPPING_FREESHIPPER_STATUS')) {
        $this->enabled = ((MODULE_SHIPPING_FREESHIPPER_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }


// Only show if weight is 0
//      if ( (!strstr($PHP_SELF,'modules.php')) || $cart->show_weight()==0) {
        if (defined('MODULE_SHIPPING_FREESHIPPER_STATUS')) {
          $this->enabled = MODULE_SHIPPING_FREESHIPPER_STATUS;
        } else {
          $this->enabled = false;
        }
        if ( ($this->enabled == true) && ($this->zone_id > 0) ) {
          $check_flag = false;
          $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . $this->zone_id . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
          while ($check = tep_db_fetch_array($check_query)) {
            if ($check['zone_id'] < 1) {
              $check_flag = true;
              break;
            } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
              $check_flag = true;
              break;
            }
          }

          if ($check_flag == false) {
            $this->enabled = false;
          }
        }
//      }
// EOF: WebMakers.com Added: Free Payments and Shipping
    }

// class methods
    function quote($method = '') {
      global $order;

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_FREESHIPPER_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => '<FONT COLOR=FF0000><B>' . MODULE_SHIPPING_FREESHIPPER_TEXT_WAY . '</B></FONT>',
                                                     'cost' => SHIPPING_HANDLING + $this->cost)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_FREESHIPPER_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) values ('Enable Free Shipping', 'MODULE_SHIPPING_FREESHIPPER_STATUS', 'True', 'Do you want to offer Free shipping?', '6', '10', now(), 'tep_cfg_select_option(array(\'True\', \'False\'), ')");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) values ('Free shipping handling cost', 'MODULE_SHIPPING_FREESHIPPER_COST', '0-0.00,1-0.00', 'What is the cost of handling fee?', '6', '20', now(), 'tep_freeshipping_show_group_values', 'tep_freeshipping_get_group_values(')");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) values ('Free Shipping for Over', 'MODULE_SHIPPING_FREESHIPPER_OVER', '0-0.00,1-0.00', 'Min order amount for free shipping(Keep zero for no min order amout required)', '6', '30', now(), 'tep_freeshipping_show_group_values', 'tep_freeshipping_get_group_values(')");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('FREESHIPPER Tax Class', 'MODULE_SHIPPING_FREESHIPPER_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '40', 'tep_get_tax_class_title_group', 'tep_cfg_pull_down_tax_classes_group(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('FREESHIPPER Shipping Zone', 'MODULE_SHIPPING_FREESHIPPER_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '50', 'tep_get_zone_class_title_group', 'tep_cfg_pull_down_zone_classes_group(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('FREESHIPPER Sort Order', 'MODULE_SHIPPING_FREESHIPPER_SORT_ORDER', '0', 'Sort order of display.', '6', '60', now())");
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

    function keys() {
      return array('MODULE_SHIPPING_FREESHIPPER_STATUS', 'MODULE_SHIPPING_FREESHIPPER_COST', 'MODULE_SHIPPING_FREESHIPPER_OVER', 'MODULE_SHIPPING_FREESHIPPER_TAX_CLASS', 'MODULE_SHIPPING_FREESHIPPER_ZONE', 'MODULE_SHIPPING_FREESHIPPER_SORT_ORDER');
    }
    
    function __get_value($data) {
      $ret = '';
      if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
        $data_array = explode(',', $data);
        foreach ($data_array as $value) {
          $tmp = explode('-', $value);
          if ($tmp[0] == $_SESSION['sppc_customer_group_id']) {
            $ret = (int)$tmp[1];
            break;
          }
        }
      } else {
        $ret = (int)$data;
      }
      return $ret;
    }
  }
  
  function tep_freeshipping_get_group_values($current_value, $current_key = '') {
    $ret = '';
    if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
      $current_value_array = explode(',', $current_value);
      $values_array = array();
      foreach ($current_value_array as $value) {
        $tmp = explode('-', $value);
        $values_array[$tmp[0]] = $tmp[1];
      }
      $groups_query = tep_db_query("SELECT customers_group_id, customers_group_name FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1'");
      while ($groups = tep_db_fetch_array($groups_query)) {
        $current = isset($values_array[$groups['customers_group_id']]) ? $values_array[$groups['customers_group_id']] : '';
        $ret .= tep_draw_input_field('configuration[' . $current_key . '][' . $groups['customers_group_id'] . ']', $current, 'size="8"') . '&nbsp;' . $groups['customers_group_name'] . '<br>';
      }
      $ret = substr($ret, 0, strlen($ret) - 6);
    } else {
      $ret = tep_draw_input_field('configuration[' . $current_key . ']', $current_value);
    }
    return $ret;
  }
  
  function tep_freeshipping_show_group_values($current_value) {
    $ret = '';
    if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
      $current_value_array = explode(',', $current_value);
      $values_array = array();
      foreach ($current_value_array as $value) {
        $tmp = explode('-', $value);
        $values_array[$tmp[0]] = $tmp[1];
      }
      $groups_query = tep_db_query("SELECT customers_group_id, customers_group_name FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1'");
      while ($groups = tep_db_fetch_array($groups_query)) {
        $current = isset($values_array[$groups['customers_group_id']]) ? $values_array[$groups['customers_group_id']] : '';
        $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $groups['customers_group_name'] . ': ' . $current . '<br>';
      }
      $ret = substr($ret, 0, strlen($ret) - 6);
    } else {
      $ret = $current_value;
    }
    return $ret;
  }
  
  function tep_cfg_pull_down_tax_classes_group($tax_class_id, $key = '') {
    $ret = '';
    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }
    if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
      $current_value_array = explode(',', $tax_class_id);
      $values_array = array();
      foreach ($current_value_array as $value) {
        $tmp = explode('-', $value);
        $values_array[$tmp[0]] = $tmp[1];
      }
      $groups_query = tep_db_query("SELECT customers_group_id, customers_group_name FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1'");
      while ($groups = tep_db_fetch_array($groups_query)) {
        $name = 'configuration[' . $key . '][' . $groups['customers_group_id'] . ']';
        $current_value = isset($values_array[$groups['customers_group_id']]) ? $values_array[$groups['customers_group_id']] : '';
        $ret .= tep_draw_pull_down_menu($name, $tax_class_array, $current_value) . '&nbsp;' . $groups['customers_group_name'] . '<br>';
      }
      $ret = substr($ret, 0, strlen($ret) - 6);
    } else {
      $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
      $ret = tep_draw_pull_down_menu($name, $tax_class_array, $tax_class_id);
    }
    return $ret;
  }
  
  function tep_get_tax_class_title_group($current_value) {
    $ret = '';
    if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
      $current_value_array = explode(',', $current_value);
      $values_array = array();
      foreach ($current_value_array as $value) {
        $tmp = explode('-', $value);
        $values_array[$tmp[0]] = $tmp[1];
      }
      $groups_query = tep_db_query("SELECT customers_group_id, customers_group_name FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1'");
      while ($groups = tep_db_fetch_array($groups_query)) {
        $current = isset($values_array[$groups['customers_group_id']]) ? $values_array[$groups['customers_group_id']] : '';
        $classes_query = tep_db_query("select tax_class_title from " . TABLE_TAX_CLASS . " where tax_class_id = '" . (int)$current . "'");
        $classes = tep_db_fetch_array($classes_query);
        $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $groups['customers_group_name'] . ': ' . $classes['tax_class_title'] . '<br>';
      }
      $ret = substr($ret, 0, strlen($ret) - 6);
    } else {
      if ($current_value == '0') {
        $ret =  TEXT_NONE;
      } else {
        $classes_query = tep_db_query("select tax_class_title from " . TABLE_TAX_CLASS . " where tax_class_id = '" . (int)$current_value . "'");
        $classes = tep_db_fetch_array($classes_query);
        $ret =  $classes['tax_class_title'];
      }
    }
    return $ret;
  }
  
  function tep_cfg_pull_down_zone_classes_group($zone_class_id, $key = '') {
    $ret = '';
    $zone_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $zone_class_query = tep_db_query("select geo_zone_id, geo_zone_name from " . TABLE_GEO_ZONES . " order by geo_zone_name");
    while ($zone_class = tep_db_fetch_array($zone_class_query)) {
      $zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
                                  'text' => $zone_class['geo_zone_name']);
    }
    if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
      $current_value_array = explode(',', $zone_class_id);
      $values_array = array();
      foreach ($current_value_array as $value) {
        $tmp = explode('-', $value);
        $values_array[$tmp[0]] = $tmp[1];
      }
      $groups_query = tep_db_query("SELECT customers_group_id, customers_group_name FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1'");
      while ($groups = tep_db_fetch_array($groups_query)) {
        $name = 'configuration[' . $key . '][' . $groups['customers_group_id'] . ']';
        $current_value = isset($values_array[$groups['customers_group_id']]) ? $values_array[$groups['customers_group_id']] : '';
        $ret .= tep_draw_pull_down_menu($name, $zone_class_array, $current_value) . '&nbsp;' . $groups['customers_group_name'] . '<br>';
      }
      $ret = substr($ret, 0, strlen($ret) - 6);
    } else {
      $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');
      $ret = tep_draw_pull_down_menu($name, $zone_class_array, $zone_class_id);
    }
    return $ret;
  }
  
  function tep_get_zone_class_title_group($current_value) {
    if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
      $current_value_array = explode(',', $current_value);
      $values_array = array();
      foreach ($current_value_array as $value) {
        $tmp = explode('-', $value);
        $values_array[$tmp[0]] = $tmp[1];
      }
      $groups_query = tep_db_query("SELECT customers_group_id, customers_group_name FROM " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1'");
      while ($groups = tep_db_fetch_array($groups_query)) {
        $current = isset($values_array[$groups['customers_group_id']]) ? $values_array[$groups['customers_group_id']] : '';
        $classes_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$current . "'");
        $classes = tep_db_fetch_array($classes_query);
        $ret .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $groups['customers_group_name'] . ': ' . $classes['geo_zone_name'] . '<br>';
      }
      $ret = substr($ret, 0, strlen($ret) - 6);
    } else {
      if ($current_value == '0') {
        $ret = TEXT_NONE;
      } else {
        $classes_query = tep_db_query("select geo_zone_name from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . (int)$current_value . "'");
        $classes = tep_db_fetch_array($classes_query);
        $ret = $classes['geo_zone_name'];
      }
    }
    return $ret;
  }
?>