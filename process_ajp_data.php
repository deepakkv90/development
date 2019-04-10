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
	
 // print_r($_POST);
  $data = array();
  $data = $_POST;
  
  //print_r($data);
  //exit;
  
  //if(isset($_POST["insert"])) {
			
		foreach($data as $arrkey=>$arrval) {							
			
			//echo $arrkey;
			
			echo $customer_id_entry = check_customer_id($arrkey);
				
			if($customer_id_entry==$arrkey) { 				
				$new_customers_id = next_auto_increment("customers");				
				$customer_id_entry_string = " customers_id='".$new_customers_id."', ajp_customers_id = '".$arrkey."', "; 
				
				$address_book_id_entry_string = " customers_id = '".$new_customers_id."', ";
				$products_users_id = $new_customers_id;
				
				$customers_info_id_entry_string = " customers_info_id = '".$new_customers_id."', ";
				$my_files_customer_id_entry_string = " customers_id = '".$new_customers_id."', ";
				$artwork_customer_id_entry_string = " customers_id = '".$new_customers_id."', ";
				$orders_customer_id_entry_string = " customers_id = '".$new_customers_id."', ";
				
				
			} else { 
				$customer_id_entry_string = " customers_id='".$arrkey."', ajp_customers_id = '".$arrkey."', "; 
				$address_book_id_entry_string = " customers_id='".$arrkey."', "; 
				$products_users_id = $arrkey;
				$customers_info_id_entry_string = " customers_info_id = '".$arrkey."', ";
				$my_files_customer_id_entry_string = " customers_id = '".$arrkey."', ";
				$artwork_customer_id_entry_string = " customers_id = '".$arrkey."', ";
				$orders_customer_id_entry_string = " customers_id = '".$arrkey."', ";
			}
			
			foreach($arrval["customers"] as $custkey=>$cusval) {
				
				echo "<br>";
				echo $ins_cus_query .= "INSERT INTO customers SET ".$customer_id_entry_string." purchased_without_account = '".escape_string($cusval['purchased_without_account'])."', ". 
				" customers_gender = '".escape_string($cusval['customers_gender'])."', "." customers_firstname = '".escape_string($cusval['customers_firstname'])."', ".
				" customers_lastname = '".escape_string($cusval['customers_lastname'])."', "." customers_dob = '".escape_string($cusval['customers_dob'])."', ".	
				" customers_email_address = '".escape_string($cusval['customers_email_address'])."', "." customers_telephone = '".escape_string($cusval['customers_telephone'])."', "." customers_fax = '".escape_string($cusval['customers_fax'])."', "." customers_password = '".escape_string($cusval['customers_password'])."', "." customers_newsletter = '".escape_string($cusval['customers_newsletter'])."', "." customers_selected_template = '".escape_string($cusval['customers_selected_template'])."', "." customers_group_id = '".escape_string($cusval['customers_group_id'])."', "." customers_group_ra = '".escape_string($cusval['customers_group_ra'])."', "." customers_payment_allowed = '".escape_string($cusval['customers_payment_allowed'])."', "." customers_validation_code = '".escape_string($cusval['customers_validation_code'])."', "." customers_validation = '".escape_string($cusval['customers_validation'])."', "." customers_email_registered = '".escape_string($cusval['customers_email_registered'])."', "." customers_access_group_id = '".escape_string($cusval['customers_access_group_id'])."', "." customers_account_approval = '".escape_string($cusval['customers_account_approval'])."', "." from_macola = '".escape_string($cusval['from_macola'])."', "." customers_orders_count = '".escape_string($cusval['customers_orders_count'])."', "." sales_consultant = '".escape_string($cusval['sales_consultant'])."', "." sales_consultant_email = '".escape_string($cusval['sales_consultant_email'])."', "." customers_term = '".escape_string($cusval['customers_term'])."', "." accountant_name = '".escape_string($cusval['accountant_name'])."', "." accountant_email = '".escape_string($cusval['accountant_email'])."', "." submit_accountant_email_to_xero = '".escape_string($cusval['submit_accountant_email_to_xero'])."', "." send_feedback_email = '".escape_string($cusval['send_feedback_email'])."'";
				
				//$ins_cus_arr = tep_db_query($ins_cus_query);
				//$new_customers_id = tep_db_insert_id();
				//$del_cus_query = "DELETE FROM customers WHERE customers_id = '".$new_customers_id."'";
				//insert_query_log($ins_cus_query);delete_query_log($del_cus_query);
								
			}
			
			foreach($arrval['accounts'] as $custkey=>$cusval) {				
								
				$accid = $cusval["id"];
				
				$ins_crm_acc_qry = "INSERT INTO accounts SET id='".$accid."', name='".escape_string($cusval["name"])."', date_entered='".escape_string($cusval["date_entered"])."', date_modified='".escape_string($cusval["date_modified"])."', modified_user_id='".$cusval["modified_user_id"]."', created_by='".$cusval["created_by"]."', description='".escape_string($cusval["description"])."', deleted='".$cusval["deleted"]."', assigned_user_id='".$cusval["assigned_user_id"]."', account_type='".escape_string($cusval["account_type"])."', industry='".escape_string($cusval["industry"])."', annual_revenue='".escape_string($cusval["annual_revenue"])."', phone_fax='".escape_string($cusval["phone_fax"])."', billing_address_street='".escape_string($cusval["billing_address_street"])."', billing_address_city='".escape_string($cusval["billing_address_city"])."', billing_address_state='".escape_string($cusval["billing_address_state"])."', billing_address_postalcode='".escape_string($cusval["billing_address_postalcode"])."', billing_address_country='".escape_string($cusval["billing_address_country"])."', rating='".escape_string($cusval["rating"])."', phone_office='".escape_string($cusval["phone_office"])."', phone_alternate='".escape_string($cusval["phone_alternate"])."', website='".escape_string($cusval["website"])."', ownership='".escape_string($cusval["ownership"])."', employees='".escape_string($cusval["employees"])."', ticker_symbol='".escape_string($cusval["ticker_symbol"])."', shipping_address_street='".escape_string($cusval["shipping_address_street"])."', shipping_address_city='".escape_string($cusval["shipping_address_city"])."', shipping_address_state='".escape_string($cusval["shipping_address_state"])."', shipping_address_postalcode='".escape_string($cusval["shipping_address_postalcode"])."', shipping_address_country='".escape_string($cusval["shipping_address_country"])."',parent_id='".escape_string($cusval["parent_id"])."', sic_code='".escape_string($cusval["sic_code"])."',campaign_id='".escape_string($cusval["campaign_id"])."', is_delete='".$cusval["is_delete"]."',imported_from='".escape_string($cusval["imported_from"])."', lead_id_ref='".escape_string($cusval["lead_id_ref"])."',from_history='".escape_string($cusval["from_history"])."', free_sample='".escape_string($cusval["free_sample"])."',sample_product_name='".escape_string($cusval["sample_product_name"])."', sample_product_sent_date='".escape_string($cusval["sample_product_sent_date"])."',sample_user_comment='".escape_string($cusval["sample_user_comment"])."'";
				
				//$ins_crm_acc_rst = mysql_query($ins_crm_acc_qry, $link_crm);
				//$del_crm_acc_qry = "DELETE FROM accounts WHERE id = '".$accid."'";
				//insert_query_log($ins_crm_acc_qry);delete_query_log($del_crm_acc_qry);
				
				//echo $ins_crm_acc_qry;
				//echo "<br><br>";
				
				$ins_crm_acc_cstm_qry = "INSERT INTO accounts_cstm SET id_c='".$accid."', customer_no_c='".escape_string($cusval["customer_no_c"])."', user_password_c='".escape_string($cusval["user_password_c"])."', customer_id_c='".escape_string($products_users_id)."', cr_lmt_c='".escape_string($cusval["cr_lmt_c"])."', sls_ptd_c='".escape_string($cusval["sls_ptd_c"])."', sls_ytd_c='".escape_string($cusval["sls_ytd_c"])."', sls_last_yr_c='".escape_string($cusval["sls_last_yr_c"])."', cost_ptd_c='".escape_string($cusval["cost_ptd_c"])."', cost_ytd_c='".escape_string($cusval["cost_ytd_c"])."', cost_last_yr_c='".escape_string($cusval["cost_last_yr_c"])."', balance_c='".escape_string($cusval["balance_c"])."', last_sale_amt_c='".escape_string($cusval["last_sale_amt_c"])."', last_pay_dt_c='".escape_string($cusval["last_pay_dt_c"])."', last_pay_amt_c='".escape_string($cusval["last_pay_amt_c"])."', email_addr_c='".escape_string($cusval["email_addr_c"])."', imported_from_c='".escape_string($cusval["imported_from_c"])."', macola_ref_no_c='".escape_string($cusval["macola_ref_no_c"])."', account_created_cre_c='".escape_string($cusval["account_created_cre_c"])."', last_sale_dt_c='".escape_string($cusval["last_sale_dt_c"])."', contact_name_c='".escape_string($cusval["contact_name_c"])."', contact_id_c='".escape_string($cusval["contact_id_c"])."', sls_ptd_macola_c='".escape_string($cusval["sls_ptd_macola_c"])."', sls_ytd_macola_c='".escape_string($cusval["sls_ytd_macola_c"])."', sls_last_yr_macola_c='".escape_string($cusval["sls_last_yr_macola_c"])."', last_sale_dt_macola_c='".escape_string($cusval["last_sale_dt_macola_c"])."', last_sale_amt_macola_c='".escape_string($cusval["last_sale_amt_macola_c"])."', act_group_c='".escape_string($cusval["act_group_c"])."', origin='".escape_string($cusval["origin"])."', term_c='".escape_string($cusval["term_c"])."',is_crdit_on_hold_c='".escape_string($cusval["is_crdit_on_hold_c"])."', is_visible_credit_mgmt_c='".$cusval["is_visible_credit_mgmt_c"]."',is_visible_macola_finance_c='".escape_string($cusval["is_visible_macola_finance_c"])."', deleted_new='".escape_string($cusval["deleted_new"])."',display_from_macola_order_c='".escape_string($cusval["display_from_macola_order_c"])."', gender_c='".escape_string($cusval["gender_c"])."'";
				
				//$ins_crm_acc_cstm_rst = mysql_query($ins_crm_acc_cstm_qry, $link_crm);
				//$del_crm_acc_cstm_qry = "DELETE FROM accounts_cstm WHERE id_c = '".$accid."'";
				//insert_query_log($ins_crm_acc_cstm_qry);delete_query_log($del_crm_acc_cstm_qry);
				
				//echo $ins_crm_acc_cstm_qry;
				//echo "<br><br>";
				
				foreach($cusval['email_addr_bean_rel'] as $eabrke=>$eabrva) {
					
					
					$eabrid = $eabrva["eabrid"];
					
					//echo "<br>";					
					$ins_crm_eabr_qry = "INSERT INTO email_addr_bean_rel SET id='".$eabrid."', email_address_id='".$eabrva["email_address_id"]."', bean_id='".$accid."', bean_module='".escape_string($eabrva["bean_module"])."', primary_address='".escape_string($eabrva["primary_address"])."', reply_to_address='".escape_string($eabrva["reply_to_address"])."', date_created='".escape_string($eabrva["date_created"])."', date_modified='".escape_string($eabrva["date_modified"])."', deleted='".$eabrva["deleted"]."', contact_id='".$eabrva["contact_id"]."'";
					//$ins_crm_eabr_rst = mysql_query($ins_crm_eabr_qry, $link_crm);
					//$del_crm_eabr_qry = "DELETE FROM email_addr_bean_rel WHERE id = '".$eabrid."'";
					//insert_query_log($ins_crm_eabr_qry);delete_query_log($del_crm_eabr_qry);
					
					//echo $ins_crm_eabr_qry;
					//echo "<br><br>";
					
					$eaaddress = escape_string($eabrva["email_address"]);
					
					$crm_ea_num_of_row = check_crm_ea_row($eabrva["eaid"],$eaaddress,$link_crm);				
					//echo "<br>";
					if($crm_ea_num_of_row==0) {
						
						$eaid = $eabrva["eaid"];
						
						//echo "<br><br>";
						$ins_crm_ea_qry = "INSERT INTO email_addresses SET id='".$eaid."', email_address='".escape_string($eabrva["email_address"])."', email_address_caps='".escape_string($eabrva["email_address_caps"])."', invalid_email='".escape_string($eabrva["invalid_email"])."', opt_out='".escape_string($eabrva["opt_out"])."', date_created='".escape_string($eabrva["date_created"])."', date_modified='".escape_string($eabrva["date_modified"])."', deleted='".$eabrva["deleted"]."', delete_now='".$eabrva["delete_now"]."'";
						
						//$ins_crm_ea_rst = mysql_query($ins_crm_ea_qry, $link_crm);
						//$del_crm_ea_qry = "DELETE FROM email_addresses WHERE id = '".$eaid."'";
						//insert_query_log($ins_crm_ea_qry);delete_query_log($del_crm_ea_qry);
						
						//echo $ins_crm_ea_qry;
						//echo "<br>";
					
					} 
					
				
				}
				
				foreach($cusval['contacts'] as $cke=>$cva) {
					
					//echo "<br/>";
					
					$ins_acc_con_qry = "INSERT INTO accounts_contacts SET id='".escape_string($cva["acid"])."', contact_id='".escape_string($cva["id"])."', account_id='".escape_string($cva["acaccountid"])."', date_modified='".escape_string($cva["acdate"])."', deleted='".escape_string($cva["acdelete"])."', is_delete='".escape_string($cva["acisdelete"])."'";
					//$ins_acc_con_rst = mysql_query($ins_acc_con_qry, $link_crm);
					//$del_crm_acc_con_qry = "DELETE FROM accounts_contacts WHERE id = '".escape_string($cva["acid"])."'";
					//insert_query_log($ins_acc_con_qry);delete_query_log($del_crm_acc_con_qry);
					
					$crm_ins_con_qry = "INSERT INTO contacts SET id='".escape_string($cva["id"])."', date_entered='".escape_string($cva["date_entered"])."', modified_user_id='".escape_string($cva["modified_user_id"])."', created_by='".escape_string($cva["created_by"])."', description='".escape_string($cva["description"])."', assigned_user_id='".escape_string($cva["assigned_user_id"])."', salutation='".escape_string($cva["salutation"])."', first_name='".escape_string($cva["first_name"])."', last_name='".escape_string($cva["last_name"])."', title='".escape_string($cva["title"])."', date_modified='".escape_string($cva["date_modified"])."', department='".escape_string($cva["department"])."', do_not_call='".escape_string($cva["do_not_call"])."', phone_home='".escape_string($cva["phone_home"])."', phone_mobile='".escape_string($cva["phone_mobile"])."', phone_work='".escape_string($cva["phone_work"])."', phone_other='".escape_string($cva["phone_other"])."', phone_fax='".escape_string($cva["phone_fax"])."', primary_address_street='".escape_string($cva["primary_address_street"])."', primary_address_city='".escape_string($cva["primary_address_city"])."', primary_address_state='".escape_string($cva["primary_address_state"])."', primary_address_postalcode='".escape_string($cva["primary_address_postalcode"])."', primary_address_country='".escape_string($cva["primary_address_country"])."', alt_address_street='".escape_string($cva["alt_address_street"])."', alt_address_city='".escape_string($cva["alt_address_city"])."', alt_address_state='".escape_string($cva["alt_address_state"])."', alt_address_postalcode='".escape_string($cva["alt_address_postalcode"])."', alt_address_country='".escape_string($cva["alt_address_country"])."', assistant='".escape_string($cva["assistant"])."', assistant_phone='".escape_string($cva["assistant_phone"])."', lead_source='".escape_string($cva["lead_source"])."', reports_to_id='".escape_string($cva["reports_to_id"])."', birthdate='".escape_string($cva["birthdate"])."', campaign_id='".escape_string($cva["campaign_id"])."', imported_from='".escape_string($cva["imported_from"])."', new_name='".escape_string($cva["new_name"])."', deleted='".escape_string($cva["deleted"])."', is_delete='".escape_string($cva["is_delete"])."'";
					//$crm_ins_con_rst = mysql_query($crm_ins_con_qry, $link_crm);
					//$crm_del_con_qry = "DELETE FROM contacts WHERE id = '".escape_string($cva["id"])."'";
					//insert_query_log($crm_ins_con_qry);delete_query_log($crm_del_con_qry);
					
					//echo "<br/>";
					
					$crm_ins_con_cstm_qry = "INSERT INTO contacts_cstm SET id_c='".escape_string($cva["id_c"])."', gender_c='".escape_string($cva["gender_c"])."'";
					//$crm_ins_con_cstm_rst = mysql_query($crm_ins_con_cstm_qry, $link_crm);
					//$crm_del_con_cstm_qry = "DELETE FROM contacts_cstm WHERE id = '".escape_string($cva["id_c"])."'";
					//insert_query_log($crm_ins_con_cstm_qry);delete_query_log($crm_del_con_cstm_qry);
					
				}
				
				foreach($cusval['quotes'] as $qke=>$qva) {
					
					//echo "<br/>";
					
					$crm_ins_quote_qry = "INSERT INTO quotes SET id='".escape_string($qva["id"])."', date_entered='".escape_string($qva["date_entered"])."', modified_user_id='".escape_string($qva["modified_user_id"])."', created_by='".escape_string($qva["created_by"])."', description='".escape_string($qva["description"])."', assigned_user_id='".escape_string($qva["assigned_user_id"])."', name='".escape_string($qva["name"])."', terms_c='".escape_string($qva["terms_c"])."', approval_issue='".escape_string($qva["approval_issue"])."', approval_status='".escape_string($qva["approval_status"])."', date_modified='".escape_string($qva["date_modified"])."', billing_account='".escape_string($qva["billing_account"])."', billing_account_id='".escape_string($qva["billing_account_id"])."', billing_address='".escape_string($qva["billing_address"])."', billing_city='".escape_string($qva["billing_city"])."', billing_contact='".escape_string($qva["billing_contact"])."', billing_contact_id='".escape_string($qva["billing_contact_id"])."', billing_country='".escape_string($qva["billing_country"])."', billing_postal='".escape_string($qva["billing_postal"])."', billing_state='".escape_string($qva["billing_state"])."', expiration='".escape_string($qva["expiration"])."', number='".escape_string($qva["number"])."', opportunity='".escape_string($qva["opportunity"])."', opportunity_id='".escape_string($qva["opportunity_id"])."', shipping_account='".escape_string($qva["shipping_account"])."', template_ddown_c='".escape_string($qva["template_ddown_c"])."', 	shipping_account_id='".escape_string($qva["shipping_account_id"])."', shipping_address='".escape_string($qva["shipping_address"])."', shipping_city='".escape_string($qva["shipping_city"])."', shipping_contact='".escape_string($qva["shipping_contact"])."', shipping_contact_id='".escape_string($qva["shipping_contact_id"])."', shipping_country='".escape_string($qva["shipping_country"])."', shipping_postal='".escape_string($qva["shipping_postal"])."', shipping_state='".escape_string($qva["shipping_state"])."', stage='".escape_string($qva["stage"])."', term='".escape_string($qva["term"])."', deleted='".escape_string($qva["deleted"])."', subtotal_amount='".escape_string($qva["subtotal_amount"])."', tax_amount='".escape_string($qva["tax_amount"])."', shipping_amount='".escape_string($qva["shipping_amount"])."', total_amount='".escape_string($qva["total_amount"])."', inserted_from='".escape_string($qva["inserted_from"])."', is_paid='".escape_string($qva["is_paid"])."'";
					//$crm_ins_quote_rst = mysql_query($crm_ins_quote_qry, $link_crm);
					//$crm_del_quote_qry = "DELETE FROM quotes WHERE id = '".escape_string($qva["id"])."'";
					//insert_query_log($crm_ins_quote_qry);delete_query_log($crm_del_quote_qry);

					//echo "<br/>";
					
					$crm_ins_quote_cstm_qry = "INSERT INTO quotes_cstm SET id_c='".escape_string($qva["id_c"])."', quote_comment_c='".escape_string($qva["quote_comment_c"])."', ot_lev_discount_c='".escape_string($qva["ot_lev_discount_c"])."', cust_discount_c='".escape_string($qva["cust_discount_c"])."', shipping_methods_c='".escape_string($qva["shipping_methods_c"])."', cre_orderid_c='".escape_string($qva["cre_orderid_c"])."', cre_order_status_id_c='".escape_string($qva["cre_order_status_id_c"])."', ot_shipping_tax_inc_c='".escape_string($qva["ot_shipping_tax_inc_c"])."', ot_tax_c='".escape_string($qva["ot_tax_c"])."', ot_gst_total_c='".escape_string($qva["ot_gst_total_c"])."', ot_coupon_c='".escape_string($qva["ot_coupon_c"])."', quote_number_c='".escape_string($qva["quote_number_c"])."', quote_email_c='".escape_string($qva["quote_email_c"])."', quote_phone_c='".escape_string($qva["quote_phone_c"])."', ip_address_c='".escape_string($qva["ip_address_c"])."', isp_c='".escape_string($qva["isp_c"])."', payment_method_c='".escape_string($qva["payment_method_c"])."', shipping_address_2_c='".escape_string($qva["shipping_address_2_c"])."', billing_email_c='".escape_string($qva["billing_email_c"])."', shipping_email_c='".escape_string($qva["shipping_email_c"])."', billing_fax_c='".escape_string($qva["billing_fax_c"])."', shipping_fax_c='".escape_string($qva["shipping_fax_c"])."', billing_telephone_c='".escape_string($qva["billing_telephone_c"])."', shipping_telephone_c='".escape_string($qva["shipping_telephone_c"])."', customer_notification_c='".escape_string($qva["customer_notification_c"])."', billing_address_2_c='".escape_string($qva["billing_address_2_c"])."', create_type_c='".escape_string($qva["create_type_c"])."', ot_grand_subtotal_c='".escape_string($qva["ot_grand_subtotal_c"])."', quote_status_option_c='".escape_string($qva["quote_status_option_c"])."', is_delete='".escape_string($qva["is_delete"])."', is_paid_c='".escape_string($qva["is_paid_c"])."', credit_changed_by_c='".escape_string($qva["credit_changed_by_c"])."', changalbe_time_credit_c='".escape_string($qva["changalbe_time_credit_c"])."', due_date_c='".escape_string($qva["due_date_c"])."', purchase_no_c='".escape_string($qva["purchase_no_c"])."', is_from_lead_c='".escape_string($qva["is_from_lead_c"])."', lead_id_c='".escape_string($qva["lead_id_c"])."', gst_not_applicatble_c='".escape_string($qva["gst_not_applicatble_c"])."', in_group_c='".escape_string($qva["in_group_c"])."', group_quote_id_c='".escape_string($qva["group_quote_id_c"])."', group_name_c='".escape_string($qva["group_name_c"])."', related_quote_name_c='".escape_string($qva["related_quote_name_c"])."', group_status_c='".escape_string($qva["group_status_c"])."'";
					
					//$crm_ins_quote_cstm_rst = mysql_query($crm_ins_quote_cstm_qry, $link_crm);
					//$crm_del_quote_cstm_qry = "DELETE FROM quotes_cstm WHERE id_c = '".escape_string($qva["id_c"])."'";
					//insert_query_log($crm_ins_quote_cstm_qry);delete_query_log($crm_del_quote_cstm_qry);
					
					foreach($qva['products'] as $qpke=>$qpva) {
						
						$crm_ins_pro_quote_qry = "INSERT INTO products_quotes SET id='".escape_string($qpva["pqid"])."', date_modified='".escape_string($qpva["pqdm"])."', category_id='".escape_string($qpva["pqcid"])."', quote_id='".escape_string($qpva["quote_id"])."', product_id='".escape_string($qpva["product_id"])."', product_name='".escape_string($qpva["product_name"])."', product_qty='".escape_string($qpva["product_qty"])."', product_cost_price='".escape_string($qpva["product_cost_price"])."',  deleted='".escape_string($qpva["pqdelete"])."', is_delete='".escape_string($qpva["is_delete"])."', product_list_price='".escape_string($qpva["product_list_price"])."',  product_unit_price='".escape_string($qpva["product_unit_price"])."', vat_amt='".escape_string($qpva["vat_amt"])."', vat='".escape_string($qpva["vat"])."' , product_total_price='".escape_string($qpva["product_total_price"])."' , product_note='".escape_string($qpva["product_note"])."'";
						//$crm_ins_pro_quote_rst = mysql_query($crm_ins_pro_quote_qry, $link_crm);
						//$crm_del_pro_quote_qry = "DELETE FROM products_quotes WHERE id = '".escape_string($qpva["id"])."'";
						//insert_query_log($crm_ins_pro_quote_qry);delete_query_log($crm_del_pro_quote_qry);
						
						//echo "<br/>";
						
						$crm_ins_quote_cost_qry = "INSERT INTO quote_products_costs SET id='".escape_string($qpva["qcid"])."', quote_id='".escape_string($qpva["quote_id"])."', products_id='".escape_string($qpva["products_id"])."', products_quantity='".escape_string($qpva["products_quantity"])."', labour_cost='".escape_string($qpva["labour_cost"])."', material_cost='".escape_string($qpva["material_cost"])."',  overhead_cost='".escape_string($qpva["overhead_cost"])."', orders_products_costs_id='".escape_string($qpva["orders_products_costs_id"])."'";
						//$crm_ins_quote_cost_rst = mysql_query($crm_ins_quote_cost_qry, $link_crm);
						//$crm_del_quote_cost_qry = "DELETE FROM quote_products_costs WHERE id = '".escape_string($qpva["id"])."'";
						//insert_query_log($crm_ins_quote_cost_qry);delete_query_log($crm_del_quote_cost_qry);
						
						$crm_pro_num_of_row = check_crm_pro_row($qpva["product_id"],$link_crm);				
						if($crm_pro_num_of_row==0) {
							
							//echo "<br/>";
							
							$crm_ins_pro_qry = "INSERT INTO products SET id='".escape_string($qpva["pid"])."', date_modified='".escape_string($qpva["pdm"])."', category_id='".escape_string($qpva["pcid"])."', date_entered='".escape_string($qpva["date_entered"])."', modified_user_id='".escape_string($qpva["modified_user_id"])."', assigned_user_id='".escape_string($qpva["assigned_user_id"])."', name='".escape_string($qpva["name"])."', description='".escape_string($qpva["description"])."',  deleted='".escape_string($qpva["pdelete"])."', termandcondition='".escape_string($qpva["termandcondition"])."', created_by='".escape_string($qpva["created_by"])."',  availability='".escape_string($qpva["availability"])."', maincode='".escape_string($qpva["maincode"])."', partnumber='".escape_string($qpva["partnumber"])."' , category='".escape_string($qpva["category"])."' , contact='".escape_string($qpva["contact"])."', contact_id='".escape_string($qpva["contact_id"])."', cost='".escape_string($qpva["cost"])."', date_available='".escape_string($qpva["date_available"])."', manufacturer='".escape_string($qpva["manufacturer"])."', mfr_part_num='".escape_string($qpva["mfr_part_num"])."', price='".escape_string($qpva["price"])."', type='".escape_string($qpva["type"])."',  vendor_part_num='".escape_string($qpva["vendor_part_num"])."', url='".escape_string($qpva["url"])."', quantity='".escape_string($qpva["quantity"])."',  weight='".escape_string($qpva["weight"])."', manufacturer_id='".escape_string($qpva["manufacturer_id"])."', date_inserted='".escape_string($qpva["date_inserted"])."' , original_product='".escape_string($qpva["original_product"])."'";
							//$crm_ins_pro_rst = mysql_query($crm_ins_pro_qry, $link_crm);
							//$crm_del_pro_qry = "DELETE FROM products WHERE id = '".escape_string($qpva["pid"])."'";
							//insert_query_log($crm_ins_pro_qry);delete_query_log($crm_del_pro_qry);
							
							//echo "<br/>";
							
							$crmp_src_image = 'http://ajparkes.com.au/ajpcrm/'.escape_string($qpva[image_c]);
							if(is_file_exists($crmp_src_image)) {
								$crmp_target_image = $_SERVER['DOCUMENT_ROOT']."/CRM-success/".escape_string($qpva[image_c]);						
								if(copy($crmp_src_image,$crmp_target_image)) { log_message($crmp_src_image . " Product image move success."); file_link_log($crmp_target_image); }else { log_message($crmp_src_image . " Product image move failed."); }
							}
							else { log_message($crmp_src_image . " Product image move failed. File not exists."); }
							
							$crm_ins_pro_cstm_qry = "INSERT INTO products_cstm SET id_c='".escape_string($qpva["id_c"])."', sku_c='".escape_string($qpva["sku_c"])."', image_c='".escape_string($qpva["image_c"])."', image1_c='".escape_string($qpva["image1_c"])."', cre_product_id_c='".escape_string($qpva["cre_product_id_c"])."', cre_status_c='".escape_string($qpva["cre_status_c"])."', products_price1_qty_c='".escape_string($qpva["products_price1_qty_c"])."', products_price2_qty_c='".escape_string($qpva["products_price2_qty_c"])."',  products_price3_qty_c='".escape_string($qpva["products_price3_qty_c"])."', products_price4_qty_c='".escape_string($qpva["products_price4_qty_c"])."', products_price5_qty_c='".escape_string($qpva["products_price5_qty_c"])."',  products_price6_qty_c='".escape_string($qpva["products_price6_qty_c"])."', products_price7_qty_c='".escape_string($qpva["products_price7_qty_c"])."', products_price8_qty_c='".escape_string($qpva["products_price8_qty_c"])."' , products_price9_qty_c='".escape_string($qpva["products_price9_qty_c"])."' , products_price10_qty_c='".escape_string($qpva["products_price10_qty_c"])."', products_price11_qty_c='".escape_string($qpva["products_price11_qty_c"])."', products_price1_c='".escape_string($qpva["products_price1_c"])."', products_price2_c='".escape_string($qpva["products_price2_c"])."', products_price3_c='".escape_string($qpva["products_price3_c"])."', products_price4_c='".escape_string($qpva["products_price4_c"])."', products_price5_c='".escape_string($qpva["products_price5_c"])."', products_price6_c='".escape_string($qpva["products_price6_c"])."',  products_price7_c='".escape_string($qpva["products_price7_c"])."', products_price8_c='".escape_string($qpva["products_price8_c"])."', products_price9_c='".escape_string($qpva["products_price9_c"])."',  products_price10_c='".escape_string($qpva["products_price10_c"])."', products_price11_c='".escape_string($qpva["products_price11_c"])."', tax_rate_c='".escape_string($qpva["tax_rate_c"])."' , product_family_c='".escape_string($qpva["product_family_c"])."', customer_no_c='".escape_string($qpva["customer_no_c"])."' , badge_data_c='".escape_string($qpva["badge_data_c"])."', test_c='".escape_string($qpva["test_c"])."' , die_number_c='".escape_string($qpva["die_number_c"])."', category_id_c='".escape_string($qpva["category_id_c"])."' , is_crm_product_c='".escape_string($qpva["is_crm_product_c"])."', prod_family_id_c='".escape_string($qpva["prod_family_id_c"])."' , labour_cost_c='".escape_string($qpva["labour_cost_c"])."', material_cost_c='".escape_string($qpva["material_cost_c"])."' , overhead_cost_c='".escape_string($qpva["overhead_cost_c"])."', date_inserted='".escape_string($qpva["date_inserted"])."'";
							//$crm_ins_pro_cstm_rst = mysql_query($crm_ins_pro_cstm_qry, $link_crm);
							//$crm_del_pro_cstm_qry = "DELETE FROM products_cstm WHERE id_c = '".escape_string($qpva["id_c"])."'";
							//insert_query_log($crm_ins_pro_cstm_qry);delete_query_log($crm_del_pro_cstm_qry);
							
						}
					
					}
					
					//echo "<br/>";
					foreach($qva['history'] as $qhke=>$qhva) {
							
							//echo "<br/>";
							
							$crm_ins_his_qry = "INSERT INTO quote_status_history SET id='".escape_string($qhva["id"])."', quote_id='".escape_string($qhva["quote_id"])."', order_status_id='".escape_string($qhva["order_status_id"])."', date_added='".escape_string($qhva["date_added"])."', customer_notified='".escape_string($qhva["customer_notified"])."', comments='".escape_string($qhva["comments"])."', user_id='".escape_string($qhva["user_id"])."', cre_user_name='".escape_string($qhva["cre_user_name"])."',  account_id='".escape_string($qhva["account_id"])."', lead_act_name='".escape_string($qhva["lead_act_name"])."', from_crm='".escape_string($qhva["from_crm"])."'";
							//$crm_ins_his_rst = mysql_query($crm_ins_his_qry, $link_crm);
							//$crm_del_his_qry = "DELETE FROM quote_status_history WHERE id = '".escape_string($qhva["id"])."'";
							//insert_query_log($crm_ins_his_qry);delete_query_log($crm_del_his_qry);
					}
					
					//echo "<br/>";
					foreach($qva['order_docs'] as $odke=>$odva) {
							
							//echo "<br/>";
							$accpath = "./CRM-success/order_docs/".escape_string($odva["account_folder_name"]);
							if(!is_dir($accpath)) { mkdir($accpath,0777,true); }
							
							$ordpath = $accpath."/".escape_string($odva["order_folder_name"]);
							if(!is_dir($ordpath)) { mkdir($ordpath,0777,true); } 
							
							$crmod_src_file = 'http://ajparkes.com.au/ajpcrm/order_docs/'.escape_string($odva["account_folder_name"])."/".escape_string($odva["order_folder_name"])."/".escape_string($odva["filename"]);
							if(is_file_exists($crmod_src_file)) {
								
								$crmod_target_file = $_SERVER['DOCUMENT_ROOT']."/CRM-success/order_docs/".escape_string($odva["account_folder_name"])."/".escape_string($odva["order_folder_name"])."/".escape_string($odva["filename"]);
								
								if(copy($crmod_src_file,$crmod_target_file)) { log_message($crmod_src_file . " Order Docs move success."); file_link_log($crmod_target_file); }else { log_message($crmod_src_file . " Order Docs move failed.".$crmod_target_file); }
							}
							else { log_message($crmod_src_file . " Order Docs move failed. File not exists.".$crmod_target_file); }
							
							$crm_ins_order_docs_qry = "INSERT INTO cre_order_docs SET quote_id='".escape_string($odva["quote_id"])."', account_id='".escape_string($odva["account_id"])."', date_entered='".escape_string($odva["date_entered"])."', date_modified='".escape_string($odva["date_modified"])."', filename='".escape_string($odva["filename"])."', mime_type='".escape_string($odva["mime_type"])."', account_folder_name='".escape_string($odva["account_folder_name"])."',  order_folder_name='".escape_string($odva["order_folder_name"])."'";
							
							//$crm_ins_order_docs_rst = mysql_query($crm_ins_order_docs_qry, $link_crm);
							//$crm_del_order_docs_rst = "DELETE FROM cre_order_docs WHERE quote_id='".escape_string($odva["quote_id"])."' and  account_id='".escape_string($odva["account_id"])."'";
							//insert_query_log($crm_ins_order_docs_qry);delete_query_log($crm_del_order_docs_rst);
					}
					
				}
				
			} //CRM update end ***********************************************
			
			
			foreach($arrval['address_book'] as $custkey=>$cusval) {
				
				echo "<br>";
				echo $ins_addr_query = "INSERT INTO address_book SET ". $address_book_id_entry_string . 
				" entry_gender = '".escape_string($cusval['entry_gender'])."', "." entry_company = '".escape_string($cusval['entry_company'])."', ".
				" entry_company_tax_id = '".escape_string($cusval['entry_company_tax_id'])."', "." entry_firstname = '".escape_string($cusval['entry_firstname'])."', ".	
				" entry_lastname = '".escape_string($cusval[entry_lastname])."', "." entry_street_address = '".escape_string($cusval[entry_street_address])."', "." entry_suburb = '".escape_string($cusval[entry_suburb])."', "." entry_postcode = '".escape_string($cusval[entry_postcode])."', "." entry_city = '".escape_string($cusval[entry_city])."', "." entry_state = '".escape_string($cusval[entry_state])."', "." entry_country_id = '".escape_string($cusval[entry_country_id])."', "." entry_zone_id = '".escape_string($cusval[entry_zone_id])."', "." entry_telephone = '".escape_string($cusval[entry_telephone])."', "." entry_fax = '".escape_string($cusval[entry_fax])."', "." entry_email_address = '".escape_string($cusval[entry_email_address])."', "." crm_import = '".escape_string($cusval[crm_import])."', "." from_macola = '".escape_string($cusval[from_macola])."'";
								
				//$ins_addr_rst = tep_db_query($ins_addr_query);
				//$new_customers_address_id = tep_db_insert_id();
				//tep_db_query("UPDATE customers SET customers_default_address_id ='".$new_customers_address_id."' WHERE customers_id='".$products_users_id."'");
				//$del_addr_query = "DELETE FROM address_book WHERE address_book_id = '".$new_customers_address_id."'";
				//insert_query_log($ins_addr_query);delete_query_log($del_addr_query);
				
			}
			
			foreach($arrval['customers_info'] as $custkey=>$cusval) {
				
				echo "<br>";
				echo $ins_ci_query = "INSERT INTO customers_info SET " . $customers_info_id_entry_string . 
				" customers_info_date_of_last_logon = '".escape_string($cusval[customers_info_date_of_last_logon])."', "." customers_info_number_of_logons = '".escape_string($cusval[customers_info_number_of_logons])."', ".
				" customers_info_date_account_created = '".escape_string($cusval[customers_info_date_account_created])."', "." customers_info_date_account_last_modified = '".escape_string($cusval[customers_info_date_account_last_modified])."', ".	
				" global_product_notifications = '".escape_string($cusval[global_product_notifications])."'";
				
				//$ins_ci_rst = tep_db_query($ins_ci_query);
				//$new_ci_id = tep_db_insert_id();
				//$del_ci_query = "DELETE FROM customers_info WHERE customers_info_id = '".$new_ci_id."'";
				//insert_query_log($ins_ci_query);delete_query_log($del_ci_query);
												
			}
			
			
			
			if(count($arrval['my_files'])>0) {
				foreach($arrval['my_files'] as $custkey=>$cusval) {								
									
					$m_filepath = explode('/',escape_string($cusval[file_path]),5);				
					
					if(!empty($m_filepath[4])) { 
						$m_srcpath = "http://ajparkes.com.au/".$m_filepath[4];
						if(is_file_exists($m_srcpath)) {
							$m_target = $_SERVER['DOCUMENT_ROOT']."/".escape_string($m_filepath[4]);
							if(copy($m_srcpath,$m_target)) { log_message($m_srcpath . " My File move success."); file_link_log($m_target); } else { log_message($m_srcpath . " My File move failed."); }
						}
						else { log_message($m_srcpath . " My File move failed. File not exists."); }
					}
					
					echo "<br>";
					echo $ins_mf_query = "INSERT INTO my_files SET ".$my_files_customer_id_entry_string. 
					" file_name = '".escape_string($cusval[file_name])."', "." file_path = '".$m_target."', ".
					" date_uploaded = '".escape_string($cusval[date_uploaded])."', "." comment = '".escape_string($cusval[comment])."'";
					
					echo "<br>";
					//$ins_mf_rst = tep_db_query($ins_mf_query);
					//$new_mf_id = tep_db_insert_id();
					//$del_mf_query = "DELETE FROM my_files WHERE files_id = '".$new_mf_id."'";
					//insert_query_log($ins_mf_query);delete_query_log($del_mf_query);
													
				}
			}
			
			//Artwork files
			if(count($arrval['artwork'])>0) {
			
				foreach($arrval['artwork'] as $custkey=>$cusval) {
					
					$artwork_id_entry = check_cre_artwork_id($cusval[artwork_id]);				
					
					if($artwork_id_entry==$cusval[artwork_id]) { 					
						
						$new_artwork_id = next_auto_increment("artwork");
						$artwork_id_entry_string = " artwork_id='".$new_artwork_id."', "; 
						
					} else { 
						$artwork_id_entry_string = " artwork_id='".$cusval[artwork_id]."', "; 
					}
					
					echo "<br>";
					echo $ins_aw1_query = "INSERT INTO artwork SET ".$artwork_customer_id_entry_string. $artwork_id_entry_string .
					" orders_id = '".escape_string($cusval[orders_id])."', "." products_id = '".escape_string($cusval[products_id])."', ".
					" creative_brief = '".escape_string($cusval[creative_brief])."', "." sales_consultant = '".escape_string($cusval[sales_consultant])."', ".
					" designer = '".escape_string($cusval[designer])."', "." designer_id = '".escape_string($cusval[designer_id])."', ".
					" notify_customer = '".escape_string($cusval[notify_customer])."', "." artwork_cc = '".escape_string($cusval[artwork_cc])."', ".
					" artwork_bcc = '".escape_string($cusval[artwork_bcc])."', "." linked_to_order = '".escape_string($cusval[linked_to_order])."', ".
					" artwork_status = '".escape_string($cusval[artwork_status])."', "." date_created = '".escape_string($cusval[date_created])."'";
					
					//$ins_aw1_rst = tep_db_query($ins_aw1_query);
					//$new_artwork_id = tep_db_insert_id();
					//$del_aw1_query = "DELETE FROM artwork WHERE artwork_id = '".$new_artwork_id."'";
					//insert_query_log($ins_aw1_query);delete_query_log($del_aw1_query);
					
					
					
					foreach($cusval['options'] as $aoke=>$aova) {
						
						$artwork_option_id_entry = check_cre_artwork_option_id($aova[artwork_option_id]);				
					
						if($artwork_option_id_entry==$aova[artwork_option_id]) { 					
							$new_artwork_option_id = next_auto_increment("artwork_option");
							$artwork_option_id_entry_string = " artwork_option_id='".$new_artwork_option_id."', "; 
							
							//update all revision for artwork option
							//tep_db_query("UPDATE artwork_option set revision_id='".$new_artwork_option_id."' WHERE revision_id='".$aova[artwork_option_id]."'");
							
						} else { 
							$artwork_option_id_entry_string = " artwork_option_id='".$aova[artwork_option_id]."', "; 
						}
						
						
						$src_image = 'http://ajparkes.com.au/'.escape_string($aova[option_image]);
						if(is_file_exists($src_image)) {
							$target_image = $_SERVER['DOCUMENT_ROOT']."/".escape_string($aova[option_image]);	
							if(copy($src_image,$target_image)) { log_message($src_image . " My artwork file move success."); file_link_log($target_image); } else { log_message($src_image . " My artwork file move failed."); }
						}
						else { log_message($src_image . " My artwork file move failed. File not exists."); }
						
						echo "<br>";
						
						echo $ins_awo1_query = "INSERT INTO artwork_option SET ".$artwork_option_id_entry_string. $artwork_id_entry_string .
						" revision_id = '".escape_string($aova[revision_id])."', "." option_name = '".escape_string($aova[option_name])."', ".
						" option_image = '".escape_string($aova[option_image])."', "." option_approve = '".escape_string($aova[option_approve])."'";
						
						//$ins_awo1_rst = tep_db_query($ins_awo1_query);
						//$new_artwork_option_id = tep_db_insert_id();
						//$del_awo1_query = "DELETE FROM artwork_option WHERE artwork_option_id = '".$new_artwork_option_id."'";
						//insert_query_log($ins_awo1_query);delete_query_log($del_awo1_query);
						
						if(!empty($aova[artwork_option_resolution_id])) {
							
							$aor_src_image = 'http://ajparkes.com.au/'.escape_string($aova[resolution_image_path]);
							if(is_file_exists($aor_src_image)) {
								$aor_target_image = $_SERVER['DOCUMENT_ROOT']."/".escape_string($aova[resolution_image_path]);	
								if(copy($aor_src_image,$aor_target_image)) { log_message($aor_src_image . " My artwork file move success."); file_link_log($aor_target_image); } else { log_message($aor_src_image . " My artwork file move failed."); }
							}
							else { log_message($aor_src_image . " My artwork file move failed. File not exists."); }
							
							echo "<br>";
							echo $ins_awor1_query = "INSERT INTO artwork_option_resolution SET ". $artwork_option_id_entry_string .
							" resolution_image_path = '".escape_string($aova[resolution_image_path])."'";
							//echo $ins_awor1_query;
							//$ins_awor1_rst = tep_db_query($ins_awor1_query);
							//$new_artwork_option_resolution_id = tep_db_insert_id();
							//$del_awor1_query = "DELETE FROM artwork_option_resolution WHERE artwork_option_resolution_id = '".$new_artwork_option_resolution_id."'";
							//insert_query_log($ins_awor1_query);delete_query_log($del_awor1_query);
						}
						
						//echo "<br>";
						
						foreach($aova['feedback'] as $afke=>$afva) {
							
							$af_src_image = 'http://ajparkes.com.au/'.escape_string($afva[attachment]);
							if(is_file_exists($af_src_image)) {
								$af_target_image = $_SERVER['DOCUMENT_ROOT']."/".escape_string($afva[attachment]);	
								if(copy($af_src_image,$af_target_image)) { log_message($af_src_image . " My artwork feedback file move success."); file_link_log($af_target_image); } else { log_message($af_src_image . " My artwork feedback file move failed."); }
							}
							else { log_message($af_src_image . " My artwork feedback file move failed. File not exists."); }
							
							echo "<br>";
							echo $ins_af1_query = "INSERT INTO artwork_feedback SET ".$artwork_option_id_entry_string. $artwork_id_entry_string .
							" feedback = '".escape_string($afva[feedback])."', "." attachment = '".escape_string($afva[attachment])."', ". " attachment_name = '".escape_string($afva[attachment_name])."', ". " user_type = '".escape_string($afva[user_type])."', ". " posted_by = '".escape_string($afva[posted_by])."', ". " status = '".escape_string($afva[status])."', "." posted_date = '".escape_string($afva[posted_date])."', "." notify_customer = '".escape_string($afva[notify_customer])."'";
							
							//echo $ins_af1_query;
							//$ins_af1_rst = tep_db_query($ins_af1_query);
							//$new_artwork_feedback_id = tep_db_insert_id();
							//$del_af1_query = "DELETE FROM artwork_feedback WHERE artwork_feedback_id = '".$new_artwork_feedback_id."'";
							//insert_query_log($ins_af1_query);delete_query_log($del_af1_query);
							//echo "<br>";
						}
						
					}
													
				}
			}
			
			foreach($arrval['orders'] as $custkey=>$cusval) {								
				
				//print_r($cusval);echo "<br><br>";	
				$order_id_entry = check_cre_order_id($cusval[orders_id]);				
				
				if($order_id_entry==$cusval[orders_id]) { 	
					$new_orders_id = next_auto_increment("orders");
					$order_id_entry_string1 = " orders_id='".$new_orders_id."', ajp_orders_id = '".$cusval[orders_id]."', ";					
					$order_id_entry_string = " orders_id = '".$new_orders_id."', ";
					//Update Orders ID in CRM					
					echo $crm_upd_quote_qry = "UPDATE quotes_cstm SET cre_orderid_c='".$new_orders_id."' WHERE cre_orderid_c='".$cusval[orders_id]."'";
					//$crm_upd_quote_rst = mysql_query($crm_upd_quote_qry, $link_crm);
					//insert_query_log($crm_upd_quote_qry);
					$zip_filename = "order_".$new_orders_id.".zip";
					
				} else { 
					$order_id_entry_string1 = " orders_id='".$cusval[orders_id]."', ajp_orders_id = '".$cusval[orders_id]."', "; 
					$order_id_entry_string = " orders_id='".$cusval[orders_id]."', "; 
					$zip_filename = "order_".$cusval[orders_id].".zip";
				}
			
				echo "<br>";
				echo $ins_or_query = "INSERT INTO orders SET ". $order_id_entry_string1 . $orders_customer_id_entry_string .  
				" customers_name = '".escape_string($cusval[customers_name])."', "." customers_company = '".escape_string($cusval[customers_company])."', ".
				" customers_street_address = '".escape_string($cusval[customers_street_address])."', "." customers_suburb = '".escape_string($cusval[customers_suburb])."', ". " customers_city = '".escape_string($cusval[customers_city])."', "." customers_postcode = '".escape_string($cusval[customers_postcode])."', ". " customers_state = '".escape_string($cusval[customers_state])."', "." customers_country = '".escape_string($cusval[customers_country])."', ". " customers_telephone = '".escape_string($cusval[customers_telephone])."', "." customers_email_address = '".escape_string($cusval[customers_email_address])."', ". " customers_address_format_id = '".escape_string($cusval[customers_address_format_id])."', "." delivery_name = '".escape_string($cusval[delivery_name])."', ". " delivery_company = '".escape_string($cusval[delivery_company])."', "." delivery_street_address = '".escape_string($cusval[delivery_street_address])."', ". " delivery_suburb = '".escape_string($cusval[delivery_suburb])."', "." delivery_city = '".escape_string($cusval[delivery_city])."', ". " delivery_postcode = '".escape_string($cusval[delivery_postcode])."', "." delivery_state = '".escape_string($cusval[delivery_state])."', ". " delivery_country = '".escape_string($cusval[delivery_country])."', "." delivery_address_format_id = '".escape_string($cusval[delivery_address_format_id])."', ". " billing_name = '".escape_string($cusval[billing_name])."', "." billing_company = '".escape_string($cusval[billing_company])."', ". " billing_street_address = '".escape_string($cusval[billing_street_address])."', "." billing_suburb = '".escape_string($cusval[billing_suburb])."', ". " billing_city = '".escape_string($cusval[billing_city])."', "." billing_postcode = '".escape_string($cusval[billing_postcode])."', ". " billing_state = '".escape_string($cusval[billing_state])."', "." billing_country = '".escape_string($cusval[billing_country])."', ". " billing_address_format_id = '".escape_string($cusval[billing_address_format_id])."', "." payment_method = '".escape_string($cusval[payment_method])."', ". " payment_info = '".escape_string($cusval[payment_info])."', "." payment_id = '".escape_string($cusval[payment_id])."', ". " cc_type = '".escape_string($cusval[cc_type])."', "." cc_owner = '".escape_string($cusval[cc_owner])."', ". " cc_number = '".escape_string($cusval[cc_number])."', "." cc_ccv = '".escape_string($cusval[cc_ccv])."', ". " cc_expires = '".escape_string($cusval[cc_expires])."', "." cc_start = '".escape_string($cusval[cc_start])."', ". " cc_issue = '".escape_string($cusval[cc_issue])."', "." cc_bank_phone = '".escape_string($cusval[cc_bank_phone])."', ". " last_modified = '".escape_string($cusval[last_modified])."', "." date_purchased = '".escape_string($cusval[date_purchased])."', ". " orders_status = '".escape_string($cusval[orders_status])."', "." orders_date_finished = '".escape_string($cusval[orders_date_finished])."', ". " currency = '".escape_string($cusval[currency])."', "." currency_value = '".escape_string($cusval[currency_value])."', ". " account_name = '".escape_string($cusval[account_name])."', "." account_number = '".escape_string($cusval[account_number])."', ". " po_number = '".escape_string($cusval[po_number])."', "." purchased_without_account = '".escape_string($cusval[purchased_without_account])."', ". " paypal_ipn_id = '".escape_string($cusval[paypal_ipn_id])."', "." ipaddy = '".escape_string($cusval[ipaddy])."', "." ipisp = '".escape_string($cusval[ipisp])."', "." delivery_telephone = '".escape_string($cusval[delivery_telephone])."', "." delivery_fax = '".escape_string($cusval[delivery_fax])."', "." delivery_email_address = '".escape_string($cusval[delivery_email_address])."', "." billing_telephone = '".escape_string($cusval[billing_telephone])."', "." billing_fax = '".escape_string($cusval[billing_fax])."', ". " billing_email_address = '".escape_string($cusval[billing_email_address])."', ". " purchase_number = '".escape_string($cusval[purchase_number])."', ". " due_date = '".escape_string($cusval[due_date])."', ". " order_assigned_to = '".escape_string($cusval[order_assigned_to])."', "." order_assigned_to_email = '".escape_string($cusval[order_assigned_to_email])."', ". " order_display = '".escape_string($cusval[order_display])."', "." crm_order = '".escape_string($cusval[crm_order])."', ". " xero = '".escape_string($cusval[xero])."', ". " is_paid = '".escape_string($cusval[is_paid])."'";								
				
				//$ins_or_rst = tep_db_query($ins_or_query);
				//$new_orders_id = tep_db_insert_id();
				//$del_or_query = "DELETE FROM orders WHERE orders_id = '".$new_orders_id."'";
				//insert_query_log($ins_or_query);delete_query_log($del_or_query);
				
				
				$src_zipbadge = 'http://ajparkes.com.au/images/zip_bages/'."order_".$cusval[orders_id].".zip";
				if(is_file_exists($src_zipbadge)) {
					$target_zipbadge = $_SERVER['DOCUMENT_ROOT']."/images/zip_bages/".$zip_filename;						
					if(copy($src_zipbadge,$target_zipbadge)) { log_message($src_zipbadge . " Zip file move success."); file_link_log($target_zipbadge); }else { log_message($src_zipbadge . " Zip file move failed."); }
				}
				else { log_message($src_zipbadge . " Zip file move failed. File not exists."); }
				
				
				//Artwork-Orders files Start
				if(count($cusval['artwork'])>0) {
					foreach($cusval['artwork'] as $aw2key=>$aw2val) {
						
						$artwork_id_entry = check_cre_artwork_id($aw2val[artwork_id]);				
						
						if($artwork_id_entry==$aw2val[artwork_id]) { 
							$new_artwork_id = next_auto_increment("artwork");
							$artwork_id_entry_string = " artwork_id='".$new_artwork_id."', ";
						} else { 
							$artwork_id_entry_string = " artwork_id='".$aw2val[artwork_id]."', "; 
						}
						
						echo "<br>";
						echo $ins_aw1_query = "INSERT INTO artwork SET ".$artwork_customer_id_entry_string. $artwork_id_entry_string . $order_id_entry_string .
						" products_id = '".escape_string($aw2val[products_id])."', ".
						" creative_brief = '".escape_string($aw2val[creative_brief])."', "." sales_consultant = '".escape_string($aw2val[sales_consultant])."', ".
						" designer = '".escape_string($aw2val[designer])."', "." designer_id = '".escape_string($aw2val[designer_id])."', ".
						" notify_customer = '".escape_string($aw2val[notify_customer])."', "." artwork_cc = '".escape_string($aw2val[artwork_cc])."', ".
						" artwork_bcc = '".escape_string($aw2val[artwork_bcc])."', "." linked_to_order = '".escape_string($aw2val[linked_to_order])."', ".
						" artwork_status = '".escape_string($aw2val[artwork_status])."', "." date_created = '".escape_string($aw2val[date_created])."'";
						
						//$ins_aw1_rst = tep_db_query($ins_aw1_query);
						//$new_artwork_id = tep_db_insert_id();
						//$del_aw1_query = "DELETE FROM artwork WHERE artwork_id = '".$new_artwork_id."'";
						//insert_query_log($ins_aw1_query);delete_query_log($del_aw1_query);
						
						foreach($aw2val['options'] as $aoke=>$aova) {
							
							$artwork_option_id_entry = check_cre_artwork_option_id($aova[artwork_option_id]);				
						
							if($artwork_option_id_entry==$aova[artwork_option_id]) { 					
								$new_artwork_option_id = next_auto_increment("artwork_option");
								$artwork_option_id_entry_string = " artwork_option_id='".$new_artwork_option_id."', ";
								
								//update all revision for artwork option
								//tep_db_query("UPDATE artwork_option set revision_id='".$new_artwork_option_id."' WHERE revision_id='".$aova[artwork_option_id]."'");
								
							} else { 
								$artwork_option_id_entry_string = " artwork_option_id='".$aova[artwork_option_id]."', "; 
							}
							
							
							$src_image = 'http://ajparkes.com.au/'.escape_string($aova[option_image]);
							if(is_file_exists($src_image)) {
								$target_image = $_SERVER['DOCUMENT_ROOT']."/".escape_string($aova[option_image]);	
								if(copy($src_image,$target_image)) { log_message($src_image . " My artwork file move success."); file_link_log($target_image); } else { log_message($src_image . " My artwork file move failed."); }
							}
							else { log_message($src_image . " My artwork file move failed. File not exists."); }
							
							echo "<br>";
							echo $ins_awo1_query = "INSERT INTO artwork_option SET ".$artwork_option_id_entry_string. $artwork_id_entry_string .
							" revision_id = '".escape_string($aova[revision_id])."', "." option_name = '".escape_string($aova[option_name])."', ".
							" option_image = '".escape_string($aova[option_image])."', "." option_approve = '".escape_string($aova[option_approve])."'";
							
							//$ins_awo1_rst = tep_db_query($ins_awo1_query);
							//$new_artwork_option_id = tep_db_insert_id();
							//$del_awo1_query = "DELETE FROM artwork_option WHERE artwork_option_id = '".$new_artwork_option_id."'";
							//insert_query_log($ins_awo1_query);delete_query_log($del_awo1_query);
							
							if(!empty($aova[artwork_option_resolution_id])) {
								
								$aor_src_image = 'http://ajparkes.com.au/'.escape_string($aova[resolution_image_path]);
								if(is_file_exists($aor_src_image)) {
									$aor_target_image = $_SERVER['DOCUMENT_ROOT']."/".escape_string($aova[resolution_image_path]);	
									if(copy($aor_src_image,$aor_target_image)) { log_message($aor_src_image . " My artwork file move success."); file_link_log($aor_target_image); } else { log_message($aor_src_image . " My artwork file move failed."); }
								}
								else { log_message($aor_src_image . " My artwork file move failed. File not exists."); }
								
								echo "<br>";
								echo $ins_awor1_query = "INSERT INTO artwork_option_resolution SET " .$artwork_option_id_entry_string .
								" resolution_image_path = '".escape_string($aova[resolution_image_path])."'";
								
								//$ins_awor1_rst = tep_db_query($ins_awor1_query);
								//$new_artwork_option_resolution_id = tep_db_insert_id();
								//$del_awor1_query = "DELETE FROM artwork_option_resolution WHERE artwork_option_resolution_id = '".$new_artwork_option_resolution_id."'";
								//insert_query_log($ins_awor1_query);delete_query_log($del_awor1_query);
							}
							
							//echo "<br>";
							
							foreach($aova['feedback'] as $afke=>$afva) {
								
								$af_src_image = 'http://ajparkes.com.au/'.escape_string($afva[attachment]);
								if(is_file_exists($af_src_image)) {
									$af_target_image = $_SERVER['DOCUMENT_ROOT']."/".escape_string($afva[attachment]);	
									if(copy($af_src_image,$af_target_image)) { log_message($af_src_image . " My artwork feedback file move success."); file_link_log($af_target_image); } else { log_message($af_src_image . " My artwork feedback file move failed."); }
								}
								else { log_message($af_src_image . " My artwork feedback file move failed. File not exists."); }
								
								echo "<br>";
								echo $ins_af1_query = "INSERT INTO artwork_feedback SET ".$artwork_option_id_entry_string. $artwork_id_entry_string .
								" feedback = '".escape_string($afva[feedback])."', "." attachment = '".escape_string($afva[attachment])."', ". " attachment_name = '".escape_string($afva[attachment_name])."', ". " user_type = '".escape_string($afva[user_type])."', ". " posted_by = '".escape_string($afva[posted_by])."', ". " status = '".escape_string($afva[status])."', "." posted_date = '".escape_string($afva[posted_date])."', "." notify_customer = '".escape_string($afva[notify_customer])."'";
								
								//echo $ins_af1_query;
								//$ins_af1_rst = tep_db_query($ins_af1_query);
								//$new_artwork_feedback_id = tep_db_insert_id();
								//$del_af1_query = "DELETE FROM artwork_feedback WHERE artwork_feedback_id = '".$new_artwork_feedback_id."'";
								//insert_query_log($ins_af1_query);delete_query_log($del_af1_query);
								echo "<br>";
							}							
						}
					}						
				}
				
				
				foreach($cusval['products'] as $pke=>$pva) {
					
					if($cusval[orders_id] == $pva[orders_id]) {
						
						$p_src_image = 'http://ajparkes.com.au/images/'.escape_string($pva[products_image]);
						if(is_file_exists($p_src_image)) {
							$p_target_image = $_SERVER['DOCUMENT_ROOT']."/images/".escape_string($pva[products_image]);						
							if(copy($p_src_image,$p_target_image)) { log_message($p_src_image . " Product image move success."); file_link_log($p_target_image); }else { log_message($p_src_image . " Product image move failed."); }
						}
						else { log_message($p_src_image . " Product image move failed. File not exists."); }
						
						if ($pva['badge_data']) {
							  
							  require_once('./templates/nbi_au/bd/badge_desc.php');
							  $badge = new Badge(escape_string($pva['badge_data']));
							  //Move logo of the badges if avail
							  foreach($badge->data->logos as $imgkey=>$imgval) {
								
								$badge_img_src = 'http://ajparkes.com.au/templates/Ajparkes1/bd/_tmp/'.$imgval->src;
								if(is_file_exists($badge_img_src)) {
									$badge_img_target = $_SERVER["DOCUMENT_ROOT"]."/templates/nbi_au/bd/_tmp/".$imgval->src;
									if(copy($badge_img_src,$badge_img_target)) { log_message($badge_img_src . " Badge logo image move success."); file_link_log($badge_img_target); } else { log_message($badge_img_src . " Product image move failed."); }
									//print_r($imgval->src);
									//echo "<br>";
								}
								else { log_message($badge_img_src . " Product image move failed. File not exists."); }
							  }
							  //echo "<br><br>";
							  //Move name list of the badges if avail							  
							  foreach($badge->data->multiName as $filekey=>$fileval) {
								
								$badge_file_src = 'http://ajparkes.com.au/templates/Ajparkes1/bd/_tmp/'.$fileval->src;
								if(is_file_exists($badge_file_src)) {
									$badge_file_target = $_SERVER["DOCUMENT_ROOT"]."/templates/nbi_au/bd/_tmp/".$fileval->src;
									if(copy($badge_file_src,$badge_file_target)) { log_message($badge_file_src . " Badge logo image move success."); file_link_log($badge_file_target); } else { log_message($badge_file_src . " Product image move failed."); }
									//print_r($fileval);
									//echo "<br>";
								}
								else { log_message($badge_file_src . " Product image move failed. File not exists."); }
							  }
							  echo "<br><br>";
							 						   
						}
						
						$product_id_entry = check_cre_product_id($pva[products_id]);
						if($product_id_entry==$pva[products_id]) {							
							
							$new_products_id = next_auto_increment("products");
							$product_id_entry_string = " products_id='".$new_products_id."', ajp_products_id = '".$pva[products_id]."', "; 
							$product_desc_id_entry_string = " products_id = '".$new_products_id."', "; 
							$final_products_id = $new_products_id;
							
						} else { 
							$product_id_entry_string = " products_id='".$pva[products_id]."', ajp_products_id = '".$pva[products_id]."', "; 
							$product_desc_id_entry_string = " products_id='".$pva[products_id]."', "; 
							$final_products_id = $pva[products_id];
						}
						
						echo "<br>";						
						echo $ins_p_query = "INSERT INTO products SET ". $product_id_entry_string ." products_quantity = '".escape_string($pva[products_quantity])."', ".
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
						//$ins_p_rst = tep_db_query($ins_p_query);
						//$new_products_id = tep_db_insert_id();
						//$del_p_query = "DELETE FROM products WHERE products_id = '".$new_products_id."'";
						//insert_query_log($ins_p_query);delete_query_log($del_p_query);
						
						echo "<br>";
						echo $ins_pd_query = "INSERT INTO products_description SET ". $product_desc_id_entry_string . " language_id = '".escape_string($pva[language_id])."', "." products_name = '".escape_string($pva[products_name])."', ". " products_description = '".escape_string($pva[products_description])."', "." products_url = '".escape_string($pva[products_url])."', ". " products_viewed = '".escape_string($pva[products_viewed])."', ". " products_head_title_tag = '".escape_string($pva[products_head_title_tag])."', ". " products_head_desc_tag = '".escape_string($pva[products_head_desc_tag])."', ". " products_head_keywords_tag = '".escape_string($pva[products_head_keywords_tag])."', ". " products_blurb = '".escape_string($pva[products_blurb])."', ". " products_option_type = '".escape_string($pva[products_option_type])."', ". " products_option_values = '".escape_string($pva[products_option_values])."'";
						
						//echo $ins_pd_query;
						//$ins_pd_rst = tep_db_query($ins_pd_query);
						//$del_pd_query = "DELETE FROM products_description WHERE products_id = '".$final_products_id."'";
						//insert_query_log($ins_pd_query);delete_query_log($del_pd_query);

						foreach($pva['products_badge_options'] as $pboke=>$pbova) {
							
							if($pva[products_id]==$pbova[products_id]) {
								
								echo "<br>";
								echo $ins_pbo_query = "INSERT INTO products_badge_options SET products_id='".$final_products_id."', options_id='".$pbova[options_id]."', options_type='".$pbova[options_type]."', options_values_id='".$pbova[options_values_id]."', options_text='".escape_string($pbova[options_text])."', options_desc='".escape_string($pbova[options_desc])."'";
								//$ins_pbo_rst = tep_db_query($ins_pbo_query);
								//$new_products_badge_options_id = tep_db_insert_id();
								//$del_pbo_query = "DELETE FROM products_badge_options WHERE products_badge_options_id = '".$new_products_badge_options_id."'";
								//insert_query_log($ins_pbo_query);delete_query_log($del_pbo_query);
						
							}
							
						}
						
						foreach($pva['products_to_categories'] as $p2cke=>$p2cva) {
							
							if($pva[products_id]==$p2cva[products_id]) {
								
								echo "<br>";
								echo $ins_p2c_query = "INSERT INTO products_to_categories SET products_id='".$final_products_id."', categories_id='".$p2cva[categories_id]."'";
								//$ins_p2c_rst = tep_db_query($ins_pbo_query);
								//$del_p2c_query = "DELETE FROM products_to_categories WHERE products_id = '".$final_products_id."'";
								//insert_query_log($ins_p2c_query);delete_query_log($del_p2c_query);
						
							}
							
						}
						
						echo "<br>";
						echo $ins_op_query = "INSERT INTO orders_products SET " . $order_id_entry_string . $product_desc_id_entry_string . " products_model = '".escape_string($pva[products_model])."', "." products_name = '".escape_string($pva[products_name])."', ". " products_description = '".escape_string($pva[products_description])."', "." products_price = '".escape_string($pva[products_price])."', ". " final_price = '".escape_string($pva[final_price])."', ". " products_tax = '".escape_string($pva[products_tax])."', ". " products_quantity = '".escape_string($pva[products_quantity])."', ". " products_returned = '".escape_string($pva[products_returned])."', ". " products_exchanged = '".escape_string($pva[products_exchanged])."', ". " products_exchanged_id = '".escape_string($pva[products_exchanged_id])."', ". " vendors_id = '".escape_string($pva[vendors_id])."', ". " products_purchase_number = '".escape_string($pva[products_purchase_number])."', ". " badge_comment = '".escape_string($pva[badge_comment])."', ". " crm_discount_percent = '".escape_string($pva[crm_discount_percent])."'";
						
						//echo $ins_op_query;
						//$ins_op_rst = tep_db_query($ins_op_query);
						//$new_orders_products_id = tep_db_insert_id();
						//$del_op_query = "DELETE FROM orders_products WHERE orders_products_id = '".$new_orders_products_id."'";
						//insert_query_log($ins_op_query);delete_query_log($del_op_query);	
						
						echo "<br>";
						echo $ins_opc_query = "INSERT INTO orders_products_costs SET orders_products_id = '".$new_op_id."', " . $order_id_entry_string . " products_id = '".$new_products_id."', ". " products_quantity = '".$pva[products_quantity]."', "." labour_cost = '".$pva[labour_cost]."', ". " material_cost = '".$pva[material_cost]."', "." overhead_cost = '".$pva[overhead_cost]."'";
						//echo $ins_opc_query;
						//$ins_op_rst = tep_db_query($ins_opc_query);
						//$new_orders_products_id = tep_db_insert_id();
						//$del_op_query = "DELETE FROM orders_products_costs WHERE products_badge_options_id = '".$new_orders_products_id."'";
						//insert_query_log($ins_op_query);delete_query_log($del_op_query);	
					}
	
				}
				
				foreach($cusval['orders_status_history'] as $oshke=>$oshva) {
					
					echo "<br>";
					echo $ins_osh_query = "INSERT INTO orders_status_history SET " .$order_id_entry_string. " orders_status_id = '".$oshva[orders_status_id]."', ". " date_added = '".escape_string($oshva[date_added])."', "." customer_notified = '".$oshva[customer_notified]."', ". " comments = '".escape_string($oshva[comments])."', "." admin_users_id = '".$oshva[admin_users_id]."', "." crm_username = '".$oshva[crm_username]."'";

					//$ins_osh_rst = tep_db_query($ins_osh_query);
					//$new_orders_status_history_id = tep_db_insert_id();
					//$del_osh_query = "DELETE FROM orders_status_history WHERE orders_status_history_id = '".$new_orders_status_history_id."'";
					//insert_query_log($ins_osh_query);delete_query_log($del_osh_query);
					
				}
				
				foreach($cusval['orders_total'] as $otke=>$otva) {
					
					echo "<br>";
					echo $ins_ot_query = "INSERT INTO orders_total SET " .$order_id_entry_string. " title = '".escape_string($otva[title])."', ". " text = '".escape_string($otva[text])."', "." value = '".$otva[value]."', ". " class = '".$otva['class']."', "." sort_order = '".$otva[sort_order]."'";
					//$ins_ot_rst = tep_db_query($ins_ot_query);
					//$new_orders_total_id = tep_db_insert_id();
					//$del_ot_query = "DELETE FROM orders_total WHERE orders_total_id = '".$new_orders_total_id."'";
					//insert_query_log($ins_ot_query);delete_query_log($del_ot_query);
						
					
				}
				
				foreach($cusval['xeros'] as $xeke=>$xeva) {
					
					echo "<br>";
					echo $ins_xe_query = "INSERT INTO xero SET " .$order_id_entry_string. " xero_request = '".escape_string($xeva[xero_request])."', ". " xero_response = '".escape_string($xeva[xero_response])."', "." date_added = '".escape_string($xeva[date_added])."'";
					//$ins_xe_rst = tep_db_query($ins_xe_query);
					//$new_xero_id = tep_db_insert_id();
					//$del_xe_query = "DELETE FROM xero WHERE xero_id = '".$new_xero_id."'";
					//insert_query_log($ins_xe_query);delete_query_log($del_xe_query);
										
				}
				
				foreach($cusval['eparcel'] as $epke=>$epva) {
					
					if($cusval[orders_id] == $epva[orders_id]) {
						
						echo "<br>";
						echo $ins_ec_query = "INSERT INTO eparcel_consignment SET " .$order_id_entry_string. " consignment_id = '".escape_string($epva[consignment_id])."', ". " consignment_number = '".escape_string($epva[consignment_number])."', "." manifest_id = '".escape_string($epva[manifest_id])."', "." date_created = '".escape_string($epva[date_created])."', "." charge_code = '".escape_string($epva[charge_code])."', "." email_notification = '".escape_string($epva[email_notification])."', "." international_delivery = '".escape_string($epva[international_delivery])."', "." signature_required = '".escape_string($epva[signature_required])."', "." post_charge_to_account = '".escape_string($epva[post_charge_to_account])."', "." dangerous_goods = '".escape_string($epva[dangerous_goods])."', "." profile_id = '".escape_string($epva[profile_id])."', "." product_code = '".escape_string($epva[product_code])."', "." delivery_part_consignment = '".escape_string($epva[delivery_part_consignment])."', "." number_of_articles = '".escape_string($epva[number_of_articles])."', "." multipart_consignment = '".escape_string($epva[multipart_consignment])."', "." status = '".escape_string($epva[status])."'";
						
						//echo $ins_ec_query;
						//$ins_ec_rst = tep_db_query($ins_ec_query);
						//$new_consignment_id = tep_db_insert_id();
						//$del_ec_query = "DELETE FROM eparcel_consignment WHERE consignment_id = '".$new_consignment_id."'";
						//insert_query_log($ins_ec_query);delete_query_log($del_ec_query);
						
						echo "<br>";
						echo $ins_ea_query = "INSERT INTO eparcel_article SET article_id = '".escape_string($epva[article_id])."', consignment_id = '".escape_string($epva[consignment_id])."', ". " article_number = '".escape_string($epva[article_number])."', "." barcode_number = '".escape_string($epva[barcode_number])."', "." length = '".escape_string($epva[length])."', "." width = '".escape_string($epva[width])."', "." height = '".escape_string($epva[height])."', "." actual_weight = '".escape_string($epva[actual_weight])."', "." cubic_weight = '".escape_string($epva[cubic_weight])."', "." article_description = '".escape_string($epva[article_description])."', "." transit_cover = '".escape_string($epva[transit_cover])."', "." transit_cover_amount = '".escape_string($epva[transit_cover_amount])."'";
						
						//echo $ins_ea_query;
						//$ins_ea_rst = tep_db_query($ins_ea_query);
						//$new_article_id = tep_db_insert_id();
						//$del_ea_query = "DELETE FROM eparcel_article WHERE article_id = '".$new_article_id."'";
						//insert_query_log($ins_ea_query);delete_query_log($del_ea_query);
						
						echo "<br>";
						echo $ins_em_query = "INSERT INTO eparcel_manifest SET article_id = '".escape_string($epva[article_id])."', consignment_id = '".escape_string($epva[consignment_id])."', ". " article_number = '".escape_string($epva[article_number])."', "." barcode_number = '".escape_string($epva[barcode_number])."', "." length = '".escape_string($epva[length])."', "." width = '".escape_string($epva[width])."', "." height = '".escape_string($epva[height])."', "." actual_weight = '".escape_string($epva[actual_weight])."', "." cubic_weight = '".escape_string($epva[cubic_weight])."', "." article_description = '".escape_string($epva[article_description])."', "." transit_cover = '".escape_string($epva[transit_cover])."', "." transit_cover_amount = '".escape_string($epva[transit_cover_amount])."'";
						//echo $ins_em_query;
						//$ins_em_rst = tep_db_query($ins_em_query);
						//$new_manifest_id = tep_db_insert_id();
						//$del_em_query = "DELETE FROM eparcel_manifest WHERE manifest_id = '".$new_manifest_id."'";
						//insert_query_log($ins_em_query);delete_query_log($del_em_query);
						
					}
				}
				
				echo "<br>";
												
			}
			
			
		}

		exit;
		
		$msg = "Response Messages: <br>";
		echo $_POST["customers_email_address"];
		
		$cus = tep_db_query("SELECT * FROM customers WHERE customers_email_address='".$_POST["customers_email_address"]."'");
		if(mysql_num_rows($cus)>0) {
			$msg .= "Customer with email address ". $_POST["customers_email_address"] . " already exists in NBI.";
		} else {
			$msg .= "Customer with email address ". $_POST["customers_email_address"] . " inserted into NBI successfully.";
		}
		
		echo $msg;
	
 // }
 
 function escape_string($str) {
	return trim(urldecode(htmlspecialchars_decode(mysql_real_escape_string($str))));
 }

function GUID1()
{
    if (function_exists('com_create_guid') === true)
    {
        return strtolower(trim(com_create_guid(), '{}'));
    }

    return strtolower(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));
}

function log_message($msg) {
	$fp = fopen('data.txt', 'a');					
	$string = $msg . "\r\n";					
	fwrite($fp, $string);					
	fclose($fp);
}

 function insert_query_log($query) {
	$fp = fopen('insert_data.txt', 'a');					
	$string = $query . "\r\n";					
	fwrite($fp, $string);					
	fclose($fp);
}

function delete_query_log($query) {
	$fp = fopen('delete_data.txt', 'a');					
	$string = $query . "\r\n";					
	fwrite($fp, $string);					
	fclose($fp);
}

function file_link_log($links) {
	$fp = fopen('file_link_data.txt', 'a');					
	$string = $links . "\r\n";					
	fwrite($fp, $string);					
	fclose($fp);
}

 function check_customer_id($cusid) {
	$qry = tep_db_query("SELECT customers_id FROM customers WHERE customers_id = '".$cusid."'");
	if(tep_db_num_rows($qry)>0) {
		$arr = tep_db_fetch_array($qry);		
		return $arr["customers_id"];
	} else { return false; }
 }
 
 function check_cre_email($email) {
	$qry = tep_db_query("SELECT customers_id FROM customers WHERE customers_email_address = '".$email."'");
	if(tep_db_num_rows($qry)>0) {
		$arr = tep_db_fetch_array($qry);		
		return $arr["customers_id"];
	} else { return false; }
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
function check_crm_pro_row($uniqueId,$link_crm) {
	$qry = mysql_query("SELECT id from products WHERE id='".$uniqueId."'", $link_crm);
	return mysql_num_rows($qry);
}

function next_auto_increment($table) {
	
	$qry = tep_db_query("SHOW TABLE STATUS LIKE '".$table."'");    
	$row = tep_db_fetch_array($qry);
	return $row['Auto_increment'];
	
}


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