<?php
/*
  $Id: pages_categories.php,v 1.1.1.1 2004/03/04 23:38:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
// define functions
require(DIR_WS_FUNCTIONS . 'pages.php');

// RCI code start
echo $cre_RCI->get('global', 'top', false);
echo $cre_RCI->get('pagescategories', 'top', false); 
// RCI code eof

// clean variables
$cID = '';
if (isset($_POST['cID']) && tep_not_null($_POST['cID'])) {
  $cID = (int)$_POST['cID'];
} elseif (isset($_GET['cID']) && tep_not_null($_GET['cID'])) {
  $cID = (int)$_GET['cID'];
}

$action = '';
if (isset($_POST['action']) && tep_not_null($_POST['action'])) {
  $action = tep_db_prepare_input($_POST['action']);
} elseif (isset($_GET['action']) && tep_not_null($_GET['action'])) {
  $action = tep_db_prepare_input($_GET['action']);
} 

$error = false;
$processed = false;

switch ($action) {
 case 'setflag':
   $status = tep_db_prepare_input($_GET['flag']);

   if ($status == '1') {
     tep_db_query("update " . TABLE_PAGES_CATEGORIES . " set categories_status = '1' where categories_id = '" . (int)$cID . "'");
   } elseif ($status == '0') {
     tep_db_query("update " . TABLE_PAGES_CATEGORIES . " set categories_status = '0' where categories_id = '" . (int)$cID . "'");
   }

   tep_redirect(tep_href_link(FILENAME_PAGES_CATEGORIES, '&cID=' . $cID));
   break;
 case 'insert':
 case 'update':
   $categories_sort_order = tep_db_prepare_input($_POST['categories_sort_order']);
   $categories_status = ((tep_db_prepare_input($_POST['categories_status']) == 'on') ? '1' : '0');

   $sql_data_array = array('categories_sort_order' => $categories_sort_order,
         'categories_status' => $categories_status);

   if ($action == 'insert') {
     $insert_sql_data = array('categories_date_added' => 'now()');

     $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

     tep_db_perform(TABLE_PAGES_CATEGORIES, $sql_data_array);

     $cID = tep_db_insert_id();
   } elseif ($action == 'update') {
     $update_sql_data = array('categories_last_modified' => 'now()');

     $sql_data_array = array_merge($sql_data_array, $update_sql_data);

     tep_db_perform(TABLE_PAGES_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$cID . "'");
   }

   $languages = tep_get_languages();
   for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
     $categories_name_array = $_POST['categories_name'];
     $categories_description_array = $_POST['categories_description'];

     $language_id = $languages[$i]['id'];

     $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),
           'categories_description' => tep_db_prepare_input($categories_description_array[$language_id]));

     if ($action == 'insert') {
       $insert_sql_data = array('categories_id' => $cID,
        'language_id' => $languages[$i]['id']);

       $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

       tep_db_perform(TABLE_PAGES_CATEGORIES_DESCRIPTION, $sql_data_array);
     } elseif ($action == 'update') {
       tep_db_perform(TABLE_PAGES_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$cID . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
     }
   }

   if (trim($_FILES['categories_image']['name']) != '' && $categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
     tep_db_query("update " . TABLE_PAGES_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$cID . "'");
   }

   tep_redirect(tep_href_link(FILENAME_PAGES_CATEGORIES, '&cID=' . $cID));
   break;
 case 'delete_confirm':
   if (tep_not_null($cID)) {
     $page_ids_query = tep_db_query("select pages_id from " . TABLE_PAGES_TO_CATEGORIES . " where categories_id = '" . (int)$cID . "'");

     while ($page_ids = tep_db_fetch_array($page_ids_query)) {
       tep_pages_remove_page($page_ids['pages_id']);
     }

     tep_pages_remove_category($cID);
   }

   tep_redirect(tep_href_link(FILENAME_PAGES_CATEGORIES));
   break;
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
          <tr><?php echo tep_draw_form('search', FILENAME_PAGES_CATEGORIES, '', 'get'); 
            if (isset($_GET[tep_session_name()])) {
              echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
          ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
      $search = " and icd.categories_name like '%" . $keywords . "%'";

      $categories_query_raw = "select ic.categories_id, ic.categories_image, ic.categories_status, ic.categories_sort_order, ic.categories_date_added, ic.categories_last_modified, icd.categories_name, icd.categories_description from " . TABLE_PAGES_CATEGORIES . " ic left join " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd on ic.categories_id = icd.categories_id where icd.language_id = '" . (int)$languages_id . "'" . $search . " order by ic.categories_sort_order, icd.categories_name";
    } else {
      $categories_query_raw = "select ic.categories_id, ic.categories_image, ic.categories_status, ic.categories_sort_order, ic.categories_date_added, ic.categories_last_modified, icd.categories_name, icd.categories_description from " . TABLE_PAGES_CATEGORIES . " ic left join " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd on ic.categories_id = icd.categories_id where icd.language_id = '" . (int)$languages_id . "' order by ic.categories_sort_order, icd.categories_name";
    }

    $categories_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $categories_query_raw, $categories_query_numrows);
    $categories_query = tep_db_query($categories_query_raw);
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo)) {
        $page_count_query = tep_db_query("select count(*) as categories_page_count from " . TABLE_PAGES_TO_CATEGORIES . " where categories_id = '" . (int)$categories['categories_id'] . "'");
        $page_count = tep_db_fetch_array($page_count_query);

        $cInfo_array = array_merge($categories, $page_count);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PAGES_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PAGES_CATEGORIES, tep_get_all_get_params(array('cID')) . 'cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $categories['categories_name']; ?></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($categories['categories_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, 'action=setflag&flag=0&cID=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, 'action=setflag&flag=1&cID=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, tep_get_all_get_params(array('cID')) . 'cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $categories_split->display_count($categories_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PAGES_CATEGORIES); ?></td>
                    <td class="smallText" align="right"><?php echo $categories_split->display_links($categories_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'pages', 'x', 'y', 'cID'))); ?></td>
                  </tr>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>              
                    <?php
                    // RCI code start
                    echo $cre_RCI->get('pagescategories', 'listingbottom');
                    // RCI code eof
                    ?>
                  </tr>
                </table></td>
              </tr>                  
                  <tr>
<?php
    if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
?>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    } else {
?>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_PAGES_HEADING_NEW_PAGES_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_new', FILENAME_PAGES_CATEGORIES, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_PAGES_CATEGORIES_INTRO);

      $category_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
      }

      $category_description_inputs_string = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_description_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5');
      }

      $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_NAME . $category_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_DESCRIPTION . $category_description_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
      $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_SORT_ORDER . '&nbsp;' . tep_draw_input_field('categories_sort_order', '', 'size="2"'));
     // $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'on', true) . ' ' . TEXT_PAGES_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'off') . ' ' . TEXT_PAGES_CATEGORIES_STATUS_DISABLE);
     
      $contents[] = array('text' => '<table><tr><td>'.TEXT_PAGES_CATEGORIES_STATUS.'</td><td>'.tep_draw_radio_field('categories_status', 'on', true).'</td><td>'.TEXT_PAGES_CATEGORIES_STATUS_ENABLE.'</td><tdwidth=5>&nbsp;</td><td>'.tep_draw_radio_field('categories_status', 'off').'</td><td>'.TEXT_PAGES_CATEGORIES_STATUS_DISABLE.'</td></tr></table>');


      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE));
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_PAGES_HEADING_EDIT_PAGES_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_edit', FILENAME_PAGES_CATEGORIES, 'action=update', 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('cID', $cInfo->categories_id));
      $contents[] = array('text' => TEXT_EDIT_PAGES_CATEGORIES_INTRO);

      $category_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_pages_get_category_name($cInfo->categories_id, $languages[$i]['id']));
      }

      $category_description_inputs_string = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_description_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5', tep_pages_get_category_description($cInfo->categories_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_NAME . $category_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_DESCRIPTION . $category_description_inputs_string);
      $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name) . '<br>' . $cInfo->categories_image);
      $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
      $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_SORT_ORDER . '&nbsp;' . tep_draw_input_field('categories_sort_order', $cInfo->categories_sort_order, 'size="2"'));

      //$contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'on', ($cInfo->categories_status == '1') ? true : false) . ' ' . TEXT_PAGES_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'off', ($cInfo->categories_status == '0') ? true : false) . ' ' . TEXT_PAGES_CATEGORIES_STATUS_DISABLE);

      $contents[] = array('text' => '<table><tr><td>'.TEXT_PAGES_CATEGORIES_STATUS.'</td><td>'.tep_draw_radio_field('categories_status', 'on',($cInfo->categories_status == '1') ? true : false).'</td><td>'.TEXT_PAGES_CATEGORIES_STATUS_ENABLE.'</td><tdwidth=5>&nbsp;</td><td>'.tep_draw_radio_field('categories_status', 'off',($cInfo->categories_status == '0') ? true : false).'</td><td>'.TEXT_PAGES_CATEGORIES_STATUS_DISABLE.'</td></tr></table>');


      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, 'cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE));
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_PAGES_HEADING_DELETE_PAGES_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_delete', FILENAME_PAGES_CATEGORIES, 'action=delete_confirm') . tep_draw_hidden_field('cID', $cInfo->categories_id));
      $contents[] = array('text' => TEXT_DELETE_PAGES_CATEGORIES_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
      if ($cInfo->categories_page_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PAGES, $cInfo->categories_page_count));
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, 'cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_PAGES_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
        $contents[] = array('text' => '<br>' . TEXT_DATE_PAGES_CATEGORY_CREATED . ' <b>' . tep_date_short($cInfo->categories_date_added) . '</b>');
        if (tep_not_null($cInfo->categories_last_modified)) {
          $contents[] = array('text' => '<br>' . TEXT_DATE_PAGES_CATEGORY_LAST_MODIFIED . ' <b>' . tep_date_short($cInfo->categories_last_modified) . '</b>');
        }
        $contents[] = array('text' => '<br>' . TEXT_PAGES_CATEGORY_COUNT . ' <b>'  . $cInfo->categories_page_count . '</b>');
        $contents[] = array('text' => TEXT_PAGES_CATEGORY_SORT_ORDER . ' <b>'  . $cInfo->categories_sort_order . '</b>');
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
      <?php
      // RCI code start
      echo $cre_RCI->get('pagescategories', 'bottom'); 
      echo $cre_RCI->get('global', 'bottom');                                      
      // RCI code eof
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
