<?php
/*
  $Id: fdm_download_log.php,v 1.0.0.0 2007/01/03 13:41:11 avicrw Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
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
                <td class="dataTableHeadingContent" width="30%"><?php echo TABLE_HEADING_FILE_DESCRIPTIVE_NAME; ?></td>
                <td class="dataTableHeadingContent" width="20%"><?php echo TABLE_HEADING_FILE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right" width="10%"><?php echo TEXT_DISPLAY_FILE_SIZE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center" width="5%"><?php echo TABLE_HEADING_IP; ?></td>
                <td class="dataTableHeadingContent" align="center" width="10%"><?php echo TABLE_HEADING_CUSTOMER_ID; ?></td>
                <td class="dataTableHeadingContent" align="center" width="5%"><?php echo TABLE_HEADING_PAGE; ?></td>
                <td class="dataTableHeadingContent" align="center" width="20%"><?php echo TABLE_HEADING_DATE_TIME; ?></td>
              </tr>
              <?php
              if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * FDMS_MAX_DISPLAY_SEARCH_RESULTS - FDMS_MAX_DISPLAY_SEARCH_RESULTS;
              $rows = 0;
              $files_query_raw = "SELECT fl.files_name, fld.files_descriptive_name, fldn.download_time, fldn.page , fldn.ip_addr, fldn.file_size, fldn.customers_id 
                                            from " . TABLE_LIBRARY_FILES." fl, 
                                                   " . TABLE_LIBRARY_FILES_DESCRIPTION . " fld, 
                                                   " . TABLE_LIBRARY_FILES_DOWNLOAD . " fldn 
                                          WHERE fl.files_id = fld.files_id 
                                            and fld.files_id = fldn.files_id 
                                            and fld.language_id = '".$languages_id."' 
                                          ORDER BY fldn.download_time DESC";
              $icount = 0;
              $files_split = new splitPageResults($_GET['page'], FDMS_MAX_DISPLAY_SEARCH_RESULTS, $files_query_raw, $files_query_numrows);
              $files_query = tep_db_query($files_query_raw);
              while ($files = tep_db_fetch_array($files_query)) {
                $rows++;
                if ($rows%2 == 0) {
                  $s_class = 'bgcolor = "#FFFFFF"';
                } else {
                  $s_class = 'class = "dataTableRow"'; 
                }
                $icount++;
                ?>
                <!-- tr <?php echo $s_class;?> onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" -->
                <tr <?php echo $s_class;?>>
                  <td class="dataTableContent"><?php echo $files['files_descriptive_name']; ?>.</td>
                  <td class="dataTableContent"><?php echo  $files['files_name']; ?></td>
                  <td class="dataTableContent" align="right"><?php echo ($files['file_size'] != 0) ? "(" . cre_resize_bytes($files['file_size']) . ")" : ''; ?>&nbsp;</td>
                  <td class="dataTableContent" align="center"><?php echo $files['ip_addr']; ?></td>
                  <td class="dataTableContent" align="center"><?php echo ($files['customers_id'] != 0) ? $files['customers_id'] : TEXT_DISPLAY_GUEST; ?></td>
                  <td class="dataTableContent" align="center"><?php echo $files['page']; ?></td>
                  <td class="dataTableContent" align="center"><?php echo $files['download_time']; ?></td>
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
                <td class="smallText" valign="top"><?php echo $files_split->display_count($files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_FILES);
                ?></td>
                <td class="smallText" align="right"><?php echo $files_split->display_links($files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
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