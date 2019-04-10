<?php
/*
  $Id: vendor_email_send.php,v 1.1 2008/06/22 22:50:52 datazen Exp $

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');


$debug='no';
$debug_preview='no';
$debug_arrive='no';
$debug_sent='no';


if (isset($_GET['vID'])) {
  $vendors_id = (int)$_GET['vID'] ;
  $vID = (int)$_GET['vID'] ;
} else if (isset($_POST['vID'])) {
  $vendors_id = (int)$_POST['vID'] ;
  $vID = (int)$_POST['vID'] ;
} else {
  $vendors_id = '' ;
  $vID = '' ;
}

if (isset($_GET['oID'])) {
  $oID = (int)$_GET['oID'] ;
} else if (isset($_POST['oID'])) {
  $oID = (int)$_POST['oID'] ;
} else {
  $oID = '' ;
}
if (isset($_GET['vOS'])) {
  $vendor_order_sent = $_GET['vOS'] ;
} else if (isset($_POST['vOS'])) {
  $vendor_order_sent = $_POST['vOS'] ;
} else {
  $vendor_order_sent = '' ;
}

if (isset($_GET['action'])) {
  $action = $_GET['action'] ;
} else if (isset($_POST['action'])) {
  $action = $_POST['action'] ;
  } else {
  $action = '' ;
}

 if ($debug == 'yes') {
   echo 'The vendor post data: ' . $vendors_id . ' ' . $oID . ' ' . $vendor_order_sent . '<br>';
 }
$error = false;
if (isset($_GET['action']) && ($_GET['action'] == 'send_vendor_email')) {
  //  $name = tep_db_prepare_input($_POST['name']);
  $email = stripslashes($_POST['email']);
  $the_email = stripslashes($_POST['the_email']);
  $the_contact = stripslashes($_POST['the_contact']);
  $oID = stripslashes($_POST['order_number']);
  $the_name = stripslashes($_POST['the_name']);
  $vendors_id = $_POST['vID'];
  if($debug_sent == 'yes') {
    echo 'All the posted data is here: <br>' . (int)$vendors_id . '<br>' . $the_email . '<br>' .  $the_contact . '<br>' . $oID . '<br>' . $the_name . '<br>' . $email;
    echo 'All the action: <br>' . $action;
  }
  if ($action == 'send_vendor_email')  {
    tep_mail($the_name, $the_email, EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID ,  $email .  '<br>', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS)  ;
    $vendor_order_sent = 'yes';
    tep_db_query("update " . TABLE_ORDERS_SHIPPING . " set vendor_order_sent = '" . $vendor_order_sent . "' where orders_id = '" . (int)$oID . "'  and vendors_id = '" . (int)$vendors_id . "'");
    $messageStack->add('success', 'Email Sent');
    tep_redirect(tep_href_link(FILENAME_VENDORS_EMAIL_SEND, 'action=success&oID=' . $oID . '&vID=' . (int)$vendors_id . '&contact=' . $the_contact,'SSL'));
  } else {
    $error = true;
    $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  }
}
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">  
<!-- header //-->
<!-- header_eof //-->
<!-- body //-->
<div id="body">  
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <?php
              $index2 = 0;
              //let's get the Vendors and
              //find out what shipping methods the customer chose
              $vendor_data_query = tep_db_query("select v.vendors_id, v.vendors_name, v.vendors_email, v.vendors_contact, v.vendor_add_info, v.vendor_street, v.vendor_city, v.vendor_state, v.vendors_zipcode, v.vendor_country, v.account_number, v.vendors_status_send, os.shipping_module, os.shipping_method, os.shipping_cost, os.shipping_tax, os.vendor_order_sent from " . TABLE_VENDORS . " v,  " . TABLE_ORDERS_SHIPPING . " os where v.vendors_id=os.vendors_id and v.vendors_id='" . $vendors_id . "' and os.orders_id='" . (int)$oID . "'");
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
                'shipping_cost' => $vendor_order['shipping_cost'],                            
                'Vmethod' => $vendor_order['shipping_method']);
                if ($debug == 'yes') {
                  echo 'The vendor query: ' . $vendor_order['vendors_id'] . '<br>';
                }
                $index = 0;
                $vendor_orders_products_query = tep_db_query("select o.orders_id, o.orders_products_id, p.products_model, o.products_id, o.products_quantity, o.products_name, p.vendors_id,  p.vendors_prod_comments, p.vendors_prod_id, p.vendors_product_price from " . TABLE_ORDERS_PRODUCTS. " o, " . TABLE_PRODUCTS . " p where p.vendors_id='" . (int)$vendors_id . "' and o.products_id=p.products_id and o.orders_id='" . $oID . "' order by o.products_name");
                while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query)) {
                  $vendor_products[$index2]['vendor_orders_products'][$index] = array('Pqty' => $vendor_orders_products['products_quantity'],
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
                  $vendor_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");
                  if (tep_db_num_rows($vendor_attributes_query)) {
                    while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query)) {
                      $vendor_products[$index2]['vendor_orders_products'][$index]['vendor_attributes'][$subindex] = array('option' => $vendor_attributes['products_options'],                             'value' => $vendor_attributes['products_options_values'],
                      'prefix' => $vendor_attributes['price_prefix'],
                      'price' => $vendor_attributes['options_values_price']);
                      $subindex++;
                    } 
                  }
                  $index++;
                }
                $index2++;
                // build the email
                // get the delivery address
                $delivery_address_query = tep_db_query("select distinct delivery_company, delivery_name, delivery_street_address, delivery_city, delivery_state, delivery_postcode from " . TABLE_ORDERS . " where orders_id='" . $oID ."'") ;
                $vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);
                if ($debug == 'yes') {
                  echo 'The number of vendors: ' . sizeof($vendor_products) . '<br>';
                }
                $email='';
                for ($l=0, $m=sizeof($vendor_products); $l<$m; $l++) {
                  $vendor_country = tep_get_country_name($vendor_products[$l]['Vcountry']);
                  $order_number = $oID;
                  $vendors_id = $vendors_id;
                  $the_email = $vendor_products[$l]['Vemail'];
                  $the_name = $vendor_products[$l]['Vname'];
                  $the_contact = $vendor_products[$l]['Vcontact'];

                  if (EMAIL_USE_HTML == 'true') {
                  $email =  '<b>To: ' . $the_contact . '  <br>' . $the_name . '<br>' . $the_email . '<br>' .
                  $vendor_products[$l]['Vstreet'] .'<br>' .
                  $vendor_products[$l]['Vcity'] .', ' .
                  $vendor_products[$l]['Vstate'] .'  ' .
                  $vendor_products[$l]['Vzipcode'] . ' ' . $vendor_country . '<br>' . '<br>' . EMAIL_SEPARATOR . '<br>' . 'Special Comments or Instructions:  ' . $vendor_products[$l]['Vinstructions'] .'<br>' . '<br>' . EMAIL_SEPARATOR . '<br>' . 'From: ' . STORE_OWNER . '<br>' . STORE_NAME_ADDRESS . '<br>' . 'Accnt #: ' . $vendor_products[$l]['Vaccount'] . '<br>' . EMAIL_SEPARATOR . '<br>' . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . '<br>' .  EMAIL_SEPARATOR . '<br>' . '<br>' .  EMAIL_SEPARATOR . '<br> Shipping Method: ' .  $vendor_products[$l]['Vmodule'] . ' -- '  .  $vendor_products[$l]['Vmethod'] .'<br>' .' Shipping Cost: '.$vendor_products[$l]['shipping_cost'] .'<br>' .  EMAIL_SEPARATOR . '<br>' . '<br>Dropship deliver to:<br>' .
                  $vendor_delivery_address_list['delivery_company'] .'<br>' .
                  $vendor_delivery_address_list['delivery_name'] .'<br>' .
                  $vendor_delivery_address_list['delivery_street_address'] .'<br>' .
                  $vendor_delivery_address_list['delivery_city'] .', ' .
                  $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] ;
                  $email = $email .  '<table valign="top" width="100%" border=1 cellspacing="0" cellpadding="3"><tr><td>Qty:</td><td>Product Name:</td><td>Item Code/Number:</td><td>Product Model:</td><td>Per Unit Price:</td><td>Item Comments: </td></tr>';
                  for ($i=0, $n=sizeof($vendor_products[$l]['vendor_orders_products']); $i<$n; $i++) {
                    $product_attribs ='';
                    if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {
                      $product_attribs .= '<i>Options<br>';
                      for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
                        $product_attribs .= '' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' .  $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . '<br>';
                      }
                    }
                    $email = $email . '<tr><td>' . trim($vendor_products[$l]['vendor_orders_products'][$i]['Pqty']) .
                                      '</td><td>' . trim($vendor_products[$l]['vendor_orders_products'][$i]['Pname']) . '<br>' . trim($product_attribs) .
                                      '</td><td>' . ( trim($vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id']) != '' ? trim($vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id']) : '-') .
                                      '</td><td>' . ( trim($vendor_products[$l]['vendor_orders_products'][$i]['Pmodel']) != '' ? trim($vendor_products[$l]['vendor_orders_products'][$i]['Pmodel']): '-') .
                                      '</td><td>' . trim($vendor_products[$l]['vendor_orders_products'][$i]['PVprod_price']) . '</td><td>'.(trim($vendor_products[$l]['vendor_orders_products'][$i]['Pcomments']) != '' ? trim($vendor_products[$l]['vendor_orders_products'][$i]['Pcomments']) : "-").'</td></tr>';
                  }
                  
                  } else { //text mail
                  
                  $email =  'To: ' . $the_contact . "\n" . $the_name . "\n" . $the_email . "\n" .
                  $vendor_products[$l]['Vstreet'] ."\n" .
                  $vendor_products[$l]['Vcity'] .', ' .
                  $vendor_products[$l]['Vstate'] .'  ' .
                  $vendor_products[$l]['Vzipcode'] . ' ' . $vendor_country . "\n\n" . EMAIL_SEPARATOR . "\n" . 'Special Comments or Instructions:  ' . $vendor_products[$l]['Vinstructions'] ."\n\n" . EMAIL_SEPARATOR . "\n" . 'From: ' . STORE_OWNER . "\n" . STORE_NAME_ADDRESS . "\n" . 'Accnt #: ' . $vendor_products[$l]['Vaccount'] . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" .  EMAIL_SEPARATOR . "\n\n" .  EMAIL_SEPARATOR . "\n" . 'Shipping Method: ' .  $vendor_products[$l]['Vmodule'] . ' -- '  .  $vendor_products[$l]['Vmethod'] .  "\n"  .' Shipping Cost: '.$vendor_products[$l]['shipping_cost'] . "\n" .  EMAIL_SEPARATOR . "\n" . 'Dropship deliver to:' . "\n" .
                  $vendor_delivery_address_list['delivery_company'] ."\n" .
                  $vendor_delivery_address_list['delivery_name'] ."\n" .
                  $vendor_delivery_address_list['delivery_street_address'] ."\n" .
                  $vendor_delivery_address_list['delivery_city'] .', ' .
                  $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] . "\n\n" ;

                  $email1 = $email .  "\t" . 'Qty' . "\t" . 'Product Name' . "\t" . 'Item Code/Number' . "\t" . 'Product Model' . "\t" . 'Per Unit Price' . "\t" . 'Item Comments' . "\n";
                  for ($i=0, $n=sizeof($vendor_products[$l]['vendor_orders_products']); $i<$n; $i++) {
                    $product_attribs ='';
                    if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {
                      $product_attribs .= '&nbsp;&nbsp;&nbsp;Options';
                      for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
                        $product_attribs .= '&nbsp;&nbsp;&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' .  $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . "\n";
                      }
                    }

                    if (trim($vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id']) == '') {                      
                      $s_code = "  \t\t ";
                    } else {                      
                      $s_code = $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id'];
                    }



                    if(trim($vendor_products[$l]['vendor_orders_products'][$i]['Pmodel']) == '') {
                      $s_model = "  \t\t ";
                    } else {
                      $s_model = $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'];
                    }
                    if (trim($vendor_products[$l]['vendor_orders_products'][$i]['Pcomments']) == '') {
                      $s_comment = "  \t\t ";
                    } else {
                      $s_comment =  $vendor_products[$l]['vendor_orders_products'][$i]['Pcomments'];
                    }

                    $email1 = $email1 . "\t" . trim($vendor_products[$l]['vendor_orders_products'][$i]['Pqty']) .
                                      "\t" . trim($vendor_products[$l]['vendor_orders_products'][$i]['Pname']) .
                                      "\t\t\t" . trim($s_code) .
                                      "\t\t\t" . trim($s_model) .
                                      "\t\t\t" . trim($vendor_products[$l]['vendor_orders_products'][$i]['PVprod_price']) . "\t\t" .
                    trim($s_comment) . "\n";
                    $email1 = $email1 . "\t\t" . trim($product_attribs) . "\n";
                  }

                  /*********************************/
                  $email = $email .  '<table valign="top" width="100%" border=1 cellspacing="0" cellpadding="3"><tr><td>Qty:</td><td>Product Name:</td><td>Item Code/Number:</td><td>Product Model:</td><td>Per Unit Price:</td><td>Item Comments: </td></tr>';
                  for ($i=0, $n=sizeof($vendor_products[$l]['vendor_orders_products']); $i<$n; $i++) {
                    $product_attribs ='';
                    if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {
                      $product_attribs .= '&nbsp;&nbsp;&nbsp;<i>Options<br>';
                      for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
                        $product_attribs .= '&nbsp;&nbsp;&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' .  $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . '<br>';
                      }
                    }
                    $email = $email . '<tr><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] .
                                      '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . '<br>' . $product_attribs .
                                      '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_id'] .
                                      '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] .
                                      '</td><td>&nbsp;' . $vendor_products[$l]['vendor_orders_products'][$i]['PVprod_price'] . '</td><td>' .
                    $vendor_products[$l]['vendor_orders_products'][$i]['Pcomments'] . '</td></tr>';
                  }
                  $email = $email . '</table>';
                  /*********************************/
                      
                      
                  }//end if html mail
                }
                if (EMAIL_USE_HTML == 'true') {
                $email = $email . '</table>';
                }
                if ($debug == 'yes') {
                  echo 'The $email(including headers:<br>Vendor Email Addy' . $the_email . '<br>Vendor Name' . $the_name . '<br>Vendor Contact' . $the_contact . '<br>Body--<br>' . $email . '<br>';
                }
              }


              if ($_GET['action'] == 'success') {
                ?>
            <script language="javascript">
            opener.location.href = '<?php echo tep_href_link(FILENAME_ORDERS_VENDORS,'vendors_id=' . $vID,'SSL');?>';
            setTimeout("self.close()", 3000 );
            </script>
                <tr>
                  <td class="main"><?php echo '<b>Congratulations!  The email has been sent to <big>' . $the_contact . ' </b></big><br>For order number <b>' . $oID . '</b>'; ?></td>
                  <td align="left"><?php echo '<a href="javascript:window.close();">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
                </tr>
                <?php 
              } else if ($_GET['action'] == 'preview') {
                ?>
                <tr><?php echo tep_draw_form('mail', FILENAME_VENDORS_EMAIL_SEND, 'action=send_vendor_email','post','','SSL'); ?>
                  <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
                    <tr>
                      <td class="main"><?php echo 'The email will look like this: <br>'; ?></td>
                      <td align="center"><nobr><?php echo '<a href="' . tep_href_link(FILENAME_VENDORS_EMAIL_SEND, '&vID=' . $vID . '&oID=' . $oID . '&vOS=' . $vOS,'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;<a href="javascript:window.close();">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></nobr></td>'; ?></td>
                    </tr>
                    <tr>
                      <td colspan="3"><?php echo tep_draw_separator(); ?></td>
                    </tr>
                    <?php 
                    if ($debug == 'yes') { 
                      ?>
                      <tr>
                        <td colspan="3"><?php echo $order_number . $the_email . $the_name . $the_contact; ?></td>
                      </tr>
                      <?php
                    }
                    $email = stripslashes($_POST['email']);
                    $email1 = stripslashes($_POST['email']);
                    echo '<tr><td><br>' . ((EMAIL_USE_HTML == 'true') ? $email : nl2br(str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$email1))); ?></td>
                </tr>
                <tr>
                  <td colspan="3"><?php echo tep_draw_separator(); ?></td>
                </tr>
                <tr>
                  <td class="main">
                    <?php 
                        echo tep_draw_hidden_field('email', $email) . "\n";
                     echo tep_draw_hidden_field('order_number', $oID) . "\n";
                     echo tep_draw_hidden_field('vID', $vendors_id) . "\n";
                     echo tep_draw_hidden_field('the_email',  stripslashes($the_email)) . "\n";
                     echo tep_draw_hidden_field('the_name', stripslashes($the_name)) . "\n";
                     echo tep_draw_hidden_field('the_contact', stripslashes($the_contact)) . "\n";
                    ?>
                  </td>
                </tr>
                <tr>
                  <td align="right"><?php echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                  </form>
                </tr>
                <?php 
              } else { 
                if (EMAIL_USE_HTML == 'true') {
                echo tep_load_html_editor();
                echo tep_insert_html_editor('email','simple');
                }
                ?>
                <tr><?php echo tep_draw_form('mail', FILENAME_VENDORS_EMAIL_SEND, 'action=preview&vID=' . $vendors_id . '&oID=' . $oID . '&vOS=' . $vOS,'post','','SSL'); ?>
                  <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
                    <tr>
                      <td class="main">Preview of the email your Vendor will see when they open the email: <br><br></td>
                    </tr>
                    <tr>
                      <td><?php echo tep_draw_separator(); ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo stripslashes((EMAIL_USE_HTML == 'true') ? $email : nl2br($email)); ?></td>
                    </tr>
                    <tr>
                      <td><?php
                  echo tep_draw_separator();  
                
                ?></td>
                    </tr>
                    <tr>
                      <td class="main"><p><b>If necessary you may modify email below:</b> <br><i>Please note that the email is already formatted. You may add / edit the text in the generated email.</i></p><br>
                        <textarea name="email" id="email" wrap="soft" cols="100%" rows="15"><?php
                  if (EMAIL_USE_HTML == 'true') {
                   echo stripslashes($email);
                  } else {
                     echo stripslashes($email1);
                  }
                  //echo stripslashes($email)
                  
                ?></textarea>     
                      </td>
                    </tr>
                    <?php echo tep_draw_hidden_field('vID', $vendors_id) . "\n";
                          echo tep_draw_hidden_field('oID', $oID) . "\n";
                     echo tep_draw_hidden_field('vOS', $vendor_order_sent) . "\n";?>
                    <tr>
                      <td><table width="77%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td align="right"><?php echo '<a href="javascript:window.close();">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_preview.gif', IMAGE_PREVIEW); ?></td>  
                        </tr>
                      </table></td>
                    </tr>
                  </table></form></td>
                </tr>
                <?php
              }
              ?>
            </table></td>
          </tr>  
        </table></td>
      </tr>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php //require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>