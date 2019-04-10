<?php
/*
  $Id: affiliate_banners.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $affiliate_banner_extension = tep_banner_image_extension();


//get post compatiblity

   if (isset($_GET['action'])) {
    $action = $_GET['action'] ;
    }else if (isset($_POST['action'])){
    $action = $_POST['action'] ;
    } else {
    $action = '' ;
   }

//abID
   if (isset($_GET['abID'])) {
    $abID = $_GET['abID'] ;
    }else if (isset($_POST['abID'])){
    $abID = $_POST['abID'] ;
    } else {
    $abID = '' ;
   }
//page
   if (isset($_GET['page'])) {
    $page = $_GET['page'] ;
    }else if (isset($_POST['page'])){
    $page = $_POST['page'] ;
    } else {
    $page = 1 ;
   }

  if ($action != '') {
    switch ($action) {
      case 'setaffiliate_flag':
        if (isset($_GET['affiliate_flag'])) {
          $affiliate_flag = $_GET['affiliate_flag'] ;
        } elseif (isset($_POST['affiliate_flag'])) {
          $affiliate_flag = $_POST['affiliate_flag'] ;
        } else {
          $affiliate_flag = 1 ;     
        }
     
       if ($affiliate_flag == '1') {
           tep_db_query("update " . TABLE_AFFILIATE_BANNERS . " set affiliate_status = '0', affiliate_date_status_change = now() where affiliate_banners_id = '" . $abID . "'");
       } elseif ($affiliate_flag == '0') {
           tep_db_query("update " . TABLE_AFFILIATE_BANNERS . " set affiliate_status = '1', affiliate_date_status_change = now() where affiliate_banners_id = '" . $abID . "'");
       }
       $messageStack->add_session('search', SUCCESS_BANNER_STATUS_UPDATED, 'success');
       tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $page . '&abID=' . $abID));
        break;
      case 'insert':
      case 'update':
        $affiliate_banners_id = (isset($_POST['affiliate_banners_id']) ? tep_db_prepare_input($_POST['affiliate_banners_id']) : '') ;
        $affiliate_banners_title = tep_db_prepare_input($_POST['affiliate_banners_title']);
        $affiliate_products_id  = (isset($_POST['affiliate_products_id']) ? tep_db_prepare_input($_POST['affiliate_products_id']) : '');
        $affiliate_category_id  = (isset($_POST['affiliate_category_id']) ? tep_db_prepare_input($_POST['affiliate_category_id']) : '') ;
        $new_affiliate_banners_group = (isset($_POST['new_affiliate_banners_group']) ? ($_POST['new_affiliate_banners_group']) : '') ;
        $affiliate_banners_group = (isset($new_affiliate_banners_group) ? $new_affiliate_banners_group : tep_db_prepare_input($_POST['affiliate_banners_group']) );
        $affiliate_html_text = '';//(isset($_POST['affiliate_html_text']) ? $_POST['affiliate_html_text'] : '' );
        $affiliate_banners_image = (isset($_POST['affiliate_banners_image']) ? tep_db_prepare_input($_POST['affiliate_banners_image']) : '');
        $affiliate_banners_image_local = (isset($_POST['affiliate_banners_image_local']) ? tep_db_prepare_input($_POST['affiliate_banners_image_local']) : '' ) ;
        $affiliate_banners_image_target = (isset($_POST['affiliate_banners_image_target']) ? tep_db_prepare_input($_POST['affiliate_banners_image_target']) : '' ) ;
        $db_image_location = '';
        $affiliate_banners_status = tep_db_prepare_input($_POST['affiliate_banners_status']);

        $affiliate_banner_error = false;
        if (empty($affiliate_banners_title)) {
          $messageStack->add('search', ERROR_BANNER_TITLE_REQUIRED, 'error');
          $affiliate_banner_error = true;
        }

       if ( (isset($affiliate_banners_image)) && ($affiliate_banners_image != 'none') && (is_uploaded_file($affiliate_banners_image)) ) {
          if (!is_writeable(DIR_FS_CATALOG_IMAGES . $affiliate_banners_image_target)) {
            if (is_dir(DIR_FS_CATALOG_IMAGES . $affiliate_banners_image_target)) {
              $messageStack->add('search', ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
            } else {
              $messageStack->add('search', ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
            }
            $affiliate_banner_error = true;
          }
        }

        if (!$affiliate_banner_error) {
          if (empty($affiliate_html_text)) {
          if (empty($affiliate_banners_image_local)) {
            $affiliate_banners_image = new upload('affiliate_banners_image');
            $affiliate_banners_image->set_destination(DIR_FS_CATALOG_IMAGES . $affiliate_banners_image_target);
            if ( ($affiliate_banners_image->parse() == false) || ($affiliate_banners_image->save() == false) ) {
              $affiliate_banner_error = true;
            }
          }
            $db_image_location = (!empty($affiliate_banners_image_local)) ? $affiliate_banners_image_local : $affiliate_banners_image_target . $affiliate_banners_image->filename;
          }

          if (!isset($affiliate_products_id)){
           $affiliate_products_id="0";
           }

          if (!isset($affiliate_category_id)){
          $affiliate_category_id="0";
           }

            $sql_data_array = array('affiliate_banners_title' => $affiliate_banners_title,
                                    'affiliate_products_id' => $affiliate_products_id,
                                    'affiliate_category_id' => $affiliate_category_id,
                                    'affiliate_banners_image' => $db_image_location,
                                    'affiliate_banners_group' => $affiliate_banners_group,
                                    'affiliate_status' => $affiliate_banners_status);

          if ($action == 'insert') {
            $insert_sql_data = array('affiliate_date_added' => 'now()');
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_AFFILIATE_BANNERS, $sql_data_array);
            $affiliate_banners_id = tep_db_insert_id();

          // Banner ID 1 is generic Product Banner
            if ($affiliate_banners_id==1) tep_db_query("update " . TABLE_AFFILIATE_BANNERS . " set affiliate_banners_id = affiliate_banners_id + 1");
            $messageStack->add_session('search', SUCCESS_BANNER_INSERTED, 'success');
          } elseif ($action == 'update') {
            $insert_sql_data = array('affiliate_date_status_change' => 'now()');
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_AFFILIATE_BANNERS, $sql_data_array, 'update', 'affiliate_banners_id = \'' . $affiliate_banners_id . '\'');
            $messageStack->add_session('search', SUCCESS_BANNER_UPDATED, 'success');
          }

          tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $page . '&abID=' . $affiliate_banners_id));
        } else {
          $action = 'new';
        }
        break;
      case 'deleteconfirm':
        $affiliate_banners_id = tep_db_prepare_input($abID);
        $delete_image = (isset($_POST['delete_image']) ? tep_db_prepare_input($_POST['delete_image']) : '');

        if ($delete_image == 'on') {
          $affiliate_banner_query = tep_db_query("select affiliate_banners_image from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . tep_db_input($affiliate_banners_id) . "'");
          $affiliate_banner = tep_db_fetch_array($affiliate_banner_query);
          if (is_file(DIR_FS_CATALOG_IMAGES . $affiliate_banner['affiliate_banners_image'])) {
            if (is_writeable(DIR_FS_CATALOG_IMAGES . $affiliate_banner['affiliate_banners_image'])) {
              @unlink(DIR_FS_CATALOG_IMAGES . $affiliate_banner['affiliate_banners_image']);
            } else {
              $messageStack->add_session('search', ERROR_IMAGE_IS_NOT_WRITEABLE, 'error');
            }
          } else {
            $messageStack->add_session('search', ERROR_IMAGE_DOES_NOT_EXIST, 'error');
          }
        }

        tep_db_query("delete from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . tep_db_input($affiliate_banners_id) . "'");
        tep_db_query("delete from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_id = '" . tep_db_input($affiliate_banners_id) . "'");

        $messageStack->add_session('search', SUCCESS_BANNER_REMOVED, 'success');

        tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $page));
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
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=300,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=300,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($action == 'new') {
    $form_action = 'insert';
    if ($abID) {
      $abID = tep_db_prepare_input($abID);
      $form_action = 'update';

      $affiliate_banner_query = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . tep_db_input($abID) . "'");
      $affiliate_banner = tep_db_fetch_array($affiliate_banner_query);

      $abInfo = new objectInfo($affiliate_banner);
    } elseif (tep_not_null($_POST)) {
      $abInfo = new objectInfo($_POST);
    } else {
      $abInfo = new objectInfo(array());
    }

    $groups_array = array();
    $groups_query = tep_db_query("select distinct affiliate_banners_group from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_group");
    while ($groups = tep_db_fetch_array($groups_query)) {
      $groups_array[] = array('id' => $groups['affiliate_banners_group'], 'text' => $groups['affiliate_banners_group']);
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php 
      echo tep_draw_form('new_banner', FILENAME_AFFILIATE_BANNER_MANAGER, (tep_not_null($page) ? 'page=' . $page . '&' : '') . 'action=' . $form_action, 'post', 'enctype="multipart/form-data"');
      if ($form_action == 'update') echo tep_draw_hidden_field('affiliate_banners_id', $abID); 
      ?>
        <td><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_STATUS; ?></td>
            <td class="main" colspan="2"><?php echo TEXT_BANNERS_STATUS_ACTIVE . tep_draw_radio_field('affiliate_banners_status','1',true) . ' &nbsp; ' . TEXT_BANNERS_STATUS_INACTIVE . tep_draw_radio_field('affiliate_banners_status','0'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_TITLE; ?></td>
            <td class="main" colspan="2"><?php echo tep_draw_input_field('affiliate_banners_title', (isset($abInfo->affiliate_banners_title) ?  $abInfo->affiliate_banners_title : '' ) , '', true); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_LINKED_PRODUCT; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_products_id', (isset($abInfo->affiliate_products_id) ? $abInfo->affiliate_products_id : ''), '', false); ?></td>
            <td class="main" colspan=2><?php echo TEXT_BANNERS_LINKED_PRODUCT_NOTE ?></td>
          </tr>
          <tr>
            <td class="main"></td>
            <td class="main"><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDPRODUCTS) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>&nbsp;' . TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW;?></td>
            <td class="main" colspan="2"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP;?></td>
          </tr>

<?php  // Category Banners ?>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_LINKED_CATEGORY; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_category_id', (isset($abInfo->affiliate_category_id) ? $abInfo->affiliate_category_id : ''), '', false); ?></td>
            <td class="main" colspan=2><?php echo TEXT_BANNERS_LINKED_CATEGORY_NOTE ?></td>
          </tr>
          <tr>
            <td class="main"></td>
            <td class="main"><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDCATS) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>&nbsp; ' . TEXT_AFFILIATE_CATEGORY_BANNER_VIEW;?></td>
            <td class="main" colspan="2"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP;?></td>
          </tr>
<?php // End Category Banners ?>

          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_BANNERS_IMAGE; ?></td>
            <td class="main" colspan="2"><?php echo tep_draw_file_field('affiliate_banners_image') . ' <br> ' . TEXT_BANNERS_IMAGE_LOCAL . '<br>' . DIR_FS_CATALOG_IMAGES . tep_draw_input_field('affiliate_banners_image_local', (isset($abInfo->affiliate_banners_image) ? $abInfo->affiliate_banners_image : '') ); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_IMAGE_TARGET; ?></td>
            <td class="main" colspan="2"><?php echo DIR_FS_CATALOG_IMAGES . tep_draw_input_field('affiliate_banners_image_target'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top" nowrap="nowrap"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $page . '&abID=' . $abID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE))?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_BANNERS; ?></td>
<?php // Added Category Banners
?>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_CATEGORY_ID; ?></td>
<?php // End Category Banners
?>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCT_ID; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATISTICS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $affiliate_banners_query_raw = "select * from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_title, affiliate_banners_group";
    $affiliate_banners_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $affiliate_banners_query_raw, $affiliate_banners_query_numrows);
    $affiliate_banners_query = tep_db_query($affiliate_banners_query_raw);
    while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_query)) {
      $info_query = tep_db_query("select sum(affiliate_banners_shown) as affiliate_banners_shown, sum(affiliate_banners_clicks) as affiliate_banners_clicks from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_id = '" . $affiliate_banners['affiliate_banners_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if (((!$abID) || ($abID == $affiliate_banners['affiliate_banners_id'])) && (!isset($abInfo)) && (substr($action, 0, 3) != 'new')) {
        $abInfo_array = array_merge($affiliate_banners, $info);
        $abInfo = new objectInfo($abInfo_array);
      }

      $affiliate_banners_shown = ($info['affiliate_banners_shown'] != '') ? $info['affiliate_banners_shown'] : '0';
      $affiliate_banners_clicked = ($info['affiliate_banners_clicks'] != '') ? $info['affiliate_banners_clicks'] : '0';

      if ( ((isset($abInfo)) && (is_object($abInfo)) ) && ($affiliate_banners['affiliate_banners_id'] == $abInfo->affiliate_banners_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_BANNERS,'abID=' . $abInfo->affiliate_banners_id . '&action=new')  . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_BANNERS, 'abID=' . $affiliate_banners['affiliate_banners_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="javascript:popupImageWindow(\'' . FILENAME_AFFILIATE_POPUP_IMAGE . '?banner=' . $affiliate_banners['affiliate_banners_id'] . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_popup.gif', ICON_PREVIEW) . '</a>&nbsp;' . $affiliate_banners['affiliate_banners_title']; ?></td>
<?php // Added Category Banners
?>
                <td class="dataTableContent" align="right"><?php if ($affiliate_banners['affiliate_category_id']>0) echo $affiliate_banners['affiliate_category_id']; else echo '&nbsp;'; ?></td>
<?php // End Category Banners
?>
                <td class="dataTableContent" align="right"><?php if ($affiliate_banners['affiliate_products_id']>0) echo $affiliate_banners['affiliate_products_id']; else echo '&nbsp;'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $affiliate_banners_shown . ' / ' . $affiliate_banners_clicked; ?></td>
                <td class="dataTableContent" align="right"><?php 
      if ($affiliate_banners['affiliate_status'] == 1) {
        echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, 'action=setaffiliate_flag&abID=' . $affiliate_banners['affiliate_banners_id'] . '&affiliate_flag=' . $affiliate_banners['affiliate_status']) . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, 'action=setaffiliate_flag&abID=' . $affiliate_banners['affiliate_banners_id'] . '&affiliate_flag=' . $affiliate_banners['affiliate_status']) . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>';
      }
      ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($abInfo) && is_object($abInfo) && ($affiliate_banners['affiliate_banners_id'] == $abInfo->affiliate_banners_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $page . '&abID=' . $affiliate_banners['affiliate_banners_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $affiliate_banners_split->display_count($affiliate_banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_BANNERS); ?></td>
                    <td class="smallText" align="right"><?php echo $affiliate_banners_split->display_links($affiliate_banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&action=new') . '">' . tep_image_button('button_new_banner.gif', IMAGE_NEW_BANNER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $abInfo->affiliate_banners_title . '</b>');
      $contents = array('form' => tep_draw_form('affiliate_banners', FILENAME_AFFILIATE_BANNER_MANAGER, 'page=' . $page . '&abID=' . $abInfo->affiliate_banners_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $abInfo->affiliate_banners_title . '</b>');
      if ($abInfo->affiliate_banners_image) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', 'on', true) . ' ' . TEXT_INFO_DELETE_IMAGE);
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $page . '&abID=' . $abID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
      break;
    default:
      if ( (isset($abInfo)) && (is_object($abInfo)) ) {
        $sql = "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $abInfo->affiliate_products_id . "' and language_id = '" . $languages_id . "'";
        $product_description_query = tep_db_query($sql);
        $product_description = tep_db_fetch_array($product_description_query);
        $heading[] = array('text' => '<b>' . $abInfo->affiliate_banners_title . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $page . '&abID=' . $abInfo->affiliate_banners_id . '&action=new') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $page . '&abID=' . $abInfo->affiliate_banners_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . $product_description['products_name']);
        $contents[] = array('text' => '<br>' . TEXT_BANNERS_DATE_ADDED . '<br><b>' . tep_date_short($abInfo->affiliate_date_added) . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_BANNERS_STATUS_CHANGE, '<br><b>' . tep_date_short($abInfo->affiliate_date_status_change)). '</b>');
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
  }
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
