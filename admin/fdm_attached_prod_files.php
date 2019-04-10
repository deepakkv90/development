<?php
/*
  $Id: fdm_attached_prod_files.php,v 1.0.0.0 2007/01/03 13:41:11 avicrw Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
                <td class="dataTableHeadingContent" align = 'left' width = '5%'><?php echo TABLE_HEADING_NUMBER; ?></td> 
                <td class="dataTableHeadingContent" align = 'left' width = '29%'><?php echo TABLE_HEADING_PRODUCT_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center" width = '10%'><?php echo TABLE_HEADING_PURCHASE_REQUIRED; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right" width = '5%'><?php echo TABLE_HEADING_FILES_ID; ?>&nbsp;&nbsp;</td>
                <td class="dataTableHeadingContent" width = '29%' align = 'left'><?php echo TABLE_HEADING_FILE_NAME; ?></td>                
                <td class="dataTableHeadingContent" align="center" width = '6%'><?php echo TABLE_HEADING_FILE_AVAILABILITY; ?></td>
                <td class="dataTableHeadingContent" align = 'center' width = '10%'><?php echo TABLE_HEADING_FILES_STATUS; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center" align="center" width = '6%' ><?php echo TABLE_HEADING_VIEWED; ?></td>
              </tr>
              <?php
              if (isset($_GET['page']) && ($_GET['page'] > 1)) { 
                $rows = (int)$_GET['page'] * FDMS_MAX_DISPLAY_SEARCH_RESULTS - FDMS_MAX_DISPLAY_SEARCH_RESULTS;   
                $page = (int)$_GET['page'];
              } else {
                $page = 1;
              }
              $rows = 0;
              $icount = 0;
              $products_with_files_query_raw = "SELECT pd.products_name, lf.files_id, lf.files_name, lf.file_availability, lf.files_status, lp.*, lf.files_download, p.products_id  
                                                                  from " . TABLE_PRODUCTS_DESCRIPTION . " pd, 
                                                                         " . TABLE_LIBRARY_FILES . " lf, 
                                                                         " . TABLE_PRODUCTS . " p 
                                                                 LEFT JOIN " . TABLE_LIBRARY_PRODUCTS . " lp 
                                                                  on (p.products_id = lp.products_id) 
                                                                WHERE p.products_id = pd.products_id 
                                                                  and lp.library_id = lf.files_id 
                                                                  and lp.products_id is not null 
                                                                  and pd.language_id = '" . $languages_id . "' 
                                                                ORDER BY lf.files_id ASC";

              $products_with_files_split = new splitPageResults($page, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $products_with_files_query_raw, $products_with_files_query_numrows);
              $products_with_files_query = tep_db_query($products_with_files_query_raw);
              while ($products_with_files = tep_db_fetch_array($products_with_files_query)) {
                $rows++;
                if (strlen($rows) < 2) {
                  $rows = '0' . $rows;
                }
                if ($tmp_id > 0) {
                  if ($tmp_id != $products_with_files['products_id']) {
                    $tmp_id = $products_with_files['products_id'];
                    $i_tmp_id = $tmp_id;
                    $s_tmp_prod_name = $products_with_files['products_name'];
                    $icount++;
                  } else {
                    $i_tmp_id = '';
                    $s_tmp_prod_name = '';
                  }
                } else if ($tmp_id == 0) {
                  $tmp_id = $products_with_files['products_id'];
                  $i_tmp_id = $tmp_id;
                  $s_tmp_prod_name = $products_with_files['products_name'];
                  $icount++;
                }
                if ($rows%2 == 0) {
                  $s_class = 'class="dataTableRow"';     
                } else {
                  $s_class = 'bgcolor = "#FFFFFF"';
                }
                ?>
                <!-- tr <?php echo $s_class;?> onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" -->
                <tr <?php echo $s_class;?>>
                 <td class="dataTableContent"><?php echo $i_tmp_id; ?></td>
                 <td class="dataTableContent"><?php echo  $s_tmp_prod_name; ?></td>              
                 <td class="dataTableContent" align="center">
                   <?php                    
                   if ($products_with_files['purchase_required'] == 1) {
                     echo tep_image(DIR_WS_ICONS . 'check_mark_small.gif', ICON_TICK);
                   }
                   ?>&nbsp;
                 </td>      
                 <td class="dataTableContent" align="right"><?php echo $products_with_files['files_id']; ?>&nbsp;&nbsp;</td>
                 <td class="dataTableContent" align="left"><?php echo $products_with_files['files_name']; ?></td>
                 <td class="dataTableContent" align="center" >
                   <?php                    
                    if ($products_with_files['file_availability'] == 0) {
                      echo TEXT_FILE_FREE;
                    } else if ($products_with_files['file_availability'] == 1) {
                      echo TEXT_FILE_LOGIN;
                    } else if ($products_with_files['file_availability'] == 2) {
                      echo TEXT_FILE_PURCHASE_TXT;
                    }
                   ?>&nbsp;&nbsp;
                  </td>
                 <td class="dataTableContent" align="center">
                   <?php
                     if ($products_with_files['files_status'] == '1') {
                       echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;';
                     } else {
                       echo  tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10).'&nbsp;&nbsp;';
                     } 
                    ?>
                 </td>
                 <td class="dataTableContent" align="center"><?php echo $products_with_files['files_download']; ?>&nbsp;</td>
               </tr>
               <?php
              }
             ?>
           </table></td>
         </tr>
         <tr>
           <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
             <tr>
               <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
             </tr>
             <tr>
                <td class="smallText" valign="top"><?php echo $products_with_files_split->display_count($products_with_files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_LINES); ?></td>
                <td class="smallText" align="right"><?php echo $products_with_files_split->display_links($products_with_files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page); ?></td>
             </tr>
           </table></td>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>