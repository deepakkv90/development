<?php
/*
  $Id: checkout_success.php,v 1.3 2004/09/25 14:36 DMG Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // if the customer is not logged on, redirect them to the shopping cart page
  if ( ! isset($_SESSION['customer_id']) ) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'update')) {
    $notify_string = 'action=notify&';
    $notify = $_POST['notify'];
    if (!is_array($notify)) $notify = array($notify);
    for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
      $notify_string .= 'notify[]=' . $notify[$i] . '&';
    }
    if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);
    if ( isset($_SESSION['noaccount']) ) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string, 'NONSSL'));
    }else{
      tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
    }
  }
  
  //Submit tell a friend form
  if ((isset($_GET['action']) && $_GET['action'] == 'refer')) {
  
		require('includes/classes/class.phpmailer.php');
		
		$your_name = tep_db_prepare_input($_POST['your_name']);
		$your_email = tep_db_prepare_input($_POST['your_email']);
		$friend_name = tep_db_prepare_input($_POST['friend_name']);
		$friend_email = tep_db_prepare_input($_POST['friend_email']);
		$your_content = tep_db_prepare_input($_POST['your_content']);
		$your_content = str_replace("[Friends Name]",$friend_name,$your_content);
	
		//Use PHP Mailer to send email - Start
		
		$mail = new PHPMailer();		

		$mail->AddReplyTo($your_email,$your_name);		
		$mail->SetFrom($your_email, $your_name);		
		$mail->AddAddress($friend_email, $friend_name);						
		$mail->Subject    = $your_name." recommend you ". HTTP_SERVER;		
		$mail->Body = $your_content;		
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test		
		//$mail->MsgHTML($your_content);						
		if($mail->Send()) {
			$refer_status = "yes";
		} else {
			$refer_status = "no";
		}		
		//confirmation email to you and admin
		$mail->AddReplyTo(STORE_OWNER_EMAIL_ADDRESS,STORE_NAME);		
		$mail->SetFrom(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);		
		$mail->AddAddress($your_email, $your_name);			
		$mail->AddAddress(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);		
		$mail->Subject = "Copy of message sent to ".$friend_name . " - " .$friend_email;		
		$mail->Body = $your_content;		
		$mail->Send();			
		//Used PHP Mailer to send emails - End  
  }
    
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  if ( isset($_SESSION['noaccount']) ) {
    $order_update = array('purchased_without_account' => '1');
    tep_db_perform(TABLE_ORDERS, $order_update, 'update', "orders_id = '".$_GET['order_id']."'");
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . tep_db_input($_SESSION['customer_id']) . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($_SESSION['customer_id']) . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . tep_db_input($_SESSION['customer_id']) . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . tep_db_input($_SESSION['customer_id']) . "'");
    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . tep_db_input($_SESSION['customer_id']) . "'");
    tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . tep_db_input($_SESSION['customer_id']) . "'");
    tep_session_destroy();
  }
  if (isset($_SESSION['nusoap_response'])) unset($_SESSION['nusoap_response']);
  
  //get customer info - July 06 2011
  $customer_info = tep_get_customer_info();
  
  // load all enabled checkout success modules
  require(DIR_WS_CLASSES . 'checkout_success.php');
  $checkout_success_modules = new checkout_success;

  $content = CONTENT_CHECKOUT_SUCCESS;
  $javascript = 'popup_window_print.js';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  
  $all_ret_1 = $_SESSION['all_pro_details'];
  $all_ret_ = unserialize($all_ret_1);
	
	?>
	<script type="text/javascript">
	var _gaq = _gaq || [];
	//23997981
  _gaq.push(['_setAccount', 'UA-23997981-1']);
  _gaq.push(['_trackPageview']);
  
	
	<?php foreach($all_ret_['prod_info'] as $prod_det ){ ?>
	_gaq.push(['_addItem',
		'<?php echo $all_ret_['cartid']; ?>',
		'<?php echo $prod_det['id']; ?>',
		'<?php echo $prod_det['name']; ?>',
		'<?php echo $prod_det['model']; ?>',
		'<?php echo $prod_det['final_price']; ?>',
		'<?php echo $prod_det['qty']; ?>'
	]);
	<?php } ?>
	
	_gaq.push(['_addTrans',
		'<?php echo $all_ret_['cartid']; ?>',
		'<?php echo $all_ret_['user_det']['company']; ?>',
		'<?php echo $all_ret_['prod_all']['total']; ?>',
		'<?php echo $all_ret_['prod_all']['tax']; ?>',
		'<?php echo $all_ret_['prod_all']['shipping_cost']; ?>',
		'<?php echo $all_ret_['user_det']['city']; ?>',
		'<?php echo $all_ret_['user_det']['state']; ?>',
		'<?php echo $all_ret_['user_det']['country']['title']; ?>'
	]);
	_gaq.push(['_trackTrans']);
	
	// load Analytics
	
	</script>
	
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
</body></html>
