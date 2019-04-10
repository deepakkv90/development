<?php

/*

  $Id: filenames.php,v 1.3 2004/09/25 15:34:33 DMG



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



//VVC Check

define('FILENAME_VISUAL_VERIFY_CODE_DISPLAY', 'vvc_display.php');



//Email Verification

define('CONTENT_PW', 'pw');

define('FILENAME_PW', CONTENT_PW . '.php');

define('CONTENT_VALIDATE_NEW', 'validate_new');

define('FILENAME_VALIDATE_NEW', CONTENT_VALIDATE_NEW . '.php');

define('FILENAME_PRODUCT_QUANTITY_TABLE','product_quantity_table.php');



// define the content used in the project

  define('FILENAME_WPCALLBACK', 'wpcallback.php');

  define('CONTENT_ACCOUNT', 'account');

  define('CONTENT_ACCOUNT_EDIT', 'account_edit');

  define('CONTENT_ACCOUNT_HISTORY', 'account_history');

  define('CONTENT_ACCOUNT_HISTORY_INFO', 'account_history_info');

  define('CONTENT_ACCOUNT_NEWSLETTERS', 'account_newsletters');

  define('CONTENT_ACCOUNT_NOTIFICATIONS', 'account_notifications');

  define('CONTENT_ACCOUNT_PASSWORD', 'account_password');

  define('CONTENT_ADDRESS_BOOK', 'address_book');

  define('CONTENT_ADDRESS_BOOK_PROCESS', 'address_book_process');

  define('CONTENT_ADVANCED_SEARCH', 'advanced_search');

  define('CONTENT_ADVANCED_SEARCH_RESULT', 'advanced_search_result');

  define('CONTENT_ALSO_PURCHASED_PRODUCTS', 'also_purchased_products');

  define('CONTENT_CHECKOUT_CONFIRMATION', 'checkout_confirmation');

  define('CONTENT_CHECKOUT_PAYMENT', 'checkout_payment');

  define('CONTENT_CHECKOUT_PAYMENT_ADDRESS', 'checkout_payment_address');

  define('CONTENT_CHECKOUT_SHIPPING', 'checkout_shipping');

  define('CONTENT_CHECKOUT_SHIPPING_ADDRESS', 'checkout_shipping_address');

  define('CONTENT_CHECKOUT_SUCCESS', 'checkout_success');

  define('CONTENT_CONTACT_US', 'contact_us');

  define('CONTENT_CONDITIONS', 'conditions');

  define('CONTENT_COOKIE_USAGE', 'cookie_usage');

  define('CONTENT_CREATE_ACCOUNT', 'create_account');

  define('CONTENT_CREATE_ACCOUNT_SUCCESS', 'create_account_success');

  define('CONTENT_DOWNLOAD', 'download');

  define('CONTENT_INDEX_DEFAULT', 'index_default');

  define('CONTENT_INDEX_NESTED', 'index_nested');

  define('CONTENT_INDEX_PRODUCTS', 'index_products');

  define('CONTENT_INFO_SHOPPING_CART', 'info_shopping_cart');

  define('CONTENT_LOGIN', 'login');

  define('CONTENT_LOGIN_ACC', 'login_acc');

  define('CONTENT_LOGOFF', 'logoff');

  define('CONTENT_NEW_PRODUCTS', 'new_products');

  define('CONTENT_PASSWORD_FORGOTTEN', 'password_forgotten');

  define('CONTENT_POPUP_IMAGE', 'popup_image');

  define('CONTENT_POPUP_SEARCH_HELP', 'popup_search_help');

  define('CONTENT_PRIVACY', 'privacy');

  define('CONTENT_PRODUCT_REVIEWS', 'product_reviews');

  define('CONTENT_PRODUCT_REVIEWS_INFO', 'product_reviews_info');

  define('CONTENT_PRODUCT_REVIEWS_WRITE', 'product_reviews_write');

  define('CONTENT_PRODUCTS_NEW', 'products_new');

  define('CONTENT_PRODUCT_INFO', 'product_info');

//  define('CONTENT_EVENTS_CALENDAR', 'events_calendar');

  define('CONTENT_REVIEWS', 'reviews');

  define('CONTENT_SHIPPING', 'shipping');

  define('CONTENT_SHOPPING_CART', 'shopping_cart');

  define('CONTENT_SPECIALS', 'specials');

  define('CONTENT_SSL_CHECK', 'ssl_check');

  define('CONTENT_TELL_A_FRIEND', 'tell_a_friend');

  define('CONTENT_UPCOMING_PRODUCTS', 'upcoming_products');

  define('CONTENT_CHECKOUT_PROCESS', 'checkout_process');

   define('CONTENT_INDEX_RESTRICTED', 'index_restricted');
   
   define('CONTENT_INDEX_RESTRICTED_LOGIN', 'index_restricted_login');



// Lango added for GV FAQ: BOF

  define('CONTENT_GV_FAQ', 'gv_faq');

  define('CONTENT_GV_REDEEM', 'gv_redeem');

  define('CONTENT_GV_SEND', 'gv_send');

  define('CONTENT_GV_REDEEM_PROCESS', 'gv_redeem_process');

  define('CONTENT_GV_SEND_PROCESS', 'gv_send_process');

  define('CONTENT_POPUP_COUPON_HELP', 'popup_coupon_help');



  define('FILENAME_GV_FAQ', CONTENT_GV_FAQ . '.php');

  define('FILENAME_GV_REDEEM', CONTENT_GV_REDEEM . '.php');

  define('FILENAME_GV_SEND', CONTENT_GV_SEND . '.php');

  define('FILENAME_GV_REDEEM_PROCESS', CONTENT_GV_REDEEM_PROCESS . 'php');

  define('FILENAME_GV_SEND_PROCESS', CONTENT_GV_SEND_PROCESS . 'php');

  define('FILENAME_POPUP_COUPON_HELP', CONTENT_POPUP_COUPON_HELP . '.php');



// Lango Added for Down for Maintainance Mod: BOF

  define('CONTENT_DOWN_FOR_MAINTAINANCE', 'down_for_maintenance');

// Lango Added for Down for Maintainance Mod: EOF



// Lango added forALL_PRODS: BOF

  define('CONTENT_ALL_PRODS', 'allprods');



define('CONTENT_ALL_PRODCATS', 'all_prodcats');   // all products and used categories

define('FILENAME_ALL_PRODCATS', 'all_prodcats.php');   // all products and used categories



define('CONTENT_ALL_PRODMANF', 'all_prodmanf');   // all products and used categories

define('FILENAME_ALL_PRODMANF', 'all_prodmanf.php');   // all products and used categories



// Lango added forALL_PRODS: EOF



// Lango added for osC-PrintOrder v1.0: BOF

  define('CONTENT_ORDERS_PRINTABLE', 'printorder');

// Lango added for osC-PrintOrder v1.0: EOF



// Lango added for Featured products: BOF

  define('CONTENT_FEATURED', 'featured');

  define('CONTENT_FEATURED_PRODUCTS', 'featured_products');



// Lango added for Featured products: EOF



// Lango Added for WishList Mod: BOF

  define('CONTENT_WISHLIST_SEND', 'wishlist_email');

  define('CONTENT_WISHLIST_HELP', 'wishlist_help');

  define('CONTENT_WISHLIST', 'wishlist');



// Lango Added for WishList Mod: EOF

// Lango Added for Links Manager Mod: BOF

  define('CONTENT_LINKS', 'links');

  define('CONTENT_LINKS_SUBMIT', 'links_submit');

  define('CONTENT_LINKS_SUBMIT_SUCCESS', 'links_submit_success');

// Lango Added for Links Manager Mod:



  define('FILENAME_LANGUAGES', 'languages.php');



// Lango Added for shop by price Mod: BOF

  define('CONTENT_SHOP_BY_PRICE', 'shop_by_price');

// Lango Added for shop by price Mod: EOF



//DWD Modify: Information Page Unlimited 1.1f - PT

  define('CONTENT_INFORMATION', 'information');

//DWD Modify End



// define the filenames used in the project

  define('FILENAME_ACCOUNT', CONTENT_ACCOUNT . '.php');

  define('FILENAME_ACCOUNT_EDIT', CONTENT_ACCOUNT_EDIT . '.php');

  define('FILENAME_ACCOUNT_HISTORY', CONTENT_ACCOUNT_HISTORY . '.php');

  define('FILENAME_ACCOUNT_HISTORY_INFO', CONTENT_ACCOUNT_HISTORY_INFO . '.php');

  define('FILENAME_ACCOUNT_NEWSLETTERS', CONTENT_ACCOUNT_NEWSLETTERS . '.php');

  define('FILENAME_ACCOUNT_NOTIFICATIONS', CONTENT_ACCOUNT_NOTIFICATIONS . '.php');

  define('FILENAME_ACCOUNT_PASSWORD', CONTENT_ACCOUNT_PASSWORD . '.php');

  define('FILENAME_ADDRESS_BOOK', CONTENT_ADDRESS_BOOK . '.php');

  define('FILENAME_ADDRESS_BOOK_PROCESS', CONTENT_ADDRESS_BOOK_PROCESS . '.php');

  define('FILENAME_ADVANCED_SEARCH', CONTENT_ADVANCED_SEARCH . '.php');

  define('FILENAME_ADVANCED_SEARCH_RESULT', CONTENT_ADVANCED_SEARCH_RESULT . '.php');

  define('FILENAME_ALSO_PURCHASED_PRODUCTS', CONTENT_ALSO_PURCHASED_PRODUCTS . '.php');

  define('FILENAME_CHECKOUT_CONFIRMATION', CONTENT_CHECKOUT_CONFIRMATION . '.php');

  define('FILENAME_CHECKOUT_PAYMENT', CONTENT_CHECKOUT_PAYMENT . '.php');

  define('FILENAME_CHECKOUT_PAYMENT_ADDRESS', CONTENT_CHECKOUT_PAYMENT_ADDRESS . '.php');

  define('FILENAME_CHECKOUT_PROCESS', CONTENT_CHECKOUT_PROCESS . '.php');

  define('FILENAME_CHECKOUT_SHIPPING', CONTENT_CHECKOUT_SHIPPING . '.php');

  define('FILENAME_CHECKOUT_SHIPPING_ADDRESS', CONTENT_CHECKOUT_SHIPPING_ADDRESS . '.php');

  define('FILENAME_CHECKOUT_SUCCESS', CONTENT_CHECKOUT_SUCCESS . '.php');

  define('FILENAME_CONTACT_US', CONTENT_CONTACT_US . '.php');

  define('FILENAME_CONDITIONS', CONTENT_CONDITIONS . '.php');

  define('FILENAME_COOKIE_USAGE', CONTENT_COOKIE_USAGE . '.php');

  define('FILENAME_CREATE_ACCOUNT', CONTENT_CREATE_ACCOUNT . '.php');

  define('FILENAME_CREATE_ACCOUNT_SUCCESS', CONTENT_CREATE_ACCOUNT_SUCCESS . '.php');

  define('FILENAME_DEFAULT', 'index.php');

  define('FILENAME_DEFAULT_SPECIALS', 'default_specials.php');

  define('FILENAME_DOWNLOAD', CONTENT_DOWNLOAD . '.php');

  define('FILENAME_INFO_SHOPPING_CART', CONTENT_INFO_SHOPPING_CART . '.php');

  define('FILENAME_LOGIN', CONTENT_LOGIN . '.php');

  define('FILENAME_LOGOFF', CONTENT_LOGOFF . '.php');

  define('FILENAME_NEW_PRODUCTS', CONTENT_NEW_PRODUCTS . '.php');

  define('FILENAME_PASSWORD_FORGOTTEN', CONTENT_PASSWORD_FORGOTTEN . '.php');

  define('FILENAME_POPUP_IMAGE', CONTENT_POPUP_IMAGE . '.php');

  define('FILENAME_POPUP_SEARCH_HELP', CONTENT_POPUP_SEARCH_HELP . '.php');

  define('FILENAME_PRIVACY', CONTENT_PRIVACY . '.php');

  define('FILENAME_PRODUCT_REVIEWS', CONTENT_PRODUCT_REVIEWS . '.php');

  define('FILENAME_PRODUCT_REVIEWS_INFO', CONTENT_PRODUCT_REVIEWS_INFO . '.php');

  define('FILENAME_PRODUCT_REVIEWS_WRITE', CONTENT_PRODUCT_REVIEWS_WRITE . '.php');

  define('FILENAME_PRODUCTS_NEW', CONTENT_PRODUCTS_NEW . '.php');

  define('FILENAME_PRODUCT_INFO', CONTENT_PRODUCT_INFO . '.php');

  define('FILENAME_REDIRECT', 'redirect.php');

  define('FILENAME_REVIEWS', CONTENT_REVIEWS . '.php');

  //define('FILENAME_EVENTS_CALENDAR', CONTENT_EVENTS_CALENDAR . '.php?view=all_events');

  define('FILENAME_SHIPPING', CONTENT_SHIPPING . '.php');

  define('FILENAME_SHOPPING_CART', CONTENT_SHOPPING_CART . '.php');

  define('FILENAME_SPECIALS', CONTENT_SPECIALS . '.php');

  define('FILENAME_SSL_CHECK', CONTENT_SSL_CHECK . '.php');

  define('FILENAME_TELL_A_FRIEND', CONTENT_TELL_A_FRIEND . '.php');

  define('FILENAME_UPCOMING_PRODUCTS', CONTENT_UPCOMING_PRODUCTS . '.php');

//begin PayPal_Shopping_Cart_IPN

  define('FILENAME_PAYPAL', 'paypal.php');

//end PayPal_Shopping_Cart_IPN

// Added for Xsell Products Mod

  define('FILENAME_XSELL_PRODUCTS', 'xsell_products_buynow.php');

  define('FILENAME_XSELL_PRODUCTS_BUYNOW', 'xsell_products_buynow.php');



//BEGIN allprods modification

define('FILENAME_ALLPRODS', 'allprods.php');

//END allprods modification



// MaxiDVD Added Line For WYSIWYG HTML Area: BOF

  define('FILENAME_DEFINE_MAINPAGE', 'mainpage.php');

    define('FILENAME_DEFINE_HOMEPAGE', 'homepage.php');

// MaxiDVD Added Line For WYSIWYG HTML Area: EOF



// Lango Added for ALL_PODS Mod: BOF

  define('FILENAME_ALL_PRODS', CONTENT_ALL_PRODS . '.php');

// Lango Added for ALL_PRODS Mod: EOF



// Lango Added for ALL_PODS Mod: BOF

  define('FILENAME_ORDERS_PRINTABLE', CONTENT_ORDERS_PRINTABLE . '.php');

// Lango Added for ALL_PRODS Mod: EOF



// Lango Added for Featured Products: BOF

  define('FILENAME_FEATURED', CONTENT_FEATURED . '.php');

  define('FILENAME_FEATURED_PRODUCTS', CONTENT_FEATURED_PRODUCTS . '.php');

// Lango Added for Featured Product: EOF





// Lango Added for WishList Mod: BOF

  define('FILENAME_WISHLIST_SEND', CONTENT_WISHLIST_SEND . '.php');

  define('FILENAME_WISHLIST_HELP', CONTENT_WISHLIST_HELP . '.php');

  define('FILENAME_WISHLIST', CONTENT_WISHLIST . '.php');

// Lango Added for WishList Mod: EOF



// Lango Added for Links Manager Mod: BOF

  define('FILENAME_LINKS', CONTENT_LINKS . '.php');

  define('FILENAME_LINKS_SUBMIT', CONTENT_LINKS_SUBMIT . '.php');

  define('FILENAME_LINKS_SUBMIT_SUCCESS', CONTENT_LINKS_SUBMIT_SUCCESS . '.php');

  define('FILENAME_LINK_LISTING', 'link_listing.php');

  define('FILENAME_POPUP_LINKS_HELP', 'popup_links_help.php');

// Lango Added for Links Manager Mod: EOF



// Lango Added for shop by price Mod: BOF

  define('FILENAME_SHOP_BY_PRICE', CONTENT_SHOP_BY_PRICE . '.php');

// Lango Added for shop by price Mod: EOF



//Begin Checkout Without Account Modifications



  define('CONTENT_ORDER_INFO', 'Order_Info');

  define('FILENAME_ORDER_INFO', CONTENT_ORDER_INFO . '.php');

  define('CONTENT_ORDER_INFO_PROCESS', 'Order_Info_Process');

  define('FILENAME_ORDER_INFO_PROCESS', CONTENT_ORDER_INFO_PROCESS . '.php');

  define('FILENAME_PWA_PWA_LOGIN', 'login_pwa.php');

  define('FILENAME_PWA_ACC_LOGIN', 'login_acc.php');

  define('FILENAME_CHECKOUT', 'Order_Info.php');



// define the templatenames used in the project

  define('TEMPLATENAME_BOX', 'box.tpl.php');

  define('TEMPLATENAME_MAIN_PAGE', 'main_page.tpl.php');

  define('TEMPLATENAME_BADGE_MAIN_PAGE', 'badge_main_page.tpl.php');

  define('TEMPLATENAME_POPUP', 'popup.tpl.php');

  define('TEMPLATENAME_STATIC', 'static.tpl.php');



//DWD Modify: Information Page Unlimited 1.1f - PT

  define('FILENAME_INFORMATION', CONTENT_INFORMATION . '.php');

//DWD Modify End



 define('FILENAME_POPUP_CVS_HELP', 'popup_cvs_help.php');

 define('FILENAME_CVS_HELP', 'cvs_help.php');



//product listing colum vs row fix

 define('CONTENT_PRODUCT_LISTING', 'product_listing');

 define('CONTENT_PRODUCT_LISTING_COL', 'product_listing_col');

 define('FILENAME_PRODUCT_LISTING', CONTENT_PRODUCT_LISTING . '.php');

 define('FILENAME_PRODUCT_LISTING_COL', CONTENT_PRODUCT_LISTING_COL . '.php');



// define the content used for affiliates

define('CONTENT_AFFILIATE', 'affiliate_affiliate');

define('CONTENT_AFFILIATE_ACCOUNT_DETAILS', 'affiliate_account_details');

define('CONTENT_AFFILIATE_BANNERS', 'affiliate_banners');

define('CONTENT_AFFILIATE_BANNERS_BANNERS', 'affiliate_banners_banners');

define('CONTENT_AFFILIATE_BANNERS_BUILD', 'affiliate_banners_build');//content file

define('CONTENT_AFFILIATE_BANNERS_BUILD_CAT', 'affiliate_banners_build_cat');

define('CONTENT_AFFILIATE_BANNERS_CATEGORY', 'affiliate_banners_category');

define('CONTENT_AFFILIATE_BANNERS_PRODUCT', 'affiliate_banners_product');

define('CONTENT_AFFILIATE_BANNERS_TEXT', 'affiliate_banners_text');

define('CONTENT_AFFILIATE_CENTRAL', 'affiliate_central');

define('CONTENT_AFFILIATE_CLICKS', 'affiliate_clicks');

define('CONTENT_AFFILIATE_CONTACT', 'affiliate_contact');

define('CONTENT_AFFILIATE_DETAILS', 'affiliate_details');

define('CONTENT_AFFILIATE_DETAILS_OK', 'affiliate_details_ok');

define('CONTENT_AFFILIATE_FAQ', 'affiliate_faq');

define('CONTENT_AFFILIATE_INFO', 'affiliate_info');

define('CONTENT_AFFILIATE_LOGOUT', 'affiliate_logout');

define('CONTENT_AFFILIATE_NEWS', 'affiliate_news');

define('CONTENT_AFFILIATE_NEWSLETTER', 'affiliate_newsletter');

define('CONTENT_AFFILIATE_TERMS', 'affiliate_terms');

define('CONTENT_AFFILIATE_PAYMENT', 'affiliate_payment');

define('CONTENT_AFFILIATE_PASSWORD', 'affiliate_password');

define('CONTENT_AFFILIATE_PASSWORD_FORGOTTEN', 'affiliate_password_forgotten');

define('CONTENT_AFFILIATE_REPORTS', 'affiliate_reports');

define('CONTENT_AFFILIATE_SALES', 'affiliate_sales');

define('CONTENT_AFFILIATE_SIGNUP_OK', 'affiliate_signup_ok');

define('CONTENT_AFFILIATE_SIGNUP', 'affiliate_signup');

define('CONTENT_AFFILIATE_SHOW_BANNER', 'affiliate_show_banner');

define('CONTENT_AFFILIATE_SUMMARY', 'affiliate_summary');

define('CONTENT_AFFILIATE_TERMS_POPUP', 'affiliate_terms_popup');

define('CONTENT_AFFILIATE_VALIDCATS', 'affiliate_validcats');

define('CONTENT_AFFILIATE_VALIDPRODUCTS', 'affiliate_validproducts');

define('CONTENT_POPUP_AFFILIATE_HELP', 'popup_affiliate_help');





// define the filenames used for affiliates

define('FILENAME_AFFILIATE', CONTENT_AFFILIATE . '.php');

define('FILENAME_AFFILIATE_ACCOUNT_DETAILS', CONTENT_AFFILIATE_ACCOUNT_DETAILS . '.php');

define('FILENAME_AFFILIATE_ACCOUNT', CONTENT_AFFILIATE_DETAILS . '.php');

define('FILENAME_AFFILIATE_BANNERS', CONTENT_AFFILIATE_BANNERS . '.php');

define('FILENAME_AFFILIATE_BANNERS_CATEGORY', CONTENT_AFFILIATE_BANNERS_CATEGORY . '.php');

define('FILENAME_AFFILIATE_BANNERS_BANNERS', CONTENT_AFFILIATE_BANNERS_BANNERS . '.php');

define('FILENAME_AFFILIATE_BANNERS_BUILD', CONTENT_AFFILIATE_BANNERS_BUILD . '.php');

define('FILENAME_AFFILIATE_BANNERS_BUILD_CAT', CONTENT_AFFILIATE_BANNERS_BUILD_CAT . '.php');

define('FILENAME_AFFILIATE_BANNERS_PRODUCT', CONTENT_AFFILIATE_BANNERS_PRODUCT . '.php');

define('FILENAME_AFFILIATE_BANNERS_TEXT', CONTENT_AFFILIATE_BANNERS_TEXT . '.php');

define('FILENAME_AFFILIATE_CENTRAL', CONTENT_AFFILIATE_CENTRAL . '.php');

define('FILENAME_AFFILIATE_CLICKS', CONTENT_AFFILIATE_CLICKS . '.php');

define('FILENAME_AFFILIATE_CONTACT', CONTENT_AFFILIATE_CONTACT . '.php');

define('FILENAME_AFFILIATE_DETAILS', CONTENT_AFFILIATE_DETAILS . '.php');

define('FILENAME_AFFILIATE_DETAILS_OK', CONTENT_AFFILIATE_DETAILS_OK . '.php');

define('FILENAME_AFFILIATE_FAQ', CONTENT_AFFILIATE_FAQ . '.php');

define('FILENAME_AFFILIATE_INFO', CONTENT_AFFILIATE_INFO . '.php');

define('FILENAME_AFFILIATE_LOGOUT', CONTENT_AFFILIATE_LOGOUT . '.php');

define('FILENAME_AFFILIATE_NEWS', CONTENT_AFFILIATE_NEWS . '.php');

define('FILENAME_AFFILIATE_NEWSLETTER', CONTENT_AFFILIATE_NEWSLETTER . '.php');

define('FILENAME_AFFILIATE_PAYMENT', CONTENT_AFFILIATE_PAYMENT . '.php');

define('FILENAME_AFFILIATE_REPORTS', CONTENT_AFFILIATE_REPORTS . '.php');

define('FILENAME_AFFILIATE_PASSWORD', CONTENT_AFFILIATE_PASSWORD . '.php');

define('FILENAME_AFFILIATE_PASSWORD_FORGOTTEN', CONTENT_AFFILIATE_PASSWORD_FORGOTTEN . '.php');

define('FILENAME_AFFILIATE_SALES', CONTENT_AFFILIATE_SALES . '.php');

define('FILENAME_AFFILIATE_SIGNUP', CONTENT_AFFILIATE_SIGNUP . '.php');

define('FILENAME_AFFILIATE_SIGNUP_OK', CONTENT_AFFILIATE_SIGNUP_OK . '.php');

define('FILENAME_AFFILIATE_SUMMARY', CONTENT_AFFILIATE_SUMMARY . '.php');

define('FILENAME_AFFILIATE_SHOW_BANNER', CONTENT_AFFILIATE_SHOW_BANNER . '.php');

define('FILENAME_AFFILIATE_TERMS', CONTENT_AFFILIATE_TERMS . '.php');

define('FILENAME_AFFILIATE_TERMS_POPUP', CONTENT_AFFILIATE_TERMS_POPUP . '.php');

define('FILENAME_AFFILIATE_VALIDCATS', CONTENT_AFFILIATE_VALIDCATS . '.php');

define('FILENAME_AFFILIATE_VALIDPRODUCTS', CONTENT_AFFILIATE_VALIDPRODUCTS . '.php');

define('FILENAME_POPUP_AFFILIATE_HELP', CONTENT_POPUP_AFFILIATE_HELP . '.php');



 //events_calendar

  define('CONTENT_EVENTS_CALENDAR', 'events_calendar');

  define('CONTENT_EVENTS_CALENDAR_CONTENT', 'calendar_content');



  //FAQ Content

  define('CONTENT_FAQ','faq');



  define('FILENAME_EVENTS_CALENDAR', CONTENT_EVENTS_CALENDAR . '.php');

  define('FILENAME_EVENTS_CALENDAR_CONTENT', CONTENT_EVENTS_CALENDAR_CONTENT . '.php');



// Added for FAQ System 2.1 DMG

  define('FILENAME_FAQ','faq.php');



//added for Article Manager

  define('CONTENT_ARTICLE_INFO', 'article_info');

  define('CONTENT_ARTICLE_LISTING', 'article_listing');

  define('CONTENT_ARTICLE_REVIEWS', 'article_reviews');

  define('CONTENT_ARTICLE_REVIEWS_INFO', 'article_reviews_info');

  define('CONTENT_ARTICLE_REVIEWS_WRITE', 'article_reviews_write');

  define('CONTENT_ARTICLES', 'articles');

  define('CONTENT_ARTICLES_NEW', 'articles_new');

  define('CONTENT_ARTICLES_UPCOMING', 'articles_upcoming');

  define('CONTENT_ARTICLES_XSELL', 'articles_xsell');

  define('CONTENT_NEW_ARTICLES', 'new_articles');

  define('CONTENT_ARTICLE_SEARCH', 'article_search');



  define('FILENAME_ARTICLE_INFO', CONTENT_ARTICLE_INFO . '.php');

  define('FILENAME_ARTICLE_LISTING', CONTENT_ARTICLE_LISTING . '.php');

  define('FILENAME_ARTICLE_REVIEWS', CONTENT_ARTICLE_REVIEWS . '.php');

  define('FILENAME_ARTICLE_REVIEWS_INFO', CONTENT_ARTICLE_REVIEWS_INFO . '.php');

  define('FILENAME_ARTICLE_REVIEWS_WRITE', CONTENT_ARTICLE_REVIEWS_WRITE . '.php');

  define('FILENAME_ARTICLES', CONTENT_ARTICLES . '.php');

  define('FILENAME_ARTICLES_NEW', CONTENT_ARTICLES_NEW . '.php');

  define('FILENAME_ARTICLES_UPCOMING', CONTENT_ARTICLES_UPCOMING . '.php');

  define('FILENAME_ARTICLES_XSELL', CONTENT_ARTICLES_XSELL . '.php');

  define('FILENAME_NEW_ARTICLES', CONTENT_NEW_ARTICLES . '.php');

  define('FILENAME_ARTICLE_SEARCH',CONTENT_ARTICLE_SEARCH . '.php');



 define('CONTENT_TELL_A_FRIEND_ARTICLE', 'tell_a_friend_article');

 define('FILENAME_TELL_A_FRIEND_ARTICLE', CONTENT_TELL_A_FRIEND_ARTICLE . '.php');



// VJ infosystem added

  define('CONTENT_PAGES', 'pages');

  define('FILENAME_PAGES', CONTENT_PAGES . '.php');



define('FILENAME_DOWNLOADBOX','downloadbox.php');

define('FILENAME_INFORMATIONBOX','informationbox.php');

define('FILENAME_LANGUAGE','language.php');

define('FILENAME_LOGINBOX','loginbox.php');

define('FILENAME_WHOS_ONLINEBOX','whos_onlinebox.php');

define('FILENAME_COUNTER','counter.php');

define('FILENAME_HEADER_TAGS','header_tags.php');

define('FILENAME_WARNINGS','warnings.php');

define('FILENAME_HEADER','header.php');

define('FILENAME_COLUMN_LEFT','column_left.php');

define('FILENAME_COLUMN_RIGHT','column_right.php');

define('FILENAME_FOOTER','footer.php');

define('FILENAME_LINKS_HTM','links.htm');



define('FILENAME_SEARCH_IN_HEADER','search_in_header.php');

define('FILENAME_LANGUAGES_IN_HEADER','languages_in_header.php');

define('FILENAME_DOWNLOADS','downloads.php');

define('FILENAME_ADDRESS_BOOK_DETAILS','address_book_details.php');

define('FILENAME_ADD_CHECKOUT_SUCCESS','add_checkout_success.php');

define('FILENAME_ORDER_INFO_CHECK','Order_Info_Check.php');

define('FILENAME_SHIPPING_ESTIMATOR','shipping_estimator.php');

define('FILENAME_FREECHARGER', 'freecharger.php');

define('FILENAME_ARTICLESS', 'articles.php');

define('FILENAME_AUTHORS', 'authors.php');

define('FILENAME_DOWNLOADS_CONTROLLER', 'downloads_controller.php');

define('FILENAME_FORM_CHECK_JS', 'form_check.js.php');

// Begin: RMA Returns System

define('CONTENT_RETURNS_TRACK', 'returns_track');

define('CONTENT_RETURN', 'return_product');



define('FILENAME_RETURN', CONTENT_RETURN . '.php');



define('FILENAME_RETURN_EMAILS', 'return_emails.php');

define('FILENAME_RETURNS_TRACK', CONTENT_RETURNS_TRACK . '.php');

// End: RMA Returns System

//products tabs

define('CONTENT_PRODUCT_INFO_TABS','product_info_tabs');

define('FILENAME_POPUP_CVV_HELP', 'popup_cvv_help.php');



define('FILENAME_ORDERS_INVOICE', 'invoice.php');


define('FILENAME_ACCOUNT_MYFILES', 'account_myfiles.php');

define('CONTENT_ACCOUNT_MYFILES', 'account_myfiles');

define('FILENAME_ACCOUNT_ARTWORKS', 'account_artworks.php');

define('CONTENT_ACCOUNT_ARTWORKS', 'account_artworks');

define('FILENAME_INSTANT_QUOTE', 'instant_quote.php');

define('CONTENT_INSTANT_QUOTE', 'instant_quote');


?>