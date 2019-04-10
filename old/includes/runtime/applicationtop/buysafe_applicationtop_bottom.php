<?php
/*
  $Id: buysafe_applicationtop_bottom.php,v 1.0.0.0 2007/08/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $cart, $buysafe_result, $messageStack, $WantsBond;

if (defined('MODULE_ADDONS_BUYSAFE_STATUS') &&  MODULE_ADDONS_BUYSAFE_STATUS == 'True') {  
  if ($cart->count_contents() > 0) {
    if (basename($_SERVER['PHP_SELF']) == 'shopping_cart.php' || basename($_SERVER['PHP_SELF']) == 'checkout_confirmation.php' || basename($_SERVER['PHP_SELF']) == 'checkout_process.php') {      
      require_once(DIR_WS_CLASSES . 'buysafe.php');
      $buysafe_module = new buysafe_class;
      $session_WantsBond = (isset($_SESSION['WantsBond'])) ? $_SESSION['WantsBond'] : '';
      $WantsBond = (isset($_POST['WantsBond'])) ? $_POST['WantsBond'] : $session_WantsBond; 
      if (!isset($_SESSION['cre_buySafe_unique_CartId'])) $_SESSION['cre_buySafe_unique_CartId'] = '';
      if($_SESSION['cre_buySafe_unique_CartId'] == '') {
        $timeStamp = strtotime ('now'); 
        $cre_buySafe_unique_cart_id = $timeStamp . $_SERVER['REMOTE_ADDR']; 
        $cre_buySafe_unique_cart_id = str_replace('.', '',$cre_buySafe_unique_cart_id);
        // unique format = timestamp + remote_address + mixed random
        $_SESSION['cre_buySafe_unique_CartId'] = MODULE_ADDONS_BUYSAFE_CART_PREFIX . '-' . $cre_buySafe_unique_cart_id . tep_create_random_value(6);
      }
      $buysafe_params = array('WantsBond' => ($WantsBond ? $WantsBond : 'false'), 'buysafe_cart_id' => ($_SESSION['cre_buySafe_unique_CartId'] ? $_SESSION['cre_buySafe_unique_CartId'] : ''));
      $buysafe_result = $buysafe_module->call_api('AddUpdateShoppingCart', $buysafe_params);  
      if (is_array($buysafe_result)) {
        $WantsBond = ($buysafe_result['BondCostDisplayText'] != '') ? true : false;
        $_SESSION['WantsBond'] = $WantsBond;
        if (defined('MODULE_ADDONS_BUYSAFE_DEBUG') && MODULE_ADDONS_BUYSAFE_DEBUG == true) {
          if (tep_not_null($buysafe_result['faultstring'])) {
            $messageStack->add('header', 'buySAFE fault: ' . $buysafe_result['faultstring'], 'error');
          } 
        }
      }
    }
  }
}
?>