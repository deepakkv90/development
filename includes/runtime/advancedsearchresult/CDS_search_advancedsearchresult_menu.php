<?php
/*
  $Id: CDS_search_advancedsearchresult_bottom.php,v 1.0.0.0 2007/03/13 13:41:11 jagdish Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Description:  Display a Page from CDS on the bottom of advanced search result page.
*/

global $languages_id;

$keyword = isset($_GET['keywords']) ? $_GET['keywords'] : '';
if($keyword != ''){
$select_str = "SELECT * ";
$from_str =  "from  " . TABLE_CDS_PAGES . " ip ,
                     " . TABLE_CDS_PAGES_DESCRIPTION . "  ipd ,
                     " . TABLE_CDS_PAGES_TO_CATEGORIES . " ip2c ";
$where_str .= " where (";
$where_str .= "(ipd.pages_title like '%" . $keyword . "%' or ipd.pages_menu_name like '%" . $keyword . "%' or ipd.pages_meta_title like '%" .$keyword . "%' or ipd.pages_meta_keywords like '%". $keyword ."%' or ipd.pages_meta_description like '%". $keyword ."%'";

if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) $where_str .= " or ipd.pages_body like '%" . $keyword . "%'";

$where_str .= ')';
$where_str .= " and  ipd.language_id = '" . $languages_id . "'
                and ip2c.pages_id = ip.pages_id
                    and ip.pages_id = ipd.pages_id and ip.pages_status = '1')";

$listing_str = $select_str . $from_str . $where_str ;

/////////////// GSR //////////

$categories_sql_listing = " SELECT ic.categories_id , icd.categories_name ,ic.categories_image , icd.categories_blurb ,  icd.categories_heading   
        from " . TABLE_CDS_CATEGORIES . " ic 
    LEFT JOIN " . TABLE_CDS_CATEGORIES_DESCRIPTION . " icd 
        on ic.categories_id = icd.categories_id 
    WHERE (icd.categories_name like '%" . $keyword . "%' or icd.categories_description like '%" . $keyword . "%' or icd.categories_heading like '%" . $keyword . "%' or icd.categories_tag_keywords like '%" . $keyword . "%' or icd.  	categories_meta_title like '%" . $keyword . "%' or icd.categories_meta_keywords like '%" . $keyword . "%' or icd.categories_meta_description like '%" . $keyword . "%' ) 
        and icd.language_id = '" . (int)$languages_id . "' and ic.categories_status = '1'   ";
$result = tep_db_query($categories_sql_listing);
$row = tep_db_num_rows($result);

$rci = '';
 $flg = 0;
if ($row > 0) {
  $flg = 1;
  $rci .= '<!-- CDS_search_advancedsearchresult_menu bof //-->' . "\n";
  $rci .= '<tr>' . "\n";
  $rci .= '<td>' . "\n";
  $rci .= '<table border="0" width="100%" cellspacing="2" cellpadding="2">' . "\n";
  $rci .= '<tr>' . "\n";
  $rci .= '<td>' . tep_draw_separator('pixel_trans.gif', '100%', '1') . '</td>' . "\n";
  $rci .= '</tr>' . "\n";
  $rci .= '<tr>' . "\n";
  $rci .= '<td class="infoBoxHeading" colspan="2">' . "\n";
  $rci .= CDS_TEXT_SEARCH_PAGES;
  $rci .= '</td>'. "\n";
  $rci .= '</tr>' ."\n";
  while ($pages = tep_db_fetch_array($result)) {
    
    if(isset($_GET['keywords'])) {      
       $link = tep_href_link(FILENAME_CDS_INDEX, 'CDpath=' . $pages['categories_id'].'&keywords='.$_GET['keywords']);
    } else { 
       $link = tep_href_link(FILENAME_CDS_INDEX, 'CDpath=' . $pages['categories_id']);
    }

    $rci .= '<tr>' . "\n";
    $rci .= '<td class="fieldValue" valign="top" align="right">' . "\n";
    $rci .= '<a href="'. $link.'">' . "\n";
    $rci .= tep_image(DIR_WS_IMAGES . $pages['categories_image'], $pages['categories_heading'], CDS_THUMBNAIL_WIDTH, CDS_THUMBNAIL_HEIGHT);
    $rci .= '</a>' . "\n";
    $rci .= '</td>' . "\n";
    $rci .= '<td valign="top" class="fieldValue" >' . "\n";
    $rci .= '<a href="'.$link .'"><b>' . $pages['categories_heading'] . '</b></a><br>' . "\n";
    $rci .= $pages['categories_blurb'];
    $rci .= '</td>' . "\n";
    $rci .= '</tr>' . "\n";
  }
  $rci .= '</table><br>' . "\n";
  $rci .= '</td>' . "\n";
  $rci .= '</tr>' . "\n";
  $rci .= '<!-- CDS_search_advancedsearchresult_menu eof //-->' . "\n";
}

/////////////// GSR //////////

$result = tep_db_query($listing_str);
$row = tep_db_num_rows($result);
//$rci = '';
if ($row > 0) {
  $rci .= '<!-- CDS_search_advancedsearchresult_menu bof //-->' . "\n";
  $rci .= '<tr>' . "\n";
  $rci .= '<td>' . "\n";
  $rci .= '<table border="0" width="100%" cellspacing="2" cellpadding="2">' . "\n";
  $rci .= '<tr>' . "\n";
  $rci .= '<td>' . tep_draw_separator('pixel_trans.gif', '100%', '1') . '</td>' . "\n";
  $rci .= '</tr>' . "\n";
  if($flg == 0) {
  $rci .= '<tr>' . "\n";
  $rci .= '<td class="infoBoxHeading" colspan="2">' . "\n";
  $rci .= CDS_TEXT_SEARCH_PAGES;
  $rci .= '</td>'. "\n";
  $rci .= '</tr>' ."\n";
}
  while ($pages = tep_db_fetch_array($result)) {
    
    if(isset($_GET['keywords'])) {      
       $link = tep_href_link(FILENAME_CDS_INDEX, 'pID=' . (int)$pages['pages_id'] . '&CDpath=' . $pages['categories_id'].'&keywords='.$_GET['keywords']);
    } else { 
       $link = tep_href_link(FILENAME_CDS_INDEX, 'pID=' . (int)$pages['pages_id'] . '&CDpath=' . $pages['categories_id']);
    }

    $rci .= '<tr>' . "\n";
    $rci .= '<td class="fieldValue" valign="top" align="right">' . "\n";
    $rci .= '<a href="'. $link.'">' . "\n";
    $rci .= tep_image(DIR_WS_IMAGES . $pages['pages_image'], $pages['pages_title'], CDS_THUMBNAIL_WIDTH, CDS_THUMBNAIL_HEIGHT);
    $rci .= '</a>' . "\n";
    $rci .= '</td>' . "\n";
    $rci .= '<td valign="top" class="fieldValue" >' . "\n";
    $rci .= '<a href="'.$link .'"><b>' . $pages['pages_title'] . '</b></a><br>' . "\n";
    $rci .= $pages['pages_blurb'];
    $rci .= '</td>' . "\n";
    $rci .= '</tr>' . "\n";
  }
  $rci .= '</table><br>' . "\n";
  $rci .= '</td>' . "\n";
  $rci .= '</tr>' . "\n";
  $rci .= '<!-- CDS_search_advancedsearchresult_menu eof //-->' . "\n";
}
} else {
  $rci .= '<!-- CDS_search_advancedsearchresult_menu No Keyword //-->' . "\n";
}
?>