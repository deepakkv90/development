<?php
/*
  $Id: box_whats_new.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_whats_new {
  public $rows = array();
  
  public function __construct() {
    global $languages_id, $customer_group_id;
    
    $whats_query = tep_db_query("SELECT DISTINCT p.products_id, p.products_image, p.products_price, p.manufacturers_id, pd.products_name, p.products_tax_class_id, p.products_date_added, p.products_image
                                 FROM (" . TABLE_PRODUCTS . " p
                                 LEFT JOIN " . TABLE_SPECIALS . " s using(products_id)),
                                       " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                 WHERE p.products_status = 1
                                   and pd.products_id = p.products_id
                                   and pd.language_id = " . $languages_id . "
                                   and DATE_SUB(CURDATE(),INTERVAL " .NEW_PRODUCT_INTERVAL ." DAY) <= p.products_date_added
                                   and p.products_group_access like '%" . $customer_group_id . "%'
                                 ORDER BY rand(), products_date_added desc
                                 LIMIT " . MAX_RANDOM_SELECT_NEW);
    while ($whats = tep_db_fetch_array($whats_query)) {
      $this->rows[] = $whats;
    }
  
  }  //end of __construct

} //end of class
?>
