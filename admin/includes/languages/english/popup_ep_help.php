<?php
/*
  $Id: popup_ep_help.php,v 1.1 $
  
  Copyright (c) 2005 Chainreactionworks.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Easy Populate Help');
define('TEXT_INFO_HEADING_NEW_DATA', 'Easy Populate Help');

define('TEXT_HEAD_HELP_EP_FILE_UPLOAD', 'File Up Load Help');
define('TEXT_HELP_EP_FILE_UPLOAD', 'This allow you to upload the edited EPA file so it can be inputed in to your database.
                                    There is a limit to the size of file you can upload. Usually this limit is 2mb. This is because of 
                                    how PHP is usually installed. If this is a problem then try to limit the files size by using the
                                    Limit number of products to Download options to make your file smaller. 
                                    If the number of records in the file is more then 300 use the Upload and Split.');

define('TEXT_HEAD_HELP_EP_FILE_UPLOAD_SPLIT', 'File Up Load and Split Help');
define('TEXT_HELP_EP_FILE_UPLOAD_SPLIT', 'This allow you to upload the edited EPA file and split itinto smaller parts so it can be inputed into your databse.
                                 As with the regular upload there is a limit on the file size of 2mb. Once you have Uploaded and Split
                                 the EPA file. Use the " Import Data from file in temp/ " to insert each file into the database. The number or records you set
                                 in the epconfigure will limit the records in the spilt files. This is done so the program will not time out.');

define('TEXT_HEAD_HELP_EP_FILE_SPLIT', ' Split a EP File on the server');
define('TEXT_HELP_EP_FILE_SPLIT', 'This allow you to spilt the edited EPA file it into smaller parts, after it has been uploaded to the server, so it can be inputed into your databse.
                                 Once you have "Split a EP File on the server"
                                 the EPA file. Use the " Import Data from file in temp/ " to insert each file into the database. The number or records you set
                                 in the admin/epconfigure.php will limit the records in the spilt files. The current limit is 1,000 records but EPA can handle well over 2,000 records, this should take 3 to 4 minutes to insert. This is done so the program will not time out.');


define('TEXT_HEAD_HELP_EP_FILE_INSERT', 'File Insert a File Help');
define('TEXT_HELP_EP_FILE_INSERT', 'Select a file in the drop down box. This is a list the files that have been uploaded or split in the directory
                                    You can choosen to store your EPA file on line. The number of records in each split is set in 
                                    the ep configuration');

define('TEXT_HEAD_HELP_EP_FILE_EXPORT', 'Export a file to edit Help');
define('TEXT_HELP_EP_FILE_EXPORT', 'In this section you can create a export file to be edited in a spread sheet program. If the export file or resulting
                                    edited file is over 2mb in size you can use the export controls to limit the the size by limiting the data you export.');

define('TEXT_HEAD_HELP_EP_SELECT_METHOD', 'Select method to use for download Help');
define('TEXT_HELP_EP_SELECT_METHOD', 'Here you can select one of two meathods for downloading your export file. "Download" method
                                      will create a file to be saved it directly to your local computer. "Save to temp file on server"
                                      will save the file to the temp directory you set in the ep configure. Later you can use a FTP program to
                                      download the file');

define('TEXT_HEAD_HELP_EP_SELECT_DOWN', 'Select feild groups to export Help');
define('TEXT_HELP_EP_SELECT_DOWN', 'In this drop down is listed the pre_defined groups of feild that can be downloaded. Please see the
                                    documentation for which feilds are in which groups.');

define('TEXT_HEAD_HELP_EP_SELECT_SORT', 'Select sort order for export Help');
define('TEXT_HELP_EP_SELECT_SORT', 'You can select the order your export rows apears in. This is used to group the rows in your export
                                    file so they can be found easily. ');

define('TEXT_HEAD_HELP_EP_LIMIT_ROWS', 'Limit rows in your Export file Help');
define('TEXT_HELP_EP_LIMIT_ROWS', 'This group of settings can be used to limit the size of your export file. This is done
                                    if your import file is to large or you need to target certain record to be edited.');

define('TEXT_HEAD_HELP_EP_LIMIT_CATS', 'Limit rows in your Export file by Categories Help');
define('TEXT_HELP_EP_LIMIT_CATS', 'This will limit the contents of the export file to a specifice Category. When set to "Top" all
                                  manufactures will be in the export file. When set to a specific category all sub categories will be included.');

define('TEXT_HEAD_HELP_EP_LIMIT_MAN', 'Limit rows in your Export file by Manufacture Help');
define('TEXT_HELP_EP_LIMIT_MAN', 'This will limit the contents of the export file to a specifice manufacture. When set to "Manufactures" all
                                  manufacture swill be in the export file.');

define('TEXT_HEAD_HELP_EP_LIMIT_PRODUCT', 'Limit rows in your Export file by Product_id Help');
define('TEXT_HELP_EP_LIMIT_PRODUCT', 'This will limit the contents of the export file to a range of product_id\'s. ');

define('TEXT_HELP_EP_LIMIT_PRODUCT1', '   1. If no product_id\'s are enter all of the product are in the export file');
define('TEXT_HELP_EP_LIMIT_PRODUCT2', '   2. If the begin product_id and no end product_id is entered. Products from the first product id to the end are in the export file');
define('TEXT_HELP_EP_LIMIT_PRODUCT3', '   3. If no beginning product_id\'s aand only and ending product_ID. From the first id to ending the products are in the export file');
define('TEXT_HELP_EP_LIMIT_PRODUCT4', '   4. If the begin product_id and ending product_id is enter then this range of products are in the export file');

define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');

?>
