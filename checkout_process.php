<?php
/*
  $Id: checkout_process.php,v 1.1.1.2 2004/03/04 23:37:57 ccwjr Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

include('includes/application_top.php');

function nbi_order_sync($data_arr)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	$url = NBI_ORDER_SYNC_URL;
	$data = $data_arr;
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
	curl_setopt($ch, CURLOPT_URL,$url);	
	$result = curl_exec ($ch);
	curl_close ($ch);
	//var_dump($result);
	return json_decode(trim($result), TRUE);		
}

//Function to create this order in Crm
function manage_crm_order($order_id)
{
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);  
	
	//fetch data from orders_total table
	$order_total = "SELECT * FROM ".TABLE_ORDERS_TOTAL." WHERE orders_id = '$order_id'";
	$exe_total = tep_db_query($order_total);
	while ($totals_array = tep_db_fetch_array($exe_total)) 
	{
		if($totals_array['class'] == "ot_subtotal")
			$sub_total = $totals_array[value];
		else if($totals_array['class'] == "ot_gst_tax")
			$tax = 	$totals_array[value];
		else if($totals_array['class'] == "ot_total")
			$total = 	$totals_array[value];
		else if($totals_array['class'] == "ot_customer_discount")
			$discount = $totals_array[value];

                //added by Basabdutta on 25.07.12 [for discount calculation]	
		else if($totals_array['class'] == "ot_gv")
			$discount = $totals_array[value];
                //---------------------------------------------	
		else if($totals_array['class'] == "ot_shipping_tax_inc")
			$ot_shipping_tax_inc = $totals_array[value];
		else if($totals_array['class'] == "ot_tax")
			$ot_tax = $totals_array[value];	
		else if($totals_array['class'] == "ot_gst_total")
			$ot_gst_total = $totals_array[value];
		else if($totals_array['class'] == "ot_lev_discount")
			$ot_lev_discount = $totals_array[value];
		else if($totals_array['class'] == "ot_grand_subtotal")
			$ot_grand_subtotal = $totals_array[value];
		else if($totals_array['class'] == "ot_coupon")
		{
	                $ot_coupon = $totals_array[value];
	                $discount = $totals_array[value]; //added by Basabdutta on 30.07.12 
		}	
		else if($totals_array['class'] == "ot_shipping")
		{
			$shipping_method = $totals_array[title];
		  	$shipping_method_price = $totals_array[value];	
		}
				
		$ot_gst_total = str_replace('$','',$ot_gst_total);
        	if($ot_gst_total == '')
          		$ot_gst_total = $tax;
	}
	//fetch data from orders_products table
	$query_prod = "SELECT * FROM orders_products WHERE orders_id='$order_id'";
	$exe_prod = tep_db_query($query_prod);
	while($prod_array = tep_db_fetch_array($exe_prod))
	{
		$prod_final_array[] = $prod_array;
	}
        $ord_prod_array = base64_encode(serialize($prod_final_array));//serialize($prod_final_array); //modified by Basabdutta on 19.06.12

	//get tax ammount
	$prod_tax_array = array();
	$query_prod = "SELECT * FROM orders_products WHERE orders_id='$order_id'";
	
	$exe_prod = tep_db_query($query_prod);
	while($prod_tax = tep_db_fetch_array($exe_prod))
	{
		$prod_id_cre = $prod_tax['products_id'];
		//get data from product table
		$prod_query = mysql_query("SELECT products_tax_class_id,`products_price` 	FROM products WHERE 
		products_id = '$prod_id_cre'");
		
		$rs_product = mysql_fetch_array($prod_query);
		$tax_class_id = $rs_product['products_tax_class_id'];
		$product_price = $rs_product['products_price'];
		//fecth from tax_rates table
	/*	$tax_rate = mysql_query("SELECT tax_rate  FROM tax_rates WHERE tax_class_id =
		'$tax_class_id'");
		$rs_tax_rate = mysql_fetch_array($tax_rate);
		$tax_amt = $rs_tax_rate[tax_rate];     */
	  $tax_amt = round($prod_tax['products_tax'],2);
		$prod_tax_array[$prod_id_cre][tax] = $tax_amt;
		$prod_tax_array[$prod_id_cre][price] = $product_price;
	} 
	
	$prod_tax_array = serialize($prod_tax_array);
	//fetch data from orders_status_history table
	$query_ord = "SELECT * FROM orders_status_history WHERE orders_id='$order_id'";
	
	$exe_ord = tep_db_query($query_ord);
	while($ord_array = tep_db_fetch_array($exe_ord))
	{
		$ord_hist_array_final[] = $ord_array;
	}
	
	$ord_hist_array = serialize($ord_hist_array_final);


	
	//Fetch data from orders array
	$query = "SELECT * FROM ".TABLE_ORDERS ." WHERE orders_id='$order_id'";
	$exe = tep_db_query($query);
  	$order_array = tep_db_fetch_array($exe);

        
  	$order_status_id = $order_array['orders_status'];
	$order_status = "SELECT * FROM orders_status WHERE orders_status_id ='$order_status_id'";
	$exe_stat = tep_db_query($order_status);
	$order_stat_array = tep_db_fetch_array($exe_stat);
	$order_status = $order_stat_array['orders_status_name'];
  	$order_from = "DSI Shopping Cart Frontend";
	
    $url = "http://namebadgesinternational.com.au/CRM-success/CrmManageOrder.php";
	$fields = "orders_id=".$order_id
			."&customers_id=".urlencode($order_array['customers_id'])
			."&customers_name=".urlencode($order_array['customers_name'])
			."&billing_street_address=".urlencode($order_array['billing_street_address'])
			."&shipping_street_address=".urlencode($order_array['delivery_street_address'])
			."&billing_city=".urlencode($order_array['billing_city'])
			."&billing_country=".urlencode($order_array['billing_country'])
			."&billing_state=".urlencode($order_array['billing_state'])
			
			."&delivery_name =".urlencode($order_array['delivery_name'])
			."&delivery_company=".urlencode($order_array['delivery_company'])
			."&delivery_street_address=".urlencode($order_array['	delivery_street_address'])
			."&delivery_city=".urlencode($order_array['delivery_city'])
			."&delivery_state=".urlencode($order_array['delivery_state'])
			."&delivery_country=".urlencode($order_array['delivery_country'])	
			."&date_purchased=".urlencode($order_array['date_purchased'])
			."&billing_postal=".urlencode($order_array['billing_postcode'])
			."&shipping_postal=".urlencode($order_array['delivery_postcode'])

       ."&order_status=".$order_status
			."&order_status_id=".$order_status_id
			
			."&sub_total=".$sub_total
      ."&tax=".$tax
      ."&total=".$total
      ."&discount=".$discount
      ."&ot_shipping_tax_inc=".$ot_shipping_tax_inc
      ."&ot_tax=".$ot_tax
      ."&ot_gst_total=".$ot_gst_total
      ."&ot_lev_discount=".$ot_lev_discount
      ."&ot_grand_subtotal=".$ot_grand_subtotal
      ."&ot_coupon=".$ot_coupon
      ."&shipping_method=".$shipping_method
      ."&shipping_method_price=".$shipping_method_price
      ."&ot_gst_total=".$ot_gst_total
      ."&create_type_c=".$order_from
			
			."&quote_email_c=".urlencode($order_array['customers_email_address'])
			."&quote_phone_c=".urlencode($order_array['customers_telephone'])
			."&ip_address_c=".urlencode($order_array['ipaddy'])
			."&isp_c=".urlencode($order_array['ipisp'])
			."&due_date=".urlencode($order_array['due_date'])
			."&purchase_no=".urlencode($order_array['purchase_number'])
			."&payment_method_c=".urlencode($order_array['payment_method'])
			."&billing_address_2_c=".urlencode($order_array['billing_suburb'])
			."&shipping_address_2_c=".urlencode($order_array['delivery_suburb'])
			."&billing_email_c=".urlencode($order_array['billing_email_address'])
			."&shipping_email_c=".urlencode($order_array['delivery_email_address'])
			."&billing_fax_c=".urlencode($order_array['billing_fax'])
			."&shipping_fax_c=".urlencode($order_array['delivery_fax'])
			."&billing_telephone_c=".urlencode($order_array['billing_telephone'])
			."&shipping_telephone_c=".urlencode($order_array['delivery_telephone'])
			
			."&ord_prod_array=".$ord_prod_array
      ."&ord_hist_array=".$ord_hist_array 
      ."&prod_tax_array=".$prod_tax_array              
			."&mode=".'order'				
			;
			
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
  curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
	curl_setopt($ch, CURLOPT_URL,$url);	
	$result = curl_exec ($ch);
	curl_close ($ch);
	
	//var_dump($result);
  //exit;
	//mail("basabdutta.dhar@indusnet.co.in", "TEST AFTER FUNC", $query ." TEST BODY TEST".$order_array, "nbi.com");
        //mail("ananthan@indusnet.co.in", "TEST AFTER FUNC", "TEST BODY TEST".$order_id, "nbi.com");
  }
  
  //End of function to create this order in Crm
//**************************************************************



if (defined('MVS_STATUS') && MVS_STATUS == 'true') {  

  function vendors_email($vendors_id, $oID, $status, $vendor_order_sent) {

    $vendor_order_sent =  false;

    $debug='no';

    $vendor_order_sent = 'no';

    $index2 = 0;

    //let's get the Vendors

    $vendor_data_query = tep_db_query("select v.vendors_id, v.vendors_name, v.vendors_email, v.vendors_contact, v.vendor_add_info, v.vendor_street, v.vendor_city, v.vendor_state, v.vendors_zipcode, v.vendor_country, v.account_number, v.vendors_status_send, os.shipping_module, os.shipping_method, os.shipping_cost, os.shipping_tax, os.vendor_order_sent from " . TABLE_VENDORS . " v,  " . TABLE_ORDERS_SHIPPING . " os where v.vendors_id=os.vendors_id and v.vendors_id='" . $vendors_id . "' and os.orders_id='" . (int)$oID . "' and v.vendors_status_send='" . $status . "'");

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

      $vendor_orders_products_query = tep_db_query("select o.orders_id, o.orders_products_id, o.products_model, o.products_id, o.products_quantity, o.products_name, p.vendors_id,  p.vendors_prod_comments, p.vendors_prod_id, p.vendors_product_price from " . TABLE_ORDERS_PRODUCTS . " o, " . TABLE_PRODUCTS . " p where p.vendors_id='" . (int)$vendor_order['vendors_id'] . "' and o.products_id=p.products_id and o.orders_id='" . $oID . "' order by o.products_name");

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

          echo 'The products query: ' . $vendor_orders_products['products_name'] . "\n";

        }

        $subindex = 0;

        $vendor_attributes_query = tep_db_query("select products_options, products_options_values, options_values_price, price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");

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

      // let's build the email

      // Get the delivery address

      $delivery_address_query = tep_db_query("select distinct delivery_company, delivery_name, delivery_street_address, delivery_city, delivery_state, delivery_postcode from " . TABLE_ORDERS . " where orders_id='" . $oID ."'") ;

      $vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);

      if ($debug == 'yes') {

        echo 'The number of vendors: ' . sizeof($vendor_products) . "\n";

      }

      $email='';

      for ($l=0, $m=sizeof($vendor_products); $l<$m; $l++) {

        $vendor_country = tep_get_country_name($vendor_products[$l]['Vcountry']);

        $order_number= $oID;

        $vendors_id=$vendor_products[$l]['Vid'];

        $the_email=$vendor_products[$l]['Vemail'];

        $the_name=$vendor_products[$l]['Vname'];

        $the_contact=$vendor_products[$l]['Vcontact'];

        $email=  "\n" . 'To: ' . $the_contact . "\n" . $the_name . "\n" . $the_email . "\n" .

        $vendor_products[$l]['Vstreet'] . "\n" .

        $vendor_products[$l]['Vcity'] .', ' .

        $vendor_products[$l]['Vstate'] .'  ' .

        $vendor_products[$l]['Vzipcode'] . ' ' . $vendor_country . "\n\n" . EMAIL_SEPARATOR . "\n" . 'Special Comments or Instructions:  ' . $vendor_products[$l]['Vinstructions'] . "\n\n" . EMAIL_SEPARATOR . "\n" . 'From: ' . STORE_OWNER . "\n" . STORE_NAME_ADDRESS . "\n" . 'Accnt #: ' . $vendor_products[$l]['Vaccount'] . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" .  EMAIL_SEPARATOR . "\n\n" . ' Shipping Method: ' .  $vendor_products[$l]['Vmodule'] . ' -- '  .  $vendor_products[$l]['Vmethod'] .  "\n" .  EMAIL_SEPARATOR . "\n\n" . 'Dropship deliver to:' . "\n" .

        $vendor_delivery_address_list['delivery_company'] . "\n" .

        $vendor_delivery_address_list['delivery_name'] . "\n" .

        $vendor_delivery_address_list['delivery_street_address'] . "\n" .

        $vendor_delivery_address_list['delivery_city'] .', ' .

        $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] . "\n\n" ;

        $email .= 'Products:' . "\n" . EMAIL_SEPARATOR . "\n";

        for ($i=0, $n=sizeof($vendor_products[$l]['vendor_orders_products']); $i<$n; $i++) {

          $product_attribs ='';

          if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {

            for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {

              $product_attribs .= '     ' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' .  $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . "\n";

            }

          }

          if (tep_not_null($vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'])) {

            $prod_module = ' (' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] . ')';

          } else {

            $prod_module = '';

          }

          $email .= $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] .

                            ' X ' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . $prod_module . "\n" . $product_attribs . "\n";

        }

        

        tep_mail($the_name, $the_email, EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID ,  $email .  '<br>', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS)  ;

      $vendor_order_sent = 'yes';

        tep_db_query("update " . TABLE_ORDERS_SHIPPING . " set vendor_order_sent = '" . tep_db_input($vendor_order_sent) . "' where orders_id = '" . (int)$oID . "'  and vendors_id = '" . (int)$vendors_id . "'");

        if ($debug == 'yes') {

          echo 'The $email(including headers:' . "\n" . 'Vendor Email Addy' . $the_email . "\n" . 'Vendor Name' . $the_name . "\n" . 'Vendor Contact' . $the_contact . "\n" . 'Body--' . "\n" . $email . "\n";

        }

      }

    }

    return true;

  } 

}

$ip = $_SERVER['REMOTE_ADDR'];

$client = gethostbyaddr($_SERVER['REMOTE_ADDR']);

$str = preg_split("/\./", $client);

$i = count($str);

$x = $i - 1;

$n = $i - 2;

$isp = (isset($str[$n]) ? $str[$n] : '') . "." . (isset($str[$x]) ? $str[$x] : '');

// if the customer is not logged on, redirect them to the login page

if ( !isset($_SESSION['customer_id']) ) {

  $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));

  tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));

}

if ( ! isset($_SESSION['sendto']) ) {

  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

}

if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!isset($_SESSION['payment'])) ) {

  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

}

// avoid hack attempts during the checkout procedure by checking the internal cartID

if (!isset($_GET['order_id'])) {

  if (isset($cart->cartID) && isset($_SESSION['cartID']) ) {

    if ($cart->cartID != $_SESSION['cartID']) {

      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

    }

  }

}

include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);

// RCI checkoutprocess check

echo $cre_RCI->get('checkoutprocess', 'check', false);

// load selected payment module

require(DIR_WS_CLASSES . 'payment.php');

if (isset($_SESSION['credit_covers'])) $_SESSION['payment']=''; //ICW added for CREDIT CLASS

$payment_modules = new payment($_SESSION['payment']);

// load the selected shipping module

if (defined('MVS_STATUS') && MVS_STATUS == 'true') {

  include(DIR_WS_CLASSES . 'vendor_shipping.php');

} else {

  include(DIR_WS_CLASSES . 'shipping.php');

}

$shipping_modules = new shipping($_SESSION['shipping']); 



require(DIR_WS_CLASSES . 'order.php');

$order = new order;

if (!class_exists('order_total', false)) {

  include(DIR_WS_CLASSES . 'order_total.php');

  $order_total_modules = new order_total;

}

$order_totals = $order_total_modules->process();

//get customer name - this custom function declared in includes/functions/general.php - Mar 21 2011
$customer_info = tep_get_customer_info();

$order->totals = $order_totals;

/*
foreach ($cart->contents as $cid=>$cont){
	
	if(is_array($cont['attributes'])) {
		
		foreach($cont['attributes'] as $attid=>$attval) {
			
			if(!empty($attval["t"])) {
				
					
				
			}
			
		}
		
	}
	
}

exit;*/

if(NBI_SYNC==true) {
	
	
	$nbi_sync = nbi_order_sync($order);
	
	//echo "<br>";
	
	//print_r($nbi_sync);
	
	//exit;
	
	//include_once("nbi_sync.php");
	
	//echo "<br>";
	//echo $nbi_sync['final_customers_id']."<br>";
	//echo $nbi_sync['final_orders_id']."<br>";

	
	$next_id = $nbi_sync['final_orders_id']; 
	
} else {
	
	echo "Not sync";
	
	$next_insert_query = tep_db_query("SHOW TABLE STATUS LIKE 'orders'");    
	$row = tep_db_fetch_array($next_insert_query);
	$next_id = $row['Auto_increment']; 
	
}


/****************** Zip file generation starts - Mar 23 2011 ********************/

require_once(DIR_WS_TEMPLATES . TEMPLATE_NAME . DIRECTORY_SEPARATOR . 'bd' . DIRECTORY_SEPARATOR . 'CreateZipFile.inc.php');
$files = array();
$zipName = '';
$badgeAvail = 0;
 
$create_zip_file=new CreateZipFile;

if(is_array($cart->contents)) {   	      
   
	$productTextString = '';
	
	$temp_files = array();

	foreach ($cart->contents as $cid=>$cont){
	
		$prod = tep_db_fetch_array(tep_db_query("SELECT products_id, products_image, badge_data, products_text, badge_logo, badge_namefile FROM products WHERE products_id = ".intval($cid)));
		
		$special_chars = array(".",",","&"," ");
		//added Dec 10 2010
		if($prod['badge_data']!="") {
		
			$filename_for_all = $next_id . "_" . date("dmy") . "_" . str_replace($special_chars,"",$customer_info['entry_firstname']) . "_" . str_replace($special_chars,"",$customer_info['entry_lastname']);
			//call zip functions
			$create_pro_zip_file=new CreateZipFile;  
			
			$textFile=$filename_for_all.'_names.csv';			
			$fh = fopen(DIR_FS_CATALOG . 'images/users_badges/'. $textFile, 'w') or die("can't open file");
			$stringData = $prod['products_text'];
			fwrite($fh, $stringData);
			fclose($fh);			
			//Add text content entered in the text field
			$text_file_path =DIR_FS_CATALOG . 'images/users_badges/'.$textFile;
			$textFileName = 'users_badges/'.$textFile;
			$create_pro_zip_file->addFile(file_get_contents($text_file_path), $textFileName);
			
			
			//Logo files to zip
			$productLogo = explode(",",$prod['badge_logo']);
			for ($i=0; $i<count($productLogo); $i++){
				if(!empty($productLogo[$i])) {
					$original_logo_image = DIR_FS_CATALOG . 'images/'.$productLogo[$i];
					$logo_pathinfo = pathinfo($original_logo_image);
					$logoName = $filename_for_all . "_logo_" . ($i+1) . ".".$logo_pathinfo['extension'];
					$create_pro_zip_file->addFile(file_get_contents($original_logo_image), 'users_badges/'.$logoName);
				}
			}
			
			//name list files to zip
			$namefiles = explode(",",$prod['badge_namefile']);
			for ($i=0; $i<count($namefiles); $i++){
				if(!empty($namefiles[$i])) {
					$original_namefile = DIR_FS_CATALOG . 'images/users_names/'.$namefiles[$i];
					$namefile = pathinfo($original_namefile);
					$nameFilesUploaded = $filename_for_all ."_namelist_" . ($i+1) . ".".$namefile['extension'];
					$create_pro_zip_file->addFile(file_get_contents($original_namefile), $nameFilesUploaded);
				}
			}
			
			//zip sample product badge design image
			$badge_image_path = DIR_FS_CATALOG . 'images/products_design/'. $prod['products_image'];
			$badge_image_info = pathinfo($badge_image_path);
			$create_pro_zip_file->addFile(file_get_contents($badge_image_path), 'users_badges/'.$filename_for_all."_design.".$badge_image_info['extension']);
						
			 $zipName='product_'.$prod['products_id'].'.zip';
			 $fd=fopen(DIR_WS_IMAGES . 'temp' . DIRECTORY_SEPARATOR . $zipName, "wb");
			 $out=fwrite($fd,$create_pro_zip_file->getZippedfile());
			 fclose($fd); 
			
			//Add products zip to another zip
			$productPath = DIR_WS_IMAGES . 'temp' . DIRECTORY_SEPARATOR . $zipName;
			$create_zip_file->addFile(file_get_contents($productPath), $zipName);
			
			//add temporary files to array
			$temp_files[] = $productPath;
			$temp_files[] = $text_file_path;
		
			$badgeAvail = 1;
		}
	} 
						  
}
   
if($badgeAvail == 1)  { 
	
	$ord_zip_name= 'order_' . $next_id . '.zip';
	$ford=fopen(DIR_WS_IMAGES . 'temp' . DIRECTORY_SEPARATOR . $ord_zip_name, "wb");
	$out_ord=fwrite($ford,$create_zip_file->getZippedfile());
	fclose($ford);		
	copy(DIR_WS_IMAGES . 'temp' . DIRECTORY_SEPARATOR . $ord_zip_name, DIR_WS_IMAGES . 'zip_bages' . DIRECTORY_SEPARATOR . 'order_' . $next_id . '.zip');   
	
	$temp_files[] =  DIR_WS_IMAGES . 'temp' . DIRECTORY_SEPARATOR . $ord_zip_name;

} 
   

//remove temp files (zip from temp folder, csv from users badges folder)
foreach($temp_files as $temp_file_path) {
	if(file_exists($temp_file_path)) {
		@unlink($temp_file_path);
	}
}

/****************** Zip file generation ends ********************/



// load the before_process function from the payment modules.

// Authorize.net/QuickCommerce/PlugnPlay processing - this called moved to a later point

// This is maintained for compatiblity with all other modules

 if(((defined('MODULE_PAYMENT_AUTHORIZENET_STATUS') && MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True') && ($_SESSION['payment'] == 'authorizenet')) ||

    ((defined('MODULE_PAYMENT_PAYFLOWPRO_STATUS') && MODULE_PAYMENT_PAYFLOWPRO_STATUS =='True') && ($_SESSION['payment'] == 'payflowpro')) ||  

    ((defined('MODULE_PAYMENT_CREMERCHANT_AUTHORIZENET_STATUS') && MODULE_PAYMENT_CREMERCHANT_AUTHORIZENET_STATUS == 'True') && ($_SESSION['payment'] == 'CREMerchant_authorizenet')) ||

    ((defined('MODULE_PAYMENT_CRESECURE_STATUS') && MODULE_PAYMENT_CRESECURE_STATUS == 'True') && (MODULE_PAYMENT_CRESECURE_BRANDED == 'False') && ($_SESSION['payment'] == 'cresecure')) ||   

    ((defined('MODULE_PAYMENT_QUICKCOMMERCE_STATUS') && MODULE_PAYMENT_QUICKCOMMERCE_STATUS =='True') && ($_SESSION['payment'] == 'quickcommerce')) || 

    ((defined('MODULE_PAYMENT_PLUGNPAY_STATUS') && MODULE_PAYMENT_PLUGNPAY_STATUS =='True')  && ($_SESSION['payment'] == 'plugnpay'))){

   //don't load before process

} elseif((defined('MODULE_PAYMENT_PAYPAL_STATUS') && MODULE_PAYMENT_PAYPAL_STATUS == 'True') && ($_SESSION['payment'] == 'paypal')) {

  $payment_modules->before_process();

  include(DIR_WS_MODULES . 'payment/paypal/catalog/checkout_process.inc.php');

} else {

  $payment_modules->before_process();

}

if ( (PAYMENT_CC_CRYPT == 'True' ) && !empty($order->info['cc_number']) ){

 $cc_number1 = cc_encrypt($order->info['cc_number']);

 $cc_expires1 = cc_encrypt($order->info['cc_expires']);

} else {

  $cc_number1 =$order->info['cc_number'];

  $cc_expires1 =$order->info['cc_expires'];

}


$sql_data_array = array('customers_id' => $_SESSION['customer_id'],

                        'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],

                        'customers_company' => $order->customer['company'],

                        'customers_street_address' => $order->customer['street_address'],

                        'customers_suburb' => $order->customer['suburb'],

                        'customers_city' => $order->customer['city'],

                        'customers_postcode' => $order->customer['postcode'],

                        'customers_state' => $order->customer['state'],

                        'customers_country' => $order->customer['country']['title'],

                        'customers_telephone' => $order->customer['telephone'],

                        'customers_email_address' => $order->customer['email_address'],

                        'customers_address_format_id' => $order->customer['format_id'],

                        'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],

                        'delivery_company' => $order->delivery['company'],

                        'delivery_street_address' => $order->delivery['street_address'],

                        'delivery_suburb' => $order->delivery['suburb'],

                        'delivery_city' => $order->delivery['city'],

                        'delivery_postcode' => $order->delivery['postcode'],

                        'delivery_state' => $order->delivery['state'],

                        'delivery_country' => $order->delivery['country']['title'],

                        'delivery_address_format_id' => $order->delivery['format_id'],

                        'delivery_telephone' => $order->delivery['telephone'],

                        'delivery_fax' => $order->delivery['fax'],

                        'delivery_email_address' => $order->delivery['email_address'],

                        'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],

                        'billing_company' => $order->billing['company'],

                        'billing_street_address' => $order->billing['street_address'],

                        'billing_suburb' => $order->billing['suburb'],

                        'billing_city' => $order->billing['city'],

                        'billing_postcode' => $order->billing['postcode'],

                        'billing_state' => $order->billing['state'],

                        'billing_country' => $order->billing['country']['title'],

                        'billing_address_format_id' => $order->billing['format_id'],

                        'billing_telephone' => $order->billing['telephone'],

                        'billing_fax' => $order->billing['fax'],

                        'billing_email_address' => $order->billing['email_address'],

                        'payment_method' => $order->info['payment_method'],

                        'payment_info' =>  $order->info['payment_info'],

                        'cc_type' => (isset($order->info['cc_type']) ? $order->info['cc_type'] : ''),

                        'cc_owner' => (isset($order->info['cc_owner']) ? $order->info['cc_owner'] : ''),

                        'cc_number' => (isset($cc_number1) ? $cc_number1 : ''),

                        'cc_start' => (isset($order->info['cc_start']) ? $order->info['cc_start'] : ''),

                        'cc_issue' => (isset($order->info['cc_issue']) ? $order->info['cc_issue'] : ''),

                        'cc_expires' => (isset($cc_expires1) ? $cc_expires1 : ''),

                        'date_purchased' => 'now()',

                        'last_modified' => 'now()',

                        'orders_status' => $order->info['order_status'],

                        'currency' => $order->info['currency'],

                        'currency_value' => $order->info['currency_value'],

						'purchase_number' => $order->info['purchase_number'],
						 
						'due_date' => (isset($order->info['due_date']) ? $order->info['due_date'] : ''),

                        'ipaddy' => $ip,

                        'ipisp' => $isp);
						
	
	if(tep_not_null($next_id)) {
	
		$sql_data_array['orders_id'] = $next_id;	
		
	}

tep_db_perform(TABLE_ORDERS, $sql_data_array);

$insert_id = tep_db_insert_id();


//update customers table for orders count  - Sep 05 2011
$cust_order_count = $customer_info["customers_orders_count"] + 1;
tep_db_query("UPDATE customers SET customers_orders_count='".$cust_order_count."' WHERE customers_id='".$_SESSION['customer_id']."'"); 


// RCI checkoutprocess logic

echo $cre_RCI->get('checkoutprocess', 'logic', false);

// Make sure the /catalog/includes/class/order.php is included

// and $order object is created before this!!!

// load the before_process function from the payment modules

//************

  if(defined('MODULE_PAYMENT_AUTHORIZENET_STATUS') && (MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True') && ($_SESSION['payment'] == 'authorizenet')){

    include(DIR_WS_MODULES . 'authorizenet_direct.php');

    $payment_modules->before_process();

  }

  // Payflow Pro

  if(defined('MODULE_PAYMENT_PAYFLOWPRO_STATUS') && (MODULE_PAYMENT_PAYFLOWPRO_STATUS == 'True') && ($_SESSION['payment'] == 'payflowpro')){

    include(DIR_WS_MODULES . 'payflowpro_direct.php');

    $payment_modules->before_process();

  }   

  // CREMerchant_authorizenet

  if( defined(MODULE_PAYMENT_CREMERCHANT_AUTHORIZENET_STATUS) && (MODULE_PAYMENT_CREMERCHANT_AUTHORIZENET_STATUS == 'True') && ($_SESSION['payment'] == 'CREMerchant_authorizenet') ) {

    include(DIR_WS_MODULES . 'CREMerchant_authorizenet_direct.php'); 

    $payment_modules->before_process();

  }    

 //quickcommerce

  if(defined('MODULE_PAYMENT_QUICKCOMMERCE_STATUS') && (MODULE_PAYMENT_QUICKCOMMERCE_STATUS =='True') && ($_SESSION['payment'] == 'quickcommerce')) {

    include(DIR_WS_MODULES . 'quickcommerce_direct.php');

    $payment_modules->before_process();

  }

  if(defined('MODULE_PAYMENT_PLUGNPAY_STATUS') && (MODULE_PAYMENT_PLUGNPAY_STATUS =='True')  && ($_SESSION['payment'] == 'plugnpay')) {

    include(DIR_WS_MODULES . 'plugnpay_api.php');

    $payment_modules->before_process();

  }

  // CRE Gateway

  if(defined('MODULE_PAYMENT_CRESECURE_STATUS') && (MODULE_PAYMENT_CRESECURE_STATUS == 'True') && (MODULE_PAYMENT_CRESECURE_BRANDED == 'False') && ($_SESSION['payment'] == 'cresecure')){

    include(DIR_WS_MODULES . 'cresecure_direct.php');

    $payment_modules->before_process();

  }  

//insert order total

for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {

  $sql_data_array = array('orders_id' => $insert_id,

                          'title' => $order_totals[$i]['title'],

                          'text' => $order_totals[$i]['text'],

                          'value' => $order_totals[$i]['value'],

                          'class' => $order_totals[$i]['code'],

                          'sort_order' => $order_totals[$i]['sort_order']);

  tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);

}

$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';

$sql_data_array = array('orders_id' => $insert_id,

                        'orders_status_id' => $order->info['order_status'],

                        'date_added' => 'now()',

                        'customer_notified' => $customer_notification,
						
						'admin_users_id' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],

                        'comments' => $order->info['comments']);

tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

if (defined('MVS_STATUS') && MVS_STATUS == 'true') {

  $shipping_array = $_SESSION['shipping']['vendor'];

  if(is_array($shipping_array)) {

    foreach ($shipping_array as $vendors_id => $shipping_data) {

      $vendors_query = tep_db_query("SELECT vendors_name

                                       from " . TABLE_VENDORS . "

                                     WHERE vendors_id = '" . (int)$vendors_id . "'"

                                   );

      $vendors_name = 'Unknown';

      if ($vendors = tep_db_fetch_array($vendors_query)) {

        $vendors_name = $vendors['vendors_name'];

      }

      $shipping_method_array = explode ('_', $shipping_data['id']);

      if ($shipping_method_array[0] == 'fedex1') {

        $shipping_method = 'Federal Express';

      } elseif ($shipping_method_array[0] == 'upsxml') {

        $shipping_method = 'UPS';

      } elseif ($shipping_method_array[0] == 'usps') {

        $shipping_method = 'USPS';

      } else {

        $shipping_method = $shipping_method_array[0];

      }

      $sql_data_array = array('orders_id' => $insert_id,

                              'vendors_id' => $vendors_id,

                              'shipping_module' => $shipping_method,

                              'shipping_method' => $shipping_data['title'],

                              'shipping_cost' => $shipping_data['cost'],

                              'shipping_tax' =>  $shipping_data['ship_tax'],

                              'vendors_name' => $vendors_name,

                              'vendor_order_sent' => 'no'

                             );

      tep_db_perform(TABLE_ORDERS_SHIPPING, $sql_data_array);

    }

  }

}

// initialized for the email confirmation

$products_ordered = '';

$subtotal = 0;

$total_tax = 0;

for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {

  // Stock Update - Joao Correia

  if (STOCK_LIMITED == 'true') {

    $downloadable_product = false;

    if (DOWNLOAD_ENABLED == 'true') {

      // see if this product actually has a downloadable file in the attributes

      $download_check_query_raw = "SELECT products_quantity, pad.products_attributes_filename

                                     from " . TABLE_PRODUCTS . " p,

                                          " . TABLE_PRODUCTS_ATTRIBUTES . " pa,

                                          " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad

                                   WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'

                                     and p.products_id=pa.products_id

                                     and pad.products_attributes_id=pa.products_attributes_id ";

      $download_check_query = tep_db_query($download_check_query_raw);

      if (tep_db_num_rows($download_check_query) > 0) {

        $downloadable_product = true;

      }

    }  // end of downloadable product check

    if ( !$downloadable_product ) {
     
        $stock_query = tep_db_query("select products_quantity, overall_quantity, progressed_quantity, products_parent_id, default_product_id from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

        $stock_values = tep_db_fetch_array($stock_query);

      //For update the Parent Product Quantity

      if ($stock_values['products_parent_id'] != 0 || $stock_values['default_product_id']!=0) {

        $product_parent_id_db = $stock_values['products_parent_id'];
		
		//Aug 01 2012
		if($product_parent_id_db==0 || $product_parent_id_db=="") {
			$product_parent_id_db = $stock_values['default_product_id'];
		}

        //Products Stock update
		$restock = false;
		tep_update_productS_stock($product_parent_id_db,$order->products[$i]['qty'],$restock);
				
		//Products Attributes Stock update - START			
			tep_update_badge_products_attributes_stock(tep_get_prid($order->products[$i]['id']),$order->products[$i]['qty'],$languages_id);	  	  
		//END   
		
      }

      //End



      $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];

      $stock_progressed = $stock_values['progressed_quantity'] + $order->products[$i]['qty']; //July 18 2012 :: QUICK UPDATES
	  
	  tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' , progressed_quantity = '".$stock_progressed."' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
	  
	  //Low Stock Level Email  :: Aug 16 2012 - START
		tep_check_low_stock_level(tep_get_prid($order->products[$i]['id']),"product",$languages_id);
	  //Low Stock Level Email  :: Aug 16 2012 - End
	  

      if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {

        $all_subs_zero = 1;

        $products_id_tmp = tep_get_prid($order->products[$i]['id']);

        //Check to see if product is a sub product

        if(tep_subproducts_parent($products_id_tmp)) {

          $products_id_query = tep_subproducts_parent($products_id_tmp);

          // update parent product stock qty

          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $order->products[$i]['qty'] . " where products_id = '" . $products_id_query . "'");

          // get all sub product id's

          $qty_check_query_raw = tep_db_query("SELECT p.products_id, p.products_quantity

                                                 from " . TABLE_PRODUCTS . " p

                                               WHERE p.products_parent_id = '" . $products_id_query . "'");

          while ($qty_check_query = tep_db_fetch_array($qty_check_query_raw)) {

            // check stock or all sub product_id's

            if ( $qty_check_query['products_quantity'] > 0) {

              $all_subs_zero = 0;

              break;

            }

          }

        }

        // if product is a sub and all other subs are zero qty, set parent and sub to out of stock, else just set sub

        if ($all_subs_zero == 1 && isset($products_id_query)){

          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $products_id_query . "'");

          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $products_id_tmp . "'");

        } else {

          tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . $products_id_tmp . "'");

        }

      }          

    }

  }

 //Mar 10 2011 - Add badge comment received at badge product creation - (Badge product only)
  
  $sel_badge_comment = tep_db_query("SELECT badge_comment FROM ". TABLE_PRODUCTS ." WHERE products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
  
  $badge_comment_data = tep_db_fetch_array($sel_badge_comment);
  
   if($badge_comment_data['badge_comment']!="") {
   
	  $sql_data_array = array('orders_id' => $insert_id,
	
							'orders_status_id' => $order->info['order_status'],
	
							'date_added' => 'now()',
	
							'customer_notified' => '1',
							
							'admin_users_id' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
	
							'comments' => $order->products[$i]['name']." : ".$badge_comment_data['badge_comment']);
	
	  tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
	  
  }
	  
  // Add badge comment End

  // Update products_ordered (for bestsellers list)

  tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

  $sql_data_array = array('orders_id' => $insert_id,

                          'products_id' => tep_get_prid($order->products[$i]['id']),

                          'products_model' => $order->products[$i]['model'],

                          'products_name' => $order->products[$i]['name'],

                          'products_price' => $order->products[$i]['price'],

                          'final_price' => $order->products[$i]['final_price'],

                          'products_tax' => $order->products[$i]['tax'],

                          'products_quantity' => $order->products[$i]['qty'],

						  'products_purchase_number' => $order->products[$i]['products_purchase_number'],
						  
						  'badge_comment' => $badge_comment_data['badge_comment'], //Mar 10 2011

                          'vendors_id' => $order->products[$i]['vendors_id']);

  tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);

  $order_products_id = tep_db_insert_id();
  
  //insert orders products costs 
  $products_costs = tep_get_products_costs(tep_get_prid($order->products[$i]['id']));
  
  $sql_data_array = array('orders_id' => $insert_id,
                          'products_id' => tep_get_prid($order->products[$i]['id']),
                          'orders_products_id' => $order_products_id,
                          'labour_cost' => $products_costs['labour_cost'],
                          'overhead_cost' => $products_costs['overhead_cost'],
                          'material_cost' => $products_costs['material_cost'],						  
                          'products_quantity' => $order->products[$i]['qty']);
  
  tep_db_perform("orders_products_costs", $sql_data_array);
  
  $order_products_costs_id = tep_db_insert_id();

  $order_total_modules->update_credit_account($i);//ICW ADDED FOR CREDIT CLASS SYSTEM

  $products_ordered_attributes = '';

  if (isset($order->products[$i]['attributes'])) {

    for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {

      $sql_data_array = array('orders_id' => $insert_id,

                              'orders_products_id' => $order_products_id,

                              'products_options' => $order->products[$i]['attributes'][$j]['option'],

                              'products_options_values' => $order->products[$i]['attributes'][$j]['value'],

                              'options_values_price' => $order->products[$i]['attributes'][$j]['price'],

                              'price_prefix' => $order->products[$i]['attributes'][$j]['prefix'],

                              'products_options_id' => $order->products[$i]['attributes'][$j]['option_id'],

                              'products_options_values_id' => $order->products[$i]['attributes'][$j]['value_id']);

      tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
	  
	  //Aug 07 2012 :: QUICK UPDATES :: START	  
	  
	  $pov_query = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_OPTIONS_VALUES." WHERE products_options_values_id='".$order->products[$i]['attributes'][$j]['value_id']."' AND language_id='".(int)$languages_id."'");
	  
	  $pov_array = tep_db_fetch_array($pov_query);	  
	  
	  $options_stock_left = $pov_array['quantity'] - $order->products[$i]['qty'];
      
	  $options_stock_progressed = $pov_array['progressed_quantity'] + $order->products[$i]['qty']; 	  
	  
	  tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set quantity = '" . $options_stock_left . "' , progressed_quantity = '".$options_stock_progressed."' where products_options_values_id = '" .$order->products[$i]['attributes'][$j]['value_id'] . "'");	  
	  
	  //Low Stock Level Email  :: Aug 16 2012 - START
		tep_check_low_stock_level($order->products[$i]['attributes'][$j]['value_id'],"attribute",$languages_id);
	  //Low Stock Level Email  :: Aug 16 2012 - End
	  
	  //Aug 07 2012:: QUICK UPDATES :: END
	  

      if (DOWNLOAD_ENABLED == 'true') {

        $attributes_query = "select pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename

                               from " . TABLE_PRODUCTS_ATTRIBUTES . " pa,

                                    " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad

                             where pa.products_id = '" . $order->products[$i]['id'] . "'

                               and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'

                               and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'

                               and pa.products_attributes_id = pad.products_attributes_id";

        $attributes = tep_db_query($attributes_query);

        $attributes_values = tep_db_fetch_array($attributes);

        if ( isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename']) ) {

          $sql_data_array = array('orders_id' => $insert_id,

                                  'orders_products_id' => $order_products_id,

                                  'orders_products_filename' => $attributes_values['products_attributes_filename'],

                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'],

                                  'download_count' => $attributes_values['products_attributes_maxcount']);

          tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);

        }

        if( (isset($order->products[$i]['attributes'])) && (!empty($order->products[$i]['attributes'])) ){

          $products_ordered_attributes .= "\n\t" . (isset($order->products[$i]['attributes'][$j]['option']) ? $order->products[$i]['attributes'][$j]['option'] : '') . ' ' . (isset($order->products[$i]['attributes'][$j]['value']) ? $order->products[$i]['attributes'][$j]['value'] : '') . ' ' . (isset($order->products[$i]['attributes'][$j]['prefix']) ? $order->products[$i]['attributes'][$j]['prefix'] : '') . ' ';

        }

      }

    }

  }  

  

  // Caculate totals

  if ( (!isset($total_weight)) || (empty($total_weight))) $total_weight = 0;

  if ( (!isset($total_tax)) || (empty($total_tax))) $total_tax = 0;

  if ( (!isset($total_cost)) || (empty($total_cost))) $total_cost = 0;

  if ( (!isset($total_products_price)) || (empty($total_products_price))) $total_products_price = 0;

  $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);

  $total_tax += tep_calculate_tax($total_products_price, $order->products[$i]['tax']) * $order->products[$i]['qty'];

  $total_cost += $total_products_price;

  

  $products_ordered .= $order->products[$i]['qty'] . ' x ' . tep_db_decoder($order->products[$i]['name']) . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "<br /> Purchase Number: ".$order->products[$i]['products_purchase_number']."\n";

}



//Modified Sep 13, 2010

//$total_tax += (($_SESSION['shipping']['cost'] / 100 ) * $order->info['tax_class_id']);



$order_total_modules->apply_credit();//ICW ADDED FOR CREDIT CLASS SYSTEM

// lets start with the email confirmation

$custname_qry = tep_db_query("select c.customers_firstname, a.entry_company from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and  c.customers_id = '" . $_SESSION['customer_id'] . "'");
    $custname = tep_db_fetch_array($custname_qry);

require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');
 

$email_order = "<div style='text-align:center; margin:0 auto; clear:both;'><img src='http://awardsplaquesinternational.com.au/images/api-nbi.jpg' alt='NBi with APi'/></div><br/>";


if ( ! isset($_SESSION['noaccount']) ) {

  $email_order .= "<b>Hello ".$custname['customers_firstname']."</b>\n" . EMAIL_TEXT_ORDER_PROCESS . STORE_NAME . "\n" .

                 EMAIL_SEPARATOR . "\n" .

                 "<b>".EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "</b>\n" .

                 EMAIL_TEXT_INVOICE_URL . ' ' .

                 tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .

                 "<b>".EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "</b>\n\n";

} else {

  $email_order .= STORE_NAME . "\n" .

                 EMAIL_SEPARATOR . "\n" .

                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .

                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";

}

if ($order->info['comments']) {

  $email_order .= tep_db_output($order->info['comments']) . "\n\n";

}

if ($order->info['due_date']) { 
 
  $email_order .= "<b>Due Date: </b>".tep_date_aus_format($order->info['due_date'],"short") . "\n\n";
  
}

$email_order .= EMAIL_TEXT_PRODUCTS . "\n" .

                EMAIL_SEPARATOR . "\n" .

                $products_ordered .

                EMAIL_SEPARATOR . "\n";

for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {

  $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";

}



// to display price break value - Modified Sep 13, 2010

// Modified Sep 08, 2010 to display Price Break Discount

		

if($order->info["price_break_amount"]>0) {

	$email_order .= "<p style='font-weight:bold;'>

	".STORE_NAME." Price Break Discount : you save $".number_format($order->info['price_break_amount'],2) ."</p>\n";

}



if ($order->content_type != 'virtual') {

  $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" .

                  EMAIL_SEPARATOR . "\n" .

                  tep_address_label($_SESSION['customer_id'], $_SESSION['sendto'], 0, '', "\n") . "\n";

}

$email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .

                EMAIL_SEPARATOR . "\n" .

                tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], 0, '', "\n") . "\n\n";



  $payment = $_SESSION['payment'];

  if (is_object($$payment)) {

    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" .

                    EMAIL_SEPARATOR . "\n";

    $payment_class = $$payment;

    $email_order .= $payment_class->title . "\n\n";

    if ($payment_class->email_footer) {

      $email_order .= $payment_class->email_footer . "\n\n";

    }

  }
  
  $email_order .= EMAIL_TEXT_FOOTER_UPDATED;

  tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT . $insert_id, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);


//Admin emails start

tep_mail(STORE_OWNER,STORE_OWNER_EMAIL_ADDRESS, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
// send emails to other people
if (SEND_EXTRA_ORDER_EMAILS_TO != '') {		
	$rem = array("<",">");
	$extra_emails = str_replace($rem,"",SEND_EXTRA_ORDER_EMAILS_TO);	
	foreach(explode(",",$extra_emails) as $emailkey=>$value) {	
		$exp = explode(" ",trim($value));
		$name = $exp[0];		
		$admail = $exp[1];			
		tep_mail($name, $admail, EMAIL_TEXT_SUBJECT,  $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');			
	}
}	
	
//Admin email send End
	

// multi-vendor shipping

if (defined('MVS_STATUS') && MVS_STATUS == 'true') {

  if ((defined('MVS_VENDOR_EMAIL_WHEN') && MVS_VENDOR_EMAIL_WHEN == 'Catalog') || 

      (defined('MVS_VENDOR_EMAIL_WHEN') && MVS_VENDOR_EMAIL_WHEN == 'Both')) {

    $status=$order->info['order_status'];

    if (isset($status)) {

      $order_sent_query = tep_db_query("select vendor_order_sent, vendors_id from " . TABLE_ORDERS_SHIPPING . " where orders_id = '" . $insert_id . "'");

      while ($order_sent_data = tep_db_fetch_array($order_sent_query)) {

        $order_sent_ckeck = $order_sent_data['vendor_order_sent'];

        $vendors_id = $order_sent_data['vendors_id'];

        if ($order_sent_ckeck == 'no') {

          $status='';

          $oID=$insert_id;

          $vendor_order_sent = false;

          $status=$order->info['order_status'];

          vendors_email($vendors_id, $oID, $status, $vendor_order_sent);

        }

      }

    }

  }

}

// multi-vendor shipping eof//

// load the after_process function from the payment modules

$payment_modules->after_process();

// record the customers order and ip address info for fraud screening process

$ip = $_SERVER['REMOTE_ADDR'];

$proxy = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']: '' );

if($proxy != ''){

  $ip = $proxy;

}

$sql_data_array = array( 'order_id' => $insert_id,

                         'ip_address' => $ip);

tep_db_perform('algozone_fraud_queries', $sql_data_array);

$cart->reset(true);

// unregister session variables used during checkout

unset($_SESSION['sendto']); unset($_SESSION['billto']); unset($_SESSION['shipping']); unset($_SESSION['payment']); unset($_SESSION['due_date']); unset($_SESSION['comments']);

if (isset($_SESSION['cot_gv'])) {  unset($_SESSION['cot_gv']); }

if(isset($_SESSION['credit_covers']))  { unset($_SESSION['credit_covers']); }

// RCI checkoutprocess unregister

echo $cre_RCI->get('checkoutprocess', 'unregister', false);

$order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM


// call function to create this order in Crm
 manage_crm_order($insert_id);	
 

tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $insert_id.'&customer_id='.$_SESSION['customer_id'], 'SSL'));

require(DIR_WS_INCLUDES . 'application_bottom.php');

?>