<?php
/*
  $Id: artworks.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define('TABLE_HEADING_ARTWORK', 'SNo');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');
define('TABLE_HEADING_ARTWORK_NAME', 'Name');
define('TABLE_HEADING_FILES', 'Files');
define('TABLE_HEADING_DESC', 'Description');
define('HEADING_TITLE', 'Artworks and design proposal for %s');
define('TABLE_HEADING_PRODUCT_NAME', 'Product name');
define('TABLE_HEADING_BRIEF', 'Briefs');

//define('ENTRY_CUSTOMER', 'Customer Name:'); Sep 29 2011
define('ENTRY_CUSTOMER', 'Name:');
//define('ENTRY_CUSTOMER_NUMBER', 'Customer number: '); //Sep 29 2011
define('ENTRY_CUSTOMER_NUMBER', 'Account number: ');
define('ENTRY_ORDERID', 'Order');
define('ENTRY_ORDER_DATE', 'Date Purchased:');
define('ENTRY_PRODUCTS_NAME', 'Products name:');

//email to client

define('ARTWORK_CLIENT_EMAIL_SUBJECT','Samples design from Name Badges International');
define('ARTWORK_CLIENT_EMAIL_GREET', 'Dear %s,' . "\n\n");
define('ARTWORK_CLIENT_EMAIL_CONTENT', 'Thank you for waiting while we were working on your project. We are pleased to inform you that the design concepts of your project are ready and can be viewed online.' . "\n\n" . 'Before you view your design concepts, please keep in mind that design is a subjective matter. Design samples are created according to the customer brief and at times can be different to what a customer may have expected.' . "\n\n" . 'Name Badges International strongly believes in customer feedback. Your feedback on these samples is taken into account when revised designs are produced.' . "\n\n" . 'We are releasing your samples for your feedback. If you like them and want the final files released then great. However if you would like to have further work done on one of these designs then please let us know. We are committed to your 100% satisfaction.' . "\n\n" . 'Please ensure that this design is suitable for your requirement. Once the final concept is approved charges will be applied for further changes. The quicker you are able to approve the design the faster we are able to complete the order. The default colour model is  PMS (Pantone) colours.' . "\n\n" . 'Note: Any format change after the start of your project will have an additional charge.' . "\n\n" . 'Final production colours and finish may vary.' . "\n\n" . '<b><font style="color:#FF6633">Here is how you can view your samples:</font></b>' . "\n" . '1. Login to your account on '.HTTP_SERVER.'/login.php Your order(s) will be listed on the first page.' . "\n" . '2. Click on the Icon "Artwork" and wait for next screen to load.' . "\n" . '3. Your samples will be listed. If there is more than one sample, you should see tabs with Option 1 , Option 2 ect... Simply click on one of these tab to select it.' . "\n\n" . '<b><font style="color:#FF6633">Here is how to validate the design proposal:</font></b>' . "\n" . 'When your chosen sample is selected click on blue button that says "Click here to APPROVE the Design".' . "\n\n" . '<b><font style="color:#FF6633">Here is how to ask for further revisions:</font></b>' . "\n" . 'a. When your chosen sample is selected (as described above) click on grey button that says "Click here to request revisions "' . "\n" . 'b. In the text window below, please provide your feedback and further instructions as separate items. For example:' . "\n\n" . '1) Use Dark Grey colour in the Font' . "\n" . '2) Add a tag line below the icon shown in the logo' . "\n" . '3) Make the dot on the Letter i bigger' . "\n" . '4) etc. etc. etc.' . "\n\n" . 'c. When you finished providing feedback and instructions, click on orange button labeled "Save Feedback". This will send your feedback to us in a timely manner.' . "\n\n" . 'You can also send us a file with your feedback – such as a better version of your logo or name list etc...' . "\n\n" . '<b><font style="color:#FF6633">What happens next?</font></b>' . "\n" . 'Within two business days, we will send your revised design concepts. We will notify you via email. Please note that at times, emails can get caught in spam filters, therefore if you did not receive an email from us within two business days, please login to your account and check the order status that way.' . "\n\n");

define('ARTWORK_CLIENT_EMAIL_FOOTER','Yours Sincerely, %s' . "\n\n" . 'Name Badges International Customer Support Team' . "\n" . '
------------------------------------------------------------------------------------------------------------' . "\n" . '
<b>Name Badges International Pty Ltd - Head Office (Brisbane)</b>' . "\n" . '
Suite F Level 1 Octagon, 110 George Street<br> Parramatta, NSW 2150 Australia' . "\n" . '

<b>sales@namebadgesinternational.com.au | '.HTTP_SERVER.'</b>' . "\n" . '
Name Badges International Pty Ltd | ABN 60 149 490 406' . "\n");


define('ARTWORK_SALES_CONSULTANT_EMAIL_SUBJECT','Samples design for Customer #%s');
define('ARTWORK_SALES_CONSULTANT_EMAIL_GREET','Hi %s,' . "\n\n" . '%s has uploaded a design proposal online:'. "\n\n");

define('ARTWORK_SALES_CONSULTANT_FEEDBACK_SUBJECT','New %s response for Design #%s');
define('ARTWORK_SALES_CONSULTANT_FEEDBACK_GREET','Hi %s,' . "\n\n" . '%s has posted a new feedback for the Order design %s'. "\n\n");

define('ARTWORK_ADMIN_LINK','Please click here to see it online: %s'. "\n\n");
define('ARTWORK_SALES_CONSULTANT_EMAIL_TEXT','PLEASE REMEMBER TO NOTIFY THE CLIENT.'. "\n\n");
define('ARTWORK_SALES_CONSULTANT_EMAIL_FOOTER','Thank you' . "\n\n" . 'Name Badges International.');

define('ARTWORK_CLIENT_FEEDBACK_SUBJECT','New %s response for Design #%s');
define('ARTWORK_CLIENT_FEEDBACK_GREET','Hi %s,' . "\n\n" . 'We have posted a new feedback for the Order design %s'. "\n\n");
define('ARTWORK_CLIENT_LINK','Please click here to see it online: %s'. "\n\n");
define('ARTWORK_CLIENT_FEEDBACK_TEXT','');


//#ff6633
?>