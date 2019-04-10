<?php
/*
  $Id: customer_files.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  require('includes/classes/class.phpmailer.php');
  $mail = new PHPMailer();
  
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  // RCI code start
  echo $cre_RCI->get('global', 'top', false); 
  echo $cre_RCI->get('orders', 'top', false); 
  // RCI code eof
    
  if(isset($_GET['cID'])) {
  	 $cID = tep_db_prepare_input($_GET['cID']);     	  	 
  } else {
  	tep_redirect(tep_href_link(FILENAME_ORDERS, '', 'SSL'));
  }
  
	//Get customer info
	$customers_query = tep_db_query("select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, a.entry_country_id, a.entry_city, a.entry_state, a.entry_telephone, a.entry_company_tax_id, a.entry_postcode, a.entry_company, a.entry_street_address from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " a WHERE c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id and c.customers_id = " . (int)$cID);
							
	$customers_array = tep_db_fetch_array($customers_query);
	
	$customers_name = $customers_array['customers_firstname']. ' ' . $customers_array['customers_lastname'];
	
	if(!empty($customers_array["entry_company_tax_id"])) {
		$customer_number = $customers_array["entry_company_tax_id"];
	} else {
		$customer_number = $customers_array["customers_id"];
	}
	
	$company_name = $customers_array["entry_company"];

  
   if (isset($_POST['action']) && ($_POST['action'] == 'process') ) {
    	
    $order = "";
		
	$brief = tep_db_prepare_input(addslashes($_POST['brief']));
	if($_POST['linked_to_order']==1) {
		$order = tep_db_prepare_input($_POST['order']);
	}
	$product = tep_db_prepare_input($_POST['product_name']);	
	$design_status = tep_db_prepare_input($_POST['design_status']);
	$notify_customer = 0;
	$artwork_cc = "";
	$artwork_bcc = "";
	
	if($_POST['notify_customer']) {
		
		$notify_customer = 1;
		if($_POST['artwork_cc']!="") {
			$artwork_cc = tep_db_prepare_input($_POST['artwork_cc']);
		}
		if($_POST['artwork_bcc']!="") {
			$artwork_bcc = tep_db_prepare_input($_POST['artwork_bcc']);
		}
	}
	$sales_consultant = tep_db_prepare_input($_POST['sales_consultant']);
	$designer = tep_db_prepare_input($_POST['designer']);
	$linked_to_order = tep_db_prepare_input($_POST['linked_to_order']);
		
    $error = false;  
   		
	$design_arr = $_POST['artwork_design'];
	$resolution_arr = $_POST['high_resolution'];
	
	
	$i=1; $r = 0;
	
	foreach($design_arr as $design) {		
				
		$fileInfo = pathinfo("../".$design);
		
		$randamNumber=md5(microtime().rand(0,999999)).$i;
		
		$myfiles_dir = 'artworks/';
		
		$path = DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension'];
		
		if(copy("../".$design, "../".$path)) {				
			
			$error = true;
			
			$files_arr[$i]['design'] = $path;
			//remove files in temp
			unlink("../".$design);
			
			if($resolution_arr[$r]!="") {
				
				$resInfo = pathinfo("../".$resolution_arr[$r]);		
				$randamNumber_2 = md5(microtime().rand(0,999999)).$i;		
				$path_2 = DIR_WS_IMAGES . $myfiles_dir . $randamNumber_2 . "." . $resInfo['extension'];				
				if(copy("../".$resolution_arr[$r], "../".$path_2)) {
					$files_arr[$i]['resolution'] = $path_2;
					//remove files in temp
					unlink("../".$resolution_arr[$r]);
					
				}
			}
			
			$r++;		
			
		 }	
		 
		 $i++;	 	 
		
	}	
			
	if ($error == true) {
		$sqlInsFiles = tep_db_query("INSERT INTO artwork set designer_id = '".(int)$_SESSION['login_id']."', customers_id='".$cID."', creative_brief='".$brief."',  orders_id = '" . $order . "', products_id='".$product."', notify_customer='".$notify_customer."', artwork_cc='".$artwork_cc."', artwork_bcc='".$artwork_bcc."', artwork_status='".$design_status."', sales_consultant='".$sales_consultant."', designer='".$designer."', linked_to_order='".$linked_to_order."', date_created = now()");					  		
		$insert_id = tep_db_insert_id();		
		$j=1;
		foreach($files_arr as $files_key=>$files) {			
			$option_name = "Opt ".$j;
			$insOption = tep_db_query("INSERT INTO artwork_option set artwork_id='".$insert_id."', option_image='".$files['design']."', option_name='".$option_name."'");	
			$insert_option_id = tep_db_insert_id();	
			if($files['resolution']!="") {
				$insResOption = tep_db_query("INSERT INTO artwork_option_resolution set artwork_option_id='".$insert_option_id."', resolution_image_path='".$files['resolution']."'");
			}
			$j++;	
		}
		
		//artwork content for sending email
		$sales_admin = tep_get_admin_details($sales_consultant);
		$sale_admin_name = $sales_admin['admin_firstname'] . ' ' . $sales_admin['admin_lastname'];
		$design_admin = tep_get_admin_details($designer);
		$design_admin_name = $design_admin['admin_firstname'] . ' ' . $design_admin['admin_lastname'];
		$created_admin = tep_get_admin_details((int)$_SESSION['login_id']);
		$created_admin_name = $created_admin['admin_firstname'] . ' ' . $created_admin['admin_lastname'];
										
		$art_content = '<b>Product name</b>: '.$product."\n\n".
					   '<b>Creative Brief</b>: ' . $brief . "\n\n" .
					   '<b>Artwork Status</b>: ' . $design_status . "\n\n" .					   
					   '<b>Customer name</b>: '.$customers_name . "\n\n" .
					   '<b>Customer number</b>: '.$customer_number."\n\n" . 
					   '<b>Sale Consultant</b>: '. $sale_admin_name ."\n\n".
					   '<b>Designer</b>: ' . $design_admin_name . "\n\n" .
					   '<b>Created by</b>: ' . $created_admin_name . "\n\n" .					   
					   '<b>Date Created</b>: ' . date("d-m-Y") . "\n\n";
		
			
		//mail to sales consultant		
		$semail .= '<a href="' . str_replace('&amp;', '&', tep_href_link('artworks.php', 'cID=' . $cID, 'SSL', false)) . '">Click Here</a>';				
		tep_mail($sale_admin_name, $sales_admin['admin_email_address'], sprintf(ARTWORK_SALES_CONSULTANT_EMAIL_SUBJECT,$customer_number), sprintf(ARTWORK_SALES_CONSULTANT_EMAIL_GREET, $sale_admin_name, $created_admin_name).$art_content.sprintf(ARTWORK_ADMIN_LINK,$semail).ARTWORK_SALES_CONSULTANT_EMAIL_TEXT.ARTWORK_SALES_CONSULTANT_EMAIL_FOOTER, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		
		//mail to customer
		if($notify_customer) {		
						
			$artwork_client_greet = str_replace("\n","<br>",ARTWORK_CLIENT_EMAIL_GREET);
			$artwork_client_content = str_replace("\n","<br>",ARTWORK_CLIENT_EMAIL_CONTENT);
			$artwork_client_footer = str_replace("\n","<br>",ARTWORK_CLIENT_EMAIL_FOOTER);
						
			//Use PHP Mailer to send email - Start
						
			$client_message = sprintf($artwork_client_greet,$customers_name) . $artwork_client_content . sprintf($artwork_client_footer,$sale_admin_name);
			
			$mail->AddReplyTo($created_admin['admin_email_address'],$created_admin_name);
			
			$mail->SetFrom($created_admin['admin_email_address'], $created_admin_name);
			
			$mail->AddAddress($customers_array['customers_email_address'], $customers_name);
			
			if($artwork_cc!="") {  $mail->AddAddress($artwork_cc, '');  }
			
			if($artwork_bcc!="") {  $mail->AddAddress($artwork_bcc, '');  }
			
			$mail->Subject    = ARTWORK_CLIENT_EMAIL_SUBJECT;
			
			//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			
			$mail->MsgHTML($client_message);
						
			//$mail->AddAttachment($files);      // attachment			
			
			$mail->Send();
							
		}
			
		tep_redirect(tep_href_link(FILENAME_ARTWORKS, 'cID=' . $cID.'&msg=upload'));
	  
	}			
			
  } //End of post
  
  
  //delete artwork files
  if(isset($_GET['action']) && $_GET['action']=="deleteconfirm" ) {	
	$del_artwork_id = $_GET['aID'];	
	$qry_rst = tep_db_query("SELECT artwork_id FROM artwork WHERE artwork_id='".$del_artwork_id."'");
	if(tep_db_num_rows($qry_rst)>0) {		
		
		//artwork option
		$opt_arr = tep_get_artwork_option($del_artwork_id);		
		
		tep_db_query("DELETE FROM artwork WHERE artwork_id='".$del_artwork_id."'");
		tep_db_query("DELETE FROM artwork_option WHERE artwork_id='".$del_artwork_id."'");
		
		foreach($opt_arr as $opt=>$op) {
			$high_resolution = tep_get_resolution($op['artwork_option_id']);
			$opt_feedback = tep_get_option_feedback($op['artwork_option_id']);
			
			tep_db_query("DELETE FROM artwork_option_resolution WHERE artwork_option_id='".$op['artwork_option_id']."'");
			tep_db_query("DELETE FROM artwork_feedback WHERE artwork_option_id='".$op['artwork_option_id']."'");
			
			unlink("../".$op['option_image']);
			if(!empty($high_resolution['resolution_image_path'])) {
				unlink("../".$high_resolution['resolution_image_path']);
			}
			if(!empty($opt_feedback['attachment'])) {
				unlink("../".$opt_feedback['attachment']);
			}
		}		
		tep_redirect(tep_href_link(FILENAME_ARTWORKS, 'cID=' . $cID.'&msg=delete'));		
	}	
  }
  
  
  
  
include(DIR_WS_CLASSES . 'order.php');


//get all admin
$admin_arr = tep_get_all_admin();	


//for preview option
if (isset($_POST['action']) && ($_POST['action'] == 'preview') ) {

  	include "artworks_preview.php";
	
} else {
		
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html <?php echo HTML_PARAMS; ?>>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title><?php echo TITLE; ?></title>
		<script type="text/javascript" src="includes/prototype.js"></script>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<style type="text/css">
		.main { border-bottom:1px solid #CCC; }
		.blue-txt { color:#0000FF; }
		</style>
		<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
		<![endif]-->
		<script language="javascript" src="includes/general.js"></script>
		<link type="text/css" rel="StyleSheet" href="includes/helptip.css">
		<script type="text/javascript" src="includes/javascript/helptip.js"></script>
		
		<script type="text/javascript" src="includes/javascript/jquery.js"></script>
		<script type="text/javascript" src="includes/javascript/jquery.validate.js"></script>
		<script type="text/javascript" src="includes/javascript/jquery.metadata.js"></script>
		
		<script type="text/javascript">
			function delconfirm() {		
				if(confirm("Are you sure want to delete this file?")) {
					return true;
				}
				return false;
			}
			
			$(document).ready(function() {
				
				$('#notify_customer').click(function() {
					
					if($('#notify_customer').attr("checked")==true) {						
						$('#artwork_cc').attr("disabled",false);
						$('#artwork_bcc').attr("disabled",false);
					} else {
						$('#artwork_cc').val(""); $('#artwork_bcc').val("");
						$('#artwork_cc').attr("disabled",true);
						$('#artwork_bcc').attr("disabled",true);
					}
					
				});
				
				$(".linked_to_order").click(function(){
					if ($('#linked_yes:checked').val() == '1') {	       				
						$("#row-order").show();	
						$("#order").attr('validate',"required:true");			
					} else {				
						$("#dynamic-pro").html('<input type="text" name="product_name" id="product_name" validate="required:true">');
						$("#row-order").hide();		
						$("#order").attr('validate',"");				
					}
				});
			
			
				$("#add_design").click(function() {						
					
					//alert($(".artwork_design:last").attr("id"));			
					//num = $(".artwork_design").length;
					num = parseInt(funGetID($(".artwork_design:last").attr("id")));			
					num = parseInt(num+1);					
						
					design_field = '<div id="design_'+num+'"><input type="file" class="artwork_design" name="artwork_design[]" id="artwork_design_'+num+'" /> &nbsp;&nbsp; <input type="button" name="remove_design" class="remove_design" id="remove_design_'+num+'" value="Remove" onclick="funRemove('+num+')"><br><input type="file" class="high_resolution" name="high_resolution[]" id="high_resolution_'+num+'" />&nbsp;<font class="blue-txt">High Resolution design</font></div>';
					$("#design-container").append(design_field);			
				});
				
				//ajax function started
				$('#order').change(function(){       
					// Call the function to handle the AJAX.
					// Pass the value of the text box to the function.
					ord = $('select#order option:selected').val();
					//alert(ord);
					sendValue(ord);  
				   
				}); 
							
				$("#frmArtwork").validate();
			});
			
			// Function to handle ajax.
			function sendValue(str){	   
				// post(file, data, callback, type); (only "file" is required)
				$.post(		   
				"get_products.php", //Ajax file	   
				{ sendValue: str },  // create an object will all values	   
				//function that is called when server returns a value.
				function(data){
					//alert(data.returnValue);
					$('#dynamic-pro').html(data.returnValue);
				},	   
				//How you want the data formated when it is returned from the server.
				"json"
				);
			   
			}
		
			function funRemove(curid) {		
				$("#remove_design_"+curid).parent().remove();
			}
				
			function funGetID(idstring) {		
				var id = idstring.lastIndexOf("_")+1;		
				return idstring.substring(id);		
			}
			
			
			//For validation
			/*jQuery(document).ready(function($) {					
					$("#frmArtwork").validate();	
			});*/
			//After validation submit form
			$.validator.setDefaults({
				submitHandler: function(form) {			
					form.submit();			
				}
			});		
			$.metadata.setType("attr", "validate");
			
			
			
		
		
		</script>
		
		<?php 
		// rci for javascript include
		echo $cre_RCI->get('orders', 'javascript');
		?>
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
				  <tr>
					<td class="pageHeading"><?php echo sprintf(HEADING_TITLE,$company_name); ?></td>           
				  </tr>		 
				</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			 
			  <?php
				 // RCI start
				 echo $cre_RCI->get('orders', 'listingtop');
				 // RCI eof
			  ?>
			  <tr>
				<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
					  <td valign="top">			  
					  
						<div id="msg" style="color:#0099FF;">
						<?php
						if(isset($_GET['msg']) && $_GET['msg']!="") {
						
							if($_GET['msg']=="upload") {
								echo "Artwork design uploaded successfully";
							} else if($_GET['msg']=="delete") {
								echo "Artwork design deleted successfully";
							}
						
						}
						?>
						</div>
						<?php
						
						 echo tep_draw_form('frmArtwork', FILENAME_ARTWORKS, tep_get_all_get_params(array('cID')) . 'cID=' . $cID, 'post', 'id="frmArtwork" enctype="multipart/form-data"', 'SSL'). tep_draw_hidden_field('action', 'preview');
									  
						  ?>
						
						<table border="0" width="100%">
							<tr>					  
							  <td width="31%" rowspan="12" valign="top">
							  
							  <?php										  					  	 	
									
									$country_query = tep_db_query("SELECT countries_name FROM " . TABLE_COUNTRIES . " WHERE countries_id = " . (int)$customers_array['entry_country_id']);
									$country_array = tep_db_fetch_array($country_query);
									
							  ?>
									<table width="100%" border="0" cellspacing="0" cellpadding="2">
									 <tr>
										<td><b><?php echo ENTRY_CUSTOMER_NUMBER; ?></b></td>
										<td><?php echo $customer_number; ?></td>
									  </tr>
									  <tr>
										<td width="40%" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
										<td width="60%">
											<?php  
												echo $customers_array['customers_firstname'] . " " . 
												$customers_array['customers_lastname'] ."<br>" . 
												$customers_array['entry_street_address']."<br>" . 
												$customers_array['entry_city']. ", " .$customers_array['entry_state']."<br>" .
												$customers_array['entry_postcode']."<br>" . $country_array['countries_name'];
											?>								</td>
									  </tr>
									  <tr>
										<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
									  </tr>
									  <tr>
										<td><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
										<td><?php echo $customers_array['entry_telephone']; ?></td>
									  </tr>
									  <tr>
										<td><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
										<td><?php echo '<a href="mailto:' . $customers_array['customers_email_address'] . '"><u>' . $customers_array['customers_email_address'] . '</u></a>'; ?></td>
									  </tr>							 
									</table>						</td>
							  
							  <td colspan="3">Please use below form to send artwork designs to customers. <br />
								Remember to write a note about your artwork design or any existing job in progress regarding the particular order.<br />
								Allowed file formats are jpg, gif and png files only.					  </td>
						  </tr>
							<tr>
							  <td colspan="2">&nbsp;</td>
							  <td>&nbsp;</td>
						  </tr>
						  
						   <?php
							$sel_cust_orders = tep_db_query("SELECT orders_id FROM orders WHERE customers_id='".$cID."' ORDER BY orders_id DESC");
							if(tep_db_num_rows($sel_cust_orders)>0) {
							?>
						  <tr>					  
							  <td valign="top" width="18%"><strong>Link to the Order: </strong></td>
							  <td width="48%">
								<label>
								  <input type="radio" id="linked_yes" class="linked_to_order" name="linked_to_order" value="1" checked="checked">
								  Yes</label>					  
								<label>
								  <input type="radio" id="linked_no" class="linked_to_order" name="linked_to_order" value="0">
								  No</label>					    </td>
							  <td width="3%">&nbsp;</td>
						   </tr>				   
						  
						   <tr id="row-order">
							  
							  <td><strong>Order #:	</strong></td> 
							  <td>
								<select name="order" id="order" validate="required:true"><option value="">...</option>
									<?php									
											while($cust_orders=tep_db_fetch_array($sel_cust_orders)) {
												echo "<option value='".$cust_orders['orders_id']."'> ".$cust_orders['orders_id']." </option>";
											}								
									?>
								</select></td>
								<td>&nbsp;</td>
						  </tr>				  
						  <?php } ?>
						  <tr>					  
							  <td><strong>Product name:	</strong></td> 
							  <td id="dynamic-pro"><input type="text" name="product_name" id="product_name" validate="required:true"></td>
								<td>&nbsp;</td>
						  </tr>			  
						 
						  <tr>
							  <td valign="top"><strong>Artwork Option:	</strong></td> 
							  <td id="design-container"> <input type="file" class="artwork_design" name="artwork_design[]" id="artwork_design_1" validate="required:true"/> &nbsp;&nbsp; <input type="button" name="add_design" id="add_design" value="Add design">
							  <br>
							  <input type="file" class="high_resolution" name="high_resolution[]" id="high_resolution_1" />
							  &nbsp;<font class="blue-txt">High Resolution design</font>
							  </td>
								<td>&nbsp;</td>
						  </tr>
						 
						  <tr>		
							  <td valign="top"><strong>Creative Brief: </strong></td>
							  <td><textarea name="brief" style="width:175px;"></textarea></td>
							  <td>&nbsp;</td>
						  </tr>
						  <tr>
							  <td><strong>Sales Consultant:	</strong></td> 
							  <td> 
								<?php
													
								if(count($admin_arr)>0) {
									echo '<select name="sales_consultant" validate="required:true">';
									foreach($admin_arr as $admin=>$admin_detail) {
										echo '<option value="'.$admin_detail['admin_id'].'">'.$admin_detail['admin_firstname']." ".$admin_detail['admin_lastname'].'</option>';
									}	
									echo '</select>';					
								}
								?>					  	
							  </td>
								<td>&nbsp;</td>
						  </tr>
						  <tr>
							  <td><strong>Designer:	</strong></td> 
							  <td> 
								<?php
													
								if(count($admin_arr)>0) {
									echo '<select name="designer" validate="required:true">';
									foreach($admin_arr as $admin=>$admin_detail) {
										if($_SESSION['login_id']==$admin_detail['admin_id']) {
											$sel = "selected";
										} else {
											$sel = "";
										}
										echo '<option value="'.$admin_detail['admin_id'].'" '.$sel.'>'.$admin_detail['admin_firstname']." ".$admin_detail['admin_lastname'].'</option>';
									}	
									echo '</select>';					
								}
								?>			
							  </td>
							  <td>&nbsp;</td>
						  </tr>
						  <tr>
							  <td><strong>Artwork Status:	</strong></td> 
							  <td> <select name="design_status">
									<option value="pending">Pending</option>
									<option value="revision">Revision</option>
									<option value="approved">Approved</option>
								</select>					  </td>
							  <td>&nbsp;</td>
						  </tr>
						  <tr>
							  <td><strong>Notify Customer:	</strong></td> 
							  <td> 
							  
							  	<table align="center" width="100%" border="0">
								  <tr>
								  	<td width="10%" valign="top"><input type="checkbox" name="notify_customer" id="notify_customer" /></td>
									<td width="90%" valign="top">
										<?php
											$from_admin = tep_get_admin_details((int)$_SESSION['login_id']);
											$from_admin_name = $from_admin['admin_firstname'] . ' ' . $from_admin['admin_lastname'];
											$from_admin_email = $from_admin['admin_email_address'];											
										?>
										<b>From: </b><?php echo $from_admin_email; ?><br>
										<b>To: &nbsp;&nbsp;</b><?php echo $customers_array['customers_email_address']; ?><br>
										<b>Cc: &nbsp;&nbsp;</b> <input type="text" name="artwork_cc" id="artwork_cc" disabled><br>
										<b>Bcc: &nbsp;</b> <input type="text" name="artwork_bcc" id="artwork_bcc" disabled><br>									
									</td>
								  </tr>
							  	</table>
							  
							  </td>
							  <td>&nbsp;</td>
						  </tr>
						  <!--
						  <tr>
							  <td><strong>Artwork option 2:	</strong></td> 
							  <td> <input type="file" name="artwork_option[]" id="artwork_option[]" /></td>
								<td>&nbsp;</td>
						  </tr>
						  <tr>
							  <td><strong>Artwork option 3:	</strong></td> 
							  <td> <input type="file" name="artwork_option[]" id="artwork_option[]" /></td>
								<td>&nbsp;</td>
						  </tr>
						  -->
						 
							<tr>
							  <td>&nbsp;</td>
							  <td><?php echo tep_image_submit('submit.png', "Preview"); ?></td>
							  <td>&nbsp;</td>
						  </tr>
							<tr>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>
							  <td>&nbsp;</td>	
							  <td>&nbsp;</td>				 
						  </tr>
						</table>
						</form>
					  
					  
					  
					  <table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
						  <?php
			$oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
			if (isset($_GET['SoID'])) {
			  $oscid .= '&SoID=' . $_GET['SoID'];
			}   
		?>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="1%">&nbsp;</td>                    
							<td class="dataTableHeadingContent" width="15%"><?php echo TABLE_HEADING_PRODUCT_NAME; ?></td>
							<td class="dataTableHeadingContent" width="5%"> <?php echo ENTRY_ORDERID; ?> </td>
							<td class="dataTableHeadingContent" width="40%"> <?php echo TABLE_HEADING_BRIEF; ?> </td>
							<td class="dataTableHeadingContent" width="10%"><?php echo "Status"; ?></td>										
							<td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
							<td class="dataTableHeadingContent" width="15%">&nbsp;</td>      
						  </tr>
						  <?php   
			
			if (isset($_GET['cID'])) {
			
				$root = $_SERVER['DOCUMENT_ROOT'].mb_substr($_SERVER['PHP_SELF'],0,-mb_strlen(strrchr($_SERVER['PHP_SELF'],"/")));	
				$root = str_replace("/admin","",$root);
		
			   
				$artwork_query = "select * from artwork where customers_id='".$cID."' order by date_created desc";	
					
				$orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $artwork_query);
				$artwork_details = tep_db_query($artwork_query);
				$artwork_query_rows = tep_db_num_rows($artwork_details);
				while ($artwork = tep_db_fetch_array($artwork_details)) {							  
						 
						 $artworkId = $artwork['artwork_id'];
						?>				
						<tr>
						  <td class="main" width="1%">&nbsp;</td>			  
						  <td class="main" width="15%">
							<?php	
								if(is_numeric($artwork['products_id'])) {
									$ord_prod = tep_get_order_products($artwork['products_id']);
									echo $ord_prod['products_name'];
								} else {
									echo $artwork['products_id']; 
								}								
							?>  
						  </td>
						  <td class="main" width="5%"> <?php echo $artwork['orders_id'];  ?>  </td>          
						  <td class="main" width="40%"> <?php echo (strlen($artwork['creative_brief'])>150)?substr(stripslashes($artwork['creative_brief']),0,150)."...":stripslashes($artwork['creative_brief']); ?></td>
						  <td class="main" width="10%"><?php echo $artwork['artwork_status']; ?></td>				  
						  <td class="main" width="10%"><?php echo tep_date_long($artwork['date_created']); ?></td>
						  <td class="main" width="15%">
							<?php 					
								if(isset($_GET['page'])) {
									$page = $_GET['page'];
								} else {
									$page = 1;
								}
								echo '<a href="' . tep_href_link(FILENAME_ARTWORKS_INFO, 'page='.$page.'&amp;cID='.$cID.'&amp;aID='.$artworkId.'&amp;action=view', 'SSL') . '">' . tep_image_button('small_delete.gif', 'View') . '</a>';
								echo '<a href="' . tep_href_link(FILENAME_EDIT_ARTWORKS, 'page='.$page.'&amp;cID='.$cID.'&amp;aID='.$artworkId, 'SSL') . '">' . tep_image_button('small_delete.gif', 'Edit') . '</a>';
								echo '<a onclick="return delconfirm()" href="' . tep_href_link(FILENAME_ARTWORKS, 'page='.$page.'&amp;cID='.$cID.'&amp;aID='.$artworkId.'&amp;action=deleteconfirm', 'SSL') . '">' . tep_image_button('small_delete.gif', 'Delete') . '</a>'; 
		
							?>
						  </td>
						</tr>
			<?php				 
					 //} //End of if loop
				 }
			
			 } 
		?>
						</table>
						<table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
						  <tr>
							<td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
								<tr>
								  <td class="smallText" valign="top"><?php echo $orders_split->display_count($artwork_query_rows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
								  <td class="smallText" align="right"><?php echo $orders_split->display_links($artwork_query_rows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
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
					</tr>
				  </table></td>
			  </tr>
			  <?php
		  
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
		
<?php } ?>