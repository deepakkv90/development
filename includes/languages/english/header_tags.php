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
define('HEAD_TITLE_TAG_ALL','Engraved plaques | Wooden plaques | Awards in Australia - Awards Plaques International');
define('HEAD_DESC_TAG_ALL','Awards Plaques International Australia is a top supplier for custom plaques, awards and plaques, plaque engraving, personalized plaques');
define('HEAD_KEY_TAG_ALL','custom plaques, awards and plaques, plaque engraving, personalized plaques');

// DEFINE TAGS FOR INDIVIDUAL PAGES

// allprods.php
define('HTTA_ALLPRODS_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_ALLPRODS_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_ALLPRODS_ON','1'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_ALLPRODS','Personalized Plaques and Awards Supplies in Australia - Awards Plaques International');
define('HEAD_DESC_TAG_ALLPRODS','Professional Choice in Personalised Plaques and Awards in Australia. Design Name Badges Online.');
define('HEAD_KEY_TAG_ALLPRODS','awards, plaques, buy awards online, buy plaques online, design awards online, design plaques online, custom plaques');

// index.php
define('HTTA_DEFAULT_ON','0'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_DEFAULT_ON','0'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_DEFAULT_ON','0'); // Include HEAD_DESC_TAG_ALL in Description
define('HTTA_CAT_DEFAULT_ON','0'); //Include HEADE_TITLE_DEFAULT in CATEGORY DISPLAY
define('HEAD_TITLE_TAG_DEFAULT','Buy Button Badge Online - Button Badges International');
define('HEAD_DESC_TAG_DEFAULT','Design & buy button badges for your all events with free design of service at Button Badges International. Visit our online shop now!');
define('HEAD_KEY_TAG_DEFAULT','button badges, button badges online');

// product_info.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_INFO_ON','0');
define('HTKA_PRODUCT_INFO_ON','0');
define('HTDA_PRODUCT_INFO_ON','0');
define('HTTA_CAT_PRODUCT_DEFAULT_ON','0');
define('HTPA_DEFAULT_ON','0');
define('HEAD_TITLE_TAG_PRODUCT_INFO','Plaque engraving | Personalized plaques | Name plaques - Awards Plaques International');
define('HEAD_DESC_TAG_PRODUCT_INFO','Awards Plaques International Australia is a premier supplier for custom plaques, awards and plaques, plaque engraving, personalized plaques. Sydney, Melbourne, Perth, Canberra, Brisbane, Hobart, Adelaide, Cairns...');
define('HEAD_KEY_TAG_PRODUCT_INFO','plaque maker, name plaques, plaques online, certificate awards');

// products_new.php - whats_new
define('HTTA_WHATS_NEW_ON','1');
define('HTKA_WHATS_NEW_ON','1');
define('HTDA_WHATS_NEW_ON','1');
define('HEAD_TITLE_TAG_WHATS_NEW','name plaques, certificate awards');
define('HEAD_DESC_TAG_WHATS_NEW','Awards Plaques International Australia is a premier supplier for corporate Awards and Plaques. Custom plaques for corporate, professional and personalised. Sydney, Melbourne, Perth, Canberra, Brisbane, Hobart, Adelaide, Cairns...');
define('HEAD_KEY_TAG_WHATS_NEW','plaque maker, name plaques, plaques online, certificate awards');

// specials.php
// If HEAD_KEY_TAG_SPECIALS is left blank, it will build the keywords from the products_names of all products on special
define('HTTA_SPECIALS_ON','1');
define('HTKA_SPECIALS_ON','1');
define('HTDA_SPECIALS_ON','1');
define('HEAD_TITLE_TAG_SPECIALS','awards and plaques, plaque engraving, personalized plaques');
define('HEAD_DESC_TAG_SPECIALS','');
define('HEAD_KEY_TAG_SPECIALS','Name Badges International Professional Choice in Personalised Awards and Plaques - Design Name Badges Online.');

// product_reviews_info.php and product_reviews.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTKA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTDA_PRODUCT_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','certificate awards | engraved plaques | wooden plaques');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','Awards Plaques International - custom plaques, plaques and awards, engraved plaques, wooden plaques, awards and plaques, plaque engraving, personalized plaques, plaque maker, name plaques, plaques online, certificate awards');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','Awards Plaques International - custom plaques, plaques and awards, engraved plaques, wooden plaques, awards and plaques');

// product_reviews_write.php
define('HTTA_PRODUCT_REVIEWS_WRITE_ON','1');
define('HTKA_PRODUCT_REVIEWS_WRITE_ON','1');
define('HTDA_PRODUCT_REVIEWS_WRITE_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_WRITE','Design online badges, name badges, name tags');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_WRITE','Name Badges International Australia is a premier supplier for corporate name badges – magnetic, metal &amp; plastic. Custom name badges for corporate, professional and personalised. Sydney, Melbourne, Perth, Canberra, Brisbane, Hobart, Adelaide, Cairns...');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_WRITE','');

// articles.php
define('HTTA_ARTICLES_ON','0'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_ARTICLES_ON','0'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_ARTICLES_ON','0'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_ARTICLES','Name Badges International Australia - Name Tags Australia | Name badges Technical Guidelines');
define('HEAD_DESC_TAG_ARTICLES','Name Badges International offers the rare scope of custom making the name tags Australia, corporate name badges, etc. Company is also offering a detailed guideline of how to design the name badges and tags using artwork.');
define('HEAD_KEY_TAG_ARTICLES','name tags Australia, name badges Melbourne, name badges Perth, name badges Brisbane, name badges Sydney, Name Badge Gold Coast, Name tags and badges,Corporate name badges, name tags Australia, Professional name badges');

// article_info.php - if left blank in articles_description table these values will be used
define('HTTA_ARTICLE_INFO_ON','1');
define('HTKA_ARTICLE_INFO_ON','1');
define('HTDA_ARTICLE_INFO_ON','1');
define('HEAD_TITLE_TAG_ARTICLE_INFO','Design online badges, name badges, name tags');
define('HEAD_DESC_TAG_ARTICLE_INFO','Name Badges International Australia is a premier supplier for corporate name badges – magnetic, metal &amp; plastic. Custom name badges for corporate, professional and personalised. Sydney, Melbourne, Perth, Canberra, Brisbane, Hobart, Adelaide, Cairns...');
define('HEAD_KEY_TAG_ARTICLE_INFO','beName Badges International Professional Choice in Personalised Name Badges -  st source for sophisticated medallions, awards, plaques, key rings, badges, name badges, school badges, Metal or Plastic. Pin or Magnet. Design Name Badges Online.');

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
define('HEAD_TITLE_TAG_ARTICLE_REVIEWS_INFO','Design online badges, name badges, name tags');
define('HEAD_DESC_TAG_ARTICLE_REVIEWS_INFO','Name Badges International Australia is a premier supplier for corporate name badges – magnetic, metal &amp; plastic. Custom name badges for corporate, professional and personalised. Sydney, Melbourne, Perth, Canberra, Brisbane, Hobart, Adelaide, Cairns...');
define('HEAD_KEY_TAG_ARTICLE_REVIEWS_INFO','Professional Choice in Personalised Name Badges -  best source for sophisticated name badges, school badges, Metal or Plastic. Pin or Magnet. Design Name Badges Online.');


// pages.php - if left blank in articles_description table these values will be used
define('HTTA_PAGE_INFO_ON','0');
define('HTKA_PAGE_INFO_ON','0');
define('HTDA_PAGE_INFO_ON','0');
define('HTTA_CAT_DEFAULT_ON','0');
define('HEAD_TITLE_TAG_PAGE_INFO','Professional Name Badges for staff | Name Badges Supplies in Perth, Brisbane, Sydney, Melbourne, Gold Coast');
define('HEAD_DESC_TAG_PAGE_INFO','Name Badges International Australia is a premier supplier for corporate name badges – magnetic, metal , plastic. Custom name badges for corporate, professional and personalised are available. Fast delivery everywhere in Australia including Sydney, Melbourne, Perth, Canberra, Brisbane, Gold Coast, Hobart, Adelaide, Cairns is carried out.');
define('HEAD_KEY_TAG_PAGE_INFO','Reusable name badge, Personalised name badges, name badges Melbourne, name badges Perth, name badges Brisbane, Name badges Sydney, Professional name badges.');

?>