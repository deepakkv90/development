<?php
/*
  Definitions for Attributes Sorter
*/

// Turn things off
define('I_AM_OFF',true);

// WebMakers.com Added: Attributes - Definitions to move to attribute_sorter.php
define('TABLE_HEADING_PRODUCT_ATTRIBUTE_ONE_TIME','One Time Charge');

// WebMakers.com Added: Attribute Copy Option
define('TEXT_COPY_ATTRIBUTES_ONLY','Only used for Duplicate Products ...');
define('TEXT_COPY_ATTRIBUTES','Copy Product Attributes to Duplicate?');
define('TEXT_COPY_ATTRIBUTES_YES','Yes');
define('TEXT_COPY_ATTRIBUTES_NO','No');

// WebMakers.com Added: Attributes Copy from Existing Product to Existing Product
define('PRODUCT_NAMES_HELPER','<FONT COLOR="FF0000"><a href="' . 'quick_products_popup.php' . '" onclick="NewWindow(this.href,\'name\',\'700\',\'500\',\'yes\');return false;">[ Product ID# Look-up ]</a>'); 
 define('ATTRIBUTES_NAMES_HELPER', '<FONT COLOR="FF0000"><a href="' . 'quick_attributes_popup.php?look_it_up=' . $pID . '&my_languages_id=' . $languages_id . '" onclick="NewWindow2(this.href,\'name2\',\'700\',\'400\',\'yes\');return false;">[ Quick List Attributes for Product ID ' . $pID . ' ]</a>');

// WebMakers.com Added: Product Option Attributes Sort Order - products_attributes.php
define('TABLE_HEADING_OPTION_SORT_ORDER','Sort Order');
?>
