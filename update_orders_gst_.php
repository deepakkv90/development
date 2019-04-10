<?php
/*
  $Id: update_orders_gst.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
	//Get order info
	//$sql_query = tep_db_query("select o.orders_id from " . TABLE_ORDERS . " o  WHERE o.order_display = '1' AND o.crm_order = '1' AND (o.date_purchased >= '2011-07-01T00:00:00' AND o.date_purchased < '2011-07-02T00:00:00')");
	
	$sql_query = tep_db_query("select o.orders_id from " . TABLE_ORDERS . " o  WHERE o.order_display = '1' AND (o.date_purchased >= '2011-07-01T00:00:00' AND o.date_purchased < '2011-08-01T00:00:00')");
								
	while($sql_array = tep_db_fetch_array($sql_query)) {
	 	$orders_id = $sql_array['orders_id'];
		$subtotal_qry = tep_db_query("SELECT * FROM orders_total WHERE class='ot_subtotal' AND orders_id='".$orders_id."'");
		$subtotal_arr = tep_db_fetch_array($subtotal_qry);
		
		$gsubtotal_qry = tep_db_query("SELECT * FROM orders_total WHERE class='ot_grand_subtotal' AND orders_id='".$orders_id."'");
		$gsubtotal_arr = tep_db_fetch_array($gsubtotal_qry);
		
		$total_qry = tep_db_query("SELECT * FROM orders_total WHERE class='ot_total' AND orders_id='".$orders_id."'");
		$total_arr = tep_db_fetch_array($total_qry);
		
		$gst_qry = tep_db_query("SELECT * FROM orders_total WHERE class='ot_gst_tax' AND orders_id='".$orders_id."'");
		$gst_arr = tep_db_fetch_array($gst_qry);
		
		$shipping_qry = tep_db_query("SELECT * FROM orders_total WHERE class='ot_shipping' AND orders_id='".$orders_id."'");
		$shipping_arr = tep_db_fetch_array($shipping_qry);
		
		$gsttotal_qry = tep_db_query("SELECT * FROM orders_total WHERE class='ot_gst_total' AND orders_id='".$orders_id."'");
		$gsttotal_arr = tep_db_fetch_array($gsttotal_qry);
		
		if(tep_db_num_rows($gsubtotal_qry)==0 && tep_db_num_rows($subtotal_qry)>0) {
			$subtot = $subtotal_arr["value"];
		} else {
			$subtot = $gsubtotal_arr["value"];
		}
					
		$subtot_ex_tax = $subtot + $shipping_arr["value"];
		$tax = ($subtot_ex_tax * 10)/100;
		$tax_text = $currencies->format($tax); 
				
		$total = $subtot_ex_tax + $tax;	
		$total_text = $currencies->format($total); 
		
			
		if(tep_db_num_rows($gsttotal_qry)==0 && tep_db_num_rows($gst_qry)==0) {
			
			echo "<font color='red'>Orders: ". $orders_id . "<br>";
			echo $subtotal_arr["class"] . ": " .$subtotal_arr["value"]."<br>";
			echo $gsubtotal_arr["class"] . ": " .$gsubtotal_arr["value"]."<br>";
			echo $shipping_arr["class"] . ": " .$shipping_arr["value"]."<br>";
			echo $gst_arr["class"] . ": " .$gst_arr["value"]."<br>";
			echo $gsttotal_arr["class"] . ": " .$gsttotal_arr["value"]."<br>";
						
			echo $total_arr["value"] . " - " . $total . "<br>";	
			
			if($total_arr["value"] == $total) {
				echo "same total<br>";				
				tep_db_query("INSERT INTO orders_total set orders_id='".$orders_id."', class='ot_gst_total', value='".$tax."', title='GST Total: ', text='".$tax_text."', sort_order='60'");
				
			}
			else {
					
				tep_db_query("insert into orders_total set orders_id='".$orders_id."', class='ot_gst_total', value='".$tax."', title='GST Total: ', text='".$tax_text."', sort_order='60'");
				$totals_orders_total_id = $total_arr["orders_total_id"];
				tep_db_query("update orders_total set value='".$total."', text='".$total_text."' where orders_total_id='".$totals_orders_total_id."'");
				
				
			}		
			
			echo $total_arr["class"] . ": " .$total_arr["value"]."</font><br><br><br>";
			
		}
		else if(tep_db_num_rows($gsttotal_qry)==0 && tep_db_num_rows($gst_qry)>0) {
			echo "<font color='blue'>Orders: ". $orders_id . "<br>";
			echo $subtotal_arr["class"] . ": " .$subtotal_arr["value"]."<br>";
			echo $gsubtotal_arr["class"] . ": " .$gsubtotal_arr["value"]."<br>";
			echo $shipping_arr["class"] . ": " .$shipping_arr["value"]."<br>";
			echo $gst_arr["class"] . ": " .$gst_arr["value"]."<br>";
			echo $gsttotal_arr["class"] . ": " .$gsttotal_arr["value"]."<br>";
						
			echo $total_arr["value"] . " - " . $total . "<br>";	
					
			if($total_arr["value"] == $total) {
				$orders_total_id = $gst_arr["orders_total_id"];
				tep_db_query("update orders_total set class='ot_gst_total', title='GST Total:', sort_order='60' where orders_total_id='".$orders_total_id."'");				
				echo "total same <br>";
				
			}	else {
			
				$orders_total_id = $gst_arr["orders_total_id"];
				tep_db_query("update orders_total set class='ot_gst_total', value='".$tax."', title='GST Total: ', text='".$tax_text."', sort_order='60' where orders_total_id='".$orders_total_id."'");
				$totals_orders_total_id = $total_arr["orders_total_id"];
				tep_db_query("update orders_total set value='".$total."', text='".$total_text."' where orders_total_id='".$totals_orders_total_id."'");
				
				
			}		
			echo $total_arr["class"] . ": " .$total_arr["value"]."</font><br><br><br>";
			
		} 
		else if(tep_db_num_rows($gsttotal_qry)>0 && tep_db_num_rows($gst_qry)==0) {
			
			echo "<font color='green'>Orders: ". $orders_id . "<br>";
			echo $subtotal_arr["class"] . ": " .$subtotal_arr["value"]."<br>";
			echo $gsubtotal_arr["class"] . ": " .$gsubtotal_arr["value"]."<br>";
			echo $shipping_arr["class"] . ": " .$shipping_arr["value"]."<br>";
			echo $gst_arr["class"] . ": " .$gst_arr["value"]."<br>";
			echo $gsttotal_arr["class"] . ": " .$gsttotal_arr["value"]."<br>";
						
			echo $total_arr["value"] . " - " . $total . "<br>";	
					
			if($gsttotal_arr["value"] != $tax) {
				$orders_total_id = $gsttotal_arr["orders_total_id"];
				echo "<font color='red'>Not same</font>";
				tep_db_query("update orders_total set class='ot_gst_total', value='".$tax."', title='GST Total: ', text='".$tax_text."', sort_order='60' where orders_total_id='".$orders_total_id."'");
				$totals_orders_total_id = $total_arr["orders_total_id"];
				tep_db_query("update orders_total set value='".$total."', text='".$total_text."' where orders_total_id='".$totals_orders_total_id."'");
				
			}	
			
			echo $total_arr["class"] . ": " .$total_arr["value"]."</font><br><br><br>";
			
		}
		
		
	}
	
?>