<?php
/*
  $Id: indvship.php,v 1.39 2003/02/05 22:41:52 hpdl Exp $
  Modified for MVS 2005/03/13 sjs

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class indvship {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function indvship() {
      global $order, $vendors_id;

//MVS
  //  $this->vendors_id = ($products['vendors_id'] <= 0) ? 1 : $products['vendors_id'];
      $this->code = 'indvship';
      $this->title = MODULE_SHIPPING_INDVSHIP_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_INDVSHIP_TEXT_DESCRIPTION;
      $this->icon = '';
      $this->delivery_country_id = $order->delivery['country']['id'];
      $this->delivery_zone_id = $order->delivery['zone_id'];
    }

//MVS start
    function sort_order($vendors_id='1') 
                   { $sort_order = 'MODULE_SHIPPING_INDVSHIP_SORT_ORDER_' . $vendors_id;
                     if (defined($sort_order)) 
            { $this->sort_order = constant($sort_order);
                } 
           else 
            { $this->sort_order = '-';
                        }
                     return $this->sort_order;
                   }

                function tax_class($vendors_id='1') {
      $this->tax_class = constant('MODULE_SHIPPING_INDVSHIP_TAX_CLASS_' . $vendors_id);
                        return $this->tax_class;
    }

              function enabled($vendors_id='1') {
      $this->enabled = false;
      $status = @constant('MODULE_SHIPPING_INDVSHIP_STATUS_' . $vendors_id);
                        if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_INDVSHIP_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_INDVSHIP_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          }
           elseif ($check['zone_id'] == $this->delivery_zone_id) {
            $check_flag = true;
            break;
            }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }//if
      }//if
      return $this->enabled;
    }

                function zones($vendors_id='1') {
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_INDVSHIP_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_INDVSHIP_ZONE . "' and zone_country_id = '" . $this->delivery_zone_id . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $this->delivery_zone_id) {
            $check_flag = true;
            break;
          } //if
        }//while

        if ($check_flag == false) {
          $this->enabled = false;
        }//if
      }//if
                        return $this->enabled;
    }//function
//MVS End

//Get a quote, added $shiptotal for IndvShip
    function quote($method = '', $module = '', $vendors_id = '1') {
      global $order, $total_count, $shipping_weight, $shipping_num_boxes, $shiptotal;
    

//MVS Start
      $vendors_data_query = tep_db_query("select handling_charge,
                                                 handling_per_box,
                                                 vendor_country,
                                                 vendors_zipcode
                                          from " . TABLE_VENDORS . "
                                          where vendors_id = '" . (int)$vendors_id . "'"
                                        );
      $vendors_data = tep_db_fetch_array($vendors_data_query);
      $country_name = tep_get_countries($vendors_data['vendor_country'], true);


// begin mod for extra handling fee
$vendors_handling_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key = 'MODULE_SHIPPING_INDVSHIP_HANDLING_" . $vendors_id . "'");
$vendors_handling_data = tep_db_fetch_array($vendors_handling_query);

$handling_charge = $vendors_data['handling_charge'] + $vendors_handling_data['configuration_value'];
// end mod for extra handling fee
      $handling_per_box = $vendors_data['handling_per_box'];
      if ($handling_charge > $handling_per_box*$shipping_num_boxes) {
        $handling = $handling_charge;
      } else {
        $handling = $handling_per_box*$shipping_num_boxes;
      }

//MVS End

//MVS - Changed 'cost' => $shiptotal + $handling
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_INDVSHIP_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_INDVSHIP_TEXT_WAY,
                                                     'cost' => $shiptotal + $handling)));
// $this->tax_class = constant(MODULE_SHIPPING_INDVSHIP_TAX_CLASS_ . $vendors_id);
      if ($this->tax_class($vendors_id) > 0) {
           $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
      }
      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check($vendors_id = '1') {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_INDVSHIP_STATUS_" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install($vendors_id = '1') {
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Item Shipping', 'MODULE_SHIPPING_INDVSHIP_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer individual rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Handling Fee', 'MODULE_SHIPPING_INDVSHIP_HANDLING_" . $vendors_id . "', '0', 'Handling fee for this shipping method.', '6', '0', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Tax Class', 'MODULE_SHIPPING_INDVSHIP_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_INDVSHIP_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort Order', 'MODULE_SHIPPING_INDVSHIP_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display.', '6', '0', now(), '" . $vendors_id . "')");
    }

    function remove($vendors_id) {
      tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
      }

    function keys($vendors_id) {
      return array('MODULE_SHIPPING_INDVSHIP_STATUS_' . $vendors_id, 'MODULE_SHIPPING_INDVSHIP_HANDLING_' . $vendors_id, 'MODULE_SHIPPING_INDVSHIP_TAX_CLASS_' . $vendors_id, 'MODULE_SHIPPING_INDVSHIP_ZONE_' . $vendors_id, 'MODULE_SHIPPING_INDVSHIP_SORT_ORDER_' . $vendors_id);
    }
  }
?>