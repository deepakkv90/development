<?php
/*
  $Id: Debug.class.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License

*/

class PayPal_Debug {

  function PayPal_Debug($email_address, $debug_enabled = 'No') {
    $this->email_address = $email_address;
    $this->enabled = ($debug_enabled == 'Yes') ? true : false;
    $this->error = false;
    $this->info = array();
  }

  function init($debug_str) {
    $debug_string = sprintf(DEBUG_MSG,str_replace('&', "\r\n", $debug_str ));
    $this->add(DEBUG,$debug_string);
  }

  function add($subject,$msg) {
    $this->info[] = array( 'subject' => $subject, 'msg' => $msg);
  }

  function raiseError($subject, $msg, $clear_stack = false) {
    if($clear_stack === true) unset($this->info);
    $this->add($subject,$msg);
    $this->error = true;
  }

  function info($html = false) {
    $debug_string = '';
    $lf = "\r\n";
    $debug = $this->info;
    reset($debug);
    $nMsgs = count($debug);
    for ($i=0; $i<$nMsgs; $i++) {
      $debug_string .= EMAIL_SEPARATOR.$lf.$debug[$i]['subject'].$lf.EMAIL_SEPARATOR.$lf.$debug[$i]['msg'].$lf.$lf;
    }
    return ($html === true) ? str_replace("\n", "\n<br>", $debug_string) : $debug_string;
  }

  function sendEmail() {
    if(count($this->info) > 0) {
      $to_name = '';
      $to_address = $this->email_address;
      $subject = IPN_PAYMENT_MODULE_NAME;
      $this->add(IPN_EMAIL,IPN_EMAIL_MSG);
      $msg = strip_tags(nl2br($this->info()));
      $from_name = IPN_PAYMENT_MODULE_NAME;
      $from_address = strtolower(trim($this->email_address));
      tep_mail($to_name, $to_address, $subject, $msg, $from_name, $from_address);
    }
  }
}//end class
?>
