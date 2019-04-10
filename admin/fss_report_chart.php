<?php
/*
  $Id: fss_report_chart.php,v 1.0.0.0 2008/06/19 23:39:49 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_INCLUDES . 'plchart/class.plchart.php');
require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FSS_REPORTS);
$type = isset($_GET['type']) ? $_GET['type'] : '';
$forms_id = isset($_GET['forms_id']) ? $_GET['forms_id'] :'';
$period_from = isset($_GET['period_from']) ? $_GET['period_from'] : '';
$period_to = isset($_GET['period_to']) ? $_GET['period_to'] : '';
$questions_id = isset($_GET['questions_id']) ? $_GET['questions_id'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$filter = urldecode(isset($_GET['filter']) ? $_GET['filter'] : '');
$r= isset($_GET['r']) ? $_GET['r'] : '';
$g= isset($_GET['g']) ? $_GET['g'] : '';
$b= isset($_GET['b']) ? $_GET['b'] : '';
$cross_reference = urldecode(isset($_GET['cross_reference']) ? $_GET['cross_reference'] : '');
switch ($type) {
  case 'activity_overtime':
    $ret = tep_fss_get_form_overtime_report($forms_id, $period_from, $period_to, $cross_reference);    
    if ( sizeof($ret['data']) > 0 ) {
      $report = new plchart($ret['data'], 'line_single', 770, 250);
      $report->set_title($title, 15);
      $report->set_color(array(255, 255, 255), 'line_scatter');
      $report->set_scale($ret['y'], $ret['x']);
      $report->set_desc();
      $report->set_graph(10, 30, 690, 200, 0);
      $report->output();
    } else {
      echo tep_fss_no_data_img(TEXT_NO_DATA);
    }
    break; 
  case 'answer_statistics':
    $ret = tep_fss_get_question_report($questions_id, $period_from, $period_to, $cross_reference); 
    if ( sizeof($ret['data']) > 0 ) {
      $report = new plchart($ret['data'], 'pie_3d', 200, 120);
      $report->set_color(array(255, 255, 255));
      $report->set_title('', 0, 0, 0, 0);
      $report->set_desc();
      $report->set_graph(10, 10, 180, 80, 0.2);
      $report->output();
    } else {
      echo tep_fss_no_data_img(TEXT_NO_DATA);
    }
    break;
  case 'activity_overtime_questions':
    $ret = tep_fss_get_question_overtime_report($questions_id, $period_from, $period_to, $filter);
    if ( sizeof($ret['data']) > 0 ) {
      $report = new plchart($ret['data'], 'line_multiple', 770, 250);
      $report->set_color(array(255, 255, 255), 'line_scatter');
      $report->set_title($title, 15);
      $report->set_scale($ret['y'], $ret['x']);
      $report->set_desc(220);
      $report->set_graph(10, 30, 690, 200, 0);
      $report->output();
    } else {
      echo tep_fss_no_data_img(TEXT_NO_DATA);
    }
    break;
  case 'image_label':
    echo tep_fss_image_label($r, $g, $b);
    break;
}
?>