<?php
/*
  $Id: box_downloads.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_downloads {
  public $rows = array();
  
  public function __construct() {
    global $PHP_SELF;
    
    if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
      // Get last order id for checkout_success
      $orders_query_raw = "SELECT orders_id
                           FROM " . TABLE_ORDERS . "
                           WHERE customers_id = " . (int)$_SESSION['customer_id'] . "
                           ORDER BY orders_id DESC LIMIT 1";
      $orders_query = tep_db_query($orders_query_raw);
      $orders_values = tep_db_fetch_array($orders_query);
      $last_order = $orders_values['orders_id'];
    } else {
      $last_order = (int)$_GET['order_id'];
    }
    // Now get all downloadable products in that order
    $query_sql = "SELECT DATE_FORMAT(date_purchased, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays
                  FROM " . TABLE_ORDERS . " o,
                       " . TABLE_ORDERS_PRODUCTS . " op,
                       " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd
                  WHERE customers_id = " . (int)$_SESSION['customer_id'] . "
                    and o.orders_id = " . (int)$last_order . "
                    and op.orders_id = " . (int)$last_order . "
                    and opd.orders_products_id = op.orders_products_id
                    and o.orders_status >= '" . DOWNLOADS_CONTROLLER_ORDERS_STATUS . "'
                    and opd.orders_products_filename <> ''";
    $query = tep_db_query($query_sql);
    while ($data = tep_db_fetch_array($query)) {
      $this->rows[] = $data;
    }
  
  }  //end of __construct

} //end of class
?>