<?php
/*
  $Id: stats_products_sales.php,v 1.1.1.1 2008/06/21 23:38:59 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false; 
$choose_products = isset($_POST['choose_products']) ? $_POST['choose_products'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
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
<script language="javascript"><!--
function ger_report() {
  document.products.action = '<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_STATS_PRODUCTS_SALES, tep_get_all_get_params(array('choose_products', 'page', 'action')) . 'action=order_preview')); ?>';
  document.products.submit();
}
--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<?php $padding = ($is_62 == true) ? 2 : 0; ?>
<table border="0" width="100%" cellspacing="<?php echo $padding; ?>" cellpadding="<?php echo $padding; ?>" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php 
    if ($is_62 == true) echo '<td width="' . BOX_WIDTH . '" valign="top"><table border="0" width="' . BOX_WIDTH . '" cellspacing="1" cellpadding="1" class="columnLeft">' . "\n";
    require(DIR_WS_INCLUDES . 'column_left.php'); 
    if ($is_62 == true) echo '</table></td>' . "\n";
    ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <?php
    echo tep_draw_form('products', FILENAME_STATS_PRODUCTS_SALES, tep_get_all_get_params(array('choose_products', 'page')));
    ?>
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="main" align="right"><?php echo ENTRY_TEXT_CHOOSE_PRODUCTS . tep_draw_input_field('choose_products', (isset($choose_products) ? $choose_products : '')); ?><?php echo tep_image_button('open_popup', ENTRY_TEXT_GET_PRODUCTS_ID, 'onclick="window.open(\'' . tep_href_link('treeview.php', 'script=' . urlencode('window.opener.document.products.choose_products.value = prod_value;'), $request_type) . '\', \'popuppage\', \'scrollbars=yes,resizable=yes,menubar=yes,width=400,height=600\');"') . '&nbsp;' . tep_image_submit('', ENTRY_TEXT_OK); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <?php
          if (tep_not_null(trim($choose_products))) {
            $pid_array = explode(',', $choose_products);
            $str = '';
            foreach ($pid_array as $value) {
              $str .= "'" . $value . "', ";
            }
            $products_list = " AND op.products_id IN (" . substr($str, 0, strlen($str) - 2) . ')';
          } else {
            $products_list = '';
          }
          switch ($action) {
            case 'month_preview':
              ?>
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SELECT; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DAY; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_QTY; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_TOTAL; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_RANGE; ?></td>
                  </tr>
                  <?php
                  $i_month = (int)$_GET['month'];
                  $i_year = (int)$_GET['year'];
                  $products_query_raw = "SELECT DAY(o.date_purchased) AS i_day, sum(op.products_quantity) AS products_quantity, sum(ot.value) AS total, min(op.final_price) AS min_price, max(op.final_price) AS max_price FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_PRODUCTS . " op WHERE o.orders_id = op.orders_id AND o.orders_id = ot.orders_id AND ot.class = 'ot_total' AND MONTH(o.date_purchased) = '" . $i_month . "' AND YEAR(o.date_purchased) = '" . $i_year . "'" . $products_list . " GROUP BY i_day ORDER BY i_day";
                  $products_query = tep_db_query($products_query_raw);
                  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
                  $products_query_numrows = tep_db_num_rows($products_query);
                  $products_query = tep_db_query($products_query_raw);
                  while ($products = tep_db_fetch_array($products_query)) {
                    ?>
                    <tr class="dataTableRow">
                      <td class="dataTableContent"><?php echo tep_draw_checkbox_field('day[]', $products['i_day']); ?></td>
                      <td class="dataTableContent" align="center"><?php echo $products['i_day']; ?></td>
                      <td class="dataTableContent" align="center"><?php echo $products['products_quantity']; ?></td>
                      <td class="dataTableContent" align="center"><?php echo $products['total']; ?></td>
                      <td class="dataTableContent" align="right"><?php echo number_format($products['min_price'], 2) . ' - ' . number_format($products['max_price'], 2); ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                </table></td>
              </tr>
              <?php
              break;
            case 'order_preview':
              ?>
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_QTY; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL; ?></td>
                  </tr>
                  <?php
                  $i_month = (int)$_GET['month'];
                  $i_year = (int)$_GET['year'];
                  $i_day = '';
                  if (is_array($_POST['day'])) {
                    foreach ($_POST['day'] as $value) {
                      $i_day .= "'" . $value . "', ";
                    }
                    $i_day = substr($i_day, 0, strlen($i_day) - 2);
                    $i_day = ' AND DAY(o.date_purchased) IN (' . $i_day . ')';
                  }
                  $products_query_raw = "SELECT o.orders_id, sum(op.products_quantity) AS products_quantity, ot.value AS total FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_PRODUCTS . " op WHERE o.orders_id = op.orders_id AND o.orders_id = ot.orders_id AND ot.class = 'ot_total' AND MONTH(o.date_purchased) = '" . $i_month . "' AND YEAR(o.date_purchased) = '" . $i_year . "'" . $products_list . $i_day . " GROUP BY orders_id ORDER BY orders_id";
                  $products_query = tep_db_query($products_query_raw);
                  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
                  $products_query_numrows = tep_db_num_rows($products_query);
                  $rows = 0;
                  $products_query = tep_db_query($products_query_raw);
                  while ($products = tep_db_fetch_array($products_query)) {
                    ?>
                    <tr class="dataTableRow">
                      <td class="dataTableContent"><?php echo $products['orders_id']; ?>.</td>
                      <td class="dataTableContent" align="center"><?php echo $products['products_quantity']; ?></td>
                      <td class="dataTableContent" align="right"><?php echo $products['total']; ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                </table></td>
              </tr>
              <?php
              break;
            case '';
            default:
              ?>
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MONTH; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_QTY; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRICE_RANGE; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_AVG; ?></td>
                  </tr>
                  <?php
                  $products_query_raw = "SELECT MONTHNAME(o.date_purchased) AS a_month, MONTH(o.date_purchased) AS i_month, YEAR(o.date_purchased) AS i_year, sum(op.products_quantity) AS products_quantity, sum(ot.value) AS total, min(op.final_price) AS min_price, max(op.final_price) AS max_price FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_PRODUCTS . " op WHERE o.orders_id = op.orders_id AND o.orders_id = ot.orders_id AND ot.class = 'ot_total'" . $products_list . " GROUP BY i_year, i_month ORDER BY i_year, i_month";
                  $products_query = tep_db_query($products_query_raw);
                  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
                  $products_query_numrows = tep_db_num_rows($products_query);
                  $rows = 0;
                  $products_query = tep_db_query($products_query_raw);
                  while ($products = tep_db_fetch_array($products_query)) {
                    $rows++;
                    if (strlen($rows) < 2) {
                      $rows = '0' . $rows;
                    }
                    ?>
                    <tr class="dataTableRow">
                      <td class="dataTableContent"><?php echo $rows; ?>.</td>
                      <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_SALES, 'action=month_preview&year=' . $products['i_year'] . '&month=' . $products['i_month'] . '&page=' . $_GET['page'], 'SSL') . '">' . $products['a_month'] . ', ' . $products['i_year'] . '</a>'; ?></td>
                      <td class="dataTableContent"><?php echo $products['products_quantity']; ?></td>
                      <td class="dataTableContent"><?php echo $products['total']; ?></td>
                      <td class="dataTableContent" align="center"><?php echo number_format($products['min_price'], 2) . ' - ' . number_format($products['max_price'], 2); ?></td>
                      <td class="dataTableContent" align="right"><?php echo number_format($products['total'] / $products['products_quantity'], 2); ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                </table></td>
              </tr>
              <?php
              break;
          } // end switch
          ?>
          </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_RESULTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
                <?php
                switch ($action) {
                  case 'month_preview':
                    ?>
                    <td class="smallText" align="right"><?php echo tep_image_button('', ENTRY_TEXT_GEN_REPORT, 'onclick="javascript:ger_report();"'); ?> <a href="<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_SALES, '', 'SSL')?>"><?php echo tep_image_button('', IMAGE_BACK); ?></a></td>
                    <?php
                    break;
                  case 'order_preview':
                    ?>
                    <td class="smallText" align="right"><a href="<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_SALES, tep_get_all_get_params(array('page', 'action')) . 'action=month_preview', 'SSL')?>"><?php echo tep_image_button('', IMAGE_BACK); ?></a></td>
                    <?php
                    break;
                }
                ?>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    </form>
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