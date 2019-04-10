<?php
include_once("msrinclude/MSRSession.php");
include_once("msrinclude/GetDiscountScriptCall.php");
include_once("msrinclude/GetRebateCall.php");
include_once("msrinclude/CompleateCheckoutCall.php");
/*
  $Id: ot_checkout_reward.php,v 1.3 2007/06/14 11:18:01 alin Exp $

  Copyright (c) 2007 MyStoreCredit
*/

  class ot_checkout_reward{
    var $title, $output;

    function ot_checkout_reward() {
      $this->code = 'ot_checkout_reward';
      $this->title = (defined('MODULE_ORDER_TOTAL_CHECKOUT_REWARD_TITLE')) ? MODULE_ORDER_TOTAL_CHECKOUT_REWARD_TITLE : '';
      $this->description = (defined('MODULE_ORDER_TOTAL_CHECKOUT_REWARD_DESCRIPTION')) ? MODULE_ORDER_TOTAL_CHECKOUT_REWARD_DESCRIPTION : '';
      $this->enabled = (defined('MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STATUS') && MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STATUS == 'on') ? true : false;
      $this->sort_order = (defined('MODULE_ORDER_TOTAL_CHECKOUT_REWARD_SORT_ORDER')) ? (int)MODULE_ORDER_TOTAL_CHECKOUT_REWARD_SORT_ORDER : 80;
      $this->serverSSL = (defined('MODULE_ORDER_TOTAL_SSL') && MODULE_ORDER_TOTAL_SSL == 'Yes') ? true : false;
      $this->rebateTextOpen = (defined('MODULE_REBATE_TEXT_STATUS') && MODULE_REBATE_TEXT_STATUS == 'Yes') ? true : false;
      $this->store_id = (defined('MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STORE_ID')) ? MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STORE_ID : '';
      if ($this->serverSSL){
        $this->serverURL = "https://www.mystorerewards.com/msrco/ws/sapi.htm";
      } else {
        $this->serverURL = "http://www.mystorerewards.com/msrco/ws/api.htm";
      }
      $this->rewardsText = (defined('MODULE_REWARD_TEXT')) ? MODULE_REWARD_TEXT : '';
      $this->rewardsMaxAmount = (defined('MODULE_REWARD_MAXAMOUNT')) ? MODULE_REWARD_MAXAMOUNT : '';
      $this->proxyServerURL = (defined('MODULE_REWARD_SERVER_PROXY_URL')) ? MODULE_REWARD_SERVER_PROXY_URL : '';
      $this->proxyServerPORT = (defined('MODULE_REWARD_SERVER_PROXY_PORT')) ? MODULE_REWARD_SERVER_PROXY_PORT : '';
      $this->output = array();
      $this->scRebate = 0;
      $this->staticText = 'MyStore<strong><font color="#0000fe">R</font><font color="#fe0000">e</font><font color="#00ccfe">w</font><font color="#fe9900">a</font><font color="#00cc00">r</font><font color="#fe0000">d</font><font color="#0000fe">s</font></strong>';
    }


    function processMyStoreRewards(){
      global $order, $currencies, $rebate, $products, $payment_modules;

      $rewardText = $this->staticText.' '.$this->rewardsText;

      if ((!isset($_GET['cr']) && !isset($_POST['cr']) &&  !isset($_POST['cr'])) && !isset($rebate)) {
        $text = '<a href="javascript:void(0)" onclick="javascript:getElementById(\'rewardsDIV\').style.display=\'block\'"><img border=0 src="https://www.mystorerewards.com/msrco/images/mark.png"></a>';
        if ($content !== false) {
          $session = new MSRSession($this->store_id, 'GetDiscountScript', $this->serverURL, $this->proxyServerURL, $this->proxyServerPORT);
          $getDiscountScriptCall = new GetDiscountScriptCall($this->store_id, $order->customer['email_address']);
          //send the request and get response
          $responseXml = $session->sendHttpRequest($getDiscountScriptCall->callToXML());
          if ($responseXml == false){
            //it meens that was an error
            return;
          }

          $aa = $_SESSION['navigation']->path;
          $paypalPost = $aa[2]['post'];

          $content = $getDiscountScriptCall->getScriptBody($responseXml);

          echo tep_draw_form('msrForm', $this->selfURL(), 'post');
                
          foreach  ($_POST as $key => $val){
            echo '<input type="hidden" name="'.$key.'" value="'.$val.'">';
          } 

          echo '<input type="hidden" name="osCisd" value="'.$this->getSession().'">';
          echo '<input type="hidden" name="cr" value=0>';
          echo '</form>';

          //default present the rebate text....
          if ($this->rebateTextOpen) {
            $content = str_replace("display:none", "display:block", $content);
          }
          $content = str_replace("<!-- BUYER_NAME -->", $order->customer['firstname']." ".$order->customer['lastname'], $content);
          $content = str_replace("<!-- OPTIN_LINK -->", "javascript:document.msrForm.submit();", $content);
          $content = str_replace("<!-- MAXIMUM_LIMIT-->", $currencies->format($this->rewardsMaxAmount, true, $order->info['currency']), $content);

          echo '<tr><td colspan=2>';
          echo $content;
          echo '<td></tr>';

          $rewardText = '<a href="javascript:void(0)" onclick="javascript:getElementById(\'rewardsDIV\').style.display=\'block\'">'.$rewardText.'</a>';

          $this->output[] = array('title' => $rewardText,
                                  'text' => $text,
                                  'value' => '');
        } else {
          // an error happened
          return;
        }
      } else {
        if (!isset($rebate)){
          $session = new MSRSession($this->store_id, 'GetRebate', $this->serverURL, $this->proxyServerURL, $this->proxyServerPORT);
          $getGetRebateCall = new GetRebateCall($this->store_id, $order->customer['email_address']);

          $responseXml = $session->sendHttpRequest($getGetRebateCall->callToXML());
          if ($responseXml == false){
            //it meens that was an error
            return;
          }

          $this->scRebate = $getGetRebateCall->getBuyerRebate($responseXml);

          //store rebate in the table....
          tep_db_query("insert into sessions_mystorerewards (session_id, rebate) values ('".$this->getSession()."' , '".$this->scRebate."')");
          $rebate=$this->scRebate;
        }

        $rewardAmount = ($order->info['subtotal']*$rebate)/100;
        if ($rewardAmount > $this->rewardsMaxAmount) {
          $rewardAmount = $this->rewardsMaxAmount;
        }

        $order->info['total'] = $order->info['total'] - $rewardAmount;

        $this->output[] = array('title' => $rewardText,
                        'text' => '<span class="productSpecialPrice">-'.$currencies->format($rewardAmount, true, $order->info['currency'], $order->info['currency_value']).'</span>',
                        'value' => -$rewardAmount);
      }
    }

    function selfURL() {
      return tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL');
        //$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        //$protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
        //$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        //return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
    }

    function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }

    function process() {
      global $order, $currencies, $rebate, $products;
      $rewardText = $this->staticText.' '.$this->rewardsText;

      //try to get the rebate...
      $this->getRebate();
      //see if it is the isConfirmationPage
      $currentURLPage = $_SERVER["PHP_SELF"];
      $isConfirmationPage =$this->stripos($currentURLPage, "checkout_process.php");
      if ($isConfirmationPage!==false){
        $rewardAmount = 0;
        if (isset($rebate)) {
          $rewardAmount = ($order->info['subtotal']*$rebate)/100;
          if ($rewardAmount > $this->rewardsMaxAmount) {
            $rewardAmount = $this->rewardsMaxAmount;
          }
        } else {
          $rebate = 0;          
        }

        $order->info['total'] = $order->info['total'] - $rewardAmount;

        //send to our server the shopping card data :)
        $session = new MSRSession($this->store_id, 'GetDiscountScript', $this->serverURL, $this->proxyServerURL, $this->proxyServerPORT);
        $compleateCheckoutCall = new CompleateCheckoutCall($this->store_id, $order->customer['email_address']);
        //send the request and get response
        $responseXml = $session->sendHttpRequest($compleateCheckoutCall->callToXML($order->products, DEFAULT_CURRENCY, $rebate, $rewardAmount, $order->info['total'], DEFAULT_CURRENCY));

        tep_db_query("DELETE FROM sessions_mystorerewards WHERE session_id='".$this->getSession()."'");

        $this->output[] = array('title' => $rewardText,
                        'text' => '<span class="productSpecialPrice">-'.$currencies->format($rewardAmount, true, $order->info['currency'], $order->info['currency_value']).'</span>',
                        'value' => -$rewardAmount);

      } else {
        $this->processMyStoreRewards();
      }
    }
  
    function getSession(){
      $ssesionId = session_id();
      if (isset($_GET['osCsid'])) {
        $ssesionId = $_GET['osCsid'];
      } else if (isset($_POST['osCsid'])) {
        $ssesionId = $_POST['osCsid'];
      }

      return $ssesionId;
    }    

    function getRebate(){
      global $rebate;
      $result = mysql_query("SELECT rebate FROM sessions_mystorerewards WHERE session_id='".$this->getSession()."'");
      if (mysql_num_rows($result)==1) {
        $rebate = mysql_result($result, 0);       
      }    
    }


    function stripos($string,$word) {
      $retval = false;
      for($i=0;$i<=strlen($string);$i++) {
        if (strtolower(substr($string,$i,strlen($word))) == strtolower($word)) {
          $retval = true;
        }
      }
      return $retval;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STATUS',
                   'MODULE_ORDER_TOTAL_CHECKOUT_REWARD_SORT_ORDER', 
                   'MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STORE_ID',
                   'MODULE_REWARD_SERVER_LOCATION',
                   'MODULE_REWARD_SERVER_PROXY_URL',
                   'MODULE_REWARD_SERVER_PROXY_PORT',
                   'MODULE_REWARD_TEXT',
                   'MODULE_REWARD_MAXAMOUNT',
                   'MODULE_ORDER_TOTAL_SSL',
                   'MODULE_REBATE_TEXT_STATUS');
    }

    function install() {
      //Add new table
      tep_db_query("drop table if exists sessions_mystorerewards");
      tep_db_query("create table sessions_mystorerewards (session_id varchar(255) NOT NULL,
           rebate int(11) NOT NULL, 
           PRIMARY KEY (session_id))");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Secure Connection', 'MODULE_ORDER_TOTAL_SSL', 'Yes', 'My PHP server allows a secure connection to MyStoreRewards (https://)', '6', '1','tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show invitation on all checkouts', 'MODULE_REBATE_TEXT_STATUS', 'Yes', '(No=Buyer must click ???s to see invitation)', '6', '2','tep_cfg_select_option(array(\'Yes\', \'No\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Checkout Rewards Program', 'MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STATUS', 'on', 'Turn MyStoreRewards on or off for this store:', '6', '3','tep_cfg_select_option(array(\'on\', \'off\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_CHECKOUT_REWARD_SORT_ORDER', '80', 'Placement of your MyStoreRewards invitation in your checkout invoice:', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Store Token', 'MODULE_ORDER_TOTAL_CHECKOUT_REWARD_STORE_ID', '0', 'Copy and paste the Store Token from your account at MyStoreRewards.com in this space:', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Proxy URL', 'MODULE_REWARD_SERVER_PROXY_URL', '', 'Hosting Service (e.g. GoDaddy) required proxy server ip address and port:', '6', '6', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Proxy PORT', 'MODULE_REWARD_SERVER_PROXY_PORT', '', '', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('MyStoreRewards URL', 'MODULE_REWARD_SERVER_LOCATION', 'www.mystorerewards.com/msrco', 'Copy and paste the location for the server hosting your MyStoreRewards program:', '6', '8', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('MyStoreRewards Text', 'MODULE_REWARD_TEXT', 'Discount', 'Type the text for the MyStoreRewards invitation line in your invoice:', '6', '9', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Set your Maximum Reward Amount', 'MODULE_REWARD_MAXAMOUNT', '10', 'You can choose the maximum award to give on any transaction. We recommend 10.00 in your existing currency:', '6', '10', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
}
?>
