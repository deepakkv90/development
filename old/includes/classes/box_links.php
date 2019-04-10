<?php
/*
  $Id: box_links.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_links {
  public $rows = array();
  
  public function __construct() {
    global $languages_id;
    
    $query = tep_db_query("SELECT lc.link_categories_id, lcd.link_categories_name
                           FROM " . TABLE_LINK_CATEGORIES . " lc,
                                " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd
                           WHERE lc.link_categories_id = lcd.link_categories_id
                             and lc.link_categories_status = 1
                             and lcd.language_id = " . (int)$languages_id . "
                           ORDER BY lcd.link_categories_name");
    while ($data = tep_db_fetch_array($query)) {
      $this->rows[] = $data;
    }
  
  }  //end of __construct

} //end of class
?>
