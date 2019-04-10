<?php
/*
  $Id: create_account.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


// MAIL VALIDATION START //
define('VALIDATE_YOUR_MAILADRESS', 'Click here to Validate/Activate Your account');
define('SECOND_LINK', '<B>Or you can manually copy and paste in the following link into your browsers window:</B><br> ');
define('OR_VALIDATION_CODE', '<B>Your Validation Code is:</B> ');
define('MAIL_VALIDATION', '<FONT COLOR="#FF0000"><B>You have to validate/activate your account before you can login.</B></FONT><P><B>Please click on the link below to finish the account creation process:</B> ');
// MAIL VALIDATION END //
define('NAVBAR_TITLE', 'Create an Account');
define('HEADING_TITLE', 'My Account Information');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><B>NOTE:</B></font></small> If you already have an account with us, please login at the <a href="%s"><u>login page</u></a>.');
define('HEADING_TITLE_CHECKOUT','Checkout Personal Info');// Added by sheetal for PWA form Title

//For create account signup email
define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME); // New user Email Subject
define('EMAIL_GREET_MR', 'Dear Mr. %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Dear Ms. %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
define('EMAIL_WELCOME', 'We welcome you to ' . STORE_NAME. "\n\n");
define('EMAIL_TEXT', '<B>You can design and order your name badge online 24/7 and any other products in our website.</B>'."\n\n".'Thank you for creating an account on '.HTTP_SERVER.' , you can now enjoy our services online:' . "\n\n" . '<li><B>Permanent Cart</B> -  Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><B>Address Book</B> - We can deliver your products to another address other than yours!' . "\n" . '<li><B>Order History</B> - View your history of purchases that you have made with us.' . "\n" . '<li><B>Products Reviews</B> - Share your opinions on products with our other customers.' . "\n". '<li><B>Invoice</B> - Print your invoices online anytime you need it.' . "\n" . '<li><B>Files</B> - You can upload any document in your account regarding your order: logo, list name etc...' . "\n" . '<li><B>Re-Order</B> - Its easy to re order a previous order, simply login to your account, edit the concerned order and click on the button "Re-Order".' . "\n\n");
define("EMAIL_TEXT_YOUR_DETAILS", "<B>Your details are:</B>");
define("EMAIL_TEXT_USERNAME", "<b>Username: </b>");
define("EMAIL_TEXT_PASSWORD", "<b>Password: </b>");
define("EMAIL_TEXT_PASSWORD_TEXT", "If you don't know it, you can reset your password on buttonbadgesinternational.com.au");
define("EMAIL_TEXT_COMPANY", "<b>Company name: </b>");
define("EMAIL_TEXT_FIRSTNAME", "<b>First name: </b>");
define("EMAIL_TEXT_LASTNAME", "<b>Last name: </b>");
define("EMAIL_TEXT_PHONE", "<b>Phone: </b>");
define("EMAIL_TEXT_ADDRESS", "<b>Address: </b>");
define('EMAIL_CONFIRMATION', 'Thank you for submitting your account information to our ' . STORE_NAME . "\n\n" . 'To finish your account setup please verify your e-mail address by clicking the link below: ' . "\n\n");
define('EMAIL_CONTACT', 'For help with any of our online services, please email the store-owner: ');
define('EMAIL_CONTACT_TEXT', '<a href=\"mailto:' . STORE_OWNER_EMAIL_ADDRESS . '\">' . STORE_OWNER_EMAIL_ADDRESS . '</a>' . "\n\n");
define('EMAIL_WARNING', '<B>Note:</B> This Email address was given to us by one of our customers. If you did not signup to be a member, please send an email to ');
define('EMAIL_WARNING_TEXT', '<a href=\"mailto:' . STORE_OWNER_EMAIL_ADDRESS . '\">' . STORE_OWNER_EMAIL_ADDRESS . '</a>' . "\n");
/* ICW Credit class gift voucher begin */
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'As part of our welcome to new customers, we have sent you a Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for your Gift Voucher is %s. You can enter the redeem code when checking out while making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations! to make your first visit to our online shop a more rewarding experience, we are sending you an e-Discount Coupon.' . "\n" .
                                      ' Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
/* ICW Credit class gift voucher end */
define('MAIL_VALIDATION_B2B','Thank you for creating an account in our store. We validate account access. We will review your information and contact you if necessary. If we approve your account, you will receive a notification via email."');

?>