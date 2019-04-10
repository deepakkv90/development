<?php
/*
  $Id: fdm_folder_files_listing_table.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require_once(DIR_WS_LANGUAGES . $language . '/modules/' . FILENAME_FOLDER_FILES_LISTING_TABLE);
  require_once(DIR_WS_FUNCTIONS . FILENAME_FDM_FUNCTIONS);
  ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="3">
    <tr>
      <?php  
      if (FILE_LIST_ACTION == 'Yes') { 
        ?>
        <td align="center" bgcolor="#CCCCCC" class="fileListing_left_header"><?php echo TABLE_HEADING_DOWNLOAD; ?></td>
        <?php
      }
      if (FILE_LIST_FILE_NAME == 'Yes') { 
        ?>
        <td bgcolor="#CCCCCC" class="fileListing_left_header"><?php echo TABLE_HEADING_FILE_NAME; ?></td>
        <?php
      }
      if (FILE_LIST_MORE_INFO=='Yes') { 
        ?>
        <td bgcolor="#CCCCCC" class="fileListing_left_header"><?php echo TABLE_HEADING_MORE_INFO; ?></td>
        <?php
      }
      if (FILE_LIST_DATE == 'Yes') {
        ?>
        <td align="center" bgcolor="#CCCCCC" class="fileListing_left_header"><?php echo TABLE_HEADING_DATE; ?></td>
        <?php 
      }
      if (FILE_LIST_SIZE == 'Yes') {
        ?>
        <td align="center" bgcolor="#CCCCCC" class="fileListing_left_header"><?php echo TABLE_HEADING_SIZE; ?></td>
        <?php 
      }
      if (FILE_LIST_DOWNLOADS == 'Yes') { 
        ?>
        <td align="center" bgcolor="#CCCCCC" class="fileListing_left_header"><?php echo TABLE_HEADING_DOWNLOADS; ?></td>
        <?php 
      }
      ?>
    </tr>
    <?php
    require_once(DIR_WS_CLASSES.FILENAME_DOWNLOAD);
    $download = new download();
    while($file_id = tep_db_fetch_array($file_query)) {
      $file_list = tep_db_fetch_array(tep_db_query("SELECT lfd.files_descriptive_name, lf.files_name, lf.files_icon, lf.file_date_created, lf.files_download, lf.file_availability, fi.icon_small 
                                                                         from " . TABLE_LIBRARY_FILES . " lf, 
                                                                                " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, 
                                                                                " . TABLE_FILE_ICONS . " fi 
                                                                       WHERE  lf.files_icon = fi.icon_id 
                                                                         and lf.files_id = lfd.files_id 
                                                                         and lf.files_status = '1' 
                                                                         and lfd.language_id = '" . $languages_id . "' 
                                                                         and lf.files_id = '" . $file_id['files_id'] . "'"));
      ?>
      <tr>
        <?php
        $CDpath_fdm = (isset($_GET['CDpath']) && (int)$_GET['CDpath'] != '') ? $_GET['CDpath'] : '';
        $files_name = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file_list['files_name'];
        if (file_exists($files_name)) {
          $f_size = (int)filesize($files_name);   
          $human_readable_size = cre_resize_bytes($f_size);
          $files_date =  date("m/d/Y", filemtime($files_name));
        }else{
          $human_readable_size = '&nbsp;';
          $files_date = '&nbsp;';
        }         
        if (FILE_LIST_ACTION == 'Yes') { 
          $download->process($file_id['files_id'],$_SESSION['customer_id']); 
          $download_criteria=$download->file_content;
          ?>
          <td class="fileListing_left" align="center">
            <?php
            echo  $download_criteria; 
            $icon = ($file_list['icon_small'] == '') ? tep_draw_separator('pixel_trans.gif', '16', '16') : '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $file_id['files_id'] . '&CDpath=' . $CDpath_fdm) . '">' . tep_image(DIR_WS_IMAGES . 'file_icons/' . $file_list['icon_small']) . '</a>';
            ?>
          </td>
          <?php
        }
        if (FILE_LIST_FILE_NAME == 'Yes') { 
          ?>
          <td class="fileListing_left">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="<?php echo FDM_SMALL_ICON_IMAGE_WIDTH; ?>" align="left"><a href="<?php echo tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $file_id['files_id'] . '&CDpath=' . $CDpath_fdm); ?>"><?php echo $icon; ?></a></td>
                <td class="main" align="left" style="padding-left:2px"><a href="<?php echo tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $file_id['files_id'] . '&CDpath=' . $CDpath_fdm); ?>"><?php echo (($file_list['files_name'] != '') ? $file_list['files_name'] : '&nbsp;'); ?></a></td>
              </tr>
            </table>
          </td>
          <?php 
         }
         if (FILE_LIST_MORE_INFO == 'Yes') {
            echo '<td class="fileListing_left"><a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $file_id['files_id'] . '&CDpath=' . $CDpath_fdm) . '">' . (($file_list['files_descriptive_name'] != '') ? $file_list['files_descriptive_name'] : '&nbsp;')  . '</a></td>';
         } 
         if (FILE_LIST_DATE == 'Yes') { 
           echo '<td class="fileListing_left" align="center">' .  $files_date . '</td>';
         }
         if (FILE_LIST_SIZE == 'Yes') { 
           echo '<td align="center" class="fileListing_left">' . $human_readable_size . '</td>';
         }
         if (FILE_LIST_DOWNLOADS == 'Yes') { 
           echo '<td align="center" class="fileListing_left">' . (($file_list['files_download'] != '') ? $file_list['files_download'] : '&nbsp;') . '</td>';
         }
         ?>
      </tr>
      <?php 
     }
    ?>
  </table>