<?php
/*
  $Id: box_authors.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_authors {
  public $rows = array();
  
  public function __construct() {
    $authors_query = tep_db_query("SELECT authors_id, authors_name
                                   FROM " . TABLE_AUTHORS . "
                                   ORDER BY authors_name");
    while ($authors = tep_db_fetch_array($authors_query)) {
      $this->rows[] = $authors;
    }
  }  //end of __construct

} //end of class
?>