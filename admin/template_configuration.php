<?php
/*
  $Id: template_configuration.php,v 1.1.1.1 2004/03/04 23:39:01 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

////
// Alias function for Store configuration values in the Administration Tool
function tep_fixweight(){
    global  $infobox_id, $cID;
    //$column = $_GET['flag'];
    $rightpos = 'right';
    $leftpos = 'left';
    $result_query = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column = '" . $leftpos . "' and template_id = '" . (int)$cID . "' order by location");

    $sorted_position = 0;
      while ($result = tep_db_fetch_array($result_query)) {
      $sorted_position++;
      tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location = '" . $sorted_position . "' where infobox_id = '" . (int)$result['infobox_id'] . "' and template_id = '" . (int)$cID . "'");
      }

    $result_query = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column = '" . $rightpos . "' and template_id = '" . (int)$cID . "' order by location");

    $sorted_position = 0;
       while ($result = tep_db_fetch_array($result_query)) {
       $sorted_positionright++;
       tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location = '" . $sorted_position . "' where infobox_id = '" . (int)$result['infobox_id'] . "' and template_id = '" . (int)$cID . "'");
    }
    tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $cID . '&action=edit'));
  }

// find gID
    if (isset($_GET['gID'])) {
      $gID = $_GET['gID'] ;
    }else if (isset($_POST['gID'])){
      $gID = $_POST['gID'] ;
    } else {
     $gID = '' ;
    }


// find cID
if (isset($_GET['cID'])) {
      $cID = $_GET['cID'] ;
    }else if (isset($_POST['cID'])){
      $cID = $_POST['cID'] ;
    } else {
     $cID = '' ;
    }

// begin action
if (isset($_GET['action'])) {
      $action = $_GET['action'] ;
    }else if (isset($_POST['action'])){
      $action = $_POST['action'] ;
    } else {
     $action = '' ;
    }


  if (tep_not_null($action)) {


   switch ($action) {
   
case 'fixweight':
    global  $infobox_id, $cID;
    $rightpos = 'right';
    $leftpos = 'left';
    
    $result_query = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column = '" . $leftpos . "' and template_id = '" . (int)$cID . "' order by location");
    $sorted_position = 0;
    while ($result = tep_db_fetch_array($result_query)) {
    $sorted_position++;
    tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location = '" . $sorted_position . "' where infobox_id = '" . (int)$result['infobox_id'] . "' and template_id = '" . (int)$cID . "'");
    }

    $result_query = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column = '" . $rightpos . "' and template_id = '" . (int)$cID . "' order by location");

    $sorted_position = 0;
    while ($result = tep_db_fetch_array($result_query)) {
    $sorted_position++;
    tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location = '" . $sorted_position . "' where infobox_id = '" . (int)$result['infobox_id'] . "' and template_id = '" . (int)$cID . "'");
    }
    
    tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $cID . '&action=edit'));
break;

case 'setflag': //set the status of a template active.
        if ( ($_GET['flag'] == 0) || ($_GET['flag'] == 1) ) {
          if ($cID) {
            tep_db_query("update " . TABLE_TEMPLATE . " set active = '" . $_GET['flag'] . "' where template_id = '" . (int)$cID . "'");
          }
        }
        
        tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $cID));
break;

case 'setflagtemplate': //set the status of a template active buttons.
        if ( ($_GET['flag'] == 'no' ) || ($_GET['flag'] == 'yes') ) {
          if ($cID) {
        if  ($_GET['case'] != 'infobox_display'){
            tep_db_query("update " . TABLE_TEMPLATE . " set  " . $_GET['case'] . " = '" . $_GET['flag'] . "' where template_id = '" . (int)$cID . "'");
            }else{
            tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set " . $_GET['case'] . " = '" . $_GET['flag'] . "' where infobox_id = '" . (int)$iID . "'");
            }
          }
        }
        
        tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $cID . '&action=edit'));
break;

case 'position_update': //set the status of a template active buttons.
        if ( ($_GET['flag'] == 'up') || ($_GET['flag'] == 'down') ) {
          if ($_GET['cID']) {
            tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set  location = '" . $_GET['loc'] .  "', last_modified = now() where location = '" . $_GET['loc1'] . "' and display_in_column = '" . $_GET['col'] . "'");
            tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set  location = '" . $_GET['loc1'] .  "', last_modified = now() where infobox_id = '" . (int)$iID . "' and display_in_column = '" . $_GET['col'] . "'");
          }
        }
        
        tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $cID . '&action=edit'));
break;

case 'setflaginfobox': //set the status of a news item.
        if ( ($_GET['flag'] == 'left') || ($_GET['flag'] == 'right') ) {
          if ($_GET['cID']) {
            tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set $case = '" . $_GET['flag'] . "' where infobox_id = '" . (int)$iID . "'");
          }
        }
        tep_fixweight();
        tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $cID . '&action=edit'));
break;

case 'save':
        $template_name = tep_db_prepare_input($_POST['template_name']);
        $cID = tep_db_prepare_input($cID);
        $sql_data_array = array('template_name' => tep_db_prepare_input($_POST['template_name']),
                                'template_cellpadding_main' => tep_db_prepare_input($_POST['template_cellpadding_main']),
                                'template_cellpadding_left' => tep_db_prepare_input($_POST['template_cellpadding_left']),
                                'template_cellpadding_right' => tep_db_prepare_input($_POST['template_cellpadding_right']),
                                 'template_cellpadding_sub' => tep_db_prepare_input($_POST['template_cellpadding_sub']),
                                 'box_width_left' => tep_db_prepare_input($_POST['box_width_left']),
                                 'box_width_right' => tep_db_prepare_input($_POST['box_width_right']),
                                 'cart_in_header' => tep_db_prepare_input($_POST['cart_in_header']),
                                 'languages_in_header' => tep_db_prepare_input($_POST['languages_in_header']),
                                 'show_header_link_buttons' => tep_db_prepare_input($_POST['show_header_link_buttons']),
                                 'include_column_left' => tep_db_prepare_input($_POST['include_column_left']),
                                 'include_column_right' => tep_db_prepare_input($_POST['include_column_right']),
                                 'module_one' => tep_db_prepare_input($_POST['module_one']),
                                 'module_two' => tep_db_prepare_input($_POST['module_two']),
                                 'module_three' => tep_db_prepare_input($_POST['module_three']),
                                 'module_four' => tep_db_prepare_input($_POST['module_four']),
                                 'module_five' => tep_db_prepare_input($_POST['module_five']),
                                 'module_six' => tep_db_prepare_input($_POST['module_six']),
                                 'customer_greeting' => tep_db_prepare_input($_POST['customer_greeting']),
                                 'edit_customer_greeting_personal' => '',
                                 'edit_customer_greeting_personal_relogon' => '',
                                 'edit_greeting_guest' => '',
                                 'main_table_border' => tep_db_prepare_input($_POST['main_table_border']),
                                 'show_heading_title_original' => tep_db_prepare_input($_POST['show_heading_title_original']),
                                 'site_width' => tep_db_prepare_input($_POST['site_width']),
                                 'side_box_left_width' => tep_db_prepare_input($_POST['side_box_left_width']),
                                 'side_box_right_width' => tep_db_prepare_input($_POST['side_box_right_width']) );

        $update_sql_data = array('last_modified' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $update_sql_data);
        tep_db_perform(TABLE_TEMPLATE, $sql_data_array, 'update', "template_id = '" . (int)$cID . "'");
        
    if (isset($_POST['default'])){
       if ($_POST['default'] == 'on') {
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $template_name . "' where configuration_key = 'DEFAULT_TEMPLATE'");
      }
    }

          tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $cID));
        //tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cID));
break;

case 'insert':
        $template_sql_file = '';
        $template_name_new = $_POST['template_name'];

        if (file_exists(DIR_FS_TEMPLATES . $template_name_new . "/install.sql")){
            $template_sql_file = "install.sql";
        } else if (file_exists(DIR_FS_TEMPLATES . $template_name_new . "/" . $template_name_new . ".sql")){
            $template_sql_file = $template_name_new . ".sql";
        } else {
            $messageStack->add_session('search', ERROR1, 'error');
            tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=new'));
        }
        
        if($template_sql_file == 'default.sql'){
            $messageStack->add_session('search', ERROR3, 'error');
            tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=new'));
        } else {
            $template_sql_file = DIR_FS_TEMPLATES . $template_name_new . "/" . $template_sql_file;
            $sql_data_array = array('template_name' => $template_name_new);
            $update_sql_data = array('date_added' => 'now()');
            $sql_data_array = array_merge($sql_data_array, $update_sql_data);
            tep_db_perform(TABLE_TEMPLATE, $sql_data_array);
            $cID = tep_db_insert_id();
            $data_query = fread(fopen( $template_sql_file, 'rb'), filesize($template_sql_file)) ;
            $data_query = str_replace('#tID#', $cID, $data_query);
            $data_query = str_replace(';', '', $data_query);

           //make an array split on end of line and
             if (isset($data_query)) {
                 $sql_array = array();
                 $sql_length = strlen($data_query);
                 $pos =  strpos($data_query, "\n");
                 
                 $data_query1 = explode("\n",$data_query);
                 $key = key($data_query1);
                 $sql_length = count($data_query1);
                 $pos = $data_query1[$key];
                 for ($i=$key; $i<$sql_length; $i++) {
                     if ( strrchr($data_query1[$i], '--') ) {
                               //if line starts with -- it's a comment ignore
                     } else if ($data_query1[$i] == '') {
                               //if line is empty ignore
                     } else {
                         tep_db_query( $data_query1[$i] );
                     }
                 }
             }//isset()
     
         // pull infobox info
             $infobox_query = tep_db_query("select box_heading, infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" .$cID. "'");
            // $infobox = tep_db_fetch_array($infobox_query);
              while ($infobox = tep_db_fetch_array($infobox_query)) {
                  $languages = tep_get_languages();
                  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                  $language_id = $languages[$i]['id'];
                  $box_heading = tep_db_prepare_input($infobox['box_heading']);
                  $infobox_id = $infobox['infobox_id'];
                  $box_heading = str_replace("'", "\\'", $box_heading);
                   tep_db_query("insert into " . TABLE_INFOBOX_HEADING . " (infobox_id, languages_id, box_heading) values ('" . $infobox_id . "', '" . $language_id . "', '" . tep_db_input($box_heading) . "') ");
               }
              }//while    
        }

        $messageStack->add_session('search', sprintf(TEMPLATE_INSTALLED_SUCCESS, $template_name_new) , 'success');
        tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION,'gID=' . $cID));

        break;

    case 'deleteconfirm':
        $cID = tep_db_prepare_input($_GET['cID']);

    //set customer template to default if selected template is deleted.
       $theme_query1 = tep_db_query("select template_name from " . TABLE_TEMPLATE . " where template_id = '" . (int)$cID . "'");
       $theme1 = tep_db_fetch_array($theme_query1);
       $sql_data_array5 = array('customers_selected_template' => '');//make it blank to use site default template
       tep_db_perform(TABLE_CUSTOMERS, $sql_data_array5, 'update', "customers_selected_template = '" . $theme1['template_name'] . "'");
       //reset affiliate template if uninstalling template is used by affiliate
       $affiliate_theme_array = array('affiliate_template' => 'NULL');//make it blank to use site default template
       tep_db_perform(TABLE_AFFILIATE_AFFILIATE, $affiliate_theme_array, 'update', "affiliate_template = '" . $theme1['template_name'] . "'");
       
       //RCI added to add delete logic for CDS and other template logics.
       echo $cre_RCI->get('templateconfiguration', 'delete', false);

      $infobox_query1 = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" .$cID. "'");
      // $infobox = tep_db_fetch_array($infobox_query);
      while ($infobox1 = tep_db_fetch_array($infobox_query1)) {
         $info_id = $infobox1['infobox_id'];          
         tep_db_query("delete from " . TABLE_INFOBOX_HEADING . " where infobox_id = '" . (int)$info_id . "'");      
      }

      tep_db_query("delete from " . TABLE_TEMPLATE . " where template_id = '" . (int)$cID . "'");
      tep_db_query("delete from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . (int)$cID . "'");
      
      //need to get default ID
      $template_id_select_query5 = tep_db_query("select template_id from " . TABLE_TEMPLATE . "  where template_name = '" . DEFAULT_TEMPLATE . "'");
      $template_id_select5 =  tep_db_fetch_array($template_id_select_query5);
      $template_default_id5 = $template_id_select5['template_id'] ;
      $messageStack->add_session('search', sprintf(TEMPLATE_UNINSTALLED_SUCCESS, $theme1['template_name']) , 'success');
      tep_redirect(tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $template_default_id5 ));
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
  <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            </tr>
          </table></td>
      </tr>
      <tr>    
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
          <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TEMPLATE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ACTIVE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DISPLAY_COLUMN_LEFT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DISPLAY_COLUMN_RIGHT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
   $count_left_active = 0;
   $count_right_active = 0;
   $infobox_query = tep_db_query("select display_in_column, infobox_display  from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $cID . "'");
  while ($infobox = tep_db_fetch_array($infobox_query)) {

      $infcol = $infobox['display_in_column'];
      $infValue = $infobox['infobox_display'];
   if (($infcol == 'left') && ($infValue != 'no')) {
    $count_left_active++;
   } else if (($infcol == 'right') && ($infValue != 'no'))
   {
    $count_right_active++;
    }
  }

  $curr_templates = '';

  $template_query = tep_db_query("select * from " . TABLE_TEMPLATE . "  order by template_name");
  while ($template = tep_db_fetch_array($template_query)) {

  $curr_templates .= $template['template_name'].",";

    if ((!isset($cID) || (isset($cID) && ($cID == $template['template_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $tInfo_array = ($template);
      $tInfo = new objectInfo($tInfo_array);
    }
    if  (isset($tInfo) && $template['template_id'] == $tInfo->template_id)  {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION,'cID=' .       $template['template_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION,'cID=' . $template['template_id']) . '\'">' . "\n";
    }
     if (DEFAULT_TEMPLATE == $template['template_name']) {
      echo '                <td class="dataTableContent"><b>' . $template['template_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $template['template_name'] . '</td>' . "\n";
   }

//   }
//  }else{
//$messageStack->add_session('search', TEMPLATE_ERROR_2,'error');
//  }
?>
              <td class="dataTableContent" align="center"><?php
      if ($template['active'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=setflag&flag=0&cID=' . $template['template_id'] ) . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=setflag&flag=1&cID=' . $template['template_id']) . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
      }
?></td>
                <td class="dataTableContent" align="center"><?php
      if ($template['include_column_left'] == 'yes') {
        echo tep_image(DIR_WS_IMAGES . 'icon_y_green.gif', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=setflagtemplate&case=include_column_left&flag=no&cID=' . $template['template_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_n_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=setflagtemplate&case=include_column_left&flag=yes&cID=' . $template['template_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_y_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_n_red.gif', IMAGE_ICON_STATUS_RED, 16, 16);
      }
?></td>
                <td class="dataTableContent" align="center"><?php
      if ($template['include_column_right'] == 'yes') {
        echo tep_image(DIR_WS_IMAGES . 'icon_y_green.gif', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=setflagtemplate&case=include_column_right&flag=no&cID=' . $template['template_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_n_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'action=setflagtemplate&case=include_column_right&flag=yes&cID=' . $template['template_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_y_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_n_red.gif', IMAGE_ICON_STATUS_RED, 16, 16);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if ( (isset($tInfo) && is_object($tInfo)) && ($template['template_id'] == $tInfo->template_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'gID=' . $gID . '&cID=' . $template['template_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>
                  &nbsp;</td>
              </tr>
              <?php
  }
?>
              <?php
  if ($action != 'new') {
?>
              </tr>
              </table>
              <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td align="right" colspan="5" class="smallText"><br>
                  <?php echo '<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION,'action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?>&nbsp;&nbsp;</td>
                <?php
  }
?>
              <tr>
                <td colspan="5"><?php require('includes/modules/ez_installer/template_installer.php'); ?>
                </td>
              </tr>
              <tr>
                <td><?php
// as more error messages are added this will change into a case statement
//if ($error == 'error1'){
//echo ERROR1;
//}
//if ($error == 'error2'){
//echo ERROR2;
//}
 ?>
                </td>
              </tr>
            </table></td>
          <?php

  $heading = array();
  $contents = array();


//get template type
if (isset($tInfo->template_name)){

    if ( file_exists(DIR_FS_TEMPLATES .$tInfo->template_name . '/template.php')) {
  include_once(DIR_FS_TEMPLATES .$tInfo->template_name . '/template.php');
 $template_type = TEMPLATE_SYSTEM ;
     } else {
  $template_type = 'cre_legacy';
    }

}

  if(isset($tInfo->template_name)) {
      $template_path = DIR_FS_TEMPLATES .$tInfo->template_name . '/template.php';
    } else {
      $template_path = '';
    }


  switch ($action) {
//************************************************************************************************************************
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_TEMPLATE . '</b>');

      $contents = array('form' => tep_draw_form('new_template', FILENAME_TEMPLATE_CONFIGURATION, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);

    if ($handle = opendir(DIR_FS_TEMPLATES)) {
      /* This is the correct way to loop over the directory. */
        while (false !== ($file = readdir($handle))) {
        if(is_dir(DIR_FS_TEMPLATES . '/' . $file) && stristr($curr_templates.".,..,content,CVS", $file ) == FALSE){
        if ( ($file == 'default') || ($file == 'boxes') || ($file == 'mainpage_modules')|| ($file == '.svn') ){
        }else{
        $dirs[] = $file;
          $dirs_array[] = array('id' => $file,
                                 'text' => $file);
           }
        }
        }
        closedir($handle);
      }

      if(count($dirs_array) == 0){
    $contents[] = array('text' => '<br>' . TEMPLATE_NAME . '<br>' . tep_draw_input_field('template_name'));
      }
      else{
      sort($dirs_array);
      $contents[] = array('text' => '<br>' . TEXT_TEMPLATE_NAME . '<br>' . tep_draw_pull_down_menu('template_name', $dirs_array, '', "style='width:150;'"));
      }

      $contents[] = array('text' => '<br>' . TEXT_TEMPLATE_IMAGE . '<br>' . tep_draw_file_field('template_image'));


      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION,'gID=1') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE));

      break;
//*************************************************************************************************************************

//*************************************************************************************************************************
      case 'edit':

      switch ($tInfo->cart_in_header) {
        case 'no': $cart_in_status = false; $cart_out_status = true; break;
        case 'yes': $cart_in_status = true; $cart_out_status = false; break;
        default: $cart_in_status = true; $cart_out_status = false;
      }
      switch ($tInfo->languages_in_header) {
        case 'no': $yes_status = false; $no_status = true; break;
        case 'yes': $yes_status = true; $no_status = false; break;
        default: $yes_status = true; $no_status = false;

      }
      switch ($tInfo->include_column_left) {
        case 'no': $no_left_status = true; $yes_left_status = false; break;
        case 'yes': $no_left_status = false; $yes_left_status = true; break;
        default: $no_left_status = false; $yes_left_status = true;
      }
      switch ($tInfo->include_column_right) {
        case 'no': $no_right_status = true; $yes_right_status = false; break;
        case 'yes': $no_right_status = false; $yes_right_status = true; break;
        default: $no_right__status = false; $yes_right_status = true;
      }

      switch ($tInfo->show_header_link_buttons) {
        case 'no': $links_no_status = true; $links_yes_status = false; break;
        case 'yes': $links_no_status = false; $links_yes_status = true; break;
        default: $links_no_status = false; $links_yes_status = true;
      }

      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_TEMPLATE . '</b>');

      $contents = array('form' =>  tep_draw_form('template', FILENAME_TEMPLATE_CONFIGURATION,'cID=' . $tInfo->template_id . '&action=save', 'post', 'enctype="multipart/form-data"'));


      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $contents[] = array('text' => TEXT_TEMPLATE_NAME . tep_draw_hidden_field('template_name', $tInfo->template_name) . $tInfo->template_name);
      if (DEFAULT_TEMPLATE != $tInfo->template_name) $contents[] = array('text' =>'<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
        $contents[] = array('text' =>  TEXT_TEMPLATE_SYSTEM . '<b>' . $template_type . '</b>'. TEXT_TEMPLATE_SYSTEM_1 . '<br><br>');

      $contents[] = array('text' => TEXT_SITE_WIDTH . '  ' . tep_draw_input_field('site_width', $tInfo->site_width,'size="3"') . '<br>');

      $contents[] = array('text' => TEXT_HEADER . '<br>');

      $contents[] = array('text' => TEXT_INCLUDE_CART_IN_HEADER. '<br>  ' .TEXT_YES . tep_draw_radio_field('cart_in_header', 'yes', $cart_in_status) . '&nbsp;' . tep_draw_radio_field('cart_in_header', 'no', $cart_out_status) . TEXT_NO . '<br>');


      $contents[] = array('text' => TEXT_INCLUDE_LANGUAGES_IN_HEADER. '<br>' .TEXT_YES . tep_draw_radio_field('languages_in_header', 'yes', $yes_status) . '&nbsp;' . tep_draw_radio_field('languages_in_header', 'no', $no_status) . TEXT_NO . '<br>');

      $contents[] = array('text' => TEXT_INCLUDE_HEADER_LINK_BUTTONS. '<br>' .TEXT_YES . tep_draw_radio_field('show_header_link_buttons', 'yes', $links_yes_status) . '&nbsp;' . tep_draw_radio_field('show_header_link_buttons', 'no', $links_no_status) . TEXT_NO . '<br>');

    $contents[] = array('text' => '<br>' . TEXT_TABLE_CELL_PADDING . '<br>');

     $select_box = '<select name="template_cellpadding_main" " style="width: 40">';
for ($i = 0; $i <= 10; $i++) {
      $select_box .= '<option value="' . $i . '"';
      if ($i == $tInfo->template_cellpadding_main) $select_box .= ' selected="selected"';
      $select_box .= '>' . $i . '</option>';
}
    $select_box .= "</select>";

      $contents[] = array('text' => TEXT_TEMPLATE_CELLPADDING_MAIN . '  ' .  $select_box . '<br>');

     $select_box = '<select name="template_cellpadding_sub" " style="width: 40">';
for ($i = 0; $i <= 10; $i++) {
      $select_box .= '<option value="' . $i . '"';
      if ($i == $tInfo->template_cellpadding_sub) $select_box .= ' selected="selected"';
      $select_box .= '>' . $i . '</option>';
}
    $select_box .= "</select>";

      $contents[] = array('text' => TEXT_TEMPLATE_CELLPADDING_SUB . '  ' . $select_box);


//-----------
     $contents[] = array('text' => '<br>' . TEXT_TABLE_CELL_LEFT_RIGHT . '<br>');

       $select_box = '<select name="side_box_left_width" " style="width: 40">';
  for ($i = 0; $i <= 20; $i++) {
        $select_box .= '<option value="' . $i . '"';
        if ($i == $tInfo->side_box_left_width) $select_box .= ' selected="selected"';
        $select_box .= '>' . $i . '</option>';
  }

    $select_box .= "</select>";
      $contents[] = array('text' => TEXT_TEMPLATE_LEFT_SIDE . '  ' .  $select_box . '<br>');

           $select_box = '<select name="side_box_right_width" " style="width: 40">';
    for ($i = 0; $i <= 20; $i++) {
          $select_box .= '<option value="' . $i . '"';
          if ($i == $tInfo->side_box_right_width) $select_box .= ' selected="selected"';
          $select_box .= '>' . $i . '</option>';
    }
      $select_box .= "</select>";
      $contents[] = array('text' => TEXT_TEMPLATE_RIGHT_SIDE . '  ' .  $select_box . '<br>');

//--------

      $contents[] = array('text' => '<br>' . TEXT_LEFT_COLUMN . '<br>');

      $contents[] = array('text' => TEXT_INCLUDE_COLUMN_LEFT .'<br>' .TEXT_YES . tep_draw_radio_field('include_column_left', 'yes', $yes_left_status) . '&nbsp;' . tep_draw_radio_field('include_column_left', 'no', $no_left_status) . TEXT_NO . '<br>');

     if ($tInfo->include_column_left == 'yes') {
      $contents[] = array('text' => TEXT_COLUMN_LEFT_WIDTH . '  ' . tep_draw_input_field('box_width_left', $tInfo->box_width_left,'size="3"'). '<br>');
     $select_box = '<select name="template_cellpadding_left" " style="width: 40">';
for ($i = 0; $i <= 10; $i++) {
      $select_box .= '<option value="' . $i . '"';
      if ($i == $tInfo->template_cellpadding_left) $select_box .= ' selected="selected"';
      $select_box .= '>' . $i . '</option>';
}
    $select_box .= "</select>";
      $contents[] = array('text' => TEXT_TEMPLATE_CELLPADDING_LEFT . '  ' . $select_box);
}else {
      $contents[] = array('text' => tep_draw_hidden_field('box_width_left', $tInfo->box_width_left));
      $contents[] = array('text' => tep_draw_hidden_field('template_cellpadding_left', $tInfo->template_cellpadding_left));
}
      $contents[] = array('text' => '<br>' . TEXT_RIGHT_COLUMN . '<br>');

      $contents[] = array('text' => TEXT_INCLUDE_COLUMN_RIGHT . '<br>' .TEXT_YES . tep_draw_radio_field('include_column_right', 'yes', $yes_right_status) . '&nbsp;' . tep_draw_radio_field('include_column_right', 'no', $no_right_status) . TEXT_NO. '<br>');

     if ($tInfo->include_column_right == 'yes') {
      $contents[] = array('text' => TEXT_COLUMN_RIGHT_WIDTH . '  ' . tep_draw_input_field('box_width_right', $tInfo->box_width_right,'size="3"') . '<br>');

     $select_box = '<select name="template_cellpadding_right" " style="width: 40">';
      for ($i = 0; $i <= 10; $i++) {
      $select_box .= '<option value="' . $i . '"';
      if ($i == $tInfo->template_cellpadding_right) $select_box .= ' selected="selected"';
      $select_box .= '>' . $i . '</option>';
  }
      $select_box .= "</select>";

      $contents[] = array('text' => TEXT_TEMPLATE_CELLPADDING_RIGHT  . '  ' . $select_box . '<br>');

  }else {
      $contents[] = array('text' => tep_draw_hidden_field('box_width_right', $tInfo->box_width_right));
      $contents[] = array('text' => tep_draw_hidden_field('template_cellpadding_right', $tInfo->template_cellpadding_right));
  }


//swtich between mainpage_module directoies
 if ( file_exists(DIR_FS_TEMPLATES . $tInfo->template_name . '/template.php')) {
   if(is_dir(DIR_FS_TEMPLATE_MAINPAGES)) {
  $modules_folder = DIR_FS_TEMPLATE_MAINPAGES;
  }else{
  $modules_folder = (DIR_FS_TEMPLATES .'default/mainpage_modules/');
  }
}else{
  if(is_dir(DIR_FS_TEMPLATES .$tInfo->template_name . '/mainpage_modules/')) {
   $modules_folder = (DIR_FS_TEMPLATES .$tInfo->template_name . '/mainpage_modules/');
  }else{
   //$modules_folder = (DIR_FS_CATALOG_MODULES. 'mainpage_modules'); // we are no more using includes/modules/mainpage_modules/ directory
   $modules_folder = (DIR_FS_TEMPLATES .'default/mainpage_modules/');
  }
}
                 $modules_folder_1 = $modules_folder  ;
                 $modules_folder_2 = wordwrap($modules_folder_1, 30, "<br>\n", 1);
      $contents[] = array('text' => '<br>' . TEXT_MAINPAGE_MODULES .'<br>');
      $contents[] = array('text' =>  TEXT_MAINPAGE_MODULES_LOCATION .'<br>');

      $contents[] = array('text' => $modules_folder_2 . '<br>');



    if ($handle = opendir($modules_folder)) {
          $dirs[] = array();
          $dirs_array[] = array('text' => '');

        while (false !== ($file = readdir($handle))) {
    if (stristr($file,".php") || stristr($file,".htm") || stristr($file,".html")) {

          $dirs_array[] = array('id' => $file,
                                 'text' => $file);
           }
        }
        closedir($handle);
      }
      sort($dirs_array);


      $contents[] = array('text' => '<br>' . '1  ' . tep_draw_pull_down_menu('module_one', $dirs_array, $tInfo->module_one, "style='width:150;'"));
      $contents[] = array('text' => '2  ' . tep_draw_pull_down_menu('module_two', $dirs_array, $tInfo->module_two, "style='width:150;'"));
      $contents[] = array('text' => '3  ' . tep_draw_pull_down_menu('module_three', $dirs_array, $tInfo->module_three, "style='width:150;'"));
      $contents[] = array('text' => '4  ' . tep_draw_pull_down_menu('module_four', $dirs_array, $tInfo->module_four, "style='width:150;'"));
      $contents[] = array('text' => '5  ' . tep_draw_pull_down_menu('module_five', $dirs_array, $tInfo->module_five, "style='width:150;'"));
      $contents[] = array('text' => '6  ' . tep_draw_pull_down_menu('module_six', $dirs_array, $tInfo->module_six, "style='width:150;'"));
      $contents[] = array('text' => '<br>' . TEXT_OTHER . '<br>');

      switch ($tInfo->customer_greeting) {
        case 'no': $show_greet_no = true; $show_greet_yes = false; break;
        case 'yes': $show_greet_no = false; $show_greet_yes = true; break;
        default: $show_greet_no = false; $show_greet_yes = true;
      }
      switch ($tInfo->main_table_border) {
        case 'no': $use_border_no = true; $use_border_yes = false; break;
        case 'yes': $use_border_no = false; $use_border_yes = true; break;
        default: $use_border_no = false; $use_border_yes = true;
      }
      switch ($tInfo->show_heading_title_original) {
        case 'no': $orig_page_headers_no = true; $orig_page_headers_yes = false; break;
        case 'yes': $orig_page_headers_no = false; $orig_page_headers_yes = true; break;
        default: $orig_page_headers_no = false; $orig_page_headers_yes = true;
      }

      $contents[] = array('text' => TEXT_SHOW_CUSTOMER_GREETING. '<br>' .TEXT_YES . tep_draw_radio_field('customer_greeting', 'yes', $show_greet_yes) . '&nbsp;' . tep_draw_radio_field('customer_greeting', 'no', $show_greet_no) . TEXT_NO . '<br>');
      $contents[] = array('text' => TEXT_INCLUDE_MAIN_TABLE_BORDER.'<br>' .TEXT_YES . tep_draw_radio_field('main_table_border', 'yes', $use_border_yes) . '&nbsp;' . tep_draw_radio_field('main_table_border', 'no', $use_border_no) . TEXT_NO . '<br>');
      $contents[] = array('text' => TEXT_SHOW_ORIGINAL_PAGE_HEADERS. '<br>' .TEXT_YES . tep_draw_radio_field('show_heading_title_original', 'yes', $orig_page_headers_yes) . '&nbsp;' . tep_draw_radio_field('show_heading_title_original', 'no', $orig_page_headers_no) . TEXT_NO . '<br>');
      $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION,'&cID=' . $tInfo->template_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE));
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_TEMPLATE . '</b>');
      $contents = array('form' => tep_draw_form('theme', FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $tInfo->template_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $tInfo->template_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION,'cID=' . $tInfo->template_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_module_remove.gif', IMAGE_DELETE));
      break;
    default:
      if (is_object($tInfo)) {
        $heading[] = array('text' => '<b>' . $tInfo->template_name . '</b>');
        if(DEFAULT_TEMPLATE == $tInfo->template_name) { // Do not allow deleting site default template!
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $tInfo->template_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="javascript:onClick=alert(\'' . ERROR_NO_DELETE . '\');">' . tep_image_button('button_module_remove.gif', IMAGE_DELETE) . '</a>');
        } else {
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $tInfo->template_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_TEMPLATE_CONFIGURATION,'cID=' . $tInfo->template_id . '&action=delete') . '">' . tep_image_button('button_module_remove.gif', IMAGE_DELETE) . '</a>');
        }
        $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' <b>' . tep_date_short($tInfo->date_added) . '</b>');
        if (tep_not_null($tInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' <b>' . tep_date_short($tInfo->last_modified) . '</b>');
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_TEMPLATES . $tInfo->template_name .'/images/' .$tInfo->template_image, $tInfo->template_name,'200','160'));
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '           <td width="25%" valign="top">' . "\n";
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
