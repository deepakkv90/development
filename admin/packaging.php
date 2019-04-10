<?php
/*
  $Id: packaging.php,v 1.1.1.1 2008/06/17 23:38:51 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

 require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($_GET['pID'])) {
          $package_id = tep_db_prepare_input($_GET['pID']);
        }
       

        if (number_format(trim($_POST['package_length']), 2, '.', '') <= 0) {
                      $error = MIN_LENGTH_NOT_MET;
                      $messageStack->add('search', $error, 'error');
                      $action = 'new';
                    } else if (number_format(trim($_POST['package_width']), 2, '.', '') <= 0) {
                      $error = MIN_WIDTH_NOT_MET;
                      $messageStack->add('search', $error, 'error');
                      $action = 'new';
                    } else if (number_format(trim($_POST['package_height']), 2, '.', '') <= 0) {
                      $error = MIN_HEIGHT_NOT_MET;
                      $messageStack->add('search', $error, 'error');
                      $action = 'new';
                    } else if (number_format(trim($_POST['package_empty_weight']), 2, '.', '') < 0) { 
                      $error = MIN_EMPTY_WEIGHT_NOT_MET;
                      $messageStack->add('search', $error, 'error');
                      $action = 'new';
                    } else if (number_format(trim($_POST['package_max_weight']), 2, '.', '') < 0) { 
                      $error = MIN_MAX_WEIGHT_NOT_MET;
                      $messageStack->add('search', $error, 'error');
                      $action = 'new';
                    } else {

        $sql_data_array = array('package_name' => tep_db_prepare_input($_POST['package_name']),
                                'package_description' => tep_db_prepare_input($_POST['package_description']),
                                'package_length' => tep_db_prepare_input($_POST['package_length']),
                                'package_width' => tep_db_prepare_input($_POST['package_width']),
                                'package_height' => tep_db_prepare_input($_POST['package_height']),
                                'package_empty_weight' => tep_db_prepare_input($_POST['package_empty_weight']),
                                'package_max_weight' => tep_db_prepare_input($_POST['package_max_weight']),
                                'package_cost' => tep_db_prepare_input($_POST['package_cost']));

        if ($action == 'insert') {
          tep_db_perform(TABLE_PACKAGING, $sql_data_array);
          $package_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          tep_db_perform(TABLE_PACKAGING, $sql_data_array, 'update', "package_id = '" . (int)$package_id . "'");
        }
        tep_redirect(tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $package_id));  
      }
           
       
        break;
      case 'deleteconfirm':
        $pID = tep_db_prepare_input($_GET['pID']);

        tep_db_query("delete from " . TABLE_PACKAGING . " where package_id = '" . tep_db_input($pID) . "'");

        tep_redirect(tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page']));
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
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
                <td class="dataTableHeadingContent"><?php echo HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo HEADING_DESCRIPTION; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo HEADING_LENGTH; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo HEADING_WIDTH; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo HEADING_HEIGHT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo HEADING_EMPTY_WEIGHT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo HEADING_MAX_WEIGHT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo HEADING_COST; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo HEADING_ACTION; ?></td>
              </tr>

<?php
  $packaging_query_raw = "select * from " . TABLE_PACKAGING . " order by package_cost";
  $packaging_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $packaging_query_raw, $packaging_query_numrows);
  $packaging_query = tep_db_query($packaging_query_raw);
  while ($packaging = tep_db_fetch_array($packaging_query)) {
    if ((!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $packaging['package_id']))) && !isset($pInfo) && (substr($action, 0, 3) != 'new')) {
      $pInfo = new objectInfo($packaging);
    }

    if (isset($pInfo) && is_object($pInfo) && ($packaging['package_id'] == $pInfo->package_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $pInfo->package_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $packaging['package_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $packaging['package_name']; ?></td>
                <td class="dataTableContent"><?php echo $packaging['package_description']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $packaging['package_length']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $packaging['package_width']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $packaging['package_height']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $packaging['package_empty_weight']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $packaging['package_max_weight']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $packaging['package_cost']; ?></td>
                
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($packaging['package_id'] == $pInfo->package_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $packaging['package_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="9"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $packaging_split->display_count($packaging_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PACKAGES); ?></td>
                    <td class="smallText" align="right"><?php echo $packaging_split->display_links($packaging_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . NEW_PACKAGE . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $pInfo->package_id  . '&action=insert'));
      
      $contents[] = array('text' => '<br>' . HEADING_NAME . ': <br>' . tep_draw_input_field('package_name'));
      $contents[] = array('text' => '<br>' . HEADING_DESCRIPTION . ': <br>' . tep_draw_input_field('package_description'));
      $contents[] = array('text' => '<br>' . HEADING_LENGTH . ': <br>' . tep_draw_input_field('package_length'));
      $contents[] = array('text' => '<br>' . HEADING_WIDTH . ': <br>' . tep_draw_input_field('package_width'));
      $contents[] = array('text' => '<br>' . HEADING_HEIGHT . ': <br>' . tep_draw_input_field('package_height'));
      $contents[] = array('text' => '<br>' . HEADING_EMPTY_WEIGHT . ': <br>' . tep_draw_input_field('package_empty_weight'));
      $contents[] = array('text' => '<br>' . HEADING_MAX_WEIGHT . ': <br>' . tep_draw_input_field('package_max_weight'));
      $contents[] = array('text' => '<br>' . HEADING_COST . ': <br>' . tep_draw_input_field('package_cost'));
      
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_insert.gif', IMAGE_INSERT));
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . UPDATE_PACKAGE . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $pInfo->package_id  . '&action=save'));
      $contents[] = array('text' => UPDATE_PACKAGE);

      
      $contents[] = array('text' => '<br>' . HEADING_NAME . ': <br>' . tep_draw_input_field('package_name', $pInfo->package_name));
      $contents[] = array('text' => '<br>' . HEADING_DESCRIPTION . ': <br>' . tep_draw_input_field('package_description', $pInfo->package_description));
      $contents[] = array('text' => '<br>' . HEADING_LENGTH . ': <br>' . tep_draw_input_field('package_length', $pInfo->package_length));
      $contents[] = array('text' => '<br>' . HEADING_WIDTH . ': <br>' . tep_draw_input_field('package_width', $pInfo->package_width));
      $contents[] = array('text' => '<br>' . HEADING_HEIGHT . ': <br>' . tep_draw_input_field('package_height', $pInfo->package_height));
      $contents[] = array('text' => '<br>' . HEADING_EMPTY_WEIGHT . ': <br>' . tep_draw_input_field('package_empty_weight', $pInfo->package_empty_weight));
      $contents[] = array('text' => '<br>' . HEADING_MAX_WEIGHT . ': <br>' . tep_draw_input_field('package_max_weight', $pInfo->package_max_weight));
      $contents[] = array('text' => '<br>' . HEADING_COST . ': <br>' . tep_draw_input_field('package_cost', $pInfo->package_cost));
      
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $pInfo->package_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . DELETE_PACKAGE . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $pInfo->package_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => CONFIRM_DELETE);
      $contents[] = array('text' => '<br><b>' . $pInfo->packaging_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $pInfo->package_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
      break;
    default:
      if (isset($pInfo) && is_object($pInfo)) {
        $heading[] = array('text' => '<b>' . $pInfo->package_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $pInfo->package_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_PACKAGING, 'page=' . $_GET['page'] . '&pID=' . $pInfo->package_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
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