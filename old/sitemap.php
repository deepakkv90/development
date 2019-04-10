<?php
/*
  $Id: sitemap.php,v 1.1.1.1 2004/03/04 23:38:01 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SITEMAP);
 



	
	

    $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_DEFAULT, '', 'SSL'));

    $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_SITEMAP, '', 'SSL'));

    $content = CONTENT_SITEMAP;

    require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
