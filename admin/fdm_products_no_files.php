<?php
/*
  $Id: fdm_products_no_files.php,v 1.0.0.0 2007/01/03 13:41:11 datazen Exp $

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
                <td class="dataTableHeadingContent" align = "left" width = "5%"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent" align = "right" width = "10%"><?php echo TABLE_HEADING_PRODUCT_ID; ?>&nbsp;&nbsp;</td> 
                <td class="dataTableHeadingContent" align = "left" width = "85%"><?php echo TABLE_HEADING_PRODUCT_NAME; ?></td>
              </tr>
              <?php
              if (isset($_GET['page']) && ($_GET['page'] > 1)) { 
                $rows = (int)$_GET['page'] * FDMS_MAX_DISPLAY_SEARCH_RESULTS - FDMS_MAX_DISPLAY_SEARCH_RESULTS;   
                $page = (int)$_GET['page'];
              } else {
                $page = 1;
              }
              $rows = 0;
              $products_no_files_query_raw = "SELECT p.products_id, pd.products_id, pd.products_name 
                                                               from " . TABLE_PRODUCTS_DESCRIPTION . " pd, 
                                                                      " . TABLE_PRODUCTS . " p 
                                                             LEFT JOIN " . TABLE_LIBRARY_PRODUCTS . " lp 
                                                               on (p.products_id = lp.products_id) 
                                                             WHERE p.products_id = pd.products_id 
                                                               and lp.products_id is null 
                                                               and pd.language_id = '" . $languages_id . "' 
                                                             ORDER BY p.products_id ASC";

              $products_no_files_split = new splitPageResults($page, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $products_no_files_query_raw, $products_no_files_query_numrows);
              $products_no_files_query = tep_db_query($products_no_files_query_raw);
              while ($products_no_files = tep_db_fetch_array($products_no_files_query)) {
                $rows++;
                if (strlen($rows) < 2) {
                  $rows = '0' . $rows;
                }
                if ($rows%2 == 0) {
                  $s_class = 'class="dataTableRow"';     
                } else {
                  $s_class = 'bgcolor = "#FFFFFF"';
                }
                ?>
                <!-- tr <?php echo $s_class;?> onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" -->
                <tr <?php echo $s_class;?>>
                  <td class="dataTableContent"><?php echo $rows; ?>.</td>
                  <td class="dataTableContent" align="right"><?php echo (int)$products_no_files['products_id']; ?>&nbsp;&nbsp;</td>
                  <td class="dataTableContent"><?php echo $products_no_files['products_name']; ?></td>             
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
                <td class="smallText" valign="top"><?php echo $products_no_files_split->display_count($products_no_files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_no_files_split->display_links($products_no_files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page); ?></td>
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