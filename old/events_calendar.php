<?php
/*
  $Id: events_calendar v2.00 2003/06/16 18:09:20 ip chilipepper.it Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EVENTS_CALENDAR);
  
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_EVENTS_CALENDAR, '', 'NONSSL'));
  $i =1;
  $select_event = isset($_GET['select_event']) ? (int)$_GET['select_event'] : 0;
  $cal = new Calendar;
  $cal->setStartDay(FIRST_DAY_OF_WEEK);
  $this_month = date('m');
  $this_year = date('Y');

  if (isset($_GET['_month']) && tep_not_null($_GET['_month'])) {
    $month = (int)$_GET['_month'];
    $year = isset($_GET['_year']) && tep_not_null($_GET['_year']) ? (int)$_GET['_year'] : $this_year;
    $a = $cal->adjustDate($month, $year);
    $month_ = $a[0];
    $year_ = $a[1];
  } else {
    $month_ = $this_month;
    $year_ = $this_year;
  }
  $day_ = isset($_GET['_day']) && tep_not_null($_GET['_day']) ? (int)$_GET['_day'] : date('j');
  
  $single_event = false;
  if(isset($_GET['_day'])){
    $ev_query = tep_db_query("select event_id from ". TABLE_EVENTS_CALENDAR ." where DAYOFMONTH(start_date)= '". $day_ ."' and MONTH(start_date) = '". $month_ ."' and YEAR(start_date) = '". $year_ ."' AND language_id = '". (int)$languages_id ."'");
    if (tep_db_num_rows($ev_query) < 2){
      $ev = tep_db_fetch_array($ev_query);
      $single_event = true;
      $select_event = $ev['event_id'];
    }
  }
  
  $content = CONTENT_EVENTS_CALENDAR;
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
