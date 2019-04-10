<?php
/*
  $Id: box_order_history.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_order_history {
  public $rows = array();
  
  public function __construct() {
    global $languages_id;
    
    if (isset($_SESSION['customer_id'])) {
      $query = tep_db_query("SELECT DISTINCT op.products_id, pd.products_name
                             FROM " . TABLE_ORDERS . " o,
                                  " . TABLE_ORDERS_PRODUCTS . " op,
                                  " . TABLE_PRODUCTS . " p,
                                  " . TABLE_PRODUCTS_DESCRIPTION . " pd
                             WHERE o.customers_id = " . (int)$_SESSION['customer_id'] . "
                               and o.orders_id = op.orders_id
                               and op.products_id = p.products_id
                               and p.products_status = 1
                               and pd.products_id = p.products_id
                               and pd.language_id = " . (int)$languages_id . "
                             GROUP BY products_id
                             ORDER BY o.date_purchased desc
                             LIMIT " . MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX);
      while ($data = tep_db_fetch_array($query)) {;
        $this->rows[] = $data;
      }
    }
  
  }  //end of __construct

} //end of class
?>
