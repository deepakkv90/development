<?php
/*
  $Id: wishlist_help.php,v 1.1.1.1 2004/03/04 23:38:03 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_WISHLIST_HELP);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_WISHLIST_HELP, '', 'NONSSL'));

  $content = CONTENT_WISHLIST_HELP;
  $content_template = TEMPLATENAME_STATIC;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
