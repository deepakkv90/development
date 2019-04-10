<?php
/*
  $Id: links.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Links');

if ($display_mode == 'links') {
  define('HEADING_TITLE', 'Links');
  } elseif ($display_mode == 'categories') {
  define('HEADING_TITLE', 'Link Categories');
}

  define('TABLE_HEADING_LINKS_IMAGE', '');
  define('TABLE_HEADING_LINKS_TITLE', 'Title');
  define('TABLE_HEADING_LINKS_URL', 'URL');
  define('TABLE_HEADING_LINKS_DESCRIPTION', 'Description');
  define('TABLE_HEADING_LINKS_COUNT', 'Clicks');
  define('TEXT_NO_LINKS', 'There are no links to list in this category.');
  define('TEXT_NO_CATEGORIES', 'There are no link categories to list yet.');


// VJ todo - move to common language file
define('TEXT_DISPLAY_NUMBER_OF_LINKS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> links)');

define('IMAGE_BUTTON_SUBMIT_LINK', 'Submit Link');
?>
