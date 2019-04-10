<?php
/*
$id author Puddled Internet - http://www.puddled.co.uk
  email support@puddled.co.uk
   osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  if (!isset($_SESSION['customer_id'])) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_RETURNS_TRACK);
  if (isset($_GET['rma'])) {
      $rma = $_GET['rma'] ;
  } else if (isset($_POST['rma'])) {
      $rma = $_POST['rma'] ;
  } else {
      $rma = '0' ;
  }

  if (!isset($_GET['action'])){
      $_GET['action'] = 'returns_track';
  }
  if (isset($_GET['action'])) {
    switch ($_GET['action']) {
    case 'returns_show':

       // first carry out a query on the database to see if there are any matching tickets
       $database_returns_query = tep_db_query("SELECT returns_id, returns_status FROM " . TABLE_RETURNS . " where customers_id = '" . $customer_id . "' and rma_value = '" . $rma . "'");
       if (!tep_db_num_rows($database_returns_query)) {
           tep_redirect(tep_href_link(FILENAME_RETURNS_TRACK,'error_message=' . TEXT_TRACK_DETAILS_1));
       } else {
          $returns_query = tep_db_fetch_array($database_returns_query);
          $returns_id = $returns_query['returns_id'];
          $returns_status_id = $returns_query['returns_status'];
          $returns_status_query = tep_db_query("SELECT returns_status_name FROM " . TABLE_RETURNS_STATUS . " where returns_status_id = " . $returns_status_id . " and language_id = '" . (int)$languages_id . "'");
          $returns_status_array = tep_db_fetch_array($returns_status_query);
          $returns_status = $returns_status_array['returns_status_name'];
          $returned_products_query = tep_db_query("SELECT * FROM " . TABLE_RETURNS_PRODUCTS_DATA . " op, " . TABLE_RETURNS . " o where o.returns_id = op.returns_id and op.returns_id = '" . $returns_id . "'");
          $returned_products = tep_db_fetch_array($returned_products_query);

              require(DIR_WS_CLASSES . 'order.php');
           $order = new order($returned_products['order_id']);

       }

    break;
   }
}
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);
 
 $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
 $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
if(isset($_GET['order_id']) && $_GET['order_id'] != ''){
 $breadcrumb->add(sprintf(NAVBAR_TITLE_3, $_GET['order_id']), tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$_GET['order_id'], 'SSL'));
}
 $breadcrumb->add(NAVBAR_TITLE . (($rma!='') ? ' #' . $rma : ''));
 $content = CONTENT_RETURNS_TRACK;
 require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>