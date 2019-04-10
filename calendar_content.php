<?php
/*
  $Id: events_calendar v1.00 2003/03/08 18:09:16 ip chilipepper.it Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_EVENTS_CALENDAR);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo STORE_NAME ?></title>
<!-- events_calendar //-->
<link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME . '/stylesheet.css'?>">
<style type="text/css">
<!--
body, td {margin :0; padding:0;}
.button {border: 1px outset; margin: 0px; color: #000000; width: 20px; height: 20px;
}
-->
</style>
<script type="text/javascript">
function jump(view, url){
if (document.all||document.getElementById){
    month= document.calendar._month.options[document.calendar._month.selectedIndex].value;
    year=  document.calendar._year.options[document.calendar._year.selectedIndex].value;
    return url +'?_month='+ month +'&amp;_year='+ year +'&amp;year_view='+ view;
 }
}
</SCRIPT>
</head>
<body>
<?php
// Construct a calendar to show the current month
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
echo '<form method="get" name="calendar" action="events_calendar.php">';
echo '<table bgcolor="#EDECE9" width="100%"  align="center" border="0" cellspacing="0" cellpadding="0" style="cursor: default">';
echo '<tr><td align="center" valign="top">' . $cal->getMonthView($month_,$year_) . '</td></tr>';
//echo '<tr>';
?>

  <tr>
     <td style="line-height: 2px;">&nbsp;
     </td>
  </tr>
  <tr>
    <td align="center" valign="top" nowrap="nowrap">
<?php
$monthShort = explode("," ,MONTHS_SHORT_ARRAY);
echo '<select name="_month" class="select">';
while (list($key, $value) = each($monthShort)){
  if ($key+1 == $month_){
    ++$key;
    echo '<option value="'. $key .'" selected>'. $value .'</option>' . "\n";
  } else {
    ++$key;
    echo '<option value="'. $key .'">'. $value .'</option>' . "\n";
  }
}
echo '</select>';
echo '<select name="_year" class="select">';
$years = NUMBER_OF_YEARS;
for ($y=0; $y < $years; $y++){
  $_y = $year+$y;
  if ($_y == $year_){
    echo '<option value="'. $_y .'" selected>'. $_y .'</option>' . "\n";
  } else {
    echo '<option value="'. $_y .'">'. $_y .'</option>' . "\n";
  }
}
?>
</select>
<input type="button" class="button" title="<?php echo BOX_GO_BUTTON_TITLE; ?>" value="<?php echo BOX_GO_BUTTON; ?>"  onclick="top.window.location=jump(0,'<?php echo  FILENAME_EVENTS_CALENDAR ; ?>')">
<?php
if ($month_ != $this_month) {
?>
<br>
<input type="button" class="button" title="<?php echo BOX_TODAY_BUTTON_TITLE; ?>" value="<?php echo BOX_TODAY_BUTTON; ?>" onclick=top.calendar.location="<?php echo  FILENAME_EVENTS_CALENDAR_CONTENT ; ?>?_month=<?php echo $this_month .'&amp;_year='. $this_year ?>">
<?php
}
?>
<input type="button" class="button" title="<?php echo BOX_YEAR_VIEW_BUTTON_TITLE; ?>" value="<?php echo BOX_YEAR_VIEW_BUTTON; ?>" onclick="top.window.location=jump(1,'<?php echo  FILENAME_EVENTS_CALENDAR ; ?>')">
     </td>
    </tr> 
   </table>
 </form>
</body>
</html>
<!-- events_calendar //-->
