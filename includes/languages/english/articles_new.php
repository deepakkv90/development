<?php
/*
  $Id: articles_new.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'New Articles');
define('HEADING_TITLE', 'New Articles');

define('TEXT_NEW_ARTICLES', 'The following articles were added in the last %s days.');
define('TEXT_NO_NEW_ARTICLES', 'No new articles have been added in the last %s days.');
define('TEXT_DATE_ADDED', 'Published:');
define('TEXT_AUTHOR', 'Author:');
define('TEXT_TOPIC', 'Topic:');
if (!defined('TEXT_BY')) {
   define('TEXT_BY', 'by');
}
define('TEXT_READ_MORE', 'Read More');
define('TABLE_HEADING_NEW_ARTICLES', 'New Articles in %s'); // needed for mainpage module
?>
