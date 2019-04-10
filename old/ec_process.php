<?php
/*
  $Id: ec_process.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

require('includes/application_top.php');
require(DIR_WS_MODULES . 'payment/paypal_xc.php');

if(tep_paypal_xc_enabled()){
  $payment_modules = new paypal_xc();
  $payment_modules->ec_step1();
}
?>