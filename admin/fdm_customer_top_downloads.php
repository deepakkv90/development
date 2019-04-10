<?php
/*
  $Id: fdm_customer_top_downloads.php,v 1.0.0.0 2007/01/03 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  $files_list[] = array('id' => '0', 'text' => TEXT_ALL_FILES);
  $files_query = tep_db_query("select files_id, files_name from " . TABLE_LIBRARY_FILES . " order by files_name");
  while ($files_array = tep_db_fetch_array($files_query)) {
    $files_list[] = array('id' => $files_array['files_id'],
                          'text' => $files_array['files_name']);
  }
  $files = isset($_GET['files']) ? $_GET['files'] : '0';
  $files_id = isset($_GET['files_id']) ? $_GET['files_id'] : '';
  $cID = isset($_GET['cID']) ? $_GET['cID'] : '';
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
<?php
    if (!tep_not_null($files_id) || !tep_not_null($cID)) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
<?php
          echo tep_draw_form('files', FILENAME_CUSTOMER_TOP_DOWNLOADS, '', 'get');
          echo tep_draw_hidden_field(tep_session_name(), tep_session_id());
?>
            <td align="right" class="main"><?php echo HEADING_TITLE_FILES . tep_draw_pull_down_menu('files', $files_list, '', 'onChange="this.form.submit();"');?></td>
            </form>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER_DOWNLOAD; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILES; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILES_DESC_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_VIEWED; ?>&nbsp;</td>
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
              $files_query_raw = "SELECT fl.files_id, fl.files_name, c.customers_firstname, c.customers_lastname, c.customers_email_address, flfd.customers_id, fld.files_descriptive_name, COUNT(*) AS nbr FROM " . TABLE_LIBRARY_FILES . " fl, " .TABLE_LIBRARY_FILES_DESCRIPTION." fld, " . TABLE_LIBRARY_FILES_DOWNLOAD . " flfd, " . TABLE_CUSTOMERS . " c WHERE flfd.customers_id = c.customers_id AND fl.files_id = fld.files_id AND fld.language_id = '".$languages_id."' AND flfd.files_id = fl.files_id " . ($files == '0' ? "" : "AND fl.files_id = '" . $files . "' ") . "GROUP BY flfd.customers_id, flfd.files_id ORDER BY nbr DESC";
              $files_split = new splitPageResults($page, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $files_query_raw, $files_query_numrows);
              $files_query = tep_db_query($files_query_raw);
              while ($files = tep_db_fetch_array($files_query)) {
                $rows++;
                $icount++;
                if ($rows%2 == 0) {
                  $s_class = 'bgcolor = "#FFFFFF"';
                } else {
                  $s_class = 'class = "dataTableRow"'; 
                }
                ?>
                <!-- tr <?php echo $s_class;?> onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" -->
                <tr <?php echo $s_class;?>>
                  <td class="dataTableContent"><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'cID=' . $files['customers_id'] . '&action=edit'); ?>"><u><?php echo $files['customers_firstname'] . ' ' . $files['customers_lastname']; ?></u></a></td>
                  <td class="dataTableContent"><a href="<?php echo tep_href_link(FILENAME_CUSTOMER_DOWNLOADS, 'cID=' . $files['customers_id']); ?>"><u><?php echo TEXT_CUSTOMER_DOWNLOADS_REPORT; ?></u></a></td>
                  <td class="dataTableContent"><?php echo $files['files_name']; ?></td>
                  <td class="dataTableContent" ><?php echo $files['files_descriptive_name']; ?>&nbsp;</td>
                  <td class="dataTableContent" align="center"><a href="<?php echo tep_href_link(FILENAME_CUSTOMER_TOP_DOWNLOADS, tep_get_all_get_params(array('cID', 'files_id')) . 'cID=' . $files['customers_id'] . '&files_id=' . $files['files_id']); ?>"><u><?php echo $files['nbr']; ?></u></a></td>
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
                <td class="smallText" valign="top"><?php echo $files_split->display_count($files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_FILES); ?></td>
                <td class="smallText" align="right"><?php echo $files_split->display_links($files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_IP; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td align="right" class="main"><a href="<?php echo tep_href_link(FILENAME_CUSTOMER_TOP_DOWNLOADS, tep_get_all_get_params(array('cID', 'files_id', 'page'))); ?>"><?php echo tep_image_button('button_back.gif', IMAGE_BACK); ?></a></td>
            </form>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILES; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILES_DESC_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DOWNLOAD_IP; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DOWNLOAD_DATE; ?></td>
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
        $files_query_raw = "SELECT ffd.files_id, fl.files_name, fld.files_descriptive_name, ffd.ip_addr, ffd.download_time FROM " . TABLE_LIBRARY_FILES . " fl, " . TABLE_LIBRARY_FILES_DESCRIPTION . " fld, " . TABLE_LIBRARY_FILES_DOWNLOAD . " ffd WHERE fl.files_id = ffd.files_id AND ffd.files_id = fld.files_id AND fld.language_id = '" . $languages_id . "' AND ffd.files_id = '" . $files_id . "' AND ffd.customers_id = '" . $cID . "' ORDER BY ffd.download_time";
        $files_split = new splitPageResults($page, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $files_query_raw, $files_query_numrows);
        $files_query = tep_db_query($files_query_raw);
        while ($files = tep_db_fetch_array($files_query)) {
          $rows++;
          $icount++;
          if ($rows%2 == 0) {
            $s_class = 'bgcolor = "#FFFFFF"';
          } else {
            $s_class = 'class = "dataTableRow"'; 
          }
?>
              <tr <?php echo $s_class;?>>
                <td class="dataTableContent"><?php echo $files['files_name']; ?></td>
                <td class="dataTableContent" ><?php echo $files['files_descriptive_name']; ?>&nbsp;</td>
                <td class="dataTableContent" align="center"><?php echo $files['ip_addr']; ?></td>
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
                <td class="smallText" valign="top"><?php echo $files_split->display_count($files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_FILES); ?></td>
                <td class="smallText" align="right"><?php echo $files_split->display_links($files_query_numrows, FDMS_MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page); ?></td>
              </tr>
            </table></td>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>