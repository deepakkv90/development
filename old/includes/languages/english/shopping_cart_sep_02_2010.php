<?php
/*
  $Id: shopping_cart.php,v 1.3 2004/03/15 12:13:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Cart Contents');
define('HEADING_TITLE', 'What\'s In My Cart?');
define('TABLE_HEADING_REMOVE', 'Remove');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Product(s)');
define('TABLE_HEADING_TOTAL', 'Total');
define('TEXT_CART_EMPTY', 'Your Shopping Cart is empty!');
define('SUB_TITLE_SUB_TOTAL', 'Sub-Total:');
define('SUB_TITLE_TOTAL', 'Total:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' are not available in the desired quantity and can not be back ordered.<br>You can not proceed with checkout unless you remove the items from the cart<br> or alter the quantity of products marked with (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ').');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' are not available in the desired quantity.<br>You can buy them anyway and the quantity not in stock will be back orderd.  The quantity we have in stock for immediate delivery can be reviewed in the checkout process.');

define('TABLE_HEADING_UNIT_PRICE', 'Price Per.');

define('TEXT_HIDE_ADD_TO_CART_ERROR', 'This product cannot be purchased without authorization. <br> Please contact the store owner if you wish to purchase this product. <br>
Thank you.');
?>