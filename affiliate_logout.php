<?php
/*

  $Id: affiliate_logout.php,v 1.1.1.1  $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_LOGOUT);

  $breadcrumb->add(NAVBAR_TITLE);
  $breadcrumb->add(NAVBAR_TITLE_AFFILIATE_CENTRAL, tep_href_link(FILENAME_AFFILIATE_CENTRAL));
  $breadcrumb->add(NAVBAR_TITLE_LOGOUT, tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));

   $old_user = (isset($_SESSION['affiliate_id']) ? (int)$_SESSION['affiliate_id'] : 0);  // store  to test if they *were* logged in
   $result = session_unregister("affiliate_id");

  $content = CONTENT_AFFILIATE_LOGOUT; 
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>
