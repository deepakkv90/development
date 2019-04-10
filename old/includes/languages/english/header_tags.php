<?php
// /catalog/includes/languages/english/header_tags.php
// Add META TAGS and Modify TITLE
//
// DEFINITIONS FOR /includes/languages/english/header_tags.php

// Define your email address to appear on all pages
define('HEAD_REPLY_TAG_ALL', STORE_OWNER_EMAIL_ADDRESS);

// For all pages not defined or left blank, and for products not defined
// These are included unless you set the toggle switch in each section below to OFF ( '0' )
// The HEAD_TITLE_TAG_ALL is included AFTER the specific one for the page
// The HEAD_DESC_TAG_ALL is included AFTER the specific one for the page
// The HEAD_KEY_TAG_ALL is included AFTER the specific one for the page
define('HEAD_TITLE_TAG_ALL','Name Plates Supplies in Australia - Name Plates International');
define('HEAD_DESC_TAG_ALL','Name Plates International Australia is a top supplier for office name plates, desk signs, door signs, table signs');
define('HEAD_KEY_TAG_ALL','name plates, desk signs, door signs, wall signs, desk name plates, door name plates, wall name plates, table name plates, desk plates, door plates, wall plates, table plates, office name plates');

// DEFINE TAGS FOR INDIVIDUAL PAGES

// allprods.php
define('HTTA_ALLPRODS_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_ALLPRODS_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_ALLPRODS_ON','1'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_ALLPRODS','Name Plates, Desk Name Plates, Door Name Plates, Office Signs');
define('HEAD_DESC_TAG_ALLPRODS','Professional Choice in Personalised name plates, desk name plates, door signs. Design Name plates Online.');
define('HEAD_KEY_TAG_ALLPRODS','buy name plates online, design name plate online, custom name plates');

// index.php
define('HTTA_DEFAULT_ON','0'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_DEFAULT_ON','0'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_DEFAULT_ON','0'); // Include HEAD_DESC_TAG_ALL in Description
define('HTTA_CAT_DEFAULT_ON','0'); //Include HEADE_TITLE_DEFAULT in CATEGORY DISPLAY
define('HEAD_TITLE_TAG_DEFAULT','Desk Name Plates, Custom Door Signs, Office Signs - Name Plates International');
define('HEAD_DESC_TAG_DEFAULT',' Name plates international is the professional choice in all personalised desk name plates, door name plates, office signs, table plates with Free Design service in Australia.');
define('HEAD_KEY_TAG_DEFAULT','name plates, desk signs, door signs, wall signs, desk name plates, door name plates, wall name plates, table name plates, desk plates, door plates, wall plates, table plates, office name plates, wedding name plates, buy name plates online, design name plates online');

// instant_quote.php
define('HTTA_INSTANT_QUOTE_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_INSTANT_QUOTE_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_INSTANT_QUOTE_ON','1'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_INSTANT_QUOTE','Instant Free Quote Name Plates, Sings in Australia - Name Plates International ');
define('HEAD_DESC_TAG_INSTANT_QUOTE','Get instant quote for name badges, office sings, door sings, desk name plates using our instant quote tool. Design Name plates Online.');
define('HEAD_KEY_TAG_INSTANT_QUOTE','buy name plates online, design name plates online, custom name plates online,  instant quote for name plates online');


// product_info.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_INFO_ON','0');
define('HTKA_PRODUCT_INFO_ON','0');
define('HTDA_PRODUCT_INFO_ON','0');
define('HTTA_CAT_PRODUCT_DEFAULT_ON','0');
define('HTPA_DEFAULT_ON','0');
define('HEAD_TITLE_TAG_PRODUCT_INFO','Name Plates in Australia - desk signs, door signs, office signs');
define('HEAD_DESC_TAG_PRODUCT_INFO','Name plates International Australia is a premier supplier for desk signs, door signs, office signs for corporate, professional and personalised. Sydney, Melbourne, Perth, Canberra, Brisbane, Hobart, Adelaide, Cairns...');
define('HEAD_KEY_TAG_PRODUCT_INFO','plates, name plates,  desk plates, door name plates');

// products_new.php - whats_new
define('HTTA_WHATS_NEW_ON','1');
define('HTKA_WHATS_NEW_ON','1');
define('HTDA_WHATS_NEW_ON','1');
define('HEAD_TITLE_TAG_WHATS_NEW','Desk & Door Name Plates - Name Plates International');
define('HEAD_DESC_TAG_WHATS_NEW','Name Plates International Australia is a premier supplier for corporate desk name plates, door name plates, office plates.');
define('HEAD_KEY_TAG_WHATS_NEW','plates, wedding plates, table plates, desk plates, door plates');

// specials.php
// If HEAD_KEY_TAG_SPECIALS is left blank, it will build the keywords from the products_names of all products on special
define('HTTA_SPECIALS_ON','1');
define('HTKA_SPECIALS_ON','1');
define('HTDA_SPECIALS_ON','1');
define('HEAD_TITLE_TAG_SPECIALS','Specials plates, name plates, office signs');
define('HEAD_DESC_TAG_SPECIALS','');
define('HEAD_KEY_TAG_SPECIALS','Name Badges International Professional Choice in Personalised Name Plates -  best source for sophisticated medallions, awards, plaques, key rings, badges, name badges, school badges, Metal or Plastic. Pin or Magnet. Design Name Badges Online.');

// product_reviews_info.php and product_reviews.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTKA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTDA_PRODUCT_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','Design online plates, name plates, office signs, desk signs, door signs');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','Name Plates International - Australia - desk signs, door signs, office signs, wedding plates');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','desk signs, door signs, office signs, wedding plates');

// product_reviews_write.php
define('HTTA_PRODUCT_REVIEWS_WRITE_ON','1');
define('HTKA_PRODUCT_REVIEWS_WRITE_ON','1');
define('HTDA_PRODUCT_REVIEWS_WRITE_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_WRITE','Design Name Plates Online - Name Plates International Australia');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_WRITE','Name Plates International Australia is a leading supplier for desk signs, door sings, office signs & all personalised name plates');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_WRITE','desk signs, door sings, office signs, buy plates online, name plates online');

// articles.php
define('HTTA_ARTICLES_ON','0'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_ARTICLES_ON','0'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_ARTICLES_ON','0'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_ARTICLES','Name Plates International Australia - Name Plates Australia | Name Plates Technical Guidelines');
define('HEAD_DESC_TAG_ARTICLES','Name Plates International offers the rare scope of custom making the name plates Australia, corporate signs & etc. Company is also offering a detailed guideline of how to design the name plates using artwork.');
define('HEAD_KEY_TAG_ARTICLES','name tags Australia, name badges Melbourne, name badges Perth, name badges Brisbane, name badges Sydney, Name Badge Gold Coast, Name tags and badges,Corporate name badges, name tags Australia, Professional name badges');

// article_info.php - if left blank in articles_description table these values will be used
define('HTTA_ARTICLE_INFO_ON','1');
define('HTKA_ARTICLE_INFO_ON','1');
define('HTDA_ARTICLE_INFO_ON','1');
define('HEAD_TITLE_TAG_ARTICLE_INFO','Design name plates , signs online - Name Plates International');
define('HEAD_DESC_TAG_ARTICLE_INFO','Design name plates online at name plates international. Fast delivery Sydney, Melbourne, Perth, Canberra, Brisbane, Hobart, Adelaide, Cairns...');
define('HEAD_KEY_TAG_ARTICLE_INFO','name plates, door signs, desk signs, office signs, office name plates');

// articles_new.php - new articles
// If HEAD_KEY_TAG_ARTICLES_NEW is left blank, it will build the keywords from the articles_names of all new articles
define('HTTA_ARTICLES_NEW_ON','1');
define('HTKA_ARTICLES_NEW_ON','1');
define('HTDA_ARTICLES_NEW_ON','1');
define('HEAD_TITLE_TAG_ARTICLES_NEW','Lastest Articles');
define('HEAD_DESC_TAG_ARTICLES_NEW','');
define('HEAD_KEY_TAG_ARTICLES_NEW','');

// article_reviews_info.php and article_reviews.php - if left blank in articles_description table these values will be used
define('HTTA_ARTICLE_REVIEWS_INFO_ON','1');
define('HTKA_ARTICLE_REVIEWS_INFO_ON','1');
define('HTDA_ARTICLE_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_ARTICLE_REVIEWS_INFO','Design online plates, name plates, desk & door signs');
define('HEAD_DESC_TAG_ARTICLE_REVIEWS_INFO','The Professional choice of name plates online in Australia. Fast delivery Sydney, Melbourne, Perth, Canberra, Brisbane, Hobart, Adelaide, Cairns.');
define('HEAD_KEY_TAG_ARTICLE_REVIEWS_INFO','desk signs, door signs, desk name plates, door name plates.');


// pages.php - if left blank in articles_description table these values will be used
define('HTTA_PAGE_INFO_ON','0');
define('HTKA_PAGE_INFO_ON','0');
define('HTDA_PAGE_INFO_ON','0');
define('HTTA_CAT_DEFAULT_ON','0');
define('HEAD_TITLE_TAG_PAGE_INFO','Professional Name Plates for Organisation | Name Plates Supplies in Perth, Brisbane, Sydney, Melbourne, Gold Coast');
define('HEAD_DESC_TAG_PAGE_INFO','Name Plates International Australia is a premier supplier for name plates. Fast delivery everywhere in Australia including Sydney, Melbourne, Perth, Canberra, Brisbane, Gold Coast, Hobart, Adelaide, Cairns is carried out.');
define('HEAD_KEY_TAG_PAGE_INFO','Personalised name plates, name plates Melbourne, name plates Perth, name plates Brisbane, Name plates Sydney, Professional name plates.');

?>