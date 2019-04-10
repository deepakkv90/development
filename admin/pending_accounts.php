<?php
/* 
  $Id: pending_accounts.php,v 1.3 2008/06/10 17:56:06 datazen Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$error = false;
$processed = false;
if (tep_not_null($action)) {
  switch ($action) {
    case 'update':
      $customers_id = tep_db_prepare_input($_GET['cID']);
      $customers_firstname = tep_db_prepare_input($_POST['customers_firstname']);
      $customers_lastname = tep_db_prepare_input($_POST['customers_lastname']);
      $customers_email_address = strtolower(tep_db_prepare_input($_POST['customers_email_address']));
      $customers_telephone = tep_db_prepare_input($_POST['customers_telephone']);
      $customers_fax = tep_db_prepare_input($_POST['customers_fax']);
      $customers_newsletter = tep_db_prepare_input($_POST['customers_newsletter']);
      $customers_emailvalidated= tep_db_prepare_input($_POST['customers_emailvalidated']);
      $customers_accountvalidated= tep_db_prepare_input($_POST['customers_accountvalidated']);
      $customers_group_id = tep_db_prepare_input($_POST['customers_group_id']);
      $customers_group_ra = tep_db_prepare_input($_POST['customers_group_ra']);
      $entry_company_tax_id = tep_db_prepare_input($_POST['entry_company_tax_id']);
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
      $customers_gender = tep_db_prepare_input($_POST['customers_gender']);
      $customers_dob = tep_db_prepare_input($_POST['customers_dob']);
      $default_address_id = tep_db_prepare_input($_POST['default_address_id']);
      $entry_street_address = tep_db_prepare_input($_POST['entry_street_address']);
      $entry_suburb = tep_db_prepare_input($_POST['entry_suburb']);
      $entry_postcode = tep_db_prepare_input($_POST['entry_postcode']);
      $entry_city = tep_db_prepare_input($_POST['entry_city']);
      $entry_country_id = tep_db_prepare_input($_POST['entry_country_id']);
      $entry_company = tep_db_prepare_input($_POST['entry_company']);
      $entry_state = tep_db_prepare_input($_POST['entry_state']);
      if (isset($_POST['entry_zone_id'])) $entry_zone_id = tep_db_prepare_input($_POST['entry_zone_id']);
      if (strlen($customers_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $error = true;
        $entry_firstname_error = true;
      } else {
        $entry_firstname_error = false;
      }
      if (strlen($customers_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
        $error = true;
        $entry_lastname_error = true;
      } else {
        $entry_lastname_error = false;
      }
      if (ACCOUNT_DOB == 'true') {
        if (checkdate(substr(tep_date_raw($customers_dob), 4, 2), substr(tep_date_raw($customers_dob), 6, 2), substr(tep_date_raw($customers_dob), 0, 4))) {
          $entry_date_of_birth_error = false;
        } else {
          $error = true;
          $entry_date_of_birth_error = true;
        }
      }
      if (strlen($customers_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
        $error = true;
        $entry_email_address_error = true;
      } else {
        $entry_email_address_error = false;
      }
      if (!tep_validate_email($customers_email_address)) {
        $error = true;
        $entry_email_address_check_error = true;
      } else {
        $entry_email_address_check_error = false;
      }
      if (strlen($entry_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $error = true;
        $entry_street_address_error = true;
      } else {
        $entry_street_address_error = false;
      }
      if (strlen($entry_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
        $error = true;
        $entry_post_code_error = true;
      } else {
        $entry_post_code_error = false;
      }
      if (strlen($entry_city) < ENTRY_CITY_MIN_LENGTH) {
        $error = true;
        $entry_city_error = true;
      } else {
        $entry_city_error = false;
      }
      if ($entry_country_id == false) {
        $error = true;
        $entry_country_error = true;
      } else {
        $entry_country_error = false;
      }
      if (ACCOUNT_STATE == 'true') {
        if ($entry_country_error == true) {
          $entry_state_error = true;
        } else {
          $zone_id = 0;
          $entry_state_error = false;
          $check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry_country_id . "'");
          $check_value = tep_db_fetch_array($check_query);
          $entry_state_has_zones = ($check_value['total'] > 0);
          if ($entry_state_has_zones == true) {
            $zone_query = tep_db_query("select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$entry_country_id . "' and zone_name = '" . tep_db_input($entry_state) . "'");
            if (tep_db_num_rows($zone_query) == 1) {
              $zone_values = tep_db_fetch_array($zone_query);
              $entry_zone_id = $zone_values['zone_id'];
            } else {
              $error = true;
              $entry_state_error = true;
            }
          } else {
            if ($entry_state == false) {
              $error = true;
              $entry_state_error = true;
            }
          }
        }
      }
      if (strlen($customers_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
        $error = true;
        $entry_telephone_error = true;
      } else {
        $entry_telephone_error = false;
      }
      $check_email = tep_db_query("select customers_email_address from " . TABLE_CUSTOMERS . " where lower(customers_email_address) = '" . tep_db_input($customers_email_address) . "' and customers_id != '" . (int)$customers_id . "'");
      if (tep_db_num_rows($check_email)) {
        $error = true;
        $entry_email_address_exists = true;
      } else {
        $entry_email_address_exists = false;
      }
      if ($error == false) {
        $sql_data_array = array('customers_firstname' => $customers_firstname,
                                'customers_lastname' => $customers_lastname,
                                'customers_email_address' => $customers_email_address,
                                'customers_validation' => $customers_emailvalidated,                
                                'customers_newsletter' => $customers_newsletter,
                                'customers_group_id' => $customers_group_id,
                                'customers_group_ra' => $customers_group_ra,
                                'customers_payment_allowed' => $customers_payment_allowed,
                                'customers_shipment_allowed' => $customers_shipment_allowed,'customers_account_approval' =>$customers_accountvalidated);
        if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
        if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);
        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "'");
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customers_id . "'");
        if ($entry_zone_id > 0) $entry_state = '';
        $sql_data_array = array('entry_firstname' => $customers_firstname,
                                'entry_lastname' => $customers_lastname,
                                'entry_telephone' => $customers_telephone,
                                'entry_fax' => $customers_fax,
                                'entry_street_address' => $entry_street_address,
                                'entry_postcode' => $entry_postcode,
                                'entry_city' => $entry_city,
                                'entry_country_id' => $entry_country_id);
        if (ACCOUNT_COMPANY == 'true') {
          $sql_data_array['entry_company'] = $entry_company;
          $sql_data_array['entry_company_tax_id'] = $entry_company_tax_id;
        }
        if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;
        if (ACCOUNT_STATE == 'true') {
          if ($entry_zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $entry_zone_id;
            $sql_data_array['entry_state'] = '';
          } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $entry_state;
          }
        }
        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$default_address_id . "'");
        if ($customers_accountvalidated=='Approve') {
         //Let's build a message object using the email class    
          $subject=EMAIL_TEXT_ACCOUNT_ACTIVATION_NOTIFICATION_SUBJECT;
          $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
          $mail = tep_db_fetch_array($mail_query);
          $message="Dear ".$mail['customers_firstname'] . ' ' . $mail['customers_lastname'].",\n\n".EMAIL_TEXT_ACCOUNT_ACTIVATION_NOTIFICATION."\n Thanks";
          $mimemessage = new email(array('X-Mailer: osCommerce'));    
          $mimemessage->add_text($message);
          $mimemessage->build_message();
          $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], strtolower($mail['customers_email_address']), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $subject);   
        }
        tep_redirect(tep_href_link(FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));
      } elseif ($error == true) {
        $cInfo = new objectInfo($_POST);
        $processed = true;
      }
      break;  
    case 'updateaccountstatus':
      $customers_id = tep_db_prepare_input($_GET['cID']);
      tep_db_query("update " . TABLE_CUSTOMERS. " set  customers_account_approval='".$_REQUEST['approvalstatus']."' where customers_id = '" . (int)$customers_id . "'");        
      if ($_REQUEST['approvalstatus']=='Approve') {
        //Let's build a message object using the email class    
        $subject=EMAIL_TEXT_ACCOUNT_ACTIVATION_NOTIFICATION_SUBJECT;
        $mail_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
        $mail = tep_db_fetch_array($mail_query);
        $message="Dear ".$mail['customers_firstname'] . ' ' . $mail['customers_lastname'].",\n\n".EMAIL_TEXT_ACCOUNT_ACTIVATION_NOTIFICATION."\n Thanks";
        $mimemessage = new email(array('X-Mailer: osCommerce'));    
        $mimemessage->add_text($message);
        $mimemessage->build_message();
        $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], strtolower($mail['customers_email_address']), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $subject);   
      }
      // unset the cID so no customer record will preselected
      unset($_GET['cID']);
      break;    
    case 'deleteconfirm':
      $customers_id = tep_db_prepare_input($_GET['cID']);
      if (isset($_POST['delete_reviews']) && ($_POST['delete_reviews'] == 'on')) {
        $reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
        while ($reviews = tep_db_fetch_array($reviews_query)) {
          tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews['reviews_id'] . "'");
        }
        tep_db_query("delete from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
      } else {
        tep_db_query("update " . TABLE_REVIEWS . " set customers_id = null where customers_id = '" . (int)$customers_id . "'");
      }
      // Once all customers with a specific customers_group_id have been deleted from
      // the table customers, the next time a customer is deleted, all entries in the table products_groups
      // that have the (now apparently obsolete) customers_group_id will be deleted!
      // If you don't want that, leave this section out, or comment it out
      // Note that when customers groups are deleted from the table customers_groups, all the
      // customers with that specific customer_group_id will be changed to customer_group_id = '0' (default/Retail)
      $multiple_groups_query = tep_db_query("select customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " ");
      while ($group_ids = tep_db_fetch_array($multiple_groups_query)) {
        $multiple_customers_query = tep_db_query("select distinct customers_group_id from " . TABLE_CUSTOMERS . " where customers_group_id = " . $group_ids['customers_group_id'] . " ");
        if (!($multiple_groups = tep_db_fetch_array($multiple_customers_query))) {
          tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $group_ids['customers_group_id'] . "'");
        }
      }
      tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customers_id . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customers_id . "'");
      tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customers_id . "'");
      tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$customers_id . "'");
      tep_redirect(tep_href_link(FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('cID', 'action'))));
      break;
    default:
      $customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_company_tax_id, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, a.entry_telephone as customers_telephone, a.entry_fax as customers_fax, c.customers_newsletter, c.customers_group_id,  c.customers_group_ra, c.customers_payment_allowed, c.customers_shipment_allowed, c.customers_default_address_id,c.customers_validation,c.customers_account_approval
                                         from " . TABLE_CUSTOMERS . " c 
                                       left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id
                                       where a.customers_id = c.customers_id
                                         and c.customers_id = '" . (int)$_GET['cID'] . "'");
      $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
      $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      $directory_array = array();
      if ($dir = @dir($module_directory)) {
        while ($file = $dir->read()) {
          if (!is_dir($module_directory . $file)) {
            if (substr($file, strrpos($file, '.')) == $file_extension) {
              $directory_array[] = $file; // array of all the payment modules present in includes/modules/payment
            }
          }
        }
        sort($directory_array);
        $dir->close();
      }
      $ship_directory_array = array();
      if ($dir = @dir($ship_module_directory)) {
        while ($file = $dir->read()) {
          if (!is_dir($ship_module_directory . $file)) {
            if (substr($file, strrpos($file, '.')) == $file_extension) {
              $ship_directory_array[] = $file; // array of all shipping modules present in includes/modules/shipping
            }
          }
        }
        sort($ship_directory_array);
        $dir->close();
      }
      $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
      $customers = tep_db_fetch_array($customers_query);
      $cInfo = new objectInfo($customers);
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<?php
if ($action == 'edit' || $action == 'update') {
   ?>
  <script language="javascript"><!--
  function check_form() {
    var error = 0;
    var error_message = "<?php echo JS_ERROR; ?>";
    var customers_firstname = document.customers.customers_firstname.value;
    var customers_lastname = document.customers.customers_lastname.value;
    <?php if (ACCOUNT_COMPANY == 'true') echo 'var entry_company = document.customers.entry_company.value;' . "\n"; ?>
    <?php if (ACCOUNT_DOB == 'true') echo 'var customers_dob = document.customers.customers_dob.value;' . "\n"; ?>
    var customers_email_address = document.customers.customers_email_address.value;
    var entry_street_address = document.customers.entry_street_address.value;
    var entry_postcode = document.customers.entry_postcode.value;    
    var entry_city = document.customers.entry_city.value;
    var customers_telephone = document.customers.customers_telephone.value;
    <?php 
    if (ACCOUNT_GENDER == 'true') { ?>
      if (document.customers.customers_gender[0].checked || document.customers.customers_gender[1].checked) {
      } else {
        error_message = error_message + "<?php echo JS_GENDER; ?>";
        error = 1;
      }
      <?php 
    } 
    ?>
    if (customers_firstname == "" || customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
      error = 1;
    }
    if (customers_lastname == "" || customers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
      error = 1;
    }
    <?php 
    if (ACCOUNT_DOB == 'true') { ?>
      if (customers_dob == "" || customers_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
        error_message = error_message + "<?php echo JS_DOB; ?>";
        error = 1;
      }
      <?php 
    } 
    ?>
    if (customers_email_address == "" || customers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
      error = 1;
    }
    if (entry_street_address == "" || entry_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_ADDRESS; ?>";
      error = 1;
    }
    if (entry_postcode == "" || entry_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_POST_CODE; ?>";
      error = 1;
    }
    if (entry_city == "" || entry_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_CITY; ?>";
      error = 1;
    }
    <?php
    if (ACCOUNT_STATE == 'true') {
      ?>
      if (document.customers.elements['entry_state'].type != "hidden") {
        if (document.customers.entry_state.value == '' || document.customers.entry_state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
          error_message = error_message + "<?php echo JS_STATE; ?>";
          error = 1;
        }
      }
      <?php
    }
    ?>
    if (document.customers.elements['entry_country_id'].type != "hidden") {
      if (document.customers.entry_country_id.value == 0) {
        error_message = error_message + "<?php echo JS_COUNTRY; ?>";
        error = 1;
      }
    }
    if (customers_telephone == "" || customers_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
      error = 1;
    }
    if (error == 1) {
      alert(error_message);
      return false;
    } else {
      return true;
    }
  }
  //--></script>
  <?php
}
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <?php
      if ($action == 'edit' || $action == 'update') {
        $newsletter_array = array(array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO),
                                  array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES));
        $emailvalidated_array = array(array('id' => '0', 'text' => ENTRY_EMAILVALIDATE_NO),
                                      array('id' => '1', 'text' => ENTRY_EMAILVALIDATE_YES));               
        $accountvalidated_array = array(array('id' => 'Approve', 'text' => ENTRY_ACCOUNTVALIDATE_A),
                                        array('id' => 'Pending', 'text' => ENTRY_ACCOUNTVALIDATE_P),
                                        array('id' => 'Deny', 'text' => ENTRY_ACCOUNTVALIDATE_D));  
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr><?php echo tep_draw_form('customers', FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('default_address_id', $cInfo->customers_default_address_id); ?>
          <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <?php
            if (ACCOUNT_GENDER == 'true') {
              ?>
              <tr>
                <td class="main"><?php echo ENTRY_GENDER; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if ($entry_gender_error == true) {
                      echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
                    } else {
                      echo ($cInfo->customers_gender == 'm') ? MALE : FEMALE;
                      echo tep_draw_hidden_field('customers_gender');
                    }
                  } else {
                    echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE;
                  }
                  ?>
                </td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_firstname_error == true) {
                    echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
                  } else {
                    echo $cInfo->customers_firstname . tep_draw_hidden_field('customers_firstname');
                  }
                } else {
                  echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"', true);
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_lastname_error == true) {
                    echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
                  } else {
                    echo $cInfo->customers_lastname . tep_draw_hidden_field('customers_lastname');
                  }
                } else {
                  echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"', true);
                }
                ?>
              </td>
            </tr>
            <?php
            if (ACCOUNT_DOB == 'true') {
              ?>
              <tr>
                <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if ($entry_date_of_birth_error == true) {
                      echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
                    } else {
                      echo $cInfo->customers_dob . tep_draw_hidden_field('customers_dob');
                    }             
                  } else {
                    echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"', true);
                  }
                  ?>
                </td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_email_address_error == true) {
                    echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
                  } elseif ($entry_email_address_check_error == true) {
                    echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
                  } elseif ($entry_email_address_exists == true) {
                    echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
                  } else {
                    echo $customers_email_address . tep_draw_hidden_field('customers_email_address');
                  }
                } else {
                  echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"', true);
                }
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <?php
        if (ACCOUNT_COMPANY == 'true') {
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
          </tr>
          <tr>
            <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_COMPANY; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if ($entry_company_error == true) {
                      echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_ERROR;
                    } else {
                      echo $cInfo->entry_company . tep_draw_hidden_field('entry_company');
                    }
                  } else {
                    echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"');
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_COMPANY_TAX_ID; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if ($entry_company_tax_id_error == true) {
                      echo tep_draw_input_field('entry_company_tax_id', $cInfo->entry_company_tax_id, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_TAX_ID_ERROR;
                    } else {
                      echo $cInfo->entry_company . tep_draw_hidden_field('entry_company_tax_id');
                    }
                  } else {
                    echo tep_draw_input_field('entry_company_tax_id', $cInfo->entry_company_tax_id, 'maxlength="32"');
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_CUSTOMERS_GROUP_REQUEST_AUTHENTICATION; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if ($customers_group_ra_error == true) {
                      echo tep_draw_radio_field('customers_group_ra', '0', false, $cInfo->customers_group_ra) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_NO . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_group_ra', '1', false, $cInfo->customers_group_ra) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_YES . '&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_ERROR;
                    } else {
                      echo ($cInfo->customers_group_ra == '0') ? ENTRY_CUSTOMERS_GROUP_RA_NO : ENTRY_CUSTOMERS_GROUP_RA_YES;
                      echo tep_draw_hidden_field('customers_group_ra');
                    }
                  } else {
                    echo tep_draw_radio_field('customers_group_ra', '0', false, $cInfo->customers_group_ra) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_NO . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_group_ra', '1', false, $cInfo->customers_group_ra) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_YES;
                  }
                  ?>
                </td>
              </tr>
            </table></td>
          </tr>
          <?php
        }  // end of ACCOUNT_COMPANY == 'true'
        ?>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_street_address_error == true) {
                    echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
                  } else {
                    echo $cInfo->entry_street_address . tep_draw_hidden_field('entry_street_address');
                  }
                } else {
                  echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"', true);
                }
                ?>
              </td>
            </tr>
            <?php
            if (ACCOUNT_SUBURB == 'true') {
              ?>
              <tr>
                <td class="main"><?php echo ENTRY_SUBURB; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if ($entry_suburb_error == true) {
                      echo tep_draw_input_field('suburb', $cInfo->entry_suburb, 'maxlength="32"') . '&nbsp;' . ENTRY_SUBURB_ERROR;
                    } else {
                      echo $cInfo->entry_suburb . tep_draw_hidden_field('entry_suburb');
                    }
                  } else {
                    echo tep_draw_input_field('entry_suburb', $cInfo->entry_suburb, 'maxlength="32"');
                  }
                  ?>
                </td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_post_code_error == true) {
                    echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="10"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
                  } else {
                    echo $cInfo->entry_postcode . tep_draw_hidden_field('entry_postcode');
                  }
                } else {
                  echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="10"', true);
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_CITY; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_city_error == true) {
                    echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"') . '&nbsp;' . ENTRY_CITY_ERROR;
                  } else {
                    echo $cInfo->entry_city . tep_draw_hidden_field('entry_city');
                  }
                } else {
                  echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"', true);
                }
                ?>
              </td>
            </tr>
            <?php
            if (ACCOUNT_STATE == 'true') {
              ?>
              <tr>
                <td class="main"><?php echo ENTRY_STATE; ?></td>
                <td class="main">
                  <?php
                  $entry_state = tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state);
                  if ($error == true) {
                    if ($entry_state_error == true) {
                      if ($entry_state_has_zones == true) {
                        $zones_array = array();
                        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($cInfo->entry_country_id) . "' order by zone_name");
                        while ($zones_values = tep_db_fetch_array($zones_query)) {
                          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
                        }
                        echo tep_draw_pull_down_menu('entry_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
                      } else {
                        echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state)) . '&nbsp;' . ENTRY_STATE_ERROR;
                      }
                    } else {
                      echo $entry_state . tep_draw_hidden_field('entry_zone_id') . tep_draw_hidden_field('entry_state');
                    }
                  } else {
                    echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state));
                  }
                  ?>
                </td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_country_error == true) {
                    echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id) . '&nbsp;' . ENTRY_COUNTRY_ERROR;
                  } else {
                    echo tep_get_country_name($cInfo->entry_country_id) . tep_draw_hidden_field('entry_country_id');
                  }
                } else {
                  echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id);
                }
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_telephone_error == true) {
                    echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
                  } else {
                    echo $cInfo->customers_telephone . tep_draw_hidden_field('customers_telephone');
                  }
                } else {
                  echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"', true);
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
              <td class="main">
                <?php
                if ($processed == true) {
                  echo $cInfo->customers_fax . tep_draw_hidden_field('customers_fax');
                } else {
                  echo tep_draw_input_field('customers_fax', $cInfo->customers_fax, 'maxlength="32"');
                }
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="formAreaTitle"><?php echo CATEGORY_OPTIONS; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
              <td class="main">
                <?php
                if ($processed == true) {
                  if ($cInfo->customers_newsletter == '1') {
                    echo ENTRY_NEWSLETTER_YES;
                  } else {
                    echo ENTRY_NEWSLETTER_NO;
                  }
                  echo tep_draw_hidden_field('customers_newsletter');
                } else {
                  echo tep_draw_pull_down_menu('customers_newsletter', $newsletter_array, (($cInfo->customers_newsletter == '1') ? '1' : '0'));
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_CUSTOMERS_GROUP_NAME; ?></td>
                <?php
                if ($processed != true) {
                  $index = 0;
                  while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
                    $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
                    ++$index;
                  }
                } // end if ($processed != true )
                ?>
                <td class="main">
                  <?php
                  if ($processed == true) {
                    echo $cInfo->customers_group_id . tep_draw_hidden_field('customers_group_id');
                  } else {
                    echo tep_draw_pull_down_menu('customers_group_id', $existing_customers_array, $cInfo->customers_group_id);
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_CUSTOMERS_EMAIL_VALIDATED;?></td>
                <td class="main">
                  <?php 
                  if (ACCOUNT_EMAIL_CONFIRMATION=='true') {
                    echo tep_draw_pull_down_menu('customers_emailvalidated',$emailvalidated_array, $cInfo->customers_validation);
                  } else {
                    echo  TEXT_EMAIL_VALIDATE_FEATURE. tep_draw_hidden_field('customers_emailvalidated',$cInfo->customers_validation);
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td class="main"><?php echo ENTRY_CUSTOMERS_ACCOUNT_VALIDATED;?></td>
                <td class="main">
                  <?php 
                  if (B2B_REQUIRE_ACCOUNT_APPROVAL=='true') {
                    echo tep_draw_pull_down_menu('customers_accountvalidated',$accountvalidated_array, $cInfo->customers_account_approval);
                  } else {
                    echo  TEXT_ACCOUNT_VALIDATE_FEATURE. tep_draw_hidden_field('customers_accountvalidated',$cInfo->customers_account_approval);
                  }
                  ?>
                </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="formAreaTitle"><?php include_once(DIR_WS_LANGUAGES . $language . '/modules.php'); echo HEADING_TITLE_MODULES_PAYMENT; ?></td>
          </tr>
          <tr>
            <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
              <tr bgcolor="#DEE4E8">
                <td class="main" colspan="2">
                  <?php
                  if ($processed == true) {
                    if ($cInfo->customers_payment_settings == '1') {
                      echo ENTRY_CUSTOMERS_PAYMENT_SET ;
                      echo ' : ';
                    } else {
                      echo ENTRY_CUSTOMERS_PAYMENT_DEFAULT;
                    }
                    echo tep_draw_hidden_field('customers_payment_settings');
                  } else { // $processed != true
                    echo tep_draw_radio_field('customers_payment_settings', '1', false, (tep_not_null($cInfo->customers_payment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_PAYMENT_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_payment_settings', '0', false, (tep_not_null($cInfo->customers_payment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_PAYMENT_DEFAULT ;
                  }
                  ?>
                </td>
              </tr>
              <?php
              if ($processed != true) {
                $payments_allowed = explode (";",$cInfo->customers_payment_allowed);
                $module_active = explode (";",MODULE_PAYMENT_INSTALLED);
                $installed_modules = array();
                for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
                  $file = $directory_array[$i];
                  if (in_array ($directory_array[$i], $module_active)) {
                    include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/payment/' . $file);
                    include($module_directory . $file);
                    $class = substr($file, 0, strrpos($file, '.'));
                    if (tep_class_exists($class)) {
                      $module = new $class;
                      if ($module->check() > 0) {
                        $installed_modules[] = $file;
                      }
                    } // end if (tep_class_exists($class))
                    ?>
                    <tr>
                      <td class="main" colspan="2"><?php echo tep_draw_checkbox_field('payment_allowed[' . $i . ']', $module->code.".php" , (in_array ($module->code.".php", $payments_allowed)) ?  1 : 0); ?>&#160;&#160;<?php echo $module->title; ?></td>
                    </tr>
                    <?php
                  } // end if (in_array ($directory_array[$i], $module_active))
                } // end for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++)
                ?>
                <tr>
                  <td class="main" colspan="2" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_CUSTOMERS_PAYMENT_SET_EXPLAIN ?></td>
                </tr>        
                <?php
              } else { // end if ($processed != true)
                ?>
                <tr>
                  <td class="main" colspan="2">
                    <?php
                    if ($cInfo->customers_payment_settings == '1') {
                      echo $customers_payment_allowed;
                    } else {
                      echo ENTRY_CUSTOMERS_PAYMENT_DEFAULT;
                    }
                    echo tep_draw_hidden_field('customers_payment_allowed');
                    ?>
                  </td>
                </tr>
                <?php
              } // end else: $processed == true
              ?>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="formAreaTitle"><?php echo HEADING_TITLE_MODULES_SHIPPING; ?></td>
          </tr>
          <tr>
            <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
              <tr bgcolor="#DEE4E8">
                <td class="main" colspan="2">
                  <?php
                  if ($processed == true) {
                    if ($cInfo->customers_shipment_settings == '1') {
                      echo ENTRY_CUSTOMERS_SHIPPING_SET ;
                      echo ' : ';
                    } else {
                      echo ENTRY_CUSTOMERS_SHIPPING_DEFAULT;
                    }
                    echo tep_draw_hidden_field('customers_shipment_settings');
                  } else { // $processed != true
                    echo tep_draw_radio_field('customers_shipment_settings', '1', false, (tep_not_null($cInfo->customers_shipment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_SHIPPING_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_shipment_settings', '0', false, (tep_not_null($cInfo->customers_shipment_allowed)? '1' : '0' )) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_SHIPPING_DEFAULT ;
                  }
                  ?>
                </td>
              </tr>
              <?php
              if ($processed != true) {
                $shipment_allowed = explode (";",$cInfo->customers_shipment_allowed);
                $ship_module_active = explode (";",MODULE_SHIPPING_INSTALLED);
                $installed_shipping_modules = array();
                for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++) {
                  $file = $ship_directory_array[$i];
                  if (in_array ($ship_directory_array[$i], $ship_module_active)) {
                    include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/shipping/' . $file);
                    include($ship_module_directory . $file);
                    $ship_class = substr($file, 0, strrpos($file, '.'));
                    if (tep_class_exists($ship_class)) {
                      $ship_module = new $ship_class;
                      if ($ship_module->check() > 0) {
                        $installed_shipping_modules[] = $file;
                      }
                    } // end if (tep_class_exists($ship_class))
                    ?>
                    <tr>
                      <td class="main" colspan="2"><?php echo tep_draw_checkbox_field('shipping_allowed[' . $i . ']', $ship_module->code.".php" , (in_array ($ship_module->code.".php", $shipment_allowed)) ?  1 : 0); ?>&#160;&#160;<?php echo $ship_module->title; ?></td>
                    </tr>
                    <?php
                  } // end if (in_array ($ship_directory_array[$i], $ship_module_active))
                } // end for ($i = 0, $n = sizeof($ship_directory_array); $i < $n; $i++)
                ?>
                <tr>
                  <td class="main" colspan="2" style="padding-left: 30px; padding-right: 10px; padding-top: 10px;"><?php echo ENTRY_CUSTOMERS_SHIPPING_SET_EXPLAIN ?></td>
                </tr>
                <?php
              } else { // end if ($processed != true)
                ?>
                <tr>
                  <td class="main" colspan="2">
                    <?php
                    if ($cInfo->customers_shipment_settings == '1') {
                      echo $customers_shipment_allowed;
                    } else {
                      echo ENTRY_CUSTOMERS_SHIPPING_DEFAULT;
                    }
                    echo tep_draw_hidden_field('customers_shipment_allowed');
                    ?>
                  </td>
                </tr>
                <?php
              } // end else: $processed == true
              ?>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr></form>
          <?php
        } else {
          ?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <?php 
                echo tep_draw_form('search', FILENAME_PENDING_ACCOUNTS, '', 'get'); 
                if ( isset($_GET[tep_session_name()]) ) {
                  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
                }          
                ?>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?> &nbsp;       
                  <select name='filterstatus' onchange='document.search.submit()'>
                    <option value='Pending' <?php echo (isset($_REQUEST['filterstatus']) && $_REQUEST['filterstatus'] == 'Pending' ? 'selected="selected"' : '')?>><?php echo TEXT_PENDING;?></option>
                    <option value='Deny' <?php echo (isset($_REQUEST['filterstatus']) && $_REQUEST['filterstatus'] == 'Deny' ? 'selected="selected"' : '')?>><?php echo TEXT_DENY;?></option>
                  </select>
                </td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
                </form>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <?php
                    // customer_sort_admin_v1 adapted for Separate Pricing Per Customer
                    $listing = (isset($listing) ? $listing : '');
                    switch ($listing) {
                      case "id-asc":
                        $order = "c.customers_id";
                        break;
                      case "cg_name":
                        $order = "cg.customers_group_name, c.customers_lastname";
                        break;
                      case "cg_name-desc":
                        $order = "cg.customers_group_name DESC, c.customers_lastname";
                        break;
                      case "firstname":
                        $order = "c.customers_firstname";
                        break;
                      case "firstname-desc":
                        $order = "c.customers_firstname DESC";
                        break;
                      case "company":
                        $order = "a.entry_company, c.customers_lastname";
                        break;
                      case "company-desc":
                        $order = "a.entry_company DESC,c .customers_lastname DESC";
                        break;
                      case "ra":
                        $order = "c.customers_group_ra DESC, c.customers_id DESC";
                        break;
                      case "ra-desc":
                        $order = "c.customers_group_ra, c.customers_id DESC";
                        break;
                      case "lastname":
                        $order = "c.customers_lastname, c.customers_firstname";
                        break;
                      case "lastname-desc":
                        $order = "c.customers_lastname DESC, c.customers_firstname";
                        break;
                      default:
                        $order = "c.customers_id DESC";
                    }                  
                    if ( isset($_GET[tep_session_name()]) ) {
                      $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
                    } else {
                      $oscid = '';
                    }
                    ?>
                    <td class="dataTableHeadingContent" valign="top"><a href="<?php echo "$PHP_SELF?listing=company" . $oscid; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . ENTRY_COMPANY . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=company-desc" . $oscid; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . ENTRY_COMPANY . ' --> Z-X-Y From Top '); ?></a><br><?php echo ENTRY_COMPANY; ?></td>
                    <td class="dataTableHeadingContent" valign="top"><a href="<?php echo "$PHP_SELF?listing=lastname" . $oscid; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_LASTNAME . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=lastname-desc" . $oscid; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_LASTNAME . ' --> Z-X-Y From Top '); ?></a><br><?php echo TABLE_HEADING_LASTNAME; ?></td>
                    <td class="dataTableHeadingContent" valign="top"><a href="<?php echo "$PHP_SELF?listing=firstname" . $oscid; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_FIRSTNAME . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=firstname-desc" . $oscid; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_FIRSTNAME . ' --> Z-X-Y From Top '); ?></a><br><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
                    <td class="dataTableHeadingContent" valign="top"><a href="<?php echo "$PHP_SELF?listing=entry_state" . $oscid; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_CUSTOMERS_STATE . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=entry_state-desc" . $oscid; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_CUSTOMERS_STATE . ' --> Z-X-Y From Top '); ?></a><br><?php echo TABLE_HEADING_CUSTOMERS_STATE; ?></td>
                    <td class="dataTableHeadingContent" align="right" valign="top"><a href="<?php echo "$PHP_SELF?listing=customers_telephone" . $oscid; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_CUSTOMERS_PHONE . ' --> 1-2-3 From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=id-desc" . $oscid; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_CUSTOMERS_PHONE . ' --> 3-2-1 From Top '); ?></a><br><?php echo TABLE_HEADING_CUSTOMERS_PHONE; ?></td>
                    <td class="dataTableHeadingContent" align="left" valign="top"><a href="<?php echo "$PHP_SELF?listing=customers_email_address" . $oscid; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TABLE_HEADING_CUSTOMERS_EMAIL . ' --> customers_email_address first (to Top) '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=customers_email_address-desc" . $oscid; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TABLE_HEADING_CUSTOMERS_EMAIL . ' --> customers_email_address last (to Bottom)'); ?></a><br><?php echo TABLE_HEADING_CUSTOMERS_EMAIL; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent" align="left" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '11', '12'); ?>&nbsp;<br><?php echo TEXT_ACCOUNT_APPROVAL?></td>
                    <td class="dataTableHeadingContent" align="right" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '11', '12'); ?>&nbsp;<br><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                  </tr>
                  <?php
                  $search = '';
                  if ( isset($_GET['search']) && tep_not_null($_GET['search']) ) {
                    $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
                      $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or lower(c.customers_email_address) like '%" . $keywords . "%'";
                  }
                  if ($search == "") {$criteria = " where ";} else {$criteria = " and ";}
                  if(!isset($_REQUEST['filterstatus'])) {
                    $criteria .= "  customers_account_approval='Pending' ";
                  } else {
                    $criteria .= "  customers_account_approval='" . $_REQUEST['filterstatus'] . "' ";
                  }
                  $customers_query_raw = "select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_group_id, c.customers_group_ra, a.entry_country_id, a.entry_company, cg.customers_group_name,customers_account_approval, a.entry_telephone as customers_telephone,customers_email_address,entry_state  from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id left join customers_groups cg on c.customers_group_id = cg.customers_group_id " . $search .$criteria." order by $order";
                  $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
                  $customers_query = tep_db_query($customers_query_raw);
                  while ($customers = tep_db_fetch_array($customers_query)) {
                    $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
                    $info = tep_db_fetch_array($info_query);
                    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $customers['customers_id']))) && !isset($cInfo)) {
                      $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customers['entry_country_id'] . "'");
                      $country = tep_db_fetch_array($country_query);
                      $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
                      $reviews = tep_db_fetch_array($reviews_query);
                      $customer_info = array_merge((array)$country, (array)$info, (array)$reviews);
                      $cInfo_array = array_merge($customers, $customer_info);
                      $cInfo = new objectInfo($cInfo_array);
                    }
                    if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) {
                      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '\'">' . "\n";
                    } else {
                      echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '\'">' . "\n";
                    }
                    ?>
                    <td class="dataTableContent">
                      <?php
                      if (strlen($customers['entry_company']) > 16 ) {
                        print ("<acronym title=\"".$customers['entry_company']."\">".substr($customers['entry_company'], 0, 16)."&#160;</acronym>");
                      } else {
                        echo $customers['entry_company'];
                      }
                      ?>
                    </td>
                    <td class="dataTableContent">
                      <?php
                      if (strlen($customers['customers_lastname']) > 15 ) {
                        print ("<acronym title=\"".$customers['customers_lastname']."\">".substr($customers['customers_lastname'], 0, 15)."&#160;</acronym>");
                      } else {
                        echo $customers['customers_lastname'];
                      }
                      ?>
                    </td>
                    <td class="dataTableContent">
                      <?php
                      if (strlen($customers['customers_firstname']) > 15 ) {
                        print ("<acronym title=\"".$customers['customers_firstname']."\">".substr($customers['customers_firstname'], 0, 15)."&#160;</acronym>");
                      } else {
                        echo $customers['customers_firstname'];
                      }
                      ?>
                    </td>
                    <td class="dataTableContent"><?php echo $customers['entry_state']?></td>
                    <td class="dataTableContent" align="right"><?php echo (isset($info['customers_telephone']) ? tep_date_short($info['customers_telephone']) : ''); ?></td>
                    <td class="dataTableContent" align="middle"><?php echo $customers['customers_email_address']?></td>
                    <td class="dataTableContent" align="right"><?php echo $customers['customers_account_approval']; ?></td>
                    <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                    </tr>
                    <?php
                  } // end of while ($customers = tep_db_fetch_array($customers_query)) {
                  ?>
                  <tr>
                    <td colspan="8"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                        <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                      </tr>
                      <?php
                      if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
                        ?>
                        <tr>
                          <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_PENDING_ACCOUNTS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                        </tr>
                        <?php
                      }
                      ?>
                    </table></td>
                  </tr>
                  <?php
                  if (!isset($_GET['search'])) {
                    $customers_groups_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
                    while ($existing_customers_groups =  tep_db_fetch_array($customers_groups_query)) {
                      $existing_customers_groups_array[] = array("id" => $existing_customers_groups['customers_group_id'], "text" => $existing_customers_groups['customers_group_name']);
                    }
                    $count_groups_query = tep_db_query("select customers_group_id, count(*) as count from " . TABLE_CUSTOMERS . " group by customers_group_id order by count desc");
                    while ($count_groups = tep_db_fetch_array($count_groups_query)) {
                      for ($n = 0; $n < sizeof($existing_customers_groups_array); $n++) {
                        if ($count_groups['customers_group_id'] == $existing_customers_groups_array[$n]['id']) {
                          $count_groups['customers_group_name'] = $existing_customers_groups_array[$n]['text'];
                        }
                      } // end for ($n = 0; $n < sizeof($existing_customers_groups_array); $n++)
                      $count_groups_array[] = array("id" => $count_groups['customers_group_id'], "number_in_group" => $count_groups['count'], "name" => $count_groups['customers_group_name']);
                    }
                    ?>
                    <tr>
                      <td style="padding-top: 10px;" align="center" colspan="7"><table border="0" cellspacing="0" cellpadding="2" style="border: 1px solid #c9c9c9">
                        <tr class="dataTableHeadingRow">
                          <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS_GROUPS ?></td>
                          <td class="dataTableHeadingContent">&#160;</td>
                          <td class="dataTableHeadingContent" align="right"><?php echo TEXT_PENDING;?></td>
                        </tr>
                        <?php $c = '0'; // variable used for background coloring of rows
                        for ($z = 0; $z < sizeof($count_groups_array); $z++) {
                          $bgcolor = ($c++ & 1) ? ' class="dataTableRow"' : '';
                          ?>
                          <tr<?php echo $bgcolor; ?>>
                            <td class="dataTableContent"><?php echo $count_groups_array[$z]['name']; ?></td>
                            <td class="dataTableContent">&#160;</td>
                            <td class="dataTableContent" align="center"><?php echo $count_groups_array[$z]['number_in_group'] ?></td>
                          </tr>
                          <?php
                        } // end for ($z = 0; $z < sizeof($count_groups_array); $z++)
                        ?>
                      </table></td>
                    </tr>
                    <?php
                  } // end if (!isset($_GET['search']))
                  ?>
                </table></td>
                <?php
                $heading = array();
                $contents = array();
                switch ($action) {
                  case 'confirm':
                    $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');
                    $contents = array('form' => tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deleteconfirm'));
                    $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
                    if (isset($cInfo->number_of_reviews) && ($cInfo->number_of_reviews) > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '<a href="' . tep_href_link(FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                    break;       
                  default:
                    if (isset($cInfo) && is_object($cInfo)) {
                      $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
                      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>'); 
                      if (B2B_REQUIRE_ACCOUNT_APPROVAL == 'true') {
                        echo $cInfo->customers_account_approval;  
                        switch ($cInfo->customers_account_approval) {
                          case "Pending":
                            $selectpending="selected";
                            break;
                          case "Approve":
                            $selectapprove="selected";
                            break;
                          case "Deny":
                            $selectdeny="selected";
                            break;
                          default:
                            $selectpending="selected";
                            break;        
                        }
                        $PAdropdown=tep_draw_form('customers', FILENAME_PENDING_ACCOUNTS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=updateaccountstatus')."<select name='approvalstatus'>
                        <option value='Pending' ".$selectpending.">".TEXT_PENDING."</option>
                        <option value='Approve' ".$selectapprove.">".TEXT_APPROVE."</option>      
                        <option value='Deny' ".$selectdeny.">".TEXT_DENY."</option>
                        </select>&nbsp;&nbsp;".tep_image_submit('button_update.gif', IMAGE_UPDATE).'</form>';
                        $contents[] = array('text' => '<br>'.ENTRY_CUSTOMERS_ACCOUNT_VALIDATED.'<br>' .$PAdropdown);
                      }
                      $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' <b>' . tep_date_short($cInfo->date_account_created) . '</b>');
                      $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' <b>' . tep_date_short($cInfo->date_account_last_modified) . '</b>');
                      $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_LAST_LOGON . ' <b>'  . tep_date_short($cInfo->date_last_logon) . '</b>');
                      $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_LOGONS . ' <b>' . $cInfo->number_of_logons . '</b>');
                      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . ' <b>' . $cInfo->countries_name . '</b>');
                      $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_REVIEWS . ' <b>' . $cInfo->number_of_reviews . '</b>');
                    }
                    break;
                }
                if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                  echo '<td width="25%" valign="top">' . "\n";
                  $box = new box;
                  echo $box->infoBox($heading, $contents);
                  echo '</td>' . "\n";
                }
                ?>
              </tr>
            </table></td>
          </tr>
          <?php
        }
        ?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
