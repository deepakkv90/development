<?php
/*
  $Id: account_myfiles.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'My Account');
define('NAVBAR_TITLE_2', '');

define('HEADING_TITLE', 'Artwork and design');

define('TEXT_ORDER_NUMBER', 'Order #');
define('TEXT_PRODUCTS_ID', 'Products ID');
define('TEXT_LOGOS', 'Logos');
define('TEXT_FILES', 'Files');
define('TEXT_COMMENTS', 'Comments');
define('TEXT_DATE_PURCHASED', 'Date');

define('TEXT_ORDER_BILLED_TO', 'Billed To:');
define('TEXT_ORDER_PRODUCTS', 'Products:');
define('TEXT_ORDER_COST', 'Order Cost:');
define('TEXT_VIEW_ORDER', 'View Order');

define('TEXT_NO_PURCHASES', 'You have not yet made any purchases.');

//email to sales consultant when feedback posted
define('ARTWORK_DESIGNER_APPROVAL_EMAIL_SUBJECT','Approval for Design #%s');

define('ARTWORK_SALES_CONSULTANT_FEEDBACK_EMAIL_SUBJECT','New %s request for Design #%s');
define('ARTWORK_SALES_CONSULTANT_FEEDBACK_EMAIL_GREET','Hi %s,' . "\n\n");
define('ARTWORK_SALES_CONSULTANT_FEEDBACK_EMAIL_INTRO','%s has posted a new feedback for the Order design #%s'. "\n\n");
define('ARTWORK_ADMIN_LINK','Please click here to see it online: %s'. "\n\n");
define('ARTWORK_SALES_CONSULTANT_EMAIL_TEXT','PLEASE REMEMBER TO NOTIFY THE CLIENT ONCE THE REQUEST IS PROCESSED.'. "\n\n");
define('ARTWORK_SALES_CONSULTANT_EMAIL_FOOTER','Thank you' . "\n<br>" . 'Button Badges International.');

//email to designer when feedback posted

define('ARTWORK_DESIGNER_FEEDBACK_EMAIL_SUBJECT','New %s request for Design #%s');
define('ARTWORK_DESIGNER_FEEDBACK_EMAIL_GREET','Hi %s,' . "\n\n");
define('ARTWORK_DESIGNER_FEEDBACK_EMAIL_INTRO','%s has posted a new feedback for the Order design #%s'. "\n\n");
define('ARTWORK_DESIGNER_EMAIL_TEXT','PLEASE REMEMBER TO NOTIFY %s ONCE THE REQUEST IS PROCESSED.'. "\n\n");
define('ARTWORK_DESIGNER_EMAIL_FOOTER','Thank you' . "\n<br>" . 'Button Badges International.');

//email for customer confirmation
define("ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_SUB", "Button Badges International revision request confirmation");
define('ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_GREET','Dear %s,' . "\n\n");
define('ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_INTRO','We have received your revision request, thank you.'. "\n\n");
define('ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_CONTENT', '<b><font style="color:#FF6633">What happens next?</font></b>'. "<br>".'We will notify you shortly via email.'."<br>".'Please note that at times, emails can get caught in spam filters, therefore if you did not receive an email from us within two business days, please login to your account and check the order status that way.'."\n<br><br>");

define('ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_FOOTER','Yours Sincerely, %s' . "<br>" . 'Button Badges International Customer Support Team' . "<br>" . '
------------------------------------------------------------------------------------------------------------' . "<br>" . '
<b>Button Badges International </b>' . "<br>" . '
Suite F Level 1 Octagon, 110 George Street<br> Parramatta, NSW 2150 Australia' . "<br><br>" . '

<b>sales@buttonbadgesinternational.com.au | http://buttonbadgesinternational.com.au/</b>' . "<br>" . '
Name Badges International Pty Ltd | ABN 60 149 490 406' . "\n<br>");

define("ARTWORK_RIGHT_CLICK_ALERT", "This document is the property of  Name Badges International & Co Pty Ltd | © Copyright Name Badges International & Co Pty Ltd. Thank you");
?>
