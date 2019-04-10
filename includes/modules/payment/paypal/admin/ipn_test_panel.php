<?php
/*
  $Id: ipn_test_panel.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/classes/ipn.class.php');
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<meta name="copyright" content="2004">
<meta name="author" content="developer&#064;devosc.com">
<style type='text/css'>
body {background-color:#FFFFFF;}
body, td, th {font-family: sans-serif; font-size: 10px;}
.p {margin-top:0px;padding-top:0px;}
.box, .boxEmail {border:1px solid black; width:100%;}
table.box td {color: #003366; }
input { border:1px solid #003366;}
</style>
</head>
<body onLoad="javascript:document.ipn.custom.select();">
<form name="ipn" method="POST" action="<?php echo DIR_WS_CATALOG.'ipn.php'; ?>">
<input type="hidden" name="business" value="<?php echo MODULE_PAYMENT_PAYPAL_BUSINESS_ID; ?>"/>
<input type="hidden" name="receiver_email" value="<?php echo MODULE_PAYMENT_PAYPAL_ID; ?>"/>
<input type="hidden" name="verify_sign" value="PAYPAL_SHOPPING_CART_IPN-TEST_TRANSACTION-00000000000000"/>
<input type="hidden" name="payment_date" value="<?php echo date("G:i:s M j, Y T"); ?>">
<input type="hidden" name="digest_key" value="<?php echo ipn::digest_key(); ?>">
<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
      <tr valign="middle">
        <td align="center">
<table border="0" width="780" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="0" class="box">
          <tr>
            <td align="center"><img border="0" src="<?php echo DIR_WS_CATALOG_LANGUAGES . '../modules/payment/paypal/images/loaded_header_logo.gif'; ?>" alt="CRE Loaded"  title=" osCommerce " /></td>
          </tr>
          <tr>
            <td class="pageHeading" style="color:green" align="center">PayPal_Shopping_Cart_IPN</td>
          </tr>
          <tr>
            <td class="pageHeading" style="color:blue; text-align:center; padding-top:5px;">IPN Test Panel</td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php if (MODULE_PAYMENT_PAYPAL_IPN_TEST_MODE == 'Off') { ?>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2" class="boxEmail" style="background-color: red;">
          <tr>
            <td class="pageHeading" align="center" style="color: #FFFFFF;">TEST MODE MUST BE SWITCHED ON!</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php } ?>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2" class="boxEmail">
          <tr>
            <td align="center"><table border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td class="smallText" nowrap="nowrap"><b>Primary PayPal Email Address</b></td>
                <td class="smallText" style="padding-left:15px;" nowrap="nowrap"><b>Business ID</b></td>
                <td class="smallText" style="padding-left:15px;" nowrap="nowrap"><b>Debug Email Address</b></td>
              </tr>
              <tr>
                <td class="smallText" nowrap="nowrap"><?php echo MODULE_PAYMENT_PAYPAL_ID; ?></td>
                <td class="smallText" style="padding-left:15px;" nowrap="nowrap"><?php echo MODULE_PAYMENT_PAYPAL_BUSINESS_ID; ?></td>
                <td class="smallText" style="padding-left:15px;" nowrap="nowrap"><?php echo MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2" class="box">
              <tr valign="top">
                <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" nowrap="nowrap"><b>First Name</b></td><td class="main" nowrap="nowrap"><input type="text" name="first_name" value="John"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Last Name</b></td><td class="main" nowrap="nowrap"><input type="text" name="last_name" value="Doe"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_BUSINESS_NAME; ?></b></td><td class="main" nowrap="nowrap"><input type="text" name="payer_business_name" value="ACME Inc."></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td><td class="main" nowrap="nowrap"><input type="text" name="payer_email" value="root@localhost"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_PAYER_ID; ?></b></td><td class="main" nowrap="nowrap"><input type="text" name="payer_id" value="PAYERID000000"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_PAYER_STATUS; ?></b></td><td class="main" nowrap="nowrap" align="right"><select name="payer_status"><option value="verified">verified</option><option value="unverified">unverified</option></select></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_INVOICE; ?></b></td><td class="main" nowrap="nowrap"><input type="text" name="invoice" value=""></td>
                      </tr>
                      </table>
                </td>
                <td style="padding-left:5px;">
                      <table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr valign="top">
                        <td class="main" nowrap="nowrap"><b>Address Name</b></td><td class="main" nowrap="nowrap"><input type="text" name="address_name" value="John Doe"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Address Street</b></td><td class="main" nowrap="nowrap"><input type="text" name="address_street" value="1 Way Street"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Address City</b></td><td class="main"><input type="text" name="address_city" value="NeverNever"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Address State</b></td><td class="main" nowrap="nowrap"><input type="text" name="address_state" value="CA"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Address Zip</b></td><td class="main"><input type="text" name="address_zip" value="12345"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Address Country</b></td><td class="main" nowrap="nowrap"><input type="text" name="address_country" value="United States"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Address Status</b></td><td class="main" nowrap="nowrap" align="right"><select name="address_status"><option value="confirmed">confirmed</option><option value="unconfirmed">unconfirmed</option></select></td>
                      </tr>
                      </table>
                </td>
                <td style="padding-left:5px;">
                      <table width="100%" border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_PAYMENT_TYPE; ?></b></td><td class="main" nowrap="nowrap" align="right"><select name="payment_type"><option value="instant">instant</option><option value="echeck">echeck</option></select></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Transaction Type</b></td><td class="main" nowrap="nowrap" align="right"><select name="txn_type"><option value="cart">cart</option><option value="web_accept">web_accept</option><option value="send_money">send_money</option></select></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Custom</b></td><td class="main" nowrap="nowrap"><input type="text" name="custom" value="1" maxlength="32"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_PAYPAL_IPN_TXN; ?></b></td><td class="main" nowrap="nowrap"><input type="text" name="txn_id" value="PAYPAL00000000000" maxlength="17"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b><?php echo ENTRY_CART_ITEMS; ?></b></td><td class="main"><input type="text" name="num_cart_items" value="1"></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Notify Version</b></td><td class="main" nowrap="nowrap" align="right"><select name="notify_version"><option value="1.6" selected>1.6</option></select></td>
                      </tr>
                      <tr>
                        <td class="main" nowrap="nowrap"><b>Memo</b></td><td class="main" nowrap="nowrap"><input type="text" name="memo" value="PAYPAL_SHOPPING_CART_IPN TEST"></td>
                      </tr>
                      </table>
                </td>
              </tr>
              <tr>
                <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="main" nowrap="nowrap"><b><?php echo ENTRY_MC_CURRENCY; ?></b></td><td class="main" align="right"><select name="mc_currency"><option value="USD">USD</option><option value="GBP">GBP</option><option value="EUR">EUR</option><option value="CAD">CAD</option><option value="JPY">JPY</option></select></td>
                          </tr>
                          <tr>
                            <td class="main" nowrap="nowrap"><b><?php echo ENTRY_MC_GROSS; ?></b></td><td class="main" align="right"><input type="text" name="mc_gross" value="0.01"></td>
                          </tr>
                          <tr>
                            <td class="main" nowrap="nowrap"><b><?php echo ENTRY_MC_FEE; ?></b></td><td class="main" align="right"><input type="text" name="mc_fee" value="0.01"></td>
                          </tr>
                        </table>
                </td>
                <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="main" nowrap="nowrap"><b><?php echo ENTRY_SETTLE_AMOUNT; ?></b></td><td class="main" align="right"><input type="text" name="settle_amount" value="0.00"></td>
                          </tr>
                          <tr>
                            <td class="main" nowrap="nowrap"><b><?php echo ENTRY_SETTLE_CURRENCY; ?></b></td><td class="main" align="right"><select name="settle_currency"><option value=""></option><option value="USD">USD</option><option value="GBP">GBP</option><option value="EUR">EUR</option><option value="CAD">CAD</option><option value="JPY">JPY</option></select></td>
                          </tr>
                          <tr>
                            <td class="main" nowrap="nowrap"><b><?php echo ENTRY_EXCHANGE_RATE; ?></b></td><td class="main" align="right"><input type="text" name="exchange_rate" value="0.00"></td>
                          </tr>
                        </table>
                </td>
                <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                          <tr>
                            <td class="main" nowrap="nowrap"><b>Payment Status</b></td><td class="main" align="right"><select name="payment_status"><option value="Completed">Completed</option><option value="Pending">Pending</option><option value="Failed">Failed</option><option value="Denied">Denied</option><option value="Refunded">Refunded</option><option value="Reversed">Reversed</option><option value="Canceled_Reversal">Canceled_Reversal</option></select></td>
                          </tr>
                          <tr>
                            <td class="main" nowrap="nowrap"><b>Pending Reason</b></td><td class="main" align="right"><select name="pending_reason"><option value=""></option><option value="echeck">echeck</option><option value="multi_currency">multi_currency</option><option value="intl">intl</option><option value="verify">verify</option><option value="address">address</option><option value="upgrade">upgrade</option><option value="unilateral">unilateral</option><option value="other">other</option></select></td>
                          </tr>
                          <tr>
                            <td class="main" nowrap="nowrap"><b>Reason Code</b></td><td class="main" align="right"><select name="reason_code"><option value=""></option><option value="chargeback">chargeback</option><option value="guarantee">guarantee</option><option value="buyer_complaint">buyer_complaint</option><option value="refund">refund</option><option value="other">other</option></select></td>
                          </tr>
                        </table>
                </td>
              </tr>
              </table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="5" class="box">
            <tr valign="top">
              <td align="center">
                <input type="submit" name="submit" value="Test IPN">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2" class="boxEmail" style="">
          <tr>
            <td class="pageHeading" align="center" style="color: #FFFFFF; background-color: #003366;">Instructions</td>
          </tr>
          <tr>
            <td class="main" style="padding:10 0 10 0;">
<ul>
<li>Begin to check out as a customer via the store, stop when you get to the PayPal site</li>
<li>Go into the store admin orders section and find the last order (just created)</li>
<li>Copy and paste the <b>Transaction Signature</b> into the above <b>Custom</b> field and into the <b>Transaction ID</b> field</li>
<li>If the <b>Cart Test</b> is on, then make sure that the above <b>MC Gross</b> amount is the same as the order total and that the <b>MC&nbsp;Currency</b> field is set to the same currency as the order.</li>
<li>Submit the Test IPN</li>
<li>Now check the admin order status</li>
</ul>
<p align="center"><b style="color:red;">If you're testing Pending payments etc, then remember to use the same Transction ID.<br/></b></p>
</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2" class="boxEmail" style="border:none;">
          <tr>
            <td class="main" style="padding:10 0 10 0;"><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></td>
          </tr>
        </table></td>
      </tr>
    </table>
</td>
</tr>
</table>
<form>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
