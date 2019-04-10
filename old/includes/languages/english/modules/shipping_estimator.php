<?php
/*
  $Id: shiupping estimator.php,v 1.1 2004/12/01  $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 CREloaded

  Released under the GNU General Public License
*/

 define('SHIPPING_OPTIONS', 'Shipping Options:');
 define('SHIPPING_OPTIONS_LOGIN_A', 'Please <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Log In</u></a>, to display your personal shipping costs.');
 define('SHIPPING_OPTIONS_LOGIN_B', 'Please Log In, to display your personal shipping costs.');
  define('SHIPPING_METHOD_TEXT','Shipping Methods:');
  define('SHIPPING_METHOD_RATES','Rates:');
  define('SHIPPING_METHOD_TO','Ship to: ');
  define('SHIPPING_METHOD_TO_NOLOGIN', 'Ship to: <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Log In</u></a>');
  define('SHIPPING_METHOD_FREE_TEXT','Free Shipping');
  define('SHIPPING_METHOD_ALL_DOWNLOADS','- Downloads');
  define('SHIPPING_METHOD_RECALCULATE','Recalculate');
  define('SHIPPING_METHOD_ZIP_REQUIRED','true');
  define('SHIPPING_METHOD_ADDRESS','Address:');
  define('SHIPPING_METHOD_ITEM','ITEM:');
  define('SHIPPING_METHOD_ITEMS','ITEMS:');
  define('SHIPPING_METHOD_WEIGHT','Weight:');
  define('SHIPPING_METHOD_WEIGHT_UNIT','lbs');
?>