<?php
/*
  $Id: infobox_configuration.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Chain Reaction Works, Inc.

  Copyright &copy; 2003-2007

*/


define('HEADING_TITLE', 'Infobox Display, Create and Update');
define('TABLE_HEADING_INFOBOX_FILE_NAME', 'File name');
define('TABLE_HEADING_INFOBOX_TITLE', 'Title');
define('TABLE_HEADING_ACTIVE', 'Activate Box?');
define('TABLE_HEADING_KEY', 'Box Heading Define');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_COLUMN', 'Set Column');
define('TABLE_HEADING_SORT_ORDER', 'Position');
define('TABLE_HEADING_TEMPLATE', 'Box Template');
define('TABLE_HEADING_FONT_COLOR', 'Font Color');
define('TABLE_HEADING_BOX_DIRECTORY', 'Location of boxes for this template: ');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_HEADING_NEW_INFOBOX', 'Create a new Infobox');
define('TEXT_INFO_INSERT_INTRO', 'An example for the<b> what\'s_new.php</b> Infobox is selected');
define('TEXT_INFO_DELETE_INTRO', '<P STYLE="color: red; font-weight: bold;">Confirm OK to delete the Infobox');
define('TEXT_INFO_HEADING_DELETE_INFOBOX', 'Delete Infobox?');
define('TEXT_INFO_HEADING_UPDATE_INFOBOX', 'Update the Infobox');
define('IMAGE_BUTTON_UPDATE_BOX_POSITIONS', 'Update Infobox Position');

define('IMAGE_INFOBOX_STATUS_UP', 'UP');
define('IMAGE_ICON_STATUS_UP_LIGHT', 'Move Up');
define('IMAGE_INFOBOX_STATUS_down', 'Down');
define('IMAGE_ICON_STATUS_DOWN_LIGHT', 'Move Down');

define('IMAGE_INFOBOX_STATUS_GREEN', 'Left');
define('IMAGE_INFOBOX_STATUS_GREEN_LIGHT', 'Set Left');
define('IMAGE_INFOBOX_STATUS_RED', 'Right');
define('IMAGE_INFOBOX_STATUS_RED_LIGHT', 'Set Right');


define('BOX_HEADING_BOXES', 'Boxes Admin');

define('TEXT_HEADING_SET_ACTIVE', 'Set this box Active? ');
define('TEXT_HEADING_DEFINE_KEY', '  Define key ');
define('TEXT_HEADING_WHAT_POS', 'Column Position? ');
define('TEXT_HEADING_WHICH_TEMPLATE', 'Which box Template? ');
define('TEXT_HEADING_HEADING', 'Infobox heading ');
define('TEXT_HEADING_WHICH_COL', 'Which column? ');
define('TEXT_HEADING_FILENAME', 'Filename ');
define('TEXT_HEADING_FONT_COLOR', 'Header Font Color ');
define('TEXT_HEADING_FONT_CHANGE_COLOR', 'Change Font color ');

define('TEXT_NOTE_REQUIRED', '* Denotes required field');

define('JS_BOX_HEADING', '* The \'Define Key\' must be completed. Example: BOX_HEADING_WHATS_NEW');
define('JS_INFO_BOX_HEADING', '* The \'Box Heading\' must be completed.');
define('JS_BOX_LOCATION', '* You must select a column to display your Infobox');
define('JS_INFO_BOX_FILENAME', '* You must select a Filename for your Infobox');
define('JS_BOX_COLOR', '* Please select a color for the font color.');


define('TEXT_INFO_MESSAGE_COUNT_1', '<br>There are currently <br>');
define('TEXT_INFO_MESSAGE_COUNT_2', ' active boxes in the left column and <br>');
define('TEXT_INFO_MESSAGE_COUNT_3', ' active boxes in the right column');
//error messages
define('infobox_error1', "This template does not have any infoboxes to install. Please put the infoboxes that you want to install in this template\'s boxes directory");
define('infobox_error2', 'WARNING: No boxes selected in your LEFT column');
define('infobox_error3', 'WARNING: No boxes selected in your RIGHT column');
define('INFOBOX_ACTIVE_BOXES', ' active boxes in the right column');
if (!defined('IMAGE_NEW_INFOBOX')) {
  define('IMAGE_NEW_INFOBOX', 'New Infobox');
}
?>
