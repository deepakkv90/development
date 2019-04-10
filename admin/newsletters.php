<?php
/*
  $Id: newsletters.php,v 1.1.1.1 2004/03/04 23:38:49 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'lock':
      case 'unlock':
        $newsletter_id = tep_db_prepare_input($_GET['nID']);
        $status = (($action == 'lock') ? '1' : '0');

        tep_db_query("update " . TABLE_NEWSLETTERS . " set locked = '" . $status . "' where newsletters_id = '" . (int)$newsletter_id . "'");

        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']));
        break;
      case 'insert':
      case 'update':
        if (isset($_POST['newsletter_id'])) $newsletter_id = tep_db_prepare_input($_POST['newsletter_id']);
        $newsletter_module = tep_db_prepare_input($_POST['module']);
        $title = tep_db_prepare_input($_POST['title']);
        $content = tep_db_prepare_input($_POST['content']);
        $module = $newsletter_module;
        $newsletter_error = false;
        if (empty($title)) {
          $messageStack->add('search', ERROR_NEWSLETTER_TITLE, 'error');
          $newsletter_error = true;
        }

        if (empty($module)) {
          $messageStack->add('search', ERROR_NEWSLETTER_MODULE, 'error');
          $newsletter_error = true;
        }

        if ($newsletter_error == false) {
          $sql_data_array = array('title' => $title,
                                  'content' => $content,
                                  'module' => $newsletter_module);

          if ($action == 'insert') {
            $sql_data_array['date_added'] = 'now()';
            $sql_data_array['status'] = '0';
            $sql_data_array['locked'] = '0';

            tep_db_perform(TABLE_NEWSLETTERS, $sql_data_array);
            $newsletter_id = tep_db_insert_id();
          } elseif ($action == 'update') {
            tep_db_perform(TABLE_NEWSLETTERS, $sql_data_array, 'update', "newsletters_id = '" . (int)$newsletter_id . "'");
          }

          tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'nID=' . $newsletter_id));
        } else {
          $action = 'new';
        }
        break;
      case 'deleteconfirm':
        $newsletter_id = tep_db_prepare_input($_GET['nID']);

        tep_db_query("delete from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$newsletter_id . "'");

        tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page']));
        break;
      case 'delete':
      case 'new': if (!isset($_GET['nID'])) break;
      case 'send':
      case 'confirm_send':
        $newsletter_id = tep_db_prepare_input($_GET['nID']);

        $check_query = tep_db_query("select locked from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$newsletter_id . "'");
        $check = tep_db_fetch_array($check_query);

        if ($check['locked'] < 1) {
          switch ($action) {
            case 'delete': $error = ERROR_REMOVE_UNLOCKED_NEWSLETTER; break;
            case 'new': $error = ERROR_EDIT_UNLOCKED_NEWSLETTER; break;
            case 'send': $error = ERROR_SEND_UNLOCKED_NEWSLETTER; break;
            case 'confirm_send': $error = ERROR_SEND_UNLOCKED_NEWSLETTER; break;
          }

          $messageStack->add_session('search', $error, 'error');

          tep_redirect(tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']));
        }
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
<?php 
// Load Editor
echo tep_load_html_editor();
echo tep_insert_html_editor('content','advanced','500');
?>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->

<div id="body">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($action == 'new') {
    $form_action = 'insert';
    
    $parameters = array('title' => '',
                        'content' => '',
                        'module' => '');
    
    $nInfo = new objectInfo($parameters);
    
    if (isset($_GET['nID'])) {
      $form_action = 'update';
      
      $nID = tep_db_prepare_input($_GET['nID']);
      
      $newsletter_query = tep_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$nID . "'");
      $newsletter = tep_db_fetch_array($newsletter_query);
      
      $nInfo->objectInfo($newsletter);
    } elseif ($_POST) {
      $nInfo->objectInfo($_POST);
    }
    
    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
    $directory_array = array();
  
    if ($dir = dir(DIR_WS_MODULES . 'newsletters/')) {
      while ($file = $dir->read()) {
        if (!is_dir(DIR_WS_MODULES . 'newsletters/' . $file)) {
          if (substr($file, strrpos($file, '.')) == $file_extension) {
            $directory_array[] = $file;
          }
        }
      }
      sort($directory_array);
      $dir->close();
    }
    
    for ($i=0, $n=sizeof($directory_array); $i<$n; $i++) {
      $modules_array[] = array('id' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')), 'text' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')));
    }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('newsletter', FILENAME_NEWSLETTERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'action=' . $form_action); if ($form_action == 'update') echo tep_draw_hidden_field('newsletter_id', $nID); ?>
        <td><table border="0" cellspacing="0" cellpadding="2" width="80%">
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_MODULE; ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('module', $modules_array, $nInfo->module); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_TITLE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('title', $nInfo->title, '', true); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_NEWSLETTER_CONTENT; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('content', 'soft', '100%', '20', $nInfo->content,' style="width: 100%" mce_editable="true"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right"><?php echo (($form_action == 'insert') ? tep_image_submit('button_save.gif', IMAGE_SAVE) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_NEWSLETTERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . (isset($_GET['nID']) ? 'nID=' . $_GET['nID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } elseif ($action == 'preview') {
    $nID = tep_db_prepare_input($_GET['nID']);

    $newsletter_query = tep_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$nID . "'");
    $newsletter = tep_db_fetch_array($newsletter_query);

    $nInfo = new objectInfo($newsletter);

    $module_name = $nInfo->module;
    if($module_name == "newsletter") {
      $frm_action =  'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm';
      $s_module_name = TEXT_CUSTOMER_NEWSLETTER_NAME;
    } else if($module_name == "product_notification") {
      $frm_action =  'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm' ;
      $s_module_name = TEXT_PRODUCT_NOTIFICATIONS_NEWSLETTER_NAME;
    } else if($module_name == "affiliate_newsletter") {
      $s_module_name = TEXT_AFFILIATE_NEWSLETTER_NAME;
    }
?>
      <tr>
        <td class="main"><B><?php echo TEXT_NEWSLETTER_MODULE;?></B><?php echo $s_module_name;?></td>
      </tr>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
      <tr>
        <td><tt><?php echo nl2br($nInfo->content); ?></tt></td>
      </tr>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } elseif ($action == 'send') {
    $nID = tep_db_prepare_input($_GET['nID']);
    
    if ( ($action == 'preview') && isset($_POST['customers_email_address']) ) {
      switch ($_POST['customers_email_address']) {
        case '***':
          $mail_sent_to = TEXT_ALL_CUSTOMERS;
          break;
        case '**D':
          $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
          break;
        default:
          $mail_sent_to = $_POST['customers_email_address'];
          break;
        }
    }
    
    $newsletter_query = tep_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$nID . "'");
    $newsletter = tep_db_fetch_array($newsletter_query);
    $nInfo = new objectInfo($newsletter);
    
    include(DIR_WS_LANGUAGES . $language . '/modules/newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
    include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
    
    $customers = array();    
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $frm_para = '';
    if($module_name == "newsletter") {
      $frm_action =  'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm';
      $s_module_name = TEXT_CUSTOMER_NEWSLETTER_NAME;
    } else if($module_name == "product_notification") {
      $frm_action =  'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm' ;
      $s_module_name = TEXT_PRODUCT_NOTIFICATIONS_NEWSLETTER_NAME;
    } else if($module_name == "affiliate_newsletter") {
      $s_module_name = TEXT_AFFILIATE_NEWSLETTER_NAME;
    }
    $frm_para = ' onSubmit="return selectAll_group(\'notifications\', \'chosen_group[]\')"';
?>
      <tr>
        <td class="main"><B><?php echo TEXT_NEWSLETTER_MODULE;?></B><?php echo $s_module_name;?></td>
      </tr>
      <!-- /***********************/ -->
      <table cellpadding = "3" cellspacing = "3" border = "0" align = "center" width = "60%">
        <tr>
        <td width  = "100%" align = "center">
          <?php echo tep_draw_form('notifications',FILENAME_NEWSLETTERS,$frm_action,'POST',$frm_para); ?>
            
            <?php
              $group = array();
              $group_query = tep_db_query("select customers_group_name,customers_group_id from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_name");
              while($group_values = tep_db_fetch_array($group_query)) {
                $group[] = array('id' => $group_values['customers_group_id'],
                                 'text' => $group_values['customers_group_name']);
              }
              $button_name_tmp = "button_send.gif";
              $button_alt_tmp = IMAGE_SEND;
              if($module_name == "newsletter") {
                global $button_name_tmp,$button_alt_tmp;
                $button_name_tmp = "button_send.gif";
                $button_alt_tmp = IMAGE_SEND;
           ?>
            <tr>
              <td><?php echo tep_draw_pull_down_menu('customers_email_address', $customers, (isset($_GET['customer']) ? $_GET['customer'] : ''));?></td>
            </tr>
            <?php
              } else if($module_name == "product_notification") {
                echo tep_draw_hidden_field('customers_email_address','***');
            }
            if($module_name == "newsletter" || $module_name == "product_notification") {
              $choose_group_string = '<script language="javascript"><!--
              function mover_group(move) {
                if (move == \'remove\') {
                  for (x=0; x<(document.notifications.groups_dropdown.length); x++) {
                    if (document.notifications.groups_dropdown.options[x].selected) {
                      with(document.notifications.elements[\'chosen_group[]\']) {
                        options[options.length] = new Option(document.notifications.groups_dropdown.options[x].text,document.notifications.groups_dropdown.options[x].value);
                      }
                      document.notifications.groups_dropdown.options[x] = null;
                      x = -1;
                    }
                  }
                }
                if (move == \'add\') {
                  for (x=0; x<(document.notifications.elements[\'chosen_group[]\'].length); x++) {
                    if (document.notifications.elements[\'chosen_group[]\'].options[x].selected) {
                      with(document.notifications.groups_dropdown) {
                        options[options.length] = new Option(document.notifications.elements[\'chosen_group[]\'].options[x].text,document.notifications.elements[\'chosen_group[]\'].options[x].value);
                      }
                      document.notifications.elements[\'chosen_group[]\'].options[x] = null;
                      x = -1;
                    }
                  }
                }
                return true;
              }
              
              function selectAll_group(FormName, SelectBox) {
                temp = "document." + FormName + ".elements[\'" + SelectBox + "\']";
                Source = eval(temp);
                for (x=0; x<(Source.length); x++) {
                  Source.options[x].selected = "true";
                }
                selectAll(\'notifications\', \'chosen[]\');
                if ( x < 1 ) {
                  alert(\'Please select any group\');
                  return false;
                } else {
                  return true;
                }
              }
              //--></script>';
              
              /* $choose_group_string .= '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                                      '  <tr>' . "\n" .
                                      '  <td align="center" class="main"><b>' . TEXT_GROUPS . '</b><br>' . tep_draw_pull_down_menu('groups_dropdown', $group, '','size="10" style="width: 20em;" multiple') . '</td>' . "\n" .
                                      '    <td align="center" class="main" colspan="2">&nbsp;<br>' . (isset($global_button) ? $global_button : '') . '<br><br><br><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover_group(\'remove\');"><br><br><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover_group(\'add\');"><br><br><br></td>' . "\n" .
                                      '    <td align="center" class="main"><b>' . TEXT_SELECTED_GROUPS . '</b><br>' . tep_draw_pull_down_menu('chosen_group[]', array(), '', 'size="10" style="width: 20em;" multiple') . '</td>' . "\n" .
                                      '  </tr>' . "\n" .
                                      '</table>';*/
              $choose_group_string .= '<table border="0" cellspacing="0" cellpadding="2" align="center" width="75%">' . "\n" .
                                      '  <tr>' . "\n" .
                                      '    <td align="center" class="main" width="25%"><b>' . TEXT_GROUPS . '</b><br>' . tep_draw_pull_down_menu('groups_dropdown', $group, '','size="10" style="width: 15em;" multiple') . '</td>' . "\n" .
                                      '    <td align="center" class="main" colspan="2" width="25%" valign="top"><table border="0"><tr><td align="left"><br>' . (isset($global_button) ? $global_button : '') . '<input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover_group(\'remove\');"></td></tr><br><tr><td align="left"><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover_group(\'add\');"></td></tr><tr><td align="left">';
                                      if($module_name == "newsletter") {
                                       $choose_group_string .=  $module->confirm1(); 
                                            };
                                      $choose_group_string .= '</td></tr></table>' . "\n" .
                                      '    <td align="center" class="main" width="25%"><b>' . TEXT_SELECTED_GROUPS . '</b><br>' . tep_draw_pull_down_menu('chosen_group[]', array(), '', 'size="10" style="width: 15em;" multiple') . '</td>' . "\n" .
                                      '  </tr>' . "\n" .
                                      '</table>';

              echo $choose_group_string;
            }
          ?>
           
      <!-- /***********************/ -->
      <tr>
        <td>
        <?php 
        if ($module->show_choose_audience) { 
          echo $module->choose_audience();
        } else if($module_name == "affiliate_newsletter") {
          echo $module->confirm(); 
        } ?></td>
      </tr>
      </table>
      
<?php
  } elseif ($action == 'confirm') {
    $nID = tep_db_prepare_input($_GET['nID']);
    
    $newsletter_query = tep_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$nID . "'");
    $newsletter = tep_db_fetch_array($newsletter_query);
    $nInfo = new objectInfo($newsletter);
    
    include(DIR_WS_LANGUAGES . $language . '/modules/newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
    include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
    
    if($module_name == "newsletter") {
      $s_module_name = TEXT_CUSTOMER_NEWSLETTER_NAME;
    } else if($module_name == "product_notification") {
      $s_module_name = TEXT_PRODUCT_NOTIFICATIONS_NEWSLETTER_NAME;
    } else if($module_name == "affiliate_newsletter") {
      $s_module_name = TEXT_AFFILIATE_NEWSLETTER_NAME;
    }
?>
      <tr>
        <td class="main"> <B><?php echo TEXT_NEWSLETTER_MODULE;?></B>  <?php echo $s_module_name;?>  </td>
      </tr>
      <tr>
        <td>
        <?php 
            if($module_name == "newsletter") {
              $frm_action =  'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send';
              echo tep_draw_form('notifications',FILENAME_NEWSLETTERS,$frm_action,'POST',$frm_para);
              
              $customers_email_address = $_POST["customers_email_address"];
              echo tep_draw_hidden_field('customers_email_address', $customers_email_address);
              
              $chosen_group = $_POST["chosen_group"];
              for ($i = 0, $n = sizeof($chosen_group); $i < $n; $i++) {
                echo tep_draw_hidden_field('chosen_group[]', $chosen_group[$i]);
              }
              global $button_name_tmp,$button_alt_tmp;
              $button_name_tmp = "button_send.gif";
              $button_alt_tmp = IMAGE_SEND;
            }
            echo $module->confirm(); 
            if($module_name == "newsletter") {
              echo '</form>';
            }     
        ?>
        </td>
      </tr>
<?php
  } elseif ($action == 'confirm_send') {
    $nID = tep_db_prepare_input($_GET['nID']);
    
    $newsletter_query = tep_db_query("select newsletters_id, title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . (int)$nID . "'");
    $newsletter = tep_db_fetch_array($newsletter_query);
    $nInfo = new objectInfo($newsletter);
    
    include(DIR_WS_LANGUAGES . $language . '/modules/newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
    include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($PHP_SELF, strrpos($PHP_SELF, '.')));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
    
    if($module_name == "newsletter") {
      $s_module_name = TEXT_CUSTOMER_NEWSLETTER_NAME;
    } else if($module_name == "product_notification") {
      $s_module_name = TEXT_PRODUCT_NOTIFICATIONS_NEWSLETTER_NAME;
    } else if($module_name == "affiliate_newsletter") {
      $s_module_name = TEXT_AFFILIATE_NEWSLETTER_NAME;
    }
?>
      <tr>
        <td class="main"> <B><?php echo TEXT_NEWSLETTER_MODULE;?></B>  <?php echo $s_module_name;?>  </td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" valign="middle"><?php echo tep_image(DIR_WS_IMAGES . 'ani_send_email.gif', IMAGE_ANI_SEND_EMAIL); ?></td>
            <td class="main" valign="middle"><b><?php echo TEXT_PLEASE_WAIT; ?></b></td>
          </tr>
        </table></td>
      </tr>
<?php
  tep_set_time_limit(0);
  flush();
  $module->send($nInfo->newsletters_id);
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><font color="#ff0000"><b><?php echo TEXT_FINISHED_SENDING_EMAILS; ?></b></font></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NEWSLETTERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_MODULE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SENT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $newsletters_query_raw = "select newsletters_id, title, length(content) as content_length, module, date_added, date_sent, status, locked from " . TABLE_NEWSLETTERS . " order by date_added desc";
    $newsletters_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $newsletters_query_raw, $newsletters_query_numrows);
    $newsletters_query = tep_db_query($newsletters_query_raw);
    while ($newsletters = tep_db_fetch_array($newsletters_query)) {
    if ((!isset($_GET['nID']) || (isset($_GET['nID']) && ($_GET['nID'] == $newsletters['newsletters_id']))) && !isset($nInfo) && (substr($action, 0, 3) != 'new')) {
        $nInfo = new objectInfo($newsletters);
      }

      if (isset($nInfo) && is_object($nInfo) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id) ) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id'] . '&action=preview') . '">' . tep_image(DIR_WS_ICONS . 'magnifier.png', ICON_PREVIEW) . '</a>&nbsp;' . $newsletters['title']; ?></td>
                <td class="dataTableContent" align="right"><?php echo number_format($newsletters['content_length']) . ' bytes'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $newsletters['module']; ?></td>
                <td class="dataTableContent" align="center"><?php if ($newsletters['status'] == '1') { echo tep_image(DIR_WS_ICONS . 'tick.png', ICON_TICK); } else { echo tep_image(DIR_WS_ICONS . 'cross.png', ICON_CROSS); } ?></td>
                <td class="dataTableContent" align="center"><?php if ($newsletters['locked'] > 0) { echo tep_image(DIR_WS_ICONS . 'locked.png', ICON_LOCKED); } else { echo tep_image(DIR_WS_ICONS . 'unlocked.png', ICON_UNLOCKED); } ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($nInfo) && is_object($nInfo) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $newsletters_split->display_count($newsletters_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS); ?></td>
                    <td class="smallText" align="right"><?php echo $newsletters_split->display_links($newsletters_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'action=new') . '">' . tep_image_button('button_new_newsletter.gif', IMAGE_NEW_NEWSLETTER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $nInfo->title . '</b>');

      $contents = array('form' => tep_draw_form('newsletters', FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $nInfo->title . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($nInfo) && is_object($nInfo)) {
        $heading[] = array('text' => '<b>' . $nInfo->title . '</b>');

        if ($nInfo->locked > 0) {
          $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=new') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '">' . tep_image_button('button_magnifier.png', IMAGE_PREVIEW) . '</a><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=send') . '">' . tep_image_button('button_send.gif', IMAGE_SEND) . '</a><br><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=unlock') . '">' . tep_image_button('button_unlock.gif', IMAGE_UNLOCK) . '</a>');
        } else {
          $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '">' . tep_image_button('button_magnifier.png', IMAGE_PREVIEW) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=lock') . '">' . tep_image_button('button_lock.gif', IMAGE_LOCK) . '</a>');
        }
        $contents[] = array('text' => '<br>' . TEXT_NEWSLETTER_DATE_ADDED . ' <b>' . tep_date_short($nInfo->date_added) . '</b>');
        if ($nInfo->status == '1') $contents[] = array('text' => TEXT_NEWSLETTER_DATE_SENT . ' <b>' . tep_date_short($nInfo->date_sent) . '</b>');
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
