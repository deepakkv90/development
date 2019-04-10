<?php
/*
  $Id: application_top_cre_setting.php ,v 1.

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/
//define banner type for box add
 define('BOX_AD_BANNER_TYPE', 'box-ad');
 
//Settings for gift voucher credit class
// Set the length of the redeem code, the longer the more secure
  define('SECURITY_CODE_LENGTH', '10');

//configuration for affiliates.
  define('AFFILIATE_KIND_OF_BANNERS','1');          // 1 Direct Link to Banner ; no counting of how much banners are shown
                                                    // 2 Banners are shown with affiliate_show_banner.php; bannerviews are counted (recommended)
  define('AFFILIATE_SHOW_BANNERS_DEBUG', 'false');  // Debug for affiliate_show_banner.php; If you have difficulties geting banners set to true,
                                                    // and try to load the banner in a new Browserwindow
                                                    // i.e.: http://yourdomain.com/affiliate_show_banner.php?ref=3569&affiliate_banner_id=3
  define('AFFILIATE_SHOW_BANNERS_DEFAULT_PIC', '/images/banners/loaded_header_logo.gif'); // absolute path to default pic for affiliate_show_banner.php, which is showed if no banner is found
                                                    // Only works with AFFILIATE_KIND_OF_BANNERS=2


//add to switch between text and html in emails, should be moved to admin group 12
 define('TELL_PRODUCT_EMAIL_USE_HTML', 'true');
 define('TELL_ARTICLE_EMAIL_USE_HTML', 'true');
 define('WISH_EMAIL_USE_HTML', 'true');
 
 // upcoming products product listing, this ads the date expected to the colomun for display inthis list only
  define('PRODUCT_LIST_DATE_EXPECTED', '4');

//products-new.php New setting define new product interval, how days in the past to since the product was added new products
 define('NEW_PRODUCT_INTERVAL', '30');
 
//number of upsell products to sell 
 define('MAX_DISPLAY_XSELL', '4');
 
//maxium artcles to display for articles module
define('MAX_DISPLAY_MODULES_ARTICLES', '5');

//faq mainpage module
define('MAX_DISPLAY_MODULES_FAQ_CATEGORY', '5');
define('MAX_DISPLAY_MODULES_FAQ', '2');

?>