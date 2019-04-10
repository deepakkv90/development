<?php
/*


  Copyright (c) 2007 Chainreactionworks.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  function tep_set_data_status($data_files_id, $data_status) {
    if ($data_status == '1') {
      return tep_db_query("update " .TABLE_DATA_FILES . " set data_status = '1' where data_files_id = '" . $data_files_id . "'");
    } elseif ($data_status == '0') {
      return tep_db_query("update " .TABLE_DATA_FILES . " set data_status = '0' where data_files_id = '" . $data_files_id . "'");
    } else {
      return -1;
    }
  }

  if (isset($_GET['action'])) {
    $action = $_GET['action'] ;
    }else if (isset($_POST['action'])){
    $action = $_POST['action'] ;
    } else {
    $action = '' ;
    }

  switch ($action) {

      case 'returnsetflag':
        tep_set_data_status($_GET['dID'], $_GET['flag']);
        tep_redirect(tep_href_link(FILENAME_DATA_ADMIN, 'dID=' . $_GET['dID'] . '&action=edit', 'NONSSL') );
        break;

    case 'setflag':
      tep_set_data_status($_GET['dID'], $_GET['flag']);
      tep_redirect(tep_href_link(FILENAME_DATA_ADMIN, 'dID=' . $_GET['dID'], 'NONSSL'));
      break;
    case 'insert':

   $insert_sql_data = array('data_files_type' => tep_db_prepare_input($_POST['data_files_type']),
                                 'data_name' => tep_db_prepare_input($_POST['data_name']),
                           'data_files_disc' => tep_db_prepare_input($_POST['data_files_disc']),
                           'data_files_type' => tep_db_prepare_input($_POST['data_files_type']),
                          'data_files_type1' => tep_db_prepare_input($_POST['data_files_type1']),
                        'data_files_service' => tep_db_prepare_input($_POST['data_files_service']),
                               'data_status' => tep_db_prepare_input(isset($_POST['data_status']) ? $_POST['data_status'] : '1'),
                           'data_files_name' => tep_db_prepare_input($_POST['data_files_name']),
                            'data_image_url' => tep_db_prepare_input($_POST['data_image_url']),
                          'data_product_url' => tep_db_prepare_input(isset($_POST['data_product_url']) ? $_POST['data_product_url'] : ''),
                           'data_ftp_server' => tep_db_prepare_input($_POST['data_ftp_server']),
                        'data_ftp_user_name' => tep_db_prepare_input($_POST['data_ftp_user_name']),
                        'data_ftp_user_pass' => tep_db_prepare_input($_POST['data_ftp_user_pass']),
                        'data_ftp_directory' => tep_db_prepare_input($_POST['data_ftp_directory']),
                         'data_tax_class_id' => tep_db_prepare_input($_POST['data_tax_class_id']),
                          'data_convert_cur' => tep_db_prepare_input($_POST['data_convert_cur']),
                              'data_cur_use' => tep_db_prepare_input($_POST['data_cur_use']),
                                  'data_cur' => tep_db_prepare_input($_POST['data_cur']),
                             'data_lang_use' => tep_db_prepare_input($_POST['data_lang_use']),
                            'data_lang_char' => tep_db_prepare_input($_POST['data_lang_char']),
                                 );

//($insert_sql_data);
   tep_db_perform(TABLE_DATA_FILES, $insert_sql_data, 'insert');

      $data_files_id = tep_db_insert_id();
      tep_redirect(tep_href_link(FILENAME_DATA_ADMIN, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'dID=' . $data_files_id));

//      tep_redirect(tep_href_link(FILENAME_DATA_ADMIN, 'dID=' . (int)$data_files_id));));
      break;
    case 'update':
    $sql_data_array = array('data_files_type' => tep_db_prepare_input($_POST['data_files_type']),
                                 'data_name' => tep_db_prepare_input($_POST['data_name']),
                           'data_files_disc' => tep_db_prepare_input($_POST['data_files_disc']),
                           'data_files_type' => tep_db_prepare_input($_POST['data_files_type']),
                          'data_files_type1' => tep_db_prepare_input($_POST['data_files_type1']),
                        'data_files_service' => tep_db_prepare_input($_POST['data_files_service']),
                           'data_files_name' => tep_db_prepare_input($_POST['data_files_name']),
                            'data_image_url' => tep_db_prepare_input($_POST['data_image_url']),
                          'data_product_url' => tep_db_prepare_input(isset($_POST['data_product_url']) ? $_POST['data_product_url'] : ''),
                           'data_ftp_server' => tep_db_prepare_input($_POST['data_ftp_server']),
                        'data_ftp_user_name' => tep_db_prepare_input($_POST['data_ftp_user_name']),
                        'data_ftp_user_pass' => tep_db_prepare_input($_POST['data_ftp_user_pass']),
                        'data_ftp_directory' => tep_db_prepare_input($_POST['data_ftp_directory']),
                         'data_tax_class_id' => tep_db_prepare_input($_POST['data_tax_class_id']),
                          'data_convert_cur' => tep_db_prepare_input($_POST['data_convert_cur']),
                              'data_cur_use' => tep_db_prepare_input($_POST['data_cur_use']),
                                  'data_cur' => tep_db_prepare_input($_POST['data_cur']),
                             'data_lang_use' => tep_db_prepare_input($_POST['data_lang_use']),
                            'data_lang_char' => tep_db_prepare_input($_POST['data_lang_char']),
                                 );

  if (isset($_GET['data_files_id'])) {
   $data_files_id = $_GET['data_files_id'] ;
    }else if (isset($_POST['data_files_id'])){
   $data_files_id = $_POST['data_files_id'] ;
    } else {
   $data_files_id = '' ;
   }

      tep_db_perform(TABLE_DATA_FILES, $sql_data_array, 'update', "data_files_id = '" . (int)$data_files_id . "'");
      tep_redirect(tep_href_link(FILENAME_DATA_ADMIN, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'dID=' . $data_files_id));

 //    tep_redirect(tep_href_link(FILENAME_DATA_ADMIN, 'dID=' . (int)$data_files_id));
      break;
    case 'deleteconfirm':
      $data_files_id = tep_db_prepare_input($_GET['dID']);

      tep_db_query("delete from " .TABLE_DATA_FILES . " where data_files_id = '" . tep_db_input($data_files_id) . "'");

      tep_redirect(tep_href_link(FILENAME_DATA_ADMIN, 'page=1&dID=' ));
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
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=300%,screenX=150,screenY=150,top=150,left=150')
}
//--></script>

<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . '&nbsp;' ;
            echo $action;
            $action?> </td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
        <?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';

 if ( ($action == 'edit') && ($_GET['dID']) ) {
    $form_action = 'update';
    }


  if ( (isset($_GET['dID'])) && ($_GET['dID']) && (!$_POST) ) {
     $data_query = tep_db_query("select * from " . TABLE_DATA_FILES . " where data_files_id = '" . $_GET['dID'] . "' order by data_files_service ");
      $data = tep_db_fetch_array($data_query);

      $dInfo = new objectInfo($data);
echo 'this one';
    } elseif ($_POST)  {
      $dInfo = new objectInfo($_POST);
      $data_files_id = (isset($_POST['data_files_id']) ? $_POST['data_files_id'] : '' );
      $data_name = (isset($_POST['data_name']) ? $_POST['data_name'] : '' );
      $data_files_type = (isset($_POST['data_files_type']) ? $_POST['data_files_type'] : '' );
      $data_files_disc = (isset($_POST['data_files_disc']) ? $_POST['data_files_disc'] : '' );
      $data_files_type1 = (isset($_POST['data_files_type1']) ? $_POST['data_files_type1'] : '' );
      $data_files_service = (isset($_POST['data_files_service']) ? $_POST['data_files_service'] : '' );
      $data_status = (isset($_POST['data_status']) ? $_POST['data_status'] : '' );
      $data_files_name = (isset($_POST['data_files_name']) ? $_POST['data_files_name'] : '' );
      $data_image_url = (isset($_POST['data_image_url']) ? $_POST['data_image_url'] : '' );
      $data_product_url = (isset($_POST['data_product_url']) ? $_POST['data_product_url'] : '' );
      $data_ftp_server = (isset($_POST['data_ftp_server']) ? $_POST['data_ftp_server'] : '' );
      $data_ftp_user_name = (isset($_POST['data_ftp_user_name']) ? $_POST['data_ftp_user_name'] : '' );
      $data_ftp_user_pass = (isset($_POST['data_ftp_user_pass']) ? $_POST['data_ftp_user_pass'] : '' );
      $data_ftp_directory = (isset($_POST['data_ftp_directory']) ? $_POST['data_ftp_directory'] : '' );
      $data_tax_class_id = (isset($_POST['data_tax_class_id']) ? $_POST['data_tax_class_id'] : '' );
      $data_convert_cur = (isset($_POST['data_convert_cur']) ? $_POST['data_convert_cur'] : '' );
      $data_cur_use = (isset($_POST['data_cur_use']) ? $_POST['data_cur_use'] : '' );
      $data_cur = (isset($_POST['data_cur']) ? $_POST['data_cur'] : '' );
      $data_lang_use = (isset($_POST['data_lang_use']) ? $_POST['data_lang_use'] : '' );
      $data_lang_char = (isset($_POST['data_lang_char']) ? $_POST['data_lang_char'] : '' );
echo 'this two';
    } else {
echo 'this 3';
      $dInfo = new objectInfo(array());
      $dInfo->data_files_id = (isset($dInfo->data_files_id) ? $dInfo->data_files_id : '' );
      $dInfo->data_name = (isset($dInfo->data_name) ? $dInfo->data_name : '' );
      $dInfo->data_files_type = (isset($dInfo->data_files_type) ? $dInfo->data_files_type : '' );
      $dInfo->data_files_disc = (isset($dInfo->data_files_disc) ? $dInfo->data_files_disc : '' );
      $dInfo->data_files_type1 = (isset($dInfo->data_files_type1) ? $dInfo->data_files_type1 : '' );
      $dInfo->data_files_service = (isset($dInfo->data_files_service) ? $dInfo->data_files_service : '' );
      $dInfo->data_status = (isset($dInfo->data_status) ? $dInfo->data_status : '' );
      $dInfo->data_files_name = (isset($dInfo->data_files_name) ? $dInfo->data_files_name : '' );
      $dInfo->data_image_url = (isset($dInfo->data_image_url) ? $dInfo->data_image_url : '' );
      $dInfo->data_product_url = (isset($dInfo->data_product_url) ? $dInfo->data_product_url : '' );
      $dInfo->data_ftp_server = (isset($dInfo->data_ftp_server) ? $dInfo->data_ftp_server : '' );
      $dInfo->data_ftp_user_name = (isset($dInfo->data_ftp_user_name) ? $dInfo->data_ftp_user_name : '' );
      $dInfo->data_ftp_user_pass = (isset($dInfo->data_ftp_user_pass) ? $dInfo->data_ftp_user_pass : '' );
      $dInfo->data_ftp_directory = (isset($dInfo->data_ftp_directory) ? $dInfo->data_ftp_directory : '' );
      $dInfo->data_tax_class_id = (isset($dInfo->data_tax_class_id) ? $dInfo->data_tax_class_id : '' );
      $dInfo->data_convert_cur = (isset($dInfo->data_convert_cur) ? $dInfo->data_convert_cur : 'true' );
      $dInfo->data_cur_use = (isset($dInfo->data_cur_use) ? $dInfo->data_cur_use : 'true' );
      $dInfo->data_cur = (isset($dInfo->data_cur) ? $dInfo->data_cur : '' );
      $dInfo->data_lang_use = (isset($dInfo->data_lang_use) ? $dInfo->data_lang_use : 'true' );
      $dInfo->data_lang_char = (isset($dInfo->data_lang_char) ? $dInfo->data_lang_char : '' );

// create an array of data datas, which will be excluded from the pull down menu of datas
// (when creating a new data data)
      $data_array = array();
      $data_query = tep_db_query("select data_files_id from " . TABLE_DATA_FILES . " s where s.data_files_id = data_files_id");
      while ($data = tep_db_fetch_array($data_query)) {
        $data_array[] = $data['data_files_id'];
      }
   }
//}
?>
      <tr><form name="new_data" <?php echo 'action="' . tep_href_link(FILENAME_DATA_ADMIN, tep_get_all_get_params(array('action', 'info', 'dID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('data_files_id', $_GET['dID']); ?>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
  <?php // build new/edit settings form

  if ($action == 'new'){
  $heading=  HEADING_TITLE_1;
  }else if ($action == 'edit'){
  $heading=  HEADING_TITLE_2;
  }
           ?>

                 <tr>
             <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
               <tr>
                 <td class="pageHeading"><?php echo $heading; ?></td>
                </tr>
                <tr>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
               </tr>
             </table></td>
           </tr>
          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_NAME . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=google_feed_name') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main"><?php echo tep_draw_input_field('data_name', $dInfo->data_name, 'size="12"'); ?> </td>
            </tr>

          <?php
              $file_type_array = array(array('id' => 'basic', 'text' => TEXT_TYPE_BASIC) ,
                                 array('id' => 'advance', 'text' => TEXT_TYPE_ADVANCE));
          ?>
          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_TYPE_PRODUCT .'&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FILE_TYPE_PRODUCT') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('data_files_type', $file_type_array, $dInfo->data_files_type, ''); ?> </td>

         </tr>

          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_DISC . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FEED_DISC') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?></td>
          <td class="main"><?php echo tep_draw_textarea_field('data_files_disc', 'soft', '32', '1', $dInfo->data_files_disc ? stripslashes($dInfo->data_files_disc) : ($dInfo->data_files_disc) ); ?></td>


           </tr>
          <?php
              $file_type_array1 = array(array('id' => 'none', 'text' => TEXT_TYPE_NONE) ,
                                 array('id' => 'products', 'text' => TEXT_TYPE_PRODUCTS) ,
                                 array('id' => 'business', 'text' => TEXT_TYPE_BUSINESS));
          ?>
          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_FILE_TYPE . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FILE_TYPE') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('data_files_type1', $file_type_array1, ($dInfo->data_files_type1)); ?> </td>

         </tr>
          <?php
                 $file_feed_array = array(array('id' => 'none', 'text' => TEXT_TYPE_NONE) ,
                                   array('id' => 'google_bas', 'text' => TEXT_FEED_GOOGLE_BASE) ,
                                   array('id' => 'froogle', 'text' => TEXT_FEED_FROOGLE) ,
                                   array('id' => 'yahoo', 'text' => TEXT_FEED_YAHOO));
             ?>

          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_FEED_SERVICE . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FEED_SERVICE') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            <td class="main"><?php echo tep_draw_pull_down_menu('data_files_service', $file_feed_array, ($dInfo->data_files_service), ''); ?> </td>
            </td>
            </tr>

          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_STATUS. '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FEED_STATUS') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?></td>
            <td class="main"><?php
      if ($data['data_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DATA_ADMIN, tep_get_all_get_params(array('dID')) . 'action=returnsetflag&flag=0&dID=' . $data['data_files_id'] , 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_DATA_ADMIN, tep_get_all_get_params(array('dID')) . 'action=returnsetflag&flag=1&dID=' . (isset($_GET['dID']) ? $_GET['dID'] : ''), 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
      }
?></td>
            </tr>
          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_FILE . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FEED_FILE') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main"><?php echo tep_draw_input_field('data_files_name', ($dInfo->data_files_name), 'size="25"'); ?> </td>
            </tr>
          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_IMAGE . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FEED_IMAGE') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main"><?php echo tep_draw_input_field('data_image_url', ($dInfo->data_image_url), 'size="25"'); ?> </td>
            </tr>
            <tr>
      <td class="main"><?php echo TABLE_HEADING_FEED . '&nbsp';   ?>
            </td>
                  </tr>
               <tr>

           <tr>
            <td class="main">&nbsp;&nbsp;&nbsp;
             <?php echo TABLE_HEADING_FEED_FTP_SERVER . '&nbsp';
                   echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FTP_SERVER') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
                 ?>
               </td>

           <td class="main">&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('data_ftp_server', ($dInfo->data_ftp_server), 'size="15"'); ?> </td>
            </tr>
         <tr>
            <td class="main">&nbsp;&nbsp;&nbsp;<?php echo TABLE_HEADING_FEED_FTP_USER . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FTP_USER') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main">&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('data_ftp_user_name', ($dInfo->data_ftp_user_name), 'size="15"'); ?> </td>
            </tr>
          <tr>
            <td class="main">&nbsp;&nbsp;&nbsp;<?php echo TABLE_HEADING_FEED_FTP_PASSWORD . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FTP_PASSWORD') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main">&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('data_ftp_user_pass', ($dInfo->data_ftp_user_pass), 'size="15"'); ?> </td>
            </tr>
          <tr>
            <td class="main">&nbsp;&nbsp;&nbsp;<?php echo TABLE_HEADING_FEED_FTP_DIRECTORY . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FTP_DIRECTORY') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main">&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('data_ftp_directory', ($dInfo->data_ftp_directory), 'size="32"'); ?> </td>
            </tr>
    </tr>
      <td class="main"><?php echo TABLE_HEADING_FEED_ADVANCE . '&nbsp';   ?>
            </td>
      </tr>

          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_CUR . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FEED_CUR') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
     <?php

   switch ($dInfo->data_cur_use) {
      case 'false':
        $data_cur_use_false_status = false;
         $data_cur_use_true_status = true;
      break;
      case 'true':
      default:
        $data_cur_use_false_status = true;
        $data_cur_use_true_status = false;
      break;
    }
     ?>
   <td class="main"><?php echo '&nbsp;' . tep_draw_radio_field('data_cur_use', 'true', $data_cur_use_false_status) . '&nbsp;' . TEXT_DATA_CUR_USE_FALSE . '&nbsp;' . tep_draw_radio_field('data_cur_use', 'false', $data_cur_use_true_status) . '&nbsp;' . TEXT_DATA_CUR_USE_TRUE; ?></td>
            </tr>
          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_CUR_USE . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_CUR_USE') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main"><?php echo tep_draw_input_field('data_cur', ($dInfo->data_cur), 'size="15"'); ?> </td>
          </tr>
          <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_CUR_CON . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_CUR_CON') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>

                <?php
         switch ($dInfo->data_convert_cur) {
            case 'false':
               $data_convert_cur_false_status = true;
               $data_convert_cur_true_status = false;
            break;
            case 'true':
              $data_convert_cur_false_status = false;
              $data_convert_cur_true_status = true;
            break;
          }
           ?>
         <td class="main"><?php echo '&nbsp;' . tep_draw_radio_field('data_convert_cur', 'false', $data_convert_cur_false_status) . '&nbsp;' . TEXT_DATA_CUR_CONVERT_FALSE . '&nbsp;' . tep_draw_radio_field('data_convert_cur', 'true', $data_convert_cur_true_status) . '&nbsp;' . TEXT_DATA_CUR_CONVERT_TRUE; ?></td>

            </tr>
          <tr>
                <?php
         switch ($dInfo->data_lang_use) {
            case 'false':
               $data_lang_use_false = false;
               $data_lang_use_true = true;
            break;
            case 'true':
            default:
              $data_lang_use_false = true;
              $data_lang_use_true = false;
            break;
          }
           ?>

            <td class="main"><?php echo TABLE_HEADING_FEED_LANG . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FEED_LANG') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main"><?php echo '&nbsp;' . tep_draw_radio_field('data_lang_use', 'true', $data_lang_use_false) . '&nbsp;' . TEXT_DATA_CUR_LANG_FALSE . '&nbsp;' . tep_draw_radio_field('data_lang_use', 'false', $data_lang_use_true) . '&nbsp;' . TEXT_DATA_CUR_LANG_TRUE; ?></td>

            </tr>
          <tr>
          <?php
                 $file_lang_array = array(array('id' => 'en', 'text' => TEXT_LANG_EN) ,
                                   array('id' => 'fr', 'text' => TEXT_LANG_FR) ,
                                   array('id' => 'de', 'text' => TEXT_LANG_DE),
                                   array('id' => 'it', 'text' => TEXT_LANG_IT),
                                   array('id' => 'es', 'text' => TEXT_LANG_ES),
                                   array('id' => 'ja', 'text' => TEXT_LANG_JA));
             ?>

            <td class="main"><?php echo TABLE_HEADING_FEED_LANG_USE . '&nbsp';
            echo '</td><td>'. '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=FEED_LANG_USE') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main"><?php echo tep_draw_pull_down_menu('data_lang_char', $file_lang_array, ($dInfo->data_lang_char)); ?> </td>
           </tr>

         <tr>
            <td class="main"><?php echo TABLE_HEADING_FEED_TAX . '&nbsp';
            echo '</td><td>' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=GOOGLE_FEED_TAX') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
             ?>
            </td>
            <td class="main"><?php echo tep_draw_input_field('data_tax_class_id', ($dInfo->data_tax_class_id), 'size="8"'); ?> </td>
            </tr>
            <tr>
            <td class="main" colspan="3">
<?php
echo TEXT_INFO_FEED_ADVANCE_ADDITION ;
?>
</td></tr>
            </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DATA_ADMIN, 'dID=' . $data['data_files_id']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
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
                <td class="dataTableHeadingContent"><?php echo TEXT_DATA_FEED_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_FEED_FEED_SERVICE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FEED_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $data_query_raw1 = "select * from  " . TABLE_DATA_FILES . " order by data_files_service ";
    $data_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $data_query_raw1, $data_query_numrows1);
    $data_query1 = tep_db_query($data_query_raw1);
    while ($data1 = tep_db_fetch_array($data_query1)) {
    if ((!isset($_GET['dID']) || (isset($_GET['dID']) && ($_GET['dID'] == $data1['data_files_id']))) && !isset($dInfo) && (substr($action, 0, 3) != 'new')) {
        $dInfo_array1 = $data1;
        $dInfo1 = new objectInfo($dInfo_array1);
   }

      if ( isset($dInfo1) && is_object($dInfo1) && ($data1['data_files_id'] == $dInfo1->data_files_id) ) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_DATA_ADMIN, tep_get_all_get_params(array('dID', 'action')) . 'dID=' . $dInfo1->data_files_id . '&action=edit') . '\'">' . "\n";
       } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_DATA_ADMIN, tep_get_all_get_params(array('dID')) . 'dID=' . $data1['data_files_id']) . '\'">' . "\n";
      }

  ?>
                <td  class="dataTableContent"><?php echo $data1['data_name'] ; ?></td>
                <td  class="dataTableContent" align="right">&nbsp;<?php echo $data1['data_files_service']; ?></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($data1['data_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_DATA_ADMIN, 'action=setflag&flag=0&dID=' . $data1['data_files_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_DATA_ADMIN, 'action=setflag&flag=1&dID=' . $data1['data_files_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($dInfo1) && is_object($dInfo1) && ($data1['data_files_id'] == $dInfo1->data_files_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_DATA_ADMIN, tep_get_all_get_params(array('dID')) . 'dID=' . $data1['data_files_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>

      </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $data_split->display_count($data_query_numrows1, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_DATA); ?></td>
                    <td class="smallText" align="right"><?php echo $data_split->display_links($data_query_numrows1, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
 // if (!$action) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DATA_ADMIN, '&action=new') . '">' . tep_image_button('button_new.gif', IMAGE_NEW_SETTING) . '</a>'; ?></td>
                  </tr>
<?php
 // }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_DATA . '</b>');

      $contents = array('form' => tep_draw_form('data', FILENAME_DATA_ADMIN, tep_get_all_get_params(array('dID')) . 'dID=' . $dInfo1->data_files_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $dInfo1->data_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(tep_get_all_get_params(array('dID')) . 'dID=' . $dInfo1->data_files_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if ((isset($dInfo1)) && (is_object($dInfo1)) ){
        $heading[] = array('text' => '<b>' . $dInfo1->data_files_disc . '</b>');

      if ($dInfo1->data_status == '1'){
         $data_status1 = TEXT_DATA_ID_ACTIVE;
         }else{
         $data_status1 = TEXT_DATA_ID_DEACTIVE;
         }
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_DATA_ADMIN, tep_get_all_get_params(array('dID')) . 'dID=' . $dInfo1->data_files_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_DATA_ADMIN, tep_get_all_get_params(array('dID')) . 'dID=' . $dInfo1->data_files_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_FEED_NAME . ' ' . $dInfo1->data_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_FEED_TYPE . ' ' . $dInfo1->data_files_type);
        $contents[] = array('text' => '<br>' . TEXT_INFO_FEED_SERVICE . ' ' . $dInfo1->data_files_service);
        $contents[] = array('text' => '<br>' . TEXT_INFO_FEED_STATUS . ' <b>' . $data_status1 . '</b>');
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
}
?>
               </td>
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
