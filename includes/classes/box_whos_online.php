<?php
/*
  $Id: box_whos_online.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_whos_online {
  public $member_count = 0;
  public $guest_count = 0;
  public $rows = array();
  
  public function __construct() {
    // Set expiration time, default is 900 secs (15 mins)
    $xx_mins_ago = (time() - 900);
    tep_db_query("DELETE FROM " . TABLE_WHOS_ONLINE . "
                         WHERE time_last_click < '" . $xx_mins_ago . "'");
    
    $whos_online_query = tep_db_query("SELECT customer_id
                                       FROM " . TABLE_WHOS_ONLINE);
    while ($whos_online = tep_db_fetch_array($whos_online_query)) {
      $this->rows[] = $whos_online['customer_id'];
      if ($whos_online['customer_id'] != 0) $this->member_count++;
      if ($whos_online['customer_id'] == 0) $this->guest_count++;
    }
    
  }  //end of __construct

} //end of class
?>