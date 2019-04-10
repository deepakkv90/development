<?php
/*
  $Id: create_account_process.php,v 1.3 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  THIS IS BETA - Use at your own risk!
  Step-By-Step Manual Order Entry Verion 1.0
  Customer Entry through Admin
*/

  require('includes/application_top.php');


if (!@$_POST['action']) {
   tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
 }

  $gender = (isset($_POST['gender']) ? tep_db_prepare_input($_POST['gender']) : '');
  $firstname = (isset($_POST['firstname']) ? tep_db_prepare_input($_POST['firstname']) : '' );
  $lastname = (isset($_POST['lastname']) ? tep_db_prepare_input($_POST['lastname']) : '');
  $dob = (isset($_POST['dob']) ? tep_db_prepare_input($_POST['dob']) : '');
  $email_address = (isset($_POST['email_address']) ? strtolower(tep_db_prepare_input($_POST['email_address'])) : '');
  $telephone = (isset($_POST['telephone']) ? tep_db_prepare_input($_POST['telephone']) : 0);
  $fax = (isset($_POST['fax']) ? tep_db_prepare_input($_POST['fax']) : 0);
  $newsletter = (isset($_POST['newsletter']) ? tep_db_prepare_input($_POST['newsletter']) : 0);
  $password = (isset($_POST['password']) ? tep_db_prepare_input($_POST['password']) : '');
  $confirmation = (isset($_POST['confirmation']) ? tep_db_prepare_input($_POST['confirmation']) : '');
  $send_password = (isset($_POST['send_password']) && $_POST['send_password'] == '1') ? true : false;
  $street_address = (isset($_POST['street_address']) ? tep_db_prepare_input($_POST['street_address']) : '');
  $company = (isset($_POST['company']) ? tep_db_prepare_input($_POST['company']) : '');
  $suburb = (isset($_POST['suburb']) ? tep_db_prepare_input($_POST['suburb']) : '') ;
  $postcode = (isset($_POST['postcode']) ? tep_db_prepare_input($_POST['postcode']) : '');
  $city = (isset($_POST['city']) ? tep_db_prepare_input($_POST['city']) : '');
  $zone_id = (isset($_POST['zone_id']) ? tep_db_prepare_input($_POST['zone_id']) : 0);
  $state = (isset($_POST['state']) ? tep_db_prepare_input($_POST['state']) : '');
  $country = (isset($_POST['country']) ? tep_db_prepare_input($_POST['country']) : 0);


  $entry_company_tax_id= (isset($_POST['entry_company_tax_id']) ? tep_db_prepare_input($_POST['entry_company_tax_id']) : '');
  $customers_group_id= (isset($_POST['customers_group_id']) ? tep_db_prepare_input($_POST['customers_group_id']) : 0);
  $customers_access_group = $_POST['customers_access_group'];
    $customers_access_group_id = '';
    foreach ($customers_access_group as $value) {
        $customers_access_group_id .= $value . ',';
    }
    $customers_access_group_id = substr($customers_access_group_id, 0, strlen($customers_access_group_id) - 1);

  
  
///Payment and Shipping CODE
  if ($_POST['customers_payment_allowed'] && $_POST['customers_payment_settings'] == '1') {
  $customers_payment_allowed = tep_db_prepare_input($_POST['customers_payment_allowed']);
  } else { // no error with subsequent re-posting of variables
  $customers_payment_allowed = '';
  if ($_POST['payment_allowed'] && $_POST['customers_payment_settings'] == '1') {
    while(list($key, $val) = each($_POST['payment_allowed'])) {
        if ($val == true) {
        $customers_payment_allowed .= tep_db_prepare_input($val).';';
        }
     } // end while
      $customers_payment_allowed = substr($customers_payment_allowed,0,strlen($customers_payment_allowed)-1);
  } // end if ($_POST['payment_allowed'])
  } // end else ($_POST['customers_payment_allowed']
  if ($_POST['customers_shipment_allowed'] && $_POST['customers_shipment_settings'] == '1') {
  $customers_shipment_allowed = tep_db_prepare_input($_POST['customers_shipment_allowed']);
  } else { // no error with subsequent re-posting of variables

    $customers_shipment_allowed = '';
    if ($_POST['shipping_allowed'] && $_POST['customers_shipment_settings'] == '1') {
      while(list($key, $val) = each($_POST['shipping_allowed'])) {
        if ($val == true) {
        $customers_shipment_allowed .= tep_db_prepare_input($val).';';
        }
      } // end while
      $customers_shipment_allowed = substr($customers_shipment_allowed,0,strlen($customers_shipment_allowed)-1);
    } // end if ($_POST['shipment_allowed'])
  } // end else ($_POST['customers_shipment_allowed']

// EOF Separate Pricing per Customer

////Ends Here Payment and shipping code


  /////////////////      RAMDOMIZING SCRIPT BY PATRIC VEVERKA       \\\\\\\\\\\\\\\\\\
if (trim($password) == '' && trim($confirmation) == '') {
  $t1 = date("mdy");
  srand ((float) microtime() * 10000000);
  $input = array ("A", "a", "B", "b", "C", "c", "D", "d", "E", "e", "F", "f", "G", "g", "H", "h", "I", "i", "J", "j", "K", "k", "L", "l", "M", "m", "N", "n", "O", "o", "P", "p", "Q", "q", "R", "r", "S", "s", "T", "t", "U", "u", "V", "v", "W", "w", "X", "x", "Y", "y", "Z", "z");
  $rand_keys = array_rand ($input, 3);
  $l1 = $input[$rand_keys[0]];
  $r1 = rand(0,9);
  $l2 = $input[$rand_keys[1]];
  $l3 = $input[$rand_keys[2]];
  $r2 = rand(0,9);
  $password = $l1.$r1.$l2.$l3.$r2;
}
/////////////////    End of Randomizing Script   \\\\\\\\\\\\\\\\\\\



  $error = false; // reset error flag

  if (ACCOUNT_GENDER == 'true') {
    if (($gender == 'm') || ($gender == 'f')) {
      $entry_gender_error = false;
    } else {
      $error = true;
      $entry_gender_error = true;
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $entry_firstname_error = true;
  } else {
    $entry_firstname_error = false;
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $entry_lastname_error = true;
  } else {
    $entry_lastname_error = false;
  }

  if (ACCOUNT_DOB == 'true') {
    if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
      $entry_date_of_birth_error = false;
    } else {
      $error = true;
      $entry_date_of_birth_error = true;
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $entry_email_address_error = true;
  } else {
    $entry_email_address_error = false;
  }

 if (!tep_validate_email($email_address)) {
    $error = true;
    $entry_email_address_check_error = true;
  } else {
    $entry_email_address_check_error = false;
  }

  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;
    $entry_street_address_error = true;
  } else {
    $entry_street_address_error = false;
  }

  if (ACCOUNT_SUBURB == 'true') {
    if (!$suburb) {
      $entry_suburb_error = true;
    } else {
      $entry_suburb_error = false;
    }
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;
    $entry_post_code_error = true;
  } else {
    $entry_post_code_error = false;
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;
    $entry_city_error = true;
  } else {
    $entry_city_error = false;
  }

  if (!$country) {
    $error = true;
    $entry_country_error = true;
  } else {
    $entry_country_error = false;
  }

  if (ACCOUNT_STATE == 'true') {
    if ($entry_country_error) {
      $entry_state_error = true;
    } else {
      $zone_id = 0;
      $entry_state_error = false;
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "'");
      $check_value = tep_db_fetch_array($check_query);
      $entry_state_has_zones = ($check_value['total'] > 0);
      if ($entry_state_has_zones) {
        $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_name = '" . tep_db_input($state) . "'");
        if (tep_db_num_rows($zone_query) == 1) {
          $zone_values = tep_db_fetch_array($zone_query);
          $zone_id = $zone_values['zone_id'];
        } else {
          $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_code = '" . tep_db_input($state) . "'");
          if (tep_db_num_rows($zone_query) == 1) {
            $zone_values = tep_db_fetch_array($zone_query);
            $zone_id = $zone_values['zone_id'];
          } else {
            $error = true;
            $entry_state_error = true;
          }
        }
      } else {
        if (!$state) {
          $error = true;
          $entry_state_error = true;
        }
      }
    }
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $entry_telephone_error = true;
  } else {
    $entry_telephone_error = false;
  }

  if (!$fax) {
    $entry_fax_error = true;
  } else {
    $entry_fax_error = false;
  }
/*
  $passlen = strlen($password);
  if ($passlen < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;
    $entry_password_error = true;
  } else {
    $entry_password_error = false;
  }

  if ($password != $confirmation) {
    $error = true;
    $entry_password_error = true;
  }
*/
  $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($email_address) . "' and customers_id <> '" . tep_db_input($customer_id) . "'");
  if (tep_db_num_rows($check_email)) {
    $error = true;
    $entry_email_address_exists = true;
  } else {
    $entry_email_address_exists = false;
  }

  if ($error == true) {
    $processed = true;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
  <title><?php echo TITLE ?></title>
  <script type="text/javascript" src="includes/prototype.js"></script> 
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<?php require('includes/form_check.js.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><form name="account_edit" method="post" <?php echo 'action="' . tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'SSL') . '"'; ?> onSubmit="return check_form();"><input type="hidden" name="action" value="process"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if (sizeof($navigation->snapshot) > 0) {
?>
      <tr>
        <td class="smallText"><br><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php
  //$email_address = tep_db_prepare_input($_GET['email_address']);
  $account['entry_country_id'] = STORE_COUNTRY;

  require(DIR_WS_MODULES . 'account_details.php');
?>
        </td>
      </tr>
      <tr>
        <td align="right" class="main"><br><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_CONTINUE); ?></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
    </table></td>
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
  } else  {
  $entry_company_tax_id=tep_db_prepare_input($_POST['entry_company_tax_id']);
    $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,  
                            'customers_newsletter' => $newsletter,
              'customers_validation' => '1',
                           'customers_password' => tep_encrypt_password($password),
               'customers_group_id'=>$customers_group_id,   
      'customers_access_group_id' => $customers_access_group_id,
               'customers_payment_allowed' => $customers_payment_allowed,
            'customers_shipment_allowed' => $customers_shipment_allowed);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

    $customer_id = tep_db_insert_id();

    $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_email_address' => $email_address, 
                              'entry_telephone' => $telephone,
                              'entry_fax' => $fax,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country,
                'entry_company_tax_id'=>$entry_company_tax_id
                );

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

    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

    $address_id = tep_db_insert_id();

    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['customer_first_name'] = $firstname;
    $_SESSION['customer_default_address_id'] = $address_id;
    $_SESSION['customer_country_id'] = $country;
    $_SESSION['customer_zone_id'] = $zone_id;

    // build the message content
    $name = $firstname . " " . $lastname;

    if (ACCOUNT_GENDER == 'true') {
       if ($_POST['gender'] == 'm') {
         $email_text = EMAIL_GREET_MR;
       } else {
         $email_text = EMAIL_GREET_MS;
       }
    } else {
      $email_text = EMAIL_GREET_NONE;
    }

    $email_text .= EMAIL_WELCOME . ($send_password ? EMAIL_PASS_1 . $password . EMAIL_PASS_2 : '') . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
    tep_mail($name, $email_address, EMAIL_SUBJECT, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

    tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
  }

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
