<?php
/*
  $Id: buysafe_applicationtop_bottom.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
require('includes/application_top.php');

/*
if (!strstr($HTTP_REFERER, 'buysafe.com')){
  header("HTTP/1.0 500 Internal Server Error");
  header("BuySafeSuccess = FALSE");
  exit;
}
*/

if ($_POST['PassCode'] != md5(MODULE_ADDONS_BUYSAFE_CART_PREFIX)) {
  header("HTTP/1.0 500 Internal Server Error");
  header("BuySafeSuccess = FALSE");
  exit;
}

tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $_POST['SealData'] . "' where configuration_key = 'MODULE_ADDONS_BUYSAFE_SEAL_AUTHENTICATION_DATA'");
tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $_POST['StoreData'] . "' where configuration_key = 'MODULE_ADDONS_BUYSAFE_STORE_AUTHENTICATION_DATA'");

$class = 'buysafe';
$buysafe_directory = DIR_FS_CATALOG_MODULES . 'addons/';
$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
if (file_exists($buysafe_directory . $class . $file_extension)){
  include($buysafe_directory . $class . $file_extension);
  $buysafe = new $class;
  $buysafe_param['private_token'] = $_POST['StoreData'];
  $buysafe_result = $buysafe->call_api('GetbuySAFEDateTime', $buysafe_param);
  if ($buysafe_result['buySAFEDateTime']) {
    header("HTTP/1.0 200 OK");
    header("BuySafeSuccess = TRUE");
    exit;
  }
}

header("HTTP/1.0 500 Internal Server Error");
header("BuySafeSuccess = FALSE");
?>