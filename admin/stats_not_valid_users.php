<?php
/*
  $Id: stats_inactive_user.php,v 1.2 2004/05/02 15:00:00
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  Created by John Wood - www.z-is.net
  Modified by Steel Shadow - rebelstyle.com

*/

 require('includes/application_top.php');
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="4">
          <tr>
            <td valign="top" class="main">
<?php
  $go = (isset($_GET['go']) ? $_GET['go'] : '' );
  $id = (isset($_GET['id']) ? $_GET['id'] : '' );
$cust_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $id . "'");
$cust = tep_db_fetch_array($cust_query);

if ($go == ''){
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACCOUNT_CREATED; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_LOGON; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DELETE; ?></td>
                    </tr>
<?php
  $siu_query_raw = "select * from " . TABLE_CUSTOMERS_INFO . " ci, " . TABLE_CUSTOMERS . " c where c.customers_id = ci.customers_info_id and c.customers_validation = '0' order by c.customers_id";
  $siu_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $siu_query_raw, $siu_query_numrows );
  $siu_query = tep_db_query($siu_query_raw);
  while ($customers = tep_db_fetch_array($siu_query)) {

 ?>
      <tr class="dataTableRow"> 
        <td class="dataTableContent"><?php echo $customers['customers_id'];?></td>
        <td class="dataTableContent"><?php echo $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];?></td>
        <td class="dataTableContent"><?php echo '<a href="mailto:' . $customers['customers_email_address'] . '"><u>' . $customers['customers_email_address'] . '</u></a>' ; ?></td>
        <td class="dataTableContent"><?php echo tep_date_short($customers['customers_info_date_account_created']); ?></td>
        <td class="dataTableContent"><?php echo tep_date_short($customers['customers_info_date_of_last_logon']); ?></td>
        <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_NOT_VALID_USER, 'go=delete&id=' . $customers['customers_id'] . '&page=' . $_GET['page']) .'">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>'; ?></td>
      </tr>
<?php
  }
?>
<?php
            } elseif ($_GET['go'] == 'delete')
      {
              echo '<br>' . sprintf(SURE_TO_DELETE, $cust[customers_firstname] . ' ' . $cust[customers_lastname]) . '<br><br><a href="' . tep_href_link(FILENAME_STATS_NOT_VALID_USER,  'page=' . $_GET['page'] . '&go=deleteyes&id=' . $_GET['id']) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STATS_NOT_VALID_USER, 'page=' . $_GET['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><br><br>';
            } elseif ($_GET['go'] == 'deleteyes'){
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS . " where customers_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . $_GET['id'] . "'");
      echo '<br>' . sprintf(SIU_CUSTOMER_DELETED, $cust[customers_firstname] . ' ' . $cust[customers_lastname]) . '<br><br><br><a href="' . tep_href_link(FILENAME_STATS_NOT_VALID_USER, 'page=' . $_GET['page']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a><br><br>';
    }
        
?>
       </table></td>
      </tr>
    
  <?php if ($go == ''){?>   
      <tr>
        <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table-foot">
          <tr>
            <td class="smallText" valign="top"><?php echo $siu_split->display_count($siu_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
            <td class="smallText" align="right"><?php echo $siu_split->display_links($siu_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
          </tr>
       </table></td>
     </tr>
  <?php }?> 
  
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