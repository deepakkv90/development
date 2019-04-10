<?php
/*
  $Id: easypopulate.php,v 1.4 2004/09/21  zip1 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 Chainreactionworks.com

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Easy Populate Configuration');
define('EASY_VERSION_A', 'Easy Populate Advanced ');
define('EASY_VERSION_B', 'Easy Populate Basic ');
define('EASY_EXPORT', ' Export');
define('EASY_IMPORT', ' Import');
define('EASY_VER_A', '');
define('EASY_VER_B', '');
define('EASY_DEFAULT_LANGUAGE', '  -  Default Language ');
define('EASY_UPLOAD_FILE', 'File uploaded. ');
define('EASY_UPLOAD_TEMP', 'Temporary filename: ');
define('EASY_UPLOAD_USER_FILE', 'User filename: ');
define('EASY_SIZE', 'Size: ');
define('EASY_FILENAME', 'Filename: ');
define('EASY_SPLIT_DOWN', 'Splitting of file complete. The split(s) are located at: ');
define('EASY_UPLOAD_EP_FILE', 'Upload EP File for Import');
define('EASY_SPLIT_EP_FILE', 'Upload and Split a EP File');
define('EASY_SPLIT_EP_LOCAL', 'Split a EP File on the server');

define('TEXT_IMPORT_TEMP', 'Import Data from file in %s');
define('TEXT_INSERT_INTO_DB', 'Insert into DB');
define('TEXT_SELECT_ONE', 'Select a EP File for Import');
define('TEXT_SELECT_TWO', 'Select a EP File for Splitting');

define('TEXT_SPLIT_FILE', 'Select a EP File');
define('TEXT_SPLIT', 'Split a EP File');

define('EASY_LABEL_CREATE', 'Create an export file');
define('EASY_LABEL_CREATE_SELECT', 'Select method to save export file');
define('EASY_LABEL_CREATE_SAVE', 'Save to temp file on server');
define('EASY_LABEL_SELECT_DOWN', 'Select field set to export');
define('EASY_LABEL_SORT', 'Select field for sort order');
define('EASY_LABEL_PRODUCT_RANGE', 'Limit by Products_ID(s)');
define('EASY_LABEL_LIMIT_CAT', 'Limit By Category');
define('EASY_LABEL_LIMIT_MAN', 'Limit By Manufacturer');

define('EASY_LABEL_PRODUCT_AVAIL', 'Range Available: ');
define('EASY_LABEL_PRODUCT_TO', ' to ');
define('EASY_LABEL_PRODUCT_RECORDS', '    Total number of records: ');
define('EASY_LABEL_PRODUCT_BEGIN', 'begin: ');
define('EASY_LABEL_PRODUCT_END', 'end: ');
define('EASY_LABEL_PRODUCT_START', 'Start File Creation ');

define('EASY_FILE_LOCATE', 'Building your export file is completed. Your files is named: ');
define('EASY_FILE_LOCATE2', 'Building your export file is completed and saved to your local computer. Your files is named: ');

define('EASY_FILE_LOCATE_2', ' by clicking this Link and going to the file manager');
define('EASY_FILE_RETURN', ' You can return to EP by clicking this link.');
define('EASY_IMPORT_TEMP_DIR', 'Import from Temp Dir ');
define('EASY_LABEL_DOWNLOAD', 'Download');
define('EASY_LABEL_COMPLETE', 'Complete');
define('EASY_LABEL_TAB', 'tab-delimited .txt file to edit');
define('EASY_LABEL_MPQ', 'Model/Price/Qty');
define('EASY_LABEL_EP_MC', 'Model/Category');

define('EASY_LABEL_EP_ATTRIB', 'Attributes');
define('EASY_LABEL_NONE', 'None');
define('EASY_LABEL_CATEGORY', '1st Category Name');
define('PULL_DOWN_MANUFACTURES', 'Manufacturers');
define('EASY_LABEL_PRODUCT', 'Product ID Number');
define('EASY_LABEL_MANUFACTURE', 'Manufacturer ID Number');
define('EASY_LABEL_EP_MA', 'Model/Attributes');
define('EASY_LABEL_EP_FR_TITLE', 'Create EP or Froogle Files in Temp Dir ');
define('EASY_LABEL_EP_DOWN_TAB', 'Create <b>Complete</b> tab-delimited .txt file in temp dir');
define('EASY_LABEL_EP_DOWN_MPQ', 'Create <b>Model/Price/Qty</b> tab-delimited .txt file in temp dir');
define('EASY_LABEL_EP_DOWN_MC', 'Create <b>Model/Category</b> tab-delimited .txt file in temp dir');
define('EASY_LABEL_EP_DOWN_MA', 'Create <b>Model/Attributes</b> tab-delimited .txt file in temp dir');
define('EASY_LABEL_EP_LIMIT', 'Limit number of products to Export');
define('EASY_LABEL_NEW_PRODUCT', "<font color='green'> !New Product!</font><br>");
define('EASY_LABEL_UPDATED', "<font color='blue'> Updated</font><br>");
define('EASY_LABEL_DELETE_STATUS_1', "<font color='red'> !!Deleting product ");
define('EASY_LABEL_DELETE_STATUS_2', " from the database !!</font><br>");
define('EASY_LABEL_LINE_COUNT_1', 'Added ');
define('EASY_LABEL_LINE_COUNT_2', 'records and closing file... ');
define('EASY_LABEL_FILE_COUNT_1A', 'Creating file EPA_Split ');
define('EASY_LABEL_FILE_COUNT_1B', 'Creating file EPB_Split ');
define('EASY_LABEL_FILE_COUNT_2', '.txt ...  ');
define('EASY_LABEL_FILE_CLOSE_1', 'Added ');
define('EASY_LABEL_FILE_CLOSE_2', ' records and closing file...');
define('EASY_LABEL_FILE_INSERT_LOCAL', 'Inserting local file: ');
define('TEXT_INFO_TIMER', 'Script timer:');
define('TEXT_INFO_SECOND', 'seconds.');

//errormessages
define('EASY_ERROR_1', 'Error 01 - Strange but there is no default language to work... That may not happen, just in case... ');
define('EASY_ERROR_2', '... Error 02 - Too many characters in the model number.<br>
      25 is the maximum on a standard cre install.<br>
      Your maximum product_model length is set to ');
define('EASY_ERROR_2A', ' <br>You can either shorten your model numbers or increase the size of the field in the database.</font>');
define('EASY_ERROR_2B',  "<font color='red'>");
define('EASY_ERROR_3', '<p class=smallText> Error 03 - No products_id field in record or it is the last line in the file. This line was not imported. <br><br>');
define('EASY_ERROR_4', '<font color=red>ERROR 04 - v_customer_group_id and v_customer_price must occur in pairs</font>');
define('EASY_ERROR_5', '</b><font color=red>Error 05 - You are trying to use a file created with EP Advanced, please try with Easy Populate Basic: </font>');
define('EASY_ERROR_5a', '<font color=red><b><u>  Click here to return to Easy Populate Basic </u></b></font>');
define('EASY_ERROR_6', '</b><font color=red>Error 06 - You are trying to use a file created with EP Basic, please try with Easy Populate Advanced: </font>');
define('EASY_ERROR_6a', '<font color=red><b><u>  Click here to return to Easy Populate Advanced </u></b></font>');
define('EASY_ERROR_7', '<p class=smallText> Error 07 - No Module field in record or it is the last line in the file. This line was not imported. <br><br>');

// Eversun mod for Easy Populate Products Options, Values and Attributes
define('EASY_VERSION_C', 'Easy Populate Options');
define('EASY_LABEL_OPTIONS_ID', 'Options ID');
define('EASY_LABEL_OPTIONS_NAME', 'Options Name');
define('EASY_VERSION_D', 'Easy Populate Values');
define('EASY_LABEL_VALUES_ID', 'Values ID');
define('EASY_LABEL_VALUES_NAME', 'Values Name');
define('EASY_VERSION_E', 'Easy Populate Attributes');
define('TEXT_SELECT_ONE_OPTIONS', 'Select a EP Option File for Import');
define('TEXT_SELECT_ONE_VALUES', 'Select a EP Values File for Import');
define('TEXT_SELECT_ONE_ATTRIBUTES', 'Select a EP Attributes File for Import');
define('EASY_ERROR_8', '<b><font color=red>Error - You didn\'t select any file</font></b>');
define('EASY_INFO_SUCCESS', '<b><font color=red>Import Success</font></b>');
define('EASY_INFO_FILE_NOT_FOUND', '<b><font color=red>File not Found</font></b>');
define('EASY_INFO_CHECK_ERROR1', '<b><font color=red>Field %s not in the table %s.</font></b>');
// Eversun mod end for Easy Populate Products Options, Values and Attributes



/*******************************/
define('ERROR_CANT_PROCESSED_ON_LINE', 'Can\'t processed on line ');
define('ERROR_DOESNT_HAVE_THIS_OPTIONS_ID', 'doesn\'t have this options_id: ');
define('ERROR_DOESNT_HAVE_THIS_PRODUCTS_ID_1', 'doesn\'t have this products_id: ');
define('ERROR_DOESNT_HAVE_THIS_PRODUCTS_ID_2', " or model: ");
define('ERROR_DOESNT_HAVE_THIS_VALUES_ID_1', 'doesn\'t have values_id: ');
define('ERROR_DOESNT_HAVE_THIS_VALUES_ID_2', " for  options_id: ");

define('MSG_READ_RECORDS', "Read records: ");
define('MSG_RECORDS_WILL_BE_UPDATED', " records will be updated");
define('MSG_RECORDS_WILL_BE_INSERTED', " records will be inserted");
define('MSG_RECORDS_WILL_BE_DELETED', " records will be deleted");
define('MSG_ERROR_RECORDS_WONT_BE_PROCESSED', ' records won\'t be processed, because of below reasons:');

define('ERROR_OPTIONS_TYPE_CHANGE_ERROR_1', ' options type change error, you can\'t change option type from');
define('ERROR_OPTIONS_TYPE_CHANGE_ERROR_2', 'to');
define('TEXT_VERIFY', 'Verify');
define('EASY_LABEL_WOSPPC', 'full w/o sppc');

/*******************************/

define('PULL_DOWN_MANUFACTURERS', 'Pull Down Manufacturers');
?>