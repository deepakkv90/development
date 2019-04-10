<?php
/*
  $Id: orderlist.php,v 1.112 2003/06/29 22:50:52 vj Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    if  ($action == 'download') {
      ob_clean();

      // Output as CSV-file
      $filename = 'orderlist' . date('Ymd-His');  

      header("Content-Transfer-Encoding: ascii");
      header("Content-Disposition: attachment; filename=$filename.csv");
      header("Content-Type: text/comma-separated-values");

      echo '"' . TABLE_HEADING_ORDER_ID . '","' . TABLE_HEADING_PRODUCTS_MODEL . '","' . TABLE_HEADING_PRODUCTS_NAME . '","' . TABLE_HEADING_PRICE . '","' . TABLE_HEADING_QUANTITY . '","' . TABLE_HEADING_NOTES . '","' . TABLE_HEADING_CHK . '"' . "\n";

      if (isset($_GET['status']) && tep_not_null($_GET['status'])) {
  $status = tep_db_prepare_input($_GET['status']);
  $orders_query_raw = "select o.orders_id, op.products_model, op.products_name, op.products_price, op.products_quantity from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_PRODUCTS . " op on op.orders_id = o.orders_id where o.orders_status = '" . (int)$status . "' order by o.orders_id DESC";
      } else {
  $orders_query_raw = "select o.orders_id, op.products_model, op.products_name, op.products_price, op.products_quantity from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_PRODUCTS . " op on op.orders_id = o.orders_id order by o.orders_id DESC";
      }

      $orders_query = tep_db_query($orders_query_raw);

      while ($orders = tep_db_fetch_array($orders_query)) {
  echo '"' . $orders['orders_id'] . '","' . $orders['products_model'] . '","' . $orders['products_name'] . '","' . $orders['products_price'] . '","' . $orders['products_quantity'] . '"," "," "' . "\n"; 
      }

      exit();
    }
  }

  include(DIR_WS_CLASSES . 'order.php');
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
<?php
  if (($action != 'print')) {
?>
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
    <?php
  }
  ?>
  <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <?php
  if (($action != 'print')) {
    ?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('status', FILENAME_ORDERLIST, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), (isset($_GET['status']) && (int)$_GET['status'] > 0 ? (int)$_GET['status'] : ''), 'onChange="this.form.submit();"'); ?></td>
              </form></tr>            
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" align="center"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER_ID; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRODUCTS_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_QUANTITY; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_NOTES; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CHK; ?></td>
              </tr>
<?php
    if (isset($_GET['status']) && tep_not_null($_GET['status'])) {
      $status = tep_db_prepare_input($_GET['status']);
      $orders_query_raw = "select o.orders_id, op.products_model, op.products_name, op.products_price, op.products_quantity from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_PRODUCTS . " op on op.orders_id = o.orders_id where o.orders_status = '" . (int)$status . "' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.orders_id, op.products_model, op.products_name, op.products_price, op.products_quantity from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_PRODUCTS . " op on op.orders_id = o.orders_id order by o.orders_id DESC";
    }

    $orders_query = tep_db_query($orders_query_raw);

    while ($orders = tep_db_fetch_array($orders_query)) {
?>
              <tr class="dataTableRow">
                <td class="dataTableContent" align="center"><?php echo $orders['orders_id']; ?></td>
                <td class="dataTableContent"><?php echo $orders['products_model']; ?></td>
                <td class="dataTableContent"><?php echo $orders['products_name']; ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format($orders['products_price']); ?></td>
                <td class="dataTableContent" align="center"><?php echo $orders['products_quantity']; ?></td>
    <td class="dataTableContent">&nbsp;</td>
                <td class="dataTableContent">&nbsp;</td>
              </tr>
<?php
    }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
<?php
  if (($action != 'print')) {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
      <td class="smallText" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERLIST, tep_get_all_get_params(array('action')) . 'action=download') . '">'. tep_image_button('button_download.gif',TEXT_DOWNLOAD) .'</a>'; ?>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_ORDERLIST, tep_get_all_get_params(array('action')) . 'action=print') . '">'.tep_image_button('button_print_page.gif',TEXT_PRINT).'</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<?php
  if (($action != 'print')) {
?>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<?php
  }
?>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
