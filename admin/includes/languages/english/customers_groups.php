<?php
/*
   for Separate Pricing Per Customer v4 2005/03/03
*/
  
define('HEADING_TITLE', 'Groups');
define('HEADING_TITLE_SEARCH', 'Search:');

define('TABLE_HEADING_NAME', 'Group Name');
define('TABLE_HEADING_ID', 'Group ID');
define('TABLE_HEADING_GROUPS_TEMPLATE','Group Template');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_GROUPS_ACCESS', 'Access');
define('TABLE_HEADING_GROUPS_PRICE', 'Price');
define('TABLE_HEADING_GROUPS_STATUS', 'Status');

define('ENTRY_GROUPS_NAME', 'Group&#160;Name:');
define('ENTRY_GROUPS_TEMPLATE','Group Template:');
define('ENTRY_GROUP_SHOW_TAX', 'Show&#160;prices&#160;with/without&#160;tax:');
define('ENTRY_GROUP_SHOW_TAX_YES', 'Show prices with tax');
define('ENTRY_GROUP_SHOW_TAX_NO', 'Show prices without tax');

define('ENTRY_GROUP_TAX_EXEMPT', 'Tax Exempt:'); 
define('ENTRY_GROUP_TAX_EXEMPT_YES', 'Yes'); 
define('ENTRY_GROUP_TAX_EXEMPT_NO', 'No'); 

define('ENTRY_GROUP_PAYMENT_SET', 'Set payment modules for the customer group');
define('ENTRY_GROUP_PAYMENT_DEFAULT', 'Use the system installed shipping modules for this group');
define('ENTRY_PAYMENT_SET_EXPLAIN', 'If you choose <b><i>Set payment modules for the customer group</i></b> but do not check any of the boxes, default settings will still be used.');

define('ENTRY_GROUP_SHIPPING_SET', 'Restrict shipping for this group to');
define('ENTRY_GROUP_SHIPPING_DEFAULT', 'Use settings from Configuration');
define('ENTRY_SHIPPING_SET_EXPLAIN', 'If you choose <b><i>Set shipping modules for the customer group</i></b> but do not check any of the boxes, default settings will still be used.');

define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this group?');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_GROUPS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Customers Groups)');
define('TEXT_INFO_HEADING_DELETE_GROUP', 'Delete Group');

define('ERROR_CUSTOMERS_GROUP_NAME', 'Please enter a Group Name');
define('TEXT_CUSTOMERS_GROUPS_1', 'Maximum length: 32 characters');
define('TEXT_CUSTOMERS_GROUPS_2', "This Setting only works when 'Display Prices with Tax'");
define('TEXT_CUSTOMERS_GROUPS_3', "is set to true in the Configuration for your store and Tax Exempt (below) to 'No'.");
define('TEXT_CUSTOMERS_GROUPS_4', "You are not allowed to delete this group:<br><br><b>");

define('GROUP_DETAILS', "Group Details");
define('HEADING_TITLE_GROUP_DISCOUNT', "Group Discount Tool");
define('ENTRY_GROUP_DISCOUNT_OPTION1', "Push to Products Base Price Only");
define('ENTRY_GROUP_DISCOUNT_OPTION2', "Push to Products Base and Quantity Price Break Fields");
define('ENTRY_GROUPS_DISCOUNT', "Discount:");
define('TEXT_GROUP_TOOL', '<span class="errorText">** This is only a tool - the discount value is not stored **</span>');
define('ENTRY_GROUPS_HIDE_SHOW_PRICES','Hide/Show Prices: ');
define('ENTRY_GROUP_HIDE_SHOW_PRICES_OPTION1','Hide Prices');
define('ENTRY_GROUP_HIDE_SHOW_PRICES_OPTION2','Show Prices');
define('ENTRY_GROUPS_ALLOW_ADD_TO_CART','Allow Add To Cart: ');
define('ENTRY_GROUPS_ALLOW_ADD_TO_CART_OPTION1','Yes');
define('ENTRY_GROUPS_ALLOW_ADD_TO_CART_OPTION2','No');

define('ENTRY_PUBLIC_DESCRIPTION','Public Description: ');
define('ENTRY_APPROVAL_MESSAGE','Approval Message: ');
define('ENTRY_COMMENTS','Comments: ');
define('ENTRY_ADMIN_COMMENTS','Admin comments: ');
define('HEADING_TITLE_ADDITIONAL_INFO','Additional Info');
define('ENTRY_FREE_SHIPPING_OVER', 'Free Shippng for Over: ');

define('TEXT_PUSH_SAVE_OPTION','Save Options');
define('TEXT_PUSH_WARNING','Warning : operation may time out if this is a top level and you have a large number of products.');
?>