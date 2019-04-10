<?php
/*
  $Id: box_free_content.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_free_content {
  public $rows = array();
  
  public function __construct() {
    global $languages_id, $customer_group_id;
    
    $query = tep_db_query("SELECT * FROM " . TABLE_PAGES_DESCRIPTION . " WHERE pages_id='54'");
    $data = tep_db_fetch_array($query);
    $this->rows[] = $data;
	  
  }  //end of __construct

} //end of class
?>
