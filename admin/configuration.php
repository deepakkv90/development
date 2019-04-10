<?php
/*
  $Id: configuration.php,v 2.0 2008/05/05 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
Header("Cache-control: private, no-cache");
Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); # Past date
Header("Pragma: no-cache");
require('includes/application_top.php');

  // RCI for global and individual top
  echo $cre_RCI->get('global', 'top', false);
  echo $cre_RCI->get('configuration', 'top', false); 
  
// local dir to the template directory where you are uploading the company logo
$template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
$template = tep_db_fetch_array($template_query);
$CURR_TEMPLATE = $template['configuration_value'] . '/';
$upload_fs_dir = DIR_FS_TEMPLATES.$CURR_TEMPLATE.DIR_WS_IMAGES;
$upload_ws_dir = DIR_WS_TEMPLATES.$CURR_TEMPLATE.DIR_WS_IMAGES;

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (tep_not_null($action)) {
  switch ($action) {
    case 'save':
      $configuration_value = tep_db_prepare_input($_POST['configuration_value']);
      $cID = tep_db_prepare_input($_GET['cID']);
      $error = false;
      $configuration_key = tep_db_prepare_input($_POST['configuration_key']);
      // check if configuration key is admin session lifetime and greater than zero
      if ($configuration_key == 'MYSESSION_LIFETIME') {
        if ((int)$configuration_value < 60) {
          $error = true;
          $messageStack->add(CONFIG_ADMIN_SESSION_ERROR, 'error');
        }
      }
      if ($error == false) {
        if (is_array($configuration_value)) {
          $configuration_value_new = '';
          foreach ($configuration_value as $value) {
            $configuration_value_new .= $value . ',';
          }
          $configuration_value_new = substr($configuration_value_new, 0, strlen($configuration_value_new) - 1);//B# 3836
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value_new) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");
        } else {
          tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");
        }
        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cID));
      }
      break;
  }
}
$gID = (isset($_GET['gID'])) ? $_GET['gID'] : 1;
$cfg_group_query = tep_db_query("select configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$gID . "'");
$cfg_group = tep_db_fetch_array($cfg_group_query);
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
<script language="javascript" src="includes/javascript/color_picker/picker-dec.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
        <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo $cfg_group['configuration_group_title']; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "' order by sort_order");
              while ($configuration = tep_db_fetch_array($configuration_query)) {
                if (tep_not_null($configuration['use_function'])) {
                  $use_function = $configuration['use_function'];
                  if (ereg('->', $use_function)) {
                    $class_method = explode('->', $use_function);
                    if (!isset(${$class_method[0]}) || !is_object(${$class_method[0]})) {
                      include(DIR_WS_CLASSES . $class_method[0] . '.php');
                      ${$class_method[0]} = new $class_method[0]();
                    }
                    $cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
                  } else {
                    $cfgValue = tep_call_function($use_function, $configuration['configuration_value']);
                  }
                } else {
                  $cfgValue = $configuration['configuration_value'];
                }
                if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $configuration['configuration_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                  $cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
                  $cfg_extra = tep_db_fetch_array($cfg_extra_query);
                  $cInfo_array = array_merge((array)$configuration, (array)$cfg_extra);
                  $cInfo = new objectInfo($cInfo_array);
                }
                if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
                  if($cInfo->set_function == 'file_upload'){
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=upload') . '\'">' . "\n";
                  } else {
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '\'">' . "\n";
                  }
                } else {
                  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
                }
                ?>
                <td class="dataTableContent"><?php echo $configuration['configuration_title']; ?></td>
                <td class="dataTableContent"><?php 
                  if ($_GET['gID']== '450' && $configuration['configuration_title'] == 'Download Order Statuses') {
                    $s1 = tep_db_query("SELECT * FROM `orders_status` WHERE `orders_status_id` IN ( ".htmlspecialchars($cfgValue)." ) and language_id = '".$languages_id."'");
                    $s2 = '';
                    while($r1 = tep_db_fetch_array($s1)) {
                      $s2 .= $r1['orders_status_name']. ", ";
                    }
                    echo substr($s2,0,strlen($s2)-2);
                  } else {
                    echo htmlspecialchars($cfgValue); 
                  }
                ?></td>
                <td class="dataTableContent" align="right"><?php if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
              <?php
              }
              ?>
            </table></td>
            <?php
            $heading = array();
            $contents = array();

            switch ($action) {
              case 'edit':
                $heading[] = array('text' => $cInfo->configuration_title);
                if ($cInfo->set_function) {
                  eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
                } else {
                  $value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
                }
                $contents = array('form' => tep_draw_form('configuration', FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=save'));
                $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
                $contents[] = array('text' => tep_draw_hidden_field('configuration_key', $cInfo->configuration_key) . '<br><b>' . $cInfo->configuration_title . '</b><br>' . $cInfo->configuration_description . '<br>' . $value_field); // VJ admin session changed
                $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
                break;
                
              default:
                if (isset($cInfo) && is_object($cInfo)) {
                  $heading[] = array('text' => $cInfo->configuration_title);
                  if ($cInfo->set_function == 'file_upload') {
                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=upload') . '">' . tep_image_button('button_upload.gif', IMAGE_EDIT) . '</a>');
                    $contents[] = array('align' => 'center', 'text' => tep_image($upload_ws_dir . $cInfo->configuration_value, IMAGE_EDIT));
                  } else {
                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');
                  }
                  $contents[] = array('text' => $cInfo->configuration_description);
                  if ($cInfo->configuration_id == 6) {
                    $a = tep_db_query("select configuration_value from configuration where configuration_id='".$cInfo->configuration_id."' ");
                    $result = tep_db_fetch_array($a);
                    $value = $result['configuration_value'];

                    $s = tep_db_query("select * from zones where zone_id='".$value."' ");
                    $result1 = tep_db_fetch_array($s);
                    $s_zone_name = $result1['zone_name'];
                    $contents[] = array('text' => '<b>' . $s_zone_name.'</b>');
                  } else {
                  $contents[] = array('text' => '<b>' . $cInfo->configuration_value.'</b>');
                  }
                  $contents[] = array('text' => TEXT_INFO_DATE_ADDED . ' <b>' . tep_date_short($cInfo->date_added) . '</b>');
                  if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' <b>' . tep_date_short($cInfo->last_modified) . '</b>');
                }
                break;
            }
            if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
              echo '<td width="25%" valign="top">' . "\n";
              $box = new box;
              echo $box->infoBox($heading, $contents);
              echo '</td>' . "\n";
            }
            ?>
          </tr>
        </table></td>
      </tr>
      <!-- // for link on free shipping module --> 
      <script language="javascript">
      <!--
        function free_shipping_module_page() {
          window.location = "<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=shipping&module=freeshipper', 'SSL')); ?>";
        }
        -->
      </script>      
      <?php
      // RCI for global and individual bottom
      echo $cre_RCI->get('configuration', 'bottom'); 
      echo $cre_RCI->get('global', 'bottom');  
      ?>
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