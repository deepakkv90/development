<?php
/*
  $Id: box_featured.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_wishlist {
  public $rows = array();
  
  public function __construct() {
    global $languages_id;
    
    if (isset($_SESSION['customer_id'])) {
      $wishlist_query_raw = "SELECT products_id
                             FROM " . TABLE_WISHLIST . "
                             WHERE customers_id = " . $_SESSION['customer_id'] . "
                               and products_id > 0
                             ORDER BY products_name
                             LIMIT " . MAX_DISPLAY_WISHLIST_BOX;
      $wishlist_query = tep_db_query($wishlist_query_raw);
      while ($wishlist = tep_db_fetch_array($wishlist_query)) {
        $this->rows[] = $wishlist['products_id'];
      }
    }
    
  }  //end of __construct

} //end of class
?>