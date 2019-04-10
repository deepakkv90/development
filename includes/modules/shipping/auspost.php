<?php
/*
  $Id: auspost.php,v 2.0.2 2003/10/15

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class auspost {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function auspost() {
      global $order;

      $this->code = 'auspost';
      $this->title = MODULE_SHIPPING_AUSPOST_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_AUSPOST_TEXT_DESCRIPTION;
      if (defined('MODULE_SHIPPING_AUSPOST_SORT_ORDER')) {
        $this->sort_order = (int)MODULE_SHIPPING_AUSPOST_SORT_ORDER;
      } else {
        $this->sort_order = '';
      }

      $this->icon = DIR_WS_ICONS . 'auspost.gif';
      if (defined('MODULE_SHIPPING_AUSPOST_TAX_CLASS')) {
        $this->tax_class = (int)MODULE_SHIPPING_AUSPOST_TAX_CLASS;
      } else {
        $this->tax_class = 0;
      }

      if (defined('MODULE_SHIPPING_AUSPOST_STATUS')) {
        $this->enabled = ((MODULE_SHIPPING_AUSPOST_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_AUSPOST_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_AUSPOST_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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
    }

// class methods
    function quote($method = '') {
      global $order,  $cart, $shipping_weight, $shipping_num_boxes, $total_weight;
			  	  
      if($order->delivery['country']['iso_code_2'] == 'AU' || $method=="instant"){
	  
	  $frompcode = MODULE_SHIPPING_AUSPOST_SPCODE;
      $topcode = $order->delivery['postcode'];
      $sweight = $shipping_weight*1000;
      $swidth = MODULE_SHIPPING_AUSPOST_SWIDTH;
      $sheight = MODULE_SHIPPING_AUSPOST_SHEIGHT;
      $slength = MODULE_SHIPPING_AUSPOST_SDEPTH;
      $error = false;

		if ($sweight < 1) {
		 $sweight = 300;
		}

      $insurance_table = split("[:,]" , MODULE_SHIPPING_AUSPOST_INSURANCE);
        for ($i = 0; $i < count($insurance_table); $i+=2) {
          if ($cart->show_total() <= $insurance_table[$i]) {
            $insurance = $insurance_table[$i+1];
            $insurance_details = " $" .$insurance . " " .MODULE_SHIPPING_AUSPOST_TEXT_INSURANCE;
            break;
          }
        }
        $url = "http://drc.edeliver.com.au/ratecalc.asp?Pickup_Postcode=$frompcode&Destination_Postcode=$topcode&Country=AU&Weight=$sweight&Service_Type=STANDARD&Height=$sheight&Width=$swidth&Length=$slength&Quantity=$shipping_num_boxes";
        $myfile = file($url);
        foreach($myfile as $vals)
        {
                $bits = split("=", $vals);
                $$bits[0] = $bits[1];
        }
      if ($charge <= 0) {
        $error = true;
      } else {

    $handling = MODULE_SHIPPING_AUSPOST_HANDLING;
        if ($handling >0) {
          $handling_details = " $" . MODULE_SHIPPING_AUSPOST_HANDLING . " " .MODULE_SHIPPING_AUSPOST_TEXT_HANDLING;
        } else {
          $handling_details = "";
      }

        if ($insurance >0) {
          $insurance_details = " $" .$insurance . " " .MODULE_SHIPPING_AUSPOST_TEXT_INSURANCE;
        } else {
          $insurance_details = "";
      }

          if ($insurance == 0 && $handling  == 0) {
          $auspost_addons = "";
          } else {
          $auspost_addons = " (" . MODULE_SHIPPING_AUSPOST_TEXT_INCLUDE . $handling_details . $insurance_details . ") ";
        }

       /* $shipping_auspost_method = MODULE_SHIPPING_AUSPOST_TEXT_WAY. " <b>"  . $topcode . "</b> - " . $days . " " . MODULE_SHIPPING_AUSPOST_TEXT_SHIPPINGDAYS . "<br>" .$shipping_num_boxes . "&nbsp;" .MODULE_SHIPPING_AUSPOST_TEXT_BOXES  . " " . $total_weight . MODULE_SHIPPING_AUSPOST_TEXT_UNITS . $auspost_addons;*/
	    $shipping_auspost_method = MODULE_SHIPPING_AUSPOST_TEXT_WAY. " <b>"  . $topcode . "</b> - " . $days . " " . MODULE_SHIPPING_AUSPOST_TEXT_SHIPPINGDAYS . $auspost_addons;
        $shipping_auspost_cost = (($charge/1.1)* $shipping_num_boxes);
      }

      /*$this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_AUSPOST_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => (isset($shipping_auspost_method) ? $shipping_auspost_method : '') ,
                                                     'cost' => ((isset($shipping_auspost_cost) ? $shipping_auspost_cost : 0) + MODULE_SHIPPING_AUSPOST_HANDLING) + (isset($insurance) ? $insurance : 0))));*/
													 
	   $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_AUSPOST_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => (isset($shipping_auspost_method) ? $shipping_auspost_method : '') ,
                                                     'cost' =>  MODULE_SHIPPING_AUSPOST_INSURANCE )));													 

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      //if ($error == true) $this->quotes['error'] = MODULE_SHIPPING_AUSPOST_TEXT_ERROR;
	  
		/* Added on 06-08-2015 */
	      $approximate_duedate = MODULE_SHIPPING_AUSPOST_APPROX_DUEDATE;
		  if(!empty($approximate_duedate)) {
				$today = strtotime(date("Y-m-d h:m:s"));
				if($approximate_duedate > 0) {
					$today = strtotime("+".$approximate_duedate." day", $today);
				}
				$duedate = date("Y-m-d h:m:s", $today);  
				$this->quotes['duedate'] = $duedate;
		  }
		   
      return $this->quotes;
	  }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_AUSPOST_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable auspost', 'MODULE_SHIPPING_AUSPOST_STATUS', 'True', 'Do you want to offer auspost?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Insurance', 'MODULE_SHIPPING_AUSPOST_INSURANCE', '25:5.90', 'Insurance cost is based on the total cost of items. Example: 25:8.50,50:5.50,etc.. Up to $25 charge $8.50, from there to $50 charge $5.50, etc', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Dispatch Postcode', 'MODULE_SHIPPING_AUSPOST_SPCODE', '2000', 'Dispatch Postcode?', '6', '0', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Due date for this Method?', 'MODULE_SHIPPING_AUSPOST_APPROX_DUEDATE', '6', 'Example, If Minimum Due date 3 and Order date is 05-08-2015 then due date will be 08-08-2015', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee', 'MODULE_SHIPPING_AUSPOST_HANDLING', '10', 'Handling Fee for this shipping method', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Parcel Height', 'MODULE_SHIPPING_AUSPOST_SHEIGHT', '100', 'Parcel Height (in mm)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Parcel Width', 'MODULE_SHIPPING_AUSPOST_SWIDTH', '100', 'Parcel Width (in mm)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Parcel Depth', 'MODULE_SHIPPING_AUSPOST_SDEPTH', '100', 'Parcel Depth (in mm)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_AUSPOST_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_AUSPOST_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_SHIPPING_AUSPOST_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");

    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_AUSPOST_STATUS', 'MODULE_SHIPPING_AUSPOST_INSURANCE', 'MODULE_SHIPPING_AUSPOST_SPCODE', 'MODULE_SHIPPING_AUSPOST_HANDLING', 'MODULE_SHIPPING_AUSPOST_SHEIGHT', 'MODULE_SHIPPING_AUSPOST_SWIDTH', 'MODULE_SHIPPING_AUSPOST_SDEPTH', 'MODULE_SHIPPING_AUSPOST_ZONE', 'MODULE_SHIPPING_AUSPOST_TAX_CLASS', 'MODULE_SHIPPING_AUSPOST_SORT_ORDER', 'MODULE_SHIPPING_AUSPOST_APPROX_DUEDATE');
    }
  }
?>
