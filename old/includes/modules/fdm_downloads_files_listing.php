<?php
/*
  $Id: fdm_downloads_files_listing.php,v 1.1.1.1 2006/10/04 23:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

if (tep_db_num_rows($file_download) > 0) { 
  ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr class="filesListingCol">
      <td class="fileListing_left_header" width="19%" align="center"><?php echo TABLE_HEADING_ACTION; ?></td>
      <td class="fileListing_left_header" align="left" width="43%"><?php echo TABLE_HEADING_FILE_NAME; ?></td>
      <td class="fileListing_left_header" width="38%"><?php echo TABLE_HEADING_FILE_TITLE; ?></td>
    </tr>
    <?php
    require_once(DIR_WS_CLASSES . FILENAME_DOWNLOAD);
    $download = new download();
    while($file_id = tep_db_fetch_array($file_download)) {
      $download->process($file_id['files_id'], $_SESSION['customer_id']); 
      $download_criteria = $download->file_content; 
      $download_trigger = (defined('TEXT_DOWNLOAD')) ? trim(TEXT_DOWNLOAD) : '';
      if (eregi($download_trigger, $download_criteria)) { 
        ?>
        <tr>
          <td class="fileListing_left" width="19%"  align="center"><?php echo $download_criteria; ?></td>
          <td class="fileListing" width="43%">
            <table width="100%" border="0">
              <tr>
                <td width="5%" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $file_id['files_id']) . '">' . tep_image('images/file_icons/' . $file_id['icon_small']) .'</a>'?></td>
                <td width="95%" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $file_id['files_id']) . '">' . $file_id['files_name'] . '</a>'?></td>
              </tr>
            </table>
          </td>
          <td class="fileListing" width="38%"><?php echo $file_id['files_descriptive_name']; ?></td>
        </tr>
        <?php
      }
    }
    ?>
  </table>
  <?php
}
?>