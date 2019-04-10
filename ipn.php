<?php
/*
  $Id: ipn.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

/* Send paypal post values to NBI IPN */
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	$url = "http://namebadgesinternational.com.au/manage_paypal_ipn.php";
	$data = $_POST;
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
	curl_setopt($ch, CURLOPT_URL,$url);	
	$result = curl_exec ($ch);
	curl_close ($ch);
	//var_dump($result);
/* Send paypal post values to NBI IPN */

  function debugWriteFile($str,$mode="a") {
    $fp = @fopen("ipn.txt",$mode);  @flock($fp, LOCK_EX); @fwrite($fp,$str); @flock($fp, LOCK_UN); @fclose($fp);
  }
   $postString = ''; foreach($_POST as $key => $val) $postString .= $key.' = '.$val."\n";
  if($postString != '') {
		debugWriteFile($postString,"w+");
  }

  require_once('includes/modules/payment/paypal/application_top.inc.php');
  require_once(DIR_WS_MODULES . 'payment/paypal/classes/IPN/IPN.class.php');
  require_once(DIR_WS_MODULES . 'payment/paypal/classes/Debug/Debug.class.php');
  require_once(DIR_WS_MODULES . 'payment/paypal/functions/general.func.php');

  paypal_include_lng(DIR_WS_MODULES . 'payment/paypal/languages/', $language, 'ipn.lng.php');

  $debug = new PayPal_Debug(MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL,MODULE_PAYMENT_PAYPAL_IPN_DEBUG);
  $ipn = new PayPal_IPN($_POST);
  $ipn->setTestMode(MODULE_PAYMENT_PAYPAL_IPN_TEST_MODE);  
  
  //Hided Sep 08, 2010 
  //unset($_POST);
 
  //post back to PayPal system to validate
  //if(!$ipn->authenticate(MODULE_PAYMENT_PAYPAL_DOMAIN) && $ipn->testMode('Off')) $ipn->dienice('500');

  //Check both the receiver_email and business ID fields match
  //if (!$ipn->validateReceiverEmail(MODULE_PAYMENT_PAYPAL_ID,MODULE_PAYMENT_PAYPAL_BUSINESS_ID)) $ipn->dienice('500');

  if($ipn->uniqueTxnID() && $ipn->isReversal()) {
  
    //parent_txn_id is the txn_id of the original transaction
    $ipn_query = tep_db_query("select paypal_id from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($ipn->key['parent_txn_id']) . "'");
    if(tep_db_num_rows($ipn_query)) {
      $ipn_query_result = tep_db_fetch_array($ipn_query);
      $ipn->insert();
      // update the order's status
      switch ($ipn->reversalType()) {
        case 'Canceled_Reversal':
          $order_status = MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID;
          break;
        case 'Reversed':
        case 'Refunded':
          $order_status = MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID;
          break;
      }
      $ipn->updateOrderStatus($ipn_query_result['paypal_id'],$order_status);
    }
	  
  } elseif ($ipn->isCartPayment() && tep_not_null($PayPal_osC_Order->orderID)) {
  
     //$currency = $PayPal_osC_Order->currency;

    //actually not essential since 'orders_status_name' is not required
    $languages_id = $PayPal_osC_Order->languageID;

    include(DIR_WS_CLASSES . 'order.php');
    $order = new order($PayPal_osC_Order->orderID);

  
    //Check that txn_id has not been previously processed
    if ($ipn->uniqueTxnID()) { //Payment is either Completed, Pending or Failed
      $ipn->insert();

      tep_db_query("update " . TABLE_ORDERS . " set payment_id = '" . (int)$ipn->ID() . "' where orders_id = '" . (int)$PayPal_osC_Order->orderID . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$order->customer['id'] . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$order->customer['id'] . "'");

      switch ($ipn->paymentStatus()) {
        case 'Completed':
          if ($ipn->validPayment($PayPal_osC_Order->payment_amount,$PayPal_osC_Order->payment_currency)) {
            include(DIR_WS_MODULES . 'payment/paypal/catalog/checkout_update.inc.php');
          } else {
            $ipn->updateOrderStatus($ipn->ID(),MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID);
          }
          break;
        case 'Failed':
          $ipn->updateOrderStatus($ipn->ID(),MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID);
          break;
        case 'Pending':
          //Assumed to do nothing since the order is initially in a Pending ORDER Status
          break;
      }//end switch

    } else { // not a unique transaction => Pending Payment

      $ipn_query = tep_db_query("select paypal_id, payment_status, pending_reason from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($ipn->txnID()) . "' limit 0,1");
      //Assumes there is only one previous IPN transaction
      $ipn_query_result = tep_db_fetch_array($ipn_query);
      if ($ipn_query_result['payment_status'] === 'Pending') {
        $ipn->updateStatus($ipn_query_result['paypal_id'],$ipn_query_result['pending_reason']);
        switch ($ipn->paymentStatus()) {
          case 'Completed':
           if ($ipn->validPayment($PayPal_osC_Order->payment_amount,$PayPal_osC_Order->payment_currency)) {
            include(DIR_WS_MODULES . 'payment/paypal/catalog/checkout_update.inc.php');
           } else {
            $ipn->updateOrderStatus($ipn_query_result['paypal_id'],MODULE_PAYMENT_PAYPAL_ORDER_ONHOLD_STATUS_ID);
           }
           break;
          case 'Denied':
            $ipn->updateOrderStatus($ipn_query_result['paypal_id'],MODULE_PAYMENT_PAYPAL_ORDER_CANCELED_STATUS_ID);
            break;
        }//end switch
      }//end if Pending Payment
    }

  } elseif ($ipn->isAuction()) {

    if ($ipn->uniqueTxnID()) $ipn->insert();
    if ($debug->enabled) $debug->add(PAYPAL_AUCTION,sprintf(PAYPAL_AUCTION_MSG));

  } elseif ($ipn->txnType('send_money')) {

    if ($ipn->uniqueTxnID()) $ipn->insert();
    if ($debug->enabled) $debug->add(PAYMENT_SEND_MONEY_DESCRIPTION,sprintf(PAYMENT_SEND_MONEY_DESCRIPTION_MSG,number_format($ipn->key['mc_gross'],2),$ipn->key['mc_currency']));

  } elseif ($debug->enabled && $ipn->testMode('On')) {
    $debug->raiseError(TEST_INCOMPLETE,sprintf(TEST_INCOMPLETE_MSG),true);
  }
  if ($ipn->testMode('On') &&  $ipn->validDigest()) {
    include(DIR_WS_MODULES . 'payment/paypal/classes/Page/Page.class.php');
    $page = new PayPal_Page();
    $page->setBaseDirectory(DIR_WS_MODULES . 'payment/paypal/');
    $page->setBaseURL(DIR_WS_MODULES . 'payment/paypal/');
    $page->includeLanguageFile('admin/languages','english','paypal.lng.php');
    $page->setTitle(HEADING_ITP_RESULTS_TITLE);
    $page->setContentFile(DIR_WS_MODULES . 'payment/paypal/admin/TestPanel/Results.inc.php');
    $page->addCSS($page->baseURL . 'templates/css/general.css');
    $page->addCSS($page->baseURL . 'templates/css/stylesheet.css');
    $page->setTemplate('default');
    include($page->template());
  }
  require(DIR_WS_MODULES . 'payment/paypal/application_bottom.inc.php');
?>
