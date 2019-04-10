<?php
/*
  $Id: cresecure_payment.php,v 1.0 2009/04/09 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
include('includes/application_top.php');

$response_code = (isset($_GET['code'])) ? $_GET['code'] : '';
$response_msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
$response_error = (isset($_GET['error'])) ? $_GET['error'] : '';
$color = ($response_error == true) ? '#FF0000' : '#008000';
if ($response_code == '000') {  // success
  // update transaction_log
  $response_token = (isset($_GET['token'])) ? $_GET['token'] : '';
  $response_transID = (isset($_GET['transID'])) ? $_GET['transID'] : '';
  $response_orderID = (isset($_GET['order_id'])) ? (int)$_GET['order_id'] : '';
  $sql_data_array = array('token' => $response_token, 
                          'transaction_id' => $response_transID,
                          'order_id' => $response_orderID,
                          'created_date' => 'now()');
  tep_db_perform('transaction_log', $sql_data_array);
  // update orders table
  $response_name = (isset($_GET['name'])) ? $_GET['name'] : '';
  $response_type = (isset($_GET['type'])) ? $_GET['type'] : '';
  $response_mPAN = (isset($_GET['mPAN'])) ? $_GET['mPAN'] : '';
  $response_exp = (isset($_GET['exp'])) ? $_GET['exp'] : '';  
  $sql_data_array = array('cc_owner' => $response_name,
                          'cc_type' => $response_type,
                          'cc_number' => $response_mPAN,
                          'cc_expires' => $response_exp,
                          );
  tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . $response_orderID);
  // update orders status
  tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . (MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID > 0 ? (int)MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID : (int)DEFAULT_ORDERS_STATUS_ID) . "', date_purchased = now(), last_modified = now() where orders_id = '" . $response_orderID . "'");
  // update order status history
  $comments = (defined('MODULE_PAYMENT_CRESECURE_TEST_MODE') && MODULE_PAYMENT_CRESECURE_TEST_MODE == 'True') ? ':TESTMODE: Processed via Admin User: ' . $_SESSION['login_firstname'] . '[' . $_SESSION['login_id'] . ']' : 'Processed via Admin User: ' . $_SESSION['login_firstname'] . '[' . $_SESSION['login_id'] . ']'; 
  $sql_data_array = array('orders_id' => $response_orderID,
                          'orders_status_id' => (MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID > 0 ? (int)MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID : (int)DEFAULT_ORDERS_STATUS_ID),
                          'date_added' => 'now()',
                          'customer_notified' => (SEND_EMAILS == 'true') ? '1' : '0',
                          'comments' => $comments);
  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array); 
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
    <tr><td><?php echo tep_draw_separator('pixel_trans', '1', '30'); ?></td></tr>
    <tr><td colspan="2" style="padding-left:2px"><img border="0" src="images/cresecure_logo.jpg"></td></tr>
    <tr><td><?php echo tep_draw_separator('pixel_trans', '1', '50'); ?></td></tr>  
    <tr>
      <td align="center" style="color:<?php echo $color; ?>;"><h3><?php echo $response_msg; ?></h3></td>
    </tr>
    <tr><td><?php echo tep_draw_separator('pixel_trans', '1', '20'); ?></td></tr>
    <tr>
      <td align="right">
        <a href="javascript:refreshParent();"><?php echo tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></a>&nbsp;&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</body>
</html>
<script language="JavaScript">
<!--
function refreshParent() {
  window.opener.location.href = window.opener.location.href;
  if (window.opener.progressWindow) {
    window.opener.progressWindow.close()
  }
  window.close();
}
//-->
</script>