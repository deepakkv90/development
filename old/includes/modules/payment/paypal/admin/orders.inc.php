<?php
/*
  $Id: orders.inc.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal.php');
  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/functions/paypal.fnc.php');
  paypal_include_lng(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/languages/', $language, 'paypal.lng.php');
  require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/database_tables.inc.php');
  $paypal = new paypal($order->info['paypal_ipn_id']);
?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2" style="padding-bottom:0px;"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . '../modules/payment/paypal/images/paypal_logo.gif','PayPal'); ?></td>
          </tr>
          <tr>
            <td colspan="2" style="padding-top:0px;"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="2">
              <table border="0" width="510" cellspacing="0" cellpadding="2">
              <tr valign="top">
                <td>
                      <table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_CUSTOMER; ?></b></td><td class="main" nowrap="nowrap">&nbsp;<?php echo $paypal->customer['first_name'] . ' ' . $paypal->customer['last_name'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_BUSINESS_NAME; ?></b></td><td class="main" nowrap="nowrap">&nbsp;<?php echo $paypal->customer['payer_business_name'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td><td class="main" nowrap="nowrap">&nbsp;<?php echo $paypal->customer['payer_email'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_PAYER_ID; ?></b></td><td class="main" nowrap="nowrap">&nbsp;<?php echo $paypal->customer['payer_id'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_PAYER_STATUS; ?></b></td><td class="main" nowrap="nowrap">&nbsp;<?php echo $paypal->customer['payer_status'];?></td>
                      </tr>
                      </table>
                </td>
                <td style="padding-left:50px;">
                      <table border="0" cellspacing="0" cellpadding="2">
                      <tr valign="top">
                        <td class="main" style="padding-right: 10px;" nowrap="nowrap"><?php echo '<b>' . ENTRY_ADDRESS . '</b><br>' . $paypal->customer['address_status']; ?></td>
                        <td class="main" nowrap="nowrap"><?php echo $paypal->customer['address_name'] . '<br>' . $paypal->customer['address_street'] . '<br>' . $paypal->customer['address_city'] . '<br>' . $paypal->customer['address_state'] . '<br>' . $paypal->customer['address_zip'] . '<br>' . $paypal->customer['address_country']; ?></td>
                      </tr>
                      </table>
                </td>
                <td style="padding-left:50px;">
                      <table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_PAYMENT_TYPE; ?></b>&nbsp;</td><td class="main" nowrap="nowrap"><?php echo $paypal->info['payment_type'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_CART_ITEMS; ?></b>&nbsp;</td><td class="main"><?php echo $paypal->txn['num_cart_items'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_PAYPAL_IPN_TXN; ?></b>&nbsp;</td><td class="main" nowrap="nowrap"><?php echo $paypal->info['txn_id'];?></td>
                      </tr>
<?php
    $txn_signature_query = tep_db_query("select txn_signature from " . TABLE_ORDERS_SESSION_INFO . " where orders_id = '" . (int)$oID . "' limit 1");
    if (tep_db_num_rows($txn_signature_query)) {
      $txn_signature = tep_db_fetch_array($txn_signature_query);
      echo            '<tr>' .
                      '  <td class="main" nowrap="nowrap"><b>' . TEXT_TXN_SIGNATURE . '</b>&nbsp;</td><td class="main" nowrap="nowrap">' . $txn_signature['txn_signature'] . '</td>' .
                      '</tr>';

    }
?>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_INVOICE; ?></b>&nbsp;</td><td class="main" nowrap="nowrap"><?php echo $paypal->info['invoice'];?></td>
                      </tr>
                      </table>
                </td>
              </tr>
              </table>
           </td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr valign="top">
            <td class="main">
              <table border="0" cellspacing="0" cellpadding="2">
              <tr valign="top">
                <td>
                      <table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_MC_CURRENCY; ?></b></td><td class="main">&nbsp;<?php echo $paypal->info['mc_currency'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_MC_GROSS; ?></b></td><td class="main">&nbsp;<?php echo $paypal->txn['mc_gross'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_MC_FEE; ?></b></td><td class="main">&nbsp;<?php echo $paypal->txn['mc_fee'];?></td>
                      </tr>
                      </table>
             </td>
             <td class="main" style="padding-left:50px;">
                      <table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_SETTLE_AMOUNT; ?></b></td><td class="main">&nbsp;<?php echo $paypal->txn['settle_amount'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_SETTLE_CURRENCY; ?></b></td><td class="main">&nbsp;<?php echo $paypal->txn['settle_currency'];?></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_EXCHANGE_RATE; ?></b></td><td class="main">&nbsp;<?php echo $paypal->txn['exchange_rate'];?></td>
                      </tr>
                      </table>
             </td>
              </tr>
              </table>
           </td>
           <td class="main" width="100%" height="100%" rowspan="2" style="padding-left:50px;">
                      <table border="0" width="100%" height="100%" cellspacing="2" cellpadding="0">
                      <tr valign="top">
                        <td class="main" nowrap="nowrap"><?php echo '<b>' . ENTRY_CUSTOMER_COMMENTS . '</b>'; if (tep_not_null($paypal->customer['memo'])) echo ' (' . tep_datetime_short($paypal->info['payment_date']) . ') '; ?></td>
                      </tr>
<?php if (tep_not_null($paypal->customer['memo'])) { ?>
                      <tr valign="top">
                        <td class="main" height="100%">
                          <?php echo '<div style="border:0px solid gray; padding: 5px auto; width:100%;">' . nl2br(tep_db_output($paypal->customer['memo'])) . '</div>' ;?>
                        </td>
                      </tr>
<?php } ?>
                      </table>
           </td>
          </tr>
          <tr valign="top">
            <td class="main">
                  <table border="1" cellspacing="0" cellpadding="5">
                  <tr>
                    <td class="smallText" align="center" nowrap="nowrap"><b><?php echo TABLE_HEADING_IPN_DATE; ?></b></td>
                    <td class="smallText" align="center" nowrap="nowrap"><b><?php echo TABLE_HEADING_PAYMENT_STATUS; ?></b></td>
                    <td class="smallText" align="center" nowrap="nowrap"><b><?php echo TABLE_HEADING_PENDING_REASON; ?></b></td>
                  </tr>
<?php
    $paypal_history_query = tep_db_query("select payment_status, pending_reason, date_added from " . TABLE_PAYPAL_PAYMENT_STATUS_HISTORY . " where paypal_ipn_id = '" . (int)$order->info['paypal_ipn_id'] . "' order by date_added");
    if (tep_db_num_rows($paypal_history_query)) {
      while ($paypal_history = tep_db_fetch_array($paypal_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center" nowrap="nowrap">' . tep_datetime_short($paypal_history['date_added']) . '</td>' . "\n".
             '            <td class="smallText" nowrap="nowrap">' . $paypal_history['payment_status'] . '</td>' . "\n" .
             '            <td class="smallText" align="center" nowrap="nowrap">'. (tep_not_null($paypal_history['pending_reason']) ? $paypal_history['pending_reason'] : '&nbsp;') . '</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="3" nowrap="nowrap">' . TEXT_NO_IPN_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
                  </table>
           </td>
           <td></td>
          </tr>
