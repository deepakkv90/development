<?php
/*
  $Id: checkout_success.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Checkout');
define('NAVBAR_TITLE_2', 'Success');

define('HEADING_TITLE', 'Your Order Has Been Processed!');

define('TEXT_SUCCESS', 'Your order has been successfully processed! <br><br>Your products will arrive at their destination within 2-3 working days from the moment your design has been approved and the payment received (Before 12.30pm).<br>');
define('TEXT_NOTIFY_PRODUCTS', 'Please notify me of updates to the products I have selected below:');
define('TEXT_SEE_ORDERS', 'You can view your order history by going to the <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'My Account\'</a> page and by clicking on <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'History\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', 'Please direct any questions you have to the <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">store owner</a>.');
define('TEXT_THANKS_FOR_SHOPPING', 'Thanks for shopping with us online!');

define('TABLE_HEADING_COMMENTS', 'Enter a comment for the order processed');

define('TABLE_HEADING_DOWNLOAD_DATE', 'Expiry date: ');
define('TABLE_HEADING_DOWNLOAD_COUNT', ' downloads remaining');
define('HEADING_DOWNLOAD', 'Download your products here:');
define('FOOTER_DOWNLOAD', 'You can also download your products at a later time at \'%s\'');

define('PAYPAL_NAVBAR_TITLE_2_OK', 'Success'); // PAYPALIPN
define('PAYPAL_NAVBAR_TITLE_2_PENDING', 'Your Order is being processed.'); // PAYPALIPN
define('PAYPAL_NAVBAR_TITLE_2_FAILED', 'Your payment has failed'); // PAYPALIPN
define('PAYPAL_HEADING_TITLE_OK', 'Your Order Has Been Processed!'); // PAYPALIPN
define('PAYPAL_HEADING_TITLE_PENDING', 'Your Order is being processed!'); // PAYPALIPN
define('PAYPAL_HEADING_TITLE_FAILED', 'Your payment has failed!'); // PAYPALIPN
define('PAYPAL_TEXT_SUCCESS_OK', 'Your order has been successfully processed! Your products will arrive at their destination within 2-5 working days.'); // PAYPALIPN
define('PAYPAL_TEXT_SUCCESS_PENDING', 'Your Order is being processed!'); // PAYPALIPN
define('PAYPAL_TEXT_SUCCESS_FAILED', 'Your payment has failed! Please verify your submitted information to pay with PayPal.'); // PAYPALIPN

define("TEXT_TELL_A_FRIEND", "Hi [Friends Name]
I found this nice website and thought I should share it with you . 
It's amazing , You can design and order a name badge online 24/7. 
Very easy, good prices and excellent service. 

Visit : http://buttonbadgesinternational.com.au/

I hope this information will help you. 

All the best");
?>