<?php
/*
  $Id: cresecure_orders_transaction.php,v 1.0 2009/04/06 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$oID = (isset($_GET['oID'])) ? tep_db_prepare_input($_GET['oID']) : 0;
$rci = '';
$trans_query = tep_db_query("SELECT transaction_id from `transaction_log` WHERE order_id = '" . (int)$oID . "' LIMIT 1");
if (tep_db_num_rows($trans_query) > 0) {
  $trans = tep_db_fetch_array($trans_query);
  $transaction_id = $trans['transaction_id'];
  if ($transaction_id != '') {
    $rci = '<tr><td class="main"><b>' . ENTRY_PAYMENT_TRANS_ID . '</b></td><td class="main">' . $transaction_id . '</td></tr>';
  }
}
return $rci;
?>