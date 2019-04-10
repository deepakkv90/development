<?php
/*
  $Id: cds_1showpages_productinfo_bottom.php,v 1.1 2007/07/30 13:41:11 jagdish Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Description:  Display a Page from CDS on the bottom of product_info page.
*/
global $languages_id, $pages;

include_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);

$products_id = (isset($_GET['products_id']) && $_GET['products_id'] != '') ? (int)$_GET['products_id'] : 0; 

$sql = "SELECT ic.categories_id as 'ID', ic.categories_parent_id as 'parentID', ic.categories_image as 'image', icd.categories_name as 'title', icd.categories_blurb as 'blurb', 'c' as 'type'     
          from " . TABLE_CDS_CATEGORIES . " ic 
        LEFT JOIN " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
          on (ic.categories_id = icd.categories_id) 
        WHERE ic.categories_status = '1' 
          and icd.language_id = '" . (int)$languages_id . "'
          and ic.categories_attach_product = '" . $products_id . "' 
        UNION
        SELECT ip.pages_id as 'ID', p2c.categories_id as 'parentID', ip.pages_image as 'image', ipd.pages_title as 'title', ipd.pages_blurb as 'blurb', 'p' as 'type' 
          from " . TABLE_CDS_PAGES . " ip, 
               " . TABLE_CDS_PAGES_DESCRIPTION . " ipd, 
               " . TABLE_CDS_PAGES_TO_CATEGORIES . " p2c 
        WHERE ip.pages_id = ipd.pages_id 
          and ipd.language_id ='" . (int)$languages_id . "'
          and ip.pages_id = p2c.pages_id 
          and ip.pages_status = '1' 
          and ip.pages_attach_product ='" . $products_id . "'";

$result = tep_db_query($sql);
$row = tep_db_num_rows($result);
$rci = '';
if ($row > 0) {   
  $rci .= '<!-- CDS_1showpages_productinfo_bottom //-->' . "\n";
  $rci .= '   <table border="0" width="100%" cellspacing="2" cellpadding="2">' . "\n";
  $rci .= '     <tr>' . "\n";
  $rci .= '       <td class="cds_pInfoContentBoxHeading" colspan="2">' . "\n";
  $rci .=  CDS_TEXT_PAGES;
  $rci .= '       </td>' . "\n";
  $rci .= '     </tr>' . "\n";
  while ($listing = tep_db_fetch_array($result)) {
      if ($listing['type'] == 'c') {
        $this_CDpath = cre_get_cds_category_path($listing['ID']); 
      $link = tep_href_link(FILENAME_CDS_INDEX, '&CDpath=' . $this_CDpath);
        } else {
        $this_CDpath = cre_get_cds_category_path($listing['parentID']); 
      $link = tep_href_link(FILENAME_CDS_INDEX, 'pID=' . (int)$listing['ID'] . '&CDpath=' . $this_CDpath);
        }
    $rci .= '   <tr>' . "\n";
    $rci .= '     <td class="cds_pInfoContentBox" valign="top" align="right">' . "\n";
    $rci .= '       <a href="'. $link.'">' . "\n";
    $rci .= tep_image(DIR_WS_IMAGES . $listing['image'], $listing['title'], CDS_THUMBNAIL_WIDTH, CDS_THUMBNAIL_HEIGHT);
    $rci .= '       </a>' . "\n";
    $rci .= '     </td>' . "\n";
    $rci .= '     <td valign="top" class="cds_pInfoContentBox" >' . "\n";
    $rci .= '       <a href="'. $link .'"><b>' . $listing['title'] . '</b></a><br>' . "\n";
    $rci .= $listing['blurb']; 
    $rci .= '     </td>' . "\n";
    $rci .= '    </tr>' . "\n";
  }  
  $rci .= '    </table>' . "\n";
  $rci .= '<!-- CDS_1showpages_productinfo_bottom eof //-->' . "\n";
} 
?>