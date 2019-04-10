<?php
/*
  $Id: fdm_file_detail.tpl.php,v 1.0.0.0 2006/10/12 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- fdm_file_detail.tpl.php -->
<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('fdmfiledetail', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php
if($file_check['total'] < 1) {
?>
    <tr>
      <td><?php  new infoBox(array(array('text' => TEXT_FILE_UNAVAILABLE))); ?></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $params) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
 <?php
  } else {
?>
  <tr>
    <td><?php echo tep_draw_form('file_detail', FILENAME_FILE_DETAIL . '?' . tep_get_all_get_params(array('action'))); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <?php
            $file_query = tep_db_query("SELECT lf.files_name, fi.icon_large, fi.icon_small, lf.files_download, lf.file_date_created, lf.file_availability, lfd.files_description ,lfd.files_descriptive_name 
                                          from " . TABLE_LIBRARY_FILES . " lf, 
                                               " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfd, 
                                               " . TABLE_FILE_ICONS . " fi 
                                        WHERE lf.files_icon = fi.icon_id 
                                          and lf.files_status = '1' 
                                          and lf.files_id = '" . (int)$current_file_id . "' 
                                          and lfd.files_id = '" . (int)$current_file_id . "' 
                                          and lfd.language_id = '" . $_SESSION['languages_id'] . "'");

            $file = tep_db_fetch_array($file_query);
            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td align="left" width="<?php echo FDM_LARGE_ICON_IMAGE_WIDTH; ?>" class="pageHeading">
                  <?php
                  echo tep_image(DIR_WS_IMAGES . 'file_icons/' . $file['icon_large']); 
                  ?>
                </td>
                <td class="pageHeading">
                  <?php echo $file['files_descriptive_name']; ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5'); ?></td>
        </tr>
        <?php
        if (MAIN_TABLE_BORDER == 'yes'){
          $heading_text = $heading_text_box ;
          table_image_border_top(false, false, $heading_text);
        }
        $file_list = tep_db_fetch_array(tep_db_query("SELECT files_name, files_icon, files_last_modified, files_download, file_availability 
                                                        from " . TABLE_LIBRARY_FILES . " 
                                                      WHERE files_status = '1' 
                                                        and files_id = '" . (int)$current_file_id . "'"));
        ?>
        <tr>
          <td class="main" valign="top"><?php echo $file['files_description']; ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php
        require_once(DIR_WS_CLASSES . FILENAME_DOWNLOAD);
        $download = new download();
        $download->process($current_file_id,$_SESSION['customer_id']); 
        $download_criteria=$download->file_content;     
        $purchase = trim(TEXT_REQ_PURCHASE);
        $login = trim(TEXT_REQ_LOGIN);
        $unavailable = trim(TEXT_FILE_UNAVAILABLE);
        $var_criteria = trim($download_criteria);
        $criteria_final = strip_tags($var_criteria);
        $files_name = DIR_FS_CATALOG . LIBRARY_DIRECTORY . $file['files_name'];
        if (file_exists($files_name)) {
          $f_size = (int)filesize($files_name);   
          $human_readable_size = cre_resize_bytes($f_size);
          $files_date =  date("m/d/Y", filemtime($files_name));
        }else{
          $human_readable_size = '';
          $files_date = '';
        }
        $download_trigger = (defined('TEXT_DOWNLOAD')) ? trim(TEXT_DOWNLOAD) : '';
        if (eregi($download_trigger, $criteria_final)) {         
        ?>
          <tr>
            <td align="center">
              <A CLASS="buttonDownloadText" style="text-decoration:none" href="<?php echo tep_href_link(FILENAME_DOWNLOAD_FILE, 'fileid=' . (int)$current_file_id, 'SSL'); ?>"><DIV ID="buttonDownload"  onMouseOver="this.className='buttonDownloadOver';"  onMouseOut="this.className='buttonDownloadText';"><br \><?php echo strtoupper(TEXT_DOWNLOAD_BTN); ?><br \>&nbsp;</DIV></A>
            </td>
          </tr>
          <?php
        } else if (eregi($login, $criteria_final)) {
          ?>
          <tr>
            <td align="center">
              <a  CLASS="buttonLoginText" style="text-decoration:none" href="javascript:document.forms.file_detail.submit();"><DIV ID="buttonLogin"  onMouseOver="this.className='buttonLoginOver';"  onMouseOut="this.className='buttonLoginText';"><br \><?php echo TEXT_REQ_LOGIN_BTN; ?><br \>&nbsp;</DIV></A>
            </td>
          </tr>
          <?php
        } else if (eregi($purchase, $criteria_final)) {
          ?>
          <tr>
            <td align="center">
              <SPAN CLASS="buttonReqPText" style="text-decoration:none" ><DIV ID="buttonReqP"  onMouseOver="this.className='buttonReqPOver';"  onMouseOut="this.className='buttonReqPText';"><br \><?php echo TEXT_REQ_PURCHASE_BTN; ?><br \>&nbsp;</DIV></SPAN>
            </td>
          </tr>
          <?php
        } else {
          ?>
          <tr>
            <td align="center">
              <SPAN CLASS="buttonUnavailText" style="text-decoration:none" ><DIV ID="buttonUnavail"  onMouseOver="this.className='buttonUnavailOver';"  onMouseOut="this.className='buttonUnavailText';"><br \><?php echo strtoupper(TEXT_UNAVAIL_BTN); ?><br \>&nbsp;</DIV></SPAN>
            </td>
          </tr>
          <?php
        }
        ?>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <tr>
          <td align="center" valign="top" class="main" width="100%">
            <table border="0" width="50%" cellspacing="0" cellpadding="3">
              <tr>
                <td width="100" class="fileListing_left_header">&nbsp;<?php echo TEXT_FILENAME; ?></td>
                <td class="fileListing_header">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" width="<?php echo FDM_SMALL_ICON_IMAGE_WIDTH; ?>">
                        <?php
                        echo tep_image(DIR_WS_IMAGES . 'file_icons/' . $file['icon_small']); 
                        ?>
                      </td>
                      <td class="main" style="padding-left:4px" align="left">
                        <?php echo $file_list['files_name']; ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td width="100" class="fileListing_left">&nbsp;<?php echo TEXT_DOWNLOADS; ?></td>
                <td class="fileListing" align="left">&nbsp;<?php echo $file_list['files_download']; ?></td>
              </tr>
              <tr>
                <td width="100" class="fileListing_left">&nbsp;<?php echo TEXT_SIZE; ?></td>
                <td class="fileListing" align="left">&nbsp;<?php echo $human_readable_size; ?></td>
              </tr>
              <tr>
                <td width="100" class="fileListing_left">&nbsp;<?php echo TEXT_FILE_DATE; ?></td>
                <td class="fileListing" align="left">&nbsp;<?php echo ($files_date == '') ? '&nbsp;' : $files_date; ?></td>
              </tr>
              <tr>
                <td width="100" class="fileListing_left">&nbsp;<?php echo TEXT_DATE_CREATED; ?></td>
                <td class="fileListing" align="left">&nbsp;<?php echo tep_date_short($file_list['files_last_modified']) == '' ? '&nbsp;' : tep_date_short($file_list['files_last_modified']); ?></td>
              </tr>       
            </table>
          </td>
        </tr>
        <?php
        if($criteria_final == $purchase) {
          ?>
          <tr>
            <td class="fileListingReqP"><?php echo TEXT_REQUIRED_PURCHASES; ?></td>
          </tr>
          <?php
        }
        ?>
        <tr>
          <td>
            <?php
            if($criteria_final == $purchase) {
              $listing_sql = "SELECT lp.products_id, p.products_id, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price 
                                from " . TABLE_LIBRARY_PRODUCTS . " lp 
                              LEFT JOIN " . TABLE_PRODUCTS . " p 
                                on (p.products_id = lp.products_id) 
                              LEFT JOIN " . TABLE_SPECIALS . " s 
                                on (p.products_id = s.products_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                              WHERE lp.library_id ='" . (int)$current_file_id . "' 
                                and pd.products_id = p.products_id 
                                and pd.language_id='" . $_SESSION['languages_id'] . "'"; 
 
              $column_list[] = 'PRODUCT_LIST_IMAGE';
              $column_list[] = 'PRODUCT_LIST_NAME';
              $column_list[] = 'PRODUCT_LIST_PRICE';
              $column_list[] = 'PRODUCT_LIST_BUY_NOW';
              if (FDM_REQ_PRODUCT_CONTENT_LISTING == 'row') {
                include(DIR_WS_MODULES . FILENAME_FILE_DETAIL_LISTING);
              } else {
                include(DIR_WS_MODULES . FILENAME_FILE_DETAIL_LISTING_COL);
              }
            }
            if (MAIN_TABLE_BORDER == 'yes') {
              table_image_border_bottom();
            }
            ?>
          </td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
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
                    <td class="main" align="right"><?php echo tep_draw_hidden_field('action', 'login') . tep_template_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN); ?></td>
                    <?php
                  }
                  ?>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
      </table>
    </form></td>
  </tr>
  <?php
  }
  ?>
</table>
<?php
// RCI code start
echo $cre_RCI->get('fdmfiledetail', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?><!-- fdm_file_detail.tpl.php-eof //-->