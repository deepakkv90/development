<?php
/*
  $Id: customer_files.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  // RCI code start
  echo $cre_RCI->get('global', 'top', false); 
  echo $cre_RCI->get('orders', 'top', false); 
  // RCI code eof
    
  if(isset($_GET['cID']) && isset($_GET['aID'])) {
  	 $cID = tep_db_prepare_input($_GET['cID']); 
	 $aID = tep_db_prepare_input($_GET['aID']);     	  	 
  } else {
  	tep_redirect(tep_href_link(FILENAME_ORDERS, '', 'SSL'));
  }
  
   if (isset($_POST['action']) && ($_POST['action'] == 'process') ) {
    	
    $order = "";
		
	$brief = tep_db_prepare_input($_POST['brief']);
	if($_POST['linked_to_order']==1) {
		$order = tep_db_prepare_input($_POST['order']);
	}
	$product = tep_db_prepare_input($_POST['product_name']);	
	$design_status = tep_db_prepare_input($_POST['design_status']);
	$notify_customer = tep_db_prepare_input($_POST['notify_customer']);
	$sales_consultant = tep_db_prepare_input($_POST['sales_consultant']);
	$designer = tep_db_prepare_input($_POST['designer']);
	$linked_to_order = tep_db_prepare_input($_POST['linked_to_order']);
	
	
	
	
    $error = false;  

    if (empty($artwork_design)) {
      $error = true;
      $messageStack->add('frmArtwork', "Please select the file");
    }
		 
	//$files_arr = array();
	//$prev_art_opt = array();
	//$ch_files_arr = array();
	//$rem_opt = array();
		
	$all_options = $_POST['all_options'];
	$prev_options = $_POST['prev_options'];
	
	foreach($prev_options as $key=>$opts) {		
		$prev_art_opt[] = $key;		
	}
	
	foreach($all_options as $opid) {		
		
		if(!in_array($opid,$prev_art_opt)) {
			$rem_opt[] = $opid; 
		} 
	}
	
	//add new design start
	
	$files_count = count($_FILES['artwork_design']['name']);
	
	for($i=0;$i<=$files_count;$i++) {
				
		$artwork_design = $_FILES['artwork_design']['name'][$i];
				
		$fileInfo = pathinfo($artwork_design);
		
		$randamNumber=md5(microtime().rand(0,999999)).$i;
		
		$myfiles_dir = 'artworks/';
		
		$path = DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension'];		
		
		$ext = strtolower($fileInfo['extension']);
		
		if($ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="gif") {
				
			if(move_uploaded_file($_FILES['artwork_design']['tmp_name'][$i],"../".$path)) {				
				
				$error = true;
				
				$files_arr[$i]['design'] = $path;
				
				$artwork_high_resolution = $_FILES['high_resolution']['name'][$i];		
						
				if(!empty($artwork_high_resolution)) {				
				
					$highResInfo = pathinfo($artwork_high_resolution);
					$randamNumber_2 = md5(microtime().rand(0,999999)).$i;
					$path_2 = DIR_WS_IMAGES . $myfiles_dir . $randamNumber_2 . "." . $highResInfo['extension'];
					$ext_2 = strtolower($highResInfo['extension']);
				
					if($ext_2=="jpeg" || $ext_2=="jpg" || $ext_2=="png" || $ext_2=="gif" || $ext_2=="bmp") {
						if(move_uploaded_file($_FILES['high_resolution']['tmp_name'][$i],"../".$path_2)) {	
							$files_arr[$i]['resolution'] = $path_2;
						}
					}
					
				}
				
			 }
		 }	 
		
	}	
	
	//add new design ends
	
	//update or change old design start
	
	
	
	$ch_files_count = count($_FILES['change_design']['name']);
				
	foreach($_FILES['change_design']['name'] as $chkey=>$chfile) {
		
		//echo $chkey.": ".$chfile;
		//echo $_FILES['change_design']['tmp_name'][$chkey];
		
		$change_design = $_FILES['change_design']['name'][$chkey];
				
		$fileInfo = pathinfo($change_design);
		
		$randamNumber=md5(microtime().rand(0,999999)).$chkey;
		
		$myfiles_dir = 'artworks/';
		
		$path = DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension'];		
		
		$ext = strtolower($fileInfo['extension']);
		
		if($ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="gif") {
				
			if(move_uploaded_file($_FILES['change_design']['tmp_name'][$chkey],"../".$path)) {				
				
				$error = true;
				
				$ch_files_arr[$chkey]['design'] = $path;
								
				$artwork_high_resolution = $_FILES['change_res']['name'][$chkey];		
						
				if(!empty($artwork_high_resolution)) {				
				
					$highResInfo = pathinfo($artwork_high_resolution);
					$randamNumber_2 = md5(microtime().rand(0,999999)).$chkey;
					$path_2 = DIR_WS_IMAGES . $myfiles_dir . $randamNumber_2 . "." . $highResInfo['extension'];
					$ext_2 = strtolower($highResInfo['extension']);
				
					if($ext_2=="jpeg" || $ext_2=="jpg" || $ext_2=="png" || $ext_2=="gif") {
						if(move_uploaded_file($_FILES['change_res']['tmp_name'][$chkey],"../".$path_2)) {	
							$ch_files_arr[$chkey]['resolution'] = $path_2;
						}
					}
					
				}			
				
			 }
		 }	 
		
	}
			
	//update or chage old design ends
	
	$prev_options = $_POST['prev_options'];
	
	foreach($prev_options as $key=>$opts) {		
		$prev_art_opt[] = $key;		
	}
			
	if (count($files_arr)>0 || count($prev_art_opt)>0 || count($ch_files_arr)>0) {
	
		$sqlInsFiles = tep_db_query("UPDATE artwork set designer_id = '".(int)$_SESSION['login_id']."', customers_id='".$cID."', creative_brief='".$brief."',  orders_id = '" . $order . "', products_id='".$product."', notify_customer='".$notify_customer."', artwork_status='".$design_status."', sales_consultant='".$sales_consultant."', designer='".$designer."', linked_to_order='".$linked_to_order."' WHERE artwork_id='".$aID."'");
		
		//update options
		
		if(count($rem_opt)>0) {
			foreach($rem_opt as $remopt) {
				tep_db_query("DELETE FROM artwork_option where artwork_id='".$aID."' and artwork_option_id='".$remopt."'");			
				tep_db_query("DELETE FROM artwork_feedback where artwork_id='".$aID."' and artwork_option_id='".$remopt."'");
				tep_db_query("DELETE FROM artwork_option_resolution where artwork_option_id='".$remopt."'");
			}			
		}
		
		if(count($ch_files_arr)>0) {			
			
			foreach($ch_files_arr as $chkey=>$chfile) {			
				
				$pre_art_opt_det = tep_get_option_details($chkey);
				if(!empty($pre_art_opt_det['option_image'])) { unlink("../".$pre_art_opt_det['option_image']); }
				 
				$updOption = tep_db_query("UPDATE artwork_option set option_image='".$chfile['design']."' WHERE artwork_id='".$aID."' and artwork_option_id='".$chkey."'");	
				$chk_high_resolution = tep_get_resolution($chkey);	
				if(!empty($chk_high_resolution['resolution_image_path'])) {
					$updResOption = tep_db_query("UPDATE artwork_option_resolution set resolution_image_path='".$chfile['resolution']."' WHERE artwork_option_id='".$chkey."'");		
				} else {
					tep_db_query("INSERT INTO artwork_option_resolution set artwork_option_id='".$chkey."', resolution_image_path='".$chfile['resolution']."'");
				}
			}	
			
		}
		
		//insert new option images of artwork
		foreach($files_arr as $files_key=>$files) {
			$opt_count = tep_get_artwork_option_count($aID); //get revision count
			$option_name = "Opt ".($opt_count+1);
			$insOption = tep_db_query("INSERT INTO artwork_option set artwork_id='".$aID."', option_image='".$files['design']."', option_name='".$option_name."'");	
			$insert_option_id = tep_db_insert_id();	
			if($files['resolution']!="") {
				$insResOption = tep_db_query("INSERT INTO artwork_option_resolution set artwork_option_id='".$insert_option_id."', resolution_image_path='".$files['resolution']."'");
			}	
		}	
		
		//chenage all option status when option changed
		if($design_status!="approved") {
			tep_db_query("UPDATE artwork_option SET option_approve='0' WHERE artwork_id='".$aID."'");
		}
					
		tep_redirect(tep_href_link(FILENAME_ARTWORKS, 'cID=' . $cID.'&msg=upload'));
	  
	}			
			
  } //End of post
  
//Get customer info
$customers_query = tep_db_query("select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, a.entry_country_id, a.entry_city, a.entry_state, a.entry_telephone, a.entry_company_tax_id, a.entry_postcode, a.entry_company, a.entry_street_address from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " a WHERE c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id and c.customers_id = " . (int)$cID);
        					
$customers_array = tep_db_fetch_array($customers_query);
if(!empty($customers_array["entry_company_tax_id"])) {
	$customer_number = $customers_array["entry_company_tax_id"];
} else {
	$customer_number = $customers_array["customers_id"];
}

$company_name = $customers_array["entry_company"];

//get all admin
$admin_arr = tep_get_all_admin();

//get artwork information
$sel_artwork = tep_db_query("SELECT * FROM artwork WHERE artwork_id='".$aID."'");
$artwork_content = tep_db_fetch_array($sel_artwork);
if(is_numeric($artwork_content['products_id'])) {
	$ord_prod = tep_get_order_products($artwork_content['products_id']);
	$prd_name = $ord_prod['products_name'];
} else {
	$prd_name = $artwork_content['products_id'];
}									
//get artwork options
$artwork_options = tep_get_artwork_option($aID);	



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
				
			design_field = '<div id="design_'+num+'"><input type="file" class="artwork_design" name="artwork_design[]" id="artwork_design_'+num+'" /> &nbsp;&nbsp; <input type="button" name="remove_design" class="remove_design" id="remove_design_'+num+'" value="Remove" onclick="funRemove('+num+')"><br><input type="file" class="high_resolution" name="high_resolution[]" id="high_resolution_1" />&nbsp;<font class="blue-txt">High Resolution design</font></div>';
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
		if(confirm("Are you sure want to remove this option?")) {
		
			$("#remove_design_"+curid).parent().remove();
			num = $(".prev_design").length;
			if(num<=0) {
				$(".artwork_design").attr("validate","required:true");
			}
		}
	}
	
	function funChange(curid) {		
		if(confirm("Are you sure want to change this option?")) {		
			$("#change_design_"+curid).parent().html('<input type="file" class="change_design" name="change_design['+curid+']" id="change_design_'+curid+'" /><br><input type="file" class="change_res" name="change_res['+curid+']" id="change_res_'+curid+'" /><font class="blue-txt">High Resolution image</font>');
		}
		
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
			<td align="right">
				<?php 
				echo '<a href="' . tep_href_link(FILENAME_ARTWORKS, 'page='.$page.'&amp;cID='.$cID, 'SSL') . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; 
				?>
			</td>                    
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
				
				 echo tep_draw_form('frmArtwork', FILENAME_EDIT_ARTWORKS, tep_get_all_get_params(array('cID')) . 'cID=' . $cID, 'post', 'id="frmArtwork" enctype="multipart/form-data"', 'SSL'). tep_draw_hidden_field('action', 'process');
				  			  
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
					      <input type="radio" id="linked_yes" class="linked_to_order" name="linked_to_order" value="1" <?php echo ($artwork_content["linked_to_order"]=="1")?"checked":""; ?>>
					      Yes</label>					  
					    <label>
					      <input type="radio" id="linked_no" class="linked_to_order" name="linked_to_order" value="0" <?php echo ($artwork_content["linked_to_order"]=="0")?"checked":""; ?>>
					      No</label>					    </td>
					  <td width="3%">&nbsp;</td>
				   </tr>				   
					  <?php if($artwork_content["linked_to_order"]=="1") { ?>
					  
					   <tr id="row-order">
						  
						  <td><strong>Order #:	</strong></td> 
						  <td>
							<select name="order" id="order" validate="required:true"><option value="">...</option>
								<?php									
										while($cust_orders=tep_db_fetch_array($sel_cust_orders)) {
											$selcted_order = "";
											if($artwork_content['orders_id']==$cust_orders['orders_id']) {
												$selcted_order = "Selected";
											}
											
											echo "<option value='".$cust_orders['orders_id']."' ".$selcted_order."> ".$cust_orders['orders_id']." </option>";
										}								
								?>
							</select>						</td>
							<td>&nbsp;</td>
					  </tr>	
					  <?php } ?>
				  			  
				  <?php } ?>
				  <tr>					  
					  <td><strong>Product name:	</strong></td> 
					  <td id="dynamic-pro">
					  <?php 
					  	$sel_pro = tep_db_query("SELECT op.orders_products_id, op.products_name FROM orders_products op LEFT JOIN products p ON op.products_id=p.products_id WHERE op.orders_id='".$artwork_content['orders_id']."' and (p.badge_data!='' OR p.badge_data IS NOT NULL)");
						if(tep_db_num_rows($sel_pro)>0) {
							echo '<select name="product_name" id="product_name" style="width:185px;" validate="required:true">';
							while($products = tep_db_fetch_array($sel_pro)) {	
								
								$selected_product = "";
								if($artwork_content['products_id']==$products['orders_products_id']) {
									$selected_product = "Selected";
								}
								echo "<option value='".$products['orders_products_id']."' ".$selected_product."> " . $products['products_name'] . " </option>";
							}
							echo '</select>';
						} else {
							echo '<input type="text" name="product_name" id="product_name" validate="required:true" value="'.$artwork_content['products_id'].'">';
						}
					  ?>
					  </td>
						<td>&nbsp;</td>
				  </tr>			  
				 
				  <tr>
					  <td valign="top"><strong>Artwork Option:	</strong></td> 
					  <td id="design-container"> 
					  
					  	<?php 
						$i=1;
							foreach($artwork_options as $options=>$ops) {
								$opsID = $ops['artwork_option_id'];
								$high_resolution = tep_get_resolution($opsID);
								$hi_res_image = "";
								if(!empty($high_resolution['resolution_image_path'])) {
									$hi_res_image = ' or <a href="../'.$high_resolution['resolution_image_path'].'" target="_blank"><b>High Resolution</b></a> &nbsp;';
								}
								echo '<input type="hidden" name="all_options[]" value="'.$opsID.'">';
								
								echo '<div id="design_'.$opsID.'" style="float:left; clear:both;"><input type="hidden" class="prev_design" name="prev_options['.$opsID.']" value="'.$ops['option_image'].'"> View <a href="../'.$ops['option_image'].'" target="_blank"><b>'.$ops['option_name'].'</b></a>' . $hi_res_image . ' <input type="button" name="remove_design" class="remove_design" id="remove_design_'.$opsID.'" value="Remove" onclick="funRemove('.$opsID.')" style="float:left;">&nbsp;<input type="button" name="change_design" class="change_design" id="change_design_'.$opsID.'" value="Change" onclick="funChange('.$opsID.')" style="float:left;">									
								</div>';
																							
								$i++;	
							}
							?>
							<br>
							<input type="file" class="artwork_design" name="artwork_design[]" id="artwork_design_1" /> &nbsp;&nbsp; <input type="button" name="add_design" id="add_design" value="Add design">
							<br>
							<input type="file" class="high_resolution" name="high_resolution[]" id="high_resolution_1" />
							&nbsp;<font class="blue-txt">High Resolution design</font>
			  
					  </td>
					  <td>&nbsp;</td>
				  </tr>
				 
				  <tr>		
				  	  <td valign="top"><strong>Creative Brief: </strong></td>
					  <td><textarea name="brief" style="width:175px;"><?php echo $artwork_content['creative_brief']; ?></textarea></td>
					  <td>&nbsp;</td>
				  </tr>
				  <tr>
					  <td><strong>Sales Consultant:	</strong></td> 
					  <td> 
					  	<?php
											
						if(count($admin_arr)>0) {
							echo '<select name="sales_consultant" validate="required:true">';
							foreach($admin_arr as $admin=>$admin_detail) {
								
								$selected_consultant = "";
								if($artwork_content["sales_consultant"]==$admin_detail['admin_id']) {
									$selected_consultant = "selected";
								} 
								
								echo '<option value="'.$admin_detail['admin_id'].'" '.$selected_consultant.'>'.$admin_detail['admin_firstname']." ".$admin_detail['admin_lastname'].'</option>';
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
								$selected_designer = "";
								if($artwork_content["designer"]==$admin_detail['admin_id']) {
									$selected_designer = "selected";
								} 
								echo '<option value="'.$admin_detail['admin_id'].'" '.$selected_designer.'>'.$admin_detail['admin_firstname']." ".$admin_detail['admin_lastname'].'</option>';
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
					  		<option value="pending" <?php echo ($artwork_content['artwork_status']=="pending")?"selected":""; ?>>Pending</option>
							<option value="revision" <?php echo ($artwork_content['artwork_status']=="revision")?"selected":""; ?>>Revision</option>
							<option value="approve" <?php echo ($artwork_content['artwork_status']=="approve")?"selected":""; ?>>Approved</option>
						</select></td>
					  <td>&nbsp;</td>
				  </tr>
				  <tr>
					  <td><strong>Notify Customer:	</strong></td> 
					  <td> <input type="checkbox" name="notify_customer" id="notify_customer" <?php echo ($artwork_content['notify_customer']=="1")?"checked":""; ?>/></td>
					  <td>&nbsp;</td>
				  </tr>	
				 		 
				  <tr>
					  <td>&nbsp;</td>
					  <td><?php echo tep_image_submit('submit.png', "Submit"); ?></td>
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
			  		  
<?php
	$oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
	if (isset($_GET['SoID'])) {
		$oscid .= '&SoID=' . $_GET['SoID'];
	}   
?>

                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">                
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