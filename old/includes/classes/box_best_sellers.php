<?php
/*
  $Id: box_best_sellers.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_best_sellers {
  public $rows = array();
  
  public function __construct() {
    global $current_category_id, $languages_id, $customer_group_id;
    
    if (isset($current_category_id) && ($current_category_id > 0)) {
      $best_query = tep_db_query("SELECT distinct p.products_id, pd.products_name
                                  FROM " . TABLE_PRODUCTS . " p,
                                       " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                       " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c,
                                       " . TABLE_CATEGORIES . " c
                                  WHERE p.products_status = 1
                                    and p.products_ordered > 0
                                    and p.products_id = pd.products_id
                                    and pd.language_id = " . (int)$languages_id . "
                                    and p.products_id = p2c.products_id
                                    and p2c.categories_id = c.categories_id
                                    and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id)
                                    and p.products_group_access like '%" . $customer_group_id . "%'
                                  ORDER BY p.products_ordered desc, pd.products_name
                                  LIMIT " . MAX_DISPLAY_BESTSELLERS);
    } else {
      $best_query = tep_db_query("SELECT distinct p.products_id, pd.products_name
                                  FROM " . TABLE_PRODUCTS . " p,
                                       " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                  WHERE p.products_status = 1
                                    and p.products_ordered > 0
                                    and p.products_id = pd.products_id
                                    and pd.language_id = " . (int)$languages_id . "
                                    and p.products_group_access like '%" . $customer_group_id . "%'
                                  ORDER BY p.products_ordered desc, pd.products_name
                                  LIMIT " . MAX_DISPLAY_BESTSELLERS);
    }
    
    while ($best = tep_db_fetch_array($best_query)) {
      $this->rows[] = $best;
    }
  
  }  //end of __construct

} //end of class
?>