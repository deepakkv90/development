<?php
/*
  Id: account.php,v 1.1.1.1 2004/03/04 23:37:52 ccwjr Exp 

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Copyright &copy; 2003-2005 Chain Reaction Works, Inc.
  
  Last Modified by : $AUTHOR$
  Latest Revision  : $REVISION$
  Last Revision Date : $DATE$
  License :  GNU General Public License 2.0
  
  http://creloaded.com
  http://creforge.com
  
*/

  require('includes/application_top.php');

  if ( ! isset($_SESSION['customer_id']) ) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));

  $content = CONTENT_ACCOUNT;
  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>
