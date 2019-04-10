<?php
/*
  $Id: box_faq.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_faq {
  public $category_rows = array();
  public $faq_rows = array();
  
  public function __construct() {
    global $languages_id;
    
    $categories_query = tep_db_query("SELECT ic.categories_id, icd.categories_name
                                      FROM " . TABLE_FAQ_CATEGORIES . " ic,
                                           " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd
                                      WHERE icd.categories_id = ic.categories_id
                                        and icd.language_id = " . (int)$languages_id . "
                                        and ic.categories_status = 1
                                      ORDER BY ic.categories_sort_order, icd.categories_name");
    while ($data = tep_db_fetch_array($categories_query)) {
      $this->category_rows[] = $data;
    }
    
    $faq_query = tep_db_query("SELECT ip.faq_id, ip.question
                               FROM " . TABLE_FAQ . " ip
                               LEFT JOIN " . TABLE_FAQ_TO_CATEGORIES . " ip2c on ip2c.faq_id = ip.faq_id
                               WHERE ip2c.categories_id = 0
                                 and ip.language = " . (int)$languages_id . "
                                 and ip.visible = 1
                               ORDER BY ip.v_order, ip.question");
    while ($data = tep_db_fetch_array($faq_query)) {
      $this->faq_rows[] = $data;
    }
  
  }  //end of __construct

} //end of class
?>