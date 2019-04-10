<?php
require('includes/configure.php');
require('includes/filenames.php');
require('includes/database_tables.php');
require('includes/functions/database.php');
require('includes/functions/general.php');
require('includes/functions/html_output.php');

tep_db_connect();


include_once("CRM-success/crm_mysql.php");
include_once("CRM-success/config_crm.php");
$config_array = configData();
$mysqlClass = new crm_mysql($config_array);
//connect to the mysql database
$link_crm = $mysqlClass -> dbConnect();


$data = array();

$data = $_POST;

$order->customer = $data['customer'];

$order->delivery = $data['delivery'];

$order->billing = $data['billing'];

$order->products = $data['products'];

$order->info = $data['info'];

$order_totals = $data["totals"];

$date 	= date('Y-m-d h:i:s');

//print_r($order);

//exit;


function is_file_exists($url){
    $ch = curl_init($url);    
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200){
       $status = true;
    }else{
      $status = false;
    }
    curl_close($ch);
   return $status;
}


?>

<?php
/*
include("includes/adodb5/adodb.inc.php");
 $nbidb = NewADOConnection('mysql');
 $nbidb->Connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
 */

$cus_qry = tep_db_query("SELECT customers_id, customers_orders_count from customers WHERE customers_email_address='".$order->customer['email_address']."'");
if(tep_db_num_rows($cus_qry)>0) {
	
	$cus_arr = tep_db_fetch_array($cus_qry);
	$final_customers_id = $cus_arr["customers_id"];
	$customers_orders_count = $cus_arr["customers_orders_count"];
	
} else {
	$cus_ins = tep_db_query("Insert into customers set customers_firstname='".$order->customer['firstname']."', 
				customers_lastname='".$order->customer['lastname']."',
				customers_gender='".$order->customer['gender']."',
				customers_email_address='".$order->customer['email_address']."',
				customers_password='".$order->customer['customers_password']."',
				customers_newsletter='".$order->customer['customers_newsletter']."',
				customers_telephone='".$order->customer['customers_telephone']."'");
	$final_customers_id = tep_db_insert_id();
	$customers_orders_count = 0;	
}

 
    $order_ins_rst = tep_db_query("INSERT into orders set customers_id = '".$final_customers_id."', 
					orders_source='dsi',
					customers_name='".$order->customer['firstname'] . " " . $order->customer['lastname']."',
					customers_company='".$order->customer['company']."',
					customers_street_address='".$order->customer['street_address']."',
					customers_suburb='".$order->customer['suburb']."',
					customers_city='".$order->customer['city']."',
					customers_postcode='".$order->customer['postcode']."',
					customers_state='".$order->customer['state']."',
					customers_country='".$order->customer['country']['title']."',
					customers_telephone='".$order->customer['telephone']."',
					customers_email_address='".$order->customer['email_address']."',
					customers_address_format_id='".$order->customer['format_id']."',
					
					delivery_name='".$order->delivery['firstname'] . ' ' . $order->delivery['lastname']."',
					delivery_company='".$order->delivery['company']."',
					delivery_street_address='".$order->delivery['street_address']."',
					delivery_city='".$order->delivery['city']."',
					delivery_suburb='".$order->delivery['suburb']."',
					delivery_state='".$order->delivery['state']."',
					delivery_postcode='".$order->delivery['postcode']."',
					delivery_country='".$order->delivery['country']['title']."',
					delivery_address_format_id='".$order->delivery['format_id']."',
					delivery_telephone='".$order->delivery['telephone']."',
					delivery_fax='".$order->delivery['fax']."',
					delivery_email_address='".$order->delivery['email_address']."',
					
					billing_name='".$order->billing['firstname'] . ' ' . $order->billing['lastname']."',
					billing_company='".$order->billing['company']."',
					billing_street_address='".$order->billing['street_address']."',
					billing_city='".$order->billing['city']."',
					billing_suburb='".$order->billing['suburb']."',
					billing_state='".$order->billing['state']."',
					billing_postcode='".$order->billing['postcode']."',
					billing_country='".$order->billing['country']['title']."',
					billing_address_format_id='".$order->billing['format_id']."',
					billing_telephone='".$order->billing['telephone']."',
					billing_fax='".$order->billing['fax']."',
					billing_email_address='".$order->billing['email_address']."',
					
					date_purchased='".$date."',
					last_modified='".$date."',
					orders_status='".$order->info['order_status']."',
					currency='".$order->info['currency']."',
					currency_value='".$order->info['currency_value']."',
					purchase_number='".$order->info['purchase_number']."',
					due_date='".(isset($order->info['due_date']) ? $order->info['due_date'] : '')."',
					ipaddy='".$ip."',
					ipisp='".$isp."',
					
					payment_method='".$order->info['payment_method']."',
					payment_info='".$order->info['payment_info']."',
					cc_type='".(isset($order->info['cc_type']) ? $order->info['cc_type'] : '')."',
					cc_owner='".(isset($order->info['cc_owner']) ? $order->info['cc_owner'] : '')."',
					
					cc_number='".(isset($cc_number1) ? $cc_number1 : '')."',
					cc_expires='".(isset($cc_expires1) ? $cc_expires1 : '')."',
					
					cc_start='".(isset($order->info['cc_start']) ? $order->info['cc_start'] : '')."',
					cc_issue='".(isset($order->info['cc_issue']) ? $order->info['cc_issue'] : '')."'"); 
 
    $final_orders_id = tep_db_insert_id();
	
 
	 $customers_orders_count = $customers_orders_count +1;
	  
	 for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
						  
		$ot_rst = tep_db_query("INSERT INTO orders_total SET orders_id = '".$final_orders_id."', 
					title='".$order_totals[$i]['title']."',
					text='".$order_totals[$i]['text']."',
					value='".$order_totals[$i]['value']."',
					class='".$order_totals[$i]['code']."',
					sort_order='".$order_totals[$i]['sort_order']."'");
		
	 }
	 
	 $osh_rst = tep_db_query("INSERT INTO orders_status_history SET orders_id = '".$final_orders_id."', 
					orders_status_id='".$order->info['order_status']."',
					date_added='".$date."',
					customer_notified='1',
					admin_users_id='".$order->customer['firstname'] . ' ' . $order->customer['lastname']."',
					comments='".$order->info['comments']."'");
	 
						
 
	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
		
		$prod_rst = tep_db_query("INSERT INTO products SET products_source='dsi', 
					products_quantity='".$order->products[$i]['products_quantity']."',
					products_model='".$order->products[$i]['model']."',
					products_image='".$order->products[$i]['products_image']."',
					
					products_image_med='".$order->products[$i]['products_image_med']."',
					products_image_lrg='".$order->products[$i]['products_image_lrg']."',
					
					products_price='".$order->products[$i]['products_price']."',
					products_date_added='".$order->products[$i]['products_date_added']."',
					
					products_last_modified='".$order->products[$i]['products_last_modified']."',
					products_date_available='".$order->products[$i]['products_date_available']."', 
					products_status='0',
					manufacturers_id='".$order->products[$i]['manufacturers_id']."',
					
					products_tax_class_id='".$order->products[$i]['products_tax_class_id']."',
					products_parent_id='".$order->products[$i]['products_parent_id']."', 
					user_id='".$final_customers_id."',
					badge_namefile='".$order->products[$i]['badge_namefile']."',
					
					badge_logo='".$order->products[$i]['badge_logo']."',
					badge_data='".$order->products[$i]['badge_data']."', 
					default_product_id='".$order->products[$i]['default_product_id']."',
					max_lines_count='".$order->products[$i]['max_lines_count']."',
					
					max_images_count='".$order->products[$i]['max_images_count']."',
					default_texts='".$order->products[$i]['default_texts']."', 
					products_group_access='".$order->products[$i]['products_group_access']."',
					products_nav_access='".$order->products[$i]['products_nav_access']."',
					
					products_text='".$order->products[$i]['products_text']."',
					badge_comment='".$order->products[$i]['badge_comment']."', 
					labour_cost='".$order->products[$i]['labour_cost']."',
					material_cost='".$order->products[$i]['material_cost']."',
					overhead_cost='".$order->products[$i]['overhead_cost']."',
					overall_quantity='".$order->products[$i]['overall_quantity']."', 
					progressed_quantity='".$order->products[$i]['progressed_quantity']."'");
					
		$final_products_id = tep_db_insert_id();

		$prodesc_rst = tep_db_query("INSERT INTO products_description SET products_id='".$final_products_id."', 
						products_name='".$order->products[$i]['name']."', products_description='".$order->products[$i]['name']."', language_id='1'");
		
		$procat_rst = tep_db_query("INSERT INTO products_to_categories SET products_id='".$final_products_id."', 
						categories_id ='".$order->products[$i]['categories_id']."'");
		
		
		//upload filesize//Logo files to zip
		$productLogo = explode(",",$order->products[$i]['badge_logo']);
		for ($h=0; $h<count($productLogo); $h++){
			if(!empty($productLogo[$h])) {
				
				$bl_src_image = 'http://domedstickersinternational.com.au/images/'.$productLogo[$h];
				if(is_file_exists($bl_src_image)) {
					$bl_target_image = $_SERVER['DOCUMENT_ROOT']."/images/".$productLogo[$h];	
					@copy($bl_src_image,$bl_target_image);
					
				}
				
			}
		}
			
		//name list files to zip
		$namefiles = explode(",",$order->products[$i]['badge_namefile']);
		for ($g=0; $g<count($namefiles); $g++){
			if(!empty($namefiles[$g])) {
				
				$nf_src_image = 'http://domedstickersinternational.com.au/images/users_names/'.$namefiles[$g];
				if(is_file_exists($nf_src_image)) {
					$nf_target_image = $_SERVER['DOCUMENT_ROOT']."/images/users_names/".$namefiles[$g];	
					@copy($nf_src_image,$nf_target_image);
				}
				
			}
		}
		
		if(!empty($order->products[$i]['products_image'])) {
			$bd_src_image = 'http://domedstickersinternational.com.au/images/'.$order->products[$i]['products_image'];
			if(is_file_exists($bd_src_image)) {
				$bd_target_image = $_SERVER['DOCUMENT_ROOT']."/images/".$order->products[$i]['products_image'];	
				@copy($bd_src_image,$bd_target_image);
			}
		}
			
		$popt = $order->products[$i]["options"];
		
		for($r=0; $r<count($popt); $r++) {
			
			$pbo_rst = tep_db_query("INSERT INTO products_badge_options SET products_id='".$final_products_id."', options_id='".$popt[$r]["options_id"]."',
						options_type='".$popt[$r]["options_type"]."', options_values_id='".$popt[$r]["options_values_id"]."', 
						options_text='".$popt[$r]["options_text"]."', options_desc='".$popt[$r]["options_desc"]."'");
		
		}
		
						  
		$ord_pro_rst = tep_db_query("INSERT INTO orders_products SET orders_id='".$final_orders_id."', products_id='".$final_products_id."', 
						products_name='".$order->products[$i]['name']."', products_model ='".$order->products[$i]['model']."', products_price='".$order->products[$i]['price']."', 
						final_price='".$order->products[$i]['final_price']."', products_tax='".$order->products[$i]['tax']."', products_quantity='".$order->products[$i]['qty']."', 
						products_purchase_number='".$order->products[$i]['products_purchase_number']."', badge_comment='".$order->products[$i]['badge_comment']."',  vendors_id='".$order->products[$i]['vendors_id']."'");
						
		$final_orders_products_id = tep_db_insert_id();
		
		
		for ($j=0, $m=sizeof($order->products[$i]["attributes"]); $j<$m; $j++) {
			
	  
			$pa_rst = tep_db_query("INSERT INTO orders_products_attributes SET orders_id= '".$final_orders_id."', orders_products_id= '".$final_orders_products_id."', products_options= '".$order->products[$i]['attributes'][$j]['option']."', products_options_values= '".$order->products[$i]['attributes'][$j]['value']."', options_values_price= '".$order->products[$i]['attributes'][$j]['price']."', price_prefix= '".$order->products[$i]['attributes'][$j]['price_prefix']."', products_options_id= '".$order->products[$i]['attributes'][$j]['option_id']."', products_options_values_id = '".$order->products[$i]['attributes'][$j]['value_id']."'");
			
		}

		$opc_rst = tep_db_query("INSERT INTO orders_products_costs SET orders_id= '".$final_orders_id."', orders_products_id= '".$final_orders_products_id."', products_id= '".$final_products_id."', products_quantity= '".$order->products[$i]['qty']."', labour_cost= '".$order->products[$i]['labour_cost']."', material_cost= '".$order->products[$i]['material_cost']."', overhead_cost= '".$order->products[$i]['overhead_cost']."'");

		$return["final_products_id"][$i] = $final_products_id;
		$return["final_orders_products_id"][$i] = $final_orders_products_id;
	
	}
 
	$return["final_customers_id"] = $final_customers_id;
	$return["final_orders_id"] = $final_orders_id;
 
	//echo json_encode($return);
	//exit;
	
?>