<?php

if (isset($_POST['action']) && ($_POST['action'] == 'preview') ) {
    	
    $order = "";
		
	$brief = tep_db_prepare_input(addslashes($_POST['brief']));
	if(isset($_POST['order'])) {
		$order = tep_db_prepare_input($_POST['order']);
	}
	$product = tep_db_prepare_input($_POST['product_name']);
	
	if(is_numeric($product)) {
		$ord_prod = tep_get_order_products($product);
		$prd_name = $ord_prod['products_name'];
	} else {
		$prd_name = $product; 
	}
		
	$design_status = tep_db_prepare_input($_POST['design_status']);
	$notify_customer = tep_db_prepare_input($_POST['notify_customer']);
	
	$artwork_cc = "";
	$artwork_bcc = "";	
	if($notify_customer) {		
		if(tep_db_prepare_input($_POST['artwork_cc'])!="") {
			$artwork_cc = tep_db_prepare_input($_POST['artwork_cc']);
		}
		if(tep_db_prepare_input($_POST['artwork_bcc'])!="") {
			$artwork_bcc = tep_db_prepare_input($_POST['artwork_bcc']);
		}		
	}
	
	$sales_consultant = tep_db_prepare_input($_POST['sales_consultant']);
	$designer = tep_db_prepare_input($_POST['designer']);
	$linked_to_order = tep_db_prepare_input($_POST['linked_to_order']);
		
    $error = false;  
   	 
	$files_arr = array();
	
	$files_count = count($_FILES['artwork_design']['name']);
	
	for($i=0;$i<=$files_count;$i++) {
				
		$artwork_design = $_FILES['artwork_design']['name'][$i];
				
		$fileInfo = pathinfo($artwork_design);
		
		$randamNumber=md5(microtime().rand(0,999999)).$i;
		
		$myfiles_dir = 'artworks/temp/';
		
		$path = DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension'];		
		
		$ext = strtolower($fileInfo['extension']);
		
		if($ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="gif") {
				
			if(move_uploaded_file($_FILES['artwork_design']['tmp_name'][$i],"../".$path)) {				
				
				$error = true;
				
				$artwork_high_resolution = $_FILES['high_resolution']['name'][$i];		
						
				if(!empty($artwork_high_resolution)) {				
				
					$highResInfo = pathinfo($artwork_high_resolution);
					$randamNumber_2 = md5(microtime().rand(0,999999)).$i;
					$path_2 = DIR_WS_IMAGES . $myfiles_dir . $randamNumber_2 . "." . $highResInfo['extension'];
					$ext_2 = strtolower($highResInfo['extension']);
				
					if($ext_2=="jpeg" || $ext_2=="jpg" || $ext_2=="png" || $ext_2=="gif" || $ext_2=="bmp") {
						if(move_uploaded_file($_FILES['high_resolution']['tmp_name'][$i],"../".$path_2)) {	
							$res_files_arr[$i] = $path_2;
						}
					}
					
				}				
				
				$count = ($i+1);
				
				$files_arr[$count] = $path;
				
			 }
		 }	 
		
	}	
		
	//print_r($_POST);
	//print_r($files_arr);
					
  } //End of preview
  
  
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

.container {width: 700px; margin: 5px auto;}
ul.tabs {
	margin: 0;
	padding: 0;
	float: left;
	list-style: none;
	height: 32px;
	border-bottom: 1px solid #DDD;
	border-left: 1px solid #DDD;
	width: 100%;
}
ul.tabs li {
	float: left;
	margin: 0;
	padding: 0;
	height: 31px;
	line-height: 31px;
	border: 1px solid #DDD;
	border-left: none;
	margin-bottom: -1px;
	background: #e0e0e0;
	overflow: hidden;
	position: relative;
}
ul.tabs li a {
	text-decoration: none;
	color: #000;
	display: block;
	font-size: 1.2em;
	padding: 0 20px;
	border: 1px solid #fff;
	outline: none;
}
ul.tabs li a:hover {
	background: #ccc;
}	
html ul.tabs li.active, html ul.tabs li.active a:hover  {
	background: #fff;
	border-bottom: 1px solid #fff;
}
.tab_container {
	border: 1px solid #DDD;
	border-top: none;
	clear: both;
	float: left; 
	width: 100%;
	background: #fff;
	-moz-border-radius-bottomright: 5px;
	-khtml-border-radius-bottomright: 5px;
	-webkit-border-bottom-right-radius: 5px;
	-moz-border-radius-bottomleft: 5px;
	-khtml-border-radius-bottomleft: 5px;
	-webkit-border-bottom-left-radius: 5px;
}
.tab_content {
	padding: 20px;
	font-size: 1.2em;
}
.tab_content h2 {
	font-weight: normal;
	padding-bottom: 10px;
	border-bottom: 1px dashed #ddd;
	font-size: 1.8em;
}
.tab_content h3 a{
	color: #254588;
}
.tab_content img {
	float: left;
	margin: 0 5px 5px 0;
	/*border: 1px solid #ddd;*/
	padding: 5px;
}


</style>

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>

<script type="text/javascript" src="includes/javascript/jquery.js"></script>

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
	//default_id = parseInt(funGetID($("ul.tabs li:first").find("a").attr("href")));		
	//$("#art_option").val(default_id);
	//alert("hai"+default_id);
	//getFeedback(default_id);
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		//active_id = parseInt(funGetID($(this).find("a").attr("href")));
		//$("#art_option").val(active_id);
		//alert("hi"+active_id);
		//getFeedback(active_id);
		return false;
	});
	
	//function to load option feedback
	function getFeedback(opID) {
		//alert(opID);
		$.post(		   
		"artwork_options_feedback.php", 
		{ opID: opID }, 
		function(data){
			//alert(data.returnValue);
			$('#feedback-history').html(data.returnValue);					
		},	   		
		"json"
		);   
	}

});
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
            <td class="pageHeading"><?php echo "Preview Artwork and design for " . $customers_array['customers_firstname'] . " " . $customers_array['customers_lastname']; ?></td>  
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
				echo tep_draw_form('frmArtwork', FILENAME_ARTWORKS, tep_get_all_get_params(array('cID')) . 'cID=' . $cID, 'post', 'id="frmArtwork" enctype="multipart/form-data"', 'SSL'). tep_draw_hidden_field('action', 'process') .				
				tep_draw_hidden_field('product_name', $product) .
				tep_draw_hidden_field('design_status', $design_status) . 
				tep_draw_hidden_field('notify_customer', $notify_customer) . 
				tep_draw_hidden_field('artwork_bcc', $artwork_bcc) . 
				tep_draw_hidden_field('artwork_cc', $artwork_cc) . 
				tep_draw_hidden_field('sales_consultant', $sales_consultant) . 
				tep_draw_hidden_field('designer', $designer) . 
				tep_draw_hidden_field('linked_to_order', $linked_to_order) . 
				tep_draw_hidden_field('order', $order) . 
				tep_draw_hidden_field('brief', $brief);
				
				foreach($files_arr as $options=>$ops) { ?>
					<input type="hidden" name="artwork_design[<?php echo $options; ?>]" value="<?php echo $ops; ?>">
				<?php
				}
								
				foreach($res_files_arr as $res_options=>$res_ops) { ?>
					<input type="hidden" name="high_resolution[<?php echo $res_options; ?>]" value="<?php echo $res_ops; ?>">
				<?php } ?>
				<table border="0" width="100%">
					<tr>					  
					  <td width="70%" rowspan="12" valign="top">
					  		
							<!-- Artwork option start -->					  				
							
							<div class="container">

								<h3>Artwork Options for <?php echo $prd_name; ?></h3>
								
								<ul class="tabs">
									<?php 
										//print_r($artwork_options);
										$i=1;
										foreach($files_arr as $options=>$ops) {
											echo '<li><a href="#tab'.$i.'">Option '.$i.'</a></li>';
											$i++;
										}
									?>							
								</ul>
								<div class="tab_container">
									
									<?php
										$i=1;
										foreach($files_arr as $options=>$ops) {
											echo '<div id="tab'.$i.'" class="tab_content">';											
												//echo '<img src="../image_thumb.php?file='.$ops['option_image'].'&sizex=500&sizey=400">';
												list($width,$height) = getimagesize("../".$ops);
												if($width>680) {
													echo '<img src="../image_thumb.php?file='.$ops.'&sizex=680&sizey=450">';
												} else if($height>680) {
													echo '<img src="../image_thumb.php?file='.$ops.'&sizex=450&sizey=680">';
												} else {
													echo '<img src="../'.$ops.'">';
												}					

											echo '</div>';
											$i++;
										}
									?>																											
									
								</div>
							 </div>
							
					  		<!-- Artwork option end -->
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
								<td>: <?php echo stripslashes($brief); ?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Sale Consultant"; ?></b></td>
								<td>: <?php 
									$sales_admin = tep_get_admin_details($sales_consultant);
									echo $sales_admin['admin_firstname']." ".$sales_admin['admin_lastname']; 
									
									?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Designer"; ?></b></td>
								<td>: <?php 									
									$design_admin = tep_get_admin_details($designer);
									echo $design_admin['admin_firstname']." ".$design_admin['admin_lastname']; 									
									?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Created by"; ?></b></td>
								<td>: <?php 									
									$created_admin = tep_get_admin_details((int)$_SESSION['login_id']);
									echo $created_admin['admin_firstname']." ".$created_admin['admin_lastname']; 									
									?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Artwork Status"; ?></b></td>
								<td>: <?php echo $design_status; ?></td>
							  </tr>
							   <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Date Created"; ?></b></td>
								<td>: <?php echo date("d-m-Y");; ?></td>
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
								<td><?php echo $artwork_cc; ?></td>
							  </tr>	
							  <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
							  </tr>
							  <tr>
								<td><b><?php echo "Bcc To: "; ?></b></td>
								<td><?php echo $artwork_bcc; ?></td>
							  </tr>											 
							</table>
																					
							<!-- Artwork info end -->
										 			  
						</td>
				  </tr>			
				</table>
				
				<?php echo "<div align='center'>".tep_image_submit('submit.png', "Submit")."</div>"; ?>
				
				</form>
			  
			  <br><br>
			  
			  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php
    $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
    if (isset($_GET['SoID'])) {
      $oscid .= '&SoID=' . $_GET['SoID'];
    }   
?>                 
                  <tr><td colspan="4" id="feedback-history"></td></tr>
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