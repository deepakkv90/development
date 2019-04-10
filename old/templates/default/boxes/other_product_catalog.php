<?php
/*
  $Id: categories3.php,v 1.0.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 

function other_product_catalog($cid, $cpath, $COLLAPSABLE) {
  global $categories_string3, $languages_id, $level, $categories;

  $selectedPath = array();
  // Get all of the categories on this level


 /* Added for Discount pharmacy hide and show on 2nd feb 2012 */
  $category_query_raw = "";

  if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {	
  
		$customer_group_id = tep_get_customers_access_group($_SESSION['customer_id']);	
	  
	  //$customer_group_id = array("G","0");
		
  } else {
  
		$customer_group_id = array("G","0");
		
  }
  
  $category_query_raw .= tep_get_access_sql('c.products_group_access', $customer_group_id);

/* Added for Discount pharmacy hide and show on 2nd feb 2012 /end */

  
  
 /* Default Modified for Discount pharmacy hide and show on 2nd feb 2012 */

 /*if(!$_SESSION['sppc_customer_group_id']) {
    $customer_group_id = 'G';
  } else {
    $customer_group_id = $sppc_customer_group_id;
  }*/
  
 /* Default Modified for Discount pharmacy hide and show on 2nd feb 2012 */

 
  
  $categories_query = tep_db_query("SELECT c.categories_id, cd.categories_name, c.parent_id 
                                      from " . TABLE_CATEGORIES . " c,
                                           " . TABLE_CATEGORIES_DESCRIPTION . " cd 
                                    WHERE c.parent_id = '25' 
                                      and c.categories_id = cd.categories_id 
                                      and cd.language_id='" . $languages_id ."' 
                                    $category_query_raw  order by sort_order, cd.categories_name");
                                      
  while ($categories = tep_db_fetch_array($categories_query))  {
    if ($categories[$level]['parent_id'] == "") { $categories[$level]['parent_id'] = 0; }
    $categories[$level]['categories_id'] = $categories[$level]['parent_id'] + 1;
    // Add category link to $categories_string3
    for ($a=1; $a < $level[$categories]['categories_id']; $a++) {
      $categories_string3 .= "&nbsp;&nbsp;";
    }
	
    $categories_string3 .= '<li><a href="';
    $cPath_new = $cpath;
    if ($categories[$level]['parent_id'] > 0) {
      $cPath_new .= "_";
    }
    $cPath_new .= $categories['categories_id'];
    // added for CDS CDpath support
    $CDpath = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : '';     
    $cPath_new_text = "cPath=" . $cPath_new . $CDpath;
    $categories_string3 .= tep_href_link(FILENAME_DEFAULT, $cPath_new_text);
    $categories_string3 .= '">';
    if ($_GET['cPath']) {
      $selectedPath = split("_", $_GET['cPath']);
    }
    if (in_array($categories['categories_id'], $selectedPath)) { $categories_string3 .= '<b>'; }
    //if ($categories[$level]['categories_id'] == 1) { $categories_string3 .= '<u>'; }
    $categories_string3 .= tep_db_output($categories['categories_name']);
    if ($COLLAPSABLE && tep_has_category_subcategories($categories['categories_id'])) { $categories_string3 .= ' ->'; }
    //if ($categories[$level]['categories_id'] == 1) { $categories_string3 .= '</u>'; }
    if (in_array($categories['categories_id'], $selectedPath)) { $categories_string3 .= '</b>'; }
    $categories_string3 .= '</a></li>';
   
    
    // If I have subcategories, get them and show them
    if (tep_has_category_subcategories($categories['categories_id'])) {
      if ($COLLAPSABLE) {
        if (in_array($categories['categories_id'], $selectedPath)) {
          other_product_catalog($categories['categories_id'], $cPath_new, $COLLAPSABLE);
        }
      } else { 
        other_product_catalog($categories['categories_id'], $cPath_new, $COLLAPSABLE); 
      }
    }
	
  }
  
}
?>
<!-- other product infobox //-->
    <?php
	
    $categories_string3 = '';
    other_product_catalog(0,'',0);
    echo '<div class="box"> <div class="box-content left-links"><ul> ' . $categories_string3 . '</ul></div></div>';
	
   
    ?>
<!-- other product infobox_eof //-->