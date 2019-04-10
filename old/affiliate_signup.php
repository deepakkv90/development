<?php
/*
  $Id: affiliate_signup.php,v 1.1.1.1 2004/03/04 23:37:55 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_SIGNUP);

//intilize variables
$affiliate_ref = '';

//Intialize error handling variables

if (empty($entry_gender_error)) {
$entry_gender_error = false;
}
if (empty($entry_firstname_error)) {
$entry_firstname_error = false;
}
if (empty($entry_lastname_error)) {
$entry_lastname_error = false;
}
if (empty($entry_date_of_birth_error)) {
$entry_date_of_birth_error = false;
}
if (empty($entry_email_address_error)) {
$entry_email_address_error = false;
}
if (empty($entry_email_address_error)) {
$entry_email_address_error = false;
}
if (empty($entry_email_address_check_error)) {
$entry_email_address_check_error = false;
}

if (empty($entry_street_address_error)) {
$entry_street_address_error = false;
}

if (empty($entry_street_address_error)) {
$entry_street_address_error = false;
}

if (empty($entry_post_code_error)) {
$entry_post_code_error = false;
}

if (empty($entry_city_error)) {
$entry_city_error = false;
}

if (empty($entry_country_error)) {
$entry_country_error = false;
}

if (empty($entry_state_error)) {
$entry_state_error = false;
}

if (empty($entry_telephone_error)) {
$entry_telephone_error = false;
}

if (empty($entry_password_error)) {
$entry_password_error = false;
}

if (empty($entry_agb_error)) {
$entry_agb_error = false;
}

// the indexes of the array must exsist before they can be tested this creates them
//if there is  only the country feild present


if (isset($affiliate)){
// first we test to se if some common items have data

if (!isset($_SESSION['affiliate_id'])){

 if (!isset($affiliate['affiliate_firstname'])){
  //if empty then we build an dummy array
      $affiliate = array('affiliate_firstname' => '',
                              'affiliate_lastname' => '',
                              'affiliate_email_address' => '',
                              'affiliate_dob' => '',
                              'affiliate_company' => '',
                              'affiliate_company_taxid' => '',
                              'affiliate_payment_check' => '',
                              'affiliate_payment_paypal' => '',
                              'affiliate_payment_bank_name' => '',
                              'affiliate_payment_bank_branch_number' => '',
                              'affiliate_payment_bank_swift_code' => '',
                              'affiliate_payment_bank_account_name' => '',
                              'affiliate_payment_bank_account_number' => '',
                              'affiliate_street_address' => '',
                              'affiliate_postcode' => '',
                              'affiliate_suburb' => '',
                              'affiliate_zone_id' => '',
                              'affiliate_city' => '',
                              'affiliate_state' => '',
                              'affiliate_country_id' => 'STORE_COUNTRY',
                              'affiliate_telephone' =>'',
                              'affiliate_fax' => '',
                              'affiliate_homepage' => '',
                              'affiliate_password' => '',
                              'affiliate_agb' => '',
                              'affiliate_newsletter' => '');

   }

}else{
// there is no $affiliate or it is not an array so we build a dummy variable

 if (!isset($affiliate['affiliate_firstname'])){
  //if empty then we build an dummy array
      $affiliate = array('affiliate_firstname' => '',
                              'affiliate_lastname' => '',
                              'affiliate_email_address' => '',
                              'affiliate_dob' => '',
                              'affiliate_company' => '',
                              'affiliate_company_taxid' => '',
                              'affiliate_payment_check' => '',
                              'affiliate_payment_paypal' => '',
                              'affiliate_payment_bank_name' => '',
                              'affiliate_payment_bank_branch_number' => '',
                              'affiliate_payment_bank_swift_code' => '',
                              'affiliate_payment_bank_account_name' => '',
                              'affiliate_payment_bank_account_number' => '',
                              'affiliate_street_address' => '',
                              'affiliate_postcode' => '',
                              'affiliate_suburb' => '',
                              'affiliate_zone_id' => '',
                              'affiliate_state' => '',
                              'affiliate_city' => '',
                              'affiliate_country_id' => 'STORE_COUNTRY',
                              'affiliate_telephone' =>'',
                              'affiliate_fax' => '',
                              'affiliate_homepage' => '',
                              'affiliate_password' => '',
                              'affiliate_agb' => '',
                              'affiliate_newsletter' => '');

   }
}

}

  if (isset($_POST['action'])) {
    $a_gender = isset($_POST['a_gender']) ? tep_db_prepare_input($_POST['a_gender']) : '';
    $a_firstname = isset($_POST['a_firstname']) ? tep_db_prepare_input($_POST['a_firstname']) : '';
    $a_lastname = isset($_POST['a_lastname']) ? tep_db_prepare_input($_POST['a_lastname']) : '';
    $a_dob = isset($_POST['a_dob']) ? tep_db_prepare_input($_POST['a_dob']) : '';
    $a_email_address = isset($_POST['a_email_address']) ? tep_db_prepare_input($_POST['a_email_address']) : '';
    $a_company = isset($_POST['a_company']) ? tep_db_prepare_input($_POST['a_company']) : '';
    $a_company_taxid = isset($_POST['a_company_taxid']) ? tep_db_prepare_input($_POST['a_company_taxid']) : '';
    $a_payment_check = isset($_POST['a_payment_check']) ? tep_db_prepare_input($_POST['a_payment_check']) : '';
    $a_payment_paypal = isset($_POST['a_payment_paypal']) ? tep_db_prepare_input($_POST['a_payment_paypal']) : '';
    $a_payment_bank_name = isset($_POST['a_payment_bank_name']) ? tep_db_prepare_input($_POST['a_payment_bank_name']) : '';
    $a_payment_bank_branch_number = isset($_POST['a_payment_bank_branch_number']) ? tep_db_prepare_input($_POST['a_payment_bank_branch_number']) : '';
    $a_payment_bank_swift_code = isset($_POST['a_payment_bank_swift_code']) ? tep_db_prepare_input($_POST['a_payment_bank_swift_code']) : '';
    $a_payment_bank_account_name = isset($_POST['a_payment_bank_account_name']) ? tep_db_prepare_input($_POST['a_payment_bank_account_name']) : '';
    $a_payment_bank_account_number = isset($_POST['a_payment_bank_account_number']) ? tep_db_prepare_input($_POST['a_payment_bank_account_number']) : '';
    $a_street_address = isset($_POST['a_street_address']) ? tep_db_prepare_input($_POST['a_street_address']) : '';
    $a_suburb = isset($_POST['a_suburb']) ? tep_db_prepare_input($_POST['a_suburb']) : '';
    $a_postcode = isset($_POST['a_postcode']) ? tep_db_prepare_input($_POST['a_postcode']) : '';
    $a_city = isset($_POST['a_city']) ? tep_db_prepare_input($_POST['a_city']) : '';
    $a_country = isset($_POST['a_country']) ? tep_db_prepare_input($_POST['a_country']) : '';
    $a_state = isset($_POST['a_state']) ? tep_db_prepare_input($_POST['a_state']) : '';
    $a_telephone = isset($_POST['a_telephone']) ? tep_db_prepare_input($_POST['a_telephone']) : '';
    $a_fax = isset($_POST['a_fax']) ? tep_db_prepare_input($_POST['a_fax']) : '';
    $a_homepage = isset($_POST['a_homepage']) ? tep_db_prepare_input($_POST['a_homepage']) : '';
    $a_password = isset($_POST['a_password']) ? tep_db_prepare_input($_POST['a_password']) : '';
    $a_confirmation =  isset($_POST['a_confirmation']) ? tep_db_prepare_input($_POST['a_confirmation']) : '';
    $a_agb =  isset($_POST['a_agb']) ? tep_db_prepare_input($_POST['a_agb']) : '';

// optional items that may not get posted from form
//if (!isset($_POST['a_newsletter'])){
// $a_newsletter = tep_db_prepare_input($_POST['a_newsletter']);
//}else{
// $a_newsletter = '0';
//}

if (!isset($_POST['a_zone_id'])){
 $a_zone_id = tep_db_prepare_input($_POST['a_zone_id']);
}else{
 $a_zone_id = '0';
}

    $error = false; // reset error flag

    if (ACCOUNT_GENDER == 'true') {
      if (($a_gender == 'm') || ($a_gender == 'f')) {
        $entry_gender_error = false;
      } else {
        $error = true;
        $entry_gender_error = true;
      }
    }

    if (strlen($a_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;
      $entry_firstname_error = true;
    } else {
      $entry_firstname_error = false;
    }

    if (strlen($a_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;
      $entry_lastname_error = true;
    } else {
      $entry_lastname_error = false;
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(tep_date_raw($a_dob), 4, 2), substr(tep_date_raw($a_dob), 6, 2), substr(tep_date_raw($a_dob), 0, 4))) {
        $entry_date_of_birth_error = false;
      } else {
        $error = true;
        $entry_date_of_birth_error = true;
      }
    }

    if (strlen($a_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;
      $entry_email_address_error = true;
    } else {
      $entry_email_address_error = false;
    }

    if (!tep_validate_email($a_email_address)) {
      $error = true;
      $entry_email_address_check_error = true;
    } else {
      $entry_email_address_check_error = false;
    }

    if (strlen($a_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;
      $entry_street_address_error = true;
    } else {
      $entry_street_address_error = false;
    }

    if (strlen($a_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;
      $entry_post_code_error = true;
    } else {
      $entry_post_code_error = false;
    }

    if (strlen($a_city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;
      $entry_city_error = true;
    } else {
      $entry_city_error = false;
    }

    if (!$a_country) {
      $error = true;
      $entry_country_error = true;
    } else {
      $entry_country_error = false;
    }

    if (ACCOUNT_STATE == 'true') {
      if ($entry_country_error) {
        $entry_state_error = true;
      } else {
        $a_zone_id = 0;
        $entry_state_error = false;
        $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($a_country) . "'");
        $check_value = tep_db_fetch_array($check_query);
        $entry_state_has_zones = ($check_value['total'] > 0);
        if ($entry_state_has_zones) {
          $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($a_country) . "' and zone_name = '" . tep_db_input($a_state) . "'");
          if (tep_db_num_rows($zone_query) == 1) {
            $zone_values = tep_db_fetch_array($zone_query);
            $a_zone_id = $zone_values['zone_id'];
          } else {
            $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($a_country) . "' and zone_code = '" . tep_db_input($a_state) . "'");
            if (tep_db_num_rows($zone_query) == 1) {
              $zone_values = tep_db_fetch_array($zone_query);
              $a_zone_id = $zone_values['zone_id'];
            } else {
              $error = true;
              $entry_state_error = true;
            }
          }
        } else {
          if (!$a_state) {
            $error = true;
            $entry_state_error = true;
          }
        }
      }
    }

    if (strlen($a_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;
      $entry_telephone_error = true;
    } else {
      $entry_telephone_error = false;
    }

    $passlen = strlen($a_password);
    if ($passlen < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;
      $entry_password_error = true;
    } else {
      $entry_password_error = false;
    }

    if ($a_password != $a_confirmation) {
      $error = true;
      $entry_password_error = true;
    }

    $check_email = tep_db_query("select affiliate_email_address from " . TABLE_AFFILIATE . " where affiliate_email_address = '" . tep_db_input($a_email_address) . "'");
    if (tep_db_num_rows($check_email)) {
      $error = true;
      $entry_email_address_exists = true;
    } else {
      $entry_email_address_exists = false;
    }


    // Check Suburb
    $entry_suburb_error = false;

    // Check Fax
    $entry_fax_error = false;

    if (!affiliate_check_url($a_homepage)) {
      $error = true;
      $entry_homepage_error = true;
    } else {
      $entry_homepage_error = false;
    }

    if (!$a_agb) {
    $error=true;
    $entry_agb_error=true;
    }

    // Check Company
    $entry_company_error = false;
    $entry_company_taxid_error = false;

  // Check Newsletter
    $entry_newsletter_error = false;
    // Check Payment
    $entry_payment_check_error = false;
    $entry_payment_paypal_error = false;
    $entry_payment_bank_name_error = false;
    $entry_payment_bank_branch_number_error = false;
    $entry_payment_bank_swift_code_error = false;
    $entry_payment_bank_account_name_error = false;
    $entry_payment_bank_account_number_error = false;
    
    //VISUAL VERIFY CODE start
    if (!isset($_SESSION['affiliate_id'])) {
    $affiliate_vvc_error = false;
    if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
    if (defined('VVC_CREATE_AFFILIATE_ACCOUNT_ON_OFF') && VVC_CREATE_AFFILIATE_ACCOUNT_ON_OFF == 'On'){
    $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
    $code_array = tep_db_fetch_array($code_query);
    tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
    if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
         isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
         strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {   //make the check case sensitive
         //match is good, no message or error.
         } else {
        $affiliate_vvc_error = true;
        $error = true;
    }
  }
}
}
//VISUAL VERIFY CODE stop

    if (!$error) {

      $sql_data_array = array('affiliate_firstname' => $a_firstname,
                              'affiliate_lastname' => $a_lastname,
                              'affiliate_email_address' => $a_email_address,
                              'affiliate_payment_check' => $a_payment_check,
                              'affiliate_payment_paypal' => $a_payment_paypal,
                              'affiliate_payment_bank_name' => $a_payment_bank_name,
                              'affiliate_payment_bank_branch_number' => $a_payment_bank_branch_number,
                              'affiliate_payment_bank_swift_code' => $a_payment_bank_swift_code,
                              'affiliate_payment_bank_account_name' => $a_payment_bank_account_name,
                              'affiliate_payment_bank_account_number' => $a_payment_bank_account_number,
                              'affiliate_street_address' => $a_street_address,
                              'affiliate_postcode' => $a_postcode,
                              'affiliate_city' => $a_city,
                              'affiliate_country_id' => $a_country,
                              'affiliate_telephone' => $a_telephone,
                              'affiliate_fax' => $a_fax,
                              'affiliate_homepage' => $a_homepage,
                              'affiliate_password' => tep_encrypt_password($a_password),
                              'affiliate_agb' => '1');

      if (ACCOUNT_GENDER == 'true') $sql_data_array['affiliate_gender'] = $a_gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['affiliate_dob'] = tep_date_raw($a_dob);
      if (ACCOUNT_COMPANY == 'true') {
        $sql_data_array['affiliate_company'] = $a_company;
        $sql_data_array['affiliate_company_taxid'] = $a_company_taxid;
      }
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['affiliate_suburb'] = $a_suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($a_zone_id > 0) {
          $sql_data_array['affiliate_zone_id'] = $a_zone_id;
          $sql_data_array['affiliate_state'] = '';
        } else {
          $sql_data_array['affiliate_zone_id'] = '0';
          $sql_data_array['affiliate_state'] = $a_state;
        }
      }

      $sql_data_array['affiliate_date_account_created'] = 'now()';

//used for when a new affliate is refered by exsisting affliate
 if (isset($_SESSION['affiliate_ref'])){
  $affiliate_ref = $_SESSION['affiliate_ref'];
  }else{
  $affiliate_ref = $_SESSION['affiliate_ref'];
  }

//affiliate_insert will parse the $affiliate_ref

      $_SESSION['affiliate_id'] = affiliate_insert ($sql_data_array, $affiliate_ref);

      // build the message content
    $name = $a_firstname . ' ' . $a_lastname;
    $email_text = sprintf(MAIL_GREET_NONE, $a_firstname);
          $email_text .= MAIL_AFFILIATE_HEADER;
    $email_text .= sprintf(MAIL_AFFILIATE_ID, $_SESSION['affiliate_id']);
    $email_text .= sprintf(MAIL_AFFILIATE_USERNAME, $a_email_address);
    $email_text .= sprintf(MAIL_AFFILIATE_PASSWORD, $a_password);
    $email_text .= sprintf(MAIL_AFFILIATE_LINK, HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE) . "\n\n";
    $email_text .= MAIL_AFFILIATE_FOOTER;

      tep_mail($name, $a_email_address, MAIL_AFFILIATE_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      $_SESSION['affiliate_email'] = $a_email_address;
      $_SESSION['affiliate_name'] = $a_firstname . ' ' . $a_lastname;

      tep_redirect(tep_href_link(FILENAME_AFFILIATE_SIGNUP_OK, '', 'SSL'));
    }
  }
  $breadcrumb->add(NAVBAR_TITLE_AFFILIATE_CENTRAL);
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_SIGNUP, '', 'SSL'));

  $content = CONTENT_AFFILIATE_SIGNUP;
   $javascript = 'form_check.js.php';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
