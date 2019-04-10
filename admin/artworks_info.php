<?php
/*
  $Id: artworks_info.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/languages/english/artworks.php');
  
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
  	tep_redirect(tep_href_link(FILENAME_CUSTOMERS, '', 'SSL'));
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
	
	//get artwork info
  	$sel_artwork = tep_db_query("SELECT * FROM artwork WHERE artwork_id='".$aID."'");
	$artwork_content = tep_db_fetch_array($sel_artwork);
	
	if(is_numeric($artwork_content['products_id'])) {
		$ord_prod = tep_get_order_products($artwork_content['products_id']);
		$prd_name = $ord_prod['products_name'];
	} else {
		$prd_name = $artwork_content['products_id']; 
	}
	
	$art_status = "";
		
	//print_r($artwork_options);
	//exit;
	
   //post feedback
    if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    
    $attachment = tep_db_prepare_input($_FILES['attachment']['name']);
	
	$revision = tep_db_prepare_input($_FILES['revision']['name']);
	$comment_to_email = $_POST['feedback'];
	
	$feedback = addslashes($_POST['feedback']);
	$opID = tep_db_prepare_input($_POST['art_option']);
	$opName = tep_db_prepare_input($_POST['option_name']);
	$artID = tep_db_prepare_input($_POST['active_artwork_id']);
	$artStatus = tep_db_prepare_input($_POST['artwork_status']);
	$notify_customer = 0;
	if($_POST['notify_customer']) {
		$notify_customer = 1;
	}
	
	$fileInfo = pathinfo($attachment);
	///print_r($_POST);
    $error = false;  

	$randamNumber=md5(microtime().rand(0,999999));
	
	if($attachment!="") {
		$myfiles_dir = 'artworks/attachments/';
		
		$path = DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension'];
		$ext = strtolower($fileInfo['extension']);				
		$upd = @move_uploaded_file($_FILES['attachment']['tmp_name'],"../".$path);
	}
	
	//add revision for options
	if($revision!="") {
		$rev_myfiles_dir = 'artworks/';
		$rev_file_info = pathinfo($revision);
		$rev_path = DIR_WS_IMAGES . $rev_myfiles_dir . $randamNumber.".".$rev_file_info['extension'];
		$rev_ext = strtolower($rev_file_info['extension']);				
		if($rev_ext=="jpeg" || $rev_ext=="jpg" || $rev_ext=="png" || $rev_ext=="gif") {			
			if(@move_uploaded_file($_FILES['revision']['tmp_name'],"../".$rev_path)) {		
				$revision_count = tep_get_revision_count($opID); //get revision count
				$option_name = $opName." Rev ".($revision_count+1);
				$insOption = tep_db_query("INSERT INTO artwork_option set artwork_id='".$artID."', option_image='".$rev_path."', option_name='".$option_name."', revision_id='".$opID."'");	
				$insert_option_id = tep_db_insert_id();	
			
				//Add high resolution image
				$randamNumber_2=md5(microtime().rand(0,999999));
				$rev_hi_res = tep_db_prepare_input($_FILES['rev_hi_res']['name']);
				$res_info = pathinfo($rev_hi_res);
				$res_path = DIR_WS_IMAGES . $rev_myfiles_dir . $randamNumber_2.".".$res_info['extension'];
				$res_ext = strtolower($res_info['extension']);		
				if($res_ext=="jpeg" || $res_ext=="jpg" || $res_ext=="png" || $res_ext=="gif") {		
					if(@move_uploaded_file($_FILES['rev_hi_res']['tmp_name'],"../".$res_path)) {		
						$insResOption = tep_db_query("INSERT INTO artwork_option_resolution set artwork_option_id='".$insert_option_id."', resolution_image_path='".$res_path."'");
					}
				}	
			}
		}
		
	}
	
	$sqlInsFeedback = "INSERT INTO artwork_feedback set feedback='".$feedback."', posted_by='".$_SESSION['login_id']."', user_type='admin', artwork_option_id='".$opID."', attachment_name='".$attachment."', attachment='".$path."', notify_customer='".$notify_customer."', artwork_id='".$artID."', status='".$artStatus."', posted_date = now()";					  			  
	$rst_files = tep_db_query($sqlInsFeedback);	
	
	$sqlUpdArtwork = tep_db_query("UPDATE artwork SET artwork_status='".$artStatus."' WHERE artwork_id='".$artID."'");
	
	//get option information
	$option_info = tep_get_option_details($opID);
		
	//sending mail to sales consultant and customers
	//artwork content for sending email
		$sales_admin = tep_get_admin_details($artwork_content['sales_consultant']);
		$sale_admin_name = $sales_admin['admin_firstname'] . ' ' . $sales_admin['admin_lastname'];
		$design_admin = tep_get_admin_details($artwork_content['designer']);
		$design_admin_name = $design_admin['admin_firstname'] . ' ' . $design_admin['admin_lastname'];
		$created_admin = tep_get_admin_details((int)$_SESSION['login_id']);
		$created_admin_name = $created_admin['admin_firstname'] . ' ' . $created_admin['admin_lastname'];
		
		if($artwork_content['orders_id']>0) {
			$design_name = $artwork_content['orders_id'];
		} else {
			$design_name = $prd_name;
		}	  
								
		$art_content = '<b>Product name</b>: '.$prd_name."\n\n".
					   '<b>Artwork Status</b>: ' . $artStatus . "\n\n" .
					   '<b>Option</b>: ' . $option_info['option_name'] . "\n\n" .
					   '<b>Customer name</b>: '.$customers_name . "\n\n" .
					   '<b>Customer number</b>: '.$customer_number."\n\n" . 
					   '<b>Sale Consultant</b>: '. $sale_admin_name ."\n\n".
					   '<b>Designer</b>: ' . $design_admin_name . "\n\n" .
					   '<b>Created by</b>: ' . $created_admin_name . "\n\n" .					   
					   '<b>Date Created</b>: ' . date("d-m-Y", strtotime($artwork_content['date_created'])) . "\n\n";
		
			
		//mail to sales consultant		
		$semail .= '<a href="' . str_replace('&amp;', '&', tep_href_link('artworks.php', 'cID=' . $cID, 'SSL', false)) . '">Click Here</a>';				
		tep_mail($sale_admin_name, $sales_admin['admin_email_address'], sprintf(ARTWORK_SALES_CONSULTANT_FEEDBACK_SUBJECT,$artStatus,$design_name), sprintf(ARTWORK_SALES_CONSULTANT_FEEDBACK_GREET, $sale_admin_name, $created_admin_name,$design_name).$art_content.sprintf(ARTWORK_ADMIN_LINK,$semail).ARTWORK_SALES_CONSULTANT_EMAIL_TEXT.ARTWORK_SALES_CONSULTANT_EMAIL_FOOTER, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		
		//mail to customer
		if($notify_customer) {		
			
			$client_email_content = '<b>Product name</b>: '.$prd_name."\n\n".
					   '<b>Message</b>: '.stripslashes($comment_to_email)."\n\n".
					   '<b>Option</b>: '. $option_info['option_name'] ."\n\n".					   
					   '<b>Artwork Status</b>: ' . $artStatus . "\n\n" ;		
					   
			$cemail .= '<a href="'.HTTP_SERVER.'/account_artworks.php?osCAdminID=f946fdcfff1cd337815f2b47bb3f5a7a">Click Here</a>';		
			tep_mail($customers_name, $customers_array['customers_email_address'], sprintf(ARTWORK_CLIENT_FEEDBACK_SUBJECT,$artStatus,$design_name), sprintf(ARTWORK_CLIENT_FEEDBACK_GREET,$customers_name,$design_name) .$client_email_content. sprintf(ARTWORK_CLIENT_LINK,$cemail) . sprintf(ARTWORK_CLIENT_EMAIL_FOOTER,$sale_admin_name), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
			
		}
	
	//tep_redirect(tep_href_link(FILENAME_ARTWORKS_INFO, '&amp;msg=suc', 'SSL'));
	
  }
  
  //get artwork options
	$artwork_options = tep_get_artwork_option($aID);
    
//get all admin
$admin_arr = tep_get_all_admin();	



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
</style>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>

<script type="text/javascript" src="includes/javascript/jquery.js"></script>

<!-- Thick box -->
<script type="text/javascript" src="includes/javascript/thickbox.js"></script>
<link rel="stylesheet" href="includes/javascript/thickbox.css" type="text/css" media="screen" />
<!-- Thick box -->

<script type="text/javascript">

function funGetID(idstring) {		
	var id = idstring.lastIndexOf("#")+4;		
	return idstring.substring(id);			
}
	
$(document).ready(function() {

	//Default Action
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	default_id = parseInt(funGetID($("ul.tabs li:first").find("a").attr("href")));		
	$("#art_option").val(default_id);	
	opt_name = $("ul.tabs li:first").find("a").html();
	$("#revision_title").html("for "+opt_name);
	$("#option_name").val(opt_name);
	
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		active_id = parseInt(funGetID($(this).find("a").attr("href")));
		$("#art_option").val(active_id);
		opt_name = $(this).find("a").html();	
		$("#revision_title").html("for "+opt_name);
		$("#option_name").val(opt_name);
		
		return false;
	});
		
});

function confirm_feedback() {
				
		if($("#feedback").val()=="") {
			alert("Please enter your message!");
			return false;
		}
		if(confirm("Are you sure want to post feedback for this option?")) {
			return true;
		}
		return false;
	}
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
				
					if($_GET['msg']=="suc") {
						echo "Message posted successfully";
					} else if($_GET['msg']=="delete") {
						echo "Artwork design deleted successfully";
					}
				
				}
				?>
				</div>
				
				<?php	
		  	echo tep_draw_form('frmArtwork', FILENAME_ARTWORKS_INFO, tep_get_all_get_params(array('cID','aID')) . 'cID=' . $cID .'&aID='.$aID, 'post', 'id="frmArtwork" enctype="multipart/form-data"', 'SSL'). tep_draw_hidden_field('action', 'process');
  		?>	  	
		
				<table border="0" width="100%">
					<tr>					  
					  <td width="70%" rowspan="12" valign="top">
					  		
							<!-- Artwork option start -->					  				
							
							<div class="container">

								<h3>Artwork Options for <?php echo $prd_name; ?></h3>
								<?php
								//for selected option								
								$p=1;
								foreach($artwork_options as $options=>$ops) {
									if($ops['option_approve']=="1") {
										echo '<h4 style="color:#3973B3;"> Approved design: <b>'.$ops['option_name'].'</b> </h4><br>';
									}
									$p++;
								}
								?>
								<ul class="tabs">
									<?php 
										//print_r($artwork_options);
										$i=1;
										foreach($artwork_options as $options=>$ops) {
											//check revision id
											$rev_to = 'Option '.$i;
										/*	if($ops['revision_id']>0) {
												$rev_to .= " - ".$i;
											}*/
											//get revision
											//$rev = tep_get_artwork_revision($ops['artwork_option_id']);
											
											
											echo '<li><a href="#tab'.$ops['artwork_option_id'].'" id="'.$i.'">'.$ops['option_name'].'</a></li>';
											$i++;
										}
									?>							
								</ul>
								<div class="tab_container">
									
									<?php
										$i=1;
										foreach($artwork_options as $options=>$ops) {
											
											$high_resolution = tep_get_resolution($ops['artwork_option_id']);
											
											echo '<div id="tab'.$ops['artwork_option_id'].'" class="tab_content" style="text-align:center;">';											
												//echo '<img src="../image_thumb.php?file='.$ops['option_image'].'&sizex=500&sizey=400">';
												list($width,$height) = getimagesize("../".$ops['option_image']);
												if($width>680) {
													echo '<img src="../image_thumb.php?file='.$ops['option_image'].'&sizex=680&sizey=450">';
													if(!empty($high_resolution['resolution_image_path'])) {
														echo 	'<br><a href="../'.$high_resolution['resolution_image_path'].'" title="" class="thickbox" style="color:#FF9900; font-weight:bold;"><img src="images/zoom.gif" border=0 style="margin:0;padding:0;">&nbsp;Click here to see High Resolution</a>';
													}		
						
												} else if($height>680) {
													echo '<img src="../image_thumb.php?file='.$ops['option_image'].'&sizex=450&sizey=680">';
													if(!empty($high_resolution['resolution_image_path'])) {
														echo 	'<br><a href="../'.$high_resolution['resolution_image_path'].'" title="" class="thickbox" style="color:#FF9900; font-weight:bold;"><img src="images/zoom.gif" border=0 style="margin:0;padding:0;">&nbsp;Click here to see High Resolution</a>';
													}		
												} else {
													echo '<img src="../'.$ops['option_image'].'">';
													if(!empty($high_resolution['resolution_image_path'])) {
														echo 	'<br><a href="../'.$high_resolution['resolution_image_path'].'" title="" class="thickbox" style="color:#FF9900; font-weight:bold;"><img src="images/zoom.gif" border=0 style="margin:0;padding:0;">&nbsp;Click here to see High Resolution</a>';
													}
												}					

											echo '</div>';
											$i++;
										}
									?>																										
									
								</div>
						  </div>
							
					  		<!-- Artwork option end -->
							
							
							
							<!-- <br><br><table align="center" border="0" width="100%" style="margin:3px">
								<tr>
									<td valign="bottom">
										<a href="javascript:void(0);" <?php echo $art_status; ?> id="btn-approve"><img src="../images/artwork_approve.gif" /></a>							
										
									</td>
									<td valign="bottom" align="right">
										<a href="javascript:void(0);" <?php echo $art_status; ?> id="btn-revision"><img src="../images/artwork_revision.gif" /></a>								
									</td>
								</tr>
							</table>	-->
		 					<br><br>
							<!-- Feedback Start -->					
							<table id="option-feedback" align="center" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #DDD;">
								<tr>
									<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td><strong>Upload Revision</strong><br><div id="revision_title"></div></td>
									<td><input type="file" name="revision" id="revision" /><br>
										<input type="file" name="rev_hi_res" id="rev_hi_res" />&nbsp;<font class="blue-txt">High Resolution design</font></td>
								</tr>
								<tr>
									<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								</tr>
								<tr>
								  <td width="1%" valign="top">&nbsp;</td>
								  <td width="18%" valign="top"><strong>Comment</strong></td>
								  <td width="80%" valign="top"><textarea name="feedback" id="feedback" class="tinymce" style="width:520px;"></textarea></td>
								</tr>
								<tr>
									<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td><strong>Attachment</strong></td>
									<td><input type="file" name="attachment" id="attachment" /></td>
								</tr>
								<tr>
									<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td><strong>Status</strong></td>
									<td><select name="artwork_status">
											<option value="pending">Pending</option>
											<option value="revision">Revision</option>
											<option value="approved">Approved</option>
										</select>									  </td>
								</tr>
								<tr>
									<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td><strong>Notify Customer:</strong></td>
									<td><input type="checkbox" name="notify_customer" id="notify_customer" />  </td>
								</tr>
								<tr>
									<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
									<td>
										<input type="hidden" name="option_name" id="option_name" value="">
										<?php echo tep_image_submit('button_submit.gif', "Submit", 'onclcik="return confirm_feedback()"'); ?>
									
									</td>
								</tr>
								<tr>
									<td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
								</tr>						
						   </table>				  
						   <!-- Feedback End -->
							
							
					  </td>
					  
					  <td width="30%" valign="top">					  	
							
							<!-- Artwork info start -->
							
							<?php										  					  	 	
							
							$country_query = tep_db_query("SELECT countries_name FROM " . TABLE_COUNTRIES . " WHERE countries_id = " . (int)$customers_array['entry_country_id']);
							$country_array = tep_db_fetch_array($country_query);
							
					  ?>
					  		<table width="100%" border="0" cellspacing="0" cellpadding="2">
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Product name"; ?></b></td>
								<td>: <?php echo $prd_name; ?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td valign="top"><b><?php echo "Creative Brief"; ?></b></td>
								<td>: <?php echo $artwork_content['creative_brief']; ?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Sale Consultant"; ?></b></td>
								<td>: <?php 
									$sales_admin = tep_get_admin_details($artwork_content['sales_consultant']);
									echo $sales_admin['admin_firstname']." ".$sales_admin['admin_lastname']; 
									
									?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Designer"; ?></b></td>
								<td>: <?php 									
									$design_admin = tep_get_admin_details($artwork_content['designer']);
									echo $design_admin['admin_firstname']." ".$design_admin['admin_lastname']; 									
									?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Created by"; ?></b></td>
								<td>: <?php 									
									$created_admin = tep_get_admin_details($artwork_content['designer_id']);
									echo $created_admin['admin_firstname']." ".$created_admin['admin_lastname']; 									
									?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Artwork Status"; ?></b></td>
								<td>: <?php echo $artwork_content['artwork_status']; ?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Date Created"; ?></b></td>
								<td>: <?php echo $artwork_content['date_created']; ?></td>
							  </tr>
							  
							  <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Company name"; ?></b></td>
								<td>: <?php echo $company_name; ?></td>
							  </tr>
							  
							  <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td width="42%" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
								<td width="58%">
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
								<td><b><?php echo ENTRY_CUSTOMER_NUMBER; ?></b></td>
								<td><?php echo $customer_number; ?></td>
							  </tr>
							  <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
								<td><?php echo $customers_array['entry_telephone']; ?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
								<td><?php echo '<a href="mailto:' . $customers_array['customers_email_address'] . '"><u>' . $customers_array['customers_email_address'] . '</u></a>'; ?></td>
							  </tr>	
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>	
							   <tr>
								<td><b><?php echo "Customer Notified To: "; ?></b></td>
								<td><?php echo $customers_array['customers_email_address'] ; ?></td>
							  </tr>
							  <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>	
							  <tr>
								<td><b><?php echo "Cc To: "; ?></b></td>
								<td><?php echo $artwork_content['artwork_cc']; ?></td>
							  </tr>	
							  <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Bcc To: "; ?></b></td>
								<td><?php echo $artwork_content['artwork_bcc']; ?></td>
							  </tr>									 
							</table>
							
							<!-- Artwork info end -->
										 			  
						</td>
				  </tr>			
				</table>
				
				<input type="hidden" name="art_option" id="art_option" value="<?php echo $opID; ?>" />
				<input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['login_id']; ?>">
		 		<input type="hidden" name="active_artwork_id" id="active_artwork_id" value="<?php echo $aID; ?>">		 
			  </form>
			  
			  <br><br>
			  
			  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php
    $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
    if (isset($_GET['SoID'])) {
      $oscid .= '&SoID=' . $_GET['SoID'];
    }   
?>                 
                  <tr><td colspan="4" id="feedback-history">
				  
				  <!-- Feedback history start -->
				  <?php 
				  
				  $sel_feedback = tep_db_query("SELECT * FROM artwork_feedback WHERE artwork_id='".$aID."' order by artwork_feedback_id desc");
							
		echo '<table border="1" cellspacing="0" cellpadding="5" width="100%">
				  <tr class="smallText">                    
					<td class="smallText" width="14%"><b>Date Added</b></td>             
                    <td class="smallText" width="8%"><b>Customer notified</b></td>
					<td class="smallText" width="10%"><b>User</b></td>
					<td class="smallText" width="8%"><b>Status</b></td>     						
					<td class="smallText" width="12%"><b>Option</b></td>			 
					<td class="smallText" width="8%"><b>Attachment</b></td>
					<td class="smallText" width="40%"><b>Comment</b></td>   
                  </tr>';
		if(tep_db_num_rows($sel_feedback)>0) {	
							
			$i=1;
			
			$revision_count = tep_get_artwork_revision_count($aID);
			
			while($artwork_content = tep_db_fetch_array($sel_feedback)) {
				
				if($artwork_content['attachment']=="") {
					$att = "N/A";
				} else {
					$att = '<a href="../'.$artwork_content['attachment'].'" target="_blank">'.$artwork_content['attachment_name'].'</a>';
				}
				
				if($artwork_content['user_type']=="admin") {				
					$posted_admin = tep_get_admin_details($artwork_content['posted_by']);
					$username = $posted_admin['admin_firstname']." ".$posted_admin['admin_lastname']; 
				} else {
					//Get customer info
					$customers_query = tep_db_query("select customers_lastname, customers_firstname from customers WHERE customers_id = " . $artwork_content['posted_by']);        					
					$customers_array = tep_db_fetch_array($customers_query);
					$username = $customers_array['customers_firstname']." ".$customers_array['customers_lastname']; 
				}
				
				if($artwork_content['status']=="revision") {
					$status = $artwork_content['status'] . " " . $revision_count;
					$revision_count--;
				} else {
					$status = $artwork_content['status'];
				}
									
				echo  '<tr>							
							<td>'.date("d-m-Y H:i:g", strtotime($artwork_content['posted_date'])).'</td>
							<td align="center">';
							
							if ($artwork_content['notify_customer']) {
							  echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK);
							} else {
							  echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS);
							}
							
							
						echo '</td>
							<td>'.$username.'</td>
							<td>'.$status.'</td>';
							$p=1;
							foreach($artwork_options as $options=>$ops) {
								if($artwork_content['artwork_option_id']==$ops['artwork_option_id']) {
									echo '<td>'.$ops['option_name'].'</td>';
								}
								$p++;
							}							
						echo '<td>'.$att.'</td>					
							<td>'.stripslashes($artwork_content['feedback']).'</td>							
						</tr>';	
																				
				$i++;
			}						
			
		} else {
			echo '<tr><td colspan="4">No Feedback available.</td></tr>';	
		}	
		
		echo '</table>';
			
	?>
				  
				  <!-- Feedback history end -->
				  
				  </td></tr>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
                  <tr>
                    <td colspan="6">&nbsp;</td>
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