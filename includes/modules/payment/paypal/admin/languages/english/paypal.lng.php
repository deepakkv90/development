<?php
/*
  $Id: paypal.lng.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  //begin ADMIN text
  define('HEADING_ADMIN_TITLE', 'PayPal Instant Payment Notifications');
  define('HEADING_PAYMENT_STATUS', 'Status');
  define('TEXT_ALL_IPNS', 'All');
  define('TEXT_INFO_PAYPAL_IPN_HEADING', 'PayPal IPN');
  define('TABLE_HEADING_ACTION', 'Action');
  define('TEXT_DISPLAY_NUMBER_OF_TRANSACTIONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> IPN\'s)');

  //shared with TransactionSummaryLogs
  define('TABLE_HEADING_DATE', 'Date');
  define('TABLE_HEADING_DETAILS', 'Details');
  define('TABLE_HEADING_PAYMENT_STATUS', 'Status');
  define('TABLE_HEADING_PAYMENT_GROSS', 'Gross');
  define('TABLE_HEADING_PAYMENT_FEE', 'Fee');
  define('TABLE_HEADING_PAYMENT_NET_AMOUNT', 'Net Amount');

  //TransactionSummaryLogs
  define('TABLE_HEADING_TXN_ACTIVITY', 'Transaction Activity');
  define('IMAGE_BUTTON_TXN_ACCEPT', 'Accept');

  //AcceptOrder
  define('SUCCESS_ORDER_ACCEPTED', 'Order Accepted!');
  define('ERROR_UNAUTHORIZED_REQUEST', 'Unauthorized Request!');
  define('ERROR_ORDER_UNPAID', 'Payment has not been Completed!');

  //Template Page Titles
  define('TEXT_NO_IPN_HISTORY', 'No PayPal Transaction Information Available (%s)');
  define('HEADING_DETAILS_TITLE', 'Transaction Details');
  define('HEADING_ITP_TITLE', 'IPN Test Panel');
  define('HEADING_ITP_HELP_TITLE', 'IPN Test Panel - Guide');
  define('HEADING_HELP_CONTENTS_TITLE', 'Help Contents');
  define('HEADING_HELP_CONFIG_TITLE', 'Configuration Guide');
  define('HEADING_HELP_FAQS_TITLE', 'Frequently Asked Questions');
  define('HEADING_ITP_RESULTS_TITLE', 'IPN Test Panel - Results');
  //IPN Test Panel
  define('IMAGE_ERROR', 'Error icon');
?>