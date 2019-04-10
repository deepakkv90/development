<?php
/*
  $Id: stats_sales_report2.php,v 1.00 2003/03/08 19:02:22 Exp $

  Charly Wilhelm  charly@yoshi.ch
  
  Released under the GNU General Public License

  Copyright (c) 2003 osCommerce
  
  possible views (srView):
  1 yearly
  2 monthly
  3 weekly
  4 daily
  
  possible options (srDetail):
  0 no detail
  1 show details (products)
  2 show details only (products)
  
  export
  0 normal view
  1 html view without left and right
  2 csv
  
  sort
  0 no sorting
  1 product description asc
  2 product description desc
  3 #product asc, product descr asc
  4 #product desc, product descr desc
  5 revenue asc, product descr asc
  6 revenue desc, product descr desc
  
*/
  
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  // default detail no detail
  $srDefaultDetail = 0;
  // default view (daily)
  $srDefaultView = 2;
  // default export
  $srDefaultExp = 0;
  // default sort
  $srDefaultSort = 4;
  $report = (isset($_GET['report']) ? $_GET['report'] : '');
  $detail = (isset($_GET['detail']) ? $_GET['detail'] : '');
  $export = (isset($_GET['export']) ? $_GET['export'] : '');
  $max = (isset($_GET['max']) ? $_GET['max'] : '');
  $status = (isset($_GET['status']) ? $_GET['status'] : '');
  $sort = (isset($_GET['sort']) ? $_GET['sort'] : '');
  $srView = (isset($_GET['report']) ? $_GET['report'] : '');
  $srDetail = (isset($_GET['detail']) ? $_GET['detail'] : '');
  $srExp = (isset($_GET['export']) ? $_GET['export'] : '');
  $srMax = (isset($_GET['max']) ? $_GET['max'] : '');
  //$srStatus = (isset($_GET['status']) ? $_GET['status'] : '');
  $srSort = (isset($_GET['sort']) ? $_GET['sort'] : '');
  $startD = (isset($_GET['startD']) ? $_GET['startD'] : '');
  $startM = (isset($_GET['startM']) ? $_GET['startM'] : '');
  $startY = (isset($_GET['startY']) ? $_GET['startY'] : '');

  $endD = (isset($_GET['endD']) ? $_GET['endD'] : '');
  $endM = (isset($_GET['endM']) ? $_GET['endM'] : '');
  $endY = (isset($_GET['endY']) ? $_GET['endY'] : '');
  $srFilter = (isset($srFilter) ? $srFilter : '');
    
  $srStatus = (isset($_GET['os']) ? implode("_",$_GET['os']) : '');
  
  //for product cost based reports
  $srDetailPro = (isset($_GET['det_pro']) ? $_GET['det_pro'] : 'p_cat');
  
  //For Orders in option
  $srOrdersIn = (isset($_GET['orders_in']) ? $_GET['orders_in'] : '');
  $srSalesConsultant = (isset($_GET['assigned']) ? $_GET['assigned'] : '');
  
  // report views (1: yearly 2: monthly 3: weekly 4: daily)
  if ( ($report) && (tep_not_null($report)) ) {
  	$srView = $report;
  }
  if ($srView < 1 || $srView > 4) {
    $srView = $srDefaultView;
  }

  // detail
  if ( isset($detail) && (tep_not_null($detail)) )  {    
  	$srDetail = $_GET['detail'];
  }
  if ($srDetail < 0 || $srDetail > 4) {
    $srDetail = $srDefaultDetail;
  }
  
  // report views (1: yearly 2: monthly 3: weekly 4: daily)
  if ( ($export) && (tep_not_null($export)) ) {    
  	$srExp = $_GET['export'];
  }
  if ($srExp < 0 || $srExp > 2) {
    $srExp = $srDefaultExp;
  }
  
  // item_level
  if ( ($max) && (tep_not_null($max)) ) {
    $srMax = $max;
  }
  if (!is_numeric($srMax)) {
    $srMax = 0;
  }

  // sort
  if ( ($sort) && (tep_not_null($sort)) ) {
    $srSort = $sort;
  }
  if ($srSort < 1 || $srSort > 6) {
    $srSort = $srDefaultSort;
  }
    
  // check start and end Date
  $startDate = "";    
  $endDate = "";
    
  //after fixing date picker
  $report_from = ""; $report_to = "";  
  if(isset($_GET['reports_from']) && !empty($_GET['reports_from'])){      
	  $startDate = strtotime(tep_db_input($_GET['reports_from']));
	  $report_from = date("d-m-Y", strtotime(tep_db_input($_GET['reports_from'])));
  } else {
      $startDate = mktime(0, 0, 0, 7, 1, date("Y"));
	  $report_from = date("d-m-Y", mktime(0, 0, 0, 7, 1, date("Y")));
  }  
  
  if(isset($_GET['reports_to']) && !empty($_GET['reports_to'])){      
	  $endDate = strtotime("+1 day",strtotime(tep_db_input($_GET['reports_to'])));
	  $report_to = date("d-m-Y", strtotime(tep_db_input($_GET['reports_to'])));
  } else {
      //$endDate = mktime(0, 0, 0, 7, 1, date("Y")+1);	  
	  //$report_to = date("d-m-Y", mktime(0, 0, 0, 6, 30, date("Y")+1));
	  $endDate = strtotime("+1 day",time());
	  $report_to = date("d-m-Y", time());
  }
  //after fixing date picker
  
  
  // Set chart for option
  $srChartFor = "basic";
  if(isset($_GET['chartFor']) && !empty($_GET['chartFor'])) {
  	 $srChartFor = $_GET['chartFor'];
  } 
  
  
  //Get All Products Model
  $models = tep_get_all_products_model();
  
  
  require(DIR_WS_CLASSES . 'sales_report2.php');
  $sr = new sales_report($srView, $startDate, $endDate, $srSort, $srStatus, $srFilter, $srDetail, $srOrdersIn, $srSalesConsultant);  
  $startDate = $sr->startDate;
  $endDate = $sr->endDate;  
  
  $file_str = '';
  
  if ($srExp < 2) {
    // not for csv export
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">  <title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script type="text/javascript" src="includes/javascript/jquery.js"></script>
<script type="text/javascript" src="includes/javascript/thickbox.js"></script>
<link rel="stylesheet" href="includes/javascript/thickbox.css" type="text/css" media="screen" />

<script type="text/javascript">

	function checkAllStatus(status) {
		$(".checkbox").each( function() {
			$(this).attr("checked",status);
		});
	}	
	
	$(document).ready(function() {
	
		$(".checkbox").click(function() {
				
			$(".checkbox").each( function() {
				
				if($(this).attr("checked")==false) {
					$("#os_all").attr("checked",false);
					return false;
				} else {
					$("#os_all").attr("checked",true);
				}
	
			});
						
		});
		
		$("#orders_in").click(function() { 
			if($(this).attr("checked")==false) {
				$("#consultant_option").hide();
			} else {
				$("#consultant_option").show();
			}
		});
		
		if($("#orders_in").attr("checked")==false) {
			$("#consultant_option").hide();
		} else {
			$("#consultant_option").show();
		}
	
		
		$("a#chart_revenue").click(function() {
			$("#chartFor").val("revenue");
			$("#frmSalesReport").submit();
		});
		
	});
	
</script>
<!-- Jquery UI Start -->
	
		<link rel="stylesheet" href="includes/javascript/ui/jquery.ui.all.css">
		<script src="includes/javascript/ui/jquery-1.5.1.js"></script>
		<script src="includes/javascript/ui/jquery.ui.core.js"></script>
		<script src="includes/javascript/ui/jquery.ui.widget.js"></script>
		<script src="includes/javascript/ui/jquery.ui.datepicker.js"></script>
		<link rel="stylesheet" href="includes/javascript/ui/demos.css">
	
		<script type="text/javascript">
		
			$(function() {
				
				$( "#datepicker_from" ).datepicker({
					dateFormat: "dd-mm-yy",
					showOn: "button",
					buttonImage: "images/calendar.gif",
					buttonImageOnly: true
				});
				$( "#datepicker_to" ).datepicker({
					dateFormat: "dd-mm-yy",
					showOn: "button",
					buttonImage: "images/calendar.gif",
					buttonImageOnly: true
				});
				
			});
			
		</script>
	
<!-- Jquery UI end -->

<!-- Chart -->
<script type="text/javascript" src="includes/javascript/amcharts/flash/swfobject.js"></script>
<script src="includes/javascript/amcharts/javascript/amcharts.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/javascript/amcharts/javascript/amfallback.js"></script>
<script src="includes/javascript/amcharts/javascript/raphael.js" type="text/javascript"></script>
<!-- Chart -->

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();"> 
<!-- header //-->
<?php
if ($srExp < 1) {
  require(DIR_WS_INCLUDES . 'header.php');
}
?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <?php
    if ($srExp < 1) {
      ?>
       <!-- left_navigation //-->
      <?php
      require(DIR_WS_INCLUDES . 'column_left.php');
      ?>
      <!-- left_navigation_eof //-->
      <!-- body_text //-->
      <?php
    } // end sr_exp
    ?>
    <td valign="top" class="page-container">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan=2>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading" width="4%"><img src="images/user_home.png" width="32" height="32"></td>
				<td class="pageHeading" width="30%"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" width="66%"><a href="javascript:void(0);" id="chart_revenue"><img src="images/revenue.png" /></a> &nbsp; <img src="images/product-cost.png" /> &nbsp; <img src="images/customer-map.png" /> <?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table>
          </td>
        </tr>
<?php
    if ($srExp < 1) {
?>
        <tr>
          <td colspan="2">
            <form action="" method="get" name="frmSalesReport" id="frmSalesReport">
				<input type="hidden" name="chartFor" id="chartFor">
            <?php
              if (isset($_GET[tep_session_name()])) {
                echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
              }
            ?>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" width="12%" valign="top">
                    <input type="radio" name="report" value="1" <?php if ($srView == 1) echo "checked"; ?>><?php echo REPORT_TYPE_YEARLY; ?><br>
                    <input type="radio" name="report" value="2" <?php if ($srView == 2) echo "checked"; ?>><?php echo REPORT_TYPE_MONTHLY; ?><br>
                    <input type="radio" name="report" value="3" <?php if ($srView == 3) echo "checked"; ?>><?php echo REPORT_TYPE_WEEKLY; ?><br>
                    <input type="radio" name="report" value="4" <?php if ($srView == 4) echo "checked"; ?>><?php echo REPORT_TYPE_DAILY; ?><br>
                  </td>
                  <td width="12%" align="left" valign="top">  
					<?php echo REPORT_START_DATE; ?><br>                    					
					<input type="text" id="datepicker_from" name="reports_from" value="<?php echo (isset($report_from))? $report_from : ""; ?>" style="width:70px;">
				  	<br><br>
					<?php echo REPORT_END_DATE; ?><br>
					<input type="text" id="datepicker_to" name="reports_to" value="<?php echo (isset($report_to))? $report_to : ""; ?>" style="width:70px;"> 
                  </td>
				  <td width="12%" align="left" valign="top"> 
				  	Orders In: <input type="checkbox" name="orders_in" id="orders_in" value="1" <?php if ($srOrdersIn == 1) echo "checked"; ?>>
					<br>
					<div id="consultant_option" style="display:none;">
					<br><br>
					Sales Consultant: 					
                      <select name="assigned" size="1" style="width:100px;">	
					  	<option value="all"<?php if ($srSalesConsultant == "all") echo "selected"; ?>><?php echo "All"; ?></option>
					  <?php
					  	$sales_consultants = $sr->getSalesConsultants();											
						
						foreach($sales_consultants as $sales_consultant) {
							
							if($srSalesConsultant == $sales_consultant) { 	$selected ="selected"; 	} 
							else { 	$selected = ""; }
														
							echo '<option value="'.$sales_consultant.'" '.$selected.'>'.$sales_consultant.'</option>';
							
						}
					  ?>					  
                    </select>
					</div>
				  </td>
                  <td width="12%" align="left" valign="top">
                    <?php echo REPORT_DETAIL; ?><br>
                    <select name="detail" size="1" style="width:100px;">
                      <option value="0"<?php if ($srDetail == 0) echo "selected"; ?>><?php echo DET_HEAD_ONLY; ?></option>
                      <option value="1"<?php if ($srDetail == 1) echo " selected"; ?>><?php echo DET_DETAIL; ?></option>
                      <option value="2"<?php if ($srDetail == 2) echo " selected"; ?>><?php echo DET_DETAIL_ONLY; ?></option>
					  <option value="3"<?php if ($srDetail == 3) echo " selected"; ?>><?php echo DET_PRODUCTS_COST; ?></option>					  
                    </select><br>
					<?php 
					if($srDetail == 3) {
					echo "Product Cost By"; ?><br>
                    <select name="det_pro" size="1" style="width:100px;">                     
                      <option value="p_cat"<?php if ($srDetailPro == "p_cat") echo " selected"; ?>>Category</option>
                      <option value="p_family"<?php if ($srDetailPro == "p_family") echo " selected"; ?>>Family Product</option>
                      <option value="p_name"<?php if ($srDetailPro == "p_name") echo " selected"; ?>>Product</option>    
					  <option value="p_code"<?php if ($srDetailPro == "p_code") echo " selected"; ?>>Code</option>               
                    </select>					
					<?php } ?>

                  </td>
                  <td width="12%" align="left" valign="top">
                    <?php echo REPORT_MAX; ?><br>
                    <select name="max" size="1" style="width:100px;">
                      <option value="0"><?php echo REPORT_ALL; ?></option>
                      <option<?php if ($srMax == 1) echo " selected"; ?>>1</option>
                      <option<?php if ($srMax == 3) echo " selected"; ?>>3</option>
                      <option<?php if ($srMax == 5) echo " selected"; ?>>5</option>
                      <option<?php if ($srMax == 10) echo " selected"; ?>>10</option>
                      <option<?php if ($srMax == 25) echo " selected"; ?>>25</option>
                      <option<?php if ($srMax == 50) echo " selected"; ?>>50</option>
                    </select><br>					
					
					<?php echo REPORT_EXP; ?><br>
                    <select name="export" size="1" style="width:100px;">
                      <option value="0" selected><?php echo EXP_NORMAL; ?></option>
                      <option value="1"><?php echo EXP_HTML; ?></option>
                      <option value="2"><?php echo EXP_CSV; ?></option>
                    </select><br>						
					
                  </td>
                  <td width="12%" align="left" valign="top">                    
                    <?php echo REPORT_SORT; ?><br>
                    <select name="sort" size="1" style="width:100px;">
                      <option value="0"<?php if ($srSort == 0) echo " selected"; ?>><?php echo SORT_VAL0; ?></option>
                      <option value="1"<?php if ($srSort == 1) echo " selected"; ?>><?php echo SORT_VAL1; ?></option>
                      <option value="2"<?php if ($srSort == 2) echo " selected"; ?>><?php echo SORT_VAL2; ?></option>
                      <option value="3"<?php if ($srSort == 3) echo " selected"; ?>><?php echo SORT_VAL3; ?></option>
                      <option value="4"<?php if ($srSort == 4) echo " selected"; ?>><?php echo SORT_VAL4; ?></option>
                      <option value="5"<?php if ($srSort == 5) echo " selected"; ?>><?php echo SORT_VAL5; ?></option>
                      <option value="6"<?php if ($srSort == 6) echo " selected"; ?>><?php echo SORT_VAL6; ?></option>
                    </select><br><br>
					
					<?php echo tep_image_submit('send.png',REPORT_SEND); ?>  
					
                  </td>
				  
				  <td width="28%" align="left" valign="top">
				  
				  		<table width="100%" border="0" style="margin:3px;">
							<tr>
								<td class="info-box-head" style="color:#FFF;"><b>Select By Status:</b></td>
							</tr>
							<tr>
								<td class="info-box-body">
									
									<table align="center" width="100%" border="0">
										<tr>
											<td>
												<input type="checkbox" id="os_all" name="os_all" onclick="checkAllStatus(this.checked)" <?php echo (isset($_GET['os_all']))? "checked":""; ?>>
											</td>
											<td>All</td>						
									
									<?php 
									//print_r($sr->status);
									$i=1;
									foreach ($sr->status as $value) {
										
										if (in_array($value["orders_status_id"], $_GET['os'])) {
											$checked_status = " checked";
										}						
										else { $checked_status = ""; }							
										
										echo '<td><input type="checkbox" class="checkbox" name="os[]" value="'.$value["orders_status_id"].'" '.$checked_status.'></td><td>'.$value["orders_status_name"]."</td>";							
										$i++;
										if(($i%2)==0) echo "</tr>";
									 }
									?> 
									</table>
									
								</td>
							</tr>
						</table>				
				  
				  </td>
				  
                </tr>
               
              </table>
              
            </form>
          </td>
        </tr>
<?php
  } // end of ($srExp < 1)
?>
        <tr>
          <td width=100% valign=top>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td valign="top">
								
                  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
                    
				  <?php if($srChartFor=="basic") {  ?>
					
					
					<?php
					if($srDetail == 0){
					?>
					<tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE; ?></td>
                      <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDERS;?></td>
                      <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ITEMS; ?></td>
					  <?php if($srOrdersIn!=1) { ?>					  
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_LABOUR; ?></td>
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_OVERHEAD; ?></td>
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_MATERIAL; ?></td>                      
					  <?php } ?>
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_REVENUE;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  TABLE_HEADING_SHIPPING;?></td>
					  <td class="dataTableHeadingContent" align="right"><?php echo  "GST Total";?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  TABLE_HEADING_DISCOUNT;?></td>
					  <td class="dataTableHeadingContent" align="right" width="14%"><?php echo  "Total (inc GST and shipping)";?></td>
                    </tr>
					<?php
					} else if($srDetail == 3) { ?>
						
						<tr class="dataTableHeadingRow">						 
						   <?php if($srDetailPro!="p_code") { ?>			 
						  <td class="dataTableHeadingContent" align="Left"><?php echo "Family Product";?></td>	
						  <td class="dataTableHeadingContent" align="Left"><?php echo PRO_COST_CATEGORY;?></td>	
						  <td class="dataTableHeadingContent" align="Left"><?php echo "Products name";?></td>
						  <?php } else { ?>							 
						  <td class="dataTableHeadingContent" colspan="2" align="Left"><?php echo "Date"; ?></td>
						  <td class="dataTableHeadingContent" align="center"><?php echo "Products Code";?></td>
						  <?php }   ?>							 
						  <td class="dataTableHeadingContent" align="right"><?php echo PRO_COST_LABOUR; ?></td>
						  <td class="dataTableHeadingContent" align="right"><?php echo PRO_COST_OVERHEAD; ?></td>
						  <td class="dataTableHeadingContent" align="right"><?php echo PRO_COST_MATERIAL; ?></td>						  
						  <td class="dataTableHeadingContent" align="right"><?php echo PRO_COST_PRODUCT_COST;?></td>
						  <td class="dataTableHeadingContent" align="right"><?php echo  PRO_COST_REVENUE;?></td>						  
						</tr>
					<?php					 	
					} 
					else {
					?>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="4%"><?php echo TABLE_HEADING_CUST_NO; ?></td>
						<td class="dataTableHeadingContent" align="left" width="5%"><?php echo TABLE_HEADING_CUST_NAME;?></td>
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo TABLE_HEADING_CUST_COMPANY;?></td>
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo "Processing Date"; ?></td>
						<td class="dataTableHeadingContent" align="left" width="5%"><?php echo "Order Date"; ?></td>
						<td class="dataTableHeadingContent" align="left" width="4%"><?php echo "Last Modified"; ?></td>
						<td class="dataTableHeadingContent" align="left" width="4%"><?php echo TABLE_HEADING_ORDER_NO; ?></td>
						<td class="dataTableHeadingContent" align="left" width="4%"><?php echo "Status"; ?></td>
						<td class="dataTableHeadingContent" align="left" width="5%"><?php echo "Sales Consultant"; ?></td>
						<td class="dataTableHeadingContent" align="left" width="3%"><?php echo TABLE_HEADING_PONUMBER; ?></td>						
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo TABLE_HEADING_PRODUCT; ?></td>
						<td class="dataTableHeadingContent" align="center" width="3%"><?php echo TABLE_HEADING_QTY;?></td>
						<td class="dataTableHeadingContent" align="right" width="5%"><?php echo TABLE_HEADING_UNIT_PRICE;?></td>
						<td class="dataTableHeadingContent" align="right" width="5%"><?php echo TABLE_HEADING_SUB_TOTAL;?></td>
						<td class="dataTableHeadingContent" align="right" width="6%"><?php echo "Shipping"; ?></td>
						<td class="dataTableHeadingContent" align="right" width="6%"><?php echo "GST Total";?></td>
						<td class="dataTableHeadingContent" align="right" width="8%"><?php echo "Total (excl GST)";?></td>
						<td class="dataTableHeadingContent" align="right" width="6%"><?php echo  TABLE_HEADING_DISCOUNT;?></td>
						<td class="dataTableHeadingContent" align="right" width="13%"><?php echo "Total";?></td>
					</tr>
					<?php
					}// end of $srDetail != 0 
				
				} // Check Chart For NOT SET END
				
} // end of if $srExp < 2 csv export


$sum = 0; $revenue_line_string = "";

while ($sr->actDate < $sr->endDate) {

  $info = $sr->getNext();
 
  $last = sizeof($info) - 1;
  
  //get product cost results
  if($srDetail == 3) { 	$pInfo = $sr->getProductCosts($srDetailPro);   }
  
  //Get chart informations START.
  if($srDetail == 0) {
  		
		$chart_revenue = $chart_revenue + $info[0]['sub_total'];
		$chart_productcost = $chart_productcost + ($info[0]['overhead_cost'] + $info[0]['labour_cost'] + $info[0]['material_cost']);
		$chart_shipping = $chart_shipping + $info[0]['shipping'];
		$chart_overhead = $chart_overhead + $info[0]['overhead_cost'];
		$chart_labour = $chart_labour + $info[0]['labour_cost'];
		$chart_material = $chart_material + $info[0]['material_cost'];
		
		if($srView==1) {
			
			$chart_value = $info[0]['chart_revenue'];	
			$chart_ship = $info[0]['chart_ship_cost'];	
			$chart_product = $info[0]['chart_pro_cost'];
									
		} else if($srView==2) {
			
			$current_month = $info[0]['chart_date'];						
			if($current_month!="") {
				$chart_value[$current_month] += $info[0]['sub_total'];	
			}			
			$current_ship_month = $info[0]['chart_ship_date'];
			$current_pro_month = $info[0]['chart_pro_date'];
			if($current_month==$current_ship_month) {
				$chart_ship[$current_ship_month] += $info[0]['shipping'];	
			}
			if($current_month==$current_pro_month) {
				$chart_product[$current_pro_month] += $info[0]['overhead_cost'] + $info[0]['labour_cost'] + $info[0]['material_cost'];									
			}
			
		} else {
			$current_month = date("M, Y", $sr->showDate);
			if($current_month!="") {
				$chart_value[$current_month] += $info[0]['sub_total'];	
				$chart_ship[$current_month] += $info[0]['shipping'];
				$chart_product[$current_month] += $info[0]['overhead_cost'] + $info[0]['labour_cost'] + $info[0]['material_cost'];	
			}
		}
		
  }
  //Get chart informations END.
  
  if($srChartFor=="basic") {
  
		  if ($srExp < 2) { ?>
						<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
						
						<?php
						switch ($srView) {
						  case '3':
								if($srDetail == 0) { ?><td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td><?php }
								
								if($srDetail != 0) { ?> 
									<td class="dataTableContent" colspan="19"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
								<?php }
						  break;
						  case '4':
								if($srDetail == 0) {?><td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td><?php }
								if($srDetail != 0) {?> <td class="dataTableContent" colspan="19"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td> <?php }
						  break;
						  default;
								if($srDetail == 0) {?> <td class="dataTableContent" align="right"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td><?php } 
								if($srDetail != 0) {?> 
									<td class="dataTableContent" colspan="19"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td><?php	}
									
						} //Switch end
			
			
						if($srDetail == 0) {								
							
							$man_gst_all =  (($info[0]['sub_total'] + $info[0]['shipping']) * 10)/100;						
							
											
						?> 
						<td class="dataTableContent" align="center"><?php echo $info[0]['order']; ?></td>
						<td class="dataTableContent" align="center"><?php echo $info[0]['orders_items']; ?></td>
						<?php if($srOrdersIn!=1) { ?>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['labour_cost']); ?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['overhead_cost']); ?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['material_cost']); ?></td>					
						<?php } ?>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['sub_total']); ?> </td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['shipping']); ?></td>
						<td class="dataTableContent" align="right">
							<?php 
								/*
								if($currencies->format($man_gst_all)!=$currencies->format($info[0]['gst_total'])) {
									echo "<font color='red'>" . $currencies->format($info[0]['gst_total']) . "</font><br>";
									echo $currencies->format($man_gst_all);
								} else {
									echo $currencies->format($info[0]['gst_total']); 
								}
								*/
								echo $currencies->format($info[0]['gst_total']);
								
							?>				
						</td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['customer_discount']); ?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['orders_total']); ?></td>
				 <?php } ?>
				</tr>
		<?php
		  } else {
			// csv export
			$file_str .= date(DATE_FORMAT, $sr->showDate) . SR_SEPARATOR1 . date(DATE_FORMAT, $sr->showDateEnd) . SR_SEPARATOR1;
			$file_str .= $info[0]['order'] . SR_SEPARATOR1;
			$file_str .= $info[$last - 1]['totitem'] . SR_SEPARATOR1;
			$file_str .= $currencies->format($info[0]['labour_cost']) . SR_SEPARATOR1;
			$file_str .= $currencies->format($info[0]['overhead_cost']) . SR_SEPARATOR1;
			$file_str .= $currencies->format($info[0]['material_cost']) . SR_SEPARATOR1;
			$file_str .= $currencies->format($info[$last - 1]['totsum']) . SR_SEPARATOR1;
			$file_str .= $currencies->format($info[0]['shipping']) . SR_NEWLINE;
		  }
		  
		  
		  //Product costs start
		  if($srDetail == 3) {
			
				//for product cost listing				
				$main_cat_count = count($pInfo['opc_info']);
				
				$subtotal = 0; 	$subtotal_pro_cost = 0;  $subtotal_labour = 0; 	$subtotal_overhead = 0;  $subtotal_material = 0;
				
				foreach($pInfo['opc_info'] as $mcat=>$pro_arr) {
					
					$labour = 0; $overhead = 0; $material = 0; $pcl_cost = 0; $pco_cost = 0; $pcm_cost = 0;
					$revenue = 0; $product_cost = 0; $item_quantity = 0;
					$sub_category_name = "";	
					
					foreach($pro_arr as $pro_key=>$pro_info) {
						//print_r($pro_info);
						$manufac_qry = tep_db_query("select manufacturers_id from products where products_id='".$pro_info['products_id']."'");
						$manufac_info = tep_db_fetch_array($manufac_qry);
						$manufac_name = tep_get_manufacturer_name($manufac_info['manufacturers_id']);
						
						$item_quantity += $pro_info['products_quantity'];
						$labour += $pro_info['labour_cost'] * $pro_info['products_quantity'];			
						$overhead += $pro_info['overhead_cost'] * $pro_info['products_quantity'];				
						$material += $pro_info['material_cost'] * $pro_info['products_quantity'];
						
						$pcl_cost += $pro_info['labour_cost']; 
						$pco_cost += $pro_info['overhead_cost']; 
						$pcm_cost += $pro_info['material_cost'];
						
						$main_category_name = $pro_info['main_category_name'];
						if(!preg_match("/".$pro_info['sub_category_name']."/",$sub_category_name)) {
							$sub_category_name .= $pro_info['sub_category_name']."<br>";
						}
						$products_code = $pro_info['products_code'];
						
					}
					
					$revenue += ($labour + $overhead + $material);
					$product_cost += ($pcl_cost + $pco_cost + $pcm_cost);
					
					$subtotal += $revenue; 
					$subtotal_pro_cost += $product_cost;
					$subtotal_labour += $labour;
					$subtotal_overhead += $overhead;
					$subtotal_material += $material;
					?>		
					<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">				
						<?php if($srDetailPro!="p_code") { ?>				
						<td class="dataTableContent"><b><?php echo ($manufac_name!="")?$manufac_name : "N/A";  ?></b></td>	
						<td class="dataTableContent"><b><?php echo $main_category_name;  ?></b></td>			
						<td class="dataTableContent"><b><?php echo $sub_category_name;  ?></b></td>	
						<?php } else { ?>
						<td class="dataTableContent" colspan="2">&nbsp;</td>				
						<td class="dataTableContent" align="center"><b><?php echo $products_code;  ?></b></td>				
						<?php } ?>						
						<td class="dataTableContent" align="right"><?php echo  $currencies->format($labour);  ?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($overhead);  ?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($material);  ?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($revenue);  ?></td>
						<td class="dataTableContent" align="right">
							<?php
								if($srDetailPro == "p_code" && is_array($info[0]['pcode'][$products_code])) {
									echo $currencies->format(array_sum($info[0]['pcode'][$products_code]));
								}
							?>
						</td>
					</tr>		
					<?php 			
				}		
		
				if(isset($info[$last - 1]['totsum'])) { 
					?>			
					<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">							
						<td class="dataTableContent" style="background-color:#FFE5FF;"><b>Sub Total</b> : </td>
						<td class="dataTableContent" colspan="2">&nbsp;</td>
						<td class="dataTableContent" align="right"><b><?php echo $currencies->format($subtotal_labour);?></b></td>
						<td class="dataTableContent" align="right"><b><?php echo $currencies->format($subtotal_overhead);?></b></td>
						<td class="dataTableContent" align="right"><b><?php echo $currencies->format($subtotal_material);?></b></td>
						<td class="dataTableContent" align="right"><b><?php echo $currencies->format($subtotal);?></b></td>
						<td class="dataTableContent" align="right"><b><?php echo (isset($info[$last - 1]['totsum']) ? $currencies->format($info[$last - 1]['totsum']) : '');?></b></td>				
					</tr>
					<?php
				}		
				
			}
				
		  //product cost end
		  
		  $customer = ""; $order_id_string = "";
		  
		  if ($srDetail!=0 && $srDetail!=3) {
		  
			//Order Total 
			$tmp_orderid = 0; $subtot = 0;  $rec_count = 0;	 $current_id = 0;
			$shipping_ex_gst = 0;  $total_gst = 0; 	$total_discount = 0; $subtotal_new = 0;	
			//Order Total eof
		  
			for ($i = 0; $i < $last; $i++) {
				  
			  if ($srMax == 0 or $i < $srMax) {
				
				if ($srExp < 2) {
		
					//Order Total 
					if($tmp_orderid != $info[$i]['orders_id'] && $tmp_orderid !=0) {  
						
						//manual calculation for GST for testing purpose
						$man_gst =  (($subtot + $shipping_ex_gst) * 10)/100;				  
					?>
			
						<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
							<td class="dataTableContent">&nbsp;</td>
							<td class="dataTableContent" colspan="2"><?php echo TABLE_HEADING_ORDER_TOTAL;?></td>
							<td class="dataTableContent" colspan="10">&nbsp;</td>
							<td class="dataTableContent" align="right">
								<?php 
										if($currencies->format($subtot)!=$currencies->format($subtotal_new)) {
											echo "<font color='red'>" . $currencies->format($subtot) . "</font><br>";
											echo $currencies->format($subtotal_new);
										} else {
											echo $currencies->format($subtot); 
										}
									?>
							</td>
							<td class="dataTableContent" align="right"><?php echo $currencies->format($shipping_ex_gst); ?></td>
							<td class="dataTableContent" align="right">
								<?php 
										if($currencies->format($man_gst)!=$currencies->format($total_gst)) {
											echo "<font color='red'>" . $currencies->format($total_gst) . "</font><br>";
											echo $currencies->format($man_gst);									
											//echo "<br>" . $current_id;
											$order_id_string .= $current_id ."\r\n\r\n";
										} else {
											echo $currencies->format($total_gst); 
										}								
									?>			
							</td>
							<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot + $shipping_ex_gst - $total_discount); ?></td>					
							<td class="dataTableContent" align="right"><?php echo $currencies->format($total_discount); ?></td>					
							<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot + $shipping_ex_gst + $total_gst - $total_discount); ?></td>
						</tr>
						<?php
						$subtot = 0;  $rec_count = 0; $current_id = 0;
						$shipping_ex_gst = 0; 	$total_gst = 0; $total_discount = 0; $subtotal_new = 0;
					}
												 
					$tmp_orderid = $info[$i]['orders_id'];
					$subtot 	+= $info[$i]['psum'];
					
					$rec_count++;
					//Order Total eof
					
					if ($rec_count == 1) {
						$shipping_ex_gst = $sr->getShippingByOrder($info[$i]['orders_id']);
						$total_gst = $sr->getGstByOrder($info[$i]['orders_id']);
						$total_discount = $sr->getDiscountByOrder($info[$i]['orders_id']);	
						$subtotal_new = $sr->getSubtotalByOrder($info[$i]['orders_id']);
						$current_id = $info[$i]['orders_id'];				
					}
					
					?>
							<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
							
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo ($info[$i]['customer_number'] == "")?$info[$i]['customers_id'] : $info[$i]['customer_number']; } ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['customers_name']; } ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['customers_company']; } ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo tep_date_aus_format($sr->getOrderProcessedDate($info[$i]['orders_id']),"long");} ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo tep_date_aus_format($info[$i]['date_purchased'],"long"); } ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo tep_date_aus_format($sr->getOrderModifiedDate($info[$i]['orders_id']),"long"); } ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['orders_id']; } ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo $sr->getOrderStatusName($info[$i]['orders_status']); } ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['order_assigned_to']; } ?></td>
									<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['purchase_number']; } ?></td>														
											
									<td class="dataTableContent" align="left">
										<a href="<?php echo tep_catalog_href_link("product_info.php?products_id=" . $info[$i]['pid']) ?>" target="_blank"><?php echo $info[$i]['pname']; ?></a>
										<?php
										  if (is_array($info[$i]['attr'])) {
											$attr_info = $info[$i]['attr'];
											foreach ($attr_info as $attr) {
											  echo '<div style="font-style:italic;">&nbsp;' . $attr['quant'] . 'x ' ;
											  //  $attr['options'] . ': '
											  $flag = 0;
											  foreach ($attr['options_values'] as $value) {
												if ($flag > 0) {
												  echo "," . $value;
												} else {
												  echo $value;
												  $flag = 1;
												}
											  }
											  $price = 0;
											  foreach ($attr['price'] as $value) {
												$price += $value;
											  }
											  if ($price != 0) {
												echo ' (';
												if ($price > 0) {
												  echo "+";
												}
												echo $currencies->format($price). ')';
											  }
											  echo '</div>';
											}
										  }
										?>                    
								</td>						
								<td class="dataTableContent" align="center"><?php echo $info[$i]['proquant']; ?></td>
								<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['price']); ?></td>					
								<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
								<td class="dataTableContent" align="right">&nbsp;</td>
								<td class="dataTableContent" align="right">&nbsp;</td>
								<td class="dataTableContent">&nbsp;</td>
								<td class="dataTableContent">&nbsp;</td>						
								<td class="dataTableContent">&nbsp;</td>                    
						  </tr>
						  <?php 
						 if($i == $last-1) {  
							
							//manual calculation for GST
							$man_gst =  (($subtot + $shipping_ex_gst) * 10)/100;
							
						 ?>					
							<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
								<td class="dataTableContent">&nbsp;</td>
								<td class="dataTableContent" colspan="2"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>						
								<td class="dataTableContent" colspan="10">&nbsp;</td>						
								<td class="dataTableContent" align="right">
									<?php 
										if($currencies->format($subtot)!=$currencies->format($subtotal_new)) {
											echo "<font color='red'>" . $currencies->format($subtot) . "</font><br>";
											echo $currencies->format($subtotal_new);
										} else {
											echo $currencies->format($subtot); 
										}
									?>
								</td>
								<td class="dataTableContent" align="right"><?php echo $currencies->format($shipping_ex_gst); ?></td>
								<td class="dataTableContent" align="right">
									<?php 								
										if($currencies->format($man_gst)!=$currencies->format($total_gst)) {								
											echo "<font color='red'>" . $currencies->format($total_gst) . "</font><br>";
											echo $currencies->format($man_gst);
											//echo "<br>" . $current_id;
											$order_id_string .= $current_id ."\r\n\r\n";
										} else {
											echo $currencies->format($total_gst); 
										}								
									?>						
								</td>
								<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot + $shipping_ex_gst - $total_discount); ?></td>
								<td class="dataTableContent" align="right"><?php echo $currencies->format($total_discount); ?></td>					
								<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot + $shipping_ex_gst + $total_gst - $total_discount); ?></td>
							</tr>
						<?php
		
						}
			   
			   
				} else {
						// csv export
						  if (is_array($info[$i]['attr'])) {
							$attr_info = $info[$i]['attr'];
							foreach ($attr_info as $attr) {
							  $file_str .= $info[$i]['pname'] . "(";
							  $flag = 0;
							  foreach ($attr['options_values'] as $value) {
								if ($flag > 0) {
								  $file_str .= "," . $value;
								} else {
								  $file_str .= $value;
								  $flag = 1;
								}
							  }
							  $price = 0;
							  foreach ($attr['price'] as $value) {
								$price += $value;
							  }
							  if ($price != 0) {
								$file_str .= ' (';
								if ($price > 0) {
								  $file_str .= "+";
								} else {
								  $file_str .= " ";
								}
								$file_str .= $currencies->format($price). ')';
							  }
							  $file_str .= ")" . SR_SEPARATOR2;
							  if ($srDetail == 2) {
								$file_str .= $attr['quant'] . SR_SEPARATOR2;
								$file_str .= $currencies->format( $attr['quant'] * ($info[$i]['price'] + $price)) . SR_NEWLINE;
							  } else {
								$file_str .= $attr['quant'] . SR_NEWLINE;
							  }
							  $info[$i]['pquant'] = $info[$i]['pquant'] - $attr['quant'];
							}
						  }
						  if ($info[$i]['pquant'] > 0) {
							$file_str .= $info[$i]['pname'] . SR_SEPARATOR2;
							if ($srDetail == 2) {
							  $file_str .= $info[$i]['pquant'] . SR_SEPARATOR2;
							  $file_str .= $currencies->format($info[$i]['pquant'] * $info[$i]['price']) . SR_NEWLINE;
							} else {
							  $file_str .= $info[$i]['pquant'] . SR_NEWLINE;
							}
						  }
				  
				  }//CSV Report
				  
			  } //srMax reports
			  
			} //End of for loop
			
			$fp = fopen('issued_orders.txt', 'w');
			fwrite($fp, $order_id_string);
			fclose($fp);
			
		  } // srDetails 1 and 2 
		  
	} // Check Chart For NOT SET END
	else if($srChartFor=="revenue") {
					
		//Customers revenue by state - Line Chart
		foreach($info[0]['chart_customers_revenue_2'] as $zoneid => $timestamps) {						
			foreach($timestamps as $timestamp => $revs) {				
				if($srView==1) {
					$cdate = date("Y",$timestamp);
				} else if($srView==2) {
					$cdate = date("M Y",$timestamp);
				} else {
					$cdate = date("d-M-Y",$timestamp);
				}
				$crevs[$cdate][$zoneid] += $revs;						
			}			
		}
		
		//Revenue by product code - Bar and Line Chart
		//print_r($info[0]['chart_products_sum_arr']);
		foreach($info[0]['chart_products_sum_arr'] as $timestamps => $timestamp) {						
						
			if($srView==1) {
				$pcdate = date("Y",$timestamps);
			} else if($srView==2) {
				$pcdate = date("M Y",$timestamps);
			} else {
				$pcdate = date("d-M-Y",$timestamps);
			}
						
			foreach($timestamp as $procode => $pc_revenue) {												
				$pc_revenues[$pcdate][$procode] += $pc_revenue;	
			}			
		}
		
		
		//Customers revenue by state - Pie Chart
		$current_month = $info[0]['chart_date'];		
		foreach($info[0]['chart_customers_revenue'] as $customers => $month) {				
			$chart_customers_revenue[$customers] += $month[$current_month];						
			$chart_customers_revenue_month[$customers][] = $month;							
		}
		
	}
	
  
} //End of while loop 

print_r($pc_revenues);



if ($srExp < 2) {
?>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
	  
	   <!-- Place the chart here START -->
	   
	  <?php
	    
		//print_r($chart_ship);
		//print_r($chart_product);
				
	   if($srChartFor=="basic") {	
		
			$finacial_graph = "";
								
			foreach($chart_value as $month=>$value) {			
				$shipping = $chart_ship[$month];
				$product = $chart_product[$month];
				$finacial_graph .= '{year:"'.$month.'", revenue:'.$value.', shipping:'.$shipping.', productcost:'.$product.', color:"'.CHART_FINANCIAL_YEAR_COLOR.'"},';			
			}
			
		  ?>
		  
		  <script type="text/javascript">
			
			var chart;
			
			var chartData = [<?php echo $finacial_graph; ?>];
			
			var chartData1 = [
			   {country:"Total Revenue",litres:<?php echo $chart_revenue; ?>,color:"<?php echo CHART_REVENUE_COLOR; ?>"},
			   {country:"Product Cost",litres:<?php echo $chart_productcost; ?>,color:"<?php echo CHART_PRODUCT_COST_COLOR; ?>"},
			   {country:"Shipping",litres:<?php echo $chart_shipping; ?>,color:"<?php echo CHART_SHIPPING_COLOR; ?>"}];
			
			var chartData2 = [
				{country:"Overhead",litres:<?php echo $chart_overhead; ?>,color:"<?php echo CHART_OVERHEAD_COLOR; ?>"},			
				{country:"Labour",litres:<?php echo $chart_labour; ?>,color:"<?php echo CHART_LABOUR_COLOR; ?>"},				
				{country:"Material",litres:<?php echo $chart_material; ?>,color:"<?php echo CHART_MATERIAL_COLOR; ?>"}
				];
			
			
			window.onload = function() 
			{
				// Pie chart
				var chart1 = new AmCharts.AmPieChart();
				chart1.dataProvider = chartData1;			
				chart1.titleField = "country";
				chart1.valueField = "litres";
				chart1.colorField = "color";
				chart1.depth3D = 20;
				chart1.angle = 30;
				chart1.labelRadius = 30;
				chart1.labelText = "[[percents]]%";			
				legend1 = new AmCharts.AmLegend();
				legend1.align = "center";
				legend1.markerType = "circle";
				chart1.addLegend(legend1);			
				chart1.write("chartdiv1");
				
				// Pie chart
				var chart2 = new AmCharts.AmPieChart();
				chart2.dataProvider = chartData2;
				chart2.titleField = "country";
				chart2.valueField = "litres";
				chart2.colorField = "color";
				chart2.depth3D = 20;
				chart2.angle = 30;
				chart2.labelRadius = 30;
				chart2.labelText = "[[percents]]%";			
				legend2 = new AmCharts.AmLegend();
				legend2.align = "center";
				legend2.markerType = "circle";
				chart2.addLegend(legend2);			
				chart2.write("chartdiv2");
				
				//Bar column for financial year			
				chart = new AmCharts.AmSerialChart();
				//chart.pathToImages = "../../amcharts/javascript/images/";
				chart.dataProvider = chartData;				
				chart.marginTop = 15;
				chart.marginRight = 20;
				chart.categoryField = "year";				
				chart.angle = 30;
				chart.depth3D = 30;
				
				var legend = new AmCharts.AmLegend();
				chart.addLegend(legend);
				
				var graph1 = new AmCharts.AmGraph();
				graph1.title = "Revenue";
				graph1.valueField = "revenue";
				graph1.lineColor = "<?php echo CHART_FINANCIAL_YEAR_COLOR; ?>";
				graph1.colorField = "color";						
				graph1.type = "column";
				graph1.lineAlpha = 0;
				graph1.fillAlphas = 1;
				chart.addGraph(graph1);
				
				
				var graph2 = new AmCharts.AmGraph();
				graph2.title = "Shipping";
				graph2.valueField = "shipping";						
				graph2.type = "line";
				graph2.lineColor = "<?php echo CHART_SHIPPING_COLOR; ?>";	
				graph2.lineThickness = 2;
				graph2.bullet = "round";
				chart.addGraph(graph2);
				
				var graph3 = new AmCharts.AmGraph();
				graph3.title = "Product Cost";
				graph3.valueField = "productcost";					
				graph3.type = "line";
				graph3.lineColor = "<?php echo CHART_PRODUCT_COST_COLOR; ?>";	
				graph3.lineThickness = 2;
				graph3.bullet = "round";			
				chart.addGraph(graph3);
				
				chart.categoryAxis.gridPosition = "start";
				
				chart.write("chartdiv");
							
			}
			
			</script>
		  
		  
		  <?php
			if($srDetail == 0) {					
			?>
				
				<table align="center" border="0" width="100%">
					
					<tr>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Revenues </b><font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv1" style="width: 100%; height: 400px;  z-index:2;"></div>		
						</td>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Product Cost </b><font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chartdiv2" style="width: 100%; height: 400px;  z-index:2;"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Current Financial Year Revenue</b>	
							<br>
							<div id="chartdiv" style="width: 60%; height: 400px;  z-index:2;"></div>
						
						</td>
					</tr>
				</table>
				
			<?php			
				//print_r($chart_value);						
			}
		
	  } // Basic report chart
	  else if($srChartFor=="revenue") {
	  		
			//For Revenue, shipping and product cost
			$finacial_graph_rev = "";								
			foreach($chart_value as $month=>$value) {			
				$shipping = $chart_ship[$month];
				$product = $chart_product[$month];
				$finacial_graph_rev .= '{year:"'.$month.'", revenue:'.$value.', shipping:'.$shipping.', productcost:'.$product.', color:"'.CHART_FINANCIAL_YEAR_COLOR.'"},';			
			}
			
			//echo finacial_graph_rev;
			//echo "<br>";
			
			//Revenue per product cost
			$pc_fin_chart = "";
			foreach($pc_revenues as $pcdates => $pcmodels) {
				
				$pc_fin_chart .= '{year:"'.$pcdates.'",';
				
				foreach($pcmodels as $pcmodel=>$pcrevenue) {
					//echo $pcdates . " - " . $pcmodel . " - " . $pcrevenue;
					$pc_fin_chart .= $pcmodel.':'.$pcrevenue.',';
					//echo "<br>";
				}
				
				$pc_fin_chart .= '},';
			}
			
			$pc_fin_chart_settings= "";
			foreach($models as $model) {
				$pc_fin_chart_settings .= 'var graph = new AmCharts.AmGraph();
						graph.title = "'.$model.'";
						graph.labelText="[[value]]";
						graph.valueField = "'.$model.'";
						graph.type = "column";
						graph.lineAlpha = 0;
						graph.fillAlphas = 1;
						graph.lineColor = "#C72C95";
						chart.addGraph(graph);';
			}
			
			//echo $pc_fin_chart;
			
			
			$zones_id_arr = array("0","182","184","185","186","187","188","189","190");
			$zones_color = array("#33AD5B","#FF7900","#4C8BFF","#9CD988","#B36912","#FFEDB3","#FF0000","#FFB760","#8DCCF7");
			$zones_arr = array_combine($zones_id_arr, $zones_color);
			
			//Customer revenue per state	
			foreach($chart_customers_revenue_month as $customers => $month) {
				
				$zone_id = tep_get_customers_zone($customers);			
							
				foreach($month as $month_name => $revenue) {
				
					foreach($revenue as $rev_month => $rev) {																	
						if(is_numeric($zone_id) && $zone_id!=183) {
							$zone_revenue[$zone_id] += $rev;
							$zone_revenue_per_month[$zone_id][$rev_month] += $rev;
						} else {
							$zone_revenue[0] += $rev;
							$zone_revenue_per_month[0][$rev_month] += $rev;
						}	
						
					}
					
				}
						
			}
					
			//Generate Line chart data as revenue by state
			$revenue_line = "";			
			foreach($crevs as $ln_date_x => $ln_zones) {				
				$revenue_line .= $ln_date_x.';';
				foreach($zones_arr as $zoneid=>$color) {
					$revenue_line .= ($ln_zones[$zoneid]>0) ? $ln_zones[$zoneid] :0;
					$revenue_line .= ';';					
				}
				$revenue_line .= '\n';
			}
						
			$revenue_graph = ""; 
			foreach($zones_arr as $zoneid=>$color) {
				if($zoneid!=0) {
					$zone_name = tep_get_zonename($zoneid);
				} else {
					$zone_name = "Other Countries";
				}		
				
				//Generate Line chart settings as revenue by state				
				$revenue_setting .= "<graph gid='".$zoneid."'><title>".$zone_name."</title><color>".$color."</color><color_hover>FF0F00</color_hover><line_width>2</line_width><bullet>round</bullet><selected>0</selected></graph>";				
				//For Pie chart as revenue per state		
				$revenue_graph .= '{country:"'.$zone_name.'", revenue:"'.$zone_revenue[$zoneid].'", color:"'.$color.'"},';				
			}
						
			//Active customers
			//customers outside australia
			$active_outside_aus = tep_get_active_customers_outside_aus();						
			$western_act = tep_get_active_customers_by_state("187");				
			$north_act = tep_get_active_customers_by_state("189");
			$south_act = tep_get_active_customers_by_state("186");
			$queen_act = tep_get_active_customers_by_state("182");
			$wales_act = tep_get_active_customers_by_state("184");
			$vic_act = tep_get_active_customers_by_state("185");
			$tas_act = tep_get_active_customers_by_state("190");
			$act_act = tep_get_active_customers_by_state("188");
						
			if($srDetail == 0) {	?>
				
				 <script type="text/javascript">
			
					var chart;
					var chartData = [];
					var newValueAxis;
					var addAxis;
					var removeAxis;
			
					
					var chartData1 = [<?php echo $revenue_graph; ?>];
										
					var chartData2 = [
									{country:"Northern Territory",values:<?php echo $north_act; ?>,color:"#FFB760"},			
									{country:"ACT",values:<?php echo $act_act; ?>,color:"#FF0000"},	
									{country:"Western Australia",values:<?php echo $western_act; ?>,color:"#FFEDB3"},		
									{country:"Victoria",values:<?php echo $vic_act; ?>,color:"#9CD988"},
									{country:"New South Wales",values:<?php echo $wales_act; ?>,color:"#4C8BFF"},					
									{country:"Queensland",values:<?php echo $queen_act; ?>,color:"#FF7900"},
									{country:"Tasmania",values:<?php echo $tas_act; ?>,color:"#8DCCF7"},	
									{country:"South Australia",values:<?php echo $south_act; ?>,color:"#B36912"},
									{country:"Other Countries",values:<?php echo $active_outside_aus; ?>,color:"#33AD5B"}
									];
					
					var chartData3 = [<?php echo $pc_fin_chart; ?>];
					
					
					window.onload = function() 
					{
						// Pie chart
						var chart1 = new AmCharts.AmPieChart();
						chart1.dataProvider = chartData1;			
						chart1.titleField = "country";
						chart1.valueField = "revenue";
						chart1.colorField = "color";
						chart1.depth3D = 20;
						chart1.angle = 30;
						chart1.labelRadius = 30;
						chart1.labelText = "[[percents]]%";			
						legend = new AmCharts.AmLegend();
						legend.align = "center";
						legend.markerType = "circle";
						chart1.addLegend(legend);			
						chart1.write("chart_state_revenue");
						
						// Pie chart
						var chart2 = new AmCharts.AmPieChart();
						chart2.dataProvider = chartData2;
						chart2.titleField = "country";
						chart2.valueField = "values";
						chart2.colorField = "color";
						chart2.depth3D = 20;
						chart2.angle = 30;
						chart2.labelRadius = 30;
						chart2.labelText = "[[percents]]%";			
						legend2 = new AmCharts.AmLegend();
						legend2.align = "center";
						legend2.markerType = "circle";
						chart2.addLegend(legend2);			
						chart2.write("chart_state_active_customers");
						
						//Chart for revenue per products code
						chart = new AmCharts.AmSerialChart();
						chart.dataProvider = chartData3;
						chart.categoryField = "year";
						chart.marginLeft = 60;
						chart.marginTop = 30;
						chart.plotAreaBorderAlpha = 0.2;
						chart.rotate = true;
						
						<?php echo $pc_fin_chart_settings; ?>
						
						var valAxis = new AmCharts.ValueAxis();
						valAxis.stackType = "regular";
						valAxis.gridAlpha = 0.1;
						valAxis.axisAlpha = 0;
						chart.addValueAxis(valAxis);
						  
						var catAxis = chart.categoryAxis;
						catAxis.gridAlpha = 0.1;
						catAxis.axisAlpha = 0;
						catAxis.gridPosition = "start";
						  
						var legend = new AmCharts.AmLegend();
						legend.position = "right";
						legend.borderAlpha = 0.2;
						legend.horizontalGap = 10;
						legend.switchType = "v";
						chart.addLegend(legend);
						
						chart.write("chartdiv3");
					
					}
										
				</script>
				
				<script type="text/javascript">
				/* Line chart for revenue per state START */
							
							var params = 
							{
								bgcolor:"#FFFFFF"
							};
							
							var flashVars = 
							{
								path: "includes/javascript/amcharts/flash/",
								
								/* in most cases settings and data are loaded from files, but, as this require
								 all the files to be upladed to web server, we use inline data and settings here.*/
								 
								// settings_file: "../sampleData/column_settings.xml",
								// data_file: "../sampleData/column_data.xml"
								
								chart_data: '<?php echo $revenue_line; ?>',
								chart_settings: "<settings><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><plot_area><margins><left>50</left><right>40</right><top>55</top><bottom>30</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>8</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><?php echo $revenue_setting; ?></graphs><labels><label lid='0'><text><![CDATA[<b>Revenues</b>]]></text><y>15</y><text_size>13</text_size><align>center</align></label></labels></settings>"
							};
							
							// change 8 to 80 to test javascript version					
							if (swfobject.hasFlashPlayerVersion("8"))
							{
								swfobject.embedSWF("includes/javascript/amcharts/flash/amline.swf", "chartdiv", "800", "600", "8.0.0", "includes/javascript/amcharts/flash/expressInstall.swf", flashVars, params);
							}
							else
							{ 
								var amFallback = new AmCharts.AmFallback();
								// amFallback.settingsFile = flashVars.settings_file;  		// doesn't support multiple settings files or additional_chart_settins as flash does
								// amFallback.dataFile = flashVars.data_file;
								amFallback.chartSettings = flashVars.chart_settings;
								amFallback.pathToImages = "includes/javascript/amcharts/javascript/images/";
								amFallback.chartData = flashVars.chart_data;
								amFallback.type = "line";
								amFallback.write("chartdiv");
							}
							
				/* Line chart for revenue per state END */
			  </script>
			  
				
				<table align="center" border="0" width="100%">
					
					<tr>
						<td colspan="2" valign="top">
							<div id="chartdiv" style="width:800px; height:600px; background-color:#FFFFFF"></div>
						</td>
					</tr>
					<tr>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Revenues per State </b><font style="color:#EC7600;"> [ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>	
							<br>
							<div id="chart_state_revenue" style="width: 100%; height: 500px;  z-index:2;"></div>		
						</td>
						<td valign="top" width="50%" align="center">
							<br>
							<b>Active Customers per State</b> <font style="color:#EC7600;">[ From <?php echo date("d-m-Y",$startDate); ?> To <?php echo date("d-m-Y",($endDate - 24*3600)); ?> time period ] </font>
							<br>
							<div id="chart_state_active_customers" style="width: 100%; height: 500px;  z-index:2;"></div>
						</td>
					</tr>	
					<tr>
						<td colspan="2"><div id="chartdiv3" style="width: 100%; height: 400px;"></div></td>
					</tr>				
				</table>
				
			<?php			
				//print_r($chart_value);						
			}
	  }
		
	  ?>
	  	  
	  <!-- Place the chart here END -->
	  
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php
  if ($srExp < 1) {
    require(DIR_WS_INCLUDES . 'footer.php');
  }
?>
<!-- footer_eof //-->
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
} // end if $srExp < 2
if ($srExp == 2) {
  if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
    header('Content-Type: application/octetstream');
    header('Cache-Control: no-store, no-cache, must-revalidate' );
    header('Cache-Control: post-check=0, pre-check=0', false );
    header("Pragma: public");
    header("Cache-control: private");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header('Content-Transfer-Encoding: Binary');
    header("Content-length: " . strlen($file_str));
    header('Content-Disposition: attachment; filename=sales_report.csv');
  } else {
    header('Content-Type: application/octet-stream');
    header('Cache-Control: no-store, no-cache, must-revalidate' );
    header('Cache-Control: post-check=0, pre-check=0', false );
    header("Pragma: no-cache");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header('Content-Transfer-Encoding: Binary');
    header("Content-length: " . strlen($file_str));
    header('Content-Disposition: attachment; filename=sales_report.csv');
  }
  echo $file_str;
  die;
} elseif ($srExp == 1) {
  echo '<br><p align="right"><a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT2, tep_get_all_get_params(array('export'))) . '">' . tep_image_button('back.png', IMAGE_BACK) . '</a></p>';
}
?>