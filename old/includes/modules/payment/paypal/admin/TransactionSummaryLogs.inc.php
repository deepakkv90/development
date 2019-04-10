<?php
/*
  $Id: TransactionSummaryLogs.inc.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/classes/TransactionDetails/TransactionDetails.class.php');
  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/classes/Page/Page.class.php');
  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/functions/general.func.php');
  paypal_include_lng(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/languages/', $language, 'paypal.lng.php');
  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/database_tables.inc.php');
  $paypal = new PayPal_TransactionDetails(TABLE_PAYPAL,$order->info['payment_id']);
?>
      <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr valign="top">
            <td style="padding-bottom:0px;"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . '../modules/payment/paypal/images/paypal_logo.gif','PayPal'); ?></td>
          </tr>
          <tr valign="top">
            <td class="main">
            <style type="text/css">.Txns{font-family: Verdana;font-size: 10px;color: #000000;background-color: #aaaaaa;}.Txns td {padding: 2px 4px;}.TxnsTitle td {color: #000000;font-weight: bold;font-size: 13px;}.TxnsSTitle td{background-color: #ccddee;color: #000000;font-weight: bold;}</style>
            <script type="text/javascript">function openWindow(url,name,args) {if (url == null || url == '') exit;if (name == null || name == '') name = 'popupWin';if (args == null || args == '') args = 'toolbar,status,scrollbars,resizable,width=640,height=480,left=50,top=50';popupWin = window.open(url,name,args);popupWin.focus();}</script>
            <table cellspacing="1" cellpadding="1" border="0" class="Txns">
              <tr>
                <td colspan="7" bgcolor="#EEEEEE">&nbsp;<b><?php echo TABLE_HEADING_TXN_ACTIVITY; ?></b></td>
              </tr>
              <tr class="TxnsSTitle">
                <td nowrap="nowrap">&nbsp;<?php echo TABLE_HEADING_DATE; ?>&nbsp;</td>
                <td nowrap="nowrap">&nbsp;<?php echo TABLE_HEADING_PAYMENT_STATUS; ?>&nbsp;</td>
                <td nowrap="nowrap">&nbsp;<?php echo TABLE_HEADING_DETAILS; ?>&nbsp;</td>
                <td nowrap="nowrap">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                <td nowrap="nowrap" align="right">&nbsp;<?php echo TABLE_HEADING_PAYMENT_GROSS; ?>&nbsp;</td>
                <td nowrap="nowrap" align="right">&nbsp;<?php echo TABLE_HEADING_PAYMENT_FEE; ?>&nbsp;</td>
                <td nowrap="nowrap" align="right">&nbsp;<?php echo TABLE_HEADING_PAYMENT_NET_AMOUNT; ?>&nbsp;</td>
              </tr>
<?php
  if (tep_not_null($paypal->info['txn_id'])) {
    $paypal_history_query = tep_db_query("select txn_id, payment_status, mc_gross, mc_fee, mc_currency, date_added, payment_date from " . TABLE_PAYPAL . " where parent_txn_id = '" . tep_db_input($paypal->info['txn_id']) . "' order by date_added desc");
    if (tep_db_num_rows($paypal_history_query)) {
      $phCount = 1;
      while ($paypal_history = tep_db_fetch_array($paypal_history_query)) {
        $phColor = (($phCount/2) == floor($phCount/2)) ? '#FFFFFF' : '#EEEEEE';
        echo '          <tr bgcolor="'.$phColor.'">' . "\n" .
             '            <td nowrap="nowrap">&nbsp;' . $paypal->date($paypal_history['payment_date']) . '&nbsp;</td>' . "\n".
             '            <td nowrap="nowrap">&nbsp;' . $paypal_history['payment_status'] . '&nbsp;</td>' . "\n" .
             '            <td nowrap="nowrap">&nbsp;'. PayPal_Page::draw_href_link(TABLE_HEADING_DETAILS,'action=details&info='.$paypal_history['txn_id']).'&nbsp;</td>' . "\n" .

             '            <td nowrap="nowrap">&nbsp;</td>' . "\n" . //Action


             '            <td nowrap="nowrap" align="right">&nbsp;'. $paypal->format($paypal_history['mc_gross'],$paypal_history['mc_currency']) . '&nbsp;</td>' . "\n" .
             '            <td nowrap="nowrap" align="right">&nbsp;'. $paypal->format($paypal_history['mc_fee'],$paypal_history['mc_currency']) . '&nbsp;</td>' . "\n" .
             '            <td nowrap="nowrap" align="right">&nbsp;'. $paypal->format($paypal_history['mc_gross']-$paypal_history['mc_fee'],$paypal_history['mc_currency']) . '&nbsp;</td>' . "\n" .

             '          </tr>' . "\n";
        $phCount++;
      }
    }

  //Now determine whether the order is on hold
  if($order->info['orders_status'] === MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID) {
    $ppImgAccept = tep_image(DIR_WS_CATALOG_LANGUAGES . '../modules/payment/paypal/images/act_accept.gif',IMAGE_BUTTON_TXN_ACCEPT);
    $ppAction = '<a href="'.tep_href_link(FILENAME_ORDERS,tep_get_all_get_params(array('action')).'action=accept_order&digest='.$paypal->digest()).'">'.$ppImgAccept.'</a>';
  } else {
    $ppAction = '';
  }
?>
              <tr bgcolor="#FFFFFF">
                <td nowrap="nowrap">&nbsp;<?php echo $paypal->date($paypal->info['payment_date']); ?>&nbsp;</td>
                <td nowrap="nowrap">&nbsp;<?php echo $paypal->info['payment_status']; ?>&nbsp;</td>
                <td nowrap="nowrap">&nbsp;<?php echo PayPal_Page::draw_href_link(TABLE_HEADING_DETAILS,'action=details&info='.$paypal->info['txn_id']); ?>&nbsp;</td>

                <td nowrap="nowrap">&nbsp;<?php echo $ppAction; ?>&nbsp;</td>

                <td align="right" nowrap="nowrap">&nbsp;<?php echo $paypal->format($paypal->txn['mc_gross'],$paypal->txn['mc_currency']); ?>&nbsp;</td>
                <td align="right" nowrap="nowrap">&nbsp;<?php echo $paypal->format($paypal->txn['mc_fee'],$paypal->txn['mc_currency']); ?>&nbsp;</td>
                <td align="right" nowrap="nowrap">&nbsp;<?php echo $paypal->format($paypal->txn['mc_gross']-$paypal->txn['mc_fee'],$paypal->txn['mc_currency']); ?>&nbsp;</td>
              </tr>
<?php } else { ?>
              <tr bgcolor="#FFFFFF">
                <td colspan="7" nowrap="nowrap">&nbsp;<?php echo sprintf(TEXT_NO_IPN_HISTORY,$paypal->transactionSignature($oID)); ?>&nbsp;</td>
              </tr>
<?php } ?>
            </table></td>
          </tr>
        </table></td>
      </tr>