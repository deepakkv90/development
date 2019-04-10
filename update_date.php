<?php
require('includes/application_top.php');

$selOrders = tep_db_query("SELECT * FROM orders where exists (select orders_id from orders_status_history) and last_modified='0000-00-00 00:00:00'");
$i = 1;
while($orders_arr = tep_db_fetch_array($selOrders)) {
		
	$orders_history = tep_db_query("select date_added from orders_status_history where orders_id='".$orders_arr['orders_id']."' order by orders_status_history_id desc limit 0,1");
	$status_arr = tep_db_fetch_array($orders_history);
	
	echo $i . " - " . $orders_arr['orders_id']. " - " . $status_arr['date_added'] . "<br>";
	
	$i++;
	
}

?>