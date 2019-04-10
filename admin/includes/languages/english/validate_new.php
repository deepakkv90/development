<?php
/*
  $Id: customers.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

$user_ip = getenv('REMOTE_ADDR');;

define('HEADING_TITLE', 'Resend Validation email');
define('TEXT_EMAIL_CONFIRMATION','Are you sure you want to resend the validation email?'); 
define('ENTRY_EMAIL_ADDRESS', 'Emailadress: ');
define('SUCCESS_REGISTRATION_CODE_SENT', 'New validation code Sent');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', 'This email address is not registered');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Your Validation Code is');
define('EMAIL_PASSWORD_REMINDER_BODY', "\n\n".'We are resending your Validation Code.' . "\n\n" . 'Your Validation Code for \'' . STORE_NAME . '\' :' . "\n\n" . '%s' . "\n\n");
define('EMAIL_PASSWORD_REMINDER_BODY2', 'Please follow this link to validate your account: %s' . "\n\n");
define('TEXT_ACCOUNT_ALREADY_EXIST', 'This Account is already active!');
define('IMAGE_BUTTON_BACK',"Go Back");

?>
