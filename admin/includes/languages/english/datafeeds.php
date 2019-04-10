<?php
/*
  $Id: datafeeds.php,v 1.0.0 2009/06/14 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Data Feed Manager');

define('TABLE_HEADING_FEED_NAME', 'Feed Name');
define('TABLE_HEADING_FEED_TYPE', 'Feed Type');
define('TABLE_HEADING_FEED_SERVICE', 'Feed Service');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_DISPLAY_NUMBER_OF_FEEDS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Data Feeds)');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this data feed?'); 
define('TEXT_INFO_HEADING_DELETE_GATEWAY', 'Delete Data Feed'); 
define('TEXT_FEED_INFO', 'Data Feed Info');
define('TEXT_AUTO_SEND', 'Automatically Send after Build:');
define('TEXT_LANGUAGE', 'Language:');
define('TEXT_CURRENCY', 'Currency:');
define('TEXT_DATE_CREATED', 'Date Created:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_HEADING_DELETE_DATAFEED', 'Delete Data Feed?');
define('TEXT_FEED_SERVICE', 'Data Feed Service');
define('TEXT_SEND_ERROR', '<span style="color: #FF0000;">Send Error, missing:</span>');

define('ENTRY_FEED_NAME', 'Data Feed Name:');
define('ENTRY_FEED_TYPE', 'Data Feed Type:');
define('ENTRY_FEED_DESC', 'Data Feed Description:');
define('ENTRY_FEED_SERVICE', 'Data Feed Service:');
define('ENTRY_FEED_FILENAME', 'Data Feed Filename:');
define('ENTRY_FEED_FILE_TYPE', 'Data Feed File Type:');
define('ENTRY_FEED_FTP_USER', 'Service FTP Username:');
define('ENTRY_FEED_FTP_PASS', 'Service FTP Password:');
define('ENTRY_FEED_LANGUAGE', 'Feed Language:');
define('ENTRY_FEED_CURRENCY', 'Feed Currency:');
define('ENTRY_FEED_TAX_CLASS', 'Feed Tax Class:');
define('ENTRY_FEED_PRICE_GROUP', 'Feed Price Group:');

define('ENTRY_FEED_NAME_ERROR', '<span class="errorText">Feed Name cannot be blank.</span>');

define('BLOCK_HELP_HELPTIP_1', 'Enter a unique name for this feed. You will use this name later in the process to identify this feed configuration.');
define('BLOCK_HELP_HELPTIP_2', 'Advance allows you to add the attributes: quantity, currency, ship_to, age_range, made_in.  Adult content must have the attribute age_range or it will be rejected.');
define('BLOCK_HELP_HELPTIP_3', 'Enter a short description to identify this feed configuration. You can also add a few notes here to help describe this feed.');
define('BLOCK_HELP_HELPTIP_4', 'Select the Service for this feed.');
define('BLOCK_HELP_HELPTIP_5', 'Enter the output XML filename assigned when you signed up for the service.');
define('BLOCK_HELP_HELPTIP_6', 'Select  XML for XML output or Text for text output.  Larger databases may benefit from smaller processing times using text format.');
define('BLOCK_HELP_HELPTIP_7', 'Enter the FTP Username you setup for this service.');
define('BLOCK_HELP_HELPTIP_8', 'Enter the FTP Password you setup for this service.');
define('BLOCK_HELP_HELPTIP_9', 'Currency to use when building this feed.');
define('BLOCK_HELP_HELPTIP_10', 'Language to use when building this feed.');
define('BLOCK_HELP_HELPTIP_11', 'Select the tax class or set to blank for no tax calculation.');
define('BLOCK_HELP_HELPTIP_12', 'Select the customer price group for this feed.');

define('IMAGE_NEW_FEED', 'Add New Data Feed');
define('IMAGE_UPDATE_FEED', 'Update Data Feed');
define('IMAGE_BUILD_FEED', 'Build Feed');
define('IMAGE_SEND_FEED', 'Send Feed');
define('TEXT_BUILD_SUCCESS', 'Date feed successfully created!');
?>