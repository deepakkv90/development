<?php
/*
  $Id: move_vendor_prods.php,v 1.1 2008/06/22 22:50:52 datazen Exp $

  Modified for MVS V1.0 2006/03/25 JCK/CWG
  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
if ($action == 'update') {
  $count_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " where vendors_id = '" . (int)$delete_vendors_id . "'");
  while ($count_products = tep_db_fetch_array($count_products_query)) {
    $num_products = $count_products['total'];
  }
  $update_query  = "update " . TABLE_PRODUCTS . " SET vendors_id = '" . $new_vendors_id . "' where vendors_id = '" . $delete_vendors_id . "';";
  $update_result = tep_db_query($update_query);
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();"> 
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">  
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
      <!-- left_navigation //-->
      <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
      <!-- left_navigation_eof //-->
    </table></td>
    <!-- body_text //-->
    <td width="100%" valign="top"><table align="center" border="0" width="98%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <?php 
      if ($action == 'update') {
        $vendor_name_deleted = tep_db_query("select vendors_name from " . TABLE_VENDORS . " where vendors_id = '" . $delete_vendors_id . "'");
        while ($vendor_deleted = tep_db_fetch_array($vendor_name_deleted)) {
          $deleted_vendor = $vendor_deleted['vendors_name'];
        }
        $vendor_name_moved = tep_db_query("select vendors_name from " . TABLE_VENDORS . " where vendors_id = '" . $new_vendors_id . "'");
        while ($vendor_moved = tep_db_fetch_array($vendor_name_moved)) {
          $moved_vendor = $vendor_moved['vendors_name'];
        }
        if ($update_result) {
          ?>
          <tr>
            <td class="messageStackSuccess" align="left">
              <?php
              echo '<br><b>' . $num_products . '</b> products were moved from <b>' . $deleted_vendor . '</b> to <b>' . $moved_vendor . '</b>.<br> You can Go <a href="' . tep_href_link(FILENAME_MOVE_VENDORS) . '"><b>Back and start</b></a> again OR Go <a href="' . tep_href_link(FILENAME_VENDORS) . '"><b>Back To Vendors List</b></a>';
              ?>
              </td>
            </tr>
            <?php
        } else {  
          ?>
          <tr>
            <td class="messageStackError" align="left">
              <?php  
              echo '<br><b>NO</b> products were moved from <b>' . $deleted_vendor . '</b> to <b>' . $moved_vendor . '</b>.<br> You should Go <a href="' . tep_href_link(FILENAME_MOVE_VENDORS) . '"><b>Back and start</b></a> over OR Go <a href="' . tep_href_link(FILENAME_VENDORS) . '"><b>Back To Vendors List</b></a>'; ?>
            </td>
          </tr>
          <?php  
        }
      } elseif ($action == '') { 
        ?>
        <tr>
          <td class="main" align="left"><?php echo '<a href="' . tep_href_link(FILENAME_VENDORS) . '"><b>Go Back To Vendors List</a>';?></td>
        </tr>
        <tr>
          <td class="main" align="left"><?php echo 'Select the vendors you plan to work with.'; ?></td>
        </tr>
        <tr bgcolor="#FF0000">
          <td class="main" align="left"><?php echo '<b>This action is not easily reversible, and clicking the update button will perform this action immediately.</b>'; ?></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td valign="top">
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <?php
                    echo tep_draw_form('move_vendor_form', FILENAME_MOVE_VENDORS, tep_get_all_get_params(array('action')) . ('action=update'), 'post');
                    $vendors_array = array(array('id' => '1', 'text' => 'NONE'));
                    $vendors_query = tep_db_query("select vendors_id, vendors_name from " . TABLE_VENDORS . " order by vendors_name");
                    while ($vendors = tep_db_fetch_array($vendors_query)) {
                      $vendors_array[] = array('id' => $vendors['vendors_id'],
                                               'text' => $vendors['vendors_name']);
                    }
                    ?>
                    <td class="main" align="left"><?php echo TEXT_VENDOR_CHOOSE_MOVE . ' -->  '; ?><?php echo tep_draw_form('vendors_report', FILENAME_PRODS_VENDORS) . tep_draw_pull_down_menu('delete_vendors_id', $vendors_array);?></td>
                  </tr>
                  <tr>
                    <td class="main" align="left"><br><?php echo TEXT_VENDOR_CHOOSE_MOVE_TO . ' -->  '; ?><?php echo tep_draw_form('vendors_report', FILENAME_PRODS_VENDORS) . tep_draw_pull_down_menu('new_vendors_id', $vendors_array);?></td>
                  </tr>
                  <tr>
                   <td align="right">
                     <?php 
                     echo '<a href="' . tep_href_link(FILENAME_MOVE_VENDORS, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>
                   </td>
                  </tr>
                </table>
              </td>
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