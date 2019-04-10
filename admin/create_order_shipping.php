<?php
/*
  $Id: create_order_shipping.php,v 1.1 2004/08/19 23:38:51 teo Exp $
  http://www.chainreactionworks.com

  Copyright (c) 2005 chainreactionworks.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {

      case 'save':
        if (isset($_GET['shipID'])) $ship_methods_id = tep_db_prepare_input($_GET['shipID']);
       
         $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
            $language_id = $languages[$i]['id'];

          $sql_data_array = array('ship_method' => tep_db_prepare_input($_POST['ship_method'][$language_id]),
                                  'ship_method_sort'=> tep_db_prepare_input($_POST['ship_method_sort']),
          );
         
          tep_db_perform(TABLE_ORDERS_SHIP_METHODS, $sql_data_array, 'update', 'ship_methods_id = \'' . (isset($_GET['shipID']) ? (int)$_GET['shipID'] : 0) . '\' and ship_method_language = \'' . $language_id . '\'');
              }

  //          tep_redirect(tep_href_link(FILENAME_CREATE_ORDERS_SHIP,'page=' . $_GET['page'] . '&shipID=' . $ship_methods_id));
              break;
      case 'insert':
          if ($action == 'insert') {
          if (isset($_GET['shipID'])) $ship_methods_id = tep_db_prepare_input($_GET['shipID']);
          if (empty($ship_methods_id)) {
            $next_id_query = tep_db_query("select max(ship_methods_id) as ship_methods_id from " . TABLE_ORDERS_SHIP_METHODS . "");
            $next_id = tep_db_fetch_array($next_id_query);
            $ship_methods_id = $next_id['ship_methods_id'] + 1;
          }
          $languages = tep_get_languages();
          for ($i = 0; $i < sizeof($languages); $i++) {
            $language_id=$languages[$i]['id'];
            $sql_data_array = array('ship_method_sort' => tep_db_prepare_input($_POST['sort'][$language_id]),
                          'ship_method' => tep_db_prepare_input($_POST['ship_method'][$language_id]),
                          'date_added' => 'now()');

            $insert_sql_data = array('ship_methods_id' => $ship_methods_id,
                                     'ship_method_language' => $language_id);
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_ORDERS_SHIP_METHODS, $sql_data_array);
          }
      }

        tep_redirect(tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] ));
        break;
      case 'deleteconfirm':
        $shipID = tep_db_prepare_input($_GET['shipID']);
        tep_db_query("delete from " . TABLE_ORDERS_SHIP_METHODS . " where ship_methods_id = '" . tep_db_input($shipID) . "'");

        tep_redirect(tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page']));
        break;
      case 'delete':
        $shipID = tep_db_prepare_input($_GET['shipID']);

           $remove_status = true;
         
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
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CREATE_ORDERS_ADMIN; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $payment_module_query_raw = "select ship_methods_id, ship_method_language, ship_method_sort, ship_method, date_added from " . TABLE_ORDERS_SHIP_METHODS . " where ship_method_language ='" . $languages_id . "' order by ship_method_sort";
  $ship_module_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $payment_module_query_raw, $payment_module_query_numrows);
  $payment_module_query = tep_db_query($payment_module_query_raw);
  while ($payment_module = tep_db_fetch_array($payment_module_query)) {
    if ((!isset($_GET['shipID']) || (isset($_GET['shipID']) && ($_GET['shipID'] == $payment_module['ship_methods_id']))) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
      $oInfo = new objectInfo($payment_module);
    }

    if (isset($oInfo) && is_object($oInfo) && ($payment_module['ship_methods_id'] == $oInfo->ship_methods_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $oInfo->ship_methods_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $payment_module['ship_methods_id']) . '\'">' . "\n";
    }

    if ($payment_module['ship_method_sort'] == '1') {
      echo '                <td class="dataTableContent"><b>' . $payment_module['ship_method'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $payment_module['ship_method'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($payment_module['ship_methods_id'] == $oInfo->ship_methods_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $payment_module['ship_methods_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $ship_module_split->display_count($payment_module_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PAYMENT_MODULES); ?></td>
                    <td class="smallText" align="right"><?php echo $ship_module_split->display_links($payment_module_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_SHIPPING . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&action=insert', 'post')) ; 
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $payment_module_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $payment_module_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('ship_method[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_SHIPPING_MODULES_NAME . $payment_module_inputs_string);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
     $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_SHIPPING . '</b>');
     $contents = array('form' => tep_draw_form('status', FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $oInfo->ship_methods_id  . '&action=save')) ; 
     $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $languages = tep_get_languages();
      if (!isset($payment_module_inputs_string)) {
        $payment_module_inputs_string = '';
      }
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $payment_module_inputs_string .= '<br>'. tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('ship_method[' . $languages[$i]['id'] . ']', tep_get_ship_method($_GET['shipID'], $languages[$i]['id']) );
    }
      $payment_module_inputs_string .= '<br>' . TEXT_INFO_SORT_ORDER . ' &nbsp;'. tep_draw_input_field('ship_method_sort', $oInfo->ship_method_sort);
    $contents[] = array('text' => '<br>' . TEXT_INFO_SHIPPING_MODULES_NAME . $payment_module_inputs_string);
    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $oInfo->ship_methods_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_DELETE_INTRO . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $oInfo->ship_methods_id  . '&action=deleteconfirm')) ; 
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $oInfo->ship_method . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $oInfo->ship_methods_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>' . $oInfo->ship_method . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $oInfo->ship_methods_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_SHIP, 'page=' . $_GET['page'] . '&shipID=' . $oInfo->ship_methods_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $payment_module_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $payment_module_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_get_ship_method($oInfo->ship_methods_id, $languages[$i]['id']);
       }

        $contents[] = array('text' => $payment_module_inputs_string);
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
