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
  define('HTTP_SERVER', 'http://localhost/dev'); // eg, http://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER', 'http://localhost/dev');
  define('HTTPS_CATALOG_SERVER', 'http://localhost/dev');
  define('HTTPS_SERVER', 'http://localhost/dev'); // eg, https://localhost - should not be empty for productive servers
  define('HTTPS_ADMIN_SERVER', 'http://localhost/dev');
  define('HTTP_COOKIE_DOMAIN', 'http://localhost/dev');
  define('HTTPS_COOKIE_DOMAIN', 'http://localhost/dev');
  define('HTTP_COOKIE_PATH', '/admin/');
  define('HTTPS_COOKIE_PATH', '/admin/');
  define('ENABLE_SSL',  'true'); // secure webserver for checkout procedure?
  define('ENABLE_SSL_CATALOG', 'true'); // secure webserver for catalog module
  define('DIR_WS_HTTP_ADMIN',  '/admin/');
  define('DIR_WS_HTTPS_ADMIN',  '/admin/');
  define('DIR_FS_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'].'/dev/'); // where the pages are located on the server
  define('DIR_FS_ADMIN', $_SERVER['DOCUMENT_ROOT'].'/dev/admin/'); // absolute path required
  define('DIR_WS_CATALOG', '/'); // absolute path required
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_FS_CATALOG', $_SERVER['DOCUMENT_ROOT'].'/dev/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

  define('DIR_FS_CATALOG_MAINPAGE_MODULES', DIR_FS_CATALOG_MODULES . 'mainpage_modules/');
  define('DIR_WS_TEMPLATES', DIR_WS_CATALOG . 'templates/');
  define('DIR_FS_TEMPLATES', DIR_FS_CATALOG . 'templates/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'root');
  define('DB_SERVER_PASSWORD', ''); //CzaTqBqSTD4h
  define('DB_DATABASE', 'buttonba_bbi');
  define('USE_PCONNECT', 'false'); // use persistent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
?>