<?php
/*
  $Id: wishlist.php,v 1 2004/09/12

  OS Commerce - Community Made Shopping!
  http://www.oscommerce.com

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'My Wishlist');
define('HEADING_TITLE', 'My Wishlist contains:');
define('BOX_TEXT_PRICE', 'Price:');
define('BOX_TEXT_SEND', 'Email your Wishlist to a friend.');
//define('BOX_TEXT_MOVE_TO_CART', 'Move to Cart');
//define('BOX_TEXT_DELETE', 'Delete');
define('BOX_TEXT_VIEW', 'Show');
define('BOX_TEXT_HELP', 'Help');
define('BOX_TEXT_SELECTED_PRODUCTS', 'Selected Products');
define('BOX_TEXT_SELECT_PRODUCT', 'Select Product ');

define('BOX_TEXT_NO_ITEMS', 'No products are in your Wishlist. <br><br><b><a href="wishlist_help.php"><u>Click here</u></a> for help on using your Wishlist</b>');
if (!defined('TEXT_WISHLIST_COUNT')) {
define('TEXT_WISHLIST_COUNT', 'Currently %s items are on your Wishlist.');
}

if (!defined('TEXT_DISPLAY_NUMBER_OF_WISHLIST')) {
define('TEXT_DISPLAY_NUMBER_OF_WISHLIST', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> items on your wishlist)');
}
?>