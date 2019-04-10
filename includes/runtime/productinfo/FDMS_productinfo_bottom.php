<?php
/*
  $Id: FDMS_productinfo_bottom.php,v 1.1.0.0 2008/03/11 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') { 
echo '<tr><td>';    
if (isset($_GET['products_id'])) {
  $current_products_id = (int)$_GET['products_id'];
  if (isset($_SESSION['languages_id']) && $languages_id == '') { $languages_id = (int)$_SESSION['languages_id']; }
  $sql_query = tep_db_query("SELECT lp.library_id, lf.files_name, lfde.files_descriptive_name, fi.icon_small, lf.files_name 
                                             from " . TABLE_LIBRARY_FILES . " lf, 
                                                    " . TABLE_LIBRARY_FILES_DESCRIPTION . " lfde, 
                                                    " . TABLE_LIBRARY_PRODUCTS . " lp, 
                                                    " . TABLE_FILE_ICONS . " fi 
                                           WHERE lfde.language_id = '" . $languages_id . "' 
                                             and lp.library_id = lf.files_id 
                                             and lf.files_id = lfde.files_id 
                                             and lf.files_icon = fi.icon_id 
                                             and lf.files_status = '1' 
                                             and lp.products_id ='" . $current_products_id . "'
                                           ORDER BY lfde.files_descriptive_name");

  $num_files_related = tep_db_num_rows($sql_query);   
  if($num_files_related > 0) {
    $info_box_contents = array();
    $info_box_contents[] = array('text' => TEXT_RELATED_FILES);

    new contentBoxHeading($info_box_contents);
      
    $info_box_contents = array();
    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TEXT_DOWNLOAD_FILE);

    $info_box_contents[0][] = array('params' => 'class="productListing-heading"',
                                    'text' => TEXT_MORE_INFO);

    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TEXT_FILE_NAME);


    require_once(DIR_WS_CLASSES . FILENAME_DOWNLOAD);
    $download = new download();
    $n = 0;
    while($listing_array=tep_db_fetch_array($sql_query)) {
      $download->process($listing_array['library_id'], $_SESSION['customer_id']); 
      $download_criteria=$download->file_content;   
           
      if (($n/2) == floor($n/2)) {
          $info_box_contents[] = array('params' => 'class="productListing-odd"');
      } else {
          $info_box_contents[] = array('params' => 'class="productListing-even"');
      }
      $cur_row = sizeof($info_box_contents) - 1;
      $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"',
                                             'text' => $download_criteria);

      $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"',
                                             'text' => '<a href="'.tep_href_link(FILENAME_FILE_DETAIL,'file_id='.$listing_array['library_id']).'">' .$listing_array['files_descriptive_name'].'</a>');

      $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"',
                                             'text' => '<a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $listing_array['library_id']) . '">' . tep_image('images/file_icons/' . $listing_array['icon_small']) . '</a> <a href="' . tep_href_link(FILENAME_FILE_DETAIL, 'file_id=' . $listing_array['library_id']) . '">' . $listing_array['files_name'] . '</a>');
                                                 
      $n++;
    }      
    new contentBox($info_box_contents, true, true);
    if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                  );
      new contentBoxFooter($info_box_contents);
    }
  }
  }
  echo '</td></tr>';
}
?>