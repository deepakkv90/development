<?php
/*
  $Id: fdm_customer_downloads.php, v 1.0.0.0 2006/10/10 23:39:00 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  $languages = tep_get_languages();
  $action = $_GET['action'];
  $cID=$_GET['cID'];
  $file_id=$_GET['file_id'];
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
<?php $padding = ($is_62 == true) ? 2 : 0; ?>
<table border="0" width="100%" cellspacing="<?php echo $padding; ?>" cellpadding="<?php echo $padding; ?>" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php 
    if ($is_62 == true) echo '<td width="' . BOX_WIDTH . '" valign="top"><table border="0" width="' . BOX_WIDTH . '" cellspacing="1" cellpadding="1" class="columnLeft">' . "\n";
    require(DIR_WS_INCLUDES . 'column_left.php'); 
    if ($is_62 == true) echo '</table></td>' . "\n";
    ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <?php
        switch ($action) {

         case 'detail':
          $customer_info = tep_db_fetch_array(tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $cID . "'"));
          ?>
           <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE_LOG; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                <td class="pageHeading" align="center"><a href="<?php echo tep_href_link(FILENAME_CUSTOMER_DOWNLOADS, tep_get_all_get_params(array('action'))); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a></td>
              </tr>
            </table></td>
          </tr>
          <tr>
      
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
           </tr> 
          <tr>
              <td class="main"><b><?php echo TEXT_CUSTOMER; ?></b><u><a href="<?php  echo tep_href_link(FILENAME_CUSTOMERS, 'search=' .$cID); ?>"><?php echo $customer_info['customers_firstname'] . ' ' . $customer_info['customers_lastname']; ?></a></u></td>
          </tr>
           <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
           </tr>   
          <?php 
            //$download_array = tep_db_fetch_array($download_query)
          $sql_info=("select lf.files_name,fi.icon_small from " . TABLE_LIBRARY_FILES . " lf left join " . TABLE_FILE_ICONS . " fi on lf.files_icon = fi.icon_id where lf.files_id = '" . $file_id . "'");
          $res_info=tep_db_query($sql_info);
          $file_info=tep_db_fetch_array($res_info);
          ?>
            <tr>
              <td class="main">
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" width="25"><strong><?php echo HEADING_TITLE_FILE . ":" ;?></strong></td>
                  <td style="padding-left:6" width="<?php echo FDM_SMALL_ICON_IMAGE_WIDTH; ?>"><?php echo  tep_image(DIR_WS_IMAGES . 'file_icons/' . $file_info['icon_small']);?></td>
                  <td style="padding-left:2" align="left"><u><?php echo  '<a href="' . HTTP_SERVER . HTTP_COOKIE_PATH . FILENAME_FILE_DETAIL, '?file_id=' . $file_id . '"target="_blank">'.$file_info['files_name'] . '</a>'?></strong></u></td>
                </tr>
              </table>
            </td>
            </tr>
          <tr>
            <td class="main">
                <table width="100%" border="1" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                   <td class="dataTableHeadingContent" align="center" width="150"><b><?php echo TABLE_HEADING_TIME_STAMP; ?></b></td>
                   <td class="dataTableHeadingContent" align="center" width="100"><b><?php echo TABLE_HEADING_SIZE; ?></b></td>
                   <td class="dataTableHeadingContent" align="center" width="150"><b><?php echo TABLE_HEADING_FILE_DATE; ?></b></td>
                   <td class="dataTableHeadingContent" align="center" width="40%"><b><?php echo TABLE_HEADING_PAGE; ?></b></td>
                   <td class="dataTableHeadingContent" align="center" width="120"><b><?php echo TABLE_HEADING_IP; ?></b></td>
                </tr>       
                <?php 
                $sql_download = ("select lf.files_name, lf.files_date_added, lf2f.folders_id, fi.icon_small, lfd.download_time, lfd.page, lfd.ip_addr from ".TABLE_LIBRARY_FILES_TO_FOLDERS."  lf2f, ".TABLE_LIBRARY_FILES_DOWNLOAD." lfd, " . TABLE_LIBRARY_FILES . " lf left join  " . TABLE_FILE_ICONS . "  fi on lf.files_icon = fi.icon_id where lfd.files_id =lf.files_id and  lf2f.files_id = lf.files_id and lfd.customers_id=$cID and lf.files_id = '" . $file_id . "'");
                $download_query=tep_db_query($sql_download);
                while ($download_array = tep_db_fetch_array($download_query)) {
                  $files_name = DIR_FS_CATALOG.LIBRARY_DIRECTORY . $download_array['files_name']; 
                  if (file_exists($files_name)) {
                    $f_size = (int)@filesize($files_name);
                    $human_readable_size = cre_resize_bytes($f_size);
                    $files_date = date("Y-m-d H:i:s", filectime($files_name));    
                  }else{
                    $human_readable_size = '';
                    $files_date = '<span class="errorText">' . TEXT_FILE_UNAVAILABLE . '</span>';
                  }
                  ?>
             <tr class="dataTableRow">
               <td  class="dataTableContent" align="center" width="150">&nbsp;<?php echo $download_array['download_time']; ?></td>
                   <td class="dataTableContent" align="center" width="100">&nbsp;<?php echo $human_readable_size; ?></td>
                   <td class="dataTableContent" align="center" width="150">&nbsp;<?php echo $files_date;  ?></td>
                   <td class="dataTableContent" align="left" width="40%">&nbsp;<?php echo $download_array['page']; ?></td>
                   <td class="dataTableContent" align="center" width="120">&nbsp;<?php echo $download_array['ip_addr']; ?></td>
                  </tr>
                  <?php
                }
                ?>
              </table>
            </td>       
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <?php
            break;

          default:
           $customer_info = tep_db_fetch_array(tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $cID . "'"));
          ?>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo sprintf(HEADING_TITLE, $customer_info['customers_firstname'] . ' ' . $customer_info['customers_lastname']); ?></td>
                <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                <td class="pageHeading" align="right"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('pg', 'page', 'fID')) . 'page=' . $_GET['pg']); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo HEADING_TITLE_FILE; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TEXT_DOWNLOAD_LOG; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                  </tr>
                  <?php
                  $filedownload_query_raw = "select lfd.files_id, lfd.files_descriptive_name, fi.icon_small, o.orders_id from " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, " . TABLE_FILE_ICONS . " fi, " . TABLE_LIBRARY_FILES . " lf, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_LIBRARY_PRODUCTS . " lp where lfd.files_id = lf.files_id and fi.icon_id = lf.files_icon and o.orders_id = op.orders_id and op.products_id = lp.products_id and o.customers_id = '" . $_GET['cID'] . "' and lp.library_id = lf.files_id group by lfd.files_id";
                  $filedownload_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $filedownload_query_raw, $filedownload_query_numrows);
                  $filedownload_query = tep_db_query($filedownload_query_raw);
                  while ($filedownload = tep_db_fetch_array($filedownload_query)) {
                    if ((!isset($_GET['fID']) || (isset($_GET['fID']) && ($_GET['fID'] == $filedownload['files_id']))) && !isset($fInfo)) {
                      $fInfo = new objectInfo($filedownload);
                    }
                    if (isset($fInfo) && is_object($fInfo) && ($filedownload['files_id'] == $fInfo->files_id)) {
                      echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMER_DOWNLOADS, tep_get_all_get_params(array('page', 'fID', 'cID', 'action')) . 'page=' . $_GET['page'] . '&fID=' . $fInfo->files_id . '&cID=' . $_GET['cID'] . '&action=edit') . '\'">' . "\n";
                    } else {
                      echo'<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMER_DOWNLOADS, tep_get_all_get_params(array('page', 'fID', 'cID', 'action')) . 'page=' . $_GET['page'] . '&cID=' . $_GET['cID'] . '&fID=' . $filedownload['files_id']) . '\'">' . "\n";
                    }
                    ?>
                    <td class="dataTableContent"><?php echo tep_image('../images/file_icons/' . $filedownload['icon_small']) . '&nbsp;&nbsp;' . $filedownload['files_descriptive_name']; ?></td>
                    <td class="dataTableContent" align="center"><a href="<?php echo tep_href_link(FILENAME_CUSTOMER_DOWNLOADS, tep_get_all_get_params(array('page', 'fID', 'cID', 'action')) . 'action=detail&cID=' . $_GET['cID'] . '&fID=' . $filedownload['files_id'] . '&page=' . $_GET['page']); ?>"><?php echo tep_image(DIR_WS_IMAGES . 'icons/preview.gif'); ?></a></td>
                    <td class="dataTableContent" align="right"><?php
                    if (isset($fInfo) && is_object($fInfo) && ($filedownload['files_id'] == $fInfo->files_id)) { 
                      echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                      } else { 
                      echo '<a href="' . tep_href_link(FILENAME_CUSTOMER_DOWNLOADS, 'page=' . $_GET['page'] . '&cID=' . $_GET['cID'] . '&fID=' . $filedownload['files_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                    } 
                    ?>&nbsp;
                    </td>
                  </tr>
                  <?php
                    $last_product_id = $filedownload['files_id'];
                  }
                  ?>
                  <tr>
                    <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="smallText" valign="top"><?php echo $filedownload_split->display_count($filedownload_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_FILES); ?></td>
                        <td class="smallText" align="right"><?php echo $filedownload_split->display_links($filedownload_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
                <?php
                  $heading = array();
                  $contents = array();
                  
                  if (isset($fInfo) && is_object($fInfo)) {
                     $heading[] = array('text' => '<b>' . $fInfo->file_ext . '</b>');
                   $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS, 'oID=' . $fInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a>');
                  }
                  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                    echo '<td width="20%" valign="top">' . "\n";
                    $box = new box;
                    echo $box->infoBox($heading, $contents);
                    echo '</td>' . "\n";
                  }
                ?>
              </tr>
            </table></td>
          </tr>
          <?php 
            break;
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