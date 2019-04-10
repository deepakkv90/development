<?php
/*
  $Id: box_manufacturers.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_manufacturers {
  public $rows = array();
  
  public function __construct() {
    global $languages_id;
    
    if (isset($_GET['products_id'])) {
      $query = tep_db_query("SELECT manufacturers_id, manufacturers_name
                             FROM " . TABLE_MANUFACTURERS . "
                             ORDER BY manufacturers_name");
      while ($data = tep_db_fetch_array($query)) {;
        $this->rows[] = $data;
      }
    }
  
  }  //end of __construct

} //end of class
?>
