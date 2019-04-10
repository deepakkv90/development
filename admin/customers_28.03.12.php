<?php
/*
  $Id: customers.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  // RCI code start
  echo $cre_RCI->get('global', 'top', false);
  echo $cre_RCI->get('customers', 'top', false); 
  // RCI code eof  

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $error = false;
  $processed = false;

  // RCI code start
  echo $cre_RCI->get('customers', 'process', false); 
  // RCI code end

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
        $customers_emailvalidated = tep_db_prepare_input($_POST['customers_emailvalidated']);
        $customers_accountvalidated = tep_db_prepare_input($_POST['customers_accountvalidated']);
// BOF Separate Pricing per Customer
  $customers_group_id = tep_db_prepare_input($_POST['customers_group_id']);
  
  $customers_voucher_amount = tep_db_prepare_input($_POST['customers_voucher_amount']);
  
  //Start Extract $ or % from the customers_voucher_amount input,  
  $search_chars = array("$","%");  
  if(strpos($customers_voucher_amount, "%") !== false) {
  	$customers_voucher_type = "P";
  } else {
  	$customers_voucher_type = "S";
  }  
  $customers_voucher_amount = str_replace($search_chars, "", $customers_voucher_amount);
  if(!is_numeric($customers_voucher_amount)) { $customers_voucher_amount = 0;}
  //End of $ % extraction
  
  $customers_selected_template = tep_db_prepare_input($_POST['customers_selected_template']);
  $customers_group_ra = tep_db_prepare_input($_POST['customers_group_ra']);
  $entry_company_tax_id = tep_db_prepare_input($_POST['entry_company_tax_id']);
  if (isset($_POST['customers_payment_allowed']) && $_POST['customers_payment_settings'] == '1') {
  $customers_payment_allowed = tep_db_prepare_input($_POST['customers_payment_allowed']);
  } else { // no error with subsequent re-posting of variables
  $customers_payment_allowed = '';
  if (isset($_POST['payment_allowed']) && $_POST['customers_payment_settings'] == '1') {
    while(list($key, $val) = each($_POST['payment_allowed'])) {
        if ($val == true) {
        $customers_payment_allowed .= tep_db_prepare_input($val).';';
        }
     } // end while
      $customers_payment_allowed = substr($customers_payment_allowed,0,strlen($customers_payment_allowed)-1);
  } // end if ($_POST['payment_allowed'])
  } // end else ($_POST['customers_payment_allowed']
  if (isset($_POST['customers_shipment_allowed']) && $_POST['customers_shipment_settings'] == '1') {
  $customers_shipment_allowed = tep_db_prepare_input($_POST['customers_shipment_allowed']);
  } else { // no error with subsequent re-posting of variables

    $customers_shipment_allowed = '';
    if (isset($_POST['shipping_allowed']) && $_POST['customers_shipment_settings'] == '1') {
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
      
      $customers_access_group = $_POST['customers_access_group'];
      $customers_access_group_id = '';
      foreach ($customers_access_group as $value) {
        $customers_access_group_id .= $value . ',';
      }
      $customers_access_group_id = substr($customers_access_group_id, 0, strlen($customers_access_group_id) - 1);
      if ($error == false) {

        $sql_data_array = array('customers_firstname' => $customers_firstname,
                                'customers_lastname' => $customers_lastname,
                                'customers_email_address' => $customers_email_address,
                                'customers_validation' => $customers_emailvalidated,
                                'customers_newsletter' => $customers_newsletter,
// BOF Separate Pricing per Customer
                                'customers_group_id' => $customers_group_id,
                                'customers_access_group_id' => $customers_access_group_id,
                                'customers_selected_template' => $customers_selected_template,
                                'customers_group_ra' => $customers_group_ra,
                                'customers_payment_allowed' => $customers_payment_allowed,
                                'customers_shipment_allowed' => $customers_shipment_allowed,
                                'customers_account_approval' =>$customers_accountvalidated);
// EOF Separate Pricing per Customer

        if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
        if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);

        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "'");

        //tep_db_query("update ".TABLE_COUPON_GV_CUSTOMER." set amount = ".$customers_voucher_amount." where customer_id = ".(int)$customers_id."");

        //insert the customer vouncher amount
        $str = tep_db_query("select * from ".TABLE_COUPON_GV_CUSTOMER." where customer_id = ".(int)$customers_id."");
        $number_of_customer = tep_db_num_rows($str);
        if($number_of_customer == 0){
        	//tep_db_query("insert into ".TABLE_COUPON_GV_CUSTOMER." (customer_id,amount) values (".(int)$customers_id.",".$customers_voucher_amount.")");
			tep_db_query("insert into ".TABLE_COUPON_GV_CUSTOMER." (customer_id,amount,type) values (".(int)$customers_id.",".$customers_voucher_amount.", '".$customers_voucher_type."')");
        } else {
        	tep_db_query("update ".TABLE_COUPON_GV_CUSTOMER." set amount = '".$customers_voucher_amount."', type='".$customers_voucher_type."' where customer_id = ".(int)$customers_id."");
        }
        //End 

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customers_id . "'");

        if ($entry_zone_id > 0) $entry_state = '';

        $sql_data_array = array('entry_firstname' => $customers_firstname,
                                'entry_lastname' => $customers_lastname,
                                'entry_email_address' => $customers_email_address,
                                'entry_telephone' => $customers_telephone,
                                'entry_fax' => $customers_fax,
                                'entry_street_address' => $entry_street_address,
                                'entry_postcode' => $entry_postcode,
                                'entry_city' => $entry_city,
                                'entry_country_id' => $entry_country_id);

// BOF Separate Pricing Per Customer
        if (ACCOUNT_COMPANY == 'true') {
        $sql_data_array['entry_company'] = $entry_company;
         $sql_data_array['entry_company_tax_id'] = $entry_company_tax_id;
        }
// EOF Separate Pricing Per Customer

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

        } else if ($error == true) {
          $cInfo = new objectInfo($_POST);
          $processed = true;
        }

        // RCI call for action update
        echo $cre_RCI->get('customers', 'action', false);
        
        if ($error !== true) {
            $messageStack->add_session('search', sprintf(NOTICE_CUSTOMER_UPDATED, $customers_id), 'success');
          tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));
        }
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

// BOF Separate Pricing Per Customer
// Once all customers with a specific customers_group_id have been deleted from
// the table customers, the next time a customer is deleted, all entries in the table products_groups
// that have the (now apparently obsolete) customers_group_id will be deleted!
// If you don't want that, leave this section out, or comment it out
// Note that when customers groups are deleted from the table customers_groups, all the
// customers with that specific customer_group_id will be changed to customer_group_id = '0' (default/Retail)
$multiple_groups_query = tep_db_query("select customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1'");
while ($group_ids = tep_db_fetch_array($multiple_groups_query)) {
  $multiple_customers_query = tep_db_query("select distinct customers_group_id from " . TABLE_CUSTOMERS . " where customers_group_id = " . $group_ids['customers_group_id'] . " ");
  if (!($multiple_groups = tep_db_fetch_array($multiple_customers_query))) {
    tep_db_query("delete from " . TABLE_PRODUCTS_GROUPS . " where customers_group_id = '" . $group_ids['customers_group_id'] . "'");
  }
}
// EOF Separate Pricing Per Customer

        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$_SESSION['customer_id'] . "'");

        // RCI call for action deleteconfirm
        echo $cre_RCI->get('customers', 'action', false);
        $messageStack->add_session('search', sprintf(NOTICE_CUSTOMER_DELETE, $customers_id), 'warning');
        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action'))));
        break;
      default:
/*$customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_default_address_id from
" . TABLE_CUSTOMERS . " c, 
" . TABLE_ADDRESS_BOOK . " a 
where
a.customers_id = c.customers_id and
a.address_book_id = c.customers_default_address_id and
c.customers_id = '" . (int)$_GET['cID'] . "'");
        $customers = tep_db_fetch_array($customers_query);
        $cInfo = new objectInfo($customers);
*/
// BOF Separate Pricing Per Customer
        //$customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_company_tax_id, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_group_id,  c.customers_group_ra, c.customers_payment_allowed, c.customers_shipment_allowed, c.customers_default_address_id,c.customers_validation,c.customers_account_approval,c.customers_selected_template  from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$_GET['cID'] . "'");
      $customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_company_tax_id, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, a.entry_telephone as customers_telephone , a.entry_fax as customers_fax, c.customers_newsletter, c.customers_group_id, c.customers_access_group_id, c.customers_group_ra, c.customers_payment_allowed, c.customers_shipment_allowed, c.customers_default_address_id,c.customers_validation,c.customers_account_approval,c.customers_selected_template from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$_GET['cID'] . "'");

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
  
    $group_price_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1' and group_price = '1' order by customers_group_id ");

    $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1' and group_access = '1' order by customers_group_id "); 

// EOF Separate Pricing Per Customer
        $customers = tep_db_fetch_array($customers_query);
        if (!is_array($customers)) {
          $customers = array();
        }
        $cInfo = new objectInfo($customers);
    
	    $customers_voucher_amount_query = tep_db_query("select * from  ".TABLE_COUPON_GV_CUSTOMER." where customer_id = ".(int)$_GET['cID']."");     
    	$customers_voucher_amount_res =  tep_db_fetch_array($customers_voucher_amount_query);
    	$i_customers_voucher_amount = $customers_voucher_amount_res['amount']; 
		$i_customers_voucher_type = $customers_voucher_amount_res['type']; 
		if($i_customers_voucher_type=="P") {		
    		$cInfo->customers_voucher_amount = (int)$i_customers_voucher_amount."%";
		} else {
			$cInfo->customers_voucher_amount = "$".number_format($i_customers_voucher_amount,2);
		}

		// BOF Separate Pricing Per Customer
		// $shipment_allowed = explode (";",$cInfo->customers_shipment_allowed);
		// EOF Separate Pricing Per Customer
        
        // RCI call for action default
        echo $cre_RCI->get('customers', 'action', false);
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

<?php if (ACCOUNT_GENDER == 'true') { ?>
  if (document.customers.customers_gender[0].checked || document.customers.customers_gender[1].checked) {
  } else {
    error_message = error_message + "<?php echo JS_GENDER; ?>";
    error = 1;
  }
<?php } ?>

  if (customers_firstname == "" || customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (customers_lastname == "" || customers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

<?php if (ACCOUNT_DOB == 'true') { ?>
  if (customers_dob == "" || customers_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_DOB; ?>";
    error = 1;
  }
<?php } ?>

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

function checkUncheckAll(theElement) {

  var theForm = theElement.form, z = 1;

  for (z=1; z < theForm.length; z++) {

    if (theForm[z].type == 'checkbox' && theForm[z].name == 'customers_access_group[]') {

      if (theElement.checked) {

        theForm[z].checked = true;

      } else{

        theForm[z].checked = false;

      }

    }

  }

}
//--></script>
<?php
  }
?>
<style type="text/css">
.img-data img { vertical-align:middle; margin-right:1px; }
</style>
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
      <tr><?php echo tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('default_address_id', (isset($cInfo->customers_default_address_id) ? $cInfo->customers_default_address_id : 0)); ?>
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
      if (isset($entry_gender_error) && $entry_gender_error == true) {
        echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
      } else {
        echo ($cInfo->customers_gender == 'm') ? MALE : FEMALE;
        echo tep_draw_hidden_field('customers_gender');
      }
    } else {
      if (!isset($cInfo->customers_gender)) {
        $cInfo->customers_gender = '';
      }
      echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE;
    }
?></td>
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
    echo tep_draw_input_field('customers_firstname', (isset($cInfo->customers_firstname) ? $cInfo->customers_firstname : ''), 'maxlength="32"', true);
  }
?></td>
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
    echo tep_draw_input_field('customers_lastname', (isset($cInfo->customers_lastname) ? $cInfo->customers_lastname : ''), 'maxlength="32"', true);
  }
?></td>
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
      echo tep_draw_input_field('customers_dob', (isset($cInfo->customers_dob) ? tep_date_short($cInfo->customers_dob) : ''), 'maxlength="10"', true);
    }
?></td>
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
    echo tep_draw_input_field('customers_email_address', (isset($cInfo->customers_email_address) ? $cInfo->customers_email_address : ''), 'maxlength="96"', true);
  }
?></td>
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
      if (isset($entry_company_error) && $entry_company_error == true) {
        echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_ERROR;
      } else {
        echo $cInfo->entry_company . tep_draw_hidden_field('entry_company');
      }
    } else {
      echo tep_draw_input_field('entry_company', (isset($cInfo->entry_company) ? $cInfo->entry_company : ''), 'maxlength="32"');
    }
?></td>
<!-- BOF Separate Pricing Per Customer -->
          </tr>
            <tr>
            <td class="main"><?php echo ENTRY_COMPANY_TAX_ID; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if (isset($entry_company_tax_id_error) && $entry_company_tax_id_error == true) {
        echo tep_draw_input_field('entry_company_tax_id', $cInfo->entry_company_tax_id, 'maxlength="32" readonly') . '&nbsp;' . ENTRY_COMPANY_TAX_ID_ERROR;
      } else {
        echo $cInfo->entry_company . tep_draw_hidden_field('entry_company_tax_id');
      }
    } else {
      echo tep_draw_input_field('entry_company_tax_id', (isset($cInfo->entry_company_tax_id) ? $cInfo->entry_company_tax_id : ''), 'maxlength="32" readonly');
      }
?></td>
          </tr>
		  
		   <tr>
            <td class="main"><?php echo ENTRY_CUSTOMER_NUMBER; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if (isset($entry_customer_number_error) && $entry_customer_number_error == true) {
        echo tep_draw_input_field('customers_id', $cInfo->customers_id, 'maxlength="32" readonly') . '&nbsp;' . ENTRY_CUSTOMER_NUMBER_ERROR;
      } else {
        echo $cInfo->customers_id . tep_draw_hidden_field('customers_id');
      }
    } else {
      echo tep_draw_input_field('customers_id', (isset($cInfo->customers_id) ? $cInfo->customers_id : ''), 'maxlength="32" readonly');
      }
?></td>
          </tr>
      
          <tr>
            <td class="main"><?php echo ENTRY_CUSTOMERS_GROUP_REQUEST_AUTHENTICATION; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if (isset($customers_group_ra_error) && $customers_group_ra_error == true) {
        echo tep_draw_radio_field('customers_group_ra', '0', false, $cInfo->customers_group_ra) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_NO . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_group_ra', '1', false, $cInfo->customers_group_ra) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_YES . '&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_ERROR;
      } else {
        echo ($cInfo->customers_group_ra == '0') ? ENTRY_CUSTOMERS_GROUP_RA_NO : ENTRY_CUSTOMERS_GROUP_RA_YES;
        echo tep_draw_hidden_field('customers_group_ra');
      }
    } else {
      if (!isset($cInfo->customers_group_ra)) {
        $cInfo->customers_group_ra = '';
      }
     echo tep_draw_radio_field('customers_group_ra', '0', true,$cInfo->customers_group_ra) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_NO . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_group_ra', '1', false, $cInfo->customers_group_ra) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_GROUP_RA_YES;
    }
?></td>
          </tr>
<!-- EOF Separate Pricing Per Customer -->
          </tr>


</td>
          </tr>
        </table></td>
      </tr>
<?php
    }
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
    echo tep_draw_input_field('entry_street_address', (isset($cInfo->entry_street_address) ? $cInfo->entry_street_address : ''), 'maxlength="64"', true);
  }
?></td>
          </tr>
<?php
    if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if (isset($entry_suburb_error) && $entry_suburb_error == true) {
        echo tep_draw_input_field('suburb', $cInfo->entry_suburb, 'maxlength="32"') . '&nbsp;' . ENTRY_SUBURB_ERROR;
      } else {
        echo $cInfo->entry_suburb . tep_draw_hidden_field('entry_suburb');
      }
    } else {
      echo tep_draw_input_field('entry_suburb', (isset($cInfo->entry_suburb) ? $cInfo->entry_suburb : ''), 'maxlength="32"');
    }
?></td>
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
    echo tep_draw_input_field('entry_postcode', (isset($cInfo->entry_postcode) ? $cInfo->entry_postcode : 0), 'maxlength="10"', true);
  }
?></td>
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
    echo tep_draw_input_field('entry_city', (isset($cInfo->entry_city) ? $cInfo->entry_city : ''), 'maxlength="32"', true);
  }
?></td>
          </tr>
<?php
    if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main">
<?php
  if (!isset($cInfo->entry_country_id)) {
    $cInfo->entry_country_id = 0;
  }
  if (!isset($cInfo->entry_zone_id)) {
    $cInfo->entry_zone_id = 0;
  }
  if (!isset($cInfo->entry_state)) {
    $cInfo->entry_state = 0;
  }
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

?></td>
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
?></td>
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
    echo tep_draw_input_field('customers_telephone', (isset($cInfo->customers_telephone) ? $cInfo->customers_telephone : ''), 'maxlength="32"', true);
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main">
<?php
  if ($processed == true) {
    echo $cInfo->customers_fax . tep_draw_hidden_field('customers_fax');
  } else {
    echo tep_draw_input_field('customers_fax', (isset($cInfo->customers_fax) ? $cInfo->customers_fax : ''), 'maxlength="32"');
  }
?></td>
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
    echo tep_draw_pull_down_menu('customers_newsletter', $newsletter_array, (isset($cInfo->customers_newsletter) && $cInfo->customers_newsletter == '1') ? '1' : '0');
  }
?></td>
          </tr>
       <!--  BOF Separate Pricing per Customer -->
<tr>
  <td class="main"><?php echo ENTRY_CUSTOMERS_GROUP_NAME; ?></td>
  <?php
  if ($processed != true) {
    $index = 0;
    while ($existing_customers = tep_db_fetch_array($group_price_customers_query)) {
      $group_price_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => " ".$existing_customers['customers_group_name']." ");
      ++$index;
    } 
  } // end if ($processed != true )
?>
  <td class="main">
  <?php 
  if ($processed == true) {
    echo $cInfo->customers_group_id . tep_draw_hidden_field('customers_group_id');
  }else{
    echo tep_draw_pull_down_menu('customers_group_id', $group_price_customers_array, (isset($cInfo->customers_group_id) ? $cInfo->customers_group_id : ''));
  } 
  ?></td>
</tr>
<tr>
  <td class="main" valign="top"><?php echo ENTRY_CUSTOMERS_ACCESS_GROUP; ?></td>
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
    echo $cInfo->customers_access_group_id . tep_draw_hidden_field('customers_access_group_id');
  } else {
    $group_access_array = explode(',', $cInfo->customers_access_group_id);
    foreach ($group_access_array as $group_access) {
      $access_value[$group_access] = true;
    }
    echo tep_draw_checkbox_field('customers_access_group_all', '', false, '', 'onclick="checkUncheckAll(this);"') . '&#160;' . ENTRY_CUSTOMERS_ALL_GROUP . '<br>';
    echo '&#160;' . tep_draw_checkbox_field('customers_access_group[]', 'G', isset($access_value['G'])) . '&#160;' . ENTRY_CUSTOMERS_GUEST_GROUP . '<br>';
    foreach ($existing_customers_array as $group_array) {
      echo '&#160;' . tep_draw_checkbox_field('customers_access_group[]', $group_array['id'], isset($access_value[$group_array['id']])) . $group_array['text'] . '<br>';
    }
  } 
  ?></td>
</tr>
<tr>
  <td class="main"><?php echo ENTRY_CUSTOMERS_EMAIL_VALIDATED;?>  </td>
  <td class="main">
  <?php
  if(ACCOUNT_EMAIL_CONFIRMATION=='true')  { 
    echo tep_draw_pull_down_menu('customers_emailvalidated', $emailvalidated_array, (isset($cInfo->customers_validation) ? $cInfo->customers_validation : ''));
  }else{
     echo  TEXT_EMAIL_VALIDATE_FEATURE . tep_draw_hidden_field('customers_emailvalidated', $cInfo->customers_validation);
  }
  ?>   
  </td>
</tr>  


  <tr>
  <td class="main"><?php echo ENTRY_CUSTOMERS_ACCOUNT_VALIDATED;?>  </td>
  </td>
  <td class="main">
  <?php 
  if(B2B_REQUIRE_ACCOUNT_APPROVAL=='true')
  {  echo tep_draw_pull_down_menu('customers_accountvalidated',$accountvalidated_array, $cInfo->customers_account_approval);
  }else
  {
    echo  TEXT_ACCOUNT_VALIDATE_FEATURE. tep_draw_hidden_field('customers_accountvalidated',(isset($cInfo->customers_account_approval) ? $cInfo->customers_account_approval : ''));
  }
  ?>   
  
  </td>
</tr>




<!-- EOF Separate Pricing per Customer -->
        </table></td>
      </tr>
<?php
      // this call allows for additional customer information to be presented 
      $returned_rci = $cre_RCI->get('customers', 'dataextension');
      echo $returned_rci;
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo SYSTEM_INFORMATION; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">

        <tr>
          <td class="main"><?php echo ENTRY_CUSTOMERS_VOUCHER_AMOUNT; ?></td>
          
          <td class="main">
            <?php
            if ($processed == true) {
            echo $cInfo->customers_voucher_amount . tep_draw_hidden_field('customers_voucher_amount');
            } else {
            // echo tep_draw_input_field('customers_voucher_amount', $cInfo->customers_voucher_amount, 'size="22"');
            echo "<input type = 'text' name = 'customers_voucher_amount' value = ". $cInfo->customers_voucher_amount ." size='22'>";
			
			echo ENTRY_CUSTOMERS_VOUCER_AMOUNT_HELP;
			
            }
            ?>
          </td>
        </tr>

        <tr>
          <td class="main"><?php echo ENTRY_CUSTOMERS_TEMPLATE_NAME; ?></td>
          <?php
          if ($processed != true) {
            
            $existing_templates_query = tep_db_query("select * from ".TABLE_TEMPLATE." order by template_id");
            $existing_templates_array[] = array("id" => '', "text" => "&#160; NONE &#160;");
            while ($existing_templates =  tep_db_fetch_array($existing_templates_query)) {
              $existing_templates_array[] = array("id" => $existing_templates['template_name'], "text" => "&#160;".$existing_templates['template_name']."&#160;");
            }
          } // end if ($processed != true )
        ?>
          <td class="main">
          <?php 
          if ($processed == true) {
            echo $cInfo->customers_selected_template . tep_draw_hidden_field('customers_selected_template');
          }else{
            if(tep_not_null($cInfo->customers_selected_template )) {
              echo tep_draw_pull_down_menu('customers_selected_template', $existing_templates_array, $cInfo->customers_selected_template);
            } else {
              echo tep_draw_pull_down_menu('customers_selected_template', $existing_templates_array, '');
            }
          } 
          ?></td>
        </tr>
        </table></td>
      </tr>
<!-- BOF Separate Pricing per Customer -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php include_once(DIR_WS_LANGUAGES . $language . '/modules.php');
  echo HEADING_TITLE_MODULES_PAYMENT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr bgcolor="#DEE4E8">
            <td class="main" colspan="2"><?php if ($processed == true) {
            if (isset($cInfo->customers_payment_settings) && $cInfo->customers_payment_settings == '1') {
    echo ENTRY_CUSTOMERS_PAYMENT_SET ;
    echo ' : ';
      } else {
    echo ENTRY_CUSTOMERS_PAYMENT_DEFAULT;
      }
      echo tep_draw_hidden_field('customers_payment_settings');
            } else { // $processed != true
            echo tep_draw_radio_field('customers_payment_settings', '1', (isset($cInfo->customers_payment_allowed) && tep_not_null($cInfo->customers_payment_allowed) ? true : false)) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_PAYMENT_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_payment_settings', '0', (isset($cInfo->customers_payment_allowed) && tep_not_null($cInfo->customers_payment_allowed) ? false : true)) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_PAYMENT_DEFAULT ; } ?></td>
    </tr>
<?php if ($processed != true) {
    
    if (isset($cInfo->customers_payment_allowed)) {
      $payments_allowed = explode (";",$cInfo->customers_payment_allowed);
    } else {
      $payments_allowed = array();
    }
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
            <td class="main" colspan="2"><?php if (isset($cInfo->customers_payment_settings) && $cInfo->customers_payment_settings == '1') {
    echo $customers_payment_allowed;
      } else {
    echo ENTRY_CUSTOMERS_PAYMENT_DEFAULT;
      }
      echo tep_draw_hidden_field('customers_payment_allowed'); ?></td>
    </tr>
<?php
 } // end else: $processed == true
?>
     </td>
    </tr>
   </table>
  </td>
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
            <td class="main" colspan="2"><?php if ($processed == true) {
            if (isset($cInfo->customers_shipment_settings) && $cInfo->customers_shipment_settings == '1') {
    echo ENTRY_CUSTOMERS_SHIPPING_SET ;
    echo ' : ';
      } else {
    echo ENTRY_CUSTOMERS_SHIPPING_DEFAULT;
      }
      echo tep_draw_hidden_field('customers_shipment_settings');
            } else { // $processed != true
            echo tep_draw_radio_field('customers_shipment_settings', '1', (isset($cInfo->customers_shipment_allowed) && tep_not_null($cInfo->customers_shipment_allowed) ? true : false)) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_SHIPPING_SET . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_shipment_settings', '0', (isset($cInfo->customers_shipment_allowed) && tep_not_null($cInfo->customers_shipment_allowed) ? false : true)) . '&nbsp;&nbsp;' . ENTRY_CUSTOMERS_SHIPPING_DEFAULT ; } ?></td>
    </tr>
<?php if ($processed != true) {
    
    if (isset($cInfo->customers_shipment_allowed)) {
      $shipment_allowed = explode (";",$cInfo->customers_shipment_allowed);
    } else {
      $shipment_allowed = array();
    }
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
            <td class="main" colspan="2"><?php if (isset($cInfo->customers_shipment_settings) && $cInfo->customers_shipment_settings == '1') {
    echo $customers_shipment_allowed;
      } else {
    echo ENTRY_CUSTOMERS_SHIPPING_DEFAULT;
      }
      echo tep_draw_hidden_field('customers_shipment_allowed'); ?></td>
    </tr>
<?php
 } // end else: $processed == true
?>
     </td>
    </tr>
   </table>
  </td>
      </tr>
<!-- EOF Separate Pricing per Customer -->
<?php
    // RCI start
    echo $cre_RCI->get('customers', 'bottominsideform');
    // RCI end
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php
        // RCI for inserting info outside the form at bottom
        echo $cre_RCI->get('customers', 'bottomoutsideform');
  } else {
?>
      <tr>
        <td>
		
		<!--<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php 
            echo tep_draw_form('search', FILENAME_CUSTOMERS, tep_get_all_get_params(array('search')), 'post'); 
            if (isset($_GET[tep_session_name()])) {
              echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }          
          ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table>
		-->
		
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			<td class="smallText" align="right">				
				<?php           
				echo tep_draw_form('custnum', FILENAME_CUSTOMERS, tep_get_all_get_params(array('custnum')), 'post'); 
					if (isset($_GET[tep_session_name()])) {
					  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
					}          
					echo TABLE_HEADING_CUSTOMERS_NUMBER . ': ' . tep_draw_input_field('custnum'); 
				?>
				</form>
				<!--
			</td>
			<td class="smallText" align="right">
				<?php           
				echo tep_draw_form('macolanum', FILENAME_CUSTOMERS, tep_get_all_get_params(array('macolanum')), 'post'); 
					if (isset($_GET[tep_session_name()])) {
					  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
					}          
					echo TABLE_HEADING_CUSTOMERS_REFERENCE . ': ' . tep_draw_input_field('macolanum'); 
				?>
				</form>			
			</td>
			-->
			<td class="smallText" align="right">
			<?php           
			echo tep_draw_form('search', FILENAME_CUSTOMERS, tep_get_all_get_params(array('search')), 'post'); 
				if (isset($_GET[tep_session_name()])) {
				  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
				}          
				echo TABLE_HEADING_CUSTOMERS_NAME . ': ' . tep_draw_input_field('search'); 
			?>
			</form>
			</td>
			
            
          </tr>
        </table>
		
		</td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
<?php

// BOF customer_sort_admin_v1 adapted for Separate Pricing Per Customer
   $listing = (isset($_GET['listing']) ? $_GET['listing'] : '');
          switch ($listing) {
              case "id-asc":
              $order = "c.customers_id";
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

			  
			  case "cn_name":
              $order = "a.entry_company_tax_id, c.customers_lastname";
              break;
              case "cn_name-desc":
              $order = "a.entry_company_tax_id DESC,c .customers_lastname DESC";
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
          if (isset($_GET[tep_session_name()])) {
            $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
          } else {
            $oscid = '';
          }
?>
               <td class="dataTableHeadingContent" valign="top" width="120"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=company'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=company-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a><br><?php echo TABLE_HEADING_COMPANY; ?></td>
				
				<td class="dataTableHeadingContent" valign="top"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=cn_name'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=cn_name-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a><br><?php echo TABLE_HEADING_CUSTOMERS_NUMBER; ?></td>
				
				<td class="dataTableHeadingContent" valign="top"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=cn_name'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=cn_name-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a><br><?php echo TABLE_HEADING_CUSTOMERS_REFERENCE; ?></td>
				
                <td class="dataTableHeadingContent" valign="top"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=lastname'); ?>">&nbsp;<img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=lastname-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a><br><?php echo TABLE_HEADING_LASTNAME; ?></td>
				
                <td class="dataTableHeadingContent" valign="top"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=firstname'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=firstname-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a><br><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
              
			    <td class="dataTableHeadingContent" valign="bottom"><?php echo TABLE_HEADING_NUMBER_OF_ORDERS; ?></td>
				
                <td class="dataTableHeadingContent" valign="bottom"><?php echo TABLE_HEADING_REVENUE; ?></td>
												
                <td class="dataTableHeadingContent" align="right" valign="top"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=id-asc'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=id-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a><br><?php echo TABLE_HEADING_ACCOUNT_CREATED_SHORT; ?></td>
				
				 <td class="dataTableHeadingContent" align="right" valign="bottom"><?php echo TABLE_HEADING_LAST_ORDER; ?></td>
				 
				 <td class="dataTableHeadingContent" align="right" valign="bottom" style="width:45px;">&nbsp;</td>
				 
                <td class="dataTableHeadingContent" align="right" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '11', '12'); ?>&nbsp;<br><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  // EOF customer_sort_admin_v1 adapted for Separate Pricing Per Customer
    $search = '';
    $keywords = isset($_GET['search']) ? tep_db_input(tep_db_prepare_input($_GET['search'])) : '';
    if (isset($_POST['search'])) {
      $keywords =  tep_db_input(tep_db_prepare_input($_POST['search']));
      // this is added to ba able to pass the avlue along to the next statement
      $_GET['search'] = $keywords;
    }
    if ($keywords != '') {
     // $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or c.customers_email_address like '%" . $keywords . "%'";
     $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or lower(c.customers_email_address) like '%" . $keywords . "%' or (a.entry_company) like '%" . $keywords . "%' ";
    }
	
	//customers id wise search
	$keywords_cn = isset($_GET['custnum']) ? tep_db_input(tep_db_prepare_input($_GET['custnum'])) : '';
	if (isset($_POST['custnum'])) {
      	$keywords_cn =  tep_db_input(tep_db_prepare_input($_POST['custnum']));	  	
    }
	if ($keywords_cn != '') {     
		if(strpos($search, "where")===false) {
			$search .= " where ";			
		} else {
			$search .= " or ";
		}
				
		if(isset($_GET['custnum'])) {
			$search .= " c.customers_id = ". $keywords_cn." ";
		} else {
			$search .= " c.customers_id like '%" . $keywords_cn . "%' ";
		}
		
		$_GET['custnum'] = $keywords_cn;
	}
		
    //macola number wise search
	$keywords_mn = isset($_GET['macolanum']) ? tep_db_input(tep_db_prepare_input($_GET['macolanum'])) : '';
	if (isset($_POST['macolanum'])) {
      	$keywords_mn =  tep_db_input(tep_db_prepare_input($_POST['macolanum']));	  	
    }
	if ($keywords_mn != '') {     
		if(strpos($search, "where")===false) {
			$search .= " where a.entry_company_tax_id like '%" . $keywords_mn . "%' ";
		} else {
			$search .= " or a.entry_company_tax_id like '%" . $keywords_mn . "%' ";
		}
		$_GET['macolanum'] = $keywords_mn;
	}    
	
	//list customers based on artwork status selected from home page chart
	$artwork_text = "";
	$artwork_condition = "";
	if(isset($_GET['artwork']) && $_GET['artwork']!="") {
		
		$search = ""; //reset search value
		
		$artwork_text = " LEFT JOIN artwork aw ON c.customers_id = aw.customers_id ";
		if(strpos($search, "where")===false) {
			$artwork_condition = " where aw.artwork_status='".$_GET['artwork']."' ";
		} else {
			$artwork_condition = " AND aw.artwork_status='".$_GET['artwork']."' ";
		}
		
		$search = $artwork_text . $artwork_condition;
	}
	//list customers based on artwork status selected from home page chart
	
	//list customers based on customers status selected from home page chart	
	
	if(!empty($_GET['status'])) {
		
		$search = ""; //reset search value
		
		if($_GET['status']=="active") {
			$status_text = " c.customers_orders_count > 0 ";
		} else {
			$status_text = " c.customers_orders_count = 0 ";
		}
		
		if(isset($_GET['zone']) && $_GET['zone']!="") {
		
			if($_GET['zone']!=0) {
				$search = " WHERE a.entry_country_id='13'  AND ". $status_text .get_customers_state_filter($_GET['zone']);
			} else {
				$search = " WHERE a.entry_country_id!='13'  AND ". $status_text;
			}
			
		} else {
			$search = " WHERE " . $status_text;
		}
						
	} 
	//list customers based on customers status selected from home page chart
	
    // BOF customer_sort_admin_v1 adapted for Separate Pricing Per Customer
    /*$customers_query_raw = "select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_group_id, c.customers_group_ra, a.entry_country_id, a.entry_company, a.entry_company_tax_id, cg.customers_group_name from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id left join customers_groups cg on c.customers_group_id = cg.customers_group_id " . $search . " order by $order";*/
	
	$customers_query_raw = "select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, c.customers_group_id, c.customers_group_ra, a.entry_country_id, a.entry_company, a.entry_company_tax_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by $order";
	
    $info = array();
    $customers = array();
    $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
      $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $customers['customers_id']))) && !isset($cInfo)) {
        $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customers['entry_country_id'] . "'");
        if ( !$country = tep_db_fetch_array($country_query) ) {
          $country = array();
        }
	
	    $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
        if ( !$reviews = tep_db_fetch_array($reviews_query) ) {
          $reviews = array();
        }

        $customer_info = array_merge($country, $info, $reviews);
        
        $Xcount=count($customer_info);
	if($Xcount>0)
	{
        	$cInfo_array = array_merge($customers, $customer_info);
        	
        }
        else
        {
              $cInfo_array = $customers;
        }	
        //$cInfo = new objectInfo($customer_info);
        $cInfo = new objectInfo($cInfo_array);
       
      }
	   
	   
	   //get orders details
	  $order_info_query = tep_db_query("SELECT MAX(o.date_purchased) as date_purchased, COUNT(o.orders_id) as ord_count, SUM(ot.value) as revenue FROM orders o LEFT JOIN orders_total ot on o.orders_id = ot.orders_id and class='ot_total' WHERE o.customers_id='".$customers['customers_id']."'");
	  $order_info_data = tep_db_fetch_array($order_info_query);	
	  
      
      if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) {
       echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '\'">' . "\n";
        
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $cInfo->customers_id) . '\'">' . "\n";
      
      }

// BOF customer_sort_admin_v1 adapted for Separate Pricing Per Customer 
?>
               <td class="dataTableContent"><?php echo $customers['entry_company'];  ?></td>
			   <td class="dataTableContent"><?php echo $customers['customers_id'] ; ?></td>
			   <td class="dataTableContent"><?php echo $customers['entry_company_tax_id'] ; ?></td>
               <td class="dataTableContent"><?php
                if (strlen($customers['customers_lastname']) > 15 ) {
             print ("<acronym title=\"".$customers['customers_lastname']."\">".substr($customers['customers_lastname'], 0, 15)."&#160;</acronym>");
             } else {
                echo $customers['customers_lastname']; } ?></td>
                <td class="dataTableContent"><?php
                if (strlen($customers['customers_firstname']) > 15 ) {
             print ("<acronym title=\"".$customers['customers_firstname']."\">".substr($customers['customers_firstname'], 0, 15)."&#160;</acronym>");
             } else {
            echo $customers['customers_firstname']; } ?></td>    
				
				<td class="dataTableContent"><?php echo $order_info_data['ord_count']; ?></td>
				<td class="dataTableContent"><?php echo "$".number_format($order_info_data['revenue'],2,'.',''); ?></td>
                <td class="dataTableContent" align="right"><?php echo tep_date_short($info['date_account_created']); ?></td>
				<td class="dataTableContent" align="right"><?php echo tep_date_short($order_info_data['date_purchased']); ?></td>
				
				<td class="dataTableContent img-data" align="center" style="width:45px;">
					
					<?php 
						//check this orders contains design or not
						$artwork_qry = tep_db_query("SELECT artwork_id,artwork_status from artwork where customers_id='".$customers['customers_id']."'");
						if(tep_db_num_rows($artwork_qry)>0) { 
							
							$pending = tep_db_fetch_array(tep_db_query("SELECT count(artwork_id) as pcnt from artwork where customers_id='".$customers['customers_id']."' and artwork_status='pending'")); 
							$revision = tep_db_fetch_array(tep_db_query("SELECT count(artwork_id) as rcnt from artwork where customers_id='".$customers['customers_id']."' and artwork_status='revision'")); 
							$approved = tep_db_fetch_array(tep_db_query("SELECT count(artwork_id) as acnt from artwork where customers_id='".$customers['customers_id']."' and (artwork_status='approved' OR artwork_status='approve')"));
							 
							 if($_GET['artwork']!="") {
							 	$design_img = "artwork-".$_GET['artwork'].".png";
							 } else {
							 
								 if($pending['pcnt']>0) {
									$design_img = "artwork-pending.png";
								 } else if($revision['rcnt']>0) {
									$design_img = "artwork-revision.png";
								 } else if($approved['acnt']>0) {
									$design_img = "artwork-approved.png";
								 }
							 
							 }
							
							echo '<a href="' . tep_href_link("artworks.php", 'cID=' . $customers['customers_id'], 'SSL') . '">' . tep_image(DIR_WS_IMAGES . "artwork-icon.png", "Artwork") . tep_image(DIR_WS_IMAGES . $design_img, "Artwork") . '</a>';
						}
					?>
					
				</td>
                
                <td class="dataTableContent" align="right">
					<!-- Edit button -->
					<?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers['customers_id'] . '&action=edit', 'SSL') . '">' . tep_image(DIR_WS_ICONS . 'magnifier.png', ICON_PREVIEW) . '</a>&nbsp;'; ?>
					
					<?php if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;
					
					</td>
              </tr>
<?php
    }
?>
                </table>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr><!-- BOF customer_sort_admin_v1 adapted for Separate Pricing Per Customer colspan 4 to 7 -->
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<!-- EOF customer_sort_admin_v1 adapted for Separate Pricing Per Customer -->
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>              
                    <?php
                    // RCI code start
                    echo $cre_RCI->get('customers', 'listingbottom');
                    // RCI code eof
                    ?>
                  </tr>
                </table></td>
              </tr>                    
<?php
    if ((isset($_POST['search']) && tep_not_null($_POST['search'])) || (isset($_GET['search']) && tep_not_null($_GET['search']))) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<!-- BOF Separate Pricing Per Customer: show numbers of customers in each customers group -->
<?php
   if (!isset($_POST['search']) && !isset($_GET['search'])) {
   $customers_groups_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " WHERE group_status = '1' order by customers_group_id ");
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
  $count_groups_array[] = array("id" => $count_groups['customers_group_id'], "number_in_group" => $count_groups['count'], "name" => (isset($count_groups['customers_group_name']) ? $count_groups['customers_group_name'] : ''));
   }
?>
              <tr>
           <td style="padding-top: 10px;" align="center" colspan="7"><table border="0" cellspacing="0" cellpadding="2" style="border: 1px solid #c9c9c9">
     <tr class="dataTableHeadingRow">
     <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS_GROUPS ?></td>
     <td class="dataTableHeadingContent">&#160;</td>
     <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_CUSTOMERS_NO;?></td>
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
?>     </table>
     </td>
              <tr>
<?php
  } // end if (!isset($_POST['search']))
?>
<!-- EOF Separate Pricing Per Customer: show numbers of customers in each customers group -->
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'confirm':
// BOF Separate Pricing Per Customer: dark grey field with customer name higher
      $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');
// EOF Separate Pricing Per Customer
      $contents = array('form' => tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      if (isset($cInfo->number_of_reviews) && ($cInfo->number_of_reviews) > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>[' . $cInfo->customers_id . ']&nbsp;' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
        $contents[] = array('align' => 'center',
                            'text' => '<br><a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>' .
                            '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br>' .
                            tep_draw_separator('pixel_trans.gif', '1', '20') . '<a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a>' . 
                            '<a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $cInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a><br>'
                          );
        $contents[] = array('align' => 'center',
                            'text' => '<a href="' . tep_href_link(FILENAME_CREATE_ORDER, 'Customer=' . $cInfo->customers_id) . '">' . tep_image_button('button_create_order.gif', IMAGE_BUTTON_CREATE_ORDER) . '</a>' .
							
							tep_draw_separator('pixel_trans.gif', '1', '20') . '<a href="' . tep_href_link(FILENAME_CUSTOMER_FILES, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_CUSTOMER_FILES) .
							tep_draw_separator('pixel_trans.gif', '1', '20') . '<a href="' . tep_href_link(FILENAME_ARTWORKS, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_ARTWORKS) . '</a><br>'
                            );
        if (ACCOUNT_EMAIL_CONFIRMATION == 'true') {
          $contents[] = array('align' => 'center',
                              'text' => '<a href="' . tep_href_link(FILENAME_VALIDATE_NEW, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=confirm') . '">' . tep_image_button('button_resend_validation.gif', IMAGE_BUTTON_RESEND_VALIDATION) . '</a>');
        }
        //RCI customer sidebar buttons top
        $returned_rci = $cre_RCI->get('customers', 'sidebarbuttons');
        $contents[] = array('align' => 'center', 'text' => $returned_old_rci . $returned_rci);
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' <b>' . tep_date_short($cInfo->date_account_created) . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' <b>' . tep_date_short($cInfo->date_account_last_modified) . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_LAST_LOGON . ' <b>'  . tep_date_short($cInfo->date_last_logon) . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_LOGONS . ' <b>' . $cInfo->number_of_logons . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . ' <b>' . (isset($cInfo->countries_name) ? $cInfo->countries_name : '') . '</b>');
        //RCI customer sidebar buttons bottom
        $returned_rci = $cre_RCI->get('customers', 'sidebarbottom');
        $contents[] = array('text' => $returned_rci);
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    <?php
    }
    // RCI code start
    echo $cre_RCI->get('customers', 'bottom'); 
    echo $cre_RCI->get('global', 'bottom');                                      
    // RCI code eof
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