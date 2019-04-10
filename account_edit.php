<?php
/*
  $Id: account_edit.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

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

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
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
      $newsletter = 0;
    }
    $password = tep_db_prepare_input($_POST['password']);
    $confirmation = tep_db_prepare_input($_POST['confirmation']);
	
    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (!checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR);
    }

    if (!tep_validate_email($email_address)) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$_SESSION['customer_id'] . "'");
    $check_email = tep_db_fetch_array($check_email_query);
    if ($check_email['total'] > 0) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_CITY_ERROR);
    }
/*
    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_COUNTRY_ERROR);
    }
*/
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

          $messageStack->add('account_edit', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('account_edit', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_TELEPHONE_NUMBER_ERROR);
    }

	if(!empty($password)) {
		if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
		  $error = true;
	
		  $messageStack->add('account_edit', ENTRY_PASSWORD_ERROR);
		} elseif ($password != $confirmation) {
		  $error = true;
	
		  $messageStack->add('account_edit', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
		}
	}
	
    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
							  'customers_newsletter' => $newsletter);
							  
	  if(isset($_POST['password']) && !empty($_POST['password'])) {
	  	$sql_data_array['customers_password'] = tep_encrypt_password($password);
	  }
	  
      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");

      tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");

      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_telephone' => $telephone,
                              'entry_fax' => $fax,
                              'entry_email_address' => $email_address);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;         
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

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$_SESSION['customer_default_address_id'] . "'");

	////////////////////// Added For CRM Synchronization ////////////////////////
	$cus_cre_id = (int)$_SESSION['customer_id'];
    $address_id = (int)$_SESSION['customer_default_address_id']  ;
	//---------------------------------------------------------------------------

	// reset the session variables
      $_SESSION['customer_first_name'] = $firstname;

      $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');

      tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    }
  }

  //$account_query = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");

  //$account_query = tep_db_query("select c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, ca.entry_telephone, ca.entry_fax from " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ca where c.customers_id = '" . (int)$_SESSION['customer_id'] . "' && ca.customers_id = '" . (int)$_SESSION['customer_id'] . "'");
   
  $account_query = tep_db_query("select c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, c.customers_default_address_id, c.customers_newsletter, ca.* from " . TABLE_CUSTOMERS . " c LEFT JOIN " . TABLE_ADDRESS_BOOK . " ca ON  c.customers_default_address_id = ca.address_book_id WHERE c.customers_id = '" . (int)$_SESSION['customer_id'] . "'");
  
  $account = tep_db_fetch_array($account_query);
  
  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));

  $content = CONTENT_ACCOUNT_EDIT;
  $javascript = 'form_check.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
