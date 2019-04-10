<?php
/*
  $Id: fdm_downloads_files_listing.php,v 1.0.0.0 2006/10/10 00:36:42 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

if (tep_db_num_rows($file_query) > 0) {
  ?>
  <table border="1" width="100%" cellspacing="0" cellpadding="2">
    <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent" width="35%"><?php echo TABLE_HEADING_MORE_INFO; ?></td>
      <td class="dataTableHeadingContent"  width="35%"><?php echo TABLE_HEADING_FILE_NAME; ?></td>
      <?php
      if ($_SESSION['fdm_show_log'] == 1) {
        ?>
        <td class="dataTableHeadingContent" width="2%"><?php echo TABLE_HEADING_DOWNLOAD; ?></td>
        <td class="dataTableHeadingContent" width="8%"><?php echo TABLE_HEADING_LOG; ?></td>
        <?php
      }
      ?>
    </tr>
    <?php 
    while($file_id = tep_db_fetch_array($file_query)) {
      if ( ($_SESSION['fdm_show_log'] == 1) || ( ($_SESSION['fdm_show_log'] == 0) && (!in_array($file_id['files_id'], $already_downloaded))) ) {
        $sql_cnt=" SELECT Count(files_id) as cnt_dnload FROM " . TABLE_LIBRARY_FILES_DOWNLOAD . " lfd  where customers_id='" . $cID."' and  lfd.files_id='" . $file_id['files_id']."'";
        $row=mysql_query($sql_cnt);
        $res=mysql_fetch_array($row);
        ?>
          <tr class="dataTableRow">
           <td class="dataTableContent" width="35%"><?php echo $file_id['files_descriptive_name']; ?></td>
            <td class="dataTableContent" width="35%">
              <table width="100%" border="0">
                <tr>
                  <td class="dataTableContent" width="5%"><?php echo '<a href="' . HTTP_SERVER . HTTP_COOKIE_PATH . FILENAME_FILE_DETAIL, '?file_id=' . $file_id['files_id'] .'" target="_blank"><img border="0" src="' . DIR_WS_CATALOG_IMAGES . 'file_icons/' . $file_id['icon_small'] . '"></a>' ?></td>
                  <td class="dataTableContent" width="95%"><?php echo  '<a href="' . HTTP_SERVER . HTTP_COOKIE_PATH . FILENAME_FILE_DETAIL, '?file_id=' . $file_id['files_id'] . '"target="_blank">'.$file_id['files_name'] . '</a>' ?></td>
                </tr>
              </table>
            </td>
            <?php
            if ($_SESSION['fdm_show_log'] == 1) {
              ?>
              <td class="dataTableContent" width="2%"><?php  echo $res['cnt_dnload']; ?></td>
              <td class="dataTableContent" width="8%"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMER_DOWNLOADS_LOG, tep_get_all_get_params(array('cID','file_id','action')) . 'cID=' . $cID . '&action=detail'.'&file_id=' .$file_id['files_id'] ). '">' .TEXT_VIEW_LOG.'</a>'?></td>
              <?php
            }
            ?>
          </tr>
          <?php 
      }
    }
    ?>  
  </table>
  <?php
}
?>