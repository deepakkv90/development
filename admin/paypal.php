<?php
/*
  $Id: paypal.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  define('DIR_WS_CATALOG_MODULES',DIR_WS_CATALOG_LANGUAGES.'../modules/');

  require(DIR_FS_CATALOG_MODULES . 'payment/paypal/classes/Page/Page.class.php');
  require(DIR_FS_CATALOG_MODULES . 'payment/paypal/database_tables.inc.php');

  $page = new PayPal_Page();
  $page->setBaseDirectory(DIR_FS_CATALOG_MODULES . 'payment/paypal/');
  $page->setBaseURL(HTTP_SERVER . DIR_WS_CATALOG_MODULES . 'payment/paypal/');
  $page->includeLanguageFile('admin/languages',$language,'paypal.lng.php');
  $page->addCSS('paypal.php?action=css&id=general');
  $page->addCSS('paypal.php?action=css&id=stylesheet');
  $page->addJavaScript($page->baseURL.'templates/js/general.js');
  $action = (isset($_GET['action'])) ? $_GET['action'] : '';

  switch($action) {
    case 'details':
      $page->setTitle(HEADING_DETAILS_TITLE);
      $page->includeLanguageFile('admin/languages',$language,'TransactionDetails.lng.php');
      $page->setContentFile(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/TransactionDetails.inc.php');
      $page->setTemplate('popup');
      break;
    case 'itp':
     include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/classes/IPN/IPN.class.php');
      $page->setTitle(HEADING_ITP_TITLE);
      $page->setContentFile(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/TestPanel/TestPanel.inc.php');
      $page->setTemplate('default');
      $page->setOnLoad('javascript:window.focus();document.ipn.txn_id.select();');
      break;
    case 'itp-help':
      $page->setTitle(HEADING_ITP_HELP_TITLE);
      $page->setContentLangaugeFile(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/TestPanel/languages',$language,'Help.inc.php');
      $page->setOnLoad('javascript:window.focus();');
      $page->setTemplate('popup');
      break;
    case 'help':
      $page->setTitle(HEADING_HELP_CONTENTS_TITLE);
      $page->setContentLangaugeFile(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/languages',$language,'Help.inc.php');
      $page->setOnLoad('javascript:window.focus();');
      $page->setTemplate('popup');
      break;
    case 'help-cfg':
      $page->setTitle(HEADING_HELP_CONFIG_TITLE);
      $page->setContentLangaugeFile(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/languages',$language,'Help_Config.inc.php');
      $page->setOnLoad('javascript:window.focus();');
      $page->setTemplate('default');
      break;
    case 'help-faqs':
      $page->setTitle(HEADING_HELP_FAQS_TITLE);
      $page->setContentLangaugeFile(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/languages',$language,'Help_FAQs.inc.php');
      $page->setOnLoad('javascript:window.focus();');
      $page->setTemplate('default');
      break;
    case 'css':
      header("Content-Type: text/css");
      echo $page->getCSS($_GET['id']);
      exit;
      break;
    case 'logo':
      header('Content-Type: image/gif'); echo $page->logo(); exit;
      break;
    default:
      $page->setContentFile(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/PayPal.inc.php');
      $page->setTemplate('osC_Admin');
      $page->setOnLoad('javascript:SetFocus();');
     break;
  }
  require($page->template());
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
