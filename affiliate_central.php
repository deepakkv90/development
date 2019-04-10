<?php
/*
  $Id: affiliate_summary.php,v 2.00 2003/10/12

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
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_CENTRAL);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_CENTRAL));

  $affiliate_banner_history_raw = "select sum(affiliate_banners_shown) as count from " . TABLE_AFFILIATE_BANNERS_HISTORY .  " where affiliate_banners_affiliate_id  = '" . (int)$_SESSION['affiliate_id'] . "'";
  $affiliate_banner_history_query=tep_db_query($affiliate_banner_history_raw);
  $affiliate_banner_history = tep_db_fetch_array($affiliate_banner_history_query);
  $affiliate_impressions = $affiliate_banner_history['count'];
  if ($affiliate_impressions == 0) $affiliate_impressions="n/a";

  $affiliate_clickthroughs_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_id = '" . (int)$_SESSION['affiliate_id'] . "'";
  $affiliate_clickthroughs_query = tep_db_query($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_query);
  $affiliate_clickthroughs =$affiliate_clickthroughs['count'];

  $affiliate_sales_raw = "select count(*) as count, sum(a.affiliate_value) as total, sum(a.affiliate_payment) as payment from
      " . TABLE_AFFILIATE_SALES . " a
      where a.affiliate_id = '" . (int)$_SESSION['affiliate_id'] . "'
        and a.affiliate_billing_status = 1
      ";
  $affiliate_sales_query = tep_db_query($affiliate_sales_raw);
  $affiliate_sales = tep_db_fetch_array($affiliate_sales_query);

  $affiliate_transactions=$affiliate_sales['count'];
  if ($affiliate_clickthroughs > 0) {
    $affiliate_conversions = tep_round(($affiliate_transactions / $affiliate_clickthroughs)*100, 2) . "%";
  } else {
    $affiliate_conversions = "n/a";
  }
  $affiliate_amount = $affiliate_sales['total'];
  if ($affiliate_transactions>0) {
$affiliate_average = tep_round($affiliate_amount / $affiliate_transactions, 2);
  } else {
$affiliate_average = "n/a";
  }
  $affiliate_commission = $affiliate_sales['payment'];

  $affiliate_values = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$_SESSION['affiliate_id'] . "'");
  $affiliate = tep_db_fetch_array($affiliate_values);
  $affiliate_percent = $affiliate['affiliate_commission_percent'];
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;
  
  // Query the pending amounts to give a complete picture
  $affiliate_pending_raw = "select count(*) as count, sum(a.affiliate_value) as total, sum(a.affiliate_payment) as payment from
      " . TABLE_AFFILIATE_SALES . " a,
      " . TABLE_ORDERS . " o
      where a.affiliate_id = '" . (int)$_SESSION['affiliate_id'] . "'
        and a.affiliate_billing_status = 0
        and o.orders_id = a.affiliate_orders_id
        and o.orders_status = '" . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . "'
      ";
  $affiliate_pending_query = tep_db_query($affiliate_pending_raw);
  $affiliate_pending = tep_db_fetch_array($affiliate_pending_query);

  $affiliate_pending_transactions = $affiliate_pending['count'];
  $affiliate_pending_amount = $affiliate_pending['total'];
  if ($affiliate_pending_transactions > 0) {
    $affiliate_pending_average = tep_round($affiliate_pending_amount / $affiliate_pending_transactions, 2);
  } else {
    $affiliate_pending_average = "n/a";
  }
  $affiliate_pending_commission = $affiliate_pending['payment'];
  
  
  $content = CONTENT_AFFILIATE_CENTRAL;
  $javascript = CONTENT_AFFILIATE_SUMMARY . '.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');

?>
