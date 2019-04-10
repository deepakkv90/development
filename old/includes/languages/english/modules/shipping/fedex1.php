<?php
/*

  Verions 2.00 for OSC 2.2 MSS and earlier

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002, 2003 Steve Fatula of Fatula Consulting
  compconsultant@yahoo.com

  Released under the GNU General Public License
*/

define('MODULE_SHIPPING_FEDEX1_TEXT_TITLE', 'FedEx');
define('MODULE_SHIPPING_FEDEX1_TEXT_DESCRIPTION', 'FedEx<br><br>You will need to have registered an account with FEDEX to use this module. Please see the README.TXT file for other requirements.');

define('MODULE_SHIPPING_FEDEX1_DOMESTIC_TYPES_01', 'Priority (by 10:30AM, later for rural)');
define('MODULE_SHIPPING_FEDEX1_DOMESTIC_TYPES_03', '2 Day Air');
define('MODULE_SHIPPING_FEDEX1_DOMESTIC_TYPES_05', 'Standard Overnight (by 3PM, later for rural)');
define('MODULE_SHIPPING_FEDEX1_DOMESTIC_TYPES_06', 'First Overnight');
define('MODULE_SHIPPING_FEDEX1_DOMESTIC_TYPES_20', 'Express Saver (3 Day)');
define('MODULE_SHIPPING_FEDEX1_DOMESTIC_TYPES_90', 'Home Delivery');
define('MODULE_SHIPPING_FEDEX1_DOMESTIC_TYPES_92', 'Ground Service');

define('MODULE_SHIPPING_FEDEX1_INTERNATIONAL_TYPES_01', 'International Priority (1-3 Days)');
define('MODULE_SHIPPING_FEDEX1_INTERNATIONAL_TYPES_03', 'International Economy (4-5 Days)');
define('MODULE_SHIPPING_FEDEX1_INTERNATIONAL_TYPES_06', 'International First');
define('MODULE_SHIPPING_FEDEX1_INTERNATIONAL_TYPES_90', 'Home Delivery');
define('MODULE_SHIPPING_FEDEX1_INTERNATIONAL_TYPES_92', 'Ground Service');

define('MODULE_SHIPPING_FEDEX1_ERROR_1', 'An error occured with the fedex shipping calculations.<br>Fedex may not deliver to your country, or your postal code may be wrong.');

define('MODULE_SHIPPING_FEDEX1_MESSAGE_1', "Data sent to Fedex for Meter: ");
define('MODULE_SHIPPING_FEDEX1_MESSAGE_2', "Data returned from Fedex for Meter: ");
define('MODULE_SHIPPING_FEDEX1_MESSAGE_3', 'No response to CURL from Fedex server, check CURL availability, or maybe timeout was set too low, or maybe the Fedex site is down');
define('MODULE_SHIPPING_FEDEX1_MESSAGE_4', 'No meter number was obtained, check configuration. Error ');

define('MODULE_SHIPPING_FEDEX1_MESSAGE_5', 'You forgot to set up your Fedex account number, this can be set up in Admin -> Modules -> Shipping');
define('MODULE_SHIPPING_FEDEX1_MESSAGE_6', 'You forgot to set up your ship from street address line 1, this can be set up in Admin -> Modules -> Shipping');
define('MODULE_SHIPPING_FEDEX1_MESSAGE_7', 'You forgot to set up your ship from City, this can be set up in Admin -> Modules -> Shipping');
define('MODULE_SHIPPING_FEDEX1_MESSAGE_8', 'You forgot to set up your ship from postal code, this can be set up in Admin -> Modules -> Shipping');
define('MODULE_SHIPPING_FEDEX1_MESSAGE_9', 'You forgot to set up your ship from phone number, this can be set up in Admin -> Modules -> Shipping');

define('MODULE_SHIPPING_FEDEX1_MESSAGE_10', "Data sent to Fedex for Rating: ");
define('MODULE_SHIPPING_FEDEX1_MESSAGE_11', "Data returned from Fedex for Rating: ");
define('MODULE_SHIPPING_FEDEX1_MESSAGE_12', 'No data returned from Fedex, perhaps the Fedex site is down');
define('MODULE_SHIPPING_FEDEX1_MESSAGE_13', 'No Rates Returned, ');
?>
