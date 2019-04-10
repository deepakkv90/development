ge<?php
/*
  $Id: fss_view_orders.php,v 1.0.0.0 2008/06/19 23:39:49 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
// RCI top
$cre_RCI->get('global', 'top');
$cre_RCI->get('fssvieworders', 'top'); 
$questions_id = $_GET['questions_id'];
$filter = urldecode(isset($_GET['filter']) ? $_GET['filter'] : '');
$period = $_GET['period'];
$period_from1 = isset($_GET['period_from']) ? urlencode($_GET['period_from']) : date('m-d-Y', mktime(0, 0, 0, date('m'), date('d')-date('w'), date('Y')));
$period_array = explode('-', $period_from1);
$period_from = $period_array[2] . '-' . $period_array[0] . '-' . $period_array[1];
$period_to1 = isset($_GET['period_to']) ? urlencode($_GET['period_to']) : date('m-d-Y');
$period_array = explode('-', $period_to1);
$period_to = $period_array[2] . '-' . $period_array[0] . '-' . $period_array[1];
$date = array('start' => $period_from,
              'end' => $period_to);
if ( !isset($_GET['page']) ) {
  $_GET['page'] = 1;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
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
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0"> 
      <tr>
        <td align="center"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . (tep_not_null($filter) ? ' (' . $filter . ')' : ' (All)'); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="smallText" align="right"><a href="<?php echo tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('filter', 'orders_id', 'order', 'listing', 'page')), 'SSL'); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <?php
                switch (isset($_GET['listing']) ? $_GET['listing'] : '') {
                  case "name":
                    $order = " order by o.customers_name";
                    break;
                  case "date":
                    $order  = " order by o.date_purchased";
                    break;
                  case 'status':
                    $order = " order by o.orders_status";
                    break;
                  default:
                   $order = " order by o.orders_id";
                }
                if ( isset($_GET['order']) && $_GET['order'] == 'desc' ) {
                  $order .= ' desc';
                }
                ?>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_CUSTOMERS_NAME; ?><a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('listing', 'order', 'orders_id')) . "listing=name"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_NAME . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('listing', 'order', 'orders_id')) . "listing=name&order=desc"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_NAME . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_DATE_PURCHASED; ?><a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('listing', 'order', 'orders_id')) . "listing=date"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_DATE_PURCHASED . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('listing', 'order', 'orders_id')) . "listing=date&order=desc"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_DATE_PURCHASED . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_STATUS; ?><a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('listing', 'order', 'orders_id')) . "listing=status"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_STATUS . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('listing', 'order', 'orders_id')) . "listing=status&order=desc"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_STATUS . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" align="right" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '11', '12'); ?><?php echo TEXT_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $orders_query_raw = "SELECT o.orders_id, o.customers_name, o.date_purchased, os.orders_status_name, ot.text as order_total 
                                     FROM " . TABLE_FSS_FORMS_POSTS . " ffp, 
                                          " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc, 
                                          " . TABLE_ORDERS . " o, 
                                          " . TABLE_ORDERS_STATUS . " os, 
                                          " . TABLE_ORDERS_TOTAL . " ot 
                                   WHERE ffp.forms_posts_id = ffpc.forms_posts_id 
                                     AND o.orders_id = ffp.orders_id 
                                     AND o.orders_status = os.orders_status_id 
                                     AND os.language_id = '" . $languages_id . "' 
                                     AND o.orders_id = ot.orders_id 
                                     AND ot.class = 'ot_total' 
                                     AND ffpc.questions_id = '" . $questions_id . "'";
              if ( tep_not_null($filter) ) {
                $orders_query_raw .= " AND (ffpc.forms_fields_value LIKE '" . $filter . ",%' OR ffpc.forms_fields_value LIKE '%, " . $filter . ",%' OR ffpc.forms_fields_value LIKE '%, " . $filter . "')";
              }
              if ( $date['start'] != '' && $date['end'] != '' ) {
                $orders_query_raw .= " AND ffp.posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'";
              }
              $orders_query_raw .= $order;
              $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
              $orders_query = tep_db_query($orders_query_raw);
              while ($orders = tep_db_fetch_array($orders_query)) {
                if ((!isset($_GET['orders_id']) || (isset($_GET['orders_id']) && ($_GET['orders_id'] == $orders['orders_id']))) && !isset($oInfo)) {
                  $orders_query_temp = tep_db_query("SELECT o.orders_id, o.customers_name, o.date_purchased, os.orders_status_name FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE o.orders_id = '" . $orders['orders_id'] . "' AND o.orders_status = os.orders_status_id AND os.language_id = '" . $languages_id . "'");
                  $orders_array = tep_db_fetch_array($orders_query_temp);
                  $oInfo = new objectInfo($orders_array);
                }
                if ( (isset($oInfo) && is_object($oInfo) && $orders['orders_id'] == $oInfo->orders_id) ) {
                  echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                } else {
                  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('orders_id')) . 'orders_id=' . $orders['orders_id']) . '\'">' . "\n";
                }
                ?>
                <td class="dataTableContent"><?php echo $orders['customers_name']; ?></td>
                <td class="dataTableContent"><?php echo strip_tags($orders['order_total']); ?></td>
                <td class="dataTableContent"><?php echo $orders['date_purchased']; ?></td>
                <td class="dataTableContent"><?php echo $orders['orders_status_name']; ?></td>
                <td class="dataTableContent" align="right">
                  <?php 
                  if ((isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id) || (isset($oInfo->fID) && $orders['orders_id'] == $oInfo->fID))) { 
                    echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
                  } else { 
                    echo '<a href="' . tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('orders_id')) . 'orders_id=' . $orders['orders_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; 
                  } 
                  ?>
                </td>
                </tr>
                <?php
              }
              ?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, isset($_GET['page']) ? $_GET['page'] : '', TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, isset($_GET['page']) ? $_GET['page'] : '', tep_get_all_get_params(array('page', 'info', 'x', 'y', 'orders_id'))); ?></td>
                  </tr>
                </table></td>
               </tr>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>              
                    <?php
                    // RCI orders view listing bottom
                    echo $cre_RCI->get('fssvieworders', 'listingbottom');
                    ?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <?php
            $heading = array();
            $contents = array();
            if($oInfo->orders_id!='') {
              switch ($action) {
                default:
                  if (isset($oInfo) && is_object($oInfo)) {
                    $heading[] = array('align'=>'left', 'text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'<b>' . sprintf(TEXT_INFORBOX_ORDERS_HEADING, $oInfo->orders_id). '</b>');
                      $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, 'oID=' . $oInfo->orders_id . '&action=edit') . '" target="_blank">' . tep_image_button('button_details.gif', IMAGE_DETAILS) . '</a>');
                  }
                  break;
              }
              if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                echo '<td width="25%" valign="top">' . "\n";
                $box = new box;
                echo $box->infoBox($heading, $contents);
                echo '</td>' . "\n";
              }
            }
            ?>
          </tr>
        </table></td>
      </tr>
      <?php
      // RCI fss view bottom
      $cre_RCI->get('fssvieworders', 'bottom'); 
      $cre_RCI->get('global', 'bottom');                                        
      ?>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>