<?php
/*
  $Id: PP_english_lang.php,v 1.0. 2009/04/06 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define("PAYPAL_SHOPPING_CART_IPN_SUBJECT","PayPal_Shopping_Cart_IPN");
define("PAYPAL_SHOPPING_CART_IPN_FROM","PayPal_Shopping_Cart_IPN");
define("PAYPAL_NOTIFY_IPN_TEST_1","TEST IPN Processed for order #");
define("PAYPAL_NOTIFY_IPN_TEST_2","You need to specify an order #");
define("PAYPAL_NOTIFY_RECEIVED_ERROR_1","Error: no valid ");
define("PAYPAL_NOTIFY_RECEIVED_ERROR_2"," received.");
define("INFO_CC_1","Make Shopping Easier for Customers with Web site Payments");
define("INFO_CC_2",''.tep_image(DIR_WS_MODULES . 'payment/paypal/images/hdr_ppGlobev4_160x76.gif',' PayPal ','','','align=right valign="top" style="margin: 10px;"').'
PayPal has optimized their checkout experience by launching an exciting new improvement to their payment flow.
<br/><br/>For new buyers, signing up for a PayPal account is now optional. This means you can complete your payment first, and then decide whether to save your information for future purchases.
<p>To pay by credit card, look for this button:<br/>
<p align="center">'.tep_image(DIR_WS_MODULES . 'payment/paypal/images/PayPal-ContinueCheckout.gif','','','').'</p>
<br/>
Or you may see this:<br/>
<p align="center">'.tep_image(DIR_WS_MODULES . 'payment/paypal/images/PayPal-no-account-Click-Here.gif','','','').'</p>
<br/>
One of these options should appear on the first PayPal screen.<br/>
<p>Note: if you are a PayPal member, you can either use your account,
or use a credit card that is not associated with a PayPal account.
In that case you\'d also need to use an email address that\'s not associated with a PayPal account.</p> ');
define("DONATE_BUTTON_IMAGE_ALT","Make payments with PayPal - it\'s fast, free and secure");
?>