<?php
/*
  The code is designed to check the current configuration file
  and compare it to the currently detected path information.
  If the path info has changed and the file is writable, update it.
  
  Released under the GNU General Public License
*/
  // Set the level of error reporting
  error_reporting(E_ALL);
  
  $script_filename = $_SERVER['SCRIPT_FILENAME'];
  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);

  $script_name = $_SERVER['SCRIPT_NAME'];
  $script_name = str_replace('\\', '/', $script_name);
  $script_name = str_replace('//', '/', $script_name);

  $dir_fs_www_root = dirname($script_filename);
  $dir_fs_www_root_admin = $dir_fs_www_root . '/admin';
    
  $www_location = 'http://' . $_SERVER['HTTP_HOST'] . substr($script_name,0, strrpos($script_name, '/'));
  if (substr($www_location, -1) != '/') $www_location .= '/';
  $http_url = parse_url($www_location);
  $http_server = 'http://' . $http_url['host'];
  $https_server = 'https://' . $http_url['host'];
  $http_cookie_domain = $http_url['host'];
  $http_path = $http_url['path'];
  if (substr($http_path, -1) != '/') $http_path .= '/';
  $http_path_admin = $http_path . 'admin/';
  
  
  $catConfig = file_get_contents('includes/configure.php');
  $adminConfig = file_get_contents('admin/includes/configure.php');
  
  $pattern = "/'DIR_FS_CATALOG',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $catConfig, $match);
  $config_fs_root_cat = $match[1];
  
  $pattern = "/'DIR_FS_ADMIN',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $adminConfig, $match);
  $config_fs_root_admin = $match[1];
  
  $pattern = "/'HTTP_SERVER',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $catConfig, $match);
  $config_http_server_cat = $match[1];
  $match = array();
  preg_match($pattern, $adminConfig, $match);
  $config_http_server_admin = $match[1];
  
  $pattern = "/'DIR_WS_HTTP_CATALOG',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $catConfig, $match);
  $config_path_cat = $match[1];
  
  $pattern = "/'DIR_WS_HTTP_ADMIN',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $adminConfig, $match);
  $config_path_admin = $match[1];
  
  
  $changed = false;
  if ($config_fs_root_cat != $dir_fs_www_root) $changed = true;
  if ($config_fs_root_admin != $dir_fs_www_root_admin) $changed = true;
  
  if ($config_http_server_cat != $http_server) $changed = true;
  if ($config_http_server_admin != $http_server) $changed = true;
  
  if ($config_path_cat != $http_path) $changed = true;
  if ($config_path_admin != $http_path_admin) $changed = true;
  
  $writable_cat = is_writable('includes/configure.php');
  $writable_admin = is_writable('admin/includes/configure.php'); 
  
  if ($changed) {
    if ($writable_cat && $writable_admin) {
      echo 'Changed and writable';
    } else {
      echo 'Changed and not writable';
      exit();
    }
  } else {
    echo 'nothing to do';
    exit();
  }
  
  echo "<br>$config_fs_root_cat compared to $dir_fs_www_root<br>";
  echo "<br>$config_fs_root_admin compared to $dir_fs_www_root_admin<br>";
  echo "<br>";
  echo "<br>$config_http_server_cat compared to $http_server<br>";
  echo "<br>$config_http_server_admin compared to $http_server<br>";
  echo "<br>";
  echo "<br>$config_path_cat compared to $http_path<br>";
  echo "<br>$config_path_admin compared to $http_path_admin<br>";
  
  // build the new catalog confugre file
  $pattern = "/'DB_SERVER',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $catConfig, $match);
  $db_server = $match[1];
  
  $pattern = "/'DB_SERVER_USERNAME',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $catConfig, $match);
  $db_username = $match[1];        
  
  $pattern = "/'DB_SERVER_PASSWORD',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $catConfig, $match);
  $db_password = $match[1];
  
  $pattern = "/'DB_DATABASE',\s*'(.*?)'/";
  $match = array();
  preg_match($pattern, $catConfig, $match);
  $db_database = $match[1];
  
  $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     '  osCommerce, Open Source E-Commerce Solutions' . "\n" .
                     '  http://www.oscommerce.com' . "\n" .
                     '' . "\n" .
                     '  Copyright (c) 2003 osCommerce' . "\n" .
                     '' . "\n" .
                     '  Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     '  define(\'HTTP_SERVER\', \'' . $http_server . '\'); // eg, http://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'HTTPS_SERVER\', \'' . $https_server . '\'); // eg, https://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'ENABLE_SSL\', true); // secure webserver for checkout procedure?' . "\n" .
                     '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTP_COOKIE_PATH\', \'' . $http_path . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_PATH\', \'' . $http_path . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_path . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $http_path . '\');' . "\n" .
                     '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                     '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
                     '' . "\n" .
                     '  define(\'DIR_WS_TEMPLATES\', \'templates/\');' . "\n" .
                     '  define(\'DIR_WS_CONTENT\', DIR_WS_TEMPLATES . \'content/\');' . "\n" .
                     '  define(\'DIR_WS_JAVASCRIPT\', DIR_WS_INCLUDES . \'javascript/\');' . "\n" .
                     '  define(\'DIR_WS_DOWNLOAD_PUBLIC\', \'pub/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_www_root . '/\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD\', DIR_FS_CATALOG . \'download/\');' . "\n" .
                     '  define(\'DIR_FS_DOWNLOAD_PUBLIC\', DIR_FS_CATALOG . \'pub/\');' . "\n" .
                     '' . "\n" .
                     '// define our database connection' . "\n" .
                     '  define(\'DB_SERVER\', \'' . $db_server . '\'); // eg, localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'DB_SERVER_USERNAME\', \'' . $db_username . '\');' . "\n" .
                     '  define(\'DB_SERVER_PASSWORD\', \'' . $db_password . '\');' . "\n" .
                     '  define(\'DB_DATABASE\', \'' . $db_database . '\');' . "\n" .
                     '  define(\'USE_PCONNECT\', \'false\'); // use persistent connections?' . "\n" .
                     '  define(\'STORE_SESSIONS\', \'mysql\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';

  $fp = fopen($dir_fs_www_root . '/includes/configure.php', 'w');
  fputs($fp, $file_contents);
  fclose($fp);
  
  
  // build the new admin confugre file
  $file_contents = '<?php' . "\n" .
                     '/*' . "\n" .
                     '  osCommerce, Open Source E-Commerce Solutions' . "\n" .
                     '  http://www.oscommerce.com' . "\n" .
                     '' . "\n" .
                     '  Copyright (c) 2003 osCommerce' . "\n" .
                     '' . "\n" .
                     '  Released under the GNU General Public License' . "\n" .
                     '*/' . "\n" .
                     '' . "\n" .
                     '// Define the webserver and path parameters' . "\n" .
                     '// * DIR_FS_* = Filesystem directories (local/physical)' . "\n" .
                     '// * DIR_WS_* = Webserver directories (virtual/URL)' . "\n" .
                     '  define(\'HTTP_SERVER\', \'' . $http_server . '\'); // eg, http://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'HTTP_CATALOG_SERVER\', \'' . $http_server . '\');' . "\n" .
                     '  define(\'HTTPS_CATALOG_SERVER\', \'' . $https_server . '\');' . "\n" .
                     '  define(\'HTTPS_SERVER\', \'' . $https_server . '\'); // eg, https://localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'HTTPS_ADMIN_SERVER\', \'' . $https_server . '\');' . "\n" .
                     '  define(\'HTTP_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_DOMAIN\', \'' . $http_cookie_domain . '\');' . "\n" .
                     '  define(\'HTTP_COOKIE_PATH\', \'' . $http_path_admin . '\');' . "\n" .
                     '  define(\'HTTPS_COOKIE_PATH\', \'' . $http_path_admin . '\');' . "\n" .
                     '  define(\'ENABLE_SSL\',  \'true\'); // secure webserver for checkout procedure?' . "\n" .
                     '  define(\'ENABLE_SSL_CATALOG\', \'true\'); // secure webserver for catalog module' . "\n" .
                     '  define(\'DIR_WS_HTTP_ADMIN\',  \'' . $http_path_admin . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_ADMIN\',  \'' . $http_path_admin . '\');' . "\n" .
                     '  define(\'DIR_FS_DOCUMENT_ROOT\', \'' . $dir_fs_www_root . '/\'); // where the pages are located on the server' . "\n" .
                     '  define(\'DIR_FS_ADMIN\', \'' . $dir_fs_www_root_admin . '/\'); // absolute path required' . "\n" .
                     '  define(\'DIR_WS_CATALOG\', \'' . $http_path . '\'); // absolute path required' . "\n" .
                     '  define(\'DIR_WS_HTTP_CATALOG\', \'' . $http_path . '\');' . "\n" .
                     '  define(\'DIR_WS_HTTPS_CATALOG\', \'' . $http_path . '\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG\', \'' . $dir_fs_www_root . '/\'); // absolute path required' . "\n" .
                     '  define(\'DIR_WS_IMAGES\', \'images/\');' . "\n" .
                     '  define(\'DIR_WS_ICONS\', DIR_WS_IMAGES . \'icons/\');' . "\n" .
                     '  define(\'DIR_WS_CATALOG_IMAGES\', DIR_WS_CATALOG . \'images/\');' . "\n" .
                     '  define(\'DIR_WS_INCLUDES\', \'includes/\');' . "\n" .
                     '  define(\'DIR_WS_BOXES\', DIR_WS_INCLUDES . \'boxes/\');' . "\n" .
                     '  define(\'DIR_WS_FUNCTIONS\', DIR_WS_INCLUDES . \'functions/\');' . "\n" .
                     '  define(\'DIR_WS_CLASSES\', DIR_WS_INCLUDES . \'classes/\');' . "\n" .
                     '  define(\'DIR_WS_MODULES\', DIR_WS_INCLUDES . \'modules/\');' . "\n" .
                     '  define(\'DIR_WS_LANGUAGES\', DIR_WS_INCLUDES . \'languages/\');' . "\n" .
                     '  define(\'DIR_WS_CATALOG_LANGUAGES\', DIR_WS_CATALOG . \'includes/languages/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_LANGUAGES\', DIR_FS_CATALOG . \'includes/languages/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_IMAGES\', DIR_FS_CATALOG . \'images/\');' . "\n" .
                     '  define(\'DIR_FS_CATALOG_MODULES\', DIR_FS_CATALOG . \'includes/modules/\');' . "\n" .
                     '  define(\'DIR_FS_BACKUP\', DIR_FS_ADMIN . \'backups/\');' . "\n" .
                     '' . "\n" .
                     '    define(\'DIR_FS_CATALOG_MAINPAGE_MODULES\', DIR_FS_CATALOG_MODULES . \'mainpage_modules/\');' . "\n" .
                     '    define(\'DIR_WS_TEMPLATES\', DIR_WS_CATALOG . \'templates/\');' . "\n" .
                     '    define(\'DIR_FS_TEMPLATES\', DIR_FS_CATALOG . \'templates/\');' . "\n" .
                     '' . "\n" .
                     '// define our database connection' . "\n" .
                     '  define(\'DB_SERVER\', \'' . $db_server . '\'); // eg, localhost - should not be empty for productive servers' . "\n" .
                     '  define(\'DB_SERVER_USERNAME\', \'' . $db_username . '\');' . "\n" .
                     '  define(\'DB_SERVER_PASSWORD\', \'' . $db_password . '\');' . "\n" .
                     '  define(\'DB_DATABASE\', \'' . $db_database . '\');' . "\n" .
                     '  define(\'USE_PCONNECT\', \'false\'); // use persistent connections?' . "\n" .
                     '  define(\'STORE_SESSIONS\', \'mysql\'); // leave empty \'\' for default handler or set to \'mysql\'' . "\n" .
                     '?>';

  $fp = fopen($dir_fs_www_root_admin . '/includes/configure.php', 'w');
  fputs($fp, $file_contents);
  fclose($fp);
  
?>