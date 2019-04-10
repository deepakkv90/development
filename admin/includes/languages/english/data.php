<?php
/*

  Copyright (c) 2005 - 2007 Chainreactionworks.com

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Data Import/Export System');

define('HEADING_TITLE_1', 'Add New Feed Setting');
define('HEADING_TITLE_2', 'Edit Feed Setting');

define('TABLE_HEADING_FEED_NAME', 'Name of Feed');
define('TABLE_HEADING_FEED_DISC', 'Data Feed Description');


define('TEXT_FEED_HELP', 'Do each step in order it is list for each feed');

define('TEXT_FEED_HELP_CONFIGURE', '1. Click on the run button to run processes');
define('TEXT_FEED_HELP_SELECT', '2. Click on the ' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO). ' for more information');
define('TEXT_FEED_HELP_PREFEED', '3. Configure, Build categories, Pre process, Submit feed');
define('TEXT_FEED_HELP_RUN', '');
define('TEXT_FEED_HELP_NOTES', 'Notes: Feeds with * are not installed');


define('TEXT_FEED_GOOGLE', '<b> Google Base </b>');
define('TEXT_FEED_YAHOO', '<b>Yahoo</b>');
define('TEXT_FEED_BIZRATE', '<b>Bizrate</b>');
define('TEXT_FEED', 'Feed: ');
define('TEXT_CONFIGURE', 'Configure: ');

define('TEXT_RUN_CONFIGURE', 'Run Configure');
define('TEXT_FEED_RUN_PRE_FEED', 'Run Pre Feed');
define('TEXT_FEED_RUN_FEED', 'Submit Feed');

define('TEXT_SET_CATEGORIES', 'Set Categories: ');
define('IMAGE_SET_CATEGORIES', 'Set Categories');
define('TEXT_FEED_SELECT', 'Select Feed: ');
define('TEXT_FEED_PRE_FEED', 'Pre Feed Process: ');
define('TEXT_FEED_RUN', 'Submit Feed: ');
define('IMAGE_FEED_RUN', 'Submit Feed');  

define('TEXT_FEED_CONFIGURE_HELP1', 'Build or edit unique configuration');
define('TEXT_SET_CATEGORIES_HELP1', 'You do not need to run this process every time');
define('TEXT_FEED_PRE_FEED_HELP1', 'Build feed the file');
define('TEXT_FEED_PRE_EDIT_HELP1', 'Check Feed in text editor');
define('TEXT_FEED_RUN_HELP1', 'Send feed to Google');


define('TEXT_INFO_FEED_NAME', 'Feed Name: ');
define('TEXT_INFO_FEED_TYPE', 'Feed Type: ');
define('TEXT_INFO_FEED_SERVICE', 'Feed service: ');
define('TEXT_INFO_FEED_STATUS', 'Status: ');


define('TEXT_INFO_STATUS_CHANGE', 'Status Change:');

define('TEXT_DISPLAY_NUMBER_OF_DATA', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> data setups)');
define('IMAGE_NEW_SETTING', 'New feed setting');

define('WELCOME_TO_DATA_EXPORT_IMPORT_SYSTEM', 'Welcome to the Data Export/Import System Help');
define('TEXT_HELP_EASY_POPULATE', 'Easy Populate:');
define('TEXT_EP_INTRO', 'Introduction to Data Export/Import System');
define('TEXT_EP_ADV_IMPORT', 'EP Advance Import');
define('TEXT_EP_ADV_EXPORT', 'EP Advance Export');
define('TEXT_EP_BASIC_IMPORT', 'EP Basic Import');
define('TEXT_EP_BASIC_EXPORT', 'EP Basic Export');
define('TEXT_EP_EDITING_FILE', 'Editing the Export File');
//data feeder system
define('TEXT_HELP_DATA_FEEDER_SYSTEM', 'Data Feeder System:');
define('TEXT_DATA_INTRO', 'Introduction to Data Feed');
define('TEXT_DATA_FIRST_GOOGLE_FEED', 'Your First Google Base Feed');
define('TEXT_DATA_CONFIGURE_FEED', 'Configure a Feed');
define('TEXT_DATA_RUN_FEED', 'Run a Feed');

define('TEXT_INFO_FEED_MISSING', 'If your feeds are missing from the RUN drop down, Please RUN the &quot;Configure&quot; and change the Feed Service from Froogle to Google Base. The FTP server has also changed.<br> This message will apear as long as there is a feed with the Feed Service of &quot;froogle&quot; is stored in the database');
define('TEXT_BUILD_SUCCESS', 'Data feed successfully created!');
?>