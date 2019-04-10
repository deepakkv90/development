<?php
/*
  $Id: account_myfiles.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

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
  
///////////////////////////////////////////////////  
//function to insert data into Crm for Account
function insert_notes_crm($customer_id,$path,$custom_file,$my_file_id)
{
      	$mode 	= "insert" ;
      	$ch 	= curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
      	//$url 	= "http://www.namebadgesinternational.com.au/CRM-success/manage_account_doc.php";
    	$url 	= "http://namebadgesinternational.com.au/CRM-success/manage_account_doc.php";
    	$fields = "customer_id=".urlencode($customer_id)
		  ."&mode=".urlencode($mode)
		  ."&path=".urlencode($path)
		  ."&cre_myfile_id=".urlencode($my_file_id)
	 	  ."&file_name=".urlencode($custom_file) ;
      
      	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
      	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
      	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
    	curl_setopt($ch, CURLOPT_URL,$url);	
    	$result = curl_exec ($ch);
    	curl_close ($ch);
    	//var_dump($result);
      //exit;
}
  
//Function for delete doc of Account from Crm
function delete_notes_crm( $custom_file_id)
{
      	$mode = "delete" ;
      	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_POST, 1);
      	//$url = "http://www.namebadgesinternational.com.au/CRM-success/manage_account_doc.php";
    	$url = "http://namebadgesinternational.com.au/CRM-success/manage_account_doc.php";
    	$fields = "my_file_id=".urlencode($custom_file_id)
		  ."&mode=".urlencode($mode) ;
      	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
    	curl_setopt($ch, CURLOPT_URL,$url);	
    	$result = curl_exec ($ch);
    	curl_close ($ch);
    	//var_dump($result);
      //exit;
}
//--------------------------------------------------------------
 

  if ( ! isset($_SESSION['customer_id']) ) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_MYFILES);
   
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    
    $char_arr = array("'", '"');
    $custom_file = str_replace($char_arr,"",tep_db_prepare_input($_FILES['custom_file']['name']));
	$comment = str_replace($char_arr,"",tep_db_prepare_input($_POST['comment']));
	
	$fileInfo = pathinfo($custom_file);
	//print_r($fileInfo);
    $error = false;  

    if (empty($custom_file)) {
      $error = true;
      $messageStack->add('frmMyfiles', "Please select the file");
    }		 
	
	$randamNumber=md5(microtime().rand(0,999999));
	
	$myfiles_dir = 'users_files/';
	
	$path = DIR_FS_CATALOG . DIR_WS_IMAGES . $myfiles_dir . $randamNumber.".".$fileInfo['extension'];
	$ext = strtolower($fileInfo['extension']);
	
	if($ext=="jpeg" || $ext=="jpg" || $ext=="png" || $ext=="gif" || $ext=="txt" || $ext=="xls" || $ext=="csv" || $ext=="xlsx" || $ext=="pdf" || $ext=="doc" || $ext=="ai" || $ext=="cdr" || $ext=="eps" || $ext=='docx' ) {
	
		if(move_uploaded_file($_FILES['custom_file']['tmp_name'],$path)) {
		
			if ($error == false) {
				$sqlInsFiles = "INSERT INTO ".TABLE_MYFILES." set file_name = '".$custom_file."', comment='".$comment."', file_path='".$path."', customers_id = '" . (int)$_SESSION['customer_id'] . "', date_uploaded = now()";					  
				$rst_files = tep_db_query($sqlInsFiles);
					   
				///////  Calling function for Synchronization with crm //////////////    
         			$my_fileid = mysql_insert_id();
		     		//Call function to insert data into Crm for Account
				$customer_id = (int)$_SESSION['customer_id'];
         			insert_notes_crm($customer_id,$path,$custom_file,$my_fileid);	   
				//-------------------------------------------------------------------
				
				/* Send confirmation to customer and sales consultant */
				require_once('includes/classes/class.phpmailer.php');
				$mail = new PHPMailer();
				$customer_arr = tep_get_customer_info();
				$customers_name = $customer_arr['customers_firstname']. ' ' . $customer_arr['customers_lastname'];
				
				/*
				if(!empty($customer_arr["sales_consultant"])) {
					$sales_admin = tep_get_admin_details($customer_arr["sales_consultant"]);
				} else {
					$sales_admin = tep_get_admin_details(15);
				}
				*/
				
				$sale_admin_name = $customer_arr["sales_consultant"];
				$sale_admin_email = $customer_arr['sales_consultant_email'];
				
				if(empty($sale_admin_email)) { $sale_admin_email = "sales@namebadgesinternational.com.au"; }
				if(empty($sale_admin_name)) { $sale_admin_name = "System"; }
				
				$mail_content = sprintf(TEXT_MYFILES_CONFIRMATION_EMAIL, $customer_arr["customers_firstname"], $custom_file, $comment, tep_date_aus_format(date("Y-m-d"),"short",$languages_id), (int)$_SESSION['customer_id'], $sale_admin_name);
				
				$mail->AddReplyTo($sale_admin_email,$sale_admin_name);
			
				$mail->SetFrom($sale_admin_email, $sale_admin_name);
				
				$mail->AddAddress($customer_arr['customers_email_address'], $customers_name);
				
				//$mail->CharSet="Shift_JIS";
				
				if(!empty($sale_admin_email)) {  $mail->AddBCC($sale_admin_email, $sale_admin_name);  }
				
				//if(!empty($sale_admin_email)) {  $mail->AddBCC("ananthan@ajparkes.com.au", $sale_admin_name);  }
				
				$mail->Subject    = TEXT_MYFILES_CONFIRMATION_SUBJECT;
				
				$mail->MsgHTML($mail_content);
				
				$mail->Send();

				/* Send confirmation to customer and sales consultant END*/
		
			    tep_redirect(tep_href_link(FILENAME_ACCOUNT_MYFILES, '&amp;msg=upload', 'SSL'));
			  
			}
		 }
  	 }
  }
  
  //delete custom files
  if(isset($_GET['action']) && $_GET['action']=="deleteconfirm") {
  	$custom_file_id = $_GET['delete'];
	
	$qry_check_id = "SELECT * FROM ".TABLE_MYFILES." WHERE files_id='".$custom_file_id."'";
	$qry_rst = tep_db_query($qry_check_id);
	if(tep_db_num_rows($qry_rst)>0) {
		$rst_arr = tep_db_fetch_array($qry_rst);
		$del_file_path = $rst_arr["file_path"];
		tep_db_query("DELETE FROM ".TABLE_MYFILES." WHERE files_id='".$custom_file_id."'");
		unlink($del_file_path);
		
		/////////// Added For CRM Synchronization ///////////////
		//call function to delete customer in Crm
    		delete_notes_crm($custom_file_id);
		//-------------------------------------------------------  

		tep_redirect(tep_href_link(FILENAME_ACCOUNT_MYFILES, '&amp;msg=delete', 'SSL'));
	}
	
  }
  

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_MYFILES, '', 'SSL'));
  
  $content = CONTENT_ACCOUNT_MYFILES;
  $javascript = 'popup_window.js';
  
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);   
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
