<?php
/*
  $Id: ot_gv.php,v 1.3 2004/03/09 18:56:37 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// 'Gift Vouchers' replaced as 'Contract Account' feb 03 2011

  define('MODULE_ORDER_TOTAL_GV_TITLE', 'Contract Discount');
  define('MODULE_ORDER_TOTAL_GV_HEADER', 'Contract Discount/Discount Coupons');
  define('MODULE_ORDER_TOTAL_GV_DESCRIPTION', 'Contract Discount');
  if (!defined('SHIPPING_NOT_INCLUDED')) {
    define('SHIPPING_NOT_INCLUDED', ' [Shipping not included]');
  }
  if (!defined('TAX_NOT_INCLUDED')) {
    define('TAX_NOT_INCLUDED', ' [Tax not included]');
  }
  define('MODULE_ORDER_TOTAL_GV_USER_PROMPT', '&nbsp; Use my pre set up discount for this order');
  define('TEXT_ENTER_GV_CODE', 'Enter Redeem Code&nbsp;&nbsp;');
  if (!defined('IMAGE_REDEEM_VOUCHER')) {
    define('IMAGE_REDEEM_VOUCHER', 'Redeem Code Voucher');
  }
  define('MODULE_ORDER_TOTAL_GV_TEXT_ERROR', 'Contract Discount/Discount coupon');
?>