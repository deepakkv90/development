<?php

require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/index.php');

if (isset($_POST['action']) && $_POST['action'] == 'save') {
  $approved = '';
  foreach ($_POST['ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP'] as $value) {
    $approved .= $value . ', ';
  }
  $approved = substr($approved, 0, strlen($approved) - 2);
  tep_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $approved . "' WHERE configuration_key = 'ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP'");
  if (isset($_POST['ADMIN_BLOCKS_OT_SHOW_LAST_MONTH']) && $_POST['ADMIN_BLOCKS_OT_SHOW_LAST_MONTH'] == 'true') {
    $show_last_month = 'true';
  } else {
    $show_last_month = 'false';
  }
  tep_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $show_last_month . "' WHERE configuration_key = 'ADMIN_BLOCKS_OT_SHOW_LAST_MONTH'");
  if (isset($_POST['ADMIN_BLOCKS_OT_SHOW_YTD']) && $_POST['ADMIN_BLOCKS_OT_SHOW_YTD'] == 'true') {
    $show_ytd = 'true';
  } else {
    $show_ytd = 'false';
  }
  tep_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $show_ytd . "' WHERE configuration_key = 'ADMIN_BLOCKS_OT_SHOW_YTD'");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<!-- code related to index.php only -->
<link type="text/css" rel="StyleSheet" href="includes/index.css" />
<!-- code related to index.php EOF -->
<?php
if (isset($_POST['action']) && $_POST['action'] == 'save') {
?>
<script type="text/javascript"><!--
parent.location.href='<?php echo tep_href_link(FILENAME_DEFAULT)?>';
this.close();
--></script>
<?php
}
?>
</head>
<body>
  <br>
  <ul class="ul_index">
    <li><?php echo BLOCK_CONTENT_OT_APPROVED_ORDERS; ?>
    <br>
<?php
    echo tep_draw_form('admin_block', 'index_block_preference.php', '', 'post', '', 'SSL');
    $orders_array = explode(',', ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP);
    $orders_status_query = tep_db_query("SELECT orders_status_id, orders_status_name FROM " . TABLE_ORDERS_STATUS . " WHERE language_id = '" . $_SESSION['languages_id'] . "'");
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
      if (in_array($orders_status['orders_status_id'], $orders_array)) {
        $checked = true;
      } else {
        $checked = false;
      }
      echo '&nbsp;&nbsp;&nbsp;' . tep_draw_checkbox_field('ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP[]', $orders_status['orders_status_id'], $checked) . '&nbsp;' . $orders_status['orders_status_name'] . '<br>';
    }
?>
    </li>
    <li>
<?php 
    echo BLOCK_CONTENT_OT_SHOW_LAST_MONTH; 
    if (strtolower(ADMIN_BLOCKS_OT_SHOW_LAST_MONTH) == 'true') {
      $checked = true;
    } else {
      $checked = false;
    }
    echo '&nbsp;' . tep_draw_checkbox_field('ADMIN_BLOCKS_OT_SHOW_LAST_MONTH', 'true', $checked);
?>    
    </li>
    <li>
<?php 
    echo BLOCK_CONTENT_OT_SHOW_YTD; 
    if (strtolower(ADMIN_BLOCKS_OT_SHOW_YTD ) == 'true') {
      $checked = true;
    } else {
      $checked = false;
    }
    echo '&nbsp;' . tep_draw_checkbox_field('ADMIN_BLOCKS_OT_SHOW_YTD', 'true', $checked);
?>    
    </li>
  </ul>
  <input type="hidden" name="action" value="save">
  <p align="right"><input type="submit" name="submit" value="Save">&nbsp;</p>
  </form>
</body>
</html>