<?php
/*
  $Id: box_customer_gv.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_customer_gv {
  public $amount = 0;
  public $rows = array();
  
  public function __construct() {
    if (isset($_SESSION['customer_id'])) {
      $query = tep_db_query("SELECT amount
                             FROM " . TABLE_COUPON_GV_CUSTOMER . "
                             WHERE customer_id = " . (int)$_SESSION['customer_id']);
      $row_count = tep_db_num_rows($query);
      if ($row_count > 0) {
        $gv_result = tep_db_fetch_array($query);
        $this->amount = $gv_result['amount'];
      }
    }
  
  }  //end of __construct

} //end of class
?>