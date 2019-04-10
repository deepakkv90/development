<?php
/*
  $Id: sales_report2.php,v 1.00 2003/03/08 19:25:29 Exp $

  Charly Wilhelm charly@yoshi.ch
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class sales_report {
    var $mode, $globalStartDate, $startDate, $endDate, $actDate, $showDate, $showDateEnd, $sortString, $status, $outlet;

    function sales_report($mode, $startDate = 0, $endDate = 0, $sort = 0, $statusFilter = 0, $filter = 0, $details, $ordersIn, $salesConsultant) {
      // startDate and endDate have to be a unix timestamp. Use mktime !
      // if set then both have to be valid startDate and endDate
      $this->mode = $mode;
      $this->tax_include = DISPLAY_PRICE_WITH_TAX;
	  $this->details = $details;
	  $this->ordersIn = $ordersIn;
	  $this->salesConsultant = $salesConsultant;

       //$this->statusFilter = $statusFilter;
	  $this->statusFilter = "";
	  if(!empty($statusFilter) || $statusFilter!=0) {
	  	$this->statusFilter = explode("_",$statusFilter);
	  }
            
      // get date of first sale
      $firstQuery = tep_db_query("select UNIX_TIMESTAMP(min(date_purchased)) as first FROM " . TABLE_ORDERS);
      $first = tep_db_fetch_array($firstQuery);
      $this->globalStartDate = mktime(0, 0, 0, date("m", $first['first']), date("d", $first['first']), date("Y", $first['first']));
            
      $statusQuery = tep_db_query("select * from orders_status");
      $i = 0;
      while ($outResp = tep_db_fetch_array($statusQuery)) {
        $status[$i] = $outResp;
        $i++;
      }
	  $this->status = $status;
            
      if ($startDate == 0  or $startDate < $this->globalStartDate) {
        // set startDate to globalStartDate
        $this->startDate = $this->globalStartDate;
      } else {
        $this->startDate = $startDate;
      }
      if ($this->startDate > mktime(0, 0, 0, date("m"), date("d"), date("Y"))) {
        $this->startDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
      }

      if ($endDate > mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"))) {
        // set endDate to tomorrow
        $this->endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
      } else {
        $this->endDate = $endDate;
      }
      if ($this->endDate < $this->startDate + 24 * 60 * 60) {
        $this->endDate = $this->startDate + 24 * 60 * 60;
      }

      $this->actDate = $this->startDate;
	  
	  
	  // query for order count
	  $this->queryOrderCnt = "SELECT count(o.orders_id) as order_cnt FROM " . TABLE_ORDERS . " o";
	  
	  //this is to check sales consultant based report		  
	  if($this->ordersIn == 1) {
	  		
			$this->queryItemCnt_2 = "SELECT p.products_model, op.products_quantity, (op.final_price*op.products_quantity) as psum, o.date_purchased FROM " . TABLE_ORDERS . " o JOIN " . TABLE_ORDERS_PRODUCTS . " op ON o.orders_id = op.orders_id JOIN ".TABLE_PRODUCTS ."p ON op.products_id = p.products_id ";
			
			$this->queryShipping = "SELECT ot.value as shipping, o.date_purchased FROM orders o LEFT JOIN orders_total ot ON (ot.orders_id = o.orders_id AND ot.class='ot_shipping') ";
			$this->queryGstTotal = "SELECT ot.value as gst_total FROM orders o LEFT JOIN orders_total ot ON (ot.orders_id = o.orders_id AND ot.class='ot_gst_total') ";
			$this->queryDiscountTotal = "SELECT ot.value as discount_total FROM orders o LEFT JOIN orders_total ot ON (ot.orders_id = o.orders_id AND (ot.class = 'ot_customer_discount' OR ot.class='ot_gv')) ";			
			$this->querySubtotal = "SELECT ot.value as subtotal, o.date_purchased, o.last_modified FROM orders o LEFT JOIN orders_total ot ON (ot.orders_id = o.orders_id AND (ot.class = 'ot_grand_subtotal' OR ot.class='ot_subtotal')) ";
			$this->queryOrderProCostCnt = "SELECT SUM(opc.labour_cost*opc.products_quantity) as pcl_cost, SUM(opc.overhead_cost*opc.products_quantity) as pco_cost, SUM(opc.material_cost*opc.products_quantity) as pcm_cost, count(distinct(o.orders_id)) FROM orders_products_costs opc LEFT JOIN orders o ON opc.orders_id = o.orders_id  ";
			
			
			$this->queryItemCnt = "SELECT pd.products_tax_class_id, pd.products_model, o.orders_id,o.customers_id, o.customers_name, o.customers_company, o.purchase_number, o.last_modified, 	o.date_purchased, o.orders_status, o.order_assigned_to, a.entry_company_tax_id as customer_number, op.products_id as pid, op.orders_products_id, op.products_quantity, op.final_price, op.products_name as pname, sum(op.products_quantity) as pquant, sum(op.final_price * op.products_quantity) as psum, op.products_tax as ptax, count(distinct(o.orders_id)) FROM orders o JOIN orders_products op ON op.orders_id = o.orders_id LEFT JOIN products pd ON pd.products_id = op.products_id LEFT JOIN address_book a ON a.customers_id = o.customers_id ";						
			$this->queryAttr = "SELECT count(op.products_id) as attr_cnt, o.orders_id, opa.orders_products_id, opa.products_options, opa.products_options_values, opa.options_values_price, opa.price_prefix from orders_products_attributes opa LEFT JOIN orders o ON opa.orders_id = o.orders_id LEFT JOIN orders_products op ON op.orders_products_id = opa.orders_products_id ";
						
			$this->queryProCost_2 = "select opc.*, o.date_purchased from orders_products_costs opc LEFT JOIN orders o ON opc.orders_id = o.orders_id ";
			$this->queryProCost = "select pc.categories_id, opc.* from products_to_categories pc, orders_products_costs opc where pc.products_id=opc.products_id and opc.orders_id IN ";					  
			
	  } else {
	  			  				  
			  // query for shipping
			  $this->queryShipping = "SELECT ot.value as shipping, o.date_purchased, o.customers_id FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND  ot.class = 'ot_shipping'";			  
			   // query for GST total
			  $this->queryGstTotal = "SELECT ot.value as gst_total FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND  ot.class = 'ot_gst_total'";
			  //query for discount
			 $this->queryDiscountTotal = "SELECT ot.value as discount_total FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND  (ot.class = 'ot_customer_discount' OR ot.class='ot_gv')";			 
			 //query for subtotal
			 $this->querySubtotal = "SELECT ot.value as subtotal, o.date_purchased, o.last_modified, o.customers_id, a.entry_zone_id FROM orders o, orders_total ot, address_book a WHERE ot.orders_id = o.orders_id AND (ot.class = 'ot_grand_subtotal' OR ot.class='ot_subtotal') AND o.customers_id = a.customers_id ";
			  
			  //products count query			  
			  $this->queryOrderProCostCnt = "SELECT (opc.labour_cost*opc.products_quantity) as pcl_cost, (opc.overhead_cost*opc.products_quantity) as pco_cost, (opc.material_cost*opc.products_quantity) as pcm_cost, o.date_purchased, o.customers_id FROM orders_products_costs opc, orders o WHERE opc.orders_id=o.orders_id ";
			  
			  
			  $this->queryItemCnt_2 = "SELECT p.products_model, op.products_quantity, (op.final_price*op.products_quantity) as psum, o.date_purchased FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_PRODUCTS . " p WHERE o.orders_id = op.orders_id AND op.products_id = p.products_id ";
			  
			  //Orders products query 
			  $this->queryItemCnt = "SELECT pd.products_tax_class_id, pd.products_model, o.orders_id,o.customers_id, o.customers_name, o.customers_company, 
										o.purchase_number, o.last_modified, o.date_purchased, 
										o.orders_status, o.order_assigned_to, a.entry_company_tax_id as customer_number, op.products_id as pid, 
										op.orders_products_id, op.products_quantity, op.final_price, op.products_name as pname, sum(op.products_quantity) as pquant, 
										sum(op.final_price * op.products_quantity) as psum, op.products_tax as ptax FROM " . TABLE_ORDERS . " o, 
										" . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ADDRESS_BOOK . " a, " . TABLE_PRODUCTS . " pd 
										WHERE o.orders_id = op.orders_id and o.customers_id = a.customers_id and op.products_id = pd.products_id";
			  // query for attributes
			  $this->queryAttr = "SELECT count(op.products_id) as attr_cnt, o.orders_id, opa.orders_products_id, opa.products_options, opa.products_options_values, opa.options_values_price, opa.price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " opa, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op WHERE o.orders_id = opa.orders_id AND op.orders_products_id = opa.orders_products_id";	
			  				
			  //products cost
			  $this->queryProCost = "select pc.categories_id, opc.* from products_to_categories pc, orders_products_costs opc  where pc.products_id=opc.products_id and opc.orders_id IN ";			  
			  $this->queryProCost_2 = "select opc.*, o.date_purchased from orders_products_costs opc, orders o  where opc.orders_id=o.orders_id ";				  
	
	}
	
	
      switch ($sort) {
        case '0':
          //$this->sortString = " "; //modified Aug 18, 2010
		  $this->sortString = " order by o.orders_id desc ";
          break;
        case '1':
          //$this->sortString = " order by pname asc "; //modified Aug 18, 2010
		  $this->sortString = " order by o.orders_id desc, pname asc ";
          break;
        case '2':
          //$this->sortString = " order by pname desc"; //modified Aug 18, 2010
		  $this->sortString = " order by o.orders_id desc, pname desc";
          break;
        case '3':
          //$this->sortString = " order by pquant asc, pname asc"; //modified Aug 18, 2010
		  $this->sortString = " order by o.orders_id desc, pquant asc, pname asc";
          break;
        case '4':
          //$this->sortString = " order by pquant desc, pname asc";
		  //$this->sortString = " order by pid desc, pname asc"; //modified Aug 18, 2010
          $this->sortString = " order by o.orders_id desc, pid desc, pname asc";
          break;
        case '5':
		  //$this->sortString = " order by psum asc, pname asc"; //modified Aug 18, 2010
          $this->sortString = " order by o.orders_id desc, psum asc, pname asc";
          break;
        case '6':
          //$this->sortString = " order by psum desc, pname asc"; //modified Aug 18, 2010
		  $this->sortString = " order by o.orders_id desc, psum desc, pname asc";
          break;
      }

    }

    function getNext() {
      switch ($this->mode) {
        // yearly
        case '1':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd), date("Y", $sd) + 1);
          break;
        // monthly
        case '2':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd) + 1, 1, date("Y", $sd));
          break;
        // weekly
        case '3':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd) + 7, date("Y", $sd));
          break;
        // daily
        case '4':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd) + 1, date("Y", $sd));
          break;
      }
      if ($ed > $this->endDate) {
        $ed = $this->endDate;
      }

      $filterString = ""; $filterInvoiceString = "";
	  $shipping_cost = 0; $gst_cost = 0; $discount_cost = 0; $subtotal = 0; 
	  $pcl_cost = 0; $pco_cost = 0; $pcm_cost = 0;
	  
	  if($this->ordersIn == 1) {
	  	
			if($this->salesConsultant=="all") {
				$sales_consultant_text = "";
			} else if($this->salesConsultant=="Empty") {
				$sales_consultant_text = " AND (o.order_assigned_to IS NULL OR o.order_assigned_to='') ";
			}
			else {
				$sales_consultant_text = " AND o.order_assigned_to='".$this->salesConsultant."' ";
			}
	  		
			$dateFilters = " JOIN (SELECT osh.orders_id, MAX(osh.date_added) as maxdate FROM orders_status_history osh WHERE osh.orders_status_id='2' AND osh.date_added >= '" .tep_db_input(date("Y-m-d\TH:i:s", $sd)). "'  AND osh.date_added < '" .tep_db_input(date("Y-m-d\TH:i:s", $ed)). "' group by osh.orders_id ) rst ON rst.orders_id = o.orders_id ";
			
			//Details view = 0 - Starts
			if($this->details==0) {
			
				$rqOrders = tep_db_query($this->queryOrderCnt . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text);
				$order = tep_db_fetch_array($rqOrders);
				
				$rqOrdersItems = tep_db_query($this->queryItemCnt_2 . $dateFilters . " AND o.order_display='1' ".$sales_consultant_text);	
				while($orders_items = tep_db_fetch_array($rqOrdersItems)) {			  		
					$items_count += $orders_items["products_quantity"];
					$products_date = strtotime($orders_items["date_purchased"]);					
					$products_code = explode("-",$orders_items["products_model"]);				
					if($products_code[0]=="" || $products_code[0]<1) { $pmodel = '000'; }
					else { $pmodel = $products_code[0]; }									
					$products_sum[$products_date][$pmodel] += $orders_items["psum"];
			    } 	  
							
				$rqShipping = tep_db_query($this->queryShipping . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text);						
				while($shipping = tep_db_fetch_array($rqShipping)) {
					$shipping_cost += $shipping["shipping"];
					$ship_date = date("M, Y",strtotime($shipping["date_purchased"]));
					$ship_cost[$ship_date] += $shipping["shipping"];
				}
				$rqGstTotal = tep_db_query($this->queryGstTotal . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text);
				while($gst_total = tep_db_fetch_array($rqGstTotal)) {
					$gst_cost += $gst_total["gst_total"];
				}
				$rqDiscountTotal = tep_db_query($this->queryDiscountTotal . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text);
				while($discount_total = tep_db_fetch_array($rqDiscountTotal)) {
					$discount_cost += $discount_total["discount_total"];
				}			
				$rqSubTotal = tep_db_query($this->querySubtotal . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text." GROUP BY o.orders_id");
				while($sub_total_arr = tep_db_fetch_array($rqSubTotal)) {
					$subtotal += $sub_total_arr["subtotal"];
					$purchased_date = date("M, Y",strtotime($sub_total_arr["date_purchased"]));
					$modified_date = date("M, Y",strtotime($sub_total_arr["last_modified"]));										
					$month[$purchased_date] += $sub_total_arr["subtotal"];
					
					$cust_zone = $sub_total_arr["entry_zone_id"];
					$timestamp = strtotime($sub_total_arr["date_purchased"]);					
					$custid = $sub_total_arr["customers_id"];
					$customer_revenue[$custid][$purchased_date] += $sub_total_arr["subtotal"];					
					$customer_revenue_2[$cust_zone][$timestamp] += $sub_total_arr["subtotal"];
				}
				
				$rqOrdProCost = tep_db_query($this->queryOrderProCostCnt . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text." GROUP BY o.orders_id");
				while($product_cost_arr = tep_db_fetch_array($rqOrdProCost)) {
					$pcl_cost += $product_cost_arr["pcl_cost"];
					$pco_cost += $product_cost_arr["pco_cost"];
					$pcm_cost += $product_cost_arr["pcm_cost"];
					$pro_date = date("M, Y",strtotime($product_cost_arr["date_purchased"]));					
					$pro_cost[$pro_date] += $product_cost_arr["pcl_cost"] + $product_cost_arr["pco_cost"] + $product_cost_arr["pcm_cost"];
				}
				
			}
			//Details view = 0 - Ends
			
			//Detail view = 2 or 3		
			if($this->details==1 || $this->details==2 || $this->details==3) {				
					$rqItems = tep_db_query($this->queryItemCnt . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text." GROUP BY op.orders_products_id " . $this->sortString);
					$this->rqOrdProCostInfo = tep_db_query($this->queryProCost_2 . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text." GROUP BY o.orders_id");			
					$this->rqOrdProCostDetail = tep_db_query($this->queryProCost . " ( SELECT o.orders_id from orders o " . $dateFilters . " WHERE o.order_display='1' ".$sales_consultant_text." )");	 	    
			}			
		
	  } else {
	  
		  $dateFilters = " (o.date_purchased >= '" .tep_db_input(date("Y-m-d\TH:i:s", $sd)). "' AND o.date_purchased < '" .tep_db_input(date("Y-m-d\TH:i:s", $ed)). "') ";
		 
		  if (is_array($this->statusFilter)) {				
				$filterStringStatus = "";					
				foreach($this->statusFilter as $status=>$state) {
					$filterStringStatus .= " o.orders_status = " . $state . " OR ";	
					$dateFilters = " (".$dateFilters;
					$filterInvoiceString .= " OR ( o.last_modified >= '" . tep_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.last_modified < '" . tep_db_input(date("Y-m-d\TH:i:s", $ed)) . "')) ";			
				}		
				
				if($filterStringStatus!="") {
					$filterString .= " AND ( " . substr($filterStringStatus, 0, -3) . " ) ";
				}			
		  }
		  
		  //Details view = 0 - Starts
		  if($this->details==0) {
		  
			  $rqOrders = tep_db_query($this->queryOrderCnt . " WHERE o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString);
			  $order = tep_db_fetch_array($rqOrders);	
			  
			  //echo $this->queryItemCnt_2 . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString;
			  
			  $rqOrdersItems = tep_db_query($this->queryItemCnt_2 . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString);				  
			  while($orders_items = tep_db_fetch_array($rqOrdersItems)) {			  		
					$items_count += $orders_items["products_quantity"];
					$products_date = strtotime($orders_items["date_purchased"]);					
					$products_code = explode("-",$orders_items["products_model"]);				
					if($products_code[0]=="" || $products_code[0]<1) { $pmodel = '000'; }
					else { $pmodel = $products_code[0]; }									
					$products_sum[$products_date][$pmodel] += $orders_items["psum"];
			  } 	   
					  
			  $rqShipping = tep_db_query($this->queryShipping . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString);
			  while($shipping = tep_db_fetch_array($rqShipping)) {
					$shipping_cost += $shipping["shipping"];
					$ship_date = date("M, Y",strtotime($shipping["date_purchased"]));
					$ship_cost[$ship_date] += $shipping["shipping"];
			  }
			  $rqGstTotal = tep_db_query($this->queryGstTotal . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString);		  
			  while($gst_total = tep_db_fetch_array($rqGstTotal)) {
					$gst_cost += $gst_total["gst_total"];
			  }		  
			  $rqDiscountTotal = tep_db_query($this->queryDiscountTotal . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString);
			  while($discount_total = tep_db_fetch_array($rqDiscountTotal)) {
					$discount_cost += $discount_total["discount_total"];
			  }
			  
			  $rqSubTotal = tep_db_query($this->querySubtotal . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString . "GROUP BY o.orders_id");
			  while($sub_total_arr = tep_db_fetch_array($rqSubTotal)) {
					$subtotal += $sub_total_arr["subtotal"];
					$purchased_date = date("M, Y",strtotime($sub_total_arr["date_purchased"]));
					$modified_date = date("M, Y",strtotime($sub_total_arr["last_modified"]));										
					$month[$purchased_date] += $sub_total_arr["subtotal"];
					
					$cust_zone = $sub_total_arr["entry_zone_id"];
					$timestamp = strtotime($sub_total_arr["date_purchased"]);					
					$custid = $sub_total_arr["customers_id"];
					$customer_revenue[$custid][$purchased_date] += $sub_total_arr["subtotal"];					
					$customer_revenue_2[$cust_zone][$timestamp] += $sub_total_arr["subtotal"];
					
			  }
			  
			  $rqOrdProCost = tep_db_query($this->queryOrderProCostCnt . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString);
			  while($product_cost_arr = tep_db_fetch_array($rqOrdProCost)) {
					$pcl_cost += $product_cost_arr["pcl_cost"];
					$pco_cost += $product_cost_arr["pco_cost"];
					$pcm_cost += $product_cost_arr["pcm_cost"];
					$pro_date = date("M, Y",strtotime($product_cost_arr["date_purchased"]));					
					$pro_cost[$pro_date] += $product_cost_arr["pcl_cost"] + $product_cost_arr["pco_cost"] + $product_cost_arr["pcm_cost"];
			  }	 	  		
			  
		  }
		  //Details view = 0 - Ends
		  
		  //Detail view = 2 or 3		
		  if($this->details==1 || $this->details==2 || $this->details==3) {
		  
			  $rqItems = tep_db_query($this->queryItemCnt ." AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString." group by orders_products_id " . $this->sortString);	   		  
			  $this->rqOrdProCostInfo = tep_db_query($this->queryProCost_2 . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString);			  
			  $this->rqOrdProCostDetail = tep_db_query($this->queryProCost . " ( SELECT o.orders_id from orders o where o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString.")");
		  }	 	    

	  
	  }
	      
	  //echo $this->queryOrderCnt . " WHERE o.order_display='1' AND " . $dateFilters . $filterInvoiceString . $filterString;
	  //echo "<br><br>";
	  
      // set the return values
      $this->actDate = $ed;
      $this->showDate = $sd;
      $this->showDateEnd = $ed - 60 * 60 * 24;
	  
	  //Execute if details view per products or products costs
	  if($this->details==1 || $this->details==2 || $this->details==3) {
	  
			  // execute the query
			  $cnt = 0;   $itemTot = 0;   $sumTot = 0; 	  $ord_total = 0;
	  
			  while ($resp[$cnt] = tep_db_fetch_array($rqItems)) {
										
				$price = $resp[$cnt]['final_price']; 		
								
				if($this->ordersIn == 1) {
					$rqAttr = tep_db_query($this->queryAttr . $dateFilters . " WHERE o.order_display='1' AND op.products_id = " . $resp[$cnt]['pid'] . $filterString . " group by products_options_values order by orders_products_id");
				} else {
					$rqAttr = tep_db_query($this->queryAttr . " AND o.order_display='1' AND " . $dateFilters . $filterInvoiceString . " AND op.products_id = " . $resp[$cnt]['pid'] . $filterString . " group by products_options_values order by orders_products_id");
				}
				$i = 0;
				while ($attr[$i] = tep_db_fetch_array($rqAttr)) {
				  $i++;
				}
		
				// values per date
				if ($i > 0) {
				  $price2 = 0;
				  $price3 = 0;
				  $option = array();
				  $k = -1;
				  $ord_pro_id_old = 0;
				  for ($j = 0; $j < $i; $j++) {
					if ($attr[$j]['price_prefix'] == "-") {
					  $price2 += (-1) *  $attr[$j]['options_values_price'];
					  $price3 = (-1) * $attr[$j]['options_values_price'];
					  $prefix = "-";
					} else {
					  $price2 += $attr[$j]['options_values_price'];
					  $price3 = $attr[$j]['options_values_price'];
					  $prefix = "+";
					}
					$ord_pro_id = $attr[$j]['orders_products_id'];
					if ( $ord_pro_id != $ord_pro_id_old) {
					  $k++;
					  $l = 0;
					  // set values
					  $option[$k]['quant'] = $attr[$j]['attr_cnt'];
					  $option[$k]['options'][0] = $attr[$j]['products_options'];
					  $option[$k]['options_values'][0] = $attr[$j]['products_options_values'];
					  if ($price3 != 0) {
						$option[$k]['price'][0] = tep_add_tax($price3, $resp[$cnt]['ptax']);
					  } else {
						$option[$k]['price'][0] = 0;
					  }
					} else {
					  $l++;
					  // update values
					  $option[$k]['options'][$l] = $attr[$j]['products_options'];
					  $option[$k]['options_values'][$l] = $attr[$j]['products_options_values'];
					  if ($price3 != 0) {
						$option[$k]['price'][$l] = tep_add_tax($price3, $resp[$cnt]['ptax']);
					  } else {
						$option[$k]['price'][$l] = 0;
					  }
					}
					$ord_pro_id_old = $ord_pro_id;
				  }
				  // set attr value
				  $resp[$cnt]['attr'] = $option;
				} else {
				  $resp[$cnt]['attr'] = "";
				}
				
				$resp[$cnt]['price'] = $price;
				$resp[$cnt]['psum'] = ($resp[$cnt]['products_quantity'] * $price);
				$products_tax = tep_get_tax_rate($resp[$cnt]['products_tax_class_id']);							
				$resp[$cnt]['proquant'] = $resp[$cnt]['products_quantity'];  					
				$sumTot += $resp[$cnt]['psum'];
				$itemTot += $resp[$cnt]['products_quantity'];
				
				$resp[$cnt]['totsum'] = $sumTot;
				$resp[$cnt]['totitem'] = $itemTot;
				
				//get product code based total - start
				$pro_model = explode("-",tep_get_product_model($resp[$cnt]['pid']));				
				if($pro_model[0]=="") { $pcode = '000'; }
				else { $pcode = $pro_model[0]; }
				$resp[0]['pcode'][$pcode][] = $resp[$cnt]['psum'];
				//get product code based total - end
				
				$cnt++;
			  }
	  } //Ends details view 1 2 3
	  
	  if($this->details==0) {		  
	  
		  //$ord_pro_cost_arr = tep_db_fetch_array($rqOrdProCost);	  
		  $resp[0]['labour_cost'] = $pcl_cost;
		  $resp[0]['overhead_cost'] = $pco_cost;
		  $resp[0]['material_cost'] = $pcm_cost;	  
		  $resp[0]['shipping'] = $shipping_cost;
		  $resp[0]['gst_total'] = $gst_cost;
		  $resp[0]['customer_discount'] = $discount_cost;
		  $resp[0]['sub_total'] = $subtotal;	  
		  $resp[0]['orders_total'] = $resp[0]['sub_total'] + $resp[0]['shipping'] + $resp[0]['gst_total'] - $resp[0]['customer_discount']; 	 
		  $resp[0]['order'] = $order['order_cnt']; 
		  $resp[0]['orders_items'] = $items_count; 
		  
		  $resp[0]['chart_revenue'] = $month;
		  $resp[0]['chart_pro_cost'] = $pro_cost;
		  $resp[0]['chart_ship_cost'] = $ship_cost;
	  	  
		  $resp[0]['chart_date'] = $purchased_date;	  
		  $resp[0]['chart_pro_date'] = $pro_date;
		  $resp[0]['chart_ship_date'] = $ship_date;
		  
		  $resp[0]['chart_customers_revenue'] = $customer_revenue;
		  $resp[0]['chart_customers_id'] = $custid;
		  
		  $resp[0]['chart_customers_revenue_2'] = $customer_revenue_2;
		  $resp[0]['chart_products_sum_arr'] = $products_sum;
	 }
	 
	  return $resp;
	  
    }
	
	function getProductCosts($cost_by) {			  
	  
	  if($cost_by == "p_family") {
	  	  
		  $j = 0;
		  
		  while($pro_cost_info = tep_db_fetch_array($this->rqOrdProCostInfo)) {
		  	
			$sel_family = tep_db_query("select manufacturers_id from manufacturers");	
			
			while($family_arr = tep_db_fetch_array($sel_family)) {
			
				$manu_info = tep_get_product_manufacturer($pro_cost_info['products_id']);									
				
				$cate_info = tep_get_product_category($pro_cost_info['products_id']);			
				
				if($family_arr['manufacturers_id'] == $manu_info) {
				
					$cat = $family_arr['manufacturers_id']; 			
					$pro['opc_info'][$cat][$j] = $pro_cost_info;
					$pro['opc_info'][$cat][$j]['main_category_name'] = $cate_info['parent_name'];
					$pro['opc_info'][$cat][$j]['sub_category_name'] = $cate_info['name'];					
				
				}
			
			}
					
			$j++;
			
		  }
				  
		  
	  } 
	  else if($cost_by == "p_code") { 
	  	  
		  //cost info by products model
		  		  		  
		  $models = $this->getProductsModel();
		  		  	  
		  $j = 0;
					  
		  while($ord_pro_cost_info = tep_db_fetch_array($this->rqOrdProCostInfo)) {
															
				$pro_model = explode("-",tep_get_product_model($ord_pro_cost_info['products_id']));
				
				if($pro_model[0]=="" || $pro_model[0]<1) { $pro_model[0] = '000'; }
				
				foreach($models as $keymodels=>$model) {	
					
					if($pro_model[0]==$model) {
					
						$pro['opc_info'][$model][$j] = $ord_pro_cost_info;						
						$pro['opc_info'][$model][$j]['products_code'] = $pro_model[0];						
						$j++;
						
					}	
				}				
				
		  }
			  
	  }
	  else if($cost_by == "p_name") { 
	  	  //cost info by products
		  $j = 0;
		  while($ord_pro_cost_info = tep_db_fetch_array($this->rqOrdProCostInfo)) {
			
			$sel_cat = tep_db_query("select categories_id from categories");	
			
			while($main_cat = tep_db_fetch_array($sel_cat)) {
				$cate_info = tep_get_product_category($ord_pro_cost_info['products_id']);			
				if($main_cat['categories_id'] == $cate_info['id']) {
					$cat = $main_cat['categories_id']; 			
					$pro['opc_info'][$cat][$j] = $ord_pro_cost_info;
					$pro['opc_info'][$cat][$j]['main_category_name'] = $cate_info['parent_name'];
					$pro['opc_info'][$cat][$j]['sub_category_name'] = $cate_info['name'];				
				}
			}		
			$j++;
			
		  }
	 } else {
	 	//cost info by category
		  
		  $j = 0;
		  while($ord_pro_cost_info = tep_db_fetch_array($this->rqOrdProCostInfo)) {
					
			$sel_cat = tep_db_query("select categories_id from categories where parent_id='0'");			
			while($main_cat = tep_db_fetch_array($sel_cat)) {
				$cate_info = tep_get_product_category($ord_pro_cost_info['products_id']);			
				if($main_cat['categories_id'] == $cate_info['parent_id']) {
					$cat = $main_cat['categories_id']; 			
					$pro['opc_info'][$cat][$j] = $ord_pro_cost_info;
					$pro['opc_info'][$cat][$j]['main_category_name'] = $cate_info['parent_name'];
					$pro['opc_info'][$cat][$j]['sub_category_name'] = $cate_info['name'];				
				}
			}		
			$j++;			
		  }
	 } 
	   	  
	  $i = 0;
	  while($ord_pro_cost_detail = tep_db_fetch_array($this->rqOrdProCostDetail)) {
	  	
		$pro['opc_detail'][$i] = $ord_pro_cost_detail;
		$i++;
		
	  }
	  
	  return $pro;
	}
	
	//get different types of products model	
	function getProductsModel() {
		
		$sel_models = tep_db_query("SELECT products_model FROM products ORDER BY products_model DESC");
		$models[] = '000';
		while($models_arr = tep_db_fetch_array($sel_models)) {				
			
			//if($models_arr['products_model']=="" || ) {
				//$models[] = '000'; 
			//} else {
				$procode = explode("-",$models_arr['products_model']); 
				if($procode[0]<1 || $procode[0]=="") { $procode[0] = '000'; }
				$models[] = $procode[0]; 
			//}		
			
		}
						
		return array_unique($models);
		
	}
	
	//get order gst by orders id
	function getGstByOrder($orders_id) {
		$sel_ord_query = tep_db_query("SELECT value FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$orders_id."' AND  class = 'ot_gst_total'");
		$gsttot = 0;
		while($rst_arr = tep_db_fetch_array($sel_ord_query)) {
			$gsttot += $rst_arr["value"];
		}
		return $gsttot;
	}
	
	//get order gst by orders id
	function getShippingByOrder($orders_id) {
		$sel_ord_query = tep_db_query("SELECT value FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$orders_id."' AND  class = 'ot_shipping'");
		$shiptot = 0;
		while($rst_arr = tep_db_fetch_array($sel_ord_query)) {
			$shiptot += $rst_arr["value"];
		}
		return $shiptot;
	}
	
	//get order gst by orders id
	function getDiscountByOrder($orders_id) {
		$sel_ord_query = tep_db_query("SELECT value FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$orders_id."' AND (class = 'ot_customer_discount' OR class='ot_gv')");
		$disctot = 0;
		while($rst_arr = tep_db_fetch_array($sel_ord_query)) {
			$disctot += $rst_arr["value"];
		}
		return $disctot;
	}
	
	//get orders status name
	function getOrderStatusName($orders_status_id) {
		$sel_ord_query = tep_db_query("SELECT orders_status_name FROM " . TABLE_ORDERS_STATUS . " WHERE orders_status_id = '".$orders_status_id."'");
		$rst_arr = tep_db_fetch_array($sel_ord_query);
		return $rst_arr['orders_status_name'];
	}
	
	//get processing orders status date from orders status history table.
	function getOrderProcessedDate($orders_id) {
		$sel_ord_query = tep_db_query("SELECT MAX(date_added) as processing_date FROM " . TABLE_ORDERS_STATUS_HISTORY . " WHERE orders_id = '".$orders_id."' AND orders_status_id='2' LIMIT 0,1");
		$rst_arr = tep_db_fetch_array($sel_ord_query);
		return $rst_arr['processing_date'];
	}
	
	//get last modified date from orders status history table.
	function getOrderModifiedDate($orders_id) {
		$sel_ord_query = tep_db_query("SELECT MAX(date_added) as modified_date FROM " . TABLE_ORDERS_STATUS_HISTORY . " WHERE orders_id = '".$orders_id."' LIMIT 0,1");
		$rst_arr = tep_db_fetch_array($sel_ord_query);
		return $rst_arr['modified_date'];
	}
	
	//get order gst by orders id
	function getSubtotalByOrder($orders_id) {
		$sel_subtotal_query = tep_db_query("SELECT value FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$orders_id."' AND class = 'ot_subtotal'");		
		$rst_sub = tep_db_fetch_array($sel_subtotal_query);	
		if(tep_db_num_rows($sel_subtotal_query)>0) {
			return $rst_sub["value"];
		} else {
			$sel_grand_query = tep_db_query("SELECT value FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$orders_id."' AND class = 'ot_grand_subtotal'");		
			$rst_grand = tep_db_fetch_array($sel_grand_query);	
			if(tep_db_num_rows($sel_grand_query)>0) {
				return $rst_grand["value"];
			}
		}
	}
	
	//get all sales consultant
	function getSalesConsultants() {
		
		$sel_consultant= tep_db_query("SELECT DISTINCT order_assigned_to FROM orders ORDER BY order_assigned_to ASC");
		while($sales_arr = tep_db_fetch_array($sel_consultant)) {
			if($sales_arr['order_assigned_to']=="" || is_null($sales_arr['order_assigned_to']) || $sales_arr['order_assigned_to']==" ") {
				$sales_arr['order_assigned_to'] = "Empty";
			}
			$sales_consultants[] = $sales_arr['order_assigned_to'];
		}
		
		return $sales_consultants;		
	}
		
}
?>