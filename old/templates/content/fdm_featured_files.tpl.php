<?php
/*
  $Id: fdm_featured_files.tpl.php,v 1.0.0.0 2006/10/12 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- fdm_featured_files.tpl.php -->
<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fdmfeaturedfiles', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
        <td class="pageHeading" align="right"><?php // echo tep_image(DIR_WS_IMAGES . 'table_background_products_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
<?php
  $listing_sql = "select lf.files_id, lfd.files_descriptive_name, fi.icon_large as file_icon from " . TABLE_LIBRARY_FILES . " lf left join " . TABLE_FILE_ICONS . " fi on fi.icon_id = lf.files_icon, " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, " . TABLE_FEATURED_FILES . " ff where lf.files_status = '1' and ff.status = '1' and lf.files_id = ff.files_id and lfd.files_id = ff.files_id and lfd.language_id = '" . $languages_id . "' order by lfd.files_descriptive_name";

  $file_query = tep_db_query($listing_sql);
  if (tep_db_num_rows($file_query) > 0) { 
    if (MAIN_TABLE_BORDER == 'yes') {
      $heading_text = HEADING_TITLE ;
      table_image_border_top(false, false, $heading_text);
    }
    ?>
    <tr>
      <td>
<?php 
//        include(DIR_WS_MODULES . FILENAME_FILE_DETAIL_LISTING); 
        if (LIBRARY_FILE_FOLDERS_LISTING  == 'detail') {
          include(DIR_WS_MODULES . FILENAME_FOLDER_FILES_LISTING); 
        } else {
          include(DIR_WS_MODULES . FILENAME_FOLDER_FILES_LISTING_TABLE);
        }
?>
      </td>
    </tr>
    <?php
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
    ?>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td class="main" align="left"><?php echo '<a href="javascript:history.back();">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
              <?php
              if (!isset($_SESSION['customer_id'])) {
                ?>
                <td class="main" align="right"><?php echo tep_draw_hidden_field('action', 'login') . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); ?></td>
                <?php
              }
              ?>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
  }
  ?>
</table>
<?php
// RCI code start
echo $cre_RCI->get('fdmfeaturedfiles', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?><!-- fdm_featured_files.tpl.php-eof //-->