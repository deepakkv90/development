<?php
/*
  $Id: box_reviews.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_reviews {
  public $random_product = array();
  public $review = array();
  
  public function __construct() {
    global $languages_id;
    
    $sql = "SELECT r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name
            FROM " . TABLE_REVIEWS . " r,
                 " . TABLE_REVIEWS_DESCRIPTION . " rd,
                 " . TABLE_PRODUCTS . " p,
                 " . TABLE_PRODUCTS_DESCRIPTION . " pd
            WHERE p.products_status = 1
              and p.products_id = r.products_id
              and r.reviews_id = rd.reviews_id
              and rd.languages_id = " . (int)$languages_id . "
              and p.products_id = pd.products_id
              and pd.language_id = " . (int)$languages_id;
    if (isset($_GET['products_id'])) {
      $sql .= " and p.products_id = " . (int)$_GET['products_id'];
    }
    $sql .= " ORDER BY r.reviews_id desc
              LIMIT " . MAX_RANDOM_SELECT_REVIEWS;
    $this->random_product = tep_random_select($sql);
    
    if ($this->random_product != '') {
      $query = tep_db_query("SELECT substring(reviews_text, 1, 60) as reviews_text
                             FROM " . TABLE_REVIEWS_DESCRIPTION . "
                             WHERE reviews_id = " . (int)$this->random_product['reviews_id'] . "
                               and languages_id = " . (int)$languages_id);
      $this->review = tep_db_fetch_array($query);
    }
      
  }  //end of __construct

} //end of class
?>
