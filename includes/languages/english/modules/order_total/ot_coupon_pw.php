<?php
/*
  $Id: ot_coupon.php,v 1.3 2004/03/09 18:56:37 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
  if (!defined('MODULE_ORDER_TOTAL_COUPON_TEXT_ERROR')) {
    define('MODULE_ORDER_TOTAL_COUPON_PW_TEXT_ERROR', 'Message');
  }
  define('MODULE_ORDER_TOTAL_COUPON_PW_TITLE', 'Discount Coupons PW');
  define('MODULE_ORDER_TOTAL_COUPON_PW_HEADER', 'Gift Vouchers/Discount Coupons');
  define('MODULE_ORDER_TOTAL_COUPON_PW_DESCRIPTION', 'Discount Coupon');
  if (!defined('SHIPPING_NOT_INCLUDED')) {
    define('SHIPPING_NOT_INCLUDED', ' [Shipping not included]');
  }
  if (!defined('TAX_NOT_INCLUDED')) {
    define('TAX_NOT_INCLUDED', ' [Tax not included]');
  }
  define('MODULE_ORDER_TOTAL_COUPON_PW_USER_PROMPT', '');
  define('ERROR_NO_INVALID_REDEEM_COUPON', 'Invalid Coupon Code');
  define('ERROR_INVALID_STARTDATE_COUPON', 'This coupon is not available yet');
  define('ERROR_INVALID_FINISDATE_COUPON', 'This coupon has expired');
  define('ERROR_INVALID_USES_COUPON', 'This coupon could only be used ');
  define('TIMES', ' times.');
  define('ERROR_INVALID_USES_USER_COUPON', 'You have used the coupon the maximum number of times allowed per customer.');
  define('REDEEMED_COUPON', 'a coupon worth ');
  define('REDEEMED_MIN_ORDER', 'on orders over ');
  define('REDEEMED_RESTRICTIONS', ' [Product-Category restrictions apply]');
 //moved to base language define file
//  define('TEXT_ENTER_COUPON_CODE', 'Enter Redeem Code&nbsp;&nbsp;');
  define('IMAGE_REDEEM_VOUCHER', 'Redeem Code Voucher');
?>