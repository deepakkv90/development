<?php
/*
  $Id: wishlist_email.php,v 1 2003/11/20

  OS Commerce - Community Made Shopping!
  http://www.oscommerce.com

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/


define('NAVBAR_TITLE', 'Send your Wishlist');
define('HEADING_TITLE', 'Send your Wishlist to a friend.');
define('FORM_TITLE_CUSTOMER_DETAILS', 'Your Details');
define('FORM_FIELD_CUSTOMER_NAME', 'Your Name:');
define('FORM_FIELD_CUSTOMER_EMAIL', 'Your Email Address:');
define('FORM_TITLE_FRIEND_DETAILS', 'Your Friend\'s Details');
define('FORM_FIELD_FRIEND_NAME', 'Your Friend\'s Name:');
define('FORM_FIELD_FRIEND_EMAIL', 'Your Friend\'s Email Address:');
define('FORM_TITLE_FRIEND_MESSAGE', 'Add a Personal Message Here');
define('FORM_FIELD_TEXT_AREA', 'My Wishlist contains:'. "\n\n");

define('FORM_FIELD_PRODUCTS', 'Products on my wish list'. "\n\n");


define('TEXT_EMAIL_SUCCESSFUL_SENT', 'Your email has been successfully sent to <b>%s</b> at the email adress <b>%s</b>.');
define('TEXT_EMAIL_SUBJECT', 'Your friend %s wants to share their Wishlist at %s.');
define('TEXT_EMAIL_INTRO', 'Hi %s,' . "\n\n" . '%s has created a Wishlist at %s which they would like to share with you.');
define('TEXT_EMAIL_SIGNATURE', 'Regards,' . "\n\n");
define('TEXT_EMAIL_LINK', 'To view the product click on the link below :' . "\n");
define('TEXT_EMAIL_LINK_TEXT', 'To view the product copy and paste the link into your web browser:' . "\n");

define('ERROR_TO_NAME', 'Error: Your friends name must not be empty.');
define('ERROR_TO_ADDRESS', 'Error: Your friends e-mail address must be a valid e-mail address.');
define('ERROR_FROM_NAME', 'Error: Your name must not be empty.');
define('ERROR_FROM_ADDRESS', 'Error: Your e-mail address must be a valid e-mail address.');

?>