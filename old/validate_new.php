<?php
/*
  $Id: index.php,v 1.2 2004/03/09 19:56:29 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_VALIDATE_NEW);

    $content = CONTENT_VALIDATE_NEW;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
