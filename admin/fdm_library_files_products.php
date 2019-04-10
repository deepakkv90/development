<?php
/*
  $Id: fdm_library_product.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
  $is_62 = (defined('PROJECT_VERSION') && ereg('6.2', PROJECT_VERSION)) ? true : false;
  $parm_exclude = array('fPath','fldID','fID');
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $fID = (isset($_GET['fID']) ? $_GET['fID'] : '');
  if ( $fID == '' ) {
    tep_redirect(tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params()));
  } else {
    $file_query = tep_db_query("select f.files_id, f.files_name, f.require_products_id, fd.files_descriptive_name from " . TABLE_LIBRARY_FILES . " f, " . TABLE_LIBRARY_FILES_DESCRIPTION . " fd where f.files_id = '" . $fID . "' and f.files_id = fd.files_id and fd.language_id = '" . (int)$_SESSION['languages_id'] . "'");
    $file = tep_db_fetch_array($file_query);
    $fInfo = new objectInfo($file);
  }
  if (!isset($_SESSION['expanded_folders']) ) {
    $expanded_folders = array();
    $expanded_folders['0'] = 1;
  }
  $expanded_folders = $_SESSION['expanded_folders'];
  switch ( $action ) {
    case 'fld_expand' :
        $expanded_folders[$_GET['fldID']] = 1;
        $action = 'file_list';
        break;

    case 'fld_contract' :
      $expanded_folders[$_GET['fldID']] = 0;
      $action = 'file_list';
      break;

    case 'update_products' :
      $files_id = $_POST['files_id'];
      $products_array = $_POST['products'];
      if (is_array($products_array)) {
	      foreach ($products_array as $value) {
	      	tep_db_query("delete from " . TABLE_LIBRARY_PRODUCTS . " where library_id  = '" . $files_id . "' and products_id = '" . $value . "'");
	      }
      }
      if ( isset($_POST['prod'])  ) {
        foreach ( $_POST['prod'] as $indx => $prod_id ) {
          tep_db_query ("insert into " . TABLE_LIBRARY_PRODUCTS . " set products_id='" . $prod_id . "', library_type='f', library_id='" . $files_id . "', purchase_required = '" . $_POST['pur_req'][$prod_id] . "', download_show = '1'");
        }
      }
//      print_r($_POST['pur_req']);die();
      if (isset($_POST['pur_req'])) {
      	foreach ($_POST['pur_req'] as $prod_id => $value) {
      		if (tep_db_num_rows(tep_db_query("select products_id from " . TABLE_LIBRARY_PRODUCTS . " where products_id = '" . $prod_id . "' and library_type='f' and library_id = '" . $files_id . "'")) == 0) {
      			tep_db_query("insert into " . TABLE_LIBRARY_PRODUCTS . " set products_id='" . $prod_id . "', library_type='f', library_id='" . $files_id . "', purchase_required = '" . $_POST['pur_req'][$prod_id] . "', download_show = '0'");
      		}
      	}
      }
      tep_redirect(tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params(array('action'))));
      break;
  }

  $_SESSION['expanded_folders'] = $expanded_folders;
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
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
    <td width="100%" valign="top">
	     <table border="0" cellspacing="0" cellpadding="2" width="600px">
        <tr>
          <td width="100%">
    	       <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              </tr>
            </table>
	         </td>
        </tr>
        <tr>
          <td width="100%" align="center"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <!-- td><?php echo tep_info_image($fInfo->products_image, $fInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT); ?></td -->            
              <td align="left"><b><?php echo $fInfo->files_descriptive_name; ?></b></td>
			        </tr>
          </table></td>
        </tr>
        <tr>
          <td width="100%">
            <?php
            $selected_folders = array();
            $selected_files = array();
            $selected_query = tep_db_query("select library_type, products_id, login_required, purchase_required, download_show from " . TABLE_LIBRARY_PRODUCTS . " where library_id = '" . $fInfo->files_id . "' order by library_id");
            while ($selected = tep_db_fetch_array($selected_query)) {
              if ( $selected['library_type'] == 'c' ) {
                $selected_folders[$selected['products_id']] = $selected['products_id'];      
              } elseif ( $selected['library_type'] == 'f' ) {
              	if ($selected['download_show']) {
              		$selected_files[$selected['products_id']] = $selected['products_id'];
              	}
                $login_require[$selected['products_id']] = $selected['login_required'];
                $purchase_require[$selected['products_id']] = $selected['purchase_required'];
              }
            }
            echo tep_draw_form('file_product', FILENAME_LIBRARY_FILES_PRODUCTS, tep_get_all_get_params() . 'action=update_products') . tep_draw_hidden_field('files_id', $fInfo->files_id) . "\n";
            ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center" width="10%" valign="bottom"><?php echo TABLE_HEADING_SELECT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent" align="center" width="12%" valign="bottom"><?php echo TABLE_REQUIRE; ?></td>
  		                <td class="dataTableHeadingContent" align="left" width="78%" valign="bottom"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
			              </tr>
                  <?php
                  echo products_table_build(0, 0, $expanded_folders, $selected_folders, $selected_files, $login_require, $purchase_require); 
                  ?>
                </table></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td align="center"><table border="0" cellspacing="1" cellpadding="1" width="100%">
                  <tr>
                    <?php
                    echo '<td align="right">' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;&nbsp;</td>';
                    echo '<td valign="top" algn="left"><a href="' . tep_href_link(FILENAME_LIBRARY_FILES, tep_get_all_get_params()) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>';
                    ?>
                  </tr>
                </table></td>
              </tr>
            </table></form>
          </td>
        </tr>
      </table>
    </td>
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
