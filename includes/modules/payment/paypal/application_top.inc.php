<?php
/*
  $Id: application_top.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
// set the level of error reporting
    //error_reporting(E_ALL & ~E_NOTICE);
    error_reporting(0);

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// include server parameters
  require('includes/configure.php');

// define the project version
  define('PROJECT_VERSION', 'osCommerce 2.2-MS2');
  define('IPN_PAYMENT_MODULE_NAME', 'PayPal_Shopping_Cart_IPN');

// set php_self in the local scope
  if (!isset($PHP_SELF)) $PHP_SELF = $_SERVER['PHP_SELF'];

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');
  require(DIR_WS_INCLUDES . 'modules/payment/paypal/database_tables.inc.php');

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set the application parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

  include(DIR_WS_MODULES . 'payment/paypal/classes/osC/Order.class.php');
  $PayPal_osC_Order = new PayPal_osC_Order();
  $PayPal_osC_Order->loadTransactionSessionInfo($_POST['custom']);

  if(isset($PayPal_osC_Order->language)) {
    // include the language translations
    $language = $PayPal_osC_Order->language;
    include(DIR_WS_LANGUAGES . $PayPal_osC_Order->language . '.php');
  } else {
    //later on change to Store Default
    include(DIR_WS_INCLUDES . 'modules/payment/paypal/languages/english.php');
  }
?>
