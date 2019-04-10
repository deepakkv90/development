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
define('TEXT_SUCCESS', 'Thank you. Your enquiry has been successfully sent. <br> We will come back to you very shortly.');
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
define('ENTRY_TOPIC_2', 'Tracking Order');
define('ENTRY_TOPIC_3', 'Technical');
define('ENTRY_TOPIC_4', 'Other');
define('ENTRY_TOPIC_5', 'Wholesale');

define('TEXT_SUBJECT_PREFIX', 'Contact from ' . STORE_NAME . ': ');
define('TEXT_BODY', '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td align="left" valign="top"><span style="font-size:14px; font-weight:bold;">Name Badges International Pty Ltd</span> <br><br>
 Suite F Level 1 Octagon, 110 George Street<br> Parramatta, NSW 2150 Australia<br>
<br>

<strong>For Email Contacts, Please use the form below to contact us or to make an enquiry.</strong>
');
?>