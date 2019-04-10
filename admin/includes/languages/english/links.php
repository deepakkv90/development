<?php
/*
  $Id: links.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Links');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_STATUS','Status:');
define('HEADING_TITLE_CATEGORIES','Categories:');

define('TABLE_HEADING_TITLE', 'Title');
define('TABLE_HEADING_URL', 'URL');
define('TABLE_HEADING_CLICKS', 'Clicks');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_HEADING_DELETE_LINK', 'Delete Link');
define('TEXT_INFO_HEADING_CHECK_LINK', 'Check Link');

define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this link?');

define('TEXT_INFO_LINK_CHECK_RESULT', 'Link Check Result:');
define('TEXT_INFO_LINK_CHECK_FOUND', 'Found');
define('TEXT_INFO_LINK_CHECK_NOT_FOUND', 'Not Found');
define('TEXT_INFO_LINK_CHECK_ERROR', 'Error Reading URL');


define('TEXT_INFO_LINK_STATUS', 'Status:');
define('TEXT_INFO_LINK_CATEGORY', 'Category:');
define('TEXT_INFO_LINK_CONTACT_NAME', 'Contact Name:');
define('TEXT_INFO_LINK_CONTACT_EMAIL', 'Contact Email:');
define('TEXT_INFO_LINK_CLICK_COUNT', 'Clicks:');
define('TEXT_INFO_LINK_DESCRIPTION', 'Description:');
define('TEXT_DATE_LINK_CREATED', 'Link Submitted:');
define('TEXT_DATE_LINK_LAST_MODIFIED', 'Last Modified:');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Link Status Update');
define('EMAIL_TEXT_STATUS_UPDATE', 'Dear %s,' . "\n\n" . 'The status of your link at ' . STORE_NAME . ' has been updated.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");

// VJ todo - move to common language file
define('CATEGORY_WEBSITE', 'Website Details');
define('CATEGORY_RECIPROCAL', 'Reciprocal Page Details');
if (!defined('CATEGORY_OPTIONS')) {
  define('CATEGORY_OPTIONS', 'Options');
}

define('ENTRY_LINKS_TITLE', 'Site Title:');
define('ENTRY_LINKS_TITLE_ERROR', 'Link title must contain a minimum of ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_URL', 'URL:');
define('ENTRY_LINKS_URL_ERROR', 'URL must contain a minimum of ' . ENTRY_LINKS_URL_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_CATEGORY', 'Category:');
define('ENTRY_LINKS_DESCRIPTION', 'Description:');
define('ENTRY_LINKS_DESCRIPTION_ERROR', 'Description must contain a minimum of ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_IMAGE', 'Image URL:');
define('ENTRY_LINKS_CONTACT_NAME', 'Full Name:');
define('ENTRY_LINKS_CONTACT_NAME_ERROR', 'Your Full Name must contain a minimum of ' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_RECIPROCAL_URL', 'Reciprocal Page:');
define('ENTRY_LINKS_RECIPROCAL_URL_ERROR', 'Reciprocal page must contain a minimum of ' . ENTRY_LINKS_URL_MIN_LENGTH . ' characters.');
define('ENTRY_LINKS_STATUS', 'Status:');
define('ENTRY_LINKS_NOTIFY_CONTACT', 'Notify Contact:');
define('ENTRY_LINKS_RATING', 'Rating:');
define('ENTRY_LINKS_RATING_ERROR', 'Rating should not be empty.');

define('TEXT_DISPLAY_NUMBER_OF_LINKS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> links)');

define('IMAGE_NEW_LINK', 'New Link');
define('IMAGE_CHECK_LINK', 'Check Link');


define('ALL', 'All');
define('LINKS_MANAGER_CHECKED_LINKS', 'Links Manager - Checked Links');
define('ASC', 'Asc');
define('DESC', 'Desc');
define('START_AT', 'Start at:');
define('HOW_MANY', 'How many?');

define('TEXT_LINKS_GROUP_ACCESS','Group Access');
define('LINKS_GUEST', ' Guest');
define('LINKS_SHOW', ' Show');
define('LINKS_HIDE', ' Hide');   
?>
