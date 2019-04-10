<?php
/*
  $Id: fss_post_manager.php,v 1.0.0.0 2008/06/19 23:39:49 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
// RCI top
$cre_RCI->get('global', 'top');
$cre_RCI->get('fsspostmanager', 'top'); 
$listing = isset($_GET['listing']) ? $_GET['listing'] : '';
$status_query = tep_db_query("select forms_posts_status_id, status_value from " . TABLE_FSS_FORMS_POSTS_STATUS );
$status_array = array();
$forms_posts_status_array_used = array();
while( $forms_posts_status_temp = tep_db_fetch_array($status_query)) {
  $status_array[] = array('id'=>$forms_posts_status_temp['forms_posts_status_id'],
                          'text'=>$forms_posts_status_temp['status_value']);
  $forms_posts_status_array_used[$forms_posts_status_temp['forms_posts_status_id']]=$forms_posts_status_temp['status_value']; 
}
unset($status_query);
unset($forms_posts_status_temp);
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$error = false;
$processed = false;
if (tep_not_null($action)) {
  switch ($action) {
    case 'purgeconfirm':
      tep_db_query("DELETE FROM " . TABLE_FSS_FORMS_POSTS . " WHERE forms_id = '" . $_GET['fID'] . "'");
      tep_db_query("DELETE FROM " . TABLE_FSS_FORMS_POSTS_CONTENT . " WHERE forms_id = '" . $_GET['fID'] . "'");
      tep_db_query("DELETE FROM " . TABLE_FSS_FORMS_POSTS_NOTES . " WHERE forms_id = '" . $_GET['fID'] . "'");
      tep_redirect(tep_href_link(FILENAME_FSS_POST_MANAGER));
      break;
    default:
      $forms_query = tep_db_query("select f.forms_id, fd.forms_name, count(p.forms_posts_id) as posts from " . TABLE_FSS_FORMS . " f left join ".TABLE_FSS_FORMS_POSTS." p on f.forms_id = p.forms_id, " . TABLE_FSS_FORMS_DESCRIPTION . " fd where f.forms_id = fd.forms_id and fd.language_id = '" . $languages_id . "' and f.forms_id = '" . (int)$_GET['fID'] . "' group by f.forms_id");
      $forms = array();
      $forms = tep_db_fetch_array($forms_query);
      $fInfo = new objectInfo(array_merge((array)$forms, (array)$_GET));
       break;
  }
} else {
  if(isset($_GET['fID'])&& $_GET['fID']!='') {
    $forms_query = tep_db_query("select f.forms_id, fd.forms_name from " . TABLE_FSS_FORMS . " f, " . TABLE_FSS_FORMS_DESCRIPTION . " fd where f.forms_id = fd.forms_id and fd.language_id = '" . $languages_id . "' and f.forms_id = '" . (int)$_GET['fID'] . "'");
    $forms = array();
    $forms = tep_db_fetch_array($forms_query);
    $fInfo = new objectInfo(array_merge((array)$forms, (array)$_GET));
  }
}
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
        <td align="center"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <?php 
            echo tep_draw_form('search', FILENAME_FSS_POST_MANAGER, '', 'get'); 
            echo tep_draw_hidden_field(tep_session_name(), tep_session_id());         
            ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <?php
                switch ($listing) {
                  case "id":
                    $order = "f.forms_id";
                    break;
                  case "id-desc":
                    $order = "f.forms_id DESC";
                    break;
                  case "name":
                    $order = "fd.forms_name";
                    break;
                  case "name-desc":
                    $order = "fd.forms_name DESC";
                    break;
                  case "post":
                    $order = "posts";
                    break;
                  case "post-desc":
                    $order = "posts DESC";
                    break;
                  default:
                    $order = "f.forms_id";
                }
                if (isset($_GET[tep_session_name()])) {
                  $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
                } else {
                  $oscid = '';
                }
                ?>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_FORMS_ID; ?><a href="<?php echo "$PHP_SELF?listing=id" . $oscid; ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_FORMS_ID . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=id-desc" . $oscid; ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_FORMS_ID . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_FORM_NAME; ?><a href="<?php echo "$PHP_SELF?listing=name" . $oscid; ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_FORM_NAME . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=name-desc" . $oscid; ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_FORM_NAME . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_FORMS_POSTS; ?><a href="<?php echo "$PHP_SELF?listing=post" . $oscid; ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_up.gif', ' Sort ' . TEXT_HEADING_FORMS_POSTS . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=post-desc" . $oscid; ?>"><?php echo tep_image(DIR_WS_LANGUAGES . $language .'/images/buttons/ic_down.gif', ' Sort ' . TEXT_HEADING_FORMS_POSTS . ' --> Z-X-Y From Top '); ?></a></td>
                <td class="dataTableHeadingContent" align="right" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '11', '12'); ?><?php echo TEXT_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $search = '';
              if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
                $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
                $search = " and fd.forms_name like '%" . $keywords . "%' ";
              }
              $forms_query_raw = "select f.forms_id, fd.forms_name from " . TABLE_FSS_FORMS . " f, " . TABLE_FSS_FORMS_DESCRIPTION . " fd where f.forms_id = fd.forms_id and fd.language_id = '" . $languages_id . "'" . $search . " GROUP BY f.forms_id" . " ORDER BY " . $order ;
              $forms_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $forms_query_raw, $forms_query_numrows);
              $forms_query = tep_db_query($forms_query_raw);
              while ($forms = tep_db_fetch_array($forms_query)) {
                if ((!isset($_GET['fID']) || (isset($_GET['fID']) && ($_GET['fID'] == $forms['forms_id']))) && !isset($fInfo)) {
                  $forms_query_temp = tep_db_query("select f.forms_id, fd.forms_name from " . TABLE_FSS_FORMS . " f, " . TABLE_FSS_FORMS_DESCRIPTION . " fd where f.forms_id = fd.forms_id and fd.language_id = '" . $languages_id . "' and f.forms_id = '" . (int)$forms['forms_id'] . "'");
                  $ftemp_array = tep_db_fetch_array($forms_query_temp);
                  $fInfo = new objectInfo($ftemp_array);
                }
                if ((isset($fInfo) && is_object($fInfo) && ($forms['forms_id'] == $fInfo->forms_id) || (isset($fInfo->fID) && $forms['forms_id'] == $fInfo->fID))) {
                  echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FSS_POST_MANAGER, tep_get_all_get_params(array('fID', 'action')) . 'fID=' . $forms['forms_id'] . '&action=edit') . '\'">' . "\n";
                } else {
                  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FSS_POST_MANAGER, tep_get_all_get_params(array('fID','action')) . 'fID=' . $forms['forms_id']) . '\'">' . "\n";
                }
                ?>
                <td class="dataTableContent">
                  <?php
                  if (strlen($forms['forms_name']) > 36 ) {
                    print ("<acronym title=\"".$forms['forms_id']."\">".substr($forms['forms_id'], 0, 16)."&#160;</acronym>");
                  } else {
                    echo $forms['forms_id']; 
                  } 
                  ?>
                </td>
                <td class="dataTableContent">
                  <?php
                  if (strlen($forms['forms_name']) > 15 ) {
                    print ("<acronym title=\"".$forms['forms_name']."\">".substr($forms['forms_name'], 0, 15)."&#160;</acronym>");
                  } else {
                    echo $forms['forms_name']; 
                  } 
                  ?>
                </td>
                <td class="dataTableContent">
                  <?php 
                  $forms_query_post_temp = tep_db_fetch_array(tep_db_query("select COUNT(p.forms_posts_id) AS posts from " . TABLE_FSS_FORMS . " f, ". TABLE_FSS_FORMS_POSTS." p where f.forms_id = p.forms_id AND f.forms_id= '".$forms['forms_id']."'"));
                  echo $forms_query_post_temp['posts']; ?>
                </td>
                <td class="dataTableContent" align="right">
                  <?php 
                  if ((isset($fInfo) && is_object($fInfo) && ($forms['forms_id'] == $fInfo->forms_id) || (isset($fInfo->fID) && $forms['forms_id'] == $fInfo->fID))) { 
                    echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
                  } else { 
                    echo '<a href="' . tep_href_link(FILENAME_FSS_POST_MANAGER, tep_get_all_get_params(array('fID', 'action')) . 'fID=' . $forms['forms_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; 
                  } 
                  ?>&nbsp;
                </td>
                </tr>
                <?php
              }
              ?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $forms_split->display_count($forms_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_FORMS); ?></td>
                    <td class="smallText" align="right"><?php echo $forms_split->display_links($forms_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'fID'))); ?></td>
                  </tr>
                  <?php
                  if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
                    ?>
                    <tr>
                      <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_FSS_POST_MANAGER) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                  <tr>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>              
                        <?php
                        // RCI listing bottom
                        echo $cre_RCI->get('fsspostmanager', 'listingbottom');
                        ?>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <?php
            $heading = array();
            $contents = array();
            if($fInfo->forms_id!='') {
              switch ($action) {
                case 'purge':
                  $heading[] = array('align'=>'left', 'text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'<b>' . sprintf(TEXT_INFORBOX_FORMS_PURGE_HEADING, $fInfo->forms_name). '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_FSS_POST_MANAGER, tep_get_all_get_params(array('fID', 'action')) . 'fID=' . $fInfo->forms_id) . '&action=purgeconfirm' . '">' . tep_image_button('button_confirm.gif', IMAGE_CONFIRM) . '</a><a href="' . tep_href_link(FILENAME_FSS_POST_MANAGER, tep_get_all_get_params(array('fID', 'action')) . 'fID=' . $fInfo->forms_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                  break;
                default:
                  if (isset($fInfo) && is_object($fInfo)) {
                    $heading[] = array('align'=>'left', 'text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'<b>' . sprintf(TEXT_INFORBOX_FORMS_HEADING, $fInfo->forms_name). '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('fID', 'action')) . 'fID=' . $fInfo->forms_id) . '">' . tep_image_button('button_view_posts.gif', IMAGE_VIEW_POSTS) . '</a><a href="' . tep_href_link(FILENAME_FSS_POST_MANAGER, tep_get_all_get_params(array('fID', 'action')) . 'fID=' . $fInfo->forms_id . '&action=purge') . '">' . tep_image_button('button_purge.gif', IMAGE_PURGE) . '</a>');
                  }
                  break;
              }
              if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                echo '<td width="25%" valign="top">' . "\n";
                $box = new box;
                echo $box->infoBox($heading, $contents);
                echo '</td>' . "\n";
              }
            }
            ?>
          </tr>
        </table></td>
      </tr>
      <?php
      // RCI bottom
      $cre_RCI->get('fsspostmanager', 'bottom'); 
      $cre_RCI->get('global', 'bottom');                                        
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