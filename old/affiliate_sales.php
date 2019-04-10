<?php
/*
  $Id: affiliate_sales.php,v 1.1.1.1 Exp $
  OSC-Affiliate
  Contribution based on:
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  if (!isset($_SESSION['affiliate_id'])) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_AFFILIATE, '', 'SSL'));
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_SALES);
  $breadcrumb->add(NAVBAR_TITLE_AFFILIATE_CENTRAL, tep_href_link(FILENAME_AFFILIATE_CENTRAL));
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'));
/*
  $affiliate_sales_raw = "
    select  a.*, o.orders_status as orders_status_id, os.orders_status_name as orders_status from " . TABLE_AFFILIATE_SALES . " a, 
    " . TABLE_ORDERS . " o, 
    " . TABLE_ORDERS_STATUS . " os
    where a.affiliate_id = '" . $_SESSION['affiliate_id'] . "' and
    o.orders_id = a.affiliate_orders_id and
    os.orders_status_id and o.orders_status and
    language_id = '" . $languages_id . "'
    order by affiliate_date DESC
    ";
*/
  $affiliate_sales_raw = "
      select asale.*, os.orders_status_name as orders_status, o.orders_status as orders_status_id
      from " . TABLE_AFFILIATE_SALES . " asale ,
           " . TABLE_ORDERS . " o,
           " . TABLE_ORDERS_STATUS . " os,
           " . TABLE_AFFILIATE . " a
      where asale.affiliate_id = '" . $_SESSION['affiliate_id'] . "'
        and a.affiliate_id = '" . $_SESSION['affiliate_id'] . "'
        and asale.affiliate_orders_id = o.orders_id
        and os.orders_status_id = o.orders_status
        and language_id = " . $languages_id . "
      order by affiliate_date desc 
      ";
  $affiliate_sales_split = new splitPageResults($affiliate_sales_raw, MAX_DISPLAY_SEARCH_RESULTS);
  $content = CONTENT_AFFILIATE_SALES ;

  $javascript = CONTENT_AFFILIATE_SUMMARY . '.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
