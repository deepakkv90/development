<?php
/*
  $Id: popup_paypal.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  require('includes/modules/payment/paypal/classes/Page/Page.class.php');
  $page = new PayPal_Page();
  $page->setBaseDirectory('includes/modules/payment/paypal/');

  $action = (isset($_GET['action'])) ? $_GET['action'] : '';

  switch($action) {
    case 'css':
      header("Content-Type: text/css");
      echo $page->getCSS($_GET['id']);
      exit;
      break;
  }

  require("includes/application_top.php");

  $navigation->remove_current_page();

  $page->setBaseURL(DIR_WS_MODULES . 'payment/paypal/');
  $page->addCSS('popup_paypal.php?action=css&id=general');
  $page->addCSS('popup_paypal.php?action=css&id=stylesheet');

  switch($action) {
    default:
      $page->setContentLangaugeFile($page->baseDirectory.'catalog/languages',$language,'info_cc.inc.php');
      $page->setTemplate('osC_Catalog');
      break;
  }
  require($page->template());
  require("includes/counter.php");
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>