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

    function sales_report($mode, $startDate = 0, $endDate = 0, $sort = 0, $statusFilter = 0, $filter = 0, $details) {
      // startDate and endDate have to be a unix timestamp. Use mktime !
      // if set then both have to be valid startDate and endDate
      $this->mode = $mode;
      $this->tax_include = DISPLAY_PRICE_WITH_TAX;
	  $this->details = $details;

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
      $this->queryOrderCnt = "SELECT count(o.orders_id) as order_cnt FROM " . TABLE_ORDERS . " o ";
      	  
	  //products count query
	  $this->queryOrderProCostCnt = "SELECT (opc.labour_cost*opc.products_quantity) as pcl_cost, (opc.overhead_cost*opc.products_quantity) as pco_cost, (opc.material_cost*opc.products_quantity) as pcm_cost, o.date_purchased, o.customers_id, a.entry_zone_id FROM orders_products_costs opc JOIN orders o ON (opc.orders_id=o.orders_id) JOIN address_book a ON (o.customers_id = a.customers_id)  ";
	  	  
	  $this->queryItemCnt_2 = "SELECT p.products_id, p.products_model, op.products_quantity, (op.final_price*op.products_quantity) as psum, o.date_purchased FROM " . TABLE_ORDERS . " o JOIN orders_products op ON o.orders_id = op.orders_id JOIN products p ON op.products_id = p.products_id  ";
						
	   $this->queryItemCnt = "SELECT pd.products_tax_class_id, pd.products_model, o.orders_id,o.customers_id, o.customers_name, o.customers_company, 
										o.purchase_number, o.last_modified, o.date_purchased, 
										o.orders_status, a.entry_company_tax_id as customer_number, op.products_id as pid, 
										op.orders_products_id, op.products_quantity, op.final_price, op.products_name as pname, sum(op.products_quantity) as pquant, 
										sum(op.final_price * op.products_quantity) as psum, op.products_tax as ptax FROM " . TABLE_ORDERS . " o JOIN  
										" . TABLE_ORDERS_PRODUCTS . " op ON (o.orders_id = op.orders_id) JOIN " . TABLE_ADDRESS_BOOK . " a ON (o.customers_id = a.customers_id) JOIN " . TABLE_PRODUCTS . " pd ON (op.products_id = pd.products_id) ";
		
	  //products cost
	  $this->queryProCost = "select pc.categories_id, opc.* from products_to_categories pc, orders_products_costs opc  ";
	  
	  $this->queryProCost_2 = "select opc.*, o.date_purchased from orders_products_costs opc JOIN orders o ON (opc.orders_id=o.orders_id)  ";
	  
      // query for attributes
      $this->queryAttr = "SELECT count(op.products_id) as attr_cnt, o.orders_id, opa.orders_products_id, opa.products_options, opa.products_options_values, opa.options_values_price, opa.price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " opa JOIN " . TABLE_ORDERS . " o ON(o.orders_id = opa.orders_id) JOIN " . TABLE_ORDERS_PRODUCTS . " op ON (op.orders_products_id = opa.orders_products_id) ";

      // query for shipping
      $this->queryShipping = "SELECT ot.value as shipping, o.date_purchased, o.customers_id, a.entry_zone_id FROM " . TABLE_ORDERS . " o JOIN " . TABLE_ORDERS_TOTAL . " ot ON (ot.orders_id = o.orders_id) JOIN address_book a ON(o.customers_id = a.customers_id)  ";
	  
	   // query for GST total
      $this->queryGstTotal = "SELECT sum(ot.value) as gst_total FROM " . TABLE_ORDERS . " o JOIN " . TABLE_ORDERS_TOTAL . " ot ON (ot.orders_id = o.orders_id) ";
	  
	  $this->queryDiscountTotal = "SELECT sum(ot.value) as discount_total FROM " . TABLE_ORDERS . " o JOIN " . TABLE_ORDERS_TOTAL . " ot ON (ot.orders_id = o.orders_id)  ";
	 
	  //query for subtotal
	  $this->querySubtotal = "SELECT ot.value as subtotal, o.date_purchased, o.last_modified, o.customers_id, a.entry_zone_id FROM orders o JOIN orders_total ot ON (ot.orders_id = o.orders_id) JOIN address_book a ON (o.customers_id = a.customers_id)  ";
			 

      switch ($sort) {
        case '0':
          //$this->sortString = " "; //modified Aug 18, 2010
		  $this->sortString = "order by o.orders_id desc ";
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

      $filterString = "";
	  $shipping_cost = 0; $gst_cost = 0; $discount_cost = 0; $subtotal = 0; 
	  $pcl_cost = 0; $pco_cost = 0; $pcm_cost = 0;
	  
	  	  
	  $ordStatus = implode(",",$this->statusFilter);		  
	  $filterInvoiceString = "";	  
	  if (!empty($ordStatus)) {							
			$dateFilters = "";
			$filterInvoiceString = " JOIN (SELECT osh.orders_id FROM orders_status_history osh WHERE osh.orders_status_id IN (".$ordStatus.") AND osh.date_added >= '" .tep_db_input(date("Y-m-d\TH:i:s", $sd)). "'  AND osh.date_added < '" .tep_db_input(date("Y-m-d\TH:i:s", $ed)). "' group by osh.orders_id ) rst ON rst.orders_id = o.orders_id ";			
	  } else {	  
			$dateFilters = " AND (o.date_purchased >= '" .tep_db_input(date("Y-m-d\TH:i:s", $sd)). "' AND o.date_purchased < '" .tep_db_input(date("Y-m-d\TH:i:s", $ed)). "') ";
			$filterInvoiceString = "";
	  }
	  
	  
	  //Details view = 0 - Starts
	  if($this->details==0) {		  
		  			  
			  $rqOrders = tep_db_query($this->queryOrderCnt . $filterInvoiceString . " WHERE o.order_display='1' " . $dateFilters . $filterString);
			  $order = tep_db_fetch_array($rqOrders);	
			  
			  $rqOrdersItems = tep_db_query($this->queryItemCnt_2 . $filterInvoiceString . " WHERE o.orders_id = op.orders_id AND op.products_id = p.products_id AND o.order_display='1' " . $dateFilters .  $filterString);
			  
			  //$orders_items = tep_db_fetch_array($rqOrdersItems);   	 	  
			  while($orders_items = tep_db_fetch_array($rqOrdersItems)) {			  		
					$items_count += $orders_items["products_quantity"];
					$products_date = strtotime($orders_items["date_purchased"]);					
					$products_code = explode("-",$orders_items["products_model"]);				
					if($products_code[0]=="" || $products_code[0]<1) { $pmodel = '000'; }
					else { $pmodel = $products_code[0]; }									
					$products_sum[$products_date][$pmodel] += $orders_items["psum"];
					
					$products_costs_arr = tep_get_products_costs($orders_items["products_id"]);
					$material_costs_tot[$products_date][$pmodel] += $products_costs_arr['material_cost'];
					$labour_costs_tot[$products_date][$pmodel] += $products_costs_arr['labour_cost'];
					$overhead_costs_tot[$products_date][$pmodel] += $products_costs_arr['overhead_cost'];					
					$products_costs_tot[$products_date][$pmodel] += ($products_costs_arr['overhead_cost'] + $products_costs_arr['material_cost'] + $products_costs_arr['labour_cost']);
					
			    } 	  
					  
			  $rqShipping = tep_db_query($this->queryShipping . $filterInvoiceString . " WHERE ot.class = 'ot_shipping' AND o.order_display='1' " . $dateFilters .  $filterString. " GROUP BY o.orders_id");
			  
			  while($shipping = tep_db_fetch_array($rqShipping)) {
					$shipping_cost += $shipping["shipping"];
					$ship_date = date("M, Y",strtotime($shipping["date_purchased"]));
					$ship_cost[$ship_date] += $shipping["shipping"];
					
					$c_zone = $shipping["entry_zone_id"];
					$customer_shipping_all[$c_zone] += $shipping["shipping"];
			  }
			  $rqGstTotal = tep_db_query($this->queryGstTotal . $filterInvoiceString . " WHERE ot.class = 'ot_gst_total' AND o.order_display='1' " . $dateFilters .  $filterString);		  
			  while($gst_total = tep_db_fetch_array($rqGstTotal)) {
					$gst_cost += $gst_total["gst_total"];
			  }		  
			  $rqDiscountTotal = tep_db_query($this->queryDiscountTotal . $filterInvoiceString . " WHERE  (ot.class = 'ot_customer_discount' OR ot.class='ot_gv' OR ot.class='ot_coupon') AND o.order_display='1'  " . $dateFilters .  $filterString);
			  while($discount_total = tep_db_fetch_array($rqDiscountTotal)) {
					$discount_cost += $discount_total["discount_total"];
			  }			
			  			  
			  $rqSubTotal = tep_db_query($this->querySubtotal . $filterInvoiceString . " WHERE (ot.class = 'ot_grand_subtotal' OR ot.class='ot_subtotal') AND o.order_display='1' " . $dateFilters .  $filterString . "GROUP BY o.orders_id");
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
					$customer_revenue_all[$cust_zone] += $sub_total_arr["subtotal"];
			  }			  
			  $rqOrdProCost = tep_db_query($this->queryOrderProCostCnt . $filterInvoiceString . " WHERE  o.order_display='1'  " . $dateFilters . $filterString);
			  while($product_cost_arr = tep_db_fetch_array($rqOrdProCost)) {
					$pcl_cost += $product_cost_arr["pcl_cost"];
					$pco_cost += $product_cost_arr["pco_cost"];
					$pcm_cost += $product_cost_arr["pcm_cost"];
					$pro_date = date("M, Y",strtotime($product_cost_arr["date_purchased"]));					
					$pro_cost[$pro_date] += $product_cost_arr["pcl_cost"] + $product_cost_arr["pco_cost"] + $product_cost_arr["pcm_cost"];
					
					$cp_zone = $product_cost_arr["entry_zone_id"];
					$customer_pc_all[$cp_zone] += $product_cost_arr["pcl_cost"] + $product_cost_arr["pco_cost"] + $product_cost_arr["pcm_cost"];
					
				}	 
		  
	  }  
	  //Detail view = 2 or 3		
	  if($this->details==1 || $this->details==2 || $this->details==3) {
			$rqItems = tep_db_query($this->queryItemCnt . $filterInvoiceString ." WHERE   o.order_display='1' " . $dateFilters . $filterString." group by orders_products_id " . $this->sortString);
			$this->rqOrdProCostInfo = tep_db_query($this->queryProCost_2 . $filterInvoiceString . " where o.order_display='1' " . $dateFilters . $filterString);			  
	  	    $this->rqOrdProCostDetail = tep_db_query($this->queryProCost . " where pc.products_id=opc.products_id and opc.orders_id IN  ( SELECT o.orders_id from orders o ".$filterInvoiceString . " where o.order_display='1' " . $dateFilters . $filterString.")");
	  }
	   
	  
	  
      // set the return values
      $this->actDate = $ed;
      $this->showDate = $sd;
      $this->showDateEnd = $ed - 60 * 60 * 24;

	  //Execute if details view per products or products costs
	  if($this->details==1 || $this->details==2 || $this->details==3) {
	  
		  // execute the query
		  $cnt = 0;   $itemTot = 0;  $sumTot = 0;  $ord_total = 0;
		  
		  while ($resp[$cnt] = tep_db_fetch_array($rqItems)) {
			// to avoid rounding differences round for every quantum
			// multiply with the number of items afterwords.
						
			$price = $resp[$cnt]['final_price']; 
			
			// products_attributes
			// are there any attributes for this order_id ?
			$rqAttr = tep_db_query($this->queryAttr . $filterInvoiceString . " WHERE o.order_display='1' " . $dateFilters . " AND op.products_id = " . $resp[$cnt]['pid'] . $filterString . " group by products_options_values order by orders_products_id");
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
			
			$resp[$cnt]['price_inc_tax'] = tep_add_tax($price, $resp[$cnt]['ptax']);
			$resp[$cnt]['psum_inc_tax'] = $resp[$cnt]['products_quantity'] * tep_add_tax($price, $resp[$cnt]['ptax']);
			
			$resp[$cnt]['price'] = $price;
			$resp[$cnt]['psum'] = ($resp[$cnt]['products_quantity'] * $price);
			
			//values for below variables modified Aug 18, 2010
			$products_tax = tep_get_tax_rate($resp[$cnt]['products_tax_class_id']);
			
			$resp[$cnt]['pgst'] = tep_calculate_tax($resp[$cnt]['psum'],$products_tax);
			$resp[$cnt]['pgst_total'] = $resp[$cnt]['psum'] + $resp[$cnt]['pgst'];
									
			$resp[$cnt]['proquant'] = $resp[$cnt]['products_quantity'];  //modified Aug 18, 2010		
			
			$resp[$cnt]['gst_by_order'] = $this->getGstByOrder($resp[$cnt]['orders_id']); 
			$resp[$cnt]['shipping_by_order'] = $this->getShippingByOrder($resp[$cnt]['orders_id']); 
	
			// values per date and item
			$sumTot += $resp[$cnt]['psum'];
			//$itemTot += $resp[$cnt]['pquant']; //modified Aug 18, 2010
			$itemTot += $resp[$cnt]['products_quantity'];
			
			$resp[$cnt]['totsum'] = $sumTot;
			$resp[$cnt]['totitem'] = $itemTot;
			
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
		  //$resp[0]['orders_items'] = $orders_items['items_cnt']; 
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
		  
		  $resp[0]['chart_material_costs_tot'] = $material_costs_tot;
		  $resp[0]['chart_labour_costs_tot'] = $labour_costs_tot;
		  $resp[0]['chart_overhead_costs_tot'] = $overhead_costs_tot;
		  $resp[0]['chart_products_costs_tot'] = $products_costs_tot;
		  
		  $resp[0]['chart_customer_revenue_all'] = $customer_revenue_all;
		  $resp[0]['chart_customer_shipping_all'] = $customer_shipping_all;
		  $resp[0]['chart_customer_pc_all'] = $customer_pc_all;
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
				
				if($pro_model[0]=="") { $pro_model[0] = '000'; }
				
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
		
		while($models_arr = tep_db_fetch_array($sel_models)) {				
			
			if($models_arr['products_model']=="") {
				$models[] = '000'; 
			} else {
				$procode = explode("-",$models_arr['products_model']); 
				if($procode[0]<1) { $procode[0] = '000'; }
				$models[] = $procode[0]; 
			}		
			
		}
						
		return array_unique($models);
		
	}
	
	//get order gst by orders id
	function getGstByOrder($orders_id) {
		$sel_ord_query = tep_db_query("SELECT sum(value) as gst_by_order FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$orders_id."' AND  class = 'ot_gst_total'");
		$rst_arr = tep_db_fetch_array($sel_ord_query);
		return $rst_arr['gst_by_order'];
	}
	
	//get order gst by orders id
	function getShippingByOrder($orders_id) {
		$sel_ord_query = tep_db_query("SELECT sum(value) as shipping_by_order FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$orders_id."' AND  class = 'ot_shipping'");
		$rst_arr = tep_db_fetch_array($sel_ord_query);
		return $rst_arr['shipping_by_order'];
	}
	
	//get order gst by orders id
	function getDiscountByOrder($orders_id) {
		$sel_ord_query = tep_db_query("SELECT sum(value) as discount_by_order FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$orders_id."' AND (class = 'ot_customer_discount' OR class='ot_gv')");
		$rst_arr = tep_db_fetch_array($sel_ord_query);
		return $rst_arr['discount_by_order'];
	}
	
	//get orders status name
	function getOrderStatusName($orders_status_id) {
		$sel_ord_query = tep_db_query("SELECT orders_status_name FROM " . TABLE_ORDERS_STATUS . " WHERE orders_status_id = '".$orders_status_id."'");
		$rst_arr = tep_db_fetch_array($sel_ord_query);
		return $rst_arr['orders_status_name'];
	}
		
}
?>