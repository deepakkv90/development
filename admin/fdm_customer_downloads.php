<?php
/*
  $Id: fdm_customer_downloads.php,v 1.1.1.1 2006/10/13 23:41:11 jagdish Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
$cID =(isset($_GET['cID']) ? $_GET['cID'] : '');
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
<?php
require(DIR_WS_INCLUDES . 'header.php');
$sql = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $cID . "'");
$row = tep_db_fetch_array($sql);
$fname=$row['customers_firstname'];
$lname=$row['customers_lastname'];
?>
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
      <td width="100%" valign="top">
        <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
          <tr>
            <td>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr> 
          <tr>
            <td class="dataTableContent"><?php echo TEXT_CUSTOMER_NAME; ?> <b> <?php echo $fname;?>&nbsp;<?php echo $lname; ?></b> &nbsp;&nbsp;<u><a href="<?php echo tep_href_link(FILENAME_CUSTOMERS, 'search=' .$cID); ?>"><?php echo (TEXT_GO_CUSTOMER_NAME); ?></a></u></td>
          </tr>
          <?php
          if (MAIN_TABLE_BORDER == 'yes') {
            $heading_text = $heading_text_box ;
            table_image_border_top(false, false, $heading_text);
          }      
          $sql_query =("select distinct lf.files_id, lf.files_name, lfde.files_descriptive_name, fi.icon_small, lf.files_download, lf.file_availability from " . TABLE_LIBRARY_FILES_DOWNLOAD . " lfd, " . TABLE_LIBRARY_FILES . " lf, " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfde, " . TABLE_FILE_ICONS . " fi where lf.files_id = lfd.files_id and lf.files_id = lfde.files_id and fi.icon_id = lf.files_icon and customers_id = '" . $cID . "' and lfde.language_id = '" . $_SESSION['languages_id'] . "'");
          $file_query=tep_db_query($sql_query);
          if (tep_db_num_rows($file_query) > 0) {
            $_SESSION['fdm_show_log'] = 1;
            ?>
            <tr>
              <td class="headingtitle"><?php echo TEXT_DOWNLOADED; ?></td>
            </tr>
            <tr>
              <td><?php include(DIR_WS_MODULES . FILENAME_DOWNLOADS_FILES_LISTING); ?></td>
            </tr>
            <?php
            unset($_SESSION['fdm_show_log']);
          }
          ?>   
         <tr>
            <td> <?php   echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>  
          <?php    
          $_SESSION['fdm_show_log'] = 0;
          $sql =("select distinct files_id from " . TABLE_LIBRARY_FILES_DOWNLOAD . " where customers_id = '" . $cID . "'");
          $sub_query = tep_db_query($sql);
          $sub = '\'\', ';
          while ($sub_array = tep_db_fetch_array($sub_query)) {
            $sub .= $sub_array['files_id'] . ', ';
          }
          $sub = substr($sub, 0, strlen($sub) - 2);
          $already_downloaded = explode(", ", $sub);
          $sql_file_query =("SELECT  distinct lf.files_id, lf.files_name, lfde.files_descriptive_name, lf.files_download, fi.icon_small FROM  " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_LIBRARY_PRODUCTS . " lp, " . TABLE_LIBRARY_FILES . " lf, " . TABLE_FILE_ICONS . " fi, " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfde WHERE  op.orders_id = o.orders_id AND  o.customers_id = '" . $cID . "' AND op.products_id = lp.products_id AND lp.purchase_required='1' AND lf.files_id=lp.library_id AND fi.icon_id = lf.files_icon AND lf.files_id=lfde.files_id AND lfde.language_id ='" . $_SESSION['languages_id'] . "'");
          $file_query=tep_db_query ($sql_file_query);
          if (tep_db_num_rows($file_query) > 0) {
            ?>
            <tr>
              <td class="headingtitle"><?php echo TEXT_PURCHASED; ?></td>
            </tr>
            <tr>
              <td><?php include(DIR_WS_MODULES . FILENAME_DOWNLOADS_FILES_LISTING); ?></td>
            </tr>
            <?php
            unset($_SESSION['fdm_show_log']);
          }
          ?>    
  </table>
  </div> 
  <!-- footer_eof //-->
  <br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>