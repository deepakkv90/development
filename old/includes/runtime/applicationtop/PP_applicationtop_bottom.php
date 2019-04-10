<?php
/*
  $Id: admin_orders_transaction.php,v 1.0 2009/04/06 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// PayPal_Shopping_Cart_IPN 2.8 
require(DIR_WS_MODULES . 'payment/paypal/classes/osC/osC.class.php');
PayPal_osC::check_order_status(true);
?>