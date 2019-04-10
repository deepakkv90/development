<?php
/*
  $Id: vendor_modules.php,v 1.1 2008/06/22 22:50:52 datazen Exp $

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$vendors_id = (isset($_GET['vendors_id']) ? $_GET['vendors_id'] : 'a');
if (tep_not_null($action)) {
  switch ($action) {
    case 'save':
      while (list($key, $value) = each($_POST['configuration'])) {
        if( is_array( $value ) ){
          $value = implode( ", ", $value);
          $value = ereg_replace (", --none--", "", $value);
        }      
        tep_db_query("update " . TABLE_VENDOR_CONFIGURATION . " set configuration_value = '" . $value . "' where configuration_key = '" . $key .  "' and vendors_id = '" . $vendors_id . "'");
      }
      tep_redirect(tep_href_link(FILENAME_VENDOR_MODULES, 'module=' . $_GET['module'] . '&vendors_id=' . $vendors_id));
      break;
    case 'install':
    case 'remove':
      $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
      $class = basename($_GET['module']);
      if (file_exists(DIR_FS_CATALOG_MODULES . 'vendors_shipping/' . $class . $file_extension)) {
        include(DIR_FS_CATALOG_MODULES . 'vendors_shipping/' . $class . $file_extension);
        $module = new $class;
        if ($action == 'install') {
          $module->install($vendors_id);  
        } elseif ($action == 'remove') {
          $module->remove($vendors_id); 
        }
      }
      tep_redirect(tep_href_link(FILENAME_VENDOR_MODULES, 'module=' . $class . '&vendors_id=' . $vendors_id));
      break;
  }
}
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
  <tr>  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
  <!-- body_text //-->
  <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_MODULES_SHIPPING; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main">
          <?php
          $vendors_query = tep_db_query("select * from " . TABLE_VENDORS . " where vendors_id = '" . $vendors_id . "' order by vendors_name");
          $vendor_info = tep_db_fetch_array($vendors_query);
          $vendors_name = $vendor_info['vendors_name'];
          if ($vendors_id == 'a') {
            echo "<div align=\"center\">" . TEXT_NO_VENDOR_SELECTED . '&nbsp;<a href="' . tep_href_link(FILENAME_VENDORS) . '">' . tep_image_submit('button_back.gif', IMAGE_BACK). '</a><br><br></div>';
          } else {
            echo CURRENTLY_MANAGING . $vendors_name . CURRENTLY_MANAGING_2 . "<a href='" . tep_href_link(FILENAME_VENDORS) . "'>" . CURRENTLY_MANAGING_3 . "</a>";
          }
          ?>
        </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
              $directory_array = array();
              if ($dir = @dir(DIR_FS_CATALOG_MODULES . 'vendors_shipping/')) {
                while ($file = $dir->read()) {
                  if (!is_dir(DIR_FS_CATALOG_MODULES . 'vendors_shipping/' . $file)) {
                    if (substr($file, strrpos($file, '.')) == $file_extension) {
                      $directory_array[] = $file;
                    }
                  }
                }
                sort($directory_array);
                $dir->close();
              }
              $installed_modules = array();
              for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
                $file = $directory_array[$i];
                include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/vendors_shipping/' . $file);
                include(DIR_FS_CATALOG_MODULES . 'vendors_shipping/' . $file);
                $class = substr($file, 0, strrpos($file, '.'));
                if (tep_class_exists($class)) {
                  $module = new $class;
                  if ($module->check($vendors_id) > 0) {
                    if ($module->sort_order($vendors_id) > 0) {
                      $installed_modules[$module->sort_order($vendors_id)] = $file;
                    } else {
                      $installed_modules[] = $file;
                    }
                  }
                  $module->zones($vendors_id);
                  if ((!isset($_GET['module']) || (isset($_GET['module']) && ($_GET['module'] == $class))) && !isset($mInfo)) {
                    $module_info = array('code' => $module->code,
                                         'title' => $module->title,
                                         'description' => $module->description,
                                         'status' => $module->check($vendors_id));
                    $module_keys = $module->keys($vendors_id);
                    $keys_extra = array();
                    for ($j=0, $k=sizeof($module_keys); $j<$k; $j++) {
                      $key_value_query_string = "select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = '" . $module_keys[$j] . "'";
                      $key_value_query = tep_db_query("select configuration_title, configuration_value, configuration_description, use_function, set_function from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = '" . $module_keys[$j] . "'");
                      $key_value = tep_db_fetch_array($key_value_query);
                      $keys_extra[$module_keys[$j]]['title'] = $key_value['configuration_title'];
                      $keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'];
                      $keys_extra[$module_keys[$j]]['description'] = $key_value['configuration_description'];
                      $keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'];
                      $keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'];
                    }
                    $module_info['keys'] = $keys_extra;
                    $mInfo = new objectInfo($module_info);
                  }
                  if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code) ) {
                    if ($module->check($vendors_id) > 0) {
                      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR_MODULES, 'module=' . $class . '&action=edit&vendors_id=' . $vendors_id) . '\'">' . "\n";
                    } else {
                      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                    }
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_VENDOR_MODULES, 'module=' . $class . '&vendors_id=' . $vendors_id) . '\'">' . "\n";
                  }
                  ?>
                  <td class="dataTableContent"><?php echo $module->title; ?></td>
                  <td class="dataTableContent" align="right"><?php if (is_numeric($module->sort_order($vendors_id))) echo $module->sort_order($vendors_id); ?></td>
                  <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($class == $mInfo->code) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_VENDOR_MODULES, 'module=' . $class . '&vendors_id=' . $vendors_id) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                  </tr>
                  <?php
                }
              }
              ksort($installed_modules);
              $check_query = tep_db_query("select configuration_value from " . TABLE_VENDOR_CONFIGURATION . " where configuration_key = 'MODULE_VENDOR_SHIPPING_INSTALLED_" . $vendors_id . "' and vendors_id = '" . $vendors_id . "'");
              if (tep_db_num_rows($check_query)) {
                $check = tep_db_fetch_array($check_query);
                if ($check['configuration_value'] != implode(';', $installed_modules)) {
                  tep_db_query("update " . TABLE_VENDOR_CONFIGURATION . " set configuration_value = '" . implode(';', $installed_modules) . "', last_modified = now() where configuration_key = 'MODULE_VENDOR_SHIPPING_INSTALLED_" . $vendors_id . "' and vendors_id = '" . $vendors_id . "'");
                }
              } else {
                tep_db_query("insert into " . TABLE_VENDOR_CONFIGURATION . " (vendors_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . $vendors_id . "', 'Installed Modules', 'MODULE_VENDOR_SHIPPING_INSTALLED_" . $vendors_id . "', '" . implode(';', $installed_modules) . "', 'This is automatically updated. No need to edit.', '6', '0', now())");
              }
              ?>
              <tr>
                <td colspan="3" class="smallText"><?php echo TEXT_MODULE_DIRECTORY . ' <input type="text" style="border-style:none;" value="' . DIR_FS_CATALOG_MODULES . 'vendors_shipping/" size="65" readonly />'; ?></td>
              </tr>
            </table></td>
            <?php
            $heading = array();
            $contents = array();
            switch ($action) {
              case 'edit':
                $keys = '';
                reset($mInfo->keys);
                while (list($key, $value) = each($mInfo->keys)) {
                  $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';
                  if ($value['set_function']) {
                    eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
                  } else {
                    $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
                  }
                  $keys .= '<br><br>';
                }
                $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
                $heading[] = array('text' => '<b>' . $mInfo->title . '</b>');
                $contents = array('form' => tep_draw_form('modules', FILENAME_VENDOR_MODULES, 'module=' . $_GET['module'] . '&action=save&vendors_id=' . $vendors_id));
                $contents[] = array('text' => $keys);
                $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_VENDOR_MODULES, 'module=' . $_GET['module'] . '&vendors_id=' . $vendors_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
                break;
              default:
                $heading[] = array('text' => '<b>' . $mInfo->title . '</b>');
                if ($mInfo->status == '1') {
                  $keys = '';
                  reset($mInfo->keys);
                  while (list(, $value) = each($mInfo->keys)) {
                    $keys .= '<b>' . $value['title'] . '</b><br>';
                    if ($value['use_function']) {
                      $use_function = $value['use_function'];
                      if (ereg('->', $use_function)) {
                        $class_method = explode('->', $use_function);
                        if (!is_object(${$class_method[0]})) {
                          include(DIR_WS_CLASSES . $class_method[0] . '.php');
                          ${$class_method[0]} = new $class_method[0]();
                        }
                        $keys .= tep_call_function($class_method[1], $value['value'], ${$class_method[0]});
                      } else {
                        $keys .= tep_call_function($use_function, $value['value']);
                      }
                    } else {
                      $keys .= $value['value'];
                    }
                    $keys .= '<br><br>';
                  }
                  $keys = substr($keys, 0, strrpos($keys, '<br><br>'));
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_VENDOR_MODULES, 'module=' . $mInfo->code . '&action=remove&vendors_id=' . $vendors_id) . '">' . tep_image_button('button_module_remove.gif', IMAGE_MODULE_REMOVE) . '</a><a href="' . tep_href_link(FILENAME_VENDOR_MODULES, (isset($_GET['module']) ? '&module=' . $_GET['module'] : '') . '&action=edit&vendors_id=' . $vendors_id) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
                  $contents[] = array('text' => '<br>' . $mInfo->description);
                  $contents[] = array('text' => '<br>' . $keys);
                } else {
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_VENDOR_MODULES, 'module=' . $mInfo->code . '&action=install&vendors_id=' . $vendors_id) . '">' . tep_image_button('button_module_install.gif', IMAGE_MODULE_INSTALL) . '</a>');
                  $contents[] = array('text' => '<br>' . $mInfo->description);
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