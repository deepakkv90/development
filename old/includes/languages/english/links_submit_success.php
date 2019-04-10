<?php
/*
  $Id: links_submit_success.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Links');
define('NAVBAR_TITLE_2', 'Success');
define('HEADING_TITLE', 'Your Link Has Been Submitted!');
define('TEXT_LINK_SUBMITTED', 'Congratulations! Your link has been successfully submitted! It will be added to our listing as soon as we approve it. If you have <small><b>ANY</b></small> questions, please email the <a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'SSL') . '">store owner</a>.<br><br>You will receive an email confirming your submittal. If you have not received it within the hour, please <a href="' . tep_href_link(FILENAME_CONTACT_US, '', 'SSL') . '">contact us</a>. Also, you will receive an email as soon as your link is approved.');
?>