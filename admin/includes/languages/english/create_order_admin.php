<?php
/*
  $Id: orders_status.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Create Order Admin');

define('TEXT_CREATE_ORDERS_ADMIN_HELP', 'The two links below will take you to edit screens where you can edit the payment and shipping for the orders.');
define('TABLE_HEADING_CREATE_ORDERS_ADMIN', 'Create Order Admin');
define('TEXT_LABEL_CREATE_ORDERS_ADMIN_PAYMENT', 'Edit Payment Methods');
define('TEXT_LABEL_CREATE_ORDERS_ADMIN_SHIPPING', 'Edit Shipping Methods');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_ORDERS_STATUS_NAME', 'Orders Status:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new payment method with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this payment?');
define('TEXT_INFO_HEADING_NEW_PAYMENT', 'New Payment');
define('TEXT_INFO_HEADING_EDIT_PAYMENT', 'Edit Payment');
define('TEXT_INFO_HEADING_DELETE_PAYMENT', 'Delete Payment');
define('TEXT_INFO_HEADING_NEW_SHIPPING', 'New Shipping');
define('TEXT_INFO_HEADING_EDIT_SHIPPING', 'Edit Shipping');
define('TEXT_INFO_HEADING_DELETE_SHIPPING', 'Delete Shipping');
define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', 'Error: The default order status can not be removed. Please set another order status as default, and try again.');
define('ERROR_STATUS_USED_IN_ORDERS', 'Error: This order status is currently used in orders.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Error: This order status is currently used in the order status history.');
?>
