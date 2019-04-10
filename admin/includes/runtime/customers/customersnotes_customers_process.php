<?php

global $action, $tmp_cust_arry;

if ( isset($action) && $action == 'update' && tep_not_null($_POST['admin_commetns']) ) {
  $last_name = tep_db_fetch_array(tep_db_query("select admin_lastname from " . TABLE_ADMIN . " where admin_id = '" . $_SESSION['login_id'] . "'"));
  $sql_data = array('customers_id' => $_GET['cID'],
                    'admin_id' => $_SESSION['login_id'],
                    'admin_name' => $_SESSION['login_firstname'] . ' ' . $last_name['admin_lastname'],
                    'comments' => nl2br($_POST['admin_commetns']),
                    'date_added' => 'now()');
  tep_db_perform(TABLE_CUSTOMERS_COMMENTS, $sql_data);
  /*if ( $action == 'add_note' ) {
    tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'action=edit'));
  }*/
  $tmp_cust_arry = array('admin_id' => $_SESSION['login_id'],
                    'admin_name' => $_SESSION['login_firstname'] . ' ' . $last_name['admin_lastname'],
                    'comments' => nl2br($_POST['admin_commetns']),
                    'date_added' => date('Y-m-d H:m:s'));

}

?>
