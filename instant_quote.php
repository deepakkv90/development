<?php
/*
  $Id: create_account.php,v 1.4 2004/09/25 15:09:15 DMG Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Chain Reaction Works, Inc.

  Copyright &copy; 2003-2007
*/

  require('includes/application_top.php');
  
  require('includes/classes/class.phpmailer.php');
  $mail = new PHPMailer();
  
  /* needs to be included earlier to set the success message in the messageStack */
  
  require(DIR_WS_LANGUAGES . $language . '/'.FILENAME_INSTANT_QUOTE);
  
  
      
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_INSTANT_QUOTE, '', 'SSL'));
  
  $content = CONTENT_INSTANT_QUOTE;

  
  $javascript = 'form_check.js.php';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>