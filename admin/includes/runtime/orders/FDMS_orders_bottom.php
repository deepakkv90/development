<?php
/*
  $Id: FDMS_orders_bottom.php, v 1.1.1.1 2006/11/28 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $the_customers_id, $oID;
if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') { 
  $rci  = '<tr>' . "\n";
  $rci .= '  <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n"; 
  $rci .= '    </tr>' . "\n"; 
  $rci .= '    <tr>' . "\n"; 
  $rci .= '      <td><a href="' . tep_href_link(FILENAME_CUSTOMER_DOWNLOADS,'cID=' . $the_customers_id, 'SSL') . '">Click here to view All Customer Downloads</a></td>' . "\n"; 
  $rci .= '    </tr>' . "\n"; 
  $rci .= '    <tr>' . "\n"; 
  $rci .= '      <td>' . "\n"; 
  $rci .= '        <table width="100%" cellpadding="3" cellspacing="0" border="1">' . "\n"; 
  $rci .= '          <tr>' . "\n"; 
  $rci .= '            <td  class="smallText"><strong>' . TABLE_HEADING_FILE . '</strong></td>' . "\n"; 
  $rci .= '            <td class="smallText" align="center"><strong>' . TABLE_HEADING_DOWNLOADS . '</strong></td>' . "\n"; 
  $rci .= '          </tr>' . "\n"; 
  // get product_id's for the order
  $current_order_id = (isset($oID) ? (int)$oID : '');  
  $current_customer_id = (isset($the_customers_id) ? (int)$the_customers_id : '');   
  $products = array();
  $products_ordered = array();
  $products_query = tep_db_query("SELECT products_id  
                                    from " . TABLE_ORDERS_PRODUCTS . " 
                                  WHERE orders_id = '" . $current_order_id . "'");
  while ($products = tep_db_fetch_array($products_query)) {
  // check fdm_library_products for attached files
  $files_attached_query = tep_db_query("SELECT lp.library_id, lf.files_name, fi.icon_small    
                                          from " . TABLE_LIBRARY_PRODUCTS . " lp, 
                                               " . TABLE_LIBRARY_FILES . " lf, 
                                               " . TABLE_FILE_ICONS . " fi 
                                        WHERE lp.library_id = lf.files_id 
                                          and lf.files_icon = fi.icon_id 
                                          and lp.products_id = '" . (int)$products['products_id'] . "'");
  while ($files_attached = tep_db_fetch_array($files_attached_query)) {
  // determine download count
  $downloads = tep_db_fetch_array(tep_db_query("SELECT COUNT(*) as cnt 
                                                  from " . TABLE_LIBRARY_FILES_DOWNLOAD . " 
                                                WHERE customers_id ='" . $current_customer_id . "' 
                                                  and files_id = '" . (int)$files_attached['library_id'] . "'"));
  $rci .= '      <tr>' . "\n"; 
  $rci .= '        <td  align="left"><b><a target="_blank" href="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_FILE_DETAIL . '?file_id=' . $files_attached['library_id'] . '">' . tep_image('../images/file_icons/' . $files_attached['icon_small']) . '&nbsp;' . $files_attached['files_name'] . '</a></b></a></td>' . "\n"; 
  $rci .= '        <td align="center">' . $downloads['cnt'] . '</td>' . "\n"; 
  $rci .= '      </tr>' . "\n"; 
                }
            }
  $rci .= '    </table>' . "\n"; 
  $rci .= '  </td>' . "\n"; 
  $rci .= '</tr>' . "\n"; 
  $rci .= '<tr>' . "\n"; 
  $rci .= '  <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n"; 
  $rci .= '</tr>' . "\n"; 
  
  return $rci;
}
?>