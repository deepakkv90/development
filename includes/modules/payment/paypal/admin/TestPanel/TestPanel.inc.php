<?php
/*
  $Id: TestPanel.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
?>
<form name="ipn" method="POST" action="<?php echo tep_catalog_href_link('ipn.php'); ?>">
<input type="hidden" name="business" value="<?php echo MODULE_PAYMENT_PAYPAL_BUSINESS_ID; ?>"/>
<input type="hidden" name="receiver_email" value="<?php echo MODULE_PAYMENT_PAYPAL_ID; ?>"/>
<input type="hidden" name="verify_sign" value="PAYPAL_SHOPPING_CART_IPN-TEST_TRANSACTION-00000000000000"/>
<input type="hidden" name="payment_date" value="<?php echo date("H:i:s M d, Y T"); ?>">
<input type="hidden" name="digestKey" value="<?php echo PayPal_IPN::digestKey(); ?>">
<table border="0" cellspacing="0" cellpadding="2" class="main">
<?php if (MODULE_PAYMENT_PAYPAL_IPN_TEST_MODE == 'Off') { ?>
  <tr>
    <td>
      <table border="0" cellspacing="0" cellpadding="0" style="padding: 4px; border:1px solid #aaaaaa; background: #ffffcc;">
        <tr>
          <td><?php echo $page->image('icon_error_40x40.gif','Error icon'); ?></td>
          <td><br class="text_spacer"></td>
          <td class="pperrorbold" style="text-align: center; width:100%;"><!-- Test Mode must be enabled! --><?php echo PAYPAL_IPN_TEST_MODE1?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><br class="h10"></td>
  </tr>
<?php } ?>
  <tr>
    <td style="text-align: right;"><a href="<?php echo tep_href_link(FILENAME_PAYPAL,'action=itp&mode=advanced'); ?>"><!-- Advanced --><?php echo PAYPAL_IPN_TEST_MODE_ADVANCED;?></a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="openWindow('<?php echo tep_href_link('paypal.php','action=itp-help'); ?>');"><!-- Help with this page --><?php echo PAYPAL_IPN_TEST_MODE_HELP_WITH_THIS_PAGE;?></a>&nbsp;<a href="#" onclick="openWindow('<?php echo tep_href_link('paypal.php','action=itp-help'); ?>');"><img src="<?php echo $page->imagePath('help.gif')?>" border="0" hspace="0" align="top"></a></td>
  </tr>
  <tr>
    <td>
      <table border="0" cellspacing="0" cellpadding="2" class="ppheaderborder" width="100%">
        <tr>
          <td align="center">
            <table border="0" cellspacing="0" cellpadding="3" class="testpanelinfo">
              <tr>
                <td class="pptextbold" nowrap="nowrap"><!-- Primary PayPal Email Address --><?php echo PAYPAL_IPN_TEST_MODE_PRIMARY_PAYPAL_EMAIL_ADDRESS;?></td>
                <td class="pptextbold" nowrap="nowrap"><!-- Business ID --><?php echo PAYPAL_IPN_TEST_MODE_BUSINESS_ID;?></td>
                <td class="pptextbold" nowrap="nowrap"><!-- Debug Email Address --><?php echo PAYPAL_IPN_TEST_MODE_DEBUG_EMAIL_ADDRESS;?></td>
              </tr>
              <tr>
                <td nowrap="nowrap"><?php echo MODULE_PAYMENT_PAYPAL_ID; ?></td>
                <td nowrap="nowrap"><?php echo MODULE_PAYMENT_PAYPAL_BUSINESS_ID; ?></td>
                <td nowrap="nowrap"><?php echo MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL; ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><br class="h10"></td>
  </tr>
  <tr valign="top">
    <td>
      <table border="0" cellspacing="0" cellpadding="5" class="testpanel">
        <tr valign="top">
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap="nowrap"><!-- First Name --><?php echo PAYPAL_IPN_TEST_MODE_FIRST_NAME;?></td><td nowrap="nowrap"><input type="text" name="first_name" value="John"></td></tr>
              <tr><td nowrap="nowrap"><?php echo PAYPAL_IPN_TEST_MODE_LAST_NAME;?><!-- Last Name --></td><td nowrap="nowrap"><input type="text" name="last_name" value="Doe"></td></tr>
              <tr><td nowrap="nowrap"><?php echo PAYPAL_IPN_TEST_MODE_BUSINESS_NAME;?><!-- Business Name --></td><td nowrap="nowrap"><input type="text" name="payer_business_name" value="ACME Inc."></td></tr>
              <tr><td nowrap="nowrap"><?php echo PAYPAL_IPN_TEST_MODE_EMAIL_ADDRESS;?><!-- Email Address --></td><td nowrap="nowrap"><input type="text" name="payer_email" value="root@localhost"></td></tr>
              <tr><td nowrap="nowrap"><?php echo PAYPAL_IPN_TEST_MODE_PAYER_ID;?><!-- Payer ID --></td><td nowrap="nowrap"><input type="text" name="payer_id" value="PAYERID000000"></td></tr>
              <tr><td nowrap="nowrap"><?php echo PAYPAL_IPN_TEST_MODE_PAYER_STATUS;?><!-- Payer Status --></td><td nowrap="nowrap" align="right"><select name="payer_status"><option value="verified"><?php echo PAYPAL_IPN_TEST_MODE_VERIFIED;?><!-- verified --></option><option value="unverified"><?php echo PAYPAL_IPN_TEST_MODE_UNVERIFIED;?><!-- unverified --></option></select></td></tr>
              <tr><td nowrap="nowrap"><?php echo PAYPAL_IPN_TEST_MODE_INVOICE;?><!-- Invoice --></td><td nowrap="nowrap"><input type="text" name="invoice" value=""></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr valign="top"><td nowrap="nowrap"><!-- Address Name --><?php echo PAYPAL_IPN_TEST_MODE_ADDRESS_NAME;?></td><td nowrap="nowrap"><input type="text" name="address_name" value="John Doe"></td></tr>
              <tr><td nowrap="nowrap"><!-- Address Street --><?php echo PAYPAL_IPN_TEST_MODE_ADDRESS_STREET;?></td><td nowrap="nowrap"><input type="text" name="address_street" value="1 Way Street"></td></tr>
              <tr><td nowrap="nowrap"><!-- Address City --><?php echo PAYPAL_IPN_TEST_MODE_ADDRESS_CITY;?></td><td><input type="text" name="address_city" value="NeverNever"></td></tr>
              <tr><td nowrap="nowrap"><!-- Address State --><?php echo PAYPAL_IPN_TEST_MODE_ADDRESS_STATE;?></td><td nowrap="nowrap"><input type="text" name="address_state" value="CA"></td></tr>
              <tr><td nowrap="nowrap"><!-- Address Zip --><?php echo PAYPAL_IPN_TEST_MODE_ADDRESS_ZIP;?></td><td><input type="text" name="address_zip" value="12345"></td></tr>
              <tr><td nowrap="nowrap"><!-- Address Country --><?php echo PAYPAL_IPN_TEST_MODE_ADDRESS_COUNTRY;?></td><td nowrap="nowrap"><input type="text" name="address_country" value="United States"></td></tr>
              <tr><td nowrap="nowrap"><!-- Address Status --><?php echo PAYPAL_IPN_TEST_MODE_ADDRESS_STATUS;?></td><td nowrap="nowrap" align="right"><select name="address_status"><option value="confirmed"><!-- confirmed --><?php echo PAYPAL_IPN_TEST_MODE_CONFIRMED;?></option><option value="unconfirmed"><!-- unconfirmed --><?php echo PAYPAL_IPN_TEST_MODE_UNCONFIRMED;?></option></select></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap="nowrap"><!-- Payment Type --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_TYPE;?></td><td nowrap="nowrap" align="right"><select name="payment_type"><option value="instant"><!-- instant --><?php echo PAYPAL_IPN_TEST_MODE_INSTANT;?></option><option value="echeck"><!-- echeck --><?php echo PAYPAL_IPN_TEST_MODE_ECHECK;?></option></select></td></tr>
              <tr><td nowrap="nowrap"><!-- Transaction Type --><?php echo PAYPAL_IPN_TEST_MODE_TRANSACTION_TYPE;?></td><td nowrap="nowrap" align="right"><select name="txn_type"><option value=""><!-- --select-- --><?php echo PAYPAL_IPN_TEST_MODE_SELECT;?></option><option value="cart"><!-- cart --><?php echo PAYPAL_IPN_TEST_MODE_CART;?></option><option value="web_accept"><!-- web_accept --><?php echo PAYPAL_IPN_TEST_MODE_WEB_ACCEPT;?></option><option value="send_money"><!-- send_money --><?php echo PAYPAL_IPN_TEST_MODE_SEND_MONEY;?></option></select></td></tr>
              <tr><td nowrap="nowrap"><!-- Custom --><?php echo PAYPAL_IPN_TEST_MODE_CUSTOM;?></td><td nowrap="nowrap"><input type="text" name="custom" value="1" maxlength="32"></td></tr>
              <tr><td nowrap="nowrap"><!-- Transaction ID --><?php echo PAYPAL_IPN_TEST_MODE_TRANSACTION_ID;?></td><td nowrap="nowrap"><input type="text" name="txn_id" value="PAYPAL00000000000" maxlength="17"></td></tr>
              <tr><td nowrap="nowrap"><!-- Parent Transaction ID --><?php echo PAYPAL_IPN_TEST_MODE_PARENT_TRANSACTION_ID;?></td><td nowrap="nowrap"><input type="text" name="parent_txn_id" value="" maxlength="17"></td></tr>
              <tr><td nowrap="nowrap"><!-- No. Cart Items --><?php echo PAYPAL_IPN_TEST_MODE_NO_CART_ITEMS;?></td><td><input type="text" name="num_cart_items" value="1"></td></tr>
              <tr><td nowrap="nowrap"><!-- Notify Version --><?php echo PAYPAL_IPN_TEST_MODE_NOTIFY_VERSION;?></td><td nowrap="nowrap" align="right"><select name="notify_version"><option value="1.6" selected><!-- 1.6 --><?php echo PAYPAL_IPN_TEST_MODE_NOTIFY_VERSION_VALUE;?></option></select></td></tr>
              <tr><td nowrap="nowrap"><!-- Memo --><?php echo PAYPAL_IPN_TEST_MODE_MEMO;?></td><td nowrap="nowrap"><input type="text" name="memo" value="PAYPAL_SHOPPING_CART_IPN TEST"></td></tr>
            </table>
          </td>
        </tr>
        <tr valign="top">
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap="nowrap"><!-- MC Currency --><?php echo PAYPAL_IPN_TEST_MODE_MC_CURRENCY;?></td><td align="right"><select name="mc_currency"><option value="USD"><!-- USD --><?php echo PAYPAL_IPN_TEST_MODE_USD;?></option><option value="GBP"><!-- GBP --><?php echo PAYPAL_IPN_TEST_MODE_GBP;?></option><option value="EUR"><!-- EUR --><?php echo PAYPAL_IPN_TEST_MODE_EUR;?></option><option value="CAD"><!-- CAD --><?php echo PAYPAL_IPN_TEST_MODE_CAD;?></option><option value="JPY"><!-- JPY --><?php echo PAYPAL_IPN_TEST_MODE_JPY;?></option></select></td></tr>
              <tr><td nowrap="nowrap"><!-- MC Gross --><?php echo PAYPAL_IPN_TEST_MODE_MC_GROSS;?></td><td align="right"><input type="text" name="mc_gross" value="0.01"></td></tr>
              <tr><td nowrap="nowrap"><!-- MC Fee --><?php echo PAYPAL_IPN_TEST_MODE_MC_FEE;?></td><td align="right"><input type="text" name="mc_fee" value="0.01"></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap="nowrap"><!-- Settle Amount --><?php echo PAYPAL_IPN_TEST_MODE_SETTLE_AMOUNT;?></td><td align="right"><input type="text" name="settle_amount" value="0.00"></td></tr>
              <tr><td nowrap="nowrap"><!-- Settle Currency --><?php echo PAYPAL_IPN_TEST_MODE_SETTLE_AMOUNT;?></td><td align="right"><select name="settle_currency"><option value=""></option><option value="USD"><!-- USD --><?php echo PAYPAL_IPN_TEST_MODE_USD;?></option><option value="GBP"><!-- GBP --><?php echo PAYPAL_IPN_TEST_MODE_GBP;?></option><option value="EUR"><!-- EUR --><?php echo PAYPAL_IPN_TEST_MODE_EUR;?></option><option value="CAD"><!-- CAD --><?php echo PAYPAL_IPN_TEST_MODE_CAD;?></option><option value="JPY"><!-- JPY --><?php echo PAYPAL_IPN_TEST_MODE_JPY;?></option></select></td></tr>
              <tr><td nowrap="nowrap"><!-- Exchange Rate --><?php echo PAYPAL_IPN_TEST_MODE_EXCHANGE_RATE;?></td><td align="right"><input type="text" name="exchange_rate" value="0.00"></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap="nowrap"><!-- Payment Status --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS;?></td><td align="right"><select name="payment_status"><option value="Completed"><!-- Completed --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_COMPLETED;?></option><option value="Pending"><!-- Pending --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_PENDING;?></option><option value="Failed"><!-- Failed --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_FAILED;?></option><option value="Denied"><!-- Denied --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_DENIED;?></option><option value="Refunded"><!-- Refunded --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_REFUNDED;?></option><option value="Reversed"><!-- Reversed --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_REVERSED;?></option><option value="Canceled_Reversal"><!-- Canceled_Reversal --><?php echo PAYPAL_IPN_TEST_MODE_PAYMENT_STATUS_CANCELED_REVERSAL;?></option></select></td></tr>
              <tr><td nowrap="nowrap"><!-- Pending Reason --><?php echo PAYPAL_IPN_TEST_MODE_PENDING_REASON;?></td><td align="right"><select name="pending_reason"><option value=""></option><option value="echeck"><!-- echeck --><?php echo PAYPAL_IPN_TEST_MODE_ECHECK;?></option><option value="multi_currency"><!-- multi_currency --><?php echo PAYPAL_IPN_TEST_MODE_MULTI_CURRENCY;?></option><option value="intl"><!-- intl --><?php echo PAYPAL_IPN_TEST_MODE_INTL;?></option><option value="verify"><!-- verify --><?php echo PAYPAL_IPN_TEST_MODE_VERIFY;?></option><option value="address"><!-- address --><?php echo PAYPAL_IPN_TEST_MODE_ADDRESS;?></option><option value="upgrade"><!-- upgrade --><?php echo PAYPAL_IPN_TEST_MODE_UPGRADE;?></option><option value="unilateral"><!-- unilateral --><?php echo PAYPAL_IPN_TEST_MODE_UNILATERAL;?></option><option value="other"><!-- other --><?php echo PAYPAL_IPN_TEST_MODE_OTHER;?></option></select></td></tr>
              <tr><td nowrap="nowrap"><!-- Reason Code --><?php echo PAYPAL_IPN_TEST_MODE_REASON_CODE;?></td><td align="right"><select name="reason_code"><option value=""></option><option value="chargeback"><!-- chargeback --><?php echo PAYPAL_IPN_TEST_MODE_CHARGEBACK;?></option><option value="guarantee"><!-- guarantee --><?php echo PAYPAL_IPN_TEST_MODE_GUARANTEE;?></option><option value="buyer_complaint"><!-- buyer_complaint --><?php echo PAYPAL_IPN_TEST_MODE_BUYER_COMPLAINT;?></option><option value="refund"><!-- refund --><?php echo PAYPAL_IPN_TEST_MODE_REFUND;?></option><option value="other"><!-- other --><?php echo PAYPAL_IPN_TEST_MODE_OTHER;?></option></select></td></tr>
            </table>
          </td>
        </tr>
<?php if (isset($_GET['mode']) && $_GET='Advanced') { ?>
        <tr valign="top">
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap="nowrap"><!-- Tax --><?php echo PAYPAL_IPN_TEST_MODE_TAX;?></td><td align="right"><input type="text" name="tax" value="0.00"></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap="nowrap"><!-- For Auction --><?php echo PAYPAL_IPN_TEST_MODE_FOR_AUCTION;?></td><td align="right"><select name="for_auction"><option value=""><!-- No --><?php echo PAYPAL_IPN_TEST_MODE_NO;?></option><option value="true"><!-- Yes --><?php echo PAYPAL_IPN_TEST_MODE_YES;?></option></select></td></tr>
              <tr><td nowrap="nowrap"><!-- Auction Buyer ID --><?php echo PAYPAL_IPN_TEST_MODE_AUCTION_BUYER_ID;?></td><td align="right"><input type="text" name="auction_buyer_id" value=""></td></tr>
              <tr><td nowrap="nowrap"><!-- Auction Closing Date --><?php echo PAYPAL_IPN_TEST_MODE_AUCTION_CLOSING_DATE;?></td><td align="right"><input type="text" name="auction_closing_date" value="<?php echo date("H:i:s M d, Y T"); ?>"></td></tr>
              <tr><td nowrap="nowrap"><!-- Auction Multi-Item --><?php echo PAYPAL_IPN_TEST_MODE_AUCTION_MULTI_ITEM;?></td><td align="right"><input type="text" name="auction_multi_item" value=""></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap="nowrap"><!-- Item Name --><?php echo PAYPAL_IPN_TEST_MODE_ITEM_NAME;?></td><td align="right"><input type="text" name="item_name" value=""></td></tr>
              <tr><td nowrap="nowrap"><!-- Item Number --><?php echo PAYPAL_IPN_TEST_MODE_ITEM_NUMBER;?></td><td align="right"><input type="text" name="item_number" value=""></td></tr>
              <tr><td nowrap="nowrap"><!-- Quantity --><?php echo PAYPAL_IPN_TEST_MODE_QUANTITY;?></td><td align="right"><input type="text" name="quantity" value=""></td></tr>
            </table>
          </td>
        </tr>
<?php } ?>
      </table>
    </td>
  </tr>
  <tr><td><hr class="solid"/></td></tr>
  <tr><td class="buttontd"><input class="ppbuttonsmall" type="submit" name="submit" value="Test IPN"></td></tr>
</table>
<form>
