<?php
require('includes/configure.php');
require('includes/filenames.php');
require('includes/database_tables.php');
require('includes/functions/database.php');
require('includes/functions/general.php');
require('includes/functions/html_output.php');
tep_db_connect();

$data = array();
$data = $_POST;


$cus_qry = tep_db_query("select customers_id from customers WHERE customers_email_address='".$data['email_address']."'");
if(tep_db_num_rows($cus_qry)>0) {
	
	$cus_arr = tep_db_fetch_array($cus_qry);
	$final_customers_id = $cus_arr["customers_id"];
	
} else {
	
	/*$next_insert_query = tep_db_query("SHOW TABLE STATUS LIKE 'customers'");    
	$row = tep_db_fetch_array($next_insert_query);
	$final_customers_id = $row['Auto_increment'];*/
	
	$cus_ins = tep_db_query("INSERT INTO customers SET customers_source='dsi', customers_email_address='".$data['email_address']."', customers_firstname='".$data['firstname']."', 
				customers_lastname='".$data['lastname']."', customers_gender='".$data['gender']."',customers_password='".tep_encrypt_password($data['password'])."', customers_newsletter='".$data['newsletter']."'");
	$final_customers_id = tep_db_insert_id();
	$sql_data_array = array('customers_id' => $final_customers_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_telephone' => $telephone,
                              'entry_fax' => $fax,
                              'entry_email_address' => $email_address,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
          $sql_data_array['entry_company_tax_id'] = $company_tax_id;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }
	  
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$final_customers_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$final_customers_id . "', '0', now())");
	  
	  
}

$return["final_customers_id"] = $final_customers_id;
echo json_encode($return);
exit;
?>