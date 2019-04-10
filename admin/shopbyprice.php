<?php
/*
  $Id: shopbyprice.php,v 1.1.1.1 2004/03/04 23:39:00 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!defined('MODULE_SHOPBYPRICE_RANGES')) define('MODULE_SHOPBYPRICE_RANGES', 0);
  if (!defined('MODULE_SHOPBYPRICE_RANGE')) define('MODULE_SHOPBYPRICE_RANGE', '');
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $error_message = '';
  if (!isset($_GET['oID'])) {
    $oid = 1;
  } else {
    $oid = $_GET['oID'];
  }
  
 // define('MODULE_SHOPBYPRICE_RANGES', $sbp_ranges);
  if ($action == 'save') {
    if ($oid == 1) {
      $sbp_ranges = tep_db_prepare_input($_POST['sbp_ranges']);
      if (is_numeric($sbp_ranges)) {
        tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . (int)$sbp_ranges . '" where configuration_key = "MODULE_SHOPBYPRICE_RANGES" ');
        tep_redirect(tep_href_link(FILENAME_SHOPBYPRICE, tep_get_all_get_params(array('action'))));
        
      } else {
        $error_message .= TEXT_EDIT_ERROR_RANGES;
      }
      tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . $_POST['configuration_value'] . '" where configuration_key = "MODULE_SHOPBYPRICE_OVER" ');

      if ($error_message != '') {
        $action = 'edit';
      }
    } else {
      $sbp_input_array = $_POST['sbp_range'];
      $sbp_array[0] = tep_db_prepare_input($sbp_input_array[0]);
      for ($i = 1, $ii = MODULE_SHOPBYPRICE_RANGES; $i < $ii; $i++) {
        $sbp_array[$i] = tep_db_prepare_input($sbp_input_array[$i]);
        if (! is_numeric($sbp_array[$i])) {
          $error_message .= TEXT_EDIT_ERROR_NUMERIC;
        } elseif ($sbp_array[$i] <= $sbp_array[$i - 1]) {
          $error_message .= TEXT_EDIT_ERROR_RANGE;
        }
      }
      if ($error_message == '') {
        $serial_array = serialize($sbp_array);
        $text = tep_db_input($serial_array);
        tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . $text . '" where configuration_key = "MODULE_SHOPBYPRICE_RANGE" ');
        tep_redirect(tep_href_link(FILENAME_SHOPBYPRICE, tep_get_all_get_params(array('action'))));
        
      } else {
        $action = 'edit';
      }
    }
  }

  $sbp_array = unserialize(MODULE_SHOPBYPRICE_RANGE);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_OPTIONS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if ($oid == 1) {
    echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1&action=edit') . '\'">' . "\n";
  } else {
    echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '\'">' . "\n";
  }
?>
                <td class="dataTableContent"><?php echo TEXT_INFO_OPTION_1; ?></td>
                <td class="dataTableContent" align="right"><?php if ($oid == 1) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  if ($oid == 2) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2') . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo TEXT_INFO_OPTION_2; ?></td>
                <td class="dataTableContent" align="right"><?php if ($oid == 2) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2') . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      if ($oid == 1) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_OPTIONS . '</b>');
        $contents = array('form' => tep_draw_form('sbp_options', FILENAME_SHOPBYPRICE, 'oID=1&action=save'));
        if ($error_message != '') {
          $contents[] = array('text' => '<font color="red">' . $error_message . '</font>');
        }
        $contents[] = array('text' => TEXT_EDIT_OPTIONS_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_RANGES . '<br>' . tep_draw_input_field('sbp_ranges', MODULE_SHOPBYPRICE_RANGES));
        $contents[] = array('text' => '<br>' . TEXT_INFO_OVER . '<br>' . tep_cfg_select_option(array('True', 'False'),MODULE_SHOPBYPRICE_OVER));
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
      } elseif (MODULE_SHOPBYPRICE_RANGES > 0) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_RANGE . '</b>');
        $contents = array('form' => tep_draw_form('sbp_options', FILENAME_SHOPBYPRICE, 'oID=2&action=save'));
        if ($error_message != '') {
          $contents[] = array('text' => '<font color="red">' . $error_message . '</font>');
        }
        $contents[] = array('text' => TEXT_EDIT_RANGE_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_UNDER . tep_draw_input_field('sbp_range[0]', $sbp_array[0]));
        for ($i = 1, $ii = MODULE_SHOPBYPRICE_RANGES; $i < $ii; $i++) {
          $contents[] = array('text' => '<br>' . TEXT_INFO_TO . tep_draw_input_field('sbp_range['.$i.']', $sbp_array[$i]));
        }
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
      }
      break;
    default:

      if ($oid == 1) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_OPTIONS . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_RANGES . ' <b>' . MODULE_SHOPBYPRICE_RANGES . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_OVER . ' <b>' . MODULE_SHOPBYPRICE_OVER . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_OPTIONS_DESCRIPTION . '<br><b>' . $tcInfo->tax_class_description . '</b>');
      } else {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_RANGE . '</b>');
        if (! MODULE_SHOPBYPRICE_RANGES > 0) {
          $contents[] = array('align' => 'center', 'text' => TEXT_INFO_ZERORANGE);
        } elseif (MODULE_SHOPBYPRICE_RANGE == '') {
          $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');
          $contents[] = array('align' => 'center', 'text' => TEXT_INFO_NORANGE);
        } else {
          $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');
          $contents[] = array('text' => '<br>' . TEXT_INFO_UNDER . '<b>' . $sbp_array[0] . '</b>');
          for ($i = 1, $ii = count($sbp_array); $i < $ii; $i++) {
            $contents[] = array('text' => '<br>' . TEXT_INFO_TO . '<b>' . $sbp_array[$i] . '</b>');
          }
          if (MODULE_SHOPBYPRICE_OVER) {
            $contents[] = array('text' => '<br><b>' . $sbp_array[$i-1] . '</b>' . TEXT_INFO_ABOVE);
          }
        }
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
