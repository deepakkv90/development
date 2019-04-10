<?php
/*
  $Id: create_customer_pdf,v 1.1 2007/07/25 clefty (osc forum id chris23)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
*/

define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_TAX', 'VAT');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Price');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Price (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inc)');

define('ENTRY_SOLD_TO', 'Sold To:');
define('ENTRY_SHIP_TO', 'Ship To:');
define('ENTRY_PAYMENT_METHOD', 'Payment Method:');
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_TAX', 'Tax:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', 'Total:');

define('PRINT_INVOICE_HEADING', 'Tax Invoice');

define('PRINT_INVOICE_TITLE', 'Invoice number: ');
define('PRINT_INVOICE_ORDERNR', 'Order # ');
define('PRINT_INVOICE_DATE', 'Date Purchased: ');

define ('PDF_META_TITLE','Your Invoice');
define ('PDF_META_SUBJECT','PDF copy of your invoice number: ');

define ('PDF_INV_QTY_CELL','Qty');
define ('PDF_INV_WEB','Web: ');
define ('PDF_INV_EMAIL','E-mail: '); 
define ('PDF_INV_CUSTOMER_REF','Customer reference: ');
//define('PDF_INV_CUSTOMER_NUMBER_TEXT', 'Customer number:  '); //Sep 29 2011
define('PDF_INV_CUSTOMER_NUMBER_TEXT', 'Account number:  ');

define('ENTRY_DATE_PURCHASED', 'Date Purchased:');

define('ENTRY_PURCHASE_NUMBER', 'Purchase #:');

define('SEND_INVOICE_EMAIL_CONTENT', 'Dear %s,'. "\n" .'Thanks for using our services.  Attached is the invoice for the Order# %s. '. "\n" .'To make payment online, please use the bank details bellow this email signature. '. "\n" .'Note that for your convenience, all your invoices are available on our website 24/7 in your account. '. "\n" .'Simply login "%s" and click on "my order history" on the left menu. '. "\n\n" .'Please feel free to e-mail us for any clarification.'. "\n\n" .'Assuring you of our best services at all times. '. "\n\n" .'Thank you'. "\n" .'%s'. "\n\n");

define('TEXT_ATTACHED_INVOICE_NAME', "tax_invoice");
define('TABLE_HEADING_QUANTITY', "QTY");

?>
