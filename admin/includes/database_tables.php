<?php
/*
  $Id: database_tables.php,v 1.1.1.1 2004/03/04 23:39:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//Admin begin
  define('TABLE_ADMIN', 'admin');
  define('TABLE_ADMIN_FILES', 'admin_files');
  define('TABLE_ADMIN_GROUPS', 'admin_groups');
//Admin end

// Lango Added Line For Infobox & Template configuration: BOF
  define('TABLE_INFOBOX_CONFIGURATION', 'infobox_configuration');
  define('TABLE_TEMPLATE', 'template');
  define('TABLE_INFOBOX_HEADING', 'infobox_heading');
  define('TABLE_BRANDING_DESCRIPTION','branding_description');
// Lango Added Line For Infobox & Template configuration: BOF

// BOF: Lango Added for Featured product MOD
  define('TABLE_FEATURED', 'featured');
// EOF: Lango Added for Featured product MOD

// define the database table names used in the project
  define('TABLE_AFFILIATE_AFFILIATE','affiliate_affiliate');
  define('TABLE_ADDRESS_BOOK', 'address_book');
  define('TABLE_ADDRESS_FORMAT', 'address_format');
  define('TABLE_BANNERS', 'banners');
  define('TABLE_BANNERS_HISTORY', 'banners_history');
  define('TABLE_CATEGORIES', 'categories');
  define('TABLE_CATEGORIES_DESCRIPTION', 'categories_description');
  define('TABLE_CONFIGURATION', 'configuration');
  define('TABLE_CONFIGURATION_GROUP', 'configuration_group');
  define('TABLE_COUNTRIES', 'countries');
  define('TABLE_CURRENCIES', 'currencies');
  define('TABLE_CUSTOMERS', 'customers');
  define('TABLE_CUSTOMERS_BASKET', 'customers_basket');
  define('TABLE_CUSTOMERS_BASKET_ATTRIBUTES', 'customers_basket_attributes');
  define('TABLE_CUSTOMERS_INFO', 'customers_info');
  define('TABLE_LANGUAGES', 'languages');
  define('TABLE_MANUFACTURERS', 'manufacturers');
  define('TABLE_MANUFACTURERS_INFO', 'manufacturers_info');
  define('TABLE_NEWSLETTERS', 'newsletters');
  define('TABLE_ORDERS', 'orders');
  define('TABLE_ORDERS_PAY_METHODS', 'orders_pay_methods');
  define('TABLE_ORDERS_SHIP_METHODS', 'orders_ship_methods');
  define('TABLE_ORDERS_PRODUCTS', 'orders_products');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES', 'orders_products_attributes');
  define('TABLE_ORDERS_PRODUCTS_ATTRIBUTES_TEXT', 'orders_products_attributes_text');
  define('TABLE_ORDERS_PRODUCTS_DOWNLOAD', 'orders_products_download');
  define('TABLE_ORDERS_STATUS', 'orders_status');
  define('TABLE_ORDERS_STATUS_HISTORY', 'orders_status_history');
  define('TABLE_ORDERS_TOTAL', 'orders_total');
  define('TABLE_PRODUCTS', 'products');
  define('TABLE_PRODUCTS_ATTRIBUTES', 'products_attributes');
  define('TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD', 'products_attributes_download');
  define('TABLE_PRODUCTS_DESCRIPTION', 'products_description');
  define('TABLE_PRODUCTS_NOTIFICATIONS', 'products_notifications');
  define('TABLE_PRODUCTS_OPTIONS', 'products_options');
  define('TABLE_PRODUCTS_OPTIONS_TEXT', 'products_options_text');
  define('TABLE_PRODUCTS_OPTIONS_VALUES', 'products_options_values');
  define('TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS', 'products_options_values_to_products_options');
  define('TABLE_PRODUCTS_TO_CATEGORIES', 'products_to_categories');
  define('TABLE_PRODUCTS_XSELL', 'products_xsell');
  define('TABLE_REVIEWS', 'reviews');
  define('TABLE_REVIEWS_DESCRIPTION', 'reviews_description');
  define('TABLE_SESSIONS', 'sessions');
  define('TABLE_SPECIALS', 'specials');
  define('TABLE_TAX_CLASS', 'tax_class');
  define('TABLE_TAX_RATES', 'tax_rates');
  define('TABLE_GEO_ZONES', 'geo_zones');
  define('TABLE_ZONES_TO_GEO_ZONES', 'zones_to_geo_zones');
  define('TABLE_WHOS_ONLINE', 'whos_online');
  define('TABLE_ZONES', 'zones');

// VJ Links Manager v1.00 begin
  define('TABLE_LINK_CATEGORIES', 'link_categories');
  define('TABLE_LINK_CATEGORIES_DESCRIPTION', 'link_categories_description');
  define('TABLE_LINKS', 'links');
  define('TABLE_LINKS_DESCRIPTION', 'links_description');
  define('TABLE_LINKS_TO_LINK_CATEGORIES', 'links_to_link_categories');
  define('TABLE_LINKS_STATUS', 'links_status');
// VJ Links Manager v1.00 end

 define('TABLE_DATA_FILES', 'data_files');
 define('TABLE_DATA_CAT', 'data_cat');

 define('TABLE_BLACKLIST', 'card_blacklist');

//calendar
 define('TABLE_EVENTS_CALENDAR', 'events_calendar');

// Article Manager  DMG
  define('TABLE_ARTICLE_REVIEWS', 'article_reviews');
  define('TABLE_ARTICLE_REVIEWS_DESCRIPTION', 'article_reviews_description');
  define('TABLE_ARTICLES', 'articles');
  define('TABLE_ARTICLES_DESCRIPTION', 'articles_description');
  define('TABLE_ARTICLES_TO_TOPICS', 'articles_to_topics');
  define('TABLE_ARTICLES_XSELL', 'articles_xsell');
  define('TABLE_AUTHORS', 'authors');
  define('TABLE_AUTHORS_INFO', 'authors_info');
  define('TABLE_TOPICS', 'topics');
  define('TABLE_TOPICS_DESCRIPTION', 'topics_description');
// START: Product Extra Fields  DMG
  define('TABLE_PRODUCTS_EXTRA_FIELDS', 'products_extra_fields');
  define('TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS', 'products_to_products_extra_fields');
// END: Product Extra Fields  DMG
//  Contact US Email Subjects
define('TABLE_EMAIL_SUBJECTS', 'email_subjects');
// VJ  CRE Page Manager begin
  define('TABLE_PAGES_CATEGORIES', 'pages_categories');
  define('TABLE_PAGES_CATEGORIES_DESCRIPTION', 'pages_categories_description');
  define('TABLE_PAGES', 'pages');
  define('TABLE_PAGES_DESCRIPTION', 'pages_description');
  define('TABLE_PAGES_TO_CATEGORIES', 'pages_to_categories');
// VJ Page Manager end
define('TABLE_FAQ', 'faq');
define('TABLE_FAQ_CATEGORIES', 'faq_categories');
define('TABLE_FAQ_CATEGORIES_DESCRIPTION', 'faq_categories_description');
define('TABLE_FAQ_TO_CATEGORIES', 'faq_to_categories');
// Eversun mod for sppc and qty price breaks
  define('TABLE_PRODUCTS_GROUPS', 'products_groups');
  define('TABLE_CUSTOMERS_GROUPS', 'customers_groups');
// Eversun mod end for sppc and qty price breaks
// CCGV
define('TABLE_COUPON_GV_QUEUE', 'coupon_gv_queue');
define('TABLE_COUPON_GV_CUSTOMER', 'coupon_gv_customer');
define('TABLE_COUPON_EMAIL_TRACK', 'coupon_email_track');
define('TABLE_COUPON_REDEEM_TRACK', 'coupon_redeem_track');
define('TABLE_COUPONS', 'coupons');
define('TABLE_COUPONS_DESCRIPTION', 'coupons_description');
// Begin RMA Returns System
define('TABLE_RETURN_REASONS', 'return_reasons');
define('TABLE_RETURNS', 'returned_products');
define('TABLE_RETURNS_STATUS', 'returns_status');
define('TABLE_RETURNS_TEXT', 'return_text');
define('TABLE_RETURNS_TOTAL', 'returns_total');
define('TABLE_RETURNS_PRODUCTS_DATA', 'returns_products_data');
define('TABLE_PAYMENT_OPTIONS', 'payment_options');
define('TABLE_RETURN_PAYMENTS', 'refund_payments');
define('TABLE_REFUND_METHOD', 'refund_method');
define('TABLE_RETURNS_STATUS_HISTORY', 'returns_status_history');
define('TABLE_RETURNS_PRODUCTS_ATTRIBUTES_DATA', 'returns_products_attributes_data');
// End RMA Returns System
define('TABLE_CUSTOMER_GROUPS_DESCRIPTION', 'customer_groups_description');
define('TABLE_CUSTOMERS_COMMENTS', 'customers_comments');
// multi-vendor shipping
define('TABLE_VENDORS', 'vendors');
define('TABLE_VENDORS_INFO', 'vendors_info');
define('TABLE_VENDOR_CONFIGURATION', 'vendor_configuration');
define('TABLE_ORDERS_SHIPPING', 'orders_shipping');
define('TABLE_PACKAGING', 'packaging');
// multi-warehouse shipping 
define('TABLE_ZONES_TO_WAREHOUSE_ZONES','zones_to_warehouse_zones');
define('TABLE_WAREHOUSE_ZONES','warehouse_zones');
// compoonents table
define('TABLE_COMPONENTS', 'components'); 
?>