<?php
/*
  $Id: box_specials.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_specials {
  public $rows = array();
  
  public function __construct() {
    global $languages_id, $customer_group_id;
    
    $query = tep_db_query("SELECT DISTINCT p.products_id, pd.products_name, p.products_tax_class_id, p.products_image, p.products_price, s.specials_new_products_price
                           FROM " . TABLE_PRODUCTS . " p,
                                " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                " . TABLE_SPECIALS . " s
                           WHERE p.products_status = 1
                             and p.products_id = s.products_id
                             and pd.products_id = s.products_id
                             and pd.language_id = " . (int)$languages_id . "
                             and s.status = 1
                             and p.products_group_access like '%" . $customer_group_id . "%'
                           ORDER BY rand(), s.specials_date_added desc
                           LIMIT " . MAX_RANDOM_SELECT_SPECIALS);
    while ($data = tep_db_fetch_array($query)) {;
      $this->rows[] = $data;
    }
  
  }  //end of __construct

} //end of class
?>
