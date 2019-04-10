<?php
/*
  $Id: PayPal.inc.php,v 1.1.1.1 2004/09/22 13:45:13 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/classes/TransactionDetails/TransactionDetails.class.php');
  include_once(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $payment_statuses = array(
                              array('id' =>'Completed',          'text' => 'Completed'),
                              array('id' =>'Pending',            'text' => 'Pending'),
                              array('id' =>'Failed',             'text' => 'Failed'),
                              array('id' =>'Denied',             'text' => 'Denied'),
                              array('id' =>'Refunded',           'text' => 'Refunded'),
                              array('id' =>'Reversed',           'text' => 'Reversed'),
                              array('id' =>'Canceled_Reversal',  'text' => 'Canceled_Reversal')
                            );
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_ADMIN_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo tep_draw_form('payment_status', FILENAME_PAYPAL, '', 'get') . HEADING_PAYMENT_STATUS . ' ' . tep_draw_pull_down_menu('payment_status', array_merge(array(array('id' => 'ALL', 'text' => TEXT_ALL_IPNS)), $payment_statuses), (isset($_GET['payment_status']) ? $_GET['payment_status'] : '' ), 'onChange="this.form.submit();"').'</form>'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAYMENT_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT_GROSS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT_FEE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT_NET_AMOUNT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $common_vars = "p.txn_id, p.parent_txn_id, p.paypal_id, p.txn_type, p.payment_type, p.payment_status, p.pending_reason, p.mc_currency, p.mc_fee, p.payer_status, p.mc_currency, p.date_added, p.mc_gross, p.payment_date";
  if(isset($_GET['payment_status']) && tep_not_null($_GET['payment_status']) && $_GET['payment_status'] != 'ALL') {
    $ipn_search = " p.payment_status = '" . tep_db_input($_GET['payment_status']) . "'";
    switch($_GET['payment_status']) {
      case 'Pending':
      case 'Completed':
      default:
        $ipn_query_raw = "select " . $common_vars . " from " . TABLE_PAYPAL . " as p  where " . $ipn_search . " order by p.paypal_id DESC";
      break;
    }
  } else {
        $ipn_query_raw = "select " . $common_vars . " from " . TABLE_PAYPAL . " as p order by p.paypal_id DESC";
  }
  $ipn_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $ipn_query_raw, $ipn_query_numrows);
  $ipn_query = tep_db_query($ipn_query_raw);
  while ($ipn_trans = tep_db_fetch_array($ipn_query)) {
    if ((!isset($_GET['ipnID']) || (isset($_GET['ipnID']) && ($_GET['ipnID'] == $ipn_trans['paypal_id']))) && !isset($ipnInfo) ) {
      $ipnInfo = new objectInfo($ipn_trans);
    }

    if (isset($ipnInfo) && is_object($ipnInfo) && ($ipn_trans['paypal_id'] === $ipnInfo->paypal_id) ) {
        $rArray = array('Refunded','Reversed','Canceled_Reversal');
        if(in_array($ipnInfo->payment_status,$rArray)) {
          $txn_id = $ipnInfo->parent_txn_id;
        } else {
          $txn_id = $ipnInfo->txn_id;
        }
        $order_query = tep_db_query("select o.orders_id from " . TABLE_ORDERS . " o left join " . TABLE_PAYPAL . " p on p.paypal_id = o.payment_id where p.txn_id = '" . tep_db_input($txn_id) . "'");
        $onClick = '';
        if(tep_db_num_rows($order_query)) {
          $order = tep_db_fetch_array($order_query);
          $ipnInfo->orders_id = $order['orders_id'];
          $onClick = "onclick=\"document.location.href='" . tep_href_link(FILENAME_ORDERS, 'page=' . $_GET['page'] . '&oID=' . $ipnInfo->orders_id . '&action=edit' . '&referer=ipn') . "'\"";
        }
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" '. $onClick .'>' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PAYPAL, 'page=' . $_GET['page'] . '&ipnID=' . $ipn_trans['paypal_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"> <?php echo PayPal_TransactionDetails::date($ipn_trans['payment_date']); ?> </td>
                <td class="dataTableContent"><?php echo $ipn_trans['payment_status']; ?></td>
                <td class="dataTableContent" align="right"><?php echo PayPal_TransactionDetails::format($ipn_trans['mc_gross'], $ipn_trans['mc_currency']); ?></td>
                <td class="dataTableContent" align="right"><?php echo PayPal_TransactionDetails::format($ipn_trans['mc_fee'], $ipn_trans['mc_currency']); ?></td>
                <td class="dataTableContent" align="right"><?php echo PayPal_TransactionDetails::format($ipn_trans['mc_gross']-$ipn_trans['mc_fee'], $ipn_trans['mc_currency']); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($ipnInfo) && is_object($ipnInfo) && ($ipn_trans['paypal_id'] == $ipnInfo->paypal_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_PAYPAL, 'page=' . $_GET['page'] . '&ipnID=' . $ipn_trans['paypal_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $ipn_split->display_count($ipn_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TRANSACTIONS); ?></td>
                    <td class="smallText" align="right"><?php echo $ipn_split->display_links($ipn_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      break;
    case 'edit':
      break;
    case 'delete':
      break;
    default:
      if (isset($ipnInfo) && is_object($ipnInfo)) {
        $heading[] = array('text' => '<b>' . TEXT_INFO_PAYPAL_IPN_HEADING.' #' . $ipnInfo->paypal_id . '</b>');
        if(tep_not_null($ipnInfo->orders_id)) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('ipnID', 'oID', 'action')) . 'oID=' . $ipnInfo->orders_id .'&action=edit&referer=ipn') . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a>');
        } elseif(tep_not_null($ipnInfo->txn_id)) {
          $contents[] = array('align' => 'center', 'text' => '<a href="javascript:openWindow(\''.tep_href_link(FILENAME_PAYPAL, tep_get_all_get_params(array('ipnID', 'oID', 'action')) . 'action=details&info=' . $ipnInfo->txn_id ).'\');">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>');
        }
        $contents[] = array('text' => '<br>' . TABLE_HEADING_DATE . ': ' . PayPal_TransactionDetails::date($ipnInfo->payment_date));
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table>
