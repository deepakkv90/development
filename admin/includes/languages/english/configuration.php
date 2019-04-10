<?php
/*
  $Id: configuration.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
//Unique to configuration_products.php
define('HEADING_TITLE_PRODUCTS', 'Product Configuration');  
define('TEXT_HEADER_EXPLAIN_PRODUCTS', 'This form will set configuration settings for Products');

//Unique to configuration_stock.php
define('HEADING_TITLE_PRODUCTS_STOCK', 'Products Stock Configuration');  
define('TEXT_HEADER_EXPLAIN_PRODUCTS_STOCK', 'This form will configure stocklevel settings');

//Unique to configuration_download.php
define('HEADING_TITLE_PRODUCTS_DOWNLOAD', 'Products Download Configuration');  
define('TEXT_HEADER_EXPLAIN_PRODUCTS_DOWNLOAD', 'This form will configure download settings');

//Unique to configuration_ship_pack.php
define('HEADING_TITLE_SHIP_PACK', 'Shipping and Packaging');  
define('TEXT_HEADER_EXPLAIN_SHIP_PACK', 'This form will configure Shipping and Packaging settings');

//Unique to configuration_accounts.php
define('HEADING_TITLE_ACCOUNT', 'Customer accounts Configuration');  
define('TEXT_HEADER_EXPLAIN_ACCOUNT', 'This form will configure Customer accounts settings');

//Unique to configuration_checkout.php
define('HEADING_TITLE_CHECKOUT', 'Checkout Configuration');  
define('TEXT_HEADER_EXPLAIN_CHECKOUT', 'This form will configure download settings');

//Unique to configuration_fraud.php
define('HEADING_TITLE_PRODUCTS_FRAUD', 'Algozone Fraud Protection');  
define('TEXT_HEADER_EXPLAIN_PRODUCTS_FRAUD', 'This form will configure Algozone Fraud Protection settings');

//Unique to configuration_cust_details.php
define('HEADING_TITLE_CUST_DETAILS', 'Customer Details');  
define('TEXT_HEADER_EXPLAIN_CUST_DETAILS', 'This form will configure customer details settings');

//Unique to configuration_cust_details.php
define('HEADING_TITLE_AFFILIATES', 'Affiliates');  
define('TEXT_HEADER_EXPLAIN_AFFILIATES', 'This form will configure Affiliates settings');
define('TABLE_HEADING_CONFIGURATION_TITLE', 'Title');
define('TABLE_HEADING_CONFIGURATION_VALUE', 'Value');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');
// VJ admin session added
define('CONFIG_ADMIN_SESSION_ERROR', 'You must enter a value above or equal to 60.');
?>
