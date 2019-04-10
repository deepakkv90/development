<?php
/*
  $Id: recover_abandonded_carts.php,v 1.0.0 2008/05/22 00:36:41 datazen Exp $    

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('MESSAGE_STACK_CUSTOMER_ID', 'Cart for Customer-ID ');
define('MESSAGE_STACK_DELETE_SUCCESS', ' deleted successfully');
define('HEADING_TITLE', 'Recover Abandoned Carts');
define('HEADING_EMAIL_SENT', 'E-mail Sent Report');
define('EMAIL_TEXT_LOGIN', 'Login to your account here:');
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Inquiry from '.  STORE_NAME );
define('EMAIL_TEXT_SALUTATION', 'Dear ' );
define('EMAIL_TEXT_NEWCUST_INTRO', "\n\n" . 'Thank you for stopping by ' . STORE_NAME .
                                   ' and considering us for your purchase. ');
define('EMAIL_TEXT_CURCUST_INTRO', "\n\n" . 'We would like to thank you for having shopped at ' .
                                   STORE_NAME . ' in the past. ');
define('EMAIL_TEXT_BODY_HEADER',
 'We noticed that during a visit to our store you placed ' .
 'the following item(s) in your shopping cart, but did not complete ' .
 'the transaction.' . "\n\n" .
 'Shopping Cart Contents:' . "\n\n"
 );
 
define('EMAIL_TEXT_BODY_FOOTER',
 'We are always interested in knowing what happened ' .
 'and if there was a reason that you decided not to purchase at ' .
 'this time. If you could be so kind as to let us ' .
 'know if you had any issues or concerns, we would appreciate it.  ' .
 'We are asking for feedback from you and others as to how we can ' .
 'help make your experience at '. STORE_NAME . ' better.'."\n\n".
 'PLEASE NOTE:'."\n".'If you believe you completed your purchase and are ' .
 'wondering why it was not delivered, this email is an indication that ' .
 'your order was NOT completed, and that you have NOT been charged! ' .
 'Please return to the store in order to complete your order.'."\n\n".
 'Our apologies if you already completed your purchase, ' .
 'we try not to send these messages in those cases, but sometimes it is ' .
 'hard for us to tell depending on individual circumstances.'."\n\n".
 'Again, thank you for your time and consideration in helping us ' .
 'improve the ' . STORE_NAME .  " website.\n\nSincerely,\n\n"
 );

define('DAYS_FIELD_PREFIX', 'Show for last ');
define('DAYS_FIELD_POSTFIX', ' days ');
define('DAYS_FIELD_BUTTON', 'Go');
define('TABLE_HEADING_DATE', 'Date');
define('TABLE_HEADING_CONTACT', 'Contacted');
define('TABLE_HEADING_CUSTOMER', 'Customer Name');
define('TABLE_HEADING_EMAIL', 'E-Mail');
define('TABLE_HEADING_PHONE', 'Phone');
define('TABLE_HEADING_MODEL', 'Item');
define('TABLE_HEADING_DESCRIPTION', 'Description');
define('TABLE_HEADING_QUANTY', 'Qty');
define('TABLE_HEADING_PRICE', 'Price');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_GRAND_TOTAL', 'Grand Total: ');
define('TABLE_CART_TOTAL', 'Cart Total: ');
define('TEXT_CURRENT_CUSTOMER', 'Customer');
define('TEXT_SEND_EMAIL', 'Send E-mail');
define('TEXT_RETURN', '[Click Here To Return]');
define('TEXT_NOT_CONTACTED', 'Uncontacted');
define('PSMSG', 'Additional PS Message: ');
define('TEXT_RAC_EDIT', 'Edit Settings for Recover Carts');
define('TEXT_RAC_RUN_RECOVER_CARTS_REPORT', 'Run Recover Carts Report');
define('TEXT_CUR_CUSTOMER', 'Current Customer'); 
define('TEXT_CONTACTED', 'Contacted'); 
define('TEXT_UNCONTACTED', 'Uncontacted'); 
define('TEXT_MATCHED', 'Matched Order');  
?>