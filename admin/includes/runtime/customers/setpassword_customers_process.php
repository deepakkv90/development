<?php

global $error, $entry_password_error, $action, $error_message;

$entry_password_error = false;
$error = false;
$error_message = '';
if ( isset($action) && $action == 'update' && (tep_not_null($_POST['customers_password']) || tep_not_null($_POST['customers_password_confirm'])) ) {
  if ( $_POST['customers_password'] != $_POST['customers_password_confirm'] ) {
    $error = true;
    $entry_password_error = true;
    $error_message = ERROR_PASSWORD_NO_MATCH;  
  } else if (strlen($_POST['customers_password']) < ENTRY_PASSWORD_MIN_LENGTH ) {
    $error = true;
    $entry_password_error = true;
    $error_message = sprintf(ERROR_PASSWORD_MIN_LENGTH, ENTRY_PASSWORD_MIN_LENGTH);  
  } else if (!preg_match('/[0-9]/', $_POST['customers_password']) || !preg_match('/[A-Z]/', $_POST['customers_password']) || !preg_match('/[a-z]/', $_POST['customers_password'])) {
    $error = true;
    $entry_password_error = true;      
    $error_message = ERROR_PASSWORD_NOT_HARDENED;  
  } else {
    $password = tep_encrypt_password($_POST['customers_password']);
    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . $password . "' where customers_id = '" . $_GET['cID'] . "'");
  }
}
?>