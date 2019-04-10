<?php
/*
  FAQ system for OSC 2.2 MS2 v2.1  22.02.2005
  Originally Created by: http://adgrafics.com admin@adgrafics.net
  Updated by: http://www.webandpepper.ch osc@webandpepper.ch v2.0 (03.03.2004)
  Last Modified: http://shopandgo.caesium55.com timmhaas@web.de v2.1 (22.02.2005)
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/

  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/faq.php');
  require(DIR_WS_FUNCTIONS . '/faq.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo FAQ_SYSTEM; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/javascript/general.js"></script>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
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
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
$faq_action = isset($_GET['faq_action']) ? $_GET['faq_action'] : '';

switch($faq_action) {

  case "Added":
  $data = browse_faq($language,$_GET);
  $no = 1;
  if (sizeof($data) > 0) {
    while (list($key, $val) = each($data)) {
      $no++;
    }
  };
  $title = FAQ_ADD . ' #' . $no;
  echo tep_draw_form('form_faq',FILENAME_FAQ_MANAGER, 'faq_action=AddSure');
  include('faq_form.php');
  break;

  case "AddSure":
  function add_faq ($data) {
    $query = "INSERT INTO " . TABLE_FAQ . " (visible,v_order,question,answer,date,language) VALUES( '". (isset($data['visible']) ? (int)$data['visible'] : 0)."', '$data[v_order]', '$data[question]', '$data[answer]', NOW(),'$data[faq_language]')";
    tep_db_query($query);

    // update category info
    $fID = tep_db_insert_id();
    tep_db_query("insert into " . TABLE_FAQ_TO_CATEGORIES . " (faq_id, categories_id) values ('" . (int)$fID . "', '" . (int)$data['faq_category'] . "')");

  }
  if (isset($_POST['v_order']) && isset($_POST['answer']) && isset($_POST['question'])) {
    if ( (int)$_POST['v_order'] ) {
    add_faq($_POST);
    $data = browse_faq($language,$_GET);
    $title = FAQ_CREATED . ' ' . FAQ_ADD_QUEUE . ' ' . $_POST['v_order'];
    include('faq_list.php');
    } else {
      $error = 20;
    }
  } else {
    $error = 80;
  }
  break;

  case "Edit":
  if (isset($_GET['faq_id'])) {
    $edit = read_data($_GET['faq_id']);

    $data = browse_faq($language,$_GET);
    $button = array("Update");
    $title = FAQ_EDIT_ID . ' ' . $_GET['faq_id'];
    echo tep_draw_form('',FILENAME_FAQ_MANAGER, 'faq_action=Update');
    echo tep_draw_hidden_field('faq_id', $_GET['faq_id']);
    include('faq_form.php');
  } else {
    $error = 80;
  }
  break;

  case "Update":
  function update_faq ($data) {
    tep_db_query("UPDATE " . TABLE_FAQ . " SET question='$data[question]', answer='$data[answer]', visible='".(isset($data['visible']) ? (int)$data['visible'] : 0)."', v_order=$data[v_order], date = now() WHERE faq_id=$data[faq_id]");

    $category_check_query = tep_db_query("select categories_id from " . TABLE_FAQ_TO_CATEGORIES . " where faq_id = '" . (int)$data['faq_id'] . "'");

    if (tep_db_fetch_array($category_check_query)) { // if category exists
      // update category info
      tep_db_query("update " . TABLE_FAQ_TO_CATEGORIES . " set categories_id = '" . (int)$data['faq_category'] . "' where faq_id = '" . (int)$data['faq_id'] . "'");
    } else { 
      tep_db_query("insert into " . TABLE_FAQ_TO_CATEGORIES . " (faq_id, categories_id) values ('" . (int)$data['faq_id'] . "', '" . (int)$data['faq_category'] . "')");
    }
  }
  if (isset($_POST['faq_id']) && isset($_POST['question']) && isset($_POST['answer']) && isset($_POST['v_order'])) {
    if ( (int)$_POST['v_order'] ) {
    update_faq($_POST);
    $data = browse_faq($_POST['language'],$_GET);
    $title = FAQ_UPDATED_ID . ' ' . $_POST['faq_id'];
    include('faq_list.php');
    } else {
      $error = 20;
    } 
  } else {
    $error = 80;
  }
  break;

  case 'Visible':
  function tep_set_faq_visible($faq_id, $_GET) {
    if ($_GET['visible'] == 1) {
    return tep_db_query("update " . TABLE_FAQ . " set visible = '0', date = now() where faq_id = '" . $faq_id . "'");
    } else{
    return tep_db_query("update " . TABLE_FAQ . " set visible = '1', date = now() where faq_id = '" . $faq_id . "'");
    } 
  }
  tep_set_faq_visible($_GET['faq_id'], $_GET);
  $data = browse_faq($language,$_GET);
  if ($_GET['visible'] == 1) {
    $vivod = FAQ_DEACTIVATED_ID;
  } else {
    $vivod = FAQ_ACTIVATED_ID;
  }
  $title = $vivod . ' ' . $_GET['faq_id'];
  include('faq_list.php');
  break;

  case "Delete":
  if (isset($_GET['faq_id'])) {
    $delete = read_data($_GET['faq_id']);
    $data = browse_faq($language,$_GET);
    $title = FAQ_DELETE_CONFITMATION_ID . ' ' . $_GET['faq_id'];
    echo '
      <tr class="pageHeading"><td>' . $title . '</td></tr>
      <tr><td class="dataTableContent"><b>' . FAQ_QUESTION . ':</b></td></tr>
      <tr><td class="dataTableContent">' . $delete[question] . '</td></tr>
      <tr><td class="dataTableContent"><b>' . FAQ_ANSWER . ':</b></td></tr>
      <tr><td class="dataTableContent">' . $delete[answer] . '</td></tr>
      <tr><td align="right">
    ';
    echo tep_draw_form('',FILENAME_FAQ_MANAGER, 'faq_action=DelSure&faq_id='.$_GET['faq_id']);
    echo tep_draw_hidden_field('faq_id', $_GET['faq_id']);
    echo tep_image_submit('button_delete.gif', IMAGE_DELETE);
    echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
    echo '</form></td></tr>';
  } else {
    $error = 80;
  }
  break;


  case "DelSure":
  function delete_faq ($faq_id) {
    tep_db_query("DELETE FROM " . TABLE_FAQ . " WHERE faq_id=$faq_id");
    tep_db_query("delete from " . TABLE_FAQ_TO_CATEGORIES . " where faq_id = '" . (int)$faq_id . "'");
  }
  if (isset($_GET['faq_id'])) {
    delete_faq($_GET['faq_id']);
    $data = browse_faq($language,$_GET);
    $title = FAQ_DELETED_ID . ' ' . $_GET['faq_id'];
    include('faq_list.php');
  } else {
    $error = 80;
  }
  break;


  default:
  $data = browse_faq($language,$_GET);
  $title = FAQ_MANAGER;
  include('faq_list.php');
  break;
}

if (isset($error)) {
  $content = error_message($error);
  echo $content;
  $data = browse_faq($language,$_GET);
  $no = 1;
  if (sizeof($data) > 0) {
    while (list($key, $val) = each($data)) {
      $no++; 
    }
  };
  $title = FAQ_ADD_QUEUE . ' ' . $no;
  echo tep_draw_form('form_faq',FILENAME_FAQ_MANAGER, 'faq_action=AddSure');
  include('faq_form.php');
}
?>
</table>
</td>
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