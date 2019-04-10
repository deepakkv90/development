<?php
/*
  $Id: events_calendar v2.00 2003/06/16 18:09:20 ip chilipepper.it Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/

// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('eventscalendar', 'top');
// RCI code eof
?>
  <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
    <tr>
      <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table>  
      </td>
    </tr>  

<?php
// BOF: Lango Added for template MOD
}else
{
  $header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD

// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
echo '<tr><td>' . "\n";
echo tep_draw_form('goto_event', FILENAME_EVENTS_CALENDAR, 'get') . "\n";
$ev_query = tep_db_query("SELECT *, DAYOFMONTH(start_date) AS day, MONTH(start_date) AS month, YEAR(start_date) AS year 
                          FROM ". TABLE_EVENTS_CALENDAR ."
                          WHERE language_id = '". $languages_id ."'
                            and end_date >= '". date('Y-m-d H:i:s') ."'
                          ORDER BY start_date");
if (tep_db_num_rows($ev_query) > 0){
  $event_array[]  = array('id' => '', 'text' => 'Select Event');
  while ($q_events = tep_db_fetch_array($ev_query)){
    $year = $q_events['year'];
    $month = $q_events['month'];
    $day = $q_events['day'];
    $event_array[] = array('id' => $q_events['event_id'] .'-'. $month .'-'. $year, 'text' => $cal->monthNames[$month - 1] .' '. $day .' -> '.$q_events['title']);
  }
  echo TEXT_SELECT_EVENT .'&nbsp;'. tep_draw_pull_down_menu('select_event', $event_array, NULL, 'onChange="(this.value != \'\') ? this.form.submit() : \'\' " ;', $required = false);
}
echo '</form>' . "\n";;

if ($single_event || isset($_GET['select_event'])) {
  $events_query = tep_db_query("select *,  DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where event_id = '". $select_event ."' and language_id = '". $languages_id ."'");
  while ($events = tep_db_fetch_array($events_query)) {
    list ($year, $month, $day) = split ('[/.-]', $events['start_date']);
    $date_start = date("F d, Y", mktime(0,0,0,$month,$day,$year));
    echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
    echo '<td class="main">' . $date_start . '</td>';
    echo '</tr></table><br>';

    list ($year, $month, $day) = split ('[/.-]', $events['start_date']);
    $date_start = date("F j, Y", mktime(0,0,0,$month,$day,$year));
    if ($events['end_date']) {
      list ($year_end, $month_end, $day_end) = split ('[/.-]', $events['end_date']);
      $date_end = date("F j, Y", mktime(0,0,0,$month_end,$day_end,$year_end));
    }
    $event_array = array('id' => $events['event_id'],
                         'title' => $events['title'],
                         'image' => $events['event_image'],
                         'description' => $events['description'],
                         'first_day' => $date_start,
                         'last_day' => $date_end,
                         'OSC_link' => $events['OSC_link'],
                         'link' => $events['link']);
    $clsp = 2;
    echo '<table border="0" width="100%" cellspacing="0" cellpadding="4" class="event">'.
         '<tr>'.
         '<td width="100%" bgcolor="#D9DEE6" class="main" style="border-bottom: 1px solid #D9DEE6" nowrap>'. TEXT_EVENT_TITLE .'&nbsp;&nbsp;'. $event_array['title'] .'</td>';
    if ($event_array['last_day']) {
      echo '<td bgcolor="#D9DEE6" align="center" nowrap><div class="event" style="border: 1px inset #F2F4F7">&nbsp;&nbsp;'. TEXT_EVENT_START_DATE .'&nbsp;&nbsp;'. $event_array['first_day'] .'&nbsp;&nbsp;</div></td><td bgcolor="#D9DEE6" align="center" nowrap><div class="event" style="border: 1px inset #F2F4F7">&nbsp;&nbsp;'. TEXT_EVENT_END_DATE .'&nbsp;&nbsp;'. $event_array['last_day'] .'&nbsp;&nbsp;</div></td>';
      $clsp++;
    }
    echo '</tr>'.
         '<tr><td colspan="'. $clsp . '" class="main">'. TEXT_EVENT_DESCRIPTION .'<br>';           
    if ($event_array['image']) {
      echo '<table border="0" cellspacing="0" cellpadding="0" align="right"><tr>'.
           '<td class="main">'. tep_image(DIR_WS_IMAGES .'events_images/' . $event_array['image'], $event_array['title'], '', '', 'align="right" hspace="5" vspace="5"') .'</td>'.
           '</tr></table>';
    }
    echo stripslashes($event_array['description']) .'</td></tr>';
    if ($event_array['OSC_link']) {
      echo '<tr>'.
           '<td colspan="'. $clsp . '"  bgcolor="#F5F5F5" align="left" class="main">'. TEXT_EVENT_OSC_LINK .'&nbsp;&nbsp;' . $event_array['OSC_link'] . $event_array['title'] .'</td></tr>';
    }
    if ($event_array['link']) {
      echo '<tr>'.
           '<td colspan="'. $clsp . '"  bgcolor="#F5F5F5" align="left" class="main">'. TEXT_EVENT_LINK .'&nbsp;&nbsp;<a href="http://'. $event_array['link'] .'" target="_blank">'. $event_array['link'] .'</a></td></tr>';
    }
    echo '</table><br>' . "\n";
  } 

  $other_events_query = tep_db_query("select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where DAYOFMONTH(start_date) = '". $day ."' and MONTH(start_date) = '". $month ."' and YEAR(start_date) = '". $year ."' and language_id = '". $languages_id ."' and event_id != '". $select_event ."'order by start_date");
  if (tep_db_num_rows($other_events_query) > 0) {
    echo '<table border="0" width="100%" cellspacing="0" cellpadding="2" class="event"><tr>'.
         '<td class="main" colspan="2"><b>'. TEXT_OTHER_EVENTS .'</b></td>'.
         '</tr>';

    while ($other_events = tep_db_fetch_array($other_events_query)) {
      $event_array = array('id' => $other_events['event_id'],
                           'event' => $other_events['event'],
                           'title' => $other_events['title']);

      echo '<tr><td align="center" width="24" class="main" nowrap><b>'. $i .'</b></td><td width="100%" class="main"><a href="'. FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. $event_array['title'] .'</a></td></tr>';
      $i++;
    }
    echo '</table>' . "\n";  
  }
  echo '</td></tr>' . "\n";
} elseif ($_GET['year_view'] == 1) {
  echo '<tr><td>' . "\n";
  echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
  echo '<td>'. $cal->getYearView($year_) .'</td>';
  echo '</tr></table>' . "\n";
  echo '</td></tr>' . "\n";
} elseif (isset($_GET['_day'])) {
  $events_query_raw = "select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where DAYOFMONTH(start_date) = '". $day_ ."' and MONTH(start_date) = '". $month_ ."' and YEAR(start_date) = '". $year_ ."' and language_id = '". $languages_id ."' order by start_date";
  $events_split = new splitPageResults($events_query_raw, MAX_DISPLAY_NUMBER_EVENTS, 'DAYOFMONTH(start_date)');
  $events_query = tep_db_query($events_query_raw);
  if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES .' : '. $date); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
       </td>
      </tr>

<?php
  }
  
  $row = 0;
  $events_query = tep_db_query($events_split->sql_query);

  while ($events = tep_db_fetch_array($events_query)) {
    $row++;
    list ($year, $month, $day) = split ('[/.-]', $events['start_date']);
    $date = date("F j, Y", mktime(0,0,0,$month,$day,$year));
    $event_array = array('id' => $events['event_id'],
                         'event' => $events['event'],
                         'title' => $events['title'],
                         'link' => $events['link'],
                         'date' => $date );
    $clsp = 2;
    echo '<tr><td>'.
         '<table border="0" width="100%" cellspacing="0" cellpadding="4" class="event">'.
         '<tr>'.
         '<td align="center" width="20" bgcolor="#F5F5F5" class="main" nowrap><b>'. $i .'</b></td><td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_DATE .'&nbsp;&nbsp;<a href="'. $cal->getDbLink($event_array['event'], $month_, $year_) .'">'. $event_array['date'] .'</a></td>';
    if ($event_array['link']){
      echo '<td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_LINK .'&nbsp;&nbsp;<a href="http://'. $event_array['link'] .'" target="_blank">'. $event_array['link'] .'</a></td>';
      $clsp++;
    } else {
      echo '<td>&nbsp;</td>';
    }
    echo '</tr><tr>'.
         '<td colspan="3" class="main">'. TEXT_EVENT_TITLE .'<br>'. $event_array['title'] .'&nbsp;&nbsp;<a href="'. FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. TEXT_EVENT_MORE .'</a></td>'.
         '</tr></table></td></tr>'. tep_draw_separator('pixel_trans.gif', '100%', '4');
    $i++;
  }
     
  if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
        <tr>
          <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES .' : '. $date); ?></td>
              <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
            </tr>
          </table>
          </td>
        </tr>
<?php
  }
} elseif ($_GET['view'] == 'all_events') {
  $events_query_raw = "select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where start_date > '". date('Y-m-d H:i:s') ."' and language_id = '". $languages_id ."' order by start_date";
  //." where start_date > '". date('Y-m-d H:i:s') ."' and language_id = '". $languages_id ."' order by start_date";
  $events_split = new splitPageResults($events_query_raw, MAX_DISPLAY_NUMBER_EVENTS, 'DAYOFMONTH(start_date)');
  $events_query = tep_db_query($events_query_raw);

  if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
       </td>
      </tr>
<?php
  }
  $row = 0;
  $events_query = tep_db_query($events_split->sql_query);

  while ($events = tep_db_fetch_array($events_query)){
    $row++;
    list ($year, $month, $day) = split ('[/.-]', $events['start_date']);
    $date = date("F j, Y", mktime(0,0,0,$month,$day,$year));
    $event_array = array('id' => $events['event_id'],
                         'event' => $events['event'],
                         'title' => $events['title'],
                         'link' => $events['link'],
                         'date' => $date );
    $clsp = 2;
    echo '<tr><td>' . "\n";
    echo '<table border="0" width="100%" cellspacing="0" cellpadding="4" class="event">'.
         '<tr>'.
         '<td align="center" width="20" bgcolor="#F5F5F5" class="main" nowrap><b>'. $i .'</b></td><td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_DATE .'&nbsp;&nbsp;<a href="'. $cal->getDbLink($event_array['event'], $month_, $year_) .'">'. $event_array['date'] .'</a></td>';
    if ($event_array['link']) {
      echo '<td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_LINK .'&nbsp;&nbsp;<a href="http://'. $event_array['link'] .'" target="_blank">'. $event_array['link'] .'</a></td>';
      $clsp++;
    }
    echo '</tr><tr>'.
         '<td colspan="3" class="main">'. TEXT_EVENT_TITLE .'<br>'. $event_array['title'] .'&nbsp;&nbsp;<a href="'. FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. TEXT_EVENT_MORE .'</a></td>'.
         '</tr></table>'. tep_draw_separator('pixel_trans.gif', '100%', '4');
    echo '</td></tr>' . "\n";
    $i++;
  }
  if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
       <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
        </td>
      </tr>
<?php
  }
} else {
  $events_query_raw = "select *, DAYOFMONTH(start_date) AS event from ". TABLE_EVENTS_CALENDAR ." where MONTH(start_date) = '". $month_ ."' and YEAR(start_date) = '". $year_ ."' and language_id = '". $languages_id ."'  order by start_date";
  $events_split = new splitPageResults($events_query_raw, MAX_DISPLAY_NUMBER_EVENTS, 'DAYOFMONTH(start_date)');
  $months = $cal->monthNames[$month_ - 1];
  echo '<tr><td>' . "\n";
  
  echo '<table border="0" width="100%" cellspacing="0" cellpadding="2">'.
       '<tr><td colspan="3" class="main">' . $months .', '. $year_ .'</td>'.
       '</tr></table>';

  if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES .' : '. $months); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table>
<?php
  }    
  
  $row = 0;
  $events_query = tep_db_query($events_split->sql_query);

  while ($events = tep_db_fetch_array($events_query)) {
    $row++;
    list ($year, $month, $day) = split ('[/.-]', $events['start_date']);
    $date = date("F j, Y", mktime(0,0,0,$month,$day,$year));
          
    $event_array = array('id' => $events['event_id'],
                         'event' => $events['event'],
                         'title' => $events['title'],
                         'link' => $events['link'],
                         'date' => $date );
    $clsp = 2;
    echo '<table border="0" width="100%" cellspacing="0" cellpadding="4" class="event">'.
         '<tr>'.
         '<td align="center" width="20" bgcolor="#F5F5F5" class="main" nowrap><b>'. $i .'</b></td><td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_DATE .'&nbsp;&nbsp;<a href="'. $cal->getDbLink($event_array['event'], $month_, $year_) .'">'. $event_array['date'] .'</a></td>';
    if ($event_array['link']) {
      echo '<td width="100%" bgcolor="#D9DEE6" class="main" nowrap>'. TEXT_EVENT_LINK .'&nbsp;&nbsp;<a href="http://'. $event_array['link'] .'" target="_blank">'. $event_array['link'] .'</a></td>';
      $clsp++;
    }
    echo '</tr><tr>'.
         '<td colspan="3" class="main">'. TEXT_EVENT_TITLE .'<br>'. $event_array['title'] .'&nbsp;&nbsp;<a href="'. FILENAME_EVENTS_CALENDAR . '?select_event='. $event_array['id'] .'">'. TEXT_EVENT_MORE .'</a></td>'.
         '</tr></table>'. tep_draw_separator('pixel_trans.gif', '100%', '4');
    $i++;
  }

  if (($events_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="smallText"><?php echo $events_split->display_count(TEXT_DISPLAY_NUMBER_OF_PAGES .' : '. $months); ?></td>
              <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $events_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
            </tr>
          </table>
<?php
  }
  echo '</td></tr>' . "\n";   
}
?>
<!-- body_text_eof //-->
<?php 
// RCI code start
echo $cre_RCI->get('eventscalendar', 'menu');
// RCI code eof
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
  </table>
<?php
// RCI code start
echo $cre_RCI->get('eventscalendar', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>