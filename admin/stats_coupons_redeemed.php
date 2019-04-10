<?php
/*
  $Id: stats_coupons_redeemed_report.php, v 1.0.0.0  08/04/06 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded

  Released under the GNU General Public License

  Written by:  Scott Logsdon
   Last Revision 08/04/06
   Latest Revision 1.0.0.0
 
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  // start csv - bounce csv string back as file
  if (isset($_POST['csv'])) {
  if ($_POST['saveas']) {  // rebound posted csv as save file
      $savename= $_POST['saveas'] . ".csv";
      }
      else $savename='unknown.csv';
  $csv_string = '';
  if ($_POST['csv']) $csv_string=$_POST['csv'];
  if (strlen($csv_string)>0){
    header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
    header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-Type: Application/octet-stream");
    header("Content-Disposition: attachment; filename=$savename");
    echo $csv_string;
  }
  else echo "CSV string empty";
  exit;
  };
  //end csv

if (isset($_GET['page']) && ($_GET['page'] >= 1)) {
  $month = $_SESSION['month'];
}else{
  $month=substr(date("Y-m-d"),5,2);
  if ($_POST['selected_month']) {
    switch ($_POST['selected_month']) {
      case 'January':
        $month='01';
        break;
      case 'February':
        $month='02';
        break;
      case 'March':
        $month='03';
        break;
      case 'April':
        $month='04';
        break;
      case 'May':
        $month='05';
        break;
      case 'June':
        $month='06';
        break;
      case 'July':
        $month='07';
        break;
      case 'August':
        $month='08';
        break;
      case 'September':
        $month='09';
        break;
      case 'October':
        $month='10';
        break;
      case 'November':
        $month='11';
        break;
      case 'December':
        $month='12';
        break;
    }
  }
$_SESSION['month'] = $month;
}
$selected_year = date("Y");

//echo 'month[' . $month . ']<br>';
//echo 'year[' . $selected_year . ']<br>';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();"> 
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>  
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              <form method="post" action="<?php echo tep_href_link(FILENAME_STATS_COUPONS_REDEEMED, '', 'SSL'); ?>" name="couponsredeemed">
              <td class=main align="right"><?php echo DISPLAY_ANOTHER_REPORT_DATE; ?>&nbsp;
              <select SIZE="1" NAME="selected_month" onchange="this.form.submit();">
                <?php
                echo ($month=='01') ? '<option selected="selected">January</option>' : '<option>January</option>'; 
                echo ($month=='02') ? '<option selected="selected">February</option>' : '<option>February</option>';
                echo ($month=='03') ? '<option selected="selected">March</option>' : '<option>March</option>';
                echo ($month=='04') ? '<option selected="selected">April</option>' : '<option>April</option>';
                echo ($month=='05') ? '<option selected="selected">May</option>' : '<option>May</option>';
                echo ($month=='06') ? '<option selected="selected">June</option>' : '<option>June</option>';
                echo ($month=='07') ? '<option selected="selected">July</option>' : '<option>July</option>';
                echo ($month=='08') ? '<option selected="selected">August</option>' : '<option>August</option>';
                echo ($month=='09') ? '<option selected="selected">September</option>' : '<option>September</option>';
                echo ($month=='10') ? '<option selected="selected">October</option>' : '<option>October</option>';
                echo ($month=='11') ? '<option selected="selected">November</option>' : '<option>November</option>';
                echo ($month=='12') ? '<option selected="selected">December</option>' : '<option>December</option>';
                ?>
              </select>
            </td>
          </tr>
        </table></form></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <?php
                $csv_accum .= "";
                ?>
                <td class="dataTableHeadingContent" width="10%"><?php echo TABLE_HEADING_NO; ?></td>
                <td class="dataTableHeadingContent"><?php mirror_out(TABLE_HEADING_COUPON_CODE); ?></td>
                <td class="dataTableHeadingContent"><?php mirror_out(TABLE_HEADING_COUPON_NAME); ?></td>
                <td class="dataTableHeadingContent" align="right"><?php mirror_out(TABLE_HEADING_NUMBER_USES); ?></td>
                <td class="dataTableHeadingContent" align="right"><?php mirror_out(TABLE_HEADING_AMT_REDEEMED); ?></td>
              </tr>
              <?php
              // new line for CSV
              $csv_accum .= "\n";
              //

             if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

               $coupons_query_raw = "select cr.coupon_id as coupon_id, cr.redeem_date as redeem_date, cr.customer_id as customer_id, cr.order_id as orders_id from coupon_redeem_track cr where MONTH(cr.redeem_date) = '" . (int)$month  . "' and YEAR(cr.redeem_date) = '" . (int)$selected_year  . "' group by cr.coupon_id order by cr.redeem_date DESC";

               $coupons_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $coupons_query_raw, $coupons_query_numrows);
               $coupons_query = tep_db_query($coupons_query_raw);
               while ($coupons = tep_db_fetch_array($coupons_query)) {
                 $rows++;
 
                 if (strlen($rows) < 2) {
                   $rows = '0' . $rows;
                 }
                 
                   $code_query_raw = "select cp.coupon_code as coupon_code, cp.coupon_amount as coupon_amount from " . TABLE_COUPONS . " cp where cp.coupon_id = '" . (int)$coupons['coupon_id'] . "'";
                   $result = tep_db_query($code_query_raw);
                   $code = tep_db_fetch_array($result); 

                 $name_query_raw = "select cd.coupon_name as coupon_name from " . TABLE_COUPONS_DESCRIPTION . " cd where cd.coupon_id = '" . (int)$coupons['coupon_id'] . "'";
                 $result = tep_db_query($name_query_raw);
                 $name = tep_db_fetch_array($result);

                 $uses_query_raw = "select count(*) as total_uses from coupon_redeem_track WHERE coupon_id = '" . (int)$coupons['coupon_id'] . "'";
                 $result = tep_db_query($uses_query_raw);
                 $uses = tep_db_fetch_array($result);
                
                 ?>
                 <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_COUPON_ADMIN, 'cid=' . (int)$coupons['coupon_id'], 'NONSSL'); ?>'">
                   <td class="dataTableContent"><?php echo $rows; ?>.</td>
                   <td class="dataTableContent"><?php mirror_out($code['coupon_code']) ?></td>
                   <td class="dataTableContent"><?php mirror_out($name['coupon_name']) ?></td>
                   <td class="dataTableContent" align="right"><?php mirror_out(number_format($uses['total_uses'],0)) ?></td>
                   <td class="dataTableContent" align="right"><?php mirror_out(number_format($code['coupon_amount']*$uses['total_uses'],2)); ?></td>
                 </tr>
                 <?php
                  // new line for CSV
                 $csv_accum .= "\n";
               }
               ?>
               </table></td>
             </tr>
             <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td></tr>
             <?php
             $coupons_query_raw = "select cr.coupon_id as coupon_id, cr.redeem_date as redeem_date, cr.customer_id as customer_id, cr.order_id as orders_id from coupon_redeem_track cr where MONTH(cr.redeem_date) = '" . (int)$month  . "' and YEAR(cr.redeem_date) = '" . (int)$selected_year  . "' group by cr.coupon_id order by cr.redeem_date DESC";
             $coupons_query_numrows = tep_db_query($coupons_query_raw);
             $coupons_query_numrows = tep_db_num_rows($coupons_query_numrows);
             $rows = 0;
             $coupons_query = tep_db_query($coupons_query_raw);
             ?>
             <tr>
               <td class="smallText" colspan="4"><form action="<?php echo $PHP_SELF; ?>" method=post>
                 <input type='hidden' name='csv' value='<?php echo $csv_accum; ?>'>
                 <input type="hidden" name="saveas" value="coupons_redeemed_report_<?php echo date('YmdHi'); ?>">
                 <input type="submit" value="<?php echo TEXT_BUTTON_REPORT_SAVE ;?>"></form>
               </td>
             </tr>
             </table>
             <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
             <tr>
               <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                 <tr>
                   <td class="smallText" valign="top"><?php echo $coupons_split->display_count($coupons_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUPONS); ?></td>
                   <td class="smallText" align="right"><?php echo $coupons_split->display_links($coupons_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                 </tr>
               </table></td>
             </tr>
           </table></td>
         </tr>
       </table></td>
       <!-- body_text_eof //-->
     </tr>
   </table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 

function mirror_out ($field) {
  global $csv_accum;
  echo $field;
  $field = strip_tags($field);
  $field = ereg_replace (",","",$field);
  if ($csv_accum=='') $csv_accum=$field; 
  else 
  {if (strrpos($csv_accum,chr(10)) == (strlen($csv_accum)-1)) $csv_accum .= $field;
    else $csv_accum .= "," . $field; };
  return;
}
?>