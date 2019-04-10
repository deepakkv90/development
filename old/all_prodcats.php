<?php
/*
  $Id: all_prodcats.php,v 3.0 2004/02/21 by Ingo (info@gamephisto.de)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License

  modified by schizobinky 05/23/2004
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ALL_PRODCATS);

  $breadcrumb->add(HEADING_TITLE, tep_href_link(FILENAME_ALL_PRODCATS, '', 'NONSSL'));

  $content = CONTENT_ALL_PRODCATS;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
