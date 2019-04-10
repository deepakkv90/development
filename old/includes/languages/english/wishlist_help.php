<?php
/*
  $Id: wishlist_help.php,v 1 2002/11/09

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'My Wishlist F.A.Q.');
define('HEADING_TITLE', 'My Wishlist F.A.Q.');

if (!defined('TEXT_CLOSE_WINDOW')) {
  define('TEXT_CLOSE_WINDOW', '<u>Close Window</u> [x]');
}


define('TEXT_INFORMATION', '<b>What is ' . BOX_HEADING_CUSTOMER_WISHLIST . '?</b><br>' .
BOX_HEADING_CUSTOMER_WISHLIST . ' is a way to save items of interest to a holding area until ready to purchase.  <span class="errorText">If you are not a current customer, please at least create a wishlist account to save your shopping cart contents for when you return. Credit card information is NOT required to create a wishlist account.</span><br>
<br>
<b>How do I add items to ' . BOX_HEADING_CUSTOMER_WISHLIST . '? </b><br>
To add an item to ' . BOX_HEADING_CUSTOMER_WISHLIST . ', just click the "Add to Wishlist" button for any item that interests you. The "Add to Wishlist" button appears next to the "Add to Cart" button in the product info pages.
<br>
<br>
<b>Can I add "out-of-stock" items or "coming-soon items"?</b><br>
Yes. You can add any item you choose to ' . BOX_HEADING_CUSTOMER_WISHLIST .
'<br>
<br>
<b>How do I view ' . BOX_HEADING_CUSTOMER_WISHLIST . '?</b><br>
On the right column is a "' . BOX_HEADING_CUSTOMER_WISHLIST . '" box.  This box shows the current status of ' . BOX_HEADING_CUSTOMER_WISHLIST . '.  If there are 4 or less items in ' . BOX_HEADING_CUSTOMER_WISHLIST . ', they will be listed here.  If there are more than 4 items, there will be a simple counter with the total number of items in ' . BOX_HEADING_CUSTOMER_WISHLIST . '.  You can change the total number of items in the box via the Administration area.
<br>
<br>
' . BOX_HEADING_CUSTOMER_WISHLIST . ' may be viewed at any time by clicking either the link found in the "' . BOX_HEADING_CUSTOMER_WISHLIST . '" box (<i><u>View ' . BOX_HEADING_CUSTOMER_WISHLIST . '</u> [+]</i>).  or by clicking the right arrow on the "' . BOX_HEADING_CUSTOMER_WISHLIST . '" box.
<br>
<br>
<b>How do I move ' . BOX_HEADING_CUSTOMER_WISHLIST . ' items to my Shopping Cart?</b><br>
To move ' . BOX_HEADING_CUSTOMER_WISHLIST . ' items to the Shopping Cart, either click "Move to Cart" under the product name in the "' . BOX_HEADING_CUSTOMER_WISHLIST . '" box (if the ' . BOX_HEADING_CUSTOMER_WISHLIST . ' items are shown in the box) or check the "Move to Cart" checkbox next to the item(s) on the ' . BOX_HEADING_CUSTOMER_WISHLIST . ' main page and click the "Update" button.
<br>
<br>
<b>How do I remove items from ' . BOX_HEADING_CUSTOMER_WISHLIST . '?</b><br>
To remove items from ' . BOX_HEADING_CUSTOMER_WISHLIST . ', either click "Delete" under the item\'s name in the "' . BOX_HEADING_CUSTOMER_WISHLIST . '" box or check the "Delete" checkbox next to the item(s) on the ' . BOX_HEADING_CUSTOMER_WISHLIST . ' main page and click the "Update" button.
<br>
<br>
<b>Can I "remove" and "move to cart" at the same time?</b><br>
Yes.  On the ' . BOX_HEADING_CUSTOMER_WISHLIST . ' mainpage, you can check the boxes next to the item(s) you want to "Delete" and "Move to cart" and click the "Update" button to perform all operations simultaneously.
<br>
<br>
<b> Can I make ' . BOX_HEADING_CUSTOMER_WISHLIST . ' available to others? </b><br>
Sorry. Currently ' . BOX_HEADING_CUSTOMER_WISHLIST . ' is only "saved" when you create / log in to your account. Therefore, you are the only one that can see it.  However, you can email your wishlist to a friend by visiting the ' . BOX_HEADING_CUSTOMER_WISHLIST . ' main page, entering their email address into the \'send your wishlist to a friend\' box and clicking the email envelope.
');
?>