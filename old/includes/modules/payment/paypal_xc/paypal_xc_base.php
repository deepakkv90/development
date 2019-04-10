<?php
/*
  $Id: paypal_xc_base.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

require_once(dirname(__FILE__) . '/curl_xc_link.php');

class paypal_xc_base {
  
  var $paypal_url, $curl, $url, $error_msg, $error_no, $button_source_ec;
  function paypal_xc_base() {
    if (MODULE_PAYMENT_PAYPAL_XC_SERVER == 'sandbox') {
      $this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
      $this->url = 'https://api.sandbox.paypal.com/nvp';
    } elseif (MODULE_PAYMENT_PAYPAL_XC_SERVER == 'live') {
      $this->paypal_url = 'https://www.paypal.com/cgibin/webscr';
      $this->url = 'https://api-3t.paypal.com/nvp';
    }
    switch (MODULE_PAYMENT_PAYPAL_XC_MERCHANT_COUNTRY) {
      case 'US':
        $this->button_source_ec = 'CREloaded_Cart_EC_US';
        break;
      case 'UK':
        $this->button_source_ec = 'CRELoaded_Cart_EC_UK';
        break;
    }
    $this->paypal_xc_init();
  }
  
  function paypal_xc_init() {
    $this->curl = new curl_xc_link($this->paypal_url);
    $this->curl->init();
  }
  
  function SetExpressCheckout($params) {
    $url = $this->url . $this->RequiredSecurityParameters('SetExpressCheckout') . '&PAYMENTACTION=' . MODULE_PAYMENT_PAYPAL_XC_TRXTYPE;
    foreach ($params as $key => $value) {
      $url .= '&' . $key . '=' . urlencode($value);
    }
    if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
      $this->write_log('SetExpressCheckout Request: ' . $url);
    }
    $response = urldecode($this->curl->redirect($url));
    if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
      $this->write_log('SetExpressCheckout Response: ' . $response);
    }
    return $response;
  }
  
  function DoCapture($params) {
    $url = $this->url . $this->RequiredSecurityParameters('DoCapture');
    foreach ($params as $key => $value) {
      $url .= '&' . $key . '=' . urlencode($value);
    }
    if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
      $this->write_log('DoCapture Request: ' . $url);
    }
    $response = urldecode($this->curl->redirect($url));
    if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
      $this->write_log('DoCapture Response: ' . $response);
    }
    return $response;
  }
  
  function getField($response, $field) {
    $field .= '=';
    if (strstr($response, $field)) {
      $nv_pair = explode('&', $response);
      foreach ($nv_pair as $value) {
        if ( substr($value, 0, strlen($field)) == $field ) {
          return substr($value, strpos($value, $field) + strlen($field));
        }
      }
    } else {
      return false;
    }
    return false;
  }
  
  function RequiredSecurityParameters($method) {
    global $currency;
    $str = '?METHOD=' . $method . '&USER=' . urlencode(MODULE_PAYMENT_PAYPAL_XC_API_USERNAME) . '&PWD=' . urlencode(MODULE_PAYMENT_PAYPAL_XC_API_PASSWORD) . '&VERSION=2.3&SIGNATURE=' . urlencode(MODULE_PAYMENT_PAYPAL_XC_API_SIGNATURE) . '&CURRENCYCODE=' . $currency;
    return $str;
  }
  
  function GetExpressCheckoutDetailsRequest($token, $PayerID) {    
    $url = $this->url . $this->RequiredSecurityParameters('GetExpressCheckoutDetails') .'&TOKEN=' . $token;
    if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
      $this->write_log('GetExpressCheckoutDetails Request: ' . $url);
    }
    $response = urldecode($this->curl->redirect($url));
    if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
      $this->write_log('GetExpressCheckoutDetails Response: ' . $response);
    }
    if ($this->is_successful($response) === true) {
      $data_array = explode('&', $response);
      foreach ($data_array as $value) {
        $pair_array = explode('=', $value);
        $customer_info[$pair_array[0]] = $pair_array[1];
      }
      return $customer_info;
    } else {
      return false;
    }
  }
  
  function DoExpressCheckoutPayment($token, $payerid, $address, $amt) {
    $url = $this->url . $this->RequiredSecurityParameters('DoExpressCheckoutPayment') .'&TOKEN=' . $token . '&PAYERID=' . $payerid . '&PAYMENTACTION=' . MODULE_PAYMENT_PAYPAL_XC_TRXTYPE . '&AMT=' . $amt . '&NOTIFYURL=' . tep_href_link(FILENAME_PAYPAL_XC_IPN, '', 'SSL') . '&BUTTONSOURCE=' . $this->button_source_ec;
    foreach ($address as $key => $value) {
      $url .= '&' . $key . '=' . urlencode($value);
    }    
//    die($url);
    if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
      $this->write_log('DoExpressCheckout Request: ' . $url);
    }
    $response = urldecode($this->curl->redirect($url));
    if ( MODULE_PAYMENT_PAYPAL_XC_DEBUGGING == 'True' ) {
      $this->write_log('DoExpressCheckout Response: ' . $response);
    }
    return $response;
  }
 
  function is_successful($response) {
    if ($this->getField($response, 'ACK') == 'Success') {
      return true;
    } else {
      $this->setError($response);
      return false;
    }
  }
  
  function setError($response) {
    $this->error_msg = $this->getField($response, 'L_LONGMESSAGE0');
    $this->error_no = $this->getField($response, 'L_ERRORCODE0');
  }
  
  function getAVSCODE($avs_code) {
    switch ($avs_code) {
      case 'A':
        $avs = 'Address Address only (no ZIP)';
        break;
      case 'B':
        $avs = 'International “A” Address only (no ZIP)';
        break;
      case 'C':
        $avs = 'International “N” None';
        break;
      case 'D':
        $avs = 'International “X” Address and Postal Code';
        break;
      case 'E':
        $avs = 'Not allowed for MOTO (Internet/Phone)';
        break;
      case 'F':
        $avs = 'UK-specific “X” Address and Postal Code';
        break;
      case 'G':
        $avs = 'Global Unavailable Not applicable';
        break;
      case 'I':
        $avs = 'International Unavailable Not applicable';
        break;
      case 'N':
        $avs = 'No None';
        break;
      case 'P':
        $avs = 'Postal (International “Z”) Postal Code only (no Address)';
        break;
      case 'R':
        $avs = 'Retry Not applicable';
        break;
      case 'S':
        $avs = 'Service not Supported Not applicable';
        break;
      case 'U':
        $avs = 'Unavailable Not applicable';
        break;
      case 'W':
        $avs = 'Whole ZIP Nine-digit ZIP code (no Address)';
        break;
      case 'X':
        $avs = 'Exact match Address and nine-digit ZIP code';
        break;
      case 'Y':
        $avs = 'Yes Address and five-digit ZIP';
        break;
      case 'Z':
        $avs = 'ZIP Five-digit ZIP code (no Address)';
        break;
      default:
        $avs = 'Error';
      }
    return $avs;
  }
  
  function getCVV2MATCH($cvv2match) {
    switch ($cvv2match) {
      case 'M':
        $cvv2 = 'Match CVV2';
        break;
      case 'N':
        $cvv2 = 'No match None';
        break;
      case 'P':
        $cvv2 = 'Not Processed Not applicable';
        break;
      case 'S':
        $cvv2 = 'Service not Supported Not applicable';
        break;
      case 'U':
        $cvv2 = 'Unavailable Not applicable';
        break;
      case 'X':
        $cvv2 = 'No response Not applicable';
        break;
      default:
        $cvv2 = 'Error';
        break;
      }
    return $cvv2;
  }
  
  function get_payment_date($payment_date) {
    $date = explode(' ', $payment_date);
    $month_array['Jan'] = 1;
    $month_array['Feb'] = 2;
    $month_array['Mar'] = 3;
    $month_array['Apr'] = 4;
    $month_array['May'] = 5;
    $month_array['Jun'] = 6;
    $month_array['Jul'] = 7;
    $month_array['Aug'] = 8;
    $month_array['Sep'] = 9;
    $month_array['Oct'] = 10;
    $month_array['Nov'] = 11;
    $month_array['Dec'] = 12;
    $ret['timezone'] = $date[4];
    $ret['date'] = $date[3] . '-' . $month_array[$date[1]] . '-' . str_replace(',', '', $date[2]);
    $ret['time'] = $date[0];
    return $ret;
  }
  
  function write_log($message, $log_file = 'debug/paypal_xc_debug.txt') {
    $fp = @fopen($log_file, 'a');
    @fwrite($fp, 'Log Time: ' . date('Y-m-d H:i:s') . ' by: ' . $_SERVER['PHP_SELF'] . "\n");
    @fwrite($fp, $message . "\n\n");
    @fclose($fp);
  }
}

?>