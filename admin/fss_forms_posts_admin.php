<?php
/*
  $Id: fss_forms_posts_admin.php,v 1.0.0.0 2008/06/19 23:39:49 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;
require(DIR_WS_FUNCTIONS . FILENAME_FSS_FUNCTIONS);
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$listing = isset($_GET['listing']) ? $_GET['listing'] : '';
// RCI top
$cre_RCI->get('global', 'top');
$cre_RCI->get('fssformspostsadmin', 'top'); 
// local functions
function tep_get_admin_username($admin_id) {
  $admins = tep_db_fetch_array(tep_db_query("SELECT admin_firstname, admin_lastname FROM ". TABLE_ADMIN . " WHERE admin_id='".$admin_id."'"));
  return $admins['admin_firstname'].' '.$admins['admin_lastname'];
}
$status_query = tep_db_query("select forms_posts_status_id, status_value from " . TABLE_FSS_FORMS_POSTS_STATUS );
$status_array = array();
$forms_posts_status_array_used = array();
while( $forms_posts_status_temp = tep_db_fetch_array($status_query)) {
  $status_array[] = array('id'=>$forms_posts_status_temp['forms_posts_status_id'],
                          'text'=>$forms_posts_status_temp['status_value']);
  $forms_posts_status_array_used[$forms_posts_status_temp['forms_posts_status_id']]=$forms_posts_status_temp['status_value']; 
}
unset($status_query);unset($forms_posts_status_temp);
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$error = false;
$processed = false;
$forms_name = tep_db_fetch_array(tep_db_query("select forms_name from " . TABLE_FSS_FORMS_DESCRIPTION . " where forms_id = '" . (int)$_GET['fID'] . "' and language_id = '" . $languages_id . "'"));
if (tep_not_null($action)) {
  switch ($action) {
    case 'delete_confirm':
      tep_db_query("delete from " . TABLE_FSS_FORMS_POSTS . " where  forms_posts_id = '" . $_GET['pID'] . "'");
      tep_db_query("delete from " . TABLE_FSS_FORMS_POSTS_CONTENT . " where  forms_posts_id = '" . $_GET['pID'] . "'");
      tep_db_query("delete from " . TABLE_FSS_FORMS_POSTS_NOTES . " where  forms_posts_id = '" . $_GET['pID'] . "'");
      tep_redirect(tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action'))));
      break;
    case 'updatestatus':
      tep_db_query('UPDATE '. TABLE_FSS_FORMS_POSTS. " SET posts_status_value='".$_POST['posts_status']. "' WHERE forms_id='".$_GET['fID']."' AND forms_posts_id='".$_GET['pID']."'");
      tep_redirect(tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action'))));
      break;
    case 'add_note':
      if (tep_not_null($_POST['notes_value'])) {
        $notes_array = array ('forms_id'=>$_POST['forms_id'],
                              'forms_posts_id'=> $_POST['posts_id'],
                              'notes_value'=> $_POST['notes_value'],
                              'notes_admin_id'=> $_SESSION['login_id'],
                              'notes_date'=>'now()');
        tep_db_perform(TABLE_FSS_FORMS_POSTS_NOTES, $notes_array);
      }
      tep_db_query('UPDATE '. TABLE_FSS_FORMS_POSTS. " SET posts_status_value='".$_POST['posts_status']. "' WHERE forms_id='".$_GET['fID']."' AND forms_posts_id='".$_GET['pID']."'");
      tep_redirect(tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action')).'&action=view_entry'));
      break;
    case "requests_status_update":
      $requests_id = tep_db_prepare_input($_GET['rID']);
       tep_db_query("UPDATE " . TABLE_REQUEST_BETTERPRICE . " SET requests_status = '".$_POST['request_status']."' WHERE requests_id = '" . (int)$requests_id . "'");
      tep_redirect(tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action')).'&action=view_entry'));
      break;
    default:
      $forms_posts_query = tep_db_query("SELECT forms_posts_id, forms_id, posts_status_value, posts_date, orders_id, products_id, customers_id 
                                           from " . TABLE_FSS_FORMS_POSTS . "
                                         WHERE forms_id = '" . (int)$_GET['fID'] . "' 
                                           and forms_posts_id='".(int)$_GET['pID']."'");
      $fprequest = tep_db_fetch_array($forms_posts_query);
      if(is_array($fprequest) && count($fprequest)>0) {
        $fpInfo = new objectInfo($fprequest);
      }
      break;
  }
} else {
  if((isset($_GET['fID'])&& $_GET['fID']!='')) {
    $forms_posts_query = tep_db_query("SELECT forms_posts_id, forms_id, posts_status_value, posts_date, orders_id, products_id, customers_id 
                                         from " . TABLE_FSS_FORMS_POSTS . "
                                       WHERE forms_id = '" . (int)$_GET['fID'] . "'");
    $fprequest = tep_db_fetch_array($forms_posts_query);
    if(is_array($fprequest) && count($fprequest)>0) {
      $fpInfo = new objectInfo($fprequest);
    }
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

<style type="text/css">
TD.fss {
  border-right: 1px solid;
  border-bottom: 1px solid;
  border-color: #b6b7cb;
  font-family: Verdana, Arial, sans-serif;
  font-size: 11px;
}

TD.fss_header {
  border-right: 1px solid;
  border-top: 1px solid;
  border-bottom: 1px solid;
  border-color: #b6b7cb;
  font-family: Verdana, Arial, sans-serif;
  font-size: 11px;
}

TD.fss_left {
  border-right: 1px solid;
  border-bottom: 1px solid;
  border-left: 1px solid;
  border-color: #b6b7cb;
  font-family: Verdana, Arial, sans-serif;
  font-size: 11px;
}

TD.fss_left_header {
  border-right: 1px solid;
  border-bottom: 1px solid;
  border-left: 1px solid;
  border-top: 1px solid;
  border-color: #b6b7cb;
  font-family: Verdana, Arial, sans-serif;
  font-size: 11px;
}
</style>
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
      <?php
      if ( $action == 'view_entry' && is_object($fpInfo) ) {
        ?>
        <tr>
          <td width="100%" colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE . ' - ' . $forms_name['forms_name']; ?></td>
              <td align="right"><a href="<?php echo tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action'))); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="formAreaTitle" colspan="2"><?php echo TEXT_FORMS_POSTS_VALUES?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <?php
        $valuequery = tep_db_query("SELECT forms_posts_content_id, questions_variable, questions_id, forms_fields_label, forms_fields_value
                                      from " . TABLE_FSS_FORMS_POSTS_CONTENT . " 
                                    WHERE forms_id = '" . $fpInfo->forms_id . "' 
                                      and forms_posts_id= '" . $fpInfo->forms_posts_id ."'"
                                  );
        if(tep_db_num_rows($valuequery)>0) {
          echo '<tr><td height="40" class="main" colspan="2">';
          echo '<table border="0" cellspacing="0" cellpadding="5">';
          echo '<tr><td align="center" width="200px" class="fss_left_header"><b>'.TEXT_TABLE_HEADING_FIELD_LABEL.'</b></td><td class="fss_header" width="400px" align="center"><b>'.TEXT_TABLE_HEADING_FIELD_VALUE.'</b></td><td class="fss_header" width="100px" align="center"><b>'.TEXT_TABLE_HEADING_FIELD_SPECIAL.'</b></td></tr>';
          while($fields_values = tep_db_fetch_array($valuequery)) {
            if (ereg("http://[^\s]", $fields_values['forms_fields_value'])) {
              $special = tep_fss_get_special_str('url', $fields_values['forms_fields_value']);
            } elseif (tep_fss_get_questions_type($fields_values['questions_id']) == 'File Upload') {
              $special = tep_fss_get_special_str('file', $fields_values['forms_fields_value']);
            } else {
              $special = tep_fss_get_special_str($fields_values['questions_variable'], $fields_values['forms_fields_value']);
            }
            echo '<tr><td class="fss_left"><b>'. $fields_values['forms_fields_label']
            . '</b></td><td class="fss">&nbsp;'. $fields_values['forms_fields_value']
            . '</td><td class="fss">&nbsp;'. $special
            . '</td></tr>';
          }
          echo "</table></td></tr>";
        }
        ?>
        <tr>
          <td colspan="2" height="20" width="100%"></td>
        </tr>
        <tr>
          <td colspan="2" height="3" bgcolor="#aaaaaa" width="100%"> </td>
        </tr>
        <tr>
          <td colspan="2" height="40">
            <?php echo '<b>'.TEXT_POSTS_DATE. "</b>&nbsp;&nbsp;". tep_date_short($fpInfo->posts_date); ?>
          </td>
        </tr>
        <tr><td colspan="2" height="3" bgcolor="#aaaaaa" width="100%"> </td></tr>
        <tr><td colspan="2" height="20" width="100%"></td></tr>
        <?php
        $notesquery = tep_db_query("SELECT notes_admin_id, notes_value, notes_date
                                      from " . TABLE_FSS_FORMS_POSTS_NOTES . "  
                                    WHERE forms_id = '" . $fpInfo->forms_id  ."' 
                                      and forms_posts_id = '" . $fpInfo->forms_posts_id ."'"
                                  );

        if(tep_db_num_rows($notesquery)>0) {
          echo '<tr><td class="formAreaTitle" colspan="2"><b>'.TEXT_TABLE_HEADING_NOTES.'</b></td></tr>';
          echo '<tr><td height="40" class="main" colspan="2">';
          echo '<table border="0" cellspacing="0" cellpadding="5">';
          $row = 0;
          while($notes_values = tep_db_fetch_array($notesquery)) {
            $row++;
            if ($row == 1) {
              echo '<tr><td width="80px" class="fss_left_header">'. tep_date_short($notes_values['notes_date']) . '</td>';
              echo '<td align="left" class="fss_header">'. $notes_values['notes_value']  . "</td>";
              echo '<td align="left" class="fss_header">' .tep_get_admin_username($notes_values['notes_admin_id']) . '</td></tr>';
            } else {
              echo '<tr><td width="80px" class="fss_left">'. tep_date_short($notes_values['notes_date']) . '</td>';
              echo '<td align="left" class="fss">'. $notes_values['notes_value']  . "</td>";
              echo '<td align="left" class="fss">' .tep_get_admin_username($notes_values['notes_admin_id']) . '</td></tr>';
            }        
          }
          echo "</table></td></tr>";
          ?>
          <tr><td colspan="2" height="20" width="100%"></td></tr>
          <tr><td colspan="2" height="3" bgcolor="#aaaaaa" width="100%"> </td></tr>
          <tr><td colspan="2" height="20" width="100%"></td></tr>
          <?php
        }
        echo tep_draw_form('notes', FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action')) . 'action=add_note', 'post').tep_draw_hidden_field('forms_id', $fpInfo->forms_id).tep_draw_hidden_field('posts_id', $fpInfo->forms_posts_id);
        ?>
        <tr>
          <td colspan="2"><b><?php echo TEXT_HEADING_NOTES_COMMENTS?></b></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo tep_draw_textarea_field('notes_value','soft', '120', '5')?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo TEXT_STATUS . '&nbsp;' . tep_draw_pull_down_menu('posts_status', $status_array, $fpInfo->posts_status_value); ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td align="center" class="main" colspan="2"><?php echo tep_image_submit('button_update_notes.gif', IMAGE_UPDATE_NOTE);?></td>
          <?php echo '<a href="'.tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action'))).'">'. tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';?>
        </tr></form>
        <?php
      } else {
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE . ' - ' . $forms_name['forms_name']; ?></td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
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
                      $order = "fp.forms_posts_id";
                      break;
                    case "id-desc":
                      $order = "fp.forms_posts_id DESC";
                      break;
                    case "field":
                      $order = "pc.forms_fields_value";
                      break;
                    case "field-desc":
                      $order = "pc.forms_fields_value DESC";
                      break;
                    default:
                      $order = "fp.forms_posts_id DESC";
                  }
                  if (isset($_GET[tep_session_name()])) {
                    $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
                  } else {
                    $oscid = '';
                  }
                  ?>
                  <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_FORMS_POSTS_ID; ?><a href="<?php echo "$PHP_SELF?listing=id" . $oscid; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TEXT_HEADING_FORMS_POSTS_ID . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=id-desc" . $oscid; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TEXT_HEADING_FORMS_POSTS_ID . ' --> Z-X-Y From Top '); ?></a></td>
                  <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_FIRST_VALUE; ?></td>
                  <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_FORMS_POSTS_FIELDS; ?><a href="<?php echo "$PHP_SELF?listing=field" . $oscid; ?>"><?php echo tep_icon_submit('ic_up.gif', ' Sort ' . TEXT_HEADING_FORMS_POSTS_FIELDS . ' --> A-B-C From Top '); ?></a>&nbsp;<a href="<?php echo "$PHP_SELF?listing=field-desc" . $oscid; ?>"><?php echo tep_icon_submit('ic_down.gif', ' Sort ' . TEXT_HEADING_FORMS_POSTS_FIELDS . ' --> Z-X-Y From Top '); ?></a></td>
                  <td class="dataTableHeadingContent" valign="top"><?php echo TEXT_HEADING_FORMS_POSTS_STATUS; ?></td>
                  <td class="dataTableHeadingContent" align="right" valign="top"><?php echo tep_draw_separator('pixel_trans.gif', '11', '12'); ?><?php echo TEXT_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
                <?php
                $forms_posts_query_raw = strtolower("SELECT fp.forms_id, fp.forms_posts_id, fp.posts_status_value, fp.posts_date,fp.orders_id, fp.products_id,fp.customers_id, count(pc.forms_posts_content_id) AS fields_count 
                                                       from " . TABLE_FSS_FORMS_POSTS . " fp, 
                                                            " . TABLE_FSS_FORMS_POSTS_CONTENT." pc 
                                                     WHERE fp.forms_posts_id = pc.forms_posts_id 
                                                       and fp.forms_id = '" . (isset($_GET['fID']) ? $_GET['fID'] : '') . "' 
                                                     GROUP BY fp.forms_posts_id 
                                                     ORDER BY " . $order);
                $forms_posts_query1 = tep_db_query($forms_posts_query_raw);
                $forms_posts_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $forms_posts_query_raw, $forms_posts_query_numrows);
                $forms_posts_query = tep_db_query($forms_posts_query_raw);
                $forms_posts_query_numrows = tep_db_num_rows($forms_posts_query1);
                $row = 0;
                while ($forms_posts = tep_db_fetch_array($forms_posts_query)) {
                  $row++;
                  if ((!isset($_GET['pID']) && $row == 1) || (isset($_GET['pID']) && $forms_posts['forms_posts_id'] == $_GET['pID'])) {
                    $forms_posts_query_temp = tep_db_query("SELECT forms_posts_id, forms_id, posts_status_value, posts_date, orders_id, products_id, customers_id 
                                                              from " . TABLE_FSS_FORMS_POSTS . " 
                                                            WHERE forms_id = '" . (int)$forms_posts['forms_id'] . "' 
                                                              and forms_posts_id='".(int)$forms_posts['forms_posts_id']."'");
                    $ftemp_array = tep_db_fetch_array($forms_posts_query_temp);
                    $fpInfo = new objectInfo($ftemp_array);
                  }
                  if (isset($fpInfo) && is_object($fpInfo) && ($forms_posts['forms_posts_id'] == $fpInfo->forms_posts_id || $forms_posts['forms_posts_id'] == $fpInfo->forms_posts_id)) {
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $forms_posts['forms_posts_id'] . '&action=edit') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('pID','action')) . 'pID=' . $forms_posts['forms_posts_id']) . '\'">' . "\n";
                  }
                  ?>
                  <td class="dataTableContent">
                    <?php echo $forms_posts['forms_posts_id'];?>
                  </td>
                  <td class="dataTableContent">
                    <?php
                     $firstvalue_query = tep_db_query("SELECT forms_fields_value FROM ".TABLE_FSS_FORMS_POSTS_CONTENT . " WHERE forms_posts_id = '".$forms_posts['forms_posts_id']."'");
                     if($firstarray = tep_db_fetch_array($firstvalue_query))
                     if (strlen($firstarray['forms_fields_value']) > 36 ) {
                       print ("<acronym title=\"".$firstarray['forms_fields_value']."\">".substr($forms_fields_value['forms_fields_value'], 0, 36)."&#160;</acronym>");
                     } else { 
                       echo $firstarray['forms_fields_value']; 
                     }
                     ?>
                    </td>
                    <td class="dataTableContent">
                      <?php echo $forms_posts['fields_count']; ?>
                    </td>
                    <td class="dataTableContent">
                      <?php echo $forms_posts_status_array_used[$forms_posts['posts_status_value']];?>
                    </td>
                    <td class="dataTableContent" align="right">
                      <?php if (isset($fpInfo->pID) && is_object($fpInfo) && ($forms_posts['forms_posts_id'] == $fpInfo->forms_posts_id || $forms_posts['forms_posts_id'] == $fpInfo->pID)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $forms_posts['forms_posts_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;
                    </td>
                  </tr>
                  <?php
                }
                ?>
                <tr>
                  <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText" valign="top"><?php echo $forms_posts_split->display_count($forms_posts_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_FORMS_POSTS); ?></td>
                      <td class="smallText" align="right"><?php echo $forms_posts_split->display_links($forms_posts_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'pID'))); ?></td>
                    </tr>
                    <?php
                    if (!tep_not_null($action)) {
                      ?>
                      <tr>
                        <td align="right" colspan="2"><?php echo '<a href="javascript:history.go(-1)">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'?></td>
                      </tr>
                      <?php
                    }
                    ?>
                    <tr>
                      <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>              
                          <?php
                          // RCI listing bottom
                          echo $cre_RCI->get('fssformspostsadmin', 'listingbottom');
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
              if(isset($fpInfo->forms_posts_id) && $fpInfo->forms_posts_id!='' && $fpInfo->forms_id!='') {
                switch ($action) {
                  case 'delete':
                    $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'<b>' . TEXT_FORMS_POSTS_DELETE. '</b>');
                    $contents[] = array('align' => 'left', 'text' => TEXT_FORMS_POSTS_DELETE_CONFIRM);
                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action')) . 'action=delete_confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                    break;
                  default:
                    if (isset($fpInfo) && is_object($fpInfo)) {
                      $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'<b>' . TEXT_INFORBOX_FORMS_POSTS_HEADING. '</b>');
                      $contents[] = array('form' => tep_draw_form('changestatus', FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('action')).'&page=' . $_GET['page'] . '&action=updatestatus'));
                      $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $fpInfo->forms_posts_id . '&action=view_entry') .'">' . tep_image_button('button_view_entry.gif', IMAGE_VIEW_POSTS_ENTRY) . '</a> <a href="' . tep_href_link(FILENAME_FSS_FORMS_POSTS_ADMIN, tep_get_all_get_params(array('pID', 'action')) . 'action=delete&pID=' . $fpInfo->forms_posts_id) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                      $contents[] = array('align' => 'left', 'text' => '<br>'. TEXT_STATUS . '<br>' . tep_draw_pull_down_menu('posts_status', $status_array, $fpInfo->posts_status_value)); //single
                      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update_status.gif', IMAGE_EDIT_UPDATE_STATUS));
                      $contents[] = array('text' => '</form>');
                      if (tep_not_null($fpInfo->customers_id)) {
                        $customers = tep_db_fetch_array(tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $fpInfo->customers_id . "'"));
                        $contents[] = array('align' => 'left', 'text' => '<br>' . TEXT_FORMS_POSTS_CUSTOMERS . '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'action=edit&cID=' . $fpInfo->customers_id) . '">' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>');
                      }
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
      }
      // RCI bottom
      $cre_RCI->get('fssformspostsadmin', 'bottom'); 
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