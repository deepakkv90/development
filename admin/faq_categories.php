<?php
/*
  $Id: faq_categories.php,v 1.1.1.1 2004/03/04 23:38:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

// define functions
require(DIR_WS_FUNCTIONS . 'faq.php');

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
     tep_db_query("update " . TABLE_FAQ_CATEGORIES . " set categories_status = '1' where categories_id = '" . (int)$cID . "'");
   } elseif ($status == '0') {
     tep_db_query("update " . TABLE_FAQ_CATEGORIES . " set categories_status = '0' where categories_id = '" . (int)$cID . "'");
   }

//print("line 46 <br>");

   tep_redirect(tep_href_link(FILENAME_FAQ_CATEGORIES, '&cID=' . $cID));
   break;
 case 'insert':
 case 'update':
   
   $categories_sort_order = tep_db_prepare_input($_POST['categories_sort_order']);
   $categories_status = ((tep_db_prepare_input($_POST['categories_status']) == 'on') ? '1' : '0');

   $sql_data_array = array('categories_sort_order' => $categories_sort_order,
         'categories_status' => $categories_status);
/************   GSR - Group Access Logic - Start   ************/
global $customergroupid;
 if ($action == 'insert') {
      $tmp_action = "new";
    } else {
      $tmp_action = '';
    }
    $sql_data_array = func_group_access_array($sql_data_array,$tmp_action);
/************   GSR - Group Access Logic - End   ************/
   if ($action == 'insert') {
     $insert_sql_data = array('categories_date_added' => 'now()');

     $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

     tep_db_perform(TABLE_FAQ_CATEGORIES, $sql_data_array);

     $cID = tep_db_insert_id();
   } elseif ($action == 'update') {
     $update_sql_data = array('categories_last_modified' => 'now()');

     $sql_data_array = array_merge($sql_data_array, $update_sql_data);

     tep_db_perform(TABLE_FAQ_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$cID . "'");
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

       tep_db_perform(TABLE_FAQ_CATEGORIES_DESCRIPTION, $sql_data_array);
     } elseif ($action == 'update') {


/************   GSR - Group Access Logic - Start   ************/

 if (isset($_POST['Push'])){
      if($_POST['Push']==2)
      {readFaqChild($cID,$customergroupid,"C");}

      if($_POST['Push']==3)
      {readFaqChild($cID,$customergroupid,"CP");}
        }   
/************   GSR - Group Access Logic - End   ************/

       tep_db_perform(TABLE_FAQ_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$cID . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
     }
   }

   if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
     tep_db_query("update " . TABLE_FAQ_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$cID . "'");
   }
   
//print("line 115 <br>");
   tep_redirect(tep_href_link(FILENAME_FAQ_CATEGORIES, '&cID=' . $cID));
   break;
 case 'delete_confirm':
   if (tep_not_null($cID)) {
     $faq_ids_query = tep_db_query("select faq_id from " . TABLE_FAQ_TO_CATEGORIES . " where categories_id = '" . (int)$cID . "'");

     while ($faq_ids = tep_db_fetch_array($faq_ids_query)) {
       tep_faq_remove_faq($faq_ids['faq_id']);
     }

     tep_faq_remove_category($cID);
   }
//print("line 128 <br>");
   tep_redirect(tep_href_link(FILENAME_FAQ_CATEGORIES));
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
<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}

.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}

.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php //require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
<tr>
  <td colspan = "3">
  <!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
  </td>
</tr>
<!-- body //-->
  <tr>  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
  <!-- body_text //-->
  <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
        <td><table border="0" width="80%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('search', FILENAME_FAQ_CATEGORIES, '', 'get'); 
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
        <td>
        <?php
      if (isset($_GET['action']) && ($_GET['action'] == 'new' || $_GET['action'] == 'edit' )) {
        if ($_GET['action'] == 'edit') {
        echo tep_draw_form('categories_new', FILENAME_FAQ_CATEGORIES, 'action=update', 'post', 'enctype="multipart/form-data"');
        } else {
          echo tep_draw_form('categories_new', FILENAME_FAQ_CATEGORIES, 'action=insert', 'post', 'enctype="multipart/form-data"');
        }

        if ($_GET['action'] == 'edit') {
          $categories_query_raw = tep_db_query("select ic.categories_id, ic.categories_image, ic.categories_status, ic.categories_sort_order, ic.categories_date_added, ic.categories_last_modified,ic.products_group_access, icd.categories_name, icd.categories_description from " . TABLE_FAQ_CATEGORIES . " ic left join " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd on ic.categories_id = icd.categories_id where icd.language_id = '" . (int)$languages_id . "' and ic.categories_id = '".(int)$_GET['cID']."' order by ic.categories_sort_order, icd.categories_name");
          $cInfo_array = tep_db_fetch_array($categories_query_raw);
          $cInfo = new objectInfo($cInfo_array);
          echo tep_draw_hidden_field('cID', $cInfo->categories_id);
        }
      ?>
        
        <table border="0" cellspacing="0" cellpadding="0" width = "100%">
          <tr>
            <td class="main">
              <table cellpadding = "0" cellspacing = "0"> 
                <tr>
                  <td>
                    <?php echo TEXT_FAQ_CATEGORIES_STATUS; ?>
                  </td>
                  <td>
                    <table>
                      <tr>
                        <td>
                          <?php
                            if (!isset($cInfo->categories_status)) {
                              $cInfo->categories_status = 0;
                            }
                            echo tep_draw_radio_field('categories_status', 'on', (isset($cInfo->categories_status) && $cInfo->categories_status == '1') ? true : false);
                          ?>
                        </td>
                        <td>
                          <?php echo TEXT_FAQ_CATEGORIES_STATUS_ENABLE; ?>
                        </td>
                        <td width = "20">&nbsp;
                        </td>
                        <td>
                          <?php
                          echo tep_draw_radio_field('categories_status', 'off', (isset($cInfo->categories_status) && $cInfo->categories_status == '0') ? true : false);  
                        ?>
                        </td>
                        <td>
                          <?php echo TEXT_FAQ_CATEGORIES_STATUS_DISABLE; ?>
                        </td>
                      </tr>
                    </table>
                  <?php
          /*  if (!isset($cInfo->categories_status)) {
              $cInfo->categories_status = 0;
            }
            echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('categories_status', 'on', (isset($cInfo->categories_status) && $cInfo->categories_status == '1') ? true : false) . ' ' . TEXT_FAQ_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'off', (isset($cInfo->categories_status) && $cInfo->categories_status == '0') ? true : false) . ' ' . TEXT_FAQ_CATEGORIES_STATUS_DISABLE; */
            ?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_FAQ_CATEGORIES_SORT_ORDER; ?><!-- </td>
            <td class="main"> --><?php echo tep_draw_separator('pixel_trans.gif', '10', '15') . '&nbsp;' . tep_draw_input_field('categories_sort_order', (isset($cInfo->categories_sort_order) ? $cInfo->categories_sort_order : ''), 'size="2"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_FAQ_CATEGORIES_IMAGE; ?><!-- </td>
            <td class="main"> --><?php echo tep_draw_separator('pixel_trans.gif', '10', '15') . '&nbsp;' . tep_draw_file_field('categories_image'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<!--  /************   GSR - Group Access Logic - Start   ************/ -->
          <tr>
            <td colspan = "2">    
              <?php
                 if (!isset($cInfo->categories_id)) {
                   $current_category_id = 0;
                 } else {
                   $current_category_id = $cInfo->categories_id;
                 }
             
                if (!isset($cInfo->products_group_access)) {
                  $cInfo->products_group_access = "G,0";
                }
                $group_access = $cInfo->products_group_access;
                
                func_category_group_access($group_access,$current_category_id) ; 
              ?>
            </td>
          </tr>
<!--  /************   GSR - Group Access Logic - End   ************/ -->
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr><td>
        <?php 
          // Load Editor
          echo tep_load_html_editor();
          if (!isset($categories_description_elements)) {
            $categories_description_elements = '';
          }
          for ($i=0; $i<sizeof($languages); $i++) {
            $categories_description_elements .= 'categories_description[' . $languages[$i]['id'] . '],'; 
          } 
          echo tep_insert_html_editor($categories_description_elements);
        ?>
        <div class="tab-pane" id="tabPane1">
          <script type="text/javascript">
            tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
          </script>
        <?php
          for ($i=0; $i<sizeof($languages); $i++) {
        ?>
          <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
            <h2 class="tab"><nobr><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="absmiddle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></nobr></h2>
            <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="tab table">
              <tr>
                <td valign="top">
                  <table border="0" cellspacing="0" cellpadding="2" width="100%">
                    <tr>
                      <td class="main"><?php echo TEXT_FAQ_CATEGORIES_NAME; ?><?php echo  '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (isset($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : (isset($cInfo->categories_id) ? tep_faq_get_category_name($cInfo->categories_id, $languages[$i]['id']) : ''))); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="main" valign="top"><?php echo TEXT_FAQ_CATEGORIES_DESCRIPTION; ?></td>
                     </tr>
                     <tr>
                      <td>
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <!-- <td class="main" valign="top"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td> -->
                            <td class="main"><?php echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '20', (isset($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : (isset($cInfo->categories_id) ? tep_faq_get_category_description($cInfo->categories_id, $languages[$i]['id']) : '')),' style="width: 100%" mce_editable="true"'); ?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>
          <?php
              }
          ?>
        </div>
       </td>
       </tr>
       <tr>
        <td colspan = "2" align = "right">
        <?php
        echo tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, (isset($cInfo->categories_id) ? ('cID=' .  $cInfo->categories_id) : '') ) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';    
        ?>
        </td>
       </tr>

     </table>
        
      </form>
      <?php            
      } else {
      ?>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
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

      $categories_query_raw = "select ic.categories_id, ic.categories_image, ic.categories_status, ic.categories_sort_order, ic.categories_date_added, ic.categories_last_modified, icd.categories_name, icd.categories_description from " . TABLE_FAQ_CATEGORIES . " ic left join " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd on ic.categories_id = icd.categories_id where icd.language_id = '" . (int)$languages_id . "'" . $search . " order by ic.categories_sort_order, icd.categories_name";
    } else {
      $categories_query_raw = "select ic.categories_id, ic.categories_image, ic.categories_status, ic.categories_sort_order, ic.categories_date_added, ic.categories_last_modified, icd.categories_name, icd.categories_description from " . TABLE_FAQ_CATEGORIES . " ic left join " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd on ic.categories_id = icd.categories_id where icd.language_id = '" . (int)$languages_id . "' order by ic.categories_sort_order, icd.categories_name";
    }

    $categories_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $categories_query_raw, $categories_query_numrows);
    $categories_query = tep_db_query($categories_query_raw);
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo)) {
        $faq_count_query = tep_db_query("select count(*) as categories_faq_count from " . TABLE_FAQ_TO_CATEGORIES . " where categories_id = '" . (int)$categories['categories_id'] . "'");
        $faq_count = tep_db_fetch_array($faq_count_query);

        $cInfo_array = array_merge($categories, $faq_count);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID')) . 'cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $categories['categories_name']; ?></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($categories['categories_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'action=setflag&flag=0&cID=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'action=setflag&flag=1&cID=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID')) . 'cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>           </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $categories_split->display_count($categories_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_FAQ_CATEGORIES); ?></td>
                    <td class="smallText" align="right"><?php echo $categories_split->display_links($categories_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'pages', 'x', 'y', 'cID'))); ?></td>
                  </tr>
                  <tr>
<?php
    if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
?>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    } else {
?>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
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
    /*case 'new':
      $heading[] = array('text' => '<b>' . TEXT_FAQ_HEADING_NEW_FAQ_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_new', FILENAME_FAQ_CATEGORIES, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_FAQ_CATEGORIES_INTRO);

      $category_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
      }

      $category_description_inputs_string = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_description_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5');
      }

      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_NAME . $category_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_DESCRIPTION . $category_description_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_SORT_ORDER . '&nbsp;' . tep_draw_input_field('categories_sort_order', '', 'size="2"'));
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'on', true) . ' ' . TEXT_FAQ_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'off') . ' ' . TEXT_FAQ_CATEGORIES_STATUS_DISABLE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_FAQ_HEADING_EDIT_FAQ_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_edit', FILENAME_FAQ_CATEGORIES, 'action=update', 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('cID', $cInfo->categories_id));
      $contents[] = array('text' => TEXT_EDIT_FAQ_CATEGORIES_INTRO);

      $category_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_faq_get_category_name($cInfo->categories_id, $languages[$i]['id']));
      }

      $category_description_inputs_string = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_description_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5', tep_faq_get_category_description($cInfo->categories_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_NAME . $category_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_DESCRIPTION . $category_description_inputs_string);
      $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name) . '<br>' . $cInfo->categories_image);
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_SORT_ORDER . '&nbsp;' . tep_draw_input_field('categories_sort_order', $cInfo->categories_sort_order, 'size="2"'));
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'on', ($cInfo->categories_status == '1') ? true : false) . ' ' . TEXT_FAQ_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'off', ($cInfo->categories_status == '0') ? true : false) . ' ' . TEXT_FAQ_CATEGORIES_STATUS_DISABLE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
      */
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_FAQ_HEADING_DELETE_FAQ_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_delete', FILENAME_FAQ_CATEGORIES, 'action=delete_confirm') . tep_draw_hidden_field('cID', $cInfo->categories_id));
      $contents[] = array('text' => TEXT_DELETE_FAQ_CATEGORIES_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
      if ($cInfo->categories_faq_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PAGES, $cInfo->categories_faq_count));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
        $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORY_DESCRIPTION . ' ' . $cInfo->categories_description);
        $contents[] = array('text' => '<br>' . TEXT_DATE_FAQ_CATEGORY_CREATED . ' ' . tep_date_short($cInfo->categories_date_added));
        if (tep_not_null($cInfo->categories_last_modified)) {
          $contents[] = array('text' => '<br>' . TEXT_DATE_FAQ_CATEGORY_LAST_MODIFIED . ' ' . tep_date_short($cInfo->categories_last_modified));
        }
        $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORY_COUNT . ' '  . $cInfo->categories_faq_count);
        $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORY_SORT_ORDER . ' '  . $cInfo->categories_sort_order);
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
<?php      } ?>
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
<?php
/*
function checkFaqParentStatus($pcatid,$grouptype)
{
  GLOBAL $groupcnt;
  if($pcatid!="")
  {
    $listing1_sql =' and (products_group_access like "%,'.$grouptype.',%" or products_group_access like "'.$grouptype.',%" or products_group_access like "%,'.$grouptype.'" or products_group_access="'.$grouptype.'")';
    $categories_query1 = tep_db_query("select count(categories_id) as cntcat from " . TABLE_FAQ_CATEGORIES."  where topics_id=".$pcatid.$listing1_sql);
    while($category = tep_db_fetch_array($categories_query1))
    {
      if($category['cntcat']=="0")
      {
        $groupcnt=$groupcnt+ 1;
      }
    }

     $category_query = tep_db_query("select parent_id from ".TABLE_FAQ_CATEGORIES." where categories_id=".$pcatid);
     while($category = tep_db_fetch_array($category_query))
     {
      //echo $category['parent_id'];
      if($category['parent_id']!=0)
      {
        checkFaqParentStatus($category['parent_id'],$grouptype);
      }
    }
  }
}
*/
function readFaqChild($pcatid,$pcustomergroupid,$categoryorproduct)
{
  /* $category_query = tep_db_query("select categories_id from ".TABLE_FAQ_CATEGORIES." where parent_id=".$pcatid);
   while($category = tep_db_fetch_array($category_query)) {
     $sql_data_array1=array('products_group_access' => $pcustomergroupid);
     $update_sql_data = array('last_modified' => 'now()');
     $sql_data_array1 = array_merge($sql_data_array1, $update_sql_data);
     tep_db_perform(TABLE_FAQ_CATEGORIES, $sql_data_array1, 'update', "categories_id = '" . (int)$category['categories_id'] . "'");
     if($categoryorproduct=="CP") {
       tep_db_query("update " . TABLE_FAQ . " a,".TABLE_FAQ_TO_CATEGORIES." b set products_group_access = '" . $pcustomergroupid . "'  where a.faq_id=b.faq_id and b.category_id= '" . (int)$category['categories_id'] . "'");
     }
   }*/
   if ($categoryorproduct=="CP") {
     tep_db_query("update " . TABLE_FAQ . " a,".TABLE_FAQ_TO_CATEGORIES." b set products_group_access = '" . $pcustomergroupid . "'  where a.faq_id=b.faq_id and b.categories_id= '" . (int)$pcatid . "'");
   }
}


?>