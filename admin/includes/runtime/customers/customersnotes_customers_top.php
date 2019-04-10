<?php

global $customer_comments;

$customer_comments = array();
$query = tep_db_query("select admin_id, admin_name, comments, date_added from " . TABLE_CUSTOMERS_COMMENTS . " where customers_id = '" . $_GET['cID'] . "' order by date_added desc");
while ( $data = tep_db_fetch_array($query)) {
  $customer_comments[] = $data;
}

?>
