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
  
  //$srStatus = (isset($_GET['status']) ? $_GET['status'] : '');
  $srStatus = (isset($_GET['os']) ? implode("_",$_GET['os']) : '');
  
  //May 16 2011
  $srDetailPro = (isset($_GET['det_pro']) ? $_GET['det_pro'] : 'p_cat');
  
  // report views (1: yearly 2: monthly 3: weekly 4: daily)
  if ( ($report) && (tep_not_null($report)) ) {
  $srView = $report;
  }
  if ($srView < 1 || $srView > 4) {
    $srView = $srDefaultView;
  }

  // detail
  if ( isset($detail) && (tep_not_null($detail)) ) 
{    $srDetail = $_GET['detail'];
  }
  if ($srDetail < 0 || $srDetail > 4) {
    $srDetail = $srDefaultDetail;
  }
  
  // report views (1: yearly 2: monthly 3: weekly 4: daily)
  if ( ($export) && (tep_not_null($export)) ) 
{    $srExp = $_GET['export'];
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
      
  /*
  // order status
  if ( ($status) && (tep_not_null($status))) {    
  	$srStatus = $status;
  }
  if (!is_numeric($srStatus)) {
    $srStatus = 0;
  }
  */
  
  // sort
  if ( ($sort) && (tep_not_null($sort)) ) {
    $srSort = $sort;
  }
  if ($srSort < 1 || $srSort > 6) {
    $srSort = $srDefaultSort;
  }
    
  // check start and end Date
  $startDate = "";
  $startDateG = 0;
  if ( ($startD) && (tep_not_null($startD)) ) 
{    $sDay = $startD;
    $startDateG = 1;
  } else {
    $sDay = 1;
  }
  if ( ($startM) && (tep_not_null($startM)) ) 
{    $sMon = $startM;
    $startDateG = 1;
  } else {
    $sMon = 1;
  }
  if ( ($startY) && (tep_not_null($startY)) ) 
{    $sYear = $startY;
    $startDateG = 1;
  } else {
    $sYear = date("Y");
  }
  if ($startDateG) {
    $startDate = mktime(0, 0, 0, $sMon, $sDay, $sYear);
  } else {
    $startDate = mktime(0, 0, 0, date("m"), 1, date("Y"));
  }
    
  $endDate = "";
  $endDateG = 0;
  if ( ($endD) && (tep_not_null($endD)) ) {
    $eDay = $endD;
    $endDateG = 1;
  } else {
    $eDay = 1;
  }
  if ( ($endM) && (tep_not_null($endM)) ) {
    $eMon = $endM;
    $endDateG = 1;
  } else {
    $eMon = 1;
  }
  if ( ($endY) && (tep_not_null($endY)) ) {
    $eYear = $endY;
    $endDateG = 1;
  } else {
    $eYear = date("Y");
  }
  if ($endDateG) {
    $endDate = mktime(0, 0, 0, $eMon, $eDay + 1, $eYear);
  } else {
    $endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
  }
  
  //after fixing date picker
  $report_from = ""; $report_to = "";
  
  if(isset($_GET['reports_from']) && !empty($_GET['reports_from'])){      
	  $startDate = strtotime(tep_db_input($_GET['reports_from']));
	  $report_from = date("d-m-Y", strtotime(tep_db_input($_GET['reports_from'])));
  } else {
     //$startDate = mktime(0, 0, 0, 7, 1, date("Y"));
	  //$report_from = date("d-m-Y", mktime(0, 0, 0, 7, 1, date("Y")));
	  $startDate = mktime(0, 0, 0, date("m"), 1, date("Y"));
	  $report_from = date("d-m-Y", mktime(0, 0, 0, date("m"), 1, date("Y")));
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
  $sr = new sales_report($srView, $startDate, $endDate, $srSort, $srStatus, $srFilter, $srDetail);  
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
		
		//Submit form when clicking "Chart Revenue" button
		$("a#chart_revenue").click(function() {
			$("#chartFor").val("revenue");
			$("#frmSalesReport").submit();
		});
		
		//Submit form when clicking "Chart Product cost" button
		$("a#chart_product_cost").click(function() {
			$("#chartFor").val("procost");
			$("#frmSalesReport").submit();
		});
		
		//Submit form when clicking "Customer Map" button
		$("a#chart_customer_map").click(function() {
			$("#chartFor").val("custmap");
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

<style type="text/css">
	
	.map1 { position:relative; width:377px; height:354px; }
	.map2 { position:relative; width:377px; height:354px; }
	
	.active-cust { background:url(images/active.png) left no-repeat; height:18px; padding-left:18px; font-weight:bold; }
	.inactive-cust { clear:both; background:url(images/inactive.png) left no-repeat; height:18px; padding-left:18px; font-weight:bold; }
	
	.male-cust { background:url(images/male-16.png) left no-repeat; height:16px; padding-left:18px; text-align: left; }
	.female-cust { clear:both; background:url(images/female-16.png) left no-repeat; height:16px; padding-left:18px; text-align: left; }
	
	.western, .northern, .south, .south-wales, .queensland, .victoria, .tasmania, .act, .other  { position:absolute;  clear:both; width:80px; }
	.western { top:160px; left:20px;}
	.northern { top:80px; left:125px; }
	.south { top:185px; left:125px;  }
	.south-wales { top:230px; left:280px; }
	.queensland { top:120px; left:220px; }
	.victoria { top:265px; left:240px; }
	.tasmania { top:335px; left:290px; }
	.act { top:300px; left:340px; }
	.other { top:300px; left:20px; }
</style>

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
                <td class="pageHeading" width="66%">
					<a href="javascript:void(0);" id="chart_revenue"><img src="images/revenue.png" /></a> &nbsp; 
					<a href="javascript:void(0);" id="chart_product_cost"><img src="images/product-cost.png" /></a> &nbsp; 
					<a href="javascript:void(0);" id="chart_customer_map"><img src="images/customer-map.png" /> </a>
					<?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
				</td>
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
                  <td width="12%" align="left" valign="top">  
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
                    <?php echo REPORT_EXP; ?><br>
                    <select name="export" size="1" style="width:100px;">
                      <option value="0" selected><?php echo EXP_NORMAL; ?></option>
                      <option value="1"><?php echo EXP_HTML; ?></option>
                      <option value="2"><?php echo EXP_CSV; ?></option>
                    </select><br>
                    <?php echo REPORT_SORT; ?><br>
                    <select name="sort" size="1" style="width:100px;">
                      <option value="0"<?php if ($srSort == 0) echo " selected"; ?>><?php echo SORT_VAL0; ?></option>
                      <option value="1"<?php if ($srSort == 1) echo " selected"; ?>><?php echo SORT_VAL1; ?></option>
                      <option value="2"<?php if ($srSort == 2) echo " selected"; ?>><?php echo SORT_VAL2; ?></option>
                      <option value="3"<?php if ($srSort == 3) echo " selected"; ?>><?php echo SORT_VAL3; ?></option>
                      <option value="4"<?php if ($srSort == 4) echo " selected"; ?>><?php echo SORT_VAL4; ?></option>
                      <option value="5"<?php if ($srSort == 5) echo " selected"; ?>><?php echo SORT_VAL5; ?></option>
                      <option value="6"<?php if ($srSort == 6) echo " selected"; ?>><?php echo SORT_VAL6; ?></option>
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
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_LABOUR; ?></td>
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_OVERHEAD; ?></td>
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_MATERIAL; ?></td>                      
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_REVENUE;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  TABLE_HEADING_SHIPPING;?></td>
					  <td class="dataTableHeadingContent" align="right"><?php echo  "GST";?></td>
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
						  <?php } ?>							 
						  <td class="dataTableHeadingContent" align="right"><?php echo PRO_COST_LABOUR; ?></td>
						  <td class="dataTableHeadingContent" align="right"><?php echo PRO_COST_OVERHEAD; ?></td>
						  <td class="dataTableHeadingContent" align="right"><?php echo PRO_COST_MATERIAL; ?></td>						  
						  <td class="dataTableHeadingContent" align="right"><?php echo PRO_COST_PRODUCT_COST;?></td>
						  <td class="dataTableHeadingContent" align="right"><?php echo  PRO_COST_REVENUE;?></td>						  
						</tr>
					<?php					 	
					} else if($srDetail == 4) { ?>
						
						<tr class="dataTableHeadingRow">
						  <td class="dataTableHeadingContent" align="left" width="10%"><?php echo PRO_COST_DATE; ?></td>
						  <td class="dataTableHeadingContent" align="Left" width="12%"><?php echo "Family Product";?></td>	
						  <td class="dataTableHeadingContent" align="Left" width="12%"><?php echo PRO_COST_CATEGORY;?></td>	
						  <td class="dataTableHeadingContent" align="Left" width="12%"><?php echo "Products name";?></td>	
						  <td class="dataTableHeadingContent" align="Left" width="6%"><?php echo "Order";?></td>		
						  <td class="dataTableHeadingContent" align="Left" width="5%"><?php echo "Quantity";?></td>					  
						  <td class="dataTableHeadingContent" align="right" width="6%"><?php echo PRO_COST_LABOUR; ?></td>
						  <td class="dataTableHeadingContent" align="right" width="6%"><?php echo PRO_COST_OVERHEAD; ?></td>
						  <td class="dataTableHeadingContent" align="right" width="6%"><?php echo PRO_COST_MATERIAL; ?></td>					  
						  <td class="dataTableHeadingContent" align="right" width="10%"><?php echo PRO_COST_PRODUCT_COST;?></td>
						  <td class="dataTableHeadingContent" align="right" width="10%"><?php echo  PRO_COST_REVENUE;?></td>						  
						</tr>
					<?php 	
					} 
					else {
					?>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo TABLE_HEADING_CUST_NO; ?></td>
						<td class="dataTableHeadingContent" align="left" width="8%"><?php echo TABLE_HEADING_CUST_NAME;?></td>
						<td class="dataTableHeadingContent" align="left" width="8%"><?php echo TABLE_HEADING_CUST_COMPANY;?></td>
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo "Order Date"; ?></td>
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo "Last Modified"; ?></td>
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo TABLE_HEADING_ORDER_NO; ?></td>
						<td class="dataTableHeadingContent" align="left" width="6%"><?php echo "Status"; ?></td>
						<td class="dataTableHeadingContent" align="left" width="4%"><?php echo TABLE_HEADING_PONUMBER; ?></td>						
						<!--<td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></td>-->
						<!--<td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></td>-->
						<td class="dataTableHeadingContent" align="left" width="8%"><?php echo TABLE_HEADING_PRODUCT; ?></td>
						<td class="dataTableHeadingContent" align="center" width="4%"><?php echo TABLE_HEADING_QTY;?></td>
						<td class="dataTableHeadingContent" align="right" width="6%"><?php echo TABLE_HEADING_UNIT_PRICE;?></td>
						<td class="dataTableHeadingContent" align="right" width="7%"><?php echo TABLE_HEADING_SUB_TOTAL;?></td>
						<td class="dataTableHeadingContent" align="right" width="6%"><?php echo "GST Total";?></td>						
						<td class="dataTableHeadingContent" align="right" width="10%"><?php echo "Total (excl GST)";?></td>
						<td class="dataTableHeadingContent" align="right" width="8%"><?php echo "Shipping"; ?></td>
						<td class="dataTableHeadingContent" align="right" width="6%"><?php echo  TABLE_HEADING_DISCOUNT;?></td>
						<td class="dataTableHeadingContent" align="right" width="12%"><?php echo "Total";?></td>
                    </tr>
					<?php
					}// end of $srDetail != 0 
				
				} // Check Chart For NOT SET END
				
} // end of if $srExp < 2 csv export

$sum = 0;
while ($sr->actDate < $sr->endDate) {
  $info = $sr->getNext();
 
  $last = sizeof($info) - 1;
  
  //get product cost results
  if($srDetail == 3) { 	$pInfo = $sr->getProductCosts($srDetailPro);  }
  
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
  
  if ($srExp < 2) {
?>
                    <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
<?php
    switch ($srView) {
      case '3':
?>
                      <?php if($srDetail == 0) {?><td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td><?php }?>
					 
					  <?php if($srDetail != 0) {?> 
				<td class="dataTableContent" colspan="17"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
				<?php }?>
<?php
        break;
      case '4':
?>
                      <?php if($srDetail == 0) {?><td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td><?php }?>
					 
					  <?php if($srDetail != 0) {?> 
				<td class="dataTableContent" colspan="17"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td>
				<?php }?>
<?php
        break;
      default;
?>
                     <?php if($srDetail == 0) {?> <td class="dataTableContent" align="right"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td><?php } ?>
					  
					  <?php if($srDetail != 0) {?> 
				<td class="dataTableContent" colspan="17"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
<?php
		}
    }
				if($srDetail == 0) { 
										
					
				?> 
				<td class="dataTableContent" align="center"><?php echo $info[0]['order']; ?></td>
				<td class="dataTableContent" align="center"><?php echo $info[0]['orders_items']; ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['labour_cost']); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['overhead_cost']); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['material_cost']); ?></td>					
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['sub_total']); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['shipping']); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['gst_total']); ?></td>
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
		$subtotal = 0;
		$subtotal_pro_cost = 0;
		$subtotal_labour = 0;
		$subtotal_overhead = 0;
		$subtotal_material = 0;
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
				<td class="dataTableContent" align="right">&nbsp;</td>
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
		
	if($srDetail == 4) {
  	
		//print_r($info['opc_detail']);
		
		//for product cost listing
		$subtotal = 0;
		$subtotal_pro_cost = 0;
		$subtotal_labour = 0;
		$subtotal_overhead = 0;
		$subtotal_material = 0;
		
		for($i=0;$i<count($pInfo['opc_detail']);$i++) { 
									
			$category = tep_get_product_category($pInfo['opc_detail'][$i]['products_id']);			
			
			$manufac_qry = tep_db_query("select manufacturers_id from products where products_id='".$pInfo['opc_detail'][$i]['products_id']."'");
			$manufac_info = tep_db_fetch_array($manufac_qry);
			$manufac_name = tep_get_manufacturer_name($manufac_info['manufacturers_id']);
			
			$product_costs = ($pInfo['opc_detail'][$i]['labour_cost'] + $pInfo['opc_detail'][$i]['overhead_cost'] + $pInfo['opc_detail'][$i]['material_cost']);
			
			$labour_cost = $pInfo['opc_detail'][$i]['labour_cost'] * $pInfo['opc_detail'][$i]['products_quantity'];
			
			$overhead_cost = $pInfo['opc_detail'][$i]['overhead_cost'] * $pInfo['opc_detail'][$i]['products_quantity'];
			
			$material_cost = $pInfo['opc_detail'][$i]['material_cost'] * $pInfo['opc_detail'][$i]['products_quantity'];
			
			$revenue = $labour_cost + $overhead_cost + $material_cost;
			
			$sel_ord_date = tep_db_query("select date_purchased from orders where orders_id='".$pInfo['opc_detail'][$i]['orders_id']."'");
			
			$order_date = tep_db_fetch_array($sel_ord_date);
		?>		
			<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
				<td class="dataTableContent" width="10%"><?php echo date("m/d/Y H:i:s",strtotime($order_date['date_purchased']));  ?></td>
				<td class="dataTableContent" width="10%"><b><?php echo ($manufac_name!="")?$manufac_name : "N/A";  ?></b></td>	
				<td class="dataTableContent" width="10%"><b><?php echo $category['parent_name'];  ?></b></td>			
				<td class="dataTableContent" width="10%"><b><?php echo $category['name'];  ?></b></td>		
				<td class="dataTableContent" width="6%"><?php echo $pInfo['opc_detail'][$i]['orders_id'];  ?></td>
				<td class="dataTableContent" width="5%"><?php echo $pInfo['opc_detail'][$i]['products_quantity'];  ?></td>
				<td class="dataTableContent" width="6%" align="right"><?php echo  $currencies->format($labour_cost);  ?></td>
				<td class="dataTableContent" width="6%" align="right"><?php echo $currencies->format($overhead_cost);  ?></td>
				<td class="dataTableContent" width="6%" align="right"><?php echo $currencies->format($material_cost);  ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($revenue);  ?></td>
				<td class="dataTableContent" align="right">&nbsp;</td>
			</tr>			
			
		<?php 	
		
			$subtotal += $revenue;
			$subtotal_pro_cost += $product_costs;
			$subtotal_labour += $labour_cost;
			$subtotal_overhead += $overhead_cost;
			$subtotal_material += $material_cost;
			
		
		} 
		
		if(isset($info[$last - 1]['totsum'])) {
			?>
			
			<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">			
				<td class="dataTableContent" style="background-color:#FFE5FF;"><b>Sub Total</b> : </td>
				<td class="dataTableContent" colspan="5">&nbsp;</td>
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
  
  $customer = "";
  if ($srDetail!=0 && $srDetail!=3 && $srDetail!=4) {
  
	//Order Total 
	$tmp_orderid = 0;
	$subtot = 0;
	$gst = 0;
	$total = 0;
	$rec_count = 0;
	
	$shipping_ex_gst = 0;
	$total_gst = 0;
	$total_discount = 0;
	
	//Order Total eof
  
    for ($i = 0; $i < $last; $i++) {
      	  
	  if ($srMax == 0 or $i < $srMax) {
        
		if ($srExp < 2) {

			//Order Total 
			if($tmp_orderid != $info[$i]['orders_id'] && $tmp_orderid !=0) {  
								  
			?>
	
				<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
					<td class="dataTableContent">&nbsp;</td>
					<td class="dataTableContent"><?php echo TABLE_HEADING_ORDER_TOTAL;?></td>
					<td class="dataTableContent" colspan="9">&nbsp;</td>
					<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot); ?></td>
					<td class="dataTableContent" align="right">
						<?php 
							//echo $currencies->format($gst); 
							echo $currencies->format($total_gst); 
						
						?>
					</td>
					<td class="dataTableContent" align="right"><?php echo $currencies->format($total + $shipping_ex_gst - $total_discount); ?></td>
					<td class="dataTableContent" align="right"><?php echo $currencies->format($shipping_ex_gst); ?></td>					
					<td class="dataTableContent" align="right"><?php echo $currencies->format($total_discount); ?></td>					
					<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot + $shipping_ex_gst + $total_gst - $total_discount); ?></td>
				</tr>
				<?php
				$subtot = 0;
				$gst 	= 0;
				$total 	= 0;
				$rec_count = 0;
				$shipping_ex_gst = 0;
				$total_gst = 0;
				$total_discount = 0;
			}
										 
			$tmp_orderid = $info[$i]['orders_id'];
			$subtot 	+= $info[$i]['psum'];
			
			$gst 		+= $info[$i]['pgst'];
			$total 		+= $info[$i]['pgst_total'];
			$rec_count++;
		  	//Order Total eof
			
			if ($rec_count == 1) {
				$shipping_ex_gst = $sr->getShippingByOrder($info[$i]['orders_id']);
	  			$total_gst = $sr->getGstByOrder($info[$i]['orders_id']);
				$total_discount = $sr->getDiscountByOrder($info[$i]['orders_id']);				
			}
			
            ?>
                    <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
					<?php if($srDetail != 0){ ?>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo ($info[$i]['customer_number'] == "")?$info[$i]['customers_id'] : $info[$i]['customer_number']; } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['customers_name']; } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['customers_company']; } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo tep_date_aus_format($info[$i]['date_purchased'],"long"); } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo tep_date_aus_format($info[$i]['last_modified'],"long"); } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['orders_id']; } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $sr->getOrderStatusName($info[$i]['orders_status']); } ?></td>
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
						<!--<td class="dataTableContent" align="right"><?php //echo $info[$i]['pquant']; ?></td>-->
						<td class="dataTableContent" align="center"><?php echo $info[$i]['proquant']; ?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['price']); ?></td>
					
					<?php }?>
					<?php if ($srDetail == 2) {?>
									<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
									<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['pgst']); ?></td>									
									<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['pgst_total']); ?></td>
									<td class="dataTableContent">&nbsp;</td>
									<td class="dataTableContent">&nbsp;</td>
					<?php } else { ?>
									<td class="dataTableContent">&nbsp;</td>
									<td class="dataTableContent">&nbsp;</td>
									<td class="dataTableContent">&nbsp;</td>
									<td class="dataTableContent">&nbsp;</td>
									<td class="dataTableContent">&nbsp;</td>
					<?php } ?>
						
						<td class="dataTableContent">&nbsp;</td>                    
                  </tr>
				  <?php 
				 if($i == $last-1) {  ?>

					<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
						<td class="dataTableContent">&nbsp;</td>
						<td class="dataTableContent"><?php echo TABLE_HEADING_ORDER_TOTAL;?></td>						
						<td class="dataTableContent" colspan="9">&nbsp;</td>						
						<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot);?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($total_gst);?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($total + $shipping_ex_gst - $total_discount);?></td>
						<td class="dataTableContent" align="right"><?php echo $currencies->format($shipping_ex_gst); ?></td>						
						<td class="dataTableContent" align="right"><?php echo $currencies->format($total_discount); ?></td>					
						<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot + $shipping_ex_gst + $total_gst - $total_discount);?></td>
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
				  
			  } //CSV Report
				
          } //srMax reports
       
	   } //End of for loop
	   
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
		
  } // Check Chart For Revenue
  else if($srChartFor=="procost") {
		//Revenue by product code - Pie Chart	
		foreach($info[0]['chart_products_sum_arr'] as $timestamps => $timestamp) {													
			foreach($timestamp as $procode => $pc_revenue) {												
				$pc_revenues[$procode] += $pc_revenue;	
			}			
		}
		
		//Total Cost by product code - Pie Chart	
		foreach($info[0]['chart_products_costs_tot'] as $timestamps => $timestamp) {													
			foreach($timestamp as $procode => $pc_cost) {												
				$pc_costs[$procode] += $pc_cost;	
			}			
		}
		
		//Material Cost by product code - Bar chart
		foreach($info[0]['chart_material_costs_tot'] as $timestamps => $timestamp) {													
			if($srView==1) {
				$cdate = date("Y",$timestamps);
			} else if($srView==2) {
				$cdate = date("M Y",$timestamps);
			} else {
				$cdate = date("d-M-Y",$timestamps);
			}
			foreach($timestamp as $procode => $pm_cost) {												
				$pm_costs[$procode] += $pm_cost;	
				$pm_costs_per_time[$cdate] += $pm_cost;	
			}
		}		
		//Labour Cost by product code - Bar Chart	
		foreach($info[0]['chart_labour_costs_tot'] as $timestamps => $timestamp) {													
			if($srView==1) {
				$cdate = date("Y",$timestamps);
			} else if($srView==2) {
				$cdate = date("M Y",$timestamps);
			} else {
				$cdate = date("d-M-Y",$timestamps);
			}
			foreach($timestamp as $procode => $pl_cost) {												
				$pl_costs[$procode] += $pl_cost;
				$pl_costs_per_time[$cdate] += $pl_cost;		
			}
		}		
		//Overhead Cost by product code - Bar Chart	
		foreach($info[0]['chart_overhead_costs_tot'] as $timestamps => $timestamp) {													
			if($srView==1) {
				$cdate = date("Y",$timestamps);
			} else if($srView==2) {
				$cdate = date("M Y",$timestamps);
			} else {
				$cdate = date("d-M-Y",$timestamps);
			}
			foreach($timestamp as $procode => $po_cost) {												
				$po_costs[$procode] += $po_cost;
				$po_costs_per_time[$cdate] += $po_cost;		
			}	
		}
		
		foreach($info[0]['chart_customer_revenue_all'] as $zid => $crevenue) {		
			$chart_customers_revenue[$zid] += $crevenue;		
		} 
		foreach($info[0]['chart_customer_pc_all'] as $pc_zid => $pc_crevenue) {		
			$chart_customers_pc[$pc_zid] += $pc_crevenue;		
		} 
		
	}
	else if($srChartFor=="custmap") {		
				
		foreach($info[0]['chart_customer_revenue_all'] as $zid => $crevenue) {		
			$chart_customers_revenue[$zid] += $crevenue;		
		} 
		foreach($info[0]['chart_customer_shipping_all'] as $sh_zid => $sh_crevenue) {		
			$chart_customers_shipping[$sh_zid] += $sh_crevenue;		
		}			
		
	}//End of custmap 
	
	
} //End of while loop 

//print_r($pc_revenues);


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
	  if($srChartFor=="basic") {			
			include_once("sales_report_chart_basic.php");			
	  } 
	  else if($srChartFor=="revenue") {	  		
			include_once("sales_report_chart_revenue.php");				
	  }
	  else if($srChartFor=="procost") {	  
	  		include_once("sales_report_chart_procost.php");	
	  }
	  else if($srChartFor=="custmap") {	  
	  		include_once("sales_report_chart_custmap.php");	
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