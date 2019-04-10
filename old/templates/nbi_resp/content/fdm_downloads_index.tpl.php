<?php
/*
  $Id: fdm_downloads_index.tpl.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- fdm_downloads_index.tpl.php -->
<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fdmdownloadsindex', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE;?></td>
        </tr>
      </table>
    </td>
  </tr>
  <?php
  if (MAIN_TABLE_BORDER == 'yes') {
    $heading_text = $heading_text_box ;
    table_image_border_top(false, false, $heading_text);
  }
  // check for downloads
  $current_customer_id = isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : '';
  $sql=("SELECT DISTINCT files_id 
           from " . TABLE_LIBRARY_FILES_DOWNLOAD . " 
         WHERE customers_id = '" . $current_customer_id . "'"); 

  $sql_query = tep_db_query($sql);
  $test = tep_db_num_rows($sql_query);

  //check for files attached to products
  $file_query_purchase= "SELECT DISTINCT lf.files_id, lf.files_name, lfde.files_descriptive_name, lf.files_download, fi.icon_small 
                           from  " . TABLE_ORDERS . " o, 
                                 " . TABLE_ORDERS_PRODUCTS . " op, 
                                 " . TABLE_LIBRARY_PRODUCTS . " lp, 
                                 " . TABLE_LIBRARY_FILES . " lf, 
                                 " . TABLE_FILE_ICONS . " fi, 
                                 " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfde 
                         WHERE op.orders_id = o.orders_id 
                           and o.customers_id = '" . $current_customer_id . "' 
                           and lfde.language_id = '" . $languages_id . "' 
                           and op.products_id = lp.products_id 
                           and lp.purchase_required = '1' 
                           and lp.download_show = '1'
                           and lf.files_id = lp.library_id
                           and lf.files_status = '1'
                           and lf.file_availability = '2'
                           and lf.files_id = lfde.files_id  
                           and fi.icon_id = lf.files_icon";

  $file_download=tep_db_query($file_query_purchase);
  $test2 = tep_db_num_rows($file_download);
  if (($test == 0) && ($test2 == 0)) {
    ?>
    <tr>
      <td align="center" class="smallText"><?php echo TEXT_NO_DOWNLOADS; ?></td>
    </tr>
    <?php 
  } else {
    $sub_query = tep_db_query("SELECT DISTINCT files_id 
                                                from " . TABLE_LIBRARY_FILES_DOWNLOAD . " 
                                              WHERE customers_id = '" . $current_customer_id . "'");
    $sub = '\'\', ';
    while ($sub_array = tep_db_fetch_array($sub_query)) {
      $sub .= $sub_array['files_id'] . ', ';
    }
    $sub = substr($sub, 0, strlen($sub) - 2);
    $file_query_purchase= "SELECT DISTINCT lf.files_id, lf.files_name, lfde.files_descriptive_name, lf.files_download, fi.icon_small, o.customers_id 
                                        from  " . TABLE_ORDERS . " o, 
                                                " . TABLE_ORDERS_PRODUCTS . " op, 
                                                " . TABLE_LIBRARY_PRODUCTS . " lp, 
                                                " . TABLE_LIBRARY_FILES . " lf,
                                                " . TABLE_FILE_ICONS . " fi, 
                                                " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfde 
                                     WHERE op.orders_id = o.orders_id 
                                       and o.customers_id = '" . $current_customer_id . "' 
                                       and lfde.language_id = '" . $languages_id . "' 
                                       and op.products_id = lp.products_id 
                                       and lp.purchase_required = '1' 
                                       and lp.download_show = '1'
                                       and lf.files_id = lp.library_id
                                       and lf.files_status = '1'
                                       and lf.file_availability = '2'
                                       and lf.files_id=lfde.files_id 
                                       and fi.icon_id = lf.files_icon 
                                       and lf.files_id NOT IN (" . $sub . ")";

    $file_download=tep_db_query($file_query_purchase);
    if (tep_db_num_rows($file_download) > 0) {
      ?>
      <tr>
        <td class="category_desc"><?php echo TEXT_PURCHASED; ?></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_DOWNLOADS_FILES_LISTING); ?></td>
      </tr>
      <?php
    }
    $sql_query=("SELECT DISTINCT lf.files_id, lf.files_name, lfde.files_descriptive_name, fi.icon_small, lf.files_download, lf.file_availability 
                   from " . TABLE_LIBRARY_FILES_DOWNLOAD . " lfd, 
                        " . TABLE_LIBRARY_FILES . " lf, 
                        " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfde, 
                        " . TABLE_FILE_ICONS . " fi 
                 WHERE lf.files_id = lfd.files_id 
                   and lf.files_id = lfde.files_id 
                   and fi.icon_id = lf.files_icon 
                   and customers_id = '" . $current_customer_id . "' 
                   and lfde.language_id = '" . $languages_id . "'");

    $file_download = tep_db_query($sql_query);
    if (tep_db_num_rows($file_download) > 0) {
      ?>
      <tr>
        <td class="category_desc"><b><?php echo TEXT_DOWNLOADED; ?></b></td>
      </tr>
      <tr>
        <td><?php include(DIR_WS_MODULES . FILENAME_DOWNLOADS_FILES_LISTING);?></td>
      </tr>
      <?php
    }
  }
  ?>
  <?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
  ?>
</table>
<?php
// RCI code start
echo $cre_RCI->get('fdmdownloadsindex', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?><!-- fdm_downloads_index.tpl.php-eof //-->