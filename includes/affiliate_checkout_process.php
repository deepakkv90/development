<?php
/*
  $Id: affiliate_checkout_process.php,v 1.1.1.1 2004/03/04 23:40:36 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

if (isset($_SESSION['affiliate_ref'])){
  // fetch the net total of an order
  $affiliate_total = 0;
  $affiliate_clientdate = (isset($affiliate_clientdate)) ? $affiliate_clientdate : date("Y-m-d H:m:s");
  $order_id = (isset($insert_id)) ? $insert_id : (int)$_GET['order_id'];
  $affiliate_clientip = (isset($affiliate_clientip)) ? $affiliate_clientip : $_SERVER['REMOTE_ADDR'];
  $affiliate_clientbrowser = (isset($affiliate_clientbrowser)) ? $affiliate_clientbrowser : $_SERVER['HTTP_USER_AGENT'];   
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    $affiliate_total += $order->products[$i]['final_price'] * $order->products[$i]['qty'];
  }
  //subtract disocunt
  // get order total info
  $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "' order by sort_order");
  $order->totals = array();
  while ($totals = tep_db_fetch_array($totals_query)) { $order->totals[] = array('title' => $totals['title'], 'text' => $totals['text'], 'class' => $totals['class'], 'value' => $totals['value'], 'orders_total_id' => $totals['orders_total_id']); }
  $TotalsArray = array();
  for ($i=0; $i<sizeof($order->totals); $i++) {
    $TotalsArray[] = array("Name" => $order->totals[$i]['title'], "Price" => number_format($order->totals[$i]['value'], 2, '.', ''), "Class" => $order->totals[$i]['class'], "TotalID" => $order->totals[$i]['orders_total_id']);
    $TotalsArray[] = array("Name" => "          ", "Price" => "", "Class" => "ot_custom", "TotalID" => "0");
  }  
  array_pop($TotalsArray);
  foreach($TotalsArray as $TotalIndex => $TotalDetails) {
    //set discount at 0
    $discount='0';
    //test for each discount avalaible in default cart and add them to gether.
    if($TotalDetails["Class"] == "ot_coupon") {
      $discount += $TotalIndex[value];
    }
    if($TotalDetails["Class"] == "ot_gv ") {
      $discount += $TotalIndex[value];
    }
    if($TotalDetails["Class"] == "ot_lev_discount") {
      $discount += $TotalIndex[value];
    }
    if($TotalDetails["Class"] == "ot_qty_discount") {
      $discount += $TotalIndex[value];
    }
  }
  //subtract discount form product final total
  $affiliate_total -=  $discount;
  //end subtract discount
  $affiliate_total = tep_round($affiliate_total, 2);
  // Check for individual commission
  $affiliate_percentage = 0;
  if (AFFILATE_INDIVIDUAL_PERCENTAGE == 'true') {
    $affiliate_commission_query = tep_db_query ("select affiliate_commission_percent from " . TABLE_AFFILIATE . " where affiliate_id = '" . $_SESSION['affiliate_ref'] . "'");
    $affiliate_commission = tep_db_fetch_array($affiliate_commission_query);
    $affiliate_percent = $affiliate_commission['affiliate_commission_percent'];
  }
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;
  $affiliate_payment = tep_round(($affiliate_total * $affiliate_percent / 100), 2);
  if ($_SESSION['affiliate_ref']) {
    $sql_data_array = array('affiliate_id' => $_SESSION['affiliate_ref'],
                            'affiliate_date' => $affiliate_clientdate,
                            'affiliate_browser' => $affiliate_clientbrowser,
                            'affiliate_ipaddress' => $affiliate_clientip,
                            'affiliate_value' => $affiliate_total,
                            'affiliate_payment' => $affiliate_payment,
                            'affiliate_orders_id' => $order_id,
                            'affiliate_clickthroughs_id' => $_SESSION['affiliate_clickthroughs_id'],
                            'affiliate_percent' => $affiliate_percent,
                            'affiliate_salesman' => $_SESSION['affiliate_ref']);
    tep_db_perform(TABLE_AFFILIATE_SALES, $sql_data_array);
    if (AFFILATE_USE_TIER == 'true') {
      $affiliate_tiers_query = tep_db_query ("SELECT aa2.affiliate_id, (aa2.affiliate_rgt - aa2.affiliate_lft) as height
                                                from affiliate_affiliate AS aa1, affiliate_affiliate AS aa2
                                              WHERE  aa1.affiliate_root = aa2.affiliate_root
                                                and aa1.affiliate_lft BETWEEN aa2.affiliate_lft and aa2.affiliate_rgt
                                                and aa1.affiliate_rgt BETWEEN aa2.affiliate_lft and aa2.affiliate_rgt
                                                and aa1.affiliate_id =  '" . $_SESSION['affiliate_ref'] . "'
                                              ORDER by height asc limit 1, " . AFFILIATE_TIER_LEVELS . "
                                            ");
      $affiliate_tier_percentage = split("[;]" , AFFILIATE_TIER_PERCENTAGE);
      $i=0;
      while ($affiliate_tiers_array = tep_db_fetch_array($affiliate_tiers_query)) {
        $affiliate_percent = $affiliate_tier_percentage[$i];
        $affiliate_payment = tep_round(($affiliate_total * $affiliate_percent / 100), 2);
        if ($affiliate_payment > 0) {
          $sql_data_array = array('affiliate_id' => $affiliate_tiers_array['affiliate_id'],
                                  'affiliate_date' => $affiliate_clientdate,
                                  'affiliate_browser' => $affiliate_clientbrowser,
                                  'affiliate_ipaddress' => $affiliate_clientip,
                                  'affiliate_value' => $affiliate_total,
                                  'affiliate_payment' => $affiliate_payment,
                                  'affiliate_orders_id' => $order_id,
                                  'affiliate_clickthroughs_id' => $_SESSION['affiliate_clickthroughs_id'],
                                  'affiliate_percent' => $affiliate_percent,
                                  'affiliate_salesman' => $_SESSION['affiliate_ref']);
          tep_db_perform(TABLE_AFFILIATE_SALES, $sql_data_array);
        }
        $i++;
      }
    }
  }
}
?>
