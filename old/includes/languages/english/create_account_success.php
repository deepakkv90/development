<?php
/*
  $Id: create_account_success.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Create an Account');
define('NAVBAR_TITLE_2', 'Success');
define('HEADING_TITLE', 'Your Account Has Been Created!');

define('TEXT_ACCOUNT_CREATED_NEEDS_VALIDATE', 'Congratulations, Your new account has been successfully created. <br><br>You can now take advantage of memberprivileges to enhance your online shopping experience with us. <br>Name Badges International Thanks value your business.<br>If you have <small><b>ANY</b></small> questions about the operation of this online shop, please email us: <a href="' . tep_href_link(FILENAME_CONTACT_US) . '"></a><br>AJ PARKES<br>.<P>
A confirmation email with a validation/activation code has been sent to the email address that you provided us with.<P>
You MUST <b>Validate/Activate</b> your account before you can login. Please follow the instructions in the E-mail that we have sent you.<P>
If you have not received this email within five minutes, one of two things has happened:<P>
1. Your Confirmation email was mistakenly sent to your "BULK MAIL" or "SPAM MAIL" folder by your Internet Service Provider, please check for it there.
<P> OR <P>2. You entered your email address in wrong, please double check it or re-sign up again for an account.
<P> If after you have checked those things, you still have not received a confirmation email please <U><a href="' . tep_href_link(FILENAME_CONTACT_US) . '">contact us</a></U> for assistance.');

define('TEXT_ACCOUNT_CREATED_NO_VALIDATE', 'Congratulations, Your new account has been successfully created. You can now take advantage of member privileges to enhance your online shopping experience with us. If you have <small><b>ANY</b></small> questions about the operation of this online shop, please email us <a href="' . tep_href_link(FILENAME_CONTACT_US) . '"></a>.' );
?>
