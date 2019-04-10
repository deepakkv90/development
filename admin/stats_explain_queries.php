<?php
/*-----------------------------------------------------------------------------*\
#################################################################################
# Script name: admin/stats_explain_queries.php
# Version: v1.0
#
# Copyright (C) 2005 Bobby Easland
# Internet moniker: Chemo
# Contact: chemo@mesoimpact.com
#
# This script is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# Script is intended to be used with:
# osCommerce, Open Source E-Commerce Solutions
# http://www.oscommerce.com
# Copyright (c) 2003 osCommerce
################################################################################
\*-----------------------------------------------------------------------------*/

  require('includes/application_top.php');
  
/* Set it false initially */
$run_it = false;
  
/* function used to download the CVS export */
function file_download($filecontents, $filename)
{ 
  header('Content-Type: application/octet-stream');
  header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  echo $filecontents; 
  exit();
}

if ( isset($_POST['truncate']) && $_POST['truncate'] == 'true' ) {
  tep_db_query('TRUNCATE TABLE `explain_queries`');
}

if ( isset($_POST['analyze']) && $_POST['analyze'] == 'true' ) {
  tep_db_query('ANALYZE TABLE `explain_queries`');
}

$limit = ( (isset($_GET['limit'])) ? (int)$_GET['limit'] : 20);
$offset = ( (isset($_GET['offset'])) ? (int)$_GET['offset'] : 0);

if ( isset($_GET['script']) && tep_not_null($_GET['script']) ){
  $type = 'script';
  $_query_raw = "SELECT `md5query` , `query` , `time` , `script` , `request_string` , `table` , `type` , `possible_keys` , `key` , `key_len` , `ref` , `rows` , `Extra` , `Comment`, avg(time) as average, count(md5query) as num_records, min(time) as min, max(time) as max from explain_queries where script='".$_GET['script']."' GROUP BY md5query ORDER BY average DESC limit $offset, $limit";
  $run_it = true;
}

if (isset($_GET['query']) && tep_not_null($_GET['query']) ){
  $type = 'query';
  $_query_raw = "SELECT `md5query` , `query` , `time`, `script` , `request_string` , `table` , `type` , `possible_keys` , `key` , `key_len` , `ref` , `rows` , `Extra` , `Comment`, avg(time) as average, count(md5query) as num_records, min(time) as min, max(time) as max from explain_queries WHERE md5query='".$_GET['query']."' GROUP BY script ORDER BY average DESC limit $offset, $limit";
  $run_it = true;
}

if (isset($_POST['format'])){
  switch($_POST['format']){
    case 'html':
      $html_out = true;
    break;
    case 'cvs':
    default:
      $cvsfields = array('time','script','request_string','table','type','possible_keys','key','key_len','ref','rows','Extra','Comment','query');
      $cvsoutput = implode("\t", $cvsfields) . "\n"; 
      $cvs_out = true;
    break;
  } # End switch
}

if ($run_it){
  $_query = tep_db_query($_query_raw);

  while ($temp = tep_db_fetch_array($_query)){
    if (tep_not_null($temp['Extra'])) $temp['Extra'] = '<font color="#990000">'. $temp['Extra'] . '</font>';
    if (tep_not_null($temp['Comment'])) $temp['Comment'] = '<font color="#990000">'. $temp['Comment'] . '</font>';
    $_result[]=$temp;
    if ($cvs_out){
      foreach ($cvsfields as $index => $field){
        $temp_cvs[] = $temp[$field];
      } # End foreach
      $cvsoutput .= implode("\t", $temp_cvs)."\n";
      unset($temp_cvs);
    } # End if
  } # End while
} # End if

/* This is a CVS download so let 'em have it */
if ($cvs_out){
  file_download(rtrim($cvsoutput), str_replace('.php', '.txt', $_GET['script']));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><!-- Explain Queries v1.0 - by Chemo --><?php echo STATS_EXPLAIN_QUERIES_TXT_1;?></title>
<script type="text/javascript" src="includes/prototype.js"></script> 
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
/* if this is an html export start the buffer to swallow the header and column left */
if ($html_out) ob_start(); 
require(DIR_WS_INCLUDES . 'header.php'); 
?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
  <br>
<!-- truncate and analyze button //-->
<div align="center">
  <form action="" method="post">
    <input type="hidden" name="truncate" value="true">
    <input type="submit" value="Truncate Table">
  </form>
</div>
  <br>
<div align="center">
  <form action="" method="post">
    <input type="hidden" name="analyze" value="true">
    <input type="submit" value="Analyze Table">
  </form>
</div>
<!--  truncate and analyze button end //--> 
<!-- body_text //-->
    <td valign="top" class="page-container">
  <!--  page heading and export format forum //-->
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
          <td class="pageHeading" align="left"><!-- Explain Queries Report v1.0 <a href="http://forums.oscommerce.com/index.php?showuser=9196">- by Chemo</a> --> <?php echo STATS_EXPLAIN_QUERIES_TXT_2;?></td>
            <td align="right">
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?' .tep_get_all_get_params(); ?>" method="post">
        <table border="0" width="100%" cellspacing="0" cellpadding="0" >
          <tr>
            <td class="dataTableContent" align="right"><b><!-- Export Format --><?php echo STATS_EXPLAIN_QUERIES_TXT_3;?></b></td><td>&nbsp;</td>
          </tr>
          <tr>
            <td class="dataTableContent" align="right"><!-- CVS --> <?php echo STATS_EXPLAIN_QUERIES_TXT_4;?> <input type="radio" name="format" value="cvs" checked><!-- HTML --> <?php echo STATS_EXPLAIN_QUERIES_TXT_5;?><input type="radio" name="format" value="html"></td>
            <td align="right"><input type="submit" value="Export"></td>
          </tr>
        </table>
        </form>
      </td>
    </tr>
  </table>
  <!-- page heading and export format end //-->
  <!-- script drop down menu filter and limit - offset //-->
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
    <td class="dataTableContent">
    <form action="" method="get">
<?php
/* Query for total scripts, number of unique queries, and total queries stored for the script */
$pages_query = tep_db_query('select script, count(distinct md5query) as count, count(*) as total from explain_queries group by script');
/* Start the drop down menu */
$page_menu = '<select name="script">';
while ($temp = tep_db_fetch_array($pages_query)){
  $pages_indexed[] = $temp['script'];
  $selected = ( ($_GET['script'] == $temp['script']) ? ' selected' : '' );
    $page_menu .= '<option value="' . $temp['script'] . '"'.$selected.'>' . $temp['script'] . ' ('.$temp['count'].' / '. $temp['total'].')</option>';
}
$page_menu .= '</select>';
echo $page_menu;
?>
&nbsp;<!-- Limit to --><?php echo STATS_EXPLAIN_QUERIES_TXT_6;?>&nbsp;<input type="text" size="3" maxlength="3" name="limit" value="<?php echo ( (isset($_GET['limit'])) ? $_GET['limit'] : '20'); ?>">
&nbsp;<!-- rows starting with row# --><?php echo STATS_EXPLAIN_QUERIES_TXT_7;?>&nbsp;<input type="text" size="3" maxlength="3" name="offset" value="<?php echo ( (isset($_GET['offset'])) ? $_GET['offset'] : '0'); ?>">
      <input type="submit">
      </form>
      </td>
    </tr>
  </table>
  <!-- script drop down filter and limit - offset end //-->
  <br>
<?php 
if ($html_out) {
  /* End the buffer if this is an html export */
  ob_end_clean(); 
  /* Give a link back to the page so they won't be stranded */
  echo '<p><a href="'.$_SERVER['PHP_SELF'].'">'.STATS_EXPLAIN_QUERIES_TXT_8.'</a><p>';
}
?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td valign="top" class="dataTableContent">
        <?php echo sizeof($_result); ?> <!-- results returned for: --> <?php echo STATS_EXPLAIN_QUERIES_TXT_9;?> <b><?php echo $type . '=' . $_GET[$type]; ?></b>
      </td>
    </tr>
  </table>
  <br>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
    <td valign="top">     
<?php
/* If we have a result set to work with let's make some pretty tables */
if (isset($_result)){
  $i = $offset+1;
  /* Loop it */
  foreach($_result as $data){
?>
  <!-- data table for query <?php echo $i; ?> //-->
  <table border="1" width="100%" cellspacing="0" cellpadding="3">
    <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent"><font color="#990000"><!-- Query# --><?php echo STATS_EXPLAIN_QUERIES_TXT_10;?>&nbsp;<?php echo $i; ?> =></font>&nbsp <a href="<?php echo basename($_SERVER['PHP_SELF']).'?query='.$data['md5query'] ?>"><?php echo $data['query']; ?></a></td>
    </tr>
  </table>
  <table border="1" width="100%" cellspacing="0" cellpadding="2">
    <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
      <td class="dataTableContent" align="left" valign="top"><!-- Time (ms):  --><?php echo STATS_EXPLAIN_QUERIES_TXT_11;?><?php echo number_format($data['min'], 3) .' - <b>'.number_format($data['average'], 3).'</b> - '.number_format($data['max'], 3); ?></td>
      <td class="dataTableContent" align="left" valign="top"><!-- # Records: --> <?php echo STATS_EXPLAIN_QUERIES_TXT_12;?><b><?php echo $data['num_records']; ?></b></td>
      <td class="dataTableContent" align="left" valign="top"><!-- Script:  --> <?php echo STATS_EXPLAIN_QUERIES_TXT_13;?><b><?php echo $data['script']; ?></b></td>
      <td class="dataTableContent" align="left" valign="top"><!-- Request String: --> <?php echo STATS_EXPLAIN_QUERIES_TXT_14;?> <b><?php echo $data['request_string']; ?></b></td>
    </tr>
    <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
      <td class="dataTableContent" align="left" valign="top"><!-- Table:  --><?php echo STATS_EXPLAIN_QUERIES_TXT_15;?><b><?php echo $data['table']; ?></b></td>
      <td class="dataTableContent" align="left" valign="top"><!-- Type:  --><?php echo STATS_EXPLAIN_QUERIES_TXT_16;?><b><?php echo $data['type']; ?></b></td>
      <td class="dataTableContent" align="left" valign="top"><!-- Possible / Used Key:  --><?php echo STATS_EXPLAIN_QUERIES_TXT_17;?><b><?php echo $data['possible_keys'].' / '.$data['key']; ?></b></td>
      <td class="dataTableContent" align="left" valign="top"><!-- Key Len: --> <?php echo STATS_EXPLAIN_QUERIES_TXT_18;?> <b><?php echo $data['key_len']; ?></b></td>
    </tr>
    <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
      <td class="dataTableContent" align="left" valign="top"><!-- Rows:  --> <?php echo STATS_EXPLAIN_QUERIES_TXT_19;?><b><?php echo $data['rows']; ?></b></td>
      <td class="dataTableContent" align="left" valign="top"><!-- Ref:  --><?php echo STATS_EXPLAIN_QUERIES_TXT_20;?><b><?php echo $data['ref']; ?></b></td>
      <td class="dataTableContent" align="left" valign="top"><!-- Extra:  --><?php echo STATS_EXPLAIN_QUERIES_TXT_21;?><b><?php echo $data['Extra']; ?></b></td>
      <td class="dataTableContent" align="left" valign="top"><!-- Comment: --> <?php echo STATS_EXPLAIN_QUERIES_TXT_22;?><b><?php echo $data['Comment']; ?></b></td>
    </tr>
  </table>
  <!-- end data table for query <?php echo $i; ?> //-->
  <br>
<?php
$i++;
  }
}
/* unset the array since it may be rather large */
unset($_result);
?>      
      </td>
    </tr>
  </table>
      </td>
    </tr>
  </table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>