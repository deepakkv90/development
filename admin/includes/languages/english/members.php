<?php
/*
  $Id: members.php,v 1.2 2003/09/24 13:57:08 anon Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Member approval');

define('TABLE_HEADING_LASTNAME', 'Lastname');
define('TABLE_HEADING_FIRSTNAME', 'Firstname');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Account Created');
define('TABLE_HEADING_ACTION', 'Action');

define('HEADING_TITLE_SEARCH', 'Search');

define('EMAIL_CONTACT', 'For help with any of our online services, please email us at: ' . STORE_OWNER_EMAIL_ADDRESS);

define('EMAIL_TEXT_CONFIRM', 'Your application to become a wholesale customer of (your store) has been approved. You can now access pricing on the (your store) site.<br>You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to drop ship direct to your customer.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n");

define('EMAIL_WARNING', '<b>Note:</b> This email address was used to request access to our wholesale website. If you did not signup to be a customer, please send an email to ' . STORE_OWNER_EMAIL_ADDRESS . "\n\n");

define('EMAIL_TEXT_SUBJECT', 'Account Approved');

define('EMAIL_SEPARATOR', '----------------------------------------------');
?>