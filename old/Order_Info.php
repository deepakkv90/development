<?php
/*
  $Id: Order_Info.php,v 0.52 2002/09/21 hpdl Exp $
        by Cheng
        OSCommerce v2.2 CVS (09/17/02)
   Modified versions of create_account.php and related
  files.  Allowing 'purchase without account'.
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
  $breadcrumb->add(NAV_ORDER_INFO, tep_href_link('Order_Info.php', '', 'SSL'));
$content = CONTENT_ORDER_INFO;
$javascript = 'form_check.js.php';
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
