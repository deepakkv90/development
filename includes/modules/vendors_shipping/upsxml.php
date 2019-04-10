<?php
/*
  $Id: upsxml.php,v 1.0.0.0 2008/06/17 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
include(DIR_WS_CLASSES . 'xml.php');
class upsxml {
    var $code, $title, $description, $icon, $enabled, $types, $boxcount;

    //***************
    function upsxml() {
        global $order, $packing;
        $this->code = 'upsxml';
        $this->title = MODULE_SHIPPING_UPSXML_RATES_TEXT_TITLE;
        $this->description = MODULE_SHIPPING_UPSXML_RATES_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER;
        $this->icon = DIR_WS_ICONS . 'shipping_ups.gif';
        $this->tax_class = MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS;
        $this->enabled = ((MODULE_SHIPPING_UPSXML_RATES_STATUS == 'True') ? true : false);
        $this->access_key = MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY;
        $this->access_username = MODULE_SHIPPING_UPSXML_RATES_USERNAME;
        $this->access_password = MODULE_SHIPPING_UPSXML_RATES_PASSWORD;
        $this->access_account_number = MODULE_SHIPPING_UPSXML_RATES_UPS_ACCOUNT_NUMBER;
        // BLM 2-14-08 MANUAL NEGOTIATED RATE
    // set the rate
        $this->manual_negotiated_rate = MODULE_SHIPPING_UPSXML_RATES_MANUAL_NEGOTIATED_RATE;
        $this->use_negotiated_rates = MODULE_SHIPPING_UPSXML_RATES_USE_NEGOTIATED_RATES;
        $this->origin = MODULE_SHIPPING_UPSXML_RATES_ORIGIN;
        $this->origin_city = MODULE_SHIPPING_UPSXML_RATES_CITY;
        $this->origin_stateprov = MODULE_SHIPPING_UPSXML_RATES_STATEPROV;
        $this->origin_country = MODULE_SHIPPING_UPSXML_RATES_COUNTRY;
        $this->origin_postalcode = MODULE_SHIPPING_UPSXML_RATES_POSTALCODE;
        $this->pickup_method = MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD;
        $this->package_type = MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE;
// the variables for unit weight, unit length, and dimensions support were moved to
// shop admin -> Configuration -> Shipping/Packaging in
// version 1.3.0. Run the configuration_shipping.sql to add these to your configuration
        if (defined('MODULE_SHIPPING_UPSXML_UNIT_WEIGHT')) {
        $this->unit_weight = MODULE_SHIPPING_UPSXML_UNIT_WEIGHT;
        } else {
// for those who will undoubtedly forget or not know how to run the configuration_shipping.sql
// we will set the default to pounds (LBS) and inches (IN)
        $this->unit_weight = 'LBS';
        }
        if (defined('MODULE_SHIPPING_UPSXML_UNIT_LENGTH')) {
        $this->unit_length = MODULE_SHIPPING_UPSXML_UNIT_LENGTH;
        } else {
          $this->unit_length = 'IN';
        }
        if (defined('MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED') && MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED == 'Ready-to-ship only') {
          $this->dimensions_support = 1;
        } elseif (defined('MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED') && MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED == 'With product dimensions') {
          $this->dimensions_support = 2;
        } else {
          $this->dimensions_support = 0;
        }
        $this->email_errors = ((MODULE_SHIPPING_UPSXML_EMAIL_ERRORS == 'Yes') ? true : false);
        $this->handling_type = MODULE_SHIPPING_UPSXML_HANDLING_TYPE;
        $this->handling_fee = MODULE_SHIPPING_UPSXML_RATES_HANDLING;
        $this->quote_type = MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE;
        $this->customer_classification = MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE;
        $this->protocol = 'https';
        $this->host = ((MODULE_SHIPPING_UPSXML_RATES_MODE == 'Test') ? 'wwwcie.ups.com' : 'www.ups.com');
        $this->port = '443';
        $this->path = '/ups.app/xml/Rate';
        $this->transitpath = '/ups.app/xml/TimeInTransit';
        $this->version = 'UPSXML Rate 1.0001';
        $this->transitversion = 'UPSXML Time In Transit 1.0002';
        $this->timeout = '60';
        $this->xpci_version = '1.0001';
        $this->transitxpci_version = '1.0002';
        $this->items_qty = 0;
        $this->timeintransit = '0';
        $this->timeInTransitView = MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW;
        $this->weight_for_timeintransit = '0';
        $now_unix_time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        $this->today_unix_time = $now_unix_time;
        $this->today = date("Ymd");
        // insurance addition
        if (MODULE_SHIPPING_UPSXML_INSURE == 'False') { 
          $this->pkgvalue = 100; 
          } else {
          $this->pkgvalue = ceil($order->info['subtotal']); // is divided by number of boxes later
          }
        // end insurance addition
        // to enable logging, create an empty "upsxml.log" file at the location you set below, give it write permissions (777) and uncomment the next line
        $this->logfile = DIR_FS_CATALOG.DIR_WS_MODULES.'vendors_shipping/upsxml.log';
        // to enable logging of just the errors, do as above but call the file upsxml_error.log
        $this->ups_error_file = DIR_FS_CATALOG.DIR_WS_MODULES.'vendors_shipping/upsxml_error.log';
        // when cURL is not compiled into PHP (Windows users, some Linux users)
        // you can set the next variable to "1" and then exec(curl -d $xmlRequest, $xmlResponse)
        // will be used
        $this->use_exec = '0';

        if (($this->enabled == true) && ((int)MODULE_SHIPPING_UPSXML_RATES_ZONE > 0)) {
            $check_flag = false;
            $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_UPSXML_RATES_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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

        // Available pickup types - set in admin
        $this->pickup_methods = array(
            'Daily Pickup' => '01',
            'Customer Counter' => '03',
            'One Time Pickup' => '06',
            'On Call Air Pickup' => '07',
            'Suggested Retail Rates (UPS Store)' => '11',
            'Letter Center' => '19',
            'Air Service Center' => '20'
        );

        // Available package types
        $this->package_types = array(
            'UPS Letter' => '01',
            'Package' => '02',
            'UPS Tube' => '03',
            'UPS Pak' => '04',
            'UPS Express Box' => '21',
            'UPS 25kg Box' => '24',
            'UPS 10kg Box' => '25'
        );

        // Human-readable Service Code lookup table. The values returned by the Rates and Service "shop" method are numeric.
        // Using these codes, and the administratively defined Origin, the proper human-readable service name is returned.
        // Note: The origin specified in the admin configuration affects only the product name as displayed to the user.
        $this->service_codes = array(
            // US Origin
            'US Origin' => array(
                '01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_01,
                '02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_02,
                '03' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_03,
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_11,
                '12' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_12,
                '13' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_13,
                '14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_14,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_54,
                '59' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_59,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_US_ORIGIN_65
            ),
            // Canada Origin
            'Canada Origin' => array(
                '01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_01,
                '02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_02,
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_11,
                '12' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_12,
                '13' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_13,
                '14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_14,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_CANADA_ORIGIN_65
            ),
            // European Union Origin
            'European Union Origin' => array(
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_11,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_65,
                // next five services Poland domestic only
                '82' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_82,
                '83' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_83,
                '84' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_84,
                '85' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_85,
                '86' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_EU_ORIGIN_86
            ),
            // Puerto Rico Origin
            'Puerto Rico Origin' => array(
                '01' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_01,
                '02' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_02,
                '03' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_03,
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_08,
                '14' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_14,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_PR_ORIGIN_65
            ),
            // Mexico Origin
            'Mexico Origin' => array(
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_08,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_MEXICO_ORIGIN_65
            ),
            // All other origins
            'All other origins' => array(
                // service code 7 seems to be gone after January 2, 2007
                '07' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_07,
                '08' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_08,
                '11' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_11,
                '54' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_54,
                '65' => MODULE_SHIPPING_UPSXML_SERVICE_CODE_OTHER_ORIGIN_65
            )
        );
    } // end function upsxml
    
     function access_key($vendors_id='1') {
      $this->access_key = constant('MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY_' . $vendors_id);
      return $this->access_key;
    }

    function access_username($vendors_id='1') {
      $this->access_username = constant('MODULE_SHIPPING_UPSXML_RATES_USERNAME_' . $vendors_id);
      return $this->access_username;
    }

    function access_password($vendors_id='1') {
      $this->access_password = constant('MODULE_SHIPPING_UPSXML_RATES_PASSWORD_' . $vendors_id);
      return $this->access_password;
    }

    function origin($vendors_id='1') {
      $this->origin = constant('MODULE_SHIPPING_UPSXML_RATES_ORIGIN_' . $vendors_id);
      return $this->origin;
    }

    function origin_city($vendors_id='1') {
      $this->origin_city = constant('MODULE_SHIPPING_UPSXML_RATES_CITY_' . $vendors_id);
      return $this->origin_city;
    }

    function origin_stateprov($vendors_id='1') {
      $this->origin_stateprov = constant('MODULE_SHIPPING_UPSXML_RATES_STATEPROV_' . $vendors_id);
      return $this->origin_stateprov;
    }

    function origin_country($vendors_id='1') {
      $this->origin_country = constant('MODULE_SHIPPING_UPSXML_RATES_COUNTRY_' . $vendors_id);
      return $this->origin_country;
    }

    function origin_postalcode($vendors_id='1') {
      $this->origin_postalcode = constant('MODULE_SHIPPING_UPSXML_RATES_POSTALCODE_' . $vendors_id);
      return $this->origin_postalcode;
    }

    function pickup_method($vendors_id='1') {
      $this->pickup_method = constant('MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD_' . $vendors_id);
      return $this->pickup_method;
    }

    function package_type($vendors_id='1') {
      $this->package_type = constant('MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE_' . $vendors_id);
      return $this->package_type;
    }

    function unit_weight($vendors_id='1') {
      $this->unit_weight = constant('MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT_' . $vendors_id);
      return $this->unit_weight;
    }

    function unit_length($vendors_id='1') {
      $this->unit_length = constant('MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH_' . $vendors_id);
      return $this->unit_length;
    }

    function handling_fee($vendors_id='1') {
      $this->handling_fee = constant('MODULE_SHIPPING_UPSXML_RATES_HANDLING_' . $vendors_id);
      return $this->handling_fee;
    }

    function quote_type($vendors_id='1') {
      $this->quote_type = constant('MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE_' . $vendors_id);
      return $this->quote_type;
    }

    function customer_classification($vendors_id='1') {
      $this->customer_classification = constant('MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE_' . $vendors_id);
      return $this->customer_classification;
    }

    function host($vendors_id) {
      $this->host = @constant('MODULE_SHIPPING_UPSXML_RATES_TEST_MODE_' . $vendors_id) == 'Test' ? 'wwwcie.ups.com' : 'wwwcie.ups.com';
      return $this->host;
    }

   function sort_order($vendors_id='1') {
     $sort_order = @constant ('MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER_' . $vendors_id);
     if (isset ($sort_order)) {
       $this->sort_order = $sort_order;
     } else {
       $this->sort_order = '-';
     }
     return $this->sort_order;
   }

    function tax_class($vendors_id='1') {
      $this->tax_class = constant('MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS_' . $vendors_id);
      return $this->tax_class;
    }

    function enabled($vendors_id='1') {
      $this->enabled = false;
      $status = @constant('MODULE_SHIPPING_UPSXML_RATES_STATUS_' . $vendors_id);
                        if (isset ($status) && $status != '') {
        $this->enabled = (($status == 'True') ? true : false);
      }
      if ( ($this->enabled == true) && ((int)constant('MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_country_id . "' order by zone_id");
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
      if ( ($this->enabled == true) && ((int)@constant('MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id) > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . (int)constant('MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id) . "' and zone_country_id = '" . $this->delivery_zone_id . "' order by zone_id");
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


    // class methods
    function quote($method = '', $module = '', $vendors_id = '1') {
        global $HTTP_POST_VARS, $order, $shipping_weight, $shipping_num_boxes, $total_weight, $boxcount, $cart, $packing;
        // UPS purports that if the origin is left out, it defaults to the account's location. Yeah, right.
        $state = $order->delivery['state'];
        $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name = '" .  tep_db_input($order->delivery['state']) . "'");
        if (tep_db_num_rows($zone_query)) {
            $zone = tep_db_fetch_array($zone_query);
            $state = $zone['zone_code'];
        }
        $this->_upsOrigin(constant('MODULE_SHIPPING_UPSXML_RATES_CITY_' . $vendors_id), constant('MODULE_SHIPPING_UPSXML_RATES_STATEPROV_' . $vendors_id), constant('MODULE_SHIPPING_UPSXML_RATES_COUNTRY_' . $vendors_id), constant('MODULE_SHIPPING_UPSXML_RATES_POSTALCODE_' . $vendors_id));
        $this->_upsDest($order->delivery['city'], $state, $order->delivery['country']['iso_code_2'], $order->delivery['postcode']);
        
// the check on $packing being an object will puzzle people who do things wrong (no changes when 
// you enable dimensional support without changing checkout_shipping.php) but better be safe
        if ($this->dimensions_support > 0 && is_object($packing)) {
          $boxValue = 0;
          $totalWeight = $packing->getTotalWeight();
          $shipping_num_boxes = $packing->getNumberOfBoxes();
          $boxesToShip = $packing->getPackedBoxes();
             for ($i = 0; $i < count($boxesToShip); $i++) {
                if (MODULE_SHIPPING_UPSXML_INSURE == 'False') {
// to avoid the addition of an insurance fee a hardcoded pkgValue (see above around line 93)
// of 100 is used when no insurance is wanted
                  $boxesToShip[$i]['item_price'] = $this->pkgvalue; 
                } 
                $this->_addItem($boxesToShip[$i]['item_length'], $boxesToShip[$i]['item_width'], $boxesToShip[$i]['item_height'], $boxesToShip[$i]['item_weight'], $boxesToShip[$i]['item_price']);
                } // end for ($i = 0; $i < count($boxesToShip); $i++)
        } else {
            // The old method. Let osCommerce tell us how many boxes, plus the weight of each (or total? - might be sw/num boxes)
            $this->items_qty = 0; //reset quantities
            if (MODULE_SHIPPING_UPSXML_INSURE == 'False') {
               for ($i = 0; $i < $shipping_num_boxes; $i++) {
                  $this->_addItem(0, 0, 0, $shipping_weight, $this->pkgvalue);
               }
            } else {
// $this->pkgvalue has been set as order subtotal around line 86, it will cause overcharging
// of insurance if not divided by the number of boxes
               for ($i = 0; $i < $shipping_num_boxes; $i++) {
                  $this->_addItem(0, 0, 0, $shipping_weight, number_format(($this->pkgvalue/$shipping_num_boxes), 2, '.', ''));
               }
            } // end if/else  (MODULE_SHIPPING_UPSXML_INSURE == 'False')
        }

      // BOF Time In Transit: used for expected delivery dates
      // is skipped when set to "Not" in the admin
      if ($this->timeInTransitView != 'Not') {
        if ($this->dimensions_support > 0) {
            $this->weight_for_timeintransit = round($totalWeight,1);
        } else {
            $this->weight_for_timeintransit = round($shipping_num_boxes * $shipping_weight,1);
        }
        // Added to workaround time in transit error 270033 if total weight of packages is over 150lbs or 70kgs
        if (($this->weight_for_timeintransit > 150) && ($this->unit_weight == "LBS")) {
          $this->weight_for_timeintransit = 150;          
        } else if (($this->weight_for_timeintransit > 70) && ($this->unit_weight == "KGS")) {
          $this->weight_for_timeintransit = 70;          
        }
        // debug only:
        /*  echo '<pre>Packages and variables:<br>';
         print_r($this);
         echo '<br>';
         exit; */
        // make sure that when TimeinTransit fails to get results (error or not available)
        // this is not obvious to the client
        $_upsGetTimeServicesResult = $this->_upsGetTimeServices();
        if ($_upsGetTimeServicesResult != false && is_array($_upsGetTimeServicesResult)) {
        //$this->servicesTimeintransit = $_upsGetTimeServicesResult;
        $this->servicesTimeintransit = $this->_upsGetTimeServices($vendors_id);
        }
        if ($this->logfile) {
            error_log("------------------------------------------\n", 3, $this->logfile);
            error_log("Time in Transit: " . $this->timeintransit . "\n", 3, $this->logfile);
        }
      } // end if ($this->timeInTransitView != 'Not') 
      // EOF Time In Transit

        $upsQuote = $this->_upsGetQuote($vendors_id);        
        if ((is_array($upsQuote)) && (sizeof($upsQuote) > 0)) {
          if (defined('MODULE_SHIPPING_UPSXML_WEIGHT1') &&  MODULE_SHIPPING_UPSXML_WEIGHT1 == 'False') {
            $this->quotes = array('id' => $this->code, 'module' => $this->title);
            usort($upsQuote, array($this, "rate_sort_func"));
          } else {
            if ($this->dimensions_support > 0) {
                $this->quotes = array('id' => $this->code, 'module' => $this->title . ' (' . $this->boxCount . ($this->boxCount > 1 ? ' pkg(s), ' : ' pkg, ') . round($totalWeight,0) . ' ' . strtolower($this->unit_weight) . ' total)');
            } else {
                $this->quotes = array('id' => $this->code, 'module' => $this->title . ' (' . $shipping_num_boxes . ($this->boxCount > 1 ? ' pkg(s) x ' : ' pkg x ') . round($shipping_weight,0) . ' ' . strtolower($this->unit_weight) . ' total)');
            }
            usort($upsQuote, array($this, "rate_sort_func"));
          } // end else/if if (defined('MODULE_SHIPPING_UPSXML_WEIGHT1')
            $methods = array();
            for ($i=0; $i < sizeof($upsQuote); $i++) {
                list($type, $cost) = each($upsQuote[$i]);
                // BOF limit choices, behaviour changed from versions < 1.2
                if ($this->exclude_choices($type,$vendors_id)) continue;
                // EOF limit choices
                if ( $method == '' || $method == $type ) {
                    $_type = $type;

                    if ($this->timeInTransitView == "Raw") {
                      if (isset($this->servicesTimeintransit[$type])) {
                        $_type = $_type . ", ".$this->servicesTimeintransit[$type]["date"];
                      }        
                    } else {
                      if (isset($this->servicesTimeintransit[$type])) {
                        $eta_array = explode("-", $this->servicesTimeintransit[$type]["date"]);
                        $months = array (" ", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        $eta_arrival_date = $months[(int)$eta_array[1]]." ".$eta_array[2].", ".$eta_array[0];
                        $_type .= ", <acronym title='Estimated Delivery Date'>EDD</acronym>: ".$eta_arrival_date;
                      }          
                    }                    
                    // BLM 2-14-08 SET MANUAL NEGOTIATED RATE
                    if ( ($this->manual_negotiated_rate > 0) && ($this->use_negotiated_rates != 'True') ) {
            $cost = ($this->manual_negotiated_rate * $cost)/100;
                    }
                    // changed to make handling percentage based
                    if ($this->handling_type == "Percentage") {
                        $methods[] = array('id' => $type, 'title' => $_type, 'cost' => ((($this->handling_fee * $cost)/100) + $cost));
                    } else {
                        $methods[] = array('id' => $type, 'title' => $_type, 'cost' => ($this->handling_fee + $cost));
                    }
                }
            }
            if ($this->tax_class($vendors_id) > 0) {
                $this->quotes['tax'] = tep_get_tax_rate($this->tax_class($vendors_id), $order->delivery['country']['id'], $order->delivery['zone_id']);
            }
            $this->quotes['methods'] = $methods;
        } else {
            if ( $upsQuote != false ) {
                $errmsg = $upsQuote;
            } else {
                $errmsg = MODULE_SHIPPING_UPSXML_RATES_TEXT_UNKNOWN_ERROR;
            }
            $errmsg .= '<br>' . MODULE_SHIPPING_UPSXML_RATES_TEXT_IF_YOU_PREFER . ' ' . STORE_NAME.' via <a href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'"><u>Email</U></a>.';
            $this->quotes = array('module' => $this->title, 'error' => $errmsg);
        }
        if (tep_not_null($this->icon)) {
            $this->quotes['icon'] = tep_image($this->icon, $this->title);
        }
        return $this->quotes;
    }

    //**************
    function check($vendors_id='1') {
        if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key = 'MODULE_SHIPPING_UPSXML_RATES_STATUS_" . $vendors_id . "'");
        $this->_check = tep_db_num_rows($check_query);
      }
        return $this->_check;
    }

    //**************
    function install($vendors_id='1') {
      $vendors_id = $_GET['vendors_id'];
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable UPS Shipping', 'MODULE_SHIPPING_UPSXML_RATES_STATUS_" . $vendors_id . "', 'True', 'Do you want to offer UPS shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(),'" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('UPS Rates Access Key', 'MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY_" . $vendors_id . "', '', 'Enter the XML rates access key assigned to you by UPS.', '6', '1', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('UPS Rates Username', 'MODULE_SHIPPING_UPSXML_RATES_USERNAME_" . $vendors_id . "', '', 'Enter your UPS Services account username.', '6', '2', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('UPS Rates Password', 'MODULE_SHIPPING_UPSXML_RATES_PASSWORD_" . $vendors_id . "', '', 'Enter your UPS Services account password.', '6', '3', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Pickup Method', 'MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD_" . $vendors_id . "', 'Daily Pickup', 'How do you give packages to UPS (only used when origin is US)?', '6', '4', 'tep_cfg_select_option(array(\'Daily Pickup\', \'Customer Counter\', \'One Time Pickup\', \'On Call Air Pickup\', \'Letter Center\', \'Air Service Center\', \'Suggested Retail Rates (UPS Store)\'), ', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Packaging Type', 'MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE_" . $vendors_id . "', 'Package', 'What kind of packaging do you use?', '6', '5', 'tep_cfg_select_option(array(\'Package\', \'UPS Letter\', \'UPS Tube\', \'UPS Pak\', \'UPS Express Box\', \'UPS 25kg Box\', \'UPS 10kg box\'), ', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Customer Classification Code', 'MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE_" . $vendors_id . "', '01', '01 - If you are billing to a UPS account and have a daily UPS pickup, 03 - If you do not have a UPS account or you are billing to a UPS account but do not have a daily pickup, 04 - If you are shipping from a retail outlet (only used when origin is US)', '6', '6', 'tep_cfg_select_option(array(\'01\', \'03\', \'04\'), ', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Shipping Origin', 'MODULE_SHIPPING_UPSXML_RATES_ORIGIN_" . $vendors_id . "', 'US Origin', 'What origin point should be used (this setting affects only what UPS product names are shown to the user)', '6', '7', 'tep_cfg_select_option(array(\'US Origin\', \'Canada Origin\', \'European Union Origin\', \'Puerto Rico Origin\', \'Mexico Origin\', \'All other origins\'), ', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Origin City', 'MODULE_SHIPPING_UPSXML_RATES_CITY_" . $vendors_id . "', '', 'Enter the name of the origin city.', '6', '8', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Origin State/Province', 'MODULE_SHIPPING_UPSXML_RATES_STATEPROV_" . $vendors_id . "', '', 'Enter the two-letter code for your origin state/province.', '6', '9', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Origin Country', 'MODULE_SHIPPING_UPSXML_RATES_COUNTRY_" . $vendors_id . "', '', 'Enter the two-letter code for your origin country.', '6', '10', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Origin Zip/Postal Code', 'MODULE_SHIPPING_UPSXML_RATES_POSTALCODE_" . $vendors_id . "', '', 'Enter your origin zip/postalcode.', '6', '11', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Test or Production Mode', 'MODULE_SHIPPING_UPSXML_RATES_MODE_" . $vendors_id . "', 'Test', 'Use this module in Test or Production mode?', '6', '12', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now(), '" . $vendors_id . "')");
        tep_db_query("INSERT INTO " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Unit Weight', 'MODULE_SHIPPING_UPSXML_RATES_UNIT_WEIGHT_" . $vendors_id . "', 'LBS', 'By what unit are your packages weighed?', '6', '7', 'tep_cfg_select_option(array(\'LBS\', \'KGS\'), ', now(), '" . $vendors_id . "')");
        tep_db_query("INSERT INTO " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Unit Length', 'MODULE_SHIPPING_UPSXML_RATES_UNIT_LENGTH_" . $vendors_id . "', 'IN', 'By what unit are your packages sized?', '6', '8', 'tep_cfg_select_option(array(\'IN\', \'CM\'), ', now(), '" . $vendors_id . "')");
         tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Quote Type', 'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE_" . $vendors_id . "', 'Commercial', 'Quote for Residential or Commercial Delivery', '6', '15', 'tep_cfg_select_option(array(\'Commercial\', \'Residential\'), ', now(), '" . $vendors_id . "')");
         tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Handling Fee', 'MODULE_SHIPPING_UPSXML_RATES_HANDLING_" . $vendors_id . "', '0', 'Handling fee for this shipping method.', '6', '16', now(), '" . $vendors_id . "')");
         tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Tax Class', 'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS_" . $vendors_id . "', '0', 'Use the following tax class on the shipping fee.', '6', '17', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now(), '" . $vendors_id . "')");
         tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added, vendors_id) values ('Shipping Zone', 'MODULE_SHIPPING_UPSXML_RATES_ZONE_" . $vendors_id . "', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '18', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now(), '" . $vendors_id . "')");
         tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('Sort order of display.', 'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER_" . $vendors_id . "', '0', 'Sort order of display. Lowest is displayed first.', '6', '19', now(), '" . $vendors_id . "')");
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Shipping Methods', 'MODULE_SHIPPING_UPSXML_TYPES_" . $vendors_id . "', 'Next Day Air, 2nd Day Air, Ground, Worldwide Express, Standard, 3 Day Select', 'Select the UPS services to be offered.', '6', '20', 'tep_cfg_select_multioption_vendor(array(\'Next Day Air\', \'2nd Day Air\', \'Ground\', \'Worldwide Express\', \'Worldwide Expedited\', \'Standard\', \'3 Day Select\', \'Next Day Air Saver\', \'Next Day Air Early A.M.\', \'Worldwide Express Plus\', \'2nd Day Air A.M.\', \'Express NA1\', \'Express Saver\'), ',  now(), '" . $vendors_id . "')");
         // add key for shipping delay, changed the constant from SHIPPING_DAYS_DELAY in v1.3.0
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function, vendors_id) values ('Shipping Delay', 'MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY_" . $vendors_id . "', '1', 'How many days from when an order is placed to when you ship it (Decimals are allowed). Arrival date estimations are based on this value.', '6', '21', now(), now(), NULL, NULL, '" . $vendors_id . "')");
        // add key for enabling email error messages
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Email UPS errors', 'MODULE_SHIPPING_UPSXML_EMAIL_ERRORS_" . $vendors_id . "', 'Yes', 'Do you want to receive UPS errors by email?', '6', '24', 'tep_cfg_select_option(array(\'Yes\', \'No\'), ', now(), '" . $vendors_id . "')");
         // add key for time in transit view type
        tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Time in Transit View Type', 'MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW_" . $vendors_id . "', 'Not', 'If and how the module should display the time in transit to the customer.', '6', '16', 'tep_cfg_select_option(array(\'Not\',\'Raw\', \'Detailed\'), ', now(), '" . $vendors_id . "')");
       tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Display Weight', 'MODULE_SHIPPING_UPSXML_WEIGHT1_" . $vendors_id . "', 'True', 'Do you want to show number of packages and package weight?', '6', '27', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
       tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('UPS Currency Code', 'MODULE_SHIPPING_UPSXML_CURRENCY_CODE_" . $vendors_id . "', '', 'Enter the 3 letter currency code for your country of origin. United States (USD)', '6', '2', now(), '" . $vendors_id . "')");
       tep_db_query("INSERT INTO " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) VALUES ('Manual Negotiated Rate', 'MODULE_SHIPPING_UPSXML_RATES_MANUAL_NEGOTIATED_RATE_" . $vendors_id . "', '', 'Enter a negotiated rate manually. <b>Note:</b> If \"Negotiated Rates\" above is set to \"True\", This <b>WILL NOT</b> be applied. If using this option, set \"Negotiated Rates\" to \"False\". Usage: \" 57 \" returns 57% of published UPS rate.', '6', '28', now(), '" . $vendors_id . "')"); 
       tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, vendors_id) values ('UPS Account Number', 'MODULE_SHIPPING_UPSXML_RATES_UPS_ACCOUNT_NUMBER_" . $vendors_id . "', '', 'Enter your UPS Account number when you have and want to use negotiated rates.', '6', '26', now(), '" . $vendors_id . "')");
      tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Enable Insurance', 'MODULE_SHIPPING_UPSXML_INSURE_" . $vendors_id . "', 'True', 'Do you want to insure packages shipped by UPS?', '6', '22', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
      // added for handling type selection
       tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Handling Type', 'MODULE_SHIPPING_UPSXML_HANDLING_TYPE_" . $vendors_id . "', 'Flat Fee', 'Handling type for this shipping method.', '6', '16', 'tep_cfg_select_option(array(\'Flat Fee\', \'Percentage\'), ', now(), '" . $vendors_id . "')");
       // next two keys added to be able to use negotiated rates (available from UPS since about July 2006)
       tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) values ('Negotiated rates', 'MODULE_SHIPPING_UPSXML_RATES_USE_NEGOTIATED_RATES_" . $vendors_id . "', 'False', 'Do you receive discounted rates from UPS and want to use these for shipping quotes? <b>Note:</b>  You need to enter your UPS account number below.', '6', '25', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now(), '" . $vendors_id . "')");
       tep_db_query("INSERT INTO " . TABLE_VENDOR_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added, vendors_id) VALUES ('Dimensions Support', 'MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED_" . $vendors_id . "', 'No', 'Do you use the additional dimensions support (read dimensions.txt in the UPSXML package)?', '6', '6', 'tep_cfg_select_option(array(\'No\', \'Ready-to-ship only\', \'With product dimensions\'), ', now(), '" . $vendors_id . "')");
    
        
        if (!defined('FILENAME_PACKAGING')) {
         tep_db_query('INSERT INTO ' . TABLE_CONFIGURATION . ' VALUES ("", "", "FILENAME_PACKAGING", "packaging.php", "", 0, 1, now(), now(), NULL, NULL)');
        }
        if (!defined('TABLE_UPSXML_PACKAGING')) {
          tep_db_query('INSERT INTO ' . TABLE_CONFIGURATION . ' VALUES ("", "", "TABLE_UPSXML_PACKAGING", "upsxml_packaging", "", 0, 1, now(), now(), NULL, NULL)');
        }
        if (!defined('TABLE_UPSXML_PRODUCTS_DIMENSION')) {
          tep_db_query('INSERT INTO ' . TABLE_CONFIGURATION . ' VALUES ("", "", "TABLE_UPSXML_PRODUCTS_DIMENSION", "upsxml_products_dimension", "", 0, 1, now(), now(), NULL, NULL)');
        }

        // create separate tables for this module
        tep_db_query("CREATE TABLE IF NOT EXISTS upsxml_products_dimension (
                      products_id int(11) NOT NULL,
                      products_length decimal(10,2) NOT NULL,
                      products_width decimal(10,2) NOT NULL,
                      products_height decimal(10,2) NOT NULL,
                      products_ready_to_ship tinyint(1) NOT NULL,
                      last_modified datetime NOT NULL,
                      PRIMARY KEY  (products_id))");
        tep_db_query("CREATE TABLE IF NOT EXISTS upsxml_packaging (
                      package_id int(11) NOT NULL auto_increment,
                      package_name varchar(64) NOT NULL default '',
                      package_description varchar(255) NOT NULL default '',
                      package_length decimal(10,2) NOT NULL default '5.00',
                      package_width decimal(10,2) NOT NULL default '5.00',
                      package_height decimal(10,2) NOT NULL default '5.00',
                      package_empty_weight decimal(10,2) NOT NULL default '0.00',
                      package_max_weight decimal(10,2) NOT NULL default '50.00',
                      package_cost decimal(10,2) NOT NULL default '0.00',
                      PRIMARY KEY  (package_id))");
    }

    //****************
    function remove($vendors_id) {
        tep_db_query("delete from " . TABLE_VENDOR_CONFIGURATION . " where vendors_id = '". $vendors_id ."' and configuration_key in ('" . implode("', '", $this->keys($vendors_id)) . "')");
    }

    //*************
    function keys($vendors_id) {
    return array('MODULE_SHIPPING_UPSXML_RATES_STATUS_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_ACCESS_KEY_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_USERNAME_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_PASSWORD_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_PICKUP_METHOD_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_PACKAGE_TYPE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_CUSTOMER_CLASSIFICATION_CODE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_ORIGIN_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_CITY_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_STATEPROV_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_COUNTRY_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_POSTALCODE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_MODE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_QUOTE_TYPE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_USE_NEGOTIATED_RATES_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_UPS_ACCOUNT_NUMBER_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_MANUAL_NEGOTIATED_RATE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_HANDLING_TYPE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_HANDLING_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_INSURE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_CURRENCY_CODE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_TAX_CLASS_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_ZONE_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_SORT_ORDER_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_TYPES_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_EMAIL_ERRORS_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_RATES_TIME_IN_TRANSIT_VIEW_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_WEIGHT1_' . $vendors_id, 'MODULE_SHIPPING_UPSXML_DIMENSIONS_SUPPORTED_' . $vendors_id);
    }

    //***********************
    function _upsProduct($prod, $vendors_id){
        $this->_upsProductCode = $prod;
    }

    //**********************************************
    function _upsOrigin($city, $stateprov, $country, $postal){
        $this->_upsOriginCity = $city;
        $this->_upsOriginStateProv = $stateprov;
        $this->_upsOriginCountryCode = $country;
        $postal = str_replace(' ', '', $postal);
        if ($country == 'US') {
            $this->_upsOriginPostalCode = substr($postal, 0, 5);
        } else {
            $this->_upsOriginPostalCode = $postal;
        }
    }

    //**********************************************
    function _upsDest($city, $stateprov, $country, $postal) {
        $this->_upsDestCity = $city;
        $this->_upsDestStateProv = $stateprov;
        $this->_upsDestCountryCode = $country;
        $postal = str_replace(' ', '', $postal);
        if ($country == 'US') {
            $this->_upsDestPostalCode = substr($postal, 0, 5);
            $territories = array('AS','FM','GU','MH','MP','PR','PW','VI');
            if (in_array($this->_upsDestStateProv,$territories)) {
              $this->_upsDestCountryCode = $stateprov;
              }
        } else if ($country == 'BR') {
            $this->_upsDestPostalCode = substr($postal, 0, 5);
        } else {
            $this->_upsDestPostalCode = $postal;
        }
    }

    //************************
    function _upsAction($action, $vendors_id) {
        // rate - Single Quote; shop - All Available Quotes
        $this->_upsActionCode = $action;
    }

    //********************************************
    // default value of 100 added for insurance (100 shouldn't trigger costs for insurance)
    function _addItem($length, $width, $height, $weight, $price = 0 ) {
        // Add box or item to shipment list. Round weights to 1 decimal places.
        if ((float)$weight < 1.0) {
            $weight = 1;
        } else {
            $weight = round($weight, 1);
        }
        $index = $this->items_qty;
        $this->item_length[$index] = ($length ? (string)$length : '0' );
        $this->item_width[$index] = ($width ? (string)$width : '0' );
        $this->item_height[$index] = ($height ? (string)$height : '0' );
        $this->item_weight[$index] = ($weight ? (string)$weight : '0' );
        $this->item_price[$index] = $price;
        $this->items_qty++;
    }

    //*********************
    function _upsGetQuote($vendors_id='1') {

        // Create the access request
        $accessRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<AccessRequest xml:lang=\"en-US\">\n".
        "   <AccessLicenseNumber>". $this->access_key($vendors_id) ."</AccessLicenseNumber>\n".
        "   <UserId>". $this->access_username($vendors_id) ."</UserId>\n".
        "   <Password>". $this->access_password($vendors_id) ."</Password>\n".
        "</AccessRequest>\n";

        $ratingServiceSelectionRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<RatingServiceSelectionRequest xml:lang=\"en-US\">\n".
        "   <Request>\n".
        "       <TransactionReference>\n".
        "           <CustomerContext>Rating and Service</CustomerContext>\n".
        "           <XpciVersion>". $this->xpci_version ."</XpciVersion>\n".
        "       </TransactionReference>\n".
        "       <RequestAction>Rate</RequestAction>\n".
        "       <RequestOption>shop</RequestOption>\n".
        "   </Request>\n";
        // according to UPS the CustomerClassification and PickupType containers should
        // not be present when the origin country is non-US see:
        // http://forums.oscommerce.com/index.php?s=&showtopic=49382&view=findpost&p=730947
        if ($this->origin_country == 'US') {
        $ratingServiceSelectionRequestHeader .=
        "   <PickupType>\n".
        "       <Code>". $this->pickup_methods[$this->pickup_method($vendors_id)] ."</Code>\n".
        "   </PickupType>\n";
        "   <CustomerClassification>\n".
        "       <Code>". $this->customer_classification($vendors_id) ."</Code>\n".
        "   </CustomerClassification>\n";
        }
        $ratingServiceSelectionRequestHeader .=
        "   <Shipment>\n".
        "       <Shipper>\n";
        if ($this->use_negotiated_rates == 'True') {
        $ratingServiceSelectionRequestHeader .=
        "         <ShipperNumber>" . $this->access_account_number . "</ShipperNumber>\n";
        }
        $ratingServiceSelectionRequestHeader .=
        "           <Address>\n".
        "               <City>". $this->_upsOriginCity ."</City>\n".
        "               <StateProvinceCode>". $this->_upsOriginStateProv ."</StateProvinceCode>\n".
        "               <CountryCode>". $this->_upsOriginCountryCode ."</CountryCode>\n".
        "               <PostalCode>". $this->_upsOriginPostalCode ."</PostalCode>\n".
        "           </Address>\n".
        "       </Shipper>\n".
        "       <ShipTo>\n".
        "           <Address>\n".
        "               <City>". $this->_upsDestCity ."</City>\n".
        "               <StateProvinceCode>". $this->_upsDestStateProv ."</StateProvinceCode>\n".
        "               <CountryCode>". $this->_upsDestCountryCode ."</CountryCode>\n".
        "               <PostalCode>". $this->_upsDestPostalCode ."</PostalCode>\n".
        ($this->quote_type($vendors_id) == "Residential" ? "<ResidentialAddressIndicator/>\n" : "") .
        "           </Address>\n".
        "       </ShipTo>\n";
        for ($i = 0; $i < $this->items_qty; $i++) {
            $ratingServiceSelectionRequestPackageContent .=
            "       <Package>\n".
            "           <PackagingType>\n".
            "               <Code>". $this->package_types[$this->package_type($vendors_id)] ."</Code>\n".
            "           </PackagingType>\n";
            if ($this->dimensions_support > 0 && ($this->item_length[$i] > 0 ) && ($this->item_width[$i] > 0 ) && ($this->item_height[$i] > 0)) {

                $ratingServiceSelectionRequestPackageContent .=
                "           <Dimensions>\n".
                "               <UnitOfMeasurement>\n".
                "                   <Code>". $this->unit_length($vendors_id) ."</Code>\n".
                "               </UnitOfMeasurement>\n".
                "               <Length>". $this->item_length[$i] ."</Length>\n".
                "               <Width>". $this->item_width[$i] ."</Width>\n".
                "               <Height>". $this->item_height[$i] ."</Height>\n".
                "           </Dimensions>\n";
            }

            $ratingServiceSelectionRequestPackageContent .=
            "           <PackageWeight>\n".
            "               <UnitOfMeasurement>\n".
            "                   <Code>". $this->unit_weight($vendors_id) ."</Code>\n".
            "               </UnitOfMeasurement>\n".
            "               <Weight>". $this->item_weight[$i] ."</Weight>\n".
            "           </PackageWeight>\n".
            "           <PackageServiceOptions>\n".
            //"               <COD>\n".
            //"                   <CODFundsCode>0</CODFundsCode>\n".
            //"                   <CODCode>3</CODCode>\n".
            //"                   <CODAmount>\n".
            //"                       <CurrencyCode>USD</CurrencyCode>\n".
            //"                       <MonetaryValue>1000</MonetaryValue>\n".
            //"                   </CODAmount>\n".
            //"               </COD>\n".
            "               <InsuredValue>\n".
            "                   <CurrencyCode>".constant('MODULE_SHIPPING_UPSXML_CURRENCY_CODE_' . $vendors_id)."</CurrencyCode>\n".
            "                   <MonetaryValue>".$this->item_price[$i]."</MonetaryValue>\n".
            "               </InsuredValue>\n".
            "           </PackageServiceOptions>\n".
            "       </Package>\n";
        }

        $ratingServiceSelectionRequestFooter = '';
        //"   <ShipmentServiceOptions/>\n".
           if ($this->use_negotiated_rates == 'True') {
        $ratingServiceSelectionRequestFooter .=
            "       <RateInformation>\n".
            "         <NegotiatedRatesIndicator/>\n".
            "       </RateInformation>\n";
           }
        $ratingServiceSelectionRequestFooter .=
        "   </Shipment>\n";
        // according to UPS the CustomerClassification and PickupType containers should
        // not be present when the origin country is non-US see:
        // http://forums.oscommerce.com/index.php?s=&showtopic=49382&view=findpost&p=730947
        if ($this->origin_country == 'US') {
        $ratingServiceSelectionRequestFooter .=
              "   <CustomerClassification>\n".
              "       <Code>". $this->customer_classification($vendors_id) ."</Code>\n".
              "   </CustomerClassification>\n";
        }
        $ratingServiceSelectionRequestFooter .=
        "</RatingServiceSelectionRequest>\n";

        $xmlRequest = $accessRequestHeader .
        $ratingServiceSelectionRequestHeader .
        $ratingServiceSelectionRequestPackageContent .
        $ratingServiceSelectionRequestFooter;
       //post request $strXML;
        $host = $this->host($vendors_id);
        $xmlResult = $this->_post($this->protocol, $host, $this->port, $this->path, $this->version, $this->timeout, $xmlRequest);

        // BOF testing with a response from UPS saved as a text file
        // needs commenting out the line above: $xmlResult = $this->_post($this->protocol, etcetera
/*        $filename = '/srv/www/htdocs/catalog/includes/modules/shipping/example_response.xml';
        $fp = fopen($filename, "r") or die("couldn't open file");
        $xmlResult = "";
        while (! feof($fp)) {
          $xmlResult .= fgets($fp, 1024);
        } 
        // EOF testing with a text file */
        return $this->_parseResult($xmlResult, $vendors_id);
    }

    //******************************************************************
    function _post($protocol, $host, $port, $path, $version, $timeout, $xmlRequest) {
        $url = $protocol."://".$host.":".$port.$path;
        if ($this->logfile) {
            error_log("------------------------------------------\n", 3, $this->logfile);
            error_log("DATE AND TIME: ".date('Y-m-d H:i:s')."\n", 3, $this->logfile);
            error_log("UPS URL: " . $url . "\n", 3, $this->logfile);
        }
        if (function_exists('exec') && $this->use_exec == '1' ) {
            exec('which curl', $curl_output);
            if ($curl_output) {
                $curl_path = $curl_output[0];
            } else {
                $curl_path = 'curl'; // change this if necessary
            }
            if ($this->logfile) {
                error_log("UPS REQUEST using exec(): " . $xmlRequest . "\n", 3, $this->logfile);
            }
            // add option -k to the statement: $command = "".$curl_path." -k -d \"". etcetera if you get
            // curl error 60: error setting certificate verify locations
            // using addslashes was the only way to avoid UPS returning the 1001 error: The XML document is not well formed
            $command = "".$curl_path." -d \"".addslashes($xmlRequest)."\" ".$url."";
            exec($command, $xmlResponse);
            if ( empty($xmlResponse) && $this->logfile) { // using exec no curl errors can be retrieved
                error_log("Error from cURL using exec() since there is no \$xmlResponse\n", 3, $this->logfile);
            }
            if ($this->logfile) {
                error_log("UPS RESPONSE using exec(): " . $xmlResponse[0] . "\n", 3, $this->logfile);
            }
        } elseif ($this->use_exec == '1') { // if NOT (function_exists('exec') && $this->use_exec == '1'
            if ($this->logfile) {
                error_log("Sorry, exec() cannot be called\n", 3, $this->logfile);
            }
        } else { // default behavior: cURL is assumed to be compiled in PHP
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            // uncomment the next line if you get curl error 60: error setting certificate verify locations
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            // uncommenting the next line is most likely not necessary in case of error 60
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
            curl_setopt($ch, CURLOPT_TIMEOUT, (int)$timeout);

            if ($this->logfile) {
                error_log("UPS REQUEST: " . $xmlRequest . "\n", 3, $this->logfile);
            }
            $xmlResponse = curl_exec ($ch);
            if (curl_errno($ch) && $this->logfile) {
                $error_from_curl = sprintf('Error [%d]: %s', curl_errno($ch), curl_error($ch));
                error_log("Error from cURL: " . $error_from_curl . "\n", 3, $this->logfile);
            }
            // send email if enabled in the admin section
            if (curl_errno($ch) && $this->email_errors) {
                $error_from_curl = sprintf('Error [%d]: %s', curl_errno($ch), curl_error($ch));
                error_log("Error from cURL: " . $error_from_curl . " experienced by customer with id " . $_SESSION['customer_id'] . " on " . date('Y-m-d H:i:s'), 1, STORE_OWNER_EMAIL_ADDRESS);
            }
            // log errors to file ups_error.log when set
            if (curl_errno($ch) && $this->ups_error_file) {
                $error_from_curl = sprintf('Error [%d]: %s', curl_errno($ch), curl_error($ch));
                error_log(date('Y-m-d H:i:s')."\tcURL\t" . $error_from_curl . "\t" . $_SESSION['customer_id']."\n", 3, $this->ups_error_file);    
            }
            if ($this->logfile) {
                error_log("UPS RESPONSE: " . $xmlResponse . "\n", 3, $this->logfile);
            }
            curl_close ($ch);
        }

        if(!$xmlResponse || strstr(strtolower(substr($xmlResponse, 0, 120)), "bad request"))  {
            /* Sometimes the UPS server responds with an HTML message (differing depending on whether the test server
               or the production server is used) but both have in the title tag "Bad request".
               Parsing this response will result in a fatal error:
               Call to a member function on a non-object in /blabla/includes/classes/xmldocument.php on line 57
               It only results in not showing Estimated Delivery Dates to the customer so avoiding the fatal error should do.
            */
            $xmlResponse = "<?xml version=\"1.0\"?>\n".
            "<RatingServiceSelectionResponse>\n".
            "   <Response>\n".
            "       <TransactionReference>\n".
            "           <CustomerContext>Rating and Service</CustomerContext>\n".
            "           <XpciVersion>1.0001</XpciVersion>\n".
            "       </TransactionReference>\n".
            "       <ResponseStatusCode>0</ResponseStatusCode>\n".
            "       <ResponseStatusDescription>". MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_UNKNOWN_ERROR ."</ResponseStatusDescription>\n".
            "   </Response>\n".
            "</RatingServiceSelectionResponse>\n";
            return $xmlResponse;
        }
        if ($this->use_exec == '1') {
            return $xmlResponse[0]; // $xmlResponse is an array in this case
        } else {
            return $xmlResponse;
        }
    }

    //*****************************
    function _parseResult($xmlResult, $vendors_id) {
        // Parse XML message returned by the UPS post server.
        $doc = XML_unserialize ($xmlResult);

        // Get version. Must be xpci version 1.0001 or this might not work.
        $responseVersion = $doc['RatingServiceSelectionResponse']['Response']['TransactionReference']['XpciVersion'];
        if ($this->xpci_version != $responseVersion) {
            $message = MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_VERSION_ERROR;
            return $message;
        }
        // Get response code: 1 = SUCCESS, 0 = FAIL
        $responseStatusCode = $doc['RatingServiceSelectionResponse']['Response']['ResponseStatusCode'];
        if ($responseStatusCode != '1') {
            $errorMsg = $doc['RatingServiceSelectionResponse']['Response']['Error']['ErrorCode'];
            $errorMsg .= ": ";
            $errorMsg .= $doc['RatingServiceSelectionResponse']['Response']['Error']['ErrorDescription'];
            // send email if enabled in the admin section
            if ($this->email_errors) {
                error_log("UPSXML Rates Error: " . $errorMsg . " experienced by customer with id " . $_SESSION['customer_id'] . " on " . date('Y-m-d H:i:s'), 1, STORE_OWNER_EMAIL_ADDRESS);
            }
            // log errors to file ups_error.log when set
            if ($this->ups_error_file) {
                error_log(date('Y-m-d H:i:s')."\tRates\t" . $errorMsg . "\t" . $_SESSION['customer_id']."\n", 3, $this->ups_error_file);    
            }
                return $errorMsg;
        }

        $ratedShipments = $doc['RatingServiceSelectionResponse']['RatedShipment'];

        $aryProducts = false;
        if (isset($doc['RatingServiceSelectionResponse']['RatedShipment'][0])) { // more than 1 rate
          for ($i = 0; $i < count($ratedShipments); $i++) {
            $serviceCode = $ratedShipments[$i]['Service']['Code'];
            if ($this->use_negotiated_rates == 'True' && isset($ratedShipments[$i]['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue'])) {
                $totalCharge = $ratedShipments[$i]['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue'];
              } else {
// either a negotiated rate was not given or the shipper does not get/wants any
                $totalCharge = $ratedShipments[$i]['TotalCharges']['MonetaryValue'];
              }
              if (!($serviceCode && $totalCharge)) {
                continue;
              } 
            $ratedPackages = $ratedShipments[0]['RatedPackage']; // only do this once for the first service given
            if (isset($ratedShipments[0]['RatedPackage'][0])) { // multidimensional array of packages
              $this->boxCount = count($ratedPackages);
            } else {
              $this->boxCount = 1; // if there is only one package count($ratedPackages) returns
              // the number of fields in the array like TransportationCharges and BillingWeight
            }
            $title = '';
            $title = $this->service_codes[$this->origin][$serviceCode];
            $aryProducts[$i] = array($title => $totalCharge);
          } // end for ($i = 0; $i < count($ratedShipments); $i++)
        } elseif (isset($doc['RatingServiceSelectionResponse']['RatedShipment'])) { // only 1 rate
          $serviceCode = $ratedShipments['Service']['Code'];
            if ($this->use_negotiated_rates == 'True' && isset($ratedShipments['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue'])) {
                $totalCharge = $ratedShipments['NegotiatedRates']['NetSummaryCharges']['GrandTotal']['MonetaryValue'];
              } else {
// either a negotiated rate was not given or the shipper does not get/wants any
                $totalCharge = $ratedShipments['TotalCharges']['MonetaryValue'];
              }
              if (!($serviceCode && $totalCharge)) {
                return $aryProducts; // is false
              } 
            $ratedPackages = $ratedShipments['RatedPackage']; // only do this once for the first service given
            if (isset($ratedShipments['RatedPackage'][0])) { // multidimensional array of packages
              $this->boxCount = count($ratedPackages);
            } else {
              $this->boxCount = 1; // if there is only one package count($ratedPackages) returns
              // the number of fields in the array like TransportationCharges and BillingWeight
            }
            $title = '';
            $title = $this->service_codes[$this->origin($vendors_id)][$serviceCode];
            $aryProducts[] = array($title => $totalCharge);
        }
        return $aryProducts;
    }

    // BOF Time In Transit

    //********************
    function _upsGetTimeServices($vendors_id) {
        if (defined('MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY_' . $vendors_id)) {
            $shipdate = date("Ymd", $this->today_unix_time + (86400*constant('MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY_' . $vendors_id)));
            $day_of_the_week = date ("w", $this->today_unix_time + (86400*constant('MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY_' . $vendors_id))) ;
            if ($day_of_the_week == "0" || $day_of_the_week == "7") { // order supposed to leave on Sunday
                $shipdate = date("Ymd", $this->today_unix_time + (86400*constant('MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY_' . $vendors_id)) + 86400);
            } elseif ($day_of_the_week == "6") { // order supposed to leave on Saturday
                $shipdate = date("Ymd", $this->today_unix_time + (86400*constant('MODULE_SHIPPING_UPSXML_SHIPPING_DAYS_DELAY_' . $vendors_id)) + 172800);
            } /**/
        } else {
            $shipdate = $this->today;
        }

        // Create the access request
        $accessRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<AccessRequest xml:lang=\"en-US\">\n".
        "   <AccessLicenseNumber>". $this->access_key($vendors_id) ."</AccessLicenseNumber>\n".
        "   <UserId>". $this->access_username($vendors_id) ."</UserId>\n".
        "   <Password>". $this->access_password($vendors_id) ."</Password>\n".
        "</AccessRequest>\n";

        $timeintransitSelectionRequestHeader =
        "<?xml version=\"1.0\"?>\n".
        "<TimeInTransitRequest xml:lang=\"en-US\">\n".
        "   <Request>\n".
        "       <TransactionReference>\n".
        "           <CustomerContext>Time in Transit</CustomerContext>\n".
        "           <XpciVersion>". $this->transitxpci_version ."</XpciVersion>\n".
        "       </TransactionReference>\n".
        "       <RequestAction>TimeInTransit</RequestAction>\n".
        "   </Request>\n".
        "   <TransitFrom>\n".
        "       <AddressArtifactFormat>\n".
        "           <PoliticalDivision2>". $this->origin_city($vendors_id) ."</PoliticalDivision2>\n".
        "           <PoliticalDivision1>". $this->origin_stateprov($vendors_id) ."</PoliticalDivision1>\n".
        "           <CountryCode>". $this->_upsOriginCountryCode ."</CountryCode>\n".
        "           <PostcodePrimaryLow>". $this->origin_postalcode($vendors_id) ."</PostcodePrimaryLow>\n".
        "       </AddressArtifactFormat>\n".
        "   </TransitFrom>\n".
        "   <TransitTo>\n".
        "       <AddressArtifactFormat>\n".
        "           <PoliticalDivision2>". $this->_upsDestCity ."</PoliticalDivision2>\n".
        "           <PoliticalDivision1>". $this->_upsDestStateProv ."</PoliticalDivision1>\n".
        "           <CountryCode>". $this->_upsDestCountryCode ."</CountryCode>\n".
        "           <PostcodePrimaryLow>". $this->_upsDestPostalCode ."</PostcodePrimaryLow>\n".
        "           <PostcodePrimaryHigh>". $this->_upsDestPostalCode ."</PostcodePrimaryHigh>\n".
        "       </AddressArtifactFormat>\n".
        "   </TransitTo>\n".
        "   <ShipmentWeight>\n".
        "       <UnitOfMeasurement>\n".
        "           <Code>" . $this->unit_weight($vendors_id) . "</Code>\n".
        "       </UnitOfMeasurement>\n".
        "       <Weight>" . $this->weight_for_timeintransit . "</Weight>\n".
        "   </ShipmentWeight>\n".
        "   <InvoiceLineTotal>\n".
        "       <CurrencyCode>" . defined('MODULE_SHIPPING_UPSXML_CURRENCY_CODE_' . $vendors_id) . "</CurrencyCode>\n".
        "       <MonetaryValue>" . $this->pkgvalue . "</MonetaryValue>\n".
        "   </InvoiceLineTotal>\n".
        "   <PickupDate>" . $shipdate . "</PickupDate>\n".
        "</TimeInTransitRequest>\n";

        $xmlTransitRequest = $accessRequestHeader .
        $timeintransitSelectionRequestHeader;

        //post request $strXML;
        $xmlTransitResult = $this->_post($this->protocol, $this->host($vendors_id), $this->port, $this->transitpath, $this->transitversion, $this->timeout, $xmlTransitRequest);
        return $this->_transitparseResult($xmlTransitResult);
    }

    //***************************************
    
    // GM 11-15-2004: modified to return array with time for each service, as
    //                opposed to single transit time for hardcoded "GND" code

    function _transitparseResult($xmlTransitResult) {
         $transitTime = array();

        // Parse XML message returned by the UPS post server.
        $doc = XML_unserialize ($xmlTransitResult);
        // Get version. Must be xpci version 1.0001 or this might not work.
        // 1.0001 and 1.0002 seem to be very similar, forget about this for the moment
        /*        $responseVersion = $doc['TimeInTransitResponse']['Response']['TransactionReference']['XpciVersion'];
        if ($this->transitxpci_version != $responseVersion) {
            $message = MODULE_SHIPPING_UPSXML_RATES_TEXT_COMM_VERSION_ERROR;
            return $message;
        } */
        // Get response code. 1 = SUCCESS, 0 = FAIL
        $responseStatusCode = $doc['TimeInTransitResponse']['Response']['ResponseStatusCode'];
        if ($responseStatusCode != '1') {
            $errorMsg = $doc['TimeInTransitResponse']['Response']['Error']['ErrorCode'];
            $errorMsg .= ": ";
            $errorMsg .= $doc['TimeInTransitResponse']['Response']['Error']['ErrorDescription'];
            // send email if enabled in the admin section
            if ($this->email_errors) {
                error_log("UPSXML TimeInTransit Error: " . $errorMsg . " experienced by customer with id " . $_SESSION['customer_id'] . " on " . date('Y-m-d H:i:s'), 1, STORE_OWNER_EMAIL_ADDRESS);
            }
            // log errors to file ups_error.log when set
            if ($this->ups_error_file) {
                error_log(date('Y-m-d H:i:s')."\tTimeInTransit\t" . $errorMsg . "\t" . $_SESSION['customer_id'] ."\n", 3, $this->ups_error_file);    
            }
           //  return $errorMsg;
           return false;
        }

        if (isset($doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary'][0])) { // more than one EDD
               foreach ($doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary'] as $key_index => $service_array) {
                    // index by description because that's all we can relate back to the service 
                    // with (though it can probably return the code as well but they are very
                    // different from those used by the Rates Service and there is a lot of 
                    // duplication so pretty useless)
                    $serviceDesc = $service_array['Service']['Description'];
                    // hack to get EDD for UPS Saver recognized (Time in Transit uses UPS Worldwide Saver
                    // but the service in Rates and Services is called UPS Saver)
                    if ($serviceDesc == "UPS Worldwide Saver") {
                      $serviceDesc = "UPS Saver";
                    }
                    // only date is used so why bother with days and guaranteed?
                    // $transitTime[$serviceDesc]["days"] = $serviceSummary[$s]->getValueByPath("EstimatedArrival/BusinessTransitDays");
                    $transitTime[$serviceDesc]['date'] = $service_array['EstimatedArrival']['Date'];
                    // $transitTime[$serviceDesc]["guaranteed"] = $serviceSummary[$s]->getValueByPath("Guaranteed/Code");
                } // end foreach ($doc['TimeInTransitResponse']['ServiceSummary'] etc.
        } elseif (isset($doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary'])) { // only one EDD
          $serviceDesc = $doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary']['Service']['Description'];
          $transitTime[$serviceDesc]['date'] = $doc['TimeInTransitResponse']['TransitResponse']['ServiceSummary']['EstimatedArrival']['Date'];
        } else {
          $errorMsg = MODULE_SHIPPING_UPSXML_TIME_IN_TRANSIT_TEXT_NO_RATES;
            if ($this->ups_error_file) {
                error_log(date('Y-m-d H:i:s')."\tTimeInTransit\t" . $errorMsg . "\t" . $_SESSION['customer_id'] ."\n", 3, $this->ups_error_file);    
            }
          return false;
        }
        if ($this->logfile) {
            error_log("------------------------------------------\n", 3, $this->logfile);
            foreach($transitTime as $desc => $time) {
                error_log("Business Transit: " . $desc ." = ". $time["date"] . "\n", 3, $this->logfile);
            }
        }
        return $transitTime;
    }

    //EOF Time In Transit
 //  ***************************
  function exclude_choices($type, $vendors_id) {
    // Used for exclusion of UPS shipping options, disallowed types are read from db (stored as 
    // short defines). The short defines are not used as such, to avoid collisions
    // with other shipping modules, they are prefixed with UPSXML_
    // These defines are found in the upsxml language file (UPSXML_US_01, UPSXML_CAN_14 etc.)
    $disallowed_types = explode(",", defined('MODULE_SHIPPING_UPSXML_TYPES_' . $vendors_id));
    if (strstr($type, "UPS")) {
        // this will chop off "UPS" from the beginning of the line - typically something like UPS Next Day Air (1 Business Days)
        $type_minus_ups = explode("UPS", $type );
        $type_root = trim($type_minus_ups[1]);
    } // end if (strstr($type, "UPS"):
    else { // service description does not contain UPS (unlikely)
        $type_root = trim($type);
    }
    for ($za = 0; $za < count ($disallowed_types); $za++ ) {
      // when no disallowed types are present, --none-- is in the db but causes an error because --none-- is
      // not added as a define
      if ($disallowed_types[$za] == '--none--' ) continue; 
        if ($type_root == @constant('UPSXML_' . trim($disallowed_types[$za]))) {
            return true;
        } // end if ($type_root == constant(trim($disallowed_types[$za]))).
    }
    // if the type is not disallowed:
    return false;
  }
// Next function used for sorting the shipping quotes on rate: low to high is default.
  function rate_sort_func ($a, $b) {
    
   $av = array_values($a);
   $av = $av[0];
   $bv = array_values($b);
   $bv = $bv[0];

//  return ($av == $bv) ? 0 : (($av < $bv) ? 1 : -1); // for having the high rates first
  return ($av == $bv) ? 0 : (($av > $bv) ? 1 : -1); // low rates first
  
  }
} // end class upsxml
// Next two functions are used only in the admin for disallowed shipping options.
// The (short) constants like US_12, CAN_14 are stored in the database
// to stay below 255 characters. The defines themselves are found in the upsxml
// language file prefixed with UPSXML_ to avoid collisions with other shipping modules.
// They can be moved to admin/includes/function/general.php if you like but don't forget
// to remove them from this file in future updates or you will get an error in the admin  

  function tep_cfg_select_multioption_vendor($select_array, $key_value, $key = '') {
    $string = '';
    for ($i=0; $i<sizeof($select_array); $i++) {
      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
      $key_values = explode( ", ", $key_value);
      if ( in_array($select_array[$i], $key_values) ) $string .= 'CHECKED';
      $string .= '> ' . $select_array[$i];
      $string .= '<input type="hidden" name="' . $name . '" value="--none--">';
    }
    return $string;
  }

?>