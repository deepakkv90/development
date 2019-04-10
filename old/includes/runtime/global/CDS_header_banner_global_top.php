<?php
/*
  $Id: CDS_header_banner_global_top.php,v 1.0.0.0 2007/06/07 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

include_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);

global $languages_id;

$CDpath = isset($_GET['CDpath']) ? $_GET['CDpath'] : '';
$pID = isset($_GET['pID']) ? $_GET['pID'] : 0;

if (($CDpath == '') && ($pID == '')) return;

$current_cat_id = (int)end(explode('_', $CDpath));
$header_image_array = cre_get_header_banner($CDpath);
$parent_cat_id = isset($header_image_array['id']) ? $header_image_array['id'] : '';
$header_banner = isset($header_image_array['banner']) ? $header_image_array['banner'] : '';

$rci = '';
if ($header_banner != '') {
  $banner_query = tep_db_query("SELECT ic.category_header_banner, icd.categories_heading, ic.category_heading_title_image  
                                  from " . TABLE_PAGES_CATEGORIES . " ic, 
                                                                       " . TABLE_PAGES_CATEGORIES_DESCRIPTION . " icd                    
                                WHERE ic.categories_id = '" . $current_cat_id . "' 
                                                                  and icd.categories_id = ic.categories_id");
  $banner = tep_db_fetch_array($banner_query);

  $rci .= '<!-- CDS_header_banner_global_top //-->' . "\n";
  $rci .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n";
  $rci .= '<tr><td>' . "\n";
  if ($pID != '') {
    $rci .= '<a href="' . FILENAME_PAGES . '?CDpath=' . $CDpath  . '"><div class="cds_header_img">' . tep_image(DIR_WS_IMAGES . $header_banner, $banner['categories_heading']) . '</div></a>' . "\n";
  } else { 
    if (($banner['category_header_banner'] == '') && ($banner['category_heading_title_image'] == '')) {     
      $rci .= '<a href="' . FILENAME_PAGES . '?CDpath=' . $parent_cat_id  . '"><div class="cds_header_img">' . tep_image(DIR_WS_IMAGES . $header_banner, $banner['categories_heading']) . '</div></a>' . "\n";
    }
    $rci .= '</td></tr></table>' . "\n";
    $rci .= '<!-- CDS_header_banner_global_top eof//-->' . "\n";
  }
}
?>