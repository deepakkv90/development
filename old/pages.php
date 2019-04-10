<?php
/*
  $Id: cds_pages.php,v 2.0 2007/07/25 11:21:11 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require_once('includes/application_top.php');
require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CDS_INDEX);
require_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);

// for backwards compatiblity with old pages links
if (isset($_GET['cID']) && ($_GET['cID'] != '')) {   
  tep_redirect(tep_href_link(FILENAME_CDS_PAGE_MANAGER, 'CDpath=' . cre_pages_parse_categories_path($_GET['cID'])));
}

// show when no CDpath and pID are set
$display_top = ( (!isset($_GET['CDpath']) || $_GET['CDpath'] == 0) && (!isset($_GET['pID'])) ) ? true : false;

$CDpath_array = (isset($_GET['CDpath']) && $_GET['CDpath'] != '') ? cre_pages_parse_categories_path($_GET['CDpath']) : array();
$pID = (isset($_GET['pID']) && $_GET['pID'] != '') ? (int)$_GET['pID'] : 0;

if ($display_top == true) {
  $flat_view = (defined('CDS_DEFAULT_TOP_LEVEL_MODE') && CDS_DEFAULT_TOP_LEVEL_MODE == 'Nested') ? false : true;
  $list_columns_text = (defined('CDS_DEFAULT_TOP_LEVEL_COLUMNS')) ? CDS_DEFAULT_TOP_LEVEL_COLUMNS : '1 Normal';
  switch ($list_columns_text) {
    case '1 with Description on Left':
      $listing_columns = 1;
      break;
    case '1 Normal':
      $listing_columns = 2;
      break;
    case '2':
      $listing_columns = 3;
      break;
    case '3':
      $listing_columns = 4;
      break;
    default:
      $listing_columns = 2;
      break;          
  }
  $heading_title = CDS_TEXT_TOP_LEVEL;
  $listing_array = cre_get_listing_array();
} else {
  if (isset($pID) && $pID != 0) {
    $heading_title = cre_get_page_title($pID);
    $heading_image = '';
  } else {
    $heading_image = cre_get_category_heading_image(end($CDpath_array));
    $heading_title = cre_get_category_title(end($CDpath_array));
  }
  $listing_array = cre_get_listing_array(end($CDpath_array));
}

$display_string = '<!-- pages.php $display_string // -->' . "\n";
if (isset($pID) && $pID != '') {
  // check if page exists
  $exists = cre_page_exists($pID);
  if (!$exists) {
    $heading_title = CDS_TEXT_404_ERROR;
    $product_string = '';
    $page_body = CDS_TEXT_NO_PAGES;
  } else {
  // process page string
    $flat_view = true;
    $listing_columns = 2;
    $product_string = cre_get_product_insert($pID);
    $page_body = cre_get_page_body($pID);
    // set ACF filename
    $acf_filename = cre_get_acf_filename($pID);
    if ($acf_filename != '') {
      $checkFilename = DIR_WS_LANGUAGES . $language . '/' . 'pages/' . $acf_filename;
      $acf_file = file_exists($checkFilename) ? $checkFilename : '';
    }
  }
  $display_string .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
  $display_string .= '  <tr>' . "\n";
  $display_string .= '    <td valign="top">' . $product_string . '<div class="cds_pages_body">' . $page_body . '</div></td>' . "\n";
  $display_string .= '  </tr>' . "\n";
  $display_string .= '</table>' . "\n";       
} else {
  // check if category exists
  $exists = cre_category_exists(end($CDpath_array));
  if (!$exists && $display_top == false) {
    $heading_title = CDS_TEXT_404_ERROR;
    $display_string .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
    $display_string .= '  <tr>' . "\n";
    $display_string .= '    <td valign="top"><div class="cds_pages_body">' . CDS_TEXT_NO_CATEGORY . '</div></td>' . "\n";
    $display_string .= '  </tr>' . "\n";
    $display_string .= '</table>' . "\n";     
  } else {
    // process category string
    $descr = cre_get_category_description(end($CDpath_array));
    $listing_columns = cre_get_category_listing_columns(end($CDpath_array));
    $product_string = cre_get_product_insert(end($CDpath_array));               
    $thumbnail = cre_get_category_thumbnail(end($CDpath_array));
    $flat_view = cre_get_category_view(end($CDpath_array));
    $columns = ($listing_columns == 1) ? 1 : $listing_columns-1;
    $cell_width = floor(100 / $columns);
    $display_string .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
    $display_string .= '  <tr>' . "\n";     
    $cols = 0;
    // determine if any pages/categories in this level contain a thumbnail image for indenting purposes
    $has_image = false;
    reset($listing_array);
    while (list($key, $value) = each($listing_array)) {
      if ($value['image'] != '') {
        $has_image = true;
        break;
      }
    } 
    // process primary level
    reset($listing_array);
    while (list($key, $value) = each($listing_array)) {
      $title = cre_build_listing_link($value);
      if ($value['type'] == 'c') {
        $blurb = cre_get_category_blurb($value['ID']);
        $thumbnail = cre_build_listing_link($value, true);
        $display_string .= cre_get_category_display_string();
        $cols++;
        if ($cols >= $columns) {
          $display_string .= '  </tr>' . "\n";
          $display_string .= '  <tr>' . "\n";
          $cols = 0;
        }
        if ($flat_view == true) {         
          // process sub level if flat view
          $sub_listing_array = cre_get_listing_array($value['ID']);         
          // determine if any pages/categories in this level contain a thumbnail image for indenting purposes
          $sub_has_image = false;
          reset($sub_listing_array);
          while (list($subkey, $subvalue) = each($sub_listing_array)) {
            if ($subvalue['image'] != '') {
              $sub_has_image = true;
              break;
            }
          }                 
          reset($sub_listing_array);              
          while (list($subkey, $subvalue) = each($sub_listing_array)) {
            $title = cre_build_listing_link($subvalue);
            if ($subvalue['type'] == 'c') {
              $blurb = cre_get_category_blurb($subvalue['ID']);
              $thumbnail = cre_build_listing_link($subvalue, true);                   
              $display_string .= cre_get_category_display_string();
              $cols++;
            } else {
              $blurb = cre_get_page_blurb($subvalue['ID']);
              $thumbnail = cre_build_listing_link($subvalue, true);
              $display_string .= cre_get_page_display_string();
              $cols++;
            }   
            if ($cols >= $columns) {
              $display_string .= '  </tr>' . "\n";
              $display_string .= '  <tr>' . "\n";
              $cols = 0;
            }
          } // end while2
          // clean the last </tr><tr> from the string
          if (htmlspecialchars(substr($display_string, strlen($display_string)-9)) == htmlspecialchars("</tr><tr>")) {
            $display_string = substr($display_string, 0, strlen($display_string)-9);
          }
        }
      } else {
        $blurb = cre_get_page_blurb($value['ID']);
        $thumbnail = cre_build_listing_link($value, true);
        $display_string .= cre_get_page_display_string();
        $cols++;
      }
      if ($cols >= $columns) {
        $display_string .= '  </tr>' . "\n";
        $display_string .= '  <tr>' . "\n";
        $cols = 0;
      }
    } // end while1
    // clean the last </tr><tr> from the string
    if (htmlspecialchars(substr($display_string, strlen($display_string)-9)) == htmlspecialchars("</tr><tr>")) {
      $display_string = substr($display_string, 0, strlen($display_string)-9);
    } 
    $display_string .= '  </tr>' . "\n";
    $display_string .= '</table>' . "\n"; 
  }
}
$display_string .= '<!-- pages.php $display_string //eof -->' . "\n";

$breadcrumb = new breadcrumb;
//$breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER); // Modified Sep 25, 2010
$breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));
//$breadcrumb->add(CDS_HEADING_TITLE, tep_href_link(FILENAME_CDS_INDEX));  

$i=0;
$override_url = '';
while($i<=sizeof($CDpath_array)) {
  $category_query = tep_db_query("SELECT cd.categories_name, c.categories_url_override
                                  from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CDS_CATEGORIES . " c
                                  WHERE c.categories_id = '" . (int)$CDpath_array[$i] . "' 
                                  AND c.categories_id = cd.categories_id 
                                  AND cd.language_id = '" . (int)$languages_id . "'");
  $category = tep_db_fetch_array($category_query);
  if ($category['categories_name'] != '') {
      if($category['categories_url_override'] != ''){
          $override_url = $category['categories_url_override'];
      } else {
          $override_url = tep_href_link(FILENAME_CDS_INDEX,'CDpath=' . cre_get_cds_category_path((int)$CDpath_array[$i]));
      }
      $breadcrumb->add($category['categories_name'],$override_url);
  }
$i++;
}

if (isset($pID) && $pID != '') {
    $exists = cre_page_exists($pID);
    if ($exists) {
    $breadcrumb->add(cre_get_page_title($pID),tep_href_link(FILENAME_CDS_INDEX,'CDpath=' . $_GET['CDpath'] . '&pID=' . $pID) );
  } 
 }

$content = 'cds_pages';
$javascript = 'cds_pages.js.php';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');

?>