<?php
/*
  $Id: product_reviews.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // Eversun mod for sppc and qty price breaks
  if(!isset($_SESSION['sppc_customer_group_id'])) {
    $customer_group_id = 'G';
  } else {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
  // Eversun mod end for sppc and qty price breaks

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, pd.products_name 
                                      from " . TABLE_PRODUCTS . " p, 
                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                      where p.products_id = '" . (int)$_GET['products_id'] . "'
                                        and p.products_status = 1
                                        and p.products_id = pd.products_id
                                        and pd.language_id = " . (int)$languages_id . "
                                        and p.products_group_access like '%". $customer_group_id."%' ");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_REVIEWS));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }

  $pf->loadProduct($product_info['products_id'],$languages_id);
  $products_price = $pf->getPriceStringShort();

  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

  $content = CONTENT_PRODUCT_REVIEWS;
  $javascript = 'popup_window.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
