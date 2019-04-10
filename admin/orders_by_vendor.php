<?php
/*
  $Id: orders_by_vendor.php,v 1.1 2008/06/22 22:50:52 datazen Exp $

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$vendor_query_raw = "select vendors_id as id, vendors_name as name from " . TABLE_VENDORS . " order by name";
$vendor_query = tep_db_query($vendor_query_raw);
if (isset($_GET['vID'])) {
  $vendors_id = $_GET['vID'];
} elseif ($_GET['vendors_id']) {
  $vendors_id = $_GET['vendors_id'];
} elseif ($_POST['vendors_id']) {
  $vendors_id = $_POST['vendors_id'];
} else {
  $vendors_id = '';
}
if ($by == 'date') {
  $by = 'date_purchased';
} elseif ($by == 'customer'){
  $by = 'customers_id';
} elseif ($by == 'customer'){
  $by = 'status';
} elseif ($by == 'sent'){
  $by == 'sent';
} else {
  $by = 'orders_id';
}
if (isset($_GET['line'])){
  $line = $_GET['line'];
} else {
  $line = 'desc';
}
if (isset($_GET['sent'])) {
  $sent = $_GET['sent'];
} else {
  $sent = '';
}
if (isset($_POST['status'])) {
  $status = $_POST['status'];
}
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
<script language="javascript">
    function LaunchEmailWindow(vID, oID, vOS ) {
        remotewin = window.open('<?php echo tep_href_link(FILENAME_VENDORS_EMAIL_SEND, 'vID=\'+vID+\'&oID=\'+oID+\'&vOS=\'+vOS+\'','SSL');?>', 'remotewin', 'width=700,height=600,scrollbars=yes,top=200,left=200');
        if (remotewin != null) {
            if (remotewin.opener == null) {
                remotewin.opener = self;
            }
        }
    }
</script>
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
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <?php
                  $vendors_array = array(array('id' => '0', 'text' => 'NONE'));
                  $vendors_query = tep_db_query("select vendors_id, vendors_name from " . TABLE_VENDORS . " order by vendors_name");
                  while ($vendors = tep_db_fetch_array($vendors_query)) {
                    $vendors_array[] = array('id' => $vendors['vendors_id'],
                                             'text' => $vendors['vendors_name']);
                  }
                  ?>
                  <td class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, 'vendors_id=' . $vendors_id, 'SSL') . '"><b>Click to reset form</a></b>';?></td>
                  <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_VENDORS, '', 'SSL') . '"><b>Go To Vendors List</a>';?></td>
                </tr>
                <tr>
                  <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif','1','5'); ?></td>
                <tr>
                  <td colspan="3"><?php echo tep_black_line(); ?></td>
                </tr>
                <tr>
                  <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif','1','5'); ?></td>
                </tr>
                <tr>
                  <td class="main" align="left"><?php echo tep_draw_form('vendors_report', FILENAME_ORDERS_VENDORS, '', 'post', '', 'SSL') . TABLE_HEADING_VENDOR_CHOOSE . ' ' . tep_draw_pull_down_menu('vendors_id', $vendors_array,'','onChange="this.form.submit();"') . tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);?></form></td>
                  <td class="main" align="left"><?php echo 'Filter by email sent: <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, 'vendors_id=' . $vendors_id . '&line=' . $line . '&sent=yes', 'SSL') . '"><b>YES</a></b>&nbsp; <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, 'vendors_id=' . $vendors_id . '&line=' . $line . '&sent=no', 'SSL') . '"><b>NO</a></b>'; ?></td>
                  <?php 
                  if ($line == 'asc') {
                    if(isset($status)) {  
                      ?>
                      <td class="main" align="right"><?php echo 'Change to <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, 'vendors_id=' . $vendors_id . '&line=desc' . '&sent=' . $sent . '&status=' . $status, 'SSL') . '"><b>DESCENDING</a></b> order'; ?></td>
                      <?php
                    } else {      
                      ?>
                      <td class="main" align="right"><?php echo 'Change to <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, 'vendors_id=' . $vendors_id . '&line=desc' . '&sent=' . $sent, 'SSL') . '"><b>DESCENDING</a></b> order'; ?></td>
                      <?php  
                    }  
                  } elseif (!isset($status)) {
                    ?>
                    <td class="main" align="right"><?php echo 'Change to <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, 'vendors_id=' . $vendors_id . '&line=asc' . '&sent=' . $sent, 'SSL') . '"><b>ASCENDING</a></b> order'; ?></td>
                    <?php  
                  } else {     
                    ?>
                    <td class="main" align="right"><?php echo 'Change to <a href="' . tep_href_link(FILENAME_ORDERS_VENDORS, 'vendors_id=' . $vendors_id . '&line=asc' . '&sent=' . $sent . '&status=' . $status, 'SSL') . '"><b>ASCENDING</a></b> order'; ?></td>
                    <?php 
                  }  
                  $orders_statuses = array();
                  $orders_status_array = array();
                  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
                  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
                    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                                               'text' => $orders_status['orders_status_name']);
                    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
                  }
                  ?>
                  <td class="main" align="right"><?php echo tep_draw_form('status_report', FILENAME_ORDERS_VENDORS, 'vendors_id=' . $vendors_id, 'post', '', 'SSL') . HEADING_TITLE_STATUS . ' '; echo tep_draw_pull_down_menu('status', $orders_statuses, '','onChange="this.form.submit()";');?></form></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_VENDOR; ?></td>
                    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_ORDER_ID; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_SENT; ?>&nbsp;</td>
                  </tr>
                  <?php
                  $vend_query_raw = "select vendors_name as name from " . TABLE_VENDORS . " where vendors_id = '" . $vendors_id . "'";
                  $vend_query = tep_db_query($vend_query_raw);
                  $vendors = tep_db_fetch_array($vend_query); ?>
                  <tr class="dataTableRow">
                    <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_VENDORS, '&vendors_id=' . $vendors_id . '&action=edit') . '" TARGET="_blank"><b>' . $vendors['name'] . '</a></b>'; ?></td>
                    <td class="dataTableContent"><?php echo ''; ?></td>
                    <td class="dataTableContent"><?php echo ''; ?></td>
                    <td class="dataTableContent"><?php echo ''; ?></td>
                    <td class="dataTableContent"><?php echo ''; ?></td>
                    <td class="dataTableContent"><?php echo ''; ?></td>
                    <td class="dataTableContent" align="right">Click To<br>Send Email</td>
                  </tr>
                  <?php
                  $index1 = 0;
                  if ($sent == 'yes'){
                    $vendors_orders_data_query = tep_db_query("select distinct orders_id, vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where vendors_id='" . $vendors_id . "' and vendor_order_sent='yes' group by orders_id " . $line . "");
                  } elseif ($sent == 'no') {
                    $vendors_orders_data_query = tep_db_query("select distinct orders_id, vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where vendors_id='" . $vendors_id . "' and vendor_order_sent='no' group by orders_id " . $line . "");
                  } else {
                    $vendors_orders_data_query = tep_db_query("select distinct orders_id, vendor_order_sent from " . TABLE_ORDERS_SHIPPING . " where vendors_id='" . $vendors_id . "' group by orders_id " . $line . "");
                  }
                  while ($vendors_orders_data = tep_db_fetch_array($vendors_orders_data_query))  {
                    $index2 = 0 ;
                    $vendors_products_data_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION ." where products_id='" . $vendors_orders_data['v_products_id'] . "' and language_id = '" . $languages_id . "'");
                    $index3 = 0;
                    if (isset($status)) {
                      $orders_query = tep_db_query("select distinct o.customers_id, o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = '" . $status . "' and o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and ot.class = 'ot_total' and o.orders_id =  '" . $vendors_orders_data['orders_id'] . "' order by o." . $by . " ASC");
                    } else {
                      $orders_query = tep_db_query("select distinct o.customers_id, o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $languages_id . "' and ot.class = 'ot_total' and o.orders_id =  '" . $vendors_orders_data['orders_id'] . "' order by o." . $by . " ASC");
                    }
                    while ($vendors_orders = tep_db_fetch_array($orders_query)) {
                      $raw_date_purchased = $vendors_orders['date_purchased'];
                      if (tep_not_null($raw_date_purchased)) {
                        list($date_2, $time_2) = explode(' ', $raw_date_purchased);
                        list($year, $month, $day) = explode('-', $date_2);
                        $date_purchased = ((strlen($day) == 1) ? '0' . $day : $day) . '/' . ((strlen($month) == 1) ? '0' . $month : $month) . '/' . $year;
                      }
                      ?>
                      <tr class="dataTableRow">
                        <td class="dataTableContent" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $vendors_orders_data['orders_id'] . '&action=edit','SSL') . '" target="_blank"><b>View this order</b></a>'; ?></td>
                        <td class="dataTableContent" align="left"><?php echo $vendors_orders['orders_id']; ?></td>
                        <?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=new_product&pID=' . $vendors_orders_data['v_products_id'],'SSL') . '" target="_blank"><b>' . $vendors_products_data['products_name'] . '</a>'; ?>
                        <td class="dataTableContent"><?php echo ' from <a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $vendors_orders['customers_id'] . '&action=edit','SSL') . '" target="_blank"><b>' . $vendors_orders['customers_name'] . '</b></a>'; ?></td>
                        <td class="dataTableContent" align="left"><?php echo strip_tags($vendors_orders['order_total']); ?></td>
                        <td class="dataTableContent" align="left"><?php echo $date_purchased; ?></td>
                        <td class="dataTableContent" align="left"><?php echo $vendors_orders['orders_status_name']; ?></td>
                        <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_VENDORS_EMAIL_SEND, 'vID=' . $vendors_id . '&oID=' . $vendors_orders_data['orders_id'] . '&vOS=' . $vendors_orders_data['vendor_order_sent'],'SSL') . '" onClick="LaunchEmailWindow(' . $vendors_id . ',' . $vendors_orders_data['orders_id'] . ',\'' . $vendors_orders_data['vendor_order_sent'] . '\'); return false;"><b>' . $vendors_orders_data['vendor_order_sent'] . '</b></a>'; ?></td>
                      </tr>
                      <?php
                      $index3++;
                    }
                    $index2++;
                    $index1++;
                  }
                  ?>
                </table></td>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>