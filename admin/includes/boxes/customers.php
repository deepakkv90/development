<?php
/*
  $Id: customers.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- customers //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_CUSTOMERS,
                   'link'  => tep_href_link(FILENAME_ORDERS, 'selected_box=customers', 'SSL'));
if ($_SESSION['selected_box'] == 'customers' || MENU_DHTML == 'True') {
 // RCO start
  if ($cre_RCO->get('customers', 'columnleft') !== true) {   
    //RCI to include links 
    $returned_rci_orders_top = $cre_RCI->get('orders', 'boxestop');
    $returned_rci_orders_bottom = $cre_RCI->get('orders', 'boxesbottom');
    $returned_rci_customers_top = $cre_RCI->get('customers', 'boxestop');
    $returned_rci_customers_bottom = $cre_RCI->get('customers', 'boxesbottom');
    $returned_rci_customer_returns_top = $cre_RCI->get('customer_returns', 'boxestop');
    $returned_rci_customer_returns_bottom = $cre_RCI->get('customer_returns', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_orders_top .
                                   tep_admin_files_boxes(FILENAME_ORDERS, BOX_CUSTOMERS_ORDERS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_CREATE_ORDER, BOX_MANUAL_ORDER_CREATE_ORDER, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_CREATE_ORDERS_ADMIN, BOX_CREATE_ORDERS_ADMIN, 'SSL','','2') .
                                   $returned_rci_orders_bottom .
                                   tep_admin_files_boxes('', BOX_CUSTOMERS_MENU) .
                                   $returned_rci_customers_top .
                                   tep_admin_files_boxes(FILENAME_CUSTOMERS, BOX_CUSTOMERS_CUSTOMERS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_PENDING_ACCOUNTS, BOX_CUSTOMERS_PENDING_APPROVALS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_CUSTOMERS_GROUPS, BOX_CUSTOMERS_GROUPS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_CREATE_ACCOUNT, BOX_MANUAL_ORDER_CREATE_ACCOUNT, 'SSL' ,'','2') .
                                   tep_admin_files_boxes(FILENAME_CRE_MARKETPLACE, BOX_CRE_MARKETPLACE, 'SSL','','2') .
                                   $returned_rci_customers_bottom.
                                   tep_admin_files_boxes('', BOX_RETURNS_HEADING) .  
                                   $returned_rci_customer_returns_top . 
                                   tep_admin_files_boxes(FILENAME_RETURNS , BOX_RETURNS_MAIN, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_RETURNS_REASONS , BOX_RETURNS_REASONS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_REFUND_METHODS , BOX_HEADING_REFUNDS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_RETURNS_STATUS , BOX_RETURNS_STATUS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_RETURNS_TEXT , BOX_RETURNS_TEXT, 'SSL','','2') . 
                                   $returned_rci_customer_returns_bottom
                       );
    }
    // RCO eof
  }
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->