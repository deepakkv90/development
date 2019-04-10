<?php
/*
  $Id: stats_customers.php,v 1.9 2002/03/30 15:03:59 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('REPORT_DATE_FORMAT', 'm. d. Y');

define('HEADING_TITLE', 'Sales Report');

define('REPORT_TYPE_YEARLY', 'Yearly');
define('REPORT_TYPE_MONTHLY', 'Monthly');
define('REPORT_TYPE_WEEKLY', 'Weekly');
define('REPORT_TYPE_DAILY', 'Daily');
define('REPORT_START_DATE', 'From date');
define('REPORT_END_DATE', 'To date (inclusive)');
define('REPORT_DETAIL', 'Detail');
define('REPORT_MAX', 'Show top');
define('REPORT_ALL', 'All');
define('REPORT_SORT', 'Sort');
define('REPORT_EXP', 'Export');
define('REPORT_SEND', 'Send');
define('EXP_NORMAL', 'Normal');
define('EXP_HTML', 'HTML only');
define('EXP_CSV', 'CSV');

define('TABLE_HEADING_DATE', 'Date');
define('TABLE_HEADING_ORDERS', '#Orders');
define('TABLE_HEADING_ITEMS', '#Items');
define('TABLE_HEADING_REVENUE', 'Revenue');
define('TABLE_HEADING_SHIPPING', 'Shipping');
define('TABLE_HEADING_DISCOUNT', 'Discount');



/* Added for Modified Sales report*/

define('TABLE_HEADING_CUST_NO', 'Cust No');
define('TABLE_HEADING_CUST_NAME', 'Cust Name');
define('TABLE_HEADING_CUST_COMPANY', 'Cust Company');
define('TABLE_HEADING_ORDER_NO', 'Order No');
define('TABLE_HEADING_PONUMBER', 'P/O Number');
define('TABLE_HEADING_BILLING_ADDRESS', 'Billing Address');
define('TABLE_HEADING_SHIPPING_ADDRESS', 'Shipping Address');
define('TABLE_HEADING_PRODUCT', 'Product');
define('TABLE_HEADING_QTY', 'QTY');
define('TABLE_HEADING_UNIT_PRICE', 'Unit Price');
define('TABLE_HEADING_SUB_TOTAL', 'Sub Total');
define('TABLE_HEADING_GST', 'GST');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_ORDER_TOTAL', 'Order Total');

/**/

define('DET_HEAD_ONLY', 'No details');
define('DET_DETAIL', 'Show details');
define('DET_DETAIL_ONLY', 'Details with amount');

define('SORT_VAL0', 'Standard');
define('SORT_VAL1', 'Description');
define('SORT_VAL2', 'Description desc');
define('SORT_VAL3', '#Items');
define('SORT_VAL4', '#Items desc');
define('SORT_VAL5', 'Revenue');
define('SORT_VAL6', 'Revenue desc');

define('REPORT_STATUS_FILTER', 'Status');

define('SR_SEPARATOR1', ';');
define('SR_SEPARATOR2', ';');
define('SR_NEWLINE', "\n");

define('TABLE_HEADING_LABOUR', 'Labour');
define('TABLE_HEADING_OVERHEAD', 'Overhead');
define('TABLE_HEADING_MATERIAL', 'Material');
define("DET_PRODUCTS_COST","Products Cost");

define("PRO_COST_DATE","Date");
define("PRO_COST_CATEGORY","Category");
define("PRO_COST_LABOUR","Labour");
define("PRO_COST_OVERHEAD","Overhead");
define("PRO_COST_MATERIAL","Material");
define("PRO_COST_PRODUCT_COST","Product cost");
define("PRO_COST_REVENUE","Revenue(excl GST)");

//SALES REPORT CHART COLOR CODES
define("CHART_FINANCIAL_YEAR_COLOR","#3399FF");
define("CHART_REVENUE_COLOR","#3399FF");
define("CHART_SHIPPING_COLOR","#FFEB00");
define("CHART_PRODUCT_COST_COLOR","#996633");
define("CHART_OVERHEAD_COLOR","#66CC33");
define("CHART_LABOUR_COLOR","#B1231B");
define("CHART_MATERIAL_COLOR","#996633");

?>