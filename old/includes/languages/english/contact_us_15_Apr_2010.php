<?php
/*
  $Id: contact_us.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Contact Us');
define('NAVBAR_TITLE', 'Contact Us');
define('TEXT_SUCCESS', 'Your enquiry has been successfully sent to the Store Owner.');
//define('EMAIL_SUBJECT', 'Enquiry from ' . STORE_NAME);

define('ENTRY_NAME', 'Full Name:');
define('ENTRY_EMAIL', 'E-Mail Address:');
define('ENTRY_ENQUIRY', 'Enquiry:');

// Contact US Email Subject : DMG
define('TEXT_EMAIL_SUBJECTS', '* select a subject *');
define('EMAIL_SUBJECT', 'Message from ' . STORE_NAME. ': ');
define('ENTRY_SUBJECT','Subject:');

// CRE Contact Us Enhancements 
// VJ
define('ENTRY_URGENT', 'Urgent:');
define('ENTRY_SELF', 'Send myself a copy:');
define('TEXT_SUBJECT_URGENT', 'Urgent');
define('ENTRY_TOPIC','Email Topic:');
define('ENTRY_TOPIC_1', 'Sales');
define('ENTRY_TOPIC_2', 'Tracking');
define('ENTRY_TOPIC_3', 'Technical');
define('ENTRY_TOPIC_4', 'Sponsorship');
define('ENTRY_TOPIC_5', 'Wholesale');

define('TEXT_SUBJECT_PREFIX', 'Contact from ' . STORE_NAME . ': ');
define('TEXT_BODY', '<div class="main"><strong>Corporate Headquarters:</strong><br>
Your Business Name <br>
  Your Business Street Address<br>
  Your Business City and Postal Code.<br>
  Business Country
<br><br>
Tel:  Telephone Number<br>
Fax:  Fax Number<br>
<br>
<strong>E-mail Contacts:</strong><br>
Business Relations - <a href="mailto:info@yourstore.com" target="blank">info@yourstore.com</a><br>
Wholesale Inquiries -&nbsp;<a href="mailto:wholesale@yourstore.com" target="blank">wholesale@yourstore.com</a><br>
Technical Assistance - <a href="mailto:tech@yourstore.com" target="blank">tech@yourstore.com</a><br>
Product Inquiries - <a href="mailto:sales@yourstore.com" target="blank">sales@yourstore.com</a><br>
Order Status - <a href="mailto:tracking@yourstore.com" target="blank">tracking@yourstore.com</a><br>
<br><br>
<strong>AOL IM Help:</strong> Your Business Name - <a href="aim:goim?screenname=YourAIM_ScreenName&message=Hi.+Are+you+there?">Click Here</a>
</div>
<br>
: Edit this information in includes/languages/' . $language . '/contact_us.php<br>');
?>