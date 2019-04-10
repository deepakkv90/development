<?php
/*
  $Id: login.php,v 1.15 2003/06/09 22:46:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


function get_ip() {
if (isset($_SERVER)) {
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
$ip = $_SERVER['HTTP_CLIENT_IP'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
} else {
if ( getenv('HTTP_X_FORWARDED_FOR') ) {
$ip = getenv('HTTP_X_FORWARDED_FOR');
} elseif ( getenv('HTTP_CLIENT_IP') ) {
$ip = getenv('HTTP_CLIENT_IP');
} else {
$ip = getenv('REMOTE_ADDR');
}
}
return $ip; 
}
$user_ip = get_ip();


define('NAVBAR_TITLE_1', 'Account');
define('NAVBAR_TITLE_2', 'Account Validation');
define('HEADING_TITLE', 'Account Validation');
define('TEXT_MAIN', 'Do you need a new Validation Link ?');
//define('ENTRY_EMAIL_ADDRESS', 'Emailadress: ');
define('SUCCESS_REGISTRATION_CODE_SENT', 'Your new validation code was sent. Please check your email account');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', 'This email address is not registered');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Your Validation Code is');
define('EMAIL_PASSWORD_REMINDER_BODY', 'We received a request from the IP ' . $user_ip . ' to renew your Validation Code.' . "\n\n" . 'Your Validation Code for \'' . STORE_NAME . '\' :' . "\n\n" . '%s' . "\n\n");
define('EMAIL_PASSWORD_REMINDER_BODY2', 'Please follow this link to validate your account: %s' . "\n\n");

?>