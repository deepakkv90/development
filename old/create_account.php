<?php
/*
  $Id: create_account.php,v 1.4 2004/09/25 15:09:15 DMG Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Chain Reaction Works, Inc.

  Copyright &copy; 2003-2007
*/

  require('includes/application_top.php');

 if(B2B_ALLOW_CREATE_ACCOUNT=='false') {
   tep_redirect(tep_href_link(FILENAME_LOGIN));
 }

  // needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
  
 
 function dsi_nbi_account_sync($data_arr)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	$url = NBI_ACCOUNT_SYNC_URL;
	$data_arr["src_url"] = HTTP_SERVER;
	$data_arr["src"] = "npi";
	$data = $data_arr;	
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	
	curl_setopt($ch, CURLOPT_URL,$url);	
	$result = curl_exec ($ch);
	curl_close ($ch);
	//var_dump($result);
	
	//echo $result;
	
	//print_r(json_decode($result));
	
	//print_r(json_decode($result->final_customers_id));
	
	return json_decode($result);		
}
 
 
  $process = false;  // used by the state routine
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $process = true;
	
	
	  
	  
    if (ACCOUNT_GENDER == 'true') {
      if (isset($_POST['gender'])) {
        $gender = tep_db_prepare_input($_POST['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $lastname = tep_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
    $email_address = strtolower(tep_db_prepare_input($_POST['email_address']));
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
    if (ACCOUNT_COMPANY == 'true') $company_tax_id = tep_db_prepare_input($_POST['company_tax_id']);
    $street_address = tep_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
    $postcode = tep_db_prepare_input($_POST['postcode']);
    $city = tep_db_prepare_input($_POST['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($_POST['state']);
      if (isset($_POST['zone_id'])) {
        $zone_id = tep_db_prepare_input($_POST['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $country = tep_db_prepare_input($_POST['country']);
    $telephone = tep_db_prepare_input($_POST['telephone']);
    $fax = tep_db_prepare_input($_POST['fax']);
    if (isset($_POST['newsletter'])) {
      $newsletter = tep_db_prepare_input($_POST['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = tep_db_prepare_input($_POST['password']);
    $confirmation = tep_db_prepare_input($_POST['confirmation']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "'");
      $check_email = tep_db_fetch_array($check_email_query);
      // BOF: daithik - PWA
      //      if ($check_email['total'] > 0) {
      //        $error = true;
      //        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      //      }
      if ($check_email['total'] > 0) {  //PWA delete account
        $get_customer_info = tep_db_query("select customers_id, customers_email_address, purchased_without_account from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "'");
        $customer_info = tep_db_fetch_array($get_customer_info);
        $customer_id = $customer_info['customers_id'];
        $customer_email_address = strtolower($customer_info['customers_email_address']);
        $customer_pwa = $customer_info['purchased_without_account'];
        if ($customer_pwa !='1') {
          $error = true;

          $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
        } else {
          tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $customer_id . "'");
          tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
          tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_id . "'");
          tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $customer_id . "'");
          tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $customer_id . "'");
          tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . $customer_id . "'");
        }
      }
      // EOF: daithik - PWA
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
      $check = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check['total'] > 0);
      // State selection bug fix applied 10/1/2004 DMG
      if ($entry_state_has_zones == true) {
        $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' OR zone_code = '" . tep_db_input($state) . "')");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone = tep_db_fetch_array($zone_query);
          $zone_id = $zone['zone_id'];
        } else {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

  if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
    if (defined('VVC_CREATE_ACCOUNT_ON_OFF') && VVC_CREATE_ACCOUNT_ON_OFF == 'On') {
      $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . "  where oscsid = '" . tep_session_id() . "'");
      $code_array = tep_db_fetch_array($code_query);
      $code = $code_array['code'];
      tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
      $user_entered_code = $_POST['visual_verify_code'];
      if (!(strcmp($user_entered_code, $code) == 0)) {    //make the check case sensitive
        $error = true;
        $messageStack->add('create_account', VISUAL_VERIFY_CODE_ENTRY_ERROR);
      }
    }
  }
    // RCI code start
    echo $cre_RCI->get('createaccount', 'check', false);
    // RCI code end    

    if ($error == false) {

      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_newsletter' => $newsletter,
                              'customers_password' => tep_encrypt_password($password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
      
      if (B2B_REQUIRE_ACCOUNT_APPROVAL == 'false') $sql_data_array['customers_account_approval'] = 'Approve';
      if (ACCOUNT_EMAIL_CONFIRMATION == 'false' ) $sql_data_array['customers_validation'] = '1';

      
	  if(NBI_SYNC==true) {
			$nbi_sync = dsi_nbi_account_sync($_POST);
			
			if(!empty($nbi_sync->final_customers_id)) $sql_data_array['customers_id'] = $nbi_sync->final_customers_id;
	  } 
	  
	  //print_r($sql_data_array);
	  //exit;
	  
	  tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $_SESSION['customer_id'] = tep_db_insert_id();

      // RCI code start
      echo $cre_RCI->get('createaccount', 'submit', false);
      // RCI code end

      $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
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

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$_SESSION['customer_id'] . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }

      // If we are not doing the email confirmation, then log the customer in
      $noloagin = 'false';

      if(B2B_REQUIRE_ACCOUNT_APPROVAL == 'true'){
        $noloagin = 'true';
      }

      if ( ACCOUNT_EMAIL_CONFIRMATION == 'false' ) {
        $_SESSION['customer_first_name'] = $firstname;
        $_SESSION['customer_default_address_id'] = $address_id;
        $_SESSION['customer_country_id'] = $country;
        $_SESSION['customer_zone_id'] = $zone_id;
      } else {  // we need to build the data to do the verification
        $noloagin = 'true';
        $Pass = '';
        $Pass_neu = '';
        $pw="ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789";
        srand((double)microtime()*1000000);
        for ($i=1;$i<=5;$i++){
          $Pass .= $pw{rand(0,strlen($pw)-1)};
         }
        $pw1="ABCDEFGHJKMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz0123456789";
        srand((double)microtime()*1000000);
        for ($i=1;$i<=5;$i++){
          $Pass_neu .= $pw1{rand(0,strlen($pw1)-1)};
         }
        $id = $_SESSION['customer_id'];
        tep_db_query('update customers set customers_validation_code = "' . $Pass . $Pass_neu . '" where customers_id = "' . $id . '"');
       }
        // restore cart contents
        $cart->restore_contents();
        
		$state_name = $state;
		
		if (isset($country) && tep_not_null($country)) {
		  $country_name = tep_get_country_name($country);
	
		  if (isset($zone_id) && tep_not_null($zone_id)) {
			$state_name = tep_get_zone_code($country, $zone_id, $state);
		  }
		} elseif (isset($country) && tep_not_null($country)) {
		  $country_name = tep_output_string_protected($country);
		} else {
		  $country_name = '';
		}
	
        $name = $firstname . ' ' . $lastname;

      if (ACCOUNT_GENDER == 'true') {
         if ($gender == 'm') {
           $email_text = sprintf(EMAIL_GREET_MR, $lastname);
         } else {
           $email_text = sprintf(EMAIL_GREET_MS, $lastname);
         }
      } else {
        $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
      }
      if (EMAIL_USE_HTML == 'true') {
        $formated_store_owner_email = '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">' . STORE_OWNER . ': ' . STORE_OWNER_EMAIL_ADDRESS . '</a>';
      } else {
        $formated_store_owner_email = STORE_OWNER . ': ' . STORE_OWNER_EMAIL_ADDRESS;
      }
	  
      $email_text .= EMAIL_WELCOME;
	  
	  //users details
	  $email_text .= EMAIL_TEXT_YOUR_DETAILS . "\n\n" . EMAIL_TEXT_USERNAME . $email_address . "\n" . EMAIL_TEXT_PASSWORD . EMAIL_TEXT_PASSWORD_TEXT."\n";
	  if($company!="") {
	  	$email_text .= EMAIL_TEXT_COMPANY . $company."\n";
	  }
	  $email_text .= EMAIL_TEXT_FIRSTNAME . $firstname . "\n" . EMAIL_TEXT_LASTNAME . $lastname . "\n" . EMAIL_TEXT_PHONE . $telephone ."\n" . EMAIL_TEXT_ADDRESS . $street_address . ", " . $city . ", " . $state_name . ", " . $country_name . " - " . $postcode . "\n\n";
	   
	  
      $email_text .= EMAIL_TEXT . EMAIL_CONTACT . $formated_store_owner_email . "\n\n" . EMAIL_WARNING . $formated_store_owner_email . "\n\n".EMAIL_TEXT_FOOTER_UPDATED."\n\n";
	  
      if ( ACCOUNT_EMAIL_CONFIRMATION == 'true' ) {
        $email_text .=  "\n" . MAIL_VALIDATION . "\n" . '<a href="' . str_replace('&amp;', '&', tep_href_link('pw.php', 'action=reg&pass=' . $Pass . $Pass_neu . '&verifyid=' . $_SESSION['customer_id'], 'SSL', false)) . '">' . VALIDATE_YOUR_MAILADRESS . '</a>' . "\n" . "\n" . '(' . SECOND_LINK . ' ' . str_replace('&amp;', '&', tep_href_link('pw.php', 'action=reg&pass=' . $Pass . $Pass_neu . '&verifyid=' . $_SESSION['customer_id'], 'SSL', false)) . ' )' . "\n" . "\n". OR_VALIDATION_CODE . $Pass . $Pass_neu . "\n" . "\n";
        $noloagin == 'true'; // logout customer to varify his email ID.
      }
  //adds text about being validated by admin
   if($noloagin =='true')
   {
     $email_text .=  "\n".MAIL_VALIDATION_B2B;
   }

// ICW - CREDIT CLASS CODE BLOCK ADDED  ******************************************************* BEGIN
  if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
    $coupon_code = create_coupon_code();
    $insert_query = tep_db_query("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $coupon_code . "', 'G', '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "', now())");
    $insert_id = tep_db_insert_id();
    $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . $email_address . "', now() )");

    $email_text .= sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) . "\n\n" .
                   sprintf(EMAIL_GV_REDEEM, $coupon_code) . "\n\n" .
                   EMAIL_GV_LINK . tep_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code,'NONSSL', false) .
                   "\n\n";
  }
  if (NEW_SIGNUP_DISCOUNT_COUPON != '') {
    $coupon_code = NEW_SIGNUP_DISCOUNT_COUPON;
    $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_code = '" . $coupon_code . "'");
    $coupon = tep_db_fetch_array($coupon_query);
    $coupon_id = $coupon['coupon_id'];
    $coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $coupon_id . "' and language_id = '" . (int)$languages_id . "'");
    $coupon_desc = tep_db_fetch_array($coupon_desc_query);
    $insert_query = tep_db_query("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $coupon_id ."', '0', 'Admin', '" . $email_address . "', now() )");
    $email_text .= EMAIL_COUPON_INCENTIVE_HEADER .  "\n" .
                   sprintf("%s", $coupon_desc['coupon_description']) ."\n\n" .
                   sprintf(EMAIL_COUPON_REDEEM, $coupon['coupon_code']) . "\n\n" .
                   "\n\n";



  }
	//    $email_text .= EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
	// ICW - CREDIT CLASS CODE BLOCK ADDED  ******************************************************* END
      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      // VJ member approval begin
      // admin email notification
	  // $admin_email_text .= ADMIN_EMAIL_WELCOME . ADMIN_EMAIL_TEXT . EMAIL_WARNING;
	  // remove hard coded admin notification for now.
	  // tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $admin_email_text, $name, $email_address, '');
      // VJ member approval end
      
      $customers_id = $_SESSION['customer_id'];
      
     
      if (isset($noloagin) && $noloagin == 'true'){
		//if account must be approved kill the session info so they can't get to the any place in the catalog        
        unset($_SESSION['customer_id']);
        unset($_SESSION['customer_default_address_id']);
        unset($_SESSION['customer_first_name']);
        // Eversun mod for sppc and qty price breaks
        unset($_SESSION['sppc_customer_group_id']);
        unset($_SESSION['sppc_customer_group_show_tax']);
        unset($_SESSION['sppc_customer_group_tax_exempt']);
        // Eversun mod for sppc and qty price breaks
        unset($_SESSION['customer_country_id']);
        unset($_SESSION['customer_zone_id']);
        unset($_SESSION['comments']);
        //ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
        unset($_SESSION['gv_id']);
        unset($_SESSION['cc_id']);
        //ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik
        $cart->reset();
      }
      
      if(B2B_REQUIRE_ACCOUNT_APPROVAL == 'true') {
        tep_redirect(tep_href_link('pw.php', 'verifyid=' . $customers_id . '&pass=&b2bwarning=1', 'SSL'));
      } else {
        tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
      }
    }
  } else {
    // check to see if someone is already logged in
    if ( isset($_SESSION['customer_id']) ) {
      // force a log off
      unset($_SESSION['customer_id']);
      unset($_SESSION['customer_default_address_id']);
      unset($_SESSION['customer_first_name']);
      // Eversun mod for sppc and qty price breaks
      unset($_SESSION['sppc_customer_group_id']);
      unset($_SESSION['sppc_customer_group_show_tax']);
      unset($_SESSION['sppc_customer_group_tax_exempt']);
      // Eversun mod for sppc and qty price breaks
      unset($_SESSION['customer_country_id']);
      unset($_SESSION['customer_zone_id']);
      unset($_SESSION['comments']);
      //ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
      unset($_SESSION['gv_id']);
      unset($_SESSION['cc_id']);
      //ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik
      $cart->reset();
    }
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));

  $content = CONTENT_CREATE_ACCOUNT;
  $javascript = 'form_check.js.php';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>