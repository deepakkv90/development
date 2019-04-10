<?php
/*
  $Id: fss_reports.php,v 1.0.0.0 2007/10/30 

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
  require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);  

  // RCI code start
  $cre_RCI->get('global', 'top');
  $cre_RCI->get('fssreports', 'top'); 
  // RCI code eof
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $forms_id = (isset($_GET['fID']) ? $_GET['fID'] : '');
  $forms_name = tep_get_forms_name($forms_id);
  $period = $_GET['period'];
  $period_from1 = isset($_GET['period_from']) ? urlencode($_GET['period_from']) : date('m-d-Y', mktime(0, 0, 0, date('m'), date('d')-date('w'), date('Y')));
  $period_array = explode('-', $period_from1);
  $period_from = $period_array[2] . '-' . $period_array[0] . '-' . $period_array[1];
  $period_to1 = isset($_GET['period_to']) ? urlencode($_GET['period_to']) : date('m-d-Y');
  $period_array = explode('-', $period_to1);
  $period_to = $period_array[2] . '-' . $period_array[0] . '-' . $period_array[1];
  $cross_reference = tep_not_null($_GET['cross_reference']) ? '&cross_reference=' . $_GET['cross_reference'] : '';
  
  switch ( $action ) {
    case 'export_excel':
      tep_fss_export_excel($_GET['filter'], $period_from1, $period_to1, $_GET['questions_id'], $_GET['type']);
      break;
  }  
  
  if ( isset($_GET['questions_id']) && tep_not_null($_GET['questions_id']) ) {
    $back_url = tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('questions_id', 'filter', 'cross_reference')));
  } else {
    $back_url = tep_href_link(FILENAME_FSS_FORMS_BUILDER, tep_get_all_get_params(array('report_period', 'period_from', 'period_to', 'filter', 'period', 'cross_reference')));
  }
  $period_array = array(array('id' => '0', 'text' => 'This Week'),
                        array('id' => '1', 'text' => 'Last 7 Days'),
                        array('id' => '2', 'text' => 'This Month'),
                        array('id' => '3', 'text' => 'Last Two Months'),
                        array('id' => '4', 'text' => 'Last Three Months'),
                        array('id' => '5', 'text' => 'Last Six Months'),
                        array('id' => '6', 'text' => 'Last Year'),
                        array('id' => '7', 'text' => 'Last Two Years'),
                        array('id' => '8', 'text' => 'All Time'))
  

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if ($is_62) {
    echo '<script language="javascript" src="includes/menu.js"></script>' . "\n";
} else {
    echo '<script type="text/javascript" src="includes/prototype.js"></script>' . "\n";
    echo '<!--[if IE]>' . "\n";
    echo '<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">' . "\n";
    echo '<![endif]-->' . "\n";
}
?>
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript"><!--  
  function set_period(obj) {
    switch (obj.value) {
      // This Week
      case '0':
      default:
        start = "<?php echo date('m-d-Y', mktime(0, 0, 0, date('m'), date('d')-date('w'), date('Y'))); ?>";
        end = "<?php echo date('m-d-Y'); ?>";
        break;
      // Last 7 Days
      case '1':
        start = "<?php echo date('m-d-Y', mktime(0, 0, 0, date('m'), date('d')-6, date('Y'))); ?>";
        end = "<?php echo date('m-d-Y');  ?>";
        break;
      // This Month
      case '2': 
        start = "<?php echo date('m-d-Y', mktime(0, 0, 0, date('m'), 1, date('Y'))); ?>";
        end = "<?php echo date('m-d-Y');   ?>";
        break;
      // Last Two Months
      case '3':
        start = "<?php echo date('m-d-Y',  mktime(0, 0, 0, date("m")-1, 1, date("Y"))); ?>";
        end = "<?php echo date('m-d-Y'); ?>";
        break;
      // Last Three Months
      case '4':
        start = "<?php echo date('m-d-Y',  mktime(0, 0, 0, date("m")-2, 1, date("Y"))); ?>";
        end = "<?php echo date('m-d-Y'); ?>";
        break;
      // Last Six Months
      case '5':
        start = "<?php echo date('m-d-Y',  mktime(0, 0, 0, date("m")-5, 1, date("Y"))); ?>";
        end = "<?php echo date('m-d-Y'); ?>";
        break;
      // Last Year
      case '6':
        start = "<?php echo date('m-d-Y',  mktime(0, 0, 0, 1, 1, date("Y")-1)); ?>";
        end = "<?php echo date('m-d-Y',  mktime(0, 0, 0, 12, 31, date("Y")-1)); ?>";
        break;
      // Last Two Years
      case '7':
        start = "<?php echo date('m-d-Y',  mktime(0, 0, 0, 1, 1, date("Y")-1)); ?>";
        end = "<?php echo date('m-d-Y'); ?>";
        break;
      // All Time
      case '8':
        start = '';
        end = '';
        break;
    }
    document.reports.period_from.value = start;
    document.reports.period_to.value = end;
  }
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="spiffycalendar" class="text"></div> 
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0"> 
      <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE . (tep_not_null($forms_name) ? ' - ' . $forms_name : ''); ?></td>
        <td class="main" align="right"><a href="<?php echo $back_url; ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a></td>
      </tr>
      <tr>
        <td class="main" align="right" colspan="2">
<?php 
  echo tep_draw_form('reports', FILENAME_FSS_REPORTS, '', 'get', '', 'SSL'); 
  foreach ($_GET as $key => $value) {
    if ( $key != 'report_period' && $key != 'period_from' && $key != 'period_to' ) {
      echo tep_draw_hidden_field($key, $value);
    }
  }
?>
<?php
  echo HEADING_TITLE_SHOW . tep_draw_pull_down_menu('period', $period_array, $period, 'onchange="set_period(this); document.reports.submit();"');
?>
<script type="text/javascript"><!-- 
  var period_start = new ctlSpiffyCalendarBox("period_start", "reports", "period_from","btnDate1","<?php echo urldecode($period_from1); ?>",scBTNMODE_CUSTOMBLUE);
  var period_end = new ctlSpiffyCalendarBox("period_end", "reports", "period_to","btnDate2","<?php echo urldecode($period_to1); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
&nbsp;&nbsp;<?php echo TEXT_PERIOD_FROM; ?><script script type="text/javascript">period_start.writeControl(); period_start.dateFormat="MM-dd-yyyy";</script>&nbsp;&nbsp;<?php echo TEXT_PERIOD_TO; ?>
<script script type="text/javascript">period_end.writeControl(); period_end.dateFormat="MM-dd-yyyy";</script>
<?php
  echo tep_image_submit('button_confirm.gif', IMAGE_CONFIRM);
  echo '</form>';
?></td>
      </tr>
      <tr>
        <td colspan="2"><table border="0" cellspacing="0" cellpadding="2">
<?php
  if ( isset($_GET['questions_id']) && tep_not_null($_GET['questions_id']) ) {
    $post_status = array();
    $post_status_query = tep_db_query("SELECT * FROM " . TABLE_FSS_FORMS_POSTS_STATUS);
    while ($post_status_array = tep_db_fetch_array($post_status_query)) {
      $post_status[] = array('id' => $post_status_array['forms_posts_status_id'],
                             'value' => $post_status_array['status_value']);
    }
    $posts_query = tep_db_query("SELECT posts_status_value, count(*) AS num FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc WHERE ffp.forms_posts_id = ffpc.forms_posts_id and ffpc.questions_id = '" . $_GET['questions_id'] . "'" . (tep_not_null($period_from) && tep_not_null($period_to) ? " AND posts_date BETWEEN '" . urldecode($period_from) . "' AND '" . urldecode($period_to) . "'" : '') . " GROUP BY posts_status_value");
    $posts = array();
    $posts_total = 0;
    while ($posts_data = tep_db_fetch_array($posts_query)) {
      $posts[$posts_data['posts_status_value']] = $posts_data['num'];
      $posts_total += $posts_data['num'];
    }
    $posts_query1 = tep_db_query("SELECT month(ffp.posts_date) as i_month, dayofmonth(ffp.posts_date) as i_day, year(ffp.posts_date) as i_year, count(*) AS num FROM " . TABLE_FSS_FORMS_POSTS . " ffp, " . TABLE_FSS_FORMS_POSTS_CONTENT . " ffpc WHERE ffp.forms_posts_id = ffpc.forms_posts_id and ffpc.questions_id = '" . $_GET['questions_id'] . "'" . (tep_not_null($period_from) && tep_not_null($period_to) ? " AND posts_date BETWEEN '" . urldecode($period_from) . "' AND '" . urldecode($period_to) . "'" : '') . " GROUP BY i_year, i_month, i_day order by posts_date desc");
    $posts1 = array();
    while ($posts_data1 = tep_db_fetch_array($posts_query1)) {
      $post_date = $posts_data1['i_year'] . '-' . $posts_data1['i_month'] . '-' . $posts_data1['i_day'];
      $posts1[$post_date] = $posts_data1['num'];
    }
?>
          <tr>
            <td>&nbsp;</td>
            <td class="pageHeading" colspan="2"><?php echo HEADING_TITLE_OVERTIME . '<br>' . TEXT_QUESTION . ' - ' . tep_fss_get_questions_label($_GET['questions_id']);?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="main" colspan="2"><img src="<?php echo tep_href_link('fss_report_chart.php', 'type=activity_overtime_questions&period_from=' . $period_from . '&period_to=' . $period_to . '&questions_id=' . $_GET['questions_id'] . '&filter=' . $_GET['filter']); ?>" /></td>
          </tr>
<?php
$specail_type = tep_fss_get_questions_special_data_type($_GET['questions_id']);
    if ( $specail_type !== false ) {      
?>
          <tr>
            <td>&nbsp;</td>
            <td class="pageHeading" colspan="2"><?php echo HEADING_TITLE_ANSWERS; ?></td>
          </tr>     
          <tr>
            <td colspan="3" style="padding-left:10px;"><table border="0" cellspacing="0" cellpadding="2">
<?php
      echo tep_draw_form('filter', FILENAME_FSS_REPORTS, '', 'get', '', 'SSL'); 
      foreach ($_GET as $key => $value) {
        if ( $key != 'filter' ) {
          echo tep_draw_hidden_field($key, $value);
        }
      }
      $quenstions_values = tep_fss_get_values_percentage($_GET['questions_id'], $period_from, $period_to);
      $total = 0;
      foreach ($quenstions_values as $value) {
        $total += $value['count'];
        $checked = ($value['fields_value'] == $_GET['filter']);
        switch ($specail_type) {
          case 'customers_id':
            $view_url = tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('filter', 'period_from', 'period_to', 'period')) . 'filter=' . urlencode($value['fields_value']) . '&period_from=' . $period_from1 . '&period_to=' . $period_to1, 'SSL');
            $view_button = tep_image_button('button_view_customers.gif', IMAGE_VIEW_CUSTOMERS);
            $view_url_all = tep_href_link(FILENAME_FSS_VIEW_CUSTOMERS, tep_get_all_get_params(array('filter', 'period', 'period_from', 'period_to')) . 'period_from=' . $period_from1 . '&period_to=' . $period_to1, 'SSL');
            $export_url = tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('action', 'filter', 'period', 'type', 'period_from', 'period_to')) . 'action=export_excel&type=customer&filter=' . urlencode($value['fields_value']) . '&period_from=' . $period_from . '&period_to=' . $period_to, 'SSL');
            $export_url_all = tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('action', 'filter', 'period', 'type', 'period_from', 'period_to')) . 'action=export_excel&type=customer&period_from=' . $period_from . '&period_to=' . $period_to, 'SSL');
            break;
          case 'orders_id':
            $view_url = tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('filter', 'period', 'period_from', 'period_to')) . 'filter=' . urlencode($value['fields_value']) . '&period_from=' . $period_from1 . '&period_to=' . $period_to1, 'SSL');
            $view_button = tep_image_button('button_view_orders.gif', IMAGE_VIEW_ORDERS);
            $view_url_all = tep_href_link(FILENAME_FSS_VIEW_ORDERS, tep_get_all_get_params(array('filter', 'period', 'period_from', 'period_to')) . 'period_from=' . $period_from1 . '&period_to=' . $period_to1, 'SSL');
            $export_url = tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('action', 'filter', 'period', 'type', 'period_from', 'period_to')) . 'action=export_excel&type=order&filter=' . urlencode($value['fields_value']) . '&period_from=' . $period_from . '&period_to=' . $period_to, 'SSL');
            $export_url_all = tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('action', 'filter', 'period', 'type', 'period_from', 'period_to')) . 'action=export_excel&type=order&period_from=' . $period_from . '&period_to=' . $period_to, 'SSL');
            break;
          default:
            break;
        }
?>
              <tr>
                <td width="50px"><?php echo tep_draw_radio_field('filter', $value['fields_value'], $checked, '', 'onclick=this.form.submit();'); ?></td>
                <td width="200px" class="main"><?php echo $value['fields_value']; ?></td>
                <td class="main" align="right"><?php echo $value['count']; ?></td>
                <td width="100px" class="main" align="right"><?php echo $value['percentage'] . '%'; ?></td>
                <td>&nbsp;&nbsp;<a href="<?php echo $view_url; ?>"><?php echo $view_button; ?></a> <a href="<?php echo $export_url; ?>"><?php echo tep_image_button('button_export_excel.gif', IMAGE_EXPORT_EXCEL); ?></a></td>
              </tr> 
<?php
      }
?>
              <tr>
                <td width="50px"><?php echo tep_draw_radio_field('filter', 'all', !isset($_GET['filter']), '', 'onclick=this.form.submit();'); ?></td>
                <td width="200px" class="main"><?php echo TEXT_ALL; ?></td>
                <td class="main" align="right"><?php echo TEXT_TOTAL_RECORDS . $total; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;&nbsp;<a href="<?php echo $view_url_all; ?>"><?php echo $view_button; ?></a> <a href="<?php echo $export_url_all; ?>"><?php echo tep_image_button('button_export_excel.gif', IMAGE_EXPORT_EXCEL); ?></a></td>
              </tr> 
            </form></td></table>
          </tr>
<?php
    }
?>
          <tr>
            <td>&nbsp;</td>
            <td class="main" colspan="2"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td width="120"><b><?php echo TEXT_DATE_STATUS; ?></b></td>
                <td><b><?php echo TEXT_POST_KEY; ?></b></td>
              </tr>
              <tr>
                <td><?php echo TEXT_TOTAL_RECORDS; ?></td>
                <td><?php echo $posts_total; ?></td>
              </tr>
<?php
    foreach ($post_status as $value) {
?>
              <tr>
                <td><?php echo $value['value']; ?></td>
                <td><?php echo isset($posts[$value['id']]) ? $posts[$value['id']] : '0'; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="main" colspan="2"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td width="120"><b><?php echo TEXT_DATE; ?></b></td>
                <td><b><?php echo TEXT_POSTS; ?></b></td>
              </tr>
<?php
    foreach ($posts1 as $key => $value) {
?>
              <tr>
                <td><?php echo $key; ?></td>
                <td><?php echo $value; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
<?php
  } else {
    $post_status = array();
    $post_status_query = tep_db_query("SELECT * FROM " . TABLE_FSS_FORMS_POSTS_STATUS);
    while ($post_status_array = tep_db_fetch_array($post_status_query)) {
      $post_status[] = array('id' => $post_status_array['forms_posts_status_id'],
                             'value' => $post_status_array['status_value']);
    }
    $posts_query = tep_db_query("SELECT posts_status_value, count(*) AS num FROM " . TABLE_FSS_FORMS_POSTS . " WHERE forms_id = '" . $forms_id . "'" . (tep_not_null($period_from) && tep_not_null($period_to) ? " AND posts_date BETWEEN '" . urldecode($period_from) . "' AND '" . urldecode($period_to) . "'" : '') . " GROUP BY posts_status_value");
    $posts = array();
    $posts_total = 0;
    while ($posts_data = tep_db_fetch_array($posts_query)) {
      $posts[$posts_data['posts_status_value']] = $posts_data['num'];
      $posts_total += $posts_data['num'];
    }
?>
          <tr>
            <td>&nbsp;</td>
            <td class="main" colspan="2"><img src="<?php echo tep_href_link('fss_report_chart.php', 'type=activity_overtime&period_from=' . $period_from . '&period_to=' . $period_to . '&forms_id=' . $forms_id . '&title=' . HEADING_TITLE_OVERTIME . $cross_reference); ?>" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="main" colspan="2"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td width="120"><b><?php echo TEXT_DATE_STATUS; ?></b></td>
                <td><b><?php echo TEXT_POST_KEY; ?></b></td>
              </tr>
              <tr>
                <td><?php echo TEXT_TOTAL_RECORDS; ?></td>
                <td><?php echo $posts_total; ?></td>
              </tr>
<?php
    foreach ($post_status as $value) {
?>
              <tr>
                <td><?php echo $value['value']; ?></td>
                <td><?php echo isset($posts[$value['id']]) ? $posts[$value['id']] : '0'; ?></td>
              </tr>
<?php
    }
?>
            </table></td>
          </tr>
<?php  
  
    
    if ( tep_fss_has_activity_data($forms_id) ) {
      $questions = tep_fss_get_questions($forms_id);
?>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="pageHeading" colspan="2"><?php echo HEADING_TITLE_ANSWER_STATISTICS . '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('cross_reference'))) . '">' . tep_image_button('button_clean_cross.gif', IMAGE_CLEAN_CROSS) . '</a>' ; ?></td>
          </tr>
<?php  
      echo tep_draw_form('cross_reference', FILENAME_FSS_REPORTS, '', 'get', '', 'SSL');
      foreach ($_GET as $key => $key_value) {
        if ($key != 'cross_reference') {
          echo tep_draw_hidden_field($key, $key_value);
        }
      }
      foreach ( $questions as $value ) {
        if ( $value['questions_type'] == 'Drop Down Menu' || $value['questions_type'] == 'Check Box' || $value['questions_type'] == 'Drop Down List' || $value['questions_type'] == 'Radio Button Group' ) {
?>
          <tr>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="main" width="100"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="mian" colspan="4"><b><?php echo $value['label']; ?></b></td>
                  </tr>
<?php
          $quenstions_values = tep_fss_get_values_percentage($value['id'], $period_from, $period_to, $_GET['cross_reference']);
          $total = 0;
          require(DIR_WS_INCLUDES . 'plchart/colors/default.color.php');
//          print_r($quenstions_values);die;
          foreach ($quenstions_values as $key => $value1) {
            $color = array_shift($colors);
            $total += $value1['count'];
?>
                  <tr>
                    <td nowrap class="main"><?php echo $value1['fields_value']; ?></td>
                    <td nowrap class="main" align="right"><?php echo tep_draw_radio_field('cross_reference', $value['id'] . '_' . $value1['fields_value'], ($_GET['cross_reference'] == $value['id'] . '_' . $value1['fields_value']), '', 'onClick="this.form.submit();"'); ?></td>
                    <td nowrap class="main" align="right"><?php echo $value1['count']; ?></td>
                    <td width="80" class="main" align="right"><?php echo $value1['percentage'] . '%'; ?></td>
                    <td>&nbsp;<img src="<?php echo tep_href_link('fss_report_chart.php', 'type=image_label&r=' . $color[0] . '&g=' . $color[1] . '&b=' . $color[2]); ?>" border="1" /></td>
                  </tr>
<?php
            $color = array_shift($colors);
          }
?>
                  <tr>
                    <td nowrap class="main"><b><?php echo TEXT_TOTAL_RECORDS; ?></b></td>
                    <td class="main" align="right"><b><?php echo $total; ?></b></td>
                    <td colspan="3">&nbsp;</td>
                  </tr> 
                  <tr>
                    <td class="main" colspan="5"><u><a href="<?php echo tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('cross_reference')) . 'questions_id=' . $value['id']); ?>"><?php echo tep_image_button('button_activity_over_time.gif', TEXT_LINK_OVERTIME_REPORT); ?></a></u></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td valign="top"><a href="<?php echo tep_href_link(FILENAME_FSS_REPORTS, tep_get_all_get_params(array('cross_reference')) . 'questions_id=' . $value['id']); ?>"><img src="<?php echo tep_href_link('fss_report_chart.php', 'type=answer_statistics&questions_id=' . $value['id'] . '&title=' . $value['label'] . '&period_from=' . $period_from . '&period_to=' . $period_to . $cross_reference); ?>" border="0" /></a></td>            
          </tr>
<?php
        }
      }
?>
          </from>
<?php
    }
  }
?>
        </table></td>
      </tr>
      <?php
      // RCI code start
      $cre_RCI->get('fssreports', 'bottom'); 
      $cre_RCI->get('global', 'bottom');                                        
      // RCI code eof
      ?>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>