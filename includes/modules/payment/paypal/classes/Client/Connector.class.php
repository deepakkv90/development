<?php
/*
  $Id: Connector.class.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License

*/

class PayPal_Client_Connector {

  function PayPal_Client_Connector() {
  }

  function getResponse($domain) {
    global $debug;
    $response = '';
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
      $response = @curl_exec($ch);
      @curl_close($ch);
      if($response == '') $curl_flag = false;
    }
    if(!$curl_flag) {
      $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
      $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $header .= "Content-Length: ".strlen($this->response_string)."\r\n\r\n";
      $transport  = 'ssl://'; $port = '443';
      $fp = @fsockopen($transport.$domain,$port, $errno, $errstr, 30);
      if(!$fp) {
        $transport = 'tcp://'; $port = '80';
        $fp = @fsockopen($transport.$domain,$port, $errno, $errstr, 30);
      }
      if(!$fp) {
          $fp = @fopen('https://'.$domain.'/cgi-bin/webscr?'.$this->response_string,'rb');
          $response = $this->getRequestBodyContents($fp); @fclose($fp);
          if (!$response) {
            $response = @file('http://'.$domain.'/cgi-bin/webscr?'.$this->response_string);
            if (!$response && ($debug->enabled)) $debug->add(HTTP_ERROR,sprintf(HTTP_ERROR_MSG,$curl_flag,$transport,$domain,$port));
          }
      } else {
        @fputs($fp, $header . $this->response_string);
        $response = $this->getRequestBodyContents($fp);
        @fclose($fp);
      }
      unset($this->response_string);
    }

    if($debug->enabled) {
      $debug->add(PAYPAL_RESPONSE,sprintf(PAYPAL_RESPONSE_MSG,$this->getVerificationResponse($response)));
      $debug->add(CONNECTION_TYPE,sprintf(CONNECTION_TYPE_MSG,$curl_flag,$transport,$domain,$port));
    }
    return $response;
  }

  function getVerificationResponse($response) {
    if(is_array($response)) {
      return @$response[0];
    } elseif (is_string($response)) {
      $array = explode("\n",$response);
      return @$array[0];
    }
    return false;
  }

  function getRequestBodyContents(&$handle) {
    $headerdone = false;
    if ($handle) {
      while (!feof($handle)) {
        $line = @fgets($handle, 1024);
        if (!strcmp($line, "\r\n")) {
          // read the header
          $headerdone = true;
        } elseif ($headerdone) {
          // header has been read. now read the contents
          $buffer .= $line;
        } elseif (in_array($line,array('VERIFIED','INVALID'))) {
          return $line;
        }
      }
      return $buffer;
    }
    return false;
  }

  //Test both receiver email address and business ID
  //Modified to accept semicolon delimited business ID list.
  function validateReceiverEmail($receiver_email,$business) {
      global $debug;
    if(!strcmp(strtolower($receiver_email),strtolower($this->key['receiver_email'])) && in_array(strtolower($this->key['business']),explode(';',strtolower($business)))) {
        if($debug->enabled) $debug->add(EMAIL_RECEIVER,sprintf(EMAIL_RECEIVER_MSG,$receiver_email,$business,$this->key['receiver_email'],$this->key['business']));
        return true;
      } else {
        if($debug->enabled) $debug->add(EMAIL_RECEIVER,sprintf(EMAIL_RECEIVER_ERROR_MSG,$receiver_email,$business,$this->key['receiver_email'],$this->key['business'],$this->key['txn_id']));
        return false;
      }
  }

  function validPayment($amount,$currency) {
    global $debug;
    $valid_payment = true;
    //check the payment currency and amount
    if ( ($this->key['mc_currency'] != $currency) || ($this->key['mc_gross'] != $amount) )
      $valid_payment = false;
    if($valid_payment === false && $debug->enabled) $debug->add(CART_TEST,sprintf(CART_TEST_ERR_MSG,$amount,$currency,$this->key['mc_gross'],$this->key['mc_currency']));
    return $valid_payment;
  }

  function dienice($status = '200') {
    switch($status) {
      case '200';
        header("HTTP/1.0 200 OK");
        break;
      case '500':
      default:
        if(isset($this->key['digestKey']) && $this->key['digestKey'] === $this->digestKey()) {
          header("HTTP/1.0 204 No Content"); exit;
        } else {
          header("HTTP/1.0 500 Internal Server Error"); exit;
        }
        break;
    }
  }

  function digestKey() {
    return strrev(md5(md5(strrev(md5(MODULE_PAYMENT_PAYPAL_IPN_DIGEST_KEY)))));
  }

  function validDigest() {
    return (isset($this->key['digestKey']) && $this->key['digestKey'] === $this->digestKey());
  }

  function setTestMode($testMode) {
    switch($testMode) {
      case 'On':
        $this->testMode = 'On';
        break;
      default:
        $this->testMode = 'Off';
      break;
    }
  }

  function testMode($testMode='') {
    if(tep_not_null($testMode)) {
      return ($this->testMode === $testMode);
    } elseif (isset($this->testMode)) {
      return ($this->testMode === 'On');
    }

    return false;
  }

}//end class
?>
