<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  Portions  Copyright (c) 2007 Chain Reaction Works

  Released under the GNU General Public License
*/
//// Added for gzip compression
// code change and moved further down in the file to use configuration settins
// End Gzip compression

// Start the clock for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// Include application configuration parameters
  require('includes/configure.php');
  define('DIR_FS_INCLUDES', DIR_FS_ADMIN . DIR_WS_INCLUDES);
  define('DIR_FS_FUNCTIONS', DIR_FS_ADMIN . DIR_WS_FUNCTIONS);
  define('DIR_FS_CLASSES', DIR_FS_ADMIN . DIR_WS_CLASSES);
  define('DIR_FS_EXTENSIONS', DIR_FS_CATALOG . 'ext/');

// define the project version
include('includes/version.php');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

  if ($request_type == 'NONSSL') {
    define('DIR_WS_ADMIN', DIR_WS_HTTP_ADMIN);
    define('BASE_HREF', HTTP_SERVER . DIR_WS_HTTP_ADMIN);
  } else {
    define('DIR_WS_ADMIN', DIR_WS_HTTPS_ADMIN);
    define('BASE_HREF', HTTPS_SERVER . DIR_WS_HTTPS_ADMIN);
  }
  
  // this is patch up code to support sites with configuration file built
  // before 6.2.08.  The only define was for the DIR_WS_CATALOG
  // the instal routines were updated to provide additional references
  // Values are forced to prevent problems, however this will still be a 
  // problem for sites using shared ssl certificates htat havea different 
  // file names
  if ( !defined('DIR_WS_HTTPS_CATALOG') || !defined('DIR_WS_HTTP_CATALOG') ) {
    define('DIR_WS_HTTPS_CATALOG', DIR_WS_CATALOG);
    define('DIR_WS_HTTP_CATALOG', DIR_WS_CATALOG);
  }

// set php_self in the local scope
  $PHP_SELF = $_SERVER['SCRIPT_NAME'];

  if (file_exists(DIR_FS_INCLUDES . 'application_top_admin_cre_setting.php')){
  include(DIR_FS_INCLUDES . 'application_top_admin_cre_setting.php');
 }

// include the list of project filenames
  require(DIR_FS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_FS_INCLUDES . 'database_tables.php');

// Define how do we update currency exchange rates
// Possible values are 'oanda' 'xe' or ''
  define('CURRENCY_SERVER_PRIMARY', 'oanda');
  define('CURRENCY_SERVER_BACKUP', 'xe');

// include select functions
  require(DIR_FS_FUNCTIONS . 'database.php');
  require(DIR_FS_CLASSES . 'logger.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    if (!defined($configuration['cfgKey'])) { 
      define($configuration['cfgKey'], $configuration['cfgValue']);
    }
  }

// Set the Time Zone
if ( ! defined(STORE_TIME_ZONE)) define('STORE_TIME_ZONE', 'America/New_York');
date_default_timezone_set(STORE_TIME_ZONE);
$timezone = new DateTimeZone(STORE_TIME_ZONE);
$dateTime = new DateTime("now", $timezone);
$timeOffset = $timezone->getOffset($dateTime);
if ($timeOffset != 0) {
  $hours = floor($timeOffset / 3600);
  $mins = abs($timeOffset) - abs($hours * 3600);
  tep_db_query("SET SESSION time_zone = '" . sprintf('%+02d:%02d', $hours, $mins) . "'");
  unset($hours, $mins);
}

//this file added so configuration settings can be added
if (file_exists('includes/application_top_cre_admin_setting.php')) {
include('includes/application_top_cre_admin_setting.php');
}

if (MENU_DHTML != 'True') {
 define('BOX_WIDTH', 170);
 } else {
 define('BOX_WIDTH', 0);
 }

// if gzip_compression is enabled, start to buffer the output
  if ( (ADMIN_GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      ob_start('ob_gzhandler');
    } else {
      ini_set('zlib.output_compression_level', ADMIN_GZIP_LEVEL);
    }
  }

//Admin begin
// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);
 require(DIR_FS_FUNCTIONS . 'password_funcs.php');
//Admin end


  require(DIR_FS_FUNCTIONS . 'general.php');
  require(DIR_FS_FUNCTIONS . 'html_output.php');
// set up the PHP and error message log
  define('ERROR_MESSAGE_LOG', DIR_FS_CATALOG . 'debug/php_error_log.txt');
  set_error_handler('_exception_handler', E_ALL & ~E_NOTICE);

// include shopping cart class
  require(DIR_FS_CLASSES . 'shopping_cart.php');
  
  $cart = new shoppingCart();
// require price formatter class
require(DIR_FS_CLASSES . 'PriceFormatter.php');
$pf = new PriceFormatter;

// some code to solve compatibility issues
  require(DIR_FS_FUNCTIONS . 'compatibility.php');

// define how the session functions will be used
  require(DIR_FS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCAdminID');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
   session_set_cookie_params(0, $cookie_path, $cookie_domain);
  
// lets start our session
  tep_session_start();
  $session_started = true;
// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == 'true') && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if ( ! isset($_SESSION['SSL_SESSION_ID']) ) {
      $_SESSION['SESSION_SSL_ID'] = $ssl_session_id;
    }

    if ($_SESSION['SESSION_SSL_ID'] != $ssl_session_id) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
  }
  
// instantiate the RCI class
  require(DIR_FS_CLASSES . 'rci.php');
  $cre_RCI = new cre_RCI;
  
// instantiate the RCO class
  require(DIR_FS_CLASSES . 'rco.php');
  $cre_RCO = new cre_RCO;   

  // the language variable is used in so many locations and 
  // is not set very often, special handling is being applied to 
  // reduce the amount of code changes needed with registered globals turned off
  
  if ( ! isset($_SESSION['language']) || isset($_GET['language']) ) {
    include(DIR_FS_CLASSES . 'language.php');
    $lng = new language();
    if ( isset($_GET['language']) && tep_not_null($_GET['language']) ) {
      $lng->set_language($_GET['language']);
    } /*else {
      $lng->get_browser_language();
    }*/
    $_SESSION['language'] = $lng->language['directory'];
    $_SESSION['languages_id'] = $lng->language['id'];
  }
  
  $language = $_SESSION['language'];
  $languages_id = $_SESSION['languages_id'];

// include the language translations
  require(DIR_WS_LANGUAGES . $language . '.php');
  $current_page = basename($PHP_SELF);
  if (file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page)) {
    include_once(DIR_WS_LANGUAGES . $language . '/' . $current_page);
  }

// include RCI language extensions
  $cre_RCI->get($language, 'lang', false);

  // navigation history
  // the class will reload any information that was stored in the session
  // include navigation history class
  require(DIR_FS_CLASSES . 'navigation_history.php');

  $navigation = new navigationHistory();
  $navigation->add_current_page();

// define our localization functions
  require(DIR_FS_FUNCTIONS . 'localization.php');

// Include validation functions (right now only email address)
  require(DIR_FS_FUNCTIONS . 'validations.php');
  setFlags();
  if (isset($_SESSION['continue']) && $_SESSION['continue'] == true) {
  } else {
    if ((basename($PHP_SELF) != 'sss_register.php') && 
        (basename($PHP_SELF) != 'sss_validate.php') &&
        (basename($PHP_SELF) != 'login.php')
       ){
      if (isset($_SESSION['force_registration']) && $_SESSION['force_registration'] == true) {
        tep_redirect(FILENAME_SSS_VALIDATE, '', 'SSL');        
      }
    }
  }  
// setup our boxes
  require(DIR_FS_CLASSES . 'table_block.php');
  require(DIR_FS_CLASSES . 'box.php');

// initialize the message stack for output messages
  require(DIR_FS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;
// set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'true');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
  define('WARN_SESSION_AUTO_START', 'true');
  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');

// split-page-results
  require(DIR_FS_CLASSES . 'split_page_results.php');

// entry/item info classes
  require(DIR_FS_CLASSES . 'object_info.php');

// email classes
  require(DIR_FS_CLASSES . 'mime.php');
  require(DIR_FS_CLASSES . 'email.php');

// file uploading class
  require(DIR_FS_CLASSES . 'upload.php');

// calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $cPath_array = array();
    $current_category_id = 0;
  }

// default open navigation box
  if ( ! isset($_SESSION['selected_box']) ) {
    $_SESSION['selected_box'] = '';
  }
 
  if (isset($_GET['selected_box'])) {
    $_SESSION['selected_box'] = $_GET['selected_box'];
  }

  //Cache control system 
  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_CATEGORIES1, 'code' => 'categories1', 'file' => 'categories1_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_CATEGORIES2, 'code' => 'categories2', 'file' => 'categories2_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_CATEGORIES3, 'code' => 'categories3', 'file' => 'categories3_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_CATEGORIES4, 'code' => 'categories4', 'file' => 'categories4_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_CATEGORIES5, 'code' => 'categories5', 'file' => 'categories5_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
                        array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true));


//Admin begin
  if (basename($PHP_SELF) != FILENAME_LOGIN && basename($PHP_SELF) != FILENAME_PASSWORD_FORGOTTEN && basename($PHP_SELF) != FILENAME_MERCHANT_ACCOUNT) {
    tep_admin_check_login();
  }
// Include OSC-AFFILIATE
 require('includes/affiliate_application_top.php');
// include giftvoucher
 require(DIR_FS_INCLUDES . 'add_ccgvdc_application_top.php');
// WebMakers.com Added: Includes Functions for Attribute Sorter and Copier
require(DIR_FS_FUNCTIONS . 'attributes_sorter_added_functions.php');
// include the articles functions
require(DIR_FS_FUNCTIONS . 'articles.php');
// Article Manager
if (isset($_GET['tPath'])) {
  $tPath = $_GET['tPath'];
} else {
  $tPath = '';
}
if (tep_not_null($tPath)) {
  $tPath_array = tep_parse_topic_path($tPath);
  $tPath = implode('_', $tPath_array);
  $current_topic_id = $tPath_array[(sizeof($tPath_array)-1)];
} else {
  $current_topic_id = 0;
}
if (file_exists('includes/application_top_newsdesk.php')) { include('includes/application_top_newsdesk.php'); }
if (file_exists('includes/application_top_faqdesk.php')) { include('includes/application_top_faqdesk.php'); }
// multi-vendor shipping
// set the vendor shipping constants
$vendor_configuration_query = tep_db_query('SELECT configuration_key as cfgKey, configuration_value as cfgValue 
                                              from ' . TABLE_VENDOR_CONFIGURATION);
while ($vendor_configuration = tep_db_fetch_array($vendor_configuration_query)) {
  define($vendor_configuration['cfgKey'], $vendor_configuration['cfgValue']);
}
//RCI bottom
echo $cre_RCI->get('applicationtop', 'bottom', false);
?>