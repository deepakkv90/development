<?php
/*
  $Id: cds_configuration.php,v 1.1.1.1 2007/01/11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (tep_not_null($action)) {
  switch ($action) {
    case 'save':
      $configuration_value = tep_db_prepare_input($_POST['configuration_value']);
      $cID = tep_db_prepare_input($_GET['cID']);
      if (is_array($configuration_value)) {
        $configuration_value_new = '';
        foreach ($configuration_value as $value) {
          $configuration_value_new .= $value . ', ';
        }
        $configuration_value_new = substr($configuration_value_new, 0, strlen($configuration_value_new) - 2);
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value_new) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");
      } else {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");
      }
      tep_redirect(tep_href_link(FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cID));
      break;
  }
}
$gID = (isset($_GET['gID'])) ? (int)$_GET['gID'] : 1;
$cfg_group_query = tep_db_query("SELECT configuration_group_title
                                   from " . TABLE_CONFIGURATION_GROUP . " 
                                          WHERE configuration_group_id = '" . (int)$gID . "'");
$cfg_group = tep_db_fetch_array($cfg_group_query);
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
<?php include('includes/javascript/editor.php');?>
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAGE_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAGE_VALUE; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_PAGE_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $configuration_query = tep_db_query("SELECT configuration_id, configuration_title, configuration_value, use_function 
                                                     from " . TABLE_CONFIGURATION . " 
                                                   WHERE configuration_group_id = '" . (int)$gID . "' 
                                                             ORDER BY sort_order");
              while ($configuration = tep_db_fetch_array($configuration_query)) {
                if (tep_not_null($configuration['use_function'])) {
                  $use_function = $configuration['use_function'];
                  if (ereg('->', $use_function)) {
                    $class_method = explode('->', $use_function);
                    if (!is_object(${$class_method[0]})) {
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
                  $cfg_extra_query = tep_db_query("SELECT configuration_key, configuration_description, date_added, last_modified, use_function, set_function 
                                                      from " . TABLE_CONFIGURATION . " 
                                                               WHERE configuration_id = '" . (int)$configuration['configuration_id'] . "'");
                  $cfg_extra = tep_db_fetch_array($cfg_extra_query);
                  $cInfo_array = array_merge($configuration, $cfg_extra);
                  $cInfo = new objectInfo($cInfo_array);
                } 
                                if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {
                  if ($cInfo->set_function == 'file_upload'){
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=upload') . '\'">' . "\n";
                  } else {
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '\'">' . "\n";
                  }
                } else {
                  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
                }
                ?>
                <td class="dataTableContent"><?php echo $configuration['configuration_title']; ?></td>
                <td class="dataTableContent"><?php echo htmlspecialchars($cfgValue); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
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
               $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');
               if ($cInfo->set_function) {
                 eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
                              } else {
                                $value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
                              }
                              $contents = array('form' => tep_draw_form('configuration', FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=save'));
                              $contents[] = array('text' => '<br><b>' . $cInfo->configuration_title . '</b><br>' . $cInfo->configuration_description . '<br>' . $value_field);
                              $contents[] = array('align' => 'center', 'text' => '<br>' .tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                            break;
                              
                        default:
                          if (isset($cInfo) && is_object($cInfo)) {
                              $heading[] = array('text' => '<b>' . $cInfo->configuration_title . '</b>');
                                if ($cInfo->set_function == 'file_upload') {
                                $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=upload') . '">' . tep_image_button('button_upload.gif', IMAGE_EDIT) . '</a>'.'<p>');
                                  $contents[] = array('align' => 'center', 'text' => tep_image($upload_ws_dir . $cInfo->configuration_value, IMAGE_EDIT));
                              } else {
                                  $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CDS_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
                              }  
                              $contents[] = array('text' => '<br>' . $cInfo->configuration_description);
                              $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
                            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
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