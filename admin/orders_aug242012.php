<?php
/*
  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/                                                   

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  
//*****************************************************  
  // Function For updtae status of order in crm
 function manage_crm_order_status($oID,$status,$status_name,$comment_post,$notify_cus_new)
 {
 	 
  $ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	$logged_in_userid = $_SESSION['login_id'];
 
 //fetch user name from cre
  $query_cre_user =  "SELECT 	admin_firstname, admin_lastname 
        FROM admin WHERE admin_id ='$logged_in_userid'";
  $exe_admin_name = tep_db_query($query_cre_user);
  $rs_admin_name  = tep_db_fetch_array ($exe_admin_name);
  $admin_name     = $rs_admin_name['admin_firstname']." ".$rs_admin_name['admin_lastname'];
  
  	//modified by Basabdutta on 13.03.12
	//$url = "http://www.namebadgesinternational.com.au/CRM-success/CrmManageOrder.php";
	//$url = "http://ajparkes.com.au/ajpcrm/index.php?entryPoint=updateQuoteFromCRE";
	$url = "http://namebadgesinternational.com.au/CRM-success/CrmManageOrder.php";
	//----------------------------------
	
	$fields = "orders_id=".$oID
			."&status=".$status
			."&status_name=".$status_name
			."&comments=".$comment_post
			."&notify=".$notify_cus_new
			."&admin_name=".$admin_name
			."&mode=".'order_status';
			
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
  curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
	curl_setopt($ch, CURLOPT_URL,$url);	
	$result = curl_exec ($ch);
	curl_close ($ch);
	
	//var_dump($result);
  //exit;
} //End of function   manage_crm_order_status

function delete_quote_from_crm($o_id)
{
  $ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	
	//$url = "http://www.namebadgesinternational.com.au/CRM-success/CrmManageOrder.php";
        $url = "http://namebadgesinternational.com.au/CRM-success/CrmManageOrder.php";
	$fields = "orders_id=".$o_id
		        ."&mode=".'delete';
		        
   
			
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
	curl_setopt($ch, CURLOPT_URL,$url);	
	$result = curl_exec ($ch);
	curl_close ($ch);
	
	//var_dump($result);
  //exit;

}//End of function delete_quote_from_crm 

//**********************************************************  
  $currencies = new currencies();
  
  // RCI code start
  echo $cre_RCI->get('global', 'top', false);
  echo $cre_RCI->get('orders', 'top', false); 
  // RCI code eof
  
  // multi-vendor shipping
  function vendors_email($vendors_id, $oID, $status, $vendor_order_sent) {
  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
    $vendor_order_sent =  false;
    $debug='no';
    $vendor_order_sent = 'no';
    $index2 = 0;
    // get the Vendors
    $vendor_data_query = tep_db_query("SELECT v.vendors_id, v.vendors_name, v.vendors_email, v.vendors_contact, v.vendor_add_info, v.vendor_street, v.vendor_city, v.vendor_state, v.vendors_zipcode, v.vendor_country, v.account_number, v.vendors_status_send, os.shipping_module, os.shipping_method, os.shipping_cost, os.shipping_tax, os.vendor_order_sent 
                                         from " . TABLE_VENDORS . " v,  
                                              " . TABLE_ORDERS_SHIPPING . " os 
                                       WHERE v.vendors_id=os.vendors_id 
                                         and v.vendors_id='" . $vendors_id . "' 
                                         and os.orders_id='" . (int)$oID . "' 
                                         and v.vendors_status_send='" . $status . "'");
    while ($vendor_order = tep_db_fetch_array($vendor_data_query)) {
      $vendor_products[$index2] = array('Vid' => $vendor_order['vendors_id'],
                                        'Vname' => $vendor_order['vendors_name'],
                                        'Vemail' => $vendor_order['vendors_email'],
                                        'Vcontact' => $vendor_order['vendors_contact'],
                                        'Vaccount' => $vendor_order['account_number'],
                                        'Vstreet' => $vendor_order['vendor_street'],
                                        'Vcity' => $vendor_order['vendor_city'],
                                        'Vstate' => $vendor_order['vendor_state'],
                                        'Vzipcode' => $vendor_order['vendors_zipcode'],
                                        'Vcountry' => $vendor_order['vendor_country'],
                                        'Vaccount' => $vendor_order['account_number'],                               
                                        'Vinstructions' => $vendor_order['vendor_add_info'],
                                        'Vmodule' => $vendor_order['shipping_module'],                               
                                        'Vmethod' => $vendor_order['shipping_method']);
      if ($debug == 'yes') {
        echo 'The vendor query: ' . $vendor_order['vendors_id'] . '<br>';
      }
      $index = 0;
      $vendor_orders_products_query = tep_db_query("SELECT o.orders_id, o.orders_products_id, o.products_model, o.products_id, o.products_quantity, o.products_name, p.vendors_id,  p.vendors_prod_comments, p.vendors_prod_id, p.vendors_product_price 
                                                      from " . TABLE_ORDERS_PRODUCTS . " o, 
                                                           " . TABLE_PRODUCTS . " p 
                                                    WHERE p.vendors_id='" . (int)$vendor_order['vendors_id'] . "' 
                                                      and o.products_id=p.products_id 
                                                      and o.orders_id='" . $oID . "' order by o.products_name");
      while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query)) {
        $vendor_products[$index2]['vendor_orders_products'][$index] = array(
                                  'Pqty' => $vendor_orders_products['products_quantity'],
                                  'Pname' => $vendor_orders_products['products_name'],
                                  'Pmodel' => $vendor_orders_products['products_model'],
                                  'Pprice' => $vendor_orders_products['products_price'],
                                  'Pvendor_name' => $vendor_orders_products['vendors_name'],
                                  'Pcomments' => $vendor_orders_products['vendors_prod_comments'],
                                  'PVprod_id' => $vendor_orders_products['vendors_prod_id'],
                                  'PVprod_price' => $vendor_orders_products['vendors_product_price'],
                                  'spacer' => '-');
        if ($debug == 'yes') {
          echo 'The products query: ' . $vendor_orders_products['products_name'] . '<br>';
        }
        $subindex = 0;
        $vendor_attributes_query = tep_db_query("SELECT products_options, products_options_values, options_values_price, price_prefix 
                                                   from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " 
                                                 WHERE orders_id = '" . (int)$oID . "' 
                                                   and orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");
        if (tep_db_num_rows($vendor_attributes_query)) {
          while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query)) {
            $vendor_products[$index2]['vendor_orders_products'][$index]['vendor_attributes'][$subindex] = array('option' => $vendor_attributes['products_options'],
                                      'value' => $vendor_attributes['products_options_values'],
                                      'prefix' => $vendor_attributes['price_prefix'],
                                      'price' => $vendor_attributes['options_values_price']);
            $subindex++;
          }
        }
        $index++;
      }
      $index2++;
      // build the email
      // Get the delivery address
      $delivery_address_query = tep_db_query("SELECT DISTINCT delivery_company, delivery_name, delivery_street_address, delivery_city, delivery_state, delivery_postcode 
                                                from " . TABLE_ORDERS . " 
                                              WHERE order_display='1' and orders_id='" . $oID ."'") ;
      $vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);
      if ($debug == 'yes') {
        echo 'The number of vendors: ' . sizeof($vendor_products) . '<br>';
      }
      $email='';
      for ($l=0, $m=sizeof($vendor_products); $l<$m; $l++) {
        $vendor_country = tep_get_country_name($vendor_products[$l]['Vcountry']);
        $order_number= $oID;
        $vendors_id=$vendor_products[$l]['Vid'];
        $the_email=$vendor_products[$l]['Vemail'];
        $the_name=$vendor_products[$l]['Vname'];
        $the_contact=$vendor_products[$l]['Vcontact'];
        $email=  '<b>To: ' . $the_contact . '  <br>' . $the_name . '<br>' . $the_email . '<br>' .
        $vendor_products[$l]['Vstreet'] .'<br>' .
        $vendor_products[$l]['Vcity'] .', ' .
        $vendor_products[$l]['Vstate'] .'  ' .
        $vendor_products[$l]['Vzipcode'] . ' ' . $vendor_country . '<br>' . '<br>' . EMAIL_SEPARATOR . '<br>' . 'Special Comments or Instructions:  ' . $vendor_products[$l]['Vinstructions'] .'<br>' . '<br>' . EMAIL_SEPARATOR . '<br>' . 'From: ' . STORE_OWNER . '<br>' . STORE_NAME_ADDRESS . '<br>' . 'Accnt #: ' . $vendor_products[$l]['Vaccount'] . '<br>' . EMAIL_SEPARATOR . '<br>' . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . '<br>' .  EMAIL_SEPARATOR . '<br>' . '<br> Shipping Method: ' .  $vendor_products[$l]['Vmodule'] . ' -- '  .  $vendor_products[$l]['Vmethod'] .  '<br>' .  EMAIL_SEPARATOR . '<br>' . '<br>Dropship deliver to:<br>' .
        $vendor_delivery_address_list['delivery_company'] .'<br>' .
        $vendor_delivery_address_list['delivery_name'] .'<br>' .
        $vendor_delivery_address_list['delivery_street_address'] .'<br>' .
        $vendor_delivery_address_list['delivery_city'] .', ' .
        $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] . '<br><br>' ;
        $email = $email .  '<table width="75%" border=1 cellspacing="0" cellpadding="3"><tr><td>Qty:</td><td>Product Name:</td><td>Item Code/Number:</td><td>Product Model:</td><td>Per Unit Price:</td><td>Item Comments: </td></tr>';
        for ($i=0, $n=sizeof($vendor_products[$l]['vendor_orders_products']); $i<$n; $i++) {
          $product_attribs ='';
          if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {
            for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
              $product_attribs .= '&nbsp;&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' .  $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . '<br>';
            }
          }
          $email = $email . '<tr><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] .
                            '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . '<br>&nbsp;&nbsp;<i>Option<br> ' . $product_attribs .
                            '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id'] .
                            '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] .
                            '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_price'] . '</td><td>' .
          $vendor_products[$l]['vendor_orders_products'][$i]['Pcomments'] . '</b></td></tr>';
        }
      }
      $email = $email . '</table><br><HR><br>';
      tep_mail($the_name, $the_email, EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID ,  $email .  '<br>', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS)  ;
      $vendor_order_sent = true;
      if ($debug == 'yes') {
        echo 'The $email(including headers:<br>Vendor Email Addy' . $the_email . '<br>Vendor Name' . $the_name . '<br>Vendor Contact' . $the_contact . '<br>Body--<br>' . $email . '<br>';
      }
      if ($vendor_order_sent == true) {
        tep_db_query("update " . TABLE_ORDERS_SHIPPING . " set vendor_order_sent = 'yes' where orders_id = '" . (int)$oID . "'");
      }
    }
    return true;
  } else {
    return false;
  }
  }
  // multi-vendor shipping //eof  
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("SELECT orders_status_id, orders_status_name 
                                         from " . TABLE_ORDERS_STATUS . " 
                                       WHERE language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      
      case 'accept_order':
        include(DIR_FS_CATALOG_MODULES.'payment/paypal/admin/AcceptOrder.inc.php');
        break;

      case 'update_order':
        $oID = tep_db_prepare_input($_GET['oID']);
        $status = tep_db_prepare_input($_POST['status']);
        $comments = tep_db_prepare_input($_POST['comments']);
        
		        
        //Se;ect order status name
	     $ord_status_name = "SELECT orders_status_name FROM orders_status WHERE orders_status_id='$status'";
	     $qry_ord_stat = tep_db_query($ord_status_name);
	     $rs_stat_name = tep_db_fetch_array($qry_ord_stat); 
	     $status_name  = $rs_stat_name[orders_status_name];
        
        
        $order_updated = false;
        $check_status_query = tep_db_query("SELECT customers_name, customers_email_address, orders_status, date_purchased
                                            FROM " . TABLE_ORDERS . "
                                            WHERE order_display='1' and orders_id = " . (int)$oID);
        $check_status = tep_db_fetch_array($check_status_query);
        // always update date and time on order_status
        //check to see if can download status change
        if ( ($check_status['orders_status'] != $status) || tep_not_null($comments) || ($status == DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE) ) {
          // RCI update order
          echo $cre_RCI->get('orders', 'updateorder', false);
          
		  //Move below code to orders updated loop - Mar 27, 2012
		  //tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where order_display='1' and orders_id = '" . (int)$oID . "'");
		  
		  
          if ( $status == DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE ) {
            tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_maxdays = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_DAYS') . "', download_count = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_COUNT') . "' where orders_id = '" . (int)$oID . "'");
          }
          // multi-vendor shipping
          if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
            if (defined('MVS_VENDOR_EMAIL_WHEN') && (MVS_VENDOR_EMAIL_WHEN == 'Admin' || MVS_VENDOR_EMAIL_WHEN == 'Both')) {
              if (isset($status)) {
                $order_sent_query = tep_db_query("SELECT vendor_order_sent, vendors_id 
                                                    from " . TABLE_ORDERS_SHIPPING . " 
                                                  WHERE orders_id = '" . $oID . "'");
                while ($order_sent_data = tep_db_fetch_array($order_sent_query)) {
                  $order_sent_ckeck = $order_sent_data['vendor_order_sent'];
                  $vendors_id = $order_sent_data['vendors_id'];
                  if ($order_sent_ckeck == 'no') {
                    $vendor_order_sent = false;
                    vendors_email($vendors_id, $oID, $status, $vendor_order_sent);
                  }
                }
              }
            } 
          }
          // multi-vendor shipping //eof          
          $customer_notified = '0';
          if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $notify_comments = '';

            if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }

            /*$email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . 
              tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);*/
			  $email = sprintf(EMAIL_GREET_TEXT, $check_status['customers_name'])."\n".sprintf(EMAIL_ORDER_UPDATE_TEXT,$orders_status_array[$status]) . "\n\n" . '<b>'.EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "</b>\n" . EMAIL_TEXT_INVOICE_URL . ' ' . 
              tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n<b>" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "</b>\n\n" . $notify_comments . "\n\n" . EMAIL_SEPARATOR . "\n\n" .MODULE_PAYMENT_TRANSFER_TEXT_EMAIL_FOOTER . "\n\n" . EMAIL_TEXT_FOOTER_UPDATED;
			  
            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], sprintf(EMAIL_TEXT_SUBJECT,$oID), $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            $customer_notified = '1';
          }

          //tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");
		  tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments, admin_users_id) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "', '".(int)$_SESSION['login_id']."')");	
          $order_updated = true;
        }

        if ($order_updated == true) {
          
		  //Mar 27 2012
		  tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where order_display='1' and orders_id = '" . (int)$oID . "'");
		  
		  //XERO Integration STARTS		
		  $xero_status = tep_db_prepare_input($_POST['xero_status']);
		  $submit_xero = tep_db_prepare_input($_POST['submit_xero']);
		
		  if(isset($_POST['status']) && $_POST['status']==100006) {
			  if(($status==100006 && $xero_status==0) || ($status==100006 && isset($_POST['submit_xero']))) {
					//update due date
					$new_due_date = tep_update_duedate($oID);
					include("xero.php");
			  }
		  }		
		  //XERO Integration ENDS
		
		  $messageStack->add_session('search', SUCCESS_ORDER_UPDATED, 'success');
			
		  //************** Added For sugar  synchronization on  19th may 2011 ********************************* 
		  
			   //cal soap function to update status in crm
				 $comments_post = $_POST['comments'];
				 if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) 
					$notify_cus_new = 1; 
				 else
					$notify_cus_new = 0;
				 manage_crm_order_status($oID,$status,$status_name,$comments_post,$notify_cus_new);

		  //*************************************************************

			
		} else {
          $messageStack->add_session('search', WARNING_ORDER_NOT_UPDATED, 'warning');
        }        

        tep_redirect(tep_href_link(FILENAME_ORDERS, 'page=' . $_GET['page'] . '&oID=' . $_GET['oID'] . '&action=edit', 'SSL'));
        break;
        
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($_GET['oID']);
        tep_remove_order($oID, $_POST['restock']);

//*************************************************        
        //delete from Crm
        delete_quote_from_crm($oID);
        
//***********************************************       
        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')), 'SSL'));
        break;

      default :
        // RCI extend switch
        echo $cre_RCI->get('orders', 'actionswitch');
        break;        
    }
  }

  // enhanced search
  $order_exists = false;
  if (($action == 'edit') && isset($_GET['SoID'])) {
    if (is_numeric($_GET['SoID'])) {  // this must be an order id, so use the old format
      $_GET['oID'] = $_GET['SoID'];
      unset($_GET['SoID']);
    }
    // see if there are any matches
    $SoID = tep_db_prepare_input($_GET['SoID']);
    
    $sql = "SELECT orders_id
            FROM " . TABLE_ORDERS . "
            WHERE order_display='1' and customers_name LIKE '%" . $SoID . "%'
               OR LOWER( customers_email_address ) LIKE '%" . $SoID . "%'
               OR customers_company LIKE '%" . $SoID . "%'"; 
    $orders_query = tep_db_query($sql);
    $row_count = tep_db_num_rows($orders_query);
    if ($row_count < 1) {
      unset($_GET['SoID']);
      $messageStack->add('search', sprintf(ERROR_ORDER_DOES_NOT_EXIST, $SoID), 'error');
    } elseif ($row_count == 1) {
      // special case, only one, so go direct to edit
      $orders = tep_db_fetch_array($orders_query);
      $_GET['oID'] = $orders['orders_id'];
      $order_exists = true;
      unset($_GET['SoID']);
    } // if greater than 1, list all the matches
  }
  
  if (($action == 'edit') && isset($_GET['oID']) && $order_exists === false) {
    $oID = tep_db_prepare_input($_GET['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where order_display='1' and orders_id = '" . (int)$oID . "'");
    if (tep_db_num_rows($orders_query) > 0) {
      $order_exists = true;
    } else {
      unset($_GET['oID']);
      $messageStack->add('search', sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
  
  include(DIR_WS_CLASSES . 'order.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<link type="text/css" rel="StyleSheet" href="includes/helptip.css">
<script type="text/javascript" src="includes/javascript/helptip.js"></script>
<script type="text/javascript" src="includes/javascript/jquery.js"></script>
<script language="javascript"><!--
function popupWindow(url) {
/*  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
*/
 window.open(url,'popupWindow','toolbar=yes,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=500,screenX=150,screenY=150,top=150,left=150')

}
//--></script>

<style type="text/css">
	.pro_cost_header { color:#FFF; padding:5px; font-weight:bold; }
	.rbline { border-right:1px solid #E5E5E5; border-bottom:1px solid #E5E5E5; }
	.img-data img { vertical-align:middle; margin-right:1px; }
	.pdf-text { width:100px; }
</style>

<?php 
// rci for javascript include
echo $cre_RCI->get('orders', 'javascript');
?>

<script type="text/javascript">

	function checkAllStatus(status) {
	    sel = "";
		$(".checkbox").each( function() {
			$(this).attr("checked",status);			
			//sel_status			
			if($(this).attr("checked")==true) {
				curval = $(this).val();
				sel = sel + "_"+curval;
			}
			
		});
		
		$("#sel_status").val(sel);
	}	
	
	$(document).ready(function() {
		
		$(".checkbox").click(function() {			
			sel = "";					
			$(".checkbox").each( function() {				
				if($(this).attr("checked")==true) {
					curval = $(this).val();
					sel = sel + "_"+curval;
				}
			});
			
			$("#sel_status").val(sel);
			
			$(".checkbox").each( function() {	
			
				if($(this).attr("checked")==false) {
					$("#os_all").attr("checked",false);					
					return false;
				} else {
					$("#os_all").attr("checked",true);
					
				}				
	
			});
						
						
		});
		
		
		//For XERO
		$("input[name='submit_xero']").click(function(){
			if($(this).is(":checked")) {							
				$("#smt_order_update").show();
			} else {
								
				$("#smt_order_update").hide();
				
				if($("#hid_order_status").val()!=100006) {
					$("#smt_order_update").show();
				}
			}			
		});
		//For XERO
		
	});
	
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
    <tr>
    
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading">
			  	<?php 
			  	echo HEADING_TITLE; 
				if(isset($_GET['oID'])) {
					echo " # ".tep_db_input($oID);
				}
			  
			  ?>
			  </td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              <?php  //begin PayPal_Shopping_Cart_IPN V3.15 DMG;
    if  (strstr(strtolower($order->info['payment_method']), 'paypal') && (isset($_GET['referer'])) && ($_GET['referer'] == 'ipn')) { ?>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PAYPAL, tep_get_all_get_params(array('action','oID','referer')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
              <?php } else { ?>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action','referer')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
              <?php } //end PayPal_Shopping_Cart_IPN ?>
              <td align="right">
			  
				<?php 
					
					/*********** For invoice orders hide Edit option. June 18 2011 *******************/
					//if($order->info['orders_status_number']!=100006) {
						echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action')), 'SSL') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a> &nbsp; '; 
					//}
				
				?> 
				
				</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" valign="top"><b><?php echo '<u><a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $order->customer['id'].'&custnum='.$order->customer['id'], 'SSL') . '">' . ENTRY_CUSTOMER . '&nbsp;' . tep_image(DIR_WS_ICONS . 'magnifier.png', ICON_PREVIEW) . '</a></u>'; ?></b></td>
                    <td class="main">
					<?php 
						//echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); 							
						if($order->customer['company']!="") {
							echo '<u><a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $order->customer['id'].'&custnum='.$order->customer['id'], 'SSL') . '">' . $order->customer['company'] . '</a></u>' ."<br>";				
							echo $order->customer['name']."<br>";	
						} else {
							echo '<u><a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $order->customer['id'].'&custnum='.$order->customer['id'], 'SSL') . '">' . $order->customer['name'] . '</a></u>' ."<br>";	
						}
						echo $order->customer['street_address']."<br>";
						echo ($order->customer['suburb']=="")?"":$order->customer['suburb']."<br>";
						echo $order->customer['city']. ", " .$order->customer['state']. " " . $order->customer['postcode'] ."<br>";						
						echo $order->customer['country'];
						
					?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                    <td class="main"><?php echo $order->customer['telephone']; ?></td>
                  </tr>
                  <tr>
                    <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                    <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
                  </tr>
                  <tr>
                    <td class="main"><b><?php echo ENTRY_IPADDRESS; ?></b></td>
                    <td class="main"><?php echo $order->customer['ipaddy']; ?></td>
                  </tr>
                   <!--
				  <tr>
                    <td class="main"><b><?php echo ENTRY_IPISP; ?></b></td>
                    <td class="main"><?php echo $order->customer['ipisp']; ?></td>
                  </tr>
				  
				  <tr>
                    <td class="main"><b><?php echo "Macola #"; ?></b></td>
                    <td class="main"><?php echo $order->customer['macola_number']; ?></td>
                  </tr>
				  -->
				  <tr>
                    <td class="main"><b><?php echo ENTRY_CUSTOMER_NUMBER; ?></b></td>
                    <td class="main"><?php echo $order->customer['customer_number']; ?></td>
                  </tr>
				  <?php if(!empty($order->info['order_assigned_to'])) { ?>
				  <tr>
                    <td class="main"><b><?php echo "Sales Consultant: "; ?></b></td>
                    <td class="main"><?php echo $order->info['order_assigned_to']; ?></td>
                  </tr>
				  <?php } ?>
				  
				  <?php if(!empty($order->customer['customers_term'])) { ?>
				  <tr>
                    <td class="main"><b><?php echo "Term : "; ?></b></td>
                    <td class="main"><?php echo $order->customer['customers_term']; ?></td>
                  </tr>
				  <?php } ?>
				  
                </table></td>
              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                    <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
                  </tr>
                </table></td>
              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                    <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'); ?></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
      <?php
      // RCI code start
      echo $cre_RCI->get('orders', 'specialform');
      // RCI code eof
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
      
		<td>
			<table width="100%" align="center" border="0">
				<tr>
					<td width="50%">
					  
					  <table border="0" cellspacing="0" cellpadding="2">
						<!-- add purchase number # // -->
						<tr>
						  <td class="main"><b>
							<!-- Order # -->
							<?php echo "Purchase Number:"; ?></b></td>
						   <td class="main"><?php echo $order->customer['purchase_number']; ?></td>
						</tr>
						<!-- add Order # // -->
						<!--
						<tr>
						  <td class="main"><b>            
							<?php echo ORDER; ?></b></td>
						  <td class="main"><?php echo tep_db_input($oID); ?></td>
						</tr>
						-->
						<!-- add date/time // -->
						<tr>
							  <td class="main"><b>
								<!-- Order Date & Time -->
								<?php echo ORDER_DATE_TIME; ?></b></td>
							  <td class="main"><?php echo tep_date_aus_format($order->info['date_purchased'],"long"); ?></td>
							</tr>
							
							<tr>
							  <td class="main"><b>								
								<?php echo "Due Date: "; ?></b></td>
							  <td class="main"><?php echo tep_date_aus_format($order->info['due_date'],"long"); ?></td>
							</tr>
						
						<?php 
				  if(!function_exists('encrypt_num')){
					function encrypt_num($num) {
					  $rand1 = rand(100, 999);
					  $rand2 = rand(100, 999);
					  $key1 = ($num + $rand1) * $rand2;
					  $key2 = ($num + $rand2) * $rand1;
					  $result = $rand1.$rand2.$key1.$key2;
					  $rand1_len = chr(ord('A') + strlen($rand1));
					  $rand2_len = chr(ord('D') + strlen($rand2));
					  $key1_len  = chr(ord('G') + strlen($key1));
					  $rand1_pos = rand(0, floor(strlen($result)/3));
					  $result1 = substr_replace($result, $rand1_len, $rand1_pos, 0);
					  $rand2_pos = rand($rand1_pos + 1, floor(2*strlen($result1)/3));
					  $result2 = substr_replace($result1, $rand2_len, $rand2_pos, 0);
					  $key1_pos  = rand($rand2_pos + 1, strlen($result2)-1);
					  $result3 = substr_replace($result2, $key1_len, $key1_pos, 0);
					  //debug('Num='.$num.'; Rand1='.$rand1.'; Rand2='.$rand2.'; Key1='.$key1.'; Key2='.$key2.'; Result='.$result.'; Rand1Pos='.$rand1_pos.'; Result1='.$result1.'; Rand2Pos='.$rand2_pos.'; Result2='.$result2.'; Key1Pos='.$key1_pos.'; Result3='.$result3);
					  return $result3;    
					} 
				  }
				?>
						<?php if(file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'zip_bages' . DIRECTORY_SEPARATOR . 'order_'.$oID.'.zip')){?>
						<tr>
						  <td class="main"><b>Download link</b></td>
						  <td class="main"><a href="<?php echo DIR_WS_CATALOG . 'images/zip_bages/order_'.$oID.'.zip'; ?>">http://<?php echo DIR_WS_CATALOG . 'images/zip_bages/order_'.$oID.'.zip'; ?></a></td>
						</tr>
						<?php } ?>
						<?php  // begin PayPal_Shopping_Cart_IPN V3.15 DMG
					if (strstr(strtolower($order->info['payment_method']), 'paypal')) {
					  include(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/TransactionSummaryLogs.inc.php');
					}
				?>
						<tr>
						  <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
						  <td class="main"><?php echo $order->info['payment_method']; ?></td>
						</tr>
						<?php
							 if ($order->info['payment_method'] == 'Purchase Order') {
						?>
						<tr>
						  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
						</tr>
						<tr>
						
							<td class="main" valign="top" align="left"><b><?php echo TEXT_INFO_PO ?></b></td>
							<td>
								<table border="0" cellspacing="0" cellpadding="2">
								  <tr>
									<td class="main"><?php echo TEXT_INFO_NAME ?></td>
									<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
									<td class="main"><?php echo $order->info['account_name']; ?></td>							  
								  </tr>							  
								  <tr>
									<td class="main"><?php echo TEXT_INFO_AC_NR ?></td>
									<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
									<td class="main"><?php echo $order->info['account_number'] ; ?></td>
								  </tr>
								  <tr>
									<td class="main"><?php echo TEXT_INFO_PO_NR ?></td>
									<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
									<td class="main"><?php echo $order->info['po_number'] ; ?></td>
								  </tr>
								</table>
							</td>
							
						</tr>
						
						<?php
						} 
						  // RCI orders transaction
						  echo $cre_RCI->get('orders', 'transaction'); 
									  
						// multi-vendor shipping
						if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
						  echo '<tr><td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td></tr>' . "\n";
						  $orders_vendors_data_query = tep_db_query("select distinct ov.orders_id, ov.vendors_id, ov.vendor_order_sent, v.vendors_name from " . TABLE_ORDERS_SHIPPING . " ov, " . TABLE_VENDORS . " v where v.vendors_id=ov.vendors_id and orders_id='" . (int)$oID . "' group by vendors_id");
						  while ($orders_vendors_data=tep_db_fetch_array($orders_vendors_data_query)) {
							echo '<tr class="dataTableRow"><td class="dataTableContent" valign="top" align="left">Order Sent to ' .$orders_vendors_data['vendors_name'] . ':<b> ' . $orders_vendors_data['vendor_order_sent'] . '</b><br></td>';
						  }
						  echo '</tr>';
						}
						// multi-vendor shipping //eof    
						?>
					  </table>
					  <?php
					  require(DIR_WS_MODULES . 'afs_v1.0/algo_fraud_screener.php');
					  ?>
				   </td>
				   
				   <td width="50%">
				   		
						<?php
							$products_cost = tep_get_orders_products_costs((int)$oID); 
							
							//print_r($products_cost);		
							if($products_cost) {					
						?>
						
						<b>Products Costs (Ex GST) : $<?php echo $products_cost['total_costs']; ?></b><br><br>
						<table width="100%" align="center" border="0" style="border:1px solid #BFA080;" cellpadding="0" cellspacing="0">
							<tr bgcolor="#804101">
								<td width="47%" class="pro_cost_header rbline">Products name </td>
								<td width="8%" class="pro_cost_header rbline" align="center">QTY</td>
								<td colspan="2" class="pro_cost_header rbline" align="center">Labour</td>
								<td colspan="2" class="pro_cost_header rbline" align="center">Overhead</td>
								<td colspan="2" class="pro_cost_header rbline" align="center">Material</td>
							</tr>
							<tr>
							  <td class="rbline" height="24px">&nbsp;</td>
							  <td class="rbline">&nbsp;</td>
							  <td width="6%" align="center" class="rbline">Unit</td>
							  <td width="10%" align="center" bgcolor="#BFA080" class="rbline">Total</td>
							  <td width="5%" align="center" class="rbline">Unit</td>
							  <td width="9%" align="center" bgcolor="#BFA080" class="rbline">Total</td>
							  <td width="5%" align="center" class="rbline">Unit</td>
							  <td width="10%" align="center" bgcolor="#BFA080" class="rbline">Total</td>
  							</tr>
							<?php
								$labour_total =0; $labour_total = 0; $material_total = 0;
							
								foreach($products_cost as $product_cost=>$cost) {
																
								if(is_numeric($product_cost)) {
									
									$labour_total +=  ($cost['products_quantity'] * $cost['labour_cost']);
									$overhead_total +=  ($cost['products_quantity'] * $cost['overhead_cost']);
									$material_total += ($cost['products_quantity'] * $cost['material_cost']);
									
									?>
									<tr>
									  <td class="rbline" height="30px"><?php echo tep_get_orders_products_name($cost['orders_products_id'], $cost['orders_id']); ?></td>
									  <td class="rbline" align="center"><?php echo $cost['products_quantity']; ?></td>
									  <td align="center" class="rbline"><?php echo $cost['labour_cost']; ?></td>
									  <td align="center" bgcolor="#BFA080" class="rbline"><?php echo ($cost['products_quantity'] * $cost['labour_cost']); ?></td>
									  <td align="center" class="rbline"><?php echo $cost['overhead_cost']; ?></td>
									  <td align="center" bgcolor="#BFA080" class="rbline"><?php echo ($cost['products_quantity'] * $cost['overhead_cost']); ?></td>
									  <td align="center" class="rbline"><?php echo $cost['material_cost']; ?></td>
									  <td align="center" bgcolor="#BFA080" class="rbline"><?php echo ($cost['products_quantity'] * $cost['material_cost']); ?></td>
									</tr>								
									<?php 
								}
							} ?>
							
							<tr>
							  <td colspan="3" align="right" height="32px"><strong>Total: </strong> </td>
							  <td align="center" style="border:1px solid #EFEFEF; color:#804101; font-weight:bold;"><?php echo $labour_total; ?></td>
							  <td align="center">&nbsp;</td>
							  <td align="center" style="border:1px solid #EFEFEF; color:#804101;font-weight:bold;"><?php echo $overhead_total; ?></td>
							  <td align="center">&nbsp;</td>
							  <td align="center" style="border:1px solid #EFEFEF; color:#804101;font-weight:bold;"><?php echo $material_total; ?></td>
  							</tr>
						</table>
						<br><br>
						<?php } ?>	
						
				   </td>
				   
				 </tr>
				 
			</table>
			
		</td>
      
      </tr>
      
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <?php 
      // multi-vendor shipping
      if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
        require ('vendor_order_info.php'); 
      }
      // multi-vendor shipping //eof
      ?>
      <!-- Begin Products Listings Block -->
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent" colspan="2" align="center"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
              <!--<td class="dataTableHeadingContent"><?php //echo TABLE_HEADING_PRODUCTS_MODEL; ?></td> -->
			  <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_BASE_PRICE; ?></td>
			  <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_QUANTITY; ?></td>
              <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRICE_BREAK_EXCLUDING_TAX; ?></td>
			  <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TAX; ?></td>              
              <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRICE_BREAK_INCLUDING_TAX; ?></td>
              <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
              <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
            </tr>
            <?php


if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    for ($x=0, $y=sizeof($order->products[$i]);$x<$y; $x++) {
      if (isset($order->products[$i]['orders_products'][$x]['id']) && (int)$order->products[$i]['orders_products'][$x]['id'] > 0 ) {
        // Begin RMA Returns System
        $returns_check_query = tep_db_query("SELECT r.rma_value, rp.products_id FROM " . TABLE_RETURNS . " r, " . TABLE_RETURNS_PRODUCTS_DATA . " rp where r.returns_id = rp.returns_id and r.order_id = '" . $oID . "' and rp.products_id = '" .  (int)$order->products[$i]['orders_products'][$x]['id'] . "' ");

        if (!tep_db_num_rows($returns_check_query)) {
          if (isset($order->products[$i]['orders_products'][$x]['return']) && $order->products[$i]['orders_products'][$x]['return'] != '1') {
            //Hided June 16, 2011
			//$return_link = '<a href="' . tep_href_link(FILENAME_RETURN, 'order_id=' . $oID . '&products_id=' . ((isset($order->products[$i]['orders_products'][$x]['id']) ? (int)$order->products[$i]['orders_products'][$x]['id'] : 0)), 'SSL') . '"><u>' . '<font color="818180">Schedule Return</font>' .'</a></u>';
			$return_link = '';
          }
          // Don't show Return link if order is still pending or processing
          // You can change this or comment it out as best fits your store configuration
          if (($order->info['orders_status'] == 'Pending') OR ($order->info['orders_status'] == 'Processing')) {
            $return_link = '';
          }
        } else {
          $returns = tep_db_fetch_array($returns_check_query);
          $return_link = '<a href=' . tep_href_link(FILENAME_RETURNS, 'cID=' . $returns['rma_value'], 'SSL') . '><font color=red><b><i>Returns</b></i></font></a>';
        }
        // End RMA Returns System
      
        echo '          <tr class="dataTableRow">' . "\n" .
             '            <td class="dataTableContent" valign="top" align="center">' . $order->products[$i]['orders_products'][$x]['qty'] . '&nbsp;x</td>' . "\n" .
             '            <td class="dataTableContent" valign="top">' . $order->products[$i]['orders_products'][$x]['name'] . '&nbsp;&nbsp;' . (isset($return_link) ? $return_link : '');

        if (isset($order->products[$i]['orders_products'][$x]['attributes']) && (sizeof($order->products[$i]['orders_products'][$x]['attributes']) > 0)) {
          for ($j = 0, $k = sizeof($order->products[$i]['orders_products'][$x]['attributes']); $j < $k; $j++) {
            echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['orders_products'][$x]['attributes'][$j]['option'] . ': ' . $order->products[$i]['orders_products'][$x]['attributes'][$j]['value'];
            if ($order->products[$i]['orders_products'][$x]['attributes'][$j]['price'] != '0') echo ' (' .    
              $order->products[$i]['orders_products'][$x]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['orders_products'][$x]['attributes'][$j]['price'] * $order->products[$i]['orders_products'][$x]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
            echo '</i></small></nobr>';
          }
        }

        echo '            </td>' . "\n" .            
			 '            <td class="dataTableContent" valign="top" align="center">' . $currencies->format($order->products[$i]['product_original_final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .  
			  '            <td class="dataTableContent" align="center" valign="top">' . $order->products[$i]['qty'] . '</td>' . "\n" . 		            
             '            <td class="dataTableContent" align="center" valign="top">' . $currencies->format($order->products[$i]['orders_products'][$x]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
			   '            <td class="dataTableContent" align="center" valign="top">' . tep_display_tax_value($order->products[$i]['orders_products'][$x]['tax']) . '%</td>' . "\n" .
             '            <td class="dataTableContent" align="center" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['orders_products'][$x]['final_price'], $order->products[$i]['orders_products'][$x]['tax'], true), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
             '            <td class="dataTableContent" align="right" valign="top">' . $currencies->format($order->products[$i]['orders_products'][$x]['final_price'] * $order->products[$i]['orders_products'][$x]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
             '            <td class="dataTableContent" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['orders_products'][$x]['final_price'], $order->products[$i]['orders_products'][$x]['tax'], true) * $order->products[$i]['orders_products'][$x]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
        echo '          </tr>' . "\n";
      }
    }
  }
} else {
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    // Begin RMA Returns System
    $returns_check_query = tep_db_query("SELECT r.rma_value, rp.products_id FROM " . TABLE_RETURNS . " r, " . TABLE_RETURNS_PRODUCTS_DATA . " rp where r.returns_id = rp.returns_id and r.order_id = '" . $oID . "' and rp.products_id = '" . (isset($order->products[$i]['id']) ? (int)$order->products[$i]['id'] : 0) . "' ");

    if (!tep_db_num_rows($returns_check_query)) {
      if (isset($order->products[$i]['return']) && $order->products[$i]['return'] != '1') {
        //Hided June 16, 2011
		//$return_link = '<a href="' . tep_href_link(FILENAME_RETURN, 'order_id=' . $oID . '&products_id=' . ((isset($order->products[$i]['id']) ? (int)$order->products[$i]['id'] : 0)), 'SSL') . '"><u>' . '<font color="818180">Schedule Return</font>' .'</a></u>';
		$return_link = "";
      }
      // Don't show Return link if order is still pending or processing
      // You can change this or comment it out as best fits your store configuration
      if (($order->info['orders_status'] == 'Pending') OR ($order->info['orders_status'] == 'Processing')) {
        $return_link = '';
      }
    } else {
      $returns = tep_db_fetch_array($returns_check_query);
      $return_link = '<a href=' . tep_href_link(FILENAME_RETURNS, 'cID=' . $returns['rma_value'], 'SSL') . '><font color=red><b><i>Returns</b></i></font></a>';
    }
    // End RMA Returns System
    
    echo '          <tr class="dataTableRow">' . "\n" .
         '            <td class="dataTableContent" valign="top" align="center">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
         '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'] . '&nbsp;&nbsp;' . (isset($return_link) ? $return_link : '');

    if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
      for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
        echo '<br><nobr><small>&nbsp;<i> - ' . ($order->products[$i]['attributes'][$j]['option'] == '' ? $order->products[$i]['attributes'][$j]['option_name'] : $order->products[$i]['attributes'][$j]['option']) . ': ' . $order->products[$i]['attributes'][$j]['value'];
        if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
        echo '</i></small></nobr>';
      }
    }
    
    $prod_img_query = tep_db_query("SELECT `products_image`, badge_data, products_purchase_number FROM `products` WHERE `products_id`='".$order->products[$i]['id']."'");
    $prod_img = tep_db_fetch_array($prod_img_query); 
     
    if ($prod_img['badge_data']) {
      require_once(dirname(dirname(__FILE__)).'/templates/Ajparkes1/bd/badge_desc.php');
      $badge = new Badge($prod_img['badge_data']);
       
      echo '<br />';
	   echo '<img src="' . DIR_WS_CATALOG . 'images/'.$prod_img['products_image'].'" />'.$badge->description();
     /* echo '<img src="' . DIR_WS_CATALOG . 'images/'.$prod_img['products_image'].'" /><br /><br /><b>Purchase Number </b> '.$prod_img['products_purchase_number'].'<br /><br />'.$badge->description();*/
    }
	
	//Modified Sep, 09, 2010
	if($order->products[$i]['product_original_final_price'] > 0) {
		$product_original_price = $order->products[$i]['product_original_final_price'];
	} else {
		$product_original_price = $order->products[$i]['final_price'];
	}
	//this is for adding product description - June 14, 2011
	if($order->products[$i]['desc']!="") {
		echo '<br />';		
		echo nl2br($order->products[$i]['desc']);
	}
	
	
    echo '            </td>' . "\n" .
         '            <td class="dataTableContent" align="center" valign="top">' . $currencies->format($product_original_price, true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" . 
		  '            <td class="dataTableContent" align="right" valign="top">' . $order->products[$i]['qty'] . '</td>' . "\n" . 		        
         '            <td class="dataTableContent" align="center" valign="top">' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
		  '            <td class="dataTableContent" align="center" valign="top">' . tep_display_tax_value($order->products[$i]['tax_rate']) . '%</td>' . "\n" .
         '            <td class="dataTableContent" align="center" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate'], true), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
         '            <td class="dataTableContent" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
         '            <td class="dataTableContent" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate'], true) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n";
    echo '          </tr>' . "\n";
    
	  
/*$without_TAX 	= $currencies->unFormat($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);
$with_TAX 	= $currencies->unFormat(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax_rate'], true) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);
$gst_amount[] 	= $with_TAX - $without_TAX;*/

  }
}

?>
            <tr>
              <td align="center" colspan="5">
					<?php
					if($order->info["price_break_amount"]>0) {
						?>
						<p style="font-weight:bold;">
							AJ Parkes Price Break Discount : you save $<?php echo number_format($order->info["price_break_amount"],2); ?>	<br><br></p>
					<?php } ?>
			  </td>
			  <td align="right" colspan="4"><table border="0" cellspacing="0" cellpadding="2">
                  <?php
                  /*$n = sizeof($order->totals);
                  $GST_Amount = array_sum($gst_amount);
		  if(!empty($GST_Amount) || $GST_Amount > 0){
			$GST_Total  = $currencies->format($GST_Amount, true, $order->info['currency'], $order->info['currency_value']);
			$order->totals[$n]["title"]= 'GST Tax:';
			$order->totals[$n]["text"] = $GST_Total;
                  }*/
                  
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
                </table></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
            <tr>
              <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
              <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
              <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
			  <td class="smallText" align="center"><b><?php echo TABLE_HEADING_USERS; ?></b></td>
			  <td class="smallText" align="center"><b><?php echo "CRM User"; ?></b></td>
              <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
            </tr>
            <?php
    $orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
	  	
		if(is_numeric($orders_history['admin_users_id'])) {
			//get admin info
			$get_admin_info = tep_db_query("select admin_firstname, admin_lastname from " . TABLE_ADMIN . " where admin_id = '" . tep_db_input($orders_history['admin_users_id']) . "'");
			$admin_info = tep_db_fetch_array($get_admin_info);	
			$user_to_display = $admin_info['admin_firstname'] . " " . $admin_info['admin_lastname'];
		} else {
			$user_to_display = $orders_history['admin_users_id'];
		}
		
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";
		
		echo '            <td class="smallText">' . $user_to_display . '</td>' . "\n";		
        
		echo '            <td class="smallText">' . $orders_history['crm_username'] . '</td>' . "\n";		
		
        echo '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
          </table></td>
      </tr>
      <tr>
        <td class="main"><br>
          <b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=update_order', 'post', '', 'SSL'); ?>
        <td class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status_number']); ?></td>
                  </tr>
                  <tr>
                    <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
                    <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
                  </tr>
				  
				   <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
				  </tr>
	  
				   <tr>
                    <td class="main" valign="middle">
						<b><?php echo "Xero Status"; ?> : 
						<?php 
							if($order->info["xero"]==1) {
								echo "<img src='images/xero-success.png' alt='ON' title='ON' />";
								echo tep_draw_hidden_field('xero_status', '1');
							} else {
								echo "<img src='images/xero-failed.png' alt='OFF' title='OFF' />";
								echo tep_draw_hidden_field('xero_status', '0');
							}
						?>
						
					</td>
                    <td class="main">
							<?php 
								if($order->info["xero"]==0) {
									echo "<b>Submit to XERO : </b>" . tep_draw_checkbox_field('submit_xero', '', false);
								}
							?>
					</td>
                  </tr>
				  
                </table></td>
              <td valign="top">
			  	<?php 
					//OLD
					//********* Update option hided for Invoice Orders Start - June 18 2011 ***************/
					//if($order->info['orders_status_number']!=100006) {
					//	echo tep_image_submit('button_update.gif', IMAGE_UPDATE); 
					//} 
					/*********** Update option hided for Invoice Orders End - June 18 2011 ***************/
					//OLD
					
					//XERO
					 
						//Update option hided for Invoice Orders Start - June 18 2011
						
						echo tep_draw_hidden_field('hid_order_status', $order->info['orders_status_number'], ' id="hid_order_status" ');
						
						
						if($order->info['orders_status_number']!=100006) {
							$hide_button = "";						
						} else {
							$hide_button = " style='display:none;' ";						
						} 					
						echo tep_image_submit('button_update.gif', IMAGE_UPDATE, $hide_button. 'id="smt_order_update"'); 
						//Update option hided for Invoice Orders End - June 18 2011
				
					//XERO
				?>
			  </td>
            </tr>
            <?php
          // RCI start
          echo $cre_RCI->get('orders', 'bottom');
          // RCI eof
          ?>
          </table></td>
        </form>
      </tr>
      <tr>
        <?php  
        // RCI start
        $buttons_bottom = $cre_RCI->get('orders', 'buttonsbottom');
        //Begin PayPal IPN V3.15 DMG (I improvised here.)
        $oscid = '&' . tep_session_name() . '=' . tep_session_id();
        if (strstr(strtolower($order->info['payment_method']), 'paypal') && isset($_GET['referer']) && $_GET['referer'] == 'ipn'){
          ?>
        <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PAYPAL, tep_get_all_get_params(array('action')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a><a href="javascript:popupWindow(\'' . (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_INVOICE) . '?' . (tep_get_all_get_params(array('oID')) . 'oID=' . $_GET['oID']) . $oscid . '\')">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a><a href="javascript:popupWindow(\'' . (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_PACKINGSLIP) . '?' . (tep_get_all_get_params(array('oID')) . 'oID=' . $_GET['oID']) . $oscid . '\')">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>' . $buttons_bottom; ?></td>
        <?php
        } else { //not paypal
          ?>
        <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a><a href="javascript:popupWindow(\'' . (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_INVOICE) . '?' . (tep_get_all_get_params(array('oID')) . 'oID=' . $_GET['oID']) . $oscid . '\')">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a><a href="javascript:popupWindow(\'' . (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_PACKINGSLIP) . '?' . (tep_get_all_get_params(array('oID')) . 'oID=' . $_GET['oID']) . $oscid . '\')">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>' . $buttons_bottom; ?></td>
        <?php
        }  //end PapPal IPN V3.15
        ?>
      </tr>
      <?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <?php 
                  echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get', '', 'SSL'); 
                  tep_hide_session_id();
                ?>
                    <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('SoID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit'); ?></td>
                    </form>
                  </tr>
                  <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, '', 'get', '', 'SSL'); ?>
                    <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), (isset($_GET['status']) ? (int)$_GET['status']: ''), 'onChange="this.form.submit();"');
                  tep_hide_session_id(); ?> </td>
                    </form>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
      <?php
      // RCI start
      echo $cre_RCI->get('orders', 'listingtop');
      // RCI eof
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                  <?php
    $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
    if (isset($_GET['SoID'])) {
      $oscid .= '&SoID=' . $_GET['SoID'];
    }
    
    $HEADING_CUSTOMERS = TABLE_HEADING_CUSTOMERS;
    $HEADING_CUSTOMERS .= '<a href="' . $PHP_SELF . '?sort=customer&order=ascending' . $oscid . '">';
    $HEADING_CUSTOMERS .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
    $HEADING_CUSTOMERS .= '<a href="' . $PHP_SELF . '?sort=customer&order=decending' . $oscid . '">';
    $HEADING_CUSTOMERS .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
    $HEADING_DATE_PURCHASED = TABLE_HEADING_DATE_PURCHASED;
    $HEADING_DATE_PURCHASED .= '<a href="' . $PHP_SELF . '?sort=date&order=ascending' . $oscid . '">';
    $HEADING_DATE_PURCHASED .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
    $HEADING_DATE_PURCHASED .= '<a href="' . $PHP_SELF . '?sort=date&order=decending' . $oscid . '">';
    $HEADING_DATE_PURCHASED .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" width="8%"><?php echo TABLE_HEADING_ORDERID; ?></td>
                    <td class="dataTableHeadingContent" width="16%"><?php echo $HEADING_CUSTOMERS; ?></td>
                    <td class="dataTableHeadingContent" width="8%" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                    <td class="dataTableHeadingContent" align="center" width="16%"><?php echo TABLE_HEADING_COMPANY; ?></td>
                    <td class="dataTableHeadingContent" align="center" width="10%"><?php echo TABLE_HEADING_PAYMENT; ?></td>
                    <td class="dataTableHeadingContent" width="16%" align="center"><?php echo $HEADING_DATE_PURCHASED; ?></td>
					<td class="dataTableHeadingContent" align="center" width="6%">&nbsp;</td>
					<td class="dataTableHeadingContent" align="center" width="6%"><img src="images/logo-xero.png" alt="Xero" /></td>
                    <td class="dataTableHeadingContent" align="center" width="10%"><?php echo TABLE_HEADING_STATUS; ?></td>
                    <td class="dataTableHeadingContent" align="right" width="6%"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                  </tr>
                  <?php
    $sortorder = 'order by ';
    $sort = (isset($_GET["sort"]) ? $_GET["sort"] : '');
    if  ($sort == 'customer') {
      if ($_GET["order"] == 'ascending') {
        $sortorder .= 'o.customers_name  asc, ';
      } else {
        $sortorder .= 'o.customers_name desc, ';
      }
    } elseif ($sort == 'date') {
      if ($_GET["order"] == 'ascending') {
        $sortorder .= 'o.date_purchased  asc, ';
      } else {
        $sortorder .= 'o.date_purchased desc, ';
      }
    }
    $sortorder .= 'o.orders_id DESC';
    if (isset($_GET['cID'])) {
      $cID = tep_db_prepare_input($_GET['cID']);
     $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_email_address, o.customers_company, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.orders_status, o.xero, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.order_display='1' and o.customers_id = '" . (int)$cID . "' and ot.orders_id = o.orders_id and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";
    } elseif (isset($_GET['status']) && (tep_not_null($_GET['status']))) {
      $status = tep_db_prepare_input($_GET['status']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_email_address, o.customers_company, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.orders_status, o.xero, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.order_display='1' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.orders_status_id = '" . (int)$status . "' order by o.orders_id DESC";
    } 
	
	//For Orders status with check boxes - Start June 17 2011
	elseif(isset($_GET['sel_status'])) {
		
		$status_array = explode("_",$_GET['sel_status']);
		
		$orders_query_raw = "select o.orders_id, o.customers_name, o.customers_email_address, o.customers_company, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.orders_status, o.xero, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.order_display='1' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "'";
		
		$orders_status_string = "";
		
		foreach($status_array as $status_keys=>$status) {
			if($status!="") {
				$orders_status_string .= " s.orders_status_id = '" . (int)$status . "' OR ";
			}
		}
		
		if($orders_status_string!="") {
			$orders_query_raw .= " AND (" . substr($orders_status_string, 0, -3) . ") ";
		}
		$orders_query_raw .= " order by o.orders_id DESC";
	} 
	//For Orders status with check boxes - End
	
	elseif (isset($_GET['SoID'])) {
      $SoID = tep_db_prepare_input($_GET['SoID']);
      $orders_query_raw = "SELECT o.orders_id, o.customers_name, o.customers_email_address, o.customers_company, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.orders_status, o.xero, s.orders_status_name
                           FROM " . TABLE_ORDERS . " o,
                                " . TABLE_ORDERS_STATUS . " s
                           WHERE o.order_display='1' and o.orders_status = s.orders_status_id
                             AND s.language_id = " . (int)$languages_id . "
                             AND (o.customers_name LIKE '%" . $SoID . "%'
                                  OR LOWER( o.customers_email_address ) LIKE '%" . $SoID . "%'
                                  OR o.customers_company LIKE '%" . $SoID . "%'
                                 ) " . $sortorder;
    } else {
      $orders_query_raw = "SELECT o.orders_id, o.customers_name, o.customers_email_address, o.customers_company, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, o.orders_status, o.xero, s.orders_status_name
                           FROM " . TABLE_ORDERS . " o,
                                " . TABLE_ORDERS_STATUS . " s
                           WHERE o.order_display='1' and o.orders_status = s.orders_status_id
                             AND s.language_id = " . (int)$languages_id . "
                           " . $sortorder;
    }
    $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
      unset($order_total1);
      $order_total1 = TEXT_INFO_ABANDONDED;
      $orders_total_query_raw = "select ot.text as order_total from " . TABLE_ORDERS_TOTAL . " ot where  ot.orders_id = '" . $orders['orders_id'] . "' and ot.class = 'ot_total' ";
      $orders_query_total = tep_db_query($orders_total_query_raw);
      while ($orders1 = tep_db_fetch_array($orders_query_total)) {
        $order_total1 = $orders1['order_total'];
        if (!$order_total1){
          $order_total1 = TEXT_INFO_ABANDONDED;
        }
      }
    
     // print_r($orders);
      
      if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
      }
      // RCO start
      if ($cre_RCO->get('orders', 'listingselect') !== true) {
        if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
          echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit', 'SSL') . '\'">' . "\n";
        } else {
          echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id'], 'SSL') . '\'">' . "\n";
        }
?>
                  <td class="dataTableContent" align="left"><?php
        echo '<b>' . $orders['orders_id'] . '</b>';
        $products = "";
        $products_query = tep_db_query("SELECT orders_products_id, products_name, products_quantity 
                                        from " . TABLE_ORDERS_PRODUCTS . " 
                                        WHERE orders_id = '" . tep_db_input($orders['orders_id']) . "' ");
        while ($products_rows = tep_db_fetch_array($products_query)) {
          $products .= ($products_rows["products_quantity"]) . "x " . (tep_html_noquote($products_rows["products_name"])) . "<br>";
          $result_attributes = tep_db_query("SELECT products_options, products_options_values 
                                             from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " 
                                             WHERE orders_id = '" . tep_db_input($orders['orders_id']). "' 
                                               and orders_products_id = '" . $products_rows["orders_products_id"] . "' 
                                             ORDER BY products_options");
          while ($row_attributes = tep_db_fetch_array($result_attributes)) {
            $products .= " - " . (tep_html_noquote($row_attributes["products_options"])) . ": " . (tep_html_noquote($row_attributes["products_options_values"])) . "<br>";
          }
        }
?>
                      <img src="images/icons/comment2.gif" onmouseover="showhint('<?php echo '' . $products . ''; ?>', this, event, '300px'); return false" align="top" border="0"> </td>
                    <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit', 'SSL') . '">' . tep_image(DIR_WS_ICONS . 'magnifier.png', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>
                    <td class="dataTableContent" align="right"><?php echo strip_tags($order_total1); ?></td>
                          <td class="dataTableContent" align="center"><?php echo  $orders['customers_company'];  ?></td>
                      <td class="dataTableContent" align="center"><?php echo  $orders['payment_method'];  ?></td>
                    <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
					
					<td class="dataTableContent img-data" align="center">
					<?php 
						//check this orders contains design or not
						$artwork_qry = tep_db_query("SELECT artwork_id,customers_id from artwork where orders_id='".$orders['orders_id']."'");
						if(tep_db_num_rows($artwork_qry)>0) { 
							$artwork_arr = tep_db_fetch_array($artwork_qry);
							$pending = tep_db_fetch_array(tep_db_query("SELECT count(artwork_id) as pcnt from artwork where orders_id='".$orders['orders_id']."' and artwork_status='pending'")); 
							$revision = tep_db_fetch_array(tep_db_query("SELECT count(artwork_id) as rcnt from artwork where orders_id='".$orders['orders_id']."' and artwork_status='revision'")); 
							$approved = tep_db_fetch_array(tep_db_query("SELECT count(artwork_id) as acnt from artwork where orders_id='".$orders['orders_id']."' and (artwork_status='approved' OR artwork_status='approve')"));
							 
							 if($pending['pcnt']>0) {
							 	$design_img = "artwork-pending.png";
							 } else if($revision['rcnt']>0) {
							 	$design_img = "artwork-revision.png";
							 } else if($approved['acnt']>0) {
							 	$design_img = "artwork-approved.png";
							 }
							
							echo '<a href="' . tep_href_link("artworks.php", 'cID=' . $artwork_arr['customers_id'], 'SSL') . '">' . tep_image(DIR_WS_IMAGES . "artwork-icon.png", "Artwork") . tep_image(DIR_WS_IMAGES . $design_img, "Artwork") . '</a>';
						}
					?>
					
					</td>
					<td class="dataTableContent" align="center">
						<?php 
							if($orders['xero']==1) {
								echo '<img src="images/xero-success.png" alt="On" />';
							} else {
								echo '<img src="images/xero-failed.png" alt="Off" />';
							}
						?>
					</td>
                    <td class="dataTableContent" align="center"><?php echo $orders['orders_status_name']; ?></td>
                    <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id'], 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>
                      &nbsp;</td>
                  </tr>
                  <?php
      }  // RCO eof
    }
?>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
                  <tr>
                    <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                          <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                          <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                          <?php
                    // RCI code start
                    echo $cre_RCI->get('orders', 'listingbottom');
                    // RCI code eof
?>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
              <?php
    $heading = array();
    $contents = array();

    switch ($action) {
      case 'delete':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');
        $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm', 'post' , '', 'SSL'));
        $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br>');
        $contents[] = array('text' => TEXT_INFO_DELETE_DATA . '&nbsp;' . $oInfo->customers_name . '<br>');
        $contents[] = array('text' => TEXT_INFO_DELETE_DATA_OID . '&nbsp;<b>' . $oInfo->orders_id . '</b><br>');
        $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id, 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
        break;
		
		case 'pdf':
        
		//Updated June 29 2011 - //For Extra Customer notification
		 
		/*$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_SEND_INVOICE_ORDER . '</b>');
        $contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS_INVOICE_PDF, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=pdf', 'post' , '', 'SSL'));
        $contents[] = array('text' => TEXT_INFO_SEND_INVOICE_INTRO . '<br><br>');
        $contents[] = array('text' => TEXT_INFO_DELETE_DATA . '&nbsp;' . $oInfo->customers_name . '<br>');
        $contents[] = array('text' => TEXT_INFO_DELETE_DATA_OID . '&nbsp;<b>' . $oInfo->orders_id . '</b><br>');
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id, 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_SEND_INVOICE));*/
		
			$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_SEND_INVOICE_ORDER . '</b>');
			$contents = array('form' => tep_draw_form('orders', FILENAME_ORDERS_INVOICE_PDF, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=pdf', 'post' , '', 'SSL'));
			$contents[] = array('text' => TEXT_INFO_SEND_INVOICE_INTRO . '<br><br>');
			$contents[] = array('text' => TEXT_INFO_DELETE_DATA . '&nbsp;' . $oInfo->customers_name . '<br>');
			$contents[] = array('text' => TEXT_INFO_DELETE_DATA_OID . '&nbsp;<b>' . $oInfo->orders_id . '</b><br><br>');
			
			//For Extra Customer notification
			$created_admin = tep_get_admin_details((int)$_SESSION['login_id']);			
			$contents[] = array('text' => "<b>". TEXT_INFO_SEND_INVOICE_FROM .'&nbsp;'. $created_admin['admin_email_address'] . tep_draw_hidden_field('pdf_from', $created_admin['admin_email_address'], '') . '</b><br>');
			$contents[] = array('text' => "<b>". TEXT_INFO_SEND_INVOICE_TO .'&nbsp;&nbsp;&nbsp;'. $oInfo->customers_email_address . '</b><br>');
			$contents[] = array('text' => "<b>". TEXT_INFO_SEND_INVOICE_CC .'&nbsp;&nbsp;&nbsp;</b>'. tep_draw_input_field('pdf_cc', '', 'size="20"') . '<br>');
			$contents[] = array('text' => "<b>". TEXT_INFO_SEND_INVOICE_BCC .'</b>&nbsp;'. tep_draw_input_field('pdf_bcc', '', 'size="20"') .'<br><br>');
			$contents[] = array('text' => "<b>". TEXT_INFO_SEND_INVOICE_NOTE_TITLE . '&nbsp;</b><br>' . TEXT_INFO_SEND_INVOICE_NOTE . '<br><br>');
						
			$contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id, 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_SEND_INVOICE));
        break;

      default:
        if (isset($oInfo) && is_object($oInfo)) {
          $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . $oInfo->customers_name . '</b>');  
          // RCO start
          if ($cre_RCO->get('orders', 'sidebarbuttons') !== true) {  
            $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit', 'SSL') . '">' . tep_image_button('button_edit_status.gif', IMAGE_EDIT_STATUS) . '</a>');
			/*<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete', 'SSL') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>'); */
           
		   /********** Edit option hided for Invoice Orders Start - June 18 2011 **************/
			
			/*if($oInfo->orders_status==100006) {
				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $oInfo->orders_id, 'SSL') . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> ');
			} else {*/
				$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('oID', 'action')) .'oID=' . $oInfo->orders_id, 'SSL'). '">' . tep_image_button('button_edit_order.gif', IMAGE_EDIT_ORDER) . '</a><a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $oInfo->orders_id, 'SSL') . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> ');	
			//}
			
			/********** Edit option hided for Invoice Orders End - June 18 2011 ***********/
            
			$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oInfo->orders_id, 'SSL') . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=pdf', 'SSL') . '">' . tep_image_button('button_delete.gif', IMAGE_SEND_INVOICE) . '</a>');
          
		  }
          // RCO eof        
          // RCI sidebar buttons
          $returned_rci = $cre_RCI->get('orders', 'sidebarbuttons');
          $contents[] = array('align' => 'center', 'text' => $returned_rci);
          $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' <b>' . tep_date_short($oInfo->date_purchased) . '</b>');  
          if (tep_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' <b>' . tep_date_short($oInfo->last_modified) . '</b>');
          $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' <b>'  . $oInfo->payment_method . '</b>');
         
		 //Uncomment below set of lines to show DELETE button
		  /* 
		  //DELETE Button hided - Starts 
			$contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete', 'SSL') . '">' . tep_image('images/button-delete.png', IMAGE_DELETE) . '</a>');		 
		  // DELETE button hide - ENDS
		  */
		  
		  // multi-vendor shipping
          if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
            $orders_vendors_data_query = tep_db_query("select distinct ov.orders_id, ov.vendors_id, ov.vendor_order_sent, v.vendors_name from " . TABLE_ORDERS_SHIPPING . " ov, " . TABLE_VENDORS . " v where v.vendors_id=ov.vendors_id and orders_id='" . $oInfo->orders_id . "' group by vendors_id");
            while ($orders_vendors_data=tep_db_fetch_array($orders_vendors_data_query)) {
              $contents[] = array('text' => VENDOR_ORDER_SENT . '<b>' . $orders_vendors_data['vendors_name'] . '</b>:<b> ' . $orders_vendors_data['vendor_order_sent'] . '</b><br>');
            }
          }
          // multi-vendor shipping //eof          
          // RCI sidebar bottom
          $returned_rci = $cre_RCI->get('orders', 'sidebarbottom');
          $contents[] = array('text' => $returned_rci);
        }
        break;
    }
    
	/*if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";
      $box = new box;
      echo $box->infoBox($heading, $contents);
      echo '            </td>' . "\n";
    }*/ //hided to add search panel as below
	
      echo '            <td width="25%" valign="top">' . "\n";
      
	  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
	  	$box = new box;
      	echo $box->infoBox($heading, $contents);
	  }
	  
	  //For orders status in check boxes - Start
	  echo '<br><table width="100%" border="0" style="margin:3px;">
	  			<tr>
					<td class="info-box-head" style="color:#FFF;"><b>Select by Status: </b></td>
				</tr>
				<tr>
					<td class="info-box-body" >';
	  ?>		  				
					
					<div id="sr_status">
						<p>
						<table align="center" width="100%" border="0">
							
							<tr>
								<td><input type="checkbox" id="os_all" name="os_all" onclick="checkAllStatus(this.checked)" <?php echo (isset($_GET['os_all']))? "checked":""; ?>></td>
								<td>All</td>						
						
						<?php 						
						$statusQuery = tep_db_query("select * from orders_status");
						$i=1;
						while ($status_arr = tep_db_fetch_array($statusQuery)) {
							$status_arra = explode("_",$_GET['sel_status']);
							if (in_array($status_arr["orders_status_id"], $status_arra)) {
								$checked_status = " checked";
							} else { 
								$checked_status = ""; 
							}							
							
							echo '<td valign="top"><input type="checkbox" class="checkbox" name="os[]" value="'.$status_arr["orders_status_id"].'" '.$checked_status.'></td><td>'.$status_arr["orders_status_name"]."</td>";					
							$i++;
							if(($i%2)==0) echo "</tr>";
                         }
						?> 
						   <tr>
						   	  <td colspan="4" align="center">
								
								<?php 
									echo tep_draw_form('multiOrderStatus', FILENAME_ORDERS, '', 'get', '', 'SSL'); 
									if (isset($_GET[tep_session_name()])) {
									  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
									}    
									echo '<input type="hidden" name="sel_status" id="sel_status">';
									echo tep_image_submit('button_submit.gif', "Submit"); 
								?>
									</form>			
							  </td>
						   </tr>
						</table>						
						</p>                   
					</div> 	
									
							
	  <?php 				
	  echo '		</td>
				</tr>
	  		</table>';
	  ////For status in check boxes - End	  
	  
      echo '            </td>' . "\n";
?>
            </tr>
          </table></td>
      </tr>
      <?php
  }
  // RCI code start
  echo $cre_RCI->get('global', 'bottom');                                        
  // RCI code eof
?>
    </table>
    </td>
    
    <!-- body_text_eof //-->
    </tr>
    
  </table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>