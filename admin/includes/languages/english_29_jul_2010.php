<?php
/*
  $Id: english.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//Admin begin
define('TEXT_ADMIN_HOME','Admin Home');
define('TEXT_VIEW_CATALOG','View Catalog');
define('TEXT_FORUMS','CRE Forums');
define('TEXT_PURCHASE_SUPPORT','Support');
define('TEXT_CRE_SECURE','CRE Secure Payments');
define('TEXT_ADMIN_LANG','Language');
define('TEXT_CHANGE_PASWORD','Change Password');
define('TEXT_LOGOUT','Logout');
define('TEXT_CHECK_UPDATES','Check for Updates');
define('TEXT_GET_PRO','Get Pro Version');
define('TEXT_ENEWS','eNews & Marketing');


// header text in includes/header.php
define('HEADER_TITLE_ACCOUNT', 'My Account/Password');
define('HEADER_TITLE_LOGOFF', 'Log Off');
define('TEXT_SELECT_LANGUAGE', 'Please select Admin language for this session ');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'My Account');

//MARKETING BOX
define('BOX_HEADING_MARKETING', 'Marketing');
define('BOX_MARKETING_EVENTS_MANAGER', 'Events Manager');
define('BOX_MARKETING_SPECIALS', 'Specials');
define('BOX_MARKETING_SPECIALSBYCAT','Specials By Category');
define('BOX_MARKETING_BANNER_MANAGER','Banner Manager');
define('BOX_EDIT_HTML_INVOICE','HTML Invoice');
// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Administrator');
define('BOX_ADMINISTRATOR_MEMBERS', 'Admin Members');
define('BOX_ADMINISTRATOR_GROUPS', 'Admin Groups');
define('BOX_ADMINISTRATOR_MEMBER', 'Members');
define('BOX_ADMINISTRATOR_BOXES', 'Menu File Access');
define('BOX_ADMINISTRATOR_ACCOUNT_UPDATE', 'Update Account');
define('BOX_ADMINISTRATOR_SECURITY', 'Admin File Security');
define('BOX_ABANDONED_ORDERS', 'Abandoned Orders');
define('BOX_CREATE_ACCOUNT', 'Create New Account');
define('BOX_CREATE_ORDER', 'Create New Order');
define('BOX_CREATE_ORDERS_ADMIN', 'Create Orders Admin');
// images
define('IMAGE_FILE_PERMISSION', 'File Permission');
define('IMAGE_GROUPS', 'Groups List');
define('IMAGE_INSERT_FILE', 'Insert File');
define('IMAGE_MEMBERS', 'Members List');
define('IMAGE_NEW_GROUP', 'New Group');
define('IMAGE_NEW_MEMBER', 'New Member');
define('IMAGE_NEXT', 'Next');

// constants for use in tep_prev_next_display function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> filenames)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> members)');
//Admin end

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'

// this  is used to switch between US and UK formates
if (ENGLISH_LANGAUGE_SET == 'uk'){
  @setlocale(LC_TIME, 'en_UK.ISO_8859-1'); // #Credits to Brian Sim (aka Simmy) http://forums.oscommerce.com/index.php?showtopic=129520&st=0&p=520992&##
  define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
  define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
  define('DATE_FORMAT', 'd/m/Y'); // this is used for date()
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
  ////
  // Return date in raw format GB Format
  // $date should be in format mm/dd/yyyy
  // raw date is in format YYYYMMDD, or DDMMYYYY

  function tep_date_raw($date, $reverse = false) {
    if ($reverse) {
      return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
    }
  }

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
  define('LANGUAGE_CURRENCY', 'GBP');

//  define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

  define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 21/05/1970)</span>');
  define('ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 21/05/1970)');

  define('ENTRY_SUBURB', 'Address Line 2:');
  define('ENTRY_SUBURB_ERROR', '<span class="errorText">&nbsp;Suburb cannot be blank.</span>');
  define('ENTRY_SUBURB_TEXT', '');

  define('ENTRY_POST_CODE', 'Post Code:');
  define('ENTRY_POST_CODE_ERROR', '<span class="errorText">&nbsp;Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.</span>');

  define('ENTRY_STATE', 'County/State:');
  define('ENTRY_STATE_ERROR', '<span class="errorText">&nbsp;County/State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.</span>');
  define('ENTRY_STATE_ERROR_SELECT', '<span class="errorText">&nbsp;Please select a county/state from the County/State pull down menu.</span>');

} else {

  @setlocale(LC_TIME, 'en_US.ISO_8859-1');
  define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
  define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

  //function tep_date_raw($date, $reverse = false) {

  function tep_date_raw($date, $reverse = false) {
    if ($reverse) {
      return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
    }
  }

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
  define('LANGUAGE_CURRENCY', 'USD');

  // text for date of birth example
  define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

  define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 05/21/1970)</span>');
  define('ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 05/21/1970)');

  //US format
  define('ENTRY_SUBURB', 'Suburb:');
  define('ENTRY_SUBURB_ERROR', '<span class="errorText"></span>');
  define('ENTRY_SUBURB_TEXT', '');

  // US format
  define('ENTRY_POST_CODE', 'Zip Code:');
  define('ENTRY_POST_CODE_ERROR', '<span class="errorText">&nbsp;Zip Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.</span>');

  define('ENTRY_STATE', 'State/Province:');
  define('ENTRY_STATE_ERROR', '<span class="errorText">&nbsp;State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.</span>');
  define('ENTRY_STATE_ERROR_SELECT', '<span class="errorText">&nbspPlease select a state from the States pull down menu.</span>');

}

//#################################
// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="en"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', 'Name Badges International | The Professional Choice in Personalised Name Badges for Staff - custom designed name tags');

// CCGV
define('BOX_HEADING_GV_ADMIN', 'Vouchers/Coupons');
define('BOX_GV_ADMIN_QUEUE', 'Gift Voucher Queue');
define('BOX_GV_ADMIN_MAIL', 'Mail Gift Voucher');
define('BOX_GV_ADMIN_SENT', 'Gift Vouchers Sent');
define('BOX_COUPON_ADMIN','Coupon Admin');
define('BOX_GV_REPORT','Gift Voucher Report');
define('IMAGE_RELEASE', 'Redeem Gift Voucher');

define('_JANUARY', 'January');
define('_FEBRUARY', 'February');
define('_MARCH', 'March');
define('_APRIL', 'April');
define('_MAY', 'May');
define('_JUNE', 'June');
define('_JULY', 'July');
define('_AUGUST', 'August');
define('_SEPTEMBER', 'September');
define('_OCTOBER', 'October');
define('_NOVEMBER', 'November');
define('_DECEMBER', 'December');

define('TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> gift vouchers)');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> coupons)');
define('BOX_HEADING_CONTENT',' Content Manager');

define('TEXT_VALID_PRODUCTS_LIST', 'Products List');
define('TEXT_VALID_PRODUCTS_ID', 'Products ID');
define('TEXT_VALID_PRODUCTS_NAME', 'Products Name');
define('TEXT_VALID_PRODUCTS_MODEL', 'Products Model');

define('TEXT_VALID_CATEGORIES_LIST', 'Categories List');
define('TEXT_VALID_CATEGORIES_ID', 'Category ID');
define('TEXT_VALID_CATEGORIES_NAME', 'Category Name');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Admin');
define('HEADER_TITLE_SUPPORT_SITE', 'Support Site');
define('HEADER_TITLE_ONLINE_CATALOG', 'Catalog');
define('HEADER_TITLE_ADMINISTRATION', 'Admin');
define('HEADER_TITLE_CHAINREACTION', 'Chainreactionweb');
define('HEADER_TITLE_CRELOADED', 'AJ PARKES');

// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
define('BOX_CATALOG_DEFINE_MAINPAGE', 'Define MainPage');
define('BOX_CATALOG_DEFINE_HOMEPAGE', 'Define HomePage');
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuration');
define('BOX_CONFIGURATION_MYSTORE', 'My Store');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// added for super-friendly admin menu:
define('BOX_CONFIGURATION_MIN_VALUES', 'Min Values');
define('BOX_CONFIGURATION_MAX_VALUES', 'Max Values');
define('BOX_CONFIGURATION_IMAGES', 'Image Configuration');
define('BOX_CONFIGURATION_CUSTOMER_DETAILS', 'Customer Details');
define('BOX_CONFIGURATION_SHIPPING', 'Default Shipping Settings');
define('BOX_CONFIGURATION_PRODUCT_LISTING', 'Product Listing');
define('BOX_CONFIGURATION_EMAIL', 'Email');
define('BOX_CONFIGURATION_DOWNLOAD', 'Download Manager');
define('BOX_CONFIGURATION_GZIP', 'GZip');
define('BOX_CONFIGURATION_SESSIONS', 'Sessions');
define('BOX_CONFIGURATION_STOCK', 'Stock Control');
define('BOX_CONFIGURATION_WYSIWYG', 'WYSIWYG Editor 1.7');
define('BOX_CONFIGURATION_AFFILIATE', 'Configuration');
define('BOX_CONFIGURATION_MAINT', 'Site Maintenance');
define('BOX_CONFIGURATION_ACCOUNTS', 'Purchase Without Account');
define('BOX_CONFIGURATION_CHECKOUT', 'Checkout Settings');
define('BOX_CONFIGURATION_LINKS', 'Links Manager');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Payment');
define('BOX_MODULES_SHIPPING', 'Shipping');
define('BOX_MODULES_ORDER_TOTAL', 'Order Total');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Product Catalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Product Attributes');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_CATEGORY_OPTIONS', 'Product Category Options');
define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');
define('BOX_CATALOG_REVIEWS', 'Reviews');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');
define('BOX_CATALOG_EASYPOPULATE', 'Easy Populate');
define('BOX_CATALOG_EASYPOPULATE_BASIC', 'Easy Populate Basic');
define('BOX_CATALOG_SHOP_BY_PRICE', 'Shop by Price');
define('BOX_CATALOG_FEATURED', 'Featured Products');
define('BOX_CATALOG_PRODUCTS', 'Products');
define('BOX_CONFIGURATION_ADMINISTRATORS', 'Administrators');
define('BOX_CONFIGURATION_SERVICES', 'Services');
define('BOX_CONFIGURATION_CREDIT_CARDS', 'Credit Cards');
define('BOX_CATALOG_SPECIALS', 'Specials');
define('BOX_CATALOG_CATEGORIES', 'Categories');
define('BOX_HEADING_SETUP', '<b>Set Up</b>');
define('BOX_HEADING_MARKETING_MANAGER', '<b>Marketing Manager</b>');
define('BOX_HEADING_CATALOG_MANAGER', '<b>Catalog Manager</b>');
define('BOX_HEADING_CONTENT_MANAGER', '<b>Content Manager</b>');
define('BOX_HEADING_TEMPLATE_MANAGER', '<b>Template Manager</b>');
define('BOX_HEADING_CUSTOMERS_ORDERS', '<b>Customers/Orders</b>');
define('BOX_HEADING_ORDERS', 'Orders');
define('TEXT_HEADING_HOME', 'Home');
define('TEXT_HEADING_ORDERS', 'Orders');
define('TEXT_HEADING_CUSTOMERS', 'Customers');
define('TEXT_HEADING_CATALOG', 'Products');
define('TEXT_HEADING_CONTENT', 'Content');
define('TEXT_HEADING_MARKETING', 'Marketing');
define('TEXT_HEADING_CONFIGURATION', 'Configuration');

// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Customers/Orders');
define('BOX_CUSTOMERS_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_PENDING_APPROVALS', 'Pending Customers');
define('BOX_CRE_MARKETPLACE', 'Marketplace');
define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_CUSTOMERS_EDIT_ORDERS', 'Edit Orders');
// taxes box text in includes/boxes/taxes.php

define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations/Taxes');
define('BOX_TAXES_COUNTRIES', 'Countries');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_GEO_ZONES', 'Tax Zones');
define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');
define('BOX_TAXES_TAX_RATES', 'Tax Rates');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Top Viewed Products');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Top Purchased Products');
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Order Totals');
define('BOX_REPORTS_BOX_REPORTS_MONTHLY_SALES', 'Monthly Sales');
define('BOX_REPORTS_CREDITS', 'Customer Credit');
define('BOX_REPORTS_COUPONS_REDEEMED','Coupons Redeemed');
define('BOX_REPORTS_CUSTOMER_WISHLIST', 'Customer Wishlist');
define('BOX_REPORTS_SALES_REPORT2', 'Sales Report');
define('BOX_REPORTS_ORDERLIST', 'Generate Order List');
define('BOX_REPORTS_MONTHLY_SALES', 'Monthly Sales/GST - Tax');
define('BOX_REPORTS_CUSTOMERS_ORDERS', 'Customer Statistics');
define('BOX_REPORTS_DAILY_PRODUCTS_ORDERS', 'Daily Product Sales');
define('BOX_REPORTS_PRODUCTS_NOTIFICATIONS', 'Product Notifications');
define('BOX_REPORTS_NOT_VALID_USER', 'Customers Not Validated');  
define('BOX_CUSTOMERS_MENU','Customer Menu');
define('BOX_ORDERS_MENU','Orders Menu');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Tools');
define('BOX_HEADING_B2BSETTINGS', 'B2B Settings');
define('BOX_TOOLS_BACKUP', 'Database Backup');
define('BOX_TOOLS_FILE_MANAGER', 'File Manger');
define('BOX_TOOLS_IMAGE_MANAGER', 'Image Manager');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');
define('BOX_TOOLS_CACHE', 'Cache Control');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Language Editor');
define('BOX_TOOLS_MAIL', 'Send Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');
define('BOX_TOOLS_SERVER_INFO', 'Server Info');
define('BOX_TOOLS_WHOS_ONLINE', 'Who\'s Online');

// BMC CC Mod Start
define('BOX_TOOLS_BLACKLIST', 'Credit Card Blacklist');
// BMC CC Mod End
// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Localization');
define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');
define('BOX_LOCALIZATION_LANGUAGES', 'Languages');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Orders Status');
// header_tags_controller text in includes/boxes/header_tags_controller.php

define('BOX_HEADING_HEADER_TAGS_CONTROLLER', 'Header Meta Tags');
define('BOX_HEADER_TAGS_ADD_A_PAGE', 'Add Remove Pages');
define('BOX_HEADER_TAGS_ENGLISH', 'Edit Tags');
define('BOX_HEADER_TAGS_FILL_TAGS', 'Fill Tags');

// infobox box text in includes/boxes/info_boxes.php
define('BOX_HEADING_DESIGN_CONTROLS', 'Design Controls');
define('BOX_HEADING_DESIGN_TEMPLATE', 'Template');
define('BOX_HEADING_TEMPLATE_CONFIGURATION', 'Template Manager');
define('BOX_HEADING_TEMPLATE_MANAGEMENT', 'Template Admin');
define('BOX_HEADING_TEMPLATE_MANAGEMENT1', 'New Manage Templates');
define('BOX_HEADING_DESIGN_INFOBOX', 'Infobox');
define('BOX_HEADING_BOXES', 'Infobox Configure');
define('BOX_HEADING_BOXES_ADMIN', 'Infobox Manager');
define('BOX_HEADING_DESIGN_BRANDING', 'Branding');
define('BOX_HEADING_TEMPLATE_HEADER_TAGS','Header Tags');
define('BOX_HEADING_DESIGN_PRODUCT_INFO','Product Info');
define('BOX_HEADING_DESIGN_PRODUCT_INFO_CONFIG','Product Info Config');
define('BOX_HEADING_PRODUCT_INFO_CONFIGURATION','Product Info');

define('BOX_HEADING_ADMIN_MENU_BUILDER', 'Admin Menu Builder');
define('BOX_HEADING_ADMIN_MENU', 'Admin Menu System');
define('BOX_HEADING_DESIGN_LAYOUT', 'Layout');
define('BOX_HEADING_DESIGN_PRODUCT_LISTING', 'Product Listing');
define('BOX_HEADING_DESIGN_HOME_PAGE', 'Home Page');
define('BOX_HEADING_DESIGN_INDEX_PAGE', 'Index Page');
define('BOX_HEADING_DESIGN_PRODUCT_PAGE', 'Product Page');

define('BOX_TEMPLATE_NAVMENU','Navigation Manager');
define('BOX_HEADING_BRANDING_MANAGER','Branding Manager');


// VJ Links Manager v1.00 begin
// links manager box text in includes/boxes/links.php
define('BOX_HEADING_LINKS', 'Links Manager');
define('BOX_LINKS_LINKS', 'Links');
define('BOX_LINKS_LINK_CATEGORIES', 'Link Categories');
define('BOX_LINKS_LINKS_CONTACT', 'Links Contact');
// VJ Links Manager v1.00 end

// javascript messages
define('JS_ERROR', 'Errors have occurred during the process of your form!\nPlease make the following corrections:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* The new product atribute needs a price value\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product atribute needs a price prefix\n');

define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');

define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry is must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');
define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');
define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' and \'Confirmation\' entries must match and have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');
/* User Friendly Admin Menu */
define('CATALOG_CATEGORIES', 'Categories');
define('CATALOG_ATTRIBUTES', 'Product Attributes');
define('CATALOG_REVIEWS', 'Product Reveiws');
define('CATALOG_SPECIALS', 'Specials');
define('CATALOG_EXPECTED', 'Products Expected');
define('REPORTS_PRODUCTS_VIEWED', 'Veiwed Products');
define('REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('TOOLS_FILE_MANAGER', 'File Manager');
define('TOOLS_CACHE', 'Cache Control');
define('TOOLS_DEFINE_LANGUAGES', 'Define Languages');
define('TOOLS_EMAIL', 'Email Customers');
define('TOOLS_NEWSLETTER', 'Newsletters');
define('TOOLS_SERVER_INFO', 'Server Info');
define('TOOLS_WHOS_ONLINE', 'Who\'s Online');
define('BOX_HEADING_GV', 'Coupon/Voucher');
define('GV_COUPON_ADMIN', 'Discount Coupons');
define('GV_EMAIL', 'Send Gift Voucher');
define('GV_QUEUE', 'Gift Voucher Redeem');
define('GV_SENT', 'Gift Voucher\'s Sent');
define('BOX_GV_SENT', 'Gift Voucher\'s Sent');
/* User Friedly Admin Menu */

define('CATEGORY_PERSONAL', 'Your Personal Details');
define('CATEGORY_ADDRESS', 'Your Address');
define('CATEGORY_CONTACT', 'Your Contact Information');
define('CATEGORY_OPTIONS', 'Options');
define('CATEGORY_PASSWORD', 'Your Password');

define('ENTRY_COMPANY', 'Company Name');
define('ENTRY_COMPANY_ERROR','<span class="errorText">&nbsp;Company Name must contain a minimum of ' . ENTRY_COMPANY_MIN_LENGTH .' characters.</span>');
define('ENTRY_COMPANY_TEXT', '*');
define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', '<span class="errorText">&nbsp;Please select your Gender.</span>');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_FIRST_NAME_ERROR', '<span class="errorText">&nbsp;First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.</span>');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', '<span class="errorText">&nbsp;Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.</span>');
define('ENTRY_LAST_NAME_TEXT', '*');

define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');

define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '<span class="errorText">&nbsp;E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '<span class="errorText">&nbsp;E-Mail Address does not appear to be valid - please make any necessary corrections.</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '<span class="errorText">&nbsp;E-Mail Address already exists in our records.</span>');
define('ENTRY_EMAIL_CHECK_ERROR', '<span class="errorText">There has been an error sending this email! Please contact the store owner.</span>');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', '<span class="errorText">&nbsp;Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.</span>');
define('ENTRY_STREET_ADDRESS_TEXT', '*');

define('ENTRY_POST_CODE_TEXT', '*');

define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', '<span class="errorText">&nbsp;City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.</span>');
define('ENTRY_CITY_TEXT', '*');

//GB format
define('ENTRY_STATE_TEXT', '*');

define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '<span class="errorText">&nbsp;Telephone Number must contain a minimum of ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.</span>');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_ERROR', '<span class="errorText">&nbsp;Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.</span>');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', '<span class="errorText">&nbsp;The Password Confirmation must match your Password.</span>');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Current Password:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', '<span class="errorText">&nbsp;Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.</span>');
define('ENTRY_PASSWORD_NEW', 'New Password:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', '<span class="errorText">&nbsp;New Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.</span>');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your new Password.');
define('PASSWORD_HIDDEN', '--HIDDEN--');

define('FORM_REQUIRED_INFORMATION', '* Required information');
define('CATEGORY_ORDER_DETAILS', 'Customer Details');
define('ENTRY_CURRENCY', 'Customer Currency');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
define('IMAGE_BACK', 'Back');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_BUTTON_CONTINUE', 'Continue');
define('IMAGE_CONFIRM', 'Confirm');
define('IMAGE_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_EDIT', 'Edit');
define('IMAGE_EDIT_STATUS', 'Edit Order Status');
define('IMAGE_EDIT_ORDER', 'Edit Order');
define('IMAGE_EDIT_LANG_DEFINE', 'Edit Language Defines');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FILE_MANAGER', 'File Manager');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_MODULE_INSTALL', 'Install Module');
define('IMAGE_MODULE_REMOVE', 'Remove Module');
define('IMAGE_MOVE', 'Move');
define('IMAGE_NEW_BANNER', 'New Banner');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_SALE', 'New Sale');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_NEW_ZONE', 'New Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('IMAGE_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Save');
define('IMAGE_SEARCH', 'Search');
define('IMAGE_SELECT', 'Select');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('IMAGE_UNLOCK', 'Unlock');
define('IMAGE_UPDATE', 'Update');
define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');
define('IMAGE_UPLOAD', 'Upload');

define('ICON_CROSS', 'False');
define('ICON_CURRENT_FOLDER', 'Current Folder');
define('ICON_DELETE', 'Delete');
//added for quick product edit DMG
define('ICON_EDIT','Edit');
define('ICON_ERROR', 'Error');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Folder');
define('ICON_LOCKED', 'Locked');
define('ICON_PREVIOUS_LEVEL', 'Previous Level');
define('ICON_PREVIEW', 'Preview');
define('ICON_STATISTICS', 'Statistics');
define('ICON_SUCCESS', 'Success');
define('ICON_TICK', 'True');
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products expected)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_SALES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> sales)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'Site default');
define('TEXT_SET_DEFAULT', 'Set as site default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: There is currently no default currency set. Please set one at: Administration Tool->Localization->Currencies');

define('TEXT_NONE', '--none--');
define('TEXT_TOP', 'Top');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destination does not exist.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: Destination not writeable.');
define('ERROR_FILE_NOT_SAVED', 'Error: File upload not saved.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: File upload type not allowed.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: File upload saved successfully.');
define('WARNING_NO_FILE_UPLOADED', 'Warning: No file uploaded.');
define('WARNING_FILE_UPLOADS_DISABLED', 'Warning: File uploads are disabled in the php.ini configuration file.');
define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warning: Installation directory exists at: ' . DIR_FS_CATALOG . 'install. Please remove this directory for security reasons.');
define('WARNING_UPGRADES_DIRECTORY_EXISTS', 'Warning: Upgrades directory exists at: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/upgrades. Please remove this directory for security reasons.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');
define('WARNING_CONFIG_FILE_WRITEABLE_CATALOG', 'Warning: I am able to write to the configuration file: ' . DIR_FS_CATALOG . 'includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warning: The sessions directory does not exist: ' . tep_session_save_path() . '. Sessions will not work until this directory is created.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: I am not able to write to the sessions directory: ' . tep_session_save_path() . '. Sessions will not work until the right user permissions are set.');
define('WARNING_SESSION_AUTO_START', 'Warning: session.auto_start is enabled - please disable this php feature in php.ini and restart the web server.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warning: The downloadable products directory does not exist: ' . DIR_FS_CATALOG . 'download/' . '. Downloadable products will not work until this directory is valid.');
define('WARNING_ENCRYPT_FILE_MISSING', 'Warning: The Encryption key file is missing.');
define('WARNING_TMP_DIRECTORY_NON_EXISTENT', 'Warning: The tmp/ is not writable: ' . DIR_FS_CATALOG . 'tmp/' . '. The page cacheing will not work until this directory is writable.');
define('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'NOTICE: The web site is currently Down For Maintenance to the public.');
define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross-Sell Products'); // X-Sell

define('TEXT_CSCAL_ERROR_CARD_TYPE_MISMATCH', 'Error: 01 The Credit Card number does not match the Card Type:');
define('TEXT_CCVAL_ERROR_INVALID_MONTH', 'Error: 02 The expiry date Motnth entered for the credit card is invalid.Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_YEAR', 'Error: 03 The expiry date year entered for the credit card is invalid.Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_DATE', 'Error: 04 The expiry date entered for the credit card is invalid.Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'Error: 05 The credit card number entered is invalid. Please check the number for misstyped numbers and try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Error: 06 The first four digits of the number entered are: %s If that number is correct, we do not accept that type of credit card.If it is wrong, please try again.');
define('TEXT_CCVAL_ERROR_NOT_ACCEPTED', 'Error: 07 The credit card number you have entered appears to be a %s card. Unfortunately at this time we do not accept %s as payment.');
define('TEXT_CCVAL_ERROR_BLACKLIST', 'Error: 08 We cannot accept your card as it is blacklisted, if you feel you have recieved this message in error please contact your card issuer.');
define('TEXT_CCVAL_ERROR_SHORT', 'Error: 09 You have not entered the correct amount of digits. Please ensure you have entered all of the long number displayed on the front of your card');
define('TEXT_CCVAL_ERROR_CVV_LENGTH', 'Error: 10 The CCV/CVV/CID number entered is the incorrect length. Please try again.');

define('IMAGE_BUTTON_PRINT_ORDER', 'Order Printable');
define('TEXT_PROCESS','Process');
// BOF: Lango Added for print order MOD
define('IMAGE_BUTTON_PRINT', 'Print');
// EOF: Lango Added for print order MOD


// BOF: Lango Added for template MOD
// WebMakers.com Added: Attribute Sorter, Copier and Catalog additions
require(DIR_WS_LANGUAGES . $language . '/' . 'attributes_sorter.php');

//DWD Modify: Information Page Unlimited 1.1f - PT
  define('BOX_HEADING_INFORMATION', 'Content Manager');
  define('BOX_INFORMATION_MANAGER', 'Info Manager');
//DWD Modify End

include('includes/languages/order_edit_english.php');

 define('BOX_TITLE_CRELOADED', 'CRE Loaded Project');
 define('LINK_CRE_FORUMS','CRE Loaded Forums');
 define('LINK_CRW_SUPPORT','Technical Support');
// General Release Edition
 define('LINK_SF_CRELOADED','Source Forge Home');
 define('LINK_SF_BUGTRACKER','Bug Tracker');
 define('LINK_SF_SUPPORT','Support Request');
 define('LINK_SF_TASK','Task Tracker');
 define('LINK_SF_CVS','Browse CVS');
 define('LINK_CRE_FILES','CRE Downloads');
 define('LINK_SF_FEATURE','Feature Request');
//included for Backup mySQL (courtesy Zen-Cart Team) DMG
define('BOX_TOOLS_MYSQL_BACKUP','Backup mySQL');
define('BOX_B2BSETTINGS_STORE_SETTINGS','Store Settings');
define('BOX_B2BSETTINGS_CUSTOMERS_GROUPS','Customer Groups');

// Included for Events Calendar 2.0 DMG
define('IMAGE_NEW_EVENT', 'New Event');

// VJ member approval added
define('BOX_CUSTOMERS_APPROVAL', 'Waiting Approval');

//DMG FAQ System 2.1
  define('BOX_HEADING_FAQ', 'FAQ System');
  define('BOX_FAQ_MANAGER', 'FAQ Manager');
  define('BOX_FAQ_CATEGORIES', 'FAQ Categories');
  define('BOX_FAQ_VIEW', 'FAQ View');
  define('BOX_FAQ_VIEW_ALL', 'FAQ View All');


// DMG Article Manager
define('BOX_HEADING_ARTICLES', 'Article Manager');
define('BOX_TOPICS_ARTICLES', 'Topics/Articles');
define('BOX_ARTICLES_CONFIG', 'Configuration');
define('BOX_ARTICLES_AUTHORS', 'Authors');
define('BOX_ARTICLES_REVIEWS', 'Article Reviews');
define('BOX_ARTICLES_XSELL', 'Cross-Sell Articles');
define('IMAGE_NEW_TOPIC', 'New Topic');
define('IMAGE_NEW_ARTICLE', 'New Article');
define('TEXT_DISPLAY_NUMBER_OF_AUTHORS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> authors)');
define('IMAGE_NEW_AUTHOR', 'New Author');
define('TEXT_WARNING_NO_AUTHORS', 'WARNING:  Empty Authors Table!&nbsp;&nbsp;You MUST add at least one Author before you will be able to add any Articles');

// Article Statistics Report DMG
  define('BOX_REPORTS_ARTICLES_VIEWED', 'Top Viewed Articles');
  define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');

// DMG :  Mulitple Products Manager

define('BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI', 'Multi Products Manager');

// START: Product Extra Fields DMG
  define('BOX_CATALOG_PRODUCTS_EXTRA_FIELDS', 'Product Extra Fields');
// END: Product Extra Fields DMG

// Contact US Email Subject DMG
define('BOX_TOOLS_EMAIL_SUBJECTS', 'Email Subjects');
define('TEXT_DISPLAY_NUMBER_OF_EMAIL_SUBJECTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> email subjects)');

define('BOX_REPORTS_EXPLAIN','osC Explain Queries');

//define('BOX_HEADING_CRYPT', 'Encryption and Decryption');
define('BOX_HEADING_CRYPT', 'Encrypt & Decrypt');
define('BOX_CRYPT_CONFIGURATION', 'Configuration');
define('BOX_CRYPT_TEST', 'Test');
define('BOX_CRYPT_CC_DATA', 'CC Data');
define('BOX_CRYPT_CONVERT', '&nbsp; Convert CC Data');
define('BOX_CRYPT_PURGE', 'Purge CC Data');
define('BOX_CRYPT_UPDATE', 'Update CC Data');
define('BOX_CRYPT_KEYS', 'Manage Keys');
define('BOX_CRYPT_HELP', 'Help');

define('IMAGE_CONVERT', 'Convert info to new key');
define('IMAGE_ENCRYPT', 'Encrypt data');
define('IMAGE_DECRYPT', 'Decrypt data');
define('IMAGE_RETURN', 'Return to main');
define('IMAGE_EDIT_KEY', 'Edit Key File');
define('IMAGE_CREATE', 'Create key');
define('IMAGE_HELP', 'Help');
define('IMAGE_RUN', 'Run');
  define('BOX_DATA_EASYPOPULATE_BASIC', 'Easy Populate Basic');
  define('BOX_DATA_EASYPOPULATE', 'Easy Populate Advance');
  define('BOX_DATA_EASYPOPULATE_EXPORT', 'EPA Export');
  define('BOX_DATA_EASYPOPULATE_IMPORT', 'EPA Import');
  define('BOX_DATA_EASYPOPULATE_BASIC_EXPORT', 'EPB Export');
  define('BOX_DATA_EASYPOPULATE_BASIC_IMPORT', 'EPB Import');
  define('BOX_DATA_EASYPOPULATE_OPTIONS_EXPORT', 'Options Export');
  define('BOX_DATA_EASYPOPULATE_OPTIONS_IMPORT', 'Options Import');
  define('BOX_HEADING_DATA', 'Data Manager');
  define('BOX_DATA', 'Data Feeds');
  define('BOX_DATA_ADMIN', 'Data Configure');
  define('BOX_HEADING_FEEDERS', 'Feeder Systems');
  define('BOX_DATA_HELP', 'Data Help');
  define('BOX_FEEDERS_AMAZON', 'Amazon Marketplace');
  define('BOX_FEEDERS_BIZRATE', 'Biz Rate');
  define('BOX_FEEDERS_GOOGLE', 'Google Base');
  define('BOX_FEEDERS_MYSIMON', 'MySimon');
  define('BOX_FEEDERS_PRICE_GRABBER', 'Price Grabber');
  define('BOX_FEEDERS_SHOPPING', 'Shopping.com');
  define('BOX_FEEDERS_YAHOO', 'Yahoo');

define('BOX_HEADING_DOC', 'Documentation');
define('BOX_DOC_ADMIN', 'Admin');
define('BOX_DOC_CAT', 'Catalog');
define('BOX_DOC_MISC', 'Miscellaneous');
// Eversun mod for sppc and qty price breaks
define('ENTRY_CUSTOMERS_GROUP_NAME', 'Customer Price Group:');
define('BOX_CUSTOMERS_GROUPS', 'Customers Groups');
define('ENTRY_COMPANY_TAX_ID', 'Customer number:');
//define('ENTRY_COMPANY_TAX_ID', 'Ajparkes Customer number:');
define('ENTRY_COMPANY_TAX_ID_ERROR', '<span class="errorText"></span>');
define('ENTRY_CUSTOMERS_GROUP_REQUEST_AUTHENTICATION', 'Switch off alert for authentication:');
define('ENTRY_CUSTOMERS_GROUP_RA_NO', 'Alert off');
define('ENTRY_CUSTOMERS_GROUP_RA_YES', 'Alert on');
define('ENTRY_CUSTOMERS_GROUP_RA_ERROR', '');
// Eversun mod end for sppc and qty price breaks
define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date("Y") . '&nbsp;Ajparkes</a>, Powered by <a target="_blank" href="http://www.archercreative.com.au">Archer Creative - eCommerce Solution</a>');
// VJ infosystem begin
define('BOX_HEADING_PAGE_MANAGER', 'Page Manager');
define('BOX_PAGES', 'Pages');
define('BOX_PAGES_CATEGORIES', 'Categories');
// VJ infosystem end

define('BOX_SHIPWIRE', 'ShipWire');
define('BOX_MODULES_CHECKOUT_SUCCESS', 'Checkout Success');
define('BOX_MODULES_ADDONS', 'Add-Ons');

define('BOX_HEADING_TECH_SUPPORT','Tech Support');
define('BOX_HEADING_INSTALL_EXPLAIN','Explain Quires');
define('IMAGE_BUTTON_BACK','Back');
define('IMAGE_BUTTON_CONFIRM','Confirm');

if(file_exists('includes/languages/english_newsdesk.php')) {
include('includes/languages/english_newsdesk.php');
include('includes/languages/english_faqdesk.php');
}

// labels for Tools>Cache Control
define('TEXT_CACHE_CATEGORIES', 'Categories Box');
define('TEXT_CACHE_CATEGORIES1', 'Categories Box 1');
define('TEXT_CACHE_CATEGORIES2', 'Categories Box 2');
define('TEXT_CACHE_CATEGORIES3', 'Categories Box 3');
define('TEXT_CACHE_CATEGORIES4', 'Categories Box 4');
define('TEXT_CACHE_CATEGORIES5', 'Categories Box 5');
define('TEXT_CACHE_ALLPROD', 'All Produces');
define('TEXT_CACHE_ALLMANUF', 'All Manufactures');
define('TEXT_CACHE_ALLCATS', 'All Categories');
define('TEXT_CACHE_MANUFACTURERS', 'Manufacturers Box');
define('TEXT_CACHE_ALSO_PURCHASED', 'Also Purchased Module');
define('TEXT_CACHE_COOLMENU', 'Cool Menu');

define('NON_TTF_FONT_ERROR','Non-TTF font size must be 1,2,3,4 or 5');
define('SETLEGEND_ERROR','Error: SetLegend argument must be an array');

define('UNABLE_TO_OPEN_ERROR','Unable to open ');
define('UNABLE_TO_OPEN_GIF_ERROR',' as a GIF');
define('UNABLE_TO_OPEN_PNG_ERROR',' as a PNG');
define('UNABLE_TO_OPEN_JPG_ERROR',' as a JPG');
define('SELECT_IMAGE_ERROR','Please select wbmp,gif,jpg, or png for image type!');
define('SELECT_IMAGE_TYPE_ERROR','Please select an image type!');
define('NOT_ACCEPTABLE_PLOT_TYPE_ERROR',' not an acceptable plot type');
define('UNKNOWN_CHART_TYPE_ERROR','ERROR: unknown chart type');
define('LOG_PLOTS_DATA_GREATER_ERROR','Log plots need data greater than 0');
define('ERROR_IN_DATA','Error in Data - max not gt min');
define('FATAL_ERROR','Fatal error');
define('THINBARLINES_DATA_TYPE_ERROR','Data Type for ThinBarLines must be data-data');
define('BAR_PLOTS_DATA_TYPE_ERROR','Bar plots must be text-data: use function SetDataType(\'text-data\')');
define('NO_IMAGE_DEFINED_DRAWGRAPH_ERROR','No Image Defined: DrawGraph');
define('NO_ARRAY_OF_DATA_IN_ERROR','No array of data in ');



##################### 03/07/2006 End  ####################


##################### 04/07/2006 Start  ####################

define('SESSION_FILE_OPEN_ERROR_1','Could not open session file (');
define('SESSION_FILE_OPEN_ERROR_2',').');
define('SESSION_FILE_WRITE_ERROR_1','Could not write session file (');
define('SESSION_FILE_WRITE_ERROR_2',').');
define('CACHING_METHOD_ERROR_1','Caching method ');
define('CACHING_METHOD_ERROR_2',' not implemented.');
define('INITIALIZE_SESSION_MODULE_ERROR','Failed to initialize session module.');
define('SESSION_NOT_SAVED_ERROR','Session could not be saved.');
define('SESSION_NOT_CLOSED_ERROR','Session could not be closed.');
define('SESSION_NOT_STARTED_ERROR','Session could not be started.');


define('CANNOT_COPY_PRODUCT_ERROR_1','<b>WARNING: Cannot copy from Product ID #');
define('CANNOT_COPY_PRODUCT_ERROR_2',' to Product ID # ');
define('CANNOT_COPY_PRODUCT_ERROR_3',' ... No copy was made</b>');
define('NO_ATTRIBUTES_COPY_ERROR_1','<b>WARNING: No Attributes to copy from Product ID #');
define('NO_ATTRIBUTES_COPY_ERROR_2',' for: ');
define('NO_ATTRIBUTES_COPY_ERROR_3',' ... No copy was made</b>');
define('NO_PRODUCT_ERROR_1','<b>WARNING: There is no Product ID #');
define('NO_PRODUCT_ERROR_2',' ... No copy was made</b>');


define('MCRYPT_ALGORITHMS_AND_MODES','Mcrypt Algorithms and Modes');
define('MCRYPT_ALGORITHM','Algorithm');
define('MCRYPT_Status','Status');
define('MCRYPT_OK','OK');
define('MCRYPT_NOT_OK','NOT OK');
define('MCRYPT_NOT_TESTED','NOT TESTED');
define('MCRYPT_MAXIMUM_KEY_SIZES_ALLOWED','Maximum Key Sizes Allowed');
define('MCRYPT_MAXIMUM_KEY_SIZE','Maximum Key Size');
define('MCRYPT_KEY_TEXT','this is a very long key, even too long for the cipher');
define('MCRYPT_PLAIN_TEXT','very important data');

define('DATABASE_TEP_DB_ERROR','[TEP STOP]');

define('CANNOT_CHANGE_THE_MODE_OF_FILE','Cannot change the mode of file');
define('FAILED_TO_OPEN_FILE','Failed to open file ');
define('CANNOT_WRITE_TO_FILE','Cannot write to file ');

define('UNABLE_TO_DETERMINE_PAGE_LINK','<b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>Function used:<br><br>');
define('UNABLE_TO_DETERMINE_CONNECTION_METHOD_ON_PAGE_LINK','<b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL<br><br>Function used:<br><br>');


define('SUPPORT_DESK','Support Desk');

define('FRAUDSCREENCLIENT_AFS','AFS');
define('FRAUDSCREENCLIENT_QUERY_RESULT','query result');
define('FRAUDSCREENCLIENT_SERVER_UNAVAILABLE','Algozone Fraud Screen Server currently unavailable. Please try again later.');
define('FRAUDSCREENCLIENT_AFS_INPUTS','AFS Inputs');
define('FRAUDSCREENCLIENT_INPUT','input');
define('FRAUDSCREENCLIENT_INVALID_INPUT','invalid input');
define('FRAUDSCREENCLIENT_MISSPELLED_FIELD','- perhaps misspelled field?');
define('FRAUDSCREENCLIENT_AFS_USING_CURL','AFS using curl');
define('FRAUDSCREENCLIENT_AFS_CURL_PARAMS','AFS curl params');
define('FRAUDSCREENCLIENT_AFS_CURL_NOT_SUPPORT','<br>error: this version of curl does not support HTTPS try build curl with SSL or specify');
define('FRAUDSCREENCLIENT_AFS_RECEIVED_ERROR_MESSAGE_1','Received error message');
define('FRAUDSCREENCLIENT_AFS_RECEIVED_ERROR_MESSAGE_2','from curl');
define('FRAUDSCREENCLIENT_AFS_CURL_PROXY','<p>using curl thru proxy');
define('FRAUDSCREENCLIENT_AFS_USING_FSOCKOPEN','<p><b>AFS using fsockopen</b>');
define('FRAUDSCREENCLIENT_AFS_SOCKET_PARAM','AFS socket url param');
define('FRAUDSCREENCLIENT_AFS_FSOCKOPEN_PROXY','<p><b>AFS using fsockopen proxy<b><br>');
define('FRAUDSCREENCLIENT_AFS_PROXY_PORT','<br>error: you need to provide the proxy port number to use the proxy port provided');
define('FRAUDSCREENCLIENT_AFS_INSTALL_CURL','<br>error: you need to install curl if you want secure HTTPS or specify the variable to be');
define('FRAUDSCREENCLIENT_AFS_QUERY_RESULTS','<p><b>AFS query results: </b>');
define('FRAUDSCREENCLIENT_AFS_OUTPUT','output');
define('MAP_MSG','<p>Courtesy of the U.S. Census Bureau\'s TIGER Mapping Service');
define('ATTRIBUTES_DISPLAY_MSG','**Discounts may vary based on selected options');

##################### 05/07/2006 End  ####################
##################### 06/07/2006 Start  ####################

define("FEATURE_NOT_PRESENT_TEXT",'This feature has not yet been added, It is a work in progress');


define('INSTALL_EXPLAIN_TXT_1','Install (and Uninstall) Database Settings script for osC-Explain - by Chemo');
define('INSTALL_EXPLAIN_TXT_2','<p><b>Install option selected...running queries</b></p>');
define('INSTALL_EXPLAIN_TXT_3','<p>STEP 1 => Add configuration group</p>');
define('INSTALL_EXPLAIN_TXT_4','<p>Added the configuration group ');
define('INSTALL_EXPLAIN_TXT_5','successfully...adding configuration values</p>');
define('INSTALL_EXPLAIN_TXT_6','<p>STEP 2 => Add configuration settings</p>');
define('INSTALL_EXPLAIN_TXT_7','<blockquote>Success...</blockquote>');
define('INSTALL_EXPLAIN_TXT_8','<p>Added the configuration settings successfully...adding the \'explain_queries\' table</p>');
define('INSTALL_EXPLAIN_TXT_9','<p>STEP 3 => Creating explain_queries table</p>');
define('INSTALL_EXPLAIN_TXT_10','<blockquote>Successfully created the table.</blockquote>');
define('INSTALL_EXPLAIN_TXT_11','<p><b>All done!  You should delete this script from the server...or not...you\'re choice.</b></p>');

define('INSTALL_EXPLAIN_TXT_12','<p><b>Uninstall optin selected...running queries</b></p><p>STEP 1 => Delete the configuration group from configuration_group table</p>');

define('INSTALL_EXPLAIN_TXT_13','<blockquote>Deleted the configuration group successfully...removing configuration values</blockquote><p>STEP 2 => Delete configuraton values</p>');

define('INSTALL_EXPLAIN_TXT_14','<blockquote>Deleted the configuration values successfully...dropping the explain_queries table</blockquote><p>STEP 3 => Dropping explain_queries table</p>');


define('INSTALL_EXPLAIN_TXT_15','<blockquote>Table dropped successfully...analyzing tables</blockquote><p>STEP 4 => Analyzing configuration_group and configuration table</p>');

define('INSTALL_EXPLAIN_TXT_16','<blockquote>Analyze configuration_group success...</blockquote>');

define('INSTALL_EXPLAIN_TXT_17','<blockquote>Analyze configuration success...</blockquote>');

define('INSTALL_EXPLAIN_TXT_18','<blockquote>Optimize configuration_group success...</blockquote>');

define('INSTALL_EXPLAIN_TXT_19','<blockquote>Optimize configuration success...</blockquote><p><b>All done!  You should delete this script from the server...or not...you\'re choice.</b></p>');

define('INSTALL_EXPLAIN_TXT_20','<p>Welcome to the barebones osC-Explain installation script (<a href="http://forums.oscommerce.com/index.php?showuser=9196">by Chemo</a>)!</p><p>This contribution is GPL and the target audience is fellow coders, optimizers,   and knowledgeable webmasters. I encourage each of you to look over the   code and add functionality so that the rest of us can benefit as well.</p><p>There are two options for this script:</p><p><strong>INSTALL</strong></p><blockquote>  <p>This option is self explanatory :) It will add a configuration group     with the title &quot;Explain Queries&quot; and set the sort order to 99 (making     it the last listed). The script will then add values to the configuration     table that is the options for this contribution. Currently, these are     available:</p>  <ul>    <li> Global on / off</li>    <li>Enable on for specific scripts (add one or list separated by comma).       This will be handy for contribution coders since they can enable only for       their development scripts and not waste room for storing other page queries.       In addition, it will speed up the admin report if there are 1,000 rows instead       of 500,000 :)</li>    <li>Enable page exclusion for specific scripts. This is handy to exclude       certain scripts (for instance, ones already optimized) but capture the rest.</li>  </ul>  <p>The last thing this install script does is add a new table called \'explain_queries\'.    This is needed to store the data. Do not change the name since the table     name is hardcoded all over the place. Why not add a new define to filenames.php?     If there is room to trim install steps and decrease the number of file changes     I\'ll take it any day of the week and twice on Sunday. You are (hopefully)     an experienced osC developer so if you want to do define table names the standard     way edit your own files.</p></blockquote><p align="center"><strong><a href="'.$PHP_SELF.'?action=install">INSTALL</strong> THE DATABASE VALUES FOR OSC-EXPLAIN</a></p><p align="left"><strong>UNINSTALL</strong></p><blockquote>  <p align="left">Hopefully, this option is self-explanatory too :) This     will delete all the values associated with osC-Explain from the configuration_group     and configuration tables. Then it will analyze the tables to reset the     cardinality of the tables. Next, the script will drop the \'explain_queries\'     table.</p></blockquote><p align="center"><strong><a href="'.$PHP_SELF.'?action=delete">UNINSTALL</strong> THE DATABASE VALUES FOR OSC-EXPLAIN</a></p><p align="left"><strong>NOTES</strong>: By default all values are set to false.   So, once you have the files uploaded and necessary changes have been made you\'ll   have to enable it through the admin control panel. </p><blockquote>  <p align="left">Configuration -> Explain Queries -> Enable explain queries     -> true</p></blockquote>');

define('VALID_CATEGORIES_PRODUCTS_LIST','Valid Categories/Products List');
define('VALID_CATEGORIES_LIST','Valid Categories List');
define('VALID_PRODUCTS_LIST','Valid Products List');


define('CRE_LOADED_OSCOMMERCE','CRE Loaded osCommerce');
define('PASS_FORGOTTEN_FOOTER','eCommerce Solution by <a href="http://www.archercreative.com.au" target="_blank">Archer Creative</a>');

##################### 06/07/2006 End  ####################


##################### 07/07/2006 End  ####################

define('QUICK_ATTRIBUTES_POPUP_TXT_0','Current Attributes');
define('QUICK_ATTRIBUTES_POPUP_TXT_1','Current ID#');
define('QUICK_ATTRIBUTES_POPUP_TXT_2','Model:');
define('QUICK_ATTRIBUTES_POPUP_TXT_3','NO CURRENT ATTRIBUTES ...');
define('QUICK_ATTRIBUTES_POPUP_TXT_4','CURRENT ATTRIBUTES:');
define('QUICK_ATTRIBUTES_POPUP_TXT_5','Close Window');

define('QUICK_PRODUCTS_POPUP_TXT_0','Quick Products Listing');
define('QUICK_PRODUCTS_POPUP_TXT_1','Quick Product Locator');
define('QUICK_PRODUCTS_POPUP_TXT_2','All categories:');
define('QUICK_PRODUCTS_POPUP_TXT_3','Click to:');
define('QUICK_PRODUCTS_POPUP_TXT_4','Show Attributes');

define('TREEVIEW_TXT_1','Catalog Tree');
define('TREEVIEW_TXT_2','open all');
define('TREEVIEW_TXT_3','close all');

define("ADMIN_JS_FILE_BROWSER","File Browser");
define("ADMIN_JS_INSERT_FILE","Insert File");

define("ADMIN_JS_IBROWSER_MSG_1",'<strong>net<span class="hilight">4</span>visions.com</strong> - the image browser plugin for WYSIWYG editors like FCKeditor, SPAW, tinyMCE, Xinha, and HTMLarea!</p>
              <p> <strong> <span class="hilight">i</span>Browser</strong> does upload images and supply file management functions. Images can be resized on the fly. If you need even more advanced features, have a look at <strong> <span class="hilight">i</span>Manager</strong>, another <strong>net<span class="hilight">4</span>visions.com</strong> plugin - it adds truecolor image editing functions like: resize, flip, crop, add text, gamma correct, merge into other image, and many others.</p>
              <p><strong> <span class="hilight">i</span>Browser</strong> is written and distributed under the GNU General Public License which means that its source code is freely-distributed and available to the general public.</p>
              <p>&nbsp;</p>');

define("ADMIN_JS_IBROWSER_MSG_2",'Lorem ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exercitation ullamcorper suscipit. Lorem ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.');

define("ADMIN_JS_PREVIEW_PAGE","Preview page");
define("ADMIN_JS_EDITOR_CONTENTS","Editor contents:");
define("ADMIN_JS_CLOSE","Close");
define("ADMIN_JS_PRINT","Print");
define("DOCUMENT_INDEX","Document Index");
define("CUSTOMER_ZIP_CODE_VALIDATOR","Customer Zip Code Validator");
define("SEND_EMAIL_TO_ALL","Send Email to All");
define("MISMATCHED_STATE_AND_ZIPCODE","Mismatched State and Zipcode");
define("ADDRESS_BOOK_ID","Address Book ID");
define("CUSTOMER_ID","Customer ID");
define("CUSTOMER","Customer");
define("REMOVE","Remove");
define('TEXT_HEADING_INPUT_COLOR', 'Current Color');
define('TEXT_HEADING_CHANGE_COLOR', 'Change Color');
define('BOX_REPORTS_RECOVER_CART_SALES', 'Recover Carts');
define('BOX_TOOLS_RECOVER_CART', 'Recover Carts');
define('TEXT_DISPLAY_NUMBER_OF_TICKET_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b>)');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock Product');
define('BOX_RETURNS_HEADING', 'Customer Returns');
define('BOX_RETURNS_REASONS', 'Return Reasons');
define('BOX_RETURNS_MAIN', 'Returned Products');
define('BOX_RETURNS_TEXT', 'Return Text Edit');
define('BOX_RETURNS_STATUS', 'Returns Status');
define('BOX_HEADING_REFUNDS', 'Refund Methods');
define('BOX_TOOLS_MY_SQL_MANAGER', 'MySQL Manager');
define('BOX_CATALOG_SPECIALSBYCAT','Specials By Category');
define('BOX_FEEDERS_FROOGLE', 'Froogle');
define('TEXT_ORDER', 'Order');  
define('TEXT_SEARCH', 'Search');
define('TEXT_CREATE', 'Create');  
define('TEXT_IMAGE_NONEXISTENT', 'Image does not exist on the server.');    
define('VISUAL_IMAGE_NONEXISTENT', '<center>' . tep_image(DIR_WS_IMAGES . 'image_not_avail.jpg') . '</center>');
define('TEXT_POPUP_CLOSE_WINDOW','Close Window');
// multi-vendor shipping
define('VENDOR_IMAGE_MAIN_CONFIGURATION', 'Set Vendor Constants');
define('VENDOR_IMAGE_MANAGE_MODULES', 'Vendor Module Manager');
define('IMAGE_MANAGE', 'Manage');
define('VENDOR_HEADING_TITLE', 'Vendor Module Manager');
define('BOX_HEADING_VENDOR_MODULES', 'Vendor Management');
define('BOX_VENDOR_SELECT', 'Vendor Select');
define('BOX_VENDOR_MODULES_SHIPPING', 'Vendor Shipping');
define('BOX_VENDOR_CONFIGURATION', 'Vendor Config');
define('BOX_CATALOG_VENDORS', 'Vendor Manager');
define('BOX_HEADING_VENDORS', 'Multi-Vendor Shipping');
define('BOX_VENDORS', 'Vendor Manager');
define('BOX_VENDORS_REPORTS_PROD', 'Product Reports');
define('TEXT_DISPLAY_NUMBER_OF_VENDORS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> vendors)');
define('TEXT_CACHE_VENDORS', 'Vendors Box');
define('BOX_VENDORS_ORDERS', 'Vendors Orders List');
define('BOX_MOVE_VENDOR_PRODS', 'Move Products Between Vendors');
// Products Sales overtime report
define('BOX_REPORTS_PRODUCTS_SALES', 'Sales Over Time');
// Multi Warehouse Shipping 
define('BOX_WAREHOUSE_ZONES','Warehouse Zones');
define('BOX_HEADING_MANUFACTURER_INFO' ,'Manufactuer Info');
define('TEXT_POWERED_BY_CRE_NAG', 'Update Success!<br><br>You\'re using our Free CRE Loaded Software, To remove this message, upgrade to our Pro products and get more features and support!<br><br>Pro versions will bring you right back to the %s page and avoid this step.');
define('ENTRY_CUSTOMERS_ACCESS_GROUP', 'Customer Access Group:');
define('ENTRY_CUSTOMERS_ALL_GROUP', ' All');
define('ENTRY_CUSTOMERS_GUEST_GROUP', ' Guest');
// low stock report
define('BOX_LOW_STOCK_REPORT','Low Stock Report');
?>