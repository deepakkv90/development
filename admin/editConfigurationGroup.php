<?php
/*
  $Id: products_expected.php,v 1.1.1.1 2004/03/04 23:38:55 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $groupid=$_REQUEST['grpID'];
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
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_EXPECTED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php

   if($action=='update_key')
   {
      $sql_data_array = array('configuration_title'=> $_REQUEST['configtitle'],'configuration_value'=> tep_db_input($_POST['configvalue']),'configuration_description'=> $_REQUEST['configdesc'],'configuration_group_id'=> $_REQUEST['configgroup'],'last_modified'=>'now()');
    tep_db_perform(TABLE_CONFIGURATION, $sql_data_array, 'update', "configuration_id   = '" .$_GET['pID']. "'");   
   }

  $products_query_raw = "select configuration_id,configuration_title,configuration_value,configuration_description,configuration_id,configuration_group_title    from " . TABLE_CONFIGURATION . " a,".TABLE_CONFIGURATION_GROUP." b  where a.configuration_group_id=b.configuration_group_id and  a.configuration_group_id = ".  $groupid." order by a.sort_order";
  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    if ((!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['configuration_id']))) && !isset($pInfo)) {
    $pInfo = new objectInfo($products);
    }

    if (isset($pInfo) && is_object($pInfo) && ($products['configuration_id '] == $pInfo->configuration_id )) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_EDIT_CONFIGURATION_GROUP, 'pID=' . $products['configuration_id'] . '&action=new_product&grpID='.$groupid) . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_EDIT_CONFIGURATION_GROUP, 'page=' . $_GET['page'] . '&pID=' . $products['configuration_id'].'&grpID='.$groupid) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $products['configuration_title']; ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_date_short($products['']); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['configuration_id '] == $pInfo->configuration_id )) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_EDIT_CONFIGURATION_GROUP, 'page=' . $_GET['page'] . '&pID=' . $products['configuration_id '].'&grpID='.$groupid) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED); ?></td>
                    <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($action) {
   case 'edit_key':
   
      $config_array = array();
          $config_query = tep_db_query("select configuration_group_id,configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " order by configuration_group_title");
          while ($config_values = tep_db_fetch_array($config_query)) {
            $config_array[] = array('id' => $config_values['configuration_group_id'], 'text' => $config_values['configuration_group_title']);
          }
          
        if(strlen($pInfo->configuration_title)>35)
      {$editheading=substr($pInfo->configuration_title,0,35)."...";}else{$editheading=$pInfo->configuration_title;}
        $heading[] = array('text' => '<b>' . $editheading . '</b>');        
        $contents = array('form' => tep_draw_form('configkeys',FILENAME_EDIT_CONFIGURATION_GROUP , tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->configuration_id  . '&action=update_key'));              
      $contents[] = array('text' => '<br>' . TEXT_CONFIG_TITLE . '<br>' . tep_draw_input_field('configtitle', $pInfo->configuration_title,'size="10"'));
      $contents[] = array('text' => '<br>' . TEXT_CONFIG_VALUE . '<br>' . tep_draw_input_field('configvalue', $pInfo->configuration_value,'size="10"'));
      $contents[] = array('text' => '<br>' . TEXT_CONFIG_DESCRIPTION . '<br>' . tep_draw_input_field('configdesc', $pInfo->configuration_description,'size="30"'));
      $contents[] = array('text' => '<br>' . TEXT_CONFIG_GROUP . '<br>' . tep_draw_pull_down_menu('configgroup', $config_array,$groupid));

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_EDIT_CONFIGURATION_GROUP, tep_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->configuration_id ) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
   
   break;
   default:
      if (isset($pInfo) && is_object($pInfo)) {
        if(strlen($pInfo->configuration_title)>35)
      {$editheading=substr($pInfo->configuration_title,0,35)."...";}else{$editheading=$pInfo->configuration_title;}
        $heading[] = array('text' => '<b>' . $editheading . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_EDIT_CONFIGURATION_GROUP, 'pID=' . $pInfo->configuration_id  . '&action=edit_key&grpID='.$groupid) . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_CONFIG_TITLE . '<br>' . $pInfo->configuration_title);
      $contents[] = array('text' => '<br>' . TEXT_CONFIG_VALUE . '<br>' . $pInfo->configuration_value);
      $contents[] = array('text' => '<br>' . TEXT_CONFIG_DESCRIPTION . '<br>' . $pInfo->configuration_description);
      $contents[] = array('text' => '<br>' . TEXT_CONFIG_GROUP . '<br>' . $pInfo->configuration_group_title);     
      }
  break;
  }
  
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) 
  {
      echo '<td width="25%" valign="top">' . "\n";
      $box = new box;
      echo $box->infoBox($heading, $contents);
      echo '</td>' . "\n";
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