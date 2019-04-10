<?php
/*
  $Id: account_artworks.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Copyright &copy; 2003-2005 Chain Reaction Works, Inc.
  
  Last Modified by : $Author$
  Latest Revision  : $Revision: 208 $
  Last Revision Date : $Date$
  License :  GNU General Public License 2.0
  
  http://creloaded.com
  http://creforge.com
  
*/

  require('includes/application_top.php');

  if ( ! isset($_SESSION['customer_id']) ) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_ARTWORKS);
   
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    
    $attachment = tep_db_prepare_input($_FILES['attachment']['name']);
	$feedback = tep_db_prepare_input(addslashes($_POST['feedback']));
	$opID = tep_db_prepare_input($_POST['art_option']);
	$artID = tep_db_prepare_input($_POST['active_artwork_id']);
	
	$fileInfo = pathinfo($attachment);
	///print_r($_POST);
    $error = false;  

	$randamNumber=md5(microtime().rand(0,999999));
	
	if($attachment!="") {
		$myfiles_dir = 'artworks/attachments/';
		
		$path = DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension'];
		$ext = strtolower($fileInfo['extension']);
		
		/*if($ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="gif" || $ext=="txt" || $ext=="xls" || $ext=="csv" || $ext=="xlsx" || $ext=="pdf" || $ext=="doc" || $ext=="ai" || $ext=="cdr" || $ext=="eps" || $ext=='docx' ) { 
		
			if(move_uploaded_file($_FILES['attachment']['tmp_name'],$path)) {
						
			 }
		}
		*/
		$upd = @move_uploaded_file($_FILES['attachment']['tmp_name'],$path);
	}
	
	$sqlInsFeedback = "INSERT INTO artwork_feedback set feedback='".$feedback."', posted_by='".(int)$_SESSION['customer_id']."', user_type='customer', artwork_option_id='".$opID."', attachment_name='".$attachment."', artwork_id='".$artID."', attachment='".$path."', status='revision', posted_date = now()";					  			  
	$rst_files = tep_db_query($sqlInsFeedback);	
	
	$sqlUpdArtwork = tep_db_query("UPDATE artwork SET artwork_status='revision' WHERE artwork_id='".$artID."'");
	
	//get artwork information to send email - Start
	
	$sql_sel_artwork = tep_db_query("SELECT * FROM artwork WHERE artwork_id='".$artID."'");
	
	if(tep_db_num_rows($sql_sel_artwork)>0) {
		
		$artwork_detail = tep_db_fetch_array($sql_sel_artwork);
		
		if(is_numeric($artwork_detail['products_id'])) {
			$ord_prod = tep_get_order_products($artwork_detail['products_id']);
			$prd_name = $ord_prod['products_name'];
		} else {
			$prd_name = $artwork_detail['products_id']; 
		}
					
		//Get customers information
		$customers_array = tep_get_customer_info();
		
		$customers_name = $customers_array['customers_firstname']. ' ' . $customers_array['customers_lastname'];
		
		if(!empty($customers_array["entry_company_tax_id"])) {
			$customer_number = $customers_array["entry_company_tax_id"];
		} else {
			$customer_number = $customers_array["customers_id"];
		}
		//option revision count
		
		if($artwork_detail['artwork_status']=="revision") {
			$revision_count = tep_get_artwork_revision_count($artID);
			$op_status = $artwork_detail['artwork_status'] . " " . $revision_count;			
		} else {
			$op_status = $artwork_detail['artwork_status'];
		}
				
		//get option information
		$option_info = tep_get_option_details($opID);
		
		//artwork content for sending email
		$sales_admin = tep_get_admin_details($artwork_detail['sales_consultant']);
		$sale_admin_name = $sales_admin['admin_firstname'] . ' ' . $sales_admin['admin_lastname'];
		$design_admin = tep_get_admin_details($artwork_detail['designer']);
		$design_admin_name = $design_admin['admin_firstname'] . ' ' . $design_admin['admin_lastname'];
		$created_admin = tep_get_admin_details($artwork_detail['designer_id']);
		$created_admin_name = $created_admin['admin_firstname'] . ' ' . $created_admin['admin_lastname'];
		
		$art_content = '<b>Product name</b>: '.$prd_name."\n\n".
					   '<b>Artwork Status</b>: ' . $op_status . "\n\n" .
					   '<b>Option</b>: ' . $option_info['option_name'] . "\n\n" .
					   '<b>Customer name</b>: '.$customers_name . "\n\n" .
					   '<b>Customer number</b>: '.$customer_number."\n\n" . 
					   '<b>Sale Consultant</b>: '. $sale_admin_name ."\n\n".
					   '<b>Designer</b>: ' . $design_admin_name . "\n\n" .
					   '<b>Created by</b>: ' . $created_admin_name . "\n\n" .					   
					   '<b>Date Created</b>: ' . date("d-m-Y", strtotime($artwork_detail['date_created'])) . "\n\n";
		
		if($artwork_detail['orders_id']>0) {
			$design_name = $artwork_detail['orders_id'];
		} else {
			$design_name = $prd_name;
		}	   
					   
		$art_link = '<a href="' . str_replace('&amp;', '&', tep_href_link('admin/artworks.php', 'cID='.$_SESSION['customer_id'], 'SSL', false)) . '">Click Here</a>';
						
		//mail to sales consultant						
		tep_mail($sales_admin_name, $sales_admin['admin_email_address'], sprintf(ARTWORK_SALES_CONSULTANT_FEEDBACK_EMAIL_SUBJECT,$artwork_detail['artwork_status'],$design_name), sprintf(ARTWORK_SALES_CONSULTANT_FEEDBACK_EMAIL_GREET, $sale_admin_name).sprintf(ARTWORK_SALES_CONSULTANT_FEEDBACK_EMAIL_INTRO,$customers_name,$artwork_detail['orders_id']).$art_content.sprintf(ARTWORK_ADMIN_LINK,$art_link).ARTWORK_SALES_CONSULTANT_EMAIL_TEXT.ARTWORK_SALES_CONSULTANT_EMAIL_FOOTER, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
		
		//mail to designer	
		tep_mail($design_admin_name, $design_admin['admin_email_address'], sprintf(ARTWORK_DESIGNER_FEEDBACK_EMAIL_SUBJECT,$artwork_detail['artwork_status'],$design_name), sprintf(ARTWORK_DESIGNER_FEEDBACK_EMAIL_GREET, $design_admin_name).sprintf(ARTWORK_DESIGNER_FEEDBACK_EMAIL_INTRO,$customers_name,$artwork_detail['orders_id']).$art_content.sprintf(ARTWORK_ADMIN_LINK,$art_link).sprintf(ARTWORK_DESIGNER_EMAIL_TEXT,$sales_admin_name).ARTWORK_DESIGNER_EMAIL_FOOTER, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	
		//confirmation email to customer
				
		$client_email_content = '<b>Product name</b>: '.$prd_name."\n\n".
					   '<b>Your message</b>: '.stripslashes($_POST['feedback'])."\n\n".
					   '<b>Chosen option</b>: '. $option_info['option_name'] ."\n\n".					   
					   '<b>Artwork Status</b>: ' . $op_status . "\n\n" .
					   '<b>Date Created</b>: ' . date("d-m-Y", strtotime($artwork_detail['date_created'])) . "\n\n";					   
					   
		tep_mail($customers_name, $customers_array['customers_email_address'], ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_SUB, sprintf(ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_GREET, $customers_name).ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_INTRO.$client_email_content.ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_CONTENT.sprintf(ARTWORK_CLIENT_FEEDBACK_CONFIRMATION_FOOTER,$sales_admin_name), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	
	}
	
	//get artwork information and send email to designer and sales consultant when feedback posted - END
	

	tep_redirect(tep_href_link(FILENAME_ACCOUNT_ARTWORKS, '&amp;msg=suc', 'SSL'));
	
  }
  
 
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_ARTWORKS, '', 'SSL'));
  
  $content = CONTENT_ACCOUNT_ARTWORKS;
  $javascript = 'popup_window.js';
  
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
   
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
