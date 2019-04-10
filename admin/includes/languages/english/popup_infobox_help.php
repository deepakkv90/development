<?php
/*
  $Id: popup_infobox_help.php,v 1.3 2004/03/15 12:13:02 ccwjr Exp $


  Copyright (c) 2004 CRE Works

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Infobox Help');
define('TEXT_INFO_HEADING_NEW_INFOBOX', 'Infobox Help');
define('TEXT_INFOBOX_HELP_FILENAME', 'For adding new info boxes: Use the drop down box to select the file.<br> For editing install side boxes: This must represent the name of the box file you have put in your <u>%s</u> folder.<br><br> It must be lowercase, but can have spaces instead of using the underscore (_)<br><br>For example:<br>Your new Infobox is named <b>new_box.php</b>, you would type in here "<b> new_box.php</b>".<br>');
define('TEXT_INFOBOX_HELP_HEADING', 'This is what will be displayed above the Infobox in your catalog. Make sure you have an entry for each language, do not leave any of the Header input boxes blank.<br><div align="center"><img border="0" src="images/help1.gif"><br></div>');
define('TEXT_INFOBOX_HELP_DEFINE', 'An example of this would be: <b>BOX_HEADING_WHATS_NEW</b>.<br> This is then used with the main logic of your store as this: <b> define(\'BOX_HEADING_WHATS_NEW\', \'What\'s New?\');</b><br><br> If you open the file <u>catalog/includes/languages/english.php</u> you can see plenty of examples, the ones that contain BOX_HEADING are no longer needed, as they are now stored within the database and defined in the files <b>column_left.php</b> and <b>column_right.php</b>.<br>But there is no need to delete them!');
define('TEXT_INFOBOX_HELP_COLUMN', 'Select either <b>left</b> or <b>right</b><br> for the Infobox to be displayed in the left or right column.<br><br>By default it set to <b>left</b>');
define('TEXT_INFOBOX_HELP_POSITION', 'Enter any number you like in here. The higher the number, the lower down the selected column the Infobox will appear.<br><br> If you enter the same number for more than one Infobox, they are displayed alphabetically first.<br><br>If you do not enter any number, then the box will also be displayed alphabetical first.');
define('TEXT_INFOBOX_HELP_ACTIVE', 'Either select <b>yes</b> or <b>no</b> to display the Infobox (yes) or not (no).<br><br>By default it is set to <b>yes</b>');
define('TEXT_INFOBOX_HELP_TEMPLATE', 'This must represent the class name for the template box class used for this side box. The default class is called infobox and is entered here. If you have a special class in the boxes.tpl.php you place it here<br><br>For example:<br> If you have a special box to change the header background color and it is named blue_box. You would enter that into the input box.');
define('TEXT_INFOBOX_HELP_COLOR', 'You can use the pop_up color chart to select the color  for the font used in info box headers, <br> Just click on the color and the color code will apear in the text box..');
define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');

?>
