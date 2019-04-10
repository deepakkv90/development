<?php
/*
  $Id: admin_account.php,v 1.1.1.1 2004/03/04 23:38:03 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  $current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;
  
  if (isset($_GET['action']) && $_GET['action']) {
    switch ($_GET['action']) {
      case 'check_password':
        $check_pass_query = tep_db_query("select admin_password as confirm_password from " . TABLE_ADMIN . " where admin_id = '" . $_POST['id_info'] . "'");
        $check_pass = tep_db_fetch_array($check_pass_query);
        
        // Check that password is good
        if (!tep_validate_password($_POST['password_confirmation'], $check_pass['confirm_password'])) {
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=check_account&error=password'));
        } else {
          //$confirm = 'confirm_account';
          $_SESSION['confirm_account']= true;
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process'));
        }
 
        break;    
      case 'save_account':
        // verify password is hardened password
        if (isset($_POST['admin_password']) && $_POST['admin_password'] != null) {
          /*if ((!preg_match('/[0-9]/', $_POST['admin_password']) || !preg_match('/[A-Z]/', $_POST['admin_password']) || !preg_match('/[a-z]/', $_POST['admin_password'])) || ($_POST['admin_password'] < 8)) {*/
            if (strlen($_POST['admin_password']) < 8) {             
            tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process&error=password'));    
          }
        }
        $admin_id = tep_db_prepare_input($_POST['id_info']);
        $admin_email_address = tep_db_prepare_input($_POST['admin_email_address']);
        $stored_email[] = 'NONE';
        $hiddenPassword = '-hidden-';
        
        $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . " where admin_id <> " . $admin_id . "");
        while ($check_email = tep_db_fetch_array($check_email_query)) {
          $stored_email[] = $check_email['admin_email_address'];
        }
        
        if (in_array($_POST['admin_email_address'], $stored_email)) {
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process&error=email'));
        } else {
          $sql_data_array = array('admin_firstname' => tep_db_prepare_input($_POST['admin_firstname']),
                                  'admin_lastname' => tep_db_prepare_input($_POST['admin_lastname']),
                                  'admin_email_address' => tep_db_prepare_input($_POST['admin_email_address']),
                                  'admin_password' => tep_encrypt_password(tep_db_prepare_input($_POST['admin_password'])),
                                  'admin_modified' => 'now()');
        
          tep_db_perform(TABLE_ADMIN, $sql_data_array, 'update', 'admin_id = \'' . $admin_id . '\'');

          tep_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        
          tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'page=' . $_GET['page'] . '&mID=' . $admin_id));
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
<script language="javascript" src="includes/general.js"></script>
<?php require('includes/account_check.js.php'); ?>
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
    <td valign="top" class="page-container">
      <?php if (isset($_GET['action']) && $_GET['action'] == 'edit_process') { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=save_account', 'post', 'enctype="multipart/form-data"', 'SSL'); } elseif (isset($_GET['action']) && $_GET['action'] == 'check_account') { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_password', 'post', 'enctype="multipart/form-data"', 'SSL'); } else { echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_account', 'post', 'enctype="multipart/form-data"', 'SSL'); } ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">     
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top">
<?php
  $my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . $_SESSION['login_id'] . " and g.admin_groups_id= " . $_SESSION['login_groups_id'] . "");
  $myAccount = tep_db_fetch_array($my_account_query);
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_ACCOUNT; ?>
                </td>
              </tr>
<?php
    if ( (isset($_GET['action']) && $_GET['action'] == 'edit_process') && (isset($_SESSION['confirm_account'])) ) {
?>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FIRSTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_input_field('admin_firstname', $myAccount['admin_firstname']); ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LASTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_input_field('admin_lastname', $myAccount['admin_lastname']); ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php if ($_GET['error'] == 'email') { echo tep_draw_input_field('admin_email_address', $myAccount['admin_email_address']) . ' <nobr>' . TEXT_EMAIL_ERROR . '</nobr>'; } else { echo tep_draw_input_field('admin_email_address', $myAccount['admin_email_address']); } ?></td>
                    </tr>
              <tr class="dataTableRow">  
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>                                                     
                      <td class="dataTableContent"><?php if ($_GET['error'] == 'password') { echo tep_draw_password_field('admin_password', $myAccount['admin_password']) . ' <nobr>' . TEXT_PASSWORD_ERROR . '</nobr>'; } else { echo tep_draw_password_field('admin_password', $myAccount['admin_password']); } ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD_CONFIRM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo tep_draw_password_field('admin_password_confirm'); ?></td>
                    </tr>
<?php
    } else {
      if (isset($_SESSION['confirm_account'])) {
        unset($_SESSION['confirm_account']);
      }
?>                        
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FULLNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_email_address']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo TEXT_INFO_PASSWORD_HIDDEN; ?></td>
                    </tr>
                    <tr class="dataTableRow dataTableRowSelected">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_GROUP; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_groups_name']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_CREATED; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_created']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGNUM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_lognum']; ?></td>
                    </tr>
              <tr class="dataTableRow">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGDATE; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_logdate']; ?></td>
                    </tr>
<?php
  }
?>                       
              </table>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="smallText" valign="top"><?php echo TEXT_INFO_MODIFIED . $myAccount['admin_modified']; ?></td><td align="right"><?php if (isset($_GET['action']) && $_GET['action'] == 'edit_process') { echo '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ';
                if (isset($_SESSION['confirm_account'])) {
                    echo tep_image_submit('button_save.gif', IMAGE_SAVE, 'onClick="validateForm();return document.returnValue"'); } } elseif (isset($_GET['action']) && $_GET['action'] == 'check_account') { echo '&nbsp;'; } else { echo tep_image_submit('button_page_edit.png', IMAGE_EDIT); } ?></td><tr></table></td>
              </tr>              
            </table>
            </td>
<?php
  $heading = array();
  $contents = array();
  $action = (isset($_GET['action']) ? $_GET['action'] : ''); 
  switch ($action) {
    case 'edit_process':
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');
      
      $contents[] = array('text' => TEXT_INFO_INTRO_EDIT_PROCESS . tep_draw_hidden_field('id_info', $myAccount['admin_id']));
      //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_confirm.gif', IMAGE_CONFIRM, 'onClick="validateForm();return document.returnValue"') . '<br>&nbsp');
      break; 
    case 'check_account':
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_CONFIRM_PASSWORD . '</b>');
      
      $contents[] = array('text' => '<br>&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD . tep_draw_hidden_field('id_info', $myAccount['admin_id']));
      if (isset($_GET['error']) && $_GET['error']) {
        $contents[] = array('text' => '&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR);
      }
      $contents[] = array('align' => 'left', 'text' => '&nbsp;' . tep_draw_password_field('password_confirmation'));
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>' . tep_image_submit('button_confirm.gif', IMAGE_CONFIRM));
      break; 
    default:
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');
      
      $contents[] = array('text' => TEXT_INFO_INTRO_DEFAULT);
      //$contents[] = array('align' => 'center', 'text' => tep_image_submit('button_page_edit.png', IMAGE_EDIT) . '<br>&nbsp');
      if ($myAccount['admin_email_address'] == 'admin@localhost') {
        $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST, $myAccount['admin_firstname']) . '<br>&nbsp');
      } elseif (($myAccount['admin_modified'] == '0000-00-00 00:00:00') || ($myAccount['admin_logdate'] <= 1) ) {
        $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST_TIME, $myAccount['admin_firstname']) . '<br>&nbsp');
      }
      
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
    </table></form></td>  
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