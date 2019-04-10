<?php
/*
  $Id: box_manufacturer_info.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_manufacturer_info {
  public $rows = array();
  
  public function __construct() {
    global $languages_id;
    
    if (isset($_GET['products_id'])) {
      $query = tep_db_query("SELECT p.products_id, p.manufacturers_id, m.manufacturers_id as manf_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url
                             FROM " . TABLE_PRODUCTS . " p,
                                  " . TABLE_MANUFACTURERS . " m,
                                  " . TABLE_MANUFACTURERS_INFO . " mi
                             WHERE p.products_id = " . (int)$_GET['products_id'] . "
                               and m.manufacturers_id = p.manufacturers_id
                               and mi.manufacturers_id = m.manufacturers_id
                               and mi.languages_id = " . (int)$languages_id);
      while ($data = tep_db_fetch_array($query)) {;
        $this->rows[] = $data;
      }
    }
  
  }  //end of __construct

} //end of class
?>
