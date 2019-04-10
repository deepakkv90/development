<?php
	//echo "select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id IN(24,62) and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'  order by cd.categories_id ASC";
	$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'  order by cd.categories_id ASC");
	
	//echo "select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id IN ('238','242','248','252','254') and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'  order by cd.categories_id ASC";
	
	if(tep_db_num_rows($categories_query)>0) {
	$txt1 = '[';
	while ($categories = tep_db_fetch_array($categories_query)) {
		$products_string = tep_get_products_by_category($categories["categories_id"]);
		
		if(!empty($products_string)) {
		
			$products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_price, p.products_status, p.products_model, p.products_min_order_qty, p.products_price1,  p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price9, p.products_price10, p.products_price11, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_price9_qty, p.products_price10_qty,p.products_price11_qty 
                                                          from " . TABLE_PRODUCTS . " p LEFT JOIN 
                                                               " . TABLE_PRODUCTS_DESCRIPTION . " pd ON (p.products_id = pd.products_id and pd.language_id = " . (int)$languages_id . ") WHERE ( p.products_parent_id = 0 and p.products_id IN (".$products_string.") OR (p.products_parent_id IN (".$products_string.")) )
                                                          and (p.user_id is NULL or p.user_id = '' ) and p.products_status=1 ORDER BY sort_order, pd.products_name");
														  												  
			while($p = tep_db_fetch_array($products_query)) {
				if($p['products_name'] != ''){			
				$pa_qry = tep_db_query("SELECT pa.options_values_id, pov.products_options_values_name, pa.options_values_price FROM products_attributes pa LEFT JOIN products_options_values pov ON (pa.options_values_id = pov.products_options_values_id and pov.language_id=".(int)$languages_id.") WHERE pa.products_id = ".$p["products_id"]);
				$patxt = "";
				while($pa = tep_db_fetch_array($pa_qry)) {
					$patxt .= '{"id":'.$pa["options_values_id"].',"name":"'.$pa["products_options_values_name"].'(+'.$pa["options_values_price"].' $)","surcharge":'.$pa["options_values_price"].'},';
				}
				$patxt = substr($patxt, 0, -1);
				
				
				$txt1 .= '{"id":'.$p["products_id"].',"name":"'.$p["products_name"].'","prices":[{"min_qty":'.$p["products_min_order_qty"].',"ppu":'.$p["products_price"].'},{"min_qty":'.$p["products_price1_qty"].',"ppu":'.$p["products_price1"].'},{"min_qty":'.($p["products_price2_qty"]+1).',"ppu":'.$p["products_price2"].'},{"min_qty":'.($p["products_price3_qty"]+1).',"ppu":'.$p["products_price3"].'},{"min_qty":'.($p["products_price4_qty"]+1).',"ppu":'.$p["products_price4"].'},{"min_qty":'.($p["products_price5_qty"]+1).',"ppu":'.$p["products_price5"].'},{"min_qty":'.($p["products_price6_qty"]+1).',"ppu":'.$p["products_price6"].'},{"min_qty":'.($p["products_price7_qty"]+1).',"ppu":'.$p["products_price7"].'},{"min_qty":'.($p["products_price8_qty"]+1).',"ppu":'.$p["products_price8"].'},{"min_qty":'.($p["products_price9_qty"]+1).',"ppu":'.$p["products_price9"].'},{"min_qty":'.($p["products_price10_qty"]+1).',"ppu":'.$p["products_price10"].'},{"min_qty":'.($p["products_price11_qty"]+1).',"ppu":'.$p["products_price11"].'}],"fittings":['.$patxt.']},';
				
				//echo "id:" . $p["products_id"] . ",name:" . $p["products_name"]."<br>";
				}
			}
		}
	}
	$badgedef = substr($txt1, 0, -1) . ']';
	
	}
	
	//echo $badgedef;
	
	
/* Installed shipping modules code start */

$module_type = 'shipping';
$module_directory = DIR_FS_CATALOG . DIR_WS_MODULES . 'shipping/';

$installed_arr = explode(";",MODULE_SHIPPING_INSTALLED);
$quotes = array();
$deliveryArr = array();
$delivery_txt = "[";
for ($i=0, $n=sizeof($installed_arr); $i<$n; $i++) {
	$file = $installed_arr[$i];
	include(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/' . $module_type . '/' . $file);
	include($module_directory . $file);
	$class = substr($file, 0, strrpos($file, '.'));	
	
	if (tep_class_exists($class)) {
		
		$module = new $class;

		$quotes[] = $module->quote($method="instant");

		$deliveryArr[$i]["id"] = $quotes[$i]["id"];
		$deliveryArr[$i]["name"] = $quotes[$i]["module"];
		$deliveryArr[$i]["cost"] = $quotes[$i]["methods"][0]["cost"];
		
		$delivery_txt .=  '{"id":"'.$quotes[$i]["id"].'",
						"name":"'.$quotes[$i]["module"].'",
						"options":[{"id":"'.$quotes[$i]["id"].'", "name":"'.$quotes[$i]["module"].'", "charge":'.$quotes[$i]["methods"][0]["cost"].', "tires":[]}]},';

	}
}
$delivery_txt = substr($delivery_txt, 0, -1) . ']';

/* Installed shipping modules code end */
 
 
/* Installed payment modules code start */
	$payment_arr = explode(";",MODULE_PAYMENT_INSTALLED);

	for ($i=0, $n=sizeof($payment_arr); $i<$n; $i++) {
		$file = $payment_arr[$i];
		require_once(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/payment/' . $file);
		require_once(DIR_FS_CATALOG . DIR_WS_MODULES . 'payment/' . $file);
		$class = substr($file, 0, strrpos($file, '.'));	
		if (tep_class_exists($class)) {		
			$module = new $class;
			$pay = $module->selection();
			$payment_txt[$i]["id"]=  $pay["id"];
			$payment_txt[$i]["name"] = $pay["module"];	
		}
	}

/* Installed payment modules code end */
?>

<style type="text/css">	
	
	
form#instantQuoteForm {
    /*display: none;*/
    width: 100%;
}
h2.instantQuote {
    color: #c2001c;
    font-size: 150%;
    padding: 0 0 10px;
    text-align: left !important;
}
.letterhead, .letterfoot {
    display: none;
}
table.instantQuote {
    font-size: 12px;
    margin-bottom: 10px;
    width: 100%;
}
table.instantQuote th {
    color: #315f89;
}
table.instantQuote th, table.instantQuote td {
    font-weight: bold;
    padding: 0 2px 12px;
    text-align: left;
    vertical-align: middle;
}
table.instantQuote td select {
    font-size: 11px;
    width: 98%;
    border: none;
    box-shadow: 0 0 8.28px 0.72px rgba(0, 0, 0, 0.15);
    -webkit-box-shadow: 0 0 8.28px 0.72px rgba(0, 0, 0, 0.15);
}
table.instantQuote th.price, table.instantQuote td.price {
    padding-left: 8px;
    vertical-align: middle;
    width: 4.2em;
}
table.instantQuote tfoot {
}
table.instantQuote tfoot th, table.instantQuote tfoot td {
    border-top: 1px solid #cacaca;
    padding-top: 12px;
}
table.instantQuote tfoot strong {
    color: #315f89;
    float: left;
    font-weight: bold;
    text-align: right;
    width: 80%;
}
table.instantQuote tfoot .price {
    float: right;
    width: 4.2em;
}
table#quoteBadges.instantQuote {
}
table#quoteBadges.instantQuote th.badge, table#quoteBadges.instantQuote td.badge {
    width: 55%;
}
table#quoteBadges.instantQuote th.qty, table#quoteBadges.instantQuote td.qty {
    width: 5em;
}
table#quoteBadges.instantQuote td.qty input {
    font-size: 11px;
    margin: 0;
    width: 98%;
    font-style: normal;
}
#customer_login .u-column1, #customer_login .u-column2 {
	    width: 100%;
}
table#quoteBadges.instantQuote th.fitting, table#quoteBadges.instantQuote td.fitting {
    width: 25%;
}
table#quoteDelivery.instantQuote {
}
table#quoteDelivery.instantQuote th.group, table#quoteDelivery.instantQuote td.group {
    width: 65%;
}
table#quoteDelivery.instantQuote th.option, table#quoteDelivery.instantQuote td.option {
    width: 35%;
}
table#quoteDelivery.instantQuote-WE {
}
table#quoteDelivery.instantQuote-WE th.group, table#quoteDelivery.instantQuote-WE td.group {
    width: 50%;
}
table#quoteDelivery.instantQuote-WE th.option, table#quoteDelivery.instantQuote-WE td.option {
    width: 50%;
}
table#quoteTotals.instantQuote {
    margin-bottom: 20px;
    margin-left: auto;
    margin-top: 10px;
    width: auto;
}
table#quoteTotals.instantQuote th {
    padding-left: 10px;
    padding-right: 4em;
}
table#quoteTotals.instantQuote tr.last th, table#quoteTotals.instantQuote tr.last td {
    padding-bottom: 24px;
}
table#quoteTotals.instantQuote tr.grandTotal {
}
table#quoteTotals.instantQuote tr.grandTotal th, table#quoteTotals.instantQuote tr.grandTotal td {
    background-color: #c2001c;
    color: #fff;
    padding-bottom: 8px;
    padding-top: 8px;
}
#quoteCoupon {
    height: 3em;
}
#quoteCoupon .couponRow {
    float: left;
    width: 100%;
}
#quoteCoupon .activeCode, #quoteCoupon input.textBox {
    float: left;
    margin: 0 10px 10px 0;
    width: 61%;
}

#quoteCoupon .activeCode {
    border: 1px solid #cacaca;
    color: #000;
    font-family: Verdana,Geneva,sans-serif;
    font-size: 13px;
    padding: 2px 3px;
}
#quoteCoupon .glassButton {
    float: left;
}
#quoteCoupon .loadAnim {
    background: rgba(0, 0, 0, 0) url("/images/ajax-loader.gif") no-repeat scroll 50% 50%;
    display: none;
    float: left;
    height: 22px;
    overflow: hidden;
    text-indent: -9.9em;
    width: 22px;
}
div.submitQuote {
    border-top: 1px solid #cacaca;
    padding-top: 20px;
}
div.submitQuote h2 {
    clear: both;
    color: #000;
    font-size: 150%;
    padding: 10px 0;
}
div.submitQuote h2.print {
    float: left;
    padding: 0 1em 0 0;
}
div.submitQuote input.textBox {
    color: #4b4b4b;
	float: left;
    margin: 0 10px 10px 0;
    width: 77%;
	border: 1px solid #4b4b4b;
}
.clear {
    clear: both;
}
div.submitQuote .textarea {
    color: #4b4b4b;
	float: left;
    margin: 0 10px 10px 0;
    width: 77%;
	height:100px;
	border: 1px solid #4b4b4b;
	border: none;
    box-shadow: 0 0 8.28px 0.72px rgba(0, 0, 0, 0.15);
    -webkit-box-shadow: 0 0 8.28px 0.72px rgba(0, 0, 0, 0.15);
}

div.submitQuote .glassButton {
    float: left;
	clear:both;
}
div.submitQuote .note {
    color: #777;
    font-size: 78.57%;
    font-style: italic;
    line-height: 1.45455;
    padding-top: 20px;
}
td.pageHeading {
    text-align: center;
}
</style>
<div class="mid-cont">
	<div id="customer_login">
	<div class="u-column1 col-1">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><h1><?php echo HEADING_TITLE; ?></h1></td>
      </tr>
	  
	  
	  
		<?php
		  if ($messageStack->size('contact') > 0) {
		?>
			  <tr>
				<td><?php echo $messageStack->output('contact'); ?></td>
			  </tr>
		<?php
		  }
		?>
		
		<tr><td>
	<?php
	
	if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
           
		   $captcha_err = "";
			if($_SESSION["captcha_code"] == $_POST["captcha"]) {
			
				$user_name = tep_db_prepare_input($_POST['userName']);
				$user_phone = tep_db_prepare_input($_POST['userPhone']);
				
				$email_address = strtolower(tep_db_prepare_input($_POST['emailAddr']));
				
				$email_ref = tep_db_prepare_input($_POST['emailRef']); 
				
				$email_json = tep_db_prepare_input($_POST['emailJson']);	
				$obj = "$email_json";	
				$email_arr = json_decode(utf8_encode($obj), true);
				$products = $email_arr["badges"];
				$ip = tep_get_ip_address();
				
				$quote_pro_txt = "<table width='100%' border='0'><tr><td width='40%' style='border-bottom:1px solid #cacaca; padding:5px; font-weight:bold;'>".TABLE_HEADING_PRODUCTS."</td><td width='10%' style='border-bottom:1px solid #cacaca; padding:5px; font-weight:bold;'>".TABLE_HEADING_QUANTITY."</td><td width='20%' style='border-bottom:1px solid #cacaca; padding:5px; font-weight:bold;'>".TABLE_HEADING_FITTING."</td><td width='20%' style='border-bottom:1px solid #cacaca; padding:5px; font-weight:bold;'>".TABLE_HEADING_UNIT_PRICE."</td></tr>";	
				$subtotal = 0;
				for($i=0;$i<count($products);$i++) {		
					$quote_pro_txt .= '<tr><td style="border-bottom:1px solid #cacaca; padding:5px;">'.utf8_decode($products[$i]["name"]).'</td><td style="border-bottom:1px solid #cacaca; padding:5px;">'.$products[$i]["qty"].'</td><td style="border-bottom:1px solid #cacaca; padding:5px;">'.utf8_decode($products[$i]["fitting"]).'</td><td style="border-bottom:1px solid #cacaca; padding:5px;">$ '.$products[$i]["price"].'</td></tr>';
					$subtotal += $products[$i]["price"];
				}	
				
				if(!empty($email_arr["payment"]["name"])) {
					$quote_pro_txt .= '<tr><td colspan="3" style="border-bottom:1px solid #cacaca; padding:5px;">'.utf8_decode($email_arr["payment"]["name"]).'</td><td style="border-bottom:1px solid #cacaca; padding:5px;">$ '.$email_arr["payment"]["price"].'</td></tr>';
				}
				
				if(!empty($email_arr["delivery"]["service"])) {
					$quote_pro_txt .= '<tr><td colspan="3" style="border-bottom:1px solid #cacaca; padding:5px;">'.utf8_decode($email_arr["delivery"]["service"]).' - '.utf8_decode($email_arr["delivery"]["name"]).'</td><td style="border-bottom:1px solid #cacaca; padding:5px;">$ '.$email_arr["delivery"]["price"].'</td></tr>';
				}
					
				$quote_pro_txt .= "</table><br>";
		
				$quote_pro_txt .= "<table width='70%' border='0' align='right'>
					<tr><td width='60%' style='font-weight:bold;'>".TABLE_HEADING_SUB_TOTAL."</td><td width='40%'>$ ".$subtotal."</td></tr>
					";
				
				$discount = 0;
				if(!empty($email_arr["coupon"]["code"])) {
					$discount = ($subtotal*$email_arr["coupon"]["value"]);
					$quote_pro_txt .= "<tr><td width='60%' style='font-weight:bold;'>".HEADING_DISCOUNT." - ".$email_arr["coupon"]["code"]."</td><td width='40%'>- $ ".($discount)."</td></tr>";
				}
				$total = ($subtotal+$email_arr["delivery"]["price"]+$email_arr["payment"]["price"])- $discount;
				
				if($email_arr["payment"]["price"]>0) {
					$quote_pro_txt .= "
				<tr><td width='60%' style='font-weight:bold;'>".HEADING_PAYMENT."</td><td width='40%'>$ ".$email_arr["payment"]["price"]."</td></tr>";
				}
				
				$quote_pro_txt .= "
				<tr><td width='60%' style='font-weight:bold;'>".HEADING_DELIVERY."</td><td width='40%'>$ ".$email_arr["delivery"]["price"]."</td></tr>
				<tr><td width='60%' style='font-weight:bold;'>".TABLE_HEADING_TOTAL."</td><td width='40%'>$ ".$total."</td></tr>";
				$quote_pro_txt .= "</table><br>";
				
				if(!empty($email_ref)) {
					$quote_pro_txt .= "<table width='100%' border='0'><tr><td>Your Reference Message</td></tr>
									<tr><td>".$email_ref."</td></tr>
									</table><br>";
				}
				
				$date_added = date("Y-m-d H:i:s");

				$header_mail_template = '<table bgcolor="#cccccc" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				<tbody>
					<tr>
						<td align="center" valign="middle" width="100%">
							<table align="center" bgcolor="#ffffff" border="0" cellpadding="8" cellspacing="2" height="493" width="633">
								<tbody>
								<tr>
									<td align="center" valign="top"><img src="'.HTTP_SERVER.'/images/nbi_instant_quote_banner.jpg" alt="NBi - confirmation" height="115" width="600"></td>
								</tr>
								<tr>
									<td align="left" height="498" valign="top"> <div style="padding:10px; line-height:20px;"> ';	
									
				$client_body_mail = '<span style="color:#0086e0"><font face="arial,helvetica,sans-serif" size="3">' . CLIENT_EMAIL_CONTACT_GREET . $user_name . '</font></span>' . "<br><br>" . $quote_pro_txt . "<br><br>";					
				
				$admin_body_mail = ADMIN_EMAIL_CONTACT_GREET . $user_name . "<br><br> Phone: " .$user_phone."<br><br>". 						
									CONTACT_EMAIL_ADDRESS . ' ' . $email_address . "<br><br>" . 
									$quote_pro_txt . "<br><br>";		
				
				$footer_mail_template = "</div>".EMAIL_CONTACT_FOOTER_TEXT . '</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
			</table>';
	
			/* smtp email validation start */
			/*require_once('includes/classes/smtp_validateEmail.class.php');
			
			$emails = array($email_address);
			
			$sender = STORE_OWNER_EMAIL_ADDRESS;
			
			$SMTP_Validator = new SMTP_validateEmail();
			// turn on debugging if you want to view the SMTP transaction
			$SMTP_Validator->debug = true;
			// do the validation
			$results = $SMTP_Validator->validate($emails, $sender);
			
			print_r($results);
			
			// view results
			foreach($results as $email=>$result) {
				// send email? 
			  if ($result) {
				  echo "Email address is valid";
				//mail($email, 'Confirm Email', 'Please reply to this email to confirm', 'From:'.$sender."\r\n"); // send email
			  } else {
				echo 'The email address '. $email.' is not valid';
			  }
			}
			
			exit;*/
			/* smtp email validation end */
			
			$check_email = explode("@", $email_address);
			
			/*
			print_r($check_email);
			
			if(!is_numeric($check_email[0]) && ($email_address!="") && (empty($_POST["fillspm"]))) {
				
				echo "Valid now";
				
			} else { echo "Not valid";  }
			
			exit;
			*/
			

			if(!is_numeric($check_email[0]) && ($email_address!="") && (empty($_POST["fillspm"])) && (count($products)>=1)) {
				
				$client_message = $header_mail_template . $client_body_mail . $footer_mail_template;
				
				//$mail->AddReplyTo(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);
				
				//$mail->SetFrom(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);

				$mail->AddReplyTo("programmer1@ajparkes.com.au", STORE_NAME);
				
				$mail->SetFrom("programmer1@ajparkes.com.au", STORE_NAME);
				
				$mail->AddAddress($email_address, $user_name);
					
				$mail->Subject    = EMAIL_CONTACT_SUBJECT;
					
				$mail->MsgHTML($client_message);
				$mail->Send();
				$mail->ClearAddresses();
				$mail->ClearAttachments();
				
				$sql_data_array = array('email_reference' => $email_ref,                               
										  'email_address' => $email_address,
										  'user_name' => $user_name,
										  'user_phone' => $user_phone,
										  'quote_content' => $email_json,
										  'date' => $date_added,
										  'ip' => $ip
										  );
				tep_db_perform(TABLE_INSTANT_QUOTE, $sql_data_array);
				
				if(STORE_OWNER_EMAIL_ADDRESS!="" && empty($_POST["fillspm"])) {
				
					$admin_message = $header_mail_template . $admin_body_mail . $footer_mail_template;
					
					$mail->SetFrom(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);	
					
					$mail->AddAddress(STORE_OWNER_EMAIL_ADDRESS, STORE_NAME);
					
					$mail->AddAddress("ananthan@ajparkes.com.au", STORE_NAME);
						
					$mail->Subject    = EMAIL_CONTACT_ADMIN_SUBJECT;
					
					$mail->MsgHTML($admin_message);
					
					$mail->Send();
				}
				
				$mail->ClearAddresses();
				$mail->ClearAttachments();
			
				tep_redirect(tep_href_link(FILENAME_PAGES, 'CDpath=0&pID=49', 'SSL'));
				
			}	else { $captcha_err = "Invalid Email."; }			
			
		} else { $captcha_err = "Invalid Captcha."; }
       
  }
	?>
	</td>
	</tr>
	
	</table>
	  
			
			<table border="0" cellspacing="2" cellpadding="2" align="center" width="90%">
              
              <tr>
				<td>
					<div style="padding:10px; line-height:20px;"> <?php echo CONTACT_QUOTE_PAGE_TEXT_HEADER; ?> </div>
				</td>
			  </tr>
			  <tr>
				<td>
					<span class="error"><?php if(!empty($captcha_err)) { echo $captcha_err; }?></span>
					
					<form id="instantQuoteForm" name="instantQuoteForm" method="post" enctype="multipart/form-data" action="<?php echo tep_href_link("instant_quote.php", '', 'SSL'); ?>">
					
					<!-- New quote form start -->
					<?php
					//echo tep_draw_form('frm_contact_quote', tep_href_link(FILENAME_INSTANT_QUOTE, '', 'SSL'), 'post', 'id="instantQuoteForm"  enctype="multipart/form-data"');

					echo tep_draw_hidden_field('action', 'process'); 
					echo tep_draw_hidden_field('lang', $languages_id,'id="hid_lang"');
					?>
					
					<h2 class="instantQuote"><?php echo HEADING_BADGES; ?></h2>
					<table id="quoteBadges" class="instantQuote" cellpadding="0" cellspacing="0" summary="Quote price of badges">
						<thead>
							<tr>
								<th scope="col" class="range"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
								<th scope="col" class="qty"><?php echo TABLE_HEADING_QUANTITY; ?></th>
								<th scope="col" class="fitting"><?php echo TABLE_HEADING_FITTING; ?></th>
								<th scope="col" class="price"><?php echo TABLE_HEADING_UNIT_PRICE; ?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="99">
									<strong><?php echo TABLE_HEADING_SUB_TOTAL; ?>: </strong>	
									<span class="price">$<span class="value">0.00</span></span>									
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<td class="range">
									<select name="badge">
										<option value=""><?php echo TEXT_NONE; ?></option>
									</select>
								</td>
								<td class="qty">
									<input type="text" class="textBox" name="qty" value="1" size="5" style="text-align: center;" />
								</td>
								<td class="fitting">
									<select name="fitting" disabled="disabled">
										<option value=""><?php echo TEXT_NONE; ?></option>
									</select>
								</td>
								
								<td scope="row" class="price">$<span class="value">0.00</span></td>
							</tr>
						</tbody>
					</table>
					<h2 class="instantQuote"><?php echo HEADING_PAYMENT; ?></h2>
					<table id="quotePayment" class="instantQuote" cellpadding="0" cellspacing="0">
						<tr>
							<td class="payment">
								<select name="payment">
									<option value=""><?php echo TEXT_NONE; ?></option>
									
									<?php
									
									foreach($payment_txt as $option) {
										echo '<option value="'.$option["id"].'">' . $option["name"] . '</option>';
									}
									
									?>
								</select>
							</td>
							<td><span class="price">$<span class="value">0.00</span></span></td>
						</tr>
					</table><br/>
					<h2 class="instantQuote"><?php echo HEADING_DELIVERY; ?></h2>
					<div class="deliveryDesc">
						<?php
						//echo TEXT_DELIVERY_DESC;
						?>
					</div>
					<table id="quoteDelivery" class="instantQuote" cellpadding="0" cellspacing="0" summary="Quote price of shipping service">
						
						<tfoot>
							<tr>
								<td colspan="2">
									<strong><?php echo TABLE_HEADING_SUB_TOTAL; ?>: </strong>
									<span class="price">$<span class="value">0.00</span></span>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<tr>
								<td class="group">
									<select name="group">
										<option value=""><?php echo TEXT_SELECT; ?></option>
										
										<!--<option value="1">1 Business day from Order Dispatch time</option>
										<option value="2">2 to 4 Business days Order Dispatch time</option>
										-->
										<?php
									
									foreach($deliveryArr as $option) {
										echo '<option value="'.$option["id"].'">' . $option["name"] . '</option>';
									}
									
									?>
										
									</select>
								</td>
								<td class="option">
									<select name="option" disabled="disabled">
										<option value=""><?php echo TEXT_NONE; ?></option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
					<h2 class="instantQuote"><?php echo HEADING_DISCOUNT; ?></h2>
					<div id="quoteCoupon" data-callback="instant_quote_coupon_process.php">
						<div class="couponRow addCoupon">
							<input type="text" title="Enter your coupon code" class="textBox" name="coupon" value="" />
							<div class="glassButton noPrint"><input type="button" class="button" style="padding: 6px 25px;" value="<?php echo TEXT_USE; ?>" /></div>
							<div class="loadAnim">...</div>
						</div>
						<div class="couponRow removeCoupon" style="display: none;">
							<div class="activeCode"></div>
							<div class="glassButton noPrint"><input class="button" style="padding: 6px 25px;" type="button" value="Remove" /></div>
						</div>
					</div>
					<table id="quoteTotals" class="instantQuote" cellpadding="0" cellspacing="0" summary="Total quote price of goods and shipping">
						<tbody>
							<tr class="badges">
								<th scope="row"><?php echo TABLE_HEADING_SUB_TOTAL; ?>: </th>
								<td class="price">$<span class="value">0.00</span></td>
							</tr>
							<tr class="discount" style="display: none;">
								<th scope="row"><?php echo HEADING_DISCOUNT; ?>: </th>
								<td class="price">-$<span class="value">0.00</span></td>
							</tr>
							<tr class="delivery">
								<th scope="row"><?php echo TABLE_HEADING_SERVICES; ?>: </th>
								<td class="price">$<span class="value">0.00</span></td>
							</tr>
							<tr class="paymentDisplay" style="display: none;">
								<th scope="row"><?php echo HEADING_PAYMENT; ?>: </th>
								<td class="price">$<span class="value">0.00</span></td>
							</tr>
							<tr class="grandTotal">
								<th scope="row"><?php echo TABLE_HEADING_TOTAL; ?>: </th>
								<td class="price">$<span class="value">0.00</span></td>
							</tr>
						</tbody>
					</table>
					<div class="submitQuote">
						
						<div class="noPrint">
							<h2 class="email"> <?php echo TEXT_EMAIL_QUOTE; ?></h2>
							<input type="hidden" name="emailJson" value="" />
							<input type="text" title="<?php echo TEXT_YOUR_NAME; ?>" class="textBox" name="userName" value="" />
							<input type="text" title="<?php echo TEXT_EMAIL_ADDRESS; ?>" class="textBox" name="emailAddr" value="" />
							<input type="text" title="<?php echo TEXT_PHONE_NUMBER; ?>" class="textBox" name="userPhone" value="" /><br>
							<div style="float:left; width:100%; font-weight:bold;"><?php echo TEXT_OPTIONAL_REFERENCE; ?></div><br>
							<textarea class="textarea" name="emailRef"></textarea><br>
							
							<input type="text" style="width:50%;" class="textBox" title="Enter Captcha" name="captcha" id="captcha" class="demoInputBox">
							<img style="float:left; width:10%;" id="captcha_code" src="load_captcha.php" />
							<input type="hidden" name="fillspm" value="" />
							<br/>
			  
							<div class="glassButton"><input class="button" id="sndQuote" type="button" value="<?php echo CONTACT_QUOTE_SUBMIT; ?>" /></div>
							<div class="spacer20 clear"></div>
							
							<!--<h2 class="print"><?php echo TEXT_PRINT_QUOTE; ?></h2>
							<div class="glassButton print"><button class="button"><?php echo TEXT_PRINT; ?></button></div>
							<div class="spacer10 clear"></div>-->
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>					
					</form>
					<!--New quote form end--><br/><br/>
					<div class="clear"></div>
					<div style="text-align:left;">**Note: Taxes will be applied when ordering from Australia.</div>
				</td>
			  </tr>
			  
            </table>		
	  
	  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="instantQuoteTable">
		  <tr>
			<td>
				<div style="padding:10px; line-height:20px;"> <?php echo CONTACT_QUOTE_PAGE_TEXT_FOOTER; ?> </div>
			</td>
		  </tr>	  
	  </table>

<noscript>
	<div class="contentArea">
		<p><strong>You must have Javascript enabled to use our instant quote generator.</strong></p>
	</div>
</noscript>
</div>
</div>
</div>
<script type="text/javascript">
	//<![CDATA[<!--
	
	//-------------------------------------------------------------------------------------
	
	function addslashes (str) {
		// Escapes single quote, double quotes and backslash characters in a string with backslashes  
		// 
		// version: 1102.614
		// discuss at: http://phpjs.org/functions/addslashes    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: Ates Goral (http://magnetiq.com)
		// +   improved by: marrtins
		// +   improved by: Nate
		// +   improved by: Onno Marsman    // +   input by: Denny Wardhana
		// +   improved by: Brett Zamir (http://brett-zamir.me)
		// +   improved by: Oskar Larsson Högfeldt (http://oskar-lh.name/)
		// *     example 1: addslashes("kevin's birthday");
		// *     returns 1: 'kevin\'s birthday'    
		return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
	}
	
	/**
	 * Converts the given data structure to a JSON string.
	 * Argument: arr - The data structure that must be converted to JSON
	 * Example: var json_string = array2json(['e', {pluribus: 'unum'}]);
	 * 			var json = array2json({"success":"Sweet","failure":false,"empty_array":[],"numbers":[1,2,3],"info":{"name":"Binny","site":"http:\/\/www.openjs.com\/"}});
	 * http://www.openjs.com/scripts/data/json_encode.php
	 */
	function array2json(arr) {
		var parts = [];
		var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

		for(var key in arr) {
			var value = arr[key];
			if(typeof value == "object") { //Custom handling for arrays
				if(is_list) parts.push(array2json(value)); /* :RECURSION: */
				else parts.push('"' + addslashes(key) + '":' + array2json(value)); /* :RECURSION: */
			} else {
				var str = "";
				if(!is_list) str = '"' + addslashes(key) + '":';

				//Custom handling for multiple data types
				if(typeof value == "number") str += value; //Numbers
				else if(value === false) str += 'false'; //The booleans
				else if(value === true) str += 'true';
				else str += '"' + addslashes(value) + '"'; //All other things
				// :TODO: Is there any more datatype we should be in the lookout for? (Functions?)
				parts.push(str);
			}
		}
		var json = parts.join(",");
		
		if(is_list) return '[' + json + ']';//Return numerical JSON
		return '{' + json + '}';//Return associative JSON
	}
	
	//-------------------------------------------------------------------------------------
	
	jQuery(document).ready(function($) {
		
		var badgeDefs = <?php echo $badgedef; ?>;	
				
		var deliveryDefs = <?php echo $delivery_txt; ?>;			
				
		var usingCoupon = null;
		
		var exchangeRate = 1 * 1.0;
		var vatRate = 0;
		
		var VAT_ON_DELIVERY = false;
		
		var CreateBadgeRow, UpdateDeliverySubtotal, UpdatePaymentSubtotal;
		
		
		// Function to round price using a specific precision
		var RoundPrice = function(price) {
			
			var prc = 100 * 1.0;
			return Math.round(price * prc) / prc;
			
		};
		
		// Function to remove all options from the given HTMLSelect element
		var RemoveSelectOptions = function(select) {
			
			for (var i=select.options.length-1; i>=0; --i) {
				select.remove(i);
			}
			
		};
		
		
		// Function to set a HTMLSelect-element's options to the given id-name pairs
		var SetSelectOptions = function(select, options) {
			
			RemoveSelectOptions(select);
			
			if (options.length < 1) {
				select.options[0] = new Option('<?php echo TEXT_NONE; ?>', '');
				select.disabled = true;
				return;
			}
			
			for (var i in options) {
				var myDef = options[i];
				//select.add(new Option(myDef.name, myDef.id));
				select.options[select.options.length] = new Option(myDef.name, myDef.id);
			}
			select.disabled = false;
			
		};
		
		
		// Search the given array to find and return an element with the specified id
		var FindOptionWithId = function(id, options) {
			
			id = parseInt(id);
			for (var i in options) {
				if (parseInt(options[i].id) == id) {
					return options[i];
				}
			}
			return null;
			
		};
		
		// Search the given array to find and return an element with the specified id
		var FindOptionWithIdString = function(id, options) {			
			
			for (var i in options) {
				if (options[i].id == id) {
					return options[i];
				}
			}
			return null;
			
		};
		
		
		// Update the prices in the totals table
		var UpdateQuoteTotals = function() {
			
			var badgesCharge = parseFloat($('#quoteBadges tfoot .price span.value').text());
			var deliveryCharge = parseFloat($('#quoteDelivery tfoot .price span.value').text());
			var paymentCharge = parseFloat($('#quotePayment .price span.value').text());
			var discountCharge = 0.0;
			
			var subtotalCharge = badgesCharge + deliveryCharge + paymentCharge;
			
			if (usingCoupon) {
				if (usingCoupon.type == 'fixed') {
					//discountCharge = ;
					discountCharge = Math.min(RoundPrice(usingCoupon.value * exchangeRate), badgesCharge);
				}
				else if (usingCoupon.type == 'percentage') {
					discountCharge = badgesCharge * usingCoupon.value;
				}
				subtotalCharge -= discountCharge;
			}
			
			var finalCharge, vatCharge;
			if (VAT_ON_DELIVERY) {
				finalCharge = RoundPrice(subtotalCharge * (vatRate + 1.0));
				vatCharge   = finalCharge - subtotalCharge;
			}
			else {
				vatCharge   = RoundPrice((badgesCharge - discountCharge) * vatRate);
				finalCharge = RoundPrice(subtotalCharge + vatCharge);
			}
			
			var $table = $('#quoteTotals > tbody');
			$table.children('tr.badges')		.find('.price .value').text(badgesCharge.toFixed(2));
			$table.children('tr.delivery')		.find('.price .value').text(deliveryCharge.toFixed(2));
			
			if(paymentCharge > 0) {
				$table.children('tr.paymentDisplay').show().find('.price .value').text(paymentCharge.toFixed(2));
			} else {
				$table.children('tr.paymentDisplay').hide();
			}
			
			$table.children('tr.subtotal')		.find('.price .value').text(subtotalCharge.toFixed(2));
			$table.children('tr.vat')			.find('.price .value').text(vatCharge.toFixed(2));
			$table.children('tr.grandTotal')	.find('.price .value').text(finalCharge.toFixed(2));
			(usingCoupon)
				? $table.children('tr.discount').show().find('.price .value').text(discountCharge.toFixed(2)) 
				: $table.children('tr.discount').hide();
		}
		
		
		// Update the subtotal price of the badge-specification table
		var UpdateBadgesSubtotal = function() {
			
			var myPrice = 0.0;
			$('#quoteBadges tbody .price span.value').each(function() {
				myPrice += Math.max(0.0, parseFloat(this.innerHTML));
			});
			
			$('#quoteBadges tfoot .price span.value').text(myPrice.toFixed(2));
			
			UpdateDeliverySubtotal(); // Because of tiered delivery
			UpdatePaymentSubtotal();
			//UpdateQuoteTotals();
			
		};
		
		
		// Update the price of a single badge-specification row
		var UpdateBadgeRowPrice = function($row) {
			
			var hasPrice = false;
			
			var qty = parseInt($row.find('input[name=qty]').val());
			
			if (qty) {
				
				var badgeID = $row.find('select[name=badge]').val();
				
				var badgeDef = FindOptionWithId(parseInt(badgeID), badgeDefs);
				if (badgeDef) {
					
					var myPrice = 0.0;
					for (var i in badgeDef.prices) {
						var priceDef = badgeDef.prices[i];
						if (qty >= priceDef.min_qty) {
							myPrice = parseFloat(priceDef.ppu);
						}
					}
					
					var fittingID = $row.find('select[name=fitting]').val();
					var fittingDef = FindOptionWithId(fittingID, badgeDef.fittings);
					if (fittingDef) {
						myPrice += parseFloat(fittingDef.surcharge);		
					}
					
					myPrice = RoundPrice(myPrice * exchangeRate);
					myPrice *= qty;
					//myPrice = Math.round(myPrice * exchangeRate * 100.0) * 0.01;
					
					$row.find('.price span.value').text(myPrice.toFixed(2));
					hasPrice = true;
				}
			}
			
			if (!hasPrice) {
				$row.find('.price span.value').text('0.00');
			}
			
			UpdateBadgesSubtotal();
			
		};
		
		
		// Callback function executed whenever the badge type is changed
		var OnBadgeChange = function(event) {
			
			var $trigger = $(event.target);
			var $row = $trigger.parents('tr').first();
			var $select = $row.find('select[name=fitting]').first();
									
			var value = $trigger.val();
			var beenSet = false;
			for (var i in badgeDefs) {
				if (badgeDefs[i].id == value) {
					SetSelectOptions($select.get(0), badgeDefs[i].fittings);
					beenSet = true;
				}
			}
			
			if (beenSet) {
				$row.removeClass('noPrint');
				if ($trigger.parents('tr').first().next().length < 1) {
					CreateBadgeRow();
				}
			}
			else {
				$row.addClass('noPrint');
				SetSelectOptions($select.get(0), []);
			}
			
		};
		
		
		// Prepare content and bind event handlers to the given badge-specification row
		var PrepareBadgeRow = function($row) {
			
			$select = $row.find('select[name=badge]');
			if ($select.length > 0) {
				var options = $.merge([{
					id		: '', 
					name	: '<?php echo TEXT_NONE; ?>'
				}], badgeDefs);
				SetSelectOptions($select.get(0), options);
				$select.change(OnBadgeChange);
			}
			
			$row.find('select, input:text')
				.change(function(event) {
					var $row = $(event.target).parents('tr').first();
					UpdateBadgeRowPrice($row);
				})
				.keyup(function(event) {
					$(this).change();
				});
			
			UpdateBadgeRowPrice($row);
			
		};
		
		
		// Create a new, 'blank' row at the end of the badge-specification table
		var $baseBadgeRow = $('#quoteBadges tbody').first().children('tr').first().detach();
		CreateBadgeRow = function() {
			
			var $tbody = $('#quoteBadges tbody').first();
			$tbody.append($baseBadgeRow.clone());
			
			var $row = $tbody.children('tr').last();
			PrepareBadgeRow($row);
			
		};
		
		/* Payment option added */
		
		// Update the subtotal price of the payment table
		UpdatePaymentSubtotal = function() {
			
			var paymentID = $('#quotePayment select[name=payment]').val();		
			var itemsPrice = parseFloat($('#quoteBadges tfoot .price span.value').text());
			var usePrice = 0;
					
			var hasPrice = false;

			if (!hasPrice) {
				$('#quotePayment .price .value').text('0.00');
			}
			
			UpdateQuoteTotals();
			
		};
		
		// Bind the onchange handler to the payment-option dropdown
		$('#quotePayment select[name=payment]').change(UpdatePaymentSubtotal);
		
		/* Payment option added end */
		
		// Update the subtotal price of the delivery table
		UpdateDeliverySubtotal = function() {
			
			var deliveryID = $('#quoteDelivery select[name=option]').val();
			
			var itemsPrice = parseFloat($('#quoteBadges tfoot .price span.value').text());
			
			var hasPrice = false;
			for (var i in deliveryDefs) {
				var myDef = FindOptionWithIdString(deliveryID, deliveryDefs[i].options);
				if (myDef) {
					
					var usePrice = -1.0;
					
					for (var j in myDef.tiers) {
						var myTier = myDef.tiers[j];
						if (myTier.min_order < itemsPrice) {
							usePrice = myTier.price;
						}
						else {
							break;
						}
					}
					
					if (usePrice < 0.0) {
						usePrice = parseFloat(myDef.charge);
					}
					
					//usePrice = Math.round(usePrice * exchangeRate * 100.0) * 0.01;
					usePrice = RoundPrice(usePrice * exchangeRate);
					$('#quoteDelivery .price .value').text(usePrice.toFixed(2));
					hasPrice = true;
				}
			}
			
			if (!hasPrice) {
				$('#quoteDelivery .price .value').text('0.00');
			}
			
			UpdateQuoteTotals();
			
		};
		
		
		// Bind an onchange handler to the delivery-group dropdown
		$('#quoteDelivery select[name=group]').change(function(event) {
			
			var $group = $(this);
			var myDef = FindOptionWithIdString($group.val(), deliveryDefs);
			var options = (myDef) 
				? myDef.options 
				: [];
			SetSelectOptions($('#quoteDelivery select[name=option]').get(0), options);
			UpdateDeliverySubtotal();
			
		});
		
		
		// Bind the onchange handler to the delivery-option dropdown
		$('#quoteDelivery select[name=option]').change(UpdateDeliverySubtotal);
		
		
		// Remove any current discount code and reset the entry form
		var ResetCouponCode = function(keepValue) {
			var $coupon = $('#quoteCoupon');
			$coupon.find('.removeCoupon').hide();
			
			var $add = $coupon.find('.addCoupon').show();
			(keepValue)
				? $add.find('input[name=coupon]').focus() 
				: $add.find('input[name=coupon]').val('').focus();
			$add.find('.glassButton').show();
			$add.find('.loadAnim').hide();
			
			usingCoupon = null;
			UpdateQuoteTotals();
		};
		
		
		// Apply the given coupon definition to the quote
		var ApplyCouponData = function(coupon) {
			$('#quoteCoupon .addCoupon').hide();
			$('#quoteCoupon .removeCoupon').show().find('.activeCode').text(coupon.code);
			usingCoupon = coupon;
			UpdateQuoteTotals();
		};
		
		
		// Attempt to load information about the entered discount code
		var LoadCouponCode = function() {
			var $coupon = $('#quoteCoupon');
			
			var $button = $coupon.find('.addCoupon .glassButton')
			$coupon.find('.addCoupon .loadAnim').width($button.outerWidth()).show();
			$button.hide();
			
			var href = $coupon.attr('data-callback');
			if (href) {
				var code = $coupon.find('input[name=coupon]').val();
				jQuery.get(href, {"code":code}, function(response) {
					
					//alert(response.code);
					//alert(response.type);
					//alert(response.value);
					
					if (response.success) {
						ApplyCouponData({
							"code"	: response.code, 
							"type"	: response.type, 
							"value" : response.value
						});
					}
					
					else {
						ResetCouponCode(true);
						if (response.message) {
							alert(response.message);
						}
						$('#quoteCoupon .addCoupon input[name=coupon]').focus();
					}
					
				});
			}
		};
		
		
		// Bind click handlers to the add/remove coupon buttons
		$('#quoteCoupon .addCoupon input[type=button]').click(function(event) {
			LoadCouponCode();
			event.preventDefault();
		});
		$('#quoteCoupon .removeCoupon input[type=button]').click(function(event) {
			ResetCouponCode();
			event.preventDefault();
		});
		
		
		// Bind a keypress handler to the coupon input to listen for ENTER key
		$('#quoteCoupon .addCoupon input[name=coupon]').keydown(function(event) {
			if (event.which == 13) {
				LoadCouponCode();
				event.preventDefault();
			}
		});
		
		
				
		// Create the first badge-specification row immediately upon load
		CreateBadgeRow();
		
		
		//-------------------------------------------------------------------------------------
		
		
		var badgeCurrent = [];
		var deliveryCurrent = {"service":"","name":""};
		var couponCurrent = null;
		
		
		var SelectOptionTitle = function(title, select) {
			
			if (!select || !select.options) {
				return false;
			}
			
			for (var i=0, ix=select.options.length; i<ix; ++i) {
				if (select.options[i].text == title) {
					select.options[i].selected = true;
					return true;
				}
			}
			
			return false;
			
		};
		
		
		(function(){
			
			var $badgeTable = $('#quoteBadges tbody');
			for (var i=0, ix=badgeCurrent.length; i<ix; ++i) {
				
				var myBadge = badgeCurrent[i];
				var $badgeRow = $badgeTable.children().last();
				
				var $selectBadge = $badgeRow.find('select[name=badge]');
				if (SelectOptionTitle(myBadge.name, $selectBadge.get(0))) {
					$selectBadge.triggerHandler('change');
					
					var $selectFitting = $badgeRow.find('select[name=fitting]');
					if (SelectOptionTitle(myBadge.fitting, $selectFitting.get(0))) {
						$selectFitting.triggerHandler('change');
					}
					
					$badgeRow.find('input[name=qty]').val(myBadge.qty).triggerHandler('change');
				}
				
			}
			
			
			var $deliveryGroup = $('#quoteDelivery select[name=group]');
			if (SelectOptionTitle(deliveryCurrent.service, $deliveryGroup.get(0))) {
				$deliveryGroup.triggerHandler('change');
				
				var $deliveryOption = $('#quoteDelivery select[name=option]');
				if (SelectOptionTitle(deliveryCurrent.name, $deliveryOption.get(0))) {
					$deliveryOption.triggerHandler('change');
				}
			}
			
			
			if (couponCurrent && (typeof ApplyCouponData != 'undefined')) {
				ApplyCouponData(couponCurrent);
			}
			
		})();
		
		
		
		//-------------------------------------------------------------------------------------
		
		
		// Comment
		var GetSelectOptionTitle = function(select) {
			return select.options[select.selectedIndex].text;
		};
		
		
		// Comment
		$('#instantQuoteForm').show().submit(function(event) {
			
			var userName = this.userName;
			if (userName.value == null || userName.value == "" || userName.value == "Enter your name") {
				event.preventDefault();
				alert("Please enter your name");
				$(userName).focus();
				return false;
			}
			
			var emailInput = this.emailAddr;
			var emailRegex = /^\s*[-\w]+(?:\.[-\w]+)*@[-\w]+(?:\.[-\w]+)+\s*$/i;
			if (!emailRegex.test(emailInput.value)) {
				event.preventDefault();
				alert('Please enter a valid email address');
				$(emailInput).focus();
				return false;
			}
									
			var $form = $(this);
			var data = {
				"badges"	: [], 
				"payment"	: null,
				"delivery"	: null
			};
			
			
			$form.find('#quoteBadges > tbody > tr').each(function() {
				
				$row = $(this);
				if ($row.hasClass('noPrint')) {
					return;
				}
				
				var badge = {
					"name"		: GetSelectOptionTitle($row.find('select[name=badge]').get(0)), 
					"qty"		: parseInt($row.find('input[name=qty]').val()),
					"fitting"	: GetSelectOptionTitle($row.find('select[name=fitting]').get(0)), 
					"price"		: parseFloat($row.find('.price .value').text())
				};
				
				if ((badge.qty > 0) && (badge.price > 0.001)) {
					data.badges.push(badge);
				}
				
			});
			
			
			if (data.badges.length < 1) {
				event.preventDefault();
				alert('Please ensure you have added at least one badge to the quote form');
				return false;
			}
			
						
				
			var paymentSelect = $form.find('select[name=payment]').get(0);
				
			if (paymentSelect.selectedIndex > 0) {
				
				data.payment = {
					"name"	: GetSelectOptionTitle(paymentSelect), 
					"price"		: parseFloat($('#quotePayment .price .value').text())
				};
				
			}
				
					
			$form.find('#quoteDelivery > tbody > tr').first().each(function() {
				
				$row = $(this);
				
				var delServiceSelect = $row.find('select[name=group]').get(0);
				
				if (delServiceSelect.selectedIndex > 0) {
					
					data.delivery = {
						"service"	: GetSelectOptionTitle(delServiceSelect), 
						"name"		: GetSelectOptionTitle($row.find('select[name=option]').get(0)), 
						"price"		: parseFloat($('#quoteDelivery tfoot .price .value').text())
					};
					
				}
				
			});
			
			
			if (usingCoupon) {
				data.coupon = usingCoupon;
			}
			
			
			$('.submitQuote input:text', this).each(function() {
				var $input = $(this);
				
				if ($input.val() == $input.attr('title')) {
					$input.val('');
				}
				
				if (($input.attr('name') == 'emailRef')) {
					if (String($input.val()).length > 60) {
						alert('Please ensure your reference is no more than 60 characters');
						event.preventDefault();
					}
				}
			});
			
			this.emailJson.value = array2json(data);
			//return false;
		});
		
		$("#sndQuote").click(function() {
			
			$("#instantQuoteForm").submit();
			
		});
		
		
		// Comment
		$('#instantQuoteForm > .submitQuote input:text').each(function() {
			
			if ($(this).val() == '') {
				$(this).val($(this).attr('title'));
			}
			
			$(this).focus(function(event) {
				if ($(this).val() == $(this).attr('title')) {
					$(this).val('');
				}
			});
			
			$(this).blur(function(event) {
				if ($(this).val() == '') {
					$(this).val($(this).attr('title'));
				}
			});
			
		})
		
		
		// Comment
		$('#instantQuoteForm > .submitQuote button.print').each(function() {
			$(this).click(function(event) {
				print();
				event.preventDefault();
				return false;
			});
		});
		
		
		//-------------------------------------------------------------------------------------
		
	});
	
	//-->]]>
</script>
