<?php
include('includes/configure.php');
include('includes/filenames.php');
include('includes/database_tables.php');
include('includes/functions/database.php');
include('includes/functions/general.php');

include('includes/languages/english/account_artworks.php');


tep_db_connect();
//Get Post Variables. The name is the same as
//what was in the object that was sent in the jQuery
if (isset($_POST['action'])){

    $aID = $_POST['artID'];  
	$opID = $_POST['opID'];
	$action = $_POST['action'];
				
	if($action=="approved") {
		$message = "Design has been approved.";
		$updArtStatus = tep_db_query("update artwork set artwork_status='approved' where artwork_id='".$aID."'");
		$updArtOption = tep_db_query("update artwork_option set option_approve='1' where artwork_option_id='".$opID."'");
					
		$unixtime = time();
		$now = gmdate("Y-m-d H:i:s", $unixtime);		
		
		$selArtCust = tep_db_query("SELECT customers_id FROM artwork WHERE artwork_id='".$aID."'");
		$artCustInfo = tep_db_fetch_array($selArtCust);
		
		$sqlInsFeedback = tep_db_query("INSERT INTO artwork_feedback set feedback='".$message."', artwork_id='".$aID."', artwork_option_id='".$opID."', user_type='customer', posted_by='".$artCustInfo['customers_id'] ."', status='approved', posted_date ='".$now."'");	
		
		//$appStatus = 'approved';
		
		//get artwork information to send email - Start
		
		$sql_sel_artwork = tep_db_query("SELECT * FROM artwork WHERE artwork_id='".$aID."'");
		if(tep_db_num_rows($sql_sel_artwork)>0) {
			
			$artwork_detail = tep_db_fetch_array($sql_sel_artwork);
			
			if(is_numeric($artwork_detail['products_id'])) {
				$ord_prod = tep_get_order_products($artwork_detail['products_id']);
				$prd_name = $ord_prod['products_name'];
				$mail_sub = $artwork_detail['orders_id']; 
			} else {
				$prd_name = $artwork_detail['products_id']; 
				$mail_sub = $artwork_detail['products_id']; 
			}
						
			
			//Get customers information
			$customer_query = tep_db_query("select c.*, a.*, ci.* from customers c left join address_book a on c.customers_default_address_id = a.address_book_id LEFT JOIN customers_info ci on c.customers_id=ci.customers_info_id where a.customers_id = c.customers_id and c.customers_id = '" . $artCustInfo['customers_id'] . "'");
    		$customers_array = tep_db_fetch_array($customer_query);
			
			$customers_name = $customers_array['customers_firstname']. ' ' . $customers_array['customers_lastname'];
						
			if(!empty($customers_array["entry_company_tax_id"])) {
				$customer_number = $customers_array["entry_company_tax_id"];
			} else {
				$customer_number = $customers_array["customers_id"];
			}
			
			//option revision count			
			if($artwork_detail['artwork_status']=="revision") {
				$revision_count = tep_get_artwork_revision_count($aID);
				$op_status = $artwork_detail['artwork_status'] . " " . $revision_count;			
			} else {
				$op_status = $artwork_detail['artwork_status'];
			}
				
			//get option information
			$option_info = tep_get_option_details($opID);
			
			//artwork content for sending email
			$sales_admin = tep_get_admin_details($artwork_detail['sales_consultant']);
			$sales_admin_name = $sales_admin['admin_firstname']. ' ' . $sales_admin['admin_lastname'];
			
			$design_admin = tep_get_admin_details($artwork_detail['designer']);
			$design_admin_name = $design_admin['admin_firstname']. ' ' . $design_admin['admin_lastname'];
			
			$created_admin = tep_get_admin_details($artwork_detail['designer_id']);
			$created_admin_name = $created_admin['admin_firstname']. ' ' . $created_admin['admin_lastname'];
						
			$art_content = '<b>Product name</b>: '.$prd_name."<br><br>".
					   '<b>Artwork Status</b>: ' . $op_status . "<br><br>" .
					   '<b>Option</b>: ' . $option_info['option_name'] . "<br><br>" .
					   '<b>Customer name</b>: '.$customers_name . "<br><br>" .
					   '<b>Customer number</b>: '.$customer_number."<br><br>" . 
					   '<b>Sales Consultant</b>: '. $sales_admin_name ."<br><br>".
					   '<b>Designer</b>: ' . $design_admin_name . "<br><br>" .
					   '<b>Created by</b>: ' . $created_admin_name . "<br><br>" .					   
					   '<b>Date Created</b>: ' . date("d-m-Y", strtotime($artwork_detail['date_created'])) . "<br>";
					
			$art_link = '<a href="http://namebadgesinternational.com.au/admin/artworks.php">Click Here</a>';											
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";						
			$headers .= 'From: Name Badges International <sales@namebadgesinternational.com.au>' . "\r\n";
			
			//mail to sales consultant			
			@mail($sales_admin['admin_email_address'], sprintf(ARTWORK_DESIGNER_APPROVAL_EMAIL_SUBJECT,$mail_sub), sprintf(ARTWORK_SALES_CONSULTANT_FEEDBACK_EMAIL_GREET, $sales_admin_name)."<br><br>".sprintf(ARTWORK_DESIGNER_FEEDBACK_EMAIL_INTRO,$customers_name,$mail_sub)."<br><br>".$art_content."<br><br>".sprintf(ARTWORK_ADMIN_LINK,$art_link)."<br><br>".ARTWORK_SALES_CONSULTANT_EMAIL_TEXT."<br><br>".ARTWORK_SALES_CONSULTANT_EMAIL_FOOTER, $headers);
			
			//mail to designer			
			@mail($design_admin['admin_email_address'], sprintf(ARTWORK_DESIGNER_APPROVAL_EMAIL_SUBJECT,$mail_sub), sprintf(ARTWORK_DESIGNER_FEEDBACK_EMAIL_GREET, $design_admin_name)."<br><br>".sprintf(ARTWORK_DESIGNER_FEEDBACK_EMAIL_INTRO,$customers_name,$mail_sub)."<br><br>".$art_content."<br><br>".sprintf(ARTWORK_ADMIN_LINK,$art_link)."<br><br>".sprintf(ARTWORK_DESIGNER_EMAIL_TEXT,$sales_admin_name)."<br><br>".ARTWORK_DESIGNER_EMAIL_FOOTER, $headers);
			
			//confirmation email to customer
		$option_info = tep_get_option_details($opID);
		
		$client_email_content = '<b>Product name</b>: '.$prd_name."<br><br>".
					   '<b>Your message</b>: '.$feedback."<br><br>".
					   '<b>Chosen option</b>: '. $option_info['option_name'] ."<br><br>".					   
					   '<b>Artwork Status</b>: ' . $op_status . "<br><br>" .
					   '<b>Date Created</b>: ' . date("d-m-Y", strtotime($artwork_detail['date_created'])) . "<br>";					   
					   
		@mail($customers_array['customers_email_address'], ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_SUB, sprintf(ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_GREET, $customers_name)."<br><br>".ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_INTRO."<br><br>".$client_email_content."<br>".ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_CONTENT."<br>".sprintf(ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_FOOTER,$sales_admin_name), $headers);
			
					
		}		
		//get artwork information and send email to designer and sales consultant when feedback posted - END
				
	}  //above loop xecute only when the option gets approved.
	
	$sel_artwork = tep_db_query("SELECT * FROM artwork WHERE artwork_id='".$aID."'");
	
	if(tep_db_num_rows($sel_artwork)>0) {
	
		$artwork_content = tep_db_fetch_array($sel_artwork);
		
		if(is_numeric($artwork_content['products_id'])) {
			$ord_prod = tep_get_order_products($artwork_content['products_id']);
			$prd_name = $ord_prod['products_name'];
		} else {
			$prd_name = $artwork_content['products_id']; 
		}
		
		//artwork status check
		$art_status = "";
		if($artwork_content['artwork_status']=="approved") {
			$art_status = "Style='display:none;'";
		}
											
		//get artwork options
		$artwork_options = tep_get_artwork_option($aID);
		
		//for selected option
		$selected_option = "";
		$p=1;
		foreach($artwork_options as $options=>$ops) {
			if($ops['option_approve']=="1") {
				$selected_option = '<h4 style="color:#3973B3;"> Approved design: <b>'.$ops['option_name'].'</b> </h4><br>';
			}
			$p++;
		}
		
		
		/* Artwork option start	*/				  				
								
		$opt = '<div class="tap-container">
	
			<h1 class="pageHeading" style="border:none">Artwork Options for '.$prd_name.'</h1><br>
			 '.$selected_option.' 
			
			<ul class="tabs">';
					
					$i=1;
					foreach($artwork_options as $options=>$ops) {
						$opt .= '<li><a href="#tab'.$ops['artwork_option_id'].'">'.$ops['option_name'].'</a></li>';
						$i++;
					}
		$opt .= '			
			</ul>
			<div class="tab_container">';
				
					$i=1;
					foreach($artwork_options as $options=>$ops) {
						
						$high_resolution = tep_get_resolution($ops['artwork_option_id']);
						
						$opt .= '<div id="tab'.$ops['artwork_option_id'].'" class="tab_content" style="text-align:center;">';
						list($width,$height) = getimagesize($ops['option_image']);
						if($width>680) {
							$opt .= '<img src="image_thumb.php?file='.$ops['option_image'].'&sizex=680&sizey=450" style="float:none;">';
						} else if($height>680) {
							$opt .= '<img src="image_thumb.php?file='.$ops['option_image'].'&sizex=450&sizey=680" style="float:none;">';
						} else {
							$opt .= '<img src="'.$ops['option_image'].'" style="float:none;">';
						}		
						
						if(!empty($high_resolution['resolution_image_path'])) {
							$opt .=	'<br><a href="'.$high_resolution['resolution_image_path'].'" title="High Resolution Design" id="fbox" style="color:#FF9900; font-weight:bold;"><img src="images/zoom.gif" border=0 style="margin:0;padding:0;">&nbsp;Click here to see High Resolution</a>';
						}						
						
						$opt .= '</div>';
						$i++;
					}
		$opt .= '			
			</div>
		 </div>
		 <table align="center" border="0" width="100%" style="margin:3px">
			<tr>
				<td valign="bottom">
					<a href="javascript:void(0);" '.$art_status.' id="btn-approve"><img src="images/artwork_approve.gif" /></a>							
					
				</td>
				<td valign="bottom" align="right">
					<a href="javascript:void(0);" '.$art_status.' id="btn-revision"><img src="images/artwork_revision.gif" /></a>								
				</td>
			</tr>
		 </table>	
		 <input type="hidden" name="art_option" id="art_option" value="'.$opID.'" />
		 <input type="hidden" name="active_artwork_id" id="active_artwork_id" value="'.$aID.'">
		 ';
								
		/* Artwork option end */		
		//Because we want to use json, we have to place things in an array and encode it for json.
		//This will give us a nice javascript object on the front side.
		echo json_encode(array("returnValue"=>$opt));
	}
	
}

?>
