<?php
/*
  $Id: update_customers_orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

    require('includes/application_top.php');
  
	// query to update customers orders count field in customers table
  	
	$selcust = tep_db_query("SELECT customers_id from customers");
	
	echo tep_db_num_rows($selcust);
	
	echo "<br>";
	
	//exit;
	
	while($custarr = tep_db_fetch_array($selcust)) {
		$custid = $custarr["customers_id"];
		$selord = tep_db_query("SELECT count(orders_id) as ordcount FROM orders WHERE customers_id='".$custid."'");
		$custorders = tep_db_fetch_array($selord);
		
		//if($custorders['ordcount']>0) {
			tep_db_query("update customers set customers_orders_count='".$custorders['ordcount']."' WHERE customers_id='".$custid."'");
		//}		
	}
	
	echo "finished";
	
	exit;	
?>