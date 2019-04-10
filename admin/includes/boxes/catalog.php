<?php
/*
  $Id: catalog.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_CATALOG,
                   'link'  => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'));
if ($_SESSION['selected_box'] == 'catalog' || MENU_DHTML == 'True') {
  // RCO start
  if ($cre_RCO->get('categories', 'columnleft') !== true) {     
    //RCI to include links  
    $returned_rci_top = $cre_RCI->get('catalog', 'boxestop');
    $returned_rci_bottom = $cre_RCI->get('catalog', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_top .
                                   tep_admin_files_boxes(FILENAME_CATEGORIES, BOX_CATALOG_CATEGORIES_PRODUCTS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_ATTRIBUTES, BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_MULTI, BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_MANUFACTURERS, BOX_CATALOG_MANUFACTURERS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_REVIEWS, BOX_CATALOG_REVIEWS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_SHOPBYPRICE, BOX_CATALOG_SHOP_BY_PRICE, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_XSELL_PRODUCTS, BOX_CATALOG_XSELL_PRODUCTS, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_FEATURED, BOX_CATALOG_FEATURED, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_EXPECTED, BOX_CATALOG_PRODUCTS_EXPECTED, 'SSL','','2') .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_EXTRA_FIELDS,BOX_CATALOG_PRODUCTS_EXTRA_FIELDS, 'SSL','','2') .
								   tep_admin_files_boxes(FILENAME_STOCK_OVERVIEW, BOX_TOOLS_STOCK_OVERVIEW, 'SSL','','2') .
								   tep_admin_files_boxes(FILENAME_QUICK_UPDATES,BOX_CATALOG_QUICK_UPDATES, 'SSL','','2') .
                                   $returned_rci_bottom);                                     
  }
  // RCO eof
}
$box = new box;
echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->