<?php
/*
  $Id: fdm_folder_files.tpl.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- fdm_folder_files.tpl.php -->
<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fdmfolderfiles', 'top');
// RCI code eof
echo tep_draw_form('folder_files', FILENAME_FOLDER_FILES . '?' . tep_get_all_get_params(array('action')) ); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="1" cellpadding="3">
        <tr>
          <td class="pageHeading" colspan="3">
            <?php
            $folder_query = tep_db_query("SELECT lfd.folders_name, lfd.folders_description, lfd.folders_heading_title, lf.folders_image 
                                            from " . TABLE_LIBRARY_FOLDERS . " lf, 
                                                 " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " lfd 
                                          WHERE lf.folders_id = '" . (int)$current_folder_id . "' 
                                            and lfd.folders_id = '" . (int)$current_folder_id . "' 
                                            and lfd.language_id = '" . $_SESSION['languages_id'] . "'");

            $folder = tep_db_fetch_array($folder_query);
            if (tep_not_null($folder['folders_heading_title'])) {
              ?>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo $folder['folders_heading_title']; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . $folder['folders_image'], HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
              <?php
            } else {
              ?>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo $folder['folders_name']; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . $folder['folders_image'], HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table>
              <?php
            }
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php 
        if (tep_not_null($folder['folders_description'])) { 
          ?>
          <tr>
            <td align="left" class="category_desc" colspan="3"><?php echo $folder['folders_description']; ?><br></td>
          </tr>
          <?php  
        } 
        ?>
        <tr>
          <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php
        $cnt = 0;
        $sql =("select folders_id from " .TABLE_LIBRARY_FOLDERS. " where folders_parent_id = '" . (int)$current_folder_id . "' ");
        $sub_folder_query = tep_db_query($sql);
        $num_rows = tep_db_num_rows ( $sub_folder_query );
        if ( $num_rows > '0' ) {
          while ($sub_folder_result =tep_db_fetch_array($sub_folder_query) ) {
            $folder_sub_query = tep_db_query("SELECT lfd.folders_name, lfd.folders_description, lfd.folders_heading_title,lf.folders_image 
                                                from " . TABLE_LIBRARY_FOLDERS_DESCRIPTION . " lfd ,
                                                     " . TABLE_LIBRARY_FOLDERS . " lf 
                                              WHERE lfd.folders_id = '" . (int)$sub_folder_result['folders_id'] . "' 
                                                and lfd.folders_id = lf.folders_id 
                                                and language_id = '" . $_SESSION['languages_id'] . "'");

            if (tep_db_num_rows($folder_sub_query) > '0') {
              $folder_result =tep_db_fetch_array($folder_sub_query);
              if ($cnt % 3 == "0") {
                echo '<tr><td>' . tep_draw_separator('pixel_trans.gif', '5', '1') . '</td></tr><tr>';
              }
              echo '<td valign="top" class="main" align="center">';
              $folder_id = (isset($_GET['fPath']) ? (int)$_GET['fPath'] : '');   
              //$fPath =  $folder_id . '_' .(int)$sub_folder_result['folders_id'];
              $fPath =  (($folder_id != '') ? $folder_id . '_' : '' ) . (int)$sub_folder_result['folders_id'];
              $link = tep_href_link(FILENAME_FOLDER_FILES, 'fPath='.$fPath ); 
              ?>
              <a href="<?php echo $link; ?>"><?php echo tep_image(DIR_WS_IMAGES .$folder_result['folders_image'] ); ?></a> <br><a href="<?php echo $link; ?>"> <?php echo $folder_result['folders_name'];  ?></a></td>
              <?php
              $cnt++;
              if ($cnt % 3 == "0")  { 
                echo '</tr>';
              }  
            } 
          }  
        }
        ?>
      </table>
    </td>
  </tr>
  <?php
  $sql_file = ("SELECT lf2f.files_id
                  from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " lf2f,
                       " . TABLE_LIBRARY_FILES . " lf
                WHERE lf2f.folders_id = '" . (int)$current_folder_id . "'
                  and lf.files_id = lf2f.files_id
                  and lf.files_status = '1'
                  and lf.files_general_display = '1'
                ORDER BY files_id");

  $file_query = tep_db_query($sql_file);
  if (tep_db_num_rows($file_query) > 0) { 
    $listing_sql = "SELECT lf.files_id
                    from " . TABLE_LIBRARY_FILES_TO_FOLDERS . " lf2f,
                         " . TABLE_LIBRARY_FILES . " lf
                  WHERE lf2f.files_id = lf.files_id
                    and lf2f.folders_id = '" . (int)$current_folder_id . "'
                    and lf.files_status = '1'
                    and lf.files_general_display = '1'
                  ORDER BY lf2f.files_id";
    if (MAIN_TABLE_BORDER == 'yes') {
      $heading_text = $heading_text_box ;
      table_image_border_top(false, false, $heading_text);
    }
    ?>
    <tr>
      <td>
        <?php 
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
                <td class="main" align="right"><?php //echo tep_draw_hidden_field('action', 'login') . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); 
                
                echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '">' . tep_template_image_button('button_login.gif', IMAGE_BUTTON_LOGIN) . '</a>';
                
                ?></td>
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
</form>
<?php
// RCI code start
echo $cre_RCI->get('fdmfolderfiles', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?><!-- fdm_folder_files.tpl.php-eof //-->
