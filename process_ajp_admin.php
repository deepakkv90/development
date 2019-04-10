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
	
	//print_r($_POST);
 
    //exit;
 
 
  $data = array();
  $data = $_POST;
  
  //if(isset($_POST["insert"])) {
			
		foreach($data["users"] as $arrkey=>$arrval) {							
			
			$user_id_entry = check_user_id($arrval["uid"],$link_crm);
			
			if($user_id_entry>0) { 
				$userid = GUID();
				$user_id_entry_string = " id = '".$userid."', "; 
			} else { 
				$userid = $arrval["uid"];
				$user_id_entry_string = " id='".$arrval["uid"]."', "; 
			}
			
			$ins_usr_query = "INSERT INTO users SET ".$user_id_entry_string." user_name = '".escape_string($arrval['user_name'])."', ". 
				" user_hash = '".escape_string($arrval['user_hash'])."', "." system_generated_password = '".escape_string($arrval['system_generated_password'])."', ". " pwd_last_changed = '".escape_string($arrval['pwd_last_changed'])."', "." authenticate_id = '".escape_string($arrval['authenticate_id'])."', ". " sugar_login = '".escape_string($arrval['sugar_login'])."', first_name = '".escape_string($arrval['first_name'])."', last_name = '".escape_string($arrval['last_name'])."', reports_to_id = '".escape_string($arrval['reports_to_id'])."', is_admin = '".escape_string($arrval['is_admin'])."',external_auth_only = '".escape_string($arrval['external_auth_only'])."', receive_notifications = '".escape_string($arrval['receive_notifications'])."',description = '".escape_string($arrval['description'])."',date_entered = '".escape_string($arrval['date_entered'])."',date_modified = '".escape_string($arrval['date_modified'])."',modified_user_id = '".escape_string($arrval['modified_user_id'])."',created_by = '".escape_string($arrval['created_by'])."',title = '".escape_string($arrval['title'])."',department = '".escape_string($arrval['department'])."',phone_home = '".escape_string($arrval['phone_home'])."',phone_mobile = '".escape_string($arrval['phone_mobile'])."',phone_work = '".escape_string($arrval['phone_work'])."',phone_other = '".escape_string($arrval['phone_other'])."',phone_fax = '".escape_string($arrval['phone_fax'])."',status = '".escape_string($arrval['status'])."',address_street = '".escape_string($arrval['address_street'])."',address_city = '".escape_string($arrval['address_city'])."',address_state = '".escape_string($arrval['address_state'])."',address_country = '".escape_string($arrval['address_country'])."',address_postalcode = '".escape_string($arrval['address_postalcode'])."',deleted = '".escape_string($arrval['deleted'])."',portal_only = '".escape_string($arrval['portal_only'])."',employee_status = '".escape_string($arrval['employee_status'])."',messenger_id = '".escape_string($arrval['messenger_id'])."',messenger_type = '".escape_string($arrval['messenger_type'])."',is_group = '".escape_string($arrval['is_group'])."',addr_2 = '".escape_string($arrval['addr_2'])."',init = '".escape_string($arrval['init'])."',cus_type_cd = '".escape_string($arrval['cus_type_cd'])."',comm_pct = '".escape_string($arrval['comm_pct'])."',cus_type_cd_2 = '".escape_string($arrval['cus_type_cd_2'])."',comm_pct_2 = '".escape_string($arrval['comm_pct_2'])."',cus_type_cd_3 = '".escape_string($arrval['cus_type_cd_3'])."',comm_pct_3 = '".escape_string($arrval['comm_pct_3'])."',cus_type_cd_4 = '".escape_string($arrval['cus_type_cd_4'])."',comm_pct_4 = '".escape_string($arrval['comm_pct_4'])."',cus_type_cd_5 = '".escape_string($arrval['cus_type_cd_5'])."',comm_pct_5 = '".escape_string($arrval['comm_pct_5'])."',cus_type_cd_6 = '".escape_string($arrval['cus_type_cd_6'])."',comm_pct_6 = '".escape_string($arrval['comm_pct_6'])."',cus_type_cd_7 = '".escape_string($arrval['cus_type_cd_7'])."',comm_pct_7 = '".escape_string($arrval['comm_pct_7'])."',cus_type_cd_8 = '".escape_string($arrval['cus_type_cd_8'])."',comm_pct_8 = '".escape_string($arrval['comm_pct_8'])."',cus_type_cd_9 = '".escape_string($arrval['cus_type_cd_9'])."',comm_pct_9 = '".escape_string($arrval['comm_pct_9'])."',cus_type_cd_10 = '".escape_string($arrval['cus_type_cd_10'])."',comm_pct_10 = '".escape_string($arrval['comm_pct_10'])."',sls_ptd = '".escape_string($arrval['sls_ptd'])."',sls_ytd = '".escape_string($arrval['sls_ytd'])."',sls_last_yr = '".escape_string($arrval['sls_last_yr'])."',cost_ptd = '".escape_string($arrval['cost_ptd'])."',cost_ytd = '".escape_string($arrval['cost_ytd'])."',fin_chg = '".escape_string($arrval['fin_chg'])."',comm_ptd = '".escape_string($arrval['comm_ptd'])."',comm_ytd = '".escape_string($arrval['comm_ytd'])."',user_def_fld_1 = '".escape_string($arrval['user_def_fld_1'])."',user_def_fld_2 = '".escape_string($arrval['user_def_fld_2'])."',user_def_fld_3 = '".escape_string($arrval['user_def_fld_3'])."',user_def_fld_4 = '".escape_string($arrval['user_def_fld_4'])."',user_def_fld_5 = '".escape_string($arrval['user_def_fld_5'])."',filler_0001 = '".escape_string($arrval['filler_0001'])."',A4GLIdentity = '".escape_string($arrval['A4GLIdentity'])."',imported_from = '".escape_string($arrval['imported_from'])."'";
			
			$qry_rst = mysql_query($ins_usr_query, $link_crm);			
			if($qry_rst) {
				
				echo "Inserted user_name: ".escape_string($arrval['user_name'])."<br/>";				
				//$new_usr_id = mysql_insert_id($link_crm);
				$del_usr_query = "DELETE FROM users WHERE id = '".$userid."'";
				insert_query_log($ins_usr_query);delete_query_log($del_usr_query);
			}	
			
			
			$crm_eabr_num_of_row = check_crm_eabr_row($arrval["eabrid"],$link_crm);				
			if($crm_eabr_num_of_row==0) {
				$eabrid = $arrval["eabrid"];
			} else {
				$eabrid = GUID();
			}
						
			$ins_crm_eabr_qry = "INSERT INTO email_addr_bean_rel SET id='".$eabrid."', email_address_id='".$arrval["email_address_id"]."', bean_id='".$userid."', bean_module='".escape_string($arrval["bean_module"])."', primary_address='".$arrval["primary_address"]."', reply_to_address='".$arrval["reply_to_address"]."', date_created='".escape_string($arrval["date_created"])."', date_modified='".escape_string($arrval["date_modified"])."', deleted='".$arrval["deleted"]."', contact_id='".$arrval["contact_id"]."'";
			
			$qry_rst2 = mysql_query($ins_crm_eabr_qry, $link_crm);			
			if($qry_rst2) {			
				//$new_eabr_id = mysql_insert_id($link_crm);
				$del_crm_eabr_qry = "DELETE FROM email_addr_bean_rel WHERE id = '".$eabrid."'";
				insert_query_log($ins_crm_eabr_qry);delete_query_log($del_crm_eabr_qry);
			}	
			

			$eaaddress = escape_string($arrval["email_address"]);
					
			$crm_ea_num_of_row = check_crm_ea_row($arrval["eaid"],$eaaddress,$link_crm);				
			
			if($crm_ea_num_of_row==0) {
				
				$eaid = $arrval["eaid"];

				$ins_crm_ea_qry = "INSERT INTO email_addresses SET id='".$eaid."', email_address='".escape_string($arrval["email_address"])."', email_address_caps='".escape_string($arrval["email_address_caps"])."', invalid_email='".escape_string($arrval["invalid_email"])."', opt_out='".escape_string($arrval["opt_out"])."', date_created='".escape_string($arrval["date_created"])."', date_modified='".escape_string($arrval["date_modified"])."', deleted='".$arrval["deleted"]."', delete_now='".$arrval["delete_now"]."'";
				
				$qry_rst3 = mysql_query($ins_crm_ea_qry, $link_crm);			
				if($qry_rst3) {			
					//$new_ea_id = mysql_insert_id($link_crm);
					$del_crm_ea_qry = "DELETE FROM email_addresses WHERE id = '".$eaid."'";
					insert_query_log($ins_crm_ea_qry);delete_query_log($del_crm_ea_qry);
				}
			
			} 
			
			foreach($arrval['users_signatures'] as $signkey=>$signval) {	
				
				$ins_crm_us_qry = "INSERT INTO users_signatures SET id='".escape_string($signval["id"])."', date_entered='".escape_string($signval["date_entered"])."', date_modified='".escape_string($signval["date_modified"])."', deleted='".escape_string($signval["deleted"])."', user_id='".escape_string($userid)."', name='".escape_string($signval["name"])."', signature='".escape_string($signval["signature"])."', signature_html='".escape_string($signval["signature_html"])."'";
				
				$qry_rst4 = mysql_query($ins_crm_us_qry, $link_crm);			
				if($qry_rst4) {			
					//$new_us_id = mysql_insert_id($link_crm);
					$del_crm_us_qry = "DELETE FROM users_signatures WHERE id = '".escape_string($signval["id"])."'";
					insert_query_log($ins_crm_us_qry);delete_query_log($del_crm_us_qry);
				}

			}
					
					
			//exit;
			
			/*
			echo $arrkey;
			
			foreach($arrval["customers"] as $custkey=>$cusval) {
				
				//print_r($custkey);echo "<br>";
								
				$ins_cus_query .= "INSERT INTO customers SET ".$customer_id_entry_string." purchased_without_account = '".escape_string($cusval['purchased_without_account'])."', ". 
				" customers_gender = '".escape_string($cusval['customers_gender'])."', "." customers_firstname = '".escape_string($cusval['customers_firstname'])."', ".
				" customers_lastname = '".escape_string($cusval['customers_lastname'])."', "." customers_dob = '".escape_string($cusval['customers_dob'])."', ".	
				" customers_email_address = '".escape_string($cusval['customers_email_address'])."', "." customers_telephone = '".escape_string($cusval['customers_telephone'])."', "." customers_fax = '".escape_string($cusval['customers_fax'])."', "." customers_password = '".escape_string($cusval['customers_password'])."', "." customers_newsletter = '".escape_string($cusval['customers_newsletter'])."', "." customers_selected_template = '".escape_string($cusval['customers_selected_template'])."', "." customers_group_id = '".escape_string($cusval['customers_group_id'])."', "." customers_group_ra = '".escape_string($cusval['customers_group_ra'])."', "." customers_payment_allowed = '".escape_string($cusval['customers_payment_allowed'])."', "." customers_validation_code = '".escape_string($cusval['customers_validation_code'])."', "." customers_validation = '".escape_string($cusval['customers_validation'])."', "." customers_email_registered = '".escape_string($cusval['customers_email_registered'])."', "." customers_access_group_id = '".escape_string($cusval['customers_access_group_id'])."', "." customers_account_approval = '".escape_string($cusval['customers_account_approval'])."', "." from_macola = '".escape_string($cusval['from_macola'])."', "." customers_orders_count = '".escape_string($cusval['customers_orders_count'])."', "." sales_consultant = '".escape_string($cusval['sales_consultant'])."', "." sales_consultant_email = '".escape_string($cusval['sales_consultant_email'])."', "." customers_term = '".escape_string($cusval['customers_term'])."', "." accountant_name = '".escape_string($cusval['accountant_name'])."', "." accountant_email = '".escape_string($cusval['accountant_email'])."', "." submit_accountant_email_to_xero = '".escape_string($cusval['submit_accountant_email_to_xero'])."', "." send_feedback_email = '".escape_string($cusval['send_feedback_email'])."'";
				
				//echo $ins_cus_query;							
				echo "<br>";
				$new_customers_id = 18829;
				//$new_customers_id = 18829 . mysql_insert_id();
				$del_cus_query = "DELETE FROM customers WHERE customers_id = '".$new_customers_id."'";
				insert_query_log($ins_cus_query);delete_query_log($del_cus_query);
								
			}
			
			if($customer_id_entry==$arrkey) { 				
				$address_book_id_entry_string = " customers_id = '".$new_customers_id."', ";
				$products_users_id = $new_customers_id;
			} else { 
				$address_book_id_entry_string = " customers_id='".$arrkey."', "; 
				$products_users_id = $arrkey;
			}
			
			
			foreach($arrval['accounts'] as $custkey=>$cusval) {				
				
				echo "<br><br>";
				
				$crm_acc_num_of_row = check_crm_acc_row($cusval["id"],$link_crm);
				
				if($crm_acc_num_of_row==0) {
					$accid = $cusval["id"];
				} else {
					$accid = GUID();
				}			
				
				$ins_crm_acc_qry = "INSERT INTO accounts SET id='".$accid."', name='".$cusval["name"]."', date_entered='".$cusval["date_entered"]."', date_modified='".$cusval["date_modified"]."', modified_user_id='".$cusval["modified_user_id"]."', created_by='".$cusval["created_by"]."', description='".$cusval["description"]."', deleted='".$cusval["deleted"]."', assigned_user_id='".$cusval["assigned_user_id"]."', account_type='".$cusval["account_type"]."', industry='".$cusval["industry"]."', annual_revenue='".$cusval["annual_revenue"]."', phone_fax='".$cusval["phone_fax"]."', billing_address_street='".$cusval["billing_address_street"]."', billing_address_city='".$cusval["billing_address_city"]."', billing_address_state='".$cusval["billing_address_state"]."', billing_address_postalcode='".$cusval["billing_address_postalcode"]."', billing_address_country='".$cusval["billing_address_country"]."', rating='".$cusval["rating"]."', phone_office='".$cusval["phone_office"]."', phone_alternate='".$cusval["phone_alternate"]."', website='".$cusval["website"]."', ownership='".$cusval["ownership"]."', employees='".$cusval["employees"]."', ticker_symbol='".$cusval["ticker_symbol"]."', shipping_address_street='".$cusval["shipping_address_street"]."', shipping_address_city='".$cusval["shipping_address_city"]."', shipping_address_state='".$cusval["shipping_address_state"]."', shipping_address_postalcode='".$cusval["shipping_address_postalcode"]."', shipping_address_country='".$cusval["shipping_address_country"]."',parent_id='".$cusval["parent_id"]."', sic_code='".$cusval["sic_code"]."',campaign_id='".$cusval["campaign_id"]."', is_delete='".$cusval["is_delete"]."',imported_from='".$cusval["imported_from"]."', lead_id_ref='".$cusval["lead_id_ref"]."',from_history='".$cusval["from_history"]."', free_sample='".$cusval["free_sample"]."',sample_product_name='".$cusval["sample_product_name"]."', sample_product_sent_date='".$cusval["sample_product_sent_date"]."',sample_user_comment='".$cusval["sample_user_comment"]."'";
				
				$del_crm_acc_qry = "DELETE FROM accounts WHERE id = '".$accid."'";
				insert_query_log($ins_crm_acc_qry);delete_query_log($del_crm_acc_qry);
				
				echo $ins_crm_acc_qry;
				echo "<br><br>";
				
				$ins_crm_acc_cstm_qry = "INSERT INTO accounts_cstm SET id_c='".$accid."', customer_no_c='".$cusval["customer_no_c"]."', user_password_c='".$cusval["user_password_c"]."', customer_id_c='".$cusval["customer_id_c"]."', cr_lmt_c='".$cusval["cr_lmt_c"]."', sls_ptd_c='".$cusval["sls_ptd_c"]."', sls_ytd_c='".$cusval["sls_ytd_c"]."', sls_last_yr_c='".$cusval["sls_last_yr_c"]."', cost_ptd_c='".$cusval["cost_ptd_c"]."', cost_ytd_c='".$cusval["cost_ytd_c"]."', cost_last_yr_c='".$cusval["cost_last_yr_c"]."', balance_c='".$cusval["balance_c"]."', last_sale_amt_c='".$cusval["last_sale_amt_c"]."', last_pay_dt_c='".$cusval["last_pay_dt_c"]."', last_pay_amt_c='".$cusval["last_pay_amt_c"]."', email_addr_c='".$cusval["email_addr_c"]."', imported_from_c='".$cusval["imported_from_c"]."', macola_ref_no_c='".$cusval["macola_ref_no_c"]."', account_created_cre_c='".$cusval["account_created_cre_c"]."', last_sale_dt_c='".$cusval["last_sale_dt_c"]."', contact_name_c='".$cusval["contact_name_c"]."', contact_id_c='".$cusval["contact_id_c"]."', sls_ptd_macola_c='".$cusval["sls_ptd_macola_c"]."', sls_ytd_macola_c='".$cusval["sls_ytd_macola_c"]."', sls_last_yr_macola_c='".$cusval["sls_last_yr_macola_c"]."', last_sale_dt_macola_c='".$cusval["last_sale_dt_macola_c"]."', last_sale_amt_macola_c='".$cusval["last_sale_amt_macola_c"]."', act_group_c='".$cusval["act_group_c"]."', origin='".$cusval["origin"]."', term_c='".$cusval["term_c"]."',is_crdit_on_hold_c='".$cusval["is_crdit_on_hold_c"]."', is_visible_credit_mgmt_c='".$cusval["is_visible_credit_mgmt_c"]."',is_visible_macola_finance_c='".$cusval["is_visible_macola_finance_c"]."', deleted_new='".$cusval["deleted_new"]."',display_from_macola_order_c='".$cusval["display_from_macola_order_c"]."', gender_c='".$cusval["gender_c"]."'";
				
				$del_crm_acc_cstm_qry = "DELETE FROM accounts_cstm WHERE id_c = '".$accid."'";
				insert_query_log($ins_crm_acc_cstm_qry);delete_query_log($del_crm_acc_cstm_qry);
				
				echo $ins_crm_acc_cstm_qry;
				echo "<br><br>";
				
				foreach($cusval['email_addr_bean_rel'] as $eabrke=>$eabrva) {
					$crm_eabr_num_of_row = check_crm_eabr_row($eabrva["eabrid"],$link_crm);				
					if($crm_eabr_num_of_row==0) {
						$eabrid = $eabrva["eabrid"];
					} else {
						$eabrid = GUID();
					}		
					echo "<br>";					
					$ins_crm_eabr_qry = "INSERT INTO email_addr_bean_rel SET id='".$eabrid."', email_address_id='".$eabrva["email_address_id"]."', bean_id='".$accid."', bean_module='".escape_string($eabrva["bean_module"])."', primary_address='".$eabrva["primary_address"]."', reply_to_address='".$eabrva["reply_to_address"]."', date_created='".escape_string($eabrva["date_created"])."', date_modified='".escape_string($eabrva["date_modified"])."', deleted='".$eabrva["deleted"]."', contact_id='".$eabrva["contact_id"]."'";
					$del_crm_eabr_qry = "DELETE FROM email_addr_bean_rel WHERE id = '".$eabrid."'";
					insert_query_log($ins_crm_eabr_qry);delete_query_log($del_crm_eabr_qry);
					
					echo $ins_crm_eabr_qry;
					echo "<br><br>";
					
					$eaaddress = escape_string($eabrva["email_address"]);
					
					echo $crm_ea_num_of_row = check_crm_ea_row($eabrva["eaid"],$eaaddress,$link_crm);				
					echo "<br>";
					if($crm_ea_num_of_row==0) {
						
						$eaid = $eabrva["eaid"];
						
						echo "<br><br>";
						$ins_crm_ea_qry = "INSERT INTO email_addresses SET id='".$eaid."', email_address='".escape_string($eabrva["email_address"])."', email_address_caps='".escape_string($eabrva["email_address_caps"])."', invalid_email='".escape_string($eabrva["invalid_email"])."', opt_out='".escape_string($eabrva["opt_out"])."', date_created='".escape_string($eabrva["date_created"])."', date_modified='".escape_string($eabrva["date_modified"])."', deleted='".$eabrva["deleted"]."', delete_now='".$eabrva["delete_now"]."'";
						$del_crm_ea_qry = "DELETE FROM email_addresses WHERE id = '".$eaid."'";
						insert_query_log($ins_crm_ea_qry);delete_query_log($del_crm_ea_qry);
						
						echo $ins_crm_ea_qry;
						echo "<br>";
					
					} 
					
				
				}
				
				print_r($cusval['contacts']);
												
				
			}
			
			
			foreach($arrval['address_book'] as $custkey=>$cusval) {
				
				//print_r($cusval);echo "<br>";
				
				$ins_addr_query = "INSERT INTO address_book SET ". $address_book_id_entry_string . 
				" entry_gender = '".escape_string($cusval['entry_gender'])."', "." entry_company = '".escape_string($cusval['entry_company'])."', ".
				" entry_company_tax_id = '".escape_string($cusval['entry_company_tax_id'])."', "." entry_firstname = '".escape_string($cusval['entry_firstname'])."', ".	
				" entry_lastname = '".escape_string($cusval[entry_lastname])."', "." entry_street_address = '".escape_string($cusval[entry_street_address])."', "." entry_suburb = '".escape_string($cusval[entry_suburb])."', "." entry_postcode = '".escape_string($cusval[entry_postcode])."', "." entry_city = '".escape_string($cusval[entry_city])."', "." entry_state = '".escape_string($cusval[entry_state])."', "." entry_country_id = '".escape_string($cusval[entry_country_id])."', "." entry_zone_id = '".escape_string($cusval[entry_zone_id])."', "." entry_telephone = '".escape_string($cusval[entry_telephone])."', "." entry_fax = '".escape_string($cusval[entry_fax])."', "." entry_email_address = '".escape_string($cusval[entry_email_address])."', "." crm_import = '".escape_string($cusval[crm_import])."', "." from_macola = '".escape_string($cusval[from_macola])."'";
				
				//echo $ins_addr_query;						
				echo "<br>";
				//$new_customers_address_id = mysql_insert_id();
				$del_addr_query = "DELETE FROM address_book WHERE address_book_id = '".$new_customers_address_id."'";
				insert_query_log($ins_addr_query);delete_query_log($del_addr_query);
												
			}
			
			if($customer_id_entry==$arrkey) { 				
				$customers_info_id_entry_string = " customers_info_id = '".$new_customers_id."', ";
			} else { 
				$customers_info_id_entry_string = " customers_info_id='".$arrkey."', "; 
			}
			
			foreach($arrval['customers_info'] as $custkey=>$cusval) {
				
				//print_r($cusval);echo "<br>";
				
				$ins_ci_query = "INSERT INTO customers_info SET " . $customers_info_id_entry_string . 
				" customers_info_date_of_last_logon = '".escape_string($cusval[customers_info_date_of_last_logon])."', "." customers_info_number_of_logons = '".escape_string($cusval[customers_info_number_of_logons])."', ".
				" customers_info_date_account_created = '".escape_string($cusval[customers_info_date_account_created])."', "." customers_info_date_account_last_modified = '".escape_string($cusval[customers_info_date_account_last_modified])."', ".	
				" global_product_notifications = '".escape_string($cusval[global_product_notifications])."'";
				
				//echo $ins_ci_query;
				echo "<br>";
				//$new_ci_id = mysql_insert_id();
				$del_ci_query = "DELETE FROM customers_info WHERE customers_info_id = '".$new_ci_id."'";
				insert_query_log($ins_ci_query);delete_query_log($del_ci_query);
												
			}
			
			if($customer_id_entry==$arrkey) { 					
				$my_files_customer_id_entry_string = " customers_id = '".$new_customers_id."', ";
			} else { 
				$my_files_customer_id_entry_string = " customers_id='".$arrkey."', "; 
			}
			
			foreach($arrval['my_files'] as $custkey=>$cusval) {								
				//print_r($cusval);echo "<br>";	
				
				$m_filepath = explode('/',escape_string($cusval[file_path]),5);
				//print_r($m_filepath);
				
				if(!empty($m_filepath[4])) { 
					echo $m_srcpath = "http://ajparkes.com.au/".$m_filepath[4];
					$m_target = $_SERVER['DOCUMENT_ROOT']."/".escape_string($m_filepath[4]);
					if(copy($m_srcpath,$m_target)) { log_message($m_srcpath . " My File move success."); file_link_log($m_target); } else { log_message($m_srcpath . " My File move failed."); }
				}
				
				$ins_mf_query = "INSERT INTO my_files SET ".$my_files_customer_id_entry_string. 
				" file_name = '".escape_string($cusval[file_name])."', "." file_path = '".$m_target."', ".
				" date_uploaded = '".escape_string($cusval[date_uploaded])."', "." comment = '".escape_string($cusval[comment])."'";
				//echo $ins_mf_query;
				//echo "<br>";
				//$new_mf_id = mysql_insert_id();
				$del_mf_query = "DELETE FROM my_files WHERE files_id = '".$new_mf_id."'";
				insert_query_log($ins_mf_query);delete_query_log($del_mf_query);
												
			}
			
			if($customer_id_entry==$arrkey) { 					
				$artwork_customer_id_entry_string = " customers_id = '".$new_customers_id."', ";
			} else { 
				$artwork_customer_id_entry_string = " customers_id='".$arrkey."', "; 
			}
			
			//Artwork files
			foreach($arrval['artwork'] as $custkey=>$cusval) {								
				
				//echo $cusval[artwork_id];
				
				$artwork_id_entry = check_cre_artwork_id($cusval[artwork_id]);				
				
				if($artwork_id_entry==$cusval[artwork_id]) { 					
					$artwork_id_entry_string = " ";
				} else { 
					$artwork_id_entry_string = " artwork_id='".$cusval[artwork_id]."', "; 
				}
				
				
				$ins_aw1_query = "INSERT INTO artwork SET ".$artwork_customer_id_entry_string. $artwork_id_entry_string .
				" orders_id = '".escape_string($cusval[orders_id])."', "." products_id = '".escape_string($cusval[products_id])."', ".
				" creative_brief = '".escape_string($cusval[creative_brief])."', "." sales_consultant = '".escape_string($cusval[sales_consultant])."', ".
				" designer = '".escape_string($cusval[designer])."', "." designer_id = '".escape_string($cusval[designer_id])."', ".
				" notify_customer = '".escape_string($cusval[notify_customer])."', "." artwork_cc = '".escape_string($cusval[artwork_cc])."', ".
				" artwork_bcc = '".escape_string($cusval[artwork_bcc])."', "." linked_to_order = '".escape_string($cusval[linked_to_order])."', ".
				" artwork_status = '".escape_string($cusval[artwork_status])."', "." date_created = '".escape_string($cusval[date_created])."'";
				
				//echo $ins_aw1_query;
				$new_artwork_id = 12434;
				
				if($artwork_id_entry==$cusval[artwork_id]) { 					
					$artwork_id_entry_string = " artwork_id = '".$new_artwork_id."', ";
				} else { 
					$artwork_id_entry_string = " artwork_id='".$cusval[artwork_id]."', "; 
				}
				
				//echo "<br>";
				
				foreach($cusval['options'] as $aoke=>$aova) {
					
					$artwork_option_id_entry = check_cre_artwork_option_id($aova[artwork_option_id]);				
				
					if($artwork_option_id_entry==$aova[artwork_option_id]) { 					
						$artwork_option_id_entry = " ";
					} else { 
						$artwork_option_id_entry = " artwork_option_id='".$aova[artwork_option_id]."', "; 
					}
					
					$src_image = 'http://ajparkes.com.au/'.escape_string($aova[option_image]);
					$target_image = $_SERVER['DOCUMENT_ROOT']."/".escape_string($aova[option_image]);	
					if(copy($src_image,$target_image)) { log_message($src_image . " My artwork file move success."); file_link_log($target_image); } else { log_message($src_image . " My artwork file move failed."); }
					
					$ins_awo1_query = "INSERT INTO artwork_option SET ".$artwork_option_id_entry. $artwork_id_entry_string .
					" revision_id = '".escape_string($aova[revision_id])."', "." option_name = '".escape_string($aova[option_name])."', ".
					" option_image = '".escape_string($aova[option_image])."', "." option_approve = '".escape_string($aova[option_approve])."'";
					
					if($artwork_option_id_entry==$aova[artwork_option_id]) { 					
						$artwork_option_id = mysql_insert_id();
						$artwork_option_id_entry = " artwork_option_id='".$artwork_option_id."', ";
					} else { 
						$artwork_option_id = $aova[artwork_option_id]; 
						$artwork_option_id_entry = " artwork_option_id='".$aova[artwork_option_id]."', ";
					}
					
					//echo $ins_awo1_query;
					//echo "<br>";
					
					if(!empty($aova[artwork_option_resolution_id])) {
						$ins_awor1_query = "INSERT INTO artwork_option_resolution SET artwork_option_id = ".$artwork_option_id .
						" resolution_image_path = '".escape_string($aova[resolution_image_path])."'";
						//echo $ins_awor1_query;
					}
					
					//echo "<br>";
					
					foreach($aova['feedback'] as $afke=>$afva) {
						
						$af_src_image = 'http://ajparkes.com.au/'.escape_string($afva[attachment]);
						$af_target_image = $_SERVER['DOCUMENT_ROOT']."/".escape_string($afva[attachment]);	
						if(copy($af_src_image,$af_target_image)) { log_message($af_src_image . " My artwork feedback file move success."); file_link_log($af_target_image); } else { log_message($af_src_image . " My artwork feedback file move failed."); }
							
						$ins_af1_query = "INSERT INTO artwork_feedback SET ".$artwork_option_id_entry. $artwork_id_entry_string .
						" feedback = '".escape_string($afva[feedback])."', "." attachment = '".escape_string($afva[attachment])."', ". " attachment_name = '".escape_string($afva[attachment_name])."', ". " user_type = '".escape_string($afva[user_type])."', ". " posted_by = '".escape_string($afva[posted_by])."', ". " status = '".escape_string($afva[status])."', "." posted_date = '".escape_string($afva[posted_date])."', "." notify_customer = '".escape_string($afva[notify_customer])."'";
						//print_r($afva);
						//echo $ins_af1_query;
						
						echo "<br>";
					}
					
				}
												
			}
			
			
			if($customer_id_entry==$arrkey) { 				
				$orders_customer_id_entry_string = " customers_id = '".$new_customers_id."', ";
			} else { 
				$orders_customer_id_entry_string = " customers_id='".$arrkey."', "; 
			}
			
			foreach($arrval['orders'] as $custkey=>$cusval) {								
				
				//print_r($cusval);echo "<br><br>";	
				$order_id_entry = check_cre_order_id($cusval[orders_id]);				
				
				if($order_id_entry==$cusval[orders_id]) { 					
					$order_id_entry_string = " ajp_orders_id = '".$cusval[orders_id]."', ";
				} else { 
					$order_id_entry_string = " orders_id='".$cusval[orders_id]."', ajp_orders_id = '".$cusval[orders_id]."', "; 
				}
			
				$ins_or_query = "INSERT INTO orders SET ". $order_id_entry_string . $orders_customer_id_entry_string .  
				" customers_name = '".escape_string($cusval[customers_name])."', "." customers_company = '".escape_string($cusval[customers_company])."', ".
				" customers_street_address = '".escape_string($cusval[customers_street_address])."', "." customers_suburb = '".escape_string($cusval[customers_suburb])."', ". " customers_city = '".escape_string($cusval[customers_city])."', "." customers_postcode = '".escape_string($cusval[customers_postcode])."', ". " customers_state = '".escape_string($cusval[customers_state])."', "." customers_country = '".escape_string($cusval[customers_country])."', ". " customers_telephone = '".escape_string($cusval[customers_telephone])."', "." customers_email_address = '".escape_string($cusval[customers_email_address])."', ". " customers_address_format_id = '".escape_string($cusval[customers_address_format_id])."', "." delivery_name = '".escape_string($cusval[delivery_name])."', ". " delivery_company = '".escape_string($cusval[delivery_company])."', "." delivery_street_address = '".escape_string($cusval[delivery_street_address])."', ". " delivery_suburb = '".escape_string($cusval[delivery_suburb])."', "." delivery_city = '".escape_string($cusval[delivery_city])."', ". " delivery_postcode = '".escape_string($cusval[delivery_postcode])."', "." delivery_state = '".escape_string($cusval[delivery_state])."', ". " delivery_country = '".escape_string($cusval[delivery_country])."', "." delivery_address_format_id = '".escape_string($cusval[delivery_address_format_id])."', ". " billing_name = '".escape_string($cusval[billing_name])."', "." billing_company = '".escape_string($cusval[billing_company])."', ". " billing_street_address = '".escape_string($cusval[billing_street_address])."', "." billing_suburb = '".escape_string($cusval[billing_suburb])."', ". " billing_city = '".escape_string($cusval[billing_city])."', "." billing_postcode = '".escape_string($cusval[billing_postcode])."', ". " billing_state = '".escape_string($cusval[billing_state])."', "." billing_country = '".escape_string($cusval[billing_country])."', ". " billing_address_format_id = '".escape_string($cusval[billing_address_format_id])."', "." payment_method = '".escape_string($cusval[payment_method])."', ". " payment_info = '".escape_string($cusval[payment_info])."', "." payment_id = '".escape_string($cusval[payment_id])."', ". " cc_type = '".escape_string($cusval[cc_type])."', "." cc_owner = '".escape_string($cusval[cc_owner])."', ". " cc_number = '".escape_string($cusval[cc_number])."', "." cc_ccv = '".escape_string($cusval[cc_ccv])."', ". " cc_expires = '".escape_string($cusval[cc_expires])."', "." cc_start = '".escape_string($cusval[cc_start])."', ". " cc_issue = '".escape_string($cusval[cc_issue])."', "." cc_bank_phone = '".escape_string($cusval[cc_bank_phone])."', ". " last_modified = '".escape_string($cusval[last_modified])."', "." date_purchased = '".escape_string($cusval[date_purchased])."', ". " orders_status = '".escape_string($cusval[orders_status])."', "." orders_date_finished = '".escape_string($cusval[orders_date_finished])."', ". " currency = '".escape_string($cusval[currency])."', "." currency_value = '".escape_string($cusval[currency_value])."', ". " account_name = '".escape_string($cusval[account_name])."', "." account_number = '".escape_string($cusval[account_number])."', ". " po_number = '".escape_string($cusval[po_number])."', "." purchased_without_account = '".escape_string($cusval[purchased_without_account])."', ". " paypal_ipn_id = '".escape_string($cusval[paypal_ipn_id])."', "." ipaddy = '".escape_string($cusval[ipaddy])."', "." ipisp = '".escape_string($cusval[ipisp])."', "." delivery_telephone = '".escape_string($cusval[delivery_telephone])."', "." delivery_fax = '".escape_string($cusval[delivery_fax])."', "." delivery_email_address = '".escape_string($cusval[delivery_email_address])."', "." billing_telephone = '".escape_string($cusval[billing_telephone])."', "." billing_fax = '".escape_string($cusval[billing_fax])."', ". " billing_email_address = '".escape_string($cusval[billing_email_address])."', ". " purchase_number = '".escape_string($cusval[purchase_number])."', ". " due_date = '".escape_string($cusval[due_date])."', ". " order_assigned_to = '".escape_string($cusval[order_assigned_to])."', "." order_assigned_to_email = '".escape_string($cusval[order_assigned_to_email])."', ". " order_display = '".escape_string($cusval[order_display])."', "." crm_order = '".escape_string($cusval[crm_order])."', ". " xero = '".escape_string($cusval[xero])."', ". " is_paid = '".escape_string($cusval[is_paid])."'";								
				
				//echo $ins_or_query."<br><br>";
				$new_orders_id = 23244;
				
				if($order_id_entry==$cusval[orders_id]) { 					
					$order_id_entry_string = " orders_id = '".$new_orders_id."', ";
				} else { 
					$order_id_entry_string = " orders_id='".$cusval[orders_id]."', "; 
				}
				
				
				foreach($cusval['products'] as $pke=>$pva) {
					
					if($cusval[orders_id] == $pva[orders_id]) {
						
						//echo "<br><br>";
						
						//echo "<img src='http://ajparkes.com.au/images/".escape_string($pva[products_image])."' alt='' />";
						
						$src_image = 'http://ajparkes.com.au/images/'.escape_string($pva[products_image]);
						$target_image = $_SERVER['DOCUMENT_ROOT']."/images/".escape_string($pva[products_image]);						
						
						if(copy($src_image,$target_image)) { log_message($src_image . " Product image move success."); file_link_log($target_image); } else { log_message($src_image . " Product image move failed."); }
						
						if ($pva['badge_data']) {
							  
							  require_once('./templates/nbi_au/bd/badge_desc.php');
							  $badge = new Badge(escape_string($pva['badge_data']));
							  //Move logo of the badges if avail
							  foreach($badge->data->logos as $imgkey=>$imgval) {
								
								$badge_img_src = 'http://ajparkes.com.au/templates/Ajparkes1/bd/_tmp/'.$imgval->src;
								$badge_img_target = $_SERVER["DOCUMENT_ROOT"]."/templates/Ajparkes1/bd/_tmp/".$imgval->src;
								if(copy($badge_img_src,$badge_img_target)) { log_message($badge_img_src . " Badge logo image move success."); file_link_log($badge_img_target); } else { log_message($badge_img_src . " Product image move failed."); }
								
								print_r($imgval->src);
								echo "<br>";
							  }
							  //echo "<br><br>";
							  //Move name list of the badges if avail							  
							  foreach($badge->data->multiName as $filekey=>$fileval) {
								
								$badge_file_src = 'http://ajparkes.com.au/templates/Ajparkes1/bd/_tmp/'.$fileval->src;
								$badge_file_target = $_SERVER["DOCUMENT_ROOT"]."/templates/nbi_au/bd/_tmp/".$fileval->src;
								if(copy($badge_file_src,$badge_file_target)) { log_message($badge_file_src . " Badge logo image move success."); file_link_log($badge_file_target); } else { log_message($badge_file_src . " Product image move failed."); }
								
								print_r($fileval);
								echo "<br>";
							  }
							  echo "<br><br>";
							 						   
						}
						
						$product_id_entry = check_cre_product_id($pva[products_id]);
						if($product_id_entry==$pva[products_id]) {
							$product_id_entry_string = " ajp_products_id = '".$pva[products_id]."', "; 
						} else { 
							$product_id_entry_string = " products_id='".$pva[products_id]."', ajp_products_id = '".$pva[products_id]."', "; 
						}
												
						$ins_p_query = "INSERT INTO products SET ". $product_id_entry_string ." products_quantity = '".escape_string($pva[products_quantity])."', ".
						" products_model = '".escape_string($pva[products_model])."', ". " products_image = '".escape_string($pva[products_image])."', "." products_image_med = '".escape_string($pva[products_image_med])."', ". " products_image_lrg = '".escape_string($pva[products_image_lrg])."', ". " products_image_sm_1 = '".escape_string($pva[products_image_sm_1])."', ". 
						" products_image_xl_1 = '".escape_string($pva[products_image_xl_1])."', ". " products_image_sm_2 = '".escape_string($pva[products_image_sm_2])."', ". " products_image_xl_2 = '".escape_string($pva[products_image_xl_2])."', ". " products_image_sm_3 = '".escape_string($pva[products_image_sm_3])."', ". " products_image_xl_3 = '".escape_string($pva[products_image_xl_3])."', ". 
						" products_image_sm_4 = '".escape_string($pva[products_image_sm_4])."', ". " products_image_xl_4 = '".escape_string($pva[products_image_xl_4])."', ". " products_image_sm_5 = '".escape_string($pva[products_image_sm_5])."',". " products_image_xl_5 = '".escape_string($pva[products_image_xl_5])."', "." products_image_sm_6 = '".escape_string($pva[products_image_sm_6])."', ". " products_image_xl_6 = '".escape_string($pva[products_image_xl_6])."', "." products_price = '".escape_string($pva[products_price])."', ". " products_date_added = '".escape_string($pva[products_date_added])."', ". " products_last_modified = '".escape_string($pva[products_last_modified])."', ". " products_date_available = '".escape_string($pva[products_date_available])."', ". 
						" products_weight = '".escape_string($pva[products_weight])."', ". " products_status = '".escape_string($pva[products_status])."', ". " products_tax_class_id = '".escape_string($pva[products_tax_class_id])."', ". " manufacturers_id = '".escape_string($pva[manufacturers_id])."', ". " products_ordered = '".escape_string($pva[products_ordered])."', ". 
						" products_parent_id = '".escape_string($pva[products_parent_id])."', ". " products_price1 = '".escape_string($pva[products_price1])."', ". " products_price2 = '".escape_string($pva[products_price2])."', "." products_price3 = '".escape_string($pva[products_price3])."', ". " products_price4 = '".escape_string($pva[products_price4])."', "." products_price5 = '".escape_string($pva[products_price5])."', ". " products_price6 = '".escape_string($pva[products_price6])."', ". " products_price7 = '".escape_string($pva[products_price7])."', ". " products_price8 = '".escape_string($pva[products_price8])."', ". " products_price9 = '".escape_string($pva[products_price9])."', ". " products_price10 = '".escape_string($pva[products_price10])."', ". " products_price11 = '".escape_string($pva[products_price11])."', ". " products_price1_qty = '".escape_string($pva[products_price1_qty])."', ". 
						" products_price2_qty = '".escape_string($pva[products_price2_qty])."', ". " products_price3_qty = '".escape_string($pva[products_price3_qty])."', ". " products_price4_qty = '".escape_string($pva[products_price4_qty])."',". " products_price5_qty = '".escape_string($pva[products_price5_qty])."', "." products_price6_qty = '".escape_string($pva[products_price6_qty])."', ". " products_price7_qty = '".escape_string($pva[products_price7_qty])."', "." products_price8_qty = '".escape_string($pva[products_price8_qty])."', ". " products_price9_qty = '".escape_string($pva[products_price9_qty])."', ". " products_price10_qty = '".escape_string($pva[products_price10_qty])."', ". " products_price11_qty = '".escape_string($pva[products_price11_qty])."', ". " products_qty_blocks = '".escape_string($pva[products_qty_blocks])."', ". " user_id = '".escape_string($products_users_id)."', ". " badge_namefile = '".escape_string($pva[badge_namefile])."', ". " badge_logo = '".escape_string($pva[badge_logo])."', ". " badge_data = '".escape_string($pva[badge_data])."', ". " old_import_design = '".escape_string($pva[old_import_design])."', ". " import_design = '".escape_string($pva[import_design])."', ". " import_create = '".escape_string($pva[import_create])."', ".
						" default_product_id = '".escape_string($pva[default_product_id])."', ". " max_lines_count = '".escape_string($pva[max_lines_count])."', "." max_images_count = '".escape_string($pva[max_images_count])."', ". " products_image_rls2 = '".escape_string($pva[products_image_rls2])."', ". " default_texts = '".escape_string($pva[default_texts])."', ". " products_group_access = '".escape_string($pva[products_group_access])."', ". " products_nav_access = '".escape_string($pva[products_nav_access])."', ". " sort_order = '".escape_string($pva[sort_order])."', ". " vendors_id = '".escape_string($pva[vendors_id])."', ". " vendors_product_price = '".escape_string($pva[vendors_product_price])."', ". " vendors_prod_id = '".escape_string($pva[vendors_prod_id])."', ". " vendors_prod_comments = '".escape_string($pva[vendors_prod_comments])."', ". 
						" products_qty_days = '".escape_string($pva[products_qty_days])."',". " products_qty_years = '".escape_string($pva[products_qty_years])."', "." products_purchase_number = '".escape_string($pva[products_purchase_number])."', ". " products_text = '".escape_string($pva[products_text])."', "." products_min_order_qty = '".escape_string($pva[products_min_order_qty])."', ". " badge_comment = '".escape_string($pva[badge_comment])."', "." labour_cost = '".escape_string($pva[labour_cost])."', ". 
						" material_cost = '".escape_string($pva[material_cost])."', ". " overhead_cost = '".escape_string($pva[overhead_cost])."', ". " from_crm = '".escape_string($pva[from_crm])."', ". " crm_insert_date = '".escape_string($pva[crm_insert_date])."', ". 
						" overall_quantity = '".escape_string($pva[overall_quantity])."', ". " progressed_quantity = '".escape_string($pva[progressed_quantity])."', ". " products_type = '".escape_string($pva[products_type])."', ". " show_in_search = '".escape_string($pva[show_in_search])."'";
						
						//echo $ins_p_query."<br>";
						
						$new_products_id = 23232323;
						
						//echo "<br><br>";
						if($product_id_entry==$pva[products_id]) {
							$product_desc_id_entry_string = " products_id = '".$new_products_id."', "; 
							$final_products_id = $new_products_id;
						} else { 
							$product_desc_id_entry_string = " products_id='".$pva[products_id]."', "; 
							$final_products_id = $pva[products_id];
						}
						
						$ins_pd_query = "INSERT INTO products_description SET ". $product_desc_id_entry_string . " language_id = '".escape_string($pva[language_id])."', "." products_name = '".escape_string($pva[products_name])."', ". " products_description = '".escape_string($pva[products_description])."', "." products_url = '".escape_string($pva[products_url])."', ". " products_viewed = '".escape_string($pva[products_viewed])."', ". " products_head_title_tag = '".escape_string($pva[products_head_title_tag])."', ". " products_head_desc_tag = '".escape_string($pva[products_head_desc_tag])."', ". " products_head_keywords_tag = '".escape_string($pva[products_head_keywords_tag])."', ". " products_blurb = '".escape_string($pva[products_blurb])."', ". " products_option_type = '".escape_string($pva[products_option_type])."', ". " products_option_values = '".escape_string($pva[products_option_values])."'";
						
						//echo $ins_pd_query;
						//echo "<br><br>";


						foreach($pva['products_badge_options'] as $pboke=>$pbova) {
							
							if($pva[products_id]==$pbova[products_id]) {
								//print_r($pbova);
								$ins_pbo_query = "INSERT INTO products_badge_options SET products_id='".$final_products_id."', options_id='".$pbova[options_id]."', options_type='".$pbova[options_type]."', options_values_id='".$pbova[options_values_id]."', options_text='".escape_string($pbova[options_text])."', options_desc='".escape_string($pbova[options_desc])."'";
							}
							
						}
						
						$ins_op_query = "INSERT INTO orders_products SET " . $order_id_entry_string . $product_desc_id_entry_string . " products_model = '".escape_string($pva[products_model])."', "." products_name = '".escape_string($pva[products_name])."', ". " products_description = '".escape_string($pva[products_description])."', "." products_price = '".escape_string($pva[products_price])."', ". " final_price = '".escape_string($pva[final_price])."', ". " products_tax = '".escape_string($pva[products_tax])."', ". " products_quantity = '".escape_string($pva[products_quantity])."', ". " products_returned = '".escape_string($pva[products_returned])."', ". " products_exchanged = '".escape_string($pva[products_exchanged])."', ". " products_exchanged_id = '".escape_string($pva[products_exchanged_id])."', ". " vendors_id = '".escape_string($pva[vendors_id])."', ". " products_purchase_number = '".escape_string($pva[products_purchase_number])."', ". " badge_comment = '".escape_string($pva[badge_comment])."', ". " crm_discount_percent = '".escape_string($pva[crm_discount_percent])."'";
						
						//echo $ins_op_query;
						$new_op_id = 234234234;
						//echo "<br><br>";	
						$ins_opc_query = "INSERT INTO orders_products_costs SET orders_products_id = '".$new_op_id."', " . $order_id_entry_string . " products_id = '".$new_products_id."', ". " products_quantity = '".$pva[products_quantity]."', "." labour_cost = '".$pva[labour_cost]."', ". " material_cost = '".$pva[material_cost]."', "." overhead_cost = '".$pva[overhead_cost]."'";
						//echo $ins_opc_query;
						//echo "<br><br>";
					}
	
				}
				
				foreach($cusval['orders_status_history'] as $oshke=>$oshva) {
					
					$ins_osh_query = "INSERT INTO orders_status_history SET " .$order_id_entry_string. " orders_status_id = '".$oshva[orders_status_id]."', ". " date_added = '".escape_string($oshva[date_added])."', "." customer_notified = '".$oshva[customer_notified]."', ". " comments = '".escape_string($oshva[comments])."', "." admin_users_id = '".$oshva[admin_users_id]."', "." crm_username = '".$oshva[crm_username]."'";

					//echo $ins_osh_query;
					echo "<br>";
					
				}
				
				foreach($cusval['orders_total'] as $otke=>$otva) {
					
					$ins_ot_query = "INSERT INTO orders_total SET " .$order_id_entry_string. " title = '".escape_string($otva[title])."', ". " text = '".escape_string($otva[text])."', "." value = '".$otva[value]."', ". " class = '".$otva['class']."', "." sort_order = '".$otva[sort_order]."'";
					//echo $ins_ot_query;
					//echo "<br>";
						
					
				}
				
				foreach($cusval['xeros'] as $xeke=>$xeva) {
					
					$ins_xe_query = "INSERT INTO xero SET " .$order_id_entry_string. " xero_request = '".escape_string($xeva[xero_request])."', ". " xero_response = '".escape_string($xeva[xero_response])."', "." date_added = '".escape_string($xeva[date_added])."'";
					//echo $ins_xe_query;
					//echo "<br>";
										
				}
				
				foreach($cusval['eparcel'] as $epke=>$epva) {
					
					if($cusval[orders_id] == $epva[orders_id]) {
						
						$ins_ec_query = "INSERT INTO eparcel_consignment SET " .$order_id_entry_string. " consignment_id = '".escape_string($epva[consignment_id])."', ". " consignment_number = '".escape_string($epva[consignment_number])."', "." manifest_id = '".escape_string($epva[manifest_id])."', "." date_created = '".escape_string($epva[date_created])."', "." charge_code = '".escape_string($epva[charge_code])."', "." email_notification = '".escape_string($epva[email_notification])."', "." international_delivery = '".escape_string($epva[international_delivery])."', "." signature_required = '".escape_string($epva[signature_required])."', "." post_charge_to_account = '".escape_string($epva[post_charge_to_account])."', "." dangerous_goods = '".escape_string($epva[dangerous_goods])."', "." profile_id = '".escape_string($epva[profile_id])."', "." product_code = '".escape_string($epva[product_code])."', "." delivery_part_consignment = '".escape_string($epva[delivery_part_consignment])."', "." number_of_articles = '".escape_string($epva[number_of_articles])."', "." multipart_consignment = '".escape_string($epva[multipart_consignment])."', "." status = '".escape_string($epva[status])."'";
						
						//echo $ins_ec_query;
						echo "<br><br>";	
						
						$ins_ea_query = "INSERT INTO eparcel_article SET article_id = '".escape_string($epva[article_id])."', consignment_id = '".escape_string($epva[consignment_id])."', ". " article_number = '".escape_string($epva[article_number])."', "." barcode_number = '".escape_string($epva[barcode_number])."', "." length = '".escape_string($epva[length])."', "." width = '".escape_string($epva[width])."', "." height = '".escape_string($epva[height])."', "." actual_weight = '".escape_string($epva[actual_weight])."', "." cubic_weight = '".escape_string($epva[cubic_weight])."', "." article_description = '".escape_string($epva[article_description])."', "." transit_cover = '".escape_string($epva[transit_cover])."', "." transit_cover_amount = '".escape_string($epva[transit_cover_amount])."'";
						
						//echo $ins_ea_query;
						echo "<br><br>";
						
						$ins_em_query = "INSERT INTO eparcel_manifest SET article_id = '".escape_string($epva[article_id])."', consignment_id = '".escape_string($epva[consignment_id])."', ". " article_number = '".escape_string($epva[article_number])."', "." barcode_number = '".escape_string($epva[barcode_number])."', "." length = '".escape_string($epva[length])."', "." width = '".escape_string($epva[width])."', "." height = '".escape_string($epva[height])."', "." actual_weight = '".escape_string($epva[actual_weight])."', "." cubic_weight = '".escape_string($epva[cubic_weight])."', "." article_description = '".escape_string($epva[article_description])."', "." transit_cover = '".escape_string($epva[transit_cover])."', "." transit_cover_amount = '".escape_string($epva[transit_cover_amount])."'";
						echo $ins_em_query;
						echo "<br><br>";						
					}
				}
				
				echo "<br>";
												
			}
			
			*/
			
		}


	
 // }
 
 function escape_string($str) {
	return trim(urldecode(htmlspecialchars_decode(mysql_real_escape_string($str))));
 }
 
function log_message($msg) {
	$fp = fopen('data.txt', 'a');					
	$string = $msg . "\r\n". "\r\n";					
	fwrite($fp, $string);					
	fclose($fp);
}

 function insert_query_log($query) {
	$fp = fopen('insert_data.txt', 'a');					
	$string = $query . "\r\n". "\r\n";					
	fwrite($fp, $string);					
	fclose($fp);
}

function delete_query_log($query) {
	$fp = fopen('delete_data.txt', 'a');					
	$string = $query . "\r\n". "\r\n";					
	fwrite($fp, $string);					
	fclose($fp);
}

function file_link_log($links) {
	$fp = fopen('file_link_data.txt', 'a');					
	$string = $links . "\r\n". "\r\n";					
	fwrite($fp, $string);					
	fclose($fp);
}

 function check_user_id($uid,$link_crm) {
	$qry = mysql_query("SELECT * FROM users WHERE id = '".$uid."'",$link_crm);
	return mysql_num_rows($qry);
 }
 
  function check_admin_email($admemail) {
	$qry = tep_db_query("SELECT admin_email_address FROM admin WHERE admin_email_address = '".$admemail."'");
	return mysql_num_rows($qry);
 }
 
 function check_cre_artwork_id($artworkid) {
	$qry = tep_db_query("SELECT artwork_id FROM artwork WHERE artwork_id = '".$artworkid."'");
	if(tep_db_num_rows($qry)>0) {
		$arr = tep_db_fetch_array($qry);		
		return $arr["artwork_id"];
	} else { return false; }
 }
 
  function check_cre_artwork_option_id($artwork_optionid) {
	$qry = tep_db_query("SELECT artwork_option_id FROM artwork_option WHERE artwork_option_id = '".$artwork_optionid."'");
	if(tep_db_num_rows($qry)>0) {
		$arr = tep_db_fetch_array($qry);		
		return $arr["artwork_option_id"];
	} else { return false; }
 }
 
  function check_cre_order_id($ordid) {
	$qry = tep_db_query("SELECT orders_id FROM orders WHERE orders_id = '".$ordid."'");
	if(tep_db_num_rows($qry)>0) {
		$arr = tep_db_fetch_array($qry);		
		return $arr["orders_id"];
	} else { return false; }
 }
 
 function check_cre_product_id($proid) {
	$qry = tep_db_query("SELECT products_id FROM products WHERE products_id = '".$proid."'");
	if(tep_db_num_rows($qry)>0) {
		$arr = tep_db_fetch_array($qry);		
		return $arr["products_id"];
	} else { return false; }
 }
 


function check_crm_acc_row($uniqueId,$link_crm) {
	$qry = mysql_query("SELECT id from accounts WHERE id='".$uniqueId."'", $link_crm);
	return mysql_num_rows($qry);
}
function check_crm_eabr_row($uniqueId,$link_crm) {
	$qry = mysql_query("SELECT id from email_addr_bean_rel WHERE id='".$uniqueId."'", $link_crm);
	return mysql_num_rows($qry);
}
function check_crm_ea_row($uniqueId,$email,$link_crm) {
	$qry = mysql_query("SELECT id from email_addresses WHERE id='".$uniqueId."' AND email_address='".$email."' and deleted=0", $link_crm);
	return mysql_num_rows($qry);
}


function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return strtolower(trim(com_create_guid(), '{}'));
    }

    return strtolower(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));
}

				
?>