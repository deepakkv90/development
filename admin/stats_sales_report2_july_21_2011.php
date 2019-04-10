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
  
  require(DIR_WS_CLASSES . 'sales_report2.php');
  $sr = new sales_report($srView, $startDate, $endDate, $srSort, $srStatus, 
$srFilter);  $startDate = $sr->startDate;
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
	});
	
</script>

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
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table>
          </td>
        </tr>
<?php
    if ($srExp < 1) {
?>
        <tr>
          <td colspan="2">
            <form action="" method="get">
            <?php
              if (isset($_GET[tep_session_name()])) {
                echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
              }
            ?>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" rowspan="2">
                    <input type="radio" name="report" value="1" <?php if ($srView == 1) echo "checked"; ?>><?php echo REPORT_TYPE_YEARLY; ?><br>
                    <input type="radio" name="report" value="2" <?php if ($srView == 2) echo "checked"; ?>><?php echo REPORT_TYPE_MONTHLY; ?><br>
                    <input type="radio" name="report" value="3" <?php if ($srView == 3) echo "checked"; ?>><?php echo REPORT_TYPE_WEEKLY; ?><br>
                    <input type="radio" name="report" value="4" <?php if ($srView == 4) echo "checked"; ?>><?php echo REPORT_TYPE_DAILY; ?><br>
                  </td>
                  <td>
<?php echo REPORT_START_DATE; ?><br>
                    <select name="startD" size="1">
<?php
      if ($startDate) {
        $j = date("j", $startDate);
      } else {
        $j = 1;
      }
      for ($i = 1; $i < 32; $i++) {
?>
                        <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
      }
?>
                    </select>
                    <select name="startM" size="1">
<?php
      if ($startDate) {
        $m = date("n", $startDate);
      } else {
        $m = 1;
      }
      for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
      }
?>
                    </select>
                    <select name="startY" size="1">
<?php
      if ($startDate) {
        $y = date("Y") - date("Y", $startDate);
      } else {
        $y = 0;
      }
      for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo date("Y") - $i; ?></option>
<?php
    }
?>
                    </select>
                  </td>
                  <td rowspan="2" align="left" valign="top">
                    <?php echo REPORT_DETAIL; ?><br>
                    <select name="detail" size="1" style="width:150px;">
                      <option value="0"<?php if ($srDetail == 0) echo "selected"; ?>><?php echo DET_HEAD_ONLY; ?></option>
                      <option value="1"<?php if ($srDetail == 1) echo " selected"; ?>><?php echo DET_DETAIL; ?></option>
                      <option value="2"<?php if ($srDetail == 2) echo " selected"; ?>><?php echo DET_DETAIL_ONLY; ?></option>
					  <option value="3"<?php if ($srDetail == 3) echo " selected"; ?>><?php echo DET_PRODUCTS_COST; ?></option>
					  <!-- <option value="4"<?php if ($srDetail == 4) echo " selected"; ?>><?php echo "Detailed Products Cost"; ?></option>-->
                    </select><br>
					<?php 
					if($srDetail == 3) {
					echo "Product Cost By"; ?><br>
                    <select name="det_pro" size="1" style="width:150px;">                     
                      <option value="p_cat"<?php if ($srDetailPro == "p_cat") echo " selected"; ?>>Category</option>
                      <option value="p_family"<?php if ($srDetailPro == "p_family") echo " selected"; ?>>Family Product</option>
                      <option value="p_name"<?php if ($srDetailPro == "p_name") echo " selected"; ?>>Product</option>                   
                    </select>					
					<?php } ?>

                  </td>
                  <td rowspan="2" align="left">
                    <?php echo REPORT_MAX; ?><br>
                    <select name="max" size="1" style="width:150px;">
                      <option value="0"><?php echo REPORT_ALL; ?></option>
                      <option<?php if ($srMax == 1) echo " selected"; ?>>1</option>
                      <option<?php if ($srMax == 3) echo " selected"; ?>>3</option>
                      <option<?php if ($srMax == 5) echo " selected"; ?>>5</option>
                      <option<?php if ($srMax == 10) echo " selected"; ?>>10</option>
                      <option<?php if ($srMax == 25) echo " selected"; ?>>25</option>
                      <option<?php if ($srMax == 50) echo " selected"; ?>>50</option>
                    </select><br>
					<!--
					<?php echo REPORT_STATUS_FILTER; ?><br>
                    <select name="status" size="1" style="width:150px;">
                      <option value="0"><?php echo REPORT_ALL; ?></option>
<?php
                        foreach ($sr->status as $value) {
?>
                      <option value="<?php echo $value["orders_status_id"]?>"<?php if ($srStatus == $value["orders_status_id"]) echo " selected"; ?>><?php echo $value["orders_status_name"] ; ?></option>
<?php
                         }
?>
                    </select><br>
					-->
					
					<!-- Modified to display status lightboxes with checkbox STARTS -->
					
					<?php //echo REPORT_STATUS_FILTER; ?><br>
					<div id="sr_status" style="width:1px; height:1px; overflow:hidden;">
						<p>
						<table align="center" width="100%" border="0">
							<tr>
								<td><input type="checkbox" id="os_all" name="os_all" onclick="checkAllStatus(this.checked)" <?php echo (isset($_GET['os_all']))? "checked":""; ?>></td>
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
							if(($i%3)==0) echo "</tr>";
                         }
						?> 
						</table>
						<div style="text-align:center;"><input type="button" id="TB_closeWindowButton" value="Done"></div>
						</p>                   
					</div>                    
					<input alt="#TB_inline?height=250&width=600&inlineId=sr_status&modal=true" title="Select Orders Status" class="thickbox" type="button" value="Select Status" />
					
					<!-- Modified to display status lightboxes with checkbox ENDS -->	
					
					
					
                  </td>
                  <td rowspan="2" align="left">
                    <?php echo REPORT_EXP; ?><br>
                    <select name="export" size="1" style="width:150px;">
                      <option value="0" selected><?php echo EXP_NORMAL; ?></option>
                      <option value="1"><?php echo EXP_HTML; ?></option>
                      <option value="2"><?php echo EXP_CSV; ?></option>
                    </select><br>
                    <?php echo REPORT_SORT; ?><br>
                    <select name="sort" size="1" style="width:150px;">
                      <option value="0"<?php if ($srSort == 0) echo " selected"; ?>><?php echo SORT_VAL0; ?></option>
                      <option value="1"<?php if ($srSort == 1) echo " selected"; ?>><?php echo SORT_VAL1; ?></option>
                      <option value="2"<?php if ($srSort == 2) echo " selected"; ?>><?php echo SORT_VAL2; ?></option>
                      <option value="3"<?php if ($srSort == 3) echo " selected"; ?>><?php echo SORT_VAL3; ?></option>
                      <option value="4"<?php if ($srSort == 4) echo " selected"; ?>><?php echo SORT_VAL4; ?></option>
                      <option value="5"<?php if ($srSort == 5) echo " selected"; ?>><?php echo SORT_VAL5; ?></option>
                      <option value="6"<?php if ($srSort == 6) echo " selected"; ?>><?php echo SORT_VAL6; ?></option>
                    </select><br>
                  </td>
                </tr>
                <tr>
                  <td>
<?php echo REPORT_END_DATE; ?><br>
                    <select name="endD" size="1">
<?php
    if ($endDate) {
      $j = date("j", $endDate - 60* 60 * 24);
    } else {
      $j = date("j");
    }
    for ($i = 1; $i < 32; $i++) {
?>
                      <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
    }
?>
                    </select>
                    <select name="endM" size="1">
<?php
    if ($endDate) {
      $m = date("n", $endDate - 60* 60 * 24);
    } else {
      $m = date("n");
    }
    for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
    }
?>
                    </select>
                    <select name="endY" size="1">
<?php
    if ($endDate) {
      $y = date("Y") - date("Y", $endDate - 60* 60 * 24);
    } else {
      $y = 0;
    }
    for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo
date("Y") - $i; ?></option><?php
    }
?>
                    </select>
                  </td>
                </tr>
              </table>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: .5em;">
                <tr>
                  <td colspan="5" align="right">
                    <?php echo tep_image_submit('send.png',REPORT_SEND); ?>                    
                  </td>
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
                    
					<?php
					if($srDetail == 0){
					?>
					<tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDERS;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ITEMS; ?></td>					  
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_LABOUR; ?></td>
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_OVERHEAD; ?></td>
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_MATERIAL; ?></td>                      
					  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_REVENUE;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  TABLE_HEADING_SHIPPING;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo  TABLE_HEADING_DISCOUNT;?></td>
                    </tr>
					<?php
					} else if($srDetail == 3) { ?>
						
						<tr class="dataTableHeadingRow">						 
						  <td class="dataTableHeadingContent" align="Left"><?php echo "Family Product";?></td>	
						  <td class="dataTableHeadingContent" align="Left"><?php echo PRO_COST_CATEGORY;?></td>	
						  <td class="dataTableHeadingContent" align="Left"><?php echo "Products name";?></td>							 
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
						<td class="dataTableHeadingContent" align="center" width="7%"><?php echo TABLE_HEADING_CUST_NO; ?></td>
						<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUST_NAME;?></td>
						<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUST_COMPANY;?></td>
						<td class="dataTableHeadingContent" align="center" width="7%"><?php echo TABLE_HEADING_ORDER_NO; ?></td>
						<td class="dataTableHeadingContent" align="center" width="10%"><?php echo TABLE_HEADING_PONUMBER; ?></td>
						<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></td>
						<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></td>
						<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRODUCT; ?></td>
						<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_QTY;?></td>
						<td class="dataTableHeadingContent" align="center" width="7%"><?php echo TABLE_HEADING_UNIT_PRICE;?></td>
						<td class="dataTableHeadingContent" align="center" width="7%"><?php echo TABLE_HEADING_SUB_TOTAL;?></td>
						<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_GST;?></td>
						<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TOTAL;?></td>
                    </tr>
					<?php
					}// end of $srDetail != 0 
					?>
<?php
} // end of if $srExp < 2 csv export
$sum = 0;
while ($sr->actDate < $sr->endDate) {
  $info = $sr->getNext();
 
  $last = sizeof($info) - 1;
  
  $pInfo = $sr->getProductCosts($srDetailPro); //get product cost results
  
  if ($srExp < 2) {
?>
                    <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
<?php
    switch ($srView) {
      case '3':
?>
                      <?php if($srDetail == 0) {?><td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td><?php }?>
					 
					  <?php if($srDetail != 0) {?> 
				<td class="dataTableContent" colspan="13"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
				<?php }?>
<?php
        break;
      case '4':
?>
                      <?php if($srDetail == 0) {?><td class="dataTableContent" align="right"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td><?php }?>
					 
					  <?php if($srDetail != 0) {?> 
				<td class="dataTableContent" colspan="13"><?php echo tep_date_long(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td>
				<?php }?>
<?php
        break;
      default;
?>
                     <?php if($srDetail == 0) {?> <td class="dataTableContent" align="right"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td><?php } ?>
					  
					  <?php if($srDetail != 0) {?> 
				<td class="dataTableContent" colspan="13"><?php echo tep_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . tep_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
<?php
		}
    }
				if($srDetail == 0) {?> 
				<td class="dataTableContent" align="right"><?php echo $info[0]['order']; ?></td>
				<td class="dataTableContent" align="right"><?php echo (isset($info[$last - 1]['totitem']) ? $info[$last - 1]['totitem'] : ''); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['labour_cost']); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['overhead_cost']); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['material_cost']); ?></td>	
				<td class="dataTableContent" align="right"><?php echo (isset($info[$last - 1]['totsum']) ? $currencies->format($info[$last - 1]['totsum']) : '');?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['shipping']);?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[0]['discount']);?></td>
					 <?php }?>
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
				<td class="dataTableContent"><b><?php echo ($manufac_name!="")?$manufac_name : "N/A";  ?></b></td>	
				<td class="dataTableContent"><b><?php echo $main_category_name;  ?></b></td>			
				<td class="dataTableContent"><b><?php echo $sub_category_name;  ?></b></td>				
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
  //Order Total eof
  
    for ($i = 0; $i < $last; $i++) {
      if ($srMax == 0 or $i < $srMax) {
        if ($srExp < 2) {

		//Order Total 
	 if($tmp_orderid != $info[$i]['orders_id'] && $tmp_orderid !=0) {  ?>

		<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
			<td class="dataTableContent">&nbsp;</td>
			<td class="dataTableContent"><?php echo TABLE_HEADING_ORDER_TOTAL;?></td>
			<td class="dataTableContent" colspan="8">&nbsp;</td>
			<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot);?></td>
			<td class="dataTableContent" align="right"><?php echo $currencies->format($gst);?></td>
			<td class="dataTableContent" align="right"><?php echo $currencies->format($total);?></td>
		</tr>
<?php
	$subtot = 0;
	$gst 	= 0;
	$total 	= 0;
	$rec_count = 0;
}
	$tmp_orderid = $info[$i]['orders_id'];
	$subtot 	+= $info[$i]['psum'];
	$gst 		+= $info[$i]['pgst'];
	$total 		+= $info[$i]['pgst_total'];
	$rec_count++;
  //Order Total eof
            ?>
                    <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
					<?php if($srDetail != 0){ ?>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['customer_number']; } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['customers_name']; } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['customers_company']; } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['orders_id']; } ?></td>
							<td class="dataTableContent"><?php if ($rec_count == 1) { echo $info[$i]['purchase_number']; } ?></td>
							
							<td class="dataTableContent"><?php echo $info[$i]['billing_name'].'<br>' .$info[$i]['billing_company'].'<br>' .$info[$i]['billing_street_address'].'<br>' .$info[$i]['billing_suburb'].'<br>' .$info[$i]['billing_city'].' '.$info[$i]['billing_state'].' '.$info[$i]['billing_postcode'].'<br>' .$info[$i]['billing_country']; ?></td>
							
							<td class="dataTableContent"><?php echo $info[$i]['delivery_name'].'<br>' .$info[$i]['delivery_company'].'<br>' .$info[$i]['delivery_street_address'].'<br>' .$info[$i]['delivery_suburb'].'<br>' .$info[$i]['delivery_city'].' '.$info[$i]['delivery_state'].' '.$info[$i]['delivery_postcode'].'<br>' .$info[$i]['delivery_country']; ?></td>
							
							<td class="dataTableContent" align="left"><a href="<?php echo tep_catalog_href_link("product_info.php?products_id=" . $info[$i]['pid']) ?>" target="_blank"><?php echo $info[$i]['pname']; ?></a>
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
?>                    </td>
					<!--<td class="dataTableContent" align="right"><?php //echo $info[$i]['pquant']; ?></td>-->
					<td class="dataTableContent" align="right"><?php echo $info[$i]['proquant']; ?></td>
					<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['price']); ?></td>
					
					<?php }?>
<?php
          if ($srDetail == 2) {?>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['pgst']); ?></td>
				<td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['pgst_total']); ?></td>
<?php
          } else { ?>
                                <td class="dataTableContent">&nbsp;</td>
                                <td class="dataTableContent">&nbsp;</td>
                                <td class="dataTableContent">&nbsp;</td>
<?php
          }
?>
                    
                  </tr>
				  <?php 
				 if($i == $last-1) {  ?>

		<tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
			<td class="dataTableContent">&nbsp;</td>
			<td class="dataTableContent"><?php echo TABLE_HEADING_ORDER_TOTAL;?></td>
			<td class="dataTableContent" colspan="8">&nbsp;</td>
			<td class="dataTableContent" align="right"><?php echo $currencies->format($subtot);?></td>
			<td class="dataTableContent" align="right"><?php echo $currencies->format($gst);?></td>
			<td class="dataTableContent" align="right"><?php echo $currencies->format($total);?></td>
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
        }
      }
    }
  }
}
if ($srExp < 2) {
?>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
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