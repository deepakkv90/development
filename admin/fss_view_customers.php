<?php
/*
  $Id: fss_view_customers.php,v 1.0.0.0 2008/06/19 23:39:49 Eversun Exp $

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
$cre_RCI->get('fssviewcustomers', 'top'); 
$questions_id = $_GET['questions_id'];
$filter = urldecode($_GET['filter']);
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
            <td class="smallText" align="right"><a href="<?php echo tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('filter', 'customers_id', 'order', 'listing', 'page')), 'SSL'); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a></td>
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
                  case "firstname":
                    $order = " order by c.customers_firstname";
                    break;  
                  case "lastname":
                    $order = " order by c.customers_lastname";
                    break;
                  case "email":
                    $order = " order by c.customers_email_address";
                    break;
                  case "phone":
                    $order = " order by ab.entry_telephone";
                    break;
                  default:
                    $order = " order by c.customers_id";
                }
                if ( isset($_GET['order']) && $_GET['order'] == 'desc' ) {
                  $order .= ' desc';
                }
                ?>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_CUSTOMERS_FIRSTNAME; ?><a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('listing', 'order', 'customers_id')) . "listing=firstname"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_FIRSTNAME . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('listing', 'order', 'customers_id')) . "listing=firstname&order=desc"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_FIRSTNAME . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_CUSTOMERS_LASTNAME; ?><a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('listing', 'order', 'customers_id')) . "listing=lastname"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_LASTNAME . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('listing', 'order', 'customers_id')) . "listing=lastname&order=desc"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_LASTNAME . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_CUSTOMERS_STATE; ?></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_CUSTOMERS_COUNTRY; ?></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_CUSTOMERS_EMAIL; ?><a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('listing', 'order', 'customers_id')) . "listing=email"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_EMAIL . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('listing', 'order', 'customers_id')) . "listing=email&order=desc"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_EMAIL . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_CUSTOMERS_PHONE; ?><a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('listing', 'order', 'customers_id')) . "listing=phone"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_PHONE . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('listing', 'order', 'customers_id')) . "listing=phone&order=desc"); ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_CUSTOMERS_PHONE . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" align="right" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '11', '12'); ?><?php echo TEXT_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $customers_query_raw = "SELECT distinct ffp.customers_id FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc WHERE ffp.forms_posts_id = ffpc.forms_posts_id AND ffpc.questions_id = '" . $questions_id . "'";
              if ( tep_not_null($filter) ) {
                $customers_query_raw .= " AND (ffpc.forms_fields_value LIKE '" . $filter . ",%' OR ffpc.forms_fields_value LIKE '%, " . $filter . ",%' OR ffpc.forms_fields_value LIKE '%, " . $filter . "')";
              }
              if ( $date['start'] != '' && $date['end'] != '' ) {
                $customers_query_raw .= " AND ffp.posts_date between '" . $date['start'] . "' AND '" . $date['end'] . "'";
              }  
              $customers_query = tep_db_query($customers_query_raw);
              $customers_ids = "'0', ";
              while ($customers = tep_db_fetch_array($customers_query)) {
                $customers_ids .= $customers['customers_id']. ', ';
              }
              $customers_ids = substr($customers_ids, 0, strlen($customers_ids) - 2);
              $customers_query_raw = "SELECT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, ab.entry_telephone as customers_telephone, ab.entry_state, ab.entry_country_id, ab.entry_zone_id FROM " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab WHERE c.customers_id in (" . $customers_ids . ") AND c.customers_default_address_id = ab.address_book_id";
              $customers_query_raw .= $order;  
              $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
              $customers_query = tep_db_query($customers_query_raw);
              while ($customers = tep_db_fetch_array($customers_query)) {
                if ((!isset($_GET['customers_id']) || (isset($_GET['customers_id']) && ($_GET['customers_id'] == $customers['customers_id']))) && !isset($cInfo)) {
                  $customers_query_temp = tep_db_query("SELECT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, ab.entry_telephone as customers_telephone, ab.entry_state, ab.entry_country_id, ab.entry_zone_id FROM " . TABLE_CUSTOMERS . " c, " . TABLE_ADDRESS_BOOK . " ab WHERE c.customers_id = '" . $customers['customers_id'] . "' AND c.customers_default_address_id = ab.address_book_id");
                  $customers_array = tep_db_fetch_array($customers_query_temp);
                  $cInfo = new objectInfo($customers_array);
                }
                if ( (isset($cInfo) && is_object($cInfo) && $customers['customers_id'] == $cInfo->customers_id) ) {
                  echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                } else {
                  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('customers_id')) . 'customers_id=' . $customers['customers_id']) . '\'">' . "\n";
                }
                ?>
                <td class="dataTableContent"><?php echo $customers['customers_firstname']; ?></td>
                <td class="dataTableContent"><?php echo $customers['customers_lastname']; ?></td>
                <td class="dataTableContent"><?php echo tep_get_zone_name($customers['entry_country_id'], $customers['entry_zone_id'], $customers['entry_state']); ?></td>
                <td class="dataTableContent"><?php echo tep_get_country_name($customers['entry_country_id']); ?></td>
                <td class="dataTableContent"><?php echo $customers['customers_email_address']; ?></td>
                <td class="dataTableContent"><?php echo $customers['customers_telephone']; ?></td>
                <td class="dataTableContent" align="right">
                  <?php 
                  if ((isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id) || (isset($cInfo->fID) && $customers['customers_id'] == $cInfo->fID))) { 
                    echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
                  } else { 
                    echo '<a href="' . tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('customers_id')) . 'customers_id=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; 
                  } 
                  ?>&nbsp;
                </td>
                </tr>
                <?php
              }
              ?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, isset($_GET['page']) ? $_GET['page'] : '', TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, isset($_GET['page']) ? $_GET['page'] : '', tep_get_all_get_params(array('page', 'info', 'x', 'y', 'customers_id'))); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>              
                    <?php
                    // RCI listing bottom
                    echo $cre_RCI->get('fssviewcustomers', 'listingbottom');
                    ?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <?php
            $heading = array();
            $contents = array();
            if($cInfo->customers_id!='') {
              switch ($action) {
                default:
                  if (isset($cInfo) && is_object($cInfo)) {
                    $heading[] = array('align'=>'left', 'text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'<b>' . sprintf(TEXT_INFORBOX_CUSTOMERS_HEADING, $cInfo->customers_firstname). '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $cInfo->customers_id . '&action=edit') . '" target="_blank">' . tep_image_button('button_details.gif', IMAGE_DETAILS) . '</a>');
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
      // RCI bottom
      $cre_RCI->get('fssviewcustomers', 'bottom'); 
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