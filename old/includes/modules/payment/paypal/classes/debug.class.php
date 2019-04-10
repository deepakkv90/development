<?php
/*
  $Id: debug.class.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License

*/

class debug {

  function debug($email_address, $debug_enabled = 'No') {
    $this->email_address = $email_address;
    $this->enabled = ($debug_enabled == 'Yes') ? true : false;
    $this->error = false;
    $this->info = array();
  }

  function init($debug_str, $response_str) {
    $debug_string = sprintf(DEBUG_MSG,str_replace('&', "\r\n", $debug_str ),str_replace('&', "\r\n", $response_str ));
    $this->add(DEBUG,$debug_string);
  }

  function add($subject,$msg) {
    $this->info[] = array( 'subject' => $subject, 'msg' => $msg);
  }

  function raise_error($subject, $msg, $clear_stack = false) {
    if($clear_stack === true) unset($this->info);
    $this->add($subject,$msg);
    $this->error = true;
  }

  function info($html = false) {
    $debug_string = '';
    $seperator = "\r\n".EMAIL_SEPARATOR."\r\n";
    $debug = $this->info;
    reset($debug);
    $debug_msg_total = count($debug);
    for ($i=0; $i<$debug_msg_total; $i++) {
      $debug_string .= $seperator.$debug[$i]['subject'].$seperator.$debug[$i]['msg']."\r\n";
    }
    if($html === true) $debug_string = str_replace("\n", "\n<br>", $debug_string);
    return $debug_string;
  }

  function send_email() {
    if(count($this->info) > 0) {
      $to_name = '';
      $to_address = $this->email_address;
      //$subject = "PayPal_Shopping_Cart_IPN";
      $subject = PAYPAL_SHOPPING_CART_IPN_SUBJECT;
      $msg = strip_tags(nl2br($this->info()));
      //$from_name = 'PayPal_Shopping_Cart_IPN';
      $from_name = PAYPAL_SHOPPING_CART_IPN_FROM;
      $from_address = strtolower(trim($this->email_address));
      tep_mail($to_name, $to_address, $subject, $msg, $from_name, $from_address);
    }
  }
}//end class
?>
