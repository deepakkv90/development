<?php
/*
  $Id: CDSLogic_applicationtop_breadcrumb.php,v 1.0.0.0 2009/03/19 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Cool idea to use CDS breadcrumb.
  Example how it works;
  If you pass CDpath and pID to product_info.php, product_info.php?pID=XXX&amp;CDpath=XXX_XXX&amp;products_id=XXX
  your breadcrumb replaced with
  Home >> CDS category >> CDS Sub Category >> CDS Page >> Product
  Simple and cool ;-)
*/
require_once(DIR_WS_FUNCTIONS . FILENAME_CDS_FUNCTIONS);
global $breadcrumb, $languages_id, $cPath_array;

    if (isset($_GET['CDpath']) && $_GET['CDpath'] != '' && $_SERVER['SCRIPT_NAME'] != FILENAME_CDS_INDEX) {
        $CDS_path_array = (isset($_GET['CDpath']) && $_GET['CDpath'] != '') ? cre_pages_parse_categories_path($_GET['CDpath']) : array();
        $i=0;
        $override_url = '';
        while($i<=sizeof($CDS_path_array)) {
            $category_query = tep_db_query("SELECT cd.categories_name, c.categories_url_override
                                      from " . TABLE_CDS_CATEGORIES_DESCRIPTION . " cd, " . TABLE_CDS_CATEGORIES . " c
                                      WHERE c.categories_id = '" . (int)$CDS_path_array[$i] . "' 
                                      AND c.categories_id = cd.categories_id 
                                      and cd.language_id = '" . (int)$languages_id . "'");
            $category = tep_db_fetch_array($category_query);
            
            if ($category['categories_name'] != '') {
                if($category['categories_url_override'] != ''){
                    $override_url = $category['categories_url_override'];
                } else {
                    $override_url = tep_href_link(FILENAME_CDS_INDEX,'CDpath=' . cre_get_cds_category_path((int)$CDS_path_array[$i]));
                }
                $breadcrumb->add($category['categories_name'],$override_url);
            }
    $i++;
    }

    if (isset($_GET['pID']) && $_GET['pID'] != '') {
        $exists = cre_page_exists($_GET['pID']);
        if ($exists) {
            $breadcrumb->add(cre_get_page_title($_GET['pID']),tep_href_link(FILENAME_CDS_INDEX,'CDpath=' . $_GET['CDpath'] . '&pID=' . $_GET['pID']) );
        }
    }
    } else if (!isset($_GET['CDpath'])) { //!isset($_GET['CDpath'])
    $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT)); // Show it is catalog
    // add category names or the manufacturer name to the breadcrumb trail
    if (isset($cPath_array)) {
      for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
        $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
        if (tep_db_num_rows($categories_query) > 0) {
          $categories = tep_db_fetch_array($categories_query);
          $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
        } else {
          break;
        }
      }
    } elseif (isset($_GET['manufacturers_id']) && $_GET['manufacturers_id'] != '') {
      $manufacturers_id = (int)$_GET['manufacturers_id'];
      $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . $manufacturers_id . "'");
      if (tep_db_num_rows($manufacturers_query)) {
        $manufacturers = tep_db_fetch_array($manufacturers_query);
        $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_id));
      }
    }
    }  //!isset($_GET['CDpath'])
?>