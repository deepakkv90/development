<?php
/*
  $Id: application_top.php,v 1.2.0.0 2008/06/29 23:38:03 ccwjr Exp $

  CRE Loaded, Open Source E-Commerce Solutions

  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
// start the timer for the page parse time log

//define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting

//error_reporting(E_ALL);

//ini_set('display_errors', true);

//error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(-1);

// Set the local configuration parameters - mainly for developers
  define('PAGE_PARSE_START_TIME', microtime());
// set the level of error reporting
if (defined('E_DEPRECATED')) {
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
} else {
  error_reporting(E_ALL & ~E_NOTICE);
}

if (file_exists('includes/local/configure.php')) include('includes/local/configure.php');

// include server parameters

if (file_exists('includes/configure.php')) require('includes/configure.php');

//check to see if the configuration is valid

if (!defined('HTTP_SERVER') || !defined('DIR_WS_INCLUDES') || !defined('DB_SERVER')) {

  if (file_exists('install/index.php')) {

    header('Location: ' . 'install/index.php');

    exit();

  } 

  else if (file_exists('upgrade/index.php')) {
    header('Location: ' . 'upgrade/index.php');
    exit();

  }else {

    echo 'configure.php is missing or corrupt. Please correct.';

    exit();

  }

}





// create additional constants for file system level access

define('DIR_FS_INCLUDES', DIR_FS_CATALOG . DIR_WS_INCLUDES);

define('DIR_FS_FUNCTIONS', DIR_FS_CATALOG . DIR_WS_FUNCTIONS);

define('DIR_FS_CLASSES', DIR_FS_CATALOG . DIR_WS_CLASSES);

define('DIR_FS_MODULES', DIR_FS_CATALOG . DIR_WS_MODULES);

define('DIR_FS_TEMPLATES', DIR_FS_CATALOG . DIR_WS_TEMPLATES);

define('DIR_FS_EXTENSIONS', DIR_FS_CATALOG . 'ext/');



// set the type of request (secure or not)

//$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';   // This one is old and replaced with below $_SERVER["HTTPS"]
$request_type = (isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on") ? 'SSL' : 'NONSSL';

if ($request_type == 'NONSSL') {

  define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);

} else {

  define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);

}

// set php_self in the local scope

$PHP_SELF = $_SERVER['SCRIPT_NAME'];

// define the project version

include('includes/version.php');

// include the list of project database tables and functions

// define functions and classes needed early in the processing used application-wide

require(DIR_FS_INCLUDES . 'database_tables.php');

require(DIR_FS_INCLUDES . 'filenames.php');

require(DIR_FS_CLASSES . 'mime.php');

require(DIR_FS_CLASSES . 'email.php');

require_once(DIR_FS_CLASSES . 'class.phpmailer.php');
require(DIR_FS_FUNCTIONS . 'general.php');

require(DIR_FS_FUNCTIONS . 'html_output.php');

require(DIR_FS_FUNCTIONS . 'sessions.php');

require(DIR_FS_FUNCTIONS . 'compatibility.php');

//require(DIR_FS_FUNCTIONS . 'database.php');

if(function_exists('mysqli_connect'))
  require(DIR_FS_FUNCTIONS . 'database_mysqli.php');
else
  require(DIR_FS_FUNCTIONS . 'database.php');
// set up the PHP and error message log

//define('ERROR_MESSAGE_LOG', DIR_FS_CATALOG . 'debug/php_error_log.txt');

//set_error_handler('_exception_handler', E_ALL & ~E_NOTICE);

define('ERROR_MESSAGE_LOG', DIR_FS_CATALOG . 'debug/php_error_log.txt');
if (defined('E_DEPRECATED')) {
  set_error_handler('_exception_handler', E_ALL & ~E_NOTICE & ~E_DEPRECATED);
} else {
  set_error_handler('_exception_handler', E_ALL & ~E_NOTICE);
}

// make a connection to the database... now

tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters

$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);

while ($configuration = tep_db_fetch_array($configuration_query)) {

  define($configuration['cfgKey'], $configuration['cfgValue']);

}

//MVS Start

// set the vendor shipping constants

  $vendor_configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_VENDOR_CONFIGURATION);

  while ($vendor_configuration = tep_db_fetch_array($vendor_configuration_query)) {

    define($vendor_configuration['cfgKey'], $vendor_configuration['cfgValue']);

  }

//MVS End



// Set the Time Zone

if ( ! defined('STORE_TIME_ZONE')) define('STORE_TIME_ZONE', 'America/New_York');

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



// if gzip_compression is enabled, start to buffer the output

if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) ) {

  if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {

    ob_start('ob_gzhandler');

  } else {

    ini_set('zlib.output_compression_level', GZIP_LEVEL);

  }

}


// include CRE SEO URLs

if (file_exists(DIR_FS_CATALOG . 'seo.php') && (CRE_SEO == 'true')) {

  require_once('seo.php');


}



include('includes/application_top_cre_setting.php');

// set the cookie domain

$cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);

$cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// include cache functions if enabled

if (USE_CACHE == 'true') include(DIR_FS_FUNCTIONS . 'cache.php');

// set the session name and save path

tep_session_name('osCsid');

tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters

session_set_cookie_params(0, $cookie_path, $cookie_domain);

$session_started = false;

// Check for spider may be required

if (SESSION_BLOCK_SPIDERS == 'True') {

  $user_agent = strtolower(getenv('HTTP_USER_AGENT'));

  $spider_flag = false;

  if ( tep_not_null($user_agent) ) {

    $spiders = file(DIR_FS_INCLUDES . 'spiders.txt');

    for ($i=0, $n=sizeof($spiders); $i<$n; ++$i) {

      if ( tep_not_null($spiders[$i]) ) {

        if ( is_integer( strpos($user_agent, trim($spiders[$i])) ) ) {

          $spider_flag = true;

          // no need to create a session

          break;

        }

      }

    }

  }

  if ($spider_flag == false) {

    tep_session_start();

    $session_started = true;

  }

// At this point, all checks are complete, so start a session

} else {

  tep_session_start();

  $session_started = true;

}

// set SID once, even if empty

$SID = (defined('SID') ? SID : '');

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

// verify the browser user agent if the feature is enabled

if (SESSION_CHECK_USER_AGENT == 'True') {

  $http_user_agent = getenv('HTTP_USER_AGENT');

  if ( ! isset($_SESSION['SESSION_USER_AGENT']) ) {

    $_SESSION['SESSION_USER_AGENT'] = $http_user_agent;

  }

  if ($_SESSION['SESSION_USER_AGENT'] != $http_user_agent) {

    tep_session_destroy();

    tep_redirect(tep_href_link(FILENAME_LOGIN));

  }

}

// verify the IP address if the feature is enabled

if (SESSION_CHECK_IP_ADDRESS == 'True') {

  $ip_address = tep_get_ip_address();

  if ( ! isset($_SESSION['SESSION_IP_ADDRESS']) ) {

    $_SESSION['SESSION_IP_ADDRESS'] = $ip_address;

  }

  if ($_SESSION['SESSION_IP_ADDRESS'] != $ip_address) {

    tep_session_destroy();

    tep_redirect(tep_href_link(FILENAME_LOGIN));

  }

}

// define functions and classes needed for the shopping cart processing

require(DIR_FS_CLASSES . 'shopping_cart.php');

require(DIR_FS_CLASSES . 'navigation_history.php');

// instantiate the RCI class

require(DIR_FS_CLASSES . 'rci.php');

$cre_RCI = new cre_RCI;

// instantiate the RCO class

require(DIR_FS_CLASSES . 'rco.php');

$cre_RCO = new cre_RCO; 

// the class will reload any information that was stored in the session

$cart = new shoppingCart();

// include currencies class and create an instance

require(DIR_FS_CLASSES . 'currencies.php');

$currencies = new currencies();

// require price formatter class

require(DIR_FS_CLASSES . 'PriceFormatter.php');

$pf = new PriceFormatter;



// set up and config HTMLPurfier

require_once DIR_FS_EXTENSIONS . 'HTMLPurifier/HTMLPurifier.auto.php';    

$purifier_config = HTMLPurifier_Config::createDefault();

$purifier_config->set('Core', 'Encoding', 'ISO-8859-1'); // replace with your encoding

$purifier_config->set('HTML', 'Doctype', 'HTML 4.01 Transitional'); // replace with your doctype

$purifier_config->set('Cache', 'SerializerPath', DIR_FS_CATALOG . 'cache');

$purifier_config->set('HTML', 'Allowed', 'xyz');  // force all HTML tags to be rejected

$purifier = new HTMLPurifier($purifier_config);



// ensure that the followin variables are loaded from the session information

if ( isset($_SESSION['customer_id']) ) {

  $customer_id = (int)$_SESSION['customer_id'];

} else {

  $customer_id = 0; 

}

// the language variable is used in so many locations and 

// is not set very often, special handling is being applied to 

// reduce the amount of code changes needed with registered globals turned off

if ( ! isset($_SESSION['language']) || isset($_GET['language']) ) {

  include(DIR_FS_CLASSES . 'language.php');

  $lng = new language();

  if ( isset($_GET['language']) && tep_not_null($_GET['language']) ) {

    $lng->set_language($_GET['language']);

  } else {

    $lng->get_browser_language();

  }

  $_SESSION['language'] = $lng->language['directory'];

  $_SESSION['languages_id'] = $lng->language['id'];

}

$language = $_SESSION['language'];

$languages_id = $_SESSION['languages_id'];

// include the language translations

//require(DIR_WS_LANGUAGES . $language . '.php');
require(DIR_WS_LANGUAGES . english . '.php');
// include RCI language extensions

$cre_RCI->get($language, 'lang', false);

// the currency variable is also used in so many locations and 

// is not set very often, special handling is being applied to 

// reduce the amount of code changes needed with registered globals turned off

if ( ! isset($_SESSION['currency'])  || isset($_GET['currency'])

    || (isset($_SESSION['currency']) && USE_DEFAULT_LANGUAGE_CURRENCY == 'true' && LANGUAGE_CURRENCY != $currency) ) {

  if (isset($_GET['currency'])) {

    if ( ! $_SESSION['currency'] = tep_currency_exists($_GET['currency'])) $_SESSION['currency'] = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;

  } else {

    $_SESSION['currency'] = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true' && tep_currency_exists(LANGUAGE_CURRENCY)) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;

  }

}

$currency = $_SESSION['currency'];

// navigation history

// the class will reload any information that was stored in the session

$navigation = new navigationHistory();

$navigation->add_current_page();

// down for maintenance except for admin ip

if (EXCLUDE_ADMIN_IP_FOR_MAINTENANCE != getenv('REMOTE_ADDR')){

  if (DOWN_FOR_MAINTENANCE=='true' and !strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) { tep_redirect(tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME)); }

}

// do not let people get to down for maintenance page if not turned on

if (DOWN_FOR_MAINTENANCE=='false' and strstr($PHP_SELF,DOWN_FOR_MAINTENANCE_FILENAME)) {

  tep_redirect(tep_href_link(FILENAME_DEFAULT));

}



// shopping cart actions

if (isset($_GET['action'])) {

  // redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled

  if ($session_started == false) {

    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));

  }

  if (isset($_GET['products_id'])) {

      $products_id = $_GET['products_id'] ;

  } else if (isset($_POST['products_id'])) {

      $products_id = $_POST['products_id'] ;

  } else {

      $products_id = '' ;

  }

  if (defined('DISPLAY_CART') && DISPLAY_CART == 'true') {

    $goto =  FILENAME_SHOPPING_CART;

    $parameters = array('action', 'cPath', 'products_id', 'pid');

  } else {

    $goto = basename($PHP_SELF);

    if ($_GET['action'] == 'buy_now') {

      $parameters = array('action', 'pid', 'products_id');

    } else {

      $parameters = array('action', 'pid');

    }

  }

  $hide_add_to_cart = hide_add_to_cart();

  if ($hide_add_to_cart == 'true') {

    tep_redirect(tep_href_link($goto, 'hide_add_to_cart_error=1'.tep_get_all_get_params($parameters)));    

  }

  

  switch ($_GET['action']) {
    
		
	// customer wants to update the product quantity in their shopping cart

    case 'update_product' :

      if(isset($_POST["proceed_checkout"]) && $_POST["proceed_checkout"]=="true") {
		$goto = FILENAME_CHECKOUT_SHIPPING;
	  }

      for ($i=0, $n=sizeof($_POST['products_id']); $i<$n; $i++) {

        if (in_array($_POST['products_id'][$i], ((isset($_POST['cart_delete']) && is_array($_POST['cart_delete'])) ? $_POST['cart_delete'] : array()))) {

          foreach ($_SESSION['shoppingCart_data']["contents"] as $ck=>$cv) {

            if(substr($ck, 0, strlen($_POST['products_id'][$i])) ==$_POST['products_id'][$i]){

                //$cart->remove($ck); // modified sep 02. 2010 

                $cart->remove($_POST['products_id'][$i]);

            }

          }

        } else {

          // the update product routine is used by the shopping cart presentation page

          // this page does not present attributes, so the product id string is used instead

          foreach ($_SESSION['shoppingCart_data']["contents"] as $ck=>$cv) {

            if(substr($ck, 0, strlen($_POST['products_id'][$i])) ==$_POST['products_id'][$i]){

              //$cart->add_cart($ck,  (int)$_POST['cart_quantity'][$i], '', false);  // modified sep 02. 2010           

			  $cart->add_cart($_POST['products_id'][$i],  (int)$_POST['cart_quantity'][$i], '', false);             

            }

          }          

        }

      }

      // RCI code extend update_product      

      echo $cre_RCI->get('applicationtop', 'updateproduct', false);

      tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

      break;

    // customer adds a product from the products page

    case 'add_product' :

      if (!isset($_POST['id'])) $_POST['id'] = '';

      if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {

        if ( isset($_SESSION['customer_id']) ) tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id='".$_POST['products_id']."'");

        if (isset($_POST['sub_products_qty'])) {

          $i = 0;

          $sub_products_qty = $_POST['sub_products_qty'];

          foreach ($_POST['sub_products_id'] as $sub_products_id) {

            if ($sub_products_qty[$i] > 0) {

              // if sub product attributes are not enabled, do not process them

              if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {

                $attributes = isset($_POST['id'][$sub_products_id]) ? $_POST['id'][$sub_products_id] : '';

              } else {
											 
                $attributes = isset($_POST['id']) ? $_POST['id'] : '';

              }

              $cart->add_cart($sub_products_id, $cart->get_quantity(tep_get_uprid($sub_products_id, $attributes)) + (int)$sub_products_qty[$i], $attributes);

            }

            $i++;

          }

        }  else {

          // the sub product attributes constant determines the name parameter format

          if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {

            $attributes = isset($_POST['id'][$_POST['products_id']]) ? $_POST['id'][$_POST['products_id']] : '';

          } else {
			
			//****************************// Dec 09 2010
			//Merge $_FILES array value with $_POST
			 $arrkey = array_keys($_FILES['id']['name']);	   
			 for($i=0; $i<count($_FILES['id']['name']); $i++) {	  	
				$optArrKey = $arrkey[$i];		
				if($_POST['id'][$optArrKey]['t']=="" && $_FILES['id']['name'][$optArrKey]['t']!="") {
					$_POST['id'][$optArrKey]['t'] = $_FILES['id']['name'][$optArrKey]['t'];										
				}
			 }
			 //**************************//
				 
            $attributes = isset($_POST['id']) ? $_POST['id'] : '';

          }

          $cart->add_cart($_POST['products_id'], $cart->get_quantity(tep_get_uprid($_POST['products_id'], $attributes)) + (int)$_POST['cart_quantity'], $attributes);

        }

      }
	  
	  //files from product info page - Dec 09, 2010
		if(isset($_FILES['id'])) {
			$k = 1;
			while (list($option, $data) = each($_FILES['id']['tmp_name'])) {
				$comment = $_POST['comment'][$k];		
				while (list($option_type, $tmppath) = each($data)) {						
					$fname = $_FILES['id']['name'][$option][$option_type];				
					$fileInfo = pathinfo($fname);
					$randamNumber=md5(microtime().rand(0,999999));					
					$myfiles_dir = 'temp/';				
					$path = DIR_FS_CATALOG . DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension']; 				
					if (move_uploaded_file($tmppath, $path)) { 				
						$cart->get_product_files($_POST['products_id'], $option, $fname, $path, $comment);					
					}
				}			
			 $k++;
			}
		 }
		 
		//BOF Bundle products
		if (isset($_POST['bundle_products_qty'])) {

          $i = 0;

          $bundle_products_qty = $_POST['bundle_products_qty'];

          foreach ($_POST['bundle_products_id'] as $bundle_products_id) {

            if ($bundle_products_qty[$i] > 0) {

              // if sub product attributes are not enabled, do not process them

              if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {

                $attributes = isset($_POST['id'][$bundle_products_id]) ? $_POST['id'][$bundle_products_id] : '';

              } else {
											 
                $attributes = isset($_POST['id']) ? $_POST['id'] : '';

              }
			  
              $cart->add_cart($bundle_products_id, $cart->get_quantity(tep_get_uprid($bundle_products_id, $attributes)) + (int)$bundle_products_qty[$i], $attributes);

            }

            $i++;

          }

        }
		//EOF Bundle products
	  
      //tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));

      tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters) . (isset($products_id) ? '&amp;products_id=' . $products_id : ''), 'SSL'));

      break;
	  
	  
	  case 'add_bulk_products_to_cart' :
		
		//print_r($_POST);
		
		$_SESSION["orders_by_excel_upload"]=1;
		
		foreach($_POST["excel_products"] as $key=>$val) {
			
			//$cart->add_cart($key, $cart->get_quantity(tep_get_uprid($key)) + (int)$val["qty"]);
			
			if(!empty($val["name"])) {
				$cart->get_product_files($key, 42, $val["name"], $val["path"], "Part of master file:" . $val["realname"]);	
				$id["42"]["t"] = $val["name"];
				
				if(!empty($val["attr"])) { $id[1] = $val["attr"]; }
				
				$attributes = $id;
			
			}
			//print_r($attributes);
			
			$cart->add_cart($key, $cart->get_quantity(tep_get_uprid($key, $attributes)) + (int)$val['qty'], $attributes);
			
		}
		
		//exit;
		
		tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters) . (isset($products_id) ? '&amp;products_id=' . $products_id : ''), 'NONSSL'));
		
		//exit;
		
	  break;
		
		

      // wishlist checkboxes

      case 'add_del_products_wishlist' : 

        //delete selected products from wishlist

        if (isset($_POST['del_wishprod'])) {

          foreach ($_POST['del_wishprod'] as $value) {

            if (preg_match('/^[0-9]+$/', $value)) {

              tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = $value and customers_id = '" . $customer_id . "'");

              tep_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where products_id = $value and customers_id = '" . $customer_id . "'");

            }

          }

        }

        // add selected products to wishlist

        if (isset($_POST['add_wishprod'])) {

          foreach ($_POST['add_wishprod'] as $value) {

            if (preg_match('/^[0-9]+$/', $value)) {

             

             foreach($_POST['id'][$value] as $tmp_attrib_ky => $tmp_attrib_val) {

                $tmp_attrib_ary = unserialize(str_replace("\\",'',$tmp_attrib_val));

                $attributes[$tmp_attrib_ky] = $tmp_attrib_ary;

             }



              $cart->add_cart($value, $cart->get_quantity(tep_get_uprid($value, $attributes))+(isset($_POST['cart_quantity']) ? $_POST['cart_quantity']:'1'), $attributes); 

            }

          }

        }

        $wishlist_query_raw = "select * from " . TABLE_WISHLIST . " where customers_id = '" . $customer_id . "' and products_id > 0 and customers_id > 0 order by products_name";

        $wishlist_query = tep_db_query($wishlist_query_raw);

        if ( (tep_db_num_rows($wishlist_query)) && ($cart->count_contents() > 0) ) {

          tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));

        } else {

          tep_redirect(tep_href_link(FILENAME_WISHLIST));

        }

        break;

      // Add product to the wishlist

      case 'add_wishlist' :

        if (isset($_POST['products_id']) && preg_match('/^[0-9]+$/', $_POST['products_id']) && $_POST['products_id'] > 0) {

          if (!isset($_SESSION['customer_id'])) {

            $_SESSION['WISHLIST_PRODUCT'] = $_POST['products_id'];

            $_SESSION['WISHLIST_ATTRIBUTES'] = $_POST['id'];

            $navigation->set_snapshot();

            tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

          }

        } elseif (isset($_SESSION['WISHLIST_PRODUCT']) && isset($_SESSION['customer_id'])) {

          $_POST['products_id'] = $_SESSION['WISHLIST_PRODUCT'];

          $_POST['id'] = $_SESSION['WISHLIST_ATTRIBUTES'];

          unset($_SESSION['WISHLIST_PRODUCT']);

          unset($_SESSION['WISHLIST_ATTRIBUTES']);

        } else {

          tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));

        }



        $wishlist_flag = 0;

        if (isset($_POST['sub_products_qty'])) {

          if (is_array($_POST['sub_products_qty'])) {

            foreach($_POST['sub_products_qty'] as $xv) {

              if ($xv >  0) {

                $wishlist_flag = 1;

                break;

              }

            }

          }

        } else {

          $wishlist_flag = 1;

        }



        if ($wishlist_flag == 0) {

          tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO,'products_id='.$_POST['products_id'].'&werror=1'));

        }





        // get name, model, and price

        $product_name_wish = tep_get_products_name($_POST['products_id'], $languages_id);

        $product_model_wish = tep_get_products_model($_POST['products_id']);

        $pf->loadProduct($_POST['products_id'],$languages_id);

        $products_price_wish = $pf->getLowPrice();

        tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $_POST['products_id'] . "' and customers_id = '" . $customer_id . "'");

        tep_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where products_id = '" . $_POST['products_id'] . "' and customers_id = '" . $customer_id . "'");

        tep_db_query("insert into " . TABLE_WISHLIST . " (customers_id, products_id, products_model, products_name, products_price, products_quantity) values ('" . $customer_id . "', '" . $_POST['products_id'] . "', '" . tep_db_input($product_model_wish) . "', '" . tep_db_input($product_name_wish) . "', '" . $products_price_wish . "', 1 )");

        

        // the sub product attributes constant determines the name parameter format

        if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {

          if (isset ($_POST['id'][$_POST['products_id']])) {

            foreach($_POST['id'][$_POST['products_id']] as $att_option => $att_value) {

              tep_db_query("insert into " . TABLE_WISHLIST_ATTRIBUTES . " (customers_id, products_id, products_options_id , products_options_value_id) values ('" . $customer_id . "', '" . $_POST['products_id'] . "', '" . (int)$att_option . "', '" . serialize($att_value) . "' )");

            }

          }

        } else {

          if (isset ($_POST['id'])) {

            foreach($_POST['id'] as $att_option => $att_value) {

              tep_db_query("insert into " . TABLE_WISHLIST_ATTRIBUTES . " (customers_id, products_id, products_options_id , products_options_value_id) values ('" . $customer_id . "', '" . $_POST['products_id'] . "', '" . (int)$att_option . "', '" . serialize($att_value) . "' )");

            }

          }

        }

        

        tep_redirect(tep_href_link(FILENAME_WISHLIST));

        break;

      // remove item from the wishlist

      case 'remove_wishlist':

        $pid = (isset($_GET['pid']) && $_GET['pid'] != '') ? (int)$_GET['pid'] : 0;

        tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $pid . "' and customers_id = '" . $customer_id . "'");

        tep_redirect(tep_href_link(FILENAME_WISHLIST));

        break;

      // performed by the 'buy now' button in product listings and review page

      case 'buy_now' :

        if ( isset($_GET['products_id']) || preg_match('/^[0-9]+$/', $_GET['products_id']) ) {

          $products_id = (int)$_GET['products_id'];

          if ( isset($_SESSION['customer_id']) ) { tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id= '" . $products_id . "'"); }

          if (tep_has_product_attributes($_GET['products_id']) || tep_has_product_subproducts($_GET['products_id']) ) {

            tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id']));

          } else {
			
			
			//Mar 10 2011 - Start product min quantity as default for all products
			
			$minimum_quantity = tep_get_products_min_order_qty($products_id);
			
			$cart_quant = (isset($_GET['cart_quantity'])?(int)$_GET['cart_quantity']:0);
			
			if($cart_quant >= $minimum_quantity) {
			
				$current_quantity = (int)$_GET['cart_quantity'];
				
			} else {
			
				$current_quantity = $minimum_quantity;
				
			}			
						
			$cart->add_cart($_GET['products_id'], $cart->get_quantity($products_id)+($current_quantity > 1)?(int)$current_quantity:'1', array('zname' => @$_GET['zname']));
			
			
            //$cart->add_cart($_GET['products_id'], $cart->get_quantity($products_id)+((int)$_GET['cart_quantity'] > 1)?(int)$_GET['cart_quantity']:'1', array('zname' => @$_GET['zname']));
			
			//Mar 10 2011 - End product min quantity as default for all products

            if (isset($_GET['delete_product']) && (int)$_GET['delete_product'] > 0) {

              foreach ($_SESSION['shoppingCart_data']["contents"] as $ck=>$cv) {

                if(substr($ck, 0, strlen($_GET['delete_product'])) == $_GET['delete_product']){

                  $cart->remove($ck);    

                }

              }

            }                                

          }

        }

        tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));

        break;

      case 'notify' :

        if ( isset($_SESSION['customer_id']) ) {

          if (isset($_GET['products_id'])) {

            $notify = $_GET['products_id'];

          } elseif (isset($_GET['notify'])) {

            $notify = $_GET['notify'];

          } elseif (isset($_POST['notify'])) {

            $notify = $_POST['notify'];

          } else {

            tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));

          }

          if (!is_array($notify)) $notify = array($notify);

            for ($i=0, $n=sizeof($notify); $i<$n; $i++) {

              $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$notify[$i] . "' and customers_id = '" . $customer_id . "'");

              $check = tep_db_fetch_array($check_query);

              if ($check['count'] < 1) {

                tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . (int)$notify[$i] . "', '" . $customer_id . "', now())");

              }

            }

            tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));

          } else {

            $navigation->set_snapshot();

            tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

          }

          break;

      case 'notify_remove' :

        if ( isset($_SESSION['customer_id']) && isset($_GET['products_id']) ) {

          $products_id = (int)$_GET['products_id']; 

          $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $products_id . "' and customers_id = '" . $customer_id . "'");

          $check = tep_db_fetch_array($check_query);

          if ($check['count'] > 0) {

            tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . $products_id . "' and customers_id = '" . $customer_id . "'");

          }

          tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));

        } else {

          $navigation->set_snapshot();

          tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

        }

        break;

      case 'cust_order' :

        if ( isset($_SESSION['customer_id']) && isset($_GET['pid']) ) {

          $pid = (int)$_GET['pid'];

          if (tep_has_product_attributes($_GET['pid'])) {

            if ($rfw == 1) tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id='" . $pid . "'");

            tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pid, 'NONSSL'));

          } else {

            if ($rfw == 1) tep_db_query("delete from " . TABLE_WISHLIST . " WHERE customers_id=$customer_id AND products_id='" . $pid . "'");

            $cart->add_cart($pid, $cart->get_quantity($pid)+1);

          }

        }

        tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));

        break;

  }

}

// include calendar class

require(DIR_FS_CLASSES . 'calendar.php');

// include affiliate system

require(DIR_FS_INCLUDES . 'affiliate_application_top.php');

// include the who's online functions

if (basename($PHP_SELF) != FILENAME_EVENTS_CALENDAR_CONTENT){

  require(DIR_FS_FUNCTIONS . 'whos_online.php');

  tep_update_whos_online();

}

// include the password crypto functions

require(DIR_FS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)

require(DIR_FS_FUNCTIONS . 'validations.php');

// split-page-results

require(DIR_FS_CLASSES . 'split_page_results.php');

// template application_top

require(DIR_FS_INCLUDES . 'template_application_top.php');

// initialize the message stack for output messages

require(DIR_FS_CLASSES . 'message_stack.php');

$messageStack = new messageStack;

// set which precautions should be checked

define('WARN_INSTALL_EXISTENCE', 'true');

define('WARN_CONFIG_WRITEABLE', 'true');

define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');

define('WARN_SESSION_AUTO_START', 'true');

define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');

// auto activate and expire banners

require(DIR_FS_FUNCTIONS . 'banner.php');

tep_activate_banners();

tep_expire_banners();

// auto expire special products

require(DIR_FS_FUNCTIONS . 'specials.php');

tep_expire_specials();

// auto expire featured products

require(DIR_FS_FUNCTIONS . 'featured.php');

tep_expire_featured();



// calculate category path

$cPath = '';

$current_category_id = 0;

if (isset($_GET['cPath'])) {
    if (file_exists(DIR_FS_CATALOG . 'seo.php') && (CRE_SEO == 'true')) {
		//print_r($_GET);die;
		$cPath_array = tep_get_seo_categories_path($_GET['cPath']);
		
		//print_r($cPath_array);
		
	} else  {
		
		$cPath_array = tep_parse_category_path($_GET['cPath']);
		
	}

  if (sizeof($cPath_array) > 0 ) {

    $cPath = implode('_', $cPath_array);

    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];

    $_GET['cPath'] = $cPath; // reset in case the supplied data was invalid

  } else {

    unset($_GET['cPath']);

  }

}

if ($cPath == '' && isset($_GET['products_id']) && $_GET['products_id'] != '' && !isset($_GET['manufacturers_id'])) {

  if (file_exists(DIR_FS_CATALOG . 'seo.php') && (CRE_SEO == 'true')) {
	  
	  $cPath = tep_get_seo_product_path($_GET['products_id']);
	  
  } else {
	  
	  $cPath = tep_get_product_path((int)$_GET['products_id']);
	  
  }

  $cPath_array = explode('_', $cPath); // the array is needed if the cPath is set

  if ($cPath != '') $current_category_id = $cPath;

}



// include the breadcrumb class and start the breadcrumb trail

require(DIR_FS_CLASSES . 'breadcrumb.php');

$breadcrumb = new breadcrumb;

//$breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER); //Modified Sep 25, 2010

// RCO start

//if ($cre_RCO->get('applicationtop', 'breadcrumb', false) !== true) {

$breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

//Modified Sep 25, 2010
//get infobox id listed in left column
$infobox_qry = tep_db_query("SELECT box_heading, infobox_id FROM " . TABLE_INFOBOX_HEADING);
while($infobox = tep_db_fetch_array($infobox_qry)) {		   
	$infobx[$infobox['infobox_id']] = $infobox['box_heading'];				
}

// add category names or the manufacturer name to the breadcrumb trail

if (isset($cPath_array)) {

  $breadcrumb->add("Products", tep_href_link("allprods.php", ''));
  for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {

    //Modified Sep 25, 2010
	//$categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
	$categories_query = tep_db_query("select c.categories_id, cd.categories_name,parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$cPath_array[$i] . "'  and c.categories_id = cd.categories_id and language_id = '" . (int)$languages_id . "'");
	if (tep_db_num_rows($categories_query) > 0) {
	  $categories = tep_db_fetch_array($categories_query);
	  
	  if($categories['parent_id']!=0) {
			$parent_categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $categories['parent_id'] . "' and language_id = '" . (int)$languages_id . "'");
			if (tep_db_num_rows($parent_categories_query) > 0) {
				$parent_categories = tep_db_fetch_array($parent_categories_query);
				
									
			}
	  }
		  
      $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));

    } else {

      break;

    }

  }

} elseif (isset($_GET['manufacturers_id']) && $_GET['manufacturers_id'] != '') {

  $manufacturers_id = (int)$_GET['manufacturers_id'];

  $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $manufacturers_id . "'");

  if (tep_db_num_rows($manufacturers_query)) {

    $manufacturers = tep_db_fetch_array($manufacturers_query);

    $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_id));

  }

}

// } // RCO eof  //Modified Sep 25, 2010

// add the products model to the breadcrumb trail

if (isset($_GET['products_id']) && $_GET['products_id'] != '') {

  $products_id = (int)$_GET['products_id'];  

  $model_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");

  if (tep_db_num_rows($model_query)) {

    $model = tep_db_fetch_array($model_query);

    if (tep_not_null($model['products_model'])){

      $breadcrumb->add($model['products_model'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&amp;products_id=' . $_GET['products_id']));

    }

  }

}

// include the articles functions

require(DIR_FS_FUNCTIONS . 'articles.php');

require(DIR_FS_FUNCTIONS . 'article_header_tags.php'); 



// calculate topic path

$tPath = '';

$current_topic_id = 0;

if (isset($_GET['tPath'])) {

  if (file_exists(DIR_FS_CATALOG . 'seo.php') && (CRE_SEO == 'true')) {
		
		$tPath_array = tep_get_seo_topics_path($_GET['tPath']);
		
	} else  {
		
		$tPath_array = tep_parse_topic_path($_GET['tPath']);
		
	}

  if (sizeof($tPath_array) > 0 ) {

    $tPath = implode('_', $tPath_array);

    $current_topic_id = $tPath_array[(sizeof($tPath_array)-1)];

    $_GET['tPath'] = $tPath; // reset in case the supplied data was invalid

  } else {

    unset($_GET['tPath']);

  }

}

if ($tPath == '' && isset($_GET['articles_id']) && !isset($_GET['authors_id'])) {

  $tPath = tep_get_article_path((int)$_GET['articles_id']);

  $tPath_array = explode('_', $tPath);

  if ($tPath != '') $current_topic_id = $tPath;

}



// add topic names or the author name to the breadcrumb trail

if (isset($tPath_array)) {

  for ($i=0, $n=sizeof($tPath_array); $i<$n; $i++) {

    $topics_query = tep_db_query("select topics_name from " . TABLE_TOPICS_DESCRIPTION . " where topics_id = '" . (int)$tPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");

    if (tep_db_num_rows($topics_query) > 0) {

      $topics = tep_db_fetch_array($topics_query);
	  
	  //$breadcrumb->add($infobx[169], tep_href_link(FILENAME_DEFAULT)); // Added Sep 25, 2010
	  
      $breadcrumb->add($topics['topics_name'], tep_href_link(FILENAME_ARTICLES, 'tPath=' . implode('_', array_slice($tPath_array, 0, ($i+1)))));

    } else {

      break;

    }

  }

} elseif (isset($_GET['authors_id'])  && $_GET['authors_id'] != '') {

  $authors_id = (int)$_GET['authors_id'];

  $authors_query = tep_db_query("select authors_name from " . TABLE_AUTHORS . " where authors_id = '" . $authors_id . "'");

  if (tep_db_num_rows($authors_query)) {

    $authors = tep_db_fetch_array($authors_query);

    $breadcrumb->add('Articles by ' . $authors['authors_name'], tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $_GET['authors_id']));

  }

}

// add the articles name to the breadcrumb trail

if (isset($_GET['articles_id']) && $_GET['articles_id'] != '') {

  $articles_id = (int)$_GET['articles_id'];

  $article_query = tep_db_query("select articles_name from " . TABLE_ARTICLES_DESCRIPTION . " where articles_id = '" . $articles_id . "'");

  if (tep_db_num_rows($article_query)) {

    $article = tep_db_fetch_array($article_query);

    if (isset($_GET['authors_id'])) {

      $breadcrumb->add($article['articles_name'], tep_href_link(FILENAME_ARTICLE_INFO, 'authors_id=' . $_GET['authors_id'] . '&articles_id=' . $_GET['articles_id']));

    } else {

      $breadcrumb->add($article['articles_name'], tep_href_link(FILENAME_ARTICLE_INFO, 'tPath=' . $tPath . '&articles_id=' . $_GET['articles_id']));

    }

  }

}

if (file_exists("includes/application_top_newsdesk.php")) include("includes/application_top_newsdesk.php");

if (file_exists("includes/application_top_faqdesk.php")) include("includes/application_top_faqdesk.php");

require(DIR_FS_FUNCTIONS . 'gv_functions.php');

// header tags controller

require(DIR_FS_FUNCTIONS . 'header_tags.php');


// clean out HTML comments from ALT tags etc.

require_once(DIR_FS_FUNCTIONS . 'clean_html_comments.php');

//B2B Code for Login to see the Site

if (B2B_REQUIRE_LOGIN=='true' && ! isset($_SESSION['customer_id'])) {

  switch ( basename( $_SERVER['PHP_SELF'] ) ) {

    case FILENAME_LOGIN:

    case FILENAME_PASSWORD_FORGOTTEN:

    case FILENAME_CREATE_ACCOUNT:

    case FILENAME_PW:

    case FILENAME_CREATE_ACCOUNT_SUCCESS:

    case FILENAME_VISUAL_VERIFY_CODE_DISPLAY:

      // continue on, these are allowed

      break;

    default:

      tep_redirect(tep_href_link(FILENAME_LOGIN));

  }

}

if ( !isset($_SESSION['customer_id']) ){

  $customer_group_id="G";

} else {

  $getcustomer_GroupID_query = tep_db_query("select customers_group_id   from " . TABLE_CUSTOMERS . " where  customers_id = '" . (int)$_SESSION['customer_id'] . "'");

  $getcustomer_GroupID = tep_db_fetch_array($getcustomer_GroupID_query);

  $customer_group_id=$getcustomer_GroupID['customers_group_id'];

}

define('CUSTOMER_GROUP_ID',$customer_group_id);

// b2b customers_groups work around for not handling guest

if ($customer_group_id == 'G') {

  $_SESSION['sppc_customer_group_show_tax'] = '1';

  $_SESSION['sppc_customer_group_tax_exempt'] = '0';

}

// down for maintenance code, moved from main_page.tpl.php

if (DOWN_FOR_MAINTENANCE == 'true') {

  $maintenance_on_at_time_raw = tep_db_query("select last_modified from " . TABLE_CONFIGURATION . " WHERE configuration_key = 'DOWN_FOR_MAINTENANCE'");

  $maintenance_on_at_time= tep_db_fetch_array($maintenance_on_at_time_raw);

  define('TEXT_DATE_TIME', $maintenance_on_at_time['last_modified']);

}

//BOF :: customized order changes
	if(isset($_POST["smtCustomizedOrder"])) {
		
		$captcha_err = "";
		
		if($_SESSION["captcha_code"] == $_POST["captcha"]) { 
		
			$mail = new PHPMailer;
					
			$co_rst = ""; $co_error = "";
			
			$co_material = tep_db_prepare_input($_POST['co_material']);			
			$co_shape = tep_db_prepare_input($_POST['co_shape']);
			$co_width = tep_db_prepare_input($_POST['co_width']);
			$co_height = tep_db_prepare_input($_POST['co_height']);
			$co_qty = tep_db_prepare_input($_POST['co_qty']);
			
			$co_name = tep_db_prepare_input($_POST['co_name']);
			$co_email = strtolower(tep_db_prepare_input($_POST['co_email']));
			$co_city = tep_db_prepare_input($_POST['co_city']);			
			$co_postcode = tep_db_prepare_input($_POST['co_postcode']); 			
			$co_comments = tep_db_prepare_input($_POST['co_comments']);
			
			if(empty($co_material)) { $co_error .= "Please select material<br>"; }
			if(empty($co_shape)) { $co_error .= "Please select product shape<br>"; }
			if(empty($co_width)) { $co_error .= "Please enter width<br>"; }
			if(empty($co_qty)) { $co_error .= "Please enter quantity<br>"; }
			
			
			
			$details_arr = array("material"=>$co_material, "shape"=>$co_shape, "width"=>$co_width, "height"=>$co_height, "qty"=>$co_qty, "name"=>$co_name, "email"=>$co_email, "city"=>$co_city, "postcode"=>$co_postcode);
			$details = json_encode($details_arr);
			$date_added = date("Y-m-d H:i:s");
			
			if(empty($co_error)) {
				
				$up_file = "";
				if(isset($_FILES['co_file']['name']) && !empty($_FILES['co_file']['name'])) {
					
					$up_file = cleanFileName($_FILES['co_file']['name']);
					$f_info = pathinfo($up_file);
					$ext = $f_info["extension"];				
					$cof_path = DIR_FS_CATALOG . DIR_WS_IMAGES . "customized_orders/".$up_file;				
					if(file_exists($cof_path)) {
						$up_file = $f_info["basename"]."-".rand(0,99).".".$ext;
						$cof_path = DIR_FS_CATALOG . DIR_WS_IMAGES . "customized_orders/".$up_file;
					}			
					$allowed_files = array("pdf","eps","ai","svg","dxf","dwg","jpeg","jpg","png","gif","tiff","tif","psd","doc","docx");				
					if(array_search($ext,$allowed_files) && ($_FILES['co_file']["size"]<5000000)) {
						if(move_uploaded_file($_FILES['co_file']['tmp_name'],$cof_path)) {
							$co_rst .= "File Uploaded: ".$up_file;
						}					
					}		
				}
				
				
				$sql_data_array = array('details' => $details,                               
									  'comments' => $co_comments,
									  'attachment' => $up_file,
									  'date_added' => $date_added
									  );
				tep_db_perform("customized_orders", $sql_data_array);
				

				 
				$header_mail_template = '<table bgcolor="#cccccc" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
						<tbody>
							<tr>
								<td align="center" valign="middle" width="100%">
									<table align="center" bgcolor="#ffffff" border="0" cellpadding="8" cellspacing="2" height="493" width="633">
										<tbody>
										<tr>
											<td align="center" valign="top"><img src="'.HTTP_SERVER.'/images/ajp_customized_order_banner.png" alt="AJ Parkes - Customized Orders Confirmation" height="115" width="600"></td>
										</tr>
										<tr>
											<td align="left" height="498" valign="top"> <div style="padding:10px; line-height:20px;"> ';
											
							$co_details = 	"Product Material: " . $co_material . "<br><br> Shape: " . $co_shape . "<br><br> Size: " . $co_width . " X " . $co_height . " mm<br><br> Quantity: " . $co_qty . "<br><br> Email: " .$co_email."<br><br> City: ". $co_city . "<br><br> Postcode: " . $co_postcode . "<br><br> Comments: " . $co_comments;
							
							$client_body_mail = '<span style="color:#0086e0">' . EMAIL_GREET_CLIENT_CUSTOMIZED_ORDER . $co_name . '</span>' . "<br><br>" . $co_details;
							$admin_body_mail = EMAIL_GREET_ADMIN_CUSTOMIZED_ORDER . $co_name . "<br><br>" . $co_details;							
							$footer_mail_template = "</div>". EMAIL_FOOTER_CUSTOMIZED_ORDER . '</td>
										</tr>
										</tbody>
									</table>
								</td>
							</tr>
					</tbody>
					</table>';
				
				$check_email = explode("@", $co_email);
		
				if(!is_numeric($check_email[0]) && ($co_email!="") && (empty($_POST["fillspm"]))) {
				
					$client_message = $header_mail_template . $client_body_mail . $footer_mail_template;
					
					if(!empty($co_email)) {
						
						$client_message = $header_mail_template . $client_body_mail . $footer_mail_template;
						$mail->SetFrom(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);	
						$mail->AddAddress($co_email, $co_name);
						$mail->Subject    = EMAIL_CUSTOMIZED_ORDER_SUBJECT;
						$mail->MsgHTML($client_message);
						$mail->Send();
						$mail->ClearAddresses();
						$co_rst = "We have received your enquiry. <br> ". $co_rst . " <br> Thank you.";
						
						if(STORE_OWNER_EMAIL_ADDRESS!="") {
						
							$admin_message = $header_mail_template . $admin_body_mail . $footer_mail_template;
							$mail->SetFrom(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);	
							$mail->AddAddress(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);
							$mail->AddBcc("ananthan@ajparkes.com.au", STORE_NAME);
							$mail->Subject    = EMAIL_ADMIN_CUSTOMIZED_ORDER_SUBJECT;		
							$mail->MsgHTML($admin_message);
							$mail->Send();
							$mail->ClearAddresses();
							
						}			
					
					}
				} else { $co_rst = "Please enter valid email address."; }

			} else { $co_rst = $co_error; }
			
		} else { $co_rst = "Please enter correct captcha."; }
			
	}
//EOF :: customized order changes


//RCI extend application_top

echo $cre_RCI->get('applicationtop', 'bottom', false);

?>