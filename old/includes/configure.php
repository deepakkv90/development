<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://dev.buttonbadgesinternational.com.au'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'http://dev.buttonbadgesinternational.com.au'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', false); // secure webserver for checkout procedure?
  define('HTTP_COOKIE_DOMAIN', 'dev.buttonbadgesinternational.com.au');
  define('HTTPS_COOKIE_DOMAIN', 'dev.buttonbadgesinternational.com.au');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_TEMPLATES', 'templates/');
  define('DIR_WS_CONTENT', DIR_WS_TEMPLATES . 'content/');
  define('DIR_WS_JAVASCRIPT', DIR_WS_INCLUDES . 'javascript/');
  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', $_SERVER['DOCUMENT_ROOT'].'/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'buttonba_bbi');
  define('DB_SERVER_PASSWORD', 'toS^r4lL%L4t');
  define('DB_DATABASE', 'buttonba_bbi');
  define('USE_PCONNECT', 'false'); // use persistent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
  
    /* NBI DB credentails to sync orders products*/
  define('SOURCE_SYNC', "npi");
  define('NBI_SYNC', true);
  define('NBI_ORDER_SYNC_URL', "http://localhost/bbi/manage_orders.php");
  define('NBI_ACCOUNT_SYNC_URL', "http://localhost/bbi/manage_accounts.php");
  define('NBI_NEXT_ORDERID_SYNC_URL', "http://localhost/bbi/manage_get_next_order.php");
  define('NBI_PAYPAL_IPN_SYNC_URL', "http://localhost/bbi/manage_paypal_ipn.php");
  //Set new or old designer
  define('NEW_DESIGNER', true);
  
?>