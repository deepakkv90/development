<?php
/*
  $Id: TransactionDetails.class.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

require_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/database_tables.inc.php');

class PayPal_TransactionDetails {
  // class constructor
  function PayPal_TransactionDetails($tablename,$paypal_id = '') {
    $this->setMySQLPaymentTable($tablename);
    if (!empty($paypal_id)) {
      $this->simpleQuery($paypal_id);
    }
  }

  function setMySQLPaymentTable($tablename) {
    $this->paymentTableName = tep_db_prepare_input($tablename);
  }

  function date($raw_date) {
    $cal = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May',  '06' => 'Jun',  '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
    list($date, $time) = explode(" ",$raw_date);
    list($year, $month, $day) = explode("-",$date);
    return $cal[$month] . '. ' . $day . ', ' . $year;
  }

  function time($raw_date) {
    list($date, $time) = explode(" ", $raw_date);
    return $time;
  }

  function getSQLDataElements(&$srcArray,$varNames) {
    $nVars = count($varNames); $array = array();
    reset($srcArray); for ($i=0; $i<$nVars; $i++) $array[$varNames[$i]] = trim(stripslashes($srcArray[$varNames[$i]]));
    return $array;
  }


  function simpleQuery($paypal_id) {
    $info = array('payment_status','txn_id','date_added','payment_date');
    $txn = array('mc_currency','mc_gross','mc_fee');
    $ipn_query = tep_db_query("select " . implode(',',array_merge($info,$txn)) . " from " . tep_db_input($this->paymentTableName) . " where paypal_id = '" . (int)$paypal_id . "'");
    if (tep_db_num_rows($ipn_query)) {
      $ipn = tep_db_fetch_array($ipn_query);
      $this->info = $this->getSQLDataElements($ipn,$info);
      $this->txn = $this->getSQLDataElements($ipn,$txn);
    }
  }

  function query($txn_id) {
    $transaction_id = tep_db_prepare_input($txn_id);
    $info = array('txn_type','reason_code','payment_type','payment_status','pending_reason','invoice','payment_date','payment_time_zone','business','receiver_email','receiver_id','txn_id','parent_txn_id','notify_version','last_modified','date_added','for_auction','auction_closing_date');
    $txn = array('num_cart_items','mc_currency','mc_gross','mc_fee','payment_gross','payment_fee','settle_amount','settle_currency','exchange_rate');
    $customer = array('first_name','last_name','payer_business_name','address_name','address_street','address_city','address_state','address_zip','address_country','address_status','payer_email','payer_id','auction_buyer_id','payer_status','memo');
    $ipn_query = tep_db_query("select " . implode(',',array_merge($info,$txn,$customer)) . " from " . tep_db_input($this->paymentTableName) . " where txn_id = '" . tep_db_input($transaction_id) . "'");
    if (tep_db_num_rows($ipn_query)) {
      $ipn = tep_db_fetch_array($ipn_query);
      $this->info = $this->getSQLDataElements($ipn,$info);
      $this->txn = $this->getSQLDataElements($ipn,$txn);
      $this->customer = $this->getSQLDataElements($ipn,$customer);
    }
  }

  function isPending() {
    return ($this->info['payment_status'] === 'Pending');
  }

  function isReversal() {
    //Canceled_Reversal ommitted on purpose!
    return in_array($this->info['payment_status'],array('Refunded','Reversed'));
  }

  function format($amount,$currency) {
    global $currencies;
    if ($amount < 0) {
      $tmpAmount = $amount*(-1);
      $prefix = '-';
    } else {
      $tmpAmount = $amount;
      $prefix = '';
    }
    return $prefix.$currencies->format($tmpAmount, false, $currency);
  }

  function displayPaymentType() {
    $array = array('instant' => 'Instant', 'echeck' => 'eCheck');
    return $array[$this->info['payment_type']];
  }

  function transactionSignature($order_id) {
    $txn_signature_query = tep_db_query("select txn_signature from " . TABLE_ORDERS_SESSION_INFO . " where orders_id = '" . (int)$order_id . "' limit 1");
    if (tep_db_num_rows($txn_signature_query)) {
      $txn_signature = tep_db_fetch_array($txn_signature_query);
      return $txn_signature['txn_signature'];
    }
  }

  function digest() {
    return strrev(md5(md5(strrev(md5(MODULE_PAYMENT_PAYPAL_IPN_DIGEST_KEY)))));
  }
}//end class
?>
