<?php
/*
  $Id: ipn.class.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License

*/

class ipn {

  var $key,
      $response_string,
      $ipn_id;

  function ipn($post_vars) {
    global $debug;
    $transaction_list = array('web_accept','cart','send_money'); //accepted transactions v1.6
    if (!in_array($post_vars['txn_type'],$transaction_list)) {
      if ($debug->enabled) $debug->add(UNKNOWN_TXN_TYPE,sprintf(UNKNOWN_TXN_TYPE_MSG, $post_vars['txn_type']));
    } else if(strlen($post_vars['txn_id']) == 17) {
      $this->init($post_vars,$custom_list); //Looks like a PayPal transaction
    } else {
      if ($debug->enabled) $debug->add(UNKNOWN_POST,sprintf(UNKNOWN_POST_MSG,$_SERVER['REMOTE_ADDR']));
    }
  }

  function init($post_vars,$custom_list) {
    global $debug;
    $debug_string = '';
    $this->key = array();
    $this->response_string = 'cmd=_notify-validate';
    reset($post_vars);
    foreach ($post_vars as $var => $val) {
      if ($debug->enabled) $debug_string .= $var . '=' . $val .'&';
      $val = tep_db_prepare_input($val);
      if (!strcasecmp($var,'cmd') || !eregi("^[_0-9a-z-]{1,32}$",$var)) {
        unset($var); unset($val);
      } elseif ($var != '') {
        $this->key[$var] = $val;
        $this->response_string .= '&' . $var . '=' . urlencode($val);
      }
    }
    if ($debug->enabled) $debug->init($debug_string, $this->response_string);
    unset($post_vars, $debug_string);
  }

  function authenticate($domain) {
    global $debug;
    $paypal_response = '';
    $curl_flag = function_exists('curl_exec');
    if($curl_flag) {
      $ch = @curl_init();
      @curl_setopt($ch,CURLOPT_URL, "https://$domain/cgi-bin/webscr");
      @curl_setopt($ch,CURLOPT_POST, 1);
      @curl_setopt($ch,CURLOPT_POSTFIELDS, $this->response_string);
      @curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
      @curl_setopt($ch,CURLOPT_HEADER, 0);
      @curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
      @curl_setopt($ch,CURLOPT_TIMEOUT, 60);
      // added support for curl proxy
      if (defined('CURL_PROXY_HOST') && defined('CURL_PROXY_PORT') && CURL_PROXY_HOST != '' && CURL_PROXY_PORT != '') {
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_HOST . ":" . CURL_PROXY_PORT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      }
      if (defined('CURL_PROXY_USER') && defined('CURL_PROXY_PASSWORD') && CURL_PROXY_USER != '' && CURL_PROXY_PASSWORD != '') {
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, CURL_PROXY_USER . ':' . CURL_PROXY_PASSWORD);
      }
      $paypal_response = @curl_exec($ch);
      @curl_close($ch);
      if($paypal_response == '') $curl_flag = false;
    }
    if(!$curl_flag) {
      $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
      $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $header .= "Content-Length: ".strlen($this->response_string)."\r\n\r\n";
      $socket  = 'ssl://'; $port = '443';
      $fp = @fsockopen($socket.$domain,$port, $errno, $errstr, 30);
      if(!$fp) {
        $socket = 'tcp://'; $port = '80';
        $fp = @fsockopen($socket.$domain,$port, $errno, $errstr, 30);
      }
      if(!$fp) {
          $fp = @fopen('https://'.$domain.'/cgi-bin/webscr?'.$this->response_string);
          $paypal_response = @fgets($fp, 1024); @fclose($fp);
          if (!$paypal_response) {
            $paypal_http_response = @file('http://'.$domain.'/cgi-bin/webscr?'.$this->response_string);
            $paypal_response = @$paypal_http_response[0];
            if (!$paypal_response && ($debug->enabled)) $debug->add(HTTP_ERROR,sprintf(HTTP_ERROR_MSG,$curl_flag,$socket,$domain,$port));
          }
      } else {
        @fputs($fp, $header . $this->response_string);
        while (!feof($fp)) {
          $paypal_response .= @fgets($fp, 1024);
        }
        @fclose($fp);
      }
      unset($this->response_string);
    }

    if (strstr($paypal_response,'VERIFIED')) {
      if($debug->enabled) $debug->add(RESPONSE_VERIFIED,sprintf(RESPONSE_MSG,$curl_flag,$socket,$domain,$port,$paypal_response));
      return true;
    } else if (strstr($paypal_response,'INVALID')) {
      if($debug->enabled) $debug->add(RESPONSE_INVALID,sprintf(RESPONSE_MSG,$curl_flag,$socket,$domain,$port,$paypal_response));
      return false;
    } else {
      if($debug->enabled) $debug->add(RESPONSE_UNKNOWN,sprintf(RESPONSE_MSG,$curl_flag,$socket,$domain,$port,$paypal_response));
      return false;
    }
  }

  //Test both receiver email address and business ID
  function validate_receiver_email($receiver_email,$business) {
      global $debug;
      if(!strcmp(strtolower($receiver_email),strtolower($this->key['receiver_email'])) && !strcmp(strtolower($business),strtolower($this->key['business']))) {
        if($debug->enabled) $debug->add(EMAIL_RECEIVER,sprintf(EMAIL_RECEIVER_MSG,$receiver_email,$business,$this->key['receiver_email'],$this->key['business']));
        return true;
      } else {
        if($debug->enabled) $debug->add(EMAIL_RECEIVER,sprintf(EMAIL_RECEIVER_ERROR_MSG,$receiver_email,$business,$this->key['receiver_email'],$this->key['business'],$this->key['txn_id']));
        return false;
      }
    }

  function unique_txn_id() {
      global $debug;
      $txn_id_query = tep_db_query("select count(*) as count from " . TABLE_PAYPAL . " where txn_id = '" . tep_db_input($this->key['txn_id']) . "'");
      $txn_id = tep_db_fetch_array($txn_id_query);
      if ($txn_id['count'] < 1) { //txn_id doesn't exist
        return true;
      } else {
        if($debug->enabled) $debug->add(TXN_DUPLICATE,sprintf(TXN_DUPLICATE_MSG,$this->key['txn_id']));
        return false;
      }
  }

  function insert_ipn_txn() {
    global $debug;
    $sql_data_array = array(
        'txn_type'            => $this->key['txn_type'],
        'reason_code'         => $this->key['reason_code'],
        'payment_type'        => $this->key['payment_type'],
        'payment_status'      => $this->key['payment_status'],
        'pending_reason'      => $this->key['pending_reason'],
        'invoice'             => $this->key['invoice'],
        'mc_currency'         => $this->key['mc_currency'],
        'first_name'          => $this->key['first_name'],
        'last_name'           => $this->key['last_name'],
        'payer_business_name' => $this->key['payer_business_name'],
        'address_name'        => $this->key['address_name'],
        'address_street'      => $this->key['address_street'],
        'address_city'        => $this->key['address_city'],
        'address_state'       => $this->key['address_state'],
        'address_zip'         => $this->key['address_zip'],
        'address_country'     => $this->key['address_country'],
        'address_status'      => $this->key['address_status'],
        'payer_email'         => $this->key['payer_email'],
        'payer_id'            => $this->key['payer_id'],
        'payer_status'        => $this->key['payer_status'],
        'payment_date'        => $this->datetime_to_sql_format($this->key['payment_date']),
        'business'            => $this->key['business'],
        'receiver_email'      => $this->key['receiver_email'],
        'receiver_id'         => $this->key['receiver_id'],
        'txn_id'              => $this->key['txn_id'],
        'parent_txn_id'       => $this->key['parent_txn_id'],
        'num_cart_items'      => $this->key['txn_type'] == 'cart' ? $this->key['num_cart_items'] : '1',
        'mc_gross'            => $this->key['mc_gross'],
        'mc_fee'              => $this->key['mc_fee'],
        'payment_gross'       => $this->key['payment_gross'],
        'payment_fee'         => $this->key['payment_fee'],
        'settle_amount'       => $this->key['settle_amount'],
        'settle_currency'     => $this->key['settle_currency'],
        'exchange_rate'       => $this->key['exchange_rate'],
        'notify_version'      => $this->key['notify_version'],
        'verify_sign'         => $this->key['verify_sign'],
        'date_added'          => 'now()',
        'memo'                => $this->key['memo']);
    tep_db_perform(TABLE_PAYPAL, $sql_data_array);
    $this->ipn_id = tep_db_insert_id();
    $this->update_status($this->ipn_id,$this->key['payment_status'],$this->key['pending_reason']);
    if($debug->enabled) $debug->add(IPN_TXN_INSERT,sprintf(IPN_TXN_INSERT_MSG,$this->ipn_id));
    return $this->ipn_id;
  }

  function update_status($paypal_ipn_id, $payment_status, $pending_reason) {
    tep_db_query("update " . TABLE_PAYPAL . " set payment_status = '" . tep_db_input($payment_status) . "', pending_reason = '" . tep_db_input($pending_reason) . "', last_modified = now() where paypal_ipn_id = '" . (int)$paypal_ipn_id . "'");
    tep_db_query("insert into " . TABLE_PAYPAL_PAYMENT_STATUS_HISTORY . " (paypal_ipn_id, payment_status, pending_reason, date_added) values ('" . (int)$paypal_ipn_id . "', '" . tep_db_input($payment_status) . "', '" . tep_db_input($pending_reason) . "', now())");
  }

  function valid_payment() {
    global $order, $currencies, $currency, $debug;
    if (MODULE_PAYMENT_PAYPAL_IPN_CART_TEST == 'Off') return true;
    $valid_payment = true;
    //check the payment_currency
    if( $this->key['mc_currency'] != $currency) {
      if ($debug->enabled) $debug->add(CHECK_CURRENCY,sprintf(CHECK_CURRENCY_MSG,$this->key['mc_currency'],$currency));
      $valid_payment = false;
    }
    //check the payment_amount
    if ( $currencies->currencies[$currency]['symbol_left'].$this->key['mc_gross'].$currencies->currencies[$currency]['symbol_right'] != $order->info['total'] ) {
      if ($debug->enabled) $debug->add(CHECK_TOTAL,sprintf(CHECK_TOTAL_MSG,$this->key['mc_gross'],$order->info['total']));
      $valid_payment = false;
    }
    return $valid_payment;
  }

  //returns TABLE_PAYPAL.paypal_ipn_id
  function id() {
    return $this->ipn_id;
  }

  //returns the transaction type (paypal.txn_type)
  function txn_type() {
    return $this->key['txn_type'];
  }

  function datetime_to_sql_format($raw_datetime) {
    $months = array('Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04', 'May' => '05',  'Jun' => '06',  'Jul' => '07', 'Aug' => '08', 'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12');
    $hour = substr($raw_datetime, 0, 2);$minute = substr($raw_datetime, 3, 2);$second = substr($raw_datetime, 6, 2);
    $month = $months[substr($raw_datetime, 9, 3)];$day = substr($raw_datetime, 13, 2);$year = substr($raw_datetime, 17, 4);
    return ($year . "-" . $month . "-" . $day . " " . $hour . ":" . $minute . ":" . $second);
  }

  function dienice() {
    if ( strcmp(phpversion(),'3.0') <= 0 ) {
      if($this->key['digest_key'] == $this->digest_key()) {
        header("status: 204");
      } else {
        header("status: 500");
      }
    } else {
      if($this->key['digest_key'] == $this->digest_key()) {
        header("HTTP/1.0 204 No Response");
      } else {
        header("HTTP/1.0 500 Internal Server Error");
      }
    }
    exit;
  }

  function digest_key() {
    return strrev(md5(md5(strrev(md5(MODULE_PAYMENT_PAYPAL_IPN_DIGEST_KEY)))));
  }
}//end class
?>
