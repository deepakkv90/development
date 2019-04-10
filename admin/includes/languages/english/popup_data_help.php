<?php
/*
  $Id: popup_data_help.php,v 1.1 $

  Copyright (c) 2005 Chainreactionworks.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Data Input/output Help');
define('TEXT_INFO_HEADING_NEW_DATA', 'Data Input/output Help');

define('TEXT_INFO_GOOGLE_HEADING_SET_CATEGORIES', 'Categories Help');
define('TEXT_INFO_GOOGLE_HEADING_CONFIGURE', 'Configure Help');
define('TEXT_INFO_GOOGLE_HEADING_PRE_FEED', 'Pre Feed Help');
define('TEXT_INFO_GOOGLE_HEADING_RUN', 'Run Feed Help');

define('TEXT_GOOGLE_SET_CATEGORIES_HELP', 'Run this step the first time you do a Google feed. <br> After you have run this the first time do this step only if you have added or changed categories.  This step is very process intense and time consuming for large sites. It will build all of your catagory paths for you.');
define('TEXT_GOOGLE_CONFIGURE_HELP', 'You can build multiple configuration for the Google feed. You must have at least one Google configuration');
define('TEXT_GOOGLE_PRE_FEED_HELP', 'In this step you will assemble the data into a text file that will be submitted to Froogle. If the process takes more then 1 minute you have a large store or there is a problem.');
define('TEXT_GOOGLE_RUN_HELP', 'This is the last step. It will submit the feed directly to Google if you have entered the correct FTP information in the configuration. You must wait 30 minute between submissions');

define('TEXT_INFO_GOOGLE_FEED_NAME', 'Name of Feed Help');
define('TEXT_INFO_GOOGLE_FEED_DISC', 'Data Feed Description Help');
define('TEXT_INFO_GOOGLE_FEED_FILE_TYPE', 'Data Feed File Type Help');
define('TEXT_INFO_GOOGLE_FEED_TYPE', 'Feed Type Help');
define('TEXT_INFO_GOOGLE_FEED', '<b>Google FTP information</b>');
define('TEXT_INFO_GOOGLE_FEED_FEED_SERVICE', 'Feed Service Help');
define('TEXT_INFO_GOOGLE_FEED_STATUS', 'Status Help');
define('TEXT_INFO_GOOGLE_FEED_FILE', 'File name Help');
define('TEXT_INFO_GOOGLE_FEED_IMAGE', 'Image URL Help');
define('TEXT_INFO_GOOGLE_FEED_PRODUCT', 'Product URL Help');
define('TEXT_INFO_GOOGLE_FEED_FTP_SERVER', 'Google FTP Server Name Help');
define('TEXT_INFO_GOOGLE_FEED_FTP_USER', 'Google FTP User Name Help');
define('TEXT_INFO_GOOGLE_FEED_FTP_PASSWORD', 'Google FTP Password Help');
define('TEXT_INFO_GOOGLE_FEED_FTP_DIRECTORY', 'Google FTP Directory Help');
define('TEXT_INFO_GOOGLE_FEED_CUR', 'Use store Currency Help');
define('TEXT_INFO_GOOGLE_FEED_CUR_USE', 'Other currency Help');
define('TEXT_INFO_GOOGLE_FEED_LANG', 'Use store languge Help');
define('TEXT_INFO_GOOGLE_FEED_LANG_USE', 'Other languge Help');
define('TEXT_INFO_GOOGLE_FEED_CUR_CON', 'Convert Currency Help');
define('TEXT_INFO_GOOGLE_FEED_TAX', 'Tax Class ID Help');


define('TEXT_GOOGLE_FEED_NAME_HELP', 'Enter a unique name for this frogle feed.  You will use this name later in the process to identify this feed configuration..');
define('TEXT_GOOGLE_FEED_DISC_HELP', 'Enter a short description to identify this feed configuration. You can also add a few notes here to help discribe this feed');
define('TEXT_GOOGLE_FEED_FILE_TYPE_HELP', 'Select the type of feed from the drop down. Use product for your normal product listing, Businees is for changing business information. ');
define('TEXT_GOOGLE_FEED_TYPE_HELP', 'Use basic for a basic feed and advance for advance feed. <br> Note: not all of the advance feed items have been installed.');
define('TEXT_GOOGLE_FEED_HELP', '<b>Google FTP information</b>');
define('TEXT_GOOGLE_FEED_FEED_SERVICE_HELP', 'Select which feed this configuration is for. Only Google feed is installed. ');
define('TEXT_GOOGLE_FEED_STATUS_HELP', 'Active or Inactive. Inactive configurations will not be available in the data feed page');
define('TEXT_GOOGLE_FEED_FILE_HELP', 'Type in the file name of the file you want to store your feed in. Its should be unique and not duplicate in other feeds');
define('TEXT_GOOGLE_FEED_IMAGE_HELP', 'Type in the patch to your product images, it should start from the images directory. <br> Example: http://mysite.com/images/categories/t_4647.jpg would be  categories/ ');
define('TEXT_GOOGLE_FEED_PRODUCT_HELP', 'This is not used');
define('TEXT_GOOGLE_FEED_FTP_SERVER_HELP', 'Name of the feed server. For Google feed leave blank and hedwig.google.com will be used');
define('TEXT_GOOGLE_FEED_FTP_USER_HELP', 'Enter the FTP user name you entered when you signed up for the service.');
define('TEXT_GOOGLE_FEED_FTP_PASSWORD_HELP', 'Enter the FTP user password you entered when you signed up for the service.');
define('TEXT_GOOGLE_FEED_FTP_DIRECTORY_HELP', 'Enter the FTP directory that you were assigned when you signed up for the service.');
define('TEXT_GOOGLE_FEED_CUR_HELP', 'False: Use the store default currency. If you click on this radio button you will use the default store currency. ');
define('TEXT_GOOGLE_FEED_CUR_USE_HELP', 'If you checked True in the above line enter the alternete currency code here. It must be a currency code that the service knows.');
define('TEXT_GOOGLE_FEED_LANG_HELP', 'False: Use store default language indicates that you want to use the store default language.');
define('TEXT_GOOGLE_FEED_LANG_USE_HELP', 'If you entered true into the above line, Use the drop down box to choose a different language code.');
define('TEXT_GOOGLE_FEED_CUR_CON_HELP', 'Convert to store currency when the visiter visits your store. Use this is you have multiple currencies in your store.');
define('TEXT_GOOGLE_FEED_TAX_HELP', 'Enter the tax class ID number from the store or set to zero (0) for no tax caculation');



define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');

?>
