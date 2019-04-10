<?php
/*
  $Id: box_featured.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_featured {
  public $row_count = 0;
  public $rows = array();
  
  public function __construct() {
    global $languages_id, $customer_group_id;
    
    $query = tep_db_query("SELECT DISTINCT p.products_id, p.products_image, p.products_price, p.manufacturers_id, pd.products_name, p.products_tax_class_id, p.products_date_added, p.products_image
                           FROM " . TABLE_PRODUCTS . " p,
                                " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                " . TABLE_FEATURED . " f
                           WHERE p.products_status = 1
                             and f.status = 1
                             and p.products_id = f.products_id
                             and pd.products_id = f.products_id
                             and pd.language_id = " . $languages_id . "
                             and p.products_group_access like '%" . $customer_group_id . "%'
                           ORDER BY rand()
                           LIMIT 1");
    $this->row_count = tep_db_num_rows($query);
    if ($this->row_count > 0) {
      $i = 0;
      while ($data = tep_db_fetch_array($query)) {
        foreach ($data as $key => $value) {
          $this->rows[$i][$key] = $value;
        }
        ++$i;
      }
    }
  
  }  //end of __construct

} //end of class
?>
